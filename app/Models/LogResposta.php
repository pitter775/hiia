<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogResposta extends Model
{
    use HasFactory;

    protected $fillable = [
      'modelo_id',
      'canal',
      'conteudo_recebido',
      'resposta_enviada',
      'tipo_resposta',
      'enviado_em',
    ];
  
    public function modelo()
    {
      return $this->belongsTo(Modelo::class);
    }
}
