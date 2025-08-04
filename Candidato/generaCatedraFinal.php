<?php
// Ignorar los abortos hechos por el usuario y permitir que el script
// se ejecute para siempre
ignore_user_abort(true);
set_time_limit(0);

header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	//include_once(constant("DIR_WS_INCLUDE") . 'SeguridadCandidatos.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidatoDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidato.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");


	$cUtilidades = new Utilidades();

include_once ('include/conexion.php');

echo date("Y-m-d H:i:s");
  	$cCandidato = new Candidatos();
	$cCandidatosDB = new CandidatosDB($conn);
	$cProceso_informesDB = new Proceso_informesDB($conn);
	$cProceso_baremosDB = new Proceso_baremosDB($conn);

	$cRespuestasPruebasDB = new Respuestas_pruebasDB($conn);

/**
** INSERT INTO `respuestas_pruebas_resultados_p` (`idEmpresa`, `descEmpresa`, `idProceso`, `descProceso`, `idCandidato`, `descCandidato`, `codIdiomaIso2`, `descIdiomaIso2`, `idPrueba`, `descPrueba`, `finalizado`, `leidoInstrucciones`, `leidoEjemplos`, `minutos_test`, `segundos_test`, `minutos2_test`, `segundos2_test`, `pantalla`, `campoLibre`, `veces`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`)
** SELECT `idEmpresa`, `descEmpresa`, `idProceso`, `descProceso`, `idCandidato`, `descCandidato`, `codIdiomaIso2`, `descIdiomaIso2`, `idPrueba`, `descPrueba`, `finalizado`, `leidoInstrucciones`, `leidoEjemplos`, `minutos_test`, `segundos_test`, `minutos2_test`, `segundos2_test`, `pantalla`, `campoLibre`, 0, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`
** FROM respuestas_pruebas
** WHERE idEmpresa=5001
** AND finalizado=1
** AND idPrueba IN (SELECT idPrueba from pruebas where idTipoPrueba NOT IN (2,5,10,11,14,15,16,17) and idPrueba NOT IN (5,41,97,98) GROUP BY idPrueba)
**/
/**
** INSERT INTO `respuestas_pruebas_resultados_p` (`idEmpresa`, `descEmpresa`, `idProceso`, `descProceso`, `idCandidato`, `descCandidato`, `codIdiomaIso2`, `descIdiomaIso2`, `idPrueba`, `descPrueba`, `finalizado`, `leidoInstrucciones`, `leidoEjemplos`, `minutos_test`, `segundos_test`, `minutos2_test`, `segundos2_test`, `pantalla`, `campoLibre`, `veces`, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`)
** SELECT `idEmpresa`, `descEmpresa`, `idProceso`, `descProceso`, `idCandidato`, `descCandidato`, `codIdiomaIso2`, `descIdiomaIso2`, `idPrueba`, `descPrueba`, `finalizado`, `leidoInstrucciones`, `leidoEjemplos`, `minutos_test`, `segundos_test`, `minutos2_test`, `segundos2_test`, `pantalla`, `campoLibre`, 0, `fecAlta`, `fecMod`, `usuAlta`, `usuMod`
** FROM respuestas_pruebas WHERE fecAlta >= NOW() - INTERVAL 1 YEAR AND finalizado=1
**/

	//DELETE FROM respuestas_pruebas_resultados_p WHERE idPrueba NOT IN (SELECT idPrueba from pruebas where idTipoPrueba NOT IN (2,5,10,11,14,15,16,17) and idPrueba NOT IN (5,41,97,98) GROUP BY idPrueba)
	//DELETE FROM respuestas_pruebas_resultados_p WHERE finalizado=0
	//SELECT * FROM respuestas_pruebas_resultados_p WHERE idPrueba IN (SELECT idPrueba from pruebas where idTipoPrueba NOT IN (2,5,10,11,14,15,16,17) and idPrueba NOT IN (5,41,97,98) GROUP BY idPrueba) and fecAlta >= NOW() - INTERVAL 1 YEAR
	//$sSQL = "SELECT * FROM respuestas_pruebas_resultados_p ORDER BY veces ASC , fecAlta DESC LIMIT 0,20";

	//Miramos si queda algo por lanzar
	$sSQL = "SELECT * FROM `catedra_sin_empresa_demo_borrar` where tratado=0 LIMIT 0, 800 ";
	echo "<br />" . $sSQL;
	$rs = $conn->Execute($sSQL);
	if ($rs->NumRows() > 0)
	{
		$iContador=0;
		while(!$rs->EOF)
		{
			//Cargamoso la tabla con los datos
			$sSQL = 'UPDATE catedra_final SET
			`Item '. $rs->fields['idItem'] . '`=\'' . $rs->fields['codigo'] . '\'
			WHERE idEmpresa=' . $rs->fields['idEmpresa'] . '
			AND idProceso=' . $rs->fields['idProceso'] . '
			AND idCandidato=' . $rs->fields['idCandidato'] . '
			AND idPrueba=' . $rs->fields['idPrueba'] . '
			AND `ID candidato`=' . $rs->fields['ID candidato'];
			$conn->Execute($sSQL);
			echo "<br>" . $sSQL;
			$sSQL = 'UPDATE catedra_sin_empresa_demo_borrar SET
			tratado=1
			WHERE idEmpresa=' . $rs->fields['idEmpresa'] . '
			AND idProceso=' . $rs->fields['idProceso'] . '
			AND idCandidato=' . $rs->fields['idCandidato'] . '
			AND codIdiomaIso2=\'' . $rs->fields['codIdiomaIso2'] . '\'
			AND idPrueba=' . $rs->fields['idPrueba'] . '
			AND `ID candidato`=' . $rs->fields['ID candidato'] . '
			AND idItem=' . $rs->fields['idItem'];
			$conn->Execute($sSQL);
			echo "<br>" . $sSQL;
			$rs->MoveNext();
		}
	}
  echo "<br />" . date("Y-m-d H:i:s");

?>
