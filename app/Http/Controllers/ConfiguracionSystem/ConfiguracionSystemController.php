<?php

namespace App\Http\Controllers\ConfiguracionSystem;

use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\SRI\SRI_Establecimiento;
use App\Modelos\SRI\SRI_TipoAmbiente;
use App\Modelos\SRI\SRI_TipoEmision;
use Illuminate\Http\Request;
use App\Modelos\SRI\SRI_TipoImpuestoIva;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConfiguracionSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ConfiguracionSystem.index');
    }


    public function getDataEmpresa()
    {
        return SRI_Establecimiento::get();
    }

    public function getConfigurations()
    {
        return ConfiguracionSystem::get();
    }

    public function getIVADefault()
    {
        return ConfiguracionSystem::where('optionname', 'SRI_IVA_DEFAULT')->get();
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url_file = null;

        if ($request->hasFile('rutalogo')) {
            $image = $request->file('rutalogo');
            $destinationPath = public_path() . '/uploads/configuracion';
            $name = rand(0, 9999) . '_' . $image->getClientOriginalName();
            if (!$image->move($destinationPath, $name)) {
                return response()->json(['success' => false]);
            } else {
                $url_file = '/uploads/configuracion/' . $name;
            }
        }

        $configuracion = new SRI_Establecimiento();

        $configuracion->ruc = $request->input('ruc');
        $configuracion->razonsocial = $request->input('razonsocial');
        $configuracion->nombrecomercial = $request->input('nombrecomercial');
        $configuracion->direccionestablecimiento = $request->input('direccionestablecimiento');
        $configuracion->rutalogo = $request->input('rutalogo');

        if ($request->input('contribuyenteespecial') == '') {
            $configuracion->contribuyenteespecial = null;
        } else {
            $configuracion->contribuyenteespecial = $request->input('contribuyenteespecial');
        }

        if ($request->input('obligadocontabilidad') == '1') {
            $configuracion->obligadocontabilidad = true;
        } else {
            $configuracion->obligadocontabilidad = false;
        }

        if ($url_file != null) {
            $configuracion->rutalogo = $url_file;
        }

        if ($configuracion->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateEstablecimiento(Request $request, $id)
    {

        $url_file = null;

        if ($request->hasFile('rutalogo')) {
            $image = $request->file('rutalogo');
            $destinationPath = public_path() . '/uploads/configuracion';
            $name = rand(0, 9999) . '_' . $image->getClientOriginalName();
            if (!$image->move($destinationPath, $name)) {
                return response()->json(['success' => false]);
            } else {
                $url_file = '/uploads/configuracion/' . $name;
            }
        }

        $configuracion = SRI_Establecimiento::find($id);

        $configuracion->ruc = $request->input('ruc');
        $configuracion->razonsocial = $request->input('razonsocial');
        $configuracion->nombrecomercial = $request->input('nombrecomercial');
        $configuracion->direccionestablecimiento = $request->input('direccionestablecimiento');
        $configuracion->rutalogo = $request->input('rutalogo');

        if ($request->input('contribuyenteespecial') == '') {
            $configuracion->contribuyenteespecial = null;
        } else {
            $configuracion->contribuyenteespecial = $request->input('contribuyenteespecial');
        }

        if ($request->input('obligadocontabilidad') == '1') {
            $configuracion->obligadocontabilidad = true;
        } else {
            $configuracion->obligadocontabilidad = false;
        }

        if ($url_file != null) {
            $configuracion->rutalogo = $url_file;
        }

        if ($configuracion->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }

    /**
     * Obtener los valores para seleccionar configuración
     */


    public function getImpuestoIVA()
    {
        return SRI_TipoImpuestoIva::orderBy('nametipoimpuestoiva', 'asc')->get();
    }

    public function getPlanCuenta()
    {
        return Cont_PlanCuenta::selectRaw('
                 * , (SELECT count(*)  FROM cont_plancuenta aux WHERE aux.jerarquia <@ cont_plancuenta.jerarquia) AS madreohija
            ')->orderBy('jerarquia', 'asc')->get();
    }

    public function updateIvaDefault(Request $request, $id)
    {

        $configuracion = ConfiguracionSystem::find($id);

        $configuracion->optionvalue = $request->input('optionvalue');


        if ($configuracion->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }


    public function getConfigCompra()
    {
        return ConfiguracionSystem::where('optionname','CONT_IVA_COMPRA')
            ->orWhere('optionname','CONT_ICE_COMPRA')
            ->orWhere('optionname','CONT_IRBPNR_COMPRA')
            ->orWhere('optionname','CONT_PROPINA_COMPRA')
            ->orWhere('optionname','SRI_RETEN_IVA_COMPRA')
            ->orWhere('optionname','SRI_RETEN_RENTA_COMPRA')
            ->selectRaw('*, (SELECT concepto FROM cont_plancuenta WHERE cont_plancuenta.idplancuenta = (configuracionsystem.optionvalue)::INT) ')
            ->get();

    }

    public function updateConfigCompra(Request $request, $id)
    {
        $array_option = $request->input('array_data');

        foreach ($array_option as $item) {
            $configuracion = ConfiguracionSystem::find($item['idconfiguracionsystem']);
            if($configuracion->optionvalue == ""){
                $configuracion->optionvalue = null;
            }
            $configuracion->optionvalue = $item['optionvalue'];

            if (! $configuracion->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);
    }


    public function getConfigVenta()
    {
        return ConfiguracionSystem::where('optionname','CONT_IVA_VENTA')
            ->orWhere('optionname','CONT_ICE_VENTA')
            ->orWhere('optionname','CONT_IRBPNR_VENTA')
            ->orWhere('optionname','CONT_PROPINA_VENTA')
            ->orWhere('optionname','CONT_COSTO_VENTA')
            ->orWhere('optionname','SRI_RETEN_IVA_VENTA')
            ->orWhere('optionname','SRI_RETEN_RENTA_VENTA')
            ->selectRaw('*, (SELECT concepto FROM cont_plancuenta WHERE cont_plancuenta.idplancuenta = (configuracionsystem.optionvalue)::INT) ')
            ->get();
    }

    public function updateConfigVenta(Request $request, $id)
    {
        $array_option = $request->input('array_data');

        foreach ($array_option as $item) {
            $configuracion = ConfiguracionSystem::find($item['idconfiguracionsystem']);
            if($configuracion->optionvalue == ""){
                $configuracion->optionvalue = null;
            }
            $configuracion->optionvalue = $item['optionvalue'];

            if (! $configuracion->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);
    }


    public function getConfigNC()
    {
        return ConfiguracionSystem::where('optionname','CONT_IVA_NC')
            ->orWhere('optionname','CONT_ICE_NC')
            ->orWhere('optionname','CONT_IRBPNR_NC')
            ->orWhere('optionname','CONT_PROPINA_NC')
            ->orWhere('optionname','SRI_RETEN_IVA_NC')
            ->orWhere('optionname','SRI_RETEN_RENTA_NC')
            ->selectRaw('*, (SELECT concepto FROM cont_plancuenta WHERE cont_plancuenta.idplancuenta = (configuracionsystem.optionvalue)::INT) ')
            ->get();
    }

    public function updateConfigNC(Request $request, $id)
    {
        $array_option = $request->input('array_data');

        foreach ($array_option as $item) {
            $configuracion = ConfiguracionSystem::find($item['idconfiguracionsystem']);
            if($configuracion->optionvalue == ""){
                $configuracion->optionvalue = null;
            }
            $configuracion->optionvalue = $item['optionvalue'];

            if (! $configuracion->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);
    }


    public function getConfigEspecifica()
    {

        //-----PARA SISTEMA PISQUE (RIEGO)------------------------------------------------

        //return ConfiguracionSystem::where('optionname','PISQUE_CONSTANTE')->get();

        //-----PARA SISTEMA AYORA (POTABLE)-----------------------------------------------

        return ConfiguracionSystem::where('optionname','AYORA_DIVIDENDO')
            ->orWhere('optionname','AYORA_TASAINTERES')
            ->get();

    }

    public function updateConfigEspecifica(Request $request, $id)
    {
        $array_option = $request->input('array_data');

        foreach ($array_option as $item) {
            $configuracion = ConfiguracionSystem::find($item['idconfiguracionsystem']);
            $configuracion->optionvalue = $item['optionvalue'];

            if (! $configuracion->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);
    }


    public function getTipoEmision()
    {
        return SRI_TipoEmision::orderBy('nametipoemision', 'asc')->get();
    }

    public function getTipoAmbiente()
    {
        return SRI_TipoAmbiente::orderBy('nametipoambiente', 'asc')->get();
    }

    public function getConfigSRI()
    {
        return ConfiguracionSystem::where('optionname','SRI_TIPO_AMBIENTE')
                                    ->orWhere('optionname','SRI_TIPO_EMISION')
                                    ->get();
    }

    public function updateConfigSRI(Request $request, $id)
    {
        $array_option = $request->input('array_data');

        foreach ($array_option as $item) {
            $configuracion = ConfiguracionSystem::find($item['idconfiguracionsystem']);
            $configuracion->optionvalue = $item['optionvalue'];

            if (! $configuracion->save()) {
                return response()->json(['success' => false]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}



