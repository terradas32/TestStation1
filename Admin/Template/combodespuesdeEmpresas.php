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
    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	$cEntidadEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
	$cEntidadEmpresas	= new Empresas();  // Entidad
	
	$sValor			= "";
	$sOption			= "";
	$lineas			= "1";
	$sComboProp		= "onchange=\"javascript:cambiadespuesde()\"";
	$sPrefijo		= "f";
	$sNombreCampo	= $sPrefijo . "DespuesDe";
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
		$sBgColor = "background-color:" . $_POST["bgColor"];
	}
	if (isset($_POST["fFrame"]) && !empty($_POST["fFrame"])){
		$sFrame = ($_POST["fFrame"] != "_") ? $_POST["fFrame"] : "";
	}else{
		$sFrame= "";
	}
	if (isset($_POST["fNombreCampo"]) && !empty($_POST["fNombreCampo"])){
		$sNombreCampo = $_POST["fNombreCampo"];
	}
	if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
		$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
	}
	if (isset($_POST[$sPrefijo . "DentroDe"]) && $_POST[$sPrefijo . "DentroDe"] != ""){
		$cEntidadEmpresas->setOrden($_POST[$sPrefijo . "DentroDe"]);
		$cEntidadEmpresas = $cEntidadEmpresasDB->readEntidadOrden($cEntidadEmpresas);
		$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPadre=" . $conn->qstr($cEntidadEmpresas->getIdEmpresa(), false);
	}
	if (empty($sWhere)){
		$sWhere = "indentacion=0";
	}
	
	if ($bJoin){
		//$comboDESPUES_DE= new Combo($conn, "fDespuesDe", "DISTINCT(s.idCentroTrabajo)", "s.descripcion" , "descripcion", "empresas s, suscriptorestemasboletins stm","","Seleccione una opción",$sWhere, "idCentroTrabajo");
	}else{
		$comboDESPUES_DE	= new Combo($conn, "fDespuesDe", "orden", "nombre", "Descripcion", "empresas","",constant("STR_PRIMERO"),$sWhere, "","orden");
	}
	if (isset($_POST["nLineas"]) &&  $_POST["nLineas"] > 1)
		$lineas		= $_POST["nLineas"];
	if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
		$sObliga= "obliga";
	if (isset($_POST["bBus"]) && $_POST["bBus"]){
		$sNombreCampo=$sPrefijo . "DespuesDe";
		$sValor=explode(",",isset($_POST[$sPrefijo . "DespuesDe"]) ? $_POST[$sPrefijo . "DespuesDe"] : "");
	}else{
		//$sOption		= "<option value='-1'>Todos</option>";
		$sValor=explode(",",isset($_POST[$sPrefijo . "DespuesDe"]) ? $_POST[$sPrefijo . "DespuesDe"] : "");
	}
	if (isset($_POST["vSelected"])){
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
	$comboDESPUES_DE->setNombre($sNombreCampo);
    echo $comboDESPUES_DE->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>
