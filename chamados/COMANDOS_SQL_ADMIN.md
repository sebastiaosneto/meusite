# Comandos SQL para Criar/Atualizar Usuário Admin

## 🔑 Credenciais Padrão
- **Usuário:** `admin`
- **Senha:** `admin123`

---

## 📝 Comando SQL Recomendado (Funciona sempre)

Este comando **insere** se não existir ou **atualiza** se já existir:

```sql
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
```

---

## 🔄 Opção 2: Atualizar Senha (se o usuário já existe)

```sql
UPDATE usuarios 
SET senha = '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    ativo = 1,
    nome = 'Administrador',
    email = 'admin@sistema.com'
WHERE usuario = 'admin';
```

---

## ➕ Opção 3: Deletar e Recriar (se houver problemas)

```sql
-- Primeiro, deletar o usuário existente
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
```

---

## ✅ Verificar se foi criado corretamente

```sql
SELECT id, nome, email, usuario, tipo, ativo 
FROM usuarios 
WHERE usuario = 'admin';
```

**Resultado esperado:**
- id: 1 (ou outro número)
- nome: Administrador
- email: admin@sistema.com
- usuario: admin
- tipo: admin
- ativo: 1

---

## 🧪 Testar o Login

Após executar o SQL:

1. Acesse: `http://seudominio.com/chamados/login.php`
2. Use:
   - **Usuário:** `admin`
   - **Senha:** `admin123`

---

## 🔧 Gerar Hash de Nova Senha (se necessário)

Se quiser criar um hash para uma senha diferente:

1. Acesse: `http://seudominio.com/chamados/gerar_senha_admin.php`
2. O script gerará o hash automaticamente
3. Use o hash gerado no comando SQL

**OU** execute no PHP:

```php
<?php
$senha = 'sua_nova_senha';
$hash = password_hash($senha, PASSWORD_BCRYPT);
echo $hash;
?>
```

---

## ⚠️ Problemas Comuns

### Erro: "Duplicate entry"
**Solução:** Use a Opção 2 (UPDATE) ou Opção 3 (DELETE + INSERT)

### Erro: "Table doesn't exist"
**Solução:** Certifique-se de que a tabela `usuarios` foi criada. Execute o arquivo `database.sql` completo.

### Senha não funciona após inserir
**Solução:** 
1. Verifique se o hash está completo (deve ter 60 caracteres)
2. Certifique-se de que não há espaços extras no hash
3. Use o script `gerar_senha_admin.php` para gerar um novo hash

---

## 📋 Checklist

- [ ] Banco de dados `sistema_chamados` existe
- [ ] Tabela `usuarios` foi criada
- [ ] Comando SQL executado com sucesso
- [ ] Usuário verificado com SELECT
- [ ] Login testado no sistema

---

## 🆘 Se Nada Funcionar

Execute este comando completo que garante a criação:

```sql
-- Garantir que o usuário admin existe e está ativo
DELETE FROM usuarios WHERE usuario = 'admin';

INSERT INTO usuarios (nome, contato, email, usuario, senha, tipo, empresa_id, ativo, created_at, updated_at) 
VALUES (
    'Administrador',
    NULL,
    'admin@sistema.com',
    'admin',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
    'admin',
    NULL,
    1,
    NOW(),
    NOW()
);
```

Depois verifique:
```sql
SELECT * FROM usuarios WHERE usuario = 'admin';
```

