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
Route::get('/recaudacion/cobroagua/cuentas','Cuentas\CobroAguaController@index');
//Ruta devuelve un arreglo de un solo suministro a AngularJS 
Route::get('/recaudacion/cobroagua/cuentas/{numerocuenta}','Cuentas\CobroAguaController@getCuenta');
//
Route::get('/recaudacion/cobroagua/cuentas/pagar/{numeroCuenta}','Cuentas\CobroAguaController@pagarCuenta');
//Ruta devuelve un arreglo de todos los rubros variables a AngularJS
Route::get('/recaudacion/cobroagua/rubrosvariables','Cuentas\CobroAguaController@getRubrosVariables');
//Ruta devuelve un arreglo de todos los rubros fijos a AngularJS
Route::get('/recaudacion/cobroagua/rubrosfijos','Cuentas\CobroAguaController@getRubrosFijos');
//Ruta para guardar los valores de los rubros variables y fijos
Route::post('/recaudacion/cobroagua/guardarrubros/{numerocuenta}','Cuentas\CobroAguaController@guardarRubros');
//
Route::get('/recaudacion/cobroagua/generar','Cuentas\CobroAguaController@generarFacturas');


/*=============================================================================================*/


/*------------------------------------Christian------------------------------------------------*/

/*===================================Módulo Clientes===========================================*/
//Ruta página de inicio de gestión de clientes
//Route::get('/clientes', function (){
//	return view('Clientes/index');
//});
//Ruta devuelve un arreglo de todos los clientes a AngularJS
//Route::get('/clientes/gestion/','Clientes\ClienteController@index');
//Ruta devuelve un arreglo de todos los clientes a AngularJS
//Route::get('/clientes/gestion/{codigocliente?}','Clientes\ClienteController@show');
//Ruta página de inicio de gestión de clientes
//Route::post('/clientes/gestion/guardarcliente','Clientes\ClienteController@store');
//Ruta página de inicio de gestión de clientes
//Route::post('/clientes/gestion/actualizarcliente/{documentoidentidad}','Clientes\ClienteController@update');
//Ruta página de inicio de gestión de clientes
//Route::post('/clientes/gestion/eliminarcliente/{documentoidentidad}','Clientes\ClienteController@destroy');


/*===================================Módulo Provincia===========================================*/

