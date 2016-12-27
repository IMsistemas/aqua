<?php

namespace App\Http\Controllers\Proveedores;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modelos\Proveedores\Proveedor;
use App\Modelos\Proveedores\Ciudades;
use App\Modelos\Proveedores\Provincias;
use App\Modelos\Proveedores\Sectores;
use App\Modelos\Proveedores\TiposContribuyentes;
use App\Modelos\Proveedores\ContactoProveedor;
use App\Modelos\Proveedores\CuentasProveedores;
use Carbon\Carbon;


class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Proveedor::with('sector.ciudad.provincia')->get();
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCiudades($idprovincia = null)
    {
        if ($idprovincia == null) {
            return Ciudades::all();
        }
        $Provincia =  Provincias::find($idprovincia);
        $Ciudades = $Provincia->ciudades;
        return $Ciudades;
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProvincias()
    {
        return Provincias::all();
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSectores($idciudad=null)
    {
        if ($idciudad == null) {
            return Sectores::all();
        }
        $Ciudad =  Ciudades::find($idciudad);
        $Sectores = $Ciudad->sectores;
        return $Sectores;
        
    }
    public function getFechaCreacion($idproveedor)
    {
        $CuentaProveedor = CuentasProveedores::where('idproveedor', $idproveedor)->get();
        
        return $CuentaProveedor;
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTiposContribuyentes()
    {
        return TiposContribuyentes::all();
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNuevoProveedor()
    {
        
        $ultimo = Proveedor::orderBy('idproveedor', 'desc')->first();
       
        if ($ultimo === null) {
        	$id = 1;
        }
        else{
        	$id = $ultimo->idproveedor+1;
        }
       
        $date = Carbon::Today();
        $proveedornuevo =[ 
        	
        		'idproveedor'=> $id,
        		'idtipoproveedor'=> '1',
        		'idsector'=>'',
                'codigotipoid'=> '',
        		'documentoproveedor'=> '',
        		'razonsocialproveedor'=> '',
        		'telefonoproveedor'=> '',
        		'direccionproveedor'=> '',
        		'correocontactoproveedor'=> '',
        		'estado'=> true,
        		'fechaingresoproveedor'=> $date->format('d-m-Y'),
        		'nombrecontacto'=>'',
        		'telefonoprincipal'=> '',
        		'telefonosecundario'=> '',
        		'celular'=> '',
        		'observacion'=> '',
        	];
        	return $proveedornuevo;
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
        $date = Carbon::Today();
        $data = Proveedor::create($request->all());
        if ($data) {
            $ultimo = Proveedor::orderBy('idproveedor', 'desc')->first();
            $idp = $ultimo->idproveedor;
            CuentasProveedores::create([
                'idproveedor' => $idp,
                'fechacreacioncuenta' => $date->format('Y-m-d'),
                'saldocuenta' => 0,
                
                ]);  
        	ContactoProveedor::create([
                'idproveedor'  => $idp, 
                'nombrecontacto'  => $request->nombrecontacto,
                'telefonoprincipal' => $request->telefonoprincipal,
                'telefonosecundario' => $request->telefonosecundario,
                'celular' => $request->celular,
                'observacion' => $request->observacion,
            ]);
        }
        return $data;
        //return "hola mundo";
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeContactos($id,Request $request)
    {
        
        foreach ($request->all() as $contacto) {
            ContactoProveedor::create($contacto);
        }
        
        return $request->all();
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
        $Proveedor = Proveedor::find($id);
        $Proveedor->update($request->all());
        $Proveedor->save();
        return $Proveedor;
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
