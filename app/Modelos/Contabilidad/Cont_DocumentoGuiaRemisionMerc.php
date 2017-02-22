<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_DocumentoGuiaRemisionMerc extends Model
{

    protected $table = 'cont_documentoguiaremisionmerc';
    protected $primaryKey = 'iddocumentoguiaremisionmerc';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddocumentoguiaremision','cantidad','peso', 'largo',
  		'ancho','altura','tipoempaque','descripcion'        
    ];

    public function cont_documentoguiaremision(){
    	return $this->belongsTo('App\Modelos\Contabilidad');
    }

}
