<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabheaderarquivotrailerlotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabheaderarquivotrailerlotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cnabheaderarquivo_id')->constrained()->onDelete('cascade');
            $table->foreignId('cnabtrailerlote_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('cnabheaderarquivotrailerlotes');
    }
}
