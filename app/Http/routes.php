<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*--------------------------------------Kevin-------------------------------------------------*/

/*===========================Ingreso rubros fijos y variables=================================*/

//Ruta agina de inicio para el ingreso de otros rubros antes de tomar la lectura
Route::get('/recaudacion', function (){
	return view('Cuentas/cobroagua');
});
//Ruta devuelve un arreglo de todos los suministros a AngularJS 
Route::get('/recaudacion/cobroagua/cuentas','Cuentas\CobroAguaController@getCuentas');
//Ruta devuelve un arreglo de un solo suministro a AngularJS 
Route::get('/recaudacion/cobroagua/cuentas/{numerocuenta}','Cuentas\CobroAguaController@getCuenta');
//Ruta devuelve un arreglo de todos los rubros variables a AngularJS
Route::get('/recaudacion/cobroagua/rubrosvariables','Cuentas\CobroAguaController@getRubrosVariables');
//Ruta devuelve un arreglo de todos los rubros fijos a AngularJS
Route::get('/recaudacion/cobroagua/rubrosfijos','Cuentas\CobroAguaController@getRubrosFijos');
//Ruta para guardar los valores de los rubros variables y fijos
Route::post('/recaudacion/cobroagua/guardarrubros{numerocuenta}','Cuentas\CobroAguaController@guardarRubros');


/*=============================================================================================*/


/*------------------------------------Christian------------------------------------------------*/

/*===================================Módulo Clientes===========================================*/
//Ruta página de inicio de gestión de clientes
Route::get('/clientes', function (){
	return view('/clientes/index');
});
//Ruta devuelve un arreglo de todos los clientes a AngularJS 
Route::get('/clientes/{cedulapersona?}','Clientes\ClienteController@index');
//Ruta página de inicio de gestión de clientes
Route::post('/clientes/','Clientes\ClientesController@store');
//Ruta página de inicio de gestión de clientes
Route::post('/clientes/{cedulapersona}','Clientes\ClienteController@update');
//Ruta página de inicio de gestión de clientes
Route::delete('/clientes/{cedulapersona}','Clientes\ClienteController@destroy');

/*Raidel*/

/*Yamilka*/

//Ruta devuelve el ultimo ID + 1 de cargos
Route::get('cargo/lastId', 'Nomina\CargoController@getLastID');
//Ruta devuelve todos los cargos
Route::get('cargo/getCargos', 'Nomina\CargoController@getCargos');
//Ruta devuelve la informacion del cargo solicitado
Route::get('cargo/{id}', 'Nomina\CargoController@show');
//Resource, atiende peticiones REST generales: [GET|POST|PUT|DELETE] hacia cargo
Route::resource('cargo', 'Nomina\CargoController');

//Ruta devuelve todos los empleados
Route::get('empleado/getEmployees', 'Nomina\EmpleadoController@getEmployees');
//Ruta devuelve todos los cargos
Route::get('empleado/getAllPositions', 'Nomina\EmpleadoController@getAllPositions');
//Resource, atiende peticiones REST generales: [GET|POST|PUT|DELETE] hacia empleado
Route::resource('empleado', 'Nomina\EmpleadoController');


/*Sebastian*/
