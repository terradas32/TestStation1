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

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	if (empty($_POST["fHijos"]))
	{
		require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
	//	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
		$_EmpresaLogada = constant("EMPRESA_PE");
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
	$sNOEnviados="";
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
				$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
				$cCandidatos->setIdProceso($cEnvios->getIdProceso());
				$cCandidatos->setInformado($cEntidad->getInformado());
				$cCandidatos->setInformadoHast($cEntidad->getInformado());
				$sqlCandidatos = $cEntidadDB->readListaIN($cCandidatos);
				$sqlCandidatos .= " AND mail <>''";
			}
//			echo "<br />" . $sqlCandidatos;
			$rsCandidatos = $conn->Execute($sqlCandidatos);
			$cEmpresas->setIdEmpresa($cEnvios->getIdEmpresa());
			$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);


			$iTotalCandidatos	=	$rsCandidatos->RecordCount();
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
			$bPrepago = $cEmpresas->getPrepago();
			if (!empty($bPrepago)){
				//Es de prepago hay que verificar los dongles
				if ($iDonglesADescontar > $iDonglesDeEmpresa){
					//Hay que descontar mas dongles que los que tiene cargados la empresa,
					//Se lanza mensaje de error.
					$sMSG_JS_ERROR="La Empresa " . $cEmpresas->getNombre() . " - No Dispone de suficientes Dongles para efectuar la operación.\\n";
					$sMSG_JS_ERROR.="\\tDongles Disponibles: " . $iDonglesDeEmpresa . " Dongles.\\n";
					$sMSG_JS_ERROR.="\\tDongles a consumir: " . $iDonglesADescontar . " Dongles.\\n\\n";
					$sMSG_JS_ERROR.="Por favor recargue un mínimo de:\\n ";
					$sMSG_JS_ERROR.="\\t" . ($iDonglesADescontar - $iDonglesDeEmpresa) . " Dongles.\\n ";

				?><script language="javascript" type="text/javascript">alert("<?php echo $sMSG_JS_ERROR;?>");</script><?php
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
			$iTotal = $rsCandidatos->RecordCount();
			$iFallidos = 0;
			switch ($cEnvios->getIdTipoCorreo()){
				case "1":	//Envio o Reenvio de información
					//Recorremos los candidatos, enviamos el correo
					//y lo damos de alta en en la tabla envios.
					//Esta forma es incremental, teniendo un histórico de envios.
					$i=0;
					$sBody = "";
					$sAltBody = "";
					$sSubject = "";
					while (!$rsCandidatos->EOF){
						$cCandidatos = new Candidatos();
						$cCandidatos->setIdEmpresa($cEnvios->getIdEmpresa());
						$cCandidatos->setIdProceso($cEnvios->getIdProceso());
						$cCandidatos->setIdCandidato($rsCandidatos->fields['idCandidato']);
						$cCandidatos = $cCandidatosDB->readEntidad($cCandidatos);
						$newPass= $cUtilidades->newPass();
						$sUsuario=$cCandidatos->getMail();

						//Administrado y enviar todas las contraseñas en 1 sólo correo
						if ($EnvioContrasenas == "2"){
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
							$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
							$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
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

							if (!enviaEmail($cEmpresas, $cCandidatos, $cCorreos_proceso, $IdModoRealizacion)){
								//informamos de los emails q no se han podido enviar.
								$iFallidos++;
								$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
								$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
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
								$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
								$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
								$cEnviosDB->insertar($cEnvios_hist);
								$sTypeError	=	date('d/m/Y H:i:s') . " Correo enviado FROM::[" . $sFrom . "] TO::[" . $cCandidatos->getMail() . "]";
								error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));

							}
						}

						$i++;
						$rsCandidatos->MoveNext();
					}
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
								$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
								$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
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
							$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
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
									$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
									$sNOEnviados.= $cCandidatos->getNombre() . " " . $cCandidatos->getApellido1() . " " . $cCandidatos->getApellido2() . " [" . $cCandidatos->getMail() . "]\\n";
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
									$cEnvios_hist->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
									$cEnvios_hist->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
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
			if (empty($newId)){
			?><script language="javascript" type="text/javascript">alert("Se han enviado <?php echo $iTotal-$iFallidos;?> Correos de un total de <?php echo $iTotal;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
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
					$cEntidad	= new Candidatos();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/EnviarCorreos/mntenviarcorreosa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo $sMSG_JS_ERROR;?><?php echo $sNOEnviados;?><?php echo $sMSG_JS_RESUMEN;?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/EnviarCorreos/mntenviarcorreosa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
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
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
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
		$cEntidad->setIdTipoCorreo((isset($_POST["fIdTipoCorreo"])) ? $_POST["fIdTipoCorreo"] : "");
		$cEntidad->setIdCorreo((isset($_POST["fIdCorreo"])) ? $_POST["fIdCorreo"] : "");
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
		return $cEntidad;
	}

	function enviaEmail($cEmpresa, $cCandidato, $cCorreos_proceso, $IdModoRealizacion){
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

			// Borro las direcciones de destino establecidas anteriormente
			$mail->clearAllRecipients();

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

			// Update 2025-08-04 [Nair E. Marinćak]: Todo lo que sale del buzón de TS si le dan responder tiene que volver al buzón de TS únicamente
			// Update 2025-08-04 [Nair E. Marinćak]: Todo sale desde ese correo
			$mail->From = constant("MAILUSERNAME");
			if ($cProcesos->getProcesoConfidencial() == "1"){
				//$mail->AddReplyTo(constant("MAILUSERNAME"), constant("NOMBRE_EMPRESA"));
				$mail->FromName = constant("NOMBRE_EMPRESA");
			}else{
				//$mail->AddReplyTo($cEmpresa->getMail(), $cEmpresa->getNombre());
				$mail->FromName = $cEmpresa->getNombre();
			}
			//$mail->AddReplyTo();
			//$mail->addReplyTo(constant("MAILUSERNAME"));

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
