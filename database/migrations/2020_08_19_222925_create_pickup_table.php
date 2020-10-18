<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->double('amount', 8, 2, true);

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->foreign('item_id')->references('id')
                ->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickup');
    }
}
