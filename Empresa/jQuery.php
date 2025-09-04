<?php

header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
require_once('./include/Configuracion.php');
include_once('include/Idiomas.php');
include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
require_once(constant("DIR_WS_COM") . "Utilidades.php");
define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
require_once(constant("DIR_WS_COM") . "Combo.php");

include_once('include/conexion.php');

//require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

$cUtilidades	= new Utilidades();

$sCol1 = '';
$sCol2 = '';

$comboUSUARIOS	= new Combo($conn, "fUsuAlta", "idUsuario", "login", "Descripcion", "wi_usuarios", "", constant("SLC_OPCION"), "");

$bLogado = isLogado($conn);

if (!$bLogado) {
	session_start();
	$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "06900 - " . constant("ERR_NO_AUTORIZADO");
	header("Location: " . constant("HTTP_SERVER") . "msg.php");
	exit;
} else {
	if (!isUsuarioActivo($conn)) {
		session_start();
		$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	//Recogemos los Menus
	$sMenus = getMenus($conn);
	if (empty($sMenus)) {
		session_start();
		$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "06920 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
}
if (empty($_POST['sPG'])) {
	session_start();
	$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_ADMINISTRADOR");
	header("Location: " . constant("HTTP_SERVER") . "msg.php");
	exit;
}
if (file_exists(constant("DIR_FS_DOCUMENT_ROOT") . "Template/" . $_POST["sPG"] . ".php")) {

	// In company report request the tests must be filtered just by company or by company and process (Jairo López - Xeridia S.L.)

	// Filtering test by company or by company and process on report zip generation
	if ((isset($_POST['companyProcessTests']) && $_POST['companyProcessTests'] == 1) || isset($_POST['companyTests'])) {
		extract($_POST);

		// Creating DB connection
		$connection = mysqli_connect(DB_HOST, DB_USUARIO, DB_PASSWORD, DB_DATOS);

		// Avoiding SQL injection
		if (isset($_POST['LSTIdEmpresa'])) $LSTIdEmpresa = mysqli_real_escape_string($connection, $LSTIdEmpresa);
		if (isset($_POST['LSTIdProceso'])) $LSTIdProceso = mysqli_real_escape_string($connection, $LSTIdProceso);
		if (isset($_POST['fLang'])) $fLang = mysqli_real_escape_string($connection, $fLang);

		// Creating SQL Sentence
		$querySentence[0] = "Select distinct pruebas.idPrueba, pruebas.nombre, pruebas.descripcion from proceso_pruebas inner join pruebas on pruebas.idPrueba = proceso_pruebas.idPrueba && pruebas.codIdiomaIso2 = proceso_pruebas.codIdiomaIso2 where proceso_pruebas.idEmpresa = '" . $LSTIdEmpresa;
		$querySentence[1] = "";

		// Searching by process?
		if (isset($companyProcessTests) && $companyProcessTests == 1 && $LSTIdProceso != "") {
			$querySentence[1] = "' && proceso_pruebas.idProceso = '" . $LSTIdProceso;
		}

		$querySentence[2] = "' && proceso_pruebas.codIdiomaIso2 = '" . $fLang . "' && pruebas.bajalog = 0 order by pruebas.nombre asc";
		$consulta = mysqli_query($connection, implode("", $querySentence));

		// Creating combo
		$combo = '<select id="LSTIdPrueba" name="LSTIdPrueba" size="1" class="cajatexto" onchange="javascript:cambiaIdPrueba()">';
		$combo .= '<option style="color:#000000;" value="">Seleccione una opción </option>';
		while ($resultado = mysqli_fetch_array($consulta)) {
			$combo .= '<option style="color:#000000;" value="' . $resultado['idPrueba'] . '">' . $resultado['nombre'] . (strlen($resultado['descripcion']) > 0 ? ' - ' . $resultado['descripcion'] : '') . '</option>';
		}
		$combo .= '</select>';

		// Printing combo and closing connection
		echo $combo;
		mysqli_close($connection);

	} else {
		include_once("Template/" . $_POST["sPG"] . ".php");
	}
} else {
	session_start();
	$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR");
	header("Location: " . constant("HTTP_SERVER") . "msg.php");
	exit;
}
