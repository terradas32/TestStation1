<?php
/*
	$sql="";
	$sql = "DELETE FROM `notificaciones` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="";
	$sql="INSERT INTO `notificaciones` (`idNotificacion`, `idTipoNotificacion`, `asunto`, `cuerpo`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 1, 'PSICÓLOGOS EMPRESARIALES: Nueva empresa llamada @empresa_colaboradora@', 'Se ha dado de alta una nueva empresa en PSIC&Oacute;LOGOS EMPRESARIALES Online.<br />\r\n<br />\r\nSu nombre es <strong><span style=\"color: rgb(255, 102, 0);\">@empresa_colaboradora@</span></strong><br />', '2011-03-29 14:54:08', '2011-03-31 14:12:44', 0, 0),";
	$sql.="(1, 3, 'Test finalizado @test_nombre@', 'El usuario @candidato_nombre@ con la direcci&oacute;n de correo electr&oacute;nico @acceso_usuario@, perteneciente a la empresa @empresa_colaboradora@, ha finalizado el test @test_nombre@ en un tiempo total de @minutos_test@ minutos y @segundos_test@ segundos.<br />\r\n<br />\r\n<br />\r\nEl proceso es el siguiente: @proceso_nombre@<br />\r\n<br />\r\nAcuda a su aplicaci&oacute;n de gesti&oacute;n para obtener el informe y las respuestas del test.<br />\r\n<br />\r\n<br />', '2011-03-29 15:01:11', '2011-03-31 14:00:28', 0, 0),";
	$sql.="(1, 4, 'Recordar contraseña', 'Tal y como nos ha solicitado, le enviamos sus datos de acceso a PSICOLOGOS EMPRESARIALES son los siguientes.<br />\r\nUsuario: @acceso_usuario@<br />\r\nContrase&ntilde;a:@acceso_password@<br />\r\n<br />\r\nUn saludo.<br />\r\n<br />\r\nPsic&oacute;logos Empresariales, S.A..<br />\r\n<br />\r\n<br />\r\n<br />\r\nPSICOLOGOS EMPRESARIALES cumple la Ley Org&aacute;nica 15/1999 de 13 de diciembre, de protecci&oacute;n de datos de Car&aacute;cter Personal, y posee el c&oacute;digo de inscripci&oacute;n n&ordm; 2022700054 del Registro General de la Agencia de Protecci&oacute;n de Datos.<br />', '2011-03-29 15:03:46', '2011-03-31 14:01:05', 0, 0),";
	$sql.="(1, 6, 'Aceptación de petición de Dongles', 'Su recarga de <strong><font color=\"#ff6347\">@nDongles@</font></strong> Dongles ha sido aceptada y se ha actualizado en su cuenta.<br />\r\n<br />\r\nGracias.', '2011-03-31 14:14:16', '2011-04-01 13:43:52', 20, 20),";
	$sql.="(1, 7, 'Su petición de Dongles ha sido rechazada', 'Su recarga de <strong><font color=\"#ff6347\">@nDongles@</font></strong>&nbsp; Dongles ha sido rechazada por <strong><font color=\"#ff6347\">@empresa_receptora@</font></strong>.<strong><font color=\"#ff6347\"> <br />\r\n</font></strong>Si quiere m&aacute;s informaci&oacute;n pongase en contacto en el siguiente email de contacto: <strong><font color=\"#ff6347\">@mail_receptora@</font></strong>.<br />\r\nGracias.', '2011-03-31 16:15:40', '2011-04-01 13:43:36', 20, 20),";
	$sql.="(1, 5, 'Petición de recarga de Dongles Realizada por @empresa_colaboradora@', 'Le informamos que la empresa <strong><font color=\"#ff6347\">@empresa_colaboradora@ </font></strong>ha solicitado una recarga de <strong><font color=\"#ff6347\">@nDongles@</font></strong> Dongles en su cuenta.', '2011-04-01 13:39:09', '2011-04-01 13:44:42', 20, 20);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	////////////////////// NOTIFICACIONES TIPO ////////////////////////////////////////
/*
	$sql="";
	$sql = "DELETE FROM `notificaciones_tipos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="";
	$sql="INSERT INTO `notificaciones_tipos` (`idTipoNotificacion`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'Alta de Nueva empresa', '2010-11-08 11:29:09', '2011-03-31 12:25:29', 0, 0),";
	$sql.="(2, 'Alta de Nuevo Candidato', '2010-11-08 11:29:17', '2011-03-31 12:25:26', 0, 0),";
	$sql.="(3, 'Finalización del cuestionario', '2011-03-28 00:00:00', '2011-03-31 12:25:22', 0, 0),";
	$sql.="(4, 'Recordatorio de contraseña', '2011-03-28 00:00:00', '2011-03-31 12:25:19', 0, 0),";
	$sql.="(5, 'Petición de Dongles', '2011-03-31 14:08:35', '2011-03-31 14:08:35', 20, 20),";
	$sql.="(6, 'Aceptación peticion de Dongles', '2011-03-31 14:08:55', '2011-03-31 14:08:55', 20, 20),";
	$sql.="(7, 'Rechazo peticion de Dongles', '2011-03-31 14:09:02', '2011-03-31 14:09:02', 20, 20);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>