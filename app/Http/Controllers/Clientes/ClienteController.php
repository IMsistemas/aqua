<?php

namespace App\Http\Controllers\Clientes;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;

use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Cuentas\CatalogoItemSolicitudServicio;
use App\Modelos\Cuentas\CuentasPorCobrarSuministro;
use App\Modelos\Cuentas\CuentasPorPagarClientes;
use App\Modelos\Persona;
use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Calle;
use App\Modelos\Servicios\ServicioJunta;
use App\Modelos\Servicios\ServiciosCliente;
use App\Modelos\Solicitud\Solicitud;
use App\Modelos\Solicitud\SolicitudCambioNombre;
use App\Modelos\Solicitud\SolicitudMantenimiento;
use App\Modelos\Solicitud\SolicitudOtro;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Solicitud\SolicitudSuministro;
use App\Modelos\SRI\SRI_TipoIdentificacion;
use App\Modelos\SRI\SRI_TipoImpuestoIva;
use App\Modelos\Suministros\Producto;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Tarifas\TarifaAguaPotable;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Clientes/index_cliente');
    }

    /**
     * Obtener los clientes paginados
     *
     * @param Request $request
     * @return mixed
     */
    public function getClientes(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cliente = null;

        $cliente = Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
                            ->select('cliente.*', 'persona.*', 'cont_plancuenta.*');

        if ($search != null) {
            $cliente = $cliente->whereRaw("(persona.razonsocial ILIKE '%" . $search . "%' OR persona.numdocidentific ILIKE '%" . $search . "%')");
        }

        return $cliente->orderBy('fechaingreso', 'desc')->paginate(8);
    }

    /**
     * Obtener todos los tipos de identificacion
     *
     * @return mixed
     */
    public function getTipoIdentificacion()
    {
        return SRI_TipoIdentificacion::orderBy('nameidentificacion', 'asc')->get();
    }

    /**
     * Obtener y devolver los numeros de identificacion que concuerden con el parametro a buscar
     *
     * @param $identify
     * @return mixed
     */
    public function getIdentify($identify)
    {
        return Persona::whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")
            ->whereRaw('idpersona NOT IN (SELECT idpersona FROM cliente)')
            ->get();
    }

    /**
     * Obtener y devolver la persona que cumpla con el numero de identificacion buscado
     *
     * @param $identify
     * @return mixed
     */
    public function getPersonaByIdentify($identify)
    {
        return Persona::whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")->get();
    }

    /**
     * Obtener el listado de los tipos de impuestos IVA
     *
     * @return mixed
     */
    public function getImpuestoIVA()
    {
        return SRI_TipoImpuestoIva::orderBy('nametipoimpuestoiva', 'asc')->get();
    }

    public function getItems()
    {
        return Cont_CatalogItem::where('idclaseitem', 1)->orderBy('codigoproducto', 'asc')->get();
    }

    public function getTipoCliente()
    {
        return TipoCliente::orderBy('nametipocliente', 'asc')->get();
    }


    public function searchDuplicate($numidentific)
    {
        $result = $this->searchExist($numidentific);
        return response()->json(['success' => $result]);
    }


    private function searchExist($numidentific)
    {
        $count = Cliente::join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
            ->where('persona.numdocidentific', $numidentific)->count();

        return ($count >= 1) ? true : false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($this->searchExist($request->input('documentoidentidadempleado'))) {

            return response()->json(['success' => false, 'type_error_exists' => true]);

        } else {

            if ($request->input('idpersona') == 0) {
                $persona = new Persona();
            } else {
                $persona = Persona::find($request->input('idpersona'));
            }

            $persona->numdocidentific = $request->input('documentoidentidadempleado');
            $persona->email = $request->input('correo');
            $persona->celphone = $request->input('celular');
            $persona->idtipoidentificacion = $request->input('tipoidentificacion');
            $persona->razonsocial = $request->input('nombres') . ' ' . $request->input('apellidos');
            $persona->lastnamepersona = $request->input('apellidos');
            $persona->namepersona = $request->input('nombres');
            $persona->direccion = $request->input('direccion');

            if ($persona->save()) {
                $cliente = new Cliente();
                $cliente->fechaingreso = $request->input('fechaingreso');
                $cliente->estado = true;
                $cliente->idpersona = $persona->idpersona;
                $cliente->idplancuenta = $request->input('cuentacontable');
                $cliente->idtipoimpuestoiva = $request->input('impuesto_iva');
                $cliente->telefonoprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
                $cliente->telefonosecundariodomicilio = $request->input('telefonosecundariodomicilio');
                $cliente->telefonoprincipaltrabajo = $request->input('telefonoprincipaltrabajo');
                $cliente->telefonosecundariotrabajo = $request->input('telefonosecundariotrabajo');
                $cliente->direcciontrabajo = $request->input('direcciontrabajo');

                $cliente->idtipocliente = $request->input('tipocliente');

                if ($cliente->save()) {
                    return response()->json(['success' => true]);
                } else return response()->json(['success' => false]);

            } else return response()->json(['success' => false]);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::find($request->input('idpersona'));

        $persona->numdocidentific = $request->input('documentoidentidadempleado');
        $persona->email = $request->input('correo');
        $persona->celphone = $request->input('celular');
        $persona->idtipoidentificacion = $request->input('tipoidentificacion');
        $persona->razonsocial = $request->input('nombres') . ' ' . $request->input('apellidos');
        $persona->lastnamepersona = $request->input('apellidos');
        $persona->namepersona = $request->input('nombres');
        $persona->direccion = $request->input('direccion');

        if ($persona->save()) {
            $cliente = Cliente::find($id);
            $cliente->fechaingreso = $request->input('fechaingreso');
            $cliente->estado = true;
            $cliente->idpersona = $persona->idpersona;
            $cliente->idplancuenta = $request->input('cuentacontable');
            $cliente->idtipoimpuestoiva = $request->input('impuesto_iva');
            $cliente->telefonoprincipaldomicilio = $request->input('telefonoprincipaldomicilio');
            $cliente->telefonosecundariodomicilio = $request->input('telefonosecundariodomicilio');
            $cliente->telefonoprincipaltrabajo = $request->input('telefonoprincipaltrabajo');
            $cliente->telefonosecundariotrabajo = $request->input('telefonosecundariotrabajo');
            $cliente->direcciontrabajo = $request->input('direcciontrabajo');

            if ($cliente->save()) {
                return response()->json(['success' => true]);
            } else return response()->json(['success' => false]);

        } else return response()->json(['success' => false]);
    }

    /**
     * Obtener si el cliente esta relacionado a alguna solicitud
     *
     * @param $codigocliente
     * @return mixed
     */
    public function getIsFreeCliente($codigocliente)
    {
        return Solicitud::where('idcliente', $codigocliente)->count();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente->delete()) {
            return response()->json(['success' => true]);
        } else return response()->json(['success' => false]);
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
            $max = Suministro::max('idsuministro');
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
                return response()->json(['success' => true, 'idsolicitud' => $solicitudsuministro->idsolicitudsuministro]);
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

        $suministro->formapago = $request->input('formapago');

        //$suministro->idcatalogitem = $request->input('idproducto');

        if ($suministro->save()) {

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

        return $pdf->save(public_path() . '/' . $url_pdf);
    }


    /*
     * FIN SECCION DE FUNCIONES REFERENTES A LAS SOLICITUDES DE LOS CLIENTES--------------------------------------------
     */
}
