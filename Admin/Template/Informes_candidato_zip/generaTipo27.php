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
		
		//Posicion 0 ->Interes MUY ELEVADO
		//Posicion 1 ->Interes ELEVADO
		//Posicion 2 ->Interes MODERADO
		//Posicion 3 ->Interes BAJO
		//Posicion 4 ->Interes NINGUNO
		$aInteresaCarreras = array("","","","","");
		/*	Carreras con un porcentage igual o superior al 75%	 */
		$sInteresaCarreras75 = "";

		//Definicion de aptitudes por carrera (idTipoCompetencia+idCompetencia)
		$aAptitudesVERVAL = array(51,52,53,61,71,81,82,83,91,92,93,101,102,121,151,152);
		$aAptitudesNUMERICO = array(72,73,74,91,92,131,132,141,142,151,152,153);
		$aAptitudesESPACIAL = array(74,131,132,141,142);
		$aAptitudesLOGICA = array(51,52,53,61,71,72,73,74,81,82,83,93,101,102,121,132,151,152,153);
		
		$iVERBAL = 0;
		$iLOGICO = 0;
		$iNUMERICO = 0;
		$iESPACIAL = 0;
		
		$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);
		$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
		$cBloquesDB = new BloquesDB($conn);
		$cEscalasDB = new EscalasDB($conn);
		$cEscalas_itemsDB = new Escalas_itemsDB($conn);
								
		$cNivelesjerarquicos = new Nivelesjerarquicos();
		$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
		$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);
		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		
		@set_time_limit(0);
		ini_set("memory_limit","1024M");
		
		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		define('_NEWPAGE', '<!--NewPage-->');
		$_HEADER = '';
		$sHtmlCab	= '';
		$sHtml		= '';
		$sHtmlFin	= '';
		
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="' . constant("DIR_WS_GESTOR") . 'estilosInformes/CIP/resetCSS.css"/>';
					if($_POST['fIdTipoInforme']!=11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="' . constant("DIR_WS_GESTOR") . 'estilosInformes/CIP/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="' . constant("DIR_WS_GESTOR") . 'estilosInformes/CIP/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>Untitled Document</title>

				</head>
				
				<body>';
	
	$sHtmlFin .='
	</body>
	</html>';

		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("3");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
		
		//PORTADA
		$sHtml.= '	<div class="pagina portada">
				    	<img src="'.constant("DIR_WS_GESTOR").'graf/CIP/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
				    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR").'estilosInformes/CIP/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_infome"><p>' . constant("STR_CIP_NOMBRE_INFORME_TXT") . '</p></div>';
		$simgP = ($cEmpresaDng->getPathLogo() != "") ? $cEmpresaDng->getPathLogo() : $cPruebas->getLogoPrueba();
		$sHtml.= 		'<div id="logoCliente">
							<p><img src="' . constant("DIR_WS_GESTOR") . $simgP . '" /></p>
							<p><strong>' .  constant("STR_CIP_BIENVENIDO_INFORME_ORIENTACION_PROFESIONAL_TXT") . '</strong></p>
						</div>';
		$sHtml.= 		'<div id="informe">
							<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
							<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
						</div>
						
				    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
					';
		$sHtml.=	constant("_NEWPAGE");
		$sHtml.= 		'
					';
		//FIN PORTADA
		
		$sHtml.= inicioInformeCPI($cCandidato,$_POST['fCodIdiomaIso2']);
		$sHtml.=	constant("_NEWPAGE");
		$sHtml.= '<div class="pagina portada" id="contraportada">
						<img src="'.constant("DIR_WS_GESTOR").'graf/CIP/siguientes.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
						' . $sHtmlCab = getCabeceraCIP($cCandidato,0) . '
    					<img id="imgContraportada" src="' . constant("DIR_WS_GESTOR") . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
					</div>
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
//		error_reporting(E_ALL);
//		ini_set("display_errors","1");
		if (ini_get("pcre.backtrack_limit") < 2000000) { ini_set("pcre.backtrack_limit",2000000); };
		@set_time_limit(0);
		@define("OUTPUT_FILE_DIRECTORY", $spath . $sDirImg);
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
/******************************************************************
* Funciones para la generación del Informe
******************************************************************/
	function getCabeceraCIP($cCandidato, $iPosicionTop=1240){
		$sHtmlCab ='<div class="cabecera" style="position:absolute; top:' . $iPosicionTop . 'px;">
						<table>
							<tr>
			    				<td class="nombre">
							        <p class="textos">Sr/a ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
							    </td>
							    <td class="logo">
							    	&nbsp;
							    </td>
							    <td class="fecha">
							        <p class="textos">' . date("d/m/Y") . '
							    </td>
						    </tr>
					    </table>
					</div>
			';
		return $sHtmlCab; 
	}
	function inicioInformeCPI($cCandidato, $codIdiomaIso2){
		
		global $conn;
		global $cPruebas;
		global $cPruebasDB;
		global $aInteresaCarreras;
		global $sInteresaCarreras75;
		global $sNombre;
		global $spath;
		global $sDirImg;
		global $iVERBAL;
		global $iLOGICO;
		global $iNUMERICO;
		global $iESPACIAL;
		
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");
		$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
		$cCompetenciasDB = new CompetenciasDB($conn);

		//Seteamos arrays a utlizar en la gráfica y en otro sitios
		// de las otras pruebas
		$cPruebaVerbal = new Pruebas();
		$cPruebaVerbal->setIdPrueba(29);
		$cPruebaVerbal->setCodIdiomaIso2($codIdiomaIso2);
		$cPruebaVerbal = $cPruebasDB->readEntidad($cPruebaVerbal);
		$iVERBAL = getPDPrueba($cCandidato, $cPruebaVerbal);
 		 
		$cPruebaLogica = new Pruebas();
		$cPruebaLogica->setIdPrueba(31);
		$cPruebaLogica->setCodIdiomaIso2($codIdiomaIso2);
		$cPruebaLogica = $cPruebasDB->readEntidad($cPruebaLogica);
		$iLOGICO = getPDPrueba($cCandidato, $cPruebaLogica);
		
		$cPruebaNumerico = new Pruebas();
		$cPruebaNumerico->setIdPrueba(30);
		$cPruebaNumerico->setCodIdiomaIso2($codIdiomaIso2);
		$cPruebaNumerico = $cPruebasDB->readEntidad($cPruebaNumerico);
		$iNUMERICO = getPDPrueba($cCandidato, $cPruebaNumerico);
		
		$cPruebaEspacial = new Pruebas();
		$cPruebaEspacial->setIdPrueba(28);
		$cPruebaEspacial->setCodIdiomaIso2($codIdiomaIso2);
		$cPruebaEspacial = $cPruebasDB->readEntidad($cPruebaEspacial);
		$iESPACIAL = getPDPrueba($cCandidato, $cPruebaEspacial);
		
		//PÁGINA INTRODUCCIÓN, 1
		$sHtml='<div class="pagina">
		<img src="'.constant("DIR_WS_GESTOR").'graf/CIP/siguientes.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
		$sHtml.= '
		<div class="desarrollo" >
            <div class="caja">
            	<h2 class="tit_peque">' . constant("STR_INTRODUCCION") . ' CPI</h2>
            	<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P1")) . '</p>
            	<h2 class="tit_peque">' . constant("STR_CONTENIDO_DEL_INFORME") . '</h2>
            	<h2 class="tit_peque">' . constant("STR_APARTADO_1") . '</h2>
				<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P2")) . '</p>
				<h2 class="tit_peque">' . constant("STR_APARTADO_2") . '</h2>
				<p class="textos">' . constant("STR_CIP_INTRO_P3") . '</p>
				<h2 class="tit_peque">' . constant("STR_APARTADO_3") . '</h2>
				<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P4")) . '</p>
            </div>
        </div>
		';
		$sHtml.=	constant("_NEWPAGE");
		$sHtml.='<div class="pagina">
		<img src="'.constant("DIR_WS_GESTOR").'graf/CIP/siguientes.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		'. $sHtmlCab = getCabeceraCIP($cCandidato,0);

		$sIdTipoCompetencia=5;	//SERVICIOS
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);
		
		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);

		$sHtml.= '
		<div class="desarrollo" >
            <div class="caja">
            	<h2 class="tit_peque">ÁREAS PROFESIONALES PREFERENTES CPI</h2>
            	<p class="textos">A continuación se presentan los resultados obtenidos a partir de las respuestas dadas al  cuestionario de intereses profesionales realizado:</p>
				<table align="center" border="0" width="100%" style="border: 2px solid #cccccc">
					<tr>
				    	<td width="81">&nbsp;</td>
						<td width="9">&nbsp;</td>
						<td style="padding:2px;font-size:13px;width:200px;"></td>
						<td style="text-align:right;padding:2px;font-size:13px;width:50px;">Escala:</td>
						<td style="font-size:9px;"><span class="bPersonas" style="float:left;margin: 0;padding: 0;">0</span><span class="bPersonas">100</span></td>
				    </tr>
					<tr>
				    	<td width="81" rowspan="11" bgcolor="#cc0066" align="center"><img src="' . constant("DIR_WS_GESTOR") . 'graf/CIP/' . $codIdiomaIso2 . '/personas.jpg" alt="" title="" /></td>
						<td width="9">&nbsp;</td>
						<td style="background-color:#d99694;padding:2px;font-size:13px;width:200px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;width:40px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . ($iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				    </tr>
					';
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
						<td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}

		$sIdTipoCompetencia=6;	//INFLUENCIA
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
						<td width="9">&nbsp;</td>
						<td style="background-color:#d99694;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		
		$sIdTipoCompetencia=7;	//SANITARIA
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td style="background-color:#d99694;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';

		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}

		$sIdTipoCompetencia=8;	//CONCEPTUAL
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);
		
		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '
					<tr>
						<td colspan="5"></td>
				    </tr>
					<tr>
				    	<td width="81" rowspan="4" bgcolor="#376092" align="center"><img src="' . constant("DIR_WS_GESTOR") . 'graf/CIP/' . $codIdiomaIso2 . '/personas_datos.jpg" alt="" title="" /></td>
					  <td width="9">&nbsp;</td>
						<td style="background-color:#558ed5;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#558ed5;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#558ed5;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#376092;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#376092;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		
		$sIdTipoCompetencia=9;	//ORGANIZACIÓN/PROCEDIMIENTOS
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
						<td colspan="5"></td>
				    </tr>
					<tr>
						<td width="81" rowspan="9" bgcolor="#31859c" align="center"><img src="' . constant("DIR_WS_GESTOR") . 'graf/CIP/' . $codIdiomaIso2 . '/datos.jpg" alt="" title="" /></td>
						<td width="9">&nbsp;</td>
						<td style="background-color:#b7dee8;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		
		$sIdTipoCompetencia=10;	//COMUNICACIÓN VERBAL
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td style="background-color:#b7dee8;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}

		$sIdTipoCompetencia=11;	//INTERNACIONAL
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
						<td width="9">&nbsp;</td>
						<td style="background-color:#b7dee8;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		
		$sIdTipoCompetencia=12;	//CREATIVIDAD
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
						<td colspan="5"></td>
				    </tr>
				    <tr>
				    	<td width="81" rowspan="12" bgcolor="#604a7b" align="center"><img src="' . constant("DIR_WS_GESTOR") . 'graf/CIP/' . $codIdiomaIso2 . '/practica.jpg" alt="" title="" /></td>
				    	<td width="9">&nbsp;</td>
						<td style="background-color:#ccc1da;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				  </tr>
				';
		
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		
		$sIdTipoCompetencia=13;	//CREATIVIDAD/TÉCNICA
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td style="background-color:#ccc1da;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
						<td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}

		$sIdTipoCompetencia=14;	//TÉCNICA
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td style="background-color:#ccc1da;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';
		
		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
						<td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		
		$sIdTipoCompetencia=15;	//CIENTÍFICA
		$iPorcientoTipoCompetencia=0;
		$cTipos_competencias = new Tipos_competencias(); 
		$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
		$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
		$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);
		
		$cCompetencias = new Competencias();
		$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
		$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
		$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
		$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
		$cCompetencias->setOrderBy("idCompetencia");
		$cCompetencias->setOrder("ASC");
		$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
		$listaCompetencias = $conn->Execute($sqlCompetencias);
		
		$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);
		
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td style="background-color:#ccc1da;padding:2px;font-size:13px;">' . strtoupper($cTipos_competencias->getNombre()) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;width:50px;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . $iPorcientoTipoCompetencia . '%;">&nbsp;</span></td>
				    </tr>
					';

		$listaCompetencias->Move(0);
		$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iPorciento = 0;
		while(!$listaCompetencias->EOF){
			$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
			setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
			setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
		$sHtml.= '	<tr>
					  <td width="9">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . $iPorciento . '%;">&nbsp;</span></td>
				    </tr>
					';
			$listaCompetencias->MoveNext(); 
		}
		$sHtml.= '
					<tr>
						<td colspan="5"></td>
				    </tr>
				</table>
            </div>
        </div>
		';
		$sHtml.=	constant("_NEWPAGE");
		$sHtml.='<div class="pagina">
		<img src="'.constant("DIR_WS_GESTOR").'graf/CIP/siguientes.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
		$sInteresaCarreras0 = (!empty($aInteresaCarreras[0])) ? str_replace("|", ", ", substr($aInteresaCarreras[0], 1)) : 'Ninguna';
		$sInteresaCarreras1 = (!empty($aInteresaCarreras[1])) ? str_replace("|", ", ", substr($aInteresaCarreras[1], 1)) : 'Ninguna';
		$sInteresaCarreras2 = (!empty($aInteresaCarreras[2])) ? str_replace("|", ", ", substr($aInteresaCarreras[2], 1)) : 'Ninguna';
		$sInteresaCarreras3 = (!empty($aInteresaCarreras[3])) ? str_replace("|", ", ", substr($aInteresaCarreras[3], 1)) : 'Ninguna';
		$sInteresaCarreras4 = (!empty($aInteresaCarreras[4])) ? str_replace("|", ", ", substr($aInteresaCarreras[4], 1)) : 'Ninguna';

		$sImg = "AA" . $sNombre . ".png";
		$_PathImg = $spath . $sDirImg;
