<?php

require('common.php');
require('funciones_generales.php');

$TipoDocumentoContacto = $_POST['TipoDocumentoContacto'];
$Documento= $_POST['Documento'];
$Estado1 = 'Activo';

//ConsultaCliente
$CodigoCliente = "";
$ConsultaSQL = "SELECT PKCLI_NCODIGO, CLI_CDOCUMENTO, CLI_CTIPO_DOCUMENTO, CLI_CNOMBRE, CLI_CNOMBRE2, CLI_CAPELLIDO, CLI_CAPELLIDO2 FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE CLI_CTIPO_DOCUMENTO ='" . $TipoDocumentoContacto . "' AND CLI_CDOCUMENTO = '" . $Documento . "' AND CLI_CESTADO = '" . $Estado1 . "';";
if($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)){
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0){
        while($FilaResultado = $ResultadoSQL->fetch_assoc()){          
            $CodigoCliente = $FilaResultado['PKCLI_NCODIGO']; 
            $Documento = $FilaResultado['CLI_CDOCUMENTO'];
            $Nombre = $FilaResultado['CLI_CNOMBRE'];
            $Nombre2 = $FilaResultado['CLI_CNOMBRE2'];
            $Apellido = $FilaResultado['CLI_CAPELLIDO'];
            $Apellido2 = $FilaResultado['CLI_CAPELLIDO2'];

        }
    }else{
        //Sin Resultados
        $php_response = array("msg" => "Sin Resultados");        
        mysqli_close($ConexionSQL);
        echo json_encode($php_response);
        exit;
    }
}else{
    //Error en la consulta sql
    $Falla = mysqli_error($ConexionSQL);
    $php_response = array("msg" => "Error en la consulta", "Falla" => $Falla);
}

