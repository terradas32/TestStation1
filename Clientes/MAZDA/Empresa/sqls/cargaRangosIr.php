<?php
/*	
	$sql="TRUNCATE TABLE `rangos_ir`;"; 
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/
	$sql="";
	$sql="INSERT INTO `rangos_ir` (`idRangoIr`, `rangoSup`, `rangoInf`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(2, '<= 0.70', '> 0.46', '2011-05-10 17:53:10', '2011-05-10 17:53:10', 20, 20),";
	$sql.="(1, '<= 1.00', '> 0.71', '2011-05-10 13:46:11', '2011-05-10 16:06:05', 20, 20),";
	$sql.="(3, '<= 0.45', '>= 0.00', '2011-05-10 17:53:43', '2011-05-10 17:53:43', 20, 20);";

	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>