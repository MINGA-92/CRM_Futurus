<?php

require('common.php');
require('funciones_generales.php');
session_start();


date_default_timezone_set("America/Bogota");
$Fecha = date("Y-m-d H:i:s");

$Documento = $_POST['DocumentoL'];
$Agente = $_POST['Agente'];
$CodigoCaso = $_POST['CodigoCaso'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$CantidadAdjuntos = $_POST['CantidadAdjuntos'];
$EstadoAtencionL = $_POST['SubObservacionL'];
$DetalleAtencionL = $_POST['DetalleAtencionL'];
$NotasAdicionalesL = $_POST['NotasAdicionalesL'];


$EstadoActivo = 'Activo';
$EstadoInActivo = 'InActivo';
$EstadoPendiente = 'Pendiente';
$EstadoCerrado = "Cerrado";
$ActualizadoPor = "Actualizado Por: " . $Agente;
$RegistradoPor = "Registrado Por: " . $Agente;

$InputDetalleAdjunto = "";
$Textos_De_Adjuntos = "";

for ($i = 1; $i <= $CantidadAdjuntos; $i++) {
    $InputDetalleAdjunto = "";
    $InputDetalleAdjunto = $_POST['InputDetalleAdjunto' . $i];
    $Textos_De_Adjuntos = $Textos_De_Adjuntos . $InputDetalleAdjunto . ',';
    if (($InputDetalleAdjunto == "") || ($InputDetalleAdjunto == 'undefined')) {
        //Realizar actualizacion  sin adjuntos

        if (($EstadoAtencionL == "Legalizacion Exitosa") || ($DetalleAtencionL == "Afiliacion Totalmente Exitosa")) {
            $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET  PENAFL_CESTADO_FINAL= '" . $EstadoAtencionL . "', PENAFL_CESTADO_FINAL2= '" . $DetalleAtencionL . "', PENAFL_CFECHA_CONFIRMACION= '" . $Fecha . "', PENAFL_COBSERVACIONES= '" . $NotasAdicionalesL . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
            if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
                $ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA= 'Retroactividad' AND EST_CESTADO = 'Activo';";
                if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                    $CantidadResultados = $ResultadoSQL->num_rows;
                    if ($CantidadResultados > 0) {
                        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                            $EST_CDETALLE = $FilaResultado['EST_CDETALLE'];
                        }
                        $FechaFuturus = date("Y-m-d H:i:s", strtotime($Fecha . "+" . $EST_CDETALLE . "days"));
                        $InsertarSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL(FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES('" . $CodigoCaso . "', '" . $FechaFuturus . "', '" . $ActualizadoPor . "', '" . $EstadoActivo . "');";
                        if ($ResultadoSQL = $ConexionSQL->query($InsertarSQL)) {
                        } else {
                            mysqli_close($ConexionSQL);
                            echo '<script>alert("Error en la actualizacion!#1");</script>';
                            echo '<script>window.location="Legalizacion.php";</script>';
                        }
                    } else {
                        $EST_CDETALLE = "";
                    }
                } else {
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error en la actualizacion!#1");</script>';
                    echo '<script>window.location="Legalizacion.php";</script>';
                }
            } else {
                mysqli_close($ConexionSQL);
                echo '<script>alert("Error en la actualizacion!!#1.1");</script>';
                echo '<script>window.location="Legalizacion.php";</script>';
            }
            mysqli_close($ConexionSQL);
            echo '<script>alert("¡Gestion Realizada Exitosamente!");</script>';
            echo '<script>window.location="Legalizacion.php";</script>';
            exit;
        }
    } else {
        //Verificador de adjuntos
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
                        echo '<script>window.location="LegalizacionFinal.php";</script>';
                    }
                } else {
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error al guardar el archivo!");</script>';
                    echo '<script>window.location="LegalizacionFinal.php";</script>';
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
                        echo '<script>window.location="LegalizacionFinal.php";</script>';
                    }
                } else {
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error al guardar el archivo! #3");</script>';
                    echo '<script>window.location="LegalizacionFinal.php";</script>';
                }
            }
        } else {
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error al guardar el archivo!#4");</script>';
            echo '<script>window.location="LegalizacionFinal.php";</script>';
        }
    }
}


