
<?php


if ($PER_CNIVEL == 'Agente HomeOffice'){
    if ($CantidadCasosVencidos == ""){
        $CantidadCasosVencidos = "0";
    }
    echo '<li class="menu-heading">Total De Clientes Por Llamar: '.$CantidadCasosVencidos. '   🤙🏼😲</li></br></li>';
    
    for ($i = 0; $i < count($DatosNA); $i++) {
            
        $NombreCliente = $DatosNA[$i][0];
        $EstadoAtencion = $DatosNA[$i][1];
        $FechaLlamar = $DatosNA[$i][2];
        $CodigoCasoVencido = $DatosNA[$i][3];
    
        echo '
        <li>
            <a href="Frankenstein.php?CodigoCasoVencido='.$CodigoCasoVencido.'">
                <span>'.$NombreCliente.'</span>
            </a>
        </li>';
    
    }
    
    

} else if($PER_CNIVEL == 'Supervisor'){

    if($CantidadCasosNuevos < 420){

        $FiltrarDatosN = array();
        if($DatosN != null){
            $FiltrarDatosN = array_unique($DatosN, SORT_REGULAR);
            $FiltrarDatosN = array_values($FiltrarDatosN);
            
        }else{
            $FiltrarDatosN = array(0 => "");
        }
     

        $FiltrarDatosNO = array();
        if ($DatosNO != null) {
            $FiltrarDatosNO = array_unique($DatosNO, SORT_REGULAR);
            $FiltrarDatosNO = array_values($FiltrarDatosNO);
        }
    
        echo '
        <li class="menu-heading">Total De Casos Sin Gestionar: '.$CantidadCasosNuevos.' </li></br></li>';
    
        for ($i = 0; $i < count($FiltrarDatosN); $i++) {
            
            $NombreAsesor = $FiltrarDatosN[$i][1];
            $NumeroDeCasos = $FiltrarDatosN[$i][2];
    
            if ($NumeroDeCasos < 28) {
                echo '<li>
                    <span>'.$NombreAsesor.':  '.$NumeroDeCasos. ' 😲</span>
                </li>';
            } else{
                echo '<li>
                    <span>Suficientes Casos 😉👍🏼</span>
                </li>';
            
            }
    
        }

        for ($i = 0; $i < count($FiltrarDatosNO); $i++) {
            
            $NombreAsesor = $FiltrarDatosNO[$i][1];
            $NumeroDeCasos = $FiltrarDatosNO[$i][2];

            if ($NumeroDeCasos < 28) {
                echo '<li>
                    <span>'.$NombreAsesor.':  '.$NumeroDeCasos. ' 😱</span>
                </li>';
            } else{
                echo '<li>
                    <span>Suficientes Casos 😉👍🏼</span>
                </li>';
            
            }
    
        }
    
    } else{
        echo '
        <li class="menu-heading">Total De Casos Sin Gestionar: '.$CantidadCasosNuevos.' </li></br>
        <li>
            <span>!No Hay Alertas De Casos Por El Momento! 😉</span>
        </li>';
    
    }

}

?>

