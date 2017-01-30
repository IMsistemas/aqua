<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Modelos\Contabilidad\Cont_DocumentoCompra;
use App\Modelos\Contabilidad\Cont_FormaPago;
use App\Modelos\Contabilidad\Cont_Bodega;
use App\Modelos\Contabilidad\Cont_CatalogItem;
use App\Modelos\SRI\SRI_PagoPais;
use App\Modelos\SRI\SRI_PagoResidente;
use App\Modelos\SRI\SRI_SustentoTributario;
use App\Modelos\SRI\SRI_TipoComprobante;
use App\Modelos\SRI\SRI_TipoImpuestoIva;
use App\Modelos\Proveedores\Proveedor;

use DateTime;

use DB;

class CompraProductoController extends Controller
{

    /**
     * Devolver la vista
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('compras.listado_compras');
    }

    /**
     * Obtener los proveedores para filtro
     *
     * @return mixed
     */
    public function getProveedores()
    {
    	return Proveedor::All();   	 
    }
    
    /**
     * Obtener las bodegas filtradas
     *
     * @param $filter
     * @return mixed
     */
    public function getCompras($filter)
    {
    	$filter = json_decode($filter);    	 
    	$filterCombo = ($filter->proveedorId != null)?" and cont_documentocompra.idproveedor = ".$filter->proveedorId:"";
    	
    	if($filter->estado != null){
    		$opcion = boolval($filter->estado)? "true" : "false";
    		$filterCombo .= ' and cont_documentocompra."estaAnulada" = '.$opcion;    		
    	}   	
    		 
    	return  Cont_DocumentoCompra::join('proveedor', 'proveedor.idproveedor', '=', 'cont_documentocompra.idproveedor')
    	->select('proveedor.razonsocial', 'cont_documentocompra.*')
    			->whereRaw("(cont_documentocompra.iddocumentocompra::text ILIKE '%" . $filter->text . "%'
                            or proveedor.razonsocial ILIKE '%" . $filter->text . "%' )
                            		".$filterCombo)
                                		->orderBy('cont_documentocompra.iddocumentocompra', 'asc')
                                		->get();
                                		 
    }
    
    
    public function getFormaPago()
    {
    	return Cont_FormaPago::All();
    }
    public function getTipoComprobante()
    {
    	return SRI_TipoComprobante::All();
    }
    
    public function getSustentoTributario()
    {
    	return SRI_SustentoTributario::All();
    }
    
    public function getBodegas()
    {
    	return Cont_Bodega::All();
    }
        
    public function getPais()
    {
    	return SRI_PagoPais::All();
    }
    
    public function getFormaPagoDocumento()
    {
    	return SRI_PagoResidente::All();
    }
    
    public function getConfiguracion()
    {
    	return Configuracion::orderBy('fechaingreso', 'desc')->first();
    }
    
    
    public function getComprasMes($proveedor)
    {
    	$date = Carbon::Today();
    	return CompraProducto::whereRaw("to_char(fecharegistrocompra, 'YYYY-MM') = '".$date->format('Y-m')."'")
    	->where('idproveedor',$proveedor)->count();
    }
    

    /**
     * Obtener base de la compra nueva
     *
     * @return mixed
     */
    public function getLastCompra()
    {
        $compra = new Cont_DocumentoCompra();		
		$compra->codigocompra = Cont_DocumentoCompra::max('iddocumentocompra') +1;
		$date = Carbon::Today();
		$compra->fecharegistrocompra = $compra->fechaemisioncompra = $date->format('Y-m-d');
		return $compra;
    }
    
    
    public function getProveedorByCI($ci)
    {
    	return Proveedor::join('persona', 'persona.idpersona', '=', 'proveedor.idpersona') 
    	->join('sri_tipoimpuestoiva', 'sri_tipoimpuestoiva.idtipoimpuestoiva', '=', 'proveedor.idtipoimpuestoiva')   
    	->select('proveedor.*','sri_tipoimpuestoiva.*')
    	->whereRaw("persona.numdocidentific = '".$ci."'")
    	->first() ;
    }
    
