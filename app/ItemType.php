<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ItemType extends Model
{
    protected $table = "item_types";

    protected $with = ["category"];

    public static function getItemTypes($filter = null, $categoryId = null){
        $result = self::orderBy('name');

        if($categoryId){
            $result = $result->where('item_category_id', $categoryId);
        }

        if($filter){
            $result = $result->whereRaw('LOWER(name) like "%'. strtolower($filter) .'%"');
        }

        return $result->get();
    }

    public function category() {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id');
    }
}
