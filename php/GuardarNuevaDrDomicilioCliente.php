<?php 

require('common.php');
require('funciones_generales.php');
session_start();

$DireccionDomCliente = $_POST['DireccionDomCliente'];
$PaisCliente = $_POST['PaisCliente'];
$DepartamentoCliente = $_POST['DepartamentoCliente'];
$CiudadCliente = $_POST['CiudadCliente'];
$BarrioCliente = $_POST['BarrioCliente'];
$Agente = $_POST['Agente'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$TipoInfromacionAGuardar = "DireccionDomicilio";
$GuardadoPor = "Registro generado por ".$Agente."";
$Estado = "Activo";

$InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE3, DETCLI_CDETALLE4, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('". $LlaveConsulta ."', '". $TipoInfromacionAGuardar ."', '". $DireccionDomCliente ."', '". $PaisCliente ."', '". $DepartamentoCliente ."', '". $CiudadCliente ."', '". $BarrioCliente ."', '". $GuardadoPor ."', '". $Estado ."')";
if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
    $ConsultaSql= "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA = '". $TipoInfromacionAGuardar ."' AND DETCLI_CDETALLE = '". $DireccionDomCliente ."' AND DETCLI_CDETALLE_REGISTRO = '". $GuardadoPor ."' AND DETCLI_CESTADO = '". $Estado ."' ORDER BY PKDETCLI_NCODIGO DESC LIMIT 1";
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