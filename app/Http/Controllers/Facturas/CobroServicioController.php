<?php

namespace App\Http\Controllers\Facturas;

use App\Modelos\Cuentas\CatalogoItemSolicitudServicio;
use App\Modelos\Cuentas\CobroCliente;
use App\Modelos\Cuentas\CobroServicio;
use App\Modelos\Solicitud\SolicitudServicio;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CobroServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Cuentas.cobroservicio');
    }


    public function getCobrosServicios(Request $request)
    {
        return CobroServicio::with('cont_cuentasporcobrar', 'solicitudservicio.catalogoitem_solicitudservicio.cont_catalogitem')
                                ->join('solicitudservicio', 'solicitudservicio.idsolicitudservicio', '=', 'cobroservicio.idsolicitudservicio')
                                ->join('solicitud', 'solicitudservicio.idsolicitud', '=', 'solicitud.idsolicitud')
                                ->join('cliente', 'cliente.idcliente', '=', 'solicitud.idcliente')
                                ->join('persona', 'cliente.idpersona', '=', 'persona.idpersona')
                                ->orderBy('fechacobro', 'desc')->get();
    }


    public function generate()
    {

        $elements = CatalogoItemSolicitudServicio::groupBy('idsolicitudservicio')->select('idsolicitudservicio')->get();

        if (count($elements) != 0) {

            foreach ($elements as $item) {
                $count = CobroServicio::where('idsolicitudservicio', $item->idsolicitudservicio)
                                        ->whereRaw('EXTRACT( MONTH FROM fechacobro) = ' . date('m'))
                                        ->whereRaw('EXTRACT( YEAR FROM fechacobro) = ' . date('Y'))
                                        ->count();

                if ($count == 0) {

                    $data_solicitud = SolicitudServicio::join('solicitud', 'solicitudservicio.idsolicitud', '=', 'solicitud.idsolicitud')
                                                        //->join('cliente', 'solicitud.idcliente', '=', 'cliente.idcliente')
                                                        ->where('idsolicitudservicio', $item->idsolicitudservicio)
                                                        ->get();

                    $cobroservicio = new CobroServicio();

                    $cobroservicio->fechacobro = date('Y-m-d');
                    $cobroservicio->idcliente = $data_solicitud[0]->idcliente;
                    $cobroservicio->idsolicitudservicio = $data_solicitud[0]->idsolicitudservicio;

                    $itemsolicitudservicio = CatalogoItemSolicitudServicio::where('idsolicitudservicio', $item->idsolicitudservicio)
                                                                                ->get();

                    $total = 0;

                    foreach ($itemsolicitudservicio as $value) {
                        $total = $total + $value->valor;
                    }

                    $cobroservicio->total = $total;

                    if ( ! $cobroservicio->save()) {
                        return response()->json( [ 'success' => false ] );
                    }
                }

            }

            $servicios = CatalogoItemSolicitudServicio::join('solicitudservicio', 'solicitudservicio.idsolicitudservicio', '=', 'catalogoitem_solicitudservicio.idsolicitudservicio')
                                    ->join('solicitud', 'solicitudservicio.idsolicitud', '=', 'solicitud.idsolicitud')
                                    ->get();

            foreach ($servicios as $item) {

                //if ($item->estadoprocesada != true) {

                    $query = CobroCliente::where('idcatalogitem', $item->idcatalogitem)
                                            ->where('idcliente', $item->idcliente)->get();

                    if (count($query) == 0) {

                        $cobrocliente = new CobroCliente();
                        $cobrocliente->idcatalogitem = $item->idcatalogitem;
                        $cobrocliente->idcliente = $item->idcliente;
                        $cobrocliente->valor = $item->valor;

                        $cobrocliente->save();

                    } else {

                        $cobrocliente = CobroCliente::find($query[0]->idcobrocliente);
                        $cobrocliente->valor = $cobrocliente->valor + $item->valor;
                        $cobrocliente->save();

                    }

                //}

            }


            return response()->json( [ 'success' => true ] );

        }
        return response()->json( [ 'success' => true ] );

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

    /*public function printer($filter)
    {

        //$data =  json_decode($filter);

        $values = json_decode($filter);

        $plantilla = 'Cuentas.cobroservicio_print';
        $view = \View::make($plantilla, compact('values'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream("estado_resultados");

    }*/

    public function printer(Request $request)
    {
        $values = $request->input('item');

        $plantilla = 'Cuentas.cobroservicio_print';
        $view = \View::make($plantilla, compact('values'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        if (! is_dir(public_path().'/uploads/')){
            mkdir(public_path().'/uploads/');
        }

        if (! is_dir(public_path().'/uploads/cobroservicios/')){
            mkdir(public_path().'/uploads/cobroservicios/');
        }



        $pdf->setPaper('a4', 'portrait')->save(public_path() . '/uploads/factura/' . $values['idcobroservicio'] . '.pdf');

        return response()->json(['success' => true, 'url' => 'uploads/factura/' . $values['idcobroservicio'] . '.pdf']);
    }
}
