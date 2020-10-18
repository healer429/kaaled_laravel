<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveExtraColumnsOfferings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offerings', function (Blueprint $table) {
            $table->dropForeign(['picked_by']);
            $table->dropColumn(['is_picked', 'picked_by', 'picked_date', 'otp']);

            $table->double('lat', 15, 12)->nullable()->default(null);
            $table->double('lng', 15, 12)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offerings', function (Blueprint $table) {
            //
        });
    }
}
