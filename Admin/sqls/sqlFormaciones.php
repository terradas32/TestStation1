<?php
	$sql="";
	$sql = "DELETE FROM `formaciones` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="INSERT INTO `formaciones` (`codIdiomaIso2`, `idFormacion`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('es', 1, 'EGB, Primaria, Elemental', '2010-11-15 18:00:39', '2010-11-15 18:00:39', 1, 1),";
	$sql.="('es', 2, 'BUP, COU, Bachiller Superior', '2010-11-15 18:01:00', '2010-11-15 18:01:00', 1, 1),";
	$sql.="('es', 3, 'FPI, FPII, Admintvo, Secretar', '2010-11-15 18:01:41', '2010-11-15 18:01:41', 1, 1),";
	$sql.="('es', 4, 'FPI, FPII, Técnico Industrial', '2010-11-15 18:02:05', '2010-11-15 18:02:05', 1, 1),";
	$sql.="('es', 5, 'Peritajes, Ingenieros Técnicos', '2010-11-15 18:02:23', '2010-11-15 18:02:23', 1, 1),";
	$sql.="('es', 6, 'Peritajes y Diplom. Medios Univ.', '2010-11-15 18:02:50', '2010-11-15 18:02:50', 1, 1),";
	$sql.="('es', 7, 'Ingenieros Licenc. Técnicos Sup.', '2010-11-15 18:03:14', '2010-11-15 18:03:14', 1, 1),";
	$sql.="('es', 8, 'Titul. Supr. Rama Ciencias', '2010-11-15 18:03:36', '2010-11-15 18:03:36', 1, 1),";
	$sql.="('es', 9, 'Titul. Supr. Rama Letras o Humanidades', '2010-11-15 18:04:00', '2010-11-15 18:04:00', 1, 1),";
	$sql.="('es', 10, 'Profesionales Liberales', '2010-11-15 18:04:17', '2010-11-15 18:05:01', 1, 1),";
	$sql.="('es', 11, 'Master Gestión y Alta Dirección', '2010-11-15 18:04:44', '2010-11-15 18:05:04', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `formaciones` (`codIdiomaIso2`, `idFormacion`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('en', 1, 'GBS, Primary, Elementary', '2010-11-15 18:00:39', '2010-11-15 18:00:39', 1, 1),";
	$sql.="('en', 2, 'BUP, COU, Bachiller Superior', '2010-11-15 18:01:00', '2010-11-15 18:01:00', 1, 1),";
	$sql.="('en', 3, 'FPI, FPII, Admintvo, Secretariat', '2010-11-15 18:01:41', '2010-11-15 18:01:41', 1, 1),";
	$sql.="('en', 4, 'FPI, FPII, Technical Industrial', '2010-11-15 18:02:05', '2010-11-15 18:02:05', 1, 1),";
	$sql.="('en', 5, 'Surveys, Engineers', '2010-11-15 18:02:23', '2010-11-15 18:02:23', 1, 1),";
	$sql.="('en', 6, 'Surveys and Diplom. Means Univ', '2010-11-15 18:02:50', '2010-11-15 18:02:50', 1, 1),";
	$sql.="('en', 7, 'Engineers Licence. Technical Area', '2010-11-15 18:03:14', '2010-11-15 18:03:14', 1, 1),";
	$sql.="('en', 8, 'Title. Delete Rama Sciences', '2010-11-15 18:03:36', '2010-11-15 18:03:36', 1, 1),";
	$sql.="('en', 9, 'Title. Del. Arts or Humanities Rama', '2010-11-15 18:04:00', '2010-11-15 18:04:00', 1, 1),";
	$sql.="('en', 10, 'Liberal Professions', '2010-11-15 18:04:00', '2010-11-15 18:04:00', 1, 1),";
	$sql.="('en', 11, 'Master and Senior Management', '2010-11-15 18:04:44', '2010-11-15 18:05:04', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `formaciones` (`codIdiomaIso2`, `idFormacion`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('ca', 1, 'EGB, Primària, Elemental', '2010-11-15 18:00:39', '2010-11-15 18:00:39', 1, 1),";
	$sql.="('ca', 2, 'BUP, COU, batxiller superior', '2010-11-15 18:01:00', '2010-11-15 18:01:00', 1, 1),";
	$sql.="('ca', 3, 'FPI, FPII, Admintvo, secretari', '2010-11-15 18:01:41', '2010-11-15 18:01:41', 1, 1),";
	$sql.="('ca', 4, 'FPI, FPII, tècnic industrial', '2010-11-15 18:02:05', '2010-11-15 18:02:05', 1, 1),";
	$sql.="('ca', 5, 'Peritatges, enginyers tècnics', '2010-11-15 18:02:23', '2010-11-15 18:02:23', 1, 1),";
	$sql.="('ca', 6, 'Peritatges i Diplom. Mitjans Univ', '2010-11-15 18:02:50', '2010-11-15 18:02:50', 1, 1),";
	$sql.="('ca', 7, 'Enginyers Licence. Tècnics Sup', '2010-11-15 18:03:14', '2010-11-15 18:03:14', 1, 1),";
	$sql.="('ca', 8, 'Títol:. Supr Branca Ciències', '2010-11-15 18:03:36', '2010-11-15 18:03:36', 1, 1),";
	$sql.="('ca', 9, 'Títol:. Supr Branca Lletres o Humanitats', '2010-11-15 18:04:00', '2010-11-15 18:04:00', 1, 1),";
	$sql.="('ca', 10, 'Professionals Liberals', '2010-11-15 18:04:17', '2010-11-15 18:05:01', 1, 1),";
	$sql.="('ca', 11, 'Màster Gestió i Alta Direcció', '2010-11-15 18:04:44', '2010-11-15 18:05:04', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `formaciones` (`codIdiomaIso2`, `idFormacion`, `nombre`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";	
	$sql.="('pt', 1, 'GBS, Primário, Elementar', '2010-11-15 18:00:39', '2010-11-15 18:00:39', 1, 1),";
	$sql.="('pt', 2, 'BUP, COU, Bachiller Superior', '2010-11-15 18:01:00', '2010-11-15 18:01:00', 1, 1),";
	$sql.="('pt', 3, 'FPI, FPII, Admintvo, Secretaria', '2010-11-15 18:01:41', '2010-11-15 18:01:41', 1, 1),";
	$sql.="('pt', 4, 'FPI, FPII, Técnico Industrial', '2010-11-15 18:02:05', '2010-11-15 18:02:05', 1, 1),";
	$sql.="('pt', 5, 'Pesquisas, Engenheiros', '2010-11-15 18:02:23', '2010-11-15 18:02:23', 1, 1),";
	$sql.="('pt', 6, 'exames e Diplom. Meios Univ', '2010-11-15 18:02:50', '2010-11-15 18:02:50', 1, 1),";
	$sql.="('pt', 7, 'Engenheiros licença. Área Técnica', '2010-11-15 18:03:14', '2010-11-15 18:03:14', 1, 1),";
	$sql.="('pt', 8, 'título ». Excluir Ciências Rama', '2010-11-15 18:03:36', '2010-11-15 18:03:36', 1, 1),";
	$sql.="('pt', 9, 'título ». Del. Artes ou Humanidades Rama', '2010-11-15 18:04:00', '2010-11-15 18:04:00', 1, 1),";
	$sql.="('pt', 10, 'profissões liberais', '2010-11-15 18:04:17', '2010-11-15 18:05:01', 1, 1),";
	$sql.="('pt', 11, 'Master e Senior Management', '2010-11-15 18:04:44', '2010-11-15 18:05:04', 1, 1);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>