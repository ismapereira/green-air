-- ============================================================
-- Green Air v2.0 — Migration Script
-- Aplique este script sobre o banco existente (green_air)
-- ============================================================

USE green_air;

-- 1. Adicionar coluna 'role' na tabela users
ALTER TABLE users ADD COLUMN role ENUM('user','moderator','admin') NOT NULL DEFAULT 'user' AFTER photo;

-- Definir admin existente (level_id = 3 / Ouro) como role admin
UPDATE users SET role = 'admin' WHERE email = 'admin@greenair.com';

-- 2. Criar tabela de sugestões de atualização
CREATE TABLE IF NOT EXISTS tree_suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tree_id INT NOT NULL,
    suggestion TEXT NOT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    reviewed_by INT DEFAULT NULL,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tree_id) REFERENCES trees(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 3. Criar tabela de notificações
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Criar tabela de tentativas de login (rate limiting)
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Índices de performance
CREATE INDEX idx_trees_species ON trees(species_id);
CREATE INDEX idx_trees_status ON trees(status_id);
CREATE INDEX idx_trees_user ON trees(user_id);
CREATE INDEX idx_trees_created ON trees(created_at);
CREATE INDEX idx_contributions_user ON contributions_log(user_id);
CREATE INDEX idx_contributions_created ON contributions_log(created_at);
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(user_id, is_read);
CREATE INDEX idx_login_attempts_email ON login_attempts(email, attempted_at);
CREATE INDEX idx_login_attempts_ip ON login_attempts(ip_address, attempted_at);
CREATE INDEX idx_suggestions_tree ON tree_suggestions(tree_id);
CREATE INDEX idx_suggestions_status ON tree_suggestions(status);

-- 6. Limpar tentativas de login antigas (pode ser cron job)
-- DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
