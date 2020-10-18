<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', '200');
            $table->string('email', '100')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('logo')->default("../../../assets/images/Ellipse211.png");
            $table->boolean('online')->default(false);
            $table->date('date_of_birth')->nullable();
            $table->rememberToken()->nullable();
            $table->string('address', 1024)->nullable();
            $table->string('city', 300)->nullable();
            $table->string('state', 300)->nullable();
            $table->string('country', 300)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
