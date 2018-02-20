<?php

namespace App\Http\Controllers\Tarifas;

use App\Modelos\Suministros\Suministro;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use App\Modelos\Tarifas\TarifaAguaPotable;
use App\Modelos\Tarifas\TarifaRubro;
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



    public function getTarifaBasica($id)
    {
        return CostoTarifa::where('idtarifaaguapotable', $id)->get();
    }

    public function getExcedente($id)
    {
        return ExcedenteTarifa::where('idtarifaaguapotable', $id)->get();
    }

    public function getRubro($id)
    {
        return TarifaRubro::where('idtarifaaguapotable', $id)->get();
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

    public function updateParams(Request $request, $id)
    {
        $result0 = CostoTarifa::where('idtarifaaguapotable', $id)->delete();

        if ($result0 !== false) {

            foreach ($request->input('basica') as $item) {

                $costotarifa = new CostoTarifa();
                $costotarifa->idtarifaaguapotable = $id;
                $costotarifa->apartirdenm3 = $item['apartirdenm3'];
                $costotarifa->valortarifa = $item['valortarifa'];

                if ($costotarifa->save() == false) {
                    return response()->json(['success' => false, 'who' => 'insertbasica']);
                }

            }

            $result1 = ExcedenteTarifa::where('idtarifaaguapotable', $id)->delete();

            if ($result1 !== false) {

                foreach ($request->input('excedente') as $item) {

                    $excedente = new ExcedenteTarifa();
                    $excedente->idtarifaaguapotable = $id;
                    $excedente->desdenm3 = $item['desdenm3'];
                    $excedente->valorexcedente = $item['valorexcedente'];

                    if ($excedente->save() == false) {
                        return response()->json(['success' => false, 'who' => 'insertexcedente']);
                    }

                }

                $result2 = TarifaRubro::where('idtarifaaguapotable', $id)->delete();

                if ($result2 !== false) {

                    $rubros = new TarifaRubro();
                    $rubros->idtarifaaguapotable = $id;
                    $rubros->alcantarillado = $request->input('alcantarillado');
                    $rubros->desechosolido = $request->input('ddss');
                    $rubros->medioambiente = $request->input('ma');

                    if ($rubros->save() == false) {
                        return response()->json(['success' => false, 'who' => 'insertrubro']);
                    }

                    return response()->json(['success' => true]);

                } else return response()->json(['success' => false, 'who' => 'deleterubros']);



            } else return response()->json(['success' => false, 'who' => 'deleteexcedente']);

        } else return response()->json(['success' => false, 'who' => 'deletebasica']);
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

            $tarifarubro = TarifaRubro::where('idtarifaaguapotable', $id)->delete();
            $costotarifa = CostoTarifa::where('idtarifaaguapotable', $id)->delete();
            $excedente = ExcedenteTarifa::where('idtarifaaguapotable', $id)->delete();

            $departamento = TarifaAguaPotable::find($id);

            if ($departamento->delete()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'exists' => false]);
            }
        }
    }
}
