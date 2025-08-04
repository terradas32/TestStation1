<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "ToXLS.php");
	
include_once ('include/conexion.php');
	
//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$sqlToExcel	= new ToXLS($conn);  // Entidad DB
	
//	session_start();
	$sDESCListaExcel = "";
	$aArray =explode(constant("CHAR_SEPARA"), base64_decode($_REQUEST['fSQLtoEXCEL']));
	$sql = $aArray[0];
	$nombre = $aArray[1];
	if (!empty($sql)){
		$sEntidad = ucfirst($nombre);
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");
		$cEntidad	= new $sEntidad();  // Entidad
		$sDESCListaExcel = $cEntidad->getDESCListaExcel();
	}else{
		echo constant("ERR");
		exit;
	}
	
	$sql = str_replace("*",$cEntidad->getPKListaExcel(), $sql);
	
	if (empty($nombre)){
		$nombre = "ExcelFile";
	}
	if (empty($_REQUEST['fPintaCabecera'])){
		$_REQUEST['fPintaCabecera'] = true;
	}
	if (empty($_REQUEST['fSepararCabecera'])){
		$_REQUEST['fSepararCabecera'] = false;
	}

	$sqlToExcel->crearXLS(	$sql, $nombre,
							$_REQUEST['fPintaCabecera'], $_REQUEST['fSepararCabecera'], $sDESCListaExcel);
?>