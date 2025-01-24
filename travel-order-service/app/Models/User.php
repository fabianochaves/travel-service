<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    // Retorna a chave primária do usuário
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Customizar os dados do token (caso queira adicionar mais dados)
    public function getJWTCustomClaims()
    {
        return [];
    }
}
