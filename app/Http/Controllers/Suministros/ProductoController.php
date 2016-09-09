<?php

namespace App\Http\Controllers\Suministros;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Suministros\Producto;

class ProductoController extends Controller
{
    public function index(){
    	return Producto::all();
    }
}
