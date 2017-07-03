<?php

namespace App\Http\Controllers\RolPago;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class rolPagoController extends Controller
{
    public function index()
    {
        return view('RolPago/index');
    }

}
