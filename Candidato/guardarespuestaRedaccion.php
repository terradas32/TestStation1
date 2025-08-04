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
	$cPruebasDB = new PruebasDB($conn);
    $cItemsDB = new ItemsDB($conn);


    $cRespPruebasItems = new Respuestas_pruebas_items();

    $cRespPruebasItems->setIdEmpresa($_POST["fIdEmpresa"]);
	$cRespPruebasItems->setIdProceso($_POST["fIdProceso"]);
	$cRespPruebasItems->setIdCandidato($_POST["fIdCandidato"]);
	$cRespPruebasItems->setIdPrueba($_POST["fIdPrueba"]);
	$cRespPruebasItems->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
	$cRespPruebasItems->setIdItem($_POST["fIdItem"]);
	$cRespPruebasItems->setIdOpcion($_POST["fIdOpcion"]);
	$cRespPruebasItems->setOrden($_POST["forden"]);
	$cRespPruebasItems->setValor($_POST["fValor"]);

//	echo $_POST["fNOInsertatOpcion"] . "<br />";
	$bInsertar =  (isset($_POST["fNOInsertatOpcion"]) && $_POST["fNOInsertatOpcion"] == "true") ? false : true ;
//	echo "<br />-->" . $bInsertar . "<br />";
//    echo "<br />-->" . $_POST["fIdEmpresa"];
//	echo "<br />-->" . $_POST["fIdProceso"];
//	echo "<br />-->" . $_POST["fIdCandidato"];
//	echo "<br />-->" . $_POST["fIdPrueba"];
//	echo "<br />-->" . $_POST["fCodIdiomaIso2"];
//	echo "<br />-->" . $_POST["fIdItem"];
//	echo "<br />-->" . $_POST["fIdOpcion"];
//	echo "<br />-->" . $_POST["forden"];
//	echo "<br />-->" . $_POST["fValor"];

    $cRespPruebasItemsDB->borrar($cRespPruebasItems);
    if ($bInsertar){
//    	echo "INSERTO";
		$cRespPruebasItems->setUsuAlta($_POST["fIdCandidato"]);
		$cRespPruebasItems->setUsuMod($_POST["fIdCandidato"]);
		$cRespPruebasItemsDB->insertar($cRespPruebasItems);
    }
?>
<script>
	var elements = document.getElementsByName("fIdOpcion<?php echo $_POST['fIdItem']?>");
	for(var i=0; i< <?php echo $_POST["fnOpciones"]?>;i++){
		elements[i].disabled=false;
	}
</script>
