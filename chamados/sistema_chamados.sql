-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/03/2026 às 00:38
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_chamados`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `chamados`
--

CREATE TABLE `chamados` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `funcionario_id` int(11) NOT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `tipo_atendimento_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `prioridade` enum('baixa','media','alta','urgente') DEFAULT 'media',
  `status` enum('pendente','em_atendimento','finalizado','reaberto','cancelado') DEFAULT 'pendente',
  `solucao` text DEFAULT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finalizado_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `chamados`
--

INSERT INTO `chamados` (`id`, `empresa_id`, `funcionario_id`, `tecnico_id`, `tipo_atendimento_id`, `titulo`, `descricao`, `prioridade`, `status`, `solucao`, `anexo`, `created_at`, `updated_at`, `finalizado_at`) VALUES
(1, 1, 3, 2, 1, 'Certificado', 'Instalação do certificado em meu laptop', 'media', 'finalizado', 'Instalação realizada.', NULL, '2026-03-16 21:40:45', '2026-03-16 21:44:38', '2026-03-16 21:44:38'),
(2, 2, 7, 2, 5, 'Restaurar', 'Restaurar pasta publica do servidor.', 'alta', 'em_atendimento', NULL, NULL, '2026-03-16 22:14:31', '2026-03-16 22:15:41', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `endereco` text DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `empresas`
--

INSERT INTO `empresas` (`id`, `nome`, `contato`, `email`, `endereco`, `usuario`, `senha`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 'Cartório do Único Ofício de Igarapé-Açu', '91989175368', 'cartorioigarapeacu125@gmail.com', 'Av. Balbino Teixeira - Igarapé-Açu, PA, 68725-000', 'cartorioigarape', '$2y$10$PsgOht28vWDXt2xwmb1ld.zyWs38NQ72oHDsXwpXSDbXatMUYKrNK', 1, '2026-03-16 21:13:10', '2026-03-16 22:06:37'),
(2, 'Cartório do Único Ofício da Comarca de São Francisco do Pará', '91986206856', 'unicooficiosfp@gmail.com', 'Av. Barão do Rio Branco, 1135, São Francisco', 'cartoriosaofran', '$2y$10$gpxiP6tLvaMwaSEB9SMCeuQR3yS78XZ/zB6rWBUD2sJhwTZyktJVq', 1, '2026-03-16 22:03:41', '2026-03-16 22:07:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_chamados`
--

CREATE TABLE `historico_chamados` (
  `id` int(11) NOT NULL,
  `chamado_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `acao` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `historico_chamados`
--

INSERT INTO `historico_chamados` (`id`, `chamado_id`, `usuario_id`, `acao`, `descricao`, `anexo`, `created_at`) VALUES
(1, 1, 3, 'abertura', 'Chamado aberto: Certificado', NULL, '2026-03-16 21:40:45'),
(2, 1, 2, 'atendimento', 'Chamado em atendimento', NULL, '2026-03-16 21:43:51'),
(3, 1, 2, 'finalizacao', 'Chamado finalizado. Solução: Instalação realizada.', NULL, '2026-03-16 21:44:38'),
(4, 2, 7, 'abertura', 'Chamado aberto: Restaurar', NULL, '2026-03-16 22:14:31'),
(5, 2, 2, 'atendimento', 'Chamado em atendimento', NULL, '2026-03-16 22:15:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_atendimento`
--

CREATE TABLE `tipos_atendimento` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tipos_atendimento`
--

INSERT INTO `tipos_atendimento` (`id`, `nome`, `descricao`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 'Suporte Técnico', 'Problemas técnicos diversos', 1, '2026-03-16 21:05:29', '2026-03-16 21:05:29'),
(2, 'Instalação de Software', 'Instalação e configuração de softwares', 1, '2026-03-16 21:05:29', '2026-03-16 21:05:29'),
(3, 'Manutenção de Hardware', 'Reparo e manutenção de equipamentos', 1, '2026-03-16 21:05:29', '2026-03-16 21:05:29'),
(4, 'Rede e Internet', 'Problemas relacionados a rede e conectividade', 1, '2026-03-16 21:05:29', '2026-03-16 21:05:29'),
(5, 'Backup e Segurança', 'Backup de dados e questões de segurança', 1, '2026-03-16 21:05:29', '2026-03-16 21:05:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','tecnico','funcionario') NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `contato`, `email`, `usuario`, `senha`, `tipo`, `empresa_id`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', '94991534873', 'contato@sasntecnologia.com.br', 'admin', '$2y$10$j0GcJHyddt.DAGkzhH2LsexOSTSlbTp9cjDJ.SXazERzj6ShJnjaG', 'admin', NULL, 1, '2026-03-16 21:05:29', '2026-03-16 21:39:27'),
(2, 'Sebastião Neto', '94991534873', 'sebastiao.neto@sasntecnologia.com.br', 'sneto', '$2y$10$757iL/VOE4Ra8LOWgwe.RekVy8tw3BXrYV9v9jQHne.vjHWNN24Pa', 'tecnico', NULL, 1, '2026-03-16 21:10:26', '2026-03-16 21:43:37'),
(3, 'Claudia Roberta', '91996131211', 'cartorioigarapeacu125@gmail.com', 'claudia', '$2y$10$rQlNDjpmcbZkky3BwJHyEeicjbE1qWfANbIAVC6f4ZDPzBuU1BuAu', 'funcionario', 1, 1, '2026-03-16 21:16:42', '2026-03-16 22:12:48'),
(7, 'Ramon Sousa', '91982886587', 'unicooficiosfp@gmail.com', 'ramon', '$2y$10$IVHxhm9X1lo5Z31poQRdTu92bCALn7TeS8wJaXVrzak61eXYGIc2m', 'funcionario', 2, 1, '2026-03-16 22:13:40', '2026-03-16 22:13:40');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `chamados`
--
ALTER TABLE `chamados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_atendimento_id` (`tipo_atendimento_id`),
  ADD KEY `idx_chamados_status` (`status`),
  ADD KEY `idx_chamados_created_at` (`created_at`),
  ADD KEY `idx_chamados_tecnico_status` (`tecnico_id`,`status`),
  ADD KEY `idx_chamados_empresa_created` (`empresa_id`,`created_at`),
  ADD KEY `idx_chamados_funcionario_status` (`funcionario_id`,`status`);

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `historico_chamados`
--
ALTER TABLE `historico_chamados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_historico_chamado_created` (`chamado_id`,`created_at`),
  ADD KEY `idx_historico_usuario_created` (`usuario_id`,`created_at`);

--
-- Índices de tabela `tipos_atendimento`
--
ALTER TABLE `tipos_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chamados`
--
ALTER TABLE `chamados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `historico_chamados`
--
ALTER TABLE `historico_chamados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tipos_atendimento`
--
ALTER TABLE `tipos_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `chamados`
--
ALTER TABLE `chamados`
  ADD CONSTRAINT `chamados_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chamados_ibfk_2` FOREIGN KEY (`funcionario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chamados_ibfk_3` FOREIGN KEY (`tecnico_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chamados_ibfk_4` FOREIGN KEY (`tipo_atendimento_id`) REFERENCES `tipos_atendimento` (`id`);

--
-- Restrições para tabelas `historico_chamados`
--
ALTER TABLE `historico_chamados`
  ADD CONSTRAINT `historico_chamados_ibfk_1` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_chamados_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
