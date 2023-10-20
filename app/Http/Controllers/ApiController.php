<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    //

    function index()
    {
        return response()->json(['test' => true]);
    }

    public function store(Request $request)
    {

        $apiKey = $request->header('X-API-Key'); // Replace 'Authorization' with the actual header name
        $domain = '';
        $domainData = Domain::where('key', '=', $apiKey)->limit(1)->get('id')->first();
        if ($domainData) {
            $domain = $domainData->id;
        } else {
            return new JsonResponse(['error' => true, 'errors' => ['api_key' => 'invalid Api Key']], 422);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:99',
            'email' => 'required|email|max:256',
            'whatsapp' => 'string|max:30',
            'subject' => 'required|string|max:256',
            'message' => 'required|string',
        ]);
        //'labels' => 'string|max:255',
        if ($validator->fails()) {
            return new JsonResponse(['error' => true, 'errors' => $validator->errors()], 422);
        }

        // Check if the Origin header is set
        if ($request->headers->has('Origin')) {
            $clientDomain = $request->headers->get('Origin');
            //Check if the Origin header is set

        } else {
            //Same origin
        }

        $data = $request->all();
        $data['domain_id'] = $domain;
        $data['labels'] = "inbox,unread";
        //dd($data);

        try {
            // Create a new message based on the validated data
            $message = Message::create($data);
            // Return a success response with a status code of 201 (Created)
            return response()->json(['error' => false, 'message' => 'Message successfully Sent'], 201);
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during data creation
            // You can log the error, return a different error message, or take appropriate action.
            return response()->json(['error' => true, 'error' => 'An error occurred while Sending Message' . $e->getMessage()], 500);
        }
    }

    function check4New($id = false)
    {
        // If $id is provided, find the count of new messages with a greater 'id' value
        if ($id) {
            $newMessageCount = Message::where('id', '>', $id)->count();
        } else {
            // If $id is not provided, return the count of all messages as new
            $newMessageCount = Message::count();
        }

        // Prepare the JSON response
        $response = [
            'has_new' => ($newMessageCount > 0), // Check if there are new messages
            'count' => $newMessageCount,         // Number of new messages
        ];

        // Return the JSON response
        return response()->json($response);
    }
}