    public function getBodega($texto)
    {
    	return Bodega::whereRaw("idbodega ILIKE '%" . $texto . "%' or nombrebodega ILIKE '%" . $texto . "%'")
    		->get() ;
    }
    
    public function getCodigoProducto($texto)
    {
    	return Cont_CatalogItem::whereRaw("codigoproducto::text LIKE '%" . $texto . "%'")
    	->get() ;
    }
    
    
    
    public function store(Request $request)
    {
    	
    
    	$exception = DB::transaction(function() use ($request)
    	{
    		$datos = $request->all();
    		$detalle = $datos['detalle'];
    		$formaId = $datos['idformapago'];
    		$ivaId = $datos['idtipoimpuestoiva'];
    		$bodegaId = $datos['idbodega'];
    		unset( $datos['detalle']);
    		unset( $datos['id']);  
    		unset( $datos['idformapago']);
    		unset( $datos['codigocompra']);
    		unset( $datos['codigopais']);
    		unset( $datos['idbodega']);
    		
    		
    		$datos['estaAnulada'] = false;
    		
    		// insertamos producto en compra
    		//$producto = Cont_DocumentoCompra::create($datos);
    		DB::table('cont_documentocompra')->insert($datos);
    		$producto = DB::getPdo()->lastInsertId('cont_documentocompra_iddocumentocompra_seq');
    		
    		$date = Carbon::Today();
    		foreach ($detalle as $item) {
    			 
    			// insercion de detalle en productos compra
    			DB::table('cont_itemcompra')->insert(
    					array('idcatalogitem' => $item['idcatalogitem'], 'iddocumentocompra' => $producto,
    							'cantidad' => $item['cantidad'], 'preciototal' => $item['total'], 
    							'preciounitario'=> $item['preciounitario'],
    							'descuento'=> $item['descuento'],
    							'idtipoimpuestoiva'=>$ivaId,
    							'idbodega' => $item['idbodega']
    					)
    					);
    			
    			
    			// ingreso o actualizacion de los productos en bodega
    			/*
    			$productobodega = DB::table('productoenbodega')->where('codigoproducto', $item['idproducto'])->where('idbodega', $item['idbodega'])->first();
    	
    			if(is_object($productobodega)){
    				DB::table('productoenbodega')
    				->where('codigoproducto', $item['idproducto'])->where('idbodega', $item['idbodega'])
    				->update(['cantidadproductobodega' => $productobodega->cantidadproductobodega + $item['cantidad']]); 				   			
    			} else {
    				DB::table('productoenbodega')->insert(
    						array('codigoproducto' => $item['idproducto'], 'idbodega' => $item['idbodega'],
    								'cantidadproductobodega' => $item['cantidad']
    						)
    						);
    			}    			 
    			
    			// ingreso del producto en el kardex calculo de algoritmo gestion kardex
    			$kardex = DB::table('kardexstock')->where('codigoproducto', $item['idproducto'])->orderBy('fechaactualizacion', 'desc')->first();
    			
    			if(is_object($kardex)){    				
    				$cantidad = $kardex->cantidaddisponible + $item['cantidad'];
    				$total = ($item['cantidad'] * $item['precioUnitario']) + $kardex->costototal;
    				$costounitario = $total / $cantidad;	
    				
    				DB::table('kardexstock')
    				->where('idkardex', $kardex->idkardex)
    				->update(['codigoproducto' => $item['idproducto'],'fechaactualizacion'=>$date->format('Y-m-d'),'cantidaddisponible'=>$cantidad,
    						'costounitario'=>$costounitario,'costototal'=>$total
    				]);
    				$kardexId = $kardex->idkardex;
    				
    			} else {
    				$cantidad = $item['cantidad'];
    				$total = $item['cantidad'] * $item['precioUnitario'];
    				$costounitario = $item['precioUnitario'];
    				
    				$kardexId = DB::table('kardexstock')->insertGetId(
    						array('codigoproducto' => $item['idproducto'], 'fechaactualizacion' => $date->format('Y-m-d'),
    								'cantidaddisponible' => $cantidad, 'costounitario' => $costounitario,
    								'costototal' => $total
    						),'idkardex'
    						);
    			}
    				
    			  // entrada stock
    			  
    			DB::table('entradastock')->insert(
    					array('idkardex' => $kardexId, 'fechaentrada' => $date->format('Y-m-d'),
    							'detalleentrada' => 'Ingreso Factura '.$producto->codigocompra, 'cantidadentrada' => $item['cantidad'],
    							'costounitarioentrada' => $item['precioUnitario'],'costototalentrada'=> $item['cantidad'] * $item['precioUnitario']
    					)
    					);
    			  
    			*/
    			}    
    			
    			// insercion moviemoo cuetna proveedor
    			/*
    			$cuenta = DB::table('cuentaproveedor')->where('idproveedor', $producto->idproveedor)->first(); 			 
    			
    			DB::table('movimientocuentaproveedor')->insert(
    					array('numerocuenta3' => $cuenta->numerocuenta3, 'codigocompra' => $producto->codigocompra,
    							'fechamovimiento' => $date->format('Y-m-d'), 'detallemovimiento' => 'Ingreso Factura '.$producto->codigocompra,
    							'montomovimiento' => $producto->totalcompra,'estapagado'=> false, 'anulada' => false
    					)
    					);
    			*/
    			
    			DB::table('cont_formapago_documentocompra')->insert(
    					array( 'iddocumentocompra' => $producto,
    							'idformapago' => $formaId
    					)
    					);
    			return $producto;
    	});
	 
    	return ($exception) ? response()->json(['success' => true, 'id' => $exception ]) : response()->json(['success' => false]);
    	
    	 
    }
    
    
    public function pagarCompra($id)
    {
    	$compra = CompraProducto::find($id);
    	$compra->estapagada =  true;
    	$date = Carbon::Today();
    	$compra->fechapago =  $date->format('Y-m-d');
    	$compra->save();
    	DB::table('movimientocuentaproveedor')
    	->where('codigocompra', $id)
    	->update(['estapagado' => true,'fechapago'=>$date->format('Y-m-d')]);
    	 
    	return response()->json(['success' => true]);
    }
    
