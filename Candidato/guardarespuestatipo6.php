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
    $_OpcionesDB = new OpcionesDB($conn);
    $_Opciones = new Opciones();


    $cItems = new Items();

    // Monto una lista de items con los items ya que mando los parámetros
    // fInicio y fFin y se lo asigno al orden y al ordenHast.

    $cItems->setIdPrueba($_POST["fIdPrueba"]);
    $cItems->setIdPruebaHast($_POST["fIdPrueba"]);
    $cItems->setOrden($_POST["fInicio"]);
    $cItems->setOrdenHast($_POST["fFin"]);
    $cItems->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);

    $sqlItems = $cItemsDB->readLista($cItems);
    $listaItems = $conn->Execute($sqlItems);

    while(!$listaItems->EOF){

    	$cRespPruebasItems = new Respuestas_pruebas_items();

	    $cRespPruebasItems->setIdEmpresa($_POST["fIdEmpresa"]);
		$cRespPruebasItems->setIdProceso($_POST["fIdProceso"]);
		$cRespPruebasItems->setIdCandidato($_POST["fIdCandidato"]);
		$cRespPruebasItems->setIdPrueba($_POST["fIdPrueba"]);
		$cRespPruebasItems->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
		$cRespPruebasItems->setIdItem($listaItems->fields['idItem']);
		$cRespPruebasItems->setOrden($listaItems->fields['orden']);

		$cRespPruebasItems = $cRespPruebasItemsDB->readEntidad($cRespPruebasItems);

		//Miro si el item que estoy mirando en la lista se corresponde al que he modificado en el cuestionario.
		if($cRespPruebasItems->getIdItem() == $_POST['fIdItem']){

			$cRespPruebasItemsDB->borrar($cRespPruebasItems);

			$_Opciones = new Opciones();
			$_Opciones->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
			$_Opciones->setIdPrueba($_POST["fIdPrueba"]);
			$_Opciones->setIdItem($_POST['fIdItem']);
			$_Opciones->setIdOpcion($_POST['fIdOpcion']);
			$_Opciones = $_OpcionesDB->readEntidad($_Opciones);

			$cRespPruebasItems->setIdOpcion($_POST["fIdOpcion"]);
			$cRespPruebasItems->setDescOpcion($_Opciones->getDescripcion());
			$cRespPruebasItems->setCodigo($_Opciones->getCodigo());
			$cRespPruebasItems->setUsuAlta($_POST["fIdCandidato"]);
			$cRespPruebasItems->setUsuMod($_POST["fIdCandidato"]);
			$cRespPruebasItemsDB->insertar($cRespPruebasItems);

		}else{

			$_Opciones = new Opciones();
			$_Opciones->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
			$_Opciones->setIdPrueba($_POST["fIdPrueba"]);
			$_Opciones->setIdItem($_POST['fIdItem']);
			$_Opciones->setIdOpcion($_POST['fIdOpcion']);
			$_Opciones = $_OpcionesDB->readEntidad($_Opciones);

			//Si no coinciden miro a ver si ya habia dada de alta esa misma opción para este grupo de items
			if($cRespPruebasItems->getCodigo() == $_Opciones->getCodigo()){

				//Si la hay le doy el valor 0 y borro e inserto
				$cRespPruebasItems->setIdOpcion($_POST["fIdOpcion"]);
				$cRespPruebasItemsDB->borrar($cRespPruebasItems);

				$_Opciones = new Opciones();
				$_Opciones->setCodIdiomaIso2($_POST["fCodIdiomaIso2"]);
				$_Opciones->setIdPrueba($_POST["fIdPrueba"]);
				$_Opciones->setIdItem($_POST['fIdItem']);
				$_Opciones->setIdOpcion($_POST['fIdOpcion']);
				$_Opciones = $_OpcionesDB->readEntidad($_Opciones);

				$cRespPruebasItems->setIdOpcion($_POST["fIdOpcion"]);
				$cRespPruebasItems->setDescOpcion($_Opciones->getDescripcion());
				$cRespPruebasItems->setCodigo($_Opciones->getCodigo());
				$cRespPruebasItems->setUsuAlta($_POST["fIdCandidato"]);
				$cRespPruebasItems->setUsuMod($_POST["fIdCandidato"]);
				$cRespPruebasItemsDB->insertar($cRespPruebasItems);
			}else{
				//Si no es el caso, pregunto si no hay opción, por lo tanto no existe el registro
				// y lo doy de alta con idOpcion 0
				if($cRespPruebasItems->getIdOpcion() == ""){
					$cRespPruebasItems->setIdOpcion("0");
					$cRespPruebasItemsDB->borrar($cRespPruebasItems);
					$cRespPruebasItems->setUsuAlta($_POST["fIdCandidato"]);
					$cRespPruebasItems->setUsuMod($_POST["fIdCandidato"]);
					$cRespPruebasItemsDB->insertar($cRespPruebasItems);
				}
				// Si no, no hago nada puesto que tiene que haber una de cada en cada grupo de items
			}

		}
		usleep(250000);
		$listaItems->MoveNext();
    }
?>
