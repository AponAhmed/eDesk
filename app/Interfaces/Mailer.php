<?php

namespace App\Interfaces;

// Define the Mailer interface
interface Mailer
{
    public function sendEmail($to, $subject, $body, $options, $attachments = []);

    public function checkConnection();

    public function updateCount();
}
