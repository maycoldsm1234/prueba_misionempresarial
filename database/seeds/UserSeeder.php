<?php

use App\User;
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
        $user = User::create([
            'name' 		 => 'Maycol Sanchez',
            'email'      => 'maycoldsm1234@gmail.com',
            'password'   =>  Hash::make('891012VaJu')
        ]);

        $user2 = User::create([
            'name' 		 => 'Administrador',
            'email'      => 'admin@admin.com',
            'password'   =>  Hash::make('administrador')
        ]);
    }
}
