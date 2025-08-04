<?php
/*
	$sql="";
	$sql = "DELETE FROM `nivelesjerarquicos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="";
	$sql="INSERT INTO `nivelesjerarquicos` (`codIdiomaIso2`, `idNivel`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('es', 1, 'Gerentes y Directivos', '2010-11-15 17:55:54', '2010-11-15 17:55:54', 1, 1),";
	$sql.="('es', 2, 'Jefes Superiores', '2010-11-15 17:56:08', '2010-11-15 17:56:08', 1, 1),";
	$sql.="('es', 3, 'Técnicos Superiores', '2010-11-15 17:56:25', '2010-11-15 17:56:25', 1, 1),";
	$sql.="('es', 4, 'Técnicos Medios', '2010-11-15 17:56:40', '2010-11-15 17:56:40', 1, 1),";
	$sql.="('es', 5, 'Técnicos Comerciales', '2010-11-15 17:56:54', '2010-11-15 17:56:54', 1, 1),";
	$sql.="('es', 6, 'Secretarias Dirección', '2010-11-15 17:57:11', '2010-11-15 17:57:11', 1, 1),";
	$sql.="('es', 7, 'Vendedores', '2010-11-15 17:58:39', '2010-11-15 17:58:39', 1, 1),";
	$sql.="('es', 8, 'Mandos Intermedios', '2010-11-15 17:58:56', '2010-11-15 17:58:56', 1, 1),";
	$sql.="('es', 9, 'Operarios y oficiales', '2010-11-15 17:59:11', '2010-11-15 17:59:36', 1, 1),";
	$sql.="('es', 10, 'Auxiliares Administrativos', '2010-11-15 17:59:29', '2010-11-15 17:59:29', 1, 1),";
	$sql.="('es', 11, 'Joven Profesional/Pasante/Becario', '2010-11-15 18:00:05', '2010-11-15 18:00:05', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `nivelesjerarquicos` (`codIdiomaIso2`, `idNivel`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('en', 1, 'Managers and Executives', '2010-11-15 17:55:54', '2010-11-15 17:55:54', 1, 1),";
	$sql.="('en', 2, 'Higher Chiefs', '2010-11-15 17:56:08', '2010-11-15 17:56:08', 1, 1),";
	$sql.="('en', 3, 'Higher Technicians', '2010-11-15 17:56:25', '2010-11-15 17:56:25', 1, 1),";
	$sql.="('en', 4, 'Allied', '2010-11-15 17:56:40', '2010-11-15 17:56:40', 1, 1),";
	$sql.="('en', 5, 'Technical Trade', '2010-11-15 17:56:54', '2010-11-15 17:56:54', 1, 1),";
	$sql.="('en', 6, 'Secretaries Address', '2010-11-15 17:57:11', '2010-11-15 17:57:11', 1, 1),";
	$sql.="('en', 7, 'Vendors', '2010-11-15 17:58:39', '2010-11-15 17:58:39', 1, 1),";
	$sql.="('en', 8, 'Middle Management', '2010-11-15 17:58:56', '2010-11-15 17:58:56', 1, 1),";
	$sql.="('en', 9, 'Operators and officers', '2010-11-15 17:59:11', '2010-11-15 17:59:36', 1, 1),";
	$sql.="('en', 10, 'Administrative Assistant', '2010-11-15 17:59:29', '2010-11-15 17:59:29', 1, 1),";
	$sql.="('en', 11, 'Young Professional / Trainee / Fellow', '2010-11-15 18:00:05', '2010-11-15 18:00:05', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `nivelesjerarquicos` (`codIdiomaIso2`, `idNivel`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('ca', 1, 'Gerents i Directius', '2010-11-15 17:55:54', '2010-11-15 17:55:54', 1, 1),";
	$sql.="('ca', 2, 'Caps Superiors', '2010-11-15 17:56:08', '2010-11-15 17:56:08', 1, 1),";
	$sql.="('ca', 3, 'Tècnics Superiors', '2010-11-15 17:56:25', '2010-11-15 17:56:25', 1, 1),";
	$sql.="('ca', 4, 'tècnics mitjans', '2010-11-15 17:56:40', '2010-11-15 17:56:40', 1, 1),";
	$sql.="('ca', 5, 'Tècnics Comercials', '2010-11-15 17:56:54', '2010-11-15 17:56:54', 1, 1),";
	$sql.="('ca', 6, 'Secretàries Adreça', '2010-11-15 17:57:11', '2010-11-15 17:57:11', 1, 1),";
	$sql.="('ca', 7, 'Venedors', '2010-11-15 17:58:39', '2010-11-15 17:58:39', 1, 1),";
	$sql.="('ca', 8, 'Comandaments Intermedis', '2010-11-15 17:58:56', '2010-11-15 17:58:56', 1, 1),";
	$sql.="('ca', 9, 'Operaris i oficials', '2010-11-15 17:59:11', '2010-11-15 17:59:36', 1, 1),";
	$sql.="('ca', 10, 'Auxiliars Administratius', '2010-11-15 17:59:29', '2010-11-15 17:59:29', 1, 1),";
	$sql.="('ca', 11, 'Jove Professional / Passant / Becari', '2010-11-15 18:00:05', '2010-11-15 18:00:05', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `nivelesjerarquicos` (`codIdiomaIso2`, `idNivel`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('pt', 1, 'gerentes e executivos', '2010-11-15 17:55:54', '2010-11-15 17:55:54', 1, 1),";
	$sql.="('pt', 2, 'Chiefs Superior', '2010-11-15 17:56:08', '2010-11-15 17:56:08', 1, 1),";
	$sql.="('pt', 3, 'de técnicos superiores', '2010-11-15 17:56:25', '2010-11-15 17:56:25', 1, 1),";
	$sql.="('pt', 4, 'aliado', '2010-11-15 17:56:40', '2010-11-15 17:56:40', 1, 1),";
	$sql.="('pt', 5, 'Comércio técnica', '2010-11-15 17:56:54', '2010-11-15 17:56:54', 1, 1),";
	$sql.="('pt', 6, 'secretários de endereços', '2010-11-15 17:57:11', '2010-11-15 17:57:11', 1, 1),";
	$sql.="('pt', 7, 'vendedores', '2010-11-15 17:58:39', '2010-11-15 17:58:39', 1, 1),";
	$sql.="('pt', 8, 'Gestão Médio', '2010-11-15 17:58:56', '2010-11-15 17:58:56', 1, 1),";
	$sql.="('pt', 9, 'Operadores e agentes', '2010-11-15 17:59:11', '2010-11-15 17:59:36', 1, 1),";
	$sql.="('pt', 10, 'Assistente Administrativo', '2010-11-15 17:59:29', '2010-11-15 17:59:29', 1, 1),";
	$sql.="('pt', 11, 'Jovem Estagiário / Profissional / companheiro', '2010-11-15 18:00:05', '2010-11-15 18:00:05', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>