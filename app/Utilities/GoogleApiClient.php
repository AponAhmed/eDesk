<?php

namespace App\Utilities;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Oauth2;

class GoogleApiClient
{

    private $credentials;
    private $configured = false;
    public $connect = false;
    private $client;

    private $token;



    public function __construct()
    {
        $this->createGoogleClient();
    }

    private function createGoogleClient()
    {
        $credentials = env('G_CREDENTIALS', "");
        if ($credentials != "") {
            $this->configured = true;
        }
        $this->credentials = json_decode(stripslashes($credentials), true);
        if ($this->configured) {
            $this->createClient();
        }
    }




    public function createClient()
    {
        $this->client = new Client();
        $this->client->setApplicationName("eDesk");
        $this->client->setScopes(Gmail::MAIL_GOOGLE_COM);
        $this->client->setAuthConfig($this->credentials);
        $this->client->setAccessType("offline");
        $this->client->setPrompt("select_account consent");
        $this->client->setRedirectUri("https://edesk.siatexmail.com/google-auth-redirect"); //Static URL Just For Test

        // if ($this->token) {
        //     $client->setAccessToken($this->token);
        // }

        // // If there is no previous token or it's expired.
        // if ($client->isAccessTokenExpired()) {
        //     //echo "Expired";
        //     // Refresh the token if possible, else fetch a new one.
        //     if ($client->getRefreshToken()) {
        //         try {
        //             $res = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        //             if (isset($res['error'])) {
        //                 if ($res['error'] && !$authCode) {
        //                     $this->sender->auth_token = "";
        //                     $this->sender->update();
        //                     $this->connect = false;
        //                     return $client;
        //                 }
        //             }
        //         } catch (Exception $e) {
        //             echo "Not Geting Refresh Token; - " . $e;
        //             $this->connect = false;
        //             return $client;
        //         }
        //     } elseif ($authCode) {
        //         // echo "Weating For Token In redirect";                
        //         // Exchange authorization code for an access token.
        //         $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
        //         $client->setAccessToken($accessToken);

        //         // Check to see if there was an error.
        //         if (array_key_exists("error", $accessToken)) {
        //             throw new Exception(join(", ", $accessToken));
        //         }
        //     } else {
        //         //echo "Revoke";
        //         $this->connect = false;
        //         return $client;
        //     }
        //     $this->sender->auth_token = $client->getAccessToken();
        //     //var_dump($this->token);
        //     $this->sender->update();
        // } else {
        //     //echo "<p>not expired</p>";
        // }
        // //echo "Connected";
        // $this->connect = true;
        // return $client;
    }

    function AuthLink()
    {
        return $this->client->createAuthUrl();
    }
}
