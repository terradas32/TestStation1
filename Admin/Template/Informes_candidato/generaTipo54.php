<?php
		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

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
										<p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
						    </td>
						    <td class="logo"><img src="'.constant("DIR_WS_GESTOR").'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo-pequenio.jpg" title="logo"/></td>
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
		//PORTADA
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("35");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sDescInforme = $cTextos_secciones->getTexto();

		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . constant("DIR_WS_GESTOR") . 'graf/prueba' . $cPruebas->getIdPrueba() . '/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR") . 'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> ' . date("d/m/Y") . '</p>
				</div>
		    	<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>
			</div>
			<!--FIN DIV PAGINA-->
      <hr>
			';
//		$sHtml.=	constant("_NEWPAGE");
		//FIN PORTADA

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;

		$sHtml.='	<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%">';

    	//TITULO INTRODUCCIÓN
		$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant('STR_INTRODUCCION'), 'UTF-8') . '</font>
    							</p>';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("34");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN

		$sHtml.="<br />" . str_replace('style="font-size:18px;"', 'style="font-size:14px;"',$cTextos_secciones->getTexto());
		$sHtml.='			</td>
    					</tr>
    				</table>
        	</div>
        	<!--FIN DIV PAGINA-->
          <hr>
    				';

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;
    	$sHtml.='	<table width="100%" border="0" style="">';
		$sHtml.='		<tr>
    						<td width="80%">';

		$sUltimoItemRespondido = 0;
		if ($listaRespItems->recordCount() > 0){
			$sUltimoItemRespondido = $listaRespItems->MoveLast();
			$sUltimoItemRespondido = $listaRespItems->fields['idItem'];
		}
		$IR = 0.00;
		$IP = 0.00;
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

		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("36");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN



    	$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 20px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8"), 'UTF-8').'</font>
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="60%" align="center" >
    							<table class="resetTable" width="95%" style="" cellspacing="30" align="center" cellpadding="15" border="0">
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_NUM_TOTAL_PREGUNTAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'. $listaItemsPrueba->recordCount().'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$iPDirecta.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").'(P.C.)</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$iPercentil.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$sIR.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_PRECISION").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$sIP.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 18px;color: #004080;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 18px;color: #6e6e6e;font-weight: bold;">'.$sPOR.'</font>
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>';
    	$iWhidth = 1;
    	$sHtml.='		<tr>
    						<td width="80%">
								<table class="" width="100%" style="background-color: #fff;" cellspacing="0" align="center" cellpadding="0" border="0">
									<tr>
										<td width="100%">
											<table width="100%" cellspacing="0" cellpadding="0" style="background:#c0c0c0;border-left:3px solid #004080;border-right:3px solid #004080;">
												<tr>
													<td width="19%" align="center" style="height:60px;border:2px solid #004080;">
														<font style="font-size: 14px;font-weight: bold;color:#004080;">'.constant("STR_PUNT_INFORMES").'</font>
													</td>
													<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
														<font style="font-size: 14px;font-weight: bold;color:#004080;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
													</td>
													<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
														<font style="font-size: 14px;font-weight: bold;color:#004080;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
													</td>
													<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
														<font style="font-size: 14px;font-weight: bold;color:#004080;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
													</td>
												</tr>
											</table>
											<table width="100%" cellspacing="0" cellpadding="0" style="border-left:3px solid #004080;border-bottom:3px solid #004080;border-right:3px solid #004080;">
												<tr>
													<td width="19%" align="center" style="height:45px;border-right:2px solid #004080;">
														<font style="font-size: 14px;color: #000000;font-weight: bold;">PD = '.$iPDirecta.'</font>
													</td>
													<td width="81%" style="height:45px;border-left:2px solid #004080;">
														<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:540px;">
													</td>
												</tr>
												<tr>
													<td width="19%" align="center" style="height:40px;border-right:2px solid #004080;">
														<font style="font-size: 14px;color: #000000;font-weight: bold;">PC = '.$iPercentil.' %</font>
													</td>
													<td width="81%" style="height:40px;border-left:2px solid #004080;vertical-align:middle;">
														<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.(($iPercentil*540)/100).'px;height:35px;">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								'; 
								include("textosPercentil.php");
								$sHtml.=$sTextosPercentil;
	  $sHtml.='			
    						</td>
    					</tr>
    					<tr>
    						<td height="40">&nbsp;
    						</td>
    					</tr>
    	';
    	$sHtml.='	</table>
       	</div>
        	<!--FIN DIV PAGINA-->
          <hr>
    			';

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
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");

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
    $header_html    = $_HEADER;

		$footer_html    =  $_sBaremo . str_repeat(" ", 30) . constant("STR_PIE_INFORMES");

		include("generaDOMPDF.php");
		//$footer_html = $footer_html;

		//


	}
?>
