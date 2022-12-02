
<?php

require('common.php');
require('funciones_generales.php');
session_start();

if (isset($_SESSION['codigopermiso'])) {
} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}

//Se obtiene el valor de la fecha actual y la hora actual
date_default_timezone_set("America/Bogota");
$Fecha =  date("Y-m-d H:i:s");


require_once '../PHPExcel/Classes/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load('../xls/empleados.xls');
$nomRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

$Estado = "Activo";
$Estado2 = "InActivo";
$RegistradoPor = "Registro generado por la Plataforma";
$ActualizadoPor = "Actualizacion generada por la Plataforma";
$TipoDocumentoEmpresa = "NIT";
$EncargadoRRHH = "EncargadoRRHH";
$TelefonoEmpresa = "TelefonoEmpresa";
$DireccionDomicilio = "DireccionDomicilio";
$FondosAnteriores = "FondosAnteriores";
$EmpresaCliente = "EmpresaCliente";
$DatosCliente1  = array();

?>
<!Doctype html>
<html lang="es">

    <head>
        <title> Guardar Cargue :: Futurus </title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
        <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
        <link rel="icon" href="../images/logo2.png" type="image/x-icon">
        <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/color_skins.css">
        <link rel="stylesheet" href="../css/EstilosPersonalizadosPlantilla.css">
    </head>

    <body class="theme-cyan">
        <div id="wrapper">
            <div id="Loading" style="margin-left: 35%">
                <img src="../images/loading.gif">
            </div>
        </div>
    </body>
</html>

<?php
//Función para eliminar las tildes según lleguen en el excel.
function eliminarTildes($string){
    //Reemplazar A y a
    $string = str_replace(
        ['Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'],
        ['A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'],
        $string
    );

    //Reemplazar A y a
    $string = str_replace(
        ['É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'],
        ['E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'],
        $string
    );

    //Reemplazamos la I y i
    $string = str_replace(
        ['Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'],
        ['I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'],
        $string
    );

    //Reemplazamos la O y o
    $string = str_replace(
        ['Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'],
        ['O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'],
        $string
    );

    //Reemplazamos la U y u
    $string = str_replace(
        ['Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'],
        ['U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'],
        $string
    );

    //Reemplazamos la N, n, C y c
    $string = str_replace(
        ['Ñ', 'ñ', 'Ç', 'ç'],
        ['N', 'n', 'C', 'c'],
        $string
    );

    return $string;
}

function ValidarEspacios($NOMBRE_STRING){

    $RESULTADO0 = str_replace("       ", " ", $NOMBRE_STRING);
    $RESULTADO1 = str_replace("      ", " ", $RESULTADO0);
    $RESULTADO2 = str_replace("     ", " ", $RESULTADO1);
    $RESULTADO3 = str_replace("    ", " ", $RESULTADO2);
    $RESULTADO4 = str_replace("   ", " ", $RESULTADO3);
    $RESULTADO5 = str_replace("  ", " ", $RESULTADO4);
    $RESULTADO = $RESULTADO5;
    return $RESULTADO;
}

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

