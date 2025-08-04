<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	
    $bLogado = isLogado($conn);
    if (!$bLogado){
    	session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");;
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}else{
		if (!isUsuarioActivo($conn)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
		}
		//Recogemos los Menus
		$sMenus = getMenus($conn);
		if (empty($sMenus)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
        }
        $_cEntidadUsuarioTK = getUsuarioToken($conn);
    }
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	
		$sValor			= "";
		$sOption			= "";
		$lineas			= "1";
		$sComboProp		= "onchange=\"javascript:cambiaIdIp()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdIp";
		$sWhere			= "";
		$bJoin			= false;
		$sFrame			= "";
		$sBgColor		= "";
		$sObliga			= "cajatexto";
		if (isset($_POST["bBus"]) && $_POST["bBus"]){
			$sPrefijo = "LST";
		}else{
			$sPrefijo = "f";
		}
		if (isset($_POST["bgColor"]) && !empty($_POST["bgColor"])){
			$sBgColor = "background-color:" . $_POST["bgColor"] . ";";
		}
		if (isset($_POST["fFrame"]) && !empty($_POST["fFrame"])){
			$sFrame = ($_POST["fFrame"] != "_") ? $_POST["fFrame"] : "";
		}else{
			$sFrame= "";
		}
		if (isset($_POST["fNombreCampo"]) && !empty($_POST["fNombreCampo"])){
			$sNombreCampo = $_POST["fNombreCampo"];
		}else{
			$sNombreCampo	= $sPrefijo . "IdIp";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "IdRangoIp"]) && $_POST[$sPrefijo . "IdRangoIp"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idRangoIp=" . $conn->qstr($_POST[$sPrefijo . "IdRangoIp"], false);
		}
		if (isset($_POST[$sPrefijo . "IdRangoIr"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idRangoIr=" . $conn->qstr($_POST[$sPrefijo . "IdRangoIr"], false);
		}
		if (isset($_POST[$sPrefijo . "RangoSup"]) && $_POST[$sPrefijo . "RangoSup"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "rangoSup=" . $conn->qstr($_POST[$sPrefijo . "RangoSup"], false);
		}
		if (isset($_POST[$sPrefijo . "RangoInf"]) && $_POST[$sPrefijo . "RangoInf"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "rangoInf=" . $conn->qstr($_POST[$sPrefijo . "RangoInf"], false);
		}
		if (isset($_POST[$sPrefijo . "FecAlta"]) && $_POST[$sPrefijo . "FecAlta"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fecAlta=" . $conn->qstr($_POST[$sPrefijo . "FecAlta"], false);
		}
		if (isset($_POST[$sPrefijo . "FecMod"]) && $_POST[$sPrefijo . "FecMod"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fecMod=" . $conn->qstr($_POST[$sPrefijo . "FecMod"], false);
		}
		if (isset($_POST[$sPrefijo . "UsuAlta"]) && $_POST[$sPrefijo . "UsuAlta"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "usuAlta=" . $conn->qstr($_POST[$sPrefijo . "UsuAlta"], false);
		}
		if (isset($_POST[$sPrefijo . "UsuMod"]) && $_POST[$sPrefijo . "UsuMod"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "usuMod=" . $conn->qstr($_POST[$sPrefijo . "UsuMod"], false);
		}
		if (empty($sWhere)){
			$sWhere = "usuMod='0'";
		}
		if ($bJoin){
			//$comboRANGOS_IP= new Combo($conn, $sPrefijo . "IdIp", "DISTINCT(s.idRangoIp)", "s.rangoSup", "descripcion", "rangos_ip s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idIp", "fecMod");
		}else{
			$comboRANGOS_IP= new Combo($conn, $sPrefijo . "IdIp","idRangoIp",$conn->Concat("rangoSup" , '" ---- "' , "rangoInf"),"descripcion","rangos_ip","",constant("SLC_OPCION"),$sWhere, "idIp", "fecMod");
		}
		if (isset($_POST["nLineas"]) &&  $_POST["nLineas"] > 1)
			$lineas		= $_POST["nLineas"];
		if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
			$sObliga= "obliga";
		if (isset($_POST["bBus"]) && $_POST["bBus"]){
			$sValor=explode(",",isset($_POST[$sNombreCampo]) ? $_POST[$sNombreCampo] : "");
		}else{
			//$sOption		= "<option value='-1'>Todos</option>";
			$sValor=explode(",",isset($_POST[$sNombreCampo]) ? $_POST[$sNombreCampo] : "");
		}
		if (isset($_POST["vSelected"])){
			$sValor = explode(",",$_POST["vSelected"]);
		}
		if (isset($_POST["multiple"]) && $_POST["multiple"]){
			//$sNombreCampo=$sNombreCampo . "[]";
			$sComboProp	.= " multiple=\"multiple\" ";
		}
		if (isset($_POST["fDisabled"]) && !empty($_POST["fDisabled"])){
			$sComboProp	.= " " . $_POST["fDisabled"];
		}
		//Otras propiedades
		//$sComboProp	.= " style=\"" . $sBgColor . "\"";
		$comboRANGOS_IP->setNombre($sNombreCampo);
echo $comboRANGOS_IP->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>