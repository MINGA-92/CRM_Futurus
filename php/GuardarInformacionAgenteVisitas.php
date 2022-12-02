
<?php

//Conexion a Bd y Funciones generales
require('common.php');
require('funciones_generales.php');
session_start();

print_r('<div id="Loading" style="margin-left: 40%">
<img src="../images/loading.gif">
</div>');

//Captura de Informacion del caso
$Documento = $_POST['DocumentoH'];

$CompromisoH = $_POST['CompromisoH'];
$ConfirmacionSalarioH = $_POST['ConfirmacionSalarioH'];
$BonoClienteH = $_POST['BonoClienteH'];
$DetalleBonoH = $_POST['DetalleBonoH'];
$EstadoAtencionH = $_POST['EstadoAtencionH'];
$DetalleAtencionH = $_POST['DetalleAtencionH'];
$NotasAdicionalesH = $_POST['NotasAdicionalesH'];
$AgenteH = $_POST['AgenteH'];
$CodigoCasoH = $_POST['CodigoCasoH'];
$LlaveConsultaH = $_POST['LlaveConsultaH'];
$FechaGestionH = $_POST['FechaGestionH'];
$CantidadAdjuntos = $_POST['CantidadAdjuntos'];



$Agente = $_POST['Agente'];
$CodigoCaso = $_POST['CodigoCaso'];
$CodigoCasoVisita = $_POST['CodigoCasoVisita'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$CodigoEmpresa = $_POST['CodigoEmpresa'];

$EstadoActivo = 'Activo';
$EstadoInActivo = 'InActivo';
$EstadoPendiente = 'Pendiente';
$EstadoCerrado = "Cerrado";
$ActualizadoPor = "Actualizado Por: " . $Agente;
$RegistradoPor = "Registrado Por: " . $Agente;


$Textos_De_Adjuntos = "";
for ($i = 1; $i <= $CantidadAdjuntos; $i++) {
    $InputDetalleAdjunto = $_POST['InputDetalleAdjunto' . $i];
    $Textos_De_Adjuntos = $Textos_De_Adjuntos . $InputDetalleAdjunto . ',';
    //Verificador de adjuntos
    if ($InputDetalleAdjunto == "") {
        //Realizar actualizacion  sin adjuntos

        if (($EstadoAtencionH == "Agenda Exitosa") || ($DetalleAtencionH == "Documentos Pendientes")) {
            $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalarioH . "', PENAFL_CBONO_AFILIACION = '" . $BonoClienteH . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionalesH  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencionH  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencionH  . "', PENAFL_CADJUNTOS = 'SinAdjuntos', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBonoH . "', PENALF_CCOMPROMISO = '" . $CompromisoH  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCasoVisita . "';";
            if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
                mysqli_close($ConexionSQL);
                echo '<script>alert("¡Caso Gestionado Exitosamente!");</script>';
                echo '<script>window.location="AgenteVisitas.php";</script>';
            } else {
                mysqli_close($ConexionSQL);
                echo '<script>alert("Error en la actualizacion!");</script>';
                echo '<script>window.location="AgenteVisitas.php";</script>';
            }
        } else if ($DetalleAtencionH == "Retracto") {
            
        }
    } else {

        $archivo =  "archivo" . $i;
        $extensionarchivo = strtolower(pathinfo($_FILES[$archivo]["name"], PATHINFO_EXTENSION));
        $archivobase =  'ArchivoCliente_' . $i . "_" . $InputDetalleAdjunto . '_' . $Documento . '.' . $extensionarchivo;

        if (is_uploaded_file($_FILES[$archivo]['tmp_name'])) {
            
                // Validacion de Directorios de Adjuntos
                $directoriodescarga = "../FTP/" . $Documento . '/';
                if (!file_exists($directoriodescarga)) {

                    mkdir($directoriodescarga, 0777, true);
                    $directoriodescarga2 = $directoriodescarga . $archivobase;
                    //Insertar archivo en la carepeta creada
 
                    if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $directoriodescarga2)) {
                        

                        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsultaH . "', 'ArchivosAdjuntos', '" . $InputDetalleAdjunto . "', '" . $directoriodescarga . "', '" . $archivobase . "', '" . $RegistradoPor . "', 'Activo')";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                        } else {
                            echo '<script>alert("Error en insertar la informacion #1!");</script>';
                            echo '<script>window.location="AgenteVisitas.php";</script>';
                        }

                    } else {
                        echo '<script>alert("Error al guardar el archivo!");</script>';
                        echo '<script>window.location="AgenteVisitas.php";</script>';
                    }
                    
                } else {
                    $directoriodescarga2 = $directoriodescarga . $archivobase;
                    if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $directoriodescarga2)) {
                        //Insercion de el registro del archivo en bd.
                        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsultaH . "', 'ArchivosAdjuntos', '" . $InputDetalleAdjunto . "', '" . $directoriodescarga . "', '" . $archivobase . "', '" . $RegistradoPor . "', 'Activo')";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                        } else {
                            echo '<script>alert("Error en insertar la informacion #2!");</script>';
                            echo '<script>window.location="AgenteVisitas.php";</script>';
                        }
                    } else {
                        echo '<script>alert("Error al guardar el archivo! #3");</script>';
                        echo '<script>window.location="AgenteVisitas.php";</script>';
                    }
                }
            
            
        } else {
            echo '<script>alert("Error al guardar el archivo!#4");</script>';
            echo '<script>window.location="AgenteVisitas.php";</script>';
        }
    }


}

