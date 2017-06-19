<?php

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Sectores\Calle;
use App\Modelos\Servicios\ServicioAguaPotable;
use App\Modelos\Suministros\Producto;

use App\Modelos\Suministros\Suministro;
use Illuminate\Support\Facades\Session;


class SuministroController extends Controller
{
    public function index(){
        return view('Suministros/index');
	}

    public function getsuministros()
    {
       return Suministro::with('cliente.persona', 'calle.barrio', 'cont_documentoventa.cont_puntoventa.sri_establecimiento')
                                    ->orderBy('idsuministro', 'asc')->get();
    }

    public function getSuministrosByBarrio($filter)
    {
        $object_filter = json_decode($filter);
        $suministro = Suministro::with(['cliente',
                            'calle' => function ($query_calle) use ($object_filter) {
                                $result_calle = $query_calle->with([
                                    'barrio' => function ($query_barrio) use ($object_filter) {

                                        if ($object_filter->barrio != 0) {
                                            $query_barrio->where('idbarrio', $object_filter->barrio);
                                        }
                                    }
                                ]);
                                if ($object_filter->calle != 0) {
                                    return $result_calle->where('idcalle', $object_filter->calle);
                                }
                            }
                        ]);

        return $suministro->get();
    }

    public function getSuministrosByCalle($id)
    {
        return $sumi = Suministro::with('cliente', 'calle.barrio')->where('idcalle', $id)->orderBy('idsuministro', 'asc')->get();
    }


    public function getAguapotable()
    {
        return ServicioAguaPotable::orderBy('nombreservicio', 'asc')->get();
    }

    public function getCalleByBarrio($id)
    {
        return Calle::with('barrio')->where('idbarrio', $id)->orderBy('namecalle', 'asc')->get();
    }

    public function getCalle()
    {
        return Calle::orderBy('nombrecalle', 'asc')->get();
    }

    public function suministroById($id)
    {
        return Suministro::with('cliente.persona', 'calle.barrio')->where('idsuministro', $id)->orderBy('idsuministro')->get();
    }


    public function getSuministroForFactura($id)
    {
        $suministro = Suministro::with('cliente.persona', 'calle.barrio', 'cont_catalogitem')
                                    ->where('idsuministro', $id)->orderBy('idsuministro')->get();

        $_SESSION['suministro_to_facturar'] = $suministro;

        Session::put('suministro_to_facturar', $suministro);

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
        $suministro = Suministro::find($id);

        $suministro->idcalle = $request->input('idcalle');
        $suministro->direccionsumnistro = $request->input('direccionsuministro');
        $suministro->telefonosuministro = $request->input('telefonosuministro');
        $suministro->save();

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