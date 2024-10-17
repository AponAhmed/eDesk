<?php

namespace App\Http\Controllers;

use App\Models\Domain;
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
        $domains = Domain::orderBy('id', 'desc')->get();
        return view('settings', ['gmail' => $gmailApi, 'Settings' => Settings::class, 'domains' => $domains]);
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
            //dd($request->all());
            $settings = $request->get('settings');
            foreach ($settings as $key => $value) {
                Settings::set($key, $value);
            }
        }
    }


    

}
