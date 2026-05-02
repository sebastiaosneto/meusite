-- Comandos SQL para criar/atualizar usuário ADMIN
-- Senha: admin123

-- Opção 1: Se o usuário admin NÃO existe, insere um novo
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
    nome = 'Administrador',
    email = 'admin@sistema.com',
    senha = '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    ativo = 1;

-- Opção 2: Se preferir deletar e recriar (descomente as linhas abaixo)
-- DELETE FROM usuarios WHERE usuario = 'admin';
-- INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo) 
-- VALUES ('Administrador', 'admin@sistema.com', 'admin', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'admin', 1);

-- Opção 3: Atualizar senha se o usuário já existe
-- UPDATE usuarios 
-- SET senha = '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
--     ativo = 1
-- WHERE usuario = 'admin';

-- Verificar se foi criado corretamente
SELECT id, nome, email, usuario, tipo, ativo FROM usuarios WHERE usuario = 'admin';

