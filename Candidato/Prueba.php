<?php

header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	$_REQUEST["fLang"] = $_REQUEST["fCodIdiomaIso2"];
	include('include/Idiomas.php');
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
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Pruebas_ayudas/Pruebas_ayudasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas_ayudas/Pruebas_ayudas.php");
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


	include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");



	$cPruebas_ayudasDB = new Pruebas_ayudasDB($conn);
	$cRespPruebasDB = new Respuestas_pruebasDB($conn);

    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;

	//Sacamos la información del proceso
	$cProcesosDB	= new ProcesosDB($conn);
	$cProcesos	= new Procesos();
	$cProcesos->setIdEmpresa($cCandidato->getIdEmpresa());
	$cProcesos->setIdProceso($cCandidato->getIdProceso());
	$cProcesos = $cProcesosDB->readEntidad($cProcesos);

    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);
    $cPruebasDB = new PruebasDB($conn);
    $cItemsDB = new ItemsDB($conn);

    $cPruebas = new Pruebas();

    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

	$cPruebas = $cPruebasDB->readEntidad($cPruebas);

	$sPreguntasPorPagina = $cPruebas->getPreguntasPorPagina();
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();

	$aPreguntasPorPagina = explode("-", $sPreguntasPorPagina);
	$iPreguntasPorPagina = count($aPreguntasPorPagina);

	$bMultiPagina = false;
	if ($iPreguntasPorPagina > 1){
		//Quiere decir que le llegan en formato 5-6-5-5-5-4-6-4 por ejemplo las preguntas por página
		$bMultiPagina = true;
	}else{
		if($sPreguntasPorPagina < 1){
			$sPreguntasPorPagina = 1;
		}
	}

	$iLineas = $sPreguntasPorPagina;
    if (empty($_POST["fPaginaSel"])){
    	$_POST["fPaginaSel"]=1;
    }
	if ($bMultiPagina){
		$iLineas = $aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
	}

    $cProceso_pruebas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cProceso_pruebas->setIdProceso($cCandidato->getIdProceso());

    $cRespPruebas = new Respuestas_pruebas();
    $cRespPruebas->setIdProceso($_POST['fIdProceso']);
    $cRespPruebas->setIdEmpresa($_POST['fIdEmpresa']);
    $cRespPruebas->setIdCandidato($_POST['fIdCandidato']);
    $cRespPruebas->setIdPrueba($_POST['fIdPrueba']);
    $cRespPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

    $cRespPruebas = $cRespPruebasDB->readEntidad($cRespPruebas);

    if($cRespPruebas->getFinalizado()==""){
    	$cRespPruebas->setFinalizado("0");
    	$cRespPruebas->setLeidoEjemplos("0");
    	$cRespPruebas->setLeidoInstrucciones("0");
			$cRespPruebas->setUsuAlta($_POST["fIdCandidato"]);
			$cRespPruebas->setUsuMod($_POST["fIdCandidato"]);
    	$cRespPruebasDB->insertar($cRespPruebas);
    }

    $leidoIns=false;
	$leidoEjemplos=false;

	if(isset($_POST['fEjemplosLeidos']) && $_POST['fEjemplosLeidos']!=""){
    	$cRespPruebas->setLeidoEjemplos("1");
			$cRespPruebas->setUsuAlta($_POST["fIdCandidato"]);
			$cRespPruebas->setUsuMod($_POST["fIdCandidato"]);
    	$cRespPruebasDB->modificar($cRespPruebas);
    }else{

	    if($cRespPruebas->getLeidoInstrucciones()== 0){
	    	$leidoIns=true;
	    }else{
	    	if($cRespPruebas->getLeidoEjemplos() == 0){
	    		$leidoEjemplos=true;
	    	}
	    }
    }
    $sqlProcPruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
    $listaProcesosPruebas = $conn->Execute($sqlProcPruebas);

	$cItemListar = new Items();
	$oItemDB = new ItemsDB($conn);

	$cItemListar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$sTRIError= "";
	$extension=".csv";
	$filename = constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . "imgItems/" . $cPruebas->getCodIdiomaIso2() . '_' . $cPruebas->getIdTipoRazonamiento() . $extension;

	$sIndex_tri = "";
	$sFinalizaTRI="";
	if ($cPruebas->getIdTIpoPrueba() == "20"){
		$cItemListar->setIdTipoRazonamiento($cPruebas->getIdTipoRazonamiento());
		$cItemListar->setOrderBy("id");
		$cItemListar->setOrder("ASC");
		//TRI Comprobamos que tenemos los ficheros de TRI
		if (!file_exists($filename)){
			if (!generaTRIFile($cItemListar, $cItemsDB, $filename)){
				$sTRIError .= "<br /><b>Error 404 cargando preguntas \"R\" tipo: ". basename($filename, $extension) . "</b>";
				$sTRIError .= "<br />";
				$sTRIError .= "<br />";
				$sTRIError .= "<br />Contacte con quien le ha proporcionado el acceso.";
				echo $sTRIError;
				exit;
			}
		}
		
		//Miramos Si hay que llamar o ya hemos llamado al script de R
		$sSQL = 'SELECT * FROM tri_init_items WHERE
				idEmpresa   = ' . $_POST['fIdEmpresa'] . 
			' AND idProceso   = ' . $_POST['fIdProceso'] .
			' AND idCandidato = ' . $_POST['fIdCandidato'] .
			' AND idPrueba    = ' . $_POST['fIdPrueba'] . ' ORDER BY orden ASC';

		$rsiniciado_tri = $conn->Execute($sSQL);
		if ($rsiniciado_tri->recordCount() <= 0)
		{
			//No hay items asignados, NO hemos llamado al script inicial, le llamamos
			$response = array();
			$aParams = array();
			$tipo_razonamiento = $cPruebas->getIdTipoRazonamiento();
			$aParams[0] = "\"" . $filename . "\"";
			$aParams[1] = $tipo_razonamiento;
			$sRcommand = "Rscript " . constant("DIR_FS_DOCUMENT_ROOT") . "TRI/initItem.r " . implode(" ", $aParams);
			exec($sRcommand, $response);
			$init_items = array();
			if (!empty($response)){	
				$sResponse = str_replace("[1] ", "", trim($response[1]));
				$sResponse = str_replace("[1]", "", trim($sResponse));
				$sItems= str_replace(" ", ",", trim($sResponse ));
				$sItems= str_replace(",,", ",", trim($sItems));
				if (substr($sItems, 0, 1) === ','){
					$sItems= substr($sItems, 1);
				}
				$aItems = explode(",",$sItems);
				if (count($aItems) < 3 ){
					$sTRIError .= "<br /><b>Error cargando preguntas iniciales [" . count($aItems) . "] \"R\" tipo: ". basename($filename, $extension) . "</b>";
					$sTRIError .= "<br />";
					$sTRIError .= "<br />";
					$sTRIError .= "<br />Contacte con quien le ha proporcionado el acceso.";
					echo $sTRIError;
					exit;
				}else{
					//$init_items = $aItems;
					//Guardamos los items en la tabla temporal tri_init_items
					for ($i=0, $max = sizeof($aItems); $i < $max; $i++){
						$oItemL = new Items();
						$oItemL->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
						$oItemL->setIdTipoRazonamiento($cPruebas->getIdTipoRazonamiento());
						$oItemL->setIndex_tri($aItems[$i]);
						$sqlItemL = $oItemDB->readLista($oItemL);
						$rsItem = $conn->Execute($sqlItemL);
						$id_item="";	
						while (!$rsItem->EOF) {
							$id_item = $rsItem->fields['id'];
							$rsItem->MoveNext();
						}
						$sSQL = 'INSERT INTO tri_init_items (
						idEmpresa,
						idProceso,
						idCandidato,
						idPrueba,
						index_tri,
						id_tri,
						orden) VALUES (' . 
						$_POST['fIdEmpresa'] . ',' .
						$_POST['fIdProceso']  . ',' .
						$_POST['fIdCandidato']  . ',' .
						$_POST['fIdPrueba'] . ',' .
						$aItems[$i] . ',' .
						$id_item . ',' .
						($i+1) . ');
						';
						//Nos guardamos el primer item por el que empezamos
						if ($i == 0){
							$init_items[] = $aItems[$i];
						}
						$conn->Execute($sSQL);
					}
				}
			}else{
				$sTRIError .= "<br /><b>Error llamada \"R\" tipo: ". basename($filename, $extension) . "</b>";
				$sTRIError .= "<br />";
				$sTRIError .= "<br />";
				$sTRIError .= "<br />Contacte con quien le ha proporcionado el acceso.";
				echo $sTRIError;
				exit;
			}
			$sIndex_tri = implode(",", $init_items);
			$cItemListar->setIndex_tri(implode(",", $init_items));
		}else{
			//Ya tenemos asignados items, sacamos el siguiente no contestado.
			$items_contestados=0;
			while (!$rsiniciado_tri->EOF)
			{
				//Miramos si ha sido sido contestado
				$sSQL = 'SELECT * FROM respuestas_pruebas_items WHERE
					idEmpresa   = ' . $_POST['fIdEmpresa'] . 
					' AND idProceso   = ' . $_POST['fIdProceso'] .
					' AND idCandidato = ' . $_POST['fIdCandidato'] .
					' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
					' AND id_tri    = ' . $rsiniciado_tri->fields['id_tri'] . '';
				$rsRPI = $conn->Execute($sSQL);
				if ($rsRPI->recordCount() <= 0){
					//primero sin contestar
					$cItemListar->setIndex_tri($rsiniciado_tri->fields['index_tri']);
					$sIndex_tri = $rsiniciado_tri->fields['index_tri'];
					break;
				}else{
					//Sí lo ha contestado 
					$items_contestados++;
				}
				$rsiniciado_tri->MoveNext();
			}
			if ($items_contestados >= 3 && empty($sIndex_tri) )
			{
				// Ha contestado los iniciales y NO tiene ninguno pendiente de contestar.
				// Hay que llamar al script R nextItem.r 
				$num_preguntas_max = $cPruebas->getNum_preguntas_max_tri();

				//#[1] -> Nombre del fichero generado con el Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI
				//#se sustituye por el nombre de fichero ejemplo: pathTo "2.csv" para Verbal
				//#[2] -> in_respuestas - Array de aciertos y errores del tipo [1,1,0,1,0,0,1] - aciertos = 1 y fallos = 0
				//#[3] -> in_respuestas_index - Array de indice de respuestas de la matriz del tipo [2,14,20,31,40,35,27] (fila del csv o Excel)
				//#[4] -> num_preguntas_max número de preguntas máximas de la prueba para TRI.
				
				//Sacamos todos los contestados en orden_tri
				$sSQL = 'SELECT * FROM respuestas_pruebas_items WHERE
				idEmpresa   = ' . $_POST['fIdEmpresa'] . 
				' AND idProceso   = ' . $_POST['fIdProceso'] .
				' AND idCandidato = ' . $_POST['fIdCandidato'] .
				' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
				' ORDER BY orden_tri ASC'  
				;
				$rsRPI = $conn->Execute($sSQL);

				$response = array();
				$aParams = array();
				$tipo_razonamiento = $cPruebas->getIdTipoRazonamiento();
				$in_respuestas = "";
				$in_respuestas_index = "";
				while (!$rsRPI->EOF) {
					$in_respuestas .= "," . $rsRPI->fields['valor'];
					$in_respuestas_index .= "," . $rsRPI->fields['index_tri'];
					$rsRPI->MoveNext();
				}
				if (!empty($in_respuestas)){
					$in_respuestas = substr($in_respuestas, 1);
				}
				if (!empty($in_respuestas_index)){
					$in_respuestas_index = substr($in_respuestas_index, 1);
				}
				$aParams[0] = "\"" . $filename . "\"";
				$aParams[1] = $in_respuestas;
				$aParams[2] = $in_respuestas_index;
				$aParams[3] = $num_preguntas_max;
				$aParams[4] = $tipo_razonamiento;
				$sRcommand = "Rscript " . constant("DIR_FS_DOCUMENT_ROOT") . "TRI/nextItem.r " . implode(" ", $aParams);
				exec($sRcommand, $response);
				$sResponse = implode(",", $response);
				$findme = '$item';
				$pos = strpos($sResponse, $findme);
				if ($pos === false){
					//Guardamos la nota en respuestas_pruebas
					//y finalizamos la prueba
					$nota = str_replace("[1] ", "", trim($sResponse));
					$nota = str_replace("[1]", "", trim($nota));
					$SQL = "UPDATE respuestas_pruebas ";
					$SQL .= "SET nota_tri= " . $nota . " ";
					$SQL .= "WHERE idEmpresa= " . $_POST['fIdEmpresa'] . " ";
					$SQL .= "AND idProceso= " . $_POST['fIdProceso'] . " ";
					$SQL .= "AND idCandidato= " . $_POST['fIdCandidato'] . " ";
					$SQL .= "AND idPrueba= " . $_POST['fIdPrueba'] . " ";
					$rs = $conn->Execute($SQL);
					$sFinalizaTRI = '
					$(function (){
						terminarTRI();
						});
						';
					?>
				<?php
				}else{
					$sItems = str_replace("[1] ", "", trim($response[1]));
					$sItems = str_replace("[1]", "", trim($sItems));
					if (!is_numeric($sItems)){
						$sTRIError .= "<br /><b>Error cargando siguiente pregunta [" . $sResponse . "] \"R\" tipo: ". basename($filename, $extension) . "</b>";
						$sTRIError .= "<br />";
						$sTRIError .= "<br />";
						$sTRIError .= "<br />Contacte con quien le ha proporcionado el acceso.";
						echo $sTRIError;
						exit;
					}else{
						$aItems = explode(",",$sItems);
						//Guardamos el item en la tabla temporal tri_init_items
						for ($i=0, $max = sizeof($aItems); $i < $max; $i++){
							$oItemL = new Items();
							$oItemL->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
							$oItemL->setIdTipoRazonamiento($cPruebas->getIdTipoRazonamiento());
							$oItemL->setIndex_tri($aItems[$i]);
							$sqlItemL = $oItemDB->readLista($oItemL);
							$rsItem = $conn->Execute($sqlItemL);
							$id_item="";	
							while (!$rsItem->EOF) {
								$id_item = $rsItem->fields['id'];
								$rsItem->MoveNext();
							}
							$sSQL = 'SELECT max(orden)+1 AS orden FROM tri_init_items WHERE
							idEmpresa   = ' . $_POST['fIdEmpresa'] . 
							' AND idProceso   = ' . $_POST['fIdProceso'] .
							' AND idCandidato = ' . $_POST['fIdCandidato'] .
							' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
							' '  
							;
							$rsOrden = $conn->Execute($sSQL);
							$orden="";	
							while (!$rsOrden->EOF) {
								$orden = $rsOrden->fields['orden'];
								$rsOrden->MoveNext();
							}
							$sSQL = 'INSERT INTO tri_init_items (
							idEmpresa,
							idProceso,
							idCandidato,
							idPrueba,
							index_tri,
							id_tri,
							orden) VALUES (' . 
							$_POST['fIdEmpresa'] . ',' .
							$_POST['fIdProceso']  . ',' .
							$_POST['fIdCandidato']  . ',' .
							$_POST['fIdPrueba'] . ',' .
							$aItems[$i] . ',' .
							$id_item . ',' .
							$orden . ');
							';
							//Nos guardamos el primer item por el que empezamos
							if ($i == 0){
								$init_items[] = $aItems[$i];
							}
							$conn->Execute($sSQL);
						}
						$sIndex_tri = implode(",", $init_items);
						$cItemListar->setIndex_tri(implode(",", $init_items));
					}
				}
			}
		}
			
	}else{
		$cItemListar->setIdPrueba($_POST['fIdPrueba']);
		if ($_POST['fIdPrueba'] == "84"){	//MB CCT
			$cItemListar->setTipoItem($cCandidato->getEspecialidadMB());
		}
		$cItemListar->setOrderBy("orden");
		$cItemListar->setOrder("ASC");
	}


    $sqlItems = $cItemsDB->readLista($cItemListar);
    $listaItems = $conn->Execute($sqlItems);

    $iTamaniolistaItems = $listaItems->recordCount();
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");

    if ($bMultiPagina){
    	$iPaginas = $iPreguntasPorPagina;	//Contador del array Multipágina
    }else{
    	$iPaginas = $iTamaniolistaItems / $sPreguntasPorPagina;
    	if($iTamaniolistaItems % $sPreguntasPorPagina !=0){
			$iPaginas = intval($iPaginas) + 1;
		}
    }
	$b7=false;
	$bBtnAtrasMostrar=true;
	$bBtnBuscarPrimeraSinResponder=true;
	$sStyleMostrarPreguntas = '';
	$sValidarPantalla = '';
	$sFinalizarPrueba = 'terminar();';

	//1 -->360º
	//2 -->Aptitudes
	//3 -->Competencias
	//4 -->Estilo de Aprendizaje
	//5 -->Inglés
	//9 -->Intereses Profesionales
	//6 -->Motivaciones
	//7 -->Personalidad
	//8 -->Varias
	//20 TRI
	switch ($cPruebas->getIdTIpoPrueba())
	{
		case "2":	//2 -->Aptitudes
		case "5":	//5 -->Ingles ELT
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
			break;
		case "3":	//3 -->Competencias
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;
			$sValidarPantalla = "valida1Opcion('" . $sPreguntasPorPagina . "');";
			break;
		case "6":	//6 -->Motivaciones
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;

	    	$cOpciones = new Opciones();
			$cOpcionesDB = new OpcionesDB($conn);
			$cOpciones->setIdPrueba($_POST['fIdPrueba']);
			$cOpciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cOpciones->setIdItem(1);
			$cOpciones->setBajaLog("0");
			$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
			$listaOpciones = $conn->Execute($sqlOpciones);
			$i_Opciones = $listaOpciones->recordCount();
			$sOpciones = '';
			while(!$listaOpciones->EOF){
				$sOpciones .= ',' . $listaOpciones->fields['descripcion'];
				$listaOpciones->MoveNext();
			}
			if (!empty($sOpciones)){
				$sOpciones = substr($sOpciones,1);
			}
			if ($bMultiPagina){
				$sPxP = "document.forms[0].fPaginaSel.value";
			}else{
				$sPxP = "'" . $sPreguntasPorPagina . "'";
			}

			$sValidarPantalla = "mejorpeor(" . $sPxP . ", '" . $sOpciones . "');";
			break;
		case "7":	//7 -->Personalidad
	    	$b7=true;
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;

	    	$cOpciones = new Opciones();
			$cOpcionesDB = new OpcionesDB($conn);
			$cOpciones->setIdPrueba($_POST['fIdPrueba']);
			$cOpciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cOpciones->setIdItem(1);
			$cOpciones->setBajaLog("0");
			$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
			$listaOpciones = $conn->Execute($sqlOpciones);
			$i_Opciones = $listaOpciones->recordCount();
			$sOpciones = '';
			while(!$listaOpciones->EOF){
				$sOpciones .= ',' . $listaOpciones->fields['descripcion'];
				$listaOpciones->MoveNext();
			}
			if (!empty($sOpciones)){
				$sOpciones = substr($sOpciones,1);
			}
			if ($bMultiPagina){
				$sPxP = "document.forms[0].fPaginaSel.value";
			}else{
				$sPxP = "'" . $sPreguntasPorPagina . "'";
			}
			if ($cPruebas->getIdPrueba() == "11" ||
				$cPruebas->getIdPrueba() == "5" ||
				$cPruebas->getIdPrueba() == "22"){
				$sValidarPantalla = "validaMejor(" . $sPxP . ", '" . $sOpciones . "');";
			}else{
				$sValidarPantalla = "mejorpeor(" . $sPxP . ", '" . $sOpciones . "');";
			}
			break;
		case "9":	//9 -->Intereses Profesionales CIP / SOP
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
	    	$bBtnAtrasMostrar=false;
	    	$bBtnBuscarPrimeraSinResponder=false;
			$sValidarPantalla = "validaTipo9('" . $sPreguntasPorPagina . "');";
			break;
		case "8":	//8 -->Varias
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
			break;
		case "10":	//10 -->Redacción
	    	$sStyleMostrarPreguntas = 'style="display:none;"';
			$bBtnAtrasMostrar=true;
			$bBtnBuscarPrimeraSinResponder=false;
			//$sValidarPantalla = "validaTipo9('" . $sPreguntasPorPagina . "');";
			$sFinalizarPrueba = 'terminarRedacion();';
			$sValidarPantalla = '';
			break;
		case "14":	//14 -->CUESTIONARIO TRAYECTORIA PROFESIONAL
			$bBtnAtrasMostrar=true;
			$bBtnBuscarPrimeraSinResponder=false;
			$sStyleMostrarPreguntas = 'style="display:none;"';
			$sValidarPantalla = '';
			break;
		case "20":	//TRI
			$bBtnAtrasMostrar=false;
			$bBtnBuscarPrimeraSinResponder=false;
			$sStyleMostrarPreguntas = 'style="display:none;"';
			$sValidarPantalla = "valida1Opcion('" . $sPreguntasPorPagina . "');";
			break;
	
		default:
			break;
	} // end switch

    $cPruebas_ayudas = new Pruebas_ayudas();
    $cPruebas_ayudas->setIdPrueba($cPruebas->getIdPrueba());
    $cPruebas_ayudas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cPruebas_ayudas->setIdAyuda("1");
    $cPruebas_ayudas = $cPruebas_ayudasDB->readEntidad($cPruebas_ayudas);

    $sTime="";
    if($cRespPruebas->getMinutos_test() !="" && $cRespPruebas->getSegundos_test() !=""){
    	$sTime = ($cRespPruebas->getMinutos_test()*60) + $cRespPruebas->getSegundos_test();
    }else{
    	$sTime=0;
    }
    $sDisplay= '';
    //Si la prueba no tiene tiempo,
    //ahora resulta que hay que saber lo que a tardado,
    //Se le asigna 60min que es el tiempo máximo ya que el
    //Cronometro no tiene horas
    //Le ponemos al cronometro horas y le establecemos 8h
    if($cPruebas->getDuracion() == 0){
		$cPruebas->setDuracion(480);
		$sDisplay= 'display:none;';
	}
	echo ("<script>console.log('eyyy :: ". $cRespPruebas->getMinutos_test().":::".$cRespPruebas->getSegundos_test().";;". $cPruebas->getDuracion() ."');</script>");
    $segundos=$cPruebas->getDuracion()*60 - $sTime;

