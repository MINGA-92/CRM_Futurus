
<?php


if ($PER_CNIVEL == 'Supervisor'){

    echo' <li class="menu-heading"><i class="glyphicon glyphicon-indent-left"></i> Reportes: </li>
    
    <li>
        <a href="GraficaGeneral.php">
        - Reporte General
        </a>
    </li> 
    <li>
        <a href="GraficaSinGestionar.php">
        - Casos Sin Gestionar
        </a>
    </li> 
    <li>
        <a href="GraficaGestionados.php">
        - Casos Gestionados
        </a>
    </li> 
    <li>
        <a href="GraficaPorAgente.php">
        - Casos Por Agente
        </a>
    </li>
    <li>
        <a href="GraficaEnviosAfiliacion.php">
        - Grafica Envios Afiliacion
        </a>
    </li>
    <li>
        <a href="Supervisor.php">
        - Productividad Grupo
        </a>
    </li>
    
    <li class="menu-heading"><i class="glyphicon glyphicon-list-alt"></i> Informes: </li>
    <li>
        <a href="InformeGestionCall.php">
        - Informe Gestión Call
        </a>
    </li>
    <li>
        <a href="InformeEnviadosAfiliacion.php">
        - Informe Enviados a Afiliacion
        </a>
    </li>
    <li>
        <a href="InformeAfiliaciones.php">
        - Informe Afiliaciones Confirmadas
        </a>
    </li>
    <li>
        <a href="InformeGeneralCall.php">
        - Informe Historico
        </a>
    </li>
    <li>
        <a href="InformeDatosCliente.php">
        - Informe De Clientes
        </a>
    </li>
    
    <li>
        <a href="InformeVisitas.php">
        - Informe Visitas
        </a>
    </li>
     <li>
        <a href="InformeGestionLegalizacion.php">
        - Informe Legalización
        </a>
    </li>
    <li>
        <a href="InformeLegalizacionFinal.php">
        - Informe Legalización Final
        </a>
    </li>';
       

} else if($PER_CNIVEL != 'Supervisor'){
    echo "<script>window.location='logout.php';</script>";
    exit;
}

?>

