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
	
	$ADODB_COUNTRECS = false; //No cuenta el numero de registros encontrados por el enunciado SELECT
	///////////////////////////////////////////////////////////////////////////////////////////////
	$conn = NewADOConnection('ado_mssql'); 
	$conn->charPage = 65001;	//utf8
	$dsn ='Provider=SQLNCLI;Server=' . constant("DB_HOST_MS") . ';Database=' . constant("DB_DATOS_MS") . ';Uid=' . constant("DB_USUARIO_MS") . ';Pwd=' . constant("DB_PASSWORD_MS");  
	$conn->Connect($dsn);
	$conn->SetFetchMode(constant("ADODB_FETCH_ASSOC")); 
	if (empty($conn)){			
        echo(constant("ERR") . " MS SQL SERVER");
		exit;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
		
    $cUtilidades	= new Utilidades();
    
    $sCol1='';
    $sCol2='';
	
    if (empty($_POST['sPG'])){
    	session_start();
		$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_ADMINISTRADOR");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	if (file_exists(constant("DIR_FS_DOCUMENT_ROOT") . "Template/" . $_POST["sPG"] . ".php" )){
		include_once("Template/" . $_POST["sPG"] . ".php");
	}else{
		session_start();
		$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
?>