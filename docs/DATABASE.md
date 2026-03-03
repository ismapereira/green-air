# Banco de dados

O schema do projeto está no arquivo `database.sql` e cria o banco `green_air` com as tabelas usadas pelo sistema.

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
- `password` (hash)
- `photo` (nome do arquivo em `uploads/users/`)
- `level_id` (FK → `user_levels.id`)
- `points` (pontuação total)
- `created_at`, `updated_at`

> Observação: a pontuação é armazenada diretamente em `users.points` (não existe uma tabela `user_points` separada).

### `tree_species`

Catálogo de espécies.

- `id` (PK)
- `name`
- `scientific_name` (opcional)

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
- `latitude`, `longitude`
- `address` (best-effort via reverse geocoding; editável)
- `size` (Pequeno/Médio/Grande)
- `age_approx`
- `observations`
- `photo` (nome do arquivo em `uploads/trees/`)
- `created_at`, `updated_at`

### `contributions_log`

Log de contribuições e pontos concedidos.

- `id` (PK)
- `user_id` (FK → `users.id`)
- `tree_id` (FK → `trees.id`, `ON DELETE SET NULL`)
- `action` (ex.: `ADD_TREE`, `SUGGEST_UPDATE`, `EDIT_TREE`)
- `points_awarded`
- `created_at`

Esse log alimenta o ranking semanal/mensal (somatório de `points_awarded` no período).

### `settings`

Armazena chaves de configuração do painel admin (`/admin/configuracoes`).

- `setting_key` (UNIQUE)
- `setting_value`

> Observação: hoje as configurações críticas (DB/OpenWeather) vêm do `.env`. A tabela `settings` é usada para armazenar parâmetros extras definidos no admin.

### `sessions`

Tabela prevista para armazenamento de sessões no banco.

> Observação: a aplicação usa a sessão padrão do PHP (filesystem) e **não** configura `session.save_handler` para usar esta tabela. Ela pode ser usada em evoluções futuras.

### `password_resets`

Tokens de recuperação de senha (válidos por 24h).

- `email`
- `token`
- `created_at`

## Seeds e usuário administrador

O `database.sql` insere:

- níveis em `user_levels`
- algumas espécies em `tree_species`
- alguns status em `tree_status`
- o usuário admin: `admin@greenair.com` (nível Ouro)

Se o admin não existir, você também pode rodar `php scripts/seed_admin.php`.

