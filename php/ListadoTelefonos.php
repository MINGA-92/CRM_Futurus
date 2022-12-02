<?php

require('common.php');
require('funciones_generales.php');

$CodigoContacto = $_POST['CodigoContacto'];
$Estado1 = 'Activo';

$ConsultaSQL = "SELECT PKDETCLI_NCODIGO, DETCLI_CCONSULTA, DETCLI_CDETALLE, DETCLI_CDETALLE1, DETCLI_CDETALLE2 FROM u632406828_dbp_crmfuturus.TBL_RDETALLE_CLIENTE WHERE FKDETCLI_NCLI_NCODIGO = '" . $CodigoContacto . "' AND DETCLI_CESTADO = '" . $Estado1 . "';";
  if($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)){
      $CantidadResultados = $ResultadoSQL->num_rows;
      if($CantidadResultados > 0){
        $ListaCelulares = '';
        $ListaTelefonos = '';

          while($FilaResultado = $ResultadoSQL->fetch_assoc()){
              $LlavePrimaria = $FilaResultado['PKDETCLI_NCODIGO'];
              $DetalleNumero = $FilaResultado['DETCLI_CCONSULTA'];
              $DETCLI_CDETALLE = $FilaResultado['DETCLI_CDETALLE'];
              $DETCLI_CDETALLE1 = $FilaResultado['DETCLI_CDETALLE1'];
              $DETCLI_CDETALLE2 = $FilaResultado['DETCLI_CDETALLE2'];
  
              if($DetalleNumero == "CelularCliente"){
                $ListaCelulares .= '
                  <tr id="ContenedorInformacion' . $LlavePrimaria  . '">
                    <td id="MovilClienteModificar' . $LlavePrimaria . '">
                      <p id="TextoMovilCliente' . $LlavePrimaria . '">' . $DETCLI_CDETALLE . '</p>
                    </td>
                    <th id="ControlCambiosMovilCliente' . $LlavePrimaria . '" style="text-align: center;"></th>
                    <th id="ControlCancelacionesMovil' . $LlavePrimaria . '" style="text-align: center;"></th>
                  </tr>
                  </div>';
              }else if($DetalleNumero == "TelefonoCliente"){
                  $ListaTelefonos .= '
                    <tr id="ContenedorInformacionFijos' . $LlavePrimaria . '">
                      <td id="IndicativoFijo' . $LlavePrimaria . '">
                        <p id="TextoIndicativoFijo' . $LlavePrimaria . '">' . $DETCLI_CDETALLE1 . '</p>
                      </td>
                      <td id="NumeroFijo' . $LlavePrimaria . '">
                        <p id="TextoNumeroFijo' . $LlavePrimaria . '">' . $DETCLI_CDETALLE . '</p>
                      </td>
                      <td id="ExtencionFijo' . $LlavePrimaria . '">
                        <p id="TextoExtencionFijo' . $LlavePrimaria . '">' . $DETCLI_CDETALLE2 . '</p>
                      </td>
                      <td id="ControlCambiosNumeroFijo' . $LlavePrimaria . '" style="text-align: center;"></td>
                      <td id="ControlCancelacionesNumeroFijo' . $LlavePrimaria . '" style="text-align: center;"></td>
                    </tr>
                    </div>';
              }  
          }

          $ListaCelulares .= '';
          $ListaTelefonos .= '';

      }else{
          //Sin Resultados
          $php_response = array("msg" => "Sin Resultados");
          mysqli_close($ConexionSQL);
          echo json_encode($php_response);
          exit;
      }
  }else{
      //Error en la consulta sql
      $Falla = mysqli_error($ConexionSQL);
      $php_response = array("msg" => "Error en la consulta", "Falla" => $Falla);
      mysqli_close($ConexionSQL);
      exit;
  }
  $php_response = array("msg" => "Ok", "CodigoCliente" => $CodigoContacto, "ListaCelulares" =>  $ListaCelulares, "ListaTelefonos" => $ListaTelefonos);



mysqli_close($ConexionSQL);
echo json_encode($php_response);
exit;

?>