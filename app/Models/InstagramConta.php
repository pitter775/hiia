<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramConta extends Model
{
  use HasFactory;

  protected $fillable = [
    'modelo_id',
    'ig_business_id',
    'nome_conta',
    'token',
    'ativo',
  ];

  public function modelo()
  {
    return $this->belongsTo(Modelo::class);
  }

  public function eventos()
  {
    return $this->hasMany(EventoInstagram::class);
  }
}
