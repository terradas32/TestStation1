<?php
	$sql="";
	$sql = "DELETE FROM `sexos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="INSERT INTO `sexos` (`idSexo`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'es', 'Varón', '2010-11-15 17:51:35', '2010-11-15 17:51:35', 1, 1),";
	$sql.="(2, 'es', 'Mujer', '2010-11-15 17:51:42', '2010-11-15 17:51:42', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `sexos` (`idSexo`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'en', 'Male', '2010-11-15 17:51:35 ', '2010-11-15 17:51:35', 1, 1),";
	$sql.="(2, 'en', 'Woman', '2010-11-15 17:51:42 ', '2010-11-15 17:51:42', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `sexos` (`idSexo`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'ca', 'Home', '2010-11-15 17:51:35 ', '2010-11-15 17:51:35', 1, 1),";
	$sql.="(2, 'ca', 'Dona', '2010-11-15 17:51:42 ', '2010-11-15 17:51:42', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `sexos` (`idSexo`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'pt', 'masculino', '2010-11-15 17:51:35 ', '2010-11-15 17:51:35', 1, 1),";
	$sql.="(2, 'pt', 'Mulher', '2010-11-15 17:51:42 ', '2010-11-15 17:51:42', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql = "DELETE FROM `tratamientos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `tratamientos` (`idTratamiento`, `codIdiomaIso2`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'es', 'Sr.', '2011-03-04 11:11:04', '2011-03-04 11:11:04', 0, 0),";
	$sql.="(2, 'es', 'Sra.', '2011-03-04 11:11:13', '2011-03-04 11:11:13', 0, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `tratamientos` (`idTratamiento`, `codIdiomaIso2`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'en', 'Mr.', '2011-03-04 11:11:04 ', '2011-03-04 11:11:04', 0, 0),";
	$sql.="(2, 'en', 'Ms.', '2011-03-04 11:11:13 ', '2011-03-04 11:11:13', 0, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `tratamientos` (`idTratamiento`, `codIdiomaIso2`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'ca', 'Sr.', '2011-03-04 11:11:04', '2011-03-04 11:11:04', 0, 0),";
	$sql.="(2, 'ca', 'Sra.', '2011-03-04 11:11:13', '2011-03-04 11:11:13', 0, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
		$sql="";
	$sql="INSERT INTO `tratamientos` (`idTratamiento`, `codIdiomaIso2`, `descripcion`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'pt', 'Srt.', '2011-03-04 11:11:04', '2011-03-04 11:11:04', 0, 0),";
	$sql.="(2, 'pt', 'Senhora', '2011-03-04 11:11:13', '2011-03-04 11:11:13', 0, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>