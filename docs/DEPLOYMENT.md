# Deploy (produção)

Este guia descreve recomendações para publicar o Green Air em produção.

## 1) Requisitos de servidor

- Apache (ou Nginx) + PHP 8.x recomendado
- MySQL/MariaDB
- HTTPS (certificado TLS)

## 2) DocumentRoot apontando para `public/`

**Recomendado**: configure o DocumentRoot do seu site para a pasta `public/`.

Motivos:

- Evita expor `config/`, `app/`, `routes/`, `storage/` e outros arquivos do projeto
- Mantém URLs limpas (sem `/public`)

## 3) Banco de dados

- Importe `database.sql` na primeira instalação
- Aplique `database/migration_v2.sql` para as tabelas v2.0
- Faça backups regulares do banco e da pasta `uploads/`

## 4) Variáveis de ambiente

- Crie `.env` na raiz do projeto com credenciais corretas
- Garanta que `.env` não seja acessível via web

Veja `docs/CONFIGURATION.md`.

## 5) Permissões

O PHP precisa escrever em:

- `uploads/trees`
- `uploads/users`
- `storage/cache`

Em Linux, ajuste ownership/permissões conforme o usuário do PHP/Apache.

## 6) Ajustes recomendados de PHP

No `php.ini` (produção):

- `display_errors=Off`
- `log_errors=On`
- Defina limites coerentes para upload:
  - `upload_max_filesize`
  - `post_max_size`

O app limita no backend `MAX_FILE_SIZE` (5MB).

## 7) HTTPS e sessão

O Green Air v2.0 já configura:

- `session.cookie_httponly = 1`
- `session.cookie_samesite = Lax`
- `session.use_strict_mode = 1`
- `session.cookie_secure = 1` (quando HTTPS detectado)

Para produção, **HTTPS é obrigatório** tanto para segurança quanto para geolocalização funcionar.

## 8) Geolocalização

Em produção, a geolocalização tende a funcionar melhor com:

- HTTPS ativo
- Site sem bloqueios de permissão no browser

## 9) CDNs

O projeto carrega frameworks via CDN (Bootstrap, Leaflet, etc.). Certifique-se de que o servidor permite conexões de saída para:

- `cdn.jsdelivr.net`
- `unpkg.com`
- `fonts.googleapis.com` / `fonts.gstatic.com`
- `api.openweathermap.org`
- `tile.openstreetmap.org`

## 10) Cache

O diretório `storage/cache/` é usado para caching de respostas de API. Garanta:

- Permissão de escrita
- Limpeza periódica (opcional, pois o `CacheHelper` respeita TTL)

## 11) Checklist final

- [ ] `.env` preenchido e protegido
- [ ] `database.sql` importado
- [ ] `migration_v2.sql` aplicada
- [ ] DocumentRoot → `public/`
- [ ] `mod_rewrite`/rewrite funcionando (rotas não dão 404)
- [ ] `uploads/` com permissão de escrita
- [ ] `storage/cache/` com permissão de escrita
- [ ] HTTPS ativo
- [ ] `display_errors=Off` no `php.ini`
- [ ] OpenWeather API key válida (`OPENWEATHER_API_KEY`)
- [ ] Backup do banco configurado
