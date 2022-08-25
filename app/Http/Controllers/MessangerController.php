<?php

namespace App\Http\Controllers;

use App\Services\MessangerService;
use Illuminate\Http\Request;

class MessangerController extends Controller
{   
    private $messanger ;

    public function __construct()
    {
        $this->messanger = new MessangerService();    
    }
    /**
     *  display pages that user has
     *  @return \Illuminate\Http\Response
     */
    public function pages(){
        
        $pages = $this->messanger->getPages();

        return response()->json($pages);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conversations(Request $request)
    {
        
        $page_id                = $request->page_id;
        $page_access_token      = $request->page_access_token;
        $conversations = $this->messanger->getConversations($page_id,$page_access_token);
        
        return response()->json($conversations);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function messages(Request $request){

        $conversation_id   = $request->id;
        $page_access_token = $request->page_access_token;
        $messages          = $this->messanger->getMessages($conversation_id,$page_access_token);
        
        return response()->json($messages);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        $user_id    = $request->id;
        $message    = $request->message;
        // $image      = $request->file('image');
        $page_access_token = $request->page_access_token;
        $response   = $this->messanger->sendMessage($user_id,$message,$page_access_token);
        // $response = $messager->sendMessageWithAttachment($user_id,$message,$image);
        // $response = $this->messager->oneTimeNotification($user_id,$message);
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
