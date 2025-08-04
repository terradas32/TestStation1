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
	
include_once ('include/conexion.php');
	
	//require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
    $cUtilidades	= new Utilidades();
    
    $sCol1='';
    $sCol2='';
	
	$comboUSUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","login","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"");

    $bLogado = isLogado($conn);

    if (!$bLogado){
    	session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "06900 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}else{
		if (!isUsuarioActivo($conn))
		{
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
		}
		//Recogemos los Menus
		$sMenus = getMenus($conn);
		if (empty($sMenus)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "06920 - " . constant("ERR_NO_AUTORIZADO");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
        }
        $_cEntidadUsuarioTK = getUsuarioToken($conn);

    }
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