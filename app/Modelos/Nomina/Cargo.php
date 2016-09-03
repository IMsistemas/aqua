<?php
 
namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = "cargo";

    protected $primaryKey = "idcargo";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idcargo', 'nombrecargo',
    ];

    public function empleado()
    {
    	return $this->hasMany('App\Modelos\Nomina\emplado','idcargo');
    } 
}
