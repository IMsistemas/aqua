<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_CatalogItem extends Model
{
    protected $table = "cont_catalogitem";

    protected $primaryKey = "idcatalogitem";

    public $incrementing = false;

    public $timestamps = false;
    protected $fillable = [
        'idtipoimpuestoiva', 'idtipoimpuestoice', 'idplancuenta', 
            'idclaseitem', 'idcategoria', 'nombreproducto', 'codigoproducto', 'precioventa'        
    ];

    public function cont_itemventa(){
    	return $this->hasMany('App\Modelos\Contabilidad');
    }

    public function cont_categoria(){
        return $this->belongsTo('App\Modelos\Contabilidad');
    }

   
}
