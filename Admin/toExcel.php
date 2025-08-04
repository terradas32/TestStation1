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
//		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");
//		$cEntidad	= new $sEntidad();  // Entidad
		$sDESCListaExcel	= "ID_EMPLEADO,APELLIDO1,APELLIDO2,EMAIL,FEC_PRUEBA,CORRECTAS_NIPS,CONTESTADAS_NIPS,PERCENTIL_NIPS,CORRECTAS_VIPS,CONTESTADAS_VIPS,PERCENTIL_VIPS,PUNTUACION_INGLES,ID_ENTREVISTADOR_EXTERNO,ID_ENTREVISTADOR_INTERNO,CAND_ASISTIO,ID_NACION,FORZADO";
		$PKListaExcel		= "empleado,apellido1,apellido2,email,fecPrueba,correctas_nips,contestadas_nips,percentil_nips,correctas_vips,contestadas_vips,percentil_vips,puntuacion_elt, '' AS id_entrevistador_externo, '' AS id_entrevistador_interno, '' AS cand_asistio, '' AS id_nacion, '' AS forzado";
	}else{
		echo constant("ERR");
		exit;
	}
	
	$sql = str_replace("*",$PKListaExcel, $sql);
	
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