<?php
/*
	$sql="TRUNCATE TABLE `textos_secciones`;"; 
	
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
*/	
	$sql="";
	$sql="INSERT INTO `textos_secciones` (`codIdiomaIso2`, `idPrueba`, `idTipoInforme`, `idSeccion`, `texto`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`) VALUES";
	$sql.="('es', 16, 1, 1, '<p align=\"justify\" style=\"font-size:22px;\">La aptitud es una predisposición natural que tiene una persona para realizar una determinada actividad. Los test aptitudinales permiten, por tanto, detectar el potencial de una persona en un determinado trabajo o tarea.<br /><br /></p>\r\n<p align=\"justify\" style=\"font-size:22px;\">El Test NIPS permite medir la capacidad para tomar decisiones correctas en base a informaciones cuantitativas presentadas en tablas estadísticas o gráficos, es decir, el potencial que una persona tiene para trabajos que requieren la comprensión y manejo de datos de tipo numérico.<br /><br /></p>\r\n<p align=\"justify\" style=\"font-size:22px;\">El resultado se compara con la puntuación obtenida por un grupo de personas similar que ha realizado el Test. Esta comparación permite comprobar dónde se sitúa una persona con respecto a otros profesionales que han realizado esta prueba en las mismas condiciones.<br /><br /></p>\r\n<p align=\"justify\" style=\"font-size:22px;\">Para realizar la comparación se utilizan baremos que son tablas de datos o resultados de un grupo de personas, suficientemente numeroso para que estén representados todos los diversos tipos en cuanto a lo que medimos. El baremo nos da el significado que esa puntuación individual tiene dentro del grupo.<br /><br /></p>', '2011-05-05 11:49:56', '2011-05-05 11:49:56', 20, 20),";
	$sql.="('pt', 16, 1, 2, 'PERFIL TESTE DE APTID&Atilde;O NUM&Eacute;RICA', '2011-05-05 17:30:38', '2011-05-05 17:30:51', 20, 20),";
	$sql.="('pt', 16, 1, 1, '<p align=\"justify\" style=\"font-size:22px;\">A aptidão é uma predisposição natural de uma pessoa para realizar uma determinada atividade.Os testes de aptidão permitem, portanto, identificar o potencial de um indivíduo para um determinado trabalho ou tarefa.<br />\r\n&nbsp;</p>\r\n<p align=\"justify\" style=\"font-size:22px;\">O Teste NIPS permite medir a capacidade para tomar decisões corretas com base em informações quantitativas apresentadas em tabelas com estatísticas ou gráficos, ou seja, avalia o potencial que uma pessoa tem para trabalhos que exigem a compreensão e o uso de dados do tipo numérico.<br />\r\n&nbsp;</p>\r\n<p align=\"justify\" style=\"font-size:22px;\">O resultado é comparado com o de um grupo de pessoas semelhantes que já fizeram o Teste. Esta comparação permite determinar onde uma pessoa se situa em relação a outros profissionais que passaram pela mesma prova nas mesmas condições.<br />&nbsp;</p>\r\n<p align=\"justify\" style=\"font-size:22px;\">Para estabelecer a comparação usam-se baremas, que são tabelas de dados ou resultados de um grupo de pessoas, suficientemente numerosos para representar todos os diversos tipos do que medimos. O barema nos dá o significado da pontuação individual em relação ao grupo.<br />&nbsp;</p>', '2011-05-05 17:34:00', '2011-05-05 17:34:00', 20, 20),";
	$sql.="('es', 16, 1, 2, 'PERFIL TEST RAZONAMIENTO NUMÉRICO', '2011-05-05 17:35:09', '2011-05-05 17:35:09', 20, 20),";
	$sql.="('es', 16, 1, 3, 'TEST DE RAZONAMIENTO CRÍTICO NUMÉRICO', '2011-05-05 17:38:06', '2011-05-05 17:38:06', 20, 20),";
	$sql.="('pt', 16, 1, 3, 'TESTE DE RACIOC&Iacute;NIO NUM&Eacute;RICO GERAL', '2011-05-05 17:38:44', '2011-05-05 17:38:44', 20, 20);";
	if($oconn->Execute($sql) === false){
		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_NUEVO") . "][ProcesoTablas]";
		error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo(constant("ERR"));
		exit;
	}
