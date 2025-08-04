<?php
	$sql="TRUNCATE TABLE `secciones_informes`;"; 
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql.="INSERT INTO `secciones_informes` (`idSeccion`, `idTipoInforme`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="(1, 1, 'Introducción', '2011-05-05 10:23:01', '2011-05-05 10:23:01', 20, 20),";
	$sql.="(2, 1, 'Cabecera Perfíl test', '2011-05-05 17:29:18', '2011-05-05 17:29:18', 20, 20),";
	$sql.="(3, 1, 'Título Informe', '2011-05-05 17:37:48', '2011-05-05 17:37:48', 20, 20);";

	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>