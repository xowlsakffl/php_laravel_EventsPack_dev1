<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('udx');
            $table->string('uid', 50)->unique();
            $table->string('password')->default("")->nullable();
            $table->string('name', 150)->default("");
            $table->string('email')->unique()->default("");
            $table->string('email_auth')->default("N");
            $table->string('cell')->default("");
            $table->string('cell_auth')->default("N");
            $table->string('tel')->default("");
            $table->string('join_from')->default("home");
            $table->string('state')->default(10);
            $table->timestamp('email_verified_at')->nullable();
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
