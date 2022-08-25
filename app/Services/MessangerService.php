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

    }

    /**
     * get all pages owned by user
     * @return array
     */
    public function getPages(){

        try{
            
            $response = Http::get('https://graph.facebook.com/me/accounts?access_token='.$this->token);

            return $response->object();

            

        }catch(Exception $e){

            throw new Exception('Error while fetch pages');

        }

    }
    /**
     *  get all conversisions from messanger api
     *  @return array
     *  @throws Exception
     * 
     */
    public function getConversations(int $page,string $page_access_token){

      
        try{
            
            $response = Http::get('https://graph.facebook.com/'.$page.'/conversations?access_token='.$page_access_token.'&fields=participants,messages.limit(1){message,created_time}');

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
    public function getMessages($conversation_id,$page_access_token){
        
        try{
            
            $response = Http::get('https://graph.facebook.com/'.$conversation_id.'/messages?access_token='.$page_access_token.'&fields=message,attachments,created_time');
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
    public function sendMessage($user, $message,$page_access_token)
    {
        $response = Http::post('https://graph.facebook.com/me/messages?access_token='.$page_access_token, [
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