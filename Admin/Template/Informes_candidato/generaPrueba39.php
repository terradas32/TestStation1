<?php
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");

		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

//*****************************************************************************************
		// CÁLCULOS GLOBALES PARA ESCALAS PRISMA PRUEBA 24,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		//Para el calculo de consistencia, segun ELENA, se cogen las escalas de la prueba original
		//Prisma ID 24
		$_pruebaPRISMA = 24;

		$cItems_inversosDB = new Items_inversosDB($conn);
		$cItems_inversos = new Items_inversos();

		$cItems_inversos->setIdPrueba($_pruebaPRISMA);
		$cItems_inversos->setIdPruebaHast($_pruebaPRISMA);
		$sqlInversos = $cItems_inversosDB->readLista($cItems_inversos);
//		echo "<br />Inversos::" . $sqlInversos;
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

		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_pruebaPRISMA);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//		echo "<br />sqlEscalas_items::" . $sqlEscalas_items . "";
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
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
		$aPuntuacionesPRISMA = array();
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
				        $cEscalas_items->setIdPrueba($_pruebaPRISMA);
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
				        $cBaremos_resultado->setIdPrueba($_pruebaPRISMA);
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
//				       	echo "<br />---------->" . $listaBloques->fields['nombre'] .  " - " . $listaEscalas->fields['nombre'] . " [" . $sPosi . "][PD::" . $iPd . "] - [PB::" . $iPBaremada . "]";

				       	$aPuntuacionesPRISMA[$sPosi] =  $iPBaremada;

				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
	// FIN CALCULOS GLOBALES ESCALAS de prueba PRISMA APLICANDO ESCALA original
//*****************************************************************************************


		$cItems_inversosDB = new Items_inversosDB($conn);
		$cItems_inversos = new Items_inversos();

		$cItems_inversos->setIdPrueba($_POST['fIdPrueba']);
		$cItems_inversos->setIdPruebaHast($_POST['fIdPrueba']);
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
//				       	echo "<br />---------->[" . $sPosi . "][" . $iPBaremada . "]";
//				       	echo "<br />---------->" . $listaBloques->fields['nombre'] .  " - " . $listaEscalas->fields['nombre'] . " [" . $sPosi . "][" . $iPd . "]";

				       	$aPuntuaciones[$sPosi] =  $iPd;


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
			 		while(!$listaCompetencias->EOF){

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
				        if($nCompetencias_items > 0){
				        	$sItemsxCompetencia = "";
				        	while(!$listaCompetencias_items->EOF){
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				        		$cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
								$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
								$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
								$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
								$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);
								$sItemsxCompetencia .= "+<b>". $listaCompetencias_items->fields['idItem'] . "</b>";
								$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
//				        		echo "<br />ITEM:" . $listaCompetencias_items->fields['idItem'] . " - opcion:: " . $cRespuestas_pruebas_items->getIdOpcion() . " DESC:: " . $cRespuestas_pruebas_items->getDescOpcion();

				        		if(array_search($listaCompetencias_items->fields['idItem'], $aInversos) === false){
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
//					       			$iPdCompetencias = $iPdCompetencias + $cRespuestas_pruebas_items->getIdOpcion();
					       		}else{
//					       			echo "<br />INVERSO::" . $listaCompetencias_items->fields['idItem'];
					       			$iPdCompetencias += getInversoPrisma($cRespuestas_pruebas_items->getIdOpcion());
					       		}

								$listaCompetencias_items->MoveNext();
				        	}
				        }
				       	$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];

