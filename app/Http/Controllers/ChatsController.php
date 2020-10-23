<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\Sendmessage;
use App\Events\Readmessage;
use Auth;
use App\Conversation;
use stdClass;
use App\User;
use Illuminate\Support\Facades\URL;

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

    public function connect()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->online = true;
            $user->save();
        }
    }

    public function disConnect(Request $request)
    {
        $user = User::find($request->id);
        $user->online = false;
        $user->save();

        $return['result'] = true;

        return response()->json($return);
    }

    /**
     * Persist message to database
     *
     * @param Request $request
     * @return Response
     */

    public function sendfile(Request $request)
    {
        $uploadedFile = $request->file('message');
        $target = $request['target_user'];

        $filename = time().$uploadedFile->getClientOriginalName();

        $destinationPath = 'uploads/';

        $uploadedFile->move($destinationPath, $filename);

        $file_path = URL::to('/') . '/uploads/' . $filename;

        $conversation = null;
        $new_conv = new stdClass();
        $new_conv1 = new stdClass();

        $conversation = Conversation::where(function ($query) use ($target) {
            $query->where('user_id', Auth::id());
            $query->where('target_id', $target);
        })->orwhere(function ($query) use ($target) {
            $query->where('target_id', Auth::id());
            $query->where('user_id', $target);
        })->get();

        if (!count($conversation)) {
            $conversation = $user->conversations()->create([
                'target_id' => $target
            ]);
            $c_id = $conversation->id;
            $new_conv1 = Conversation::where('id', $c_id)->with('target_user')->get()[0];
            $new_conv = Conversation::where('id', $c_id)->with('user')->get()[0];
            $new_conv['target_user'] = $new_conv['user'];
            unset($new_conv['user']);

        } else {
            $conversation = $conversation[0];
        }
        $user = Auth::user();
        $message = $user->messages()->create([
            'attachment' => 'file',
            'content' => $file_path,
            'conversation_id' => $conversation->id
        ]);

        broadcast(new Sendmessage($user, $message, $target, $new_conv));
        broadcast(new Sendmessage($user, $message, Auth::id(), $new_conv1));

        $return = array();
        $return['success'] = $filename;
        return response()->json($return);
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $target_user = $request['target_user'];
        $target_users = explode(",", $target_user);

        $conversation = null;

        $newly_convs = array();
        $new_conv = new stdClass();
        $new_conv1 = new stdClass();
        $messagss = array();

        foreach ($target_users as $target) {
            $conversation = Conversation::where(function ($query) use ($target) {
                $query->where('user_id', Auth::id());
                $query->where('target_id', $target);
            })->orwhere(function ($query) use ($target) {
                $query->where('target_id', Auth::id());
                $query->where('user_id', $target);
            })->get();

            if (!count($conversation)) {
                $conversation = $user->conversations()->create([
                    'target_id' => $target
                ]);
                $c_id = $conversation->id;
                $new_conv1 = Conversation::where('id', $c_id)->with('target_user')->get()[0];
                $newly_convs[] = $new_conv1;
                $new_conv = Conversation::where('id', $c_id)->with('user')->get()[0];
                $new_conv['target_user'] = $new_conv['user'];
                unset($new_conv['user']);

            } else {
                $conversation = $conversation[0];
            }

            if ($request['attachment'] == "image") {
                $base64_image = $request->input('message');
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
                $data = base64_decode($data);
                $folderPath = "images/";
                $file = $folderPath . uniqid() . '.png';

                file_put_contents($file, $data);

                $file_path = URL::to('/') . '/' . $file;

                $message = $user->messages()->create([
                    'attachment' => $request->input('attachment'),
                    'content' => $file_path,
                    'conversation_id' => $conversation->id
                ]);
                $messagss[] = $message;
            } else {
                $message = $user->messages()->create([
                    'attachment' => $request->input('attachment'),
                    'content' => $request->input('message'),
                    'conversation_id' => $conversation->id
                ]);
                $messagss[] = $message;
            }
            broadcast(new Sendmessage($user, $message, $target, $new_conv));
            broadcast(new Sendmessage($user, $message, Auth::id(), $new_conv1));
        }

        $return = array();
        $return['status'] = 'success';
        $return['messages'] = $messagss;
        $return['newconvs'] = $newly_convs;

        return response()->json($return);
    }

    public function unreadMessage(Request $request)
    {
        # code...
        $conv_id = $request['conv_id'];
        Message::where('conversation_id', $conv_id)->where('user_id', '<>', Auth::id())->update(['unread' => 0]);
        broadcast(new Readmessage())->toOthers();
    }
}
