# API interna

Esta documentação descreve os endpoints internos usados pelo frontend do Green Air.

## Convenções

- **Base URL**: depende de onde o projeto foi publicado.
  - Ex.: `http://localhost/Desenvolvimentos/green-air/public/`
- Respostas em JSON com `Content-Type: application/json; charset=utf-8`

## `GET /api/mapa/arvores`

Lista árvores para o mapa (público).

### Query params (opcionais)

- `species_id` (int)
- `status_id` (int)
- `size` (string: `Pequeno`, `Médio`, `Grande`)
- `address` (string; busca parcial em `trees.address`)

### Resposta (200)

Array de objetos:

- `id` (int)
- `latitude` (string/decimal)
- `longitude` (string/decimal)
- `address` (string|null)
- `size` (string|null)
- `photo` (string) — nome do arquivo
- `photo_url` (string|null) — URL pronta para `<img>`
- `species_name` (string)
- `status_name` (string)

Exemplo:

```json
[
  {
    "id": 10,
    "latitude": "-23.55050000",
    "longitude": "-46.63330000",
    "address": "Av. Exemplo, Centro, São Paulo, SP",
    "size": "Médio",
    "photo": "tree_1710000000_ab12cd34.jpg",
    "photo_url": "http://localhost/.../uploads/trees/tree_1710000000_ab12cd34.jpg",
    "species_name": "Ipê-Amarelo",
    "status_name": "Saudável"
  }
]
```

## `GET /api/clima`

Retorna clima e qualidade do ar (AQI) para o dashboard. **Requer autenticação** (sessão ativa).

### Query params (opcionais)

- `lat` (float)
- `lon` (float)

Se `lat/lon` forem fornecidos e válidos, o backend consulta o OpenWeather por coordenadas. Caso contrário, usa `OPENWEATHER_CITY`.

### Resposta (200)

Objeto:

- `temp` (number|null) — °C
- `humidity` (number|null) — %
- `description` (string)
- `city` (string)
- `aqi` (number|null) — escala OpenWeather **1..5**
- `forecast` (array) — até 8 itens (3h)

Exemplo:

```json
{
  "temp": 24.2,
  "humidity": 62,
  "description": "céu limpo",
  "city": "São Paulo",
  "aqi": 2,
  "forecast": [
    { "dt": 1710000000, "temp": 24.0, "description": "nuvens dispersas" }
  ]
}
```

### AQI (OpenWeather) — interpretação

O `aqi` segue a escala do OpenWeather:

- `1`: Good
- `2`: Fair
- `3`: Moderate
- `4`: Poor
- `5`: Very Poor

Referência: `https://openweathermap.org/api/air-pollution`.

### Erros comuns

- Sem login: retorna `{ "error": "Não autorizado" }`
- Sem API key configurada: retorna `error` explicando que `OPENWEATHER_API_KEY` deve ser definida no `.env`

