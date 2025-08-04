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

		// CÁLCULOS GLOBALES PARA ESCALAS FIT,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		
		$sqlFIT= "SELECT * FROM fit_competencial WHERE idEmpresa='" . $_POST['fIdEmpresa'] . "' AND idPrueba='" . $_POST['fIdPrueba'] . "' AND idBloque IS NOT NULL AND idEscala IS NOT NULL ORDER BY nomMatching";
		//echo "<br />30-->sqlFIT::" . $sqlFIT . "";
		$rsFIT = $conn->Execute($sqlFIT);

		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		
		$aPuntuaciones = array();
		$aPuntuacionesAUX = array();

		
			
		$nFit=$rsFIT->recordCount();
		if($nFit > 0) {
			while(!$rsFIT->EOF){

				$cEscalas_items = new Escalas_items();
				$cEscalas_items->setIdEscala($rsFIT->fields['idEscala']);
				$cEscalas_items->setIdEscalaHast($rsFIT->fields['idEscala']);
				$cEscalas_items->setIdBloque($rsFIT->fields['idBloque']);
				$cEscalas_items->setIdBloqueHast($rsFIT->fields['idBloque']);
				$cEscalas_items->setIdPrueba($_POST['fIdPrueba']);
				$cEscalas_items->setOrderBy("idItem");
				$cEscalas_items->setOrder("ASC");
				$sqlEscalas_items = $cEscalas_itemsDB->readLista($cEscalas_items);
				//echo "<br />54-->sqlEscalas_items::" . $sqlEscalas_items;
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
						}else{
							$iPd += getInversoPrisma($cRespuestas_pruebas_items->getIdOpcion());
						}

						$listaEscalas_items->MoveNext();
					}
				}

				$cBaremos_resultado = new Baremos_resultados();
				$cBaremos_resultado->setIdBaremo($_POST['fIdBaremo']);
				$cBaremos_resultado->setIdPrueba($_POST['fIdPrueba']);
				$cBaremos_resultado->setIdBloque($rsFIT->fields['idBloque']);
				$cBaremos_resultado->setIdEscala($rsFIT->fields['idEscala']);

				$sqlBaremos_resultado = $cBaremos_resultadoDB->readLista($cBaremos_resultado);
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

				$sPosi = $rsFIT->fields['idBloque'] . "-" . $rsFIT->fields['idEscala'];
				$sNombreFit = $rsFIT->fields['nomMatching'];
				//echo "<br />ESCALAS ->[" . $sPosi . "][" . $rsFIT->fields['nomMatching'] . "][" . $iPBaremada . "][Peso::". $rsFIT->fields['porcentaje'] ."]==[" . (($iPBaremada*$rsFIT->fields['porcentaje'])/100) . "]";
				$aPuntuaciones[$sPosi] =  $iPBaremada;
				$aPuntuacionesAUX[$sNombreFit][$sPosi] =  (($iPBaremada*$rsFIT->fields['porcentaje'])/100);

				$rsFIT->MoveNext();
			}
		}

		$key=0;
		$aKEY_FIT= array_keys($aPuntuacionesAUX);
		$aPuntuacionesFIT= array();


		$sSQLExportInit = "INSERT INTO export_personalidad_fit (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, idTipoInforme, descTipoInforme, fecAltaProceso, idSexo,idEdad, idFormacion, idNivel, idArea, nomMatching, puntuacion, fecAlta, fecMod) VALUES ";
		$sSQLExportData="";
		foreach($aPuntuacionesAUX as $aPuntuacionAUX){
			$aPuntuacionesFIT[$aKEY_FIT[$key]] = array_sum($aPuntuacionAUX);
			$sSQL_Chk = "SELECT id FROM export_personalidad_fit WHERE idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false)  . " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false) . " AND  nomMatching=" . $conn->qstr($aKEY_FIT[$key]);
			$rsChk = $conn->Execute($sSQL_Chk);
			//echo "<br />137-->sSQL_Chk::" . $sSQL_Chk . "";
			if ($rsChk->recordCount() <= 0) {
				$sSQLExportData .=  "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($sDescInforme, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $conn->qstr($aKEY_FIT[$key]) . "," . $conn->qstr(array_sum($aPuntuacionAUX), false) . ", now(), now()),";
			}
			$key++;
        }
		if (!empty($sSQLExportData)) {
			$sSQLExportData = substr($sSQLExportData, 0, strlen($sSQLExportData)-1);
			$conn->Execute($sSQLExportInit . $sSQLExportData);
		}
	// FIN CALCULOS GLOBALES ESCALAS

	//CALCULOS GLOBALES COMPETENCIAS
		$sqlFIT= "SELECT * FROM fit_competencial WHERE idEmpresa='" . $_POST['fIdEmpresa'] . "' AND idPrueba='" . $_POST['fIdPrueba'] . "' AND idTipoCompetencia IS NOT NULL AND idCompetencia IS NOT NULL ORDER BY nomMatching";
		//echo "<br />151-->sqlFIT COMPETENCIAS::" . $sqlFIT . "";
		$rsFIT = $conn->Execute($sqlFIT);

		$cBaremos_resultados_competenciasDB = new Baremos_resultados_competenciasDB($conn);
		
		$aPuntuacionesCompetencias = array();
		$aPuntuacionesCompetenciasAUX = array();

		$nFit=$rsFIT->recordCount();
		if($nFit > 0) {
			while(!$rsFIT->EOF){

				$cCompetencias_items = new Competencias_items();
				$cCompetencias_items->setIdCompetencia($rsFIT->fields['idCompetencia']);
				$cCompetencias_items->setIdCompetenciaHast($rsFIT->fields['idCompetencia']);
				$cCompetencias_items->setIdTipoCompetencia($rsFIT->fields['idTipoCompetencia']);
				$cCompetencias_items->setIdTipoCompetenciaHast($rsFIT->fields['idTipoCompetencia']);
				$cCompetencias_items->setIdPrueba($_POST['fIdPrueba']);
				$cCompetencias_items->setOrderBy("idItem");
				$cCompetencias_items->setOrder("ASC");
				$sqlCompetencias_items = $cCompetencias_itemsDB->readLista($cCompetencias_items);
				//echo "<br />" . $sqlCompetencias_items . "";
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
						//MEJOR => 2 PEOR => 0 VACIO => 1
						switch ($cRespuestas_pruebas_items->getIdOpcion())
						{
							case '1':	// Mejor
								$iPdCompetencias += 2;
								break;
							case '2':	// Peor
								$iPdCompetencias += 0;
								break;
							default:	// Sin contestar opcion 0 en respuestas
								$iPdCompetencias += 1;
								break;
						}
						$listaCompetencias_items->MoveNext();
					}
				}
				$cBaremos_resultado_competencias = new Baremos_resultados_competencias();
				$cBaremos_resultado_competencias->setIdBaremo($_POST['fIdBaremo']);
				$cBaremos_resultado_competencias->setIdPrueba($_POST['fIdPrueba']);
				$cBaremos_resultado_competencias->setIdTipoCompetencia($rsFIT->fields['idTipoCompetencia']);
				$cBaremos_resultado_competencias->setIdCompetencia($rsFIT->fields['idCompetencia']);

				$sqlBaremos_resultado_competencia = $cBaremos_resultados_competenciasDB->readLista($cBaremos_resultado_competencias);
				//echo $sqlBaremos_resultado_competencia . "<br />";
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

				$sPosiCompetencias = $rsFIT->fields['idTipoCompetencia'] . "-" . $rsFIT->fields['idCompetencia'];
				$sNombreFit = $rsFIT->fields['nomMatching'];
				//echo "<br />COMPETENCIAS ->[" . $sPosiCompetencias . "][" . $rsFIT->fields['nomMatching'] . "][" . $iPBaremadaCompetencias . "][Peso::". $rsFIT->fields['porcentaje'] ."]==[" . (($iPBaremadaCompetencias*$rsFIT->fields['porcentaje'])/100) . "]";
				$aPuntuacionesCompetencias[$sPosiCompetencias] =  $iPBaremadaCompetencias;
				$aPuntuacionesCompetenciasAUX[$sNombreFit][$sPosiCompetencias] =  (($iPBaremadaCompetencias*$rsFIT->fields['porcentaje'])/100);
				$rsFIT->MoveNext();
			}
		}
		$key=0;
		$aKEY_FIT= array_keys($aPuntuacionesCompetenciasAUX);
		$aPuntuacionesCompetenciasFIT= array();


		$sSQLExportInit = "INSERT INTO export_personalidad_fit (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, nombre, apellido1, apellido2, email, dni, idPrueba, descPrueba, fecPrueba, idBaremo, descBaremo, idTipoInforme, descTipoInforme, fecAltaProceso, idSexo,idEdad, idFormacion, idNivel, idArea, nomMatching, puntuacion, fecAlta, fecMod) VALUES ";
		$sSQLExportData="";
		foreach($aPuntuacionesCompetenciasAUX as $aPuntuacionAUX){
			$aPuntuacionesCompetenciasFIT[$aKEY_FIT[$key]] = array_sum($aPuntuacionAUX);
			$sSQL_Chk = "SELECT id FROM export_personalidad_fit WHERE idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . " AND idBaremo=" . $conn->qstr($_POST["fIdBaremo"], false)  . " AND idTipoInforme=" . $conn->qstr($_POST['fIdTipoInforme'], false) . " AND  nomMatching=" . $conn->qstr($aKEY_FIT[$key]);
			$rsChk = $conn->Execute($sSQL_Chk);
			//echo "<br />244-->sSQL_Chk::" . $sSQL_Chk . "";
			if ($rsChk->recordCount() <= 0) {
				$sSQLExportData .=  "(" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getDescEmpresa(), false) . "," . $conn->qstr($cRespPruebas->getIdProceso(), false) . "," . $conn->qstr($cRespPruebas->getDescProceso(), false) . "," . $conn->qstr($cRespPruebas->getIdCandidato(), false) . "," . $conn->qstr($cCandidato->getNombre(), false) . "," . $conn->qstr($cCandidato->getApellido1(), false) . "," . $conn->qstr($cCandidato->getApellido2(), false) . "," . $conn->qstr($cCandidato->getMail(), false) . "," . $conn->qstr($cCandidato->getDni(), false) . "," . $conn->qstr($cRespPruebas->getIdPrueba(), false) . "," . $conn->qstr($cRespPruebas->getDescPrueba(), false) . "," . $conn->qstr($cRespPruebas->getFecAlta(), false) . "," . $conn->qstr($_POST["fIdBaremo"], false) . "," . $conn->qstr($_sBaremo, false) . "," . $conn->qstr($_POST['fIdTipoInforme'], false) . "," . $conn->qstr($sDescInforme, false) . "," . $conn->qstr($cProceso->getFecAlta(), false) . "," . $conn->qstr($cCandidato->getIdSexo(), false) . "," . $conn->qstr($cCandidato->getIdEdad(), false) . "," . $conn->qstr($cCandidato->getIdFormacion(), false) . "," . $conn->qstr($cCandidato->getIdNivel(), false) . "," . $conn->qstr($cCandidato->getIdArea(), false) . "," . $conn->qstr($aKEY_FIT[$key]) . "," . $conn->qstr(array_sum($aPuntuacionAUX), false) . ", now(), now()),";
			}
			$key++;
        }
		if (!empty($sSQLExportData)) {
			$sSQLExportData = substr($sSQLExportData, 0, strlen($sSQLExportData)-1);
			$conn->Execute($sSQLExportInit . $sSQLExportData);
		}	
		
		//FIN CALCULOS GLOBALES COMPETENCIAS

