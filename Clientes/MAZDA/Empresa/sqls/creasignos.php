<?php
/*
	$sql="TRUNCATE TABLE `signos`;"; 
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="INSERT INTO `signos` (`idSigno`, `signo`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, '>', '2011-05-10 13:12:07', '2011-05-10 13:12:07', 20, 20),";
	$sql.="(2, '<', '2011-05-10 13:12:11', '2011-05-10 13:12:11', 20, 20),";
	$sql.="(3, '<=', '2011-05-10 13:12:17', '2011-05-10 13:12:17', 20, 20),";
	$sql.="(4, '>=', '2011-05-10 13:12:20', '2011-05-10 13:12:20', 20, 20);";

	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>