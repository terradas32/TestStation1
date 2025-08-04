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
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
		}
//		echo "<br />222-->sBloques::" . $sBloques;
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
//									echo "<br />DescItem::" . $cRespuestas_pruebas_items->getDescItem();
//									echo "<br />Valor::" . $cRespuestas_pruebas_items->getValor();
									if ($cRespuestas_pruebas_items->getValor() != ""){
										$iPd += $cRespuestas_pruebas_items->getValor();
									}
					       		}else{
//					       			echo "<br />" . $listaEscalas_items->fields['idItem'];
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
				        }else{
				        	//No tiene puntuación baremada
				        	$iPBaremada = $iPd;
				        }

				       	$sPosi = $listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala'];
//				       	echo "<br />---------->[" . $sPosi . "][" . $iPBaremada . "]";
//				       	echo "<br />---------->[" . $listaEscalas->fields['nombre'] . "][" . $sPosi . "][" . $iPBaremada . "]";

						switch ($sPosi)
						{
							case "60-3":	// [Adecuar productos][60-3]
							case "60-5":	// [Argumentación comercial][60-5]
								$iPBaremada = ceil($iPBaremada/1.4);
								break;
							case "60-6":	// [Sistemática Comercial][60-6]
								$iPBaremada = ceil($iPBaremada/1.2);
								break;
						}

				       	$aPuntuaciones[$sPosi] =  $iPBaremada;
//				       	echo "<br />---------->[" . $listaEscalas->fields['nombre'] . "][" . $sPosi . "][" . $iPBaremada . "]";
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
				        		if ($cRespuestas_pruebas_items->getValor() != ""){
									$iPdCompetencias += $cRespuestas_pruebas_items->getValor();
								}
//								switch ($cRespuestas_pruebas_items->getIdOpcion())
//								{
//									case '1':	// Mejor
//										$iPdCompetencias += 2;
//										break;
//									case '2':	// Peor
//										$iPdCompetencias += 0;
//										break;
//									default:	// Sin contestar opcion 0 en respuestas
//										$iPdCompetencias += 1;
//										break;
//								}
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
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/game/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/game/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/game/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>Game</title>
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
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/game/img/logo-pequenio.jpg" title="logo"/>
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
		$sDescInforme = $cTextos_secciones->getTexto();

		//PORTADA
		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/game/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/game/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_nombre_infome"><p>' . $cPruebas->getNombre() . '</p></div>';
		$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_puntos_infome"><p>' . str_repeat(".", 40) . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
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

				    $consistencia = baremo_C(number_format(sqrt($iPGlobal/$nEscalas)*100 ,0));
//echo "G.C.::" . $consistencia;
//// FIN de consistencia GENERAL
	    $aSQLPuntuacionesPPL =  array();
	    $aSQLPuntuacionesC =  array();
		switch ($_POST['fIdTipoInforme'])
		{
			case(1);//Perfil Básico + Perfil General
				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	$sHtml.= perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);

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
		global $cCandidato;

		global $aSQLPuntuacionesPPL;

		$sSQLExport ="";
		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;

		//PÁGINA INTRODUCCIÓN, 1
		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL
		$sHtml.='
				<div class="desarrollo">
			        <table id="personalidad" border="1">
			          <tr>
			            <td class="azul" colspan="4" rowspan="2" valign="middle"><h2> </h2></td>
			            <td class="celS" ><p>1</p></td>
			            <td class="celS" ><p>2</p></td>
			            <td class="celS" ><p>3</p></td>
			            <td class="celS" ><p>4</p></td>
			            <td class="celS last" ><p>5</p></td>
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
					$aImagenesBloques = array("gestionComercial.jpg" ,"controlEmocional.jpg", "relacion.jpg", "personas.jpg" , "mando.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
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
                        <img src="'.constant("DIR_WS_GESTOR").'graf/game/' . $idIdioma. '/'.$aImagenesBloques[$iPosiImg].'" alt="'.$listaBloques->fields['nombre'].'" title="'.$listaBloques->fields['nombre'].'" />
                        </td>
							        	';
							        }
							        $sSeparador = '';
							        if ($nVueltas == $nEscalas){
							        	$nVueltas = 1;
							        	$sSeparador = ' style="border-bottom: 2px solid #8796AA;" ';
							        }

							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

							        $sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
							        $sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($listaBloques->fields['idBloque'], false) . "," . $conn->qstr($listaBloques->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['idEscala'], false) . "," . $conn->qstr($listaEscalas->fields['nombre'], false) . "," . $conn->qstr($listaEscalas->fields['descripcion'], false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
							        $aSQLPuntuacionesPPL[] = $sSQLExport;

							        $sHtml.='
							        	<td class="number" ' . $sSeparador . '><p>'.$iPBaremada.'</p></td>
							        	<td class="tablaTitu" ' . $sSeparador . '><p>' . $listaEscalas->fields['nombre'] . '</p></td>
										<td class="descripcion" ' . $sSeparador . '><p>' . getNarrativoGame($aPuntuaciones,$idIdioma,$cCandidato, $listaBloques->fields['idBloque'], $listaEscalas->fields['idEscala'], $iPBaremada) . '</p></td>
										';

							       	if($iPBaremada==1){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/game/graficasBajo.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==2){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/game/graficasMB.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==3){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/game/graficasMedio.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==4){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/game/graficasMA.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '>&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==5){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . '><img src="'.constant("DIR_WS_GESTOR").'graf/game/graficasAlto.JPG" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" /></td>
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

			     $sHtml.='
					</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
        <hr>
    		';

		return $sHtml;
	}
	function getNarrativoGame($aPuntuaciones,$idIdioma,$cCandidato, $_idBloque, $_idEscala, $_puntuacion){
		global $conn;

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Textos_escalas/Textos_escalas.php");

		$cTextos_escalasDB = new Textos_escalasDB($conn);

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;

		$iPosiImg=0;
		$iPGlobal = 0;
		$sTexto = "";

	 			$cTexto = new Textos_escalas();
	 			$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 			$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 			$cTexto->setIdBloque($_idBloque);
	 			$cTexto->setIdBloqueHast($_idBloque);
	 			$cTexto->setIdEscala($_idEscala);
	 			$cTexto->setIdEscalaHast($_idEscala);

	 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
	 			$listaTextos = $conn->Execute($sqlTextos);
//	 			echo "<br />::--::" . $sqlTextos;

	 			if($listaTextos->recordCount()>0){
	 				while(!$listaTextos->EOF){
	 					if($listaTextos->fields['puntMin'] <= $_puntuacion && $listaTextos->fields['puntMax'] >= $_puntuacion){
	 						$sTexto.= str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']);
	 					}
	 					$listaTextos->MoveNext();
	 				}
	 			}

		return $sTexto;
	}
/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
