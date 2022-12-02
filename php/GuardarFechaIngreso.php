
<?php

require('common.php');
require('funciones_generales.php');
session_start();

$Agente = $_POST['Agente'];
$FechaIngreso =  $_POST['FechaIngreso'];
$LlaveConsulta = $_POST['LlaveConsulta'];


$GuardadoPor = "Registro generado por " . $Agente . "";
$ActualizadoPor = "Actualizado por " . $Agente . "";
$Estado = "Activo";
$Estado2 = "InActivo";

$ConsultaSql = "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $LlaveConsulta . "' AND DETCLI_CCONSULTA = 'FechaIngresoLaboral' AND DETCLI_CESTADO = 'Activo';";
if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
    $CantidadResultados = $ResultadoSql->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
            $CodigoActualizacion = $FilaResultado['PKDETCLI_NCODIGO'];
        }

        $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CESTADO = '" . $Estado2 . "', DETCLI_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKDETCLI_NCODIGO = '" . $CodigoActualizacion . "'";
        if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
            $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'FechaIngresoLaboral', '" . $FechaIngreso . "', '" . $GuardadoPor . "', '" . $Estado . "');";
            if ($ResultadoSql = $ConexionSQL->query($InsercionSql)) {
                $php_response = array("msg" => "Ok");
                mysqli_close($ConexionSQL);
                echo json_encode($php_response);
                exit;
            } else {
                mysqli_close($ConexionSQL);
                $Falla = mysqli_error($ConexionSQL);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                echo json_encode($php_response);
                exit;
            }
        } else {
            mysqli_close($ConexionSQL);
            $Falla = mysqli_error($ConexionSQL);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
    } else {
        $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'FechaIngresoLaboral', '" . $FechaIngreso . "', '" . $GuardadoPor . "', '" . $Estado . "');";
        if ($ResultadoSql = $ConexionSQL->query($InsercionSql)) {
            $php_response = array("msg" => "Ok");
            mysqli_close($ConexionSQL);
            echo json_encode($php_response);
            exit;
        } else {
            mysqli_close($ConexionSQL);
            $Falla = mysqli_error($ConexionSQL);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
    }
} else {
    //Error en la consulta
    mysqli_close($ConexionSQL);
    $Falla = mysqli_error($ConexionSQL);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}
$php_response = array("msg" => "Ok");
mysqli_close($ConexionSQL);
echo json_encode($php_response);
?>
