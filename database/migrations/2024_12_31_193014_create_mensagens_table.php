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
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->text('conteudo');
            $table->enum('remetente', ['cliente', 'gpt']); // Diferencia quem enviou a mensagem
            $table->timestamp('enviado_em')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
