
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
$LlaveConsulta = $_POST['LlaveConsulta'];
$CodigoEmpresa = $_POST['CodigoEmpresa'];
$ValorEmpresa = $_POST['ValorEmpresa'];
$FondoActual = $_POST['FondoActual'];
$FechaFuturo = $_POST['FechaFuturo'];
$DescripcionSiafpCliente2 = $_POST['DescripcionSiafpCliente2'];
$FechaRegistro = $_POST['FechaRegistro'];
if($FechaRegistro == ""){
    $FechaRegistro =  $Hoy;
}

//Codigo Agente PENAFL_CFONDO_ANTERIOR
$Agente = $_POST['Agente'];
$Registro = "Registrado por: " . $Agente;
$ActualizadoPor = "Actualizado por: " . $Agente;

//Numero del caso
$CodigoCaso = $_POST['CodigoCaso'];

//Estados
$EstadoActivo = 'Activo';
$EstadoInActivo = 'InActivo';
$EstadoPendiente = 'Pendiente';
$EstadoCerrado = "Cerrado";

//Validacion tipo informacion.
if ($SubObservacion == "Cita Agendada") {
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
    $GuardarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION (FKPENAFL_NPKPENCAL_NCODIGO, PENAFL_CFECHA_AGENDAMIENTO, PENAFL_CFONDO_ANTERIOR, PENAFL_CFONDO_NUEVO,  PENAFL_CSALARIO_ACTUAL, PENALF_CDETALLE_REGISTRO, PENALF_CESTADO) VALUES ('" . $LlaveConsulta . "', '" . $FechaRegistro . "', '" . $FondoActual . "', '" . $EstadoCliente . "' ,'" . $SalarioCliente . "', '" . $Registro . "', '" . $EstadoActivo . "');";
    if ($ResultadoSql = $ConexionSQL->query($GuardarSql)) {
        echo "1";
        exit;
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }

    

} else if (($SubObservacion == 'Rellamada') || ($SubObservacion == "Cliente Indeciso")) {

    //Consulta de informacion del caso
    $ConsultaSql = "SELECT FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_CFECHA_EXPEDIENTE, PENCAL_CFECHA_OFRECIMIENTO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = '" . $CodigoCaso . "' AND PENCAL_CESTADO = '" . $EstadoActivo . "' LIMIT 1;";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FKPENCAL_NPKPER_NCODIGO = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                $FKPENCAL_NPKCLI_NCODIGO = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                $PENCAL_CFONDO_NUEVO = $FilaResultado['PENCAL_CFONDO_NUEVO'];
                $PENCAL_CFECHA_EXPEDIENTE = $FilaResultado['PENCAL_CFECHA_EXPEDIENTE'];
                $PENCAL_CFECHA_OFRECIMIENTO = $FilaResultado['PENCAL_CFECHA_OFRECIMIENTO'];
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
        mysqli_close($ConexionSQL);
        echo $CodigoCaso;
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }
} else if (($SubObservacion == "Viable En El Futuro")) {
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
} else {
}


?>