<?php

namespace App\Http\Controllers;

use App\Offering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OfferingController extends Controller
{

    public function createOffering(Request $request) {
        $validated = $this->validateOfferingRequest($request);

        if($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }



        $newOffering = Offering::addOffering($request);

        $newOffering->setReward();

        return response()->json(array(
            'status' => 1,
            'data' => Offering::find($newOffering->id)
        ), 200);

    }

    public function editOffering(Request $request, Offering $offering) {
        $validated = $this->validateOfferingRequest($request);

        if($validated->fails()) {
            $error = $validated->getMessageBag();
            return response()->json(array(
                'status' => 0,
                'error' => $error
            ), 200);
        }

        //Check if the offer belongs to this user.
        if($owner = $offering->user and $owner->id == auth()->id()) {
            try {
                $offering->editOffering($request);

                return response()->json(array(
                    'status' => 1,
                    'data' => $offering
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

    public function validateOfferingRequest(Request $request) {
        return Validator::make($request->all(), array(
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
//            'city' => 'required',
//            'state' => 'required',
//            'country' => 'required',
            'postalCode' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ));
    }

    public function getOffering(Offering $offering) {
        return response()->json(array(
            'status' => 1,
            'data' => $offering
        ));
    }

    public function myOfferings() {
        return response()->json(array(
            'status' => 1,
            'data' => auth()->user()->offerings
        ));
    }

    public function deleteOffering(Offering $offering) {
        if($user = auth()->user() and $user->id == $offering->user_id) {
            try {
                $offering->delete();
                return response()->json(array(
                    'status' => 1,
                    'message' => 'Offering deleted'
                ));
            }
            catch (\Exception $e) {
                return response()->json(array(
                    'status' => 0,
                    'error' => 'Internal Server Error.'
                ));
            }
        }
        else {
            return response()->json(array(
                'status' => 0,
                'error' => 'Forbidden'
            ));
        }
    }
}
