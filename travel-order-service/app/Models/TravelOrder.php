<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    // Defina os campos que podem ser preenchidos (mass assignment)
    protected $fillable = [
        'user_id',            // Relacionamento com o User
        'requester_name',     // Nome do solicitante
        'destination',        // Destino
        'departure_date',     // Data de ida
        'return_date',        // Data de volta
        'status',             // Status do pedido
    ];

   
    protected $table = 'travel_orders'; //definição do nome da tabela, pois é diferente do modelo

    protected $primaryKey = 'id'; // chave primária

    protected $dates = ['departure_date', 'return_date']; // definição dos campos de data

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
