<?php

namespace App\Http\Controllers\Cuentas;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Cuentas\RubroFijo;
use App\Modelos\Cuentas\RubroVariable;
use App\Modelos\Suministros\Suministro;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Modelos\Sectores\Calle;

class CobroAguaController extends Controller
{

	/**
	========Kevin=======================================================================================
	**/

	/**
	*Retorna todos los suministros con sus respectivos dueños, sus tarifas y su calle
	**/
	public function getSuministros(){
		return Suministro::with('cliente','tarifa','calle')->get();
	}

	/**
	*Retorna un suministro con su respectivos dueño, su tarifa y su calle
	**/
	public function getSuministro($numeroSuministro){
        $suministro = Suministro::with('cliente','tarifa','calle')->get();
        return $suministro[$numeroSuministro-1];
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
    public function guardarRubros(){
        
    }

    /**
	====================================================================================================
	**/
    
}
