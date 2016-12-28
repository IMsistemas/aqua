<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class catalogoproducto extends Model
{
    protected $table = "catalogoproducto";

    protected $primaryKey = "codigoproducto";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigoproducto',
        'idkardex',
        'idcategoria',
        'nombreproducto',
        'fechaingreso',
        'rutafoto'        
    ];
    public function productoenbodega(){
    	return $this->belongsTo('App\Modelos\Facturacionventa\productoenbodega','idproductobodega');
    }
}
