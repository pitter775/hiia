<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoInstagram extends Model
{
    use HasFactory;

    protected $fillable = [
      'instagram_conta_id',
      'payload',
      'tipo_evento',
      'recebido_em',
    ];
  
    public function conta()
    {
      return $this->belongsTo(InstagramConta::class, 'instagram_conta_id');
    }
}
