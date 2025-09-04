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
	function getCabeceraCIP($cCandidato, $iPosicionTop=1240){
		global $dirGestor;
		global $documentRoot;
		$sHtmlCab ='<div class="cabecera">
				<table width="100%" border="0">
				<tr>
					<td class="nombre">
						<p class="textos">' . constant("STR_SR_A") . ' ' . $cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2() .'</p>
					</td>
					<td class="logo">&nbsp;
					</td>
					<td class="fecha">
						<p class="textos">' . date("d/m/Y") . '</p>
					</td>
					</tr>
				</table>
			</div>
		';
			return $sHtmlCab;
		}
		function inicioInformeCIP($cCandidato, $codIdiomaIso2){
			global $dirGestor;
			global $documentRoot;

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

			global $aSQLPuntuacionesPPL;
			global $aSQLPuntuacionesC;
			global $cPruebas;
			global $cProceso;
			global $cRespPruebas;

			$iPorcientoEconomia = 0;
			$iPorcientoOrientacionInternacional = 0;

			require_once($documentRoot . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias/Competencias.php");
			$cTipos_competenciasDB = new Tipos_competenciasDB($conn);
			$cCompetenciasDB = new CompetenciasDB($conn);

			//Seteamos arrays a utlizar en la grÃ¡fica y en otro sitios
			// de las otras pruebas
			$cPruebaVerbal = new Pruebas();
			$cPruebaVerbal->setIdPrueba(29);
			$cPruebaVerbal->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
			$cPruebaVerbal = $cPruebasDB->readEntidad($cPruebaVerbal);
			$iVERBAL = getPercentilPrueba($cCandidato, $cPruebaVerbal);

			$cPruebaLogica = new Pruebas();
			$cPruebaLogica->setIdPrueba(31);
			$cPruebaLogica->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
			$cPruebaLogica = $cPruebasDB->readEntidad($cPruebaLogica);
			$iLOGICO = getPercentilPrueba($cCandidato, $cPruebaLogica);

			$cPruebaNumerico = new Pruebas();
			$cPruebaNumerico->setIdPrueba(30);
			$cPruebaNumerico->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
			$cPruebaNumerico = $cPruebasDB->readEntidad($cPruebaNumerico);
			$iNUMERICO = getPercentilPrueba($cCandidato, $cPruebaNumerico);

			$cPruebaEspacial = new Pruebas();
			$cPruebaEspacial->setIdPrueba(28);
			$cPruebaEspacial->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
			$cPruebaEspacial = $cPruebasDB->readEntidad($cPruebaEspacial);
			$iESPACIAL = getPercentilPrueba($cCandidato, $cPruebaEspacial);

			$cPruebaHabilidades = new Pruebas();
			$cPruebaHabilidades->setIdPrueba(49);
			$cPruebaHabilidades->setCodIdiomaIso2($_POST['fCodIdiomaIso2Prueba']);
			$cPruebaHabilidades = $cPruebasDB->readEntidad($cPruebaHabilidades);
			$iHABILIDADES = getPDPrueba($cCandidato, $cPruebaHabilidades);

			//PÃGINA INTRODUCCIÃN, 1
			$sHtml=
			'<div class="pagina">
					<div class="desarrollo">
							'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
							$sHtml.= '
					<div class="caja">
						<h2 class="tit_peque">' . constant("STR_INTRODUCCION") . ' CIP</h2>
						<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P1")) . '</p>
						<h2 class="tit_peque">' . constant("STR_CONTENIDO_DEL_INFORME") . '</h2>
						<h2 class="tit_peque">' . constant("STR_APARTADO_1") . '</h2>
								<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P2")) . '</p>
								<h2 class="tit_peque">' . constant("STR_APARTADO_2") . '</h2>
								<p class="textos">' . constant("STR_CIP_INTRO_P3") . '</p>
								<h2 class="tit_peque">' . constant("STR_APARTADO_3") . '</h2>
								<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P4")) . '</p>
								<h2 class="tit_peque">' . constant("STR_APARTADO_4") . '</h2>
								<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_INTRO_P5")) . '</p>
					</div>
				</div>
				<!--FIN DIV DESARROLLO-->
				</div>
				<!--FIN DIV PAGINA-->
				<hr>
				';

			$sHtml.=
			'<div class="pagina">
				';

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
				//echo $sqlCompetencias;
				$listaCompetencias = $conn->Execute($sqlCompetencias);

				$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas);

			$sHtml.= '
					<div class="desarrollo">
							'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
							$sHtml.= '
					<div class="caja">
						<h2 class="tit_peque" style="line-height: 15px;"> ' . constant("STR_CIP_AREAS_PROFESIONALES_PREFERENTES_CIP") . '</h2>
						<p class="textos" style="line-height: 12px;">' . constant("STR_CIP_AREAS_PROFESIONALES_PREFERENTES_CIP_TXT") . '</p>
									<table border="0" width="100%" style="font-size: 14px;border-collapse: separate;border: 1px solid #97a3b1;">
								<tr>
								<td width="35">&nbsp;</td>
								<td width="6">&nbsp;</td>
									<td style="width:22%;padding:2px;font-size:12px;"></td>
									<td style="text-align:right;padding:2px;font-size:10px;">' . constant("STR_CIP_ESCALA") . ':</td>
									<td style="font-size:9px;"><span class="bPersonas" style="float:left;margin: 0;padding: 0;">0</span><span class="bPersonas">100</span></td>
							</tr>
								<tr>
								<td width="35" rowspan="12" bgcolor="#cc0066" align="center"><img src="' . $dirGestor . 'graf/CIP/' . $codIdiomaIso2 . '/personas.jpg" alt="" title="" /></td>
								<td width="6">&nbsp;</td>
									<td style="background-color:#d99694;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
									<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
									<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
							</tr>
							';

			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PERSONAS", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
						<tr>
							<td width="6">&nbsp;</td>
							<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
							<td class="aPorciento" style="font-size:10px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
							<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
						</tr>
						';

						$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
						$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PERSONAS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
						$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=6;	//INFLUENCIA
			$iPorcientoTipoCompetencia=0;
			$cTipos_competencias = new Tipos_competencias();
			$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
			$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cTipos_competencias->setIdPrueba($cPruebas->getIdPrueba());
			$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);
			
			//$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 1);

			$cCompetencias = new Competencias();
			$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
			$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
			$cCompetencias->setIdPrueba($cPruebas->getIdPrueba());
			$cCompetencias->setOrderBy("idCompetencia");
			$cCompetencias->setOrder("ASC");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			$listaCompetencias = $conn->Execute($sqlCompetencias);

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 2);

			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#d99694;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';
			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PERSONAS", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PERSONAS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 3);

			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#d99694;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';
			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PERSONAS", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PERSONAS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 4);

			$sHtml.= '
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td width="35" rowspan="4" bgcolor="#376092" align="center"><img src="' . $dirGestor . 'graf/CIP/' . $codIdiomaIso2 . '/personas_datos.jpg" alt="" title="" /></td>
					<td width="6">&nbsp;</td>
					<td style="background-color:#558ed5;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#558ed5;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#558ed5;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';
			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PERSONAS-DATOS", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#376092;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#376092;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PERSONAS-DATOS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=9;	//ORGANIZACIÃN/PROCEDIMIENTOS
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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 5);

			$sHtml.= '
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td width="35" rowspan="11" bgcolor="#31859c" align="center"><img src="' . $dirGestor . 'graf/CIP/' . $codIdiomaIso2 . '/datos.jpg" alt="" title="" /></td>
					<td width="6">&nbsp;</td>
					<td style="background-color:#b7dee8;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';

			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("DATOS", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				if ($listaCompetencias->fields['idTipoCompetencia'] == 9 &&
					$listaCompetencias->fields['idCompetencia'] == 2 ){
					$iPorcientoEconomia = $iPorciento;
				}
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("DATOS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=10;	//COMUNICACIÃN VERBAL
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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 6);

			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#b7dee8;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';

			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("DATOS", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("DATOS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

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
			
			//*****************************************************
			// Eliminamos la llamada a esta función solo para esta competencia
			// porque al llamarla no se generaba correctamente el pdf
			//*****************************************************
			//$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 7);
				/* Mofificado Ãºltimo cambio solicitado por Cris correo 09/07/2014
						$sHtml.= '	<tr>
										<td width="6">&nbsp;</td>
										<td style="background-color:#b7dee8;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
										<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
										<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
									</tr>
									';
				*/
			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#b7dee8;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;">&nbsp;</td>
					<td style="background-color:#b7dee8;font-size:9px;">&nbsp;</td>
				</tr>
				';

			//No se guarda la puntuación para tener en cuenta la solicitud de 2014, se introduce vacio en $iPorcientoTipoCompetencia
			$sPorcientoTipoCompetencia="";
			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("DATOS", false) . "," . $sPorcientoTipoCompetencia . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				/* Mofificado Ãºltimo cambio solicitado por Cris correo 09/07/2014 */
				if ($listaCompetencias->fields['idTipoCompetencia'] == 11 &&
					$listaCompetencias->fields['idCompetencia'] == 2){
					$iPorciento = (($iPorcientoEconomia + $iPorcientoOrientacionInternacional) / 2 );
				}else{
					$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
					if ($iPorcientoOrientacionInternacional == 0 ){
						if ($listaCompetencias->fields['idTipoCompetencia'] == 11 &&
							$listaCompetencias->fields['idCompetencia'] == 1){
								$iPorcientoOrientacionInternacional = $iPorciento;
						}
					}
				}
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("DATOS", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 8);

			$sHtml.= '
				<tr>
					<td colspan="5"></td>
				</tr>
					<tr>
						<td width="35" rowspan="15" bgcolor="#604a7b" align="center"><img src="' . $dirGestor . 'graf/CIP/' . $codIdiomaIso2 . '/practica.jpg" alt="" title="" /></td>
						<td width="6">&nbsp;</td>
						<td style="background-color:#ccc1da;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
						<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
						<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
					</tr>
				';
			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=13;	//CREATIVIDAD/TÃCNICA
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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 9);

			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#ccc1da;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';
			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=14;	//TÃCNICA
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
			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 10);

			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#ccc1da;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';

			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
			$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=15;	//CIENTÃFICA
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

			$iPorcientoTipoCompetencia = getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebas, $test = 11);

			$sHtml.= '
				<tr>
					<td width="6">&nbsp;</td>
					<td style="background-color:#ccc1da;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
					<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;">' . number_format($iPorcientoTipoCompetencia, 2, ',', '.') . '%</td>
					<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
				</tr>
				';

			$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
			$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr(0, false) . "," . $conn->qstr("", false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorcientoTipoCompetencia, 2), false) . ",now());\n";
			$aSQLPuntuacionesC[] = $sSQLExport;

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarrera($listaCompetencias, $cCandidato, $cPruebas);
				setArrayGlobalCarreras($iPorciento, $listaCompetencias->fields['nombre']);
				setArrayGlobalCarreras75($iPorciento, $listaCompetencias->fields['nombre'], $listaCompetencias->fields['idTipoCompetencia'] . $listaCompetencias->fields['idCompetencia']);
				$sHtml.= '
					<tr>
						<td width="6">&nbsp;</td>
						<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
						<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
						<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
					</tr>
					';
				$sSQLExport = "INSERT INTO export_personalidad_competencias (idEmpresa, idProceso, descProceso, idCandidato, idPrueba, descPrueba, fecPrueba, idBaremo, idTipoInforme, codIdiomaIso2Informe, idTipoCompetencia, nomTipoCompetencia, idCompetencia, nomCompetencia, descCompetencia, puntuacion, fecAlta) VALUES ";
				$sSQLExport .= "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST['fIdBaremo'], false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($_POST['fCodIdiomaIso2'], false) . "," . $conn->qstr($sIdTipoCompetencia, false) . "," . $conn->qstr(mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8'), false) . "," . $conn->qstr($listaCompetencias->fields['idCompetencia'], false) . "," . $conn->qstr($listaCompetencias->fields['nombre'], false) . "," . $conn->qstr("PRÃCTICA", false) . "," . $conn->qstr(number_format($iPorciento, 2), false) . ",now());\n";
				$aSQLPuntuacionesC[] = $sSQLExport;

				$listaCompetencias->MoveNext();
			}
			$sHtml.= '
							<tr>
								<td colspan="5"></td>
						</tr>
						</table>
				</div>
				<!--FIN DIV CAJA-->
			</div>
			<!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
		<hr>
			';

			$sHtml.=
			'<div class="pagina">
			';
			$sInteresaCarreras0 = (!empty($aInteresaCarreras[0])) ? str_replace("|", ", ", substr($aInteresaCarreras[0], 1)) : constant("STR_CIP_NINGUNA");
			$sInteresaCarreras1 = (!empty($aInteresaCarreras[1])) ? str_replace("|", ", ", substr($aInteresaCarreras[1], 1)) : constant("STR_CIP_NINGUNA");
			$sInteresaCarreras2 = (!empty($aInteresaCarreras[2])) ? str_replace("|", ", ", substr($aInteresaCarreras[2], 1)) : constant("STR_CIP_NINGUNA");
			$sInteresaCarreras3 = (!empty($aInteresaCarreras[3])) ? str_replace("|", ", ", substr($aInteresaCarreras[3], 1)) : constant("STR_CIP_NINGUNA");
			$sInteresaCarreras4 = (!empty($aInteresaCarreras[4])) ? str_replace("|", ", ", substr($aInteresaCarreras[4], 1)) : constant("STR_CIP_NINGUNA");

			$sImg = "AA" . $sNombre . ".png";
			$_PathImg = $spath . $sDirImg;
			//		echo "->" . $_PathImg;
			$sCadena = "";
			grafAreaAptitudes(450, 240, $sCadena, $_PathImg, $sImg, $iVERBAL, $iLOGICO, $iNUMERICO, $iESPACIAL);
			$sHtml.= '
				<div class="desarrollo" >
					'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
					$sHtml.= '
				<div class="caja">
					<h2 class="tit_peque">' . constant("STR_CIP_SINTESIS_DEL_PERFIL") . '</h2>
					<p class="textos">' . str_replace("@candidato_prueba@",$cCandidato->getNombre() . ' ' . $cCandidato->getApellido1() . ' ' . $cCandidato->getApellido2(), constant("STR_CIP_SINTESIS_DEL_PERFIL_P1")) . '</p>
					<p class="textos">' . constant("STR_CIP_SINTESIS_DEL_PERFIL_P2") . ' ' .  $sInteresaCarreras0 . '.</p>
					<p class="textos">' . constant("STR_CIP_SINTESIS_DEL_PERFIL_P3") . ' ' . $sInteresaCarreras1 . '.</p>
					<p class="textos">' . constant("STR_CIP_SINTESIS_DEL_PERFIL_P4") . ' ' . $sInteresaCarreras2 . '.</p>
					<p class="textos">' . constant("STR_CIP_SINTESIS_DEL_PERFIL_P5") . ' ' . $sInteresaCarreras3 . '.</p>
					<p class="textos">' . constant("STR_CIP_SINTESIS_DEL_PERFIL_P6") . ' ' . $sInteresaCarreras4 . '.</p>
					<br /><br />
					<h2 class="tit_peque">' . constant("STR_CIP_AREAS_DE_APTITUDES") . '</h2>
					<p class="textos">' . constant("STR_CIP_AREAS_DE_APTITUDES_P1") . '</p>
					<table align="center" border="0" width="525">
							<tr>
									<td align="center"><img src="' . $dirGestor . $sDirImg . $sImg . '" border="0"  alt="" title="" /></td>
							</tr>
						</table>
				</div>
				<!--FIN DIV CAJA-->
			</div>
			<!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
		<hr>
			';

			$sHtml.=
		'<div class="pagina">
				<div class="desarrollo" >
					'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
					$sHtml.= '
				<div class="caja">
					<h2 class="tit_peque">' . constant("STR_CIP_ORIENTACION_PROFESIONAL") . ' </h2>
					<p class="textos">' . constant("STR_CIP_ORIENTACION_PROFESIONAL_P1") . '</p>
					<br /><br />
					<table align="center" border="0" width="100%" style="border-collapse:separate;border-spacing:0px;margin:auto;" cellpadding="5" cellspacing="5">
							<tr>
									<td align="center" style="border: 1px solid #376092;background-color:#376092;color:#ffffff;font-size:13px;">' . constant("STR_CIP_AREA_DE_INTERES") . '</td>
									<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;">' . constant("STR_CIP_AREA_DE_INTERES_RV") . '</td>
									<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;">' . constant("STR_CIP_AREA_DE_INTERES_RN") . '</td>
									<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;">' . constant("STR_CIP_AREA_DE_INTERES_RL") . '</td>
									<td valign="top" align="center" style="border: 1px solid #376092;color:#376092;font-size:9px;font-family:verdana;">' . constant("STR_CIP_AREA_DE_INTERES_RE") . '</td>
							</tr>
						' . $sInteresaCarreras75 . '
						</table>
						<br /><br />
						<table align="center" border="0" style="border-collapse:separate;border-spacing:0px;margin:auto;" cellpadding="5" cellspacing="5">
							<tr>
								<td class="textos"><strong style="font-weight: bold;">' . constant("STR_CIP_INTERPRETACION") . '</strong></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="center" style="border: 1px solid #376092;background-color:#00ff00;">&nbsp;</td>
								<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">' . constant("STR_CIP_INTERPRETACION_P1") . '</td>
							</tr>
							<tr>
								<td align="center" style="border: 1px solid #376092;background-color:#ffff00;">&nbsp;</td>
								<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">' . constant("STR_CIP_INTERPRETACION_P2") . '</td>
							</tr>
							<tr>
								<td align="center" style="border: 1px solid #376092;background-color:#ff0000;">&nbsp;</td>
								<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">' . constant("STR_CIP_INTERPRETACION_P3") . '</td>
							</tr>
							<tr>
								<td align="center" style="border: 1px solid #376092;background-color:#d9d9d9;">&nbsp;</td>
								<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">' . constant("STR_CIP_INTERPRETACION_P4") . '</td>
							</tr>
							<tr>
								<td align="center" style="border: 1px solid #376092;background-color:#4f81bd;">&nbsp;</td>
								<td style="border: 1px solid #376092;font-size:10px;font-family:verdana;padding-left:5px;">' . constant("STR_CIP_INTERPRETACION_P5") . '</td>
							</tr>
						</table>
				</div>
				<!--FIN DIV CAJA-->
			</div>
			<!--FIN DIV DESARROLLO-->
		</div>
		<!--FIN DIV PAGINA-->
		<hr>
			';

			if ($iHABILIDADES > 0){

				$sHtml.=
				'<div class="pagina">
				';

					$sPrueba= 49;
					$sIdTipoCompetencia=5;	//PRUDENCIA
					$iPorcientoTipoCompetencia=0;
					$cTipos_competencias = new Tipos_competencias();
					$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
					$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
					$cTipos_competencias->setIdPrueba($sPrueba);
					$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

					$cCompetencias = new Competencias();
					$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
					$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
					$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
					$cCompetencias->setIdPrueba($sPrueba);
					$cCompetencias->setOrderBy("idCompetencia");
					$cCompetencias->setOrder("ASC");
					$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
					$listaCompetencias = $conn->Execute($sqlCompetencias);

					$iPorcientoTipoCompetencia = getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
					$iPorcientoTipoCompetencia=0;
					$sHtml.= '
						<div class="desarrollo" >
							'. $sHtmlCab = getCabeceraCIP($cCandidato,0);
							$sHtml.= '
						<div class="caja">
							<h2 class="tit_peque">HABILIDADES</h2>
							<p class="textos">Esta sección del informe refleja las principales habilidades del alumno. El resultado debe ser considerado como un indicador orientativo de su perfil para determinar aquellos aspectos que pueden ayudarle a la hora de afrontar los retos que pueda abordar en su etapa de estudios.</p>
						<table align="center" border="0" width="100%" style="border-collapse:separate;border-spacing:0px;margin:auto;" cellpadding="5" cellspacing="5">
									<tr>
										<td style="padding:2px;font-size:12px;width: 50%;"></td>
										<td style="text-align:right;padding:2px;font-size:10px;">Escala:</td>
										<td style="font-size:9px;width: 50%;"><span class="bPersonas" style="float:left;margin: 0;padding: 0;">0</span><span class="bPersonas">100</span></td>
								</tr>
									<tr>
										<td style="background-color:#d99694;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
										<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#d99694;">&nbsp;</td>
										<td style="background-color:#d99694;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
								</tr>
								';
					$listaCompetencias->Move(0);
					$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
					$iPorciento = 0;
					while(!$listaCompetencias->EOF){
						$iPorciento = getPuntuacionPorCarreraHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
					$sHtml.= '
							<tr>
										<td class="aTxt" style="color:#cc0066;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
										<td class="aPorciento" style="font-size:11px;color:#cc0066;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
										<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
									</tr>
								';
						$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=6;	//FORTALEZA
			$iPorcientoTipoCompetencia=0;
			$cTipos_competencias = new Tipos_competencias();
			$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
			$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cTipos_competencias->setIdPrueba($sPrueba);
			$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);
			$iPorcientoTipoCompetencia = getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebas);
			$iPorcientoTipoCompetencia=0;

			$cCompetencias = new Competencias();
			$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
			$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
			$cCompetencias->setIdPrueba($sPrueba);
			$cCompetencias->setOrderBy("idCompetencia");
			$cCompetencias->setOrder("ASC");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			$listaCompetencias = $conn->Execute($sqlCompetencias);

			$iPorcientoTipoCompetencia = getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebas);
			$iPorcientoTipoCompetencia=0;
			$sHtml.= '
					<tr>
								<td style="background-color:#558ed5;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
								<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#558ed5;">&nbsp;</td>
								<td style="background-color:#558ed5;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
							</tr>
						';
			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarreraHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
			$sHtml.= '
					<tr>
								<td class="aTxt" style="color:#376092;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
								<td class="aPorciento" style="font-size:11px;color:#376092;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
								<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
							</tr>
						';
				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=7;	//SOCIABILIDAD
			$iPorcientoTipoCompetencia=0;
			$cTipos_competencias = new Tipos_competencias();
			$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
			$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cTipos_competencias->setIdPrueba($sPrueba);
			$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

			$cCompetencias = new Competencias();
			$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
			$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
			$cCompetencias->setIdPrueba($sPrueba);
			$cCompetencias->setOrderBy("idCompetencia");
			$cCompetencias->setOrder("ASC");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			$listaCompetencias = $conn->Execute($sqlCompetencias);

			$iPorcientoTipoCompetencia = getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebas);
			$iPorcientoTipoCompetencia=0;
			$sHtml.= '
					<tr>
								<td style="background-color:#b7dee8;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
								<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#b7dee8;">&nbsp;</td>
								<td style="background-color:#b7dee8;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
							</tr>
						';

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarreraHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
			$sHtml.= '
				<tr>
								<td class="aTxt" style="color:#31859c;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
								<td class="aPorciento" style="font-size:11px;color:#31859c;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
								<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
						</tr>
						';
				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=8;	//RESPETO Y LEALTAD
			$iPorcientoTipoCompetencia=0;
			$cTipos_competencias = new Tipos_competencias();
			$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
			$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cTipos_competencias->setIdPrueba($sPrueba);
			$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

			$cCompetencias = new Competencias();
			$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
			$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
			$cCompetencias->setIdPrueba($sPrueba);
			$cCompetencias->setOrderBy("idCompetencia");
			$cCompetencias->setOrder("ASC");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			$listaCompetencias = $conn->Execute($sqlCompetencias);

			$iPorcientoTipoCompetencia = getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
			$iPorcientoTipoCompetencia=0;
			$sHtml.= '
							<tr>
								<td colspan="5"></td>
						</tr>
							<tr>
								<td style="background-color:#ccc1da;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
								<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#ccc1da;">&nbsp;</td>
								<td style="background-color:#ccc1da;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
						</tr>
						';
			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarreraHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
			$sHtml.= '
					<tr>
								<td class="aTxt" style="color:#604a7b;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
								<td class="aPorciento" style="font-size:11px;color:#604a7b;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
								<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
							</tr>
						';
				$listaCompetencias->MoveNext();
			}

			$sIdTipoCompetencia=9;	//OPTIMISMO
			$iPorcientoTipoCompetencia=0;
			$cTipos_competencias = new Tipos_competencias();
			$cTipos_competencias->setCodIdiomaIso2($codIdiomaIso2);
			$cTipos_competencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cTipos_competencias->setIdPrueba($sPrueba);
			$cTipos_competencias = $cTipos_competenciasDB->readEntidad($cTipos_competencias);

			$cCompetencias = new Competencias();
			$cCompetencias->setCodIdiomaIso2($codIdiomaIso2);
			$cCompetencias->setIdTipoCompetencia($sIdTipoCompetencia);
			$cCompetencias->setIdTipoCompetenciaHast($sIdTipoCompetencia);
			$cCompetencias->setIdPrueba($sPrueba);
			$cCompetencias->setOrderBy("idCompetencia");
			$cCompetencias->setOrder("ASC");
			$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);
			$listaCompetencias = $conn->Execute($sqlCompetencias);
			//echo "<br />" . $sqlCompetencias;

			$iPorcientoTipoCompetencia = getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
			$iPorcientoTipoCompetencia=0;
			$sHtml.= '
				<tr>
								<td colspan="5"></td>
						</tr>
							<tr>
								<td style="background-color:#83b027;padding:2px;font-size:12px;">' . mb_strtoupper($cTipos_competencias->getNombre(), 'UTF-8') . '</td>
								<td class="aPorciento" style="font-size:11px;color:#000000;background-color:#83b027;">&nbsp;</td>
								<td style="background-color:#83b027;font-size:9px;"><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorcientoTipoCompetencia) . '%;">&nbsp;</span></td>
						</tr>
						';

			$listaCompetencias->Move(0);
			$sSP = '&nbsp;&nbsp;&nbsp;&nbsp;';
			$iPorciento = 0;
			while(!$listaCompetencias->EOF){
				$iPorciento = getPuntuacionPorCarreraHabilidades($listaCompetencias, $cCandidato, $cPruebaHabilidades);
				if ($listaCompetencias->fields['idTipoCompetencia'] == 9 &&
					$listaCompetencias->fields['idCompetencia'] == 2 ){
					$iPorcientoEconomia = $iPorciento;
				}
			$sHtml.= '
					<tr>
								<td class="aTxt" style="color:#83b027;">' . str_replace(" ", "&nbsp;", $listaCompetencias->fields['nombre']) . '</td>
								<td class="aPorciento" style="font-size:11px;color:#83b027;">' . number_format($iPorciento, 2, ',', '.') . '%</td>
								<td><span class="aPersonas" style="width:' . str_replace(",", ".", $iPorciento) . '%;">&nbsp;</span></td>
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
					<!--FIN DIV CAJA-->
				</div>
				<!--FIN DIV DESARROLLO-->
			</div>
			<!--FIN DIV PAGINA-->
			<hr>
				';
			}

		return $sHtml;
		}
		/* Posicion 0 ->Interes MUY ELEVADO
		* Posicion 1 ->Interes ELEVADO
		* Posicion 2 ->Interes MODERADO
		* Posicion 3 ->Interes BAJO
		* Posicion 4 ->Interes NINGUNO
		*/
		function setArrayGlobalCarreras($iPorcentaje, $sCarrera){
			global $dirGestor;
			global $documentRoot;
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
			global $dirGestor;
			global $documentRoot;
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
						if ($iVERBAL >= 71){
							$sColorVERVAL = '#00ff00'; //verde
						}
						if ($iVERBAL >= 41 && $iVERBAL <= 70){
							$sColorVERVAL = '#ffff00';	//Amarillo
						}
						if ($iVERBAL >= 0 && $iVERBAL <= 40){
							$sColorVERVAL = '#ff0000';	//Rojo
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
		*	Te agradarÃ­a realizar esa tarea = 2 puntos.
		*	Te desagradarÃ­a realizar esa tarea = 0 puntos.
		*	Te resultarÃ­a indiferente realizarla o no = 1 punto.
		*/
		function getPuntuacionPorArea($listaCompetencias, $cCandidato, $cPruebaCIP, $test = null){
			global $dirGestor;
			global $documentRoot;
			//echo $listaCompetencias;
			
			/* if($test==2
			|| $test==7
			){
				if($test == 2){
					//echo "test: ".$test . "</br>" . "listaCompetencias" . "</br>" .  "cCandidato" . "</br>". "cPruebaCIP" . "</br>";

				}else if($test == 7){

					return $test;
				}
			} */
			global $conn;
			$iPorcentaje=0;
			$iSumador=0;
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
			
			if(!isset($_POST['esZip']) && $_POST['esZip'] != true){
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
			}
			$cCompetencias_itemsDB = new Competencias_itemsDB($conn);
			$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
			$nAreas = $listaCompetencias->NumRows();
			$contItems=0;
			$contCompetencias = 0;
			$sumaCompetencias = 0;
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
				$contItems +=$nCompetencias_items;
				$contCompetencias++;
				//echo "<br>idTipo::" . $listaCompetencias->fields['idTipoCompetencia'] . " Competencia::" . $listaCompetencias->fields['nombre'] . " NÃºmero competencias::" . $nAreas;
				//echo "<br>NÂº items de ::" . $listaCompetencias->fields['nombre'] . " -> " . $nCompetencias_items;
				
				$iPdCompetencias = 0;
				$sumaPrueba = 0;
				if($nCompetencias_items>0){
					while(!$listaCompetencias_items->EOF){
						$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

						$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
						$cRespuestas_pruebas_items->setIdProceso($cCandidato->getIdProceso());
						$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
						$cRespuestas_pruebas_items->setIdPrueba($cPruebaCIP->getIdPrueba());
						$cRespuestas_pruebas_items->setCodIdiomaIso2($cPruebaCIP->getCodIdiomaIso2());
						$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

						$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

						//	1 	A 	Agrada
						//	2 	D 	Desagrada
						//	3 	I 	Indiferente
						switch ($cRespuestas_pruebas_items->getIdOpcion())
						{
							case 1:
								$iSumador += 2;
								$sumaPrueba+= 2;
								break;
							case 2:
								$iSumador += 0;
								break;
							case 3:
								$iSumador += 1;
								$sumaPrueba+= 1;
								break;
							default:

						};
						$listaCompetencias_items->MoveNext();
					}
				}
				$porcentajePrueba = (100*$sumaPrueba)/(2*$nCompetencias_items);
				$sumaCompetencias+= $porcentajePrueba;
				$listaCompetencias->MoveNext();
			}
			//$iPorcentaje = (($iSumador*100)/($nAreas*16));
			// $iPorcentaje = (($iSumador*100)/($contItems*2));
			$iPorcentaje = $sumaCompetencias/$contCompetencias;
			//echo "<br>NÂº items total ::" . $contItems . " -> puntuación:: " . $iSumador;
			return $iPorcentaje;
		}
		function getPuntuacionPorAreaHabilidades($listaCompetencias, $cCandidato, $cPruebaCIP){
			global $dirGestor;
			global $documentRoot;
			global $conn;
			$iPorcentaje=0;
			$iSumador=0;

			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
			if(!isset($_POST['esZip']) && $_POST['esZip'] != true){
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
			}
			
			$cItems_inversosDB = new Items_inversosDB($conn);
			$cItems_inversos = new Items_inversos();
			
			$cItems_inversos->setIdPrueba($cPruebaCIP->getIdPrueba());
			$cItems_inversos->setIdPruebaHast($cPruebaCIP->getIdPrueba());
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
						$cRespuestas_pruebas_items->setIdPrueba($cPruebaCIP->getIdPrueba());
						$cRespuestas_pruebas_items->setCodIdiomaIso2($cPruebaCIP->getCodIdiomaIso2());
						$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

						$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

						if(array_search($listaCompetencias_items->fields['idItem'], $aInversos) === false){
							//MEJOR => 2 PEOR => 0 VACIO => 1
							switch ($cRespuestas_pruebas_items->getIdOpcion())
							{
								case '1':	// Mejor
									$iSumador += 2;
									break;
								case '2':	// Peor
									$iSumador += 0;
									break;
								default:	// Sin contestar opcion 0 en respuestas
									$iSumador += 1;
									break;
							}
						}else{
							$iSumador += getInversoHabilidades($cRespuestas_pruebas_items->getIdOpcion());
						}
						$listaCompetencias_items->MoveNext();
					}
				}
				$listaCompetencias->MoveNext();
			}
			$iPorcentaje = (($iSumador*100)/($nAreas*12));
			return $iPorcentaje;
		}

		function getPuntuacionPorCarreraHabilidades($rsCompetencia, $cCandidato, $cPruebaCIP){
			global $dirGestor;
			global $documentRoot;
			global $conn;
			$iPorcentaje=0;
			$iSumador=0;
			
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
			if(!isset($_POST['esZip']) && $_POST['esZip'] != true){
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
			}
			
			$cItems_inversosDB = new Items_inversosDB($conn);
			$cItems_inversos = new Items_inversos();

			$cItems_inversos->setIdPrueba($cPruebaCIP->getIdPrueba());
			$cItems_inversos->setIdPruebaHast($cPruebaCIP->getIdPrueba());
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
					$cRespuestas_pruebas_items->setIdPrueba($cPruebaCIP->getIdPrueba());
					$cRespuestas_pruebas_items->setCodIdiomaIso2($cPruebaCIP->getCodIdiomaIso2());
					$cRespuestas_pruebas_items->setIdItem($listaCompetencias_items->fields['idItem']);

					$cRespuestas_pruebas_items = $cRespuestas_pruebas_itemsBD->readEntidad($cRespuestas_pruebas_items);

					if(array_search($listaCompetencias_items->fields['idItem'], $aInversos) === false){
						//MEJOR => 2 PEOR => 0 VACIO => 1
						switch ($cRespuestas_pruebas_items->getIdOpcion())
						{
							case '1':	// Mejor
								$iSumador += 2;
								break;
							case '2':	// Peor
								$iSumador += 0;
								break;
							default:	// Sin contestar opcion 0 en respuestas
								$iSumador += 1;
								break;
						}
					}else{
						$iSumador += getInversoHabilidades($cRespuestas_pruebas_items->getIdOpcion());
					}

					$listaCompetencias_items->MoveNext();
				}
			}
			$iPorcentaje = (($iSumador*100)/12);
			return $iPorcentaje;

		}

		function getPuntuacionPorCarrera($rsCompetencia, $cCandidato, $cPruebaCIP){
			global $dirGestor;
			global $documentRoot;
			global $conn;
			$iPorcentaje=0;
			$iSumador=0;
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_items.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");
			if(!isset($_POST['esZip']) && $_POST['esZip'] != true){
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
			}

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
			//echo "<br>***idTipo::" . $rsCompetencia->fields['idTipoCompetencia'] . " Competencia::" . $rsCompetencia->fields['nombre'];
			//echo "<br>***NÂº items de ::" . $rsCompetencia->fields['nombre'] . " -> " . $nCompetencias_items;
			
			$iPdCompetencias = 0;
			if($nCompetencias_items > 0){
				while(!$listaCompetencias_items->EOF){
					$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

					$cRespuestas_pruebas_items->setIdEmpresa($cCandidato->getIdEmpresa());
					$cRespuestas_pruebas_items->setIdProceso($cCandidato->getIdProceso());
					$cRespuestas_pruebas_items->setIdCandidato($cCandidato->getIdCandidato());
					$cRespuestas_pruebas_items->setIdPrueba($cPruebaCIP->getIdPrueba());
					$cRespuestas_pruebas_items->setCodIdiomaIso2($cPruebaCIP->getCodIdiomaIso2());
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
			$iPorcentaje = (($iSumador*100)/($nCompetencias_items*2)); //puntuación mÃ¡xima == 2 * nÂº items de la competencia
			//echo "<br>***NÂº items total ::" . $nCompetencias_items . " -> puntuación:: " . $iSumador;
			return $iPorcentaje;

		}

		function grafAreaAptitudes($width, $height, $sCadena, $_PathImgInforme, $sImg, $iVERBAL=0, $iLOGICO=0, $iNUMERICO=0, $iESPACIAL=0)
		{
			//var_dump(constant("DIR_WS_JPGRAPH") . "jpgraph.php");
			//die;
			global $dirGestor;
			global $documentRoot;
			require_once(constant("DIR_WS_JPGRAPH") . "jpgraph.php");
			require_once(constant("DIR_WS_JPGRAPH") . "jpgraph_bar.php");
			require_once(constant("DIR_WS_JPGRAPH") . "jpgraph_line.php");
			require_once(constant("DIR_WS_JPGRAPH") . "jpgraph_date.php");

			$datay=array($iVERBAL, $iLOGICO, $iNUMERICO, $iESPACIAL);
	//		print_r($datay);
	//		$datax=array("de todos los " . $sCadena,"de todos los directores","resto de profesionales","de todos los puestos");
			$datax=array(constant("STR_CIP_VERBAL"), constant("STR_CIP_LOGICO"), constant("STR_CIP_NUMERICO"), constant("STR_CIP_ESPACIAL"));

			// Set the basic parameters of the graph
			$graph = new Graph($width,$height);
			$graph->SetScale("textlin");
				$graph->graph_theme = null;
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
			global $dirGestor;
			global $documentRoot;
			global $conn;
			$iPDirecta = 0;
			$iPercentil = 0;
			if(!isset($_POST['esZip']) && $_POST['esZip'] != true){
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
			}
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/Opciones.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Items/Items.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Items/ItemsDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");


			$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($conn);
			$cItemsDB = new ItemsDB($conn);
			$cOpcionesDB = new OpcionesDB($conn);
			$cOpciones_valoresDB = new Opciones_valoresDB($conn);

			$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

			$cProcesoBaremosDB	= new Proceso_baremosDB($conn);  // Entidad DB
			$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);  // Entidad DB

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

			// Montamos la lista de respuestas para los parÃ¡metros enviados.
			$sqlRespItems = $cRespuestas_pruebas_itemsDB->readLista($cRespuestas_pruebas_items);
	//		echo "<br />" . $sqlRespItems;
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
				$iPDirecta = (($iPDirecta*100) / $listaItemsPrueba->recordCount());

			}
			return $iPDirecta;
		}
		function getPercentilPrueba($cCandidato, $cPrueba){
			global $dirGestor;
			global $documentRoot;
			global $conn;
			$iPDirecta = 0;
			$iPercentil = 0;
			if(!isset($_POST['esZip']) && $_POST['esZip'] != true){
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Items/Items.php");
				require_once($documentRoot . constant("DIR_WS_COM") . "Items/ItemsDB.php");
			}
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/Opciones.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valores.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Opciones_valores/Opciones_valoresDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
			require_once($documentRoot . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");


			$cRespuestas_pruebas_itemsDB = new Respuestas_pruebas_itemsDB($conn);
			
			$cItemsDB = new ItemsDB($conn);
			$cOpcionesDB = new OpcionesDB($conn);
			$cOpciones_valoresDB = new Opciones_valoresDB($conn);

			$cRespuestas_pruebas_items = new Respuestas_pruebas_items();

			$cProcesoBaremosDB	= new Proceso_baremosDB($conn);  // Entidad DB
			$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);  // Entidad DB

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

			// Montamos la lista de respuestas para los parÃ¡metros enviados.
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
				//Sacamos el baremo a que solicitamos en el proceso para esa prueba.
				$cProcesoBaremos = new Proceso_baremos();  // Entidad
				$cProcesoBaremos->setIdProceso($cCandidato->getIdProceso());
				$cProcesoBaremos->setIdEmpresa($cCandidato->getIdEmpresa());
				$cProcesoBaremos->setIdPrueba($cPrueba->getIdPrueba());
				$cProcesoBaremos->setCodIdiomaIso2($cPrueba->getCodIdiomaIso2());
				$sqlProcesoBaremos = $cProcesoBaremosDB->readLista($cProcesoBaremos);
	//			echo "<br />sqlProcesoBaremos:: " . $sqlProcesoBaremos;
				$listaProcesoBaremos = $conn->Execute($sqlProcesoBaremos);
				$sBaremo= -1;
				while(!$listaProcesoBaremos->EOF)
				{
					$sBaremo = $listaProcesoBaremos->fields['idBaremo'];
					break;
					$listaProcesoBaremos->MoveNext();
				}
	//			echo "<br />Baremo::" . $sBaremo;

				$cBaremos_resultados = new Baremos_resultados();
				$cBaremos_resultados->setIdBaremo($sBaremo);
				$cBaremos_resultados->setIdPrueba($cPrueba->getIdPrueba());

				$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
				//echo "<br />A" . $sqlBaremosResultados . "<br />";
				$listaBaremosResultados = $conn->Execute($sqlBaremosResultados);
				$ipMin=0;
				$ipMax=0;
				// Recorremos la lista de los valores del baremo seleccionado para mirar el percentil que
				// corresponde con la puntuación directa obtenida.
				if($listaBaremosResultados->recordCount() > 0)
				{
					while(!$listaBaremosResultados->EOF)
					{
						$ipMin = $listaBaremosResultados->fields['puntMin'];
						$ipMax = $listaBaremosResultados->fields['puntMax'];
						if($ipMin <= $iPDirecta && $iPDirecta <= $ipMax){
							$iPercentil = $listaBaremosResultados->fields['puntBaremada'];
						}
						$listaBaremosResultados->MoveNext();
					}
				}

	//			echo "<br />pDirecta: " . $iPDirecta . "<br />";
	//			echo "<br />pPercentil: " . $iPercentil . "<br />";

				$iPDirecta = (($iPDirecta*100) / $listaItemsPrueba->recordCount());

			}
			return $iPercentil;
		}
		// Si llega MEJOR devolver 0
		// Si llega PEOR devolver 2
		// Si llega BLANCO devolver 1
		function getInversoHabilidades($valor){
			global $dirGestor;
			global $documentRoot;
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


	/******************************************************************
	* FIN de Funciones para la generación del Informe
	******************************************************************/
}

		require_once($documentRoot . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Bloques/Bloques.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Escalas/Escalas.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Competencias/Competencias.php");
		require_once($documentRoot . constant("DIR_WS_COM") . "Competencias_items/Competencias_itemsDB.php");

		$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false),"","fecMod");
		$sDescInforme = $comboTIPOS_INFORMES->getDescripcionCombo($_POST['fIdTipoInforme']);

		//Posicion 0 ->Interes MUY ELEVADO
		//Posicion 1 ->Interes ELEVADO
		//Posicion 2 ->Interes MODERADO
		//Posicion 3 ->Interes BAJO
		//Posicion 4 ->Interes NINGUNO
		$aInteresaCarreras = array("","","","","");
		/*	Carreras con un porcentage igual o superior al 75%	 */
		$sInteresaCarreras75 = "";

		//Definicion de aptitudes por carrera (idTipoCompetencia+idCompetencia)
		$aAptitudesVERVAL = array(51,52,53,54,61,71,81,82,83,84,91,92,93,94,101,102,103,112,121,151,152);
		$aAptitudesNUMERICO = array(72,73,74,84,91,92,112,131,132,141,142,151,152,153,154,155);
		$aAptitudesESPACIAL = array(74,122,131,132,141,142,155);
		$aAptitudesLOGICA = array(51,52,53,54,61,71,72,73,74,81,82,83,84,93,94,101,102,103,112,121,122,132,151,152,153,154,155);

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
					<link rel="stylesheet" type="text/css" href="' . $dirGestor . 'estilosInformes/CIP/resetCSS.css"/>';
					if($_POST['fIdTipoInforme'] != 11){
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/CIP/style.css"/>';
					}else{
						$sHtmlInicio.= 		'<link rel="stylesheet" type="text/css" href="'.$dirGestor.'estilosInformes/CIP/styleNarrativos.css"/>';
					}
		$sHtmlInicio.='
					<title>cip</title>
					<style type="text/css">
					<!--
					-->
					</style>
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
		$sHtml.= '
			   <div class="pagina portada">
				    	<img src="' . $dirGestor . 'graf/CIP/portada.jpg" alt="Psicólogos Empresariales" title="Psicólogos Empresariales" />
				    	<h1 class="titulo"><img src="' . $dirGestor . 'estilosInformes/CIP/img/logo.jpg" /></h1>';
		$sHtml.= 		'<div id="txt_infome"><p>' . constant("STR_CIP_NOMBRE_INFORME_TXT") . '</p></div>';
		$simgP = ($cEmpresaDng->getPathLogo() != "") ? $cEmpresaDng->getPathLogo() : $cPruebas->getLogoPrueba();
		$sHtml.= 		'<div id="logoCliente">
      							<p><img src="' . $dirGestor . $simgP . '" /></p>
      							<p><strong>' .  constant("STR_CIP_BIENVENIDO_INFORME_ORIENTACION_PROFESIONAL_TXT") . '</strong></p>
						     </div>';
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

		//FIN PORTADA

		$aSQLPuntuacionesPPL =  array();
		$aSQLPuntuacionesC =  array();

		$sHtml.= inicioInformeCIP($cCandidato,$_POST['fCodIdiomaIso2']);


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