<?php

namespace App\Http\Controllers\ConfiguracionSystem;

use App\Modelos\Configuracion\ConfigNomina;
use App\Modelos\Nomina\ConceptoPago;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConfigNominaController extends Controller
{

    public function getConfigNomina()
    {
        return ConfigNomina::orderBy('id_conceptospago', 'asc')->get();
    }

    public function getConceptos()
    {
        return ConceptoPago::with('confignomina')->orderBy('id_conceptospago', 'asc')->get();
    }

    /**
     * Almacenar la Configuracion de la Nomina
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $array = $request->input('conceptos');

        foreach ($array as $item) {
            $cant_concepto = ConfigNomina::where('id_conceptospago',$item['id_conceptospago'])->count();

            if ($cant_concepto === 0){
                $concepto = new ConfigNomina();
                $concepto->id_conceptospago = $item['id_conceptospago'];
                if($item['idcuenta1']!== ''){
                    $concepto->cuenta = $item['idcuenta'] . ',' . $item['idcuenta1'];
                }else{
                    $concepto->cuenta = $item['idcuenta'];
                }
                $concepto->value_imp = $item['impuesto'];
            }else{
                $config = ConfigNomina::where('id_conceptospago',$item['id_conceptospago'])->get();
                $concepto = ConfigNomina::find($config[0]->id_confignomina);

                $concepto->id_conceptospago = $item['id_conceptospago'];
                if($item['idcuenta1']!== ''){
                    $concepto->cuenta = $item['idcuenta'] . ',' . $item['idcuenta1'];
                }else{
                    $concepto->cuenta = $item['idcuenta'];
                }
                $concepto->value_imp = $item['impuesto'];
            }

            if (! $concepto->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

    }

    /**
     * Actualizar la Configuracion de la Nomina
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

    }


}
