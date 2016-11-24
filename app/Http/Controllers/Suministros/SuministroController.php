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


class SuministroController extends Controller
{
    public function index(){
        return view('Suministros/index');
	}

    public function getsuministros()
    {
       return Suministro::with('cliente', 'calle.barrio', 'producto')->orderBy('numerosuministro', 'asc')->get();
    }

    public function getSuministrosByBarrio($id)
    {
        $calles = Calle::with('barrio')->where('idbarrio', $id)->count();
        if($calles > 0)
        {
            $calless = Calle::with('barrio')->where('idbarrio', $id)->get();

            $array_sumi = [];

            foreach ($calless as $callee){
                $result = Suministro::with('cliente', 'calle.barrio', 'producto')->where('idcalle', $callee->idcalle)->orderBy('numerosuministro', 'asc')->get();
            }
            return $result;
        }
    }

    public function getSuministrosByCalle($id)
    {
        return $sumi = Suministro::with('cliente', 'calle.barrio', 'producto')->where('idcalle', $id)->orderBy('numerosuministro', 'asc')->get();
    }


    public function getAguapotable()
    {
        return ServicioAguaPotable::orderBy('nombreservicio', 'asc')->get();
    }

    public function getCalleByBarrio($id)
    {
        return Calle::with('barrio')->where('idbarrio', $id)->orderBy('nombrecalle', 'asc')->get();
    }

    public function getCalle()
    {
        return Calle::orderBy('nombrecalle', 'asc')->get();
    }

    public function suministroById($id)
    {
        return Suministro::with('cliente', 'calle.barrio', 'producto')->where('numerosuministro', $id)->orderBy('numerosuministro')->get();
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
        $calle = new Calle();

        $calle->idbarrio = $request->input('idbarrio');
        $calle->nombrecalle = $request->input('nombrecalle');
        $calle->observacion = $request->input('observacion');
        $calle->fechaingreso = date('Y-m-d');

        $calle->save();

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
       $aux =  Canal::where ('idcalle',$id)->count('idcanal');

        if ($aux > 0){
            return response()->json(['success' => false, 'msg' => 'exist_canales']);
        } else {
            $calle = Calle::find($id);
            $calle->delete();
            return response()->json(['success' => true]);
        }
    }

        
 }
       