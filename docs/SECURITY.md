# Segurança

Este documento resume as práticas de segurança **implementadas** no Green Air v2.0 e recomendações para produção.

## O que está implementado

### CSRF Protection

- Token CSRF gerado com `random_bytes(32)` e armazenado na sessão (`$_SESSION['csrf_token']`).
- Verificado em todos os endpoints POST via `Controller::validateCsrf()` usando `hash_equals()` (timing-safe).
- O token é enviado como campo oculto `<input type="hidden" name="_csrf">` em todos os formulários.
- Requisições AJAX podem enviar via header `X-CSRF-TOKEN`.
- Em caso de falha, retorna HTTP 403 com mensagem de erro.

### Rate Limiting (Login)

- O model `LoginAttempt` registra cada tentativa de login com email e IP.
- Após **5 tentativas em 15 minutos** (configurável via `LOGIN_MAX_ATTEMPTS` / `LOGIN_LOCKOUT_MINUTES`), o login é bloqueado.
- Após login bem-sucedido, as tentativas anteriores do email são limpas.
- Método `cleanup()` disponível para limpeza periódica de registros antigos.

### RBAC (Role-Based Access Control)

- Coluna `role` na tabela `users` com valores: `user`, `moderator`, `admin`.
- `Controller::requireAdmin()` verifica `role === 'admin'`.
- Substitui o controle anterior baseado em `level_id` (nível Ouro), que agora é usado apenas para gamificação.
- O admin panel (`/admin/*`) é protegido por `requireAdmin()` em todos os controllers.

### Sessão segura

Configurações aplicadas em `config/app.php`:

- `session.cookie_httponly = 1` — impede acesso ao cookie via JavaScript
- `session.cookie_samesite = Lax` — proteção contra CSRF cross-origin
- `session.use_strict_mode = 1` — rejeita IDs de sessão não inicializados
- `session.cookie_secure = 1` — apenas HTTPS (quando disponível)
- `session_regenerate_id(true)` — regenera ID da sessão após login (previne session fixation)
- Nome de sessão customizado: `GREENAIR_SID`

### Autenticação e senhas

- Senhas armazenadas com `password_hash(PASSWORD_DEFAULT)` e verificadas com `password_verify()`.
- Recuperação de senha usa tokens aleatórios (`random_bytes(32)`) com validade de 24 horas.

### SQL injection

- Acesso ao banco via **PDO** com **prepared statements** (placeholders `?`) em todas as queries.
- Nenhuma concatenação direta de dados do usuário em SQL.

### XSS (Cross-Site Scripting)

- Views usam `htmlspecialchars()` para escapar dados dinâmicos.
- Mapa usa `textContent` (DOM API) ao invés de `innerHTML` para prevenir XSS via dados de árvores.
- Header `Content-Type: application/json` em respostas de API.

### Sanitização de entrada

- Campos de formulário passam por `filter_var(FILTER_SANITIZE_EMAIL)`, `FILTER_SANITIZE_SPECIAL_CHARS` e `trim()`.
- Validações de tipo (int, float) com typecasting explícito.

### Upload de imagens

Centralizado no `UploadHelper`:

- Validação de MIME type (`finfo(FILEINFO_MIME_TYPE)`) — aceita apenas JPEG, PNG e WebP
- Limite de tamanho: `MAX_FILE_SIZE` = 5MB
- Nome de arquivo aleatório (`bin2hex(random_bytes(8))`) — evita colisões e path traversal
- Upload em diretórios fora de `public/` (`uploads/`)
- Limpeza automática de arquivo antigo ao atualizar foto (`UploadHelper::deleteOld()`)

### Exposição de `uploads/`

Como `uploads/` fica fora de `public/`, as imagens são servidas por um handler restrito no `public/index.php`, somente para:

- `/uploads/trees/{arquivo}`
- `/uploads/users/{arquivo}`

### Proteção de formulários

- Double-submit protection no frontend (botão desabilitado + spinner por 5 segundos).
- Confirmation dialog antes de ações destrutivas (excluir árvore, usuário, etc.).

## Recomendações para produção

### HTTPS obrigatório

- Geolocalização em browsers modernos exige HTTPS (exceto `localhost`).
- Use HTTPS para proteger sessão, credenciais e cookies.

### Tratamento de erros

- Em produção, desative exibição de erros no PHP (`display_errors=Off`) e registre em logs.
- Não exponha stack traces ao usuário final.

### Proteção do `.env` e `config/`

- O `.env` não deve ser servido pelo servidor web.
- Configure o Apache/Nginx para negar acesso a arquivos sensíveis (`.env`, `config/*`, etc).

### Melhorias futuras sugeridas

- **CAPTCHA** em formulários de cadastro e recuperação de senha.
- **Content Security Policy (CSP)** headers.
- **Honeypot fields** para prevenção de bots.
- **Logs de auditoria** para ações administrativas.
- **2FA** (Two-Factor Authentication) para contas admin.

## Privacidade

- A aplicação armazena coordenadas de árvores cadastradas. Isso é inerente ao objetivo do app.
- Não armazena a localização "do usuário" (a não ser temporariamente no browser para centralizar mapa/clima).
- Fotos de perfil são opcionais.
- Modal de Termos de Uso exibido no cadastro com aceite obrigatório.
