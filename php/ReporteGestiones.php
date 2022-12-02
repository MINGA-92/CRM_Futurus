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

if (isset($_POST['FechaFinal2'])) {
    $Hora = '00:00:00';
    $Hora2 = '23:59:59';
    $FechaI = $_POST['FechaInicial2'];
    $FechaF = $_POST['FechaFinal2'];
    $FechaInicial = $FechaI . ' ' . $Hora;
    $FechaFinal = $FechaF . ' ' . $Hora2;
} else {
    date_default_timezone_set("America/Bogota");
    $Fecha =  date("Y-m-d");
    $FechaInicial = $Fecha . ' ' . '00:00:00';
    $FechaFinal = $Fecha . ' '  . '23:59:59';
}

// Consulta Nombre usuario y Supervisor
$datos = 'Activo';
$ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, PER_CGRUPO, PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKPER_NCODIGO = " . $codigopermisos . " AND PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PER_CESTADO = 'Activo';";
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
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}
//Datos informe agente Call
$ConsultaSQL = "SELECT COUNT(PENCAL_CESTADO_FINAL) AS CONTADOR1 FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL = 'Contacto' AND PENCAL_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {

        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CONTADOR1 = $FilaResultado['CONTADOR1'];

        }
    } else {
        $CONTADOR1="";
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    exit;
}

$ConsultaSQL2 = "SELECT COUNT(PENCAL_CESTADO_FINAL) AS CONTADOR2 FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL = 'No Contacto' AND PENCAL_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL2)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {

        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CONTADOR2 = $FilaResultado['CONTADOR2'];

        }
    } else {
        $CONTADOR2="";
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    exit;
}

$ConsultaSQL3 = "SELECT COUNT(PENCAL_CESTADO_FINAL2) AS CONTADOR3 FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL2 = 'Cita Agendada' AND PENCAL_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL3)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {

        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CONTADOR3 = $FilaResultado['CONTADOR3'];
        }
    } else {
        $CONTADOR3="";
    }
}else{
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    exit;
}

$ConsultaSQL4 = "SELECT COUNT(PENCAL_CESTADO_FINAL2) AS CONTADOR4 FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL2 = 'Numero Errado' AND PENCAL_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL4)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {

        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CONTADOR4 = $FilaResultado['CONTADOR4'];
        }
    } else {
        $CONTADOR4="";
    }
}else{
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    exit;
}

$ConsultaSQL5 = "SELECT COUNT(PENCAL_CESTADO_FINAL2) AS CONTADOR5 FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL2 = 'No Contesta' AND PENCAL_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL5)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {

        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CONTADOR5 = $FilaResultado['CONTADOR5'];
        }
    } else {
        $CONTADOR5="";
    }
}else{
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    exit;
}


mysqli_close($ConexionSQL);

?>

<!Doctype html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="../images/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/color_skins.css">
    <link rel="stylesheet" href="../css/EstilosPersonalizadosPlantilla.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Contacto', <?php echo $CONTADOR1 ?>],               
                ['Cita Agendada', <?php echo $CONTADOR3 ?>],

            ]);

            var options = {
                title: 'Reporte Contactados',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>

<script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['No Contacto', <?php echo $CONTADOR2 ?>],
                ['Numero Errado', <?php echo $CONTADOR4 ?>],
                ['No Contesta', <?php echo $CONTADOR5 ?>]
            ]);

            var options = {
                title: 'Reporte No Contactados',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('grafica2'));
            chart.draw(data, options);
        }
    </script>
</head>

<body class="theme-cyan">
    <div id="wrapper">
        <div id="nav" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="row">
                <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
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
                                                    <a href="#" class="icon-menu"><i class="icon-bulb" title="Pendientes 0" aria-expanded="false"></i><span style="background-color: #93C01F;" class="notification-dot"></span></a>
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
        <div id="subTitle" class="row">
            <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center !important;">
                        <div class="navbar-brand d-none d-sm-block d-md-block" style="margin-bottom: 3%;">
                            <h4>REPORTE DE GESTIONES CALL</h4>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h4>REPORTE DE GESTIONES CALL</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2"></div>
        </div>
    </div>
    <div class="container">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12  frm">
            <div class="form-group">

                <div class="col-lg-12 col text-left">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <div class="row">
                                <div id="piechart_3d" class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="width: 500px; height: 400px;"></div>
                                <div id="grafica2" class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="width: 500px; height: 400px;"></div>
                            </div>
                            <div class="body">
                            </div>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>

        <script src="../assets/bundles/libscripts.bundle.js"></script>
        <script src="../assets/bundles/vendorscripts.bundle.js"></script>
        <script src="../assets/bundles/datatablescripts.bundle.js"></script>
        <script src="../assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
        <script src="../assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
        <script src="../assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
        <script src="../assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
        <script src="../assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
        <script src="../assets/vendor/sweetalert/sweetalert.min.js"></script>
        <script src="../assets/bundles/mainscripts.bundle.js"></script>
        <script src="../assets/js/pages/tables/jquery-datatable.js"></script>
        <script src="../js/datagrid/datatables/datatables.export.js"></script>

        <script>





        </script>

</body>

</html>