<?php

namespace App\Http\Controllers\Solicitud;


use App\Modelos\Solicitud\SolicitudCambioNombre;
use App\Modelos\Solicitud\SolicitudMantenimiento;
use App\Modelos\Solicitud\SolicitudOtro;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Solicitud\SolicitudSuministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Solicitud/index');
    }

    public function getSolicitudes()
    {
        $solicitudsuministro = SolicitudSuministro::with('cliente')->orderBy('fechasolicitud', 'desc')->get();
        $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')->get();
        $solicitudsetname = SolicitudCambioNombre::with('cliente')->orderBy('fechasolicitud', 'desc')->get();
        $solicitudservicio = SolicitudServicio::with('cliente')->orderBy('fechasolicitud', 'desc')->get();
        $solicitudmantenim = SolicitudMantenimiento::with('cliente')->orderBy('fechasolicitud', 'desc')->get();

        return response()->json([
            'suministro' => $solicitudsuministro, 'otro' => $solicitudotro,
            'setname' => $solicitudsetname, 'servicio' => $solicitudservicio,
            'mantenimiento' => $solicitudmantenim
        ]);
    }


    public function getByFilter($filter)
    {
        $filter_view = json_decode($filter);

        $solicitudsuministro = [];
        $solicitudsetname = [];
        $solicitudservicio = [];
        $solicitudotro = [];
        $solicitudmantenim = [];

        if ($filter_view->estado != 3) {

            $estado = true;
            if ($filter_view->estado == 2) $estado = false;

            if ($filter_view->tipo == 5){
                $solicitudsuministro = SolicitudSuministro::with('cliente')
                                        ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 4){
                $solicitudservicio = SolicitudServicio::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 3){
                $solicitudsetname = SolicitudCambioNombre::with('cliente')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 2){
                $solicitudmantenim = SolicitudMantenimiento::with('cliente')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 1){
                $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else {
                $solicitudsuministro = SolicitudSuministro::with('cliente')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudsetname = SolicitudCambioNombre::with('cliente')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudservicio = SolicitudServicio::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudmantenim = SolicitudMantenimiento::with('cliente')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            }

        } else {
            if ($filter_view->tipo == 5){
                $solicitudsuministro = SolicitudSuministro::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 4){
                $solicitudservicio = SolicitudServicio::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 3){
                $solicitudsetname = SolicitudCambioNombre::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 2){
                $solicitudsetname = SolicitudMantenimiento::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 1){
                $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else {
                $solicitudsuministro = SolicitudSuministro::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudotro = SolicitudOtro::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudsetname = SolicitudCambioNombre::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudservicio = SolicitudServicio::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudmantenim = SolicitudMantenimiento::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
            }
        }

        return response()->json([
            'suministro' => $solicitudsuministro, 'otro' => $solicitudotro,
            'setname' => $solicitudsetname, 'servicio' => $solicitudservicio,
            'mantenimiento' => $solicitudmantenim
        ]);
    }
/*
    public function getIdentifyCliente($idcliente)
    {
        return Cliente::where('codigocliente', $idcliente)->get();
    }

    public function getSolicitudOtro($idsolicitud)
    {
        return SolicitudOtro::with('cliente')->where('idsolicitud', $idsolicitud)->get();
    }

    public function getSolicitudRiego($idsolicitud)
    {
        return SolicitudRiego::with('cliente', 'terreno.derivacion.canal.calle')->where('idsolicitud', $idsolicitud)->get();
    }

    public function getSolicitudSetN($idsolicitud)
    {
        return SolicitudCambioNombre::with('cliente', 'terreno.derivacion.canal.calle.barrio', 'terreno.cultivo')
                                    ->where('idsolicitudcambionombre', $idsolicitud)->get();
    }

    public function getSolicitudFraccion($idsolicitud)
    {
        return SolicitudReparticion::with('cliente', 'terreno.derivacion.canal.calle.barrio', 'terreno.cultivo')
                                    ->where('idsolicitudreparticion', $idsolicitud)->get();
    }


    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::find($id);

        $solicitud->estaprocesada = true;
        $solicitud->fechaprocesada = date('Y-m-d');

        $solicitud->save();

        return response()->json(['success' => true]);
    }

    public function updateSolicitudOtro(Request $request, $id)
    {

        $solicitud = SolicitudOtro::find($id);

        $solicitud->fechasolicitud = $request->input('fecha_solicitud');
        $solicitud->descripcion = $request->input('observacion');
        $result = $solicitud->save();

        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function updateSolicitudRiego(Request $request, $id)
    {
        $solicitudriego = SolicitudRiego::find($id);
        $solicitudriego->fechasolicitud = $request->input('fecha_solicitud');
        $solicitudriego->observacion = $request->input('observacion');
        $result = $solicitudriego->save();

        $terreno = Terreno::find($solicitudriego->idterreno);
        $terreno->idcultivo = $request->input('idcultivo');
        $terreno->idtarifa = $request->input('idtarifa');
        $terreno->idderivacion = $request->input('idderivacion');
        $terreno->fechacreacion = $request->input('fecha_solicitud');
        $terreno->caudal = $request->input('caudal');
        $terreno->area = $request->input('area');
        $terreno->valoranual = $request->input('valoranual');
        $terreno->observacion = $request->input('observacion');

        $terreno->save();

        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function updateSolicitudSetName(Request $request, $id)
    {
        $solicitudsetname = SolicitudCambioNombre::find($id);
        $solicitudsetname->codigocliente = $request->input('codigocliente_old');
        $solicitudsetname->codigonuevocliente = $request->input('codigocliente_new');
        $solicitudsetname->idterreno = $request->input('idterreno');
        $solicitudsetname->fechasolicitud = $request->input('fecha_solicitud');
        $solicitudsetname->observacion = $request->input('observacion');

        $result = $solicitudsetname->save();

        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function updateSolicitudFraccion(Request $request, $id)
    {
        $solicitud = SolicitudReparticion::find($id);
        $solicitud->codigocliente = $request->input('codigocliente_old');
        $solicitud->codigonuevocliente = $request->input('codigocliente_new');
        $solicitud->idterreno = $request->input('idterreno');
        $solicitud->fechasolicitud = $request->input('fecha_solicitud');
        $solicitud->observacion = $request->input('observacion');
        $solicitud->nuevaarea = $request->input('area');

        $result = $solicitud->save();

        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function processSolicitudSetName(Request $request, $id)
    {
        $solicitud = SolicitudCambioNombre::find($id);

        $terreno = Terreno::find($solicitud->idterreno);
        $terreno->codigocliente = $solicitud->codigonuevocliente;
        $terreno->save();

        $solicitud->estaprocesada = true;
        $solicitud->fechaprocesada = date('Y-m-d');
        $solicitud->save();

        return response()->json(['success' => true]);
    }

    public function processSolicitudFraccion(Request $request, $id)
    {
        $solicitud = SolicitudReparticion::find($id);

        $arriendo = new ClienteArriendo();
        $arriendo->codigoclientearrendador = $solicitud->codigonuevocliente;
        $arriendo->codigoclientearrendatario = $solicitud->codigocliente;
        $arriendo->idterreno = $solicitud->idterreno;
        $arriendo->areaarriendo = $solicitud->nuevaarea;
        $arriendo->save();

        $solicitud->estaprocesada = true;
        $solicitud->fechaprocesada = date('Y-m-d');
        $solicitud->save();
        return response()->json(['success' => true]);
    }

*/
}
