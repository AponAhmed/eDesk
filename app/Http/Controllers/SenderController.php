<?php

namespace App\Http\Controllers;

use App\Interfaces\Mailer;
use App\Interfaces\MailSender;
use App\Models\Sender;
use Illuminate\Http\Request;

class SenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $senders = Sender::paginate(14); // Paginate by 10 items per page, you can adjust as needed
        return view('senders.index', compact('senders'));
    }


    function connectionCheck($id)
    {
        $sender = Sender::find($id);
        //$sender->auth_login_type = true;
        $mailer = $sender->getMailReceiver();

        $mails = $mailer->getEmails(1);
        dd($mails); //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('senders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email_address' => 'required|unique:senders,email_address',
            'auth_login_type' => 'boolean',
            'smtp_options' => 'array',
            'imap_options' => 'array',
            'auth_token' => 'array',
            'other_options' => 'array',
            'daily_limit' => 'integer',
            'daily_send_count' => 'integer',
        ]);

        $sender = Sender::create($request->all());
        return redirect()->route('senders.index')->with('success', 'Sender created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sender  $sender
     * @return \Illuminate\Http\Response
     */
    public function edit(Sender $sender)
    {
        return view('senders.edit', compact('sender'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sender  $sender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sender $sender)
    {
        $request->validate([
            'email_address' => 'unique:senders,email_address,' . $sender->id,
            'auth_login_type' => 'boolean',
            'smtp_options' => 'array',
            'imap_options' => 'array',
            'auth_token' => 'array',
            'other_options' => 'array',
            'daily_limit' => 'integer',
            'daily_send_count' => 'integer',
        ]);

        $sender->update($request->all());
        return redirect()->route('senders.index')->with('success', 'Sender updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sender  $sender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sender $sender)
    {
        $sender->delete();
        return redirect()->route('senders.index')->with('success', 'Sender deleted successfully.');
    }
}
