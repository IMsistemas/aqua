<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Nomina\Cargo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CargoController extends Controller
{
    /**
     * Mostrar una lista de los recursos de cargos
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Nomina.index_cargo');
    }

    /**
     * Obtener todos los cargos de manera ascendente
     *
     * @return mixed
     */
    public function getCargos()
    {
        return Cargo::orderBy('idcargo', 'asc')->get();
    }

    /**
     * Obtener el ultimo ID insertado y sumarle 1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastID()
    {
        $lastID = Cargo::max('idcargo');

        if ($lastID == ''){
            $lastID = 'CAR01';
        } else {
            $lastID = trim($lastID);
            $str_to_array = explode('CAR', $lastID);
            $lastID = $str_to_array[1];

            settype($lastID, 'integer');
            $lastID = $lastID + 1;

            $lastID = (strlen($lastID) == 1) ? 'CAR0' . $lastID : 'CAR' . $lastID;
        }

        return response()->json(['lastId' => $lastID]);
    }

    /**
     * Obtener los cargos filtrados
     *
     * @param $filter
     * @return mixed
     */
    public function getByFilter($filter)
    {
        $filter = json_decode($filter);

        return Cargo::orderBy('idcargo', 'asc')
                      ->whereRaw("cargo.idcargo LIKE '%" . $filter->text . "%' OR cargo.nombrecargo LIKE '%" . $filter->text . "%' ")
                      ->get();
    }

    /**
     * Almacenar un recurso cargo recién creado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = Cargo::create($request->all());
        return ($result) ? response()->json(['success' => true]) : response()->json(['success' => false]);
    }

    /**
     * Mostrar un recurso cargo especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cargo = Cargo::find($id);
        return response()->json($cargo);
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
        $cargo = Cargo::find($id);
        $cargo->fill($request->all());
        $cargo->save();
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
        $cargo = Cargo::find($id);
        $cargo->delete();
        return response()->json(['success' => true]);
    }
}
