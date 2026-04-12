# Banco de dados

O schema inicial do projeto está em `database.sql`. As migrações estão em `database/migration_v2.sql` e `database/migration_v2.1.sql`.

## Instalação

```bash
# Schema inicial (banco + tabelas + seeds)
mysql -u root -p < database.sql

# Migração v2.0 (novas tabelas + coluna role + índices)
mysql -u root -p green_air < database/migration_v2.sql

# Migração v2.1 (espécie "Não identificada")
mysql -u root -p green_air < database/migration_v2.1.sql
```

## Visão geral (tabelas)

### `user_levels`

Define os níveis do usuário e o mínimo de pontos.

- `id` (PK)
- `name` (Bronze/Prata/Ouro)
- `min_points`

Seeds padrão:

- Bronze: 0
- Prata: 50
- Ouro: 150

### `users`

Usuários do sistema.

- `id` (PK)
- `name`
- `email` (UNIQUE)
- `password` (hash bcrypt)
- `photo` (nome do arquivo em `uploads/users/`)
- `role` (enum: `user`, `moderator`, `admin`) — **novo na v2.0**
- `level_id` (FK → `user_levels.id`)
- `points` (pontuação total)
- `created_at`, `updated_at`

> **Nota v2.0**: A coluna `role` controla o acesso administrativo. O campo `level_id` continua para gamificação (Bronze/Prata/Ouro) mas **não** é mais usado para controle de acesso ao admin.

### `tree_species`

Catálogo de espécies.

- `id` (PK)
- `name`
- `scientific_name` (opcional)

Seeds padrão: Ipê-Amarelo, Pau-Brasil, Jacarandá-Mimoso, Sibipiruna, Quaresmeira.

> **v2.1**: adicionada a espécie **"Não identificada"** (sem nome científico) para árvores cuja espécie o usuário não sabe identificar.

### `tree_status`

Catálogo de status de preservação.

- `id` (PK)
- `name`
- `description` (opcional)

### `trees`

Árvores cadastradas.

- `id` (PK)
- `user_id` (FK → `users.id`)
- `species_id` (FK → `tree_species.id`)
- `status_id` (FK → `tree_status.id`)
- `latitude`, `longitude` (DECIMAL(10,8) / DECIMAL(11,8))
- `address` (best-effort via reverse geocoding; editável)
- `size` (Pequeno/Médio/Grande)
- `age_approx` (INT)
- `observations`
- `photo` (nome do arquivo em `uploads/trees/`)
- `created_at`, `updated_at`

### `contributions_log`

Log de contribuições e pontos concedidos.

- `id` (PK)
- `user_id` (FK → `users.id`)
- `tree_id` (FK → `trees.id`, `ON DELETE SET NULL`)
- `action` (ex.: `ADD_TREE`, `SUGGEST_UPDATE`, `EDIT_TREE`, `SUGGESTION_APPROVED`)
- `points_awarded`
- `created_at`

Esse log alimenta o ranking semanal/mensal (somatório de `points_awarded` no período).

### `settings`

Armazena chaves de configuração do painel admin (`/admin/configuracoes`).

- `setting_key` (UNIQUE)
- `setting_value`

> As credenciais OpenWeather usadas pelo sistema vêm do `.env`. A tabela `settings` armazena parâmetros extras.

### `password_resets`

Tokens de recuperação de senha (válidos por 24h).

- `email`
- `token`
- `created_at`

---

## Tabelas da Migração v2.0

### `tree_suggestions` — **nova**

Sugestões de atualização de árvores (enviadas por usuários Prata+).

- `id` (PK, AUTO_INCREMENT)
- `user_id` (FK → `users.id`, ON DELETE CASCADE)
- `tree_id` (FK → `trees.id`, ON DELETE CASCADE)
- `suggestion` (TEXT) — descrição da sugestão
- `status` (ENUM: `pending`, `approved`, `rejected`, default `pending`)
- `reviewed_by` (FK → `users.id`, nullable)
- `reviewed_at` (DATETIME, nullable)
- `created_at` (TIMESTAMP, default CURRENT_TIMESTAMP)

