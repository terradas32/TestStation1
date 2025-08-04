<?php
	$sql="";
	$sql = "DELETE FROM `opciones_ejemplos` ";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_BORRAR") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="INSERT INTO `opciones_ejemplos` (`codIdiomaIso2`, `idPrueba`, `idEjemplo`, `idOpcion`, `descripcion`, `codigo`, `bajaLog`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('es', 16, 1, 1, 'Aumento en la rentabilidad de los bancos.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 2, 1, 'Mayor en bancos.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 3, 1, 'Mayores en las plantillas de bancos.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 1, 1, 'Verdadero', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 2, 1, 'Verdadero', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 3, 1, 'Verdadero', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 4, 1, 'Verdadero', 'A', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 1, 2, 'Aumento en la rentabilidad de las cajas.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 2, 2, 'Mayor en cajas.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 3, 2, 'Las plantillas aumentan igual.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 1, 2, 'Falso', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 2, 2, 'Falso', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 3, 2, 'Falso', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 4, 2, 'Falso', 'B', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 1, 3, 'Aumento en la plantilla de los bancos.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 2, 3, 'Es igual en bancos y cajas.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 3, 3, 'En los bancos, la relación entre aumento de  rentabilidad y activos es más baja que en las cajas.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 1, 3, 'Información Insuficiente', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 2, 3, 'Información Insuficiente', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 3, 3, 'Información Insuficiente', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 26, 4, 3, 'Información Insuficiente', 'C', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 1, 4, 'No se puede saber.', 'D', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 2, 4, 'No se puede saber.', 'D', 0, now(), now(), 0, 0),";
	$sql.="('es', 16, 3, 4, 'En las cajas, la relación entre aumento de  rentabilidad y activos es más baja que en los bancos.', 'D', 0, now(), now(), 0, 0);";
		if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="INSERT INTO `opciones_ejemplos` (`codIdiomaIso2`, `idPrueba`, `idEjemplo`, `idOpcion`, `descripcion`, `codigo`, `bajaLog`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('en', 16, 1, 1,'Increase in bank profitability.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 2, 1,'Mayor in banks.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 3, 1,'Major banks in the templates.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 1, 1,'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 2, 1,'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 3, 1,'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 4, 1,'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 1, 2,'Increase the profitability of banks.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 2, 2,'Mayor in boxes.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 3, 2,'Increases as templates.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 1, 2,'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 2, 2,'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 3, 2,'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 4, 2,'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 1, 3,'Increased template banks.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 2, 3,'It\'s the same banks and savings banks.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 3, 3,'In banking, the relationship between increased profitability and assets is lower than in the boxes.', 'C', 0, now(), now(), 0, 0),"; 
	$sql.="('en', 26, 1, 3,'Insufficient information', 'C', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 2, 3,'Insufficient information', 'C', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 3, 3,'Insufficient information', 'C', 0, now(), now(), 0, 0),";
	$sql.="('en', 26, 4, 3,'Insufficient information', 'C', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 1, 4,'You can not know.', 'D', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 2, 4,'You can not know.', 'D', 0, now(), now(), 0, 0),";
	$sql.="('en', 16, 3, 4,'In the boxes, the relationship between increased profitability and assets is lower than the banks.', 'D', 0, now(), now(), 0, 0);";
		if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	$sql="";
	$sql="INSERT INTO `opciones_ejemplos` (`codIdiomaIso2`, `idPrueba`, `idEjemplo`, `idOpcion`, `descripcion`, `codigo`, `bajaLog`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('ca', 16, 1, 1, 'Augment en la rendibilitat dels bancs.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 2, 1, 'Major en bancs.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 3, 1, 'Majors a les plantilles de bancs.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 1, 1, 'Veritable', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 2, 1, 'Veritable', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 3, 1, 'Veritable', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 4, 1, 'Veritable', 'A', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 1, 2, 'Augment en la rendibilitat de les caixes.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 2, 2, 'Major en caixes.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 3, 2, 'Les plantilles augmenten igual.', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 1, 2, 'Fals', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 2, 2, 'Fals', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 3, 2, 'Fals', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 4, 2, 'Fals', 'B', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 1, 3, 'Augment en la plantilla dels bancs.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 2, 3, 'És igual en bancs i caixes.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 3, 3, 'En els bancs, la relació entre augment de rendibilitat i actius és més baixa que en les caixes.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 1, 3, 'Informació Insuficient', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 2, 3, 'Informació Insuficient', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 3, 3, 'Informació Insuficient', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 26, 4, 3, 'Informació Insuficient', 'C', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 1, 4, 'No es pot saber.', 'D', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 2, 4, 'No es pot saber.', 'D', 0, now(), now(), 0, 0),";
	$sql.="('ca', 16, 3, 4, 'A les caixes, la relació entre augment de rendibilitat i actius és més baixa que en els bancs.', 'D', 0, now(), now(), 0, 0);";
		if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
	$sql="";
	$sql="INSERT INTO `opciones_ejemplos` (`codIdiomaIso2`, `idPrueba`, `idEjemplo`, `idOpcion`, `descripcion`, `codigo`, `bajaLog`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('pt', 16, 1, 1, 'Aumento da lucratividade do banco', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 2, 1, 'O prefeito nos bancos', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 3, 1, 'Os grandes bancos nos modelos.', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 1, 1, 'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 2, 1, 'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 3, 1, 'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 4, 1, 'True', 'A', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 1, 2, 'Aumentar a rentabilidade dos bancos', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 2, 2, 'O prefeito em caixas', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 3, 2, 'Aumenta à medida que os modelos', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 1, 2, 'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 2, 2, 'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 3, 2, 'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 4, 2, 'False', 'B', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 1, 3, 'Bancos modelo de Aumento', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 2, 3, 'É o mesmos bancos e caixas econômicas', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 3, 3, 'No setor bancário, a relação entre aumento da rentabilidade e ativos é menor do que nas caixas.', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 1, 3, 'A insuficiência de informações', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 2, 3, 'A insuficiência de informações', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 3, 3, 'Insuficiência de informações', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 26, 4, 3, 'A insuficiência de informações', 'C', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 1, 4, 'Você não pode saber', 'D', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 2, 4, 'Você não pode saber', 'D', 0, now(), now(), 0, 0),";
	$sql.="('pt', 16, 3, 4, 'Nas caixas, a relação entre aumento da rentabilidade e ativos é menor do que os bancos.', 'D', 0, now(), now(), 0, 0);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
	
?>