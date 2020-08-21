<?php

namespace App\Http\Controllers;

use App\Clientes;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
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
        $title = 'Clientes';
        return view('clientes.listado', compact('title'));
    }

    public function listado(Request $request)
    {
        $query = Clientes::with('tipo');
        return datatables()->of($query)
            ->addColumn('action', function ($data) {
                return '<button class="btn-editar btn btn-sm btn-icon btn--raised btn-success" data-id="'.$data->id.'"><i class="zmdi zmdi-edit"></i></button>
                <button class="btn-eliminar btn btn-sm btn-icon btn--raised btn-danger" data-id="'.$data->id.'"><i class="zmdi zmdi-delete"></i></button>';
            })
            ->make(true);
    }

    public function buscar($id)
    {
        $cliente = new Clientes;
        $cliente->find($id);
        return $cliente->get()[0];
    
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), Clientes::rules());
        if ($validator->fails()) {
            return array(
                'success' => false,
                'error'   => $validator->errors()
            );
        }
    
        $data = $request->except('_token');
        Clientes::create($data);
        
        return array(
            'success' => true,
            'msg'   => 'Cliente creado exitosamente',
            'cli_documento' => $request->input('cli_documento')
        );
    }

    public function editar(Request $request)
    {
        $cliente = Clientes::find($request->input('id'));

        $validator = Validator::make($request->all(), Clientes::rules('id'));
        if ($validator->fails()) {
            return array(
                'success' => false,
                'error'   => $validator->errors()
            );
        }
        
        $data = $request->except(['_token']);
        $cliente->fill($data);
		$cliente->save();
        
        return array(
            'success' => true,
            'msg'   => 'Cliente modificado exitosamente',
            'cli_documento' => $request->input('cli_documento')
        );
    }

    public function eliminar(Request $request)
    {
        $cliente = Clientes::find($request->input('id'));
        $cliente->delete();
        
        return array(
            'success' => true,
            'msg'   => 'Cliente Eliminado exitosamente'
        );
    }
}
