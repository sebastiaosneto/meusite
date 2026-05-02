-- Migração: Empresas sem login/senha + Funcionários com e-mail duplicado permitido
-- Execute este script no phpMyAdmin ou MySQL se você já tem o sistema instalado.
-- Se alguma linha DROP INDEX der erro (índice inexistente), ignore e prossiga.

USE sistema_chamados;

-- 1. Empresas: tornar usuario e senha opcionais (empresas são só para vincular ao funcionário)
ALTER TABLE empresas 
    MODIFY COLUMN usuario VARCHAR(100) NULL,
    MODIFY COLUMN senha VARCHAR(255) NULL;

-- Remover UNIQUE de usuario em empresas para permitir NULL
ALTER TABLE empresas DROP INDEX usuario;

-- 2. Usuários: permitir e-mail duplicado (funcionários da mesma empresa podem compartilhar e-mail)
ALTER TABLE usuarios DROP INDEX email;
