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
	
	
	
	$sSQL = "SELECT * FROM respuestas_pruebas_resultados_p ORDER BY veces DESC ";
	echo "<br />" . $sSQL;
	$rsRespuestas_pruebasApti = $conn->Execute($sSQL);
	
	
	$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	
	$cItemsDB = new ItemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
	$iContador=0;
	while(!$rsRespuestas_pruebasApti->EOF)
	{
		//Miramos si ya está registrado
		$sSQL = "SELECT count(*) AS cuantos FROM export_personalidad WHERE idEmpresa=" . $rsRespuestas_pruebasApti->fields['idEmpresa'] . " AND ";
		$sSQL .= "idProceso=" . $rsRespuestas_pruebasApti->fields['idProceso'] . " AND ";
		$sSQL .= "idCandidato=" . $rsRespuestas_pruebasApti->fields['idCandidato'] . " AND ";
		$sSQL .= "idPrueba=" . $rsRespuestas_pruebasApti->fields['idPrueba'] . " ";
		$rsYaGenerado = $conn->Execute($sSQL);
		if ($rsYaGenerado->fields['cuantos'] > 0)
		{
			$sSQL = "DELETE FROM respuestas_pruebas_resultados_p WHERE idEmpresa=" . $rsRespuestas_pruebasApti->fields['idEmpresa'] . " AND ";
			$sSQL .= "idProceso=" . $rsRespuestas_pruebasApti->fields['idProceso'] . " AND ";
			$sSQL .= "idCandidato=" . $rsRespuestas_pruebasApti->fields['idCandidato'] . " AND ";
			$sSQL .= "idPrueba=" . $rsRespuestas_pruebasApti->fields['idPrueba'] . " ";
			$conn->Execute($sSQL);
		}
    	$rsRespuestas_pruebasApti->MoveNext();
    	$iContador++;
    }
    echo "<br />" . date("Y-m-d H:i:s");

?>
