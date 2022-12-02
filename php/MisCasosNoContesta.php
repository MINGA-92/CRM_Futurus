
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
        $ErrorConsulta = mysqli_error($ConexionSQL);
        echo "eRrOr" . $ErrorConsulta;
        mysqli_close($ConexionSQL);
        //echo "<script>window.location='logout.php';</script>";
        exit;
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    echo "ErRoR" . $ErrorConsulta;
    mysqli_close($ConexionSQL);
    //echo "<script>window.location='logout.php';</script>";
    exit;
}

//Consulta Tabla Casos No Contesta
$Datos2 = array();
$ConsultaSQL = "SELECT PKPENCAL_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE, PENCAL_CFONDO_NUEVO AS FONDO, PENCAL_CESTADO_FINAL2 AS EstadoCliente, PENCAL_CFECHA_REGISTRO AS UltimaGestion FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE, u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE PKCLI_NCODIGO = FKDETCLI_NCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO = PKCLI_NCODIGO AND DETCLI_CCONSULTA = 'FondoPensionCliente' AND FKPENCAL_NPKPER_NCODIGO = " . $AGENTE . " AND (PENCAL_CESTADO_FINAL2 = 'No Contesta') AND PENCAL_CESTADO = 'Activo' GROUP BY DOCUMENTO;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
            $CLI_CNOMBRE= $FilaResultado['NOMBRE_CLIENTE'];
            $DETCLI_CDETALLE = $FilaResultado['FONDO'];
            $EstadoCliente = $FilaResultado['EstadoCliente'];
            $UltimaGestion = $FilaResultado['UltimaGestion'];
            $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];
            
            //Consulta CodigoCaso Para Consulta Numero de telefono
            $CodigoCaso= $PKPENCAL_NCODIGO;
            $ConsultaSQL = "SELECT FKPENCAL_NPKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO= '". $CodigoCaso ."' AND PENCAL_CESTADO = 'Activo';";
            if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL)) {
                $CantidadResultados2 = $ResultadoSQL2->num_rows;
                if ($CantidadResultados2 > 0) {
                    while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                        $CodigoCliente = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                        //Consulta Numero de telefono
                        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA= 'CelularCliente' AND FKDETCLI_NCLI_NCODIGO= '". $CodigoCliente ."' AND DETCLI_CDETALLE != '' LIMIT 1;";
                        if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL)) {
                            $CantidadResultados3 = $ResultadoSQL3->num_rows;
                            if ($CantidadResultados3 > 0) {
                                while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                    $NumeroCliente = $FilaResultado['DETCLI_CDETALLE'];

                                    //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $DETCLI_CDETALLE, "3" => $EstadoCliente, "4" => $UltimaGestion, "5" => $NumeroCliente, "6" => $PKPENCAL_NCODIGO));
                                    
                                }
                            } else {
                                //Sin Resultados
                                $NumeroCliente = "";
                            }
                        } else {
                            //Error en la consulta sql
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            mysqli_close($ConexionSQL);
                            //echo "<script>window.location='logout.php';</script>";
                            exit;
                        }

                    }
                } else {
                    //Sin Resultados
                    $CodigoCliente = "";
                }
            } else {
                //Error en la consulta sql
                $ErrorConsulta = mysqli_error($ConexionSQL);
                mysqli_close($ConexionSQL);
                //echo "<script>window.location='logout.php';</script>";
                exit;
            }

            array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $DETCLI_CDETALLE, "3" => $EstadoCliente, "4" => $UltimaGestion, "5" => $NumeroCliente, "6" => $PKPENCAL_NCODIGO));
            
        }


    } else {
        //Sin Resultados
    }
} else {
    //Error en la consulta sql
    $ErrorConsulta = mysqli_error($ConexionSQL);
    echo "ErRoR .l." . $ErrorConsulta;
    mysqli_close($ConexionSQL);
    //echo "<script>window.location='logout.php';</script>";
    exit;
}

mysqli_close($ConexionSQL);

?>


<!Doctype html>
<html lang="es">

<head>
    <title> Mis Casos No Contesta :: Futurus </title>
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
                                    <a href="AgenteCall.php"><img width="50%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 d-block d-sm-block d-md-block d-lg-none" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="AgenteCall.php"><img width="60%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
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
                                    <?php
                                        if ($CantidadCasosVencidos == ""){
                                            $CantidadCasosVencidos = "0";
                                        }
                                    ?>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-bulb" title="Pendientes: <?php echo $CantidadCasosVencidos ?>" aria-expanded="false"></i><span style="background-color: #93C01F;" class="notification-dot"></span></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php  require("Notificaciones.php") ?>
                                                    </ul> 
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-info"></i></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon">
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
                            <h6>MIS CASOS NO CONTESTA</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h6>MIS CASOS NO CONTESTA</h6>
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
                <br>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="header">
                        </div>
                        <div class="body">
                            <table id="tabla" class="table table-bordered table-striped table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Documento</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Fondo</th>
                                        <th style="text-align: center;">Estado De Atencion</th>
                                        <th style="text-align: center;">Ultima Gestion</th>
                                        <th style="text-align: center;">Numero De Contacto</th>
                                        <th style="text-align: center;">Gestionar</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        for ($i = 0; $i < count($Datos2); $i++) {
                                            echo '<tr>';
                                            for ($b = 0; $b < count($Datos2[$i]); $b++) {

                                                if($b == 6){
                                                    echo '<td style="text-align: center;"><label><a onclick="enviarInformacionHomeOffice(' . $Datos2[$i][$b] .');" id="SeleccionarCaso" class="btn btn-futurus-r " data-target="1" value=""><i class="fa icon-eye"></i></a><span></span></label></td>';
                                                }else{
                                                    echo '<td style="text-align: center;">' . $Datos2[$i][$b] . '</td>';
                                                }    

                                            }
                                            echo '</tr>';
                                        }                                    
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center;">Documento</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Fondo</th>
                                        <th style="text-align: center;">Estado De Atencion</th>
                                        <th style="text-align: center;">Ultima Gestion</th>
                                        <th style="text-align: center;">Numero De Contacto</th>
                                        <th style="text-align: center;">Gestionar</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input id="Agente" name="Agente" type="text" value="<?php echo $AGENTE; ?>" hidden="true">
    <input id="str" name="str" type="text" hidden="true">
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
    <script src="../js/ajax/controlPendientes.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla').dataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-2 d-flex align-items-center justify-content-start'f>    >" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });
        });

        $(document).ready(function() {
            $('#tabla2').dataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-2 d-flex align-items-center justify-content-start'f>    >" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });
        });
    </script>
</body>
</html>