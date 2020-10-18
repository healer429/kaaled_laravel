<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use phpDocumentor\Reflection\Types\Null_;

class Offering extends Model
{
    protected $table = "offerings";

    protected $appends = ["self"];

    protected $with = ['items', 'images' ,'user'];

    /**
     * Add a new offering fro the user
     * @param Request $request
     * @return Offering
     */
    public static function addOffering(Request $request){

        return DB::transaction(function() use($request){
            $offering = new Offering();
            self::offeringFactory($offering, $request);

            foreach($request->items as $key => $item){
                $item = json_decode(json_encode($item), false);
                Item::addItem($item, $offering, $request);
            }

            $offering->addImages($request);
            $offering->createEarning();

            return $offering;
        });
    }

    public function editOffering(Request $request) {

        foreach($request->items as $key => $item) {
            $item = json_decode(json_encode($item), false);
            $itemObject = Item::findOrFail($item->item_id);
            self::offeringFactory($this, $request);
            $itemObject->updateItem($item);
        }

    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function offeringFactory(Offering $offering, Request $request) {
        $offering->title = $request->title;
        $offering->description = $request->description;
        $offering->lat = $request->lat;
        $offering->lng = $request->lng;
        $offering->user_id = auth()->user()->id;
        $offering->address = $request->address;
//        $offering->city = $request->city;
//        $offering->state = $request->state;
//        $offering->country = $request->country;
        $offering->postal_code = $request->postalCode;
        $offering->save();
    }

    public function addImages(Request $request) {
        try {

            foreach ($request->images as $image) {
                OfferingImage::createImage($this, $image);
            }


            return true;
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function setReward() {
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item->qty;
        }

        $this->reward_count = $count;
        $this->save();

        return true;
    }

    public function items() {
        return $this->hasMany(Item::class, 'offering_id', 'id');
    }

    public function images() {
        return $this->hasMany(OfferingImage::class, 'offering_id', 'id');
    }

    public function createEarning() {
        if(Earning::createEarning($this)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function earning() {
        return $this->hasOne(Earning::class, 'offering_id', 'id');
    }

    public function getSelfAttribute() {
        if($this->user_id == auth()->id()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function checkSoldOut() {
        if($this->is_expired) {
            return true;
        }
        else {
            $soldOut = 1;

            foreach ($this->items as $item) {
                if ($item->remaining_qty > 0) {
                    $soldOut = 0;
                    break;
                }
            }

            if ($soldOut == 1) {
                $this->is_expired = 1;
                $this->save();
                return true;
            }

            return false;
        }
    }
}
