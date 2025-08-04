<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');

include_once ('include/conexion.php');
/////
//---------ALTER TABLE `respuestas_pruebas_items` CHANGE `usuAlta` `usuAlta` VARCHAR(11) NULL, CHANGE `usuMod` `usuMod` VARCHAR(11) NULL;

$sql = "ALTER TABLE `wi_historico_cambios` CHANGE `usuAlta` `usuAlta` VARCHAR(11) NOT NULL;";
echo "<br />" . $sql;
	//$conn->Execute($sql);
$sql = "ALTER TABLE `wi_historico_cambios` CHANGE `usuMod` `usuMod` VARCHAR(11) NOT NULL;";
echo "<br />" . $sql;
	//$conn->Execute($sql);

$sql = "SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'teststation' and ENGINE = 'myISAM' LIMIT 0,1";
echo "<br />" . $sql;
$lista = $conn->Execute($sql);
echo "<br />Iniciado Listado";
		while (!$lista->EOF)
		{
			//echo "<br />".$lista->fields['TABLE_NAME'];
			if ( $lista->fields['TABLE_NAME'] != "rules" && $lista->fields['TABLE_NAME'] != "timezone"
			&& $lista->fields['TABLE_NAME'] != "descargas_informes" && $lista->fields['TABLE_NAME'] != "catedra_final"
			&& $lista->fields['TABLE_NAME'] != "catedra_final_orden" && $lista->fields['TABLE_NAME'] != "catedra_sin_empresa_demo"
			&& $lista->fields['TABLE_NAME'] != "catedra_sin_empresa_demo_borrar"
			&& $lista->fields['TABLE_NAME'] != "catedra_sin_empresa_demo_borrar_eliminar"
			&& $lista->fields['TABLE_NAME'] != "empresas_mazda"
			&& $lista->fields['TABLE_NAME'] != "export_especial"
			&& $lista->fields['TABLE_NAME'] != "export_especial_old"
		){
				$sql = 'SET SQL_MODE=\'ALLOW_INVALID_DATES\';';
				echo "<br />" . $sql;
				//$conn->Execute($sql);
				if ( $lista->fields['TABLE_NAME'] == "consumos"){
					$sql = 'ALTER TABLE `consumos` CHANGE `idConsumo` `idConsumo` INT(11) NOT NULL;';
					echo "<br />" . $sql;
					//$conn->Execute($sql);
				}
				$sql = 'UPDATE ' . $lista->fields['TABLE_NAME'] . ' SET fecAlta = NOW(), fecMod = NOW() WHERE fecAlta=\'0000-00-00 00:00:00\' || fecMod =\'0000-00-00 00:00:00\';';
				echo "<br />" . $sql;
				// if($conn->Execute($sql) === false){
				//
				// 	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [Modificando fechas][" . $sql . "]";
				// 	echo "<br />" . $sTypeError;
				// 	exit;
				// }
			}
			$sSQL= "ALTER TABLE " . $lista->fields['TABLE_NAME'] . " ENGINE=InnoDB; ";
			echo "<br />" . $sSQL;
			// if($conn->Execute($sSQL) === false){
			//
			// 	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [Modificando MOTOR][" . $sSQL . "]";
			// 	echo "<br />" . $sTypeError;
			// }
			if ( $lista->fields['TABLE_NAME'] == "consumos"){
				$sql = 'ALTER TABLE `consumos` CHANGE `idConsumo` `idConsumo` INT(11) NOT NULL AUTO_INCREMENT;';
				echo "<br />" . $sql;
				//$conn->Execute($sql);
			}
			$lista->MoveNext();
		}
echo "<br />Finalizado Listado";

?>
