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
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
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
		$sComboProp		= "onchange=\"javascript:cambiaIdProvincia()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdProvincia";
		$sWhere			= "";
		$bJoin			= false;
		$sFrame			= "";
		$sBgColor		= "";
		$sObliga        = "cajatexto";
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
			$sNombreCampo	= $sPrefijo . "IdProvincia";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "IdProvincia"]) && $_POST[$sPrefijo . "IdProvincia"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProvincia=" . $conn->qstr($_POST[$sPrefijo . "IdProvincia"], false);
		}
		if (isset($_POST[$sPrefijo . "CodIdiomaIso2"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "codIdiomaIso2=" . $conn->qstr($_POST[$sPrefijo . "CodIdiomaIso2"], false);
		}
		if (isset($_POST[$sPrefijo . "Descripcion"]) && $_POST[$sPrefijo . "Descripcion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descripcion=" . $conn->qstr($_POST[$sPrefijo . "Descripcion"], false);
		}
		if (isset($_POST[$sPrefijo . "IdPais"]) && $_POST[$sPrefijo . "IdPais"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPais=" . $conn->qstr($_POST[$sPrefijo . "IdPais"], false);
		}
		if (isset($_POST[$sPrefijo . "IdComunidad"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idComunidad=" . $conn->qstr($_POST[$sPrefijo . "IdComunidad"], false);
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
			//$comboWI_PROVINCIAS= new Combo($conn, $sPrefijo . "IdProvincia", "DISTINCT(s.idProvincia)", "s.descripcion", "descripcion", "wi_provincias s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idProvincia", "fecMod");
		}else{
			$comboWI_PROVINCIAS= new Combo($conn, $sPrefijo . "IdProvincia","idProvincia","descripcion","descripcion","wi_provincias","",constant("SLC_OPCION"),$sWhere, "idProvincia", "fecMod");
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
		//		$sComboProp	.= " style=\"" . $sBgColor . "\"";
		$comboWI_PROVINCIAS->setNombre($sNombreCampo);
echo $comboWI_PROVINCIAS->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>