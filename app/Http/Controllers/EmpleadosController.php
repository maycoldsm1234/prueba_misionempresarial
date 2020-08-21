<?php

namespace App\Http\Controllers;

use App\Empleados;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;

class EmpleadosController extends Controller
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
        $title = 'Empleados';
        return view('empleados.listado', compact('title'));
    }

    public function listado(Request $request)
    {
        $query = Empleados::with('cliente');
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
        return $empleado->get()[0];
    
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), Empleados::rules());
        if ($validator->fails()) {
            return array(
                'success' => false,
                'error'   => $validator->errors()
            );
        }
    
        $data = $request->except('_token');
        Empleados::create($data);
        
        return array(
            'success' => true,
            'msg'   => 'Empleado creado exitosamente',
            'emp_documento' => $request->input('emp_documento')
        );
    }

    public function editar(Request $request)
    {
        $empleado = Empleados::find($request->input('id'));

        $validator = Validator::make($request->all(), Empleados::rules('id'));
        if ($validator->fails()) {
            return array(
                'success' => false,
                'error'   => $validator->errors()
            );
        }
        
        $data = $request->except(['_token']);
        $empleado->fill($data);
		$empleado->save();
        
        return array(
            'success' => true,
            'msg'   => 'Empleado modificado exitosamente',
            'emp_documento' => $request->input('emp_documento')
        );
    }

    public function eliminar(Request $request)
    {
        $empleado = Empleados::find($request->input('id'));
        $empleado->delete();
        
        return array(
            'success' => true,
            'msg'   => 'Empleado Eliminado exitosamente'
        );
    }
}
