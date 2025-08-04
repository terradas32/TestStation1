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
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
  require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidatoDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidato.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresasDB.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresas.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Envios/EnviosDB.php");
	require_once(constant("DIR_WS_COM") . "Envios/Envios.php");
	require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
	require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");

include_once ('include/conexion.php');


	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
	$cEntidad	= new Candidatos();  // Entidad

	$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
	$cEmpresas		= new Empresas();  // Entidad

	$cProcesosDB	= new ProcesosDB($conn);  // Entidad DB
	$cProcesos		= new Procesos();  // Entidad

	$cProceso_informesDB	= new Proceso_informesDB($conn);  // Entidad DB
	$cProceso_informes		= new Proceso_informes();  // Entidad

	$cProceso_pruebasDB	= new Proceso_pruebasDB($conn);  // Entidad DB
	$cProceso_pruebas		= new Proceso_pruebas();  // Entidad

	$cProceso_pruebas_candidatoDB	= new Proceso_pruebas_candidatoDB($conn);  // Entidad DB
	$cProceso_pruebas_candidato		= new Proceso_pruebas_candidato();  // Entidad

	$cPruebasDB = new PruebasDB($conn);
	$cPruebas = new Pruebas();

	$cInformes_pruebasDB	= new Informes_pruebasDB($conn);  // Entidad DB
	$cInformes_pruebas		= new Informes_pruebas();  // Entidad

	$cInformes_pruebas_empresasDB	= new Informes_pruebas_empresasDB($conn);  // Entidad DB
	$cInformes_pruebas_empresas		= new Informes_pruebas_empresas();  // Entidad

	$cCorreos_procesoDB	= new Correos_procesoDB($conn);  // Entidad DB
	$cCorreos_proceso	= new Correos_proceso();  // Entidad

	$cEnviosDB	= new EnviosDB($conn);  // Entidad DB
	$cEnvios	= new Envios();  // Entidad

	$cNotificacionesDB	= new NotificacionesDB($conn);
	$cNotificaciones	= new Notificaciones();

	$cCandidatosDB = new CandidatosDB($conn);

	$sCol1='';
	$sCol2='';
	$sHijos = "";
	$bReenviar = "1";

	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"","","orden");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboTIPOS_CORREOS	= new Combo($conn,"fIdTipoCorreo","idTipoCorreo","nombre","Descripcion","tipos_correos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","fecMod");
	$comboCORREOS_PROCESO	= new Combo($conn,"fIdCorreo","idCorreo","nombre","Descripcion","correos_proceso","",constant("SLC_OPCION"),"","","fecMod");
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","nombre,apellido1,apellido2,mail");
	$comboTRATAMIENTOS	= new Combo($conn,"fIdTratamiento","idTratamiento","descripcion","Descripcion","tratamientos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboSEXOS	= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboEDADES	= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_PAISES	= new Combo($conn,"fIdPais","idPais","descripcion","Descripcion","wi_paises","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_PROVINCIAS	= new Combo($conn,"fIdProvincia","idProvincia","descripcion","Descripcion","wi_provincias","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_MUNICIPIOS	= new Combo($conn,"fIdMunicipio","idMunicipio","descripcion","Descripcion","wi_municipios","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_ZONAS	= new Combo($conn,"fIdZona","idZona","descripcion","Descripcion","wi_zonas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboFORMACIONES	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboNIVELESJERARQUICOS	= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboAREAS	= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");

	$sCandidatosYaEvaluadosLessYear="";
	$aCandidatosYaEvaluadosLessYear= array();

	$sNOEnviados="";
	$sNOEnviadosLessYear="";
	$sMailsNOEnviados="";
	$sMailsNOEnviadosLessYear="";
	$bTodos=false;
	$iTotalCandidatos=0;
	$iTotalEnviados=0;
	$sMailsNOEnviados="";
	$sMailsNOEnviadosLessYear="";
	$sEnviados="";
	$sMailsEnviados="";

	$aMsg = array();
	//Recogemos todos los procesos que tengan un envio programado

	$sSQL ="SELECT * FROM procesos WHERE fecEnvioProgramado IS NOT NULL ORDER BY fecEnvioProgramado ASC ";
	//echo "<br />" . $sSQL;
	$rsPROCESOS = $conn->Execute($sSQL);

	while (!$rsPROCESOS->EOF)
	{
		$aMsg = array();
		$iTotalEnviados=0;
		$cEmpresas		= new Empresas();
		$cEmpresas->setIdEmpresa($rsPROCESOS->fields['idEmpresa']);
		$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
		if ($cEmpresas->getTimezone() != "")	//Por si acaso es un proceso antiguo que la empresa se haya eliminado.
		{
			$dt=new datetime("now",new datetimezone($cEmpresas->getTimezone()));
			$fecActual = gmdate("Y-m-d H:i:s",(time()+$dt->getOffset()));	//Fecha actual de la Zona horaria
			$fecActual = strtotime($fecActual);
			$fecProgramada = strtotime($rsPROCESOS->fields['fecEnvioProgramado']);

			if(($fecActual >= $fecProgramada)){
				//Miramos los candidatos No informados y que tengan cuenta de correo para evitar las altas ciegas.
				$sSQL ="SELECT * FROM candidatos WHERE mail <> '' ";
				$sSQL .=" AND idEmpresa=" . $conn->qstr($rsPROCESOS->fields['idEmpresa'], false);
				$sSQL .=" AND idProceso=" . $conn->qstr($rsPROCESOS->fields['idProceso'], false);
				$sSQL .=" AND informado=0";
				//echo "<br />" . $sSQL;
				$rsCANDIDATOS = $conn->Execute($sSQL);
				$iTotalCandidatos	=	$rsCANDIDATOS->RecordCount();
				$sEmpresa = $cEmpresas->getNombre();
				$sProceso = $rsPROCESOS->fields['nombre'];
				$sFecEnvio = $rsPROCESOS->fields['fecEnvioProgramado'];
				$sTimezone = $cEmpresas->getTimezone();

				if ($iTotalCandidatos > 0){
					$cCorreos_proceso	= new Correos_proceso();
					$cCorreos_proceso->setIdEmpresa($rsPROCESOS->fields['idEmpresa']);
					$cCorreos_proceso->setIdProceso($rsPROCESOS->fields['idProceso']);
					$cCorreos_proceso->setIdTipoCorreo("1");	//Envío de datos de Acceso
					//echo "<br />-->" . $cCorreos_procesoDB->readLista($cCorreos_proceso);
					$rsCorreos_proceso = $conn->Execute($cCorreos_procesoDB->readLista($cCorreos_proceso));
					enviarEmailsProgramados($rsPROCESOS, $cEmpresas, $rsCANDIDATOS, $rsCorreos_proceso);
					$sEnviadosMsg = str_replace("\\n", "<br />",str_replace("\\t", str_repeat("&nbsp;", 4), nl2br($sEnviados)));
					$sNOEnviadosMsg = str_replace("\\n", "<br />",str_replace("\\t", str_repeat("&nbsp;", 4), nl2br($sNOEnviados)));

					$sMsg = 'A continuación, le confirmamos que se ha procedido a realizar el envío programado de datos de acceso a participantes con fecha de <strong>' . $sFecEnvio . ' (' . $sTimezone . ')</strong>.
					<br><br>
					Detalle del envío
					<br><br>
					Nombre del Proceso: <strong>' . $sProceso . '</strong>:
					<br>Nº de participantes incluidos en el envío programado: ' . $iTotalCandidatos . '
					<br>Nº de participantes con datos de acceso enviados: ' . $iTotalEnviados . '
					';
						if (!empty($sEnviados))
						{
							$sMsg .= '
							<br />Detalle de participantes informados:
							<br /><ul>' . $sEnviadosMsg . '</ul>';
						}
						if (!empty($sNOEnviados))
						{
							$sMsg .= '
							<br />Detalle de participantes NO informados:
							<br /><ul>' . $sNOEnviadosMsg . '</ul>';
						}
					$sMsg .= '
					<br>El proceso de envío programado ha finalizado. Si lo desea puede volver a programar otro envío.
					<br><br>
					<br>Cordialmente,
					<br>People Experts
					<br>info@test-station.com';

					$aMsg[] = $sMsg;
					if (enviaEmailResumen($cEmpresas, $aMsg, $sProceso)){
						$sSQL ="UPDATE procesos SET fecEnvioProgramado=NULL ";
						$sSQL .=" WHERE idEmpresa=" . $conn->qstr($rsPROCESOS->fields['idEmpresa'], false);
						$sSQL .=" AND idProceso=" . $conn->qstr($rsPROCESOS->fields['idProceso'], false);
						$conn->Execute($sSQL);
					}
				}else{
					$sMsg = 'A continuación, le confirmamos que se ha procedido a realizar el envío programado de datos de acceso a participantes con fecha de <strong>' . $sFecEnvio . ' (' . $sTimezone . ')</strong>.
					<br><br>
					Detalle del envío
					<br><br>
					Nombre del Proceso: <strong>' . $sProceso . '</strong>:
					<br>Nº de participantes incluidos en el envío programado: ' . $iTotalCandidatos . '
					<br>Nº de participantes con datos de acceso enviados: ' . $iTotalEnviados . '
					';
						if (!empty($sEnviados))
						{
							$sMsg .= '
							<br />Detalle de participantes informados:
							<br /><ul>' . $sEnviadosMsg . '</ul>';
						}
						if (!empty($sNOEnviados))
						{
							$sMsg .= '
							<br />Detalle de participantes NO informados:
							<br /><ul>' . $sNOEnviadosMsg . '</ul>';
						}
					$sMsg .= '
					<br>El proceso de envío programado ha finalizado. Si lo desea puede volver a programar otro envío.
					<br><br>
					<br>Cordialmente,
					<br>People Experts
					<br>info@test-station.com';
					$aMsg[] = $sMsg;
					if (enviaEmailResumen($cEmpresas, $aMsg, $sProceso)){
						$sSQL ="UPDATE procesos SET fecEnvioProgramado=NULL ";
						$sSQL .=" WHERE idEmpresa=" . $conn->qstr($rsPROCESOS->fields['idEmpresa'], false);
						$sSQL .=" AND idProceso=" . $conn->qstr($rsPROCESOS->fields['idProceso'], false);
						$conn->Execute($sSQL);
					}
				}
			}
		}
		$rsPROCESOS->MoveNext();
	}
	//print_r($aMsg);
	//exit;

	function enviarEmailsProgramados($rsPROCESO, $cEmpresas, $rsCandidatos, $rsCorreos_proceso){
		global $conn;
		global $aMsg;
		global $cUtilidades;
		global $cCandidatosDB;
		global $cProceso_pruebasDB;
		global $cProceso_informesDB;
		global $cInformes_pruebas_empresasDB;
		global $cInformes_pruebasDB;
		global $cEmpresasDB;
		global $cProcesosDB;
		global $cEnviosDB;
		global $cCorreos_procesoDB;
		global $cNotificacionesDB;

		global $sNOEnviados;
		global $sNOEnviadosLessYear;
		global $sMailsNOEnviados;
		global $sMailsNOEnviadosLessYear;
		global $iTotalCandidatos;
		global $iTotalEnviados;
		global $sMailsNOEnviados;
		global $sMailsNOEnviadosLessYear;
		global $sEnviados;
		global $sMailsEnviados;

			$sMSG_JS_ERROR = "";
			$sMSG_JS_RESUMEN = "";
			$sqlCandidatos="";
			$cCandidatos = new Candidatos();
			$cProcesos = new Procesos();
			$bTodos=true;
			$iTotalCandidatos	=	$rsCandidatos->RecordCount();

			$cEnvios = new Envios();
			$cEnvios->setIdEmpresa($rsCorreos_proceso->fields['idEmpresa']);
			$cEnvios->setIdProceso($rsCorreos_proceso->fields['idProceso']);
			$cEnvios->setIdTipoCorreo($rsCorreos_proceso->fields['idTipoCorreo']);
			$cEnvios->setIdCorreo($rsCorreos_proceso->fields['idCorreo']);

			$iDonglesDeEmpresa	=	$cEmpresas->getDongles();
			$iDonglesADescontarUnitario	=	0;
			$iDonglesADescontar	=	0;

			$cProceso_pruebas	=	new Proceso_pruebas();
			$cProceso_pruebas->setIdEmpresa($cEnvios->getIdEmpresa());
			$cProceso_pruebas->setIdProceso($cEnvios->getIdProceso());
			$sqlProceso_pruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
			$rsProceso_pruebas = $conn->Execute($sqlProceso_pruebas);
      $sPruebas="";
      while (!$rsProceso_pruebas->EOF)
			{
				$sPruebas.="," . $rsProceso_pruebas->fields['idPrueba'];
				$rsProceso_pruebas->MoveNext();
      }
      if (!empty($sPruebas)){
      	$sPruebas = substr($sPruebas,1);
      }
			//echo "*->" . $sPruebas;exit;
			$cProceso_informes	=	new Proceso_informes();
			$cProceso_informes->setIdEmpresa($cEnvios->getIdEmpresa());
			$cProceso_informes->setIdProceso($cEnvios->getIdProceso());
      $cProceso_informes->setIdPrueba($sPruebas);
			$sqlProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);

			$rsProceso_informes = $conn->Execute($sqlProceso_informes);

			//Miramos por cada informe seleccionado la tarifa a descontar
			while (!$rsProceso_informes->EOF)
			{
				//Cambiar Dongels por Cliente/Prueba/Informe
    			//Miramos si tiene definido dongles por empresa
    			$cInformes_pruebas = new Informes_pruebas_empresas();
    			$cInformes_pruebas->setIdPrueba($rsProceso_informes->fields['idPrueba']);
    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
    			$cInformes_pruebas->setIdEmpresa($rsProceso_informes->fields['idEmpresa']);

				$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformes_pruebas);
				$rsIPE = $conn->Execute($sql_IPE);
    		if ($rsIPE->NumRows() > 0){
    				$cInformes_pruebas = $cInformes_pruebas_empresasDB->readEntidad($cInformes_pruebas);
    		}else {
					$cInformes_pruebas	=	new Informes_pruebas();
					$cInformes_pruebas->setIdPrueba($rsProceso_informes->fields['idPrueba']);
					$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
					$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
					$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);
    		}

				$iDonglesADescontarUnitario += $cInformes_pruebas->getTarifa();
				$rsProceso_informes->MoveNext();
			}
			$iDonglesADescontar	=	($iDonglesADescontarUnitario * $iTotalCandidatos);
      //echo "<br />**->" . $iDonglesADescontar;
			//Verificamos si esa empresa es por contrato o prepago
			//Si es por prepago verificamos si tiene suficientes dongles
			$bPrepago = "1";
			//Miramos si hay que descontar de la Matriz
			$sDescuentaMatriz = $cEmpresas->getDescuentaMatriz();
			$cMatrizDng = new Empresas();
			if (!empty($sDescuentaMatriz)){
				$cMatrizDng->setIdEmpresa($sDescuentaMatriz);
				$cMatrizDng = $cEmpresasDB->readEntidad($cMatrizDng);
				$bPrepago = $cMatrizDng->getPrepago();
			}else{
				$bPrepago = $cEmpresas->getPrepago();
			}
			//echo "<br />*2*->" . $bPrepago;
			//$bPrepago = $cEmpresas->getPrepago();
			if (!empty($bPrepago))
			{
				$sDescuentaMatriz = $cEmpresas->getDescuentaMatriz();
				if (!empty($sDescuentaMatriz)){
					//Consultamos los datos de la empresa a la que realmente se le descontará
					$cMatrizConsumo = new Empresas();
					$cMatrizConsumoDB = new EmpresasDB($conn);
					$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
					$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
					$iDonglesDeEmpresa	=	$cMatrizConsumo->getDongles();
				}
				$bUnidadesSuficientes=true;
				//Es de prepago hay que verificar los dongles
				if ($iDonglesADescontar > $iDonglesDeEmpresa){
					$bUnidadesSuficientes=false;
					//Hay que descontar mas dongles que los que tiene cargados la empresa,
					//Se lanza mensaje de error.
					$iTotalEnviados=0;
					$sMSG_JS_ERROR="" . $cEmpresas->getNombre() . " - " . constant("STR_NO_DISPONE_DE_SUFICIENTES_UNIDADES_PARA_EFECTUAR_LA_OPERACION") . ".\\n";
					$sMSG_JS_ERROR.="\\t" . constant("STR_UNIDADES_DISPONIBLES") . ": " . $iDonglesDeEmpresa . " " . constant("STR_UNIDADES") . ".\\n";
					$sMSG_JS_ERROR.="\\t" . constant("STR_UNIDADES_A_CONSUMIR") . ": " . $iDonglesADescontar . " " . constant("STR_UNIDADES") . ".\\n\\n";
					$sMSG_JS_ERROR.="" . constant("STR_POR_FAVOR_RECARGUE_UN_MINIMO_DE") . ":\\n ";
					$sMSG_JS_ERROR.="\\t" . ($iDonglesADescontar - $iDonglesDeEmpresa) . " " . constant("STR_UNIDADES") . ".\\n ";
					$sMSG_JS_ERROR.='<br /><br />Nº de candidatos que deben ser incluidos en el envío programado: ' . $iTotalCandidatos . '
							<br />Nº de candidatos con datos de acceso enviados: ' . $iTotalEnviados . '
									<br /><br />El envío programado ha finalizado.
									<br /><br />Si lo desea puede volver a programar otro envío
									<br /><br /><br /><br />Atentamente,
									<br />Test-Station
									<br /><img src="http://www.people-experts.com/estilos/images/logo_people.jpg" title="People Experts" alt="" />';

					$bReenviar = "0";
					$aMsg[] =str_replace("\\n", "<br />",str_replace("\\t", str_repeat("&nbsp;", 4), nl2br($sMSG_JS_ERROR)));
					if (enviaEmailResumen($cEmpresas, $aMsg, $sProceso)){
						$sSQL ="UPDATE procesos SET fecEnvioProgramado=NULL ";
						$sSQL .=" WHERE idEmpresa=" . $conn->qstr($rsPROCESO->fields['idEmpresa'], false);
						$sSQL .=" AND idProceso=" . $conn->qstr($rsPROCESO->fields['idProceso'], false);
						$conn->Execute($sSQL);
					}
				}
			}else{
				//Es de contrato, No se hacer la verificación de dongles
				$bUnidadesSuficientes=true;
			}

		//echo "<br />*3*->" . $bUnidadesSuficientes;
		if ($bUnidadesSuficientes)
		{
			@set_time_limit(0);
			ini_set("memory_limit","512M");
			$sFrom=$cEmpresas->getMail();	//Cuenta de correo de la empresa
			$sFromName=$cEmpresas->getNombre();	//Nombre de la empresa

			$cProcesos->setIdProceso($cEnvios->getIdProceso());
			$cProcesos->setIdEmpresa($cEnvios->getIdEmpresa());
			$cProcesos = $cProcesosDB->readEntidad($cProcesos);
			$IdModoRealizacion = $cProcesos->getIdModoRealizacion();
			// EnvioContrasenas == 1 Individuales
			// EnvioContrasenas == 2 Todas en 1 sólo correo
			$EnvioContrasenas = $cProcesos->getEnvioContrasenas();
			$sTodas1Correo = "";
			$cCandidatosDB = new CandidatosDB($conn);
			$sNOEnviados= "";
			$sNOEnviadosLessYear= "";
			$sEnviados="";
			$sMailsEnviados="";

			$iTotal = $rsCandidatos->RecordCount();
			$iFallidos = 0;
			$aMailsNOEnviados = (empty($sMailsNOEnviados)) ? array() : explode(",", $sMailsNOEnviados);
			$aMailsNOEnviadosLessYear = (empty($sMailsNOEnviadosLessYear)) ? array() : explode(",", $sMailsNOEnviadosLessYear);
			$bFormatoCorreoOK=true;
			//echo "<br />*4*->" . $cEnvios->getIdTipoCorreo();
			switch ($cEnvios->getIdTipoCorreo()){
				case "1":	//Envio o Reenvio de información
					//Recorremos los candidatos, enviamos el correo
					//y lo damos de alta en en la tabla envios.
					//Esta forma es incremental, teniendo un histórico de envios.
					$i=0;
					$sBody = "";
					$sAltBody = "";
					$sSubject = "";
					while (!$rsCandidatos->EOF)
					{
						$cCandidatos = new Candidatos();
						$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
						$cCandidatos->setIdProceso($cEnvios->getIdProceso());
						$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
						$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
						$newPass= $cUtilidades->newPass();
						$sUsuario=$cCandidatos->getMail();

						//Administrado y enviar todas las contraseñas en 1 sólo correo
						//echo "<br />EnvioContrasenas:" . $EnvioContrasenas;exit;
						if ($EnvioContrasenas == "2")
						{
							$cNotificaciones	= new Notificaciones();
							$cNotificaciones->setIdTipoNotificacion(8);	//Usuarios y contraseña juntos
							$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
							$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, null, $cProcesos, null, $cCandidatos, null, null, $sUsuario, $newPass);

							$sSubject=$cNotificaciones->getAsunto();
							$sBody.=$cNotificaciones->getCuerpo();
							$sAltBody.="\\n" . strip_tags($cNotificaciones->getCuerpo());

							//Actualizamos el usuario con la nueva contraseña
							//Lo ponemos como informado
							$cCandidatos->setPassword($newPass);
							$cCandidatos->setInformado(1);
							$cCandidatos = $cCandidatosDB->modificar($cCandidatos);
							$cEnvios_hist	= new Envios();
							$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
							$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
							$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
							$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
							$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
							$cEnvios_hist->setUsuAlta(0);
							$cEnvios_hist->setUsuMod(0);
							$cEnviosDB->insertar($cEnvios_hist);
						}else{
							$cCorreos_proceso = new Correos_proceso();
							$cCorreos_proceso->setIdEmpresa($cEnvios->getIdEmpresa());
							$cCorreos_proceso->setIdProceso($cEnvios->getIdProceso());
							$cCorreos_proceso->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
							$cCorreos_proceso->setIdCorreo($cEnvios->getIdCorreo());
							$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

							$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cCandidatos, $cProcesos, $cEmpresas, $sUsuario, $newPass);

							$sSubject=$cCorreos_proceso->getAsunto();
							$sBody=$cCorreos_proceso->getCuerpo();
							$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

							if (!empty($sBody) && !empty($sSubject))
							{
    							if (!in_array(strtolower($cCandidatos->getMail()), $aMailsNOEnviados))
    							{

    								if (!enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
    									//informamos de los emails q no se han podido enviar.
    									$iFallidos++;
											$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
											$sNOEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
    									$sMailsNOEnviados.= "," . $cCandidatos->getMail();
    								}else{
    									//Actualizamos el usuario con la nueva contraseña
    									//Lo ponemos como informado
											$sEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
											$sMailsEnviados.= "," . $cCandidatos->getMail();

    									$cCandidatos->setPassword($newPass);
    									$cCandidatos->setInformado(1);
    									$OK = $cCandidatosDB->modificar($cCandidatos);
    									$cEnvios_hist	= new Envios();
    									$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
    									$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
    									$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
    									$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
    									$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
    									$cEnvios_hist->setUsuAlta(0);
    									$cEnvios_hist->setUsuMod(0);
    									$cEnviosDB->insertar($cEnvios_hist);
    									$sTypeError	=	date('d/m/Y H:i:s') . " Correo enviado FROM::[" . $sFrom . "] TO::[" . $cCandidatos->getMail() . "]";
    									error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
    								}
    							}
              }else{
								//informamos de los emails q no se han podido enviar.
								$iFallidos++;
								$bFormatoCorreoOK=false;

								$sMSG_JS_ERROR="\\n MSG400 empty::\\t" . $cCandidatos->getMail();
								$sMSG_JS_RESUMEN.="\\n" . $sMSG_JS_ERROR;
								$sNOEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
								$sMailsNOEnviados.= "," . $cCandidatos->getMail();
								$aMsg[] =str_replace("\\n", "<br />",str_replace("\\t", str_repeat("&nbsp;", 4), nl2br($sMSG_JS_ERROR)));
							}
						}

						$i++;
						$rsCandidatos->MoveNext();
					}
					if (!$bFormatoCorreoOK){

						$sMSG_JS_ERROR='<br /><br />Nº de candidatos que deben ser incluidos en el envío programado: ' . $iTotalCandidatos . '
							<br />Nº de candidatos con datos de acceso enviados: ' . $iTotalEnviados . '
									<br /><br />El envío programado ha finalizado.
									<br /><br />Si lo desea puede volver a programar otro envío
									<br /><br /><br /><br />Atentamente,
									<br />Test-Station
									<br /><img src="http://www.people-experts.com/estilos/images/logo_people.jpg" title="People Experts" alt="" />';
						$aMsg[] =str_replace("\\n", "<br />",str_replace("\\t", str_repeat("&nbsp;", 4), nl2br($sMSG_JS_ERROR)));
						if (enviaEmailResumen($cEmpresas, $aMsg, $sProceso)){
							$sSQL ="UPDATE procesos SET fecEnvioProgramado=NULL ";
							$sSQL .=" WHERE idEmpresa=" . $conn->qstr($rsPROCESO->fields['idEmpresa'], false);
							$sSQL .=" AND idProceso=" . $conn->qstr($rsPROCESO->fields['idProceso'], false);
							$conn->Execute($sSQL);
						}
					}
					//echo "//-->" . $EnvioContrasenas;exit;
					//Administrado y enviar todas las contraseñas en 1 sólo correo
					if ($EnvioContrasenas == "2"){
						if (!empty($sBody)){
							$cCorreos_proceso = new Correos_proceso();
							$cCorreos_proceso->setIdEmpresa($cEnvios->getIdEmpresa());
							$cCorreos_proceso->setIdProceso($cEnvios->getIdProceso());
							$cCorreos_proceso->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
							$cCorreos_proceso->setIdCorreo($cEnvios->getIdCorreo());
							$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

							$cCorreos_proceso->setAsunto($sSubject);
							$cCorreos_proceso->setCuerpo($sBody);

							if (!enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
								//informamos de los emails q no se han podido enviar.
								$iFallidos++;
								$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
								$sNOEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
								$sMailsNOEnviados.= "," . $cCandidatos->getMail();
							}
						}
					}
					break;
				case "2":	//Confirmación
					//Miramos por candidato si se le ha enviado
					// previamente el correo de envio.
					while (!$rsCandidatos->EOF){
						$cCandidatos = new Candidatos();
						$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
						$cCandidatos->setIdProceso($cEnvios->getIdProceso());
						$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
						$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
						$cEnvios_hist = new Envios();
						$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
						$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
						$cEnvios_hist->setIdTipoCorreo(1); //Envio
						$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
						$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
						$sqlEnvios_hist = $cEnviosDB->readLista($cEnvios_hist);
						//echo "<br />" . $sqlEnvios_hist;
						$rsEnvios_hist = $conn->Execute($sqlEnvios_hist);
						if ($rsEnvios_hist->RecordCount() <= 0){
							$iFallidos++;
							$sMSG_JS_ERROR="No se ha enviado previamente el correo de información\\nSE HA CANCELADO EL PROCESO DE ENVÍO.\\n";
							$sNOEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
							$sMailsNOEnviados.= "," . $cCandidatos->getMail();
							$iFallidos = $iTotal;
						}
						$rsCandidatos->MoveNext();
					}
					if (empty($sNOEnviados))
					{
						//Miramos que tenga el corre definido en el cuerpo
						//la etiqueta @acceso_password@
					    $sLiteral = "@acceso_password@";
        				if (strpos($cCorreos_proceso->getCuerpo(), $sLiteral)){
							//continue;
						}else{
							$iFallidos++;
							$sMSG_JS_ERROR="El correo de confirmación no contiene la etiqueta adecuada de @acceso_password@\\nSE HA CANCELADO EL PROCESO DE ENVÍO.\\n";
							$sNOEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
							$sMailsNOEnviados.= "," . $cCandidatos->getMail();
							$iFallidos = $iTotal;
						}
						if (empty($sNOEnviados))
						{
							$rsCandidatos->Move(0); //Posicionamos en el primer registro.
							while (!$rsCandidatos->EOF){
								$cCandidatos = new Candidatos();
								$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
								$cCandidatos->setIdProceso($cEnvios->getIdProceso());
								$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
								$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
								$newPass= $cUtilidades->newPass();
								$sUsuario=$cCandidatos->getMail();
								$cCorreos_proceso = new Correos_proceso();
								$cCorreos_proceso->setIdEmpresa($cEnvios->getIdEmpresa());
								$cCorreos_proceso->setIdProceso($cEnvios->getIdProceso());
								$cCorreos_proceso->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
								$cCorreos_proceso->setIdCorreo($cEnvios->getIdCorreo());

								$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);
								$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cCandidatos, $cProcesos, $sUsuario, $newPass);

								$sSubject=$cCorreos_proceso->getAsunto();
								$sBody=$cCorreos_proceso->getCuerpo();
								$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

								if (!enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
									//informamos de los emails q no se han podido enviar.
									$iFallidos++;
									$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
									$sNOEnviados.= "<li>" . $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]</li>";
									$sMailsNOEnviados.= "," . $cCandidatos->getMail();
								}else{
									//Actualizamos el usuario con la nueva contraseña
									//Lo ponemos como informado
									$cCandidatos->setPassword($newPass);
									$cCandidatos->setInformado(1);
									$cCandidatos = $cCandidatosDB->modificar($cCandidatos);
									$cEnvios_hist	= new Envios();
									$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
									$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
									$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
									$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
									$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
									$cEnvios_hist->setUsuAlta(0);
									$cEnvios_hist->setUsuMod(0);
									$cEnviosDB->insertar($cEnvios_hist);
								}
								$i++;
								$rsCandidatos->MoveNext();
							}
						}
					}
					break;
				default:
					break;
			}
			$newId	= $sNOEnviados;
			if (!empty($sMailsNOEnviados)){
				if (substr($sMailsNOEnviados, 0, 1) == ","){
					$sMailsNOEnviados = substr($sMailsNOEnviados, 1);
					$aMailsNOEnviados = explode(",", $sMailsNOEnviados);
				}
				$_POST['fMailsNOEnviados'] =$sMailsNOEnviados;
			}
			$iTotalEnviados += $iTotal;
			$_POST['fiTotalEnviados'] =$iTotalEnviados;

			if (empty($newId))
			{
				if ($iTotalEnviados >= $iTotalCandidatos)
				{
					$bReenviar = "0";

					$_POST['fiTotalCandidatosInicio']=0;
					$_POST['fiTotalEnviados']=0;
					$_POST['fMailsNOEnviados']="";
					$_POST['fMailsNOEnviadosLessYear']="";
				}else{

				}
			}else{
				if ($iTotalEnviados >= $iTotalCandidatos)
				{
					if (!empty($sMailsNOEnviados))
					{

						$aMailsNOEnviados = explode(",", $sMailsNOEnviados);
						$sMSG_JS_ERROR="-" . constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
						$sNOEnviados= "";
						for ($i=0, $max = sizeof($aMailsNOEnviados); $i < $max; $i++)
						{
							$sNOEnviados.=  " [" . $aMailsNOEnviados[$i] . "]\\n";
						}
						$bReenviar = "0";
						$_POST['fiTotalCandidatosInicio']=0;
						$_POST['fiTotalEnviados']=0;
						$_POST['fMailsNOEnviados']="";

					}
				}
				//$_POST['MODO']=constant("MNT_ALTA");
				//include('Template/EnviarCorreos/mntenviarcorreosa.php');
			}
		}
	}

	function enviaEmail(&$cEmpresa, &$cCandidato, &$cCorreos_proceso, $IdModoRealizacion){
		global $conn;

		$sSubject=$cCorreos_proceso->getAsunto();
		$sBody=$cCorreos_proceso->getCuerpo();
		$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

		require_once constant("DIR_WS_COM") . 'PHPMailer/Exception.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/PHPMailer.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/SMTP.php';

		//instanciamos un objeto de la clase phpmailer al que llamamos
		//por ejemplo mail
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);  //PHPMailer instance with exceptions enabled
		$mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		try {
			//Server settings
			//$mail->SMTPDebug = 2; 					                //Enable verbose debug output
			$mail->isSMTP();                                        //Send using SMTP                  
			$mail->Host = constant("HOSTMAIL");						//Set the SMTP server to send through
			$mail->SMTPAuth   = true;                               //Enable SMTP authentication
			$mail->Username = constant("MAILUSERNAME");             //SMTP username
			$mail->Password = constant("MAILPASSWORD");             //SMTP password
			$mail->SMTPSecure = 'tls';							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';

			//Con PluginDir le indicamos a la clase phpmailer donde se
			//encuentra la clase smtp que como he comentado al principio de
			//este ejemplo va a estar en el subdirectorio includes
			$mail->PluginDir = constant("DIR_WS_COM") . "/_Email/";

			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = $mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresa->getMail();
			$mail->From = constant("MAILUSERNAME");
			$mail->AddReplyTo($cEmpresa->getMail(), $cEmpresa->getNombre());
			$mail->FromName = $cEmpresa->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $sSubject;
			$mail->Body = $sBody;

			//Definimos AltBody por si el destinatario del correo no admite
			//email con formato html
			$mail->AltBody = $sAltBody;

			//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
			//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
			$mail->Timeout=120;

			//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
			$archivo="none";
			if ($archivo !="none") {
				$mail->AddAttachment($archivo,$archivo_name);
			}
			//Indicamos cuales son las direcciones de destino del correo
			if ($IdModoRealizacion == "2"){	//Administrado SE ENVIAN A LA EMPRESA
				$mail->AddAddress($cEmpresa->getMail(), $cEmpresa->getNombre());
				if($cEmpresa->getMail2()!=""){
					$mail->AddAddress($cEmpresa->getMail2(), $cEmpresa->getNombre());
				}
				if($cEmpresa->getMail3()!=""){
					$mail->AddAddress($cEmpresa->getMail3(), $cEmpresa->getNombre());
				}
			}else{
				$mail->AddAddress($cCandidato->getMail(), $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2());
			}

			//se envia el mensaje, si no ha habido problemas la variable $success
			//tendra el valor true
			$exito=false;
			//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
			//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
			//segundos despues del anterior, para ello se usa la funcion sleep
			$intentos=1;
			while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
			{
			sleep(rand(0, 2));
				//echo $mail->ErrorInfo;
				$exito = $mail->Send();
				$intentos=$intentos+1;
			}

			//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
			//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
			//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
			if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				$exito=true;
			}
			if(!$exito){
				$sTypeError	=	date('d/m/Y H:i:s') . " Problemas enviando correo electrónico FROM::[" . $mail->From . "] TO::[" . $cCandidatos->getMail() . "]";
				error_log($sTypeError . " ->\t" . $mail->ErrorInfo . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
			}
			//echo $mail->ErrorInfo;exit;
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
	    return $exito;
	}

	function enviaEmailResumen($cEmpresa, $aMsg, $sProceso=""){
		global $conn;

		$sSubject=constant("STR_NOTIFICACION_DE_ENVIO_PROGRAMADO") . " - " . $sProceso;
		$sBody=implode("", $aMsg);
		$sAltBody=strip_tags($sBody);

		require_once constant("DIR_WS_COM") . 'PHPMailer/Exception.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/PHPMailer.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/SMTP.php';

		//instanciamos un objeto de la clase phpmailer al que llamamos
		//por ejemplo mail
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);  //PHPMailer instance with exceptions enabled
		$mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		try {
			//Server settings
			//$mail->SMTPDebug = 2; 					                //Enable verbose debug output
			$mail->isSMTP();                                        //Send using SMTP                  
			$mail->Host = constant("HOSTMAIL");						//Set the SMTP server to send through
			$mail->SMTPAuth   = true;                               //Enable SMTP authentication
			$mail->Username = constant("MAILUSERNAME");             //SMTP username
			$mail->Password = constant("MAILPASSWORD");             //SMTP password
			$mail->SMTPSecure = 'tls';							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';


			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = $mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresa->getMail();
			$mail->From = constant("MAILUSERNAME");
			$mail->AddReplyTo($cEmpresa->getMail(), $cEmpresa->getNombre());
			$mail->FromName = $cEmpresa->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $sSubject;
			$mail->Body = $sBody;

			//Definimos AltBody por si el destinatario del correo no admite
			//email con formato html
			$mail->AltBody = $sAltBody;

			//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
			//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
			$mail->Timeout=120;

			//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
			$archivo="none";
			if ($archivo !="none") {
				$mail->AddAttachment($archivo,$archivo_name);
			}
			//Indicamos cuales son las direcciones de destino del correo

			$mail->AddAddress($cEmpresa->getMail(), $cEmpresa->getNombre());
			if($cEmpresa->getMail2()!=""){
				$mail->AddAddress($cEmpresa->getMail2(), $cEmpresa->getNombre());
			}
			if($cEmpresa->getMail3()!=""){
				$mail->AddAddress($cEmpresa->getMail3(), $cEmpresa->getNombre());
			}

			//se envia el mensaje, si no ha habido problemas la variable $success
			//tendra el valor true
			$exito=false;
			//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
			//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
			//segundos despues del anterior, para ello se usa la funcion sleep
			$intentos=1;
			while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
			{
				sleep(rand(0, 2));
				//echo $mail->ErrorInfo;
				$exito = $mail->Send();
				$intentos=$intentos+1;
			}

			//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
			//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
			//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
			if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				$exito=true;
			}
			if(!$exito){
				$sTypeError	=	date('d/m/Y H:i:s') . " Problemas enviando correo electrónico RESUMEN ENVIO PROGRAMADO FROM::[" . $mail->From . "][" . $mail->ErrorInfo . "] ";
				error_log($sTypeError . " ->\t" . $mail->ErrorInfo . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
			}
			//echo $mail->ErrorInfo;exit;
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
		return $exito;
	}
?>
