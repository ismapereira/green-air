<h1 align="center"> 🌳 Green Air - Mapeamento Colaborativo de Árvores Urbanas</h1>

<p align="center">
  <img src = "https://img.shields.io/badge/License-MIT-green.svg">
  <img src = "https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white">
  <img src = "https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white">
  <img src = "https://img.shields.io/badge/API-OpenWeather-orange?logo=icloud&logoColor=white">
  <img src = "https://img.shields.io/badge/Map-Leaflet-199900?logo=leaflet&logoColor=white">
  <img src = "https://img.shields.io/badge/Open%20Source-Yes-brightgreen?logo=opensourceinitiative">
  <img src = "https://img.shields.io/badge/Urban%20Ecology-Tree%20Mapping-3CB371?logo=leaflet">
</p>

Aplicação web completa (PHP puro + MySQL + HTML/CSS/JS) para **mapear árvores urbanas** de forma colaborativa: cadastro com foto e GPS, mapa interativo, gamificação por pontos/níveis e painel administrativo com indicadores e gráficos.

## Visão geral

- **Cadastro de árvores** com foto, espécie, status de preservação, tamanho, idade, observações e **geolocalização automática**.
- **Mapa interativo (Leaflet)** com pins e filtros; o mapa tenta **centralizar na localização do usuário** e mostra “Você está aqui”.
- **Dashboard do usuário** com resumo, ranking e **clima + qualidade do ar (OpenWeather)** usando a localização do usuário (com fallback).
- **Admin (nível Ouro)** com CRUDs e indicadores (Chart.js).

## Requisitos

- PHP 7.4+ (recomendado 8.x) com extensões: `PDO`, `pdo_mysql`, `mbstring`, `fileinfo`, `json`
- MySQL 5.7+ ou MariaDB
- Apache com `mod_rewrite` (ou Nginx equivalente)

## Quickstart (XAMPP/Apache)

### 1) Banco de dados

Importe `database.sql` no MySQL (phpMyAdmin ou CLI). Isso cria o banco, tabelas e o usuário admin (se não existir).

```bash
mysql -u root -p < database.sql
```

### 2) Variáveis de ambiente (`.env`)

Crie o `.env` na raiz do projeto:

```bash
cp .env.example .env
```

Preencha pelo menos:
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `OPENWEATHER_API_KEY` (para clima/qualidade do ar)

O arquivo `.env` **não deve ser versionado** (já está no `.gitignore`).

### 3) DocumentRoot / URL

O ponto de entrada é `public/index.php`.

- **Recomendado (produção)**: configure o DocumentRoot para a pasta `public`.
- **Dev no XAMPP**: acesse via URL que aponte para a pasta `public`, por exemplo: `http://localhost/Desenvolvimentos/green-air/public/`.

Se rotas como `/login` retornarem 404, veja `docs/TROUBLESHOOTING.md`.

### 4) Admin

Usuário administrador (nível Ouro):
- **E-mail**: `admin@greenair.com`
- **Senha**: `admin123`

Se você já tinha o banco antes do INSERT, rode:

```bash
php scripts/seed_admin.php
```

## Estrutura do projeto (MVC)

```
public/               # Front controller, assets e .htaccess
  index.php
  assets/
app/
  controllers/
  models/
  views/
config/
  env.php             # Loader do .env + helper env()
  database.php        # PDO
  app.php             # Constantes e autoload
routes/
  web.php             # Tabela de rotas
uploads/
  trees/
  users/
docs/                 # Documentação detalhada
database.sql
```

## Rotas principais

- Público: `/`, `/mapa`
- Auth: `/login`, `/registro`, `/logout`, `/esqueci-senha`, `/redefinir-senha`
- Usuário: `/painel`, `/cadastrar-arvore`, `/minhas-arvores`, `/perfil`, `/ranking`
- Admin (Ouro): `/admin` e `/admin/*`

## APIs internas

- `GET /api/mapa/arvores` — lista árvores para o mapa (com filtros)
- `GET /api/clima?lat=...&lon=...` — clima/umidade/AQI + previsão (usa localização do usuário; fallback para `OPENWEATHER_CITY`)

Detalhes em `docs/API.md`.

## Segurança (resumo)

- Prepared statements (PDO) para evitar SQL injection
- Senhas com `password_hash()`
- Upload de imagens com validação de tamanho/tipo MIME e nomes aleatórios

Detalhes em `docs/SECURITY.md`.

## Documentação

- `docs/INSTALLATION.md` — instalação (XAMPP/Apache/Nginx)
- `docs/CONFIGURATION.md` — `.env` e configurações
- `docs/ARCHITECTURE.md` — MVC, rotas e fluxo da aplicação
- `docs/API.md` — endpoints, parâmetros e exemplos
- `docs/DATABASE.md` — esquema, relacionamentos e seeds
- `docs/SECURITY.md` — decisões e recomendações
- `docs/DEPLOYMENT.md` — deploy e checklist de produção
- `docs/TROUBLESHOOTING.md` — problemas comuns (404, mod_rewrite, geolocalização, e-mail)
