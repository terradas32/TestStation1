<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once('./include/Configuracion.php');
require_once('./include/Seguridad.php');
include_once('include/Idiomas.php');
define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
require_once(constant("DIR_WS_COM") . "ToXLS.php");
include_once ('include/conexion.php');
	
$sqlToExcel	= new ToXLS($conn);  // Entidad DB
$sDESCListaExcel = "";

// --- NUEVO: flujo cifrado+firmado si viene 'sig' ---
if (isset($_REQUEST['signature']) && isset($_REQUEST['fSQLtoEXCEL'])) {
    $token = base64_decode($_REQUEST['fSQLtoEXCEL']);
    $sig   = base64_decode($_REQUEST['signature']);

	// 1) Verifica firma
    $calc = excel_sign($token, EXCEL_HMAC_KEY);
    if (!excel_safe_equals($calc, $sig)) {
        http_response_code(400);
        echo 'Firma inválida';
        exit;
    }

    // 2) Descifra
    $data = excel_decrypt_token($token, EXCEL_ENC_KEY);
    if (!$data) {
        http_response_code(400);
        echo 'Token inválido';
        exit;
    }

    // 3) Validez temporal
    if (!isset($data['ts']) || abs(time() - (int)$data['ts']) > EXCEL_TOKEN_TTL) {
        http_response_code(400);
        echo 'Token caducado';
        exit;
    }

    $sql    = (string)($data['sql'] ?? '');
    $nombre = (string)($data['nombre'] ?? '');

} else {
    /*
    // --- COMPAT: flujo antiguo base64 con CHAR_SEPARA ---
    $aArray = explode(constant("CHAR_SEPARA"), base64_decode($_REQUEST['fSQLtoEXCEL'] ?? '', true) ?: '');
    $sql    = $aArray[0] ?? '';
    $nombre = $aArray[1] ?? '';
    */

    http_response_code(400);
    echo 'Firma inexistente';
    exit;

}

	if (!empty($sql)){
		$sEntidad = ucfirst($nombre);
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");
		$cEntidad	= new $sEntidad();  // Entidad
		$sDESCListaExcel = $cEntidad->getDESCListaExcel();
	}else{
		echo constant("ERR");
		exit;
	}
	
	$sql = str_replace("*",$cEntidad->getPKListaExcel(), $sql);
	
	if (empty($nombre)){
		$nombre = "ExcelFile";
	}
	if (empty($_REQUEST['fPintaCabecera'])){
		$_REQUEST['fPintaCabecera'] = true;
	}
	if (empty($_REQUEST['fSepararCabecera'])){
		$_REQUEST['fSepararCabecera'] = false;
	}

	$sqlToExcel->crearXLS(	$sql, $nombre,
							$_REQUEST['fPintaCabecera'], $_REQUEST['fSepararCabecera'], $sDESCListaExcel);
?>