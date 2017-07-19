
app.controller('mainController',function($scope, $http, API_URL) {

    $scope.titulo = "Inicio";
    $scope.toModulo = "";

    $scope.username = 'Secretaría';

    $scope.list_breadcrumb = [];

    $scope.logoutSystem = function () {

        $http.get(API_URL + '/logout' ).success(function (response) {

            location.reload(true);

        }).error(function (res) {

        });

    };

    $scope.getPermisosRol = function () {
        $http.get(API_URL + 'rol/getPermisosRol').success(function(response){

            var longitud = response.length;

            for (var i = 0; i < longitud; i++) {

                if (response[i].permiso_rol.length > 0) {

                    if (response[i].permiso_rol[0].idrol !== 1) {

                        if (response[i].permiso_rol[0].state === true) {

                            $('#permiso_' + response[i].idpermiso).show();

                        } else {

                            $('#permiso_' + response[i].idpermiso).hide();

                        }

                    }

                }

            }

        });
    };


    $scope.toLogout = function () {
        $('#modalConfirmLogout').modal('show');
    };

    $scope.toModuloRol = function(){
        $scope.titulo = "Rol";
        $scope.toModulo = "rol";
    };

    $scope.toModuloUsuario = function(){
        $scope.titulo = "";
        $scope.toModulo = "usuario";
    };

    $scope.toModuloEmpleado = function(){
        $scope.titulo = "Colaboradores";
        $scope.toModulo = "empleado";

        var list = [
            '<li>Personal</li>',
            '<li>Colaboradores</li>'
        ];

        $scope.prepareListBreadcrumb(list);
    }

    $scope.toModuloCliente = function(){
        $scope.titulo = "Clientes";
        $scope.toModulo = "cliente";

        var list = [
            '<li>Clientes</li>',
            '<li>Clientes</li>'
        ];

        $scope.prepareListBreadcrumb(list);
    }

    $scope.toModuloProvincia = function(){
        $scope.titulo = "Provincias";
        $scope.toModulo = "provincias";
    }

    $scope.toModuloCanton = function(idprovincia){
        $scope.idprovincia = idprovincia;
        $scope.titulo = "Cantones";
        $scope.toModulo = "cantones";
    }

    $scope.toModuloParroquia = function(idcanton){
        $scope.idcanton = idcanton;
        $scope.titulo = "Parroquias";
        $scope.toModulo = "parroquias";
    }

    $scope.toModuloBarrio = function(idparroquia){
        $scope.idparroquia = idparroquia;
        $scope.titulo = "Zonas";
        $scope.toModulo = "barrio";

    }

    $scope.toModuloCalle = function(){
        $scope.titulo = "Tranversales";
        $scope.toModulo = "calle";
    }

    $scope.toModuloConfiguracion = function(){
        $scope.titulo = "Configuración";
        $scope.toModulo = "configuracion";
    }

    $scope.toModuloCargo = function(){
        $scope.titulo = "Cargos";
        $scope.toModulo = "cargo";

        var list = [
            '<li>Personal</li>',
            '<li>Cargos</li>'
        ];

        $scope.prepareListBreadcrumb(list);
    }

    $scope.toModuloDepartamento = function(){
        $scope.titulo = "Departamento";
        $scope.toModulo = "departamento";
    }

    $scope.toModuloNewLectura = function(){
        window.open(API_URL + '/nuevaLectura');
    }

    $scope.toModuloLectura = function(){
        $scope.titulo = "Lecturas";
        $scope.toModulo = "verLectura";
    }

    $scope.toModuloRecaudacion = function(){
        $scope.titulo = "Recaudación";
        $scope.toModulo = "factura";
    }

    $scope.toModuloRecaudacionServicio = function(){
        $scope.titulo = "Recaudación Servicio";
        $scope.toModulo = "cobroservicio";
    }

    $scope.toModuloSolicitud = function(){
        $scope.titulo = "Solicitudes";
        $scope.toModulo = "solicitud";
    }

    $scope.toModuloSolicitudEspera = function(){
        $scope.titulo = "Solicitudes";
        $scope.toModulo = "suministros/espera";
    }

    $scope.toModuloSuministro = function(){
        $scope.titulo = "suministros";
        $scope.toModulo = "suministros";
    }

    $scope.toModuloCXP = function(){
        $scope.titulo = "Cuentas por pagar al cliente";
        $scope.toModulo = "cuentaspagarcliente";
    }

    $scope.toModuloCXC = function(){
        $scope.titulo = "Cuentas por cobrar al cliente";
        $scope.toModulo = "cuentascobrarcliente";
    }

    $scope.toModuloProveedores = function(){
        $scope.titulo = "Proveedores";
        $scope.toModulo = "proveedor";
    }

    $scope.toModuloCompras = function(){
        $scope.titulo = "Compras Inventario";
        $scope.toModulo = "DocumentoCompras";
    }

    $scope.toModuloVentas = function(){
        $scope.titulo = "Ventas: Registro Ventas";
        $scope.toModulo = "DocumentoVenta";
    }

    $scope.toModuloNC = function(){
        $scope.titulo = "";
        $scope.toModulo = "DocumentoNC";
    }

    $scope.toModuloGuiaRemision = function(){
        $scope.titulo = "Ventas";
        $scope.toModulo = "guiaremision";
    }

    $scope.toModuloComprobantesVentas = function(){
        $scope.titulo = "Comprobantes Ventas";
        $scope.toModulo = "cuentascobrarcliente";
    }

    $scope.toModuloComprobantesCompras = function(){
        $scope.titulo = "Comprobantes Compras";
        $scope.toModulo = "comprobEgreso";
    }

    $scope.toModuloRetencionesVentas = function(){
        $scope.titulo = "Retenciones Ventas";
        $scope.toModulo = "retencionCompra";
    }

    $scope.toModuloRetencionesCompras = function(){
        $scope.titulo = "Retenciones Compras";
        $scope.toModulo = "retencionCompras";
    }

    $scope.toModuloRetencionesVentas = function(){
        $scope.titulo = "Retenciones Ventas";
        $scope.toModulo = "retencionVenta";
    }

    $scope.toModuloReporteCompras = function(){
        $scope.titulo = "Reporte Compras";
        $scope.toModulo = "reportecompra";
    }

    $scope.toModuloReporteVentas = function(){
        $scope.titulo = "Reporte Ventas";
        $scope.toModulo = "reporteventa";
    }

    $scope.toModuloReporteNC = function(){
        $scope.titulo = "Reporte Nota Credito";
        $scope.toModulo = "reportenc";
    }

    $scope.toModuloReporteVentasBalance = function(){
        $scope.titulo = "Reporte Ventas Balance";
        $scope.toModulo = "reporteventabalance";
    }

    $scope.toModuloPortafolioProductos = function(){
        $scope.titulo = "Portafolio de Productos";
        $scope.toModulo = "categoria";
    }

    $scope.toModuloCatalogoProductos = function(){
        $scope.titulo = "Catálogo de Productos";
        $scope.toModulo = "catalogoproducto";
    }

    $scope.toModuloCrearBodegas = function(){
        $scope.titulo = "Crear Bodegas";
        $scope.toModulo = "bodega";
    }

    $scope.toModuloPlanCuentas = function(){
        $scope.titulo = "Plan de Cuentas";
        $scope.toModulo = "Contabilidad";
    }

    $scope.toModuloBalance = function(){
        $scope.titulo = "Estados Financieros";
        $scope.toModulo = "Balance";
    }

    $scope.toModuloInventario = function(){
        $scope.titulo = "Inventario";
        $scope.toModulo = "Inventario";
    }

    $scope.toModuloTransportistas = function(){
        $scope.titulo = "Crear Transportistas";
        $scope.toModulo = "transportista";
    }

    $scope.toModuloNomenclador = function(){
        $scope.titulo = "Crear Transportistas";
        $scope.toModulo = "Nomenclador";
    }

    $scope.toModuloPuntoVenta = function(){
        
        $scope.toModulo = "puntoventa";
    }

    $scope.toModuloCuentasxCobrar = function(){
        $scope.titulo = "Crear Transportistas";
        $scope.toModulo = "cuentasxcobrar";
    }

    $scope.toModuloCuentasxPagar = function(){
        $scope.titulo = "Crear Transportistas";
        $scope.toModulo = "cuentasxpagar";
    }

    $scope.toModuloActivosFijos = function(){
        
        $scope.toModulo = "Activosfijos/depreciacionActivosFijos";
    }

    $scope.toModuloRolPago = function(){
        $scope.titulo = "Rol de Pago";
        $scope.toModulo = "rolPago";
    }

    $scope.prepareListBreadcrumb = function (list_module) {
        $scope.list_breadcrumb = [
            "<li><img src='img/ico-aqua.png'></li>",
            "<li>Inicio</li>"
        ];

        var breadcrumb = ($scope.list_breadcrumb).concat(list_module);

        $('#list_breadcrumb').html(breadcrumb);

    }

    $scope.prepareListBreadcrumb();

    $scope.getPermisosRol();

});
