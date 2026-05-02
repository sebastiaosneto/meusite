-- Sistema de Chamados T.I.
-- Banco de Dados MySQL

CREATE DATABASE IF NOT EXISTS sistema_chamados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_chamados;

-- Tabela de Empresas (apenas para vincular ao funcionário; sem login/senha)
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    contato VARCHAR(50),
    email VARCHAR(255) NOT NULL,
    endereco TEXT,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Usuários (Admin, Técnico, Funcionário)
-- E-mail pode repetir: funcionários da mesma empresa podem compartilhar um e-mail
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    contato VARCHAR(50),
    email VARCHAR(255) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'tecnico', 'funcionario') NOT NULL,
    empresa_id INT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Tipos de Atendimento
CREATE TABLE tipos_atendimento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Chamados
CREATE TABLE chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    funcionario_id INT NOT NULL,
    tecnico_id INT NULL,
    tipo_atendimento_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') DEFAULT 'media',
    status ENUM('pendente', 'em_atendimento', 'finalizado', 'reaberto', 'cancelado') DEFAULT 'pendente',
    solucao TEXT NULL,
    anexo VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    finalizado_at TIMESTAMP NULL,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    FOREIGN KEY (funcionario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (tipo_atendimento_id) REFERENCES tipos_atendimento(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Histórico de Chamados (para reaberturas e interações)
CREATE TABLE historico_chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chamado_id INT NOT NULL,
    usuario_id INT NOT NULL,
    acao VARCHAR(100) NOT NULL,
    descricao TEXT,
    anexo VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chamado_id) REFERENCES chamados(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para otimização de consultas frequentes
ALTER TABLE chamados
    ADD INDEX idx_chamados_status (status),
    ADD INDEX idx_chamados_created_at (created_at),
    ADD INDEX idx_chamados_tecnico_status (tecnico_id, status),
    ADD INDEX idx_chamados_empresa_created (empresa_id, created_at),
    ADD INDEX idx_chamados_funcionario_status (funcionario_id, status);

ALTER TABLE historico_chamados
    ADD INDEX idx_historico_chamado_created (chamado_id, created_at),
    ADD INDEX idx_historico_usuario_created (usuario_id, created_at);

-- Inserir usuário administrador padrão (senha: admin123)
INSERT INTO usuarios (nome, email, usuario, senha, tipo) VALUES 
('Administrador', 'admin@sistema.com', 'admin', '$2y$10$6.9XUCWiOHmlVBofN.Q/guMENaMo7Vio9Xioe4.vySl8KFoVMBRUe', 'admin');

-- Inserir alguns tipos de atendimento padrão
INSERT INTO tipos_atendimento (nome, descricao) VALUES 
('Suporte Técnico', 'Problemas técnicos diversos'),
('Instalação de Software', 'Instalação e configuração de softwares'),
('Manutenção de Hardware', 'Reparo e manutenção de equipamentos'),
('Rede e Internet', 'Problemas relacionados a rede e conectividade'),
('Backup e Segurança', 'Backup de dados e questões de segurança');

