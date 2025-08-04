<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	//include_once(constant("DIR_WS_INCLUDE") . 'SeguridadCandidatos.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


include_once ('include/conexion.php');

	//require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");



    $cRespPruebasDB = new Respuestas_pruebasDB($conn);
    $cRespPruebas = new Respuestas_pruebas();

    $sSegundos="";
    $sMinutos="";

    $segundosConsumidos = $_POST['fTiempo']-$_POST['fSegundos'] ;
    $sSegundos=$segundosConsumidos;
    if($sSegundos<60){
    	$sMinutos="0";
    }else{
    	$sMinutos = intval($sSegundos/60);
    	$sSegundos = $sSegundos%60;
    }

//    echo $segundosConsumidos . "<br />";

//    echo "Minutos: " . $sMinutos . "<br />";
//    echo "Segundos: " . $sSegundos . "<br />";
	$cRespPruebas->setIdEmpresa($_POST["fIdEmpresa"]);
	$cRespPruebas->setIdProceso($_POST["fIdProceso"]);
	$cRespPruebas->setIdCandidato($_POST["fIdCandidato"]);
	$cRespPruebas->setIdPrueba($_POST["fIdPrueba"]);
	$cRespPruebas->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);

	$cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);
	$_idcandidato= 0;
	if (!empty($_POST["fIdCandidato"])){
		$_idcandidato=$_POST["fIdCandidato"];
	}
	if($cRespPruebas->getFinalizado() < 1){
		$cRespPruebas->setMinutos_test($sMinutos);
		$cRespPruebas->setSegundos_test($sSegundos);
		$cRespPruebas->setUsuAlta($_idcandidato);
		$cRespPruebas->setUsuMod($_idcandidato);
		$cRespPruebasDB->modificar($cRespPruebas);
	}

	?>