//Se realiza for para que me recorra todas las columnas del excel
for ($i = 2; $i <= $nomRows; $i++) {
    //Se obtienen todos los valores de cada una de las columnas del excel
    $NIT_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue());
    $NIT_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue()));
    $NOMBRE_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
    $NOMBRE_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue()));
    $DIRECCION_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
    $DIRECCION_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue()));
    $CELULAR_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
    $CELULAR_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue()));
    $TIPO_DOCUMENTO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue());
    $TIPO_DOCUMENTO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue()));
    $DOCUMENTO_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue());
    $DOCUMENTO_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue()));
    $NOMBRE_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue());
    $NOMBRE_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue()));
    $CARGO_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue());
    $CARGO_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue()));
    $PAIS_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue());
    $PAIS_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue()));
    $CIUDAD_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue());
    $CIUDAD_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue()));
    $SALARIO_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue());
    $SALARIO_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue()));
    $IBC_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue());
    $IBC_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue()));
    $FONDO_ACTUAL = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue());
    $FONDO_ACTUAL = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue()));
    $DESCRIPCION_SIAFP = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue());
    $DESCRIPCION_SIAFP = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue()));
    $FECHA_EXPEDICION = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue());
    $FECHA_EXPEDICION = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue()));
    $FECHA_EXPEDICION = (PHPExcel_Style_NumberFormat::toFormattedString($FECHA_EXPEDICION, 'DD/MM/YYYY'));
    $FECHA_NACIMIENTO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue());
    $FECHA_NACIMIENTO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue()));
    $FECHA_NACIMIENTO = (PHPExcel_Style_NumberFormat::toFormattedString($FECHA_NACIMIENTO, 'DD/MM/YYYY'));
    $FONDOS_ANTERIORES = ($objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue());
    $FECHA_CAMBIO = ($objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue());
    $CELULAR_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue());
    $CELULAR_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue()));
    $CELULAR_CLIENTE2 = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue());
    $CELULAR_CLIENTE2 = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue()));
    $CELULAR_CLIENTE3 = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue());
    $CELULAR_CLIENTE3 = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue()));
    $CELULAR_CLIENTE4 = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue());
    $CELULAR_CLIENTE4 = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue()));
    $CORREO_CLIENTE = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue());
    $CORREO_CLIENTE = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue()));
    $ESTADO_CIVIL = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue());
    $ESTADO_CIVIL = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue()));
    $FECHA_INGRESO_LABORAL = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue());
    $FECHA_INGRESO_LABORAL = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue()));
    $FECHA_INGRESO_LABORAL = (PHPExcel_Style_NumberFormat::toFormattedString($FECHA_INGRESO_LABORAL, 'DD/MM/YYYY'));
    $DIRECCION_OFICINA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AA' . $i)->getCalculatedValue());
    $DIRECCION_OFICINA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AA' . $i)->getCalculatedValue()));
    $DIRECCION_DOMICILIO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AB' . $i)->getCalculatedValue());
    $DIRECCION_DOMICILIO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AB' . $i)->getCalculatedValue()));
    $INTERESADO_CREDITO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AD' . $i)->getCalculatedValue());
    $INTERESADO_CREDITO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AD' . $i)->getCalculatedValue()));
    $INTERESADO_CARTERA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AE' . $i)->getCalculatedValue());
    $INTERESADO_CARTERA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AE' . $i)->getCalculatedValue()));
    $NOMBRE_RRHH = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AF' . $i)->getCalculatedValue());
    $NOMBRE_RRHH = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AF' . $i)->getCalculatedValue()));
    $CORREO_RRHH = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AG' . $i)->getCalculatedValue());
    $CORREO_RRHH = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AG' . $i)->getCalculatedValue()));
    $CONTACTO_RRHH = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AH' . $i)->getCalculatedValue());
    $CONTACTO_RRHH = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AH' . $i)->getCalculatedValue()));
    $VALOR_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AK' . $i)->getCalculatedValue());
    $VALOR_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AK' . $i)->getCalculatedValue()));
    $TELEFONO_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AL' . $i)->getCalculatedValue());
    $TELEFONO_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AL' . $i)->getCalculatedValue()));
    $EXTENSION_EMPRESA = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AM' . $i)->getCalculatedValue());
    $EXTENSION_EMPRESA = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AM' . $i)->getCalculatedValue()));
    $CESANTIAS = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AQ' . $i)->getCalculatedValue()));
    $CAJA_DE_COMPENSACION = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AR' . $i)->getCalculatedValue());
    $CAJA_DE_COMPENSACION = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AR' . $i)->getCalculatedValue()));
    $SALUD_EPS = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AS' . $i)->getCalculatedValue());
    $SALUD_EPS = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AS' . $i)->getCalculatedValue()));
    $TIEMPOCAMBIOS = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AO' . $i)->getCalculatedValue());
    $TIEMPOCAMBIOS = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AO' . $i)->getCalculatedValue()));
    $CANTIDADFONDO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AN' . $i)->getCalculatedValue());
    $CANTIDADFONDO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AN' . $i)->getCalculatedValue()));
    $GENERO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('AT' . $i)->getCalculatedValue());
    $GENERO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('AT' . $i)->getCalculatedValue()));
    $FONDO_NUEVO = quitarCaracteres($objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue());
    $FONDO_NUEVO = trim(eliminarTildes($objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue()));

    //Se realiza el reemplazo de los "/" de las fechas por "-" para cumplir con la inserción en BD
    $FECHA_EXPEDICION = str_replace('/', '-', $FECHA_EXPEDICION);
    $FECHA_NACIMIENTO = str_replace('/', '-', $FECHA_NACIMIENTO);
    $FECHA_INGRESO_LABORAL = str_replace('/', '-', $FECHA_INGRESO_LABORAL);

    //Se generan arrays para recorridos de inserción según corresponde
    $DatosEmpresa = array('DireccionEmpresa', 'CelularEmpresa', 'ValorEmpresa');
    $DatosEmpresa1 = array($DIRECCION_EMPRESA, $CELULAR_EMPRESA, $VALOR_EMPRESA);

    $DatosCliente = array('CargoLaboralCliente', 'SalarioCliente', 'IBCCliente', 'FondoPensionCliente', 'DescripcionSiafpCliente', 'FechaExpedicionDocumento', 'FechaNacimientoCliente', 'CelularCliente', 'CelularCliente', 'CelularCliente', 'CelularCliente', 'CorreoCliente', 'FechaIngresoLaboralCliente', 'PreguntaInteresadoCredito', 'PreguntaInteresadoCartera', 'CesantiasCliente', 'CajaDeCompensacion', 'SaludEps', 'DireccionOficina', 'EstadoCivilCliente', 'TiempoEntreCambios', 'CantidadDeCambios', 'GeneroCliente', 'FondoAlQueVa');
    $DatosCliente1 = array($CARGO_CLIENTE, $SALARIO_CLIENTE, $IBC_CLIENTE, $FONDO_ACTUAL, $DESCRIPCION_SIAFP, $FECHA_EXPEDICION, $FECHA_NACIMIENTO, $CELULAR_CLIENTE, $CELULAR_CLIENTE2, $CELULAR_CLIENTE3, $CELULAR_CLIENTE4, $CORREO_CLIENTE, $FECHA_INGRESO_LABORAL, $INTERESADO_CREDITO, $INTERESADO_CARTERA, $CESANTIAS, $CAJA_DE_COMPENSACION, $SALUD_EPS, $DIRECCION_OFICINA, $ESTADO_CIVIL, $TIEMPOCAMBIOS, $CANTIDADFONDO, $GENERO, $FONDO_NUEVO);

    //se reemplaza los espacios por barras para proseguir a contarlos y así generar la separación de los nombres de la empresa
    $NOMBRES_EMPRESAS = trim($NOMBRE_EMPRESA);
    $NOMBRE_EMPRESA = trim($NOMBRES_EMPRESAS);
    $VALIDADOR = str_replace(" ", "|", $NOMBRE_EMPRESA);

    if ($NOMBRE_EMPRESA == $VALIDADOR) {
        $NOMBRE_EMPRESA1 = $NOMBRE_EMPRESA;
        $NOMBRE_EMPRESA2 = "";
        $NOMBRE_EMPRESA3 = "";
        $NOMBRE_EMPRESA4 = "";
        $NOMBRE_EMPRESA5 = "";
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


    $NOMBRE_CLIENTE = ValidarEspacios($NOMBRE_CLIENTE);
    $VALIDADOR = str_replace(" ", "|", $NOMBRE_CLIENTE);
    if ($NOMBRE_CLIENTE == $VALIDADOR) {
        $NOMBRE_CLIENTE1 = $NOMBRE_CLIENTE;
        $NOMBRE_CLIENTE2 = "";
        $APELLIDO_CLIENTE = "";
        $APELLIDO_CLIENTE2 = "";

    } else {
        
        $CONTADOR = substr_count($VALIDADOR, "|");
        switch ($CONTADOR) {
            case 1:
                list($NOMBRE_CLIENTE1, $NOMBRE_CLIENTE2) = explode('|', $VALIDADOR);
                $NOMBRE_CLIENTE1;
                $NOMBRE_CLIENTE2;
                $APELLIDO_CLIENTE = "";
                $APELLIDO_CLIENTE2 = "";
                break;
            case 2:
                list($NOMBRE_CLIENTE1, $APELLIDO_CLIENTE, $APELLIDO_CLIENTE2) = explode('|', $VALIDADOR);
                $NOMBRE_CLIENTE1;
                $NOMBRE_CLIENTE2 = "";
                $APELLIDO_CLIENTE;
                $APELLIDO_CLIENTE2;
                break;
            case 3:
                list($NOMBRE_CLIENTE1, $NOMBRE_CLIENTE2, $APELLIDO_CLIENTE, $APELLIDO_CLIENTE2) = explode('|', $VALIDADOR);
                $NOMBRE_CLIENTE1;
                $NOMBRE_CLIENTE2;
                $APELLIDO_CLIENTE;
                $APELLIDO_CLIENTE2;
                break;

            default:
                try {
                    $RESULTADO = ValidarEspacios($NOMBRE_CLIENTE);
                    $ArrayNombre = explode(' ', $RESULTADO);
                    $NOMBRE_CLIENTE1 = $ArrayNombre[0];
                    $NOMBRE_CLIENTE2 = $ArrayNombre[1];
                    $APELLIDO_CLIENTE = $ArrayNombre[2];
                    $APELLIDO_CLIENTE2 = $ArrayNombre[3];

                }catch (Exception $e) {
                    $NOMBRE_CLIENTE1 = "";
                    $NOMBRE_CLIENTE2 = "";
                    $APELLIDO_CLIENTE = "";
                    $APELLIDO_CLIENTE2 = "";
                }

        }
    }

    //Se valida si existe empresa si existe se baja la llave primaria y se realiza inserción de los clientes
    $ConsultaSQL = "SELECT PKEMP_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE EMP_CDOCUMENTO = '" . $NIT_EMPRESA . "' AND EMP_CESTADO = '" . $Estado . "' LIMIT 1;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $PKEMP_NCODIGO = $FilaResultado['PKEMP_NCODIGO'];
                $EmpresaCliente = $PKEMP_NCODIGO;
                break;
            }

            //Si existe la empresa se actualiza 
            //Actualiza la tabla empresa conn los datos basicos de la empresa
            $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_REMPRESA SET EMP_CDOCUMENTO = '" . $NIT_EMPRESA . "' ,EMP_CTIPO_DOCUMENTO = '" . $TipoDocumentoEmpresa . "' ,EMP_CNOMBRE = '" . $NOMBRE_EMPRESA1 . "' ,EMP_CNOMBRE2 = '" . $NOMBRE_EMPRESA2 . "' , EMP_CAPELLIDO = '" . $NOMBRE_EMPRESA3 . "'  , EMP_CAPELLIDO2 = '" . $NOMBRE_EMPRESA4 . "'  , EMP_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKEMP_NCODIGO = '" . $PKEMP_NCODIGO . "' AND EMP_CESTADO = '" .  $Estado . "'; ";
            if ($ResultadoSql = $ConexionSQL->query($ActualizarSQL)) {
                
                //Se actualiza detalles de la empresa encargado RRHH
                $ActualizarSQL2 = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA SET DETEMP_CCONSULTA = '" . $EncargadoRRHH . "',DETEMP_CDETALLE= '" . $NOMBRE_RRHH . "',DETEMP_CDETALLE1= '" . $CORREO_RRHH . "',DETEMP_CDETALLE2 = '" . $CONTACTO_RRHH . "', DETEMP_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE FKDETEMP_NCLI_NCODIGO = '" . $PKEMP_NCODIGO . "' AND DETEMP_CCONSULTA = 'EncargadoRRHH' AND DETEMP_CESTADO = '" .  $Estado . "';";
                if ($ResultadoSql = $ConexionSQL->query($ActualizarSQL2)) {
                
                    //Se actualizan detalles del telefono de la empresa
                    $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA SET DETEMP_CCONSULTA = '" . $TelefonoEmpresa . "' , DETEMP_CDETALLE = '" . $TELEFONO_EMPRESA . "', DETEMP_CDETALLE1 = '" . $EXTENSION_EMPRESA . "', DETEMP_CDETALLE_REGISTRO= '" . $ActualizadoPor . "' WHERE FKDETEMP_NCLI_NCODIGO = '" . $PKEMP_NCODIGO . "' AND DETEMP_CCONSULTA = 'TelefonoEmpresa' AND DETEMP_CESTADO = '" .  $Estado . "';";
                    if ($ResultadoSql = $ConexionSQL->query($ActualizarSQL)) {


                        for ($d = 0; $d < count($DatosEmpresa); $d++) {
                            $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA SET DETEMP_CDETALLE = '" . $DatosEmpresa1[$d] . "', DETEMP_CDETALLE_REGISTRO ='" . $ActualizadoPor . "' WHERE FKDETEMP_NCLI_NCODIGO = '" . $PKEMP_NCODIGO . "' AND DETEMP_CCONSULTA = '" . $DatosEmpresa[$d] . "' AND DETEMP_CESTADO = '" .  $Estado . "';";
                            if ($ResultadoSql = $ConexionSQL->query($ActualizarSQL)) {
                            } else {
                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                mysqli_close($ConexionSQL);
                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                exit;
                            }
                        }

                    } else {
                        $ErrorConsulta = mysqli_error($ConexionSQL);
                        mysqli_close($ConexionSQL);
                        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                        exit;
                    }
                } else {
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                    exit;
                }

                //Se valida si existe el cliente
                $ConsultaSQL = "SELECT PKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE CLI_CDOCUMENTO = '" . $DOCUMENTO_CLIENTE . "' AND CLI_CESTADO = '" . $Estado . "' LIMIT 1;";
                if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {                
                    $CantidadResultados = $ResultadoSQL->num_rows;
                    if ($CantidadResultados > 0) {
                        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                            $PKCLI_NCODIGO = $FilaResultado['PKCLI_NCODIGO'];
                            break;
                        }
                        
                        //se genera actualización de los datos basicos del cliente en rcliente
                        $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RCLIENTE SET CLI_CDOCUMENTO = '" . $DOCUMENTO_CLIENTE . "', CLI_CTIPO_DOCUMENTO = '" . $TIPO_DOCUMENTO . "', CLI_CNOMBRE = '" . $NOMBRE_CLIENTE1 . "', CLI_CNOMBRE2 = '" . $NOMBRE_CLIENTE2 . "', CLI_CAPELLIDO = '" . $APELLIDO_CLIENTE . "', CLI_CAPELLIDO2 = '" . $APELLIDO_CLIENTE2 . "', CLI_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKCLI_NCODIGO= '" .  $PKCLI_NCODIGO . "' AND CLI_CESTADO = '" . $Estado . "';";
                        if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {

                            //SE realiza actualización de la dirección del cliente
                            $InsercionSQL4 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE3,DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', '" . $DireccionDomicilio . "', '" . $DIRECCION_DOMICILIO . "', '" . $PAIS_CLIENTE . "', '" . $CIUDAD_CLIENTE . "', '" . $RegistradoPor . "', '" . $Estado . "')";
                            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL4)) {

                                for ($b = 0; $b < count($DatosCliente); $b++) {

                                    //Se realiza actualización del resto de detalles del cliente
                                    if ($DatosCliente[$b] == 'CelularCliente'){
                                        $Actualizar2 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO,DETCLI_CCONSULTA,DETCLI_CDETALLE,DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES('" .  $PKCLI_NCODIGO . "', '" . $DatosCliente[$b] . "', '" . $DatosCliente1[$b] . "', '" . $RegistradoPor . "', '" . $Estado . "') ";
                                    } else if ($DatosCliente[$b] == 'DireccionOficina'){
                                        $Actualizar2 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO,DETCLI_CCONSULTA,DETCLI_CDETALLE,DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES('" .  $PKCLI_NCODIGO . "', '" . $DatosCliente[$b] . "', '" . $DatosCliente1[$b] . "', '" . $RegistradoPor . "', '" . $Estado . "') ";
                                    } else {
                                        $Actualizar2 = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '" . $DatosCliente1[$b] . "', DETCLI_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE FKDETCLI_NCLI_NCODIGO = '" . $PKCLI_NCODIGO . "' AND DETCLI_CCONSULTA = '" . $DatosCliente[$b] . "' AND DETCLI_CESTADO = '" . $Estado . "';";
                                        if ($ResultadoSQL = $ConexionSQL->query($Actualizar2)) {
                                        } else {
                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                            mysqli_close($ConexionSQL);
                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                            exit;
                                        }
                                    }
                                }

                                $CONTADOR = explode("/", $FONDOS_ANTERIORES);
                                $CONTADOR2 = explode("/", $FECHA_CAMBIO);
                                $DatosFondosAntes = $CONTADOR;
                                $DatosFechaCambio = $CONTADOR2;
                                //Diccionario de datos para fondos anteriores
                                for ($c = 0; $c < count($DatosFondosAntes); $c++) {

                                    $fondo = "";
                                    switch ($DatosFondosAntes[$c]) {
                                        case 'SK':
                                            $fondo = "Skandia";
                                            break;
                                        case 'PV':
                                            $fondo = "Porvenir";
                                            break;
                                        case 'PT':
                                            $fondo = "Proteccion";
                                            break;
                                        case 'COL':
                                            $fondo = "Colfondos";
                                            break;
                                        case 'C':
                                            $fondo = "Colpensiones";
                                            break;
                                        default:
                                            $fondo = "";
                                            break;
                                    }

                                    if ($fondo == '') {} else {                                 
                                        //Se realiza inserción de los fondos anteriores y las fechas en las que estuvo el cliente con ese fondo
                                        $InsercionSQL6 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA,DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO)VALUES ('" . $PKCLI_NCODIGO . "', '" . $FondosAnteriores . "' , '" . $fondo . "',' " . $DatosFechaCambio[$c] . " ' , ' " . $RegistradoPor . " ', ' " .$Estado2. " ')";
                                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL6)) {
                                        } else {
                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                            mysqli_close($ConexionSQL);
                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                            exit;
                                        }
                                    }
                                }
                            } else {
                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                mysqli_close($ConexionSQL);
                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                exit;
                            }    
                        } else{
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            mysqli_close($ConexionSQL);
                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                            exit;
                        }
                    } else{

                        //Se empieza a realizar inserción del cliente en la tabla de rcliente
                        $InsercionSQL3 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RCLIENTE(CLI_CDOCUMENTO, CLI_CTIPO_DOCUMENTO, CLI_CNOMBRE, CLI_CNOMBRE2, CLI_CAPELLIDO, CLI_CAPELLIDO2, CLI_CDETALLE_REGISTRO, CLI_CESTADO) VALUES ('" . $DOCUMENTO_CLIENTE . "', '" . $TIPO_DOCUMENTO . "', '" . $NOMBRE_CLIENTE1 . "', '" . $NOMBRE_CLIENTE2 . "', '" . $APELLIDO_CLIENTE . "', '" . $APELLIDO_CLIENTE2 . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL3)) {
                            
                            //consulta de la llave primaria para poder realizar inserción en detalles del cliente
                            $ConsultaSQL2 = "SELECT PKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE CLI_CDOCUMENTO = '" . $DOCUMENTO_CLIENTE . "' AND CLI_CESTADO = '" . $Estado . "' LIMIT 1;";
                            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL2)) {
                                $CantidadResultados = $ResultadoSQL->num_rows;
                                if ($CantidadResultados > 0) {
                                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                                        $PKCLI_NCODIGO = $FilaResultado['PKCLI_NCODIGO'];
                                        break;
                                    }
                                    
                                    //Se realiza la inserción del detalle de dirección en la tabla de los detalles del cliente
                                    $InsercionSQL4 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE3,DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', '" . $DireccionDomicilio . "', '" . $DIRECCION_DOMICILIO . "', '" . $PAIS_CLIENTE . "', '" . $CIUDAD_CLIENTE . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL4)) {
                                        
                                        //Se realiza la inserción de la empresa asociada al cliente según corresponda.
                                        $InsercionSQL8 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'EmpresaCliente', '" .  $EmpresaCliente . "', '" . $RegistradoPor . "', '" . $Estado . "' );";
                                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL8)) {

                                            for ($b = 0; $b < count($DatosCliente); $b++) {
                                                
                                                //Inserción del resto de detales de rcliente con un for para que me recorra los dos arreglos
                                                $InsercionSQL5 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO,DETCLI_CCONSULTA,DETCLI_CDETALLE,DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES('" .  $PKCLI_NCODIGO . "', '" . $DatosCliente[$b] . "', '" . $DatosCliente1[$b] . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                                                if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL5)) {
                                                } else {
                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                    mysqli_close($ConexionSQL);
                                                    echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                    exit;
                                                }
                                            }

                                            $CONTADOR = explode("/", $FONDOS_ANTERIORES);
                                            $CONTADOR2 = explode("/", $FECHA_CAMBIO);
                                            $DatosFondosAntes = $CONTADOR;
                                            $DatosFechaCambio = $CONTADOR2;
                                            //Diccionario de datos de los fondos anteriores.
                                            for ($c = 0; $c < count($DatosFondosAntes); $c++) {

                                                $fondo = "";
                                                switch ($DatosFondosAntes[$c]) {
                                                    case 'SK':
                                                        $fondo = "Skandia";
                                                        break;
                                                    case 'PV':
                                                        $fondo = "Porvenir";
                                                        break;
                                                    case 'PT':
                                                        $fondo = "Proteccion";
                                                        break;
                                                    case 'COL':
                                                        $fondo = "Colfondos";
                                                        break;
                                                    case 'C':
                                                        $fondo = "Colpensiones";
                                                        break;
                                                    default:
                                                        $fondo = "";
                                                        break;
                                                }
                                                if ($fondo == '') {} else {
                                                    //Se realiza inserción de los fondos anteriores según la fecha en la que estuvo el cliente.
                                                    $InsercionSQL6 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA,DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO)VALUES ('" . $PKCLI_NCODIGO . "', '" . $FondosAnteriores . "' , '" . $fondo . "',' " . $DatosFechaCambio[$c] . " ' , ' " . $RegistradoPor . " ', ' " .$Estado2. " ');";
                                                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL6)) {
                                                    } else {
                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                        mysqli_close($ConexionSQL);
                                                        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                        exit;
                                                    }
                                                }
                                            }
                                            //Se inserta cliente en la tabla de call para que pueda ser gestionado POR el asesor.
                                            $InsercionSQL7 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL (FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES('" . $PKCLI_NCODIGO . "','" . $Fecha . "' ,'" . $ActualizadoPor . "', '" . $Estado . "');";
                                            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL7)) {
                                            } else {
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                exit;
                                            }
                                        } else {
                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                            mysqli_close($ConexionSQL);
                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                            exit;
                                        }
                                    } else {
                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                        mysqli_close($ConexionSQL);
                                        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                        exit;
                                    }
                                } else {
                                    //Sin resultados
                                    $PKCLI_NCODIGO = "";
                                    mysqli_close($ConexionSQL);
                                    echo ('<script>alert("' . 'Error consultando el cliente recien creado' . '")</script>');
                                    exit;
                                }
                            } else {
                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                mysqli_close($ConexionSQL);
                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                exit;
                            }
                        } else {
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            mysqli_close($ConexionSQL);
                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                            exit;
                        }
                    }
                } else{
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                    exit;
                }

            } else {
                $ErrorConsulta = mysqli_error($ConexionSQL);
                mysqli_close($ConexionSQL);
                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                exit;
            }
        } else {
            //Se Realiza la insercion de los datos basicos de la empresa
            $InsercionSql = "INSERT INTO u632406828_dbp_crmfuturus.TBL_REMPRESA(EMP_CDOCUMENTO,EMP_CTIPO_DOCUMENTO,EMP_CNOMBRE,EMP_CNOMBRE2, EMP_CAPELLIDO, EMP_CAPELLIDO2, EMP_CDETALLE_REGISTRO, EMP_CESTADO) VALUES('" . $NIT_EMPRESA . "','" . $TipoDocumentoEmpresa . "', '" . $NOMBRE_EMPRESA1 . "', '" . $NOMBRE_EMPRESA2 . "', '" . $NOMBRE_EMPRESA3 . "', '" . $NOMBRE_EMPRESA4 . "' ,'" . $RegistradoPor . "', '" .  $Estado . "');";
            if ($ResultadoSQL = $ConexionSQL->query($InsercionSql)) {
                
                //Se realiza consulta de la inserción anterior para bajar la llave primaria
                $ConsultaSQL = "SELECT PKEMP_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE EMP_CDOCUMENTO = '" . $NIT_EMPRESA . "' AND EMP_CESTADO = '" . $Estado . "' LIMIT 1;";
                if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                    $CantidadResultados = $ResultadoSQL->num_rows;
                    if ($CantidadResultados > 0) {
                        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                            $PKEMP_NCODIGO = $FilaResultado['PKEMP_NCODIGO'];
                            $EmpresaCliente = $PKEMP_NCODIGO;
                            break;
                        }
                        //Se inserta detalles de la empresa encargado RRHH
                        $InsercionSQL1 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA (FKDETEMP_NCLI_NCODIGO,DETEMP_CCONSULTA,DETEMP_CDETALLE,DETEMP_CDETALLE1,DETEMP_CDETALLE2, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('" . $PKEMP_NCODIGO . "', '" . $EncargadoRRHH . "', '" .  $NOMBRE_RRHH . "', '" . $CORREO_RRHH . "', '" . $CONTACTO_RRHH . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL1)) {
                            
                            //se insertan detalles de la empresa telefono
                            $InsercionSQL2 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA(FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE, DETEMP_CDETALLE1, DETEMP_CDETALLE_REGISTRO, DETEMP_CESTADO) VALUES ('" . $PKEMP_NCODIGO . "', '" . $TelefonoEmpresa . "' , '" . $TELEFONO_EMPRESA . "', '" . $EXTENSION_EMPRESA . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL2)) {
                                
                                for ($a = 0; $a < count($DatosEmpresa); $a++) {
                                    
                                    //Se realiza inserción de los detalles de la empresa direccion, valor empresa celular empresa
                                    $InsercionSQL = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_EMPRESA(FKDETEMP_NCLI_NCODIGO, DETEMP_CCONSULTA, DETEMP_CDETALLE,DETEMP_CESTADO,DETEMP_CDETALLE_REGISTRO ) VALUES( '" .  $PKEMP_NCODIGO . "', '" . $DatosEmpresa[$a] . "', '" . $DatosEmpresa1[$a] . "' , '" . $Estado . "', '" . $RegistradoPor . "');";
                                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL)) {
                                    } else {
                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                        mysqli_close($ConexionSQL);
                                        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                        exit;
                                    }
                                }

                                //Se valida si existe el cliente
                                $ConsultaSQL = "SELECT PKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE CLI_CDOCUMENTO = '" . $DOCUMENTO_CLIENTE . "' AND CLI_CESTADO = '" . $Estado . "' LIMIT 1;";
                                if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {                
                                    $CantidadResultados = $ResultadoSQL->num_rows;
                                    if ($CantidadResultados > 0) {
                                        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                                            $PKCLI_NCODIGO = $FilaResultado['PKCLI_NCODIGO'];
                                            break;
                                        }
                                        
                                        //se genera actualización de los datos basicos del cliente en rcliente
                                        $ActualizarSQL = "UPDATE u632406828_dbp_crmfuturus.TBL_RCLIENTE SET CLI_CDOCUMENTO = '" . $DOCUMENTO_CLIENTE . "', CLI_CTIPO_DOCUMENTO = '" . $TIPO_DOCUMENTO . "', CLI_CNOMBRE = '" . $NOMBRE_CLIENTE1 . "', CLI_CNOMBRE2 = '" . $NOMBRE_CLIENTE2 . "', CLI_CAPELLIDO = '" . $APELLIDO_CLIENTE . "', CLI_CAPELLIDO2 = '" . $APELLIDO_CLIENTE2 . "', CLI_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE PKCLI_NCODIGO= '" .  $PKCLI_NCODIGO . "' AND CLI_CESTADO = '" . $Estado . "';";
                                        if ($ResultadoSQL = $ConexionSQL->query($ActualizarSQL)) {

                                            //SE realiza actualización de la dirección del cliente
                                            $InsercionSQL4 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE3,DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', '" . $DireccionDomicilio . "', '" . $DIRECCION_DOMICILIO . "', '" . $PAIS_CLIENTE . "', '" . $CIUDAD_CLIENTE . "', '" . $RegistradoPor . "', '" . $Estado . "')";
                                            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL4)) {

                                                for ($b = 0; $b < count($DatosCliente); $b++) {

                                                    //Se realiza actualización del resto de detalles del cliente
                                                    if ($DatosCliente[$b] == 'CelularCliente'){
                                                        $Actualizar2 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO,DETCLI_CCONSULTA,DETCLI_CDETALLE,DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES('" .  $PKCLI_NCODIGO . "', '" . $DatosCliente[$b] . "', '" . $DatosCliente1[$b] . "', '" . $RegistradoPor . "', '" . $Estado . "') ";
                                                    } else if ($DatosCliente[$b] == 'DireccionOficina'){
                                                        $Actualizar2 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO,DETCLI_CCONSULTA,DETCLI_CDETALLE,DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES('" .  $PKCLI_NCODIGO . "', '" . $DatosCliente[$b] . "', '" . $DatosCliente1[$b] . "', '" . $RegistradoPor . "', '" . $Estado . "') ";
                                                    } else {
                                                        $Actualizar2 = "UPDATE u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE SET DETCLI_CDETALLE = '" . $DatosCliente1[$b] . "', DETCLI_CDETALLE_REGISTRO = '" . $ActualizadoPor . "' WHERE FKDETCLI_NCLI_NCODIGO = '" . $PKCLI_NCODIGO . "' AND DETCLI_CCONSULTA = '" . $DatosCliente[$b] . "' AND DETCLI_CESTADO = '" . $Estado . "';";
                                                        if ($ResultadoSQL = $ConexionSQL->query($Actualizar2)) {
                                                        } else {
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                            exit;
                                                        }
                                                    }
                                                }

                                                $CONTADOR = explode("/", $FONDOS_ANTERIORES);
                                                $CONTADOR2 = explode("/", $FECHA_CAMBIO);
                                                $DatosFondosAntes = $CONTADOR;
                                                $DatosFechaCambio = $CONTADOR2;
                                                //Diccionario de datos para fondos anteriores
                                                for ($c = 0; $c < count($DatosFondosAntes); $c++) {

                                                    $fondo = "";
                                                    switch ($DatosFondosAntes[$c]) {
                                                        case 'SK':
                                                            $fondo = "Skandia";
                                                            break;
                                                        case 'PV':
                                                            $fondo = "Porvenir";
                                                            break;
                                                        case 'PT':
                                                            $fondo = "Proteccion";
                                                            break;
                                                        case 'COL':
                                                            $fondo = "Colfondos";
                                                            break;
                                                        case 'C':
                                                            $fondo = "Colpensiones";
                                                            break;
                                                        default:
                                                            $fondo = "";
                                                            break;
                                                    }

                                                    if ($fondo == '') {} else {                                 
                                                        //Se realiza inserción de los fondos anteriores y las fechas en las que estuvo el cliente con ese fondo
                                                        $InsercionSQL6 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA,DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO)VALUES ('" . $PKCLI_NCODIGO . "', '" . $FondosAnteriores . "' , '" . $fondo . "',' " . $DatosFechaCambio[$c] . " ' , ' " . $RegistradoPor . " ', ' " .$Estado2. " ')";
                                                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL6)) {
                                                        } else {
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                            exit;
                                                        }
                                                    }
                                                }
                                            } else {
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                exit;
                                            }    
                                        } else{
                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                            mysqli_close($ConexionSQL);
                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                            exit;
                                        }
                                    } else{

                                        //Se empieza a realizar inserción del cliente en la tabla de rcliente
                                        $InsercionSQL3 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RCLIENTE(CLI_CDOCUMENTO, CLI_CTIPO_DOCUMENTO, CLI_CNOMBRE, CLI_CNOMBRE2, CLI_CAPELLIDO, CLI_CAPELLIDO2, CLI_CDETALLE_REGISTRO, CLI_CESTADO) VALUES ('" . $DOCUMENTO_CLIENTE . "', '" . $TIPO_DOCUMENTO . "', '" . $NOMBRE_CLIENTE1 . "', '" . $NOMBRE_CLIENTE2 . "', '" . $APELLIDO_CLIENTE . "', '" . $APELLIDO_CLIENTE2 . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL3)) {
                                            
                                            //consulta de la llave primaria para poder realizar inserción en detalles del cliente
                                            $ConsultaSQL2 = "SELECT PKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCLIENTE WHERE CLI_CDOCUMENTO = '" . $DOCUMENTO_CLIENTE . "' AND CLI_CESTADO = '" . $Estado . "' LIMIT 1;";
                                            if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL2)) {
                                                $CantidadResultados = $ResultadoSQL->num_rows;
                                                if ($CantidadResultados > 0) {
                                                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                                                        $PKCLI_NCODIGO = $FilaResultado['PKCLI_NCODIGO'];
                                                        break;
                                                    }
                                                    
                                                    //Se realiza la inserción del detalle de dirección en la tabla de los detalles del cliente
                                                    $InsercionSQL4 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE3,DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', '" . $DireccionDomicilio . "', '" . $DIRECCION_DOMICILIO . "', '" . $PAIS_CLIENTE . "', '" . $CIUDAD_CLIENTE . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                                                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL4)) {
                                                        
                                                        //Se realiza la inserción de la empresa asociada al cliente según corresponda.
                                                        $InsercionSQL8 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES ('" . $PKCLI_NCODIGO . "', 'EmpresaCliente', '" .  $EmpresaCliente . "', '" . $RegistradoPor . "', '" . $Estado . "' );";
                                                        if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL8)) {

                                                            for ($b = 0; $b < count($DatosCliente); $b++) {
                                                                
                                                                //Inserción del resto de detales de rcliente con un for para que me recorra los dos arreglos
                                                                $InsercionSQL5 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE (FKDETCLI_NCLI_NCODIGO,DETCLI_CCONSULTA,DETCLI_CDETALLE,DETCLI_CDETALLE_REGISTRO, DETCLI_CESTADO) VALUES('" .  $PKCLI_NCODIGO . "', '" . $DatosCliente[$b] . "', '" . $DatosCliente1[$b] . "', '" . $RegistradoPor . "', '" . $Estado . "');";
                                                                if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL5)) {
                                                                } else {
                                                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                    mysqli_close($ConexionSQL);
                                                                    echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                                    exit;
                                                                }
                                                            }

                                                            $CONTADOR = explode("/", $FONDOS_ANTERIORES);
                                                            $CONTADOR2 = explode("/", $FECHA_CAMBIO);
                                                            $DatosFondosAntes = $CONTADOR;
                                                            $DatosFechaCambio = $CONTADOR2;
                                                            //Diccionario de datos de los fondos anteriores.
                                                            for ($c = 0; $c < count($DatosFondosAntes); $c++) {

                                                                $fondo = "";
                                                                switch ($DatosFondosAntes[$c]) {
                                                                    case 'SK':
                                                                        $fondo = "Skandia";
                                                                        break;
                                                                    case 'PV':
                                                                        $fondo = "Porvenir";
                                                                        break;
                                                                    case 'PT':
                                                                        $fondo = "Proteccion";
                                                                        break;
                                                                    case 'COL':
                                                                        $fondo = "Colfondos";
                                                                        break;
                                                                    case 'C':
                                                                        $fondo = "Colpensiones";
                                                                        break;
                                                                    default:
                                                                        $fondo = "";
                                                                        break;
                                                                }
                                                                if ($fondo == '') {} else {
                                                                    //Se realiza inserción de los fondos anteriores según la fecha en la que estuvo el cliente.
                                                                    $InsercionSQL6 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE(FKDETCLI_NCLI_NCODIGO, DETCLI_CCONSULTA,DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE_REGISTRO,DETCLI_CESTADO)VALUES ('" . $PKCLI_NCODIGO . "', '" . $FondosAnteriores . "' , '" . $fondo . "',' " . $DatosFechaCambio[$c] . " ' , ' " . $RegistradoPor . " ', ' " .$Estado2. " ');";
                                                                    if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL6)) {
                                                                    } else {
                                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                        mysqli_close($ConexionSQL);
                                                                        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                                        exit;
                                                                    }
                                                                }
                                                            }
                                                            //Se inserta cliente en la tabla de call para que pueda ser gestionado POR el asesor.
                                                            $InsercionSQL7 = "INSERT INTO u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL(FKPENCAL_NPKCLI_NCODIGO, PENCAL_CFECHA_OFRECIMIENTO, PENCAL_CDETALLE_REGISTRO, PENCAL_CESTADO) VALUES('" . $PKCLI_NCODIGO . "','" . $Fecha . "' ,'" . $ActualizadoPor . "', '" . $Estado . "');";
                                                            if ($ResultadoSQL = $ConexionSQL->query($InsercionSQL7)) {
                                                            } else {
                                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                                mysqli_close($ConexionSQL);
                                                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                                exit;
                                                            }
                                                        } else {
                                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                                            mysqli_close($ConexionSQL);
                                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                            exit;
                                                        }
                                                    } else {
                                                        $ErrorConsulta = mysqli_error($ConexionSQL);
                                                        mysqli_close($ConexionSQL);
                                                        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                        exit;
                                                    }
                                                } else {
                                                    //Sin resultados
                                                    $PKCLI_NCODIGO = "";
                                                    mysqli_close($ConexionSQL);
                                                    echo ('<script>alert("' . 'Error consultando el cliente recien creado' . '")</script>');
                                                    exit;
                                                }
                                            } else {
                                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                                mysqli_close($ConexionSQL);
                                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                                exit;
                                            }
                                        } else {
                                            $ErrorConsulta = mysqli_error($ConexionSQL);
                                            mysqli_close($ConexionSQL);
                                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                            exit;
                                        }
                                    }
                                } else{
                                    $ErrorConsulta = mysqli_error($ConexionSQL);
                                    mysqli_close($ConexionSQL);
                                    echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                    exit;
                                }
                                
                            } else {
                                $ErrorConsulta = mysqli_error($ConexionSQL);
                                mysqli_close($ConexionSQL);
                                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                                exit;
                            }
                        } else {
                            $ErrorConsulta = mysqli_error($ConexionSQL);
                            mysqli_close($ConexionSQL);
                            echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                            exit;
                        }
                    } else {
                        //Sin resultados
                        $PKEMP_NCODIGO = "";
                        mysqli_close($ConexionSQL);
                        echo ('<script>alert("' . 'Error al momento de validar la empresa recien registrada' . '")</script>');
                        exit;
                    }
                } else {
                    $ErrorConsulta = mysqli_error($ConexionSQL);
                    mysqli_close($ConexionSQL);
                    echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                    exit;
                }
            } else {
                $ErrorConsulta = mysqli_error($ConexionSQL);
                mysqli_close($ConexionSQL);
                echo ('<script>alert("' . $ErrorConsulta . '")</script>');
                echo ('<script>window.location="CargueBasePrincipal.php"</script>');
                exit;
            }
        }
    } else {

        $ErrorConsulta = mysqli_error($ConexionSQL);
        mysqli_close($ConexionSQL);
        echo ('<script>alert("' . $ErrorConsulta . '")</script>');
        exit;
    }
}

mysqli_close($ConexionSQL);
echo ('<script>alert("¡Base Procesada Correctamente!")</script>');
echo ('<script>window.location="CargueBasePrincipal.php"</script>');
exit;
