<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getBalance(){
        return response()->json(array(
            'status' => 1,
            'data' => Wallet::getBalance()
        ), 200);
    }

    public function addMoney(){
        Wallet::deductMoney(auth()->user(), 100);
        return response()->json(array(
            'status' => 1,
            'message' => "success"
        ), 200);
    }
}
