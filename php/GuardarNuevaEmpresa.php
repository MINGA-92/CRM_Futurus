<?php

require('common.php');
require('funciones_generales.php');
session_start();


$TipoDocumento = $_POST['TipoDocumento'];
$NumeroDocumento = $_POST['NumeroDocumento'];
$NOMBRE_EMPRESA = $_POST['NewNombreEmpresa'];
$Agente = $_POST['Agente'];
$RegistradoPor = "Registro generado por " .  $Agente;
$Estado = "Activo";
$NewNombreEmpresa = $_POST['NewNombreEmpresa'];
$NombreEncargadoEmpresa = $_POST['NombreEncargadoEmpresa'];
$TelefonoEmpresaNueva = $_POST['TelefonoEmpresaNueva'];
$CorreoEncargadoEmpresa = $_POST['CorreoEncargadoEmpresa'];
$DireccionNuevaEmpresa = $_POST['DireccionNuevaEmpresa'];
$PaisNuevaEmpresa = $_POST['PaisNuevaEmpresa'];
$DepartamentoNuevaEmpresa = $_POST['DepartamentoNuevaEmpresa'];
$CiudadNuevaEmpresa = $_POST['CiudadNuevaEmpresa'];
$BarrioNuevaEmpresa = $_POST['BarrioNuevaEmpresa'];
$IndicativoNuevaEmpresa = $_POST['IndicativoNuevaEmpresa'];
$TelefonoNuevaEmpresa = $_POST['TelefonoNuevaEmpresa'];
$ExtencionNuevaEmpresa = $_POST['ExtencionNuevaEmpresa'];

function ValidarEspacios2($NOMBRE_EMPRESA){

    $RESULTADO0 = str_replace("       ", " ", $NOMBRE_EMPRESA);
    $RESULTADO1 = str_replace("      ", " ", $RESULTADO0);
    $RESULTADO2 = str_replace("     ", " ", $RESULTADO1);
    $RESULTADO3 = str_replace("    ", " ", $RESULTADO2);
    $RESULTADO4 = str_replace("   ", " ", $RESULTADO3);
    $RESULTADO5 = str_replace("  ", " ", $RESULTADO4);
    $RESULTADO = $RESULTADO5;
    return $RESULTADO;
}