Route::get('/provincias', function (){
	return view('Sectores/provincia');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/provincias/gestion','Sectores\ProvinciaController@index');
//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/provincias/gestion/{idprovincia?}','Sectores\ProvinciaController@show');
//Ruta página de inicio de gestión de provincias
Route::get('/provincias/maxid','Sectores\ProvinciaController@maxId');
//Ruta página de inicio de gestión de provincias
Route::post('/provincias/gestion/guardarprovincia','Sectores\ProvinciaController@postCrearProvincia');
//Ruta página de inicio de gestión de provincias
Route::post('/provincias/gestion/actualizarprovincia/{idprovincia}','Sectores\ProvinciaController@postActualizarProvincia');
//Ruta página de inicio de gestión de provincias
Route::Delete('/provincias/gestion/eliminarprovincia/{idprovincia?}','Sectores\ProvinciaController@destroy');

/*===================================Módulo Cantón===========================================*/


Route::get('/cantones', function (){
	return view('Sectores/canton');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/cantones/gestion/{idprovincia?}','Sectores\CantonController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/cantones/gestion/{idcanton?}','Sectores\CantonController@show');
//Ruta página de inicio de gestión de Cantons
Route::get('/cantones/maxid','Sectores\CantonController@maxId');
//Ruta página de inicio de gestión de Cantons
Route::post('/cantones/gestion/guardarcanton/{idprovincia}','Sectores\CantonController@postCrearCanton');
//Ruta página de inicio de gestión de Cantons
Route::post('/cantones/gestion/actualizarcanton/{idcanton}','Sectores\CantonController@postActualizarCanton');
//Ruta página de inicio de gestión de Cantons
Route::Delete('/cantones/gestion/eliminarcanton/{idcanton}','Sectores\CantonController@destroy');

/*===================================Módulo Parroquia===========================================*/

Route::get('/parroquias', function (){
	return view('Sectores/parroquia');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/parroquias/gestion/{idcanton?}','Sectores\ParroquiaController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/parroquias/gestion/{idparroquia?}','Sectores\ParroquiaController@show');
//Ruta página de inicio de gestión de parroquias
Route::get('/parroquias/maxid','Sectores\ParroquiaController@maxId');
//Ruta página de inicio de gestión de parroquias
Route::post('/parroquias/gestion/guardarparroquia/{idcanton}','Sectores\ParroquiaController@postCrearParroquia');
//Ruta página de inicio de gestión de parroquias
Route::post('/parroquias/gestion/actualizarparroquia/{idparroquia}','Sectores\ParroquiaController@postActualizarParroquia');
//Ruta página de inicio de gestión de parroquias
Route::Delete('/parroquias/gestion/eliminarparroquia/{idparroquia}','Sectores\ParroquiaController@destroy');


/*===================================Módulo Barrio===========================================*/

Route::get('/barrios', function (){
	return view('Sectores/barrio');
});

//----Kevin Tambien :-(---------
Route::get('/barrios/gestion/concalles','Sectores\BarrioController@getBarriosCalles');


//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/barrios/gestion/{idparroquia?}','Sectores\BarrioController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/barrios/gestion/{idbarrio?}','Sectores\BarrioController@show');
//Ruta página de inicio de gestión de barrios
Route::get('/barrios/maxid','Sectores\BarrioController@maxId');
//Ruta página de inicio de gestión de barrios
Route::post('/barrios/gestion/guardarbarrio/{idparroquia}','Sectores\BarrioController@postCrearBarrio');
//Ruta página de inicio de gestión de barrios
Route::post('/barrios/gestion/actualizarbarrio/{idbarrio}','Sectores\BarrioController@postActualizarBarrio');
//Ruta página de inicio de gestión de barrios
Route::Delete('/barrios/gestion/eliminarbarrio/{idbarrio}','Sectores\BarrioController@destroy');

/*===================================Módulo Calle===========================================*/

Route::get('/calles', function (){
	return view('Sectores/calle');
});

//Ruta devuelve un arreglo de todos los provincias a AngularJS 
Route::get('/calles/gestion/{idbarrio?}','Sectores\CalleController@index');
//Ruta devuelve un arreglo de todos los Cantons a AngularJS 
Route::get('/calles/gestion/{idcalle?}','Sectores\CalleController@mostrar');
//Ruta página de inicio de gestión de calles
Route::get('/calles/maxid','Sectores\CalleController@maxId');
//Ruta página de inicio de gestión de calles
Route::post('/calles/gestion/guardarcalle/{idbarrio}','Sectores\CalleController@postCrearCalle');
//Ruta página de inicio de gestión de calles
Route::post('/calles/gestion/actualizarcalle/{idcalle}','Sectores\CalleController@postActualizarCalle');
//Ruta página de inicio de gestión de calles
Route::Delete('/calles/gestion/eliminarcalle/{idcalle}','Sectores\CalleController@destroy');

/*------------------------------------Raidel------------------------------------------------*/

/*===================================Solicitud=================================================*/

//Peticion para obtener el listado de solicitudes
Route::get('solicitud/getSolicitudes', 'Solicitud\SolicitudController@getSolicitudes');

Route::get('solicitud/getByFilter/{filter}', 'Solicitud\SolicitudController@getByFilter');

Route::put('solicitud/updateSolicitudOtro/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudOtro');

Route::put('solicitud/updateSolicitudMantenimiento/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudMantenimiento');

Route::put('solicitud/updateSolicitudSetName/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudSetName');

Route::put('solicitud/updateSolicitudServicio/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudServicio');

/*Route::get('solicitud/getSolicitudOtro/{idsolicitud}', 'Solicitud\SolicitudController@getSolicitudOtro');

Route::get('solicitud/getSolicitudRiego/{idsolicitud}', 'Solicitud\SolicitudController@getSolicitudRiego');

Route::get('solicitud/getSolicitudSetN/{idsolicitud}', 'Solicitud\SolicitudController@getSolicitudSetN');

Route::get('solicitud/getSolicitudFraccion/{idsolicitud}', 'Solicitud\SolicitudController@getSolicitudFraccion');

Route::get('solicitud/getIdentifyCliente/{idcliente}', 'Solicitud\SolicitudController@getIdentifyCliente');



Route::put('solicitud/processSolicitudSetName/{idsolicitud}', 'Solicitud\SolicitudController@processSolicitudSetName');

Route::put('solicitud/processSolicitudFraccion/{idsolicitud}', 'Solicitud\SolicitudController@processSolicitudFraccion');



Route::put('solicitud/updateSolicitudRiego/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudRiego');

Route::put('solicitud/updateSolicitudSetName/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudSetName');

Route::put('solicitud/updateSolicitudFraccion/{idsolicitud}', 'Solicitud\SolicitudController@updateSolicitudFraccion');*/

//Resource, atiende peticiones REST generales: [GET|POST|PUT|DELETE] hacia Solicitud
Route::resource('/solicitud', 'Solicitud\SolicitudController');

/*===================================Módulo Lectura===========================================*/

Route::get('nuevaLectura/getInfo/{filter}', 'Lecturas\LecturaController@getInfo');

Route::get('nuevaLectura/exportToPDF/{type}/{data}', 'Lecturas\LecturaController@exportToPDF');

//Ruta devuelve el ultimo ID + 1
Route::get('nuevaLectura/lastId', 'Lecturas\LecturaController@getLastID');
//Ruta devuelve todos los rubros
Route::get('nuevaLectura/getRubros', 'Lecturas\LecturaController@getRubros');
//Ruta devuelve los valores de los rublos en dependencia del suministro, consumo y tarifa
Route::get('nuevaLectura/getRubros/{consumo}/{tarifa}/{numerosuministro}', 'Lecturas\LecturaController@getRubrosValue');
//Resource, atiende peticiones REST generales: [GET|POST|PUT|DELETE] hacia Lectura
Route::resource('nuevaLectura', 'Lecturas\LecturaController');
//Ruta devuelve las lecturas
Route::get('verLectura/getLecturas', 'Lecturas\ViewLecturaController@getLecturas');
//Ruta devuelve los barrios
Route::get('verLectura/getBarrios', 'Lecturas\ViewLecturaController@getBarrios');
//Ruta devuelve las calles
Route::get('verLectura/getCalles/{idbarrio}', 'Lecturas\ViewLecturaController@getCalles');
//Ruta devuelve las lecturas por filtro
Route::get('verLectura/getByFilter/{filters}', 'Lecturas\ViewLecturaController@getByFilter');
//Ruta para actualizar los campos de lectura actual y observacion en cada lectura
Route::put('verLectura/update/{request}', 'Lecturas\ViewLecturaController@update');
//Resource, atiende peticiones REST generales: [GET|POST|PUT|DELETE] hacia ViewLectura
Route::resource('verLectura', 'Lecturas\ViewLecturaController');



/*------------------------------------Yamilka------------------------------------------------*/

/*===================================Módulo Nomina===========================================*/

Route::get('cargo/getCargos', 'Nomina\CargoController@getCargos');
Route::get('cargo/getCargoByID/{id}', 'Nomina\CargoController@getCargoByID');
Route::resource('/cargo', 'Nomina\CargoController');


//Ruta devuelve todos los empleados
Route::get('empleado/getEmployees', 'Nomina\EmpleadoController@getEmployees');
//Ruta devuelve todos los cargos
Route::get('empleado/getAllPositions', 'Nomina\EmpleadoController@getAllPositions');

Route::post('empleado/updateEmpleado/{id}', 'Nomina\EmpleadoController@updateEmpleado');

Route::resource('/empleado', 'Nomina\EmpleadoController');


Route::get('cuentaspagarcliente/getAll', 'Cuentas\CuentasPorPagarClientesController@getAll');
Route::get('cuentaspagarcliente/getByFilter/{filter}', 'Cuentas\CuentasPorPagarClientesController@getByFilter');

Route::post('cuentaspagarcliente/ingresarcuenta', 'Cuentas\CuentasPorPagarClientesController@ingresarCuenta');
Route::resource('cuentaspagarcliente', 'Cuentas\CuentasPorPagarClientesController');

Route::get('cuentascobrarcliente/getAll', 'Cuentas\CuentasPorCobrarSuministroController@getAll');
Route::get('cuentascobrarcliente/getByFilter/{filter}', 'Cuentas\CuentasPorCobrarSuministroController@getByFilter');
Route::post('cuentascobrarcliente/ingresarcuenta', 'Cuentas\CuentasPorCobrarSuministroController@ingresarCuenta');

Route::resource('cuentascobrarcliente', 'Cuentas\CuentasPorCobrarSuministroController');


/*===================================Módulo Cliente===========================================*/

Route::get('cliente/getTarifas', 'Clientes\ClienteController@getTarifas');

Route::get('cliente/getBarrios', 'Clientes\ClienteController@getBarrios');

Route::get('cliente/getCalles/{idbarrio}', 'Clientes\ClienteController@getCalles');

Route::get('cliente/getDividendos', 'Clientes\ClienteController@getDividendos');

Route::get('cliente/getInfoMedidor', 'Clientes\ClienteController@getInfoMedidor');

Route::get('cliente/getSuministros/{codigocliente}', 'Clientes\ClienteController@getSuministros');

Route::get('cliente/getLastID/{table}', 'Clientes\ClienteController@getLastID');

Route::get('cliente/getClientes', 'Clientes\ClienteController@getClientes');

Route::get('cliente/getTipoCliente', 'Clientes\ClienteController@getTipoCliente');

Route::get('cliente/getServicios', 'Clientes\ClienteController@getServicios');

Route::get('cliente/getIdentifyClientes/{idcliente}', 'Clientes\ClienteController@getIdentifyClientes');

Route::get('cliente/getExistsSolicitudServicio/{codigocliente}', 'Clientes\ClienteController@getExistsSolicitudServicio');

Route::post('cliente/storeSolicitudSuministro', 'Clientes\ClienteController@storeSolicitudSuministro');

Route::post('cliente/storeSolicitudServicios', 'Clientes\ClienteController@storeSolicitudServicios');

Route::post('cliente/storeSolicitudCambioNombre', 'Clientes\ClienteController@storeSolicitudCambioNombre');

Route::post('cliente/storeSolicitudMantenimiento', 'Clientes\ClienteController@storeSolicitudMantenimiento');

Route::post('cliente/storeSolicitudOtro', 'Clientes\ClienteController@storeSolicitudOtro');

Route::put('cliente/processSolicitud/{idsolicitud}', 'Clientes\ClienteController@processSolicitud');

Route::put('cliente/updateSetNameSuministro/{numerosuministro}', 'Clientes\ClienteController@updateSetNameSuministro');

Route::resource('/cliente', 'Clientes\ClienteController');

/*===================================Módulo Sectorizacion===========================================*/

Route::get('barrio/getBarrios', 'Sectores\BarrioController@getBarrios');
Route::get('barrio/getParroquias', 'Sectores\BarrioController@getParroquias');
Route::get('barrio/getLastID', 'Sectores\BarrioController@getLastID');
Route::get('barrio/getBarrio', 'Sectores\BarrioController@getBarrios');
Route::get('calle/getLastID', 'Sectores\CalleController@getLastID');
Route::get('barrio/calles/{id}', 'Sectores\CalleController@getCallesById');
Route::get('barrio/calles/{id}', 'Sectores\CalleController@getCallesById');
Route::post('barrio/editar_calle', 'Sectores\CalleController@editar_calle');
Route::post('barrio/editar_Barrio', 'Sectores\BarrioController@editar_barrio');

Route::get('calle/getCalles', 'Sectores\CalleController@getCalles');
Route::get('calle/getBarrio', 'Sectores\CalleController@getBarrios');
Route::get('calle/getCallesByBarrio/{id}','Sectores\CalleController@getCallesById');
Route::get('calle/getLastID', 'Sectores\CalleController@getLastID');
Route::post('calle/editar_calle', 'Sectores\CalleController@editar_calle');

Route::resource('/barrio', 'Sectores\BarrioController');
Route::resource('/calle', 'Sectores\CalleController');

/*=============================Módulo Suministro====================================*/

Route::get('suministros/getsuministros', 'Suministros\SuministroController@getsuministros');
Route::get('suministros/suministroById/{id}','Suministros\SuministroController@suministroById');
Route::get('suministros/getAguapotable', 'Suministros\SuministroController@getAguapotable');
Route::get('suministros/getCalle', 'Suministros\SuministroController@getCalle');

Route::resource('/suministros', 'Suministros\SuministroController');

/*=============================Módulo Facturacion====================================*/

Route::get('factura/getCobroAgua', 'Facturas\FacturaController@getCobroAgua');
Route::get('factura/getServicios', 'Facturas\FacturaController@getServicios');
Route::get('factura/verifyPeriodo', 'Facturas\FacturaController@verifyPeriodo');
Route::get('factura/generate', 'Facturas\FacturaController@generate');
Route::get('factura/getServiciosXCobro/{id}', 'Facturas\FacturaController@getServiciosXCobro');


Route::resource('/factura', 'Facturas\FacturaController');

/*------------------------------------Kevin Tambien :-( ------------------------------------------------*/

/*=============================Módulo Solicitud Suministro====================================*/

/*
//---------SOLICITUDES-----------------------------------------------------------------------
Route::get('/suministros/solicitudes', function (){
	return view('Suministros/Solicitudes/index');
});
Route::get('suministros/espera',function (){
	return view('Suministros/Solicitudes/solicitudespera');
});
Route::get('suministros/espera/espera','Suministros\SolicitudController@getSolicitudEspera');

Route::get('suministros/solicitudes/solicitudes','Suministros\SolicitudController@index');

Route::get('suministros/solicitudes/{idSolicitud}','Suministros\SolicitudController@getSolicitud');

Route::post('suministros/solicitudes/nueva/solicitud','Suministros\SolicitudController@ingresarSolicitud');

Route::post('suministros/solicitudes/procesar/{idSolicitud}','Suministros\SolicitudController@procesarSolicitud');

Route::post('suministros/solicitudes/eliminar/{idSolicitud}','Suministros\SolicitudController@eliminarSolicitud');

Route::post('suministros/solicitudes/modificar/{idSolicitud}','Suministros\SolicitudController@modificarSolicitud');

//------SUMINISTROS-----------------------------------------------------------------------------

Route::get('/suministros', function (){
	return view('Suministros/index');
});

Route::get('suministros/suministros/{numeroSuministro}','Suministros\SuministroController@getSuministro');

Route::get('suministros/suministros/{numeroSuministro}','Suministros\SuministroController@getSuministro');

Route::get('suministros/suministros','Suministros\SuministroController@index');

Route::get('tarifas/tarifas','Tarifas\TarifaController@index');

Route::get('suministros/productos','Suministros\ProductoController@index');

Route::post('suministros/editar/{idsuministro}','Suministros\SuministroController@editarSuministro');

Route::post('suministros/nuevo','Suministros\SuministroController@ingresarSuministro');

*/
//-----CONFIGURACION--------------------------------------------------------------------------

Route::get('configuracion/configuracion','Configuraciones\ConfiguracionController@index');


/*=======================================================*/
/* GENERACIÓN PDF                                        */
/*=======================================================*/

Route::get('suministros/solicitudes/solicitudes/pdf/{idSolicitud}', 'Suministros\SolicitudController@generarPDF');

Route::get('recaudacion/cobroagua/cuentas/pdf/{numerocuenta}', 'Cuentas\CobroAguaController@generarPDF');