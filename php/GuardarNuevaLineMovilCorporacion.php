<?php

require('common.php');
require('funciones_generales.php');
session_start();

$Agente = $_POST['Agente'];
$NueviMovilCorporacion = $_POST['NueviMovilCorporacion'];
$CodigoEmpresa = $_POST['CodigoEmpresa'];
$TipoInfromacionAGuardar = "CelularEmpresa";
$GuardadoPor = "Registro generado por " . $Agente . "";
$Estado = "Activo";

$InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('". $CodigoEmpresa . "', '". $TipoInfromacionAGuardar . "','". $NueviMovilCorporacion . "','". $GuardadoPor . "','". $Estado . "')";
if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
    $ConsultaSql = "SELECT PKDETEMP_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE FKDETEMP_NCLI_NCODIGO = '" . $CodigoEmpresa . "' AND  DETEMP_CCONSULTA = '" . $TipoInfromacionAGuardar . "' AND DETEMP_CDETALLE = '" . $NueviMovilCorporacion . "' AND DETEMP_CDETALLE_REGISTRO = '" . $GuardadoPor . "' AND DETEMP_CESTADO = '" . $Estado . "' ORDER BY PKDETEMP_NCODIGO DESC LIMIT 1";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultado = $ResultadoSQL->num_rows;
        if ($CantidadResultado > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                mysqli_close($ConexionSQL);
                echo $FilaResultado['PKDETEMP_NCODIGO'];
            }
        } else {
            mysqli_close($ConexionSQL);
            echo "0";
            echo "No hay Resultados";
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo "0";
        echo $ErrorConsulta;
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo "0";
    echo $ErrorConsulta;
}
?>