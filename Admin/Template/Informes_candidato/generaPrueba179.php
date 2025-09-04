<?php
///////////////////////////////////
//Generación de informe  de MAZDA
//////////////////////////////////

//tipo prisma CUESTIONARIO DE PERSONALIDAD COMERCIAL CPC (179) ->Lanza informe completo

if(isset($_POST['esZip']) && $_POST['esZip'] == true){
	$dirGestor = constant("DIR_WS_GESTOR_HTTPS");
	$documentRoot = constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
}else{
	$dirGestor = constant("DIR_WS_GESTOR");
	$documentRoot = constant("DIR_FS_DOCUMENT_ROOT");
}

global $dirGestor;
global $documentRoot;

require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
$cItems_inversosDB = new Items_inversosDB($conn);
$cItems_inversos = new Items_inversos();
$iCPC = 179;
$cItems_inversos->setIdPrueba($iCPC);
$cItems_inversos->setIdPruebaHast($iCPC);
$sqlInversos = $cItems_inversosDB->readLista($cItems_inversos);
//		echo "<br />" . $sqlInversos;
$listaInversos = $conn->Execute($sqlInversos);
$nInversos = $listaInversos->recordCount();
$aInversos = array();
if ($nInversos > 0) {
	$i = 0;
	while (!$listaInversos->EOF) {
		$aInversos[$i] = $listaInversos->fields['idItem'];
		$i++;
		$listaInversos->MoveNext();
	}
}
$comboTIPOS_INFORMES	= new Combo($conn, "fIdTipoInforme", "idTipoInforme", "nombre", "Descripcion", "tipos_informes", "", constant("SLC_OPCION"), "codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false), "", "fecMod");
$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

// CÁLCULOS GLOBALES PARA ESCALAS,
// Se hace fuera y los metemos en un array para
// reutilizarlo en varias funciones
$cEscalas_items =  new Escalas_items();
$cEscalas_itemsDB =  new Escalas_itemsDB($conn);
$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
$sqlEscalas_items = $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//		echo "<br />sqlEscalas_items::" . $sqlEscalas_items . "";
$rsEscalas_items = $conn->Execute($sqlEscalas_items);
$sBloques = "";
while (!$rsEscalas_items->EOF) {
	$sBloques .= "," . $rsEscalas_items->fields['idBloque'];
	$rsEscalas_items->MoveNext();
}
//		echo "<br />222-->sBloques::" . $sBloques;
if (!empty($sBloques)) {
	$sBloques = substr($sBloques, 1);
}
$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
$cBloques = new Bloques();
$cBloques->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
$cBloques->setIdBloque($sBloques);
$cBloques->setOrderBy("idBloque");
$cBloques->setOrder("ASC");
$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "<br />" . $sqlBloques;
$listaBloques = $conn->Execute($sqlBloques);
$nBloques = $listaBloques->recordCount();
$aPuntuaciones = array();
if ($nBloques > 0) {
	while (!$listaBloques->EOF) {
		$cEscalas = new Escalas();
		$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
		$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
		$cEscalas->setOrderBy("idEscala");
		$cEscalas->setOrder("ASC");
		$sqlEscalas = $cEscalasDB->readLista($cEscalas);
		//				echo "<br />" . $sqlEscalas;
		$listaEscalas = $conn->Execute($sqlEscalas);
		$nEscalas = $listaEscalas->recordCount();
		if ($nEscalas > 0) {
			while (!$listaEscalas->EOF) {
				$cEscalas_items = new Escalas_items();
				$cEscalas_items->setIdEscala($listaEscalas->fields['idEscala']);
				$cEscalas_items->setIdEscalaHast($listaEscalas->fields['idEscala']);
				$cEscalas_items->setIdBloque($listaEscalas->fields['idBloque']);
				$cEscalas_items->setIdBloqueHast($listaEscalas->fields['idBloque']);
				$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
				$cEscalas_items->setOrderBy("idItem");
				$cEscalas_items->setOrder("ASC");
				$sqlEscalas_items = $cEscalas_itemsDB->readLista($cEscalas_items);
				//						echo "<br />" . $sqlEscalas_items;
				$listaEscalas_items = $conn->Execute($sqlEscalas_items);
				$nEscalas_items = $listaEscalas_items->recordCount();

				$iPd = 0;
				if ($nEscalas_items > 0) {
					while (!$listaEscalas_items->EOF) {
						$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

						$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
						$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestas_pruebas_items->setIdItem($listaEscalas_items->fields['idItem']);

						$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

						if (array_search($listaEscalas_items->fields['idItem'], $aInversos) === false) {
							//MEJOR => 2 PEOR => 0 VACIO => 1
							switch ($cRespuestas_pruebas_items->getIdOpcion()) {
								case '1':	// Mejor
									$iPd += 3;
									break;
								case '2':	// Peor
									$iPd += 1;
									break;
								default:	// Sin contestar opcion 0 en respuestas
									$iPd += 2;
									break;
							}
							//					       			$iPd = $iPd + $cRespuestas_pruebas_items->getIdOpcion();
						} else {
							//					       			echo "<br />" . $listaEscalas_items->fields['idItem'];
							$iPd += getInversoPrisma($cRespuestas_pruebas_items->getIdOpcion());
						}

						$listaEscalas_items->MoveNext();
					}
				}

				$cBaremos_resultado = new Baremos_resultados();
				$cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
				$cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
				$cBaremos_resultado->setIdBloque($listaEscalas->fields['idBloque']);
				$cBaremos_resultado->setIdEscala($listaEscalas->fields['idEscala']);

				$sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
				//						echo "<br />iPd:: " . $iPd . " - " . $sqlBaremos_resultado;
				$listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

				$iPBaremada = 0;
				$nBaremos = $listaBaremos_resultado->recordCount();
				if ($nBaremos > 0) {
					while (!$listaBaremos_resultado->EOF) {
						if ($iPd <= $listaBaremos_resultado->fields['puntMax'] && $iPd >= $listaBaremos_resultado->fields['puntMin']) {
							$iPBaremada = 	$listaBaremos_resultado->fields['puntBaremada'];
						}
						$listaBaremos_resultado->MoveNext();
					}
				}

				$sPosi = $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'];
				$sNombreBE = $listaBloques->fields['nombre'] . "-" . $listaEscalas->fields['nombre'];

								       	//echo "<br />---------->[" . $sPosi . "][" . $sNombreBE . "][PD:" . $iPd . "][PB:" . $iPBaremada . "]";
				

				
				//echo('<script type="text/javascript">
				//var user = ' . json_encode($iPd) . ';' .
				//'console.log("iPd", user); </script>');
				
				$aPuntuaciones[$sPosi] =  $iPBaremada;

				$listaEscalas->MoveNext();
			}
		}
		$listaBloques->MoveNext();
	}
}

// FIN CALCULOS GLOBALES ESCALAS

//CALCULOS GLOBALES COMPETENCIAS
$aPromediosTC = array();
$cBaremos_resultados_competenciasDB = new Baremos_resultados_competenciasDB($conn);
$cTipos_competencias = new Tipos_competencias();
$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
$cTipos_competencias->setOrderBy("idTipoCompetencia");
$cTipos_competencias->setOrder("ASC");
$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
//  	echo "<br />-->" . $sqlTipos_competencias . "";
$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
$nTCompetencias = $listaTipoCompetencia->recordCount();
$nTotalCompetencias = 0;
$aPuntuacionesCompetencias = array();
$aPerfilIdealCompetencias = array();
$iPGlobalC = 0;

if ($nTCompetencias > 0) {
	while (!$listaTipoCompetencia->EOF) {

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
		$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
		$cCompetencias->setIdPrueba($_POST['fIdPrueba']);
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		//			 	echo "<br />" . $sqlCompetencias . "";
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		$nCompetencias = $listaCompetencias->recordCount();
		$nTotalCompetencias += $nCompetencias;
		if ($nCompetencias > 0) {
			while (!$listaCompetencias->EOF) {

				$cCompetencias_items = new Competencias_items();
				$cCompetencias_items->setIdCompetencia($listaCompetencias->fields['idCompetencia']);
				$cCompetencias_items->setIdCompetenciaHast($listaCompetencias->fields['idCompetencia']);
				$cCompetencias_items->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
				$cCompetencias_items->setIdTipoCompetenciaHast($listaCompetencias->fields['idTipoCompetencia']);
				$cCompetencias_items->setIdPrueba($_POST['fIdPrueba']);
				$cCompetencias_items->setOrderBy("idItem");
				$cCompetencias_items->setOrder("ASC");
				$sqlCompetencias_items = $cCompetencias_itemsDB->readLista($cCompetencias_items);
				//				       	echo "<br />" . $sqlCompetencias_items . "";
				//				       	echo "<br />" . $listaCompetencias->fields['nombre'];
				$listaCompetencias_items = $conn->Execute($sqlCompetencias_items);
				$nCompetencias_items = $listaCompetencias_items->recordCount();
				$iPdCompetencias = 0;
				if ($nCompetencias_items > 0) {
					while (!$listaCompetencias_items->EOF) {
						$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
						$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
						$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
						$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
						$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
						$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
						$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

						$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

						if (array_search($listaCompetencias_items->fields['idItem'], $aInversos) === false) {
							//MEJOR => 2 PEOR => 0 VACIO => 1
							switch ($cRespuestas_pruebas_items->getIdOpcion()) {
								//case '1':
								//	$iPdCompetencias += 1;
								//	break;
								//default:	// Sin contestar opcion 0 en respuestas
								//	$iPdCompetencias += 0;
								//	break;

								
								case '1':	// Mejor
									$iPdCompetencias += 2;
									break;
								case '2':	// Peor
									$iPdCompetencias += 0;
									break;
								default:	// Sin contestar opcion 0 en respuestas
									$iPdCompetencias += 1;
									break;
							}
						} else {
							$iPdCompetencias += getInversoPrisma($cRespuestas_pruebas_items->getIdOpcion());
						}
						$listaCompetencias_items->MoveNext();
					}
				}
				$cBaremos_resultado_competencias = new Baremos_resultados_competencias();
				$cBaremos_resultado_competencias->setIdBaremo($_POST['fIdBaremo']);
				$cBaremos_resultado_competencias->setIdPrueba($_POST['fIdPrueba']);
				$cBaremos_resultado_competencias->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
				$cBaremos_resultado_competencias->setIdCompetencia($listaCompetencias->fields['idCompetencia']);

				$sqlBaremos_resultado_competencia = $cBaremos_resultados_competenciasDB->readLista($cBaremos_resultado_competencias);
				//echo "<br />" . $sqlBaremos_resultado_competencia . "<br />";
				$listaBaremos_resultado_competencia = $conn->Execute($sqlBaremos_resultado_competencia);
				//echo $iPdCompetencias . "<br />";
				$iPBaremadaCompetencias = 0;
				$nBaremosC = $listaBaremos_resultado_competencia->recordCount();
				if ($nBaremosC > 0) {
					while (!$listaBaremos_resultado_competencia->EOF) {

						if ($iPdCompetencias <= $listaBaremos_resultado_competencia->fields['puntMax'] && $iPdCompetencias >= $listaBaremos_resultado_competencia->fields['puntMin']) {
							$iPBaremadaCompetencias = 	$listaBaremos_resultado_competencia->fields['puntBaremada'];
						}
						$listaBaremos_resultado_competencia->MoveNext();
					}
				}

				$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
				$sNombreC = $listaTipoCompetencia->fields['nombre'] . "-" . $listaCompetencias->fields['nombre'];
				$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPBaremadaCompetencias;
				$aPerfilIdealCompetencias[$sPosiCompetencias] = trim($listaCompetencias->fields['descripcion']);
				$iPGlobalC += $iPBaremadaCompetencias;	//($iPBaremadaCompetencias - 2.75) * ($iPBaremadaCompetencias - 2.75);

				// echo "<br />" . $sPosiCompetencias . " " . $listaCompetencias->fields['nombre'] . " - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias . " ";
				// echo "<br />---------->[" . $sPosiCompetencias . "][" . $sNombreC . "] - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias . " ";
				$listaCompetencias->MoveNext();
			}
		}
		$listaTipoCompetencia->MoveNext();
	}
	//$consistenciaC = baremo_C(number_format(sqrt($iPGlobalC/$nTotalCompetencias)*100 ,0));
	$consistenciaC = baremo_C($iPGlobalC);
	//echo "<br />iPGlobalC::" .$iPGlobalC;
	//echo "<br />nº Competencias::" .$nTotalCompetencias;
	//echo "<br />Formula::sqrt(iPGlobalC/nCompetencias)*100";
	//echo "<br />Result formula" . number_format(sqrt($iPGlobalC/$nTotalCompetencias)*100 ,0);
	//echo "<br />G.C.C.::" . $consistenciaC;
}

//FIN CALCULOS GLOBALES COMPETENCIAS

$cNivelesjerarquicos = new Nivelesjerarquicos();
$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);

setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");

@set_time_limit(0);
$_HEADER = '';
$sHtmlCab	= '';
$sHtml		= '';
$sHtmlFin	= '';
//$aux			= $this->conn;

$spath = (substr($documentRoot, -1, 1) != '/') ? $documentRoot . '/' : $documentRoot;

$sHtmlInicio = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="' . $dirGestor . 'estilosInformes/sercMazda/resetCSS.css"/>';
if ($_POST['fIdTipoInforme'] != 11) {
	$sHtmlInicio .= 		'<link rel="stylesheet" type="text/css" href="' . $dirGestor . 'estilosInformes/sercMazda/style.css"/>';
} else {
	$sHtmlInicio .= 		'<link rel="stylesheet" type="text/css" href="' . $dirGestor . 'estilosInformes/sercMazda/styleNarrativos.css"/>';
}
$sHtmlInicio .= '
					<title>AC</title>
					<style type="text/css">
					ul.inner{
						list-style-type: disc;
						margin-left: 20px;
					}
					<!--
					-->
					</style>
				</head>
			<body>';
$sHtmlFin .= '
	</body>
	</html>';

//$sFechaCon = $this->convertir_fecha($cEntidadEmpresas->getFechaInscripcion());

//$sFecha = explode(" " , $sFechaCon);
$sHtmlCab .= '<div class="cabecera">
					<table width="100%" border="0">
						<tr>
		    				<td class="nombre">
										<p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
						    </td>
						    <td class="logo">
						    	<img src="' . $dirGestor . 'estilosInformes/sercMazda/img/sp.gif" title="logo"/>
						    </td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '</p>
						    </td>
					    </tr>
				    </table>
				</div>
		';
$_HEADERz = '<div class="cabecera">
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
						    </td>
						    <td class="logo">
						    	<img src="" />
						    </td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '</p>
						    </td>
					    </tr>
				    </table>
				</div>
		';
$cTextos_secciones = new Textos_secciones();
$cTextos_secciones->setIdSeccion("3");
$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
$cTextos_secciones->setIdTipoInforme($idTipoInforme);
$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);

$comboESPECIALIDADESMB	= new Combo($conn, "fEspecialidadMB", "idEspecialidadMB", "descripcion", "Descripcion", "especialidadesmb", "", "", "", "", "", "");
$sEsp = $comboESPECIALIDADESMB->getDescripcionCombo($cCandidato->getEspecialidadMB());


///////////
////// Calculo consistencia GENERAL
///////////

$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
$i = 0;
$cEscalas_items =  new Escalas_items();
$cEscalas_itemsDB =  new Escalas_itemsDB($conn);
$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
$sqlEscalas_items = $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
$rsEscalas_items = $conn->Execute($sqlEscalas_items);
$sBloques = "";
while (!$rsEscalas_items->EOF) {
	$sBloques .= "," . $rsEscalas_items->fields['idBloque'];
	$rsEscalas_items->MoveNext();
}
//					echo "<br />sBloques::" . $sBloques;
if (!empty($sBloques)) {
	$sBloques = substr($sBloques, 1);
}
$cBloques = new Bloques();
$cBloques->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
$cBloques->setIdBloque($sBloques);
$cBloques->setOrderBy("idBloque");
$cBloques->setOrder("ASC");
$sqlBloques = $cBloquesDB->readLista($cBloques);
$listaBloques = $conn->Execute($sqlBloques);

$iPosiImg = 0;
$iPGlobal = 0;
$nBloques = $listaBloques->recordCount();
$aSeparadorNum = array(1, 4);
$iSeparadorNum = 0;
if ($nBloques > 0) {
	while (!$listaBloques->EOF) {

		$cEscalas = new Escalas();
		$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
		$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
		$cEscalas->setOrderBy("idEscala");
		$cEscalas->setOrder("ASC");
		$sqlEscalas = $cEscalasDB->readLista($cEscalas);
		$listaEscalas = $conn->Execute($sqlEscalas);
		$nEscalas = $listaEscalas->recordCount();
		$nVueltas = 1;
		if ($nEscalas > 0) {
			while (!$listaEscalas->EOF) {
				$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
				$iPGlobal += ($iPBaremada - 2.75) * ($iPBaremada - 2.75);
				$listaEscalas->MoveNext();
			}
		}
		$iSeparadorNum++;
		$iPosiImg++;
		$listaBloques->MoveNext();
	}
}

$consistencia = baremo_C(number_format(sqrt($iPGlobal / 12) * 100, 0));
//echo "<br />G.C.::" . $consistencia;
//// FIN de consistencia GENERAL
//echo "<br />--------->" . $_POST['fIdTipoInforme'];
$aSQLPuntuacionesPPL = array();
$aSQLPuntuacionesC = array();

switch ($_POST['fIdTipoInforme']) {
	case (3); //Informe Completo
		//FUNCIÓN PARA generar informe
		$sHtml .= getPortada();
		$sHtml .= generarSERC($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $_POST['fCodIdiomaIso2']);
		$sHtml .= generarEntrevista($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $_POST['fCodIdiomaIso2']);
		$sHtml .= getContraPortada();
		break;
	case (56); //Informe Academia de Formación

		$sHtml .= generarAcademia($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $_POST['fCodIdiomaIso2']);
		break;
	case (57); //Informe Entrevista

		$sHtml .= generarEntrevista($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $_POST['fCodIdiomaIso2']);
		break;
}


