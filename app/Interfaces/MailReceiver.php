<?php

namespace App\Interfaces;

interface MailReceiver
{
    public function getEmails($count = 5);
    public function checkConnection();
}
