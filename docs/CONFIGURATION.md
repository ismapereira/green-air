# Configuração

Este projeto usa um arquivo `.env` na **raiz** para armazenar configurações de ambiente (banco, API externa, etc). Ele é carregado por `config/env.php`.

## `.env` e `.env.example`

- Use o arquivo `.env.example` como base
- Crie/edite o arquivo `.env` na raiz do projeto
- O `.env` **não deve ser versionado** (já está no `.gitignore`)

## Variáveis suportadas

### `APP_ENV`

Define o ambiente (atualmente informativo).

- Ex.: `APP_ENV=development` ou `APP_ENV=production`

### Banco de dados

Usadas em `config/database.php`:

- `DB_HOST` (padrão `localhost`)
- `DB_NAME` (padrão `green_air`)
- `DB_USER` (padrão `root`)
- `DB_PASS` (padrão vazio)

### OpenWeather (clima e qualidade do ar)

Usadas em `config/app.php` e no endpoint `GET /api/clima`:

- `OPENWEATHER_API_KEY` (**obrigatória** para o widget funcionar)
- `OPENWEATHER_CITY` (fallback quando não há latitude/longitude; padrão `São Paulo`)

O widget de clima tenta usar **geolocalização do navegador** e envia `lat/lon` para o backend. Se falhar (permissão negada, timeout, etc), usa `OPENWEATHER_CITY`.

## Formato e aspas

O loader (`config/env.php`) aceita:

- Linhas `CHAVE=valor`
- Comentários com `#`
- Valores com aspas simples ou duplas (as aspas externas são removidas)

Exemplo válido:

```ini
OPENWEATHER_API_KEY="minha_chave"
OPENWEATHER_CITY='São Paulo'
```

## Configurações no painel Admin vs `.env`

Existe uma tela de admin em `/admin/configuracoes` que salva pares chave/valor na tabela `settings`.

Importante:

- **As credenciais do OpenWeather usadas pelo sistema vêm do `.env`** (`OPENWEATHER_API_KEY` e `OPENWEATHER_CITY`).
- As chaves salvas em `settings` (ex.: `openweather_api_key`) hoje são armazenadas, mas não substituem automaticamente o `.env`.

Se você quiser que o admin controle a chave/cidade, isso exige uma pequena alteração para o backend ler `Setting::get(...)` como fallback.

