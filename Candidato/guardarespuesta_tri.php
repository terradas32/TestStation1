<?php
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
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


	include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");



    $cRespPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cOpcionesDB = new OpcionesDB($conn);
	$cPruebasDB = new PruebasDB($conn);
    $cItemsDB = new ItemsDB($conn);
	
    $cItem = new Items();
    $cItem->setId($_POST["fId"]);
    $cItem->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
    $cItem = $cItemsDB->readEntidadId($cItem);

    $cRespPruebasItems = new Respuestas_pruebas_items();

    $cRespPruebasItems->setIdEmpresa($_POST["fIdEmpresa"]);
	$cRespPruebasItems->setIdProceso($_POST["fIdProceso"]);
	$cRespPruebasItems->setIdCandidato($_POST["fIdCandidato"]);
	$cRespPruebasItems->setIdPrueba($_POST["fIdPrueba"]);
	$cRespPruebasItems->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
	$cRespPruebasItems->setIdItem($cItem->getIdItem());
	$cRespPruebasItems->setIdOpcion($_POST["fIdOpcion"]);
    $cRespPruebasItems->setId_tri($_POST["fId"]);
	$cRespPruebasItems->setIndex_tri($cItem->getIndex_tri());
	$cRespPruebasItems->setOrden($_POST["forden"]);

	//Leemos la opción para saber en código de la misma
	$cOpcion = new Opciones();
	$cOpcion->setIdItem($cItem->getIdItem());
	$cOpcion->setIdPrueba($cItem->getIdPrueba());
	$cOpcion->setIdOpcion($cRespPruebasItems->getIdOpcion());
	$cOpcion->setCodIdiomaIso2($cRespPruebasItems->getCodIdiomaIso2());
	$cOpcion = $cOpcionesDB->readEntidad($cOpcion);

	//$bInsertar =  (isset($_POST["fNOInsertatOpcion"]) && $_POST["fNOInsertatOpcion"] == "true") ? false : true ;
    $cRespPruebasItemsDB->borrarId_tri($cRespPruebasItems);
    // if ($bInsertar){
		$cRespPruebasItems->setUsuAlta($_POST["fIdCandidato"]);
		$cRespPruebasItems->setUsuMod($_POST["fIdCandidato"]);
		$cRespPruebasItemsDB->insertar($cRespPruebasItems);
    //}
	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
	$cRespuestasPruebasItems->setIdEmpresa($_POST["fIdEmpresa"]);
	$cRespuestasPruebasItems->setIdProceso($_POST["fIdProceso"]);
	$cRespuestasPruebasItems->setIdCandidato($_POST["fIdCandidato"]);
	$cRespuestasPruebasItems->setIdPrueba($_POST["fIdPrueba"]);
	$cRespuestasPruebasItems->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
	$cRespuestasPruebasItems->setIdItem($cItem->getIdItem());
	$cRespuestasPruebasItems->setIdOpcion($_POST["fIdOpcion"]);
    $cRespuestasPruebasItems->setId_tri($_POST["fId"]);
	$cRespuestasPruebasItems->setOrden($_POST["forden"]);
	$sqlRespItems = $cRespPruebasItemsDB->readLista($cRespuestasPruebasItems);
	$listaRespItems = $conn->Execute($sqlRespItems);
	$_sValor = "0";
	if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
		$_sValor = 1;
	}else{
		$_sValor = 0;
	}
	//Buscamos que orden de presentación va a tener para este candidato.
	$sSQLOrden = "SELECT orden FROM tri_init_items ";
	$sSQLOrden .= " WHERE";
	$sSQLOrden .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
	$sSQLOrden .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
	$sSQLOrden .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
	$sSQLOrden .= " AND idPrueba='" . $_POST["fIdPrueba"] . "'";
	$sSQLOrden .= " AND id_tri='" . $_POST["fId"] . "'";
	$rsOrden = $conn->Execute($sSQLOrden);

	$sSQLValor = "UPDATE respuestas_pruebas_items SET ";
	$sSQLValor .= " valor=" . $conn->qstr($_sValor, false);
	$sSQLValor .= " ,orden_tri=" . $conn->qstr($rsOrden->fields['orden'], false);
	$sSQLValor .= " WHERE";
	$sSQLValor .= " idEmpresa='" . $_POST['fIdEmpresa'] . "'";
	$sSQLValor .= " AND idProceso='" . $_POST['fIdProceso'] . "'";
	$sSQLValor .= " AND idCandidato='" . $_POST['fIdCandidato'] . "'";
	$sSQLValor .= " AND codIdiomaIso2='" . $_POST['fCodIdiomaIso2'] . "'";
	$sSQLValor .= " AND idPrueba='" . $_POST["fIdPrueba"] . "'";
	$sSQLValor .= " AND idItem='" . $listaRespItems->fields['idItem'] . "'";
	$sSQLValor .= " AND id_tri='" . $_POST["fId"] . "'";
	$conn->Execute($sSQLValor);
?>
<script>
	var elements = document.getElementsByName("fIdOpcion<?php echo $_POST['fId']?>");
	for(var i=0; i< <?php echo $_POST["fnOpciones"]?>;i++){
		elements[i].disabled=false;
	}
</script>
