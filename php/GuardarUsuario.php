
<?php

require('common.php');
require('funciones_generales.php');
session_start();


$Agente = $_POST['Agente'];
$Usuario = $_POST['Usuario'];
$Contrasena = EnCrypt($_POST['Contrasena']);
$Identificacion = $_POST['Identificacion'];
$Login = $_POST['Login'];
$Nombre = $_POST['Nombre'];
$Nombre2 = $_POST['Nombre2'];
$Apellido = $_POST['Apellido'];
$Apellido2 = $_POST['Apellido2'];
$Cliente = $_POST['Cliente'];
$Campana = $_POST['Campana'];
$TipoPermiso = $_POST['TipoPermiso'];
$Cargo = $_POST['Cargo'];
$Grupo = $_POST['Grupo'];
$SubGrupo = $_POST['SubGrupo'];
$NuevoGrupo = $_POST['NuevoGrupo'];
$AutorizacionRegistro = $_POST['AutorizacionRegistro'];


$Estado1 = "Activo";
$Estado2 = "InActivo";
$ActualizadoPor = "Actualizado por " . $Agente;
$RegistradoPor = "Registrado por " . $Agente;
$datos = 'Activo';
$Area = 'FUTURUS';
$Foraneo = '';


//verificacion de existencia del usuario en tabla CREDENCIAL por identificacion
$ConsultaSQL = "SELECT CRE_CDOCUMENTO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE CRE_CDOCUMENTO = '" . $Identificacion . "' AND CRE_CESTADO = 'Activo'";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) { 
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        $php_response = array("msg" => "El documento ya existe");
        echo json_encode($php_response);
        mysqli_close($ConexionSQL);
        exit;
    }else {
        $ConsultaSQL = "SELECT CRE_CUSUARIO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE CRE_CUSUARIO = '" . $Usuario . "' AND CRE_CESTADO = 'Activo'";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) { 
            $CantidadResultados = $ResultadoSQL->num_rows;
        
            if($CantidadResultados > 0) {
                $php_response = array("msg" => "El usuario ya existe");
                echo json_encode($php_response);
                mysqli_close($ConexionSQL);
                exit;
            }else { 
                //1. Si no existe definitivamente se inserta a CREDENCIALES
                $datos3 = 'Registro de usuario por: ' . $AutorizacionRegistro;
                $ConsultaSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RCREDENCIAL (CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, CRE_CIDAGENTE, CRE_CDOCUMENTO, CRE_CDETALLE_REGISTRO, CRE_CESTADO) VALUES ('" . $Usuario . "','" . $Nombre . "','" . $Nombre2 . "','" . $Apellido . "','" . $Apellido2 . "','" . $Login . "','" . $Identificacion . "','" . $datos3 . "','" . $datos . "');";
                if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) { 
                    //inserción correcta
                } else {
                    // Error en la Consulta
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    echo $ErrorConsulta;
                    exit;
                }

                //2.Se busca su id para insertarlo (como FK) en PERMISO
                $CodigoCredencial = null;
                $ConsultaSQL = "SELECT PKCRE_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE CRE_CUSUARIO = '" . $Usuario . "' AND CRE_CESTADO = '" . $datos . "' ORDER BY PKCRE_NCODIGO DESC";
                if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                    $CantidadResultados = $ResultadoSQL->num_rows;
                    if ($CantidadResultados > 0) {  //Ya que existe se inserta en PERMISO
                        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                            $CodigoCredencial = $FilaResultado['PKCRE_NCODIGO'];
                            break;
                        }
                        mysqli_free_result($ResultadoSQL);

                        $datos1 = 'SIMPLE';
                        $datos2 = 'LIBRE';
                        $datos3 = 'Registro de usuario por: ' . $AutorizacionRegistro;                   

                        if($NuevoGrupo == '' || $NuevoGrupo == null ) {
                            $ConsultaSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPERMISO (FKPER_NCRE_NCODIGO, PER_CNIVEL, PER_CCONTRASENA, PER_CPERMISO, PER_CCLIENTE, PER_CCAMPANA, PER_CAREA, PER_CGRUPO, PER_CSUBGRUPO, PER_CCARGO, PER_CTIPOLOGUEO, PER_CESTADO_LOGUEO, PER_CDETALLE_REGISTRO, PER_CESTADO) VALUES (" . $CodigoCredencial . ",'" . $Cargo . "','" . $Contrasena . "','" . $TipoPermiso . "','" . $Cliente . "','" . $Campana . "','" . $Area . "','" . $Grupo . "','" . $SubGrupo . "','" . $Cargo . "','" . $datos1 . "','" . $datos2 . "','" . $datos3 . "','" . $datos . "');";
                        }else {
                            $ConsultaSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPERMISO (FKPER_NCRE_NCODIGO, PER_CNIVEL, PER_CCONTRASENA, PER_CPERMISO, PER_CCLIENTE, PER_CCAMPANA, PER_CAREA, PER_CGRUPO, PER_CSUBGRUPO, PER_CCARGO, PER_CTIPOLOGUEO, PER_CESTADO_LOGUEO, PER_CDETALLE_REGISTRO, PER_CESTADO) VALUES (" . $CodigoCredencial . ",'" . $Cargo . "','" . $Contrasena . "','" . $TipoPermiso . "','" . $Cliente . "','" . $Campana . "','" . $Area . "','" . $Grupo . "','" . $NuevoGrupo . "','" . $Cargo . "','" . $datos1 . "','" . $datos2 . "','" . $datos3 . "','" . $datos . "');";
                        }

                        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) { } else {
                            // Error en la Consulta
                            mysqli_close($ConexionSQL);
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            echo $ErrorConsulta;
                            exit;
                        }
                    }
                } else {
                    mysqli_close($ConexionSQL);
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    echo $ErrorConsulta;
                    exit;
                }
                    }
                } else {
                    mysqli_close($ConexionSQL);
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    echo $ErrorConsulta;
                    exit;
                }
            }
        }

$php_response = array("msg" => "Ok");
echo json_encode($php_response);
mysqli_close($ConexionSQL);
exit;
