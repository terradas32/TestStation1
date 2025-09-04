<?php

if(!isset($counter) || $counter==0){
	
	if(isset($_POST['esZip']) && $_POST['esZip'] == true){
		$dirGestor = constant("DIR_WS_GESTOR_HTTPS");
		$documentRoot = constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
	}else{
		$dirGestor = constant("DIR_WS_GESTOR");
		$documentRoot = constant("DIR_FS_DOCUMENT_ROOT");
	}

	global $dirGestor;
	global $documentRoot;

	/******************************************************************
	* Funciones para la generación del Informe
	******************************************************************/


		/*
		* INTERPRETACIÓN DE INFORME EXPERTO.
		*/
		function generaInforme($cPruebas, $sHtmlCab, $idIdioma)
		{
			global $dirGestor;
			global $documentRoot;
			global $conn;

			global $cTextos_secciones;
			global $cTextos_seccionesDB;
			global $listaRespItems;
			global $listaItemsPrueba;
			global $iPDirecta;
			global $iPercentil;

			$cTextos_secciones = new Textos_secciones();
			$cTextos_secciones->setIdSeccion("4");
			$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
			$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
			$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
			// TEXTO INTRODUCCIÓN

			$sHtml='
				<div class="pagina">'. $sHtmlCab;
		$s_test = str_replace('<div style="margin-left: 20px;">', '<p class="textos" style="margin-left: 20px;">',$cTextos_secciones->getTexto());
		$s_test = str_replace('</div>', '</p>',$s_test);
			$sHtml.= '
					<div class="desarrollo">
						<h2 class="subtitulo">' . mb_strtoupper(constant("STR_INTRODUCCION"), 'UTF-8') . '</h2>
						<div class="caja">
					<table width="100%" border="0">
					<tr>
						<td>
							' . $s_test . '
						</td>
					</tr>
					</table>
						</div>';
			$cTextos_secciones = new Textos_secciones();
			$cTextos_secciones->setIdSeccion("9");
			$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
			$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
			$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
			$sHtml.= '
						<h2 class="subtitulo" >' . mb_strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8"), 'UTF-8') . '</h2>
						<div class="caja">
							<table width="100%" border="0">
						';
			$iWhidth = 1;
			$sHtml.='			<tr>
									<td width="80%" >
										<table width="100%" cellspacing="0" cellpadding="0" style="background:#c0c0c0;">
											<tr>
												<td width="19%" align="center" style="height:30px;border-left:3px solid #004080;border-top:3px solid #004080;border-bottom:3px solid #004080;">
													<font style="color: #004080;font-weight: bold;">'.constant("STR_PUNT_INFORMES").'</font>
												</td>
												<td width="27%" align="center" style="border-left:3px solid #004080;border-top:3px solid #004080;border-bottom:3px solid #004080;">
													<font style="color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
												</td>
												<td width="27%" align="center" style="border-left:3px solid #004080;border-top:3px solid #004080;border-bottom:3px solid #004080;">
													<font style="color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
												</td>
												<td width="27%" align="center" style="border-left:3px solid #004080;border-right:3px solid #004080;border-top:3px solid #004080;border-bottom:3px solid #004080;">
													<font style="color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
												</td>
											</tr>
										</table>
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td width="19%" align="center" style="height:45px;border-left:3px solid #004080;">
													<font style="color: #000000;font-weight: bold;">PD = '.$iPDirecta.'</font>
												</td>
												<td width="81%" style="height:45px;border-left:3px solid #004080;border-right:3px solid #004080;">
													<img src="'.$dirGestor . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width: 498px;">
												</td>
											</tr>
											<tr>
												<td width="19%" align="center" style="border-left:3px solid #004080;border-bottom:3px solid #004080;">
													<font style="color: #000000;font-weight: bold;">PC = '.$iPercentil.' %</font>
												</td>
												<td width="81%" style="border-left:3px solid #004080;border-right:3px solid #004080;border-bottom:3px solid #004080;vertical-align:middle;">
													<img src="'.$dirGestor . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.(($iPercentil*498)/100).'px;height:25px;">
												</td>
											</tr>
										</table>
										'; 
										include("textosPercentil.php");
										$sHtml.='<p class="textos">' . $sTextosPercentil . '</p>';
			$sHtml.='			
									</td>
								</tr>
								<tr>
									<td height="12">&nbsp;
									</td>
								</tr>';
			$cTextos_secciones = new Textos_secciones();
			$cTextos_secciones->setIdSeccion("5");
			$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
			$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
			$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
			$sHtml.='		<tr>
								<td>
									<p style="color: #ffffff;background-color: #66669a;border: 1px solid black;font-size: 17px;;padding-left: 10px;width: 35%;">' . constant("STR_BAJO_POTENCIAL_0_A_35") . '</p>
									<p class="textos" style="padding-left: 10px;">' . $cTextos_secciones->getTexto() . '</p>
								</td>
							</tr>';
			$cTextos_secciones = new Textos_secciones();
			$cTextos_secciones->setIdSeccion("6");
			$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
			$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
			$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
			$sHtml.='		<tr>
								<td>
									<p style="color: #ffffff;background-color: #66669a;border: 1px solid black;font-size: 17px;;padding-left: 10px;width: 35%;">' . constant("STR_MEDIO_POTENCIAL_36_A_70") . '</p>
									<p class="textos" style="padding-left: 10px;">' . $cTextos_secciones->getTexto() . '</p>
								</td>
							</tr>';
			$cTextos_secciones = new Textos_secciones();
			$cTextos_secciones->setIdSeccion("7");
			$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
			$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
			$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
			$sHtml.='		<tr>
								<td>
									<p style="color: #ffffff;background-color: #66669a;border: 1px solid black;font-size: 17px;;padding-left: 10px;width: 35%;">' . constant("STR_ALTO_POTENCIAL_71_A_100") . '</p>
									<p class="textos" style="padding-left: 10px;">' . $cTextos_secciones->getTexto() . '</p>
								</td>
							</tr>';

			$sHtml.='	</table>

						</div>
					</div>
					<!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->
			<hr>
				';

			return $sHtml;
		}

	/******************************************************************
	* FIN Funciones para la generación del Informe
	******************************************************************/
}
		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

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

		$spath = (substr($documentRoot, -1, 1) != '/') ? $documentRoot . '/' : $documentRoot;

		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/tac/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/tac/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/tac/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>tac</title>
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
										<p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo">
						    	<img src="'.$dirGestor.'estilosInformes/tac/img/logo-pequenio.jpg" title="logo"/>
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
		$cTextos_secciones->setIdSeccion("8");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($_POST['fIdTipoInforme']);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);

		//PORTADA
		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . $dirGestor . 'graf/tac/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . $dirGestor . 'estilosInformes/tac/img/logo.jpg" /></h1>';
			if($_POST['fIdTipoInforme']!=11){
				$sHtml.= 		'<div id="txt_infome"><p>' . $cTextos_secciones->getTexto() . '</p></div>';
			}else{
				$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . $sDescInforme . '</p></div>';
			}
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
		    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
			</div>
			<!--FIN DIV PAGINA-->
      <hr>
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA

		switch ($_POST['fIdTipoInforme'])
		{
			case(13);
			   	$sHtml.= generaInforme($cPruebas,$sHtmlCab,$_POST['fCodIdiomaIso2']);
		}

		$sHtml.= '
			<div class="pagina portada" id="contraportada">
    			<img id="imgContraportada" src="' . $dirGestor . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
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
		$spath = (substr($documentRoot, -1, 1) != '/') ? $documentRoot . '/' : $documentRoot;

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
?>
