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
        echo '<script language="javascript" type="text/javascript">top.location.replace("' . constant("HTTP_SERVER") . '");</script>';
    }
	if ($_cEntidadUsuarioTK->getIdUsuario() == ""){
		$_SESSION['mensaje' . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
        echo '<script language="javascript" type="text/javascript">top.location.replace("' . constant("HTTP_SERVER") . '");</script>';
		exit;
    }
	
	$cEntidadDB	= new FuncionalidadesDB($conn);  // Entidad DB
	$cEntidad	= new Funcionalidades();  // Entidad
	
	ob_start();
		$sValor			= "";
		$sOption			= "";
		$lineas			= "1";
		$sComboProp		= "onchange=\"javascript:top.cambiadespuesde()\"";
		$sNombreCampo	= "fDespuesDe";
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
			$sFrame= "contenido.";
		}
		if (isset($_POST["fNombreCampo"]) && !empty($_POST["fNombreCampo"])){
			$sNombreCampo = $_POST["fNombreCampo"];
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:top." . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST["fDentroDe"]) && $_POST["fDentroDe"] != ""){
			$cEntidad->setOrden($_POST["fDentroDe"]);
			$cEntidad = $cEntidadDB->readEntidadOrden($cEntidad);
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPadre=" . $conn->qstr($cEntidad->getIdFuncionalidad(), false);
		}
		if (empty($sWhere)){
			$sWhere = "orden=-1";
		}
		if ($bJoin){
			//$comboDESPUESDE= new Combo($conn, "fDespuesDe", "DISTINCT(s.idCentroTrabajo)", "s.descripcion" , "descripcion", "funcionalidades s, suscriptorestemasboletins stm","","Seleccione una opción",$sWhere, "idCentroTrabajo");
		}else{
			$comboDESPUESDE= new Combo($conn,"fDespuesDe","orden","nombre","nombre","wi_funcionalidades","","El primero",$sWhere, "orden");
		}
		if (isset($_POST["nLineas"]) &&  $_POST["nLineas"] > 1)
			$lineas		= $_POST["nLineas"];
		if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
			$sComboProp .= " id='tid-obliga'";
		if ($_POST["bBus"]){
			$sNombreCampo="LSTIdCentroTrabajo";
			$sValor=explode(",",$_POST["LSTIdCentroTrabajo"]);
		}else{
			//$sOption		= "<option value='-1'>Todos</option>";
			$sValor=explode(",",$_POST["fDespuesDe"]);
		}
		if ($_POST["vSelected"]){
			$sValor = explode(",",$_POST["vSelected"]);
		}
		if ($_POST["multiple"]){
			$sNombreCampo=$sNombreCampo . "[]";
			$sComboProp	.= " multiple";
		}
		if (isset($_POST["fDisabled"]) && !empty($_POST["fDisabled"])){
			$sComboProp	.= " " . $_POST["fDisabled"];
		}
		//Otras propiedades
		//$sComboProp	.= " style='width:100%;'";
		$comboDESPUESDE->setNombre($sNombreCampo);
    echo $comboDESPUESDE->getHTMLCombo($lineas, "cajaTexto", $sValor, $sComboProp, $sOption);
ob_end_flush();
?>