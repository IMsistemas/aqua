<?php

namespace App\Http\Controllers\ActivosFijos;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modelos\Contabilidad\Cont_ItemCompra;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Persona;
use App\Modelos\Nomina\empleado;
use App\Modelos\Contabilidad\Cont_Detalleitemactivofijo;
use App\Modelos\Contabilidad\Cont_incidenciaaf;
use App\Modelos\Contabilidad\Cont_mantencionaf;
use App\Modelos\Contabilidad\Cont_tipomantencionaf;
use App\Modelos\Contabilidad\Cont_Trasladoaf;
use App\Modelos\Contabilidad\Cont_conceptobajaaf;
use App\Modelos\Contabilidad\Cont_bajaaf;
use App\Modelos\Contabilidad\Cont_registroactivofijo;
use DB;
//use StdClass;
use App\Http\Controllers\Contabilidad\CoreContabilidad;





class depreciacionActivosFijosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('Activosfijos/depreciacionActivosFijos');
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
    public function store(Request $request, $numero)
    {

        if ($numero ==  1) {
  
            $AltaActivoFijo = new Cont_detalleitemactivofijo($request->all());

            $AltaActivoFijo->iditemactivofijo = $request->input('iditemactivofijo');
            $AltaActivoFijo->iditemcompra = $request->input('iditemcompra');
            $AltaActivoFijo->idempleado = $request->input('idempleado');
            $AltaActivoFijo->idplancuentadepreciacion = $request->input('idplancuentadepreciacion');
            $AltaActivoFijo->idplancuentagasto = $request->input('idplancuentagasto');
            $AltaActivoFijo->numactivo = $request->input('numactivo');
            $AltaActivoFijo->vidautil = $request->input('vidautil');
            $AltaActivoFijo->fechaalta = $request->input('fechaalta');
            $AltaActivoFijo->valorsalvamento = $request->input('valorsalvamento');
            $AltaActivoFijo->precioventa = $request->input('precioventa');
            $AltaActivoFijo->estado = $request->input('estado');
            $AltaActivoFijo->ubicacion = $request->input('ubicacion');
            $AltaActivoFijo->observacion = $request->input('observacion');
            $AltaActivoFijo->depreciado = $request->input('depreciado');
            $AltaActivoFijo->baja = $request->input('baja');
            $AltaActivoFijo->save();
       
        }

        if ($numero == 2) {
            

            $data = $request->input('data');

            foreach ($data as $i => $value) {
            
             $GuardarIncidenciaActivoFijo = new Cont_incidenciaaf($request->all());

            $GuardarIncidenciaActivoFijo->iddetalleitemactivofijo = $data[$i]['iddetalleitemactivofijo'];
            $GuardarIncidenciaActivoFijo->descripcion = $data[$i]['descripcion'];
            $GuardarIncidenciaActivoFijo->fecha = $data[$i]['fecha'];
            $GuardarIncidenciaActivoFijo->save();

            }
        }

        if ($numero == 3) {

            $data =$request->input('data');

             foreach ($data as $i => $value) {

                $GuardarMantencionActivoFijo = new Cont_mantencionaf($request->all()); 

                $GuardarMantencionActivoFijo->iddetalleitemactivofijo = $data[$i]['iddetalleitemactivofijo'];
                $GuardarMantencionActivoFijo->observacion = $data[$i]['ObservacionMantencion'];
                $GuardarMantencionActivoFijo->idtipomantencionaf = $data[$i]['IdTipoMantencion'];
                $GuardarMantencionActivoFijo->fecha = $data[$i]['fechaMantencion'];
                $GuardarMantencionActivoFijo->save();

            }

        }

        if ($numero == 4) {

             $data =$request->input('data');
         
               

             foreach ($data as $i => $value) {

                $GuardarTrasladoActivoFijo = new Cont_trasladoaf($request->all()); 

                  //return   $data[$i];
         
                
                $GuardarTrasladoActivoFijo->idempleadoorigen = $data[$i]['IdEmpleadoOrigen'];
                $GuardarTrasladoActivoFijo->idempleadodestino = $data[$i]['IdEmpleadoDestino'];
                $GuardarTrasladoActivoFijo->fecha = $data[$i]['fechaTraslado'];
                $GuardarTrasladoActivoFijo->iddetalleitemactivofijo = $data[$i]['iddetalleitemactivofijo'];
                $GuardarTrasladoActivoFijo->save();

            }
           
        }


        if ($numero == 5  ) {

               $GuardarBajaActivoFijo = new Cont_bajaaf($request->all());          
                
                $GuardarBajaActivoFijo->idconceptobajaaf        = $request->input('idconceptobajaaf');
                $GuardarBajaActivoFijo->descripcion             = $request->input('descripcionbaja');
                $GuardarBajaActivoFijo->fecha                   =$request->input('fechabaja');
                $GuardarBajaActivoFijo->iddetalleitemactivofijo = $request->input('iddetalleitemactivofijo');
                $GuardarBajaActivoFijo->save();
           
        }

        if ($numero== 6) {
           

            $GuardarActivoFijoDepreciado = new Cont_registroactivofijo($request->all());
            $GuardarActivoFijoDepreciado ->iddetalleitemactivofijo = $request->input('iddetalleitemactivofijo');
            $GuardarActivoFijoDepreciado ->idtransaccion = $request->input('idtransaccion');
            $GuardarActivoFijoDepreciado ->fecha = $request->input('fecha');
            $GuardarActivoFijoDepreciado ->debe = $request->input('debe');
            $GuardarActivoFijoDepreciado ->haber = $request->input('haber');
            $GuardarActivoFijoDepreciado ->numerodocumento = $request->input('numerodocumento');
             $GuardarActivoFijoDepreciado ->save();

}

         if ($numero== 7) {
           

            $GuardarActivoFijoNoDepreciado = new Cont_registroactivofijo($request->all());
            $GuardarActivoFijoNoDepreciado ->iddetalleitemactivofijo = $request->input('iddetalleitemactivofijo');
            $GuardarActivoFijoNoDepreciado ->idtransaccion = $request->input('idtransaccion');
            $GuardarActivoFijoNoDepreciado ->fecha = $request->input('fecha');
            $GuardarActivoFijoNoDepreciado ->debe = $request->input('debe');
            $GuardarActivoFijoNoDepreciado ->haber = $request->input('haber');
            $GuardarActivoFijoNoDepreciado ->numerodocumento = $request->input('numerodocumento');
            $GuardarActivoFijoNoDepreciado ->save();


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
    public function update(Request $request, $iddetalleitemactivofijo)
    {
        

        $tablaDetalleItemActivoFijo = Cont_detalleitemactivofijo::find($iddetalleitemactivofijo);

        $tablaDetalleItemActivoFijo->precioventa = $request->input('precioventa');
        $tablaDetalleItemActivoFijo->estado = $request->input('estado');
        $tablaDetalleItemActivoFijo->idempleado = $request->input('idempleado');
        $tablaDetalleItemActivoFijo->ubicacion = $request->input('ubicacion');
        $tablaDetalleItemActivoFijo->observacion = $request->input('observacion');
        $tablaDetalleItemActivoFijo->save();




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

     public function AllActivosFijosAlta()
    {

        return $AllActivosFijos = Cont_ItemCompra::join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemcompra.idcatalogitem')
                                                 ->join('cont_bodega','cont_bodega.idbodega','=','cont_itemcompra.idbodega')
                                                 ->join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')  
                                                 ->where('cont_catalogitem.idclaseitem','=','3')
                                                 ->select('cont_catalogitem.foto','cont_catalogitem.codigoproducto','cont_documentocompra.numdocumentocompra','cont_itemcompra.preciounitario','cont_bodega.namebodega','cont_documentocompra.fecharegistrocompra','cont_itemcompra.idcatalogitem','cont_itemcompra.iditemcompra')
                                                 ->get();
    }


      public function AllActivosFijosSinAlta()
    {
       return $AllActivosFijosSinAlta =   Cont_ItemCompra::join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemcompra.idcatalogitem')
                                                 ->join('cont_bodega','cont_bodega.idbodega','=','cont_itemcompra.idbodega')
                                                 ->join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')
                                                 ->join('cont_detalleitemactivofijo','cont_detalleitemactivofijo.iditemcompra','=','cont_itemcompra.iditemcompra')
                                                 ->join('empleado','empleado.idempleado','=','cont_detalleitemactivofijo.idempleado')
                                                 ->join('persona','persona.idpersona','=','empleado.idpersona')
                                                 ->where('cont_catalogitem.idclaseitem','=','3')
                                                 ->select('cont_catalogitem.foto','cont_catalogitem.codigoproducto','cont_documentocompra.numdocumentocompra','cont_itemcompra.preciounitario','cont_bodega.namebodega','cont_documentocompra.fecharegistrocompra','cont_itemcompra.idcatalogitem','cont_itemcompra.iditemcompra','cont_detalleitemactivofijo.estado','persona.namepersona','cont_detalleitemactivofijo.iddetalleitemactivofijo','cont_detalleitemactivofijo.fechaalta','cont_documentocompra.numdocumentocompra','cont_detalleitemactivofijo.vidautil')
                                                 ->get();
    }

  

    public function ActivoFijoIndividual($idcatalogitem)
    {
       
        return $ActivoFijoIndividual = Cont_ItemCompra::join('cont_catalogitem','cont_catalogitem.idcatalogitem','=','cont_itemcompra.idcatalogitem')
                                                    ->join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                                                    ->join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')
                                                    ->where('cont_itemcompra.idcatalogitem','=',$idcatalogitem)
                                                    ->select('cont_catalogitem.nombreproducto','cont_catalogitem.codigoproducto','cont_itemactivofijo.iditemactivofijo')
                                                    ->get();
    }

    public function ObtenerDemasDAtos($iditemcompra)
    {
        return $ActivoFijoIndividual1 = Cont_ItemCompra::join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')
        ->where('cont_itemcompra.iditemcompra','=',$iditemcompra)
        ->select('cont_itemcompra.iditemcompra','cont_documentocompra.fechaemisioncompra')->get();                     

    }


    public function Responsable($responsable)
    {
       
        return  $AllResponsable = empleado::join('persona','persona.idpersona','=','empleado.idpersona')
                                            ->whereRaw("namepersona::text iLIKE  '%". $responsable ."%'")
                                            ->select('persona.namepersona','empleado.idempleado')
                                            ->get();

    }

    public function VerificarAltaCompra($iditemcompra)
    {
            $ExistenciaAlta = Cont_detalleitemactivofijo::join('cont_itemcompra','cont_itemcompra.iditemcompra','=','cont_detalleitemactivofijo.iditemcompra')
            ->where('cont_itemcompra.iditemcompra','=',$iditemcompra)
            ->select('cont_detalleitemactivofijo.numactivo')->count();


           if ($ExistenciaAlta == 0) {
               
                return 0;

           }else{
                
                return $ExisteAlta = Cont_detalleitemactivofijo::join('cont_itemcompra','cont_itemcompra.iditemcompra','=','cont_detalleitemactivofijo.iditemcompra')
                    ->where('cont_itemcompra.iditemcompra','=',$iditemcompra)
                    ->select('cont_detalleitemactivofijo.iddetalleitemactivofijo','cont_itemcompra.iditemcompra')->get();
           }
    }

    public function ObtenerDatosAlta($iditemcompra)
    {


        return $DatosAlta = Cont_detalleitemactivofijo::join('cont_itemcompra','cont_itemcompra.iditemcompra','=','cont_detalleitemactivofijo.iditemcompra')
                    ->join('empleado','empleado.idempleado','=','cont_detalleitemactivofijo.idempleado')
                    ->join('persona','persona.idpersona','=','empleado.idpersona')
                    ->join('cont_plancuenta','cont_plancuenta.idplancuenta','=','cont_detalleitemactivofijo.idplancuentadepreciacion')
                    ->where('cont_itemcompra.iditemcompra','=',$iditemcompra)
                    ->select('cont_detalleitemactivofijo.iddetalleitemactivofijo','cont_detalleitemactivofijo.numactivo','cont_detalleitemactivofijo.precioventa','cont_detalleitemactivofijo.vidautil','empleado.idpersona','persona.namepersona','cont_detalleitemactivofijo.ubicacion','cont_detalleitemactivofijo.idplancuentadepreciacion','cont_plancuenta.concepto','cont_detalleitemactivofijo.observacion','cont_detalleitemactivofijo.estado','cont_detalleitemactivofijo.iditemactivofijo')
                    ->get();
    }


    public function ObtenerPlanCuentaGasto($iditemcompra)
    {
            
        return $DatosAltaPlanCuentaGastos =  Cont_detalleitemactivofijo::join('cont_itemcompra','cont_itemcompra.iditemcompra','=','cont_detalleitemactivofijo.iditemcompra')
            ->join('cont_plancuenta','cont_plancuenta.idplancuenta','=','cont_detalleitemactivofijo.idplancuentagasto')
            ->select('cont_detalleitemactivofijo.idplancuentagasto','cont_plancuenta.concepto')
            ->get();

    }

    public function ObtenerTiposMantencion()
    {
        
        return $ObtenerTiposMantencion =  Cont_tipomantencionaf::all(); 

    }

    public function ObtenerNumActivo($numactivo)
    {
        
        $ObtenerNumActivo = DB::table('cont_detalleitemactivofijo')->where('cont_detalleitemactivofijo.numactivo', '=',$numactivo)->count();

        if ($ObtenerNumActivo == 0) {
             
             return 0;
        }
        else{

            return 1;
        }
                                   

    }

    public function ObtenerIncidencia($iddetalleitemactivofijo)
    {
       $ObtenerIncidencia= DB::table('cont_incidenciaaf')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->count();

       if ($ObtenerIncidencia > 0 ) {
           
         return $ObtenerIncidencia2 = DB::table('cont_incidenciaaf')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->get();   

       }
       else{

        return 0;
       }
    }



     public function ObtenerMantencion($iddetalleitemactivofijo)
    {
       $ObtenerMantencion= DB::table('cont_mantencionaf')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->count();

       if ($ObtenerMantencion > 0 ) {
           
         return $ObtenerMantencion2 = DB::table('cont_mantencionaf')
         ->join('cont_tipomantencionaf','cont_tipomantencionaf.idtipomantencionaf','=','cont_mantencionaf.idtipomantencionaf')
         ->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)
         ->select('cont_mantencionaf.fecha','cont_mantencionaf.observacion','cont_tipomantencionaf.tipo')->get();   

       }
       else{

        return 0;
       }
    }



     public function ObtenerTraslados($iddetalleitemactivofijo)
    {
       $ObtenerTraslados= DB::table('cont_trasladoaf') ->where('cont_trasladoaf.iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->count();

       if ($ObtenerTraslados > 0 ) {
           
         $ObtenerTraslados2 = DB::table('cont_trasladoaf')
        ->join('empleado','empleado.idempleado','=','cont_trasladoaf.idempleadoorigen')
        ->join('persona','persona.idpersona','=','empleado.idpersona')
        ->where('cont_trasladoaf.iddetalleitemactivofijo','=',$iddetalleitemactivofijo)
         ->select('cont_trasladoaf.fecha','persona.namepersona as namepersonaorigen')
         ->get();   

        $ObtenerTraslados3 = DB::table('cont_trasladoaf')
        ->join('empleado','empleado.idempleado','=','cont_trasladoaf.idempleadodestino')
        ->join('persona','persona.idpersona','=','empleado.idpersona')
         ->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)
         ->select('persona.namepersona as namepersonadestino')->get();  

             return [$ObtenerTraslados2,$ObtenerTraslados3];
       }

       else{

        return 0;
       }



  }

  public function ObtenerBaja($iddetalleitemactivofijo)
  {
       $ObtenerBaja = DB::table('cont_bajaaf')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->count();

      if ($ObtenerBaja > 0) {

        return $ObtenerBaja2 = DB::table('cont_bajaaf')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)
        ->join('cont_conceptobajaaf','cont_conceptobajaaf.idconceptobajaaf','=','cont_bajaaf.idconceptobajaaf')
        ->select('cont_bajaaf.fecha','cont_bajaaf.descripcion','cont_conceptobajaaf.concepto')->get();

      }
      else{

        return 0;

      }

  }



    public function ObtenerConceptoBaja()
    {
       return  $ObtenerConceptoBaja = Cont_conceptobajaaf::all();
       }




    public function VerificaDepreciacion($iddetalleitemactivofijo)
    {
       
     $ObtenerDepreciacion = DB::table('cont_registroactivofijo')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->count();

     if ($ObtenerDepreciacion > 0) {
            
        return  $ObtenerDepreciacion = DB::table('cont_registroactivofijo')->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)->get();

     }else{

        return 0;

     }

    }


  
    public function DevolverDatosDeDetealleItemActivosFijos($iddetalleitemactivofijo)
    {
         return  $ObtenerDatosDeDetealleItemActivosFijos = DB::table('cont_detalleitemactivofijo')
         ->join('cont_itemcompra','cont_itemcompra.iditemcompra','=','cont_detalleitemactivofijo.iditemcompra')
         ->join('cont_documentocompra','cont_documentocompra.iddocumentocompra','=','cont_itemcompra.iddocumentocompra')
         ->join('cont_plancuenta','cont_plancuenta.idplancuenta','=','cont_detalleitemactivofijo.idplancuentadepreciacion')
         ->where('iddetalleitemactivofijo','=',$iddetalleitemactivofijo)
         ->select('cont_detalleitemactivofijo.iddetalleitemactivofijo','cont_detalleitemactivofijo.vidautil','cont_itemcompra.preciounitario','cont_documentocompra.numdocumentocompra','cont_detalleitemactivofijo.idplancuentadepreciacion','cont_detalleitemactivofijo.idplancuentagasto')->get();
    }



        public function ObtenerDatosCuentaDepreciacion($iddepreciacion)
    {
      return  $ObtenerDatosCuentaDepreciacion = DB::table('cont_plancuenta')->where('idplancuenta','=',$iddepreciacion)->get();
    }


     public function ObtenerDatosCuentaGasto($idgasto)
    {
       return $ObtenerDatosCuentaDepreciacion = DB::table('cont_plancuenta')->where('idplancuenta','=',$idgasto)->get();
    }





  
