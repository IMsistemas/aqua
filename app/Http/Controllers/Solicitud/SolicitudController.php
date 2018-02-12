<?php

namespace App\Http\Controllers\Solicitud;


use App\Modelos\Configuracion\ConfiguracionSystem;
//use App\Modelos\Configuraciones\Configuracion;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Cuentas\CatalogoItemSolicitudServicio;
use App\Modelos\Cuentas\CobroCliente;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;
use App\Modelos\Servicios\ServiciosCliente;
use App\Modelos\Solicitud\Solicitud;
use App\Modelos\Solicitud\SolicitudCambioNombre;
use App\Modelos\Solicitud\SolicitudMantenimiento;
use App\Modelos\Solicitud\SolicitudOtro;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Solicitud\SolicitudSuministro;
use App\Modelos\SRI\SRI_Establecimiento;
use App\Modelos\Suministros\Producto;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Suministros\SuministroCatalogItem;
use App\Modelos\Tarifas\TarifaAguaPotable;
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




    /*
     * INICIO SECCION DE FUNCIONES REFERENTES A LAS SOLICITUDES DE LOS CLIENTES-----------------------------------------
     */


    public function getSuministroByClient($idcliente)
    {
        return Solicitud::whereRaw('idsolicitud IN (SELECT idsolicitud FROM solicitudsuministro)')
            ->where('idcliente', $idcliente)
            ->where('estadoprocesada', true)
            ->count();
    }

    /**
     * Obtener todos los clientes diferentes al id por parametro
     *
     * @param $text
     * @return mixed
     */
    public function getIdentifyClientes($text)
    {

        return Persona::with('cliente')->whereRaw("numdocidentific::text ILIKE '%" . $text . "%'")
            //->whereRaw('idpersona NOT IN (SELECT idpersona FROM cliente)')
            ->get();

        /*return Cliente::where('documentoidentidad', 'LIKE', '%' . $text . '%')
                        ->orderBy('documentoidentidad', 'asc')->get();*/
    }

    /**
     * Obtener la informacion de un cliente en especifico
     *
     * @param $idcliente
     * @return mixed
     */
    public function getInfoCliente($idcliente)
    {
        return Cliente::where('idcliente', $idcliente)->get();
    }

    /**
     * Obtener el ultimo id insertado y devolver el proximo de la tabla pasada por parametro
     *
     * @param $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastID($table)
    {
        $max = null;

        if ($table == 'solicitudservicio') {
            $max = SolicitudServicio::max('idsolicitudservicio');
        }  else if ($table == 'solicitudotro') {
            $max = SolicitudOtro::max('idsolicitudotro');
        } else if ($table == 'solsuministro') {
            $max = SolicitudSuministro::max('idsolicitudsuministro');
        } else if ($table == 'suministro') {
            $max = Suministro::max('numconexion');
        } else if ($table == 'solicitudcambionombre') {
            $max = SolicitudCambioNombre::max('idsolicitudcambionombre');
        } else if ($table == 'solicitudmantenimiento') {
            $max = SolicitudMantenimiento::max('idsolicitudmantenimiento');
        }

        if ($max != null){
            $max += 1;
        } else $max = 1;

        return response()->json(['id' => $max]);
    }

    /**
     * Obtener todos los servicios ordenados ascendentemente
     *
     * @return mixed
     */
    public function getServicios()
    {
        return Cont_CatalogItem::where('idclaseitem', 2)->orderBy('nombreproducto', 'asc')->get();
    }

    /**
     * Obtener todas las tarifas
     *
     * @return mixed
     */
    public function getTarifas()
    {
        return TarifaAguaPotable::orderBy('nametarifaaguapotable', 'asc')->get();
    }

    /**
     * Obtener todos los barrios ordenadados ascedentemente
     *
     * @return mixed
     */
    public function getBarrios()
    {

        return Barrio::orderBy('namebarrio', 'asc')->get();
    }

    /**
     * Obtener las calles de un barrio ordenadas ascendentemente
     *
     * @param $idbarrio
     * @return mixed
     */
    public function getCalles($idbarrio)
    {
        return Calle::where('idbarrio', $idbarrio)->orderBy('namecalle', 'asc')->get();
    }

    /**
     * Obtener la configuracion del sistema
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDividendos()
    {
        return ConfiguracionSystem::where('optionname', 'AYORA_DIVIDENDO')->get();
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
     * Obtener la informacion del producto con ID 1
     *
     * @return mixed
     */
    public function getInfoMedidor()
    {
        return Producto::where('idproducto', 1)->get();
    }

    /**
     * Obtener los suministros de un cliente en especifico
     *
     * @param $codigocliente
     * @return mixed
     */
    public function getSuministros($codigocliente)
    {
        return Suministro::with('calle.barrio', 'tarifaaguapotable')
            ->where('idcliente', $codigocliente)
            ->orderBy('direccionsumnistro', 'asc')->get();
    }

    /**
     * Verificar si existe alguna solicitud de servicio para el cliente solicitado
     *
     * @param $codigocliente
     * @return mixed
     */
    public function getExistsSolicitudServicio($codigocliente)
    {
        $count = SolicitudServicio::join('solicitud', 'solicitud.idsolicitud', '=', 'solicitudservicio.idsolicitud')
            ->whereRaw('solicitud.idcliente = ' . $codigocliente)->count();
        if ($count >= 1) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Almacenar los datos de solicitud de suministro
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSolicitudSuministro(Request $request)
    {
        $fecha_actual = date('Y-m-d');

        $solicitud = new Solicitud();
        $solicitud->idcliente = $request->input('codigocliente');
        $solicitud->fechasolicitud = $fecha_actual;
        $solicitud->estadoprocesada = false;

        if ($solicitud->save()) {

            $solicitudsuministro = new SolicitudSuministro();
            $solicitudsuministro->idsolicitud = $solicitud->idsolicitud;
            $solicitudsuministro->direccioninstalacion = $request->input('direccionsuministro');
            $solicitudsuministro->telefonosuminstro = $request->input('telefonosuministro');

            if ($solicitudsuministro->save() != false) {

                $fecha_actual = date('Y-m-d');

                $suministro = new Suministro();
                $suministro->idcalle = $request->input('idcalle');
                $suministro->idcliente = $request->input('codigocliente');
                $suministro->idtarifaaguapotable = $request->input('idtarifa');
                $suministro->direccionsumnistro = $request->input('direccionsuministro');
                $suministro->telefonosuministro = $request->input('telefonosuministro');
                $suministro->fechainstalacion = $fecha_actual;

                $suministro->valoraguapotable = $request->input('agua_potable');
                $suministro->valoralcantarillado = $request->input('alcantarillado');
                $suministro->valorgarantia = $request->input('garantia');
                $suministro->valorcuotainicial = $request->input('cuota_inicial');
                $suministro->dividendocredito = $request->input('dividendos');

                $suministro->valortotalsuministro = $request->input('valor_partial');

                $suministro->formapago = $request->input('formapago');

                $numconexion = Suministro::max('numconexion');

                $suministro->numconexion = $numconexion + 1;


                if ($suministro->save()) {

                    $updateSolicitudSuministro = SolicitudSuministro::find($solicitudsuministro->idsolicitudsuministro);
                    $updateSolicitudSuministro->idsuministro = $suministro->idsuministro;
                    $updateSolicitudSuministro->save();

                    $cobrocliente = new CobroCliente();

                    $cobrocliente->idcatalogitem = 2;
                    $cobrocliente->valor = $request->input('garantia');
                    $cobrocliente->idcliente = $request->input('codigocliente');
                    $cobrocliente->save();

                    $dividendos = $request->input('dividendos');
                    $valor = $request->input('valor_partial') / $dividendos;

                    for ($i = 0; $i < $dividendos; $i++) {

                        $cobrocliente = new CobroCliente();

                        $cobrocliente->idcatalogitem = 1;
                        $cobrocliente->valor = $valor;
                        $cobrocliente->idcliente = $request->input('codigocliente');
                        $cobrocliente->save();

                    }


                    $o = new SuministroCatalogItem();
                    $o->idsuministro = $suministro->idsuministro;
                    $o->idcatalogitem = 1;
                    $o->valor = $request->input('valor_partial');

                    $o->save();

                    $oo = new SuministroCatalogItem();
                    $oo->idsuministro = $suministro->idsuministro;
                    $oo->idcatalogitem = 2;
                    $oo->valor = $request->input('garantia');

                    $oo->save();

                    $ooo = new SuministroCatalogItem();
                    $ooo->idsuministro = $suministro->idsuministro;
                    $ooo->idcatalogitem = 3;
                    $ooo->valor = $request->input('cuota_inicial');

                    $ooo->save();

                    /*$name = date('Ymd') . '_' . $suministro->idsuministro . '.pdf';

                    $url_pdf = 'uploads/pdf_suministros/' . $name;

                    $this->createPDF($request->input('data_to_pdf'), $url_pdf);*/

                    return response()->json(['success' => true, 'idsolicitud' => $solicitudsuministro->idsolicitudsuministro]);

                } else return response()->json(['success' => false]);


            } else return response()->json(['success' => false]);

        } else {

            return response()->json(['success' => false]);

        }
    }

    /**
     * Almacenar los datos de solicitud de Servicios
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSolicitudServicios(Request $request)
    {
        $fecha_actual = date('Y-m-d');

        $solicitud = new Solicitud();
        $solicitud->idcliente = $request->input('codigocliente');
        $solicitud->fechasolicitud = $fecha_actual;
        $solicitud->estadoprocesada = false;

        if ($solicitud->save() != false) {

            $solicitudservicio = new SolicitudServicio();
            $solicitudservicio->idsolicitud = $solicitud->idsolicitud;

            if ($solicitudservicio->save()) {

                $list_services = $request->input('servicios');

                foreach ($list_services as $item) {
                    if ($item['valor'] != 0 && $item['valor'] != '') {
                        $object = new CatalogoItemSolicitudServicio();
                        $object->idcatalogitem = $item['idserviciojunta'];
                        $object->idsolicitudservicio = $solicitudservicio->idsolicitudservicio;

                        if ($item['valor'] == '' || $item['valor'] == null) {
                            $object->valor = 0;
                        } else {
                            $object->valor = $item['valor'];
                        }

                        if ($object->save() == false) {
                            return response()->json(['success' => false]);
                        }
                    }
                }
            }
            return response()->json(['success' => true, 'idsolicitud' => $solicitud->idsolicitud]);
        } else return response()->json(['success' => false]);
    }

    /**
     * Almacenar los datos de solicitud de Cambio de Nombre
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSolicitudCambioNombre(Request $request)
    {
        $fecha_actual = date('Y-m-d');

        $solicitud = new Solicitud();
        $solicitud->idcliente = $request->input('codigocliente');
        $solicitud->fechasolicitud = $fecha_actual;
        $solicitud->estadoprocesada = false;

        if ($solicitud->save()) {

            $solicitud_cambio = new SolicitudCambioNombre();
            $solicitud_cambio->idsuministro = $request->input('numerosuministro');
            $solicitud_cambio->idcliente = $request->input('codigoclientenuevo');
            $solicitud_cambio->idsolicitud = $solicitud->idsolicitud;

            if ($solicitud_cambio->save()) {
                $max_idsolicitud = SolicitudCambioNombre::where('idsolicitudcambionombre', $solicitud_cambio->idsolicitudcambionombre)
                    ->get();
                return response()->json(['success' => true, 'idsolicitud' => $max_idsolicitud[0]->idsolicitud]);
            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);
    }

    /**
     * Actualizar cambio de cliente de suministro
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSetNameSuministro(Request $request, $id)
    {
        $suministro = Suministro::find($id);
        $suministro->idcliente = $request->input('codigoclientenuevo');

        if ($suministro->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Almacenar los datos de solicitud de Mantenimiento
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSolicitudMantenimiento(Request $request)
    {
        $fecha_actual = date('Y-m-d');

        $solicitud = new Solicitud();
        $solicitud->idcliente = $request->input('codigocliente');
        $solicitud->fechasolicitud = $fecha_actual;
        $solicitud->estadoprocesada = false;

        if ($solicitud->save()) {

            $solicitudMant = new SolicitudMantenimiento();
            $solicitudMant->idsuministro = $request->input('numerosuministro');
            $solicitudMant->observacion = $request->input('observacion');
            $solicitudMant->idsolicitud = $solicitud->idsolicitud;
            $solicitudMant->idcliente = $request->input('codigocliente');

            if ($solicitudMant->save() != false) {
                $max_idsolicitud = SolicitudMantenimiento::where('idsolicitudmantenimiento', $solicitudMant->idsolicitudmantenimiento)
                    ->get();
                return response()->json(['success' => true, 'idsolicitud' => $max_idsolicitud[0]->idsolicitud]);
            } else return response()->json(['success' => false]);

        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Almacenar los datos de Otras Solicitudes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSolicitudOtro(Request $request)
    {
        $fecha_actual = date('Y-m-d');

        $solicitud = new Solicitud();
        $solicitud->idcliente = $request->input('codigocliente');
        $solicitud->fechasolicitud = $fecha_actual;
        $solicitud->estadoprocesada = false;

        if ($solicitud->save()) {

            $solicitudriego = new SolicitudOtro();
            $solicitudriego->idsolicitud = $solicitud->idsolicitud;
            $solicitudriego->descripcion = $request->input('observacion');

            $result = $solicitudriego->save();

            $max_idsolicitud = SolicitudOtro::where('idsolicitudotro', $solicitudriego->idsolicitudotro)->get();

            return ($result) ? response()->json(['success' => true, 'idsolicitud' => $max_idsolicitud[0]->idsolicitud]) :
                response()->json(['success' => false]);

        } else {
            response()->json(['success' => false]);
        }



    }

    /**
     * Procesar las solicitudes
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function processSolicitud(Request $request, $id)
    {
        $solicitud = Solicitud::find($id);
        $solicitud->estadoprocesada = true;
        $solicitud->fechaprocesada = date('Y-m-d');
        $solicitud->save();

        return response()->json(['success' => true]);
    }

    /**
     * Procesar especificamente la Solicitud de Suministro
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function processSolicitudSuministro(Request $request, $id)
    {

        $fecha_actual = date('Y-m-d');

        $suministro = new Suministro();
        $suministro->idcalle = $request->input('idcalle');
        $suministro->idcliente = $request->input('codigocliente');
        $suministro->idtarifaaguapotable = $request->input('idtarifa');
        $suministro->direccionsumnistro = $request->input('direccionsuministro');
        $suministro->telefonosuministro = $request->input('telefonosuministro');
        $suministro->fechainstalacion = $fecha_actual;

        $suministro->valoraguapotable = $request->input('agua_potable');
        $suministro->valoralcantarillado = $request->input('alcantarillado');
        $suministro->valorgarantia = $request->input('garantia');
        $suministro->valorcuotainicial = $request->input('cuota_inicial');
        $suministro->dividendocredito = $request->input('dividendos');

        $suministro->valortotalsuministro = $request->input('valor_partial');

        $suministro->formapago = $request->input('formapago');

        $numconexion = Suministro::max('numconexion');

        $suministro->numconexion = $numconexion + 1;

        //$suministro->idcatalogitem = $request->input('idproducto');

        if ($suministro->save()) {

            $cobrocliente = new CobroCliente();

            $cobrocliente->idcatalogitem = 2;
            $cobrocliente->valor = $request->input('garantia');
            $cobrocliente->idcliente = $request->input('codigocliente');
            $cobrocliente->save();


            $dividendos = $request->input('dividendos');
            $valor = $request->input('valor_partial') / $dividendos;

            for ($i = 0; $i < $dividendos; $i++) {

                $cobrocliente = new CobroCliente();

                $cobrocliente->idcatalogitem = 1;
                $cobrocliente->valor = $valor;
                $cobrocliente->idcliente = $request->input('codigocliente');
                $cobrocliente->save();

            }


            $o = new SuministroCatalogItem();
            $o->idsuministro = $suministro->idsuministro;
            $o->idcatalogitem = 1;
            $o->valor = $request->input('valor_partial');

            $o->save();

            $oo = new SuministroCatalogItem();
            $oo->idsuministro = $suministro->idsuministro;
            $oo->idcatalogitem = 2;
            $oo->valor = $request->input('garantia');

            $oo->save();

            $ooo = new SuministroCatalogItem();
            $ooo->idsuministro = $suministro->idsuministro;
            $ooo->idcatalogitem = 3;
            $ooo->valor = $request->input('cuota_inicial');

            $ooo->save();

            $name = date('Ymd') . '_' . $suministro->idsuministro . '.pdf';

            $url_pdf = 'uploads/pdf_suministros/' . $name;

            $this->createPDF($request->input('data_to_pdf'), $url_pdf);

            $solicitudsuministro = SolicitudSuministro::find($id);

            $solicitudsuministro->idsuministro = $suministro->idsuministro;

            $solicitudsuministro->rutapdf = $url_pdf;

            if ($solicitudsuministro->save()) {

                $solicitud = Solicitud::find($solicitudsuministro->idsolicitud);
                $solicitud->estadoprocesada = true;
                $solicitud->fechaprocesada = date('Y-m-d');

                if ($solicitud->save()) {
                    /*$cxc = new CuentasPorCobrarSuministro();
                    $cxc->codigocliente = $request->input('codigocliente');
                    $cxc->numerosuministro = $suministro->numerosuministro;
                    $cxc->fecha = $fecha_actual;
                    $cxc->dividendos = $request->input('dividendos');
                    $cxc->pagoporcadadividendo = $request->input('valor');
                    $cxc->pagototal = $request->input('valor_partial');

                    if ($cxc->save() != false) {

                        if ($request->input('garantia') != '' && $request->input('garantia') != 0) {

                            $cxp_cliente = new CuentasPorPagarClientes();
                            $cxp_cliente->codigocliente = $request->input('codigocliente');
                            $cxp_cliente->valor = $request->input('garantia');
                            $cxp_cliente->fecha = $fecha_actual;

                            if ($cxp_cliente->save() != false) {

                                return response()->json(['success' => true]);

                            } else return response()->json(['success' => false]);

                        } else return response()->json(['success' => true]);

                    } else return response()->json(['success' => false]);*/

                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false]);
                }

            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);

    }

    private function createPDF($data0, $url_pdf)
    {
        $data = json_decode($data0);
        $plantilla = 'Solicitud.index_createpdf';
        $view = \View::make($plantilla, compact('data'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if (! is_dir(public_path().'/uploads/')){
            mkdir(public_path().'/uploads/');
        }

        if (! is_dir(public_path().'/uploads/pdf_suministros/')){
            mkdir(public_path().'/uploads/pdf_suministros/');
        }

        return @$pdf->save(public_path() . '/' . $url_pdf);
    }


    /*
     * FIN SECCION DE FUNCIONES REFERENTES A LAS SOLICITUDES DE LOS CLIENTES--------------------------------------------
     */



    /**
     * Obtener la configuracion del sistema
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    /*public function getTasaInteres()
    {
        return ConfiguracionSystem::where('optionname', 'AYORA_TASAINTERES')->get();
    }*/

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
                            ->orderBy('fechasolicitud', 'asc')->paginate(8);

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
        return SolicitudSuministro::with('suministro.tarifaaguapotable', 'suministro.calle.barrio', 'suministro.cont_catalogitem')
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
            $solicitud->whereRaw("(persona.lastnamepersona ILIKE '%" . $search . "%' OR persona.namepersona ILIKE '%" . $search . "%')");
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
        /*$solicitud = SolicitudSuministro::find($id);
        $solicitud->direccioninstalacion = $request->input('direccionsuministro');
        $solicitud->telefonosuminstro = $request->input('telefonosuministro');
        $result = $solicitud->save();
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);*/


        $solicitudsuministro = SolicitudSuministro::find($id);
        $solicitudsuministro->direccioninstalacion = $request->input('direccionsuministro');
        $solicitudsuministro->telefonosuminstro = $request->input('telefonosuministro');

        if ($solicitudsuministro->save() != false) {

            $temp_solicitud = SolicitudSuministro::find($solicitudsuministro->idsuministro);

            $suministro = Suministro::find($temp_solicitud->idsuministro);

            $suministro->idcalle = $request->input('idcalle');
            $suministro->idcliente = $request->input('codigocliente');
            $suministro->idtarifaaguapotable = $request->input('idtarifa');
            $suministro->direccionsumnistro = $request->input('direccionsuministro');
            $suministro->telefonosuministro = $request->input('telefonosuministro');

            $suministro->valoraguapotable = $request->input('agua_potable');
            $suministro->valoralcantarillado = $request->input('alcantarillado');
            $suministro->valorgarantia = $request->input('garantia');
            $suministro->valorcuotainicial = $request->input('cuota_inicial');
            $suministro->dividendocredito = $request->input('dividendos');

            $suministro->valortotalsuministro = $request->input('valor_partial');

            $suministro->formapago = $request->input('formapago');

            if ($suministro->save()) {


                $cobrocliente = new CobroCliente();

                $cobrocliente->idcatalogitem = 2;
                $cobrocliente->valor = $request->input('garantia');
                $cobrocliente->idcliente = $request->input('codigocliente');
                $cobrocliente->save();

                $dividendos = $request->input('dividendos');
                $valor = $request->input('valor_partial') / $dividendos;

                for ($i = 0; $i < $dividendos; $i++) {

                    $cobrocliente = new CobroCliente();

                    $cobrocliente->idcatalogitem = 1;
                    $cobrocliente->valor = $valor;
                    $cobrocliente->idcliente = $request->input('codigocliente');
                    $cobrocliente->save();

                }

                $o = SuministroCatalogItem::where('idsuministro', $temp_solicitud->idsuministro)
                                            ->where('idcatalogitem', 1)->get();
                $o = SuministroCatalogItem::find($o[0]->idsuministro_catalogitem);
                $o->valor = $request->input('valor_partial');
                $o->save();


                $oo = SuministroCatalogItem::where('idsuministro', $temp_solicitud->idsuministro)
                                                ->where('idcatalogitem', 2)->get();
                $oo = SuministroCatalogItem::find($oo[0]->idsuministro_catalogitem);
                $oo->valor = $request->input('garantia');
                $oo->save();

                $ooo = SuministroCatalogItem::where('idsuministro', $temp_solicitud->idsuministro)
                                                ->where('idcatalogitem', 3)->get();
                $ooo = SuministroCatalogItem::find($ooo[0]->idsuministro_catalogitem);
                $ooo->valor = $request->input('cuota_inicial');
                $ooo->save();

                /*$name = date('Ymd') . '_' . $suministro->idsuministro . '.pdf';

                $url_pdf = 'uploads/pdf_suministros/' . $name;

                $this->createPDF($request->input('data_to_pdf'), $url_pdf);*/

                return response()->json(['success' => true, 'idsolicitud' => $solicitudsuministro->idsolicitudsuministro]);

            } else return response()->json(['success' => false]);


        } else return response()->json(['success' => false]);



    }


    private function getMantenimiento()
    {
        $solicitud = SolicitudMantenimiento::join('solicitud', 'solicitud.idsolicitud', '=', 'solicitudmantenimiento.idsolicitud')
                                            ->join('cliente', 'cliente.idcliente', '=', 'solicitudmantenimiento.idcliente')
                                            ->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                                            ->join('suministro', 'suministro.idsuministro', '=', 'solicitudmantenimiento.idsuministro')
                                            ->where('solicitud.estadoprocesada', false)->get();

        return $solicitud;
    }


    public function reporte_printM()
    {
        ini_set('max_execution_time', 3000);

        $filtro = $this->getMantenimiento();

        $aux_empresa = SRI_Establecimiento::all();

        $today = date("Y-m-d H:i:s");

        $view =  \View::make('Solicitud.reporteMantenimientoPrint', compact('filtro','today','aux_empresa'))->render();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($view);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('reportM_' . $today);
    }

}
