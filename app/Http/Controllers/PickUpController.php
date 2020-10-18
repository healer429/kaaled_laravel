<?php

namespace App\Http\Controllers;

use App\Item;
use App\PickUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class PickUpController extends Controller
{
    public function createPickUp(Request $request) {
        $validated = Validator::make($request->all(), array(
            'items' => 'required'
        ));

        if($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        $itemsArr = [];
        foreach ($request->items as $item) {
            if($item["qty"] == 0) {
                continue;
            }
            else {
                if($itemObj = Item::find($item["itemId"])) {
                    if($itemObj->remaining_qty >= $item["qty"]) {
                        array_push($itemsArr, array(
                            'item' => $itemObj,
                            'qty' => $item["qty"]
                        ));
                    }
                    else {
                        return response()->json(array(
                            'status' => 0,
                            'error' => "You cannot pick: " .
                                $item["qty"] .
                                " for ID: " .
                                $item["itemId"] .
                                " when only " .
                                $itemObj->remaining_qty .
                                " remains"
                        ));
                    }
                }
                else {
                    return response()->json(array(
                        'status' => 0,
                        'error' => "Item ID: " . $item["itemId"] . " does not exist"
                    ));
                }
            }
        }


        try {
            DB::transaction(function () use($itemsArr){
                foreach ($itemsArr as $item) {
                    PickUp::createPickUp(auth()->user(), $item['item'], $item['qty']);
                }
            });

            return response()->json(array(
                'status' => 1,
                'message' => 'Pick up marked'
            ));
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array(
                'status' => 0,
                'error' => "Internal Error."
            ));
        }
    }

    public function getPickUps() {
        return response()->json(array(
            'status' => 1,
            'data' => auth()->user()->pickups
        ));
    }
}
