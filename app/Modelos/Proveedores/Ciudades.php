<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Ciudades extends Model
{
    protected $table = "ciudad";

    protected $primaryKey = "idciudad";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idprovincia', 
        'nombreciudad',
    ];

    public function provincia()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Provincias', 'idprovincia');
    }
    public function sectores()
    {
        return $this->hasMany('App\Modelos\Proveedores\Sectores','idsector');
    }
   
}