    public function imprimirCompra($id)
    {
    	$compra = CompraProducto::find($id);
    	$compra->impreso =  true;    	
    	$compra->save();
    	
    	
    	return response()->json(['success' => true]);
    }
    
    public function anularCompra($id)
    {
    	/*
    	$exception = DB::transaction(function() use ($id)
    	{
    		// disminucion de cantidad de los productos de la compra producto en las bodegas
	    	$productos = DB::table('productosencompra')->where('codigocompra', $id)->get();	    	
	    	$date = Carbon::Today();
	    	foreach ($productos as $item){	    		
	    		DB::table('productoenbodega')
	    		->where('codigoproducto', $item->codigoproducto)->where('idbodega', $item->idbodega)
	    		->decrement('cantidadproductobodega', $item->cantidadtotal);
	    		
	    		
	    		// ingreso del producto en el kardex calculo de algoritmo gestion kardex
	    		$kardex = DB::table('kardexstock')->where('codigoproducto', $item->codigoproducto)->orderBy('fechaactualizacion', 'desc')->first();
	    		 
	    		$cantidad = $kardex->cantidaddisponible - $item->cantidadtotal;
	    		if($cantidad == 0){
	    			$total = 0;
	    			$costounitario = 0;
	    		} else {
	    			$total = $kardex->costototal - ($item->cantidadtotal * $item->precio);
	    			$costounitario = $total / $cantidad;
	    		}
	    		
	    		DB::table('kardexstock')
	    			->where('idkardex', $kardex->idkardex)
	    			->update(['codigoproducto' => $item->codigoproducto,'fechaactualizacion'=>$date->format('Y-m-d'),'cantidaddisponible'=>$cantidad,
	    					'costounitario'=>$costounitario,'costototal'=>$total
	    		]);
	    		
	    		
	    		// salida stock 
	    			
	    		DB::table('salidastock')->insert(
	    				array('idkardex' => $kardex->idkardex, 'fechasalida' => $date->format('Y-m-d'),
	    						'detallesalida' => 'Anulacion Factura '.$id, 'cantidadsalida' => $item->cantidadtotal,
	    						'costounitariosalida' => $item->precio,'costototalsalida'=> $item->cantidadtotal * $item->precio
	    				)
	    				);

	    		
	    	}	    		
	    	// eliminacion de los prodctos de la compra del producto
	    	//DB::table('productosencompra')->where('codigocompra', $id)->delete();
	    	// eliminacion de los items del kardex
	    	//DB::table('kardexstock')->where('idcompra', $id)->delete();    	
	    	
	    	// actualizacion de la compra en anulado
	    	$compra = CompraProducto::find($id);
	    	$compra->estaanulada =  true;
	    	$compra->save();
	    	
	    	// actualizacion   movimiento cuenta proveedor compra anulada
	    	DB::table('movimientocuentaproveedor')
	    	->where('codigocompra', $id)
	    	->update(['anulada' => true]);
	    	
    	});
	 
    	return is_null($exception) ? response()->json(['success' => true, ]) : response()->json(['success' => false]);
    	*/
    	
    	$compra = Cont_DocumentoCompra::find($id);
    	$compra->estaAnulada =  true;
    	$compra->save();
    	return response()->json(['success' => true, ]) ;
    }
    