?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/apple-overlay.css" type="text/css" />
    <link media="screen" rel="stylesheet" type="text/css" href="estilos/jquery.epiclock.css"/>
    <link media="screen" rel="stylesheet" type="text/css" href="estilos/epiclock.retro-countdown.css"/>
     <script src="codigo/comun.js"></script>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/eventos.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jQuery1.4.2.js"></script>
	 <script src="codigo/jquery.tools.min.js"></script>
     <script src="codigo/jquery.dateformat.min.js"></script>
     <script src="codigo/jquery.epiclock.min.js"></script>
     <script src="codigo/epiclock.retro-countdown.min.js"></script>

<script   >
//<![CDATA[

//Cargo dos variables:
//segundos que lo utilizo para guardar cada cierto tiempo.
//segundosActuales que lo utilizo para llevar un contador actualizado cada segundo de el tiempo
var segundos = <?php echo $segundos?>;
var segundosActuales =<?php echo $segundos?>;
<?php
// bAnt nos indica en pruebas que permite blancos si fue pulsada ya la opción, en ese caso
// No se inserta, solo se borra.
?>
Array.prototype.in_array=function(){
    for(var j in this){
        if(this[j]==arguments[0]){
            return true;
        }
    }
    return false;
}
function cargaListening(){
	var aListening=['58','59','60'];	//KPMG
	if(aListening.in_array(<?php echo $cPruebas->getIdPrueba();?>)){
		var f = document.forms[0];
		var paginacargada = "cargaAudio.php";
		$("div#audio").load(paginacargada,{
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" });
	}
}
function setListening(){
	var aListening=['58','59','60'];	//KPMG
	if(aListening.in_array(<?php echo $cPruebas->getIdPrueba();?>)){
		var f = document.forms[0];
		var paginacargada = "grabaprueba.php";
		$("div#status").load(paginacargada,{
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fIdEmpresa: f.fIdEmpresa.value,
			fIdProceso: f.fIdProceso.value,
			fIdCandidato: f.fIdCandidato.value,
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" });
	}
}
function abrirVentana(bImg, file){
	var f = document.forms[0];
	var paginacargada = "Audio.php";
	$("div#audio").load(paginacargada,{
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fIdEmpresa: f.fIdEmpresa.value,
		fIdProceso: f.fIdProceso.value,
		fIdCandidato: f.fIdCandidato.value,
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>" });
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
}
var bAnt="";

function rediInst(){
	document.forms[0].action = 'instrucciones.php';
	document.forms[0].submit();
}
function rediEjemplos(){
	document.forms[0].action = 'ejemplos.php';
	document.forms[0].submit();
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	msg +=vNumber("<?php echo constant("STR_EDAD");?>:",f.fIdEdad.value,11,true);
	msg +=vString("<?php echo constant("STR_SEXO");?>:",f.fIdSexo.value,11,true);
	msg +=vString("<?php echo constant("STR_NIVEL_ACTUAL");?>:",f.fIdNivel.value,255,true);
	msg +=vString("<?php echo constant("STR_AREA");?>:",f.fIdArea.value,11,true);
	msg +=vString("<?php echo constant("STR_FORMACION_ACADEMICA");?>:",f.fIdFormacion.value,255,true);
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
        lon();
		return true;
	}else	return false;
}

function ocultomuestro(atras, siguiente, fin){

	var displayAtras ="";
	var displaySiguiente ="";
	var displayFin ="";
	var displayBusca ="";

	if(atras == 0){
		displayAtras = 'none';
	}else{
		if(atras == 1){
			displayAtras = 'block';
		}
	}
	if(siguiente == 0){
		displaySiguiente = 'none';
	}else{
		if(siguiente == 1){
			displaySiguiente = 'block';
		}
	}
	if(fin == 0){
		displayFin = 'none';
	}else{
		if(fin == 1){
			displayFin = 'block';
		}
	}


	document.getElementById("divatras").style.display = displayAtras;
	document.getElementById("divsiguiente").style.display = displaySiguiente;
	document.getElementById("divfin").style.display = displayFin;

}
<?php
if ($bMultiPagina){
	echo "var aPreguntasPorPagina=new Array();\n\t";
	$i=0;
	while($i < $iPaginas){
		echo "aPreguntasPorPagina[" . $i . "]=" . $aPreguntasPorPagina[$i] . ";\n\t";
		$i++;
	}
}
?>
function veapregunta(){
	toggleClock();
	lon();
	var f = document.forms[0];

	f.fOrden.value = f.fPreguntas.value;
	f.fPaginaSel.value = parseInt(f.fPreguntas.selectedIndex) + parseInt(1);
	var paginacargada = "cuerpoprueba.php";
	$("div#cuerpoprueba").show().load(paginacargada,{
		fOrden:f.fPreguntas.value,
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		loff();
		toggleClock();
	}).fadeIn("slow");
}
function toggleClock(){
	$(function (){
		$.epiclock('#countdown-retro').toggle();
	});
}
function siguiente(){
	toggleClock();
	lon();
	var f = document.forms[0];
	var orden ="";
	if(f.fOrden.value=="" || f.fOrden.value== 1){
		orden = parseInt(1) + parseInt(<?php echo $iLineas;?>);
	}else{
	<?php
	if ($bMultiPagina){
		echo "var iAnterior=0;\n\t";
		echo "for(var i=0; i < f.fPaginaSel.value; i++){\n\t";
		echo "		iAnterior = iAnterior + aPreguntasPorPagina[i];\n\t";
		echo "}\n\t";
	?>
		orden = parseInt(iAnterior+1);
	<?php
	}else{
	?>
		orden = parseInt(f.fOrden.value) + parseInt(<?php echo $iLineas;?>);
	<?php
	 }
	?>
	}
	f.fOrden.value=orden;
	f.fPaginaSel.value = parseInt(f.fPaginaSel.value) + parseInt(1);
	f.fPreguntas.selectedIndex =  parseInt(f.fPaginaSel.value)- parseInt(1);
	var paginacargada = "cuerpoprueba.php";
	var idTipoPrueba = "<?php echo $cPruebas->getIdTipoPrueba();?>";
	if (idTipoPrueba == "20"){
		paginacargada = "cuerpopruebaTRI.php"; 
		//TRI
		$("div#cuerpoprueba").show().load(paginacargada,{
			fOrden:orden,
			fPaginaSel:f.fPaginaSel.value,
			fIdEmpresa:f.fIdEmpresa.value,
			fIdProceso:f.fIdProceso.value,
			fIdCandidato:f.fIdCandidato.value,
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fIdTipoRazonamiento:"<?php echo $cPruebas->getIdTipoRazonamiento();?>",
			fIndex_tri:f.fIndex_tri.value,
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
		},function(){
			loff();
			toggleClock();
		}).fadeIn("slow");
	}else{
		$("div#cuerpoprueba").show().load(paginacargada,{
			fOrden:orden,
			fPaginaSel:f.fPaginaSel.value,
			fIdEmpresa:f.fIdEmpresa.value,
			fIdProceso:f.fIdProceso.value,
			fIdCandidato:f.fIdCandidato.value,
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
		},function(){
			loff();
			toggleClock();
		}).fadeIn("slow");
	}

}
function anterior(){
	toggleClock();
	lon();
	var f = document.forms[0];
	var orden ="";

	if(f.fOrden.value=="" || f.fOrden.value== 1){
		orden = 1;
	}else{
		<?php
		if ($bMultiPagina){
			echo "var iAnterior=0;\n\t";
			echo "var sPS = (parseInt(f.fPaginaSel.value)-1);";
			echo "var resta = 0;";
			echo "for(var i=0; i < f.fPaginaSel.value; i++){\n\t";
			//echo "alert(aPreguntasPorPagina[i]);";
			echo "	if (i == sPS){\n\t";
			//echo "		alert(f.fPaginaSel.value);";
			echo "		resta= aPreguntasPorPagina[i-1];";
			echo "		break;\n\t";
			echo "	}else{\n\t";
			echo "		iAnterior = iAnterior + aPreguntasPorPagina[i];\n\t";
			echo "	}\n\t";
			echo "}\n\t";
		?>
			//alert(f.fOrden.value);
			//alert(resta);
			orden = parseInt(f.fOrden.value) - parseInt(resta);
		<?php
		}else{
		?>
			orden = parseInt(f.fOrden.value) - parseInt(<?php echo $iLineas;?>);
		<?php
		 }
		?>

	}

	f.fPaginaSel.value = parseInt(f.fPaginaSel.value) - parseInt(1);
	f.fPreguntas.selectedIndex =  parseInt(f.fPaginaSel.value)- parseInt(1);
	f.fOrden.value=orden;

	var paginacargada = "cuerpoprueba.php";
//	alert(f.fPaginaSel.value);
	$("div#cuerpoprueba").show().load(paginacargada,{
		fOrden:orden,
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		loff();
		toggleClock();
	}).fadeIn("slow");
}
function cargapregunta(){
	toggleClock();
	lon();
	var f = document.forms[0];

	var paginacargada = "cuerpoprueba.php";
	$("div#cuerpoprueba").load(paginacargada,{
		fPaginas:<?php echo $iPaginas?>,
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		loff();
		toggleClock();
	}).fadeIn("slow");
}

function buscaprimera(){
	toggleClock();
	lon();
	var f = document.forms[0];
	var paginacargada = "cuerpoprueba.php"; 
	var idTipoPrueba = "<?php echo $cPruebas->getIdTipoPrueba();?>";
	if (idTipoPrueba == "20"){
		paginacargada = "cuerpopruebaTRI.php"; 
		//TRI
		$("div#cuerpoprueba").show().load(paginacargada,{
			fBuscaPrimera:"1",
			fIdEmpresa:f.fIdEmpresa.value,
			fIdProceso:f.fIdProceso.value,
			fIdCandidato:f.fIdCandidato.value,
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fIdTipoPrueba:"<?php echo $cPruebas->getIdTipoPrueba();?>",
			fIdTipoRazonamiento:"<?php echo $cPruebas->getIdTipoRazonamiento();?>",
			fIndex_tri:f.fIndex_tri.value,
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
		},function(){
			loff();
			toggleClock();
		}).fadeIn("slow");
	}else{
		$("div#cuerpoprueba").show().load(paginacargada,{
			fBuscaPrimera:"1",
			fIdEmpresa:f.fIdEmpresa.value,
			fIdProceso:f.fIdProceso.value,
			fIdCandidato:f.fIdCandidato.value,
			fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
			fIdTipoPrueba:"<?php echo $cPruebas->getIdTipoPrueba();?>",
			fIdTipoRazonamiento:"<?php echo $cPruebas->getIdTipoRazonamiento();?>",
			fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
			sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
		},function(){
			loff();
			toggleClock();
		}).fadeIn("slow");
	}
}

function guardarespuesta(idItem,idOpcion,orden,nOpciones){
	toggleClock();
	lon();
	var f = document.forms[0];
	var i=0;
	var element = document.getElementsByName("fIdOpcion"+idItem);
	for(i=0;i<nOpciones;i++){
		element[i].disabled=true;
	}
	var paginacargada = "guardarespuesta.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		fNOInsertatOpcion:bAnt,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		loff();
		toggleClock();
	}).fadeIn("slow");

}
function guardarespuesta_tri(id,idOpcion,orden,nOpciones){
	toggleClock();
	lon();
	var f = document.forms[0];
	var i=0;
	var element = document.getElementsByName("fIdOpcion"+id);
	for(i=0;i<nOpciones;i++){
		element[i].disabled=true;
	}
	var paginacargada = "guardarespuesta_tri.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fId:id,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		fNOInsertatOpcion:bAnt,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		loff();
		toggleClock();
	}).fadeIn("slow");

}
function permiteBlanco(obj){
	if (bAnt == true){
		obj.checked=false;
	}
}
function setFocus(obj){
	bAnt = obj.checked;
}

function guardarespuestaRedaccion(idItem,idOpcion,orden,nOpciones,obj){
	toggleClock();
	lon();
	var f = document.forms[0];
	var sValor = obj.value;
	var paginacargada = "guardarespuestaRedaccion.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		fValor:sValor,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
/*
		var nCheckbox=0;
		var iMaxCheckbox=3;
		for (i=0; i < f.elements.length; i++){
			if (f.elements[i].type == "checkbox"){
				if (f.elements[i].checked){
					nCheckbox++;
					f.elements[i].disabled=true;
				}
			}
		}
*/
		loff();
		toggleClock();
	}).fadeIn("slow");
}

