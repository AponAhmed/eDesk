<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Settings;
use App\Utilities\GmailApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    static $signature = '<p>--<br>
    Kind regards,<br><br>

    Pranub Dutta<br>
    SiATEX (BD) Limited<br>
    House - 8, Road - 6<br>
    Unit A5 and B5, 5th Floor<br>
    Niketon, Gulshan 1<br>
    Dhaka 1212, Bangladesh<br>
    Ph: (02) 222-855-548 Ext 201<br>
    sales@siatexltd.com</p>';


    public function __construct()
    {
        $this->middleware('auth');
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
        $emails[Settings::get('admin_email')] = Settings::get('admin_name');
        return view('redirect', array('id' => $id, 'emails' => $emails));
    }

    function reply($id)
    {
        $emails = [];
        $emails[Settings::get('admin_email')] = Settings::get('admin_name');
        $emails['admin@siatexltd.com'] = "Admin";

        return view('reply', array('id' => $id, 'emails' => $emails));
    }

    function reply_send(Request $request)
    {
        if ($request->has('message_id') && $request->get('message') && !empty($request->get('message'))) {
            $message = Message::find($request->get('message_id'));
            $toRmail = $message->email;
            $previousBody = ' <br><br><blockquote style="margin: 0 0 0 20px; border-left: 2px solid #ccc; padding-left: 10px;">' . $message->message . '</blockquote>';
            $replyMessage = "<p>" . nl2br($request->get('message')) . "</p>";
            $replyMessage .= self::$signature;

            try {
                //code...
                $gmail = new GmailApi();
                if (!$gmail->configured) {
                    throw new Exception('You must configure with Google cloude API');
                }
                if (!$gmail->connect) {
                    throw new Exception('Could not connect to Google');
                }
                $gmail->SentBoxCustomLabel = Settings::get('after_reply_box_name', 'eDesk'); //
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
                ];

                $replyMessage .= $previousBody;
                //dd($replyMessage);

                $gmail->send($toRmail, "Re: " . $message->subject, $replyMessage, $options);

                $message->removeLabel('inbox')->addLabel('sent');
                Session::flash('success', 'Message Succefully Sent.');
            } catch (Exception $e) {
                //throw $th;
                throw new Exception("Reply Error, " . $e->getMessage());
            }
        }
    }

    function redirect_send(Request $request)
    {
        if ($request->has('message_id')) {
            try {
                $gmail = new GmailApi();
                if (!$gmail->configured) {
                    throw new Exception('You must configure with Google cloude API');
                }
                if (!$gmail->connect) {
                    throw new Exception('Could not connect to Google');
                }
                $gmail->SentBoxCustomLabel = Settings::get('after_redirect_box_name', 'eDesk-Redirect'); //
                $message = Message::find($request->get('message_id'));
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
                $gmail->send($AdminEmail, $message->subject, $message->message, $options);

                $message->removeLabel('inbox')->addLabel('redirect');
                Session::flash('success', 'Message Succefully Redirected.');
            } catch (Exception $e) {
                throw new Exception("Redirect Error, " . $e->getMessage());
            }
        } else {
            throw new Exception("Invalid request : Message id must be provided");
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($box = 'inbox')
    {
        // Define an array to store labels to include
        $labelsToInclude = [];
        $labelsToNotInclude = [];
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

        // Perform the query to filter messages based on labels to include and labels to not include
        $messages = Message::where(function ($query) use ($labelsToInclude, $labelsToNotInclude) {
            foreach ($labelsToInclude as $label) {
                $query->orWhere('labels', 'LIKE', '%' . $label . '%');
            }

            foreach ($labelsToNotInclude as $label) {
                $query->where('labels', 'NOT LIKE', '%' . $label . '%');
            }
        })->orderBy('id', 'desc')->paginate(10);

        $actions = $this->multipleActions($box);
        return view('home', compact('messages', 'actions'));
    }


    function multipleActions($box)
    {
        $actions = [
            'spam'      => ['label' => 'Mark as Spam'],
            'trash'     => ['label' => 'Trash'],
            'delete'    => ['label' => 'Delete'],
            'local'     => ['label' => 'Mark as Local'],
            'untrash'   => ['label' => 'Untrash'],
            'notspam'   => ['label' => 'Not Spam'],
            'notlocal'  => ['label' => 'Not Local'],
        ];

        $actionsBox = [
            'inbox'     => ['local', 'spam', 'trash', 'delete'],
            'trash'     => ['untrash', 'delete'],
            'spam'      => ['notspam', 'delete'],
            'sent'      => ['trash', 'delete'],
            'local'     => ['notlocal', 'trash', 'delete'],
            'redirect'  => ['trash', 'delete']
        ];

        // Check if the $box parameter exists in $actionsBox, and return the associated actions
        if (array_key_exists($box, $actionsBox)) {
            $actions4box = $actionsBox[$box];
            $result = [];

            // Create an array of action details based on the actions associated with the box
            foreach ($actions4box as $action) {
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
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
}
