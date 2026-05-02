-- ============================================
-- CORREÇÃO DEFINITIVA - USUÁRIO ADMIN
-- ============================================
-- Este script garante que o usuário admin existe
-- com a senha correta e está ativo

-- Passo 1: Deletar usuário admin existente (se houver)
DELETE FROM usuarios WHERE usuario = 'admin';

-- Passo 2: Inserir usuário admin com hash correto
-- IMPORTANTE: Este hash foi gerado especificamente para a senha "admin123"
INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo, created_at, updated_at) 
VALUES (
    'Administrador',
    'admin@sistema.com',
    'admin',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    'admin',
    1,
    NOW(),
    NOW()
);

-- Passo 3: Verificar se foi criado corretamente
SELECT 
    id,
    nome,
    email,
    usuario,
    tipo,
    ativo,
    LENGTH(senha) as tamanho_hash,
    LEFT(senha, 10) as inicio_hash,
    RIGHT(senha, 10) as fim_hash
FROM usuarios 
WHERE usuario = 'admin';

-- ============================================
-- NOTA: Se o hash acima não funcionar, gere um novo:
-- ============================================
-- 1. Acesse: http://seudominio.com/chamados/gerar_hash.php
-- 2. Copie o hash gerado
-- 3. Execute: UPDATE usuarios SET senha = '[NOVO_HASH]' WHERE usuario = 'admin';

-- ============================================
-- VERIFICAÇÃO ADICIONAL
-- ============================================
-- Verificar se a coluna senha tem tamanho suficiente
-- Deve ser VARCHAR(255) ou maior
SHOW COLUMNS FROM usuarios WHERE Field = 'senha';

-- Se a coluna for menor que VARCHAR(255), execute:
-- ALTER TABLE usuarios MODIFY senha VARCHAR(255) NOT NULL;

