<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition()
    {
        return [
            'requester_name' => $this->faker->name,
            'destination' => $this->faker->city,
            'departure_date' => $this->faker->date(),
            'return_date' => $this->faker->date(),
            'status' => 'requested',
            'user_id' => \App\Models\User::factory(),
        ];
    }
}

