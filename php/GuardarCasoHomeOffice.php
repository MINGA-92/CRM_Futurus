
<?php

//Conexion a Bd y Funciones generales
require('common.php');
require('funciones_generales.php');
session_start();

date_default_timezone_set("America/Bogota");               
$Fecha=  date ("Y-m-d");
$Hoy= $Fecha .' '. '00:00:00';

//Captura de Informacion del caso
$EstadoAtencion = $_POST['EstadoAtencion'];
$SubObservacion = $_POST['SubObservacion'];
$EstadoCliente = $_POST['EstadoCliente'];
$NotasAdicionales = $_POST['NotasAdicionales'];
$FechaRegistro = $_POST['FechaRegistro'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$CodigoEmpresa = $_POST['CodigoEmpresa'];
$ValorEmpresa = $_POST['ValorEmpresa'];
$FondoActual = $_POST['FondoActual'];
$FechaFuturo = $_POST['FechaFuturo'];
$DescripcionSiafpCliente2 = $_POST['DescripcionSiafpCliente2'];
$ConfirmacionSalario = $_POST['ConfirmacionSalario'];
$BonoCliente =  $_POST['BonoCliente'];
$DetalleBono =  $_POST['DetalleBono'];
$ValorBono =  $_POST['ValorBono'];
$Compromiso = $_POST['Compromiso'];
$PENCAL_CFECHA_EXPEDIENTE = $_POST['FechaALlamar'];
if($FechaRegistro == ""){
    $FechaRegistro = $Hoy;
}else{
    $FechaRegistro = $_POST['FechaRegistro'];
}
$FechaGestion = $FechaRegistro;
$DetalleAtencion = $SubObservacion;
$Incentivo = $ValorBono;

//Codigo Agente PENAFL_CFONDO_ANTERIOR
$Agente = $_POST['Agente'];
$Registro = "Registrado por: " . $Agente;
$ActualizadoPor = "Actualizado por: " . $Agente;

//Numero del caso
$CodigoCaso = $_POST['CodigoCaso'];

/*
    echo("Hoy: ");
    echo($Hoy . " => ");
    echo("PENCAL_CFECHA_EXPEDIENTE: ");
    echo($PENCAL_CFECHA_EXPEDIENTE . " => ");
    echo("EstadoAtencion: ");
    echo($EstadoAtencion . " => ");
    echo("SubObservacion: ");
    echo($SubObservacion . " => ");
    echo("EstadoCliente: ");
    echo($EstadoCliente . " => ");
    echo("FechaRegistro: ");
    echo($FechaRegistro . " => ");
    echo("LlaveConsulta: ");
    echo($LlaveConsulta . " => ");
    echo("CodigoEmpresa: ");
    echo($CodigoEmpresa . " => ");
    echo("ValorEmpresa: ");
    echo($ValorEmpresa . " => ");
    echo("FondoActual: ");
    echo($FondoActual . " => ");
    echo("FechaFuturo: ");
    echo($FechaFuturo . " => ");
    echo("DescripcionSiafpCliente2: ");
    echo($DescripcionSiafpCliente2 . " => ");
    echo("Agente: ");
    echo($Agente . " => ");
    echo("CodigoCaso: ");
    echo($CodigoCaso . " => ");
    echo("ConfirmacionSalario: ");
    echo($ConfirmacionSalario . " => ");
    echo("BonoCliente: ");
    echo($BonoCliente . " => ");
    echo("DetalleBono: ");
    echo($DetalleBono . " => ");
    echo("ValorBono: ");
    echo($ValorBono . " => ");
    echo("NotasAdicionales: ");
    echo($NotasAdicionales . " => ");
    echo("Compromiso: ");
    echo($Compromiso . " => ");
    echo("CantidadAdjuntos: ");
    echo($CantidadAdjuntos . " => ");
    echo("InputDetalleAdjunto: ");
    echo($InputDetalleAdjunto . " => ");
*/
    

//Estados
$EstadoActivo = 'Activo';
$EstadoInActivo = 'InActivo';
$EstadoPendiente = 'Pendiente';
$EstadoCerrado = "Cerrado";


//Variables De Adjuntos
$CantidadAdjuntos = " ";
$InputDetalleAdjunto = " ";
//$CantidadAdjuntos = $_POST['CantidadAdjuntos'];
//$InputDetalleAdjunto = $_POST['InputDetalleAdjunto'];
//$CantidadAdjuntos2 = intval($CantidadAdjuntos);

//exit;

$Textos_De_Adjuntos = "";
/*
    for ($i = 1; $i <= $CantidadAdjuntos2; $i++) {
        echo "i: ". $i;
        echo "InputDetalleAdjunto: ". $InputDetalleAdjunto;
        
        $i = strval($i);
        $InputDetalleAdjunto2 = "";
        $InputDetalleAdjunto2 = $InputDetalleAdjunto;
        $InputDetalleAdjunto2 = $_POST['InputDetalleAdjunto' . $i];
        $Textos_De_Adjuntos = $Textos_De_Adjuntos . $InputDetalleAdjunto2 . ',';

        //Verificador de adjuntos 
        if (($InputDetalleAdjunto2 == "")||($InputDetalleAdjunto2 == 'Undefined')) {
            
            //Realizar actualizacion  sin adjuntos
            if (($EstadoAtencion == "Contacto") || ($SubObservacion == "Envio De Afiliacion Exitoso")) {
                $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '". $ConfirmacionSalario ."', PENAFL_CCOMISION_CALCULADA= '". $DetalleBono ."', PENAFL_CESTADO_FINAL_LEGALIZACION= '". $EstadoAtencion ."', PENAFL_CESTADO_FINAL2_LEGALIZACION= '". $SubObservacion ."', PENAFL_CFECHA_LEGALIZACION= '". $FechaRegistro ."', PENAFL_COBSERVACIONES_LEGALIZACION= '". $NotasAdicionales ."', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
                if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
                    echo 1;
                } else {
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error en la actualizacion!");</script>';
                    echo '<script>window.location="Frankenstein.php";</script>';
                }
                mysqli_close($ConexionSQL);
                echo '<script>alert("¡Gestion Realizada Exitosamente!");</script>';
                echo '<script>window.location="Frankenstein.php";</script>';
                exit;
            } 
                
            
        } else {
            $archivo =  "archivo" . $i;
            $extensionarchivo = strtolower(pathinfo($_FILES[$archivo]["name"], PATHINFO_EXTENSION));
            $archivobase =  'ArchivoCliente_' . $i . "_" . $InputDetalleAdjunto2 . '_' . $Documento . '.' . $extensionarchivo;

            if (is_uploaded_file($_FILES[$archivo]['tmp_name'])) {
                
                // Validacion de Directorios de Adjuntos
                $directoriodescarga = "../FTP/" . $Documento . '/';
                if (!file_exists($directoriodescarga)) {

                    mkdir($directoriodescarga, 0777, true);
                    $directoriodescarga2 = $directoriodescarga . $archivobase;
                    //Insertar archivo en la carepeta creada

                    if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $directoriodescarga2)) {  

                        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'ArchivosAdjuntos', '" . $InputDetalleAdjunto2 . "', '" . $directoriodescarga . "', '" . $archivobase . "', '" . $RegistradoPor . "', 'Activo')";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                            echo 1;
                        } else {
                            mysqli_close($ConexionSQL);
                            echo '<script>alert("Error en insertar la informacion #1!");</script>';
                            echo '<script>window.location="Frankenstein.php";</script>';
                        }

                    } else {
                        mysqli_close($ConexionSQL);
                        echo '<script>alert("Error al guardar el archivo!");</script>';
                        echo '<script>window.location="Frankenstein.php";</script>';
                    }
                    
                } else {
                    $directoriodescarga2 = $directoriodescarga . $archivobase;
                    if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $directoriodescarga2)) {
                        //Insercion de el registro del archivo en bd.
                        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'ArchivosAdjuntos', '" . $InputDetalleAdjunto2 . "', '" . $directoriodescarga . "', '" . $archivobase . "', '" . $RegistradoPor . "', 'Activo')";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                        } else {
                            mysqli_close($ConexionSQL);
                            echo '<script>alert("Error en insertar la informacion #2!");</script>';
                            echo '<script>window.location="Frankenstein.php";</script>';
                        }
                    } else {
                        mysqli_close($ConexionSQL);
                        echo '<script>alert("Error al guardar el archivo! #3");</script>';
                        echo '<script>window.location="Frankenstein.php";</script>';
                    }
                } 
                
            } else {
                mysqli_close($ConexionSQL);
                echo '<script>alert("Error al guardar el archivo!#4");</script>';
                echo '<script>window.location="Frankenstein.php";</script>';
            }
        }

    }
*/



//Validacion tipo informacion.
if ($SubObservacion == "Envio De Afiliacion Exitoso") {
    //Actualizar Caso 
    $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CFONDO_NUEVO = '" . $EstadoCliente . "', PENCAL_CFECHA_COMUNICACION = '" . $FechaRegistro . "', PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";
    if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
        $ActualizarSql2 = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '". $DescripcionSiafpCliente2 ."', DETCLI_CDETALLE_REGISTRO = '". $ActualizadoPor ."' WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA = 'DescripcionSiafpCliente' AND DETCLI_CESTADO = '". $EstadoActivo ."';";
        if ($ResultadoSql = $ConexionSQL->query($ActualizarSql2)) {           
            $ActualizarSql3 = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '". $FondoActual ."', DETCLI_CDETALLE_REGISTRO = '". $ActualizadoPor ."' WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA = 'FondoPensionCliente' AND DETCLI_CESTADO = '". $EstadoActivo ."';";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql3)) {
                
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
                exit;
            }

        } else {
            mysqli_close($ConexionSQL);
            echo $ErrorConsulta = mysqli_error($ConexionSQL);
            exit;
        }

    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
        exit;
    }

    $ConsultaSql = "SELECT FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_COMUNICACION FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $CodigoCliente = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                $PENCAL_CFECHA_COMUNICACION = $FilaResultado['PENCAL_CFECHA_COMUNICACION'];
            }
        } else {
            //Sin Resultados
            $CodigoCliente = "";
            $PENCAL_CFECHA_COMUNICACION = "";
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

    //Consulta Fondo actual
    $ConsultaSql = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LlaveConsulta . "' AND DETCLI_CCONSULTA = 'FondoPensionCliente' AND DETCLI_CESTADO = 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FondoActual = $FilaResultado['DETCLI_CDETALLE'];
            }
        } else {
            //Sin Resultados
            $FondoActual = "";
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

    //Consulta Salario
    $ConsultaSql = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LlaveConsulta . "' AND DETCLI_CCONSULTA = 'SalarioCliente' AND DETCLI_CESTADO = 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $SalarioCliente = $FilaResultado['DETCLI_CDETALLE'];
            }
        } else {
            //Sin Resultados
            $SalarioCliente = "";
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

    //Consulta Valor Empresa
    $ConsultaSql = "SELECT DETEMP_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE DETEMP_CCONSULTA= 'ValorEmpresa' AND FKDETEMP_NCLI_NCODIGO= '". $CodigoEmpresa ."' AND DETEMP_CESTADO= 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            //Si Existe
            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA SET DETEMP_CDETALLE = '". $ValorEmpresa ."', DETEMP_CDETALLE_REGISTRO= '". $ActualizadoPor ."' WHERE FKDETEMP_NCLI_NCODIGO = '". $CodigoEmpresa ."' AND DETEMP_CCONSULTA= 'ValorEmpresa' AND DETEMP_CESTADO = 'Activo';";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
                
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }

        } else {
            //Si No Existe
            $GuardarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('". $CodigoEmpresa ."', 'ValorEmpresa', '". $ValorEmpresa ."', '". $Registro ."', 'Activo');";
            if ($ResultadoSql = $ConexionSQL->query($GuardarSql)) {
                
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
            
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
        exit;
    }
    
    
    //Consulta Fondo Actual
    $ConsultaSql = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA= 'FondoPensionCliente' AND FKDETCLI_NCLI_NCODIGO= '". $LlaveConsulta ."' AND DETCLI_CESTADO= 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            //Si Existe
            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '". $FondoActual ."', DETCLI_CDETALLE_REGISTRO= '". $ActualizadoPor ."' WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO = 'Activo';";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
                
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }

        } else {
            //Si No Existe
            $GuardarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('". $LlaveConsulta ."', 'FondoPensionCliente', '". $FondoActual ."', '". $Registro ."', 'Activo');";
            if ($ResultadoSql = $ConexionSQL->query($GuardarSql)) {
                
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
            
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
        exit;
    }
    

    //Guardar informacion
    $GuardarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION (FKPENAFL_NPKPENCAL_NCODIGO, FKPENAFL_NPKPER_NCODIGO, FKPENAFL_NPKPER_NCODIGO_LEGALIZADOR, PENAFL_CFECHA_AGENDAMIENTO, PENAFL_CFONDO_ANTERIOR, PENAFL_CFONDO_NUEVO, PENAFL_CSALARIO_ACTUAL, PENAFL_CSALARIO_CONFIRMADO, PENAFL_CBONO_AFILIACION, PENAFL_COBSERVACIONES_AGENDA, PENAFL_CESTADO_FINAL_AGENDA, PENAFL_CESTADO_FINAL2_AGENDA, PENAFL_CADJUNTOS, PENAFL_CCOMISION_CALCULADA, PENAFL_CDETALLE_BONO_AFILIACION, PENALF_CCOMPROMISO, PENALF_CDETALLE_REGISTRO, PENALF_CESTADO) VALUES ('" . $LlaveConsulta . "', '". $Agente ."', '". $Agente ."', '" . $FechaRegistro . "', '" . $FondoActual . "', '" . $EstadoCliente . "' ,'" . $SalarioCliente . "', '" . $ConfirmacionSalario . "', '" . $BonoCliente . "', '" . $NotasAdicionales  . "', '" . $EstadoAtencion  . "', '" . $DetalleAtencion  . "', '" . $Textos_De_Adjuntos . "', '". $Incentivo ."', '" . $DetalleBono . "', '" . $Compromiso  . "', '" . $Registro . "', '" . $EstadoActivo . "');";
    if ($ResultadoSql = $ConexionSQL->query($GuardarSql)) {

        $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET FKPENAFL_NPKPER_NCODIGO_LEGALIZADOR = '". $Agente ."', PENAFL_CSALARIO_CONFIRMADO = '". $ConfirmacionSalario ."', PENAFL_CCOMISION_CALCULADA= '". $Incentivo ."', PENAFL_CESTADO_FINAL_LEGALIZACION= '". $EstadoAtencion ."', PENAFL_CESTADO_FINAL2_LEGALIZACION= '". $DetalleAtencion ."', PENAFL_CFECHA_LEGALIZACION= '". $FechaGestion ."', PENAFL_COBSERVACIONES_LEGALIZACION= '". $NotasAdicionales ."', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO != ' ' AND PENALF_CESTADO='Activo' AND FKPENAFL_NPKPENCAL_NCODIGO= '" . $CodigoCaso . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
            //exito
        } else {
            mysqli_close($ConexionSQL);
        }
        mysqli_close($ConexionSQL);
        echo "1";
        exit;

    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

    

} else if (($SubObservacion == 'Volver a Llamar') || ($SubObservacion == "Cliente Indeciso")) {

    //Consulta de informacion del caso
    $ConsultaSql = "SELECT FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_CFECHA_EXPEDIENTE, PENCAL_CFECHA_OFRECIMIENTO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "' LIMIT 1;";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FKPENCAL_NPKPER_NCODIGO = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                $FKPENCAL_NPKCLI_NCODIGO = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                $PENCAL_CFONDO_NUEVO = $FilaResultado['PENCAL_CFONDO_NUEVO'];
                //$PENCAL_CFECHA_EXPEDIENTE = $FilaResultado['PENCAL_CFECHA_EXPEDIENTE'];
                $PENCAL_CFECHA_OFRECIMIENTO = $FilaResultado['PENCAL_CFECHA_OFRECIMIENTO'];
                if (($PENCAL_CFECHA_OFRECIMIENTO == "") || ($PENCAL_CFECHA_OFRECIMIENTO == NULL)){
                    $PENCAL_CFECHA_OFRECIMIENTO = $PENCAL_CFECHA_EXPEDIENTE;
                } else{
                    $PENCAL_CFECHA_OFRECIMIENTO = $Hoy;
                }
                break;
            }

            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CFONDO_NUEVO = '" . $EstadoCliente . "', PENCAL_CFECHA_COMUNICACION = '" . $FechaRegistro . "', PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "', PENCAL_CESTADO = '" . $EstadoInActivo . "' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {

                //Insertar nuevo Registro
                $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_COBSERVACIONES, PENCAL_CESTADO_FINAL, PENCAL_CESTADO_FINAL2, PENCAL_CFECHA_COMUNICACION, PENCAL_CFECHA_EXPEDIENTE, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES ('" . $FKPENCAL_NPKPER_NCODIGO . "', '" . $FKPENCAL_NPKCLI_NCODIGO . "', '" . $EstadoCliente . "', '" . $NotasAdicionales . "','" . $EstadoAtencion . "', '" . $SubObservacion . "', '" . $FechaRegistro . "', '" . $PENCAL_CFECHA_EXPEDIENTE . "', '" . $PENCAL_CFECHA_OFRECIMIENTO . "', '" . $Registro . "', '" . $EstadoActivo . "')";
                if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                    echo 1;
                    //echo "1";
                } else {
                    echo $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    
                }
            } else {
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
                mysqli_close($ConexionSQL);
            }
        } else {
            //No hay resultados
            mysqli_close($ConexionSQL);
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

} else if (($SubObservacion == 'Cliente Molesto') || ($SubObservacion == "No Le Interesa")){
    
    //Consulta datos
    $ConsultaSql = "SELECT FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_CFECHA_EXPEDIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "' LIMIT 1;";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FKPENCAL_NPKPER_NCODIGO = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                $FKPENCAL_NPKCLI_NCODIGO = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                $PENCAL_CFONDO_NUEVO = $FilaResultado['PENCAL_CFONDO_NUEVO'];
                $PENCAL_CFECHA_EXPEDIENTE = $FilaResultado['PENCAL_CFECHA_EXPEDIENTE'];
                break;
            }

            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET FKPENCAL_NPKPER_NCODIGO = '" . $FKPENCAL_NPKPER_NCODIGO . "', PENCAL_CFONDO_NUEVO = '" . $EstadoCliente . "', PENCAL_CFECHA_COMUNICACION = '" . $FechaRegistro . "',  PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";

            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {

                $ConsultaSql = "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA = 'EstadoCliente' AND DETCLI_CESTADO = '" . $EstadoActivo . "'";
                if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
                    $CantidadResultados = $ResultadoSql->num_rows;

                    if ($CantidadResultados > 0) {
                        while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                            $PKDETCLI_NCODIGO = $FilaResultado['PKDETCLI_NCODIGO'];
                        }
                        $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CESTADO = '" . $EstadoInActivo . "' WHERE PKDETCLI_NCODIGO = '" . $PKDETCLI_NCODIGO . "' AND DETCLI_CESTADO = '" . $EstadoActivo . "'";
                        if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
                        } else {
                            mysqli_close($ConexionSQL);
                            echo $ErrorConsulta = mysqli_error($ConexionSQL);
                        }
                        $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'EstadoCliente', 'ClienteMolesto', '" . $Registro . "', '" . $EstadoActivo . "')";
                        if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                            mysqli_close($ConexionSQL);
                            echo "1";
                            exit;
                        } else {
                            mysqli_close($ConexionSQL);
                            echo $ErrorConsulta = mysqli_error($ConexionSQL);
                        }
                    } else {
                        $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'EstadoCliente', 'ClienteMolesto', '" . $Registro . "', '" . $EstadoActivo . "')";
                        if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                            mysqli_close($ConexionSQL);
                            echo "1";
                            exit;
                        } else {
                            mysqli_close($ConexionSQL);
                            echo $ErrorConsulta = mysqli_error($ConexionSQL);
                        }
                    }
                } else {
                    mysqli_close($ConexionSQL);
                    echo $ErrorConsulta = mysqli_error($ConexionSQL);
                }
            } else {
                //No hay resultados
                mysqli_close($ConexionSQL);
            }
        } else {
            //No hay resultados
            mysqli_close($ConexionSQL);
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }
} else if ($SubObservacion == "No Contesta") {

    //Consulta de informacion del caso
    $ConsultaSql = "SELECT FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_CFECHA_EXPEDIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FKPENCAL_NPKPER_NCODIGO = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                $FKPENCAL_NPKCLI_NCODIGO = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                $PENCAL_CFONDO_NUEVO = $FilaResultado['PENCAL_CFONDO_NUEVO'];
                $PENCAL_CFECHA_EXPEDIENTE = $FilaResultado['PENCAL_CFECHA_EXPEDIENTE'];
            }

            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CFONDO_NUEVO = '" . $EstadoCliente . "', PENCAL_CFECHA_COMUNICACION = '" . $FechaRegistro . "', PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "', PENCAL_CESTADO = '". $EstadoInActivo . "' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {

                //Insertar nuevo Registro
                $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_COBSERVACIONES, PENCAL_CESTADO_FINAL, PENCAL_CESTADO_FINAL2, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES ('". $Agente ."', '" . $LlaveConsulta . "', '" . $EstadoCliente . "', '". $NotasAdicionales ."', '" . $EstadoAtencion . "', '" . $SubObservacion . "', '" .  $Hoy . "', '" . $Registro . "', '" . $EstadoActivo . "')";
                if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                    echo "1";
                } else {
                    mysqli_close($ConexionSQL);
                    echo $ErrorConsulta = mysqli_error($ConexionSQL);
                }

            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }

            
        } else {
            //Sin Resultados
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

} else if (($SubObservacion == "No Aplica") || ($SubObservacion == "Numero Errado") || ($SubObservacion == 'No Contacto')) {
    $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CFONDO_NUEVO = '" . $EstadoCliente . "', PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";
    if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
        echo "1";
    } else {
        echo $CodigoCaso;
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
    }
} else if (($SubObservacion == "Viable En El Futuro") || ($SubObservacion == "Mas Adelante")) {
    $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "', PENCAL_CESTADO = '". $EstadoInActivo ."' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "';";
    if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {

        $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_COBSERVACIONES, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES ('" . $LlaveConsulta . "', '". $EstadoCliente ."', '" . $NotasAdicionales . "', '". $FechaFuturo ."', '" . $Registro . "', '" . $EstadoActivo . "');";
        if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
            mysqli_close($ConexionSQL);
            echo 1;
        } else {
            mysqli_close($ConexionSQL);
            echo $ErrorConsulta = mysqli_error($ConexionSQL);
        }

    } else {
        mysqli_close($ConexionSQL);
        echo $CodigoCaso;
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }
} else if ($SubObservacion == "Desistimiento o Retracto") {
    $Textos_De_Adjuntos = "N/A";
    $Compromiso = "N/A";
    //Tabla Call
    $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CFONDO_NUEVO = '" . $EstadoCliente . "', PENCAL_CESTADO_FINAL = '" . $EstadoAtencion . "', PENCAL_CESTADO_FINAL2 = '" . $SubObservacion . "', PENCAL_COBSERVACIONES = '" . $NotasAdicionales . "', PENCAL_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "'";
    if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {

        //Tabla Afiliaciones/Guardar informacion
        $GuardarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION (FKPENAFL_NPKPENCAL_NCODIGO, PENAFL_CFECHA_AGENDAMIENTO, PENAFL_CFONDO_ANTERIOR, PENAFL_CFONDO_NUEVO,  PENAFL_CSALARIO_ACTUAL, PENALF_CDETALLE_REGISTRO, PENALF_CESTADO) VALUES ('" . $LlaveConsulta . "', '" . $Hoy . "', '" . $FondoActual . "', '" . $EstadoCliente . "' ,'" . $ConfirmacionSalario . "', '" . $Registro . "', '" . $EstadoActivo . "');";
        if ($ResultadoSql = $ConexionSQL->query($GuardarSql)) {
            $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENAFL_CSALARIO_CONFIRMADO = '" . $ConfirmacionSalario . "', PENAFL_CBONO_AFILIACION = '" . $BonoCliente . "', PENAFL_COBSERVACIONES_AGENDA = '" . $NotasAdicionales  . "', PENAFL_CESTADO_FINAL_AGENDA = '" . $EstadoAtencion  . "', PENAFL_CESTADO_FINAL2_AGENDA = '" . $DetalleAtencion  . "', PENAFL_CADJUNTOS = '" . $Textos_De_Adjuntos . "', PENAFL_CDETALLE_BONO_AFILIACION = '" . $DetalleBono . "', PENALF_CCOMPROMISO = '" . $Compromiso  . "', PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKPENAFL_NCODIGO = '" . $CodigoCaso . "';";
            if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {
                echo "1";
                exit;  

            } else {
                echo "0";
                mysqli_close($ConexionSQL);
                echo '<script>alert(".l.  Error en la actualizacion!");</script>';
                echo '<script>window.location="Frankenstein.php";</script>';
            }
        } else {
            echo "0";
            mysqli_close($ConexionSQL);
            echo $ErrorConsulta = mysqli_error($ConexionSQL);
        }

    } else {
        mysqli_close($ConexionSQL);
        echo $CodigoCaso;
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

} else {

}



?>

