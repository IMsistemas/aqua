<?php

namespace App\Http\Controllers\Cuentas;

use App\Modelos\Clientes\Cliente;
use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Suministros\Suministro;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecaudacionCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Recaudacion.index_recaudacion');
    }



    /**
     * Obtener los clientes paginados
     *
     * @param Request $request
     * @return mixed
     */
    public function getClientes(Request $request)
    {
        $filter = json_decode($request->get('filter'));
        $search = $filter->search;

        $cliente = null;

        $cliente = Cliente::join('persona', 'persona.idpersona', '=', 'cliente.idpersona')
            ->join('cont_plancuenta', 'cont_plancuenta.idplancuenta', '=', 'cliente.idplancuenta')
            ->with('sri_tipoempresa', 'sri_parte')
            ->select('cliente.*', 'persona.*', 'cont_plancuenta.*')
            ->where("cliente.estado", true);

        if ($search != null) {
            $cliente = $cliente->whereRaw("(persona.razonsocial ILIKE '%" . $search . "%' OR persona.numdocidentific ILIKE '%" . $search . "%')");
        }

        return $cliente->orderBy('lastnamepersona', 'asc')->paginate(8);
    }



    public function getFacConsumo($idcliente)
    {
        $cobroagua = CobroAgua::join('suministro', 'suministro.idsuministro', '=', 'cobroagua.idsuministro')
                        ->where('suministro.idcliente', $idcliente)->orderBy('idcobroagua', 'desc')
                        ->where('cobroagua.total', '!=', null)->get();


        return $cobroagua;
    }

    public function getDerechoAcometida($idcliente)
    {
        $suministro = Suministro::where('idcliente', $idcliente)->orderBy('idsuministro', 'desc')->get();

        return $suministro;
    }

    public function getOtrosCargos($idcliente)
    {

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
