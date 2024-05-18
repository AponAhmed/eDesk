<?php

namespace App\Http\Controllers;

use App\Models\GMessage;
use App\Models\Sender;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;
use Ddeboer\Imap\Message\EmailAddress;

class CronController extends Controller
{

    private $accounts;

    public function __construct()
    {
        $this->accounts = Sender::where('status', '1')->get();
    }
    //
    public function get()
    {
        foreach ($this->accounts as $account) {
            try {
                if ($account->auth_login_type != 1) {
                    $this->getByIMAP($account);
                } else {
                    $this->getByAPI($account);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    private function getByAPI(Sender $account)
    {
        echo "Mail Get by API: " . $account->email_address . "<br>";
        echo "::in Progress::";
    }

    private function getByIMAP(Sender $account)
    {
        $messages = $account->getMailReceiver()->getEmails(5);
        foreach ($messages as $message) {
            $messageObject = $message['object'];
            // Extract relevant data from the message object
            $name = $message['from']->getName() ?? 'Unknown Sender';
            $email = $message['from']->getAddress();
            $subject = $message['subject'] ?? 'No Subject';
            $body = $message['body'] ?? 'No Content';
            $header = json_encode($message['headers']);

            // Create the GMessage record
            try {
                GMessage::create([
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $body,
                    'sender_id' => $account->id,
                    'reminder' => 0, // Set default reminder value, modify as needed
                    'header' => $header,
                    'labels' => "inbox,unread",
                ]);
                echo "Mail Received by IMAP from: " . $name . "<br>";
                
            } catch (\Throwable $th) {
                // Handle any errors here
                echo $th->getMessage();
            }
        }
    }
}
