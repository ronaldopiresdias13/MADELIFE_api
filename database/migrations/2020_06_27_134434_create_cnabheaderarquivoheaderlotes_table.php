<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabheaderarquivoheaderlotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabheaderarquivoheaderlotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cnabheaderarquivo_id')->constrained()->onDelete('cascade');
            $table->foreignId('cnabheaderlote_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('cnabheaderarquivoheaderlotes');
    }
}
