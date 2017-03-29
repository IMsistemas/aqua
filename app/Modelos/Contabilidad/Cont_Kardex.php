<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Kardex extends Model
{
    protected $table = "cont_kardex";

    protected $primaryKey = "idkardex";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idkardex',
        'idtransaccion',
        'idproducto_bodega',
        'fecharegistro',
        'cantidad',
        'costounitario',
        'costototal',
        'tipoentradasalida',
        'estadoanulado',
        'descripcion'
    ];
    
    public function cont_transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_Transaccion',"idtransaccion");
    }
   
}
