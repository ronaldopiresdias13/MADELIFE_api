<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConversasMensagens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversas_mensagens', function (Blueprint $table) {
            $table->id();

            $table->string('type')->default('text');
           
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('pessoas')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->string('arquivo')->nullable();

            $table->text('uuid');
            $table->boolean('visto')->default(false);
            $table->unsignedBigInteger('conversa_id');
            $table->foreign('conversa_id')->references('id')->on('conversas')->onDelete('cascade');
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
        Schema::dropIfExists('conversas_mensagens');
    }
}
