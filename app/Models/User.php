<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',          // Nome do usuário
        'email',         // Email do usuário
        'password',      // Senha do usuário
        'tipo_usuario',  // Tipo do usuário (cliente ou admin)
        'photo',         // URL da foto do usuário
        'telefone',      // Telefone com DDD
        'status',        // Status do usuário
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relacionamento com reservas
     */

    // Relacionamento com mensagens
    public function mensagens() {
        return $this->hasMany(Mensagem::class);
    }

    // Relacionamento com chats
    public function chats() {
        return $this->belongsToMany(Chat::class, 'chat_usuarios');
    }
    
}

