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
}
	/**
	* Informe para las pruebas de tipo TRI (Teoría de respuesta al ítem)
	**/
		$SQLPercentil = "SELECT * FROM respuestas_pruebas ";
		$SQLPercentil .= "WHERE idEmpresa= " . $_POST['fIdEmpresa'] . " ";
		$SQLPercentil .= "AND idProceso= " . $_POST['fIdProceso'] . " ";
		$SQLPercentil .= "AND idCandidato= " . $_POST['fIdCandidato'] . " ";
		$SQLPercentil .= "AND idPrueba= " . $_POST['fIdPrueba'] . " ";
		$listaPercentil = $conn->Execute($SQLPercentil);
		while(!$listaPercentil->EOF){
			$iPercentil = $listaPercentil->fields['nota_tri'];
			$listaPercentil->MoveNext();

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

		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		define ('_NEWPAGE', '<!--NewPage-->');
		$_HEADER = '';
		$sHtmlCab	= '';
		$sHtml		= '';
		$sHtmlFin	= '';
		//$aux			= $this->conn;

		$spath = (substr($dirGestor, -1, 1) != '/') ? $dirGestor . '/' : $dirGestor;

		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/resetCSS.css"/>
					<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/style.css"/>
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
						    <td class="logo">' . $cPruebas->getNombre() . '
						    <!-- <img src="'.$dirGestor.'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo-pequenio.jpg" title="logo"/> -->
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
		//PORTADA
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("3");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
		$sDescInforme = $cTextos_secciones->getTexto();

		$sHtml.= '
			<div class="pagina portada">
		    	<img src="' . $dirGestor . 'graf/prueba' . $cPruebas->getIdPrueba() . '/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
		    	<h1 class="titulo"><img src="' . $dirGestor . 'estilosInformes/prueba' . $cPruebas->getIdPrueba() . '/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_nombre_infome"><p>' . $cPruebas->getNombre() . '</p></div>';
		$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_puntos_infome"><p>' . str_repeat(".", 40) . '</p></div>';
		$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_DEF_CANDIDATO") . ':</strong><br />' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2() . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong><br />' . $cUtilidades->PrintDate(date("Y-m-d"), $_POST['fCodIdiomaIso2']) . '</p>
				</div>
				<div id="ts">
					<img src="' . $dirGestor . 'graf/TS.jpg" />
				</div>

		    	<!-- <h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2> -->
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
		$sHtml.='				<p style="text-align:center;background:#ff411d;height:25px;padding:5px;">
    								<font style="font-size: 20px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant('STR_INTRODUCCION'), 'UTF-8').'</font>
    							</p>';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("1");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones);
    	// TEXTO INTRODUCCIÓN

		$sHtml.="<br />" . str_replace('style="font-size:18px;"', 'style="font-size:14px;"',$cTextos_secciones->getTexto());

		$sHtml.='				<p align="justify" style="font-size: 14px;padding-bottom: 10px;">'.constant("STR_ESTE_INFORME_REPRESENTA").'</p>
								<p align="left">
									<ul>
										<li style="list-style: square;padding:8px;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_PUNTUACION_DIRECTA").':</font><br /><br />
    										<font style="font-size: 14px;color: #000000;">'.constant("STR_EXP_PUNT_DIRECTA").'</font>
	    								</li>
	    								<li style="list-style: square;padding:8px;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").':</font><br /><br />
    										<font style="font-size: 14px;color: #000000;">'.constant("STR_EXP_PUNT_PECENTIL").'</font>
	    								</li>
	    								<li style="list-style: square;padding:8px;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").':</font><br /><br />
    										<font style="font-size: 14px;color: #000000;">'.constant("STR_EXP_INDICE_RAPIDEZ").'</font>
	    								</li>
	    								<li style="list-style: square;padding:8px;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_INDICE_PRECISION").':</font><br /><br />
    										<font style="font-size: 14px;color: #000000;">'.constant("STR_EXP_INDICE_PRECISION").'</font>
	    								</li>
	    								<li style="list-style: square;padding:8px;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").':</font><br /><br />
    										<font style="font-size: 14px;color: #000000;">'.constant("STR_EXP_PRODUCTO_RENDIMIENTO").'</font>
	    								</li>
	    								<li style="list-style: square;padding:8px;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_ESTILO_PROCESAMIENTO_MENTAL").':</font><br /><br />
    										<font style="font-size: 14px;color: #000000;">'.constant("STR_EXP_ESTILO_PROCESAMIENTO_MENTAL").'</font>
	    								</li>
	    							</ul>
	    							<br />
	    							<br />
	    							<br /><br /><br />
								</p>';
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
		$sqllistaRespItems = "SELECT * FROM tri_init_items ";
		$sqllistaRespItems .= "WHERE idEmpresa= " . $_POST['fIdEmpresa'] . " ";
		$sqllistaRespItems .= "AND idProceso= " . $_POST['fIdProceso'] . " ";
		$sqllistaRespItems .= "AND idCandidato= " . $_POST['fIdCandidato'] . " ";
		$sqllistaRespItems .= "AND idPrueba= " . $_POST['fIdPrueba'] . " ";
		$listaRespItems = $conn->Execute($sqllistaRespItems);

		if ($listaRespItems->recordCount() > 0){
			$sUltimoItemRespondido = $listaRespItems->MoveLast();
			//Preguntar a Verdejo
			//$sUltimoItemRespondido = $listaRespItems->fields['idItem'];
			$sUltimoItemRespondido = $listaRespItems->fields['orden'];
		}
		$IR = 0.00;
		$IP = 0.00;
