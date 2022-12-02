<?php

require('common.php');
require('funciones_generales.php');
session_start();

$Agente = $_POST['Agente'];
$NuevoNumeroFijoIndicativo = $_POST['NuevoNumeroFijoIndicativo'];
$NuevoNumeroFijo = $_POST['NuevoNumeroFijo'];
$NuevoNumeroFijoExtencion= $_POST['NuevoNumeroFijoExtencion'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$TipoInfromacionAGuardar = "TelefonoCliente";
$GuardadoPor = "Registrado por ".$Agente."";
$Estado = "Activo";

$InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('". $LlaveConsulta ."', '". $TipoInfromacionAGuardar ."', '". $NuevoNumeroFijo ."','". $NuevoNumeroFijoIndicativo ."', '". $NuevoNumeroFijoExtencion ."','". $GuardadoPor ."', '". $Estado ."')";
if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
    $ConsultaSql= "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA = '". $TipoInfromacionAGuardar ."' AND DETCLI_CDETALLE = '". $NuevoNumeroFijo ."' AND DETCLI_CDETALLE_REGISTRO = '". $GuardadoPor ."' AND DETCLI_CESTADO = '". $Estado ."' ORDER BY PKDETCLI_NCODIGO DESC LIMIT 1";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSql)){
        $CantidadResultado = $ResultadoSQL->num_rows;
        if ($CantidadResultado > 0){
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()){
                mysqli_close($ConexionSQL);
                echo $FilaResultado['PKDETCLI_NCODIGO'];
            }
        }else{
            mysqli_close($ConexionSQL);
            echo "0";
            echo "No hay Resultados";
        }
    }else{
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