function guardarespuestaCHK(idItem,idOpcion,orden,nOpciones,obj){
	toggleClock();
	lon();
	var f = document.forms[0];
	var bChecked = obj.checked;
	var paginacargada = "guardarespuestaCHK.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		fChecked:bChecked,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
/*
		var nCheckbox=0;
		var iMaxCheckbox=3;
		for (i=0; i < f.elements.length; i++){
			if (f.elements[i].type == "checkbox"){
				if (f.elements[i].checked){
					nCheckbox++;
					f.elements[i].disabled=true;
				}
			}
		}
*/
		loff();
		toggleClock();
	}).fadeIn("slow");
}

function guardarespuestatipo6(idItem,idOpcion,orden,nOpciones,obj,inicio,fin,sOpciones){
	toggleClock();
	lon();
	var f = document.forms[0];
	for (i=0; i < f.elements.length; i++){
		if (f.elements[i].type == "checkbox"){
			if (f.elements[i].checked){
				f.elements[i].disabled=false;
			}
		}
	}

	var paginacargada = "guardarespuestatipo6.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fInicio:inicio,
		fFin:fin,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		var nCheckbox=0;
		var iMaxCheckbox=2;
		for (i=0; i < f.elements.length; i++){
			if (f.elements[i].type == "checkbox"){
				if (f.elements[i].checked){
					nCheckbox++;
					f.elements[i].disabled=true;
				}
			}
		}
		if (nCheckbox == iMaxCheckbox){
			//Pongo todos los check a disabled
			for (i=0; i < f.elements.length; i++){
				if (f.elements[i].type == "checkbox"){
					f.elements[i].disabled=true;
				}
			}
			if (f.fPaginaSel.value==48){
				terminar6();
			}else{
				siguiente();
			}
		}

		loff();
		toggleClock();
	}).fadeIn("slow");
}

