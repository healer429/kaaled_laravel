<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offering_images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('image', 200);

            $table->bigInteger('offering_id')->unsigned();
            $table->foreign('offering_id')->references('id')
                ->on('offerings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offering_images');
    }
}
