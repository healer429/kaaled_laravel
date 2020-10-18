<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItemsAddQtyForeignWeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
            $table->bigInteger('offering_id')->unsigned();
            $table->decimal('qty', 10, 2);
            $table->string('unit')->nullable()->default(null);

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
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
