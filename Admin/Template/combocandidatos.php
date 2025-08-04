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
		$sComboProp		= "onchange=\"javascript:cambiaIdCandidato()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdCandidato";
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
			$sNombreCampo	= $sPrefijo . "IdCandidato";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "IdCandidato"]) && $_POST[$sPrefijo . "IdCandidato"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idCandidato=" . $conn->qstr($_POST[$sPrefijo . "IdCandidato"], false);
		}
		if (isset($_POST[$sPrefijo . "IdEmpresa"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idEmpresa=" . $conn->qstr($_POST[$sPrefijo . "IdEmpresa"], false);
		}
		if (isset($_POST[$sPrefijo . "IdProceso"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProceso=" . $conn->qstr($_POST[$sPrefijo . "IdProceso"], false);
		}
		if (isset($_POST[$sPrefijo . "Nombre"]) && $_POST[$sPrefijo . "Nombre"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "nombre=" . $conn->qstr($_POST[$sPrefijo . "Nombre"], false);
		}
		if (isset($_POST[$sPrefijo . "Apellido1"]) && $_POST[$sPrefijo . "Apellido1"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "apellido1=" . $conn->qstr($_POST[$sPrefijo . "Apellido1"], false);
		}
		if (isset($_POST[$sPrefijo . "Apellido2"]) && $_POST[$sPrefijo . "Apellido2"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "apellido2=" . $conn->qstr($_POST[$sPrefijo . "Apellido2"], false);
		}
		if (isset($_POST[$sPrefijo . "Dni"]) && $_POST[$sPrefijo . "Dni"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "dni=" . $conn->qstr($_POST[$sPrefijo . "Dni"], false);
		}
		if (isset($_POST[$sPrefijo . "Mail"]) && $_POST[$sPrefijo . "Mail"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "mail=" . $conn->qstr($_POST[$sPrefijo . "Mail"], false);
		}
		if (isset($_POST[$sPrefijo . "Password"]) && $_POST[$sPrefijo . "Password"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "password=" . $conn->qstr($_POST[$sPrefijo . "Password"], false);
		}
		if (isset($_POST[$sPrefijo . "IdTratamiento"]) && $_POST[$sPrefijo . "IdTratamiento"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idTratamiento=" . $conn->qstr($_POST[$sPrefijo . "IdTratamiento"], false);
		}
		if (isset($_POST[$sPrefijo . "IdSexo"]) && $_POST[$sPrefijo . "IdSexo"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idSexo=" . $conn->qstr($_POST[$sPrefijo . "IdSexo"], false);
		}
		if (isset($_POST[$sPrefijo . "IdEdad"]) && $_POST[$sPrefijo . "IdEdad"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idEdad=" . $conn->qstr($_POST[$sPrefijo . "IdEdad"], false);
		}
		if (isset($_POST[$sPrefijo . "FechaNacimiento"]) && $_POST[$sPrefijo . "FechaNacimiento"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fechaNacimiento=" . $conn->qstr($_POST[$sPrefijo . "FechaNacimiento"], false);
		}
		if (isset($_POST[$sPrefijo . "IdPais"]) && $_POST[$sPrefijo . "IdPais"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idPais=" . $conn->qstr($_POST[$sPrefijo . "IdPais"], false);
		}
		if (isset($_POST[$sPrefijo . "IdProvincia"]) && $_POST[$sPrefijo . "IdProvincia"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProvincia=" . $conn->qstr($_POST[$sPrefijo . "IdProvincia"], false);
		}
		if (isset($_POST[$sPrefijo . "IdMunicipio"]) && $_POST[$sPrefijo . "IdMunicipio"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idMunicipio=" . $conn->qstr($_POST[$sPrefijo . "IdMunicipio"], false);
		}
		if (isset($_POST[$sPrefijo . "IdZona"]) && $_POST[$sPrefijo . "IdZona"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idZona=" . $conn->qstr($_POST[$sPrefijo . "IdZona"], false);
		}
		if (isset($_POST[$sPrefijo . "Direccion"]) && $_POST[$sPrefijo . "Direccion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "direccion=" . $conn->qstr($_POST[$sPrefijo . "Direccion"], false);
		}
		if (isset($_POST[$sPrefijo . "CodPostal"]) && $_POST[$sPrefijo . "CodPostal"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "codPostal=" . $conn->qstr($_POST[$sPrefijo . "CodPostal"], false);
		}
		if (isset($_POST[$sPrefijo . "IdFormacion"]) && $_POST[$sPrefijo . "IdFormacion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idFormacion=" . $conn->qstr($_POST[$sPrefijo . "IdFormacion"], false);
		}
		if (isset($_POST[$sPrefijo . "IdNivel"]) && $_POST[$sPrefijo . "IdNivel"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idNivel=" . $conn->qstr($_POST[$sPrefijo . "IdNivel"], false);
		}
		if (isset($_POST[$sPrefijo . "IdArea"]) && $_POST[$sPrefijo . "IdArea"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idArea=" . $conn->qstr($_POST[$sPrefijo . "IdArea"], false);
		}
		if (isset($_POST[$sPrefijo . "Telefono"]) && $_POST[$sPrefijo . "Telefono"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "telefono=" . $conn->qstr($_POST[$sPrefijo . "Telefono"], false);
		}
		if (isset($_POST[$sPrefijo . "EstadoCivil"]) && $_POST[$sPrefijo . "EstadoCivil"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "estadoCivil=" . $conn->qstr($_POST[$sPrefijo . "EstadoCivil"], false);
		}
		if (isset($_POST[$sPrefijo . "Nacionalidad"]) && $_POST[$sPrefijo . "Nacionalidad"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "nacionalidad=" . $conn->qstr($_POST[$sPrefijo . "Nacionalidad"], false);
		}
		if (isset($_POST[$sPrefijo . "Informado"]) && $_POST[$sPrefijo . "Informado"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "informado=" . $conn->qstr($_POST[$sPrefijo . "Informado"], false);
		}
		if (isset($_POST[$sPrefijo . "Finalizado"]) && $_POST[$sPrefijo . "Finalizado"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "finalizado=" . $conn->qstr($_POST[$sPrefijo . "Finalizado"], false);
		}
		if (isset($_POST[$sPrefijo . "FechaFinalizado"]) && $_POST[$sPrefijo . "FechaFinalizado"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fechaFinalizado=" . $conn->qstr($_POST[$sPrefijo . "FechaFinalizado"], false);
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
		if (isset($_POST[$sPrefijo . "UltimoLogin"]) && $_POST[$sPrefijo . "UltimoLogin"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "ultimoLogin=" . $conn->qstr($_POST[$sPrefijo . "UltimoLogin"], false);
		}
		if (isset($_POST[$sPrefijo . "Token"]) && $_POST[$sPrefijo . "Token"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "token=" . $conn->qstr($_POST[$sPrefijo . "Token"], false);
		}
		if (isset($_POST[$sPrefijo . "UltimaAcc"]) && $_POST[$sPrefijo . "UltimaAcc"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "ultimaAcc=" . $conn->qstr($_POST[$sPrefijo . "UltimaAcc"], false);
		}
		if (empty($sWhere)){
			$sWhere = "ultimaAcc='0'";
		}
		if ($bJoin){
			//$comboCANDIDATOS= new Combo($conn, $sPrefijo . "IdCandidato", "DISTINCT(s.idCandidato)", "s.nombre", "descripcion", "candidatos s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idCandidato", "nombre,apellido1,apellido2,mail");
		}else{
			$comboCANDIDATOS= new Combo($conn, $sPrefijo . "IdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"descripcion","candidatos","",constant("SLC_OPCION"),$sWhere, "idCandidato", "nombre,apellido1,apellido2,mail");
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
		$comboCANDIDATOS->setNombre($sNombreCampo);
echo $comboCANDIDATOS->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);
?>