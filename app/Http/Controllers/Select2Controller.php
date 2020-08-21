<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Select2Controller extends Controller
{

    public function index(Request $request, $tipo)
    {
        return ['results' => $this->$tipo($request)];
    }


    public function tipos(Request $request)
    {
        if($request->has('buscarid')) {
            return \App\Tipo::where('id', $request->input('buscarid'))->get(['id', 'descripcion as text'])[0];
        }
        return \App\Tipo::get(['id', 'descripcion as text']);
    }

    public function cliente(Request $request)
    {
        if($request->has('buscarid')) {
            return \App\Clientes::where('id', $request->input('buscarid'))->get(['id', 'cli_nombre as text'])[0];
        }
        return \App\Clientes::get(['id', 'cli_nombre as text']);
    }

    public function proveedor(Request $request)
    {
        if($request->has('buscarid')) {
            return \App\Proveedores::where('id', $request->input('buscarid'))->get(['id', 'prov_nombre as text'])[0];
        }
        return \App\Proveedores::get(['id', 'prov_nombre as text']);
    }

    public function empleados(Request $request)
    {
        if($request->has('buscarid')) {
            return \App\Empleados::where('id', $request->input('buscarid'))->get(['id', 'emp_nombre as text'])[0];
        }
        if($request->has('cliente_id')) {
            return \App\Empleados::where('emp_cliente', $request->input('cliente_id'))->get(['id', 'emp_nombre as text']);
        }
        return \App\Empleados::get(['id', 'emp_nombre as text']);
    }

    public function secciones(Request $request){
        if($request->has('buscarid')) {
            return \App\Secciones::where('id', $request->input('buscarid'))->get(['id', 'emp_nombre as text'])[0];
        }
        if($request->has('empleado_id')) {
            return \App\Empleados::with('secciones')->where('id', $request->input('empleado_id'))->get();
        }
        return \App\Secciones::with(array('empleados' => function($query) use($request)
        {
            $query->where('empleados.id', '!=', $request->input('empleado_id'));
        
        }))->where('cliente_id', $request->input('cliente_id'))->get(['id', 'descripcion as text']);  
        
    }

}