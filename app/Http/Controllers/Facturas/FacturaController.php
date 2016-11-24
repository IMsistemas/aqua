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

    public function getCobroAgua()
    {
        return CobroAgua::with('suministro.cliente','suministro.aguapotable','factura.otrosvaloresfactura.otrosvalores','factura.serviciosenfactura.serviciojunta','lectura' )
                                ->orderBy('fecha','asc')->get();
    }

    public function Filtrar($filter)
    {
        $filter = json_decode($filter);


        $cobro = CobroAgua::with('suministro.cliente','suministro.aguapotable','factura.otrosvaloresfactura.otrosvalores','factura.serviciosenfactura.serviciojunta','lectura' );
        $cobro->whereRaw('EXTRACT( MONTH FROM fecha) = ' . $filter->mes);
        $cobro ->whereRaw('EXTRACT( YEAR FROM fecha) = ' . $filter->anio);
        if($filter->estado == 1)
        {
            $cobro ->where('estapagado', true);
        }

        if($filter->estado == 2)
        {
            $cobro ->where('estapagado', false);
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
        return response()->json(['count' => $count]);
    }

    public function generate()
    {
        $suministro = Suministro::get();
        if (count($suministro) > 0){
            foreach ($suministro as $item){
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
                $factura-> save();

                $cobro2 = CobroAgua::find($cobro->idcobroagua);
                $cobro2->idfactura = $factura->idfactura;
                $cobro2->save();
            }
            $result = 1;
        } else {
            $result = 2;
        }
        return response()->json(['result' => $result]);
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

        $cobro = CobroAgua::find($id);

        $cobro->estapagado = true;
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
