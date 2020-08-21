<?php

namespace App;

use App\Proveedores;
use App\Clientes;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $guarded = [];

    public static function rules($id=0, $merge=[]) {
        return array_merge( 
            [
                'prod_nombre' => 'required',
                'prod_descripcion' => 'required',
                'prod_valor' => 'required',
                'prod_proveedor' => 'required',
                'prod_cliente' => 'required'
            ],
            $merge
        );
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'prod_proveedor');
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'prod_cliente');
    }
}
