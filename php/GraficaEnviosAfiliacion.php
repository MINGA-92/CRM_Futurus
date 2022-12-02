
<?php

require('common.php');
require('funciones_generales.php');
require('ConsultaNotificaciones.php');
session_start();


if (isset($_SESSION['codigopermiso'])) {
} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}
$codigopermisos = $_SESSION['codigopermiso'];
$codigopermisos = trim($codigopermisos);


if (isset($_POST['FechaFinal2'])) {
    $Hora= '00:00:00';
    $Hora2= '23:59:59';
    $FechaI = $_POST['FechaInicial2']; 
    $FechaF = $_POST['FechaFinal2'];
    $FechaInicial= $FechaI. ' ' .$Hora;
    $FechaFinal= $FechaF. ' ' .$Hora2;
} else {
    date_default_timezone_set("America/Bogota");               
    $Fecha=  date ("Y-m-d");
    $FechaInicial= '2012-01-30' .' '. '00:00:00';
    $FechaFinal= $Fecha . ' '  .'23:59:59';
}


// Consulta Nombre usuario y Supervisor
$datos = 'Activo';
$ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, PER_CGRUPO, PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKPER_NCODIGO = " . $codigopermisos . " AND PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PER_CESTADO = 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {

            $CRE_CUSUARIO = $FilaResultado['CRE_CUSUARIO'];
            $AGENTE = $FilaResultado['PKPER_NCODIGO'];
            $nombre = null;
            $nombrecompleto = null;
            $nombre = $FilaResultado['CRE_CNOMBRE'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombre;
            }

            $nombre = null;
            $nombre = $FilaResultado['CRE_CNOMBRE2'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombrecompleto . ' ' . $nombre;
            }

            $nombre = null;
            $nombre = $FilaResultado['CRE_CAPELLIDO'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombrecompleto . ' ' . $nombre;
            }

            $nombre = null;
            $nombre = $FilaResultado['CRE_CAPELLIDO2'];
            if ($nombre == null || $nombre == '') {
            } else {
                $nombrecompleto = $nombrecompleto . ' ' . $nombre;
            }

            if ($nombrecompleto == null || $nombrecompleto == '') {
                $nombrecompleto = $FilaResultado['CRE_CUSUARIO'];
            } else {
            }

            $PER_CNIVEL = $FilaResultado['PER_CNIVEL'];
            $grupotrabajo = $FilaResultado['PER_CGRUPO'];
            break;
        }
        mysqli_free_result($ResultadoSQL);
    } else {
        // Sin Resultados
        mysqli_close($ConexionSQL);
        echo "<script>window.location='logout.php';</script>";
        exit;
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    echo "<script>window.location='logout.php';</script>";
    exit;
}


//Consulta Agentes
$ListadoPorAgente = "";
$ConsultaSQL = "SELECT PKCRE_NCODIGO AS CODIGO_CREDENCIAL, PKPER_NCODIGO AS CODIGO_PERMISOS, CONCAT (CRE_CNOMBRE,' ',CRE_CNOMBRE2,' ',CRE_CAPELLIDO,' ',CRE_CAPELLIDO2) AS NOMBRE_AGENTE FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PER_CNIVEL != 'Supervisor' AND CRE_CESTADO= 'Activo' AND PER_CESTADO= 'Activo';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $CODIGO_CREDENCIAL = $FilaResultado['CODIGO_CREDENCIAL'];
            $CODIGO_PERMISOS = $FilaResultado['CODIGO_PERMISOS'];
            $ListadoPorAgente = $ListadoPorAgente . '<option value="' . $FilaResultado['CODIGO_PERMISOS'] . '">' . $FilaResultado['NOMBRE_AGENTE'] . '</option>';
        }
    } else {
        //Sin Resultados
        echo '<script>alert("No Hay Agentes");</script>';
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    mysqli_close($ConexionSQL);
    exit;
}


