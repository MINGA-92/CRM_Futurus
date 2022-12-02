<?php

session_start();
require('common.php');
require('funciones_generales.php');

$usuario = $_POST['usuario'];
$password = $_POST['password'];
$username = quitarCaracteres(trim($usuario));
$passw = Encrypt(trim($password));

$ConsultaSQL = "SELECT PKPER_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL, u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND CRE_CUSUARIO = '" . $username . "' AND PER_CCONTRASENA = '" . $passw . "' AND PER_CESTADO = 'Activo';";
if($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        if ($CantidadResultados = 1) {
            // 1 Resultados
            if(isset($_SESSION)){
                session_destroy();
            }
            session_start();
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $_SESSION['codigopermiso'] = $FilaResultado['PKPER_NCODIGO'];
                break;
            }
            mysqli_free_result($ResultadoSQL);
            mysqli_close($ConexionSQL);
            echo '1';
        } else {
            if(isset($_SESSION)){
                session_destroy();
            }
            session_start();
            // Mas de 1 Resultados
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $_SESSION['codigopermiso'] = $FilaResultado['PKCRE_NCODIGO'];
                break;
            }
            mysqli_free_result($ResultadoSQL);
            mysqli_close($ConexionSQL);
            echo '2';
        }
    } else {
        // Sin Resultados
        mysqli_close($ConexionSQL);
        if(isset($_SESSION)){
            session_destroy();
        }
        echo '0';
    }
} else {
    // Error en la Consulta
    $ErrorConsulta = mysqli_error($ConexionSQL);
    mysqli_close($ConexionSQL);
    if(isset($_SESSION)){
        session_destroy();
    }
    echo $ErrorConsulta;
}
?>