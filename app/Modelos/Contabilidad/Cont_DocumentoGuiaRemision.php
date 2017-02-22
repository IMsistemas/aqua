<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_DocumentoGuiaRemision extends Model
{

    protected $table = 'cont_documentoguiaremision';
    protected $primaryKey = 'iddocumentoguiaremision';
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'idtransportista','idcliente','iddocumentoventa', 'nrodocumentoguiaremision',
  		'nrodeclaracionaduana','codestablesimiento','ruta','fechainiciotransp','fechafintransp','motivotraslado','direccdestinatario','partida'        
    ];

    public function cont_documentoguiaremisionmerc(){
    	return $this->hasMany('App\Modelos\Contabilidad');
    }

    public function cliente(){
    	return $this->belongsTo('App\Modelos\Clientes');
    }

    public function cont_documentoventa(){
    	return $this->belongsTo('App\Modelos\Facturacionventa');
    }

    public function transportista(){
    	return $this->belongsTo('App\Modelos\Transportista');
    }

}


