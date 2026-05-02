-- ============================================
-- COMANDO SQL PARA CRIAR/ATUALIZAR USUÁRIO ADMIN
-- Senha: admin123
-- ============================================

-- MÉTODO 1: Inserir ou Atualizar (RECOMENDADO)
-- Este comando funciona mesmo se o usuário já existir
INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo) 
VALUES (
    'Administrador', 
    'admin@sistema.com', 
    'admin', 
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 
    'admin', 
    1
)
ON DUPLICATE KEY UPDATE
    nome = VALUES(nome),
    email = VALUES(email),
    senha = VALUES(senha),
    ativo = VALUES(ativo);

-- ============================================
-- MÉTODO 2: Se o método acima der erro, use este:
-- ============================================

-- Primeiro, deletar se existir
DELETE FROM usuarios WHERE usuario = 'admin';

-- Depois, inserir novo
INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo) 
VALUES (
    'Administrador', 
    'admin@sistema.com', 
    'admin', 
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 
    'admin', 
    1
);

-- ============================================
-- MÉTODO 3: Apenas atualizar senha (se usuário já existe)
-- ============================================

UPDATE usuarios 
SET senha = '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    ativo = 1,
    nome = 'Administrador',
    email = 'admin@sistema.com'
WHERE usuario = 'admin';

-- ============================================
-- VERIFICAR SE FOI CRIADO CORRETAMENTE
-- ============================================

SELECT id, nome, email, usuario, tipo, ativo, created_at 
FROM usuarios 
WHERE usuario = 'admin';

