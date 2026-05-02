-- Executar em bases já existentes para habilitar anexo em interações do histórico
ALTER TABLE historico_chamados
ADD COLUMN anexo VARCHAR(255) NULL AFTER descricao;
