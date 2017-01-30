<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Ciudades extends Model
{
    protected $table = "canton";

    protected $primaryKey = "idcanton";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idprovincia', 
        'nameciudad',
    ];

    public function provincia()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Provincias', 'idprovincia');
    }
    public function sectores()
    {
        return $this->hasMany('App\Modelos\Proveedores\Sectores','idparroquia');
    }
   
}