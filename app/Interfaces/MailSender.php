<?php

namespace App\Interfaces;

// Define the Mailer interface
interface MailSender
{
    public function sendEmail($to, $subject, $body, $options, $attachments = []);

    public function checkConnection();

    public function updateCount();
}