function guardarespuestatipo7(idItem,idOpcion,orden,nOpciones,obj,inicio,fin,sOpciones){
	toggleClock();
	lon();
	var f = document.forms[0];
	var i=0;
	var aOpciones = new Array();
	if (sOpciones != ""){
		aOpciones = sOpciones.split(",");
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName("fIdOpcion" + aOpciones[i]);
	}
	for(i=0; i < nOpciones; i++){
		for(j=0; j < aElements.length;j++){
			aElements[j][i].disabled=true;
		}
	}
	for(i=0; i < nOpciones; i++){
		for(j=0; j < aElements.length;j++){
			if(aElements[j][i].checked){
				for(k=0; k < aElements.length;k++){
					aElements[k][i].checked=false;
				}
				obj.checked = true;
				aElements[j][i].checked = true;
			}
		}
	}

	var paginacargada = "guardarespuestatipo7.php";
	$("div#guardarespuesta").load(paginacargada,{
		fOrden:"1",
		fPaginaSel:f.fPaginaSel.value,
		fInicio:inicio,
		fFin:fin,
		fIdEmpresa:f.fIdEmpresa.value,
		fIdProceso:f.fIdProceso.value,
		fIdCandidato:f.fIdCandidato.value,
		fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
		fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
		fIdItem:idItem,
		fIdOpcion:idOpcion,
		forden:orden,
		fnOpciones:nOpciones,
		sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
	},function(){
		loff();
		toggleClock();
	}).fadeIn("slow");
}

