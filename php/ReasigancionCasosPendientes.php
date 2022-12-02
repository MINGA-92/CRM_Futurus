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
$ConsultaSQL = "SELECT FKPER_NCRE_NCODIGO, CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, PER_CGRUPO, PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL JOIN u632406828_dbp_crmfuturus.TBL_RPERMISO ON PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PKPER_NCODIGO = " . $codigopermisos . " AND CRE_CESTADO = '" . $datos . "' AND PER_CESTADO = '" . $datos . "' ORDER BY PKCRE_NCODIGO DESC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {

            $CRE_CUSUARIO = $FilaResultado['CRE_CUSUARIO'];
            $AGENTE = $FilaResultado['FKPER_NCRE_NCODIGO'];
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
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
}



//Consulta Datos para Asiganción de casos USUARIOS

$Datos = array();
$ConsultaSQL = "SELECT FKPER_NCRE_NCODIGO, CRE_CUSUARIO, CRE_CDOCUMENTO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2 FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PER_CNIVEL = 'AgenteVisitas' AND CRE_CESTADO = 'Activo' AND PER_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CodigoCaso = $FilaResultado['FKPER_NCRE_NCODIGO'];
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

//Consulta Tabla completa de Asignación casos visitas Clientes
$Datos1 = array();
$ConsultaSQL = "SELECT PKPENAFL_NCODIGO, CLI_CDOCUMENTO, CLI_CNOMBRE, CLI_CNOMBRE2, CLI_CAPELLIDO, CLI_CAPELLIDO2, PENCAL_CFONDO_NUEVO, DETCLI_CDETALLE, PENAFL_CFECHA_AGENDAMIENTO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE, u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE, u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE PKCLI_NCODIGO = FKDETCLI_NCLI_NCODIGO AND PKCLI_NCODIGO = FKPENCAL_NPKCLI_NCODIGO AND PKPENCAL_NCODIGO = FKPENAFL_NPKPENCAL_NCODIGO AND FKPENAFL_NPKPER_NCODIGO IS NULL AND DETCLI_CCONSULTA = 'DireccionDomicilioPrincipal' AND CLI_CESTADO = 'Activo' AND DETCLI_CESTADO = 'Activo' AND PENCAL_CESTADO = 'Activo' AND PENALF_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $PKPENAFL_NCODIGO = $FilaResultado['PKPENAFL_NCODIGO'];
            $CLI_CDOCUMENTO = $FilaResultado['CLI_CDOCUMENTO'];
            $CLI_CNOMBRE= $FilaResultado['CLI_CNOMBRE'];
            $CLI_CNOMBRE2 = $FilaResultado['CLI_CNOMBRE2'];
            $CLI_CAPELLIDO = $FilaResultado['CLI_CAPELLIDO'];
            $CLI_CAPELLIDO2 = $FilaResultado['CLI_CAPELLIDO2'];
            $PENCAL_CFONDO_NUEVO = $FilaResultado['PENCAL_CFONDO_NUEVO'];
            $DETCLI_CDETALLE = $FilaResultado['DETCLI_CDETALLE'];
            $PENAFL_CFECHA_AGENDAMIENTO = $FilaResultado['PENAFL_CFECHA_AGENDAMIENTO'];
      array_push($Datos1, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE.' '. $CLI_CNOMBRE2.' '. $CLI_CAPELLIDO.' '.$CLI_CAPELLIDO2,"2" => $DETCLI_CDETALLE,"3" => $PENCAL_CFONDO_NUEVO,"4"=>$PENAFL_CFECHA_AGENDAMIENTO, "5" => $PKPENAFL_NCODIGO));
        }
    } else {
        //Sin Resultados
    }
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
}


mysqli_close($ConexionSQL);
?>
<!doctype html>
<html lang="es">

<head>
    <title> Reasigancion Casos Pendientes :: Futurus</title>
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
                                                    <a href="app-inbox.html" class="icon-menu"><i class="icon-bulb" title="Pendientes 0" aria-expanded="false"></i><span style="background-color: #93C01F;" class="notification-dot"></span></a>
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
                            <h6>REASIGNACION DE CASOS PENDIENTES</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h6>REASIGNACION DE CASOS PENDIENTES</h6>
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
                
                    <button id="btnAsignar" data-toggle="modal" data-target="#myModal" class="btn btn-primary nextBtn btn-lg center-block btnsig" type="submit">REASIGNAR</button>
                    
                </div>
                <br>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="header">
                        </div>
                        <div class="body">
                            <table id="tabla" class="table table-bordered table-striped table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable">
                                <thead>
                                    <tr>
                                        
                                        <th style="text-align: center;">NIT</th>
                                        <th style="text-align: center;">NOMBRE EMPRESA</th>
                                        <th style="text-align: center;">CONTACTO</th>
                                        <th style="text-align: center;">EMPRESA</th>
                                        <th style="text-align: center;">NOMBRE CLIENTE</th>
                                        <th style="text-align: center;">TELEFONO CLIENTE</th>
                                        <th style="text-align: center;">SELECCIONAR</th>
                                        

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        for ($i = 0; $i < count($Datos1); $i++) {
                                            echo '<tr>';
                                            for ($b = 0; $b < count($Datos1[$i]); $b++) {
                                                if ($b == 5) {
                                                    echo '<td style="text-align: center;"><label><input type="checkbox" class="btn-check" name="checks[]" id="btn-check" autocomplete="off" value="' . $Datos1[$i][$b] . '"/><span></span></label></td>';
                                                } else if ($b == 2) {
                                                    echo '<td style="text-align: center;">' . $Datos1[$i][$b] . '</td>';
                                                } else {
                                                    echo '<td style="text-align: center;">' . $Datos1[$i][$b] . '</td>';
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                    
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <th style="text-align: center;">NIT</th>
                                        <th style="text-align: center;">NOMBRE EMPRESA</th>
                                        <th style="text-align: center;">CONTACTO</th>
                                        <th style="text-align: center;">EMPRESA</th>
                                        <th style="text-align: center;">NOMBRE CLIENTE</th>
                                        <th style="text-align: center;">TELEFONO CLIENTE</th>
                                        <th style="text-align: center;">SELECCIONAR</th>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Reasignación De Casos</h4>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                    <h5 align="center">Listado de Agentes</h5>
                    <table id="tabla2" class="table table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable" style="margin-left: 1%;">
                        <thead>
                            <tr>
                                <th class="center">Documento</th>
                                <th class="center">Usuario</th>
                                <th class="center">Nombre</th>
                                <th class="center">Reasignar Caso</th>
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
                                <th class="center">Reasignar Caso</th>
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
    <script>

        //Funcion para asignar caso a un usuario
        $("body").on('click', '.AsignarCaso', function(){
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
            }else{
                $.ajax({
                    url: "GuardarCasosAsignados.php",
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
                            alert("Asignacion Exitosa!");
                            window.location = "AsignacionCasosVisitas.php";
                        } else if (Respuesta == "Error") {
                            alert("Se genero una falla en la asignación!");
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


        $(document).ready(function() {

            $('[name="checks[]"]').click(function() {

                var arr = $('[name="checks[]"]:checked').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');

                $('#str').val(str);
            
            });

        });
    </script>

</body>

</html>