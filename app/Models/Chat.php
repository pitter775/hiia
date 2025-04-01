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
        
        'modelo_id',      // Relacionamento com o modelo
    ];

    // Relacionamento com o modelo
    public function modelo() {
        return $this->belongsTo(Modelo::class);
    }

    // Relacionamento com mensagens
    public function mensagens() {
        return $this->hasMany(Mensagem::class);
    }
}
