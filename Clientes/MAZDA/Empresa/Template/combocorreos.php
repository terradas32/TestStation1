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
		$sObliga        = "cajatexto";
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
		}else{
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idTipoCorreo=" . $conn->qstr("-1", false);
		}
		if (isset($_POST["fIdEmpresa"]) && $_POST["fIdEmpresa"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "(idEmpresa=" . $conn->qstr($_POST["fIdEmpresa"], false);
			//$sWhere.= " OR idEmpresa IS NULL OR idEmpresa='0')";
      $sWhere.= " )";
		}
//		echo $sWhere;
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
			$sObliga= "obliga";
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
    echo $comboCORREOS->getHTMLCombo($lineas, $sObliga, $sValor, "onchange=\"javascript:cargaplantilla()\"", $sOption);

?>
