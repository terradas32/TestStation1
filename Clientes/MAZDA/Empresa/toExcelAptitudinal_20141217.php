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
	$pruebas = $aArray[2];
	//echo $sql;exit;
	if (!empty($sql)){
		if (empty($pruebas)){
			echo "(2pr0):: " . constant("ERR");
			exit;
		}
		$sEntidad = ucfirst($nombre);
		//Parte común
		$sDESCListaExcel	= "PARTICIPANTE,APELLIDO1,APELLIDO2,EMAIL,FEC_PRUEBA,FEC_ALTA_PROCESO";
		$PKListaExcel		= "empleado,apellido1,apellido2,email,fecPrueba,fecAltaProceso";
		
		//Parte variable
		$apruebas = explode(",", $pruebas);
		if (in_array("16", $apruebas)){
			//NIPS
			$sDESCListaExcel	.= ",CORRECTAS_NIPS,CONTESTADAS_NIPS,PERCENTIL_NIPS";
			$PKListaExcel		.= ",correctas_nips,contestadas_nips,percentil_nips";			
		}
		if (in_array("20", $apruebas)){
			//TIC
			$sDESCListaExcel	.= ",CORRECTAS_TIC,CONTESTADAS_TIC,PERCENTIL_TIC";
			$PKListaExcel		.= ",correctas_tic,contestadas_tic,percentil_tic";
		}
		if (in_array("21", $apruebas)){
			//TAC
			$sDESCListaExcel	.= ",CORRECTAS_TAC,CONTESTADAS_TAC,PERCENTIL_TAC";
			$PKListaExcel		.= ",correctas_tac,contestadas_tac,percentil_tac";
		}
		if (in_array("26", $apruebas)){
			//VIPS
			$sDESCListaExcel	.= ",CORRECTAS_VIPS,CONTESTADAS_VIPS,PERCENTIL_VIPS";
			$PKListaExcel		.= ",correctas_vips,contestadas_vips,percentil_vips";
		}
		if (in_array("28", $apruebas)){
			//EN1
			$sDESCListaExcel	.= ",CORRECTAS_EN1,CONTESTADAS_EN1,PERCENTIL_EN1";
			$PKListaExcel		.= ",correctas_en1,contestadas_en1,percentil_en1";
		}
		if (in_array("29", $apruebas)){
			//VN1
			$sDESCListaExcel	.= ",CORRECTAS_VN1,CONTESTADAS_VN1,PERCENTIL_VN1";
			$PKListaExcel		.= ",correctas_vn1,contestadas_vn1,percentil_vn1";
		}
		if (in_array("30", $apruebas)){
			//NN1
			$sDESCListaExcel	.= ",CORRECTAS_NN1,CONTESTADAS_NN1,PERCENTIL_NN1";
			$PKListaExcel		.= ",correctas_nn1,contestadas_nn1,percentil_nn1";
		}
		if (in_array("31", $apruebas)){
			//DN1
			$sDESCListaExcel	.= ",CORRECTAS_DN1,CONTESTADAS_DN1,PERCENTIL_DN1";
			$PKListaExcel		.= ",correctas_dn1,contestadas_dn1,percentil_dn1";
		}		
		if (in_array("40", $apruebas)){
			//DIPS
			$sDESCListaExcel	.= ",CORRECTAS_DIPS,CONTESTADAS_DIPS,PERCENTIL_DIPS";
			$PKListaExcel		.= ",correctas_dips,contestadas_dips,percentil_dips";
		}
		if (in_array("8", $apruebas)){
			//ELT
			$sDESCListaExcel	.= ",PUNTUACION_INGLES";
			$PKListaExcel		.= ",puntuacion_elt";
		}
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