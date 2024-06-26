<?php

namespace App\Utilities;

use App\Models\Settings;
use Exception;
use Google\Client;
use Google\Service\Gmail\Draft;
use Google\Service\Gmail\Message;
use Google\Service\Gmail;
use Google\Service\Gmail\Label;
use Google\Service\Gmail\ModifyMessageRequest;

/**
 * Description of GmailApi
 *
 * @author apon
 */
class GmailApi
{

    //put your code here
    public $client;
    private $tokenField = "gmailApiToken";
    private $credentials;
    public $token = [];
    public $connect = false;
    public $configured = false;
    //Sender Property
    public $to;
    public $subject;
    public $body;
    public $options;
    public $SentBoxCustomLabel = "";

    public function __construct()
    {
        $credentials = env('G_CREDENTIALS', "");
        if ($credentials != "") {
            $this->configured = true;
            $this->getAccessToken();
        }
        $this->credentials = json_decode(stripslashes($credentials), true);
        if ($this->configured) {
            $this->client = $this->create_client();
        }
    }

    function storeAccessToken()
    {
        Settings::set($this->tokenField, json_encode($this->token));
    }

    function getAccessToken()
    {
        $jsonStr = Settings::get($this->tokenField);
        if ($jsonStr != "") {
            $this->token = json_decode($jsonStr, true);
        }
    }

    public function get2Redirect()
    {
        if (isset($_GET["code"])) {
            return true;
        }
        return false;
    }

    function getProfile()
    {
        $service = new Gmail($this->client);
        return $service->users->getProfile('me');
    }

    public function create_client()
    {
        $client = new Client();
        $client->setApplicationName("eDesk");
        $client->setScopes(Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig($this->credentials);
        $client->setAccessType("offline");
        $client->setPrompt("select_account consent");
        $client->setRedirectUri("https://edesk.siatexmail.com/settings/general"); //Static URL Just For Test

        //$client->setRedirectUri("http://localhost/GmailApi"); // Must Match with credential's redirect URL
        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        //$tokenPath = 'token.json';
        if ($this->token) {
            // $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $client->setAccessToken($this->token);
        }
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            //echo "Expired";
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                try {
                    $res = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    if (isset($res['error'])) {
                        if ($res['error'] && !$this->get2Redirect()) {
                            $this->token = "";
                            $this->storeAccessToken();
                            $this->getAccessToken();
                            //var_dump($this->token);
                            $this->connect = false;
                            return $client;
                        }
                    }
                } catch (Exception $e) {
                    echo "Not Geting Refresh Token; - " . $e;
                    //echo $e;
                    //$this->department->oauth_token = "";
                    //$this->department->save();
                    $this->connect = false;
                    return $client;
                }
            } elseif ($this->get2Redirect()) {
                // echo "Weating For Token In redirect";

                $authCode = $_GET["code"];
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
            $this->token = $client->getAccessToken();
            //var_dump($this->token);
            $this->storeAccessToken();
        } else {
            //echo "<p>not expired</p>";
        }
        //echo "Connected";
        $this->connect = true;
        return $client;
    }

    /**
     * To Send Email Via Gmail API
     *
     * @param  $to Receiver array or string for single send
     * @param string $subject Subject line of email
     * @param string $message Mail Body
     * @param array $options Mail Sending options
     */
    public function send($to, $subject = "", $message = "", $options = [], $attachments = [])
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $message;

        $defaultOption = [
            'fromName' => "",
            'fromEmail' => "",
            'toName' => "",
            'CC' => "",
            'BCC' => "",
            "Return-Path" => "",
            "ReadRecept" => "",
        ];
      
        $options = array_merge($defaultOption, $options);

        $this->options = $options;

       
        $service = new Gmail($this->client);
        //var_dump($service);
        // Print the labels in the user's account.
        //FormEmail
        $FormEmail = !empty($options['fromEmail']) ? $options['fromEmail'] : "me";
        $message =  $this->createMessage($FormEmail, $to, $subject, $message, $options, $attachments);
        //$draft = new Google_Service_Gmail_Draft();
        //$draft->setMessage($message);
        //$draft = $service->users_drafts->create('me', $draft);

        $message = $service->users_messages->send('me', $message);
        //var_dump($message);
        // var_dump($this->client);
        if (isset($message->id) && !empty($message->id)) {
            $this->modifyLabels($message, $service);
            return true;
        }
        return false;
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

    /**
     * @param $sender string sender email address
     * @param $to string recipient email address
     * @param $subject string email subject
     * @param $messageText string email text
     * @return Message
     */
    function createMessage($sender, $to, $subject, $messageText, $options, $attach = [])
    {
        $message = new Message();
        $rawMessageString = $this->createMessageMIME($attach);
        $rawMessage = strtr($rawMessageString, array('+' => '-', '/' => '_'));
        $message->setRaw($rawMessage);
        return $message;
    }

    /**
     * To Create MIME of Mail By PHPMailer
     *
     */
    public function createMessageMIME($attachments = [])
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

        if ($this->options['ReadRecept'] != "") {
            $mail->addCustomHeader('Disposition-Notification-To', $this->options['fromName'] . "<" . $this->options['ReadRecept'] . ">"); //" . $this->options['ReadRecept'] . "
        }

        if (isset($this->options['CC']) && !empty($this->options['CC'])) {
            $mail->addCC($this->options['CC']);
        }
        if (isset($this->options['BCC']) && !empty($this->options['BCC'])) {
            $mail->addBCC($this->options['BCC']);
        }
        $mail->XMailer = "GmailAPI-MIME::PHPMailer";
        $mail->MessageID = "<" . md5('HELLO' . (idate("U") - 1000000000) . uniqid()) . "@gmail.com>";

        if (count($attachments) > 0) {
            foreach ($attachments as $attach) {
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
        //dd($mime);
        $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
        return $mime;
    }

    /**
     * @param $service Google_Service_Gmail an authorized Gmail API service instance.
     * @param $user string User's email address or "me"
     * @param $message Google_Service_Gmail_Message
     * @return Draft
     */
    function createDraft($service, $user, $message)
    {
        $draft = new Draft();
        $draft->setMessage($message);

        try {
            $draft = $service->users_drafts->create($user, $draft);
            print 'Draft ID: ' . $draft->getId();
        } catch (Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }

        return $draft;
    }

    function loginButton()
    {
        $link = $this->client->createAuthUrl();
        echo "<a href='$link' class=\"google-login button action\"><span style=\"padding: 4px 0;\" class=\"dashicons dashicons-google\"></span> Login</a>";
    }

    /**
     * @param $service Google_Service_Gmail an authorized Gmail API service instance.
     * @param $userId string User's email address or "me"
     * @param $message Google_Service_Gmail_Message
     * @return null|Message
     */
    function sendMessage($service, $userId, $message)
    {
        try {
            $message = $service->users_messages->send($userId, $message);
            print 'Message with ID: ' . $message->getId() . ' sent.';
            return $message;
        } catch (Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }

        return null;
    }

    function getClientIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];
            return $_SERVER["REMOTE_ADDR"];
        }
        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');

        return getenv('REMOTE_ADDR');
    }

    function convertip($ip)
    {
        //?fields=country,city,lat,lon
        $url = "http://ip-api.com/json/$ip";
        $content = file_get_contents($url);
        $ob = json_decode($content);
        if (isset($ob->status) && $ob->status == 'success') {
            return $ob->city . "," . $ob->country;
        }
    }
}
