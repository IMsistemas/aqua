
    app.controller('comprasController',  function($scope, $http, API_URL) {

        $scope.compras = [];
        $scope.items=[];
        $scope.list_item = [];
        $scope.listado = true;

        $scope.proveedor = '';

        $scope.estados = [
            {id: 1, nombre: 'ANULADO'},
            {id: 0, nombre: 'NO ANULADO'}
        ];

        $scope.Subtotalconimpuestos = '0.00';
        $scope.Subtotalcero = '0.00';
        $scope.Subtotalnobjetoiva = '0.00';
        $scope.Subototalexentoiva = '0.00';
        $scope.Subtotalsinimpuestos = '0.00';
        $scope.Totaldescuento = '0.00';
        $scope.ValICE = '0.00';
        $scope.ValIVA = '0.00';
        $scope.ValIRBPNR = '0.00';
        $scope.ValPropina = '0.00';
        $scope.ValorTotal = '0.00';

        $scope.Bodegas=[];
        $scope.Configuracion=[];


        $scope.pageChanged = function(newPage) {
            $scope.initLoad(newPage);
        };

        $scope.getProveedorByFilter = function () {
            $http.get(API_URL + 'DocumentoCompras/getProveedorByFilter').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].persona.razonsocial, id: response[i].idproveedor})
                }
                $scope.proveedor = array_temp;
                $scope.proveedoresFiltro = array_temp[0].id;

            });
        };

        $scope.getBodegas = function () {
            /*$http.get(API_URL + 'DocumentoCompras/getBodegas').success(function(response){

                var longitud = response.length;
                var array_temp = [];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namebodega, id: response[i].idbodega})
                }

                $scope.listbodegas = array_temp;
                $scope.bodega = array_temp[0].id

            });*/

            $http.get(API_URL + 'DocumentoVenta/AllBodegas').success(function(response){
                    $scope.Bodegas=response;
                    console.log(response);
            });
        };

        $scope.getLastIDCompra = function () {

            $http.get(API_URL + 'DocumentoCompras/getLastIDCompra').success(function(response){

                if (response != null && response != 0) {
                    $scope.numcompra = response;
                } else {
                    $scope.numcompra = 1;
                }

            });
        };

        $scope.getSustentoTributario = function () {
            $http.get(API_URL + 'DocumentoCompras/getSustentoTributario').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].namesustento, id: response[i].idsustentotributario})
                }

                $scope.listsustentotributario = array_temp;
                $scope.sustentotributario = array_temp[0].id;

                $scope.listtipocomprobante = [{label: '-- Seleccione --', id: ''}];
                $scope.tipocomprobante = array_temp[0].id;

            });
        };

        $scope.getTipoComprobante = function () {

            var idsustento = $scope.sustentotributario;

            var array_temp = [{label: '-- Seleccione --', id: ''}];
            $scope.listtipocomprobante = array_temp;
            $scope.tipocomprobante = array_temp[0].id;

            if (idsustento != '' && idsustento != undefined) {
                $http.get(API_URL + 'DocumentoCompras/getTipoComprobante/' + idsustento).success(function(response){

                    var longitud = response.length;

                    for (var i = 0; i < longitud; i++){
                        array_temp.push({label: response[i].namecomprobante, id: response[i].idtipocomprobante})
                    }

                    $scope.listtipocomprobante = array_temp;


                });
            }

        };

        $scope.getFormaPago = function () {
            $http.get(API_URL + 'DocumentoCompras/getFormaPago').success(function(response){

                var longitud = response.length;
                var array_temp = [{label: '-- Seleccione --', id: ''}];
                for (var i = 0; i < longitud; i++){
                    array_temp.push({label: response[i].nameformapago, id: response[i].idformapago})
                }

                $scope.listformapago = array_temp;
                $scope.formapago = array_temp[0].id;

            });
        };

        $scope.showDataProveedor = function (object) {

            if (object != undefined && object.originalObject != undefined) {

                console.log(object);

                $scope.razon = object.originalObject.razonsocial;
                $scope.direccion = object.originalObject.direccion;
                $scope.telefono = object.originalObject.proveedor[0].telefonoprincipal;
                $scope.iva = object.originalObject.proveedor[0].sri_tipoimpuestoiva.nametipoimpuestoiva;

                $scope.proveedor = object;

            }

        };

        $scope.createRow = function () {

            /*var object_row = {
                cantidad:0,
                descuento:0,
                precioUnitario:0,
                iva: $scope.ivaPro,
                ice:0,
                total:0,
                productoObj:null,
                testObj:null
            };

            ($scope.list_item).push(object_row);*/

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

        };


        /*
             -------------------------------------------------------------------------------------------       NEW
         */

        $scope.ConfigContable=function(){
            $http.get(API_URL + 'DocumentoCompras/porcentajeivaiceotro')
                .success(function(response){
                    $scope.Configuracion=response;
                    console.log(response);
                    for(x=0;x<$scope.Configuracion.length;x++){
                        if($scope.Configuracion[x].Descripcion=="CONT_COSTO_VENTA"){
                            if($scope.Configuracion[x].IdContable==null){
                                $scope.Valida="1";
                                QuitarClasesMensaje();
                                $("#titulomsm").addClass("btn-danger");
                                $("#msm").modal("show");
                                $scope.Mensaje="La venta necesita la cuenta contable de COSTO DE VENTA";
                            }
                        }
                        if($scope.Configuracion[x].Descripcion=="CONT_IVA_VENTA"){
                            if($scope.Configuracion[x].IdContable==null){
                                $scope.Valida="1";
                                QuitarClasesMensaje();
                                $("#titulomsm").addClass("btn-danger");
                                $("#msm").modal("show");
                                $scope.Mensaje="La venta necesita la cuenta contable de IVA DE VENTA";
                            }
                        }
                    }
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

        $scope.CalculaValores=function(){
            var aux_subtotalconimpuestos=0;
            var aux_totaldescuento=0;
            var aux_totalIce=0;

            for(x=0;x<$scope.items.length;x++){
                console.log($scope.items[x]);
                if(parseInt($scope.items[x].iva)==0 ){
                    if($scope.items[x].cantidad!=undefined && $scope.items[x].precioU!=undefined ){
                        if(parseFloat($scope.items[x].descuento)>0){
                            var aux_descuento=(((parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU))*(parseFloat($scope.items[x].descuento)))/100);
                            aux_totaldescuento+=aux_descuento;
                            var preciouxcantida=(parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU));
                            $scope.items[x].total=(preciouxcantida-aux_descuento).toFixed(4);
                        }else{
                            $scope.items[x].total=(parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU));
                        }
                        if(parseFloat($scope.items[x].ice)>0){
                            var aux_totalaplicaice=((parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU))*((parseFloat($scope.items[x].ice)))/100);
                            aux_totalIce+=aux_totalaplicaice;
                        }
                    }
                }
            }

            for(x=0;x<$scope.items.length;x++){
                console.log($scope.items[x]);
                if(parseInt($scope.items[x].iva)==0 ){
                    if($scope.items[x].cantidad!=undefined && $scope.items[x].precioU!=undefined ){
                        aux_subtotalconimpuestos+=(parseFloat($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU));
                    }
                }
            }

            $scope.Totaldescuento=aux_totaldescuento.toFixed(4);
            $scope.ValICE=aux_totalIce.toFixed(4);

            /*if(parseFloat($scope.ValICE)>0){
                for(x=0;x<$scope.Configuracion.length;x++){
                    if($scope.Configuracion[x].Descripcion=="CONT_ICE_VENTA"){
                        if($scope.Configuracion[x].IdContable==null){
                            $scope.ValidacionCueContExt="1";
                            QuitarClasesMensaje();
                            $("#titulomsm").addClass("btn-danger");
                            $("#msm").modal("show");
                            $scope.Mensaje="La venta necesita la cuenta contable de ICE";
                        }else{
                            $scope.ValidacionCueContExt="0";
                        }
                    }
                }
            }
            if(parseFloat($scope.ValIRBPNR)>0){
                for(x=0;x<$scope.Configuracion.length;x++){
                    if($scope.Configuracion[x].Descripcion=="CONT_IRBPNR_VENTA"){
                        if($scope.Configuracion[x].IdContable==null){
                            $scope.ValidacionCueContExt="1";
                            QuitarClasesMensaje();
                            $("#titulomsm").addClass("btn-danger");
                            $("#msm").modal("show");
                            $scope.Mensaje="La venta necesita la cuenta contable de IRBPNR";
                        }else{
                            $scope.ValidacionCueContExt="0";
                        }
                    }
                }
            }*/

            /*if(parseFloat($scope.ValPropina)>0){
                for(x=0;x<$scope.Configuracion.length;x++){
                    if($scope.Configuracion[x].Descripcion=="CONT_PROPINA_VENTA"){
                        if($scope.Configuracion[x].IdContable==null){
                            $scope.ValidacionCueContExt="1";
                            QuitarClasesMensaje();
                            $("#titulomsm").addClass("btn-danger");
                            $("#msm").modal("show");
                            $scope.Mensaje="La venta necesita la cuenta contable de PROPINA";
                        }else{
                            $scope.ValidacionCueContExt="0";
                        }
                    }
                }
            }*/

            $scope.Subtotalconimpuestos= aux_subtotalconimpuestos.toFixed(4);
            $scope.ValIVA=(($scope.Subtotalconimpuestos*parseInt($scope.proveedor.originalObject.proveedor[0].sri_tipoimpuestoiva.porcentaje))/100).toFixed(4);

            $scope.ValorTotal=((parseFloat($scope.Subtotalconimpuestos)+parseFloat($scope.ValIVA) + parseFloat($scope.ValICE) + parseFloat($scope.ValIRBPNR) + parseFloat($scope.ValPropina) )   - ($scope.Totaldescuento)).toFixed(4);
        };

        $scope.QuitarItem=function (item) {
            var posicion= $scope.items.indexOf(item);
            $scope.items.splice(posicion,1);
            $scope.CalculaValores();
        };

        $scope.save = function(){

            var Transaccion={
                fecha:$("#fechaemisioncompra").val(),
                idtipotransaccion: 7,
                numcomprobante:1,
                descripcion: $scope.observacion
            };

            //Asiento contable Partida doble 	ay123
            var RegistroC=[];

            //Asiento contable cliente -- el cliente por lo genearal es un activo entonces el cliente aumenta una deuda por el debe
            var aux_bodegaseleccionada={};
            for(var i=0;i<$scope.Bodegas.length;i++){
                if(parseInt($scope.Bodegas[i].idbodega)==parseInt($scope.Bodega)){
                    aux_bodegaseleccionada=$scope.Bodegas[i];
                }
            }
            var cliente = {
                idplancuenta: $scope.proveedor.originalObject.proveedor[0].cont_plancuenta.idplancuenta,
                concepto: $scope.proveedor.originalObject.proveedor[0].cont_plancuenta.concepto,
                controlhaber: $scope.proveedor.originalObject.proveedor[0].cont_plancuenta.controlhaber,
                tipocuenta: $scope.proveedor.originalObject.proveedor[0].cont_plancuenta.tipocuenta,
                Debe: 0,
                Haber: $scope.ValorTotal,
                Descipcion:''
            };

            RegistroC.push(cliente);

            //--Sacar producto de bodega -- el producto es un activo pero como se lo vente disminuye por el haber
            for(var x=0;x<$scope.items.length;x++){
                if($scope.items[x].productoObj.originalObject.idclaseitem==1 || $scope.items[x].productoObj.originalObject.idclaseitem==3){
                    var producto={
                        //idplancuenta: $scope.items[x].productoObj.originalObject.idplancuenta,
                        idplancuenta: aux_bodegaseleccionada.idplancuenta,
                        concepto: aux_bodegaseleccionada.concepto,
                        controlhaber: aux_bodegaseleccionada.controlhaber,
                        tipocuenta: aux_bodegaseleccionada.tipocuenta,
                        Debe: (parseFloat($scope.items[x].total)).toFixed(4),
                        Haber: 0,
                        Descipcion:''
                    };
                    RegistroC.push(producto);
                }
            }
            //--Sacar producto de bodega -- el producto es un activo pero como se lo vente disminuye por el haber


            //--Ingreso del item producto o servicio
            for(x=0;x<$scope.items.length;x++){
                if($scope.items[x].productoObj.originalObject.idclaseitem==4){
                    var itemproductoservicio={
                        idplancuenta: $scope.items[x].productoObj.originalObject.idplancuenta_ingreso,
                        concepto: $scope.items[x].productoObj.originalObject.conceptoingreso,
                        controlhaber: $scope.items[x].productoObj.originalObject.controlhaberingreso,
                        tipocuenta: $scope.items[x].productoObj.originalObject.tipocuentaingreso,
                        Debe: (parseFloat($scope.items[x].total)).toFixed(4),
                        Haber: 0,
                        Descipcion:''
                    };
                    RegistroC.push(itemproductoservicio);
                }
            }
            //--Ingreso del item producto o servicio

            //-- ICE venta
            if(parseFloat($scope.ValICE)>0){
                var iceventa={};
                for(i=0;i<$scope.Configuracion.length;i++){
                    if($scope.Configuracion[i].Descripcion=="CONT_ICE_COMPRA"){
                        var auxcosto=$scope.Configuracion[i].Contabilidad;
                        iceventa=auxcosto[0];
                    }
                }
                var ice={
                    idplancuenta: iceventa.idplancuenta,
                    concepto: iceventa.concepto,
                    controlhaber: iceventa.controlhaber,
                    tipocuenta: iceventa.tipocuenta,
                    Debe: parseFloat($scope.ValICE),
                    Haber: 0,
                    Descipcion:''
                };
                RegistroC.push(ice);
            }
            //-- ICE venta

            //-- IRBPNR venta
            if(parseFloat($scope.ValIRBPNR)>0){
                var irbpnrventa={};
                for(i=0;i<$scope.Configuracion.length;i++){
                    if($scope.Configuracion[i].Descripcion=="CONT_IRBPNR_COMPRA"){
                        var auxcosto=$scope.Configuracion[i].Contabilidad;
                        irbpnrventa=auxcosto[0];
                    }
                }
                var irbpnr={
                    idplancuenta: irbpnrventa.idplancuenta,
                    concepto: irbpnrventa.concepto,
                    controlhaber: irbpnrventa.controlhaber,
                    tipocuenta: irbpnrventa.tipocuenta,
                    Debe: parseFloat($scope.ValIRBPNR),
                    Haber: 0,
                    Descipcion:''
                };
                RegistroC.push(irbpnr);
            }
            //-- IRBPNR venta

            //-- PROPINTA venta
            if(parseFloat($scope.ValPropina)>0){
                var propinaventa={};
                for(i=0;i<$scope.Configuracion.length;i++){
                    if($scope.Configuracion[i].Descripcion=="CONT_PROPINA_COMPRA"){
                        var auxcosto=$scope.Configuracion[i].Contabilidad;
                        propinaventa=auxcosto[0];
                    }
                }
                var propinav={
                    idplancuenta: propinaventa.idplancuenta,
                    concepto: propinaventa.concepto,
                    controlhaber: propinaventa.controlhaber,
                    tipocuenta: propinaventa.tipocuenta,
                    Debe: parseFloat($scope.ValPropina),
                    Haber: 0,
                    Descipcion:''
                };
                RegistroC.push(propinav);
            }
            //-- PROPINTA venta


            //--Iva venta
            var ivaventa={};
            for(i=0;i<$scope.Configuracion.length;i++){
                if($scope.Configuracion[i].Descripcion=="CONT_IVA_COMPRA"){
                    var auxcosto=$scope.Configuracion[i].Contabilidad;
                    ivaventa=auxcosto[0];
                }
            }
            var iva={
                idplancuenta: ivaventa.idplancuenta,
                concepto: ivaventa.concepto,
                controlhaber: ivaventa.controlhaber,
                tipocuenta: ivaventa.tipocuenta,
                Debe: parseFloat($scope.ValIVA),
                Haber: 0,
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
                if($scope.items[x].productoObj.originalObject.idclaseitem == 1){
                    var producto={
                        idtransaccion: 0,
                        idcatalogitem: $scope.items[x].productoObj.originalObject.idcatalogitem,
                        idbodega: $scope.Bodega,
                        fecharegistro:$("#fechaemisioncompra").val(),
                        cantidad:parseInt($scope.items[x].cantidad),
                        costounitario: parseFloat($scope.items[x].precioU),
                        //costototal:(parseInt($scope.items[x].cantidad)*parseFloat($scope.items[x].productoObj.originalObject.costopromedio)).toFixed(4),
                        costototal:(parseInt($scope.items[x].cantidad)*parseFloat($scope.items[x].precioU)).toFixed(4),
                        tipoentradasalida:1,
                        estadoanulado:true,
                        descripcion:$scope.observacion
                    };
                    kardex.push(producto);
                }
            }
            //--proceso kardex

            //--Documento de venta
            var DocVenta={
                //idpuntoventa: $scope.PuntoVentaSeleccionado.idpuntoventa,
                idproveedor: $scope.proveedor.originalObject.proveedor[0].idproveedor,
                idtipoimpuestoiva:$scope.proveedor.originalObject.proveedor[0].idtipoimpuestoiva,

                numdocumentocompra: $('#t_establ').val() + '-' + $('#t_pto').val() + '-' + $('#t_secuencial').val(),

                fecharegistrocompra:$("#fecharegistrocompra").val(),
                fechaemisioncompra:$("#fechaemisioncompra").val(),

                nroautorizacioncompra:$scope.nroautorizacioncompra,
                //nroguiaremision:$("#t_establ_guia").val()+"-"+$("#t_pto_guia").val()+"-"+$("#t_secuencial_guia").val(),

                idsustentotributario: $scope.sustentotributario,
                idtipocomprobante: $scope.tipocomprobante,

                //idcomprobanteretencion: null,

                subtotalconimpuestocompra:$scope.Subtotalconimpuestos,
                subtotalcerocompra:$scope.Subtotalcero,
                subtotalnoobjivacompra:$scope.Subtotalnobjetoiva,
                subtotalexentivacompra:$scope.Subototalexentoiva,
                subtotalsinimpuestocompra:$scope.Subtotalsinimpuestos,
                totaldescuento:$scope.Totaldescuento,
                icecompra:$scope.ValICE,
                ivacompra:$scope.ValIVA,
                irbpnrcompra:$scope.ValIRBPNR,
                propinacompra:$scope.ValPropina,
                otroscompra:0,
                valortotalcompra:$scope.ValorTotal,
                estadoanulado:'false',
                idtransaccion:''
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
            //console.log(Contabilidad);
            /*console.log(kardex);
             console.log(DocVenta);
             console.log(ItemsVenta);*/
            var transaccion_venta_full={
                DataContabilidad:Contabilidad,
                Datakardex:kardex,
                DataCompra:DocVenta,
                Idformapagocompra: $scope.formapago,
                DataItemsCompra:ItemsVenta
            };

            console.log(transaccion_venta_full);

            //$http.get(API_URL+'DocumentoVenta/getVentas/'+JSON.stringify(transaccion_venta_full))
            //$http.get(API_URL+'DocumentoVenta/getVentas/'+JSON.stringify(2))

            var transaccionfactura={
                datos:JSON.stringify(transaccion_venta_full)
                //datos: transaccion_venta_full
            };

            $http.post(API_URL+'DocumentoCompras',transaccionfactura).success(function (response) {

                    console.log(response);

                    /*if(parseInt(response)>0){
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
                    }*/

                if (response.success == true) {

                    $('#modalConfirmSave').modal('hide');
                    $scope.message = 'Se insertó correctamente la Compra...';
                    $('#modalMessage1').modal('show');

                }
                else {
                    $('#modalConfirmSave').modal('hide');
                    $scope.message_error = 'Ha ocurrido un error al intentar guardar la Compra...';
                    $('#modalMessageError').modal('show');
                }

            })
            .error(function(err){
                console.log(err);
            });
        };

        $scope.confirmSave = function() {
            $('#modalConfirmSave').modal('show');
        }

        /*
            ------------------------------------------------------------------------------------------        NEW
         */

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

        $scope.initLoad = function () {
            $scope.ConfigContable();
            $scope.getProveedorByFilter();
            $scope.searchByFilter();
        };

        $scope.sumar = function(v1,v2){
            return $scope.roundToTwo(parseFloat(v1) + parseFloat(v2)).toFixed(2);
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

        $scope.roundToTwo = function (num) {
            return +(Math.round(num + "e+2")  + "e-2");
        }

        $scope.InicioList=function() {
            $scope.listado =  true;
            $scope.initLoad();
        };

        $scope.initLoad();

        $scope.newRow = function(){
            $scope.read =  false;
            return {
                cantidad:0,
                descuento:0,
                precioUnitario:0,
                iva: $scope.ivaPro,
                ice:0,
                total:0,
                productoObj:null,
                testObj:null
            }
        };

        $scope.activeForm = function (action) {

            if (action == 0) {

                $scope.listado = false;

                $scope.t_establ = '000';
                $scope.t_pto = '000';
                $scope.t_secuencial = '000000000';

                $scope.getLastIDCompra();
                $scope.getBodegas();
                $scope.getSustentoTributario();
                $scope.getFormaPago();

                $scope.Subtotalconimpuestos = '0.00';
                $scope.Subtotalcero = '0.00';
                $scope.Subtotalnobjetoiva = '0.00';
                $scope.Subototalexentoiva = '0.00';
                $scope.Subtotalsinimpuestos = '0.00';
                $scope.Totaldescuento = '0.00';
                $scope.ValICE = '0.00';
                $scope.ValIVA = '0.00';
                $scope.ValIRBPNR = '0.00';
                $scope.ValPropina = '0.00';
                $scope.ValorTotal = '0.00';

                $scope.fecharegistrocompra = $scope.fecha();
                $scope.fechaemisioncompra = $scope.fecha();

                $scope.createRow();

            } else {

                $scope.listado = true;

            }

        };

        $('.datepicker').datetimepicker({
            locale: 'es',
            format: 'YYYY-MM-DD',
            ignoreReadonly: true
        });

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

        $scope.fecha = function(){

            var f = new Date();

            var dd = f.getDate();
            if (dd < 10) dd = '0' + dd;
            var mm = f.getMonth() + 1;
            if (mm < 10) mm = '0' + mm;
            var yyyy = f.getFullYear();
            //var fecha_actual = dd + "\/" + mm + "\/" + yyyy;

            return yyyy + '-' + mm + '-' + dd;

        };
    });