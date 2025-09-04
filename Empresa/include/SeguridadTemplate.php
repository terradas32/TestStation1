<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/Configuracion.php");
    }
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	  include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	  require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");

  	if (!isset($conn)){
  		session_start();
          $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "01000 - " . constant("ERR_NO_AUTORIZADO");
  		header("Location: " . constant("HTTP_SERVER") . "msg.php");
  		exit;
  	}
    $bLogado = isLogado($conn);
    if (!$bLogado){
    	session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "02000 - " . constant("ERR_NO_AUTORIZADO");
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
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "03000 - " . constant("ERR_NO_AUTORIZADO");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
        }
        $_cEntidadUsuarioTK = getUsuarioToken($conn);
    }

	$hDatos = permisosFuncionalidadNombre(basename($_SERVER["PHP_SELF"]), $conn);

	if ($hDatos == null || empty($hDatos)){
		session_start();
		$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "04000 - " . constant("ERR_FORM_EJECUTAR_OPCION");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}

	$_bModificar	=	(!empty($hDatos->fields["modificar"]) && $hDatos->fields["modificar"] == "on") ? true : false;
	$_bBorrar		=	(!empty($hDatos->fields["borrar"]) && $hDatos->fields["borrar"] == "on") ? true : false;
?>