if (isset($_POST['SelecionarPorAgente2'])) {
    $SelecionarPorAgente= $_POST['SelecionarPorAgente2'];

    //Consulta Tods Los Casos Afiliacion Exitoso Por Agente
    $DatosBono = array();
    $ConsultaSQL = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE FKPENAFL_NPKPER_NCODIGO= '". $SelecionarPorAgente ."' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            $CasosEnvioDeAfiliacion = $CantidadResultados;
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ValorBono=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono, $ValorBono);
                
            }

        } else {
            $CasosEnvioDeAfiliacion = 0;
        }

        $ValorNetoBono = 0;
        for ($i=0; $i < count($DatosBono); $i++) {
            $ValorUnitarioBono = $DatosBono[$i];
            $ValorNetoBono = $ValorUnitarioBono + $ValorNetoBono;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Bono Por Consignación Por Agente
    $DatosBono1 = array();
    $ConsultaSQL1 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE FKPENAFL_NPKPER_NCODIGO= '". $SelecionarPorAgente ."' AND PENAFL_CBONO_AFILIACION= 'Bono Por Consignación' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
        $CantidadResultados1 = $ResultadoSQL1->num_rows;
        if ($CantidadResultados1 > 0) {
            $BonoPorConsignación = $CantidadResultados1;
            while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {
                $ValorBono1=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono1, $ValorBono1);
                
            } 

        } else {
            $BonoPorConsignación = 0;
        }

        $ValorNetoBono1 = 0;
        for ($i=0; $i < count($DatosBono1); $i++) {
            $ValorUnitarioBono1 = $DatosBono1[$i];
            $ValorNetoBono1 = $ValorUnitarioBono1 + $ValorNetoBono1;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Bono En Efectivo Por Agente
    $DatosBono2 = array();
    $ConsultaSQL2 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE FKPENAFL_NPKPER_NCODIGO= '". $SelecionarPorAgente ."' AND PENAFL_CBONO_AFILIACION= 'Bono En Efectivo' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
        $CantidadResultados2 = $ResultadoSQL2->num_rows;
        if ($CantidadResultados2 > 0) {
            $BonoEnEfectivo = $CantidadResultados2;
            while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                $ValorBono2=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono2, $ValorBono2);
                
            }

        } else {
            $BonoEnEfectivo = 0;
        }

        $ValorNetoBono2 = 0;
        for ($i=0; $i < count($DatosBono2); $i++) {
            $ValorUnitarioBono2 = $DatosBono2[$i];
            $ValorNetoBono2 = $ValorUnitarioBono2 + $ValorNetoBono2;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Bono De Tienda Por Agente
    $DatosBono3 = array();
    $ConsultaSQL3 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE FKPENAFL_NPKPER_NCODIGO= '". $SelecionarPorAgente ."' AND PENAFL_CBONO_AFILIACION= 'Bono De Tienda' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
        $CantidadResultados3 = $ResultadoSQL3->num_rows;
        if ($CantidadResultados3 > 0) {
            $BonoDeTienda = $CantidadResultados3;
            while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                $ValorBono3=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono3, $ValorBono3);
                
            }

        } else {
            $BonoDeTienda = 0;
        }

        $ValorNetoBono3 = 0;
        for ($i=0; $i < count($DatosBono3); $i++) {
            $ValorUnitarioBono3 = $DatosBono3[$i];
            $ValorNetoBono3 = $ValorUnitarioBono3 + $ValorNetoBono3;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Sin Bono Por Agente
    $DatosBono4 = array();
    $ConsultaSQL4 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE FKPENAFL_NPKPER_NCODIGO= '". $SelecionarPorAgente ."' AND PENAFL_CBONO_AFILIACION= 'Sin Bono' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
        $CantidadResultados4 = $ResultadoSQL4->num_rows;
        if ($CantidadResultados4 > 0) {
            $SinBono = $CantidadResultados4;
            while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                $ValorBono4=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono4, $ValorBono4);
                
            }

        } else {
            $SinBono = 0;
        }

        $ValorNetoBono4 = 0;
        for ($i=0; $i < count($DatosBono4); $i++) {
            $ValorUnitarioBono4 = $DatosBono4[$i];
            $ValorNetoBono4 = $ValorUnitarioBono4 + $ValorNetoBono4;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }


} else {

    /*if (isset($_POST['FechaFinal2'])){

        //Consulta Tods Los Casos Afiliacion Exitoso Por Agente
        $DatosBono = array();
        $ConsultaSQL = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE PENALF_CFECHA_REGISTRO= BETWEEN '". $FechaInicial ."' AND '". $FechaFinal ."' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if ($CantidadResultados > 0) {
                $CasosEnvioDeAfiliacion = $CantidadResultados;
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $ValorBono=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                    array_push($DatosBono, $ValorBono);
                    
                }

            } else {
                $CasosEnvioDeAfiliacion = 0;
            }

            $ValorNetoBono = 0;
            for ($i=0; $i < count($DatosBono); $i++) {
                $ValorUnitarioBono = $DatosBono[$i];
                $ValorNetoBono = $ValorUnitarioBono + $ValorNetoBono;
            }
            
        } else {
            //Errro en la consulta sql
            echo mysqli_error($ConexionSQL);
            exit;
        }

    }*/

    //Consulta Todos Los Casos Afiliacion Exitoso
    $DatosBono = array();
    $ConsultaSQL = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            $CasosEnvioDeAfiliacion = $CantidadResultados;
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ValorBono=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono, $ValorBono);
                
            }

        } else {
            $CasosEnvioDeAfiliacion = 0;
        }

        $ValorNetoBono = 0;
        for ($i=0; $i < count($DatosBono); $i++) {
            $ValorUnitarioBono = $DatosBono[$i];
            $ValorNetoBono = $ValorUnitarioBono + $ValorNetoBono;
        }
        
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Bono Por Consignación
    $DatosBono1 = array();
    $ConsultaSQL1 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE PENAFL_CBONO_AFILIACION= 'Bono Por Consignación' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL1 = $ConexionSQL->query($ConsultaSQL1)) {
        $CantidadResultados1 = $ResultadoSQL1->num_rows;
        if ($CantidadResultados1 > 0) {
            $BonoPorConsignación = $CantidadResultados1;
            while ($FilaResultado = $ResultadoSQL1->fetch_assoc()) {
                $ValorBono1=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono1, $ValorBono1);
                
            } 

        } else {
            $BonoPorConsignación = 0;
        }

        $ValorNetoBono1 = 0;
        for ($i=0; $i < count($DatosBono1); $i++) {
            $ValorUnitarioBono1 = $DatosBono1[$i];
            $ValorNetoBono1 = $ValorUnitarioBono1 + $ValorNetoBono1;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Bono En Efectivo
    $DatosBono2 = array();
    $ConsultaSQL2 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE PENAFL_CBONO_AFILIACION= 'Bono En Efectivo' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
        $CantidadResultados2 = $ResultadoSQL2->num_rows;
        if ($CantidadResultados2 > 0) {
            $BonoEnEfectivo = $CantidadResultados2;
            while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                $ValorBono2=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono2, $ValorBono2);
                
            }

        } else {
            $BonoEnEfectivo = 0;
        }

        $ValorNetoBono2 = 0;
        for ($i=0; $i < count($DatosBono2); $i++) {
            $ValorUnitarioBono2 = $DatosBono2[$i];
            $ValorNetoBono2 = $ValorUnitarioBono2 + $ValorNetoBono2;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Bono De Tienda
    $DatosBono3 = array();
    $ConsultaSQL3 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE PENAFL_CBONO_AFILIACION= 'Bono De Tienda' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
        $CantidadResultados3 = $ResultadoSQL3->num_rows;
        if ($CantidadResultados3 > 0) {
            $BonoDeTienda = $CantidadResultados3;
            while ($FilaResultado = $ResultadoSQL3->fetch_assoc()) {
                $ValorBono3=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono3, $ValorBono3);
                
            }

        } else {
            $BonoDeTienda = 0;
        }

        $ValorNetoBono3 = 0;
        for ($i=0; $i < count($DatosBono3); $i++) {
            $ValorUnitarioBono3 = $DatosBono3[$i];
            $ValorNetoBono3 = $ValorUnitarioBono3 + $ValorNetoBono3;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }

    //Consulta Sin Bono
    $DatosBono4 = array();
    $ConsultaSQL4 = "SELECT FKPENAFL_NPKPER_NCODIGO, PENAFL_CCOMISION_CALCULADA FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_AFILIACION WHERE PENAFL_CBONO_AFILIACION= 'Sin Bono' AND (PENAFL_CESTADO_FINAL2_AGENDA= 'Envio De Afiliacion Exitoso' OR PENAFL_CESTADO_FINAL2_AGENDA= 'Afiliacion Exitosa') AND PENALF_CESTADO= 'Activo' GROUP BY FKPENAFL_NPKPENCAL_NCODIGO;";
    if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
        $CantidadResultados4 = $ResultadoSQL4->num_rows;
        if ($CantidadResultados4 > 0) {
            $SinBono = $CantidadResultados4;
            while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                $ValorBono4=  $FilaResultado['PENAFL_CCOMISION_CALCULADA'];

                array_push($DatosBono4, $ValorBono4);
                
            }

        } else {
            $SinBono = 0;
        }

        $ValorNetoBono4 = 0;
        for ($i=0; $i < count($DatosBono4); $i++) {
            $ValorUnitarioBono4 = $DatosBono4[$i];
            $ValorNetoBono4 = $ValorUnitarioBono4 + $ValorNetoBono4;
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
        exit;
    }
    

    
}

