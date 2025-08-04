<?php
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
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
		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

		//HRAccess de CM
		calculaResultados($conn, $_POST['fIdProceso'], $_POST['fIdEmpresa']);


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
				        	while(!$listaCompetencias_items->EOF){
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
								switch ($cRespuestas_pruebas_items->getIdOpcion())
								{
									case '1':	// Mejor
										$iPdCompetencias += 2;
										break;
									case '2':	// Peor//
										$iPdCompetencias += 0;
										break;
									default:	// Sin contestar opcion 0 en respuestas
										$iPdCompetencias += 1;
										break;
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
//				        echo $sqlBaremos_resultado_competencia . "<br />";
				        $listaBaremos_resultado_competencia = $conn->Execute($sqlBaremos_resultado_competencia);
//				        echo $iPdCompetencias . "<br />";
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
				       	$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPBaremadaCompetencias;
//				       	echo "<br />" . $listaCompetencias->fields['nombre'] . " - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias;
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
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/resetCSS.css"/>
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/style.css"/>
					<title>' . $cPruebas->getIdPrueba() . ' - ' . $_sBaremo . '</title>
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
						    <td class="logo">' . $cPruebas->getNombre() . '
						    <!-- <img src="'.constant("DIR_WS_GESTOR").'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo-pequenio.jpg" title="logo"/> -->
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
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/prueba' . $cPruebas->getIdPrueba() . '/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_nombre_infome"><p>' . $cPruebas->getNombre() . '</p></div>';
		$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_puntos_infome"><p>' . str_repeat(".", 40) . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_DEF_CANDIDATO") . ':</strong><br />' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong><br />' . $cUtilidades->PrintDate(date("Y-m-d"), $_POST['fCodIdiomaIso2']) . '</p>
				</div>
				<div id="ts">
					<img src="' . constant("DIR_WS_GESTOR") . 'graf/TS.jpg" />
				</div>

		    	<!-- <h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2> -->
			</div>
			<!--FIN DIV PAGINA-->
      <hr>
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA
		$_POST['fIdTipoInforme']=3;
		switch ($_POST['fIdTipoInforme'])
		{
			case(2);//Informe competencias
				//SINTESIS DE COMPETENCIAS
				$sHtml.= informeSintesisCompetencias($aPuntuacionesCompetencias,$sHtmlCab,$_POST['fCodIdiomaIso2']);

				//PAGINAS INFORME DE COMPETENCIAS
				$sHtml.= informeCompetencias($aPuntuacionesCompetencias,$sHtmlCab,$_POST['fCodIdiomaIso2']);

				//DEFINICIONES DE LAS COMPETENCIAS(Sólo html)
				$sHtml.= informeCompetenciasDefiniciones($sHtmlCab);
				break;
			case(3);//Informe Completo
				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	//$sHtml.= perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);

			    //PAGINA DE PERFIL GENERAL DE PERSONALIDAD LABORAL
			    //$sHtml.= perfilGeneralPersonalidadLaboral($aPuntuaciones , $sHtmlCab);

				//FUNCIÓN PARA generar la página Narrativo
			   	//$sHtml.= generaNarrativoPrisma($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2'],$cCandidato);

			    //PAGINAS INFORME DE COMPETENCIAS
				$sHtml.= informeCompetencias($aPuntuacionesCompetencias,$sHtmlCab,$_POST['fCodIdiomaIso2']);

				//DEFINICIONES DE LAS COMPETENCIAS(Sólo html)
				//$sHtml.= informeCompetenciasDefiniciones($sHtmlCab);

				//SINTESIS DE COMPETENCIAS
				//$sHtml.= informeSintesisCompetencias($aPuntuacionesCompetencias,$sHtmlCab,$_POST['fCodIdiomaIso2']);

				//ESTILOS DE PROCESAMIENTO MENTAL
				//$sHtml.= generaPerfilEstilos($aPuntuaciones , $sHtmlCab);

				//PERFIL COMPETENCIA EMOCIONAL
				//$sHtml.= generaPerfilCompetenciaEmocional($aPuntuaciones, $sHtmlCab, $_POST['fIdPrueba'], $_POST['fIdBaremo'],$_POST['fCodIdiomaIso2Prueba'], $_POST['fIdProceso'], $_POST['fIdCandidato'],$_POST['fIdEmpresa']);

			    //PERFIL ESTRESORES
				//$sHtml.=generaPerfilesEstresores($aPuntuaciones, $sHtmlCab, $_POST['fIdPrueba'], $_POST['fIdBaremo'],$_POST['fCodIdiomaIso2Prueba'], $_POST['fIdProceso'], $_POST['fIdCandidato'],$_POST['fIdEmpresa']);
				break;
			case(16);//Perfil Básico + Perfil General
				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	$sHtml.= perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);

			    //PAGINA DE PERFIL GENERAL DE PERSONALIDAD LABORAL
			    $sHtml.= perfilGeneralPersonalidadLaboral($aPuntuaciones , $sHtmlCab);
				break;
			case(20);//Perfil Emocional + Perfil Estrés
				//PERFIL COMPETENCIA EMOCIONAL
				$sHtml.= generaPerfilCompetenciaEmocional($aPuntuaciones, $sHtmlCab, $_POST['fIdPrueba'], $_POST['fIdBaremo'],$_POST['fCodIdiomaIso2Prueba'], $_POST['fIdProceso'], $_POST['fIdCandidato'],$_POST['fIdEmpresa']);

			    //PERFIL ESTRESORES
				$sHtml.=generaPerfilesEstresores($aPuntuaciones, $sHtmlCab, $_POST['fIdPrueba'], $_POST['fIdBaremo'],$_POST['fCodIdiomaIso2Prueba'], $_POST['fIdProceso'], $_POST['fIdCandidato'],$_POST['fIdEmpresa']);
				break;
			case(21);//Perfil Estilos
				//ESTILOS DE PROCESAMIENTO MENTAL
				$sHtml.= generaPerfilEstilos($aPuntuaciones , $sHtmlCab);
				break;
			case(26);//Síntesis Perfil Competencias
				//DEFINICIONES DE LAS COMPETENCIAS(Sólo html)
				$sHtml.= informeCompetenciasDefiniciones($sHtmlCab);

				//SINTESIS DE COMPETENCIAS
				$sHtml.= informeSintesisCompetencias($aPuntuacionesCompetencias,$sHtmlCab,$_POST['fCodIdiomaIso2']);
				break;
			case(11);
				//FUNCIÓN PARA generar la página Narrativo
			   	$sHtml.= generaNarrativoPrisma($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2'],$cCandidato);
				break;

			case(28);//Perfil Básico Narrativo
				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	$sHtml.= perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);

			   	//FUNCIÓN PARA generar la página Narrativo
			   	$sHtml.= generaNarrativoPrisma($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2'],$cCandidato);
				break;

		}

		$sHtml.= '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
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

    $footer_html    =  mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . str_repeat(" ", 70) . constant("STR_PIE_INFORMES");
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
	function textoDefinicion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str=constant("STR_PRISMA_NUNCA");	//"NUNCA";
		}
		if($puntuacion ==3 || $puntuacion==4){
			$str=constant("STR_PRISMA_CASI_NUNCA");	//"CASI NUNCA";
		}
		if($puntuacion ==5 || $puntuacion==6){
			$str=constant("STR_PRISMA_A_VECES");	//"A VECES";
		}
		if($puntuacion ==7 || $puntuacion==8){
			$str=constant("STR_PRISMA_CASI_SIEMPRE");	//"CASI SIEMPRE";
		}
		if($puntuacion ==9 || $puntuacion==10){
			$str=constant("STR_PRISMA_SIEMPRE");	//"SIEMPRE";
		}
		return $str;
	}
	//Funcion que devuelve un texto a la parte del informe de competencias de prisma
	function textoPuntuacion($puntuacion){
		$str="";
		if($puntuacion ==1 || $puntuacion==2){
			$str=constant("STR_PRISMA_AREA_CLAVE_DE_MEJORA");	//"ÁREA CLAVE DE MEJORA";
		}
		if($puntuacion ==3 || $puntuacion==4){
			$str=constant("STR_PRISMA_AREA_POTENCIAL_DESARROLLO");	//"ÁREA POTENCIAL DESARROLLO";
		}
		if($puntuacion ==5 || $puntuacion==6){
			$str=constant("STR_PRISMA_AREA_EN_DESARROLLO");	//"ÁREA EN DESARROLLO";
		}
		if($puntuacion ==7 || $puntuacion==8){
			$str=constant("STR_PRISMA_AREA_POTENCIAL_FORTALEZA");	//"ÁREA POTENCIAL FORTALEZA";
		}
		if($puntuacion ==9 || $puntuacion==10){
			$str=constant("STR_PRISMA_AREA_DE_FORTALEZA");	//"ÁREA DE FORTALEZA";
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
	 * INTERPRETACIÓN DE LA ESCALA DE CONSISTENCIA
	 * PERFIL DE PERSONALIDAD LABORAL
	 */
	function perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;

		//PÁGINA INTRODUCCIÓN, 1
		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		$sHtml.= '
				<div class="desarrollo">
		        	<h2 class="subtitulo">' . mb_strtoupper(constant("STR_INTRODUCCION"), 'UTF-8') . '</h2>
        		    <div class="caja">
            			<p class="textos">' . constant("STR_PRISMA_INTRO_P1") . '</p>
						<p class="textos">' . constant("STR_PRISMA_INTRO_P2") . '</p>
						<p class="textos">' . constant("STR_PRISMA_INTRO_P3") . '</p>
		                 <ul>
							<li>' . constant("STR_PRISMA_INTRO_P4") . '</li>
							<li>' . constant("STR_PRISMA_INTRO_P5") . '</li>
							<li>' . constant("STR_PRISMA_INTRO_P6") . '</li>
							<li>' . constant("STR_PRISMA_INTRO_P7") . '</li>
		                 </ul>
						<p class="textos">' . constant("STR_PRISMA_INTRO_P8") . '</p>
						<p class="textos">' . constant("STR_PRISMA_INTRO_P9") . '</p>
		            </div>
        		    <div class="caja">
						<h3 class="encabezado">' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA") . '</h3>
						<p class="textos">' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P1") . '</p>
		                <p class="textos">' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P2") . '</p>
						<img style="width: 666px;" src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/ejemploConsistencia.jpg" alt="Escala Consistencia" title="Escala Consistencia" />
						<p class="textos"><span>' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P3A") . '</span>:' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P3B") . '</p>
		                <p class="textos"><span>' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P4A") . '</span>:' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P4B") . '</p>
		                <p class="textos"><span>' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P5A") . '</span>:' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P5B") . '</p>
		                <p class="textos"><span>' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P6A") . '</span>:' . constant("STR_PRISMA_INTERPRETACION_ESCALA_CONSISTENCIA_P6B") . '</p>
		            </div>
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
			        <table id="personalidad" border="1">
			          <tr>
			            <td class="azul" colspan="4" rowspan="2" valign="middle"><h2>' . constant("STR_PRISMA_PERFIL_DE_PERSONALIDAD_LABORAL") . ' </h2></td>
			            <td class="celS" ><p>1-2</p></td>
			            <td class="celS" ><p>3-4</p></td>
			            <td class="celS" ><p>5-6</p></td>
			            <td class="celS" ><p>7-8</p></td>
			            <td class="celS last" ><p>9-10</p></td>
			          </tr>
			          <tr>
			            <td class="celI" ><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
			            <td class="celI" ><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
			            <td class="celI" ><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
			            <td class="celI" ><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
			            <td class="celI last" ><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
			          </tr>';
					// ENERGÍAS Y MOTIVACIONES
					$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
					$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
					$aImagenesBloques = array("energias.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
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
//					echo "<br />1111-->sBloques::" . $sBloques;
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
				 	$sSeparadorNum = '
					        	<tr>
					        		<td colspan="4" bgcolor="#6A6A6C">&nbsp;</td>
						            <td class="celI" ><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
						            <td class="celI" ><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
						            <td class="celI" ><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
						            <td class="celI" ><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
						            <td class="celI last" ><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
								</tr>
					        	';
				 	$aSeparadorNum = array(1,4);
				 	$iSeparadorNum =0;
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
						 	$nVueltas = 1;
						 	if($nEscalas > 0){
						 		$bPrimeraVuelta = true;
						 		while(!$listaEscalas->EOF){
							        $sHtml.='<tr>';
							        if($bPrimeraVuelta){
							        	$sHtml.='
							        	<td rowspan="'.$nEscalas.'" style="width:58px;border-bottom: 2px solid #8796AA;" bgcolor="#d9d9d9">
                        <img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $idIdioma. '/'.$aImagenesBloques[$iPosiImg].'" alt="'.$listaBloques->fields['nombre'].'" title="'.$listaBloques->fields['nombre'].'" />
                        </td>
							        	';
							        }
							        $sSeparador = '';
							        if ($nVueltas == $nEscalas){
							        	$nVueltas = 1;
							        	$sSeparador = ' style="border-bottom: 2px solid #8796AA;" ';
							        }

							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							        $sHtml.='
							        	<td class="number" ' . $sSeparador . '><p>'.$iPBaremada.'</p></td>
							        	<td class="tablaTitu" ' . $sSeparador . '><p>' . $listaEscalas->fields['nombre'] . '</p></td>
										<td class="descripcion" ' . $sSeparador . '><p>' . $listaEscalas->fields['descripcion'] . '</p></td>
										';

							       	if($iPBaremada==1 || $iPBaremada==2){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==3 || $iPBaremada==4){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==5 || $iPBaremada==6){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==7 || $iPBaremada==8){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==9 || $iPBaremada==10){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	$sHtml.='
							       	</tr>
							       	';
							       	$nVueltas++;
							       	$bPrimeraVuelta = false;
							        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
							        $listaEscalas->MoveNext();
						 		}
						 	}
						 	if (in_array($iSeparadorNum, $aSeparadorNum)){
						 		$sHtml.=$sSeparadorNum;
						 	}
						 	$iSeparadorNum++;
						 	$iPosiImg++;
						 	$listaBloques->MoveNext();
						}
					 }

				    $consistencia = baremo_C(number_format(sqrt($iPGlobal/32)*100 ,0));

					$sHtml.='
						<tr>
				            <td rowspan="2" bgcolor="#ff411d" style="border:none;text-align:center;"><p style="text-align:center;"><strong>' . constant("STR_PRISMA_G_C") . '</strong></p></td>
				            <td class="number"><p>'.$consistencia.'</p></td>
				            <td colspan="2"><p style="color:#494949; font-size:15px; font-weight:bold;">' . constant("STR_PRISMA_STR_PRISMA_G_C_TXT") . '</p></td>
				           ';
			            if($consistencia==1 || $consistencia==2){
							$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
				       	}else{
				       		$sHtml.='<td class="simbol">&nbsp;</td>';
				       	}
				       	if($consistencia==3 || $consistencia==4){
							$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
				       	}else{
				       		$sHtml.='<td class="simbol">&nbsp;</td>';
				       	}
				       	if($consistencia==5 || $consistencia==6){
							$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
				       	}else{
				       		$sHtml.='<td class="simbol">&nbsp;</td>';
				       	}
				       	if($consistencia==7 || $consistencia==8){
							$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
				       	}else{
				       		$sHtml.='<td class="simbol">&nbsp;</td>';
				       	}
				       	if($consistencia==9 || $consistencia==10){
							$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
				       	}else{
				       		$sHtml.='<td class="simbol">&nbsp;</td>';
				       	}
			     $sHtml.='
			     		</tr>
						<tr>
				            <td colspan="3" bgcolor="#ff411d">&nbsp;</td>
				            <td class="celS"><p>1-2</p></td>
				            <td class="celS"><p>3-4</p></td>
				            <td class="celS"><p>5-6</p></td>
				            <td class="celS"><p>7-8</p></td>
				            <td class="celS"><p>9-10</p></td>
						</tr>
					</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

		return $sHtml;
	}
	/*
	 * PÁGINA PERFIL GENERAL PERSONALIDAD LABORAL
	 * E1  --> 1-1
	 * E2  --> 1-2
	 * E3  --> 1-3
	 * E4  --> 1-4
	 * E5  --> 1-5
	 * E6  --> 2-1
	 * E7  --> 2-2
	 * E8  --> 2-3
	 * E9  --> 2-4
	 * E10 --> 2-5
	 * E11 --> 2-6
	 * S1  --> 3-1
	 * S2  --> 3-2
	 * S3  --> 3-3
	 * S4  --> 4-1
	 * S5  --> 4-2
	 * S6  --> 4-3
	 * S7  --> 5-1
	 * S8  --> 5-2
	 * S9  --> 5-3
	 * S10 --> 5-4
	 * M1  --> 6-1
	 * M2  --> 6-2
	 * M3  --> 6-3
	 * M4  --> 7-1
	 * M5  --> 7-2
	 * M6  --> 7-3
	 * M7  --> 8-1
	 * M8  --> 8-2
	 * M9  --> 8-3
	 * M10 --> 9-1
	 * M11 --> 9-2
	 */
	function perfilGeneralPersonalidadLaboral($aPuntuaciones , $sHtmlCab){

		$GEM = round(($aPuntuaciones["1-1"] + $aPuntuaciones["1-2"] + $aPuntuaciones["1-3"] + $aPuntuaciones["1-4"] + $aPuntuaciones["1-5"])/5 , 0);
//		echo "<br />";		//GEM Nivel General de Energía y Motivaciones
		$GIE = round(($aPuntuaciones["3-1"]+$aPuntuaciones["3-2"]+$aPuntuaciones["3-3"])/3 , 0);
//		echo "<br />";//GIE Nivel General de Extroversión y Protagonismo Social
		$GAI = round(($aPuntuaciones["5-1"]+$aPuntuaciones["5-2"]+$aPuntuaciones["5-3"]+ $aPuntuaciones["5-4"])/4 , 0);
//		echo "<br />";		//GAI Nivel General de Influencia, Mando o Ascendencia
		$GOC = round(($aPuntuaciones["4-1"]+$aPuntuaciones["4-2"]+$aPuntuaciones["4-3"])/3 , 0);
//		echo "<br />";				//GOC Nivel General de Empatía y Orientación a las Personas
		$COG = round(($aPuntuaciones["1-2"]+$aPuntuaciones["1-3"])/2 , 0);
//		echo "<br />";				//COG Nivel General de Orientación a Tareas y Objetivos
		$GPA = round(($aPuntuaciones["6-1"]+$aPuntuaciones["6-2"]+$aPuntuaciones["6-3"]+$aPuntuaciones["7-2"]+$aPuntuaciones["1-5"])/ 5 , 0);
//		echo "<br />";		//GPA Nivel General de Potencia Analítica y Solución de Problemas
		$GFM = round(($aPuntuaciones["8-1"]+$aPuntuaciones["8-2"])/2 , 0);
//		echo "<br />";			//GFM Nivel General de Flexibilidad Mental
		$GDO = round(($aPuntuaciones["1-2"]+$aPuntuaciones["9-1"]+$aPuntuaciones["9-2"])/3 , 0);
//		echo "<br />";			//GDO Nivel General de Disciplina y Orden
		$GPC = round(($aPuntuaciones["6-2"]+$aPuntuaciones["9-1"]+$aPuntuaciones["9-2"]+$aPuntuaciones["1-2"])/4 , 0);
//		echo "<br />";  		//GPC Nivel General de Precisión y Calidad
		$GRO = round(($aPuntuaciones["8-3"]+$aPuntuaciones["1-5"])/2 , 0);
//		echo "<br />";			//GRO Nivel General de Rapidez y Operatividad
		$GCE = round(($aPuntuaciones["2-2"]+$aPuntuaciones["2-4"])/2 , 0);
//		echo "<br />";				//GCE Nivel General de Control Emocional
		$IGT = round(($aPuntuaciones["2-2"]+$aPuntuaciones["2-3"]+$aPuntuaciones["2-4"])/3 , 0);
//		echo "<br />";				//IGT Nivel General de Tolerancia al estres

		// PÁGINA PERFIL GENERAL PERSONALIDAD LABORAL
		$sHtml='
		<div class="pagina">' . $sHtmlCab;
		$sHtml.='
		<div class="desarrollo">
      		<h2 class="subtitulo">' . constant("STR_PRISMA_PERFIL_GENERAL_DE_PERSONALIDAD_LABORAL") . '</h2>
            <div class="caja" style="padding-top:30px; margin-bottom:55px;">
            	<p class="textos">' . constant("STR_PRISMA_PERFIL_GENERAL_DE_PERSONALIDAD_LABORAL_P1") . '</p>
				<p class="textos">' . constant("STR_PRISMA_PERFIL_GENERAL_DE_PERSONALIDAD_LABORAL_P2") . '</p>
            </div><!--FIN DIV CAJA-->
			<table id="perfilGeneral" class="perfilGeneral" border="1" cellspacing="0" cellpadding="0">
				<tr class="cabeceraPerfil">
                	<td class="pLinea" width="200"><p>' . constant("STR_PRISMA_NIVEL_GENERAL_DE") . '</p></td>
                    <td class="pLinea" width="60"><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
                    <td class="pLinea" width="60"><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
                    <td class="pLinea" width="60"><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
                    <td class="pLinea" width="60"><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
                    <td class="pLinea" width="60"><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
				</tr>
                <tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_ENERGIA_Y_MOTIVACIONES") . '</p></td>';
			if($GEM==1 || $GEM==2){
				$sHtml.='
					<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
			 }else{
			 	$sHtml.='
			 		<td>&nbsp;</td>';
			}
	       	if($GEM==3 || $GEM==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GEM==5 || $GEM==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GEM==7 || $GEM==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GEM==9 || $GEM==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
        		<tr class="cabeceraPerfil">
        	       	<td class="tablaTitu"><p>' . constant("STR_PRISMA_EXTROVERSION_Y_PROTAGONISMO_SOCIAL") . '</p></td>';
			if($GIE==1 || $GIE==2){
				$sHtml.='
					<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
			}else{
				$sHtml.='<td>&nbsp;</td>';
			}
			if($GIE==3 || $GIE==4){
				$sHtml.='
					<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
			}else{
				$sHtml.='<td>&nbsp;</td>';
			}
	       	if($GIE==5 || $GIE==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GIE==7 || $GIE==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GIE==9 || $GIE==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
                <tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_INFLUENCIA_MANDO_O_ASCENDENCIA") . '</p></td>';
			if($GAI==1 || $GAI==2){
				$sHtml.='
					<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GAI==3 || $GAI==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GAI==5 || $GAI==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GAI==7 || $GAI==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GAI==9 || $GAI==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
                <tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_EMPATIA_Y_ORIENTACION_A_LAS_PERSONAS") . '</p></td>';
			if($GOC==1 || $GOC==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GOC==3 || $GOC==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GOC==5 || $GOC==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GOC==7 || $GOC==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GOC==9 || $GOC==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
            	<tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_ORIENTACION_A_TAREAS_Y_OBJETIVOS") . '</p></td>';
			if($COG==1 || $COG==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($COG==3 || $COG==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($COG==5 || $COG==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($COG==7 || $COG==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($COG==9 || $COG==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
                <tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_POTENCIA_ANALITICA_Y_SOLUCIAON_DE_PROBLEMAS") . '</p></td>';
			if($GPA==1 || $GPA==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPA==3 || $GPA==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPA==5 || $GPA==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPA==7 || $GPA==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPA==9 || $GPA==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
            	<tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_FLEXIBILIDAD_MENTAL") . '</p></td>';
			if($GFM==1 || $GFM==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GFM==3 || $GFM==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GFM==5 || $GFM==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GFM==7 || $GFM==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GFM==9 || $GFM==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
        		<tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_DISCIPLINA_Y_ORDEN") . '</p></td>';
			if($GDO==1 || $GDO==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GDO==3 || $GDO==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GDO==5 || $GDO==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GDO==7 || $GDO==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GDO==9 || $GDO==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
                <tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_PRECISION_Y_CALIDAD") . '</p></td>';
			if($GPC==1 || $GPC==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPC==3 || $GPC==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPC==5 || $GPC==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPC==7 || $GPC==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GPC==9 || $GPC==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
                <tr class="cabeceraPerfil">
					<td class="tablaTitu"><p>' . constant("STR_PRISMA_RAPIDEZ_Y_OPERATIVIDAD") . '</p></td>';
			if($GRO==1 || $GRO==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GRO==3 || $GRO==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GRO==5 || $GRO==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GRO==7 || $GRO==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GRO==9 || $GRO==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
                <tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_CONTROL_EMOCIONAL") . '</p></td>';
			if($GCE==1 || $GCE==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GCE==3 || $GCE==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GCE==5 || $GCE==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GCE==7 || $GCE==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($GCE==9 || $GCE==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
        		<tr class="cabeceraPerfil">
                	<td class="tablaTitu"><p>' . constant("STR_PRISMA_TOLERANCIA_AL_ESTRES") . '</p></td>';
			if($IGT==1 || $IGT==2){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($IGT==3 || $IGT==4){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($IGT==5 || $IGT==6){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($IGT==7 || $IGT==8){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
	       	if($IGT==9 || $IGT==10){
				$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
	       	}else{
	       		$sHtml.='<td>&nbsp;</td>';
	       	}
		$sHtml.='
				</tr>
			</table>
      	</div><!--FIN DIV DESARROLLO-->
      </div>
      <!--FIN DIV PAGINA-->
      <hr>';
		return $sHtml;
	}

	/*
	 * INFORME ORIENTADO A COMPETENCIAS
	 * PÁGINA COMPETENCIAS PARA LA GESTIÓN
	 * PÁGINA COMPETENCIAS TECNICAS
	 * PÁGINA COMPETENCIAS PARA EL DESARROLLO DEL NEGOCIO
	 */
	function informeCompetencias($aPuntuaciones , $sHtmlCab, $idIdioma){

		global $conn;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");


		$cTipos_competenciaDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);

		$sHtml= '
		<div class="pagina">'. $sHtmlCab;
		// PÁGINA COMPETENCIAS
		$sHtml.= '
			<div class="desarrollo">
	      		<h2 style="line-height: 3px;">&nbsp;</h2>
				<table class="competencias" width="730" border="0" cellspacing="0" cellpadding="0">';

			$cTipoCompetencia = new Tipos_competencias();
			$cTipoCompetencia->setCodIdiomaIso2($idIdioma);
			$cTipoCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cTipoCompetencia->setIdTipoCompetencia("1");
			$cTipoCompetencia = $cTipos_competenciaDB->readEntidad($cTipoCompetencia);
			$sHtml.= '<tr>
                        <td colspan="3" bgcolor="#fff" style="border-bottom:3px solid #ff411d;">
                        <h2 class="subtitulo" style="line-height:24px; color:#555">' . $cTipoCompetencia->getNombre() . '</h2></td>
                      </tr>	';

			$cCompetencia = new Competencias();
			$cCompetencia->setCodIdiomaIso2($idIdioma);
			$cCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cCompetencia->setIdTipoCompetencia("1");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencia);
//			echo $sqlCompetencias;
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencia =$listaCompetencias->recordCount();
			//PÁGINA COMPETENCIAS
			if($nCompetencia>0){
				while(!$listaCompetencias->EOF){
					$iPuntacion = $aPuntuaciones[$listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia']];
//					echo "<br />" . $listaCompetencias->fields['nombre'] . ": " . $listaCompetencias->fields['idTipoCompetencia'] . " - " . $listaCompetencias->fields['idCompetencia'];
//					echo "<br />iPuntacion:: " . $iPuntacion;
					$sHtml.= ' <tr class="pLinea">
		                        <td class="tablaTitu"><p>' . $listaCompetencias->fields['nombre'] . '</p></td>
		                        <td class="descripcion" valign="top">
		                        	<p class="intro">' . textoDefinicion($iPuntacion) . '</p>
									<p class="textos">' . $listaCompetencias->fields['descripcion'] . '</p>
		                        </td>
		                        <td class="subTabla">
		                        	<table border="0" cellspacing="0" cellpadding="0">
		                              	<tr>
		                                	<td colspan="7" style="height:35px;"><p>' . textoPuntuacion($iPuntacion) . '</p></td>
		                              	</tr>
		                              	<tr>
			                                <td rowspan="2">&nbsp;</td>
			                                <td class="cel"><p>1</p></td>
			                                <td class="cel"><p>2</p></td>
			                                <td class="cel"><p>3</p></td>
			                                <td class="cel"><p>4</p></td>
			                                <td class="cel"><p>5</p></td>
			                                <td rowspan="2">&nbsp;</td>
		                              	</tr>
		                              	<tr>';
		                              		if($iPuntacion==1 || $iPuntacion==2){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==3 || $iPuntacion==4){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==5 || $iPuntacion==6){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==7 || $iPuntacion==8){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==9 || $iPuntacion==10){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
		                       $sHtml.='</tr>
		                          		<tr>
		                                	<td colspan="7" style="height:35px;">&nbsp;</td>
		                          		</tr>
		                        	</table>
		                        </td>
		                      </tr>';
					$listaCompetencias->MoveNext();
				}
			}
        $sHtml.= '</table>
      	</div><!--FIN DIV DESARROLLO-->
      </div>
      <!--FIN DIV PAGINA-->
      <hr>
      ';

		$sHtml.= '
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA FACTORES ACTITUDINALES
		$sHtml.= '
			<div class="desarrollo">
				<h2 style="line-height: 3px;">&nbsp;</h2>
				<table class="competencias" border="0" cellspacing="0" cellpadding="0">';

			$cTipoCompetencia = new Tipos_competencias();
			$cTipoCompetencia->setCodIdiomaIso2($idIdioma);
			$cTipoCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cTipoCompetencia->setIdTipoCompetencia("2");
			$cTipoCompetencia = $cTipos_competenciaDB->readEntidad($cTipoCompetencia);
			$sHtml.= '<tr>
                        <td colspan="3" bgcolor="#fff" style="border-bottom:3px solid #ff411d;">
                        <h2 class="subtitulo" style="line-height:24px; color:#555">' . $cTipoCompetencia->getNombre() . '</h2></td>
                      </tr>	';

			$cCompetencia = new Competencias();
			$cCompetencia->setCodIdiomaIso2($idIdioma);
			$cCompetencia->setIdTipoCompetencia("2");
			$cCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencia);
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencia =$listaCompetencias->recordCount();
			if($nCompetencia>0){
				while(!$listaCompetencias->EOF){
					$iPuntacion = $aPuntuaciones[$listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia']];
//					echo "<br />" . $listaCompetencias->fields['nombre'] . ": " . $listaCompetencias->fields['idTipoCompetencia'] . " - " . $listaCompetencias->fields['idCompetencia'];
//					echo "<br />iPuntacion:: " . $iPuntacion;
					$sHtml.= ' <tr class="pLinea">
		                        <td class="tablaTitu"><p>' . $listaCompetencias->fields['nombre'] . '</p></td>
		                        <td class="descripcion" valign="top">
		                        	<p class="intro">' . textoDefinicion($iPuntacion) . '</p>
									<p class="textos">' . $listaCompetencias->fields['descripcion'] . '</p>
		                        </td>
		                        <td class="subTabla">
		                        	<table border="0" cellspacing="0" cellpadding="0">
		                              	<tr>
		                                	<td colspan="7" style="height:35px;"><p>' . textoPuntuacion($iPuntacion) . '</p></td>
		                              	</tr>
		                              	<tr>
			                                <td rowspan="2">&nbsp;</td>
			                                <td class="cel"><p>1</p></td>
			                                <td class="cel"><p>2</p></td>
			                                <td class="cel"><p>3</p></td>
			                                <td class="cel"><p>4</p></td>
			                                <td class="cel"><p>5</p></td>
			                                <td rowspan="2">&nbsp;</td>
		                              	</tr>
		                              	<tr>';
		                              		if($iPuntacion==1 || $iPuntacion==2){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==3 || $iPuntacion==4){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==5 || $iPuntacion==6){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==7 || $iPuntacion==8){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==9 || $iPuntacion==10){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
		                       $sHtml.='</tr>
		                        	</table>
		                        </td>
		                      </tr>';
					$listaCompetencias->MoveNext();
				}
			}
        $sHtml.= '	</table>

                 	<table class="competencias" border="0" cellspacing="0" cellpadding="0">';

			$cTipoCompetencia = new Tipos_competencias();
			$cTipoCompetencia->setCodIdiomaIso2($idIdioma);
			$cTipoCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$cTipoCompetencia->setIdTipoCompetencia("3");
			$cTipoCompetencia = $cTipos_competenciaDB->readEntidad($cTipoCompetencia);
			$sHtml.= '<tr>
                        <td colspan="3" bgcolor="#fff" style="border-bottom:3px solid #ff411d;">
                        <h2 class="subtitulo" style="line-height:24px; color:#555">' . $cTipoCompetencia->getNombre() . '</h2></td>
                      </tr>	';

			$cCompetencia = new Competencias();
			$cCompetencia->setCodIdiomaIso2($idIdioma);
			$cCompetencia->setIdTipoCompetencia("3");
			$cCompetencia->setIdPrueba($_POST['fIdPrueba']);
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencia);
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			$nCompetencia =$listaCompetencias->recordCount();
			if($nCompetencia>0){
				while(!$listaCompetencias->EOF){
					$iPuntacion = $aPuntuaciones[$listaCompetencias->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia']];
//					echo "<br />" . $listaCompetencias->fields['nombre'] . ": " . $listaCompetencias->fields['idTipoCompetencia'] . " - " . $listaCompetencias->fields['idCompetencia'];
//					echo "<br />iPuntacion:: " . $iPuntacion;
					$sHtml.= ' <tr class="pLinea">
		                        <td class="tablaTitu"><p>' . $listaCompetencias->fields['nombre'] . '</p></td>
		                        <td class="descripcion" valign="top">
		                        	<p class="intro">' . textoDefinicion($iPuntacion) . '</p>
									<p class="textos">' . $listaCompetencias->fields['descripcion'] . '</p>
		                        </td>
		                        <td class="subTabla">
		                        	<table border="0" cellspacing="0" cellpadding="0">
		                              	<tr>
		                                	<td colspan="7" style="height:35px;"><p>' . textoPuntuacion($iPuntacion) . '</p></td>
		                              	</tr>
		                              	<tr>
			                                <td rowspan="2">&nbsp;</td>
			                                <td class="cel"><p>1</p></td>
			                                <td class="cel"><p>2</p></td>
			                                <td class="cel"><p>3</p></td>
			                                <td class="cel"><p>4</p></td>
			                                <td class="cel"><p>5</p></td>
			                                <td rowspan="2">&nbsp;</td>
		                              	</tr>
		                              	<tr>';
		                              		if($iPuntacion==1 || $iPuntacion==2){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==3 || $iPuntacion==4){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==5 || $iPuntacion==6){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==7 || $iPuntacion==8){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
										       	if($iPuntacion==9 || $iPuntacion==10){
													$sHtml.='<td class="cel"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
										       	}else{
										       		$sHtml.='<td class="cel">&nbsp;</td>';
										       	}
		                       $sHtml.='</tr>
		                          		<tr>
		                                	<td colspan="7" style="height:35px;">&nbsp;</td>
		                          		</tr>
		                        	</table>
		                        </td>
		                      </tr>';
					$listaCompetencias->MoveNext();
				}
			}
        $sHtml.= '		</table>


                 <table class="indicadores" border="1" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="5" bgcolor="#ffffff">
                        <h2 class="subtitulo" style="line-height:32px; color:#475464">' . constant("STR_PRISMA_INDICADORES_DE_AJUSTE_A_LAS_COMPETENCIAS") . '</h2></td>
                      </tr>
                      <tr style="height:67px; background:#475464; border-bottom:2px solid #c9d2da;">
                        <td class="tablaTitu"><p>' . constant("STR_PRISMA_AREA_CLAVE_DE_MEJORA_BR") . '</p></td>
                        <td class="tablaTitu"><p>' . constant("STR_PRISMA_AREA_DE_POTENCIAL_DESARROLLO") . '</p></td>
                        <td class="tablaTitu"><p>' . constant("STR_PRISMA_AREA_DE_DESARROLLO_2BR") . '</p></td>
                        <td class="tablaTitu"><p>' . constant("STR_PRISMA_AREA_DE_POTENCIAL_FORTALEZA") . '</p></td>
                        <td class="tablaTitu"><p>' . constant("STR_PRISMA_AREA_DE_FORTALEZA_2BR") . '</p></td>
                      </tr>
                      <tr style="height:32px; background:#949398; font-size:20px; font-weight:bold; color:#000;">
                        <td class="tablaTitu"><p>1</p></td>
                        <td class="tablaTitu"><p>2</p></td>
                        <td class="tablaTitu"><p>3</p></td>
                        <td class="tablaTitu"><p>4</p></td>
                        <td class="tablaTitu"><p>5</p></td>
                      </tr>
                      <tr>
                        <td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
                        <td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
                        <td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
                        <td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
                        <td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
                      </tr>
                      <tr style="height:75px; background:#949398;">
                        <td class="descripcion"><p>' . constant("STR_PRISMA_NUNCA_SE_COMPORTA_ASI") . '</p></td>
                        <td class="descripcion"><p>' . constant("STR_PRISMA_CASI_NUNCA_SE_COMPORTA_ASI") . '</p></td>
                        <td class="descripcion"><p>' . constant("STR_PRISMA_A_VECES_SE_COMPORTA_ASI") . '</p></td>
                        <td class="descripcion"><p>' . constant("STR_PRISMA_CASI_SIEMPRE_SE_COMPORTA_ASI") . '</p></td>
                        <td class="descripcion"><p>' . constant("STR_PRISMA_SIEMPRE_SE_COMPORTA_ASI") . '</p></td>
                      </tr>
                 </table>
			</div><!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
    <hr>
    ';
		return $sHtml;
	}

	function informeCompetenciasDefiniciones($sHtmlCab){

		$sHtml= '
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA DEF COMPETENCIAS
		$sHtml.= '
				<div class="desarrollo">
			       	<h2 class="subtitulo">' . constant("STR_PRISMA_DEFINICION_DE_LAS_COMPETENCIAS") . '</h2>
		            <table class="definiciones" border="1" cellspacing="0" cellpadding="0">
		                  <tr>
		                    <td rowspan="4" style="width:89px; background:#D9D9D9; border-bottom:2px solid #555;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/compeGestion.jpg" alt="Competencias para la gestión" title="Competencias para la gestión" /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_DIRIGIR_Y_LIDERAR") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_DIRIGIR_Y_LIDERAR_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_PLANIFICACION") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_PLANIFICACION_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_COMUNICACION_SOCIAL") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_COMUNICACION_SOCIAL_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_PERSUASIVIDAD_E_INFLUENCIA") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_PERSUASIVIDAD_E_INFLUENCIA_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td rowspan="4" style="width:89px; background:#D9D9D9; border-bottom:2px solid #555;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/compeTecnicas.jpg" alt="Competencias tecnicas" title="Competencias tecnicas" /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_DOMINIO_DE_LA_ESPECIALIDAD") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_DOMINIO_DE_LA_ESPECIALIDAD_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_ANALISIS") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_ANALISIS_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_GARANTIA_Y_PROMOCION_DE_LA_CALIDAD_1BR") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_GARANTIA_Y_PROMOCION_DE_LA_CALIDAD_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_ORDEN_Y_SISTEMATICA") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_ORDEN_Y_SISTEMATICA_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td rowspan="4" style="width:89px; background:#D9D9D9; border-bottom:2px solid #555;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/compePromocion.jpg"
		                    alt="Competencias para la promocion del negocio" title="Competencias para la promocion del negocio"  /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_ORIENTACION_AL_LOGRO") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_ORIENTACION_AL_LOGRO_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_INNOVACION_Y_CREATIVIDAD_CAMBIOS_Y_MEJORAS") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_INNOVACION_Y_CREATIVIDAD_CAMBIOS_Y_MEJORAS_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_ORIENTACION_A_LA_ACCION_Y_DECISION_BR") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_ORIENTACION_A_LA_ACCION_Y_DECISION_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_VISION_ESTRATEGICA") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_VISION_ESTRATEGICA_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td rowspan="4" style="width:89px; background:#D9D9D9; border-bottom:2px solid #555;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/compeInterper.jpg"
		                    alt="Competencias interpersonales" title="Competencias interpersonales" /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_HABILIDAD_INTERPERSONAL") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_HABILIDAD_INTERPERSONAL_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_FLEXIBILIDAD_Y_ADAPTACION") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_FLEXIBILIDAD_Y_ADAPTACION_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_RESISTENCIA_A_LA_FATIGA_Y_AL_ESTRES_1BR") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_RESISTENCIA_A_LA_FATIGA_Y_AL_ESTRES_TXT") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_MOTIVACION_PERSONAL_EN_EL_TRABAJO_BR") . '</p></td>
		                    <td class="descripcion"><p class="textos">' . constant("STR_PRISMA_MOTIVACION_PERSONAL_EN_EL_TRABAJO_TXT") . '</p></td>
		                  </tr>
		             </table>
					</div><!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->
        <hr>
        ';
		//FIN DEFINICIONES COMPETENCIAS
		return $sHtml;
	}

	function informeSintesisCompetencias($aPuntuaciones , $sHtmlCab, $idIdioma){

		global $conn;
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
					<h2 class="subtitulo">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_PRISMA") . '</h2>
        			<div class="caja" style="margin-bottom:20px;">
		            	<h3 class="encabezado">' . mb_strtoupper(constant("STR_INTRODUCCION"), 'UTF-8') . '</h3>
		            	<p class="textos">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_PRISMA_INTRO_P1") . '</p>
						<p class="textos">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_PRISMA_INTRO_P2") . '</p>
						<p class="textos">' . constant("STR_PRISMA_SINTESIS_DEL_PERFIL_DE_COMPETENCIAS_PRISMA_INTRO_P3") . '</p>
			          </div><!--FIN DIV CAJA-->
			          <table class="sintesis" border="1" cellspacing="0" cellpadding="0">';
		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTipos_competencias->setIdPrueba($_POST['fIdPrueba']);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);
		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();
		$aPuntuacionesCompetencias = array();
		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$sHtml.='
							<tr>
			                  <td colspan="7" style="background:#fff;"><h2 class="subtitulo">' . $listaTipoCompetencia->fields['nombre'] . '</h2></td>
			                </tr>
			                <tr>
			                  <td colspan="2" style="background:#6a6a6b;">&nbsp;</td>
			                  <td class="cel">' . constant("STR_PRISMA_CLAVE_DE_MEJORA") . '</td>
			                  <td class="cel">' . constant("STR_PRISMA_POTENCIAL_DESARROLLO") . '</td>
			                  <td class="cel">' . constant("STR_PRISMA_AREA_DE_DESARROLLO_BR") . '</td>
			                  <td class="cel">' . constant("STR_PRISMA_POTENCIAL_FORTALEZA") . '</td>
			                  <td class="cel">' . constant("STR_PRISMA_AREA_DE_BR_FORTALEZA") . '</td>
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
			                  <td class="number"><p>'.$iPuntacion.'</p></td>
			                  <td class="tablaTitu">' . mb_strtoupper($listaCompetencias->fields['nombre'], 'UTF-8') . '</td>';
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="celS"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="celS">&nbsp;</td>';
					       	}
		               	$sHtml.='
			               	</tr>';
				       $listaCompetencias->MoveNext();
			 		}
			 	}
			 	$listaTipoCompetencia->MoveNext();
			 }
		 }

         $sHtml.='
						</table>
					</div><!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->
        <hr>
        ';
		return $sHtml;
	}

	function generaPerfilEstilos($aPuntuaciones , $sHtmlCab){


		$DIRECTIVO=round(((0.57*$aPuntuaciones["1-5"])+(0.37*$aPuntuaciones["5-2"])+(0.37*(11-$aPuntuaciones["4-3"]))+(0.22*$aPuntuaciones["1-2"])+(0.22*$aPuntuaciones["2-5"]))/2.12 , 0);
		$DELEGADOR=round(((0.57*$aPuntuaciones["4-3"])+(0.37*(11-$aPuntuaciones["5-2"]))+(0.37*(11-$aPuntuaciones["2-2"]))+(0.37*(11-$aPuntuaciones["1-2"]))+(0.22*(11-$aPuntuaciones["1-4"])))/1.90 , 0);
		$NEGOCIADOR=round(((0.57*$aPuntuaciones["5-1"])+(0.57*$aPuntuaciones["3-3"])+(0.37*$aPuntuaciones["4-3"])+(0.37*$aPuntuaciones["6-3"])+(0.37*$aPuntuaciones["8-1"])+(0.22*$aPuntuaciones["1-4"])+(0.22*(11-$aPuntuaciones["2-5"])))/2.69 , 0);
		$CONSULTIVO=round(((0.57*$aPuntuaciones["5-2"])+(0.37*($aPuntuaciones["5-3"]))+(0.37*$aPuntuaciones["3-3"])+(0.37*$aPuntuaciones["1-4"])+(0.37*$aPuntuaciones["4-3"])+(0.22*(11-$aPuntuaciones["2-5"])))/2.27 , 0);
		$DEMOCRATICO=round(((0.57*$aPuntuaciones["4-3"])+(0.57*$aPuntuaciones["8-1"])+(0.57*$aPuntuaciones["2-5"])+(0.37*$aPuntuaciones["5-1"])+(0.22*$aPuntuaciones["6-3"]))/2.30 , 0);
		$AUTOSUFICIENTE=round(((0.57*$aPuntuaciones["5-3"])+(0.57*(11-$aPuntuaciones["4-3"]))+(0.37*$aPuntuaciones["7-2"])+(0.37*$aPuntuaciones["1-3"])+(0.22*$aPuntuaciones["1-5"])+(0.22*$aPuntuaciones["1-2"]))/2.32 , 0);
		$SUMISO=round(((0.57*(11-$aPuntuaciones["5-2"]))+(0.57*(11-$aPuntuaciones["5-3"]))+(0.57*$aPuntuaciones["4-2"])+(0.37*$aPuntuaciones["9-2"])+(0.37*$aPuntuaciones["2-5"])+(0.57*(11-$aPuntuaciones["1-4"]))+(0.57*(11-$aPuntuaciones["1-3"])))/3.59 , 0);
		$COMPLICE=round(((0.57*$aPuntuaciones["5-3"])+(0.57*$aPuntuaciones["5-1"])+(0.57*$aPuntuaciones["5-4"])+(0.57*$aPuntuaciones["3-3"])+(0.37*$aPuntuaciones["2-3"])+(0.57*$aPuntuaciones["1-4"]))/3.22 , 0);
		$INFORMADOR=round(((0.57*$aPuntuaciones["6-2"])+(0.57*(11-$aPuntuaciones["5-2"]))+(0.37*$aPuntuaciones["5-3"])+(0.37*(11-$aPuntuaciones["1-4"]))+(0.22*$aPuntuaciones["9-2"])+(0.22*$aPuntuaciones["5-4"]))/2.32 , 0);
		$PARTICIPATIVO=round(((0.57*$aPuntuaciones["7-1"])+(0.57*$aPuntuaciones["5-2"])+(0.37*$aPuntuaciones["5-4"])+(0.37*$aPuntuaciones["5-1"])+(0.22*(11-$aPuntuaciones["2-4"]))+(0.22*$aPuntuaciones["1-4"]))/2.32 , 0);
		$COORDINADOR=round(((0.57*$aPuntuaciones["3-3"])+(0.37*$aPuntuaciones["5-1"])+(0.57*$aPuntuaciones["5-2"])+(0.37*(11-$aPuntuaciones["9-1"]))+(0.22*$aPuntuaciones["6-3"]))/2.1 , 0);
		$CONSTRUCTOR_EQUIPOS=round(((0.57*$aPuntuaciones["3-2"])+(0.37*$aPuntuaciones["4-3"])+(0.22*$aPuntuaciones["3-1"])+(0.22*$aPuntuaciones["4-1"])+(0.22*$aPuntuaciones["2-6"])+(0.22*$aPuntuaciones["2-5"]))/1.82 , 0);
		$INVESTIGADOR_RECURSOS=round(((0.57*$aPuntuaciones["4-3"])+(0.37*$aPuntuaciones["5-1"])+(0.37*$aPuntuaciones["3-3"])+(0.37*$aPuntuaciones["2-6"])+(0.22*$aPuntuaciones["4-1"]))/1.9 , 0);
		$IMPLULSOR_ENERGETICO=round(((0.57*$aPuntuaciones["1-5"])+(0.57*$aPuntuaciones["5-2"])+(0.37*$aPuntuaciones["1-2"])+(0.22*$aPuntuaciones["1-3"])+(0.37*(11-$aPuntuaciones["2-1"])))/2.1 , 0);
		$APLICADOR=round(((0.57*$aPuntuaciones["5-2"])+(0.37*$aPuntuaciones["1-5"])+(0.37*$aPuntuaciones["9-1"])+(0.37*$aPuntuaciones["1-2"])+(0.22*$aPuntuaciones["1-4"]))/1.9 , 0);
		$ANALIZADOR_EVALUADOR=round(((0.57*$aPuntuaciones["6-2"])+(0.37*$aPuntuaciones["9-1"])+(0.37*$aPuntuaciones["6-1"])+(0.57*$aPuntuaciones["5-3"])+(0.22*(11-$aPuntuaciones["2-2"])))/2.1 , 0);
		$CREATIVO=round(((0.57*$aPuntuaciones["7-2"])+(0.37*$aPuntuaciones["7-1"])+(0.37*$aPuntuaciones["5-3"])+(0.37*$aPuntuaciones["8-3"]))/1.68 , 0);
		$PERFECCIONISTA=round(((0.57*$aPuntuaciones["9-1"])+(0.57*$aPuntuaciones["6-2"])+(0.37*$aPuntuaciones["1-2"])+(0.37*(11-$aPuntuaciones["2-2"]))+(0.37*(11-$aPuntuaciones["8-1"])))/2.25 , 0);

		$sHtml= '
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA PERFIL DE ESTILOS

		$sHtml.='
				<div class="desarrollo">
			       	<h2 class="subtitulo">' . constant("STR_PRISMA_PERFIL_DE_ESTILOS") . '</h2>
			        <div class="caja" style="margin-bottom:295px;">
		        		<p class="textos">' . constant("STR_PRISMA_PERFIL_DE_ESTILOS_TXT") . '</p>
		            	<h3 class="encabezado">' . constant("STR_PRISMA_ESTILOS_DE_LIDERAZGO") . '</h3>
		                <p class="textos">' . constant("STR_PRISMA_ESTILOS_DE_LIDERAZGO_P1") . '</p>
		                <p class="textos">' . constant("STR_PRISMA_ESTILOS_DE_LIDERAZGO_P2") . '</p>
						<h3 class="encabezado">' . constant("STR_PRISMA_ESTILOS_DE_COLABORACION") . '</h3>
		                <p class="textos">' . constant("STR_PRISMA_ESTILOS_DE_COLABORACION_P1") . '</p>
						<p class="textos">' . constant("STR_PRISMA_ESTILOS_DE_COLABORACION_P2") . '</p>
						<h3 class="encabezado">' . constant("STR_PRISMA_ESTILOS_DE_COMPORTAMIENTO_EN_EQUIPO") . '</h3>
		                <p class="textos">' . constant("STR_PRISMA_ESTILOS_DE_COMPORTAMIENTO_EN_EQUIPO_P1") . '</p>
						<p class="textos">' . constant("STR_PRISMA_ESTILOS_DE_COMPORTAMIENTO_EN_EQUIPO_P2") . '</p>
		          	</div><!--FIN DIV CAJA-->
      			</div><!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
      <hr>
      ';


		$sHtml.= '
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA PERFIL DE ESTILOS

		$sHtml.='
				<div class="desarrollo">
		        	<table id="liderazgo" class="liderazgo" border="1" cellspacing="0" cellpadding="0">
						<tr style="height:36px; background:#555; border:2px solid #dee4e6; border-width:2px 0 2px 0;">
		                    <td colspan="3" align="center"><h2>' . constant("STR_PRISMA_LIDERAZGO") . '</h2></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_CASI_NUNCA_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_RARAS_VECES_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_PUEDE_SER") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_ALGUNAS_VECES_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_CASI_SIEMPRE_SERA") . '</p></td>
						</tr>
                  		<tr>
		                    <td rowspan="5" style="background:#D9D9D9;width:32px;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/liderazgo1.jpg" alt="Estilos" title="Estilos" /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_DIRECTIVO") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_DIRECTIVO_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_DIRECTIVO_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_DIRECTIVO_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $DIRECTIVO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
						</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_DELEGADOR") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_DELEGADOR_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_DELEGADOR_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_DELEGADOR_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $DELEGADOR;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
						</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_NEGOCIADOR") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_NEGOCIADOR_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_NEGOCIADOR_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_NEGOCIADOR_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $NEGOCIADOR;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
	                  <tr>
	                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_CONSULTIVO") . '</p></td>
	                    <td class="descripcion">
	                    	<ul>
	                        	<li>' . constant("STR_PRISMA_CONSULTIVO_P1") . '</li>
	                            <li>' . constant("STR_PRISMA_CONSULTIVO_P2") . '</li>
	                        </ul>
	                    </td>';
							$iPuntacion = $CONSULTIVO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_DEMOCRATICO") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_DEMOCRATICO_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_DEMOCRATICO_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_DEMOCRATICO_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $DEMOCRATICO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr style="height:36px; background:#555; border:2px solid #dee4e6; border-width:2px 0 2px 0;">
		                    <td colspan="3" align="center"><h2>' . constant("STR_PRISMA_COLABORACION") . '</h2></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_CASI_NUNCA_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_RARAS_VECES_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_PUEDE_SER") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_ALGUNAS_VECES_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_CASI_SIEMPRE_SERA") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td rowspan="5" style="background:#D9D9D9;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/liderazgo1.jpg" alt="Estilos" title="Estilos" /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_AUTOSUFICIENTE") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_AUTOSUFICIENTE_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_AUTOSUFICIENTE_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_AUTOSUFICIENTE_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $AUTOSUFICIENTE;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
						</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_SUMISO") . 'Sumiso</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_SUMISO_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_SUMISO_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_SUMISO_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $SUMISO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_COMPLICE") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_COMPLICE_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_COMPLICE_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_COMPLICE_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $COMPLICE;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_INFORMADOR") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_INFORMADOR_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_INFORMADOR_P2") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $INFORMADOR;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_PARTICIPATIVO") . '</p></td>
		                    <td class="descripcion">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_PARTICIPATIVO_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_PARTICIPATIVO_P2") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $PARTICIPATIVO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr style="height:36px; background:#555; border:2px solid #dee4e6; border-width:2px 0 2px 0;">
		                    <td colspan="3" align="center"><h2>' . constant("STR_PRISMA_COMPORTAMIENTO_EN_EQUIPO") . '</h2></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_CASI_NUNCA_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_RARAS_VECES_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_PUEDE_SER") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_ALGUNAS_VECES_SERA") . '</p></td>
		                    <td class="cel" align="center"><p>' . constant("STR_PRISMA_CASI_SIEMPRE_SERA") . '</p></td>
		                  </tr>
		                  <tr>
		                    <td rowspan="8" style="background:#D9D9D9;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/liderazgo1.jpg" alt="Liderazgo" title="Liderazgo" /></td>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_COORDINADOR") . '</p></td>
		                    <td class="descripcion" valign="top">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_COORDINADOR_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_COORDINADOR_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_COORDINADOR_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $COORDINADOR;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_CONSTRUCTOR_DE_EQUIPOS") . '</p></td>
		                    <td class="descripcion" valign="top">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_CONSTRUCTOR_DE_EQUIPOS_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_CONSTRUCTOR_DE_EQUIPOS_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_CONSTRUCTOR_DE_EQUIPOS_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $CONSTRUCTOR_EQUIPOS;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_INVESTIGADOR_DE_RECURSOS") . '</p></td>
		                    <td class="descripcion" valign="top">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_INVESTIGADOR_DE_RECURSOS_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_INVESTIGADOR_DE_RECURSOS_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_INVESTIGADOR_DE_RECURSOS_P3") . '</li>
		                            <li>' . constant("STR_PRISMA_INVESTIGADOR_DE_RECURSOS_P4") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $INVESTIGADOR_RECURSOS;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='
					</tr>
		                  <tr>
		                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_IMPULSOR_ENERGETICO") . '</p></td>
		                    <td class="descripcion" valign="top">
		                    	<ul>
		                        	<li>' . constant("STR_PRISMA_IMPULSOR_ENERGETICO_P1") . '</li>
		                            <li>' . constant("STR_PRISMA_IMPULSOR_ENERGETICO_P2") . '</li>
		                            <li>' . constant("STR_PRISMA_IMPULSOR_ENERGETICO_P3") . '</li>
		                        </ul>
		                    </td>';
							$iPuntacion = $IMPLULSOR_ENERGETICO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='</tr>
                  <tr>
                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_APLICADOR") . '</p></td>
                    <td class="descripcion" valign="top">
                    	<ul>
                        	<li>' . constant("STR_PRISMA_APLICADOR_P1") . '</li>
                            <li>' . constant("STR_PRISMA_APLICADOR_P2") . '</li>
                            <li>' . constant("STR_PRISMA_APLICADOR_P3") . '</li>
                        </ul>
                    </td>';
							$iPuntacion = $APLICADOR;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='</tr>
                  <tr>
                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_ANALIZADOR_EVALUADOR") . '</p></td>
                    <td class="descripcion" valign="top">
                    	<ul>
                        	<li>' . constant("STR_PRISMA_ANALIZADOR_EVALUADOR_P1") . '</li>
                            <li>' . constant("STR_PRISMA_ANALIZADOR_EVALUADOR_P2") . '</li>
                            <li>' . constant("STR_PRISMA_ANALIZADOR_EVALUADOR_P3") . '</li>
                        </ul>
                    </td>';
							$iPuntacion = $ANALIZADOR_EVALUADOR;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='</tr>
                  <tr>
                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_CREATIVO") . '</p></td>
                    <td class="descripcion" valign="top">
                    	<ul>
                        	<li>' . constant("STR_PRISMA_CREATIVO_P1") . '</li>
                            <li>' . constant("STR_PRISMA_CREATIVO_P2") . '</li>
                            <li>' . constant("STR_PRISMA_CREATIVO_P3") . '</li>
                        </ul>
                    </td>';
							$iPuntacion = $CREATIVO;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='</tr>
                  <tr>
                    <td class="tablaTitu"><p>' . constant("STR_PRISMA_PERFECCIONISTA") . '</p></td>
                    <td class="descripcion" valign="top">
                    	<ul>
                        	<li>' . constant("STR_PRISMA_PERFECCIONISTA_P1") . '</li>
                            <li>' . constant("STR_PRISMA_PERFECCIONISTA_P2") . '</li>
                            <li>' . constant("STR_PRISMA_PERFECCIONISTA_P3") . '</li>
                        </ul>
                    </td>';
							$iPuntacion = $PERFECCIONISTA;
			 				if($iPuntacion==1 || $iPuntacion==2){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==3 || $iPuntacion==4){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==5 || $iPuntacion==6){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==7 || $iPuntacion==8){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}
					       	if($iPuntacion==9 || $iPuntacion==10){
								$sHtml.='<td class="simbol"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
					       	}else{
					       		$sHtml.='<td class="simbol">&nbsp;</td>';
					       	}

			$sHtml.='</tr>
            	</table>
			</div><!--FIN DIV DESARROLLO-->
      	</div>
      	<!--FIN DIV PAGINA-->
        <hr>
        ';
			return $sHtml;
	}

	function generaPerfilCompetenciaEmocional($aPuntuaciones, $sHtmlCab,$idPrueba, $idBaremo,$idIdioma, $idProceso, $idCandidato, $idEmpresa){

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $cRespuestas_pruebas_itemsBD;
		global $aInversos;
		global $aPuntuaciones;
		global $cBaremos_resultadoDB;

	// ENERGÍAS Y MOTIVACIONES
		$aImagenesBloques = array("energias.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
		$i=0;

		$sHtml= '
			<div class="pagina" >'. $sHtmlCab;

			// PÁGINA PERFIL COMPETENCIA EMOCIONAL

			$sHtml.='
				<div class="desarrollo">
		       		<h2 class="subtitulo">' . constant("STR_PRISMA_PERFIL_COMPETENCIA_EMOCIONAL") . '</h2>
		        	<div class="caja" style="margin-bottom:140px;">
		        		<p class="textos">' . constant("STR_PRISMA_PERFIL_COMPETENCIA_EMOCIONAL_P1") . '</p>
		            	<p class="textos">' . constant("STR_PRISMA_PERFIL_COMPETENCIA_EMOCIONAL_P2") . '</p>
		                <p class="textos">' . constant("STR_PRISMA_PERFIL_COMPETENCIA_EMOCIONAL_P3") . '</p>
		                <p class="textos">' . constant("STR_PRISMA_PERFIL_COMPETENCIA_EMOCIONAL_P4") . '</p>
		          	</div><!--FIN DIV CAJA-->
		          	<table class="estadistica" width="650" border="0" cellspacing="0" cellpadding="0">
		              <tr>
		                <td style="width:215px;">&nbsp;</td>
		                <td colspan="10" class="bg_estadistica">&nbsp;</td>
		              </tr>
		              <tr>
		                <td><p>' . constant("STR_PRISMA_TENSION_INTERNA") . '</p></td>
		                <td colspan="10" class="bg_estadistica"><div class="tension" style="width:'.$aPuntuaciones['2-1']*10 .'%"></div></td>
		              </tr>
		              <tr>
		                <td>&nbsp;</td>
		                <td colspan="10" class="bg_estadistica">&nbsp;</td>
		              </tr>
		              <tr>
		                <td><p>' . constant("STR_PRISMA_CONTROL_EXTERNO") . '</p></td>
		                <td colspan="10" class="bg_estadistica"><div class="control" style="width:'.$aPuntuaciones['2-4']*10 .'%"></div></td>
		              </tr>
		              <tr>
		                <td>&nbsp;</td>
		                <td colspan="10" class="bg_estadistica">&nbsp;</td>
		              </tr>
		              <tr>
		                <td><p>' . constant("STR_PRISMA_AUTODOMINIO") . '</p></td>
		                <td colspan="10" class="bg_estadistica"><div class="barra autodominio" style="width:'.$aPuntuaciones['2-2']*10 .'%"></div></td>
		              </tr>
		              <tr>
		                <td>&nbsp;</td>
		                <td colspan="10" class="bg_estadistica">&nbsp;</td>
		              </tr>
		              <tr>
		                <td><p>' . constant("STR_PRISMA_ACTITUD_POSITIVA") . '</p></td>
		                <td colspan="10" class="bg_estadistica"><div class="barra actitud" style="width:'.$aPuntuaciones['2-6']*10 .'%"></div></td>
		              </tr>
		              <tr>
		                <td>&nbsp;</td>
		                <td colspan="10" class="bg_estadistica">&nbsp;</td>
		              </tr>
		              <tr>
		                <td><p>' . constant("STR_PRISMA_RESISTENCIA_EMOCIONAL") . '</p></td>
		                <td colspan="10" class="bg_estadistica"><div class="barra resistencia" style="width:'.$aPuntuaciones['2-3']*10 .'%"></div></td>
		              </tr>
		              <tr>
		                <td>&nbsp;</td>
		                <td colspan="10" class="bg_estadistica">&nbsp;</td>
		              </tr>
		              <tr>
		                <td><p>' . constant("STR_PRISMA_CONFIANZA_EN_OTROS") . '</p></td>
		                <td colspan="10" class="bg_estadistica"><div class="barra confianza" style="width:'.$aPuntuaciones['2-5']*10 .'%"></div></td>
		              </tr>
		              <tr>
		                <td>&nbsp;</td>
		                <td colspan="10" class="bg_estadistica" style="border-bottom:3px solid #4e7187;">&nbsp;</td>
		              </tr>
		              <tr>
		              	<td style="text-align:right;"><p>0</p></td>
		              	<td >
		              		<table width="398" cellspacing=0 cellpadding=0 border="0">
		              			<tr>
			                		<td class="number"><p>1</p></td>
					                <td class="number"><p>2</p></td>
					                <td class="number"><p>3</p></td>
					                <td class="number"><p>4</p></td>
					                <td class="number"><p>5</p></td>
					                <td class="number"><p>6</p></td>
					                <td class="number"><p>7</p></td>
					                <td class="number"><p>8</p></td>
					                <td class="number"><p>9</p></td>
					                <td class="number"><p>10</p></td>
					             </tr>
					        </table>
					      </td>
		              </tr>
		          </table>
		          <div style="margin-bottom:140px;"></div>
			</div><!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
    <hr>
    ';

		return $sHtml;
	}

	function generaPerfilesEstresores($aPuntuaciones, $sHtmlCab, $idPrueba, $idBaremo, $idIdioma, $idProceso, $idCandidato, $idEmpresa){

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $cRespuestas_pruebas_itemsBD;
		global $aInversos;
		global $aPuntuaciones;
		global $cBaremos_resultadoDB;

	// ENERGÍAS Y MOTIVACIONES
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aImagenesBloques = array("energias.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
		$i=0;
	/*
	 * PÁGINA PERFIL GENERAL PERSONALIDAD LABORAL
	 * E1  --> 1-1
	 * E2  --> 1-2
	 * E3  --> 1-3
	 * E4  --> 1-4
	 * E5  --> 1-5
	 * E6  --> 2-1
	 * E7  --> 2-2
	 * E8  --> 2-3
	 * E9  --> 2-4
	 * E10 --> 2-5
	 * E11 --> 2-6
	 * S1  --> 3-1
	 * S2  --> 3-2
	 * S3  --> 3-3
	 * S4  --> 4-1
	 * S5  --> 4-2
	 * S6  --> 4-3
	 * S7  --> 5-1
	 * S8  --> 5-2
	 * S9  --> 5-3
	 * S10 --> 5-4
	 * M1  --> 6-1
	 * M2  --> 6-2
	 * M3  --> 6-3
	 * M4  --> 7-1
	 * M5  --> 7-2
	 * M6  --> 7-3
	 * M7  --> 8-1
	 * M8  --> 8-2
	 * M9  --> 8-3
	 * M10 --> 9-1
	 * M11 --> 9-2
	 */

		$sHtml= '
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA PERFIL DE ESTRESORES POTENCIALES
		//M8  --> 8-2 ->directa
		$sHtml.='
				<div class="desarrollo">
			       	<h2 class="subtitulo">' . constant("STR_PRISMA_PERFIL_DE_ESTRESORES_POTENCIALES") . '</h2>
					<div class="caja" style="margin-bottom:8px;">
			        		<p class="textos">' . constant("STR_PRISMA_PERFIL_DE_ESTRESORES_POTENCIALES_P1") . '</p>
			                <p class="textos">' . constant("STR_PRISMA_PERFIL_DE_ESTRESORES_POTENCIALES_P2") . '</p>
			                <p class="textos">' . constant("STR_PRISMA_PERFIL_DE_ESTRESORES_POTENCIALES_P3") . '</p>
			          </div><!--FIN DIV CAJA-->
		          <table class="estresores" border="0" cellspacing="0" cellpadding="0">
		              <tr>
		                <td colspan="8" style="background:#fff; height:22px;" align="center"><h2>' . constant("STR_PRISMA_FACTORES_ORGANIZATIVOS") . '</h2></td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;" align="center">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="3" align="center">&nbsp;</td>
		                <td class="cel" align="center"><p>1-2<br />' . constant("STR_PRISMA_BAJO") . '</p></td>
		                <td class="cel" align="center"><p>3-4<br />' . constant("STR_PRISMA_ME_BA") . '</p></td>
		                <td class="cel" align="center"><p>5-6<br />' . constant("STR_PRISMA_MEDIO") . '</p></td>
		                <td class="cel" align="center"><p>7-8<br />' . constant("STR_PRISMA_ME_AL") . '</p></td>
		                <td class="cel" align="center"><p>9-10<br />' . constant("STR_PRISMA_ALTO") . '</p></td>
		              </tr>
		              <tr>
		                <td class="number" align="center"><p>' . $iPuntuacion = $aPuntuaciones["8-2"] . '</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_TRABAJO_RUTINARIO_Y_REPETITIVO") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				     //M7  --> 8-1 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["8-1"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_CAMBIOS_FRECUENTES") . '</p></td>
		                <td align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //M5  --> 7-2 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["7-2"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_ESTRUCTURAS_BUROCRATICAS") . '</p></td>
		                <td align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //M6  --> 7-3 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["7-3"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_ENTORNOS_REACTIVOS") . '</p></td>
		                <td align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S3  --> 3-3 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#fff; height:22px;" align="center"><h2>' . constant("STR_PRISMA_FACTORES_RELACIONALES") . '</h2></td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;" align="center">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="3" align="center">&nbsp;</td>
		                <td class="cel" align="center"><p>1-2<br />' . constant("STR_PRISMA_BAJO") . '</p></td>
		                <td class="cel" align="center"><p>3-4<br />' . constant("STR_PRISMA_ME_BA") . '</p></td>
		                <td class="cel" align="center"><p>5-6<br />' . constant("STR_PRISMA_MEDIO") . '</p></td>
		                <td class="cel" align="center"><p>7-8<br />' . constant("STR_PRISMA_ME_AL") . '</p></td>
		                <td class="cel" align="center"><p>9-10<br />' . constant("STR_PRISMA_ALTO") . '</p></td>
		              </tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["3-3"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_HABLAR_EN_PUBLICO") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S7  --> 5-1 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["5-1"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_VENDER_NEGOCIAR") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
					//S4  --> 4-1 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["4-1"].'</p></td>
		                <td class="tablaTitu"align="center"><p>' . constant("STR_PRISMA_DAR_MALAS_NOTICIAS") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S2  --> 3-2 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["3-2"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_CONFLICTOS_INTERPERSONALES") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S8  --> 5-2 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;"align="center">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#fff; height:22px;" align="center"><h2>' . constant("STR_PRISMA_FACTORES_MOTIVACIONALES") . '</h2></td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;" align="center">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="3" align="center">&nbsp;</td>
		                <td class="cel" align="center"><p>1-2<br />' . constant("STR_PRISMA_BAJO") . '</p></td>
		                <td class="cel" align="center"><p>3-4<br />' . constant("STR_PRISMA_ME_BA") . '</p></td>
		                <td class="cel" align="center"><p>5-6<br />' . constant("STR_PRISMA_MEDIO") . '</p></td>
		                <td class="cel" align="center"><p>7-8<br />' . constant("STR_PRISMA_ME_AL") . '</p></td>
		                <td class="cel" align="center"><p>9-10<br />' . constant("STR_PRISMA_ALTO") . '</p></td>
		              </tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["5-2"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_PODER") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol"  align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol"  align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol"  align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol"  align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol"  align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol"  align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol"  align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol"  align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol"  align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol"  align="center">&nbsp;</td>';
							       	}
				    //S6  --> 4-3 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["4-3"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_APOYO_SOCIAL") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S9  --> 5-3 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["5-3"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_AUTONOMIA") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //E3  --> 1-3 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["1-3"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_PROGRESO_PROFESIONAL") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //M4  --> 7-1 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["7-1"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_ESCASOS_RETOS_INTELECTUALES") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S5  --> 4-2 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["4-2"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_RECONOCIMIENTO") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //E5  --> 1-5 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;" align="center">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#fff; height:22px;" align="center"><h2>' . constant("STR_PRISMA_FACTORES_DEL_PUESTO_DE_TRABAJO") . '</h2></td>
		              </tr>
		              <tr>
		                <td colspan="8" style="background:#ff411d; height:6px;" align="center">&nbsp;</td>
		              </tr>
		              <tr>
		                <td colspan="3" align="center">&nbsp;</td>
		                <td class="cel" align="center"><p>1-2<br />' . constant("STR_PRISMA_BAJO") . '</p></td>
		                <td class="cel" align="center"><p>3-4<br />' . constant("STR_PRISMA_ME_BA") . '</p></td>
		                <td class="cel" align="center"><p>5-6<br />' . constant("STR_PRISMA_MEDIO") . '</p></td>
		                <td class="cel" align="center"><p>7-8<br />' . constant("STR_PRISMA_ME_AL") . '</p></td>
		                <td class="cel" align="center"><p>9-10<br />' . constant("STR_PRISMA_ALTO") . '</p></td>
		              </tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["1-5"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_TOMA_DE_DECISIONES_RAPIDAS_DIFICILES") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //M1  --> 6-1 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["6-1"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_SOLUCION_DE_PROBLEMAS_COMPLEJOS") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //E2  --> 1-2 -->directa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = $aPuntuaciones["1-2"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FUERTES_RESTRICCIONES_DE_TIEMPO") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //E4  --> 1-4 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["1-4"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_AMBIENTE_COMPETITIVO") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
				    //S8  --> 5-2 -->inversa
					$sHtml.='</tr>
		              <tr>
		                <td class="number" align="center"><p>'.$iPuntuacion = 11-$aPuntuaciones["5-2"].'</p></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_DIRIGIR_A_OTROS") . '</p></td>
		                <td style="width:4px;" align="center">&nbsp;</td>';
									if($iPuntuacion==1 || $iPuntuacion==2){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==3 || $iPuntuacion==4){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==5 || $iPuntuacion==6){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==7 || $iPuntuacion==8){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}
							       	if($iPuntuacion==9 || $iPuntuacion==10){
										$sHtml.='<td class="simbol" align="center"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>';
							       	}else{
							       		$sHtml.='<td class="simbol" align="center">&nbsp;</td>';
							       	}

					$sHtml.='</tr>
		         </table>
			</div><!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
    <hr>
    ';
		$sHtml.= '
		<div class="pagina">'. $sHtmlCab;

		// PÁGINA DEFINICIÓN DE LOS FACTORES ESTRESORES

		$sHtml.='
			<div class="desarrollo">
		       	<h2 class="subtitulo" style="line-height:20px;">' . constant("STR_PRISMA_DEFINICION_DE_LOS_FACTORES_ESTRESORES") . '</h2>
		        <table class="definicionesEstresores" border="1" cellspacing="0" cellpadding="0">
		              <tr>
		                <td rowspan="4" style="width:55px; border-bottom:2px solid #b4dbdb;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/estresores1.jpg" alt="Factores organizativos"
		                title="Factores organizativos" /></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_TRABAJO_RUTINARIO_Y_REPETITIVO") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_TRABAJO_RUTINARIO_Y_REPETITIVO_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_CAMBIOS_FRECUENTES") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_CAMBIOS_FRECUENTES_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_ESTRUCTURAS_BUROCRATICAS") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_ESTRUCTURAS_BUROCRATICAS_DEF") . '</p>
		                </td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_ENTORNOS_REACTIVOS") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_ENTORNOS_REACTIVOS_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td rowspan="4" style="width:55px; border-bottom:2px solid #b4dbdb;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/estresores2.jpg" alt="Factores relacionales"
		                title="Factores relacionales" /></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_HABLAR_EN_PUBLICO") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_HABLAR_EN_PUBLICO_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_VENDER_NEGOCIAR") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_VENDER_NEGOCIAR_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_DAR_MALAS_NOTICIAS") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_DAR_MALAS_NOTICIAS_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_CONFLICTOS_INTERPERSONALES") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_CONFLICTOS_INTERPERSONALES_DEF") . '</p>
		                </td>
		              </tr>
		              <tr>
		                <td rowspan="6" style="width:55px; border-bottom:2px solid #b4dbdb;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/estresores3.jpg" alt="Factores motivacionales"
		                 title="Factores motivacionales" /></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_PODER") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_FALTA_DE_PODER_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_APOYO_SOCIAL") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_FALTA_DE_APOYO_SOCIAL_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_AUTONOMIA") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_FALTA_DE_AUTONOMIA_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_PROGRESO_PROFESIONAL_BR") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_FALTA_DE_PROGRESO_PROFESIONAL_BR_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_ESCASOS_RETOS_INTELECTUALES") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_ESCASOS_RETOS_INTELECTUALES_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FALTA_DE_RECONOCIMIENTO") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_FALTA_DE_RECONOCIMIENTO_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td rowspan="5" style="width:55px; border-bottom:2px solid #b4dbdb;"><img src="'.constant("DIR_WS_GESTOR").'graf/prisma/' . $_POST['fCodIdiomaIso2']. '/estresores4.jpg" alt="" title="" /></td>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_TOMA_DE_DECISIONES_RAPIDAS_DIFICILES_BR") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_TOMA_DE_DECISIONES_RAPIDAS_DIFICILES_BR_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_SOLUCION_DE_PROBLEMAS_COMPLEJOS_BR") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_SOLUCION_DE_PROBLEMAS_COMPLEJOS_BR_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_FUERTES_RESTRICCIONES_DE_TIEMPO_BR") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_FUERTES_RESTRICCIONES_DE_TIEMPO_BR_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_AMBIENTE_COMPETITIVO") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_AMBIENTE_COMPETITIVO_DEF") . '</p></td>
		              </tr>
		              <tr>
		                <td class="tablaTitu" align="center"><p>' . constant("STR_PRISMA_DIRIGIR_A_OTROS") . '</p></td>
		                <td class="descripcion"><p>' . constant("STR_PRISMA_DIRIGIR_A_OTROS_DEF") . '</p></td>
		              </tr>
		        </table>
			</div><!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
    <hr>
    ';

		return $sHtml;
	}

