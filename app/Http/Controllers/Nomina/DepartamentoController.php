<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Nomina\Cargo;
use App\Modelos\Nomina\Departamento;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Nomina.index_departamento');
    }


    /**
     * Obtener todos los cargos de manera ascendente
     *
     * @return mixed
     */
    public function getDepartamentos(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cargo = null;

        if ($search != null) {
            $cargo = Departamento::whereRaw("departamento.namedepartamento ILIKE '%" . $search . "%'")->orderBy('namedepartamento', 'asc');
        } else {
            $cargo = Departamento::orderBy('namedepartamento', 'asc');
        }

        return $cargo->paginate(10);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartamentoByID($id)
    {
        return Departamento::where('iddepartamento', $id)->get();
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
        $count = Departamento::where('namedepartamento', $request->input('namedepartamento'))->count();

        if ($count > 0) {
            return response()->json(['success' => false]);
        } else {
            $cargo = new Departamento();
            $cargo->namedepartamento = $request->input('namedepartamento');
            $cargo->centrocosto = $request->input('centrocosto');

            if ($cargo->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
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
        $count = Departamento::where('namedepartamento', $request->input('namedepartamento'))->count();

        if ($count > 0) {
            return response()->json(['success' => false, 'repeat' => true]);
        } else {
            $departamento = Departamento::find($id);
            $departamento->namedepartamento = $request->input('namedepartamento');
            if ($departamento->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'repeat' => false]);
            }
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
        $count = Cargo::where('iddepartamento',$id)->count();
        if ($count > 0) {
            return response()->json(['success' => false, 'exists' => true]);
        } else {
            $departamento = Departamento::find($id);

            if ($departamento->delete()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'exists' => false]);
            }
        }
    }
}
