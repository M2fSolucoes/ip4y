<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new User();

        $users = [
            [
                "name" => "Administrador",
                "email" => "admin@ip4y.com.br",
                "user_type" => "admin",
                "password" => Hash::make("#ip4Y@2024")
            ],
            [
                "name" => "Usuário 1",
                "email" => "user1@ip4y.com.br",
                "user_type" => "user",
                "password" => Hash::make("#ip4Y@2024")
            ],
            [
                "name" => "Usuário 2",
                "email" => "user2@ip4y.com.br",
                "user_type" => "user",
                "password" => Hash::make("#ip4Y@2024")
            ],
            [
                "name" => "Marcelo Bezerra",
                "email" => "marcelo.bezerra@ip4y.com.br",
                "user_type" => "user",
                "password" => Hash::make("#ip4Y@2024")
            ]
        ];

        foreach($users as $user) {
            $model->create($user);
        }


    }
}
