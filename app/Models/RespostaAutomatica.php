<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaAutomatica extends Model
{
    use HasFactory;

    protected $fillable = [
      'modelo_id',
      'canal',
      'gatilho',
      'resposta',
      'tipo_resposta',
      'ativo',
    ];
  
    public function modelo()
    {
      return $this->belongsTo(Modelo::class);
    }
}
