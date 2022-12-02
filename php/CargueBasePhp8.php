<?php

require '../lib/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$fileName = '..xls/pruebaPHP.xls';
$file = IOFactory::load($fileName);
$actualPage = $file->getSheet(0);
$amountRows = $actualPage->getHighestDataRow();
$amountColumn = $actualPage->getHighestColumn();

for ($i = 1; $i <= $amountRows ; $i++) {

    $Codificacion = trim($actualPage->getCellByColumnAndRow(1, $i));
    $CodifTxt = trim($actualPage->getCellByColumnAndRow(2, $i));
    $CentroPtoTbjo = trim($actualPage->getCellByColumnAndRow(3, $i));
    $Poblacion = trim($actualPage->getCellByColumnAndRow(4, $i));
    $Centroplanif = trim($actualPage->getCellByColumnAndRow(5, $i));
    $DenomEjecut = trim($actualPage->getCellByColumnAndRow(6, $i));
    $NoPedido = trim($actualPage->getCellByColumnAndRow(7, $i));
    $FechaAviso = $actualPage->getCellByColumnAndRow(8, $i);
    $Aviso = trim($actualPage->getCellByColumnAndRow(9, $i));
    $FechaCierre = $actualPage->getCellByColumnAndRow(10, $i);
    $Descripcion = trim($actualPage->getCellByColumnAndRow(11, $i));
    $ReemplazoEquipo = trim($actualPage->getCellByColumnAndRow(12, $i));
    $CuentaContrato = trim($actualPage->getCellByColumnAndRow(13, $i));
    $Instalacion = trim($actualPage->getCellByColumnAndRow(14, $i));
    $CeEmplazam = trim($actualPage->getCellByColumnAndRow(15, $i));
    $StatusUsuario = trim($actualPage->getCellByColumnAndRow(16, $i));
    $Sociedad = trim($actualPage->getCellByColumnAndRow(17, $i));
    $AutorAviso = trim($actualPage->getCellByColumnAndRow(18, $i));
    $ModificadoPor = trim($actualPage->getCellByColumnAndRow(19, $i));
    $ClaseTarifa = trim($actualPage->getCellByColumnAndRow(20, $i));
    $TipoRespuesta = trim($actualPage->getCellByColumnAndRow(21, $i));
    $Reiteracion = trim($actualPage->getCellByColumnAndRow(22, $i));
    $ClaseAviso = trim($actualPage->getCellByColumnAndRow(23, $i));
    $FaltaInformacion = trim($actualPage->getCellByColumnAndRow(24, $i));
    $FCR = trim($actualPage->getCellByColumnAndRow(25, $i));
    $MalClasificado = trim($actualPage->getCellByColumnAndRow(26, $i));
    $InicioDeseado = $actualPage->getCellByColumnAndRow(27, $i);
    $FinDeseado = $actualPage->getCellByColumnAndRow(28, $i);
    $HoraCierre = trim($actualPage->getCellByColumnAndRow(29, $i));
    $ModificadoEl = $actualPage->getCellByColumnAndRow(30, $i);
    $ModificadoALas = $actualPage->getCellByColumnAndRow(31, $i);

    echo "$Codificacion <br>";
    echo "$CodifTxt <br>";
    echo "$CentroPtoTbjo <br>";
    echo "$Poblacion <br>";
    echo "$Centroplanif <br>";
    echo "$DenomEjecut <br>";
    echo "$NoPedido <br>";
    echo "$FechaAviso <br>";
    echo "$Aviso <br>";
    echo "$FechaCierre <br>";
    echo "$Descripcion <br>";
    echo "$ReemplazoEquipo <br>";
    echo "$CuentaContrato <br>";
    echo "$Instalacion <br>";
    echo "$CeEmplazam <br>";
    echo "$StatusUsuario <br>";
    echo "$Sociedad <br>";
    echo "$AutorAviso <br>";
    echo "$ModificadoPor <br>";
    echo "$ClaseTarifa <br>";
    echo "$TipoRespuesta <br>";
    echo "$Reiteracion <br>";
    echo "$ClaseAviso <br>";
    echo "$FaltaInformacion <br>";
    echo "$FCR <br>";
    echo "$MalClasificado <br>";
    echo "$InicioDeseado <br>";
    echo "$FinDeseado <br>";
    echo "$HoraCierre <br>";
    echo "$ModificadoEl <br>";
    echo "$ModificadoALas <br>";
}

?>
