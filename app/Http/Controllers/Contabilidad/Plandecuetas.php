<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Plandecuetas extends Controller
{
    /**
     * Carga la vista
     *
     * 
     */
    public function index()
    {
        return view('Estadosfinancieros/PlandeCuentasContables');
        //return view('Estadosfinancieros/aux_PlandeCuentasContables');
    }
}
