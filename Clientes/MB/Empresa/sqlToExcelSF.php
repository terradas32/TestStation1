<?php
	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "ToXLS.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/EscalasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas/Escalas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/BloquesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Bloques/Bloques.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades			= new Utilidades();
	$cRespuestas_pruebas	= new Respuestas_pruebas();  // Entidad
	$cRespuestas_pruebasDB	= new Respuestas_pruebasDB($conn);  // Entidad
	$cItems					= new Items();  // Entidad
	$cItemsDB				= new ItemsDB($conn);  // Entidad
	$cBloquesDB				= new BloquesDB($conn);
	$cEscalasDB 			= new EscalasDB($conn);
	$cCandidatosDB			= new CandidatosDB($conn);  // Entidad
	
	$aCabExcel		=	array("descProceso","nomPrueba","item","valor","dni");
	$aPK	=	array("Proceso","Prueba","Item","Valor","Candidato");
	
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
//	echo "<br />" . $sql;exit; 
	@set_time_limit(0);
	ini_set("memory_limit","2048M");
	
	$vVector = $conn->Execute($sql);

	//Relleno el campo valor de la tabla con el valor calculado segun prueba
	//Por cada ORDER BY idProceso,idPrueba,idCandidato,idItem ASC
	trataValores($vVector, $nombre);
	//Fin de Relleno el campo valor
	
	////////////////////////////////////////
	// Solo se tratan la prueba de PRISM@ y CML
	// Desde el form solo aparecerán los procesos con los candidatos
	// que tengan tanto PRISM@ como CML terminados.
	
	//INVERSOS PRISM@
	$cItems_inversosDB = new Items_inversosDB($conn);
	$cItems_inversos = new Items_inversos();
	$cItems_inversos->setIdPrueba("24");
	$cItems_inversos->setIdPruebaHast("24");
	$sqlInversosPRISMA = $cItems_inversosDB->readLista($cItems_inversos);
//	echo "<br />" . $sqlInversosPRISMA;
	$listaInversosPRISMA = $conn->Execute($sqlInversosPRISMA);
	$nInversosPRISMA = $listaInversosPRISMA->recordCount();
	$aInversosPRISMA = array();
	if($nInversosPRISMA > 0){
		$i=0;
		while(!$listaInversosPRISMA->EOF){
			$aInversosPRISMA[$i] = $listaInversosPRISMA->fields['idItem'];	
			$i++;
			$listaInversosPRISMA->MoveNext();
		}
	}
	//INVERSOS CML
	$cItems_inversosDB = new Items_inversosDB($conn);
	$cItems_inversos = new Items_inversos();
	$cItems_inversos->setIdPrueba("13");
	$cItems_inversos->setIdPruebaHast("13");
	$sqlInversosCML = $cItems_inversosDB->readLista($cItems_inversos);
//	echo "<br />" . $sqlInversosCML;
	$listaInversosCML = $conn->Execute($sqlInversosCML);
	$nInversosCML = $listaInversosCML->recordCount();
	$aInversosCML = array();
	if($nInversosCML > 0){
		$i=0;
		while(!$listaInversosCML->EOF){
			$aInversosCML[$i] = $listaInversosCML->fields['idItem'];	
			$i++;
			$listaInversosCML->MoveNext();
		}
	}
	////////////////////////////////////////

	//Preparamos la Query para el conjunto de candidatos a tratar
	$sSQLCandidatos = str_replace("respuestas_pruebas_items", "respuestas_pruebas", $sql);
	$sSQLCandidatos = str_replace("idProceso,idPrueba,idCandidato,idItem ASC", "idCandidato, idProceso ASC, idPrueba DESC", $sSQLCandidatos);
