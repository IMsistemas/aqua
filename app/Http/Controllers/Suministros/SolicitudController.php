<?php

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Suministros\Solicitud;
use App\Modelos\Clientes\Cliente;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SolicitudController extends Controller
{
	public function show($idSolicitud){
		if($id == "solCli"){
			$ultimo = Solicitud::max('idsolicitud');
			return $ultimo+1;
		}else{
			return Solicitud::with('cliente')->where('idsolicitud',$idSolicitud)->get();
		}
	}

	public function index(){
		return Solicitud::with('cliente')->get();
	}

	public function getSolicitud($idSolicitud){
		return Solicitud::with('cliente')->where('idsolicitud',$idSolicitud)->get();
	}
    
}
