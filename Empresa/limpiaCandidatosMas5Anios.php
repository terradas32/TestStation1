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
        echo $fecha_menos5;

        //Sacamos todos los CANDIDATOS donde la fecha desde la que finalizaron la prueba, sea mayor a 5 años

        $Start_Date_Time = $fecha_menos5 . ' 00:00';
        $Start_Date_Time_Hast = $fecha_menos5 . ' 23:59';

        $sqlCandidatos  = "SELECT * FROM candidatos WHERE ";
        $sqlCandidatos .= " fecMod < " . $conn->qstr($Start_Date_Time_Hast, false);
        $sqlCandidatos .= " ORDER BY fecMod ASC LIMIT 0,1000";
        echo("<br>SQL:: " . $sqlCandidatos);
        echo("<br>PROCESANDO:: " . date('d/m/Y H:i:s'));

        $rsCandidatosABorrar = $conn->Execute($sqlCandidatos);
        if ($rsCandidatosABorrar->recordCount() <= 0) {
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
        $sSQL="";
        while (!$rsCandidatosABorrar->EOF) {

            //Borramos todas las posibles asignaciones de candidato
            $sSQL="DELETE FROM proceso_pruebas_candidato WHERE ";
            $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
            if ($conn->Execute($sSQL)) {
                $sSQL="DELETE FROM umarkedcandidatesforhiring WHERE ";
                $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                if ($conn->Execute($sSQL)) {
                    $sSQL="DELETE FROM respuestas_pruebas WHERE ";
                    $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                    $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                    $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                    if ($conn->Execute($sSQL)) {
                        $sSQL="DELETE FROM respuestas_pruebas_items WHERE ";
                        $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                        $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                        $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                        if ($conn->Execute($sSQL)) {
                            $sSQL="DELETE FROM consumos WHERE ";
                            $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                            if ($conn->Execute($sSQL)) {
                                $sSQL="DELETE FROM export_personalidad WHERE ";
                                $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                if ($conn->Execute($sSQL)) {
                                    $sSQL="DELETE FROM export_personalidad_competencias WHERE ";
                                    $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                    $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                    $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                    if ($conn->Execute($sSQL)) {
                                        $sSQL="DELETE FROM export_personalidad_laboral WHERE ";
                                        $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                        $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                        $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                        if ($conn->Execute($sSQL)) {
                                            $sSQL="DELETE FROM export_aptitudinales WHERE ";
                                            $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                            if ($conn->Execute($sSQL)) {
                                                $sSQL="DELETE FROM envios WHERE ";
                                                $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                                if ($conn->Execute($sSQL)) {
                                                    $sSQL="DELETE FROM candidatos WHERE ";
                                                    $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                                    $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                                    $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                                    if ($conn->Execute($sSQL)) {
                                                        $sSQL = "BORRADO:: idEmpresa=" . $rsCandidatosABorrar->fields['idEmpresa'] . " AND idProceso=" . $rsCandidatosABorrar->fields['idProceso'] . " AND idCandidato:" . $rsCandidatosABorrar->fields['idCandidato'] . " Última actualización: " . $rsCandidatosABorrar->fields['fecMod'] . " Finalizó el:" . $rsCandidatosABorrar->fields['fechaFinalizado'];
                                                        $sTypeError	=	date('d/m/Y H:i:s') . " MANTENIMIENTO OK [limpiaCandidatosMas5Anios]";
                                                        error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . date('Y-m') . "-limpiaMas5Anios.log");
                                                    } else {
                                                        $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][candidatos]";
                                                        error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                                        exit;
                                                    }
                                                } else {
                                                    $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][envios]";
                                                    error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                                    exit;
                                                }
                                            } else {
                                                $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][export_aptitudinales]";
                                                error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                                exit;
                                            }
                                        } else {
                                            $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][export_personalidad_laboral]";
                                            error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                            exit;
                                        }
                                    } else {
                                        $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][export_personalidad_competencias]";
                                        error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                        exit;
                                    }
                                } else {
                                    $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][export_personalidad]";
                                    error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                    exit;
                                }
                            } else {
                                $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][consumos]";
                                error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                                exit;
                            }
                        } else {
                            $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][respuestas_pruebas_items]";
                            error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                            exit;
                        }
                    } else {
                        $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][respuestas_pruebas]";
                        error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                        exit;
                    }
                } else {
                    $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][umarkedcandidatesforhiring]";
                    error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                    exit;
                }
            } else {
                $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [limpiaCandidatosMas5Anios][proceso_pruebas_candidato]";
                error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-limpiaMas5Anios.log");
                exit;
            }

               
            $iP++;
            $rsCandidatosABorrar->MoveNext();
        }
        echo("<br>FIN PROCESANDO:: " . date('d/m/Y H:i:s'));
