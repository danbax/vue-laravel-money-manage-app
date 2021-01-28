<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
            $table->id();
            $table->boolean('isFirstTime')->default(true);
            $table->string('email')->unique();
            $table->string('password');
            $table->float('current_amount')->default(0);
            $table->float('income_amount')->default(0);
            $table->date('income_date')->default(new Carbon("01/01/2021"));
            $table->string('default_screen')->default("TODAY_STATUS");
            $table->rememberToken();
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
