<?php
 
namespace App\Modelos\Sectores;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    protected $table = "parroquia";
    protected $primarykey = "idparroquia";
    public $timestamps = false;
    public $incrementing = false;

    public function canton(){
    	return $this->belongsTo('App\Modelos\Sectores\Canton','idcanton');
    }

    public function barrio(){
    	return $this->hasMany('App\Modelos\Sectores\Barrio','idparroquia');
    }


}
 