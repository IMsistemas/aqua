<?php

namespace App\Http\Controllers\Proveedores;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modelos\Proveedores\Proveedor;
use App\Modelos\Proveedores\ContactoProveedor;

class ContactosProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContactosProveedor($idproveedor)
    {
        //$Proveedor =  Proveedor::find($idproveedor);
        $ContactosProveedor = ContactoProveedor::where('idproveedor', $idproveedor)->orderBy('idcontacto', 'asc')->get();
        return $ContactosProveedor;
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
    public function updateContactosProveedor($request)
    {
       $contactos = json_decode($request);
      
        foreach ($contactos as $item) {
            if($item->idcontacto == "")
            {
             ContactoProveedor::create([
                'idproveedor' => $item->idproveedor,
                'nombrecontacto' => $item->nombrecontacto,
                'telefonoprincipal' => $item->telefonoprincipal,
                'telefonosecundario' => $item->telefonosecundario,
                'celular' => $item->celular,
                'observacion' => $item->observacion,
                
                ]);  
            }
            else
            {
                $contacto = ContactoProveedor::where('idcontacto', $item->idcontacto)->first();
                $contacto->nombrecontacto = $item->nombrecontacto;  
                $contacto->telefonoprincipal = $item->telefonoprincipal;  
                $contacto->telefonosecundario = $item->telefonosecundario;  
                $contacto->celular = $item->celular;  
                $contacto->observacion = $item->observacion;  
                $contacto->save();
            }
        }
        return response()->json(['success' => true]);
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyContactosProveedor($id)
    {
        $contacto = ContactoProveedor::find($id);
        $contacto->delete();
        return $contacto;
    }
}
