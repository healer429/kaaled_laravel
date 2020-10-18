<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemAddItemCategoryColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_types', function (Blueprint $table) {
            $table->bigInteger('item_category_id')->unsigned();
            $table->foreign('item_category_id')->references('id')
                ->on('item_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_types', function (Blueprint $table) {
            //
        });
    }
}
