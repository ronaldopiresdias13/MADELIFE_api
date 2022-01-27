<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeadKatzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neads_katz', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('neads_id')->references('id')->on('neads')->onDelete('cascade');
            
            $table->text('categoria');
            $table->text('value');
            $table->double('pontuacao',8,2);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neads_katz');
    }
}