function generaNarrativoPrisma($aPuntuaciones,$sHtmlCab,$idIdioma,$cCandidato)
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
		//PÁGINA INTRODUCCIÓN, 1
		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		$sHtml.= '
        		<h2 class="subtitulo_prisma" style="color:#000000">' . constant("STR_INTRODUCCION_CAPS") . '</h2>
            	<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP1"))  . '</p><br />
				<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP2")) . '</p><br />
				<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP3")) . '</p><br />
                <p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP4")) . '</p><br />
				<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getApellido1() , constant("PRISMA_NARRATIVO_INTP5")) . '</p>
        </div>
        <!--FIN DIV PAGINA-->
        <hr>
        ';
		$sHtml.=	constant("_NEWPAGE");

		//FIN PÁGINA INTRODUCCIÓN, 1
		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		// PÁGINA PERFIL PERSONALIDAD LABORAL

		// ENERGÍAS Y MOTIVACIONES
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($idIdioma);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);

		$listaBloques = $conn->Execute($sqlBloques);

		$iPosiImg=0;
		$iPGlobal = 0;
		$nBloques= $listaBloques->recordCount();

		$listaBloques->MoveFirst();

		$sHtml.= '
		        	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
		            ';


		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(1);
	 	$cEscalas->setIdBloqueHast(1);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//		echo $sqlEscalas;
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

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
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}
	 	$listaBloques->MoveNext();
		$sHtml.='
		<br />
      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
			';


		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(2);
	 	$cEscalas->setIdBloqueHast(2);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}
		$sHtml.='';

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
    <hr>
    ';
		$sHtml.=	constant("_NEWPAGE");
 	$sHtml.='
 	<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL


		$nBloques= $listaBloques->recordCount();

		$listaBloques->MoveNext();

		$sHtml.= '
				<h2 class="subtitulo_prisma" style="color:#000000;">'.constant("STR_ESTILO_ORENTACION_NARR_PRISMA").'</h2>
		        	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';

		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(3);
	 	$cEscalas->setIdBloqueHast(3);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}
	 	$listaBloques->MoveNext();
		$sHtml.='

      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
			';



		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(4);
	 	$cEscalas->setIdBloqueHast(4);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}


		$listaBloques->MoveNext();
		$sHtml.='

      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
			';



		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(5);
	 	$cEscalas->setIdBloqueHast(5);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}
		$sHtml.='';

 $sHtml.='
    </div>
    <!--FIN DIV PAGINA-->
    <hr>
    ';
		$sHtml.=	constant("_NEWPAGE");
 $sHtml.='
 	<div class="pagina">'. $sHtmlCab;
		//$sHtml.='<div class="pagina">';
		// PÁGINA PERFIL PERSONALIDAD LABORAL


		$nBloques= $listaBloques->recordCount();

		$listaBloques->MoveNext();

		$sHtml.= '
						<h2 class="subtitulo_prisma" style="color:#000000;">'.constant("STR_ESTILO_PENSAMIENTO_NARR_PRISMA").'</h2>
		        	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>
		           ';

		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(6);
	 	$cEscalas->setIdBloqueHast(6);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}
	 	$listaBloques->MoveNext();
		$sHtml.='
      	<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';



		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(7);
	 	$cEscalas->setIdBloqueHast(7);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . "(" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . "(" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}


		$listaBloques->MoveNext();
		$sHtml.='<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';



		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(8);
	 	$cEscalas->setIdBloqueHast(8);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}

	$sHtml.='
	</div>
	<!--FIN DIV PAGINA-->
  <hr>
  ';
		$sHtml.=	constant("_NEWPAGE");
  $sHtml.='
	<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL
