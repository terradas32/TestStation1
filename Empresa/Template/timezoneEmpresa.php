<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
	
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
  	
  	$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
  	$cEmpresas	= new Empresas();  // Entidad
  	
  	  	 
  	$_IdEmpresa = "";
	if (isset($_POST["fIdEmpresa"]) && $_POST["fIdEmpresa"] != ""){
		$_IdEmpresa= $_POST["fIdEmpresa"];
	}
	if (!empty($_IdEmpresa)){
		$cEmpresas->setIdEmpresa($_IdEmpresa);
		$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
	}
?>
<?php echo ($cEmpresas->getTimezone() != "") ? '<span style="color:green;margin-left: 10px;">' . constant("STR_ZONA_HORARIA") . ': ' . $cEmpresas->getTimezone() . '</span>' :  '<span style="color:red;">' . constant("STR_ZONA_HORARIA") . ': ' . constant("STR_NO_DEFINIDA") . '</span>'; ?>