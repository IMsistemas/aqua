<?php

namespace App\Http\Controllers\ConfiguracionSystem;

use App\Modelos\Configuracion\ConfigNomina;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConfigNominaController extends Controller
{

    /**
     * Almacenar la Configuracion de la Nomina
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /*$array = $request->input('array_data');

        foreach ($array as $item) {
            $configuracion = ConfigNomina::find($item['id_conceptospago']);

            if ($item['optionvalue'] == '' || $item['optionvalue'] == null) {
                $configuracion->optionvalue = null;
            } else {
                $configuracion->optionvalue = $item['optionvalue'];
            }


            if (! $configuracion->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);*/
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
