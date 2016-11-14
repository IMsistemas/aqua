<?php
 
namespace App\Modelos\Tarifas;

use Illuminate\Database\Eloquent\Model;

class ExcedenteTarifa extends Model
{

    protected $table = "excedentetarifa";
    protected $primaryKey = 'idexcedente';
    public $timestamps = false;


}