$ConsultaSQL = "SELECT EMP_CDOCUMENTO FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE EMP_CDOCUMENTO = '" . $NumeroDocumento . "' AND EMP_CESTADO = '" . $Estado . "';";
if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        echo "2";
    } else {
        //Sin Resultados crear empresa
        $VALIDADOR = str_replace(" ", "|", $NOMBRE_EMPRESA);
        $VALIDADOR = $VALIDADOR . '|';
        if ($NOMBRE_EMPRESA == $VALIDADOR) {
            $NOMBRE_EMPRESA1 = $NOMBRE_EMPRESA;
            $NOMBRE_EMPRESA2 = "";
            $NOMBRE_EMPRESA3 = "";
            $NOMBRE_EMPRESA4 = "";
        } else {

            $CONTADOR = substr_count($VALIDADOR, "|");
            switch ($CONTADOR) {
                case 1:
                    list($NOMBRE_EMPRESA1, $NOMBRE_EMPRESA2) = explode('|', $VALIDADOR);
                    $NOMBRE_EMPRESA1;
                    $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                    $NOMBRE_EMPRESA2;
                    $NOMBRE_EMPRESA3 = "";
                    $NOMBRE_EMPRESA4 = "";
                    break;
                case 2:
                    list($NOMBRE_EMPRESA1, $NOMBRE_EMPRESA2, $NOMBRE_EMPRESA3) = explode('|', $VALIDADOR);
                    $NOMBRE_EMPRESA1;
                    $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                    $NOMBRE_EMPRESA2;
                    $NOMBRE_EMPRESA3;
                    $NOMBRE_EMPRESA4 = "";
                    break;
                case 3:
                    list($NOMBRE_EMPRESA1, $NOMBRE_EMPRESA2, $NOMBRE_EMPRESA3, $NOMBRE_EMPRESA4) = explode('|', $VALIDADOR);
                    $NOMBRE_EMPRESA1;
                    $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                    $NOMBRE_EMPRESA2;
                    $NOMBRE_EMPRESA3;
                    $NOMBRE_EMPRESA4;
                    break;
                default:
                    try {
                        $RESULTADO = ValidarEspacios2($NOMBRE_EMPRESA);
                        $ArrayNombre = explode(' ', $RESULTADO);

                        if ($CONTADOR == 4) {
                            $NOMBRE_EMPRESA1 = $ArrayNombre[0] ." ". $ArrayNombre[1];
                            $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                            $NOMBRE_EMPRESA2 = $ArrayNombre[2];
                            $NOMBRE_EMPRESA3 = $ArrayNombre[3];
                            $NOMBRE_EMPRESA4 = $ArrayNombre[4];

                        } else if ($CONTADOR == 5) {
                            $NOMBRE_EMPRESA1 = $ArrayNombre[0] ." ". $ArrayNombre[1];
                            $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                            $NOMBRE_EMPRESA2 = $ArrayNombre[2] ." ". $ArrayNombre[3];
                            $NOMBRE_EMPRESA3 = $ArrayNombre[4];
                            $NOMBRE_EMPRESA4 = $ArrayNombre[5];

                        } else if ($CONTADOR == 6) {
                            $NOMBRE_EMPRESA1 = $ArrayNombre[0] ." ". $ArrayNombre[1];
                            $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                            $NOMBRE_EMPRESA2 = $ArrayNombre[2] ." ". $ArrayNombre[3];
                            $NOMBRE_EMPRESA3 = $ArrayNombre[4] ." ". $ArrayNombre[5];
                            $NOMBRE_EMPRESA4 = $ArrayNombre[6];

                        } else if ($CONTADOR == 7) {
                            $NOMBRE_EMPRESA1 = $ArrayNombre[0] ." ". $ArrayNombre[1];
                            $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                            $NOMBRE_EMPRESA2 = $ArrayNombre[2] ." ". $ArrayNombre[3];
                            $NOMBRE_EMPRESA3 = $ArrayNombre[4] ." ". $ArrayNombre[5];
                            $NOMBRE_EMPRESA4 = $ArrayNombre[6] ." ". $ArrayNombre[7];

                        } else {
                            $NOMBRE_EMPRESA1 = $ArrayNombre[0];
                            $NOMBRE_EMPRESA1 = trim($NOMBRE_EMPRESA1);
                            $NOMBRE_EMPRESA2 = $ArrayNombre[1];
                            $NOMBRE_EMPRESA3 = $ArrayNombre[2];
                            $NOMBRE_EMPRESA4 = $ArrayNombre[3];
                        }

                    }catch (Exception $e) {
                        $NOMBRE_EMPRESA1 = "";
                        $NOMBRE_EMPRESA2 = "";
                        $NOMBRE_EMPRESA3 = "";
                        $NOMBRE_EMPRESA4 = "";
                    }
                    break;
            }
        }

        $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_REMPRESA (EMP_CDOCUMENTO, EMP_CTIPO_DOCUMENTO, EMP_CNOMBRE, EMP_CNOMBRE2, EMP_CAPELLIDO, EMP_CAPELLIDO2, EMP_CDETALLE_REGISTRO, EMP_CESTADO) VALUES ('" . $NumeroDocumento . "', '" . $TipoDocumento . "', '" . $NOMBRE_EMPRESA1 . "', '" . $NOMBRE_EMPRESA2 . "', '" . $NOMBRE_EMPRESA3 . "', '" . $NOMBRE_EMPRESA4 . "', '" . $RegistradoPor . "', '" . $Estado . "')";
        if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
            //Se Crea empresa, Se consulta el numero de registro
            $ConsultaSQL = "SELECT PKEMP_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE EMP_CDOCUMENTO = '" . $NumeroDocumento . "' AND EMP_CESTADO = '" . $Estado . "';";
            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if ($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $PKEMP_NCODIGO = $FilaResultado['PKEMP_NCODIGO'];
                    }

                    //Insercion de los detalles de la empresa
                    $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CDETALLE1, DETEMP_CDETALLE2, DETEMP_CDETALLE3, DETEMP_CDETALLE4, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('" .  $PKEMP_NCODIGO. "', 'DireccionEmpresa', '" . $DireccionNuevaEmpresa . "', '" . $PaisNuevaEmpresa . "', '" . $DepartamentoNuevaEmpresa . "', '" . $CiudadNuevaEmpresa . "', '" . $BarrioNuevaEmpresa . "', '" . $RegistradoPor . "', '" . $Estado . "')" ;
                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
                    } else {
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "0";
                        echo $ErrorConsulta;
                    }

                    //Incerción telefono empresa
                    $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CDETALLE1, DETEMP_CDETALLE2, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('" .  $PKEMP_NCODIGO. "', 'TelefonoEmpresa', '" . $TelefonoNuevaEmpresa . "', '" . $IndicativoNuevaEmpresa . "', '" . $ExtencionNuevaEmpresa . "', '" . $RegistradoPor . "', '" . $Estado . "')" ;
                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
                    } else {
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "0";
                        echo $ErrorConsulta;
                    }

                    $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CDETALLE1, DETEMP_CDETALLE2, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('" . $PKEMP_NCODIGO . "', 'EncargadoRRHH', '" . $NombreEncargadoEmpresa  . "', '" . $CorreoEncargadoEmpresa . "', '" . $TelefonoEmpresaNueva . "', '" . $RegistradoPor . "', '" . $Estado . "')";
                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
                        echo "1";
                    } else {
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo "0";
                        echo $ErrorConsulta;
                    }
                    
                } else {
                    //Sin Resultados

                }
            } else {
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
    }
} else {
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    echo "0";
    echo $ErrorConsulta;
}
