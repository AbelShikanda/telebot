<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class telebotController extends Controller
{
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++
    private $baseUrl;
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl = env('API_URL') . env('BOT_TOKEN');
    }

    /**
     * get data sent to the php://input
     * Place it in a log file and decode it
     * Chech message to determine the response
     * @param  \Illuminate\Http\Request  $request
     * run telebotApi to respond
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $message = json_encode($request->all());
        // $messageId = $request->input('message.from.id');
        // $name = $request->input('message.from.first_name');

        // Storage::disk('local')->put('log.txt', $message);

        $data = file_get_contents('php://input');
        $logFIle = "webhooksentdata.json";
        $log = fopen($logFIle, 'a');
        fwrite($log, $data);
        fclose($log);

        // Check if $getData is not null and if it contains the expected keys
        if (!empty($getData) && isset($getData['message']['from']['id']) && isset($getData['message']['text'])) {

            // // Retrieve the data
            $getData = json_decode($data, true);
            $userid = $getData['message']['from']['id'];
            $userMessage = $getData['message']['text'];

            // check to determine how to respond
            if ($userMessage == "Hi" || $userMessage == "hi" || $userMessage == "Hello" || $userMessage == "hello") {
                // respond to the message
                $botReply = "Hi there! How can i help you?";
            } elseif ($userMessage == "How are you" || $userMessage == "how are you") {
                // respond to the message
                $botReply = "I'm fine, thank you. How are you?";
            } else {
                // respond to the message
                $botReply = "Sorry, I don't understand.";
            }

            // get the parameters required
            $param = array(
                "chat_id" => $userid,
                "text" => $botReply,
                "parse_mode" => "html"
            );

            // send the response
            $this->TelebotApi("sendMessage", $userid, $param);
            // return the response
            // return $botReply;
            return view('welcome');
        } else {
            // Handle the case where the expected keys are not present
            $botReply = "Error: Invalid message format";
        }
        return view('welcome');
    }

    /**
     * setting a webhook
     *
     * This function does the following:
     * - Get the url where to set the webhook
     * - Run the telegram function to set the webhook
     *
     * @param  
     * @return Return value of the result (optional)
     */
    public function setWebhook()
    {
        $webHookUrl = "https://www.print.printshopeld.com/phptelebot/index.php";
        $apiUrl = $this->baseUrl . '/' . 'setWebhook' . '?url=' . $webHookUrl;
        $res = file_get_contents($apiUrl);

        return view('setWebhook', $res);
    }

    /**
     * private funvtion to send responce
     *
     * This function does the following:
     * - retrieves the url path
     * - uses the php curl
     * - Sends a response
     *
     * @param  int $id  users id
     * @param  array $param  array of parameters
     * @param  string of the function name
     * @return Return type Description of the return value (optional)
     */
    private function TelebotApi($method, $id, $param)
    {
        // send the response
        $url = $this->baseUrl . '/' . $method . '?chat_id=' . $id . '&' . http_build_query($param);
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
