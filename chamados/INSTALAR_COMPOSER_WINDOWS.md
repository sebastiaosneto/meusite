# Como Instalar o Composer no Windows

## Método 1: Instalador Oficial (Recomendado)

### Passo 1: Baixar o Instalador
1. Acesse: https://getcomposer.org/download/
2. Clique em **"Composer-Setup.exe"** para baixar o instalador

### Passo 2: Executar o Instalador
1. Execute o arquivo `Composer-Setup.exe` baixado
2. O instalador irá:
   - Detectar automaticamente o PHP instalado (se o XAMPP estiver instalado)
   - Adicionar o Composer ao PATH do Windows
   - Configurar tudo automaticamente

### Passo 3: Verificar a Instalação
Abra um novo terminal (PowerShell ou CMD) e execute:
```bash
composer --version
```

Se aparecer a versão do Composer, está instalado corretamente!

---

## Método 2: Instalação Manual

### Passo 1: Baixar o Composer
1. Acesse: https://getcomposer.org/Composer-Setup.exe
2. Baixe o arquivo `composer.phar`

### Passo 2: Colocar no PATH
1. Crie uma pasta (ex: `C:\composer`)
2. Coloque o `composer.phar` nesta pasta
3. Crie um arquivo `composer.bat` na mesma pasta com o conteúdo:
```batch
@echo off
php "%~dp0composer.phar" %*
```

### Passo 3: Adicionar ao PATH do Windows
1. Pressione `Win + R`, digite `sysdm.cpl` e pressione Enter
2. Vá na aba "Avançado"
3. Clique em "Variáveis de Ambiente"
4. Em "Variáveis do sistema", encontre "Path" e clique em "Editar"
5. Clique em "Novo" e adicione: `C:\composer` (ou o caminho que você escolheu)
6. Clique em "OK" em todas as janelas

---

## Instalando as Dependências do Projeto

Após instalar o Composer, abra o terminal na pasta do projeto e execute:

```bash
composer install
```

**⚠️ IMPORTANTE:** O comando correto é `composer install`, NÃO `install composer.json`!

### O que este comando faz:
- Lê o arquivo `composer.json`
- Baixa o PHPMailer e outras dependências
- Cria a pasta `vendor/` com todas as bibliotecas

---

## Solução de Problemas

### Erro: "composer não é reconhecido como comando"
**Solução:**
1. Feche e abra novamente o terminal
2. Verifique se o Composer está no PATH:
   ```bash
   echo %PATH%
   ```
3. Se não aparecer, reinstale usando o método 1

### Erro: "PHP não encontrado"
**Solução:**
1. Certifique-se de que o XAMPP está instalado
2. Adicione o PHP ao PATH:
   - Caminho típico: `C:\xampp\php`
   - Siga os mesmos passos do Método 2, Passo 3

### Erro ao executar `composer install`
**Soluções:**
1. Verifique se está na pasta correta do projeto
2. Verifique se o arquivo `composer.json` existe
3. Tente executar como administrador
4. Verifique sua conexão com a internet

---

## Verificação Final

Após instalar o Composer e executar `composer install`, você deve ter:

```
chamados/
├── vendor/              ← Esta pasta deve ser criada
│   └── phpmailer/       ← Com o PHPMailer dentro
├── composer.json
└── ...
```

---

## Comandos Úteis do Composer

```bash
# Instalar dependências
composer install

# Atualizar dependências
composer update

# Verificar dependências
composer show

# Ver versão
composer --version
```

---

## Dica Rápida

Se você usar o XAMPP, o instalador do Composer geralmente detecta automaticamente o PHP do XAMPP. Se não detectar, você pode apontar manualmente para:
```
C:\xampp\php\php.exe
```

