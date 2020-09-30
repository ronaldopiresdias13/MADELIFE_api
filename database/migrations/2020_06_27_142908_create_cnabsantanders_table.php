<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabsantandersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabsantanders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cnab_id')->constrained()->onDelete('cascade');
            $table->foreignId('cnabheaderarquivo_id')->constrained()->onDelete('cascade');
            $table->foreignId('cnabtrailerarquivo_id')->constrained()->onDelete('cascade');
            $table->string('tipo')->nullable();
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
        Schema::dropIfExists('cnabsantanders');
    }
}
