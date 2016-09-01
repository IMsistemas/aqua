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

/*Kevin*/

//Ruta agina de inicio para el ingreso de otros rubros antes de tomar la lectura
Route::get('/recaudacion/otrosrubros', function (){
	return view('cuentas/otros-rubros');
});
//Ruta devuelve un arreglo de todos los suministros a AngularJS 
Route::get('/recaudacion/otrosrubros/suministros','Cuentas\CobroAguaController@getSuministros');
//Ruta devuelve un arreglo de un solo suministro a AngularJS 
Route::get('/recaudacion/otrosrubros/suministros\{id}','Cuentas\CobroAguaController@getSuministro');

/*Christian*/

/*Raidel*/

/*Yamilka*/

/*Sebastian*/
