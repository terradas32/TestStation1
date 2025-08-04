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
					       			$iPd += getInversoCel16($cRespuestas_pruebas_items->getIdOpcion());
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
//				       	echo "<br />---------->[" . $sPosi . "]";
				       	$aPuntuaciones[$sPosi] =  $iPBaremada;
				       	
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
//		@set_time_limit(-1);
		ini_set("memory_limit","1024M");
//		ini_set("max_execution_time","600");
		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		define ('_NEWPAGE', '<!--NewPage-->');
		$_HEADER = '';
		$sHtmlCab	= '';
		$sHtml		= '';
		$sHtmlFin	= '';
		//$aux			= $this->conn;
		
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/resetCSS.css"/>';
		$sHtmlInicio.= '<link rel="stylesheet" type="text/css" href="'.constant("DIR_WS_GESTOR").'estilosInformes/cel16/style.css"/>';
		$sHtmlInicio.='
					<title>cel16</title>
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
						        <p class="textos">Sr/a '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/cel16/img/logo-informe.jpg" title="logo"/>
						    </td>
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
		    	<img src="'.constant("DIR_WS_GESTOR").'graf/cel16/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="'.constant("DIR_WS_GESTOR").'estilosInformes/cel16/img/logo.jpg" /></h1>';
		$sHtml.= '<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
			</div>
			<!--FIN DIV PAGINA-->
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA
		$idTipoInforme = $_POST['fIdTipoInforme'];
		switch ($idTipoInforme)
		{
			case(8);//Informe Estilos I
				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	$sHtml.= perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);

			   	break;
			case(9);//Informe Estilos II
				//FUNCIÓN PARA generar la página de perfil de personalidad general
			   	$sHtml.= perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$_POST['fCodIdiomaIso2']);
				$sHtml.= generaPerfilEstilos($aPuntuaciones , $sHtmlCab);
			   	break;
		}
		
		$sHtml.= '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
			</div>
			<!--FIN DIV PAGINA-->
		';
		
		
	if (!empty($sHtml))
	{
		$replace = array('@', '.');
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" .$_POST['fIdTipoInforme'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
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
                                                      'top'     => 14,
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
					<td width="100%" align="center"><p style="font-size:10px;"> '. strtoupper($cPruebas->getNombre()) .' '.constant("STR_PIE_INFORMES").'</p></td>
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
		
/******************************************************************
* Funciones para la generación del Informe
******************************************************************/
					
	function baremo_C($pd)
	{
		if ($pd <=120){ $baremo_C=1;}
		if ($pd >=121 && $pd <=139){$baremo_C=2;}
		if ($pd >=140 && $pd <=158){$baremo_C=3;}
		if ($pd >=159 && $pd <=177){$baremo_C=4;}
		if ($pd >=178 && $pd <=195){$baremo_C=5;}
		if ($pd >=196 && $pd <=214){$baremo_C=6;}
		if ($pd >=215 && $pd <=233){$baremo_C=7;}
		if ($pd >=234 && $pd <=252){$baremo_C=8;}
		if ($pd >=253 && $pd <=270){$baremo_C=9;}
		if ($pd >=271){ $baremo_C=10;}	
		return $baremo_C;
	}

	//-------------------------------
	// Baremos para la segunda parte
	//-------------------------------
	//escala I: Estilo Impulsor antes Innovar - Arriesgar
	function baremo_celB1($pd){
		if ($pd <=1){	$baremo_celB1=1;	}
		if ($pd ==2){	$baremo_celB1=2;	}
		if ($pd ==3){	$baremo_celB1=3;	}
		if ($pd ==4){	$baremo_celB1=4;	}
		if ($pd ==5){	$baremo_celB1=5;	}
		if ($pd ==6){	$baremo_celB1=6;	}
		if ($pd ==7){	$baremo_celB1=7;	}
		if ($pd ==8){	$baremo_celB1=8;	}
		if ($pd ==9){	$baremo_celB1=9;	}
		if ($pd >=10){	$baremo_celB1=10;	}
		return $baremo_celB1;
	}
	
	//escala T: Estilo Teórico antes Teorizar - Sistematizar
	function baremo_celB2($pd){
		if ($pd <=1){	$baremo_celB2=1;	}
		if ($pd ==2){	$baremo_celB2=2;	}
		if ($pd ==3){	$baremo_celB2=3;	}
		if ($pd ==4){	$baremo_celB2=4;	}
		if ($pd ==5){	$baremo_celB2=5;	}
		if ($pd ==6){	$baremo_celB2=6;	}
		if ($pd ==7){	$baremo_celB2=7;	}
		if ($pd ==8){	$baremo_celB2=8;	}
		if ($pd ==9){	$baremo_celB2=9;	}
		if ($pd >=10){	$baremo_celB2=10;	}
		return $baremo_celB2;
	}
	
	//escala A: Estilo Analítico antes Analizar - Observar
	function baremo_celB3($pd){
		if ($pd <=1){	$baremo_celB3=1;	}
		if ($pd ==2){	$baremo_celB3=2;	}
		if ($pd ==3){	$baremo_celB3=3;	}
		if ($pd ==4){	$baremo_celB3=4;	}
		if ($pd ==5){	$baremo_celB3=5;	}
		if ($pd ==6){	$baremo_celB3=6;	}
		if ($pd ==7){	$baremo_celB3=7;	}
		if ($pd ==8){	$baremo_celB3=8;	}
		if ($pd ==9){	$baremo_celB3=9;	}
		if ($pd >=10){	$baremo_celB3=10;	}
		return $baremo_celB3;
	}
	
	//escala P: Estilo Operativo antes Practicar - Actuar
	function baremo_celB4($pd){
		if ($pd <=1){	$baremo_celB4=1;	}
		if ($pd ==2){	$baremo_celB4=2;	}
		if ($pd ==3){	$baremo_celB4=3;	}
		if ($pd ==4){	$baremo_celB4=4;	}
		if ($pd ==5){	$baremo_celB4=5;	}
		if ($pd ==6){	$baremo_celB4=6;	}
		if ($pd ==7){	$baremo_celB4=7;	}
		if ($pd ==8){	$baremo_celB4=8;	}
		if ($pd ==9){	$baremo_celB4=9;	}
		if ($pd >=10){	$baremo_celB4=10;	}
		return $baremo_celB4;
	}

	// Si llega MEJOR devolver 0
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 1
	function getInversoCel16($valor){	
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
		return $inv;
	}
	
	/*
	 * INTERPRETACIÓN DE LA ESCALA DE CONSISTENCIA
	 * PERFIL DE PERSONALIDAD LABORAL
	 *  
	 */
	function perfilPersonalidadLaboral($aPuntuaciones,$sHtmlCab,$idIdioma)
	{

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;

		global $cTextos_secciones;
		global $cTextos_seccionesDB;
		global $idTipoInforme;
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("10");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
		
		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		// PÁGINA PERFIL PERSONALIDAD LABORAL
		$sHtml.='
				<div class="desarrollo">
			        <table id="personalidad" border="1" width="730">
			          <tr>
			            <td class="azul" colspan="3" valign="middle"><h2>' . $cTextos_secciones->getTexto() . '</h2></td>
			          </tr>';
					// Estilo Motivacional
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
						while(!$listaBloques->EOF){
							 		
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
						 		$bPrimeraVuelta = true;
						 		while(!$listaEscalas->EOF){
							        if($bPrimeraVuelta){
							        	$sHtml.='<tr>';
							        	$sHtml.='
							        		<td class="tablaTitu" ><p>' . strtoupper($listaBloques->fields['nombre']) . '</p></td>
							        		<td class="tablaTitu" style="font-size:9px;">
								        		<span style="float:left;margin-left: -9px;padding-right: 8.2%;">0</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">1</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">2</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">3</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">4</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">5</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">6</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">7</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">8</span>
								        		<span style="float:left;margin: 0;padding-right: 8.2%;">9</span>
								        		<span style="float:right;margin-right: -9px;padding: 0;">10</span>
								        	</td>
							        		<td class="tablaTitu">&nbsp;</td>
							        	';
							        	$sHtml.='</tr>';
							        }
							        $sHtml.='<tr>';
							        $iPBaremada = $aPuntuaciones[$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
							        $sDESC = $listaEscalas->fields['descripcion'];
							        $aDESC = explode("<!--SEPARADOR-->",$sDESC);
							        $sHtml.='
							        	<td class="descripcion"><p>' . $aDESC[0] . '</p></td>
							        	<td class="number"><span class="aPersonas" style="width:' . (($iPBaremada)*10) . '%;"><img src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/barra_gris.jpg" height="21px" width="100%" /><p>' . $iPBaremada . ' ' . $listaEscalas->fields['nombre'] . '</p></span></td>
										<td class="descripcion"><p>' . $aDESC[1] . '</p></td>
										';
			
							       	$sHtml.='
							       	</tr>
							       	';
							       	$bPrimeraVuelta = false;
							        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
							        $listaEscalas->MoveNext();
						 		}
						 	}
						 	$iPosiImg++;
						 	$listaBloques->MoveNext();
						 }
					 }
			
				    $consistencia = baremo_C(number_format(sqrt($iPGlobal/16)*100 ,0));
					$cTextos_secciones = new Textos_secciones();
					$cTextos_secciones->setIdSeccion("12");
					$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
					$cTextos_secciones->setIdTipoInforme($idTipoInforme);
					$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
		        	$sHtml.='<tr>';
		        	$sHtml.='
		        		<td class="tablaTitu" ><p>' . $cTextos_secciones->getTexto() . '</p></td>
		        		<td class="tablaTitu" style="font-size:9px;">
			        		<span style="float:left;margin-left: -9px;padding-right: 8.2%;">0</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">1</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">2</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">3</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">4</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">5</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">6</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">7</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">8</span>
			        		<span style="float:left;margin: 0;padding-right: 8.2%;">9</span>
			        		<span style="float:right;margin-right: -9px;padding: 0;">10</span>
			        	</td>
		        		<td class="tablaTitu">&nbsp;</td>
		        	';
					$cTextos_secciones = new Textos_secciones();
					$cTextos_secciones->setIdSeccion("13");
					$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
					$cTextos_secciones->setIdTipoInforme($idTipoInforme);
					$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
					$sDESC = $cTextos_secciones->getTexto();
					$aDESC = explode("<!--SEPARADOR-->",$sDESC);
		        	$sHtml.='</tr>';
			        $sHtml.='
						<tr>
			        	<td class="descripcion"><p>' . $aDESC[0] . '</p></td>
			        	<td class="number"><span class="aPersonas" style="width:' . (($consistencia)*10) . '%;"><img style="overflow: hidden;" src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/barra_gris.jpg" height="21px" width="100%" /><p>' . $consistencia . '</p></span></td>
						<td class="descripcion"><p>' . $aDESC[1] . '</p></td>
						';
		        	$sHtml.='</tr>';
			     $sHtml.='
					</table>
				</div>
				<!--FIN DIV DESARROLLO-->
    		</div>
    		<!--FIN DIV PAGINA-->
    		';
     
		return $sHtml;
	}
	
	
	function generaPerfilEstilos($aPuntuaciones , $sHtmlCab){
		global $conn;
		
		global $cTextos_secciones;
		global $cTextos_seccionesDB;
		global $idTipoInforme;
		
		$sSQL_Comun = "AND idEmpresa = " . $_POST['fIdEmpresa'] . " ";
		$sSQL_Comun .= "AND idProceso = " . $_POST['fIdProceso'] . " ";
		$sSQL_Comun .= "AND idCandidato = " . $_POST['fIdCandidato'] . " ";
		$sSQL_Comun .= "AND idPrueba = " . $_POST['fIdPrueba'] . " ";
		$sSQL_Comun .= "AND codIdiomaIso2 = '" . $_POST['fCodIdiomaIso2Prueba'] . "' ";
		
		//Sacamos las puntuciones directas de las escalas de la segunda parte
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("17");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala1 = $cTextos_secciones->getTexto();

		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("21");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala1B = $cTextos_secciones->getTexto();
		
		//Este array lo sacaba en la antigua por posición,
		//nosotros lo sacamos por item
//		$arrEscala1 = Array(2,5,14,19,23,28,29,32,36,38);
		$arrEscala1 = Array(99,104,117,125,131,138,140,144,150,152);
		$sSQL = "SELECT SUM(idOpcion) AS SUMA FROM respuestas_pruebas_items ";
		$sSQL .= "WHERE idItem IN (" . implode(",", $arrEscala1) . ") ";
        $sSQL .= $sSQL_Comun;
//        echo "<br />" . $sSQL;
		$rs1 = $conn->Execute($sSQL);
		$iV1 = baremo_celB1($rs1->fields['SUMA']);
//		echo "<br />" . $iV1;
//		exit;
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("18");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala2 = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("22");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala2B = $cTextos_secciones->getTexto();
		
//		$arrEscala2 = Array(8,11,22,24,26,30,33,35,37,39); 
		$arrEscala2 = Array(108,113,129,132,135,141,146,149,151,153); 
		$sSQL = "SELECT SUM(idOpcion) AS SUMA FROM respuestas_pruebas_items ";
		$sSQL .= "WHERE idItem IN (" . implode(",", $arrEscala2) . ") ";
        $sSQL .= $sSQL_Comun;
		$rs2 = $conn->Execute($sSQL);
		$iV2 = baremo_celB2($rs2->fields['SUMA']);
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("19");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala3 = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("23");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala3B = $cTextos_secciones->getTexto();
		
//		$arrEscala3 = Array(1,3,10,12,13,16,17,25,27,40);  
		$arrEscala3 = Array(98,101,111,114,116,120,122,134,137,154);
		$sSQL = "SELECT SUM(idOpcion) AS SUMA FROM respuestas_pruebas_items ";
		$sSQL .= "WHERE idItem IN (" . implode(",", $arrEscala3) . ") ";
        $sSQL .= $sSQL_Comun;
		$rs3 = $conn->Execute($sSQL);
		$iV3 = baremo_celB3($rs3->fields['SUMA']);
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("20");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala4 = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("24");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$txtEscala4B = $cTextos_secciones->getTexto();
		
//		$arrEscala4 = Array(4,6,7,9,15,18,20,21,31,34);  
		$arrEscala4 = Array(102,105,107,110,119,123,126,128,143,147);
		$sSQL = "SELECT SUM(idOpcion) AS SUMA FROM respuestas_pruebas_items ";
		$sSQL .= "WHERE idItem IN (" . implode(",", $arrEscala4) . ") ";
        $sSQL .= $sSQL_Comun;
		$rs4 = $conn->Execute($sSQL);
		$iV4 = baremo_celB4($rs4->fields['SUMA']);
		
		$sHtml= '
			<div class="pagina">'. $sHtmlCab;
		
		// PÁGINA PERFIL DE ESTILOS
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("14");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sTitEstilo_Aprendizaje = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("15");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sEstilo_Operativo = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("25");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sEstilo_Impulsor = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("26");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sEstilo_Teorico = $cTextos_secciones->getTexto();
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("27");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sEstilo_Analitico = $cTextos_secciones->getTexto();
		
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("16");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($_POST['fIdPrueba']);
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sAPRENDERAN_MEJOR_CUANDO = $cTextos_secciones->getTexto();
		
		
		$sHtml.='
			<div class="desarrollo">
	        	<table id="personalidad" width="730" border="1" cellspacing="0" cellpadding="0">
	        		<tr>
		            	<td valign="middle" colspan="3" class="azul"><h2>' . $sTitEstilo_Aprendizaje . '</h2></td>
		          	</tr>
					<tr>
		        		<td class="tablaTitu"><p>' . $sEstilo_Operativo . '</p></td>
		        		<td style="font-size:9px;" class="tablaTitu">
		        			<span style="float:left;margin-left: -6px;padding-right: 7.3%;">0</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">1</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">2</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">3</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">4</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">5</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">6</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">7</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">8</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">9</span>
			        		<span style="float:right;margin-right: -8px;padding: 0;">10</span>
		        		</td>
		        		<td class="tablaTitu" style="text-align: center;">' . $sAPRENDERAN_MEJOR_CUANDO . '</td>
		        	</tr>
					<tr>
			        	<td class="descripcion" nowrap="nowrap" style="font-size:14px;font-weight: bold;"><p>' . $txtEscala1 . '</p></td>
			        	<td class="number"><span class="aPersonas" style="width:' . (($iV1)*10) . '%;"><img style="overflow: hidden;" src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/barra_gris.jpg" height="21px" width="100%" /><p>' . $iV1 . '</p></span></td>
						<td class="descripcion" width="50%" style="font-size: 12px;padding: 10px;"><p>' . $txtEscala1B . '</p></td>
					</tr>
					<tr>
		        		<td class="tablaTitu"><p>' . $sEstilo_Impulsor . '</p></td>
		        		<td style="font-size:9px;" class="tablaTitu">
		        			<span style="float:left;margin-left: -6px;padding-right: 7.3%;">0</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">1</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">2</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">3</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">4</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">5</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">6</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">7</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">8</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">9</span>
			        		<span style="float:right;margin-right: -8px;padding: 0;">10</span>
		        		</td>
		        		<td class="tablaTitu" style="text-align: center;">' . $sAPRENDERAN_MEJOR_CUANDO . '</td>
		        	</tr>
					<tr>
			        	<td class="descripcion" style="font-size:14px;font-weight: bold;"><p>' . $txtEscala2 . '</p></td>
			        	<td class="number"><span class="aPersonas" style="width:' . (($iV2)*10) . '%;"><img style="overflow: hidden;" src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/barra_gris.jpg" height="21px" width="100%" /><p>' . $iV2 . '</p></span></td>
						<td class="descripcion" style="font-size: 12px;padding: 10px;"><p>' . $txtEscala2B . '
					</p></td>
					</tr>
					<tr>
		        		<td class="tablaTitu"><p>' . $sEstilo_Teorico . '</p></td>
		        		<td style="font-size:9px;" class="tablaTitu">
		        			<span style="float:left;margin-left: -6px;padding-right: 7.3%;">0</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">1</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">2</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">3</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">4</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">5</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">6</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">7</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">8</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">9</span>
			        		<span style="float:right;margin-right: -8px;padding: 0;">10</span>
		        		</td>
		        		<td class="tablaTitu" style="text-align: center;">' . $sAPRENDERAN_MEJOR_CUANDO . '</td>
		        	</tr>
					<tr>
			        	<td class="descripcion" style="font-size:14px;font-weight: bold;"><p>' . $txtEscala3 . '</p></td>
			        	<td class="number"><span class="aPersonas" style="width:' . (($iV3)*10) . '%;"><img style="overflow: hidden;" src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/barra_gris.jpg" height="21px" width="100%" /><p>' . $iV3 . '</p></span></td>
						<td class="descripcion" style="font-size: 12px;padding: 10px;"><p>' . $txtEscala3B . '</p></td>
					</tr>
					<tr>
		        		<td class="tablaTitu"><p>' . $sEstilo_Analitico . '</p></td>
		        		<td style="font-size:9px;" class="tablaTitu">
		        			<span style="float:left;margin-left: -6px;padding-right: 7.3%;">0</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">1</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">2</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">3</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">4</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">5</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">6</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">7</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">8</span>
			        		<span style="float:left;margin: 0;padding-right: 7.3%;">9</span>
			        		<span style="float:right;margin-right: -8px;padding: 0;">10</span>
		        		</td>
		        		<td class="tablaTitu" style="text-align: center;">' . $sAPRENDERAN_MEJOR_CUANDO . '</td>
		        	</tr>
					<tr>
			        	<td class="descripcion" style="font-size:14px;font-weight: bold;"><p>' . $txtEscala4 . '</p></td>
			        	<td class="number"><span class="aPersonas" style="width:' . (($iV4)*10) . '%;"><img style="overflow: hidden;" src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/cel16/barra_gris.jpg" height="21px" width="100%" /><p>' . $iV4 . '</p></span></td>
						<td class="descripcion" style="font-size: 12px;padding: 10px;"><p>' . $txtEscala4B . '</p></td>
					</tr>
            	</table>
			</div><!--FIN DIV DESARROLLO-->
      	</div>
      	<!--FIN DIV PAGINA-->';
			return $sHtml;
	}
/******************************************************************
* FIN Funciones para la generación del Informe
******************************************************************/
?>