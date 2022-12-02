
<?php

require('common.php');
require('funciones_generales.php');
require('ConsultaNotificaciones.php');
session_start();


if (isset($_SESSION['codigopermiso'])) {
} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}

$codigopermisos = $_SESSION['codigopermiso'];
$codigopermisos = trim($codigopermisos);

date_default_timezone_set("America/Bogota");               
$Fecha=  date ("Y-m-d");
$Hoy= $Fecha .' '. '00:00:00';
$FechaHoy= date("Y-m-d", strtotime($Hoy));



if (isset($_POST['InicioRango'])) {
    $Item = $_POST['Item'];
    $Item2 = $_POST['Item2']; 
    $InicioRango = $_POST['InicioRango']; 
    $FinRango = $_POST['FinRango'];
    $InicioRango2 = $_POST['InicioRango2']; 
    $FinRango2 = $_POST['FinRango2'];

} else {
    $Item = "";
    $Item2 = ""; 
    $InicioRango = ""; 
    $FinRango = "";
    $InicioRango2 = "";
    $FinRango2 = "";
    
}


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



//Consulta USUARIOS para Asiganción de casos
$Datos = array();
$ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CDOCUMENTO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2 FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PER_CNIVEL = 'Agente HomeOffice' AND CRE_CESTADO = 'Activo' AND PER_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CodigoCaso = $FilaResultado['PKPER_NCODIGO'];
            $Documento = $FilaResultado['CRE_CDOCUMENTO'];
            $CRE_CNOMBRE = $FilaResultado['CRE_CNOMBRE'];
            $CRE_CNOMBRE2 = $FilaResultado['CRE_CNOMBRE2'];
            $CRE_CAPELLIDO = $FilaResultado['CRE_CAPELLIDO'];
            $CRE_CAPELLIDO2 = $FilaResultado['CRE_CAPELLIDO2'];
            
            $Usuario = $FilaResultado['CRE_CUSUARIO'];
            
            array_push($Datos, array("0" => $Documento, "1" => $Usuario, "2" => $CRE_CNOMBRE.' '. $CRE_CNOMBRE2.' '. $CRE_CAPELLIDO.' '.$CRE_CAPELLIDO2,"3" => $CodigoCaso, "4" => $CodigoCaso));
        }
    } else {
        //Sin Resultados
    }
} else {
    //Errro en la consulta sql
    echo mysqli_error($ConexionSQL);
}

