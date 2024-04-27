<?php

namespace App\Utilities;

use App\Interfaces\Mailer;
use Google\Client;
use Google\Service\Gmail\Draft;
use Google\Service\Gmail\Message;
use Google\Service\Gmail;
use Google\Service\Gmail\Label;
use Google\Service\Gmail\ModifyMessageRequest;

use App\Models\Sender;
use Exception;

// Implement GmailApiMailer using Gmail API
class GmailApiMailer implements Mailer
{
    private $client;

    public function __construct(Sender $sender)
    {
        $this->client = $this->createGoogleClient($sender->auth_token);
    }

    public function sendEmail($to, $subject, $body, $options)
    {
        // Code to send email using Gmail API
        $service = new Gmail($this->client);

        // Create a new Message instance
        $message = new Message();
        $message->setRaw($this->createMessage($to, $subject, $body, $options));

        // Send the message
        try {
            $service->users_messages->send('me', $message);
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'An error occurred: ' . $e->getMessage();
        }
    }

    public function checkConnection()
    {
        // Check connection status with Gmail API
        // You might implement this method to verify if the Google_Client instance is properly configured
        return true; // Placeholder return value
    }

    private function createGoogleClient($authToken)
    {
        $client = new Client();
        $client->setAccessToken($authToken);

        // Load Google application credentials from environment
        $credentials = env('G_CREDENTIALS');
        $client->setAuthConfig(json_decode($credentials, true));

        return $client;
    }

    private function createMessage($to, $subject, $body, $options)
    {
        // Create a new MimeMessage instance
        $mime = new Mail_mime(array('eol' => "\r\n"));

        // Add recipient
        $mime->setTXTBody($body);
        $mime->setHTMLBody($body);
        $mimeParams = array('html_charset' => 'UTF-8', 'text_charset' => 'UTF-8', 'head_charset' => 'UTF-8');
        $body = $mime->get($mimeParams);
        $headers = $mime->headers($options);

        return rtrim(strtr(base64_encode($headers . "\r\n" . $body), '+/', '-_'), '=');
    }
}
