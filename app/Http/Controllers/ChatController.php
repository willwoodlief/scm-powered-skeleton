<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Helpers\Utilities;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller {
    public function index()
    {
        return view('chat.chat_page');
    }

    public function create_message(Request $request) {
        $user = Utilities::get_logged_user();
        $chat = new ChatMessage();
        $chat->user_id = $user->id;
        $chat->message = $request->request->get('message');
        if (empty($chat->message)) {
            throw new UserException("Chat message needs to have something in it");
        }
        $chat->save();

        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Added message",'chat'=>$chat]);
        } else {
            return redirect()->back();
        }
    }

    const LAST_N_MESSAGES = 5;

    public function get_last_messages() {
        $logged_user = Utilities::get_logged_user();

        $chats = ChatMessage::where('id','>',0)
                ->orderBy('id','desc')
                ->limit(static::LAST_N_MESSAGES)
                ->get();


        if (count($chats) > 0) {
            $message_parts = [];
            foreach ($chats as $chat) {
                if ($chat->user_id === $logged_user->id) {
                    $message_parts[] =  view('chat.chat_message_from_me')->with(compact('chat'))->render();
                } else {
                    $message_parts[] =  view('chat.chat_message')->with(compact('chat'))->render();
                }
            }
            $messsage_html = implode("\n\n",$message_parts);
        } else {
            $messsage_html = view('chat.chat_empty')->render();;
        }



        return response()->json(['success'=>true,'message'=>"Got messages",'chat_html'=>$messsage_html,'token'=>csrf_token()]);
    }


    public function delete_chat(int $chat_id,Request $request) {
        $user = Utilities::get_logged_user();
        $chat =  ChatMessage::where("id",$chat_id)->where('user_id',$user->id)->first();
        if (!$chat) {
            throw new UserException(__("Chat message not found or not owned by you"));
        }
        $chat->delete();
        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Deleted chat",'chat'=>$chat]);
        } else {
            return redirect()->back();
        }
    }
}
