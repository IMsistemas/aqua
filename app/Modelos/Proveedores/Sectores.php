<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Sectores extends Model
{
    protected $table = "sector";

    protected $primaryKey = "idsector";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idciudad',
        'nombreparroquia'
    ];

    public function ciudad()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Ciudades','idciudad');
    }
   
}