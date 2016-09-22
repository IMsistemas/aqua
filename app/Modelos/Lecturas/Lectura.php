<?php
 
namespace App\Modelos\Lecturas;

use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    protected $table = "lectura";
    protected $primaryKey = "idlectura";
    public $timestamps = false;

    protected $fillable =  [
        'fechalectura', 'numerosuministro', 'lecturaanterior', 'lecturaactual', 'consumo', 'observacion'
    ];

    public function suministro(){
    	return $this->belongsTo('App\Modelos\Suministros\Suministro');
    }

    public function cobroagua(){
    	return $this->hasMany('App\Modelos\Cuentas\CobroAgua','idlectura');
    }
}
