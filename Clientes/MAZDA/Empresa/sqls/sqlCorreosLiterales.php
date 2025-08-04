<?php
/*
	$sql="";
	$sql = "DELETE FROM `correos_literales` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/
	$sql="";
	$sql="INSERT INTO `correos_literales` (`idLiteral`, `codIdiomaIso2`, `literal`, `descripcion`, `sistema`, `visible`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'es', '@proceso_nombre@', 'Se corresponde con el nombre del proceso', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:04:01', 0, 0),";
	$sql.="(2, 'es', '@proceso_fecha_inicio@', 'Se corresponde con la fecha de inicio de de la convocatoria', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:03:49', 0, 0),";
	$sql.="(3, 'es', '@proceso_fecha_fin@', 'Se corresponde con la fecha de fin de la convocatoria', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:03:43', 0, 0),";
	$sql.="(4, 'es', '@candidato_nombre@', 'Se corresponde con el nombre del candidato', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:04:49', 0, 0),";
	$sql.="(5, 'es', '@candidato_apellido1@', 'Se corresponde con el apellido1 del candidato', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:04:43', 0, 0),";
	$sql.="(6, 'es', '@candidato_apellido2@', 'Se corresponde con el apellido2 del candidato', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:04:40', 0, 0),";
	$sql.="(7, 'es', '@acceso_usuario@', 'Se corresponde con el usuario para acceder a la plataforma de pruebas', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:04:22', 0, 0),";
	$sql.="(8, 'es', '@acceso_password@', 'Se corresponde con el password para acceder a la plataforma de pruebas ', 1, 1, '2011-02-15 18:16:56', '2011-03-29 18:04:18', 0, 0),";
	$sql.="(9, 'es', '@link_acceso_pruebas@', 'Se corresponde con la url de acceso a la plataforma de pruebas', 1, 1, '2011-02-15 00:00:00', '2011-03-29 18:04:32', 0, 0),";
	$sql.="(10, 'es', '@proceso_hora_inicio@', 'Hora de Inicio', 1, 1, '2011-03-24 13:51:05', '2011-03-29 18:03:33', 0, 0),";
	$sql.="(11, 'es', '@proceso_hora_fin@', 'Hora de fin', 1, 1, '2011-03-24 13:51:37', '2011-03-29 18:03:22', 0, 0),";
	$sql.="(12, 'es', '@test_nombre@', 'Nombre del test', 1, 0, '2011-03-29 17:55:06', '2011-03-29 17:58:03', 0, 0),";
	$sql.="(13, 'es', '@empresa_colaboradora@', 'Nombre de la empresa colaboradora', 1, 0, '2011-03-29 17:55:32', '2011-03-29 17:57:26', 0, 0),";
	$sql.="(14, 'es', '@minutos_test_fin@', 'Minuto en el que se ha finalizado el test', 1, 0, '2011-03-29 17:55:58', '2011-03-29 17:57:04', 0, 0),";
	$sql.="(15, 'es', '@segundos_test_fin@', 'Segundos en el que se ha finalizado el test', 1, 0, '2011-03-29 17:56:26', '2011-03-29 17:56:55', 0, 0),";
	$sql.="(16, 'es', '@nDongles@', 'Número de Dongles de la petición', 1, 0, '2011-03-31 14:07:45', '2011-03-31 14:07:45', 20, 20),";
	$sql.="(17, 'es', '@empresa_receptora@', 'Se corresponde con la empresa receptora de la petición de Dongles', 1, 0, '2011-04-01 13:41:37', '2011-04-05 10:28:22', 20, 20),";
	$sql.="(18, 'es', '@mail_receptora@', 'Se corresponde con el mail de la empresa receptora de la petición de Dongles', 1, 0, '2011-04-01 13:42:12', '2011-04-05 10:29:35', 20, 20);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>