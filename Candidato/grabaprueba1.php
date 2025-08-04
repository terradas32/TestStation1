<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
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
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


include_once ('include/conexion.php');

	$sUsuario=(!empty($_POST["usuario"])) ? $_POST["usuario"] : "";
	$_POST['sTKCandidatos'] = $sUsuario;

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$sProceso=$_POST["proceso"];
	$sConvocatoria=$_POST["convocatoria"];
	$sSala=$_POST["sala"];
	$sPrueba=$_POST["prueba"];
	$sPantalla=$_POST["pantalla"];
	$sMinutos=$_POST["minutos"];
	$sSegundos=$_POST["segundos"];
	$sMinutos2=$_POST["minutos2"];
	$sSegundos2=$_POST["segundos2"];

	$sCampolibre=$_POST["campolibre"];
	$sFinalizado=$_POST["finalizado"];
	$sItems= '';

	$salida = "proceso='"  . $sProceso .  "'";
	$salida .= "&convocatoria='"  . $sConvocatoria .  "'";
	$salida .= "&sala='"  . $sSala .  "'";
	$salida .= "&prueba='"  . $sPrueba .  "'";
	$salida .= "&usuario='"  . $sUsuario .  "'";
	$salida .= "&pantalla='"  . $sPantalla .  "'";
	$salida .= "&minutos='"  . $sMinutos .  "'";
	$salida .= "&segundos='"  . $sSegundos .  "'";
	$salida .= "&minutos2='"  . $sMinutos2 .  "'";
	$salida .= "&segundos2='"  . $sSegundos2 .  "'";
	$salida .= "&campolibre='"  . $sCampolibre .  "'";
	$salida .= "&finalizado='"  . $sFinalizado .  "'";