    public function formulario($compra)
    {
    	return view('compras.formulario_compras', ['compra' => $compra]);    	
    }
    
    
    public function show($id)
    {
    	$producto = Cont_DocumentoCompra::find($id);
    	$producto->proveedor = Proveedor::join('persona', 'persona.idpersona', '=', 'proveedor.idpersona')
    	->join('sri_tipoimpuestoiva', 'sri_tipoimpuestoiva.idtipoimpuestoiva', '=', 'proveedor.idtipoimpuestoiva')
    	->select('proveedor.*','sri_tipoimpuestoiva.*','persona.numdocidentific')
    	->where("proveedor.idproveedor",$producto->idproveedor)->first();  

    	$formapago = DB::table('cont_formapago_documentocompra')
    	->select('idformapago')
    	->where("iddocumentocompra",$id)->first();
    	$producto->idformapago = $formapago->idformapago;
    	
    	return $producto;
    }
    
    public function getDetalle($id)
    {
    	$detalles = DB::table('cont_itemcompra')->
    	where('iddocumentocompra', $id)->
    	select('idcatalogitem','iddocumentocompra','cantidad','preciounitario as precioUnitario','preciototal as total', 'idbodega','idtipoimpuestoiva')->
    	get();
		
    	foreach ($detalles as $item){
    		$item->producto = Cont_CatalogItem::find($item->idcatalogitem);
    		$iva = SRI_TipoImpuestoIva::find($item->idtipoimpuestoiva);
    		$item->iva = $iva->porcentaje;
    		$item->ice = 0;
    		$item->descuento = 0;
    	}
    	return $detalles;
    }
    
    
    private function getCompraById($id)
    {
    	$producto = CompraProducto::join('tipocomprobante','tipocomprobante.codigocomprbante', '=','documentocompra.codigocomprbante')
    	->join('sustentocomporbante','sustentocomporbante.codigosustento', '=','documentocompra.codigosustento')
    	->join('formapagodocumento','formapagodocumento.idformapago', '=','documentocompra.idformapago')
    	->leftJoin('paispago','paispago.codigopais', '=','documentocompra.codigopais')
    	->leftJoin('formapagocompra','formapagocompra.codigoformapago3', '=','documentocompra.codigoformapago3')		
    	->select('documentocompra.*','formapagocompra.nombreformapago as forma','paispago.nombrepais','formapagodocumento.nombreformapago','sustentocomporbante.nombresustento','tipocomprobante.nombretipocomprobante')
    	->where('documentocompra.codigocompra',$id)->first();
    	
    	
    	$producto->proveedor = Proveedor::join('sector', 'sector.idsector', '=', 'proveedor.idsector')
    	->join('ciudad', 'sector.idciudad', '=', 'ciudad.idciudad')
    	->join('tipoidentificacionproveedor', 'tipoidentificacionproveedor.idtipoproveedor', '=', 'proveedor.idtipoproveedor')
    	->join('tipoidentificacion', 'tipoidentificacion.codigotipoid', '=', 'proveedor.codigotipoid')
    	->select('proveedor.*','ciudad.nombreciudad','tipoidentificacion.tipoidentificacion','tipoidentificacion.codigotipoid','tipoidentificacionproveedor.idtipoproveedor','tipoidentificacionproveedor.nombretipoproveedor')
    	->where("proveedor.idproveedor",$producto->idproveedor)->first();
    	
    	
    	$detalles = DB::table('productosencompra')->
    	where('codigocompra', $id)->
    	select('codigoproducto','codigocompra','cantidadtotal','precio as precioUnitario','preciototal as total','porcentajeiva as iva', 'porcentajeice as ice', 'idbodega')->
    	get();
    	
    	foreach ($detalles as $item){
    		$productod = CatalogoProducto::find($item->codigoproducto);
    		$item->codigoproducto = $productod->codigoproducto;
    		$item->nombreproducto =  $productod->nombreproducto;
    		$bodega = Bodega::find($item->idbodega);
    		$item->bodega = $bodega->idbodega ."-".$bodega->nombrebodega;
    	}
    	$producto->detalles = $detalles;
    	return $producto;
    }
    
    
    public function imprimir($id){
    	$producto = $this->getCompraById($id);
    	return view('compras.plantilla_compras', ['producto' => $producto,'imprimir' => true,'id'=>$id]);
    }
    
    
    public function pdf($id)
    {
    	$producto = $this->getCompraById($id);    	
    	$imprimir = false;    	
    	$view =  \View::make('compras.plantilla_compras', compact('producto','imprimir','id'))->render();
    	$pdf = \App::make('dompdf.wrapper');
    	$pdf->loadHTML($view);
    	return $pdf->stream('compradocumento');
    }

