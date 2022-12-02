
<?php

if($PER_CNIVEL == 'AgenteCall'){
    echo '<li class="menu-heading">Agente Call</li>
    <li>
        <a id="BtnAgenteCall" href="AgenteCall.php">
            <span>Agente Call</span>
        </a>
    </li>
    <li>
        <a href="MisCasosPendientesCall.php">
            <span>Mis Casos Pendientes</span>
        </a>
    </li>
    <li>
        <a href="MisCasosNoContesta.php">
            <span>Mis Casos No Contesta</span>
        </a>
    </li>
    <li>
        <a href="logout.php">
            <span>Cerrar Sesión</span>
        </a>
    </li>';
}else if ($PER_CNIVEL == 'AgenteVisitas'){
    echo '<li class="menu-heading">Agente Visitas:</li>
    <li>
        <a href="AgenteVisitas.php">
            <span>Agente Visitas</span>
        </a>
    </li>
    <li>
        <a href="MisCasosPendientes.php">
            <span>Mis Casos Pendientes</span>
        </a>
    </li>
    <li>
        <a href="logout.php">
            <span>Cerrar Sesión</span>
        </a>
    </li>';

}else if ($PER_CNIVEL == 'AgenteLegalizador'){
    echo '<li class="menu-heading">Legalizador: </li>
    <li>
        <a href="Legalizacion.php">
            <span>Legalizacion De Casos</span>
        </a>
    </li>
    <li>
    <a href="MisCasosPendientesLegalizador.php">
        <span>Mis Casos Pendientes</span>
    </a>
    </li>
    <li>
        <a href="MisEnviosAfiliacion.php">
            <span>Enviados a Afiliacion</span>
        </a>
    </li>
    <li>
        <a href="logout.php">
            <span>Cerrar Sesión</span>
        </a>
    </li>';

}else if ($PER_CNIVEL == 'Agente HomeOffice'){
    echo '<li class="menu-heading">Agente HomeOffice:</li>
    <li>
        <a href="Frankenstein.php">
            <span>Agente HomeOffice</span>
        </a>
    </li>
    <li>
        <a href="MisPendientesHomeOffice.php">
            <span>Mis Casos Pendientes</span>
        </a>
    </li>
    <li>
        <a href="MisCasosNoContesta.php">
            <span>Mis Casos No Contesta</span>
        </a>
    </li>
    <li>
        <a href="logout.php">
            <span>Cerrar Sesión</span>
        </a>
    </li>';

}else if($PER_CNIVEL == 'Supervisor'){
    echo '<li class="menu-heading ">Paginas Agentes</li>
    <li>
        <a href="AsignacionNuevosHomeOffice.php">
        - Asignación Casos Nuevos
        </a>
    </li>
    <li>
        <a href="AsignacionCasosCall.php">
        - Asignación Casos Pendientes Call
        </a>
    </li>
    <li>
        <a href="AsignacionCasosNuevos.php">
        - Asignación Casos Nuevos Visitas
        </a>
    </li>
    <li>
        <a href="AsignacionCasosPendientes.php">
        - Asignación Casos Pendientes Visitas
        </a>
    </li>
    <li>
        <a href="AsignacionCasosLegalizador.php">
        - Asignación Casos Pendientes Legalizador
        </a>
    </li>

    <li class="menu-heading">Paginas Supervisor</li>  
    <li>
        <a href="ListadoUsuarios.php">
        - Listado De Usuarios
        </a>
    </li>
    <li>
        <a href="RegistroUsuarios.php">
        - Registrar Usuario
        </a>
    </li>
    <li>
        <a href="CargueBasePrincipal.php">
        - Cargue Base Principal
        </a>
    </li>
    <li>
        <a href="EnviosAfiliacion.php">
        - Enviados a Afiliacion
        </a>
    </li>
    <li>
        <a href="AgregarUnContacto.php">
        - Agregar Un Nuevo Cliente
        </a>
    </li>
    <li>
        <a href="logout.php">
        - Cerrar Sesión
        </a>
    </li>';
}else{
    echo '<li>
        <a href="logout.php">
            <span>Cerrar Sesión</span>
        </a>
    </li>';
}


?>
