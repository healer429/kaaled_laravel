<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Item extends Model
{
    protected $table = "items";

    protected $appends = ["remaining_qty"];

    protected $hidden = ["pickups"];

    protected $with = ["itemType"];

    /**
     * Add new item for an offering
     * @param $item
     * @param Offering $offering
     * @param Request $request
     * @return Item
     */
    public static function addItem($item, Offering $offering, Request $request){
        $newItem = new Item();

        $newItem->item_type_id = $item->itemTypeId;
        $newItem->offering_id = $offering->id;

        if(isset($item->qty)) {
            $newItem->qty = $item->qty;

            if(isset($item->unit)) {
                $newItem->unit = $item->unit;
            }
        }

        $newItem->save();
        return $newItem;
    }

    public function user() {
        if($offer = $this->belongsTo(Offering::class, 'offering_id', 'id')->first()) {
            return $offer->user();
        }
        else {
            return null;
        }
    }

    public function pickups() {
        return $this->hasMany(PickUp::class, 'item_id', 'id');
    }

    public function getRemainingQtyAttribute() {
        $remaining = $this->qty;

        foreach ($this->pickups as $pickup) {
            $remaining -= $pickup->amount;
            if($remaining < 0) {
                return 0;
            }
        }

        return $remaining;
    }

    public function updateItem($item){

        if( $this->id =$item->item_id and $this->offering_id = $item->offering_id){
            $this->id = $item->item_id;
            $this->offering_id = $item->offering_id;
            $this->item_type_id=$item->item_type_id;
            if(isset($item->qty)) {
                $this->qty = $item->qty;

                if(isset($item->unit)) {
                    $this->unit = $item->unit;
                }
            }

            $this->save();
        }else{
            return 0;
        }

    }

    public function offering() {
        return $this->belongsTo(Offering::class, 'offering_id', 'id');
    }


    public function itemType() {
        return $this->belongsTo(ItemType::class, 'item_type_id', 'id');
    }
}
