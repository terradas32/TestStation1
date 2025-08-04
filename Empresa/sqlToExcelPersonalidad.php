<<<<<<< HEAD
<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

	require_once('./include/Configuracion.php');
	// No sé por qué pero la siguiente línea estaba comentada, habrá que comprobar
	// que no da problemas en distintas exportaciones
	include_once('include/Idiomas.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "ToXLS.php");

	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");


include_once ('include/conexion.php');

//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
//var_dump($_REQUEST['LSTTipoInforme']);
//die;
	$sqlToExcel	= new ToXLS($conn);  // Entidad DB

	$sDesPrueba = $_REQUEST['LSTDescPrueba'];
	$_IdPrueba = "";
	$cPrueba=  new Pruebas();
	$cPruebaDB=  new PruebasDB($conn);

	if (!empty($sDesPrueba)){

		$cPrueba->setNombre($sDesPrueba);
		//$sSQLPruebas = $cPruebaDB->readLista($cPrueba);
		$sSQLPruebas = "SELECT * FROM pruebas WHERE UPPER(nombre) = UPPER('" . $sDesPrueba . "')";
		$vPruebas = $conn->Execute($sSQLPruebas);
		$_IdPrueba = $vPruebas->fields['idPrueba'];
	}else{
		echo "DesPrueba::" . constant("ERR");
		exit;
	}
	if (empty($_IdPrueba)){
		echo "IdPrueba::" . constant("ERR");
		exit;
	}

	if ($_IdPrueba == "32" || $_IdPrueba == "42"){	//CIP
		$sSQLUPDATE = "UPDATE export_personalidad_competencias SET nomCompetencia=nomTipoCompetencia WHERE idPrueba=" . $_IdPrueba . " AND nomCompetencia ='' OR nomCompetencia IS NULL ";
		$conn->Execute($sSQLUPDATE);
	}
	$sSQLUPDATE2= "UPDATE proceso_informes, export_personalidad  SET export_personalidad.idTipoInforme = proceso_informes.idTipoInforme WHERE proceso_informes.idEmpresa=export_personalidad.idEmpresa AND proceso_informes.idProceso=export_personalidad.idProceso AND proceso_informes.idPrueba=export_personalidad.idPrueba AND export_personalidad.idTipoInforme=0 AND export_personalidad.idPrueba = " . $_IdPrueba;
	$conn->Execute($sSQLUPDATE2);
	$sSQLUPDATE2= "UPDATE export_personalidad  SET cobrado=1 WHERE cobrado=0 AND idPrueba = " . $_IdPrueba;
	$conn->Execute($sSQLUPDATE2);


	$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
	$cBloquesDB = new BloquesDB($conn);
	$cEscalasDB = new EscalasDB($conn);

	$cCompetenciasDB = new CompetenciasDB($conn);
	$cTipos_competenciasDB = new Tipos_competenciasDB($conn);

//	session_start();
	$sDESCListaExcel = "";
	$aArray =explode(constant("CHAR_SEPARA"), base64_decode($_REQUEST['fSQLtoEXCEL']));

	//echo $_REQUEST['fSQLtoEXCEL'];exit;
	$sql = $aArray[0];
	$nombre = $aArray[1];

	if($_REQUEST['LSTTipoInforme'] == 71){
		$sql = str_replace("export_personalidad", 'export_personalidad_fit', $sql);
		$sql = str_replace(" AND cobrado='1' ", " AND idTipoInforme='71'", $sql);
	}
	if (!empty($sql)){
		$sEntidad = ucfirst($nombre);
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");
		$cEntidad	= new $sEntidad();  // Entidad
		$sDESCListaExcel = $cEntidad->getDESCListaExcel();
	}else{
		echo constant("ERR");
		exit;
	}
	$InitSQL = $sql;
	$sOrder = substr($sql, strpos($sql, "ORDER BY"));
	$sWhere = substr($sql, strpos($sql, "WHERE"));
	$sWhere = str_replace($sOrder,"", $sWhere);

	//Quitamos la agrupación si la hay
	$sGroup ="GROUP BY idEmpresa, idProceso, idCandidato, idPrueba, idBaremo";
	$sWhere = str_replace($sGroup,"", $sWhere);
	//Quitamos lo de cobrado
	$sCobrado ="AND cobrado='1'";
	$sWhere = str_replace($sCobrado,"", $sWhere);

	if($_REQUEST['LSTTipoInforme'] == 71){
		$sWhere_aux = "WHERE idTipoInforme = 71 AND";
		$sWhere = str_replace("WHERE", $sWhere_aux, $sWhere);
		$sWhere = str_replace(" AND cobrado='1' ", " ", $sWhere);
		// Si es tipo informe 71 recoger los datos de esta tabla
		//$sqlCOMPETENCIAS = "SELECT * FROM export_personalidad_fit " . $sWhere;
		//$sqlESCALAS = "SELECT * FROM export_personalidad_laboral " . $sWhere;
		//$sqlCOMPETENCIAS = "SELECT * FROM export_personalidad_competencias " . $sWhere;
	//	export_personalidad_fit
		//echo $sqlCOMPETENCIAS;exit;

	}else{
		$sqlESCALAS = "SELECT * FROM export_personalidad_laboral " . $sWhere;
		$sqlCOMPETENCIAS = "SELECT * FROM export_personalidad_competencias " . $sWhere;
	}
		

	$sql = str_replace("*",$cEntidad->getPKListaExcel(), $sql);
	
	if (empty($nombre)){
		$nombre = "ExcelFile";
	}
	if (empty($_REQUEST['fPintaCabecera'])){
		$_REQUEST['fPintaCabecera'] = true;
	}
	if (empty($_REQUEST['fSepararCabecera'])){
		$_REQUEST['fSepararCabecera'] = false;
	}
	
	//Datos comunes Candidatos
	//Josh
	//echo $InitSQL;exit;
	if($_REQUEST['LSTTipoInforme'] == 71){
		$vDatos = $conn->Execute($InitSQL);
	}else{
		$vDatos = $conn->Execute($InitSQL);
	}

	$iTotalEscalas=1;
	$iTotalCompetencias=1;
	$buf = "";
	$buf .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	$buf .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"es\">";
	// put in some style
	$buf .= "<head>" .
			"<title>Export Personality</title>" .
			"<meta name=\"language\" content=\"es\" />" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	$buf .= "</head>";

	// generate the body
	$buf .= "<body>";
	$buf .= "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";

	$bufFin = "</table>";
	$bufFin .= "</body>";
	$bufFin .= "</html>";
	//Empresa	Proceso	Nombre	Apellido1	Apellido2	Email	DNI / N. ID	Prueba	Fecha De Prueba	Fecha De Alta Proceso
	$head="";
	$datosHeadInit="";
	$datosComunes="";
	$datosHead="";

	$bTrazaId=false;
	$columnaNum=true;	//Para que pinte una columna inicial con el número
	$colspan=19;	//16 Fijas (Empresa,Proceso,Nombre,Apellido1,Apellido2,email,DNI/ N. ID,Prueba,Informe,Baremo,Idioma,Fecha de Prueba,Fecha de Alta Proceso,Sexo,Edad,Formación,Nivel,Área,País de procedencia)
	if ($columnaNum){
		$colspan++;
	}
	$DatosHeadInit = "<tr><td colspan=" . $colspan . ">&nbsp</td>";
	$IdiomaEncontradoPrimerRegistro="";
	
	if($_REQUEST['LSTTipoInforme'] == 71){
		//Escalas
		//Recoger los datos de la tabla export_personalidad_fit y imprimir el nomMatching y la puntuación
		// Hay que descubrir donde se están imprimiendo los valores en el xlsx
		//$DatosHeadBloquesEscalas = getHTMLBloquesDatos($vDatos);
		
		//Competencias
		//$DatosHeadTiposCompetencias = getHTMLTiposCompetenciasDatos($vDatos);
		//$vDatos->Move(0);
		//@include(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . $IdiomaEncontradoPrimerRegistro . ".php");
	
		//$DatosHeadInit = $DatosHeadInit . $DatosHeadBloquesEscalas . $DatosHeadTiposCompetencias . "</tr>";
		//$vDatos->MoveFirst();
		$auxFunction = countParticipantsAndRows($vDatos);
		$counterParticipants =  $auxFunction[0];
		$rowsParticipants =  $auxFunction[1];
		$vDatos->MoveFirst();
		//echo "ey, IF ; ".$vDatos;exit;

		// Josh
		$DatosHeadEscalas = getHTMLFit($vDatos, $rowsParticipants);
		//$vDatos->Move();
		//$DatosHeadCompetencias = getHTMLCompetenciasDatos($vDatos);
		//$vDatos->Move(0);

	}else{
		//Escalas
		//$DatosHeadBloquesEscalas = getHTMLBloques($_IdPrueba);
		$vDatos->Move(0);
		$DatosHeadBloquesEscalas = getHTMLBloquesDatos($vDatos);
		
		//$DatosHeadBloquesEscalas .="<td rowspan=2><b>" . constant("STR_G_C") . "</b></td>";
		//Competencias
		//$DatosHeadTiposCompetencias = getHTMLTiposCompetencias($_IdPrueba);
		$DatosHeadTiposCompetencias = getHTMLTiposCompetenciasDatos($vDatos);
		$vDatos->Move(0);
		@include(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . $IdiomaEncontradoPrimerRegistro . ".php");
		//$DatosHeadTiposCompetencias .="<td rowspan=2><b>" . constant("STR_G_C") . "</b></td>";
	
		$DatosHeadInit = $DatosHeadInit . $DatosHeadBloquesEscalas . $DatosHeadTiposCompetencias . "</tr>";
		//echo $DatosHeadInit;exit;
	
		//	$DatosHeadEscalas = getHTMLEscalas($_IdPrueba);
		$DatosHeadEscalas = getHTMLEscalasDatos($vDatos);
		$vDatos->Move(0);
		//	$DatosHeadCompetencias = getHTMLCompetencias($_IdPrueba);
		$DatosHeadCompetencias = getHTMLCompetenciasDatos($vDatos);
		$vDatos->Move(0);

	}

	$datosHead .= "<tr>";
	if ($columnaNum){
		$datosHead .= "<td align=\"center\"><b>Nº</b></td>";
	}

	$datosHead .= "<td align=\"center\"><b>" . constant("STR_EMPRESA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_PROCESO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_NOMBRE") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_APELLIDO1") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_APELLIDO2") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_EMAIL") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_DNI") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_PRUEBA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_INFORME") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_BAREMO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_IDIOMA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_FECHA_DE_PRUEBA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_FECHA_DE_ALTA_PROCESO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_SEXO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_EDAD") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_FORMACION") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_NIVEL") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_AREA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_PAIS_DE_PROCEDENCIA") . "</b></td>";
	
	//echo "ey vDatos 2; ". $datosHead;
	//exit;
	if($_REQUEST['LSTTipoInforme'] == 71){
		$datosHead .= $DatosHeadEscalas;
	}else{
		$datosHead .= $DatosHeadEscalas;
		$datosHead .= $DatosHeadCompetencias;
	}
	$datosHead .= "</tr>";
	$sqlESCALASIn = "";
	$i=1;
	
	//echo "ey vDatos 2; ". $vDatos->recordCount();
	//exit;
	if($_REQUEST['LSTTipoInforme'] == 71){

		$auxFunction = countParticipantsAndRows($vDatos);
		$counterParticipants =  $auxFunction[0];
		$rowsParticipants =  $auxFunction[1];
		
		$vDatos->MoveFirst();

		//echo "counterParticipants: ". $counterParticipants . ", rowsParticipants:" . $rowsParticipants;
		//exit;
		for($k=0; $k<$counterParticipants; $k++){
			// Multiplico para buscar la posición del primer resultado del participante
			// $k = al orden del participante * las filas de cada participante
			//if($l==0){
				$moveToParticipantResult = $k * $rowsParticipants;
				$vDatos->move($moveToParticipantResult);
				//echo "ey vDatos: ". $moveToParticipantResult . "; k: ". $k . "</br>";
			//}
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . ($k+1) . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descEmpresa'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descProceso'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['nombre'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['apellido1'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['apellido2'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['email'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['dni'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descPrueba'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descTipoInforme'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descBaremo'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $_codIdiomaInforme . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['fecPrueba'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['fecAltaProceso'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descSexo'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descEdad'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descFormacion'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descNivel'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descArea'] . "</font></td>";
			$datosComunes .= "<td align=\"center\"><font color=\"blue\">" . $vDatos->fields['codIso2PaisProcedencia'] . "</font></td>";
			
			while (!$vDatos->EOF){
				$datosComunes .= "<td align=\"center\">" . $vDatos->fields['puntuacion'] . "</td>";
				$vDatos->MoveNext();
				$l++;

				if($l >= $rowsParticipants){
					$l=0;
					$vDatos->MoveFirst();
					break;
				}
			}
			$datosComunes .= "</tr>";
		}
	}else{
		$i=0;
		while (!$vDatos->EOF){
			$sqlESCALASIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlESCALASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlESCALASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlESCALASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlESCALASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlESCALASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlESCALASIn .= $sOrder;
			$sqlESCALASIn .= " ,fecAlta ASC ";
			//echo "<br />" . $i . " -> " . $sqlESCALASIn;exit;
			$vEscalasCandidato = $conn->Execute($sqlESCALASIn);

			$sqlCOMPETENCIASIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlCOMPETENCIASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlCOMPETENCIASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlCOMPETENCIASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlCOMPETENCIASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlCOMPETENCIASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			//$sqlCOMPETENCIASIn .= $sOrder;
			$sqlCOMPETENCIASIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
			//echo "<br />" . $i . " -> " . $sqlCOMPETENCIASIn;exit;
			$vCompetenciasCandidato = $conn->Execute($sqlCOMPETENCIASIn);

			$datosComunes .= "<tr>";
			if ($columnaNum){
				$datosComunes .= "<td align=\"center\" style=\"background-color: #ccc;\"><font color=\"#000\">" . ($i+1);
				if ($bTrazaId){
					$datosComunes .= " -> [" . $vDatos->fields['idEmpresa'] . " - " . $vDatos->fields['idProceso'] . " - " . $vDatos->fields['idCandidato'] . " - " . $vDatos->fields['idPrueba'] . "]";
				}
				$datosComunes .= "</font></td>";
			}

			$_codIdiomaInforme = $vEscalasCandidato->fields['codIdiomaIso2Informe'];
			if (empty($_codIdiomaInforme)){
				$_codIdiomaInforme = $vCompetenciasCandidato->fields['codIdiomaIso2Informe'];
			}
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descEmpresa'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descProceso'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['nombre'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['apellido1'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['apellido2'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['email'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['dni'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descPrueba'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descTipoInforme'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descBaremo'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $_codIdiomaInforme . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['fecPrueba'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['fecAltaProceso'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descSexo'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descEdad'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descFormacion'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descNivel'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descArea'] . "</font></td>";
			$datosComunes .= "<td align=\"center\"><font color=\"blue\">" . $vDatos->fields['codIso2PaisProcedencia'] . "</font></td>";

			if ($vEscalasCandidato->recordCount() > 0){
				while (!$vEscalasCandidato->EOF){
						//$datosComunes .= "<td align=\"center\">(" . $vEscalasCandidato->recordCount(). ") [" . $vEscalasCandidato->fields['idEmpresa'] . " - " . $vEscalasCandidato->fields['idProceso'] . " - " . $vEscalasCandidato->fields['idCandidato'] . " - " . $vEscalasCandidato->fields['idPrueba'] . "]" . $vEscalasCandidato->fields['nomBloque'] . "<br />" . $vEscalasCandidato->fields['nomEscala'] . "<br />" . $vEscalasCandidato->fields['puntuacion'] . "</td>";
						$datosComunes .= "<td align=\"center\">" . $vEscalasCandidato->fields['puntuacion'] . "</td>";
					$vEscalasCandidato->MoveNext();
				}
			}else{
				for ($j=0, $max = $iTotalEscalas; $j < $max; $j++){
					$datosComunes .= "<td align=\"center\">&nbsp;</td>";
				}
			}

			if ($vCompetenciasCandidato->recordCount() > 0){
				while (!$vCompetenciasCandidato->EOF){
					//$datosComunes .= "<td align=\"center\">(C: " . $vCompetenciasCandidato->recordCount(). ") [" . $vEscalasCandidato->fields['idEmpresa'] . " - " . $vEscalasCandidato->fields['idProceso'] . " - " . $vEscalasCandidato->fields['idCandidato'] . " - " . $vEscalasCandidato->fields['idPrueba'] . "]" . $vCompetenciasCandidato->fields['nomTipoCompetencia'] . "<br />" . $vCompetenciasCandidato->fields['nomCompetencia'] . "<br />" . $vCompetenciasCandidato->fields['puntuacion'] . "</td>";
					$datosComunes .= "<td align=\"center\">" . $vCompetenciasCandidato->fields['puntuacion'] . "</td>";
					$vCompetenciasCandidato->MoveNext();
				}
			}else{
				for ($j=0, $max = $iTotalCompetencias; $j < $max; $j++){
					$datosComunes .= "<td align=\"center\">&nbsp;</td>";
				}
			}
			$datosComunes .= "</tr>";
			$i++;
			$vDatos->MoveNext();
		}
	}
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Content-Description: PE Generador de XLS");
	header ("Content-Disposition: attachment; filename=" . $nombre . ".xls");
	header ('Content-Transfer-Encoding: binary');
	header ('Content-Type: application/force-download');
	header ('Content-Type: application/octet-stream');
	header ("Content-type: application/x-msexcel");
	header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
	header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	header ("Pragma: no-cache");
	print  ( $buf . $DatosHeadInit . $datosHead . $datosComunes . $bufFin);
	//echo ( $buf . $DatosHeadInit . $datosHead . $datosComunes . $bufFin);




	/********************************************************************************/

	function getHTMLBloques($_idPrueba){

		global $conn;
		global $cEscalas_itemsDB;
		global $cBloquesDB;
		global $cEscalasDB;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cEscalas_items=  new Escalas_items();

		$cEscalas_items->setIdPrueba($_idPrueba);
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
		$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);

		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($_codIdiomaIso2);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();
				if($nEscalas > 0){
					$sRetorno .= '<td align="center" colspan="' . $nEscalas . '" ><b>' . $listaBloques->fields['nombre'] . "</b></td>";
				}
				$listaBloques->MoveNext();
			}
		}
		return $sRetorno;
	}

	function getHTMLBloquesDatos($vDatos){
		global $conn;
		global $sqlESCALAS;
		global $sOrder;
		global $iTotalEscalas;
		global $IdiomaEncontradoPrimerRegistro;
		$bGC=false;
		
		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";
		
		while (!$vDatos->EOF){
			$sqlESCALASIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlESCALASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlESCALASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlESCALASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlESCALASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlESCALASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlESCALASIn .= " ORDER BY fecAlta ASC ";
			//echo "<br />-->" . $sqlESCALASIn;exit;
			$vEscalasCandidato = $conn->Execute($sqlESCALASIn);

			$iTotalEscalas=$vEscalasCandidato->recordCount();
			$sBloque="";
			while (!$vEscalasCandidato->EOF){
				if ($sBloque != $vEscalasCandidato->fields['nomBloque']){
					$sqlBloquesIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlBloquesIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlBloquesIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlBloquesIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlBloquesIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlBloquesIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlBloquesIn .= " AND nomBloque=" . $conn->qstr($vEscalasCandidato->fields['nomBloque'], false) . "";
					$sqlBloquesIn .= " ORDER BY fecAlta ASC ";
					$vBloques = $conn->Execute($sqlBloquesIn);
					$iBloques = $vBloques->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iBloques . '" ><b>' . $vEscalasCandidato->fields['nomBloque'] . "</b></td>";
					$IdiomaEncontradoPrimerRegistro=$vEscalasCandidato->fields['codIdiomaIso2Informe'];
				}
				$sBloque = $vEscalasCandidato->fields['nomBloque'];
				$vEscalasCandidato->MoveNext();
			}
			if ($iTotalEscalas > 0){
				break;
			}
			$vDatos->MoveNext();
		}

		return $sRetorno;
	}

	function getHTMLEscalas($_idPrueba){
		global $conn;
		global $cEscalas_itemsDB;
		global $cBloquesDB;
		global $cEscalasDB;
		global $iTotalEscalas;
		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cEscalas_items=  new Escalas_items();

		$cEscalas_items->setIdPrueba($_idPrueba);
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
		$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);

		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($_codIdiomaIso2);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();
				$iTotalEscalas+=$nEscalas;
				if($nEscalas > 0){
					while(!$listaEscalas->EOF){
						$sRetorno .= '<td align="center"><b>' . $listaEscalas->fields['nombre'] . "</b></td>";
						$listaEscalas->MoveNext();
					}
				}
				$listaBloques->MoveNext();
			}
		}
		return $sRetorno;

	}

	function getHTMLEscalasDatos($vDatos){

		global $conn;
		global $sqlESCALAS;
		global $sOrder;
		global $iTotalEscalas;
		$bGC=false;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlESCALASIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlESCALASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlESCALASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlESCALASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlESCALASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlESCALASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlESCALASIn .= " ORDER BY fecAlta ASC ";
			$vEscalasCandidato = $conn->Execute($sqlESCALASIn);
			$iTotalEscalas=$vEscalasCandidato->recordCount();
			$sEscala="";
			while (!$vEscalasCandidato->EOF){
				if ($sEscala != $vEscalasCandidato->fields['nomEscala']){
					$sqlEscalasIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlEscalasIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlEscalasIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlEscalasIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlEscalasIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlEscalasIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlEscalasIn .= " AND nomEscala=" . $conn->qstr($vEscalasCandidato->fields['nomEscala'], false) . "";
					//$sqlEscalasIn .= " ORDER BY fecAlta ASC ";
					$vEscalas = $conn->Execute($sqlEscalasIn);
					$iEscalas = $vEscalas->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iEscalas . '" ><b>' . $vEscalasCandidato->fields['nomEscala'] . "</b></td>";
				}
				$sEscala = $vEscalasCandidato->fields['nomEscala'];
				$vEscalasCandidato->MoveNext();
			}
			if ($iTotalEscalas > 0){
				break;
			}
			$vDatos->MoveNext();
		}

		return $sRetorno;
	}

	//Josh
	function getHTMLFit($vDatos, $rowsParticipants){
		$sRetorno = "";
		$m = 0;
		
		while (!$vDatos->EOF){
			if($m < $rowsParticipants){
				$sRetorno .= '<td align="center" colspan="" ><b>' . $vDatos->fields['nomMatching'] . "</b></td>";
				$m++;
			}
			$vDatos->MoveNext();
		}
		$vDatos->MoveFirst();
		
		return $sRetorno;

	}
	function countParticipantsAndRows($vDatos){
		
		// Aquí contamos cuantos candidatos diferentes hay en los registros de export_personalidad_fit
		$counterParticipants = 1;
		$auxCounter = 0;
		$rowsParticipants = 0;
		
		while (!$vDatos->EOF){
			if($auxCounter > 0){
				if($vDatos->fields['idEmpresa'] != $dEmpresa 
				|| $vDatos->fields['idCandidato'] != $dCandidato 
				|| $vDatos->fields['idProceso'] != $dProceso){
					$counterParticipants++;
					$rowsParticipants = 0;
				}
			}
			
			$dEmpresa = $vDatos->fields['idEmpresa'];
			$dCandidato = $vDatos->fields['idCandidato'];
			$dProceso = $vDatos->fields['idProceso'];
			
			$auxCounter++;
			//$auxCounter++;
			$rowsParticipants++;
			
			$vDatos->MoveNext();
		}
		return [$counterParticipants, $rowsParticipants];
	}

	function getHTMLTiposCompetencias($_idPrueba){

		global $conn;
		global $cCompetenciasDB;
		global $cTipos_competenciasDB;
		global $cEscalasDB;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_codIdiomaIso2);
		$cTipos_competencias->setCodIdiomaIso2('es');
		$cTipos_competencias->setIdPrueba($_idPrueba);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);

		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();

		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
				$cCompetencias->setCodIdiomaIso2($_codIdiomaIso2);
				$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdPrueba($_idPrueba);
				$cCompetencias->setOrderBy("idCompetencia");
				$cCompetencias->setOrder("ASC");
				$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);

				$listaCompetencias = $conn->Execute($sqlCompetencias);
				$nCompetencias=$listaCompetencias->recordCount();
				if($nCompetencias > 0){
					$sRetorno .= '<td align="center" colspan="' . $nCompetencias . '" ><b>' . $listaTipoCompetencia->fields['nombre'] . "</b></td>";
				}
				$listaTipoCompetencia->MoveNext();
			}
		}
		return $sRetorno;
	}

	function getHTMLTiposCompetenciasDatos($vDatos){

		global $conn;
		global $sqlCOMPETENCIAS;
		global $sOrder;
		global $iTotalTiposCompetencias;
		global $IdiomaEncontradoPrimerRegistro;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlCOMPETENCIASIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlCOMPETENCIASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlCOMPETENCIASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlCOMPETENCIASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlCOMPETENCIASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlCOMPETENCIASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlCOMPETENCIASIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
			//echo "<br />A::" . $sqlCOMPETENCIASIn;
			$vCompetenciasCandidato = $conn->Execute($sqlCOMPETENCIASIn);
			$iTotalTiposCompetencias=$vCompetenciasCandidato->recordCount();
			$sTiposCompetencias="";
			while (!$vCompetenciasCandidato->EOF){
				if ($sTiposCompetencias != $vCompetenciasCandidato->fields['nomTipoCompetencia']){
					$sqlBloquesIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlBloquesIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlBloquesIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlBloquesIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlBloquesIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlBloquesIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlBloquesIn .= " AND nomTipoCompetencia=" . $conn->qstr($vCompetenciasCandidato->fields['nomTipoCompetencia'], false) . "";
					$sqlBloquesIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
					//echo "<br />B::" . $sqlCOMPETENCIASIn;
					$vBloques = $conn->Execute($sqlBloquesIn);
					$iBloques = $vBloques->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iBloques . '" ><b>' . $vCompetenciasCandidato->fields['nomTipoCompetencia'] . "</b></td>";
					if (empty($IdiomaEncontradoPrimerRegistro)){
						$IdiomaEncontradoPrimerRegistro=$vCompetenciasCandidato->fields['codIdiomaIso2Informe'];
					}
				}
				$sTiposCompetencias = $vCompetenciasCandidato->fields['nomTipoCompetencia'];
				$vCompetenciasCandidato->MoveNext();
			}
			if ($iTotalTiposCompetencias > 0){
				break;
			}
			$vDatos->MoveNext();
		}
		return $sRetorno;

	}

	function getHTMLCompetenciasDatos($vDatos){

		global $conn;
		global $sqlCOMPETENCIAS;
		global $sOrder;
		global $iTotalCompetencias;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlCOMPETENCIASIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlCOMPETENCIASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlCOMPETENCIASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlCOMPETENCIASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlCOMPETENCIASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlCOMPETENCIASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlCOMPETENCIASIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
			//echo "<br />A::" . $sqlCOMPETENCIASIn;
			$vCompetenciasCandidato = $conn->Execute($sqlCOMPETENCIASIn);
			$iTotalCompetencias=$vCompetenciasCandidato->recordCount();
			$sCompetencias="";
			while (!$vCompetenciasCandidato->EOF){
				if ($sCompetencias != $vCompetenciasCandidato->fields['nomCompetencia']){
					$sqlBloquesIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlBloquesIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlBloquesIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlBloquesIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlBloquesIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlBloquesIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlBloquesIn .= " AND nomCompetencia=" . $conn->qstr($vCompetenciasCandidato->fields['nomCompetencia'], false) . "";
					$sqlBloquesIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
					//echo "<br />B::" . $sqlBloquesIn;
					$vBloques = $conn->Execute($sqlBloquesIn);
					$iBloques = $vBloques->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iBloques . '" ><b>' . $vCompetenciasCandidato->fields['nomCompetencia'] . "</b></td>";
				}
				$sCompetencias = $vCompetenciasCandidato->fields['nomCompetencia'];
				$vCompetenciasCandidato->MoveNext();
			}
			if ($iTotalCompetencias > 0){
				break;
			}
			$vDatos->MoveNext();
		}

		return $sRetorno;

	}

	function getHTMLCompetencias($_idPrueba){

		global $conn;
		global $cCompetenciasDB;
		global $cTipos_competenciasDB;
		global $cEscalasDB;
		global $iTotalCompetencias;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_codIdiomaIso2);
		$cTipos_competencias->setCodIdiomaIso2('es');
		$cTipos_competencias->setIdPrueba($_idPrueba);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);

		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();

		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
				$cCompetencias->setCodIdiomaIso2($_codIdiomaIso2);
				$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdPrueba($_idPrueba);
				$cCompetencias->setOrderBy("idCompetencia");
				$cCompetencias->setOrder("ASC");
				$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);

				$listaCompetencias = $conn->Execute($sqlCompetencias);
				$nCompetencias=$listaCompetencias->recordCount();
				$iTotalCompetencias+=$nCompetencias;
				if($nCompetencias > 0){
					while(!$listaCompetencias->EOF){
						$sRetorno .= '<td align="center"><b>' . $listaCompetencias->fields['nombre'] . "</b></td>";
						$listaCompetencias->MoveNext();
					}
				}
				$listaTipoCompetencia->MoveNext();
			}
		}
		return $sRetorno;
	}
