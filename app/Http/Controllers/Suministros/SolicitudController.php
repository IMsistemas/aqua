<?php 

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Suministros\Solicitud;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modelos\Cuentas\CuentasPorCobrarSuministro;

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


	/*==========================================================================================================*/
	/*    PDF SOLICITUD                                                                                         */
	/*==========================================================================================================*/

	public function generarPDF($idSolicitud){
		$solicitud = Solicitud::find($idSolicitud);
		$documentoidentidad = $solicitud->documentoidentidad;
		$idSolicitud = $solicitud->idsolicitud;

		$cuentaPorCobrar = CuentasPorCobrarSuministro::where('idsolicitud','=',$idSolicitud)->get();

		$numeroSuministro = $cuentaPorCobrar[0]->numerosuministro;

		$suministro = Suministro::find($numeroSuministro);
		$idTarifa = $suministro->idtarifa;
		$tarifa = Tarifa::find($idTarifa);

		$cliente = Cliente::find($documentoidentidad);

		$data =  [
            'idsolicitud'           => $solicitud->idsolicitud ,
        	'fechasolicitud'        => $solicitud->fechasolicitud,
        	'direccionsuministro'   => $solicitud->direccionsuministro,
        	'telefonosuministro'    => $solicitud->telefonosuministro,

        	'documentoidentidad'    => $solicitud->documentoidentidad,
        	'cliente'               => $cliente->apellido." ".$cliente->nombre,

        	'numerosuministro'      => $suministro->numerosuministro,
        	'tarifa'                => $tarifa->nombretarifa,[0],

        	'dividendos'            => $cuentaPorCobrar[0]->dividendos,
			'pagototal'             => $cuentaPorCobrar[0]->pagototal,
			'pagoporcadadividendo'  => $cuentaPorCobrar[0]->pagoporcadadividendo,
			'cuotainicial'          => $cuentaPorCobrar[0]->cuotainicial
        	

        

        ];

           //dd($cuentaPorCobrar);
        $view =  \View::make('invoice', compact('data'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('invoice');

    }
 
    public function getData(){

    	$solicitud = Solicitud::with('cliente')->where('idsolicitud',6)->get();

       dd($solicitud);
    }
    
}
