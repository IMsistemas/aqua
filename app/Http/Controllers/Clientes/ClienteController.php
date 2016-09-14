<?php
namespace App\Http\Controllers\Clientes;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Modelos\Clientes\Cliente;

class ClienteController extends Controller
{
    public function index()
	{		 
		return $clientes=Cliente::all();
	}
	public function store(Request $request)
	{
		$cliente= new Cliente;
		$cliente->documentoidentidad = $request->input('documentoidentidad');
		$cliente->fechaingreso = date("Y-m-d H:i:s");
		$cliente->nombre = $request->input('nombre');
		$cliente->apellido = $request->input('apellido');
		$cliente->telefonoprincipal = $request->input('telefonoprincipal');
		$cliente->telefonosecundario = $request->input('telefonosecundario');
		$cliente->celular = $request->input('celular');
		$cliente->direccion = $request->input('direccion');
		$cliente->correo = $request->input('correo');
		$cliente->save();
		return 'El Cliente fue creado exitosamente';
	}

	public function show($documentoidentidad)
	{
		return Cliente::find($documentoidentidad);
	}

	public function update(Request $request,$documentoidentidad)
	{
		$cliente = Cliente::find($documentoidentidad);
		$cliente->documentoidentidad = $request->input('documentoidentidad');
		$cliente->fechaingreso = $request->input('fechaingreso');
		$cliente->nombre = $request->input('nombre');
		$cliente->apellido = $request->input('apellido');
		$cliente->telefonoprincipal = $request->input('telefonoprincipal');
		$cliente->telefonosecundario = $request->input('telefonosecundario');
		$cliente->celular = $request->input('celular');
		$cliente->direccion = $request->input('direccion');
		$cliente->correo = $request->input('correo');

		$cliente->save();
		return "Se actualizo correctamente el cliente con CI || RUC ".$cliente->documentoidentidad;
	}
	public function destroy(Request $request)
	{
		$cliente = Cliente::find($request->input('documentoidentidad'));
		$cliente->delete();
		return "Cliente borrado correctamente".$request->input('documentoidentidad');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}
}
  