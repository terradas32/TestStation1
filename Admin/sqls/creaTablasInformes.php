<?php
	$sql="";
	$sql="CREATE TABLE IF NOT EXISTS `signos` (";
	$sql.="`idSigno` int(11) NOT NULL,";
	$sql.="`signo` varchar(255) NOT NULL,";
	$sql.="`fecAlta` datetime NOT NULL,";
	$sql.="`fecMod` datetime NOT NULL,";
	$sql.="`usuAlta` int(11) NOT NULL,";
	$sql.="`usuMod` int(11) NOT NULL,";
	$sql.="PRIMARY KEY (`idSigno`)";
	$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="CREATE TABLE IF NOT EXISTS `secciones_informes` (";
  	$sql.="`idSeccion` int(11) NOT NULL,";
  	$sql.="`idTipoInforme` int(11) NOT NULL,";
  	$sql.="`nombre` varchar(255) NOT NULL,";
  	$sql.="`fecAlta` datetime NOT NULL,";
  	$sql.="`fecMod` datetime NOT NULL,";
  	$sql.="`usuAlta` int(11) NOT NULL,";
  	$sql.="`usuMod` int(11) NOT NULL,";
  	$sql.="PRIMARY KEY (`idSeccion`)";
	$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	
	$sql="";
	$sql="CREATE TABLE IF NOT EXISTS `textos_secciones` (";
  	$sql.="`codIdiomaIso2` char(2) NOT NULL,";
  	$sql.="`idPrueba` int(11) NOT NULL,";
	$sql.="`idTipoInforme` int(11) NOT NULL,";
  	$sql.="`idSeccion` int(11) NOT NULL,";
  	$sql.="`texto` mediumtext NOT NULL,";
  	$sql.="`fecAlta` datetime NOT NULL,";
  	$sql.="`fecMod` datetime NOT NULL,";
  	$sql.="`usuAlta` int(11) NOT NULL,";
  	$sql.="`usuMod` int(11) NOT NULL,";
  	$sql.="PRIMARY KEY (`codIdiomaIso2`,`idTipoInforme`,`idSeccion`,`idPrueba`)";
	$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="CREATE TABLE IF NOT EXISTS `rangos_ir` (";
  	$sql.="`idRangoIr` int(11) NOT NULL,";
  	$sql.="`rangoSup` varchar(255) NOT NULL,";
  	$sql.="`rangoInf` varchar(255) NOT NULL,";
  	$sql.="`fecAlta` datetime NOT NULL,";
  	$sql.="`fecMod` datetime NOT NULL,";
  	$sql.="`usuAlta` int(11) NOT NULL,";
  	$sql.="`usuMod` int(11) NOT NULL,";
  	$sql.="PRIMARY KEY (`idRangoIr`)";
	$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}

	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="CREATE TABLE IF NOT EXISTS `rangos_ip` (";
  	$sql.="`idRangoIp` int(11) NOT NULL,";
  	$sql.="`idRangoIr` int(11) NOT NULL,";
  	$sql.="`rangoSup` varchar(255) NOT NULL,";
  	$sql.="`rangoInf` varchar(255) NOT NULL,";
  	$sql.="`fecAlta` datetime NOT NULL,";
  	$sql.="`fecMod` datetime NOT NULL,";
  	$sql.="`usuAlta` int(11) NOT NULL,";
  	$sql.="`usuMod` int(11) NOT NULL,";
  	$sql.="PRIMARY KEY (`idRangoIp`,`idRangoIr`)";
	$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="CREATE TABLE IF NOT EXISTS `rangos_textos` (";
  	$sql.="`codIdiomaIso2` char(2) NOT NULL,";
  	$sql.="`idPrueba` int(11) NOT NULL,";
  	$sql.="`idTipoInforme` int(11) NOT NULL,";
  	$sql.="`idIr` int(11) NOT NULL,";
  	$sql.="`idIp` int(11) NOT NULL,";
  	$sql.="`texto` mediumtext NOT NULL,";
  	$sql.="`fecAlta` datetime NOT NULL,";
  	$sql.="`fecMod` datetime NOT NULL,";
  	$sql.="`usuAlta` int(11) NOT NULL,";
  	$sql.="`usuMod` int(11) NOT NULL,";
  	$sql.="PRIMARY KEY (`codIdiomaIso2`,`idPrueba`,`idTipoInforme`,`idIr`,`idIp`)";
	$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
?>