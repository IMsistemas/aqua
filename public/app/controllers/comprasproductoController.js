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
            $scope.estados = [{id: 1, nombre: "Anulado"},{id: 0, nombre: "No Anulado"}]
           
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
	        	 $scope.anulado =  false;
	        	$scope.message = 'La compra se ha Anulado.';	        	 
	        	if(!response.success){
	        		$scope.compra_anular = anular;
       			$scope.message = 'Ocurrio un error intentelo mas tarde';
       		}	
	        	$('#modalConfirmAnular').modal('hide'); 
	        	$('#modalConfirmAnular1').modal('hide'); 
	            $('#modalMessage').modal('show');
	            $('#modalMessage1').modal('show');
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
   
  
	 $scope.ivaProLabel = '';
	 $scope.ivaPro = 0;
	
	$scope.newRow = function(){
		$scope.read =  false;
		return {cantidad:0,descuento:0,precioUnitario:0,iva: $scope.ivaPro,ice:0,total:0,productoObj:null,testObj:null}	
	}
	
	$scope.openForm = function(id){        	
		$scope.listado = false;
		$scope.idcompra = id;
		$scope.detalle = [$scope.newRow()];

		/*$http.get(API_URL + 'compras/getConfiguracion').success(function(response){    
       	$scope.configuracion = response;
       
       });*/
		if($scope.idcompra == 0){
			$http.get(API_URL + 'compras/getLastCompra').success(function(response){    
	        	$scope.compra = response;
	        	$scope.compra.ivacompra =  0;
	        	
	        	$scope.compra.subtotalconimpuestocompra = $scope.compra.subtotalcerocompra = 0;
	        	$scope.compra.totaldescuento = $scope.compra.id = 0;
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
				 $('#razon').val('');
				 $('#telefono').val('');
				 $('#direccion').val('');
				 $scope.anulado =  true;
	        });
			
		} else {
			$scope.read =  true;
			$http.get(API_URL + 'compras/'  + $scope.idcompra ).success(function(response){
				$scope.compra = response;
				
				$scope.anulado = ($scope.compra.estaAnulada == 0)?true:false;
				
				
				
				if($scope.compra.estaanulada == 1){
					$scope.impreso  = true; 
				}
				$scope.compra.codigocompra = $scope.compra.iddocumentocompra;
				autorization = $scope.compra.numdocumentocompra.split("-");
				$scope.numero1 = autorization[0];
				$scope.numero2 = autorization[1];
				$scope.numero3 = autorization[2];
				
				$scope.ci = $scope.compra.proveedor.numdocidentific;
				$('#razon').val($scope.compra.proveedor.razonsocial);
				 $('#telefono').val($scope.compra.proveedor.telefonoprincipal);
				 $('#direccion').val($scope.compra.proveedor.direccion);
				 $('#iva').val($scope.compra.proveedor.nametipoimpuestoiva);
				 $scope.compra.idproveedor = $scope.compra.proveedor.idproveedor;
				 
					
				 
				
           });
			
			$http.get(API_URL + 'compras/getDetalle/'  + $scope.idcompra ).success(function(response){
				$scope.detalle = response;
				$scope.detalle.forEach(function(item) {
					$scope.compra.idbodega = item.idbodega;
				 });
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
       
       $http.get(API_URL + 'compras/getBodegas').success(function(response){    
          	$scope.bodegas = response;
          });
       
       
       $http.get(API_URL + 'compras/getFormaPagoDocumento').success(function(response){    
       	$scope.TiposPago = response;
       });
       
       
   }
	
	 
	
	 $scope.loadProveedor = function() {
		 
		 if(typeof($scope.ci) != "undefined" && $scope.ci.length == 10 || $scope.ci.length == 13 ){
			 $http.get(API_URL + 'compras/getProveedorByCI/' + $scope.ci).success(function(response){
				 if(response){
					 $('#razon').val(response.razonsocial);
					 $('#telefono').val(response.telefonoprincipal);
					 $('#direccion').val(response.direccion);					 
					 $('#iva').val(response.nametipoimpuestoiva);
					 $scope.compra.idproveedor = response.idproveedor;
					 $scope.compra.idtipoimpuestoiva = response.idtipoimpuestoiva;
					 
					 $scope.ivaPro = response.porcentaje;
					 $scope.detalle.forEach(function(item) {
						 item.iva = $scope.ivaPro;
					 });
					 
					 $scope.mensaje = false;
				 } else {					 
					 $scope.mensaje = true;
					 $scope.relacionada = false;
					 $('#razon').val('');
					 $('#telefono').val('');
					 $('#direccion').val('');
					 $('#iva').val('');
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
		$scope.compra.ivacompra =  valoriva = 0;
		$scope.compra.subtotalconimpuestocompra = $scope.compra.subtotalcerocompra = $scope.compra.totaldescuento = 0;
		$scope.compra.subtotalcerocompra = 0;
		$scope.compra.totaldescuento = 0;
		$scope.detalle.forEach(function(item) {
		    item.total = $scope.roundToTwo(parseInt(item.cantidad) * parseFloat(item.precioUnitario));
		    if(parseFloat(item.iva) == 0){
		    	$scope.compra.subtotalcerocompra = $scope.roundToTwo(parseFloat($scope.compra.subtotalcerocompra) + item.total);
		    } else {
		    	$scope.compra.subtotalconimpuestocompra = $scope.roundToTwo(parseFloat($scope.compra.subtotalconimpuestocompra) + item.total);	
		    	$scope.compra.ivacompra = $scope.roundToTwo(parseFloat($scope.compra.ivacompra) + ((item.total*item.iva)/100));
		    }
		});
		
		
		$scope.compra.valortotalcompra = $scope.roundToTwo(($scope.compra.subtotalcerocompra + $scope.compra.subtotalconimpuestocompra - $scope.compra.totaldescuento) +  $scope.compra.ivacompra);
		$scope.pagoM = true;
		
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
			 var row = {cantidad:item.cantidad,preciounitario:item.precioUnitario,iva:item.iva,ice:item.ice,descuento:item.descuento,total:item.total,idbodega:$scope.compra.idbodega,idcatalogitem: item.productoObj.originalObject.idcatalogitem} ;
			 datas.push(row);
		 });
		 
	    	var url = API_URL + "compras";
	    	
	    	$scope.compra.numdocumentocompra = $scope.numero1 + '-' + $scope.numero2 + '-' + $scope.numero3;
       	$scope.compra.detalle = datas;
       	$scope.guardado = true;
       console.log($scope.compra);
       	
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
	 
	 	 
   $scope.showModalConfirm1 = function(){       
	   $scope.compra_anular = $scope.compra.iddocumentocompra;
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
   
   $scope.calculateLength = function(field, length) {
       var text = $("#" + field).val();
       var longitud = text.length;
       var relleno = '';
       if (longitud == length) {
           $("#" + field).val(text);
       } else {
           var diferencia = parseInt(length) - parseInt(longitud);
           
           if (diferencia == 1) {
               relleno = '0';
           } else {
               var i = 0;
               while (i < diferencia) {
                   relleno += '0';
                   i++;
               }
           }
           $("#" + field).val(relleno + text);
          
       }
       return relleno+text;
   };
   
   
   $scope.onlyNumber = function ($event, length, field) {

       
        var k = $event.charCode;
           if (k == 8 || k == 0){
        	   return true;
           }
           
           if (length != undefined) {
               var valor = $('#' + field).val();
               if (valor.length == length) $event.preventDefault();
           }          
           
           
           var patron = /\d/;
           var n = String.fromCharCode(k);

           if (n === ".") {
               return true;
           } else {
        	  
	           if(patron.test(n) === false){
	                   $event.preventDefault();
	                   
	            } else { 
	            	
            	   return true;
               }
           }
          
       };
   
       $('.datepicker').datetimepicker({
           locale: 'es',
           format: 'YYYY-MM-DD',
           ignoreReadonly: true
       });  
       
});

function defaultImage (obj){
	obj.src = 'img/empleado.png';
}


