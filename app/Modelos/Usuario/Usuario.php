<?php

namespace App\Modelos\Usuario;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'idusuario';
    public $timestamps = false;


}
