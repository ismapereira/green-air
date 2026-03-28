<?php
class DashboardController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAuth();
        $treeModel = new Tree();
        $userModel = new User();
        $trees = $treeModel->byUser($user['id']);
        $topContributors = $userModel->topContributors(5);
        $levelProgress = $userModel->levelProgress($user);

        $this->view('dashboard.index', [
            'user' => $user,
            'currentUser' => $user,
            'myTrees' => $trees,
            'topContributors' => $topContributors,
            'levelProgress' => $levelProgress,
            'totalTreesGlobal' => $treeModel->count(),
            'totalUsersGlobal' => $userModel->count()
        ]);
    }

    public function apiClima(): void
    {
        $user = $this->auth();
        if (!$user) {
            $this->json(['error' => 'Não autorizado']);
            return;
        }

        $key = OPENWEATHER_API_KEY;
        if (!$key) {
            $this->json([
                'error' => 'API key não configurada. Defina OPENWEATHER_API_KEY no .env'
            ]);
            return;
        }

        $lat = isset($_GET['lat']) ? (float) $_GET['lat'] : null;
        $lon = isset($_GET['lon']) ? (float) $_GET['lon'] : null;
        $cityFallback = OPENWEATHER_CITY;

        // Gerar chave de cache baseada na localização (arredondada)
        $cacheKey = 'clima_';
        if ($lat !== null && $lon !== null && $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180) {
            $cacheKey .= round($lat, 2) . '_' . round($lon, 2);
        } else {
            $cacheKey .= md5($cityFallback);
            $lat = null;
            $lon = null;
        }

        // Verificar cache
        $cached = CacheHelper::get($cacheKey);
        if ($cached !== null) {
            $this->json($cached);
            return;
        }

        $ctx = stream_context_create(['http' => ['timeout' => 5]]);

        // 1. Weather atual
        if ($lat !== null && $lon !== null) {
            $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$key}&units=metric&lang=pt_br";
        } else {
            $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($cityFallback) . "&appid={$key}&units=metric&lang=pt_br";
        }

        $json = @file_get_contents($url, false, $ctx);
        if ($json === false) {
            $this->json(['error' => 'Falha ao obter dados do clima']);
            return;
        }
        $data = json_decode($json, true);
        if (!$data || isset($data['cod']) && $data['cod'] != 200) {
            $this->json(['error' => 'Resposta inválida da API']);
            return;
        }

        $lat = $data['coord']['lat'] ?? $lat;
        $lon = $data['coord']['lon'] ?? $lon;
        $cityName = $data['name'] ?? $cityFallback;

        // 2. Air Pollution (PM2.5, PM10, NO2, O3, etc.)
        $aqi = null;
        $pollutants = null;
        if ($lat !== null && $lon !== null) {
            $aqiUrl = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$key}";
            $aqiJson = @file_get_contents($aqiUrl, false, $ctx);
            if ($aqiJson) {
                $aqiData = json_decode($aqiJson, true);
                $aqi = $aqiData['list'][0]['main']['aqi'] ?? null;
                $components = $aqiData['list'][0]['components'] ?? [];
                $pollutants = [
                    'pm2_5' => round($components['pm2_5'] ?? 0, 1),
                    'pm10' => round($components['pm10'] ?? 0, 1),
                    'no2' => round($components['no2'] ?? 0, 1),
                    'o3' => round($components['o3'] ?? 0, 1),
                    'so2' => round($components['so2'] ?? 0, 1),
                    'co' => round($components['co'] ?? 0, 1),
                ];
            }
        }

        // 3. Forecast 5 dias (a cada 3h)
        $forecast = [];
        $dailyForecast = [];
        if ($lat !== null && $lon !== null) {
            $fcUrl = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$key}&units=metric&lang=pt_br";
        } else {
            $fcUrl = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($cityFallback) . "&appid={$key}&units=metric&lang=pt_br";
        }
        $fcJson = @file_get_contents($fcUrl, false, $ctx);
        if ($fcJson) {
            $fcData = json_decode($fcJson, true);
            if (!empty($fcData['list'])) {
                // Próximas 24h (8 intervalos de 3h)
                foreach (array_slice($fcData['list'], 0, 8) as $f) {
                    $forecast[] = [
                        'dt' => $f['dt'],
                        'temp' => round($f['main']['temp'] ?? 0, 1),
                        'feels_like' => round($f['main']['feels_like'] ?? 0, 1),
                        'humidity' => $f['main']['humidity'] ?? null,
                        'description' => $f['weather'][0]['description'] ?? '',
                        'icon' => $f['weather'][0]['icon'] ?? '',
                        'wind_speed' => round($f['wind']['speed'] ?? 0, 1),
                        'pop' => round(($f['pop'] ?? 0) * 100), // probabilidade de chuva %
                    ];
                }
                // Previsão diária (agregar por dia)
                $days = [];
                foreach ($fcData['list'] as $f) {
                    $day = date('Y-m-d', $f['dt']);
                    if (!isset($days[$day])) {
                        $days[$day] = ['temps' => [], 'desc' => '', 'icon' => '', 'pop' => 0];
                    }
                    $days[$day]['temps'][] = $f['main']['temp'];
                    if (date('H', $f['dt']) >= 12 && date('H', $f['dt']) <= 15) {
                        $days[$day]['desc'] = $f['weather'][0]['description'] ?? '';
                        $days[$day]['icon'] = $f['weather'][0]['icon'] ?? '';
                    }
                    $days[$day]['pop'] = max($days[$day]['pop'], round(($f['pop'] ?? 0) * 100));
                }
                foreach (array_slice($days, 0, 5, true) as $date => $d) {
                    $dailyForecast[] = [
                        'date' => $date,
                        'temp_min' => round(min($d['temps']), 1),
                        'temp_max' => round(max($d['temps']), 1),
                        'description' => $d['desc'] ?: ($fcData['list'][0]['weather'][0]['description'] ?? ''),
                        'icon' => $d['icon'] ?: ($fcData['list'][0]['weather'][0]['icon'] ?? ''),
                        'pop' => $d['pop'],
                    ];
                }
            }
        }

        // AQI labels
        $aqiLabels = [1 => 'Bom', 2 => 'Razoável', 3 => 'Moderado', 4 => 'Ruim', 5 => 'Muito Ruim'];
        $aqiColors = [1 => '#22c55e', 2 => '#84cc16', 3 => '#eab308', 4 => '#f97316', 5 => '#ef4444'];

        $result = [
            'temp' => round($data['main']['temp'] ?? 0, 1),
            'feels_like' => round($data['main']['feels_like'] ?? 0, 1),
            'temp_min' => round($data['main']['temp_min'] ?? 0, 1),
            'temp_max' => round($data['main']['temp_max'] ?? 0, 1),
            'humidity' => $data['main']['humidity'] ?? null,
            'pressure' => $data['main']['pressure'] ?? null,
            'visibility' => isset($data['visibility']) ? round($data['visibility'] / 1000, 1) : null,
            'description' => $data['weather'][0]['description'] ?? '',
            'icon' => $data['weather'][0]['icon'] ?? '',
            'city' => $cityName,
            'wind_speed' => round(($data['wind']['speed'] ?? 0) * 3.6, 1), // m/s → km/h
            'wind_deg' => $data['wind']['deg'] ?? null,
            'clouds' => $data['clouds']['all'] ?? null,
            'sunrise' => $data['sys']['sunrise'] ?? null,
            'sunset' => $data['sys']['sunset'] ?? null,
            'aqi' => $aqi,
            'aqi_label' => $aqiLabels[$aqi] ?? 'N/A',
            'aqi_color' => $aqiColors[$aqi] ?? '#6b7280',
            'pollutants' => $pollutants,
            'forecast' => $forecast,
            'daily_forecast' => $dailyForecast,
        ];

        // Salvar no cache
        CacheHelper::set($cacheKey, $result, CACHE_CLIMATE_TTL);

        $this->json($result);
    }

    public function apiNotifications(): void
    {
        $user = $this->requireAuth();
        $notifModel = new Notification();
        $notifications = $notifModel->byUser($user['id'], 20);
        $this->json($notifications);
    }

    public function markNotificationRead(string $id): void
    {
        $user = $this->requireAuth();
        $notifModel = new Notification();
        $notifModel->markAsRead((int)$id, $user['id']);
        $this->json(['ok' => true]);
    }
}
