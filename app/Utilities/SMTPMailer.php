<?php

namespace App\Utilities;

use App\Interfaces\MailSender;
use App\Models\Sender;
use PHPMailer\PHPMailer\PHPMailer;

// Implement SMTPMailer using PHPMailer
class SMTPMailer implements MailSender
{
    private $mailer;
    private $smtpConfig;
    private $to;
    private $subject;
    private $body;
    private $attachments;
    private $options;

    public function __construct(private Sender $sender)
    {
        $this->mailer = new PHPMailer;
        $this->smtpConfig = $sender->smtp_options;
        $this->configureMailer();
    }

    public function sendEmail($to, $subject, $body, $options, $attachments = [])
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;

        $defaultOption = [
            'fromName' => "",
            'fromEmail' => "",
            'toName' => "",
            'CC' => "",
            'BCC' => "",
            "Return-Path" => ""
        ];

        $options = array_merge($defaultOption, $options);

        $this->options = $options;

        $this->mailer->SetFrom($this->options['fromEmail'], $this->options['fromName']);
        $this->mailer->From = $this->options['fromEmail'];
        $this->mailer->FromName = $this->options['fromName'];


        $this->mailer->addAddress($to, $this->options['toName']);     //Add a recipient

        if ($this->options['Return-Path']) {
            $this->mailer->addReplyTo($this->options['Return-Path']);
        }
        if (isset($this->options['CC']) && !empty($this->options['CC'])) {
            $this->mailer->addCC($this->options['CC']);
        }
        if (isset($this->options['BCC']) && !empty($this->options['BCC'])) {
            $this->mailer->addBCC($this->options['BCC']);
        }
        $this->mailer->XMailer = "GmailAPI-MIME::PHPMailer";
        $this->mailer->MessageID = "<" . md5('HELLO' . (idate("U") - 1000000000) . uniqid()) . "@gmail.com>";

        if (count($attachments) > 0) {
            foreach ($attachments as $attach) {
                $this->mailer->addAttachment($attach['path'], $attach['name']);
            }
        }


        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;

        if (!$this->mailer->send()) {
            return false;
        } else {
            $this->updateCount();
            return true;
        }
    }

    public function checkConnection()
    {
        return $this->mailer->smtpConnect();
    }

    public function updateCount()
    {
        $this->sender->updateCount();
    }

    private function configureMailer()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->smtpConfig['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->smtpConfig['account'];
        $this->mailer->Password = $this->smtpConfig['password'];
        $this->mailer->SMTPSecure = $this->smtpConfig['security'];
        $this->mailer->Port = $this->smtpConfig['port'];
    }
}