//descSexo,descEdad,descFormacion,descNivel,descArea
$sSQLUPDATE = "UPDATE export_personalidad_fit ep, sexos s SET ep.descSexo=s.nombre WHERE ep.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
$conn->Execute($sSQLUPDATE);
//echo "<br />" . $sSQLUPDATE;
$sSQLUPDATE = "UPDATE export_personalidad_fit ep, edades e SET ep.descEdad=e.nombre WHERE ep.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
$conn->Execute($sSQLUPDATE);
//echo "<br />" . $sSQLUPDATE;
$sSQLUPDATE = "UPDATE export_personalidad_fit ep, formaciones f SET ep.descFormacion=f.nombre WHERE ep.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
$conn->Execute($sSQLUPDATE);
//echo "<br />" . $sSQLUPDATE;
$sSQLUPDATE = "UPDATE export_personalidad_fit ep, nivelesjerarquicos n SET ep.descNivel=n.nombre WHERE ep.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
$conn->Execute($sSQLUPDATE);
//echo "<br />" . $sSQLUPDATE;
$sSQLUPDATE = "UPDATE export_personalidad_fit ep, areas a SET ep.descArea=a.nombre WHERE ep.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($cRespPruebas->getCodIdiomaIso2(), false) . " AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
$conn->Execute($sSQLUPDATE);
//echo "<br />" . $sSQLUPDATE;
$sSQLUPDATE = "UPDATE export_personalidad_fit ep, candidatos c SET ep.codIso2PaisProcedencia=c.codIso2PaisProcedencia WHERE ep.idEmpresa=c.idEmpresa AND ep.idProceso=c.idProceso AND ep.idCandidato=c.idCandidato AND ep.idEmpresa=" . $conn->qstr($cRespPruebas->getIdEmpresa(), false) . " AND ep.idProceso=" . $conn->qstr($cRespPruebas->getIdProceso(), false) . " AND ep.idCandidato=" . $conn->qstr($cRespPruebas->getIdCandidato(), false) . " AND ep.idPrueba=" . $conn->qstr($cRespPruebas->getIdPrueba(), false) . ";";
$conn->Execute($sSQLUPDATE);
//echo "<br />" . $sSQLUPDATE;





	// Si llega MEJOR devolver 0
	// Si llega PEOR devolver 2
	// Si llega BLANCO devolver 1
	function getInversoPrisma($valor){
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
//		if($valor==2){$inv=0;}
//		if($valor==1){$inv=1;}
//		if($valor==0){$inv=2;}
//		echo "<br />id::" . $valor .  " - valor::" . $inv;
		return $inv;
	}





	
?>