//		echo "->" . $_PathImg; 
		$sCadena = "";
		grafAreaAptitudes(450, 240, $sCadena, $_PathImg, $sImg, $iVERBAL, $iLOGICO, $iNUMERICO, $iESPACIAL);
		$sHtml.= '
		<div class="desarrollo" >
            <div class="caja">
            	<h2 class="tit_peque">SÍNTESIS DEL PERFIL</h2>
            	<p class="textos">A partir de las puntuaciones obtenidas, ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . ' demuestra:</p>
            	<p class="textos">Un interés muy elevado por actividades relacionadas con: ' .  $sInteresaCarreras0 . '.</p>
            	<p class="textos">Un interés elevado por actividades relacionadas con: ' . $sInteresaCarreras1 . '.</p>
            	<p class="textos">Un interés moderado por actividades relacionadas con: ' . $sInteresaCarreras2 . '.</p>
            	<p class="textos">Un interés bajo por actividades relacionadas con: ' . $sInteresaCarreras3 . '.</p>
            	<p class="textos">Poco o ningún interés por actividades relacionadas con: ' . $sInteresaCarreras4 . '.</p>
            	<br /><br />
            	<h2 class="tit_peque">ÁREAS DE APTITUDES</h2>
            	<p class="textos">Resultados en una escala de 0 a 100 correspondientes al rendimiento obtenido en las pruebas de aptitud realizadas.  La puntuación obtenida en cada test ha sido comparada con una muestra significativa de estudiantes que han respondido también al  CIP.</p>
            	<table align="center" border="0" width="600">
					<tr>
						<td align="center"><img src="' . constant("DIR_WS_GESTOR") . $sDirImg . $sImg . '" border="0"  alt="" title="" /></td>
				    </tr>
				</table>
            </div>
        </div>
		';
		$sHtml.=	constant("_NEWPAGE");
		$sHtml.='<div class="pagina">
		<img src="'.constant("DIR_WS_GESTOR").'graf/CIP/siguientes.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
		$sHtml.= '
		<div class="desarrollo" >
            <div class="caja">
            	<h2 class="tit_peque">ORIENTACIÓN PROFESIONAL </h2>
            	<p class="textos">A continuación, indicamos las carreras que mejor se adecuarían a los resultados obtenidos en los ejercicios de aptitudes realizados:</p>
            	<br /><br />
            	<table align="center" border="0" width="100%" style="border: 1px solid;border-color:#376092;" >
					<tr>
						<td align="center" style="border: 1px solid #376092;background-color:#376092;color:#ffffff;font-size:15px;">Área Interés</td>
						<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;"><strong style="font-weight: bold;">Razonamiento<br />Verbal</strong><br /><font style="font-size:9px;">Análisis e intepretación<br />de información escrita y hablada.<br />Redacción escritos.<br />Actividades de<br />comunicación.</font></td>
						<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;"><strong style="font-weight: bold;">Razonamiento<br />Numérico</strong><br /><font style="font-size:9px;">Calcular o chequear<br />datos.<br />Realizar operaciones de cálculo.<br />Manejar cifras,<br />estadísticas, ratios.<br />Analizar financieramente.</font></td>
						<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;"><strong style="font-weight: bold;">Razonamiento<br />Lógico</strong><br /><font style="font-size:9px;">Crear nuevos conceptos.<br />Desarrollar nuevos<br />enfoques.<br />Responder a preguntas<br />por qué o cómo.<br />Resolución problemas<br />lógicos.</font></td>
						<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;"><strong style="font-weight: bold;">Razonamiento Espacial</strong><br /><font style="font-size:9px;">Representar figuras o<br />espacios mentalmente.<br />Trabajar en varios planos.<br />Rotar figuras o modelos<br />en el espacio.<br />Manejar mapas, planos<br />y/o diseños gráficos.</font></td>
				    </tr>
					' . $sInteresaCarreras75 . '
				</table>
				<br /><br />
				<table align="center" border="0" style="border-collapse:separate;border-spacing:0px;margin:auto;" cellpadding="5" cellspacing="5">
					<tr>
						<td class="textos"><strong style="font-weight: bold;">INTERPRETACIÓN</strong></td>
						<td>&nbsp;</td>
				    </tr>
					<tr>
						<td align="center" style="border: 1px solid #376092;background-color:#00ff00;">&nbsp;</td>
						<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">Dispone ya de una capacidad óptima para el desarrollo de contenidos referidos a la carrera indicada.</td>
				    </tr>
					<tr>
						<td align="center" style="border: 1px solid #376092;background-color:#ffff00;">&nbsp;</td>
						<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">Dispone de potencialidad para el desarrollo	de contenidos referidos a la carrera indicada.</td>
				    </tr>
					<tr>
						<td align="center" style="border: 1px solid #376092;background-color:#ff0000;">&nbsp;</td>
						<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">Podría tener algunas dificultades o requerir más esfuerzos en algunos contenidos de la carrera indicada.</td>
				    </tr>
					<tr>
						<td align="center" style="border: 1px solid #376092;background-color:#d9d9d9;">&nbsp;</td>
						<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">No hay resultados en este test.</td>
				    </tr>
					<tr>
						<td align="center" style="border: 1px solid #376092;background-color:#4f81bd;">&nbsp;</td>
						<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">No es una capacidad crítica en la carrera, aunque pueda facilitar algunos aspectos complementarios.</td>
				    </tr>
				</table>
            </div>
        </div>
		';

     return $sHtml;
	}
	/* Posicion 0 ->Interes MUY ELEVADO
	 * Posicion 1 ->Interes ELEVADO
	 * Posicion 2 ->Interes MODERADO
	 * Posicion 3 ->Interes BAJO
	 * Posicion 4 ->Interes NINGUNO 
	 */
	function setArrayGlobalCarreras($iPorcentaje, $sCarrera){
		global $aInteresaCarreras;
		if ($iPorcentaje >= 81){
			$aInteresaCarreras[0] .= "|" . $sCarrera;
		}
		if ($iPorcentaje >= 61 && $iPorcentaje <= 80){
			$aInteresaCarreras[1] .= "|" . $sCarrera;
		}
		if ($iPorcentaje >= 41 && $iPorcentaje <= 60){
			$aInteresaCarreras[2] .= "|" . $sCarrera;
		}
		if ($iPorcentaje >= 31 && $iPorcentaje <= 40){
			$aInteresaCarreras[3] .= "|" . $sCarrera;
		}
		if ($iPorcentaje >= 0 && $iPorcentaje <= 30){
			$aInteresaCarreras[4] .= "|" . $sCarrera;
		}
	}
	/*	Carreras con un porcentage igual o superior al 75%	 */
	function setArrayGlobalCarreras75($iPorcentaje, $sCarrera, $sAptitud){
		global $sInteresaCarreras75;
		global $iVERBAL;
		global $iLOGICO;
		global $iNUMERICO;
		global $iESPACIAL;
		global $aAptitudesVERVAL;
		global $aAptitudesNUMERICO;
		global $aAptitudesESPACIAL;
		global $aAptitudesLOGICA;

		$sColorVERVAL = ($iVERBAL > 0 ) ? '#4f81bd' : '#d9d9d9';
		$sColorNUMERICO = ($iNUMERICO > 0 ) ? '#4f81bd' : '#d9d9d9';
		$sColorESPACIAL = ($iESPACIAL > 0 ) ? '#4f81bd' : '#d9d9d9';
		$sColorLOGICA = ($iLOGICO > 0 ) ? '#4f81bd' : '#d9d9d9';
		
		if ($iPorcentaje >= 75){
//			echo "<br />" . $sCarrera . " sAptitud:: " . $sAptitud . " - iPorcentaje::" . $iPorcentaje;
			if ($iVERBAL > 0 ){
				if (in_array($sAptitud, $aAptitudesVERVAL)){
//					echo "<br />----------->" . $sCarrera . " sAptitud:: " . $sAptitud . " - iPorcentaje::" . $iPorcentaje;
					if ($iVERBAL >= 71){
						$sColorVERVAL = '#00ff00';
					}
					if ($iVERBAL >= 41 && $iVERBAL <= 70){
						$sColorVERVAL = '#ffff00';
					}
					if ($iVERBAL >= 0 && $iVERBAL <= 40){
						$sColorVERVAL = '#ff0000';
					}
				}
			}
			if ($iNUMERICO > 0 ){
				if (in_array($sAptitud, $aAptitudesNUMERICO)){
					if ($iNUMERICO >= 71){
						$sColorNUMERICO = '#00ff00';
					}
					if ($iNUMERICO >= 41 && $iNUMERICO <= 70){
						$sColorNUMERICO = '#ffff00';
					}
					if ($iNUMERICO >= 0 && $iNUMERICO <= 40){
						$sColorNUMERICO = '#ff0000';
					}
				}
			}
			if ($iESPACIAL > 0 ){
				if (in_array($sAptitud, $aAptitudesESPACIAL)){
					if ($iESPACIAL >= 71){
						$sColorESPACIAL = '#00ff00';
					}
					if ($iESPACIAL >= 41 && $iESPACIAL <= 70){
						$sColorESPACIAL = '#ffff00';
					}
					if ($iESPACIAL >= 0 && $iESPACIAL <= 40){
						$sColorESPACIAL = '#ff0000';
					}
				}
			}
			if ($iLOGICO > 0 ){
				if (in_array($sAptitud, $aAptitudesLOGICA)){
					if ($iLOGICO >= 71){
						$sColorLOGICA = '#00ff00';
					}
					if ($iLOGICO >= 41 && $iLOGICO <= 70){
						$sColorLOGICA = '#ffff00';
					}
					if ($iLOGICO >= 0 && $iLOGICO <= 40){
						$sColorLOGICA = '#ff0000';
					}
				}
			}
			
			$sInteresaCarreras75 .= '<tr>'; 
			$sInteresaCarreras75 .= '	<td align="center" style="border: 1px solid #376092;font-size: 12px;">' . $sCarrera; '</td>';
			$sInteresaCarreras75 .= '	<td align="center" style="border: 1px solid #376092;background-color:' . $sColorVERVAL . ';">&nbsp;</td>';
			$sInteresaCarreras75 .= '	<td align="center" style="border: 1px solid #376092;background-color:' . $sColorNUMERICO . ';">&nbsp;</td>';
			$sInteresaCarreras75 .= '	<td align="center" style="border: 1px solid #376092;background-color:' . $sColorLOGICA . ';">&nbsp;</td>';
			$sInteresaCarreras75 .= '	<td align="center" style="border: 1px solid #376092;background-color:' . $sColorESPACIAL . ';">&nbsp;</td>';
			$sInteresaCarreras75 .= '</tr>';
		}
	}
	
	/*
	*	Te agradaría realizar esa tarea = 2 puntos.
	*	Te desagradaría realizar esa tarea = 0 puntos.
	*	Te resultaría indiferente realizarla o no = 1 punto.
	*/
	function getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebaCPI){
		global $conn;
		$iPorcentaje=0;
		$iSumador=0;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		
        $cCompetencias_itemsDB = new Competencias_itemsDB($conn);
        $cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
        $nAreas = $listaCompetencias->NumRows();
		while(!$listaCompetencias->EOF){

	        $cCompetencias_items = new Competencias_items();
	        $cCompetencias_items->setIdCompetencia($listaCompetencias->fields['idCompetencia']);
	        $cCompetencias_items->setIdCompetenciaHast($listaCompetencias->fields['idCompetencia']);
	        $cCompetencias_items->setIdTipoCompetencia($listaCompetencias->fields['idTipoCompetencia']);
	        $cCompetencias_items->setIdTipoCompetenciaHast($listaCompetencias->fields['idTipoCompetencia']);
	        $cCompetencias_items->setIdPrueba($listaCompetencias->fields['idPrueba']);
	        $cCompetencias_items->setOrderBy("idItem");
	        $cCompetencias_items->setOrder("ASC");
			$sqlCompetencias_items = $cCompetencias_itemsDB->readLista($cCompetencias_items);
			$listaCompetencias_items = $conn->Execute($sqlCompetencias_items);
	        $nCompetencias_items =$listaCompetencias_items->recordCount();
	        $iPdCompetencias = 0;
	        if($nCompetencias_items>0){
	        	while(!$listaCompetencias_items->EOF){
	        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
	        	
	        		$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
					$cRespuestas_pruebas_items->setIdProceso($cCandidato->getIdProceso());
					$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
					$cRespuestas_pruebas_items->setIdPrueba($cPruebaCPI->getIdPrueba());
					$cRespuestas_pruebas_items->setCodIdiomaIso2($cPruebaCPI->getCodIdiomaIso2());
					$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);
	
					$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
					
					//	1 	A 	Agrada
					//	2 	D 	Desagrada
					//	3 	I 	Indiferente
					switch ($cRespuestas_pruebas_items->getIdOpcion())
					{
						case 1:
							$iSumador += 2;
							break;
						case 2:
							$iSumador += 0;
							break;
						case 3:
							$iSumador += 1;
							break;
						default:
							
					};
					$listaCompetencias_items->MoveNext();
	        	}
	        }
			$listaCompetencias->MoveNext(); 
		}
		$iPorcentaje = (($iSumador*100)/($nAreas*16));
		return $iPorcentaje;
	}
	function getPuntuacionPorCarrera($rsCompetencia, $cCandidato, $cPruebaCPI){
		global $conn;
		$iPorcentaje=0;
		$iSumador=0;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		
        $cCompetencias_items = new Competencias_items();
        $cCompetencias_itemsDB = new Competencias_itemsDB($conn);
        $cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
        
        $cCompetencias_items->setIdCompetencia($rsCompetencia->fields['idCompetencia']);
        $cCompetencias_items->setIdCompetenciaHast($rsCompetencia->fields['idCompetencia']);
        $cCompetencias_items->setIdTipoCompetencia($rsCompetencia->fields['idTipoCompetencia']);
        $cCompetencias_items->setIdTipoCompetenciaHast($rsCompetencia->fields['idTipoCompetencia']);
        $cCompetencias_items->setIdPrueba($rsCompetencia->fields['idPrueba']);
        $cCompetencias_items->setOrderBy("idItem");
        $cCompetencias_items->setOrder("ASC");
		$sqlCompetencias_items = $cCompetencias_itemsDB->readLista($cCompetencias_items);
		$listaCompetencias_items = $conn->Execute($sqlCompetencias_items);
        $nCompetencias_items =$listaCompetencias_items->recordCount();
        $iPdCompetencias = 0;
        if($nCompetencias_items > 0){
        	while(!$listaCompetencias_items->EOF){
        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
        	
        		$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
				$cRespuestas_pruebas_items->setIdProceso($cCandidato->getIdProceso());
				$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
				$cRespuestas_pruebas_items->setIdPrueba($cPruebaCPI->getIdPrueba());
				$cRespuestas_pruebas_items->setCodIdiomaIso2($cPruebaCPI->getCodIdiomaIso2());
				$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

				$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
				
				//	1 	A 	Agrada
				//	2 	D 	Desagrada
				//	3 	I 	Indiferente
				switch ($cRespuestas_pruebas_items->getIdOpcion())
				{
					case 1:
						$iSumador += 2;
						break;
					case 2:
						$iSumador += 0;
						break;
					case 3:
						$iSumador += 1;
						break;
					default:
						
				};
				$listaCompetencias_items->MoveNext();
        	}
        }
		$iPorcentaje = (($iSumador*100)/16);
		return $iPorcentaje;
			
	}
	
	function grafAreaAptitudes($width, $height, $sCadena, $_PathImgInforme, $sImg, $iVERBAL=0, $iLOGICO=0, $iNUMERICO=0, $iESPACIAL=0)
	{
		require_once(constant("DIR_WS_JPGRAPH") . "jpgraph.php");
		require_once(constant("DIR_WS_JPGRAPH") . "jpgraph_bar.php");
		require_once(constant("DIR_WS_JPGRAPH") . "jpgraph_line.php");
		require_once(constant("DIR_WS_JPGRAPH") . "jpgraph_date.php");

		$datay=array($iVERBAL, $iLOGICO, $iNUMERICO, $iESPACIAL);
//		$datax=array("de todos los " . $sCadena,"de todos los directores","resto de profesionales","de todos los puestos");
		$datax=array("VERBAL", "LOGICO", "NUMERICO", "ESPACIAL");
		
		// Set the basic parameters of the graph 
		$graph = new Graph($width,$height);
		$graph->SetScale("textlin");
		$graph->SetFrame(false); 
		$graph->SetMarginColor('white');
		// Rotate graph 90 degrees and set margin
		$graph->Set90AndMargin(100,50,50,30);
		$graph->SetScale('textlin',0,100);
		// Nice shadow
		$graph->SetShadow();
		
		// Setup title
		$graph->title->Set($sCadena);
		$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
		
		// Setup X-axis
		$graph->xaxis->SetTickLabels($datax);
//		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
		
		// Some extra margin looks nicer
		$graph->xaxis->SetLabelMargin(8);
		
		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center');
		
		// Add some grace to y-axis so the bars doesn't go
		// all the way to the end of the plot area
		$graph->yaxis->scale->SetGrace(10);
		// Display every 6:th tickmark
//		$graph->yaxis->SetTextTickInterval(6);
		// Label every 2:nd tick mark
//		$graph->yaxis->SetTextLabelInterval(2);

		// We don't want to display Y-axis
		//$graph->yaxis->Hide();
		
		// Now create a bar pot
		$bplot = new BarPlot($datay);
		$bplot->SetFillColor("orange");
		$bplot->SetShadow();
		
		//You can change the width of the bars if you like
		//$bplot->SetWidth(0.5);
	
		// We want to display the value of each bar at the top
		$bplot->value->Show();
		$bplot->value->SetFont(FF_ARIAL, FS_BOLD, 12);
		$bplot->value->SetAlign('left', 'center');
		$bplot->value->SetColor("black", "darkred");
		$bplot->value->SetFormat("%.2f%%");
		
		// Add the bar to the graph
		$graph->Add($bplot);
		
		if (!chk_dir($_PathImgInforme . $sImg, 0777)){
			return false;
		}
		// .. and stroke the graph
		$graph->Stroke($_PathImgInforme . $sImg);
	}
	function getPDPrueba($cCandidato, $cPrueba){
		global $conn;
		$iPDirecta = 0;
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
		
		$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($conn);
		$cItemsDB = new ItemsDB($conn);

		$cOpcionesDB = new OpcionesDB($conn);
		$cOpciones_valoresDB = new Opciones_valoresDB($conn);
						
		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
        
		$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
		$cRespuestas_pruebas_items->setIdProceso($cCandidato->getIdProceso());
		$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
		$cRespuestas_pruebas_items->setIdPrueba($cPrueba->getIdPrueba());
		$cRespuestas_pruebas_items->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
		$cRespuestas_pruebas_items->setOrderBy("idItem");
		$cRespuestas_pruebas_items->setOrder("ASC");
		
		$cIt = new Items();
		$cIt->setIdPrueba($cPrueba->getIdPrueba());
		$cIt->setIdPruebaHast($cPrueba->getIdPrueba());
		$cIt->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
		$sqlItemsPrueba= $cItemsDB->readLista($cIt);
		$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);

		// Montamos la lista de respuestas para los parámetros enviados. 
		$sqlRespItems = $cRespuestas_pruebas_itemsDB->readLista($cRespuestas_pruebas_items);
		$listaRespItems = $conn->Execute($sqlRespItems);
		
		if($listaRespItems->recordCount() > 0)
		{
			while(!$listaRespItems->EOF){
				
				//Leemos el item para saber cual es la opción correcta
				$cItem = new Items();
				$cItem->setIdItem($listaRespItems->fields['idItem']);
				$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
				$cItem->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
				$cItem = $cItemsDB->readEntidad($cItem);
				
				//Leemos la opción para saber en código de la misma
				$cOpcion = new Opciones();
				$cOpcion->setIdItem($listaRespItems->fields['idItem']);
				$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
				$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
				$cOpcion->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
				$cOpcion = $cOpcionesDB->readEntidad($cOpcion);
				
				//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
				if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
					//echo $listaRespItems->fields['idItem'] . " - bien <br />";
					//Si coincide se le suma uno a la PDirecta.
					$iPDirecta++;
				}
				$listaRespItems->MoveNext();
			}
		}
		$iPDirecta = (($iPDirecta*100) / $listaItemsPrueba->recordCount());
		return $iPDirecta;
	}
/******************************************************************
* FIN de Funciones para la generación del Informe
******************************************************************/
?>