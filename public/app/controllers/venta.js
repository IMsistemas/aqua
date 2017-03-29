app.controller('Venta', function($scope, $http, API_URL) {
$scope.FechaRegistro=now();
$scope.FechaEmision=now();
$scope.VerFactura=2;
$scope.DICliente="";
$scope.Cliente={};
$scope.Bodegas=[];
$scope.Formapago=[];
$scope.PuntoVenta=[];
$scope.PuntoVentaSeleccionado={};
$scope.Configuracion=[];
$scope.mensaje="";
$scope.AgenteVenta="";
$scope.t_establ="";
$scope.t_pto="";
$scope.Bodega="";
$scope.observacion="";
$scope.Allventas=[];
$scope.cmbFormapago="";

	$scope.NumeroRegistroVenta=function() {
        $http.get(API_URL + 'DocumentoVenta/NumRegistroVenta')
        .success(function(response){
        	console.log(response)

        /*NoVenta iddocumentoventa*/
            if(response.iddocumentoventa!=null ){
                $scope.NoVenta=parseInt(response.iddocumentoventa)+1;
                $("#t_secuencial").val(String(parseInt(response.iddocumentoventa)+1));
                $scope.calculateLength("t_secuencial",9);
                $scope.t_secuencial=$("#t_secuencial").val();
            }else{
            	$scope.NoVenta=1;
            	$("#t_secuencial").val("1")
            	$scope.calculateLength("t_secuencial",9);
            	$scope.t_secuencial=$("#t_secuencial").val();
            	
            }
        });
    };
	///---All Facturas
	$scope.AllDocVenta=function () {
		$http.get(API_URL + 'DocumentoVenta/getAllFitros')
	        .success(function(response){
	            $scope.Allventas=response;
	            console.log(response);
	     });
	};
	///---Cliente
	$scope.BuscarCliente=function () {
		if($scope.DICliente!=""){
			$http.get(API_URL + 'DocumentoVenta/getInfoClienteXCIRuc/'+$scope.DICliente)
		        .success(function(response){
		            $scope.Cliente=response[0];
		            console.log($scope.Cliente);
		     });
		}else{
			QuitarClasesMensaje();
	        $("#titulomsm").addClass("btn-warning");
	        $("#msm").modal("show");
	        $scope.Mensaje="Ingrese un número de identificación";
		}
	};
	///---
	$scope.ConfigContable=function(){
		$http.get(API_URL + 'DocumentoVenta/porcentajeivaiceotro')
	        .success(function(response){
	            $scope.Configuracion=response;
	            console.log(response);
	            if(String($scope.Configuracion[0].IdContable)==""){
	            	QuitarClasesMensaje();
			        $("#titulomsm").addClass("btn-danger");
			        $("#msm").modal("show");
			        $scope.Mensaje="La venta necesita que llene los campos de configuracion esten llenos para poder realizar esta transaccion";
	            }
	     }).error(function(res){
	     	QuitarClasesMensaje();
	        $("#titulomsm").addClass("btn-danger");
	        $("#msm").modal("show");
	        $scope.Mensaje="La venta necesita que llene los campos de configuracion esten llenos para poder realizar esta transaccion";
	     });
	};
	///---
	$scope.GetBodegas=function () {
		$http.get(API_URL + 'DocumentoVenta/AllBodegas')
	        .success(function(response){
	            $scope.Bodegas=response;
	            console.log(response);
	     });
	};
	///---
	$scope.GetFormaPago=function () {
		$http.get(API_URL + 'DocumentoVenta/formapago')
	        .success(function(response){
	            $scope.Formapago=response;
	            console.log(response);
	     });
	};
	///---
	$scope.GetPuntodeVenta=function(){
		$http.get(API_URL + 'DocumentoVenta/getheaddocumentoventa')
	        .success(function(response){
	            $scope.PuntoVenta=response;
	            console.log(response);
	    });
	};
	///---

	$scope.DataNoDocumento=function() {
		$scope.PuntoVenta
		if($scope.AgenteVenta!=""){
			for(x=0;x<$scope.PuntoVenta.length;x++){
				if($scope.PuntoVenta[x].idpuntoventa==$scope.AgenteVenta){
					$scope.PuntoVentaSeleccionado=$scope.PuntoVenta[x];
					var aux_establecimiento=$scope.PuntoVenta[x].ruc.split("-");
					$scope.t_establ=aux_establecimiento[0];
					$scope.t_pto=$scope.PuntoVenta[x].codigoptoemision;
				}
			}
		}
	};
	///---
	$scope.items=[];
	$scope.Agregarfila=function(){
		console.log($scope.Bodega);
		if($scope.Bodega!=""){
			var item={
				productoObj:null,
				cantidad:0,
				precioU:0,
				descuento:0,
				iva :0,
				ice:0,
				total:0
			};
			$scope.items.push(item);
		}else{
			QuitarClasesMensaje();
	        $("#titulomsm").addClass("btn-warning");
	        $("#msm").modal("show");
	        $scope.Mensaje="Seleccione una bodega";
		}
	};
	///---
    $scope.QuitarItem=function (item) {
        var posicion= $scope.items.indexOf(item);
         $scope.items.splice(posicion,1);
    };
    ///---
    $scope.Subtotalconimpuestos=0;
    $scope.Subtotalcero=0;
    $scope.Subtotalnobjetoiva=0;
    $scope.Subototalexentoiva=0;
    $scope.Subtotalsinimpuestos=0;
    $scope.Totaldescuento=0;
    $scope.ValICE=0;
    $scope.ValIVA=0;
    $scope.ValIRBPNR=0;
    $scope.ValPropina=0;
    $scope.ValorTotal=0;
    $scope.CalculaValores=function(){
    	var aux_subtotalconimpuestos=0;

    	for(x=0;x<$scope.items.length;x++){
    		console.log($scope.items[x]);
    		if(parseInt($scope.items[x].iva)==0 ){
    			if($scope.items[x].cantidad!=undefined && $scope.items[x].precioU!=undefined ){
    				if(parseFloat($scope.items[x].descuento)>0){
    					var aux_descuento=(((parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU))*(parseFloat($scope.items[x].descuento)))/100);
    					var preciouxcantida=(parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU));
    					$scope.items[x].total=(preciouxcantida-aux_descuento).toFixed(4);
    				}else{
    					$scope.items[x].total=(parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU));
    				}
    			}
    		}
    	}

    	for(x=0;x<$scope.items.length;x++){
    		console.log($scope.items[x]);
    		if(parseInt($scope.items[x].iva)==0 ){
    			if($scope.items[x].cantidad!=undefined && $scope.items[x].precioU!=undefined ){
    				aux_subtotalconimpuestos+=parseFloat($scope.items[x].total);
    			}
    		}
    	}
    	
    	$scope.Subtotalconimpuestos= aux_subtotalconimpuestos.toFixed(4);
    	$scope.ValIVA=(($scope.Subtotalconimpuestos*parseInt($scope.Cliente.porcentaje))/100).toFixed(4);
    	$scope.ValorTotal=(parseFloat($scope.Subtotalconimpuestos)+parseFloat($scope.ValIVA)).toFixed(4);
    };
    ///---
    $scope.IniGuardarFactura=function(){
    	$("#ConfirmarVenta").modal("show");
    };
    $scope.EnviarDatosGuardarVenta=function(){
    	var Transaccion={
    		fecha:convertDatetoDB($("#FechaEmision").val()),
    		idtipotransaccion: 2,
    		numcomprobante:1,
    		descripcion: $scope.observacion
    	};
    	//Asiento contable Partida doble 	ay123
    	var RegistroC=[];
    	//Asiento contable cliente -- el cliente por lo genearal es un activo entonces el cliente aumenta una deuda por el debe 
    	var aux_bodegaseleccionada={};
    	for(i=0;i<$scope.Bodegas.length;i++){
    		if(parseInt($scope.Bodegas[i].idbodega)==parseInt($scope.Bodega)){
    			aux_bodegaseleccionada=$scope.Bodegas[i];
    		}
    	}
    	var cliente={
    		idplancuenta: $scope.Cliente.idplancuenta,
    		concepto: $scope.Cliente.concepto,
    		controlhaber: $scope.Cliente.controlhaber,
    		tipocuenta: $scope.Cliente.tipocuenta,
    		Debe: $scope.ValorTotal,
    		Haber: 0,
    		Descipcion:''
    	};

    	RegistroC.push(cliente);
    	//--Sacar producto de bodega -- el producto es un activo pero como se lo vente disminuye por el haber
    	for(x=0;x<$scope.items.length;x++){
    		if($scope.items[x].productoObj.originalObject.idclaseitem==1){
    			var producto={
		    		//idplancuenta: $scope.items[x].productoObj.originalObject.idplancuenta,
		    		idplancuenta: aux_bodegaseleccionada.idplancuenta,
		    		concepto: aux_bodegaseleccionada.concepto,
		    		controlhaber: aux_bodegaseleccionada.controlhaber,
		    		tipocuenta: aux_bodegaseleccionada.tipocuenta,
		    		Debe: 0,
		    		Haber: (parseFloat($scope.items[x].productoObj.originalObject.costopromedio)*parseInt($scope.items[x].cantidad)).toFixed(4),
		    		Descipcion:''
		    	};
		    	RegistroC.push(producto);
    		}	
    	}
    	//--Sacar producto de bodega -- el producto es un activo pero como se lo vente disminuye por el haber
    	//--Costo venta producto
    	//---obtener costo venta
    	var costoventa={};
    	for(i=0;i<$scope.Configuracion.length;i++){
    		if($scope.Configuracion[i].Descripcion=="CONT_COSTO_VENTA"){
    			var auxcosto=$scope.Configuracion[i].Contabilidad;
    			costoventa=auxcosto[0];
    		}
    	}
    	//---obtener costo venta
    	for(x=0;x<$scope.items.length;x++){
    		if($scope.items[x].productoObj.originalObject.idclaseitem==1){
    			var productocosto={
		    		idplancuenta: costoventa.idplancuenta,
		    		concepto: costoventa.concepto,
		    		controlhaber: costoventa.controlhaber,
		    		tipocuenta: costoventa.tipocuenta,
		    		Debe: (parseFloat($scope.items[x].productoObj.originalObject.costopromedio)*parseInt($scope.items[x].cantidad)).toFixed(4),
		    		Haber: 0,
		    		Descipcion:''
		    	};
		    	RegistroC.push(productocosto);
    		}	
    	}
    	//--Costo venta producto
    	//--Ingreso del item producto o servicio
    	for(x=0;x<$scope.items.length;x++){
    		if($scope.items[x].productoObj.originalObject.idclaseitem==1 || $scope.items[x].productoObj.originalObject.idclaseitem==2){
    			var itemproductoservicio={
		    		idplancuenta: $scope.items[x].productoObj.originalObject.idplancuenta_ingreso,
		    		concepto: $scope.items[x].productoObj.originalObject.conceptoingreso,
		    		controlhaber: $scope.items[x].productoObj.originalObject.controlhaberingreso,
		    		tipocuenta: $scope.items[x].productoObj.originalObject.tipocuentaingreso,
		    		Debe: 0,
		    		Haber: (parseInt($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU)).toFixed(4),
		    		Descipcion:''
		    	};
		    	RegistroC.push(itemproductoservicio);
    		}	
    	}
    	//--Ingreso del item producto o servicio
    	//--Iva venta
    	var ivaventa={};
    	for(i=0;i<$scope.Configuracion.length;i++){
    		if($scope.Configuracion[i].Descripcion=="SRI_RETEN_IVA_VENTA"){
    			var auxcosto=$scope.Configuracion[i].Contabilidad;
    			ivaventa=auxcosto[0];
    		}
    	}
		var iva={
	    		idplancuenta: ivaventa.idplancuenta,
	    		concepto: ivaventa.concepto,
	    		controlhaber: ivaventa.controlhaber,
	    		tipocuenta: ivaventa.tipocuenta,
	    		Debe: 0,
	    		Haber: parseFloat($scope.ValIVA),
	    		Descipcion:''
	    	};
	    RegistroC.push(iva);
    	//--Iva venta
    	var Contabilidad={
    		transaccion: Transaccion,
    		registro: RegistroC
    	};

    	//--proceso kardex
    	var kardex=[];
		for(x=0;x<$scope.items.length;x++){
    		if($scope.items[x].productoObj.originalObject.idclaseitem==1){
    			var producto={
		    		idtransaccion: 0,
		    		idcatalogitem: $scope.items[x].productoObj.originalObject.idcatalogitem,
		    		idbodega: $scope.Bodega,
		    		fecharegistro:convertDatetoDB($("#FechaEmision").val()),
		    		cantidad:parseInt($scope.items[x].cantidad),
		    		costounitario: parseFloat($scope.items[x].productoObj.originalObject.costopromedio),
		    		costototal:(parseInt($scope.items[x].cantidad)*parseFloat($scope.items[x].productoObj.originalObject.costopromedio)).toFixed(4),
		    		tipoentradasalida:2,
		    		estadoanulado:true,
		    		descripcion:$scope.observacion
		    	};
		    	kardex.push(producto);
    		}	
    	}
    	//--proceso kardex

    	//--Documento de venta
    	var DocVenta={
    		idpuntoventa: $scope.PuntoVentaSeleccionado.idpuntoventa,
    		idcliente: $scope.Cliente.idcliente,
    		idtipoimpuestoiva:$scope.Cliente.idtipoimpuestoiva,
    		numdocumentoventa:'1',
    		fecharegistroventa:convertDatetoDB($("#FechaRegistro").val()),
    		fechaemisionventa:convertDatetoDB($("#FechaEmision").val()),
    		nroautorizacionventa:$scope.NoAutorizacion,
    		nroguiaremision:$("#t_establ_guia").val()+"-"+$("#t_pto_guia").val()+"-"+$("#t_secuencial_guia").val(),
    		subtotalconimpuestoventa:$scope.Subtotalconimpuestos,
    		subtotalceroventa:$scope.Subtotalcero,
    		subtotalnoobjivaventa:$scope.Subtotalnobjetoiva,
    		subtotalexentivaventa:$scope.Subototalexentoiva,
    		subtotalsinimpuestoventa:$scope.Subtotalsinimpuestos,
    		totaldescuento:$scope.Totaldescuento,
    		icecompra:$scope.ValICE,
    		ivacompra:$scope.ValIVA,
    		irbpnrventa:$scope.ValIRBPNR,
    		propina:$scope.ValPropina,
    		otrosventa:0,
    		valortotalventa:$scope.ValorTotal,
    		estadoanulado:'false'
    	};
    	//--Documento de venta
    	//--Items venta
    	var ItemsVenta=[];
    	for(x=0;x<$scope.items.length;x++){
    		var itemsdocventa={
	    		idcatalogitem: $scope.items[x].productoObj.originalObject.idcatalogitem,
	    		iddocumentoventa:0,
	    		idbodega: $scope.Bodega,
	    		idtipoimpuestoiva:$scope.items[x].productoObj.originalObject.idtipoimpuestoiva,
	    		idtipoimpuestoice:$scope.items[x].productoObj.originalObject.idtipoimpuestoice,
	    		cantidad:parseInt($scope.items[x].cantidad),
	    		preciounitario: parseFloat($scope.items[x].precioU),
	    		descuento: $scope.items[x].descuento,
	    		preciototal:(parseInt($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU)).toFixed(4)
	    	};
	    	ItemsVenta.push(itemsdocventa);
    	}
    	//--Items venta
    	console.log(Contabilidad);
    	console.log(kardex);
    	console.log(DocVenta);
    	console.log(ItemsVenta);
    	var transaccion_venta={
    		DataContabilidad:Contabilidad,
    		Datakardex:kardex,
    		DataVenta:DocVenta,
    		Idformapagoventa: $scope.cmbFormapago, 
    		DataItemsVenta:ItemsVenta
    	};
    	$http.get(API_URL+'DocumentoVenta/getVentas/'+JSON.stringify(transaccion_venta))
                .success(function (response) {
                    if(parseInt(response)>0){
                    	QuitarClasesMensaje();
				        $("#titulomsm").addClass("btn-success");
				        $("#msm").modal("show");
				        $scope.Mensaje="La venta se guardo correctamente";
				        $scope.LimiarDataVenta();
				        $scope.NumeroRegistroVenta();
                    }else{
                    	QuitarClasesMensaje();
				        $("#titulomsm").addClass("btn-danger");
				        $("#msm").modal("show");
				        $scope.Mensaje="Error al guardar la venta";
				        $scope.LimiarDataVenta();
                    }
        });
    };
    $scope.LimiarDataVenta=function(){
    	$scope.Subtotalconimpuestos=0;
	    $scope.Subtotalcero=0;
	    $scope.Subtotalnobjetoiva=0;
	    $scope.Subototalexentoiva=0;
	    $scope.Subtotalsinimpuestos=0;
	    $scope.Totaldescuento=0;
	    $scope.ValICE=0;
	    $scope.ValIVA=0;
	    $scope.ValIRBPNR=0;
	    $scope.ValPropina=0;
	    $scope.ValorTotal=0;

	    $scope.DICliente="";
		$scope.Cliente={};
		$scope.items=[];
		$scope.Bodegas=[];
		$scope.cmbFormapago="";
		$scope.PuntoVenta=[];
		$scope.PuntoVentaSeleccionado={};
		$scope.Configuracion=[];
		$scope.mensaje="";
		$scope.AgenteVenta="";
		$scope.t_establ="";
		$scope.t_pto="";
		$scope.Bodega="";
		$scope.observacion="";
		$scope.NoVenta="";
		$scope.t_establ_guia="";
		$scope.t_pto_guia="";
		$scope.t_secuencial_guia="";
		$scope.NoAutorizacion="";
		$scope.t_secuencial="";
    };









    $scope.onlyDecimal = function ($event) {
        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);
        if (n == ".") {
            return true;
        } else {
            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.onlyCharasterAndSpace = function ($event) {

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /^([a-zA-Záéíóúñ\s]+)$/;
        var n = String.fromCharCode(k);

        if(patron.test(n) == false){
            $event.preventDefault();
            return false;
        }
        else return true;

    };

    $scope.onlyNumber = function ($event, length, field) {

        if (length != undefined) {
            var valor = $('#' + field).val();
            if (valor.length == length) $event.preventDefault();
        }

        var k = $event.keyCode;
        if (k == 8 || k == 0) return true;
        var patron = /\d/;
        var n = String.fromCharCode(k);

        if (n == ".") {
            return true;
        } else {

            if(patron.test(n) == false){
                $event.preventDefault();
            }
            else return true;
        }
    };

    $scope.calculateLength = function(field, length) {
        var text = $("#" + field).val();
        var longitud = text.length;
        if (longitud == length) {
            $("#" + field).val(text);
        } else {
            var diferencia = parseInt(length) - parseInt(longitud);
            var relleno = '';
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
    };    
});

function convertDatetoDB(now, revert){
    if (revert == undefined){
        var t = now.split('/');
        return t[2] + '-' + t[1] + '-' + t[0];
    } else {
        var t = now.split('-');
        return t[2] + '/' + t[1] + '/' + t[0];
    }
}

function now(){
    var now = new Date();
    var dd = now.getDate();
    if (dd < 10) dd = '0' + dd;
    var mm = now.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;
    var yyyy = now.getFullYear();
    return dd + "\/" + mm + "\/" + yyyy;
}
function first(){
    var now = new Date();
    var yyyy = now.getFullYear();
    return "01/01/"+ yyyy;
}

function completarNumer(valor){
    if(valor.toString().length>0){
        var i=5;
        var completa="0";
        var aux_com=i-valor.toString().length;
        for(x=0;x<aux_com;x++){
            completa+="0";
        }
    }
    return completa+valor.toString();
}

function QuitarClasesMensaje() {
    $("#titulomsm").removeClass("btn-primary");
    $("#titulomsm").removeClass("btn-warning");
    $("#titulomsm").removeClass("btn-success");
    $("#titulomsm").removeClass("btn-info");
    $("#titulomsm").removeClass("btn-danger");
}
$(document).ready(function(){
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });
});