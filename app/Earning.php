<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class Earning extends Model
{
    protected $table = 'earning';

    protected $with = ["offering"];

    public static function createEarning(Offering $offering) {
        $earning = new Earning();
        $earning->user_id = $offering->user_id;
        $earning->offering_id = $offering->id;
        $earning->save();
        return $earning;
    }

    public function updateEarning($amount) {
        if($this->amount + $amount > $this->offering->reward_count) {
            $this->amount = $this->offering->reward_count;
            Log::error("Over flow of reward detected for earning id: " . $this->id);
        }
        else {
            $this->amount += $amount;
        }

//        try {
            $this->save();
//            return true;
//        }
//        catch (Exception $e) {
//            Log::error($e->getMessage());
//            return false;
//        }
    }

    public function offering() {
        return $this->belongsTo(Offering::class, 'offering_id', 'id');
    }
}
