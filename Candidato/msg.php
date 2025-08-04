<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

session_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	
$strMensaje = "";
if(isset($_SESSION['mensaje' . constant("NOMBRE_SESSION")])){
    $strMensaje = $_SESSION['mensaje' . constant("NOMBRE_SESSION")];
    unset($_SESSION['mensaje' . constant("NOMBRE_SESSION")]);
}
include('Template/msg.php');
?>