//	error_log(date('d/m/Y H:i:s') . " ->\t-*1*->" . $salida . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . "filePruebas/datos.txt");

	if (empty($_POST["proceso"])
		|| empty($_POST["convocatoria"])
		|| empty($_POST["sala"])
		|| empty($_POST["prueba"])
		|| empty($_POST["pantalla"])
		|| $_POST["pantalla"] == ""
		|| $_POST["segundos"] == ""
		|| $_POST["minutos"] == ""
		|| $_POST["finalizado"] == "")
	{
		error_log(date('d/m/Y H:i:s') . " ->\t-**->" . "Error gargando datos - (Faltan datos)." . $salida . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . "filePruebas/datos.txt");
		exit;
	}
	$cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;

	//Recogemos los items
	$cItemsDB = new ItemsDB($conn);
	$cItems = new Items();

   	$cItems->setIdPrueba($sPrueba);
   	$cItems->setIdPruebaHast($sPrueba);
   	$_POST['fCodIdiomaIso2'] = "es";	//Las pruebas FLASH sólo están en español
   	$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$sqlItems= $cItemsDB->readLista($cItems);
   	$listaItems = $conn->Execute($sqlItems);
   	//Miramos si bienen todos los items que se esperan
   	$sEncontrado = "";
   	$sNoEncontrado="";
   	$bOK = true;

	while(!$listaItems->EOF){
		if (!isset($_POST[$listaItems->fields['descripcion']])){
			$sNoEncontrado .= " - " . $listaItems->fields['descripcion'];
			$bOK=false;
		}else {
			if ($_POST[$listaItems->fields['descripcion']] == "undefined"){
                error_log(date('d/m/Y H:i:s') . " ->\t ::" . $listaItems->fields['descripcion'] . " **************************** -->undefined" . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . "filePruebas/datos.txt");
			}
			$sEncontrado .= "&" . $listaItems->fields['descripcion'] . "=" . $_POST[$listaItems->fields['descripcion']];
		}
		$listaItems->MoveNext();
	}

	if (!$bOK){
		//mandamos el error de vuelta a la pagina de login
		error_log(date('d/m/Y H:i:s') . " ->\t-**->" . "Error gargando datos - (Faltan datos)." . $sNoEncontrado . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . "filePruebas/datos.txt");
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "Error gargando datos - (Faltan datos)." . $sNoEncontrado;
		echo '<script   >top.location.replace("' . constant("HTTP_SERVER") . 'msg.php");</script>';
		exit;
	}
	$salida .= $sEncontrado;

   	$cRespuestasPruebas = new Respuestas_pruebas();
	$cRespuestasPruebasDB = new Respuestas_pruebasDB($conn);

	//Seteamos los valores para la entidad Respuestaspruebas
	$cRespuestasPruebas->setIdPrueba($sPrueba);
	$cRespuestasPruebas->setIdProceso($sProceso);
	$cRespuestasPruebas->setIdEmpresa($cCandidato->getIdEmpresa());
	$cRespuestasPruebas->setIdCandidato($cCandidato->getIdCandidato());
	$cRespuestasPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

	$cRespuestasPruebas = $cRespuestasPruebasDB->readEntidad($cRespuestasPruebas);
	if ($cRespuestasPruebas->getDescProceso() == ""){
		$cRespuestasPruebas->setFinalizado($sFinalizado);
		$cRespuestasPruebas->setLeidoInstrucciones(1);
		$cRespuestasPruebas->setLeidoEjemplos(1);
		$cRespuestasPruebas->setMinutos_test($sMinutos);
		$cRespuestasPruebas->setSegundos_test($sSegundos);
		$cRespuestasPruebas->setMinutos2_test($sMinutos2);
		$cRespuestasPruebas->setSegundos2_test($sSegundos2);
		$cRespuestasPruebas->setPantalla($sPantalla);
		$cRespuestasPruebas->setCampoLibre($sCampolibre);
		$cRespuestasPruebas->setCampoLibre($sCampolibre);
		$cRespuestasPruebas->setUsuAlta($cCandidato->getIdCandidato());
		$cRespuestasPruebas->setUsuMod($cCandidato->getIdCandidato());

		$cRespuestasPruebasDB->insertar($cRespuestasPruebas);
	}else{
		//Miro si ya está finalizada en ese caso no la reabro
		if ($cRespuestasPruebas->getFinalizado() == "0"){
			$cRespuestasPruebas->setFinalizado($sFinalizado);
		}
		$cRespuestasPruebas->setMinutos_test($sMinutos);
		$cRespuestasPruebas->setSegundos_test($sSegundos);
		$cRespuestasPruebas->setMinutos2_test($sMinutos2);
		$cRespuestasPruebas->setSegundos2_test($sSegundos2);
		$cRespuestasPruebas->setPantalla($sPantalla);
		$cRespuestasPruebas->setCampoLibre($sCampolibre);
		$cRespuestasPruebas->setCampoLibre($sCampolibre);
		$cRespuestasPruebas->setUsuAlta($cCandidato->getIdCandidato());
		$cRespuestasPruebas->setUsuMod($cCandidato->getIdCandidato());
		$cRespuestasPruebasDB->modificar($cRespuestasPruebas);
	}

	//Guardamos las respuestas de los items
	$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($conn);
	$listaItems->Move(0); //Posicionamos en el primer registro.
	while(!$listaItems->EOF){
		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
		$cRespuestas_pruebas_items->setIdPrueba($sPrueba);
		$cRespuestas_pruebas_items->setIdProceso($sProceso);
		$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
		$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
		$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespuestas_pruebas_items->setIdItem($listaItems->fields['idItem']);
		//Lo borro
		$cRespuestas_pruebas_itemsDB->borrar($cRespuestas_pruebas_items);
		//Ahora lo inserto con el valor
		$cRespuestas_pruebas_items->setIdOpcion(0);
		$cRespuestas_pruebas_items->setCodigo($listaItems->fields['descripcion']);
		$cRespuestas_pruebas_items->setOrden($listaItems->fields['idItem']);
		$cRespuestas_pruebas_items->setValor($_POST[$listaItems->fields['descripcion']]);
		$cRespuestas_pruebas_items->setUsuAlta($cCandidato->getIdCandidato());
		$cRespuestas_pruebas_items->setUsuMod($cCandidato->getIdCandidato());
		$cRespuestas_pruebas_itemsDB->insertarPruebaFLASH($cRespuestas_pruebas_items);

		$listaItems->MoveNext();
	}

//  error_log(date('d/m/Y H:i:s') . " ->\t" . $salida . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . "filePruebas/datos.txt");
  error_log(date('d/m/Y H:i:s') . " ->\t-*1*->IdEmpresa::" . $cCandidato->getIdEmpresa() . " IdCandidato::" . $cCandidato->getIdCandidato() . " ->"  . $salida . "\n", 3, constant("DIR_FS_DOCUMENT_ROOT") . "filePruebas/datos.txt");
?>
