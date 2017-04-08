<?php

namespace App\Http\Controllers\Solicitud;


use App\Modelos\Configuracion\ConfiguracionSystem;
//use App\Modelos\Configuraciones\Configuracion;
use App\Modelos\Cuentas\CatalogoItemSolicitudServicio;
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
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cliente = null;

        /*return Solicitud::with('cliente.persona')
            ->selectRaw(
                '*,
                (SELECT idsolicitudotro FROM solicitudotro WHERE solicitudotro.idsolicitud = solicitud.idsolicitud) AS solicitudotro,
                (SELECT idsolicitudcambionombre FROM solicitudcambionombre WHERE solicitudcambionombre.idsolicitud = solicitud.idsolicitud) AS solicitudcambionombre,
                (SELECT idsolicitudmantenimiento FROM solicitudmantenimiento WHERE solicitudmantenimiento.idsolicitud = solicitud.idsolicitud) AS solicitudmantenimiento,
                (SELECT idsolicitudsuministro FROM solicitudsuministro WHERE solicitudsuministro.idsolicitud = solicitud.idsolicitud) AS solicitudsuministro,
                (SELECT idsolicitudservicio FROM solicitudservicio WHERE solicitudservicio.idsolicitud = solicitud.idsolicitud) AS solicitudservicio'
            )
            ->orderBy('fechasolicitud', 'asc')->paginate(10);*/

        return Solicitud::join('cliente', 'solicitud.idcliente', '=', 'cliente.idcliente')
                            ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
                            ->selectRaw(
                                '*,
                                (SELECT idsolicitudotro FROM solicitudotro WHERE solicitudotro.idsolicitud = solicitud.idsolicitud) AS solicitudotro,
                                (SELECT idsolicitudcambionombre FROM solicitudcambionombre WHERE solicitudcambionombre.idsolicitud = solicitud.idsolicitud) AS solicitudcambionombre,
                                (SELECT idsolicitudmantenimiento FROM solicitudmantenimiento WHERE solicitudmantenimiento.idsolicitud = solicitud.idsolicitud) AS solicitudmantenimiento,
                                (SELECT idsolicitudsuministro FROM solicitudsuministro WHERE solicitudsuministro.idsolicitud = solicitud.idsolicitud) AS solicitudsuministro,
                                (SELECT rutapdf FROM solicitudsuministro WHERE solicitudsuministro.idsolicitud = solicitud.idsolicitud) AS rutapdf,
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

    public function getSolicitudServicio($id)
    {
        return CatalogoItemSolicitudServicio::with('cont_catalogitem')
                                                ->where('idsolicitudservicio', $id)->get();
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

        $search = $filter_view->search;

        $solicitud = Solicitud::join('cliente', 'solicitud.idcliente', '=', 'cliente.idcliente')
                                ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona');

        if ($filter_view->estado == 2) {
            $solicitud = $solicitud->where('estadoprocesada', false);
        } elseif ($filter_view->estado == 1) {
            $solicitud = $solicitud->where('estadoprocesada', true);
        }

        if ($filter_view->tipo == 1) {
            $solicitud = $solicitud->selectRaw('
                *, (SELECT idsolicitudotro FROM solicitudotro WHERE solicitudotro.idsolicitud = solicitud.idsolicitud) AS solicitudotro 
            ')->whereRaw('idsolicitud IN (SELECT idsolicitud FROM solicitudotro)');
        } elseif ($filter_view->tipo == 2) {
            $solicitud = $solicitud->selectRaw('
                *, (SELECT idsolicitudmantenimiento FROM solicitudmantenimiento WHERE solicitudmantenimiento.idsolicitud = solicitud.idsolicitud) AS solicitudmantenimiento 
            ')->whereRaw('idsolicitud IN (SELECT idsolicitud FROM solicitudmantenimiento)');
        } elseif ($filter_view->tipo == 3) {
            $solicitud = $solicitud->selectRaw('
                *, (SELECT idsolicitudcambionombre FROM solicitudcambionombre WHERE solicitudcambionombre.idsolicitud = solicitud.idsolicitud) AS solicitudcambionombre 
            ')->whereRaw('idsolicitud IN (SELECT idsolicitud FROM solicitudcambionombre)');
        } elseif ($filter_view->tipo == 4) {
            $solicitud = $solicitud->selectRaw('
                *, (SELECT idsolicitudservicio FROM solicitudservicio WHERE solicitudservicio.idsolicitud = solicitud.idsolicitud) AS solicitudservicio 
            ')->whereRaw('idsolicitud IN (SELECT idsolicitud FROM solicitudservicio)');
        } elseif ($filter_view->tipo == 5) {
            $solicitud = $solicitud->selectRaw('
                *, (SELECT idsolicitudsuministro FROM solicitudsuministro WHERE solicitudsuministro.idsolicitud = solicitud.idsolicitud) AS solicitudsuministro 
            ')->whereRaw('idsolicitud IN (SELECT idsolicitud FROM solicitudsuministro)');
        } else {
            $solicitud = $solicitud->selectRaw('
                *,
                (SELECT idsolicitudotro FROM solicitudotro WHERE solicitudotro.idsolicitud = solicitud.idsolicitud) AS solicitudotro,
                (SELECT idsolicitudcambionombre FROM solicitudcambionombre WHERE solicitudcambionombre.idsolicitud = solicitud.idsolicitud) AS solicitudcambionombre,
                (SELECT idsolicitudmantenimiento FROM solicitudmantenimiento WHERE solicitudmantenimiento.idsolicitud = solicitud.idsolicitud) AS solicitudmantenimiento,
                (SELECT idsolicitudsuministro FROM solicitudsuministro WHERE solicitudsuministro.idsolicitud = solicitud.idsolicitud) AS solicitudsuministro,
                (SELECT idsolicitudservicio FROM solicitudservicio WHERE solicitudservicio.idsolicitud = solicitud.idsolicitud) AS solicitudservicio
            ');
        }

        if ($search != null) {
            $solicitud->whereRaw("(persona.lastnamepersona LIKE '%" . $search . "%' OR persona.namepersona LIKE '%" . $search . "%')");
        }

        return $solicitud->orderBy('fechasolicitud', 'asc')->paginate(10);

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
        $list_services = $request->input('servicios');
        foreach ($list_services as $item) {
            if ($item['valor'] != 0 && $item['valor'] != '') {
                $object = CatalogoItemSolicitudServicio::where('idsolicitudservicio', $id)
                            ->where('idcatalogitem', $item['idserviciojunta']);
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
