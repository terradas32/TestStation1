<?php
        require_once("include/Configuracion.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/lang/es.php");
        define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
        include(constant("DIR_ADODB") . 'adodb.inc.php');
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "/Utilidades.php");


        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
        
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidatoDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidato.php");

        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");

        include_once('include/conexion.php');

        //Conexión a e-Cases
        include_once('include/conexionECases.php');
        //FIN Conexión a e-Cases
        $cUtilidades	= new Utilidades();
        $cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB

        //

        //Le resto 5 años
        $now = date("Y-m-d");
        $fecha_menos5= date("Y-m-d", strtotime($now."- 5 years"));
        echo $fecha_menos5;exit;

        //Sacamos todos los procesos donde la fecha de fin sea menor a fecha_menos5
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
        $cProcesosDB = new ProcesosDB($conn);
        $cProcesos = new Procesos();
        $Start_Date_Time = $fecha_menos5 . ' 00:00';
        $Start_Date_Time_Hast = $fecha_menos5 . ' 23:59';

        $sqlProcesos  = "SELECT * FROM procesos WHERE ";
        $sqlProcesos .= " fechaFin < " . $conn->qstr($Start_Date_Time_Hast, false);
        $sqlProcesos .= " ORDER BY fechaFin ASC LIMIT 0,250";
        echo("<br>SQL:: " . $sqlProcesos);
        //exit;

        $rsProcesosABorrar = $conn->Execute($sqlProcesos);
        if ($rsProcesosABorrar->recordCount() <= 0) {
            echo("<br><br>SinDatos:: Sin Datos que procesar para la fecha dada.");
        }
        $cProceso_informesDB	= new Proceso_informesDB($conn);
        $cProceso_baremosDB	= new Proceso_baremosDB($conn);
        $cProceso_pruebasDB	= new Proceso_pruebasDB($conn);
        $cProceso_pruebas_candidato	= new Proceso_pruebas_candidatoDB($conn);
        $cCandidatosDB = new CandidatosDB($conn);
        $cCorreos_procesoDB = new Correos_procesoDB($conn);
        $cProcesosDB = new ProcesosDB($conn);

        $iP=0;
        while (!$rsProcesosABorrar->EOF) {

            //Borramos todas las posibles asignaciones con el proceso
            $sSQL="DELETE FROM proceso_informes WHERE ";
            $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
            $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
            if ($conn->Execute($sSQL)) {
                $sSQL="DELETE FROM proceso_baremos WHERE ";
                $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                if ($conn->Execute($sSQL)) {
                    $sSQL="DELETE FROM proceso_pruebas WHERE ";
                    $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                    $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                    if ($conn->Execute($sSQL)) {
                        $sSQL="DELETE FROM proceso_pruebas_candidato WHERE ";
                        $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                        $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                        if ($conn->Execute($sSQL)) {
                            $sSQL="DELETE FROM correos_proceso_ley WHERE ";
                            $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                            $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                            if ($conn->Execute($sSQL)) {
                                $sSQL="DELETE FROM umarkedcandidatesforhiring WHERE ";
                                $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                if ($conn->Execute($sSQL)) {
                                    $sSQL="DELETE FROM respuestas_pruebas WHERE ";
                                    $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                    $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                    if ($conn->Execute($sSQL)) {
                                        $sSQL="DELETE FROM respuestas_pruebas_items WHERE ";
                                        $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                        $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                        if ($conn->Execute($sSQL)) {
                                            $sSQL="DELETE FROM correos_proceso WHERE ";
                                            $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                            $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                            if ($conn->Execute($sSQL)) {
                                                $sSQL="DELETE FROM candidatos WHERE ";
                                                $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                if ($conn->Execute($sSQL)) {
                                                    $sSQL="DELETE FROM consumos WHERE ";
                                                    $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                    $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                    if ($conn->Execute($sSQL)) {
                                                        $sSQL="DELETE FROM export_personalidad WHERE ";
                                                        $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                        $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                        if ($conn->Execute($sSQL)) {
                                                            $sSQL="DELETE FROM export_personalidad_competencias WHERE ";
                                                            $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                            $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                            if ($conn->Execute($sSQL)) {
                                                                $sSQL="DELETE FROM export_personalidad_laboral WHERE ";
                                                                $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                                $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                                if ($conn->Execute($sSQL)) {
                                                                    $sSQL="DELETE FROM export_aptitudinales WHERE ";
                                                                    $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                                    $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                                    if ($conn->Execute($sSQL)) {
                                                                        $sSQL="DELETE FROM envios WHERE ";
                                                                        $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                                        $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                                        if ($conn->Execute($sSQL)) {
                                                                            $sSQL="DELETE FROM procesos WHERE ";
                                                                            $sSQL.=" idEmpresa= " . $conn->qstr($rsProcesosABorrar->fields['idEmpresa'], false);
                                                                            $sSQL.=" AND idProceso= " . $conn->qstr($rsProcesosABorrar->fields['idProceso'], false);
                                                                            if ($conn->Execute($sSQL)) {
                                                                                echo "<br>BORRADO:: idEmpresa:" . $rsProcesosABorrar->fields['idEmpresa'] . " idProceso:" . $rsProcesosABorrar->fields['idProceso'] . " Finalizó el:" . $rsProcesosABorrar->fields['fechaFin'];
                                                                            } else {
                                                                                echo("<br>ErrorProceso :: " . $sSQL);
                                                                                exit;
                                                                            }
                                                                        } else {
                                                                            echo("<br>ErrorProceso :: " . $sSQL);
                                                                            exit;
                                                                        }
                                                                    } else {
                                                                        echo("<br>ErrorProceso :: " . $sSQL);
                                                                        exit;
                                                                    }
                                                                } else {
                                                                    echo("<br>ErrorProceso :: " . $sSQL);
                                                                    exit;
                                                                }
                                                            } else {
                                                                echo("<br>ErrorProceso :: " . $sSQL);
                                                                exit;
                                                            }
                                                        } else {
                                                            echo("<br>ErrorProceso :: " . $sSQL);
                                                            exit;
                                                        }
                                                    } else {
                                                        echo("<br>ErrorProceso :: " . $sSQL);
                                                        exit;
                                                    }
                                                } else {
                                                    echo("<br>ErrorProceso :: " . $sSQL);
                                                    exit;
                                                }
                                            } else {
                                                echo("<br>ErrorProceso :: " . $sSQL);
                                                exit;
                                            }
                                        } else {
                                            echo("<br>ErrorProceso :: " . $sSQL);
                                            exit;
                                        }
                                    } else {
                                        echo("<br>ErrorProceso :: " . $sSQL);
                                        exit;
                                    }
                                } else {
                                    echo("<br>ErrorProceso :: " . $sSQL);
                                    exit;
                                }
                            } else {
                                echo("<br>ErrorProceso :: " . $sSQL);
                                exit;
                            }
                        } else {
                            echo("<br>ErrorProceso :: " . $sSQL);
                            exit;
                        }
                    } else {
                        echo("<br>ErrorProceso :: " . $sSQL);
                        exit;
                    }
                } else {
                    echo("<br>ErrorProceso :: " . $sSQL);
                    exit;
                }
            } else {
                echo("<br>ErrorProceso :: " . $sSQL);
                exit;
            }


            $iP++;
            $rsProcesosABorrar->MoveNext();
        }
