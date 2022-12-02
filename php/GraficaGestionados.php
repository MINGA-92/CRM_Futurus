
<?php

require('common.php');
require('funciones_generales.php');
require('ConsultaNotificaciones.php');
session_start();


if (isset($_SESSION['codigopermiso'])) {
} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}
$codigopermisos = $_SESSION['codigopermiso'];
$codigopermisos = trim($codigopermisos);

if (isset($_POST['FechaFinal2'])) {
    $Hora= '00:00:00';
    $Hora2= '23:59:59';
    $FechaI = $_POST['FechaInicial2']; 
    $FechaF = $_POST['FechaFinal2'];
    $FechaInicial= $FechaI. ' ' .$Hora;
    $FechaFinal= $FechaF. ' ' .$Hora2;
} else {
    date_default_timezone_set("America/Bogota");               
    $Fecha=  date ("Y-m-d");
    $FechaInicial= '2012-01-30' .' '. '00:00:00';
    $FechaFinal= $Fecha . ' '  .'23:59:59';
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


//Consulta Casos Todos Gestionados
$ConsultaSQL = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 != '' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        $CasosGestionados = $CantidadResultados;

    } else {
        $CasosGestionados = "0";
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta No Aplica
$ConsultaSQL2 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'No Aplica' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
    $CantidadResultados2 = $ResultadoSQL2->num_rows;
    if ($CantidadResultados2 > 0) {
        $CasosNoAplica = $CantidadResultados2;

    } else {
        $CasosNoAplica = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Viable En El Futuro
$ConsultaSQL3 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'Viable En El Futuro' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'InActivo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
    $CantidadResultados3 = $ResultadoSQL3->num_rows;
    if ($CantidadResultados3 > 0) {
        $CasosViableFuturo = $CantidadResultados3;

    } else {
        $CasosViableFuturo = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta No Contesta
$ConsultaSQL3 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'No Contesta' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
    $CantidadResultados3 = $ResultadoSQL3->num_rows;
    if ($CantidadResultados3 > 0) {
        $CasosNoContesta = $CantidadResultados3;

    } else {
        $CasosNoContesta = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Numero Errado
$ConsultaSQL4 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'Numero Errado' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
    $CantidadResultados4 = $ResultadoSQL4->num_rows;
    if ($CantidadResultados4 > 0) {
        $CasosNumeroErrado = $CantidadResultados4;

    } else {
        $CasosNumeroErrado = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta No Le Interesa
$ConsultaSQL5 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'No Le Interesa' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
    $CantidadResultados5 = $ResultadoSQL5->num_rows;
    if ($CantidadResultados5 > 0) {
        $CasosNoLeInteresa = $CantidadResultados5;

    } else {
        $CasosNoLeInteresa = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Desistimiento o Retracto
$ConsultaSQL6 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND (PENCAL_CESTADO_FINAL2 = 'Desistimiento o Retracto' OR PENCAL_CESTADO_FINAL2 = 'No Contacto') AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL6 = $ConexionSQL->query($ConsultaSQL6)) {
    $CantidadResultados6 = $ResultadoSQL6->num_rows;
    if ($CantidadResultados6 > 0) {
        $CasosDesistimientoRetracto = $CantidadResultados6;

    } else {
        $CasosDesistimientoRetracto = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Cliente Molesto
$ConsultaSQL7 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'Cliente Molesto' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL7 = $ConexionSQL->query($ConsultaSQL7)) {
    $CantidadResultados7 = $ResultadoSQL7->num_rows;
    if ($CantidadResultados7 > 0) {
        $CasosClienteMolesto = $CantidadResultados7;

    } else {
        $CasosClienteMolesto = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Cliente Indeciso
$ConsultaSQL8 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'Cliente Indeciso' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL8 = $ConexionSQL->query($ConsultaSQL8)) {
    $CantidadResultados8 = $ResultadoSQL8->num_rows;
    if ($CantidadResultados8 > 0) {
        $CasosClienteIndeciso = $CantidadResultados8;

    } else {
        $CasosClienteIndeciso = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Mas Adelante
$ConsultaSQL9 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND PENCAL_CESTADO_FINAL2 = 'Mas Adelante' AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'InActivo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL9 = $ConexionSQL->query($ConsultaSQL9)) {
    $CantidadResultados9 = $ResultadoSQL9->num_rows;
    if ($CantidadResultados9 > 0) {
        $CasosMasAdelante = $CantidadResultados9;

    } else {
        $CasosMasAdelante = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Volver a Llamar
$ConsultaSQL10 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND (PENCAL_CESTADO_FINAL2 = 'Volver a Llamar' OR PENCAL_CESTADO_FINAL2 = 'Rellamada') AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL10 = $ConexionSQL->query($ConsultaSQL10)) {
    $CantidadResultados10 = $ResultadoSQL10->num_rows;
    if ($CantidadResultados10 > 0) {
        $CasosVolverLlamar = $CantidadResultados10;

    } else {
        $CasosVolverLlamar = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}

//Consulta Envio De Afiliacion Exitoso
$ConsultaSQL11 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_COBSERVACIONES != '' AND (PENCAL_CESTADO_FINAL2 = 'Envio De Afiliacion Exitoso' OR PENCAL_CESTADO_FINAL2 = 'Cita Agendada') AND PENCAL_CFECHA_REGISTRO BETWEEN '" . $FechaInicial . "' AND '" . $FechaFinal . "' AND PENCAL_CESTADO = 'Activo' GROUP BY FKPENCAL_NPKCLI_NCODIGO;";
if ($ResultadoSQL11 = $ConexionSQL->query($ConsultaSQL11)) {
    $CantidadResultados11 = $ResultadoSQL11->num_rows;
    if ($CantidadResultados11 > 0) {
        $CasosEnvioDeAfiliacion = $CantidadResultados11;

    } else {
        $CasosEnvioDeAfiliacion = 0;
    }
    
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
    exit;
}


/*print_r($TotalDeCasos);
print_r($CasosSinGestionar);
print_r($CasosGestionados);
exit;*/

mysqli_close($ConexionSQL);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Casos Gestionados</title>
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="../images/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/color_skins.css">
    <link rel="stylesheet" href="../css/EstilosPersonalizados2.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
</head>
<body>

    <div id="wrapper">
        <div id="nav" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="row">
                <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
                    <nav>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 d-none d-sm-none d-md-none d-lg-block">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="#"><img width="50%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 d-block d-sm-block d-md-block d-lg-none" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="#"><img width="60%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
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
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-bulb" title="Notificaciones: <?php echo $CantidadResultados ?>" aria-expanded="false"></i><span style="background-color: #93C01F;" class="notification-dot"></span></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php  require("Notificaciones.php") ?>
                                                    </ul> 
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="glyphicon glyphicon-stats" title="Reportes"></i></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php  require("Reportes.php") ?>
                                                    </ul> 
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></a>
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
                        <div class="navbar-brand d-none d-sm-block d-md-block" style="margin-bottom: 3%; margin-top: -2%;">
                            <h6>REPORTE GENERAL DE CASOS</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-bottom: 3%; margin-top: -2%;">
                            <h6>REPORTE GENERAL DE CASOS</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" ></div>
        </div>
    </div>

    <div class="row" style="margin-left: 28%;">
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group has-feedback">
                <label id="lblFechaInicial">Fecha Inicial</label>
                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                <input type="date" id="FechaInicial" class="form-control transparencia" required="">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group has-feedback">
                <label id="lblFechaFinal">Fecha Final</label>
                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                <input type="date" id="FechaFinal" class="form-control transparencia" required="" disabled>
            </div>
        </div>
    </div>
    
    <div class = "contenedor"> 
        <div class = "fila"> 
            <div class = "col-10 col-sm-6 col-md-9 col-lg-9 col-xl-9"> 
                <div style="margin-left: 32%;" class= "tarjeta"> 
                    <div class= "card-body"> 
                        <canvas id="myChart" width="228" height="228"></canvas>
                    </div> 
                </div> 
            </div> 
        </div> 
    </div>

    <div>
        <form method="POST" action="GraficaGestionados.php" enctype="multipart/form-data">
            <input id="FechaInicial2" name="FechaInicial2" hidden="true">
            <input id="FechaFinal2" name="FechaFinal2" hidden="true">
            <button id="Consultar" type="submit" class="btn" hidden="true">Guardar</button>
        </form>
    </div>

    <script>

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['No Aplica', 'Viable En El Futuro', 'No Contesta', 'Numero Errado', 'No Le Interesa', 'No Contacto o Desistimiento', 'Cliente Molesto', 'Cliente Indeciso', 'Mas Adelante', 'Volver a Llamar', 'Envio De Afiliacion'],
                datasets: [{
                    label: 'Numero De Casos',
                    data: [<?php echo $CasosNoAplica; ?>, <?php echo $CasosViableFuturo; ?>, <?php echo $CasosNoContesta; ?>, <?php echo $CasosNumeroErrado; ?>, <?php echo $CasosNoLeInteresa; ?>, <?php echo $CasosDesistimientoRetracto; ?>, <?php echo $CasosClienteMolesto; ?>, <?php echo $CasosClienteIndeciso; ?>, <?php echo $CasosMasAdelante; ?>, <?php echo $CasosVolverLlamar; ?>, <?php echo $CasosEnvioDeAfiliacion; ?>],
                    backgroundColor: [
                        'rgba(0, 0, 0, 0.8)',
                        'rgba(255, 215, 0, 0.8)',
                        'rgba(28, 192, 228, 0.8)',
                        'rgba(255, 0, 0, 0.8)',
                        'rgba(245, 245, 220, 0.8)',
                        'rgba(255, 140, 0, 0.8)',
                        'rgba(34, 139, 34, 0.8)',
                        'rgba(139, 69, 19, 0.8)',
                        'rgba(138, 43, 228, 0.8)',
                        'rgba(119, 135, 153, 0.8)',
                        'rgba(173, 255, 47, 0.8)',
                    ],
                    borderColor: [
                        'rgba(0, 0, 0)',
                        'rgba(255, 215, 0)',
                        'rgba(28, 192, 228)',
                        'rgba(255, 0, 0)',
                        'rgba(245, 245, 220)',
                        'rgba(255, 140, 0)',
                        'rgba(34, 139, 34)',
                        'rgba(139, 69, 19)',
                        'rgba(138, 43, 228)',
                        'rgba(119, 135, 153)',
                        'rgba(173, 255, 47)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        setTimeout(() => {

            $('body').on('change', '#FechaInicial', function() {
            document.getElementById("FechaFinal").disabled = false;
            })

            $('body').on('change', '#FechaFinal', function() {
                
                let form_data = new FormData();

                var FechaInicial = $("#FechaInicial").val();
                form_data.append('FechaInicial', FechaInicial);
                var FechaFinal = $("#FechaFinal").val();
                form_data.append('FechaFinal', FechaFinal);

                document.getElementById('FechaInicial2').value = FechaInicial;
                document.getElementById('FechaFinal2').value = FechaFinal;

                    
                $("#Consultar").click();

            })

        }, 1000);

        


    </script>

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
        
</body>
</html>