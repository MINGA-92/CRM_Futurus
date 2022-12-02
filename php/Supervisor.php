<?php

require('common.php');
require('funciones_generales.php');
session_start();

if (isset($_SESSION['codigopermiso'])) {
} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}

$codigopermisos = $_SESSION['codigopermiso'];
$codigopermisos = trim($codigopermisos);
$hoy = date("Y-m-d");

// Consulta Nombre usuario y Supervisor
$datos = 'Activo';
$ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, PER_CGRUPO, PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL JOIN u632406828_dbp_crmfuturus.TBL_RPERMISO ON PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PKPER_NCODIGO = " . $codigopermisos . " AND CRE_CESTADO = '" . $datos . "' AND PER_CESTADO = '" . $datos . "' ORDER BY PKCRE_NCODIGO DESC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {

            $CRE_CUSUARIO = $FilaResultado['CRE_CUSUARIO'];
            $AGENTE = $FilaResultado['PKPER_NCODIGO'];
            $nombre = null;
            $nombrecompleto = null;
            $nombre = $FilaResultado['CRE_CNOMBRE'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombre;
            }

            $nombre = null;
            $nombre = $FilaResultado['CRE_CNOMBRE2'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombrecompleto . ' ' . $nombre;
            }

            $nombre = null;
            $nombre = $FilaResultado['CRE_CAPELLIDO'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombrecompleto . ' ' . $nombre;
            }

            $nombre = null;
            $nombre = $FilaResultado['CRE_CAPELLIDO2'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombrecompleto . ' ' . $nombre;
            }

            if ($nombrecompleto == null || $nombrecompleto == '') {
                $nombrecompleto = $FilaResultado['CRE_CUSUARIO'];
            } else {
            }

            $PER_CNIVEL = $FilaResultado['PER_CNIVEL'];
            $grupotrabajo = $FilaResultado['PER_CGRUPO'];
            break;
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
        mysqli_close($ConexionSQL);
        echo "<script>window.location='logout.php';</script>";
        exit;
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> 1' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}


//Validacion De Usuario
if ($PER_CNIVEL != 'Supervisor'){
    echo "<script>window.location='logout.php';</script>";
    exit;
}

mysqli_close($ConexionSQL);
?>
<!doctype html>
<html lang="es">

<head>
    <title> Supervisor :: Futurus</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="../images/logo2.png" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendor/c3/c3.min.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/color_skins.css">
    <!--Estilis Plantilla Fturus-->
    <link rel="stylesheet" href="../css/EstilosPersonalizadosPlantilla.css">
</head>

<body class="theme-cyan">

    <!-- Page Loader -->

    <!-- Overlay For Sidebars -->
    <div id="wrapper">
        <div id="nav" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="row">
                <div class="col-1 col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block"></div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 buttom-bar-line">
                    <nav>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 d-none d-sm-none d-md-none d-lg-block">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="index.html"><img width="50%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 d-block d-sm-block d-md-block d-lg-none" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="index.html"><img width="60%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 2%;">
                                    <h6 style="color: black;"><?php echo $nombrecompleto; ?></h6>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <div class="row">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block">
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block">
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li>
                                                    <a href="app-inbox.html" class="icon-menu"><i class="icon-bulb" title="Pendientes 0" aria-expanded="false"></i><span style="background-color: #93C01F;" class="notification-dot"></span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-info"></i></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php require("navdgacion.php") ?>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block"></div>
            </div>
        </div>
        <div id="subTitle" class="row top">
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 buttom-bar-line">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center !important;">
                        <div class="navbar-brand" style="margin-top: 2%; margin-bottom: 2%;">
                            <h6>Supervisor</h6>
                            <h6>Productividad Grupo de Trabajo</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2"></div>
        <div id="subTitle" class="row top">
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 buttom-bar-line">
                <div class="col-lg-12 col-md-6 col-sm-12">
                    <div class="card2">
                        <div class="header">
                            <h2>Reporte De Productividad General</h2>
                        </div>
                        <div class="body">
                            <div id="chart-time"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-6 col-sm-12">
                    <div class="card2">
                        <div class="header">
                            <h2>Reporte De Productividad Global</h2>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    <div id="main" style="margin-top: 1%;">
        <div class="row clearfix">
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="planned_task">
                    <div class="body">
                        <div class="row">
                            <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                <div class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!--Modals-->

                <div style="background-color: #ffffff1a;" class="modal fade" id="DatosAdicionalesCliente" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos Adicionales Del Cliente
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="" style="color: #5f615fb4;">Dirección Adicional</label>
                                        <input type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="" style="color: #5f615fb4;">Dirección Oficina</label>
                                        <input type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="" style="color: #5f615fb4;">País</label>
                                        <input type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="" style="color: #5f615fb4;">Departamento</label>
                                        <input type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="" style="color: #5f615fb4;">Ciudad</label>
                                        <input type="text" class="form-control" required="">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="" style="color: #5f615fb4;">Barrio</label>
                                        <input type="text" class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-futurus-r">Guardar</button>
                                <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DatosAdicionalesDireccion" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="largeModalLabel" style="color: #fafffa23;">Datos Adicionales Dirección</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Histórico de cambios</label>
                                    <input type="text" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Edad</label>
                                    <input type="num" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Descripción SIAFP</label>
                                    <input type="text" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Estado Civil</label>
                                    <input type="text" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Fecha expedición documento</label>
                                    <input type="date" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Lugar expedición documento</label>
                                    <input type="textt" class="form-control" required="">
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <label for="" style="color: #5f615fb4;">Correo electrónico</label>
                                    <input type="mail" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-futurus-r">Guardar</button>
                            <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="NumerosAdicionalesContacto" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="largeModalLabel" style="color: #fafffa23;">Datos Adicionales Contacto</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Número de Celular Adicional</label>
                                    <input type="Num" class="form-control" required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Número Fijo Adicional</label>
                                    <input type="num" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-futurus-r">Guardar</button>
                            <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DatosAdicionalesEmpleos" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="largeModalLabel" style="color: #fafffa23;">Datos Adicionales Empleo</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Fecha Ingreso Laboral</label>
                                    <input type="date" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-futurus-r">Guardar</button>
                            <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="DatosAdicionalesEmpresa" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="largeModalLabel" style="color: #fafffa23;">Datos Adicionales de la Empresa
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Nombre de la empresa</label>
                                    <input type="tetx" class="form-control" required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Empleados de la misma empresa</label>
                                    <input type="date" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-futurus-r">Guardar</button>
                            <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="NumerosAdicionalesContactoEmpresa" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="largeModalLabel" style="color: #fafffa23;">Números Adicionales de Contacto de la Empresa</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Número de Celular Adicional</label>
                                    <input type="Num" class="form-control" required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <label for="" style="color: #5f615fb4;">Número Fijo Adicional</label>
                                    <input type="num" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-futurus-r">Guardar</button>
                            <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
</body>
<!-- Javascript -->
<script src="../bundles/libscripts.bundle.js"></script>
<script src="../bundles/vendorscripts.bundle.js"></script>
<script src="../bundles/mainscripts.bundle.js"></script>
<script src="../vendor/c3/c3.min.js"></script>
<script src="../vendor/c3/d3.v5.min.js"></script>
<script src="../bundles/chartist.bundle.js"></script>
<script src="../js/pages/chart/c3.js"></script>
<!-- pie chart grafica redonda  -->
<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.PieChart);

        // Add data
        chart.data = [{
            "country": "Casos Gestionados",
            "litres": 501.9
        }, {
            "country": "Casos Asignados",
            "litres": 301.9

        }, {
            "country": "Casos Libres",
            "litres": 50
        }];

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "litres";
        pieSeries.dataFields.category = "country";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;

        // This creates initial animation
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;

    }); // end am4core.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>

</html>
