<?php 
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
// No es compatible con noback header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

	require_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Configuracion.php");
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
    require_once(constant("DIR_FS_DOCUMENT_ROOT")  . constant("DIR_WS_COM") . "Usuarios/Usuarios.php");            
            
	include_once(constant("DIR_FS_DOCUMENT_ROOT")  . constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT")  . constant("DIR_WS_COM") . "Utilidades.php");
session_start();
	define("ADODB_ASSOC_CASE", 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . "adodb.inc.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT")  . constant("DIR_WS_COM") . "Combo.php");
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
		$sComboProp		= "onchange=\"javascript:top.cambiaccaa()\"";
		$sNombreCampo	= "fIdCorreo";
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
		if (isset($_POST["fIdTipoCorreo"]) && $_POST["fIdTipoCorreo"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idTipoCorreo=" . $conn->qstr($_POST["fIdTipoCorreo"], false);
		}
		if (empty($sWhere)){
			$sWhere = "idTipoCorreo='0'";
		}
		if ($bJoin){
			//$comboCCAA= new Combo($conn, "fIdComunidad", "DISTINCT(s.idComunidad)", "s.descripcion" , "descripcion", "ccaa s, suscriptorestemasboletins stm","","Seleccione una opción",$sWhere, "idComunidad");
		}else{
			$comboCORREOS= new Combo($conn,"fIdCorreo","idCorreo","nombre","descripcion","correos","","Seleccione una opción",$sWhere, "idCorreo");
		}
		if (isset($_POST["nLineas"]) &&  $_POST["nLineas"] > 1)
			$lineas		= $_POST["nLineas"];
		if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
			$sComboProp .= " id='tid-obliga'";
		if (isset($_POST["bBus"]) && $_POST["bBus"]){
			$sNombreCampo="LSTIdComunidad";
			$sValor=explode(",",isset($_POST["LSTIdComunidad"]) ? $_POST["LSTIdComunidad"] : "");
		}else{
			//$sOption		= "<option value='-1'>Todos</option>";
			$sValor=explode(",",isset($_POST["fIdComunidad"]) ? $_POST["fIdComunidad"] : "");
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
		$comboCORREOS->setNombre($sNombreCampo);
    echo $comboCORREOS->getHTMLCombo($lineas, "obliga", $sValor, "onchange=\"javascript:cargaplantilla()\"", $sOption);
ob_end_flush();
?>