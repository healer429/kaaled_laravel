<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name', 200);
            $table->string('description', 2048)->nullable();

            $table->bigInteger('item_type_id')->unsigned();
            $table->bigInteger('item_category_id')->unsigned();

            $table->foreign('item_type_id')->references('id')
                ->on('item_types')->onDelete('cascade');
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
        Schema::dropIfExists('items');
    }
}
