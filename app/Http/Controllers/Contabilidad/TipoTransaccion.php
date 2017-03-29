<?php

namespace App\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modelos\Contabilidad\Cont_TipoTransaccion;


use Carbon\Carbon;
use DateTime;
use DB;


class TipoTransaccion extends Controller
{
	/**
	 *
	 *
	 * Carga los tipos de transaccion contables que hay ;
	 *
	 */
	public function getalltipotransacciones()
	{
		return Cont_TipoTransaccion::with("cont_tipoingresoegreso")->get();
	}
}