    public function excel($id)
    {
    	$producto = $this->getCompraById($id);
    	\Excel::create('compradocumento', function($excel) use($producto){
    	
    		$excel->sheet('Compra', function($sheet) use($producto) {
    	
    			$sheet->setOrientation('landscape');
    			
    			$sheet->mergeCells('B5:I5');
    			$sheet->cells('B5:I5', function($cells) {
    				$cells->setFontWeight('bold');
    				$cells->setAlignment('center');
    			});
    			$sheet->row(5, array('','Compras Inventario'));    			
  			
    			$sheet->row(8, array('','Fecha Registro:',$producto->fecharegistrocompra,'Registro Compra No:',str_pad($producto->codigocompra, 7, "0", STR_PAD_LEFT)));
    			$sheet->row(9, array('','Datos Proveedor'));
    			$sheet->row(10, array('','Ruc/CI:',$producto->proveedor->documentoproveedor,'Razón Social:',$producto->proveedor->razonsocialproveedor));
    			$sheet->row(11, array('','Teléfono:',$producto->proveedor->telefonoproveedor,'Dirección:',$producto->proveedor->direccionproveedor));
    			$etiqueta = '';
    			$valor = '';
    			if((($producto->proveedor->codigotipoid == '01')||($producto->proveedor->codigotipoid == '02')||($producto->proveedor->codigotipoid == '03'))){
    				$valos = 'SI';
    				$etiqueta = 'Parte Relacionada:';
    			}    			
    			$sheet->row(12, array('','Tipo id. proveedor:',$producto->proveedor->codigotipoid ." - ".$producto->proveedor->tipoidentificacion,$etiqueta,$valor));
    			$sheet->row(13, array('','Tipo Proveedor:',$producto->proveedor->idtipoproveedor ." - ".$producto->proveedor->nombretipoproveedor,'Ciudad:',$producto->proveedor->nombreciudad));
    			$sheet->row(14, array('','Datos Documento'));
    			$sheet->row(15, array('','Fecha Emisión:',$producto->fechaemisionfacturaproveedor,'Fecha Caducidad:',$producto->fechacaducidad));
    			$sheet->row(16, array('','Numero de documento:',$producto->numerodocumentoproveedor,'Tipo Comprobante:',$producto->codigocomprbante ." - ".$producto->nombretipocomprobante));
    			$sheet->row(17, array('','Autorización:',$producto->autorizacionfacturaproveedor,'Sustento Tributario:',$producto->codigosustento ." - ".$producto->nombresustento));
    			$sheet->row(18, array('','Forma Pago:',$producto->nombreformapago));
    			$sheet->row(19, array('','Detalle Compra'));
    			$sheet->row(20, array('','Bodega','Cod. Prod','Cant.','Detalle','PVP Unitario','IVA','ICE','Total'));
    			
    			$sheet->cells('B20:I20', function($cells) {    			
    				$cells->setFontWeight('bold');			
    			
    			});
    			
    			$i = 21;
    			foreach ($producto->detalles as $item){
    				$sheet->row($i, array('',$item->bodega,$item->codigoproducto,$item->cantidadtotal,$item->nombreproducto,$item->precioUnitario,$item->iva,$item->ice,$item->total));
    				$i++;
    			}
    			$i = $i - 1;
    			$sheet->cells('B20:I'.$i, function($cells) {
    				$cells->setBorder('solid', 'solid', 'solid', 'solid');    				 
    			});
    				$i++;   			
    				$i++;
    			
    			$sheet->row($i, array('','Datos Pago'));
    			$i++;
    			$valor = 'Residente';
    			if($producto->codigotipopago == '02'){
    				$valor = $producto->nombrepais;
    			}
    			$sheet->row($i, array('','Pais Pago:',$valor));
    			$i++;
    			$sheet->row($i, array('','Forma Pago:',$producto->codigoformapago ." - ".$producto->forma,'','',$producto->procentajedescuentocompra,'% Descuento','Subtotal 14%:',$producto->subtotalivacompra));
    			$i++;
    			$sheet->row($i, array('','','','','','','','Subtotal 0%:',$producto->subtotalnoivacompra));
    			$i++;
    			$sheet->row($i, array('','','','','','','','Descuento:',$producto->descuentocompra));
    			$i++;
    			$sheet->row($i, array('','','','','','','','Otros:',$producto->otrosvalores));
    			$i++;
    			$sheet->row($i, array('','','','','','','','IVA:',$producto->ivacompra));
    			$i++;
    			$sheet->row($i, array('','','','','','','','Total:',$producto->totalcompra));
    			
    			$objDrawing = new \PHPExcel_Worksheet_Drawing;
    			$objDrawing->setPath(public_path('img/logo.png')); //your image path
    			$objDrawing->setCoordinates('B2');
    			$objDrawing->setWorksheet($sheet);
    			
    		});
    	
    	})->export('xls');
    }
    
    
    public function update(Request $request, $id)
    {
    

    $exception = DB::transaction(function() use ($request,$id)
    {
   
    	$datos = $request->all();
    	$detalle = $datos['detalle'];
    	$formaId = $datos['idformapago'];
    	$ivaId = $datos['idtipoimpuestoiva'];
    	$bodegaId = $datos['idbodega'];
    	unset( $datos['detalle']);
    	unset( $datos['id']);
    	unset( $datos['idformapago']);
    	unset( $datos['codigocompra']);
    	unset( $datos['codigopais']);
    	unset( $datos['idbodega']);
    	
    	
    	
    	// actualizamos producto en compra    	    	
    	$producto = Cont_DocumentoCompra::find($id);
    	$producto->fill($datos);
    	$producto->save();
    	/*
    	// eliminacion de prodctos en bodega y kardex    	
    	$productos = DB::table('productosencompra')->where('codigocompra', $id)->get();
    	$date = Carbon::Today();
    	foreach ($productos as $item){
    		DB::table('productoenbodega')
    		->where('codigoproducto', $item->codigoproducto)->where('idbodega', $item->idbodega)
    		->decrement('cantidadproductobodega', $item->cantidadtotal);
    		 
    		 
    		// ingreso del producto en el kardex calculo de algoritmo gestion kardex
    		$kardex = DB::table('kardexstock')->where('codigoproducto', $item->codigoproducto)->orderBy('fechaactualizacion', 'desc')->first();
    	
    		$cantidad = $kardex->cantidaddisponible - $item->cantidadtotal;
    		if($cantidad == 0){
    			$total = 0;
    			$costounitario = 0;
    		} else {
    			$total = $kardex->costototal - ($item->cantidadtotal * $item->precio);
    			$costounitario = $total / $cantidad;
    		}
    		
    		 
    		DB::table('kardexstock')
    		->where('idkardex', $kardex->idkardex)
    		->update(['codigoproducto' => $item->codigoproducto,'fechaactualizacion'=>$date->format('Y-m-d'),'cantidaddisponible'=>$cantidad,
    				'costounitario'=>$costounitario,'costototal'=>$total
    		]);    		 
    		 
    		// salida stock    	
    		DB::table('salidastock')->insert(
    				array('idkardex' => $kardex->idkardex, 'fechasalida' => $date->format('Y-m-d'),
    						'detallesalida' => 'Edicion Factura '.$id, 'cantidadsalida' => $item->cantidadtotal,
    						'costounitariosalida' => $item->precio,'costototalsalida'=> $item->cantidadtotal * $item->precio
    				)
    				);  	
    		 
    	}  	
    	*/
    	// eliminacion de los prodctos de la compra del producto
    	DB::table('cont_itemcompra')->where('iddocumentocompra', $id)->delete();
    	
    	
    	
    	
    	$date = Carbon::Today();
    	foreach ($detalle as $item) {
    	
    		// insercion de detalle en productos compra
    		DB::table('cont_itemcompra')->insert(
    				array('idcatalogitem' => $item['idcatalogitem'], 'iddocumentocompra' => $id,
    						'cantidad' => $item['cantidad'], 'preciototal' => $item['total'],
    						'preciounitario'=> $item['preciounitario'],
    						'descuento'=> $item['descuento'],
    						'idtipoimpuestoiva'=>$ivaId,
    						'idbodega' => $item['idbodega']
    				)
    				);
    	}
    	
    	
    	/*
    	
    	
    	/// ingreso nuevos productos    
    	foreach ($detalle as $item) {  		    		
    
    		// insercion de detalle en productos compra
    		DB::table('productosencompra')->insert(
    				array('codigoproducto' => $item['idproducto'], 'codigocompra' => $producto->codigocompra,
    						'cantidadtotal' => $item['cantidad'], 'preciototal' => $item['total'],
    						'precio'=> $item['precioUnitario'],
    						'porcentajeiva'=>$item['iva'], 'porcentajeice' => $item['ice'],
    						'idbodega' => $item['idbodega']
    				)
    				);
    
    		// ingreso o actualizacion de los productos en bodega
    		$productobodega = DB::table('productoenbodega')->where('codigoproducto', $item['idproducto'])->where('idbodega', $item['idbodega'])->first();
    		 
    		if(is_object($productobodega)){
    			DB::table('productoenbodega')
    			->where('codigoproducto', $item['idproducto'])->where('idbodega', $item['idbodega'])
    			->update(['cantidadproductobodega' => $productobodega->cantidadproductobodega + $item['cantidad']]);
    		} else {
    			DB::table('productoenbodega')->insert(
    					array('codigoproducto' => $item['idproducto'], 'idbodega' => $item['idbodega'],
    							'cantidadproductobodega' => $item['cantidad']
    					)
    					);
    		}
    		 
    		// ingreso del producto en el kardex calculo de algoritmo gestion kardex
    		$kardex = DB::table('kardexstock')->where('codigoproducto', $item['idproducto'])->orderBy('fechaactualizacion', 'desc')->first();
    		 
    		if(is_object($kardex)){
    			$cantidad = $kardex->cantidaddisponible + $item['cantidad'];
    			$total = ($item['cantidad'] * $item['precioUnitario']) + $kardex->costototal;
    			$costounitario = $total / $cantidad;
    
    			DB::table('kardexstock')
    			->where('idkardex', $kardex->idkardex)
    			->update(['codigoproducto' => $item['idproducto'],'fechaactualizacion'=>$date->format('Y-m-d'),'cantidaddisponible'=>$cantidad,
    					'costounitario'=>$costounitario,'costototal'=>$total
    			]);
    			$kardexId = $kardex->idkardex;
    
    		} else {
    			$cantidad = $item['cantidad'];
    			$total = $item['cantidad'] * $item['precioUnitario'];
    			$costounitario = $item['precioUnitario'];
    
    			$kardexId = DB::table('kardexstock')->insertGetId(
    					array('codigoproducto' => $item['idproducto'], 'fechaactualizacion' => $date->format('Y-m-d'),
    							'cantidaddisponible' => $cantidad, 'costounitario' => $costounitario,
    							'costototal' => $total
    					),'idkardex'
    					);
    		}
    
    		// entrada stock
    			
    		DB::table('entradastock')->insert(
    				array('idkardex' => $kardexId, 'fechaentrada' => $date->format('Y-m-d'),
    						'detalleentrada' => 'Ingreso Factura '.$producto->codigocompra, 'cantidadentrada' => $item['cantidad'],
    						'costounitarioentrada' => $item['precioUnitario'],'costototalentrada'=> $item['cantidad'] * $item['precioUnitario']
    				)
    				);
    			
    		 
    	}
    	 
    	// actualizacion movimiento cuenta proveedor
    	 
    	$cuenta = DB::table('cuentaproveedor')->where('idproveedor', $producto->idproveedor)->first();
    	
    	
    	DB::table('movimientocuentaproveedor')
    	->where('codigocompra',$producto->codigocompra)
    	->update(['numerocuenta3' => $cuenta->numerocuenta3, 'codigocompra' => $producto->codigocompra,
    					'fechamovimiento' => $date->format('Y-m-d'), 'detallemovimiento' => 'Modificacion Factura '.$producto->codigocompra,
    					'montomovimiento' => $producto->totalcompra
    	]); 
    	*/
    	
    	DB::table('cont_formapago_documentocompra')->where('iddocumentocompra', $id)->delete(); 
    	
    	DB::table('cont_formapago_documentocompra')->insert(
    			array( 'iddocumentocompra' => $id,
    					'idformapago' => $formaId
    			)
    			);
    	 
    	 
    });
    
    return is_null($exception) ? response()->json(['success' => true, ]) : response()->json(['success' => false]);
     
    }
    
    
    
    
    

}
