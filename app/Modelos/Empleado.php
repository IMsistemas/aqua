<?php
 
namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleado";

    protected $primaryKey = "idempleado";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idempleado','idcargo' ,'documentoidentidadempleado','fechaingreso','apellidos',
  'nombres','telefonoprincipaldomicilio','telefonosecundariodomicilio','celular','direcciondomicilio',
  'correo','rutafoto','salario',
    ];

   
}
