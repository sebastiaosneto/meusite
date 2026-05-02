<?php
// Sistema de Envio de E-mail

// Tentar carregar PHPMailer via Composer; se não existir, usar cópia local.
$vendorAutoload = ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$localPhpMailerDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

$phpMailerLoaded = false;
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
    $phpMailerLoaded = true;
} elseif (
    file_exists($localPhpMailerDir . 'PHPMailer.php') &&
    file_exists($localPhpMailerDir . 'SMTP.php') &&
    file_exists($localPhpMailerDir . 'Exception.php')
) {
    require_once $localPhpMailerDir . 'Exception.php';
    require_once $localPhpMailerDir . 'SMTP.php';
    require_once $localPhpMailerDir . 'PHPMailer.php';
    $phpMailerLoaded = true;
}

if (!$phpMailerLoaded) {
    class EmailService {
        public function enviarNotificacaoChamadoAberto($chamado, $funcionario, $empresa, $tecnico = null) {
            error_log("PHPMailer não disponível. Instale via Composer ou mantenha a biblioteca local em libraries/PHPMailer/src.");
            return false;
        }

        public function enviarNotificacaoChamadoAtendido($chamado, $funcionario, $tecnico, $empresa) {
            error_log("PHPMailer não disponível. Instale via Composer ou mantenha a biblioteca local em libraries/PHPMailer/src.");
            return false;
        }

        public function enviarNotificacaoChamadoFinalizado($chamado, $funcionario, $tecnico) {
            error_log("PHPMailer não disponível. Instale via Composer ou mantenha a biblioteca local em libraries/PHPMailer/src.");
            return false;
        }
    }
} else {
    class EmailService {
        private $mail;
        private $enabled = true;

        public function __construct() {
            if (empty(SMTP_USER) || empty(SMTP_PASS) || empty(SMTP_FROM_EMAIL)) {
                $this->enabled = false;
                error_log("SMTP não configurado. Defina SMTP_USER, SMTP_PASS e SMTP_FROM_EMAIL para enviar e-mails.");
                return;
            }

            $this->mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $this->mail->isSMTP();
            $this->mail->Host = SMTP_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = SMTP_USER;
            $this->mail->Password = SMTP_PASS;
            $this->mail->Port = SMTP_PORT;
            $this->mail->CharSet = 'UTF-8';

            // Porta 465 costuma usar SSL/SMTPS; 587 usa STARTTLS
            $encryption = SMTP_ENCRYPTION;
            if ($encryption === '') {
                $encryption = SMTP_PORT === 465 ? 'ssl' : 'tls';
            }

            if ($encryption === 'ssl' || $encryption === 'smtps') {
                $this->mail->SMTPSecure = defined('\PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS')
                    ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                    : 'ssl';
            } else {
                $this->mail->SMTPSecure = defined('\PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS')
                    ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS
                    : 'tls';
            }

            $this->mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        }

        private function baseTemplate($titulo, $conteudoHtml) {
            return "<html><body style='font-family:Arial,sans-serif'><h3>{$titulo}</h3>{$conteudoHtml}<p style='margin-top:20px;color:#666'>Este é um e-mail automático.</p></body></html>";
        }

        private function enviar($toEmail, $toNome, $subject, $htmlBody, $altBody, $cc = []) {
            $this->mail->clearAddresses();
            $this->mail->clearCCs();
            $this->mail->clearAttachments();
            $this->mail->isHTML(true);
            $this->mail->addAddress($toEmail, $toNome);

            foreach ($cc as $email => $nome) {
                if (!empty($email)) {
                    $this->mail->addCC($email, $nome);
                }
            }

            $this->mail->Subject = $subject;
            $this->mail->Body = $htmlBody;
            $this->mail->AltBody = $altBody;
            $this->mail->send();
        }

        public function enviarNotificacaoChamadoAberto($chamado, $funcionario, $empresa, $tecnico = null) {
            if (!$this->enabled) {
                return false;
            }

            try {
                // Notificação operacional para contato da empresa de suporte
                if (!empty(SUPPORT_NOTIFICATION_EMAIL)) {
                    $subject = "[ABERTO] Chamado #{$chamado['id']} - {$chamado['titulo']}";
                    $body = $this->baseTemplate(
                        "Novo chamado aberto",
                        "<p><strong>Empresa:</strong> " . htmlspecialchars($empresa['nome'] ?? '-') . "</p>
                         <p><strong>Funcionário:</strong> " . htmlspecialchars($funcionario['nome'] ?? '-') . " (" . htmlspecialchars($funcionario['email'] ?? '-') . ")</p>
                         <p><strong>Título:</strong> " . htmlspecialchars($chamado['titulo']) . "</p>
                         <p><strong>Prioridade:</strong> " . htmlspecialchars($chamado['prioridade']) . "</p>
                         <p><strong>Descrição:</strong><br>" . nl2br(htmlspecialchars($chamado['descricao'] ?? '')) . "</p>"
                    );
                    $cc = [];
                    if ($tecnico && !empty($tecnico['email'])) {
                        $cc[$tecnico['email']] = $tecnico['nome'] ?? '';
                    }
                    $this->enviar(SUPPORT_NOTIFICATION_EMAIL, 'Atendimento TI', $subject, $body, "Chamado #{$chamado['id']} aberto", $cc);
                }

                // Confirmação ao solicitante
                if (!empty($funcionario['email'])) {
                    $subject = "Chamado #{$chamado['id']} Aberto - Sistema de Chamados T.I.";
                    $body = $this->baseTemplate(
                        "Chamado aberto com sucesso",
                        "<p>Seu chamado foi registrado.</p><p><strong>Título:</strong> " . htmlspecialchars($chamado['titulo']) . "</p>"
                    );
                    $this->enviar($funcionario['email'], $funcionario['nome'] ?? '', $subject, $body, "Chamado #{$chamado['id']} aberto com sucesso");
                }

                return true;
            } catch (\Exception $e) {
                error_log("Erro ao enviar e-mail (abertura): " . $this->mail->ErrorInfo);
                return false;
            }
        }

        public function enviarNotificacaoChamadoAtendido($chamado, $funcionario, $tecnico, $empresa) {
            if (!$this->enabled) {
                return false;
            }

            try {
                $cc = [];
                if (!empty($tecnico['email'])) {
                    $cc[$tecnico['email']] = $tecnico['nome'] ?? '';
                }

                if (!empty($funcionario['email'])) {
                    $subject = "Chamado #{$chamado['id']} em atendimento";
                    $body = $this->baseTemplate(
                        "Seu chamado está em atendimento",
                        "<p><strong>Empresa:</strong> " . htmlspecialchars($empresa['nome'] ?? '-') . "</p>
                         <p><strong>Técnico:</strong> " . htmlspecialchars($tecnico['nome'] ?? '-') . "</p>
                         <p><strong>Título:</strong> " . htmlspecialchars($chamado['titulo']) . "</p>"
                    );
                    $this->enviar($funcionario['email'], $funcionario['nome'] ?? '', $subject, $body, "Chamado #{$chamado['id']} em atendimento", $cc);
                }

                if (!empty(SUPPORT_NOTIFICATION_EMAIL)) {
                    $this->enviar(
                        SUPPORT_NOTIFICATION_EMAIL,
                        'Atendimento TI',
                        "[ATENDIMENTO] Chamado #{$chamado['id']} - {$chamado['titulo']}",
                        $this->baseTemplate("Chamado em atendimento", "<p><strong>Técnico:</strong> " . htmlspecialchars($tecnico['nome'] ?? '-') . "</p>"),
                        "Chamado #{$chamado['id']} em atendimento"
                    );
                }

                return true;
            } catch (\Exception $e) {
                error_log("Erro ao enviar e-mail (atendimento): " . $this->mail->ErrorInfo);
                return false;
            }
        }

        public function enviarNotificacaoChamadoFinalizado($chamado, $funcionario, $tecnico) {
            if (!$this->enabled) {
                return false;
            }

            try {
                $cc = [];
                if (!empty($tecnico['email'])) {
                    $cc[$tecnico['email']] = $tecnico['nome'] ?? '';
                }

                if (!empty($funcionario['email'])) {
                    $subject = "Chamado #{$chamado['id']} Finalizado - Sistema de Chamados T.I.";
                    $body = $this->baseTemplate(
                        "Chamado finalizado",
                        "<p>Seu chamado foi finalizado.</p><p><strong>Solução:</strong><br>" . nl2br(htmlspecialchars($chamado['solucao'] ?? '')) . "</p>"
                    );
                    $this->enviar($funcionario['email'], $funcionario['nome'] ?? '', $subject, $body, "Chamado #{$chamado['id']} finalizado", $cc);
                }

                if (!empty(SUPPORT_NOTIFICATION_EMAIL)) {
                    $this->enviar(
                        SUPPORT_NOTIFICATION_EMAIL,
                        'Atendimento TI',
                        "[FINALIZADO] Chamado #{$chamado['id']} - {$chamado['titulo']}",
                        $this->baseTemplate("Chamado finalizado", "<p><strong>Técnico:</strong> " . htmlspecialchars($tecnico['nome'] ?? '-') . "</p>"),
                        "Chamado #{$chamado['id']} finalizado"
                    );
                }

                return true;
            } catch (\Exception $e) {
                error_log("Erro ao enviar e-mail (finalização): " . $this->mail->ErrorInfo);
                return false;
            }
        }
    }
}
