<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingItemRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offering_item_relations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->decimal('qty', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('unit', 10, 2)->nullable();

            $table->bigInteger('offering_id')->unsigned();
            $table->foreign('offering_id')->references('id')
                ->on('offerings')->onDelete('cascade');

            $table->bigInteger('item_id')->unsigned();
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
        Schema::dropIfExists('offering_item_relations');
    }
}
