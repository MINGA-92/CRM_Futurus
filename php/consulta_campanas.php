<?php

require('common.php');
session_start();
$opcion = $_GET['opcion'];
$Campana = "FUTURUS";

$ConsultaSQL = "SELECT PER_CCAMPANA FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CCLIENTE = '" . $opcion . "' AND PER_CCAMPANA = '".$Campana."' AND PER_CESTADO = 'Activo' GROUP BY PER_CCAMPANA ORDER BY PER_CCAMPANA ASC;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $campana = $FilaResultado['PER_CCAMPANA'];
                echo('<option value="'.$campana.'">'.$campana.'</option>');
            }
            $ConexionSQL->close();
            mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
        $ConexionSQL->close();
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = $ConexionSQL->error;
    $ConexionSQL->close();
}
?>