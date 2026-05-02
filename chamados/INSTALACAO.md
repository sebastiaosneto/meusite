# Guia de Instalação - Sistema de Chamados T.I.

## Passo a Passo

### 1. Preparação do Ambiente

Certifique-se de ter instalado:
- **XAMPP** (ou similar) com PHP 7.4+ e MySQL
- **Composer** (para gerenciar dependências PHP)

### 2. Configuração do Banco de Dados

1. Abra o **phpMyAdmin** (geralmente em `http://localhost/phpmyadmin`)
2. Crie um novo banco de dados chamado `sistema_chamados`
3. Importe o arquivo `database.sql`:
   - Clique na aba "Importar"
   - Selecione o arquivo `database.sql`
   - Clique em "Executar"

**OU** execute o SQL diretamente no terminal MySQL:

```sql
mysql -u root -p < database.sql
```

### 3. Configuração do Sistema

1. **Edite `config/database.php`**:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_chamados');
   define('DB_USER', 'root');        // Seu usuário MySQL
   define('DB_PASS', '');             // Sua senha MySQL
   ```

2. **Edite `config/config.php`**:
   - Ajuste a `BASE_URL` se necessário:
     ```php
     define('BASE_URL', 'http://localhost/chamados/');
     ```

3. **Configure o E-mail (Opcional)**:
   - Edite `config/config.php`:
     ```php
     define('SMTP_HOST', 'smtp.gmail.com');
     define('SMTP_PORT', 587);
     define('SMTP_USER', 'seu_email@gmail.com');
     define('SMTP_PASS', 'sua_senha_app');  // Senha de app do Gmail
     ```
   
   **Para Gmail:**
   - Ative a verificação em duas etapas
   - Gere uma senha de aplicativo em: https://myaccount.google.com/apppasswords

### 4. Instalação de Dependências

Abra o terminal na pasta do projeto e execute:

```bash
composer install
```

Isso instalará o PHPMailer necessário para envio de e-mails.

### 5. Permissões

Certifique-se de que a pasta `uploads/` tenha permissão de escrita:

**Windows:**
- A pasta já deve funcionar, mas verifique se o Apache tem permissão

**Linux/Mac:**
```bash
chmod 755 uploads/
```

### 6. Primeiro Acesso

1. Acesse: `http://localhost/chamados/login.php`
2. **Credenciais padrão:**
   - Usuário: `admin`
   - Senha: `admin123`

⚠️ **IMPORTANTE:** Altere a senha do administrador após o primeiro login!

### 7. Configuração Inicial

Após fazer login como administrador:

1. **Cadastre Empresas** (Menu: Empresas)
2. **Cadastre Funcionários** (Menu: Funcionários) - vincule à empresa
3. **Cadastre Técnicos** (Menu: Técnicos)
4. **Configure Tipos de Atendimento** (Menu: Tipos de Atendimento)

### 8. Teste o Sistema

1. Faça logout e faça login como funcionário
2. Abra um chamado de teste
3. Faça login como técnico
4. Atenda e finalize o chamado
5. Verifique se os e-mails foram enviados (se configurado)

## Solução de Problemas

### Erro de Conexão com Banco de Dados
- Verifique se o MySQL está rodando
- Confirme as credenciais em `config/database.php`
- Verifique se o banco `sistema_chamados` existe

### E-mails não são enviados
- Verifique as configurações SMTP em `config/config.php`
- Para Gmail, use senha de aplicativo (não a senha normal)
- Verifique os logs de erro do PHP

### Erro 404 nas páginas
- Verifique se o mod_rewrite está habilitado no Apache
- Confirme que o arquivo `.htaccess` está presente

### Upload de arquivos não funciona
- Verifique permissões da pasta `uploads/`
- Confirme o tamanho máximo de upload no PHP (php.ini)

## Estrutura de Pastas

```
chamados/
├── api/              # APIs
├── assets/           # CSS e JavaScript
├── config/           # Configurações
├── images/           # Logo e favicon
├── includes/         # Templates
├── uploads/          # Arquivos anexados
└── *.php            # Páginas principais
```

## Suporte

Em caso de dúvidas, consulte o `README.md` ou entre em contato com o suporte técnico.

