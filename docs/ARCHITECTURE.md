# Arquitetura

O Green Air usa **PHP puro** com um **MVC simples** e um front controller.

## Visão geral do fluxo

1. O Apache reescreve as rotas (mod_rewrite) para `public/index.php`
2. `public/index.php` carrega:
   - `config/env.php` (variáveis do `.env`)
   - `config/database.php` (PDO)
   - `config/app.php` (constantes, autoload, BASE_URL)
3. As rotas são carregadas de `routes/web.php`
4. O front controller resolve:
   - match exato (`GET /mapa`)
   - match com parâmetros (`/arvore/editar/{id}`)
5. O controller executa a action e renderiza uma view em `app/views/*`

## Estrutura de diretórios

```text
public/                 Front controller + assets + .htaccess
app/
  controllers/          Controllers (regras de negócio / fluxo)
  models/               Models (acesso ao banco via PDO)
  views/                Views (templates PHP)
config/                 env.php, database.php, app.php
routes/                 web.php (tabela de rotas)
uploads/                Armazenamento local de imagens
```

## Rotas

As rotas vivem em `routes/web.php` como um array:

- Chave: `"MÉTODO /caminho"`
- Valor: `['NomeDoController', 'metodo']`

Exemplos:

- `GET /mapa` → `MapController::index()`
- `GET /api/mapa/arvores` → `MapController::apiTrees()`
- `GET /api/clima` → `DashboardController::apiClima()`

## Controllers

O controller base (`app/controllers/Controller.php`) fornece:

- `view()` para renderização
- `redirect()` para redirecionamentos respeitando `BASE_PATH`
- `json()` para respostas JSON
- `auth()`, `requireAuth()`, `requireAdmin()` para autenticação/autorizações

### Autorização (resumo)

- Usuário logado: exigido por `requireAuth()`
- Admin (nível Ouro): exigido por `requireAdmin()` (baseado em `level_id`)

## Models e Banco de Dados

Todos os models estendem `app/models/Model.php`, que encapsula o acesso ao PDO:

- `fetchOne`, `fetchAll`, `execute`

O schema do banco é criado por `database.sql`. Detalhes em `DATABASE.md`.

## Uploads

As imagens são salvas em `uploads/` (fora de `public/`).

Para permitir que o navegador carregue as imagens, `public/index.php` tem um handler que “serve”:

- `/uploads/trees/{arquivo}`
- `/uploads/users/{arquivo}`

Isso evita expor todo o diretório `uploads/` diretamente.

## Integrações externas

- **OpenWeather**: clima e AQI via backend (`GET /api/clima`)
- **OpenStreetMap/Nominatim**: reverse geocoding no frontend (best-effort) ao cadastrar árvore (`public/assets/js/geolocation.js`)

