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
        $this->view('dashboard.index', [
            'user' => $user,
            'currentUser' => $user,
            'myTrees' => $trees,
            'topContributors' => $topContributors
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
                'temp' => null,
                'humidity' => null,
                'aqi' => null,
                'forecast' => [],
                'error' => 'API key não configurada. Defina OPENWEATHER_API_KEY no .env'
            ]);
            return;
        }

        $lat = isset($_GET['lat']) ? (float) $_GET['lat'] : null;
        $lon = isset($_GET['lon']) ? (float) $_GET['lon'] : null;
        $cityFallback = OPENWEATHER_CITY;
        $ctx = stream_context_create(['http' => ['timeout' => 5]]);

        if ($lat !== null && $lon !== null && $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180) {
            $url = 'https://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon . '&appid=' . $key . '&units=metric&lang=pt_br';
        } else {
            $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($cityFallback) . '&appid=' . $key . '&units=metric&lang=pt_br';
        }

        $json = @file_get_contents($url, false, $ctx);
        if ($json === false) {
            $this->json(['error' => 'Falha ao obter dados do clima']);
            return;
        }
        $data = json_decode($json, true);
        if (!$data) {
            $this->json(['error' => 'Resposta inválida da API']);
            return;
        }

        $lat = $data['coord']['lat'] ?? $lat;
        $lon = $data['coord']['lon'] ?? $lon;
        $cityName = $data['name'] ?? $cityFallback;

        $aqi = null;
        if ($lat !== null && $lon !== null) {
            $aqiUrl = 'https://api.openweathermap.org/data/2.5/air_pollution?lat=' . $lat . '&lon=' . $lon . '&appid=' . $key;
            $aqiJson = @file_get_contents($aqiUrl, false, $ctx);
            if ($aqiJson) {
                $aqiData = json_decode($aqiJson, true);
                $aqi = $aqiData['list'][0]['main']['aqi'] ?? null;
            }
        }

        $forecast = [];
        $fcLatLon = ($lat !== null && $lon !== null);
        $fcUrl = $fcLatLon
            ? 'https://api.openweathermap.org/data/2.5/forecast?lat=' . $lat . '&lon=' . $lon . '&appid=' . $key . '&units=metric&cnt=8'
            : 'https://api.openweathermap.org/data/2.5/forecast?q=' . urlencode($cityFallback) . '&appid=' . $key . '&units=metric&cnt=8';
        $fcJson = @file_get_contents($fcUrl, false, $ctx);
        if ($fcJson) {
            $fcData = json_decode($fcJson, true);
            if (!empty($fcData['list'])) {
                foreach (array_slice($fcData['list'], 0, 8) as $f) {
                    $forecast[] = [
                        'dt' => $f['dt'],
                        'temp' => round($f['main']['temp'] ?? 0, 1),
                        'description' => $f['weather'][0]['description'] ?? ''
                    ];
                }
            }
        }

        $this->json([
            'temp' => round($data['main']['temp'] ?? 0, 1),
            'humidity' => $data['main']['humidity'] ?? null,
            'description' => $data['weather'][0]['description'] ?? '',
            'city' => $cityName,
            'aqi' => $aqi,
            'forecast' => $forecast
        ]);
    }
}
