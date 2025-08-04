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
				break;
			default:
				echo "ERROR Cáculo.";
				exit;
				break;
		} // end switch
	}
	// FIN CALCULOS
	
	setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
	
	@set_time_limit(0);
	ini_set("memory_limit","1024M");
	
	$_NEWPAGE = '<!--NewPage-->';
	$_HEADER = '';
	$sHtmlCab	= '<table width="100%" border="0">
				<tr>
    				<td width="50%">
    					' . mb_strtoupper($cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2(), 'UTF-8').'
    				</td>
    				<td width="50%" align="right">
    					'.date("d/m/Y").'
    				</td>
    			</tr>
    			
    	</table>';
	$sHtml		= '';
	$sHtmlFin	= '';

	$sDirImg="imgContratos";
	$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
	
	$sHtmlInicio='
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			
				<style media="screen" type="text/css" >
					body{
						font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
						font-size: 12pt;
						font-weight: normal;
						color: #000000;
					}
				</style>
			</head>
		<body>';
	
	$sHtmlFin .='
	</body>
	</html>';
	$sHtmlCab .='
		';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("3");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
		$sHtml.= '<br /><br /><br /><br /><br /><br /><br /><br />
			<table width="100%" border="0" cellspacing="20" cellpadding="20">
					<tr>
    					<td width="80%" align="center" height="350">
    						<font style="font-size: 42px;color: #004080;">' . $cTextos_secciones->getTexto() . '</font>
    					</td>
    				</tr>
					<tr>
    					<td width="80%" align="center">
    						<img src="' . constant('DIR_WS_GESTOR') . str_replace(".jpg" , "Informe.jpg", $cPruebas->getLogoPrueba()).'" width="319" />
    					</td>
    				</tr>  
    				<tr>
    					<td width="80%" align="center" height="20">
    					&nbsp;
    					</td>
    				</tr>
    				<tr>
    					<td width="100%" align="center" height="300">
    						<table width="70%" style="border:2px solid #004080">
    							<tr>
    								<td style="padding:20px;height:200px;">
    									<table width="100%" cellpadding="10" cellspacing="10">
    										<tr>
    											<td width="100%">
    												<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NOMBRE_APELLIDOS") . ':</font>  <font style="font-size: 22px;color: #000000;">' . mb_strtoupper($cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2(), 'UTF-8').'</font>
    											</td>
    										</tr>
    										<tr>
    											<td width="80%">
    												<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_FECHA_INFORME").':</font>  <font style="font-size: 22px;color: #000000;">'.date("d/m/Y").'</font>
    											</td>
    										</tr>
    									</table>
    								</td>
    							</tr>
    						</table>
    					</td>
    				</tr>    				
    		</table><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		$sHtml.='	'.$_NEWPAGE.$sHtmlCab.'<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%"><br /><br />';
						
    	//TITULO INTRODUCCIÓN
		$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant('STR_INTRODUCCION'), 'UTF-8').'</font>	
    							</p>';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("1");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
    	// TEXTO INTRODUCCIÓN
    			
		$sHtml.=$cTextos_secciones->getTexto();

    	$sHtml.='	'.$_NEWPAGE.$sHtmlCab.'
    				<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%"><br /><br />';
						
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("2");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
    	// TEXTO INTRODUCCIÓN
    			
		
		
    	$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8"), 'UTF-8').'</font>	
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="60%" align="center">
    							<table cellspacing="15" cellpadding="10" border="0" width="70%">
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_TOTAL_PREGUNTAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $listaItemsPrueba->recordCount() . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'(P.D.)</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $puntuacion . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ERRONEAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $erroneas . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_BLANCO").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $respuestasBlancas . '</font>
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>';
    	$iWhidth = 1;
    	$sHtml.='		<tr>
    						<td width="80%" align="center">
    							<table width="90%" cellspacing="0" cellpadding="0" style="background:#c0c0c0;border-left:3px solid #004080;border-right:3px solid #004080;">
    								<tr>
    									<td width="19%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PUNT_INFORMES").'</font>
    									<td>
    									<td width="24%" align="center" style="height:60px;border-top:2px solid #004080;border-bottom:2px solid #004080;">
    										<font style="font-size: 22px;color: #c0c0c0;font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
    									<td>
    									<td width="25%" align="center" style="height:60px;border:2px solid #004080;border-left:2px solid #c0c0c0;">
    										<font style="font-size: 22px;color: #c0c0c0;font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
    									<td>
    									<td width="32%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">First Certificate Level</font>
    									<td>
    								</tr>
    							</table>
    							<table width="90%" cellspacing="0" cellpadding="0" style="border-left:4px solid #004080;border-bottom:3px solid #004080;border-right:4px solid #004080;">
    								<tr>
    									<td width="19%" style="height:45px;border-right:2px solid #004080;">
    										<br />
    										<font style="font-size: 20px;padding-left:20px;color: #000000;font-weight: bold;">PD = ' . $puntuacion . '</font>
    										<br />
    										<font style="font-size: 20px;padding-left:20px;color: #000000;font-weight: bold;">PC = ' . ($iWhidth * $factor) . '%</font>
    									<td>
    									<td width="81%" style="height:45px;border-left:2px solid #004080;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:100%;">
    									<td>
    								</tr>
    								<tr>
    									<td width="19%" align="center" style="height:40px;border-right:2px solid #004080;">
    										<font style="font-size: 22px;color: #000000;font-weight: bold;"></font>
    									<td>
    									<td width="81%" style="height:40px;border-left:2px solid #004080;vertical-align:middle;">
    										<img src="' . constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:' . ($iWhidth * $factor) . '%;height:35px;">	
    									<td>
    								</tr>
    							</table>
    							<br />
    							<table cellspacing="15" cellpadding="10" border="0" width="90%">
    								<tr>
    									<td width="90%" style="font-weight:bold;font-size: 15pt;padding:0px;">' . constant("STR_VOCABULARIO") . ':
    									</td>
    									<td width="1px">
    									</td>
    									<td width="10%">
    									</td>
    								</tr>
    								<tr>
    									<td colspan="2" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NUMERO_TOTAL_DE_PREGUNTAS_REFERENTES_AL_VOCABULARIO") . '</font>
    									</td>
    									<td style="border:3px solid #004080;" align="center">
    										<font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'. '31' .'</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'(P.D.)</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $vocabularioOK . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NUM_PREGUNTAS_ERRONEAS") . '</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $vocabularioERROR . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_BLANCO").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $vocabularioBLANCO . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="font-weight:bold;font-size: 15pt;padding:0px;">' . constant("STR_GRAMATICA") . ':
    									</td>
    									<td width="1px">
    									</td>
    									<td width="10%">
    									</td>
    								</tr>
    								<tr>
    									<td colspan="2" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NUMERO_TOTAL_DE_PREGUNTAS_REFERENTES_A_GRAMATICA") . '</font>
    									</td>
    									<td style="border:3px solid #004080;" align="center">
    										<font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . '9' . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NUM_PREGUNTAS_ACERTADAS") . '(P.D.)</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $gramaticaOK . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ERRONEAS").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $gramaticaERROR . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NUM_PREGUNTAS_BLANCO") . '</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $gramaticaBLANCO . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="font-weight:bold;font-size: 15pt;padding:0px;">' . constant("STR_COMPRENSION") . ':
    									</td>
    									<td width="1px">
    									</td>
    									<td width="10%">
    									</td>
    								</tr>
    								<tr>
    									<td colspan="2" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NUMERO_TOTAL_DE_PREGUNTAS_REFERENTES_A_COMPRENSION") . '</font>
    									</td>
    									<td style="border:3px solid #004080;" align="center">
    										<font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'. '30' . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'(P.D.)</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $comprensionOK . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ERRONEAS").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $comprensionERROR . '</font>
    									</td>
    								</tr>
    								<tr>
    									<td style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_BLANCO").'</font>
    									</td>
    									<td width="1px">
    									</td>
    									<td style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">' . $comprensionBLANCO . '</font>
    									</td>
    								</tr>
    								
    							</table>
    						</td>
    					</tr>
    	';
	    $sHtml.='			</td>
    					</tr>
    			</table>';
	if (!empty($sHtml))
	{
		$replace = array('@', '.');
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT") . '/' : constant("DIR_FS_DOCUMENT_ROOT");
		
		$_fichero = $spath . $sDirImg . $sNombre . ".html";
		//$cEntidad->chk_dir($spath . $sDirImg, 0777);
		
		if(is_file($_fichero)){
			unlink($_fichero);
		}
		error_log(utf8_decode($sHtmlInicio . $sHtml . $sHtmlFin), 3, $_fichero);
	}
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
                                                      'top'     => 10,
                                                      'bottom'  => 10
                                                      ),
                             'media'         => 'A4',
                             'method'        => 'fpdf',
                             'mode'          => 'html',
                             'output'        => 2,
                             'pagewidth'     => 1024,
                             'pdfversion'    => '1.3',
                             'ps2pdf'        => '',
                             'pslevel'       => 3,
                             'renderfields'  => 1,
                             'renderforms'   => '',
                             'renderimages'  => 1,
                             'renderlinks'   => '',
                             'scalepoints'   => 1,
                             'smartpagebreak' => '',
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
		$_NEWPAGE = '<!--NewPage-->';
		$footer_html    = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100%" align="center"><p style="font-size:11px;"> ' . mb_strtoupper($cPruebas->getNombre(), 'UTF-8') .' '.constant("STR_PIE_INFORMES").'</p></td>
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
?>