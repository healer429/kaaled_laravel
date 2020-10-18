<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offering_chats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->boolean('is_read')->default(false);
            $table->bigInteger('offering_id')->unsigned();
            $table->foreign('offering_id')->references('id')
                ->on('offerings')->onDelete('cascade');

            $table->bigInteger('from')->unsigned();
            $table->foreign('from')->references('id')
                ->on('users')->onDelete('cascade');

            $table->bigInteger('to')->unsigned();
            $table->foreign('to')->references('id')
                ->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offering_chats');
    }
}
