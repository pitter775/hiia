<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Modelo extends Model
{
    use HasFactory;
    // Permite preenchimento em massa nos seguintes campos
    protected $fillable = [
        'user_id',   // Relacionamento com o usuário
        'nome',      // Nome do modelo
        'descricao', // Descrição opcional
        'dados',     // Informações do modelo em JSON ou texto
        'allowed_domains', // Domínios permitidos para uso
        'activated_at', // Domínios permitidos para uso
        'token', // Domínios permitidos para uso
    ];

    // Relacionamento com o usuário
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com chats
    public function chats() {
        return $this->hasMany(Chat::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