//		$IR = number_format($listaRespItems->recordCount() / $listaItemsPrueba->recordCount(),2);
		//IR= Último ítem respondido por el candidato/Nº total de ítems de la prueba.
		//Preguntar a Verdejo
		//en TRI no tiene definido items la prueba
		//Ponemos el número maximo definido a nivel de prueba?
		$iNum_preguntas_max_tri = $cPruebas->getNum_preguntas_max_tri();
		if ($listaRespItems->recordCount() > 0){
			//$IR = number_format($sUltimoItemRespondido / $listaItemsPrueba->recordCount(),2);
			$IR = number_format($sUltimoItemRespondido / $iNum_preguntas_max_tri,2);
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



    	$sHtml.='				<p style="text-align:center;background:#ff411d;height:25px;padding:5px;">
    								<font style="font-size: 20px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8"), 'UTF-8').'</font>
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="60%" align="center" >
    							<table class="resetTable" width="95%" style="" cellspacing="30" align="center" cellpadding="15" border="0">
    								<tr>
    									<td width="90%" style="border:3px solid #6e6e6e;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_NUM_TOTAL_PREGUNTAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #6e6e6e;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'. $iNum_preguntas_max_tri.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #6e6e6e;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #6e6e6e;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'.$iPDirecta.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #6e6e6e;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #6e6e6e;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">' .$iPercentil.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #6e6e6e;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #6e6e6e;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'.$sIR.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #6e6e6e;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_INDICE_PRECISION").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #6e6e6e;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'.$sIP.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #6e6e6e;">
    										<font style="font-size: 14px;color: #cc3300;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #6e6e6e;" align="center"><font style="font-size: 14px;color: #6e6e6e;font-weight: bold;">'.$sPOR.'</font>
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
											<table width="100%" cellspacing="0" cellpadding="0" style="background:#c0c0c0;border-left:3px solid #6e6e6e;border-right:3px solid #6e6e6e;">
												<tr>
													<td width="19%" align="center" style="height:60px;border:2px solid #6e6e6e;">
														<font style="font-size: 14px;font-weight: bold;">'.constant("STR_PUNT_INFORMES").'</font>
													</td>
													<td width="27%" align="center" style="height:60px;border:2px solid #6e6e6e;">
														<font style="font-size: 14px;font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
													</td>
													<td width="27%" align="center" style="height:60px;border:2px solid #6e6e6e;">
														<font style="font-size: 14px;font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
													</td>
													<td width="27%" align="center" style="height:60px;border:2px solid #6e6e6e;">
														<font style="font-size: 14px;font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
													</td>
												</tr>
											</table>
											<table width="100%" cellspacing="0" cellpadding="0" style="border-left:3px solid #6e6e6e;border-bottom:3px solid #6e6e6e;border-right:3px solid #6e6e6e;">
												<tr>
													<td width="19%" align="center" style="height:45px;border-right:2px solid #6e6e6e;">
														<font style="font-size: 14px;color: #000000;font-weight: bold;">PD = '.$iPDirecta.'</font>
													</td>
													<td width="81%" style="height:45px;border-left:2px solid #6e6e6e;">
														<img src="'. $dirGestor . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:540px;">
													</td>
												</tr>
												<tr>
													<td width="19%" align="center" style="height:40px;border-right:2px solid #6e6e6e;">
														<font style="font-size: 14px;color: #000000;font-weight: bold;">PC = '.$iPercentil.' %</font>
													</td>
													<td width="81%" style="height:40px;border-left:2px solid #6e6e6e;vertical-align:middle;">
														<img src="'. $dirGestor . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.(($iPercentil*540)/100).'px;height:35px;">
													</td>
												</tr>
											</table>
										</td>
                    				</tr>
                  				</table>'; 
								  include("textosPercentil.php");
								  $sHtml.=$sTextosPercentil;
		$sHtml.='			</td>
    					</tr>
    					<tr>
    						<td height="10">&nbsp;</td>
    					</tr>
    	';
    	$sHtml.='		<tr>
    						<td>
    							<p style="text-align:center;background:#ff411d;height:25px;padding:5px;">
    								<font style="font-size: 20px;color:#FFFFFF;font-weight: bold;">' . mb_strtoupper(constant("STR_ESTILO_PROCESAMIENTO_MENTAL"), 'UTF-8').'</font>
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="95%">';
		//Miramos lo que tenemos que pintar de los textos segun los rangos.
		$cRangos_textos = new Rangos_textos();
		$cRangos_textos->setIdPrueba($cPruebas->getIdPrueba());
		$cRangos_textos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    	$cRangos_textos->setIdTipoInforme($idTipoInforme);
    	$cRangos_textos->setOrderBy("`idIr` ASC, `idIp` DESC");
    	$cRangos_ir = new Rangos_ir();

    	$sqlRangosTextos = $cRangos_textosDB->readLista($cRangos_textos);


