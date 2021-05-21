<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    class Cnab extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
    
            Schema::create('registrocnabs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('empresa_id')->nullable();
                $table->foreign('empresa_id')->references('id')->on('empresas');
                $table->string('arquivo');
                $table->string('mes');
                $table->string('codigo_banco');
                $table->date('data');
                $table->string('observacao')->nullable();
                $table->string('situacao')->nullable();
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
            Schema::dropIfExists('registrocnabs');
        }
    }