if (!isset($NOGenerarFICHERO_INFORME)) {
	if (!empty($sHtml)) {
		$replace = array('@', '.');
		//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" .$_POST['fIdTipoInforme'] . "_" . $cPruebas->getNombre();
		$sDirImg = "imgInformes/";
		$spath = (substr($documentRoot, -1, 1) != '/') ? $documentRoot . '/' : $documentRoot;

		$_fichero = $spath . $sDirImg . $sNombre . ".html";
		//$cEntidad->chk_dir($spath . $sDirImg, 0777);

		if (is_file($_fichero)) {
			unlink($_fichero);
		}
		error_log(utf8_decode($sHtmlInicio . $sHtml . $sHtmlFin), 3, $_fichero);
	}
	//Si ha pulsado PDF
	if ($_POST['MODO'] != constant("MNT_EXPORTA_HTML")) {
		//		error_reporting(E_ALL);
		//		ini_set("display_errors","1");
		if (ini_get("pcre.backtrack_limit") < 2000000) {
			ini_set("pcre.backtrack_limit", 2000000);
		};
		@set_time_limit(0);
		@define("OUTPUT_FILE_DIRECTORY", $spath . $sDirImg);
		//		echo "LLEGO PDF";exit;

		$header_html    = $_HEADER;

		$footer_html    =  str_repeat(" ", 70) . constant("STR_PIE_INFORMES");
		//$footer_html = $footer_html;
		include("generaDOMPDF.php");

		//$footer_html = $footer_html;

		//


	}
}
/******************************************************************
 * Funciones para la generación del Informe
 ******************************************************************/

function baremo_C($pd)
{
	if ($pd <= 11) {
		$baremo_C = 1;
	}
	if ($pd >= 12 && $pd <= 18) {
		$baremo_C = 2;
	}
	if ($pd >= 19 && $pd <= 26) {
		$baremo_C = 3;
	}
	if ($pd >= 27 && $pd <= 33) {
		$baremo_C = 4;
	}
	if ($pd >= 34 && $pd <= 41) {
		$baremo_C = 5;
	}
	if ($pd >= 42 && $pd <= 48) {
		$baremo_C = 6;
	}
	if ($pd >= 49 && $pd <= 56) {
		$baremo_C = 7;
	}
	if ($pd >= 57 && $pd <= 63) {
		$baremo_C = 8;
	}
	if ($pd >= 64 && $pd <= 71) {
		$baremo_C = 9;
	}
	if ($pd >= 72) {
		$baremo_C = 10;
	}

	return $baremo_C;
}
//Funcion que devuelve un texto a la parte del informe de competencias de serc
function textoDefinicion($puntuacion)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";
	if ($puntuacion < 2) {
		$str = "Nunca o casi nunca";	//"NUNCA";
	}
	if ($puntuacion == 2) {
		$str = "A veces con dificultad";	//"CASI NUNCA";
	}
	if ($puntuacion >= 3 && $puntuacion <= 4) {
		$str = "Con cierta frecuencia";	//"A VECES";
	}
	if ($puntuacion == 5) {
		$str = "Casi siempre:";	//"CASI SIEMPRE";
	}
	if ($puntuacion > 5) {
		$str = "Una de sus características clave es que";	//"SIEMPRE";
	}
	return $str;
}
//Funcion que devuelve un texto a la parte del informe de competencias de serc
function textoPuntuacion($puntuacion, $iPerfilIdeal)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";

	if ($puntuacion > $iPerfilIdeal) {
		$str = "Área de Fortaleza";
	}
	if ($puntuacion == $iPerfilIdeal) {
		$str = "Nivel adecuado para el puesto";
	}
	if ($puntuacion < $iPerfilIdeal) {
		$str = "Área de Desarrollo";
	}
	return $str;
}

function textoConsistenciaC($puntuacion)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";

	if ($puntuacion >= 8) {
		$str = "Excesivamente alta";
	}
	if ($puntuacion >= 4 && $puntuacion <= 7) {
		$str = "Adecuada";
	}
	if ($puntuacion <= 3) {
		$str = "Excesivamente baja";
	}
	return $str;
}

function textoAjustePerfil($puntuacion)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";

	$ipociento = (($puntuacion * 100) / 5);
	if ($ipociento >= 74) {
		$str = "Adecuado";
	} else {
		$str = "No Adecuado";
	}
	return $str;
}
function xCientoAjustePerfil($puntuacion)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";

	$iporciento = (($puntuacion * 100) / 5);
	$str = number_format($iporciento, 2, ",", ".") . "%";

	return $str;
}
function bolosPuntuacion($puntuacion, $iPerfilIdeal)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";
	if ($puntuacion < $iPerfilIdeal) {
		$str = '
				<span class="rojo">&nbsp;</span>
				';
	}
	if ($puntuacion == $iPerfilIdeal) {
		$str = '
				<span class="amarillo">&nbsp;</span>
				';
	}
	if ($puntuacion > $iPerfilIdeal) {
		$str = '
				<span class="verde">&nbsp;</span>
				';
	}
	return $str;
}
function colorPuntuacion($puntuacion, $iPerfilIdeal)
{
	global $dirGestor;
	global $documentRoot;
	$str = "";
	if ($puntuacion < $iPerfilIdeal) {
		$str = '#FF0000;';
	}
	if ($puntuacion == $iPerfilIdeal) {
		$str = '#ebee13;';
	}
	if ($puntuacion > $iPerfilIdeal) {
		$str = '#09a334;';
	}
	return $str;
}
// Si llega MEJOR devolver 1
// Si llega PEOR devolver 2
// Si llega BLANCO devolver 3
function getInversoPrisma($valor)
{
	global $dirGestor;
	global $documentRoot;
	$inv = 0;

	//MEJOR => 3 PEOR => 1 VACIO => 2
	switch ($valor) {
		case '1':	// Mejor
			$inv = 0;
			break;
		case '2':	// Peor
			$inv = 2;
			break;
		default:	// Sin contestar opcion 0 en respuestas
			$inv = 1;
			break;
	}
	//		if($valor==2){$inv=0;}
	//		if($valor==1){$inv=1;}
	//		if($valor==0){$inv=2;}
	//		echo "<br />id::" . $valor .  " - valor::" . $inv;
	return $inv;
}


/*
	 * APTITUDES SERC
	 */