?>
=======
<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

	require_once('./include/Configuracion.php');
	//include_once('include/Idiomas.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "ToXLS.php");

	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competenciasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_competencias/Tipos_competencias.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/CompetenciasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Competencias/Competencias.php");


include_once ('include/conexion.php');

//	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$sqlToExcel	= new ToXLS($conn);  // Entidad DB

	$sDesPrueba = $_REQUEST['LSTDescPrueba'];
	$_IdPrueba = "";
	$cPrueba=  new Pruebas();
	$cPruebaDB=  new PruebasDB($conn);

	if (!empty($sDesPrueba)){

		$cPrueba->setNombre($sDesPrueba);
		//$sSQLPruebas = $cPruebaDB->readLista($cPrueba);
		$sSQLPruebas = "SELECT * FROM pruebas WHERE UPPER(nombre) = UPPER('" . $sDesPrueba . "')";
		$vPruebas = $conn->Execute($sSQLPruebas);
		$_IdPrueba = $vPruebas->fields['idPrueba'];
	}else{
		echo "DesPrueba::" . constant("ERR");
		exit;
	}
	if (empty($_IdPrueba)){
		echo "IdPrueba::" . constant("ERR");
		exit;
	}

	if ($_IdPrueba == "32" || $_IdPrueba == "42"){	//CIP
		$sSQLUPDATE = "UPDATE export_personalidad_competencias SET nomCompetencia=nomTipoCompetencia WHERE idPrueba=" . $_IdPrueba . " AND nomCompetencia ='' OR nomCompetencia IS NULL ";
		$conn->Execute($sSQLUPDATE);
	}
	$sSQLUPDATE2= "UPDATE proceso_informes, export_personalidad  SET export_personalidad.idTipoInforme = proceso_informes.idTipoInforme WHERE proceso_informes.idEmpresa=export_personalidad.idEmpresa AND proceso_informes.idProceso=export_personalidad.idProceso AND proceso_informes.idPrueba=export_personalidad.idPrueba AND export_personalidad.idTipoInforme=0 AND export_personalidad.idPrueba = " . $_IdPrueba;
	$conn->Execute($sSQLUPDATE2);
	$sSQLUPDATE2= "UPDATE export_personalidad  SET cobrado=1 WHERE cobrado=0 AND idPrueba = " . $_IdPrueba;
	$conn->Execute($sSQLUPDATE2);


	$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
	$cBloquesDB = new BloquesDB($conn);
	$cEscalasDB = new EscalasDB($conn);

	$cCompetenciasDB = new CompetenciasDB($conn);
	$cTipos_competenciasDB = new Tipos_competenciasDB($conn);

