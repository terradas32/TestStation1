<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/Configuracion.php");
    }
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "SeguridadCandidatos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");

	if (!isset($conn)){
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "01000 - " . constant("ERR_NO_AUTORIZADO");
		echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
		exit;
	}

    $bLogado = isLogado($conn);
    if (!$bLogado){
    	session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "02000 - " . constant("ERR_NO_AUTORIZADO");
		echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
		exit;
	}else{
		if (!isCandidatoActivo($conn)){
		  session_start();
		  $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
		  echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
		  exit;
		}
		$_cEntidadCandidatoTK = getCandidatoToken($conn);
    }
?>