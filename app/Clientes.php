<?php

namespace App;

use App\Tipo;
use App\Empleados;
use App\Productos;
use App\Secciones;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;

    protected $guarded = [];

    public static function rules($id=0, $merge=[]) {
        return array_merge( 
            [
                'cli_documento' => 'required|unique:clientes'.($id ? ",$id" : ''),
                'cli_nombre' => 'required',
                'cli_direccion' => 'required',
                'cli_telefono' => 'required',
                'cli_email' => 'email',
                'cli_tipo' => 'required'
            ],
            $merge
        );
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'cli_tipo');
    }

    public function empleados()
    {
        return $this->hasMany(Empleados::class, 'id', 'emp_cliente');
    }

    public function secciones()
    {
        return $this->hasMany(Secciones::class, 'id', 'cliente_id');
    }

    public function productos()
    {
        return $this->hasMany(Productos::class, 'id', 'prod_cliente');
    }
}