### `notifications` — **nova**

Notificações internas do usuário.

- `id` (PK, AUTO_INCREMENT)
- `user_id` (FK → `users.id`, ON DELETE CASCADE)
- `type` (VARCHAR 50) — ex.: `welcome`, `suggestion_approved`
- `title` (VARCHAR 255)
- `message` (TEXT)
- `link` (VARCHAR 255, nullable) — URL de destino
- `is_read` (TINYINT 1, default 0)
- `created_at` (TIMESTAMP, default CURRENT_TIMESTAMP)

### `login_attempts` — **nova**

Tracking de tentativas de login para rate limiting.

- `id` (PK, AUTO_INCREMENT)
- `email` (VARCHAR 255)
- `ip_address` (VARCHAR 45)
- `attempted_at` (TIMESTAMP, default CURRENT_TIMESTAMP)

## Índices de performance (v2.0)

```sql
CREATE INDEX idx_trees_species ON trees(species_id);
CREATE INDEX idx_trees_status ON trees(status_id);
CREATE INDEX idx_trees_user ON trees(user_id);
CREATE INDEX idx_trees_created ON trees(created_at);
CREATE INDEX idx_contributions_user ON contributions_log(user_id);
CREATE INDEX idx_contributions_created ON contributions_log(created_at);
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(user_id, is_read);
CREATE INDEX idx_login_attempts_email ON login_attempts(email);
CREATE INDEX idx_login_attempts_ip ON login_attempts(ip_address);
CREATE INDEX idx_suggestions_tree ON tree_suggestions(tree_id);
CREATE INDEX idx_suggestions_status ON tree_suggestions(status);
```

## Diagrama de relacionamentos

```
user_levels 1──N users
users 1──N trees
users 1──N contributions_log
users 1──N notifications
users 1──N tree_suggestions
users 1──N community_suggestions
users 1──N login_attempts
tree_species 1──N trees
tree_status 1──N trees
trees 1──N tree_suggestions
trees 1──N contributions_log
```

### `community_suggestions` (v2.1)

Sugestões colaborativas da comunidade.

- `id` (PK)
- `user_id` (FK → `users.id`, CASCADE)
- `category` (ENUM: `feature`, `species`, `improvement`, `bug`, `other`)
- `title` (VARCHAR 150)
- `description` (TEXT)
- `status` (ENUM: `pending`, `reviewed`, `accepted`, `implemented`, `rejected`)
- `admin_response` (TEXT, nullable)
- `reviewed_by` (FK → `users.id`, SET NULL)
- `reviewed_at` (TIMESTAMP, nullable)
- `created_at`, `updated_at`
- Índices: `idx_status`, `idx_category`, `idx_user`

> **Nota**: Administradores (role = `admin`) são excluídos automaticamente dos rankings e contagens públicas de contribuidores.

### `badges` (v2.2)

Catálogo de conquistas do sistema.

- `id` (PK)
- `slug` (VARCHAR 50, UNIQUE) — identificador único
- `name` (VARCHAR 100)
- `description` (VARCHAR 255)
- `icon` (VARCHAR 50) — classe Bootstrap Icons
- `color` (VARCHAR 30) — cor do badge
- `criteria_type` (VARCHAR 50) — tipo de critério (`trees_count`, `community_suggestions_count`, `distinct_species_count`, `streak_days`, etc.)
- `criteria_value` (INT) — valor necessário para desbloquear

### `user_badges` (v2.2)

Relação M:N entre usuários e conquistas.

- `id` (PK)
- `user_id` (FK → `users.id`, CASCADE)
- `badge_id` (FK → `badges.id`, CASCADE)
- `unlocked_at` (TIMESTAMP)
- UNIQUE KEY: `(user_id, badge_id)`

## Seeds e usuário administrador

O `database.sql` insere:

- níveis em `user_levels`
- algumas espécies em `tree_species`
- alguns status em `tree_status`
- o usuário admin: `admin@greenair.com` (role `admin`, nível Ouro)

Se o admin não existir, rode: `php scripts/seed_admin.php`
