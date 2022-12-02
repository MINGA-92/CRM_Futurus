<?php

require('common.php');
require('funciones_generales.php');
require('ConsultaNotificaciones.php');
//session_start();

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


//Validacion De Usuario
if ($PER_CNIVEL != 'Supervisor'){
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//Consulta Tabla Completa
$Datos = array();
$ConsultaSQL = "SELECT PKPER_NCODIGO AS CODIGO, CRE_CUSUARIO AS USUARIO, CONCAT(CRE_CNOMBRE, ' ', CRE_CNOMBRE2, ' ', CRE_CAPELLIDO, ' ', CRE_CAPELLIDO2) AS NOMBRE, CRE_CDOCUMENTO AS DOCUMENTO, PER_CCARGO AS CARGO, PER_CESTADO AS ESTADO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND CRE_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CodigoCaso = $FilaResultado['CODIGO'];
            $Documento = $FilaResultado['DOCUMENTO'];
            $CRE_CNOMBRE = $FilaResultado['NOMBRE'];
            $Cargo = $FilaResultado['CARGO'];
            $Usuario = $FilaResultado['USUARIO'];
            $Estado = $FilaResultado['ESTADO'];
            
            array_push($Datos, array("0" => $Documento, "1" => $Usuario, "2" => $CRE_CNOMBRE, "3" => $Cargo, "4" => $Estado, "5" => $CodigoCaso));
        }
    } else {
        //Sin Resultados
    }
} else {
    //Errro en la consulta sql
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='ListadoUsuarios.php';</script>";
    exit;
}

mysqli_close($ConexionSQL);
?>

<!Doctype html>
<html lang="es">

<head>
    <title> Listado De Usuarios :: Futurus </title>
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
                        <div class="navbar-brand d-none d-sm-block d-md-block" style="margin-bottom: 3%;">
                            <h4>LISTADO DE USUARIOS</h4>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h4>LISTADO DE USUARIOS</h4>
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
                    <div class="table-responsive">
                        <table id="tabla2"  class="table table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable" style="margin: 0px 0px 0px -18px;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Documento</th>
                                    <th style="text-align: center;">Usuario</th>
                                    <th style="text-align: center;">Nombre Completo</th>
                                    <th style="text-align: center;">Cargo</th>
                                    <th style="text-align: center;">Estado</th>
                                    <th style="text-align: center;">Actualizar</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: fff9f92f">
                                <?php
                                    for ($i = 0; $i < count($Datos); $i++) {
                                        echo '<tr>';
                                        for ($b = 0; $b < count($Datos[$i]); $b++) {
                                            if ($b == 5) {
                                                echo '<td style="text-align: center"><a id="BtnActualizar" class="BtnActualizar btn btn-primary" onclick="enviarInformacionUsuario(' . $Datos[$i][$b] .');"><i class="fa  icon-note"></i></a></td>';
                                            } else if ($b == 2) {
                                                echo '<td style="text-align: center;">' . $Datos[$i][$b] .'</td>';
                                            } else {
                                                echo '<td style="text-align: center;">' . $Datos[$i][$b] . '</td>';
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                ?>
                            </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align: center;">Documento</th>
                                    <th style="text-align: center;">Usuario</th>
                                    <th style="text-align: center;">Nombre Completo</th>
                                    <th style="text-align: center;">Cargo</th>
                                    <th style="text-align: center;">Estado</th>
                                    <th style="text-align: center;">Actualizar</th>
                                </tr>
                            </tfoot>
                        </table>
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