$listaBloques->MoveNext();

		$sHtml.= '<h2 class="subtitulo_prisma" style="color:#000000;">' . $listaBloques->fields['nombre'] . '</h2>';

		$cEscalas = new Escalas();
	 	$cEscalas->setCodIdiomaIso2($idIdioma);
	 	$cEscalas->setIdBloque(9);
	 	$cEscalas->setIdBloqueHast(9);
	 	$cEscalas->setOrderBy("idEscala");
	 	$cEscalas->setOrder("ASC");
	 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
	 	$listaEscalas = $conn->Execute($sqlEscalas);
	 	$nEscalas=$listaEscalas->recordCount();
	 	if($nEscalas >0){
	 		$bPrimeraVuelta = true;
	 		while(!$listaEscalas->EOF){

	 			$iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 			$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
	 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $iPBaremada && $listaTextos->fields['puntMax'] >= $iPBaremada){
	 						$sHtml.= '<p class="textos_prisma">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</p>';
	 					}

	 					$listaTextos->MoveNext();
	 				}
	 			}

				if($iPBaremada==1 || $iPBaremada==2 || $iPBaremada==3){
					$aMejoras[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}
		       	if($iPBaremada==8 || $iPBaremada==9 || $iPBaremada==10){
					$aFuertes[] = $listaEscalas->fields['nombre'] . " (" . $iPBaremada . ")";
		       	}

		        $listaEscalas->MoveNext();
	 		}
	 	}


	$sHtml.= '<h2 class="subtitulo_prisma" style="color:#000000;">' . constant("STR_PRISMA_ESCALAS_DIFERENCIALES") . '</h2>';
	$sHtml.='<div class="caja">
				<div style="width:80%;padding-left:50px;"><img src="'.constant("DIR_WS_GESTOR") . constant("DIR_WS_GRAF") . 'prisma/'. $idIdioma . '/graficoNarrativos.jpg" title="gráfico" align="center"/></div>
				<div style="width:80%;padding-left:40px;">
					<table width="100%">
						<tr>
							<td width="50%">
								<ul>';
									$sizeMejoras = sizeof($aMejoras);
									if($sizeMejoras>0){
										$iM=0;
										while($iM<$sizeMejoras){
											$sHtml.='<li>'.$aMejoras[$iM].'</li>';
											$iM++;
										}
									}
	$sHtml.='					</ul>
							</td>
							<td width="50%" align="right">
								<ul>';
									$sizeFuertes = sizeof($aFuertes);
									if($sizeFuertes>0){
										$iF=0;
										while($iF<$sizeFuertes){
											$sHtml.='<li>'.$aFuertes[$iF].'</li>';
											$iF++;
										}
									}
	$sHtml.='					</ul>
							</td>
						</tr>
					</table>
				</div>';
 $sHtml.='
      		</div>
    </div>
    <!--FIN DIV PAGINA-->
    <hr>
    ';

     return $sHtml;
	}

		/**
	 * Calcula los resultados de los candidatos para un proceso concreto
	 * @param mrequest
	 * @param conConexion
	 * @param idProceso
	 * @throws Exception
	 */
	function calculaResultados($conn, $idProceso, $idEmpresa)
    {
    	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_competencias/Sec_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_competencias/Sec_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_dimensiones/Sec_dimensionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_dimensiones/Sec_dimensiones.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionesdimensiones/Sec_ponderacionesdimensionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionesdimensiones/Sec_ponderacionesdimensiones.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderaciones/Sec_ponderacionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderaciones/Sec_ponderaciones.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionesfa/Sec_ponderacionesfaDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionesfa/Sec_ponderacionesfa.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionescompetencias/Sec_ponderacionescompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionescompetencias/Sec_ponderacionescompetencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionesfm/Sec_ponderacionesfmDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_ponderacionesfm/Sec_ponderacionesfm.php");
    	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_respuestasfa/Sec_respuestasfaDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_respuestasfa/Sec_respuestasfa.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_respuestasfm/Sec_respuestasfmDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_respuestasfm/Sec_respuestasfm.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_respuestascompetencias/Sec_respuestascompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_respuestascompetencias/Sec_respuestascompetencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_perfilesprocesos/Sec_perfilesprocesosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_perfilesprocesos/Sec_perfilesprocesos.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_factoresactitudinales/Sec_factoresactitudinalesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_factoresactitudinales/Sec_factoresactitudinales.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_factoresmotivacionales/Sec_factoresmotivacionalesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_factoresmotivacionales/Sec_factoresmotivacionales.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_factorescompetencias/Sec_factorescompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Sec_factorescompetencias/Sec_factorescompetencias.php");


		$vCandidatos = "";

		$cCompetenciasBD = new Sec_competenciasDB($conn);
		$cDimensionBD = new Sec_dimensionesDB($conn);
		$pondDimBD = new Sec_ponderacionesdimensionesDB($conn);
		$pondBD = new Sec_ponderacionesDB($conn);
		$pondFaBD = new Sec_ponderacionesfaDB($conn);
		$pondCBD = new Sec_ponderacionescompetenciasDB($conn);
		$pondFmBD = new Sec_ponderacionesfmDB($conn);
		// Declaració de las entidades donde se van a almacenar las respuestas
		// de la gente a esos factores.
		$cRespuestasFa = new Sec_respuestasfa();
		$cRespuestasFaBD = new Sec_respuestasfaDB($conn);
		$cRespuestasFm = new Sec_respuestasfm();
		$cRespuestasFmBD = new Sec_respuestasfmDB($conn);
		$cRespuestasCompetencias = new Sec_respuestascompetencias();
		$cRespuestasCompetenciasBD = new Sec_respuestascompetenciasDB($conn);
		// Fin entidades de almacenaje

		$respItemsBD = new Respuestas_pruebas_itemsDB($conn);
		$pruebaBD = new PruebasDB($conn);
		$cCandidato = new Candidatos();
		$cCandidatoBD = new CandidatosDB($conn);

		$perProc = new Sec_perfilesprocesos();
		$perProcBD = new Sec_perfilesprocesosDB($conn);

		$perProc->setIDPROCESO($idProceso);
		$perProc = $perProcBD->consultaPorProceso($perProc);

		$cFactActitudinales = new Sec_factoresactitudinales();
		$cFactActitudinalesBD = new Sec_factoresactitudinalesDB($conn);
		$vFA = "";

		$cFactActitudinales->setOrderBy("IDFACTOR");
		$cFactActitudinales->setOrder("ASC");
		$sql = $cFactActitudinalesBD->readLista($cFactActitudinales);
		$vFA = $conn->Execute($sql);

		$cFactMotivacionales = new Sec_factoresmotivacionales();
		$cFactMotivacionalesBD = new Sec_factoresmotivacionalesDB($conn);
		$vFM = "";

		$cFactMotivacionales->setOrderBy("IDFACTOR");
		$cFactMotivacionales->setOrder("ASC");
		$sql = $cFactMotivacionalesBD->readLista($cFactMotivacionales);
		$vFM = $conn->Execute($sql);

		$cFactCompetencias = new Sec_factorescompetencias();
		$cFactCompetenciasBD = new Sec_factorescompetenciasDB($conn);
		$vFC = "";

		$cFactCompetencias->setOrderBy("IDFACTOR");
		$cFactCompetencias->setOrder("ASC");
		$sql = $cFactCompetenciasBD->readLista($cFactCompetencias);
		$vFC = $conn->Execute($sql);

		$cCandidato->setIdProceso($idProceso);
		//Hay que setearle tambien la empresa en la nueva plataforma
		$cCandidato->setIdEmpresa($idEmpresa);
		$sql = $cCandidatoBD->readLista($cCandidato);

		$vCandidatos = $conn->Execute($sql);

		if ($vCandidatos->recordCount() > 0) {

			$iCandi = 0;
			while(!$vCandidatos->EOF){
				$respItems = new Respuestas_pruebas_items();

				$respItems->setIdCandidato($vCandidatos->fields["idCandidato"]);
				$respItems->setIdProceso($idProceso);
				$respItems->setIdEmpresa($idEmpresa);

				/*************************************************/
				/********
				 * Vector de Factores Actitudinales
				 ***************************/
				if ($vFA->recordCount() > 0) {
					$iFa = 0;
					$hFa = "";
//					while ($iFa < $vFA.size()) {
					while(!$vFA->EOF){

//						$hFa = $vFA.elementAt($iFa);
						$vItems = "";

						// Comprobamos si este factor actitudinal pertenece a
						// una competencia o a una dimension.
						// Si IDCOMPETENCIA es 0 significa que pertenece a una
						// competencia, en caso contrario pertenece a una
						// dimensión.
						if ($vFA->fields["IDCOMPETENCIA"] != "0") {
							$cCompetencia = new Sec_competencias();
							$cCompetencia->setIDCOMPETENCIA($vFA->fields["IDCOMPETENCIA"]);
							$cCompetencia->setIDTIPOPRUEBA($vFA->fields["IDTIPOPRUEBA"]);

							$cCompetencia = $cCompetenciasBD->consultar($cCompetencia);

							// Si no hay ninguna competencia que coincida no
							// listamos porque nos listaría un vector con todos
							// los items
							// para ese candidato y proceso, no nos filtraría la
							// competencia.

							if ($cCompetencia->getIDTIPOPRUEBA() != "") {
								$respItems->setCodigo($cCompetencia->getCODIGO());
								$sql = $respItemsBD->readListaItems($respItems);
								$vItems = $conn->Execute($sql);

								$cDimension = new Sec_dimensiones();

								$cDimension->setCODIGO($vFA->fields["CODIGO"]);
								$cDimension->setIDCOMPETENCIA($vFA->fields["IDCOMPETENCIA"]);
								$cDimension->setIDTIPOPRUEBA($vFA->fields["IDTIPOPRUEBA"]);
								$cDimension = $cDimensionBD->consultarConCod($cDimension);

								$puntuacionMinDim = intval($cDimension->getPUNTUACIONMIN());
								$puntuacionMaxDim = intval($cDimension->getPUNTUACIONMAX());
								$sumar = abs($puntuacionMinDim);

								if ($vItems->recordCount() > 0) {
									echo "<br />->vItems->recordCount():" . $vItems->recordCount();
									exit;
									$suma = 0;
									$iPd =0;
									$iItems = 0;
									$iCont = 0;

//									$hItems = "";

//									while ($iItems < $vItems.size()) {
									while(!$vItems->EOF){
										$sDim="";
//										$hItems = $vItems.elementAt($iItems);
										$valor = 0;
										$buscaDim[] =explode("d", $vItems->fields["ITEM"]);

										$sDespuesD = $buscaDim[1];

										$sDim="d";

										for($cuenta=0; $cuenta < strlen($sDespuesD);$cuenta++){

											$bIsNumber=false;
											for($i=0; $i<10;$i++){
												if($sDespuesD{$cuenta} == $i){
													$sDim = $sDim . $sDespuesD{$cuenta};
													$bIsNumber=true;
													break;
												}
											}
											if($bIsNumber==false){
												$cuenta= strlen($sDespuesD);
											}else{break;}
										}

										if ($sDim == $cDimension->getCODIGO()) {
											if ($vItems->fields["valor"] != "") {
												$valor = intval($vItems->fields["valor"]);
											}
										}
										$suma = $suma + $valor;
										$iPd = $iPd + $valor;

										$iCont++;
										$iItems++;
										$vItems->MoveNext();
									}
									if ($puntuacionMinDim < 0) {
										$puntuacionMinDim = $puntuacionMinDim+ $sumar;
										$puntuacionMaxDim = $puntuacionMaxDim+ $sumar;
										$suma = $suma + $sumar;
									}

									$sobre10 = ($suma * 10.00) / $puntuacionMaxDim;

									if($sobre10 <1){
										$sobre10 = 1;
									}

									$pondFa = new Sec_ponderacionesfa();

									if ($perProc->getIDPERFILPROCESO() != "" ) {
										$pondFa->setIDPERFILPUESTO($perProc->getIDPERFILPUESTO());
									} else {
										$pondFa->setIDPERFILPUESTO("1");
									}
									$pondFa->setIDCOMPETENCIA($cDimension->getIDCOMPETENCIA());
									$pondFa->setIDTIPOPRUEBA($cDimension->getIDTIPOPRUEBA());
									$pondFa->setIDDIMENSION($cDimension->getIDDIMENSION());

									$pondFa = $pondFaBD->consultar($pondFa);

									$ponderacion = null;
									if($pondFa->getPONDERACION() !=""){
										$ponderacion = str_replace(",", ".", $pondFa->getPONDERACION());
									}else{
										$ponderacion = 1;
									}

									$mediaPonderada = ($sobre10 * ($ponderacion));

									$resFa = new Sec_respuestasfa();

									$resFa->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
									$resFa->setIDPROCESO($idProceso);
									$resFa->setIDPROCESOHast($idProceso);
									$resFa->setDNI($vCandidatos->fields["DNI"]);
									$resFa->setCODFACTOR($cDimension->getCODIGO());
									$resFa->setDESCFACTOR($cDimension->getNOMBRE());
									$resFa->setIDFACTOR($cDimension->getIDDIMENSION());

									$vCompruebaFa = "";
									$sql = $cRespuestasFaBD->readLista($resFa);
									$vCompruebaFa = $conn->Execute($sql);

									if($vFA->fields["IDCOMPETENCIA"] == "1" && $cDimension->getCODIGO() == "d6"){
										$respItems->setCodigo("f1item11d6");
										$sql = $respItemsBD->readListaItems($respItems);
										$vItems = $conn->Execute($sql);
										$icI = 0;
										if($vItems->recordCount() > 0){
//											$hItems =$vItems.elementAt($icI);
											$iPd = intval($vItems->fields["valor"]);

											if($iPd == -5){
												$sobre10 = round( 1, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == -4){
												$sobre10 = round( 1, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == -3){
												$sobre10 = round( 2, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == -2){
												$sobre10 = round( 3, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == -1){
												$sobre10 = round( 4, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == 0){
												$sobre10 = round( 5, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == 1){
												$sobre10 = round( 6, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == 2){
												$sobre10 = round( 7, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == 3){
												$sobre10 = round( 8, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == 4){
												$sobre10 = round( 9, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
											if($iPd == 5){
												$sobre10 = round( 10, 2, PHP_ROUND_HALF_UP);
												$mediaPonderada = ($sobre10 * ($ponderacion));
											}
										}
									}

									if($vCompruebaFa->recordCount() <= 0){

										$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPd));
										$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
										$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
										$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

										$newId = $cRespuestasFaBD.insertar($resFa);
									}else{

										$cRespuestasFaBD.borrarFa($resFa);

										$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPd));
										$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
										$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
										$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

										$newId = $cRespuestasFaBD.insertar($resFa);
									}

								}
							}

						} else {

							$cCompetencia = new Sec_competencias();
							$cCompetencia->setCODIGO($vFA->fields["CODIGO"]);

							$cCompetencia = $cCompetenciasBD->consultarPorCod($cCompetencia);

							$respItems->setCodigo($cCompetencia->getCODIGO());
							$sql = $respItemsBD->readListaItems($respItems);
							$vItems = $conn->Execute($sql);

							$puntuacionMinComp = intval($cCompetencia->getPUNTUACIONMIN());
							$puntuacionMaxComp = intval($cCompetencia->getPUNTUACIONMAX());
							$sumar = abs($puntuacionMinComp);

							if ($vItems->recordCount() > 0) {
								$suma = 0;
								$iPd = 0;
								$iItems = 0;
								$iCont = 0;

//								$hItems = "";

//								while ($iItems < $vItems.size()) {
								while(!$vItems->EOF){

//									$hItems =  $vItems.elementAt($iItems);
									$valor = 0;
									if ($vItems->fields["valor"] !="") {
										$valor = intval($vItems->fields["valor"]);
									}
									$suma = $suma + $valor;
									$iPd = $iPd + $valor;
									$iCont++;
									$iItems++;
									$vItems->MoveNext();
								}

								if ($puntuacionMinComp < 0) {
									$puntuacionMinComp = $puntuacionMinComp+ $sumar;
									$puntuacionMaxComp = $puntuacionMaxComp+ $sumar;
									$suma = $suma + $sumar;
								}

								//$sobre10 = new BigDecimal(($suma * 10.00) / $puntuacionMaxComp).setScale(2, BigDecimal.ROUND_HALF_UP);
								$sobre10 = round(($suma * 10.00) / $puntuacionMaxComp, 2, PHP_ROUND_HALF_UP);

								if($sobre10 <1){
									//$sobre10 = new BigDecimal(1);
									$sobre10 = round(1, 2);
								}

								$cPonderacionFa = new Sec_ponderacionesfa();

								if ($perProc->getIDPERFILPROCESO() != "") {
									$cPonderacionFa->setIDPERFILPUESTO($perProc->getIDPERFILPUESTO());
								} else {
									$cPonderacionFa->setIDPERFILPUESTO("1");
								}

								$cPonderacionFa->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
								$cPonderacionFa->setIDTIPOPRUEBA($cCompetencia->getIDTIPOPRUEBA());
								$cPonderacionFa->setIDDIMENSION("0");

								$cPonderacionFa = $pondFaBD->consultar($cPonderacionFa);

								$ponderacion = null;

								if($cPonderacionFa->getPONDERACION() != ""){
									$ponderacion = str_replace(",", ".", $cPonderacionFa->getPONDERACION());
								}else{
									$ponderacion = 1;
								}

								$mediaPonderada = ($sobre10 * ($ponderacion));

								$resFa = new Sec_respuestasfa();

								$resFa->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
								$resFa->setIDPROCESO($idProceso);
								$resFa->setIDPROCESOHast($idProceso);
								$resFa->setDNI($vCandidatos->fields["DNI"]);
								$resFa->setCODFACTOR($cCompetencia->getCODIGO());
								$resFa->setDESCFACTOR($cCompetencia->getDESCRIPCION());
								$resFa->setIDFACTOR($cCompetencia->getIDCOMPETENCIA());

								$vCompruebaFa = "";
								$sql = $cRespuestasFaBD->readLista($resFa);
								$vCompruebaFa = $conn->Execute($sql);

								if($cCompetencia->getCODIGO() != "f9"){

									if($cCompetencia->getCODIGO() == "f5"){
										$iPdTotalF5=0;
										$iContTotalF5=0;
										$iItemsTotalF5 =0;
										$sumaTotalF5=0;
										if ($vItems->recordCount() > 0) {
											$suma = 0;
											$iPd = 0;
											$iItems = 0;
											$iCont = 0;

											$hItems = "";

//											while ($iItems < $vItems.size()) {
											while(!$vItems->EOF){
												$sDim="";
//												$hItems =  $vItems.elementAt($iItems);
												$valor = 0;
												$buscaDim[] =explode("d", $vItems->fields["ITEM"]);
//-->Pedro
												$sDespuesD = "";
												if ($buscaDim.length > 1){
													$sDespuesD = $buscaDim[1];
												}
//-->Fin Pedro
												$sDim="d";

												for($cuenta=0;$cuenta < strlen($sDespuesD);$cuenta++){

													$bIsNumber=false;
													for($i=0; $i<10;$i++){
														if($sDespuesD{$cuenta} == $i){
															$sDim = $sDim . $sDespuesD{$cuenta};
															$bIsNumber=true;
														}
													}
													if($bIsNumber==false){
														$cuenta= strlen($sDespuesD);
													}
												}

												if ($sDim == "d1") {
													if ($vItems->fields["valor"] != "") {
														$valor = intval($vItems->fields["valor"]);
													}
												}

												$suma = $suma + $valor;
												$iPd = $iPd + $valor;

												$iCont++;
												$iItems++;
												$vItems->MoveNext();
											}

											$cDimension = new Sec_dimensiones();

											$cDimension->setCODIGO("d1");
											$cDimension->setIDCOMPETENCIA("5");
											$cDimension->setIDTIPOPRUEBA("2");
											$cDimension = $cDimensionBD->consultarConCod($cDimension);

											$puntuacionMinDim = intval($cDimension->getPUNTUACIONMIN());
											$puntuacionMaxDim = intval($cDimension->getPUNTUACIONMAX());
											$sumarD = abs($puntuacionMinDim);

											if ($puntuacionMinDim < 0) {
												$puntuacionMinDim = $puntuacionMinDim+ $sumarD;
												$puntuacionMaxDim = $puntuacionMaxDim+ $sumarD;
												$suma = $suma + $sumarD;
											}

											//$sobre10 = new BigDecimal(($suma * 10.00) / $puntuacionMaxDim).setScale(2, BigDecimal.ROUND_HALF_UP);
											$sobre10 = round(($suma * 10.00) / $puntuacionMaxDim, 2, PHP_ROUND_HALF_UP);
											if($sobre10 <1){
												//$sobre10 = new BigDecimal(1);
												$sobre10 = round(1, 2);
											}
											$resDimF5 = new Sec_respuestasdimensiones();
											$resDimBD = new Sec_respuestasdimensionesDB($conn);
											$resDimF5->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
											$resDimF5->setIDPROCESO($idProceso);
											$resDimF5->setDNI($vCandidatos->fields["DNI"]);
											$resDimF5->setCODCOMPETENCIA("f5");
											$resDimF5->setIDCOMPETENCIA("2");
											$resDimF5->setIDDIMENSION($cDimension->getIDDIMENSION());
											$resDimF5->setCODDIMENSION($cDimension->getCODIGO());
											$resDimF5->setDESCDIMENSION($cDimension->getNOMBRE());


											$vCompruebaDim = "";
											$sql = $resDimBD->readLista($resDimF5);
											$vCompruebaDim = $conn->Execute($sql);

											if($vCompruebaDim->recordCount() <= 0){
												$resDimF5->setPUNTUACIONDIRECTA(Integer.toString($iPd));
												$resDimF5->setPUNTSOBRE10(String.valueOf($sobre10));
												$resDimF5->setMEDIAPONDERADA("0");

												$newId = $resDimBD.insertar($resDimF5);
											}else{

												$resDimBD.borrarDim($resDimF5);

												$resDimF5->setPUNTUACIONDIRECTA(Integer.toString($iPd));
												$resDimF5->setPUNTSOBRE10(String.valueOf($sobre10));
												$resDimF5->setMEDIAPONDERADA("0");

												$newId = $resDimBD.insertar($resDimF5);
											}
											$iPdTotalF5 = $iPd;
											$iContTotalF5 = $iCont;
											$sumaTotalF5 = $suma;
											$iItemsTotalF5 = $iItems;

											$suma = 0;
											$iPd = 0;
											$iItems = 0;
											$iCont = 0;
//											while ($iItems < $vItems.size()) {
											while(!$vItems->EOF){

												$sDim="";
//												$hItems =  $vItems.elementAt($iItems);
												$valor = 0;
												$buscaDim[] =explode("d", $vItems->fields["ITEM"]);

//-->Pedro
												$sDespuesD = "";
												if ($buscaDim.length > 1){
													$sDespuesD = $buscaDim[1];
												}
//-->Fin Pedro
												$sDim="d";

												for($cuenta=0; $cuenta < strlen($sDespuesD);$cuenta++){

													$bIsNumber=false;
													for($i=0; $i<10;$i++){
														if($sDespuesD{$cuenta} == $i){
															$sDim = $sDim . $sDespuesD{$cuenta};
															$bIsNumber=true;
															break;
														}
													}
													if($bIsNumber==false){
														$cuenta= strlen($sDespuesD);
													}else{break;}
												}

												if ($sDim == "d2") {
													if ($vItems->fields["valor"] != "") {
														$valor = intval($vItems->fields["valor"]);
													}
												}
												$suma = $suma + $valor;
												$iPd = $iPd + $valor;
												$iCont++;
												$iItems++;
												$vItems->MoveNext();
											}

											$cDimension = new Sec_dimensiones();

											$cDimension->setCODIGO("d2");
											$cDimension->setIDCOMPETENCIA("5");
											$cDimension->setIDTIPOPRUEBA("2");
											$cDimension = $cDimensionBD->consultarConCod($cDimension);

											$sumarD =0;
											$puntuacionMinDim =0;
											$puntuacionMaxDim =0;
											$puntuacionMinDim = intval($cDimension->getPUNTUACIONMIN());
											$puntuacionMaxDim = intval($cDimension->getPUNTUACIONMAX());
											$sumarD = abs($puntuacionMinDim);

											if ($puntuacionMinDim < 0) {
												$puntuacionMinDim = $puntuacionMinDim+ $sumarD;
												$puntuacionMaxDim = $puntuacionMaxDim+ $sumarD;
												$suma = $suma + $sumarD;
											}

											//$sobre10 = new BigDecimal(($suma * 10.00) / $puntuacionMaxDim).setScale(2, BigDecimal.ROUND_HALF_UP);
											$sobre10 = round(($suma * 10.00) / $puntuacionMaxDim, 2, PHP_ROUND_HALF_UP);

											if($sobre10 <1){
												//$sobre10 = new BigDecimal(1);
												$sobre10 = round(1, 2);
											}

											$resDimF5 = new Sec_respuestasdimensiones();

											$resDimF5->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
											$resDimF5->setIDPROCESO($idProceso);
											$resDimF5->setDNI($vCandidatos->fields["DNI"]);
											$resDimF5->setCODCOMPETENCIA("f5");
											$resDimF5->setIDCOMPETENCIA("2");
											$resDimF5->setIDDIMENSION($cDimension->getIDDIMENSION());
											$resDimF5->setCODDIMENSION($cDimension->getCODIGO());
											$resDimF5->setDESCDIMENSION($cDimension->getNOMBRE());

											$vCompruebaDim = "";
											$sql = $resDimBD->readLista($resDimF5);
											$vCompruebaDim = $conn->Execute($sql);

											if($vCompruebaDim->recordCount() <= 0){
												$resDimF5->setPUNTUACIONDIRECTA(Integer.toString($iPd));
												$resDimF5->setPUNTSOBRE10(String.valueOf($sobre10));
												$resDimF5->setMEDIAPONDERADA("0");

												$newId = $resDimBD.insertar($resDimF5);
											}else{

												$resDimBD.borrarDim($resDimF5);

												$resDimF5->setPUNTUACIONDIRECTA(Integer.toString($iPd));
												$resDimF5->setPUNTSOBRE10(String.valueOf($sobre10));
												$resDimF5->setMEDIAPONDERADA("0");

												$newId = $resDimBD.insertar($resDimF5);
											}
										}
										$iPdTotalF5 = $iPdTotalF5 + $iPd;
										$iContTotalF5 = $iContTotalF5 +  $iCont;
										$sumaTotalF5 = $sumaTotalF5 +  $suma;
										$iItemsTotalF5 = $iItemsTotalF5 + $iItems;

										if ($puntuacionMinComp < 0) {
											$puntuacionMinComp = $puntuacionMinComp+ $sumar;
											$puntuacionMaxComp = $puntuacionMaxComp+ $sumar;
											$sumaTotalF5 = $sumaTotalF5 + $sumar;
										}

										//$sobre10 = new BigDecimal(($sumaTotalF5 * 10.00) / $puntuacionMaxComp).setScale(2, BigDecimal.ROUND_HALF_UP);
										$sobre10 = round(($sumaTotalF5 * 10.00) / $puntuacionMaxComp, 2, PHP_ROUND_HALF_UP);

										if($sobre10 < 1){
											//$sobre10 = new BigDecimal(1);
											$sobre10 = round(1, 2);
										}

										$cPonderacionFa = new Sec_ponderacionesfa();

										if ($perProc->getIDPERFILPROCESO() != "") {
											$cPonderacionFa->setIDPERFILPUESTO($perProc->getIDPERFILPUESTO());
										} else {
											$cPonderacionFa->setIDPERFILPUESTO("1");
										}

										$cPonderacionFa->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
										$cPonderacionFa->setIDTIPOPRUEBA($cCompetencia->getIDTIPOPRUEBA());
										$cPonderacionFa->setIDDIMENSION("0");

										$cPonderacionFa = $pondFaBD->consultar($cPonderacionFa);

										$ponderacion = null;

										if($cPonderacionFa->getPONDERACION() != ""){
											$ponderacion = str_replace(",", ".", $cPonderacionFa->getPONDERACION());
										}else{
											$ponderacion = 1;
										}

										$mediaPonderada = ($sobre10 * ($ponderacion));

										if($vCompruebaFa->recordCount() <= 0){
											$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPdTotalF5));
											$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
											$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
											$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

											$newId = $cRespuestasFaBD.insertar($resFa);
										}else{

											$cRespuestasFaBD.borrarFa($resFa);

											$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPdTotalF5));
											$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
											$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
											$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

											$newId = $cRespuestasFaBD.insertar($resFa);
										}

									}else{

										if($vCompruebaFa->recordCount() <= 0){
											$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPd));
											$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
											$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
											$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

											$newId = $cRespuestasFaBD.insertar($resFa);
										}else{

											$cRespuestasFaBD.borrarFa($resFa);

											$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPd));
											$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
											$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
											$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

											$newId = $cRespuestasFaBD.insertar($resFa);
										}
									}

								}else{

									$coeficiente = calculaCoeficiente($idProceso, $vCandidatos->fields["idCandidato"], $vCandidatos->fields["DNI"], $conn);

									if($coeficiente!=-2){
										//$coef = Double.parseDouble(String.valueOf(new BigDecimal($coeficiente).setScale(2, BigDecimal.ROUND_HALF_UP)));
										$coef = round($coeficiente, 2, PHP_ROUND_HALF_UP);
										//$sobre10 = new BigDecimal(10*$coeficiente).setScale(2, BigDecimal.ROUND_HALF_UP);
										$sobre10 = round(10*$coeficiente, 2, PHP_ROUND_HALF_UP);
									}

									if($vCompruebaFa->recordCount() <= 0){
										$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPd));
										$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
										$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
										$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

										$newId = $cRespuestasFaBD.insertar($resFa);
									}else{

										$cRespuestasFaBD.borrarFa($resFa);

										$resFa->setPUNTUACIONDIRECTA(Integer.toString($iPd));
										$resFa->setPUNTSOBRE10(String.valueOf($sobre10));
										$resFa->setMEDIAPONDERADA(String.valueOf($mediaPonderada));
										$resFa->setINTELIGENCIA($vFA->fields["INTELIGENCIA"]);

										$newId = $cRespuestasFaBD.insertar($resFa);
									}
								}
							}
						}
						$iFa++;
						$vFA->MoveNext();
					}
				}

				/*************************************************/
				/********
				 * Vector de Competencias
				 ***************************/
				if ($vFC->recordCount() > 0) {
					$iFc = 0;
					$hFc = "";
//					while ($iFc < $vFC.size()) {
					while(!$vFC->EOF){

//						$hFc =  $vFC.elementAt($iFc);
						$vItems = "";
						$cCompetencia = new Sec_competencias();
						$cCompetencia->setCODIGO($vFC->fields["CODIGO"]);

						$cCompetencia = $cCompetenciasBD->consultarPorCod($cCompetencia);

						$respItems->setCodigo($cCompetencia->getCODIGO());
						$sql = $respItemsBD->readListaItems($respItems);

						$vItems = $conn->Execute($sql);

						$puntuacionMinComp = intval($cCompetencia->getPUNTUACIONMIN());
						$puntuacionMaxComp = intval($cCompetencia->getPUNTUACIONMAX());
						$sumar = abs($puntuacionMinComp);

						if ($vItems->recordCount() > 0) {
							$suma = 0;
							$iPd = 0;
							$iItems = 0;
							$iCont = 0;

							$hItems = "";

//							while ($iItems < $vItems.size()) {
							while(!$vItems->EOF){

//								$hItems =  $vItems.elementAt($iItems);

								$sCom = "";

								$aBuscacomp =explode("c", $vItems->fields["codigo"]);

								$sDespuesDC = $aBuscacomp[1];


								$sCom="c";

								for($cuenta=0; $cuenta < strlen($sDespuesDC);$cuenta++){

									$bIsNumber=false;
									for($ic=0; $ic<10;$ic++){
										if($sDespuesDC{$cuenta} == $ic){
											$sCom = $sCom . $sDespuesDC{$cuenta};
											$bIsNumber=true;
											break;
										}
									}
									if($bIsNumber==false){
										$cuenta= strlen($sDespuesDC);
									}else{break;}
								}
								echo "<br/>" . $sCom . " - " . $cCompetencia->getCODIGO();

								$valor = 0;
								if($sCom == $cCompetencia->getCODIGO()){
									echo "<br/>*->valor::" . $valor;
									if ($vItems->fields["valor"] != "") {
										$valor = intval($vItems->fields["valor"]);
										echo "<br/>" . $valor;
									}
									$iCont++;
								}

								$suma += $valor;
								$iPd += $valor;

								$iItems++;
								$vItems->MoveNext();
							}
							echo "<br/>*->iPd::" . $iPd;

							if($iCont>0){
								if ($puntuacionMinComp < 0) {
									$puntuacionMinComp = $puntuacionMinComp + $sumar;
									$puntuacionMaxComp = $puntuacionMaxComp + $sumar;
									$suma = $suma + $sumar;
								}

								//$sobre10 = new BigDecimal(($suma * 10.00)/ $puntuacionMaxComp).setScale(2,BigDecimal.ROUND_HALF_UP);
								$sobre10 = round(($suma * 10.00)/ $puntuacionMaxComp, 2, PHP_ROUND_HALF_UP);


								if($sobre10 <1){
									//$sobre10 = new BigDecimal(1);
									$sobre10 = round(1, 2);
								}

								$cPonderacionC = new Sec_ponderacionescompetencias();

								if ($perProc->getIDPERFILPROCESO() != "") {
									$cPonderacionC->setIDPERFILPUESTO($perProc->getIDPERFILPUESTO());
								} else {
									$cPonderacionC->setIDPERFILPUESTO("1");
								}

								$cPonderacionC->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
								$cPonderacionC->setIDTIPOPRUEBA($cCompetencia->getIDTIPOPRUEBA());

								$cPonderacionC = $pondCBD->consultar($cPonderacionC);

								$ponderacion = null;

								if($cPonderacionC->getPONDERACION() != ""){
									$ponderacion = str_replace(",", ".", $cPonderacionC->getPONDERACION());
								}else{
									$ponderacion = 1;
								}

								$mediaPonderada = ($sobre10 * ($ponderacion));

								$resComp = new Sec_respuestascompetencias();

								$resComp->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
								$resComp->setIDPROCESO($idProceso);
								$resComp->setIDPROCESOHast($idProceso);
								$resComp->setDNI($vCandidatos->fields["DNI"]);
								$resComp->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
								$resComp->setCODCOMPETENCIA($cCompetencia->getCODIGO());
								$resComp->setDESCCOMPETENCIA($cCompetencia->getDESCRIPCION());

								$vCompruebaC = "";
								$sql = $cRespuestasCompetenciasBD->readLista($resComp);
								$vCompruebaC = $conn->Execute($sql);

								if($vCompruebaC->recordCount() <= 0){
								$resComp->setPUNTUACIONDIRECTA(Integer.toString($iPd));
								$resComp->setPUNTSOBRE10(String.valueOf($sobre10));
								$resComp->setMEDIAPONDERADA(String.valueOf($mediaPonderada));

								$newId = $cRespuestasCompetenciasBD.insertar($resComp);
								}else{
									$cRespuestasCompetenciasBD.borrarFC($resComp);

									$resComp->setPUNTUACIONDIRECTA(Integer.toString($iPd));
									$resComp->setPUNTSOBRE10(String.valueOf($sobre10));
									$resComp->setMEDIAPONDERADA(String.valueOf($mediaPonderada));

									$newId = $cRespuestasCompetenciasBD.insertar($resComp);
								}

								//Ahora calcularemos los resultados para las dimensiones de cada una de las competencias

								$sumaDim = 0;
								$iPdDim = 0;
								$cDimension = new Sec_dimensiones();

								$cDimension->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
								$cDimension->setIDTIPOPRUEBA("1");
								$cDimension->setOrderBy("IDDIMENSION");
								$cDimension->setOrder("ASC");

								$vDimen = "";

								$sql = $cDimensionBD->readLista($cDimension);
								$vDimen = $conn->Execute($sql);
								$respDimBD = new Sec_respuestasdimensionesDB($conn);

								$hDimen = "";

								if($vDimen->recordCount() > 0){
									$iDim =0;
//									while($iDim < $vDimen.size()){
									while(!$vDimen->EOF){

										$hDimen = $vDimen.elementAt($iDim);

										$sumaDim = 0;
										$iPdDim = 0;
										$iItems = 0;
										$iCont = 0;

										$puntuacionMinDim = intval($hDimen.get("PUNTUACIONMIN").toString());
										$puntuacionMaxDim = intval($hDimen.get("PUNTUACIONMAX").toString());
										$sumarDim = abs($puntuacionMinDim);

										if ($vItems->recordCount() > 0) {
//											while ($iItems < $vItems.size()) {
											while(!$vItems->EOF){

//												$hItems =  $vItems.elementAt($iItems);


												$sDim="";

												$buscaDim[] =explode("d", $vItems->fields["ITEM"]);

												$sDespuesD = $buscaDim[1];

												$sDim="d";

												for($cuenta=0; $cuenta < strlen($sDespuesD);$cuenta++){

													$bIsNumber=false;
													for($i=0; $i<10;$i++){
														if($sDespuesD{$cuenta} == $i){
															$sDim = $sDim . $sDespuesD{$cuenta};
															$bIsNumber=true;
															break;
														}
													}
													if($bIsNumber==false){
														$cuenta= strlen($sDespuesD);
													}else{break;}
												}

												$sCom = "";

												$aBuscacomp =explode("c", $vItems->fields["ITEM"]);

												$sDespuesDC = $aBuscacomp[1];

												$sCom="c";

												for($cuenta=0; $cuenta < strlen($sDespuesDC);$cuenta++){

													$bIsNumber=false;
													for($ic=0; $ic<10;$ic++){
														if($sDespuesDC{$cuenta} == $ic){
															$sCom = sCom . $sDespuesDC{$cuenta};
															$bIsNumber=true;
															break;
														}
													}
													if($bIsNumber==false){
														$cuenta= strlen($sDespuesDC);
													}else{break;}
												}

												$valor = 0;
												if($sCom == $cCompetencia->getCODIGO()){
													if($sDim == $hDimen.get("CODIGO")){
														if ($vItems->fields["valor"] != "") {
															$valor = intval($vItems->fields["valor"]);
														}
													}
												}
												$sumaDim = $sumaDim + $valor;
												$iPdDim = $iPdDim + $valor;
												$iCont++;
												$iItems++;
												$vItems->MoveNext();
											}
										}

										if ($puntuacionMinDim < 0) {
											$puntuacionMinDim = $puntuacionMinDim + $sumarDim;
											$puntuacionMaxDim = $puntuacionMaxDim + $sumarDim;
											$sumaDim = $sumaDim + $sumarDim;
										}

										//$sobre10Dim = new BigDecimal(($sumaDim * 10.00)/ $puntuacionMaxDim).setScale(2,BigDecimal.ROUND_HALF_UP);
										$sobre10Dim = round(($sumaDim * 10.00)/ $puntuacionMaxDim, 2, PHP_ROUND_HALF_UP);

										if($sobre10Dim <1){
											//$sobre10Dim = new BigDecimal(1);
											$sobre10Dim = round(1, 2);
										}
										$resDim = new Sec_respuestasdimensiones();

										$resDim->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
										$resDim->setIDPROCESO($idProceso);
										$resDim->setIDPROCESOHast($idProceso);
										$resDim->setDNI($vCandidatos->fields["DNI"]);
										$resDim->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
										$resDim->setCODCOMPETENCIA($cCompetencia->getCODIGO());
										$resDim->setCODDIMENSION($hDimen.get("CODIGO").toString());
										$resDim->setDESCDIMENSION($hDimen.get("NOMBRE").toString());
										$resDim->setIDDIMENSION($hDimen.get("IDDIMENSION").toString());

										$vCompruebaDim = "";
										$sql = $respDimBD->readLista($resDim);
										$vCompruebaDim = $conn->Execute($sql);

										if($vCompruebaDim->recordCount() <= 0){
											$resDim->setPUNTUACIONDIRECTA(Integer.toString($iPdDim));
											$resDim->setPUNTSOBRE10(String.valueOf($sobre10Dim));
											$resDim->setMEDIAPONDERADA(String.valueOf($mediaPonderada));

											$newId = $respDimBD.insertar($resDim);
										}else{

											$respDimBD.borrarDim($resDim);

											$resDim->setPUNTUACIONDIRECTA(Integer.toString($iPdDim));
											$resDim->setPUNTSOBRE10(String.valueOf($sobre10Dim));
											$resDim->setMEDIAPONDERADA(String.valueOf($mediaPonderada));

											$newId = $respDimBD.insertar($resDim);
										}
										$iDim++;
										$vDimen->MoveNext();
									}
								}
							}
						}

						$iFc++;
						$vFC->MoveNext();
					}
				}

				/*************************************************/
				/********
				 * Vector de Factores Motivacionales
				 ***************************/

				if ($vFM->recordCount() > 0) {
					$iFm = 0;
					$hFm = "";
//					while ($iFm < $vFM.size()) {
					while(!$vFM->EOF){

//						$hFm =  $vFM.elementAt($iFm);
						$vItems = "";
						$cCompetencia = new Sec_competencias();
						$cCompetencia->setCODIGO($vFM->fields["CODIGO"]);

						$cCompetencia = $cCompetenciasBD->consultarPorCod($cCompetencia);

						$respItems->setCodigo($cCompetencia->getCODIGO());
						$sql = $respItemsBD->readListaItems($respItems);
						$vItems = $conn->Execute($sql);

						$puntuacionMinComp = intval($cCompetencia->getPUNTUACIONMIN());
						$puntuacionMaxComp = intval($cCompetencia->getPUNTUACIONMAX());
						$sumar = abs($puntuacionMinComp);

						if ($vItems->recordCount() > 0) {
							$suma = 0;
							$iPd =0;
							$iItems = 0;
							$iCont = 0;

//							$hItems = "";

//							while ($iItems < $vItems.size()) {
							while(!$vItems->EOF){

//								$hItems =  $vItems.elementAt($iItems);
								$valor = 0;
								if ($vItems->fields["valor"] != "") {
									$valor = intval($vItems->fields["valor"]);
								}
								$suma = $suma + $valor;
								$iPd = $iPd + $valor;
								$iCont++;
								$iItems++;
								$vItems->MoveNext();
							}

							if ($puntuacionMinComp < 0) {
								$puntuacionMinComp = $puntuacionMinComp + $sumar;
								$puntuacionMaxComp = $puntuacionMaxComp + $sumar;
								$suma = $suma + $sumar;
							}

							//$sobre10 = new BigDecimal(($suma * 10.00)/ $puntuacionMaxComp).setScale(2,BigDecimal.ROUND_HALF_UP);
							$sobre10 = round(($suma * 10.00)/ $puntuacionMaxComp, 2, PHP_ROUND_HALF_UP);

							if($sobre10 <1){
								$sobre10 = round(1, 2);
							}

							$cPonderacionFm = new Sec_ponderacionesfm();

							if ($perProc->getIDPERFILPROCESO() != "") {
								$cPonderacionFm->setIDPERFILPUESTO($perProc->getIDPERFILPUESTO());
							} else {
								$cPonderacionFm->setIDPERFILPUESTO("1");
							}

							$cPonderacionFm->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());
							$cPonderacionFm->setIDTIPOPRUEBA($cCompetencia->getIDTIPOPRUEBA());

							$cPonderacionFm = $pondFmBD->consultar($cPonderacionFm);

							$ponderacion = null;

							if($cPonderacionFm->getPONDERACION() != ""){
								$ponderacion = str_replace(",", ".", $cPonderacionFm->getPONDERACION());
							}else{
								$ponderacion = 1;
							}

							$mediaPonderada = ($sobre10 * ($ponderacion));

							$resFm = new Sec_respuestasfm();

							$resFm->setIDCANDIDATO($vCandidatos->fields["idCandidato"]);
							$resFm->setIDPROCESO($idProceso);
							$resFm->setIDPROCESOHast($idProceso);
							$resFm->setDNI($vCandidatos->fields["DNI"]);
							$resFm->setCODCOMPETENCIA($cCompetencia->getCODIGO());
							$resFm->setDESCCOMPETENCIA($cCompetencia->getDESCRIPCION());
							$resFm->setIDCOMPETENCIA($cCompetencia->getIDCOMPETENCIA());

							$vCompruebaFm = "";
							$sql = $cRespuestasFmBD->readLista($resFm);
							$vCompruebaFm = $conn->Execute($sql);

							if($vCompruebaFm->recordCount() <= 0){
								$resFm->setPUNTUACIONDIRECTA(Integer.toString($iPd));
								$resFm->setPUNTSOBRE10(String.valueOf($sobre10));
								$resFm->setMEDIAPONDERADA(String.valueOf($mediaPonderada));

								$newId = $cRespuestasFmBD.insertar($resFm);
							}else{
								$cRespuestasFmBD.borrarFm($resFm);

								$resFm->setPUNTUACIONDIRECTA(Integer.toString($iPd));
								$resFm->setPUNTSOBRE10(String.valueOf($sobre10));
								$resFm->setMEDIAPONDERADA(String.valueOf($mediaPonderada));

								$newId = $cRespuestasFmBD.insertar($resFm);

							}
						}

						$iFm++;
						$vFM->MoveNext();
					}
				}

				$iCandi++;
				$vCandidatos->MoveNext();
			}

		}
	}