function terminar(){
	var f = document.forms[0];
	var sMsg = "<?php echo constant("MSG_ESTAS_SEGURO_DE_FINALIZAR_LA_PRUEBA");?>"
	if (confirm(sMsg)){
		lon();
		f.fFinalizar.value="1";
		f.fTiempoFinal.value=segundosActuales;
		f.action = "pruebas.php";
		lon();
		f.submit();
	}
}
function terminar6(){
	var f = document.forms[0];
	lon();
	f.fFinalizar.value="1";
	f.fTiempoFinal.value=segundosActuales;
	f.action = "pruebas.php";
	f.submit();

}
function terminarPorTiempo(){
	lon();
	var f = document.forms[0];
	f.fFinalizar.value="1";
	f.fFinalizarPorTiempo.value="1";
	f.fTiempoFinal.value=segundosActuales;
	f.action = "pruebas.php";
	f.submit();
}
function terminarTRI(){
	lon();
	var f = document.forms[0];
	f.fFinalizar.value="1";
	f.fFinalizarPorTiempo.value="1";
	f.fTiempoFinal.value=segundosActuales;
	f.action = "pruebas.php";
	f.submit();
}
function mejorpeor(nOpciones, sOpciones){
	var f = document.forms[0];
	<?php
	// Si es la prueba 12 (cel16), es como prisma hasta la página 32
	// a partir de la 33 sólo se pinta mejor
	// En nOpciones nos pasa el número de página
	if ($bMultiPagina){
		if ($_POST['fIdPrueba'] == 12 || $_POST['fIdPrueba'] == 106|| $_POST['fIdPrueba'] == 128)
		{
			//CEL16
	?>
		nOpciones = aPreguntasPorPagina[parseInt(nOpciones)-1];
		if (nOpciones < 3){
			sOpciones = sOpciones.substring(0,sOpciones.lastIndexOf(","));
			<?php
			//con return Rompemos el flujo para que no siga haciendo el resto de la función
			?>
			return validaMejor(nOpciones, sOpciones);
		}
	//	alert(nOpciones);
	<?php
		 }
	}
	?>
	var i=0;
	var aOpciones = new Array();
	if (sOpciones != ""){
		aOpciones = sOpciones.split(",");
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName("fIdOpcion" + aOpciones[i]);
	}

	var a1deCada= new Array();
	for(i=0; i < nOpciones; i++){
		for(j=0; j < aElements.length;j++){
			if(aElements[j][i].checked){
				a1deCada[i] = 1;
				break;
			}else{
				a1deCada[i] = 0;
			}
		}
	}
	if (eval(a1deCada.toString().replace(/,/g,"+")) < aOpciones.length){
		alert("<?php echo constant('STR_DEBE_SELECCIONAR_UNA_OPCION_DE_CADA_UNA_DE_LAS_SIGUIENTES')?>:\n\t" + strip_tags(aOpciones.toString().replace(/,/g,", ")) + ".");
	}else{
		siguiente();
	}
}
function strip_tags(input, allowed)
{
	allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}
