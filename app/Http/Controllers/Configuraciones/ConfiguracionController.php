<?php

namespace App\Http\Controllers\Configuraciones;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modelos\Configuraciones\Configuracion;


class ConfiguracionController extends Controller
{
	public function index(){
		return Configuracion::all();
	}
}