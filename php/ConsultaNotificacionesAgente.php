
<?php

session_start();

if (isset($_SESSION['codigopermiso'])) {

    $codigopermisos = $_SESSION['codigopermiso'];

    // Consulta Nombre usuario y Supervisor
    $datos = 'Activo';
    $ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, PER_CGRUPO, PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL JOIN u632406828_dbp_crmfuturus.TBL_RPERMISO ON PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PKPER_NCODIGO = " . $codigopermisos . " AND CRE_CESTADO = '" . $datos . "' AND PER_CESTADO = '" . $datos . "' ORDER BY PKCRE_NCODIGO DESC;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {

                $AGENTE = $FilaResultado['PKPER_NCODIGO'];
                $CRE_CUSUARIO = $FilaResultado['CRE_CUSUARIO'];

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


    $DatosNA = array();
    $CantidadCasosVencidos = "";
    $ConsultaSQL = "SELECT PKPENCAL_NCODIGO, CONCAT (CLI_CNOMBRE, ' ', CLI_CAPELLIDO) AS NOMBRE_CLIENTE, PENCAL_CESTADO_FINAL2 AS EstadoCliente, PENCAL_CFECHA_EXPEDIENTE AS FechaLlamar FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL, u632406828_dbp_crmfuturus.TBL_RCLIENTE, u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE PKCLI_NCODIGO = FKDETCLI_NCLI_NCODIGO AND FKPENCAL_NPKCLI_NCODIGO = PKCLI_NCODIGO AND DETCLI_CCONSULTA = 'FondoPensionCliente' AND FKPENCAL_NPKPER_NCODIGO = '". $AGENTE ."' AND (PENCAL_CESTADO_FINAL2 = 'Cliente Indeciso' OR PENCAL_CESTADO_FINAL2 = 'Volver a Llamar' OR PENCAL_CESTADO_FINAL2 = 'Rellamada') AND PENCAL_CFECHA_EXPEDIENTE < NOW() AND PENCAL_CESTADO = 'Activo';";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            $CantidadCasosVencidos = $CantidadResultados;
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $CodigoCasoPendiente = $FilaResultado['PKPENCAL_NCODIGO'];
                $NombreCliente = $FilaResultado['NOMBRE_CLIENTE'];
                $EstadoCliente = $FilaResultado['EstadoCliente'];
                $FechaLlamada = $FilaResultado['FechaLlamar'];

                array_push($DatosNA, array("0" => $NombreCliente, "1" => $EstadoCliente, "2" => $FechaLlamada, "3" => $CodigoCasoPendiente));



            }

        } else {
            $CodigoCasoPendiente = "";
            $NombreCliente = "";
            $EstadoCliente = "";
            $FechaLlamada = "";
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
    }




} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}


?>