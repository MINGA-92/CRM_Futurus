 
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
    echo '<script>alert("Error Falla -> 1' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//ME RECIBE EL CODIGO DE EL EXPEDIENTE ENVIADO POR EL CONTROL
if (isset($_POST['codigoExpediente'])) {
    $CASOS = 1;
    $CODIGO_CASO = $_POST['codigoExpediente'];
} else {
    $CASOS = 0;
    $CODIGO_CASO = "";
    /*
    //Consulta Asignador
    //Se consulta 1 si ya se tiene un caso asignado, de lo contrario asigna uno
    $ConsultaSQL = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKPER_NCODIGO = '" . $AGENTE . "' AND (PENCAL_CESTADO_FINAL IS NULL OR  PENCAL_CESTADO_FINAL='') AND (PENCAL_COBSERVACIONES IS NULL OR PENCAL_COBSERVACIONES='') AND PENCAL_CESTADO = '" . $datos . "' LIMIT 1;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            $CASOS = 1;
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $CODIGO_CASO = $FilaResultado['PKPENCAL_NCODIGO'];
                break;
            }
        } else {
            //Asignar caso
            $ConsultaSQL = "SELECT PKPENCAL_NCODIGO, FKPENCAL_NPKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CFECHA_OFRECIMIENTO <= NOW() AND FKPENCAL_NPKPER_NCODIGO IS NULL AND PENCAL_CESTADO = '" . $datos . "' LIMIT 1 ;";
            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if ($CantidadResultados > 0) {
                    $CASOS = 1;
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $CASO_ASIGNADO = $FilaResultado['PKPENCAL_NCODIGO'];
                        $CODIGO_CLIENTE = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];

                        $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET FKPENCAL_NPKPER_NCODIGO = '" . $AGENTE . "', PENCAL_CFECHA_EXPEDIENTE = NOW() WHERE PKPENCAL_NCODIGO = '" . $CASO_ASIGNADO . "' AND FKPENCAL_NPKPER_NCODIGO IS NULL AND PENCAL_CESTADO = '" . $datos . "'";
                        if ($ResultadoSQL = $ConexionSQL->query($ActualizarSql)) {
                            echo "<script>window.location='AgenteCall.php';</script>";
                        } else {
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            mysqli_close($ConexionSQL);
                            exit;
                        }
                    }
                } else {
                    $CASOS = 0;
                    $CODIGOPEMPRESA = "";
                }
            } else {
                $ErrorConsulta = mysqli_error($ConexionSQL);
                mysqli_close($ConexionSQL);
                echo '<script>alert("Error Falla -> 4' . $ErrorConsulta . '");</script>';
                echo "<script>window.location='logout.php';</script>";
                exit;
            }
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> 5' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    */
}


$EstadosCiviles = "";
$EstadoCivilActual = "";
$PreguntaInteresadoCredito = "";
$PreguntaInteresadoCartera = "";
$FechaNacimiento = "";
$FechaIngresoLaboral = "";
$ListadoPaises = "";
$ListadoContacto = "";
$ListadoNoContacto = "";
$ListadoNoAplica = "";
$NOMBREEMPRESA = "";

