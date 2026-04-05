# Arquitetura

O Green Air usa **PHP puro** com um **MVC simples**, front controller e um design system baseado em **Bootstrap 5.3**.

## Visão geral do fluxo

1. O Apache reescreve as rotas (mod_rewrite) via `.htaccess` raiz para `public/index.php`
2. `public/index.php` carrega:
   - `config/env.php` (variáveis do `.env`)
   - `config/database.php` (PDO)
   - `config/app.php` (constantes, sessão segura, autoload de controllers/models/helpers)
3. As rotas são carregadas de `routes/web.php`
4. O front controller resolve:
   - match exato (`GET /mapa`)
   - match com parâmetros (`/arvore/editar/{id}`)
5. O controller executa a action e renderiza uma view em `app/views/*`

## Estrutura de diretórios

```text
.htaccess               Redirecionamento raiz → public/
public/                 Front controller + assets + .htaccess
  favicon.svg           Favicon SVG (ícone de árvore)
  assets/
    css/style.css       Design system (CSS variables, Bootstrap overrides)
    js/main.js          Dark mode, toasts, photo preview, form protection
    js/clima.js         Widget de clima expandido
    js/map.js           Mapa com MarkerCluster (XSS-safe)
    js/geolocation.js   GPS para cadastro de árvores
app/
  controllers/          Controllers (fluxo + CSRF + auth)
  models/               Models (acesso ao banco via PDO)
  helpers/              UploadHelper, CacheHelper, SmtpMailer
  views/
    layout/             header.php (navbar + favicon), footer.php (bottom nav + footer)
    auth/               Login, registro, forgot, reset
    dashboard/          Painel do usuário
    home/               Homepage, termos de uso, política de privacidade
    tree/               Create, edit, my-trees, show
    map/                Mapa interativo
    user/               Perfil
    ranking/            Ranking com pódio
    admin/              Layout admin + todas as views (sidebar dark)
    errors/             404
config/                 env.php, database.php, app.php
routes/                 web.php (tabela de rotas)
uploads/                Armazenamento local de imagens
storage/
  cache/                Cache file-based para API
  logs/                 Logs de erros de e-mail SMTP
database/
  migration_v2.sql      Script de migração v2.0
  migration_v2.1.sql    Espécie "Não identificada" + tabela community_suggestions
```

## Rotas

As rotas vivem em `routes/web.php` como um array:

- Chave: `"MÉTODO /caminho"`
- Valor: `['NomeDoController', 'metodo']`

Exemplos:

- `GET /` → `HomeController::index()`
- `GET /mapa` → `MapController::index()`
- `GET /arvore/{id}` → `TreeController::show()`
- `GET /sugestoes` → `SuggestionController::index()`
- `POST /sugestoes/nova` → `SuggestionController::store()`
- `GET /api/clima` → `DashboardController::apiClima()`
- `GET /admin/comunidade` → `AdminCommunitySuggestionController::index()`
- `POST /admin/comunidade/responder/{id}` → `AdminCommunitySuggestionController::respond()`

## Controllers

O controller base (`app/controllers/Controller.php`) fornece:

### Rendering / Fluxo
- `view($name, $data)` — renderiza view com CSRF token e contagem de notificações
- `redirect($url)` — redirect respeitando `BASE_PATH`
- `json($data)` — resposta JSON

### Autenticação
- `auth()` — retorna usuário logado ou `null`
- `requireAuth()` — redireciona para `/login` se não autenticado
- `requireAdmin()` — redireciona se `role !== 'admin'`

### CSRF
- `csrfToken()` — gera/retorna token na sessão
- `validateCsrf()` — valida token em POST (campo `_csrf` ou header `X-CSRF-TOKEN`)

### Helpers
- `clientIp()` — retorna IP do cliente

## Models e Banco de Dados

Todos os models estendem `app/models/Model.php`, que encapsula o acesso ao PDO:

- `fetchOne`, `fetchAll`, `execute`, `lastInsertId`

**Models disponíveis**:

| Model | Responsabilidade |
|-------|--------------------|
| `User` | Usuários, pontuação, ranking, level progress. `count()` e rankings excluem admins; `countAll()` inclui todos. |
| `Tree` | Árvores (CRUD, contagens, filtros) |
| `TreeSpecies` | Catálogo de espécies |
| `TreeStatus` | Status de preservação |
| `ContributionLog` | Log de contribuições e pontos |
| `Setting` | Configurações do admin |
| `PasswordReset` | Tokens de recuperação de senha |
| `Notification` | Notificações do usuário |
| `TreeSuggestion` | Sugestões de atualização de árvores (legado) |
| `CommunitySuggestion` | Sugestões colaborativas da comunidade (v2.1) |
| `LoginAttempt` | Rate limiting de login |

O schema do banco é criado por `database.sql` + `database/migration_v2.sql` + `database/migration_v2.1.sql`. Detalhes em `DATABASE.md`.

## Helpers

| Helper | Responsabilidade |
|--------|-----------------|
| `UploadHelper` | Upload centralizado: validação MIME/tamanho, nomes aleatórios, limpeza de arquivos antigos |
| `CacheHelper` | Cache file-based em `storage/cache/` com TTL configurável |
| `SmtpMailer` | Envio de e-mails via SMTP (TLS/SSL, AUTH LOGIN) sem dependências externas. Credenciais via `.env` |

## Uploads

As imagens são salvas em `uploads/` (fora de `public/`).

Para permitir que o navegador carregue as imagens, `public/index.php` tem um handler que "serve":

- `/uploads/trees/{arquivo}`
- `/uploads/users/{arquivo}`

Isso evita expor todo o diretório `uploads/` diretamente.

## Frontend (v2.0)

### Frameworks via CDN
- **Bootstrap 5.3** — estrutura responsiva
- **Bootstrap Icons** — iconografia
- **AOS (Animate On Scroll)** — animações de entrada
- **Leaflet + MarkerCluster** — mapa interativo
- **Chart.js** — gráficos no admin
- **Google Fonts (Inter)** — tipografia

### Design System
- Variáveis CSS em `:root` e `[data-bs-theme="dark"]`
- Glassmorphism (`backdrop-filter: blur`)
- Gradientes customizados
- Componentes: glass-card, stat-card, tree-card, clima-card, podium, bottom-nav

### Layout
- **Desktop**: navbar superior com dropdown de perfil + footer
- **Mobile**: navbar compacta + bottom navigation com 5 itens (FAB central)
- **Dark mode**: toggle persistente via localStorage, ícone lua/sol

## Integrações externas

- **OpenWeather API**:
  - Weather (temperatura, umidade, vento, pressão, etc.)
  - Air Pollution (AQI + poluentes individuais)
  - Forecast 5 days/3h (previsão horária e diária)
- **OpenStreetMap/Nominatim**: reverse geocoding no frontend (best-effort) ao cadastrar árvore
- **Gmail SMTP**: envio de e-mails transacionais (recuperação de senha) via `SmtpMailer`
