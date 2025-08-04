<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
		@set_time_limit(0);
		ini_set("memory_limit","2048M");

	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "ExportAptitudinalesB/ExportAptitudinalesBDB.php");
	require_once(constant("DIR_WS_COM") . "ExportAptitudinalesB/ExportAptitudinalesB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new ExportAptitudinalesBDB($conn);  // Entidad DB
	$cEntidad	= new ExportAptitudinalesB();  // Entidad
	$cBaremos_resultadosDB = new Baremos_resultadosDB($conn);
	$_cEntidadUsuarioTK = getUsuarioToken($conn);

	$sPaso=0;

	if (isset($_POST['fPasosNext'])){
		$sPaso = $_POST['fPasosNext'];
	}
	if ($_POST['MODO'] == 0){
		$sPaso=0;
		$_POST['MODO']    = constant("MNT_LISTAR");
	}

	$_POST['fPasosNext'] = $sPaso;
//	echo $sPaso;exit;
	$sCol1='';
	$sCol2='';
	$sHijos = "";

	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	//$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
		$_EmpresaLogada = constant("EMPRESA_PE");
	if (empty($_POST["fHijos"]))
	{
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
	$sSQLPruebaIN = "";
	if (!empty($_POST["fIdEmpresa"])){
		$cEmpresaPadre->setIdEmpresa($_POST["fIdEmpresa"]);
		$cEmpresaPadre = $cEmpresaPadreDB->readEntidad($cEmpresaPadre);
		$sSQLPruebaIN = $cEmpresaPadre->getIdsPruebas();
		if (!empty($sSQLPruebaIN)){
			//chequeamos si el primer caracter es una coma
			if (substr($sSQLPruebaIN, 0, 1) == ","){
				$sSQLPruebaIN = substr($sSQLPruebaIN, 1);
			}
			$sSQLPruebaIN = " idPrueba IN (" . $sSQLPruebaIN . ") ";
		}
	}
	//$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"listar=1 AND bajaLog=0 AND idTipoPrueba IN (2,5)","","nombre");
    $comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba",$conn->Concat("idPrueba", "' - '", "nombre"),"Descripcion","pruebas","",constant("SLC_OPCION"),"bajaLog=0 AND idTipoPrueba IN (2,5) AND (listar=1 OR (`nombre` like '%ips%' AND listar=0 AND codIdiomaIso2='es'  ))","","nombre");
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa",$conn->Concat("idEmpresa", "' - '", "nombre"),"Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboBAREMOS	= new Combo($conn,"fIdBaremo","idBaremo","nombre","Descripcion","baremos","",constant("SLC_OPCION"),"","","fecMod");
	//$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","","","bajaLog=0 AND listar=1","","idPrueba","");
    $comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba",$conn->Concat("idPrueba", "' - '", "nombre"),"Descripcion","pruebas","","","bajaLog=0 AND idTipoPrueba IN (2,5) AND (listar=1 OR (`nombre` like '%ips%' AND listar=0 AND codIdiomaIso2='es'  ))","","idPrueba", "");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr('es', false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr('es', false),"","idEdad","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr('es', false),"","","");
	$comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr('es', false),"","","");
    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr('es', false),"","","");


	//echo('modo:' . $_POST['MODO']);

	if ($_POST['fPasosNext'] == 0)
	{
		if (!empty($_EmpresaLogada)){
			$_REQUEST['LSTIdEmpresaOrigen'] = $_EmpresaLogada;
   			$sEmpresaOrigen=$_EmpresaLogada;
		}else{
			echo("00001 - " . constant("ERR_NO_AUTORIZADO"));
			exit;
		}
	}else{
		$sEmpresaOrigen =(!empty($_POST['LSTIdEmpresaOrigen'])) ? $_POST['LSTIdEmpresaOrigen'] : "-1";
	}


	$sMensaje = "";
	$sDesde=(!empty($_POST['LSTFecDesde'])) ? $_POST['LSTFecDesde'] : "";
	$sHasta=(!empty($_POST['LSTFecHasta'])) ? $_POST['LSTFecHasta'] : "";
	$sEmpresa=(!empty($_POST['LSTIdEmpresa'])) ? $_POST['LSTIdEmpresa'] : "";
	$sProceso=(!empty($_POST['LSTIdProceso'])) ? $_POST['LSTIdProceso'] : "";
	$iConsumos=0;
	$newId=0;
	$bPrepago=false;
	$bExportar=true;
