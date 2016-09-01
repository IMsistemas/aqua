<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class RubroVariable extends Model
{
    protected $table = "rubrovariable";
    protected $primaryKey = "idrubrovariable";
    public $timestamps = false;

    public function cobrosagua(){
    	return $this->belongsToMany('App\Modelos\Cuentas\cobroagua','rubrosvariablescuentas','idcuenta','idrubrovariable');
    }
}
 