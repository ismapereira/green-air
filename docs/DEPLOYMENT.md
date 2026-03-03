# Deploy (produção)

Este guia descreve recomendações para publicar o Green Air em produção.

## 1) Requisitos de servidor

- Apache (ou Nginx) + PHP 8.x recomendado
- MySQL/MariaDB
- HTTPS (certificado TLS)

## 2) DocumentRoot apontando para `public/`

**Recomendado**: configure o DocumentRoot do seu site para a pasta `public/`.

Motivos:

- Evita expor `config/`, `app/`, `routes/` e outros arquivos do projeto
- Mantém URLs limpas (sem `/public`)

## 3) Variáveis de ambiente

- Crie `.env` na raiz do projeto com credenciais corretas
- Garanta que `.env` não seja acessível via web

Veja `docs/CONFIGURATION.md`.

## 4) Permissões

O PHP precisa escrever em:

- `uploads/trees`
- `uploads/users`

Em Linux, ajuste ownership/permissões conforme o usuário do PHP/Apache.

## 5) Banco de dados

- Importe `database.sql` na primeira instalação
- Faça backups regulares do banco e da pasta `uploads/` (se fotos forem importantes)

## 6) Ajustes recomendados de PHP

No `php.ini` (produção):

- `display_errors=Off`
- `log_errors=On`
- Defina limites coerentes para upload:
  - `upload_max_filesize`
  - `post_max_size`

O app limita no backend `MAX_FILE_SIZE` (5MB).

## 7) HTTPS e sessão

Para produção, configure cookies de sessão de forma segura e habilite HTTPS.

Veja recomendações em `docs/SECURITY.md`.

## 8) Geolocalização

Em produção, a geolocalização tende a funcionar melhor com:

- HTTPS ativo
- Site sem bloqueios de permissão no browser

## 9) Checklist final

- [ ] `.env` preenchido e protegido
- [ ] DocumentRoot → `public/`
- [ ] `mod_rewrite`/rewrite funcionando (rotas não dão 404)
- [ ] `uploads/` com permissão de escrita
- [ ] HTTPS ativo
- [ ] OpenWeather API key válida (`OPENWEATHER_API_KEY`)

