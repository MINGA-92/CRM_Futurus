
<?php

//Conexion a Bd y Funciones generales
require('common.php');
require('funciones_generales.php');
session_start();

date_default_timezone_set("America/Bogota");               
$Fecha=  date ("Y-m-d");
$Hoy= $Fecha .' '. '00:00:00';

//Captura de Informacion del caso
$EstadoCliente = $_POST['EstadoCliente'];
$LlaveConsulta = $_POST['LlaveConsulta'];
$CodigoEmpresa = $_POST['CodigoEmpresa'];
$ValorEmpresa = $_POST['ValorEmpresa'];
$FondoActual = $_POST['FondoActual'];
$FechaFuturo = $_POST['FechaFuturo'];
$DescripcionSiafpCliente2 = $_POST['DescripcionSiafpCliente2'];
$FechaRegistro = $_POST['FechaRegistro'];
if($FechaRegistro == ""){
    $FechaRegistro = $Hoy;
}else{
    $FechaRegistro = $_POST['FechaRegistro'];
}
$FechaGestion = $FechaRegistro;

//Codigo Agente PENAFL_CFONDO_ANTERIOR
$Agente = $_POST['Agente'];
$Registro = "Registrado por: " . $Agente;
$ActualizadoPor = "Actualizado por: " . $Agente;

//Numero del caso
$CodigoCaso = $_POST['CodigoCaso'];


/*
    echo("Hoy: ");
    echo($Hoy . " => ");
    echo("EstadoCliente: ");
    echo($EstadoCliente . " => ");
    echo("FechaRegistro: ");
    echo($FechaRegistro . " => ");
    echo("LlaveConsulta: ");
    echo($LlaveConsulta . " => ");
    echo("CodigoEmpresa: ");
    echo($CodigoEmpresa . " => ");
    echo("ValorEmpresa: ");
    echo($ValorEmpresa . " => ");
    echo("FondoActual: ");
    echo($FondoActual . " => ");
    echo("FechaFuturo: ");
    echo($FechaFuturo . " => ");
    echo("DescripcionSiafpCliente2: ");
    echo($DescripcionSiafpCliente2 . " => ");
    echo("Agente: ");
    echo($Agente . " => ");
    echo("CodigoCaso: ");
    echo($CodigoCaso . " => ");

    exit;
*/

//Estados
$EstadoActivo = 'Activo';
$EstadoInActivo = 'InActivo';
$EstadoPendiente = 'Pendiente';
$EstadoCerrado = "Cerrado";


//Consulta de informacion de Fondo Nuevo
$ConsultaSql = "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO= 'Activo';";
if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
    $CantidadResultados = $ResultadoSql->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
            $CodigoFondoAlQueVa = $FilaResultado['PKDETCLI_NCODIGO'];
            break;
        }

        $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '". $EstadoCliente ."' WHERE PKDETCLI_NCODIGO = '". $CodigoFondoAlQueVa ."' AND DETCLI_CCONSULTA= 'FondoAlQueVa' AND DETCLI_CESTADO = 'Activo';";
        if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
            $Comprobar = "Ok";
        } else {
            mysqli_close($ConexionSQL);
            echo $ErrorConsulta = mysqli_error($ConexionSQL);
        }
    } else {
        //No hay resultados
        //Insertar nuevo Registro
        $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CESTADO) VALUES ('". $LlaveConsulta ."', 'FondoAlQueVa', '". $EstadoCliente ."', 'Activo');";
        if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
            $Comprobar = "Ok";
            //mysqli_close($ConexionSQL);
            //echo 1;

        } else {
            $Comprobar = "False";
            mysqli_close($ConexionSQL);
            echo $ErrorConsulta = mysqli_error($ConexionSQL);
        }
    }
} else {
    echo "Error En Consulta Informacion De Fondo Nuevo";
    mysqli_close($ConexionSQL);
    echo $ErrorConsulta = mysqli_error($ConexionSQL);
}


