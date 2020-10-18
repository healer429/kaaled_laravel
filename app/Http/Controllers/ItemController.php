<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function editItem(Request $request, Item $item) {
        $validated = Validator::make($request->all(), array(
            'itemTypeId' => 'required|integer',
            'qty' => 'required'
        ));

        if($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        //Verify this Item belongs to this user.
        if($owner = $item->user and $owner->id == auth()->id()) {
            try {
                $item->item_type_id = $request->itemTypeId;
                $item->qty = $request->qty;
                isset($request->unit) ? $item->unit = $request->unit : true;

                $item->save();

                return response()->json(array(
                    'status' => 1,
                    'data' => $item
                ));
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(array(
                    'status' => 0,
                    'error' => "Internal Error"
                ));
            }
        }
        else {
            return response()->json(array(
                'status' => 0,
                'error' => "Forbidden"
            ));
        }
    }
}
