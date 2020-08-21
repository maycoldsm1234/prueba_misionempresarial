<?php

namespace App;

use App\Clientes;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipos';
    public $timestamps = false;

    protected $guarded = [];

    public function cliente()
    {
        return $this->hasMany(Clientes::class, 'id', 'cli_tipo');
    }
}
