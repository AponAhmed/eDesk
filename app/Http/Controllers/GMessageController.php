<?php

namespace App\Http\Controllers;

use App\Models\GMessage as Message;
use App\Models\Reply;
use App\Models\Sender;
use App\Models\Settings;
use App\Utilities\GmailApi;
use App\Utilities\Helper;
use DOMDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Psy\VarDumper\Dumper;

class GMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    static $signature = "";


    public function __construct()
    {
        self::$signature = "<p>" . nl2br(Settings::get('gdesk_signature')) . "</p>";
        self::GetReminder();
        $this->middleware('auth');
    }


    //return View To create a new message
    function newMessage()
    {
        $emails = [];
        $emails[Settings::get('gadmin_email')] = Settings::get('gadmin_name');

        return view('new-message', ['senders' => Sender::all(), 'emails' => $emails]);
    }

    function sendNew(Request $request)
    {
        $attachment = [];
        // Check if the request has files
        if ($request->hasFile('attachments')) {
            $files = $request->file('attachments');

            foreach ($files as $file) {
                // Get the original file name
                $originalName = $file->getClientOriginalName();

                // Move the uploaded file to a temporary storage directory
                $tempPath = $file->storeAs('temp', $originalName);

                // Get the full path of the stored file
                $fullPath = storage_path("app/{$tempPath}");

                // Add the file information to the array
                $attachment[] = [
                    'path' => $fullPath,
                    'name' => $originalName,
                ];
            }
        }

        try {
            $to = $request->get('toaddress');
            if (empty($to)) {
                throw new Exception("Could not find recipient");
            }
            $subject = $request->get('subject');
            $messageBody = $request->get('message');
            $cc = $request->get('cc');

            //Return path
            $returnToStr = $request->get('return_to');
            $return = explode(":", $returnToStr);
            $AdminName = $return[0];
            $AdminEmail = $return[1];

            if ($request->has('return_to_custom') && !empty($request->get('return_to_custom'))) {
                $customEmail = $request->input('return_to_custom');
                // Check if the email is valid (optional, Laravel's email validation rule above already checks for this)
                if (filter_var($customEmail, FILTER_VALIDATE_EMAIL)) {
                    // Assign the email to AdminEmail
                    // You can add your logic here to assign the email
                    $AdminEmail = $customEmail;
                } else {
                    throw new Exception('Invalid email address provided');
                }
            }


            $sender = Sender::find($request->get('sender'));
            $mailer = $sender->getMailer();



            if (!$mailer->checkConnection()) {
                throw new Exception("Message not Sent: Could not connect to Email Server");
            }
            $options = [
                'fromName' => $AdminName,
                'fromEmail' => $sender->email_address,
                'Return-Path' => $AdminEmail,
                'toName' => "",
                'CC' => $cc,
            ];
            $messageBody = nl2br($messageBody) . " " . self::$signature;
            $sent = $mailer->sendEmail($to, $subject, $messageBody, $options, $attachment);
            if ($sent) {
                Session::flash('success', 'Message Succefully Sent.');
            } else {
                throw new Exception("Message not Sent !");
            }
            //Session::flash('success', 'Message Succefully Sent.');
        } catch (\Throwable $th) {
            throw new Exception("Message not Sent: " . $th->getMessage());
        }
    }

    function info($id)
    {
        $message = Message::find($id);
        echo '<div class="max-w-md mx-autoshadow-md p-6 rounded-md">
        <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
        <div class="mb-2 flex items-center">
            <label class="block text-gray-700 text-sm font-bold w-20" for="name">Name:</label>
            <span class="text-gray-800 text-lg" id="name">' . $message->name . '</span>
        </div>
        <div class="mb-2 flex items-center">
            <label class="block text-gray-700 text-sm font-bold w-20" for="email">Email:</label>
            <span class="text-gray-800 text-lg" id="email">' . $message->email . '</span>
        </div>
        <div class="mb-1 flex items-center">
            <label class="block text-gray-700 text-sm font-bold w-20" for="ip">Time:</label>
            <span class="text-gray-800 text-lg" id="ip">' . $message->created_at . '</span>
        </div>
        <div class="mb-1 flex items-center">
            <label class="block text-gray-700 text-sm font-bold w-20" for="ip">IP:</label>
            <span class="text-gray-800 text-lg" id="ip">' . $message->ip . '</span>
        </div>

        <div class="mb-1 flex items-center">
            <label class="block text-gray-700 text-sm font-bold w-20" for="country">Country:</label>
            <span class="text-gray-800 text-lg" id="country">' . $message->country() . '</span>
        </div>
        </div>
        <button class="mt-4 text-blue-500 hover:underline" onclick="more(this)">More</button>
            <div class="colapse-able hidden">
                <label class="block text-gray-700 text-sm font-bold mt-4" for="json">JSON Data:</label>
                <pre class="text-sm text-gray-800">' . json_encode($message->senderData(), JSON_PRETTY_PRINT) . '</pre>
            </div>
        </div>';
    }

    public function deleteAll(Request $request)
    {
        if ($request->has('box') && $request->get('box') == 'trash') {
            $messages = $this->getMessageByBox($request->get('box'));
            return $messages->delete();
        }
    }

    function multipleAction(Request $request)
    {
        if ($request->has('action')) {
            $action = $request->get('action');
            $ids = $request->get('ids');
            // Use the retrieved IDs in a whereIn query
            $messages = Message::whereIn('id', $ids)->get();

            try {
                foreach ($messages as $message) {

                    switch ($action) {
                        case 'spam':
                            $message->addLabel('spam');
                            # code...
                            break;
                        case 'local':
                            $message->addLabel('local');
                            # code...
                            break;
                        case 'notlocal':
                            $message->removeLabel('local');
                            # code...
                            break;
                        case 'trash':
                            $message->addLabel('trash');
                            break;
                        case 'untrash':
                            $message->removeLabel('trash');
                            break;
                        case 'delete':
                            $message->delete();
                            break;
                        default:
                            # code...
                            break;
                    }
                }
                Session::flash('success', 'Action successfully processed');
                return response()->json(['error' => false, 'message' => '']);
            } catch (Exception $e) {
                return response()->json(['error' => true, 'message' => 'Action Failed ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Local Label Modify
     */
    function modifiLabels(Request $request)
    {
        try {
            //code...
            if ($request->has('id')) {
                $message = Message::find($request->get('id'));
                $currentLabels = $message->getLabels();

                $add = $request->get('add'); // Assuming $add is an array of labels to be added.
                if ($add) {
                    $currentLabels = array_merge($currentLabels, $add);
                }
                $remove = $request->get('remove'); // Assuming $remove is an array of labels to be removed.
                if ($remove) {
                    $currentLabels = array_filter(array_diff($currentLabels, $remove));
                }
                $message->updateLabels($currentLabels);

                return response()->json(['error' => false, 'message' => 'Success']);
            } else {
                throw new Exception("You must provide a unique identifier of message");
            }
        } catch (Exception $e) {
            //throw $th;
            return response()->json(['error' => true, 'message' => 'Labels Modification Error,' . $e->getMessage()]);
        }
    }

    function makeSpam($id)
    {
        try {
            //code...
            Message::find($id)->removeLabel('inbox')->addLabel('spam');
            return response()->json(['error' => false]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => false]);
        }
    }

    function notSpam($id)
    {
        try {
            //code...
            Message::find($id)->removeLabel('spam')->addLabel('inbox');
            return response()->json(['error' => false]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => false]);
        }
    }

    function makeLocal($id)
    {
        try {
            //code...
            Message::find($id)->removeLabel('inbox')->addLabel('local');
            return response()->json(['error' => false]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => false]);
        }
    }

    function notLocal($id)
    {
        try {
            //code...
            Message::find($id)->removeLabel('local')->addLabel('inbox');
            return response()->json(['error' => false]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => false]);
        }
    }

    function redirect($id)
    {
        $emails = [];
        $emails[Settings::get('gadmin_email')] = Settings::get('gadmin_name');
        return view('redirect', array('id' => $id, 'emails' => $emails));
    }

    function reply($id)
    {
        $prefix = Settings::get('ai_prompt_prefix', 'Write a reply in short-sentence to this email using the hints below:');
        $plainText = "$prefix\n\n" . $this->getBodyText($id);

        $plainText = $this->removeMobileNumbersAndEmails($plainText);

        $emails = [];
        $emails[Settings::get('gadmin_email')] = Settings::get('gadmin_name');
        return view('reply', array('id' => $id, 'emails' => $emails, 'query' => $plainText));
    }

    function removeMobileNumbersAndEmails($text)
    {
        // Remove mobile numbers
        $text = preg_replace('/\b(?:\+\d{1,2}\s?)?(?:\d{3}[-\.\s]?)?\d{3}[-\.\s]?\d{4}\b/', '', $text);
        $text = preg_replace('/^((?:[1-9][0-9 ().-]{5,28}[0-9])|(?:(00|0)( ){0,1}[1-9][0-9 ().-]{3,26}[0-9])|(?:(\+)( ){0,1}[1-9][0-9 ().-]{4,27}[0-9]))$/', '', $text);

        $text = preg_replace('/^((091|\+91)?|\((091|\+91)?\)|(91)?|\(91\)|0)? ?[7-9][0-9]{9}$/', '', $text);
        $text = preg_replace('/\b\d{4}\s\d{5}\s\d{5}\b|\b\d{5}\s\d{5}\b/', '', $text);

        $text = preg_replace('/^(?:(?:\+|00)33[\s.-]?[67]|0[\s.-]?[67])(?:[\s.-]*\d{2}){4}$/', '', $text);

        // Remove email addresses
        $text = preg_replace('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/', '', $text);

        // Regular expression pattern to match WhatsApp number and following digits until newline
        $re = '/\**WhatsApp:.*R?/';
        // Replace WhatsApp number and following digits with an empty string
        $text = preg_replace($re, '', $text);

        return $text;
    }


    function getBodyText($id)
    {
        $message = Message::find($id);
        $bodyHtml = $message->message;

        // Create a DOMDocument instance
        $dom = new DOMDocument();
        // Load HTML content
        $bodyHtml = str_replace('&', '&amp;', $bodyHtml);

        @$dom->loadHTML($bodyHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);


        // Find all tables
        $tables = $dom->getElementsByTagName('table');

        // Loop through each table
        foreach ($tables as $table) {
            // Find all th elements
            $thItems = $table->getElementsByTagName('th');
            // Flag to determine if the table should be removed
            $removeTable = false;
            // Loop through each th element
            foreach ($thItems as $th) {
                // Check if th contains "Item" or "Info"
                if (strpos($th->nodeValue, 'Item') !== false || strpos($th->nodeValue, 'Info') !== false) {
                    // If it does, set flag to true and break out of the loop
                    $removeTable = true;
                    break;
                }
            }
            // If the table should be removed, remove it from the DOM
            if ($removeTable) {
                $table->parentNode->removeChild($table);
            }
        }

        // Get the HTML content after table removal
        $bodyHtml = $dom->saveHTML();

        $plainText = Helper::htmlToMarkdown($bodyHtml);   // Remove all HTML tags except <br>
        $plainText = strip_tags($plainText, '<br>');
        $plainText = str_replace('&amp;', '&', $plainText);
        return $plainText;
    }

    public static function GetReminder()
    {
        // Get messages where the reminder timestamp is greater than or equal to the current time
        $messagesToUpdate = Message::where('reminder', '>', 1)
            ->where('reminder', '<=', now()->timestamp)
            ->get();
        foreach ($messagesToUpdate as $message) {
            $message->reminder = 0;
            $message->addLabel('reminder');
        }
    }

    function setReminder(Message $message)
    {
        $day = env('REMINDER_DAY');
        $timestamp = time() + ($day * 24 * 60 * 60);
        $message->reminder = $timestamp;
        $message->save();
    }

    function reply_send(Request $request)
    {

        $cc = $request->get('reply_cc');
        $attachment = [];
        // Check if the request has files
        if ($request->hasFile('attachments')) {
            $files = $request->file('attachments');

            foreach ($files as $file) {
                // Get the original file name
                $originalName = $file->getClientOriginalName();

                // Move the uploaded file to a temporary storage directory
                $tempPath = $file->storeAs('temp', $originalName);

                // Get the full path of the stored file
                $fullPath = storage_path("app/{$tempPath}");

                // Add the file information to the array
                $attachment[] = [
                    'path' => $fullPath,
                    'name' => $originalName,
                ];
            }
        }

        if ($request->has('message_id') && $request->get('message') && !empty($request->get('message'))) {
            $message = Message::find($request->get('message_id'));
            $toRmail = $message->email;
            $previousBody = ' <br><br><blockquote style="margin: 0 0 0 20px; border-left: 2px solid #ccc; padding-left: 10px;">' . $message->message . '</blockquote>';
            $replyMessage = "<p>" . nl2br($request->get('message')) . "</p>";
            $replyMessage .= self::$signature;

            try {
                //code...
                $mailer = $message->sender->getMailer();


                if (!$mailer->checkConnection()) {
                    throw new Exception('Could not connect to Email Server');
                }

                //$mailer->SentBoxCustomLabel = Settings::get('after_reply_box_name', 'eDesk'); //
                //Return path
                $returnToStr = $request->get('return_to');

                $return = explode(":", $returnToStr);
                $AdminName = $return[0];
                $AdminEmail = $return[1];

                if ($request->has('return_to_custom') && !empty($request->get('return_to_custom'))) {
                    $customEmail = $request->input('return_to_custom');
                    // Check if the email is valid (optional, Laravel's email validation rule above already checks for this)
                    if (filter_var($customEmail, FILTER_VALIDATE_EMAIL)) {
                        // Assign the email to AdminEmail
                        // You can add your logic here to assign the email
                        $AdminEmail = $customEmail;
                    } else {
                        throw new Exception('Invalid email address provided');
                    }
                }

                $options = [
                    'fromName' => $AdminName,
                    'fromEmail' => $AdminEmail,
                    'toName' => $message->name,
                    'Return-Path' => $AdminEmail,
                    'CC' => $cc,
                ];

                $replyMessage .= $previousBody;
                //dd($options);

                if (Auth::user()->name == "Pritom") {
                    if (!$this->waitReply($toRmail, "Re: " . $message->subject, $replyMessage, $options, $message->id)) {
                        throw new Exception("reply data could not stored");
                    }
                } else {
                    $mailer->sendEmail($toRmail, "Re: " . $message->subject, $replyMessage, $options, $attachment);
                }
                $message->removeLabel('inbox')->addLabel('sent');
                Session::flash('success', 'Message Succefully Sent.');
                if ($request->has('reminder')) {
                    $this->setReminder($message);
                } //
            } catch (Exception $e) {
                //throw $th;
                throw new Exception("Reply Error, " . $e->getMessage());
            }
        }
    }

    function waitReply($to, $subject, $message, $options, $messageId)
    {
        $waitReply = new Reply(
            [
                'to' => $to,
                'subject' => $subject,
                'replyBody' => $message,
                'options' => json_encode($options),
                'message_id' => $messageId
            ]
        );

        return $waitReply->save();
    }

    function redirect_send(Request $request)
    {
        if ($request->has('message_id')) {
            try {
                $message = Message::find($request->get('message_id'));
                //code...
                $mailer = $message->sender->getMailer();
                if (!$mailer->checkConnection()) {
                    throw new Exception('Could not connect to Email Server');
                }

                //$gmail->SentBoxCustomLabel = Settings::get('after_redirect_box_name', 'eDesk-Redirect'); //

                //Receiver
                $receiverStr = $request->get('redirect_to');
                $receiver = explode(":", $receiverStr);
                $AdminName = $receiver[0];
                $AdminEmail = $receiver[1];

                if ($request->has('redirect_to_custom') && !empty($request->get('redirect_to_custom'))) {
                    $customEmail = $request->input('redirect_to_custom');
                    // Check if the email is valid (optional, Laravel's email validation rule above already checks for this)
                    if (filter_var($customEmail, FILTER_VALIDATE_EMAIL)) {
                        // Assign the email to AdminEmail
                        // You can add your logic here to assign the email
                        $AdminEmail = $customEmail;
                    } else {
                        throw new Exception('Invalid email address provided');
                    }
                }

                $options = [
                    'fromName' => $message->name,
                    'fromEmail' => $message->email,
                    'toName' => $AdminName,
                    'Return-Path' => $message->email,
                ];
                $mailer->sendEmail($AdminEmail, $message->subject, $message->message, $options);

                $message->removeLabel('inbox')->addLabel('redirect');
                Session::flash('success', 'Message Succefully Redirected.');
            } catch (Exception $e) {
                throw new Exception("Redirect Error, " . $e->getMessage());
            }
        } else {
            throw new Exception("Invalid request : Message id must be provided");
        }
    }


    public function getMessageByBox($box = 'inbox')
    {
        // Define an array to store labels to include
        $labelsToInclude = [];

        // Set the labels to include based on the $box value
        if ($box === 'trash') {
            $labelsToInclude = ['trash'];
        } elseif ($box === 'local') {
            $labelsToInclude = ['local'];
        } elseif ($box === 'spam') {
            $labelsToInclude = ['spam'];
        } else {
            // For other boxes, include $box in labels except 'trash' and 'spam'
            $labelsToInclude = [$box];
            $labelsToNotInclude = ['trash', 'spam', 'local'];
        }

        if ($box != 'reminder') {
            $labelsToNotInclude[] = "reminder";
        }

        // Perform the query to filter messages based on labels to include and labels to not include
        $messages = Message::where(function ($query) use ($labelsToInclude, $labelsToNotInclude) {
            foreach ($labelsToInclude as $label) {
                $query->orWhere('labels', 'LIKE', '%' . $label . '%');
            }
            foreach ($labelsToNotInclude as $label) {
                $query->where('labels', 'NOT LIKE', '%' . $label . '%');
            }
            $query->where('reminder', '=', '0');
        })->orderBy('id', 'desc');
        return $messages;
    }

    function getCount($box = 'inbox')
    {
        return $this->getMessageByBox($box)->count();
    }

    public function getCountData()
    {
        $boxes = $this->getCountAll();
        return response()->json(['error' => false, 'data' => $boxes]);
    }

    public function getCountAll()
    {
        $boxes = ['inbox' => 0, 'reminder' => 0, 'outbox' => 0];
        foreach ($boxes as $box => $c) {
            $boxes[$box] = $this->getCount($box);
        }
        return $boxes;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($box = 'inbox')
    {
        // Perform the query to filter messages based on labels to include and labels to not include
        $messages = $this->getMessageByBox($box)->paginate(10);
        $actions = $this->multipleActions($box);
        return view('ghome', compact('messages', 'actions', 'box'));
    }


    function multipleActions($box)
    {
        $actions = [
            // 'spam'      => ['label' => 'Mark as Spam'],
            'trash'     => ['label' => 'Trash'],
            'delete'    => ['label' => 'Delete'],
            // 'local'     => ['label' => 'Mark as Local'],
            'untrash'   => ['label' => 'Untrash'],
            // 'notspam'   => ['label' => 'Not Spam'],
            // 'notlocal'  => ['label' => 'Not Local'],
        ];


        $actionsBox = [
            'inbox'     => ['trash', 'delete'],
            'trash'     => ['untrash', 'delete'],
            'sent'      => ['trash', 'delete'],
            'redirect'  => ['trash', 'delete']
        ];

        // Check if the $box parameter exists in $actionsBox, and return the associated actions
        if (array_key_exists($box, $actionsBox)) {
            $actions4box = $actionsBox[$box];
            $result = [];

            $skipactions = ['delete', 'trash'];
            // Create an array of action details based on the actions associated with the box
            foreach ($actions4box as $action) {
                if (Auth::user()->name == 'Pritom' && in_array($action, $skipactions)) {
                    continue;
                }
                $result[] = (object) [
                    'action' => $action,
                    'label' => $actions[$action]['label']
                ];
            }

            return $result;
        } else {
            // Handle the case where $box is not found
            return [];
        }
    }

    function getMessage(Request $request)
    {
        if ($request->has('id')) {
            $message = Message::find($request->get('id'));
            $message->removeLabel('unread');
            return response()->json($message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the message by its ID
            $message = Message::findOrFail($id);

            // Delete the message
            $message->delete();
            return response()->json(['error' => false, 'message' => 'Message Deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function prompt()
    {
        return view('prompt');
    }
}
