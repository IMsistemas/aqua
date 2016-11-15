<?php

namespace App\Http\Controllers\Solicitud;


use App\Modelos\Servicios\ServiciosCliente;
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
        $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio', 'suministro.cuentaporcobrarsuministro')
                                                        ->orderBy('fechasolicitud', 'desc')->get();

        $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')->get();

        $solicitudsetname = SolicitudCambioNombre::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                                                        ->orderBy('fechasolicitud', 'desc')->get();

        $solicitudservicio = SolicitudServicio::with('cliente.tipocliente', 'cliente.servicioscliente.serviciojunta')
                                                        ->orderBy('fechasolicitud', 'desc')->get();

        $solicitudmantenim = SolicitudMantenimiento::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                                                        ->orderBy('fechasolicitud', 'desc')->get();

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
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio')
                                        ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 4){
                $solicitudservicio = SolicitudServicio::with('cliente.tipocliente', 'cliente.servicioscliente.serviciojunta')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 3){
                $solicitudsetname = SolicitudCambioNombre::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 2){
                $solicitudmantenim = SolicitudMantenimiento::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 1){
                $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            } else {
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudsetname = SolicitudCambioNombre::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudservicio = SolicitudServicio::with('cliente.tipocliente', 'cliente.servicioscliente.serviciojunta')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
                $solicitudmantenim = SolicitudMantenimiento::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->where('estaprocesada', $estado)->orderBy('fechasolicitud', 'desc')->get();
            }

        } else {
            if ($filter_view->tipo == 5){
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 4){
                $solicitudservicio = SolicitudServicio::with('cliente.tipocliente', 'cliente.servicioscliente.serviciojunta')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 3){
                $solicitudsetname = SolicitudCambioNombre::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 2){
                $solicitudsetname = SolicitudMantenimiento::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else if ($filter_view->tipo == 1){
                $solicitudotro = SolicitudOtro::with('cliente')->orderBy('fechasolicitud', 'desc')
                    ->orderBy('fechasolicitud', 'desc')->get();
            } else {
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio')
                                                                ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudotro = SolicitudOtro::with('cliente')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudsetname = SolicitudCambioNombre::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudservicio = SolicitudServicio::with('cliente.tipocliente', 'cliente.servicioscliente.serviciojunta')
                    ->orderBy('fechasolicitud', 'desc')->get();
                $solicitudmantenim = SolicitudMantenimiento::with('cliente', 'suministro.calle.barrio', 'suministro.aguapotable')
                    ->orderBy('fechasolicitud', 'desc')->get();
            }
        }

        return response()->json([
            'suministro' => $solicitudsuministro, 'otro' => $solicitudotro,
            'setname' => $solicitudsetname, 'servicio' => $solicitudservicio,
            'mantenimiento' => $solicitudmantenim
        ]);
    }

    public function updateSolicitudOtro(Request $request, $id)
    {
        $solicitud = SolicitudOtro::find($id);
        $solicitud->descripcion = $request->input('observacion');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function updateSolicitudMantenimiento(Request $request, $id)
    {
        $solicitud = SolicitudMantenimiento::find($id);
        $solicitud->numerosuministro = $request->input('numerosuministro');
        $solicitud->observacion = $request->input('observacion');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function updateSolicitudSetName(Request $request, $id)
    {
        $solicitud = SolicitudCambioNombre::find($id);
        $solicitud->numerosuministro = $request->input('numerosuministro');
        $solicitud->codigoclientenuevo = $request->input('codigoclientenuevo');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    public function updateSolicitudServicio(Request $request, $id)
    {
        $solicitud = SolicitudServicio::find($id);
        $list_services = $request->input('servicios');
        foreach ($list_services as $item) {
            if ($item['valor'] != 0 && $item['valor'] != '') {
                $object = ServiciosCliente::where('codigocliente', $solicitud->codigocliente)
                                            ->where('idserviciojunta', $item['idserviciojunta']);
                if ($object->update(['valor' => $item['valor']]) == false){
                    return response()->json(['success' => false]);
                }
            }
        }
        return response()->json(['success' => true]);
    }

}
