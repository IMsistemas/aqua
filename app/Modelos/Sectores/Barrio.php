<?php
 
namespace App\Modelos\Sectores;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    protected $table = "barrio";
    protected $primaryKey = "idbarrio";
    public $timestamps = false;

    public function parroquia(){
        return $this->belongsTo('App\Modelos\Sectores\Parroquia','idparroquia');
    }

    public function calle(){
        return $this->hasMany('App\Modelos\Sectores\Calle','idbarrio');
    }
}
 