<?php 
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
// No es compatible con noback header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

	require_once("../include/Configuracion.php");
    include_once("../include/Idiomas.php");
    require_once("../" . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");            
            
	include_once("../" . constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once("../" . constant("DIR_WS_COM") . "Utilidades.php");
session_start();
	define("ADODB_ASSOC_CASE", 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . "adodb.inc.php");
	require_once("../" . constant("DIR_WS_COM") . "Combo.php");
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	if(isset($_SESSION['_cEntidadUsuarioTK' . constant("NOMBRE_SESSION")])){
	   $_cEntidadUsuarioTK = $_SESSION["_cEntidadUsuarioTK" . constant("NOMBRE_SESSION")];
    }else{
    	$_SESSION['mensaje' . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
        echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . '");</script>';
    }
	if ($_cEntidadUsuarioTK->getIdUsuario() == ""){
		$_SESSION['mensaje' . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
        echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . '");</script>';
		exit;
    }
	ob_start();
		$sValor			= "";
		$sOption			= "";
		$lineas			= "1";
		$sComboProp		= "onchange=\"javascript:top.cambiaprovincias()\"";
		$sNombreCampo	= "fIdProvincia";
		$sWhere			= "";
		$bJoin			= false;
		$sFrame			= "";
		$sBgColor		= "";
		if (isset($_POST["bgColor"])){
			$sBgColor = "background-color:" . $_POST["bgColor"];
		}
		if (isset($_POST["fFrame"])){
			$sFrame = ($_POST["fFrame"] != "_") ? $_POST["fFrame"] : "";
		}else{
			$sFrame= "";
		}
		if (isset($_POST["fNombreCampo"]) && !empty($_POST["fNombreCampo"])){
			$sNombreCampo = $_POST["fNombreCampo"];
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:top." . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST["fIdProvincia"]) && $_POST["fIdProvincia"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProvincia=" . $conn->qstr($_POST["fIdProvincia"], false);
		}
		if (isset($_POST["fDescripcion"]) && $_POST["fDescripcion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descripcion=" . $conn->qstr($_POST["fDescripcion"], false);
		}
		if (isset($_POST["fIdPais"]) && $_POST["fIdPais"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPais=" . $conn->qstr($_POST["fIdPais"], false);
		}
		if (isset($_POST["fDescPais"]) && $_POST["fDescPais"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descPais=" . $conn->qstr($_POST["fDescPais"], false);
		}
		if (isset($_POST["fIdComunidad"]) && $_POST["fIdComunidad"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idComunidad=" . $conn->qstr($_POST["fIdComunidad"], false);
		}
		if (isset($_POST["fDescComunidad"]) && $_POST["fDescComunidad"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descComunidad=" . $conn->qstr($_POST["fDescComunidad"], false);
		}
		if (isset($_POST["fFecAlta"]) && $_POST["fFecAlta"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fecAlta=" . $conn->qstr($_POST["fFecAlta"], false);
		}
		if (isset($_POST["fFecMod"]) && $_POST["fFecMod"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fecMod=" . $conn->qstr($_POST["fFecMod"], false);
		}
		if (isset($_POST["fUsuAlta"]) && $_POST["fUsuAlta"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "usuAlta=" . $conn->qstr($_POST["fUsuAlta"], false);
		}
		if (isset($_POST["fUsuMod"]) && $_POST["fUsuMod"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "usuMod=" . $conn->qstr($_POST["fUsuMod"], false);
		}
		if (empty($sWhere)){
			$sWhere = "idProvincia='0'";
		}
		if ($bJoin){
			//$comboPROVINCIAS= new Combo($conn, "fIdProvincia", "DISTINCT(s.idProvincia)", "s.descripcion" , "descripcion", "provincias s, suscriptorestemasboletins stm","","Seleccione una opción",$sWhere, "idProvincia");
		}else{
			$comboPROVINCIAS= new Combo($conn,"fIdProvincia","idProvincia","descripcion","descripcion","wi_provincias","","Seleccione una opción",$sWhere, "idProvincia");
		}
		if (isset($_POST["nLineas"]) &&  $_POST["nLineas"] > 1)
			$lineas		= $_POST["nLineas"];
		if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
			$sComboProp .= " id='tid-obliga'";
		if (isset($_POST["bBus"]) && $_POST["bBus"]){
			$sNombreCampo="LSTIdProvincia";
			$sValor=explode(",",isset($_POST["LSTIdProvincia"]) ? $_POST["LSTIdProvincia"] : "");
		}else{
			//$sOption		= "<option value='-1'>Todos</option>";
			$sValor=explode(",",isset($_POST["fIdProvincia"]) ? $_POST["fIdProvincia"] : "");
		}
		if (isset($_POST["vSelected"]) && $_POST["vSelected"]){
			$sValor = explode(",",$_POST["vSelected"]);
		}
		if (isset($_POST["multiple"]) && $_POST["multiple"]){
			$sNombreCampo=$sNombreCampo . "[]";
			$sComboProp	.= " multiple";
		}
		if (isset($_POST["fDisabled"]) && !empty($_POST["fDisabled"])){
			$sComboProp	.= " " . $_POST["fDisabled"];
		}
		//Otras propiedades
		//$sComboProp	.= " style='width:100%;'";
		$comboPROVINCIAS->setNombre($sNombreCampo);
    echo $comboPROVINCIAS->getHTMLCombo($lineas, "cajatexto", $sValor, $sComboProp, $sOption);
ob_end_flush();
?>