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
    return view('login');
});

Route::get('/inicio', function () {
    return view('index');
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
	return view('clientes/index');
});

//Ruta devuelve un arreglo de todos los clientes a AngularJS 
Route::get('/clientes/gestion/','Clientes\ClienteController@index');
//Ruta devuelve un arreglo de todos los clientes a AngularJS 
Route::get('/clientes/gestion/{documentoidentidad}','Clientes\ClienteController@show');
//Ruta página de inicio de gestión de clientes
Route::post('/clientes/gestion/guardarcliente','Clientes\ClienteController@store');
//Ruta página de inicio de gestión de clientes
Route::post('/clientes/gestion/actualizarcliente/{documentoidentidad}','Clientes\ClienteController@update');
//Ruta página de inicio de gestión de clientes
Route::post('/clientes/gestion/eliminarcliente/{documentoidentidad}','Clientes\ClienteController@destroy');


/*===================================Módulo Provincia===========================================*/

Route::get('/provincias', function (){
	return view('Sectores/provincia');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/provincias/gestion','Sectores\ProvinciaController@index');
//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/provincias/gestion/{idprovincia?}','Sectores\ProvinciaController@show');
//Ruta página de inicio de gestión de provincias
Route::get('/provincias/gestion/ultimocodigoprovincia','Sectores\ProvinciaController@getUltimoCodigoProvincia');
//Ruta página de inicio de gestión de provincias
Route::post('/provincias/gestion/guardarprovincia','Sectores\ProvinciaController@postCrearProvincia');
//Ruta página de inicio de gestión de provincias
Route::post('/provincias/gestion/actualizarprovincia/{idprovincia}','Sectores\ProvinciaController@postActualizarProvincia');
//Ruta página de inicio de gestión de provincias
Route::post('/provincias/gestion/eliminarprovincia/{idprovincia}','Sectores\ProvinciaController@destroy');

/*===================================Módulo Cantón===========================================*/


Route::get('/cantones', function (){
	return view('Sectores/canton');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/cantones/gestion/','Sectores\CantonController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/cantones/gestion/{idcanton?}','Sectores\CantonController@show');
//Ruta página de inicio de gestión de Cantons
Route::get('/cantones/gestion/ultimocodigocanton','Sectores\CantonController@getUltimoCodigoCanton');
//Ruta página de inicio de gestión de Cantons
Route::post('/cantones/gestion/guardarcanton','Sectores\CantonController@postCrearCanton');
//Ruta página de inicio de gestión de Cantons
Route::post('/cantones/gestion/actualizarcanton/{idcanton}','Sectores\CantonController@postActualizarCanton');
//Ruta página de inicio de gestión de Cantons
Route::post('/cantones/gestion/eliminarcanton/{idcanton}','Sectores\CantonController@destroy');

/*===================================Módulo Parroquia===========================================*/

Route::get('/parroquias', function (){
	return view('Sectores/parroquia');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/parroquias/gestion/{idcanton?}','Sectores\ParroquiaController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/parroquias/gestion/{idparroquia?}','Sectores\ParroquiaController@show');
//Ruta página de inicio de gestión de parroquias
Route::get('/parroquias/gestion/ultimocodigoparroquia','Sectores\ParroquiaController@getUltimoCodigoparroquia');
//Ruta página de inicio de gestión de parroquias
Route::post('/parroquias/gestion/guardarparroquia','Sectores\ParroquiaController@postCrearparroquia');
//Ruta página de inicio de gestión de parroquias
Route::post('/parroquias/gestion/actualizarparroquia/{idparroquia}','Sectores\ParroquiaController@postActualizarparroquia');
//Ruta página de inicio de gestión de parroquias
Route::post('/parroquias/gestion/eliminarparroquia/{idparroquia}','Sectores\ParroquiaController@destroy');


/*===================================Módulo Barrio===========================================*/

Route::get('/barrios', function (){
	return view('Sectores/barrio');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/barrios/gestion/','Sectores\BarrioController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/barrios/gestion/{idbarrio?}','Sectores\BarrioController@show');
//Ruta página de inicio de gestión de barrios
Route::get('/barrios/gestion/ultimocodigobarrio','Sectores\BarrioController@getUltimoCodigobarrio');
//Ruta página de inicio de gestión de barrios
Route::post('/barrios/gestion/guardarbarrio','Sectores\BarrioController@postCrearbarrio');
//Ruta página de inicio de gestión de barrios
Route::post('/barrios/gestion/actualizarbarrio/{idbarrio}','Sectores\BarrioController@postActualizarbarrio');
//Ruta página de inicio de gestión de barrios
Route::post('/barrios/gestion/eliminarbarrio/{idbarrio}','Sectores\BarrioController@destroy');

/*===================================Módulo Calle===========================================*/

Route::get('/calles', function (){
	return view('Sectores/calle');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/calles/gestion/','Sectores\CalleController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/calles/gestion/{idcalle?}','Sectores\CalleController@show');
//Ruta página de inicio de gestión de calles
Route::get('/calles/gestion/ultimocodigocalle','Sectores\CalleController@getUltimoCodigocalle');
//Ruta página de inicio de gestión de calles
Route::post('/calles/gestion/guardarcalle','Sectores\CalleController@postCrearcalle');
//Ruta página de inicio de gestión de calles
Route::post('/calles/gestion/actualizarcalle/{idcalle}','Sectores\CalleController@postActualizarcalle');
//Ruta página de inicio de gestión de calles
Route::post('/calles/gestion/eliminarcalle/{idcalle}','Sectores\CalleController@destroy');

/*Raidel*/

Route::get('nuevaLectura/lastId', 'Lecturas\LecturaController@getLastID');
Route::get('nuevaLectura/getRubros', 'Lecturas\LecturaController@getRubros');
Route::get('nuevaLectura/getRubros/{consumo}/{tarifa}', 'Lecturas\LecturaController@getRubrosValue');
Route::resource('nuevaLectura', 'Lecturas\LecturaController');

/*------------------------------------Yamilka------------------------------------------------*/

/*===================================Módulo Nomina===========================================*/
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


/*------------------------------------Sebastian------------------------------------------------*/

/*=============================Módulo Solicitud Suministro====================================*/


Route::get('/suministros/solicitudes', function (){
	return view('Suministros/Solicitudes/index');
});

Route::get('suministros/solicitudes/solicitudes','Suministros\SolicitudController@index');

Route::get('suministros/solicitudes/{idSolicitud}','Suministros\SolicitudController@getSolicitud');
