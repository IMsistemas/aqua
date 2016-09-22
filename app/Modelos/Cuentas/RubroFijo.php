<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class RubroFijo extends Model
{
    protected $table = "rubrofijo";
    protected $primaryKey = "idrubrofijo";
    public $timestamps = false;

    public function cobrosagua(){
    	return $this->belongsToMany('App\Modelos\Cuentas\CobroAgua','rubrosfijoscuentas','idcuenta','idrubrovariable');
    }
}
 