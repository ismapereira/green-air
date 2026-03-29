# API interna

Esta documentação descreve os endpoints internos usados pelo frontend do Green Air.

## Convenções

- **Base URL**: depende de onde o projeto foi publicado.
  - Ex.: `http://localhost/Desenvolvimentos/green-air/`
- Respostas em JSON com `Content-Type: application/json; charset=utf-8`
- Endpoints que requerem autenticação verificam a sessão PHP ativa.
- Endpoints POST requerem token CSRF via campo `_csrf` ou header `X-CSRF-TOKEN`.

---

## `GET /api/mapa/arvores`

Lista árvores para o mapa (**público**, não requer login).

### Query params (opcionais)

- `species_id` (int) — filtrar por espécie
- `status_id` (int) — filtrar por status
- `size` (string: `Pequeno`, `Médio`, `Grande`)
- `address` (string; busca parcial em `trees.address`)

### Resposta (200)

Array de objetos:

```json
[
  {
    "id": 10,
    "latitude": "-23.55050000",
    "longitude": "-46.63330000",
    "address": "Av. Exemplo, Centro, São Paulo, SP",
    "size": "Médio",
    "photo": "tree_1710000000_ab12cd34.jpg",
    "species_name": "Ipê-Amarelo",
    "status_name": "Saudável",
    "user_name": "João Silva",
    "observations": "Próximo ao ponto de ônibus",
    "age_approx": 15
  }
]
```

---

## `GET /api/clima`

Retorna clima completo, qualidade do ar, poluentes e previsão. **Requer autenticação** (sessão ativa).

### Query params (opcionais)

- `lat` (float) — latitude
- `lon` (float) — longitude

Se `lat/lon` forem fornecidos e válidos, o backend consulta o OpenWeather por coordenadas. Caso contrário, usa `OPENWEATHER_CITY` do `.env`.

> **Cache**: respostas são cacheadas por **10 minutos** por localização (arredondada a 2 casas decimais) via `CacheHelper`.

### Resposta (200)

```json
{
  "temp": 24.2,
  "feels_like": 25.1,
  "temp_min": 22.0,
  "temp_max": 27.5,
  "humidity": 62,
  "pressure": 1013,
  "visibility": 10.0,
  "description": "céu limpo",
  "icon": "01d",
  "city": "São Paulo",
  "wind_speed": 12.5,
  "wind_deg": 180,
  "clouds": 20,
  "sunrise": 1710750000,
  "sunset": 1710793200,
  "aqi": 2,
  "aqi_label": "Razoável",
  "aqi_color": "#84cc16",
  "pollutants": {
    "pm2_5": 12.3,
    "pm10": 18.7,
    "no2": 25.1,
    "o3": 45.2,
    "so2": 3.1,
    "co": 280.5
  },
  "forecast": [
    {
      "dt": 1710800000,
      "temp": 24.0,
      "feels_like": 24.8,
      "humidity": 60,
      "description": "nuvens dispersas",
      "icon": "03d",
      "wind_speed": 10.2,
      "pop": 15
    }
  ],
  "daily_forecast": [
    {
      "date": "2026-03-28",
      "temp_min": 19.5,
      "temp_max": 28.2,
      "description": "céu limpo",
      "icon": "01d",
      "pop": 10
    }
  ]
}
```

### Campos de resposta

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `temp` | float | Temperatura atual (°C) |
| `feels_like` | float | Sensação térmica (°C) |
| `temp_min` / `temp_max` | float | Mín/máx do dia (°C) |
| `humidity` | int | Umidade relativa (%) |
| `pressure` | int | Pressão atmosférica (hPa) |
| `visibility` | float | Visibilidade (km) |
| `description` | string | Descrição do clima (pt-br) |
| `icon` | string | Código do ícone OpenWeather |
| `city` | string | Nome da cidade |
| `wind_speed` | float | Velocidade do vento (km/h) |
| `wind_deg` | int | Direção do vento (graus) |
| `clouds` | int | Cobertura de nuvens (%) |
| `sunrise` / `sunset` | int | Unix timestamp |
| `aqi` | int (1–5) | Air Quality Index OpenWeather |
| `aqi_label` | string | Label traduzido (Bom/Razoável/Moderado/Ruim/Muito Ruim) |
| `aqi_color` | string | Cor hex para visualização |
| `pollutants` | object | Poluentes individuais (µg/m³) |
| `forecast` | array | Até 8 intervalos de 3h |
| `daily_forecast` | array | Até 5 dias (agregação diária) |

### AQI (OpenWeather) — interpretação

| Valor | Label | Cor |
|-------|-------|-----|
| 1 | Bom | `#22c55e` |
| 2 | Razoável | `#84cc16` |
| 3 | Moderado | `#eab308` |
| 4 | Ruim | `#f97316` |
| 5 | Muito Ruim | `#ef4444` |

### Erros comuns

- Sem login: `{ "error": "Não autorizado" }`
- Sem API key: `{ "error": "API key não configurada..." }`
- API indisponível: `{ "error": "Falha ao obter dados do clima" }`

---

## `GET /api/notificacoes`

Lista notificações do usuário logado (últimas 20). **Requer autenticação**.

### Resposta (200)

```json
[
  {
    "id": 1,
    "user_id": 2,
    "type": "welcome",
    "title": "Bem-vindo ao Green Air!",
    "message": "Comece cadastrando sua primeira árvore e ganhe pontos.",
    "link": "/cadastrar-arvore",
    "is_read": 0,
    "created_at": "2026-03-28 18:30:00"
  }
]
```

---

## `POST /api/notificacoes/ler/{id}`

Marca uma notificação como lida. **Requer autenticação + CSRF**.

### Headers

- `X-CSRF-TOKEN` (string) — token CSRF

### Resposta (200)

```json
{ "ok": true }
```
