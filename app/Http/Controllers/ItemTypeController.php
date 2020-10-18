<?php

namespace App\Http\Controllers;

use App\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    public function typeCompletion(Request $request) {
        $filter = isset($request->filter) ? $request->filter : null;
        $categoryId = isset($request->categoryId) ? $request->categoryId : null;

        return response()->json(array(
            'status' => 1,
            'data' => ItemType::getItemTypes($filter, $categoryId)
        ));
    }
}
