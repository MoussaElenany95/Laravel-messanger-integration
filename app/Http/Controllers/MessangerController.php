<?php

namespace App\Http\Controllers;

use App\Services\MessangerService;
use Illuminate\Http\Request;

class MessangerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conversations(Request $request)
    {
        $messager = new MessangerService();

        $conversations = $messager->getConversations();
        
        return response()->json($conversations);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function messages($id){

        $messager = new MessangerService();

        $messages = $messager->getMessages($id);
        
        return response()->json($messages);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id    = $request->id;
        $message    = $request->message;
        $image      = $request->file('image');
        $messager   = new MessangerService();
        // $response = $messager->sendMessage($user_id,$message);
        // $response = $messager->sendMessageWithAttachment($user_id,$message,$image);
        $response = $messager->oneTimeNotification($user_id,$message);
        return $response ? response()->json(['message'=>'Message sent']): response()->json(['message'=>'Error while sending message']);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
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
