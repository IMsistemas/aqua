<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Sectores extends Model
{
    protected $table = "parroquia";

    protected $primaryKey = "idparroquia";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idcanton',
        'nameparroquia'
    ];

    public function ciudad()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Ciudades','idcanton');
    }
   
}