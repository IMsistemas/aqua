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

	public function index(){
			return Solicitud::with('cliente')->get();
	}

	public function getSolicitud($idSolicitud){
		return Solicitud::with('cliente')->where('idsolicitud',$idSolicitud)->get();
	}
	public function getSolicitudEspera(){
		return Solicitud::with('cliente')->where('estaprocesada','false')->get();
	}

	public function ingresarSolicitud(Request $request){
		$solicitud = new Solicitud();
		$solicitud->documentoidentidad = $request->input('cliente.documentoidentidad');
        $solicitud->fechasolicitud = date("Y-m-d H:i:s");
        $solicitud->direccionsuministro = $request->input('direccionsuministro');
        $solicitud->telefonosuministro = $request->input('telefonosuministro');
        $solicitud->estaprocesada = false;
        $solicitud->save();
        return response()->json(['success' => true]);
	}

	public function modificarSolicitud(Request $request, $idSolicitud){
		dd($request);
		$solicitud = Solicitud::find($idSolicitud);
		$solicitud->fechasolicitud = date("Y-m-d H:i:s");
		$solicitud->direccionsuministro = $request->input('direccionsuministro');	
		$solicitud->telefonosuministro = $request->input('telefonosuministro');
		return response()->json(['success' => true]);
	}

	public function procesarSolicitud(Request $request, $idSolicitud){
		$solicitud = Solicitud::find($idSolicitud);	
		$solicitud->estaprocesada = true;
		$solicitud->save();
		return response()->json(['success' => true]);
	}

	public function eliminarSolicitud(Request $request, $idsolicitud){
		$solicitud = Solicitud::find($idsolicitud);
		$solicitud->delete();
	}
    
}
