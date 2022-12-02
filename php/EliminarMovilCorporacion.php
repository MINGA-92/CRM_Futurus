<?php 

require('common.php');
require('funciones_generales.php');
session_start();

$Agente = $_POST['Agente'];
$codigo = $_POST['codigo'];
$ModuloActualizar = "CelularEmpresa";
$ActualizadoPor = "Registro actualizado por ".$Agente."";
$estado = "Activo";
$EstadoActualizar = "InActivo";
$ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA SET DETEMP_CDETALLE_REGISTRO = '". $ActualizadoPor ."', DETEMP_CESTADO = '" . $EstadoActualizar . "' WHERE PKDETEMP_NCODIGO = " . $codigo . " AND DETEMP_CCONSULTA = '" . $ModuloActualizar . "' AND DETEMP_CESTADO = '" . $estado . "'";
if ($ResultadoSQL = $ConexionSQL->query($ActualizarSql)) {
    echo '1';
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo $ErrorConsulta;
}

?>


