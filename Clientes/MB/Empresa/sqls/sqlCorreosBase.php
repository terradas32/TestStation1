<?php
/*
	$sql="";
	$sql = "DELETE FROM `correos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="";
	$sql="INSERT INTO `correos` (`idCorreo`, `idTipoCorreo`, `idEmpresa`, `nombre`, `asunto`, `cuerpo`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 1, 0, 'Correo genérico', 'Bienvenido', 'Bienvenido/a,<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />\r\nHa sido dado/a de alta para realizar un cuestionario / test, por la empresa @empresa_colaboradora@, en la plataforma on-line de Psic&oacute;logos Empresariales, S.A. Para acceder deber&aacute; pinchar en el link @link_acceso_pruebas@. Una vez haya accedido, deber&aacute; pinchar en el &aacute;rea &quot;Candidatos&quot; e introducir las siguientes claves:<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />\r\nUsuario: @acceso_usuario@<br />\r\n<br />\r\nContrase&ntilde;a: @acceso_password@<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />\r\nLe agradecemos de antemano su cooperaci&oacute;n.<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />\r\nSi tiene cualquier pregunta, por favor no dude en contactarnos:<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />\r\n&nbsp;<br />\r\n<br />\r\nEmail: info@test-station.com<br />\r\n<br />\r\nPsic&oacute;logos Empresariales, S.A. cumple la Ley Org&aacute;nica 15/1999 de 13 de diciembre, de protecci&oacute;n de datos de Car&aacute;cter Personal, y posee el c&oacute;digo de inscripci&oacute;n n&ordm; 2022700054 del Registro General de la Agencia de Protecci&oacute;n de Datos.<br />\r\n<br />', '', '2011-03-09 12:26:05', '2011-04-05 10:23:21', 20, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
/*
	$sql="";
	$sql = "DELETE FROM `tipos_correos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/
	$sql="";
	$sql="INSERT INTO `tipos_correos` (`idTipoCorreo`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'Envio', '2010-11-08 11:29:09', '2011-02-28 18:27:47', 1, 0),";
	$sql.="(2, 'Confirmacion', '2010-11-08 11:29:17', '2011-02-28 18:27:44', 1, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>