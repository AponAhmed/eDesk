<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Utilities\GmailApi;
use Exception;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gmailApi = new GmailApi();


        if ($gmailApi->get2Redirect()) {
            return redirect(url()->current());
        }
        return view('settings', ['gmail' => $gmailApi, 'Settings' => Settings::class]);
    }

    function AuthLogout()
    {
        try {
            //code...
            Settings::remove('gmailApiToken');
            return response()->json(['error' => false, 'message' => 'Successfully logged out']);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    function UpdateSettings(Request $request)
    {
        if ($request->has('settings')) {
            $settings = $request->get('settings');
            foreach ($settings as $key => $value) {
                Settings::set($key, $value);
            }
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(Settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settings $settings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Settings $settings)
    {
        //
    }
}
