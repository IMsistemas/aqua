<?php

namespace App\Http\Controllers\Cuentas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Contabilidad\Cont_DocumentoVenta;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\CobroCierreCaja;
use App\Modelos\Cuentas\CobroCliente;
use App\Modelos\Cuentas\CobroHistorial;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RecaudacionCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Recaudacion.index_recaudacion');
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

        /*$cliente = Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
            ->with('sri_tipoempresa', 'sri_parte')
            ->selectRaw('cliente.*, persona.*, cont_plancuenta.*, (SELECT SUM(valor) FROM cobrocliente WHERE cobrocliente.idcliente = cliente.idcliente) AS valorcobrar')
            ->where("cliente.estado", true);

        if ($search != null) {
            $cliente = $cliente->whereRaw("(persona.razonsocial ILIKE '%" . $search . "%' OR persona.numdocidentific ILIKE '%" . $search . "%')");
        }

        return $cliente->orderBy('lastnamepersona', 'asc')->paginate(8);*/

        $cliente = Suministro::join('cliente', 'cliente.idcliente', '=', 'suministro.idcliente')
                                ->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
            //->with('sri_tipoempresa', 'sri_parte')
            ->selectRaw('suministro.*, cliente.*, persona.*, cont_plancuenta.*, (SELECT SUM(valor) FROM cobrocliente WHERE cobrocliente.idcliente = cliente.idcliente) AS valorcobrar')
            ->where("cliente.estado", true);

        if ($search != null) {
            $cliente = $cliente->whereRaw("(persona.razonsocial ILIKE '%" . $search . "%' OR persona.numdocidentific ILIKE '%" . $search . "%' OR CAST(suministro.numconexion AS TEXT) LIKE '%" . $search . "%')");
        }

        return $cliente->orderBy('numconexion', 'asc')->paginate(8);
    }

    public function getFacConsumo($idcliente)
    {
        $cobroagua = CobroAgua::join('suministro', 'suministro.idsuministro', '=', 'cobroagua.idsuministro')
                        ->where('suministro.idcliente', $idcliente)->orderBy('idcobroagua', 'desc')
                        ->where('cobroagua.total', '!=', null)->get();


        return $cobroagua;
    }

    public function getDerechoAcometida($idcliente)
    {
        $suministro = Suministro::where('idcliente', $idcliente)->orderBy('idsuministro', 'desc')->get();

        return $suministro;
    }

    public function getOtrosCargos($idcliente)
    {
        $otrosCargos = SolicitudServicio::join('solicitud', 'solicitud.idsolicitud', '=', 'solicitudservicio.idsolicitud')
            ->where('solicitud.idcliente', $idcliente)->orderBy('fechaprocesada', 'desc')->get();

        return $otrosCargos;
    }


    public function getItemsCobro($idcliente)
    {
        return CobroCliente::join('cont_catalogitem', 'cont_catalogitem.idcatalogitem', '=', 'cobrocliente.idcatalogitem')
                ->where('idcliente', $idcliente)->get();
    }

    public function getRegistroCobro($idcliente)
    {

        $facturas = Cont_DocumentoVenta::join('cobrohistorial', 'cobrohistorial.iddocumentoventa', '=', 'cont_documentoventa.iddocumentoventa')
                                    ->join('cont_catalogitem', 'cont_catalogitem.idcatalogitem', '=', 'cobrohistorial.idcatalogitem')
                                    ->where('cobrohistorial.idcliente', $idcliente)->get();

        return $facturas;

        /*return CobroHistorial::join('cont_catalogitem', 'cont_catalogitem.idcatalogitem', '=', 'cobrohistorial.idcatalogitem')
                                ->where('idcliente', $idcliente)->get();*/
    }


    public function createFacturaItems(Request $request)
    {
        $data = json_decode($request->get('data'));

        $result = [];

        foreach ($data as $item) {

            $id = $item->idcatalogitem;

            /*$result_query = CobroCliente::with([
                'cliente.persona',
                'cont_catalogitem' => function ($query) use ($id) {
                    return $query->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                        ->selectRaw("*")
                        ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                        ->selectRaw("(SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                        ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                        ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                        ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                        ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                        ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                        ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                        ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
                        ->whereRaw("cont_catalogitem.idcatalogitem = " . $id);
                }
            ])
            ->get();*/

            $result_query = Cont_CatalogItem::with('cobrocliente.cliente.persona')
                ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                //->join("sri_tipoimpuestoice","sri_tipoimpuestoice.idtipoimpuestoice","=","cont_catalogitem.idtipoimpuestoice")
                ->selectRaw("*")
                ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                ->selectRaw(" (SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
                //->whereRaw(" upper(cont_catalogitem.codigoproducto) LIKE upper('%$id%') OR cont_catalogitem.idcatalogitem = 7  OR cont_catalogitem.idcatalogitem = 2")
                ->whereRaw(" cont_catalogitem.idcatalogitem = " . $id)
                ->get();


            $result_query2 = Cont_CatalogItem::join('cobrocliente', 'cobrocliente.idcatalogitem', '=', 'cont_catalogitem.idcatalogitem')
                ->join('cliente', 'cliente.idcliente', '=', 'cobrocliente.idcliente')
                ->join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
                ->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                //->join("sri_tipoimpuestoice","sri_tipoimpuestoice.idtipoimpuestoice","=","cont_catalogitem.idtipoimpuestoice")
                ->selectRaw("cont_catalogitem.*, persona.*, sri_tipoimpuestoiva.*, cobrocliente.*, cliente.idcliente")
                ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                ->selectRaw(" (SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
                //->whereRaw(" upper(cont_catalogitem.codigoproducto) LIKE upper('%$id%') OR cont_catalogitem.idcatalogitem = 7  OR cont_catalogitem.idcatalogitem = 2")
                ->whereRaw(" cont_catalogitem.idcatalogitem = " . $id . " AND cobrocliente.idcliente = " . $item->idcliente)
                ->get();


            $temp = $result_query2[0];

            $temp->valor = $item->acobrar;

            $result[] = $temp;

        }



        Session::forget('suministro_to_facturar');

        Session::put('suministro_to_facturar', $result);

        return response()->json(['success' => true]);
    }


    public function getCuentasCerrar()
    {
        return CobroCierreCaja::join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cobrocierrecaja.idplancuenta')
                                ->get();
    }





    public function createFactura(Request $request)
    {
        $data = json_decode($request->get('data'));

        $result = [];

        foreach ($data as $item) {

            if ($item->type == 'derAcometida') {

                $suministro = Suministro::with([
                    'cliente.persona', 'calle.barrio',
                    'suministrocatalogitem.cont_catalogitem' => function ($query) {
                        return $query->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                            ->selectRaw("*")
                            ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                            ->selectRaw("(SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                            ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
                            ->whereRaw(" cont_catalogitem.idcatalogitem = 1");
                    }
                ])
                ->where('idsuministro', $item->id)->get();

                $result[] = $suministro[0];


            } else if ($item->type == 'derAcometida-garantia') {

                $garantia = Suministro::with([
                    'cliente.persona', 'calle.barrio',
                    'suministrocatalogitem.cont_catalogitem' => function ($query) {
                        return $query->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                            ->selectRaw("*")
                            ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                            ->selectRaw("(SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                            ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
                            ->whereRaw(" cont_catalogitem.idcatalogitem = 2");
                    }
                ])
                    ->where('idsuministro', $item->id)->get();

                $result[] = $garantia[0];

            } else if ($item->type == 'derAcometida-cuotaInicial') {

                $cuotainicial = Suministro::with([
                    'cliente.persona', 'calle.barrio',
                    'suministrocatalogitem.cont_catalogitem' => function ($query) {
                        return $query->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                            ->selectRaw("*")
                            ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                            ->selectRaw("(SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                            ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio")
                            ->whereRaw(" cont_catalogitem.idcatalogitem = 3");
                    }
                ])
                    ->where('idsuministro', $item->id)->get();

                $result[] = $cuotainicial[0];

            } else if ($item->type == 'facConsumo') {

                $cobroagua = CobroAgua::with([
                    'suministro.cliente.persona', 'catalogoitem_cobroagua.cont_catalogitem' => function ($query) {
                        return $query->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                            ->selectRaw("*")
                            ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                            ->selectRaw("(SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                            ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio");
                        //->whereRaw(" cont_catalogitem.idcatalogitem = 1 OR cont_catalogitem.idcatalogitem = 2");
                    }

                ])
                    ->join('suministro', 'suministro.idsuministro', '=', 'cobroagua.idsuministro')
                    ->where('cobroagua.idcobroagua', $item->id)->get();

                $result[] = $cobroagua[0];

            } else if ($item->type == 'otrosCargos') {

                $cobroagua = SolicitudServicio::with([
                    'solicitud.cliente.persona', 'catalogoitem_solicitudservicio.cont_catalogitem' => function ($query) {
                        return $query->join("sri_tipoimpuestoiva","sri_tipoimpuestoiva.idtipoimpuestoiva","=","cont_catalogitem.idtipoimpuestoiva")
                            ->selectRaw("*")
                            ->selectRaw("sri_tipoimpuestoiva.porcentaje as PorcentIva ")
                            ->selectRaw("(SELECT aux_ice.porcentaje FROM sri_tipoimpuestoice aux_ice WHERE aux_ice.idtipoimpuestoice=cont_catalogitem.idtipoimpuestoice ) as PorcentIce ")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as concepto")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as controlhaber")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta) as tipocuenta")
                            ->selectRaw("( SELECT concepto FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as conceptoingreso")
                            ->selectRaw("( SELECT controlhaber FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as controlhaberingreso")
                            ->selectRaw("( SELECT tipocuenta FROM cont_plancuenta  WHERE idplancuenta=cont_catalogitem.idplancuenta_ingreso) as tipocuentaingreso")
                            ->selectRaw("(SELECT f_costopromedioitem(cont_catalogitem.idcatalogitem,'') ) as CostoPromedio");
                        //->whereRaw(" cont_catalogitem.idcatalogitem = 1 OR cont_catalogitem.idcatalogitem = 2");
                    }

                ])->where('solicitudservicio.idsolicitudservicio', $item->id)->get();

                $result[] = $cobroagua[0];

            }

        }

        Session::forget('suministro_to_facturar');

        Session::put('suministro_to_facturar', $result);

        return response()->json(['success' => true]);
    }




    public function generate()
    {

        $suministro = Suministro::orderBy('idsuministro', 'asc')->get();

        if (count($suministro) > 0) {

            foreach ($suministro as $item) {

                $objectCobro = CobroAgua::where('idsuministro', $item->idsuministro)
                                ->whereRaw('EXTRACT( MONTH FROM fechacobro) = ' . date('m'))
                                ->whereRaw('EXTRACT( YEAR FROM fechacobro) = ' . date('Y'))
                                ->count();

                if ($objectCobro == 0) {

                    $cobro = new CobroAgua();
                    $cobro->fechacobro = date('Y-m-d');
                    $cobro->idsuministro = $item->idsuministro;
                    $cobro->estadopagado = false;


                    if ($cobro->save() == false) {
                        return response()->json( [ 'success' => false ] );
                    }
                }

            }

            return response()->json( [ 'success' => true ] );

        } else {

            return response()->json( [ 'success' => true ] );

        }

    }




    public function getFacturas($idcliente)
    {

        $factura = Cont_DocumentoVenta::with('cont_cuentasporcobrar', 'suministro')
                                        ->join('cliente', 'cliente.idcliente', '=', 'cont_documentoventa.idcliente')
                                        ->join('persona','persona.idpersona','=','cliente.idpersona')
                                        ->where('cont_documentoventa.idcliente', $idcliente)
                                        ->get();
        $result = [];

        foreach ($factura as $item) {
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $result = DB::table('cobrocierrecaja')->delete();

        if ($result){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
