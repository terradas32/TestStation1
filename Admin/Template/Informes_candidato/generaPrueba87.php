<?php
///////////////////////////////////
//Generación de informe SEAS de MB
//////////////////////////////////

//tipo prisma CUESTIONARIO DE PERSONALIDAD LABORAL CPL (87) ->Lanza informe completo

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
		$cItems_inversosDB = new Items_inversosDB($conn);
		$cItems_inversos = new Items_inversos();
		$iCPL=87;
		$cItems_inversos->setIdPrueba($iCPL);
		$cItems_inversos->setIdPruebaHast($iCPL);
		$sqlInversos = $cItems_inversosDB->readLista($cItems_inversos);
//		echo "<br />" . $sqlInversos;
		$listaInversos = $conn->Execute($sqlInversos);
		$nInversos = $listaInversos->recordCount();
		$aInversos = array();
		if($nInversos > 0){
			$i=0;
			while(!$listaInversos->EOF){
				$aInversos[$i] = $listaInversos->fields['idItem'];
				$i++;
				$listaInversos->MoveNext();
			}
		}
		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

		// CÁLCULOS GLOBALES PARA ESCALAS,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//		echo "<br />sqlEscalas_items::" . $sqlEscalas_items . "";
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
//		echo "<br />222-->sBloques::" . $sBloques;
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
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
		$nBloques= $listaBloques->recordCount();
		$aPuntuaciones = array();
		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		while(!$listaEscalas->EOF){
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
				        $nEscalas_items =$listaEscalas_items->recordCount();

				        $iPd = 0;
				        if($nEscalas_items > 0){
				        	while(!$listaEscalas_items->EOF){
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

				        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
										$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
										$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
										$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
										$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
										$cRespuestas_pruebas_items->setIdItem($listaEscalas_items->fields['idItem']);

										$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

				        		if(array_search($listaEscalas_items->fields['idItem'], $aInversos) === false){
									//MEJOR => 2 PEOR => 0 VACIO => 1
									switch ($cRespuestas_pruebas_items->getIdOpcion())
									{
										case '1':	// Mejor
											$iPd += 2;
											break;
										case '2':	// Peor
											$iPd += 0;
											break;
										default:	// Sin contestar opcion 0 en respuestas
											$iPd += 1;
											break;
									}
//					       			$iPd = $iPd + $cRespuestas_pruebas_items->getIdOpcion();
					       		}else{
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

				        $iPBaremada=0;
				        $nBaremos = $listaBaremos_resultado->recordCount();
				        if($nBaremos > 0){
				        	while(!$listaBaremos_resultado->EOF)
				        	{
				        		if($iPd <= $listaBaremos_resultado->fields['puntMax'] && $iPd >= $listaBaremos_resultado->fields['puntMin'])
				        		{
				        			$iPBaremada = 	$listaBaremos_resultado->fields['puntBaremada'];
				        		}
				        		$listaBaremos_resultado->MoveNext();
				        	}
				        }

				       	$sPosi = $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'];
				       	$sNombreBE = $listaBloques->fields['nombre'] . "-" . $listaEscalas->fields['nombre'];

//				       	echo "<br />---------->[" . $sPosi . "][" . $sNombreBE . "][PD:" . $iPd . "][PB:" . $iPBaremada . "]";
				       	$aPuntuaciones[$sPosi] =  $iPBaremada;

				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
	// FIN CALCULOS GLOBALES ESCALAS

	//CALCULOS GLOBALES COMPETENCIAS
		$cBaremos_resultados_competenciasDB = new Baremos_resultados_competenciasDB($conn);
		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
//      	echo "<br />-->" . $sqlTipos_competencias . "";
		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();
		$aPuntuacionesCompetencias = array();
		if($nTCompetencias > 0){
			while(!$listaTipoCompetencia->EOF){

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
			 	$nCompetencias=$listaCompetencias->recordCount();
			 	if($nCompetencias > 0){
			 		while(!$listaCompetencias->EOF)
					{

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
				        $nCompetencias_items =$listaCompetencias_items->recordCount();
				        $iPdCompetencias = 0;
				        if($nCompetencias_items > 0)
								{
				        	while(!$listaCompetencias_items->EOF)
									{
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
										$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
										$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
										$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
										$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
										$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

										$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
//				        		echo "<br />ITEM:" . $listaCompetencias_items->fields['idItem'] . " - opcion:: " . $cRespuestas_pruebas_items->getIdOpcion() . " DESC:: " . $cRespuestas_pruebas_items->getDescOpcion();
								//MEJOR => 2 PEOR => 0 VACIO => 1

										if(array_search($listaCompetencias_items->fields['idItem'], $aInversos) === false)
										{
											//MEJOR => 2 PEOR => 0 VACIO => 1
											switch ($cRespuestas_pruebas_items->getIdOpcion())
											{
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
											//					       			$iPd = $iPd + $cRespuestas_pruebas_items->getIdOpcion();
							       }else{
											//					       			echo "<br />" . $listaEscalas_items->fields['idItem'];
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
				        $iPBaremadaCompetencias=0;
				        $nBaremosC = $listaBaremos_resultado_competencia->recordCount();
				        if($nBaremosC>0){
				        	while(!$listaBaremos_resultado_competencia->EOF){

				        		if($iPdCompetencias <= $listaBaremos_resultado_competencia->fields['puntMax'] && $iPdCompetencias >= $listaBaremos_resultado_competencia->fields['puntMin']){
				        			$iPBaremadaCompetencias = 	$listaBaremos_resultado_competencia->fields['puntBaremada'];
				        		}
				        		$listaBaremos_resultado_competencia->MoveNext();
				        	}
				        }

				       	$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
				       	$sNombreC = $listaTipoCompetencia->fields['nombre'] . "-" . $listaCompetencias->fields['nombre'];
				       	$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPBaremadaCompetencias;
//				       	echo "<br />" . $sPosiCompetencias . " " . $listaCompetencias->fields['nombre'] . " - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias;
//				       	echo "<br />---------->[" . $sPosiCompetencias . "][" . $sNombreC . "] - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias;
				        $listaCompetencias->MoveNext();
			 		}
			 	}
			 	$listaTipoCompetencia->MoveNext();
			 }
		 }

		//FIN CALCULOS GLOBALES COMPETENCIAS

		$cNivelesjerarquicos = new Nivelesjerarquicos();
		$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
		$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

		@set_time_limit(0);
		ini_set("memory_limit","1024M");
//		ini_set("max_execution_time","600");
		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		define ('_NEWPAGE', '<!--NewPage-->');
		$_HEADER = '';
		$sHtmlCab	= '';
		$sHtml		= '';
		$sHtmlFin	= '';
		//$aux			= $this->conn;

		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/seas/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/seas/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/seas/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>SEAS</title>
					<style type="text/css">
					<!--
					-->
					</style>
				</head>
			<body>';
$sHtmlFin .='
	</body>
	</html>';

	//$sFechaCon = $this->convertir_fecha($cEntidadEmpresas->getFechaInscripcion());

	//$sFecha = explode(" " , $sFechaCon);
	$sHtmlCab .='<div class="cabecera">
					<table width="100%" border="0">
						<tr>
		    				<td class="nombre">
										<p class="textos">' . constant("STR_SR_A") . ' '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/seas/img/sp.gif" title="logo"/>
						    </td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '</p>
						    </td>
					    </tr>
				    </table>
				</div>
		';
		$_HEADERz='<div class="cabecera">
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
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

		$comboESPECIALIDADESMB	= new Combo($conn,"fEspecialidadMB","idEspecialidadMB","descripcion","Descripcion","especialidadesmb","","","","","","");
		$sEsp = $comboESPECIALIDADESMB->getDescripcionCombo($cCandidato->getEspecialidadMB());


///////////
////// Calculo consistencia GENERAL
///////////

					$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
					$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
					$i=0;
					$cEscalas_items=  new Escalas_items();
					$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
					$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
					$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
					$rsEscalas_items = $conn->Execute($sqlEscalas_items);
					$sBloques = "";
					while(!$rsEscalas_items->EOF){
						$sBloques .="," . $rsEscalas_items->fields['idBloque'];
						$rsEscalas_items->MoveNext();
					}
//					echo "<br />sBloques::" . $sBloques;
					if (!empty($sBloques)){
						$sBloques = substr($sBloques,1);
					}
					$cBloques = new Bloques();
					$cBloques->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cBloques->setIdBloque($sBloques);
					$cBloques->setOrderBy("idBloque");
					$cBloques->setOrder("ASC");
					$sqlBloques = $cBloquesDB->readLista($cBloques);
					$listaBloques = $conn->Execute($sqlBloques);

					$iPosiImg=0;
					$iPGlobal = 0;
					$nBloques= $listaBloques->recordCount();
				 	$aSeparadorNum = array(1,4);
				 	$iSeparadorNum =0;
					if($nBloques>0){
						while(!$listaBloques->EOF)
						{

							$cEscalas = new Escalas();
						 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
						 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
						 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
						 	$cEscalas->setOrderBy("idEscala");
						 	$cEscalas->setOrder("ASC");
						 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
						 	$listaEscalas = $conn->Execute($sqlEscalas);
						 	$nEscalas=$listaEscalas->recordCount();
						 	$nVueltas = 1;
						 	if($nEscalas > 0){
						 		while(!$listaEscalas->EOF){
							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
							        $listaEscalas->MoveNext();
						 		}
						 	}
						 	$iSeparadorNum++;
						 	$iPosiImg++;
						 	$listaBloques->MoveNext();
						}
					 }

				    $consistencia = baremo_C(number_format(sqrt($iPGlobal/14)*100 ,0));
//echo "G.C.::" . $consistencia;
//// FIN de consistencia GENERAL
    $aSQLPuntuacionesPPL = array();
    $aSQLPuntuacionesC = array();

		switch ($_POST['fIdTipoInforme'])
		{
			case(3);//Informe Completo
				//FUNCIÓN PARA generar informe SEAS
				$sHtml.= getPortada();
				$sHtml.= generarSEAS($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab,$_POST['fCodIdiomaIso2']);
				$sHtml.= getContraPortada();
				break;
			case(56);//Informe Academia de Formación

				$sHtml.= generarAcademia($aPuntuaciones,$aPuntuacionesCompetencias, $sHtmlCab,$_POST['fCodIdiomaIso2']);
				break;

		}


if (!isset($NOGenerarFICHERO_INFORME))
{
  if (!empty($sHtml))
	{
		$replace = array('@', '.');
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" .$_POST['fIdTipoInforme'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		$_fichero = $spath . $sDirImg . $sNombre . ".html";
		//$cEntidad->chk_dir($spath . $sDirImg, 0777);

		if(is_file($_fichero)){
			unlink($_fichero);
		}
		error_log(utf8_decode($sHtmlInicio . $sHtml . $sHtmlFin), 3, $_fichero);
	}
	//Si ha pulsado PDF
	if ($_POST['MODO'] != constant("MNT_EXPORTA_HTML"))
	{
//		error_reporting(E_ALL);
//		ini_set("display_errors","1");
		if (ini_get("pcre.backtrack_limit") < 2000000) { ini_set("pcre.backtrack_limit",2000000); };
		@set_time_limit(0);
		@define("OUTPUT_FILE_DIRECTORY", $spath . $sDirImg);
//		echo "LLEGO PDF";exit;

		$header_html    = $_HEADER;

    $footer_html    =  mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . str_repeat(" ", 30) . constant("STR_PIE_INFORMES");
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
		if ($pd<=132){ $baremo_C=1;}
		if ($pd>=133 && $pd<=148){$baremo_C=2;}
		if ($pd>=149 && $pd<=164){$baremo_C=3;}
		if ($pd>=165 && $pd<=180){$baremo_C=4;}
		if ($pd>=181 && $pd<=197){$baremo_C=5;}
		if ($pd>=198 && $pd<=213){$baremo_C=6;}
		if ($pd>=214 && $pd<=229){$baremo_C=7;}
		if ($pd>=230 && $pd<=245){$baremo_C=8;}
		if ($pd>=246 && $pd<=262){$baremo_C=9;}
		if ($pd>=263){ $baremo_C=10;}
		return $baremo_C;
	}
	//Funcion que devuelve un texto a la parte del informe de competencias de seas
	function textoDefinicion($puntuacion){
		$str="";

		if($puntuacion ==1 || $puntuacion==2){
			$str="Área con clara necesidad de Desarrollo";	//"NUNCA";
		}
		if($puntuacion ==3){
			$str="Área de Desarrollo";	//"CASI NUNCA";
		}
		if($puntuacion ==4){
			$str="Nivel Adecuado para el puesto";	//"A VECES";
		}
		if($puntuacion ==5){
			$str="Área de Fortaleza";	//"CASI SIEMPRE";
		}
		if($puntuacion ==6){
			$str="Área de clara Fortaleza";	//"SIEMPRE";
		}
		return $str;
	}
	//Funcion que devuelve un texto a la parte del informe de competencias de seas
	function textoPuntuacion($puntuacion){
		$str="";

		if($puntuacion ==1 || $puntuacion==2){
			$str="Área con clara necesidad de Desarrollo";	//"NUNCA";
		}
		if($puntuacion ==3){
			$str="Área de Desarrollo";	//"CASI NUNCA";
		}
		if($puntuacion ==4){
			$str="Nivel Adecuado para el puesto";	//"A VECES";
		}
		if($puntuacion ==5){
			$str="Área de Fortaleza";	//"CASI SIEMPRE";
		}
		if($puntuacion ==6){
			$str="Área de clara Fortaleza";	//"SIEMPRE";
		}
		return $str;
	}
	function textoEspecializacion($puntuacion){
		$str="";
		if($puntuacion <=2 ){
			$str="Área con clara necesidad de Desarrollo";
		}
		if($puntuacion ==3){
			$str="Área de Desarrollo";
		}
		if($puntuacion ==4){
			$str="Nivel Adecuado para el puesto";
		}
		if($puntuacion ==5){
			$str="Área de Fortaleza";
		}
		if($puntuacion >=6){
			$str="Área de clara Fortaleza";
		}
		return $str;
	}

	function bolosPuntuacion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str='
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				';	//"ÁREA CLAVE DE MEJORA";
		}
		if($puntuacion ==3 ){
			$str='
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				';	//"ÁREA POTENCIAL DESARROLLO";
		}
		if($puntuacion ==4){
			$str='
				<span class="amarillo">&nbsp;</span>
				';	//"ÁREA EN DESARROLLO";
		}
		if($puntuacion ==5){
			$str='
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				';	//"ÁREA POTENCIAL FORTALEZA";
		}
		if($puntuacion ==6){
			$str='
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				';	//"ÁREA DE FORTALEZA";
		}
		return $str;
	}
	function bolosEspecializacion($puntuacion){
		$str="";
		if($puntuacion <=2){
			$str='
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				';	//"ÁREA CLAVE DE MEJORA";
		}
		if($puntuacion ==3 ){
			$str='
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				';	//"ÁREA POTENCIAL DESARROLLO";
		}
		if($puntuacion ==4){
			$str='
				<span class="amarillo">&nbsp;</span>
				';	//"ÁREA EN DESARROLLO";
		}
		if($puntuacion ==5){
			$str='
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				';	//"ÁREA POTENCIAL FORTALEZA";
		}
		if($puntuacion >=6){
			$str='
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				';	//"ÁREA DE FORTALEZA";
		}
		return $str;
	}

	// Si llega MEJOR devolver 0
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 1
	function getInversoPrisma($valor){
		$inv=0;

		//MEJOR => 2 PEOR => 0 VACIO => 1
		switch ($valor)
		{
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
	 * Imforme completo SEAS
	 */
	function generarSEAS($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $sEsp;
		global $cCandidato;
		global $comboESPECIALIDADESMB;
		global $cUtilidades;

		$sSQLExport ="";
		global $aSQLPuntuacionesPPL;
		global $aSQLPuntuacionesC;
		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;


		$iCCT= "84";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iCCT);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptCCT84 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iCCT, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo'], $cCandidato->getEspecialidadMB());
//		echo "<br />generar SEAS";
//		echo "<br />[iPDirecta, iPercentil, IR, IP, POR, iItemsPrueba]";
//		echo "<br />CUESTIONARIO DE CONOCIMIENTOS TÉCNICOS - CCT84:: ";
//		print_r($aAptCCT84);

		$iWhidth = 1;
		$iPDirecta= $aAptCCT84[0];
		$iPEspecializacion= $iPDirecta;
		$iPDirectaX= number_format((($iPDirecta*100)/$aAptCCT84[5]), 2, '.', '');
		$iAutoevaluacion = $cCandidato->getNivelConocimientoMB();
		$iAutoevaluacionX = $iAutoevaluacion*10;
		$iPercentil= 0;

		//PÁGINA INTRODUCCIÓN, 1
		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		$sHtml.= '
				<div class="desarrollo">
		        	<h2 class="subtitulo">' . mb_strtoupper(constant("STR_APTITUDES"), 'UTF-8') . '</h2>
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td>TEST CONOCIMIENTOS</td>
		        			<td class="grisb" style="text-align: center;">Sector</td>
		        			<td class="blancob">' . $sEsp . '</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">' . constant("STR_SEAS_TEST_CONOCIMIENTOS_P1") . '</p>
		            </div>';
		$sBorrar= '<table id="caja_tit_puntos" border="0">
		        		<tr>
		        			<td class="grisb">Autoevaluación</td>
		        		</tr>
		        	</table>
       				<table id="caja_puntos" border="0">
		        		<tr>
		        			<td class="puntos_num">' . $iAutoevaluacionX . '</td>
							<td class="puntos_escala">
								<table width="100%" cellspacing="0" cellpadding="0">
    								<tr>
    									<td style="height:45px;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF') . 'numeritosEstandar.jpg'.'" style="width:100%;">
    									</td>
    								</tr>
    								<tr>
    									<td width="81%" style="vertical-align:middle;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.$iWhidth*$iAutoevaluacionX.'%;height:25px;">
    									</td>
    								</tr>
    							</table>
							</td>
		        		</tr>
		        	</table>';

		$sHtml.= '
							<table id="caja_tit_puntos" border="0">
		        		<tr>
		        			<td class="grisb">Resultados Obtenidos</td>
		        		</tr>
		        	</table>
							<table id="caja_puntos" border="0">
		        		<tr>
		        			<td class="puntos_num"><span class="pdOut">P.D.</span><br /><br /> ' . $iPDirecta . '</td>
									<td class="puntos_escala">
										<table width="564" cellspacing="0" cellpadding="0">
	    								<tr>
	    									<td style="height:45px;">
	    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:564px;">
	    									</td>
	    								</tr>
	    								<tr>
	    									<td width="81%" style="vertical-align:middle;">
	    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.(($iPDirectaX*564)/100).'px;height:25px;">
	    									</td>
	    								</tr>
	    							</table>
									</td>
		        		</tr>
		        	</table>';

		$iTMC= "85";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iTMC);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptTMC85 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iTMC, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo']);
//		echo "<br />TEST DE MEMORIA CONTEXTUAL - TMC85:: ";
//		print_r($aAptTMC85);

		$iWhidth = 1;
		$iPDirecta= $aAptTMC85[0];
		$iPDirectaX= $iPDirecta*10;
		$iPercentil= $aAptTMC85[1];
		$iPercentilX= $iPercentil*10;

		$sHtml.= '
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td>TEST MEMORIA</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">' . constant("STR_SEAS_TEST_MEMORIA_P1") . '</p>
									<p class="textos">' . constant("STR_SEAS_TEST_MEMORIA_P2") . '</p>
		            </div>
      				<table id="caja_puntos" border="0">
		        		<tr>
		        			<td class="puntos_num"><span class="pdOut">P.D</span>&nbsp;&nbsp;' . $iPDirecta . '<br /><br /><span class="pdOut">P.C</span>&nbsp;&nbsp;' . $iPercentil . '</td>
									<td class="puntos_escala">
										<table width="564" cellspacing="0" cellpadding="0">
		    								<tr>
		    									<td style="height:45px;">
		    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:564px;">
		    									</td>
		    								</tr>
		    								<tr>
		    									<td width="81%" style="vertical-align:middle;">
		    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.(($iPercentil*564)/100).'px;height:25px;">
		    									</td>
		    								</tr>
	    							</table>
										<table width="564" cellspacing="0" cellpadding="0" border="0">
											<tr>
		    									<td width="28%" align="center" style="height:30px;border-right:2px solid #FF8000;">
		    										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
		    									</td>
		    									<td width="30%" align="center" style="height:30px;border-right:2px solid #FF8000;">
		    										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
		    									</td>
		    									<td width="34%" align="center" style="height:30px;">
		    										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
		    									</td>
		    									<td width="19%" align="center" style="">

		    									</td>
		    								</tr>
		    						</table>
									</td>
		        		</tr>
		        	</table>';

		$iTOP= "86";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iTOP);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptTOP86 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iTOP, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo']);
//		echo "<br />TEST DE ORGANIZACIÓN DE PROCESOS - TOP86:: ";
//		print_r($aAptTOP86);

		$iWhidth = 1;
		$iPDirecta= $aAptTOP86[0];
		$iPDirectaX= $iPDirecta*10;
		$iPercentil= $aAptTOP86[1];
		$iPercentilX= $iPercentil*10;

				$sHtml.= '
		  			<table id="caja_tit" border="0">
		        		<tr>
		        			<td>TEST FLEXIBILIDAD MENTAL</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">' . constant("STR_SEAS_TEST_FLEXIBILIDAD_MENTAL_P1") . '</p>
		            </div>
      				<table id="caja_puntos" border="0">
		        		<tr>
		        			<td class="puntos_num"><span class="pdOut">P.D</span>&nbsp;&nbsp;' . $iPDirecta . '<br /><br /><span class="pdOut">P.C</span>&nbsp;&nbsp;' . $iPercentil . '</td>
							<td class="puntos_escala">
								<table width="564" cellspacing="0" cellpadding="0">
    								<tr>
    									<td style="height:45px;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:564px;">
    									</td>
    								</tr>
    								<tr>
    									<td width="81%" style="vertical-align:middle;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.(($iPercentil*564)/100).'px;height:25px;">
    									</td>
    								</tr>
    							</table>
								<table width="564" cellspacing="0" cellpadding="0" border="0">
									<tr>

    									<td width="28%" align="center" style="height:30px;border-right:2px solid #FF8000;">
    										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
    									</td>
    									<td width="30%" align="center" style="height:30px;border-right:2px solid #FF8000;">
    										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
    									</td>
    									<td width="34%" align="center" style="height:30px;">
    										<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
    									</td>
    									<td width="19%" align="center" style="">

    									</td>
    								</tr>
    							</table>
							</td>
		        		</tr>
		        	</table>
					<table style="font-size: 13px;" width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="9%" align="center" >
								<font style="font-weight: bold;">P.D</font>
    						</td>
    						<td width="91%" align="left">Puntuación Directa. Es el número de preguntas contestadas correctamente.
    						</td>
    					</tr>
    					<tr>
    						<td>&nbsp;</td>
    						<td>&nbsp;</td>
    					</tr>
						<tr>
							<td align="center" >
								<font style="font-weight: bold;">P.C</font>
    						</td>
    						<td align="left" >Puntuación Centil. Porcentaje obtenido en función de la comparativa de su resultados con la muestra de personas que han realizado la prueba.
    						</td>
    					</tr>

    				</table>
        		</div>
        		<!--FIN DIV DESARROLLO-->
        	</div>
        	<!--FIN DIV PAGINA-->
        	<hr>
        	';
		//		$sHtml.=	constant("_NEWPAGE");
		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA PERFIL PERSONALIDAD LABORAL
		$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">PERFIL DEL CUESTIONARIO DE PERSONALIDADAD LABORAL</h2>
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td class="blancob" style="text-align: center;">Introducción</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">Este informe ofrece información general sobre el perfil profesional de un/a candidato/a en base a una serie de escalas que se han detectado como relevantes en perfiles de éxito de Asesores de Servicio. Es una interpretación experta que sirve como complemento a otros informes de evaluación y desarrollo y para predecir comportamientos laborales.</p>
						<p class="textos">El informe analiza las respuestas dadas por el/la candidato/a al Cuestionario, indicando su perfil de preferencias y actitudes en la vida laboral. En él la información resultante, se distinguen cuatro bloques o áreas generales que ofrecen información completa sobre los aspectos más significativos en el entorno laboral: Dinamismo y Energía, Relación con los demás, Estructura mental y Emociones.</p>
						<p class="textos">Se debe tener en cuenta que el perfil resultado de este Cuestionario procede de las evaluaciones o percepciones que la persona tiene de sí misma. No procede de las evaluaciones que otros hagan de él/ella. Está demostrado estadísticamente el gran valor de las autoevaluaciones. La calidad, fiabilidad y validez de este informe están condicionadas por la franqueza y colaboración con las que la persona haya respondido a las preguntas y a su nivel de autoanálisis.</p>
						<p class="textos">Este informe debe ser tratado confidencialmente. El valor de los resultados tiene una validez aproximada de 18 a 24 meses, y debe ser relacionado directamente con la situación actual y contexto del individuo.</p>
		            </div>
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td class="blancob" style="text-align: center;">Consistencia</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">La Escala de Consistencia nos proporciona indicadores generales del estilo de respuesta o actitud del candidato/a. Es un Cuestionario de "respuesta obligada", lo que delimita bastante la "distorsión motivacional" o tendencia a dar un perfil deseado o conveniente.</p>
						<p class="textos">El informe analiza las respuestas dadas por el/la candidato/a al Cuestionario, indicando su perfil de preferencias y actitudes en la vida laboral. En él la información resultante, se distinguen cuatro bloques o áreas generales que ofrecen información completa sobre los aspectos más significativos en el entorno laboral: Dinamismo y Energía, Relación con los demás, Estructura mental y Emociones.</p>
						<p class="textos">La puntuación en "Consistencia" expresa el grado en que el candidato/a se ha esforzado en mantener una coherencia o congruencia entre unas y otras respuestas al Cuestionario. Normalmente esto se explica "en el sentido de dar buena imagen", pero podría ser que se diera "coherencia en dar mala imagen", para lo cual basta analizar puntuaciones estremas (9 y 10 / 1 y 2) y ver si están en los polos positivos o negativos de las escalas.</p>
						<p class="textos"><font style="font-weight: bold;">Ejemplo:<br /><br /></font>
							<img class="ejemCons" src="'.constant("DIR_WS_GESTOR").'graf/seas/' . $_POST['fCodIdiomaIso2']. '/ejemploConsistenciaMB.jpg" alt="Escala Consistencia" title="Escala Consistencia" />
						</p>
						<p class="textos">En este ejemplo, el candidato/a ha hecho un esfuerzo significativo por lograr una coherencia o congruencia alta en las respuestas a las preguntas de este Cuestionario. Habrá que analizar si lo ha logrado y especialmente en que escalas. Habrá algunas escalas en las que no haya obtenido puntos altos o extremos, y serán escalas a explorar en la entrevista.</p>
		            </div>

				';

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

//---------------------------------

$sHtml.='
			<div class="pagina">'. $sHtmlCab;
// PÁGINA PERFIL PERSONALIDAD LABORAL
$iPGlobal = 0;
$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">PERFIL DEL CUESTIONARIO DE PERSONALIDADAD LABORAL</h2>
        			<table id="caja_tit_gris" border="0">
		        		<tr>
		        			<td class="blancob" style="text-align: left;height: 20px;padding-left:4px;">PUNTUACIONES BAJAS (1,2,3)</td>
							<td class="blancob" align="center">
								<table border="0" width="100%" style="width:100%;border-collapse: separate;border: 1px solid #c0c0c0;">
									<tr>
										<td class="blancob" style="padding-left:7px;">1</td>
										<td class="blancob" style="padding-left:7px;">2</td>
										<td class="blancob" style="padding-left:7px;">3</td>
										<td class="blancob" style="padding-left:7px;">4</td>
										<td class="blancob" style="padding-left:7px;">5</td>
										<td class="blancob" style="padding-left:7px;">6</td>
										<td class="blancob" style="padding-left:7px;">7</td>
										<td class="blancob" style="padding-left:7px;">8</td>
										<td class="blancob" style="text-align: right;">9</td>
										<td class="blancob" style="text-align: right;padding-right:5px">10</td>
									</tr>
								</table>
							</td>
							<td class="blancob "style="text-align: right;">PUNTUACIONES ALTAS (8,9,10)</td>
		        		</tr>
		        		<tr>
		        			<td colspan="3" class="caja_tit" style="text-align: center;">DINAMISMO Y ENERGÍA</td>
		        		</tr>';
						$iPBaremada = $aPuntuaciones["51-1"];	//OCUPACIÓN Y ACTIVIDAD
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("51", false) . "," . $conn->qstr("DINAMISMO Y ENERGÍA", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("OCUPACIÓN Y ACTIVIDAD", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Fatigable. Su nivel de esfuerzo y energía en el día a día es bajo. Le cuesta tolerar una carga y ritmo de trabajo elevado.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">OCUPACIÓN Y ACTIVIDAD.<br />Infatigable. Invierte un alto nivel de esfuerzo en el día a día. Tolera una carga de trabajo alta y lo afronta con mucha energía. </td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["51-2"];	//RESOLUCION
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("51", false) . "," . $conn->qstr("DINAMISMO Y ENERGÍA", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("RESOLUCION", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Poco operativo/a. Le cuesta ofrecer soluciones ágiles a los problemas u objeciones. No ofrece alternativas variadas.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">RESOLUCIÓN.<br />Operativo/a. Capacidad para ofrecer soluciones rápidas y eficaces a los problemas y objeciones. Ofrece alternativas variadas.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["51-3"];	//ORIENTACIÓN AL LOGRO
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("51", false) . "," . $conn->qstr("DINAMISMO Y ENERGÍA", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("ORIENTACIÓN AL LOGRO", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Conformista. Su interés no se centra en mejorar como profesional. Sin motivación especial por marcarse metas y esforzarse en superarlas.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ORIENTACIÓN AL LOGRO.<br />Autoexigente. Preocupación e interés por aprender y mejorar como profesional. se marca metas elevadas y se esfuerza por superarlas.</td>
		        		</tr>
		        		<tr>
		        			<td colspan="3" class="caja_tit" style="text-align: center;">EMOCIONES</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["52-1"];	//TENSIÓN INTERNA
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("52", false) . "," . $conn->qstr("EMOCIONES", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("TENSIÓN INTERNA", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Afronta los problemas con tranquilidad. El exceso de relajación puede derivar en pasividad o falta de implicación activa.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">TENSIÓN INTERNA.<br />Su energía interna le mantiene alerta y le moviliza para la acción. Sin embargo, las presiones y tensiones constantes pueden llegar a bloquearles.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["52-2"];	//SERENIDAD Y AUTODOMINIO
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("52", false) . "," . $conn->qstr("EMOCIONES", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("SERENIDAD Y AUTODOMINIO", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Le cuesta mantener la calma y dominarse ante situaciones de estrés o de presión. Los contratiempos le generan ansiedad o nerviosismo.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">SERENIDAD Y AUTODOMINIO.<br />Logra controlar la presión, se mantiene tranquilo/a y con calma en situaciones de estrés o ante contratiempos.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["52-3"];	//CONTROL EXTERNO
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("52", false) . "," . $conn->qstr("EMOCIONES", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("CONTROL EXTERNO", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Expresa abiertamente sus emociones y sentimientos. Le cuesta ocultar su estado de ánimo ante los demás.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">CONTROL EXTERNO.<br />Controla sus emociones y sentimientos evitando que los demás perciban su estado de ánimo bien sea de entusiasmo o de malestar.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["52-4"];	//PENSAMIENTO POSITIVO
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("52", false) . "," . $conn->qstr("EMOCIONES", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("PENSAMIENTO POSITIVO", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Ante las dificultades y problemas se muestra poco optimista. Le cuesta ver la parte positiva de las situaciones.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">PENSAMIENTO POSITIVO.<br />Afronta las dificultades y problemas como retos. Transmite energía positiva al encarar las limitaciones. Espera que las cosas vayan bien.</td>
		        		</tr>
		        		<tr>
		        			<td colspan="3" class="caja_tit" style="text-align: center;">RELACIÓN CON LOS DEMÁS</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["53-1"];	//HABILIDAD SOCIAL
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("53", false) . "," . $conn->qstr("RELACIÓN CON LOS DEMÁS", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("HABILIDAD SOCIAL", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Se siente incómodo/a o poco seguro/a cuando se relaciona con gente desconocida. No se siente hábil o diplomático/a en situaciones sociales o conflictivas.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">HABILIDAD SOCIAL.<br />Se muestra seguro/a y cómodo/a cuando se relaciona con gente desconocida. Maneja las situaciones sociales con diplomacia. </td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["53-2"];	//EMPATÍA
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("53", false) . "," . $conn->qstr("RELACIÓN CON LOS DEMÁS", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("EMPATÍA", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">No invierte tiempo en escuchar a los demás ni muestra un especial interés por ayudarles ni comprender sus problemas y necesidades.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">EMPATÍA.<br />Se esfuerza por comprender las preocupaciones y necesidades de los demás, les escucha y se implica activamente en ayudarles en sus problemas.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["53-3"];	//FLEXIBILIDAD RELACIONAL
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("53", false) . "," . $conn->qstr("RELACIÓN CON LOS DEMÁS", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("FLEXIBILIDAD RELACIONAL", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Le cuesta integrarse y adaptarse a diferentes equipos o situaciones novedosas. Mantiene su punto de vista u opinión y le cuesta ceder ante otros enfoques.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">FLEXIBILIDAD RELACIONAL.<br />Se adapta con facilidad a diferentes personas y situaciones. Acepta otros puntos de vista u opiniones.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["53-4"];	//COMUNICACIÓN PERSUASIVA
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("53", false) . "," . $conn->qstr("RELACIÓN CON LOS DEMÁS", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("COMUNICACIÓN PERSUASIVA", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">No se muestra interesado/a en convencer a los demás de su postura y lograr que cambien su punto de vista. Poco claro/a o convincente.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">COMUNICACIÓN PERSUASIVA.<br />Se expresa con claridad y convicción. Demuestra interés por convencer a los demás de su postura y hacer que cambien su punto de vista.</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["53-5"];	//COOPERACIÓN
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("53", false) . "," . $conn->qstr("RELACIÓN CON LOS DEMÁS", false) . "," . $conn->qstr("5", false) . "," . $conn->qstr("COOPERACIÓN", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">No busca activamente actividades que impliquen colaborar y trabajar en equipo.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">COOPERACIÓN.<br />Disposición para ayudar y trabajar con los demás en las tareas y proyectos comunes. Actitud positiva hacia la colaboración y el trabajo en equipo.</td>
		        		</tr>
		        		<tr>
		        			<td colspan="3" class="caja_tit" style="text-align: center;">ESTILO DE TRABAJO</td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["59-3"];	//ORGANIZACIÓN
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("59", false) . "," . $conn->qstr("ESTILO DE TRABAJO", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("ORGANIZACIÓN", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">No sigue un método de trabajo específico para cumplir los plazos establecidos. Poco metódico/a o sistemático/a.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ORGANIZACIÓN.<br />Estructura las tareas diarias en función de su importancia y urgencia. Trabaja de forma metódica y sistemática para cumplir los plazos establecidos. </td>
		        		</tr>
						';
						$iPBaremada = $aPuntuaciones["59-4"];	//PERSEVERANCIA
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("59", false) . "," . $conn->qstr("ESTILO DE TRABAJO", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("PERSEVERANCIA", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_Texto_L">Le cuesta mantener la constancia en la finalización de las tareas y consecución de los objetivos, sobre todo cuando afronta dificultades y obstáculos.</td>
							<td class="pl_img" align="center">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">PERSEVERANCIA.<br />Constante en la realización de su trabajo. Se esfuerza por finalizar sus tareas incluso ante obstáculos y dificultades.</td>
		        		</tr>
		        		<tr>
		        			<td class="pl_img" align="center">&nbsp;</td>
							<td class="pl_img" align="center"></td>
							<td class="pl_img" align="center">&nbsp;</td>
		        		</tr>
						';
				    $consistencia = baremo_C(number_format(sqrt($iPGlobal/14)*100 ,0));

				    $sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
				    $sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("CONSISTENCIA", false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("CONSISTENCIA", false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr($consistencia, false) . ",now());\n";
				    $aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
		        		<tr>
		        			<td class="pl_consistencia_L">CONSISTENCIA BAJA.<br />Poca congruencia en las respuestas, azar, baja motivación, indefinición.</td>
							<td class="pl_img" align="center" style="border-style: solid;border-width: 2px 1px 2px 1px;">' . '<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/cpc' . $consistencia . '.jpg" title="' . $consistencia . '" />' . '</td>
							<td class="pl_consistencia_R">CONSISTENCIA ALTA.<br />Excesiva congruencia en las respuestas, posible rigidez o por orientar su perfil.</td>
		        		</tr>
		        	</table>
				';

$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

//2-------------------------------------
$sHtml.='
			<div class="pagina">'. $sHtmlCab;
// INFORME DE COMPETENCIAS
$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
        			<table id="caja_tit" style="height: 50px !important;" border="0">
		        		<tr>
		        			<td class="blancob" style="text-align: center;">Asesores de Servicio Postventa</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">Este informe competencial se obtiene como resultado de aplicación informatizada del Cuestionario de Personalidad Laboral para Asesores de Servicio Postventa. En él se indican de manera resumida, las principales Áreas de Fortaleza, Áreas de Potencial Desarrollo y Áreas que sería recomendable desarrollar para puestos de Asesores de Servicio Postventa en la firma Mercedes-Benz.</p>
						<p class="textos">Para la realización de este informe, han sido consideradas las competencias esenciales para ocupar puestos de Asesor en la Firma. Para la determinación de las Áreas de Fortaleza y Áreas de Desarrollo, se han tenido en cuenta las definiciones y los principales indicadores que constituyen el perfil ideal de un Asesor de Mercedes-Benz.</p>
						<p class="textos">Este Cuestionario describe preferencias y actitudes en relación a catorce escalas directas y una escala de control (consistencia), en relación a aspectos correspondientes a las funciones de Asesores de Servicio Postventa. No es un test, sino un Cuestionario donde se indican las preferencias de la persona, así como su estilo personal y profesional. El Cuestionario genera un perfil de personalidad haciendo comparaciones con colectivos de profesionales de Postventa.</p>
						<p class="textos">Este Cuestionario no es infalible, y, como todo Cuestionario de personalidad laboral, su validez depende de la honestidad y sinceridad con la que haya sido respondido por los/las candidatos/as.</p>
						<p class="textos">Este informe se genera de una manera electrónica, por lo que siempre recomendamos que se complemente con información relativa a otras técnicas como la entrevista personal.</p>
						<p class="textos">El informe, también refleja la manera en la que la persona ha descrito su estilo de trabajo con respecto a 6 competencias esenciales. Nos facilita información correspondiente a las principales fortalezas, áreas de desarrollo y potencial a desarrollar.</p>
						<p class="textos">No se puede considerar esta información como definitiva, sino que constituye un documento orientativo para la toma de decisiones en selección, y como documento de análisis para el desarrollo de los planes de formación y/o desarrollo futuros en la organización.</p>
						<p class="textos">Psicólogos Empresariales no se hacen responsables de posibles modificaciones o cambios en el informe escrito o contenidos que hayan sido generados de manera automática en el original.</p>
		            </div>
				';

$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';
//3-------------------------------------
$sHtml.='
			<div class="pagina">'. $sHtmlCab;
// INFORME DE COMPETENCIAS
$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
        		    <div class="caja" style="margin-top:20px !important;">
	        			<table border="0" style="margin-left: 117px;">
			        		<tr>
			        			<td rowspan="8" class="blancob" style="text-align: center;background-color: #a85400; width: 184px;">COMPETENCIAS<br />IDEALES</td>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td width="200px">Orientación al Cliente</td>
	        				</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Especialización <font color="orange"><sup>*</sup></font></td>
			        		</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Trabajo en Equipo</td>
			        		</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Tolerancia al Estrés</td>
			        		</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Organización</td>
			        		</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Flexibilidad</td>
			        		</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Comunicación <sup>*</sup></td>
			        		</tr>
			        		<tr>
								<td style="text-align: center;width: 50px;"><img src="'.constant("DIR_WS_GESTOR").'graf/seas/flechita.jpg" title="Flecha" /></td>
								<td >Orientación Comercial</td>
			        		</tr>
			        	</table>
		            </div>
        			<table id="caja_tit" border="0">
		        		<tr>
		        			<td class="blancob" style="text-align: left;">Competencias: Interpretación de indicadores y símbolos</td>
		        		</tr>
		        	</table>
        		    <div class="caja">
            			<p class="textos">Los siguientes símbolos e indicadores de color, son los que aparecen en el informe para la identificación rápida del nivel en el que se ha descrito el candidato/a en las diferentes competencias:</p>
		            </div>
        		    <div class="caja">
            			<p class="textos" style="font-size: 12px;color:#FF8000;font-weight: bold;">Indicadores y símbolos</p>
		            </div>
					<div class="cajaTextos">
						<div class="cajaTextos">
							<p class="indicadores"><span class="verde">&nbsp;</span><span class="verde">&nbsp;</span><span class="verde">&nbsp;</span> <span class="txt">= Área de clara Fortaleza</span></p>
						</div>
						<div class="cajaTextos">
							<p class="indicadores"><span class="verde">&nbsp;</span><span class="verde">&nbsp;</span> <span class="txt">= Área de Fortaleza</span></p>
						</div>
						<div class="cajaTextos">
							<p class="indicadores"><span class="amarillo">&nbsp;</span><span class="txt">= Nivel Adecuado para el puesto</span></p>
						</div>
						<div class="cajaTextos">
							<p class="indicadores"><span class="rojo">&nbsp;</span><span class="rojo">&nbsp;</span> <span class="txt">= Área de Desarrollo</span></p>
						</div>
						<div class="cajaTextos">
							<p class="indicadores"><span class="rojo">&nbsp;</span><span class="rojo">&nbsp;</span> <span class="rojo">&nbsp;</span> <span class="txt">= Área con clara necesidad de Desarrollo</span></p>
						</div>
					</div>
					<table width="97%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="5%" align="center" >
								<font style="font-size: 12px;font-weight: bold;"><sup>*</sup></font>
    						<td>
    						<td width="95%" style="font-size: 12px;text-align: justify;">Esta competencia debe ser validada de manera presencial. Ya que a través de un cuestionario de Personalidad no se recogen aspectos esenciales (comunicación no verbal, tono, seguridad, etc.) para determinar las habilidades de comunicación de una persona.<td>
    					</tr>
						<tr>
							<td width="5%" align="center" >
								<font style="font-size: 12px;font-weight: bold;"><font color="orange"><sup>*</sup></font></font>
    						<td>
    						<td width="95%" style="font-size: 12px;text-align: justify;">Esta competencia ha sido evaluada a través del Cuestionario de Conocimientos para Asesores de Postventa de la marca. Por tanto, su interpretación ha de remitirse al número de respuestas válidas obtenidas por la persona. El resto de competencias se evalúan a través del Cuestionario de Personalidad Laboral.<td>
    					</tr>

    				</table>
				';

$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

//4-------------------------------------
$sHtml.='
			<div class="pagina">'. $sHtmlCab;
// INFORME DE COMPETENCIAS
$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-1"];	// Orientación al Cliente
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("Orientación al Cliente", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td class="cajaColor dato-informe" >Orientación al Cliente</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Escucha atentamente al cliente, mostrando interés por satisfacer sus necesidades e intereses.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</span></td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Se compromete en solucionar sus problemas ofreciéndole soluciones personalizadas y un servicio ágil y eficiente.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra honradez e integridad con sus clientes como perseverancia en satisfacer sus demandas.</td>
		        			</tr>
						</table>
					</div>';

//----------
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $iPEspecializacion;	// Especialización
$iPuntacion = TransformarEspecializacion($cCandidato->getEspecialidadMB(), $iPEspecializacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("0", false) . "," . $conn->qstr("Especialización *", false) . "," . $conn->qstr("0", false) . "," . $conn->qstr("Especialización", false) . "," . $conn->qstr(textoEspecializacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Especialización</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoEspecializacion($iPuntacion)  . '</p>
								' . bolosEspecializacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Hace esfuerzos extras para satisfacer las necesidades de sus clientes.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Escucha atentamente al cliente, detectando sus necesidades e intereses.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Se adapta a todo tipo de interlocutores, estableciendo relaciones duraderas.</td>
		        			</tr>
						</table>
					</div>';

//----------
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-2"];	// Trabajo en Equipo
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("Trabajo en Equipo", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Trabajo en Equipo</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Colabora y trabaja adecuadamente con los demás en la consecución de objetivos comunes.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Comparte la información disponible y ayuda a sus compañeros voluntariamente a resolver sus problemas.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Favorece el desarrollo de las relaciones y el espíritu de equipo.</td>
		        			</tr>
						</table>
					</div>';
$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
				<hr>
    		';

//---------
$sHtml.='
			<div class="pagina">'. $sHtmlCab;
// INFORME DE COMPETENCIAS
$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
		';
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-3"];	// Tolerancia al Estrés
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("Tolerancia al Estrés", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Tolerancia al Estrés</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Mantiene la calma, es objetivo y autocontrolado bajo presión.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Mantiene un rendimiento constante en situaciones difíciles.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Aceptan las críticas y las situaciones conflictivas con actitud positiva.</td>
		        			</tr>
						</table>
					</div>';
//-----------
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-4"];	// Organización
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("Organización", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Organización</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Muestra capacidad para estructurar el trabajo a realizar y las tareas a desempeñar, tanto el propio como el del equipo.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Tiene en cuenta los recursos disponibles y las limitaciones existentes.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Organiza su tiempo eficazmente, siguiendo una sistemática y dominando los procesos.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Permanece atento a los plazos y a los imprevistos.</td>
		        			</tr>

						</table>
					</div>';
//--------
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-5"];	// Flexibilidad
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("5", false) . "," . $conn->qstr("Flexibilidad", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Flexibilidad</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Adaptable y abierto a nuevas ideas, procedimientos y métodos.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra capacidad de adaptación y reacción frente a los imprevistos.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Es capaz de simultanear diferentes trabajos, funciones y problemas sin restarle eficacia ni constancia.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Permanece atento a los plazos y a los imprevistos.</td>
		        			</tr>

						</table>
					</div>';
//----------------
$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

//--------------------
//---------
$sHtml.='
			<div class="pagina">'. $sHtmlCab;
// INFORME DE COMPETENCIAS
$sHtml.='
				<div class="desarrollo">
		        	<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
		';
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-6"];	// Comunicación
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("6", false) . "," . $conn->qstr("Comunicación", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Comunicación</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Se expresa de forma clara, fluida y precisa adaptando su argot a su interlocutor evitando el uso de tecnicismos.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Transmite seguridad en su discurso, consiguiendo influir sobre las opiniones de los demás y logrando acuerdos en sus propuestas.</td>
		        			</tr>
						</table>
					</div>';
//-----------
$sHtml.='
            		<div class="cajaTextos-resultados">
		';
$iPuntacion = $aPuntuacionesCompetencias["1-7"];	// Orientación Comercial
$iPuntacion = TransformarCompetencia($iPuntacion);

$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SEAS", false) . "," . $conn->qstr("7", false) . "," . $conn->qstr("Orientación Comercial", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
$aSQLPuntuacionesC[] = $sSQLExport;

$sHtml.='
						<table width="100%" border="0">
		        			<tr>
								<td width="350" class="cajaColor dato-informe" >Orientación Comercial</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
		        			</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
    				<div class="cajaTextos-resultados">
						<table width="100%" border="0">
		        			<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Se orienta a resultados de venta y actúa en consecuencia.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Encuentra soluciones en base a rentabilidad y beneficios.</td>
		        			</tr>
		        			<tr>
								<td colspan="3">&nbsp;</td>
		        			</tr>
		        			<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Maneja con facilidad las situaciones de negociación y venta.</td>
		        			</tr>
						</table>
					</div>';
//-----------

//--------------------

$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
    		';

		return $sHtml;
	}

//---------------------------***
	/*
	 * Imforme Academia de formación
	 */
	function generarAcademia($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $sEsp;
		global $cCandidato;
		global $comboESPECIALIDADESMB;
		global $cUtilidades;

		$iCCT= "84";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iCCT);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptCCT84 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iCCT, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo'], $cCandidato->getEspecialidadMB());
		//		echo "<br />generar Academia";
		//		echo "<br />[iPDirecta, iPercentil, IR, IP, POR, iItemsPrueba]";
		//		echo "<br />CUESTIONARIO DE CONOCIMIENTOS TÉCNICOS - CCT84:: ";
		//		print_r($aAptCCT84);

		$iWhidth = 1;
		$iPDirecta= $aAptCCT84[0];
		$iPEspecializacion= $iPDirecta;
		$iPDirectaX= $iPDirecta*10;
		$iAutoevaluacion = $cCandidato->getNivelConocimientoMB();
		$iAutoevaluacionX = $iAutoevaluacion*10;
		$iPercentil= 0;


		//------------------------------ INFORME PARA LA ACADEMIA ---------------
		$sHtml='';
		//---------
		$sHtml.='
			<div class="pagina">';
		// INFORME ACADEMIA
		$rsEspecialidades = $comboESPECIALIDADESMB->getDatos();
		$rsEspecialidades->Move(0);

		$sHtml.='
				<div class="desarrollo">
					<table width="100%" >
						<tr>
							<td width="100%" align="center">
								<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/logoMB.jpg" title="logo MB"/>
							</td>
						</tr>
					</table>
		        	<h2 class="subtituloFormacion">SOLICITUD DE DESARROLLO DE PLAN PERSONALIZADO DE FORMACION</h2>
					<table width="100%" style="font-size: 14px;" >
						<tr>
							<td colspan="2" class="negrob">DATOS PERSONALES NUEVA INCORPORACION</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
						<tr>
							<td class="negro" width="200">NOMBRE Y APELLIDOS:</td>
							<td>&nbsp;' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">D5 ó EMEA:</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">EDAD:</td>
							<td>&nbsp;' . $cUtilidades->calculaedad($cCandidato->getFechaNacimiento()) . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">E-MAIL:</td>
							<td>&nbsp;' . $cCandidato->getMail() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">CONCESIÓN / TALLER AUTORIZADO:</td>
							<td>&nbsp;' . $cCandidato->getConcesionMB() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">BASE:</td>
							<td>&nbsp;' . $cCandidato->getBaseMB() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>

					<table width="100%" style="font-size: 14px;">
						<tr><td colspan="2" class="negrob">SECTOR</td></tr>
						<tr><td colspan="2" >&nbsp;</td></tr>';

		while(!$rsEspecialidades->EOF){
			$_sSeleccionadoEsp = "";
			if ($sEsp == $rsEspecialidades->fields['Descripcion']){
				$_sSeleccionadoEsp = '<img width="20" src="' . constant("DIR_WS_GESTOR") . 'graf/ok.png" title="ok"/>';
			}
			$sHtml.='
								<tr>
									<td width="42%">' . $rsEspecialidades->fields['Descripcion'] . '</td>
									<td >' . $_sSeleccionadoEsp . '</td>
								</tr>
								<tr><td colspan="2" >&nbsp;</td></tr>
								';
			$rsEspecialidades->MoveNext();
		}
		$sHtml.='
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">Fecha de finalización:</td>
							<td>&nbsp;' . (new DateTime($cCandidato->getFechaFinalizado()))->format('d/m/Y') . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>

					<table width="100%" style="font-size: 14px;">
						<tr><td colspan="2" class="negrob">VALORACIÓN DE COMPETENCIAS</td></tr>
					</table>

					<div class="caja" style="margin-top:5px !important;">
	        			<table border="0" style="margin-left: 16px;">
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Orientación al Cliente</td>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-1"];	// Orientación al Cliente
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
	        				</tr>
					';
		$iPuntacion = $iPEspecializacion;	// Especialización
		$iPuntacion = TransformarEspecializacion($cCandidato->getEspecialidadMB(), $iPEspecializacion);
		$sHtml.='
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Especialización</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-2"];	// Trabajo en Equipo
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='

			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Trabajo en Equipo</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-3"];	// Tolerancia al Estrés
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Tolerancia al Estrés</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-4"];	// Organización
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Organización</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-5"];	// Flexibilidad
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Flexibilidad</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-6"];	// Comunicación
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Comunicación</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-7"];	// Orientación Comercial
		$iPuntacion = TransformarCompetencia($iPuntacion);
		$sHtml.='
			        		<tr>
								<td style="white-space: nowrap;padding-right: 10px;">Orientación Comercial</td>
								<td class="caja_desc">' . $iPuntacion . ' - ' . textoDefinicion($iPuntacion) . '</td>
			        		</tr>
			        	</table>
		            </div>
					<p style="font-size: 14px;">Por favor, le rogamos envíe este informe a la siguiente dirección de correo electrónico para que se proceda a la inscripción de la persona en la formación correspondiente a su perfil:</p>
					<p style="font-size: 14px;">&nbsp;</p>
					<p style="font-size: 14px;font-weight: bold;">academiadeformacion@daimler.com</p>
					<table width="100%" >
						<tr>
							<td class="margenTD">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/logoAcademia.jpg" title="logo Academia"/>
							</td>
						</tr>
					</table>
		';

		//--------------------

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
    		';
/*
		//************************************
		//---------
		$sHtml.='
			<div class="pagina">';
		// INFORME ACADEMIA pág2

		$sHtml.='
				<div class="desarrollo">
					<table width="100%" >
						<tr>
							<td width="100%" align="center">
								<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/logoMB.jpg" title="logo MB"/>
							</td>
						</tr>
					</table>
					<table width="100%" >
						<tr>
							<td class="negro"><strong>Observaciones:</strong> en este espacio podrá realizar las observaciones que considere con respecto a los cursos recomendados.</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px !important;">
	        			<table height="135" width="96%" border="0" style="margin-left: 16px;">
			        		<tr>
								<td>&nbsp;</td>
							</tr>
			        	</table>
		            </div>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>Por favor, una vez haya completado este formulario con sus observaciones, le rogamos lo envíe a la siguiente dirección de correo electrónico para que se proceda a la inscripción de la persona en los cursos recomendados:</p>
					<p>&nbsp;</p>
					<p style="font-weight: bold;">academiadeformacion@daimler.com</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>Muchas gracias.</p>
					<table width="100%" >
						<tr>
							<td class="margenTD2">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<img src="' . constant("DIR_WS_GESTOR") . 'graf/seas/logoAcademia.jpg" title="logo Academia"/>
							</td>
						</tr>
					</table>
		';

		//--------------------

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';
*/
		return $sHtml;
	}

	function calalculaAPTITUDES($_idEmpresa, $_idProceso, $_idPrueba, $_codIdiomaIso2Prueba, $_idCandidato, $_idBaremo, $_especialidadMB="")
	{
		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");

		//[$iPDirecta, $iPercentil, $IR, $IP, $POR, $iItemsPrueba]
		$aAptitudes =array();
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

		if (!empty($_especialidadMB)){
			$cIt->setTipoItem($_especialidadMB);
		}
		$sqlItemsPrueba= $cItemsDB->readLista($cIt);
		$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
		$iItemsPrueba = $listaItemsPrueba->recordCount();
		//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
		$iPDirecta = 0;
		$iPercentil = 0;
		if($listaRespItems->recordCount()>0)
		{
			while(!$listaRespItems->EOF){

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
				if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
					//echo $listaRespItems->fields['idItem'] . " - bien <br />";
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
			$ipMin=0;
			$ipMax=0;
			// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que
			// corresponde con la puntuación directa obtenida.
			if($listaBaremosResultados->recordCount()>0){
				while(!$listaBaremosResultados->EOF){

					$ipMin = $listaBaremosResultados->fields['puntMin'];
					$ipMax = $listaBaremosResultados->fields['puntMax'];
					if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
						$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
					}
					$listaBaremosResultados->MoveNext();
				}
			}

//			echo "pDirecta: " . $iPDirecta . "<br />";
//			echo "pPercentil: " . $iPercentil . "<br />";
//--
			$sUltimoItemRespondido = 0;
			if ($listaRespItems->recordCount() > 0){
				$sUltimoItemRespondido = $listaRespItems->MoveLast();
				$sUltimoItemRespondido = $listaRespItems->fields['idItem'];
			}

	//		$IR = number_format($listaRespItems->recordCount() / $listaItemsPrueba->recordCount(),2);
			//IR= Último ítem respondido por el candidato/Nº total de ítems de la prueba.
			if ($listaItemsPrueba->recordCount() > 0){
				$IR = number_format($sUltimoItemRespondido / $listaItemsPrueba->recordCount(),2);
			}
			$sIR = str_replace("." , "," , $IR);
	//		$IP = number_format($iPDirecta/$listaRespItems->recordCount() ,2);
			//IP= Aciertos/Último ítem respondido por el candidato
			if ($sUltimoItemRespondido > 0){
				$IP = number_format($iPDirecta / $sUltimoItemRespondido ,2);
			}
			$sIP = str_replace("." , "," , $IP);
			$POR = number_format($IR*$IP ,2);
			$sPOR = str_replace("." , "," , number_format($POR ,2));

		}
		$aAptitudes[] =$iPDirecta;
		$aAptitudes[] =$iPercentil;
		$aAptitudes[] =$IR;
		$aAptitudes[] =$IP;
		$aAptitudes[] =$POR;
		$aAptitudes[] =$iItemsPrueba;


		return $aAptitudes;

	}

	//Transforma la puntuacion obtenida en la prueba de conocimientos
	//a un rango de 1-6, dependiendo de los aciertos
	function TransformarEspecializacion($especialidad, $aciertoscono)
	{
		$especializacionfinal=0;

		// 1 	Turismos -> 20  items
		// 35 	Furgonetas -> 9 items
		// 36 	Camiones -> 14 items
		if (($especialidad == "1"))
		{

			switch ($aciertoscono)
		    {
		        //area con clara necesidad de desarrollo 7	6
		        case ($aciertoscono >=0) && ($aciertoscono <=  6):
		        	$especializacionfinal = 1;
		        	break;
		        //area con clara necesidad de desarrollo 5	3
		        case ($aciertoscono >=7) && ($aciertoscono <=  9):
		        	$especializacionfinal = 2;
		        	break;
		        //area de desarrollo 5	3
		        case ($aciertoscono >=10) && ($aciertoscono <=  12):
		        	$especializacionfinal = 3;
		        	break;
		        //nivel adecuado para el puesto 5	3
		        case ($aciertoscono >=13) && ($aciertoscono <=  15):
		        	$especializacionfinal = 4;
		        	break;
		        //area de fortaleza 5	3
		        case ($aciertoscono >=16) && ($aciertoscono <=  18):
		        	$especializacionfinal = 5;
		        	break;
		        //area de clara fortaleza 3	2
		        case ($aciertoscono >=19) && ($aciertoscono <=  20):
		        	$especializacionfinal = 6;
		        	break;

		    }
		}else{
			if (($especialidad == "36"))
			{

				switch ($aciertoscono)
				{

					//area con clara necesidad de desarrollo 	3
					case ($aciertoscono >=0) && ($aciertoscono <=  3):
						$especializacionfinal = 1;
						break;
					//area con clara necesidad de desarrollo 	2
					case ($aciertoscono >=4) && ($aciertoscono <=  5):
						$especializacionfinal = 2;
						break;
					//area de desarrollo 	2
					case ($aciertoscono >=6) && ($aciertoscono <=  7):
						$especializacionfinal = 3;
						break;
					//nivel adecuado para el puesto 	2
					case ($aciertoscono >=8) && ($aciertoscono <=  9):
						$especializacionfinal = 4;
						break;
					//area de fortaleza 	2
					case ($aciertoscono >=10) && ($aciertoscono <=  11):
						$especializacionfinal = 5;
						break;
					//area de clara fortaleza 	3
					case ($aciertoscono >=12) && ($aciertoscono <=  14):
						$especializacionfinal = 6;
						break;
				}
			}else{
			    if ($especialidad == "35")
			    {
			        switch ($aciertoscono)
			        {

					//area con clara necesidad de desarrollo 	3
					case ($aciertoscono >=0) && ($aciertoscono <=  3):
						$especializacionfinal = 1;
						break;
					//area con clara necesidad de desarrollo 	1
					case ($aciertoscono >=4) && ($aciertoscono <=  4):
						$especializacionfinal = 2;
						break;
					//area de desarrollo 	1
					case ($aciertoscono >=5) && ($aciertoscono <=  5):
						$especializacionfinal = 3;
						break;
					//nivel adecuado para el puesto 	1
					case ($aciertoscono >=6) && ($aciertoscono <=  6):
						$especializacionfinal = 4;
						break;
					//area de fortaleza 	1
					case ($aciertoscono >=7) && ($aciertoscono <=  7):
						$especializacionfinal = 5;
						break;
					//area de clara fortaleza 	2
					case ($aciertoscono >=8) && ($aciertoscono <=  9):
						$especializacionfinal = 6;
						break;
			        }

			    }
			}
		}

	//devuelve la puntuacion transformada
	return $especializacionfinal;

	}

	//Transforma la puntuacion baremada de la competencia en escala de 1-6
	function TransformarCompetencia($iPuntBaremada)
	{
		$iPuntuacion=0;


			switch ($iPuntBaremada)
			{
				case ($iPuntBaremada >=1) && ($iPuntBaremada <=  2):
					$iPuntuacion = 1;
					break;
				case ($iPuntBaremada >=3) && ($iPuntBaremada <=  3):
					$iPuntuacion = 2;
					break;
				case ($iPuntBaremada >=4) && ($iPuntBaremada <=  5):
					$iPuntuacion = 3;
					break;
				case ($iPuntBaremada >=6) && ($iPuntBaremada <=  6):
					$iPuntuacion = 4;
					break;
				case ($iPuntBaremada >=7) && ($iPuntBaremada <=  8):
					$iPuntuacion = 5;
					break;
				case ($iPuntBaremada >=9) && ($iPuntBaremada <=  10):
					$iPuntuacion = 6;
					break;

			}

		//devuelve la puntuacion transformada
		return $iPuntuacion;

	}

function getPortada()
{
	global $comboESPECIALIDADESMB;
	global $cCandidato;
	global $conn;

	//PORTADA
	$sHtml= '
			<div class="pagina portada">
		    	<img src="'.constant("DIR_WS_GESTOR").'graf/seas/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"></h1>';
	if($_POST['fIdTipoInforme']!=11){
		//$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_infome"><p></p></div>';
	}else{
		//$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_infome_narrativo"><p></p></div>';
	}

	$sEsp = $comboESPECIALIDADESMB->getDescripcionCombo($cCandidato->getEspecialidadMB());
	$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>Concesión / Taller Autorizado:</strong> ' . $cCandidato->getConcesionMB() . ' ' . '</p>
					<p class="textos"><strong>Sector:</strong> ' . $sEsp . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<!--<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>-->
			</div>
			<!--FIN DIV PAGINA-->
			';
	//		$sHtml.=	constant("_NEWPAGE");
	//FIN PORTADA
	return $sHtml;
}
function getContraPortada()
{
		$sHtml= '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
			</div>
			<!--FIN DIV PAGINA-->
		';
		return $sHtml;

}

/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
