<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomecareTelefoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homecare_telefone', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homecare_id');
            $table->foreign('homecare_id')->references('id')->on('homecares')->onDelete('cascade');
            $table->unsignedBigInteger('telefone_id');
            $table->foreign('telefone_id')->references('id')->on('telefones')->onDelete('cascade');
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
        Schema::dropIfExists('homecare_telefone');
    }
}
