<?php
 
namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleado";

    protected $primaryKey = "documentoidentidadempleado";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'documentoidentidadempleado', 'fechaingreso', 'nombre', 'apellido',
        'telefonoprincipal', 'telefonosecundario',
        'celular', 'direccion', 'correo',
        'idcargo',
    ];

    public function cargo(){
    	return $this->belongsTo('App\Modelos\Nomina\cargo');
    }
}
