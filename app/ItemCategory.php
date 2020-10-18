<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $table = "item_categories";

    public static function getAllCategories(){
        return self::orderBy('name')->get()->toArray();
    }
}
