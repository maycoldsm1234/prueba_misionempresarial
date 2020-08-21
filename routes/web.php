<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->intended("home");
    }
    return view('login');
})->name('/');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => '/clientes'], function () {
    Route::get('', 'ClientesController@index')->name('clientes');
    Route::post('/listado', 'ClientesController@listado')->name('clientes.listado');
    Route::post('/agregar', 'ClientesController@nuevo')->name('clientes.agregar');
    Route::post('/editar', 'ClientesController@editar')->name('clientes.editar');
    Route::post('/eliminar', 'ClientesController@eliminar')->name('clientes.eliminar');
    Route::post('/{id}', 'ClientesController@buscar')->name('clientes.buscar');
});

Route::group(['prefix' => '/empleados'], function () {
    Route::get('', 'EmpleadosController@index')->name('empleados');
    Route::post('/listado', 'EmpleadosController@listado')->name('empleados.listado');
    Route::post('/agregar', 'EmpleadosController@nuevo')->name('empleados.agregar');
    Route::post('/editar', 'EmpleadosController@editar')->name('empleados.editar');
    Route::post('/eliminar', 'EmpleadosController@eliminar')->name('empleados.eliminar');
    Route::post('/{id}', 'EmpleadosController@buscar')->name('empleados.buscar');
});

Route::group(['prefix' => '/proveedores'], function () {
    Route::get('', 'ProveedoresController@index')->name('proveedores');
    Route::post('/listado', 'ProveedoresController@listado')->name('proveedores.listado');
    Route::post('/agregar', 'ProveedoresController@nuevo')->name('proveedores.agregar');
    Route::post('/editar', 'ProveedoresController@editar')->name('proveedores.editar');
    Route::post('/eliminar', 'ProveedoresController@eliminar')->name('proveedores.eliminar');
    Route::post('/{id}', 'ProveedoresController@buscar')->name('proveedores.buscar');
});

Route::group(['prefix' => '/productos'], function () {
    Route::get('', 'ProductosController@index')->name('productos');
    Route::post('/listado', 'ProductosController@listado')->name('productos.listado');
    Route::post('/agregar', 'ProductosController@nuevo')->name('productos.agregar');
    Route::post('/editar', 'ProductosController@editar')->name('productos.editar');
    Route::post('/eliminar', 'ProductosController@eliminar')->name('productos.eliminar');
    Route::post('/{id}', 'ProductosController@buscar')->name('productos.buscar');
});

Route::group(['prefix' => '/secciones'], function () {
    Route::get('', 'SeccionesController@index')->name('secciones');
    Route::post('/listado', 'SeccionesController@listado')->name('secciones.listado');
    Route::post('/agregar', 'SeccionesController@nuevo')->name('secciones.agregar');
    Route::post('/editar', 'SeccionesController@editar')->name('secciones.editar');
    Route::post('/eliminar', 'SeccionesController@eliminar')->name('secciones.eliminar');
    Route::post('/{id}', 'SeccionesController@buscar')->name('secciones.buscar');
});

Route::group(['prefix' => '/empleadossecciones'], function () {
    Route::get('', 'EmpleadosSeccionesController@index')->name('empleadossecciones');
    Route::post('/listado', 'EmpleadosSeccionesController@listado')->name('empleadossecciones.listado');
    Route::post('/agregar', 'EmpleadosSeccionesController@nuevo')->name('empleadossecciones.agregar');
    Route::post('/editar', 'EmpleadosSeccionesController@editar')->name('empleadossecciones.editar');
    Route::post('/eliminar', 'EmpleadosSeccionesController@eliminar')->name('empleadossecciones.eliminar');
    Route::post('/{id}', 'EmpleadosSeccionesController@buscar')->name('empleadossecciones.buscar');
});

Route::post('/select2/{tipo}', 'Select2Controller@index')->name('select2');
