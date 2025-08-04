<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
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
	require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
	require_once(constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
	require_once(constant("DIR_WS_COM") . "Consumos/Consumos.php");
	require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");

include_once ('include/conexion.php');

	//Conexión a e-Cases
include_once ('include/conexionECases.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

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

	$cTipos_informesDB = new Tipos_informesDB($conn);
	$cConsumosDB = new ConsumosDB($conn);
	$cBaremosDB = new BaremosDB($conn);

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$cCandidatosDB = new CandidatosDB($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	$bReenviar = "1";
	if (empty($_POST["fHijos"]))
	{
		require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
		$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
	//	$_EmpresaLogada = constant("EMPRESA_PE");
		$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
		if (!empty($sHijos)){
			$sHijos .= $_EmpresaLogada;
		}else{
			$sHijos = $_EmpresaLogada;
		}
	}else{
		$sHijos = $_POST["fHijos"];
	}
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
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

	//echo('modo:' . $_POST['MODO']);

	if (!isset($_POST['MODO'])){
		session_start();
		$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "04000 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	$sCandidatosYaEvaluadosLessYear="";
	$aCandidatosYaEvaluadosLessYear= array();

	$sNOEnviados="";
	$sNOEnviadosLessYear="";
	$sMailsNOEnviados="";
	$sMailsNOEnviadosLessYear="";
	$bTodos=false;
	$iTotalCandidatosInicio=(!empty($_POST['fiTotalCandidatosInicio'])) ? $_POST['fiTotalCandidatosInicio'] : 0;
	$iTotalEnviados=(!empty($_POST['fiTotalEnviados'])) ? $_POST['fiTotalEnviados'] : 0;
	$sMailsNOEnviados=(!empty($_POST['fMailsNOEnviados'])) ? $_POST['fMailsNOEnviados'] : "";
	$sMailsNOEnviadosLessYear=(!empty($_POST['fMailsNOEnviadosLessYear'])) ? $_POST['fMailsNOEnviadosLessYear'] : "";
	$errorZip="";
  //echo "<br />'" . $sMailsNOEnviadosLessYear . "'";
	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):
		case constant("MNT_MODIFICAR"):
			$cEntidad	= readEntidad($cEntidad);
			$cEnvios	= readEntidadEnvios($cEnvios);
			$sMSG_JS_ERROR = "";
			$sMSG_JS_RESUMEN = "";
			$sqlCandidatos="";
			$findme   = '-1';	//Todos
			$pos = strpos($cEnvios->getIdCandidato(), $findme);
			$cCandidatos = new Candidatos();
			if ($pos === false) {
				$cCandidatos->setIdCandidatoIN($cEnvios->getIdCandidato());
				$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
				$cCandidatos->setIdProceso($cEnvios->getIdProceso());
				$sqlCandidatos = $cEntidadDB->readListaIN($cCandidatos);
			} else {
				//Todos los del proceso
				$bTodos=true;
				$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
				$cCandidatos->setIdProceso($cEnvios->getIdProceso());
				$cCandidatos->setInformado($cEntidad->getInformado());
				$cCandidatos->setInformadoHast($cEntidad->getInformado());
				$sqlCandidatos = $cEntidadDB->readListaIN($cCandidatos);
				$sqlCandidatos .= " AND mail <>''";
			}
			if (empty($_POST['fiTotalCandidatosInicio'])){
				//Primera llamada
				$rsCandidatos = $conn->Execute($sqlCandidatos);
				$iTotalCandidatosInicio	=	$rsCandidatos->RecordCount();
				$_POST['fiTotalCandidatosInicio'] = $iTotalCandidatosInicio;
			}
			if ($bTodos){
				$sqlCandidatos .= " LIMIT 0 , 30 ";
			}
			$rsCandidatos = $conn->Execute($sqlCandidatos);
			$iTotalCandidatos	=	$rsCandidatos->RecordCount();

			$cEmpresas->setIdEmpresa($cEnvios->getIdEmpresa());
			$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

			$iDonglesDeEmpresa	=	$cEmpresas->getDongles();
			$iDonglesADescontarUnitario	=	0;
			$iDonglesADescontar	=	0;

			$cProceso_pruebas	=	new Proceso_pruebas();
			$cProceso_pruebas->setIdEmpresa($cEnvios->getIdEmpresa());
			$cProceso_pruebas->setIdProceso($cEnvios->getIdProceso());
			$sqlProceso_pruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
			$rsProceso_pruebas = $conn->Execute($sqlProceso_pruebas);

			$bEcases=false;	// Tiene o no una prueba de tipo Ecases
      $_iCaseUsuarioIdioma=1;
      $_sCaseSimulacion="";
      $_iCaseCliente=0;	//Default People
      $_iCaseSimulacion=0;
      $sPruebas="";
      while (!$rsProceso_pruebas->EOF)
			{
        $sPruebas.="," . $rsProceso_pruebas->fields['idPrueba'];
        //Miramos si alguna de las pruebas es de tipo Ecases
        $cPruebas = new Pruebas();
        $cPruebas->setIdPrueba($rsProceso_pruebas->fields['idPrueba']);
        $cPruebas->setIdPruebaHast($rsProceso_pruebas->fields['idPrueba']);
        $cPruebas->setBajaLog(0);
        $cPruebas->setBajaLogHast(0);
        $sSQL = $cPruebasDB->readLista($cPruebas);
        //echo $sSQL;exit;
        $rs = $conn->Execute($sSQL);
        if ($rs->fields['idTipoPrueba'] == "17")	//Prueba de tipo ECases
        {
        	$bEcases=true;
        	if ($rsProceso_pruebas->fields['codIdiomaIso2'] == "en"){
        		$_iCaseUsuarioIdioma=2;
        	}
        	$_sCaseSimulacion = $rs->fields['codigo'];
        }
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
    			}else{
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
            //echo "->" . $iDonglesADescontar;exit;
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
				//Es de prepago hay que verificar los dongles
				if ($iDonglesADescontar > $iDonglesDeEmpresa){
					//Hay que descontar mas dongles que los que tiene cargados la empresa,
					//Se lanza mensaje de error.
					$sMSG_JS_ERROR="" . $cEmpresas->getNombre() . " - " . constant("STR_NO_DISPONE_DE_SUFICIENTES_UNIDADES_PARA_EFECTUAR_LA_OPERACION") . ".\\n";
					$sMSG_JS_ERROR.="\\t" . constant("STR_UNIDADES_DISPONIBLES") . ": " . $iDonglesDeEmpresa . " " . constant("STR_UNIDADES") . ".\\n";
					$sMSG_JS_ERROR.="\\t" . constant("STR_UNIDADES_A_CONSUMIR") . ": " . $iDonglesADescontar . " " . constant("STR_UNIDADES") . ".\\n\\n";
					$sMSG_JS_ERROR.="" . constant("STR_POR_FAVOR_RECARGUE_UN_MINIMO_DE") . ":\\n ";
					$sMSG_JS_ERROR.="\\t" . ($iDonglesADescontar - $iDonglesDeEmpresa) . " " . constant("STR_UNIDADES") . ".\\n ";
					$bReenviar = "0";
					$_POST['fiTotalCandidatosInicio']=0;
					$_POST['fiTotalEnviados']=0;
					$_POST['fMailsNOEnviados']="";
					$_POST['fMailsNOEnviadosLessYear']="";
				?><script language="javascript" type="text/javascript">alert("<?php echo $sMSG_JS_ERROR;?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
					$_POST['MODO']=constant("MNT_ALTA");
					include('Template/EnviarCorreos/mntenviarcorreosa.php');
					break;
				}
			}else{
				//Es de contrato, No se hacer la verificación de dongles
			}
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

			$iTotal = $rsCandidatos->RecordCount();
			$iFallidos = 0;
			$aMailsNOEnviados = (empty($sMailsNOEnviados)) ? array() : explode(",", $sMailsNOEnviados);
			$aMailsNOEnviadosLessYear = (empty($sMailsNOEnviadosLessYear)) ? array() : explode(",", $sMailsNOEnviadosLessYear);
			// echo "<pre>";
			// print_r($aMailsNOEnviadosLessYear);
			// echo "</pre>";
			// echo "<br />cuantos: " . sizeof($aMailsNOEnviadosLessYear);
			// echo "<br />IdTipoCorreo: " . $cEnvios->getIdTipoCorreo();
			switch ($cEnvios->getIdTipoCorreo())
			{
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

						//--------------------------- Para Ecases
						if ($bEcases)
						{
							$idUsrCase=0;

							//Miramos si la simulación existe y sacamos el id de cliente
							$sSQLECases = "SELECT * FROM simulacion WHERE nombre=" . $connECases->qstr($_sCaseSimulacion, false);
							$rsCeCases = $connECases->Execute($sSQLECases);

							if ($rsCeCases->NumRows() > 0){
								while (!$rsCeCases->EOF){
									$_iCaseCliente =$rsCeCases->fields['cliente_id'];
									$_iCaseSimulacion=$rsCeCases->fields['id'];
									$rsCeCases->MoveNext();
								}
							}
							//Miramos si ya está dado de alta
							$sSQLECases = "SELECT * FROM users WHERE email=" . $connECases->qstr($cCandidatos->getMail(), false);
							$rsCeCases = $connECases->Execute($sSQLECases);
							if (function_exists('password_hash')) {
								// php >= 5.5
								$sPass = password_hash($newPass, PASSWORD_BCRYPT);
							} else {
								$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
								$salt = base64_encode($salt);
								$salt = str_replace('+', '.', $salt);
								$sPass = crypt($newPass, '$2y$10$' . $salt . '$');
							}
							$sTK=$cUtilidades->quickRandom(60);

							if ($rsCeCases->NumRows() > 0)
							{
								//Existe, le cambiamos la Pass y el idioma por que es con el que tiene que realizar la simulación
								$sSQLECases = 'UPDATE users SET
								password=' . $connECases->qstr($sPass, false) . '
								,alternativo=' . $connECases->qstr($sTK, false) . '
								,id_idiom=' . $connECases->qstr($_iCaseUsuarioIdioma, false) . '
								WHERE ID=' . $rsCeCases->fields['ID'];
								$rsUCase = $connECases->Execute($sSQLECases);
// 								$sTypeError	=	date('d/m/Y H:i:s') . " TRAZA::[e-Cases] TO::[" . $cCandidatos->getMail() . "]:: " . $sSQLECases;
// 								error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
								$idUsrCase=$rsCeCases->fields['ID'];
							}else{
								//No existe, lo insertamos
								$sSQLECases = '
								INSERT INTO users (id_rol, id_cliente, id_idiom, first_name, last_name, password, email, trato, state, created_at, updated_at, remember_token, evaluado, temporal, finalizado, alternativo, logged)
								VALUES (\'3\', \'' . $_iCaseCliente . '\', \'' . $_iCaseUsuarioIdioma . '\', \'' . $cCandidatos->getNombre() . '\', \'' . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . '\', \'' . $sPass .'\', \'' . $cCandidatos->getMail() . '\', \'1\', \'1\', now(), now(), NULL, \'0\', \'0\', \'0\', \'' . $sTK . '\', \'0\');';

								$rsUCase = $connECases->Execute($sSQLECases);
								$sSQLECases = "SELECT * FROM users WHERE email=" . $connECases->qstr($cCandidatos->getMail(), false);
								$rsCeCases = $connECases->Execute($sSQLECases);
// 								$sTypeError	=	date('d/m/Y H:i:s') . " TRAZA::[e-Cases] TO::[" . $cCandidatos->getMail() . "]:: " . $sSQLECases;
// 								error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
								if ($rsCeCases->NumRows() <= 0)
								{
									$sTypeError	=	date('d/m/Y H:i:s') . " AltaECases::[Error alta usuario e-Cases] TO::[" . $cCandidatos->getMail() . "]";
									error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
								}else {
									while (!$rsCeCases->EOF){
										$idUsrCase=$rsCeCases->fields['ID'];
										$rsCeCases->MoveNext();
									}
								}
							}

							if ($idUsrCase !=0 && $_iCaseCliente !=0 && $_iCaseSimulacion!=0){
								//Miramos si ya está asignado a la simulación, si no lo está lo asignamos
								$sSQLECases = "SELECT * FROM simulacion_usuario WHERE id_simulacion=" . $connECases->qstr($_iCaseSimulacion, false) . " AND id_usuario=" . $connECases->qstr($idUsrCase, false);
								$rsCeCases = $connECases->Execute($sSQLECases);
								if ($rsCeCases->NumRows() <= 0){
									$sSQLECases = 'INSERT INTO simulacion_usuario (id_simulacion, id_usuario, id_evaluador, mail_bienvenida, estado, tiempo, finalizado)
									VALUES (\'' . $_iCaseSimulacion . '\', \'' . $idUsrCase . '\', NULL, \'0\', \'0\', \'00:00:00\', \'0\');';
									$rsUCase = $connECases->Execute($sSQLECases);
								}
							}else{
								$sTypeError	=	date('d/m/Y H:i:s') . " AltaECases::[Error alta e-Cases] TO::[" . $cCandidatos->getMail() . "]";
								error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
							}
						}
						//FIN ---------------------- Para Ecases

						//Administrado y enviar todas las contraseñas en 1 sólo correo
						//echo "<br />EnvioContrasenas:" . $EnvioContrasenas;
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
							$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
							$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
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
								//echo "<br />Cuerpo asunto OK.";
								if (!in_array(strtolower($cCandidatos->getMail()), $aMailsNOEnviados) && !in_array(strtolower($cCandidatos->getMail()), $aMailsNOEnviadosLessYear))
								{
									$_brevisarRepes=false;
									if ($cEmpresas->getCandidatosRepetidores() != ""){
										$_brevisarRepes = validaCandidatosYaEvaluadosLessYear($cCandidatos, $sPruebas, $rsProceso_informes, $cEnvios);
									}
									if (!$_brevisarRepes)
									{
										if (!enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
											//informamos de los emails q no se han podido enviar.
											$iFallidos++;
											$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
											$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
											$sMailsNOEnviados.= "," . $cCandidatos->getMail();
										}else{
											//Actualizamos el usuario con la nueva contraseña
											//Lo ponemos como informado
											$cCandidatos->setPassword($newPass);
											$cCandidatos->setInformado(1);
											$OK = $cCandidatosDB->modificar($cCandidatos);
											$cEnvios_hist	= new Envios();
											$cEnvios_hist->setIdEmpresa($cEnvios->getIdEmpresa());
											$cEnvios_hist->setIdProceso($cEnvios->getIdProceso());
											$cEnvios_hist->setIdTipoCorreo($cEnvios->getIdTipoCorreo());
											$cEnvios_hist->setIdCorreo($cEnvios->getIdCorreo());
											$cEnvios_hist->setIdCandidato($rsCandidatos->fields['idCandidato']);
											$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
											$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
											$cEnviosDB->insertar($cEnvios_hist);
											$sTypeError	=	date('d/m/Y H:i:s') . " Correo enviado FROM::[" . $sFrom . "] TO::[" . $cCandidatos->getMail() . "]";
											error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));

										}
									}else {
											$iFallidos++;
											$sMSG_JS_ERROR=constant("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA") . "\\n";
											$sNOEnviadosLessYear.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
											$sMailsNOEnviadosLessYear.= "," . $cCandidatos->getMail();
									}
								}
							}else{
								//informamos de los emails q no se han podido enviar.
								$iFallidos++;
								//echo "<br />MSG400 empty BODY fallidos:" . $iFallidos;
								$sMSG_JS_ERROR="MSG400 empty BODY::\\t" . $cCandidatos->getMail();
								$sMSG_JS_RESUMEN.="\\n" . $sMSG_JS_ERROR;
								$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
								$sMailsNOEnviados.= "," . $cCandidatos->getMail();
							}
						}

						$i++;
						$rsCandidatos->MoveNext();
					}
					//Administrado y enviar todas las contraseñas en 1 sólo correo
					//echo "<br />EnvioContrasenas:" . $EnvioContrasenas;
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
								$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
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
							$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
							$sMailsNOEnviados.= "," . $cCandidatos->getMail();
							$iFallidos = $iTotal;
						}
						$rsCandidatos->MoveNext();
					}
					if (empty($sNOEnviados) && empty($sNOEnviadosLessYear))
					{
						//Miramos que tenga el corre definido en el cuerpo
						//la etiqueta @acceso_password@
					    $sLiteral = "@acceso_password@";
        				if (strpos($cCorreos_proceso->getCuerpo(), $sLiteral)){
							//continue;
						}else{
							$iFallidos++;
							$sMSG_JS_ERROR="El correo de confirmación no contiene la etiqueta adecuada de @acceso_password@\\nSE HA CANCELADO EL PROCESO DE ENVÍO.\\n";
							$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
							$sMailsNOEnviados.= "," . $cCandidatos->getMail();
							$iFallidos = $iTotal;
						}
						if (empty($sNOEnviados) && empty($sNOEnviadosLessYear))
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
									$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
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
									$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
									$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
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
			$newId	= $sNOEnviados . $sNOEnviadosLessYear;
			//echo "<br />newId:" . $newId;
			if (!empty($sMailsNOEnviados)){
				//echo "<br />sMailsNOEnviados:" . $sMailsNOEnviados;
				if (substr($sMailsNOEnviados, 0, 1) == ","){
					$sMailsNOEnviados = substr($sMailsNOEnviados, 1);
					$aMailsNOEnviados = explode(",", $sMailsNOEnviados);
				}
				//echo "<br />sMailsNOEnviados 2 ->:" . $sMailsNOEnviados;
				$_POST['fMailsNOEnviados'] =$sMailsNOEnviados;
			}
			//echo "<br />sMailsNOEnviadosLessYear:" . $sMailsNOEnviadosLessYear;
			//print_r($aMailsNOEnviadosLessYear);
			//echo "<br />---------->sizeof(aMailsNOEnviadosLessYear)" . sizeof($aMailsNOEnviadosLessYear);
			if (!empty($sMailsNOEnviadosLessYear)){
				//echo "<br />!empty(sMailsNOEnviadosLessYear):" . $sMailsNOEnviadosLessYear;
				if (substr($sMailsNOEnviadosLessYear, 0, 1) == ","){
					$sMailsNOEnviadosLessYear = substr($sMailsNOEnviadosLessYear, 1);
					$aMailsNOEnviadosLessYear = explode(",", $sMailsNOEnviadosLessYear);
				}
				$_POST['fMailsNOEnviadosLessYear'] =$sMailsNOEnviadosLessYear;
			}
			$iTotalEnviados += $iTotal;
			$_POST['fiTotalEnviados'] =$iTotalEnviados;

			if (empty($newId))
			{
				if ($iTotalEnviados >= $iTotalCandidatosInicio)
				{
					$bReenviar = "0";
			?>
 				<script language="javascript" type="text/javascript">alert("<?php echo $iTotalCandidatosInicio-sizeof($aMailsNOEnviados)-sizeof($aMailsNOEnviadosLessYear);?> <?php echo constant("STR_DE");?> <?php echo $iTotalCandidatosInicio;?> <?php echo constant("STR_ENVIADOS_CORRECTAMENTE");?>. \n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script>
<?php
					$_POST['fiTotalCandidatosInicio']=0;
					$_POST['fiTotalEnviados']=0;
					$_POST['fMailsNOEnviados']="";
					$_POST['fMailsNOEnviadosLessYear']="";
				}else{
//					echo "<br />iTotalEnviados::" . $iTotalEnviados;
//					echo "<br />iTotalCandidatosInicio::" . $iTotalCandidatosInicio;
				}
				if (!$bTodos)
				{

					$cEntidad = readLista($cEntidad);
					if ($cEntidad->getIdEmpresa() == ""){
							$cEntidad->setIdEmpresa($sHijos);
					}
					if (!empty($_POST['candidatos_next_page']) && $_POST['candidatos_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'candidatos');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/EnviarCorreos/mntenviarcorreosl.php');
				}else{
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/EnviarCorreos/mntenviarcorreosa.php');
				}
			}else{
				// echo "<br />iTotalEnviados:" . $iTotalEnviados . " >= iTotalCandidatosInicio:" . $iTotalCandidatosInicio;
				// echo "<br />***---------->sizeof(aMailsNOEnviadosLessYear)" . sizeof($aMailsNOEnviadosLessYear);
				if ($iTotalEnviados >= $iTotalCandidatosInicio)
				{
					if (!empty($sMailsNOEnviados) || !empty($sMailsNOEnviadosLessYear))
					{
						//echo "<br />*-*-*-sMailsNOEnviados:" . $sMailsNOEnviados . " || sMailsNOEnviadosLessYear:" . $sMailsNOEnviadosLessYear;
						if (!empty($sMailsNOEnviados)){
							$aMailsNOEnviados = explode(",", $sMailsNOEnviados);
						}
						if (!empty($sMailsNOEnviadosLessYear)){
							$aMailsNOEnviadosLessYear = explode(",", $sMailsNOEnviadosLessYear);
						}
						//echo "<br />>>>>>>>>>>>>>>sizeof(aMailsNOEnviados):" . sizeof($aMailsNOEnviados);
						//echo "<br />>>>>>>>>>>>>>>sizeof(aMailsNOEnviadosLessYear):" . sizeof($aMailsNOEnviadosLessYear);
						if (sizeof($aMailsNOEnviados) > 0 ){
							$sMSG_JS_ERROR="-" . constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
							$sNOEnviados= "";
							for ($i=0, $max = sizeof($aMailsNOEnviados); $i < $max; $i++)
							{
								$sNOEnviados.=  " [" . $aMailsNOEnviados[$i] . "]\\n";
							}
						}
						//echo "<br />sizeof(aMailsNOEnviadosLessYear):" . sizeof($aMailsNOEnviadosLessYear);
						if (sizeof($aMailsNOEnviadosLessYear) > 0 ){
							$sMSG_JS_ERROR="-" . constant("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA") . "\\n";

							$sNOEnviadosLessYear= "";
							for ($i=0, $max = sizeof($aMailsNOEnviadosLessYear); $i < $max; $i++)
							{
							$sNOEnviadosLessYear.=  " [" . $aMailsNOEnviadosLessYear[$i] . "]\\n";
							}
						}
						$bReenviar = "0";
						$_POST['fiTotalCandidatosInicio']=0;
						$_POST['fiTotalEnviados']=0;
						$_POST['fMailsNOEnviados']="";
						$_POST['fMailsNOEnviadosLessYear']="";

						//echo "<br />sizeof(aMailsNOEnviadosLessYear):" . sizeof($aMailsNOEnviadosLessYear);
						if (sizeof($aMailsNOEnviadosLessYear) > 0 ){
							?>
								<script language="javascript" type="text/javascript">alert("<?php echo $iTotalCandidatosInicio-sizeof($aMailsNOEnviados)-sizeof($aMailsNOEnviadosLessYear);?> <?php echo constant("STR_DE");?> <?php echo $iTotalCandidatosInicio;?> <?php echo constant("STR_ENVIADOS_CORRECTAMENTE");?>. \n<?php echo $cEntidadDB->ver_errores();?>\n <?php echo $sMSG_JS_ERROR;?><?php echo $sNOEnviadosLessYear;?><?php echo $sMSG_JS_RESUMEN;?>","<?php echo constant("STR_NOTIFICACION");?>");</script>
							<?php
						}else {
							?>
							<script language="javascript" type="text/javascript">alert("<?php echo $iTotalCandidatosInicio-sizeof($aMailsNOEnviados)-sizeof($aMailsNOEnviadosLessYear);?> <?php echo constant("STR_DE");?> <?php echo $iTotalCandidatosInicio;?> <?php echo constant("STR_ENVIADOS_CORRECTAMENTE");?>. \n<?php echo $cEntidadDB->ver_errores();?>\n <?php echo $sMSG_JS_ERROR;?><?php echo $sNOEnviados;?><?php echo $sMSG_JS_RESUMEN;?>","<?php echo constant("STR_NOTIFICACION");?>");</script>
							<?php
						}
					}
				}
				$_POST['MODO']=constant("MNT_ALTA");
				if (sizeof($aMailsNOEnviadosLessYear) > 0 ){
					include('Template/EnviarCorreos/mntcandidatosR.php');
				}else{
					include('Template/EnviarCorreos/mntenviarcorreosa.php');
				}
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
			}
			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
			}
			if (!empty($_POST['candidatos_next_page']) && $_POST['candidatos_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'candidatos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/EnviarCorreos/mntenviarcorreosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/EnviarCorreos/mntenviarcorreosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/EnviarCorreos/mntenviarcorreosa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/EnviarCorreos/mntenviarcorreos.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
			}
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				if (!empty($_POST['candidatos_next_page']) && $_POST['candidatos_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'candidatos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/EnviarCorreos/mntenviarcorreosl.php');
			break;
		case 888:
				$cEntidad = readLista($cEntidad);
				if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
				}
				if (!empty($_POST["fDescargaInforme"]))	//Volver a evaluar menos la prueba seleccionada (Enviar resto de pruebas)
				{
					$iVolverEvaluarComunicar=0;
					$DescargaInforme = $_POST["fDescargaInforme"];
					if (is_array($DescargaInforme))
					{
						//print_r($DescargaInforme);exit;
						$_ficheroPDF="";
						$bSended = false;
						$iTotalVolverEvaluar=sizeof($DescargaInforme);
						//echo "<br >---->" . $iTotalVolverEvaluar;exit;
						for ($i=0, $max = sizeof($DescargaInforme); $i < $max; $i++)
						{
							$_ficheroPDF="";
 							if (!empty($DescargaInforme[$i]))
 							{
 								$aValores = explode(",", $DescargaInforme[$i]);
								//Contamos cuantas pruebas tiene el destino
								$cPI	= new Proceso_informes();  // Entidad
								$cPI->setIdEmpresa($aValores[3]);
								$cPI->setIdProceso($aValores[4]);
								$sSQLPI= $cProceso_informesDB->readLista($cPI);
								//echo $sSQLPI;exit;
								$rsPI = $conn->Execute($sSQLPI);
								$iTotalPI	=	$rsPI->RecordCount();
								//Sacamos la información del candidato Destino
								$cCandidato = new Candidatos();
								$cCandidato->setIdEmpresa($aValores[3]);
								$cCandidato->setIdProceso($aValores[4]);
								$cCandidato->setIdCandidato($aValores[5]);
								$cCandidato = $cCandidatosDB->readEntidad($cCandidato);

								//Validamos cuantas le quedarían por realizaran

								$iRestoPruebas = ValidaRestoPruebas($cCandidato, $DescargaInforme, $rsPI);
								//echo "iRestoPruebas::".$iRestoPruebas;exit;
 								$_sEmpresaDest = $comboEMPRESAS->getDescripcionCombo($aValores[3]);
 								$cProcDest = new Procesos();
 								$cProcDest->setIdEmpresa($aValores[3]);
 								$cProcDest->setIdProceso($aValores[4]);
 								$cProcDest = $cProcesosDB->readEntidad($cProcDest);
 								$comboDESC_CANDIDATOS	= new Combo($conn,"_fDescCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","","","idEmpresa=" . $conn->qstr($aValores[3], false) . " AND idProceso=" . $conn->qstr($aValores[4], false) . " AND idCandidato=" . $conn->qstr($aValores[5], false),"","fecMod");
 								$_sDescCandidatoDest = $comboDESC_CANDIDATOS->getDescripcionCombo($aValores[5]);
								//echo "<br />-->" . $_sDescCandidatoDest;
								//print_r($aValores);
								//Replicamos las respuestas origen
								$_sDesdePK =$aValores[0] . "," . $aValores[1] . "," . $aValores[2];

								$sSQL = " INSERT INTO respuestas_pruebas (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, descCandidato, codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, finalizado, leidoInstrucciones, leidoEjemplos, minutos_test, segundos_test, minutos2_test, segundos2_test, pantalla, campoLibre, replicada, fecAlta, fecMod, usuAlta, usuMod) ";
								$sSQL .= " SELECT " . $aValores[3] . ", " . $conn->qstr($_sEmpresaDest, false) . ", " . $aValores[4] . ", " . $conn->qstr($cProcDest->getNombre(), false) . ", " . $aValores[5] . ", " . $conn->qstr($_sDescCandidatoDest, false) . ", codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, finalizado, leidoInstrucciones, leidoEjemplos, minutos_test, segundos_test, minutos2_test, segundos2_test, pantalla, campoLibre, " . $conn->qstr($_sDesdePK, false) . " , fecAlta, now(), 0, 0 ";
								$sSQL .= " FROM respuestas_pruebas ";
								$sSQL .= " WHERE ";
								$sSQL .= " idEmpresa= " . $aValores[0];
								$sSQL .= " AND idProceso= " . $aValores[1];
								$sSQL .= " AND idCandidato= " . $aValores[2];
								$sSQL .= " AND idPrueba= " . $aValores[8];
								//Borramos por si ha y respuestas a medias
								$sSQLDel = " DELETE FROM respuestas_pruebas ";
								$sSQLDel .= " WHERE ";
								$sSQLDel .= " idEmpresa= " . $aValores[3];
								$sSQLDel .= " AND idProceso= " . $aValores[4];
								$sSQLDel .= " AND idCandidato= " . $aValores[5];
								$sSQLDel .= " AND idPrueba= " . $aValores[8];
								//echo $sSQLDel;exit;
								$conn->Execute($sSQLDel);
								//echo $sSQL;exit;
								if($conn->Execute($sSQL) === false){
									$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EnviarCorreos][Replicar candidatos][respuestas_pruebas]";
									echo $sTypeError . $sSQL;exit;
									error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
								}else{
									//Borramos por si ha y respuestas a medias
									$sSQLDel = " DELETE FROM respuestas_pruebas_items ";
									$sSQLDel .= " WHERE ";
									$sSQLDel .= " idEmpresa= " . $aValores[3];
									$sSQLDel .= " AND idProceso= " . $aValores[4];
									$sSQLDel .= " AND idCandidato= " . $aValores[5];
									$sSQLDel .= " AND idPrueba= " . $aValores[8];
									$conn->Execute($sSQLDel);
									//Replicamos los items respondidos
									$sSQL = " INSERT INTO respuestas_pruebas_items (idEmpresa, descEmpresa, idProceso, descProceso, idCandidato, descCandidato, codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, idItem, descItem, idOpcion, descOpcion, codigo, orden, valor, fecAlta, fecMod, usuAlta, usuMod) ";
									$sSQL .= " SELECT " . $aValores[3] . ", " . $conn->qstr($_sEmpresaDest, false) . ", " . $aValores[4] . ", " . $conn->qstr($cProcDest->getNombre(), false) . ", " . $aValores[5] . ", " . $conn->qstr($_sDescCandidatoDest, false) . ", codIdiomaIso2, descIdiomaIso2, idPrueba, descPrueba, idItem, descItem, idOpcion, descOpcion, codigo, orden, valor, fecAlta, now(), 0, 0 ";
									$sSQL .= " FROM respuestas_pruebas_items ";
									$sSQL .= " WHERE ";
									$sSQL .= " idEmpresa= " . $aValores[0];
									$sSQL .= " AND idProceso= " . $aValores[1];
									$sSQL .= " AND idCandidato= " . $aValores[2];
									$sSQL .= " AND idPrueba= " . $aValores[8];
									if($conn->Execute($sSQL) === false){
										$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EnviarCorreos][Replicar candidatos][respuestas_pruebas_items]";
										echo $sTypeError . $sSQL;exit;
										error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
									}
								}	// FIN - Replicamos los items respondidos

								//ponemos en consumos como una replica sin consumo y Mandamos generar el informe


								//echo $aValores[0] . " <-> " . $aValores[1] . " -> " . $iTotalPI;exit;
								//Sacamos los datos del informe de la prueba
								$cProceso_informesR	= new Proceso_informes();  // Entidad
								$cProceso_informesR->setIdEmpresa($aValores[0]);
								$cProceso_informesR->setIdProceso($aValores[1]);
								$cProceso_informesR->setIdPrueba($aValores[8]);
								$rsProceso_informesR = $conn->Execute($cProceso_informesDB->readLista($cProceso_informesR));

								$_sBaremo ="";
								$_idBaremo =$rsProceso_informesR->fields['idBaremo'];
								$_idBaremo = (!empty($_idBaremo)) ? $_idBaremo : "1";
								//Sacamos el literal del baremo para pintarlo en los informes si lo tiene
								$cBaremos = new Baremos();
								$cBaremos->setIdBaremo($_idBaremo);
								$cBaremos->setIdPrueba($rsProceso_informesR->fields['idPrueba']);
								$cBaremos = $cBaremosDB->readEntidad($cBaremos);
								$_sBaremo = $cBaremos->getNombre();

								$cTipos_informes = new  Tipos_informes();
								$cTipos_informes->setCodIdiomaIso2($rsProceso_informesR->fields['codIdiomaInforme']);
								$cTipos_informes->setIdTipoInforme($rsProceso_informesR->fields['idTipoInforme']);
								$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);

								$cPruebas = new Pruebas();
								$cPruebas->setIdPrueba($rsProceso_informesR->fields['idPrueba']);
								$cPruebas->setCodIdiomaIso2($rsProceso_informesR->fields['codIdiomaIso2']);
								$cPruebas = $cPruebasDB->readEntidad($cPruebas);

								$cConsumos = new Consumos();
								$cConsumos->setIdEmpresa($aValores[3]);
								$cConsumos->setIdProceso($aValores[4]);
								$cConsumos->setIdCandidato($aValores[5]);
								$cConsumos->setCodIdiomaIso2($rsProceso_informesR->fields['codIdiomaIso2']);
								$cConsumos->setIdPrueba($aValores[8]);
								$cConsumos->setCodIdiomaInforme($rsProceso_informesR->fields['codIdiomaInforme']);
								$cConsumos->setIdTipoInforme($rsProceso_informesR->fields['idTipoInforme']);
								$cConsumos->setIdBaremo($_idBaremo);

								$cConsumos->setNomEmpresa($_sEmpresaDest);
								$cConsumos->setNomProceso($cProcDest->getNombre());
								$cConsumos->setNomCandidato($cCandidato->getNombre());
								$cConsumos->setApellido1($cCandidato->getApellido1());
								$cConsumos->setApellido2($cCandidato->getApellido2());
								$cConsumos->setDni($cCandidato->getDni());
								$cConsumos->setMail($cCandidato->getMail());
								$cConsumos->setNomPrueba($cPruebas->getNombre());
								$cConsumos->setNomInforme($cTipos_informes->getNombre());
								$cConsumos->setNomBaremo($_sBaremo);

								//$cConsumos->setConcepto(constant("STR_REPLICADA") . " " . $_sDesdePK);
								$cConsumos->setConcepto(constant("STR_REPLICADA"));

								$cConsumos->setUnidades("0");
								$cConsumos->setUsuAlta("0");
								$cConsumos->setUsuMod("0");
								//Revisamos si ya se le ha cobrado, si el Candidato actualiza la página, no hay que cobrar dos veces
								$sqlConsumos = $cConsumosDB->readLista($cConsumos);
								//echo $sqlConsumos;exit;
								$rsConsRead = $conn->Execute($sqlConsumos);
								$iConsRead = $rsConsRead->NumRows();
								//Si ya tiene consumo vemos si es de una replica a medias y la eliminamos
								//echo "1->iConsRead::" . $iConsRead;exit;
								while (!$rsConsRead->EOF)
								{
									if ($rsConsRead->fields['concepto'] == constant("STR_REPLICADA"))
									{
										$idC = $rsConsRead->fields['idConsumo'];
										//Borramos por si ha y respuestas a medias
										$sSQLDel = str_replace("SELECT *", "DELETE", $sqlConsumos);

										//echo "sSQLDel::" . $sSQLDel;exit;
										$conn->Execute($sSQLDel);
										$iConsRead=0;
									}
									$rsConsRead->MoveNext();
								}

								if ($iConsRead <= 0)
								{

									$idConsumo = $cConsumosDB->insertar($cConsumos);
									//echo "1A->iConsRead::" . $iConsRead;exit;

									//Mandamos Generar el informe para que esté disponible en la descarga
									//Parámetros por orden
									// es proceso 1
									// MODO 627
									// fIdTipoInforme Prisma Informe completo 3
									// fCodIdiomaIso2 Idioma del informe es
									// fIdPrueba Prueba prisma 24
									// fIdEmpresa Id de empresa  3788
									// fIdProceso Id del proceso 3
									// fIdCandidato Id Candidato 1
									// fCodIdiomaIso2Prueba Idioma prueba es
									// fIdBaremo Id Baremo, prisma no tiene , le pasamos 1
									//$cmd = constant("DIR_FS_PATH_PHP") . ' ' . str_replace("Empresa", "Admin", constant("DIR_FS_DOCUMENT_ROOT")) . '/Informes_candidato.php 1 627 ' . $cInformes_pruebas->getIdTipoInforme() . ' ' . $cInformes_pruebas->getCodIdiomaIso2() . ' ' . $_POST['fIdPrueba'] . ' ' . $_POST['fIdEmpresa'] . ' ' . $_POST['fIdProceso'] . ' ' . $_POST['fIdCandidato'] . ' ' . $_POST['fCodIdiomaIso2'] . ' 1';
									//$cUtilidades->execInBackground($cmd);

									$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato.php?MODO=627&fIdTipoInforme=' . $rsProceso_informesR->fields['idTipoInforme'] . '&fCodIdiomaIso2=' . $rsProceso_informesR->fields['codIdiomaInforme'] . '&fIdPrueba=' . $rsProceso_informesR->fields['idPrueba'] . '&fIdEmpresa=' . $aValores[3] . '&fIdProceso=' . $aValores[4] . '&fIdCandidato=' . $aValores[5] . '&fCodIdiomaIso2Prueba=' . $rsProceso_informesR->fields['codIdiomaIso2'] . '&fIdBaremo=' . $_idBaremo;
									//echo "<br />" . $cmdPost;
									$cUtilidades->backgroundPost($cmdPost);
									file_get_contents($cmdPost);
//echo "<br />iRestoPruebas::" . $iRestoPruebas;exit;
//Enviamos el correo
if ($iRestoPruebas > 0 )
{	//SÓLO MANDAMOS CORREO SI TIENE QUE REALIZAR ALGUNA PRUEBA MÁS
	// echo "<br />iTotalPI:: " . $iTotalPI;
	// echo "<br />iRestoPruebas:: " . $iRestoPruebas;
	// exit;
						if (!$bSended)
						{
									$bSended=true;
									//Sacamos la información del candidato
									$cCandidato = new Candidatos();
									$cCandidato->setIdEmpresa($aValores[3]);
									$cCandidato->setIdProceso($aValores[4]);
									$cCandidato->setIdCandidato($aValores[5]);
									$cCandidato = $cCandidatosDB->readEntidad($cCandidato);

									$newPass= $cUtilidades->newPass();
									$sUsuario=$cCandidato->getMail();

									$sIdEmpresa= $cCandidato->getIdEmpresa();
									$sIdProceso= $cCandidato->getIdProceso();
									$sIdTipoCorreo = $aValores[6];
									$sIdCorreo = $aValores[7];

									$cEmpresas		= new Empresas();
									$cEmpresas->setIdEmpresa($cCandidato->getIdEmpresa());
									$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
									$iDonglesDeEmpresa	=	$cEmpresas->getDongles();
									$bPrepago = $cEmpresas->getPrepago();

									$sFrom=$cEmpresas->getMail();	//Cuenta de correo de la empresa
									$sFromName=$cEmpresas->getNombre();	//Nombre de la empresa

									$cProcesos->setIdProceso($cCandidato->getIdProceso());
									$cProcesos->setIdEmpresa($cCandidato->getIdEmpresa());
									$cProcesos = $cProcesosDB->readEntidad($cProcesos);
									$IdModoRealizacion = $cProcesos->getIdModoRealizacion();
									// EnvioContrasenas == 1 Individuales
									// EnvioContrasenas == 2 Todas en 1 sólo correo
									$EnvioContrasenas = $cProcesos->getEnvioContrasenas();

									$sBody = "";
									$sAltBody = "";
									$sSubject = "";
									//Administrado y enviar todas las contraseñas en 1 sólo correo
									if ($EnvioContrasenas == "2")
									{
										$cNotificaciones	= new Notificaciones();
										$cNotificaciones->setIdTipoNotificacion(8);	//Usuarios y contraseña juntos
										$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
										$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, null, $cProcesos, null, $cCandidato, null, null, $sUsuario, $newPass);

										$sSubject=$cNotificaciones->getAsunto();
										$sBody.=$cNotificaciones->getCuerpo();
										$sAltBody.="\\n" . strip_tags($cNotificaciones->getCuerpo());

										//Actualizamos el usuario con la nueva contraseña
										//Lo ponemos como informado
										$cCandidato->setPassword($newPass);
										$cCandidato->setInformado(1);
										$cCandidato = $cCandidatosDB->modificar($cCandidato);
										$cEnvios_hist	= new Envios();
										$cEnvios_hist->setIdEmpresa($cCandidato->getIdEmpresa());
										$cEnvios_hist->setIdProceso($cCandidato->getIdProceso());
										$cEnvios_hist->setIdTipoCorreo($aValores[6]);
										$cEnvios_hist->setIdCorreo($aValores[7]);
										$cEnvios_hist->setIdCandidato($cCandidato->getIdCandidato());
										$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
										$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
										$cEnviosDB->insertar($cEnvios_hist);
									}else{
										$cCorreos_proceso = new Correos_proceso();
										$cCorreos_proceso->setIdEmpresa($cCandidato->getIdEmpresa());
										$cCorreos_proceso->setIdProceso($cCandidato->getIdProceso());
										$cCorreos_proceso->setIdTipoCorreo($aValores[6]);
										$cCorreos_proceso->setIdCorreo($aValores[7]);
										$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

										$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cCandidato, $cProcesos, $cEmpresas, $sUsuario, $newPass);

										$sSubject=$cCorreos_proceso->getAsunto();
										$sBody=$cCorreos_proceso->getCuerpo();
										$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

										if (!empty($sBody) && !empty($sSubject))
										{
											if (!enviaEmail($cEmpresas, $cCandidato, $cCorreos_proceso, $IdModoRealizacion)){
												//informamos de los emails q no se han podido enviar.
												$iFallidos++;
												$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
												$sNOEnviados.= $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2() . " [" . $cCandidato->getMail() . "]\\n";
											}else{
												//Actualizamos el usuario con la nueva contraseña
												//Lo ponemos como informado
												$cCandidato->setPassword($newPass);
												$cCandidato->setInformado(1);
												$OK = $cCandidatosDB->modificar($cCandidato);
												$cEnvios_hist	= new Envios();
												$cEnvios_hist->setIdEmpresa($cCandidato->getIdEmpresa());
												$cEnvios_hist->setIdProceso($cCandidato->getIdProceso());
												$cEnvios_hist->setIdTipoCorreo($aValores[6]);
												$cEnvios_hist->setIdCorreo($aValores[7]);
												$cEnvios_hist->setIdCandidato($cCandidato->getIdCandidato());
												$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
												$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
												$cEnviosDB->insertar($cEnvios_hist);
												$sTypeError	=	date('d/m/Y H:i:s') . " Correo enviado FROM::[" . $sFrom . "] TO::[" . $cCandidato->getMail() . "]";
												error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
												$iVolverEvaluarComunicar++;

											}
										}else{
											//informamos de los emails q no se han podido enviar.
											$iFallidos++;
											$sMSG_JS_ERROR="MSG400 empty BODY::\\t" . $cCandidato->getMail();
											$sMSG_JS_RESUMEN.="\\n" . $sMSG_JS_ERROR;
											$sNOEnviados.= $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2() . " [" . $cCandidato->getMail() . "]\\n";
										}
									}
					}		//FIN bSended
}else{
	$cCandidato = new Candidatos();
	$cCandidato->setIdEmpresa($aValores[3]);
	$cCandidato->setIdProceso($aValores[4]);
	$cCandidato->setIdCandidato($aValores[5]);
	$cCandidato = $cCandidatosDB->readEntidad($cCandidato);
	$cCandidato->setInformado("2");	//Para que no salga otra vez para enviar correos.
	$cCandidato->setFinalizado("1");
	$cCandidato->setFechaFinalizado($conn->sysTimeStamp);
	$cCandidatosDB->modificar($cCandidato);
	//echo "Sólo tiene " . $iTotalPI . " prueba en el proceso, replicadas " . $iRestoPruebas . ", no se le ha enviado correo y se finaliza proceso.";
	//exit;
	//echo "<br />" . $conn->sysTimeStamp;
}
//FIN de Enviamos el correo

								}	// fin de 	if ($iConsRead <= 0)
								//echo "2->iConsRead::" . $iConsRead;exit;
								$sSinZip = sprintf(constant("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA_ZIP_DESCARGA"),$cProcDest->getNombre());
								$sImgEnviadoOk = "";
								//$aCandidatosYaEvaluadosLessYear[] = $aValores[0] . "," . $aValores[1] . "," . $aValores[2] . "," . $aValores[3] . "," . $aValores[4] . "," . $aValores[5] . "," . $aValores[6] . "," . $aValores[7] . "," . $aValores[8] . "," . $sSinZip . "," . $sImgEnviadoOk;
								$sResultadoRestoPruebas=$sSinZip;
								$sResultadoVolverEvaluar=$sImgEnviadoOk;
								$aCandidatosYaEvaluadosLessYear[] = $aValores[0] . "," . $aValores[1] . "," . $aValores[2] . "," . $aValores[3] . "," . $aValores[4] . "," . $aValores[5] . "," . $aValores[6] . "," . $aValores[7] . "," . $aValores[8] . "," . $aValores[9] . "," . $sResultadoRestoPruebas . "," .$sResultadoVolverEvaluar;
							}else{
								echo "empty(DescargaInforme[i]";
							}	//fin de if (!empty($DescargaInforme[$i]))
						}	// fin del for
						//$iVolverEvaluarComunicar++;
						if (!empty($iTotalVolverEvaluar) && !empty($iVolverEvaluarComunicar)) {
							?>
							<script language="javascript" type="text/javascript">alert("<?php echo $iVolverEvaluarComunicar;?> <?php echo constant("STR_DE");?> <?php echo ($bSended) ? $iVolverEvaluarComunicar : $iTotalVolverEvaluar;?> <?php echo constant("STR_ENVIADOS_CORRECTAMENTE");?>. \n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script>
							<?php
						}
					}else{
						echo "NO Array";exit;
					}	// fin de if (is_array($DescargaInforme))
				}	// fin de Volver a evaluar menos la prueba seleccionada (Enviar resto de pruebas)

				if (!empty($_POST["fVolverEvaluar"]))
				{
					$iVolverEvaluarComunicar=0;
					$VolverEvaluar = $_POST["fVolverEvaluar"];
					if (is_array($VolverEvaluar)){
						$iTotalVolverEvaluar=sizeof($VolverEvaluar);
						$IdModoRealizacion="";
						$sIdEmpresa="";
						$sIdProceso="";
						$sIdTipoCorreo="";
						$sIdCorreo="";
						$cCandidato = new Candidatos();
						$cEmpresas		= new Empresas();
						for ($i=0, $max = sizeof($VolverEvaluar); $i < $max; $i++)
						{
							if (!empty($VolverEvaluar[$i]))
							{
								$aValores = explode(",", $VolverEvaluar[$i]);
								//Sacamos la información del candidato
								$cCandidato = new Candidatos();
								$cCandidato->setIdEmpresa($aValores[3]);
								$cCandidato->setIdProceso($aValores[4]);
								$cCandidato->setIdCandidato($aValores[5]);
								$cCandidato = $cCandidatosDB->readEntidad($cCandidato);
								//Puede tener marcada para una prueba como replicada y hay que borrarlas
								$cConsumos = new Consumos();
								$cConsumos->setIdEmpresa($aValores[3]);
								$cConsumos->setIdProceso($aValores[4]);
								$cConsumos->setIdCandidato($aValores[5]);
								$sqlConsumos = $cConsumosDB->readLista($cConsumos);
								$sqlConsumos .= " AND concepto like " . $conn->qstr("%" . constant("STR_REPLICADA") . "%", false);
								//echo $sqlConsumos;exit;
								$rsConsumosReplicado = $conn->Execute($sqlConsumos);
								$iConConsumosReplicado = $rsConsumosReplicado->NumRows();
								if ($iConConsumosReplicado > 0)
								{
									while (!$rsConsumosReplicado->EOF)
									{
										 $sConceptReplicado = $rsConsumosReplicado->fields['concepto'];
										 $_idPrueba=$rsConsumosReplicado->fields['idPrueba'];

										 $sSQL = " DELETE ";
										 $sSQL .= " FROM respuestas_pruebas ";
										 $sSQL .= " WHERE ";
										 $sSQL .= " idEmpresa= " . $aValores[3];
										 $sSQL .= " AND idProceso= " . $aValores[4];
										 $sSQL .= " AND idCandidato= " . $aValores[5];
										 $sSQL .= " AND idPrueba= " . $_idPrueba;
										 if($conn->Execute($sSQL) === false){
										 	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EnviarCorreos][Borrar respuestas][respuestas_pruebas]";
										 	error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
										 }else{
										 	$sSQL = " DELETE ";
										 	$sSQL .= " FROM respuestas_pruebas_items ";
										 	$sSQL .= " WHERE ";
										 	$sSQL .= " idEmpresa= " . $aValores[3];
										 	$sSQL .= " AND idProceso= " . $aValores[4];
										 	$sSQL .= " AND idCandidato= " . $aValores[5];
										 	$sSQL .= " AND idPrueba= " . $_idPrueba;
										 	if($conn->Execute($sSQL) === false){
										 		$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EnviarCorreos][Borrar items respuestas][respuestas_pruebas_items]";
										 		error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
										 	}else{
										 		//$_sDesdePK =constant("STR_REPLICADA") . " " . $aValores[0] . "," . $aValores[1] . "," . $aValores[2];
												$_sDesdePK =constant("STR_REPLICADA");
										 		$sSQL = " DELETE ";
										 		$sSQL .= " FROM consumos ";
										 		$sSQL .= " WHERE ";
										 		$sSQL .= " idEmpresa= " . $aValores[3];
										 		$sSQL .= " AND idProceso= " . $aValores[4];
										 		$sSQL .= " AND idCandidato= " . $aValores[5];
										 		$sSQL .= " AND idPrueba= " . $_idPrueba;
										 		$sSQL .= " AND concepto= " . $conn->qstr($sConceptReplicado, false);
										 		if($conn->Execute($sSQL) === false){
										 			$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_ALTA") . "][EnviarCorreos][Borrar consumo replicado][consumos]";
										 			error_log($sTypeError . " ->\t" . $sSQL . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
										 		}else{
										 			$cCandidato->setFinalizado("0");
										 			$cCandidato->setFechaFinalizado(NULL);
										 			$cCandidatosDB->modificar($cCandidato);
										 		}
										 	}
										 }
										 $rsConsumosReplicado->MoveNext();
									}
								}
								$newPass= $cUtilidades->newPass();
								$sUsuario=$cCandidato->getMail();

								$sIdEmpresa= $cCandidato->getIdEmpresa();
								$sIdProceso= $cCandidato->getIdProceso();
								$sIdTipoCorreo = $aValores[6];
								$sIdCorreo = $aValores[7];

								$cEmpresas		= new Empresas();
								$cEmpresas->setIdEmpresa($cCandidato->getIdEmpresa());
								$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
								$iDonglesDeEmpresa	=	$cEmpresas->getDongles();
								$bPrepago = $cEmpresas->getPrepago();

								$sFrom=$cEmpresas->getMail();	//Cuenta de correo de la empresa
								$sFromName=$cEmpresas->getNombre();	//Nombre de la empresa

								$cProcesos->setIdProceso($cCandidato->getIdProceso());
								$cProcesos->setIdEmpresa($cCandidato->getIdEmpresa());
								$cProcesos = $cProcesosDB->readEntidad($cProcesos);
								$IdModoRealizacion = $cProcesos->getIdModoRealizacion();
								// EnvioContrasenas == 1 Individuales
								// EnvioContrasenas == 2 Todas en 1 sólo correo
								$EnvioContrasenas = $cProcesos->getEnvioContrasenas();

								$sBody = "";
								$sAltBody = "";
								$sSubject = "";
								//Administrado y enviar todas las contraseñas en 1 sólo correo
								if ($EnvioContrasenas == "2")
								{
									$cNotificaciones	= new Notificaciones();
									$cNotificaciones->setIdTipoNotificacion(8);	//Usuarios y contraseña juntos
									$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
									$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, null, $cProcesos, null, $cCandidato, null, null, $sUsuario, $newPass);

									$sSubject=$cNotificaciones->getAsunto();
									$sBody.=$cNotificaciones->getCuerpo();
									$sAltBody.="\\n" . strip_tags($cNotificaciones->getCuerpo());

									//Actualizamos el usuario con la nueva contraseña
									//Lo ponemos como informado
									$cCandidato->setPassword($newPass);
									$cCandidato->setInformado(1);
									$cCandidato = $cCandidatosDB->modificar($cCandidato);
									$cEnvios_hist	= new Envios();
									$cEnvios_hist->setIdEmpresa($cCandidato->getIdEmpresa());
									$cEnvios_hist->setIdProceso($cCandidato->getIdProceso());
									$cEnvios_hist->setIdTipoCorreo($aValores[6]);
									$cEnvios_hist->setIdCorreo($aValores[7]);
									$cEnvios_hist->setIdCandidato($cCandidato->getIdCandidato());
									$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
									$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
									$cEnviosDB->insertar($cEnvios_hist);
								}else{
									$cCorreos_proceso = new Correos_proceso();
									$cCorreos_proceso->setIdEmpresa($cCandidato->getIdEmpresa());
									$cCorreos_proceso->setIdProceso($cCandidato->getIdProceso());
									$cCorreos_proceso->setIdTipoCorreo($aValores[6]);
									$cCorreos_proceso->setIdCorreo($aValores[7]);
									$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

									$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cCandidato, $cProcesos, $cEmpresas, $sUsuario, $newPass);

									$sSubject=$cCorreos_proceso->getAsunto();
									$sBody=$cCorreos_proceso->getCuerpo();
									$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

									if (!empty($sBody) && !empty($sSubject))
									{
										if (!enviaEmail($cEmpresas, $cCandidato, $cCorreos_proceso, $IdModoRealizacion)){
											//informamos de los emails q no se han podido enviar.
											$iFallidos++;
											$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
											$sNOEnviados.= $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2() . " [" . $cCandidato->getMail() . "]\\n";
										}else{
											//Actualizamos el usuario con la nueva contraseña
											//Lo ponemos como informado

											$cCandidato->setPassword($newPass);
											$cCandidato->setInformado(1);
											$OK = $cCandidatosDB->modificar($cCandidato);
											$cEnvios_hist	= new Envios();
											$cEnvios_hist->setIdEmpresa($cCandidato->getIdEmpresa());
											$cEnvios_hist->setIdProceso($cCandidato->getIdProceso());
											$cEnvios_hist->setIdTipoCorreo($aValores[6]);
											$cEnvios_hist->setIdCorreo($aValores[7]);
											$cEnvios_hist->setIdCandidato($cCandidato->getIdCandidato());
											$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
											$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
											$cEnviosDB->insertar($cEnvios_hist);
											$sTypeError	=	date('d/m/Y H:i:s') . " Correo enviado FROM::[" . $sFrom . "] TO::[" . $cCandidato->getMail() . "]";
											error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
											$iVolverEvaluarComunicar++;
											$sSinZip = "";
											$sImgEnviadoOk = sprintf(constant("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA_ENVIO_OK"),$cProcesos->getNombre());
											//$aCandidatosYaEvaluadosLessYear[] = $aValores[0] . "," . $aValores[1] . "," . $aValores[2] . "," . $aValores[3] . "," . $aValores[4] . "," . $aValores[5] . "," . $aValores[6] . "," . $aValores[7] . "," . $aValores[8] . "," . $sSinZip . "," . $sImgEnviadoOk;
											$sResultadoRestoPruebas=$sSinZip;
											$sResultadoVolverEvaluar=$sImgEnviadoOk;
											$aCandidatosYaEvaluadosLessYear[] = $aValores[0] . "," . $aValores[1] . "," . $aValores[2] . "," . $aValores[3] . "," . $aValores[4] . "," . $aValores[5] . "," . $aValores[6] . "," . $aValores[7] . "," . $aValores[8] . "," . $aValores[9] . "," . $sResultadoRestoPruebas . "," .$sResultadoVolverEvaluar;
										}
									}else{
										//informamos de los emails q no se han podido enviar.
										$iFallidos++;
										$sMSG_JS_ERROR="MSG400 empty BODY::\\t" . $cCandidato->getMail();
										$sMSG_JS_RESUMEN.="\\n" . $sMSG_JS_ERROR;
										$sNOEnviados.= $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2() . " [" . $cCandidato->getMail() . "]\\n";
									}
								}
							}
						}

						$EnvioContrasenas = $cProcesos->getEnvioContrasenas();
						//echo "--**-->> " . $EnvioContrasenas;
						//Administrado y enviar todas las contraseñas en 1 sólo correo
						if ($EnvioContrasenas == "2"){
							if (!empty($sBody)){

								$cCorreos_proceso = new Correos_proceso();
								$cCorreos_proceso->setIdEmpresa($sIdEmpresa);
								$cCorreos_proceso->setIdProceso($sIdProceso);
								$cCorreos_proceso->setIdTipoCorreo($sIdTipoCorreo);
								$cCorreos_proceso->setIdCorreo($sIdCorreo);
								$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

								$cCorreos_proceso->setAsunto($sSubject);
								$cCorreos_proceso->setCuerpo($sBody);

								if (!enviaEmail($cEmpresas, $cCandidato, $cCorreos_proceso, $IdModoRealizacion)){
									//informamos de los emails q no se han podido enviar.
									$iFallidos++;
									$sMSG_JS_ERROR=constant("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES") . "\\n";
									$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
								}
							}
						}
						if (!empty($iTotalVolverEvaluar) && !empty($iVolverEvaluarComunicar)) {
							?>
							<script language="javascript" type="text/javascript">alert("<?php echo $iVolverEvaluarComunicar;?> <?php echo constant("STR_DE");?> <?php echo $iTotalVolverEvaluar;?> <?php echo constant("STR_ENVIADOS_CORRECTAMENTE");?>. \n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script>
							<?php
						}
					}
				}
				//include('Template/EnviarCorreos/mntcandidatosR.php');
				$cEntidad = readLista($cEntidad);
				if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
				}
				if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					if (!empty($_POST['candidatos_next_page']) && $_POST['candidatos_next_page'] > 1){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
				}
				$pager = new ADODB_Pager($conn,$sql,'candidatos');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/EnviarCorreos/mntenviarcorreosl.php');
				break;

		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/EnviarCorreos/mntenviarcorreos.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cEntidad->setDni((isset($_POST["fDni"])) ? $_POST["fDni"] : "");
		$cEntidad->setMail((isset($_POST["fMail"])) ? $_POST["fMail"] : "");
		$cEntidad->setPassword((isset($_POST["fPassword"])) ? $_POST["fPassword"] : "");
		$cEntidad->setIdTratamiento((isset($_POST["fIdTratamiento"])) ? $_POST["fIdTratamiento"] : "");
		$cEntidad->setIdSexo((isset($_POST["fIdSexo"])) ? $_POST["fIdSexo"] : "");
		$cEntidad->setIdEdad((isset($_POST["fIdEdad"])) ? $_POST["fIdEdad"] : "");
		$cEntidad->setFechaNacimiento((isset($_POST["fFechaNacimiento"])) ? $_POST["fFechaNacimiento"] : "");
		$cEntidad->setIdPais((isset($_POST["fIdPais"])) ? $_POST["fIdPais"] : "");
		$cEntidad->setIdProvincia((isset($_POST["fIdProvincia"])) ? $_POST["fIdProvincia"] : "");
		$cEntidad->setIdMunicipio((isset($_POST["fIdMunicipio"])) ? $_POST["fIdMunicipio"] : "");
		$cEntidad->setIdZona((isset($_POST["fIdZona"])) ? $_POST["fIdZona"] : "");
		$cEntidad->setDireccion((isset($_POST["fDireccion"])) ? $_POST["fDireccion"] : "");
		$cEntidad->setCodPostal((isset($_POST["fCodPostal"])) ? $_POST["fCodPostal"] : "");
		$cEntidad->setIdFormacion((isset($_POST["fIdFormacion"])) ? $_POST["fIdFormacion"] : "");
		$cEntidad->setIdNivel((isset($_POST["fIdNivel"])) ? $_POST["fIdNivel"] : "");
		$cEntidad->setIdArea((isset($_POST["fIdArea"])) ? $_POST["fIdArea"] : "");
		$cEntidad->setTelefono((isset($_POST["fTelefono"])) ? $_POST["fTelefono"] : "");
		$cEntidad->setEstadoCivil((isset($_POST["fEstadoCivil"])) ? $_POST["fEstadoCivil"] : "");
		$cEntidad->setNacionalidad((isset($_POST["fNacionalidad"])) ? $_POST["fNacionalidad"] : "");
		$cEntidad->setInformado((isset($_POST["fInformado"])) ? $_POST["fInformado"] : "");
		$cEntidad->setFinalizado((isset($_POST["fFinalizado"])) ? $_POST["fFinalizado"] : "");
		$cEntidad->setFechaFinalizado((isset($_POST["fFechaFinalizado"])) ? $_POST["fFechaFinalizado"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUltimoLogin((isset($_POST["fUltimoLogin"])) ? $_POST["fUltimoLogin"] : "");
		$cEntidad->setToken((isset($_POST["fToken"])) ? $_POST["fToken"] : "");
		$cEntidad->setUltimaAcc((isset($_POST["fUltimaAcc"])) ? $_POST["fUltimaAcc"] : "");
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		$cEntidad->setIdCandidato((isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "") ? $_POST["LSTIdCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_ID_CANDIDATO"), (isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "" ) ? $_POST["LSTIdCandidato"] : "");
		$cEntidad->setIdCandidatoHast((isset($_POST["LSTIdCandidatoHast"]) && $_POST["LSTIdCandidatoHast"] != "") ? $_POST["LSTIdCandidatoHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_CANDIDATO") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdCandidatoHast"]) && $_POST["LSTIdCandidatoHast"] != "" ) ? $_POST["LSTIdCandidatoHast"] : "");
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
		global $comboPROCESOS;
		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $comboPROCESOS->getDescripcionCombo($_POST["LSTIdProceso"]) : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "") ? $_POST["LSTApellido1"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO_1"), (isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "" ) ? $_POST["LSTApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "") ? $_POST["LSTApellido2"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO_2"), (isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "" ) ? $_POST["LSTApellido2"] : "");
		$cEntidad->setDni((isset($_POST["LSTDni"]) && $_POST["LSTDni"] != "") ? $_POST["LSTDni"] : "");	$cEntidad->setBusqueda(constant("STR_NIF"), (isset($_POST["LSTDni"]) && $_POST["LSTDni"] != "" ) ? $_POST["LSTDni"] : "");
		$cEntidad->setMail((isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "") ? $_POST["LSTMail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "" ) ? $_POST["LSTMail"] : "");
		$cEntidad->setPassword((isset($_POST["LSTPassword"]) && $_POST["LSTPassword"] != "") ? $_POST["LSTPassword"] : "");	$cEntidad->setBusqueda(constant("STR_PASSWORD"), (isset($_POST["LSTPassword"]) && $_POST["LSTPassword"] != "" ) ? $_POST["LSTPassword"] : "");
		global $comboTRATAMIENTOS;
		$cEntidad->setIdTratamiento((isset($_POST["LSTIdTratamiento"]) && $_POST["LSTIdTratamiento"] != "") ? $_POST["LSTIdTratamiento"] : "");	$cEntidad->setBusqueda(constant("STR_TRATAMIENTO"), (isset($_POST["LSTIdTratamiento"]) && $_POST["LSTIdTratamiento"] != "" ) ? $comboTRATAMIENTOS->getDescripcionCombo($_POST["LSTIdTratamiento"]) : "");
		global $comboSEXOS;
		$cEntidad->setIdSexo((isset($_POST["LSTIdSexo"]) && $_POST["LSTIdSexo"] != "") ? $_POST["LSTIdSexo"] : "");	$cEntidad->setBusqueda(constant("STR_SEXO"), (isset($_POST["LSTIdSexo"]) && $_POST["LSTIdSexo"] != "" ) ? $comboSEXOS->getDescripcionCombo($_POST["LSTIdSexo"]) : "");
		global $comboEDADES;
		$cEntidad->setIdEdad((isset($_POST["LSTIdEdad"]) && $_POST["LSTIdEdad"] != "") ? $_POST["LSTIdEdad"] : "");	$cEntidad->setBusqueda(constant("STR_EDAD"), (isset($_POST["LSTIdEdad"]) && $_POST["LSTIdEdad"] != "" ) ? $comboEDADES->getDescripcionCombo($_POST["LSTIdEdad"]) : "");
		$cEntidad->setFechaNacimiento((isset($_POST["LSTFechaNacimiento"]) && $_POST["LSTFechaNacimiento"] != "") ? $_POST["LSTFechaNacimiento"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_NACIMIENTO"), (isset($_POST["LSTFechaNacimiento"]) && $_POST["LSTFechaNacimiento"] != "" ) ? $conn->UserDate($_POST["LSTFechaNacimiento"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFechaNacimientoHast((isset($_POST["LSTFechaNacimientoHast"]) && $_POST["LSTFechaNacimientoHast"] != "") ? $_POST["LSTFechaNacimientoHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_NACIMIENTO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFechaNacimientoHast"]) && $_POST["LSTFechaNacimientoHast"] != "" ) ? $conn->UserDate($_POST["LSTFechaNacimientoHast"],constant("USR_FECHA"),false) : "");
		global $comboWI_PAISES;
		$cEntidad->setIdPais((isset($_POST["LSTIdPais"]) && $_POST["LSTIdPais"] != "") ? $_POST["LSTIdPais"] : "");	$cEntidad->setBusqueda(constant("STR_PAIS"), (isset($_POST["LSTIdPais"]) && $_POST["LSTIdPais"] != "" ) ? $comboWI_PAISES->getDescripcionCombo($_POST["LSTIdPais"]) : "");
		global $comboWI_PROVINCIAS;
		$cEntidad->setIdProvincia((isset($_POST["LSTIdProvincia"]) && $_POST["LSTIdProvincia"] != "") ? $_POST["LSTIdProvincia"] : "");	$cEntidad->setBusqueda(constant("STR_PROVINCIA"), (isset($_POST["LSTIdProvincia"]) && $_POST["LSTIdProvincia"] != "" ) ? $comboWI_PROVINCIAS->getDescripcionCombo($_POST["LSTIdProvincia"]) : "");
		global $comboWI_MUNICIPIOS;
		$cEntidad->setIdMunicipio((isset($_POST["LSTIdMunicipio"]) && $_POST["LSTIdMunicipio"] != "") ? $_POST["LSTIdMunicipio"] : "");	$cEntidad->setBusqueda(constant("STR_MUNICIPIO"), (isset($_POST["LSTIdMunicipio"]) && $_POST["LSTIdMunicipio"] != "" ) ? $comboWI_MUNICIPIOS->getDescripcionCombo($_POST["LSTIdMunicipio"]) : "");
		global $comboWI_ZONAS;
		$cEntidad->setIdZona((isset($_POST["LSTIdZona"]) && $_POST["LSTIdZona"] != "") ? $_POST["LSTIdZona"] : "");	$cEntidad->setBusqueda(constant("STR_ZONA"), (isset($_POST["LSTIdZona"]) && $_POST["LSTIdZona"] != "" ) ? $comboWI_ZONAS->getDescripcionCombo($_POST["LSTIdZona"]) : "");
		$cEntidad->setDireccion((isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "") ? $_POST["LSTDireccion"] : "");	$cEntidad->setBusqueda(constant("STR_DIRECCION"), (isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "" ) ? $_POST["LSTDireccion"] : "");
		$cEntidad->setCodPostal((isset($_POST["LSTCodPostal"]) && $_POST["LSTCodPostal"] != "") ? $_POST["LSTCodPostal"] : "");	$cEntidad->setBusqueda(constant("STR_CODIGO_POSTAL"), (isset($_POST["LSTCodPostal"]) && $_POST["LSTCodPostal"] != "" ) ? $_POST["LSTCodPostal"] : "");
		global $comboFORMACIONES;
		$cEntidad->setIdFormacion((isset($_POST["LSTIdFormacion"]) && $_POST["LSTIdFormacion"] != "") ? $_POST["LSTIdFormacion"] : "");	$cEntidad->setBusqueda(constant("STR_FORMACION"), (isset($_POST["LSTIdFormacion"]) && $_POST["LSTIdFormacion"] != "" ) ? $comboFORMACIONES->getDescripcionCombo($_POST["LSTIdFormacion"]) : "");
		global $comboNIVELESJERARQUICOS;
		$cEntidad->setIdNivel((isset($_POST["LSTIdNivel"]) && $_POST["LSTIdNivel"] != "") ? $_POST["LSTIdNivel"] : "");	$cEntidad->setBusqueda(constant("STR_NIVEL"), (isset($_POST["LSTIdNivel"]) && $_POST["LSTIdNivel"] != "" ) ? $comboNIVELESJERARQUICOS->getDescripcionCombo($_POST["LSTIdNivel"]) : "");
		global $comboAREAS;
		$cEntidad->setIdArea((isset($_POST["LSTIdArea"]) && $_POST["LSTIdArea"] != "") ? $_POST["LSTIdArea"] : "");	$cEntidad->setBusqueda(constant("STR_AREA"), (isset($_POST["LSTIdArea"]) && $_POST["LSTIdArea"] != "" ) ? $comboAREAS->getDescripcionCombo($_POST["LSTIdArea"]) : "");
		$cEntidad->setTelefono((isset($_POST["LSTTelefono"]) && $_POST["LSTTelefono"] != "") ? $_POST["LSTTelefono"] : "");	$cEntidad->setBusqueda(constant("STR_TELEFONO"), (isset($_POST["LSTTelefono"]) && $_POST["LSTTelefono"] != "" ) ? $_POST["LSTTelefono"] : "");
		$cEntidad->setEstadoCivil((isset($_POST["LSTEstadoCivil"]) && $_POST["LSTEstadoCivil"] != "") ? $_POST["LSTEstadoCivil"] : "");	$cEntidad->setBusqueda(constant("STR_ESTADO_CIVIL"), (isset($_POST["LSTEstadoCivil"]) && $_POST["LSTEstadoCivil"] != "" ) ? $_POST["LSTEstadoCivil"] : "");
		$cEntidad->setNacionalidad((isset($_POST["LSTNacionalidad"]) && $_POST["LSTNacionalidad"] != "") ? $_POST["LSTNacionalidad"] : "");	$cEntidad->setBusqueda(constant("STR_NACIONALIDAD"), (isset($_POST["LSTNacionalidad"]) && $_POST["LSTNacionalidad"] != "" ) ? $_POST["LSTNacionalidad"] : "");
		$cEntidad->setInformado((isset($_POST["LSTInformado"]) && $_POST["LSTInformado"] != "") ? $_POST["LSTInformado"] : "");	$cEntidad->setBusqueda(constant("STR_INFORMADO"), (isset($_POST["LSTInformado"]) && $_POST["LSTInformado"] != "" ) ? $_POST["LSTInformado"] : "");
		$cEntidad->setInformadoHast((isset($_POST["LSTInformadoHast"]) && $_POST["LSTInformadoHast"] != "") ? $_POST["LSTInformadoHast"] : "");	$cEntidad->setBusqueda(constant("STR_INFORMADO") . " " . constant("STR_HASTA"), (isset($_POST["LSTInformadoHast"]) && $_POST["LSTInformadoHast"] != "" ) ? $_POST["LSTInformadoHast"] : "");
		$cEntidad->setFinalizado((isset($_POST["LSTFinalizado"]) && $_POST["LSTFinalizado"] != "") ? $_POST["LSTFinalizado"] : "");	$cEntidad->setBusqueda(constant("STR_FINALIZADO"), (isset($_POST["LSTFinalizado"]) && $_POST["LSTFinalizado"] != "" ) ? $_POST["LSTFinalizado"] : "");
		$cEntidad->setFinalizadoHast((isset($_POST["LSTFinalizadoHast"]) && $_POST["LSTFinalizadoHast"] != "") ? $_POST["LSTFinalizadoHast"] : "");	$cEntidad->setBusqueda(constant("STR_FINALIZADO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFinalizadoHast"]) && $_POST["LSTFinalizadoHast"] != "" ) ? $_POST["LSTFinalizadoHast"] : "");
		$cEntidad->setFechaFinalizado((isset($_POST["LSTFechaFinalizado"]) && $_POST["LSTFechaFinalizado"] != "") ? $_POST["LSTFechaFinalizado"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_FINALIZADO"), (isset($_POST["LSTFechaFinalizado"]) && $_POST["LSTFechaFinalizado"] != "" ) ? $conn->UserDate($_POST["LSTFechaFinalizado"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFechaFinalizadoHast((isset($_POST["LSTFechaFinalizadoHast"]) && $_POST["LSTFechaFinalizadoHast"] != "") ? $_POST["LSTFechaFinalizadoHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_FINALIZADO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFechaFinalizadoHast"]) && $_POST["LSTFechaFinalizadoHast"] != "" ) ? $conn->UserDate($_POST["LSTFechaFinalizadoHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboWI_USUARIOS;
		$cEntidad->setUsuAlta((isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "") ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_ALTA"), (isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "") ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_MODIFICACION"), (isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
		$cEntidad->setUltimoLogin((isset($_POST["LSTUltimoLogin"]) && $_POST["LSTUltimoLogin"] != "") ? $_POST["LSTUltimoLogin"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN"), (isset($_POST["LSTUltimoLogin"]) && $_POST["LSTUltimoLogin"] != "" ) ? $conn->UserDate($_POST["LSTUltimoLogin"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimoLoginHast((isset($_POST["LSTUltimoLoginHast"]) && $_POST["LSTUltimoLoginHast"] != "") ? $_POST["LSTUltimoLoginHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN") . " " . constant("STR_HASTA"), (isset($_POST["LSTUltimoLoginHast"]) && $_POST["LSTUltimoLoginHast"] != "" ) ? $conn->UserDate($_POST["LSTUltimoLoginHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setToken((isset($_POST["LSTToken"]) && $_POST["LSTToken"] != "") ? $_POST["LSTToken"] : "");	$cEntidad->setBusqueda(constant("STR_TOKEN"), (isset($_POST["LSTToken"]) && $_POST["LSTToken"] != "" ) ? $_POST["LSTToken"] : "");
		$cEntidad->setUltimaAcc((isset($_POST["LSTUltimaAcc"]) && $_POST["LSTUltimaAcc"] != "") ? $_POST["LSTUltimaAcc"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMA_ACCION"), (isset($_POST["LSTUltimaAcc"]) && $_POST["LSTUltimaAcc"] != "" ) ? $conn->UserDate($_POST["LSTUltimaAcc"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimaAccHast((isset($_POST["LSTUltimaAccHast"]) && $_POST["LSTUltimaAccHast"] != "") ? $_POST["LSTUltimaAccHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMA_ACCION") . " " . constant("STR_HASTA"), (isset($_POST["LSTUltimaAccHast"]) && $_POST["LSTUltimaAccHast"] != "" ) ? $conn->UserDate($_POST["LSTUltimaAccHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setOrderBy((!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "fecMod");	$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "fecMod");
		$cEntidad->setOrder((!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "DESC");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "DESC");
		$cEntidad->setLineasPagina((!empty($_POST["LSTLineasPagina"]) && is_numeric($_POST["LSTLineasPagina"])) ? $_POST["LSTLineasPagina"] : constant("CNF_LINEAS_PAGINA"));
		$_POST["LSTLineasPagina"] = $cEntidad->getLineasPagina();
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request, los asocia a
	* una determinada Entidad para borrar las imagenes seleccionadas.
	*/
	function quitaImg($cEntidad, $cEntidadDB, $bBorrar= false){
		$bLlamada=false;
		if ($bBorrar){
			setBorradoRegistro();
		}
		if ($bLlamada){
			$cEntidadDB->quitaImagen($cEntidad);
		}
	}
	/*
	* "Setea" el request, para el borrado de imagenes
	* cuando es un borrado del registro.
	*/
	function setBorradoRegistro(){
	}

	function readEntidadEnvios($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdEnvio((isset($_POST["fIdEnvio"])) ? $_POST["fIdEnvio"] : "");
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setIdTipoCorreo((isset($_POST["fIdTipoCorreo"])) ? $_POST["fIdTipoCorreo"] : "1");
		$cEntidad->setIdCorreo((!empty($_POST["fIdCorreo"])) ? $_POST["fIdCorreo"] : "1");
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
		return $cEntidad;
	}
	function validaCandidatosYaEvaluadosLessYear($cCandidato, $sPruebas, $rsProceso_informes, $cEnvios){
		global $conn;
		global $cEntidadDB;
		global $sHijos;
		global $cProceso_informesDB;
		global $aCandidatosYaEvaluadosLessYear;
		global $cUtilidades;
		global $cProceso_pruebas_candidatoDB;
		$aEncontrados=array();	//Para que sólo saque una vez candidato-prueba
		$bRetorno = false;
		$cCandidatosYaEvaluadosLessYear	= new Candidatos();  // Entidad
		$cCandidatosYaEvaluadosLessYear->setIdEmpresa($sHijos);
		$cCandidatosYaEvaluadosLessYear->setMail($cCandidato->getMail());
		$cCandidatosYaEvaluadosLessYear->setInformado("1");
		$cCandidatosYaEvaluadosLessYear->setInformadoHast("1");
		$cCandidatosYaEvaluadosLessYear->setFinalizado("1");
		$cCandidatosYaEvaluadosLessYear->setFinalizadoHast("1");
		$now = date("Y-m-d H:i:s");
		$lessYear = date( 'Y-m-d', strtotime( '-1 year' , strtotime($now)));
		$cCandidatosYaEvaluadosLessYear->setFechaFinalizado($lessYear);
		$cCandidatosYaEvaluadosLessYear->setFechaFinalizadoHast($now);
		$cCandidatosYaEvaluadosLessYear->setOrderBy("fechaFinalizado");
		$cCandidatosYaEvaluadosLessYear->setOrder("DESC");
		$sSQL = $cEntidadDB->readListaIN($cCandidatosYaEvaluadosLessYear);
		$rsRepes = $conn->Execute($sSQL);
		//echo "<br />*" . $sSQL;
		if ($rsRepes->NumRows() > 0){
			while (!$rsRepes->EOF)
			{
				//Miramos si la bateria de pruebas es la misma
				$cProceso_informesR	= new Proceso_informes();  // Entidad
				$cProceso_informesR->setIdEmpresa($rsRepes->fields['idEmpresa']);
				$cProceso_informesR->setIdProceso($rsRepes->fields['idProceso']);
				$cProceso_informesR->setIdPrueba($sPruebas);
				$sqlProceso_informesR = $cProceso_informesDB->readLista($cProceso_informesR);
				$rsProceso_informesR = $conn->Execute($sqlProceso_informesR);
//				echo "<br />Proceso_informes Repes:" . $sqlProceso_informesR;
//				echo "<br />" . $rsProceso_informesR->NumRows() . " == " . $rsProceso_informes->NumRows();
//				if ($rsProceso_informesR->NumRows() == $rsProceso_informes->NumRows())
//				{
					//Miramos La configuración completa incluido baremo
					$iCoincidenciasCompletas=0;
					$rsProceso_informes->Move(0);
					$rsProceso_informesR->Move(0);
					while (!$rsProceso_informes->EOF)
					{
// 						echo "<br />Idioma Prueba: " . $rsProceso_informes->fields['codIdiomaIso2'];
// 						echo "<br />Prueba: " . $rsProceso_informes->fields['idPrueba'];
// 						echo "<br />Idioma Informe: " . $rsProceso_informes->fields['codIdiomaInforme'];
// 						echo "<br />Tipo Informe: " . $rsProceso_informes->fields['idTipoInforme'];
// 						echo "<br />Baremo: " . $rsProceso_informes->fields['idBaremo'];

						while (!$rsProceso_informesR->EOF)
						{
// 							echo "<br />R - Idioma Prueba: " . $rsProceso_informesR->fields['codIdiomaIso2'];
// 							echo "<br />R - Prueba: " . $rsProceso_informesR->fields['idPrueba'];
// 							echo "<br />R - Idioma Informe: " . $rsProceso_informesR->fields['codIdiomaInforme'];
// 							echo "<br />R - Tipo Informe: " . $rsProceso_informesR->fields['idTipoInforme'];
// 							echo "<br />R - Baremo: " . $rsProceso_informesR->fields['idBaremo'];

							if ($rsProceso_informesR->fields['codIdiomaIso2'] == $rsProceso_informes->fields['codIdiomaIso2'] &&
								$rsProceso_informesR->fields['idPrueba'] == $rsProceso_informes->fields['idPrueba'] &&
								$rsProceso_informesR->fields['codIdiomaInforme'] == $rsProceso_informes->fields['codIdiomaInforme'] &&
								$rsProceso_informesR->fields['idTipoInforme'] == $rsProceso_informes->fields['idTipoInforme'] &&
								$rsProceso_informesR->fields['idBaremo'] == $rsProceso_informes->fields['idBaremo'] &&
								!in_array($cCandidato->getMail() . $rsProceso_informes->fields['idPrueba'], $aEncontrados)){
//								echo "<br />Empresa: " . $rsRepes->fields['idEmpresa'] . " Proceso: " . $rsRepes->fields['idProceso'] . " Candidato: " .  $rsRepes->fields['idCandidato'] . " Prueba: " . $rsProceso_informesR->fields['idPrueba'] . " codIdiomaIso2: " . $rsProceso_informesR->fields['codIdiomaIso2'] . " TipoInforme: " . $rsProceso_informesR->fields['idTipoInforme'] . " codIdiomaInforme: " . $rsProceso_informesR->fields['codIdiomaInforme'] . " Baremo: " . $rsProceso_informesR->fields['idBaremo'];
								$aEncontrados[] = $cCandidato->getMail() . $rsProceso_informes->fields['idPrueba'];
								$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"bajaLog=0 AND listar=1","","","idprueba");
								$idBaremo = (empty($rsProceso_informesR->fields['idBaremo'])) ? "1" : $rsProceso_informesR->fields['idBaremo'];
								$sDirImg="imgInformes/";
								$sNombrePDF = $cUtilidades->SEOTitulo($comboPRUEBASGROUP->getDescripcionCombo($rsProceso_informesR->fields['idPrueba']) . "_" . $rsRepes->fields['nombre'] . "_" . $rsRepes->fields['apellido1'] . "_" . $rsRepes->fields['mail'] . "_" . $rsRepes->fields['idEmpresa'] . "_" .$rsRepes->fields['idProceso'] . "_" . $rsProceso_informesR->fields['idTipoInforme'] . "_" . $rsProceso_informesR->fields['codIdiomaInforme'] . "_" . $idBaremo) . ".pdf";
								$sResultadoRestoPruebas="";
								$sResultadoVolverEvaluar="";
								//Las pruebas de VIPS y NIPS son aleatorias, tenemos que saber que prueba realizó
								//Miramos las prueba que realmente realizó
								$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
								$cProceso_pruebas_candidato->setIdEmpresa($cCandidato->getIdEmpresa());
								$cProceso_pruebas_candidato->setIdProceso($cCandidato->getIdProceso());
								$cProceso_pruebas_candidato->setIdCandidato($cCandidato->getIdCandidato());

								$sqlProceso_pruebas_candidato = $cProceso_pruebas_candidatoDB->readLista($cProceso_pruebas_candidato);
								//echo $sqlProceso_pruebas_candidato;exit;
								$listaProceso_pruebas_candidato = $conn->Execute($sqlProceso_pruebas_candidato);
								$_idPruebaReal=$rsProceso_informesR->fields['idPrueba'];
								//echo $_idPruebaReal;exit;
								while(!$listaProceso_pruebas_candidato->EOF)
								{
									if(!empty($listaProceso_pruebas_candidato->fields['idPruebaProceso'])){
										if($rsProceso_informesR->fields['idPrueba'] == $listaProceso_pruebas_candidato->fields['idPruebaProceso']){
											$_idPruebaReal=$listaProceso_pruebas_candidato->fields['idPrueba'];
										}
									}
									$listaProceso_pruebas_candidato->MoveNext();
								}
								$aCandidatosYaEvaluadosLessYear[] = $rsRepes->fields['idEmpresa'] . "," . $rsRepes->fields['idProceso'] . "," . $rsRepes->fields['idCandidato'] . "," . $cCandidato->getIdEmpresa() . "," . $cCandidato->getIdProceso() . "," . $cCandidato->getIdCandidato() . "," . $cEnvios->getIdTipoCorreo() . "," . $cEnvios->getIdCorreo() . "," . $_idPruebaReal . "," . constant("DIR_WS_GESTOR") . $sDirImg . $sNombrePDF . "," . $sResultadoRestoPruebas . "," .$sResultadoVolverEvaluar;
								$bRetorno = true;
								$iCoincidenciasCompletas++;
							}
							$rsProceso_informesR->MoveNext();
						}
						$rsProceso_informesR->Move(0);
						$rsProceso_informes->MoveNext();
					}
//					echo "<br />iCoincidenciasCompletas-> " . $iCoincidenciasCompletas;
// 					if ($iCoincidenciasCompletas == $rsProceso_informes->NumRows()){
// 						$aCandidatosYaEvaluadosLessYear[] = $rsRepes->fields['idEmpresa'] . "," . $rsRepes->fields['idProceso'] . "," . $rsRepes->fields['idCandidato'] . "," . $cCandidato->getIdEmpresa() . "," . $cCandidato->getIdProceso() . "," . $cCandidato->getIdCandidato() . "," . $cEnvios->getIdTipoCorreo() . "," . $cEnvios->getIdCorreo();
// 						$bRetorno = true;
// 					}
				//}
				$rsRepes->MoveNext();
			}
		}
		return $bRetorno;
	}

	function ValidaRestoPruebas($cCandidatoDestino, $DescargaInforme, $rsPIDestino)
	{
		$iTotalPIDestino	=	$rsPIDestino->RecordCount();
		$CountPrismaXInforme=0;
		while (!$rsPIDestino->EOF){
			$aPruebas[]=$rsPIDestino->fields['idPrueba'];
			if ($rsPIDestino->fields['idPrueba'] == 24){
				$CountPrismaXInforme++;
			}
			$rsPIDestino->MoveNext();
		}
		if ($CountPrismaXInforme > 1){
			$iTotalPIDestino = ($iTotalPIDestino - ($CountPrismaXInforme-1));
		}
		sort($aPruebas);

		for ($i=0, $max = sizeof($DescargaInforme); $i < $max; $i++)
		{
			if (!empty($DescargaInforme[$i]))
			{
				$aValores = explode(",", $DescargaInforme[$i]);
				if ($cCandidatoDestino->getIdEmpresa() == $aValores[3] &&
						$cCandidatoDestino->getIdProceso() == $aValores[4] &&
						$cCandidatoDestino->getIdCandidato() == $aValores[5]
				){
					$aPruebasDestino[]=$aValores[8];	//Prueba
				}

			}
		}	//Fin del for

		$iTotalPruebasDestino=0;
		if (isset($aPruebasDestino)){
			$iTotalPruebasDestino = count($aPruebasDestino);
		}
		return ($iTotalPIDestino - $iTotalPruebasDestino);
	}

	function enviaEmail($cEmpresa, $cCandidato, $cCorreos_proceso, $IdModoRealizacion)
	{
		global $conn;
		global $cProcesos;

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
			//$mail->PluginDir = constant("DIR_WS_COM") . "/_Email/";

			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = constant("MAILER");
			

			//Le decimos cual es nuestro nombre de usuario y password
			
			

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
			//$mail->Timeout=120;

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
				$exito = $mail->send();
				$intentos=$intentos+1;
			}

			//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
			//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
			//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
			if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				$exito=true;
			}
			if(!$exito){
				$sTypeError	=	date('d/m/Y H:i:s') . " Problemas enviando correo electrónico FROM::[" . $mail->From . "] TO::[" . $cCandidato->getMail() . "]";
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
