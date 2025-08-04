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
		$sComboProp		= "onchange=\"javascript:cambiaIdCorreo()\"";
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
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idEmpresa=" . $conn->qstr($_POST[$sPrefijo . "IdEmpresa"], false);
		}
		if (isset($_POST[$sPrefijo . "IdProceso"]) && $_POST[$sPrefijo . "IdProceso"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProceso=" . $conn->qstr($_POST[$sPrefijo . "IdProceso"], false);
		}
//		if (isset($_POST[$sPrefijo . "IdTipoCorreo"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idTipoCorreo=" . $conn->qstr($_POST[$sPrefijo . "IdTipoCorreo"], false);
//		}
		if (isset($_POST[$sPrefijo . "IdCorreo"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idCorreo=" . $conn->qstr($_POST[$sPrefijo . "IdCorreo"], false);
		}
		if (isset($_POST[$sPrefijo . "Nombre"]) && $_POST[$sPrefijo . "Nombre"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "nombre=" . $conn->qstr($_POST[$sPrefijo . "Nombre"], false);
		}
		if (isset($_POST[$sPrefijo . "Asunto"]) && $_POST[$sPrefijo . "Asunto"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "asunto=" . $conn->qstr($_POST[$sPrefijo . "Asunto"], false);
		}
		if (isset($_POST[$sPrefijo . "Cuerpo"]) && $_POST[$sPrefijo . "Cuerpo"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "cuerpo=" . $conn->qstr($_POST[$sPrefijo . "Cuerpo"], false);
		}
		if (isset($_POST[$sPrefijo . "Descripcion"]) && $_POST[$sPrefijo . "Descripcion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descripcion=" . $conn->qstr($_POST[$sPrefijo . "Descripcion"], false);
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
//        echo $sWhere; 
		if ($bJoin){
			//$comboPROCESOS= new Combo($conn, $sPrefijo . "IdProceso", "DISTINCT(s.idProceso)", "s.nombre", "descripcion", "procesos s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idProceso", "fecMod");
		}else{
			$comboPROCESOS= new Combo($conn, $sPrefijo . "IdCorreo","idCorreo","nombre","descripcion","correos_proceso","",constant("SLC_OPCION"),$sWhere, "idTipoCorreo", "fecMod");
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
		$rsCP = $comboPROCESOS->getDatos();
		if ($rsCP->RecordCount() == 1){
//			print_r($rsCP);
			while (!$rsCP->EOF){
				$sValor = $rsCP->fields['idTipoCorreo'];
				$rsCP->MoveNext();
			}
		} 
echo $comboPROCESOS->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>