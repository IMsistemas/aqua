<?php

namespace App\Http\Controllers\Nomina;

use App\Modelos\Nomina\Cargo;
use App\Modelos\Nomina\Empleado;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CargoController extends Controller
{
    public function index()
    {
        return view('Nomina/index_cargo');
    }

    public function getCargos()
    {
        return Cargo::orderBy('nombrecargo', 'asc')->get();
    }


    public function getCargoByID($id){
        return Cargo::where('idcargo', $id)->orderBy('nombrecargo')->get();
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

        $cargo1 = Cargo::where ('nombrecargo',$request->input('nombrecargo'))-> count();


        if($cargo1 > 0)
        {
            return response()->json(['success' => false]);
        }else {
            $cargo = new Cargo();

            $cargo->nombrecargo = $request->input('nombrecargo');

            $cargo->save();

            return response()->json(['success' => true]);
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
    public function update(Request $request, $id)
    {

        $cargo = Cargo::find($id);

        $cargo->nombrecargo = $request->input('nombrecargo');
        $cargo->save();

        return response()->json(['success' => true]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $empleado = Empleado::where ('idcargo',$id)-> count();
        if($empleado > 0)
        {
            return response()->json(['success' => false]);
        }else{
            $cargo = Cargo::find($id);
            $cargo->delete();
            return response()->json(['success' => true]);
        }
    }

}
