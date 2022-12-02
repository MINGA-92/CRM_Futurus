
<?php

require('common.php');
require('funciones_generales.php');
session_start();

$Documento = $_POST['DocumentoL']; 
$Agente = $_POST['Agente'];
$CodigoCaso = $_POST['CodigoCaso'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$CantidadAdjuntos = $_POST['CantidadAdjuntos'];
$ConfirmacionSalarioL = $_POST['ConfirmacionSalarioL'];
$Incentivo = $_POST['IncentivoL'];
$EstadoAtencionL = $_POST['SubObservacionL'];
$DetalleAtencionL = $_POST['DetalleAtencionL'];
$FechaGestionL = $_POST['FechaGestionL'];
$FechaReagendamiento = $_POST['FechaReagendamientoL'];
$NotasAdicionalesL = $_POST['NotasAdicionalesL'];


$EstadoActivo = 'Activo';
$EstadoInActivo = 'InActivo';
$EstadoPendiente = 'Pendiente';
$EstadoCerrado = "Cerrado";
$ActualizadoPor = "Actualizado Por: " . $Agente;
$RegistradoPor = "Registrado Por: " . $Agente;

$InputDetalleAdjunto ="";
$Textos_De_Adjuntos = "";
for ($i = 1; $i <= $CantidadAdjuntos; $i++) {
    $InputDetalleAdjunto="";
    $InputDetalleAdjunto = $_POST['InputDetalleAdjunto' . $i];
    $Textos_De_Adjuntos = $Textos_De_Adjuntos . $InputDetalleAdjunto . ',';
    //Verificador de adjuntos
   
    if (($InputDetalleAdjunto == "")||($InputDetalleAdjunto == 'undefined')) {
        //Realizar actualizacion  sin adjuntos

        if (($EstadoAtencionL == "Legalizacion Exitosa") || ($DetalleAtencionL == "Envío Afiliación")) {
            $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '". $ConfirmacionSalarioL ."', PENAFL_CCOMISION_CALCULADA= '". $Incentivo ."', PENAFL_CESTADO_FINAL_LEGALIZACION= '". $EstadoAtencionL ."', PENAFL_CESTADO_FINAL2_LEGALIZACION= '". $DetalleAtencionL ."', PENAFL_CFECHA_LEGALIZACION= '". $FechaGestionL ."', PENAFL_COBSERVACIONES_LEGALIZACION= '". $NotasAdicionalesL ."', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
            if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
            } else {
                mysqli_close($ConexionSQL);
                echo '<script>alert("Error en la actualizacion!");</script>';
                echo '<script>window.location="Legalizacion.php";</script>';
            }
            mysqli_close($ConexionSQL);
            echo '<script>alert("¡Gestion Realizada Exitosamente!");</script>';
            echo '<script>window.location="Legalizacion.php";</script>';
            exit;
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
                        

                        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'ArchivosAdjuntos', '" . $InputDetalleAdjunto . "', '" . $directoriodescarga . "', '" . $archivobase . "', '" . $RegistradoPor . "', 'Activo')";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                        } else {
                            mysqli_close($ConexionSQL);
                            echo '<script>alert("Error en insertar la informacion #1!");</script>';
                            echo '<script>window.location="Legalizacion.php";</script>';
                        }

                    } else {
                        mysqli_close($ConexionSQL);
                        echo '<script>alert("Error al guardar el archivo!");</script>';
                        echo '<script>window.location="Legalizacion.php";</script>';
                    }
                    
                } else {
                    $directoriodescarga2 = $directoriodescarga . $archivobase;
                    if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $directoriodescarga2)) {
                        //Insercion de el registro del archivo en bd.
                        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'ArchivosAdjuntos', '" . $InputDetalleAdjunto . "', '" . $directoriodescarga . "', '" . $archivobase . "', '" . $RegistradoPor . "', 'Activo')";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                        } else {
                            mysqli_close($ConexionSQL);
                            echo '<script>alert("Error en insertar la informacion #2!");</script>';
                            echo '<script>window.location="Legalizacion.php";</script>';
                        }
                    } else {
                        mysqli_close($ConexionSQL);
                        echo '<script>alert("Error al guardar el archivo! #3");</script>';
                        echo '<script>window.location="Legalizacion.php";</script>';
                    }
                }
            
            
        } else {
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error al guardar el archivo!#4");</script>';
            echo '<script>window.location="Legalizacion.php";</script>';
        }
    }


}
if (($EstadoAtencionL == "Legalizacion Exitosa") || ($DetalleAtencionL == "Envío Afiliación")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '". $ConfirmacionSalarioL ."', PENAFL_CCOMISION_CALCULADA= '". $Incentivo ."', PENAFL_CESTADO_FINAL_LEGALIZACION= '". $EstadoAtencionL ."', PENAFL_CESTADO_FINAL2_LEGALIZACION= '". $DetalleAtencionL ."', PENAFL_CFECHA_LEGALIZACION= '". $FechaGestionL ."', PENAFL_COBSERVACIONES_LEGALIZACION= '". $NotasAdicionalesL ."', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        //exito
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="Legalizacion.php";</script>';
    }
    mysqli_close($ConexionSQL);
    echo '<script>alert("¡Caso Legalizado Exitosamente!");</script>';
    echo '<script>window.location="Legalizacion.php";</script>';
    exit;

} if (($EstadoAtencionL == "Pendiente") || ($DetalleAtencionL == "Documentos Pendientes")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '". $ConfirmacionSalarioL ."', PENAFL_CCOMISION_CALCULADA= '". $Incentivo ."', PENAFL_CESTADO_FINAL_LEGALIZACION= '". $EstadoAtencionL ."', PENAFL_CESTADO_FINAL2_LEGALIZACION= '". $DetalleAtencionL ."', PENAFL_CFECHA_LEGALIZACION= '". $FechaGestionL ."', PENAFL_COBSERVACIONES_LEGALIZACION= '". $NotasAdicionalesL ."', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!");</script>';
        echo '<script>window.location="Legalizacion.php";</script>';
    }
    mysqli_close($ConexionSQL);
    echo '<script>alert("¡Caso Legalizado Exitosamente!");</script>';
    echo '<script>window.location="Legalizacion.php";</script>';
    exit;
} 
mysqli_close($ConexionSQL);
echo '<script>alert("¡Gestion realizada Exitosamente!");</script>';
echo '<script>window.location="Legalizacion.php";</script>';
exit;
?>

