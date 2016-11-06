<?php

namespace App\Http\Controllers\Facturas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Clientes\TipoCliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Facturas\Factura;
use App\Modelos\Servicios\ServicioJunta;
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
       // return CobroAgua::with('suministro','factura') ->orderBy('fecha','asc')->get();

        return  CobroAgua::join('suministro', 'suministro.numerosuministro', '=', 'cobroagua.numerosuministro')
            ->join('factura', 'factura.idcobroagua', '=', 'cobroagua.idcobroagua')
            ->join('cliente', 'factura.codigocliente', '=', 'cliente.codigocliente')
            ->join('tipocliente', 'cliente.id', '=', 'tipocliente.id')
            ->orderBy('fecha', 'desc')
            ->get();




       // return TipoCliente::find($cobro->id) -> serviciojunta() -> get ();


       /* foreach ($cobro as $item)
        {
            $roles = App\User::find(1)->roles()->orderBy('name')->get();
            $roles = App\User::find(1)->roles()->orderBy('name')->get();

        }*/

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
