<?php
require('common.php');
require('funciones_generales.php');
session_start();
$Agente = $_POST['Agente'];
$codigo = $_POST['codigo'];
$LlaveConsulta = $_POST['LlaveConsulta'];


$ActualizadoPor = "Actualizado por " . $Agente;
$RegistradoPor = "Registrado por " . $Agente;
$Estado1 = "Activo";
$Estado2 = "InActivo";


//Consultar Cual es la direccion principal actual, e inactivar;
$ConsultaSQL = "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE DETCLI_CCONSULTA = 'DireccionDomicilioPrincipal' AND DETCLI_CESTADO = '" . $Estado1 . "';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CodigoDireccionPActual = $FilaResultado['PKDETCLI_NCODIGO'];
            break;
        }

        //Se inactiva la direccion principal actual
        $ActualizacionSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE_REGISTRO = '" . $ActualizadoPor . "', DETCLI_CESTADO = '" . $Estado2 . "' WHERE PKDETCLI_NCODIGO = " . $CodigoDireccionPActual . "";
        if ($ResultadoSQL = $ConexionSQL->query($ActualizacionSQL)) {

            //Se crea la nueva direccion actual;
            $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'DireccionDomicilioPrincipal', '" . $codigo . "','" . $RegistradoPor . "', '" . $Estado1 . "')";
            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {

                $php_response = array("msg" => "Ok");
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

        //Se crea la nueva direccion actual;
        $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $LlaveConsulta . "', 'DireccionDomicilioPrincipal', '" . $codigo . "','" . $RegistradoPor . "', '" . $Estado1 . "')";
        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {

            $php_response = array("msg" => "Ok");
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
    mysqli_close($ConexionSQL);
    $Falla = mysqli_error($ConexionSQL);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}
