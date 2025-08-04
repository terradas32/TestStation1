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
	require_once(constant("DIR_WS_COM") . "Combo.php");
	
include_once ('include/conexion.php');
	
//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$sqlToExcel	= new ToXLS($conn);  // Entidad DB
	
//	session_start();
	$sDESCListaExcel = "";
	$aArray =explode(constant("CHAR_SEPARA"), base64_decode($_REQUEST['fSQLtoEXCEL']));
	$sql = $aArray[0];
	$nombre = $aArray[1];
	$pruebas = $aArray[2];
//	echo $sql;exit;
	if (!empty($sql)){
		if (empty($pruebas)){
			echo "(2pr0):: " . constant("ERR");
			exit;
		}
/*
	//------------ Modificamos las descripciones. ---------------------------------------
	$comboSEXOS	= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","","","codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","");
	$rsSEXOS		= $comboSEXOS->getDatos();
	$sAsIdSEXOS	= $comboSEXOS->getIdKey();
	$sAsSEXOS		= $comboSEXOS->getDescKey();
	$iSEXOS		= $rsSEXOS->RecordCount();
	$sDefaultSEXOS	= $comboSEXOS->getDefault();
	$rsSEXOS->Move(0); //Posicionamos en el primer registro.
	while (!$rsSEXOS->EOF)
	{
		$sSQL = "UPDATE export_especial SET ";
		$sSQL .= "descSexo=" . $conn->qstr($rsSEXOS->fields[$sAsSEXOS], false) . " ";
		$sSQL .= "WHERE idSexo=" . $conn->qstr($rsSEXOS->fields[$sAsIdSEXOS], false) . " ";
		$conn->Execute($sSQL);
		$rsSEXOS->MoveNext();
		
	}
	
	$comboEDADES	= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","","","codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","");
	$rsEDADES		= $comboEDADES->getDatos();
	$sAsIdEDADES	= $comboEDADES->getIdKey();
	$sAsEDADES		= $comboEDADES->getDescKey();
	$iEDADES		= $rsEDADES->RecordCount();
	$sDefaultEDADES	= $comboEDADES->getDefault();
	$rsEDADES->Move(0); //Posicionamos en el primer registro.
	while (!$rsEDADES->EOF)
	{
		$sSQL = "UPDATE export_especial SET ";
		$sSQL .= "descEdad=" . $conn->qstr($rsEDADES->fields[$sAsEDADES], false) . " ";
		$sSQL .= "WHERE idEdad=" . $conn->qstr($rsEDADES->fields[$sAsIdEDADES], false) . " ";
		$conn->Execute($sSQL);
		$rsEDADES->MoveNext();
	}
	
	$comboFORMACIONES	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","","","codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","");
	$rsFORMACIONES		= $comboFORMACIONES->getDatos();
	$sAsIdFORMACIONES	= $comboFORMACIONES->getIdKey();
	$sAsFORMACIONES		= $comboFORMACIONES->getDescKey();
	$iFORMACIONES		= $rsFORMACIONES->RecordCount();
	$sDefaultFORMACIONES	= $comboFORMACIONES->getDefault();
	$rsFORMACIONES->Move(0); //Posicionamos en el primer registro.
	while (!$rsFORMACIONES->EOF)
	{
		$sSQL = "UPDATE export_especial SET ";
		$sSQL .= "descFormacion=" . $conn->qstr($rsFORMACIONES->fields[$sAsFORMACIONES], false) . " ";
		$sSQL .= "WHERE idFormacion=" . $conn->qstr($rsFORMACIONES->fields[$sAsIdFORMACIONES], false) . " ";
		$conn->Execute($sSQL);
		$rsFORMACIONES->MoveNext();
	}

	$comboNIVELES	= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","","","codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","");
	$rsNIVELES		= $comboNIVELES->getDatos();
	$sAsIdNIVELES	= $comboNIVELES->getIdKey();
	$sAsNIVELES		= $comboNIVELES->getDescKey();
	$iNIVELES		= $rsNIVELES->RecordCount();
	$sDefaultNIVELES	= $comboNIVELES->getDefault();
	$rsNIVELES->Move(0); //Posicionamos en el primer registro.
	while (!$rsNIVELES->EOF)
	{
		$sSQL = "UPDATE export_especial SET ";
		$sSQL .= "descNivel=" . $conn->qstr($rsNIVELES->fields[$sAsNIVELES], false) . " ";
		$sSQL .= "WHERE idNivel=" . $conn->qstr($rsNIVELES->fields[$sAsIdNIVELES], false) . " ";
		$conn->Execute($sSQL);
		$rsNIVELES->MoveNext();
	}

	$comboAREAS	= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","","","codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","");
	$rsAREAS		= $comboAREAS->getDatos();
	$sAsIdAREAS	= $comboAREAS->getIdKey();
	$sAsAREAS		= $comboAREAS->getDescKey();
	$iAREAS		= $rsAREAS->RecordCount();
	$sDefaultAREAS	= $comboAREAS->getDefault();
	$rsAREAS->Move(0); //Posicionamos en el primer registro.
	while (!$rsAREAS->EOF)
	{
		$sSQL = "UPDATE export_especial SET ";
		$sSQL .= "descArea=" . $conn->qstr($rsAREAS->fields[$sAsAREAS], false) . " ";
		$sSQL .= "WHERE idArea=" . $conn->qstr($rsAREAS->fields[$sAsIdAREAS], false) . " ";
		$conn->Execute($sSQL);
		$rsAREAS->MoveNext();
	}
	//FIN------------ Modificamos las descripciones. ---------------------------------------
*/	
		$sEntidad = ucfirst($nombre);
		//Parte común
		$sDESCListaExcel	= "PARTICIPANTE,APELLIDO1,APELLIDO2,EMAIL,FEC_PRUEBA,FEC_ALTA_PROCESO";
		$PKListaExcel		= "empleado,apellido1,apellido2,email,fecPrueba,fecAltaProceso";
		
		//Parte variable
		$apruebas = explode(",", $pruebas);
//		echo $pruebas;exit;
		$sDESCListaExcel	.= ",PRUEBA,CORRECTAS,CONTESTADAS,PERCENTIL,INDICE RAPIDEZ,INDICE PRECISION,PRODUCTO RENDIMIENTO,ESTILO PROCESAMIENTO MENTAL";
		$PKListaExcel		.= ",prueba,correctas,contestadas,percentil,ir,ip,por,estilo";			

	}else{
		echo constant("ERR");
		exit;
	}
	
	$sql = str_replace("*",$PKListaExcel, $sql);
	$sql .= "ORDER BY email, prueba"; 
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