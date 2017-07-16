<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Nomina\Cargo;
use App\Modelos\Nomina\Departamento;
use App\Modelos\Nomina\Empleado;
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
    public function getCargos(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cargo = null;

        if ($search != null) {
            $cargo = Cargo::with('departamento')->whereRaw("cargo.namecargo ILIKE '%" . $search . "%'")
                                ->orderBy('namecargo', 'asc');
        } else {
            $cargo = Cargo::with('departamento')->orderBy('namecargo', 'asc');
        }

        return $cargo->paginate(10);
    }

    public function getExistDepartament()
    {
        $result = Departamento::count();

        if ($result === 0) {
            return response()->json(['success' => false]);
        } else {
            return response()->json(['success' => true]);
        }
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCargoByID($id)
    {
        return Cargo::where('idcargo', $id)->orderBy('namecargo')->get();
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
                      ->whereRaw("cargo.namecargo ILIKE '%" . $filter->text . "%' ")
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
        $cargo1 = Cargo::where('namecargo', $request->input('nombrecargo'))->count();

        if ($cargo1 > 0) {
            return response()->json(['success' => false]);
        } else {
            $cargo = new Cargo();
            $cargo->namecargo = $request->input('nombrecargo');
            $cargo->iddepartamento = $request->input('iddepartamento');

            if ($cargo->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
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

        $cargo->namecargo = $request->input('nombrecargo');
        $cargo->iddepartamento = $request->input('iddepartamento');

        if ($cargo->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empleado = Empleado::where('idcargo',$id)->count();
        if ($empleado > 0) {
            return response()->json(['success' => false]);
        } else {
            $cargo = Cargo::find($id);
            $cargo->delete();
            return response()->json(['success' => true]);
        }
    }
}
