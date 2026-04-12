-- Migration: Green Air v2.2
-- Data: 2026-04-05
-- Conquistas, Badges

USE green_air;

-- ============================================================
-- Sistema de Conquistas (Badges)
-- ============================================================
CREATE TABLE IF NOT EXISTS badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    icon VARCHAR(50) NOT NULL DEFAULT 'bi-award',
    color VARCHAR(30) NOT NULL DEFAULT '#10B981',
    criteria_type VARCHAR(50) NOT NULL,
    criteria_value INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE,
    UNIQUE KEY uk_user_badge (user_id, badge_id),
    INDEX idx_user (user_id)
);

-- Seeds: Conquistas iniciais
INSERT INTO badges (slug, name, description, icon, color, criteria_type, criteria_value) VALUES
    ('first_tree', 'Primeira Árvore', 'Cadastrou sua primeira árvore na plataforma', 'bi-tree', '#10B981', 'trees_count', 1),
    ('5_trees', 'Plantador', 'Cadastrou 5 árvores', 'bi-tree-fill', '#059669', 'trees_count', 5),
    ('10_trees', 'Guardião Verde', 'Cadastrou 10 árvores', 'bi-shield-check', '#047857', 'trees_count', 10),
    ('25_trees', 'Reflorestador', 'Cadastrou 25 árvores. Impressionante!', 'bi-globe-americas', '#065F46', 'trees_count', 25),
    ('100_trees', 'Centurião', 'Cadastrou 100 árvores. Lendário!', 'bi-trophy-fill', '#F59E0B', 'trees_count', 100),
    ('first_suggestion', 'Voz Ativa', 'Enviou sua primeira sugestão para a comunidade', 'bi-lightbulb', '#8B5CF6', 'community_suggestions_count', 1),
    ('suggestion_accepted', 'Colaborador', 'Teve uma sugestão aceita pela equipe', 'bi-patch-check', '#3B82F6', 'accepted_suggestions_count', 1),
    ('3_species', 'Explorador', 'Cadastrou árvores de 3 espécies diferentes', 'bi-binoculars', '#EC4899', 'distinct_species_count', 3),
    ('7_day_streak', 'Maratonista', 'Cadastrou árvores por 7 dias consecutivos', 'bi-fire', '#EF4444', 'streak_days', 7),
    ('first_update', 'Olho Vivo', 'Enviou sua primeira sugestão de atualização de árvore', 'bi-eye', '#14B8A6', 'tree_suggestions_count', 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);
