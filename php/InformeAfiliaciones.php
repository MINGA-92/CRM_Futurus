
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
    $FechaInicial= $Fecha .' '. '00:00:00';
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

$TablaDatos = "";
$ColumnaDatos = "";
$DETCLI_CDETALLE = "";
$FechaNacimientoCliente = "";
$EstadoCivilCliente = "";
$CorreoCliente = "";
$CelularCliente = "";
$SalarioCliente = "";
$FondoPensionCliente = "";
$CargoLaboralCliente = "";
$EdadCliente = "";
$DireccionDomicilioPrincipal = "";
$TelefonoCliente = "";
$FechaIngresoLaboral = "";
$CargoLaboralCliente = "";
$IBCCliente = "";
$cmbIncentivo = "";


$ConsultaSQL1 = "SELECT FKPENAFL_NPKPENCAL_NCODIGO, FKPENAFL_NPKPER_NCODIGO_LEGALIZADOR, CLI_CTIPO_DOCUMENTO, CLI_CDOCUMENTO, CONCAT(CLI_CNOMBRE,' ', CLI_CNOMBRE2,' ',CLI_CAPELLIDO, ' ' ,CLI_CAPELLIDO2) AS NOMBRECLIENTE, PENAFL_CFONDO_ANTERIOR, PENAFL_CFONDO_NUEVO, PENAFL_COBSERVACIONES, PENAFL_CESTADO_FINAL2, PENAFL_CFECHA_CONFIRMACION, CONCAT(CRE_CNOMBRE,' ', CRE_CNOMBRE2,' ',CRE_CAPELLIDO, ' ' ,CRE_CAPELLIDO2) AS NOMBREAGENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION, u632406828_dbp_crmfuturus.TBL_RCLIENTE, u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE FKPENAFL_NPKPENCAL_NCODIGO = PKCLI_NCODIGO AND FKPENAFL_NPKPER_NCODIGO_LEGALIZADOR = PKCRE_NCODIGO AND PENAFL_COBSERVACIONES!= '' AND PENAFL_CESTADO_FINAL != '' AND PENAFL_CESTADO_FINAL2!= '' AND PENAFL_CFECHA_CONFIRMACION BETWEEN '". $FechaInicial ."' AND '". $FechaFinal ."' AND PENALF_CESTADO = 'Activo';";
if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
    $CantidadResultados1 = $ResultadoSQL1->num_rows;
    if ($CantidadResultados1 > 0) {
        while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {

            $CodigoCliente = $FilaResultado['FKPENAFL_NPKPENCAL_NCODIGO'];
            $CodigoAsesor = $FilaResultado['FKPENAFL_NPKPER_NCODIGO_LEGALIZADOR'];
            $CLI_CTIPO_DOCUMENTO = $FilaResultado['CLI_CTIPO_DOCUMENTO'];
            $CLI_CDOCUMENTO = $FilaResultado['CLI_CDOCUMENTO'];
            $NOMBRECLIENTE = $FilaResultado['NOMBRECLIENTE'];
            $PENAFL_CFONDO_ANTERIOR = $FilaResultado['PENAFL_CFONDO_ANTERIOR'];
            $PENAFL_CFONDO_NUEVO = $FilaResultado['PENAFL_CFONDO_NUEVO'];
            $PENAFL_COBSERVACIONES = $FilaResultado['PENAFL_COBSERVACIONES'];
            $PENAFL_CESTADO_FINAL2 = $FilaResultado['PENAFL_CESTADO_FINAL2'];
            $PENAFL_CFECHA_CONFIRMACION = $FilaResultado['PENAFL_CFECHA_CONFIRMACION'];
            $NOMBREAGENTE = $FilaResultado['NOMBREAGENTE'];

            $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA = 'EmpresaCliente'AND FKDETCLI_NCLI_NCODIGO= '". $CodigoCliente ."' AND DETCLI_CESTADO= 'Activo';  ";
            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if ($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $CodigoEmpresa = $FilaResultado['DETCLI_CDETALLE'];
                    }
                    
                }else {
                    $CodigoEmpresa= "";
                }
            }else {
                $ErrorConsulta = mysqli_error($ConexionSQL);
                echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                mysqli_close($ConexionSQL);
                exit;
            }

            $ConsultaSQL2 = "SELECT CONCAT(EMP_CNOMBRE,' ', EMP_CNOMBRE2,' ',EMP_CAPELLIDO, ' ' ,EMP_CAPELLIDO2) AS NOMBREEMPRESA FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE PKEMP_NCODIGO = '". $CodigoEmpresa ."' AND EMP_CESTADO= 'Activo';";
            if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                $CantidadResultados2 = $ResultadoSQL2->num_rows;
                if ($CantidadResultados2 > 0) {
                    while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                        $NombreEmpresa = $FilaResultado['NOMBREEMPRESA'];
                    }
                }else {
                    $NombreEmpresa= "";
                }

            }else {
                $ErrorConsulta = mysqli_error($ConexionSQL);
                echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                mysqli_close($ConexionSQL);
                exit;
            }
            
            $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA = 'IBCCliente'AND FKDETCLI_NCLI_NCODIGO= '". $CodigoCliente ."' AND DETCLI_CESTADO= 'Activo';  ";
            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if ($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $IBCCliente = $FilaResultado['DETCLI_CDETALLE'];
                    }
                    
                }else {
                    $IBCCliente= "";
                }
            }else {
                $ErrorConsulta = mysqli_error($ConexionSQL);
                echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                mysqli_close($ConexionSQL);
                exit;
            }

            $TablaDatos .= '
                    <tr>
                        <td style="text-align: center;" hidden>' . $CodigoCliente . '</td>
                        <td style="text-align: center;" hidden>' . $CodigoAsesor . '</td>
                        <td style="text-align: center;">' . $CLI_CTIPO_DOCUMENTO . '</td>
                        <td style="text-align: center;">' . $CLI_CDOCUMENTO . '</td>
                        <td style="text-align: center;">' . $NOMBRECLIENTE . '</td>
                        <td style="text-align: center;" hidden>' . $NombreEmpresa . '</td>
                        <td style="text-align: center;">' . $PENAFL_CFONDO_ANTERIOR . '</td>
                        <td style="text-align: center;">' . $IBCCliente . '</td>
                        <td style="text-align: center;">' . $PENAFL_CFONDO_NUEVO . '</td>
                        <td style="text-align: center;" hidden>' . $PENAFL_CESTADO_FINAL2 . '</td>
                        <td style="text-align: center;" hidden>' . $PENAFL_COBSERVACIONES . '</td>
                        <td style="text-align: center;">' . $PENAFL_CFECHA_CONFIRMACION . '</td>
                        <td style="text-align: center;">' . $NOMBREAGENTE . '</td>
                    </tr>
                ';
        }
    } else {
        $CodigoCliente = "";
        $CodigoAsesor = "";
        $CLI_CTIPO_DOCUMENTO = "";
        $CLI_CDOCUMENTO = "";
        $NOMBRECLIENTE = "";
        $PENAFL_CFONDO_ANTERIOR = "";
        $PENAFL_CFONDO_NUEVO = "";
        $PENAFL_COBSERVACIONES = "";
        $PENAFL_CESTADO_FINAL2 = "";
        $PENAFL_CFECHA_CONFIRMACION = "";
        $NOMBREAGENTE = "";
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
    <title>Informe Afiliaciones :: Futurus</title>
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
                            <h4>INFORME DE TODAS LAS AFILIACIONES</h4>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h4>INFORME DE TODAS LAS AFILIACIONES</h4>
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
                            <div class="header">
                            </div>
                            <div class="body">
                                <table class="table table-bordered table-striped table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable js-exportable">
                                    
                                    <div class="row">
                                    
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
                                        
                                    
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;" hidden>Id Cliente</th>
                                            <th style="text-align: center;" hidden>Id Agente</th>
                                            <th style="text-align: center;">Tipo Documento</th>
                                            <th style="text-align: center;">Documento</th>
                                            <th style="text-align: center;">Nombre Cliente</th>
                                            <th style="text-align: center;" hidden>Empresa</th>
                                            <th style="text-align: center;">Fondo Anterior</th>
                                            <th style="text-align: center;">IBC Cliente</th>
                                            <th style="text-align: center;">Afiliado A:</th>
                                            <th style="text-align: center;" hidden>Estado De Afiliacion</th>
                                            <th style="text-align: center;" hidden>Observaciones De Afiliacion</th>
                                            <th style="text-align: center;">Fecha De Afiliacion</th>
                                            <th style="text-align: center;">Agente Que Getiono</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        echo $TablaDatos;
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: center;" hidden>Id Cliente</th>
                                            <th style="text-align: center;" hidden>Id Agente</th>
                                            <th style="text-align: center;">Tipo Documento</th>
                                            <th style="text-align: center;">Documento</th>
                                            <th style="text-align: center;">Nombre Cliente</th>
                                            <th style="text-align: center;" hidden>Empresa</th>
                                            <th style="text-align: center;">Fondo Anterior</th>
                                            <th style="text-align: center;">IBC Cliente</th>
                                            <th style="text-align: center;">Afiliado A:</th>
                                            <th style="text-align: center;" hidden>Estado De Afiliacion</th>
                                            <th style="text-align: center;" hidden>Observaciones De Afiliacion</th>
                                            <th style="text-align: center;">Fecha De Afiliacion</th>
                                            <th style="text-align: center;">Agente Que Getiono</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div>
                <form method="POST" action="InformeAfiliaciones.php" enctype="multipart/form-data">
                    <input id="FechaInicial2" name="FechaInicial2" hidden="true">
                    <input id="FechaFinal2" name="FechaFinal2" hidden="true">
                    <button id="Consultar" type="submit" class="btn" hidden="true">Guardar</button>
                </form>
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

        </script>

</body>

</html>