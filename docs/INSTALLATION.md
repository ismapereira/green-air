# Instalação

Este guia cobre a instalação local (principalmente **XAMPP/Apache**) e as opções de configuração do servidor.

## Pré‑requisitos

- **PHP 7.4+** (recomendado 8.x) com extensões: `PDO`, `pdo_mysql`, `mbstring`, `fileinfo`, `json`
- **MySQL 5.7+** ou **MariaDB**
- **Apache** com `mod_rewrite` habilitado (XAMPP já inclui)

## Instalação local (XAMPP no Windows)

### 1) Colocar o projeto no `htdocs`

Exemplo:

- `C:\xampp\htdocs\Desenvolvimentos\green-air\`

O front controller do app é `public/index.php`.

### 2) Criar o banco e as tabelas

Importe o arquivo `database.sql` no MySQL (via **phpMyAdmin** ou CLI):

```bash
mysql -u root -p < database.sql
```

Isso cria o banco `green_air`, as tabelas e o usuário admin.

### 3) Aplicar migração v2.0

Execute o script de migração que adiciona novas tabelas e colunas:

```bash
mysql -u root -p green_air < database/migration_v2.sql
```

> **Nota**: a migração é idempotente — pode ser executada mais de uma vez sem efeitos colaterais (erros de "duplicate column/key" são esperados na reaplicação).

### 4) Configurar o `.env`

Na raiz do projeto, copie `.env.example` para `.env` e preencha suas credenciais.

Detalhes em `CONFIGURATION.md`.

### 5) Garantir permissões de escrita

O projeto precisa escrever em:

- `uploads/trees/`
- `uploads/users/`
- `storage/cache/`

Em Windows (XAMPP) normalmente funciona sem ajustes. Em Linux, ajuste ownership/permissões conforme o usuário do PHP/Apache.

### 6) Acessar no navegador

#### Opção A (mais simples no XAMPP)

Mantenha o DocumentRoot do Apache em `htdocs` e acesse a pasta `public` pela URL:

- `http://localhost/Desenvolvimentos/green-air/public/`

#### Opção B (recomendado): DocumentRoot apontando para `public/`

Crie um VirtualHost apontando para `...green-air/public`. Assim a URL fica mais limpa (sem `/public`).

## mod_rewrite e `.htaccess` (rotas amigáveis)

As rotas do projeto dependem do rewrite em `public/.htaccess`:

- `public/.htaccess` reescreve tudo para `public/index.php`

Se você ver **404** ao acessar `/login`, `/mapa`, `/painel`, etc:

- Verifique se `mod_rewrite` está habilitado no Apache
- Garanta `AllowOverride All` no diretório do seu projeto/DocumentRoot
- Reinicie o Apache pelo painel do XAMPP

Checklist e passos detalhados em `TROUBLESHOOTING.md`.

## Usuário admin

- **E-mail**: `admin@greenair.com`
- **Senha**: `admin123`
- **Role**: `admin` (acesso ao painel administrativo)

Se por algum motivo o admin não for criado, rode:

```bash
php scripts/seed_admin.php
```

## Dependências externas (CDN)

O projeto carrega as seguintes bibliotecas via CDN (não precisa instalar nada):

- Bootstrap 5.3 + Bootstrap Icons
- Google Fonts (Inter)
- AOS (Animate On Scroll)
- Leaflet + MarkerCluster
- Chart.js (admin)
