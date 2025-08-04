<?php
/*
	$sql="TRUNCATE TABLE `rangos_ip`;"; 
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="";
	$sql="INSERT INTO `rangos_ip` (`idRangoIp`, `idRangoIr`, `rangoSup`, `rangoInf`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(2, 1, '>= 0.45', '< 0.70', '2011-05-10 17:57:00', '2011-05-10 17:57:00', 20, 20),";
	$sql.="(1, 1, '< 0.45', '>= 0.00', '2011-05-10 17:56:34', '2011-05-10 17:56:34', 20, 20),";
	$sql.="(3, 1, '>= 0.70', '<= 1.00', '2011-05-10 17:57:33', '2011-05-10 17:57:33', 20, 20),";
	$sql.="(1, 2, '< 0.45', '>= 0.00', '2011-05-10 17:57:55', '2011-05-10 17:57:55', 20, 20),";
	$sql.="(2, 2, '>= 0.45', '< 0.70', '2011-05-10 17:58:11', '2011-05-10 17:58:11', 20, 20),";
	$sql.="(3, 2, '>= 0.70', '<= 1.00', '2011-05-10 17:58:26', '2011-05-10 17:58:26', 20, 20),";
	$sql.="(1, 3, '< 0.45', '>= 0.00', '2011-05-10 17:58:54', '2011-05-10 17:58:54', 20, 20),";
	$sql.="(2, 3, '>= 0.45', '< 0.70', '2011-05-10 17:59:08', '2011-05-10 17:59:08', 20, 20),";
	$sql.="(3, 3, '>= 0.70', '<= 1.00', '2011-05-10 17:59:29', '2011-05-10 17:59:29', 20, 20);";
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>