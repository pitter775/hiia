<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respostas_automaticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modelo_id')->constrained('modelos')->onDelete('cascade');
            $table->string('canal'); // instagram, whatsapp, etc
            $table->string('gatilho');
            $table->text('resposta');
            $table->enum('tipo_resposta', ['fixa', 'gpt', 'api'])->default('gpt');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resposta_automaticas');
    }
};
