<?php

namespace App\Http\Controllers\Tarifas;

use App\Modelos\Suministros\Suministro;
use App\Modelos\Tarifas\TarifaAguaPotable;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TarifaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Tarifas.index');
    }


    /**
     * Obtener todos las Tarifas de manera ascendente
     *
     * @return mixed
     */
    public function getTarifas(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;
        $cargo = null;

        if ($search != null) {
            $cargo = TarifaAguaPotable::whereRaw("nametarifaaguapotable ILIKE '%" . $search . "%'")->orderBy('nametarifaaguapotable', 'asc');
        } else {
            $cargo = TarifaAguaPotable::orderBy('nametarifaaguapotable', 'asc');
        }

        return $cargo->paginate(10);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarifaByID($id)
    {
        return TarifaAguaPotable::where('idtarifaaguapotable', $id)->get();
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
        $count = TarifaAguaPotable::where('nametarifaaguapotable', $request->input('nametarifaaguapotable'))->count();

        if ($count > 0) {
            return response()->json(['success' => false]);
        } else {
            $cargo = new TarifaAguaPotable();
            $cargo->nametarifaaguapotable = $request->input('nametarifaaguapotable');

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
        $count = TarifaAguaPotable::where('nametarifaaguapotable', $request->input('nametarifaaguapotable'))
            ->where('idtarifaaguapotable', '!=', $id)->count();

        if ($count > 0) {

            return response()->json(['success' => false, 'repeat' => true]);

        } else {

            $departamento = TarifaAguaPotable::find($id);
            $departamento->nametarifaaguapotable = $request->input('nametarifaaguapotable');

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
        $count = Suministro::where('idtarifaaguapotable',$id)->count();
        if ($count > 0) {
            return response()->json(['success' => false, 'exists' => true]);
        } else {
            $departamento = TarifaAguaPotable::find($id);

            if ($departamento->delete()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'exists' => false]);
            }
        }
    }
}
