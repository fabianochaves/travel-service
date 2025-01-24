<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Usuário 1',
            'email' => 'fabiano-chaves@hotmail.com',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'name' => 'Usuário 2',
            'email' => 'fabianochavesg@gmail.com',
            'password' => Hash::make('123'),
        ]);
    }
}
