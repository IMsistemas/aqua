<?php

namespace App\Http\Controllers\Solicitud;


use App\Modelos\Configuracion\ConfiguracionSystem;
//use App\Modelos\Configuraciones\Configuracion;
use App\Modelos\Servicios\ServiciosCliente;
use App\Modelos\Solicitud\Solicitud;
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


    /**
     * Obtener la configuracion del sistema
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTasaInteres()
    {
        return ConfiguracionSystem::where('optionname', 'AYORA_TASAINTERES')->get();
    }

    /**
     * Obtener todas las solicitudes independientemente de su tipo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitudes(Request $request)
    {
        /*$solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio', 'suministro.cuentaporcobrarsuministro')
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
        ]);*/

        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cliente = null;

        return Solicitud::with('cliente.persona')
            ->selectRaw(
                '*,
                (SELECT idsolicitudotro FROM solicitudotro WHERE solicitudotro.idsolicitud = solicitud.idsolicitud) AS solicitudotro,
                (SELECT idsolicitudcambionombre FROM solicitudcambionombre WHERE solicitudcambionombre.idsolicitud = solicitud.idsolicitud) AS solicitudcambionombre,
                (SELECT idsolicitudmantenimiento FROM solicitudmantenimiento WHERE solicitudmantenimiento.idsolicitud = solicitud.idsolicitud) AS solicitudmantenimiento,
                (SELECT idsolicitudsuministro FROM solicitudsuministro WHERE solicitudsuministro.idsolicitud = solicitud.idsolicitud) AS solicitudsuministro,
                (SELECT idsolicitudservicio FROM solicitudservicio WHERE solicitudservicio.idsolicitud = solicitud.idsolicitud) AS solicitudservicio'
            )
            ->orderBy('fechasolicitud', 'asc')->paginate(10);

    }


    public function getSolicitudOtro($id)
    {
        return SolicitudOtro::where('idsolicitudotro', $id)->get();
    }


    public function getSolicitudMantenimiento($id)
    {
        return SolicitudMantenimiento::with('suministro.tarifaaguapotable', 'suministro.calle.barrio')
                                        ->where('idsolicitudmantenimiento', $id)->get();
    }


    public function getSolicitudSetN($id)
    {
        return SolicitudCambioNombre::with(
            'suministro.tarifaaguapotable', 'suministro.calle.barrio', 'cliente.persona'
            )->where('idsolicitudcambionombre', $id)->get();
    }

    public function getSolicitudSuministro($id)
    {
        return SolicitudSuministro::with('suministro.tarifaaguapotable', 'suministro.calle.barrio')
                                        ->where('idsolicitudsuministro', $id)->get();
    }





    /**
     * Obtener mediante filtros de busqueda, las solicitudes que correspondan
     *
     * @param $filter
     * @return \Illuminate\Http\JsonResponse
     */
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
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio', 'suministro.cuentaporcobrarsuministro')
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
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio', 'suministro.cuentaporcobrarsuministro')
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
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio', 'suministro.cuentaporcobrarsuministro')
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
                $solicitudsuministro = SolicitudSuministro::with('cliente', 'suministro.aguapotable', 'suministro.calle.barrio', 'suministro.cuentaporcobrarsuministro')
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

    /**
     * Actualizar Tipo Otras Solicitudes
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSolicitudOtro(Request $request, $id)
    {
        $solicitud = SolicitudOtro::find($id);
        $solicitud->descripcion = $request->input('observacion');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    /**
     * Actualizar Tipo Solicitud de Mantenimiento
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSolicitudMantenimiento(Request $request, $id)
    {
        $solicitud = SolicitudMantenimiento::find($id);
        $solicitud->idsuministro = $request->input('numerosuministro');
        $solicitud->observacion = $request->input('observacion');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    /**
     * Actualizar Tipo de Solicitud de Cambio de Nombre
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSolicitudSetName(Request $request, $id)
    {
        $solicitud = SolicitudCambioNombre::find($id);
        $solicitud->idsuministro = $request->input('numerosuministro');
        $solicitud->idcliente = $request->input('codigoclientenuevo');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    /**
     * Actualizar Tipo de Solicitud de Servicios
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Actualizar Tipo de Solicitud de Suministros
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSolicitudSuministro(Request $request, $id)
    {
        $solicitud = SolicitudSuministro::find($id);
        $solicitud->direccioninstalacion = $request->input('direccionsuministro');
        $solicitud->telefonosuminstro = $request->input('telefonosuministro');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

}
