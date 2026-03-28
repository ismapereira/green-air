# Troubleshooting

## Rotas dão 404 (ex.: `/login`, `/painel`)

### Verifique se você está acessando a URL correta

Se você não configurou VirtualHost/DocumentRoot para `public/`, então é esperado acessar por:

- `http://localhost/.../green-air/public/`

Se você tentar acessar `http://localhost/.../green-air/login` sem apontar o DocumentRoot para `public/`, o Apache vai procurar um diretório/arquivo real e pode retornar 404.

### mod_rewrite e AllowOverride

As rotas dependem do `public/.htaccess`. Garanta:

- `mod_rewrite` habilitado (`LoadModule rewrite_module ...`)
- No `httpd.conf`, o diretório do DocumentRoot deve permitir `.htaccess`:

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
    Require all granted
</Directory>
```

Depois **reinicie o Apache** no painel do XAMPP.

### Teste rápido

Acesse:

- `http://localhost/.../green-air/public/` (deve abrir a home)
- `http://localhost/.../green-air/public/login` (deve abrir login)

## Erro 403 / Token CSRF inválido

Se um formulário retornar erro **"Token de segurança inválido"** (403):

- O token CSRF pode ter expirado se a sessão for perdida.
- Recarregue a página para obter um token novo.
- Verifique se `session.save_path` está configurado e com escrita permitida.
- No XAMPP, problemas de sessão podem ocorrer se o PHP não conseguir escrever em `C:\xampp\tmp\`.

## Rate limiting bloqueou meu login

Se você ver **"Muitas tentativas de login. Tente novamente em X minutos."**:

- Aguarde o tempo indicado (padrão: 15 minutos).
- Alternativamente, limpe a tabela `login_attempts` no banco:

```sql
DELETE FROM login_attempts WHERE email = 'seu@email.com';
```

## Widget de clima mostra erro

### "API key não configurada…"

Defina `OPENWEATHER_API_KEY` no `.env` e recarregue a página.

Veja `docs/CONFIGURATION.md`.

### "Falha ao obter dados do clima"

Possíveis causas:

- API key inválida/revogada
- Bloqueio de saída (firewall/proxy)
- Instabilidade no OpenWeather
- Diretório `storage/cache/` sem permissão de escrita

### Cache de clima desatualizado

O cache de respostas da API tem TTL de 10 minutos. Para forçar atualização, limpe os arquivos em `storage/cache/`:

```bash
rm storage/cache/climate_*.json
```

## Geolocalização não funciona (mapa ou cadastro)

### Permissão do navegador

Verifique se o browser tem permissão para localização no site.

### HTTPS

Em geral, geolocalização exige HTTPS (exceto `localhost`). Em produção, habilite HTTPS.

### Timeout / baixa precisão

O sistema usa `navigator.geolocation.getCurrentPosition(...)` com timeout. Em desktop sem GPS pode falhar dependendo da rede/serviços de localização.

## Endereço não preenche automaticamente

O reverse geocoding é feito no frontend usando Nominatim/OpenStreetMap e é **best-effort**:

- Pode falhar por rate limit do Nominatim
- Pode retornar endereços incompletos

Mesmo assim, o campo "Endereço" é editável manualmente.

## Recuperação de senha não envia e-mail

O projeto usa `mail()` do PHP. Em ambientes locais (XAMPP) normalmente isso não está configurado.

Opções:

- Configurar `sendmail` do XAMPP (via `php.ini` e `sendmail.ini`)
- Usar um SMTP "fake" para desenvolvimento (MailHog/Papercut) e capturar e-mails localmente
- Em produção, o ideal é integrar um provedor SMTP/API (SendGrid, Amazon SES, etc.)

Referência útil (XAMPP + sendmail): `https://stackoverflow.com/questions/15965376/how-to-configure-xampp-to-send-mail-from-localhost/18185233`.

## Dark mode não persiste entre abas

O dark mode é salvo em `localStorage`. Se abrir em aba anônima ou com localStorage desabilitado, a preferência não será mantida.

O sistema também respeita `prefers-color-scheme: dark` do sistema operacional como valor inicial.

## Bottom navigation não aparece no desktop

A bottom navigation é **exclusiva para mobile** (telas com largura ≤ 768px). Em desktop, a navegação é feita pela barra superior (navbar) e pelo footer.

## Upload de imagem falha

Verifique:

- Tamanho do arquivo ≤ 5MB
- Formato aceito: JPEG, PNG ou WebP
- Permissão de escrita em `uploads/trees/` e `uploads/users/`
- `upload_max_filesize` e `post_max_size` no `php.ini` são suficientes
