<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Modelo;
use App\Models\Mensagem;

class Chat extends Model
{
    use HasFactory;
    
    // Permite preenchimento em massa nos seguintes campos
    protected $fillable = [
        'user_id',        // Relacionamento com o usuário
        'modelo_id',      // Relacionamento com o modelo
        'mensagem_usuario', // Mensagem enviada pelo usuário
        'resposta_gpt',   // Resposta gerada pelo GPT
    ];

    // Relacionamento com o usuário
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o modelo
    public function modelo() {
        return $this->belongsTo(Modelo::class);
    }

    // Relacionamento com mensagens
    public function mensagens() {
        return $this->hasMany(Mensagem::class);
    }
}
