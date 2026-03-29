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

### OpenWeather (clima, qualidade do ar, poluentes, previsão)

Usadas em `config/app.php` e no endpoint `GET /api/clima`:

- `OPENWEATHER_API_KEY` (**obrigatória** para o widget funcionar)
- `OPENWEATHER_CITY` (fallback quando não há latitude/longitude; padrão `São Paulo`)

O widget de clima tenta usar **geolocalização do navegador** e envia `lat/lon` para o backend. Se falhar (permissão negada, timeout, etc), usa `OPENWEATHER_CITY`.

> **APIs utilizadas**: Weather (current), Air Pollution (AQI + poluentes), Forecast 5 days/3h. Todas requerem a mesma API key.

### E-mail (SMTP) — Recuperação de Senha

Usadas pelo `SmtpMailer` (`app/helpers/SmtpMailer.php`):

| Variável | Padrão | Descrição |
|----------|--------|-----------|
| `MAIL_HOST` | `smtp.gmail.com` | Servidor SMTP |
| `MAIL_PORT` | `587` | Porta (587 para TLS, 465 para SSL) |
| `MAIL_USERNAME` | (vazio) | E-mail de autenticação SMTP |
| `MAIL_PASSWORD` | (vazio) | Senha ou App Password |
| `MAIL_FROM_ADDRESS` | (vazio) | E-mail remetente (exibido ao destinatário) |
| `MAIL_FROM_NAME` | `Green Air` | Nome do remetente |
| `MAIL_ENCRYPTION` | `tls` | Tipo de criptografia (`tls` ou `ssl`) |

O e-mail de contato na página de Privacidade também lê de `MAIL_FROM_ADDRESS`.

> **Gmail**: ative a [Verificação em 2 etapas](https://myaccount.google.com/security) e gere uma [Senha de App](https://myaccount.google.com/apppasswords). Use a senha de app (16 caracteres, sem espaços) em `MAIL_PASSWORD`.

## Constantes de `config/app.php`

As seguintes constantes são definidas e podem ser ajustadas:

| Constante | Valor Padrão | Descrição |
|-----------|-------------|-----------|
| `SESSION_LIFETIME` | 7200 | Tempo de sessão em segundos (2h) |
| `SESSION_NAME` | `GREENAIR_SID` | Nome do cookie de sessão |
| `MAX_FILE_SIZE` | 5MB | Tamanho máximo de upload |
| `POINTS_NEW_TREE` | 10 | Pontos por cadastrar árvore |
| `POINTS_SUGGESTION_APPROVED` | 3 | Pontos por sugestão aprovada |
| `CACHE_CLIMATE_TTL` | 600 | TTL do cache de clima (10 min) |
| `LOGIN_MAX_ATTEMPTS` | 5 | Máx. tentativas de login |
| `LOGIN_LOCKOUT_MINUTES` | 15 | Minutos de bloqueio após exceder |

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
- As chaves salvas em `settings` (ex.: `openweather_api_key`) são armazenadas, mas não substituem automaticamente o `.env`.

Se você quiser que o admin controle a chave/cidade, isso exige uma alteração para o backend ler `Setting::get(...)` como fallback.
