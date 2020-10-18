<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\User;
//use App\UserRole;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), array(
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ));

        if ($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        //Check if email already used.
        if ($user = User::where('email', $request->email)->first()) {
            return response()->json(array(
                'status' => 0,
                'error' => 'Email already in use'
            ), 200);
        }

        //Create a new User.
        try {
            $user = new User();

            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = bcrypt($request->password);
            $user->save();

            Wallet::initWallet($user);

            //Create JWT for the new user.
            $token = $user->createToken('kaaled')->accessToken;

            return response()->json(array(
                'status' => 1,
                'message' => 'Registered Successfully',
                'onboard' => $user->needsOnboarding(),
                'token' => $token
            ), 200);
        } catch (\Exception $e) {
            echo(json_encode($e->getMessage()));
            return response()->json(array(
                'status' => 0,
                'error' => 'Internal Server Error'
            ), 200);
        }
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), array(
            'email' => 'required',
            'password' => 'required'
        ));


        if ($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        $credentials = array(
            'email' => $request->email,
            'password' => $request->password
        );


        if ($user = User::where('email', $request->email)->first()) {
            if (Auth::attempt($credentials)) {
                $token = auth()->user()->createToken('kaaled')->accessToken;
                $user->online = true;
                $user->update();
                return response()->json(array(
                    'status' => 1,
                    'message' => 'Welcome back ' . auth()->user()->name,
                    'token' => $token,
                    'onboard' => auth()->user()->needsOnboarding(),
                    'data' => auth()->user()
                ), 200);
            } else {
                return response()->json(array(
                    'status' => 0,
                    'error' => 'Incorrect Credentials! Please try again.'
                ), 200);
            }
        } else {
            //Create a new User.
            try {
                $user = new User();

                $user->email = $request->email;
                $user->name = $request->has('name') ? $request->name : "TEMP USER1";
                $user->password = bcrypt($request->password);
                $user->online = true;
                $user->save();

                Wallet::initWallet($user);

                //Create JWT for the new user.
                $token = $user->createToken('kaaled')->accessToken;

                return response()->json(array(
                    'status' => 1,
                    'message' => 'Registered Successfully',
                    'onboard' => $user->needsOnboarding(),
                    'token' => $token
                ), 200);
            } catch (\Exception $e) {
                echo(json_encode($e->getMessage()));
                return response()->json(array(
                    'status' => 0,
                    'error' => 'Internal Server Error'
                ), 200);
            }
        }


    }

    public function getLoginError()
    {
        return response()->json(array(
            'status' => 0,
            'Error' => "Invalid Token"
        ), 200);
    }

    public function validateToken(Request $request)
    {
        return response()->json(array(
            'status' => 1,
            "message" => "Success"
        ), 200);
    }

    /**
     * Login via Social Network
     * Attempt to login via social network - supports facebook and google
     * @queryParam token required Access Token from Facebook/Google
     * @queryParam type required Token Source (0 -> facebook, 1 -> google)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialLogin(Request $request)
    {
        $validated = Validator::make($request->all(), array(
            'token' => 'required',
            'type' => 'required'    // 0-> facebook, 1-> google
        ));
        $user = null;
        if ($request->type == '0') {
            $user = User::facebookLogin($request->token);
        } else if ($request->type == '1') {
            $user = User::googleLogin($request->token);
        } else {
            return response()->json(array(
                'status' => 0,
                'message' => "Invalid Request"
            ));
        }
        if (isset($user)) {

            Auth::login($user);
            return response()->json(array(
                'status' => 1,
                'token' => auth()->user()->createToken('kaaled')->accessToken,
                'data' => $user,
                'onboard' => $user->needsOnboarding()
            ));


        } else {
            return response()->json(array(
                'status' => 0,
                'message' => "Error while fetching details"
            ));
        }
    }

    public function onBoard(Request $request)
    {
        $validated = Validator::make($request->all(), array(
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postalCode' => 'required',
            'dateOfBirth' => 'required|date|date_format:Y-m-d',
            'nickName' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ));

        if ($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        $user = auth()->user();
        if ($user->onBoard($request)) {
            return response()->json(array(
                'status' => 1,
                'message' => "Successfully updated on boarding information",
                'data' => $user
            ), 200);
        } else {
            return response()->json(array(
                'status' => 0,
                'error' => "Internal Error."
            ), 200);
        }
    }

    public function getusers()
    {
        $users = User::all()->except(Auth::id());
        return response()->json($users);
    }

    public function getconversations()
    {
        $conversations1 = Conversation::where('target_id', Auth::id())->with('user')->get()->toarray();
        foreach ($conversations1 as $key => $value) {
            $conversations1[$key]['target_user'] = $conversations1[$key]['user'];
            unset($conversations1[$key]['user']);
        }
        $conversations2 = Conversation::where('user_id', Auth::id())->with('target_user')->get()->toarray();
        $result = array_merge($conversations1, $conversations2);
        return response()->json($result);
    }

    public function getuserdata(Request $request)
    {
        $user_id = $request['user_id'];

        $userdata = User::find($user_id);

        return response()->json($userdata);
    }
}

