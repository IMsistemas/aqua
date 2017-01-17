<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Plandecuetas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Estadosfinancieros/PlandeCuentasContables');
    }
}
