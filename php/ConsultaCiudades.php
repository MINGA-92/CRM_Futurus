<?php

require('common.php');
require('funciones_generales.php');

$TextoPaisDomicilio = $_POST['TextoPaisDomicilio'];
$TextoDepartamentoDomicilio = $_POST['TextoDepartamentoDomicilio'];

$ListadoCiudades = "";
$ConsultaSQL = "SELECT EST_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RESTANDAR WHERE  EST_CCONSULTA = 'cmbCiudades' AND EST_CDETALLE = '" . $TextoPaisDomicilio . "' AND EST_CDETALLE1 = '" . $TextoDepartamentoDomicilio . "' AND EST_CESTADO = 'Activo' GROUP BY EST_CDETALLE2;";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0){
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()){
            $ListadoCiudades = $ListadoCiudades . '<option value="' . $FilaResultado['EST_CDETALLE2'] . '">' . $FilaResultado['EST_CDETALLE2'] . '</option>';
        }
        $php_response = array("msg" => "Ok", "Resultado" => $ListadoCiudades);
        mysqli_close($ConexionSQL);
        echo json_encode($php_response);
        exit;
    }else{
        //Sin Resultados
        $php_response = array("msg" => "SinResultados");
        mysqli_close($ConexionSQL);
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
