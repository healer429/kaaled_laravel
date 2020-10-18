<?php

namespace App\Http\Controllers;

use App\ItemCategory;
use App\ItemType;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function getAllItemCategories(Request $request){
        return response()->json(array(
            'status' => 1,
            'data' => ItemCategory::getAllCategories()
        ), 200);
    }

    public function getAllItemTypes(Request $request){

        return response()->json(array(
            'status' => 1,
            'data' => ItemType::getItemTypes($request)
        ), 200);
    }
}