//	session_start();
	$sDESCListaExcel = "";
	$aArray =explode(constant("CHAR_SEPARA"), base64_decode($_REQUEST['fSQLtoEXCEL']));

	$sql = $aArray[0];
	$nombre = $aArray[1];
	if (!empty($sql)){
		$sEntidad = ucfirst($nombre);
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");
		$cEntidad	= new $sEntidad();  // Entidad
		$sDESCListaExcel = $cEntidad->getDESCListaExcel();
	}else{
		echo constant("ERR");
		exit;
	}
	$InitSQL = $sql;
	//echo $InitSQL;exit;
	$sOrder = substr($sql, strpos($sql, "ORDER BY"));
	$sWhere = substr($sql, strpos($sql, "WHERE"));
	$sWhere = str_replace($sOrder,"", $sWhere);

	//$sqlESCALAS = str_replace("export_personalidad","export_personalidad_laboral", $sql);

	//Quitamos la agrupación si la hay
	$sGroup ="GROUP BY idEmpresa, idProceso, idCandidato, idPrueba, idBaremo";
	$sWhere = str_replace($sGroup,"", $sWhere);
	//Quitamos lo de cobrado
	$sCobrado ="AND cobrado='1'";
	$sWhere = str_replace($sCobrado,"", $sWhere);

	//echo $sWhere;exit;
	$sqlESCALAS = "SELECT * FROM export_personalidad_laboral " . $sWhere;
	$sqlCOMPETENCIAS = "SELECT * FROM export_personalidad_competencias " . $sWhere;
	//echo $sqlCOMPETENCIAS;exit;

	$sql = str_replace("*",$cEntidad->getPKListaExcel(), $sql);

	if (empty($nombre)){
		$nombre = "ExcelFile";
	}
	if (empty($_REQUEST['fPintaCabecera'])){
		$_REQUEST['fPintaCabecera'] = true;
	}
	if (empty($_REQUEST['fSepararCabecera'])){
		$_REQUEST['fSepararCabecera'] = false;
	}

	//echo "<br />" . $sql;exit;
	//Datos comunes Candidatos
	$vDatos = $conn->Execute($InitSQL);
	$iTotalEscalas=1;
	$iTotalCompetencias=1;
	$buf = "";
	$buf .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	$buf .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"es\">";
	// put in some style
	$buf .= "<head>" .
			"<title>Export Personality</title>" .
			"<meta name=\"language\" content=\"es\" />" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	$buf .= "</head>";

	// generate the body
	$buf .= "<body>";
	$buf .= "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";

	$bufFin = "</table>";
	$bufFin .= "</body>";
	$bufFin .= "</html>";
	//Empresa	Proceso	Nombre	Apellido1	Apellido2	Email	DNI / N. ID	Prueba	Fecha De Prueba	Fecha De Alta Proceso
	$head="";
	$datosHeadInit="";
	$datosComunes="";
	$datosHead="";

	$bTrazaId=false;
	$columnaNum=true;	//Para que pinte una columna inicial con el número
	$colspan=19;	//16 Fijas (Empresa,Proceso,Nombre,Apellido1,Apellido2,email,DNI/ N. ID,Prueba,Informe,Baremo,Idioma,Fecha de Prueba,Fecha de Alta Proceso,Sexo,Edad,Formación,Nivel,Área,País de procedencia)
	if ($columnaNum){
		$colspan++;
	}
	$DatosHeadInit = "<tr><td colspan=" . $colspan . ">&nbsp</td>";

	$IdiomaEncontradoPrimerRegistro="";
	//Escalas
	//$DatosHeadBloquesEscalas = getHTMLBloques($_IdPrueba);
	$DatosHeadBloquesEscalas = getHTMLBloquesDatos($vDatos);
	$vDatos->Move(0);
	//$DatosHeadBloquesEscalas .="<td rowspan=2><b>" . constant("STR_G_C") . "</b></td>";
	//Competencias
	//$DatosHeadTiposCompetencias = getHTMLTiposCompetencias($_IdPrueba);
	$DatosHeadTiposCompetencias = getHTMLTiposCompetenciasDatos($vDatos);
	$vDatos->Move(0);
	@include(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . $IdiomaEncontradoPrimerRegistro . ".php");
	//$DatosHeadTiposCompetencias .="<td rowspan=2><b>" . constant("STR_G_C") . "</b></td>";

	$DatosHeadInit = $DatosHeadInit . $DatosHeadBloquesEscalas . $DatosHeadTiposCompetencias . "</tr>";
	//echo $DatosHeadInit;exit;

