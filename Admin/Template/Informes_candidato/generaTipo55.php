<?php
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");

		$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);
		$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cEscalas_itemsDB = new Escalas_itemsDB($conn);

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
		if($nInversos>0){
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
		$aPuntuacionesDirectas = array();
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
//									echo "<br />idItem::" . $listaEscalas_items->fields['idItem'] . " - OPCION::" . $cRespuestas_pruebas_items->getIdOpcion();
									switch ($cRespuestas_pruebas_items->getIdOpcion())
									{
										case '1':	// A
											$iPd += 1;
//											echo " - puntuación:: 1";
											break;
										case '2':	// B
											$iPd += 3;
//											echo " - puntuación:: 3";
											break;
										case '3':	// C
											$iPd += 5;
//											echo " - puntuación:: 5";
											break;
										case '4':	// D
											$iPd += 7;
//											echo " - puntuación:: 7";
											break;
										default:	// Sin contestar opcion 0 en respuestas
											$iPd += 1;
//											echo " - *puntuación:: 1";
											break;
									}
//					       			$iPd = $iPd + $cRespuestas_pruebas_items->getIdOpcion();
					       		}else{
//					       			echo "<br />INVERSO::" . $listaEscalas_items->fields['idItem'];
					       			$iPd += getInversoJN36($cRespuestas_pruebas_items->getIdOpcion());
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
//				        	echo "BAREMOS";
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
//				       	echo "<br />PB::[" . $sPosi . "][" . $iPBaremada . "]";
//				       	echo "<br />*PD::>[" . $sPosi . "][" . $iPd . "]";
//				       	echo "<br />---------->" . $listaBloques->fields['nombre'] .  " - " . $listaEscalas->fields['nombre'] . " [" . $sPosi . "][PD::" . $iPd . "][PB::" . $iPBaremada . "]";
				       	$aPuntuaciones[$sPosi] =  $iPBaremada;
				       	$aPuntuacionesDirectas[$sPosi] =  $iPd;
				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
		 }
	// FIN CALCULOS GLOBALES ESCALAS

		$cNivelesjerarquicos = new Nivelesjerarquicos();
		$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
		$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		@set_time_limit(0);
		ini_set("memory_limit","1024M");

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
					<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/JN36/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/JN36/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/JN36/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>JN36</title>
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
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">' . constant("STR_SR_A") . ' '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">' . mb_strtoupper($sDescInforme, 'UTF-8') . '</td>
						    <td class="fecha">
						        <p class="textos">' . date("d/m/Y") . '
						    </td>
					    </tr>
				    </table>
				</div>
		';
		$_HEADERz='<div class="cabecera">
					<table>
						<tr>
		    				<td class="nombre">
						        <p class="textos">Sr/a '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
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
		//PORTADA
		$sHtml.= '
			<div class="pagina portada">
		    	<img src="'.constant("DIR_WS_GESTOR").'graf/JN36/portada_' . $_POST['fCodIdiomaIso2'] . '.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	';
//		    	<h1 class="titulo"><img src="'.constant("DIR_WS_GESTOR").'estilosInformes/JN36/img/logo.jpg" /></h1>';
			if($_POST['fIdTipoInforme']!=11){
				$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
			}else{
				$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . $sDescInforme . '</p></div>';
			}
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
				';
