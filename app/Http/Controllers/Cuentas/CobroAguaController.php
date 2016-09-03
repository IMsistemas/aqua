<?php

namespace App\Http\Controllers\Cuentas;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Cuentas\CobroAgua;
use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Cuentas\RubroVariable;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Modelos\Sectores\Calle;

class CobroAguaController extends Controller
{

	/**
	=========================================Kevin======================================================
	**/

	/**
	*Retorna todas las cuentas con los suministros, los clientes y tarifas del suministro
	**/
	public function getCuentas(){
		return $aux = CobroAgua::with('suministro.cliente','suministro.tarifa','lectura')->get();
	}

	/**
	*Retorna una cuenta con el suministro, el dueño del suministro y su tarifa
	**/
	public function getCuenta($numeroCuenta){
        $cuenta = CobroAgua::with('suministro.cliente','suministro.tarifa','suministro.calle.barrio','lectura')->get();
        return dd($cuenta[$numeroCuenta-1]);
    }

    /**
	*Retorna los rubros variables de la junta
	**/
    public function getRubrosVariables(){
    	return RubroVariable::all();
    }

    /**
	*Retorna los rubros fijos de la junta
	**/
    public function getRubrosFijos(){
    	return RubroFijo::all();
    }

    /**
	*Guarda los rubros fijos y variables para antes de la toma de lecturas
	**/
    public function guardarRubros(Request $request,$numeroCobro){
        $cobroAgua = CobroAgua::find($numeroCobro);
        $rubrosFijos = $cobroAgua->rubrosfijos;
        $rubrosVariables = $cobroAgua->rubrosvariables;
        
        $rubrosfijos->idcuenta = $numeroCobro;
        $rubrosfijos->idrubrofijo = $request->input('');
        $rubrosfijos->costorubro = $request->input('costoRubroFijo');
       
        $rubrosvariables->idcuenta = $numeroCobro;
        $rubrosvariables->idrubrovariable = $request->input('');
        $rubrosvariables->idrubrovariable = $request->input('costoRubroFijo');

        $rubrosfijos->save();
        $rubrosvariables->save();

        return 'Se agregaron los valores de otros rubros con exito';

    }

    /**
	====================================================================================================
	**/
    
}
