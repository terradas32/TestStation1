<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "ProcesoTablas/ProcesoTablas.php");
	require_once(constant("DIR_WS_COM") . "ToXLS.php");
	
include_once ('include/conexion.php');
	///////////////////////////////////////////////////////////////////////////////////////////////
	$connMssql = NewADOConnection('ado_mssql'); 
	$connMssql->charPage = 65001;	//utf8
	$dsn ='Provider=SQLNCLI;Server=' . constant("DB_HOST_MS") . ';Database=' . constant("DB_DATOS_MS") . ';Uid=' . constant("DB_USUARIO_MS") . ';Pwd=' . constant("DB_PASSWORD_MS");  
	$connMssql->Connect($dsn);
	$connMssql->SetFetchMode(constant("ADODB_FETCH_ASSOC")); 
	if (empty($connMssql)){			
        echo(constant("ERR") . " MS SQL SERVER");
		exit;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
	$cUtilidades	= new Utilidades();
	$cEntidad	= new ProcesoTablas($conn, $connMssql);
	$sqlToExcel	= new ToXLS($connMssql);  // Entidad DB
	$sPaso=0;
	
	if (!empty($_POST['fPasosNext'])){
		$sPaso = $_POST['fPasosNext'];
	}
	$_POST['fPasosNext'] = $sPaso;
	$sEmpresa = "";
	$sEmpresaOrigen = "";
	if ($_POST['fPasosNext'] == 0)
	{
		//Primera llamada desde tests-station vieja
		if (isset($_REQUEST['call']) && !empty($_REQUEST['call'])){
			$sEmpresa = base64_decode($_REQUEST['call']);
			if (!empty($sEmpresa)){
				$aEmpresa = explode("=", $sEmpresa);
				//La empresa puede consultar ???
				$vect = array("DIRHU_ADMIN","TECHINT","TERNIUM","TECPETROL","DIRHU","TECHINT_CHI","TECHINT_PER","TECHINT_COL","TECHINT_MEX","TERNIUM_COL","TERNIUM_MEX","TECPETROL_PER","TECPETROL_COL","TECPETROL_ECU","TECPETROL_MEX","000072");
                //$vect = array("msilva","otra1","otra2","otra...");
				if (in_array($aEmpresa[1], $vect)) {
    				$_REQUEST['LSTIdEmpresaOrigen'] = $aEmpresa[1];
    				$sEmpresaOrigen=$aEmpresa[1];
				}else{
					echo("00003 - " . constant("ERR_NO_AUTORIZADO"));
					exit;
				}
			}else{
				echo("00001 - " . constant("ERR_NO_AUTORIZADO"));
				exit;
			}
		}else{
			echo("00000 - " . constant("ERR_NO_AUTORIZADO"));
			exit;
		}
	}else{
		$sEmpresaOrigen =(!empty($_POST['LSTIdEmpresaOrigen'])) ? $_POST['LSTIdEmpresaOrigen'] : "-1";
	}
	
	$sWhereComboEMPRESAS="";
	if (!empty($sEmpresaOrigen)){
		//Miro para ese usuario q ID Tiene
		$sSQL="SELECT * FROM empresas WHERE UPPER(usuario)=" . $conn->qstr(strtoupper($sEmpresaOrigen), false);
//		echo $sSQL . "<br />--";
		$rs = $conn->Execute($sSQL);
		$sIdEmpresaOrigen ="";
		while (!$rs->EOF){
			$sIdEmpresaOrigen = $rs->fields['idEmpresa'];
			$rs->MoveNext();
		}		  
		//cogemos todo slos hijos del padre logado
		require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
		require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
		$sHijos = $cEmpresaPadreDB->getHijos($sIdEmpresaOrigen);
		if (!empty($sHijos)){
			$sHijos .= $sIdEmpresaOrigen;
		}else{
			$sHijos = $sIdEmpresaOrigen;
		}
//		echo $sIdEmpresaOrigen . "<br />**";
		$sWhereComboEMPRESAS = " idEmpresa IN (" . $sHijos . ") ";
//		echo $sWhereComboEMPRESAS;
	}
	$sWhereComboPROCESOS="";
	if (!empty($_REQUEST['LSTIdEmpresa'])){
		$sWhereComboPROCESOS = " empresa='" . $_REQUEST['LSTIdProceso'] . "'";
	}


	$comboEMPRESAS	= new Combo($conn,"LSTIdEmpresa","usuario",$conn->Concat("usuario", "' - '", "nombre"),"Descripcion","empresas","",constant("SLC_OPCION"),$sWhereComboEMPRESAS,"","orden");
	$comboPROCESOS	= new Combo($connMssql,"LSTIdProceso","id","nombre","Descripcion","procesos","",constant("SLC_OPCION"),$sWhereComboPROCESOS,"","");
	
	$sMensaje = "";
	$sDesde=(!empty($_POST['LSTFecDesde'])) ? $_POST['LSTFecDesde'] : "";
	$sHasta=(!empty($_POST['LSTFecHasta'])) ? $_POST['LSTFecHasta'] : "";
	$sEmpresa=(!empty($_REQUEST['LSTIdEmpresa'])) ? $_REQUEST['LSTIdEmpresa'] : "";
	$sProceso=(!empty($_POST['LSTIdProceso'])) ? $_POST['LSTIdProceso'] : "";
	$iConsumos=0;
	$newId=0;
	$bPrepago=false;
	$bExportar=false;
//	echo $sPaso;
	switch ($sPaso)
	{
		case 1:
			$sql="  SELECT * FROM usuarios ";
			$sql.=" WHERE ";
			$sql.=" (empresa = '" . $sEmpresa . "')";
//			$sql.=" AND (finalizado = 1)";
			$sql.=" AND (generadoPDF IS NOT NULL)";
			if (!empty($sDesde)){
				$sql.=" AND (fecha_alta >= '" . $sDesde . " 00:00:00') ";
			}
			if (!empty($sHasta)){
				$sql.=" AND (fecha_alta <= '" . $sHasta . " 23:59:59') ";
			}
			if (!empty($sProceso)){
				$sql.=" AND (proceso = '" . $sProceso . "')";
			}
			$sql.=" AND (pass LIKE 'o-%' OR pass LIKE 'y-%' OR pass LIKE 'h-%')";	// NIPS o VIPS o ELT
//			echo $sql;exit; 
			$MsListaCANDIDATOS =& $connMssql->Execute($sql);
			$sql = "SELECT MAX(id) AS Max FROM export_especial ";
			$RsID = $conn->Execute($sql);

			if ($RsID){
				while ($arr = $RsID->FetchRow()){
					$newId = $arr['Max'] + 1;
				}
			}else{
				echo("MAX::" . constant("ERR"));
				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [newID][EdadesDB]";
				error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				exit;
			}
			$sSQL ="";
			$aRespuestasNIPS	= array("","");
			$aRespuestasVIPS	= array("","");
			$aRespuestasELT		= array("","");
			$iConsumos=0;
			$i=0;
			while (!$MsListaCANDIDATOS->EOF)
			{
				$aRespuestasNIPS	= array("","");
				$aRespuestasVIPS	= array("","");
				$aRespuestasELT		= array("","");
				$iPercentilNIPS		= "";
				$iPercentilVIPS		= "";
				if ($i ==0){
					$sSQL ="INSERT INTO `export_especial` (`id`, `empresa`, `pass`, `empleado`, `apellido1`, `apellido2`, `email`, `fecPrueba`, `correctas_nips`, `contestadas_nips`, `percentil_nips`, `correctas_vips`, `contestadas_vips`, `percentil_vips`, `puntuacion_elt`) VALUES ";
				}
				//miramos el inicio de la contraseña
				//aRespuestas (puntuacion == Correctas,ultima_pregunta==contestadas)
				if (substr($MsListaCANDIDATOS->fields['pass'],0,2) == "o-"){
					$aRespuestasNIPS	= getCorrectas($connMssql, "nips", $MsListaCANDIDATOS->fields['pass']);
					$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
					$iPercentilNIPS = getPercentilNIPS($aRespuestasNIPS[0]);
				}
				if (substr($MsListaCANDIDATOS->fields['pass'],0,2) == "y-"){
					$aRespuestasVIPS	= getCorrectas($connMssql, "vips", $MsListaCANDIDATOS->fields['pass']);
					$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
					$iPercentilVIPS = getPercentilVIPS($aRespuestasVIPS[0]);
				}
				if (substr($MsListaCANDIDATOS->fields['pass'],0,2) == "h-"){
					$aRespuestasELT		= getPuntuacionELT($connMssql, "elt", $MsListaCANDIDATOS->fields['pass']);
					$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
				}
				$sSQL .="(" . $newId . ", '" . $MsListaCANDIDATOS->fields['empresa'] . "', '" . $MsListaCANDIDATOS->fields['pass'] . "', '" . addslashes($MsListaCANDIDATOS->fields['nombre']) . "', '" . addslashes($MsListaCANDIDATOS->fields['apellido1']) . "', '" . addslashes($MsListaCANDIDATOS->fields['apellido2']) . "', '" . $MsListaCANDIDATOS->fields['mail'] . "', '" . $MsListaCANDIDATOS->fields['generadoPDF'] . "', '" . $aRespuestasNIPS[0] . "', '" . $aRespuestasNIPS[1] . "', '" . $iPercentilNIPS . "', '" . $aRespuestasVIPS[0] . "', '" . $aRespuestasVIPS[1] . "', '" . $iPercentilVIPS . "', '" . $aRespuestasELT[0] . "'),";
				$MsListaCANDIDATOS->MoveNext();
				$i++;
			}

			//Miramos los que se han generado con 
			//carga masiva con lo que las contraseñas no están bien "oy-"
			$sql="  SELECT * FROM usuarios ";
			$sql.=" WHERE ";
			$sql.=" (empresa = '" . $sEmpresa . "')";
			$sql.=" AND (finalizado = 1)";
			if (!empty($sDesde)){
				$sql.=" AND (fecha_alta >= '" . $sDesde . " 00:00:00') ";
			}
			if (!empty($sHasta)){
				$sql.=" AND (fecha_alta <= '" . $sHasta . " 23:59:59') ";
			}
			if (!empty($sProceso)){
				$sql.=" AND (proceso = '" . $sProceso . "')";
			}
			$sql.=" AND (pass LIKE 'oy-%')";	// NIPS , VIPS
			$MsListaCANDIDATOS =& $connMssql->Execute($sql);
			
			while (!$MsListaCANDIDATOS->EOF)
			{
				$aRespuestasNIPS	= array("","");
				$aRespuestasVIPS	= array("","");
				$aRespuestasELT		= array("","");
				$iPercentilNIPS		= "";
				$iPercentilVIPS		= "";

				//son todo nips y vips
				//aRespuestas (puntuacion == Correctas,ultima_pregunta==contestadas)
				$aRespuestasNIPS	= getCorrectas($connMssql, "nips", $MsListaCANDIDATOS->fields['pass']);
				$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
				$iPercentilNIPS = getPercentilNIPS($aRespuestasNIPS[0]);
				
				$aRespuestasVIPS	= getCorrectas($connMssql, "vips", $MsListaCANDIDATOS->fields['pass']);
				$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo
				$iPercentilVIPS = getPercentilVIPS($aRespuestasVIPS[0]);
				
				$sSQL .="(" . $newId . ", '" . $MsListaCANDIDATOS->fields['empresa'] . "', '" . $MsListaCANDIDATOS->fields['pass'] . "', '" . $MsListaCANDIDATOS->fields['nombre'] . "', '" . $MsListaCANDIDATOS->fields['apellido1'] . "', '" . $MsListaCANDIDATOS->fields['apellido2'] . "', '" . $MsListaCANDIDATOS->fields['mail'] . "', '" . $MsListaCANDIDATOS->fields['generadoPDF'] . "', '" . $aRespuestasNIPS[0] . "', '" . $aRespuestasNIPS[1] . "', '" . $iPercentilNIPS . "', '" . $aRespuestasVIPS[0] . "', '" . $aRespuestasVIPS[1] . "', '" . $iPercentilVIPS . "', '" . $aRespuestasELT[0] . "'),";
				$MsListaCANDIDATOS->MoveNext();
				$i++;
			}

			if (!empty($sSQL)){
				$sSQL = substr($sSQL, 0, strlen($sSQL)-1) . ";";
//				echo $sSQL;exit;
				if($conn->Execute($sSQL) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Expecial_export]";
					error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo("INS::" . constant("ERR"));
					exit;
				}else{
					//Miramos los dongles de la Empresa
					$sql="SELECT * FROM empresas WHERE usuario='" . $sEmpresa . "'";
					$MsEmpresa =& $connMssql->Execute($sql);
					$bEncontrada=false;
					$iDonglesEmpresa=0;
					while (!$MsEmpresa->EOF)
					{
						$bEncontrada=true;
						$bPrepago = $MsEmpresa->fields['prepago'];
						$iDonglesEmpresa = (!empty($MsEmpresa->fields['dongles'])) ? $MsEmpresa->fields['dongles'] : "0";
						$MsEmpresa->MoveNext();
					}
					if ($bEncontrada){
						if ($bPrepago){
							$iRresto = $iDonglesEmpresa - $iConsumos; 
							if ($iRresto >= 0){
								$bExportar=true;

								$sMensaje = "<br />" . $i . " " . constant("STR_REGISTROS_SE_EXPORTARAN") . ".";
								$sMensaje .= "<br />" . constant("STR_ESTA_OPERACION_PUEDE_DURAR_VARIOS_MINUTOS") . ".";
								$sMensaje .= "<br /><br />" . constant("STR_PULSE_SOBRE_EL_ICONO_EXCEL_PARA_DESCARGAR_LAS_PUNTUACIONES") . ".";
								
								
							}else{
								
								$bExportar=true;
								$sMensaje = "<br />" . $i . " " . constant("STR_REGISTROS_SE_EXPORTARAN") . ".";
								$sMensaje .= "<br />" . constant("STR_ESTA_OPERACION_PUEDE_DURAR_VARIOS_MINUTOS") . ".";
								$sMensaje .= "<br /><br />" . constant("STR_PULSE_SOBRE_EL_ICONO_EXCEL_PARA_DESCARGAR_LAS_PUNTUACIONES") . ".";
							}
						}else{
							$bExportar=true;

							$sMensaje = "<br />" . $i . " " . constant("STR_REGISTROS_SE_EXPORTARAN") . ".";
							$sMensaje .= "<br />" . constant("STR_ESTA_OPERACION_PUEDE_DURAR_VARIOS_MINUTOS") . ".";
							$sMensaje .= "<br /><br />" . constant("STR_PULSE_SOBRE_EL_ICONO_EXCEL_PARA_DESCARGAR_LAS_PUNTUACIONES") . ".";
									
						}
						include('Template/Expecial_Export/mntexpecial_export.php');
					}else{
						$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Expecial_export]";
						error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						echo("NOTFOUND::" . constant("ERR"));
						exit;
					}
				}
			}else{
				$sMensaje = constant("STR_NO_HAY_RESULTADOS") . ".";
				include('Template/Expecial_Export/mntexpecial_export.php');
			}
			break;
		default:
			include('Template/Expecial_Export/mntexpecial_export.php');
			break;
	} // end switch
	
	function getCorrectas($connMssql, $sPrueba, $sCandidato){
		require_once(constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();
		
		$puntuacion=0;
		$ultima_pregunta=0;
		if ($sPrueba == "nips"){
			//Llamamos a la generación del informe pdf
			$cUtilidades->verifica_url("http://www.test-station.com/nips/generapdf_ni.asp?usr=" . $sCandidato);
			
			$sSQL="SELECT r.respuesta, ns.correcta, ns.item ";
			$sSQL.="FROM nips_resp r LEFT JOIN nips_soluciones ns ON r.pregunta=ns.item ";
			$sSQL.="WHERE r.usuario='" . $sCandidato . "' ORDER BY r.pregunta ASC";
			
			$rs =& $connMssql->Execute($sSQL);
			$i=0;
			$puntuacion=0;
			//Esta query sólo saca los registros en los que Sí se ha contestado algo,
			// x lo q solo los cuento
			$ultima_pregunta = 0;
			while (!$rs->EOF)
			{
				if ($rs->fields['respuesta'] == $rs->fields['correcta']){
		            $puntuacion=$puntuacion+1;
				}
		        if (!empty($rs->fields['respuesta'])){
		            $ultima_pregunta = $ultima_pregunta+1;	//$rs->fields['item'];
		        }
				$rs->MoveNext();
				$i++;
			}
		}
		if ($sPrueba == "vips"){
			//Llamamos a la generación del informe pdf
			$cUtilidades->verifica_url("http://www.test-station.com/vips/generapdf_ni.asp?usr=" . $sCandidato);

//			$sSQL="SELECT r.respuesta, vs.correcta, vs.item ";
//			$sSQL.="FROM vips_soluciones AS vs LEFT OUTER JOIN vips_resp AS r ON r.pregunta = vs.item ";
//			$sSQL.="AND r.usuario = '" . $sCandidato . "' ORDER BY vs.item";

			$sSQL="SELECT r.respuesta, vs.correcta, vs.item ";
			$sSQL.="FROM vips_resp r LEFT JOIN vips_soluciones vs ON r.pregunta=vs.item ";
			$sSQL.="WHERE r.usuario='" . $sCandidato . "' ORDER BY r.pregunta ASC";
			
			$rs =& $connMssql->Execute($sSQL);
			$i=0;
			$puntuacion=0;
			//Esta query sólo saca los registros en los que Sí se ha contestado algo,
			// x lo q solo los cuento
			$ultima_pregunta = 0;
			while (!$rs->EOF)
			{
				if ($rs->fields['respuesta'] == $rs->fields['correcta']){
		            $puntuacion=$puntuacion+1;
				}
		        if (!empty($rs->fields['respuesta'])){
		            $ultima_pregunta = $ultima_pregunta+1;	//$rs->fields['item'];
		        }
				$rs->MoveNext();
				$i++;
			}
		}
		return array($puntuacion,$ultima_pregunta);
	}
	function getPuntuacionELT($connMssql, $sPrueba, $sCandidato){
		$puntuacion=0;
		$ultima_pregunta=0;
		if ($sPrueba == "elt"){
			$sSQL="SELECT r.respuesta, ns.correcta, ns.item, ns.tipo_item ";
			$sSQL.="FROM ELT_First_soluciones AS ns LEFT OUTER JOIN ELT_First_resp AS r ON r.pregunta = ns.item ";
			$sSQL.="AND r.usuario = '" . $sCandidato . "' ORDER BY ns.item ASC";
			
			$rs =& $connMssql->Execute($sSQL);
			$i=0;
			$puntuacion=0;
			$ultima_pregunta = 0;
			while (!$rs->EOF)
			{
				if ($rs->fields['respuesta'] == $rs->fields['correcta']){
		            $puntuacion=$puntuacion+1;
				}
		        if (!empty($rs->fields['respuesta'])){
		            $ultima_pregunta = $rs->fields['item'];
		        }
				$rs->MoveNext();
				$i++;
			}
		}
		return array($puntuacion,$ultima_pregunta);
	}
	
	function getPercentilNIPS($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
		if ($pd==0) $Baremo_Estandar=1;
		if ($pd==1) $Baremo_Estandar=1;
		if ($pd==2) $Baremo_Estandar=2;
		if ($pd==3) $Baremo_Estandar=5;
		if ($pd==4) $Baremo_Estandar=12;
		if ($pd==5) $Baremo_Estandar=21;
		if ($pd==6) $Baremo_Estandar=34;
		if ($pd==7) $Baremo_Estandar=50;
		if ($pd==8) $Baremo_Estandar=66;
		if ($pd==9) $Baremo_Estandar=79;
		if ($pd==10) $Baremo_Estandar=88;
		if ($pd==11) $Baremo_Estandar=95;
		if ($pd==12) $Baremo_Estandar=98;
		if ($pd==13) $Baremo_Estandar=99;
		if ($pd==14) $Baremo_Estandar=99;
		if ($pd==15) $Baremo_Estandar=99;
		return $Baremo_Estandar;
	}
	
	function getPercentilVIPS($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
	    if ($pd >= 0 && $pd < 12) $Baremo_Estandar=1;
	    if ($pd==12) $Baremo_Estandar=2;
	    if ($pd==13) $Baremo_Estandar=3;
	    if ($pd==14) $Baremo_Estandar=5;
	    if ($pd==15) $Baremo_Estandar=8;
	    if ($pd==16) $Baremo_Estandar=12;
	    if ($pd==17) $Baremo_Estandar=16;
	    if ($pd==18) $Baremo_Estandar=24;
	    if ($pd==19) $Baremo_Estandar=31;
	    if ($pd==20) $Baremo_Estandar=38;
	    if ($pd==21) $Baremo_Estandar=46;
	    if ($pd==22) $Baremo_Estandar=54;
	    if ($pd==23) $Baremo_Estandar=66;
	    if ($pd==24) $Baremo_Estandar=73;
	    if ($pd==25) $Baremo_Estandar=79;
	    if ($pd==26) $Baremo_Estandar=84;
	    if ($pd==27) $Baremo_Estandar=88;
	    if ($pd==28) $Baremo_Estandar=93;
	    if ($pd==29) $Baremo_Estandar=96;
	    if ($pd==30) $Baremo_Estandar=97;
	    if ($pd==31) $Baremo_Estandar=98;
	    if ($pd>=32 && $pd<=39) $Baremo_Estandar=99;

	    return $Baremo_Estandar;
	}


?>