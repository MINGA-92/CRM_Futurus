
<?php

session_start();

if (isset($_SESSION['codigopermiso'])) {

    $codigopermisos = $_SESSION['codigopermiso'];
    // Consulta Nombre usuario y Supervisor
    $datos = 'Activo';
    $ConsultaSQL = "SELECT PKPER_NCODIGO, CRE_CUSUARIO, CRE_CNOMBRE, CRE_CNOMBRE2, CRE_CAPELLIDO, CRE_CAPELLIDO2, PER_CGRUPO, PER_CNIVEL FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL JOIN u632406828_dbp_crmfuturus.TBL_RPERMISO ON PKCRE_NCODIGO = FKPER_NCRE_NCODIGO AND PKPER_NCODIGO = " . $codigopermisos . " AND CRE_CESTADO = '" . $datos . "' AND PER_CESTADO = '" . $datos . "' ORDER BY PKCRE_NCODIGO DESC;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {

                $AGENTE = $FilaResultado['PKPER_NCODIGO'];
                $CRE_CUSUARIO = $FilaResultado['CRE_CUSUARIO'];

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

    // Consulta Notificaciones
    $ConsultaPermisos = "SELECT PKPER_NCODIGO, FKPER_NCRE_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PER_CNIVEL= 'Agente HomeOffice' AND PER_CESTADO= 'Activo';";
    if ($ResultadoPermisos = $ConexionSQL->query($ConsultaPermisos)) {
        $CantidadResultado = $ResultadoPermisos->num_rows;
        if ($CantidadResultado > 0) {
            $CantidadAgentes = $CantidadResultado;
            while ($FilaResultado = $ResultadoPermisos->fetch_assoc()) {
                $CodigoTblPermisos = $FilaResultado['PKPER_NCODIGO'];
                $CodigoTblCredencial = $FilaResultado['FKPER_NCRE_NCODIGO'];

                //cONSULTA sI hAY cASOS
                $ConsultaCasos = "SELECT PKPENCAL_NCODIGO, FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKPER_NCODIGO='". $CodigoTblPermisos ."' AND PENCAL_CESTADO_FINAL='' AND PENCAL_COBSERVACIONES= '' AND PENCAL_CESTADO= 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NOT NULL;";
                if ($ResultadoCasos = $ConexionSQL->query($ConsultaCasos)) {
                    $CantidadResultado2 = $ResultadoCasos->num_rows;
                    if ($CantidadResultado2 > 0) {
                      
                        $DatosN = array();
                        $CantidadCasosNuevos = "";
                        $ConsultaSQL = "SELECT PKPENCAL_NCODIGO, FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL='' AND PENCAL_COBSERVACIONES= '' AND PENCAL_CESTADO= 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NOT NULL;";
                        if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
                            $CantidadResultados = $ResultadoSQL->num_rows;
                            if ($CantidadResultados > 0) {
                                $CantidadCasosNuevos = $CantidadResultados;
                                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                                    $CodigoCasoNew = $FilaResultado['PKPENCAL_NCODIGO'];
                                    $CodigoAgente = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                                    $CodigoCliente = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];

                                    //Consulta En Credencial
                                    $ConsultaSQL2 = "SELECT CRE_CNOMBRE, CRE_CAPELLIDO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE PKCRE_NCODIGO = '". $CodigoAgente ."' AND CRE_CESTADO = 'Activo';";
                                    if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                                        $CantidadResultados2 = $ResultadoSQL2->num_rows;
                                        if ($CantidadResultados2 > 0) {
                                            while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                                                $Nombre = $FilaResultado['CRE_CNOMBRE'];
                                                $Apellido = $FilaResultado['CRE_CAPELLIDO'];
                                                $NombreCompleto = $Nombre. " " . $Apellido;

                                                $ConsultaSQL3 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKPER_NCODIGO = '". $CodigoAgente ."' AND PENCAL_CESTADO_FINAL = '' AND PENCAL_CESTADO = 'Activo';";
                                                if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                                    $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                                    if ($CantidadResultados3 > 0) {
                                                        $CantidadCasosAgente = $CantidadResultados3;
                                                        array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));

                                                    } else {
                                                        $CantidadCasosAgente = 0;
                                                        array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));
                                                    }
                                                    
                                                } else {
                                                    //Errro en la consulta sql
                                                    echo mysqli_error($ConexionSQL);
                                                }

                                            }
                                            
                                        } else {
                                            //Consulta En Permisos
                                            $ConsultaSQL4 = "SELECT FKPER_NCRE_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKPER_NCODIGO= '". $CodigoAgente ."' AND PER_CESTADO= 'Activo';";
                                            if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                                                $CantidadResultados4 = $ResultadoSQL4->num_rows;
                                                if ($CantidadResultados4 > 0) {
                                                    while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                                        $CodigoPermisos = $FilaResultado['FKPER_NCRE_NCODIGO'];

                                                        $ConsultaSQL5 = "SELECT CRE_CNOMBRE, CRE_CAPELLIDO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE PKCRE_NCODIGO = '". $CodigoPermisos ."' AND CRE_CESTADO = 'Activo';";
                                                        if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                                            $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                                            if ($CantidadResultados5 > 0) {
                                                                while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                                    $Nombre = $FilaResultado['CRE_CNOMBRE'];
                                                                    $Apellido = $FilaResultado['CRE_CAPELLIDO'];
                                                                    $NombreCompleto = $Nombre. " " . $Apellido;

                                                                    $ConsultaSQL6 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKPER_NCODIGO = '". $CodigoAgente ."' AND PENCAL_CESTADO_FINAL = '' AND PENCAL_COBSERVACIONES = '' AND PENCAL_CESTADO = 'Activo';";
                                                                    if ($ResultadoSQL6 = $ConexionSQL->query($ConsultaSQL6)) {
                                                                        $CantidadResultados6 = $ResultadoSQL6->num_rows;
                                                                        if ($CantidadResultados6 > 0) {
                                                                            $CantidadCasosAgente = $CantidadResultados6;
                                                                            array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));

                                                                        } else {
                                                                            $CantidadCasosAgente = 0;
                                                                            array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));
                                                                        }
                                                                        
                                                                    } else {
                                                                        //Errro en la consulta sql
                                                                        echo mysqli_error($ConexionSQL);
                                                                    }
                                                                }
                                                                
                                                            } else {
                                                                $NombreCompleto = "";
                                                            }

                                                        } else {
                                                            //Errro en la consulta sql
                                                            echo mysqli_error($ConexionSQL);
                                                        }
                                                    }
                                                } else {
                                                    $CantidadCasosAgente = 0;
                                                }
                                                
                                            } else {
                                                //Errro en la consulta sql
                                                echo mysqli_error($ConexionSQL);
                                            }

                                        }
                                        
                                    } else {
                                        //Errro en la consulta sql
                                        echo mysqli_error($ConexionSQL);
                                    }

                                }

                            } else {

                                $CantidadCasosAgente = 0;
                                $CodigoCasoNew = "";
                                $CodigoAgente = "";
                                $CodigoCliente = "";
                            }
                            
                        } else {
                            //Errro en la consulta sql
                            echo mysqli_error($ConexionSQL);
                        }
  

                    } else{
                        
                        //Consulta Nombre En Credencial
                        $DatosNO = array();
                        $ConsultaSQL2 = "SELECT CRE_CNOMBRE, CRE_CAPELLIDO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE PKCRE_NCODIGO = '". $CodigoTblCredencial ."' AND CRE_CESTADO = 'Activo';";
                        if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                            $CantidadResultados2 = $ResultadoSQL2->num_rows;
                            if ($CantidadResultados2 > 0) {
                                while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                                    $Nombre = $FilaResultado['CRE_CNOMBRE'];
                                    $Apellido = $FilaResultado['CRE_CAPELLIDO'];
                                    $NombreCompleto = $Nombre. " " . $Apellido;
                                    $CantidadCasosAgente = 0;
                                }
                                
                            }else {
                                //Sin Resultados
                                echo mysqli_error($ConexionSQL);
                            }

                            array_push($DatosNO, array("0" => $CodigoTblPermisos, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));

                        }else {
                            echo mysqli_error($ConexionSQL);
                            
                        }
                    }

                } else {
                    //Errro en la consulta sql
                    echo mysqli_error($ConexionSQL);
                }  
            }

        } else {
            $CantidadAgentes = 0;
            //array_push($DatosN, array("0" => $CodigoTblPermisos, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
    }

    /*
    $DatosN = array();
    $CantidadCasosNuevos = "";
    $ConsultaSQL = "SELECT PKPENCAL_NCODIGO, FKPENCAL_NPKPER_NCODIGO, FKPENCAL_NPKCLI_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE PENCAL_CESTADO_FINAL='' AND PENCAL_COBSERVACIONES= '' AND PENCAL_CESTADO= 'Activo' AND FKPENCAL_NPKPER_NCODIGO IS NOT NULL;";
    if ($ResultadoSQL = $ConexionSQL->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            $CantidadCasosNuevos = $CantidadResultados;
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $CodigoCasoNew = $FilaResultado['PKPENCAL_NCODIGO'];
                $CodigoAgente = $FilaResultado['FKPENCAL_NPKPER_NCODIGO'];
                $CodigoCliente = $FilaResultado['FKPENCAL_NPKCLI_NCODIGO'];

                //Consulta En Credencial
                $ConsultaSQL2 = "SELECT CRE_CNOMBRE, CRE_CAPELLIDO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE PKCRE_NCODIGO = '". $CodigoAgente ."' AND CRE_CESTADO = 'Activo';";
                if ($ResultadoSQL2 = $ConexionSQL->query($ConsultaSQL2)) {
                    $CantidadResultados2 = $ResultadoSQL2->num_rows;
                    if ($CantidadResultados2 > 0) {
                        while ($FilaResultado = $ResultadoSQL2->fetch_assoc()) {
                            $Nombre = $FilaResultado['CRE_CNOMBRE'];
                            $Apellido = $FilaResultado['CRE_CAPELLIDO'];
                            $NombreCompleto = $Nombre. " " . $Apellido;

                            $ConsultaSQL3 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKPER_NCODIGO = '". $CodigoAgente ."' AND PENCAL_CESTADO_FINAL = '' AND PENCAL_CESTADO = 'Activo';";
                            if ($ResultadoSQL3 = $ConexionSQL->query($ConsultaSQL3)) {
                                $CantidadResultados3 = $ResultadoSQL3->num_rows;
                                if ($CantidadResultados3 > 0) {
                                    $CantidadCasosAgente = $CantidadResultados3;

                                } else {
                                    $CantidadCasosAgente = 0;
                                    array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));
                                }
                                
                            } else {
                                //Errro en la consulta sql
                                echo mysqli_error($ConexionSQL);
                            }

                        }
                        
                    } else {
                        //Consulta En Permisos
                        $ConsultaSQL4 = "SELECT FKPER_NCRE_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPERMISO WHERE PKPER_NCODIGO= '". $CodigoAgente ."' AND PER_CESTADO= 'Activo';";
                        if ($ResultadoSQL4 = $ConexionSQL->query($ConsultaSQL4)) {
                            $CantidadResultados4 = $ResultadoSQL4->num_rows;
                            if ($CantidadResultados4 > 0) {
                                while ($FilaResultado = $ResultadoSQL4->fetch_assoc()) {
                                    $CodigoPermisos = $FilaResultado['FKPER_NCRE_NCODIGO'];

                                    $ConsultaSQL5 = "SELECT CRE_CNOMBRE, CRE_CAPELLIDO FROM u632406828_dbp_crmfuturus.TBL_RCREDENCIAL WHERE PKCRE_NCODIGO = '". $CodigoPermisos ."' AND CRE_CESTADO = 'Activo';";
                                    if ($ResultadoSQL5 = $ConexionSQL->query($ConsultaSQL5)) {
                                        $CantidadResultados5 = $ResultadoSQL5->num_rows;
                                        if ($CantidadResultados5 > 0) {
                                            while ($FilaResultado = $ResultadoSQL5->fetch_assoc()) {
                                                $Nombre = $FilaResultado['CRE_CNOMBRE'];
                                                $Apellido = $FilaResultado['CRE_CAPELLIDO'];
                                                $NombreCompleto = $Nombre. " " . $Apellido;

                                                $ConsultaSQL6 = "SELECT PKPENCAL_NCODIGO FROM u632406828_dbp_crmfuturus.TBL_RPENSIONES_CALL WHERE FKPENCAL_NPKPER_NCODIGO = '". $CodigoAgente ."' AND PENCAL_CESTADO_FINAL = '' AND PENCAL_CESTADO = 'Activo';";
                                                if ($ResultadoSQL6 = $ConexionSQL->query($ConsultaSQL6)) {
                                                    $CantidadResultados6 = $ResultadoSQL6->num_rows;
                                                    if ($CantidadResultados6 > 0) {
                                                        $CantidadCasosAgente = $CantidadResultados6;
                                                        array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));

                                                    } else {
                                                        $CantidadCasosAgente = 0;
                                                        array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));
                                                    }
                                                    
                                                } else {
                                                    //Errro en la consulta sql
                                                    echo mysqli_error($ConexionSQL);
                                                }
                                            }
                                            
                                        } else {
                                            $NombreCompleto = "";
                                        }

                                    } else {
                                        //Errro en la consulta sql
                                        echo mysqli_error($ConexionSQL);
                                    }
                                }
                            } else {
                                $CantidadCasosAgente = 0;
                            }
                            
                        } else {
                            //Errro en la consulta sql
                            echo mysqli_error($ConexionSQL);
                        }

                    }
                    
                } else {
                    //Errro en la consulta sql
                    echo mysqli_error($ConexionSQL);
                }

                array_push($DatosN, array("0" => $CodigoAgente, "1" => $NombreCompleto, "2" => $CantidadCasosAgente));

            }

        } else {

            $CantidadCasosAgente = 0;
            $CodigoCasoNew = "";
            $CodigoAgente = "";
            $CodigoCliente = "";
        }
        
    } else {
        //Errro en la consulta sql
        echo mysqli_error($ConexionSQL);
    }
    */
    

} else {
    echo "<script>window.location='logout.php';</script>";
    exit;
}


?>