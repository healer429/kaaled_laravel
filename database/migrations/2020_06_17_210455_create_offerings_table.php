<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offerings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title', 500);
            $table->text('description');
            $table->integer('reward_count')->nullable();
            $table->string('address', 1024)->nullable();
            $table->string('city', 300)->nullable();
            $table->string('state', 300)->nullable();
            $table->string('country', 300)->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->boolean('is_picked')->default(false);

            $table->bigInteger('picked_by')->unsigned();
            $table->foreign('picked_by')->references('id')
                ->on('users')->onDelete('cascade');

            $table->timestamp('picked_date')->nullable();
            $table->string('otp', 10)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offerings');
    }
}
