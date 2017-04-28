<?php

namespace App\Http\Controllers\Compras;

use App\Modelos\Configuracion\ConfiguracionSystem;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_FormaPago;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Persona;
use App\Modelos\Proveedores\Proveedor;
use App\Modelos\SRI\SRI_Sustento_Comprobante;
use App\Modelos\SRI\SRI_SustentoTributario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('compras.index');
    }

    public function getProveedorByFilter()
    {
        return Proveedor::with('persona')->get();
    }

    public function getBodegas()
    {
        return Cont_Bodega::orderBy('namebodega', 'asc')->get();
    }

    public function getSustentoTributario()
    {
        return SRI_SustentoTributario::orderBy('namesustento', 'asc')->get();
    }

    public function getTipoComprobante($idsustento)
    {
        return SRI_Sustento_Comprobante::join('sri_tipocomprobante', 'sri_sustento_comprobante.idtipocomprobante', '=', 'sri_tipocomprobante.idtipocomprobante')
                                        ->where('idsustentotributario', $idsustento)
                                        ->select('sri_tipocomprobante.idtipocomprobante', 'sri_tipocomprobante.namecomprobante')
                                        ->orderBy('sri_tipocomprobante.namecomprobante', 'asc')->get();
    }

    public function getFormaPago()
    {
        return Cont_FormaPago::orderBy('nameformapago', 'asc')->get();
    }

    public function getProveedorByIdentify($identify)
    {
        return Persona::with('proveedor.sri_tipoimpuestoiva', 'proveedor.cont_plancuenta')
                        ->whereRaw("numdocidentific::text ILIKE '%" . $identify . "%'")
                        ->whereRaw('idpersona IN (SELECT idpersona FROM proveedor)')
                        ->get();
    }

    /**
     * Obtener configuracion contable
     *
     *
     * @return mixed
     */
    public function getCofiguracioncontable()
    {
        //return   configuracioncontable::all();
        $aux_data= ConfiguracionSystem::whereRaw(" optionname='CONT_IRBPNR_COMPRA' OR optionname='SRI_RETEN_IVA_COMPRA' OR optionname='CONT_PROPINA_COMPRA' OR optionname='SRI_RETEN_RENTA_COMPRA' OR optionname='CONT_IVA_COMPRA' OR optionname='CONT_ICE_COMPRA' ")->get();
        $aux_configcontable=array();
        foreach ($aux_data as $i) {
            $aux_contable="";
            if($i->optionvalue!=""){
                $aux_contable=Cont_PlanCuenta::whereRaw("idplancuenta=".$i->optionvalue." ")->get();
            }
            $configventa = array(
                'Id' => $i->idconfiguracionsystem,
                'IdContable'=> $i->optionvalue,
                'Descripcion'=>$i->optionname,
                'Contabilidad'=>$aux_contable );
            array_push($aux_configcontable, $configventa);
        }
        return $aux_configcontable;

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
        //
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
        //
    }
}
