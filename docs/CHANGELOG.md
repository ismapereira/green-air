# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato baseia-se em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [2.0.0] - 2026-03-28 — Redesign Completo + Segurança

### Adicionado

#### Segurança
- **CSRF Protection**: token `random_bytes(32)` em todos os formulários POST, verificado via `hash_equals()`. Aceita header `X-CSRF-TOKEN` para AJAX.
- **Rate Limiting**: model `LoginAttempt` bloqueia login após 5 tentativas em 15 minutos (por email + IP). Configurável via `LOGIN_MAX_ATTEMPTS` / `LOGIN_LOCKOUT_MINUTES`.
- **RBAC**: coluna `role` na tabela `users` (`user`, `moderator`, `admin`). Acesso admin agora é por `role = 'admin'` (não mais pelo nível Ouro).
- **Sessão segura**: `httponly`, `samesite=Lax`, `use_strict_mode`, `session_regenerate_id(true)` no login.

#### Backend
- **`UploadHelper`**: upload centralizado com validação (MIME, tamanho), nomes aleatórios e limpeza de arquivos antigos.
- **`CacheHelper`**: cache file-based para respostas de API com TTL configurável.
- **Model `Notification`**: sistema de notificações do usuário (boas-vindas, sugestão aprovada, etc.). Badge de contagem no navbar.
- **Model `TreeSuggestion`**: persistência de sugestões de atualização com workflow approve/reject.
- **Model `LoginAttempt`**: tracking de tentativas de login para rate limiting.
- **`User::levelProgress()`**: calcula progresso em % para o próximo nível.
- **`User::weeklyRanking()`** e **`User::monthlyRanking()`**: queries movidas do controller para o model.
- **`Tree::countBySpeciesId()`**: dependency check antes de excluir espécies.
- **`AdminSuggestionController`**: CRUD de sugestões com aprovação (dá pontos + notificação ao autor).
- **Endpoint `GET /api/notificacoes`** e **`POST /api/notificacoes/ler/{id}`**.
- **Endpoint `GET /arvore/{id}`**: página pública de detalhes de árvore com mini mapa e compartilhamento.
- **Endpoint `POST /arvore/excluir/{id}`**: exclusão de árvore pelo proprietário.

#### API de Clima Expandida
- **AQI com labels/cores**: Bom, Razoável, Moderado, Ruim, Muito Ruim + barra visual.
- **Poluentes individuais**: PM2.5, PM10, NO₂, O₃, SO₂, CO (via Air Pollution API).
- **Métricas extras**: sensação térmica, pressão, visibilidade, velocidade/direção do vento, nascer/pôr do sol.
- **Previsão 24h**: 8 intervalos de 3 horas com temperatura, ícone e probabilidade de chuva.
- **Previsão 5 dias**: agregação diária com mín/máx e probabilidade de chuva.
- **Cache server-side**: 10 minutos por localização (arredondada).

#### Frontend — Redesign Completo
- **Bootstrap 5.3** + Bootstrap Icons via CDN.
- **Google Fonts (Inter)** para tipografia premium.
- **AOS (Animate On Scroll)** para micro-animações.
- **Design system CSS**: variáveis CSS, glassmorphism, sombras graduais, gradientes.
- **Dark mode toggle**: persistente via localStorage, respeita `prefers-color-scheme`.
- **Bottom navigation mobile**: 5 itens incluindo FAB central elevado para "Cadastrar Árvore".
- **Homepage**: hero com gradiente, stats animadas, seção "Como funciona", features, CTA.
- **Autenticação**: glassmorphism cards com ícones nos inputs.
- **Dashboard**: stat cards com ícones e gradientes, progresso de nível, clima widget expandido, top contribuidores, últimas árvores.
- **Mapa**: fullscreen com filtros inline, MarkerCluster, FAB de geolocalização, modal Bootstrap para detalhes da árvore. XSS-safe (textContent).
- **Minhas Árvores**: grid de cards com foto, edit/view/delete com CSRF.
- **Página de Árvore** (nova): foto grande, info grid, mini mapa Leaflet, Web Share API.
- **Perfil**: hero card com avatar, progresso de nível, formulário de edição, timeline de contribuições.
- **Ranking**: pódio visual (🥇🥈🥉), tabs (Geral/Mês/Semana), highlight do usuário logado.
- **404**: página temática com navegação.
- **Photo upload preview**: preview inline ao selecionar arquivo.
- **Form protection**: bloqueio de duplo-submit com spinner.
- **Toast notifications**: sistema de toasts flutuantes.

#### Admin — Redesign Completo
- **Layout com sidebar dark**: navegação lateral fixa com ícones, responsiva (toggle mobile).
- **Dashboard**: stat cards com gradientes, Chart.js (doughnut por status + bar por espécie), tabelas de contribuidores e bairros, clima widget.
- **Usuários**: role badges coloridos (admin=vermelho, moderator=amarelo), CSRF em todas as ações.
- **Sugestões** (novo): tabela com filtro por status (Pendentes/Aprovadas/Rejeitadas), botões aprovar/rejeitar.
- **Espécies/Status**: inline edit + dependency check antes de excluir.
- **Contribuições**: tabela com log completo.

#### Infraestrutura
- **Migration script** (`database/migration_v2.sql`): adiciona `role`, cria `tree_suggestions`, `notifications`, `login_attempts` + índices de performance.
- **`storage/cache/`**: diretório para cache de API com `.gitignore`.

### Alterado
- `config/app.php`: sessão segura centralizada, autoload de helpers, novas constantes.
- `Controller.php`: CSRF, `requireAdmin()` por role (não mais por level_id), badge de notificações.
- Todos os controllers refatorados para CSRF + UploadHelper.
- `AdminDashboardController`: N+1 query fix (uma query para status counts).
- `routes/web.php`: todas as novas rotas.

### Removido
- Dependência de `level_id` para autorização admin (substituída por `role`).
- CSS Vanilla customizado substituído por Bootstrap 5 + design system.
- JavaScript com innerHTML no mapa (substituído por textContent para segurança XSS).

---

## [Não Lançado]
### Adicionado
- Modal de **Termos de Uso e Política de Privacidade** na página de cadastro.

## [1.0.0] - Nova Estrutura MVC - Marco Zero
### Adicionado
- **Arquitetura Base:** Padrão MVC customizado do zero utilizando nativamente PHP, sem frameworks pesados, garantindo altíssimo desempenho.
- **Roteador:** Motor de rotas amigáveis com front-controller.
- **Autenticação:** Sistema de login seguro protegendo contas e senhas (bcrypt).
- **Banco de Dados Relacional:** Estrutura otimizada para escalabilidade com chaves estrangeiras entre utilizadores, árvores, espécies, sistema de pontos e níveis de usuários (Bronze, Prata, Ouro).
- **Proteção Upload:** Camada protetora em `.htaccess` e indexamento validando tipo MIME em fotos de usuários e de árvores cadastradas.
- **Design Web:** Interface inteiramente nova utilizando Flexbox/CSS Grid, cores vibrantes customizadas sem bibliotecas externas (Vanilla CSS).
- **Dashboard:** Visão geral de árvores por usuário, clima integrado via OpenWeather API e sistema do mapa base.
