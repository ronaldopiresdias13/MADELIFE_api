<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUuidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->uuid('user_id')->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->change();
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