//		echo "<br />" . $sqlRangosTextos;
		$listaRangosTextos = $conn->Execute($sqlRangosTextos);

		$caseIr = 0;

		$caseIp = 0;
		$bEncontrado=false;
		$bEncontradoIp=false;

		$idRango="";
		$idRangoIp="";

		if($listaRangosTextos->recordCount()>0)
		{
			while(!$listaRangosTextos->EOF)
			{
				if(!$bEncontrado)
				{
					$cRango_ir = new Rangos_ir();

					$cRango_ir->setIdRangoIr($listaRangosTextos->fields['idIr']);
					$cRango_ir = $cRangos_irDB->readEntidad($cRango_ir);
					$aRangoSup = explode(" " , $cRango_ir->getRangoSup());
					$aRangoInf = explode(" " , $cRango_ir->getRangoInf());

					$sSignoSup = $aRangoSup[0];
					$sPuntSup = $aRangoSup[1];

					$sSignoInf = $aRangoInf[0];
					$sPuntInf = $aRangoInf[1];


//					if(($IR $sSignoSup $sPuntSup) && ($IR $sSignoInf $sPuntInf)){
//						echo $IR . " es :" . $sSignoSup . " que " . $sPuntSup . " y " . $sSignoInf . " que " . $sPuntInf;
//					}
//					echo "<br />sup: " . $sSignoSup . " inf: " . $sSignoInf;
//					echo "<br />Punt sup: " . $sPuntSup . " Punt inf: " . $sPuntInf;
//					echo "<br />IR: " . $IR ;
//					echo "<br />";

					$idRango="";

					switch ($sSignoSup)
					{
						case "<":
							switch ($sSignoInf)
							{
								case "<":
									if($IR < $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR < $sPuntSup && $IR <= $sPuntInf){
//										echo "<br />" . $IR . " es : < que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR < $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR < $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
						case "<=":
							switch ($sSignoInf)
							{
								case "<":
									if($IR <= $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR <= $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR <= $sPuntSup && $IR > $sPuntInf){
//										echo "<br />" . $IR . " es : <= que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR <= $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
						case ">":
							switch ($sSignoInf)
							{
								case "<":
									if($IR > $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR > $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR > $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR > $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
						case ">=":
							switch ($sSignoInf)
							{
								case "<":
									if($IR >= $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR >= $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR >= $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR >= $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}
							break;
					}
				}
//				echo "<br />rango " . $idRango . "<br />";


				if(!$bEncontradoIp && !empty($idRango))
				{
					$cRango_ip = new Rangos_ip();

					$cRango_ip->setIdRangoIr($idRango);
					$cRango_ip->setIdRangoIp($listaRangosTextos->fields['idIp']);
					$cRango_ip = $cRangos_ipDB->readEntidad($cRango_ip);
					$aRangoSupIp = explode(" " , $cRango_ip->getRangoSup());
					$aRangoInfIp = explode(" " , $cRango_ip->getRangoInf());

					$sSignoSupIp = $aRangoSupIp[0];
					$sPuntSupIp = $aRangoSupIp[1];
//					echo "<br />sSignoSupIp::" . $sSignoSupIp;
//					echo "<br />sPuntSupIp::" . $sPuntSupIp;
					$sSignoInfIp = $aRangoInfIp[0];
					$sPuntInfIp = $aRangoInfIp[1];
//					echo "<br />sSignoInfIp::" . $sSignoInfIp;
//					echo "<br />sPuntInfIp::" . $sPuntInfIp;
					//Ahora lo hacemos para El IP
					$idRangoIp="";

					switch ($sSignoSupIp)
					{
						case "<":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP < $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />A::" . $IP . " es : < que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP < $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />B::" . $IP . " es : < que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP < $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />C::" . $IP . " es : < que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP < $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />D::" . $IP . " es : < que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
						case "<=":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP <= $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />E::" . $IP . " es : <= que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP <= $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />F::" . $IP . " es : <= que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP <= $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />G::" . $IP . " es : <= que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP <= $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />H::" . $IP . " es : <= que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
						case ">":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP > $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />I::" . $IP . " es : > que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP > $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />J::" . $IP . " es : > que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP > $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />K::" . $IP . " es : > que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP > $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />L::" . $IP . " es : > que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
						case ">=":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP >= $sPuntSupIp && $IP < $sPuntInfIp){
//										echo "<br />M::" . $IP . " es : >= que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP >= $sPuntSupIp && $IP <= $sPuntInfIp){
//										echo "<br />N::" . $IP . " es : >= que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP >= $sPuntSupIp && $IP > $sPuntInfIp){
//										echo "<br />Ñ::" . $IP . " es : >= que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP >= $sPuntSupIp && $IP >= $sPuntInfIp){
//										echo "<br />O::" . $IP . " es : >= que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}
							break;
					}
				}
				$listaRangosTextos->MoveNext();
			}
		}
		if (empty($idRango) || empty($idRangoIp)){
			echo "ERROR calculo de rangos. Contacte con el Administrador.";
			exit;
		}
//		echo "<br />idRango::" . $idRango . " idRangoIp::" . $idRangoIp;
		$cRan_test = new Rangos_textos();
		$cRan_test->setIdPrueba($cPruebas->getIdPrueba());
		$cRan_test->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    	$cRan_test->setIdTipoInforme($idTipoInforme);
    	$cRan_test->setIdIp($idRangoIp);
    	$cRan_test->setIdIr($idRango);

    	$cRan_test = $cRangos_textosDB->readEntidad($cRan_test);
    	$sRan_test = $cRan_test->getTexto();
      $sRan_test = str_replace('font-size: 18px;', 'font-size:14px;',$sRan_test);
      $sRan_test = str_replace('list-style: square;padding:10px;', 'list-style: square;padding:8px;',$sRan_test);
      $sRan_test = str_replace('list-style:square;padding:10px;', 'list-style: square;padding:8px;',$sRan_test);
    	$sHtml .= $sRan_test;


	    $sHtml.='			</td>
    					</tr>
    			</table>
        	</div>
        	<!--FIN DIV PAGINA-->
          <hr>
    			';

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
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr($documentRoot, -1, 1) != '/') ? $documentRoot . '/' : $documentRoot;

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
