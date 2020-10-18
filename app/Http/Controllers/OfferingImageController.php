<?php

namespace App\Http\Controllers;

use App\Offering;
use App\OfferingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferingImageController extends Controller
{

    public function addImage(Request $request, Offering $offering) {
        if($owner = $offering->user and $owner->id == auth()->id()) {
            if($offering->addImages($request)) {
                return response()->json(array(
                    'status' => 1,
                    'data' => $offering
                ));
            }
            else {
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

    public function removeImage(Request $request, OfferingImage $image) {
        //Check if the image belongs to this user before deleting it.
        if($owner = $image->user and auth()->id() == $owner->id) {
            try {
                $image->delete();
                return response()->json(array(
                   'status' => 1
                ));
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(array(
                    'status' => 0,
                    'error' => 'Internal error'
                ));
            }
        }
        else {
            return response()->json(array(
                'status' => 0,
                'error' => 'forbidden'
            ));
        }
    }
}
