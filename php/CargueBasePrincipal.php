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

mysqli_close($ConexionSQL);
?>
<!doctype html>
<html lang="es">

<head>
    <title> Cargue Base Principal :: Futurus </title>
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
        <div id="Loading" style="margin-left: 35%">
            <img src="../images/loading.gif">
        </div>
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
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2"></div>
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8 buttom-bar-line">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center !important;">
                        <div class="navbar-brand" style="margin-top: 4%; margin-bottom: 2%;">
                            <?php
                            if (isset($_REQUEST['Archivo_Carga_Excel'])) {
                                echo '<hs>Información A Cargar</h5>';
                            } else {
                                echo '<hs>Selección De Archivo A Cargar</h5>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2"></div>
        </div>
    </div>

    <div id="main" style="margin-top: 10%;">
        <div class="row clearfix">
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" >
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="planned_task">
                    <div class="body" >
                        <?php
                        if (isset($_REQUEST['Archivo_Carga_Excel'])) {
                            require_once '../PHPExcel/Classes/PHPExcel/IOFactory.php';
                            //subir la imagen del articulo
                            $nameEXCEL = $_FILES['archivo']['name'];
                            $tmpEXCEL = $_FILES['archivo']['tmp_name'];
                            $extEXCEL = pathinfo($nameEXCEL);
                            $urlnueva = "../xls/empleados.xls";
                            if (is_uploaded_file($tmpEXCEL)) {
                                copy($tmpEXCEL, $urlnueva);
                            }
                            $objPHPExcel = PHPExcel_IOFactory::load('../xls/empleados.xls');
                            $nomRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

                            echo ('<div class="planned_task">
                                <div class="body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="header">
                                            </div>
                                            <div class="body">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                    <tr>');
                            for ($i = 1; $i <= 1; $i++) {

                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue() . '</th>');
                            }

                            echo ('</thead> <tbody>');
                            for ($i = 2; $i <= $nomRows; $i++) {
                                echo ('<tr>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue() . '</th>');
                                echo ('<th>' . $becera = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue() . '</th>');
                                echo ('</tr>');
                            }
                            echo ('
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>');
                        } else {
                            echo ('<div class="">
                                <form action="CargueBasePrincipal.php" method="post" enctype="multipart/form-data" name="form1">
                                    <div class="row">
                                      <div class="col-lg-10 col-md-10 col-sm-10 cl-xs-10 offset-1" style="margin-top: 4%;">
                                        <div class=" input-group fileinput-new" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>
                                                <input id="archivo" type="file" accept=".csv,.xlsx,.xlm,.ods,.xlsm" required onchange="" name="archivo"></input>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <button type="button" class="btn btn-futurus-r" id="eliminar">Eliminar</button>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center; margin-top: 5%;">
                                            <div class="custom-file">
                                                <button type="submit" id="BtnCargarArchivo" name="Archivo_Carga_Excel" class="btn btn-futurus-r">Cargar Archivo</button>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                              </form>
                            </div>');
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>                    
    <script src="../bundles/libscripts.bundle.js"></script>
    <script src="../bundles/vendorscripts.bundle.js"></script>
    <script src="../bundles/datatablescripts.bundle.js"></script>
    <script src="../vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="../vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="../vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="../vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="../vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
    <script src="../vendor/sweetalert/sweetalert.min.js"></script>
    <script src="../bundles/mainscripts.bundle.js"></script>
    <script src="../js/pages/tables/jquery-datatable.js"></script>
    <script>
        
        $(document).ready(function() {

            $("#Loading").hide();
            $("#seleccionar").click(function() {
                $("#file").click();
            });
            $("#eliminar").click(function() {
                $("#archivo").val("");
            });
            $(".dt-buttons").addClass("co-md-10 col-lg-10 col-xl-10 d-none d-sm-none d-md-block d-lg-block d-xl-block");
            $(".dt-buttons").attr("style", "margin-bottom: -4%; margin-left: -1.8%");
            $(".dt-buttons").append('<a id="Guardar" class="btn btn-round btn-primary" tabindex="0" aria-controls="DataTables_Table_0" href="GuardarCargue.php"><span>Guardar</span></a>');
        })

        $("#Guardar").click(function() {
            $("#Loading").show();
        })

        $("#BtnCargarArchivo").click(function() {
            let archivo = document.getElementById('archivo').value,
            extension = archivo.substring(archivo.lastIndexOf('.'),archivo.length);
            // Si la extensión obtenida no está incluida en la lista de valores
            if(document.getElementById('archivo').getAttribute('accept').split(',').indexOf(extension) < 0) {
                alert('¡Archivo inválido! No se permite la extensión: ' + extension);
                $("#archivo").text("");
                $("#archivo").val("");                       
            }
        })
        

    </script>
</body>

</html>