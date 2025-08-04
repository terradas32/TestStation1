<?php
	$sql="";
	$sql = "DELETE FROM `edades` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="INSERT INTO `edades` (`idEdad`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'es', 'Cualquiera', '2010-11-15 17:40:05', '2010-11-15 17:49:33', 0, 1),";
	$sql.="(2, 'es', 'Menos de 26', '2010-11-15 17:49:49', '2010-11-15 17:49:49', 1, 1),";
	$sql.="(3, 'es', 'Entre 26 y 35', '2010-11-15 17:50:06', '2010-11-15 17:50:06', 1, 1),";
	$sql.="(4, 'es', 'Entre 36 y 45', '2010-11-15 17:50:20', '2010-11-15 17:50:20', 1, 1),";
	$sql.="(5, 'es', 'Entre 46 y 55', '2010-11-15 17:50:36', '2010-11-15 17:50:36', 1, 1),";
	$sql.="(6, 'es', '56 en adelante', '2010-11-15 17:50:51', '2010-11-15 17:50:51', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `edades` (`idEdad`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'en', 'Any', '2010-11-15 17:40:05', '2010-11-15 17:49:33', 0, 1),";
	$sql.="(2, 'en', 'Less than 26', '2010-11-15 17:49:49', '2010-11-15 17:49:49', 1, 1),";
	$sql.="(3, 'en', 'Between 26 and 35', '2010-11-15 17:50:06', '2010-11-15 17:50:06', 1, 1),";
	$sql.="(4, 'en', 'Between 36 and 45', '2010-11-15 17:50:20', '2010-11-15 17:50:20', 1, 1),";
	$sql.="(5, 'en', 'Between 46 and 55', '2010-11-15 17:50:36', '2010-11-15 17:50:36', 1, 1),";
	$sql.="(6, 'en', '56 onwards', '2010-11-15 17:50:51', '2010-11-15 17:50:51 ', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `edades` (`idEdad`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'ca', 'Qualsevol', '2010-11-15 17:40:05', '2010-11-15 17:49:33', 0, 1),";
	$sql.="(2, 'ca', 'Menys de 26', '2010-11-15 17:49:49', '2010-11-15 17:49:49', 1, 1),";
	$sql.="(3, 'ca', 'Entre 26 i 35', '2010-11-15 17:50:06', '2010-11-15 17:50:06', 1, 1),";
	$sql.="(4, 'ca', 'Entre 36 i 45', '2010-11-15 17:50:20', '2010-11-15 17:50:20', 1, 1),";
	$sql.="(5, 'ca', 'Entre 46 i 55', '2010-11-15 17:50:36', '2010-11-15 17:50:36', 1, 1),";
	$sql.="(6, 'ca', '56 d\'ara endavant', '2010-11-15 17:50:51', '2010-11-15 17:50:51 ', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `edades` (`idEdad`, `codIdiomaIso2`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 'pt', 'Qualquer', '2010-11-15 17:40:05', '2010-11-15 17:49:33', 0, 1),";
	$sql.="(2, 'pt', 'Menos de 26', '2010-11-15 17:49:49', '2010-11-15 17:49:49', 1, 1),";
	$sql.="(3, 'pt', 'Entre 26 e 35', '2010-11-15 17:50:20', '2010-11-15 17:50:20', 1, 1),";
	$sql.="(4, 'pt', 'Entre 36 e 45', '2010-11-15 17:50:20', '2010-11-15 17:50:20', 1, 1),";
	$sql.="(5, 'pt', 'Entre 46 e 55', '2010-11-15 17:50:36', '2010-11-15 17:50:36', 1, 1),";
	$sql.="(6, 'pt', '56 em diante', '2010-11-15 17:50:51', '2010-11-15 17:50:51 ', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
?>