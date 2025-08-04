<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Baremos_empresas/Baremos_empresasDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_empresas/Baremos_empresas.php");
		
include_once ('include/conexion.php');
	
	
	$cUtilidades	= new Utilidades();
	
	$cBaremosDB	= new BaremosDB($conn);  // Entidad DB
	$cBaremos	= new Baremos();  // Entidad
	
	$cEmpresasDB = new EmpresasDB($conn);
	$cEmpresas = new Empresas();
	
	$cBaremos_empresasDB = new Baremos_empresasDB($conn);
	$cBaremos_empresas = new Baremos_empresas();
	
	$sqlEmpresas = $cEmpresasDB->readLista($cEmpresas);
	$listaEmpresas = $conn->Execute($sqlEmpresas);
	
	$sqlBaremos = $cBaremosDB->readLista($cBaremos);
	$listaBaremos = $conn->Execute($sqlBaremos);
	
	while(!$listaEmpresas->EOF){
		$listaBaremos->Move(0);
		while(!$listaBaremos->EOF){
			$cBaremos_empresas = new Baremos_empresas();
			$cBaremos_empresas->setIdEmpresa($listaEmpresas->fields['idEmpresa']);
			$cBaremos_empresas->setIdPrueba($listaBaremos->fields['idPrueba']);
			$cBaremos_empresas->setIdBaremo($listaBaremos->fields['idBaremo']);
			$cBaremos_empresas->setUsuAlta("0");
			$rsBaremos_empresas = $conn->Execute($cBaremos_empresasDB->readLista($cBaremos_empresas));
			if ($rsBaremos_empresas->recordCount() <= 0){
				$cBaremos_empresasDB->insertar($cBaremos_empresas);
			}
			$listaBaremos->MoveNext();
		}
		$listaEmpresas->MoveNext();
	}
	echo "<br />Finalizado";
?>