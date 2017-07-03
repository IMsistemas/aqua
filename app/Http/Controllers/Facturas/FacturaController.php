<?php

namespace App\Http\Controllers\Facturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Facturas\Factura;
use App\Modelos\Facturas\OtrosValoresCobroAgua;
use App\Modelos\Facturas\OtrosValoresFactura;
use App\Modelos\Facturas\OtrosValores;
use App\Modelos\Servicios\ServicioJunta;
use App\Modelos\Servicios\ServicioAguaPotable;
use App\Modelos\Servicios\AguaPotable;
use App\Modelos\Servicios\ServiciosCliente;
use App\Modelos\Servicios\ServiciosTipoCliente;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Factura/factura');
    }

    public function getServicios()
    {
        return ServicioJunta::orderBy('nombreservicio', 'asc')->get();
    }

    public function getMultas()
    {
        return OtrosValores::orderBy('nombreotrosvalores', 'asc')->get();
    }

    public function getCobroAgua(Request $request)
    {

        $filter = json_decode($request->get('filter'));

        $search = $filter->search;

        $facturas = CobroAgua::with(
            [
                'suministro.tarifaaguapotable', 'lectura', 'catalogoitem_cobroagua.cont_catalogitem', 'otrosvalores_cobroagua.otrosvalores',
                'suministro.cliente.sri_tipoimpuestoiva', 'cont_cuentasporcobrar',
                'suministro' => function ($query_suministro) use ($search) {
                    return $query_suministro->with([
                        'cliente' => function ($query_cliente) use ($search) {
                            return $query_cliente->with([
                                'persona' => function ($query_persona) use ($search) {
                                    $query_persona->whereRaw("(persona.lastnamepersona LIKE '%" . $search . "%' OR persona.namepersona LIKE '%" . $search . "%')");
                                }
                            ]);
                        }
                    ]);
                }
            ]);


        if ($filter->estado == 1) {
            $facturas->where('estadopagado', true);
        }
        if ($filter->estado == 2) {
            $facturas->where('estadopagado', false);
        }

        if ($filter->mes != null && $filter->mes != '') {
            $facturas->whereRaw('EXTRACT( MONTH FROM fechacobro) = ' . $filter->mes);
        }

        if ($filter->anio != null && $filter->anio != '') {
            $facturas->whereRaw('EXTRACT( YEAR FROM fechacobro) = ' . $filter->anio);
        }

        return $facturas->orderBy('fechacobro','desc')->paginate(5);
    }

    public function Filtrar($filter)
    {
        $filter = json_decode($filter);

        $cobro = Factura::with('cobroagua.suministro.cliente','cobroagua.suministro.aguapotable','otrosvaloresfactura.otrovalor','serviciosenfactura.serviciojunta','cobroagua.lectura','cliente.servicioscliente.serviciojunta' );
        $cobro->whereRaw('EXTRACT( MONTH FROM fechafactura) = ' . $filter->mes);
        $cobro ->whereRaw('EXTRACT( YEAR FROM fechafactura) = ' . $filter->anio);

        if($filter->estado == 1)
        {
            $cobro ->where('estapagado', true);
        }

        if($filter->estado == 2)
        {
            $cobro ->where('estapagado', false);
        }

        return $cobro->paginate(5);

    }

    public function getServiciosXCobro($id)
    {
        $cliente = Cliente::find($id);
        return ServiciosTipoCliente::with('serviciojunta')->where('id', $cliente->id)->get();
    }

    public function verifyPeriodo()
    {
        $countCobroAgua = CobroAgua::whereRaw('EXTRACT( MONTH FROM fechacobro ) = ' . date('m'))
                                        ->whereRaw('EXTRACT( YEAR FROM fechacobro ) = ' . date('Y'))->count();

        if ($countCobroAgua > 0) {
            $activate = true;
        } else {
            $activate = false;
        }


        $countClientes = Cliente::whereRaw('idcliente NOT IN (SELECT idcliente FROM suministro)')->count();

        if ($countClientes > 0) {
            $activate = true;
        } else {
            $activate = false;
        }


        return response()->json(['success' => $activate]);

        /*$countCobroAgua = CobroAgua::whereRaw('EXTRACT( MONTH FROM fecha ) = ' . date('m'))
                                    ->whereRaw('EXTRACT( YEAR FROM fecha ) = ' . date('Y'))
                                    ->get();

        $partialSQL = 'codigocliente NOT IN (SELECT codigocliente FROM facturacobro WHERE EXTRACT( MONTH FROM fechafactura ) = ' . date('m');
        $partialSQL .= ' AND EXTRACT( YEAR FROM fechafactura ) = ' .  date('Y') . ' )';

        $countClientServices = ServiciosCliente::whereRaw($partialSQL)->count();

        $activate = false;

        if ($countClientServices > 0) {
            $activate = true;
        } else if (count($countCobroAgua) > 0) {
            $count_suministro = Suministro::whereRaw('numerosuministro NOT IN(SELECT numerosuministro FROM cobroagua)')
                                            ->count();
            if($count_suministro > 0) {
                $activate = true;
            }
        } else $activate = true;

        return response()->json(['success' => $activate]);*/

        /*if($countCobroAgua != 0) {
            $count_suministro=Suministro::whereRaw('numerosuministro NOT IN(SELECT numerosuministro FROM cobroagua)')->count();
            if($count_suministro != 0) {
                return response()->json(['success' => true, 'count' => $count_suministro]);
            } else {
                return response()->json(['success' => false, 'count' => $count_suministro]);
            }
        } else {
            return response()->json(['success' => true, 'count' => $countCobroAgua]);
        }*/

    }

    public function generate()
    {
        $cliente = Cliente::all();

        if (count($cliente) > 0) {
            foreach ($cliente as $item) {
                $cliente_suministro = Suministro::where('idcliente', $item->idcliente)->get();
                if (count($cliente_suministro) > 0) {

                    foreach ($cliente_suministro as $item0) {
                        $objectCobro = CobroAgua::where('idsuministro', $item0->idsuministro)
                                                    ->whereRaw('EXTRACT( MONTH FROM fechacobro) = ' . date('m'))
                                                    ->whereRaw('EXTRACT( YEAR FROM fechacobro) = ' . date('Y'))
                                                    ->count();
                        if ($objectCobro == 0) {
                            $cobro = new CobroAgua();
                            $cobro->fechacobro = date('Y-m-d');
                            $cobro->idsuministro = $item0->idsuministro;
                            $cobro->estadopagado = false;
                            $cobro->save();

                            /*$factura = new Factura();
                            $factura->fechafactura = date('Y-m-d');
                            $factura->idcobroagua = $cobro->idcobroagua;
                            $factura->codigocliente = $item->codigocliente;
                            $factura->estapagado = false;
                            $factura->save();

                            $cobro2 = CobroAgua::find($cobro->idcobroagua);
                            $cobro2->idfactura = $factura->idfactura;
                            $cobro2->save();*/
                        }
                    }

                } else {
                    /*$partialSQL = 'codigocliente NOT IN (SELECT codigocliente FROM facturacobro WHERE EXTRACT( MONTH FROM fechafactura ) = ' . date('m');
                    $partialSQL .= ' AND EXTRACT( YEAR FROM fechafactura ) = ' .  date('Y') . ' )';

                    $cliente_servicio = ServiciosCliente::where('codigocliente', $item->codigocliente)
                                                        ->whereRaw( $partialSQL )
                                                        ->get();
                    if ( count($cliente_servicio) > 0 ) {
                        $factura = new Factura();
                        $factura->fechafactura = date('Y-m-d');
                        $factura->codigocliente = $item->codigocliente;
                        $factura->estapagado = false;
                        $factura->save();
                    }*/

                }
            }
        }

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
        $list_services = $request->input('rubros');

        foreach ($list_services as $item) {

            if ($item['id'] != 0){
                if ($item['valor'] != 0 && $item['valor'] != '') {

                    $object_find = OtrosValoresCobroAgua::where('idcobroagua', $request->input('no_factura'))
                                                ->where('idotrosvalores', $item['id'])
                                                ->get();

                    if (count($object_find) == 0){
                        $object = new OtrosValoresCobroAgua();
                        $object->idcobroagua = $request->input('no_factura');
                        $object->valor = $item['valor'];
                        $object->idotrosvalores = $item['id'];
                        $object->save();
                    } else {
                        $object = OtrosValoresCobroAgua::where('idcobroagua', $request->input('no_factura'))
                                                        ->where('idotrosvalores', $item['id']);
                        if ($object->update(['valor' => $item['valor']]) == false){
                            return response()->json(['success' => false]);
                        }
                    }
                }
            }

        }

        /*$factura = Factura::find($request->input('no_factura'));
        $factura->totalfactura = $request->input('total');
        $factura->save();*/

        return response()->json(['success' => true]);
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
        $cobro = CobroAgua::find($id);

        $cobro->estadopagado = true;
        $cobro->save();

        return response()->json(['success' => true]);
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

    public function printer(Request $request)
    {
        $data = $request->input('item');

        $plantilla = 'Factura.factura_print';
        $view = \View::make($plantilla, compact('data'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if (! is_dir(public_path().'/uploads/')){
            mkdir(public_path().'/uploads/');
        }

        if (! is_dir(public_path().'/uploads/factura/')){
            mkdir(public_path().'/uploads/factura/');
        }

        /*$pdf->setPaper('a4', 'landscape')->save(public_path() . '/uploads/factura/' . $data['cobroagua']['numerosuministro'] . '.pdf');

        return response()->json(['success' => true, 'url' => 'uploads/factura/' . $data['cobroagua']['numerosuministro'] . '.pdf']);*/

        $pdf->setPaper('a4', 'portrait')->save(public_path() . '/uploads/factura/' . $data['idsuministro'] . '.pdf');

        return response()->json(['success' => true, 'url' => 'uploads/factura/' . $data['idsuministro'] . '.pdf']);
    }
}
