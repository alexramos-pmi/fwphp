<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // Configurações do servidor SMTP da HostGator
        $this->mailer->isSMTP();
        $this->mailer->Host = 'mail.ramcon.com.br';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'blog@ramcon.com.br';
        $this->mailer->Password = 'Al521140';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;

        // Configurações adicionais
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom('blog@ramcon.com.br', 'Ramos Construções e Reformas');

        // Ativar depuração para diagnosticar problemas
        $this->mailer->SMTPDebug = 0; // 0 = off, 1 = comandos do cliente, 2 = comandos e respostas do servidor
        $this->mailer->Debugoutput = 'html'; // Formato de saída da depuração
    }

    public function getErrors()
    {
        return $this->mailer->ErrorInfo;
    }

    /**
     * Envia um e-mail utilizando um template HTML.
     *
     * @param string $destinatario Endereço de e-mail do destinatário.
     * @param string $assunto Assunto do e-mail.
     * @param string $mensagem Corpo do e-mail em HTML.
     * @param array $anexos Lista de caminhos para arquivos a serem anexados.
     * @return bool Retorna true em caso de sucesso, false caso contrário.
     */
    public function enviar(string $destinatario, string $assunto, string $mensagem, array $anexos = []): bool
    {
        try
        {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = $assunto;
            $this->mailer->isHTML(true);

            // Carrega o template HTML
            $template = file_get_contents(__DIR__ . '/email_template.html');

            // Substitui os placeholders pelos valores reais
            $corpoEmail = str_replace(
                ['{subject}', '{message}', '{year}'],
                [$assunto, $mensagem, date('Y')],
                $template
            );

            $this->mailer->Body = $corpoEmail;

            // Adiciona anexos, se houver
            foreach($anexos as $anexo)
            {
                if(file_exists($anexo))
                {
                    $this->mailer->addAttachment($anexo);
                }
            }

            return $this->mailer->send();
        }
        catch(Exception $e)
        {
            // Log de erro ou tratamento conforme necessário
            error_log('Erro ao enviar e-mail: ' . $e->getMessage());
            return false;
        }
    }
}