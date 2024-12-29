<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemSala extends Model
{
    use HasFactory;
    protected $table = 'imagens_salas';

    protected $fillable = ['sala_id', 'path', 'principal'];

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}