if ($Comprobar == "Ok") {

    //Consulta de informacion de Fondo Actual
    $ConsultaSql = "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO= 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $CodigoFondoPensionCliente = $FilaResultado['PKDETCLI_NCODIGO'];
                break;
            }

            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '". $FondoActual ."' WHERE PKDETCLI_NCODIGO = '". $CodigoFondoPensionCliente ."' AND DETCLI_CCONSULTA= 'FondoPensionCliente' AND DETCLI_CESTADO = 'Activo';";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
                $Comprobar = "Ok";
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
        } else {
            //No hay resultados
            //Insertar nuevo Registro
            $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CESTADO) VALUES ('". $LlaveConsulta ."', 'FondoPensionCliente', '". $FondoActual ."', 'Activo');";
            if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                $Comprobar = "Ok";
                //mysqli_close($ConexionSQL);
                //echo 1;

            } else {
                $Comprobar = "False";
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }
    
} else {
    mysqli_close($ConexionSQL);
    echo "Error En Consulta Informacion De Fondo Actual";
    echo $ErrorConsulta = mysqli_error($ConexionSQL);
}



if ($Comprobar == "Ok") {

    //Consulta de informacion de Descripcion Siaf pCliente
    $ConsultaSql = "SELECT PKDETCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETCLI_CCONSULTA= 'DescripcionSiafpCliente' AND DETCLI_CESTADO= 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $CodigoDescripcionSiafpCliente = $FilaResultado['PKDETCLI_NCODIGO'];
                break;
            }

            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '". $DescripcionSiafpCliente2 ."' WHERE PKDETCLI_NCODIGO = '". $CodigoDescripcionSiafpCliente ."' AND DETCLI_CCONSULTA= 'DescripcionSiafpCliente' AND DETCLI_CESTADO = 'Activo';";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
                $Comprobar = "Ok";
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
        } else {
            //No hay resultados
            //Insertar nuevo Registro
            $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CESTADO) VALUES ('". $LlaveConsulta ."', 'DescripcionSiafpCliente', '". $DescripcionSiafpCliente2 ."', 'Activo');";
            if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                $Comprobar = "Ok";
                //mysqli_close($ConexionSQL);
                //echo 1;

            } else {
                $Comprobar = "False";
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }
    
} else {
    mysqli_close($ConexionSQL);
    echo "Error En Consulta Informacion De Fondo Actual";
    echo $ErrorConsulta = mysqli_error($ConexionSQL);
}




if ($Comprobar == "Ok") {

    //Consulta de informacion de Valor Empresa
    $ConsultaSql = "SELECT PKDETEMP_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA WHERE FKDETEMP_NCLI_NCODIGO = '". $LlaveConsulta ."' AND DETEMP_CCONSULTA= 'ValorEmpresa' AND DETEMP_CESTADO= 'Activo';";
    if ($ResultadoSql = $ConexionSQL->query($ConsultaSql)) {
        $CantidadResultados = $ResultadoSql->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSql->fetch_assoc()) {
                $CodigoValorEmpresa = $FilaResultado['PKDETEMP_NCODIGO'];
                break;
            }

            $ActualizarSql = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA SET DETEMP_CDETALLE = '". $ValorEmpresa ."' WHERE PKDETEMP_NCODIGO = '". $CodigoValorEmpresa ."' AND DETEMP_CCONSULTA= 'ValorEmpresa' AND DETEMP_CESTADO = 'Activo'";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSql)) {
                mysqli_close($ConexionSQL);
                echo 1;
            } else {
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
        } else {
            //No hay resultados
            //Insertar nuevo Registro
            $InsertarSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CESTADO) VALUES ('". $LlaveConsulta ."', 'ValorEmpresa', '". $ValorEmpresa ."', 'Activo');";
            if ($ResultadoSql = $ConexionSQL->query($InsertarSql)) {
                
                mysqli_close($ConexionSQL);
                echo 1;

            } else {
                $Comprobar = "False";
                mysqli_close($ConexionSQL);
                echo $ErrorConsulta = mysqli_error($ConexionSQL);
            }
        }
    } else {
        mysqli_close($ConexionSQL);
        echo $ErrorConsulta = mysqli_error($ConexionSQL);
    }
    
} else {
    mysqli_close($ConexionSQL);
    echo "Error En Consulta Informacion De Fondo Actual";
    echo $ErrorConsulta = mysqli_error($ConexionSQL);
}

?>
