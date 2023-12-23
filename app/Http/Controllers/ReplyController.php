<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Settings;
use App\Utilities\GmailApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReplyController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($box = 'inbox')
    {


        // Perform the query to filter messages based on labels to include and labels to not include
        $messages = Reply::orderBy('id', 'desc')->paginate(10);

        $actions = [];
        return view('replies', compact('messages', 'actions'));
    }

    function getMessage(Request $request)
    {
        if ($request->has('id')) {
            $message = Reply::find($request->get('id'));
            $emails = [];
            $emails[Settings::get('admin_email')] = Settings::get('admin_name');
            $emails['admin@siatexltd.com'] = "Admin";
            $preview = Auth::user()->name == 'Pritom' ? true : false;
            return view('release', array('message' => $message, 'preview' => $preview));
        }
    }

    function release(Request $request)
    {
        if ($request->has('id')) {
            $message = Reply::find($request->get('id'));
            $mesasageBody = $request->get('modifiedMsg');
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

                if ($gmail->send($message->to, $message->subject, $mesasageBody, $message->getOptions(), [])) {
                    $message->delete();
                    Session::flash('success', 'Message Released successfully');
                    return response()->json(['success' => true, 'message' => "Message Released successfully"]);
                }
                //$message->removeLabel('inbox')->addLabel('sent');
            } catch (Exception $e) {
                //throw $th;
                throw new Exception("Release Error, " . $e->getMessage());
            }
        }
        return response()->json(['success' => false, 'message' => "Message not Released successfully"]);
    }
}
