<?php

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Modelos\Sectores\Calle;
use App\Modelos\Suministros\Suministro;


class SuministroController extends Controller
{
    public function index(){
		return Suministro::with('cliente','tarifa','calle.barrio')->get();
	}

	public function getSuministro($numeroSuministro){
		return Suministro::with('cliente','tarifa','calle')->where('numerosuministro',$numeroSuministro)->get();
	}

	public function editarSuministro(Request $request, $numeroSuministro){
		$suministro = Suministro::find($numeroSuministro);
		$suministro->idtarifa = $request->input('tarifa.idtarifa');
		$suministro->idcalle = $request->input('calle.idcalle');
		$suministro->direccionsuministro = $request->input('direccionsuministro');
		$suministro->telefonosuministro = $request->input('telefonosuministro');
		$suministro->save();
		return "El Suministro fue actualizado exitosamente";

	}

	public function ingresarSuministro(Request $request){
		$suministro = new Suministro();
		$suministro->idtarifa = $request->input('tarifa.idtarifa');
		$suministro->idcalle = $request->input('calle.idcalle');
		$suministro->codigocliente = $request->input('cliente.codigocliente');
		$suministro->idproducto = 'PRO00001';
		$suministro->direccionsuministro = $request->input('direccionsuministro');
		$suministro->telefonosuministro = $request->input('telefonosuministro');
		$suministro->save();
	}

        
 }
       