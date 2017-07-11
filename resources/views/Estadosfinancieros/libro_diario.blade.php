<!DOCTYPE html>
<html>
<head>
    <title></title>

<style type="text/css">
    body{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            position: absolute;
        }

        .col-xs-3, .col-xs-6,  .col-xs-12 {
            position: relative;
            min-height: 1px;
            padding-right: 5px;
            padding-left: 5px;
        }

        /*.col-xs-3, .col-xs-6, .col-xs-12 {
            float: left;
        }*/

        .col-xs-12 {
            width: 100%;
        }

        .col-xs-6 {
            float: left;
            width: 50%;
        }

        .col-xs-3 {
            float: left;
            width: 25%;
        }

        .form-control {
            /*display: block;*/
            width: 100%;
            height: 20px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;

            text-align: right;

        }

        .table {
            border-collapse: collapse !important;
        }
        .table td,
        .table th {
            background-color: #fff !important;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .text-right
        {
            text-align: right !important;
        }

        .text-center
        {
            text-align: center !important;
        }

        .text-left
        {
            text-align: left !important;
        }
        .bg-primary{
            background:#2F70A8 !important;
        }
        .bg-success{
            background:#DFF0D8 !important;
        }
        .bg-warning{
            background:#FCF8E3 !important;
        }
</style>

</head>
<body>
    <div class="col-xs-12 text-center">
        <h3><strong><?= $aux_empresa[0]->nombrecomercial ?> </strong></h3>
    </div>
    <div class="col-xs-12 text-center">
        <h3><strong> Libro Diario </strong></h3>
    </div>
    <div class="col-xs-12 text-center">
        <h4><strong>En El Periodo Desde: <?= $filtro->FechaI ?>  Hasta : <?= $filtro->FechaF ?> </strong>  <strong>  Moneda: USD $ </strong></h4>
    </div>
    <div class="col-xs-12 text-right">
        <h4><strong>Fecha: <?= $today ?> </strong></h4>
    </div>
    
    <div class="col-xs-12">

        <?php
            function orden_plan_cuenta($orden)
            {
                $aux_orden=explode('.', $orden);
                $aux_numero_orden="";
                $aux_numero_completar="";
                $tam=count($aux_orden);
                if($tam>0){
                      for($x=0;$x<$tam;$x++){
                        if($x<3){
                            $aux_numero_orden.=$aux_orden[$x];
                        }elseif($x>=3){
                            if($x==3){
                                $aux_numero_completar=$aux_orden[$x];
                                if(strlen ((string)$aux_numero_completar)==1){
                                    $aux_numero_completar="0".$aux_numero_completar;
                                }
                                $aux_numero_orden.=$aux_numero_completar;
                            }elseif($x>3){
                                $aux_numero_orden.=$aux_orden[$x];
                            }

                        }
                    }
                }else{
                   $aux_numero_orden=$orden;
                }
                
                return $aux_numero_orden;
            }
            $aux_debe=0; $aux_haber=0;
            $total_aux_debe=0; $total_aux_haber=0;
            foreach ($libro_diario as $libro) {
                echo "<table class='table table-bordered'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Tipo: ".$libro->cont_tipotransaccion->descripcion."(".$libro->cont_tipotransaccion->siglas.")</th>";
                echo "<th colspan='4'>Asineto Nro: ".$libro->idtransaccion."</th>";
                echo "<th>Fecha : ".$libro->fechatransaccion."</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Numero</th>";
                echo "<th>Cuenta</th>";
                echo "<th>Descripción</th>";
                echo "<th>Debe</th>";
                echo "<th>Haber</th>";
                echo "<th>Estado</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    $aux_debe=0; $aux_haber=0;
                    foreach ($libro["cont_registrocontable"] as $reg) {
                        echo "<tr>";
                        echo "<td>".orden_plan_cuenta($reg["cont_plancuentas"]["jerarquia"])."</td>";
                        echo "<td>".$reg["cont_plancuentas"]["concepto"]."</td>";
                        echo "<td>".$reg["descripcion"]."</td>";
                        echo "<td>"."$ ".number_format($reg["debe_c"],4,'.',',')."</td>";
                        echo "<td>"."$ ".number_format($reg["haber_c"],4,'.',',')."</td>";
                        if($reg["estadoanulado"]==true){
                         echo "<td class='bg-success'>Activo</td>";
                        }
                        elseif ($reg["estadoanulado"]==false) {
                            echo "<td class='bg-warning'>Anulado</td>";
                        }
                        echo "</tr>";
                        $aux_debe+=$reg["debe_c"]; 
                        $aux_haber+=$reg["haber_c"];

                        $total_aux_debe+=$reg["debe_c"]; 
                        $total_aux_haber+=$reg["haber_c"];
                        echo "</tbody>";
                    }
                    echo "<tfoot>";
                    echo "<tr>";
                    echo "<th class='text-left' colspan='3'>".$libro->descripcion."</th>";
                    echo "<th>"."$ ".number_format($aux_debe,4,'.',',')."</th>";
                    echo "<th>"."$ ".number_format($aux_haber,4,'.',',')."</th>";
                    echo "<th></th>";
                    echo "</tr>";
                    echo "<tfoot>";
                echo "</table><br/>";
            }
        ?>
        
    </div>
    <div class="col-xs-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-right">Total Debe</th>
                    <th><?= "$ ".number_format($total_aux_debe,4,'.',',') ?></th>
                    <th><?= "$ ".number_format($total_aux_haber,4,'.',',') ?></th>
                    <th class="text-left">Total Haber</th>
                </tr>
            </thead>
        </table>
    </div>
</body>
</html>