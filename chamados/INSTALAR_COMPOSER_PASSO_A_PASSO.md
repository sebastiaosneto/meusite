# 🚀 Instalação do Composer no Windows - Passo a Passo

## ⚠️ Erro Identificado

Você tentou executar: `install composer.json` ❌

**O comando correto é:** `composer install` ✅

Mas primeiro, você precisa **instalar o Composer** no Windows!

---

## 📥 Passo 1: Baixar o Composer

1. Abra seu navegador
2. Acesse: **https://getcomposer.org/download/**
3. Clique no botão **"Composer-Setup.exe"** (Download para Windows)
4. O arquivo será baixado (cerca de 1.5 MB)

---

## 🔧 Passo 2: Instalar o Composer

1. **Localize o arquivo baixado** (geralmente na pasta Downloads)
2. **Clique duas vezes** em `Composer-Setup.exe`
3. Se aparecer aviso de segurança, clique em **"Executar mesmo assim"** ou **"Mais informações"** → **"Executar mesmo assim"**

### Durante a Instalação:

1. **Tela de Boas-vindas**: Clique em **"Next"**

2. **Seleção do PHP**:
   - O instalador tentará encontrar o PHP automaticamente
   - Se você usa XAMPP, ele geralmente encontra em: `C:\xampp\php\php.exe`
   - Se não encontrar, clique em **"Browse"** e navegue até:
     ```
     C:\xampp\php\php.exe
     ```
   - Clique em **"Next"**

3. **Configurações de Proxy**:
   - Deixe em branco (a menos que use proxy)
   - Clique em **"Next"**

4. **Preparação para Instalação**:
   - Clique em **"Install"**
   - Aguarde a instalação

5. **Concluído**:
   - Marque a opção **"Launch Composer"** se quiser testar
   - Clique em **"Finish"**

---

## ✅ Passo 3: Verificar a Instalação

1. **Feche todos os terminais abertos** (importante!)
2. **Abra um NOVO terminal**:
   - Pressione `Win + R`
   - Digite `cmd` ou `powershell`
   - Pressione Enter

3. **Teste o Composer**:
   ```bash
   composer --version
   ```

4. **Se aparecer algo como:**
   ```
   Composer version 2.x.x
   ```
   ✅ **Sucesso! O Composer está instalado!**

---

## 📦 Passo 4: Instalar as Dependências do Projeto

Agora que o Composer está instalado:

1. **Navegue até a pasta do projeto**:
   ```bash
   cd E:\OneDriver\OneDrive\Xampp\htdocs\chamados
   ```

2. **Execute o comando correto**:
   ```bash
   composer install
   ```

   ⚠️ **NÃO use:** `install composer.json` ❌
   ✅ **Use:** `composer install` ✅

3. **Aguarde o download**:
   - O Composer irá baixar o PHPMailer
   - Criará a pasta `vendor/`
   - Isso pode levar alguns minutos

4. **Quando terminar**, você verá:
   ```
   Package operations: X installs
   Writing lock file
   Installing dependencies from lock file
   ```

---

## 🎯 Resumo dos Comandos Corretos

```bash
# Verificar se Composer está instalado
composer --version

# Navegar até a pasta do projeto
cd E:\OneDriver\OneDrive\Xampp\htdocs\chamados

# Instalar dependências
composer install
```

---

## ❌ Problemas Comuns e Soluções

### Problema 1: "composer não é reconhecido"
**Solução:**
- Feche e abra um NOVO terminal
- Reinicie o computador (se necessário)
- Verifique se o Composer foi adicionado ao PATH durante a instalação

### Problema 2: "PHP não encontrado"
**Solução:**
- Certifique-se de que o XAMPP está instalado
- Durante a instalação do Composer, aponte manualmente para:
  ```
  C:\xampp\php\php.exe
  ```

### Problema 3: Erro de permissão
**Solução:**
- Execute o terminal como Administrador
- Clique com botão direito no terminal → "Executar como administrador"

### Problema 4: Erro de conexão
**Solução:**
- Verifique sua conexão com a internet
- Alguns antivírus podem bloquear, tente desabilitar temporariamente

---

## 📝 Após a Instalação

Após executar `composer install` com sucesso, você terá:

```
chamados/
├── vendor/                    ← Nova pasta criada
│   ├── autoload.php
│   └── phpmailer/             ← PHPMailer instalado
│       └── ...
├── composer.json
├── composer.lock              ← Arquivo criado automaticamente
└── ...
```

Agora o sistema está pronto para enviar e-mails! 🎉

---

## 🔗 Links Úteis

- **Download do Composer**: https://getcomposer.org/download/
- **Documentação**: https://getcomposer.org/doc/
- **XAMPP**: https://www.apachefriends.org/

---

## 💡 Dica Final

Se você não conseguir instalar o Composer agora, o sistema ainda funcionará, mas **não enviará e-mails**. Todas as outras funcionalidades estarão disponíveis.

Para habilitar os e-mails depois, basta:
1. Instalar o Composer
2. Executar `composer install`
3. Configurar o e-mail em `config/config.php`

