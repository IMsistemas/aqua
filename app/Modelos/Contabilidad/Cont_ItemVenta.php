<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_ItemVenta extends Model
{

    protected $table = 'cont_itemventa';
    protected $primaryKey = 'iditemventa';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iditemventa', 'idcatalogitem', 'iddocumentoventa', 'idbodega', 'idtipoimpuestoiva', 
            'idtipoimpuestoice', 'cantidad', 'preciounitario', 'descuento', 'preciototal'        
    ];

    public function cont_documentoventa(){
    	return $this->belongsTo('App\Modelos\Contabilidad');
    }

    public function cont_catalogoitem(){
        return $this->HasMany('App\Modelos\Contabilidad');
    }

}
