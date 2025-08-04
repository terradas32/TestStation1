<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	
    $bLogado = isLogado($conn);
    if(isset($_SESSION['mensaje' . constant("NOMBRE_SESSION")])){
		unset($_SESSION['mensaje' . constant("NOMBRE_SESSION")]);
		$_SESSION['mensaje' . constant("NOMBRE_SESSION")]= "";
	}
    if (!$bLogado){
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");;
		header("Location: " . constant("HTTP_SERVER"));
		exit;
	}else{
		if (!isUsuarioActivo($conn))
		{
		  $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
		  header("Location: " . constant("HTTP_SERVER"));
		  exit;
		}
		//Recogemos los Menus
		$sMenus = getMenus($conn);
		if (empty($sMenus)){
		  $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
		  header("Location: " . constant("HTTP_SERVER"));
		  exit;
        }
        $_cEntidadUsuarioTK = getUsuarioToken($conn);
        if(isset($_SESSION['_cEntidadUsuarioTK' . constant("NOMBRE_SESSION")])){
    		unset($_SESSION['_cEntidadUsuarioTK' . constant("NOMBRE_SESSION")]);
    		$_SESSION['_cEntidadUsuarioTK' . constant("NOMBRE_SESSION")]= "";
    		$_SESSION["_cEntidadUsuarioTK" . constant("NOMBRE_SESSION")]= $_cEntidadUsuarioTK;
    	}else{
            $_SESSION['_cEntidadUsuarioTK' . constant("NOMBRE_SESSION")]= "";
    		$_SESSION["_cEntidadUsuarioTK" . constant("NOMBRE_SESSION")]= $_cEntidadUsuarioTK;
        }
    }
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	
	$sValor			= "";
	$sOption			= "";
	$lineas			= "1";
	$sComboProp		= "onchange=\"javascript:top.cambiadespuesde()\"";
	$sPrefijo		= "f";
	$sNombreCampo	= $sPrefijo . "DespuesDe";
	$sWhere			= "";
	$bJoin			= false;
	$sFrame			= "";
	$sBgColor		= "";
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
		$sFrame= "contenido.";
	}
	if (isset($_POST["fNombreCampo"]) && !empty($_POST["fNombreCampo"])){
		$sNombreCampo = $_POST["fNombreCampo"];
	}
	if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
		$sComboProp		= "onchange=\"javascript:top." . $sFrame . $_POST["fJSProp"] . "()\"";
	}
	if (isset($_POST["fDentroDe"])){
		$cEntidad->setOrden($_POST["fDentroDe"]);
		$cEntidad = $cEntidadDB->readEntidadOrden($cEntidad);
		$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPadre=" . $conn->qstr($cEntidad->getIdFuncionalidad(), false);
	}
	if (empty($sWhere)){
		$sWhere = "indentacion=0";
	}
	if ($bJoin){
		//$comboDESPUES_DE= new Combo($conn, "fDespuesDe", "DISTINCT(s.idCentroTrabajo)", "s.descripcion" , "descripcion", "funcionalidades s, suscriptorestemasboletins stm","","Seleccione una opción",$sWhere, "idCentroTrabajo");
	}else{
		$comboDESPUES_DE	= new Combo($conn, "fDespuesDe", "orden","nombre","Descripcion","wi_funcionalidades","",constant("STR_PRIMERO"),$sWhere, "","orden");
	}
	if (isset($_POST["nLineas"]) && $_POST["nLineas"] > 1)
		$lineas		= $_POST["nLineas"];
	if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
		$sComboProp .= " id='tid-obliga'";
	if (isset($_POST["bBus"]) && $_POST["bBus"]){
		$sNombreCampo="LSTDespuesDe";
		$sValor=explode(",",$_POST["LSTDespuesDe"]);
	}else{
		//$sOption		= "<option value='-1'>Todos</option>";
		$sValor=(!empty($_POST["fDespuesDe"])) ? explode(",",$_POST["fDespuesDe"]) : array();
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
    echo $comboDESPUES_DE->getHTMLCombo($lineas, "cajatexto", $sValor, $sComboProp, $sOption);
?>
