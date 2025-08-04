<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	
    $bLogado = isLogado($conn);
	$sSQLPruebaIN = "";
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
		require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
        $_cEntidadUsuarioTK = getUsuarioToken($conn);
        $_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
        
		if (!empty($_EmpresaLogada)){
			$cEmpresaPadre->setIdEmpresa($_EmpresaLogada);
			$cEmpresaPadre = $cEmpresaPadreDB->readEntidad($cEmpresaPadre);
			$sSQLPruebaIN = $cEmpresaPadre->getIdsPruebas();
			if (!empty($sSQLPruebaIN)){
				//chequeamos si el primer caracter es una coma
				if (substr($sSQLPruebaIN, 0, 1) == ","){
					$sSQLPruebaIN = substr($sSQLPruebaIN, 1);
				}
				$sSQLPruebaIN = " AND bajaLog=0 AND idPrueba IN (" . $sSQLPruebaIN . ") ";
			}
		}
		if (empty($sSQLPruebaIN)){
			$sSQLPruebaIN = " AND bajaLog=0 ";
		}
    }
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	
		$sValor			= "";
		$sOption			= "";
		$lineas			= "1";
		$sComboProp		= "onchange=\"javascript:cambiaIdPrueba()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdPrueba";
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
			$sNombreCampo	= $sPrefijo . "IdPrueba";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "CodIdiomaIso2"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "codIdiomaIso2=" . $conn->qstr($_POST[$sPrefijo . "CodIdiomaIso2"], false);
		}
		if (isset($_POST[$sPrefijo . "IdPrueba"]) && $_POST[$sPrefijo . "IdPrueba"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPrueba=" . $conn->qstr($_POST[$sPrefijo . "IdPrueba"], false);
		}
		if (isset($_POST[$sPrefijo . "Codigo"]) && $_POST[$sPrefijo . "Codigo"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "codigo=" . $conn->qstr($_POST[$sPrefijo . "Codigo"], false);
		}
		if (isset($_POST[$sPrefijo . "Nombre"]) && $_POST[$sPrefijo . "Nombre"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "nombre=" . $conn->qstr($_POST[$sPrefijo . "Nombre"], false);
		}
		if (isset($_POST[$sPrefijo . "Descripcion"]) && $_POST[$sPrefijo . "Descripcion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descripcion=" . $conn->qstr($_POST[$sPrefijo . "Descripcion"], false);
		}
		if (isset($_POST[$sPrefijo . "IdTipoPrueba"]) && $_POST[$sPrefijo . "IdTipoPrueba"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idTipoPrueba=" . $conn->qstr($_POST[$sPrefijo . "IdTipoPrueba"], false);
		}
		if (isset($_POST[$sPrefijo . "Observaciones"]) && $_POST[$sPrefijo . "Observaciones"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "observaciones=" . $conn->qstr($_POST[$sPrefijo . "Observaciones"], false);
		}
		if (isset($_POST[$sPrefijo . "Duracion"]) && $_POST[$sPrefijo . "Duracion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "duracion=" . $conn->qstr($_POST[$sPrefijo . "Duracion"], false);
		}
		if (isset($_POST[$sPrefijo . "LogoPrueba"]) && $_POST[$sPrefijo . "LogoPrueba"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "logoPrueba=" . $conn->qstr($_POST[$sPrefijo . "LogoPrueba"], false);
		}
		if (isset($_POST[$sPrefijo . "CapturaPantalla"]) && $_POST[$sPrefijo . "CapturaPantalla"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "capturaPantalla=" . $conn->qstr($_POST[$sPrefijo . "CapturaPantalla"], false);
		}
		if (isset($_POST[$sPrefijo . "Cabecera"]) && $_POST[$sPrefijo . "Cabecera"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "cabecera=" . $conn->qstr($_POST[$sPrefijo . "Cabecera"], false);
		}
		if (isset($_POST[$sPrefijo . "BajaLog"]) && $_POST[$sPrefijo . "BajaLog"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "bajaLog=" . $conn->qstr($_POST[$sPrefijo . "BajaLog"], false);
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
		$sWhere .=$sSQLPruebaIN;
//		echo $sWhere;
		if ($bJoin){
			//$comboPRUEBAS= new Combo($conn, $sPrefijo . "IdPrueba", "DISTINCT(s.idPrueba)", "s.nombre", "descripcion", "pruebas s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idPrueba", "fecMod");
		}else{
			$comboPRUEBAS= new Combo($conn, $sPrefijo . "IdPrueba","idPrueba","nombre","descripcion","pruebas","",constant("SLC_OPCION"),$sWhere, "idPrueba", "fecMod");
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
		$comboPRUEBAS->setNombre($sNombreCampo);
echo $comboPRUEBAS->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>