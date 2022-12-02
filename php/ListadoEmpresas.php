<?php

require('common.php');
require('funciones_generales.php');

$Estado1 = 'Activo';

$ConsultaSQL = "SELECT PKEMP_NCODIGO, EMP_CDOCUMENTO, CONCAT(EMP_CNOMBRE, ' ', EMP_CNOMBRE2, ' ', EMP_CAPELLIDO, ' ', EMP_CAPELLIDO2) AS EMPRESA FROM u632406828_dbp_crmfuturus.TBL_REMPRESA WHERE  EMP_CESTADO = '" . $Estado1 . "';";
  if($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)){
      $CantidadResultados = $ResultadoSQL->num_rows;
      if($CantidadResultados > 0){
        $ListaEmpresas = '';

          while($FilaResultado = $ResultadoSQL->fetch_assoc()){
              $CODIGOEMPRESA = $FilaResultado['PKEMP_NCODIGO'];
              $NOMBREEMPRESA = $FilaResultado['EMPRESA'];
              $DOCUMENTOEMPRESA = $FilaResultado['EMP_CDOCUMENTO'];
              $ListaEmpresas .= '<option value="' . $CODIGOEMPRESA . '">' . $NOMBREEMPRESA . '</option>';
          }
      }else{
          //Sin Resultados
          $php_response = array("msg" => "Sin Resultados");
          mysqli_close($ConexionSQL);
          exit;
      }
  }else{
      //Error en la consulta sql
      $Falla = mysqli_error($ConexionSQL);
      $php_response = array("msg" => "Error en la consulta", "Falla" => $Falla);
      mysqli_close($ConexionSQL);
      exit;
  }
  $php_response = array("msg" => "Ok", "CodigoEmpresa" => $CODIGOEMPRESA, "ListaEmpresas" =>  $ListaEmpresas);



mysqli_close($ConexionSQL);
echo json_encode($php_response);
exit;

?>