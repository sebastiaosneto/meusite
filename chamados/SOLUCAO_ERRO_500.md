# Solução para Erro 500 - Internal Server Error

## 🔍 Diagnóstico Rápido

O erro "Internal Server Error" geralmente ocorre por um destes motivos:

### 1. Banco de Dados Não Criado (Mais Comum)

**Sintoma:** Erro ao acessar qualquer página do sistema

**Solução:**
1. Abra o **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Clique em **"Novo"** ou **"New"** no menu lateral
3. Crie um banco chamado: `sistema_chamados`
4. Selecione o banco criado
5. Vá na aba **"Importar"** ou **"Import"**
6. Clique em **"Escolher arquivo"** e selecione `database.sql`
7. Clique em **"Executar"** ou **"Go"**

**OU** execute diretamente o SQL:
1. No phpMyAdmin, clique em **"SQL"**
2. Copie e cole todo o conteúdo do arquivo `database.sql`
3. Clique em **"Executar"**

---

### 2. MySQL Não Está Rodando

**Sintoma:** Erro de conexão com o banco

**Solução:**
1. Abra o **Painel de Controle do XAMPP**
2. Certifique-se de que o **MySQL** está com status **"Running"** (verde)
3. Se não estiver, clique em **"Start"** ao lado do MySQL

---

### 3. Credenciais do Banco Incorretas

**Sintoma:** Erro de acesso negado

**Solução:**
1. Abra o arquivo: `config/database.php`
2. Verifique e ajuste se necessário:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_chamados');
   define('DB_USER', 'root');        // Seu usuário MySQL
   define('DB_PASS', '');           // Sua senha MySQL (geralmente vazio no XAMPP)
   ```

---

### 4. Erro de Sintaxe PHP

**Sintoma:** Página em branco ou erro específico

**Solução:**
1. Acesse: `http://localhost/chamados/teste.php`
2. Este arquivo mostrará todos os erros detalhados
3. Corrija os problemas indicados

---

## 🛠️ Passo a Passo Completo

### Passo 1: Verificar o MySQL

1. Abra o XAMPP Control Panel
2. Verifique se MySQL está **Running**
3. Se não estiver, clique em **Start**

### Passo 2: Criar o Banco de Dados

**Opção A - Via phpMyAdmin (Recomendado):**
1. Acesse: `http://localhost/phpmyadmin`
2. Clique em **"Novo"** no menu esquerdo
3. Nome do banco: `sistema_chamados`
4. Collation: `utf8mb4_unicode_ci`
5. Clique em **"Criar"**
6. Selecione o banco `sistema_chamados`
7. Vá em **"Importar"**
8. Escolha o arquivo `database.sql`
9. Clique em **"Executar"**

**Opção B - Via SQL:**
1. No phpMyAdmin, clique em **"SQL"**
2. Abra o arquivo `database.sql` em um editor de texto
3. Copie todo o conteúdo
4. Cole no campo SQL do phpMyAdmin
5. Clique em **"Executar"**

### Passo 3: Verificar Configurações

1. Abra: `config/database.php`
2. Confirme:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_chamados');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Vazio se não tiver senha no XAMPP
   ```

### Passo 4: Testar o Sistema

1. Acesse: `http://localhost/chamados/teste.php`
2. Verifique se todos os testes passam (✅)
3. Se houver erros (❌), corrija conforme indicado
4. Depois acesse: `http://localhost/chamados/login.php`

---

## 🔧 Arquivo de Teste

Use o arquivo `teste.php` para diagnosticar problemas:

```
http://localhost/chamados/teste.php
```

Este arquivo verifica:
- ✅ Versão do PHP
- ✅ Extensões necessárias
- ✅ Arquivos do sistema
- ✅ Configurações
- ✅ Conexão com banco de dados
- ✅ Sessões
- ✅ Permissões

---

## 📋 Checklist Rápido

- [ ] MySQL está rodando no XAMPP?
- [ ] Banco de dados `sistema_chamados` existe?
- [ ] Tabelas foram criadas (usuarios, empresas, chamados, etc)?
- [ ] Credenciais em `config/database.php` estão corretas?
- [ ] Arquivo `database.sql` foi importado?
- [ ] Pasta `uploads/` existe e tem permissão de escrita?

---

## 🆘 Se Nada Funcionar

1. **Verifique os logs de erro do Apache:**
   - Caminho: `C:\xampp\apache\logs\error.log`
   - Procure por erros recentes

2. **Verifique os logs do PHP:**
   - Caminho: `C:\xampp\php\logs\php_error_log`
   - Ou ative display_errors no php.ini

3. **Teste com o arquivo de diagnóstico:**
   ```
   http://localhost/chamados/teste.php
   ```

4. **Verifique se o PHP está funcionando:**
   - Crie um arquivo `info.php` com: `<?php phpinfo(); ?>`
   - Acesse: `http://localhost/chamados/info.php`
   - Se não funcionar, há problema com o PHP/Apache

---

## ✅ Após Corrigir

Quando tudo estiver funcionando:

1. Acesse: `http://localhost/chamados/login.php`
2. Use as credenciais padrão:
   - **Usuário:** `admin`
   - **Senha:** `admin123`
3. **IMPORTANTE:** Altere a senha após o primeiro login!

---

## 📞 Informações Adicionais

- **Versão do PHP:** 8.2.12 (conforme erro)
- **Servidor:** Apache 2.4.58
- **Sistema:** Windows

Se o problema persistir, compartilhe o resultado do `teste.php` para diagnóstico mais preciso.

