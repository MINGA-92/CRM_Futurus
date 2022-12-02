<?php 


require('common.php');
require('funciones_generales.php');
session_start();

$Empresas = $_POST['Empresas'];

$ConsultaSQL = "SELECT DETEMP_CDETALLE, DETEMP_CDETALLE1 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE FKDETEMP_NCLI_NCODIGO = '" . $Empresas . "' AND DETEMP_CCONSULTA = 'EncargadoRRHH' AND DETEMP_CESTADO = 'Activo';";

if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)){
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0){
        while($FilaResultado = $ResultadoSQL->fetch_assoc()){
            $NombreEncargado = $FilaResultado['DETEMP_CDETALLE'];
            $CorreoEncargado = $FilaResultado['DETEMP_CDETALLE1'];
        }
    mysqli_free_result($ResultadoSQL);
    $Datos2 = array();
    $ConsultaSQL = "SELECT DISTINCT CLI_CDOCUMENTO, CLI_CNOMBRE, CLI_CAPELLIDO, CLI_CESTADO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE t1 inner join u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE t2  on t1.PKCLI_NCODIGO = t2.FKDETCLI_NCLI_NCODIGO WHERE DETCLI_CCONSULTA='EmpresaCliente' AND DETCLI_CDETALLE= '" . $Empresas . "'";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $Identificacion = $FilaResultado['CLI_CDOCUMENTO'];
                $Nombre = $FilaResultado['CLI_CNOMBRE'];
                $Apellido = $FilaResultado['CLI_CAPELLIDO'];
                $Estado = $FilaResultado['CLI_CESTADO'];

                array_push($Datos2, array("0" => $Identificacion, "1" => $Nombre, "2" => $Apellido, "3" => $Estado));
            }
        }else {
            //Sin Resultados
            
        }
    } else {
        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_free_result($ResultadoSQL);
        mysqli_close($ConexionSQL);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        echo "<script>window.location='logout.php';</script>";
        exit;
    }

        $php_response = array("msg" => "Ok", "NombreEncargado" => $NombreEncargado, "CorreoEncargado" => $CorreoEncargado, "Empleados" =>$Datos2);    
        mysqli_close($ConexionSQL);
        echo json_encode($php_response);
        exit;    
    }else{
        //Sin Resultados
    }
}else{
    $Falla = mysqli_error($ConexionSQL);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    mysqli_close($ConexionSQL);
    echo json_encode($php_response);
}



?>