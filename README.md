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

Aplicação web completa (PHP puro + MySQL + Bootstrap 5) para **mapear árvores urbanas** de forma colaborativa: cadastro com foto e GPS, mapa interativo com clusters, gamificação por pontos/níveis, painel de clima expandido e painel administrativo.

## Visão geral

- **Cadastro de árvores** com foto, espécie, status de preservação, tamanho, idade, observações e **geolocalização automática**.
- **Mapa interativo (Leaflet + MarkerCluster)** com filtros por espécie, status e tamanho; centralização automática no GPS do usuário.
- **Dashboard do usuário** com progresso de nível, ranking, **clima completo** (temperatura, umidade, vento, pressão, AQI, poluentes, previsão 5 dias) via OpenWeather.
- **Gamificação** com pontos, níveis (Bronze/Prata/Ouro), ranking semanal/mensal/geral e pódio visual.
- **Sugestões de atualização** de árvores — aprovadas por moderadores com bonificação de pontos.
- **Notificações** internas para o usuário (boas-vindas, sugestões aprovadas, etc.).
- **Painel admin** com dashboard de gráficos, gerenciamento de usuários (com roles), árvores, espécies, status, sugestões e configurações.
- **Design mobile-first** com Bootstrap 5.3, glassmorphism, dark mode e bottom navigation.

## Requisitos

- PHP 7.4+ (recomendado 8.x) com extensões: `PDO`, `pdo_mysql`, `mbstring`, `fileinfo`, `json`
- MySQL 5.7+ ou MariaDB
- Apache com `mod_rewrite` (ou Nginx equivalente)

## Quickstart (XAMPP/Apache)

### 1) Banco de dados

Importe `database.sql` no MySQL (cria banco, tabelas e seeds):

```bash
mysql -u root -p < database.sql
```

Em seguida, aplique a migração v2.0:

```bash
mysql -u root -p green_air < database/migration_v2.sql
```

### 2) Variáveis de ambiente (`.env`)

```bash
cp .env.example .env
```

Preencha pelo menos:
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `OPENWEATHER_API_KEY` (para clima/qualidade do ar/poluentes/previsão)

O arquivo `.env` **não deve ser versionado** (já está no `.gitignore`).

### 3) DocumentRoot / URL

O ponto de entrada é `public/index.php`.

- **Recomendado (produção)**: configure o DocumentRoot para a pasta `public`.
- **Dev no XAMPP**: acesse via `http://localhost/Desenvolvimentos/green-air/public/`.

Se rotas como `/login` retornarem 404, veja `docs/TROUBLESHOOTING.md`.

### 4) Admin

Usuário administrador (role `admin`):
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
    css/style.css     # Design system (Bootstrap 5 + custom)
    js/main.js        # Dark mode, toasts, photo preview
    js/clima.js       # Widget de clima expandido
    js/map.js         # Mapa com MarkerCluster
app/
  controllers/        # Controllers (lógica de fluxo + CSRF)
  models/             # Models (acesso ao banco via PDO)
  helpers/            # UploadHelper, CacheHelper
  views/
    layout/           # header.php (navbar), footer.php (bottom nav)
    auth/             # Login, registro, forgot, reset
    dashboard/        # Painel do usuário
    tree/             # CRUD de árvores + detalhes
    map/              # Mapa interativo
    user/             # Perfil
    ranking/          # Ranking com pódio
    admin/            # Admin layout + todas as views
    errors/           # 404
config/
  env.php             # Loader do .env + helper env()
  database.php        # PDO
  app.php             # Constantes, sessão segura e autoload
routes/
  web.php             # Tabela de rotas
uploads/
  trees/
  users/
storage/
  cache/              # Cache de APIs (file-based)
database/
  migration_v2.sql    # Migração v2.0
docs/                 # Documentação detalhada
database.sql          # Schema inicial
```

## Rotas principais

- **Público**: `/`, `/mapa`, `/arvore/{id}`
- **Auth**: `/login`, `/registro`, `/logout`, `/esqueci-senha`, `/redefinir-senha`
- **Usuário**: `/painel`, `/cadastrar-arvore`, `/minhas-arvores`, `/perfil`, `/ranking`
- **Admin** (role `admin`): `/admin` e `/admin/*` (usuarios, arvores, especies, status, sugestoes, contribuicoes, configuracoes)

## APIs internas

- `GET /api/mapa/arvores` — lista árvores para o mapa (público, com filtros)
- `GET /api/clima?lat=...&lon=...` — clima + AQI + poluentes + previsão 5 dias (requer login; cache 10min)
- `GET /api/notificacoes` — notificações do usuário (requer login)
- `POST /api/notificacoes/ler/{id}` — marcar notificação como lida (requer login + CSRF)

Detalhes em `docs/API.md`.

## Segurança (v2.0)

- **CSRF** em todos os formulários POST (token com `random_bytes(32)` + `hash_equals`)
- **Rate limiting** no login (5 tentativas / 15 min, por email+IP)
- **RBAC** com coluna `role` (user/moderator/admin) substituindo nível Ouro para acesso admin
- **Sessão segura** (httponly, samesite=Lax, strict_mode, regenerate_id no login)
- **Upload centralizado** via `UploadHelper` (validação MIME, tamanho, nomes aleatórios)
- Prepared statements (PDO), `password_hash()`, XSS-safe no mapa (textContent)

Detalhes em `docs/SECURITY.md`.

## Tecnologias (v2.0)

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8.x puro (MVC) |
| Banco | MySQL / MariaDB |
| Frontend | Bootstrap 5.3 + Bootstrap Icons (CDN) |
| Fontes | Inter (Google Fonts) |
| Animações | AOS — Animate On Scroll (CDN) |
| Mapa | Leaflet + MarkerCluster (CDN) |
| Gráficos | Chart.js (CDN, admin) |
| Clima | OpenWeather API (weather + air pollution + forecast) |

## Documentação

- `docs/INSTALLATION.md` — instalação (XAMPP/Apache/Nginx)
- `docs/CONFIGURATION.md` — `.env` e configurações
- `docs/ARCHITECTURE.md` — MVC, rotas, controllers, helpers e fluxo
- `docs/API.md` — endpoints, parâmetros e exemplos expandidos
- `docs/DATABASE.md` — esquema completo (incluindo tabelas v2.0)
- `docs/SECURITY.md` — CSRF, rate limiting, RBAC e recomendações
- `docs/DEPLOYMENT.md` — deploy e checklist de produção
- `docs/TROUBLESHOOTING.md` — problemas comuns
- `docs/CHANGELOG.md` — histórico de alterações