//	echo $sPaso;
//	echo('modo:' . $_POST['MODO']);
	$sql="";
	if (!empty($_POST['fSQL'])){
		$sql = base64_decode($_POST['fSQL']);
	}

	switch ($sPaso)
	{
		case 1:
			//Sacamos todos los procesos que tengan dado de alta alguna de estas pruebas
			$sql="  SELECT rp.*, c.nombre, c.apellido1, c.apellido2, c.dni, c.mail, c.password, c.idTratamiento, c.idSexo, c.idEdad, c.fechaNacimiento, c.idPais, c.idProvincia, c.idMunicipio, c.idZona, c.direccion, c.codPostal, c.idFormacion, c.idNivel, c.idArea, c.telefono, c.estadoCivil, c.nacionalidad, c.informado, c.fechaFinalizado, c.ultimoLogin, c.token, c.ultimaAcc FROM respuestas_pruebas rp, candidatos c";
			$sql.=" WHERE ";
			$sql.=" rp.idPrueba = " . $_POST['LSTIdPrueba'] . "";
			if ($_POST['LSTFinalizado'] != ""){
				$sql.=" AND rp.finalizado = " . $_POST['LSTFinalizado'] . " ";
			}
			$sql.=" AND rp.idEmpresa = c.idEmpresa ";
			$sql.=" AND rp.idProceso = c.idProceso ";
			$sql.=" AND rp.idCandidato = c.idCandidato ";
			if (!empty($sEmpresa)){
				$sql.=" AND rp.idEmpresa = '" . $sEmpresa . "'";
			}
			if (!empty($sDesde)){
				$sql.=" AND rp.fecAlta >= '" . $sDesde . " 00:00:00' ";
			}
			if (!empty($sHasta)){
				$sql.=" AND rp.fecAlta <= '" . $sHasta . " 23:59:59' ";
			}
			if (!empty($sProceso)){
				$sql.=" AND rp.idProceso = '" . $sProceso . "'";
			}
			if (!empty($_POST['LSTIdSexo'])){
				$sql.=" AND c.idSexo = '" . $_POST['LSTIdSexo'] . "'";
			}
			if (!empty($_POST['LSTIdEdad'])){
				$sql.=" AND c.idEdad = '" . $_POST['LSTIdEdad'] . "'";
			}
			if (!empty($_POST['LSTIdFormacion'])){
				$sql.=" AND c.idFormacion = '" . $_POST['LSTIdFormacion'] . "'";
			}
			if (!empty($_POST['LSTIdNivel'])){
				$sql.=" AND c.idNivel = '" . $_POST['LSTIdNivel'] . "'";
			}
			if (!empty($_POST['LSTIdArea'])){
				$sql.=" AND c.idArea = '" . $_POST['LSTIdArea'] . "'";
			}

			$sql.=" ORDER BY c.idCandidato, rp.idPrueba ";
			//echo $sql;exit;
			$rsRESPUESTAS_PRUEBAS = $conn->Execute($sql);

			if (!empty($_POST['fSQL'])){
				$_POST['fSQL'] = base64_encode($_POST['fSQL']);
			}
			if ($rsRESPUESTAS_PRUEBAS->RecordCount() > 0 ){
				$sMensaje = "<br />Se exportaran " . $rsRESPUESTAS_PRUEBAS->RecordCount() . " Registros.";
				$sMensaje .= "<br />Esta operación puede durar varios minutos.";
				$sMensaje .= "<br /><br />Espere un momento ....";
				$sMensaje .= '<input type="hidden" name="fSQL" value="' . base64_encode($sql) . '" />';

				$sMensaje .= '<script language="javascript" type="text/javascript">setTimeout(function(){enviar(); }, 5000);</script>';
			}else {
				$sMensaje = "No hay resultados.";
			}
			include('Template/ExportAptitudinalesB/mntexportAptitudinalesB.php');

			break;
		case 2:
			@set_time_limit(0);
			ini_set("memory_limit","2048M");
//		ini_set("max_execution_time","600");
			$rsRESPUESTAS_PRUEBAS = $conn->Execute($sql);

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
			$aRespuestas	= array("","","");

			$iConsumos=0;
			$i=0;
			require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
			require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
			$cProcesosDB = new ProcesosDB($conn);

			require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
			require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
			$cCandidatosDB = new CandidatosDB($conn);
			$fecAltaProceso = "";
			while (!$rsRESPUESTAS_PRUEBAS->EOF)
			{
				$aRespuestas	= array("","","");

				$iPercentil		= "";

				if ($i ==0){
					$cProcesos = new Procesos();
					$cProcesos->setIdEmpresa($rsRESPUESTAS_PRUEBAS->fields['idEmpresa']);
					$cProcesos->setIdProceso($rsRESPUESTAS_PRUEBAS->fields['idProceso']);
					$cProcesos = $cProcesosDB->readEntidad($cProcesos);
					$fecAltaProceso = $cProcesos->getFecAlta();

					$sSQL ="INSERT INTO `export_especial` (`id`,`empresa`,`pass`,`descEmpresa`,`descProceso`,`empleado`,`apellido1`,`apellido2`,`email`,`fecPrueba`,`fecAltaProceso`";
					$sSQL .=",`prueba`,`correctas`,`contestadas`,`percentil`";
					$sSQL .=",`ir`,`ip`,`por`";
					$sSQL .=") VALUES ";
				}

				$aRespuestas	= getCorrectas($conn, $rsRESPUESTAS_PRUEBAS->fields['idPrueba'], $rsRESPUESTAS_PRUEBAS);
				//echo "<br />----------------------------------------------------<br />";
				//print_r($aRespuestas);
				//echo "<br />----------------------------------------------------<br />";
				$iConsumos = $iConsumos + 5;	// 5 es la tarifa por el infome en TS viejo

				$cCandidatos = new Candidatos();
				$cCandidatos->setIdEmpresa($rsRESPUESTAS_PRUEBAS->fields['idEmpresa']);
				$cCandidatos->setIdProceso($rsRESPUESTAS_PRUEBAS->fields['idProceso']);
				$cCandidatos->setIdCandidato($rsRESPUESTAS_PRUEBAS->fields['idCandidato']);
				$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
				$sSQL .="(" . $newId . ",'" . $rsRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "','" . $rsRESPUESTAS_PRUEBAS->fields['idEmpresa'] . $rsRESPUESTAS_PRUEBAS->fields['idProceso'] . $rsRESPUESTAS_PRUEBAS->fields['idCandidato'] . $rsRESPUESTAS_PRUEBAS->fields['idPrueba'] . $rsRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . $rsRESPUESTAS_PRUEBAS->fields['password'] . "','" . addslashes($rsRESPUESTAS_PRUEBAS->fields['descEmpresa']) . "','" . addslashes($rsRESPUESTAS_PRUEBAS->fields['descProceso']) . "','" . addslashes($cCandidatos->getNombre()) . "','" . addslashes($cCandidatos->getApellido1()) . "','" . addslashes($cCandidatos->getApellido2()) . "','" . $cCandidatos->getMail() . "','" . $rsRESPUESTAS_PRUEBAS->fields['fecAlta'] . "','" . $fecAltaProceso . "'";
				$sSQL .=",'" . $comboPRUEBASGROUP->getDescripcionCombo($rsRESPUESTAS_PRUEBAS->fields['idPrueba']) . "','" . $aRespuestas[0] . "','" . $aRespuestas[1] . "','" . $aRespuestas[2] . "'";
				$sSQL .=",'" . $aRespuestas[3] . "','" . $aRespuestas[4] . "','" . $aRespuestas[5] . "'";
				$sSQL .="),";
				$rsRESPUESTAS_PRUEBAS->MoveNext();
				$i++;
			}

			if (!empty($sSQL)){
				$sSQL = substr($sSQL, 0, strlen($sSQL)-1) . ";";
				if($conn->Execute($sSQL) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][Expecial_export]";
					error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					echo("INS::" . constant("ERR"));
					exit;
				}else{
					$sMensaje = "<br />Se exportaran " . $i . " Registros.";
					$sMensaje .= "<br />Esta operación puede durar varios minutos.";
					$sMensaje .= "<br /><br />Pulse sobre el icono Excel para descargar las puntuaciones.";
					include('Template/ExportAptitudinalesB/mntexportAptitudinalesB.php');
				}
			}else{
				$sMensaje = "No hay resultados.";
				include('Template/ExportAptitudinalesB/mntexportAptitudinalesB.php');
			}

			break;
		default:
			include('Template/ExportAptitudinalesB/mntexportAptitudinalesB.php');
			break;
	} // end switch


	function getCorrectas($conn, $sPrueba, $rowRESPUESTAS_PRUEBAS){
		require_once(constant("DIR_WS_COM") . "Utilidades.php");
		$cUtilidades	= new Utilidades();

       	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
    	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
        $cProceso_informesDB = new Proceso_informesDB($conn);	// Entidad DB
        $cProceso_informes =  new Proceso_informes();

		$cProceso_informes->setIdEmpresa($rowRESPUESTAS_PRUEBAS->fields['idEmpresa']);
		$cProceso_informes->setIdProceso($rowRESPUESTAS_PRUEBAS->fields['idProceso']);
		$cProceso_informes->setIdPrueba($rowRESPUESTAS_PRUEBAS->fields['idPrueba']);
		$cProceso_informes->setCodIdiomaIso2($rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2']);
		$sSQLProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);
		$rsProceso_informes = $conn->Execute($sSQLProceso_informes);
		$cProceso_informes->setCodIdiomaInforme($rsProceso_informes->fields['codIdiomaInforme']);
		$cProceso_informes->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
		$cProceso_informes->setIdBaremo($rsProceso_informes->fields['idBaremo']);
		$cProceso_informes = $cProceso_informesDB->readEntidad($cProceso_informes);

		$sSQL="SELECT * ";
		$sSQL.="FROM respuestas_pruebas_items ";
		$sSQL.="WHERE idEmpresa='" . $rowRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "' ";
		$sSQL.="AND idProceso='" . $rowRESPUESTAS_PRUEBAS->fields['idProceso'] . "' ";
		$sSQL.="AND idCandidato='" . $rowRESPUESTAS_PRUEBAS->fields['idCandidato'] . "' ";
		$sSQL.="AND idPrueba='" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . "' ";
		$sSQL.="AND codIdiomaIso2='" . $rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . "' ";
		$sSQL.="ORDER BY idProceso,idCandidato,idPrueba,idItem ASC";

//		echo "<br />" . $sSQL;

		$vVector = $conn->Execute($sSQL);
		trataValores($vVector, "respuestas_pruebas_items");

		$puntuacion=0;
		$ultima_pregunta=0;

		$sSQL="SELECT SUM(valor) AS valor, COUNT(*) AS respuestas ";
		$sSQL.="FROM respuestas_pruebas_items ";
		$sSQL.="WHERE idEmpresa='" . $rowRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "' ";
		$sSQL.="AND idProceso='" . $rowRESPUESTAS_PRUEBAS->fields['idProceso'] . "' ";
		$sSQL.="AND idCandidato='" . $rowRESPUESTAS_PRUEBAS->fields['idCandidato'] . "' ";
		$sSQL.="AND idPrueba='" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . "' ";
		$sSQL.="AND codIdiomaIso2='" . $rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2'] . "' ";

//		echo "<br />" . $sSQL;exit;

		$rs = $conn->Execute($sSQL);
		$i=0;
		$puntuacion=0;
		//$ultima_pregunta = 0;
		while (!$rs->EOF)
		{
			$puntuacion = $rs->fields['valor'];
			//$ultima_pregunta = $rs->fields['respuestas'];
				$rs->MoveNext();
			$i++;
		}
		$ultima_pregunta = 0;
		$vVector->MoveFirst();
//		echo "<br />::" . $vVector->recordCount();
		if ($vVector->recordCount() > 0){
			$ultima_pregunta = $vVector->MoveLast();
			$ultima_pregunta = $vVector->fields['idItem'];
		}
//		echo "<br />::->" . $ultima_pregunta;
		//Si hemos seleccionado un baremo, se calcula el percentil con ese baremo,
		//En caso contrario, con el baremo seleccionado en la prueba.
		$_IdBaremo=(!empty($_POST['LSTIdBaremo'])) ? $_POST['LSTIdBaremo'] : $cProceso_informes->getIdBaremo();
		//echo $_IdBaremo;exit;
		$iPercentil = getPercentil($puntuacion, $_IdBaremo, $rowRESPUESTAS_PRUEBAS->fields['idPrueba']);

			$IR = 0.00;
			$IP = 0.00;

			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
			require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");

			$cIt = new Items();
			$cItemsDB = new ItemsDB($conn);
			$cIt->setIdPrueba($rowRESPUESTAS_PRUEBAS->fields['idPrueba']);
			$cIt->setIdPruebaHast($rowRESPUESTAS_PRUEBAS->fields['idPrueba']);
			$cIt->setCodIdiomaIso2($rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2']);
			$sqlItemsPrueba= $cItemsDB->readLista($cIt);
//			echo "<br />" . $sqlItemsPrueba;
			$listaItemsPrueba = $conn->Execute($sqlItemsPrueba);
//			echo "<br />" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . " - " . $listaItemsPrueba->recordCount();
			//IR= Último ítem respondido por el candidato/Nº total de ítems de la prueba.
			if ($listaItemsPrueba->recordCount() > 0){
				$IR = number_format($ultima_pregunta / $listaItemsPrueba->recordCount(),2);
			}
			$sIR = str_replace("." , "," , $IR);
			//IP= Aciertos/Último ítem respondido por el candidato
			if ($ultima_pregunta > 0){
				$IP = number_format($puntuacion / $ultima_pregunta ,2);
			}
			$sIP = str_replace("." , "," , $IP);
			$POR = number_format($IR*$IP ,2);
			$sPOR = str_replace("." , "," , number_format($POR ,2));

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_textos/Rangos_textos.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_irDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ir/Rangos_ir.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");

		//Miramos lo que tenemos que pintar de los textos segun los rangos.
		$cRangos_textos = new Rangos_textos();
		$cRangos_textosDB = new Rangos_textosDB($conn);
		$cRangos_textos->setIdPrueba($rowRESPUESTAS_PRUEBAS->fields['idPrueba']);
		$cRangos_textos->setCodIdiomaIso2($rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2']);
    	$cRangos_textos->setIdTipoInforme($cProceso_informes->getIdTipoInforme());
    	$cRangos_textos->setOrderBy("`idIr` ASC, `idIp` DESC");
    	$cRangos_ir = new Rangos_ir();
    	$cRangos_irDB = new Rangos_irDB($conn);

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
					$cRangos_ipDB = new Rangos_ipDB($conn);
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
		$sRan_test="";
		if (!empty($idRango) && !empty($idRangoIp)){

	//		echo "<br />idRango::" . $idRango . " idRangoIp::" . $idRangoIp;
			$cRan_test = new Rangos_textos();
			$cRan_test->setIdPrueba($rowRESPUESTAS_PRUEBAS->fields['idPrueba']);
			$cRan_test->setCodIdiomaIso2($rowRESPUESTAS_PRUEBAS->fields['codIdiomaIso2']);
	    	$cRan_test->setIdTipoInforme($cProceso_informes->getIdTipoInforme());
	    	$cRan_test->setIdIp($idRangoIp);
	    	$cRan_test->setIdIr($idRango);

	    	$cRan_test = $cRangos_textosDB->readEntidad($cRan_test);

	    	$sRan_test = strip_tags($cRan_test->getTexto());
	    	//$sRan_test = preg_replace("/[\n\r]/","",$sRan_test);
	    	$sRan_test = trim($sRan_test,"\n\r");
	    	//echo nl2br($sRan_test);exit;
		}

		return array($puntuacion, $ultima_pregunta, $iPercentil, $IR, $IP, $POR, $sRan_test);
	}

	function getPuntuacionELT($conn, $sPrueba, $rowRESPUESTAS_PRUEBAS){
		$puntuacion=0;
		$ultima_pregunta=0;

		$sSQL="SELECT SUM(valor) AS valor, COUNT(*) AS respuestas ";
		$sSQL.="FROM respuestas_pruebas_items ";
		$sSQL.="WHERE idEmpresa='" . $rowRESPUESTAS_PRUEBAS->fields['idEmpresa'] . "' ";
		$sSQL.="AND idProceso='" . $rowRESPUESTAS_PRUEBAS->fields['idProceso'] . "' ";
		$sSQL.="AND idCandidato='" . $rowRESPUESTAS_PRUEBAS->fields['idCandidato'] . "' ";
		$sSQL.="AND idPrueba='" . $rowRESPUESTAS_PRUEBAS->fields['idPrueba'] . "' ";
		$rs = $conn->Execute($sSQL);
		$i=0;
		$puntuacion=0;
		$ultima_pregunta = 0;
		while (!$rs->EOF)
		{
			$puntuacion = $rs->fields['valor'];
			$ultima_pregunta = $rs->fields['respuestas'];
				$rs->MoveNext();
			$i++;
		}

		return array($puntuacion,$ultima_pregunta);
	}

	function getPercentil($iPDirecta, $idBaremo, $idPrueba){

		global $cBaremos_resultadosDB;
		global $conn;
		$iPercentil = -1;

		$cBaremos_resultados = new Baremos_resultados();
		$cBaremos_resultados->setIdBaremo($idBaremo);
		$cBaremos_resultados->setIdPrueba($idPrueba);
		$sqlBaremosResultados = $cBaremos_resultadosDB->readLista($cBaremos_resultados);
//		echo "<br />" . $sqlBaremosResultados;exit;
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

		return $iPercentil;
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

	function getPercentilTIC($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
		if ($pd==0) $Baremo_Estandar=1;
		if ($pd==1) $Baremo_Estandar=3;
		if ($pd==2) $Baremo_Estandar=5;
		if ($pd==3) $Baremo_Estandar=12;
		if ($pd==4) $Baremo_Estandar=18;
		if ($pd==5) $Baremo_Estandar=31;
		if ($pd==6) $Baremo_Estandar=46;
		if ($pd==7) $Baremo_Estandar=58;
		if ($pd==8) $Baremo_Estandar=73;
		if ($pd==9) $Baremo_Estandar=82;
		if ($pd==10) $Baremo_Estandar=90;
		if ($pd==11) $Baremo_Estandar=95;
		if ($pd==12) $Baremo_Estandar=98;
		if ($pd==13) $Baremo_Estandar=99;
		return $Baremo_Estandar;
	}
	function getPercentilTAC($pd){
		//BAREMO ESTANDAR
		$Baremo_Estandar=0;
		if ($pd==0) $Baremo_Estandar=2;
		if ($pd==1) $Baremo_Estandar=4;
		if ($pd==2) $Baremo_Estandar=8;
		if ($pd==3) $Baremo_Estandar=14;
		if ($pd==4) $Baremo_Estandar=24;
		if ($pd==5) $Baremo_Estandar=34;
		if ($pd==6) $Baremo_Estandar=50;
		if ($pd==7) $Baremo_Estandar=66;
		if ($pd==8) $Baremo_Estandar=76;
		if ($pd==9) $Baremo_Estandar=86;
		if ($pd==10) $Baremo_Estandar=92;
		if ($pd==11) $Baremo_Estandar=96;
		if ($pd==12) $Baremo_Estandar=98;
		if ($pd==13) $Baremo_Estandar=99;
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

	function getPercentilEN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilVN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilNN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilDN1($pd){
		$Baremo_Estandar=-1;

	    return $Baremo_Estandar;
	}
	function getPercentilDIPS($pd){
		$Baremo_Estandar=0;
	    if ($pd >= 0 && $pd < 7) $Baremo_Estandar=1;
	    if ($pd==8) $Baremo_Estandar=2;
	    if ($pd==9) $Baremo_Estandar=4;
	    if ($pd==10) $Baremo_Estandar=8;
	    if ($pd==11) $Baremo_Estandar=12;
	    if ($pd==12) $Baremo_Estandar=18;
	    if ($pd==13) $Baremo_Estandar=24;
	    if ($pd==14) $Baremo_Estandar=34;
	    if ($pd==15) $Baremo_Estandar=46;
	    if ($pd==16) $Baremo_Estandar=54;
	    if ($pd==17) $Baremo_Estandar=66;
	    if ($pd==18) $Baremo_Estandar=73;
	    if ($pd==19) $Baremo_Estandar=82;
	    if ($pd==20) $Baremo_Estandar=86;
	    if ($pd==21) $Baremo_Estandar=92;
	    if ($pd==22) $Baremo_Estandar=96;
	    if ($pd==23) $Baremo_Estandar=97;
	    if ($pd>=24 && $pd<=30) $Baremo_Estandar=99;

	    return $Baremo_Estandar;
	}

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
		global $_idEmpresa;
		global $_descEmpresa;
		global $_idProceso;
		global $_descProceso;
		global $_codIdiomaIso2;
		global $_descIdiomaIso2;
		global $_idPrueba;
		global $_descPrueba;
		global $_idCandidato;

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
		if ($i==0){
			$_idEmpresa=$rsLine->fields['idEmpresa'];
			$_descEmpresa=$rsLine->fields['descEmpresa'];
			$_idProceso=$rsLine->fields['idProceso'];
			$_descProceso=$rsLine->fields['descProceso'];
			$_codIdiomaIso2=$rsLine->fields['codIdiomaIso2'];
			$_descIdiomaIso2=$rsLine->fields['descIdiomaIso2'];
			$_idPrueba=$rsLine->fields['idPrueba'];
			$_descPrueba=$rsLine->fields['descPrueba'];
			$_idCandidato=$rsLine->fields['idCandidato'];
		}
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
?>
