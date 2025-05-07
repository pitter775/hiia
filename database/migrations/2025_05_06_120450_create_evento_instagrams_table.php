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
        Schema::create('evento_instagrams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instagram_conta_id')->constrained('instagram_contas')->onDelete('cascade');
            $table->json('payload')->nullable(); // Armazena o evento completo
            $table->string('tipo_evento')->nullable();
            $table->timestamp('recebido_em')->nullable();
            $table->timestamps();
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_instagrams');
    }
};
