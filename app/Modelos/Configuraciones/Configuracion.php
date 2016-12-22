<?php
 
namespace App\Modelos\Configuraciones;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = "configuracionjunta";
    protected $primaryKey = "idconfiguracion";
    public $timestamps = false;

    
}