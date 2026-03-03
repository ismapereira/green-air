CREATE DATABASE IF NOT EXISTS green_air CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE green_air;

CREATE TABLE IF NOT EXISTS user_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    min_points INT DEFAULT 0
);

INSERT INTO user_levels (name, min_points) VALUES
('Bronze', 0),
('Prata', 50),
('Ouro', 150)
ON DUPLICATE KEY UPDATE name=VALUES(name);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    level_id INT NOT NULL DEFAULT 1,
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (level_id) REFERENCES user_levels(id)
);

CREATE TABLE IF NOT EXISTS tree_species (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    scientific_name VARCHAR(100) DEFAULT NULL
);

INSERT INTO tree_species (name, scientific_name) VALUES
('Ipê-Amarelo', 'Handroanthus albus'),
('Pau-Brasil', 'Paubrasilia echinata'),
('Jacarandá-Mimoso', 'Jacaranda mimosifolia'),
('Sibipiruna', 'Caesalpinia pluviosa'),
('Quaresmeira', 'Tibouchina granulosa')
ON DUPLICATE KEY UPDATE name=VALUES(name);

CREATE TABLE IF NOT EXISTS tree_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255) DEFAULT NULL
);

INSERT INTO tree_status (name, description) VALUES
('Saudável', 'Árvore em boas condições'),
('Necessita Poda', 'Galhos muito extensos ou tocando fios'),
('Risco de Queda', 'Tronco inclinado ou raízes expostas perigosamente'),
('Doente', 'Apresenta fungos, cupins ou folhas secas')
ON DUPLICATE KEY UPDATE name=VALUES(name);

CREATE TABLE IF NOT EXISTS trees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    species_id INT NOT NULL,
    status_id INT NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    address VARCHAR(255) DEFAULT NULL,
    size VARCHAR(50) DEFAULT NULL, -- Pequeno, Médio, Grande
    age_approx INT DEFAULT NULL,
    observations TEXT DEFAULT NULL,
    photo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (species_id) REFERENCES tree_species(id),
    FOREIGN KEY (status_id) REFERENCES tree_status(id)
);

CREATE TABLE IF NOT EXISTS contributions_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tree_id INT DEFAULT NULL,
    action VARCHAR(50) NOT NULL, -- ADD_TREE, SUGGEST_UPDATE, EDIT_TREE
    points_awarded INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tree_id) REFERENCES trees(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    last_activity INT(10) UNSIGNED NOT NULL,
    data TEXT NOT NULL,
    KEY `last_activity_idx` (`last_activity`)
);

CREATE TABLE IF NOT EXISTS password_resets (
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `email_idx` (`email`)
);

-- Usuário administrador (nível Ouro): admin@greenair.com / admin123
INSERT INTO users (name, email, password, level_id, points) VALUES
('Administrador', 'admin@greenair.com', '$2y$10$d7ztJPItR6.tRCFs3xyhcOoO6HCJdbGw15XTsnS.YU0ZA3CyvMJ0G', 3, 0)
ON DUPLICATE KEY UPDATE name = VALUES(name), password = VALUES(password), level_id = VALUES(level_id);
