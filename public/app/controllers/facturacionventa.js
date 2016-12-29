app.controller('facturacioventa', function($scope, $http, API_URL) {

    $scope.CodigoDocumentoVenta="";

    $scope.ActivaVenta="0";
    $scope.Mensaje="";
    $scope.FechaRegistro=now();
    $scope.CLiente=[];
    $scope.Formapago=[];
    $scope.DetalleVenta=[];

    $scope.Comentario="";

    $scope.Establecimiento="";
    $scope.PuntoDeVenta="";
    $scope.Numero="";
    $scope.Autorizacion="";

    $scope.Vendor="";
    $scope.IDVendor="";
    $scope.CiVenedor="";
    $scope.NombreVendor="";

    $scope.PorcentajeIvaIceOtroConfig=0;


    $scope.PorcentajeDescuento=0;
    $scope.SubtotalIva=0.0;
    $scope.SubtotalCero=0.0; 
    $scope.Descuento=0.0; 
    $scope.Otros=0.0; 
    $scope.Iva=0.0; 
    $scope.Total=0.0;
    //Porcentaje Iva Ice Otro
    $scope.ConfigContable=function() {
        $http.get(API_URL + 'DocumentoVenta/porcentajeivaiceotro')
        .success(function(response){
            $scope.PorcentajeIvaIceOtroConfig=response[0].iva;
        });
    };
    //Datos Cabezera Factura
    $scope.HeadInfoFacturaVenta=function() {
        $http.get(API_URL + 'DocumentoVenta/getheaddocumentoventa')
        .success(function(response){
            $scope.Establecimiento=response[0].idestablecimiento;
            $scope.PuntoDeVenta=response[0].idpuntoventa;
            $scope.CiVenedor=response[0].empleado.documentoidentidadempleado;
            $scope.IDVendor=response[0].empleado.idempleado;
            $scope.NombreVendor=response[0].empleado.apellidos+" "+response[0].empleado.nombres;
            $scope.Vendor=$scope.CiVenedor+" - "+$scope.NombreVendor;
        });
    };
    //forma de pago
    $scope.FormaPagoVenta=function() {
        $http.get(API_URL + 'DocumentoVenta/formapago')
        .success(function(response){
            $scope.Formapago=response;
            $scope.pago="0";
        });
    };
    //Cliente
    $scope.BuscarCliente=function(){
        $http.get(API_URL + 'DocumentoVenta/getInfoClienteXCIRuc/'+$scope.RUCCI)
        .success(function(response){
            $scope.CLiente=response;
        });
    };

    //Agregar Item
    $scope.AddFilaDetalleVenta=function () {
            $scope.Aux_Intem={
                TipoItem :'P',
                IdBodega:'',
                Bodega :'',
                CodProducto:'',
                Detalle :'',
                Descripcion:'',
                Cantidad:1,
                PVPUnitario:'',
                IVA: $scope.PorcentajeIvaIceOtroConfig,
                Total:''
            };
            $scope.DetalleVenta.push($scope.Aux_Intem);
    };
    //Quitar Item
    $scope.QuitarItem=function (item) {
        var posicion= $scope.DetalleVenta.indexOf(item);
         $scope.DetalleVenta.splice(posicion,1);
    };
    //Calcular Totales
    $scope.CalculaTotalesVenta=function () {
        var aux_subtotalIva=0;
        var aux_subtotalCero=0;
        var aux_subtotalOtros=0;
        for(x=0;x<$scope.DetalleVenta.length;x++ ){
            if($scope.DetalleVenta[x].IVA==14 & $scope.DetalleVenta[x].IVA!=0) {
                aux_subtotalIva+=(($scope.DetalleVenta[x].Cantidad)*($scope.DetalleVenta[x].PVPUnitario))
            }
            if($scope.DetalleVenta[x].IVA==0 & $scope.DetalleVenta[x].IVA!=14){
                aux_subtotalCero+=(($scope.DetalleVenta[x].Cantidad)*($scope.DetalleVenta[x].PVPUnitario))
            }
            if($scope.DetalleVenta[x].IVA!=0 & $scope.DetalleVenta[x].IVA!=14){
                aux_subtotalOtros+=(($scope.DetalleVenta[x].Cantidad)*($scope.DetalleVenta[x].PVPUnitario))
            }
        }
        $scope.SubtotalIva=aux_subtotalIva;
        $scope.SubtotalCero=aux_subtotalCero;
        $scope.Otros=aux_subtotalOtros;

        $scope.Descuento=(($scope.SubtotalIva + $scope.SubtotalCero + $scope.Otros) * (($scope.PorcentajeDescuento)/100) );
        $scope.Iva=  (($scope.SubtotalIva)*($scope.PorcentajeIvaIceOtroConfig/100));
        $scope.Total=(($scope.SubtotalIva + $scope.SubtotalCero + $scope.Otros) - ($scope.Descuento) + ($scope.Iva));
    };
    
    /////////////--------------------------
    $scope.Aux_AddProductoServicio="";
    $scope.Bodegas=[];
    $scope.ProductoPorBodega=[];
    $scope.Aux_ProductoSelect=[];
    $scope.AllServiciosV=[];
    $scope.Aux_ServicioSelect=[];
    $scope.SelectProductoServicio=function () {
        $scope.Aux_AddProductoServicio="";
        $('#MBuscarProductoServicio').modal('show');
        $scope.LoadModega();
        $scope.ProductoPorBodega=[];
        $scope.Aux_ProductoSelect=[];
        $scope.AllServiciosV=[];
        $scope.Aux_ServicioSelect=[];
    };
    $scope.LoadModega=function() {
        $http.get(API_URL + 'DocumentoVenta/AllBodegas')
        .success(function(response){
            $scope.Bodegas=response;
            $scope.Aux_AddBodega="";
        });
    };
    $scope.SearchProducto=function(){
        if($scope.Aux_AddBodega!=""){
            $http.get(API_URL + 'DocumentoVenta/LoadProductos/'+$scope.Aux_AddBodega)
            .success(function(response){
                $scope.ProductoPorBodega=response;
            });
        }else{
            $scope.ProductoPorBodega=[];
        }
    };
    $scope.AgregarIntemTem=function(itemprod){
        var positem= $scope.Aux_ProductoSelect.indexOf(itemprod);
        if(positem<0){//agrego a la lista 
            $scope.Aux_ProductoSelect.push(itemprod);
        }else{//lo quito de la lista
            $scope.Aux_ProductoSelect.splice(positem,1);
        }
    };
    $scope.ConfirItemProduc=function () {
        $('#MBuscarProductoServicio').modal('hide');
        if($scope.Aux_AddProductoServicio=="P"){
            for(x=0;x<$scope.Aux_ProductoSelect.length;x++){
                $scope.Aux_Intem={
                    TipoItem : $scope.Aux_AddProductoServicio,
                    IdBodega: $scope.Aux_ProductoSelect[x].idbodega,
                    Bodega : $scope.Aux_ProductoSelect[x].idbodega,
                    CodProducto: $scope.Aux_ProductoSelect[x].codigoproducto,
                    Detalle : $scope.Aux_ProductoSelect[x].nombreproducto,
                    Descripcion: $scope.Aux_ProductoSelect[x].nombreproducto,
                    //Cantidad: $scope.Aux_ProductoSelect[x].cantidadproductobodega,
                    Cantidad: 1,
                    PVPUnitario:'',
                    IVA: $scope.PorcentajeIvaIceOtroConfig,
                    Total:''
                };
                $scope.DetalleVenta.push($scope.Aux_Intem);
            }
        }
        if($scope.Aux_AddProductoServicio=="S"){
            for(x=0;x<$scope.Aux_ServicioSelect.length;x++){
                $scope.Aux_Intem={
                    TipoItem : $scope.Aux_AddProductoServicio,
                    IdBodega: '',
                    Bodega : '',
                    CodProducto: $scope.Aux_ServicioSelect[x].idservicio,
                    Detalle : $scope.Aux_ServicioSelect[x].nombreservicio,
                    Descripcion: $scope.Aux_ServicioSelect[x].nombreservicio,
                    //Cantidad: $scope.Aux_ServicioSelect[x].cantidadproductobodega,
                    Cantidad: 1,
                    PVPUnitario:'',
                    IVA: $scope.PorcentajeIvaIceOtroConfig,
                    Total:''
                };
                $scope.DetalleVenta.push($scope.Aux_Intem);
            }
        }
    };
    //------servicios
    $scope.LoadServicios=function() {
        if($scope.Aux_AddProductoServicio=="S"){
            $http.get(API_URL + 'DocumentoVenta/AllServicios')
            .success(function(response){
                $scope.AllServiciosV=response;
            });
        }
    };
    $scope.AgregarIntemTemSer=function (itemser) {
        var positem= $scope.Aux_ServicioSelect.indexOf(itemser);
        if(positem<0){
            $scope.Aux_ServicioSelect.push(itemser);
        }else{
            $scope.Aux_ServicioSelect.splice(positem,1);
        }
    };
    $scope.productosenventa=[];
    $scope.serviciosenventa=[];
    $scope.documentoventa={};
    $scope.Venta={};
    $scope.SaveVenta=function() {
        $("#titulomsm").addClass("btn-primary");
        if($scope.pago!="0"){
            if($scope.CLiente.length!=0) {
                $scope.sendDataToSave();
            }else{
                $scope.Mensaje="Seleccione un cliente";
                $("#Msm").modal("show");
            }
        }else{
            $scope.Mensaje="Seleccione un metodo de pago";
            $("#Msm").modal("show");
        }
    };
    $scope.sendDataToSave=function() {
        ///detalle de la venta...!!
        $scope.productosenventa=[];
        $scope.serviciosenventa=[];
        $scope.documentoventa={};
        $scope.Venta={};

        for(x=0;x<$scope.DetalleVenta.length;x++){
            if($scope.DetalleVenta[x].TipoItem=="P"){
                var aux_productoventa={
                    codigoproducto: $scope.DetalleVenta[x].CodProducto,
                    idbodega: $scope.DetalleVenta[x].IdBodega,
                    cantidad : $scope.DetalleVenta[x].Cantidad,
                    precio : $scope.DetalleVenta[x].PVPUnitario,
                    preciototal: (($scope.DetalleVenta[x].Cantidad) * ($scope.DetalleVenta[x].PVPUnitario)),
                    porcentajeiva: $scope.DetalleVenta[x].IVA
                };
                $scope.productosenventa.push(aux_productoventa);
            }
            if($scope.DetalleVenta[x].TipoItem=="S"){
                var aux_servicioventa={
                    idservicio : $scope.DetalleVenta[x].CodProducto 
                };
                $scope.serviciosenventa.push(aux_servicioventa);
            }
        }
        $scope.documentoventa={
            codigoformapago: $scope.pago,
            codigocliente: $scope.CLiente[0].codigocliente,
            idpuntoventa: $scope.PuntoDeVenta,
            idempleado: $scope.IDVendor,
            idfactura: 1,
            numerodocumento: '12345678910',
            fecharegistrocompra: convertDatetoDB($scope.FechaRegistro),
            autorizacionfacturar: '9876543211235474144',
            subtotalivaventa: $scope.SubtotalIva,
            descuentoventa: $scope.Descuento,
            ivaventa: $scope.Iva,
            totalventa: $scope.Total,
            otrosvalores: $scope.Otros,
            procentajedescuentocompra: $scope.PorcentajeDescuento,
            estapagada:'F',
            estaanulada :'F',
            fechapago: convertDatetoDB($scope.FechaRegistro),
            comentario: $scope.Comentario,
            impreso : 'F'
        };
        $scope.Venta={
            documentoventa : $scope.documentoventa,
            productosenventa : $scope.productosenventa,
            serviciosenventa : $scope.serviciosenventa
        };
        
        $http.post(API_URL+'DocumentoVenta',$scope.Venta)
        .success(function (response) {
            $("#titulomsm").addClass("btn-success");
            $scope.Mensaje="Se guardo correctamente";
            $("#Msm").modal("show");
        });
    };

    /////////Filtro
    $scope.auxf_PuntoVenta=[];
    $scope.auxf_establecimiento=[];
    $scope.Registroventas=[];
    $scope.F_RucCliente="";
    $scope.F_PuntoVeta="";
    $scope.F_Establecimiento="";
    $scope.F_Estado="";
    $scope.F_Anulada="";
    //$scope.auxf_anulada=[];
    $scope.LoadDataToFiltro=function(){
        $http.get(API_URL + 'DocumentoVenta/getAllFitros')
        .success(function(response){
            $scope.auxf_PuntoVenta=response.puntoventa;
            $scope.auxf_establecimiento=response.establecimiento;
            
            $scope.F_PuntoVeta="";
            $scope.F_Establecimiento="";
        });
    };


    $scope.FiltrarVenta=function () {
        var aux_PuntoVeta="";
        var aux_Establecimiento="";
        var aux_Estado="";
        var aux_Anulada="";
        if($scope.F_PuntoVeta!=""){
            aux_PuntoVeta=$scope.F_PuntoVeta;
        }
        if($scope.F_Establecimiento!=""){
            aux_Establecimiento=$scope.F_Establecimiento;
        }
        if($scope.F_Estado!=""){
            aux_Estado=$scope.F_Estado;
        }
        if($scope.F_Anulada!=""){
            aux_Anulada=$scope.F_Anulada;
        }
        var Filtro={
            RucOcLiente : $scope.F_RucCliente,
            PuntoVenta: aux_PuntoVeta,
            Establecimiento : aux_Establecimiento,
            Estado : aux_Estado,
            Anulada : aux_Anulada
        };
        $http.get(API_URL + 'DocumentoVenta/getVentas/' + JSON.stringify(Filtro))
        .success(function(response){
            $scope.Registroventas = response;            
        });
    };
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   
        $scope.reverse = !$scope.reverse; 
    };
    ///Iniciar de nuevo
    $scope.InicioList=function() {
        $scope.HeadInfoFacturaVenta();
        $scope.FormaPagoVenta();
        $scope.ConfigContable();
        $scope.FiltrarVenta();
        $scope.LoadDataToFiltro();

        $scope.ActivaVenta="0";
        $scope.Mensaje="";
        $scope.FechaRegistro=now();
        $scope.CLiente=[];
        $scope.Formapago=[];
        $scope.DetalleVenta=[];

        $scope.Comentario="";

        $scope.Establecimiento="";
        $scope.PuntoDeVenta="";
        $scope.Numero="";
        $scope.Autorizacion="";

        $scope.Vendor="";
        $scope.IDVendor="";
        $scope.CiVenedor="";
        $scope.NombreVendor="";

        $scope.RUCCI="";

        $scope.PorcentajeIvaIceOtroConfig=0;


        $scope.PorcentajeDescuento=0;
        $scope.SubtotalIva=0.0;
        $scope.SubtotalCero=0.0; 
        $scope.Descuento=0.0; 
        $scope.Otros=0.0; 
        $scope.Iva=0.0; 
        $scope.Total=0.0;
    };
    //Anular Venta
    $scope.aux_ventatoAnular={};
    $scope.AnularVenta=function(item) {
        $("#AnularVenta").modal("show");
        $scope.aux_ventatoAnular={};
        $scope.aux_ventatoAnular=item;
    };
    $scope.ConfirAnulacion=function() {
        $("#AnularVenta").modal("hide");
        $http.get(API_URL + 'DocumentoVenta/anularVenta/'+$scope.aux_ventatoAnular.codigoventa)
        .success(function(response){
            $("#titulomsm").addClass("btn-success");
            $scope.Mensaje="Se guardo correctamente";
            $("#Msm").modal("show");
        });
    };
    //Editar
    $scope.EditDocVenta=function(item) {
      $http.get(API_URL + 'DocumentoVenta/loadEditVenta/'+item.codigoventa)
        .success(function(response){
            console.log(response);
            $scope.ActivaVenta="1";
            var aux_pventa=response.puntoventa;
            var aux_cliente=response.cliente;
            var aux_venta=response.venta;
            $scope.Establecimiento=aux_pventa[0].idestablecimiento;
            $scope.PuntoDeVenta=aux_pventa[0].idpuntoventa;
            $scope.CiVenedor=aux_pventa[0].empleado.documentoidentidadempleado;
            $scope.IDVendor=aux_pventa[0].empleado.idempleado;
            $scope.NombreVendor=aux_pventa[0].empleado.apellidos+" "+aux_pventa[0].empleado.nombres;
            $scope.Vendor=$scope.CiVenedor+" - "+$scope.NombreVendor;

            $scope.CLiente=aux_cliente;
            $scope.RUCCI=aux_cliente[0].documentoidentidad;
            $scope.ReloadVenta(aux_venta);
        });  
    };
    $scope.ReloadVenta=function(data) {
        $scope.FechaRegistro=convertDatetoDB(data[0].fecharegistrocompra,"");
        $scope.Autorizacion=data[0].autorizacionfacturar;
        $scope.Numero=data[0].numerodocumento;
        $scope.pago=data[0].codigoformapago;

        $scope.PorcentajeDescuento= parseFloat(data[0].procentajedescuentocompra);
        $scope.SubtotalIva= parseFloat(data[0].subtotalivaventa);
        $scope.SubtotalCero=parseFloat(data[0].subtotalnoivaventa); 
        $scope.Descuento=parseFloat(data[0].descuentoventa); 
        $scope.Otros=parseFloat(data[0].otrosvalores); 
        $scope.Iva=parseFloat(data[0].ivaventa) 
        $scope.Total=parseFloat(data[0].totalventa);
        $scope.Comentario=data[0].comentario;

        var aux_intem=data[0].productosenventa;
        for(x=0;x<aux_intem.length;x++){
            $scope.Aux_Intem={
                    TipoItem : "P",
                    IdBodega: aux_intem[x].idbodega ,
                    Bodega :  aux_intem[x].idbodega,
                    CodProducto: aux_intem[x].codigoproducto,
                    Detalle : aux_intem[x].codigoproducto,
                    Descripcion: "",
                    Cantidad: parseInt(aux_intem[x].cantidad),
                    PVPUnitario: parseFloat(aux_intem[x].precio),
                    IVA: parseInt(aux_intem[x].porcentajeiva),
                    Total:''
                };
                $scope.DetalleVenta.push($scope.Aux_Intem);
        }

        $scope.CalculaTotalesVenta();
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

$(document).ready(function(){
    $('.datepicker').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        ignoreReadonly: true
    });
})