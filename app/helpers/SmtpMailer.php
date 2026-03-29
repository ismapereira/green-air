<?php
/**
 * SmtpMailer — Envio de e-mails via SMTP sem dependências externas
 * Suporta Gmail, Outlook, e qualquer servidor SMTP com TLS/SSL
 */
class SmtpMailer
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $fromEmail;
    private string $fromName;
    private string $encryption;
    private int $timeout;

    public function __construct()
    {
        $this->host       = env('MAIL_HOST', 'smtp.gmail.com');
        $this->port       = (int) env('MAIL_PORT', '587');
        $this->username   = env('MAIL_USERNAME', '');
        $this->password   = env('MAIL_PASSWORD', '');
        $this->fromEmail  = env('MAIL_FROM_ADDRESS', 'noreply@greenair.com');
        $this->fromName   = env('MAIL_FROM_NAME', 'Green Air');
        $this->encryption = env('MAIL_ENCRYPTION', 'tls');
        $this->timeout    = 30;
    }

    /**
     * Verifica se as credenciais SMTP estão configuradas
     */
    public function isConfigured(): bool
    {
        return !empty($this->host) && !empty($this->username) && !empty($this->password);
    }

    /**
     * Enviar e-mail
     */
    public function send(string $to, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $socket = $this->connect();
            if (!$socket) return false;

            // Greeting
            $this->getResponse($socket);

            // EHLO
            $this->sendCommand($socket, "EHLO " . gethostname());

            // STARTTLS
            if ($this->encryption === 'tls' && $this->port === 587) {
                $this->sendCommand($socket, "STARTTLS");
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
                $this->sendCommand($socket, "EHLO " . gethostname());
            }

            // AUTH LOGIN
            $this->sendCommand($socket, "AUTH LOGIN");
            $this->sendCommand($socket, base64_encode($this->username));
            $response = $this->sendCommand($socket, base64_encode($this->password));

            if (strpos($response, '235') === false) {
                fclose($socket);
                return false;
            }

            // MAIL FROM
            $this->sendCommand($socket, "MAIL FROM:<{$this->fromEmail}>");

            // RCPT TO
            $this->sendCommand($socket, "RCPT TO:<{$to}>");

            // DATA
            $this->sendCommand($socket, "DATA");

            // Headers & Body
            $boundary = md5(uniqid(time()));
            $message  = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $message .= "To: {$to}\r\n";
            $message .= "Subject: {$subject}\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
            $message .= "\r\n";

            // Plain text part
            if ($textBody) {
                $message .= "--{$boundary}\r\n";
                $message .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
                $message .= $textBody . "\r\n";
            }

            // HTML part
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
            $message .= $htmlBody . "\r\n";
            $message .= "--{$boundary}--\r\n";

            // End with period
            $message .= ".";
            $this->sendCommand($socket, $message);

            // QUIT
            $this->sendCommand($socket, "QUIT");
            fclose($socket);

            return true;
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    private function connect()
    {
        $host = $this->host;
        if ($this->encryption === 'ssl') {
            $host = 'ssl://' . $host;
        }

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $socket = @stream_socket_client(
            "{$host}:{$this->port}",
            $errno, $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        return $socket ?: false;
    }

    private function sendCommand($socket, string $command): string
    {
        fwrite($socket, $command . "\r\n");
        return $this->getResponse($socket);
    }

    private function getResponse($socket): string
    {
        $response = '';
        stream_set_timeout($socket, $this->timeout);
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        return $response;
    }

    private function logError(string $message): void
    {
        $dir = ROOT_PATH . '/storage/logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $logFile = $dir . '/mail_errors.log';
        $entry = '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n";
        file_put_contents($logFile, $entry, FILE_APPEND);
    }
}
