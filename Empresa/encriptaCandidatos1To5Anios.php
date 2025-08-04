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
        $fecha_desde1= date("Y-m-d", strtotime($now."- 1 years"));
        $fecha_hasta5= date("Y-m-d", strtotime($now."- 5 years"));
        echo $fecha_hasta5;

        //Sacamos todos los CANDIDATOS donde la fecha desde la que finalizaron la prueba, sea mayor a 5 años

        $Start_Date_Time = $fecha_desde1 . ' 00:00';
        $Start_Date_Time_Hast = $fecha_desde1 . ' 23:59';

        $End_Date_Time = $fecha_hasta5 . ' 00:00';
        $End_Date_Time_Hast = $fecha_hasta5 . ' 23:59';

        $sqlCandidatos  = "SELECT * FROM candidatos WHERE ";
        $sqlCandidatos .= " fecMod < " . $conn->qstr($Start_Date_Time, false);
        $sqlCandidatos .= " AND fecMod > " . $conn->qstr($End_Date_Time_Hast, false);
        $sqlCandidatos .= " AND encrypt IS NULL ";
        $sqlCandidatos .= " ORDER BY fecMod ASC LIMIT 0,2000";
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

            //encriptamos los datos de candidato
            $sSQL="SELECT *  FROM respuestas_pruebas WHERE ";
            $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
            $rs = $conn->Execute($sSQL);
            $descCandidato="";
            while (!$rs->EOF) {
                $descCandidato = $rs->fields['descCandidato'];
                $rs->MoveNext();
            }
            $descCandidato = $cUtilidades->encrypt($descCandidato);
            $sSQL="UPDATE respuestas_pruebas SET ";
            $sSQL.=" descCandidato= " . $conn->qstr($descCandidato, false);
            $sSQL.=" WHERE idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
            if ($conn->Execute($sSQL)) {
                $sSQL="SELECT *  FROM respuestas_pruebas_items WHERE ";
                $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                $rs = $conn->Execute($sSQL);
                $descCandidato="";
                while (!$rs->EOF) {
                    $descCandidato = $rs->fields['descCandidato'];
                    $rs->MoveNext();
                }
                $descCandidato = $cUtilidades->encrypt($descCandidato);
                $sSQL="UPDATE respuestas_pruebas_items SET ";
                $sSQL.=" descCandidato= " . $conn->qstr($descCandidato, false);
                $sSQL.=" WHERE idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                if ($conn->Execute($sSQL)) {
                    $sSQL="SELECT *  FROM consumos WHERE ";
                    $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                    $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                    $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                    $rs = $conn->Execute($sSQL);
                    $nomCandidato="";
                    $apellido1= "";
                    $apellido2= "";
                    $dni= "";
                    $mail= "";
                    while (!$rs->EOF) {
                        $nomCandidato = $rs->fields['nomCandidato'];
                        $apellido1 = $rs->fields['apellido1'];
                        $apellido2 = $rs->fields['apellido2'];
                        $dni = $rs->fields['dni'];
                        $mail = $rs->fields['mail'];
                        $rs->MoveNext();
                    }
                    $nomCandidato = $cUtilidades->encrypt($nomCandidato);
                    $apellido1 = $cUtilidades->encrypt($apellido1);
                    $apellido2 = $cUtilidades->encrypt($apellido2);
                    $dni = $cUtilidades->encrypt($dni);
                    $mail = $cUtilidades->encrypt($mail);
                    $sSQL="UPDATE consumos SET ";
                    $sSQL.=" nomCandidato= " . $conn->qstr($nomCandidato, false);
                    $sSQL.=" ,apellido1= " . $conn->qstr($apellido1, false);
                    $sSQL.=" ,apellido2= " . $conn->qstr($apellido2, false);
                    $sSQL.=" ,dni= " . $conn->qstr($dni, false);
                    $sSQL.=" ,mail= " . $conn->qstr($mail, false);
                    $sSQL.=" WHERE idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                    $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                    $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                    if ($conn->Execute($sSQL)) {
                        $sSQL="SELECT *  FROM export_personalidad WHERE ";
                        $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                        $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                        $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                        $rs = $conn->Execute($sSQL);
                        $nombre="";
                        $apellido1= "";
                        $apellido2= "";
                        $dni= "";
                        $email= "";
                        while (!$rs->EOF) {
                            $nombre = $rs->fields['nombre'];
                            $apellido1 = $rs->fields['apellido1'];
                            $apellido2 = $rs->fields['apellido2'];
                            $dni = $rs->fields['dni'];
                            $email = $rs->fields['email'];
                            $rs->MoveNext();
                        }
                        $nombre = $cUtilidades->encrypt($nombre);
                        $apellido1 = $cUtilidades->encrypt($apellido1);
                        $apellido2 = $cUtilidades->encrypt($apellido2);
                        $dni = $cUtilidades->encrypt($dni);
                        $email = $cUtilidades->encrypt($email);
                        $sSQL="UPDATE export_personalidad SET ";
                        $sSQL.=" nombre= " . $conn->qstr($nombre, false);
                        $sSQL.=" ,apellido1= " . $conn->qstr($apellido1, false);
                        $sSQL.=" ,apellido2= " . $conn->qstr($apellido2, false);
                        $sSQL.=" ,email= " . $conn->qstr($email, false);
                        $sSQL.=" ,dni= " . $conn->qstr($dni, false);
                        $sSQL.=" WHERE idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                        $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                        $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                        if ($conn->Execute($sSQL)) {
                            $sSQL="SELECT *  FROM export_aptitudinales WHERE ";
                            $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                            $rs = $conn->Execute($sSQL);
                            $nombre="";
                            $apellido1= "";
                            $apellido2= "";
                            $dni= "";
                            $email= "";
                            while (!$rs->EOF) {
                                $nombre = $rs->fields['nombre'];
                                $apellido1 = $rs->fields['apellido1'];
                                $apellido2 = $rs->fields['apellido2'];
                                $dni = $rs->fields['dni'];
                                $email = $rs->fields['email'];
                                $rs->MoveNext();
                            }
                            $nombre = $cUtilidades->encrypt($nombre);
                            $apellido1 = $cUtilidades->encrypt($apellido1);
                            $apellido2 = $cUtilidades->encrypt($apellido2);
                            $dni = $cUtilidades->encrypt($dni);
                            $email = $cUtilidades->encrypt($email);
                            $sSQL="UPDATE export_aptitudinales SET ";
                            $sSQL.=" nombre= " . $conn->qstr($nombre, false);
                            $sSQL.=" ,apellido1= " . $conn->qstr($apellido1, false);
                            $sSQL.=" ,apellido2= " . $conn->qstr($apellido2, false);
                            $sSQL.=" ,email= " . $conn->qstr($email, false);
                            $sSQL.=" ,dni= " . $conn->qstr($dni, false);
                            $sSQL.=" WHERE idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                            $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                            $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                            if ($conn->Execute($sSQL)) {
                                $sSQL="SELECT *  FROM candidatos WHERE ";
                                $sSQL.=" idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                $rs = $conn->Execute($sSQL);
                                $nombre="";
                                $apellido1= "";
                                $apellido2= "";
                                $dni= "";
                                $mail= "";
                                while (!$rs->EOF) {
                                    $nombre = $rs->fields['nombre'];
                                    $apellido1 = $rs->fields['apellido1'];
                                    $apellido2 = $rs->fields['apellido2'];
                                    $dni = $rs->fields['dni'];
                                    $mail = $rs->fields['mail'];
                                    $rs->MoveNext();
                                }
                                $nombre = $cUtilidades->encrypt($nombre);
                                $apellido1 = $cUtilidades->encrypt($apellido1);
                                $apellido2 = $cUtilidades->encrypt($apellido2);
                                $dni = $cUtilidades->encrypt($dni);
                                $mail = $cUtilidades->encrypt($mail);
                                $sSQL="UPDATE candidatos SET ";
                                $sSQL.=" nombre= " . $conn->qstr($nombre, false);
                                $sSQL.=" ,apellido1= " . $conn->qstr($apellido1, false);
                                $sSQL.=" ,apellido2= " . $conn->qstr($apellido2, false);
                                $sSQL.=" ,mail= " . $conn->qstr($mail, false);
                                $sSQL.=" ,dni= " . $conn->qstr($dni, false);
                                $sSQL.=" ,encrypt= 1";
                                $sSQL.=" WHERE idEmpresa= " . $conn->qstr($rsCandidatosABorrar->fields['idEmpresa'], false);
                                $sSQL.=" AND idProceso= " . $conn->qstr($rsCandidatosABorrar->fields['idProceso'], false);
                                $sSQL.=" AND idCandidato= " . $conn->qstr($rsCandidatosABorrar->fields['idCandidato'], false);
                                if ($conn->Execute($sSQL)) {
                                    $sSQL = "ENCRIPTADO:: idEmpresa=" . $rsCandidatosABorrar->fields['idEmpresa'] . " AND idProceso=" . $rsCandidatosABorrar->fields['idProceso'] . " AND idCandidato:" . $rsCandidatosABorrar->fields['idCandidato'] . " Última actualización: " . $rsCandidatosABorrar->fields['fecMod'] . " Finalizó el:" . $rsCandidatosABorrar->fields['fechaFinalizado'];
                                    $sTypeError	=	date('d/m/Y H:i:s') . " MANTENIMIENTO OK [encriptaCandidatos1To5Anios]";
                                    error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                                } else {
                                    $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [encriptaCandidatos1To5Anios][candidatos]";
                                    error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                                    exit;
                                }
                            } else {
                                $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [encriptaCandidatos1To5Anios][export_aptitudinales]";
                                error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                                exit;
                            }
                        } else {
                            $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [encriptaCandidatos1To5Anios][export_personalidad]";
                            error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                            exit;
                        }
                    } else {
                        $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [encriptaCandidatos1To5Anios][consumos]";
                        error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                        exit;
                    }
                } else {
                    $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [encriptaCandidatos1To5Anios][respuestas_pruebas_items]";
                    error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                    exit;
                }
            } else {
                $sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [encriptaCandidatos1To5Anios][respuestas_pruebas]";
                error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") .  date('Y-m') . "-encriptaCandidatos1To5Anios.log");
                exit;
            }

            

               
            $iP++;
            $rsCandidatosABorrar->MoveNext();
        }
        echo("<br>FIN PROCESANDO:: " . date('d/m/Y H:i:s'));
