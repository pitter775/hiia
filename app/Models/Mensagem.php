<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;
    protected $table = 'mensagens'; // Correção para o nome da tabela


    // Permite preenchimento em massa nos seguintes campos
    protected $fillable = [
        'chat_id',       // Relacionamento com o chat
        'conteudo',      // Conteúdo da mensagem
        'remetente',     // Quem enviou (cliente ou GPT)
    ];

    // Relacionamento com o chat
    public function chat() {
        return $this->belongsTo(Chat::class);
    }

}
