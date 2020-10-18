<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PickUp extends Model
{
    protected $table = 'pickup';

    protected $with = ["item"];

    public static function createPickUp(User $user, Item $item, $amount) {
        if($item->remaining_qty >= $amount) {
            try {
                return DB::transaction(function() use($user, $item, $amount){
                    $pickup = new PickUp();
                    $pickup->user_id = $user->id;
                    $pickup->item_id = $item->id;
                    $pickup->amount = $amount;

                    $pickup->save();

                    //Add earning from this pick up.
                    $item->offering->earning->updateEarning($amount);

                    //Check if the offering is sold out.
                    $item->offering->checkSoldOut();

                    return array(
                        'status' => 1,
                        'data' => $pickup,
                        'message' => "Pickup has been marked"
                    );
                });

            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
                return array(
                    'status' => 0,
                    'error' => 'Internal Error'
                );
            }
        }
        else {
            return array(
                'status' => 0,
                'error' => "You cannot pickup more than the remaining qty of: " . $item->remaining_qty
            );
        }
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