if (($InicioRango == "") && ($InicioRango2 == "")){

    //Consulta Tabla Casos Pendientes
    $Datos2 = array();
    $ConsultaSQL = "SELECT PKPENCAL_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE, PENCAL_CFECHA_OFRECIMIENTO AS FechaOfrecimiento FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE PKCLI_NCODIGO = FKPENCAL_NPKCLI_NCODIGO AND PENCAL_CESTADO = 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NULL;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
                $CLI_CNOMBRE= $FilaResultado['NOMBRE_CLIENTE'];
                $FechaOfrecimiento = $FilaResultado['FechaOfrecimiento'];
                $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];
                $CodigoCliente = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];

                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'SalarioCliente' AND DETCLI_CESTADO= 'Activo';";
                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                    if ($CantidadResultados2 > 0) {
                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                            $SALARIO = $FilaResultado['DETCLI_CDETALLE'];

                            $ConsultaSQL3 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FechaNacimientoCliente' AND DETCLI_CESTADO= 'Activo';";
                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                if ($CantidadResultados3 > 0) {
                                    while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                        $FECHANACIMIENTO = $FilaResultado['DETCLI_CDETALLE'];

                                        $ConsultaSQL4 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CantidadDeCambios' AND DETCLI_CESTADO= 'Activo';";
                                        if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                                            $CantidadResultados4 = $ResultadoSQL4->num_rows;
                                            if ($CantidadResultados4 > 0) {
                                                while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                                    $CantidadDeCambios = $FilaResultado['DETCLI_CDETALLE'];
                                                    
                                                    $ConsultaSQL5 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
                                                    if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                                        $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                                        if ($CantidadResultados5 > 0) {
                                                            while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                                $FondoPensionCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                
                                                                $ConsultaSQL0 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'GeneroCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                if ($ResultadoSQL0 = $ConexionSQL->query($ConsultaSQL0)) {
                                                                    $CantidadResultados0 = $ResultadoSQL0->num_rows;
                                                                    if ($CantidadResultados0 > 0) {
                                                                        while ($FilaResultado = $ResultadoSQL0->fetch_assoc()) {
                                                                            $GeneroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                            
                                                                            $ConsultaSQL7 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
                                                                            if ($ResultadoSQL7 = $ConexionSQL->query($ConsultaSQL7)) {
                                                                                $CantidadResultados7 = $ResultadoSQL7->num_rows;
                                                                                if ($CantidadResultados7 > 0) {
                                                                                    while ($FilaResultado = $ResultadoSQL7->fetch_assoc()) {
                                                                                        $FondoNuevo = $FilaResultado['DETCLI_CDETALLE'];
                                                                                        
                                                                                        $ConsultaSQL8 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CelularCliente' AND DETCLI_CESTADO= 'Activo' LIMIT 1;";
                                                                                        if ($ResultadoSQL8 = $ConexionSQL->query($ConsultaSQL8)) {
                                                                                            $CantidadResultados8 = $ResultadoSQL8->num_rows;
                                                                                            if ($CantidadResultados8 > 0) {
                                                                                                while ($FilaResultado = $ResultadoSQL8->fetch_assoc()) {
                                                                                                    $NumeroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                    
                                                                                                    //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $PKPENCAL_NCODIGO));

                                                                                                }
                                                                                            } else {
                                                                                                //Sin Resultados
                                                                                                $CantidadDeCambios ="";
                                                                                            }
                                                                                        } else {
                                                                                            //Errro en la consulta sql
                                                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                            mysqli_close($ConexionSQL);
                                                                                            exit;
                                                                                        }


                                                                                    }
                                                                                } else {
                                                                                    //Sin Resultados
                                                                                    $CantidadDeCambios ="";
                                                                                }
                                                                            } else {
                                                                                //Errro en la consulta sql
                                                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                mysqli_close($ConexionSQL);
                                                                                exit;
                                                                            }

                                                                        }
                                                                    } else {
                                                                        //Sin Resultados
                                                                        $CantidadDeCambios ="";
                                                                    }
                                                                } else {
                                                                    //Errro en la consulta sql
                                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                    mysqli_close($ConexionSQL);
                                                                    exit;
                                                                }

                                                            }
                                                        } else {
                                                            //Sin Resultados
                                                            $CantidadDeCambios ="";
                                                        }
                                                    } else {
                                                        //Errro en la consulta sql
                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                        mysqli_close($ConexionSQL);
                                                        exit;
                                                    }

                                                }
                                            } else {
                                                //Sin Resultados
                                                $CantidadDeCambios ="";
                                            }
                                        } else {
                                            //Errro en la consulta sql
                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                            mysqli_close($ConexionSQL);
                                            exit;
                                        }

                                    }
                                } else {
                                    //Sin Resultados
                                    $FECHANACIMIENTO = "";
                                }
                            } else {
                                //Errro en la consulta sql
                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                mysqli_close($ConexionSQL);
                                exit;
                            }

                        }
                    } else {
                        //Sin Resultados
                        $SALARIO = "";
                    }
                } else {
                    //Errro en la consulta sql
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    exit;
                }

                array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $PKPENCAL_NCODIGO));
            }
        } else {
            //Sin Resultados
        }
    } else {
        //Errro en la consulta sql
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        exit;
    }
    
} else {

    if (($Item == "Salario") && ($Item2 == "Filtrar Por..")){

        //Consulta Tabla Detalle Cliente
        $Datos2 = array();
        $ConsultaSQL = "SELECT FKDETCLI_NCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA = 'SalarioCliente' AND (DETCLI_CDETALLE BETWEEN ". $InicioRango ." AND ". $FinRango .") AND DETCLI_CESTADO= 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoCliente = $FilaResultado['FKDETCLI_NCLI_NCODIGO'];
                    $SalarioCliente = $FilaResultado['DETCLI_CDETALLE'];
                    
                    $ConsultaSQL1 = "SELECT PKPENCAL_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE FKPENCAL_NPKCLI_NCODIGO=PKCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO='". $CodigoCliente ."' AND PENCAL_CESTADO = 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NULL;";
                    if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
                        $CantidadResultados1 = $ResultadoSQL1->num_rows;
                        if ($CantidadResultados1 > 0) {
                            while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {
                                $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
                                $CLI_CNOMBRE= $FilaResultado['NOMBRE_CLIENTE'];
                                $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];

                                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'SalarioCliente' AND DETCLI_CESTADO= 'Activo';";
                                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                                    if ($CantidadResultados2 > 0) {
                                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                                            $SALARIO = $FilaResultado['DETCLI_CDETALLE'];

                                            $ConsultaSQL3 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FechaNacimientoCliente' AND DETCLI_CESTADO= 'Activo';";
                                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                                if ($CantidadResultados3 > 0) {
                                                    while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                                        $FECHANACIMIENTO = $FilaResultado['DETCLI_CDETALLE'];

                                                        $ConsultaSQL4 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CantidadDeCambios' AND DETCLI_CESTADO= 'Activo';";
                                                        if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                                                            $CantidadResultados4 = $ResultadoSQL4->num_rows;
                                                            if ($CantidadResultados4 > 0) {
                                                                while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                                                    $CantidadDeCambios = $FilaResultado['DETCLI_CDETALLE'];
                                                                    
                                                                    $ConsultaSQL5 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                    if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                                                        $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                                                        if ($CantidadResultados5 > 0) {
                                                                            while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                                                $FondoPensionCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                
                                                                                $ConsultaSQL0 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'GeneroCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                                if ($ResultadoSQL0 = $ConexionSQL->query($ConsultaSQL0)) {
                                                                                    $CantidadResultados0 = $ResultadoSQL0->num_rows;
                                                                                    if ($CantidadResultados0 > 0) {
                                                                                        while ($FilaResultado = $ResultadoSQL0->fetch_assoc()) {
                                                                                            $GeneroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                            
                                                                                            $ConsultaSQL7 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
                                                                                            if ($ResultadoSQL7 = $ConexionSQL->query($ConsultaSQL7)) {
                                                                                                $CantidadResultados7 = $ResultadoSQL7->num_rows;
                                                                                                if ($CantidadResultados7 > 0) {
                                                                                                    while ($FilaResultado = $ResultadoSQL7->fetch_assoc()) {
                                                                                                        $FondoNuevo = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                        
                                                                                                        $ConsultaSQL8 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CelularCliente' AND DETCLI_CESTADO= 'Activo' LIMIT 1;";
                                                                                                        if ($ResultadoSQL8 = $ConexionSQL->query($ConsultaSQL8)) {
                                                                                                            $CantidadResultados8 = $ResultadoSQL8->num_rows;
                                                                                                            if ($CantidadResultados8 > 0) {
                                                                                                                while ($FilaResultado = $ResultadoSQL8->fetch_assoc()) {
                                                                                                                    $NumeroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                                    
                                                                                                                    array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                                                                                                                }
                                                                                                            } else {
                                                                                                                //Sin Resultados
                                                                                                                $CantidadDeCambios ="";
                                                                                                            }
                                                                                                        } else {
                                                                                                            //Errro en la consulta sql
                                                                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                            mysqli_close($ConexionSQL);
                                                                                                            echo "<script>window.location='logout.php';</script>";
                                                                                                            exit;
                                                                                                        }


                                                                                                    }
                                                                                                } else {
                                                                                                    //Sin Resultados
                                                                                                    $CantidadDeCambios ="";
                                                                                                }
                                                                                            } else {
                                                                                                //Errro en la consulta sql
                                                                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                mysqli_close($ConexionSQL);
                                                                                                echo "<script>window.location='logout.php';</script>";
                                                                                                exit;
                                                                                            }

                                                                                        }
                                                                                    } else {
                                                                                        //Sin Resultados
                                                                                        $CantidadDeCambios ="";
                                                                                    }
                                                                                } else {
                                                                                    //Errro en la consulta sql
                                                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                    mysqli_close($ConexionSQL);
                                                                                    echo "<script>window.location='logout.php';</script>";
                                                                                    exit;
                                                                                }

                                                                            }
                                                                        } else {
                                                                            //Sin Resultados
                                                                            $CantidadDeCambios ="";
                                                                        }
                                                                    } else {
                                                                        //Errro en la consulta sql
                                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                        mysqli_close($ConexionSQL);
                                                                        echo "<script>window.location='logout.php';</script>";
                                                                        exit;
                                                                    }

                                                                }
                                                            } else {
                                                                //Sin Resultados
                                                                $CantidadDeCambios ="";
                                                            }
                                                        } else {
                                                            //Errro en la consulta sql
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo "<script>window.location='logout.php';</script>";
                                                            exit;
                                                        }

                                                    }
                                                } else {
                                                    //Sin Resultados
                                                    $FECHANACIMIENTO = "";
                                                }
                                            } else {
                                                //Errro en la consulta sql
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo "<script>window.location='logout.php';</script>";
                                                exit;
                                            }

                                        }
                                    } else {
                                        //Sin Resultados
                                        $SALARIO = "";
                                    }
                                } else {
                                    //Errro en la consulta sql
                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                    mysqli_close($ConexionSQL);
                                    echo "<script>window.location='logout.php';</script>";
                                    exit;
                                }
                            }
                        } else {
                            //Sin Resultados
                        }
                    } else {
                        //Errro en la consulta sql
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "<script>window.location='logout.php';</script>";
                        exit;
                    }

                    //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                }
            } else {
                //Sin Resultados
                $CodigoCliente ="";
                $SalarioCliente ="";
            }
        } else {
            //Errro en la consulta sql
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo "<script>window.location='logout.php';</script>";
            exit;
        }


    } else if (($Item == "Fecha De Nacimiento") && ($Item2 == "Filtrar Por..")){

        $InicioRangoFecha= date("Y-m-d", strtotime($InicioRango));
        $FinRangoFecha= date("Y-m-d", strtotime($FinRango));
        
        //Consulta Tabla Detalle Cliente
        $Datos2 = array();
        $ConsultaSQL = "SELECT FKDETCLI_NCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA = 'FechaNacimientoCliente' AND (STR_TO_DATE(DETCLI_CDETALLE,'%d-%m-%Y') BETWEEN '". $InicioRangoFecha ."' AND '". $FinRangoFecha ."') AND DETCLI_CESTADO= 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoCliente = $FilaResultado['FKDETCLI_NCLI_NCODIGO'];
                    $SalarioCliente = $FilaResultado['DETCLI_CDETALLE'];
                    
                    $ConsultaSQL1 = "SELECT PKPENCAL_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE FKPENCAL_NPKCLI_NCODIGO=PKCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO='". $CodigoCliente ."' AND PENCAL_CESTADO = 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NULL;";
                    if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
                        $CantidadResultados1 = $ResultadoSQL1->num_rows;
                        if ($CantidadResultados1 > 0) {
                            while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {
                                $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
                                $CLI_CNOMBRE= $FilaResultado['NOMBRE_CLIENTE'];
                                $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];

                                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'SalarioCliente' AND DETCLI_CESTADO= 'Activo';";
                                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                                    if ($CantidadResultados2 > 0) {
                                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                                            $SALARIO = $FilaResultado['DETCLI_CDETALLE'];

                                            $ConsultaSQL3 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FechaNacimientoCliente' AND DETCLI_CESTADO= 'Activo';";
                                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                                if ($CantidadResultados3 > 0) {
                                                    while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                                        $FECHANACIMIENTO = $FilaResultado['DETCLI_CDETALLE'];

                                                        $ConsultaSQL4 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CantidadDeCambios' AND DETCLI_CESTADO= 'Activo';";
                                                        if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                                                            $CantidadResultados4 = $ResultadoSQL4->num_rows;
                                                            if ($CantidadResultados4 > 0) {
                                                                while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                                                    $CantidadDeCambios = $FilaResultado['DETCLI_CDETALLE'];
                                                                    
                                                                    $ConsultaSQL5 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                    if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                                                        $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                                                        if ($CantidadResultados5 > 0) {
                                                                            while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                                                $FondoPensionCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                
                                                                                $ConsultaSQL0 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'GeneroCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                                if ($ResultadoSQL0 = $ConexionSQL->query($ConsultaSQL0)) {
                                                                                    $CantidadResultados0 = $ResultadoSQL0->num_rows;
                                                                                    if ($CantidadResultados0 > 0) {
                                                                                        while ($FilaResultado = $ResultadoSQL0->fetch_assoc()) {
                                                                                            $GeneroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                            
                                                                                            $ConsultaSQL7 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
                                                                                            if ($ResultadoSQL7 = $ConexionSQL->query($ConsultaSQL7)) {
                                                                                                $CantidadResultados7 = $ResultadoSQL7->num_rows;
                                                                                                if ($CantidadResultados7 > 0) {
                                                                                                    while ($FilaResultado = $ResultadoSQL7->fetch_assoc()) {
                                                                                                        $FondoNuevo = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                        
                                                                                                        $ConsultaSQL8 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CelularCliente' AND DETCLI_CESTADO= 'Activo' LIMIT 1;";
                                                                                                        if ($ResultadoSQL8 = $ConexionSQL->query($ConsultaSQL8)) {
                                                                                                            $CantidadResultados8 = $ResultadoSQL8->num_rows;
                                                                                                            if ($CantidadResultados8 > 0) {
                                                                                                                while ($FilaResultado = $ResultadoSQL8->fetch_assoc()) {
                                                                                                                    $NumeroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                                    
                                                                                                                    array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                                                                                                                }
                                                                                                            } else {
                                                                                                                //Sin Resultados
                                                                                                                $CantidadDeCambios ="";
                                                                                                            }
                                                                                                        } else {
                                                                                                            //Errro en la consulta sql
                                                                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                            mysqli_close($ConexionSQL);
                                                                                                            echo "<script>window.location='logout.php';</script>";
                                                                                                            exit;
                                                                                                        }


                                                                                                    }
                                                                                                } else {
                                                                                                    //Sin Resultados
                                                                                                    $CantidadDeCambios ="";
                                                                                                }
                                                                                            } else {
                                                                                                //Errro en la consulta sql
                                                                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                mysqli_close($ConexionSQL);
                                                                                                echo "<script>window.location='logout.php';</script>";
                                                                                                exit;
                                                                                            }

                                                                                        }
                                                                                    } else {
                                                                                        //Sin Resultados
                                                                                        $CantidadDeCambios ="";
                                                                                    }
                                                                                } else {
                                                                                    //Errro en la consulta sql
                                                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                    mysqli_close($ConexionSQL);
                                                                                    echo "<script>window.location='logout.php';</script>";
                                                                                    exit;
                                                                                }

                                                                            }
                                                                        } else {
                                                                            //Sin Resultados
                                                                            $CantidadDeCambios ="";
                                                                        }
                                                                    } else {
                                                                        //Errro en la consulta sql
                                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                        mysqli_close($ConexionSQL);
                                                                        echo "<script>window.location='logout.php';</script>";
                                                                        exit;
                                                                    }

                                                                }
                                                            } else {
                                                                //Sin Resultados
                                                                $CantidadDeCambios ="";
                                                            }
                                                        } else {
                                                            //Errro en la consulta sql
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo "<script>window.location='logout.php';</script>";
                                                            exit;
                                                        }

                                                    }
                                                } else {
                                                    //Sin Resultados
                                                    $FECHANACIMIENTO = "";
                                                }
                                            } else {
                                                //Errro en la consulta sql
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo "<script>window.location='logout.php';</script>";
                                                exit;
                                            }

                                        }
                                    } else {
                                        //Sin Resultados
                                        $SALARIO = "";
                                    }
                                } else {
                                    //Errro en la consulta sql
                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                    mysqli_close($ConexionSQL);
                                    echo "<script>window.location='logout.php';</script>";
                                    exit;
                                }
                            }
                        } else {
                            //Sin Resultados
                        }
                    } else {
                        //Errro en la consulta sql
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "<script>window.location='logout.php';</script>";
                        exit;
                    }

                    //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                }
            } else {
                //Sin Resultados
                $CodigoCliente ="";
                $SalarioCliente ="";
            }
        } else {
            //Errro en la consulta sql
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

    } else if (($Item == "Salario") && ($Item2 == "Fecha De Nacimiento")) {
        
        $InicioRangoFecha= date("Y-m-d", strtotime($InicioRango2));
        $FinRangoFecha= date("Y-m-d", strtotime($FinRango2));

        //Consulta Tabla Detalle Cliente
        $Datos2 = array();
        $ConsultaSQL = "SELECT FKDETCLI_NCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE (DETCLI_CCONSULTA = 'FechaNacimientoCliente' AND (STR_TO_DATE(DETCLI_CDETALLE,'%d-%m-%Y') BETWEEN '". $InicioRangoFecha ."' AND '". $FinRangoFecha ."')) or (DETCLI_CCONSULTA = 'SalarioCliente' AND DETCLI_CDETALLE BETWEEN ". $InicioRango ." AND ". $FinRango .") AND DETCLI_CESTADO= 'Activo' GROUP BY FKDETCLI_NCLI_NCODIGO HAVING COUNT(1) > 1;";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoCliente = $FilaResultado['FKDETCLI_NCLI_NCODIGO'];
                    $SalarioCliente = $FilaResultado['DETCLI_CDETALLE'];
                    
                    $ConsultaSQL1 = "SELECT PKPENCAL_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE FKPENCAL_NPKCLI_NCODIGO=PKCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO='". $CodigoCliente ."' AND PENCAL_CESTADO = 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NULL;";
                    if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
                        $CantidadResultados1 = $ResultadoSQL1->num_rows;
                        if ($CantidadResultados1 > 0) {
                            while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {
                                $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
                                $CLI_CNOMBRE= $FilaResultado['NOMBRE_CLIENTE'];
                                $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];

                                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'SalarioCliente' AND DETCLI_CESTADO= 'Activo';";
                                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                                    if ($CantidadResultados2 > 0) {
                                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                                            $SALARIO = $FilaResultado['DETCLI_CDETALLE'];

                                            $ConsultaSQL3 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FechaNacimientoCliente' AND DETCLI_CESTADO= 'Activo';";
                                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                                if ($CantidadResultados3 > 0) {
                                                    while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                                        $FECHANACIMIENTO = $FilaResultado['DETCLI_CDETALLE'];

                                                        $ConsultaSQL4 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CantidadDeCambios' AND DETCLI_CESTADO= 'Activo';";
                                                        if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                                                            $CantidadResultados4 = $ResultadoSQL4->num_rows;
                                                            if ($CantidadResultados4 > 0) {
                                                                while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                                                    $CantidadDeCambios = $FilaResultado['DETCLI_CDETALLE'];
                                                                    
                                                                    $ConsultaSQL5 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                    if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                                                        $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                                                        if ($CantidadResultados5 > 0) {
                                                                            while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                                                $FondoPensionCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                
                                                                                $ConsultaSQL0 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'GeneroCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                                if ($ResultadoSQL0 = $ConexionSQL->query($ConsultaSQL0)) {
                                                                                    $CantidadResultados0 = $ResultadoSQL0->num_rows;
                                                                                    if ($CantidadResultados0 > 0) {
                                                                                        while ($FilaResultado = $ResultadoSQL0->fetch_assoc()) {
                                                                                            $GeneroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                            
                                                                                            $ConsultaSQL7 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
                                                                                            if ($ResultadoSQL7 = $ConexionSQL->query($ConsultaSQL7)) {
                                                                                                $CantidadResultados7 = $ResultadoSQL7->num_rows;
                                                                                                if ($CantidadResultados7 > 0) {
                                                                                                    while ($FilaResultado = $ResultadoSQL7->fetch_assoc()) {
                                                                                                        $FondoNuevo = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                        
                                                                                                        $ConsultaSQL8 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CelularCliente' AND DETCLI_CESTADO= 'Activo' LIMIT 1;";
                                                                                                        if ($ResultadoSQL8 = $ConexionSQL->query($ConsultaSQL8)) {
                                                                                                            $CantidadResultados8 = $ResultadoSQL8->num_rows;
                                                                                                            if ($CantidadResultados8 > 0) {
                                                                                                                while ($FilaResultado = $ResultadoSQL8->fetch_assoc()) {
                                                                                                                    $NumeroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                                    
                                                                                                                    array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                                                                                                                }
                                                                                                            } else {
                                                                                                                //Sin Resultados
                                                                                                                $CantidadDeCambios ="";
                                                                                                            }
                                                                                                        } else {
                                                                                                            //Errro en la consulta sql
                                                                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                            mysqli_close($ConexionSQL);
                                                                                                            echo "<script>window.location='logout.php';</script>";
                                                                                                            exit;
                                                                                                        }


                                                                                                    }
                                                                                                } else {
                                                                                                    //Sin Resultados
                                                                                                    $CantidadDeCambios ="";
                                                                                                }
                                                                                            } else {
                                                                                                //Errro en la consulta sql
                                                                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                mysqli_close($ConexionSQL);
                                                                                                echo "<script>window.location='logout.php';</script>";
                                                                                                exit;
                                                                                            }

                                                                                        }
                                                                                    } else {
                                                                                        //Sin Resultados
                                                                                        $CantidadDeCambios ="";
                                                                                    }
                                                                                } else {
                                                                                    //Errro en la consulta sql
                                                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                    mysqli_close($ConexionSQL);
                                                                                    echo "<script>window.location='logout.php';</script>";
                                                                                    exit;
                                                                                }

                                                                            }
                                                                        } else {
                                                                            //Sin Resultados
                                                                            $CantidadDeCambios ="";
                                                                        }
                                                                    } else {
                                                                        //Errro en la consulta sql
                                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                        mysqli_close($ConexionSQL);
                                                                        echo "<script>window.location='logout.php';</script>";
                                                                        exit;
                                                                    }

                                                                }
                                                            } else {
                                                                //Sin Resultados
                                                                $CantidadDeCambios ="";
                                                            }
                                                        } else {
                                                            //Errro en la consulta sql
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo "<script>window.location='logout.php';</script>";
                                                            exit;
                                                        }

                                                    }
                                                } else {
                                                    //Sin Resultados
                                                    $FECHANACIMIENTO = "";
                                                }
                                            } else {
                                                //Errro en la consulta sql
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo "<script>window.location='logout.php';</script>";
                                                exit;
                                            }

                                        }
                                    } else {
                                        //Sin Resultados
                                        $SALARIO = "";
                                    }
                                } else {
                                    //Errro en la consulta sql
                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                    mysqli_close($ConexionSQL);
                                    echo "<script>window.location='logout.php';</script>";
                                    exit;
                                }
                            }
                        } else {
                            //Sin Resultados
                        }
                    } else {
                        //Errro en la consulta sql
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "<script>window.location='logout.php';</script>";
                        exit;
                    }

                    //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                }
            } else {
                //Sin Resultados
                $CodigoCliente ="";
                $SalarioCliente ="";
            }

        } else {
            //Errro en la consulta sql
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

    } else if (($Item == "Fecha De Nacimiento") && ($Item2 == "Salario")) {

        $InicioRangoFecha= date("Y-m-d", strtotime($InicioRango));
        $FinRangoFecha= date("Y-m-d", strtotime($FinRango));

        //Consulta Tabla Detalle Cliente
        $Datos2 = array();
        $ConsultaSQL = "SELECT FKDETCLI_NCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE (DETCLI_CCONSULTA = 'FechaNacimientoCliente' AND (STR_TO_DATE(DETCLI_CDETALLE,'%d-%m-%Y') BETWEEN '". $InicioRangoFecha ."' AND '". $FinRangoFecha ."')) or (DETCLI_CCONSULTA = 'SalarioCliente' AND DETCLI_CDETALLE BETWEEN ". $InicioRango2 ." AND ". $FinRango2 .") AND DETCLI_CESTADO= 'Activo' GROUP BY FKDETCLI_NCLI_NCODIGO HAVING COUNT(1) > 1;";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoCliente = $FilaResultado['FKDETCLI_NCLI_NCODIGO'];
                    $SalarioCliente = $FilaResultado['DETCLI_CDETALLE'];
                    
                    $ConsultaSQL1 = "SELECT PKPENCAL_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE FKPENCAL_NPKCLI_NCODIGO=PKCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO='". $CodigoCliente ."' AND PENCAL_CESTADO = 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NULL;";
                    if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
                        $CantidadResultados1 = $ResultadoSQL1->num_rows;
                        if ($CantidadResultados1 > 0) {
                            while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {
                                $CLI_CDOCUMENTO = $FilaResultado['DOCUMENTO'];
                                $CLI_CNOMBRE= $FilaResultado['NOMBRE_CLIENTE'];
                                $PKPENCAL_NCODIGO = $FilaResultado['PKPENCAL_NCODIGO'];

                                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'SalarioCliente' AND DETCLI_CESTADO= 'Activo';";
                                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                                    if ($CantidadResultados2 > 0) {
                                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                                            $SALARIO = $FilaResultado['DETCLI_CDETALLE'];

                                            $ConsultaSQL3 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FechaNacimientoCliente' AND DETCLI_CESTADO= 'Activo';";
                                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                                if ($CantidadResultados3 > 0) {
                                                    while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                                                        $FECHANACIMIENTO = $FilaResultado['DETCLI_CDETALLE'];

                                                        $ConsultaSQL4 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CantidadDeCambios' AND DETCLI_CESTADO= 'Activo';";
                                                        if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                                                            $CantidadResultados4 = $ResultadoSQL4->num_rows;
                                                            if ($CantidadResultados4 > 0) {
                                                                while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                                                    $CantidadDeCambios = $FilaResultado['DETCLI_CDETALLE'];
                                                                    
                                                                    $ConsultaSQL5 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                    if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                                                        $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                                                        if ($CantidadResultados5 > 0) {
                                                                            while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                                                $FondoPensionCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                
                                                                                $ConsultaSQL0 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'GeneroCliente' AND DETCLI_CESTADO= 'Activo';";
                                                                                if ($ResultadoSQL0 = $ConexionSQL->query($ConsultaSQL0)) {
                                                                                    $CantidadResultados0 = $ResultadoSQL0->num_rows;
                                                                                    if ($CantidadResultados0 > 0) {
                                                                                        while ($FilaResultado = $ResultadoSQL0->fetch_assoc()) {
                                                                                            $GeneroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                            
                                                                                            $ConsultaSQL7 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
                                                                                            if ($ResultadoSQL7 = $ConexionSQL->query($ConsultaSQL7)) {
                                                                                                $CantidadResultados7 = $ResultadoSQL7->num_rows;
                                                                                                if ($CantidadResultados7 > 0) {
                                                                                                    while ($FilaResultado = $ResultadoSQL7->fetch_assoc()) {
                                                                                                        $FondoNuevo = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                        
                                                                                                        $ConsultaSQL8 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO='". $CodigoCliente ."' AND DETCLI_CCONSULTA= 'CelularCliente' AND DETCLI_CESTADO= 'Activo' LIMIT 1;";
                                                                                                        if ($ResultadoSQL8 = $ConexionSQL->query($ConsultaSQL8)) {
                                                                                                            $CantidadResultados8 = $ResultadoSQL8->num_rows;
                                                                                                            if ($CantidadResultados8 > 0) {
                                                                                                                while ($FilaResultado = $ResultadoSQL8->fetch_assoc()) {
                                                                                                                    $NumeroCliente = $FilaResultado['DETCLI_CDETALLE'];
                                                                                                                    
                                                                                                                    array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                                                                                                                }
                                                                                                            } else {
                                                                                                                //Sin Resultados
                                                                                                                $CantidadDeCambios ="";
                                                                                                            }
                                                                                                        } else {
                                                                                                            //Errro en la consulta sql
                                                                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                            mysqli_close($ConexionSQL);
                                                                                                            echo "<script>window.location='logout.php';</script>";
                                                                                                            exit;
                                                                                                        }


                                                                                                    }
                                                                                                } else {
                                                                                                    //Sin Resultados
                                                                                                    $CantidadDeCambios ="";
                                                                                                }
                                                                                            } else {
                                                                                                //Errro en la consulta sql
                                                                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                                mysqli_close($ConexionSQL);
                                                                                                echo "<script>window.location='logout.php';</script>";
                                                                                                exit;
                                                                                            }

                                                                                        }
                                                                                    } else {
                                                                                        //Sin Resultados
                                                                                        $CantidadDeCambios ="";
                                                                                    }
                                                                                } else {
                                                                                    //Errro en la consulta sql
                                                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                                    mysqli_close($ConexionSQL);
                                                                                    echo "<script>window.location='logout.php';</script>";
                                                                                    exit;
                                                                                }

                                                                            }
                                                                        } else {
                                                                            //Sin Resultados
                                                                            $CantidadDeCambios ="";
                                                                        }
                                                                    } else {
                                                                        //Errro en la consulta sql
                                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                        mysqli_close($ConexionSQL);
                                                                        echo "<script>window.location='logout.php';</script>";
                                                                        exit;
                                                                    }

                                                                }
                                                            } else {
                                                                //Sin Resultados
                                                                $CantidadDeCambios ="";
                                                            }
                                                        } else {
                                                            //Errro en la consulta sql
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo "<script>window.location='logout.php';</script>";
                                                            exit;
                                                        }

                                                    }
                                                } else {
                                                    //Sin Resultados
                                                    $FECHANACIMIENTO = "";
                                                }
                                            } else {
                                                //Errro en la consulta sql
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo "<script>window.location='logout.php';</script>";
                                                exit;
                                            }

                                        }
                                    } else {
                                        //Sin Resultados
                                        $SALARIO = "";
                                    }
                                } else {
                                    //Errro en la consulta sql
                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                    mysqli_close($ConexionSQL);
                                    echo "<script>window.location='logout.php';</script>";
                                    exit;
                                }
                            }
                        } else {
                            //Sin Resultados
                        }
                    } else {
                        //Errro en la consulta sql
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "<script>window.location='logout.php';</script>";
                        exit;
                    }

                    //array_push($Datos2, array("0" => $CLI_CDOCUMENTO, "1" => $CLI_CNOMBRE, "2" => $FondoPensionCliente, "3" => $FondoNuevo, "4" => $SALARIO, "5" => $CantidadDeCambios, "6" => $GeneroCliente, "7" => $FECHANACIMIENTO, "8" => $NumeroCliente, "9" => $PKPENCAL_NCODIGO, "10" => $CodigoCliente));

                }
            } else {
                //Sin Resultados
                $CodigoCliente ="";
                $SalarioCliente ="";
            }

        } else {
            //Errro en la consulta sql
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

    }
}



