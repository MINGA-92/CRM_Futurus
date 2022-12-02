<?php

require('common.php');
require('funciones_generales.php');


$TipoDocumentoContacto =$_POST['TipoDocumentoContacto'];
$Documento =$_POST['Documento'];
$PrimerNombre =$_POST['PrimerNombre'];
$SegundoNombre =$_POST['SegundoNombre'];
$PrimerApellido =$_POST['PrimerApellido'];
$SegundoApellido =$_POST['SegundoApellido'];
$FechaNacimiento =$_POST['FechaNacimiento'];
$EstadoCivil =$_POST['EstadoCivil'];
$Direccion =$_POST['Direccion'];
$PaisNuevoContacto =$_POST['PaisNuevoContacto'];
$DepartamentoNuevoContacto =$_POST['DepartamentoNuevoContacto'];
$CiudadNuevoContacto =$_POST['CiudadNuevoContacto'];
$BarrioNuevoContacto =$_POST['BarrioNuevoContacto'];
$NumeroMovil =$_POST['NumeroMovil'];

$IndicativoFijo =$_POST['IndicativoFijo'];
$NumeroFijo =$_POST['NumeroFijo'];
$ExtencionFijo =$_POST['ExtencionFijo'];
$Cargo =$_POST['Cargo'];
$IngresoLaboral =$_POST['IngresoLaboral'];
$Salario =$_POST['Salario'];
$Fondo =$_POST['Fondo'];
$DescripcionSIAFP =$_POST['DescripcionSIAFP'];
$IbcCliente =$_POST['IbcCliente'];
$Empresa =$_POST['Empresa'];



$Agente = $_POST['Agente'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$RegistradoPor = "Registrado por: " . $Agente;
$ActualizadoPor = "Actualizado por: " . $Agente;

$Estado1 = "Activo";
$Estado2 = "InActivo";



//Se realiza inserción de los datos iniciales de contacto en la tabla rcleinte
$InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RCLIENTE (CLI_CDOCUMENTO, CLI_CTIPO_DOCUMENTO, CLI_CNOMBRE, CLI_CNOMBRE2, CLI_CAPELLIDO, CLI_CAPELLIDO2 , CLI_CDETALLE_REGISTRO, CLI_CESTADO) VALUES ('" . $Documento . "', '" .$TipoDocumentoContacto. "', '". $PrimerNombre . "', '" .$SegundoNombre. "', '" . $PrimerApellido . "', '" .$SegundoApellido. "', '" . $RegistradoPor . "', '" . $Estado1 . "');";
if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)){
    //cONSULTA LLAVE PRIMARIA DEL CLIENTE REGISTRADO
    $ConsultaSql = "SELECT PKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE CLI_CDOCUMENTO = '" .$Documento. "' AND CLI_CTIPO_DOCUMENTO = '" . $TipoDocumentoContacto . "' AND CLI_CESTADO = '" . $Estado1 . "' ;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $PKCLI_NCODIGO = $FilaResultado['PKCLI_NCODIGO'];
            }

            //Se realiza inserción de los detalles de cliente
            //Se inserta 'DireccionDomicilio'
            $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE3, DETCLI_CDETALLE4, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'DireccionDomicilio' , '" . $Direccion . "', '" . $PaisNuevoContacto . "', '" . $CiudadNuevoContacto . "', '" . $DepartamentoNuevoContacto . "', '" . $BarrioNuevoContacto . "', '" . $RegistradoPor . "', '" . $Estado1 . "' );";
            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
            } else {
                mysqli_close($ConexionSQL);
                $Falla = mysqli_error($ConexionSQL);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                exit;
            }
            //SE INSERTA EstadoCivilCliente
            $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'EstadoCivilCliente' , '" . $EstadoCivil . "', '" . $RegistradoPor . "', '" . $Estado1 . "' );";
            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
            } else {
                mysqli_close($ConexionSQL);
                $Falla = mysqli_error($ConexionSQL);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                exit;
            }

            //SE INSERTA FechaNacimientoCliente
            $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'FechaNacimientoCliente', '" . $FechaNacimiento . "', '" . $RegistradoPor . "', '" . $Estado1 . "');";
            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
            } else {
                mysqli_close($ConexionSQL);
                $Falla = mysqli_error($ConexionSQL);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                exit;
            }

             //SE INSERTA CelularCliente
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'CelularCliente', '" . $NumeroMovil . "', '" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }
            
             //SE INSERTA TelefonoCliente
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'TelefonoCliente', '" . $NumeroFijo . "',  '" . $ExtencionFijo . "' , '" . $IndicativoFijo . "' ,'" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

             //SE INSERTA CargoLaboralCliente
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'CargoLaboralCliente', '" . $Cargo . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

             //SE INSERTA SALARIO DEL CLIENTE
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'SalarioCliente' , '" . $Salario . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

             //SE INSERTA FechaIngresoLaboralCliente
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'FechaIngresoLaboralCliente', '" . $IngresoLaboral . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

             //SE INSERTA FondoPensionCliente
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'FondoPensionCliente', '" . $Fondo . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }
             //SE INSERTA dESCRIPCIÓIN SIAFP
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'DescripcionSiafpCliente', '" . $DescripcionSIAFP . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }
             //SE INSERTA IBC DEL CLIENTE
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'IBCCliente', '" . $IbcCliente . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

             //SE INSERTA EmpresaCliente
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE , DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'EmpresaCliente', '" . $Empresa . "','" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

             //SE INSERTA El Caso Nuevo
             $InsercionSQL ="INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', NOW(), '" . $RegistradoPor . "', '" . $Estado1 . "');";
             if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
             } else {
                 mysqli_close($ConexionSQL);
                 $Falla = mysqli_error($ConexionSQL);
                 $php_response = array("msg" => "Error", "Falla" => $Falla);
                 exit;
             }

        }else {
            mysqli_close($ConexionSsQL);
            $Falla = mysqli_error($ConexionSQL);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            exit;
        }
    }else {
        mysqli_close($ConexionSsQL);
        $Falla = mysqli_error($ConexionSQL);
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        exit;
    }

}else{
    //Errorn en la sentencia sql
     mysqli_close($ConexionSQL);
            $Falla = mysqli_error($ConexionSQL);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
}
$php_response = array("msg" => "Ok");
mysqli_close($ConexionSQL);
echo json_encode($php_response);
exit;



