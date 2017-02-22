<?php

namespace App\Http\Controllers\Sectores;

use App\Modelos\Sectores\Barrio;
use App\Modelos\Sectores\Parroquia;
use App\Modelos\Tomas\Calle;
use App\Modelos\Tomas\Canal;
use App\Modelos\Tomas\Derivacion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BarrioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Sectores/index_barrio');
    }

    public function llenar_tabla($data)
    {
        $calles = json_decode($data);

        $array_aux = [];

        foreach ($calles->calles as $idcalle) {
            $result = Canal::where('idcalle', $idcalle->idcalle)->orderBy('nombrecanal', 'asc')->get();
            //$t = ['idcalle' => $idcalle->idcalle, 'canales' => $result];
            $t = ['idcalle' => $idcalle->nombrecalle, 'canales' => $result];
            $array_aux[] = $t;
        }
        return $array_aux;
    }

    public function getBarrios()
    {
        //return Barrio::orderBy('nombrebarrio', 'asc')->get();
        return Barrio::with('calle')->orderBy('namebarrio', 'asc')->get();
    }

    public function getBarrio()
    {
        return Barrio::orderBy('namebarrio', 'asc')->get();
    }

    public function getBarrio_ID($id)
    {
        return Barrio::where('idbarrio', $id)->orderBy('namebarrio', 'asc')->get();
    }


    public function getCanals($data)
    {
        $calles = json_decode($data);

        $array_calles = [];

        foreach ($calles->idcalles as $idcalle){
            $result = Canal::where('idcalle', $idcalle)->orderBy('nombrecanal', 'asc')->get();
            if (is_array($result)){
                $array_calles = array_merge($array_calles, $result);
            } else if (is_object($result)) {
                $array_calles[] = $result;
            }
        }
        return $array_calles;
    }

    public function getderivaciones($data)
    {
        $id_canales = json_decode($data);

        $array_derivaciones = [];

        foreach ($id_canales->idcanales as $idcanal){
            $result = Derivacion::where('idcanal', $idcanal)->orderBy('nombrederivacion', 'asc')->get();
            $array_derivaciones[] = $result;
        }
        return $array_derivaciones;

    }


    public function getLastID()
    {
        $max_barrio = Barrio::max('idbarrio');

        if ($max_barrio != null){
            $max_barrio += 1;
        } else {
            $max_barrio = 1;
        }

        return response()->json(['id' => $max_barrio]);
    }


    public function getParroquias()
    {
        return Parroquia::orderBy('nameparroquia', 'asc')->get();
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
        $barrio = new Barrio();

        $barrio->idparroquia = $request->input('idparroquia');
        $barrio->namebarrio = $request->input('nombrebarrio');
        //$barrio->observacion = $request->input('observacion');
        //$barrio->fechaingreso = date('Y-m-d');

        $barrio->save();

        return response()->json(['success' => true]);
    }

    public function editar_barrio(Request $request)
    {
        $barrioa = $request->input('arr_barrio');

        foreach ($barrioa as $item) {
            $barri = Barrio::find($item['idbarrio']);

            $barri->namebarrio = $item['namebarrio'];

            $barri->save();
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

        $aux =  Calle::where ('idbarrio',$id)->count('idcalle');

        if ($aux > 0){
            return response()->json(['success' => false, 'msg' => 'exist_calle']);
        } else {
            $barrio = Barrio::find($id);
            $barrio->delete();
            return response()->json(['success' => true]);
        }





    }
}
