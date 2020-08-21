<?php

use App\Tipo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tipo::create([
            'descripcion' => 'Persona Natural'
        ]);

        Tipo::create([
            'descripcion' => 'Persona Juridica'
        ]);
    }
}
