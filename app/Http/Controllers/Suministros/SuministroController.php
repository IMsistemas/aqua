<?php

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Modelos\Sectores\Calle;
use App\Modelos\Suministros\Suministro;


class SuministroController extends Controller
{
    public function index(){
        return view('Suministros/index');
	}

    public function getsuministros()
    {
       return Suministro::with('cliente', 'calle', 'producto', 'servicioaguapotable')->orderBy('numerosuministro', 'asc')->get();
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


    public function editar_calle(Request $request)
    {
        $callea = $request->input('arr_calle');

        foreach ($callea as $item) {
            $calle1 = Calle::find($item['idcalle']);

            $calle1->nombrecalle = $item['nombrecalle'];

            $calle1->save();
        }
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
       