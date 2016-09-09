<?php
 
namespace App\Modelos\Sectores;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = "provincia";
    protected $primaryKey = "idprovincia";
    public $timestamps = false;
    public $incrementing = false;

    public function canton (){
    	return $this->hasMany('App\Modelos\Sectores\Canton','idprovincia');
    }
}
