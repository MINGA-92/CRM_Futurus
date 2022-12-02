
<?php

require('common.php');
require('funciones_generales.php');
session_start();


$Agente = $_POST['Agente'];
echo $Agente;
echo "=>";
$id = $_POST['id'];
echo $id;
echo "=>";
$casos = $_POST['casos'];
echo $casos;
echo "=>";
exit;

$CantidadCasos = explode(",", $casos);
echo $CantidadCasos;
exit;
$Estado1 = "Activo";
$Estado2 = "InActivo";
$ActualizadoPor = "Actualizado por " . $Agente;
$RegistradoPor = "Registrado por " . $Agente;

for ($i = 0; $i < count($CantidadCasos); $i++) {
    $CodigoCaso = $CantidadCasos[$i];

    $ConsultaSql = "SELECT FKPENAFL_NPKPENCAL_NCODIGO, PENAFL_CFECHA_AGENDAMIENTO, PENAFL_COBSERVACIONES_AGENDA, PENAFL_CESTADO_FINAL_AGENDA, PENAFL_CESTADO_FINAL2_AGENDA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION;";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
            
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FKPENAFL_NPKPENCAL_NCODIGO = $FilaResultado['FKPENAFL_NPKPENCAL_NCODIGO'];
                $PENAFL_COBSERVACIONES_AGENDA = $FilaResultado['PENAFL_COBSERVACIONES_AGENDA'];
                $PENAFL_CESTADO_FINAL_AGENDA = $FilaResultado['PENAFL_CESTADO_FINAL_AGENDA'];
                $PENAFL_CESTADO_FINAL2_AGENDA = $FilaResultado['PENAFL_CESTADO_FINAL2_AGENDA'];
                $PENAFL_CFECHA_AGENDAMIENTO = $FilaResultado['PENAFL_CFECHA_AGENDAMIENTO'];
                
                }
                $UpdateSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION SET PENALF_CDETALLE_REGISTRO = '" . $ActualizadoPor . "', PENALF_CESTADO = '" . $Estado2 . "' WHERE PKPENAFL_NCODIGO ='" . $CodigoCaso . "' ;";
                if ($ResultadoSql = $ConexionSQL->query($UpdateSql)) {
                    $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION (FKPENAFL_NPKPENCAL_NCODIGO, PENAFL_CFECHA_AGENDAMIENTO, PENAFL_COBSERVACIONES_AGENDA, PENAFL_CESTADO_FINAL_AGENDA, PENAFL_CESTADO_FINAL2_AGENDA, PENALF_CDETALLE_REGISTRO, PENALF_CESTADO) VALUES ('" . $FKPENAFL_NPKPENCAL_NCODIGO . "', '" . $FKPENAFL_NPKPENCAL_NCODIGO . "', '" . $PENAFL_COBSERVACIONES_AGENDA . "', '" . $PENAFL_CESTADO_FINAL_AGENDA . "', '" . $PENAFL_CESTADO_FINAL2_AGENDA ."', '" . $RegistradoPor. "', '" . $Estado1 . "');";
                    if($ResultadoSql = $ConexionSQL->query($InsercionSql)){

                    }else{
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
                //Sin Resultados
                mysqli_close($ConexionSQL);
                $php_response = array("msg" => "Sin Resultados");
                echo json_encode($php_response);
                exit;
            }
        } else {
            //Error en la consulta
            mysqli_close($ConexionSQL);
            $Falla = mysqli_error($ConexionSQL);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }
    } else {
        //Error en la consulta
        mysqli_close($ConexionSQL);
        $Falla = mysqli_error($ConexionSQL);
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        echo json_encode($php_response);
        exit;
    }
}

$php_response = array("msg" => "Ok");
mysqli_close($ConexionSQL);
echo json_encode($php_response);
?>