if ($CodigoCliente == " "){

}else{

    //Consulta Detalles Cliente
    $Direccion = "";
    $PaisNuevoContacto = "";
    $DepartamentoNuevoContacto = "";
    $CiudadNuevoContacto = "";
    $BarrioNuevoContacto = "";
    $FechaNacimiento = "";
    $EstadoCivil = "";
    $NumeroMovil = "";
    $NumeroFijo = "";
    $IndicativoFijo = "";
    $ExtencionFijo = "";
    $Cargo = "";
    $IngresoLaboral = "";
    $Salario = "";
    $FondoPensionCliente = "";
    $DescripcionSiafpCliente = "";
    $EmpresaCliente = "";
    $NombreEmpresa= "";


    $ConsultaSQL = "SELECT DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2, DETCLI_CDETALLE3, DETCLI_CDETALLE4 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $CodigoCliente . "' AND DETCLI_CESTADO = '" . $Estado1 . "';";
    
    if($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)){
        $CantidadResultados = $ResultadoSQL->num_rows;
        if($CantidadResultados > 0){
            while($FilaResultado = $ResultadoSQL->fetch_assoc()){

                $TipoInformacion = $FilaResultado['DETCLI_CCONSULTA'];
                $DETCLI_CDETALLE = $FilaResultado['DETCLI_CDETALLE'];
                $DETCLI_CDETALLE1 = $FilaResultado['DETCLI_CDETALLE1'];
                $DETCLI_CDETALLE2 = $FilaResultado['DETCLI_CDETALLE2'];
                $DETCLI_CDETALLE3 = $FilaResultado['DETCLI_CDETALLE3'];
                $DETCLI_CDETALLE4 = $FilaResultado['DETCLI_CDETALLE4'];
    
                if($TipoInformacion == "DireccionDomicilio"){
                    $Direccion = $DETCLI_CDETALLE;
                    $PaisNuevoContacto = $DETCLI_CDETALLE1;
                    $DepartamentoNuevoContacto = $DETCLI_CDETALLE2;
                    $CiudadNuevoContacto = $DETCLI_CDETALLE3;
                    $BarrioNuevoContacto= $DETCLI_CDETALLE4;
                    
                }else if($TipoInformacion == "FechaNacimientoCliente"){
                    $FechaNacimiento = $DETCLI_CDETALLE;

                }else if($TipoInformacion == "EstadoCivilCliente"){
                    $EstadoCivil = $DETCLI_CDETALLE;

                }else if($TipoInformacion == "CelularCliente"){
                    $NumeroMovil = $DETCLI_CDETALLE;
                }else if($TipoInformacion == "TelefonoCliente"){
                    $NumeroFijo = $DETCLI_CDETALLE;
                    $IndicativoFijo = $DETCLI_CDETALLE1;
                    $ExtencionFijo = $DETCLI_CDETALLE2;
                }else if($TipoInformacion == "CargoLaboralCliente"){
                    $Cargo = $DETCLI_CDETALLE;
                }else if($TipoInformacion == "FechaIngresoLaboralCliente"){
                    $IngresoLaboral = $DETCLI_CDETALLE;
                }else if($TipoInformacion == "SalarioCliente"){
                    $Salario = $DETCLI_CDETALLE;
                }else if($TipoInformacion == "FondoPensionCliente"){
                    $FondoPensionCliente = $DETCLI_CDETALLE;
                }else if($TipoInformacion == "DescripcionSiafpCliente"){
                    $DescripcionSiafpCliente = $DETCLI_CDETALLE;
                }else if($TipoInformacion == "EmpresaCliente"){
                    $EmpresaCliente  = $DETCLI_CDETALLE;
                }
                
                
            }
        }else{
            //Sin Resultados
            $php_response = array("msg" => "Sin Resultados");
        }

        //ConsultaNombre empresa
        $TipoConsulta= "EmpresaCliente";
        $ConsultaSQL = "SELECT CONCAT(EMP_CNOMBRE, ' ', EMP_CNOMBRE2, ' ', EMP_CAPELLIDO, ' ', EMP_CAPELLIDO2) AS NameEmpresa FROM u632406828_dbp_crmfuturus.TBL_REMPRESA, u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE PKEMP_NCODIGO = '". $EmpresaCliente ."' AND PKEMP_NCODIGO = DETCLI_CDETALLE AND DETCLI_CCONSULTA = '". $TipoConsulta ."' AND EMP_CESTADO = 'Activo' AND DETCLI_CESTADO = 'Activo';";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $NombreEmpresa= $FilaResultado['NameEmpresa'];
                }
            } else {
                //Sin Resultados
                $php_response = array("msg" => "Sin Resultados");
            }
        }else {
            $Falla = mysqli_error($ConexionSQL);
            $php_response = array("msg" => "Error en la consulta", "Falla" => $Falla);
        }
        
        $php_response = array("msg" => "Ok", "CodigoCliente" => $CodigoCliente, "Nombre" =>  $Nombre, "Nombre2" => $Nombre2, "Apellido" => $Apellido , "Apellido2" => $Apellido2, "FechaNacimiento" => $FechaNacimiento, "EstadoCivil" => $EstadoCivil, "Direccion" => $Direccion, "PaisNuevoContacto" => $PaisNuevoContacto, "DepartamentoNuevoContacto" => $DepartamentoNuevoContacto , "CiudadNuevoContacto" => $CiudadNuevoContacto, "BarrioNuevoContacto" => $BarrioNuevoContacto  , "NumeroMovil" => $NumeroMovil, "NumeroFijo" =>$NumeroFijo , "IndicativoFijo" => $IndicativoFijo , "ExtencionFijo" => $ExtencionFijo, "Cargo" => $Cargo, "IngresoLaboral" => $IngresoLaboral ,"Salario" => $Salario ,"FondoPensionCliente" =>$FondoPensionCliente ,"DescripcionSiafpCliente" => $DescripcionSiafpCliente ,"NombreEmpresa" => $NombreEmpresa);


    }else{
        //Error en la consulta sql
        $Falla = mysqli_error($ConexionSQL);
        
        $php_response = array("msg" => "Error en la consulta", "Falla" => $Falla);
    }

    
}


mysqli_close($ConexionSQL);
echo json_encode($php_response);

?>
