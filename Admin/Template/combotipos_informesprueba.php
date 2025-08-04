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
		$sComboProp		= "onchange=\"javascript:cambiaIdTipoInforme()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdTipoInforme";
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
			$sNombreCampo	= $sPrefijo . "IdTipoInforme";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "CodIdiomaIso2"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "codIdiomaIso2=" . $conn->qstr($_POST[$sPrefijo . "CodIdiomaIso2"], false);
		}
		if (isset($_POST[$sPrefijo . "IdPrueba"]) && $_POST[$sPrefijo . "IdPrueba"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "IdPrueba=" . $conn->qstr($_POST[$sPrefijo . "IdPrueba"], false);
		}
		if (isset($_POST[$sPrefijo . "IdTipoInforme"]) && $_POST[$sPrefijo . "IdTipoInforme"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idTipoInforme=" . $conn->qstr($_POST[$sPrefijo . "IdTipoInforme"], false);
		}
		if (empty($sWhere)){
			$sWhere = "usuMod='0'";
		}
		$sSQL = "SELECT * FROM informes_pruebas WHERE " . $sWhere;
		$listaInfomesPruebas = $conn->Execute($sSQL);
		$sIdTipoInforme="";
		while(!$listaInfomesPruebas->EOF){
			$sIdTipoInforme.="," . $listaInfomesPruebas->fields['idTipoInforme']; 	
			$listaInfomesPruebas->MoveNext();
		}
		$sWhere2="";
		if (!empty($sIdTipoInforme)){
			$sIdTipoInforme = substr($sIdTipoInforme, 1);
			$sWhere2="idTipoInforme IN (" . $sIdTipoInforme . ")";
			if (isset($_POST[$sPrefijo . "CodIdiomaIso2"])){
				$sWhere2.=(empty($sWhere2) ? " " : " AND ") . "codIdiomaIso2=" . $conn->qstr($_POST[$sPrefijo . "CodIdiomaIso2"], false);
			}
		}else{
			$sWhere2 = "idTipoInforme='-1'";
		}
		$comboTIPOS_INFORMES= new Combo($conn, $sPrefijo . "IdTipoInforme","idTipoInforme","nombre","descripcion","tipos_informes","",constant("SLC_OPCION"),$sWhere2, "idTipoInforme", "fecMod");

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
		$comboTIPOS_INFORMES->setNombre($sNombreCampo);
		

//inicio de HERRAMIENTAS -->
		$rsTIPOS_INFORMES		= $comboTIPOS_INFORMES->getDatos();
		$sAsIdTIPOS_INFORMES		= $comboTIPOS_INFORMES->getIdKey();
		$sAsTIPOS_INFORMES		= $comboTIPOS_INFORMES->getDescKey();
		$iTIPOS_INFORMES		= $rsTIPOS_INFORMES->RecordCount();
		$sDefaultTIPOS_INFORMES	= $comboTIPOS_INFORMES->getDefault();
		$sTIPOS_INFORMES1 = '';
		$i = 1;
		$rsTIPOS_INFORMES->Move(0); //Posicionamos en el primer registro.
		while (!$rsTIPOS_INFORMES->EOF)
		{
			$sTIPOS_INFORMES1 .= '<p><span><input id="' . $sPrefijo . 'IdTipoInforme' . $i . '" name="' . $sPrefijo . 'IdTipoInforme' . $i . '" type="checkbox" value="' . $rsTIPOS_INFORMES->fields[$sAsIdTIPOS_INFORMES] . '"><label for="' . $sPrefijo . 'IdTipoInforme' . $i . '">' . $rsTIPOS_INFORMES->fields[$sAsTIPOS_INFORMES] . '</label>';
			$sTIPOS_INFORMES1 .= '</span></p>';
			$i++;
			$rsTIPOS_INFORMES->MoveNext();
		}
//FIN de HERRAMIENTAS -->
	
echo $sTIPOS_INFORMES1
//echo $comboTIPOS_INFORMES->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>