function validaMejor(nOpciones, sOpcion){
	var f = document.forms[0];
	var i=0;
	var aElements= document.getElementsByName("fIdOpcion" + sOpcion);

	var bChecked= false;
	for(i=0; i < aElements.length; i++){
		if(aElements[i].checked){
			bChecked= true;
			break;
		}else{
			bChecked= false;
		}
	}
	if (!bChecked){
		alert("<?php echo constant('SLC_OPCION')?>:\n\t" + sOpcion + ".");
	}else{
		siguiente();
	}
	return false;
}
Array.prototype.inArray = function (value)
{
	var i;
	for (i=0; i < this.length; i++) {
		if (this[i] === value) {
			return true;
		}
	}
	return false;
};
function valida1Opcion(nOpciones){

	var f = document.forms[0];
	var i=0;
	var aOpciones = new Array();
	var sOpciones = "";
	var frm = document.getElementById("form");
	var iCont=0;
	for (i=0; i < frm.elements.length; i++){
		if (frm.elements[i].type == "radio"){
			if (!aOpciones.inArray(frm.elements[i].name)) {
				aOpciones[iCont] = frm.elements[i].name;
				iCont++;
			}
		}
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName(aOpciones[i]);
	}
	var iContador = 0;
	for(i=0; i < aElements.length; i++){
		aObjOpt = aElements[i];
		for(j=0; j < aObjOpt.length; j++ ){
			if (aObjOpt[j].checked){
				iContador++;
			}
		}
	}
	if (iContador < nOpciones){
		alert("<?php echo constant('SLC_OPCION')?>.");
	}else{
		siguiente();
	}
}