if (($EstadoAtencionH == "Agenda Exitosa") || ($DetalleAtencionH == "Documentos Pendientes")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalarioH . "', PENAFL_CBONO_AFILIACION = '" . $BonoClienteH . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionalesH  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencionH  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencionH  . "', PENAFL_CADJUNTOS = '" . $Textos_De_Adjuntos . "', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBonoH . "', PENALF_CCOMPROMISO = '" . $CompromisoH  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCasoVisita . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        mysqli_close($ConexionSQL);
        echo '<script>alert("!Caso Gestionado Exitosamente!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    }
} else if (($EstadoAtencionH == "Agenda Exitosa") || ($DetalleAtencionH == "Afiliacion Exitosa")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalarioH . "', PENAFL_CBONO_AFILIACION = '" . $BonoClienteH . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionalesH  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencionH  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencionH  . "', PENAFL_CADJUNTOS = '" . $Textos_De_Adjuntos . "', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBonoH . "', PENALF_CCOMPROMISO = '" . $CompromisoH  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCasoVisita . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        mysqli_close($ConexionSQL);
        echo '<script>alert("!Caso Gestionado Exitosamente!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    }
} else if (($EstadoAtencionH == "Agenda Exitosa") || ($DetalleAtencionH == "Retracto")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalarioH . "', PENAFL_CBONO_AFILIACION = '" . $BonoClienteH . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionalesH  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencionH  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencionH  . "', PENAFL_CADJUNTOS = '" . $Textos_De_Adjuntos . "', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBonoH . "', PENALF_CCOMPROMISO = '" . $CompromisoH  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCasoVisita . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        mysqli_close($ConexionSQL);
        echo '<script>alert("!Caso Gestionado Exitosamente!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    }

} else if (($EstadoAtencionH == "Pendiente") || ($DetalleAtencionH == "Cliente No Atiende")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalarioH . "', PENAFL_CBONO_AFILIACION = '" . $BonoClienteH . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionalesH  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencionH  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencionH  . "', PENAFL_CADJUNTOS = '" . $Textos_De_Adjuntos . "', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBonoH . "', PENALF_CCOMPROMISO = '" . $CompromisoH  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCasoVisita . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        mysqli_close($ConexionSQL);
        echo '<script>alert("!Caso Gestionado Exitosamente!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    }
} else if (($EstadoAtencionH == "Pendiente") || ($DetalleAtencionH == "Reagendamiento")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalarioH . "', PENAFL_CBONO_AFILIACION = '" . $BonoClienteH . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionalesH  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencionH  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencionH  . "', PENAFL_CADJUNTOS = '" . $Textos_De_Adjuntos . "', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBonoH . "', PENALF_CCOMPROMISO = '" . $CompromisoH  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCasoVisita . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        mysqli_close($ConexionSQL);
        echo '<script>alert("!Caso Gestionado Exitosamente!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="AgenteVisitas.php";</script>';
    }
}


