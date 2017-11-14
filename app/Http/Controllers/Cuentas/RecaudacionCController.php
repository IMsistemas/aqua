<?php

namespace App\Http\Controllers\Cuentas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Solicitud\SolicitudServicio;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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

        $cliente = Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
            ->with('sri_tipoempresa', 'sri_parte')
            ->select('cliente.*', 'persona.*', 'cont_plancuenta.*')
            ->where("cliente.estado", true);

        if ($search != null) {
            $cliente = $cliente->whereRaw("(persona.razonsocial ILIKE '%" . $search . "%' OR persona.numdocidentific ILIKE '%" . $search . "%')");
        }

        return $cliente->orderBy('lastnamepersona', 'asc')->paginate(8);
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
        //
    }
}
