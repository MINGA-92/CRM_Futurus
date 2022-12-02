<?php

require('common.php');
require('funciones_generales.php');
session_start();

$Agente = $_POST['Agente'];
$codigo = $_POST['codigo'];
$ActualizadoPor = "Registro generado por ".$Agente."";
$estado = "Activo";
$EstadoActualizar = "InActivo";
$ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE_REGISTRO = '". $ActualizadoPor ."', DETCLI_CESTADO = '" . $EstadoActualizar . "' WHERE DETCLI_CCONSULTA= 'ArchivosAdjuntos' AND PKDETCLI_NCODIGO = " . $codigo . "  AND DETCLI_CESTADO = '" . $estado . "'";
if ($ResultadoSQL = $ConexionSQL->query($ActualizarSql)) {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '1';
} else {
    mysqli_close($ConexionSQL);
    $ErrorConsulta = mysqli_error($ConexionSQL);
    echo $ErrorConsulta;
}

?>