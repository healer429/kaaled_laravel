<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\Sendmessage;
use Auth;
use App\Conversation;
use stdClass;

class ChatsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages(Request $request)
    {
        $conv_id = $request['conv_id'];
        $messages = Message::where('conversation_id', $conv_id)->with('user')->get();
        return response()->json($messages);
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $target_user = $request['target_user'];
        $target_users = explode(",", $target_user);

        $conversation = null;

        $newly_convs = array();
        $new_conv = new stdClass();
        $messagss = array();


        foreach ($target_users as $target) {
            $conversation = Conversation::where(function($query) use ($target){
                $query->where('user_id',Auth::id());
                $query->where('target_id',$target);
            })->orwhere(function($query) use ($target){
                $query->where('target_id',Auth::id());
                $query->where('user_id',$target);
            })->get();

            if(!count($conversation)) {
                $conversation = $user->conversations()->create([
                    'target_id' => $target
                ]);
                $c_id = $conversation->id;
                $newly_convs[] = Conversation::where('id',$c_id)->with('target_user')->get()[0];
                $new_conv = Conversation::where('id',$c_id)->with('user')->get()[0];
                $new_conv['target_user'] = $new_conv['user'];
                unset($new_conv['user']);

            } else {
                $conversation = $conversation[0];
            }

            $message = $user->messages()->create([
                'content' => $request->input('message'),
                'conversation_id' => $conversation->id
            ]);
            $messagss[] = $message;
            broadcast(new Sendmessage($user, $message, $target, $new_conv))->toOthers();
        }

        $return = array();
        $return['status'] = 'success';
        $return['messages'] = $messagss;
        $return['newconvs'] = $newly_convs;

        return response()->json($return);
    }
}
