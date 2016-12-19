<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Provincias extends Model
{
    protected $table = "provincia";

    protected $primaryKey = "idprovincia";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'nombreprovincia',
    ];

    public function ciudades()
    {
        return $this->hasMany('App\Modelos\Proveedores\Ciudades','idciudad');
    }
   
}