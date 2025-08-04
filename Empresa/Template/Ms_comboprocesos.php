<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	
		$sValor			= "";
		$sOption			= "";
		$lineas			= "1";
		$sComboProp		= "onchange=\"javascript:cambiaIdProceso()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdProceso";
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
			$sNombreCampo	= $sPrefijo . "IdProceso";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "IdEmpresa"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "empresa=" . $conn->qstr($_POST[$sPrefijo . "IdEmpresa"], false);
		}
		if (empty($sWhere)){
			$sWhere = "id='0'";
		}
//		echo $sWhere;
		if ($bJoin){
			//$comboPROCESOS= new Combo($conn, $sPrefijo . "IdProceso", "DISTINCT(s.idProceso)", "s.nombre", "descripcion", "procesos s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idProceso", "fecMod");
		}else{
			$comboPROCESOS= new Combo($conn, $sPrefijo . "IdProceso","id","nombre","Descripcion","procesos","",constant("SLC_OPCION"),$sWhere,"","");
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
		$comboPROCESOS->setNombre($sNombreCampo);
echo $comboPROCESOS->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>