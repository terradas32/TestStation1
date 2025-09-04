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

	function baremo_C($pd)
	{
		global $dirGestor;
		global $documentRoot;
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
	//Funcion que devuelve un texto a la parte del informe de competencias de serc
	function textoDefinicion($puntuacion){
		global $dirGestor;
		global $documentRoot;
		$str="";
		if($puntuacion < 2){
			$str="Nunca o casi nunca";	//"NUNCA";
		}
		if($puntuacion == 2 ){
			$str="A veces con dificultad";	//"CASI NUNCA";
		}
		if($puntuacion >= 3 && $puntuacion <=4){
			$str="Con cierta frecuencia";	//"A VECES";
		}
		if($puntuacion == 5){
			$str="Casi siempre:";	//"CASI SIEMPRE";
		}
		if($puntuacion > 5){
			$str="Una de sus características clave es que";	//"SIEMPRE";
		}
		return $str;
	}
	//Funcion que devuelve un texto a la parte del informe de competencias de serc
	function textoPuntuacion($puntuacion){
		global $dirGestor;
		global $documentRoot;
		$str="";
		if($puntuacion > 5){
			$str="Área de clara Fortaleza.";
		}
		if($puntuacion ==5){
			$str="Área de Fortaleza.";
		}
		if($puntuacion >=3 && $puntuacion<=4){
			$str="Potencial a Desarrollar.";
		}
		if($puntuacion ==2){
			$str="Área de Desarrollo.";
		}
		if($puntuacion < 2){
			$str="Área de clara necesidad de Desarrollo.";
		}
		return $str;
	}
	function bolosPuntuacion($puntuacion){
		global $dirGestor;
		global $documentRoot;
		$str="";
		if($puntuacion < 2){
			$str='
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				';	//"ÁREA CLAVE DE MEJORA";
		}
		if($puntuacion == 2){
			$str='
				<span class="rojo">&nbsp;</span>
				<span class="rojo">&nbsp;</span>
				';	//"ÁREA POTENCIAL DESARROLLO";
		}
		if($puntuacion >= 3 && $puntuacion<= 4){
			$str='
				<span class="amarillo">&nbsp;</span>
				';	//"ÁREA EN DESARROLLO";
		}
		if($puntuacion == 5){
			$str='
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				';	//"ÁREA POTENCIAL FORTALEZA";
		}
		if($puntuacion > 5){
			$str='
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				<span class="verde">&nbsp;</span>
				';	//"ÁREA DE FORTALEZA";
		}
		return $str;
	}
	// Si llega MEJOR devolver 1
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 3
	function getInversoPrisma($valor){
		global $dirGestor;
		global $documentRoot;
		$inv=0;

		//MEJOR => 3 PEOR => 1 VACIO => 2
		switch ($valor)
		{
			case '1':	// Mejor
				$inv = 1;
				break;
			case '2':	// Peor
				$inv = 3;
				break;
			default:	// Sin contestar opcion 0 en respuestas
				$inv = 2;
				break;
		}
	//		if($valor==2){$inv=0;}
	//		if($valor==1){$inv=1;}
	//		if($valor==0){$inv=2;}
	//		echo "<br />id::" . $valor .  " - valor::" . $inv;
		return $inv;
	}


	/*
	* APTITUDES SERC
	*/
	function generarSERC($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
	{
		global $dirGestor;
		global $documentRoot;

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $sEsp;
		global $cCandidato;
		global $comboESPECIALIDADESMB;
		global $cUtilidades;

		$sSQLExport ="";
		global $aSQLPuntuacionesPPL;
		global $aSQLPuntuacionesC;
		global $cPruebas;
		global $cProceso;
		global $cRespPruebas;


		$iMC= "82";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iMC);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptMC82 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iMC, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo'], $cCandidato->getEspecialidadMB());
	//		echo "<br />[iPDirecta, iPercentil, IR, IP, POR, iItemsPrueba]";
	//		echo "<br />MC82:: ";
	//		print_r($aAptMC82);

		$iWhidth = 1;
		$iPDirecta= $aAptMC82[0];
		$iPDirectaX= $iPDirecta*10;
		$iPercentil= $aAptMC82[1];
		$iPercentilX= $iPercentil*10;

		$sHtml='
			<div class="pagina">'. $sHtmlCab;
		$sHtml.= '
				<div class="desarrollo">
					<h2 class="subtitulo">' . mb_strtoupper(constant("STR_APTITUDES"), 'UTF-8') . '</h2>
				';

		$sHtml.= '
					<table id="caja_tit" border="0">
						<tr>
							<td>TEST MEMORIA COMERCIAL</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">Esta prueba tiene como objetivo evaluar la capacidad para memorizar cifras, datos, nombres o precios. Los estudios que relacionan memoria y éxito en funciones comerciales demuestran que existe una correlación positiva entre ambos factores. No obstante, este resultado deberá ser contrastado con el resto de aspectos contemplados en el informe.</p>
					</div>
					<table id="caja_puntos" border="0">
						<tr>
							<td class="puntos_num"><span class="pdOut">P.D</span>&nbsp;&nbsp;' . $iPDirecta . '<br /><br /><span class="pdOut">P.C</span>&nbsp;&nbsp;' . $iPercentil . '</td>
							<td class="puntos_escala">
								<table width="564" cellspacing="0" cellpadding="0">
									<tr>
										<td style="height:45px;">
											<img src="'. $dirGestor . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:564px;">
										</td>
									</tr>
									<tr>

										<td width="81%" style="vertical-align:middle;">
											<img src="'. $dirGestor . constant('DIR_WS_GRAF'). 'bodoque_gigante_OLD.jpg'.'" style="width:'.(($iPercentil*564)/100).'px;height:25px;">
										</td>
									</tr>
								</table>
								<table width="564" cellspacing="0" cellpadding="0" border="0">
									<tr>

										<td width="28%" align="center" style="height:30px;border-right:2px solid #000080;">
											<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
										</td>
										<td width="30%" align="center" style="height:30px;border-right:2px solid #000080;">
											<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
										</td>
										<td width="34%" align="center" style="height:30px;">
											<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
										</td>
										<td width="19%" align="center" style="">

										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';


		$iCEC= "81";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iCEC);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptTMC81 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iCEC, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo']);
	//		echo "<br />TMC81:: ";
	//		print_r($aAptTMC81);

		$iWhidth = 1;
		$iPDirecta= $aAptTMC81[0];
		$iPDirectaX= $iPDirecta*10;
		$iPercentil= $aAptTMC81[1];
		$iPercentilX= $iPercentil*10;

		$sHtml.= '
					<table id="caja_tit" border="0">
						<tr>
							<td> TEST DE CÁLCULO DE ESTADÍSTICAS COMERCIALES</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">Esta prueba mide la capacidad para comprender tablas y datos, cifras y precios, para dar respuestas correctas a cuestiones a ellas referidas. Es un test de dificultad y valor de predicción probado para cualquier puesto que implique manejo de datos cuantitativos, como ocurre con los puestos de relación comercial y venta.</p>
					</div>
					<table id="caja_puntos" border="0">
						<tr>
							<td class="puntos_num"><span class="pdOut">P.D</span>&nbsp;&nbsp;' . $iPDirecta . '<br /><br /><span class="pdOut">P.C</span>&nbsp;&nbsp;' . $iPercentil . '</td>
							<td class="puntos_escala">
								<table width="564" cellspacing="0" cellpadding="0">
									<tr>
										<td style="height:45px;">
											<img src="'. $dirGestor . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:564px;">
										</td>
									</tr>
									<tr>
										<td width="81%" style="vertical-align:middle;">
											<img src="'. $dirGestor . constant('DIR_WS_GRAF'). 'bodoque_gigante_OLD.jpg'.'" style="width:'.(($iPercentil*564)/100).'px;height:25px;">
										</td>
									</tr>
								</table>
								<table width="564" cellspacing="0" cellpadding="0" border="0">
									<tr>

										<td width="28%" align="center" style="height:30px;border-right:2px solid #000080;">
											<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_BAJO_POTENCIAL"), 'UTF-8').'</font>
										</td>
										<td width="30%" align="center" style="height:30px;border-right:2px solid #000080;">
											<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_MEDIO_POTENCIAL"), 'UTF-8').'</font>
										</td>
										<td width="34%" align="center" style="height:30px;">
											<font style="font-weight: bold;">' . mb_strtoupper(constant("STR_ALTO_POTENCIAL"), 'UTF-8').'</font>
										</td>
										<td width="19%" align="center" style="">

										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';

				$sHtml.= '
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
					<h2 class="subtitulo">CPC INFORME EXPERTO</h2>
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">Introducción</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">Este informe está diseñado para servir de base en los procesos de selección de Vendedores de la firma. Es una interpretación experta que igualmente, podrá servir de base o ayuda-organizador a las entrevistas de selección, para complementar otros informes de evaluación o desarrollo y para predecir comportamientos laborales.</p>
						<p class="textos">El informe analiza las respuestas del/la candidato/a al Cuestionario Profesional Comercial, CPC, indicando su perfil de preferencias y actitudes en la vida laboral. Cada sección se refiere a un área del Perfil Comercial y va acompañado de la interpretación de las escalas de ese área.</p>
						<p class="textos">Se debe tener en cuenta que el perfil resultado de este Cuestionario procede de las evaluaciones o perceciones que la persona tiene de sí misma. No procede de las evaluaciones que otros hacen de él/ella. Está demostrado estadísticamente el gran valor de las autoevaluaciones. La calidad, fiabilidad y validez de este informe están condicionadas por la franqueza y colaboración con las que la persona haya respondido a las preguntas y a su nivel de autoanálisis.</p>
						<p class="textos">Este informe debe ser tratado confidencialmente. El valor de los resultados tiene una validez aproximada de 18 a 24 meses, y debe ser relacionado directamente con la situación actual y contexto del individuo.</p>
					</div>
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">Escalas Distractoras</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">Las Escalas Distractoras corresponden a preguntas no relacionadas con el ámbito comercial, y sí con puestos administrativos, de back office, técnicos o personal de producción. Las personas que responden frecuentemente a estas escalas tienen perfiles de profesionales más introvertidos o más orientados a las tareas que a las personas, y, por tanto, puntuaciones altas en estas escalas disminuirán los valores relativos a un perfil comercial. Por tanto, puntuaciones bajas nos indican una mayor orientación a funciones de vendedor/a o perfiles puramente comerciales.</p>
					</div>
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">Consistencia</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">La Escala de Consistencia nos proporciona indicadores generales del estilo de respuesta o actitud del candidato/a. Es un Cuestionario de "respuesta obligada", lo que delimita bastante la "distorsión motivacional" o tendencia a dar un perfil deseado o conveniente.</p>
						<p class="textos">La puntuación en "Consistencia" expresa el grado en que el candidato/a se ha esforzado en mantener una coherencia o congruencia entre unas y otras respuestas al Cuestionario. Normalmente esto se explica "en el sentido de dar buena imagen", pero podría ser que se diera "coherencia en dar mala imagen", para lo cual basta analizar puntuaciones estremas (9 y 10 / 1 y 2) y ver si están en los polos positivos o negativos de las escalas.</p>
						<p class="textos"><font style="font-weight: bold;">Ejemplo:<br /><br /></font>
							<img class="ejemCons" src="'.$dirGestor.'graf/serc/' . $_POST['fCodIdiomaIso2']. '/ejemploConsistenciaMB.jpg" alt="Escala Consistencia" title="Escala Consistencia" />
						</p>
						<p class="textos">En este ejemplo, el candidato/a ha hecho un esfuerzo significativo por lograr una coherencia o congruencia alta en las respuestas a las preguntas de este Cuestionario. Habrá que analizar si lo ha logrado y especialmente en que escalas. Habrá algunas escalas en las que no haya obtenido puntos altos o extremos, y serán escalas a explorar en la entrevista.</p>
					</div>

				';

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//---------------------------------

	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// PÁGINA PERFIL PERSONALIDAD LABORAL
	$iPGlobal = 0;
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo" style="text-align: left;padding-left: 12px;"> PERFIL DEL CUESTIONARIO PROFESIONAL COMERCIAL <span class="serc_cpc">CPC</span></h2>
					<table id="caja_tit_gris" border="0">
						<tr>
							<td class="blancob" style="text-align: left;height: 20px;">PUNTUACIONES BAJAS (1,2,3)</td>
							<td class="blancob" align="center">
								<table border="0" width="100%" style="width:100%;border-collapse: separate;border: 1px solid #0072a8;">
									<tr>
										<td class="blancob" style="padding-left:7px;">1</td>
										<td class="blancob" style="padding-left:7px;">2</td>
										<td class="blancob" style="padding-left:7px;">3</td>
										<td class="blancob" style="padding-left:7px;">4</td>
										<td class="blancob" style="padding-left:7px;">5</td>
										<td class="blancob" style="padding-left:7px;">6</td>
										<td class="blancob" style="padding-left:7px;">7</td>
										<td class="blancob" style="padding-left:7px;">8</td>
										<td class="blancob" style="text-align: right;">9</td>
										<td class="blancob" style="text-align: right;padding-right:5px">10</td>
									</tr>
								</table>
							</td>
							<td class="blancob "style="text-align: right;">PUNTUACIONES ALTAS (8,9,10)</td>
						</tr>
						<tr>
							<td colspan="3" class="caja_tit" style="text-align: center;">DINAMISMO Y ENERGÍA</td>
						</tr>';
						$iPBaremada = $aPuntuaciones["51-4"];	//ACTIVIDAD Y DINAMISMO
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("51", false) . "," . $conn->qstr("DINAMISMO Y ENERGÍA", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("ACTIVIDAD Y DINAMISMO", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">BAJA ACTIVIDAD Y DINAMISMO.<br />Fatigables. Poco constantes, baja tensión o vitalidad, baja energía.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ALTA ACTIVIDAD Y DINAMISMO.<br />Alto nivel de esfuerzo, perseverancia comercial, tenacidad, alta energía y disposición. Infatigables.</td>
						</tr>
						';
			$sHtml.='
						<tr>
							<td colspan="3" class="caja_tit" style="text-align: center;">SOCIABILIDAD COMERCIAL Y ORIENTACIÓN AL CLIENTE</td>
						</tr>';
						$iPBaremada = $aPuntuaciones["54-1"];	//INFLUENCIA - PERSUASIÓN
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("54", false) . "," . $conn->qstr("SOCIABILIDAD COMERCIAL Y ORIENTACIÓN AL CLIENTE", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("INFLUENCIA - PERSUASIÓN", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;
			$sHtml.='
						<tr>
							<td class="pl_Texto_L">BAJA INFLUENCIA - PERSUASIÓN.<br />Tendencia a ser complacientes. Poco influyentes, poco persuasivos/as, sin voluntad de autoafirmarse.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ALTA INFLUENCIA - PERSUASIÓN.<br />Autoafirmación, voluntad para influir y tener ascendencia.Elocuentes y eficaces hablando y convenciendo.</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["54-2"];	// ENTUSIASMO - ESPONTANEIDAD
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("54", false) . "," . $conn->qstr("SOCIABILIDAD COMERCIAL Y ORIENTACIÓN AL CLIENTE", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("ENTUSIASMO - ESPONTANEIDAD", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">SERIEDAD - BAJA ESPONTANEIDAD.<br />Contenidos/as, sin espontaneidad, reservados/as, cautos/as, poco animados/as. Trasmiten aparente frialdad o apatía.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ENTUSIASMO - ESPONTANEIDAD.<br />Optimismo, jovialidad, buen humor, positivismo, animados/as. Trasmiten confianza en sí mismos/as y entusiasmo.</td>
						</tr>';

						$iPBaremada = $aPuntuaciones["54-3"];	// SOCIABILIDAD - EXTROVERSIÓN
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("54", false) . "," . $conn->qstr("SOCIABILIDAD COMERCIAL Y ORIENTACIÓN AL CLIENTE", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("SOCIABILIDAD - EXTROVERSIÓN", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">INTROVERSIÓN - TIMIDEZ.<br />Prefieren trabajar a solas. Les cuesta iniciar y mantener relaciones. Más orientados a puestos técnicos o de oficina.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">SOCIABILIDAD - EXTROVERSIÓN.<br />Prefieren trabajar en equipo. Les gusta iniciar y mantener relaciones. Conversadores/as y animados/as en visitas o reuniones sociales.</td>
						</tr>';

						$iPBaremada = $aPuntuaciones["54-4"];	// FLEXIBILIDAD - TOLERANCIA
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("54", false) . "," . $conn->qstr("SOCIABILIDAD COMERCIAL Y ORIENTACIÓN AL CLIENTE", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("FLEXIBILIDAD - TOLERANCIA", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;
			$sHtml.='
						<tr>
							<td class="pl_Texto_L">INFLEXIBILIDAD - DUREZA. Poca flexibilidad, poca adaptabilidad. Rigidez. Prefieren no negociar y utilizar presión para vender. Se ofenden con facilidad. Pueden resultar agresivos/as.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">FLEXIBILIDAD - TOLERANCIA. Se adaptan a los clientes fácilmente. No se suelen ofender. Negocian sin presión ni agresividad.Se muestran amables y complacientes.</td>
						</tr>';
			$sHtml.='
						<tr>
							<td colspan="3" class="caja_tit" style="text-align: center;">RECURSOS MENTALES Y ORGANIZACIÓN OPERATIVA</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["55-1"];	//AGILIDAD MENTAL
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("55", false) . "," . $conn->qstr("RECURSOS MENTALES Y ORGANIZACIÓN OPERATIVA", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("AGILIDAD MENTAL - ANTICIPACIÓN", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">LENTITUD - BAJO ANÁLISIS.<br />Se les puede pasar por alto oportunidades. Poco analíticos/as. Bajo nivel de reacción y/o perspicacia.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">AGILIDAD MENTAL - ANTICIPACIÓN.<br />Atentos/as a oportunidades. Despiertos/as, con capacidad de análisis, espabilados/as o ágiles para reaccionar con prontitud.</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["55-2"];	//ORGANIZACIÓN - SISTEMÁTICA
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("55", false) . "," . $conn->qstr("RECURSOS MENTALES Y ORGANIZACIÓN OPERATIVA", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("ORGANIZACIÓN - SISTEMÁTICA", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">DESORDEN - DESORGANIZACIÓN.<br />Caóticos/as, desordenados/as, despistados/as. Pueden perder u olvidar detalles, documentos, citas o datos. Poco estructurados/as.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ORGANIZACIÓN - SISTEMÁTICA.<br />Ordenados/as, estructurados/as para sus visitas, agenda, documentos o administración comercial. Operativos/as en el trabajo.</td>
						</tr>
						';
			$sHtml.='
						<tr>
							<td colspan="3" class="caja_tit" style="text-align: center;">DECISIONES Y AMBICIONES</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["56-1"];	//CAPACIDAD Y TENDENCIA A DECIDIR
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("56", false) . "," . $conn->qstr("DECISIONES Y AMBICIONES", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("CAPACIDAD Y TENDENCIA A DECIDIR", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;


			$sHtml.='
						<tr>
							<td class="pl_Texto_L">INDECISIÓN Y BAJA AUTONOMÍA.<br />Les cuesta asumir retos o riesgos. Prefieren consultar a actuar. Convencionales y burocráticos/as.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">CAPACIDAD Y TENDENCIA A DECIDIR.<br />Autonomía y determinación. Orientados/as a asumir retos, riesgos o nuevas soluciones. Orientación a la acción.</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["56-2"];	//ORIENTACIÓN A RESULTADOS - BENEFICIOS
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("56", false) . "," . $conn->qstr("DECISIONES Y AMBICIONES", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("ORIENTACIÓN A RESULTADOS - BENEFICIOS", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">ORIENTACIÓN A NORMAS - CONFORMISMO.<br />Con poca ambición para lograr objetivos de venta. Conformados/as con lo establecido. Centrados/as en el cumplimiento de normas y tareas.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">EMPATÍA.<br />ORIENTACIÓN A RESULTADOS - BENEFICIOS.<br />Promotores/as de negocio (cifras, ventas, beneficios). Ambiciosos/as de objetivos. Buscan nuevas metas.</td>
						</tr>
						';
			$sHtml.='
						<tr>
							<td colspan="3" class="caja_tit" style="text-align: center;">EMOCIONES Y DISCIPLINA</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["57-1"];	//CONTROL EMOCIONAL
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("57", false) . "," . $conn->qstr("EMOCIONES Y DISCIPLINA", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("CONTROL EMOCIONAL", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">IMPULSIVIDAD - BAJO CONTROL.<br />Con reacciones emocionales inesperadas. Efusividad o nerviosismo. Mala imagen de sí ante los demás.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">CONTROL EMOCIONAL.<br />Buen dominio de sus impulsos y emociones. Mantienen una alta imagen de sí mismos/as ante los demás.</td>
						</tr>
						';
						$iPBaremada = $aPuntuaciones["57-2"];	// NORMAS Y REGLAS
						$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
						$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

						$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("57", false) . "," . $conn->qstr("EMOCIONES Y DISCIPLINA", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("CUMPLIMIENTO DE NORMAS Y REGLAS", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
						$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">TRANSGRESIÓN DE REGLAS Y NORMAS.<br />Sin frenos o inhibiciones para saltarse las normas o procedimientos. Pueden ser oportunistas o saltarse los procedimientos o compromisos.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">CUMPLIMIENTO DE NORMAS Y REGLAS.<br />Con valores y principios ante los clientes y ante la empresa. Respetuosos/as con políticas, normas y procedimientos, reglas y/o compromisos.</td>
						</tr>
						<tr>
							<td class="pl_img" align="center">&nbsp;</td>
							<td class="pl_img" align="center"></td>
							<td class="pl_img" align="center">&nbsp;</td>
						</tr>
						';
			$sHtml.='
						<tr>
							<td colspan="3" class="caja_tit" style="text-align: center;">ESCALAS DISTRACTORAS</td>
						</tr>
						';
			$iPBaremada = $aPuntuaciones["58-1"];	// TRABAJOS
			$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
			$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

			$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("58", false) . "," . $conn->qstr("ESCALAS DISTRACTORAS", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("ORIENTACIÓN A TRABAJOS TÉCNICOS Y/O ADMINISTRATIVOS", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
			$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">BAJA ORIENTACIÓN A TRABAJOS BUROCRÁTICOS.<br />Poco orientado/a a tareas de minuciosidad, detalle, en oficinas o trabajos en producción y /o talleres.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ALTA ORIENTACIÓN A TRABAJOS TÉCNICOS<br />Y/O ADMINISTRATIVOS. Muy orientado/a a tareas de detalle y minuciosidad, trabajos individuales en oficinas, producción y/o talleres.</td>
						</tr>
						';
			$iPBaremada = $aPuntuaciones["58-2"];	// ORIENTACIÓN
			$iPBaremada = (empty($iPBaremada)) ? 0 : $iPBaremada;
			$iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);

			$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("58", false) . "," . $conn->qstr("ESCALAS DISTRACTORAS", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("ORIENTACIÓN A PUESTOS TÉCNICOS", false) . "," . $conn->qstr("", false) . "," . $conn->qstr($iPBaremada, false) . ",now());\n";
			$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_Texto_L">BAJA ORIENTACIÓN A PUESTOS Y TAREAS que requieran técnicas de especialización, estudios y/o asesoramiento técnico.</td>
							<td class="pl_img" align="center">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $iPBaremada . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_Texto_R">ALTA ORIENTACIÓN A PUESTOS TÉCNICOS, de cálculo, asesoramiento, investigación o desarrollo. Más orientados/as a lo técnico que a la relación.</td>
						</tr>
						<tr>
							<td class="pl_img" align="center">&nbsp;</td>
							<td class="pl_img" align="center"></td>
							<td class="pl_img" align="center">&nbsp;</td>
						</tr>
						';

					$consistencia = baremo_C(number_format(sqrt($iPGlobal/14)*100 ,0));

					$sSQLExport = "INSERT INTO export_personalidad_laboral (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idBloque, nomBloque, idEscala, nomEscala, descEscala, puntuacion, fecAlta) VALUES ";
					$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("CONSISTENCIA", false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("CONSISTENCIA", false) . "," . $conn->qstr(constant("STR_PRISMA_STR_PRISMA_G_C_TXT"), false) . "," . $conn->qstr($consistencia, false) . ",now());\n";
					$aSQLPuntuacionesPPL[] = $sSQLExport;

			$sHtml.='
						<tr>
							<td class="pl_consistencia_L">CONSISTENCIA BAJA:<br />Poca congruencia en las respuestas, azar, baja motivación, indefinición.</td>
							<td class="pl_img" align="center" style="border-style: solid;border-width: 2px 1px 2px 1px;">' . '<img src="' . $dirGestor . 'graf/serc/cpc' . $consistencia . '.jpg" title="' . $iPBaremada . '" />' . '</td>
							<td class="pl_consistencia_R">CONSISTENCIA ALTA:<br />Excesiva congruencia en las respuestas, posible rigidez o por orientar su perfil.</td>
						</tr>
					</table>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//2-------------------------------------

	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">CPC     INFORME EXPERTO</h2>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">DINAMISMO, OPERATIVIDAD Y ORIENTACIÓN A RESULTADOS</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">' . Rules("ENERGIAS", $aPuntuaciones["51-4"], $aPuntuaciones["56-1"], $aPuntuaciones["56-2"]) . '</p>
					</div>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">SOCIABILIDAD COMERCIAL Y ORIENTACIÓN AL CLIENTE</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">' . Rules("SOCIABILIDAD", $aPuntuaciones["54-3"], $aPuntuaciones["54-1"], $aPuntuaciones["54-4"]) . '</p>
					</div>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">RECURSOS MENTALES Y ORGANIZACIÓN OPERATIVA</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">' . Rules("RECURSOS", $aPuntuaciones["55-1"], $aPuntuaciones["55-2"], 0) . '</p>
					</div>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">AUTOIMAGEN, CONTROLES Y DISCIPLINA</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">' . Rules("EMOCIONES", $aPuntuaciones["54-2"], $aPuntuaciones["57-1"], $aPuntuaciones["57-2"]) . '</p>
					</div>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">ESCALAS DISTRACTORAS</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">' . Rules("EDISTRAC", $aPuntuaciones["58-2"], $aPuntuaciones["58-1"], 0) . '</p>
					</div>

				';
	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//**************************
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">CPC</td>
						</tr>
					</table>
					<table id="caja_tit" style="height: 50px !important;" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">Cuestionario Profesional Comercial</td>
						</tr>
					</table>
					<div class="caja">
							<h2 class="subtitulo">Introducción</h2>
							<div class="caja">
							<p class="textos">Este informe competencial se obtiene como resultado de aplicación informatizada del Cuestionario Profesional Comercial CPC. En él se indican de manera resumida, las principales Áreas de Fortaleza, Áreas de Potencial Desarrollo y Áreas que sería recomendable desarrollar para puestos comerciales en la firma Mercedes Benz.</p>
								<p class="textos">Para la realización de este informe, han sido consideradas las competencias esenciales para ocupar puestos de Vendedor en la Firma. Para la determinación de las Áreas de Fortaleza y Áreas de Desarrollo, se han tenido en cuenta las definiciones y los principales indicadores que constituyen el perfil ideal de un Vendedor de Mercedes Benz.</p>
								<p class="textos"><font class="negrob">Cuestionario Profesional Comercial, CPC</font></p>
								<p class="textos">Este Cuestionario describe preferencias y actitudes en relación a once escalas directas y tres escalas de control (escalas distractoras y consistencia), en relación a aspectos correspondientes a las funciones comerciales. No es un test, sino un Cuestionario donde se indican las preferencias de la persona, así como su estilo personal y profesional. El Cuestionario genera un perfil de personalidad haciendo comparaciones con colectivos de profesionales del área comercial y vendedores.</p>
								<p class="textos">Este Cuestionario no es infalible, y, como todo Cuestionario de personalidad laboral, su validez depende de la honestidad y sinceridad con la que haya sido respondido por los/las candidatos/as.</p>
								<p class="textos">El informe, también refleja la manera en la que la persona ha descrito su estilo de trabajo con respecto a 7 competencias esenciales. Nos facilita información correspondiente a las principales fortalezas, áreas de desarrollo y potencial a desarrollar.</p>
								<p class="textos">No se puede considerar esta información como definitiva, sino que constituye un documento orientativo para la toma de decisiones en selección, y como documento de análisis para el desarrollo de los planes de formación y/o desarrollo futuros en la organización.</p>
						</div>
					</div>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';



	//3-------------------------------------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
					<div class="caja">
						<p class="textos">Este informe competencial es generado desde los resultados de un Cuestionario respondido por los/as candidatos/as y refleja las respuestas dadas por ellos/as en el mismo. Es necesario tener siempre en consideración la posible subjetividad de la autoevaluación en la interpretación. Este informe se genera de una manera electrónica, por lo que siempre recomendamos que se complemente con información relativa a otras técnicas como la entrevista personal.</p>
			</div>
			<div class="caja" style="margin-top:20px !important;">
						<table border="0" style="margin-left: 5px;">
							<tr>
								<td rowspan="7" class="blancob" style="text-align: center;background-color: #000080; width: 184px;">COMPETENCIAS<br />IDEALES</td>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td width="460px">Integridad Personal</td>
							</tr>
							<tr>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td >Habilidades de Comunicación e Interpersonales</td>
							</tr>
							<tr>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td >Profesional, Interés por Aprender y por Involucrarse</td>
							</tr>
							<tr>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td >Agilidad Mental</td>
							</tr>
							<tr>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td >Acción Independiente y Sistemática</td>
							</tr>
							<tr>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td >Grado de Cooperación y Habilidad para Trabajar en Equipo</td>
							</tr>
							<tr>
									<td style="text-align: center;width: 50px;"><img src="'.$dirGestor.'graf/serc/flechita.jpg" title="Flecha" /></td>
									<td >Habilidades Metodológicas</td>
							</tr>
						</table>
				</div>
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: left;">Competencias: Interpretación de indicadores y símbolos</td>
						</tr>
					</table>
				<div class="caja">
						<p class="textos">Los siguientes símbolos e indicadores de color, son los que aparecen en el informe para la identificación rápida del nivel en el que se ha descrito el candidato/a en las diferentes competencias:</p>
				</div>
				<div class="caja">
						<p class="textos" style="font-size: 12px;color:#000080;font-weight: bold;">Indicadores y símbolos</p>
				</div>


		<table class="cajaTextos" border="0" width="100%" style="width:100%;border-spacing: 4px;border-collapse: separate;">
			<tr>
			<td class="cajaTextos">
							<p class="indicadores"><span class="verde">&nbsp;</span><span class="verde">&nbsp;</span><span class="verde">&nbsp;</span> <span class="txt" style="margin-left: 12px;">= Área de clara Fortaleza.</span></p>
						</td>
			</tr>
			<tr>
						<td class="cajaTextos">
							<p class="indicadores"><span class="verde">&nbsp;</span><span class="verde">&nbsp;</span> <span class="txt" style="margin-left: 26px;">= Área de Fortaleza.</span></p>
			</td>
			</tr>
			<tr>
						<td class="cajaTextos">
							<p class="indicadores"><span class="amarillo">&nbsp;</span><span class="txt" style="margin-left: 40px;">= Potencial a Desarrollar.</span></p>
			</td>
			</tr>
			<tr>
						<td class="cajaTextos">
							<p class="indicadores"><span class="rojo">&nbsp;</span><span class="rojo">&nbsp;</span> <span class="txt" style="margin-left: 26px;">= Área de Desarrollo.</span></p>
			</td>
			</tr>
			<tr>
						<td class="cajaTextos">
							<p class="indicadores"><span class="rojo">&nbsp;</span><span class="rojo">&nbsp;</span> <span class="rojo">&nbsp;</span> <span class="txt" style="margin-left: 13px;">= Área con clara necesidad de Desarrollo.</span></p>
			</td>
			</tr>
					</table>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//4-------------------------------------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
					<div class="cajaTextos-resultados">
		';

	$iPuntacion = $aPuntuacionesCompetencias["1-1"];	// Integridad Personal


	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SERC", false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("Integridad Personal", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td class="cajaColor dato-informe" >Integridad Personal</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Su conducta es auténtica y transparente generando confianza y credibilidad.</td>
							</tr>
							<tr>
									<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Actúa con una personalidad que convence.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Actúa con empatía hacia los clientes.</td>
							</tr>
						</table>
					</div>';

	//----------
	$sHtml.='
					<div class="cajaTextos-resultados">
		';

	$iPuntacion = $aPuntuacionesCompetencias["1-2"];	// Habilidades de Comunicación e Interpersonales


	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SERC", false) . "," . $conn->qstr("2", false) . "," . $conn->qstr("Habilidades de Comunicación e Interpersonales", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td width="350" class="cajaColor dato-informe" >Habilidades de Comunicación e Interpersonales</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Dispone de habilidad social para el contacto interpersonal.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra escucha activa y aceptación.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Tiene capacidad de argumentación y transmite entusiasmo.</td>
							</tr>
						</table>
					</div>';

	//----------
	$sHtml.='
					<div class="cajaTextos-resultados">
		';
	$iPuntacion = $aPuntuacionesCompetencias["1-3"];	// Profesional, Interés por Aprender y por Involucrarse

	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SERC", false) . "," . $conn->qstr("3", false) . "," . $conn->qstr("Profesional, Interés por Aprender y por Involucrarse", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td width="350" class="cajaColor dato-informe" >Profesional, Interés por Aprender y por Involucrarse</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra actitud hacia el servicio.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra una actitud de lealtad y compromiso con la empresa y sus normas.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra iniciativa e involucración con la empresa.</td>
							</tr>
						</table>
					</div>';
	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//---------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
		';
	$sHtml.='
					<div class="cajaTextos-resultados">
		';
	$iPuntacion = $aPuntuacionesCompetencias["1-4"];	// Agilidad Mental

	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SERC", false) . "," . $conn->qstr("4", false) . "," . $conn->qstr("Agilidad Mental", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td width="350" class="cajaColor dato-informe" >Agilidad Mental</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Tiene creatividad para encontrar nuevas ideas.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Dispone de habilidad para resolver problemas y adaptarse a nuevas situaciones.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra capacidad intelectual (comprobar con pruebas de aptitud).</td>
							</tr>
						</table>
					</div>';
	//-----------
	$sHtml.='
					<div class="cajaTextos-resultados">
		';
	$iPuntacion = $aPuntuacionesCompetencias["1-5"];	// Acción Independiente y Sistemática

	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SERC", false) . "," . $conn->qstr("5", false) . "," . $conn->qstr("Acción Independiente y Sistemática", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td width="350" class="cajaColor dato-informe" >Acción Independiente y Sistemática</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Se orienta al objetivo y a conseguir los objetivos propios con determinación.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Dispone de control personal que le permite tener habilidad organizacional.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Asume retos y toma sus propias decisiones a nuevas situaciones con independencia y autonomía.</td>
							</tr>

						</table>
					</div>';
	//--------
	$sHtml.='
					<div class="cajaTextos-resultados">
		';
	$iPuntacion = $aPuntuacionesCompetencias["1-6"];	// Grado de Cooperación y Habilidad para Trabajar en Equipo

	$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
	$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr("1", false) . "," . $conn->qstr("COMPETENCIAS SERC", false) . "," . $conn->qstr("6", false) . "," . $conn->qstr("Grado de Cooperación y Habilidad para Trabajar en Equipo", false) . "," . $conn->qstr(textoPuntuacion($iPuntacion), false) . "," . $conn->qstr($iPuntacion, false) . ",now());\n";
	$aSQLPuntuacionesC[] = $sSQLExport;

	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td width="350" class="cajaColor dato-informe" >Grado de Cooperación y Habilidad para Trabajar en Equipo</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Habilidad para gestionar el conflicto y aceptar la crítica.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra tolerancia y aceptación de los demás, balanceando de forma activa diferentes intereses.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Demuestra actitud para el trabajo en equipo.</td>
							</tr>

						</table>
					</div>';
	//----------------
	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//--------------------
	//---------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
		';
	$sHtml.='
					<div class="cajaTextos-resultados">
		';
	$iPuntacion = $aPuntuacionesCompetencias["1-7"];	// Habilidades Metodológicas
	$sHtml.='
						<table width="100%" border="0">
							<tr>
								<td width="350" class="cajaColor dato-informe" >Habilidades Metodológicas</td>
								<td width="7">&nbsp;</td>
								<td width="345" class="autoevaluacion"><p>' . textoPuntuacion($iPuntacion)  . '</p>
								' . bolosPuntuacion($iPuntacion) . '
								</td>
							</tr>
						</table>
					</div>
					<span class="negrob" style="line-height: 27px;">' . textoDefinicion($iPuntacion) . ':</span>
					<div class="cajaTextos-resultados">
						<table width="100%" border="0">
							<tr>
								<td width="15"><span  class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Analiza las necesidades del cliente y sus requerimientos.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Prepara y hace seguimiento de los contactos y acciones comerciales.</td>
							</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
							<tr>
								<td width="15"><span class="naranja" style="width:5px;height: 5px;">&nbsp;</span></td>
								<td width="7">&nbsp;</td>
								<td width="680" class="desComportamiento">Trabaja con sistemática y siguiendo los procedimientos.</td>
							</tr>

						</table>
					</div>';
	//--------------------

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//---------*-*-*-*-*
	/*
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;
	// INFORME DE COMPETENCIAS
	$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">INFORME DE COMPETENCIAS</h2>
					<div class="caja">
						<p class="textos">Acerca del Informe Experto SERC</p>
					</div>
					<div class="caja">
						<p class="textos">El informe que Vd ha recibido es generado desde el Sistema Experto de Psicólogos Empresariales SERC (Sistema de Evaluación Red Comercial). Incluye información relativa al perfil gráfico del Cuestionario Profesional Comercial. El uso de este Cuestionario está limitado a aquellas personas que tengan formación y/o autorización para asegurar su correcta interpretación.</p>
						<p class="textos">Este informe competencial es generado desde los resultados de un Cuestionario respondido por los/as candidatos/as y refleja las respuestas dadas por ellos/as en el mismo. Es necesario tener siempre en consideración la posible subjetividad de la autoevaluación en la interpretación. Este informe se genera de una manera electrónica, por lo que siempre recomendamos que se complemente con información relativa a otras técnicas como la entrevista personal.</p>
						<p class="textos">Dei Consultores y Psicólogos Empresariales, no se hacen responsables de posibles modificaciones o cambios en el informe escrito o contenidos que hayan sido generados de manera automática en el original.</p>
					</div>
					<h2 class="subtitulo">PERFIL PARA ENVÍO A LA ACADEMIA DE FORMACIÓN</h2>
					<div class="caja">
						<p class="textos">A continuación se le presenta un informe "resumen" que deberá ser enviado a la Academia de Formación, únicamente en el caso de vendedores/as que vayan a ser incorporados a la firma.</p>
						<p class="textos">La dirección de correo electrónico a la que debe ser enviado, será la siguiente:</p>
						<p class="textos">academiadeformacion@daimler.com</p>
						<p class="textos">Por favor, complete con sus comentarios de entrevista, todos los espacios que aparecen en blanco.</p>
						<p class="textos">Este documento es confidencial, y su objetivo será poder establecer las posibles acciones de desarrollo profesional que Vd. indique para el profesional que desea incorporar en su concesión.</p>
					</div>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	*/
		return $sHtml;
	}
	//**************---------------------*********************-------------------
	/*
	* APTITUDES SERC
	*/
	function generarEntrevista($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
	{
		global $dirGestor;
		global $documentRoot;

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $sEsp;
		global $cCandidato;
		global $comboESPECIALIDADESMB;
		global $cUtilidades;


		$sHtml='
			<div class="pagina">'. $sHtmlCab;

		$sHtml.='
				<div class="desarrollo">
					<h2 class="subtitulo">GUÍA PARA LA ENTREVISTA COMPETENCIAL</h2>
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;">INTRODUCCIÓN</td>
						</tr>
					</table>
					<div class="caja">
						<p class="textos">A continuación se le presentarán una serie de competencias propias de Vendedores de Mercedes Benz.</p>
						<p class="textos">Cada competencia aparece en un cuadro que contiene:</p>
						<p class="textos">&nbsp;&nbsp;&nbsp;&nbsp;La definición de la competencia</p>
						<p class="textos">&nbsp;&nbsp;&nbsp;&nbsp;Los indicadores de la competencia</p>
						<p class="textos">&nbsp;&nbsp;&nbsp;&nbsp;Preguntas específicas para el sondeo en entrevista de la competencia</p>
						<p class="textos">El guión de preguntas que le indicamos sigue la estructura de una entrevista basada en competencias. El objetivo es el de sondear, a través del guión que le proponemos, aquellas áreas en las que el candidato/a se haya posicionado por debajo del nivel deseado para el óptimo desempeño en su puesto.</p>
						<p class="textos">Aquellas competencias sobre las que se recomienda profundizar aparecerán encabezadas por una etiqueta roja en la que figurará “área susceptible de sondear”.</p>
					</div>
				';

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';

	//---------------------------------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;

	$sHtml.='
				<div class="desarrollo">
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;line-height:25px;">GUÍA ESTRUCTURADA PARA LA ENTREVISTA<br />(Áreas de desarrollo)</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px;">
						<p class="textos" style="text-align: center;">' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() . '</p>
					</div>';
					$iPuntacion = $aPuntuacionesCompetencias["1-1"];	// Integridad Personal
					$sColor="white";
					if ($iPuntacion < 4 ){
						$sColor="red";
					}
	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
				<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Integridad Personal.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ Defina a una persona que sea para Usted modelo de Integridad. ¿Qué características posee?</p>
									<p class="textos">2/ Descríbame alguna ocasión en las que haya tenido que convencer a alguien y haya tenido éxito. ¿Cómo actúa cuando tiene éxito convenciendo a otros?</p>
									<p class="textos">3/ Cuénteme una situación que haya sido especialmente difícil en su trayectoria (profesional/estudios). ¿Cuál fue la dificultad? ¿Cómo actuó? ¿Cuál fue el resultado? ¿Qué haría de forma diferente?</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Actuar coherentemente con los valores personales, con autoconfianza y seguridad en las capacidades individuales, con empatía y respeto por el otro, responsabilizándose de las acciones que lleva a cabo y sus consecuencias, con capacidad para sobreponerse a las dificultades y problemas y extraer una visión o un aprendizaje positivo de ello.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Expresa sus ideas y pareceres de forma abierta y sincera.</p>
									<p class="textos">- Transmi te credibi l idad y conf ianza cuando argumenta sus ideas.</p>
									<p class="textos">- Consigue convencer y/o persuadir a través de su personalidad.</p>
									<p class="textos">- Supera las dificultades y sale reforzado de ellas.</p>
									<p class="textos">- Actúa con resolución y autonomía.</p>
								</td>
							</tr>
							<tr>
								<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
								</td>
							</tr>
						</table>
					</div>
				';
	//------********--------
					$iPuntacion = $aPuntuacionesCompetencias["1-7"];	// Habilidades Metodológicas
					$sColor="white";
					if ($iPuntacion < 4 ){
						$sColor="red";
					}

	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
						<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Habilidades metodológicas.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ Indique una situación profesional en la que haya sido especialmente metódico/a o sistemático/a. ¿Qué es lo que más le costó? ¿Y lo que menos? ¿Cómo podría mejorar?</p>
									<p class="textos">2/ Cuando tiene que iniciar un proyecto, ¿qué pasos o fases sigue habitualmente? ¿Qué hace cuando se producen desviaciones del objetivo inicial?</p>
									<p class="textos">3/ Qué aspectos suelen hacerle que no pueda no cumplir los plazos o tiempos e s t a b l e c i d o s ? ¿ C ómo g e s t i o n a habitualmente estas situaciones? ¿Conoce a alguien que sea eficaz planificando? ¿En qué se parecen y en qué se diferencian?</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Trabaja de forma metódica y sistemática, tanto a nivel interno (gestión interna) como externo (con clientes) haciendo uso de herramientas de gestión que faciliten el orden y la prioridad en lo que respecta al trato con clientes, objetivos comerciales, optimización procesos, etc. Diseña y utiliza herramientas que redunden en una gestión eficiente de los procedimientos.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Establece acciones y prevé acontecimientos con antelación.</p>
									<p class="textos">- Diseña y utiliza herramientas que facilitan la gestión de la actividad.</p>
									<p class="textos">- Organiza de manera eficaz su tiempo y el de los demás.</p>
									<p class="textos">- Define los objetivos de los proyectos de forma precisa, estableciendo con claridad los pasos y las acciones para lograr los objetivos predefinidos.</p>
									<p class="textos">- Es metódico en sus enfoques.</p>
									<p class="textos">- Optimiza el tiempo disponible de forma eficaz.</p>
									<p class="textos">- Tiene presentes las funciones y los objetivos en su trabajo.</p>
								</td>
							</tr>
							<tr>
					<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
					</td>
							</tr>
						</table>
					</div>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';
	///
	//---------------------------------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;

	$sHtml.='
				<div class="desarrollo">
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;line-height:25px;">GUÍA ESTRUCTURADA PARA LA ENTREVISTA<br />(Áreas de desarrollo)</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px;">
						<p class="textos" style="text-align: center;">' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() . '</p>
					</div>';

					$iPuntacion = $aPuntuacionesCompetencias["1-2"];	// Habilidades de Comunicación e Interpersonales
					$sColor="white";
					if ($iPuntacion < 4 ){
						$sColor="red";
					}

	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
						<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Habilidades de comunicación e interpersonales.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ Imagínese que está en la concesión y está pendiente de que el taller le informe de la llegada de una pieza, para hablar con su cliente de la fecha de entrega del coche. Su cliente ha ido a reclamar la espera y usted, para tranquilizarle, le ha llevado al taller a preguntar al responsable por la pieza. Cuál ha sido su sorpresa, cuando le ha dicho que tenía la pieza hace una semana. ¿Cómo actuaría teniendo en cuenta que está con el cliente?</p>
									<p class="textos">2/ Imagínese que yo soy su cliente y tengo fama de mal carácter, desconfiado y algo tacaño. He venido a la concesión para realizar pruebas dinámicas de un vehículo que estoy pensando adquirir y cuando llego, usted me tiene que decir que ese día no hay posibilidad de realizar la demostración, ¿cómo me convencería para que volviera otro día?</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Se relaciona e interactúa con los demás de modo receptivo y efectivo. Se comunica de forma clara, sencilla, estructurando lo que quiere transmitir, consiguiendo que los demás se sientan cómodos, generando armonía y consenso. Demuestra respeto a diversas posturas e ideas, y maneja de forma positiva los desacuerdos o conflictos personales.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Expresa sus ideas y pareceres de forma abierta y sincera.</p>
									<p class="textos">- Transmite credibi l idad y conf ianza cuando argumenta sus ideas.</p>
									<p class="textos">- Consigue convencer y/o persuadir a través de su personalidad.</p>
									<p class="textos">- Supera las dificultades y sale reforzado de ellas.</p>
									<p class="textos">- Actúa con resolución y autonomía.</p>
								</td>
							</tr>
							<tr>
					<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
					</td>
							</tr>
						</table>
					</div>
				';
	//------********--------
					$iPuntacion = $aPuntuacionesCompetencias["1-6"];	// Grado de Cooperación y Habilidad para Trabajar en Equipo
					$sColor="white";
					if ($iPuntacion < 4 ){
						$sColor="red";
					}

	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
						<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Grado de Cooperación y habilidad para trabajar en equipo.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ Según su criterio, ¿qué hace ser eficaz a un equipo? ¿Cómo contribuye usted para que éste sea eficaz?</p>
									<p class="textos">2/ Cuando no está en un equipo de trabajo, ¿qué se pierde éste?</p>
									<p class="textos">3/ En su trayectoria profesional o académica, habrá tenido que trabajar en equipo. Me gustaría que me contara una situación en la que el equipo no funcionó. ¿Por qué fue? ¿Qué dificultades tuvo el equipo? ¿Qué aportaciones personales hizo para solucionarlo?</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Colabora y trabaja adecuadamente con los demás en la consecución de objetivos comunes, compartiendo la información disponible. Promueve la participación entre los miembros del equipo, mostrándose receptivo y flexible ante las ideas de los demás. En su relación con los demás, ayuda a compañeros, genera buen clima de trabajo.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Acepta y apoya las decisiones del equipo.</p>
									<p class="textos">- Respeta opiniones diferentes a las suyas.</p>
									<p class="textos">- Contribuye al equipo con sugerencias e impulso.</p>
									<p class="textos">- Busca el consenso. Actúa de mediador.</p>
									<p class="textos">- Incorpora en sus argumentos las ideas de otros.</p>
									<p class="textos">- Acepta y respeta ideas que difieran de la suya.</p>
									<p class="textos">- Comparte información con el resto del equipo, y se interesa por sus opiniones.</p>
								</td>
							</tr>
							<tr>
					<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
					</td>
							</tr>
						</table>
					</div>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';
	///
	///***
	///
	//---------------------------------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;

	$sHtml.='
				<div class="desarrollo">
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;line-height:25px;">GUÍA ESTRUCTURADA PARA LA ENTREVISTA<br />(Áreas de desarrollo)</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px;">
						<p class="textos" style="text-align: center;">' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() . '</p>
					</div>';

					$iPuntacion = $aPuntuacionesCompetencias["1-5"];	// Acción Independiente y Sistemática
					$sColor="white";
					if ($iPuntacion < 4 ){
						$sColor="red";
					}

	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
						<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Acción Independiente y Sistemática.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ Póngame un ejemplo de una situación en la que tuviera que afrontar una operación complicada, en un plazo de tiempo preestablecido y no escatimara esfuerzos para conseguirlo, obteniendo unos buenos resultados. ¿Qué hizo exactamente? ¿Cómo se sintió?</p>
									<p class="textos">2/ Descríbame una situación en la que tuvo que conseguir unos resultados con unas condiciones difíciles y desistiera. ¿A qué fue debido? ¿Cuál fue su actuación? ¿Cómo se sintió en esta ocasión?</p>
									<p class="textos">3/ Piense en una persona que conozca, que usted considere que destaca por su constancia y tenacidad en el trabajo. ¿Qué debería mejorar usted para lograr una actuación similar?</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Se refiere a la autodeterminación para conseguir los objetivos o metas deseadas, mostrando constancia y tenacidad en el logro de sus objetivos, terminando lo que inicia incluso en situaciones adversas. Muestra independencia de criterio y es capaz de tomar decisiones y posicionarse, incluso en momentos difíciles.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Se muestra tenaz y persistente en la consecución de los objetivos propuestos.</p>
									<p class="textos">- Defiende sus ideas ante los demás.</p>
									<p class="textos">- No se rinde en la defensa de sus objetivos.</p>
									<p class="textos">- Se centra en completar la tarea.</p>
									<p class="textos">- Consigue los resultados esperados.</p>
									<p class="textos">- Manifiesta impulso por conseguir los resultados.</p>
									<p class="textos">- Es sistemático y metódico cuando expone su trabajo/ideas.</p>
								</td>
							</tr>
							<tr>
					<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
					</td>
							</tr>
						</table>
					</div>
				';
	//------********--------
	$iPuntacion = $aPuntuacionesCompetencias["1-3"];	// Profesional, Interés por Aprender y por Involucrarse
	$sColor="white";
	if ($iPuntacion < 4 ){
	$sColor="red";
	}

	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
						<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Profesional, interés por aprender y por involucrarse.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ A nivel profesional ¿cuáles son sus metas u objetivos a tres años? ¿Dónde le gustaría estar o qué le gustaría estar haciendo?</p>
									<p class="textos">2/ Piense en una persona que destaque por su compromiso con la empresa. ¿Cómo actúa? ¿Dónde se sitúa con respecto a ella? ¿Qué debería mejorar?</p>
									<p class="textos">3/ Identifique alguna situación en la que ha conseguido hacer algún cambio y/o mejora. ¿Qué hizo? ¿Qué beneficios supuso esa mejora? ¿Qué fue lo más difícil en esa situación para Usted?</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Muestra implicación y compromiso por su desarrollo profesional. Se muestra motivado por el aprendizaje y la asunción de retos. Asume la responsabilidad de su propio desarrollo buscando activamente oportunidades de formación y/o carrera profesional con iniciativa hacia los procesos, proyectos y acontecimientos de su entorno, reconociendo y actuando ante las oportunidades que se presentan y aportando ideas de mejora o soluciones por sí mismo.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Busca oportunidades activamente para mejorar lo que existe, o aporta nuevas soluciones.</p>
									<p class="textos">- Identifica nuevas necesidades con el fin de ofrecer valor añadido.</p>
									<p class="textos">- Tiene claros sus objetivos y plantea las acciones a seguir para lograrlo.</p>
									<p class="textos">- Muestra interés por aprender y conseguir o recopilar más información.</p>
									<p class="textos">- Muestra una actitud de disponibilidad y de ayuda cuando se relaciona con otros.</p>
								</td>
							</tr>
							<tr>
					<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
					</td>
							</tr>
						</table>
					</div>
				';

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';
	///***
	//----------------------***********------------------
	///
	//---------------------------------
	$sHtml.='
			<div class="pagina">'. $sHtmlCab;

	$sHtml.='
				<div class="desarrollo">
					<table id="caja_tit" border="0">
						<tr>
							<td class="blancob" style="text-align: center;line-height:25px;">GUÍA ESTRUCTURADA PARA LA ENTREVISTA<br />(Áreas de desarrollo)</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px;">
						<p class="textos" style="text-align: center;">' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() . '</p>
					</div>';

					$iPuntacion = $aPuntuacionesCompetencias["1-4"];	// Agilidad Mental
					$sColor="white";
					if ($iPuntacion < 4 ){
						$sColor="red";
					}

	$sHtml.='
					<table width="100%" border="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="60%"class="blancob" style="background-color:' . $sColor . ';text-align: center;">Área recomendada a sondear</td>
						</tr>
					</table>
					<div class="caja">
						<table border="0" >
							<tr>
								<td width="210"><b class="negrob">Competencia:</b> Agilidad Mental.</td>
								<td width="10">&nbsp;</td>
								<td width="277" class="negrob">Definición de la competencia:</td>
							</tr>
							<tr>
								<td>
									<b class="negrob">Preguntas posibles:</b><br /><br />
									<p class="textos">1/ Indique una situación difícil en la Vd. tuviera que buscar una solución rápida. ¿Qué pasos dio para llegar a la solución? ¿En base a qué eligió la/s solución/es aportadas?</p>
									<p class="textos">2/ Cuál ha sido la pr incipal dificultad/problema que ha tenido que resolver en su trayectoria? ¿Cuál fue el resultado de la opción elegida? Si volviera a encontrarse con esa situación qué haría diferente? ¿Por qué?</p>
									<p class="textos">3/ Qué tipo de problemáticas son las que mejor resuelve habitualmente? Descríbame los pasos esenciales para poder resolver los problemas con éxito.</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p class="textos">Se refiere a la autodeterminación para conseguir los objetivos o metas deseadas, mostrando constancia y tenacidad en el logro de sus objetivos, terminando lo que inicia incluso en situaciones adversas. Muestra independencia de criterio y es capaz de tomar decisiones y posicionarse, incluso en momentos difíciles.</p>
									<b class="negrob">Indicadores:</b><br /><br />
									<p class="textos">- Se muestra tenaz y persistente en la consecución de los objetivos propuestos.</p>
									<p class="textos">- Defiende sus ideas ante los demás.</p>
									<p class="textos">- No se rinde en la defensa de sus objetivos.</p>
									<p class="textos">- Se centra en completar la tarea.</p>
									<p class="textos">- Consigue los resultados esperados.</p>
									<p class="textos">- Manifiesta impulso por conseguir los resultados.</p>
									<p class="textos">- Es sistemático y metódico cuando expone su trabajo/ideas.</p>
								</td>
							</tr>
							<tr>
					<td colspan="3" align="right">
						<table border="0" style="border-collapse: separate;border: 1px solid #fff;">
						<tr>
							<td width="365" >&nbsp;</td>
							<td class="negrob" style="width: 100px;">Puntuación</td>
							<td align="center" width="50" height="30" style="border: 2px solid #c0c0c0;">' . $iPuntacion . '</td>
						</tr>
						</table >
					</td>
							</tr>
						</table>
					</div>
				';
	//------********--------

	$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
			';
	///***

		return $sHtml;
	}

	//**************---------------------*********************-------------------
		/*
	* Imforme Academia de formación
	*/
	function generarAcademia($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab, $idIdioma)
	{
		global $dirGestor;
		global $documentRoot;

		global $conn;
		global $cBloquesDB;
		global $cEscalasDB;
		global $cEscalas_itemsDB;
		global $sEsp;
		global $cCandidato;
		global $comboESPECIALIDADESMB;
		global $cUtilidades;

		$iCCT= "84";
		$cPrc_infDB = new Proceso_informesDB($conn);	// Entidad DB
		$cPrc_inf =  new Proceso_informes();

		$cPrc_inf->setIdEmpresa($_POST['fIdEmpresa']);
		$cPrc_inf->setIdProceso($_POST['fIdProceso']);
		$cPrc_inf->setIdPrueba($iCCT);
		$sSQLPrc_inf = $cPrc_infDB->readLista($cPrc_inf);

		$rsPrc_inf = $conn->Execute($sSQLPrc_inf);

		$aAptCCT84 = calalculaAPTITUDES($_POST['fIdEmpresa'], $_POST['fIdProceso'], $iCCT, $rsPrc_inf->fields['codIdiomaIso2'], $_POST['fIdCandidato'], $rsPrc_inf->fields['idBaremo'], $cCandidato->getEspecialidadMB());
	//				echo "<br />[iPDirecta, iPercentil, IR, IP, POR, iItemsPrueba]";
	//				echo "<br />CUESTIONARIO DE CONOCIMIENTOS TÉCNICOS - CCT84:: ";
	//				print_r($aAptCCT84);


		//------------------------------ INFORME PARA LA ACADEMIA ---------------
		$sHtml='';
		//---------
		$sHtml.='
			<div class="pagina">';
		// INFORME ACADEMIA
		$rsEspecialidades = $comboESPECIALIDADESMB->getDatos();
		$rsEspecialidades->Move(0);

		$sHtml.='
				<div class="desarrollo">
					<table width="100%" >
						<tr>
							<td width="100%" align="center">
								<img src="' . $dirGestor . 'graf/seas/logoMB.jpg" title="logo MB"/>
							</td>
						</tr>
					</table>
					<h2 class="subtituloFormacion">SOLICITUD DE DESARROLLO DE PLAN PERSONALIZADO DE FORMACION PARA NUEVAS INCORPORACIONES</h2>
					<table width="100%" style="font-size: 14px;" >
						<tr>
							<td colspan="2" class="negrob">DATOS PERSONALES NUEVA INCORPORACION</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
						<tr>
							<td class="negro" width="200">NOMBRE Y APELLIDOS:</td>
							<td>&nbsp;' . $cCandidato->getNombre() . ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">EDAD:</td>
							<td>&nbsp;' . $cUtilidades->calculaedad($cCandidato->getFechaNacimiento()) . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">E-MAIL:</td>
							<td>&nbsp;' . $cCandidato->getMail() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">CONCESIÓN / TALLER AUTORIZADO:</td>
							<td>&nbsp;' . $cCandidato->getConcesionMB() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">BASE:</td>
							<td>&nbsp;' . $cCandidato->getBaseMB() . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>

					<table width="100%" style="font-size: 14px;">
						<tr><td colspan="2" class="negrob">ESPECIALIDAD</td></tr>
						<tr><td colspan="2" >&nbsp;</td></tr>';

		while(!$rsEspecialidades->EOF){
			$_sSeleccionadoEsp = "";
			if ($sEsp == $rsEspecialidades->fields['Descripcion']){
				$_sSeleccionadoEsp = '<img width="20" src="' . $dirGestor . 'graf/ok.png" title="ok"/>';
			}
			$sHtml.='
								<tr>
									<td width="42%">' . $rsEspecialidades->fields['Descripcion'] . '</td>
									<td >' . $_sSeleccionadoEsp . '</td>
								</tr>
								<tr><td colspan="2" >&nbsp;</td></tr>
								';
			$rsEspecialidades->MoveNext();
		}
		$sHtml.='
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>
					<table style="font-size: 14px;">
						<tr>
							<td class="negro">Fecha de finalización:</td>
							<td>&nbsp;' . (new DateTime($cCandidato->getFechaFinalizado()))->format('d/m/Y') . '</td>
						</tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>

					<table width="100%" style="font-size: 14px;">
						<tr><td colspan="2" class="negrob">ANÁLISIS COMPETENCIAL</td></tr>
						<tr><td colspan="2" >&nbsp;</td></tr>
					</table>

					<div class="caja" style="margin-top:5px !important;">
						<table border="0" style="margin-left: 16px;">
							<tr>
								<td width="width: 247px;">Integridad Personal</td>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-1"];	// Integridad Personal

		$sHtml.='
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-2"];	// Habilidades de Comunicación e Interpersonales
		$sHtml.='
							<tr>
								<td width="width: 247px;">Habilidades de Comunicación e Interpersonales</td>
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-3"];	// Profesional, Interés por Aprender y por Involucrarse
		$sHtml.='

							<tr>
								<td width="width: 247px;">Grado de Cooperación y Habilidad para Trabajar en Equipo</td>
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-4"];	// Agilidad Mental
		$sHtml.='
							<tr>
								<td width="width: 247px;">Agilidad Mental</td>
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-5"];	// Acción Independiente y Sistemática
		$sHtml.='
							<tr>
								<td width="width: 247px;">Acción Independiente y Sistemática</td>
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-6"];	// Grado de Cooperación y Habilidad para Trabajar en Equipo
		$sHtml.='
							<tr>
								<td width="width: 247px;">Grado de Cooperación y Habilidad para Trabajar en Equipo</td>
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$iPuntacion = $aPuntuacionesCompetencias["1-7"];	// Habilidades Metodológicas
		$sHtml.='
							<tr>
								<td width="width: 247px;">Habilidades Metodológicas</td>
								<td class="caja" style="width: 261px !important;">' . $iPuntacion . ' - ' . textoPuntuacion($iPuntacion) . '</td>
							</tr>
					';
		$sHtml.='
						</table>
					</div>
					<h2 class="avisoFormacion" style="font-size: 14px;"></h2>
					<p style="font-size: 14px;">Por favor, le rogamos envíe esta ficha a la siguiente dirección de correo electrónico para que se proceda a la inscripción de la persona en los cursos recomendados:</p>
					<p style="font-size: 14px;">&nbsp;</p>
					<p style="font-size: 14px;font-weight: bold;">academiadeformacion@daimler.com</p>
					<table width="100%" >
						<tr>
							<td class="margenTD">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<img src="' . $dirGestor . 'graf/seas/logoAcademia.jpg" title="logo Academia"/>
							</td>
						</tr>
					</table>
		';

		//--------------------

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
			';
	/*
		//************************************
		//---------
		$sHtml.='
			<div class="pagina">';
		// INFORME ACADEMIA pág2

		$sHtml.='
				<div class="desarrollo">
					<table width="100%" >
						<tr>
							<td width="100%" align="center">
								<img src="' . $dirGestor . 'graf/seas/logoMB.jpg" title="logo MB"/>
							</td>
						</tr>
					</table>
					<table width="100%" >
						<tr>
							<td class="negro">COMENTARIOS SOBRE EL ANALISIS COMPETENCIAL<br /><br /><font class="miniatura">(Obtenidos durante la entrevista)</font></td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px !important;">
						<table height="135" width="96%" border="0" style="margin-left: 16px;">
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
					<p>&nbsp;</p>
					<table width="100%" >
						<tr>
							<td class="negro">VALORACION DE LA ENTREVISTA</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px !important;">
						<table height="135" width="96%" border="0" style="margin-left: 16px;">
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
					<p>&nbsp;</p>
					<table width="100%" >
						<tr>
							<td class="negro">PROPUESTA DE ÁREAS DE DESARROLLO</td>
						</tr>
					</table>
					<div class="caja" style="margin-top:20px !important;">
						<table height="135" width="96%" border="0" style="margin-left: 16px;">
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
					<p>&nbsp;</p>
					<p>Por favor, una vez haya completado este formulario con sus observaciones, le rogamos lo envíe a la siguiente dirección de correo electrónico para que se proceda a la inscripción de la persona en los cursos recomendados:</p>
					<p>&nbsp;</p>
					<p style="font-weight: bold;">academiadeformacion@daimler.com</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>

					<table width="100%" >
						<tr>
							<td class="margenTD2">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<img src="' . $dirGestor . 'graf/seas/logoAcademia.jpg" title="logo Academia"/>
							</td>
						</tr>
					</table>
		';

		//--------------------

		$sHtml.='
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
		<hr>
			';
	*/
		return $sHtml;
	}

	function calalculaAPTITUDES($_idEmpresa, $_idProceso, $_idPrueba, $_codIdiomaIso2Prueba, $_idCandidato, $_idBaremo, $_especialidadMB="")
	{
		global $dirGestor;
		global $documentRoot;
		global $conn;

		require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Items/ItemsDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Items/Items.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/Opciones.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");

		//[$iPDirecta, $iPercentil, $IR, $IP, $POR, $iItemsPrueba]
		$aAptitudes =array();
		$iPDirecta = 0;
		$iPercentil = 0;
		$IR = 0.00;
		$IP = 0.00;
		$POR = 0.00;
		$iItemsPrueba = 0;
	// --
		$cItemsDB = new ItemsDB($conn);
		$cOpcionesDB = new OpcionesDB($conn);
		$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
		$cRespuestasPruebasItems = new Respuestas_pruebas_items();
		$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
		$cRespuestasPruebasItems->setIdCandidato($_idCandidato);
		$cRespuestasPruebasItems->setIdPrueba($_idPrueba);
		$cRespuestasPruebasItems->setIdEmpresa($_idEmpresa);
		$cRespuestasPruebasItems->setIdProceso($_idProceso);
		$cRespuestasPruebasItems->setCodIdiomaIso2($_codIdiomaIso2Prueba);
		$cRespuestasPruebasItems->setOrderBy("idItem");
		$cRespuestasPruebasItems->setOrder("ASC");

		$sqlRespItems = $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
		$listaRespItems = $conn->Execute($sqlRespItems);

		$cIt = new Items();
		$cIt->setIdPrueba($_idPrueba);
		$cIt->setIdPruebaHast($_idPrueba);
		$cIt->setCodIdiomaIso2($_codIdiomaIso2Prueba);

		if (!empty($_especialidadMB)){
			$cIt->setTipoItem($_especialidadMB);
		}
		$sqlItemsPrueba= $cItemsDB->readLista($cIt);
		$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
		$iItemsPrueba = $listaItemsPrueba->recordCount();
		//Inicializamos la puntuación directa y el percentil que más tarde transformaremos
		$iPDirecta = 0;
		$iPercentil = 0;
		if($listaRespItems->recordCount()>0)
		{
			while(!$listaRespItems->EOF){

				//Leemos el item para saber cual es la opción correcta
				$cItem = new Items();
				$cItem->setIdItem($listaRespItems->fields['idItem']);
				$cItem->setIdPrueba($listaRespItems->fields['idPrueba']);
				$cItem->setCodIdiomaIso2($_codIdiomaIso2Prueba);
				$cItem = $cItemsDB->readEntidad($cItem);

				//Leemos la opción para saber en código de la misma
				$cOpcion = new Opciones();
				$cOpcion->setIdItem($listaRespItems->fields['idItem']);
				$cOpcion->setIdPrueba($listaRespItems->fields['idPrueba']);
				$cOpcion->setIdOpcion($listaRespItems->fields['idOpcion']);
				$cOpcion->setCodIdiomaIso2($_codIdiomaIso2Prueba);
				$cOpcion = $cOpcionesDB->readEntidad($cOpcion);


				//Comparamos el código de la opción elegida con la opción correcta reflejada en el item
				if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
	//					echo $listaRespItems->fields['idItem'] . " - bien <br />";
					//Si coincide se le suma uno a la PDirecta.
					$iPDirecta++;
				}
				$listaRespItems->MoveNext();
			}

			$cBaremos_resultados = new Baremos_resultados();
			$cBaremos_resultados->setIdBaremo($_idBaremo);
			$cBaremos_resultados->setIdPrueba($_idPrueba);

			$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
	//			echo "<br />B" . $sqlBaremosResultados . "<br />";
			$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
			$ipMin=0;
			$ipMax=0;
			// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que
			// corresponde con la puntuación directa obtenida.
			if($listaBaremosResultados->recordCount()>0){
				while(!$listaBaremosResultados->EOF){

					$ipMin = $listaBaremosResultados->fields['puntMin'];
					$ipMax = $listaBaremosResultados->fields['puntMax'];
					if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
						$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
					}
					$listaBaremosResultados->MoveNext();
				}
			}

	//			echo "pDirecta: " . $iPDirecta . "<br />";
	//			echo "pPercentil: " . $iPercentil . "<br />";
	//--
			$sUltimoItemRespondido = 0;
			if ($listaRespItems->recordCount() > 0){
				$sUltimoItemRespondido = $listaRespItems->MoveLast();
				$sUltimoItemRespondido = $listaRespItems->fields['idItem'];
			}

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

		}
		$aAptitudes[] =$iPDirecta;
		$aAptitudes[] =$iPercentil;
		$aAptitudes[] =$IR;
		$aAptitudes[] =$IP;
		$aAptitudes[] =$POR;
		$aAptitudes[] =$iItemsPrueba;


		return $aAptitudes;

	}

	function Rules($A , $Escala1, $Escala2, $Escala3)
	{
		global $dirGestor;
		global $documentRoot;
		global $conn;
		global $cCandidato;

		$TEXTO ="";
		$b = 0;

		$A = trim($A);
		$b = 100;

	//	    $sSQL = "select * from RULES where AREA ='" . $A . "';";
		$sSQL = "SELECT * FROM RULES WHERE AREA='" . $A . "' and " . $Escala1 . " >= Min_1 and " . $Escala1 . " <= Max_1 and " . $Escala2 . " >= Min_2 and " . $Escala2 . " <= Max_2 and " . $Escala3 . " >= Min_3 and " . $Escala3 . " <= Max_3;";

		$myrecord = $conn->Execute($sSQL);
	//		echo '<br />' . $A . ': Escala1:' . $Escala1 . ' - Escala2:' . $Escala2 . ' - Escala3:' . $Escala3;
	//		echo '<br />' . $sSQL;
	//		while(!$myrecord->EOF)
	//        {
	//
	//            $Area = $myrecord->fields['AREA'];
	//
	//            if ($A == $myrecord->fields['AREA'])
	//            {
	//
	//                if ($Escala1 >=$myrecord->fields["Min_1"] && $Escala1 <=$myrecord->fields["Max_1"])
	//                {
	//                    if (empty($Escala2)) {break;}
	//
	//                        if ($Escala2 >=$myrecord->fields["Min_2"] && $Escala2 <=$myrecord->fields["Max_2"])
	//                        {
	//                            if (empty($Escala3)){ break;}
	//
	//                                if ($Escala3 >=$myrecord->fields["Min_3"] && $Escala3 <=$myrecord->fields["Max_3"] ){
	//                                    break;
	//                                }else{
	//                                    $myrecord->MoveNext();
	//                                }
	//                        }
	//
	//                }
	//            }
	//            $myrecord->MoveNext();
	//        }


		$nregla =$myrecord->fields["Rule"];

		$TEXTO =$myrecord->fields["texto"];
		$TEXTO = str_replace("@usuario@" , $cCandidato->getNombre() , $TEXTO);

		return $TEXTO;
	}
	//*************------------------
	function getPortada()
	{
		global $dirGestor;
		global $documentRoot;
	global $comboESPECIALIDADESMB;
	global $cCandidato;
	global $conn;
	global $sEsp;

	//PORTADA
	$sHtml= '
			<div class="pagina portada">
				<img src="'.$dirGestor.'graf/serc/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
				<h1 class="titulo"></h1>';
	if($_POST['fIdTipoInforme']!=11){
		//$sHtml.= 		'<div id="txt_infome"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_infome"><p></p></div>';
	}else{
		//$sHtml.= 		'<div id="txt_infome_narrativo"><p>' . $sDescInforme . '</p></div>';
		$sHtml.= 		'<div id="txt_infome_narrativo"><p></p></div>';
	}

	$sHtml.='
				<div id="informe">
					<p class="textos"><strong>' . constant("STR_NOMBRE_APELLIDOS") . ':</strong> '. $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					<p class="textos"><strong>Concesión / Taller Autorizado:</strong> ' . $cCandidato->getConcesionMB() . ' ' . '</p>
					<p class="textos"><strong>Especialidad:</strong> ' . $sEsp . '</p>
					<p class="textos"><strong>' . constant("STR_FECHA_INFORME") . ':</strong> '.date("d/m/Y").'</p>
				</div>
				<!--<h2 id="copy">Copyright 2011, PSICÓLOGOS EMPRESARIALES S.A.</h2>-->
			</div>
			<!--FIN DIV PAGINA-->
	<hr>
			';
	//		$sHtml.=	constant("_NEWPAGE");
	//FIN PORTADA
	return $sHtml;
	}
	function getContraPortada()
	{
		global $dirGestor;
		global $documentRoot;
		$sHtml= '
			<div class="pagina portada" id="contraportada">
				<img id="imgContraportada" src="' . $dirGestor . 'graf/contraportada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
			</div>
			<!--FIN DIV PAGINA-->
		';

	}
	//*************------------------
	//'Calculo de puntuación de las nuevas competencias
	//'se transforma a puntuaciones de 1-6
	function getPuntCompetencia($valor, $iValor)
	{
		global $dirGestor;
		global $documentRoot;
	$iRetorno=0;
	//	echo "<br />tipo:: " . $valor . " Pd::" . $iValor;
	switch ($valor)
	{

		case "A":    //A)  INTEGRIDAD PERSONAL
			switch ($iValor)
			{
				case ($iValor > 48):
					$iRetorno = 6;
					break;
				case ($iValor >=43) && ($iValor <= 48):
					$iRetorno = 5;
					break;
				case ($iValor >=37) && ($iValor <= 42):
					$iRetorno = 4;
					break;
				case ($iValor >=30) && ($iValor <= 36):
					$iRetorno = 3;
					break;
				case ($iValor >=24) && ($iValor <=  29):
					$iRetorno = 2;
					break;
				case ($iValor < 24):
					$iRetorno = 1;
					break;
			}
			break;
		case "B":    //B)  HABILIDADES DE COMUNICACIÓN E INTERPERSONALES
			switch ($iValor)
			{
				case ($iValor > 98):
					$iRetorno = 6;
					break;
				case ($iValor >=83) && ($iValor <= 98):
					$iRetorno = 5;
					break;
				case ($iValor >=67) && ($iValor <= 82):
					$iRetorno = 4;
					break;
				case ($iValor >=51) && ($iValor <= 66):
					$iRetorno = 3;
					break;
				case ($iValor >=35) && ($iValor <= 50):
					$iRetorno = 2;
					break;
				case ($iValor < 35):
					$iRetorno = 1;
					break;
			}
			break;
		case "C":    //C)  PROFESIONAL, INTERÉS POR APRENDER Y POR INVOLUCRARSE
			switch ($iValor)
			{
				case ($iValor > 32):
					$iRetorno = 6;
					break;
				case ($iValor >=29) && ($iValor <= 32):
					$iRetorno = 5;
					break;
				case ($iValor >=25) && ($iValor <= 28):
					$iRetorno = 4;
					break;
				case ($iValor >=20) && ($iValor <= 24):
					$iRetorno = 3;
					break;
				case ($iValor >=16) && ($iValor <= 19):
					$iRetorno = 2;
					break;
				case ($iValor < 16):
					$iRetorno = 1;
					break;
			}
			break;
		case "D":    //D)  AGILIDAD MENTAL
			switch ($iValor)
			{
				case ($iValor > 40):
					$iRetorno = 6;
					break;
				case ($iValor >=36) && ($iValor <= 40):
					$iRetorno = 5;
					break;
				case ($iValor >=31) && ($iValor <= 35):
					$iRetorno = 4;
					break;
				case ($iValor >=25) && ($iValor <= 30):
					$iRetorno = 3;
					break;
				case ($iValor >=20) && ($iValor <= 24):
					$iRetorno = 2;
					break;
				case ($iValor < 20):
					$iRetorno = 1;
					break;
			}
			break;
		case "E":    //E)  ACCION INDEPENDIENTE Y SISTEMÁTICA
			switch ($iValor)
			{
				case ($iValor > 54):
					$iRetorno = 6;
					break;
				case ($iValor >=48) && ($iValor <= 54):
					$iRetorno = 5;
					break;
				case ($iValor >=41) && ($iValor <= 47):
					$iRetorno = 4;
					break;
				case ($iValor >=33) && ($iValor <= 40):
					$iRetorno = 3;
					break;
				case ($iValor >=26) && ($iValor <= 32):
					$iRetorno = 2;
					break;
				case ($iValor < 26):
					$iRetorno = 1;
					break;
			}
			break;
		case "F":    //F)  GRADO DE COOPERACIÓN Y HABILIDAD PARA TRABAJAR EN EQUIPO
			switch ($iValor)
			{
			case ($iValor > 22):
				$iRetorno = 6;
				break;
			case ($iValor >=20) && ($iValor <= 22):
				$iRetorno = 5;
				break;
			case ($iValor >=17) && ($iValor <= 19):
				$iRetorno = 4;
				break;
			case ($iValor >=13) && ($iValor <= 16):
				$iRetorno = 3;
				break;
			case ($iValor >=10) && ($iValor <= 12):
				$iRetorno = 2;
				break;
			case ($iValor < 10):
				$iRetorno = 1;
				break;
			}
			break;
		case "G":    //G)  HABILIDADES METODOLÓGICAS
			switch ($iValor)
			{
				case ($iValor > 39):
					$iRetorno = 6;
					break;
				case ($iValor >=35) && ($iValor <= 39):
					$iRetorno = 5;
					break;
				case ($iValor >=29) && ($iValor <= 34):
					$iRetorno = 4;
					break;
				case ($iValor >=23) && ($iValor <= 28):
					$iRetorno = 3;
					break;
				case ($iValor >=18) && ($iValor <= 22):
					$iRetorno = 2;
					break;
				case ($iValor < 18):
					$iRetorno = 1;
					break;
			}
			break;
	}
	//		echo "<br />tipo:: " . $valor . " Pd::" . $iValor . " Retorno::" . $iRetorno;
	return $iRetorno;
	}
	function areaRecomendada(){
		global $dirGestor;
		global $documentRoot;
		$string = "";
		$string = "Área recomendada a sondear";
		return $string;
	}
	/******************************************************************
	* FIN Funciones para la generación del Informe
	******************************************************************/
}
///////////////////////////////////
//Generación de informe SERC de MB
//////////////////////////////////

//tipo prisma CUESTIONARIO DE PERSONALIDAD COMERCIAL CPC (83) ->Lanza informe completo

require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
$cItems_inversosDB = new Items_inversosDB($conn);
$cItems_inversos = new Items_inversos();
		$iCPC=83;
		$cItems_inversos->setIdPrueba($iCPC);
		$cItems_inversos->setIdPruebaHast($iCPC);
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
											$iPd += 3;
											break;
										case '2':	// Peor
											$iPd += 1;
											break;
										default:	// Sin contestar opcion 0 en respuestas
											$iPd += 2;
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
				       	$sNombreBE = $listaBloques->fields['nombre'] . "-" . $listaEscalas->fields['nombre'];

//				       	echo "<br />---------->[" . $sPosi . "][" . $sNombreBE . "][PD:" . $iPd . "][PB:" . $iPBaremada . "]";
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

								if(array_search($listaCompetencias_items->fields['idItem'], $aInversos) === false){
									//MEJOR => 2 PEOR => 0 VACIO => 1
									switch ($cRespuestas_pruebas_items->getIdOpcion())
									{
										case '1':	// Mejor
											$iPdCompetencias += 3;
											break;
										case '2':	// Peor
											$iPdCompetencias += 1;
											break;
										default:	// Sin contestar opcion 0 en respuestas
											$iPdCompetencias += 2;
											break;
									}
					       		}else{
					       			$iPdCompetencias += getInversoPrisma($cRespuestas_pruebas_items->getIdOpcion());
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
				        //echo "<br />" . $sqlBaremos_resultado_competencia . "<br />";
				        $listaBaremos_resultado_competencia = $conn->Execute($sqlBaremos_resultado_competencia);
				        //echo $iPdCompetencias . "<br />";
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

				        //--Pedro
				       	$sPosiCompetencias = $listaTipoCompetencia->fields['idTipoCompetencia'] . "-" . $listaCompetencias->fields['idCompetencia'];
				       	$sNombreC = $listaTipoCompetencia->fields['nombre'] . "-" . $listaCompetencias->fields['nombre'];

				       	$iPe= 0;
				       	if ($sPosiCompetencias == "1-1"){	// Integridad Personal
				       		$iPe = getPuntCompetencia("A", $iPdCompetencias);
				       	}
				       	if ($sPosiCompetencias == "1-2"){	// Habilidades de Comunicación e Interpersonales
				       		$iPe = getPuntCompetencia("B", $iPdCompetencias);
				       	}
				       	if ($sPosiCompetencias == "1-3"){	// Profesional, Interés por Aprender y por Involucrarse
				       		$iPe = getPuntCompetencia("C", $iPdCompetencias);
				       	}
				       	if ($sPosiCompetencias == "1-4"){	// Agilidad Mental
				       		$iPe = getPuntCompetencia("D", $iPdCompetencias);
				       	}
				       	if ($sPosiCompetencias == "1-5"){	// Acción Independiente y Sistemática
				       		$iPe = getPuntCompetencia("E", $iPdCompetencias);
				       	}
				       	if ($sPosiCompetencias == "1-6"){	// Grado de Cooperación y Habilidad para Trabajar en Equipo
				       		$iPe = getPuntCompetencia("F", $iPdCompetencias);
				       	}
				       	if ($sPosiCompetencias == "1-7"){	// Habilidades Metodológicas
				       		$iPe = getPuntCompetencia("G", $iPdCompetencias);
				       	}

				       	$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPe;

//				       	echo "<br />" . $sPosiCompetencias . " " . $listaCompetencias->fields['nombre'] . " - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias . " Puntuación rango: " . $iPe;
//				       	echo "<br />---------->[" . $sPosiCompetencias . "][" . $sNombreC . "] - PD: " . $iPdCompetencias . " PBaremada: " . $iPBaremadaCompetencias . " Puntuación rango: " . $iPe;
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

		$spath = (substr($documentRoot, -1, 1) != '/') ? $documentRoot . '/' : $documentRoot;

		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/serc/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/serc/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/serc/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>SERC</title>
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
						    	<img src="'.$dirGestor.'estilosInformes/serc/img/sp.gif" title="logo"/>
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

		$comboESPECIALIDADESMB	= new Combo($conn,"fEspecialidadMB","idEspecialidadMB","descripcion","Descripcion","especialidadesmb","","","","","","");
		$sEsp = $comboESPECIALIDADESMB->getDescripcionCombo($cCandidato->getEspecialidadMB());
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

				    $consistencia = baremo_C(number_format(sqrt($iPGlobal/14)*100 ,0));
//echo "<br />G.C.::" . $consistencia;
//// FIN de consistencia GENERAL
//echo "<br />--------->" . $_POST['fIdTipoInforme'];
		$aSQLPuntuacionesPPL = array();
		$aSQLPuntuacionesC = array();

		switch ($_POST['fIdTipoInforme'])
		{
			case(3);//Informe Completo
				//FUNCIÓN PARA generar informe SERC
				$sHtml= getPortada();
				$sHtml.= generarSERC($aPuntuaciones, $aPuntuacionesCompetencias, $sHtmlCab,$_POST['fCodIdiomaIso2']);
				$sHtml.= generarEntrevista($aPuntuaciones,$aPuntuacionesCompetencias, $sHtmlCab,$_POST['fCodIdiomaIso2']);
				$sHtml.= getContraPortada();
				break;
			case(56);//Informe Academia de Formación

				$sHtml= generarAcademia($aPuntuaciones,$aPuntuacionesCompetencias, $sHtmlCab,$_POST['fCodIdiomaIso2']);
				break;
			case(57);//Informe Entrevista

				$sHtml= generarEntrevista($aPuntuaciones,$aPuntuacionesCompetencias, $sHtmlCab,$_POST['fCodIdiomaIso2']);
				break;
		}


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

    $footer_html    =  mb_strtoupper($cPruebas->getNombre(), 'UTF-8') . str_repeat(" ", 30) . constant("STR_PIE_INFORMES");
		//$footer_html = $footer_html;
		include("generaDOMPDF.php");
		//$footer_html = $footer_html;

		//


	}
}
?>
