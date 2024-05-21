<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;

class telebotController extends Controller
{
    protected $telegram;

    /**
     * Create a new controller instance.
     *
     * @param  Api  $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->telegram->getMe();

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * A function to send messages
     *
     * This function does the following:
     * - Step 1
     * - Step 2
     * - Step 3
     *
     * @param  Parameter type  Parameter name Description of the parameter (optional)
     * @return Return type Description of the return value (optional)
     */
    public function sendMessage($chat_id, $message) 
    {
        $response = $this->telegram->sendMessage([
            'chat_id' => 'CHAT_ID',
            'text' => 'Hello World'
        ]);
        
        $messageId = $response->getMessageId();
    }

    /**
     * A function to forward messages
     *
     * This function does the following:
     * - Step 1
     * - Step 2
     * - Step 3
     *
     * @param  Parameter type  Parameter name Description of the parameter (optional)
     * @return Return type Description of the return value (optional)
     */
    public function forwardMessage($chat_id, $message)
    {
        $response = $this->telegram->forwardMessage([
            'chat_id' => 'CHAT_ID',
            'from_chat_id' => 'FROM_CHAT_ID',
            'message_id' => 'MESSAGE_ID'
        ]);
        
        $messageId = $response->getMessageId();
    }

    /**
     * A function to send photos
     *
     * This function does the following:
     * - Step 1
     * - Step 2
     * - Step 3
     *
     * @param  Parameter type  Parameter name Description of the parameter (optional)
     * @return Return type Description of the return value (optional)
     */
    public function sendPhoto($chat_id, $photo)
    {
        $response = $this->telegram->sendPhoto([
            'chat_id' => 'CHAT_ID',
            'photo' => 'path/to/photo.jpg',
            // 'photo' => 'http://example.com/photos/image.jpg',
            'caption' => 'Some caption'
        ]);
        
        $messageId = $response->getMessageId();
    }

    /**
     * A function to get updates
     *
     * This function does the following:
     * - Step 1
     * - Step 2
     * - Step 3
     *
     * @param  Parameter type  Parameter name Description of the parameter (optional)
     * @return Return type Description of the return value (optional)
     */
    public function getUpdates()
    {
        $updates = $this->telegram->getUpdates();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
