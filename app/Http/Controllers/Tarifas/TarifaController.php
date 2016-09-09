<?php

namespace App\Http\Controllers\Tarifas;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Tarifas\Tarifa;
class TarifaController extends Controller
{
    public function index(){
		return Tarifa::all();
	}
}
