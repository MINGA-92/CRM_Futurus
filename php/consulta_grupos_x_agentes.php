<?php

require('common.php');
session_start();


$cliente = $_GET['cliente'];
$cargo = $_GET['cargo'];
$campana = $_GET['campana'];
$datos = 'Activo';
$grupo = '';

//Consulta PER_CCARGO
$Campana = "COMPENSAR";
$Estado = "Activo";
$PER_CCARGO = array();
$ConsultaSQL = "SELECT PER_CCARGO FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CCAMPANA = '".$Campana."' AND PER_CESTADO = '".$Estado."' GROUP BY PER_CCARGO ORDER BY PER_CCARGO ASC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            array_push($PER_CCARGO, $FilaResultado['PER_CCARGO']);
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    echo $ErrorConsulta;
    exit;
}

//Valida si la consulta es para mostrar grupo o subgrupo
if(($cargo == 'Agente')||($cargo == 'AgenteVisitas')||($cargo == 'AgenteLegalizador')) {
    $grupo = 'PER_CGRUPO';
} else {
    $grupo = 'PER_CSUBGRUPO';
}

$controlvalidacion = 'OK';
    $ConsultaSQL = "SELECT $grupo FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CCLIENTE = '" . $cliente . "' AND PER_CCAMPANA ='" . $campana . "' AND PER_CESTADO = '" . $datos ."' GROUP BY $grupo ORDER BY $grupo ASC;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
    
                if ($FilaResultado[$grupo] == '') {
                } else if ($FilaResultado[$grupo] == null) {
                } else {
                    
                    $campana2 = $FilaResultado[$grupo];
                    echo('<option value="'.$campana2.'">'.$campana2.'</option>');

                }
            }
            mysqli_free_result($ResultadoSQL);
            mysqli_close($ConexionSQL);
            
        } else {
            // Sin Resultados
            mysqli_close($ConexionSQL);
        }
    } else {
        // Error en la Consulta
        $ErrorConsulta = $ConexionSQL->error;
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta;
        exit;
    }