//	echo "<br />" . $sSQLCandidatos;exit;
	$vVectorCandidatos = $conn->Execute($sSQLCandidatos);
	//FIN Preparamos la Query

	//Generamos en HTML la información para el EXCEL, con las lineas de cabecera fijas
	$buf = "";
	$buf .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	$buf .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"es\">";
	// put in some style
	$buf .= "<head>" .
	"<title>Export Agility</title>" .
	"<meta name=\"language\" content=\"es\" />" .
	"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	$buf .= "</head>";

	// generate the body
	$buf .= "<body>";
	$buf .= "<table border=1 cellspacing=0 cellpadding=0 >";
	$buf .= "<tr>";
	$buf .= 	"<td align=center style='background:#deebf6;'><b>^UserId</b></td>";
	$buf .= 	"<td align=center style='background:#deebf6;'><b>COMPMOTIV</b></td>";
	$buf .= 	"<td align=center style='background:#deebf6;'><b>FECHA</b></td>";
	$buf .= 	"<td align=center style='background:#deebf6;'><b>COMPETENCIA</b></td>";
	$buf .= 	"<td align=center style='background:#deebf6;'><b>VALOR</b></td>";
	$buf .= "</tr>";
	
	//Recorro el vector de usuarios y pinto sus DNI'S
	$sIdCandidatoANT = "";
	$aPuntuacionesPRISMA = array();
	$aPuntuacionesCML = array();
	$bufC1 = "";
	$bufC2 = "";
	$bufC3 = "";
	$bufC4 = "";
	$bufC5 = "";

	while (!$vVectorCandidatos->EOF){
		
		if ($sIdCandidatoANT != $vVectorCandidatos->fields['idCandidato'])
		{
			if ($sIdCandidatoANT != ""){
				$bufC1 = "";
				$bufC2 = "";
				$bufC3 = "";
				$bufC4 = "";
				$bufC5 = "";
				
			}
			$cCandidatos			= new Candidatos();
			$cCandidatos->setIdCandidato($vVectorCandidatos->fields['idCandidato']);
			$cCandidatos->setIdEmpresa($vVectorCandidatos->fields['idEmpresa']);
			$cCandidatos->setIdProceso($vVectorCandidatos->fields['idProceso']);
			$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
			//DNI
			$sDNI = ($cCandidatos->getDni() != "") ? $cCandidatos->getDni() : $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() ;
			
		}
		//Sacamos la puntuación de cada persona y prueba por escala
		
		switch ($vVectorCandidatos->fields['idPrueba'])
		{
			case 13:	//Tipo Prisma CML
				$aPuntuacionesCML = array();
				$aPuntuacionesCML = calculosGlobalesEscala($vVectorCandidatos->fields['idEmpresa'], $vVectorCandidatos->fields['idProceso'], $vVectorCandidatos->fields['idPrueba'], $vVectorCandidatos->fields['codIdiomaIso2'], $vVectorCandidatos->fields['idCandidato'], $aInversosCML);
				$i=0;
				$cEscalas_items=  new Escalas_items();
				$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
				$cEscalas_items->setIdPrueba($vVectorCandidatos->fields['idPrueba']);
				$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//				echo "<br />" . $sqlEscalas_items;
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
				$cBloques->setCodIdiomaIso2($vVectorCandidatos->fields['codIdiomaIso2']);
				$cBloques->setIdBloque($sBloques);
				$cBloques->setOrderBy("idBloque");
				$cBloques->setOrder("ASC");
				$sqlBloques = $cBloquesDB->readLista($cBloques);
//				echo "<br />" . $sqlBloques;
				$listaBloques = $conn->Execute($sqlBloques);
				
				$nBloques= $listaBloques->recordCount();
				
				if($nBloques>0){
					$bufC1 .= 	"<td align=left style='white-space:nowrap;'>" . $sDNI . "</td>";
					$bufC1 .= 	"<td align=left >COMPMOTIV</td>";
					$bufC1 .= 	"<td align=center >" . $conn->UserDate($vVectorCandidatos->fields['fecMod'],constant("USR_FECHA"),false) . "</td>";
			
					while(!$listaBloques->EOF){
						$cEscalas = new Escalas();
					 	$cEscalas->setCodIdiomaIso2($vVectorCandidatos->fields['codIdiomaIso2']);
					 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
					 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
					 	$cEscalas->setOrderBy("idEscala");
					 	$cEscalas->setOrder("ASC");
					 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
//					 	echo "<br />" . $sqlEscalas;
					 	$listaEscalas = $conn->Execute($sqlEscalas);
					 	$nEscalas=$listaEscalas->recordCount();
//					 	echo $nEscalas;exit;
					 	if($nEscalas > 0){
					 		while(!$listaEscalas->EOF){
						        $iPBaremada = $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']][$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
						        $listaEscalas->MoveNext();
					 		}
					 	}
					 	$listaBloques->MoveNext();
					}
					//Calculamos las columnas de PERSONAS
					//HABILIDAD RELACIONAL + EMPATÍA + MODESTIA + CONSENSUADOR/A + PERSUASIÓN + ANÁLISIS DE PERSONAS + ADAPTABILIDAD FLEXIBILIDAD + [APOYO, COLABORACIÓN, CONVIVENCIA]
					//PRIMA -> [3-3] + [4-1] + [4-2] + [4-3] + [5-1] + [8-1] 
					//CML -> [42-1]
					$iPERSONAS = (($aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["3-3"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["4-1"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["4-2"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["4-3"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["5-1"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["8-1"] + $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']]["42-1"])/2.5) / 7;
					if ($iPERSONAS < 1){
						$iPERSONAS = 1;
					}
					$bufC2 = 	"<td align=center >PERSONAS</td>";
					$bufC2 .=	"<td align=center >" . number_format($iPERSONAS ,2) . "</td>";
					
					
					//Calculamos las columna APRENDIZAJE
					//ANÁLISIS DE RIESGO + PROFUNDIDAD CONCEPTUAL + PRO CAMBIOS + PRAGMATISMO + ADAPTABILIDAD FLEXIBILIDAD + [PROMOCIÓN O PROGRESO PROFESIONAL] + [FORMACIÓN Y DESARROLLO PERSONAL]
					//PRIMA ->  [6-2] + [7-1] + [8-2] + [8-3] + [8-1] + 
					//CML ->	[43-6] + [43-7]
					$iAPRENDIZAJE = (($aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["6-2"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["7-1"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["8-2"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["8-3"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["8-1"] + $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']]["43-6"] + $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']]["43-7"])/2.5) / 7;
					if ($iAPRENDIZAJE < 1){
						$iAPRENDIZAJE = 1;
					}
					$bufC3 = 	"<td align=center >APRENDIZAJE</td>";
					$bufC3 .=	"<td align=center >" . number_format($iAPRENDIZAJE ,2) . "</td>";
					
					//Calculamos las columna EFICIENCIA 
					//DINAMISMO Y ACTIVIDAD + CONSTANCIA PERSVERANCIA + AMBICIÓN DE METAS Y LOGROS + AUTODOMINIO + [NIVEL DE ACTIVIDAD] + [MOTIVACIÓN POR EL EXITO]
					//PRIMA ->	[1-1] + [1-2] + [1-3] + [2-2] + 
					//CML ->	[41-1] + [43-1]
					$iEFICIENCIA = (($aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["1-1"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["1-2"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["1-3"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["2-2"] + $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']]["41-1"] + $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']]["43-1"])/2.5) / 6;
					if ($iEFICIENCIA < 1){
						$iEFICIENCIA = 1;
					}
					$bufC4 = 	"<td align=center >EFICIENCIA</td>";
					$bufC4 .=	"<td align=center >" . number_format($iEFICIENCIA ,2) . "</td>";
					
					//Calculamos las columna  CAMBIOS
					//AUTODOMINIO + ACTITUD POSITIVA + INNOVACIÓN Y SOLUCIONES + ADAPTABILIDAD FLEXIBILIDAD + PRO CAMBIOS + [VARIEDAD Y TAREAS ESTIMULANTES]
					//PRIMA ->	[2-2] + [2-6] + [7-2] + [8-1] + [8-2]
					//CML ->	[41-6] 
					$iCAMBIOS = (($aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["2-2"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["2-6"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["7-2"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["8-1"] + $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']]["8-2"] + $aPuntuacionesCML[$vVectorCandidatos->fields['idCandidato']]["41-6"])/2.5) / 6;
					if ($iCAMBIOS < 1){
						$iCAMBIOS = 1;
					}
					$bufC5 .= 	"<td align=center >CAMBIOS</td>";
					$bufC5 .=	"<td align=center >" . number_format($iCAMBIOS ,2) . "</td>";
					
					//Media 4 dimensiones
					//$iPERSONAS + $iAPRENDIZAJE + $iEFICIENCIA + $iCAMBIOS 
 					$iMedia4D = ($iPERSONAS + $iAPRENDIZAJE + $iEFICIENCIA + $iCAMBIOS) / 4;
// 					$buf .=	"<td align=center ><strong>" . number_format($iMedia4D ,2) . "</strong></td>";
					$buf .= "<tr>" . $bufC1 . $bufC2 . "</tr>";
					$buf .= "<tr>" . $bufC1 . $bufC3 . "</tr>";
					$buf .= "<tr>" . $bufC1 . $bufC4 . "</tr>";
					$buf .= "<tr>" . $bufC1 . $bufC5 . "</tr>";
					$buf .= "<tr>" . $bufC1 . "<td align=center >MEDIA 4 DIMENSIONES</td><td align=center ><strong>" . number_format($iMedia4D ,2) . "</strong></td></tr>";
					
				 }
				break;
			case 24:	//Prisma
				$iPGlobal = 0;
				$aPuntuacionesPRISMA = array();
				$aPuntuacionesPRISMA = calculosGlobalesEscala($vVectorCandidatos->fields['idEmpresa'], $vVectorCandidatos->fields['idProceso'], $vVectorCandidatos->fields['idPrueba'], $vVectorCandidatos->fields['codIdiomaIso2'], $vVectorCandidatos->fields['idCandidato'], $aInversosPRISMA);
				$i=0;
				$cEscalas_items=  new Escalas_items();
				$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
				$cEscalas_items->setIdPrueba($vVectorCandidatos->fields['idPrueba']);
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
				$cBloques->setCodIdiomaIso2($vVectorCandidatos->fields['codIdiomaIso2']);
				$cBloques->setIdBloque($sBloques);
				$cBloques->setOrderBy("idBloque");
				$cBloques->setOrder("ASC");
				$sqlBloques = $cBloquesDB->readLista($cBloques);
				$listaBloques = $conn->Execute($sqlBloques);
				
				$iPGlobal = 0;
				$nBloques= $listaBloques->recordCount();
				
				if($nBloques>0){
					while(!$listaBloques->EOF){
						$cEscalas = new Escalas();
					 	$cEscalas->setCodIdiomaIso2($vVectorCandidatos->fields['codIdiomaIso2']);
					 	$cEscalas->setIdBloque($listaBloques->fields['idBloque']);
					 	$cEscalas->setIdBloqueHast($listaBloques->fields['idBloque']);
					 	$cEscalas->setOrderBy("idEscala");
					 	$cEscalas->setOrder("ASC");
					 	$sqlEscalas = $cEscalasDB->readLista($cEscalas);
					 	$listaEscalas = $conn->Execute($sqlEscalas);
					 	$nEscalas=$listaEscalas->recordCount();
//					 	echo $nEscalas;exit;
					 	if($nEscalas > 0){
					 		while(!$listaEscalas->EOF){
						        $iPBaremada = $aPuntuacionesPRISMA[$vVectorCandidatos->fields['idCandidato']][$listaBloques->fields['idBloque'] . "-" . $listaEscalas->fields['idEscala']];
						        $iPGlobal += ($iPBaremada - 5.5) * ($iPBaremada - 5.5);
						        $listaEscalas->MoveNext();
					 		}
					 	}
					 	$listaBloques->MoveNext();
					 }
					 $consistencia = baremo_C_PRISMA(number_format(sqrt($iPGlobal/32)*100 ,0));
				 }

			break;
				 
		} // end switch

		$sIdCandidatoANT = $vVectorCandidatos->fields['idCandidato'];
		$vVectorCandidatos->MoveNext();
	}
	$buf .= "</table>";
	$buf .= "</body>";
	$buf .= "</html>";
	

	
	function baremo_C_PRISMA($pd)
	{
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




	if (empty($nombre)){
		$nombre = "ExcelFile";
	}
	if (empty($_REQUEST['fPintaCabecera'])){
		$_REQUEST['fPintaCabecera'] = true;
	}
	if (empty($_REQUEST['fSepararCabecera'])){
		$_REQUEST['fSepararCabecera'] = false;
	}

	// Genera arquivo(xls)

	header ("Cache-Control: no-cache, must-revalidate");
	header ("Content-Description: AzulPomodoro Generador de XLS");
	header ("Content-Disposition: attachment; filename=" . $nombre . ".xls");
	header ('Content-Transfer-Encoding: binary');
	header ('Content-Type: application/force-download');
	header ('Content-Type: application/octet-stream');
	header ("Content-type: application/x-msexcel");
	header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
	header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	header ("Pragma: no-cache");
	print  ( $buf);


	function trataValores($rs, $nombre){
		$i=0;
		while (!$rs->EOF){
			setValor($rs, $nombre, $i);
			$i++;
			$rs->MoveNext();
		}
	}
	function setValor($rsLine, $nombre, $i){
		global $conn;
		global $cUtilidades;
		
		require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
		require_once(constant("DIR_WS_COM") . "Items/Items.php");
		$cItems = new Items();
		$cItems->setCodIdiomaIso2($rsLine->fields['codIdiomaIso2']);
		$cItems->setIdPrueba($rsLine->fields['idPrueba']);
		$cItems->setIdItem($rsLine->fields['idItem']);
//		$cItems->setIdOpcion($rsLine->fields['idOpcion']);
		
		$cItemsDB = new ItemsDB($conn);
		$cItems = $cItemsDB->readEntidad($cItems);
		$sValor="";
		$sValor=$cUtilidades->getValorCalculadoPRUEBAS($rsLine,$cItems, $conn);
		$sWHERE = ""; 
		$sWHERE .= " WHERE idEmpresa='" . $rsLine->fields['idEmpresa'] . "' ";
		$sWHERE .= " AND idProceso='" . $rsLine->fields['idProceso'] . "' ";
		$sWHERE .= " AND idCandidato='" . $rsLine->fields['idCandidato'] . "' ";
		$sWHERE .= " AND codIdiomaIso2='" . $rsLine->fields['codIdiomaIso2'] . "' ";
		$sWHERE .= " AND idPrueba='" . $rsLine->fields['idPrueba'] . "' ";
		$sWHERE .= " AND idItem='" . $rsLine->fields['idItem'] . "' ";
		$sWHERE .= " AND idOpcion='" . $rsLine->fields['idOpcion'] . "' ";
		
		$sSQL = " UPDATE " . $nombre . " SET valor='" . $sValor . "' " . $sWHERE;
//		echo "<br />" . $sSQL;
		$conn->Execute($sSQL);
	}
	
	
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
//		echo "<br />id::" . $valor .  " - valor::" . $inv;
		return $inv;
	}
	function calculosGlobalesEscala($_idEmpresa, $_idProceso, $_idPrueba, $_codIdiomaIso2, $_idCandidato, $aInversos)
	{
		
		global $conn;
		global $cUtilidades;
		global $cBloquesDB; 
		global $cEscalasDB;
		global $cItems;
		global $cItemsDB;
		$aPuntuaciones = array();
		
		// CÁLCULOS GLOBALES PARA ESCALAS,
		// Se hace fuera y los metemos en un array para
		// reutilizarlo en varias funciones
		$cEscalas_items=  new Escalas_items();
		$cEscalas_itemsDB=  new Escalas_itemsDB($conn);
		$cEscalas_items->setIdPrueba($_idPrueba);
		$sqlEscalas_items= $cEscalas_itemsDB->readListaGroupBloque($cEscalas_items);
//		echo "<br />sqlEscalas_items::" . $sqlEscalas_items . "";
		$rsEscalas_items = $conn->Execute($sqlEscalas_items);
		$sBloques = "";
		while(!$rsEscalas_items->EOF){
			$sBloques .="," . $rsEscalas_items->fields['idBloque'];
			$rsEscalas_items->MoveNext();
		}
		if (!empty($sBloques)){
			$sBloques = substr($sBloques,1);
		}
		$cRespuestas_pruebas_itemsBD = new Respuestas_pruebas_itemsDB($conn);
		$cBaremos_resultadoDB = new Baremos_resultadosDB($conn);
		$cBloques = new Bloques();
		$cBloques->setCodIdiomaIso2($_codIdiomaIso2);
		$cBloques->setIdBloque($sBloques);
		$cBloques->setOrderBy("idBloque");
		$cBloques->setOrder("ASC");
		$sqlBloques = $cBloquesDB->readLista($cBloques);
//		echo "<br />" . $sqlBloques;
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
				        $cEscalas_items->setIdPrueba($_idPrueba);
				        $cEscalas_items->setOrderBy("idItem");
				        $cEscalas_items->setOrder("ASC");
				        $sqlEscalas_items = $cEscalas_itemsDB->readLista($cEscalas_items);
//						echo "<br />" . $sqlEscalas_items;
				        $listaEscalas_items = $conn->Execute($sqlEscalas_items);
				        $nEscalas_items =$listaEscalas_items->recordCount();
				        
				        $iPd = 0;
				        if($nEscalas_items > 0){
				        	while(!$listaEscalas_items->EOF)
				        	{
				        		$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
				        		
				        		$cRespuestas_pruebas_items->setIdEmpresa($_idEmpresa);
								$cRespuestas_pruebas_items->setIdProceso($_idProceso);
								$cRespuestas_pruebas_items->setIdCandidato($_idCandidato);
								$cRespuestas_pruebas_items->setIdPrueba($_idPrueba);
								$cRespuestas_pruebas_items->setCodIdiomaIso2($_codIdiomaIso2);
								$cRespuestas_pruebas_items->setIdItem($listaEscalas_items->fields['idItem']);
								$sSQLRPI = $cRespuestas_pruebas_itemsBD->readLista($cRespuestas_pruebas_items);
								$vRPI = $conn->Execute($sSQLRPI);
								
								$cItems = new Items();
								$cItems->setCodIdiomaIso2($_codIdiomaIso2);
								$cItems->setIdPrueba($_idPrueba);
								$cItems->setIdItem($listaEscalas_items->fields['idItem']);
								$cItemsDB = new ItemsDB($conn);
								$cItems = $cItemsDB->readEntidad($cItems);

								$iPd += $cUtilidades->getValorCalculadoPRUEBAS($vRPI, $cItems, $conn);
										
								$listaEscalas_items->MoveNext();
				        	}
				        }
				        
				        $cBaremos_resultado = new Baremos_resultados();
				        $cBaremos_resultado->setIdBaremo(0);
				        $cBaremos_resultado->setIdPrueba($_idPrueba);
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
				       	$sDesc = $listaBloques->fields['nombre'] . "-" . $listaEscalas->fields['nombre'];
//				       	echo "<br />---------->[" . $sDesc . "][" . $iPBaremada . "]"; 
//				       	echo "<br />---------->[" . $sPosi . "][" . $iPBaremada . "]";
				       	$aPuntuaciones[$_idCandidato][$sPosi] =  $iPBaremada;
				       	
				        $listaEscalas->MoveNext();
			 		}
			 	}
			 	$listaBloques->MoveNext();
			 }
			 
		 }
		// FIN CALCULOS GLOBALES ESCALAS
		return $aPuntuaciones;
	}
	
	
?>