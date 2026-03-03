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

## Widget de clima mostra erro

### “API key não configurada…”

Defina `OPENWEATHER_API_KEY` no `.env` e recarregue a página.

Veja `docs/CONFIGURATION.md`.

### “Falha ao obter dados do clima”

Possíveis causas:

- API key inválida/revogada
- bloqueio de saída (firewall/proxy)
- instabilidade no OpenWeather

## Geolocalização não funciona (mapa ou cadastro)

### Permissão do navegador

Verifique se o browser tem permissão para localização no site.

### HTTPS

Em geral, geolocalização exige HTTPS (exceto `localhost`). Em produção, habilite HTTPS.

### Timeout / baixa precisão

O sistema usa `navigator.geolocation.getCurrentPosition(...)` com timeout. Em desktop sem GPS pode falhar dependendo da rede/serviços de localização.

## Endereço não preenche automaticamente

O reverse geocoding é feito no frontend usando Nominatim/OpenStreetMap e é **best-effort**:

- pode falhar por rate limit
- pode retornar endereços incompletos

Mesmo assim, o campo “Endereço aproximado” é editável.

## Recuperação de senha não envia e-mail

O projeto usa `mail()` do PHP. Em ambientes locais (XAMPP) normalmente isso não está configurado.

Opções:

- Configurar `sendmail` do XAMPP (via `php.ini` e `sendmail.ini`)
- Usar um SMTP “fake” para desenvolvimento (MailHog/Papercut) e capturar e-mails localmente
- Em produção, o ideal é integrar um provedor SMTP/API (SendGrid, Amazon SES, etc.)

Referência útil (XAMPP + sendmail): `https://stackoverflow.com/questions/15965376/how-to-configure-xampp-to-send-mail-from-localhost/18185233`.

