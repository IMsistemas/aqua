<?php
 
namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;
 
class Suministro extends Model
{
    protected $table = "suministro";
    protected $primaryKey = "idsuministro";
    public $timestamps = false; 

    public function cliente()
    {
    	return $this->belongsTo('App\Modelos\Clientes\Cliente','idcliente');
    }

    public function calle()
    {
    	return $this->belongsTo('App\Modelos\Sectores\Calle','idcalle');
    }

    public function cont_catalogitem()
    {
    	return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem','idcatalogitem');
    }

    public function cont_documentoventa()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_DocumentoVenta','iddocumentoventa');
    }

    public function tarifaaguapotable()
    {
    	return $this->belongsTo('App\Modelos\Servicios\AguaPotable','idtarifaaguapotable');
    }

    public function cobroagua()
    {
        return $this->hasMany('App\Modelos\Cuentas\CobroAgua','idsuministro');
    }

    public function cuentaporcobrarsuministro()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasPorCobrarSuministro','idsuministro');
    }

}
