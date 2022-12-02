
<?php

require('common.php');
require('funciones_generales.php');
require('ConsultaNotificaciones.php');
session_start();

$Agente = $_POST['Agente'];
$id = $_POST['id2'];
$casos = $_POST['casos'];

$CantidadCasos = explode(",", $casos);
$Estado1 = "Activo";
$Estado2 = "InActivo";
$ActualizadoPor = "Actualizado por " . $Agente;
$RegistradoPor = "Registrado por " . $Agente;


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
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//Validacion De Usuario
if ($PER_CNIVEL != 'Supervisor'){
    echo "<script>window.location='logout.php';</script>";
    exit;
}



//Consulta USUARIOS para Asiganción de casos
$Datos = array();
$ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CDOCUMENTO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2 FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PER_CNIVEL = 'Agente HomeOffice' AND CRE_CESTADO = 'Activo' AND PER_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CodigoCaso = $FilaResultado['PKPER_NCODIGO'];
            $Documento = $FilaResultado['CRE_CDOCUMENTO'];
            $CRE_CNOMBRE = $FilaResultado['CRE_CNOMBRE'];
            $CRE_CNOMBRE2 = $FilaResultado['CRE_CNOMBRE2'];
            $CRE_CAPELLIDO = $FilaResultado['CRE_CAPELLIDO'];
            $CRE_CAPELLIDO2 = $FilaResultado['CRE_CAPELLIDO2'];
            
            $Usuario = $FilaResultado['CRE_CUSUARIO'];
            
            array_push($Datos, array("0" => $Documento, "1" => $Usuario, "2" => $CRE_CNOMBRE.' '. $CRE_CNOMBRE2.' '. $CRE_CAPELLIDO.' '.$CRE_CAPELLIDO2,"3" => $CodigoCaso));
        }
    } else {
        //Sin Resultados
    }
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
}



$TablaDatos = "";
$Datos2 = array();
for ($i = 0; $i < count($CantidadCasos); $i++) {
    $CodigoCaso = $CantidadCasos[$i];
    $ConsultaSQL1 = "SELECT PKPENCAL_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE PKCLI_NCODIGO = FKPENCAL_NPKCLI_NCODIGO AND PENCAL_CESTADO = 'Activo' AND PKPENCAL_NCODIGO = '". $CodigoCaso ."' AND FKPENCAL_NPKPER_NCODIGO IS NULL;";
    if ($ResultadoSql1 = $ConexionSQL->query($ConsultaSQL1)) {
        $CantidadResultados1 = $ResultadoSql1->num_rows;
        if ($CantidadResultados1 > 0) {
            while ($FilaResultado = $ResultadoSql1->fetch_assoc()) {
                $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];
                $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
                $NOMBRE_CLIENTE = $FilaResultado['NOMBRE_CLIENTE'];
                $CodigoCliente = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];

                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                    if ($CantidadResultados2 > 0) {
                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                            $FondoNuevo = $FilaResultado['DETCLI_CDETALLE'];

                            $ConsultaSQL3 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                if ($CantidadResultados3 > 0) {
                                    while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                        $FondoPensionCliente = $FilaResultado['DETCLI_CDETALLE'];
                                        
                                        //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $NOMBRE_CLIENTE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $PKPENCAL_NCODIGO, "5" => $PKPENCAL_NCODIGO));
                            
                                    }
                                } else {
                                    //Sin Resultados
                                    $CantidadDeCambios ="";
                                }
                            } else {
                                //Errro en la consulta sql
                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                mysqli_close($ConexionSQL);
                                echo "<script>window.location='logout.php';</script>";
                                exit;
                            }

                            
                            /*
                            $TablaDatos .= '
                                <tr>
                                    <td style="text-align: center;">' . $CLI_CDOCUMENTO . '</td>
                                    <td style="text-align: center;">' . $NOMBRE_CLIENTE . '</td>
                                    <td style="text-align: center;">' . $FondoNuevo . '</td>
                                    <td style="text-align: center;"><label><input type="checkbox" class="btn-check" name="checks[]" id="btn-check" autocomplete="off" value="' . $PKPENCAL_NCODIGO . '"/><span></span></label></td>
                                </tr>
                            ';
                            */

                        }
                    } else {
                        //Sin Resultados
                        $FondoNuevo ="";
                    }
                } else {
                    //Errro en la consulta sql
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    echo "<script>window.location='logout.php';</script>";
                    exit;
                }

                array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $NOMBRE_CLIENTE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $PKPENCAL_NCODIGO, "5" => $PKPENCAL_NCODIGO));
            
            }

        } else {
            //Sin Resultados
            mysqli_close($ConexionSQL);
            $php_response = array("msg" => "Sin Resultados");
            echo json_encode($php_response);
            exit;
        }
        
    } else {
        //Error en la consulta
        mysqli_close($ConexionSQL);
        $Falla = mysqli_error($ConexionSQL);
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        echo json_encode($php_response);
        exit;
    }

}


