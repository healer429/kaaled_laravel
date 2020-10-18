<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earning', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('offering_id')->unsigned()->nullable();
            $table->integer('amount')->default(0);

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->foreign('offering_id')->references('id')
                ->on('offerings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('earning');
    }
}