//Añadir puntos a los valores numericos
$ValorNetoBonoP = number_format($ValorNetoBono, 0, ',', '.');
$ValorNetoBono1P = number_format($ValorNetoBono1, 0, ',', '.');
$ValorNetoBono2P = number_format($ValorNetoBono2, 0, ',', '.');
$ValorNetoBono3P = number_format($ValorNetoBono3, 0, ',', '.');
$ValorNetoBono4P = number_format($ValorNetoBono4, 0, ',', '.');



mysqli_close($ConexionSQL);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Enviados a Afiliacion</title>
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="../images/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/color_skins.css">
    <link rel="stylesheet" href="../css/EstilosPersonalizados2.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
</head>
<body>

    <div id="wrapper">
        <div id="nav" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="row">
                <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
                    <nav>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 d-none d-sm-none d-md-none d-lg-block">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="#"><img width="50%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 d-block d-sm-block d-md-block d-lg-none" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 1%;">
                                    <a href="#"><img width="60%" src="../images/FUTURUS.png" alt="Lucid Logo" class="img-responsive logo"></a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" style="text-align: center;">
                                <div class="navbar-brand" style="margin-top: 2%;">
                                    <h6 style="color: black;"><?php echo $nombrecompleto; ?></h6>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <div class="row">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block">
                                    </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block">
                                    </div>
                                        <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">     
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-bulb" title="Notificaciones: <?php echo $CantidadResultados ?>" aria-expanded="false"></i><span style="background-color: #93C01F;" class="notification-dot"></span></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php  require("Notificaciones.php") ?>
                                                    </ul> 
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="glyphicon glyphicon-stats" title="Reportes"></i></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php  require("Reportes.php") ?>
                                                    </ul> 
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 col-xl-2" style="text-align: center;">
                                        <div id="navbar-menu" style="padding-right: 25%;">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></a>
                                                    <ul style="margin-top: -100% !important; background-color: #61636294;" class="dropdown-menu user-menu menu-icon pre-scrollable">
                                                        <?php require("navdgacion.php") ?>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 d-none d-sm-none d-md-none d-lg-block"></div>
            </div>
        </div>
        <div id="subTitle" class="row">
            <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 d-none d-sm-none d-md-none d-lg-block"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 buttom-bar-line">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="text-align: center !important;">
                        <div class="navbar-brand d-none d-sm-block d-md-block" style="margin-bottom: 3%; margin-top: -2%;">
                            <h6>REPORTE DE CASOS ENVIADOS A AFILIACION</h6>
                        </div>
                        <div class="navbar-brand d-block d-sm-none d-md-none" style="margin-bottom: 3%; margin-top: -2%;">
                            <h6>REPORTE DE CASOS ENVIADOS A AFILIACION</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" ></div>
        </div>
    </div>

    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%; margin-left: 29%;">
        <label id="LblFiltro" style="font-size: 10px; margin-top: 10%; margin-left: -1%;"><h5>Agente: </h5>
    </div>
    <div class="row">
        
        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0.5%; margin-left: 29%; margin-top: -1%;">
            <select id="SelecionarPorAgente" name="SelecionarPorAgente" class="form-control" required="">
                <option selected="" value="Todos">TODOS </option>
                <option selected="true" hidden="" > Seleccionar...  </option>
                <?php echo $ListadoPorAgente; ?>
            </select>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4" style="padding: 0.5%; margin-left: 2%; margin-top: -1%;">
            <button id="ConsultarPor" name="ConsultarPor" type="button" class="btn btn-futurus-r">Consultar</button>
            <button id="BtnQuitarFiltro" name="BtnQuitarFiltro" type="button" class="btn btn-primary">Consultar Todo</button>
        </div>
    </div>

    <div class="row" style="margin-left: 28%;">
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group has-feedback">
                <label id="lblFechaInicial">Fecha Inicial</label>
                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                <input type="date" id="FechaInicial" class="form-control transparencia" required="">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group has-feedback">
                <label id="lblFechaFinal">Fecha Final</label>
                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                <input type="date" id="FechaFinal" class="form-control transparencia" required="" disabled>
            </div>
        </div>
    </div>
    
    <div class = "contenedor"> 
        <div class = "fila"> 
            <div class = "col-10 col-sm-7 col-md-10 col-lg-10 col-xl-10"> 
                <div style="margin-left: 20%;" class= "tarjeta"> 
                    <div class= "card-body"> 
                        <canvas id="myChart" width="228" height="228"></canvas>
                    </div> 
                </div> 
            </div> 
        </div> 
    </div>

    <div>
        <form method="POST" action="GraficaEnviosAfiliacion.php" enctype="multipart/form-data">
            <input id="FechaInicial2" name="FechaInicial2" hidden="true">
            <input id="FechaFinal2" name="FechaFinal2" hidden="true">
            <input id="SelecionarPorAgente2" name="SelecionarPorAgente2" hidden="true">
            <button id="Consultar" type="submit" class="btn" hidden="true">Guardar</button>
        </form>
    </div>

    <script>

        ValorNetoBono = <?php echo $ValorNetoBono; ?>

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['(Valor Bonos: $<?php echo $ValorNetoBono; ?>)' + ' - Total Enviados', '(Valor Bonos: $<?php echo $ValorNetoBono1; ?>)' + ' - Bono Por Consignación', '(Valor Bonos: $<?php echo $ValorNetoBono2; ?>)' + ' - Bono En Efectivo', '(Valor Bonos: $<?php echo $ValorNetoBono3; ?>)' + ' - Bono De Tienda', '(Valor Bonos: $<?php echo $ValorNetoBono4; ?>)' + ' - Sin Bono'],
                datasets: [{
                    label: 'Total Casos Enviados a Afiliacion',
                    data: [<?php echo $CasosEnvioDeAfiliacion; ?> , <?php echo $BonoPorConsignación; ?>, <?php echo $BonoEnEfectivo; ?>, <?php echo $BonoDeTienda; ?>, <?php echo $SinBono; ?>],
                    backgroundColor: [
                        'rgba(0, 0, 0, 0.8)',
                        'rgba(255, 215, 0, 0.8)',
                        'rgba(28, 192, 228, 0.8)',
                        'rgba(255, 0, 0, 0.8)',
                        'rgba(245, 245, 220, 0.8)',
                        'rgba(255, 140, 0, 0.8)',
                        'rgba(34, 139, 34, 0.8)',
                        'rgba(139, 69, 19, 0.8)',
                        'rgba(138, 43, 228, 0.8)',
                        'rgba(119, 135, 153, 0.8)',
                        'rgba(173, 255, 47, 0.8)',
                    ],
                    borderColor: [
                        'rgba(0, 0, 0)',
                        'rgba(255, 215, 0)',
                        'rgba(28, 192, 228)',
                        'rgba(255, 0, 0)',
                        'rgba(245, 245, 220)',
                        'rgba(255, 140, 0)',
                        'rgba(34, 139, 34)',
                        'rgba(139, 69, 19)',
                        'rgba(138, 43, 228)',
                        'rgba(119, 135, 153)',
                        'rgba(173, 255, 47)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        setTimeout(() => {

            $('body').on('change', '#FechaInicial', function() {
            document.getElementById("FechaFinal").disabled = false;
            })

            $('body').on('click', '#ConsultarPor', function() {
                
                let form_data = new FormData();

                var SelecionarPorAgente = $("#SelecionarPorAgente").val();
                form_data.append('SelecionarPorAgente', SelecionarPorAgente);
                var FechaInicial = $("#FechaInicial").val();
                form_data.append('FechaInicial', FechaInicial);
                var FechaFinal = $("#FechaFinal").val();
                form_data.append('FechaFinal', FechaFinal);

                if((SelecionarPorAgente == null) || (SelecionarPorAgente == "Seleccionar...")){
                    alert("¡Debe Seleccionar Un Agente Para Consultar!  🤨");

                } else if(SelecionarPorAgente == "Todos"){

                    window.location='GraficaEnviosAfiliacion.php';
                    /*document.getElementById('FechaInicial2').value = FechaInicial;
                    document.getElementById('FechaFinal2').value = FechaFinal;

                    $("#Consultar").click();*/

                } else if((FechaFinal == null) || (FechaFinal == "")){             
                    FechaJava= new Date();
                    y = FechaJava.getFullYear();
                    m = FechaJava.getMonth() + 1;
                    d = FechaJava.getDate();
                    FechaHoy= y+'-'+m+'-'+d;
                    FechaInicial= '2012-01-30' +' '+ '00:00:00';
                    FechaFinal= FechaHoy + ' ' +'23:59:59';

                    document.getElementById('FechaInicial2').value = FechaInicial;
                    document.getElementById('FechaFinal2').value = FechaFinal;
                    document.getElementById('SelecionarPorAgente2').value = SelecionarPorAgente;
                        
                    $("#Consultar").click();
                    
                } else{
                    document.getElementById('FechaInicial2').value = FechaInicial;
                    document.getElementById('FechaFinal2').value = FechaFinal;
                    document.getElementById('SelecionarPorAgente2').value = SelecionarPorAgente;
                        
                    $("#Consultar").click();

                }

            })

        }, 1000);

        


    </script>

    <div class="row" style="margin-left: 28%;">
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
            <div class="form-group has-feedback">
                <label id="lblNumeroDeBonos">Valor Total De Bonos: $<?php echo $ValorNetoBonoP; ?></label>
                <label id="lblNumeroDeBonos2">Bonos Por Consignacion: $<?php echo $ValorNetoBono1P; ?></label>
                <label id="lblNumeroDeBonos3">Bonos En Efectivo: $<?php echo $ValorNetoBono2P; ?></label>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group has-feedback">      
                <label id="lblNumeroDeBonos4">Bonos De Tienda: $<?php echo $ValorNetoBono3P; ?></label>
                <label id="lblNumeroDeBonos4">Sin Bono: $<?php echo $ValorNetoBono4P; ?></label>
            </div>
        </div>
    </div>
    <script src="../assets/bundles/libscripts.bundle.js"></script>
    <script src="../assets/bundles/vendorscripts.bundle.js"></script>
    <script src="../assets/bundles/datatablescripts.bundle.js"></script>
    <script src="../assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="../assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="../assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="../assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="../assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
    <script src="../assets/vendor/sweetalert/sweetalert.min.js"></script>
    <script src="../assets/bundles/mainscripts.bundle.js"></script>
    <script src="../assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="../js/datagrid/datatables/datatables.export.js"></script>

    <script>
        //Consultar Todo
        $("#BtnQuitarFiltro").on("click", function() {
            window.location='GraficaEnviosAfiliacion.php';
        });
    </script>
        
</body>
</html>