//Listado Flitrar Por...
$ListadoFlitrarPor = "";
$ConsultaSQL = "SELECT DISTINCT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbFiltro' AND EST_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListadoFlitrarPor = $ListadoFlitrarPor . '<option value="' . $FilaResultado['EST_CDETALLE'] . '">' . $FilaResultado['EST_CDETALLE'] . '</option>';
        }
    } else {
        //Sin Resultados
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//ListadoCantidadCambios
$ListadoCantidadCambios = "";
$ConsultaSQL = "SELECT EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbFiltro' AND EST_CDETALLE = 'Cantidad De Cambios' AND EST_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListadoCantidadCambios = $ListadoCantidadCambios . '<option value="' . $FilaResultado['EST_CDETALLE1'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
        }
    } else {
        //Sin Resultados
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//ListadoSalario
$ListadoSalario = "";
$ConsultaSQL = "SELECT EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbFiltro' AND EST_CDETALLE = 'Salario' AND EST_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListadoSalario = $ListadoSalario . '<option value="' . $FilaResultado['EST_CDETALLE1'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
        }
    } else {
        //Sin Resultados
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//ListadoFechaDeNacimiento
$ListadoFechaDeNacimiento = "";
$ConsultaSQL = "SELECT EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbFiltro' AND EST_CDETALLE = 'Fecha De Nacimiento' AND EST_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListadoFechaDeNacimiento = $ListadoFechaDeNacimiento . '<option value="' . $FilaResultado['EST_CDETALLE1'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
        }
    } else {
        //Sin Resultados
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}


