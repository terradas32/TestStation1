<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpvDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpv.php");
	
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
  	
  	$cEntidadTPVDB	= new Empresas_conf_tpvDB($conn);  // Entidad DB
  	$cEntidadTPV	= new Empresas_conf_tpv();  // Entidad
  	
  	  	 
  	$_IdEmpresa = "";
  	$_IdTipoTpv = "";
		if (isset($_POST["IdEmpresa"]) && $_POST["IdEmpresa"] != ""){
			$_IdEmpresa= $_POST["IdEmpresa"];
		}
		
		if (isset($_POST["IdTipoTpv"]) && $_POST["IdTipoTpv"] != ""){
			$_IdTipoTpv=$_POST["IdTipoTpv"];
		}
		if (!empty($_IdEmpresa) && !empty($_IdTipoTpv)){
				$cEntidadTPV->setIdEmpresa($_POST["IdEmpresa"]);
				$cEntidadTPV->setIdTipoTpv($_POST["IdTipoTpv"]);
				$cEntidadTPV = $cEntidadTPVDB->readEntidad($cEntidadTPV);
		}
?>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr>					
				<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
				<td nowrap="nowrap" width="140" class="negrob" valign="top">Nombre de Comercio&nbsp;</td>
				<td ><input type="text" name="fBUSINESS_NAME" value="<?php echo $cEntidadTPV->getBUSINESS_NAME();?>" class="obliga" onchange="javascript:trim(this);" /></td>
			</tr>
			<tr>					
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
				<td nowrap="nowrap" width="140" class="negrob" valign="top">Código de Comercio&nbsp;</td>
				<td ><input type="text" name="fBUSINESS_CODE" value="<?php echo $cEntidadTPV->getBUSINESS_CODE();?>" class="obliga" onchange="javascript:trim(this);" /></td>
			</tr>
			<tr>					
				<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
				<td nowrap="nowrap" width="140" class="negrob" valign="top">Clave de comercio&nbsp;</td>
				<td ><input type="text" name="fBUSINESS_PASS" value="<?php echo $cEntidadTPV->getBUSINESS_PASS();?>" class="obliga" onchange="javascript:trim(this);" /></td>
			</tr>
			<tr>					
				<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
				<td nowrap="nowrap" width="140" class="negrob" valign="top">Moneda de pago&nbsp;</td>
				<td ><input type="text" name="fBUSINESS_COINC" value="<?php echo $cEntidadTPV->getBUSINESS_COINC();?>" class="obliga" onchange="javascript:trim(this);" /></td>
			</tr>
