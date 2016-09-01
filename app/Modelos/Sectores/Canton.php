<?php
 
namespace App\Modelos\Sectores;

use Illuminate\Database\Eloquent\Model;

class Canton extends Model
{
    protected $table = "canton";
    protected $primaryKey = "idcanton";
    public $timestamps = false;

    public function provincia(){
    	return $this->belongsTo('App\Modelos\Sectores\provincia');
    }

    public function parroquia(){
    	return $this->hasMany('App\Modelos\Sectores\parroquia','idcanton');
    }
}