//Consulte la cantidad de casos pendientes para mostrar en el boton
$CantidadResultados = 0;
$ConsultaSQL = "SELECT PKPENCAL_NCODIGO, CLI_CDOCUMENTO AS DOCUMENTO, CONCAT (CLI_CNOMBRE,' ',CLI_CNOMBRE2,' ',CLI_CAPELLIDO,' ',CLI_CAPELLIDO2) AS NOMBRE_CLIENTE, PENCAL_CFONDO_NUEVO AS FONDO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE, u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE PKCLI_NCODIGO = FKDETCLI_NCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO = PKCLI_NCODIGO AND DETCLI_CCONSULTA = 'FondoPensionCliente' AND FKPENCAL_NPKPER_NCODIGO = " . $AGENTE . " AND PENCAL_CESTADO_FINAL = 'No Contacto' AND PENCAL_CESTADO_FINAL2 = 'No Contesta' AND PENCAL_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
    } else {
        $CantidadResultados = 0;
    }
} else {
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
    <title>Asignacion Casos Nuevos :: Futurus</title>
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
    <!-- Data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">

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
            <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center !important;">
                        <div class="navbar-brand d-none d-sm-block d-md-block" style="margin-bottom: 3%;">
                            <h6>ASIGNACION DE CASOS NUEVOS</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h6>ASIGNACION DE CASOS NUEVOS</h6>
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
                
                <label class="btn btn-primary nextBtn btn-lg center-block btnsig" id="Seleccionar_Todo" style="float: right">SELECCIONAR TODOS</label>
                    <button id="btnAsignar" data-toggle="modal" data-target="#myModal" class="btn btn-primary nextBtn btn-lg center-block btnsig" type="submit">ASIGNAR</button>     
                </div>
                


                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%; padding-left: 3%;">
                        <label id="LblFiltro" style="font-size: 10px; margin-top: 10%; margin-left: -2%;"><h5>Filtro Por Rangos: </h5>
                    </div>

                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
                    </div>

                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%; padding-left: 5%;">
                        <label id="LblFiltro" style="font-size: 10px; margin-top: 10%; margin-left: -1%;"><h5>Rango: </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 3%; margin-top: -1%;">
                        <select id="FlitrarPor" name="" class="form-control" required="">
                            <option selected="" hidden="" disabled=""> Filtrar Por... </option>
                            <?php echo $ListadoFlitrarPor; ?>
                        </select>
                    </div>

                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 3%; margin-top: -2%;">
                        <select id="Rango" name="" class="form-control" required="">
                            <option selected="" hidden="" disabled=""> Entre... </option>
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 3%; margin-top: -2%;">
                        <button id="BtnFiltrar" name="BtnFiltrar" type="button" class="btn btn-futurus-r">Filtrar</button>
                        <button id="BtnQuitarFiltro" name="BtnQuitarFiltro" type="button" class="btn btn-primary">Quitar Filtros</button>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 3%; margin-top: -1%;">
                        <select id="FlitrarPor2" name="" class="form-control" required="">
                            <option selected="" hidden="" disabled=""> Filtrar Por... </option>
                            <?php echo $ListadoFlitrarPor; ?>
                        </select>
                    </div>

                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; padding-left: 3%; margin-top: -2%;">
                        <select id="Rango2" name="" class="form-control" required="">
                            <option selected="" hidden="" disabled=""> Entre... </option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="col-lg-12">
                    <div class="table-responsive" style="width: 128%; margin-left: -11%;">
                        <div class="header">
                        </div>
                        <div class="body">
                            <table id="tabla" class="table table-bordered table-striped table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable">
                            <thead>
                                    <tr>
                                        <th style="text-align: center;">Documento</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Fondo Actual</th>
                                        <th style="text-align: center;">Fondo Al Que Va</th>
                                        <th style="text-align: center;">Salario Cliente</th>
                                        <th style="text-align: center;">Cantidad Cambios</th>
                                        <th style="text-align: center;">Genero Cliente</th>
                                        <th style="text-align: center;">Fecha De Nacimiento</th>
                                        <th style="text-align: center;">Numero Contacto</th>
                                        <th style="text-align: center;">Seleccionar</th>
                                        <th style="text-align: center;">Actualizar Informacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        for ($i = 0; $i < count($Datos2); $i++) {
                                            echo '<tr>';
                                            for ($b = 0; $b < count($Datos2[$i]); $b++) {
                                                if($b == 9){
                                                    echo '<td style="text-align: center;"><label><input type="checkbox" class="btn-check" name="checks[]" id="btn-check" autocomplete="off" value="' . $Datos2[$i][9] . '"/><span></span></label></td>';
                                                }else if($b == 10){
                                                    echo '<td style="text-align: center;"><label><a onclick="enviarInformacionSupervisor(' .$Datos2[$i][10] .');" id="SeleccionarCaso" class="btn btn-futurus-r " data-target="1" value=""><i class="fa icon-note"></i></a><span></span></label></td>';
                                                }else{
                                                    echo '<td style="text-align: center;">' . $Datos2[$i][$b] . '</td>';
                                                }                      
                                            }
                                            echo '</tr>';
                                        }
                                    
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center;">Documento</th>
                                        <th style="text-align: center;">Nombre Cliente</th>
                                        <th style="text-align: center;">Fondo Actual</th>
                                        <th style="text-align: center;">Fondo Al Que Va</th>
                                        <th style="text-align: center;">Salario Cliente</th>
                                        <th style="text-align: center;">Cantidad Cambios</th>
                                        <th style="text-align: center;">Genero Cliente</th>
                                        <th style="text-align: center;">Fecha De Nacimiento</th>
                                        <th style="text-align: center;">Numero Contacto</th>
                                        <th style="text-align: center;">Seleccionar</th>
                                        <th style="text-align: center;">Actualizar Informacion</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal" id="myModal">
            <div class="modal-lg modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Asignación De Casos</h4>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>

                    <div id="Loading" style="margin-left: 30%">
                        <img src="../images/loading.gif">
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        
                        <table id="tabla2" class="table table-responsive-sm-md-lg-xl dt-responsive table-hover dataTable" >
                            <thead>
                                <tr>
                                    <th class="center">Documento</th>
                                    <th class="center">Usuario</th>
                                    <th class="center">Nombre</th>
                                    <th class="center">PreAsignar Casos</th>
                                    <th class="center">Asignar Casos</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: fff9f92f">
                                <?php

                                     for ($i = 0; $i < count($Datos); $i++) {
                                            echo '<tr>';
                                            for ($b = 0; $b < count($Datos[$i]); $b++) {
                                                if ($b == 3) {
                                                    echo '<td style="text-align: center"><a id="PreAsignarCaso' . $Datos[$i][$b] . '" class="PreAsignarCaso btn btn-primary"><i class="fa fa-folder-open-o"></i></a></td>';
                                                } else if ($b == 4) {
                                                    echo '<td style="text-align: center"><a id="AsignarCaso' . $Datos[$i][$b] . '" class="AsignarCaso btn btn-futurus-r"><i class="fa fa-check-square"></i></a></td>';
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
                                <th class="center">PreAsignar Casos</th>
                                <th class="center">Asignar Casos</th>
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
        <div>
            <form method="POST" action="AsignacionNuevosHomeOffice.php" enctype="multipart/form-data">
                <input id="Item" name="Item" hidden="true">
                <input id="Item2" name="Item2" hidden="true">
                <input id="InicioRango" name="InicioRango" hidden="true">
                <input id="FinRango" name="FinRango" hidden="true">
                <input id="InicioRango2" name="InicioRango2" hidden="true">
                <input id="FinRango2" name="FinRango2" hidden="true">
                <button id="GuardarSub" type="submit" class="btn" hidden="true">Guardar</button>
            </form>
        </div>
        <div>
            <form method="POST" action="PreasignacionDeCasos.php" enctype="multipart/form-data">
                <input id="id2" name="id2" hidden="true">
                <input id="Agente" name="Agente" hidden="true">
                <input id="casos" name="casos" hidden="true">
                <button id="GuardarSub2" type="submit" class="btn" hidden="true">Guardar2</button>
            </form>
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
    <script src="../js/ajax/controlPendientes.js"></script>
    <!-- datatable -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>

   
    <script>

        $(document).ready(function() {
            $('#Loading').hide();
            $('#FlitrarPor2').hide();
            $('#Rango2').hide();

        });
        
        //Inicializacion Tabla1
        $(document).ready(function() {
            var table = $('#tabla').DataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-2 d-flex align-items-center justify-content-start'f>    >" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    //Quitar Paginado
                    "paging": false,
                    orderCellsTop: true     
            });

            //Creamos una fila en el head de la tabla y lo clonamos para cada columna
            $('#tabla thead tr').clone(true).appendTo('#tabla thead');

            $('#tabla thead tr:eq(1) th').each(function(i) {
                var title = $(this).text(); //es el nombre de la columna
                if (title == "Documento") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder=""/>');
                } else if (title == "Nombre Cliente") {
                    $(this).html('<input style="width: 192px;" type="text" placeholder=""/>');
                } else if (title == "Fondo Actual") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder=""/>');
                } else if (title == "Fondo Al Que Va") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder=""/>');
                } else if (title == "Salario Cliente") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder="" disabled="true"/>');
                } else if (title == "Fecha De Nacimiento") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder="" disabled="true"/>');
                } else if (title == "Genero Cliente") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder="" />');
                } else if (title == "Cantidad Cambios") {
                    $(this).html('<input style="width: 28px;" type="text" placeholder=""/>');
                } else if (title == "Numero Contacto") {
                    $(this).html('<input style="width: 92px;" type="text" placeholder="" />');
                } else if (title == "Seleccionar") {
                    $(this).html('<input hidden="true" style="width: 28px;" type="text" placeholder=""/>');
                } else if (title == "Actualizar Informacion") {
                    $(this).html('<input hidden="true" style="width: 28px;" type="text" placeholder=""/>');
                } else{
                    $(this).html('<input type="text" placeholder=""/>');
                }
                

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

        });

        //Inicializacion Tabla2
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

        //Seleccionar Un Caso
        $(document).ready(function() {

            $('[name="checks[]"]').click(function() {

                var arr = $('[name="checks[]"]:checked').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');

                $('#str').val(str);

            });

        });

        //Funcion para asignar caso a un usuario
        $("body").on('click', '.AsignarCaso', function(){

            $("#tabla2_wrapper").hide();
            $("#Loading").show();
            let form_data = new FormData();

            id = $(this).attr("id");
            id = id.replace("AsignarCaso", "");
            form_data.append('id', id);
            
            var Agente = $("#Agente").val();
            form_data.append('Agente', Agente);
            var casos = $("#str").val();
            form_data.append('casos', casos);
            
            if (casos == ""){
                alert("¡Se Tiene Que seleccionar Minimo Un Caso Para Asignar Al Asesor!");
                $("#Loading").hide();
                $("#tabla2_wrapper").show();
            }else{
                $.ajax({
                    url: "GuardarCasosPendientesCall.php",
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
                            alert("¡Asignacion De Caso Exitosa! 😉");
                            $("#Loading").hide();
                            $("#tabla2_wrapper").show();
                            window.location = "AsignacionNuevosHomeOffice.php";
                        } else if (Respuesta == "Error") {
                            alert("😩  Se genero una falla en la asignación!");
                            $("#Loading").hide();
                            $("#tabla2_wrapper").show();
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


        //Funcion para asignar caso a un usuario
        $("body").on('click', '.PreAsignarCaso', function(){

            $("#tabla2_wrapper").hide();
            $("#Loading").show();

            id = $(this).attr("id");
            id = id.replace("PreAsignarCaso", "");

            var Agente = $("#Agente").val();
            var casos = $("#str").val();

            if (casos == ""){
                alert("🤔 ¡Se Tiene Que seleccionar Minimo Un Caso Para Preasignar Al Asesor! 🤔");
                $("#Loading").hide();
                $("#tabla2_wrapper").show();

            }else{
            
                document.getElementById('id2').value = id;
                document.getElementById('Agente').value = Agente;
                document.getElementById('casos').value = casos;

                $("#Loading").hide();
                $("#tabla2_wrapper").show();

                $("#GuardarSub2").click();

            }
        
        })

        //Funcion para Seleccionar Todo
        $("#Seleccionar_Todo").click(function(){

            let IsCheck= $(this).is(':checked');
            
            if(IsCheck == false){
                $(".btn-check").prop('checked', true);
                var arr = $('[name="checks[]"]:checked').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');
                $('#str').val(str);
            }else{
                $(".btn-check").prop('checked', false);
            }
        });

        //Funcion Para Filtro Por Rangos
        $("#FlitrarPor").on("change", function() {
            var valor = $(this).val();
            if (valor == "Cantidad De Cambios") {
                $("#Rango").text('');
                $("#Rango").append('<option selected="" hidden="" disabled="">Opciones</option>');
                $("#Rango").append('<?php echo $ListadoCantidadCambios;?>');
            } else if (valor == "Salario") {
                $("#Rango").text('');
                $("#Rango").append('<option selected="" hidden="" disabled="">Entre...</option>');
                $("#Rango").append('<?php echo $ListadoSalario; ?>');
                $("#FlitrarPor2").text('');
                $("#FlitrarPor2").append('<option selected="" value="Fecha De Nacimiento">Fecha De Nacimiento</option>');
                $("#FlitrarPor2").append('<option selected="" value="">Filtrar Por..</option>');
            }else if (valor == "Fecha De Nacimiento") {
                $("#Rango").text('');
                $("#Rango").append('<option selected="" hidden="" disabled="">Entre...</option>');
                $("#Rango").append('<?php echo $ListadoFechaDeNacimiento; ?>');
                $("#FlitrarPor2").text('');
                $("#FlitrarPor2").append('<option selected="" value="Salario">Salario</option>');
                $("#FlitrarPor2").append('<option selected="" value="">Filtrar Por..</option>');
            }
        });

        //Funcion Para Filtro Por Rangos 2
        $("#FlitrarPor2").on("click", function() {
            var valor = $(this).val();
            if (valor == "Cantidad De Cambios") {
                $("#Rango2").text('');
                $("#Rango2").append('<option selected="" hidden="" disabled="">Opciones</option>');
                $("#Rango2").append('<?php echo $ListadoCantidadCambios;?>');
            } else if (valor == "Salario") {
                $("#Rango2").text('');
                $("#Rango2").append('<option selected="" hidden="" disabled="">Entre...</option>');
                $("#Rango2").append('<?php echo $ListadoSalario; ?>');
            }else if (valor == "Fecha De Nacimiento") {
                $("#Rango2").text('');
                $("#Rango2").append('<option selected="" hidden="" disabled="">Entre...</option>');
                $("#Rango2").append('<?php echo $ListadoFechaDeNacimiento; ?>');
            }
        });

        //Mostrar Otro Rango
        $("#Rango").on("change", function() {
            $('#FlitrarPor2').show();
            $('#Rango2').show();
        });

        
        //Filtrar Por Rango
        $("#BtnFiltrar").on("click", function() {
            var Item = $("#FlitrarPor option:selected").text();
            var Rango = $("#Rango option:selected").text();
            var Item2 = $("#FlitrarPor2 option:selected").text();
            var Rango2 = $("#Rango2 option:selected").text();

            const fecha = new Date();
            const Fecha= fecha.toLocaleDateString();
            const Fecha1= Fecha.replace('/', '-');
            const FechaHoy= Fecha1.replace('/', '-');

            if (Item == "Salario") {
                if (Rango == "Menos De $900.000") {
                    InicioRango = "0";
                    FinRango = "900000";
                } else if (Rango == "De $900.000 a $1.500.000") {
                    InicioRango = "900000";
                    FinRango = "1500000";
                } else if (Rango == "De $1.500.000 a $2.000.000") {
                    InicioRango = "1500000";
                    FinRango = "2000000";
                } else if (Rango == "De $2.000.000 a $2.500.000") {
                    InicioRango = "2000000";
                    FinRango = "2500000";
                } else if (Rango == "De $2.500.000 a $3.000.000") {
                    InicioRango = "2500000";
                    FinRango = "3000000";
                } else if (Rango == "De $3.000.000 a $3.700.000") {
                    InicioRango = "3000000";
                    FinRango = "3700000";
                } else if (Rango == "Mas De $3.700.000") {
                    InicioRango = "3700000";
                    FinRango = "1000000000";
                }

                if (Rango2 == "De 1964 a 1969") {
                    InicioRango2 = "01-01-1964";
                    FinRango2 = "31-12-1969";
                } else if (Rango2 == "De 1970 a 1975") {
                    InicioRango2 = "01-01-1970";
                    FinRango2 = "31-12-1975";
                } else if (Rango2 == "De 1976 a 1980") {
                    InicioRango2 = "01-01-1976";
                    FinRango2 = "31-12-1980";
                } else if (Rango2 == "De 1981 a 1985") {
                    InicioRango2 = "01-01-1981";
                    FinRango2 = "31-12-1986";
                } else if (Rango2 == "De 1986 a 1990") {
                    InicioRango2 = "01-01-1986";
                    FinRango2 = "31-12-1990";
                } else if (Rango2 == "De 1991 a 1995") {
                    InicioRango2 = "01-01-1991";
                    FinRango2 = "31-12-1995";
                } else if (Rango2 == "De 1996 a 2000") {
                    InicioRango2 = "01-01-1996";
                    FinRango2 = "31-12-2000";
                } else if (Rango2 == "De 2001 a 2005") {
                    InicioRango2 = "01-01-2001";
                    FinRango2 = "31-12-2005";
                } else if (Rango2 == "De 2006 a 2010") {
                    InicioRango2 = "01-01-2006";
                    FinRango2 = "31-12-2010";
                } else if (Rango2 == "De 2011 a 2015") {
                    InicioRango2 = "01-01-2011";
                    FinRango2 = "31-12-2015";
                } else if (Rango2 == "De 2016 a 2020") {
                    InicioRango2 = "01-01-2016";
                    FinRango2 = "31-12-2020";
                } else if (Rango2 == "De 2020 a Hoy") {
                    InicioRango2 = "01-01-2020";
                    FinRango2 = FechaHoy;
                }


            } else {

                if (Rango == "De 1964 a 1969") {
                    InicioRango = "01-01-1964";
                    FinRango = "31-12-1969";
                } else if (Rango == "De 1970 a 1975") {
                    InicioRango = "01-01-1970";
                    FinRango = "31-12-1975";
                } else if (Rango == "De 1976 a 1980") {
                    InicioRango = "01-01-1976";
                    FinRango = "31-12-1980";
                } else if (Rango == "De 1981 a 1985") {
                    InicioRango = "01-01-1981";
                    FinRango = "31-12-1986";
                } else if (Rango == "De 1986 a 1990") {
                    InicioRango = "01-01-1986";
                    FinRango = "31-12-1990";
                } else if (Rango == "De 1991 a 1995") {
                    InicioRango = "01-01-1991";
                    FinRango = "31-12-1995";
                } else if (Rango == "De 1996 a 2000") {
                    InicioRango = "01-01-1996";
                    FinRango = "31-12-2000";
                } else if (Rango == "De 2001 a 2005") {
                    InicioRango = "01-01-2001";
                    FinRango = "31-12-2005";
                } else if (Rango == "De 2006 a 2010") {
                    InicioRango = "01-01-2006";
                    FinRango = "31-12-2010";
                } else if (Rango == "De 2011 a 2015") {
                    InicioRango = "01-01-2011";
                    FinRango = "31-12-2015";
                } else if (Rango == "De 2016 a 2020") {
                    InicioRango = "01-01-2016";
                    FinRango = "31-12-2020";
                } else if (Rango == "De 2020 a Hoy") {
                    InicioRango = "01-01-2020";
                    FinRango = FechaHoy;
                }

                if (Rango2 == "Menos De $900.000") {
                    InicioRango2 = "0";
                    FinRango2 = "900000";
                } else if (Rango2 == "De $900.000 a $1.500.000") {
                    InicioRango2 = "900000";
                    FinRango2 = "1500000";
                } else if (Rango2 == "De $1.500.000 a $2.000.000") {
                    InicioRango2 = "1500000";
                    FinRango2 = "2000000";
                } else if (Rango2 == "De $2.000.000 a $2.500.000") {
                    InicioRango2 = "2000000";
                    FinRango2 = "2500000";
                } else if (Rango2 == "De $2.500.000 a $3.000.000") {
                    InicioRango2 = "2500000";
                    FinRango2 = "3000000";
                } else if (Rango2 == "De $3.000.000 a $3.700.000") {
                    InicioRango2 = "3000000";
                    FinRango2 = "3700000";
                } else if (Rango2 == "Mas De $3.700.000") {
                    InicioRango2 = "3700000";
                    FinRango2 = "1000000000";
                }


            }

            document.getElementById('Item').value = Item;
            document.getElementById('Item2').value = Item2;
            document.getElementById('InicioRango').value = InicioRango;
            document.getElementById('FinRango').value = FinRango;
            document.getElementById('InicioRango2').value = InicioRango2;
            document.getElementById('FinRango2').value = FinRango2;

            
            $("#GuardarSub").click();
        });

        //Quitar Filtros 
        $("#BtnQuitarFiltro").on("click", function() {
            window.location='AsignacionNuevosHomeOffice.php';
        });
        
        

    </script>

</body>

</html>