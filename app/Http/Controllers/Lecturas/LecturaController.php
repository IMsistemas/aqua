<?php

namespace App\Http\Controllers\Lecturas;

use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Lecturas\Lectura;
use App\Modelos\Tarifas\CostoTarifa;
use App\Modelos\Tarifas\ExcedenteTarifa;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LecturaController extends Controller
{

    public function index()
    {
        return view('Lecturas.index_nuevaLectura');
    }

    public function getLastID()
    {
        $last_id = Lectura::max('idlectura');

        if ($last_id == 0){
            $last_id = 1;
        } else {
            $last_id += 1;
        }

        return response()->json(['lastID' => $last_id]);
    }

    public function show($id)
    {
        $lectura = Lectura::join('suministro', 'lectura.numerosuministro', '=', 'suministro.numerosuministro')
                            ->join('tarifa', 'suministro.idtarifa', '=', 'tarifa.idtarifa')
                            ->join('cliente', 'suministro.documentoidentidad', '=', 'cliente.documentoidentidad')
                            ->join('calle', 'suministro.idcalle', '=', 'calle.idcalle')
                            ->join('barrio', 'calle.idbarrio', '=', 'barrio.idbarrio')
                            ->select('tarifa.nombretarifa', 'calle.nombrecalle', 'barrio.nombrebarrio',
                                        'cliente.apellido', 'cliente.nombre', 'lectura.lecturaactual', 'tarifa.idtarifa')
                            ->where('lectura.numerosuministro', '=', $id)
                            ->orderBy('idlectura', 'desc')
                            ->take(1)
                            ->get();

        return response()->json($lectura);
    }

    public function getRubros()
    {
        return RubroFijo::all();
    }

    public function getRubrosValue($consumo, $tarifa)
    {
        $tarifabasica = DB::table('costotarifa')
                                ->select(DB::raw('MAX(valorconsumo) AS valorconsumo'))
                                ->where([
                                    ['idtarifa', '=', $tarifa], ['apartirdenm3', '<', $consumo]
                                ])->get();

        $value_tarifa_excedente = DB::table('excedentetarifa')
                                ->select(DB::raw('MAX(valorconsumo) AS valorconsumo'))
                                ->where([
                                    ['idtarifa', '=', $tarifa], ['desdenm3', '<=', $consumo]
                                ])->get();

        if($tarifabasica[0]->valorconsumo == null || $tarifabasica[0]->valorconsumo == ''){
            $tarifabasica[0]->valorconsumo = 0;
        }

        if ($value_tarifa_excedente == null || $value_tarifa_excedente == 0){
            $excedente = 0;
        } else {
            $excedente = ($consumo - 15) * $value_tarifa_excedente[0]->valorconsumo;
        }

        $medioambiente = RubroFijo::find(1)->valorrubro;

        $object_alcantarillado = RubroFijo::find(2)->valorrubro;
        $alcantarillado = ($tarifabasica[0]->valorconsumo + $excedente) * $object_alcantarillado;

        $object_ddss = RubroFijo::find(3)->valorrubro;
        $ddss = ($tarifabasica[0]->valorconsumo + $excedente) * $object_ddss;

        return response()->json([
            'tarifabasica' => $tarifabasica[0]->valorconsumo,
            'excedente' => $excedente,
            'medioambiente' => $medioambiente,
            'alcantarillado' => $alcantarillado,
            'ddss' => $ddss
        ]);
    }

    public function store(Request $request)
    {

    }

}
