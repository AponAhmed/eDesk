<?php

namespace App\Http\Controllers;

use App\Models\Canned;
use App\Models\Settings;
use Illuminate\Http\Request;

class AiGenerate extends Controller
{
    //
    public function generate(Request $request)
    {
        $this->freebox($request->all());
    }

    function getInfo(Request $request)
    {

        echo Settings::get($request->get('about'), "");
    }

    public function getInfoRemove(Request $request)
    {
        // Extract delete file name from the request
        $deleteFileName = $request->input('file'); // 'file' is the key from the Axios request

        // Get current field and all fields from settings
        $currentField = Settings::get('ai_about', 'ai_about_company');
        $allFields = Settings::get('ai_about_fields', 'ai_about_company,ai_about_company_faq,ai_about_company_new');

        // Check if the file to delete is the current field
        if ($deleteFileName === $currentField) {
            // If the file to delete is the current field, reset to default
            Settings::set('ai_about', 'ai_about_company');
        }

        // Split allFields by comma into an array
        $allFieldsArray = explode(',', $allFields);

        // Remove the deleteFileName from the array if it exists
        if (($key = array_search($deleteFileName, $allFieldsArray)) !== false) {
            unset($allFieldsArray[$key]);
        }

        // Implode the array back to a comma-separated string
        $updatedFields = implode(',', $allFieldsArray);

        // Update the 'ai_about_fields' with the new list
        Settings::set('ai_about_fields', $updatedFields);

        // Finally, remove the setting for the delete file name
        $deleteResult = Settings::remove($deleteFileName);

        // Return success based on the result of the removal
        if ($deleteResult) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 500);
        }
    }


    public static function getAboutInfo()
    {
        $aboutField = Settings::get('ai_about', 'ai_about_company');
        return Settings::get($aboutField, '');
    }


    function generateReply()
    {
        $hints = CannedController::getHints();
        return view('replyGenerator', array('hints' => $hints));
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
