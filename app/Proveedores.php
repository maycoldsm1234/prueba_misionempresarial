<?php

namespace App;

use App\Productos;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $table = 'proveedores';
    public $timestamps = false;

    protected $guarded = [];

    public static function rules($id=0, $merge=[]) {
        return array_merge( 
            [
                'prov_nit' => 'required|unique:proveedores'.($id ? ",$id" : ''),
                'prov_nombre' => 'required',
                'prov_direccion' => 'required',
                'prov_telefono' => 'required',
                'prov_email' => 'email'
            ],
            $merge
        );
    }

    public function productos()
    {
        return $this->hasMany(Productos::class, 'id', 'prod_proveedor');
    }

}
