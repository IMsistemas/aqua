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
		return Suministro::with('cliente','tarifa','calle')->get();
	}

	public function getSuministro($numerosuministro){
		return Suministro::with('cliente','tarifa','calle')->where('numerosuministro',$numerosuministro)->get();
	}

	public function ingresarSuministro(Request $request){
		$suministro = new Suministro();
		$suministro->idtarifa = $request->input('tarifa.idtarifa');
		$suministro->idcalle = $request->input('calle.idcalle');
		$suministro->documentoidentidad = $request->input('cliente.documentoidentidad');
		$suministro->idproducto = $request->input('producto.idproducto');
		$suministro->direccionsuministro = $request->input('direccionsuministro');
		$suministro->telefonosuministro = $request->input('telefonosuministro');
		$suministro->save();
	}

        
 }
       