if (($EstadoAtencionL == "Legalizacion Exitosa") || ($DetalleAtencionL == "Afiliacion Totalmente Exitosa")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET  PENAFL_CESTADO_FINAL = '" . $EstadoAtencionL . "', PENAFL_CESTADO_FINAL2 = '" . $DetalleAtencionL . "', PENAFL_CFECHA_CONFIRMACION = '" . $Fecha . "', PENAFL_COBSERVACIONES = '" . $NotasAdicionalesL . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND PENAFL_CESTADO_FINAL2_LEGALIZACION = 'Envío Afiliación' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        $ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA= 'Retroactividad' AND EST_CESTADO = 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $EST_CDETALLE = $FilaResultado['EST_CDETALLE'];
                }
                $FechaFuturus = date("Y-m-d H:i:s", strtotime($Fecha . "+" . $EST_CDETALLE . "days"));
                $InsertarSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL(FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES('" . $CodigoCaso . "', '" . $FechaFuturus . "', '" . $ActualizadoPor . "', '" . $EstadoActivo . "');";
                if ($ResultadoSQL = $ConexionSQL->query($InsertarSQL)) {
                } else {
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error en la actualizacion!#1");</script>';
                    echo '<script>window.location="LegalizacionFinal.php";</script>';
                }
            } else {
                $EST_CDETALLE = "";
            }
        } else {
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error en la actualizacion!#1");</script>';
            echo '<script>window.location="LegalizacionFinal.php";</script>';
        }
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!#1");</script>';
        echo '<script>window.location="LegalizacionFinal.php";</script>';
    }
    mysqli_close($ConexionSQL);
    echo '<script>alert("¡Caso Legalizado Exitosamente!");</script>';
    echo '<script>window.location="LegalizacionFinal.php";</script>';
    exit;
} else if (($EstadoAtencionL == "Legalizacion Exitosa") || ($DetalleAtencionL == "Desestimiento")) {
    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET  PENAFL_CESTADO_FINAL = '" . $EstadoAtencionL . "', PENAFL_CESTADO_FINAL2 = '" . $DetalleAtencionL . "', PENAFL_CFECHA_CONFIRMACION = '" . $Fecha . "', PENAFL_COBSERVACIONES = '" . $NotasAdicionalesL . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND PENAFL_CESTADO_FINAL2_LEGALIZACION = 'Envío Afiliación' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
        $ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA= 'Retroactividad' AND EST_CESTADO = 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $EST_CDETALLE = $FilaResultado['EST_CDETALLE'];
                }
                $FechaFuturus = date("Y-m-d H:i:s", strtotime($Fecha . "+" . $EST_CDETALLE . "days"));
                $InsertarSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES('" . $CodigoCaso . "', '" . $FechaFuturus . "', '" . $ActualizadoPor . "', '" . $EstadoActivo . "');";
                if ($ResultadoSQL = $ConexionSQL->query($InsertarSQL)) {
                } else {
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error en la actualizacion!#1");</script>';
                    echo '<script>window.location="LegalizacionFinal.php";</script>';
                }
            } else {
                $EST_CDETALLE = "";
            }
        } else {
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error en la actualizacion!#1");</script>';
            echo '<script>window.location="LegalizacionFinal.php";</script>';
        }
    } else {
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error en la actualizacion!#2");</script>';
        echo '<script>window.location="LegalizacionFinal.php";</script>';
    }
    mysqli_close($ConexionSQL);
    echo '<script>alert("¡Caso Legalizado Exitosamente!");</script>';
    echo '<script>window.location="LegalizacionFinal.php";</script>';
    exit;
}
mysqli_close($ConexionSQL);
echo '<script>alert("¡Gestion realizada Exitosamente!");</script>';
echo '<script>window.location="LegalizacionFinal.php";</script>';
exit;