//	$DatosHeadEscalas = getHTMLEscalas($_IdPrueba);
	$DatosHeadEscalas = getHTMLEscalasDatos($vDatos);
	$vDatos->Move(0);
//	$DatosHeadCompetencias = getHTMLCompetencias($_IdPrueba);
	$DatosHeadCompetencias = getHTMLCompetenciasDatos($vDatos);
	$vDatos->Move(0);

	$datosHead .= "<tr>";
	if ($columnaNum){
		$datosHead .= "<td align=\"center\"><b>Nº</b></td>";
	}

	$datosHead .= "<td align=\"center\"><b>" . constant("STR_EMPRESA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_PROCESO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_NOMBRE") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_APELLIDO1") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_APELLIDO2") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_EMAIL") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_DNI") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_PRUEBA") . "</b></td>";
		$datosHead .= "<td align=\"center\"><b>" . constant("STR_INFORME") . "</b></td>";
		$datosHead .= "<td align=\"center\"><b>" . constant("STR_BAREMO") . "</b></td>";
		$datosHead .= "<td align=\"center\"><b>" . constant("STR_IDIOMA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_FECHA_DE_PRUEBA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_FECHA_DE_ALTA_PROCESO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_SEXO") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_EDAD") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_FORMACION") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_NIVEL") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_AREA") . "</b></td>";
	$datosHead .= "<td align=\"center\"><b>" . constant("STR_PAIS_DE_PROCEDENCIA") . "</b></td>";
	$datosHead .= $DatosHeadEscalas;
	$datosHead .= $DatosHeadCompetencias;
	$datosHead .= "</tr>";
	$sqlESCALASIn = "";
	//echo $datosHead;exit;
	$i=0;
	while (!$vDatos->EOF){
		$sqlESCALASIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
		$sqlESCALASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
		$sqlESCALASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
		$sqlESCALASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
		$sqlESCALASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
		$sqlESCALASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
		$sqlESCALASIn .= $sOrder;
		$sqlESCALASIn .= " ,fecAlta ASC ";
		//echo "<br />" . $i . " -> " . $sqlESCALASIn;exit;
		$vEscalasCandidato = $conn->Execute($sqlESCALASIn);

		$sqlCOMPETENCIASIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
		$sqlCOMPETENCIASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
		$sqlCOMPETENCIASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
		$sqlCOMPETENCIASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
		$sqlCOMPETENCIASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
		$sqlCOMPETENCIASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
		//$sqlCOMPETENCIASIn .= $sOrder;
		$sqlCOMPETENCIASIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
		//echo "<br />" . $i . " -> " . $sqlCOMPETENCIASIn;exit;
		$vCompetenciasCandidato = $conn->Execute($sqlCOMPETENCIASIn);

		$datosComunes .= "<tr>";
		if ($columnaNum){
			$datosComunes .= "<td align=\"center\" style=\"background-color: #ccc;\"><font color=\"#000\">" . ($i+1);
			if ($bTrazaId){
				$datosComunes .= " -> [" . $vDatos->fields['idEmpresa'] . " - " . $vDatos->fields['idProceso'] . " - " . $vDatos->fields['idCandidato'] . " - " . $vDatos->fields['idPrueba'] . "]";
			}
			$datosComunes .= "</font></td>";
		}

		$_codIdiomaInforme = $vEscalasCandidato->fields['codIdiomaIso2Informe'];
		if (empty($_codIdiomaInforme)){
			$_codIdiomaInforme = $vCompetenciasCandidato->fields['codIdiomaIso2Informe'];
		}
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descEmpresa'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descProceso'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['nombre'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['apellido1'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['apellido2'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['email'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['dni'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descPrueba'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descTipoInforme'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descBaremo'] . "</font></td>";
			$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $_codIdiomaInforme . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['fecPrueba'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['fecAltaProceso'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descSexo'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descEdad'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descFormacion'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descNivel'] . "</font></td>";
		$datosComunes .= "<td align=\"left\"><font color=\"blue\">" . $vDatos->fields['descArea'] . "</font></td>";
		$datosComunes .= "<td align=\"center\"><font color=\"blue\">" . $vDatos->fields['codIso2PaisProcedencia'] . "</font></td>";

		if ($vEscalasCandidato->recordCount() > 0){
			while (!$vEscalasCandidato->EOF){
					//$datosComunes .= "<td align=\"center\">(" . $vEscalasCandidato->recordCount(). ") [" . $vEscalasCandidato->fields['idEmpresa'] . " - " . $vEscalasCandidato->fields['idProceso'] . " - " . $vEscalasCandidato->fields['idCandidato'] . " - " . $vEscalasCandidato->fields['idPrueba'] . "]" . $vEscalasCandidato->fields['nomBloque'] . "<br />" . $vEscalasCandidato->fields['nomEscala'] . "<br />" . $vEscalasCandidato->fields['puntuacion'] . "</td>";
					$datosComunes .= "<td align=\"center\">" . $vEscalasCandidato->fields['puntuacion'] . "</td>";
				$vEscalasCandidato->MoveNext();
			}
		}else{
			for ($j=0, $max = $iTotalEscalas; $j < $max; $j++){
				$datosComunes .= "<td align=\"center\">&nbsp;</td>";
			}
		}

		if ($vCompetenciasCandidato->recordCount() > 0){
			while (!$vCompetenciasCandidato->EOF){
				//$datosComunes .= "<td align=\"center\">(C: " . $vCompetenciasCandidato->recordCount(). ") [" . $vEscalasCandidato->fields['idEmpresa'] . " - " . $vEscalasCandidato->fields['idProceso'] . " - " . $vEscalasCandidato->fields['idCandidato'] . " - " . $vEscalasCandidato->fields['idPrueba'] . "]" . $vCompetenciasCandidato->fields['nomTipoCompetencia'] . "<br />" . $vCompetenciasCandidato->fields['nomCompetencia'] . "<br />" . $vCompetenciasCandidato->fields['puntuacion'] . "</td>";
				$datosComunes .= "<td align=\"center\">" . $vCompetenciasCandidato->fields['puntuacion'] . "</td>";
				$vCompetenciasCandidato->MoveNext();
			}
		}else{
			for ($j=0, $max = $iTotalCompetencias; $j < $max; $j++){
				$datosComunes .= "<td align=\"center\">&nbsp;</td>";
			}
		}
		$datosComunes .= "</tr>";
		$i++;
		$vDatos->MoveNext();
	}
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Content-Description: PE Generador de XLS");
	header ("Content-Disposition: attachment; filename=" . $nombre . ".xls");
	header ('Content-Transfer-Encoding: binary');
	header ('Content-Type: application/force-download');
	header ('Content-Type: application/octet-stream');
	header ("Content-type: application/x-msexcel");
	header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
	header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	header ("Pragma: no-cache");
	print  ( $buf . $DatosHeadInit . $datosHead . $datosComunes . $bufFin);
//echo ( $buf . $DatosHeadInit . $datosHead . $datosComunes . $bufFin);




	/********************************************************************************/

	function getHTMLBloques($_idPrueba){

		global $conn;
		global $cEscalas_itemsDB;
		global $cBloquesDB;
		global $cEscalasDB;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cEscalas_items=  new Escalas_items();

		$cEscalas_items->setIdPrueba($_idPrueba);
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
		$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);

		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($_codIdiomaIso2);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();
				if($nEscalas > 0){
					$sRetorno .= '<td align="center" colspan="' . $nEscalas . '" ><b>' . $listaBloques->fields['nombre'] . "</b></td>";
				}
				$listaBloques->MoveNext();
			}
		}
		return $sRetorno;
	}

	function getHTMLBloquesDatos($vDatos){

		global $conn;
		global $sqlESCALAS;
		global $sOrder;
		global $iTotalEscalas;
		global $IdiomaEncontradoPrimerRegistro;
		$bGC=false;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlESCALASIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlESCALASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlESCALASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlESCALASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlESCALASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlESCALASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlESCALASIn .= " ORDER BY fecAlta ASC ";
			//echo "<br />-->" . $sqlESCALASIn;exit;
			$vEscalasCandidato = $conn->Execute($sqlESCALASIn);
			$iTotalEscalas=$vEscalasCandidato->recordCount();
			$sBloque="";
			while (!$vEscalasCandidato->EOF){
				if ($sBloque != $vEscalasCandidato->fields['nomBloque']){
					$sqlBloquesIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlBloquesIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlBloquesIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlBloquesIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlBloquesIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlBloquesIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlBloquesIn .= " AND nomBloque=" . $conn->qstr($vEscalasCandidato->fields['nomBloque'], false) . "";
					$sqlBloquesIn .= " ORDER BY fecAlta ASC ";
					$vBloques = $conn->Execute($sqlBloquesIn);
					$iBloques = $vBloques->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iBloques . '" ><b>' . $vEscalasCandidato->fields['nomBloque'] . "</b></td>";
					$IdiomaEncontradoPrimerRegistro=$vEscalasCandidato->fields['codIdiomaIso2Informe'];
				}
				$sBloque = $vEscalasCandidato->fields['nomBloque'];
				$vEscalasCandidato->MoveNext();
			}
			if ($iTotalEscalas > 0){
				break;
			}
			$vDatos->MoveNext();
		}

		return $sRetorno;
	}

	function getHTMLEscalas($_idPrueba){
		global $conn;
		global $cEscalas_itemsDB;
		global $cBloquesDB;
		global $cEscalasDB;
		global $iTotalEscalas;
		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cEscalas_items=  new Escalas_items();

		$cEscalas_items->setIdPrueba($_idPrueba);
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
		$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
		$listaBloques = $conn->Execute($sqlBloques);

		$nBloques= $listaBloques->recordCount();

		if($nBloques > 0){
			while(!$listaBloques->EOF){
				$cEscalas = new Escalas();
				$cEscalas->setCodIdiomaIso2($_codIdiomaIso2);
				$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
				$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
				$cEscalas->setOrderBy("idEscala");
				$cEscalas->setOrder("ASC");
				$sqlEscalas = $cEscalasDB->readLista($cEscalas);
				$listaEscalas = $conn->Execute($sqlEscalas);
				$nEscalas=$listaEscalas->recordCount();
				$iTotalEscalas+=$nEscalas;
				if($nEscalas > 0){
					while(!$listaEscalas->EOF){
						$sRetorno .= '<td align="center"><b>' . $listaEscalas->fields['nombre'] . "</b></td>";
						$listaEscalas->MoveNext();
					}
				}
				$listaBloques->MoveNext();
			}
		}
		return $sRetorno;

	}

	function getHTMLEscalasDatos($vDatos){

		global $conn;
		global $sqlESCALAS;
		global $sOrder;
		global $iTotalEscalas;
		$bGC=false;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlESCALASIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlESCALASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlESCALASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlESCALASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlESCALASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlESCALASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlESCALASIn .= " ORDER BY fecAlta ASC ";
			$vEscalasCandidato = $conn->Execute($sqlESCALASIn);
			$iTotalEscalas=$vEscalasCandidato->recordCount();
			$sEscala="";
			while (!$vEscalasCandidato->EOF){
				if ($sEscala != $vEscalasCandidato->fields['nomEscala']){
					$sqlEscalasIn = $sqlESCALAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlEscalasIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlEscalasIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlEscalasIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlEscalasIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlEscalasIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlEscalasIn .= " AND nomEscala=" . $conn->qstr($vEscalasCandidato->fields['nomEscala'], false) . "";
					//$sqlEscalasIn .= " ORDER BY fecAlta ASC ";
					$vEscalas = $conn->Execute($sqlEscalasIn);
					$iEscalas = $vEscalas->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iEscalas . '" ><b>' . $vEscalasCandidato->fields['nomEscala'] . "</b></td>";
				}
				$sEscala = $vEscalasCandidato->fields['nomEscala'];
				$vEscalasCandidato->MoveNext();
			}
			if ($iTotalEscalas > 0){
				break;
			}
			$vDatos->MoveNext();
		}

		return $sRetorno;
	}

	function getHTMLTiposCompetencias($_idPrueba){

		global $conn;
		global $cCompetenciasDB;
		global $cTipos_competenciasDB;
		global $cEscalasDB;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_codIdiomaIso2);
		$cTipos_competencias->setCodIdiomaIso2('es');
		$cTipos_competencias->setIdPrueba($_idPrueba);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);

		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();

		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
				$cCompetencias->setCodIdiomaIso2($_codIdiomaIso2);
				$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdPrueba($_idPrueba);
				$cCompetencias->setOrderBy("idCompetencia");
				$cCompetencias->setOrder("ASC");
				$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);

				$listaCompetencias = $conn->Execute($sqlCompetencias);
				$nCompetencias=$listaCompetencias->recordCount();
				if($nCompetencias > 0){
					$sRetorno .= '<td align="center" colspan="' . $nCompetencias . '" ><b>' . $listaTipoCompetencia->fields['nombre'] . "</b></td>";
				}
				$listaTipoCompetencia->MoveNext();
			}
		}
		return $sRetorno;
	}

	function getHTMLTiposCompetenciasDatos($vDatos){

		global $conn;
		global $sqlCOMPETENCIAS;
		global $sOrder;
		global $iTotalTiposCompetencias;
		global $IdiomaEncontradoPrimerRegistro;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlCOMPETENCIASIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlCOMPETENCIASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlCOMPETENCIASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlCOMPETENCIASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlCOMPETENCIASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlCOMPETENCIASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlCOMPETENCIASIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
			//echo "<br />A::" . $sqlCOMPETENCIASIn;
			$vCompetenciasCandidato = $conn->Execute($sqlCOMPETENCIASIn);
			$iTotalTiposCompetencias=$vCompetenciasCandidato->recordCount();
			$sTiposCompetencias="";
			while (!$vCompetenciasCandidato->EOF){
				if ($sTiposCompetencias != $vCompetenciasCandidato->fields['nomTipoCompetencia']){
					$sqlBloquesIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlBloquesIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlBloquesIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlBloquesIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlBloquesIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlBloquesIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlBloquesIn .= " AND nomTipoCompetencia=" . $conn->qstr($vCompetenciasCandidato->fields['nomTipoCompetencia'], false) . "";
					$sqlBloquesIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
					//echo "<br />B::" . $sqlCOMPETENCIASIn;
					$vBloques = $conn->Execute($sqlBloquesIn);
					$iBloques = $vBloques->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iBloques . '" ><b>' . $vCompetenciasCandidato->fields['nomTipoCompetencia'] . "</b></td>";
					if (empty($IdiomaEncontradoPrimerRegistro)){
						$IdiomaEncontradoPrimerRegistro=$vCompetenciasCandidato->fields['codIdiomaIso2Informe'];
					}
				}
				$sTiposCompetencias = $vCompetenciasCandidato->fields['nomTipoCompetencia'];
				$vCompetenciasCandidato->MoveNext();
			}
			if ($iTotalTiposCompetencias > 0){
				break;
			}
			$vDatos->MoveNext();
		}
		return $sRetorno;

	}

	function getHTMLCompetenciasDatos($vDatos){

		global $conn;
		global $sqlCOMPETENCIAS;
		global $sOrder;
		global $iTotalCompetencias;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		while (!$vDatos->EOF){
			$sqlCOMPETENCIASIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
			$sqlCOMPETENCIASIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
			$sqlCOMPETENCIASIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
			$sqlCOMPETENCIASIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
			$sqlCOMPETENCIASIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
			$sqlCOMPETENCIASIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
			$sqlCOMPETENCIASIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
			//echo "<br />A::" . $sqlCOMPETENCIASIn;
			$vCompetenciasCandidato = $conn->Execute($sqlCOMPETENCIASIn);
			$iTotalCompetencias=$vCompetenciasCandidato->recordCount();
			$sCompetencias="";
			while (!$vCompetenciasCandidato->EOF){
				if ($sCompetencias != $vCompetenciasCandidato->fields['nomCompetencia']){
					$sqlBloquesIn = $sqlCOMPETENCIAS . " AND idEmpresa=" . $vDatos->fields['idEmpresa'] . " ";
					$sqlBloquesIn .= " AND idProceso=" . $vDatos->fields['idProceso'] . " ";
					$sqlBloquesIn .= " AND idCandidato=" . $vDatos->fields['idCandidato'] . " ";
					$sqlBloquesIn .= " AND idPrueba=" . $vDatos->fields['idPrueba'] . " ";
					$sqlBloquesIn .= " AND idBaremo=" . $vDatos->fields['idBaremo'] . " ";
					$sqlBloquesIn .= " AND idTipoInforme=" . $vDatos->fields['idTipoInforme'] . " ";
					$sqlBloquesIn .= " AND nomCompetencia=" . $conn->qstr($vCompetenciasCandidato->fields['nomCompetencia'], false) . "";
					$sqlBloquesIn .= " ORDER BY idTipoCompetencia DESC, idCompetencia ASC ";
					//echo "<br />B::" . $sqlBloquesIn;
					$vBloques = $conn->Execute($sqlBloquesIn);
					$iBloques = $vBloques->recordCount();
					$sRetorno .= '<td align="center" colspan="' . $iBloques . '" ><b>' . $vCompetenciasCandidato->fields['nomCompetencia'] . "</b></td>";
				}
				$sCompetencias = $vCompetenciasCandidato->fields['nomCompetencia'];
				$vCompetenciasCandidato->MoveNext();
			}
			if ($iTotalCompetencias > 0){
				break;
			}
			$vDatos->MoveNext();
		}

		return $sRetorno;

	}

	function getHTMLCompetencias($_idPrueba){

		global $conn;
		global $cCompetenciasDB;
		global $cTipos_competenciasDB;
		global $cEscalasDB;
		global $iTotalCompetencias;

		$_codIdiomaIso2="es";	//En español siempre tenemos que tenerlo
		$sRetorno = "";

		$cTipos_competencias = new Tipos_competencias();
		$cTipos_competencias->setCodIdiomaIso2($_codIdiomaIso2);
		$cTipos_competencias->setCodIdiomaIso2('es');
		$cTipos_competencias->setIdPrueba($_idPrueba);
		$cTipos_competencias->setOrderBy("idTipoCompetencia");
		$cTipos_competencias->setOrder("ASC");
		$sqlTipos_competencias = $cTipos_competenciasDB->readLista($cTipos_competencias);

		$listaTipoCompetencia = $conn->Execute($sqlTipos_competencias);
		$nTCompetencias= $listaTipoCompetencia->recordCount();

		if($nTCompetencias>0){
			while(!$listaTipoCompetencia->EOF){

				$cCompetencias = new Competencias();
				$cCompetencias->setCodIdiomaIso2($_codIdiomaIso2);
				$cCompetencias->setIdTipoCompetencia($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdTipoCompetenciaHast($listaTipoCompetencia->fields['idTipoCompetencia']);
				$cCompetencias->setIdPrueba($_idPrueba);
				$cCompetencias->setOrderBy("idCompetencia");
				$cCompetencias->setOrder("ASC");
				$sqlCompetencias = $cCompetenciasDB->readLista($cCompetencias);

				$listaCompetencias = $conn->Execute($sqlCompetencias);
				$nCompetencias=$listaCompetencias->recordCount();
				$iTotalCompetencias+=$nCompetencias;
				if($nCompetencias > 0){
					while(!$listaCompetencias->EOF){
						$sRetorno .= '<td align="center"><b>' . $listaCompetencias->fields['nombre'] . "</b></td>";
						$listaCompetencias->MoveNext();
					}
				}
				$listaTipoCompetencia->MoveNext();
			}
		}
		return $sRetorno;
	}
?>
>>>>>>> ef67b2adad35376e7004f53c2ad7cef5f1096846
