<?php
 
namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
    protected $primaryKey = "idproducto";
    public $timestamps = false;

    public function suministro(){
    	return $this->hasMany('App\Modelos\Suministros\Suministro','idproducto');
    }
}
