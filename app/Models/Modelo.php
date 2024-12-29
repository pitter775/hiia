<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    // Define a tabela associada (caso não siga o padrão plural)
    protected $table = 'modelos';

    // Permite preenchimento em massa nos seguintes campos
    protected $fillable = [
        'user_id',   // Relacionamento com o usuário
        'nome',      // Nome do modelo
        'descricao', // Descrição opcional
        'dados',     // Informações do modelo em JSON ou texto
        'activated_at',
        'chat_token',
        'allowed_domains',
    ];

    // Relacionamento com o usuário (opcional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
