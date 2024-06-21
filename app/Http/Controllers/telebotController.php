<?php

namespace App\Http\Controllers;

use App\Models\TelegramMessages;

class telebotController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function teleMessages()
    {
        $messages = TelegramMessages::with(['chat', 'user'])->get();
        // dd($messages);
        return view('telegram-components.telemessage', with([
            'messages' => $messages,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTeleMessages($id)
    {
        $messages = TelegramMessages::with(['chat', 'user'])->find($id);
        // dd($message);
        // $messages = TelegramMessages::findOrFail($id)->with(['chat', 'user'])->first();
        $threads = TelegramMessages::where('user_id', $messages->user_id)->get();

        // dd($messages, $threads);
        return view('telegram-components.showtelemessage', with([
            'messages' => $messages,
            'threads' => $threads,
        ]));
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }
}