function generarSERC($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
{
	global $dirGestor;
	global $documentRoot;

	global $conn;
	global $cBloquesDB;
	global $cEscalasDB;
	global $cEscalas_itemsDB;
	global $sEsp;
	global $cCandidato;
	global $comboESPECIALIDADESMB;
	global $cUtilidades;
	global $nTCompetencias;
	global $listaTipoCompetencia;
	global $cCompetencias;
	global $cCompetenciasDB;

	$sSQLExport = "";
	global $aSQLPuntuacionesPPL;
	global $aSQLPuntuacionesC;
	global $cPruebas;
	global $cProceso;
	global $cRespPruebas;
	global $consistenciaC;
	global $aPerfilIdealCompetencias;

	$sumPromedioTipoCompetencia = 0;

	$iMC = "26";
	$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
	$cPrc_inf =  new Proceso_informes();

	$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
	$cPrc_inf->setIdProceso($_POST['fIdProceso']);
	$cPrc_inf->setIdPrueba($iMC);
	$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

	$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

	$aAptMC116 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iMC, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo'], $cCandidato->getEspecialidadMB());
	//echo "<br />[iPDirecta, iPercentil, IR, IP, POR, iItemsPrueba]";
	//echo "<br />MC116:: ";
	//print_r($aAptMC116);

	$iWhidth = 1;
	$iPDirecta = $aAptMC116[0];
	$iPDirectaX = $iPDirecta * 10;
	$iPercentil = $aAptMC116[1];
	$iPercentilX = $iPercentil * 10;

	$sHtml = '
			<div class="pagina">' . $sHtmlCab;
	$sHtml .= '
				<div class="desarrollo">
		        	<h2 class="subtitulo">' . mb_strtoupper(constant("STR_APTITUDES"), 'UTF-8') . '</h2>
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td>RAZONAMIENTO VERBAL</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">Esta prueba tiene como objetivo evaluar la capacidad para comprender, retener, analizar y razonar textos de contenido muy diverso, es decir, el potencial que tiene una persona para trabajos que implican actividades de tipo verbal.</p>
		            </div>
      				<table id="caja_puntos" border="0">
		        		<tr>
    							<td class="puntos_escala">
                    <table width="666" cellspacing="0" cellpadding="0">
                        <tr>
                          <td style="height:45px;">
                            <img src="' . $dirGestor . constant('DIR_WS_GRAF') . 'numeritosEstandar.jpg' . '" style="width:666px;">
                          </td>
                        </tr>
                        <tr>
                          <td width="81%" style="vertical-align:middle;">
                            <img src="' . $dirGestor . constant('DIR_WS_GRAF') . 'bodoque_gigante_OLD.jpg' . '" style="width:' . str_replace(",", ".", (($iPercentil * 666) / 100)) . 'px;height:25px;">
                          </td>
                        </tr>
                    </table>
    								<table width="666" cellspacing="0" cellpadding="0" border="0">
    									<tr>

        									<td width="90" align="center" style="height:30px;border-right:2px solid #413D3E;">
        										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8') . '</font>
        									</td>
        									<td width="125" align="center" style="height:30px;border-right:2px solid #413D3E;">
        										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8') . '</font>
        									</td>
        									<td width="100" align="center" style="height:30px;">
        										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8') . '</font>
        									</td>
        									<td width="100" align="center" style="">

        									</td>
        								</tr>
        							</table>
    							</td>
		        		</tr>
		        	</table>';


	$iCEC = "16";
	$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
	$cPrc_inf =  new Proceso_informes();

	$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
	$cPrc_inf->setIdProceso($_POST['fIdProceso']);
	$cPrc_inf->setIdPrueba($iCEC);
	$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

	$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

	$aAptTMC115 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iCEC, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo']);
	//		echo "<br />TMC115:: ";
	//		print_r($aAptTMC115);

	$iWhidth = 1;
	$iPDirecta = $aAptTMC115[0];
	$iPDirectaX = $iPDirecta * 10;
	$iPercentil = $aAptTMC115[1];
	$iPercentilX = $iPercentil * 10;

	$sHtml .= '
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td> RAZONAMIENTO NUMÉRICO</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">Esta prueba mide la capacidad para comprender tablas y datos, cifras y precios, para dar respuestas correctas a cuestiones a ellas referidas. Es un test de dificultad y valor de predicción probado para cualquier puesto que implique manejo de datos cuantitativos, como ocurre con los puestos de relación comercial y venta.</p>
		            </div>
      				<table id="caja_puntos" border="0">
		        		<tr>
    							<td class="puntos_escala">
    								<table width="666" cellspacing="0" cellpadding="0">
        								<tr>
        									<td style="height:45px;">
        										<img src="' . $dirGestor . constant('DIR_WS_GRAF') . 'numeritosEstandar.jpg' . '" style="width:666px;">
        									</td>
        								</tr>
        								<tr>
        									<td width="81%" style="vertical-align:middle;">
												<p>El percentil :: ' . $iPercentil . '</p>
        										<img src="' . $dirGestor . constant('DIR_WS_GRAF') . 'bodoque_gigante_OLD.jpg' . '" style="width:' . str_replace(",", ".", (($iPercentil * 666) / 100)) . 'px;height:25px;">
        									</td>
        								</tr>
      							</table>
    								<table width="666" cellspacing="0" cellpadding="0" border="0">
    									<tr>

        									<td width="90" align="center" style="height:30px;border-right:2px solid #413D3E;">
        										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8') . '</font>
        									</td>
        									<td width="125" align="center" style="height:30px;border-right:2px solid #413D3E;">
        										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8') . '</font>
        									</td>
        									<td width="100" align="center" style="height:30px;">
        										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8') . '</font>
        									</td>
        									<td width="100" align="center" style="">

        									</td>
        								</tr>
        							</table>
    							</td>
		        		</tr>
		        	</table>';

	$sHtml .= '
							<div class="caja">
								<p class="textos">Se considera que puntuaciones inferiores a 30%, indican que la persona tiene un bajo potencial y, por tanto, puede tener ciertas dificultades para desempeñar adecuadamente las funciones del puesto en lo relativo al manejo de datos cuantitativos o en lo relativo al manejo de datos cuantitativos o de caracter verbal.</p>
							</div>
							<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
							<div class="caja">
								<p class="textos">Este informe competencial es generado desde los resultados de un Cuestionario respondido por los candidatos y refleja las respuestas dadas por ellos en el mismo. Es necesario tener siempre en consideración la posible subjetividad de la autoevaluación en la interpretación. Este informe se genera de una manera automática, por lo que siempre recomendamos que se complemente con información relativa a otras técnicas como la entrevista personal.</p>
							</div>
<!--        		</div> -->
        		<!--FIN DIV DESARROLLO-->
<!--        	</div> -->
        	<!--FIN DIV PAGINA-->
        	';

	// $sHtml.='
	// 			<div class="pagina">'. $sHtmlCab;
	// // INFORME DE COMPETENCIAS
	// $sHtml.='
	// 				<div class="desarrollo">
	// 		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
	// 					<div class="caja">
	// 						<p class="textos">Este informe competencial es generado desde los resultados de un Cuestionario respondido por los candidatos y refleja las respuestas dadas por ellos en el mismo. Es necesario tener siempre en consideración la posible subjetividad de la autoevaluación en la interpretación. Este informe se genera de una manera automática, por lo que siempre recomendamos que se complemente con información relativa a otras técnicas como la entrevista personal.</p>
	// 		        	</div>
	//         		    <div class="caja" style="margin-top:20px !important;">
	// 	        			<table border="0" style="margin-left: 5px;width: 100%;">
	// 									<tr>
	// 										<td rowspan="' . ($nTCompetencias+1) . '" class="blancob" style="text-align: center;background-color: #413D3E; width: 184px;">Competencias<br />Asesor Comercial<br /></td>
	// 									</tr>';
	// 								$listaTipoCompetencia->Move(0);
	// 								while(!$listaTipoCompetencia->EOF)
	// 								{
	// 									$sHtml.='			    <tr>
	// 																			<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/sercMazda/flechita.jpg" title="Flecha" /></td>
	// 																			<td >' . $listaTipoCompetencia->fields['nombre'] . '</td>
	// 												        		</tr>
	// 																		';
	// 									$listaTipoCompetencia->MoveNext();
	// 								 }
	// $sHtml.='
	// 			        	</table>
	// 		            </div>
	//         			<table id="caja_tit" border="0">
	// 		        		<tr>
	// 		        			<td class="blancob" style="text-align: left;">Competencias: Interpretación de indicadores y símbolos</td>
	// 		        		</tr>
	// 		        	</table>
	//         		    <div class="caja">
	//             			<p class="textos">Los siguientes símbolos e indicadores de color, son los que aparecen en el informe para la identificación rápida del nivel en el que se ha descrito el candidato en las diferentes competencias:</p>
	// 		            </div>
	//         		    <div class="caja">
	//             			<p class="textos" style="font-size: 12px;color:#413D3E;font-weight: bold;">Indicadores y símbolos</p>
	// 		            </div>
	// 					<div class="cajaTextos">
	// 						<div class="cajaTextos">
	// 							<p class="indicadores"><span class="verde">&nbsp;</span> <span class="txt">= Área de Fortaleza.</span></p>
	// 						</div>
	// 						<div class="cajaTextos">
	// 							<p class="indicadores"><span class="amarillo">&nbsp;</span><span class="txt">= Nivel Adecuado para el puesto.</span></p>
	// 						</div>
	// 						<div class="cajaTextos">
	// 							<p class="indicadores"></span><span class="rojo">&nbsp;</span> <span class="txt">= Área de Desarrollo.</span></p>
	// 						</div>
	// 					</div>
	// 				';
	//
	// $sHtml.='
	// 				</div>
	// 				<!--FIN DIV DESARROLLO-->
	//     		</div>
	//     		<!--FIN DIV PAGINA-->
	//     		<hr>
	//     		';

	//4-------------------------------------
	// $sHtml.='
	// 			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml .= '
<!--				<div class="desarrollo">
		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2> -->
            		<div class="cajaTextos-resultados">
                  <table border="0" width="100%" style="font-size: 14px;border-collapse: separate;border: 1px solid #ffff;">
		';
	$listaTipoCompetencia->Move(0);
	$iTC = 0;
	global $aPromediosTC;
	while (!$listaTipoCompetencia->EOF) {

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
		$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
		$cCompetencias->setIdPrueba($_POST['fIdPrueba']);
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		$nCompetencias = $listaCompetencias->recordCount();

		while (!$listaCompetencias->EOF) {
			$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
			//echo "<br />" . $sPosiCompetencias;
			$iPuntacion = $aPuntuacionesCompetencias[$sPosiCompetencias];
			$iPerfilIdeal = $aPerfilIdealCompetencias[$sPosiCompetencias];

			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($listaCompetencias->fields['idTipoCompetencia'], false) . "," . $conn->qstr($listaTipoCompetencia->fields['nombre'], false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr($listaCompetencias->fields['descripcion'], false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$sHtml .= '
      			<tr>';
			if ($iTC == 0) {
				$aPromedios = getPromedioTC($listaTipoCompetencia->fields['idTipoCompetencia']);

				$aPromediosTC[$listaTipoCompetencia->fields['idTipoCompetencia']][0] = $aPromedios[0];	//Promedio puntuación por Tipo competencia
				$aPromediosTC[$listaTipoCompetencia->fields['idTipoCompetencia']][1] = $aPromedios[1];	//Promedio perfil ideal por Tipo competencia
				$aPromediosTC[$listaTipoCompetencia->fields['idTipoCompetencia']][2] = $listaTipoCompetencia->fields['descripcion'];	//Definición del tipo de competencia
				$iPromedioTipoCompetencia = $aPromedios[0];
				$sumPromedioTipoCompetencia += number_format($iPromedioTipoCompetencia, 2);
				$sHtml .= '
							<td rowspan="' . $nCompetencias . '" class="cajaColor dato-informe" >' . $listaTipoCompetencia->fields['nombre'] . '<br /><br /><p style="color:#fff;">' . number_format($iPromedioTipoCompetencia, 2, ",", ".") . '</p></td>
							';
				$iTC++;
			}
			$sHtml .= '
							<td style="font-size: 10px;padding-left: 3px;padding-right: 3px;background-color: gainsboro;border: 1px solid #fff;white-space: nowrap;">' . $listaCompetencias->fields['nombre'] . '</td>
							<td style="background-color: gainsboro;text-align: center;width: 30px;border: 1px solid #fff; font-size: 10px;" >' . $iPuntacion . '</td>
							<td class="autoevaluacion" style="text-align: center;width: 16px;">' . bolosPuntuacion($iPuntacion, $iPerfilIdeal) . '</td>
							<td class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion, $iPerfilIdeal)  . '</p></td>
      			</tr>';
			$listaCompetencias->MoveNext();
		}
		$iTC = 0;
		$listaTipoCompetencia->MoveNext();
	}

	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr($consistenciaC, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml .= '
						</table>
					</div>
					<table id="caja_tit" border="0">
						<tr>
							<td>ESCALA DE CONSISTENCIA</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">La escala de consistencia indica cuanto de consistente ha sido el candidato al responder al cuestionario. Se considera que es adecuada cuando se sitúa en valores de 4 a 7. En caso de estar en valores entre 1-3 o 8-10 sería necesario contrastar con mayor profundidad su perfil en la entrevista.</p>
					</div>
					<div class="cajaTextos-resultados">
            <table border="0" width="100%" style="font-size: 14px;border-collapse: separate;border: 1px solid #ffff;">
							<tr>
								<td class="cajaColor dato-informe" ><p class="dato-informe" style="border: 1px solid #0072a8;"><em style="font-weight: bold;">Consistencia</em></p></td>
								<td style="text-align: center;width: 210px;font-weight: bold;">' . $consistenciaC . '</td>
								<td style="text-align: center;font-weight: bold;">' . textoConsistenciaC($consistenciaC) . '</td>
							</tr>
						</table>
					</div>

					<table id="caja_tit" border="0">
						<tr>
							<td>AJUSTE AL PERFIL DE MANAGER</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">Se considera que el grado de ajuste al perfil de Manager es adecuado cuando es igual o superior a 74%.</p>
					</div>
					<div class="cajaTextos-resultados">
            <table border="0" width="100%" style="font-size: 14px;border-collapse: separate;border: 1px solid #ffff;">
							<tr>
								<td class="cajaColor dato-informe" ><p class="dato-informe" style="border: 1px solid #0072a8;"><em style="font-weight: bold;">Grado de ajuste al perfil</em></p></td>
								<td style="text-align: center;width: 210px;font-weight: bold;">' . xCientoAjustePerfil(($sumPromedioTipoCompetencia / $listaTipoCompetencia->recordCount())) . '</td>
								<td style="text-align: center;font-weight: bold;">' . textoAjustePerfil(($sumPromedioTipoCompetencia / $listaTipoCompetencia->recordCount())) . '</td>
							</tr>
						</table>
					</div>
				';

	$sHtml .= '
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';


	return $sHtml;
}
//**************---------------------*********************-------------------
/*
	 * APTITUDES
	 */
function generarEntrevista($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
{
	global $dirGestor;
	global $documentRoot;

	global $conn;
	global $cBloquesDB;
	global $cEscalasDB;
	global $cEscalas_itemsDB;
	global $sEsp;
	global $cCandidato;
	global $comboESPECIALIDADESMB;
	global $cUtilidades;
	//-----------
	global $nTCompetencias;
	global $listaTipoCompetencia;
	global $cCompetencias;
	global $cCompetenciasDB;
	global $cPruebas;
	global $cProceso;
	global $cRespPruebas;
	global $consistenciaC;
	global $aPerfilIdealCompetencias;
	global $aPromediosTC;

	$sHtml = '
			<div class="pagina">' . $sHtmlCab;

	$sHtml .= '
			<div class="desarrollo">
				<h2 class="subtitulo">GUÍA PARA LA ENTREVISTA COMPETENCIAL</h2>
				<table id="caja_tit" border="0">
					<tr>
						<td class="blancob" style="text-align: center;">INTRODUCCIÓN</td>
					</tr>
				</table>
				<div class="caja">
					<p class="textos">A continuación, se adjunta un guion para realizar la entrevista competencial. Aparecen las competencias con su definición y las preguntas necesarias para poder sondearlas.</p>
					<p class="textos">Aunque se deben sondear todas las competencias, se hará hincapié en obtener información sobre aquellas que han sido identificadas como áreas de desarrollo.</p>
				</div>
				<table id="caja_tit" border="0">
					<tr>
						<td class="blancob" style="text-align: center;line-height:25px;">GUIÓN PARA LA ENTREVISTA</td>
					</tr>
				</table>
				<div class="caja">
					<p class="textos">1.- Repasar brevemente su trayectoria profesional realizando preguntas en base al CV y sondear la motivación:</p>
					<p class="textos">&nbsp;&nbsp;&nbsp;&nbsp;- ¿Coméntame brevemente cuál ha sido tu trayectoria profesional hasta la fecha? Sondear qué funciones ha realizado en cada puesto, los motivos de cambio de una empresa a otra...</p>
					<p class="textos">&nbsp;&nbsp;&nbsp;&nbsp;- ¿Qué es lo que te motiva de trabajar en este concesionario Mazda?, ¿Qué crees que podrías aportar?, ¿Qué crees que te podemos aportar nosotros?</p>
					<p class="textos">2.- A continuación, te voy a pedir que me hables de situaciones muy concretas que hayas vivido en experiencias del pasado. Necesito que seas muy concreto en tus respuestas, yo iré tomando nota de lo que me vayas comentando.</p>
				</div>

        	';
			
			
			//echo('<script type="text/javascript">
			//var users = ' . json_encode($aPromediosTC) . ';' .
			//'console.log("Esto es una prueba de users", users); </script>');


	$idTC = "25";	// Competencias de Negocio
	$iPuntacion = $aPromediosTC[$idTC][0];	// Competencias de Negocio
	$iPuntacionIdeal = $aPromediosTC[$idTC][1];
	$definiciónTC  = $aPromediosTC[$idTC][2];

	$sColor = colorPuntuacion($iPuntacion, $iPuntacionIdeal);
	
	$sHtml .= '
				<table width="100%" border="0">
					<tr>
						<td width="80%" height="30" class="textos negrob" style="padding: 5px;border: 1px solid #000;border-right: 0;border-bottom: 0;"><b>Competencias de Negocio</b></td>
						<td width="20%" class="textos negrob" style="white-space: nowrap;padding: 5px;background-color:' . $sColor . ';text-align: right;padding-right: 10px;border: 1px solid #000;border-left: 0;">' . textoPuntuacion($iPuntacion, $iPuntacionIdeal) . '</td>
					</tr>
					<tr>
						<td colspan="2" height="15" class="textos negrob" style="background-color: gainsboro;padding: 5px;border: 1px solid #000;border-top: 0;border-bottom: 0;"><b>Preguntas:</b></td>
					</tr>
					<tr>
						<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;">
							<ul style="list-style-type: lower-alpha; list-style-type: lower-alpha; margin-left: 25px; font-size: 12px;">
								<li>
									<p class="textos_preguntas">Capacidad analítica:</p>
									<ul class="inner">
										<li>
											<p class="textos_preguntas">¿Qué tipo de información analizas en tu día a día? ¿Qué es lo que te resulta más sencillo? ¿Y lo más complejo? ¿Podrías ponerme un ejemplo? ¿Qué es lo que hiciste en este caso concreto? ¿Qué tipo de variables manejaste? ¿Por qué esas y no otras? ¿Recurriste a alguien para que te ayudara con el análisis o lo hiciste de forma autónoma? ¿Detectaste algún riesgo? ¿Planteaste alguna acción posterior? ¿Cuál? ¿Qué impacto tuvo?</p>
										</li>
										<li>
											<p class="textos_preguntas">¿Trabajas con KPI´s? ¿Los defines tú o te los dan ya definidos? ¿Podrías contarme una situación en la que haya sido especialmente relevante trabajar con estos KPI´s? ¿Qué ocurrió? ¿En qué consistió tu gestión?</p>
										</li>
									</ul>
								</li>
								<li>
									<p class="textos_preguntas">Orientación a objetivos:</p>
									<ul class="inner">
										<li>
											<p class="textos_preguntas">¿Qué tipo de objetivos tienes? ¿Con qué frecuencia se te exigen (mensual / trimestral / anual)? ¿Cuáles fueron los últimos objetivos que te pidieron? ¿Los alcanzaste? ¿Qué aspectos influyeron para lograrlo / no lograrlo? ¿Y qué hiciste tú?</p>
										</li>
										<li>
											<p class="textos_preguntas">Piensa en alguna situación en la que te haya costado alcanzar un objetivo. ¿Qué es lo que ocurrió? ¿Por qué era difícil? ¿Cómo gestionaste esa situación? Si volvieras para atrás, ¿harías algo diferente? ¿El qué?</p>
										</li>
										<li>
											<p class="textos_preguntas">¿Qué haces para ayudar al equipo / departamento / área a alcanzar los objetivos? ¿Podrías hablarme de algún caso concreto en el que lo hayas hecho? ¿Qué paso? ¿Cómo lo gestionaste? ¿Cuál fue el resultado?</p>
										</li>
									</ul>
								</li>
								<li>
									<p class="textos_preguntas">Visión de negocio:</p>
									<ul class="inner">
										<li>
											<p class="textos_preguntas">¿Conoces la estrategia / objetivos de la organización? ¿Qué haces para estar al tanto de los mismos? ¿Podrías ponerme un ejemplo?</p>
										</li>
										<li>
											<p class="textos_preguntas">¿Consideras que estás al día del sector / mercado / competencia? ¿Qué haces para ello? Háblame de un caso concreto donde hayas realizado alguna acción para estar al tanto. ¿Qué hiciste? ¿Por qué? ¿Cuál fue el resultado?</p>
										</li>
										<li>
											<p class="textos_preguntas">Piensa en algún caso en el que hayas contribuido a los objetivos de la organización a un medio o largo plazo. ¿Qué hiciste? ¿Cómo lo hiciste? ¿Te apoyaste en alguien? ¿Cuál fue el resultado? ¿Lo mediste de alguna forma?</p>
										</li>
									</ul>
								</li>
							</ul>
						</td>
					</tr>
				</table><br />
				';
				
	$sHtml .= '
			</div>
			<!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
		<hr>
		';

		//---------------------------------
		$sHtml .= '
				<div class="pagina">' . $sHtmlCab . '
				<div class="desarrollo">
				<table id="caja_tit" border="0">
					<tr>
						<td class="blancob" style="text-align: center;line-height:25px;">GUÍA ESTRUCTURADA PARA LA ENTREVISTA</td>
					</tr>
				</table>
				';


	$idTC = "26";	// COMPETENCIAS PARA LA GESTIÓN DE PERSONA
	$iPuntacion = $aPromediosTC[$idTC][0];	// COMPETENCIAS PARA LA GESTIÓN DE PERSONA
	$iPuntacionIdeal = $aPromediosTC[$idTC][1];
	$definiciónTC  = $aPromediosTC[$idTC][2];
	$sColor = colorPuntuacion($iPuntacion, $iPuntacionIdeal);
	$sHtml .= '
		<table width="100%" border="0">
			<tr>
				<td width="80%" height="30" class="textos negrob" style="padding: 5px;border: 1px solid #000;border-right: 0;border-bottom: 0;"><b>Competencias para la Gestión de Personas</b></td>
				<td width="20%" class="textos negrob" style="white-space: nowrap;padding: 5px;background-color:' . $sColor . ';text-align: right;padding-right: 10px;border: 1px solid #000;border-left: 0;">' . textoPuntuacion($iPuntacion, $iPuntacionIdeal) . '</td>
			</tr>
			<tr>
				<td colspan="2" height="15" class="textos negrob" style="background-color: gainsboro;padding: 5px;border: 1px solid #000;border-top: 0;border-bottom: 0;"><b>Preguntas:</b></td>
			</tr>
			<tr>
				<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;">
					<ul style="list-style-type: lower-alpha; list-style-type: lower-alpha; margin-left: 25px; font-size: 12px;">
						<li>
							<p class="textos_preguntas">Dirigir:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">Piensa en algún caso reciente en el que hayas planteado objetivos a algún miembro de tu equipo. ¿De donde partían esos objetivos? ¿Hiciste algo concreto para asegurar que se cumplieran? ¿Tu colaborador los alcanzó? ¿Por qué? ¿Qué aspectos facilitaron / dificultaron su consecución? ¿Hubo algo de tu gestión que podría haberse hecho de otra forma?</p>
								</li>
							</ul>
						</li>
						<li>
							<p class="textos_preguntas">Inspirar:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">¿Qué haces para animar al equipo cuando está desmotivado? ¿Podrías pensar en alguna persona concreta y contarme qué ocurrió? ¿Por qué estaba desmotivada esa persona? ¿Qué hiciste tú para revertir la situación?</p>
								</li>
								<li>
									<p class="textos_preguntas">Ahora piensa en una situación en la que el equipo en su conjunto estuviera pasando por un mal momento. ¿Qué hiciste? ¿Qué impacto tuvo tu acción? ¿Conseguiste el resultado deseado? ¿Por qué?</p>
								</li>
							</ul>
						</li>
						<li>
							<p class="textos_preguntas">Empoderar:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">¿Sabes que necesidades de desarrollo tienen tus colaboradores? ¿Cómo lo sabes? ¿Podrías contarme un caso concreto y cómo gestionaste esa situación? ¿Cuál fue el resultado?</p>
								</li>
								<li>
									<p class="textos_preguntas">¿Delegas en tus colaboradores? ¿Qué tipo de tareas son las que delegas? ¿Qué criterios empleas a la hora de repartir las tareas? ¿Me podrías poner un ejemplo? ¿Qué riesgos asumiste en ese caso concreto al delegar la tarea? ¿Qué fue lo que ocurrió?</p>
								</li>
								<li>
									<p class="textos_preguntas">Piensa en alguna situación en que un colaborador no haya estado acertado con su aportación/respuesta. ¿Qué hiciste? ¿Por qué? ¿Cómo reaccionó? ¿Qué aprendiste de esta situación?</p>
								</li>
								<li>
									<p class="textos_preguntas">¿Das feedback a tus colaboradores? ¿Con qué frecuencia? ¿Qué tipo de feedback? Cuéntame el último que hayas dado. ¿Qué ocurrió?</p>
								</li>
							</ul>
						</li>
					</ul>
				</td>
			</tr>
		</table><br />
		';
	
	$idTC = "27";	// COMPETENCIAS PERSONALES
	$iPuntacion = $aPromediosTC[$idTC][0];	// COMPETENCIAS PERSONALES
	$iPuntacionIdeal = $aPromediosTC[$idTC][1];
	$definiciónTC  = $aPromediosTC[$idTC][2];
	$sColor = colorPuntuacion($iPuntacion, $iPuntacionIdeal);

	$sHtml .= '
				<table width="100%" border="0">
					<tr>
						<td width="80%" height="30" class="textos negrob" style="padding: 5px;border: 1px solid #000;border-right: 0;border-bottom: 0;"><b>Competencias Personales</b></td>
						<td width="20%" class="textos negrob" style="white-space: nowrap;padding: 5px;background-color:' . $sColor . ';text-align: right;padding-right: 10px;border: 1px solid #000;border-left: 0;">' . textoPuntuacion($iPuntacion, $iPuntacionIdeal) . '</td>
					</tr>
					<tr>
						<td colspan="2" height="15" class="textos negrob" style="background-color: gainsboro;padding: 5px;border: 1px solid #000;border-top: 0;border-bottom: 0;"><b>Preguntas:</b></td>
					</tr>
					<tr>
						<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;">
							<ul style="list-style-type: lower-alpha; list-style-type: lower-alpha; margin-left: 25px; font-size: 12px;">
								<li>
									<p class="textos_preguntas">Persuasión e influencia: -> se puede observar de manera transversal a lo largo de toda la entrevista</p>
									<ul class="inner">
										<li>
											<p class="textos_preguntas">Piensa en una situación de la que te sientas orgulloso/a por haber atraído a otra persona hacia tu punto de vista. ¿Qué ocurrió? ¿Qué hiciste? ¿Por qué has elegido esta situación y no otra?</p>
										</li>
										<li>
											<p class="textos_preguntas">Ahora piensa en una situación contraria, en la que, a pesar de tus intentos, no conseguiste atraer a la otra parte hacia tu punto de vista. ¿Qué ocurrió? ¿Por qué crees que no lograste convencer a la otra parte? ¿Cómo lo asumiste? ¿Podrías haberlo gestionado mejor?</p>
										</li>
									</ul>
								</li>
								<li>
									<p class="textos_preguntas">Colaboración:</p>
									<ul class="inner">
										<li>
											<p class="textos_preguntas">¿Colaboras con otras áreas para conseguir los objetivos de la organización / concesión? ¿Con qué áreas? ¿En qué consiste esa colaboración? ¿Cuándo fue la última vez que tu aportaste algo? ¿Qué fue lo que aportaste? ¿Por qué?</p>
										</li>
										<li>
											<p class="textos_preguntas">¿Alguna vez has promovido tú una colaboración con otro departamento o incluso con otra concesión / empresa? Cuéntame qué paso. ¿Cómo lo hiciste? ¿A quién recurriste? ¿Qué resultado esperabas? ¿Qué resultado obtuviste?</p>
										</li>
									</ul>
								</li>
							</ul>
						</td>
					</tr>
				</table><br />
			';
				
			$sHtml .= '
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
			<hr>
			';
	
			//---------------------------------
			$sHtml .= '
			<div class="pagina">' . $sHtmlCab . '
				<div class="desarrollo">
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;line-height:25px;">GUÍA ESTRUCTURADA PARA LA ENTREVISTA</td>
						</tr>
					</table>
			';

	$idTC = "28";	// COMPETENCIAS DE EJECUCIÓN
	$iPuntacion = $aPromediosTC[$idTC][0];	// COMPETENCIAS DE EJECUCIÓN
	$iPuntacionIdeal = $aPromediosTC[$idTC][1];
	$definiciónTC  = $aPromediosTC[$idTC][2];
	$sColor = colorPuntuacion($iPuntacion, $iPuntacionIdeal);

	$sHtml .= '
		<table width="100%" border="0">
			<tr>
				<td width="80%" height="30" class="textos negrob" style="padding: 5px;border: 1px solid #000;border-right: 0;border-bottom: 0;"><b>Competencias de Ejecución</b></td>
				<td width="20%" class="textos negrob" style="white-space: nowrap;padding: 5px;background-color:' . $sColor . ';text-align: right;padding-right: 10px;border: 1px solid #000;border-left: 0;">' . textoPuntuacion($iPuntacion, $iPuntacionIdeal) . '</td>
			</tr>
			<tr>
				<td colspan="2" height="15" class="textos negrob" style="background-color: gainsboro;padding: 5px;border: 1px solid #000;border-top: 0;border-bottom: 0;"><b>Preguntas:</b></td>
			</tr>
			<tr>
				<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;">
					<ul style="list-style-type: lower-alpha; list-style-type: lower-alpha; margin-left: 25px; font-size: 12px;">
						<li>
							<p class="textos_preguntas">Orientación a la acción y decisión:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">Cuando tomas decisiones, ¿sueles ser rápido en tu respuesta o prefieres sopesar las alternativas con más detenimiento? ¿Podrías ponerme un ejemplo? ¿Qué aspectos tuviste en consideración en este caso? ¿Asumiste algún riesgo? ¿Cuál?</p>
								</li>
								<li>
									<p class="textos_preguntas">Piensa en alguna situación en la que, tras tomar una decisión, te has dado cuenta de que no era la más acertada. ¿Cómo te diste cuenta? ¿Qué hiciste? ¿Cuál fue el impacto? ¿Aprendiste algo de esta situación? ¿Puedes contarme un ejemplo en el que hayas puesto en marcha ese aprendizaje?</p>
								</li>
								<li>
									<p class="textos_preguntas">Piensa en un problema nuevo que se haya producido y haya requerido una respuesta por tu parte. ¿Qué hiciste? ¿Valoraste alguna alternativa adicional? ¿Por qué te decantaste por una opción y no por la otra?</p>
								</li>
							</ul>
						</li>
						<li>
							<p class="textos_preguntas">Planificar:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">¿Cómo organizas tu actividad? ¿Y la de tu equipo? ¿Te apoyas en alguna herramienta específica para hacerlo? Cuéntame un caso en el que, a pesar de tu planificación, se te haya producido un imprevisto que hayas tenido que gestionar. ¿Qué hiciste? ¿Cómo lo hiciste?</p>
								</li>
								<li>
									<p class="textos_preguntas">¿Haces algo para controlar los imprevistos o para anticiparte a posibles desviaciones en los tiempos? ¿El qué? ¿Cuándo fue la última vez que tuviste que ponerlo en marcha?</p>
								</li>
								<li>
									<p class="textos_preguntas">Piensa en el proyecto / plan en el que hayas tenido que trabajar a más largo plazo. ¿Con qué plazos contabas? ¿Qué hiciste para alcanzar el objetivo planteado? ¿Qué resultados obtuviste? ¿Contaste con alguien / te apoyaste en alguien para lograrlo?</p>
								</li>
							</ul>
						</li>
						<li>
							<p class="textos_preguntas">Impulso del cambio:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">¿Cuál es el cambio más complejo al que te has enfrentado en el entorno laboral? ¿Por qué era complejo? ¿Qué hiciste para adaptarte? ¿Cómo te sentiste? ¿Qué impacto tuvo en la actividad?</p>
								</li>
								<li>
									<p class="textos_preguntas">Ahora piensa en un cambio organizativo que haya impactado también en tu equipo / departamento. ¿Hiciste algo para que lo pudieran acoger más fácilmente? ¿El qué? ¿Por qué lo hiciste? ¿Cuál fue el resultado?</p>
								</li>
								<li>
									<p class="textos_preguntas">¿Y algún caso en el que tú hayas planteado un cambio o una mejora para la organización? ¿En qué consistió? ¿Qué impacto tuvo? ¿A quién se lo planteaste? ¿Qué dificultades encontraste? ¿Cómo las gestionaste? ¿Qué aportó?</p>
								</li>
							</ul>
						</li>
					</ul>
				</td>
			</tr>
		</table>
						';

	$idTC = "29";	// VALORES PERSONALES
	$iPuntacion = $aPromediosTC[$idTC][0];	// VALORES PERSONALES
	$iPuntacionIdeal = $aPromediosTC[$idTC][1];
	$definiciónTC  = $aPromediosTC[$idTC][2];
	$sColor = colorPuntuacion($iPuntacion, $iPuntacionIdeal);

	$sHtml .= '
		<table width="100%" border="0">
			<tr>
				<td width="80%" height="30" class="textos negrob" style="padding: 5px;border: 1px solid #000;border-right: 0;border-bottom: 0;"><b>Valores Personales</b></td>
				<td width="20%" class="textos negrob" style="white-space: nowrap;padding: 5px;background-color:' . $sColor . ';text-align: right;padding-right: 10px;border: 1px solid #000;border-left: 0;">' . textoPuntuacion($iPuntacion, $iPuntacionIdeal) . '</td>
			</tr>
			<tr>
				<td colspan="2" height="15" class="textos negrob" style="background-color: gainsboro;padding: 5px;border: 1px solid #000;border-top: 0;border-bottom: 0;"><b>Preguntas:</b></td>
			</tr>
			<tr>
				<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;">
					<ul style="list-style-type: lower-alpha; list-style-type: lower-alpha; margin-left: 25px; font-size: 12px;">
						<li>
							<p class="textos_preguntas">Integridad:</p>
							<ul class="inner">
								<li>
									<p class="textos_preguntas">¿Conoces los procedimientos de la empresa / concesión? ¿Has tenido que enfrentarte a alguna situación con cliente en la que, por ser operativo, tuvieras que saltarte el procedimiento? ¿Qué hiciste en este caso? ¿Por qué? ¿Contaste con alguien? ¿Cuál fue el resultado?</p>
								</li>
								<li>
									<p class="textos_preguntas">Ahora piensa en alguna persona a la que hayas visto que no cumple con el procedimiento marcado. ¿Qué estaba haciendo? ¿Cuál era el impacto? ¿Qué hiciste? ¿Cómo respondió esta persona? ¿Cómo te sentiste?</p>
								</li>
							</ul>
						</li>
					</ul>
				</td>
			</tr>
		</table>
					';
	/* $idTC = "7";	// Valores Personales
	$iPuntacion = $aPromediosTC[$idTC][0];	// Valores Personales
	$iPuntacionIdeal = $aPromediosTC[$idTC][1];
	$definiciónTC  = $aPromediosTC[$idTC][2];
	$sColor = colorPuntuacion($iPuntacion, $iPuntacionIdeal);

	$sHtml .= '
								<table width="100%" border="0">
									<tr>
										<td width="80%" height="30" class="textos negrob" style="padding: 5px;border: 1px solid #000;border-right: 0;border-bottom: 0;"><b>Valores Personales</b></td>
										<td width="20%" class="textos negrob" style="white-space: nowrap;padding: 5px;background-color:' . $sColor . ';text-align: right;padding-right: 10px;border: 1px solid #000;border-left: 0;">' . textoPuntuacion($iPuntacion, $iPuntacionIdeal) . '</td>
									</tr>
									<tr>
										<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;"><p class="textos">' . $definiciónTC . '</p></td>
									</tr>
									<tr>
										<td colspan="2" height="15" class="textos negrob" style="background-color: gainsboro;padding: 5px;border: 1px solid #000;border-top: 0;border-bottom: 0;"><b>Preguntas:</b></td>
									</tr>
									<tr>
										<td colspan="2" style="border: 1px solid #000;padding: 5px;line-height: 1.5;text-align: justify;">
											<p class="textos_preguntas">Cuéntame una situación en la que hayas tenido que realizar un esfuerzo adicional para cumplir con lo que te habías comprometido. ¿Qué pasó?, ¿qué esfuerzo tuviste que hacer? ¿qué te resultó más difícil?</p>

											<p class="textos_preguntas">Háblame de una situación contraria en la que no hayas podido cumplir con tu compromiso. ¿Por qué no pudiste? ¿qué aprendiste? ¿qué harías diferente si volvieses a vivir esa situación?</p>

											<p class="textos_preguntas">Cuéntame una situación en la que no hayas podido respetar los procedimientos o normas establecidas en tu Compañía. ¿Por qué no pudiste cumplirlos? ¿Qué harías diferente si volvieses a vivir esa situación?</p>

											<p class="textos_preguntas">Cuéntame una situación en la que hayas tenido que hacer que otros cumplan con las normas o principios establecidos. ¿Por qué tuviste que intervenir? ¿cómo lo planteaste? ¿cómo reaccionaron?</p>
										</td>
									</tr>
								</table>
				'; */


	$sHtml .= '
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
    		';
	return $sHtml;
}

//**************---------------------*********************-------------------

function calalculaAPTITUDES($_idEmpresa, $_idProceso, $_idPrueba, $_codIdiomaIso2Prueba, $_idCandidato, $_idBaremo, $_especialidadMB = "")
{
	global $dirGestor;
	global $documentRoot;
	global $conn;

	require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Items/Items.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");

	//[$iPDirecta, $iPercentil, $IR, $IP, $POR, $iItemsPrueba]
	$aAptitudes = array();
	$iPDirecta = 0;
	$iPercentil = 0;
	$IR = 0.00;
	$IP = 0.00;
	$POR = 0.00;
	$iItemsPrueba = 0;
	// --
	$cItemsDB = new ItemsDB($conn);
	$cOpcionesDB = new OpcionesDB($conn);
	$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
	$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cRespuestasPruebasItems->setIdCandidato($_idCandidato);
	$cRespuestasPruebasItems->setIdPrueba($_idPrueba);
	$cRespuestasPruebasItems->setIdEmpresa($_idEmpresa);
	$cRespuestasPruebasItems->setIdProceso($_idProceso);
	$cRespuestasPruebasItems->setCodIdiomaIso2($_codIdiomaIso2Prueba);
	$cRespuestasPruebasItems->setOrderBy("idItem");
	$cRespuestasPruebasItems->setOrder("ASC");

	$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
	$listaRespItems = $conn->Execute($sqlRespItems);

	$cIt = new Items();
	$cIt->setIdPrueba($_idPrueba);
	$cIt->setIdPruebaHast($_idPrueba);
	$cIt->setCodIdiomaIso2($_codIdiomaIso2Prueba);

	if (!empty($_especialidadMB)) {
		$cIt->setTipoItem($_especialidadMB);
	}
	$sqlItemsPrueba = $cItemsDB->readLista($cIt);
	//echo "<br />" . $sqlItemsPrueba;
	$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
	$iItemsPrueba = $listaItemsPrueba->recordCount();
	//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
	$iPDirecta = 0;
	$iPercentil = 0;
	if ($listaRespItems->recordCount() > 0) {
		while (!$listaRespItems->EOF) {

			//Leemos el item para saber cual es la opción correcta
			$cItem = new Items();
			$cItem->setIdItem($listaRespItems->fields['idItem']);
			$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
			$cItem->setCodIdiomaIso2($_codIdiomaIso2Prueba);
			$cItem = $cItemsDB->readEntidad($cItem);

			//Leemos la opción para saber en código de la misma
			$cOpcion = new Opciones();
			$cOpcion->setIdItem($listaRespItems->fields['idItem']);
			$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
			$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
			$cOpcion->setCodIdiomaIso2($_codIdiomaIso2Prueba);
			$cOpcion = $cOpcionesDB->readEntidad($cOpcion);


			//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
			if (strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())) {
				//					echo $listaRespItems->fields['idItem'] . " - bien <br />";
				//Si coincide se le suma uno a la PDirecta.
				$iPDirecta++;
			}
			$listaRespItems->MoveNext();
		}

		$cBaremos_resultados = new Baremos_resultados();
		$cBaremos_resultados->setIdBaremo($_idBaremo);
		$cBaremos_resultados->setIdPrueba($_idPrueba);

		$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
		//			echo "<br />B" . $sqlBaremosResultados . "<br />";
		$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
		$ipMin = 0;
		$ipMax = 0;
		// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que
		// corresponde con la puntuación directa obtenida.
		if ($listaBaremosResultados->recordCount() > 0) {
			while (!$listaBaremosResultados->EOF) {

				$ipMin = $listaBaremosResultados->fields['puntMin'];
				$ipMax = $listaBaremosResultados->fields['puntMax'];
				if ($ipMin <= $iPDirecta && $iPDirecta <= $ipMax) {
					$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
				}
				$listaBaremosResultados->MoveNext();
			}
		}

		//			echo "pDirecta: " . $iPDirecta . "<br />";
		//			echo "pPercentil: " . $iPercentil . "<br />";
		//--
		$sUltimoItemRespondido = 0;
		if ($listaRespItems->recordCount() > 0) {
			$sUltimoItemRespondido = $listaRespItems->MoveLast();
			$sUltimoItemRespondido = $listaRespItems->fields['idItem'];
		}

		//		$IR = number_format($listaRespItems->recordCount() / $listaItemsPrueba->recordCount(),2);
		//IR= Último ítem respondido por el candidato/Nº total de ítems de la prueba.
		if ($listaItemsPrueba->recordCount() > 0) {
			$IR = number_format($sUltimoItemRespondido / $listaItemsPrueba->recordCount(), 2);
		}
		$sIR = str_replace(".", ",", $IR);
		//		$IP = number_format($iPDirecta/$listaRespItems->recordCount() ,2);
		//IP= Aciertos/Último ítem respondido por el candidato
		if ($sUltimoItemRespondido > 0) {
			$IP = number_format($iPDirecta / $sUltimoItemRespondido, 2);
		}
		$sIP = str_replace(".", ",", $IP);
		$POR = number_format($IR * $IP, 2);
		$sPOR = str_replace(".", ",", number_format($POR, 2));
	}
	$aAptitudes[] = $iPDirecta;
	$aAptitudes[] = $iPercentil;
	$aAptitudes[] = $IR;
	$aAptitudes[] = $IP;
	$aAptitudes[] = $POR;
	$aAptitudes[] = $iItemsPrueba;


	return $aAptitudes;
}


//*************------------------
function getPortada()
{
	global $dirGestor;
	global $documentRoot;
	global $comboESPECIALIDADESMB;
	global $cCandidato;
	global $conn;
	global $sEsp;

	require_once($documentRoot . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once($documentRoot . constant("DIR_WS_COM") . "Empresas/Empresas.php");
	$cEmpresasDB = new EmpresasDB($conn);
	$cEmpresas = new Empresas();
	$cEmpresas->setIdEmpresa($cCandidato->getIdEmpresa());
	$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
	//PORTADA
	$sHtml = '
			<div class="pagina portada">
		    	<img src="' . $dirGestor . 'graf/sercMazda/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"></h1>';
	if ($_POST['fIdTipoInforme'] != 11) {
		$sHtml .= 		'<div id="txt_infome"><p>Informe Selección<br />Manager</p></div>';
	} else {
		$sHtml .= 		'<div id="txt_infome_narrativo"><p>Informe Selección<br />Manager</p></div>';
	}
	$sHtml .= 		'<div id="txt_infome_narrativo"><p>' . $cEmpresas->getNombre() . '</p></div>';
	$sHtml .= '
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> ' . date("d/m/Y") . '</p>
				</div>
		    	<!--<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>-->
			</div>
			<!--FIN DIV PAGINA-->
      <hr>
			';
	//		$sHtml.=	constant("_NEWPAGE");
	//FIN PORTADA
	return $sHtml;
}
function getContraPortada()
{
	global $dirGestor;
	global $documentRoot;
	$sHtml = '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . $dirGestor . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
			</div>
			<!--FIN DIV PAGINA-->
		';
}

function getPromedioTC($idTC)
{
	global $dirGestor;
	global $documentRoot;
	global $conn;
	global $cCompetencias;
	global $cCompetenciasDB;
	global $aPuntuacionesCompetencias;
	global $cTipos_competenciasDB;

	$cTipos_competencias = new Tipos_competencias();
	$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
	$cTipos_competencias->setOrderBy("idTipoCompetencia");
	$cTipos_competencias->setOrder("ASC");
	$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
	//  	echo "<br />-->" . $sqlTipos_competencias . "";
	$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
	$nTCompetencias = 0;
	$listaTipoCompetencia->Move(0);
	$iPuntacion = 0;
	$iPuntacionPerfilIdeal = 0;
	$nCompetencias = 0;
	while (!$listaTipoCompetencia->EOF) {
		if ($listaTipoCompetencia->fields['idTipoCompetencia'] == $idTC) {
			$cCompetencias = new Competencias();
			$cCompetencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
			$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
			$cCompetencias->setIdPrueba($_POST['fIdPrueba']);
			$cCompetencias->setOrderBy("idCompetencia");
			$cCompetencias->setOrder("ASC");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencias += $listaCompetencias->recordCount();

			while (!$listaCompetencias->EOF) {
				$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
				$iPuntacion += $aPuntuacionesCompetencias[$sPosiCompetencias];
				$iPuntacionPerfilIdeal += trim($listaCompetencias->fields['descripcion']);
				$listaCompetencias->MoveNext();
			}
		}
		
		$listaTipoCompetencia->MoveNext();
	}
	$aRetorno = array();
	
	$aRetorno[0] = ($iPuntacion / $nCompetencias);
	$aRetorno[1] = ($iPuntacionPerfilIdeal / $nCompetencias);
	return $aRetorno;
}
/******************************************************************
 * FIN Funciones para la generación del Informe
 ******************************************************************/
