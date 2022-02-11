<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Conversas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversas', function (Blueprint $table) {
            $table->id();
            

            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('pessoas')->onDelete('cascade');

            $table->unsignedBigInteger('receive_id');
            $table->foreign('receive_id')->references('id')->on('pessoas')->onDelete('cascade');
            // $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
           

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
        Schema::dropIfExists('conversas');
    }
}
