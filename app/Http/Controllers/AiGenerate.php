<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class AiGenerate extends Controller
{
    //
    public function generate(Request $request)
    {
        $this->freebox($request->all());
    }

    function generateReply(){
        return view('replyGenerator');
    }


    function freebox($data, $url = "ai-content-generator", $lang = "English")
    {

        $data['url'] = Settings::get('ai_freebox_model', 'ai-content-generator');
        // URL of the API endpoint
        $url = 'https://api.aifreebox.com/api/openai';
        // Headers
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Origin: https://your-origin.com' // Replace with your actual origin
        );
        $responseArr = array();
        $response = $this->sendPostRequest($url, $data, $headers);
        if (is_array($response) && isset($response['error'])) {
            $responseArr['error'] = true;
            $responseArr['message'] = $response['error'];
        } else {
            $responseArr['error'] = false;
            $responseArr['body'] = $response;
        }

        echo json_encode($responseArr);
    }
    


    function sendPostRequest($url, $data, $headers)
    {
        // Encode the data as JSON
        $postData = json_encode($data);
        // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Set option to receive the response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $err = curl_error($ch);
        // Execute cURL session
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);

        // Check for errors
        if ($response === false) {
            // Return the error message if cURL execution failed
            return 'Error: ' . $err;
        } else {
            // Return the response
            return $response;
        }
    }
}
