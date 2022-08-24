<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class MessangerService
{
    private $page_id, $token;
    
    public function __construct()
    {   
        $this->token = config('services.messanger.token');

        try{

            // send get request to messanger api to get page id
            $response = Http::get('https://graph.facebook.com/me?access_token='.$this->token);
            $this->page_id = $response->object()->id;


        }catch(Exception $e){

           $this->page_id = null;

        }
       
    }

    /**
     *  get all conversisions from messanger api
     *  @return array
     *  @throws Exception
     * 
     */
    public function getConversations(){

        if($this->page_id == null){

            throw new Exception('Page id not found');
        }

        try{
            
            $response = Http::get('https://graph.facebook.com/'.$this->page_id.'/conversations?access_token='.$this->token.'&fields=participants,messages.limit(1){message,created_time}');

            return $response->object();

            

        }catch(Exception $e){

            throw new Exception('Error while fetch conversions Conversations');

        }
      
       
    }
    /**
     *  get messages for a conversation
     *  @param string $conversation_id
     *  @return array
     */
    public function getMessages($conversation_id){
        
        try{
            
            $response = Http::get('https://graph.facebook.com/'.$conversation_id.'/messages?access_token='.$this->token.'&fields=message,attachments,created_time');
            return $response->object();
        }catch(Exception $e){
            throw new Exception('Error while fetch messages for conversation');
        }

    }
    /**
     * Send a message to a user.
     *
     * @param  string  $user
     * @param  string  $message
     * @return void
     */
    public function sendMessage($user, $message)
    {
        $response = Http::post('https://graph.facebook.com/me/messages?access_token='.$this->token, [
            'recipient' => [
                'id' => $user,
            ],
            'message' => [
                'text' => $message,
            ],
            [
                'messaging_type' => 'RESPONSE'
            ]
        ]);

        // get status code
        return $response->status()  == Response::HTTP_OK ? true : false;
    }
    // send message with attachment
    public function sendMessageWithAttachment($user, $message, $attachment){
    
        try{

            $client = new Client();
            $headers = [
                'Accept' => 'application/json',
            ];
            $options = [
                'multipart' => [
                    [
                    'name' => 'recipient[id]',
                    'contents' => $user
                    ],
                    [
                    'name' => 'message[attachment][type]',
                    'contents' => 'image'
                    ],
                    [
                    'name' => 'message[attachment][payload][is_reusable]',
                    'contents' => 'false'
                    ],
                    [
                    'name' => 'attachment',
                    'contents' => fopen($attachment, 'r'),
                    'filename' => '/home/moussa/Downloads/26248615.jpeg',
                    ]
                ]];
            $request = new Request('POST', 'https://graph.facebook.com/me/messages?access_token='.$this->token, $headers);
            $client->sendAsync($request, $options)->wait();
            
            return true;

        }catch(Exception $e){

            return false;
        }
       
    }

    public function oneTimeNotification($user,$message){
       
        $response = Http::post('https://graph.facebook.com/me/messages?access_token='.$this->token, [
            'recipient' => [
                'id' => $user,
            ],
            'message' => [
                "attachment" =>  [
                    "type" => "template",
                    "payload" =>  [
                      "template_type" => "one_time_notif_req",
                      "title" => $message,
                      "payload" => $message
                    ]
                ]
            ],
            [
                'messaging_type' => 'RESPONSE'
            ]
        ]);

        dd($response);
        // get status code
        return $response->status()  == Response::HTTP_OK ? true : false;

    }
}