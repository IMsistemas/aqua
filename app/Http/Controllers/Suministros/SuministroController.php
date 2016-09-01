<?php

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Suministros\Suministro;
use App\Modelos\Clientes\Cliente;
use App\Modelos\Tarifas\Tarifa;
use App\Modelos\Sectores\Calle;

class SuministroController extends Controller
{
    public function index(){
        return Suministro::with('cliente','tarifa','calle')->get();
    }

    public function getsuministro($numeroSuministro){
        $suministro = Suministro::with('cliente','tarifa','calle')->get();
        return $suministro[$numeroSuministro-1];
    }

    public function guardarRubros(){
        
    }

        
 }
       