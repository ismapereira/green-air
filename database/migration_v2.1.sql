-- Migration: Green Air v2.1
-- Data: 2026-04-05

USE green_air;

-- ============================================================
-- Espécie "Não identificada"
-- ============================================================
INSERT INTO tree_species (name, scientific_name) 
SELECT 'Não identificada', NULL
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM tree_species WHERE name = 'Não identificada'
);

-- ============================================================
-- Sugestões Colaborativas da Comunidade
-- ============================================================
CREATE TABLE IF NOT EXISTS community_suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category ENUM('feature', 'species', 'improvement', 'bug', 'other') NOT NULL DEFAULT 'other',
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'reviewed', 'accepted', 'implemented', 'rejected') NOT NULL DEFAULT 'pending',
    admin_response TEXT DEFAULT NULL,
    reviewed_by INT DEFAULT NULL,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_user (user_id)
);