public function ActualizarCampoDepreciado(Request $request, $iddetalleitemactivofijo)
{
     $tablaDetalleItemActivoFijo = Cont_detalleitemactivofijo::find($iddetalleitemactivofijo);

        $tablaDetalleItemActivoFijo->depreciado = $request->input('depreciado');
        $tablaDetalleItemActivoFijo->save();
}

public function ActualizarCampoBaja(Request $request, $iddetalleitemactivofijo)
{
     $tablaDetalleItemActivoFijo = Cont_detalleitemactivofijo::find($iddetalleitemactivofijo);

        $tablaDetalleItemActivoFijo->baja = $request->input('baja');
        $tablaDetalleItemActivoFijo->save();
}


public function ObtenerDepreciados()
{
    
    $CuentaActivosDepreciados = DB::table('cont_detalleitemactivofijo')->where([

        ['depreciado',1],['baja',0]
        
        ])->count();


        if ($CuentaActivosDepreciados > 0) {
           
           return  $activosDepreciados = DB::table('cont_detalleitemactivofijo')->where([

            ['depreciado',1],['baja',0]
          
            ])->get();
       
        }
        else{

            return 0;
        }

}


public function ObtenerNoDepreciados()
{
    
    $CuentaActivosNoDepreciados = DB::table('cont_detalleitemactivofijo')->where([

        ['depreciado',0],['baja',0]
        
        ])->count();


        if ($CuentaActivosNoDepreciados > 0) {
           
           return  $activosNoDepreciados = DB::table('cont_detalleitemactivofijo')->where([

            ['depreciado',0],['baja',0]
            
            ])->get();
        }
        else{

            return 0;
        }

}



public function GuardarAsientoContable(Request $request)
{

     $aux = $request->all();
     $filtro = json_decode($aux["datos"]);


    $id_transaccion = CoreContabilidad::SaveAsientoContable( $filtro->DataContabilidad);

    return  $id_transaccion;
}


public function ObtenerUltimaDepreciacion()
{
    $ObtenerUltimaDepreciacion = DB::table('cont_registroactivofijo')->count();

    if ($ObtenerUltimaDepreciacion > 0) {

        return $ObtenerUltimaDepreciacion = DB::table('cont_registroactivofijo')->orderBy('idregistroactivofijo','desc')->take(1)->select('cont_registroactivofijo.fecha')->get();

  }else{

        return 0;


    }
    

}





}