if ($CASOS == 1) {
    //Consulta Nombre y documento
    $ConsultaSQL = "SELECT PKCLI_NCODIGO, CLI_CNOMBRE, CLI_CNOMBRE2, CLI_CAPELLIDO, CLI_CAPELLIDO2, CLI_CDOCUMENTO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE, u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = " . $CODIGO_CASO . " AND PKCLI_NCODIGO = FKPENCAL_NPKCLI_NCODIGO AND CLI_CESTADO = '" . $datos . "';";

    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $LLAVECONSULTA = $FilaResultado['PKCLI_NCODIGO'];
                $CLI_CNOMBRE = $FilaResultado['CLI_CNOMBRE'];
                $CLI_CNOMBRE2 = $FilaResultado['CLI_CNOMBRE2'];
                $CLI_CAPELLIDO = $FilaResultado['CLI_CAPELLIDO'];
                $CLI_CAPELLIDO2 = $FilaResultado['CLI_CAPELLIDO2'];
                $CLI_CDOCUMENTO = $FilaResultado['CLI_CDOCUMENTO'];

                $NOMBRECOMPLETOCLIENTE = $CLI_CNOMBRE . " " . $CLI_CNOMBRE2 . " " . $CLI_CAPELLIDO . " " . $CLI_CAPELLIDO2;
            }
        } else {
            $LLAVECONSULTA = "";
            $CLI_CNOMBRE = "";
            $CLI_CNOMBRE2 = "";
            $CLI_CAPELLIDO = "";
            $CLI_CAPELLIDO2 = "";
            $CLI_CDOCUMENTO = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla ->  6' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    if ($LLAVECONSULTA == '') {
        $CODIGOPEMPRESA = "";
    } else {
        //Consulta EmpresaCliente.
        $TIPOCONSULTA = "EmpresaCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";

        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CODIGOPEMPRESA = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $CODIGOPEMPRESA = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla ->  7' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Numero Contacto.
        $TIPOCONSULTA = "CelularCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CONTACTOMOVIL = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $CONTACTOMOVIL = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla ->  8' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }
        //Consulta Direccion Cliente
        $TIPOCONSULTA = "DireccionDomicilioPrincipal";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CODIGODRDIMCLIENTEPRINCIPAL = $FilaResultado['DETCLI_CDETALLE'];
                    $DIRECCIONDOMICILIO = "";
                }
                $ConsultaSQL2 = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE PKDETCLI_NCODIGO = '" . $CODIGODRDIMCLIENTEPRINCIPAL . "' AND DETCLI_CESTADO = '" . $datos . "'";
                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                    $CantidadResultados = $ResultadoSQL2->num_rows;
                    if ($CantidadResultados > 0) {
                        while ($FilaResultado2 = $ResultadoSQL2->fetch_assoc()) {
                            $DIRECCIONDOMICILIO = $FilaResultado2['DETCLI_CDETALLE'];
                        }
                    } else {
                        //Sin Resultados
                        $CODIGODRDIMCLIENTEPRINCIPAL = "";
                        $DIRECCIONDOMICILIO = "";
                    }
                } else {
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    echo '<script>alert("Error Falla -> 9' . $ErrorConsulta . '");</script>';
                    echo "<script>window.location='logout.php';</script>";
                    exit;
                }
            } else {
                $CODIGODRDIMCLIENTEPRINCIPAL = "";
                $DIRECCIONDOMICILIO = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> 10' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Cargo.
        $TIPOCONSULTA = "CargoLaboralCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CARGO = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $CARGO = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> 11' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Salario.
        $TIPOCONSULTA = "SalarioCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $SALARIOCLIENTE = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $SALARIOCLIENTE = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }


        //Consulta IBCCliente.
        $TIPOCONSULTA = "IBCCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $IBCCLIENTE = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $IBCCLIENTE = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Fondo Actual.
        $TIPOCONSULTA = "FondoPensionCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $FONDOACTUAL = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $FONDOACTUAL = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Cesantias.
        $TIPOCONSULTA = "CesantiasCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CESANTIAS = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $CESANTIAS = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            exit;
        }


        //Consulta Nombre empresa y nit
        $ConsultaSQL = "SELECT PKEMP_NCODIGO, EMP_CDOCUMENTO, CONCAT(EMP_CNOMBRE, ' ', EMP_CNOMBRE2, ' ', EMP_CAPELLIDO, ' ', EMP_CAPELLIDO2) AS EMPRESA FROM u632406828_dbp_crmfuturus.TBL_REMPRESA, u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE PKEMP_NCODIGO = '" . $CODIGOPEMPRESA . "' AND EMP_CESTADO = '" . $datos . "' AND DETCLI_CESTADO = '" . $datos . "' GROUP BY EMP_CNOMBRE;";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CODIGOEMPRESA = $FilaResultado['PKEMP_NCODIGO'];
                    $NOMBREEMPRESA = $FilaResultado['EMPRESA'];
                    $DOCUMENTOEMPRESA = $FilaResultado['EMP_CDOCUMENTO'];
                    $SELECCION = '<option value="' . $CODIGOEMPRESA . '">' . $NOMBREEMPRESA . '</option>';
                }
            } else {
                $NOMBREEMPRESA = "";
                $DOCUMENTOEMPRESA = "";
                $CODIGOEMPRESA = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Lista de las empresas 
        $LISTA = "";
        $ConsultaSQL = "SELECT PKEMP_NCODIGO, CONCAT(EMP_CNOMBRE, ' ', EMP_CNOMBRE2, ' ', EMP_CAPELLIDO, ' ', EMP_CAPELLIDO2) AS EMPRESA FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE PKEMP_NCODIGO != '" . $CODIGOEMPRESA . "' AND EMP_CESTADO = '" . $datos . "' ORDER BY EMP_CNOMBRE;";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CODIGOEMPRESA = $FilaResultado['PKEMP_NCODIGO'];
                    $EMPRESA = $FilaResultado['EMPRESA'];
                    $LISTA = $LISTA . '<option value="' . $CODIGOEMPRESA . '">' . $EMPRESA . '</option>';
                }
            } else {
                $LISTA = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Direccion Empresa
        $TIPOCONSULTA = "DireccionEmpresa";
        $ConsultaSQL = "SELECT DETEMP_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA, u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE FKDETEMP_NCLI_NCODIGO = '" . $CODIGOPEMPRESA . "' AND PKEMP_NCODIGO = FKDETEMP_NCLI_NCODIGO AND DETEMP_CCONSULTA = '" . $TIPOCONSULTA . "' AND EMP_CESTADO = '" . $datos . "' AND DETEMP_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $DIRECCIONEMPRESA = $FilaResultado['DETEMP_CDETALLE'];
                }
            } else {
                $DIRECCIONEMPRESA = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Numero Empresa
        $TIPOCONSULTA = "TelefonoEmpresa";
        $ConsultaSQL = "SELECT DETEMP_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA, u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE FKDETEMP_NCLI_NCODIGO = '" . $CODIGOPEMPRESA . "' AND PKEMP_NCODIGO = FKDETEMP_NCLI_NCODIGO AND DETEMP_CCONSULTA = '" . $TIPOCONSULTA . "' AND EMP_CESTADO = '" . $datos . "' AND DETEMP_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $TELEFONOEMPRESA = $FilaResultado['DETEMP_CDETALLE'];
                }
            } else {
                $TELEFONOEMPRESA = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Fondo Actual.
        $TIPOCONSULTA = "DescripcionSiafpCliente";
        $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $DESCRIPCIPNSIAFP = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $DESCRIPCIPNSIAFP = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }


        //Consulta Fondo al que se envia
        $ConsultaSQL = "SELECT PENCAL_CFONDO_NUEVO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE FKPENCAL_NPKCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND PKCLI_NCODIGO = FKPENCAL_NPKCLI_NCODIGO AND PENCAL_CESTADO = '" . $datos . "' AND CLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $FONDOENVIA = $FilaResultado['PENCAL_CFONDO_NUEVO'];
                }
            } else {
                $FONDOENVIA = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        /**Inicio de consultas informacion modales */
        //Consulta Correos 
        $CorreosCliente = array();
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = 'CorreoCliente' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($CorreosCliente, array('0' => $FilaResultado['PKDETCLI_NCODIGO'], '1' => $FilaResultado['DETCLI_CDETALLE']));
                }
            } else {
                //No hay Resultados
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Direcciones Domicilio Cliente
        $TIPOCONSULTA = "DireccionDomicilio";
        $DirecconesDomicilioCliente = array();
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE3, DETCLI_CDETALLE4 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($DirecconesDomicilioCliente, array('0' => $FilaResultado['PKDETCLI_NCODIGO'], '1' => $FilaResultado['DETCLI_CDETALLE'], '2' => $FilaResultado['DETCLI_CDETALLE1'], '3' => $FilaResultado['DETCLI_CDETALLE2'], '4' => $FilaResultado['DETCLI_CDETALLE3'], '5' => $FilaResultado['DETCLI_CDETALLE4']));
                }
            } else {
                $DirecconesDomicilioCliente = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }


        //Direcciones Oficina cliente
        $TIPOCONSULTA = "DireccionOficina";
        $DirecconesOficcilioCliente = array();
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE3, DETCLI_CDETALLE4 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($DirecconesOficcilioCliente, array('0' => $FilaResultado['PKDETCLI_NCODIGO'], '1' => $FilaResultado['DETCLI_CDETALLE'], '2' => $FilaResultado['DETCLI_CDETALLE1'], '3' => $FilaResultado['DETCLI_CDETALLE2'], '4' => $FilaResultado['DETCLI_CDETALLE3'], '5' => $FilaResultado['DETCLI_CDETALLE4']));
                }
            } else {
                $DirecconesOficcilioCliente = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }


        //Consulta Estado Civil
        $TIPOCONSULTA = "EstadoCivilCliente";
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND  DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoEstadoCivilActual = $FilaResultado['PKDETCLI_NCODIGO'];
                    $EstadoCivilActual = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $EstadoCivilActual = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Estado Civil
        $TIPOCONSULTA = "EstadoCivilCliente";
        $EstadosCiviles = "";
        $ConsultaSQL = "SELECT DISTINCT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbEstadoCivil' AND EST_CESTADO = 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $EST_CDETALLE = $FilaResultado['EST_CDETALLE'];
                    $EstadosCiviles = "'" . $FilaResultado['EST_CDETALLE'] . "', " . $EstadosCiviles;
                }
            } else {
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Fecha Nacimiento
        $TIPOCONSULTA = "FechaNacimientoCliente";
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoFechaNacimiento = $FilaResultado['PKDETCLI_NCODIGO'];
                    $FechaNacimiento = $FilaResultado['DETCLI_CDETALLE'];
                    $FechaNacimiento = date("Y-m-d", strtotime($FechaNacimiento));

                    //Calcular Edad
                    $Fecha = time() - strtotime($FechaNacimiento);
                    $Edad = floor($Fecha / 31556926);
                }
            } else {
                $CodigoFechaNacimiento = "";
                $FechaNacimiento = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }



        //Consulta Estado Siafp
        $TIPOCONSULTA = "DescripcionSiafpCliente";
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoDescripcionSiafpCliente = $FilaResultado['PKDETCLI_NCODIGO'];
                    $DescripcionSiafpCliente = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $CodigoDescripcionSiafpCliente = $FilaResultado['PKDETCLI_NCODIGO'];
                $DescripcionSiafpCliente = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta PreguntaInteresadoCredito

        $TIPOCONSULTA = "PreguntaInteresadoCredito";
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoPreguntaInteresadoCredito = $FilaResultado['PKDETCLI_NCODIGO'];
                    $PreguntaInteresadoCredito = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
                $CodigoPreguntaInteresadoCredito = "";
                $PreguntaInteresadoCredito = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }


        //Consulta PreguntaInteresadoCartera
        $TIPOCONSULTA = "PreguntaInteresadoCartera";
        $PreguntaInteresadoCartera = "";
        $CodigoPreguntaInteresadoCartera = "";
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $CodigoPreguntaInteresadoCartera = $FilaResultado['PKDETCLI_NCODIGO'];
                    $PreguntaInteresadoCartera = $FilaResultado['DETCLI_CDETALLE'];
                }
            } else {
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Direcciones Domicilio Cliente
        $TIPOCONSULTA = "CelularCliente";
        $LineasMoviles = array();
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($LineasMoviles, array('0' => $FilaResultado['PKDETCLI_NCODIGO'], '1' => $FilaResultado['DETCLI_CDETALLE']));
                }
            } else {
                $LineasMoviles = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Direcciones Domicilio Cliente
        $TIPOCONSULTA = "TelefonoCliente";
        $LineasFijas = array();
        $ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CDETALLE != '' AND DETCLI_CESTADO = '" . $datos . "';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($LineasFijas, array('0' => $FilaResultado['PKDETCLI_NCODIGO'], '1' => $FilaResultado['DETCLI_CDETALLE'], '2' => $FilaResultado['DETCLI_CDETALLE1'], '3' => $FilaResultado['DETCLI_CDETALLE2']));
                }
            } else {
                $LineasFijas = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Numeros de contacto encargado RRHH
        $TIPOCONSULTA = "EncargadoRRHH";
        $NumeroContactoRRHH = array();
        $ConsultaSQL = "SELECT PKDETEMP_NCODIGO, DETEMP_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE DETEMP_CDETALLE IS NOT NULL AND FKDETEMP_NCLI_NCODIGO = " . $CODIGOPEMPRESA . " AND DETEMP_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETEMP_CESTADO = '" . $datos . "'";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($NumeroContactoRRHH, array('0' => $FilaResultado['PKDETEMP_NCODIGO'], '1' => $FilaResultado['DETEMP_CDETALLE2']));
                }
            } else {
                //echo "No hay Resultados y hay que realizar la otra consulta";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta nombre y correo encargado RRHH
        $TIPOCONSULTA = "EncargadoRRHH";
        $ConsultaSQL = "SELECT PKDETEMP_NCODIGO, FKDETEMP_NCLI_NCODIGO, DETEMP_CDETALLE, DETEMP_CDETALLE1, DETEMP_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE DETEMP_CDETALLE IS NOT NULL AND DETEMP_CDETALLE1 IS NOT NULL AND FKDETEMP_NCLI_NCODIGO = " . $CODIGOPEMPRESA . " AND DETEMP_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETEMP_CESTADO = '" . $datos . "'";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $PKDETEMP_NCODIGO = $FilaResultado['PKDETEMP_NCODIGO'];
                    $FKDETEMP_NCLI_NCODIGO = $FilaResultado['FKDETEMP_NCLI_NCODIGO'];
                    $NombreRRHH = $FilaResultado['DETEMP_CDETALLE'];
                    $MailRRHH = $FilaResultado['DETEMP_CDETALLE1'];
                    $DETEMP_CDETALLE3 = $FilaResultado['DETEMP_CDETALLE2'];
                }
            } else {
                $NombreRRHH = "";
                $MailRRHH = "";
                $DETEMP_CDETALLE3 = "";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_free_result($ResultadoSQL);
            mysqli_close($ConexionSQL);
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Numero movil de la Corporacion
        $TIPOCONSULTA = "CelularEmpresa";
        $NumeroMovilEmpresa = array();
        $ConsultaSQL = "SELECT PKDETEMP_NCODIGO, DETEMP_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE FKDETEMP_NCLI_NCODIGO = " . $CODIGOPEMPRESA . " AND DETEMP_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETEMP_CESTADO = '" . $datos . "'";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($NumeroMovilEmpresa, array('0' => $FilaResultado['PKDETEMP_NCODIGO'], '1' => $FilaResultado['DETEMP_CDETALLE']));
                }
            } else {
                //echo "No hay Resultados y hay que realizar la otra consulta";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }

        //Consulta Numero Fijo Corporacion
        $TIPOCONSULTA = "TelefonoEmpresa";
        $NumeroFijoCorporacion = array();
        $ConsultaSQL = "SELECT PKDETEMP_NCODIGO, DETEMP_CDETALLE, DETEMP_CDETALLE1, DETEMP_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE FKDETEMP_NCLI_NCODIGO = " . $CODIGOPEMPRESA . " AND DETEMP_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETEMP_CESTADO = '" . $datos . "'";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    array_push($NumeroFijoCorporacion, array('0' => $FilaResultado['PKDETEMP_NCODIGO'], '1' => $FilaResultado['DETEMP_CDETALLE'], '2' => $FilaResultado['DETEMP_CDETALLE1'], '3' => $FilaResultado['DETEMP_CDETALLE2']));
                }
            } else {
                //echo "No hay Resultados y hay que realizar la otra consulta";
            }
        } else {
            $ErrorConsulta = mysqli_error($ConexionSQL);
            mysqli_close($ConexionSQL);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            echo "<script>window.location='logout.php';</script>";
            exit;
        }
    }


    $CodigosSiafp = "";
    $ConsultaSQL = "SELECT EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'SIAFP' AND EST_CESTADO = 'Activo'";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $CodigosSiafp = $CodigosSiafp . '<option value="' . $FilaResultado['EST_CDETALLE1'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
            }
        } else {
            //Sin resultados
        }
    } else {
        //Falla en consulta
    }


    //Consulta Siafp
    $DescripcionSiafpCliente = "DescripcionSiafpCliente";
    $Datos = "Activo";
    $SIAFP = "SIAFP";
    $ConsultaSQL = "SELECT DETCLI_CDETALLE, EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE, u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND EST_CCONSULTA = '" . $SIAFP . "' AND DETCLI_CCONSULTA = '" . $DescripcionSiafpCliente . "' AND EST_CDETALLE = DETCLI_CDETALLE AND DETCLI_CESTADO = '" . $Datos . "' AND EST_CESTADO = '" . $Datos . "';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ResultadoSiafp = $FilaResultado['EST_CDETALLE1'];
                $TituloResultado = $FilaResultado['DETCLI_CDETALLE'];
            }
        } else {
            //Sin resultados
            $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $DescripcionSiafpCliente . "' AND DETCLI_CESTADO = '" . $Datos . "';";
            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if ($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $TituloResultado = $FilaResultado['DETCLI_CDETALLE'];
                        
                        $ConsultaSQL = "SELECT EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CDETALLE = '". $TituloResultado ."' AND EST_CESTADO= 'Activo';";
                        if($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                            $CantidadResultados = $ResultadoSQL->num_rows;
                            if ($CantidadResultados > 0) {
                                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                                    $ResultadoSiafp = $FilaResultado['EST_CDETALLE1'];
                                }
                            }else{
                                $ResultadoSiafp = "VIABILIDAD NO CARGADA EN LA BASE";
                            }
                        }else{
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            mysqli_close($ConexionSQL);
                            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                        }
                    }
                } else {
                    //Sin Resultados
                    $TituloResultado = "SIN INFORMACION";
                    $ResultadoSiafp = "SIN INFORMACION";
                }
            } else {
                //Error en la consulta
                $ErrorConsulta = mysqli_error($ConexionSQL);
                mysqli_close($ConexionSQL);
                echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
                exit;
            }
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($ConexionSQL);
        echo $ErrorConsulta;
    }

    

    //Lista tipos de documento empresas
    $ListaTiposDocumentosEmpresas = "";
    $ConsultaSQL = "SELECT EMP_CTIPO_DOCUMENTO FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE EMP_CESTADO = '" . $Datos . "' GROUP BY EMP_CTIPO_DOCUMENTO;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaTiposDocumentosEmpresas = $ListaTiposDocumentosEmpresas . '<option value="' . $FilaResultado['EMP_CTIPO_DOCUMENTO'] . '">' . $FilaResultado['EMP_CTIPO_DOCUMENTO'] . '</option>';
            }
        } else {
            //Sin Resultados
            $ListaTiposDocumentosEmpresas = "";
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    //Consulta Paises 
    $ListadoPaises = "";
    $ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE  EST_CCONSULTA = 'cmbCiudades' AND EST_CESTADO = 'Activo' GROUP BY EST_CDETALLE;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListadoPaises = $ListadoPaises . '<option value="' . $FilaResultado['EST_CDETALLE'] . '">' . $FilaResultado['EST_CDETALLE'] . '</option>';
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

    //Consulta Fecha de ingreso laboral 
    $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = 'FechaIngresoLaboralCliente' AND DETCLI_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $FechaIngresoLaboral = $FilaResultado['DETCLI_CDETALLE'];
            }
        } else {
            //Sin Resultados
            $FechaIngresoLaboral = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    //Consulta de fondo al que se envia
    $ListadoFondos = "";
    $ConsultaSQL = "SELECT PKEST_NCODIGO, EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbFondosPension' AND EST_CESTADO = 'Activo' ;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListadoFondos = $ListadoFondos . '<option value="' . $FilaResultado['EST_CDETALLE'] . '">' . $FilaResultado['EST_CDETALLE'] . '</option>';
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

    //Consulta de Estado Atencion
    $ListadoEstadoAtencion = "";
    $ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbAtencionCall' AND EST_CESTADO = 'Activo' GROUP BY EST_CDETALLE;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListadoEstadoAtencion = $ListadoEstadoAtencion . '<option value="' . $FilaResultado['EST_CDETALLE'] . '">' . $FilaResultado['EST_CDETALLE'] . '</option>';
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

    //Listado Contacto
    $ListadoContacto = "";
    $ConsultaSQL = "SELECT DISTINCT EST_CDETALLE1, EST_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbAtencionCall' AND EST_CDETALLE = 'Contacto' AND EST_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListadoContacto = $ListadoContacto . '<option value="' . $FilaResultado['EST_CDETALLE2'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
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

    //Listado No Contacto
    $ListadoNoContacto = "";
    $ConsultaSQL = "SELECT DISTINCT EST_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbAtencionCall' AND EST_CDETALLE = 'No Contacto' AND EST_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListadoNoContacto = $ListadoNoContacto . '<option value="' . $FilaResultado['EST_CDETALLE1'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
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

    //Listado No Aplica
    $ListadoNoAplica = "";
    $ConsultaSQL = "SELECT DISTINCT EST_CDETALLE1, EST_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbAtencionCall' AND EST_CDETALLE = 'No Aplica' AND EST_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListadoNoAplica = $ListadoNoAplica . '<option value="' . $FilaResultado['EST_CDETALLE1'] . '">' . $FilaResultado['EST_CDETALLE1'] . '</option>';
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

    //Consulta Valor Empresa
    $ValorEmpresa = "";
    $ConsultaSQL = "SELECT DETEMP_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE DETEMP_CCONSULTA= 'ValorEmpresa' AND FKDETEMP_NCLI_NCODIGO= '". $CODIGOEMPRESA ."' AND DETEMP_CESTADO= 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ValorEmpresa = $FilaResultado['DETEMP_CDETALLE'];
            }
        } else {
            //Sin Resultados
            $ValorEmpresa = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    //Consulta Lista Valor Empresa
    $ListaValorEmpresa = "";
    $ConsultaSQL = "SELECT EST_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE EST_CCONSULTA = 'cmbValorEmpresa' AND EST_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaValorEmpresa = $ListaValorEmpresa . '<option value="' . $FilaResultado['EST_CDETALLE'] . '">' . $FilaResultado['EST_CDETALLE'] . '</option>';
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

    //Consulta Fecha Expedicion Documento
    $TIPOCONSULTA = "FechaExpedicionDocumento";
    $ConsultaSQL = "SELECT DISTINCT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LLAVECONSULTA . "' AND DETCLI_CCONSULTA = '" . $TIPOCONSULTA . "' AND DETCLI_CESTADO = 'Activo'";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $FechaExpedicionDocumento = $FilaResultado['DETCLI_CDETALLE'];
                $FechaExpedicionDocumento = date("Y-m-d", strtotime($FechaExpedicionDocumento));
                
            }
        } else {
            $FechaExpedicionDocumento = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_free_result($ResultadoSQL);
        mysqli_close($ConexionSQL);
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    //Consulta Observaciones Anteriores
    $ObservacionesAnteriores = "";
    $ConsultaSQL = "SELECT PENCAL_COBSERVACIONES FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKCLI_NCODIGO = '". $LLAVECONSULTA ."' AND FKPENCAL_NPKPER_NCODIGO ='". $AGENTE ."' AND PENCAL_CESTADO= 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ObservacionesAnteriores = $FilaResultado['PENCAL_COBSERVACIONES'];
                
            }
        } else {
            $ObservacionesAnteriores = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_free_result($ResultadoSQL);
        mysqli_close($ConexionSQL);
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    //Consulta tiempo entre cambios de fondo
    $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LLAVECONSULTA ."' AND DETCLI_CCONSULTA = 'TiempoEntreCambios' AND DETCLI_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $TiempoEntreCambios = $FilaResultado['DETCLI_CDETALLE'];
            }
        } else {
            //Sin Resultados
            $TiempoEntreCambios = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }


    //Consulta cantidad de cambios de fondo
    $ConsultaSQL = "SELECT DETCLI_CDETALLE FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LLAVECONSULTA ."' AND DETCLI_CCONSULTA = 'CantidadDeCambios' AND DETCLI_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $CantidadCambios = $FilaResultado['DETCLI_CDETALLE'];
            }
        } else {
            //Sin Resultados
            $CantidadCambios = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }


    //Consulta Tabla cambios de fondo
    $Datos2 = array();
    $ConsultaSQL = "SELECT DETCLI_CDETALLE, DETCLI_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO= '". $LLAVECONSULTA ."' AND DETCLI_CCONSULTA= 'FondosAnteriores';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $FondoAnterior = $FilaResultado['DETCLI_CDETALLE'];
                $FechaAnterior = $FilaResultado['DETCLI_CDETALLE1'];

                array_push($Datos2, array("0" => $FondoAnterior, "1" => $FechaAnterior));
            }
        } else {
            //Sin Resultados
            $FondoAnterior = "";
            $FechaAnterior = "";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_free_result($ResultadoSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

} else {
    $CASOS = 0;
    $CODIGOPEMPRESA = "";
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
    <title> Formulario De Actualizacion :: Futurus </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="../images/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/color_skins.css">
    <link rel="stylesheet" href="../css/EstilosPersonalizadosPlantilla.css">
</head>

<body class="theme-cyan">
    <div id="wrapper">
        <div id="nav" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="row">
                <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
                    <nav>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 d-none d-sm-none d-md-none d-lg-block">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="#"><img width="50%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 d-block d-sm-block d-md-block d-lg-none" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="#"><img width="60%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
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
                            <h6>CONTACTO DEL CLIENTE PARA PENSIONES</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-top: 22%;margin-bottom: 3%;">
                            <h6>CONTACTO DEL CLIENTE PARA PENSIONES</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2"></div>
        </div>
    </div>

    <div id="Loading4" style="margin-left: 35%">
        <img src="../images/loading.gif">
    </div>

    <div id="main" style="margin-top: 1%;">
        <div class="row clearfix">
            <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
            </div>
            <div class="col-lg-10 col-md-10">
                <?php

                if ($CASOS == 1 && $CODIGOPEMPRESA != '') {
                    echo '
                <div class="planned_task">
                    <div class="body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                    <div class="row">
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">NOMBRE CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $NOMBRECOMPLETOCLIENTE . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $CLI_CNOMBRE . '" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" data-target="#DatosAdicionalesCliente"><i class="icon-layers"></i></button>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">DOCUMENTO CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $CLI_CDOCUMENTO . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $CLI_CDOCUMENTO . '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">DIRECCIÓN CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $DIRECCIONDOMICILIO . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $DIRECCIONDOMICILIO . '" disabled="disabled">
                                            <input id="LlaveDrDomPrinCliente" name="LlaveDrDomPrinCliente" value="' . $CODIGODRDIMCLIENTEPRINCIPAL . '" hidden="hidden">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" data-target="#DatosAdicionalesDireccion"><i class="icon-layers"></i></button>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 0.5%; padding-left: 5%;"></div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">EDAD CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%; margin-top: 2%;">
                                            <input title="' .$Edad. '" id="EdadCliente" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' .$Edad. '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 4%;">
                                            <label style="font-size: 10px; margin-top: 10%;">NÚMERO CONTACTO</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $CONTACTOMOVIL . '" style="background-color: #fafffa23; margin-top: 5%;" type="text" class="form-control" required="" value="' . $CONTACTOMOVIL . '" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="margin-top: 2%;">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" data-target="#NumerosAdicionalesContacto"><i class="icon-layers"></i></button>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 0.5%; padding-left: 5%;"></div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 4%;">
                                            <label style="font-size: 10px; margin-top: 10%;">CARGO CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 4%;">
                                            <input title="' . $CARGO . '" style="background-color: #fafffa23; margin-top: 5%;" type="text" class="form-control" required="" value="' . $CARGO . '" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="padding: 0.5%; padding-left: 3%; margin-top: 2%;">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" data-target="#DatosAdicionalesEmpleos"><i class="icon-layers"></i></button>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">SALARIO CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $SALARIOCLIENTE . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $SALARIOCLIENTE . '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">IBC CLIENTE</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $IBCCLIENTE . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $IBCCLIENTE . '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">CESANTIAS</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $CESANTIAS . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $CESANTIAS . '" disabled="disabled">
                                        </div>

                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 0.5%; padding-left: 5%; margin-top: 2%;"></div>
                                        
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">CANTIDAD DE CAMBIOS</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $CantidadCambios . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $CantidadCambios . '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">TIEMPO EN MESES ENTRE FONDOS</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $TiempoEntreCambios . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $TiempoEntreCambios . '" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="margin-top: 1%;">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" data-target="#CambiosDeFondos"><i class="icon-layers"></i></button>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">

                                </div>
                                <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                    <div class="row">
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">NOMBRE DE EMPRESA</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $NOMBREEMPRESA . '" id="EmpresaFija" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $NOMBREEMPRESA . '" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" id="BtnAbrirModalEmpresa" data-target="#DatosAdicionalesEmpresa"><i class="icon-layers"></i></button>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">NÚMERO NIT</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $DOCUMENTOEMPRESA . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $DOCUMENTOEMPRESA . '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 2%;">DIRECCIÓN EMPRESA</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $DIRECCIONEMPRESA . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $DIRECCIONEMPRESA . '" disabled="disabled">
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 0.5%; padding-left: 5%;"></div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <label style="font-size: 10px; margin-top: 10%;">NÚMERO CONTACTO</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <input title="' . $TELEFONOEMPRESA . '" style="background-color: #fafffa23; margin-top: 5%;" type="text" class="form-control" required="" value="' . $TELEFONOEMPRESA . '" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="margin-top: 2%;">
                                            <button type="button" class="btn btn-futurus-v" data-toggle="modal" data-target="#NumerosAdicionalesContactoEmpresa"><i class="icon-layers"></i></button>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <label style="font-size: 10px; margin-top: 10%;">VALOR EMPRESA</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <select id="ValorEmpresa" name="ValorEmpresa" class="form-control" required="" value="'. $ValorEmpresa .'">
                                                <option value="'. $ValorEmpresa .'" selected="" hidden="" disabled="">'. $ValorEmpresa .'</option>
                                                ' . $ListaValorEmpresa . '
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 0.5%; padding-left: 5%;"></div>
                                        
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0.5%; padding-left: 5%; margin-top: 3%;">
                                            <label>DESCRIPCION SIAFP</label>
                                        </div>
                                        <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10" style="padding: 0.5%; padding-left: 5%;">
                                            <input id="DescripcionSiafpCliente2" name="DescripcionSiafpCliente2" required="" title="' . $TituloResultado . '" style="background-color: #fafffa23;" type="text" class="form-control" required="" value="' . $TituloResultado . '"></input>
                                        </div>

                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                            <label style="font-size: 10px; margin-top: 10%;">ESTADO VIABILIDAD SIAFP</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                            <input id="EstadoClienteSiafp" title="' . $ResultadoSiafp . '" style="background-color: #fafffa23; margin-top: 1%;" type="text" class="form-control" required="" value="' . $ResultadoSiafp . '">
                                        </div>

                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 5%; margin-top: 10%;">FONDO ACTUAL</label>
                                        </div>

                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%; margin-top: 1%;">
                                        <select id="FondoActual" name="FondoActual" class="form-control" required="">
                                            <option value="' . $FONDOACTUAL . '" selected="" hidden="" disabled="">' . $FONDOACTUAL . '</option>
                                            ' . $ListadoFondos . '
                                        </select>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                            <label style="font-size: 10px; margin-top: 10%;">FONDO AL QUE SE ENVÍA</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;">
                                        <select id="EstadoCliente" name="EstadoCliente" class="form-control" required="">
                                            <option selected="" hidden="" disabled="">Opciones</option>
                                            ' . $ListadoFondos . '
                                        </select>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="margin-top: 2%;"></div>

                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                            <label style="font-size: 10px; margin-top: 10%;">ESTADO ATENCIÓN</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%; margin-top: 2%;" hidden="true">
                                            <select id="EstadoAtencion" name="EstadoAtencion" class="form-control" required="">
                                                <option selected="" hidden="" disabled="">Opciones</option>
                                                ' . $ListadoEstadoAtencion . '
                                            </select>
                                        </div>
                                        <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                            <label style="font-size: 10px; margin-top: 10%; padding-left: 3%;">DETALLE ATENCIÓN</label>
                                        </div>
                                        <div class="col-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                            <select id="SubObservacion" name="SubObservacion" class="form-control" required="">
                                                <option selected="" hidden="" disabled="">Opciones</option>
                                            </select>
                                        </div>

                                        <div id="TituloFechaGestion" class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%;">
                                            <label style="font-size: 10px; margin-top: 10%; margin-left: 15%;">FECHA GESTION</label>
                                        </div>
                                        <div id="FechaGestion" class="col-10 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="padding: 0.5%; margin-top: 2%">
                                            <input id="FechaRegistro" title="20/07/2020" style="background-color: #fafffa23;" type="datetime-local" class="form-control" required="" value="">
                                        </div>
                                        <div id="TituloFechaFuturo" class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%;">
                                            <label style="font-size: 10px; margin-top: 10%; margin-left: 15%;">FECHA-MES VIABILIDAD</label>
                                        </div>
                                        <div id="FechaFuturo" class="col-10 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="padding: 0.5%; margin-top: 2%">
                                            <input id="FechaFuturo2" title="20/07/2020" style="background-color: #fafffa23;" type="date" class="form-control" required="" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="margin-top: 1.6%;"></div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                    <label>OBSERVACIONES DE LA LLAMADA</label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0.5%; padding-left: 5%;" hidden="true">
                                    <textarea id="NotasAdicionales" name="NotasAdicionales" title="' . $ObservacionesAnteriores . '" maxlength="2892" class="form-control" rows="0" cols="30" required="">' . strval($ObservacionesAnteriores) . '</textarea>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center; margin-top: 1%;">
                                    <button id="Registro" name="Registro" type="button" class="btn btn-futurus-r">Actualizar Informacion</button>
                                </div>
                            </div>
                        </form>
                    </div>';
                } else {
                    echo '<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center; margin-top: 1%;">
                            <h4>¡NO HAY CASOS ASIGNADOS!</h4>
                            <h6>POR FAVOR, ACTUALICE LA PAGINA</h6>
                        </div>';
                }
                ?>

            </div>
        </div>
    </div>

    <div style="background-color: #ffffff1a;" class="modal" id="DatosAdicionalesCliente" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos Adicionales Del Cliente</h4>
                </div>
                <div id="Loading" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="Adiciones_Cliente">
                    <div class="row">
                        <div class="col-12 col-sm-12 co-md-12 col-lg-12 col-xl-12">
                            <div class="planned_task">
                                <div class="body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="header">
                                            </div>
                                            <div class="body table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <div class="row">
                                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                                            <button id="AddCorreoCliente" type="button" class="btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                                        </div>
                                                    </div>
                                                    <thead>
                                                        <tr>
                                                            <th>Correo Electronico Cliente</th>
                                                            <th style="text-align: center;">Modificar</th>
                                                            <th style="text-align: center;">Eliminar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="CorreosCliente">
                                                        <?php
                                                        for ($i = 0; $i < count($CorreosCliente); $i++) {
                                                            echo ('<tr id="ContenedorInformacion' . $CorreosCliente[$i][0] . '">');

                                                            echo ('<td id="MailClienteModificar' . $CorreosCliente[$i][0] . '">
                                                                        <p id="TextoMailCliente' . $CorreosCliente[$i][0] . '">' . $CorreosCliente[$i][1] . '</p>
                                                                    </td>
                                                                    <th id="ControlCamviosMialCliente' . $CorreosCliente[$i][0] . '" style="text-align: center;"><button id="CofigoActualizacioMailCliente' . $CorreosCliente[$i][0] . '" type="button" class="btn btn-futurus-r MailCliente"><i class="fa  icon-note"></i></button></th>
                                                                    <th id="ControlCancelacionesMail' . $CorreosCliente[$i][0] . '" style="text-align: center;"><button id="EliminarCorreoCliente' . $CorreosCliente[$i][0] . '" type="button" class="btn btn-futurus-r EliminarCorreoCliente"><i class="fa icon-close"></i></button></th>
                                                            ');

                                                            echo ('</tr>');
                                                        }
                                                        ?>
                                                        <tr id="NuevoCorreoCliente">
                                                            <td><input id="ValorCorreCliente" name="ValorCorreCliente" type="mail" maxlength="225" class="form-control transparencia" required=""></td>
                                                            <td style="text-align: center;"><button id="CapturaCorreoCliente" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                                            <td style="text-align: center;"><button id="CancelarCorreoNuevoCliente" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Primer Nombre</label>
                                                <input id="PrimerNombreCliente" onkeyup="this.value = this.value.toUpperCase();" maxlength="225" name="PrimerNombreCliente" type="text" class="form-control transparencia" required="" value="<?php echo $CLI_CNOMBRE; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Segundo Nombre</label>
                                                <input id="SegundoNombreCliente" onkeyup="this.value = this.value.toUpperCase();" maxlength="225" name="SegundoNombreCliente" type="text" class="form-control transparencia" required="" value="<?php echo $CLI_CNOMBRE2; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Primer Apellido</label>
                                                <input id="PrimerApellidoCliente" onkeyup="this.value = this.value.toUpperCase();" maxlength="225" name="PrimerApellidoCliente" type="text" class="form-control transparencia" required="" value="<?php echo $CLI_CAPELLIDO; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Segundo Apellido</label>
                                                <input id="SegundoApellidoCliente" onkeyup="this.value = this.value.toUpperCase();" maxlength="225" name="SegundoApellidoCliente" type="text" class="form-control transparencia" required="" value="<?php echo $CLI_CAPELLIDO2; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Edad Cliente</label>
                                                <input id="EdadCliente" name="EdadCliente" type="text" class="form-control transparencia" required="" value="<?php echo $Edad; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Estado Civil</label>
                                                <select class="form-control" id="EstadoCivil" name="EstadoCivil" required="" value="<?php echo $EstadoCivilActual; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Fecha Nacimiento</label>
                                                <input id="FechaNacimiento" name="FechaNacimiento" type="date" class="form-control transparencia" required="" value="<?php echo $FechaNacimiento; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Fecha Expedición Documento</label>
                                                <input id="FechaExpedicion" name="FechaExpedicion" type="date" class="form-control transparencia" required="" value="<?php echo $FechaExpedicionDocumento; ?>">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="form-group">
                                                <label>Descripcion Siafp</label>
                                                <input id="DescripcionSiafpCliente" name="DescripcionSiafpCliente" type="text" maxlength="225" class="form-control transparencia" value="<?php echo $TituloResultado; ?>" required="">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label class="fancy-checkbox">
                                                <input id="TipoInteresCredito" name="TipoInteresCredito" value="Si" type="checkbox" required="" data-parsley-errors-container="#error-checkbox" data-parsley-multiple="checkbox">
                                                <span>Interesado en Credito</span>
                                            </label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label class="fancy-checkbox">
                                                <input id="TipoInteresCartera" name="TipoInteresCartera" value="Si" type="checkbox" required="" data-parsley-errors-container="#error-checkbox" data-parsley-multiple="checkbox">
                                                <span>Interesado en Cartera</span>
                                            </label>
                                        </div>
                                        <input type="text" id="CodigoEstadoCivil" name="CodigoEstadoCivil" value="<?php echo $CodigoEstadoCivilActual; ?>" hidden="hidden">
                                        <input type="text" id="CodigoFechaNacimiento" name="CodigoFechaNacimiento" value="<?php echo $CodigoFechaNacimiento; ?>" hidden="hidden">
                                        <input type="text" id="CodigoDescripcionSiafpCliente" name="CodigoDescripcionSiafpCliente" value="<?php echo $CodigoDescripcionSiafpCliente; ?>" hidden="hidden">
                                        <input type="text" id="CodigoPreguntaInteresadoCredito" name="CodigoPreguntaInteresadoCredito" value="<?php echo $CodigoPreguntaInteresadoCredito; ?>" hidden="hidden">
                                        <input type="text" id="CodigoPreguntaInteresadoCartera" name="CodigoPreguntaInteresadoCartera" value="<?php echo $CodigoPreguntaInteresadoCartera; ?>" hidden="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="ActualizarDatosAdicionalesCliente" type="button" class="btn btn-futurus-r">Actualizar</button>
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="DatosAdicionalesDireccion" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos Adicionales Direccion</h4>
                </div>
                <div id="Loading5" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="ModalDireccion">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Direcciones Cliente</h5>
                                </div>
                            </div>
                            <div class="header">
                            </div>
                            <div class="body">
                                <table class="table table-responsive table table-bordered table-striped table-hover">

                                    <div class="row">
                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <button id="MostrarNuevaDrDomicilio" type="button" class="MostrarNuevaDrDomicilio btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th>Direcciones Cliente</th>
                                            <th>Pais</th>
                                            <th>Departamento</th>
                                            <th>Ciudad</th>
                                            <th>Barrio</th>
                                            <th>Fijar</th>
                                            <th style="text-align: center;">Modificar</th>
                                            <th style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="DatosAdicionalesDireccionDomicilio">
                                        <?php
                                        if ($DirecconesDomicilioCliente == "") {
                                        } else {
                                            for ($i = 0; $i < count($DirecconesDomicilioCliente); $i++) {
                                                echo ('<tr id="ContenedorInformacionDrDomicilio' . $DirecconesDomicilioCliente[$i][0] . '">');

                                                echo ('
                                                <td id="DireccionActualDomicilio' . $DirecconesDomicilioCliente[$i][0] . '"><p id="TextoDrDomicilio' . $DirecconesDomicilioCliente[$i][0] . '">' . $DirecconesDomicilioCliente[$i][1] . '</p></td>
                                                <td id="PaisActualDomicilio' . $DirecconesDomicilioCliente[$i][0] . '"><p id="TextoPaisDomicilio' . $DirecconesDomicilioCliente[$i][0] . '">' . $DirecconesDomicilioCliente[$i][2] . '</p></td>
                                                <td id="DepartammentoActualDomicilio' . $DirecconesDomicilioCliente[$i][0] . '"><p id="TextoDepartamentoDomicilio' . $DirecconesDomicilioCliente[$i][0] . '">' . $DirecconesDomicilioCliente[$i][3] . '</p></td>
                                                <td id="CiudadActualDomicilio' . $DirecconesDomicilioCliente[$i][0] . '"><p id="TextoCiudadDomicilio' . $DirecconesDomicilioCliente[$i][0] . '">' . $DirecconesDomicilioCliente[$i][4] . '</p></td>
                                                <td id="BarropActualDomicilio' . $DirecconesDomicilioCliente[$i][0] . '"><p id="TextoBarrioDomicilio' . $DirecconesDomicilioCliente[$i][0] . '">' . $DirecconesDomicilioCliente[$i][5] . '</p></td>
                                                <td id="FijarDatosAdicionalesDireccionDomicilio' . $DirecconesDomicilioCliente[$i][0] . '" style="text-align: center;"><button id="ConfirmacionDefinirComoPrincipal' . $DirecconesDomicilioCliente[$i][0] . '" type="button" class="btn btn-futurus-r ConfirmacionDefinirComoPrincipal"><i class="fa icon-pin"></i></button></td>
                                                <td id="ControlCambiosDatosAdicionalesDireccionDomicilio' . $DirecconesDomicilioCliente[$i][0] . '" style="text-align: center;"><button id="ModificarDireccionDomicilioCliente' . $DirecconesDomicilioCliente[$i][0] . '" type="button" class="btn btn-futurus-r ModificarDireccionDomicilioCliente"><i class="fa icon-note"></i></button></td>
                                                <td id="ControlCancelacionesDatosAdicionalesDireccionDomicilio' . $DirecconesDomicilioCliente[$i][0] . '" style="text-align: center;"><button id="EliminarDireccionDomicilioCliente' . $DirecconesDomicilioCliente[$i][0] . '" type="button" class="btn btn-futurus-r EliminarDireccionDomicilioCliente"><i class="fa icon-close"></i></button></td>
                                                ');

                                                echo ('</tr>');
                                            }
                                        }
                                        ?>
                                        <tr id="NuevaDrDomicilio">
                                            <td><input id="DireccionDomCliente" name="DireccionDomCliente" type="text" maxlength="225" class="form-control transparencia" required=""></td>
                                            <td>
                                                <select id="PaisCliente" name="PaisCliente" class="NewPaisCliente form-control transparencia" required="">
                                                    <option value="">Seleccionar una opcion</option>
                                                    <?php echo $ListadoPaises; ?>
                                                </select>
                                            </td>
                                            <td><select id="DepartamentoCliente" name="DepartamentoCliente" class="form-control NewDepartamentoCliente" required="">
                                                    <option value="">Seleccionar una opcion</option>
                                                </select></td>
                                            <td><select id="CiudadCliente" name="CiudadCliente" maxlength="225" class="form-control transparencia" required="">
                                                    <option value="">Seleccionar una opcion</option>
                                                </select></td>
                                            <td><input id="BarrioCliente" name="BarrioCliente" maxlength="225" type="text" class="form-control transparencia" required=""></td>
                                            <td id="ControlCambiosDatosAdicionalesDireccionDomicilio1" style="text-align: center;"><button id="CofigoActualizacioControlCambiosDatosAdicionalesDireccionDomicilio1" type="button" class="btn btn-futurus-r DtoAdCliente"><i class="fa icon-pin"></i></button></td>
                                            <td style="text-align: center;"><button id="GuardarNuevaDireccionDomicilioCliente" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                            <td style="text-align: center;"><button id="CancelarDtoAdCliente" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="NumerosAdicionalesContacto" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos Adicionales Contacto</h4>
                </div>
                <div id="Loading6" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="ModalNum">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Celular Registrado al Cliente</h5>
                                </div>
                            </div>

                            <div class="body">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <div class="row">
                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <button id="MostrarFormularioNuevoMovil" type="button" class="btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <td>Telefono Celular</td>
                                            <th style="text-align: center;">Modificar</th>
                                            <th style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="TelefonoCelular">
                                        <?php
                                        if ($LineasMoviles == "") {
                                        } else {
                                            for ($i = 0; $i < count($LineasMoviles); $i++) {
                                                echo ('<tr  id="ContenedorInformacion' . $LineasMoviles[$i][0] . '">');

                                                echo ('<td id="MovilClienteModificar' . $LineasMoviles[$i][0] . '">
                                                                    <p id="TextoMovilCliente' . $LineasMoviles[$i][0] . '">' . $LineasMoviles[$i][1] . '</p>
                                                                </td>
                                                                <th id="ControlCamviosMovilCliente' . $LineasMoviles[$i][0] . '" style="text-align: center;"><button id="CofigoActualizacioMovilCliente' . $LineasMoviles[$i][0] . '" type="button" class="btn btn-futurus-r ModificarLineaMovil"><i class="fa  icon-note"></i></button></th>
                                                                <th id="ControlCancelacionesMovil' . $LineasMoviles[$i][0] . '" style="text-align: center;"><button id="EliminarMovilCliente' . $LineasMoviles[$i][0] . '" type="button" class="btn btn-futurus-r EliminarMovilCliente"><i class="fa icon-close"></i></button></th>
                                                            ');

                                                echo ('</tr>');
                                            }
                                        }
                                        ?>
                                        <tr id="NuenvoNumeroMovil">
                                            <td><input id="NuevomOVIL" name="NuevomOVIL" type="text" minlength=10 maxlength=10 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td style="text-align: center;"><button id="CapturaNumeroMovil" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                            <td style="text-align: center;"><button id="CancelarNuevoNumeroMovil" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Telefono Fijo</h5>
                                </div>
                            </div>
                            <div class="header">
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <div class="row">
                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <button id="MostrarFormularioNumeroFijo" type="button" class="btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th>Indicativo</th>
                                            <th>Telefono</th>
                                            <th>Extencion</th>
                                            <th style="text-align: center;">Modificar</th>
                                            <th style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="TelefonosFijos">
                                        <?php

                                        if (!($LineasFijas == "" || $LineasFijas == null)) {
                                            for ($i = 0; $i < count($LineasFijas); $i++) {
                                                echo ('<tr id="ContenedorInformacionFijos' . $LineasFijas[$i][0] . '">');
                                                echo ('<td id="IndicativoFijo' . $LineasFijas[$i][0] . '">
                                                                    <p id="TextoIndicativoFico' . $LineasFijas[$i][0] . '">' . $LineasFijas[$i][2] . '</p>
                                                                </td>
                                                                <td id="NumeroFijo' . $LineasFijas[$i][0] . '">
                                                                    <p id="TextoNumeroFijo' . $LineasFijas[$i][0] . '">' . $LineasFijas[$i][1] . '</p>
                                                                </td>
                                                                <td id="ExtencionFijo' . $LineasFijas[$i][0] . '">
                                                                    <p id="TextoExtencionFijo' . $LineasFijas[$i][0] . '">' . $LineasFijas[$i][3] . '</p>
                                                                </td>
                                                               
    
                                                                <td id="ControlCamviosNumeroFija' . $LineasFijas[$i][0] . '" style="text-align: center;"><button id="CofigoActualizacioNumeroFijo' . $LineasFijas[$i][0] . '" type="button" class="btn btn-futurus-r CofigoActualizacioNumeroFijo"><i class="fa  icon-note"></i></button></td>
                                                                <td id="ControlCancelacionesNumeroFija' . $LineasFijas[$i][0] . '" style="text-align: center;"><button id="EliminarNumeroFijo' . $LineasFijas[$i][0] . '" type="button" class="btn btn-futurus-r EliminarNumeroFijo"><i class="fa icon-close"></i></button></td>
                                                            ');

                                                echo ('</tr>');
                                            }
                                        }

                                        ?>
                                        <tr id="NuenvoNumeroFijo">
                                            <td><input id="NuevoNumeroFijoIndicativo" name="NuevoNumeroFijoIndicativo" type="text" maxlength=5 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td><input id="NuevoNumeroFijo" name="NuevoNumeroFijo" type="text" minlength=7 maxlength=7 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td><input id="NuevoNumeroFijoExtencion" name="NuevoNumeroFijoExtencion" type="text" maxlength=5 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td style="text-align: center;"><button id="CapturaNumeroFijo" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                            <td style="text-align: center;"><button id="CancelarNuevoNumeroFijo" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="DatosAdicionalesEmpleos" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos Adicionales Empleo</h4>
                </div>
                <div id="Loading2" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="ContModal2">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="FechaIngreso" style="color: #5f615fb4;">Fecha Ingreso Laboral</label>
                            <input id="FechaIngreso" name="FechaIngreso" type="date" class="form-control" value="<?php echo $FechaIngresoLaboral; ?>" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnGuardarFecha" name="btnGuardarFecha" type="button" class="btn btn-futurus-r">Guardar</button>
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="DatosAdicionalesEmpresa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos Adicionales De La Empresa</h4>
                </div>
                <div id="Loading7" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="ModalEmpresa">
                    <div class="row">
                        <input id="CodigoDatosEncargadoRRHH" name="CodigoDatosEncargadoRRHH" type="text" value="<?php echo $PKDETEMP_NCODIGO; ?>" hidden="hidden">
                        <input id="SubCosigoEncargadoRRHH" name="Codigo" type="text" value="<?php echo $FKDETEMP_NCLI_NCODIGO; ?>" hidden="hidden">
                        <input id="NumeroContactoRRHHPrincipal" name="NumeroContactoRRHHPrincipal" type="text" value="<?php echo $DETEMP_CDETALLE3; ?>" hidden="hidden">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <label for="" style="color: #5f615fb4;">Nombre de Empresa</label>
                            <select id="Empresas" name="Empresas" class="form-control" required="">
                                <?php
                                echo $SELECCION;
                                echo $LISTA;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12" style="margin-top: 3.5%; text-align:center">
                            <button id="AgregarNuevaEmpresa" type="button" class="btn btn-futurus-r" data-toggle="modal" data-target="#DatosNuevaEmpresa" style="margin-bottom: 2%;">Adicionar nueva empresa</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Nombre de Encargado RRHH</label>
                            <input id="NombreEncargadoRRHH" name="NombreEncargadoRRHH" type="text" maxlength="225" class="form-control" required="" value="<?php echo $NombreRRHH; ?>">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Correo Encargado</label>
                            <input id="CorreoEncargadoRRHH" name="CorreoEncargadoRRHH" maxlength="225" type="mail" class="form-control" required="" value="<?php echo $MailRRHH; ?>">
                        </div>
                        <div class="TablaContactoEncargadoRHH col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center; margin-top: 5%">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Contacto Encargado RHH</h5>
                                </div>
                            </div>
                            <div class="header">
                            </div>
                            <div class="body">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <div class="row">
                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <button id="MostrarFormularioMovilEmpresa" type="button" class="btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th>Numero Contacto</th>
                                            <th style="text-align: center;">Modificar</th>
                                            <th style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="NumerosDeContactoEmpresa">
                                        <?php
                                        for ($i = 0; $i < count($NumeroContactoRRHH); $i++) {
                                            echo ('<tr id="ContenedorMovilEmpresa' . $NumeroContactoRRHH[$i][0] . '">');
                                            echo ('
                                                <td id="NuemeroMovilEmpresa' . $NumeroContactoRRHH[$i][0] . '">
                                                    <p id="TextoMovilEmpresa' . $NumeroContactoRRHH[$i][0] . '">' . $NumeroContactoRRHH[$i][1] . '</p>
                                                </td>
                                                <td id="ControlCamviosMovilEmprea' . $NumeroContactoRRHH[$i][0] . '" style="text-align: center;"><button id="CofigoActualizacioMovilEmprea' . $NumeroContactoRRHH[$i][0] . '" type="button" class="btn btn-futurus-r CofigoActualizacioMovilEmprea"><i class="fa  icon-note"></i></button></td>
                                                <td id="ControlCancelacionesMovilEmprea' . $NumeroContactoRRHH[$i][0] . '" style="text-align: center;"><button id="EliminarNumeroMovilEmprea' . $NumeroContactoRRHH[$i][0] . '" type="button" class="btn btn-futurus-r EliminarNumeroMovilEmprea"><i class="fa icon-close"></i></button></td>
                                            </tr>
                                                    ');
                                            echo ('</tr>');
                                        }
                                        ?>
                                        <tr id="NuevoNuemeroMovilEmpresa">
                                            <td><input id="NueviMovilEmpresa" name="NueviMovilEmpresa" type="text" minlength=10 maxlength=10 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td style="text-align: center;"><button id="CapturaNumeroMovilEmpresa" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                            <td style="text-align: center;"><button id="CancelarNuevoNumeroMovilEmpresa" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="ActualizarDatosAdicionalesEmpresa" type="button" class="btn btn-futurus-r" onClick="window.location.reload();">Actualizar</button>
                    <button id="CerrarModalAddEmpresa" type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="DatosNuevaEmpresa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Datos De La Nueva Empresa</h4>
                </div>
                <div id="Loading9" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="CrearEmpresa">
                    <div class="row">
                        <input id="SubCosigoEncargadoRRHH" name="Codigo" type="text" value="<?php echo $FKDETEMP_NCLI_NCODIGO; ?>" hidden="hidden">
                        <input id="NumeroContactoRRHHPrincipal" name="NumeroContactoRRHHPrincipal" type="text" value="<?php echo $DETEMP_CDETALLE3; ?>" hidden="hidden">
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Tipo Documento</label>
                            <select id="TipoDocumento" name="TipoDocumento" class="form-control" required="">
                                <?php
                                echo $ListaTiposDocumentosEmpresas;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Numero documento empresa</label>
                            <input id="NumeroDocumento" name="NumeroDocumento" type="text" type="text" maxlength=20 onkeypress="return validaNumericos(event)" class="form-control" required="" value="">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Nombre Empresa</label>
                            <input id="NewNombreEmpresa" name="NewNombreEmpresa" type="text" maxlength="225" class="form-control" required="" value="">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Nombre Encargado RRHH</label>
                            <input id="NombreEncargadoEmpresaPrin" name="NombreEncargadoEmpresaPrin" type="text" maxlength="225" class="form-control" required="" value="">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Telefono Movil Empresa</label>
                            <input id="TelefonoEmpresaNueva" name="TelefonoEmpresaNueva" type="text" type="text" minlength=10 maxlength=10 onkeypress="return validaNumericos(event)" class="form-control" required="" value="0000000000">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Correo Encargado</label>
                            <input id="CorreoEncargadoEmpresa" name="CorreoEncargadoEmpresa" type="mail" maxlength="225" class="form-control" required="" value="Correo@SinDefinir.com">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 2%; padding-left: 5%;"></div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <h4 class="title text-center" id="largeModalLabel" style="color: #5f615fb4; padding: 1%;">Datos de Ubicación</h4>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <label for="" style="color: #5f615fb4;">Dirección</label>
                            <input id="DireccionNuevaEmpresa" name="DireccionNuevaEmpresa" type="text" maxlength="225" class="form-control" required="" value="">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">País</label>
                            <select id="PaisNuevaEmpresa" name="PaisNuevaEmpresa" type="text" maxlength="225" class="PaisNewEmpresa form-control" required="" value="">
                                <option value="">Seleccionar una opcion</option>
                                <?php echo $ListadoPaises; ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Departamento</label>
                            <select id="DepartamentoNuevaEmpresa" name="DepartamentoNuevaEmpresa" type="text" maxlength="225" class="DepartamentoNewEmpresa form-control" required="" value=""></select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Ciudad</label>
                            <select id="CiudadNuevaEmpresa" name="CiudadNuevaEmpresa" type="text" maxlength="225" class="CiudadNewEmpresa form-control" required="" value=""></select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Barrio</label>
                            <input id="BarrioNuevaEmpresa" name="BarrioNuevaEmpresa" type="text" maxlength="225" class="form-control" required="" value="">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 buttom-bar-line" style="padding: 2%; padding-left: 5%;"></div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <h4 class="title text-center" id="largeModalLabel" style="color: #5f615fb4; padding: 1%;">Datos de Contacto</h4>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Indicativo Fijo</label>
                            <input id="IndicativoNuevaEmpresa" name="IndicativoNuevaEmpresa" type="text" maxlength=5 onkeypress="return validaNumericos(event)" class="form-control" required="" value="">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Telefono Fijo</label>
                            <input id="TelefonoNuevaEmpresa" name="TelefonoNuevaEmpresa" type="text" minlength=7 maxlength=7 onkeypress="return validaNumericos(event)" class="form-control" required="" value="0000000">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <label for="" style="color: #5f615fb4;">Extención Fijo</label>
                            <input id="ExtencionNuevaEmpresa" name="ExtencionNuevaEmpresa" type="text" maxlength=5 onkeypress="return validaNumericos(event)" class="form-control" required="" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="CrearNuevaEmpresa" type="button" class="btn btn-futurus-r">Crear</button>
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="NumerosAdicionalesContactoEmpresa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Numeros Adicionales De Contato De La Empresa</h4>
                </div>
                <div id="Loading8" style="margin-left: 28%">
                    <img src="../images/loading.gif">
                </div>
                <div class="modal-body" id="ContEmpresa">
                    <div class="row">

                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Telefono Empresa</h5>
                                </div>
                            </div>
                            <div class="header">
                            </div>
                            <div class="body table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <div class="row">
                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <button id="FormularioFijoCorporativo" type="button" class="btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th>Indicativo</th>
                                            <th>Numero</th>
                                            <th>Extencion</th>
                                            <th style="text-align: center;">Modificar</th>
                                            <th style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($i = 0; $i < count($NumeroFijoCorporacion); $i++) {
                                            echo ('<tr id="ContenedorFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '">');

                                            echo ('<td id="FijoIndicativoCorporacionModificar' . $NumeroFijoCorporacion[$i][0] . '">
                                                                    <p id="TextoIndicativoFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '">' . $NumeroFijoCorporacion[$i][2] . '</p>
                                                                </td>
                                                                <td id="FijoCorporacionModificar' . $NumeroFijoCorporacion[$i][0] . '">
                                                                    <p id="TextoFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '">' . $NumeroFijoCorporacion[$i][1] . '</p>
                                                                </td>
                                                                <td id="FijoExtencionCorporacionModificar' . $NumeroFijoCorporacion[$i][0] . '">
                                                                    <p id="TextoFijoExtencionCorporacion' . $NumeroFijoCorporacion[$i][0] . '">' . $NumeroFijoCorporacion[$i][3] . '</p>
                                                                </td>
                                                                <td id="ControlCamviosFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '" style="text-align: center;"><button id="ConfigActualizacioFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '" type="button" class="btn btn-futurus-r ConfigActualizacioFijoCorporacion"><i class="fa  icon-note"></i></button></td>
                                                                <td id="ControlCancelacionesFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '" style="text-align: center;"><button id="EliminarFijoCorporacion' . $NumeroFijoCorporacion[$i][0] . '" type="button" class="btn btn-futurus-r EliminarFijoCorporacion"><i class="fa icon-close"></i></button></td>
                                                            ');

                                            echo ('</tr>');
                                        }
                                        ?>
                                        <tr id="FormFijoCoporacion">
                                            <td><input id="IndicativoFijoCorporacion" name="IndicativoFijoCorporacion" type="text" maxlength=5 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td><input id="NuevoNumeroFijoCorporacion" name="NuevoNumeroFijoCorporacion" type="text" minlength=7 maxlength=7 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td><input id="ExtencionFijoCorporacion" name="ExtencionFijoCorporacion" type="text" maxlength=5 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td style="text-align: center;"><button id="CapturaNumeroFijoCorporacion" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                            <td style="text-align: center;"><button id="CancelarNuevoNumeroFijoCorporacion" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Celular Empresa</h5>
                                </div>
                            </div>
                            <div class="header">
                            </div>
                            <div class="body">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <div class="row">
                                        <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <button id="MostrarFormularioMovilCoporacion" type="button" class="btn btn-futurus-r" style="margin-bottom: 2%;">Adicionar</button>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th style="text-align: center;">Modificar</th>
                                            <th style="text-align: center;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="MovilesEmpresa">
                                        <?php
                                        for ($i = 0; $i < count($NumeroMovilEmpresa); $i++) {
                                            echo ('<tr id="ContenedorMoviCorporacion' . $NumeroMovilEmpresa[$i][0] . '">');

                                            echo ('<td id="MovilMoviCorporacionModificar' . $NumeroMovilEmpresa[$i][0] . '">
                                                                    <p id="TextoMovilMoviCorporacion' . $NumeroMovilEmpresa[$i][0] . '">' . $NumeroMovilEmpresa[$i][1] . '</p>
                                                                </td>
                                                                <th id="ControlCamviosMovilMoviCorporacion' . $NumeroMovilEmpresa[$i][0] . '" style="text-align: center;"><button id="ConfigActualizacioMovilMoviCorporacion' . $NumeroMovilEmpresa[$i][0] . '" type="button" class="btn btn-futurus-r ConfigActualizacioMovilMoviCorporacion"><i class="fa  icon-note"></i></button></th>
                                                                <th id="ControlCancelacionesMovilCorporacion' . $NumeroMovilEmpresa[$i][0] . '" style="text-align: center;"><button id="EliminarMovilCorporacion' . $NumeroMovilEmpresa[$i][0] . '" type="button" class="btn btn-futurus-r EliminarMovilCorporacion"><i class="fa icon-close"></i></button></th>
                                                            ');

                                            echo ('</tr>');
                                        }
                                        ?>
                                        <tr id="FormMovilCoporacion">
                                            <td><input id="NueviMovilCorporacion" name="NueviMovilCorporacion" type="text" minlength=10 maxlength=10 onkeypress="return validaNumericos(event)" class="form-control transparencia" required=""></td>
                                            <td style="text-align: center;"><button id="CapturaNumeroMovilCorporacion" type="button" class="btn btn-futurus-r"><i class="fa icon-check"></i></button></td>
                                            <td style="text-align: center;"><button id="CancelarNuevoNumeroMovilCorporacion" type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" id="btnCerrar" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="CambiosDeFondos" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel" style="color: #5f615fb4;">Informacion De Cambios De Fondo</h4>
                </div>
                <div class="modal-body" id="ModalCambiosDeFondos">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center;">
                                    <h5 class="title" id="largeModalLabel" style="color: #5f615fb4;">Cambios De Fondo Cliente</h5>
                                </div>
                            </div>
                            <div class="header">
                            </div>
                            <div class="body">
                                <div id="TablaCambiosDeFondos" class="col-md-12 col-sm-12 col-lg-12 table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Fondo Anterior</th>
                                                <th style="text-align: center;">Fecha De Cambio</th>
                                            </tr>
                                        </thead>
                                        <tbody id="BodyTablaCambiosDeFondos">
                                            <?php

                                            for ($i = 0; $i < count($Datos2); $i++) {
                                                echo '<tr>';
                                                for ($b = 0; $b < count($Datos2[$i]); $b++) {

                                                    echo '<td style="text-align: center;">' . $Datos2[$i][$b] . '</td>';
                                                }
                                                echo '</tr>';
                                            }

                                            ?>

                                        </tbody>
                                        <tr hidden="True">
                                            <th style="text-align: center;">Fondo Anterior</th>
                                            <th style="text-align: center;">Fecha De Cambio</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #61636294; color: #ffffff;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <input type="text" id="Agente" name="Agente" value="<?php echo $AGENTE; ?>" hidden="hidden">
    <input type="text" id="CodigoCaso" name="CodigoCaso" value="<?php echo $CODIGO_CASO ?>" hidden="hidden">
    <input type="text" id="LlaveConsulta" name="LlaveConsulta" value="<?php echo $LLAVECONSULTA; ?>" hidden="hidden">
    <input type="text" id="CodigoEmpresa" name="CodigoEmpresa" value="<?php echo $CODIGOPEMPRESA; ?>" hidden="hidden">
    <!-- Javascript -->
    <script src="../bundles/libscripts.bundle.js"></script>
    <script src="../bundles/vendorscripts.bundle.js"></script>
    <script src="../bundles/mainscripts.bundle.js"></script>
    <script>

        function fechagestion(){
            estadoActualFecha = $("#FechaRegistro").val();
            console.log(estadoActualFecha)
            if(sessionStorage.getItem("fecha_gestion") == ""){
                sessionStorage.setItem("fecha_gestion", estadoActualFecha);
                return
            }           
            
        }
        
        function resetearSessionStorage(){
            window.location.reload();
            sessionStorage.removeItem("estado_cliente");
            sessionStorage.removeItem("estado_atencion");
            sessionStorage.removeItem("sub_observacion"); 
            sessionStorage.removeItem("fecha_gestion");
            sessionStorage.removeItem("notas_adicionales");
            document.getElementById("BtnAgenteCall").click();
        }



        //Añadir codigos Siafp
        $(document).ready(function() {

            //Formularios de modales Ocultos
            $("#NuevoCorreoCliente").hide();
            $("#NuevaDrDomicilio").hide();
            $("#NuevaDrOficina").hide();
            $("#NuenvoNumeroMovil").hide();
            $("#NuenvoNumeroFijo").hide();
            $("#NuevoNuemeroMovilEmpresa").hide();
            $("#FormMovilCoporacion").hide();
            $("#FormFijoCoporacion").hide();
            $("#TituloFechaGestion").hide();
            $("#FechaGestion").hide();
            $("#TituloFechaFuturo").hide();
            $("#FechaFuturo").hide();
            $('#Loading').hide();
            $('#Loading2').hide();
            $('#Loading3').hide();
            $('#Loading4').hide();
            $('#Loading5').hide();
            $('#Loading6').hide();
            $('#Loading7').hide();
            $('#Loading8').hide();
            $('#Loading9').hide();
            $(".btn-futurus-v").attr("onclick", "fechagestion()")


            //Lista desplegable 
            var EstadoCivil = [<?php echo $EstadosCiviles; ?>];
            var EstadoActual = "<?php echo $EstadoCivilActual; ?>";
            var validador = EstadoCivil.includes(EstadoActual);
            var PreguntaInteresadoCredito = "<?php echo $PreguntaInteresadoCredito; ?>";
            var PreguntaInteresadoCartera = "<?php echo $PreguntaInteresadoCartera; ?>";

            if (PreguntaInteresadoCredito == "Si") {
                $("#TipoInteresCredito").click();
            } else {
                //  
            }

            if (PreguntaInteresadoCartera == "Si") {
                $("#TipoInteresCartera").click();
            } else {
                //
            }

            if (validador == true) {
                EstadoCivil = jQuery.grep(EstadoCivil, function(value) {
                    return value != EstadoActual;
                });
                $("#EstadoCivil").append('<option value="' + EstadoActual + '">' + EstadoActual + '</option>');
                EstadoCivil.forEach(ImprecionArray);
            } else {
                $("#EstadoCivil").append('<option selected="" value="" hidden="" disabled="">Selecciona el Estado Civil</option>');
                EstadoCivil.forEach(ImprecionArray);
            }

            function ImprecionArray(item, index) {
                $("#EstadoCivil").append('<option value="' + item + '">' + item + '</option>')
            }

            if ($('#LlaveDrDomPrinCliente').val().length > 0) {
                let codDireccionFijada = $('#LlaveDrDomPrinCliente').val();
                let BotonOcultar = '#ConfirmacionDefinirComoPrincipal' + codDireccionFijada;

                $(BotonOcultar).hide();
            }
        })

        window.estadoActualFecha = "";

        

        $(document).ready(function() {

            // 1
            if(sessionStorage.getItem("estado_cliente") == null){console.log("null");}else{
                if ($("#EstadoCliente option:selected").text() == "Opciones"){
                    $("#EstadoCliente").val(sessionStorage.getItem("estado_cliente")).change();
                                
                } else{console.log("!=Opciones");}
            }
            if(sessionStorage.getItem("estado_atencion") == null){console.log("null");}else{
                if ($("#EstadoAtencion option:selected").text() == "Opciones"){
                    $("#EstadoAtencion").val(sessionStorage.getItem("estado_atencion")).change();
                                
                } else{console.log("!=Opciones");}
            }
            if(sessionStorage.getItem("sub_observacion") == null){console.log("null");}else{
                if ($("#SubObservacion option:selected").text() == "Opciones"){
                    //$("#SubObservacion").text(sessionStorage.getItem("sub_observacion")).change();
                    var sessSub_observacion = sessionStorage.getItem("sub_observacion")
                    $('#SubObservacion option:contains(' + sessSub_observacion + ')').attr('selected', true);
                                
                } else{console.log("!=Opciones");}
            }

            if(sessionStorage.getItem("fecha_gestion") == null){
                console.log("select null");
            
            }else{
                    if(sessionStorage.getItem("fecha_gestion") == ""){
                    console.log("select vacio");
                
                }else{
                    $("#FechaGestion").show();
                    $("#TituloFechaGestion").show();
                    $("#FechaRegistro").val(sessionStorage.getItem("fecha_gestion"));

                }
                
            }

            // 

            if(sessionStorage.getItem("notas_adicionales") == null){console.log("null");}else{
                $("#NotasAdicionales").val(sessionStorage.getItem("notas_adicionales"))
            }

             // 2
           
            $('#EstadoCliente').on('change', function() {
                var estadoActual = $(this).val();
                var sess_estado = sessionStorage.setItem("estado_cliente", estadoActual);
            });
            $('#EstadoAtencion').on('change', function() {
                var estadoActual = $(this).val();
                var sess_estado = sessionStorage.setItem("estado_atencion", estadoActual);
            });
            $('#SubObservacion').on('change', function() {
                var estadoActual = $("#SubObservacion option:selected").text();
                var sess_estado = sessionStorage.setItem("sub_observacion", estadoActual);
                visibilidadFechaGestion = $("#FechaGestion").is(":visible");
                
                if(visibilidadFechaGestion == true){                    
                    console.log(estadoActual);
                    var sess_estado = sessionStorage.setItem("fecha_gestion", estadoActualFecha);
                }else{
                    sessionStorage.removeItem("fecha_gestion")

                }
            });

            $('#NotasAdicionales').on('keyup', function() {
                var estadoActual = $(this).val();
                var sess_estado = sessionStorage.setItem("notas_adicionales", estadoActual);
            });

        });

        


        //Mostrar Formulario nuevo correo
        $("#AddCorreoCliente").click(function() {
            $("#NuevoCorreoCliente").show();
        })

        //Cerrar modal datos adicionales empresa
        $("#AgregarNuevaEmpresa").click(function() {
            $("#CerrarModalAddEmpresa").click();
        })

        //Eliminar Correo Cliente
        $(function() {
            $('body').on('click', '.EliminarCorreoCliente', function(event) {

                $("#Adiciones_Cliente").hide();
                $("#Loading").show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarCorreoCliente', '');
                Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminacionCorreoCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == "1") {
                        alert("¡Eliminado Correctamente!");
                        $("#Loading").hide();
                        $("#Adiciones_Cliente").show();
                        $("#ContenedorInformacion" + codigo).text("");
                    } else {
                        alert(":(  Error Al Eliminar - Algun Dato Ya No Existe  :(");
                        $("#Loading").hide();
                        $("#Adiciones_Cliente").show();
                        console.log(Resultado);
                    }
                })
            });
        });

        //Guardar Nuevo Correo
        $("#CapturaCorreoCliente").click(function() {

            $("#Adiciones_Cliente").hide();
            $("#Loading").show();

            NuevoCorreo = $("#ValorCorreCliente").val();
            Agente = $("#Agente").val();
            LlaveConsulta = $("#LlaveConsulta").val();
            event.preventDefault();
            if (NuevoCorreo == "") {
                alert("Tienes que diligenciar un correo electronico");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else {
                if (NuevoCorreo.indexOf('@', 0) == -1 || NuevoCorreo.indexOf('.', 0) == -1) {
                    alert('El correo electrónico introducido no es correcto.');
                    $("#Loading").hide();
                    $("#Adiciones_Cliente").show();
                    return false;
                } else {

                    $.ajax({
                        url: "GuardarNuevoCorreoCliente.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            NuevoCorreo: NuevoCorreo,
                            LlaveConsulta: LlaveConsulta
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "") {
                            $("#Loading").hide();
                            $("#Adiciones_Cliente").show();
                            $("#NuevoCorreoCliente").hide();
                            $("#NuevoCorreoCliente").before('<tr id="ContenedorInformacion' + Resultado + '"><td id="MailClienteModificar' + Resultado + '"><p id="TextoMailCliente' + Resultado + '">' + NuevoCorreo + '</p></td><td id="ControlCamviosMialCliente' + Resultado + '" style="text-align: center;"><button id="CofigoActualizacioMailCliente' + Resultado + '" type="button" class="btn btn-futurus-r MailCliente"><i class="fa  icon-note"></i></button></td><td id="ControlCancelacionesMail' + Resultado + '" style="text-align: center;"><button id="EliminarCorreoCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarCorreoCliente"><i class="fa icon-close"></i></button></td>');
                            $("#ValorCorreCliente").val("");
                        } else {
                            alert("Error al guardar la informacion");
                            $("#Loading").hide();
                            $("#Adiciones_Cliente").show();
                            console.log(Resultado);
                        }

                    })
                }
            }
        })

        //Camcelar Nuevo correo
        $("#CancelarCorreoNuevoCliente").click(function() {
            $("#ValorCorreCliente").val("");
            $("#NuevoCorreoCliente").hide();
        })

        //Modificar Correo
        $(function() {
            $('body').on('click', '.MailCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('CofigoActualizacioMailCliente', '');
                var ModificacionSobre = "MailClienteModificar" + codigo;
                var mail = $("#TextoMailCliente" + codigo).text();

                $("#TextoMailCliente" + codigo).hide();
                $("#ControlCamviosMialCliente" + codigo).text("");
                $("#ControlCancelacionesMail" + codigo).text("");


                $("#" + ModificacionSobre).append('<input id="ValorCorreCliente' + codigo + '" name="ValorCorreCliente" type="mail" maxlength="225" class="form-control transparencia" value="' + mail + '" required="">');
                $("#ControlCamviosMialCliente" + codigo).append('<button id="ConfirmarModificacionMailCliente' + codigo + '" type="button" class="btn btn-futurus-r ConfirmacionCambioMailCliente ' + codigo + '"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesMail" + codigo).append('<button id="ConfirmarCancelarModificacionMailCliente' + codigo + '" type="button" class="btn btn-futurus-r CancelarModificacionMailCliente ' + codigo + '"><i class="fa icon-close"></i></button>');

            });
        });

        //Confirmacion Modificacion
        $(function() {
            $('body').on('click', '.ConfirmacionCambioMailCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionMailCliente', '');
                var ModificacionSobre = "MailClienteModificar" + codigo;
                var mail = $("#ValorCorreCliente" + codigo).val();
                Agente = $("#Agente").val();
                LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();

                if (mail.indexOf('@', 0) == -1 || mail.indexOf('.', 0) == -1) {
                    alert('El correo electrónico introducido no es correcto.');
                    return false;
                } else {

                    $.ajax({
                        url: "ActualizarCorreoCliente.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            codigo: codigo,
                            mail: mail,
                            LlaveConsulta: LlaveConsulta
                        }
                    }).done(function(data) {
                        Resultado = String(data);

                        if (Resultado != "0") {
                            $("#MailClienteModificar" + codigo).text("");
                            $("#ControlCamviosMialCliente" + codigo).text("");
                            $("#ControlCancelacionesMail" + codigo).text("");

                            $("#MailClienteModificar" + codigo).append('<p id="TextoMailCliente' + Resultado + '">' + mail + '</p>');
                            $("#ControlCamviosMialCliente" + codigo).append('<button id="CofigoActualizacioMailCliente' + Resultado + '" type="button" class="btn btn-futurus-r MailCliente"><i class="fa  icon-note"></i></button>');
                            $("#ControlCancelacionesMail" + codigo).append('<button id="EliminarCorreoCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarCorreoCliente"><i class="fa icon-close"></i></button>');


                            $("#ContenedorInformacion" + codigo).attr("id", "ContenedorInformacion" + Resultado);
                            $("#MailClienteModificar" + codigo).attr("id", "MailClienteModificar" + Resultado);
                            $("#ControlCamviosMialCliente" + codigo).attr("id", "ControlCamviosMialCliente" + Resultado);
                            $("#ControlCancelacionesMail" + codigo).attr("id", "ControlCancelacionesMail" + Resultado);
                            window.location.reload();
                            

                        } else {
                            alert("Error en la actualizacion");
                            console.log(Resultado);
                        }
                    });
                }
            });

        });
        //Cancelacion Modificacion
        $(function() {
            $('body').on('click', '.CancelarModificacionMailCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionMailCliente', '');

                var mail = $("#TextoMailCliente" + codigo).text();
                $("#MailClienteModificar" + codigo).text("");
                $("#ControlCamviosMialCliente" + codigo).text("");
                $("#ControlCancelacionesMail" + codigo).text("");
                $("#MailClienteModificar" + codigo).append('<p id="TextoMailCliente' + codigo + '">' + mail + '</p>');
                $("#ControlCamviosMialCliente" + codigo).append('<button id="CofigoActualizacioMailCliente' + codigo + '" type="button" class="btn btn-futurus-r MailCliente"><i class="fa  icon-note"></i></button>');
                $("#ControlCancelacionesMail" + codigo).append('<button type="button" class="btn btn-futurus-r"><i class="fa icon-close"></i></button>');
            });
        });


        //Mostrar Formulario Nueva direccion domicilio
        $("#MostrarNuevaDrDomicilio").click(function() {
            $("#NuevaDrDomicilio").show();
        });

        //Cancelar Nueva direccion
        $("#CancelarDtoAdCliente").click(function() {
            $("#NuevaDrDomicilio").hide();
            $("#DireccionDomCliente").val("");
            $("#PaisCliente").val("");
            $("#DepartamentoCliente").val("");
            $("#CiudadCliente").val("");
            $("#BarrioCliente").val("");
        })

        //Guardar Nueva Direccion Domicilio Cliente
        $("#GuardarNuevaDireccionDomicilioCliente").click(function() {
            $("#ModalDireccion").hide();
            $("#Loading5").show();

            var DireccionDomCliente = $("#DireccionDomCliente").val();
            var PaisCliente = $("#PaisCliente").val();
            var DepartamentoCliente = $("#DepartamentoCliente").val();
            var CiudadCliente = $("#CiudadCliente").val();
            var BarrioCliente = $("#BarrioCliente").val();
            var Agente = $("#Agente").val();
            var LlaveConsulta = $("#LlaveConsulta").val();
            var ListadoPaises = '<?php echo $ListadoPaises; ?>';

            event.preventDefault();

            if ((DireccionDomCliente == "") || (PaisCliente == "") || (DepartamentoCliente == "") || (CiudadCliente == "") || (BarrioCliente == "")) {
                alert("Tienes que llenar todos los campos de la direccion nueva");
                $("#Loading5").hide();
                $("#ModalDireccion").show();
            } else {
                $.ajax({
                    url: "GuardarNuevaDrDomicilioCliente.php",
                    type: "POST",
                    data: {

                        DireccionDomCliente: DireccionDomCliente,
                        PaisCliente: PaisCliente,
                        DepartamentoCliente: DepartamentoCliente,
                        CiudadCliente: CiudadCliente,
                        BarrioCliente: BarrioCliente,
                        Agente: Agente,
                        LlaveConsulta: LlaveConsulta

                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == "0") {
                        alert("Fallo al guardar la informacion");
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();
                        console.log(Resultado);
                    } else {
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();

                        $("#NuevaDrDomicilio").hide();
                        $("#NuevaDrDomicilio").before('<tr id="ContenedorInformacionDrDomicilio' + Resultado + '"><td id="DireccionActualDomicilio' + Resultado + '"><p id="TextoDrDomicilio' + Resultado + '">' + DireccionDomCliente + '</p></td><td id="PaisActualDomicilio' + Resultado + '"><p id="TextoPaisDomicilio' + Resultado + '">' + PaisCliente + '</p></td><td id="DepartammentoActualDomicilio' + Resultado + '"><p id="TextoDepartamentoDomicilio' + Resultado + '">' + DepartamentoCliente + '</p></td><td id="CiudadActualDomicilio' + Resultado + '"><p id="TextoCiudadDomicilio' + Resultado + '">' + CiudadCliente + '</p></td><td id="BarropActualDomicilio' + Resultado + '"><p id="TextoBarrioDomicilio' + Resultado + '">' + BarrioCliente + '</p></td><td id="FijarDatosAdicionalesDireccionDomicilio' + Resultado + '" style="text-align: center;"><button id="ConfirmacionDefinirComoPrincipal' + Resultado + '" type="button" class="btn btn-futurus-r ConfirmacionDefinirComoPrincipal"><i class="fa icon-pin"></i></button></td><td id="ControlCambiosDatosAdicionalesDireccionDomicilio' + Resultado + '" style="text-align: center;"><button id="ModificarDireccionDomicilioCliente' + Resultado + '" type="button" class="btn btn-futurus-r ModificarDireccionDomicilioCliente"><i class="fa icon-note"></i></button></td><td id="ControlCancelacionesDatosAdicionalesDireccionDomicilio' + Resultado + '" style="text-align: center;"><button id="EliminarDireccionDomicilioCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarDireccionDomicilioCliente"><i class="fa icon-close"></i></button></td></tr>');
                        $("#DireccionDomCliente").val("");
                        $("#PaisCliente").val("");
                        $("#DepartamentoCliente").val("");
                        $("#CiudadCliente").val("");
                        $("#BarrioCliente").val("");
                    }
                })
            }
        })

        //Eliminar o inactivar Direccion Domicilio
        $(function() {
            $('body').on('click', '.EliminarDireccionDomicilioCliente', function(event) {

                $("#ModalDireccion").hide();
                $("#Loading5").show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarDireccionDomicilioCliente', '');
                Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminacionDrDomicilioCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == "1") {
                        alert("¡Eliminado Correctamente!");
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();
                        $("#ContenedorInformacionDrDomicilio" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();
                        console.log(Resultado);
                    }
                })
            });
        });

        //Modificar Direccion Domicilio cliente
        $(function() {
            $('body').on('click', '.ModificarDireccionDomicilioCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ModificarDireccionDomicilioCliente', '');
                var TextoDrDomicilio = $("#TextoDrDomicilio" + codigo).text();
                var TextoPaisDomicilio = $("#TextoPaisDomicilio" + codigo).text();
                var TextoDepartamentoDomicilio = $("#TextoDepartamentoDomicilio" + codigo).text();
                var TextoCiudadDomicilio = $("#TextoCiudadDomicilio" + codigo).text();
                var TextoBarrioDomicilio = $("#TextoBarrioDomicilio" + codigo).text();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                var ListadoPaises = '<?php echo $ListadoPaises; ?>';

                $("#TextoDrDomicilio" + codigo).hide();
                $("#TextoPaisDomicilio" + codigo).hide();
                $("#TextoDepartamentoDomicilio" + codigo).hide();
                $("#TextoCiudadDomicilio" + codigo).hide();
                $("#TextoBarrioDomicilio" + codigo).hide();

                $("#FijarDatosAdicionalesDireccionDomicilio" + codigo).text('');
                $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).text('');
                $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).text('');


                $("#DireccionActualDomicilio" + codigo).append('<input id="ActualizarDireccionDomCliente' + codigo + '" name="ActualizarDireccionDomCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoDrDomicilio + '" title="' + TextoDrDomicilio + '">');
                $("#PaisActualDomicilio" + codigo).append('<select id="ActualizarPaisCliente' + codigo + '" name="ActualizarPaisCliente" class="form-control ActualizarPaisCliente" required=""><option value="">Seleccionar una opcion</option>' + ListadoPaises + '</select>');


                $("#PaisActualDomicilio" + codigo + " option[value='" + TextoPaisDomicilio + "']").attr("selected", "true");
                $("#DepartammentoActualDomicilio" + codigo).append('<select id="ActualizarDepartamentoCliente' + codigo + '" name="ActualizarDepartamentoCliente" class="form-control ActualizarDepartamentoCliente" required=""><option value="">Seleccionar una opcion</option></select>');
                $("#CiudadActualDomicilio" + codigo).append('<select id="ActualizarCiudadCliente' + codigo + '" name="ActualizarCiudadCliente" class="form-control ActualizarCiudadCliente" required=""><option value="">Seleccionar una opcion</option></select>');
                $("#BarropActualDomicilio" + codigo).append('<input id="ActualizarBarrioCliente' + codigo + '" name="ActualizarBarrioCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoBarrioDomicilio + '" title="' + TextoBarrioDomicilio + '">');

                $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="ConfirmarModificacionDrDomicilioCliente' + codigo + '" type="button" class="btn btn-futurus-r ConfirmacionCambioDrDomicilioCliente"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="ConfirmarCancelarModificacionDrDomCliente' + codigo + '" type="button" class="btn btn-futurus-r CancelarModificacionDrDomCliente"><i class="fa icon-close"></i></button>');


                if (TextoPaisDomicilio != "") {
                    let form_data = new FormData();
                    form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                    $.ajax({
                        url: "ConsultaDepartamentos.php",
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: form_data,
                        success: function(php_response) {
                            Respuesta = php_response.msg;
                            if (Respuesta == "Ok") {
                                $("#ActualizarDepartamentoCliente" + codigo).append(php_response.Resultado);
                                $("#ActualizarDepartamentoCliente" + codigo + " option[value='" + TextoDepartamentoDomicilio + "']").attr("selected", "true");
                            } else if (Respuesta == "SinResultados") {
                                alert("No se encontraron departamentos de este pais, consultar con el administrador de sistema!");
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

                if (TextoDepartamentoDomicilio != "") {
                    let form_data = new FormData();
                    form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                    form_data.append('TextoDepartamentoDomicilio', TextoDepartamentoDomicilio);
                    $.ajax({
                        url: "ConsultaCiudades.php",
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: form_data,
                        success: function(php_response) {
                            Respuesta = php_response.msg;
                            if (Respuesta == "Ok") {
                                $("#ActualizarCiudadCliente" + codigo).append(php_response.Resultado);
                                $("#ActualizarCiudadCliente" + codigo + " option[value='" + TextoCiudadDomicilio + "']").attr("selected", "true");
                            } else if (Respuesta == "SinResultados") {
                                alert("No se encontraron ciudades de este departamento, consultar con el administrador de sistema!");
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

            });
        });

        //Consulta de Departamentos x pais seleccionado
        $("body").on("change", '.ActualizarPaisCliente', function() {
            event.preventDefault();
            let form_data = new FormData();
            var id = $(this).attr('id');
            var TextoPaisDomicilio = $("#" + id).val();
            var codigo = id.replace("ActualizarPaisCliente", "");
            if (TextoPaisDomicilio == "") {
                alert("Se tiene que seleccionar una opcion valida!");
            } else {
                let form_data = new FormData();
                form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                $.ajax({
                    url: "ConsultaDepartamentos.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        if (Respuesta == "Ok") {
                            $("#ActualizarDepartamentoCliente" + codigo).text("");
                            $("#ActualizarDepartamentoCliente" + codigo).text("<option>Seleccionar una opcion</option>");
                            $("#ActualizarDepartamentoCliente" + codigo).append(php_response.Resultado);
                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron departamentos de este pais, consultar con el administrador de sistema!");
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

        //Consulta de Ciudades x departamento seleccionado
        $("body").on("change", '.ActualizarDepartamentoCliente', function() {
            event.preventDefault();
            let form_data = new FormData();
            var id = $(this).attr('id');
            var TextoDepartamentoDomicilio = $("#" + id).val();
            var codigo = id.replace("ActualizarDepartamentoCliente", "");
            var TextoPaisDomicilio = $("#ActualizarPaisCliente" + codigo).val();
            if (TextoDepartamentoDomicilio == "") {
                alert("Se tiene que seleccionar una opcion valida!");
            } else {
                let form_data = new FormData();
                form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                form_data.append('TextoDepartamentoDomicilio', TextoDepartamentoDomicilio);
                $.ajax({
                    url: "ConsultaCiudades.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        if (Respuesta == "Ok") {
                            $("#ActualizarCiudadCliente" + codigo).text("");
                            $("#ActualizarCiudadCliente" + codigo).text("<option>Seleccionar una opcion</option>");
                            $("#ActualizarCiudadCliente" + codigo).append(php_response.Resultado);
                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron ciudades de este departamento, consultar con el administrador de sistema!");
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


        //Confirmacion Modificacion direccion domicilio cliente
        $(function() {
            $('body').on('click', '.ConfirmacionCambioDrDomicilioCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionDrDomicilioCliente', '');
                var ActualizarDireccionDomCliente = $("#ActualizarDireccionDomCliente" + codigo).val();
                var ActualizarPaisCliente = $("#ActualizarPaisCliente" + codigo).val();
                var ActualizarDepartamentoCliente = $("#ActualizarDepartamentoCliente" + codigo).val();
                var ActualizarCiudadCliente = $("#ActualizarCiudadCliente" + codigo).val();
                var ActualizarBarrioCliente = $("#ActualizarBarrioCliente" + codigo).val();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();

                $.ajax({
                    url: "ActualizarDrDomicilioCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo,
                        ActualizarDireccionDomCliente: ActualizarDireccionDomCliente,
                        ActualizarPaisCliente: ActualizarPaisCliente,
                        ActualizarDepartamentoCliente: ActualizarDepartamentoCliente,
                        ActualizarCiudadCliente: ActualizarCiudadCliente,
                        ActualizarBarrioCliente: ActualizarBarrioCliente,
                        LlaveConsulta: LlaveConsulta
                    }
                }).done(function(data) {
                    Resultado = String(data);

                    if (Resultado != "0") {
                        $("#DireccionActualDomicilio" + codigo).text('');
                        $("#PaisActualDomicilio" + codigo).text('');
                        $("#DepartammentoActualDomicilio" + codigo).text('');
                        $("#CiudadActualDomicilio" + codigo).text('');
                        $("#BarropActualDomicilio" + codigo).text('');
                        $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).text('');
                        $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).text('');



                        $("#DireccionActualDomicilio" + codigo).append('<p id="TextoDrDomicilio' + Resultado + '">' + ActualizarDireccionDomCliente + '</p>');
                        $("#PaisActualDomicilio" + codigo).append('<p id="TextoPaisDomicilio' + Resultado + '">' + ActualizarPaisCliente + '</p>');
                        $("#DepartammentoActualDomicilio" + codigo).append('<p id="TextoDepartamentoDomicilio' + Resultado + '">' + ActualizarDepartamentoCliente + '</p>');
                        $("#CiudadActualDomicilio" + codigo).append('<p id="TextoCiudadDomicilio' + Resultado + '">' + ActualizarCiudadCliente + '</p>');
                        $("#BarropActualDomicilio" + codigo).append('<p id="TextoBarrioDomicilio' + Resultado + '">' + ActualizarBarrioCliente + '</p>');
                        $("#FijarDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="ConfirmacionDefinirComoPrincipal' + Resultado + '" type="button" class="btn btn-futurus-r ConfirmacionDefinirComoPrincipal"><i class="fa icon-pin"></i></button>');
                        $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="ModificarDireccionDomicilioCliente' + Resultado + '" type="button" class="btn btn-futurus-r ModificarDireccionDomicilioCliente"><i class="fa icon-note"></i></button>');
                        $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="EliminarDireccionDomicilioCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarDireccionDomicilioCliente"><i class="fa icon-close"></i></button>');


                        $("#ContenedorInformacionDrDomicilio" + codigo).attr("id", "ContenedorInformacionDrDomicilio" + Resultado);
                        $("#DireccionActualDomicilio" + codigo).attr("id", "DireccionActualDomicilio" + Resultado);
                        $("#PaisActualDomicilio" + codigo).attr("id", "PaisActualDomicilio" + Resultado);
                        $("#DepartammentoActualDomicilio" + codigo).attr("id", "DepartammentoActualDomicilio" + Resultado);
                        $("#CiudadActualDomicilio" + codigo).attr("id", "CiudadActualDomicilio" + Resultado);
                        $("#BarropActualDomicilio" + codigo).attr("id", "BarropActualDomicilio" + Resultado);
                        $("#FijarDatosAdicionalesDireccionDomicilio" + codigo).attr("id", "FijarDatosAdicionalesDireccionDomicilio" + Resultado);
                        $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).attr("id", "ControlCambiosDatosAdicionalesDireccionDomicilio" + Resultado);
                        $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).attr("id", "ControlCancelacionesDatosAdicionalesDireccionDomicilio" + Resultado);

                    } else {
                        alert("Error en la actualizacion");
                        console.log(Resultado);
                    }
                });
            });
        });

        //Cancelacion Modificacion domicilio cliente
        $(function() {
            $('body').on('click', '.CancelarModificacionDrDomCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionDrDomCliente', '');
                var TextoDrDomicilio = $("#TextoDrDomicilio" + codigo).text();
                var TextoPaisDomicilio = $("#TextoPaisDomicilio" + codigo).text();
                var TextoDepartamentoDomicilio = $("#TextoDepartamentoDomicilio" + codigo).text();
                var TextoCiudadDomicilio = $("#TextoCiudadDomicilio" + codigo).text();
                var TextoBarrioDomicilio = $("#TextoBarrioDomicilio" + codigo).text();

                $("#DireccionActualDomicilio" + codigo).text("");
                $("#PaisActualDomicilio" + codigo).text("");
                $("#DepartammentoActualDomicilio" + codigo).text("");
                $("#CiudadActualDomicilio" + codigo).text("");
                $("#BarropActualDomicilio" + codigo).text("");
                $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).text("");
                $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).text("");


                $("#DireccionActualDomicilio" + codigo).append('<p id="TextoDrDomicilio' + codigo + '">' + TextoDrDomicilio + '</p>');
                $("#PaisActualDomicilio" + codigo).append('<p id="TextoPaisDomicilio' + codigo + '">' + TextoPaisDomicilio + '</p>');
                $("#DepartammentoActualDomicilio" + codigo).append('<p id="TextoDepartamentoDomicilio' + codigo + '">' + TextoDepartamentoDomicilio + '</p>');
                $("#CiudadActualDomicilio" + codigo).append('<p id="TextoCiudadDomicilio' + codigo + '">' + TextoCiudadDomicilio + '</p>');
                $("#BarropActualDomicilio" + codigo).append('<p id="TextoBarrioDomicilio' + codigo + '">' + TextoBarrioDomicilio + '</p>');

                $("#FijarDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="FijarDatosAdicionalesDireccionDomicilio' + codigo + '" type="button" class="btn btn-futurus-r FijarDatosAdicionalesDireccionDomicilio"><i class="fa icon-pin"></i></button>');
                $("#ControlCambiosDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="ModificarDireccionOficinaCliente' + codigo + '" type="button" class="btn btn-futurus-r ModificarDireccionOficinaCliente"><i class="fa icon-note"></i></button>');
                $("#ControlCancelacionesDatosAdicionalesDireccionDomicilio" + codigo).append('<button id="EliminarDireccionOficinaCliente' + codigo + '" type="button" class="btn btn-futurus-r EliminarDireccionOficinaCliente"><i class="fa icon-close"></i></button>');

            });
        });

        //Definir como principal
        $('body').on('click', '.ConfirmacionDefinirComoPrincipal', function(event) {

            $("#ModalDireccion").hide();
            $("#Loading5").show();

            let form_data = new FormData();
            event.preventDefault();
            var id = $(this).attr('id');
            form_data.append('id', id);
            var codigo = id.replace('ConfirmacionDefinirComoPrincipal', '');
            form_data.append('codigo', codigo);
            var LlaveDrDomPrinCliente = $("#LlaveDrDomPrinCliente").val();
            form_data.append('LlaveDrDomPrinCliente', LlaveDrDomPrinCliente);
            var ActualizarDireccionDomCliente = $("#TextoDrDomicilio" + codigo).text();
            form_data.append('ActualizarDireccionDomCliente', ActualizarDireccionDomCliente);
            var ActualizarPaisCliente = $("#TextoPaisDomicilio" + codigo).text();
            form_data.append('ActualizarPaisCliente', ActualizarPaisCliente);
            var ActualizarDepartamentoCliente = $("#TextoDepartamentoDomicilio" + codigo).text();
            form_data.append('ActualizarDepartamentoCliente', ActualizarDepartamentoCliente);
            var ActualizarCiudadCliente = $("#TextoCiudadDomicilio" + codigo).text();
            form_data.append('ActualizarCiudadCliente', ActualizarCiudadCliente);
            var ActualizarBarrioCliente = $("#TextoBarrioDomicilio" + codigo).text();
            form_data.append('ActualizarBarrioCliente', ActualizarBarrioCliente);
            var Agente = $("#Agente").val();
            form_data.append('Agente', Agente);
            var LlaveConsulta = $("#LlaveConsulta").val();
            form_data.append('LlaveConsulta', LlaveConsulta);

            $('.ConfirmacionDefinirComoPrincipal').show();
            $(this).hide();

            $.ajax({
                url: "FijarDrDomicilioClientePrincipal.php",
                dataType: "json",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: form_data,
                success: function(php_response) {
                    Respuesta = php_response.msg;
                    if (Respuesta == "Ok") {
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();
                        fechagestion()
                        window.location.reload();
                    } else if (Respuesta == "SinResultados") {
                        alert("Se presento un error en la actualizacion!");
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();
                    } else if (Respuesta == "Error") {
                        alert("Se genero una falla en la asignación!");
                        $("#Loading5").hide();
                        $("#ModalDireccion").show();
                        console.log("Error en el sistema");
                        console.log(php_response.Falla);
                    }
                },
                error: function(php_response) {
                    php_response = JSON.stringify(php_response);
                    alert("Error en la comunicacion con el servidor!");
                    $("#Loading5").hide();
                    $("#ModalDireccion").show();
                    console.log(php_response);
                }
            });

        });

        //Mostrar formulario nueva direccion oficina
        $("#MostrarNuevaDrOficina").click(function() {
            $("#NuevaDrOficina").show();
        })

        //Cancelar Nueva direccion Oficina
        $("#CancelarDtoOfcAdCliente").click(function() {
            $("#NuevaDrOficina").hide();
            $("#DireccionOfcCliente").val("");
            $("#PaisOfcCliente").val("");
            $("#DepartamentoOfcCliente").val("");
            $("#CiudadOfcCliente").val("");
            $("#BarrioOfcCliente").val("");
        })

        //Guardar Nueva Direccion Oficina Cliente
        $("#GuardarNuevaDireccionOfccilioCliente").click(function() {

            var DireccionOfcCliente = $("#DireccionOfcCliente").val();
            var PaisOfcCliente = $("#PaisOfcCliente").val();
            var DepartamentoOfcCliente = $("#DepartamentoOfcCliente").val();
            var CiudadOfcCliente = $("#CiudadOfcCliente").val();
            var BarrioOfcCliente = $("#BarrioOfcCliente").val();
            var Agente = $("#Agente").val();
            var LlaveConsulta = $("#LlaveConsulta").val();

            event.preventDefault();

            if ((DireccionDomCliente == "") || (PaisCliente == "") || (DepartamentoCliente == "") || (CiudadCliente == "") || (BarrioCliente == "")) {
                alert("Tienes que llenar todos los campos de la direccion nueva");
            } else {
                $.ajax({
                    url: "GuardarNuevaDrOficinaCliente.php",
                    type: "POST",
                    data: {

                        DireccionOfcCliente: DireccionOfcCliente,
                        PaisOfcCliente: PaisOfcCliente,
                        DepartamentoOfcCliente: DepartamentoOfcCliente,
                        CiudadOfcCliente: CiudadOfcCliente,
                        BarrioOfcCliente: BarrioOfcCliente,
                        Agente: Agente,
                        LlaveConsulta: LlaveConsulta

                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == "0") {
                        alert("Fallo al guardar la informacion");
                        console.log(Resultado);
                    } else {
                        $("#NuevaDrOficina").hide();
                        $("#NuevaDrOficina").before('<tr id="ContenedorInformacionDrOficina' + Resultado + '"><td id="DireccionActualOficina' + Resultado + '"><p id="TextoDrOficina' + Resultado + '">' + DireccionOfcCliente + '</p></td><td id="PaisActualOficina' + Resultado + '"><p id="TextoPaisOficina' + Resultado + '">' + PaisOfcCliente + '</p></td><td id="DepartammentoActualOficina' + Resultado + '"><p id="TextoDepartamentoOficina' + Resultado + '">' + DepartamentoOfcCliente + '</p></td><td id="CiudadActualOficina' + Resultado + '"><p id="TextoCiudadOficina' + Resultado + '">' + CiudadOfcCliente + '</p></td><td id="BarropActualOficina' + Resultado + '"><p id="TextoBarrioOficina' + Resultado + '">' + BarrioOfcCliente + '</p></td><td id="FijarDatosAdicionalesDireccionOficina' + Resultado + '" style="text-align: center;"><button id="FijarDatosAdicionalesDireccionOficina' + Resultado + '" type="button" class="btn btn-futurus-r FijarDatosAdicionalesDireccionOficina"><i class="fa icon-pin"></i></button></td><td id="ControlCambiosDatosAdicionalesDireccionOficina' + Resultado + '" style="text-align: center;"><button id="ModificarDireccionOficinaCliente' + Resultado + '" type="button" class="btn btn-futurus-r ModificarDireccionOficinaCliente"><i class="fa icon-note"></i></button></td><td id="ControlCancelacionesDatosAdicionalesDireccionOficina' + Resultado + '" style="text-align: center;"><button id="EliminarDireccionOficinaCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarDireccionOficinaCliente"><i class="fa icon-close"></i></button></td></tr>');
                        $("#DireccionOfcCliente").val("");
                        $("#PaisOfcCliente").val("");
                        $("#DepartamentoOfcCliente").val("");
                        $("#CiudadOfcCliente").val("");
                        $("#BarrioOfcCliente").val("");
                    }
                })
            }
        })

        //Eliminar o inactivar Direccion Oficina
        $(function() {
            $('body').on('click', '.EliminarDireccionOficinaCliente', function(event) {

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarDireccionOficinaCliente', '');
                Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminacionOficilioCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Eliminado Correctamente!");
                        $("#ContenedorInformacionDrOficina" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        console.log(Resultado);
                    }
                })
            });
        });

        //Modificar Direccion Oficina cliente
        $(function() {
            $('body').on('click', '.ModificarDireccionOficinaCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ModificarDireccionOficinaCliente', '');
                var TextoDrOficina = $("#TextoDrOficina" + codigo).text();
                var TextoPaisOficina = $("#TextoPaisOficina" + codigo).text();
                var TextoDepartamentoOficina = $("#TextoDepartamentoOficina" + codigo).text();
                var TextoCiudadOficina = $("#TextoCiudadOficina" + codigo).text();
                var TextoBarrioOficina = $("#TextoBarrioOficina" + codigo).text();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();

                $("#TextoDrOficina" + codigo).hide();
                $("#TextoPaisOficina" + codigo).hide();
                $("#TextoDepartamentoOficina" + codigo).hide();
                $("#TextoCiudadOficina" + codigo).hide();
                $("#TextoBarrioOficina" + codigo).hide();

                $("#FijarDatosAdicionalesDireccionOficina" + codigo).text('');
                $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).text('');
                $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).text('');

                $("#DireccionActualOficina" + codigo).append('<input id="ActualizarDireccionDomCliente' + codigo + '" name="ActualizarDireccionDomCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoDrOficina + '" title="' + TextoDrOficina + '">');
                $("#PaisActualOficina" + codigo).append('<input id="ActualizarPaisCliente' + codigo + '" name="ActualizarPaisCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoPaisOficina + '" title="' + TextoPaisOficina + '">');
                $("#DepartammentoActualOficina" + codigo).append('<input id="ActualizarDepartamentoCliente' + codigo + '" name="ActualizarDepartamentoCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoDepartamentoOficina + '" title="' + TextoDepartamentoOficina + '">');
                $("#CiudadActualOficina" + codigo).append('<input id="ActualizarCiudadCliente' + codigo + '" name="ActualizarCiudadCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoCiudadOficina + '" title="' + TextoCiudadOficina + '">');
                $("#BarropActualOficina" + codigo).append('<input id="ActualizarBarrioCliente' + codigo + '" name="ActualizarBarrioCliente" type="text" maxlength="225" class="form-control transparencia" required="" value="' + TextoBarrioOficina + '" title="' + TextoBarrioOficina + '">');

                $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).append('<button id="ConfirmarModificacionDrOficilioCliente' + codigo + '" type="button" class="btn btn-futurus-r ConfirmacionCambioDrOfiCliente"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).append('<button id="ConfirmarCancelarModificacionDrOfiCliente' + codigo + '" type="button" class="btn btn-futurus-r CancelarModificacionDrOfiCliente"><i class="fa icon-close"></i></button>');


            });
        });
        //Cancelacion Modificacion oficina cliente
        $(function() {
            $('body').on('click', '.CancelarModificacionDrOfiCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionDrOfiCliente', '');
                var TextoDrOficina = $("#TextoDrOficina" + codigo).text();
                var TextoPaisOficina = $("#TextoPaisOficina" + codigo).text();
                var TextoDepartamentoOficina = $("#TextoDepartamentoOficina" + codigo).text();
                var TextoCiudadOficina = $("#TextoCiudadOficina" + codigo).text();
                var TextoBarrioOficina = $("#TextoBarrioOficina" + codigo).text();

                $("#DireccionActualOficina" + codigo).text("");
                $("#PaisActualOficina" + codigo).text("");
                $("#DepartammentoActualOficina" + codigo).text("");
                $("#CiudadActualOficina" + codigo).text("");
                $("#BarropActualOficina" + codigo).text("");
                $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).text("");
                $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).text("");

                $("#DireccionActualOficina" + codigo).append('<p id="TextoDrOficina' + codigo + '">' + TextoDrOficina + '</p>');
                $("#PaisActualOficina" + codigo).append('<p id="TextoPaisOficina' + codigo + '">' + TextoPaisOficina + '</p>');
                $("#DepartammentoActualOficina" + codigo).append('<p id="TextoDepartamentoOficina' + codigo + '">' + TextoDepartamentoOficina + '</p>');
                $("#CiudadActualOficina" + codigo).append('<p id="TextoCiudadOficina' + codigo + '">' + TextoCiudadOficina + '</p>');
                $("#BarropActualOficina" + codigo).append('<p id="TextoBarrioOficina' + codigo + '">' + TextoBarrioOficina + '</p>');

                $("#FijarDatosAdicionalesDireccionOficina" + codigo).append('<button id="FijarDatosAdicionalesDireccionOficina' + codigo + '" type="button" class="btn btn-futurus-r FijarDatosAdicionalesDireccionOficina"><i class="fa icon-pin"></i></button>');
                $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).append('<button id="ModificarDireccionOficinaCliente' + codigo + '" type="button" class="btn btn-futurus-r ModificarDireccionOficinaCliente"><i class="fa icon-note"></i></button>');
                $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).append('<button id="EliminarDireccionOficinaCliente' + codigo + '" type="button" class="btn btn-futurus-r EliminarDireccionOficinaCliente"><i class="fa icon-close"></i></button>');

            });
        });

        //Confirmacion Modificacion direccion domicilio cliente
        $(function() {
            $('body').on('click', '.ConfirmacionCambioDrOfiCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionDrOficilioCliente', '');
                var ActualizarDireccionDomCliente = $("#ActualizarDireccionDomCliente" + codigo).val();
                var ActualizarPaisCliente = $("#ActualizarPaisCliente" + codigo).val();
                var ActualizarDepartamentoCliente = $("#ActualizarDepartamentoCliente" + codigo).val();
                var ActualizarCiudadCliente = $("#ActualizarCiudadCliente" + codigo).val();
                var ActualizarBarrioCliente = $("#ActualizarBarrioCliente" + codigo).val();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();

                $.ajax({
                    url: "ActualizarDrOficilioCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo,
                        ActualizarDireccionDomCliente: ActualizarDireccionDomCliente,
                        ActualizarPaisCliente: ActualizarPaisCliente,
                        ActualizarDepartamentoCliente: ActualizarDepartamentoCliente,
                        ActualizarCiudadCliente: ActualizarCiudadCliente,
                        ActualizarBarrioCliente: ActualizarBarrioCliente,
                        LlaveConsulta: LlaveConsulta
                    }
                }).done(function(data) {
                    Resultado = String(data);

                    if (Resultado != "0") {
                        $("#DireccionActualOficina" + codigo).text("");
                        $("#PaisActualOficina" + codigo).text("");
                        $("#DepartammentoActualOficina" + codigo).text("");
                        $("#CiudadActualOficina" + codigo).text("");
                        $("#BarropActualOficina" + codigo).text("");
                        $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).text("");
                        $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).text("");



                        $("#DireccionActualOficina" + codigo).append('<p id="TextoDrOficina' + Resultado + '">' + ActualizarDireccionDomCliente + '</p>');
                        $("#PaisActualOficina" + codigo).append('<p id="TextoPaisOficina' + Resultado + '">' + ActualizarPaisCliente + '</p>');
                        $("#DepartammentoActualOficina" + codigo).append('<p id="TextoDepartamentoOficina' + Resultado + '">' + ActualizarDepartamentoCliente + '</p>');
                        $("#CiudadActualOficina" + codigo).append('<p id="TextoCiudadOficina' + Resultado + '">' + ActualizarCiudadCliente + '</p>');
                        $("#BarropActualOficina" + codigo).append('<p id="TextoBarrioOficina' + Resultado + '">' + ActualizarBarrioCliente + '</p>');
                        $("#FijarDatosAdicionalesDireccionOficina" + codigo).append('<button id="FijarDatosAdicionalesDireccionOficina' + Resultado + '" type="button" class="btn btn-futurus-r FijarDatosAdicionalesDireccionOficina"><i class="fa icon-pin"></i></button>');
                        $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).append('<button id="ModificarDireccionOficinaCliente' + Resultado + '" type="button" class="btn btn-futurus-r ModificarDireccionOficinaCliente"><i class="fa icon-note"></i></button>');
                        $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).append('<button id="EliminarDireccionOficinaCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarDireccionOficinaCliente"><i class="fa icon-close"></i></button>');


                        $("#ContenedorInformacionDrDomicilio" + codigo).attr("id", "ContenedorInformacionDrDomicilio" + Resultado);
                        $("#DireccionActualDomicilio" + codigo).attr("id", "DireccionActualDomicilio" + Resultado);
                        $("#PaisActualDomicilio" + codigo).attr("id", "PaisActualDomicilio" + Resultado);
                        $("#DepartammentoActualDomicilio" + codigo).attr("id", "DepartammentoActualDomicilio" + Resultado);
                        $("#CiudadActualDomicilio" + codigo).attr("id", "CiudadActualDomicilio" + Resultado);
                        $("#BarropActualDomicilio" + codigo).attr("id", "BarropActualDomicilio" + Resultado);
                        $("#FijarDatosAdicionalesDireccionOficina" + codigo).attr("id", "FijarDatosAdicionalesDireccionOficina" + Resultado);
                        $("#ControlCambiosDatosAdicionalesDireccionOficina" + codigo).attr("id", "ControlCambiosDatosAdicionalesDireccionOficina" + Resultado);
                        $("#ControlCancelacionesDatosAdicionalesDireccionOficina" + codigo).attr("id", "ControlCancelacionesDatosAdicionalesDireccionOficina" + Resultado);

                    } else {
                        alert("Error en la actualizacion");
                        console.log(Resultado);
                    }
                });
            });
        });

        //Mostrar Formulario nuevo movil
        $("#MostrarFormularioNuevoMovil").click(function() {
            $("#NuenvoNumeroMovil").show();
        })

        //Cancelar Nueva linea movil
        $("#CancelarNuevoNumeroMovil").click(function() {
            $("#NuenvoNumeroMovil").hide();
            $("#NuevomOVIL").val('');
        })

        //Guardar Nueva linea movil
        $("#CapturaNumeroMovil").click(function() {

            $("#ModalNum").hide();
            $("#Loading6").show();

            NuevomMovil = $("#NuevomOVIL").val();
            Agente = $("#Agente").val();
            LlaveConsulta = $("#LlaveConsulta").val();
            event.preventDefault();

            ValiadarCantidadCaracteresMovil(NuevomMovil);

            if (Controlador == 1) {
                if (NuevomMovil == "") {
                    alert("Tienes que diligenciar una linea movil");
                    $("#ModalNum").show();
                    $("#Loading6").hide();
                } else {
                    $.ajax({
                        url: "GuardarNuevaLineMovil.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            NuevomMovil: NuevomMovil,
                            LlaveConsulta: LlaveConsulta
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "") {
                            $("#ModalNum").show();
                            $("#Loading6").hide();
                            $("#NuenvoNumeroMovil").hide();
                            $("#NuenvoNumeroMovil").before('<tr id="ContenedorInformacion' + Resultado + '"><td id="MovilClienteModificar' + Resultado + '"><p id="TextoMovilCliente' + Resultado + '">' + NuevomMovil + '</p></td><td id="ControlCamviosMovilCliente' + Resultado + '" style="text-align: center;"><button id="CofigoActualizacioMovilCliente' + Resultado + '" type="button" class="btn btn-futurus-r ModificarLineaMovil"><i class="fa  icon-note"></i></button></td><td id="ControlCancelacionesMovil' + Resultado + '" style="text-align: center;"><button id="EliminarMovilCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarMovilCliente"><i class="fa icon-close"></i></button></td>');
                            $("#NuevomOVIL").val("");
                        } else {
                            alert("Error al guardar la informacion");
                            $("#ModalNum").show();
                            $("#Loading6").hide();
                            console.log(Resultado);
                        }

                    })

                }

            }
        })

        //Eliminar movil Cliente
        $(function() {
            $('body').on('click', '.EliminarMovilCliente', function(event) {

                $("#ModalNum").hide();
                $("#Loading6").show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarMovilCliente', '');
                Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminacionMovilCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == "1") {
                        alert("¡Eliminado Correctamente!");
                        $("#Loading6").hide();
                        $("#ModalNum").show();
                        $("#ContenedorInformacion" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        $("#Loading6").hide();
                        $("#ModalNum").show();
                        console.log(Resultado);
                    }
                })
            });
        });

        //Modificar movil
        $(function() {
            $('body').on('click', '.ModificarLineaMovil', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('CofigoActualizacioMovilCliente', '');
                var TextoMovilCliente = $("#TextoMovilCliente" + codigo).text();

                $("#MovilClienteModificar" + codigo).text('');
                $("#ControlCamviosMovilCliente" + codigo).text('');
                $("#ControlCancelacionesMovil" + codigo).text('');

                $("#MovilClienteModificar" + codigo).append('<input id="ValorMovilCliente' + codigo + '" name="ValorMovilCliente" type="text" minlength=10 maxlength=10 onkeypress="return validaNumericos(event)" class="form-control transparencia" value="' + TextoMovilCliente + '" required="">');
                $("#ControlCamviosMovilCliente" + codigo).append('<button id="ConfirmarModificacionMovilCliente' + codigo + '" type="button" class="btn btn-futurus-r ConfirmacionCambioMovilCliente ' + codigo + '"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesMovil" + codigo).append('<button id="ConfirmarCancelarModificacionMovilCliente' + codigo + '" type="button" class="btn btn-futurus-r CancelarModificacionMovilCliente ' + codigo + '"><i class="fa icon-close"></i></button>');

            });
        });

        //Cancelacion Modificacion
        $(function() {
            $('body').on('click', '.CancelarModificacionMovilCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionMovilCliente', '');
                var MovilCliente = $("#ValorMovilCliente" + codigo).val();

                $("#MovilClienteModificar" + codigo).text("");
                $("#ControlCamviosMovilCliente" + codigo).text("");
                $("#ControlCancelacionesMovil" + codigo).text("");
                $("#MovilClienteModificar" + codigo).append('<p id="TextoMovilCliente' + codigo + '">' + MovilCliente + '</p>');
                $("#ControlCamviosMovilCliente" + codigo).append('<button id="CofigoActualizacioMovilCliente' + codigo + '" type="button" class="btn btn-futurus-r ModificarLineaMovil"><i class="fa  icon-note"></i></button>');
                $("#ControlCancelacionesMovil" + codigo).append('<button id="EliminarMovilCliente' + codigo + '" type="button" class="btn btn-futurus-r EliminarMovilCliente"><i class="fa icon-close"></i></button>');
            });
        });

        //Confirmacion Modificacion Movil
        $(function() {
            $('body').on('click', '.ConfirmarModificacionMovilCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionMailCliente', '');
                var ValorMovilCliente = $("#ValorMovilCliente" + codigo).val();
                Agente = $("#Agente").val();
                LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();
                $.ajax({
                    url: "ActualizarMovilCliente.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo,
                        ValorMovilCliente: ValorMovilCliente,
                        LlaveConsulta: LlaveConsulta
                    }
                }).done(function(data) {
                    Resultado = String(data);

                    if (Resultado != "0") {
                        $("#MovilClienteModificar" + codigo).text("");
                        $("#ControlCamviosMovilCliente" + codigo).text("");
                        $("#ControlCancelacionesMovil" + codigo).text("");

                        $("#MovilClienteModificar" + codigo).append('<p id="TextoMovilCliente' + Resultado + '">' + ValorMovilCliente + '</p>');
                        $("#ControlCamviosMovilCliente" + codigo).append('<button id="ControlCamviosMovilCliente' + Resultado + '" type="button" class="btn btn-futurus-r MailCliente"><i class="fa  icon-note"></i></button>');
                        $("#ControlCancelacionesMovil" + codigo).append('<button id="ControlCancelacionesMovil' + Resultado + '" type="button" class="btn btn-futurus-r EliminarCorreoCliente"><i class="fa icon-close"></i></button>');

                        $("#ContenedorInformacion" + codigo).attr("id", "ContenedorInformacion" + Resultado);
                        $("#MovilClienteModificar" + codigo).attr("id", "MovilClienteModificar" + Resultado);
                        $("#ControlCamviosMovilCliente" + codigo).attr("id", "ControlCamviosMovilCliente" + Resultado);
                        $("#ControlCancelacionesMovil" + codigo).attr("id", "ControlCancelacionesMovil" + Resultado);

                    } else {
                        alert("Error en la actualizacion");
                        console.log(Resultado);
                    }
                });

            });

        });

        //Confirmacion Modificacion
        $(function() {
            $('body').on('click', '.ConfirmacionCambioMovilCliente', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionMovilCliente', '');
                var ModificacionSobre = "MailClienteModificar" + codigo;
                var ValorMovilCliente = $("#ValorMovilCliente" + codigo).val();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();

                ValiadarCantidadCaracteresMovil(ValorMovilCliente);

                if (Controlador == 1) {
                    $.ajax({
                        url: "ActualizarNMovilCliente.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            codigo: codigo,
                            ValorMovilCliente: ValorMovilCliente,
                            LlaveConsulta: LlaveConsulta
                        }
                    }).done(function(data) {
                        Resultado = String(data);

                        if (Resultado != "0") {
                            $("#MovilClienteModificar" + codigo).text("");
                            $("#ControlCamviosMovilCliente" + codigo).text("");
                            $("#ControlCancelacionesMovil" + codigo).text("");

                            $("#MovilClienteModificar" + codigo).append('<p id="TextoMovilCliente' + Resultado + '">' + ValorMovilCliente + '</p>');
                            $("#ControlCamviosMovilCliente" + codigo).append('<button id="CofigoActualizacioMovilCliente' + Resultado + '" type="button" class="btn btn-futurus-r ModificarLineaMovil"><i class="fa  icon-note"></i></button>');
                            $("#ControlCancelacionesMovil" + codigo).append('<button id="EliminarMovilCliente' + Resultado + '" type="button" class="btn btn-futurus-r EliminarMovilCliente"><i class="fa icon-close"></i></button>');


                            $("#ContenedorInformacion" + codigo).attr("id", "ContenedorInformacion" + Resultado);
                            $("#MovilClienteModificar" + codigo).attr("id", "MovilClienteModificar" + Resultado);
                            $("#ControlCamviosMovilCliente" + codigo).attr("id", "ControlCamviosMovilCliente" + Resultado);
                            $("#ControlCancelacionesMovil" + codigo).attr("id", "ControlCancelacionesMovil" + Resultado);

                        } else {
                            alert("Error en la actualizacion");
                            console.log(Resultado);
                        }
                    });
                }
            });

        });

        //Mostrar Formulario Numero Fijo
        $("#MostrarFormularioNumeroFijo").click(function() {
            $("#NuenvoNumeroFijo").show();
        })

        //Canselar Nuevo Numero Fijo
        $("#CancelarNuevoNumeroFijo").click(function() {
            $("#NuenvoNumeroFijo").hide();
            $("#NuevoNumeroFijoIndicativo").val("");
            $("#NuevoNumeroFijo").val("");
            $("#NuevoNumeroFijoExtencion").val("");
        })

        //Guardar Nuevo Numero Fijo
        $("#CapturaNumeroFijo").click(function() {

            $("#ModalNum").hide();
            $("#Loading6").show();

            var NuevoNumeroFijoIndicativo = $("#NuevoNumeroFijoIndicativo").val();
            var NuevoNumeroFijo = $("#NuevoNumeroFijo").val();
            var NuevoNumeroFijoExtencion = $("#NuevoNumeroFijoExtencion").val();
            var Agente = $("#Agente").val();
            var LlaveConsulta = $("#LlaveConsulta").val();

            event.preventDefault();

            ValiadarCantidadCaracteresFijo(NuevoNumeroFijo);

            if (Controlador == 1) {
                if ((NuevoNumeroFijoIndicativo == "") || (NuevoNumeroFijo == "") || (NuevoNumeroFijoExtencion == "")) {
                    alert("Tienes que diligenciar una linea movil");
                    $("#ModalNum").show();
                    $("#Loading6").hide();
                } else {
                    $.ajax({
                        url: "GuardarNuevaLineFija.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            NuevoNumeroFijoIndicativo: NuevoNumeroFijoIndicativo,
                            NuevoNumeroFijo: NuevoNumeroFijo,
                            NuevoNumeroFijoExtencion: NuevoNumeroFijoExtencion,
                            LlaveConsulta: LlaveConsulta
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "") {
                            $("#ModalNum").show();
                            $("#Loading6").hide();
                            $("#NuenvoNumeroFijo").hide();
                            $("#NuenvoNumeroFijo").before('<tr id="IndicativoFijo' + Resultado + '"><td id="IndicativoFijo' + Resultado + '"><p id="TextoIndicativoFico' + Resultado + '">' + NuevoNumeroFijoIndicativo + '</p></td><td id="NumeroFijo' + Resultado + '"><p id="TextoNumeroFijo' + Resultado + '">' + NuevoNumeroFijo + '</p></td><td id="ExtencionFijo' + Resultado + '"><p id="TextoExtencionFijo' + Resultado + '">' + NuevoNumeroFijoExtencion + '</p></td><td id="ControlCamviosNumeroFija' + Resultado + '" style="text-align: center;"><button id="CofigoActualizacioNumeroFijo' + Resultado + '" type="button" class="btn btn-futurus-r ModificarLineaMovil"><i class="fa  icon-note"></i></button></td><td id="ControlCancelacionesNumeroFija' + Resultado + '" style="text-align: center;"><button id="EliminarNumeroFijo' + Resultado + '" type="button" class="btn btn-futurus-r EliminarNumeroFijo"><i class="fa icon-close"></i></button></td></tr>');
                            $("#NuevoNumeroFijoIndicativo").val("");
                            $("#NuevoNumeroFijo").val("");
                            $("#NuevoNumeroFijoExtencion").val("");
                            window.location.reload();
                        } else {
                            alert("Error al guardar la informacion");
                            $("#ModalNum").show();
                            $("#Loading6").hide();
                            console.log(Resultado);
                        }

                    })

                }
            }

        })

        //Eliminar o Inactivar Numero Fijo
        $(function() {
            $('body').on('click', '.EliminarNumeroFijo', function(event) {

                $("#ModalNum").hide();
                $("#Loading6").show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarNumeroFijo', '');
                var Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminacionNumeroFijo.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == "1") {
                        alert("¡Eliminado Correctamente!");
                        $("#Loading6").hide();
                        $("#ModalNum").show();
                        $("#ContenedorInformacionFijos" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        $("#Loading6").hide();
                        $("#ModalNum").show();
                        console.log(Resultado);
                    }
                })
            });
        });

        //Modificar Linea Fija
        $(function() {
            $('body').on('click', '.CofigoActualizacioNumeroFijo', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('CofigoActualizacioNumeroFijo', '');

                console.log("#TextoIndicativoFico" + codigo);
                var TextoIndicativoFico = $("#TextoIndicativoFico" + codigo).text();
                var TextoNumeroFijo = $("#TextoNumeroFijo" + codigo).text();
                var TextoExtencionFijo = $("#TextoExtencionFijo" + codigo).text();


                $("#TextoIndicativoFico" + codigo).hide();
                $("#TextoNumeroFijo" + codigo).hide();
                $("#TextoExtencionFijo" + codigo).hide();

                $("#ControlCamviosNumeroFija" + codigo).text('');
                $("#ControlCancelacionesNumeroFija" + codigo).text('');

                $("#IndicativoFijo" + codigo).append('<input id="ValorIndicativo' + codigo + '" name="ValorIndicativo" type="text" onkeypress="return validaNumericos(event)"  maxlength=4 class="form-control" value="' + TextoIndicativoFico + '" required="">');
                $("#NumeroFijo" + codigo).append('<input id="ValorNumero' + codigo + '" name="ValorNumero" type="text" minlength=7 maxlength=7 onkeypress="return validaNumericos(event)" class="form-control" value="' + TextoNumeroFijo + '" required="">');
                $("#ExtencionFijo" + codigo).append('<input id="ValorExtencion' + codigo + '" name="ValorExtencion" type="text" onkeypress="return validaNumericos(event)" maxlength=5 class="form-control" value="' + TextoExtencionFijo + '" required="">');
                $("#ControlCamviosNumeroFija" + codigo).append('<button id="ConfirmarModificacionNumeroFijo' + codigo + '" type="button" class="btn btn-futurus-r ModificacionNumeroFijo"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesNumeroFija" + codigo).append('<button id="ConfirmarCancelarModificacionNumeroFijo' + codigo + '" type="button" class="btn btn-futurus-r CancelarModificacionNumeroFijo ' + codigo + '"><i class="fa icon-close"></i></button>');

            });
        });

        //Cancelacion Modificacion
        $(function() {
            $('body').on('click', '.CancelarModificacionNumeroFijo', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionNumeroFijo', '');
                var TextoIndicativoFico = $("#TextoIndicativoFico" + codigo).text();
                var TextoNumeroFijo = $("#TextoNumeroFijo" + codigo).text();
                var TextoExtencionFijo = $("#TextoExtencionFijo" + codigo).text();

                $("#IndicativoFijo" + codigo).text('');
                $("#NumeroFijo" + codigo).text('');
                $("#ExtencionFijo" + codigo).text('');
                $("#ControlCamviosNumeroFija" + codigo).text('');
                $("#ControlCancelacionesNumeroFija" + codigo).text('');

                $("#IndicativoFijo" + codigo).append('<p id="TextoIndicativoFico' + codigo + '">' + TextoIndicativoFico + '</p>');
                $("#NumeroFijo" + codigo).append('<p id="TextoNumeroFijo' + codigo + '">' + TextoNumeroFijo + '</p>');
                $("#ExtencionFijo" + codigo).append('<p id="TextoExtencionFijo' + codigo + '">' + TextoExtencionFijo + '</p>');
                $("#ControlCamviosNumeroFija" + codigo).append('<button id="CofigoActualizacioNumeroFijo' + codigo + '" type="button" class="btn btn-futurus-r CofigoActualizacioNumeroFijo"><i class="fa  icon-note"></i></button>');
                $("#ControlCancelacionesNumeroFija" + codigo).append('<button id="EliminarNumeroFijo' + codigo + '" type="button" class="btn btn-futurus-r EliminarNumeroFijo"><i class="fa icon-close"></i></button>');

            });
        });

        //Confirmacion Modificacion Numero Fijo
        $(function() {
            $('body').on('click', '.ModificacionNumeroFijo', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionNumeroFijo', '');
                var ValorIndicativo = $("#ValorIndicativo" + codigo).val();
                var ValorNumero = $("#ValorNumero" + codigo).val();
                var ValorExtencion = $("#ValorExtencion" + codigo).val();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();
                ValiadarCantidadCaracteresFijo(ValorNumero);
                if (Controlador == 1) {
                    $.ajax({
                        url: "ActualizarNFijo.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            codigo: codigo,
                            ValorIndicativo: ValorIndicativo,
                            ValorNumero: ValorNumero,
                            ValorExtencion: ValorExtencion,
                            LlaveConsulta: LlaveConsulta
                        }
                    }).done(function(data) {
                        Resultado = String(data.trim());

                        if (Resultado != "0") {
                            $("#IndicativoFijo" + codigo).text("");
                            $("#NumeroFijo" + codigo).text("");
                            $("#ExtencionFijo" + codigo).text("");
                            $("#ControlCamviosNumeroFija" + codigo).text("");
                            $("#ControlCancelacionesNumeroFija" + codigo).text("");

                            $("#IndicativoFijo" + codigo).append('<p id="TextoIndicativoFico' + Resultado + '">' + ValorIndicativo + '</p>');
                            $("#NumeroFijo" + codigo).append('<p id="TextoNumeroFijo' + Resultado + '">' + ValorNumero + '</p>');
                            $("#ExtencionFijo" + codigo).append('<p id="TextoExtencionFijo' + Resultado + '">' + ValorExtencion + '</p>');
                            $("#ControlCamviosNumeroFija" + codigo).append('<button id="CofigoActualizacioNumeroFijo' + Resultado + '" type="button" class="btn btn-futurus-r CofigoActualizacioNumeroFijo"><i class="fa  icon-note"></i></button>');
                            $("#ControlCancelacionesNumeroFija" + codigo).append('<button id="EliminarNumeroFijo' + Resultado + '" type="button" class="btn btn-futurus-r EliminarNumeroFijo"><i class="fa icon-close"></i></button>');

                            $("#ControlCamviosNumeroFija" + codigo).attr("id", "ControlCamviosNumeroFija" + Resultado);
                            $("#ControlCancelacionesNumeroFija" + codigo).attr("id", "ControlCancelacionesNumeroFija" + Resultado);
                            $("#ContenedorInformacionFijos" + codigo).attr("id", "ContenedorInformacionFijos" + Resultado);
                            $("#IndicativoFijo" + codigo).attr("id", "IndicativoFijo" + Resultado);
                            $("#NumeroFijo" + codigo).attr("id", "NumeroFijo" + Resultado);
                            $("#ExtencionFijo" + codigo).attr("id", "ExtencionFijo" + Resultado);

                        } else {
                            alert("Error en la actualizacion");
                            console.log(Resultado);
                        }
                    });
                } else {}
            });

        });

        //Mostrar Nueva linea movil RRHH
        $("#MostrarFormularioMovilEmpresa").click(function() {
            $("#NuevoNuemeroMovilEmpresa").show();
        })

        //Cancelar Nueva linea movil RRHH
        $("#CancelarNuevoNumeroMovilEmpresa").click(function() {
            $("#NueviMovilEmpresa").val("");
            $("#NuevoNuemeroMovilEmpresa").hide();
        })

        //Guardar Nueva linea Movil RRHH
        $("#CapturaNumeroMovilEmpresa").click(function() {

            $("#ModalEmpresa").hide();
            $("#Loading7").show();

            var NueviMovilEmpresa = $("#NueviMovilEmpresa").val();
            var Agente = $("#Agente").val();
            var CodigoEmpresa = $("#CodigoEmpresa").val();

            event.preventDefault();
            ValiadarCantidadCaracteresMovil(NueviMovilEmpresa);
            if (Controlador == 1) {
                if (NueviMovilEmpresa == "") {
                    alert("Tienes que diligenciar una linea movil");
                    $("#Loading7").hide();
                    $("#ModalEmpresa").show();
                } else {
                    $.ajax({
                        url: "GuardarNuevaLineMovilEmpresa.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            NueviMovilEmpresa: NueviMovilEmpresa,
                            CodigoEmpresa: CodigoEmpresa
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "") {
                            $("#Loading7").hide();
                            $("#ModalEmpresa").show();
                            $("#NuevoNuemeroMovilEmpresa").hide();
                            $("#NuevoNuemeroMovilEmpresa").before('<tr id="ContenedorMovilEmpresa' + Resultado + '"><td id="NuemeroMovilEmpresa' + Resultado + '"><p id="TextoMovilEmpresa' + Resultado + '">' + NueviMovilEmpresa + '</p></td><td id="ControlCamviosMovilEmprea' + Resultado + '" style="text-align: center;"><button id="CofigoActualizacioMovilEmprea' + Resultado + '" type="button" class="btn btn-futurus-r CofigoActualizacioMovilEmprea"><i class="fa  icon-note"></i></button></td><td id="ControlCancelacionesMovilEmprea' + Resultado + '" style="text-align: center;"><button id="EliminarNumeroMovilEmprea' + Resultado + '" type="button" class="btn btn-futurus-r EliminarNumeroMovilEmprea"><i class="fa icon-close"></i></button></td></tr>');
                            $("#NueviMovilEmpresa").val("");
                        } else {
                            alert("Error al guardar la informacion");
                            $("#Loading7").hide();
                            $("#ModalEmpresa").show();
                            console.log(Resultado);
                        }
                    })

                }
            }
        })

        //Eliminar Numero Contacto RRHH
        $(function() {
            $('body').on('click', '.EliminarNumeroMovilEmprea', function(event) {

                $("#ModalEmpresa").hide();
                $("#Loading7").show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarNumeroMovilEmprea', '');
                var Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminarNumeroRRHH.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Eliminado Correctamente!");
                        $("#Loading7").hide();
                        $("#ModalEmpresa").show();
                        $("#ContenedorMovilEmpresa" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        $("#Loading7").hide();
                        $("#ModalEmpresa").show();
                        console.log(Resultado);
                    }
                })
            });
        });

        //Modificar Numero RRHH
        $(function() {
            $('body').on('click', '.CofigoActualizacioMovilEmprea', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('CofigoActualizacioMovilEmprea', '');
                var TextoMovilCliente = $("#TextoMovilEmpresa" + codigo).text();

                $("#NuemeroMovilEmpresa" + codigo).text('');
                $("#ControlCamviosMovilEmprea" + codigo).text('');
                $("#ControlCancelacionesMovilEmprea" + codigo).text('');

                $("#NuemeroMovilEmpresa" + codigo).append('<input id="NumeroContactoRRHH' + codigo + '" name="NumeroContactoRRHH" type="text" minlength="10" maxlength="10" onkeypress="return validaNumericos(event)"  class="form-control transparencia" value="' + TextoMovilCliente + '" required="">');
                $("#ControlCamviosMovilEmprea" + codigo).append('<button id="ConfirmarModificacionContactoRRHH' + codigo + '" type="button" class="btn btn-futurus-r ConfirmarModificacionContactoRRHH ' + codigo + '"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesMovilEmprea" + codigo).append('<button id="ConfirmarCancelarModificacionContactoRRHH' + codigo + '" type="button" class="btn btn-futurus-r ConfirmarCancelarModificacionContactoRRHH ' + codigo + '"><i class="fa icon-close"></i></button>');

            });
        });
        //Cancelar Modificacion Numero RRHH
        $(function() {
            $('body').on('click', '.ConfirmarCancelarModificacionContactoRRHH', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionContactoRRHH', '');
                var ContactoRRHH = $("#NumeroContactoRRHH" + codigo).val();

                $("#NuemeroMovilEmpresa" + codigo).text("");
                $("#ControlCamviosMovilEmprea" + codigo).text("");
                $("#ControlCancelacionesMovilEmprea" + codigo).text("");
                $("#NuemeroMovilEmpresa" + codigo).append('<p id="TextoMovilEmpresa' + codigo + '">' + ContactoRRHH + '</p>');
                $("#ControlCamviosMovilEmprea" + codigo).append('<button id="ConfigActualizacioFijoCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfigActualizacioFijoCorporacion"><i class="fa  icon-note"></i></button>');
                $("#ControlCancelacionesMovilEmprea" + codigo).append('<button id="EliminarNumeroMovilEmprea' + codigo + '" type="button" class="btn btn-futurus-r EliminarNumeroMovilEmprea"><i class="fa icon-close"></i></button>');
            });
        });

        //Confirmar Modificacion Numero RRHH
        $(function() {
            $('body').on('click', '.ConfirmarModificacionContactoRRHH', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarModificacionContactoRRHH', '');
                var NumeroContactoRRHH = $("#NumeroContactoRRHH" + codigo).val();
                Agente = $("#Agente").val();
                CodigoEmpresa = $("#CodigoEmpresa").val();
                event.preventDefault();

                ValiadarCantidadCaracteresMovil(NumeroContactoRRHH);

                if (Controlador == 1) {
                    $.ajax({
                        url: "AcruazarNumeroRRHH.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            codigo: codigo,
                            NumeroContactoRRHH: NumeroContactoRRHH,
                            CodigoEmpresa: CodigoEmpresa
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "0") {
                            $("#NuemeroMovilEmpresa" + codigo).text("");
                            $("#ControlCamviosMovilEmprea" + codigo).text("");
                            $("#ControlCancelacionesMovilEmprea" + codigo).text("");

                            $("#NuemeroMovilEmpresa" + codigo).append('<p id="TextoMovilEmpresa' + Resultado + '">' + NumeroContactoRRHH + '</p>');
                            $("#ControlCamviosMovilEmprea" + codigo).append('<button id="CofigoActualizacioMovilEmprea' + Resultado + '" type="button" class="btn btn-futurus-r CofigoActualizacioMovilEmprea"><i class="fa  icon-note"></i></button>');
                            $("#ControlCancelacionesMovilEmprea" + codigo).append('<button id="EliminarNumeroMovilEmprea' + Resultado + '" type="button" class="btn btn-futurus-r EliminarNumeroMovilEmprea"><i class="fa icon-close"></i></button>');

                            $("#ContenedorMovilEmpresa" + codigo).attr("id", "ContenedorMovilEmpresa" + Resultado);
                            $("#NuemeroMovilEmpresa" + codigo).attr("id", "NuemeroMovilEmpresa" + Resultado);
                            $("#ControlCamviosMovilEmprea" + codigo).attr("id", "ControlCamviosMovilEmprea" + Resultado);
                            $("#ControlCancelacionesMovilEmprea" + codigo).attr("id", "ControlCancelacionesMovilEmprea" + Resultado);

                        } else {
                            alert("Error en la actualizacion");
                            console.log(Resultado);
                        }
                    });
                } else {}


            });

        });

        //Mostrar Formulario Movil Coporacion
        $("#MostrarFormularioMovilCoporacion").click(function() {
            $("#FormMovilCoporacion").show();
        })
        //Cancelar Nuevo Movil Corporacion
        $("#CancelarNuevoNumeroMovilCorporacion").click(function() {
            $("#NueviMovilCorporacion").val("");
            $("#FormMovilCoporacion").hide();
        })
        //Guardar Nuevo Numero Movil Corporacion
        $("#CapturaNumeroMovilCorporacion").click(function() {

            $('#ContEmpresa').hide();
            $('#Loading8').show();

            var NueviMovilCorporacion = $("#NueviMovilCorporacion").val();
            var Agente = $("#Agente").val();
            var CodigoEmpresa = $("#CodigoEmpresa").val();

            event.preventDefault();
            ValiadarCantidadCaracteresMovil(NueviMovilCorporacion);
            if (Controlador == 1) {
                if (NueviMovilCorporacion == "") {
                    alert("Tienes que diligenciar una linea movil");
                    $('#Loading8').hide();
                    $('#ContEmpresa').show();
                } else {
                    $.ajax({
                        url: "GuardarNuevaLineMovilCorporacion.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            NueviMovilCorporacion: NueviMovilCorporacion,
                            CodigoEmpresa: CodigoEmpresa
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "") {
                            $('#Loading8').hide();
                            $('#ContEmpresa').show();
                            $("#FormMovilCoporacion").hide();
                            $("#FormMovilCoporacion").before('<tr id="ContenedorMoviCorporacion' + Resultado + '"><td id="MovilMoviCorporacionModificar' + Resultado + '"><p id="ControlCamviosMovilMoviCorporacion' + Resultado + '">' + NueviMovilCorporacion + '</p></td><td id="ControlCamviosMovilEmprea' + Resultado + '" style="text-align: center;"><button id="ConfigActualizacioMovilMoviCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r ConfigActualizacioMovilMoviCorporacion"><i class="fa  icon-note"></i></button></td><td id="ControlCancelacionesMovilCorporacion' + Resultado + '" style="text-align: center;"><button id="EliminarMovilCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r EliminarMovilCorporacion"><i class="fa icon-close"></i></button></td></tr>');
                            $("#NueviMovilCorporacion").val("");
                            window.location.reload();
                        } else {
                            alert("Error al guardar la informacion");
                            $('#Loading8').hide();
                            $('#ContEmpresa').show();
                            console.log(Resultado);
                        }
                    })

                }
            }
        })
        //Eliminar Movil Corporacion
        $(function() {
            $('body').on('click', '.EliminarMovilCorporacion', function(event) {

                $('#ContEmpresa').hide();
                $('#Loading8').show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarMovilCorporacion', '');
                var Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminarMovilCorporacion.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Eliminado Correctamente!");
                        $('#Loading8').hide();
                        $('#ContEmpresa').show();
                        $("#ContenedorMoviCorporacion" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        $('#Loading8').hide();
                        $('#ContEmpresa').show();
                        console.log(Resultado);
                    }
                })
            });
        });
        //Modificar Movil Coporacion
        $(function() {
            $('body').on('click', '.ConfigActualizacioMovilMoviCorporacion', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfigActualizacioMovilMoviCorporacion', '');
                var TextoMovilCliente = $("#TextoMovilMoviCorporacion" + codigo).text();

                $("#MovilMoviCorporacionModificar" + codigo).text('');
                $("#ControlCamviosMovilMoviCorporacion" + codigo).text('');
                $("#ControlCancelacionesMovilCorporacion" + codigo).text('');

                $("#MovilMoviCorporacionModificar" + codigo).append('<input id="NumeroMovilCorporacion' + codigo + '" name="NumeroMovilCorporacion" type="text" minlength="10" maxlength="10" onkeypress="return validaNumericos(event)" class="form-control transparencia" value="' + TextoMovilCliente + '" required="">');
                $("#ControlCamviosMovilMoviCorporacion" + codigo).append('<button id="ConfirmacionModificacionMovilCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfirmacionModificacionMovilCorporacion ' + codigo + '"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesMovilCorporacion" + codigo).append('<button id="ConfirmarCancelarModificacionMovilCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfirmarCancelarModificacionMovilCorporacion ' + codigo + '"><i class="fa icon-close"></i></button>');

            });
        });

        //Cancelar Modificacion Corporacion
        $(function() {
            $('body').on('click', '.ConfirmarCancelarModificacionMovilCorporacion', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionMovilCorporacion', '');
                var ContactoCorporacion = $("#NumeroMovilCorporacion" + codigo).val();

                $("#MovilMoviCorporacionModificar" + codigo).text("");
                $("#ControlCamviosMovilMoviCorporacion" + codigo).text("");
                $("#ControlCancelacionesMovilCorporacion" + codigo).text("");
                $("#MovilMoviCorporacionModificar" + codigo).append('<p id="TextoMovilMoviCorporacion' + codigo + '">' + ContactoCorporacion + '</p>');
                $("#ControlCamviosMovilMoviCorporacion" + codigo).append('<button id="ConfigActualizacioMovilMoviCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfigActualizacioMovilMoviCorporacion"><i class="fa  icon-note"></i></button>');
                $("#ControlCancelacionesMovilCorporacion" + codigo).append('<button id="EliminarMovilCorporacion' + codigo + '" type="button" class="btn btn-futurus-r EliminarMovilCorporacion"><i class="fa icon-close"></i></button>');
            });
        });

        //Confirmar Modificacion Movil Corporacion
        $(function() {
            $('body').on('click', '.ConfirmacionModificacionMovilCorporacion', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmacionModificacionMovilCorporacion', '');
                var NumeroMovilCorporacion = $("#NumeroMovilCorporacion" + codigo).val();
                Agente = $("#Agente").val();
                CodigoEmpresa = $("#CodigoEmpresa").val();
                event.preventDefault();

                ValiadarCantidadCaracteresMovil(NumeroMovilCorporacion);
                if (Controlador == 1) {
                    $.ajax({
                        url: "AcruazarNumeroMovilCorporacion.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            codigo: codigo,
                            NumeroMovilCorporacion: NumeroMovilCorporacion,
                            CodigoEmpresa: CodigoEmpresa
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "0") {
                            $("#MovilMoviCorporacionModificar" + codigo).text("");
                            $("#ControlCamviosMovilMoviCorporacion" + codigo).text("");
                            $("#ControlCancelacionesMovilCorporacion" + codigo).text("");

                            /* ConfigActualizacioMovilMoviCorporacion

                            CofigoActualizacioMovilEmprea */

                            $("#MovilMoviCorporacionModificar" + codigo).append('<p id="TextoMovilMoviCorporacion' + Resultado + '">' + NumeroMovilCorporacion + '</p>');
                            $("#ControlCamviosMovilMoviCorporacion" + codigo).append('<button id="ConfigActualizacioMovilMoviCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r ConfigActualizacioMovilMoviCorporacion"><i class="fa  icon-note"></i></button>');
                            $("#ControlCancelacionesMovilCorporacion" + codigo).append('<button id="EliminarNumeroMovilEmprea' + Resultado + '" type="button" class="btn btn-futurus-r EliminarNumeroMovilEmprea"><i class="fa icon-close"></i></button>');

                            $("#ContenedorMoviCorporacion" + codigo).attr("id", "ContenedorMoviCorporacion" + Resultado);
                            $("#MovilMoviCorporacionModificar" + codigo).attr("id", "MovilMoviCorporacionModificar" + Resultado);
                            $("#ControlCamviosMovilMoviCorporacion" + codigo).attr("id", "ControlCamviosMovilMoviCorporacion" + Resultado);
                            $("#ControlCancelacionesMovilCorporacion" + codigo).attr("id", "ControlCancelacionesMovilCorporacion" + Resultado);

                        } else {
                            alert("Error en la actualizacion");
                            console.log(Resultado);
                        }
                    });
                } else {}
            });
        });

        //Mostrar Formulario Fijo Corporacion
        $("#FormularioFijoCorporativo").click(function() {
            /*$("#IndicativoFijoCorporacion").val('');
            $("#IndicativoFijoCorporacion").text('');
            $("#NuevoNumeroFijoCorporacion").val('');
            $("#NuevoNumeroFijoCorporacion").text('');
            $("#ExtencionFijoCorporacion").val('');
            $("#ExtencionFijoCorporacion").text('');*/
            $("#FormFijoCoporacion").show();
        })
        //Cancelar Nuevo Numero Fijo Corporacion
        $("#CancelarNuevoNumeroFijoCorporacion").click(function() {
            $("#FormFijoCoporacion").hide();
            $("#IndicativoFijoCorporacion").val('');
            $("#NuevoNumeroFijoCorporacion").val('');
            $("#ExtencionFijoCorporacion").val('');
        })
        //Guardar Nuevo Numero Fijo Corporacion
        $("#CapturaNumeroFijoCorporacion").click(function() {

            $("#ModalNum").hide();
            $("#Loading6").show();
            $('#ContEmpresa').hide();
            $('#Loading8').show();

            var IndicativoFijoCorporacion = $("#IndicativoFijoCorporacion").val();
            var NuevoNumeroFijoCorporacion = $("#NuevoNumeroFijoCorporacion").val();
            var ExtencionFijoCorporacion = $("#ExtencionFijoCorporacion").val();
            var Agente = $("#Agente").val();
            var CodigoEmpresa = $("#CodigoEmpresa").val();

            event.preventDefault();

            ValiadarCantidadCaracteresFijo(NuevoNumeroFijoCorporacion);

            if (Controlador == 1) {
                if (NueviMovilCorporacion == "") {
                    alert("Tienes que diligenciar una linea movil");
                    $("#Loading6").hide();
                    $("#ModalNum").show();
                    $('#Loading8').hide();
                    $('#ContEmpresa').show();
                } else {
                    $.ajax({
                        url: "GuardarNuevaLineFijaCorporacion.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            IndicativoFijoCorporacion: IndicativoFijoCorporacion,
                            NuevoNumeroFijoCorporacion: NuevoNumeroFijoCorporacion,
                            ExtencionFijoCorporacion: ExtencionFijoCorporacion,
                            CodigoEmpresa: CodigoEmpresa
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "") {
                            $("#Loading6").hide();
                            $("#ModalNum").show();
                            $('#Loading8').hide();
                            $('#ContEmpresa').show();
                            $("#FormFijoCoporacion").hide();
                            $("#FormFijoCoporacion").before('<tr id="ContenedorFijoCorporacion' + Resultado + '"><td id="FijoIndicativoCorporacionModificar' + Resultado + '"><p id="TextoIndicativoFijoCorporacion' + Resultado + '">' + IndicativoFijoCorporacion + '</p></td><td id="FijoCorporacionModificar' + Resultado + '"><p id="TextoFijoCorporacion' + Resultado + '">' + NuevoNumeroFijoCorporacion + '</p></td><td id="FijoExtencionCorporacionModificar' + Resultado + '"><p id="TextoFijoExtencionCorporacion' + Resultado + '">' + ExtencionFijoCorporacion + '</p></td><td id="ControlCamviosFijoCorporacion' + Resultado + '" style="text-align: center;"><button id="ConfigActualizacioFijoCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r ConfigActualizacioFijoCorporacion"><i class="fa  icon-note"></i></button></td><td id="ControlCancelacionesFijoCorporacion' + Resultado + '" style="text-align: center;"><button id="EliminarFijoCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r EliminarFijoCorporacion"><i class="fa icon-close"></i></button></td></tr>');
                            $("#NueviMovilCorporacion").val("");
                            window.location.reload();
                        } else {
                            alert("Error al guardar la informacion");
                            $("#Loading6").hide();
                            $("#ModalNum").show();
                            $('#Loading8').hide();
                            $('#ContEmpresa').show();
                            console.log(Resultado);
                        }
                    })

                }
            }


        })
        //Eliminar Numero Fijo Corporacion
        $(function() {
            $('body').on('click', '.EliminarFijoCorporacion', function(event) {

                $('#ContEmpresa').hide();
                $('#Loading8').show();

                var id = $(this).attr('id');
                var codigo = id.replace('EliminarFijoCorporacion', '');
                var Agente = $("#Agente").val();
                event.preventDefault();

                $.ajax({
                    url: "EliminarFijoCorporacion.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        codigo: codigo
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Eliminado Correctamente!");
                        $('#Loading8').hide();
                        $('#ContEmpresa').show();
                        $("#ContenedorFijoCorporacion" + codigo).text("");
                    } else {
                        alert("Error al eliminar");
                        $('#Loading8').hide();
                        $('#ContEmpresa').show();
                        console.log(Resultado);
                    }
                })
            });
        });
        //Modificar Fijo Corporacion
        $(function() {
            $('body').on('click', '.ConfigActualizacioFijoCorporacion', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfigActualizacioFijoCorporacion', '');
                var TextoIndicativoFijoCorporacion = $("#TextoIndicativoFijoCorporacion" + codigo).text();
                var TextoFijoCorporacion = $("#TextoFijoCorporacion" + codigo).text();
                var TextoFijoExtencionCorporacion = $("#TextoFijoExtencionCorporacion" + codigo).text();

                $("#FijoIndicativoCorporacionModificar" + codigo).text('');
                $("#FijoCorporacionModificar" + codigo).text('');
                $("#FijoExtencionCorporacionModificar" + codigo).text('');
                $("#ControlCamviosFijoCorporacion" + codigo).text('');
                $("#ControlCancelacionesFijoCorporacion" + codigo).text('');

                $("#FijoIndicativoCorporacionModificar" + codigo).append('<input id="IndicativoFijoCorporacion' + codigo + '" name="IndicativoFijoCorporacion" type="text" onkeypress="return validaNumericos(event)" maxlength="4" class="form-control transparencia" value="' + TextoIndicativoFijoCorporacion + '" required="">');
                $("#FijoCorporacionModificar" + codigo).append('<input id="NumeroFijoCorporacion' + codigo + '" name="NumeroFijoCorporacion" type="text" maxlength="7" onkeypress="return validaNumericos(event)" class="form-control transparencia" value="' + TextoFijoCorporacion + '" required="">');
                $("#FijoExtencionCorporacionModificar" + codigo).append('<input id="ExtencionFijoCorporacion' + codigo + '" name="ExtencionFijoCorporacion" type="text" onkeypress="return validaNumericos(event)" maxlength="5" class="form-control transparencia" value="' + TextoFijoExtencionCorporacion + '" required="">');
                $("#ControlCamviosFijoCorporacion" + codigo).append('<button id="ConfirmacionModificacionFijoCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfirmacionModificacionFijoCorporacion ' + codigo + '"><i class="fa icon-check"></i></button>');
                $("#ControlCancelacionesFijoCorporacion" + codigo).append('<button id="ConfirmarCancelarModificacionFijoCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfirmarCancelarModificacionFijoCorporacion ' + codigo + '"><i class="fa icon-close"></i></button>');

            });
        });
        //Cancelar Modificacion Fijo Corporacion
        $(function() {
            $('body').on('click', '.ConfirmarCancelarModificacionFijoCorporacion', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmarCancelarModificacionFijoCorporacion', '');
                var IndicativoFijoCorporacion = $("#IndicativoFijoCorporacion" + codigo).val();
                var NumeroFijoCorporacion = $("#NumeroFijoCorporacion" + codigo).val();
                var ExtencionFijoCorporacion = $("#ExtencionFijoCorporacion" + codigo).val();

                $("#FijoIndicativoCorporacionModificar" + codigo).text('');
                $("#FijoCorporacionModificar" + codigo).text('');
                $("#FijoExtencionCorporacionModificar" + codigo).text('');
                $("#ControlCamviosFijoCorporacion" + codigo).text('');
                $("#ControlCancelacionesFijoCorporacion" + codigo).text('');


                $("#FijoIndicativoCorporacionModificar" + codigo).append('<p id="TextoIndicativoFijoCorporacion' + codigo + '">' + IndicativoFijoCorporacion + '</p>');
                $("#FijoCorporacionModificar" + codigo).append('<p id="TextoFijoCorporacion' + codigo + '">' + NumeroFijoCorporacion + '</p>');
                $("#FijoExtencionCorporacionModificar" + codigo).append('<p id="TextoFijoExtencionCorporacion' + codigo + '">' + ExtencionFijoCorporacion + '</p>');
                $("#ControlCamviosFijoCorporacion" + codigo).append('<button id="ConfigActualizacioFijoCorporacion' + codigo + '" type="button" class="btn btn-futurus-r ConfigActualizacioFijoCorporacion"><i class="fa  icon-note"></i></button>');
                $("#ControlCancelacionesFijoCorporacion" + codigo).append('<button id="EliminarFijoCorporacion' + codigo + '" type="button" class="btn btn-futurus-r EliminarFijoCorporacion"><i class="fa icon-close"></i></button>');
            });
        });
        //Confirmar Modificacion Fijo Corporativo
        $(function() {
            $('body').on('click', '.ConfirmacionModificacionFijoCorporacion', function(event) {
                event.preventDefault();
                var id = $(this).attr('id');
                var codigo = id.replace('ConfirmacionModificacionFijoCorporacion', '');
                var IndicativoFijoCorporacion = $("#IndicativoFijoCorporacion" + codigo).val();
                var NumeroFijoCorporacion = $("#NumeroFijoCorporacion" + codigo).val();
                var ExtencionFijoCorporacion = $("#ExtencionFijoCorporacion" + codigo).val();
                var Agente = $("#Agente").val();
                var CodigoEmpresa = $("#CodigoEmpresa").val();
                event.preventDefault();


                ValiadarCantidadCaracteresFijo(NumeroFijoCorporacion);
                if (Controlador == 1) {
                    $.ajax({
                        url: "AcruazarNumeroFijoCorporacion.php",
                        type: "POST",
                        data: {
                            Agente: Agente,
                            codigo: codigo,
                            IndicativoFijoCorporacion: IndicativoFijoCorporacion,
                            NumeroFijoCorporacion: NumeroFijoCorporacion,
                            ExtencionFijoCorporacion: ExtencionFijoCorporacion,
                            CodigoEmpresa: CodigoEmpresa
                        }
                    }).done(function(data) {
                        Resultado = String(data);
                        if (Resultado != "0") {
                            $("#FijoIndicativoCorporacionModificar" + codigo).text("");
                            $("#FijoCorporacionModificar" + codigo).text("");
                            $("#FijoExtencionCorporacionModificar" + codigo).text("");
                            $("#ControlCamviosFijoCorporacion" + codigo).text("");
                            $("#ControlCancelacionesFijoCorporacion" + codigo).text("");

                            $("#FijoIndicativoCorporacionModificar" + codigo).append('<p id="TextoIndicativoFijoCorporacion' + Resultado + '">' + IndicativoFijoCorporacion + '</p>');
                            $("#FijoCorporacionModificar" + codigo).append('<p id="TextoFijoCorporacion' + Resultado + '">' + NumeroFijoCorporacion + '</p>');
                            $("#FijoExtencionCorporacionModificar" + codigo).append('<p id="TextoFijoExtencionCorporacion' + Resultado + '">' + ExtencionFijoCorporacion + '</p>');
                            $("#ControlCamviosFijoCorporacion" + codigo).append('<button id="ConfigActualizacioFijoCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r ConfigActualizacioFijoCorporacion"><i class="fa  icon-note"></i></button>');
                            $("#ControlCancelacionesFijoCorporacion" + codigo).append('<button id="EliminarFijoCorporacion' + Resultado + '" type="button" class="btn btn-futurus-r EliminarFijoCorporacion"><i class="fa icon-close"></i></button>');

                            $("#ContenedorFijoCorporacion" + codigo).attr("id", "ContenedorFijoCorporacion" + Resultado);
                            $("#FijoIndicativoCorporacionModificar" + codigo).attr("id", "FijoIndicativoCorporacionModificar" + Resultado);
                            $("#FijoCorporacionModificar" + codigo).attr("id", "FijoCorporacionModificar" + Resultado);
                            $("#FijoExtencionCorporacionModificar" + codigo).attr("id", "FijoExtencionCorporacionModificar" + Resultado);
                            $("#ControlCamviosFijoCorporacion" + codigo).attr("id", "ControlCamviosFijoCorporacion" + Resultado);
                            $("#ControlCancelacionesFijoCorporacion" + codigo).attr("id", "ControlCancelacionesFijoCorporacion" + Resultado);

                        } else {
                            alert("Error en la actualizacion");
                            console.log(Resultado);
                        }
                    });
                } else {}


            });
        });
        //Actualizacion de datos adicionales cliente
        $("#ActualizarDatosAdicionalesCliente").click(function() {

            $("#Adiciones_Cliente").hide();
            $("#Loading").show();

            var PrimerNombreCliente = $("#PrimerNombreCliente").val();
            var SegundoNombreCliente = $("#SegundoNombreCliente").val();
            var PrimerApellidoCliente = $("#PrimerApellidoCliente").val();
            var SegundoApellidoCliente = $("#SegundoApellidoCliente").val();
            var EstadoCivil = $("#EstadoCivil option:selected").val();
            var FechaNacimiento = $("#FechaNacimiento").val();
            var DescripcionSiafpCliente = $("#DescripcionSiafpCliente").val();
            var TipoInteresCredito = $('input[name="TipoInteresCredito"]:checked').val();
            var TipoInteresCartera = $('input[name="TipoInteresCartera"]:checked').val();

            if (TipoInteresCredito == undefined) {
                TipoInteresCredito = "No";
                if (TipoInteresCartera == undefined) {
                    TipoInteresCartera = "No";
                }
            } else if (TipoInteresCartera == undefined) {
                TipoInteresCartera = "No";
            }

            if (PrimerNombreCliente == "") {
                alert("Tienes que digitar el primer nombre del cliente");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else if (PrimerApellidoCliente == "") {
                alert("Tienes que digitar el primer apellido del cliente");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else if (SegundoApellidoCliente == "") {
                alert("Tienes que digitar el segundo apellido del cliente");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else if (EstadoCivil == "") {
                alert("Tienes que seleccionar un estado civil");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else if (FechaNacimiento == "") {
                alert("Tienes que seleccionar una fecha de nacimiento");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else if (DescripcionSiafpCliente == "") {
                alert("Tienes que diligenciar el campo 'Descripcion Siafp'");
                $("#Loading").hide();
                $("#Adiciones_Cliente").show();
            } else {
                var CodigoPreguntaInteresadoCredito = $("#CodigoPreguntaInteresadoCredito").val();
                var CodigoPreguntaInteresadoCartera = $("#CodigoPreguntaInteresadoCartera").val();
                var CodigoEstadoCivil = $("#CodigoEstadoCivil").val();
                var CodigoFechaNacimiento = $("#CodigoFechaNacimiento").val();
                var CodigoDescripcionSiafpCliente = $("#CodigoDescripcionSiafpCliente").val();
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                event.preventDefault();

                $.ajax({
                    url: "ActualizarDatosAdicionalesCliente.php",
                    type: "POST",
                    data: {
                        PrimerNombreCliente: PrimerNombreCliente,
                        SegundoNombreCliente: SegundoNombreCliente,
                        PrimerApellidoCliente: PrimerApellidoCliente,
                        SegundoApellidoCliente: SegundoApellidoCliente,
                        EstadoCivil: EstadoCivil,
                        FechaNacimiento: FechaNacimiento,
                        DescripcionSiafpCliente: DescripcionSiafpCliente,
                        TipoInteresCredito: TipoInteresCredito,
                        TipoInteresCartera: TipoInteresCartera,
                        CodigoEstadoCivil: CodigoEstadoCivil,
                        CodigoFechaNacimiento: CodigoFechaNacimiento,
                        CodigoDescripcionSiafpCliente: CodigoDescripcionSiafpCliente,
                        CodigoPreguntaInteresadoCredito: CodigoPreguntaInteresadoCredito,
                        CodigoPreguntaInteresadoCartera: CodigoPreguntaInteresadoCartera,
                        Agente: Agente,
                        LlaveConsulta: LlaveConsulta
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Actualizado Correctamente!");
                        $("#Loading").hide();
                        $("#Adiciones_Cliente").show();
                        window.location.reload();

                    } else if (Resultado == 0) {
                        alert("F!");
                        $("#Loading").hide();
                        $("#Adiciones_Cliente").show();
                        window.location.reload();
                    } else {
                        alert("Error en la Actualizacion" + Resultado);
                        $("#Loading").hide();
                        $("#Adiciones_Cliente").show();
                        console.log(Resultado);
                    }
                })
            }
        })

        $("#ActualizarDatosAdicionalesEmpresa").click(function() {

            $("#ModalEmpresa").hide();
            $("#Loading7").show();

            var Empresas = $("#Empresas").val();
            var NombreEncargadoRRHH = $("#NombreEncargadoRRHH").val();
            var CorreoEncargadoRRHH = $("#CorreoEncargadoRRHH").val();
            if (NombreEncargadoRRHH == "") {
                alert("Tienes que digitar el nombre del encargado de RRHH");
                $("#Loading7").hide();
                $("#ModalEmpresa").show();
            } else if (CorreoEncargadoRRHH.indexOf('@', 0) == -1 || CorreoEncargadoRRHH.indexOf('.', 0) == -1) {
                alert('El correo electrónico introducido no es correcto.');
                $("#Loading7").hide();
                $("#ModalEmpresa").show();
                return false;
            } else {
                var Agente = $("#Agente").val();
                var LlaveConsulta = $("#LlaveConsulta").val();
                var CodigoEmpresa = $("#CodigoEmpresa").val();
                var CodigoDatosEncargadoRRHH = $("#CodigoDatosEncargadoRRHH").val();
                var SubCosigoEncargadoRRHH = $("#SubCosigoEncargadoRRHH").val();
                var NumeroContactoRRHHPrincipal = $("#NumeroContactoRRHHPrincipal").val();

                $.ajax({
                    url: "ActualizarDatosAdicionalesEmpresaRRHH.php",
                    type: "POST",
                    data: {
                        Agente: Agente,
                        Empresas: Empresas,
                        NombreEncargadoRRHH: NombreEncargadoRRHH,
                        CorreoEncargadoRRHH: CorreoEncargadoRRHH,
                        CodigoDatosEncargadoRRHH: CodigoDatosEncargadoRRHH,
                        SubCosigoEncargadoRRHH: SubCosigoEncargadoRRHH,
                        NumeroContactoRRHHPrincipal: NumeroContactoRRHHPrincipal,
                        LlaveConsulta: LlaveConsulta,
                        CodigoEmpresa: CodigoEmpresa
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Actualizado Correctamente!");
                        $("#Loading7").hide();
                        $("#ModalEmpresa").show();
                    } else {
                        alert("Error en la actualizacion");
                        $("#Loading7").hide();
                        $("#ModalEmpresa").show();
                        console.log(Resultado);
                    }
                })
            }
        })

        $("#EstadoAtencion").on("change", function() {
            var valor = $(this).val();
            if (valor == "Contacto") {
                $("#SubObservacion").text('');
                $("#SubObservacion").append('<option selected="" hidden="" disabled="">Opciones</option>');
                $("#SubObservacion").append('<?php echo $ListadoContacto ?>');
            } else if (valor == "No Contacto") {
                $("#SubObservacion").text('');
                $("#SubObservacion").append('<option selected="" hidden="" disabled="">Opciones</option>');
                $("#SubObservacion").append('<?php echo $ListadoNoContacto; ?>');
            }else if (valor == "No Aplica") {
                $("#SubObservacion").text('');
                $("#SubObservacion").append('<option selected="" hidden="" disabled="">Opciones</option>');
                $("#SubObservacion").append('<?php echo $ListadoNoAplica; ?>');
            }
        })
        // funcion  para opcion numero errado
        $("#SubObservacion").on("change", function() {
            var valor = $(this).val();

            if ((valor == "Numero Errado")) {
                $("#NotasAdicionales").prop('disabled', true);
                $("#NotasAdicionales").attr("placeholder", "Numero errado");
                $("#NotasAdicionales").text("");
                $("#NotasAdicionales").text("Numero Errado");
                $("#NotasAdicionales").val("");
                $("#NotasAdicionales").val(" :(    Numero Errado   :( ");
                $("#FechaFuturo").hide();
                $("#TituloFechaFuturo").hide();

            } else if (valor == "Viable En El Futuro") {
                $("#TituloFechaFuturo").show();
                $("#FechaFuturo").show();

            } else {
                $("#NotasAdicionales").text("");
                $("#NotasAdicionales").val("");
                $("#NotasAdicionales").prop('disabled', false);
                $("#NotasAdicionales").attr("placeholder", "");
                $("#TituloFechaFuturo").hide();
                $("#FechaFuturo").hide();
            }
        })

        $("#EstadoAtencion").on("change", function() {
            $("#FechaGestion").hide();
            $("#TituloFechaGestion").hide();
            $("#TituloFechaFuturo").hide();
            $("#FechaFuturo").hide();
        })

        $("#SubObservacion").on("change", function() {
            valor = $("#SubObservacion").val();

            if (valor == "0") {
                $("#TituloFechaGestion").hide();
                $("#FechaGestion").hide();
                $("#FechaRegistro").removeAttr('required');
            } else if (valor == "1") {
                $("#TituloFechaGestion").show();
                $("#FechaGestion").show();
                $("#FechaRegistro").attr('required', 'true');
            } else if (valor == "2") {
                $("#TituloFechaFuturo").show();
                $("#FechaFuturo").show();
                $("#FechaFuturo").attr('required', 'true');
            }
        })

        $("#Registro").click(function() {

            $("#main").hide();
            $("#Loading4").show();

            var Agente = $("#Agente").val();
            var CodigoCaso = $("#CodigoCaso").val();
            var LlaveConsulta = $("#LlaveConsulta").val();
            var FechaRegistro = $("#FechaRegistro").val();
            var estado = $("#FechaRegistro").attr('required');
            var CodigoEmpresa = $("#CodigoEmpresa").val();
            var ValorEmpresa = $("#ValorEmpresa").val();
            var EstadoCliente = $("#EstadoCliente option:selected").text();
            var FondoActual = $("#FondoActual").val();
            var FechaFuturo = $("#FechaFuturo2").val();
            var DescripcionSiafpCliente2 = $("#DescripcionSiafpCliente2").val();
            

            if ((ValorEmpresa == 'Opciones') || (ValorEmpresa == null)){
                alert("Se Debe Confirmar El Valor Empresa");
                $("#Loading4").hide();
                $("#main").show();
            } else if ((FondoActual == 'Opciones') || (FondoActual == null)){
                alert("Se Debe Confirmar El Fondo Actual");
                $("#Loading4").hide();
                $("#main").show();
            } else if (EstadoCliente == 'Opciones') {
                alert("Debe seleccionar una opción en el campo Fondo al Que Se Envia");
                $("#Loading4").hide();
                $("#main").show();
            } else if (DescripcionSiafpCliente2 == '') {
                alert("Debe llenar la Descripcion Siafp Del Cliente");
                $("#Loading4").hide();
                $("#main").show();
            } else{

                $.ajax({
                    url: "GuardarActualizacionSupervisor.php",
                    type: "POST",
                    data: {
                        EstadoCliente: EstadoCliente,
                        Agente: Agente,
                        FechaRegistro: FechaRegistro,
                        LlaveConsulta: LlaveConsulta,
                        CodigoCaso: CodigoCaso,
                        CodigoEmpresa: CodigoEmpresa,
                        ValorEmpresa: ValorEmpresa,
                        FondoActual: FondoActual,
                        FechaFuturo: FechaFuturo,
                        DescripcionSiafpCliente2: DescripcionSiafpCliente2
                    }
                }).done(function(data) {
                    Resultado = String(data);
                    if (Resultado == 1) {
                        alert("¡Actualizado Exitosamente!");
                        $("#Loading4").hide();
                        $("#main").show();
                        window.location = "AsignacionNuevosHomeOffice.php";
                        //resetearSessionStorage();
                        
                    } else {
                        alert(":(  Error: no se pudo realizar ninguna accion, por favor validar con el administrador del sistema");
                        $("#Loading4").hide();
                        $("#main").show();
                        console.log(Resultado);
                    }
                })
                
            }
        })

        //Guardar Fecha Ingreso
        $("#btnGuardarFecha").click(function() {

            $("#ContModal2").hide();
            $("#Loading2").show();

            let form_data = new FormData();
            Agente = $("#Agente").val();
            form_data.append('Agente', Agente);
            LlaveConsulta = $("#LlaveConsulta").val();
            form_data.append('LlaveConsulta', LlaveConsulta);
            FechaIngreso = $("#FechaIngreso").val();
            form_data.append('FechaIngreso', FechaIngreso);

            if (FechaIngreso == "") {
                alert("Tienes Que Elegir Una Fecha");
                $("#Loading2").hide();
                $("#ContModal2").show();
            } else {
                $.ajax({
                    url: "GuardarFechaIngreso.php",
                    dataType: "json",
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        if (Respuesta == "Ok") {
                            alert("¡Gestion Realizada Exitosamente!");
                            $("#Loading2").hide();
                            $("#ContModal2").show();
                        } else if (Respuesta == "Error") {
                            alert("Error al guardar la informacion, por favor ponerse en contacto con el administrador");
                            $("#Loading2").hide();
                            $("#ContModal2").show();
                            console.log(php_response.msg);
                        }
                    },
                    error: function(php_response) {
                        php_response = JSON.stringify(php_response);
                        alert("Error en la comunicacion con el servidor!");
                        $("#Loading2").hide();
                        $("#ContModal2").show();
                        console.log(php_response);
                    }
                });

            }

        })
        //Función para min y maximo de caracteres núnericos
        function validaNumericos(event) {
            if (event.charCode >= 48 && event.charCode <= 57) {
                return true;
            }
            return false;
        }


        function ValiadarCantidadCaracteresMovil(valor) {
            if (valor.length < 10) {
                alert("El numero de celular no tiene los 10 digitos!");
                $("#Loading6").hide();
                $("#ModalNum").show();
                $("#Loading7").hide();
                $("#ModalEmpresa").show();
                $('#Loading8').hide();
                $('#ContEmpresa').show();
                return Controlador = 0;
            } else {
                return Controlador = 1;
            }
        }

        function ValiadarCantidadCaracteresFijo(valor) {
            if (valor.length < 7) {
                alert("El telefono fijo no tiene los 7 digitos!");
                $("#Loading6").hide();
                $("#ModalNum").show();
                $("#Loading7").hide();
                $("#ModalEmpresa").show();
                $('#Loading8').hide();
                $('#ContEmpresa').show();
                return Controlador = 0;
            } else {
                return Controlador = 1;
            }
        }


        //Crear nueva empresa
        $("#CrearNuevaEmpresa").click(function() {

            $("#CrearEmpresa").hide();
            $("#Loading9").show();

            var TipoDocumento = $("#TipoDocumento").val();
            var NumeroDocumento = $("#NumeroDocumento").val();
            var NewNombreEmpresa = $("#NewNombreEmpresa").val();
            var Agente = $("#Agente").val();
            var NombreEncargadoEmpresa = $("#NombreEncargadoEmpresaPrin").val();
            var TelefonoEmpresaNueva = $("#TelefonoEmpresaNueva").val();
            var CorreoEncargadoEmpresa = $("#CorreoEncargadoEmpresa").val();
            var DireccionNuevaEmpresa = $("#DireccionNuevaEmpresa").val();
            var PaisNuevaEmpresa = $("#PaisNuevaEmpresa").val();
            var DepartamentoNuevaEmpresa = $("#DepartamentoNuevaEmpresa").val();
            var CiudadNuevaEmpresa = $("#CiudadNuevaEmpresa").val();
            var BarrioNuevaEmpresa = $("#BarrioNuevaEmpresa").val();
            var IndicativoNuevaEmpresa = $("#IndicativoNuevaEmpresa").val();
            var TelefonoNuevaEmpresa = $("#TelefonoNuevaEmpresa").val();
            var ExtencionNuevaEmpresa = $("#ExtencionNuevaEmpresa").val();

            ValiadarCantidadCaracteresMovil(TelefonoEmpresaNueva);
            ValiadarCantidadCaracteresFijo(TelefonoEmpresaNueva);


            if (Controlador == 1) {
                if ((TipoDocumento == "") || (NumeroDocumento == "") || (NewNombreEmpresa == "")) {
                    alert("¡Se Debe Llenar Todo El Formulario De La Nueva Empresa!");
                    $("#Loading9").hide();
                    $("#CrearEmpresa").show();
                } else {

                    if (CorreoEncargadoEmpresa.indexOf('@', 0) == -1 || CorreoEncargadoEmpresa.indexOf('.', 0) == -1) {
                        alert('El correo electrónico introducido no es valido');
                        $("#Loading9").hide();
                        $("#CrearEmpresa").show();
                        return false;
                    } else {

                        $.ajax({
                            url: "GuardarNuevaEmpresa.php",
                            type: "POST",
                            data: {
                                TipoDocumento: TipoDocumento,
                                NumeroDocumento: NumeroDocumento,
                                NewNombreEmpresa: NewNombreEmpresa,
                                Agente: Agente,
                                NombreEncargadoEmpresa: NombreEncargadoEmpresa,
                                TelefonoEmpresaNueva: TelefonoEmpresaNueva,
                                CorreoEncargadoEmpresa: CorreoEncargadoEmpresa,
                                DireccionNuevaEmpresa: DireccionNuevaEmpresa,
                                PaisNuevaEmpresa: PaisNuevaEmpresa,
                                DepartamentoNuevaEmpresa: DepartamentoNuevaEmpresa,
                                CiudadNuevaEmpresa: CiudadNuevaEmpresa,
                                BarrioNuevaEmpresa: BarrioNuevaEmpresa,
                                IndicativoNuevaEmpresa: IndicativoNuevaEmpresa,
                                TelefonoNuevaEmpresa: TelefonoNuevaEmpresa,
                                ExtencionNuevaEmpresa: ExtencionNuevaEmpresa
                            },

                        }).done(function(resultado) {
                            resultado = String(resultado);
                            if (resultado == 1) {
                                window.location.reload();
                                alert("¡Registro Existoso!");
                                $("#Loading9").hide();
                                $("#CrearEmpresa").show();
                            } else if (resultado == 2) {
                                alert("Este Nit Ya Se Encuentra Registrado");
                                $("#Loading9").hide();
                                $("#CrearEmpresa").show();
                            } else {
                                alert(":(    Error al guardar la informacion");
                                $("#Loading9").hide();
                                $("#CrearEmpresa").show();
                                console.log(resultado);
                            }
                        })
                    }
                }
            } else {
                alert("¡Se Debe Llenar Todo El Formulario De La Nueva Empresa!");
                $("#CrearEmpresa").show();
                $("#Loading9").hide();
            }

        })

        //Consulta de Departamentos x pais seleccionado NUEVA DIRECCION
        $("body").on("change", '.NewPaisCliente', function() {
            event.preventDefault();
            let form_data = new FormData();
            var id = $(this).attr('id');
            var TextoPaisDomicilio = $("#" + id).val();
            if (TextoPaisDomicilio == "") {
                alert("Se tiene que seleccionar una opcion valida!");
            } else {
                let form_data = new FormData();
                form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                $.ajax({
                    url: "ConsultaDepartamentos.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        console.log(php_response.Resultado);
                        if (Respuesta == "Ok") {
                            $("#DepartamentoCliente").text("");
                            $("#DepartamentoCliente").append("<option>Seleccionar una opcion</option>");
                            $("#DepartamentoCliente").append(php_response.Resultado);
                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron departamentos de este pais, consultar con el administrador de sistema!");
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

        //Consulta de Ciudad x Departamento seleccionado NUEVA DIRECCION
        $("body").on("change", '.NewDepartamentoCliente', function() {
            event.preventDefault();
            let form_data = new FormData();
            var id = $(this).attr('id');
            var TextoDepartamentoDomicilio = $("#" + id).val();
            var codigo = id.replace("DepartamentoCliente", "");
            var TextoPaisDomicilio = $("#PaisCliente" + codigo).val();

            if (TextoDepartamentoDomicilio == "") {
                alert("Se tiene que seleccionar una opcion valida!");
            } else {
                let form_data = new FormData();
                form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                form_data.append('TextoDepartamentoDomicilio', TextoDepartamentoDomicilio);
                $.ajax({
                    url: "ConsultaCiudades.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        if (Respuesta == "Ok") {
                            $("#CiudadCliente" + codigo).text("");
                            $("#CiudadCliente" + codigo).text("<option>Seleccionar una opcion</option>");
                            $("#CiudadCliente" + codigo).append(php_response.Resultado);
                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron ciudades de este departamento, consultar con el administrador de sistema!");
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

        //Consulta datos encargado
        $("#Empresas").on("change", function() {
            let form_data = new FormData();
            var Empresas = $("#Empresas").val();
            form_data.append('Empresas', Empresas);
            var EmpresaFija = $("#EmpresaFija").val();
            let EmpresaOriginal = '<?php echo $NOMBREEMPRESA ?>';
            let EmpresaActual = $("#Empresas option:selected").html();

            if (Empresas == "") {
                alert("Se tiene que seleccionar una empresa!");
            } else {
                $.ajax({
                    url: "ConsultaInfoDetalleEmpresa.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        if (Respuesta == "Ok") {
                            $("#NombreEncargadoRRHH").val("");
                            $("#NombreEncargadoRRHH").val(php_response.NombreEncargado);
                            $("#CorreoEncargadoRRHH").val("");
                            $("#CorreoEncargadoRRHH").val(php_response.CorreoEncargado);

                            if (EmpresaActual != EmpresaOriginal) {
                                $('#ActualizarDatosAdicionalesEmpresa').show();
                                $('.TablaContactoEncargadoRHH').hide();
                            } else {
                                $('.TablaContactoEncargadoRHH').show();
                                $('#ActualizarDatosAdicionalesEmpresa').hide();
                            }


                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron departamentos de este pais, consultar con el administrador de sistema!");
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

        //Consulta de Departamentos x pais seleccionado NUEVA DIRECCION EMPRESA
        $("body").on("change", '.PaisNewEmpresa ', function() {
            event.preventDefault();
            let form_data = new FormData();
            var id = $(this).attr('id');
            var TextoPaisDomicilio = $("#" + id).val();
            if (TextoPaisDomicilio == "") {
                alert("Se tiene que seleccionar una opción valida!");
            } else {
                let form_data = new FormData();
                form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                $.ajax({
                    url: "ConsultaDepartamentos.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        console.log(php_response.Resultado);
                        if (Respuesta == "Ok") {
                            $("#DepartamentoNuevaEmpresa").text("");
                            $("#DepartamentoNuevaEmpresa").append("<option>Seleccionar una opcion</option>");
                            $("#DepartamentoNuevaEmpresa").append(php_response.Resultado);
                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron departamentos de este pais, consultar con el administrador de sistema!");
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

        //Consulta de Ciudad x Departamento seleccionado NUEVA DIRECCION EMPRESA
        $("body").on("change", '.DepartamentoNewEmpresa', function() {
            event.preventDefault();
            let form_data = new FormData();
            var id = $(this).attr('id');
            var TextoDepartamentoDomicilio = $("#" + id).val();
            var codigo = id.replace("DepartamentoNuevaEmpresa", "");
            var TextoPaisDomicilio = $("#PaisNuevaEmpresa" + codigo).val();

            if (TextoDepartamentoDomicilio == "") {
                alert("Se tiene que seleccionar una opcion valida!");
            } else {
                let form_data = new FormData();
                form_data.append('TextoPaisDomicilio', TextoPaisDomicilio);
                form_data.append('TextoDepartamentoDomicilio', TextoDepartamentoDomicilio);
                $.ajax({
                    url: "ConsultaCiudades.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function(php_response) {
                        Respuesta = php_response.msg;
                        if (Respuesta == "Ok") {
                            $("#CiudadNuevaEmpresa" + codigo).text("");
                            $("#CiudadNuevaEmpresa" + codigo).text("<option>Seleccionar una opcion</option>");
                            $("#CiudadNuevaEmpresa" + codigo).append(php_response.Resultado);
                        } else if (Respuesta == "SinResultados") {
                            alert("No se encontraron ciudades de este departamento, consultar con el administrador de sistema!");
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

        $('#BtnAbrirModalEmpresa').click(function() {

            let EmpresaOriginal = '<?php echo $NOMBREEMPRESA ?>';
            let ValorOriginal = '';

            $('#Empresas option').each(function() {
                if (EmpresaOriginal == $(this).text()) {
                    ValorOriginal = $(this).attr('value');
                }
            });

            $('#Empresas').val(ValorOriginal);
            $('.TablaContactoEncargadoRHH').show();
            $('#ActualizarDatosAdicionalesEmpresa').hide()
        });

        //Cerrar modal datos adicionales empresa
        $("#btnCerrar").click(function() {
            window.location.reload();
        })
    </script>
</body>

</html>