//				       	echo "<br />" . $listaTipoCompetencia->fields['nombre'] . "-" . $listaCompetencias->fields['nombre'] . " [" . $sPosiCompetencias . "]";
//				       	echo "<br />Items de la competencia:: " . $sItemsxCompetencia;
//				       	echo "<br />PDC:: " . $iPdCompetencias;
//				       	echo "<br />        nº ITEMS C:: " . $nCompetencias_items;
//				       	echo "<br />        P. Máx teórica:: " . ($nCompetencias_items*2);
//				       	echo "<br />        PDC/nº ITEMS C == " . ($iPdCompetencias/$nCompetencias_items);
//				       	echo "<br />        Redondeo (PDC/nº ITEMS C) == " . round((($iPdCompetencias*5)/($nCompetencias_items*2)), 0);

				       	$iPuntuacion = round((($iPdCompetencias*5)/($nCompetencias_items*2)), 0);
				       	$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPuntuacion;
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
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/lechepascual/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/lechepascual/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/lechepascual/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>Corporación Empresarial Pascual</title>
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
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/lechepascual/img/logo-pequenio.jpg" title="logo"/>
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

		//PORTADA
		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/lechepascual/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR").'estilosInformes/lechepascual/img/logo.jpg" /></h1>';
			if($_POST['fIdTipoInforme']!=11){
				$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
			}else{
				$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . $sDescInforme . '</p></div>';
			}
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<h2 id="copy">© ' . mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . ' '.constant("STR_PIE_INFORMES_PASCUAL") . '</h2>
			</div>
			<!--FIN DIV PAGINA-->
      <hr>
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA
		$aSQLPuntuacionesPPL =  array();
		$aSQLPuntuacionesC = array();
		switch ($_POST['fIdTipoInforme'])
		{
			default;	//Informe competencias

				$sHtml.= informeSintesisCompetencias($aPuntuacionesCompetencias, $sHtmlCab, $_POST['fCodIdiomaIso2']);
//				$sHtml.= informeCompetenciasDefiniciones($sHtmlCab, $_POST['fCodIdiomaIso2']);
				$sHtml.= generaNarrativoPascual($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
				$sHtml.= generaNarrativoPascual2($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
//				$sHtml.= generaNarrativoPascual3($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
				$sHtml.= generaNarrativoPascual4($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
//				$sHtml.= generaNarrativoPascual5($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
				$sHtml.= generaNarrativoPascual6($aPuntuaciones, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
				$sWord = generaPascualWORD($cPruebas, $aPuntuacionesCompetencias, $aPuntuaciones, $_POST['fCodIdiomaIso2'], $sDescInforme, $cCandidato);
				break;

		}


		//print_r($aSQLPuntuacionesC);
		$sHtml.= '
			<div class="pagina portada" id="contraportada">
				<div class="caja" style="position: absolute;top: 800px;left:55px;margin-bottom:15px; border: 2px solid #ffffff;">
		        	<h3 class="encabezado" style="font-size: 11px;color:#000000;">ACERCA DE ESTE INFORME:</h3>
		            <p class="textos" style="color:#000000;font-size: 11px;font-style: italic;">Este informe se ha generado utilizando el sistema Experto de Evaluación on Line de Psicólogos Empresariales. Incluye información procedente del Cuestionario de Personalidad Laboral Prisma. El uso de este cuestionario está limitado a aquellos que han recibido la formación para su uso e interpretación.</p>
					<p class="textos" style="color:#000000;font-size: 11px;font-style: italic;">El presente informe se genera a partir de los resultados de un cuestionario completado por los evaluados/as y refleja sustancialmente sus respuestas. A la hora de interpretar estos datos, se debe tener en cuenta la naturaleza de autoinforme de las evaluaciones basadas en un cuestionario.</p>
					<p class="textos" style="color:#000000;font-size: 11px;font-style: italic;">Este informe se ha generado electrónicamente: el usuario de Test Station puede realizar modificaciones y añadidos al texto de este informe.</p>
					<p class="textos" style="color:#000000;font-size: 11px;font-style: italic;">Psicólogos Empresariales y Asociados no pueden garantizar que el contenido de este informe sea el resultado directo del sistema de interpretación. No puede asumir responsabilidades por las consecuencias del uso de este informe ni de su contenido modificado.</p>
					<p class="textos" style="color:#000000;font-size: 11px;font-style: italic;">© Psicólogos Empresariales y Asociados, S.A. Este informe ha sido desarrollado por PEASA en beneficio de su cliente y contiene propiedad intelectual de PEASA. Por lo tanto, PEASA permite a sus clientes reproducir, corregir y almacenar este informe para su uso interno y sin carácter comercial. Cualquier otro será Derecho Reservado.</p>
				</div><!--FIN DIV CAJA-->
				<h2 id="copy">© ' . mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . ' '.constant("STR_PIE_INFORMES_PASCUAL") . '</h2>
			</div>
			<!--FIN DIV PAGINA-->
		';

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
	if (!empty($sWord))
	{
		$replace = array('@', '.');
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

		$_fichero = $spath . $sDirImg . $sNombre . ".rtf";

		if(is_file($_fichero)){
			unlink($_fichero);
		}
		error_log(utf8_decode($sWord), 3, $_fichero);
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
    $footer_html    = "";	// mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . str_repeat(" ", 70) . constant("STR_PIE_INFORMES");
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
	//Funcion que devuelve un texto a la parte del informe de competencias de prisma
	function textoPuntuacion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str="ÁREA CLAVE DE MEJORA";
		}
		if($puntuacion ==3 || $puntuacion==4){
			$str="ÁREA POTENCIAL DESARROLLO";
		}
		if($puntuacion ==5 || $puntuacion==6){
			$str="ÁREA EN DESARROLLO";
		}
		if($puntuacion ==7 || $puntuacion==8){
			$str="ÁREA POTENCIAL FORTALEZA";
		}
		if($puntuacion ==9 || $puntuacion==10){
			$str="ÁREA DE FORTALEZA";
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

	function informeSintesisCompetencias($aPuntuaciones , $sHtmlCab, $idIdioma){

		global $conn;
		global $aPuntuacionesPRISMA;

		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;
		global $aSQLPuntuacionesC;
		$sSQLExport ="";


		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");


		$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);

		$sHtml= '
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA SINTESIS COMPETENCIAS

		$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">' . constant("STR_PRISMA_PERFIL_DE_COMPETENCIAS_PASCUAL") . '</h2>
        			<div class="caja" style="margin-bottom:15px;">
		            	<h3 class="encabezado">' . mb_strtoupper(constant("STR_INTRODUCCION"), 'UTF-8') . '</h3>
		            	<p class="textos">' . constant("STR_PRISMA_PERFIL_DE_COMPETENCIAS_PASCUAL_INTRO_P1") . '</p>
						<p class="textos">' . constant("STR_PRISMA_PERFIL_DE_COMPETENCIAS_PASCUAL_INTRO_P2") . '</p>
						<p class="textos">' . constant("STR_PRISMA_PERFIL_DE_COMPETENCIAS_PASCUAL_INTRO_P3") . '</p>
			          </div><!--FIN DIV CAJA-->
			          <table class="sintesis" border="1" cellspacing="0" cellpadding="0">';
		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
//		echo $sqlTipos_competencias;
		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();
		$aPuntuacionesCompetencias = array();
		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$sHtml.='
							<tr>
			                  <td colspan="6" style="background:#fff;"><h2 class="subtitulo">' . $listaTipoCompetencia->fields['nombre'] . '</h2></td>
			                </tr>
			                <tr>
			                  <td style="background:#6a6a6b;">&nbsp;</td>
			                  <td class="number">1</td>
			                  <td class="number">2</td>
			                  <td class="number">3</td>
			                  <td class="number">4</td>
			                  <td class="number">5</td>
			                </tr>';

				$cCompetencias = new Competencias();
			 	$cCompetencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdPrueba($_POST['fIdPrueba']);
			 	$cCompetencias->setOrderBy("idCompetencia");
			 	$cCompetencias->setOrder("ASC");
			 	$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			 	$listaCompetencias = $conn->Execute($sqlCompetencias);
			 	$nCompetencias=$listaCompetencias->recordCount();
			 	if($nCompetencias >0){
			 		while(!$listaCompetencias->EOF){
			 			$iPuntacion = $aPuntuaciones[$listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia']];
//			 			echo "<br />" . mb_strtoupper($listaCompetencias->fields['nombre'], 'UTF-8') . $listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'] . " -> punt: " .  $iPuntacion;
				        $sHtml.='
					        <tr>
			                  <td class="tablaTitu">' . mb_strtoupper($listaCompetencias->fields['nombre'], 'UTF-8') . '</td>';
			 				if($iPuntacion==1){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/lechepascual/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==2){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/lechepascual/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/lechepascual/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==4){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/lechepascual/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/lechepascual/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
		               	$sHtml.='
			               	</tr>';


		               	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
		               	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($listaTipoCompetencia->fields['idTipoCompetencia'], false) . "," . $conn->qstr($listaTipoCompetencia->fields['nombre'], false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr($listaCompetencias->fields['descripcion'], false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
		               	$aSQLPuntuacionesC[] = $sSQLExport;
// 		               	echo "<br >-->" . $listaTipoCompetencia->fields['nombre'];
// 		               	echo "<br >--------------->" . $listaCompetencias->fields['nombre'];
				       $listaCompetencias->MoveNext();
			 		}
			 	}
			 	$listaTipoCompetencia->MoveNext();
			 }
		 }
         $sHtml.='
						</table>
						<table class="sintesis" border="0" cellspacing="0" cellpadding="0">';
         				$sHtml.= getConsistencia($aPuntuacionesPRISMA, $idIdioma);
         				$consistencia = getPuntuacionConsistencia($aPuntuacionesPRISMA, $idIdioma);

         				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
         				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr(constant("STR_PRISMA_G_C"), false) . "," . $conn->qstr($consistencia, false) . ",now());\n";
         				$aSQLPuntuacionesC[] = $sSQLExport;

         $sHtml.='
         				</table>
					</div><!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->
				<hr>
				';
		return $sHtml;
	}


	function informeCompetenciasDefiniciones($sHtmlCab, $idIdioma){

		global $conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");


		$cTipos_competenciaDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);


		$sHtml= '
			<div class="pagina">'. $sHtmlCab;
			$sHtml.= '
			<div class="desarrollo">
				<h2 class="subtitulo">' . constant("STR_PRISMA_DEFINICION_DE_LAS_COMPETENCIAS_PASCUAL") . '</h2>
	            <table class="definiciones" width="730" border="1" cellspacing="0" cellpadding="0">
	            ';
			$idTipoCompetencia="";
			$cCompetencia = new Competencias();
			$cCompetencia->setCodIdiomaIso2($idIdioma);
			$cCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencia);
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencia =$listaCompetencias->recordCount();
			$bPintado=false;
			if($nCompetencia > 0){
				while(!$listaCompetencias->EOF){
					if ($listaCompetencias->fields['idTipoCompetencia'] > 2 && $bPintado ==false){
						$sHtml.= '
						             </table>
									</div><!--FIN DIV DESARROLLO-->
								</div>
								<!--FIN DIV PAGINA-->
								<hr>
								';
						$sHtml.= '
							<div class="pagina">'. $sHtmlCab;
							$sHtml.= '
							<div class="desarrollo">
								<h2 class="subtitulo">' . constant("STR_PRISMA_DEFINICION_DE_LAS_COMPETENCIAS_PASCUAL") . '</h2>
					            <table class="definiciones" width="730" border="1" cellspacing="0" cellpadding="0">
					            ';
						$bPintado=true;
					}
					$cC = new Competencias();
					$cC->setCodIdiomaIso2($idIdioma);
					$cC->setIdPrueba($_POST['fIdPrueba']);
					$cC->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
					$sqlC = $cCompetenciasDB->readLista($cC);
					$listaC = $conn->Execute($sqlC);
					$nC =$listaC->recordCount();

					$sDESC = $listaCompetencias->fields['descripcion'];
					$aDESC = explode("<!--SEPARADOR-->",$sDESC);
					if ($idTipoCompetencia != $listaCompetencias->fields['idTipoCompetencia']){
						$sHtml.= '
						<tr>
							<td rowspan="' . ($nC+1) . '" style="width:89px; background:#D9D9D9; border-bottom:2px solid #475464;"><img src="' . constant("DIR_WS_GESTOR") . 'graf/lechepascual/' . $_POST['fCodIdiomaIso2']. '/' . $listaCompetencias->fields['idTipoCompetencia'] . '.jpg" alt="' . $listaCompetencias->fields['idTipoCompetencia'] . '" title="' . $listaCompetencias->fields['idTipoCompetencia'] . '" /></td>
		            	</tr>
		            	';
					}
					$sHtml.= '
						<tr>
		                    <td class="tablaTitu"><p>' . $listaCompetencias->fields['nombre'] . '</p></td>
		                    <td class="descripcion"><p class="textos">' . $aDESC[0] . '</p></td>
		                </tr>';
					$idTipoCompetencia = $listaCompetencias->fields['idTipoCompetencia'];
					$listaCompetencias->MoveNext();
				}
			}

		$sHtml.= '
		             </table>
					</div><!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->
        <hr>
        ';
		//FIN DEFINICIONES COMPETENCIAS
		return $sHtml;
	}

	function getHTMLPerfilIdeal($pMin, $pMax){
		$sHtml='';
		for ($i=1; $i < 9; $i++){
			if (($i >= $pMin) && ($i <= $pMax)){
				$sHtml.='<td class="cel color">&nbsp;</td>';
			}else{
				$sHtml.='<td class="cel">&nbsp;</td>';
			}
		}
	return $sHtml;
	}


	function getConsistencia($aPuntuaciones, $idIdioma)
	{
		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $_pruebaPRISMA;
		$iPGlobal = 0;
			$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
			$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
			$aImagenesBloques = array("energias.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
			$i=0;
			$cEscalas_items=  new Escalas_items();
			$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
			$cEscalas_items->setIdPrueba($_pruebaPRISMA);
			$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
			$rsEscalas_items = $conn->Execute($sqlEscalas_items);
			$sBloques = "";
			while(!$rsEscalas_items->EOF){
				$sBloques .="," . $rsEscalas_items->fields['idBloque'];
				$rsEscalas_items->MoveNext();
			}
			if (!empty($sBloques)){
				$sBloques = substr($sBloques,1);
			}
			$cBloques = new Bloques();
			$cBloques->setCodIdiomaIso2($idIdioma);
			$cBloques->setIdBloque($sBloques);
			$cBloques->setOrderBy("idBloque");
			$cBloques->setOrder("ASC");
			$sqlBloques = $cBloquesDB->readLista($cBloques);
			$listaBloques = $conn->Execute($sqlBloques);

			$iPosiImg=0;
			$iPGlobal = 0;
			$nBloques= $listaBloques->recordCount();
			if($nBloques>0){
				while(!$listaBloques->EOF)
				{
					$cEscalas = new Escalas();
				 	$cEscalas->setCodIdiomaIso2($idIdioma);
				 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				 	$cEscalas->setOrderBy("idEscala");
				 	$cEscalas->setOrder("ASC");
				 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				 	$listaEscalas = $conn->Execute($sqlEscalas);
				 	$nEscalas=$listaEscalas->recordCount();
				 	if($nEscalas > 0){
				 		while(!$listaEscalas->EOF){
					        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
//					       	echo "<br />---------->" . $listaBloques->fields['nombre'] .  " - " . $listaEscalas->fields['nombre'] . " [" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "][PB::" . $iPBaremada . "]";
//					       	echo "<br />iPGlobal::--> (iPBaremada(" . $iPBaremada . ") - 5.5) * (iPBaremada(" . $iPBaremada . ") - 5.5)" . ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

					        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
					        $listaEscalas->MoveNext();
				 		}
				 	}
				 	$listaBloques->MoveNext();
				}
			 }
//			 echo "<br />iPGlobal::-->" . $iPGlobal;
//			 echo "<br />iPGlobal/32::-->" . $iPGlobal/32;
//			 echo "<br />Raiz cuadrada(iPGlobal/32)::-->" . sqrt($iPGlobal/32);
//			 echo "<br />Raiz cuadrada * 100::-->" . sqrt($iPGlobal/32)*100;
//			 echo "<br />Resultado formateado::-->" . number_format(sqrt($iPGlobal/32)*100 ,0);
//			 echo "<br />Paso por baremo_C::-->" . number_format(sqrt($iPGlobal/32)*100 ,0);
//
//			 echo "<br />Consistencia::-->" . baremo_C(number_format(sqrt($iPGlobal/32)*100 ,0));
		    $consistencia = baremo_C(number_format(sqrt($iPGlobal/32)*100 ,0));

	        $sHtml='
		        <tr>
                  <td class="tablaTitu" style="background: none repeat scroll 0 0 #93b1d3;border:none;text-align:center;">' . constant("STR_PRISMA_G_C") . '</td>';
	               	$sHtml.='<td class="celS" style="border:none;width: auto;"><p style="color:#494949; font-size:15px; font-weight:bold;">' . $consistencia . '&nbsp;&nbsp;' . constant("STR_PRISMA_STR_PRISMA_G_C_TXT") . '</p></td>
               	</tr>';

		return $sHtml;
	}
	function getConsistenciaWORD($aPuntuaciones, $idIdioma)
	{
		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $_pruebaPRISMA;
		$iPGlobal = 0;
			$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
			$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
			$aImagenesBloques = array("energias.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
			$i=0;
			$cEscalas_items=  new Escalas_items();
			$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
			$cEscalas_items->setIdPrueba($_pruebaPRISMA);
			$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
			$rsEscalas_items = $conn->Execute($sqlEscalas_items);
			$sBloques = "";
			while(!$rsEscalas_items->EOF){
				$sBloques .="," . $rsEscalas_items->fields['idBloque'];
				$rsEscalas_items->MoveNext();
			}
			if (!empty($sBloques)){
				$sBloques = substr($sBloques,1);
			}
			$cBloques = new Bloques();
			$cBloques->setCodIdiomaIso2($idIdioma);
			$cBloques->setIdBloque($sBloques);
			$cBloques->setOrderBy("idBloque");
			$cBloques->setOrder("ASC");
			$sqlBloques = $cBloquesDB->readLista($cBloques);
			$listaBloques = $conn->Execute($sqlBloques);

			$iPosiImg=0;
			$iPGlobal = 0;
			$nBloques= $listaBloques->recordCount();
			if($nBloques>0){
				while(!$listaBloques->EOF)
				{
					$cEscalas = new Escalas();
				 	$cEscalas->setCodIdiomaIso2($idIdioma);
				 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				 	$cEscalas->setOrderBy("idEscala");
				 	$cEscalas->setOrder("ASC");
				 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				 	$listaEscalas = $conn->Execute($sqlEscalas);
				 	$nEscalas=$listaEscalas->recordCount();
				 	if($nEscalas > 0){
				 		while(!$listaEscalas->EOF){
					        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
//					       	echo "<br />---------->" . $listaBloques->fields['nombre'] .  " - " . $listaEscalas->fields['nombre'] . " [" . $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'] . "][PB::" . $iPBaremada . "]";
//					       	echo "<br />iPGlobal::--> (iPBaremada(" . $iPBaremada . ") - 5.5) * (iPBaremada(" . $iPBaremada . ") - 5.5)" . ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

					        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
					        $listaEscalas->MoveNext();
				 		}
				 	}
				 	$listaBloques->MoveNext();
				}
			 }
//			 echo "<br />iPGlobal::-->" . $iPGlobal;
//			 echo "<br />iPGlobal/32::-->" . $iPGlobal/32;
//			 echo "<br />Raiz cuadrada(iPGlobal/32)::-->" . sqrt($iPGlobal/32);
//			 echo "<br />Raiz cuadrada * 100::-->" . sqrt($iPGlobal/32)*100;
//			 echo "<br />Resultado formateado::-->" . number_format(sqrt($iPGlobal/32)*100 ,0);
//			 echo "<br />Paso por baremo_C::-->" . number_format(sqrt($iPGlobal/32)*100 ,0);
//
//			 echo "<br />Consistencia::-->" . baremo_C(number_format(sqrt($iPGlobal/32)*100 ,0));
		    $consistencia = baremo_C(number_format(sqrt($iPGlobal/32)*100 ,0));

		return $consistencia;
	}
	function generaNarrativoPascual($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
	{

		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES DE VALORES
		$sBloques = "15,16,17,18,19,20";
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
		$sEscala = "";
		$sHtml.= '
		        	<h2 class="subtitulo" style="text-align:center;background-color:gray;">VALORES</h2>
		            ';
		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#365f91;">' . $sBloqueAnterior . '</h2>
		            ';
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
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sEscala = $listaEscalas->fields['nombre'];
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
//			 				$sHtml.= '
//		        				<h2 class="subtitulo_prisma" style="color:#95b3d7;">' . $listaEscalas->fields['nombre'] . '</h2>
//		            		';
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
	 					$iEsalas++;
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
		<hr>
		';

     return $sHtml;
	}

	function generaNarrativoPascual2($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
	{

		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES DE COMPETENCIAS CORPORATIVAS
		$sBloques = "21,22,23";
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
		$sEscala = "";
 		$sHtml.= '
 			<br /><br />
	    	<h2 class="subtitulo" style="text-align:center;background-color:gray;">COMPETENCIAS CORPORATIVAS</h2>
	    ';

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#365f91;">' . $sBloqueAnterior . '</h2>
		            ';
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
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sEscala = $listaEscalas->fields['nombre'];
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
//			 				$sHtml.= '
//		        				<h2 class="subtitulo_prisma" style="color:#95b3d7;">' . $listaEscalas->fields['nombre'] . '</h2>
//		            		';
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
		<hr>
		';

     return $sHtml;
	}

	function generaNarrativoPascual3($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
	{

		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES DE COMPETENCIAS CORPORATIVAS
		//$sBloques = "23,24,25"; // La 23 y 24 las quitó Pascual
		$sBloques = "26";
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
		$sEscala = "";
//		$sHtml.= '
//		        	<h2 class="subtitulo" style="text-align: left;color:#000000;">VALORES</h2>
//		            ';
		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#365f91;">' . $sBloqueAnterior . '</h2>
		            ';
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
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sEscala = $listaEscalas->fields['nombre'];
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
//			 				$sHtml.= '
//		        				<h2 class="subtitulo_prisma" style="color:#95b3d7;">' . $listaEscalas->fields['nombre'] . '</h2>
//		            		';
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}
 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
		<hr>
		';

     return $sHtml;
	}

	function generaNarrativoPascual4($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
	{

		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES COMPETENCIAS ESPECÍFICAS
		$sBloques = "24,25,26,27,28,29";
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
		$sEscala = "";
 		$sHtml.= '
 			<br /><br />
	    	<h2 class="subtitulo" style="text-align:center;background-color:gray;">COMPETENCIAS ESPECÍFICAS</h2>
	    ';

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#365f91;">' . $sBloqueAnterior . '</h2>
		            ';
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
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sEscala = $listaEscalas->fields['nombre'];
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
//			 				$sHtml.= '
//		        				<h2 class="subtitulo_prisma" style="color:#95b3d7;">' . $listaEscalas->fields['nombre'] . '</h2>
//		            		';
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
		<hr>
		';

     return $sHtml;
	}

	function generaNarrativoPascual5($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
	{

		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES DE COMPETENCIAS ESPECÍFICAS
		$sBloques = "31,32";
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
		$sEscala = "";
//		$sHtml.= '
//		        	<h2 class="subtitulo" style="text-align: left;color:#000000;">VALORES</h2>
//		            ';
		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#365f91;">' . $sBloqueAnterior . '</h2>
		            ';
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
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sEscala = $listaEscalas->fields['nombre'];
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
//			 				$sHtml.= '
//		        				<h2 class="subtitulo_prisma" style="color:#95b3d7;">' . $listaEscalas->fields['nombre'] . '</h2>
//		            		';
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
		<hr>
		';

     return $sHtml;
	}

	function generaNarrativoPascual6($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
	{

		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);
		$sHtml='';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES DE COMPETENCIAS ESPECÍFICAS
		$sBloques = "30,31,32,33";
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
		$sEscala = "";
//		$sHtml.= '
//		        	<h2 class="subtitulo" style="text-align: left;color:#000000;">VALORES</h2>
//		            ';
		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#365f91;">' . $sBloqueAnterior . '</h2>
		            ';
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
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sEscala = $listaEscalas->fields['nombre'];
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
//			 				$sHtml.= '
//		        				<h2 class="subtitulo_prisma" style="color:#95b3d7;">' . $listaEscalas->fields['nombre'] . '</h2>
//		            		';
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
    <hr>
    ';

     return $sHtml;
	}

	function generaPascualWORD($cPruebas, $aPuntuacionesCompetencias, $aPuntuaciones, $idIdioma, $sDescInforme, $cCandidato)
	{
		global $conn;
		global $aPuntuacionesPRISMA;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");

    	$txtWORD = "";
    	include_once('39_IMG_Word.php');
		include_once('39_Word.php');

		$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);

		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();
		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
			 	$cCompetencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
			 	$cCompetencias->setIdPrueba($_POST['fIdPrueba']);
			 	$cCompetencias->setOrderBy("idCompetencia");
			 	$cCompetencias->setOrder("ASC");
			 	$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
//			 	echo "<br />" . $sqlCompetencias;
			 	$listaCompetencias = $conn->Execute($sqlCompetencias);
			 	$nCompetencias=$listaCompetencias->recordCount();
				if($nCompetencias >0)
				{
					while(!$listaCompetencias->EOF)
          			{
			 			$iPuntacion = $aPuntuacionesCompetencias[$listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia']];
//			 			echo "<br />" . mb_strtoupper($listaCompetencias->fields['nombre'], 'UTF-8') . $listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'] . " -> punt: " .  $iPuntacion;
		                $strC = $listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
        	        	if($iPuntacion==1){
							//Reemplazamos para esa competencia B con la imágen
	                	    $sReemplaza ='@' . $strC . 'B@';
	                    	$txtWORD = str_replace($sReemplaza , $sB , $txtWORD);
					      }else{
		                    //Reemplazamos para esa competencia con vacio
		                    $sReemplaza ='@' . $strC . 'B@';
		                    $txtWORD = str_replace($sReemplaza , $sVACIO , $txtWORD);
					      }
					    if($iPuntacion==2){
							//Reemplazamos para esa competencia BM con la imágen
							$sReemplaza ='@' . $strC . 'BM@';
							$txtWORD = str_replace($sReemplaza , $sBM , $txtWORD);
					    }else{
		                    $sReemplaza ='@' . $strC . 'BM@';
		                    $txtWORD = str_replace($sReemplaza , $sVACIO , $txtWORD);
					    }
						if($iPuntacion==3){
							//Reemplazamos para esa competencia M con la imágen
		                    $sReemplaza ='@' . $strC . 'M@';
		                    $txtWORD = str_replace($sReemplaza , $sM , $txtWORD);
				       	}else{
		                    $sReemplaza ='@' . $strC . 'M@';
		                    $txtWORD = str_replace($sReemplaza , $sVACIO , $txtWORD);
				       	}
						if($iPuntacion==4){
							//Reemplazamos para esa competencia MA con la imágen
		                    $sReemplaza ='@' . $strC . 'MA@';
		                    $txtWORD = str_replace($sReemplaza , $sMA , $txtWORD);
						}else{
		                    $sReemplaza ='@' . $strC . 'MA@';
		                    $txtWORD = str_replace($sReemplaza , $sVACIO , $txtWORD);
					    }
					    if($iPuntacion==5){
							//Reemplazamos para esa competencia A con la imágen
		                    $sReemplaza ='@' . $strC . 'A@';
		                    $txtWORD = str_replace($sReemplaza , $sA , $txtWORD);
					    }else{
		                    $sReemplaza ='@' . $strC . 'A@';
		                    $txtWORD = str_replace($sReemplaza , $sVACIO , $txtWORD);
					    }
				       $listaCompetencias->MoveNext();
			 		}
			 	}
			 	$listaTipoCompetencia->MoveNext();
			 }
		 }


		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cTextos_escalasDB = new Textos_escalasDB($conn);

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$i=0;
		//BLOQUES
		global $sBloques;
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "BLOQUES:: " . $sqlBloques;
		$listaBloques = $conn->Execute($sqlBloques);
		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		$sBloqueAnterior = "";
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
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
            $strBE = $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
		//	 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$txtWPunt = str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']);
                  $sReemplaza ='[' . $strBE . ']';
                  $txtWORD = str_replace($sReemplaza , $txtWPunt , $txtWORD);
		 						}
		 						$listaTextos->MoveNext();
		 					}
	 					}
				        $listaEscalas->MoveNext();
			 		}
			 	}
	 			$listaBloques->MoveNext();
	 		}
	 	}
		$iConsistencia = getConsistenciaWORD($aPuntuacionesPRISMA, $idIdioma);
		$txtWORD = str_replace("[grado]" , $iConsistencia , $txtWORD);

		return $txtWORD;
	}

	function getPuntuacionConsistencia($aPuntuaciones, $idIdioma)
	{
		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $_pruebaPRISMA;
		$iPGlobal = 0;
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aImagenesBloques = array("energias.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
		$i=0;
		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_pruebaPRISMA);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
		}
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);

		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();
		if($nBloques>0){
			while(!$listaBloques->EOF)
			{
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($idIdioma);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();
				if($nEscalas > 0){
					while(!$listaEscalas->EOF){
						$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
						$listaEscalas->MoveNext();
					}
				}
				$listaBloques->MoveNext();
			}
		}
		$consistencia = baremo_C(number_format(sqrt($iPGlobal/32)*100 ,0));

		return $consistencia;
	}
/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
