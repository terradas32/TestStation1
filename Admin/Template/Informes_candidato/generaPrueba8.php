<?php
	//Variables globales de cálculo
	$respuestasBlancas=0;
	$puntuacion=0;

	$vocabularioOK=0;
	$vocabularioERROR=0;
	$vocabularioBLANCO=0;

	$gramaticaOK=0;
	$gramaticaERROR=0;
	$gramaticaBLANCO=0;

	$comprensionOK=0;
	$comprensionERROR=0;
	$comprensionBLANCO=0;

	// CÁLCULOS GLOBALES
	$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);

	$listaItemsPrueba->Move(0);
	while(!$listaItemsPrueba->EOF){
	    $cRespuestas_pruebas_items = new Respuestas_pruebas_items();

	    $cRespuestas_pruebas_items->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespuestas_pruebas_items->setIdProceso($_POST['fIdProceso']);
		$cRespuestas_pruebas_items->setIdCandidato($_POST['fIdCandidato']);
		$cRespuestas_pruebas_items->setIdPrueba($_POST['fIdPrueba']);
		$cRespuestas_pruebas_items->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
		$cRespuestas_pruebas_items->setIdItem($listaItemsPrueba->fields['idItem']);
		$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);
//		echo "->item::" . $listaItemsPrueba->fields['idItem'] . " - " . $cRespuestas_pruebas_items->getIdOpcion();
		if ($cRespuestas_pruebas_items->getIdOpcion() != ""){
			//Miramos la opcion de respuesta
			$cOpcionRespuesta = new Opciones();
			$cOpcionRespuesta->setIdItem($listaItemsPrueba->fields['idItem']);
			$cOpcionRespuesta->setIdPrueba($listaItemsPrueba->fields['idPrueba']);
			$cOpcionRespuesta->setIdOpcion($cRespuestas_pruebas_items->getIdOpcion());
			$cOpcionRespuesta->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
			$cOpcionRespuesta = $cOpcionesDB->readEntidad($cOpcionRespuesta);

			if ($listaItemsPrueba->fields['correcto'] == $cOpcionRespuesta->getCodigo()){
//				echo "<br />item::" . $cRespuestas_pruebas_items->getIdItem() . " -> Correcto:: " . $listaItemsPrueba->fields['correcto'] . " Opción respuesta::" . $cOpcionRespuesta->getCodigo();
				setPuntuacionTipoTipoItem($listaItemsPrueba->fields['tipoItem'], "OK");
				$puntuacion++;
			}else{
				if ($cRespuestas_pruebas_items->getIdOpcion() == ""){
//					echo "<br />BLANCAS:: item::" . $cRespuestas_pruebas_items->getIdItem() . " -> Correcto:: " . $listaItemsPrueba->fields['correcto'] . " Opción respuesta::" . $cOpcionRespuesta->getCodigo();
					setPuntuacionTipoTipoItem($listaItemsPrueba->fields['tipoItem'], "BLANCO");
					$respuestasBlancas++;
				}else{
//					echo "<br />ERROR:: item::" . $cRespuestas_pruebas_items->getIdItem() . " -> Correcto:: " . $listaItemsPrueba->fields['correcto'] . " Opción respuesta::" . $cOpcionRespuesta->getCodigo();
					setPuntuacionTipoTipoItem($listaItemsPrueba->fields['tipoItem'], "ERROR");
				}
			}
		}else{
//			echo "<br />BLANCAS:: item::" . $cRespuestas_pruebas_items->getIdItem() . " -> Correcto:: " . $listaItemsPrueba->fields['correcto'] . " Opción respuesta::" . $cOpcionRespuesta->getCodigo();
			setPuntuacionTipoTipoItem($listaItemsPrueba->fields['tipoItem'], "BLANCO");
			$respuestasBlancas++;
		}
		$listaItemsPrueba->MoveNext();
	}
	//Preguntas erroneas
	$erroneas = $listaItemsPrueba->recordCount() - $puntuacion - $respuestasBlancas;
	//barra con el valor obtenido por el candidato
	$factor= ($puntuacion*100)/$listaItemsPrueba->recordCount();


	function setPuntuacionTipoTipoItem($ti, $resultado){
		global $vocabularioOK;
		global $vocabularioERROR;
		global $vocabularioBLANCO;

		global $gramaticaOK;
		global $gramaticaERROR;
		global $gramaticaBLANCO;

		global $comprensionOK;
		global $comprensionERROR;
		global $comprensionBLANCO;

		switch ($resultado)
		{
			case "OK":
				if ($ti == "V"){
					$vocabularioOK++;
				}
				if ($ti == "VC"){
					$vocabularioOK++;
					$comprensionOK++;
				}
				if ($ti == "GC"){
					$gramaticaOK++;
					$comprensionOK++;
				}
				if ($ti == "G"){
					$gramaticaOK++;
				}
				break;
			case "ERROR":
				if ($ti == "V"){
					$vocabularioERROR++;
				}
				if ($ti == "VC"){
					$vocabularioERROR++;
					$comprensionERROR++;
				}
				if ($ti == "GC"){
					$gramaticaERROR++;
					$comprensionERROR++;
				}
				if ($ti == "G"){
					$gramaticaERROR++;
				}
				break;
			case "BLANCO":
				if ($ti == "V"){
					$vocabularioBLANCO++;
				}
				if ($ti == "VC"){
					$vocabularioBLANCO++;
					$comprensionBLANCO++;
				}
				if ($ti == "GC"){
					$gramaticaBLANCO++;
					$comprensionBLANCO++;
				}
				if ($ti == "G"){
					$gramaticaBLANCO++;
				}

				break;
			default:
				echo "ERROR Cáculo.";
				exit;
				break;
		} // end switch
	}
	// FIN CALCULOS

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
						    <td class="logo">
						    	<img src="'.constant("DIR_WS_GESTOR").'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo-pequenio.jpg" title="logo"/>
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
		    	<img src="' . constant("DIR_WS_GESTOR").'graf/prueba' . $cPruebas->getIdPrueba() . '/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . constant("DIR_WS_GESTOR").'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
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

		$sHtml.='
			<div class="pagina">'. $sHtmlCab;
    	//TITULO INTRODUCCIÓN
		$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 18px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant('STR_INTRODUCCION'), 'UTF-8').'</font>
    							</p>';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("1");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN
		$s_test = str_replace('<br />
<br />
<br />', '<br><br>',$cTextos_secciones->getTexto());
		$s_test = str_replace('font-size:18px;', 'font-size:16px;',$s_test);
		$s_test = str_replace('font-size: 18px;', 'font-size:16px;',$s_test);


		$sHtml.= $s_test;

		$sHtml.='
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
		$sRan_test="";
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
		$cTextos_secciones->setIdSeccion("2");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN



    	$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 18px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8"), 'UTF-8').'</font>
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="60%" align="center" >
    							<table class="resetTable" cellspacing="15" cellpadding="10" border="0" width="95%">
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_TOTAL_PREGUNTAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $listaItemsPrueba->recordCount() . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $puntuacion . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ERRONEAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $erroneas . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_BLANCO").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $respuestasBlancas . '</font>
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>';
    	$iWhidth = 1;
    	$sHtml.='		<tr>
    						<td width="80%" align="">
    							<table width="474" cellspacing="0" cellpadding="0" style="margin-left: 13px;background:#c0c0c0;border-left:3px solid #004080;border-right:3px solid #004080;">
    								<tr>
    									<td width="19%" align="center" style="height:40px;border:2px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_PUNT_INFORMES").'</font>
    									</td>
    									<td width="24%" align="center" style="height:40px;border-top:2px solid #004080;border-bottom:2px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
    									</td>
    									<td width="25%" align="center" style="height:40px;border:2px solid #004080;border-left:2px solid #c0c0c0;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
    									</td>
    									<td width="32%" align="center" style="height:40px;border:2px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">First Certificate Level</font>
    									</td>
    								</tr>
    							</table>
    							<table width="90%" cellspacing="0" cellpadding="0" style="margin-left: 13px;border-left:4px solid #004080;border-bottom:3px solid #004080;border-right:4px solid #004080;">
    								<tr>
    									<td width="19%" style="height:45px;border-right:2px solid #004080;">
    										<br />
    										<font style="font-size: 14px;padding-left:20px;color: #000000;font-weight: bold;">' . constant("STR_PD") . ' = ' . $puntuacion . '</font>
    										<br />
    										<font style="font-size: 14px;padding-left:20px;color: #000000;font-weight: bold;">' . constant("STR_PC") . ' = ' . ($iWhidth * $iPercentil) . '%</font>
    									</td>
    									<td width="81%" style="height:45px;border-left:2px solid #004080;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width: 498px;">
    									</td>
    								</tr>
    								<tr>
    									<td width="19%" align="center" style="height:40px;border-right:2px solid #004080;">
    										<font style="font-size: 14px;color: #000000;font-weight: bold;"></font>
    									</td>
    									<td width="81%" style="height:40px;border-left:2px solid #004080;vertical-align:middle;">
    										<img src="' . constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:' . (($iPercentil*498)/100) . 'px;height:35px;">
    									</td>
    								</tr>
    							</table>

              </td>
            </tr>
      			<tr>
      	       <td width="60%" align="center" >

    							<table class="resetTable" cellspacing="13" cellpadding="8" border="0" width="90%">
    								<tr>
    									<td width="90%" class="puntuacionTipo">' . constant("STR_VOCABULARIO") . ':
    									</td>
    									<td width="1px">
    									</td>
    									<td width="10%">
    									</td>
    								</tr>
    								<tr>
    									<td colspan="2" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . constant("STR_NUMERO_TOTAL_DE_PREGUNTAS_REFERENTES_AL_VOCABULARIO") . '</font>
    									</td>
    									<td style="border:3px solid #004080;" align="center">
    										<font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'. '20' .'</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $vocabularioOK . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . constant("STR_NUM_PREGUNTAS_ERRONEAS") . '</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $vocabularioERROR . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_BLANCO").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $vocabularioBLANCO . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" class="puntuacionTipo">' . constant("STR_GRAMATICA") . ':
    									</td>
    									<td width="1px">
    									</td>
    									<td width="10%">
    									</td>
    								</tr>
    								<tr>
    									<td colspan="2" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . constant("STR_NUMERO_TOTAL_DE_PREGUNTAS_REFERENTES_A_GRAMATICA") . '</font>
    									</td>
    									<td style="border:3px solid #004080;" align="center">
    										<font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . '20' . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . constant("STR_NUM_PREGUNTAS_ACERTADAS") . '</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $gramaticaOK . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ERRONEAS").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $gramaticaERROR . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . constant("STR_NUM_PREGUNTAS_BLANCO") . '</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $gramaticaBLANCO . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" class="puntuacionTipo">' . constant("STR_COMPRENSION") . ':
    									</td>
    									<td width="1px">
    									</td>
    									<td width="10%">
    									</td>
    								</tr>
    								<tr>
    									<td colspan="2" style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">' . constant("STR_NUMERO_TOTAL_DE_PREGUNTAS_REFERENTES_A_COMPRENSION") . '</font>
    									</td>
    									<td style="border:3px solid #004080;" align="center">
    										<font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'. '30' . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $comprensionOK . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ERRONEAS").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $comprensionERROR . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 14px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_BLANCO").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' . $comprensionBLANCO . '</font>
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>
    				</table>
        		';

		$sHtml.= '
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
?>
