<?php

namespace App\Http\Controllers\Clientes;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Modelos\Clientes\Cliente;

class ClienteController extends Controller
{
    public function index($documentoidentidad=null)
	{	
		if($documentoidentidad==null){		 
		return $clientes=Cliente::all();
		}else
		{
			return $this->show($documentoidentidad);
		}
	}
	public function store(Request $request)
	{
		$cliente=new Cliente;
		$cliente->documentoidentidad = $request->input('documentoidentidad');
		$cliente->fechaingreso = $request->input('fechaingreso');
		$cliente->nombre = $request->input('nombre');
		$cliente->apellido = $request->input('apellido');			
		$cliente->telefonoprincipal = $request->input('telefonoprincipal');
		$cliente->telefonosecundario = $request->input('telefonosecundario');
		$cliente->celular = $request->input('celular');
		$cliente->direccion= $request->input('direccion');
		$cliente->correo = $request->input('correo');
		$cliente->save();

		return 'El Cliente fue creado correctamente con cedula'.$cliente->documentoidentidad;
	}

	public function show($documentoidentidad)
	{
		return Cliente::find($documentoidentidad);
	}

	public function update(Request $request,$documentoidentidad)
	{
		$cliente = Cliente::find($request->input('documentoidentidad'));
		$cliente->fechaingreso = $request->input('fechaingreso');
		$cliente->nombre = $request->input('nombre');
		$cliente->apellido = $request->input('apellido');
		$cliente->telefonoprincipal = $request->input('telefonoprincipal');
		$cliente->telefonosecundario = $request->input('telefonosecundario');
		$cliente->celular = $request->input('celular');
		$cliente->direccion = $request->input('direccion');
		$cliente->correo = $request->input('correo');

		$cliente->save();
		return "Se actualizo correctamente".$cliente->documentoidentidad;
	}
	public function destroy(Request $request)
	{
		$cliente = Cliente::find($request->get('documentoidentidad'));
		$cliente->delete();
		return "Cliente borrado correctamente".$request->get('documentoidentidad');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}
}