//		    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
		$sHtml.='
			</div>
			<!--FIN DIV PAGINA-->
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA

		switch ($_POST['fIdTipoInforme'])
		{
			case(55);//Informe Completo
				//FUNCIÓN PARA generar la página Narrativo
			   	$sHtml.= generaNarrativoJN36($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

			   	$sHtml.= generaNarrativoJN36_1($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_2($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_3($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_4($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_5($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_6($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_7($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_8($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

			   	$sHtml.= generaNarrativoJN36_9($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_10($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_11($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

			   	$sHtml.= generaNarrativoJN36_12($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_13($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

			   	$sHtml.= generaNarrativoJN36_14($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_15($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_16($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
//			   	$sHtml.= generaNarrativoJN36_17($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

			   	$sHtml.= generaNarrativoJN36_18($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);
			   	$sHtml.= generaNarrativoJN36_19($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

			   	$sHtml.= generaNarrativoJN36_20($aPuntuacionesDirectas, $sHtmlCab, $_POST['fCodIdiomaIso2'], $cCandidato);

				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	$sHtml.= perfilGrafico1($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);
			   	$sHtml.= perfilGrafico2($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);

				break;
		}

//		$sHtml.= '
//			<div class="pagina portada" id="contraportada">
//    			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
//			</div>
//			<!--FIN DIV PAGINA-->
//		';

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
		require_once(constant('HTML2PS_DIR') . 'config.inc.php');
		require_once(constant('HTML2PS_DIR') . 'pipeline.factory.class.php');
		$g_baseurl = constant('DIR_WS_GESTOR') . $sDirImg . $sNombre . '.html';
		$GLOBALS['g_config'] = array(
                             'compress'      => '',
                             'cssmedia'      => 'Screen',
                             'debugbox'      => '',
                             'debugnoclip'   => '',
                             'draw_page_border'	=>   '',
                             'encoding'      => '',
                             'html2xhtml'    => 1,
                             'imagequality_workaround' => '',
                             'landscape'     => '',
                             'margins'       => array(
                                                      'left'    => 15,
                                                      'right'   => 15,
                                                      'top'     => 4,
                                                      'bottom'  => 10
                                                      ),
                             'media'         => 'A4',
                             'method'        => 'fpdf',
                             'mode'          => 'html',
                             'output'        => 2,
                             'pagewidth'     => 794,
                             'pdfversion'    => '1.3',
                             'ps2pdf'        => '',
                             'pslevel'       => 3,
                             'renderfields'  => 1,
                             'renderforms'   => '',
                             'renderimages'  => 1,
                             'renderlinks'   => '',
                             'scalepoints'   => 1,
                             'smartpagebreak' => 1,
                             'transparency_workaround' => ''
                             );
		parse_config_file(constant('HTML2PS_DIR') . 'html2ps.config');
		$g_media = Media::predefined($GLOBALS['g_config']['media']);
		$g_media->set_landscape($GLOBALS['g_config']['landscape']);
		$g_media->set_margins($GLOBALS['g_config']['margins']);
		$g_media->set_pixels($GLOBALS['g_config']['pagewidth']);

		$pipeline = new Pipeline;
		$pipeline->configure($GLOBALS['g_config']);
		// Configure the fetchers
		if (extension_loaded('curl')) {
			require_once(constant('HTML2PS_DIR') . 'fetcher.url.curl.class.php');
			$pipeline->fetchers = array(new FetcherUrlCurl());
		} else {
			require_once(constant('HTML2PS_DIR') . 'fetcher.url.class.php');
			$pipeline->fetchers[] = new FetcherURL();
		};

		// Configure the data filters
		$pipeline->data_filters[] = new DataFilterDoctype();
		$pipeline->data_filters[] = new DataFilterUTF8($GLOBALS['g_config']['encoding']);
		if ($GLOBALS['g_config']['html2xhtml']) {
			$pipeline->data_filters[] = new DataFilterHTML2XHTML();
		} else {
			$pipeline->data_filters[] = new DataFilterXHTML2XHTML();
		};

		$pipeline->parser = new ParserXHTML();

		// "PRE" tree filters

		$pipeline->pre_tree_filters = array();

		$header_html    = $_HEADER;
		$footer_html    = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100%" align="center"><p style="font-size:10px;"> ' . mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . ' ' . constant("STR_JN36_PIE_INFORMES").'</p></td>
				</tr>
			</table>
			';
		//$footer_html = $footer_html;
		$filter = new PreTreeFilterHeaderFooter($header_html, $footer_html);
		$pipeline->pre_tree_filters[] = $filter;

		if ($GLOBALS['g_config']['renderfields']) {
			$pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
		};
		//

		if ($GLOBALS['g_config']['method'] === 'ps') {
			$pipeline->layout_engine = new LayoutEnginePS();
		} else {
			$pipeline->layout_engine = new LayoutEngineDefault();
		};

		$pipeline->post_tree_filters = array();

		// Configure the output format
		if ($GLOBALS['g_config']['pslevel'] == 3) {
			$image_encoder = new PSL3ImageEncoderStream();
		} else {
			$image_encoder = new PSL2ImageEncoderStream();
		};

		switch ($GLOBALS['g_config']['method']) {
			case 'fastps':
				if ($GLOBALS['g_config']['pslevel'] == 3) {
					$pipeline->output_driver = new OutputDriverFastPS($image_encoder);
				} else {
					$pipeline->output_driver = new OutputDriverFastPSLevel2($image_encoder);
				};
				break;
			case 'pdflib':
				$pipeline->output_driver = new OutputDriverPDFLIB16($GLOBALS['g_config']['pdfversion']);
				break;
			case 'fpdf':
				$pipeline->output_driver = new OutputDriverFPDF();
				break;
			case 'png':
				$pipeline->output_driver = new OutputDriverPNG();
				break;
			case 'pcl':
				$pipeline->output_driver = new OutputDriverPCL();
				break;
			default:
				die("Unknown output method");
		};

		// Setup watermark
		$watermark_text = '';
		if ($watermark_text != '') {
			$pipeline->add_feature('watermark', array('text' => $watermark_text));
		};

		if ($GLOBALS['g_config']['debugbox']) {
			$pipeline->output_driver->set_debug_boxes(true);
		}

		if ($GLOBALS['g_config']['draw_page_border']) {
			$pipeline->output_driver->set_show_page_border(true);
		}

		if ($GLOBALS['g_config']['ps2pdf']) {
			$pipeline->output_filters[] = new OutputFilterPS2PDF($GLOBALS['g_config']['pdfversion']);
		}

		if ($GLOBALS['g_config']['compress'] && $GLOBALS['g_config']['method'] == 'fastps') {
			$pipeline->output_filters[] = new OutputFilterGZip();
		}
		$process_mode='';
		if ($process_mode == 'batch') {
			$filename = "batch";
		} else {
			$filename = $g_baseurl;
		};

		switch ($GLOBALS['g_config']['output']) {
			case 0:
				$pipeline->destination = new DestinationBrowser($filename);
				break;
			case 1:
				$pipeline->destination = new DestinationDownload($filename);
				break;
			case 2:
				$pipeline->destination = new DestinationFile($filename, '');
				$pipeline->destination->set_filename($sNombre);
				break;
		};

		// Add additional requested features
		$toc_location = '';	//after o before
		if (!empty($toc_location)) {
			$pipeline->add_feature('toc', array('location' => $toc_location));
		}
		$automargins = '';
		if (!empty($automargins)) {
			$pipeline->add_feature('automargins', array());
		};

		// Start the conversion

		$time = time();
		if ($process_mode == 'batch') {
			$batch = array();

			for ($i=0; $i<count($batch); $i++) {
				if (trim($batch[$i]) != "") {
					if (!preg_match("/^https?:/",$batch[$i])) {
						$batch[$i] = "http://".$batch[$i];
					}
				};
			};

			$status = $pipeline->process_batch($batch, $g_media);
		} else {
			$status = $pipeline->process($g_baseurl, $g_media);
		};

		error_log(sprintf("Processing of '%s' completed in %u seconds", $g_baseurl, time() - $time), 3, constant("DIR_FS_PATH_NAME_LOG"));

		if ($status == null) {
			print($pipeline->error_message());
			error_log("Error in conversion pipeline", 3, constant("DIR_FS_PATH_NAME_LOG"));
			die();
		}else{
			//$cEntidad->setPdf($sDirImg . $sNombre . '.pdf');
			//$this->modificarPDF($cEntidad);
		}

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
	//Funcion que devuelve un texto a la parte del informe de competencias de JN36
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
	//Funcion que devuelve un texto a la parte del informe de competencias de JN36
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
	function getInversoJN36($valor){
		$inv=0;
		return $valor;
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
	 */
	function perfilGrafico1($aPuntuaciones,$sHtmlCab,$idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;

		$sHtml ='
			<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PSICOLÓGICO-HUMANO DE JESÚS DE NAZARET
		$sHtml.='
				<div class="desarrollo">
			        <table id="personalidad" border="1" width="730">
			          <tr>
			            <td class="azul" colspan="3" valign="middle"><h2>' . constant("STR_JN36_PERFIL_PSICOLOGICO_HUMANO_DE_JESUS_DE_NAZARET") . ' </h2></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
			            <td class="celI last" height="25"><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
			          </tr>';
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
					if (!empty($sBloques)){
						$sBloques = substr($sBloques,1);
					}
					//44,45,46,47,48,49,50
					$sBloques = "44,45";
//					echo "<br />" . $sBloques;
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
					$sSeparadorNum = '';
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
						 			$sDESC = $listaEscalas->fields['descripcion'];
			 						$aDESC = explode("<!--SEPARADOR-->",$sDESC);
							        $sHtml.='<tr>';
							        if($bPrimeraVuelta){
							        	$sHtml.='
							        	<td rowspan="' . $nEscalas . '" style="border-bottom: 4px solid #000;" bgcolor="#ffffff"><img src="'.constant("DIR_WS_GESTOR").'graf/JN36/' . $idIdioma. '/'.$aImagenesBloques[$iPosiImg].'" alt="'.$listaBloques->fields['nombre'].'" title="'.$listaBloques->fields['nombre'].'" /></td>
							        	';
							        }
							        $sSeparador = ' style="height: 55px; ';
							        if ($nVueltas == $nEscalas){
							        	$nVueltas = 1;
							        	$sSeparador .= ' border-bottom: 4px solid #000; ';
							        }

							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
						 			$sNombreEscala = $listaEscalas->fields['nombre'];
						 			$pos = strrpos($sNombreEscala, "(");
									if ($pos === false) {
									    $sNombreEscala = $listaEscalas->fields['nombre'];
									}else {
										$sNombreEscala = substr($sNombreEscala, 0, $pos);
									}
							        $sHtml.='
							        	<!--<td class="number" ' . $sSeparador . '"><p>' . $iPBaremada . '</p></td>-->
							        	<td class="tablaTitu" ' . $sSeparador . '"><p>' . $sNombreEscala . '</p></td>
										<td class="descripcion" ' . $sSeparador . '"><p>' . $aDESC[0] . '</p></td>
										';

							       	if($iPBaremada<=1){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #6dcff6;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==2){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #fff685;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==3){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #9acf8b;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==4){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #ff9c21;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada>=5){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #f49ac1;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
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
     $sHtml.='		</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
    		';

		return $sHtml;
	}

	function perfilGrafico2($aPuntuaciones,$sHtmlCab,$idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;

		/////--------- AGRUPACIONES
		global $aPuntuacionesDirectas;
		global $cBaremos_resultado;
		global $cBaremos_resultadoDB;
		//Guardo la puntuación de las escalas agrupadas
		// Son las escalas
		// [46-1] + [46-2] -> 18+19
		// [48-3] + [48-5] -> 28+30
		// [49-1] + [49-3] -> 32+34
		// [50-1] + [50-2] -> 35+36
		$_sumPD46_1_2 = $aPuntuacionesDirectas["46-1"] + $aPuntuacionesDirectas["46-2"];
		$_sumPD48_3_5 = $aPuntuacionesDirectas["48-3"] + $aPuntuacionesDirectas["48-5"];
		$_sumPD49_1_3 = $aPuntuacionesDirectas["49-1"] + $aPuntuacionesDirectas["49-3"];
		$_sumPD50_1_2 = $aPuntuacionesDirectas["50-1"] + $aPuntuacionesDirectas["50-2"];
//		echo "<br />_sumPD46_1_2::" . $_sumPD46_1_2;
//		echo "<br />_sumPD48_3_5::" . $_sumPD48_3_5;
//		echo "<br />_sumPD49_1_3::" . $_sumPD49_1_3;
//		echo "<br />_sumPD50_1_2::" . $_sumPD50_1_2;


        $cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("46");
        $cBaremos_resultado->setIdEscala("1");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD46_1_2;
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
       	$sPosi = "46-1";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;

        $cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("46");
        $cBaremos_resultado->setIdEscala("2");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD46_1_2;
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
       	$sPosi = "46-2";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;
       	//--------------
       	$cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("48");
        $cBaremos_resultado->setIdEscala("3");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD48_3_5;
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
       	$sPosi = "48-3";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;

       	$cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("48");
        $cBaremos_resultado->setIdEscala("5");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD48_3_5;
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
       	$sPosi = "48-5";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;
       	//-----------------
       	$cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("49");
        $cBaremos_resultado->setIdEscala("1");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD49_1_3;
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
       	$sPosi = "49-1";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;

       	$cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("49");
        $cBaremos_resultado->setIdEscala("3");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD49_1_3;
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
       	$sPosi = "49-3";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;
       	//--------------------
       	$cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("50");
        $cBaremos_resultado->setIdEscala("1");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD50_1_2;
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
       	$sPosi = "50-1";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;

       	$cBaremos_resultado = new Baremos_resultados();
        $cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
        $cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
        $cBaremos_resultado->setIdBloque("50");
        $cBaremos_resultado->setIdEscala("2");

        $sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
        $listaBaremos_resultado = $conn->Execute($sqlBaremos_resultado);

        $iPBaremada=0;
        $iPd = $_sumPD50_1_2;
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
       	$sPosi = "50-2";
       	$aPuntuaciones[$sPosi] =  $iPBaremada;

		///// FIN --------- AGRUPACIONES


		$sHtml ='
			<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PSICOLÓGICO-HUMANO DE JESÚS DE NAZARET
		$sHtml.='
				<div class="desarrollo">
			        <table id="personalidad" border="1" width="730">
			          <tr>
			            <td class="azul" colspan="3" valign="middle"><h2>' . constant("STR_JN36_PERFIL_PSICOLOGICO_HUMANO_DE_JESUS_DE_NAZARET") . ' </h2></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_BAJO") . '</p></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_BA") . '</p></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_MEDIO") . '</p></td>
			            <td class="celI" height="25"><p>' . constant("STR_PRISMA_ME_AL") . '</p></td>
			            <td class="celI last" height="25"><p>' . constant("STR_PRISMA_ALTO") . '</p></td>
			          </tr>';
					$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
					$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
					$aImagenesBloques = array("relacion.jpg" ,"personas.jpg", "mando.jpg", "competencias.jpg" , "potencial.jpg","competencias.jpg","potencial.jpg","flexibilidad.jpg","estiloTrabajo.jpg");
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
					if (!empty($sBloques)){
						$sBloques = substr($sBloques,1);
					}
					//44,45,46,47,48,49,50
					$sBloques = "46,47,48,49,50";
//					echo "<br />" . $sBloques;
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
					$sSeparadorNum = '';
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
						 			$sDESC = $listaEscalas->fields['descripcion'];
			 						$aDESC = explode("<!--SEPARADOR-->",$sDESC);
							        $sHtml.='<tr>';
							        if($bPrimeraVuelta){
							        	$sHtml.='
							        	<td rowspan="' . $nEscalas . '" style="border-bottom: 4px solid #000;" bgcolor="#ffffff"><img src="'.constant("DIR_WS_GESTOR").'graf/JN36/' . $idIdioma. '/'.$aImagenesBloques[$iPosiImg].'" alt="'.$listaBloques->fields['nombre'].'" title="'.$listaBloques->fields['nombre'].'" /></td>
							        	';
							        }
							        $sSeparador = ' style="';
							        if ($nVueltas == $nEscalas){
							        	$nVueltas = 1;
							        	$sSeparador .= ' border-bottom: 4px solid #000; ';
							        }

							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
						 			$sNombreEscala = $listaEscalas->fields['nombre'];
						 			$pos = strrpos($sNombreEscala, "(");
									if ($pos === false) {
									    $sNombreEscala = $listaEscalas->fields['nombre'];
									}else {
										$sNombreEscala = substr($sNombreEscala, 0, $pos);
									}
							        $sHtml.='
							        	<!--<td class="number" ' . $sSeparador . '"><p>' . $iPBaremada . '</p></td>-->
							        	<td class="tablaTitu" ' . $sSeparador . '"><p>' . $sNombreEscala . '</p></td>
										<td class="descripcion" ' . $sSeparador . '"><p>' . $aDESC[0] . '</p></td>
										';

							        if($iPBaremada<=1){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #6dcff6;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==2){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #fff685;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==3){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #9acf8b;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada==4){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #ff9c21;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
							       		';
							       	}
							       	if($iPBaremada>=5){
										$sHtml.='
										<td class="simbol" ' . $sSeparador . 'background: none repeat scroll 0 0 #f49ac1;">&nbsp;</td>
										';
							       	}else{
							       		$sHtml.='
							       		<td class="simbol" ' . $sSeparador . '">&nbsp;</td>
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
     $sHtml.='		</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
    		';

		return $sHtml;
	}


	function generaNarrativoJN36($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "44";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
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
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
			 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
	 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
			 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);
	 					$cTexto->setOrderBy("puntMin, puntMax");
			 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
//			 			echo $sqlTextos;
	 					$listaTextos = $conn->Execute($sqlTextos);

			 			if($listaTextos->recordCount()>0){

		 					while(!$listaTextos->EOF){
		 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_1($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
		            ';
				$sEscalas = "1,2";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_2($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "3,4";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_3($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "5,6";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_4($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "7,8";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_5($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "9,10";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_6($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "11,12";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_7($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "13,14";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_8($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "45";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';
				$sEscalas = "15";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_9($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "46";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
		            ';
		$sHtml.= $listaBloques->fields['descripcion'];

				$sEscalas = "1,2";
				$iPuntuacionAgrupada=0;
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
			 				if ($listaEscalas->fields['idEscala'] == 2)
							{
								$sHtml.= $aDESC[1];
							}
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
//			 			echo "<br />" . $iPuntuacion;
						$iPuntuacionAgrupada += $iPuntuacion;
						if ($listaEscalas->fields['idEscala'] == 2)
						{
//							echo "<br />SUMA::" . $iPuntuacionAgrupada;
							$iPuntuacion = $iPuntuacionAgrupada;
				 			$cTexto = new Textos_escalas();
		 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
		 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
		 					$cTexto->setCodIdiomaIso2($idIdioma);
				 			$cTexto->setIdBloque($listaEscalas->fields['idBloque']);
		 					$cTexto->setIdBloqueHast($listaEscalas->fields['idBloque']);
		 					$cTexto->setIdEscala($listaEscalas->fields['idEscala']);
				 			$cTexto->setIdEscalaHast($listaEscalas->fields['idEscala']);

				 			$sqlTextos = $cTextos_escalasDB->readLista($cTexto);
			//	 			echo $sqlTextos;
		 					$listaTextos = $conn->Execute($sqlTextos);

				 			if($listaTextos->recordCount()>0)
				 			{

			 					while(!$listaTextos->EOF){
			 						if($listaTextos->fields['puntMin'] <= $iPuntuacion && $listaTextos->fields['puntMax'] >= $iPuntuacion){
			 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
										$sHtml.= '
										<div class="contenedorC_JN36_JN">
											<div class="superiorC_JN36_JN"></div>
											<div class="perfilC_JN36_JN">
												<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
												<div class="galletaC_JN36_JN">
													<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
													<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
													<p class="colorC_JN36_JN">' . $sColor . '</p>
												</div>
												<div class="tC_JN36_JN">
													<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
												</div>
											</div>
											<div class="inferiorC_JN36_JN"></div>
										</div>
										';
			 						}
			 						$listaTextos->MoveNext();
			 					}
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_10($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "46";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "3,4";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_11($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "46";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "5";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_12($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "47";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
		            ';
		$sHtml.= $listaBloques->fields['descripcion'];

				$sEscalas = "1,2";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_13($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "47";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "3";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_14($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "48";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
		            ';
		$sHtml.= $listaBloques->fields['descripcion'];

				$sEscalas = "1";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_15($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "48";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "2";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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


/////////// Agrupacion de escala 28 y 30
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$aMejoras = array();
		$aFuertes = array();
		$i=0;
		//BLOQUES DE VALORES
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "48";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "3,5";
				$iPuntuacionAgrupada=0;
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
			 				if ($listaEscalas->fields['idEscala'] == 5)
							{
								$sHtml.= $aDESC[1];
							}
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
//			 			echo "<br />" . $iPuntuacion;
						$iPuntuacionAgrupada += $iPuntuacion;
						if ($listaEscalas->fields['idEscala'] == 5)
						{
//							echo "<br />SUMA::" . $iPuntuacionAgrupada;
							$iPuntuacion = $iPuntuacionAgrupada;
				 			$cTexto = new Textos_escalas();
		 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
		 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
		 					$cTexto->setCodIdiomaIso2($idIdioma);
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
			 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
										$sHtml.= '
										<div class="contenedorC_JN36_JN">
											<div class="superiorC_JN36_JN"></div>
											<div class="perfilC_JN36_JN">
												<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
												<div class="galletaC_JN36_JN">
													<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
													<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
													<p class="colorC_JN36_JN">' . $sColor . '</p>
												</div>
												<div class="tC_JN36_JN">
													<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
												</div>
											</div>
											<div class="inferiorC_JN36_JN"></div>
										</div>
										';
			 						}
			 						$listaTextos->MoveNext();
			 					}
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_16($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "48";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "4,6";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_17($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "48";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "6";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}


	function generaNarrativoJN36_18($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "49";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
		            ';
		$sHtml.= $listaBloques->fields['descripcion'];

				$sEscalas = "1,3";
				$iPuntuacionAgrupada=0;
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
			 				if ($listaEscalas->fields['idEscala'] == 3)
							{
								$sHtml.= $aDESC[1];
							}
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
//			 			echo "<br />" . $iPuntuacion;
						$iPuntuacionAgrupada += $iPuntuacion;
						if ($listaEscalas->fields['idEscala'] == 3)
						{
//							echo "<br />SUMA::" . $iPuntuacionAgrupada;
							$iPuntuacion = $iPuntuacionAgrupada;
				 			$cTexto = new Textos_escalas();
		 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
		 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
		 					$cTexto->setCodIdiomaIso2($idIdioma);
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
			 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
										$sHtml.= '
										<div class="contenedorC_JN36_JN">
											<div class="superiorC_JN36_JN"></div>
											<div class="perfilC_JN36_JN">
												<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
												<div class="galletaC_JN36_JN">
													<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
													<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
													<p class="colorC_JN36_JN">' . $sColor . '</p>
												</div>
												<div class="tC_JN36_JN">
													<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
												</div>
											</div>
											<div class="inferiorC_JN36_JN"></div>
										</div>
										';
			 						}
			 						$listaTextos->MoveNext();
			 					}
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_19($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "49";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
//		            ';

				$sEscalas = "2";
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
							$sHtml.= $aDESC[1];
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];

			 			$cTexto = new Textos_escalas();
	 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
	 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
	 					$cTexto->setCodIdiomaIso2($idIdioma);
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
		 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
		 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
									$sHtml.= '
									<div class="contenedorC_JN36_JN">
										<div class="superiorC_JN36_JN"></div>
										<div class="perfilC_JN36_JN">
											<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
											<div class="galletaC_JN36_JN">
												<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
												<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
												<p class="colorC_JN36_JN">' . $sColor . '</p>
											</div>
											<div class="tC_JN36_JN">
												<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
											</div>
										</div>
										<div class="inferiorC_JN36_JN"></div>
									</div>
									';
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
    		';

     return $sHtml;
	}

	function generaNarrativoJN36_20($aPuntuaciones, $sHtmlCab, $idIdioma, $cCandidato)
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
//		$sBloques = "44,45,46,47,48,49,50";
		$sBloques = "50";
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
//				$sHtml.= '
//		        	<h2 class="tit_inf_JN36_JN" >' . constant("STR_JN36_INFORME_PERSONAL_E_INDIVIDUALIZADO") . '</h2>
//		            ';


		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$sBloqueAnterior = $listaBloques->fields['nombre'];
				$sHtml.= '
		        	<h2 class="tit_inf_JN36_JN" >' . $sBloqueAnterior . '</h2>
		            ';

				$sEscalas = "1,2";
				$iPuntuacionAgrupada=0;
				$cEscalas = new Escalas();
			 	$cEscalas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
			 	$cEscalas->setIdEscala($sEscalas);
			 	$cEscalas->setOrderBy("idEscala");
			 	$cEscalas->setOrder("ASC");
			 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//				echo "<br />" . $sqlEscalas;
			 	$listaEscalas = $conn->Execute($sqlEscalas);
			 	$nEscalas=$listaEscalas->recordCount();
			 	if($nEscalas > 0){
			 		$iEsalas=0;
			 		while(!$listaEscalas->EOF){
			 			$sDESC = $listaEscalas->fields['descripcion'];
			 			$aDESC = explode("<!--SEPARADOR-->",$sDESC);
			 			$sEscala = mb_strtoupper($listaEscalas->fields['nombre'], 'UTF-8');
			 			if (($sEscala != $sBloqueAnterior) || ($iEsalas > 0)){
			 				$sHtml.= '
		        				<h2 class="subtitulo_JN36" >' . $sEscala . '</h2>
		            		';
//							$sHtml.= '<p class="textos_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaEscalas->fields['descripcion']) . '</p>';
			 				if ($listaEscalas->fields['idEscala'] == 2)
							{
								$sHtml.= $aDESC[1];
							}
			 			}
			 			$iPuntuacion = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
//			 			echo "<br />" . $iPuntuacion;
						$iPuntuacionAgrupada += $iPuntuacion;
						if ($listaEscalas->fields['idEscala'] == 2)
						{
//							echo "<br />SUMA::" . $iPuntuacionAgrupada;
							$iPuntuacion = $iPuntuacionAgrupada;

				 			$cTexto = new Textos_escalas();
		 					$cTexto->setIdPrueba($_POST['fIdPrueba']);
		 					$cTexto->setIdPruebaHast($_POST['fIdPrueba']);
		 					$cTexto->setCodIdiomaIso2($idIdioma);
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
			 							$sZona = getZona($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sColor = getColor($listaTextos->fields['puntMin'], $listaTextos->fields['puntMax'], $iPuntuacion);
			 							$sLitMax = getLiterarMax($listaTextos->fields['puntMax']);
										$sHtml.= '
										<div class="contenedorC_JN36_JN">
											<div class="superiorC_JN36_JN"></div>
											<div class="perfilC_JN36_JN">
												<div class="tituloC_JN36_JN">' . constant("STR_JN36_TU_PERFIL") . '</div>
												<div class="galletaC_JN36_JN">
													<p class="porcentajeC_JN36_JN">' . $sZona . '</p>
													<p class="zonaC_JN36_JN">' . $listaTextos->fields['puntMin'] . $sLitMax . ' ' . constant("STR_JN36_PUNTOS") . '</p>
													<p class="colorC_JN36_JN">' . $sColor . '</p>
												</div>
												<div class="tC_JN36_JN">
													<div class="textosC_JN36_JN">' . str_replace("@usuario@" , $cCandidato->getNombre() , $listaTextos->fields['texto']) . '</div>
												</div>
											</div>
											<div class="inferiorC_JN36_JN"></div>
										</div>
										';
			 						}
			 						$listaTextos->MoveNext();
			 					}
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
    		';

     return $sHtml;
	}


	function getZona($iPuntMin, $iPuntMax, $iPuntuacion)
	{
		if($iPuntMin == 4 && $iPuntMax == 8){
			return constant("STR_JN36_ZONA_BAJA");
		}
		if($iPuntMin == 9 && $iPuntMax == 13){
			return constant("STR_JN36_ZONA_MEDIA_BAJA");
		}
		if($iPuntMin == 14 && $iPuntMax == 18){
			return constant("STR_JN36_ZONA_MEDIA");
		}
		if($iPuntMin == 19 && $iPuntMax == 23){
			return constant("STR_JN36_ZONA_MEDIA_ALTA");
		}
		if($iPuntMin == 24 && $iPuntMax == 999){
			return constant("STR_JN36_ZONA_ALTA");
		}

		if($iPuntMin == 8 && $iPuntMax == 26){
			return constant("STR_JN36_ZONA_MEDIA");
		}
		if($iPuntMin == 27 && $iPuntMax == 36){
			return constant("STR_JN36_ZONA_MEDIA_ALTA");
		}
		if($iPuntMin == 37 && $iPuntMax == 999){
			return constant("STR_JN36_ZONA_ALTA");
		}

		if($iPuntMin == 4 && $iPuntMax == 13){
			return constant("STR_JN36_ZONA_MEDIA");
		}
		if($iPuntMin == 14 && $iPuntMax == 18){
			return constant("STR_JN36_ZONA_MEDIA_ALTA");
		}
		if($iPuntMin == 19 && $iPuntMax == 999){
			return constant("STR_JN36_ZONA_ALTA");
		}

		if($iPuntMin == 8 && $iPuntMax == 26){
			return constant("STR_JN36_ZONA_MEDIA");
		}
		if($iPuntMin == 27 && $iPuntMax == 36){
			return constant("STR_JN36_ZONA_MEDIA_ALTA");
		}
		if($iPuntMin == 37 && $iPuntMax == 999){
			return constant("STR_JN36_ZONA_ALTA");
		}

	}
	function getColor($iPuntMin, $iPuntMax, $iPuntuacion)
	{
		if($iPuntMin == 4 && $iPuntMax == 8){
			return constant("STR_JN36_COLOR_AZUL");
		}
		if($iPuntMin == 9 && $iPuntMax == 13){
			return constant("STR_JN36_COLOR_AMARILLO");
		}
		if($iPuntMin == 14 && $iPuntMax == 18){
			return constant("STR_JN36_COLOR_VERDE");
		}
		if($iPuntMin == 19 && $iPuntMax == 23){
			return constant("STR_JN36_COLOR_NARANJA");
		}
		if($iPuntMin == 24 && $iPuntMax == 999){
			return constant("STR_JN36_COLOR_ROSA");
		}

		if($iPuntMin == 8 && $iPuntMax == 26){
			return constant("STR_JN36_COLOR_AMARILLO");
		}
		if($iPuntMin == 27 && $iPuntMax == 36){
			return constant("STR_JN36_COLOR_VERDE");
		}
		if($iPuntMin == 37 && $iPuntMax == 999){
			return constant("STR_JN36_COLOR_ROSA");
		}

		if($iPuntMin == 4 && $iPuntMax == 13){
			return constant("STR_JN36_COLOR_AMARILLO");
		}
		if($iPuntMin == 14 && $iPuntMax == 18){
			return constant("STR_JN36_COLOR_VERDE");
		}
		if($iPuntMin == 19 && $iPuntMax == 999){
			return constant("STR_JN36_COLOR_ROSA");
		}

		if($iPuntMin == 8 && $iPuntMax == 26){
			return constant("STR_JN36_COLOR_AMARILLO");
		}
		if($iPuntMin == 27 && $iPuntMax == 36){
			return constant("STR_JN36_COLOR_VERDE");
		}
		if($iPuntMin == 37 && $iPuntMax == 999){
			return constant("STR_JN36_COLOR_ROSA");
		}
	}

	function getLiterarMax($iPuntMax){
		if($iPuntMax >= 999){
			return constant("STR_JN36_O_MAS");
		}else{
			return "-" . $iPuntMax;
		}
	}

/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>
