//var appPage = angular.module('softver-aqua-page', ['angularUtils.directives.dirPagination','softver-aqua']);

app.controller('comprasproductoController',  function($scope, $http, API_URL) {

    $scope.compras = [];   
    $scope.proveedores = [];    
    $scope.listado = true;
    $scope.compra_anular = 0;
    
    $scope.url = API_URL;
    
    $scope.searchByFilter = function(){

        var t_search = null;
        var t_proveedorId = null;
        var t_estado = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            var last = t_search.substring(t_search.length -1);            
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }
        }
        
        if($scope.proveedorFiltro != undefined && $scope.proveedorFiltro != ''){
        	t_proveedorId = $scope.proveedorFiltro;            
        }
        
        if($scope.estadoFiltro != undefined && $scope.estadoFiltro != ''){
        	t_estado = $scope.estadoFiltro;            
        }
        
        var filter = {
            text: t_search,
            proveedorId: t_proveedorId,
            estado: t_estado
        };

        $http.get(API_URL + 'compras/getCompras/' + JSON.stringify(filter)).success(function(response){
            $scope.compras = response;            
        });
    }
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'compras/getProveedores').success(function(response){
            $scope.proveedoresFiltro = response;
            $scope.estados = [{id: 1, nombre: "Pagado"},{id: 0, nombre: "No Pagado"}]
           
        });
       
    }
    
    $scope.initLoad();   
    
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   
        $scope.reverse = !$scope.reverse; 
    }
    
    $scope.sumar = function(v1,v2){
    	return $scope.roundToTwo(parseFloat(v1) + parseFloat(v2)).toFixed(2);
    }
    
    $scope.roundToTwo = function (num) {    
	    return +(Math.round(num + "e+2")  + "e-2");
	}
    
    $scope.Add = function () {
                $('#modalActionCargo').modal('show');
    }
    
    $scope.anularCompra = function(){	
    	var anular = $scope.compra_anular;
	        $http.get(API_URL + 'compras/anularCompra/' + $scope.compra_anular).success(function(response) {
	        	$scope.initLoad(); 
	        	$scope.compra_anular = 0;
	        	$scope.message = 'La compra se ha Anulado.';	        	 
	        	if(!response.success){
	        		$scope.compra_anular = anular;
       			$scope.message = 'Ocurrio un error intentelo mas tarde';
       		}	
	        	$('#modalConfirmAnular').modal('hide'); 
	            $('#modalMessage').modal('show');
	            setTimeout("$('#modalMessage').modal('hide')",3000);
	        });
	    }
	 
   $scope.showModalConfirm = function(id){   
	   $scope.compra_anular = id;
           $('#modalConfirmAnular').modal('show');       
   }
   
   $scope.formatoFecha = function(fecha){
	      	if(typeof fecha != 'undefined'){
	       		var t = fecha.split('-');
	           	var meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
	               return t[2] + '-' + meses[t[1]-1] + '-' + t[0];
	       	} else {
	       		return '';
	       	}
	       	
	       }
   
   // ingreso compra
   
   
	
	$scope.newRow = function(){
		$scope.read =  false;
		return {cantidad:0,precioUnitario:0,iva:$scope.configuracion.iva,ice:$scope.configuracion.ice,total:0,productoObj:null,testObj:null}	
	}
	
	$scope.openForm = function(id){        	
		$scope.listado = false;
		$scope.idcompra = id;
		$http.get(API_URL + 'compras/getConfiguracion').success(function(response){    
       	$scope.configuracion = response;
       	$scope.detalle = [$scope.newRow()];
       });
		if($scope.idcompra == 0){
			$http.get(API_URL + 'compras/getLastCompra').success(function(response){    
	        	$scope.compra = response;
	        	$scope.compra.ivacompra = $scope.compra.totalcompra = 0;
	        	$scope.compra.otrosvalores = $scope.compra.procentajedescuentocompra = 0;
	        	$scope.compra.subtotalivacompra = $scope.compra.subtotalnoivacompra = 0;
	        	$scope.compra.descuentocompra = $scope.compra.id = 0;
	        	$scope.compra.codigopais = '999';	        	
	        	$scope.ci = '';
	        	$scope.residente =  true;
	        	$scope.retencion =  true;
	        	$scope.pestana = false;
	        	$scope.configuracion = [];
	        	$scope.pagoM =  true;
	        	$scope.read =  false;
	        	$scope.anulado = $scope.pagado = $scope.impreso =  $scope.guardado = false;	        	
				$scope.numero1 = '';
				$scope.numero2 = '';
				$scope.numero3 = '';
				$scope.mensaje = true;
				 $scope.relacionada = false;
				 $('#razon').text('');
				 $('#telefono').text('');
				 $('#direccion').text('');
				 $('#tipoidproveedor').text('');
				 $('#tipoproveedor').text('');
				 $('#ciudad').text('');
	        });
			
		} else {
			$scope.read =  true;
			$http.get(API_URL + 'compras/'  + $scope.idcompra ).success(function(response){
				$scope.compra = response;
				
				$scope.anulado = ($scope.compra.estaanulada == 1)?true:false;
				$scope.pagado = ($scope.compra.estapagada == 1)?true:false;
				
				$scope.impreso = ($scope.compra.impreso == 1)?true:false;
				
				if($scope.compra.estaanulada == 1){
					$scope.impreso  = true; 
				}
				
				autorization = $scope.compra.numerodocumentoproveedor.split("-");
				$scope.numero1 = autorization[0];
				$scope.numero2 = autorization[1];
				$scope.numero3 = autorization[2];
				
				$scope.ci = $scope.compra.proveedor.documentoproveedor;
				$('#razon').text($scope.compra.proveedor.razonsocialproveedor);
				 $('#telefono').text($scope.compra.proveedor.telefonoproveedor);
				 $('#direccion').text($scope.compra.proveedor.direccionproveedor);
				 $('#tipoidproveedor').text($scope.compra.proveedor.codigotipoid + ' - ' + $scope.compra.proveedor.tipoidentificacion);
				 $('#tipoproveedor').text($scope.compra.proveedor.idtipoproveedor + ' - ' + $scope.compra.proveedor.nombretipoproveedor);
				 $('#ciudad').text($scope.compra.proveedor.nombreciudad);
				 $scope.compra.idproveedor = $scope.compra.proveedor.idproveedor;
				 if(($scope.compra.proveedor.codigotipoid == '01')||($scope.compra.proveedor.codigotipoid == '02')||($scope.compra.proveedor.codigotipoid == '03')){
					 $scope.relacionada = true;
				 }
				 
				 if($scope.compra.codigotipopago == '02'){
					 $scope.residente = false;
				 } else {
					 $scope.compra.codigopais = '999';
				 }
					
				 if(($scope.compra.totalcompra >= 50)){
		            	$scope.retencion =  false;
		            }
				
           });
			
			$http.get(API_URL + 'compras/getDetalle/'  + $scope.idcompra ).success(function(response){
				$scope.detalle = response;
           });
			
			
			
			$scope.guardado = true;
			
			
		}
       
       $http.get(API_URL + 'compras/getFormaPago').success(function(response){    
       	$scope.formasPago = response;
       });
       $http.get(API_URL + 'compras/getTipoComprobante').success(function(response){    
       	$scope.tiposComprobante = response;
       });
       $http.get(API_URL + 'compras/getSustentoTributario').success(function(response){    
       	$scope.sustentotributario = response;
       });
       
       $http.get(API_URL + 'compras/getPais').success(function(response){    
       	$scope.paises = response;
       });
       
       
       
       $http.get(API_URL + 'compras/getFormaPagoDocumento').success(function(response){    
       	$scope.formaPagoDocumento = response;
       });
       
       
   }
	
	 
	
	 $scope.loadProveedor = function() {
		 
		 if(typeof($scope.ci) != "undefined" && $scope.ci.length == 10 || $scope.ci.length == 13 ){
			 $http.get(API_URL + 'compras/getProveedorByCI/' + $scope.ci).success(function(response){
				 if(response){
					 $('#razon').text(response.razonsocialproveedor);
					 $('#telefono').text(response.telefonoproveedor);
					 $('#direccion').text(response.direccionproveedor);
					 $('#tipoidproveedor').text(response.codigotipoid + ' - ' + response.tipoidentificacion);
					 $('#tipoproveedor').text(response.idtipoproveedor + ' - ' + response.nombretipoproveedor);
					 $('#ciudad').text(response.nombreciudad);
					 $scope.compra.idproveedor = response.idproveedor;
					 if((response.codigotipoid == '01')||(response.codigotipoid == '02')||(response.codigotipoid == '03')){
						 $scope.relacionada = true;
					 }
					 $scope.mensaje = false;
				 } else {					 
					 $scope.mensaje = true;
					 $scope.relacionada = false;
					 $('#razon').text('');
					 $('#telefono').text('');
					 $('#direccion').text('');
					 $('#tipoidproveedor').text('');
					 $('#tipoproveedor').text('');
					 $('#ciudad').text('');
				 }
				 		        	         
		        });
		 }	    	
	        
	    }
	 
	
	 $scope.addDetalle = function() {	 
	    $scope.detalle.push($scope.newRow());	              	          
	}; 
	 
	$scope.delDetalle = function(index) {	 
	    $scope.detalle.splice(index, 1);    
	    $scope.calcular();
	};
	 
	$scope.calcular = function (){
		$scope.compra.ivacompra = $scope.compra.totalcompra = valoriva = 0;
   	$scope.compra.subtotalivacompra = $scope.compra.subtotalnoivacompra = $scope.compra.descuentocompra = 0;
		$scope.detalle.forEach(function(item) {
		    item.total = $scope.roundToTwo(parseInt(item.cantidadtotal) * parseFloat(item.precioUnitario));
		    if(parseFloat(item.iva) == 0){
		    	$scope.compra.subtotalnoivacompra = $scope.roundToTwo(parseFloat($scope.compra.subtotalnoivacompra) + item.total);
		    } else {
		    	$scope.compra.subtotalivacompra = $scope.roundToTwo(parseFloat($scope.compra.subtotalivacompra) + item.total);	
		    	$scope.compra.ivacompra = $scope.roundToTwo(parseFloat($scope.compra.ivacompra) + ((item.total*item.iva)/100));
		    }
		});
		  
		if(parseFloat($scope.compra.procentajedescuentocompra) > 0){
			 $scope.compra.descuentocompra = $scope.roundToTwo((($scope.compra.subtotalnoivacompra + $scope.compra.subtotalivacompra) * parseFloat($scope.compra.procentajedescuentocompra))/100);
		}
		$scope.compra.totalcompra = $scope.roundToTwo(($scope.compra.subtotalnoivacompra + $scope.compra.subtotalivacompra - $scope.compra.descuentocompra) + parseFloat($scope.compra.otrosvalores) + $scope.compra.ivacompra);
		$scope.pagoM = true;
		if($scope.compra.totalcompra>=1000){
			$scope.pagoM = false;
		}
	}
	
	
	$scope.roundToTwo = function (num) {    
	    return +(Math.round(num + "e+2")  + "e-2");
	}
	
	$scope.seleccionarPais = function () {    
		$scope.compra.codigopais = '';	
	    if($scope.residente){
	    	$scope.compra.codigopais = '999';	    	
	    }
	}
	
	 $scope.save = function() {		 
		 var datas = [];
		 $scope.impreso = true;
		 $scope.detalle.forEach(function(item) {
			 var row = {cantidad:item.cantidadtotal,precioUnitario:item.precioUnitario,iva:item.iva,ice:item.ice,total:item.total,idbodega: item.testObj.originalObject.idbodega,idproducto: item.productoObj.originalObject.codigoproducto} ;
			 datas.push(row);
		 });
		 
	    	var url = API_URL + "compras";
	    	
	    	$scope.compra.numerodocumentoproveedor = $scope.numero1 + '-' + $scope.numero2 + '-' + $scope.numero3;
       	$scope.compra.detalle = datas;
       	$scope.guardado = true;

	        if ($scope.idcompra > 0){ 
	        	
	        	url += "/" + $scope.idcompra;        	
	        	
	        	$http.put(url, $scope.compra ).success(function (data) {
	        		$scope.message = 'Se guardo correctamente el Item';
	        		$scope.impreso = false;
	        		if(!data.success){	        			
	        			$scope.message = 'Ocurrio un error intentelo mas tarde';
	        		}	                
	                $('#modalMessage1').modal('show');
	                setTimeout("$('#modalMessage1').modal('hide')",3000);
	                
	                $http.get(API_URL + 'compras/getComprasMes/'+$scope.compra.idproveedor).success(function(response){    
	                	if(($scope.compra.totalcompra >= 50)||(response >= 2)){
		                	$scope.retencion =  false;
		                }
	                });            
	            });
	            
	            
	        } else {	 
	        	
	        	$http.post(url,$scope.compra).success(function (data) {
	        		$scope.message = 'Se insertó correctamente el Item';
	        		$scope.impreso = false;
	        		if(!data.success){	        			
	        			$scope.message = 'Ocurrio un error intentelo mas tarde';
	        		} else {
	        			$scope.idcompra = data.id;
	        		}
	        			
	                $('#modalMessage1').modal('show');
	                setTimeout("$('#modalMessage1').modal('hide')",3000);
	                
	                $http.get(API_URL + 'compras/getComprasMes/'+$scope.compra.idproveedor).success(function(response){    
	                	if(($scope.compra.totalcompra >= 50)||(response >= 2)){
		                	$scope.retencion =  false;
		                }
	                });             

	            });
	        }      
	    	
	    }
	
	 $scope.activarPestana = function(){
		 $scope.pestana = true;
	 }
	 
	 $scope.pagarCompra = function(){
		 $scope.pagado = true;	
	        $http.get(API_URL + 'compras/pagarCompra/' + $scope.compra.codigocompra).success(function(response) {
	        	 $scope.message = 'La compra se ha Pagado.';
	        	
	        	if(!response.success){
       			$scope.pagado = false;
       			$scope.message = 'Ocurrio un error intentelo mas tarde';
       		}	
	           
	            $('#modalMessage1').modal('show');
	            setTimeout("$('#modalMessage1').modal('hide')",3000);
	        });
	    }
	 
	 $scope.anularCompra = function(){	
		 $scope.anulado = true;
	        $http.get(API_URL + 'compras/anularCompra/' + $scope.compra.codigocompra).success(function(response) {
	        	 $scope.message = 'La compra se ha Anulado.';
	        	 
	        	if(!response.success){
       			$scope.anulado = false;
       			$scope.message = 'Ocurrio un error intentelo mas tarde';
       		}	
	        	$('#modalConfirmAnular1').modal('hide'); 
	            $('#modalMessage1').modal('show');
	            setTimeout("$('#modalMessage1').modal('hide')",3000);
	        });
	    }
	 
	 
	 
   $scope.showModalConfirm = function(){       
           $('#modalConfirmAnular1').modal('show');       
   }
   
   $scope.pdf = function(){
	   id =  $scope.idcompra;
   	if(id == 0){
   		id = $scope.compra.codigocompra;
   	}
	   window.open(API_URL + 'compras/pdf/'+id);
   }
   
   $scope.excel = function(){
	   id =  $scope.idcompra;
   	if(id == 0){
   		id = $scope.compra.codigocompra;
   	}
 	   window.open(API_URL + 'compras/excel/'+id);
    }
    
   $scope.imprimir = function (){
	   id =  $scope.idcompra;
   	if(id == 0){
   		id = $scope.compra.codigocompra;
   	}
   	var posicion_x; 
   	var posicion_y; 
   	var ancho = 1000;
   	var alto = 500;
   	posicion_x=(screen.width/2)-(ancho/2); 
   	posicion_y=(screen.height/2)-(alto/2); 
   	var accion = API_URL + "compras/imprimir/"+id;
   	var opciones="toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width="+ancho+",height="+alto+",left="+posicion_x+",top="+posicion_y;
   	window.open(accion,"",opciones);
   }
   
   
   $scope.InicioList=function() {
	   $scope.listado =  true;
	   $scope.initLoad();
   }
   
   
   
    
});

function defaultImage (obj){
	obj.src = 'img/empleado.png';
}

$(function(){  

    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });   
    
    
})
