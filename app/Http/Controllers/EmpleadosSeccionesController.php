<?php

namespace App\Http\Controllers;

use App\Empleados;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;

class EmpleadosSeccionesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Empleados Secciones';
        return view('empleadossecciones.listado', compact('title'));
    }

    public function listado(Request $request)
    {
        $query = Empleados::with('secciones', 'cliente');
        return datatables()->of($query)
            ->addColumn('action', function ($data) {
                return '<button class="btn-editar btn btn-sm btn-icon btn--raised btn-success" data-id="'.$data->id.'"><i class="zmdi zmdi-edit"></i></button>
                <button class="btn-eliminar btn btn-sm btn-icon btn--raised btn-danger" data-id="'.$data->id.'"><i class="zmdi zmdi-delete"></i></button>';
            })
            ->make(true);
    }

    public function buscar($id)
    {
        $empleado = new Empleados;
        $empleado->find($id);
        $empleado->secciones();
        return $empleado->get()[0];
    
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), Empleados::rules_seccion());
        if ($validator->fails()) {
            return array(
                'success' => false,
                'error'   => $validator->errors()
            );
        }
    
        $empleado = Empleados::find($request->input('empleado_id'));
        $empleado->secciones()->attach($request->input('seccion_id'), array('cliente_id' => $request->input('cliente_id')));
        
        return array(
            'success' => true,
            'msg'   => 'La seccion fue asignada al empleado exitosamente'
        );
    }

    public function editar(Request $request)
    {
        $validator = Validator::make($request->all(), Empleados::rules_seccion('id'));
        if ($validator->fails()) {
            return array(
                'success' => false,
                'error'   => $validator->errors()
            );
        }

        $empleado = Empleados::find($request->input('empleado_id'));
        $empleado->secciones()->sync($request->input('seccion_id'), array('cliente_id' => $request->input('cliente_id')));
        
        return array(
            'success' => true,
            'msg'   => 'Seccion modificado exitosamente',
            'emp_documento' => $request->input('emp_documento')
        );
    }

    public function eliminar(Request $request)
    {
        $empleado = Empleados::find($request->input('id'));

        $empleado->secciones()->detach();
        
        return array(
            'success' => true,
            'msg'   => 'Seccion Eliminado exitosamente'
        );
    }
}
