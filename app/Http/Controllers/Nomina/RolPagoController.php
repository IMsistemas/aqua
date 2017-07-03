<?php

namespace App\Http\Controllers\Nomina;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RolPagoController extends Controller
{
    public function index()
    {
        return view('RolPago/index');
    }

}
