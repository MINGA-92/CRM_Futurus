
<?php

require('common.php');
require('funciones_generales.php');
session_start();


$Agente = $_POST['Agente'];
$id = $_POST['id'];
$casos = $_POST['casos'];

$CantidadCasos = explode(",", $casos);
$Estado1 = "Activo";
$Estado2 = "InActivo";
$ActualizadoPor = "Actualizado por " . $Agente;
$RegistradoPor = "Registrado por " . $Agente;

for ($i = 0; $i < count($CantidadCasos); $i++) {
    $CodigoCaso = $CantidadCasos[$i];

    $ConsultaSql = "SELECT FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_COBSERVACIONES, PENCAL_CESTADO_FINAL, PENCAL_CESTADO_FINAL2, PENCAL_CFECHA_COMUNICACION, PENCAL_CFECHA_EXPEDIENTE, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CFECHA_REGISTRO, PENCAL_CFECHA_MODIFICACION FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PKPENCAL_NCODIGO = " . $CodigoCaso . ";";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
            
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $FKPENCAL_NPKPER_NCODIGO = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                $FKPENCAL_NPKCLI_NCODIGO = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];
                $PENCAL_CFONDO_NUEVO = $FilaResultado['PENCAL_CFONDO_NUEVO'];
                $PENCAL_COBSERVACIONES = $FilaResultado['PENCAL_COBSERVACIONES'];
                $PENCAL_CESTADO_FINAL = $FilaResultado['PENCAL_CESTADO_FINAL'];
                $PENCAL_CESTADO_FINAL2 = $FilaResultado['PENCAL_CESTADO_FINAL2'];
                $PENCAL_CFECHA_COMUNICACION = $FilaResultado['PENCAL_CFECHA_COMUNICACION'];
                $PENCAL_CFECHA_EXPEDIENTE = $FilaResultado['PENCAL_CFECHA_EXPEDIENTE'];
                $PENCAL_CFECHA_OFRECIMIENTO = $FilaResultado['PENCAL_CFECHA_OFRECIMIENTO'];
                $PENCAL_CFECHA_REGISTRO = $FilaResultado['PENCAL_CFECHA_REGISTRO'];
                $PENCAL_CFECHA_MODIFICACION = $FilaResultado['PENCAL_CFECHA_MODIFICACION'];
                
                }
                $UpdateSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL SET PENCAL_CESTADO_FINAL= 'Asignado', PENCAL_CESTADO = '" . $Estado2 . "' WHERE PKPENCAL_NCODIGO ='" . $CodigoCaso . "' ;";
                if ($ResultadoSql = $ConexionSQL->query($UpdateSql)) {
                    $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFONDO_NUEVO, PENCAL_COBSERVACIONES, PENCAL_CESTADO_FINAL, PENCAL_CESTADO_FINAL2, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES ('" . $id . "', '".$FKPENCAL_NPKCLI_NCODIGO."', '".$PENCAL_CFONDO_NUEVO."', '".$PENCAL_COBSERVACIONES."','".$PENCAL_CESTADO_FINAL."', '".$PENCAL_CESTADO_FINAL2."','".$PENCAL_CFECHA_OFRECIMIENTO."','" . $ActualizadoPor . "' ,'" . $Estado1 . "');";
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