<?php

namespace App;

use App\Empleados;
use App\Clientes;
use Illuminate\Database\Eloquent\Model;

class Secciones extends Model
{
    protected $table = 'secciones';

    protected $guarded = [];

    public static function rules($id=0, $merge=[]) {
        return array_merge( 
            [
                'descripcion' => 'required|unique:secciones'.($id ? ",$id" : ''),
            ],
            $merge
        );
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleados::class, 'empleados_secciones', 'seccion_id', 'empleado_id');
    }
    
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }
}