$ListadoSelecionarPor = "";
$ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA= 'cmbSeleccionar' AND EST_CESTADO= 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListadoSelecionarPor = $ListadoSelecionarPor . '<option value="' . $FilaResultado['EST_CDETALLE'] . '">' . $FilaResultado['EST_CDETALLE'] . '</option>';
        }
    } else {
        //Sin Resultados
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}



mysqli_close($ConexionSQL);

?>
<!Doctype html>
<html lang="es">

<head>
    <title>Asignacion Casos Nuevos :: Futurus</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="../images/logo2.png" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/color_skins.css">
    <!--Estilis Plantilla Fturus-->
    <link rel="stylesheet" href="../css/EstilosPersonalizadosPlantilla.css">
</head>

<body class="theme-cyan">
<!-- Overlay For Sidebars -->
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
                            <h6>PREASIGNACION DE CASOS NUEVOS</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h6>PREASIGNACION DE CASOS NUEVOS</h6>
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

                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%; padding-left: 0%;">
                        <label id="LblFiltro" style="font-size: 10px; margin-top: 10%; margin-left: -1%;"><h5>Seleccionar: </h5>
                    </div>
                    <div class="row">
                        
                        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 1%; margin-top: -1%;">
                            <select id="SelecionarPor" name="" class="form-control" required="">
                                <option selected="" hidden="" disabled=""> Seleccionar... </option>
                                <?php echo $ListadoSelecionarPor; ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 2%; margin-top: -1%;">
                            <button id="BtnSelecionarPor" name="BtnSelecionarPor" type="button" class="btn btn-futurus-r">SELECCIONAR</button>
                        </div>
                    </div>
                    <label class="btn btn-primary nextBtn btn-lg center-block btnsig" id="Seleccionar_Todo" style="float: right">SELECCIONAR TODOS</label>
                        <button id="btnAsignar" data-toggle="modal" data-target="#myModal" class="btn btn-primary nextBtn btn-lg center-block btnsig" type="submit">ASIGNAR</button>     
                    </div>
                    <br>
                </div>
                
                
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
                                        <th style="text-align: center;">Fondo Actual</th>
                                        <th style="text-align: center;">Fondo Al Que Va</th>
                                        <th style="text-align: center;">Seleccionar</th>
                                        <th style="text-align: center;">Actualizar Informacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                        for ($i = 0; $i < count($Datos2); $i++) {
                                            echo '<tr>';
                                            for ($b = 0; $b < count($Datos2[$i]); $b++) {
                                                if($b == 4){
                                                    echo '<td style="text-align: center;"><input type="checkbox" class="btn-check" name="checks[]" id="btn-check" autocomplete="off" value="' . $Datos2[$i][4] . '"/></td>';
                                                }else if($b == 5){
                                                    echo '<td style="text-align: center;"><label><a onclick="enviarInformacionSupervisor(' .$Datos2[$i][5] .');" id="SeleccionarCaso" class="btn btn-futurus-r " data-target="1" value=""><i class="fa icon-note"></i></a><span></span></label></td>';
                                                }else{
                                                    echo '<td style="text-align: center;">' . $Datos2[$i][$b] . '</td>';
                                                }                      
                                            }
                                            echo '</tr>';
                                        }   

                                        //echo $TablaDatos;
                                    
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center;">Documento</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Fondo Actual</th>
                                        <th style="text-align: center;">Fondo Al Que Va</th>
                                        <th style="text-align: center;">Seleccionar</th>
                                        <th style="text-align: center;">Actualizar Informacion</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal" id="myModal">
            <div class="modal-lg modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Asignación De Casos</h4>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>

                    <div id="Loading" style="margin-left: 30%">
                        <img src="../images/loading.gif">
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        
                        <table id="tabla2" class="table table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable" >
                            <thead>
                                <tr>
                                    <th class="center">Documento</th>
                                    <th class="center">Usuario</th>
                                    <th class="center">Nombre</th>
                                    <th class="center">Asignar Casos</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: fff9f92f">
                                <?php

                                    for ($i = 0; $i < count($Datos); $i++) {
                                        echo '<tr>';
                                        for ($b = 0; $b < count($Datos[$i]); $b++) {
                                            if ($b == 3) {
                                                echo '<td style="text-align: center"><a id="AsignarCaso' . $Datos[$i][$b] . '" class="AsignarCaso btn btn-primary"><i class="fa  icon-note"></i></a></td>';
                                            } else if ($b == 2) {
                                                echo '<td style="text-align: center;">' . $Datos[$i][$b] .'</td>';
                                            } else {
                                                echo '<td style="text-align: center;">' . $Datos[$i][$b] . '</td>';
                                            }
                                        }
                                        echo '</tr>';
                                        
                                    }

                                    
                                ?>
                                <tr>
                                <th class="center">Documento</th>
                                <th class="center">Usuario</th>
                                <th class="center">Nombre</th>
                                <th class="center">Asignar Casos</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal footer -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
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
            window.tabla1 = $('#tabla').dataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-2 d-flex align-items-center justify-content-start'f>    >" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    //Quitar Paginado
                    "paging": false
                    
                   
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

        $(document).ready(function() {
            $('#Loading').hide();

        });


    </script>
    <script>

        //Funcion para asignar caso a un usuario
        $("body").on('click', '.AsignarCaso', function(){

            $("#tabla2_wrapper").hide();
            $("#Loading").show();
            let form_data = new FormData();

            id = $(this).attr("id");
            id = id.replace("AsignarCaso", "");
            form_data.append('id', id);
           
            var Agente = $("#Agente").val();
            form_data.append('Agente', Agente);
            var casos = $("#str").val();
            form_data.append('casos', casos);
            
            if (casos == ""){
                alert("Se tiene que seleccionar un caso para asignar al asesor!");
                $("#Loading").hide();
                $("#tabla2_wrapper").show();
            }else{
                $.ajax({
                    url: "GuardarCasosPendientesCall.php",
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response){
                        Respuesta = php_response.msg;
                        console.log(Respuesta);
                        if(Respuesta == "Ok"){
                            alert("¡Asignacion De Caso Exitosa! 😉");
                            $("#Loading").hide();
                            $("#tabla2_wrapper").show();
                            window.location = "AsignacionNuevosHomeOffice.php";
                        } else if (Respuesta == "Error") {
                            alert("😩  Se genero una falla en la asignación!");
                            $("#Loading").hide();
                            $("#tabla2_wrapper").show();
                            console.log("Error en el sistema");
                            console.log(php_response.Falla);
                        }
                    },
                    error: function(php_response) {
                        php_response = JSON.stringify(php_response);
                        alert("Error en la comunicacion con el servidor!");
                        
                        console.log(php_response);

   
                    }
                })
            }
        })

        //Seleccionar Un Caso
        $(document).ready(function() {

            $('[name="checks[]"]').click(function() {

                var arr = $('[name="checks[]"]:checked').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');

                $('#str').val(str);
            
            });

        });

        //Funcion para Seleccionar Todo
        $("#Seleccionar_Todo").click(function(){

            let IsCheck= $(this).is(':checked');
            
            if(IsCheck == false){
                $(".btn-check").prop('checked', true);
                var arr = $('[name="checks[]"]:checked').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');
                $('#str').val(str);
            }else{
                $(".btn-check").prop('checked', false);
            }
        });
        

        $("#BtnSelecionarPor").click(function(){

            tbody = $("#tabla > tbody");

            cantidadDeTr = $("#tabla > tbody > tr");
            CantidadRegistros = cantidadDeTr.length;

            ValorSelecionarPor= $("#SelecionarPor").val();
            if (ValorSelecionarPor == "Seleccionar 100 Casos"){
                ValorASeleccionar = 100
            } else if (ValorSelecionarPor == "Seleccionar 200 Casos"){
                ValorASeleccionar = 200
            } else if (ValorSelecionarPor == "Seleccionar 300 Casos"){
                ValorASeleccionar = 300
            } else if (ValorSelecionarPor == "Seleccionar 400 Casos"){
                ValorASeleccionar = 400
            } else if (ValorSelecionarPor == "Seleccionar 500 Casos"){
                ValorASeleccionar = 500
            } else if (ValorSelecionarPor == "Seleccionar 600 Casos"){
                ValorASeleccionar = 600
            } else if (ValorSelecionarPor == "Seleccionar 700 Casos"){
                ValorASeleccionar = 700
            } else if (ValorSelecionarPor == "Seleccionar 800 Casos"){
                ValorASeleccionar = 800
            } else if (ValorSelecionarPor == "Seleccionar 900 Casos"){
                ValorASeleccionar = 900
            } else if (ValorSelecionarPor == "Seleccionar 1000 Casos"){
                ValorASeleccionar = 1000
            }

            if (ValorASeleccionar > CantidadRegistros){
                alert("¡La Cantidad a Selecionar, Supera La Cantidad De Registros!  🙄");

            }else{

                $(".btn-check").prop('checked', false);
                for (let D = 0; D < ValorASeleccionar; D++) {

                    tbody.children("tr:eq('"+ D +"')").children("td:eq(4)").find("input").prop('checked', true);

                }
            }

        });


    </script>

</body>

</html>