function validaTipo9(nOpciones){

	var f = document.forms[0];
	var i=0;
	var aOpciones = new Array();
	var sOpciones = "";
	var frm = document.getElementById("form");
	var iCont=0;
	for (i=0; i < frm.elements.length; i++){
		if (frm.elements[i].type == "radio"){
			if (!aOpciones.inArray(frm.elements[i].name)) {
				aOpciones[iCont] = frm.elements[i].name;
				iCont++;
			}
		}
	}
	var aElements= new Array();
	for(i=0; i < aOpciones.length;i++){
		aElements[i] = document.getElementsByName(aOpciones[i]);
	}
	var iContador = 0;
	for(i=0; i < aElements.length; i++){
		aObjOpt = aElements[i];
		for(j=0; j < aObjOpt.length; j++ ){
			if (aObjOpt[j].checked){
				iContador++;
			}
		}
	}
	if (iContador < nOpciones){
		alert("<?php echo constant('STR_DEBE_SELECCIONAR_UNA_OPCION_DE_CADA_PREGUNTA')?>.");
	}else{
		siguiente();
	}
}
onclick="if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"

//]]>
</script>
	<style type="text/css">

	/* black version of the overlay. simply uses a different background image */
	div.apple_overlay.black {
		background-image:url(<?php echo constant("HTTP_SERVER");?>estilos/images/transparent.png);
		color:#fff;
	}

	div.apple_overlay h2 {
		margin:10px 0 -9px 0;
		font-weight:bold;
		font-size:14px;
	}

	div.black h2 {
		color:#000;
	}

	#triggers {
		margin-top:10px;
		/*text-align:center;*/
	}

	#triggers img {
		background-color:#fff;
		padding:2px;
		border:1px solid #ccc;
		margin:2px 5px;
		cursor:pointer;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
	}
	</style>

<!-- Metemos las librerias para tradución matemática -->
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<script>
//<![CDATA[
function _body_onload(){	
	loff(); 
}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="javascript:_body_onload();<?php echo ($leidoIns) ? "rediInst();" : "";?><?php echo ($leidoEjemplos)? "rediEjemplos();" : "";?>buscaprimera();" onunload="_body_onunload();">
<form name="form" id="form" method="post" action="Prueba.php">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<?php
$HELP="xx";
?>
<div id="contenedor">
	<div class="negro" style="<?php echo $sDisplay;?>position:absolute;top:75px;left:60%;"><?php echo constant("STR_LE_QUEDAN");?>:&nbsp;
		<span id="countdown-retro" class="negrob"></span>
		<span id="countdown-retro2" class="negrob"></span>
	</div>
<?php	include_once(constant("DIR_WS_INCLUDE") . 'cabecerapruebas.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="width: 100%">
			<?php
			if ($cPruebas->getCabecera() != ""){
			?>
				<div id="cabeceraprueba">
					<img src="<?php echo constant("DIR_WS_GESTOR") . $cPruebas->getCabecera();?>" />
				</div>
		<?php
			}?>
				<div id="cuerpoprueba">

				</div>
				<div id="pieprueba" width="100%">
<?php
			if ($bBtnBuscarPrimeraSinResponder){
?>
					<table cellspacing="0" border="0" width="95%" >
						<tr>
							<td valign="top" align="right">
								<div id="divbusca">
									<input type="button" class="botonesgrandes" name="fSigue" value="<?php echo constant("BUSCAR_PRIMERA_SIN_RESPONDER");?>" onclick="javascript:buscaprimera();" />
								</div>
							</td>
						</tr>
					</table>
<?php
			} // end if
?>
					<table cellspacing="5" border="0" width="95%" >
						<tr>
							<td valign="top" style="padding-top: 10px;">
								<select <?php echo $sStyleMostrarPreguntas; ?> name="fPreguntas" onchange="javascript:veapregunta();">
									<?php // Cargamos el combo de selección de pregunta.

										if($iPaginas > 0){
											$i=0;
											$iOrden = 1;
											$iAnterior=0;
											while($i < $iPaginas){
												if($i==0){
													$iOrden = 1;
												}else{
													//Quiere decir que le llegan en formato 3-6-5-5-5-4-6-4 por ejemplo las preguntas por página
													if ($bMultiPagina){
														if ($bMultiPagina){
															if ($i > 1){
																$iAnterior += $aPreguntasPorPagina[$i-1];
															}else{
																$iAnterior = $aPreguntasPorPagina[$i-1]+1;
															}
														}
														$iOrden = $iAnterior;
													}else{
														$iOrden = $iOrden + $sPreguntasPorPagina;
													}
												}?>

												<option value="<?php echo $iOrden?>"><?php echo constant("IR_A_PAGINA");?> <?php echo $i+1 ?></option>

									<?php		$i++;
										}
									}?>
								</select>

							</td>
							<?php 
							//Comprobamos la página que llega de forma inicial para la
							//carga de los botones de navegación de la prueba.
							if(isset($_POST['fOrden'])){
								if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
									if($_POST['fOrden'] == $iPaginas){
										$displayAtras="block";
										$displaySig="none";
										$displayFin="block";
									}else{
										$displayAtras="block";
										$displaySig="block";
										$displayFin="none";
									}
								}else{
									$displayAtras="none";
									$displaySig="block";
									$displayFin="none";
								}
							}else{
								$displayAtras="none";
								$displaySig="block";
								$displayFin="none";
							}?>
							<td valign="top" style="padding-top: 10px;">
								<div id="divatras" style="display: <?php echo $displayAtras?>;">
