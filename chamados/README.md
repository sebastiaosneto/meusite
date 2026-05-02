# Sistema de Chamados T.I.

Sistema web para gerenciamento de chamados de suporte, com perfis de acesso, histórico de interações, anexos e notificações por e-mail.

## Destaques atuais

- Três perfis de usuário: `admin`, `tecnico` e `funcionario`
- Dashboard com indicadores e gráficos
- Fluxo de chamados com estados: `pendente`, `em_atendimento`, `finalizado`, `reaberto`
- Histórico do chamado com linha do tempo, comentários e anexos
- Notificações por e-mail em abertura, atendimento e finalização
- Regras de segurança reforçadas (CSRF, sessão, validações backend)

## Stack

- PHP 7.4+
- MySQL 5.7+
- Apache (XAMPP)
- PHPMailer (via Composer ou biblioteca local)

## Instalação

1. Copie o projeto para `htdocs/chamados`.
2. Crie/importe o banco com `database.sql`.
3. Ajuste credenciais do banco em `config/database.php`.
4. Garanta permissão de escrita na pasta `uploads/`.
5. Acesse `http://localhost/chamados/login.php`.

Usuário padrão:
- Usuário: `admin`
- Senha: `admin123`

## Configuração de e-mail

O sistema lê configurações de e-mail por ambiente:

- `config/local.php` (recomendado em ambiente local; já ignorado pelo git)
- variáveis de ambiente do servidor

Variáveis suportadas:

- `SMTP_HOST`
- `SMTP_PORT`
- `SMTP_ENCRYPTION` (`ssl` para 465, `tls` para 587)
- `SMTP_USER`
- `SMTP_PASS`
- `SMTP_FROM_EMAIL`
- `SMTP_FROM_NAME`
- `SUPPORT_NOTIFICATION_EMAIL` (e-mail operacional para novos chamados)

Exemplo aplicado localmente:
- `SMTP_HOST=smtp.hostinger.com`
- `SMTP_PORT=465`
- `SMTP_ENCRYPTION=ssl`

## Regras de notificação por e-mail

### Abertura de chamado
- Envia para `SUPPORT_NOTIFICATION_EMAIL` (ex.: `contato@sasntecnologia.com.br`) com:
  - empresa
  - funcionário solicitante
  - título, prioridade e descrição
- Envia confirmação para o solicitante
- Técnico pode receber cópia em CC quando definido

### Atendimento de chamado
- Envia atualização para o solicitante
- Técnico recebe cópia
- E-mail operacional de acompanhamento para `SUPPORT_NOTIFICATION_EMAIL`

### Finalização de chamado
- Envia para o solicitante com a solução
- Técnico recebe cópia
- E-mail operacional de finalização para `SUPPORT_NOTIFICATION_EMAIL`

## PHPMailer: Composer ou modo local

O carregamento do PHPMailer funciona em dois modos:

1. Composer (`vendor/autoload.php`)
2. Biblioteca local (`libraries/PHPMailer/src`)

Isso permite funcionamento mesmo quando o Composer não está disponível no ambiente.

## Migrações e banco de dados

Se a base já existia antes das melhorias de histórico, execute:

- `MIGRACAO_HISTORICO_ANEXO.sql`

Este script adiciona a coluna `anexo` em `historico_chamados`.

## Melhorias de segurança implementadas

- Proteção CSRF em formulários POST
- Regeneração de sessão no login (`session_regenerate_id(true)`)
- Cookies de sessão com `HttpOnly` e `SameSite`
- Validação de autorização por ação crítica no backend
- Transações (`beginTransaction/commit/rollback`) em fluxos sensíveis
- Upload validado por MIME real (`finfo`) e extensão compatível
- Bloqueio de scripts de diagnóstico/manutenção via `.htaccess`
- Segredos removidos de código versionado (uso de configuração local/ambiente)

## Histórico com interação e anexo

Na tela `historico.php` é possível:

- adicionar interação textual no chamado
- anexar arquivo na interação (imagem/pdf/doc/docx)
- visualizar anexos diretamente na timeline

## Estrutura principal

```
chamados/
├── api/
├── assets/
├── config/
│   ├── config.php
│   ├── local.php              # local, não versionado
│   ├── database.php
│   ├── email.php
│   └── functions.php
├── includes/
├── libraries/PHPMailer/src/   # fallback sem Composer
├── uploads/
├── database.sql
├── MIGRACAO_HISTORICO_ANEXO.sql
└── *.php
```

## Arquivos de diagnóstico úteis

- `testar_phpmailer.php`: valida carregamento do PHPMailer e EmailService
- `SOLUCAO_ERRO_500.md`: roteiro para diagnóstico inicial

## Observações

- Projeto de uso interno.
- Recomenda-se rotacionar senhas SMTP periodicamente.

