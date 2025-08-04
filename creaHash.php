<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');

include_once ('include/conexion.php');

$max=10 ;	//13.800
for ($i=0, $max ; $i < $max; $i++){
	$sSQL = "INSERT INTO hash SET hash='" . md5(uniqid(true) . time() . $i) . "', mail='@cuestionario.com'";
	echo "<br>" . 	$sSQL;
	$conn->Execute($sSQL);
}

echo "<br>Generado " . $i . " Hash";

?>