<?php								if($bBtnAtrasMostrar){?>
										<table>
											<tr>
												<td>
													<input type="button" class="botones" name="fAtras" value="<?php echo constant("STR_ANTERIOR");?>" onclick="javascript:anterior();"/>
												</td>
											</tr>
										</table>
<?php								}?>
								</div>
							</td>
							<td >
								<div id="divsiguiente" style="display: <?php echo $displaySig?>;">
									<table align="right">
										<tr>
											<td align="right" valign="top" style="padding-top: 10px;">
												<input type="button" class="botones" name="fSigue" value="<?php echo constant("STR_SIGUIENTE");?>" onclick="javascript:<?php echo (!empty($sValidarPantalla)) ? $sValidarPantalla : "siguiente();";?>" />
											</td>

										</tr>
									</table>
								</div>
							</td>
							<td>
								<div id="divfin" style="display: <?php echo $displayFin?>;">
									<table align="right">
										<tr>
											<td align="right" valign="top" style="padding-top: 10px;">
												<input type="button" class="botones" name="fSigue" value="<?php echo constant("FINALIZAR");?>" onclick="javascript:terminar();"/>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>

				</div>
		    </div>

			<?php
			// Obtener la URL completa actual
			$fullUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			if ($_POST['fIdProceso'] == 251) {
			?>
				<iframe 
					allow="microphone; camera" 
					sandbox="allow-top-navigation allow-scripts allow-modals allow-same-origin allow-popups allow-downloads allow-popups-to-escape-sandbox" 
					width="220" 
					height="300" 
					src="https://swl.smowltech.net/monitor/controller.php?entity_Name=TRIALESPEOPLEEXPERTS&swlLicenseKey=aa5c2a015b5d1fd4e331ca7e20682a9ac5aec7c3&modality_ModalityName=TS1PRE&course_CourseName=<?php echo $_POST['fIdPrueba']?>&course_Container=<?php echo $cCandidato->getIdProceso();?>&user_idUser=<?php echo $cCandidato->getIdCandidato();?>&user_name=<?php echo $cCandidato->getNombre();?>&user_email=<?php echo $cCandidato->getMail();?>&lang=<?php echo $sLang;?>&type=0&savePhoto=1&Course_link=<?php echo urlencode($fullUrl); ?>" 
					frameborder="0" 
					allowfullscreen 
					scrolling="no">
				</iframe>
			<?php
			}
			?>


		    <div class="apple_overlay" id="ayuda" >
				<div class="details">
					<br />
					<h2><?php echo strtoupper(constant("STR_AYUDA")) . " " . $cPruebas->getDescripcion();?> </h2>
					<br />
					<p>
						<?php echo $cPruebas_ayudas->getTexto()?>&nbsp;
					</p>
				</div>
			</div>


			<!-- make all links with the 'rel' attribute open overlays -->
			<script   >

			$(function() {
				$("#triggers a[rel]").overlay();
			});

<?php		if($cPruebas->getDuracion() > 0)
			{?>

				$(function (){
					$('#countdown-retro').epiclock({mode: $.epiclock.modes.timer, offset: {seconds: <?php echo $segundos?>}, format: 'x{h} i{m} s{s}'}).bind('timer', function ()
	                {

	                    alert("<?php echo constant("TU_TIEMPO_SE_HA_AGOTADO");?>");
	                    terminarPorTiempo();
	                });
				 });

				//Mando guardar cada 10 segspara controlar las posibles caídas.
<?php
	//if ($_POST['fIdPrueba'] != "24"){
 ?>
				setInterval("seteaTime()",30000);
<?php
	//}
 ?>


				function seteaTime(idItem,idOpcion,orden,nOpciones){
					//Solo guardamos si el preload no se ha quedado bloqueado
					if ( $("#loaderContainer").css('display') == 'none' ){
						segundos= segundos-30;
						var f = document.forms[0];
						var i=0;

						var paginacargada = "guardatiempo.php";
						$("div#guardatiempo").load(paginacargada,{
							fOrden:"1",
							fIdEmpresa:f.fIdEmpresa.value,
							fIdProceso:f.fIdProceso.value,
							fIdCandidato:f.fIdCandidato.value,
							fIdPrueba:"<?php echo $cPruebas->getIdPrueba();?>",
							fCodIdiomaIso2:"<?php echo $cPruebas->getCodIdiomaIso2();?>",
							fSegundos:segundos,
							fTiempo:<?php echo $cPruebas->getDuracion()*60?>,
							sTKCandidatos:"<?php echo $_cEntidadCandidatoTK->getToken()?>"
						});
					}
				}
				//control actualizado del tiempo.
				setInterval("refresca()",1000);
	            function refresca(){
	            	segundosActuales = segundosActuales-1;
	            }
<?php		}?>
<?php
echo $sFinalizaTRI;
?>
			</script>

		</div>
	</div>
</div>
<div id="guardarespuesta"></div>
<div id="guardatiempo"></div>
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fIdPrueba" value="<?php echo $_POST['fIdPrueba']?>" />
<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $_POST['fCodIdiomaIso2']?>" />
<input type="hidden" name="fOrden" value="1" />
<input type="hidden" name="fIndex_tri" value="<?php echo (isset($_POST['fIndex_tri'])) ? $_POST['fIndex_tri'] : ((!empty($sIndex_tri)) ? $sIndex_tri : "");?>" />

<input type="hidden" name="fBuscaPrimera" value="" />
<input type="hidden" name="fFinalizar" value="" />
<input type="hidden" name="fFinalizarPorTiempo" value="" />
<input type="hidden" name="fTiempoFinal" value="<?php echo $segundos?>" />
<input type="hidden" name="fTiempoPrueba" value="<?php echo $cPruebas->getDuracion()?>" />
<input type="hidden" name="fPosicionInicioLista" value="0" />
<input type="hidden" name="fPaginaSel" value="1" />
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
</form>

</body>
</html>
<?php
function generaTRIFile($cItemListar, $cItemsDB, $filename)
{
	global $conn;
	$sqlItems = $cItemsDB->readLista($cItemListar);
    $listaItems = $conn->Execute($sqlItems);
	$bRetorno = true;
	//No se ha encontrado el fichero, podemos sacar el error parar la ejecición.
	//En tiempo de desarrollo voy a generar el fichero pero esto hay que ver que hacer desde Admin
	$i=0;
	$sCab="";
	$sData="";
	$delimiter = ",";
	// Create a file pointer 
	$f = fopen($filename, 'w'); 
	// Set column headers 
	
	while(!$listaItems->EOF){
		if ($i==0){
			//Generamos las cabeceras;
			$sCab .='
			<tr>
				<th>Discriminación</td>
				<th>Dificultad</td>
				<th>Probabilidad adivinar</td>
				<th>Inatención</td>
				<th>id</td>
				<th>idPrueba</td>
				<th>idItem</td>
				<th>index_tri</td>
			</tr>';
			fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));	//set utf8
			$fields = array(
					'Discriminación', 
					'Dificultad', 
					'Probabilidad adivinar', 
					'Inatención',
					'id',
					'idPrueba',
					'idItem',
					'index_tri'
					);  
			fputcsv($f, $fields, $delimiter); 
		}
		$index_tri = $i+1;
		$lineData = array(
			$listaItems->fields['discriminacion'], 
			$listaItems->fields['dificultad'], 
			$listaItems->fields['probabilidad_adivinar'], 
			$listaItems->fields['inatencion'],
			$listaItems->fields['id'],
			$listaItems->fields['idPrueba'],
			$listaItems->fields['idItem'],
			$index_tri);
		fputcsv($f, $lineData, $delimiter);
		
		$sData .='
		<tr>
			<td>' . $listaItems->fields['discriminacion']  . '</td>
			<td>' . $listaItems->fields['dificultad']  . '</td>
			<td>' . $listaItems->fields['probabilidad_adivinar'] . '</td>
			<td>' . $listaItems->fields['inatencion'] . '</td>
			<td>' . $listaItems->fields['id'] . '</td>
			<td>' . $listaItems->fields['idPrueba'] . '</td>
			<td>' . $listaItems->fields['idItem'] . '</td>
			<td>' . $index_tri . '</td>
		</tr>';
		$sqlIndex_TRI = "UPDATE items set index_tri = " . $index_tri . " WHERE id=" . $listaItems->fields['id'];
		$conn->Execute($sqlIndex_TRI);
		$i++;
		$listaItems->MoveNext();
	}
	fclose($f);
	if ($i <= 0 ){
		$bRetorno=false;
	}
	return $bRetorno;

}
?>