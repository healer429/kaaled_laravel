<?php

namespace App\Http\Controllers;

use App\Helper\ListSearchHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListController extends Controller
{
    public function nearbyOffers(Request $request) {
        $validated = Validator::make($request->all(), array(
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'required|numeric'
        ));

        if($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        $searchHelper = new ListSearchHelper();

        if($request->has('doPaginate') and $request->doPaginate) {
            return response()->json(array(
                'status' => 1,
                'data' => $searchHelper->searchNearByOffer($request->lat, $request->lng, $request->radius)->paginate(8)
            ));
        }
        else {
            return response()->json(array(
                'status' => 1,
                'data' => $searchHelper->searchNearByOffer($request->lat, $request->lng, $request->radius)->get()
            ));
        }
    }
}
