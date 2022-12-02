
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
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}


//Validacion De Usuario
if ($PER_CNIVEL != 'Supervisor'){
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//Consulta clientes
$cliente = array();
$ConsultaSQL = "SELECT PER_CCLIENTE FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CCLIENTE = 'FUTURUS' AND PER_CESTADO = 'Activo' GROUP BY PER_CCLIENTE ORDER BY PER_CCLIENTE ASC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            array_push($cliente, $FilaResultado['PER_CCLIENTE']);
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='.php';</script>";
    exit;
}

//Consulta PER_CPERMISO
$PER_CPERMISO = array();
$Estado = "Activo";
$ConsultaSQL = "SELECT PER_CPERMISO FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CESTADO = '" . $Estado . "' GROUP BY PER_CPERMISO ORDER BY PER_CPERMISO ASC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            array_push($PER_CPERMISO, $FilaResultado['PER_CPERMISO']);
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//Consulta Cargo
$PER_CCARGO = array();
$ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA= 'cmbRol' AND EST_CESTADO= 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            array_push($PER_CCARGO, $FilaResultado['EST_CDETALLE']);
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//Consulta Grupo
$PER_CGRUPO = array();
$ConsultaSQL = "SELECT PER_CGRUPO FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CESTADO = 'Activo' GROUP BY PER_CGRUPO ORDER BY PER_CGRUPO ASC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            array_push($PER_CGRUPO, $FilaResultado['PER_CGRUPO']);
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
    }
} else {
    // Error en la Consulta
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
    <title> Registro Usuarios :: Futurus</title>
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
    <link rel="stylesheet" href="../css/EstilosPersonalizadosActualizar.css">
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
                                    <a href="index.php"><img width="50%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 d-block d-sm-block d-md-block d-lg-none" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="index.php"><img width="60%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
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
        <div id="subTitle" class="row top">
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 buttom-bar-line">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center !important;">
                        <div class="navbar-brand" style="margin-top: 5%; margin-bottom: 2%;">
                            <h6>REGISTRO USUARIOS</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2"></div>
        </div>
    </div>

    <div id="main" class="row" style="margin-top: 3%;">
        <div class="col-12 col-sm-8 col-md-8  col-lg-8 col-xl-8 offset-sm-2 offset-md-2 offset-lg-2 offset-xl-2 ">
            <div class="row">
                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblUsuario">Usuario</label>
                        <input id="Usuario" type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control transparencia" required="">
                    </div>
                </div>
                
                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblContrasena">Contraseña</label>
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span id="mostrar" class="input-group-text"><i id="iconoContra" class="fa icon-lock"></i></span>
                            </div>
                            <input id="Contrasena" type="password" class="form-control transparencia" placeholder="">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblIdentificacion">Identificación</label>
                        <input id="Identificacion" type="text" onkeypress="return validaNumericos(event)" class="form-control transparencia" required="">
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblLogin">Login</label>
                        <input id="Login" type="text" onkeypress="return validaNumericos(event)" class="form-control transparencia" required="">
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblNombre">Primer Nombre</label>
                        <input id="Nombre" type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control transparencia" required="">
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblNombre2">Segundo Nombre</label>
                        <input id="Nombre2" type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control transparencia" required="">
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblApellido">Primer Apellido</label>
                        <input id="Apellido" type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control transparencia" required="">
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="LblApellido2">Segundo Apellido</label>
                        <input id="Apellido2" type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control transparencia" required="">
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-sm-12 form-group">
                    <label for="fname" id="LblCliente">Cliente</label>
                        <select id="Cliente" class="form-control" name="cliente" required>
                            <option selected hidden disabled>Selecciona un Cliente</option>
                            <?php
                                for ($i = 0; $i < count($cliente); $i++) {
                                    echo ('<option value="' . $cliente[$i] . '">' . $cliente[$i] . '</option>');
                                }
                            ?>
                        </select>
                 </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 form-group" id="ContSelCampana">
                    <label for="fname" id="LblCampana">Campaña</label>
                    <select id="Campana" class="form-control" name="campana" required>
                        <option selected hidden disabled>Selecciona Una Campaña</option>
                    </select>
                </div>
                
                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 form-group" id="ContSelTipoPermiso">
                    <label for="per_nnivel" id="LblTipoPermiso">Tipo Permiso</label>
                    <select class="form-control" id="TipoPermiso" required>
                        <option selected hidden disabled>Selecciona El Permiso</option>
                        <?php
                            for ($i = 0; $i < count($PER_CPERMISO); $i++) {
                                echo ('<option value="' . $PER_CPERMISO[$i] . '">' . $PER_CPERMISO[$i] . '</option>');
                            }
                        ?>
                    </select>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3" id="ContSelCargo">
                    <div class="form-group">
                        <label id="LblCargo">Cargo</label>
                        <select class="form-control" id="Cargo" name="Cargo" required>
                            <option selected hidden disabled>Seleccione Un Cargo</option>
                            <?php
                                for ($i = 0; $i < count($PER_CCARGO); $i++) {
                                    echo ('<option value="' . $PER_CCARGO[$i] . '">' . $PER_CCARGO[$i] . '</option>');
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3" id="ContSelGrupo">
                    <div class="form-group">
                        <label id="LblGrupo">Grupo</label>
                        <select id="Grupo" class="form-control" name="Grupo" tabindex="-1">
                            <option selected hidden disabled>Seleccionar una Opción</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3" id="ContSelSubGrupo">
                    <div class="form-group">
                        <label id="LblSubGrupo">Sub Grupo</label>
                        <select class="form-control" id="SubGrupo" name="SubGrupo">
                            <option selected hidden disabled>Selecciona SubGrupo</option>
                            <option>SubGrupo</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3" id="ContNuevoGrupo">
                    <div class="form-group">
                    <label for="NuevoGrupo" class="active" id="LblNuevoGrupo">Nombre Del Nuevo SubGrupo</label>
                        <input id="NuevoGrupo" name="NuevoGrupo" type="text" class="form-control validate active" value="">
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center; margin-top: 1%;">
                    <button id="Guardar" type="button" class="Guardar btn btn-futurus-r">Registrar</button>
                </div>
            </div>
        </div>
    </div>
    <input type="text" id="AutorizacionRegistro" name="AutorizacionRegistro" class="form-control shadow-inset-2" placeholder="" required hidden="true" value="<?php echo $codigopermisos; ?>">
    <input id="Agente" name="Agente" type="text" value="<?php echo $nombrecompleto; ?>" hidden="true">
    <!-- Javascript -->
    <script src="../bundles/libscripts.bundle.js"></script>
    <script src="../bundles/vendorscripts.bundle.js"></script>
    <script src="../bundles/mainscripts.bundle.js"></script>
    <script src="../js/ajax/Registro.js"></script>
    <script src="../js/ajax/RegistroUsuarios.js"></script>
    
    <script>
        function validaNumericos(event) {
            if (event.charCode >= 48 && event.charCode <= 57) {
                return true;
            }
            return false;
        }


    </script>


</body>
</html>