/**
 * Calcula en coeficiente de secbook
 * @param idProceso
 * @param idCandidato
 * @param dni
 * @param conConexion
 * @return
 * @throws Exception
 */
function calculaCoeficiente($idProceso, $idCandidato, $dni, $conn)
{


		$cRespuestasCompetenciasBD = new Sec_respuestascompetenciasDB($conn);
		$cRespuestasFaBD = new Sec_respuestasfaDB($conn);
		$respItemsBD = new Sec_respuestaspruebasITEMSDB($conn);

		$cRespuestasItems = new Respuestas_pruebas_items();

		$listaPuntSecbookX = new ArrayList();
		$listaPuntCandidatosY = new ArrayList();


		$iCuentaPruebas =0;

		//Declaramos 12 variables para separar las 12 mediciones con las que tienen que compararse las respuestas de secbook

		//Las 3 dimensiones c1->PROACTIVIDAD, c2->ADAPTABILIDAD y c5->ORIENTACIÓN A RESULTADOS
		$cC1 = new Sec_respuestascompetencias();
		$cC2 = new Sec_respuestascompetencias();
		$cC5 = new Sec_respuestascompetencias();

		//Factor F2->COMPRENSION SOCIAL: d1->Comprensión organizativa y d2->Empatía

		$cF2d1 = new Sec_respuestasfa();
		$cF2d2 = new Sec_respuestasfa();

		//Factor F3->DESARROLLO RELACIONES E INFLUENCIA: d1->Desarrollo de relaciones y d2->Influencia

		$cF3d1 = new Sec_respuestasfa();
		$cF3d2 = new Sec_respuestasfa();

		//Factor F4->TOLERANCIA A LA FRUSTRACIÓN Y PERSISTENCIA: d2->Tolerancia a la frustración y d1->Persistencia

		$cF4d1 = new Sec_respuestasfa();
		$cF4d2 = new Sec_respuestasfa();


		//Factores f5->AUTOCONTROL, f6->AUTOCONFIANZA, f7->AFECTIVIDAD POSITIVA
		$cF5 = new Sec_respuestasfa();
		$cF6 = new Sec_respuestasfa();
		$cF7 = new Sec_respuestasfa();


		//En cada uno de los factores que mide secbook miramos a ver si hay resultados de cada uno de ellos.


		$cC1->setIDPROCESO($idProceso);
		$cC1->setIDCANDIDATO($idCandidato);
		$cC1->setDNI($dni);
		$cC1->setCODCOMPETENCIA("c1");

		$cC1 = $cRespuestasCompetenciasBD->consultarPorCod($cC1);

		$vI = new Vector();

		$sumaSecbook =0;
		if($cC1->getIDRESPUESTA() != "")
		{

			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			//Listamos los items para etse factor
			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "c1");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			//Añadimos a la lista de Puntuaciones de secbook y del candidato los valores que hemos obtenido.
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cC1->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cC1->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cC2->setIDPROCESO($idProceso);
		$cC2->setIDCANDIDATO($idCandidato);
		$cC2->setDNI($dni);
		$cC2->setCODCOMPETENCIA("c2");

		$cC2 = $cRespuestasCompetenciasBD->consultarPorCod($cC2);

		if($cC2->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "c2");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cC2->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cC2->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));



			$iCuentaPruebas++;
		}

		$cC5->setIDPROCESO($idProceso);
		$cC5->setIDCANDIDATO($idCandidato);
		$cC5->setDNI($dni);
		$cC5->setCODCOMPETENCIA("c5");

		$cC5 = $cRespuestasCompetenciasBD->consultarPorCod($cC5);

		if($cC5->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "c5");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cC5->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cC5->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF2d1->setIDPROCESO($idProceso);
		$cF2d1->setIDCANDIDATO($idCandidato);
		$cF2d1->setDNI($dni);
		$cF2d1->setCODFACTOR("d1");
		$cF2d1->setDESCFACTOR("Comprensi");

		$cF2d1 = $cRespuestasFaBD->consultaCompleta($cF2d1);

		if($cF2d1->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f2d1");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF2d1->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF2d1->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF2d2->setIDPROCESO($idProceso);
		$cF2d2->setIDCANDIDATO($idCandidato);
		$cF2d2->setDNI($dni);
		$cF2d2->setCODFACTOR("d2");
		$cF2d2->setDESCFACTOR("Empat");

		$cF2d2 = $cRespuestasFaBD->consultaCompleta($cF2d2);

		if($cF2d2->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f2d2");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF2d2->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF2d2->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF3d1->setIDPROCESO($idProceso);
		$cF3d1->setIDCANDIDATO($idCandidato);
		$cF3d1->setDNI($dni);
		$cF3d1->setCODFACTOR("d1");
		$cF3d1->setDESCFACTOR("Desarrollo de relaciones");

		$cF3d1 = $cRespuestasFaBD->consultaCompleta($cF3d1);

		if($cF3d1->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f3d1");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF3d1->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF3d1->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));


			$iCuentaPruebas++;
		}


		$cF3d2->setIDPROCESO($idProceso);
		$cF3d2->setIDCANDIDATO($idCandidato);
		$cF3d2->setDNI($dni);
		$cF3d2->setCODFACTOR("d2");
		$cF3d2->setDESCFACTOR("Influencia");

		$cF3d2 = $cRespuestasFaBD->consultaCompleta($cF3d2);

		if($cF3d2->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f3d2");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF3d2->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF3d2->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF4d1->setIDPROCESO($idProceso);
		$cF4d1->setIDCANDIDATO($idCandidato);
		$cF4d1->setDNI($dni);
		$cF4d1->setCODFACTOR("d1");
		$cF4d1->setDESCFACTOR("Persistencia");

		$cF4d1 = $cRespuestasFaBD->consultaCompleta($cF4d1);

		if($cF4d1->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f4d1");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF4d1->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF4d1->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF4d2->setIDPROCESO($idProceso);
		$cF4d2->setIDCANDIDATO($idCandidato);
		$cF4d2->setDNI($dni);
		$cF4d2->setCODFACTOR("d2");
		$cF4d2->setDESCFACTOR("Tolerancia");

		$cF4d2 = $cRespuestasFaBD->consultaCompleta($cF4d2);

		if($cF4d2->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f4d2");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF4d2->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF4d2->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF5->setIDPROCESO($idProceso);
		$cF5->setIDCANDIDATO($idCandidato);
		$cF5->setDNI($dni);
		$cF5->setCODFACTOR("f5");
		$cF5->setDESCFACTOR("AUTOCONTROL");

		$cF5 = $cRespuestasFaBD->consultaCompleta($cF5);

		if($cF5->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f5");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF5->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF5->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF6->setIDPROCESO($idProceso);
		$cF6->setIDCANDIDATO($idCandidato);
		$cF6->setDNI($dni);
		$cF6->setCODFACTOR("f6");
		$cF6->setDESCFACTOR("AUTOCONFIANZA");

		$cF6 = $cRespuestasFaBD->consultaCompleta($cF6);

		if($cF6->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f6");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF6->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF6->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));

			$iCuentaPruebas++;
		}

		$cF7->setIDPROCESO($idProceso);
		$cF7->setIDCANDIDATO($idCandidato);
		$cF7->setDNI($dni);
		$cF7->setCODFACTOR("f7");
		$cF7->setDESCFACTOR("AFECTIVIDAD");

		$cF7 = $cRespuestasFaBD->consultaCompleta($cF7);

		if($cF7->getIDRESPUESTA() != "")
		{
			$sumaSecbook =0;

			$cRespuestasItems->setIDCANDIDATO($idCandidato);
			$cRespuestasItems->setIDPROCESO($idProceso);

			for($iCu=1; $iCu<=5;$iCu++){
				$cRespuestasItems->setCodigo("f9item" . $iCu . "f7");
				$sql = $respItemsBD->readListaItems($cRespuestasItems);
				$vI = $conn->Execute($sql);
				if($vI->recordCount() > 0){
//					$hDItem = new Hashtable();
					$i =0;
//					while($i<$vI.size()){
					while(!$vI->EOF){
//						$hDItem = $vI.elementAt($i);
						if($vI->fields["VALOR"] != ""){
							$sumaSecbook = $sumaSecbook + intval($vI->fields["VALOR"]);
						}
						$i++;
						$vI->MoveNext();
					}

				}
			}
			if($sumaSecbook==0){
				$sumaSecbook++;
			}
			$listaPuntSecbookX.add($sumaSecbook);
			//$listaPuntCandidatosY.add(Double.parseDouble(String.valueOf(new BigDecimal(Double.parseDouble($cF7->getPUNTSOBRE10())).setScale(2, BigDecimal.ROUND_HALF_UP))));
			$listaPuntCandidatosY.add(round($cF7->getPUNTSOBRE10(), 2, PHP_ROUND_HALF_UP));


			$iCuentaPruebas++;
		}

		if($listaPuntCandidatosY->recordCount() > 0 && $listaPuntSecbookX->recordCount() > 0)
		{
			if($listaPuntCandidatosY.size()>1 && $listaPuntSecbookX.size()>1){
				//X siempre se referirá a secbook y la Y al resto de pruebas
				$mediaX = 0;
				$mediaY = 0;

				$sumaX=0;
				$sumaY=0;

				for($i=0; $i<$listaPuntSecbookX.size();$i++){
					$sumaX = $sumaX + Double.parseDouble($listaPuntSecbookX.get($i).toString());
				}
				$mediaX = $sumaX/$listaPuntSecbookX.size();

				for($i=0; $i<$listaPuntCandidatosY.size();$i++){
					$sumaY = $sumaY + Double.parseDouble($listaPuntCandidatosY.get($i).toString());
				}
				$mediaY = $sumaY/$listaPuntCandidatosY.size();

				$dividendo=0;

				$dividendo = $sumaX - $sumaY;


				return 1-(abs($dividendo)/ (9* $listaPuntSecbookX.size()));

			}else{
				return -2;
			}
		}else{
			return -2;
		}
}


/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
