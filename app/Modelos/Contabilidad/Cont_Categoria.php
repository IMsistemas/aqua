<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Categoria extends Model
{

    protected $table = 'cont_categoria';
    protected $primaryKey = 'idcategoria';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'idcategoria', 'nombrecategoria', 'jerarquia'        
    ];

    public function cont_catalogitem(){
    	return $this->hasMany('App\Modelos\Contabilidad');
    }

}
