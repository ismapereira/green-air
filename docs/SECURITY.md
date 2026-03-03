# Segurança

Este documento resume as práticas de segurança **implementadas** e recomendações para produção.

## O que já está implementado

### Autenticação e senhas

- Senhas são armazenadas com `password_hash()` e verificadas com `password_verify()`.
- Recuperação de senha usa tokens aleatórios (`random_bytes`) com validade de 24 horas.

### SQL injection

- Acesso ao banco via **PDO** com **prepared statements** (evita interpolação direta de dados do usuário).

### Sanitização básica de entrada

- Campos de formulário passam por `filter_var(...)` e `trim()` em pontos críticos (ex.: nome, endereço, observações).

### Upload de imagens

- Validação de MIME type (`finfo(FILEINFO_MIME_TYPE)`)
- Limite de tamanho (`MAX_FILE_SIZE` = 5MB)
- Nome de arquivo aleatório (evita colisões e path traversal)
- Upload em diretórios fora de `public/` (`uploads/`)

### Exposição de `uploads/`

Como `uploads/` fica fora de `public/`, as imagens são servidas por um handler restrito no `public/index.php`, somente para:

- `/uploads/trees/{arquivo}`
- `/uploads/users/{arquivo}`

## Recomendações para produção

### HTTPS obrigatório

- Geolocalização em browsers modernos exige HTTPS (exceto `localhost`).
- Use HTTPS para proteger sessão e credenciais.

### Cookies de sessão

Para produção, considere:

- `session.cookie_secure=1` (somente HTTPS)
- `session.cookie_httponly=1`
- `session.cookie_samesite=Lax` (ou `Strict` dependendo do fluxo)

### Tratamento de erros

- Em produção, desative exibição de erros no PHP e registre em logs.
- Não exponha stack traces ao usuário final.

### Proteção do `.env` e `config/`

- O `.env` não deve ser servido pelo servidor web.
- Configure o Apache/Nginx para negar acesso a arquivos sensíveis (`.env`, `config/*`, etc).

### Rate limiting e antifraude

O projeto não implementa rate limiting/captcha. Se publicado publicamente:

- Limite tentativas de login
- Proteja formulários de recuperação de senha e cadastro
- Considere logs e alertas

## Privacidade

- A aplicação armazena coordenadas de árvores cadastradas. Isso é inerente ao objetivo do app.
- Não armazena a localização “do usuário” (a não ser temporariamente no browser para centralizar mapa/clima).

