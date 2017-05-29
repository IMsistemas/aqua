<?php

namespace App\Http\Controllers\ActivosFijos;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\Contabilidad\Cont_claseItem;
use App\Modelos\Contabilidad\Cont_Categoria;
use App\Modelos\SRI\SRI_TipoImpuestoIva;
use App\Modelos\SRI\SRI_TipoImpuestoIce;
use App\Modelos\Contabilidad\Cont_PlanCuenta;
use App\Modelos\Contabilidad\Cont_Itemactivofijo;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;

class activosfijosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    return view('Activosfijos/index_activosfijos');

    }

     public function addActivosFijo()
    {

     return view('Activosfijos/index_activosfijos');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $guardarCatalogItem = new Cont_CatalogItem($request->all());

        if ($request->file('foto') == null) {


            $guardarCatalogItem->idtipoimpuestoiva = $request->input('iva_item');
            $guardarCatalogItem->idtipoimpuestoice = $request->input('ice');
            $guardarCatalogItem->idplancuenta = $request->input('id_cuenta');
            $guardarCatalogItem->idclaseitem = $request->input('claseitem');
            $guardarCatalogItem->idcategoria = $request->input('categoria');
            //$guardarCatalogItem->idlinea = $request->input('idlinea');
            //$guardarCatalogItem->idsublinea = $request->input('sublinea');
            $guardarCatalogItem->nombreproducto = $request->input('detalleitem');
            $guardarCatalogItem->codigoproducto = $request->input('codigoitem');
            $guardarCatalogItem->foto= rand(0,99999). "activofijo.jpeg";
            $guardarCatalogItem->save();
               
            $id = $guardarCatalogItem::all();
                      
            $guardarItemactivofijo = new  Cont_Itemactivofijo($request->all());
            $guardarItemactivofijo->idcatalogitem =$id->last()->idcatalogitem;
            $guardarItemactivofijo->save();

            $origen= public_path() ."\img";
            $destino= public_path() ."\imgActivosFijos";

            copy($origen."\activofijo.jpeg",$destino. "/".$guardarCatalogItem->foto);
           

        } 



        else {

            $nom_img = $request->input('nombre_foto');
            $ruta=public_path() . '/imgActivosFijos';
            $imagen = $request->file('foto');
            $imagen-> move($ruta, $nom_img);


            $guardarCatalogItem->idtipoimpuestoiva = $request->input('iva_item');
            $guardarCatalogItem->idtipoimpuestoice = $request->input('ice');
            $guardarCatalogItem->idplancuenta = $request->input('id_cuenta');
            $guardarCatalogItem->idclaseitem = $request->input('claseitem');
            $guardarCatalogItem->idcategoria = $request->input('categoria');
            //$guardarCatalogItem->idlinea = $request->input('idlinea');
            //$guardarCatalogItem->idsublinea = $request->input('sublinea');
            $guardarCatalogItem->nombreproducto = $request->input('detalleitem');
            $guardarCatalogItem->codigoproducto = $request->input('codigoitem');
            $guardarCatalogItem->foto = $request->input('nombre_foto');
            $guardarCatalogItem->save();

            $id = $guardarCatalogItem::all();
                      
            $guardarItemactivofijo = new  Cont_Itemactivofijo($request->all());
            $guardarItemactivofijo->idcatalogitem =$id->last()->idcatalogitem;
            $guardarItemactivofijo->save();

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

        $ice = Cont_CatalogItem::find($id);

        switch ($ice) {
          
            case  $ice->idplancuenta == null && $ice->idtipoimpuestoice== null:
                       
                 $activosfijos =  DB::table('cont_catalogitem')
                ->join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                ->join('sri_tipoimpuestoiva','sri_tipoimpuestoiva.idtipoimpuestoiva','=','cont_catalogitem.idtipoimpuestoiva')
                ->join('cont_claseitem', 'cont_claseitem.idclaseitem','=', 'cont_catalogitem.idclaseitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
                ->where('cont_catalogitem.idcatalogitem','=',$id)->get();

                $activosfijos2 =  DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idlinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();
                
                 $activosfijos3 = DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idsublinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();

                $activo = [$activosfijos,$activosfijos2, $activosfijos3];

                return $activo;


            break;

            case $ice->idplancuenta == null && $ice->idtipoimpuestoice != null:

                $activosfijos =  DB::table('cont_catalogitem')
                ->join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                ->join('sri_tipoimpuestoiva','sri_tipoimpuestoiva.idtipoimpuestoiva','=','cont_catalogitem.idtipoimpuestoiva')
                ->join('sri_tipoimpuestoice', 'sri_tipoimpuestoice.idtipoimpuestoice', '=','cont_catalogitem.idtipoimpuestoice')
                ->join('cont_claseitem', 'cont_claseitem.idclaseitem','=', 'cont_catalogitem.idclaseitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
                ->where('cont_catalogitem.idcatalogitem','=',$id)->get();

                $activosfijos2 =  DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idlinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();
                
                 $activosfijos3 = DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idsublinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();

                $activo = [$activosfijos,$activosfijos2, $activosfijos3];

                return $activo;

            break;

             
            case $ice->idtipoimpuestoice == null && $ice->idplancuenta != null:

                $activosfijos =  DB::table('cont_catalogitem')
                ->join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                ->join('sri_tipoimpuestoiva','sri_tipoimpuestoiva.idtipoimpuestoiva','=','cont_catalogitem.idtipoimpuestoiva')
                ->join('cont_claseitem', 'cont_claseitem.idclaseitem','=', 'cont_catalogitem.idclaseitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
                ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_catalogitem.idplancuenta')
                ->where('cont_catalogitem.idcatalogitem','=',$id)->get();

                $activosfijos2 =  DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idlinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();
                
                $activosfijos3 = DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idsublinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();

                $activo = [$activosfijos,$activosfijos2, $activosfijos3];

                return $activo;

            break;

            case  $ice->idplancuenta != null && $ice->idtipoimpuestoice != null:
 
            $activosfijos =  DB::table('cont_catalogitem')
                ->join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                ->join('sri_tipoimpuestoiva','sri_tipoimpuestoiva.idtipoimpuestoiva','=','cont_catalogitem.idtipoimpuestoiva')
                ->join('sri_tipoimpuestoice','sri_tipoimpuestoice.idtipoimpuestoice','=', 'cont_catalogitem.idtipoimpuestoice')
                ->join('cont_claseitem', 'cont_claseitem.idclaseitem','=', 'cont_catalogitem.idclaseitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idcategoria')
                ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cont_catalogitem.idplancuenta')
                ->where('cont_catalogitem.idcatalogitem','=',$id)->get();

                $activosfijos2 =  DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idlinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();
                
                $activosfijos3 = DB::table('cont_catalogitem')
                ->join('cont_categoria', 'cont_categoria.idcategoria', '=', 'cont_catalogitem.idsublinea')
                ->where('cont_catalogitem.idcatalogitem','=',$id)
                ->select('cont_categoria.nombrecategoria','cont_categoria.idcategoria','cont_categoria.jerarquia')->get();

                $activo = [$activosfijos,$activosfijos2, $activosfijos3];

                return $activo;

        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
        
        $guardarCatalogItem = Cont_CatalogItem::find($id);
          
        if ($request->file('foto') == null) {
            
            $guardarCatalogItem->idtipoimpuestoiva = $request->input('iva_item');
            $guardarCatalogItem->idtipoimpuestoice = $request->input('ice');
            $guardarCatalogItem->idplancuenta = $request->input('id_cuenta');
            $guardarCatalogItem->idclaseitem = $request->input('claseitem');
            $guardarCatalogItem->idcategoria = $request->input('categoria');
            //$guardarCatalogItem->idlinea = $request->input('idlinea');
            //$guardarCatalogItem->idsublinea = $request->input('sublinea');
            $guardarCatalogItem->nombreproducto = $request->input('detalleitem');
            $guardarCatalogItem->codigoproducto = $request->input('codigoitem');
            $guardarCatalogItem->foto= rand(0,99999). "activofijo.jpeg";
            $guardarCatalogItem->save();
               

            $origen= public_path() ."\img";
            $destino= public_path() ."\imgActivosFijos";

            copy($origen."\activofijo.jpeg",$destino. "/".$guardarCatalogItem->foto);  

        } 

        else {

           
            $guardarCatalogItem->idtipoimpuestoiva = $request->input('iva_item');
            $guardarCatalogItem->idtipoimpuestoice = $request->input('ice');
            $guardarCatalogItem->idplancuenta = $request->input('id_cuenta');
            $guardarCatalogItem->idclaseitem = $request->input('claseitem');
            $guardarCatalogItem->idcategoria = $request->input('categoria');
            //$guardarCatalogItem->idlinea = $request->input('idlinea');
            //$guardarCatalogItem->idsublinea = $request->input('sublinea');
            $guardarCatalogItem->nombreproducto = $request->input('detalleitem');
            $guardarCatalogItem->codigoproducto = $request->input('codigoitem');
            $guardarCatalogItem->foto = $request->input('nombre_foto');
            $guardarCatalogItem->save();


            $nom_img = $request->input('nombre_foto');
            $ruta=public_path() . '/imgActivosFijos';
            $imagen = $request->file('foto');
            $imagen-> move($ruta, $nom_img);
           
          
        }




    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($iditemactivofijo, $Idcatal, $NomImg)
    {

    $Cont_Itemactivofijo = Cont_Itemactivofijo::find($iditemactivofijo);
    $Cont_Itemactivofijo->delete();

    $Cont_CatalogItem = Cont_CatalogItem::find($Idcatal);
    $Cont_CatalogItem->delete();

    $ruta= public_path().'/imgActivosFijos/';
    $NombreImg = $NomImg;
    unlink($ruta.$NombreImg);

    }


    public function getClaseItem()
    {
        return  $getclase= Cont_claseItem::all();   
    }

    public function getCategorias()
    {
        return $getCategorias= DB::table('cont_categoria')->where('jerarquia','~','*.*{1}')->get();  
    }

     public function getLinea($jerarquia)
    { 

        $getL = DB::table('cont_categoria')->where('idcategoria', '=', $jerarquia)->get();

        return $getLinea = DB::table('cont_categoria')->where('jerarquia','~',$getL[0]->jerarquia.'.*{1}')->get();

    }

    public function getSubLinea($subjerarquia)
    {

        return $getSubLinea = DB::table('cont_categoria')->where('jerarquia','~',$subjerarquia.'.*.*.*{1}' )->get();
        //select * from cont_categoria where jerarquia ~ '1.2.*.*.*{1}';
    }

     public function getTipoIva()
    {
        return  $getTipoIva = SRI_TipoImpuestoIva::all();   
    }

     public function getTipoIce()
    {
        return  $getTipoIce = SRI_TipoImpuestoIce::all();   
    }

    public function getPlanCuentas()
    {
        return  $getTipoIce = Cont_PlanCuenta::all();   
    }

    public function getAllActivosfijos()
    {
    return $activosfijos = Cont_CatalogItem::join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                                             ->select('cont_catalogitem.foto','cont_catalogitem.codigoproducto','cont_catalogitem.nombreproducto','cont_itemactivofijo.iditemactivofijo','cont_itemactivofijo.idcatalogitem')
                                             ->get();

    }

     public function getAllActivosfijosfiltradosbusqueda($palabra)
    {
    return $activosfijos = Cont_CatalogItem::join('cont_itemactivofijo','cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')
                                             ->where('cont_catalogitem.nombreproducto','like', '%'. $palabra .'%')
                                             ->orwhere('cont_catalogitem.codigoproducto','like,', '%'. $palabra. '%')
                                             ->select('cont_catalogitem.foto','cont_catalogitem.codigoproducto','cont_catalogitem.nombreproducto','cont_itemactivofijo.iditemactivofijo','cont_itemactivofijo.idcatalogitem')
                                             ->get();

   /*;DB::table('cont_catalogitem')->where('cont_itemactivofijo.idcatalogitem','=','cont_catalogitem.idcatalogitem')    
                                ->where('cont_catalogitem.nombreproducto','like', '%'. $palabra .'%')
                                ->select('cont_catalogitem.*','cont_itemactivofijo.*')->get();*/
    }

    public function getCodigo($codigo)
    {
        return $getcodigo = DB::table('cont_catalogitem')->where('codigoproducto','=',$codigo)->select('cont_catalogitem.codigoproducto')->get();
    
    }
    
   /* public function GetIdLinea($jerarquiaLinea)
    {
        return $getCategorias= DB::table('cont_categoria')->where('jerarquia','=',$jerarquiaLinea)->get(); 
    }*/
}