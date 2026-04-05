-- Migration: Adicionar espécie "Não identificada" para árvores não classificadas
-- Data: 2026-04-05

USE green_air;

-- Inserir espécie "Não identificada" se não existir
INSERT INTO tree_species (name, scientific_name) 
SELECT 'Não identificada', NULL
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM tree_species WHERE name = 'Não identificada'
);
