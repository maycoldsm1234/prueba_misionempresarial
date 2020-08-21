<?php

namespace App;

use App\Cliente;
use App\Secciones;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    protected $table = 'empleados';
    public $timestamps = false;

    protected $guarded = [];

    public static function rules($id=0, $merge=[]) {
        return array_merge( 
            [
                'emp_documento' => 'required|unique:empleados'.($id ? ",$id" : ''),
                'emp_nombre' => 'required',
                'emp_direccion' => 'required',
                'emp_telefono' => 'required',
                'emp_email' => 'email',
                'emp_cliente' => 'required'
            ],
            $merge
        );
    }

    public static function rules_seccion($id=0, $merge=[]) {
        return array_merge( 
            [
                'empleado_id' => 'required',
                'seccion_id' => 'required'
            ],
            $merge
        );
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'emp_cliente');
    }

    public function secciones()
    {
        return $this->belongsToMany(Secciones::class, 'empleados_secciones', 'empleado_id', 'seccion_id');
    }
}
