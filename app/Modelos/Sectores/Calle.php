<?php
 
namespace App\Modelos\Sectores;

use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    protected $table = "calle";
    protected $primaryKey = "idcalle";
    public $timestamps = false;
    public $incrementing = false;

    public function barrio(){
    	return $this->belongsTo('App\Modelos\Sectores\Barrio','idbarrio');
    }
}
 