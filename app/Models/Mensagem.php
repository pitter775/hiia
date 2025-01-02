<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;


    // Permite preenchimento em massa nos seguintes campos
    protected $fillable = [
        'chat_id',       // Relacionamento com o chat
        'usuario_id',    // Relacionamento com o usuário
        'conteudo',      // Conteúdo da mensagem
        'remetente',     // Quem enviou (cliente ou GPT)
        'enviado_em',    // Data e hora do envio
    ];

    // Relacionamento com o chat
    public function chat() {
        return $this->belongsTo(Chat::class);
    }

    // Relacionamento com o usuário
    public function usuario() {
        return $this->belongsTo(User::class);
    }
}
