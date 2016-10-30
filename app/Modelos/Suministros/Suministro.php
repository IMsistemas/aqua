<?php
 
namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;
 
class Suministro extends Model
{
    protected $table = "suministro";
    protected $primaryKey = "numerosuministro";
    public $timestamps = false; 

    public function cliente(){
    	return $this->belongsTo('App\Modelos\Clientes\Cliente','codigocliente');
    }

    public function calle(){
    	return $this->belongsTo('App\Modelos\Sectores\Calle','idcalle');
    }

    public function producto(){
    	return $this->belongsTo('App\Modelos\Suministros\Producto','idproducto');
    }

    public function servicioaguapotable(){
    	return $this->belongsTo('','idaguapotable');
    }
}
