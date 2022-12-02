
<?php

    //Conexion
    require('common.php');
    require('funciones_generales.php');
    session_start();

    if (isset($_SESSION['codigopermiso'])) { } else {
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

    $codigopermisos = $_SESSION['codigopermiso'];
    $codigopermisos = trim($codigopermisos);
    $hoy = date("Y-m-d");
    $nivelasesor = null;
    
    // Consulta Nombre usuario y Supervisor
    $datos = 'Activo';
    $ConsultaSQL = "SELECT PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PKPER_NCODIGO = " . $codigopermisos . " AND CRE_CESTADO = '" . $datos . "' AND PER_CESTADO = '" . $datos . "' ORDER BY PKCRE_NCODIGO DESC;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $nivelasesor = $FilaResultado['PER_CNIVEL'];
                break;
            }
            mysqli_free_result($ResultadoSQL);
            mysqli_close($ConexionSQL);
        } else {
            // Sin Resultados
            mysqli_close($ConexionSQL);
        }
    } else {
        // Error en la Consulta
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
    }

    
    if ($nivelasesor == null) {
        echo "<script>window.location='logout.php';</script>";
        echo $nivelasesor;
        exit;
    } else if ($nivelasesor == '') {
        echo $nivelasesor;
        echo "<script>window.location='logout.php';</script>";
        exit;
    } else if ($nivelasesor == 'AgenteCall') {
        echo "<script>window.location='AgenteCall.php';</script>";
    }else if ($nivelasesor == 'AgenteLegalizador') {
        echo "<script>window.location='Legalizacion.php';</script>";
    } else if ($nivelasesor == 'AgenteVisitas') {
        echo "<script>window.location='AgenteVisitas.php';</script>";
    } else if ($nivelasesor == 'Agente HomeOffice') {
        echo "<script>window.location='Frankenstein.php';</script>";
    } else if ($nivelasesor == 'Supervisor') {
        echo "<script>window.location='ListadoUsuarios.php';</script>";
    } else {
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

?>