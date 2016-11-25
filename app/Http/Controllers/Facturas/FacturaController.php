<?php

namespace App\Http\Controllers\Facturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Facturas\Factura;
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

    public function getCobroAgua()
    {
        /*return CobroAgua::with('suministro.cliente','suministro.aguapotable','factura.otrosvaloresfactura.otrosvalores','factura.serviciosenfactura.serviciojunta','lectura' )
                                ->orderBy('fecha','asc')->get();*/

        return Factura::with('cobroagua.suministro.cliente', 'cobroagua.suministro.aguapotable',
                                        'otrosvaloresfactura.otrosvalores', 'serviciosenfactura.serviciojunta',
                                        'cobroagua.lectura', 'cliente.servicioscliente.serviciojunta')->orderBy('fechafactura','asc')->get();
    }

    public function Filtrar($filter)
    {
        $filter = json_decode($filter);


        $cobro = Factura::with('cobroagua.suministro.cliente','cobroagua.suministro.aguapotable','otrosvaloresfactura.otrosvalores','serviciosenfactura.serviciojunta','cobroagua.lectura','cliente.servicioscliente.serviciojunta' );
        $cobro->whereRaw('EXTRACT( MONTH FROM fechafactura) = ' . $filter->mes);
        $cobro ->whereRaw('EXTRACT( YEAR FROM fechafactura) = ' . $filter->anio);
        if($filter->estado == 1)
        {
            $cobro ->where('estapagada', true);
        }

        if($filter->estado == 2)
        {
            $cobro ->where('estapagada', false);
        }

        return $cobro->get();

    }



    public function getServiciosXCobro($id)
    {
        $cliente = Cliente::find($id);
        return ServiciosTipoCliente::with('serviciojunta')->where('id', $cliente->id)->get();
    }

    public function verifyPeriodo()
    {
        $count = CobroAgua::whereRaw('EXTRACT( MONTH FROM fecha) = ' . date('m'))
            ->whereRaw('EXTRACT( YEAR FROM fecha) = ' . date('Y'))
            ->count();
        if($count!=0)
        {
            $count_suministro=Suministro::whereRaw('numerosuministro NOT IN(SELECT numerosuministro FROM cobroagua)')->count();
            if($count_suministro!=0) {
                return response()->json(['success' => true, 'count' => $count_suministro]);

            }
            else
            {
                return response()->json(['success' => false, 'count' => $count_suministro]);
            }


        }else{
            return response()->json(['success' => true, 'count' => $count]);
        }

    }

    public function generate()
    {

        $cliente = Cliente::all();

        if (count($cliente) > 0) {
            foreach ($cliente as $item) {
                $cliente_suministro = Suministro::where('codigocliente', $item->codigocliente)->get();
                if (count($cliente_suministro) > 0) {

                    foreach ($cliente_suministro as $item0) {

                        $objectCobro = CobroAgua::where('numerosuministro', $item0->numerosuministro)
                                                    ->whereRaw('EXTRACT( MONTH FROM fecha) = ' . date('m'))
                                                    ->whereRaw('EXTRACT( YEAR FROM fecha) = ' . date('Y'))
                                                    ->count();
                        if ($objectCobro == 0) {

                            $cobro = new CobroAgua();
                            $cobro->fecha = date('Y-m-d');
                            $cobro->numerosuministro = $item0->numerosuministro;
                            $cobro->estapagado = false;
                            $cobro->save();

                            $factura = new Factura();
                            $factura->fechafactura = date('Y-m-d');
                            $factura->idcobroagua = $cobro->idcobroagua;
                            $factura->codigocliente = $item->codigocliente;
                            $factura->estapagada = false;
                            $factura->save();

                            $cobro2 = CobroAgua::find($cobro->idcobroagua);
                            $cobro2->idfactura = $factura->idfactura;
                            $cobro2->save();
                        }
                    }

                } else {

                    $cliente_servicio = ServiciosCliente::where('codigocliente', $item->codigocliente)->get();

                    if (count($cliente_servicio) > 0) {
                        $factura = new Factura();
                        $factura->fechafactura = date('Y-m-d');
                        $factura->codigocliente = $item->codigocliente;
                        $factura->estapagada = false;
                        $factura->save();
                    }

                }
            }
        }


        /*$suministro = Suministro::get();
        if (count($suministro) > 0){
            foreach ($suministro as $item) {

                $objectCobro = CobroAgua::where('numerosuministro', $item->numerosuministro)
                    ->whereRaw('EXTRACT( MONTH FROM fecha) = ' . date('m'))
                    ->whereRaw('EXTRACT( YEAR FROM fecha) = ' . date('Y'))
                    ->count();
                if ($objectCobro == 0) {

                    $cobro = new CobroAgua();
                    $cobro->fecha = date('Y-m-d');
                    $cobro->numerosuministro = $item->numerosuministro;
                    $cobro->estapagado = false;
                    $cobro->save();

                    $factura = new Factura();
                    $factura->fechafactura = date('Y-m-d');
                    $factura->idcobroagua = $cobro->idcobroagua;
                    $factura->codigocliente = $item->codigocliente;
                    $factura->estapagada = false;
                    $factura->save();

                    $cobro2 = CobroAgua::find($cobro->idcobroagua);
                    $cobro2->idfactura = $factura->idfactura;
                    $cobro2->save();
                }
            }
            $result = 1;
        } else {
            $result = 2;
        }
        return response()->json(['result' => $result]);*/
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

                    $object_find = OtrosValoresFactura::where('idfactura', $request->input('no_factura'))
                                                ->where('idotrosvalores', $item['id'])
                                                ->get();
                    if (count($object_find) == 0){
                        $object = new OtrosValoresFactura();
                        $object->idfactura = $request->input('no_factura');
                        $object->valor = $item['valor'];
                        $object->idotrosvalores = $item['id'];
                        $object->save();
                    } else {
                        $object = $object_find[0];
                        $object->idfactura = $request->input('no_factura');
                        $object->valor = $item['valor'];
                        $object->idotrosvalores = $item['id'];
                        $object->save();
                    }
                }
            }

        }

        $factura = Factura::find($request->input('no_factura'));
        $factura->totalfactura = $request->input('total');
        $factura->save();

        return response()->json(['success' => $object]);
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

        $cobro = Factura::find($id);

        $cobro->estapagada = true;
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
}
