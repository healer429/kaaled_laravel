<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'online'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'date_of_birth', 'address', 'city', 'state', 'country', 'phone', 'postal_code', 'lat', 'lng',
        'email_verified_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Attempt facebook login
     * @param $token
     * @return User|null
     */
    public static function facebookLogin($token){

        $url = "https://graph.facebook.com/me?access_token=" . $token . "&fields=id,name,email";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec ($ch);
        $err = curl_error($ch);  //if you need
        curl_close($ch);


        $fbResult = json_decode($response);
        if(isset($fbResult->id) && isset($fbResult->email) && isset($fbResult->name)){

            $existingUser = self::where('email', $fbResult->email)->first();

            if(isset($existingUser)){
                return $existingUser;
            }else{
                $user = new User();
                $user->email = $fbResult->email;
                $user->name = $fbResult->name;
                $user->password = bcrypt($fbResult->id . Str::random(10));
                $user->save();
                return $user;
            }

        }else{
            return null;
        }
    }

    /**
     * Attempt google login
     * @param $token
     * @return User|null
     */
    public static function googleLogin($token){

        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = array(

        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec ($ch);
        $err = curl_error($ch);  //if you need
        curl_close($ch);


        $googleResult = json_decode($response);

        if(isset($googleResult->sub) && isset($googleResult->email) && isset($googleResult->name)){

            $existingUser = self::where('email', $googleResult->email)->first();

            if(isset($existingUser)){
                return $existingUser;
            }else{

                $user = new User();
                $user->email = $googleResult->email;
                $user->name = $googleResult->name;
                $user->password = bcrypt($googleResult->sub . Str::random(10));
                $user->save();
                return $user;
            }

        }else{
            return null;
        }
    }

    /**
     * Checks if a user needs on boarding
     * @return bool
     */
    public function needsOnboarding() {
        if(!$this->nick_name and !$this->lat and !$this->lng) {
            return true;
        }
        else {
            return false;
        }
    }

    public function onBoard(Request $request) {
        try {
            $this->address = $request->address;
            $this->city = $request->city;
            $this->state = $request->state;
            $this->country = $request->country;
            $this->postal_code = $request->postalCode;
            $this->lat = $request->lat;
            $this->lng = $request->lng;
            $this->date_of_birth = $request->dateOfBirth;
            $this->nick_name = $request->nickName;

            $this->save();
            return true;
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

    }

    public function earnings() {
        return $this->hasMany(Earning::class, 'user_id', 'id');
    }

    public function offerings() {
        return $this->hasMany(Offering::class, 'user_id', 'id');
    }

    public function pickups() {
        return $this->hasMany(PickUp::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }




}
