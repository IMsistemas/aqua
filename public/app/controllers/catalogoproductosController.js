//var appUp = angular.module('softver-aqua-upload', ['ngFileUpload','softver-aqua']);


app.controller('catalogoproductosController',  function($scope, $http, API_URL,Upload,$timeout) {

    $scope.producto_del = 0;    
    $scope.items = [];
    $scope.select_cuenta = null;
    $scope.lineas = $scope.sublineasFiltro  =[];   
    $scope.select_cuentaC = null;
    $scope.opcion = 0;

    $scope.listopenbalance = [];
    $scope.BodegasList = [];
    $scope.item_cuenta = null;
    $scope.producto = null;
    $scope.ob_anular = 0;
    
    $scope.searchByFilter = function(){
    
        var t_search = null;
        var t_lineaId = null;
        var t_subId = null;
        
        if($scope.search != undefined && $scope.search != ''){
            t_search = $scope.search;
            /*var last = t_search.substring(t_search.length -1);
            if (last === "."){ 
            	t_search = t_search.substring(0,t_search.length -1);
            }*/
        }
        
        if($scope.lineaFiltro != undefined && $scope.lineaFiltro != ''){
            t_lineaId = $scope.lineaFiltro;            
        }
        
        if($scope.idCategoria != undefined && $scope.idCategoria != ''){
            t_subId = $scope.idCategoria;            
        }

        var filter = {
            text: t_search,
            linId: t_lineaId,
            subId: t_subId
        };

        console.log(filter);

        $scope.productos = [];
        $http.get(API_URL + 'catalogoproducto/getCatalogoProductos/' + JSON.stringify(filter)).success(function(response){

            //$scope.items = response;

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                if (response[i].foto == null || response[i].foto == '') {
                    response[i].foto = 'img/product_services.jpg';
                }

            }

            $scope.items = response;

        });
    }
    
    $scope.initLoad = function(){
    	$scope.searchByFilter();
        $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
            $scope.lineasFiltro = response;
           
        });
       
    };
    

	
    $scope.toggle = function(modalstate, id) {

    	$scope.modalstate = modalstate;
    	$scope.formProducto.$setPristine();
        $scope.formProducto.$setUntouched(); 

        switch (modalstate) {
            case 'add':

                $('#btn-ob').hide();

            	$scope.thumbnail = {
        	        dataUrl: ''
        	    };
                $scope.form_title = 'Nuevo Item';
                $scope.producto = null;
                $scope.t_cuentacontableingreso = '';
                $scope.t_cuentacontable = '';
                $http.get(API_URL + 'catalogoproducto/getLastCatalogoProducto' )
                .success(function(response) {
                	$scope.producto = response; 
                	$http.get(API_URL + 'catalogoproducto/getTipoItem').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nameclaseitem, id: response[i].idclaseitem})
                        }
                        $scope.tipo = array_temp;
                        $scope.producto.idclaseitem = '';

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoICE').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoice, id: response[i].idtipoimpuestoice})
                        }
                        $scope.imp_ice = array_temp;
                        $scope.producto.idtipoimpuestoice = '';

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoIVA').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                        $scope.producto.idtipoimpuestoiva = '';

                    });
                    $scope.sublineas = [{label: '-- Seleccione --', id: ''}];
                     
                    $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
                    	var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nombrecategoria, id: response[i].jerarquia})
                        }
                        $scope.lineas = array_temp;
                        $scope.s_linea = '';

                        $http.get(API_URL + 'cliente/getIVADefault').success(function(response){

                            if (response[0].optionvalue !== null && response[0].optionvalue !== '') {
                                $scope.producto.idtipoimpuestoiva = parseInt(response[0].optionvalue);
                            }

                            $scope.producto.idcategoria = '';
                            $('#modalAction').modal('show');


                        });


                    });
                });

                

                break;
            case 'edit':

            	$scope.form_title = "Editar Item";


                $scope.id = id;
                $scope.producto = null;              
                $http.get(API_URL + 'catalogoproducto/'  + id ).success(function(response){

                    console.log(response);

                  	$scope.producto = response;

                  	if (response.idclaseitem === 1) {
                        $('#btn-ob').show();
                    } else {
                        $('#btn-ob').hide();
                    }


                	$http.get(API_URL + 'catalogoproducto/getTipoItem').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nameclaseitem, id: response[i].idclaseitem})
                        }
                        $scope.tipo = array_temp;                       

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoICE').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: null}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoice, id: response[i].idtipoimpuestoice})
                        }
                        $scope.imp_ice = array_temp;

                    });

                    $http.get(API_URL + 'catalogoproducto/getImpuestoIVA').success(function(response){

                        var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nametipoimpuestoiva, id: response[i].idtipoimpuestoiva})
                        }
                        $scope.imp_iva = array_temp;
                       

                    });
                    $scope.sublineas = [{label: '-- Seleccione --', id: ''}];
                     
                    $http.get(API_URL + 'catalogoproducto/getCategoriasToFilter').success(function(response){
                    	var longitud = response.length;
                        var array_temp = [{label: '-- Seleccione --', id: ''}];
                        for(var i = 0; i < longitud; i++){
                            array_temp.push({label: response[i].nombrecategoria, id: response[i].jerarquia})
                        }
                        $scope.lineas = array_temp;           
                       
                        
                    });


                    var ids = $scope.producto.jerarquia.split('.');
                	$scope.s_linea = ids[0];

                	$scope.idcat = $scope.producto.idcategoria;
	                $scope.loadSubLinea($scope.s_linea,false, $scope.idcat);
	                 
	                $scope.t_cuentacontable = $scope.producto.concepto;	                
	                $scope.t_cuentacontableingreso = $scope.producto.c2;
	                
	                
	                $scope.thumbnail = {
	            	        dataUrl: $scope.producto.foto
	            	    };
	                
	                $('#modalAction').modal('show');
                    
                    
                });

                break;
            case 'info':

            	$http.get(API_URL + 'catalogoproducto/'  + id ).success(function(response){                	
                    $scope.producto = response;                                       
                    var ids = $scope.producto.jerarquia.split('.');    	
                    var filter = {
                            padre:  ids[0],
                            nivel: 1
                        };
                	
                    $http.get(API_URL + 'catalogoproducto/getCategoriasHijas/' + JSON.stringify(filter)).success(function(response){
	                     	$scope.linea = response[0].nombrecategoria;
	                });
	                
	                console.log($scope.producto);
                    $('#modalInfoEmpleado').modal('show');
                });


                break;

            default:
                break;
        }
    };

    $scope.showPlanCuenta = function (opcion) {

        $scope.item_cuenta = null;

    	$scope.opcion = opcion;
        $http.get(API_URL + 'empleado/getPlanCuenta').success(function(response){
            $scope.cuentas = response;
            $scope.cuentas.push({"idplancuenta":0,"concepto": "NINGUNO"});
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.selectCuenta = function () {

        if ($scope.item_cuenta !== null) {

            $scope.item_cuenta.contabilidad = $scope.select_cuenta;

            console.log($scope.item_cuenta);

        } else {

            var selected = $scope.select_cuenta;
            var concepto = selected.concepto;
            var idplan = selected.idplancuenta;

            if(idplan == 0){
                concepto = "";
                idplan = null;
            }
            if($scope.opcion == 1){
                $scope.t_cuentacontable = concepto;
                $scope.producto.idplancuenta = idplan;
            } else {
                $scope.t_cuentacontableingreso = concepto;
                $scope.producto.idplancuenta_ingreso = idplan;
            }

        }
    	


        $('#modalPlanCuenta').modal('hide');
    };

    $scope.click_radio = function (item) {
        $scope.select_cuenta = item;
    };

    $scope.hideModalMessage = function () {
        setTimeout("$('#modalMessage').modal('hide')", 3000);
    };
    
    $scope.loadSubLinea = function(padre,filtro,value1) {
    	var filter = {
                padre: padre,
                nivel: 2
            };
    	 if(padre > 0){
    	 
        $http.get(API_URL + 'catalogoproducto/getCategoriasHijas/' + JSON.stringify(filter)).success(function(response){
        	if(filtro){
        		$scope.sublineasFiltro = response; 
        		$scope.idCategoria = '';
        	}else{
        		var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: 0}];
                for(var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nombrecategoria, id: response[i].idcategoria})
                }
                
                $scope.sublineas = array_temp;
                $scope.producto.idcategoria = value1; 	
                
        	}
        	         
        });
    	 } else {
    		 $scope.sublineas =  [{label: '-- Seleccione --', id: 0}];
    		 if(filtro){
    			 $scope.idCategoria = '';
    			 $scope.initLoad(); 
    		 }
    		 
    	 }
    	 
    }
    
    $scope.save = function(modalstate, id) {

        var url = API_URL + "catalogoproducto";

        if (modalstate === 'edit'){
            url += "/" + id;
            $scope.producto._method= 'PUT'; 

        }  

        /*if ($scope.producto.idtipoimpuestoice==null){
           $scope.producto.idtipoimpuestoice=undefined; 

        } */
        	
        
        console.log($scope.producto);
        
        $scope.upload = Upload.upload({
      	      url: url,
      	      data: $scope.producto,   
      	      
      	}).success(function(data, status, headers, config) {
      	    	$scope.initLoad();
      	    	$scope.message = 'El item se guardó correctamente...';
              	$('#modalAction').modal('hide');
              	$('#modalMessage').modal('show');
              	setTimeout("$('#modalMessage').modal('hide')",3000);
       });
        
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
    
    $scope.showModalConfirm = function(id){
        $scope.empleado_del = id;
        $http.get(API_URL + 'catalogoproducto/'  + id).success(function(response) {
            $scope.producto = response;
            $('#modalConfirmDelete').modal('show');
        });
    }

    $scope.destroyProducto = function(){
        $http.delete(API_URL + 'catalogoproducto/' + $scope.empleado_del).success(function(response) {

            $('#modalConfirmDelete').modal('hide');

            if (response.success === true) {

                $scope.initLoad();
                $scope.empleado_del = 0;
                $scope.message = 'Se eliminó correctamente el Item seleccionado...';
                $('#modalMessage').modal('show');
                setTimeout("$('#modalMessage').modal('hide')",3000);

            } else {

                if (response.exists != undefined) {

                    $scope.message_error = 'No se puede eliminar el item, ya que ha sido utilizado en el sistema...';

                } else {

                    $scope.message_error = 'Ha ocurrido un error al intentar eliminar el item seleccionado...';

                }

                $('#modalMessageError').modal('show');

            }



        });
    }   

    $scope.formatDate = function(date){
        var dateOut = new Date(date);
        return dateOut;
    };

    $scope.thumbnail = {
            dataUrl: ''
    };

    $scope.fileReaderSupported = window.FileReader != null;

	$scope.photoChanged = function(files){
	        if (files != null) {
	            var file = files[0];
	        if ($scope.fileReaderSupported && file.type.indexOf('image') > -1) {
	            $timeout(function() {
	                var fileReader = new FileReader();
	                fileReader.readAsDataURL(file);
	                fileReader.onload = function(e) {
	                    $timeout(function(){
	 $scope.thumbnail.dataUrl = e.target.result;
	                    });
	                }
	            });
	        }
	    }
	    };
  

    /*
    --------------------------------------- OPEN BALANCE ---------------------------------------------------------------
     */

    $scope.showListOpenBalance = function () {

        $http.get(API_URL + 'catalogoproducto/getOpenBalanceProducto/' + $scope.producto.idcatalogitem).success(function(response){

            $scope.openbalance_item = $scope.producto.nombreproducto;

            var longitud = response.length;

            $scope.listopenbalance = [];

            for (var i = 0; i < longitud; i++) {

                var item = {
                    id: response[i].idopenbalanceitems,
                    fecha:response[i].fecha,
                    idbodega: (response[i].idbodega).toString(),
                    contabilidad:response[i].cont_plancuenta,
                    totalvalor: response[i].totalvalor,
                    totalstock: response[i].totalstock,
                    estadoanulado: response[i].estadoanulado
                };

                $scope.listopenbalance.push(item);

            }

            $(document).ready (function(){
                $('.datepickerA').datetimepicker({
                    locale: 'es',
                    format: 'YYYY-MM-DD'
                });
            });

            $('#modalOpenBalance').modal('show')

        });


    };

    $scope.getBodegas = function () {

        $http.get(API_URL + 'catalogoproducto/getBodegas').success(function(response){

            $scope.BodegasList = response;

            var longitud = response.length;
            var array_temp = [{label: '-- Seleccione --', id: ''}];

            for(var i = 0; i < longitud; i++){
                array_temp.push({label: response[i].namebodega, id: parseInt(response[i].idbodega)})
            }

            $scope.listbodegas = array_temp;

        });

    };

    $scope.reafirmData = function (tipo, item, field) {

        item.fecha = $('#' + tipo + field).val();

    };

    $scope.createRowOB = function () {

        $(document).ready (function(){
            $('.datepickerA').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });
        });

        var item = {
            id: null,
            fecha: null,
            idbodega: '',
            contabilidad:'',
            totalvalor: 0,
            totalstock: 0,
            estadoanulado: false
        };
        $scope.listopenbalance.push(item);

        $(document).ready (function(){
            $('.datepickerA').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });
        });

    };

    $scope.showPlanCuentaItem = function (item) {

        $scope.item_cuenta = item;

        $http.get(API_URL + 'configuracion/getPlanCuenta').success(function(response){

            $scope.cuentas = response;
            $('#modalPlanCuenta').modal('show');
        });

    };

    $scope.saveItemOB = function (item) {

        var Transaccion={
            fecha: item.fecha,
            idtipotransaccion: 1,
            numcomprobante: 1,
            descripcion: 'OPEN BALANCE ITEM: ' + $scope.producto.nombreproducto
        };

        var RegistroC = [];

        var aux_bodegaseleccionada = {};

        for(var i = 0; i < $scope.BodegasList.length; i++){
            console.log($scope.BodegasList[i]);
            if(parseInt($scope.BodegasList[i].idbodega) === parseInt(item.idbodega)){
                aux_bodegaseleccionada = $scope.BodegasList[i];
            }
        }

        var cuentaBodega = {
            idplancuenta: aux_bodegaseleccionada.idplancuenta,
            concepto: aux_bodegaseleccionada.concepto,
            controlhaber: aux_bodegaseleccionada.controlhaber,
            tipocuenta: aux_bodegaseleccionada.tipocuenta,
            Debe: item.totalvalor,
            Haber: 0,
            Descipcion: 'OPEN BALANCE BODEGA: ' + aux_bodegaseleccionada.namebodega + ', PARA ITEM: ' + $scope.producto.nombreproducto
        };

        var cuentaInicial = {
            idplancuenta: item.contabilidad.idplancuenta,
            concepto: item.contabilidad.concepto,
            controlhaber: item.contabilidad.controlhaber,
            tipocuenta: item.contabilidad.tipocuenta,
            Debe: 0,
            Haber: item.totalvalor,
            Descipcion: 'OPEN BALANCE CUENTA INICIAL PARA ITEM: ' + $scope.producto.nombreproducto
        };

        RegistroC.push(cuentaBodega);
        RegistroC.push(cuentaInicial);


        var Contabilidad = {
            transaccion: Transaccion,
            registro: RegistroC
        };

        //--proceso kardex
        var kardex = [];

        var producto = {
            idtransaccion: 0,
            idcatalogitem: $scope.producto.idcatalogitem,
            idbodega: item.idbodega,
            fecharegistro: item.fecha,
            cantidad: parseInt(item.totalstock),
            costounitario: parseFloat(item.totalvalor) / parseInt(item.totalstock),
            costototal: parseFloat(item.totalvalor),
            tipoentradasalida: 1,
            estadoanulado: true,
            descripcion: 'OPEN BALANCE ITEM: ' + $scope.producto.nombreproducto
        };

        kardex.push(producto);
        //--proceso kardex


        var openBalance = {

            idtransaccion: 0,
            idcatalogitem: $scope.producto.idcatalogitem,
            idbodega: item.idbodega,
            idplancuenta: item.contabilidad.idplancuenta,
            fecha: item.fecha,
            totalstock: item.totalstock,
            totalvalor: item.totalvalor

        };

        var data = {
            DataContabilidad: Contabilidad,
            Datakardex: kardex,
            DataOpenBalance: openBalance
        };

        console.log(data);

        var transaccion = {
            datos:JSON.stringify(data)
        };

        $http.post(API_URL + 'catalogoproducto/saveOpenBalance', transaccion).success(function (response) {

            console.log(response);

            if (response.success === true) {

                $scope.showListOpenBalance();

                $scope.message = 'Se insertó correctamente el Open Balance...';

                $('#modalMessage').modal('show');

            }
            else {

                $scope.message_error = 'Ha ocurrido un error al intentar guardar la Compra...';

                $('#modalMessageError').modal('show');
            }

        }).error(function(err){
            console.log(err);
        });

    };

    $scope.anular = function (item) {

        $scope.ob_anular = item.id;

        $('#modalConfirmAnular').modal('show');
    };

    $scope.anularOB = function () {
        var object = {
            idopenbalanceitems: $scope.ob_anular
        };

        $http.post(API_URL + 'catalogoproducto/anularOB', object).success(function(response) {

            $('#modalConfirmAnular').modal('hide');

            if(response.success === true){

                $scope.showListOpenBalance();

                $scope.ob_anular = 0;
                $scope.message = 'Se ha anulado el Open Balance seleccionado...';
                $('#modalMessage').modal('show');

            } else {
                $scope.message_error = 'Ha ocurrido un error al intentar anular el Open Balance seleccionado...';
                $('#modalMessageError').modal('show');
            }

        });
    };

    $scope.initLoad();

    $scope.getBodegas();

});

function defaultImage (obj){

    console.log(obj);

	obj.src = 'img/producto.png';
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#fotoPre').attr('src', e.target.result);
            console.log(e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#foto").change(function () {
    readURL(this);
});

$("#fotoedit").change(function () {
    readURL(this);
});

