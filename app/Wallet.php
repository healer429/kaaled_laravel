<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = "wallets";

    public static function getBalance(){
        return self::where('user_id', auth()->user()->id)->first();
    }

    public static function getBalanceByUser(User $user){
        return self::where('user_id', $user->id)->first();
    }

    /**
     * Init wallet for user
     * @param User $user
     */
    public static function initWallet(User $user){
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->amount = 0;

        $wallet->save();
    }

    /**
     * Add Money to wallet
     * @param User $user
     * @param $amount
     * @return mixed
     */
    public static function addMoney(User $user, $amount){
        $wallet = self::getBalanceByUser($user);

        $wallet->amount += $amount;
        $wallet->save();
        return $wallet;
    }

    /**
     * Deduct Money from wallet
     * @param User $user
     * @param $amount
     * @return mixed
     */
    public static function deductMoney(User $user, $amount){
        $wallet = self::getBalanceByUser($user);

        $wallet->amount -= $amount;
        $wallet->save();
        return $wallet;
    }

}
