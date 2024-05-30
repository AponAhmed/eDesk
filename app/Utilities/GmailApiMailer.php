<?php

namespace App\Utilities;

use App\Interfaces\MailSender;
use Google\Client;
use Google\Service\Gmail\Draft;
use Google\Service\Gmail\Message;
use Google\Service\Gmail;
use Google\Service\Gmail\Label;
use Google\Service\Gmail\ModifyMessageRequest;

use App\Models\Sender;
use Exception;

// Implement GmailApiMailer using Gmail API
class GmailApiMailer implements MailSender
{
    private $credentials;
    private $configured = false;
    public $connect = false;
    private $client;
    private $to;
    private $subject;
    private $body;
    private $attachments;
    private $options;
    public $SentBoxCustomLabel = "";

    public function __construct(private Sender $sender)
    {
        $this->createGoogleClient();
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
        // Code to send email using Gmail API
        $service = new Gmail($this->client);

        // Create a new Message instance
        $message = $this->createMessage();

        // Send the message
        try {
            $service->users_messages->send('me', $message);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function checkConnection()
    {
        return $this->connect; // Placeholder return value
    }


    public function updateCount()
    {
        $this->sender->updateCount();
    }

    private function createGoogleClient()
    {
        $credentials = env('G_CREDENTIALS', "");
        if ($credentials != "") {
            $this->configured = true;
        }
        $this->credentials = json_decode(stripslashes($credentials), true);
        if ($this->configured) {
            $this->client = $this->createClient();
        }
    }

    public function createClient($authCode = false)
    {
        $client = new Client();
        $client->setApplicationName("eDesk");
        $client->setRedirectUri("https://edesk.siatexmail.com/google-auth-redirect"); //Static URL Just For Test
        $client->setScopes(Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig($this->credentials);
        $client->setAccessType("offline");
        $client->setPrompt("select_account consent");

        if ($this->sender->auth_token) {
            // $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $client->setAccessToken($this->sender->auth_token);
        }
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            //echo "Expired";
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                try {
                    $res = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    if (isset($res['error'])) {
                        if ($res['error'] && !$authCode) {
                            $this->sender->auth_token = "";
                            $this->sender->update();
                            $this->connect = false;
                            return $client;
                        }
                    }
                } catch (Exception $e) {
                    echo "Not Geting Refresh Token; - " . $e;
                    $this->connect = false;
                    return $client;
                }
            } elseif ($authCode) {
                // echo "Weating For Token In redirect";                
                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists("error", $accessToken)) {
                    throw new Exception(join(", ", $accessToken));
                }
            } else {
                //echo "Revoke";
                $this->connect = false;
                return $client;
            }
            $this->sender->auth_token = $client->getAccessToken();
            //var_dump($this->token);
            $this->sender->update();
        } else {
            //echo "<p>not expired</p>";
        }
        //echo "Connected";
        $this->connect = true;
        return $client;
    }

    function getProfile()
    {
        $service = new Gmail($this->client);
        return $service->users->getProfile('me');
    }

    /**
     * @param Gmail $service
     * @return array|false All Labels
     */
    function getExistingLabels($service)
    {
        return $service->users_labels->listUsersLabels('me');
    }

    /**
     * @param Message $service
     * @param Gmail $service
     */
    function modifyLabels($message, $service)
    {
        $modify = new ModifyMessageRequest();
        if (!empty($this->SentBoxCustomLabel)) { //After Sent Box Modify
            $label = $this->getLabel($this->SentBoxCustomLabel, $service);
            $modify->setAddLabelIds([$label->getID()]);
        }
        $service->users_messages->modify('me', $message->getId(), $modify);
    }

    /**
     * Return Existing Label or by creating a new one
     * @param string $label
     * @param  $service
     * @param Label $label
     */
    function getLabel($labelName, $service)
    {
        $label = new Label();
        $label->setName($labelName);
        $existingLabels = $this->getExistingLabels($service);

        $labelExists = false;
        foreach ($existingLabels as $existingLabel) {
            if ($existingLabel->getName() === $labelName) {
                $label = $existingLabel;
                $labelExists = true;
                break;
            }
        }

        if (!$labelExists) {
            $label = $service->users_labels->create('me', $label);
        }
        return $label;
    }


    function loginButton()
    {
        $link = $this->client->createAuthUrl();
        echo "<a href='$link' class=\"google-login button action\"><span style=\"padding: 4px 0;\" class=\"dashicons dashicons-google\"></span> Login</a>";
    }

    /**
     * @param $sender string sender email address
     * @param $to string recipient email address
     * @param $subject string email subject
     * @param $messageText string email text
     * @return Message
     */
    function createMessage()
    {
        $message = new Message();
        $rawMessageString = $this->createMessageMIME();
        $rawMessage = strtr($rawMessageString, array('+' => '-', '/' => '_'));
        $message->setRaw($rawMessage);
        return $message;
    }

    public function createMessageMIME()
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer();

        $mail->CharSet = "UTF-8";
        $mail->SetFrom($this->options['fromEmail'], $this->options['fromName']);
        $mail->From = $this->options['fromEmail'];
        $mail->FromName = $this->options['fromName'];

        $mail->addAddress($this->to, $this->options['toName']);     //Add a recipient

        if ($this->options['Return-Path']) {
            $mail->addReplyTo($this->options['Return-Path']);
        }
        if (isset($this->options['CC']) && !empty($this->options['CC'])) {
            $mail->addCC($this->options['CC']);
        }
        if (isset($this->options['BCC']) && !empty($this->options['BCC'])) {
            $mail->addBCC($this->options['BCC']);
        }
        $mail->XMailer = "GmailAPI-MIME::PHPMailer";
        $mail->MessageID = "<" . md5('HELLO' . (idate("U") - 1000000000) . uniqid()) . "@gmail.com>";

        if (count($this->attachments) > 0) {
            foreach ($this->attachments as $attach) {
                $mail->addAttachment($attach['path'], $attach['name']);
            }
        }

        $mail->isHTML(true);
        $mail->Subject = $this->subject;
        $mail->Body = $this->body;

        //Attachment

        //Pre send to generate MIME
        $mail->preSend();
        $mime = $mail->getSentMIMEMessage();
        $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
        return $mime;
    }
}
