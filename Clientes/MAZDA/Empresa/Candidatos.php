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
	include(constant("DIR_ADODB") . 'adodb-errorhandler.inc.php');
	include(constant("DIR_ADODB") . 'adodb.inc.php');

	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
	require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");

	include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
	$cEntidad	= new Candidatos();  // Entidad
	$cPruebasDB	= new PruebasDB($conn);  // Pruebas DB
	$cPruebas	= new Pruebas();  // Pruebas
	$cInformes_pruebasDB = new Informes_pruebasDB($conn);
	$cInformes_pruebas = new Informes_pruebas();

	$cRespuestas_pruebasDB	= new Respuestas_pruebasDB($conn);  // Entidad DB
	$cRespuestas_pruebas	= new Respuestas_pruebas();  // Entidad

	$cNotificacionesDB	= new NotificacionesDB($conn);
	$cNotificaciones	= new Notificaciones();

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	if (empty($_POST["fHijos"]))
	{
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
	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):
			$cEntidad	= readEntidad($cEntidad);
			$newId	= $cEntidadDB->insertar($cEntidad);
			if (!empty($newId)){
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
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
					include('Template/Candidatos/mntcandidatosl.php');
				}else{
					$cEntidad	= new Candidatos();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Candidatos/mntcandidatosa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Candidatos/mntcandidatosa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad_oldDB	= new CandidatosDB($conn);  // Entidad DB
			$cEntidad_old	= new Candidatos();  // Entidad
			$cEntidad_old->setIdEmpresa($cEntidad->getIdEmpresa());
			$cEntidad_old->setIdProceso($cEntidad->getIdProceso());
			$cEntidad_old->setIdCandidato($cEntidad->getIdCandidato());
			$cEntidad_old = $cEntidad_oldDB->readEntidad($cEntidad_old);

			if ($cEntidadDB->modificar($cEntidad))
			{
				$sMsgError="";

                    //Miramos si está para contratación
                    if ($cEntidad->getCertificada() == "1")
					{
						//Miramos si ha dado consentimiento a Mazda
						if ($cEntidad->getAceptaMazda() == "1")
						{
							$sCode = $cEntidad->getIdEmpresa() . '.' . $cEntidad->getIdProceso() . '.' . $cEntidad->getIdCandidato();

							$client_id = '13';	//Cliente MAZDA en AV
							$role_id = '3';	//Rol de Participante
							global $comboWI_PAISES;
							$pais = ($cEntidad->getIdPais() != "") ? $comboWI_PAISES->getDescripcionCombo($cEntidad->getIdPais()) : "";
							global $comboWI_PROVINCIAS;
							$provincia = ($cEntidad->getIdProvincia() != "") ? $comboWI_PROVINCIAS->getDescripcionCombo($cEntidad->getIdProvincia()) : "";
							global $comboAREAS;
							//$area = ($cEntidad->getIdArea() != "" ) ? $comboAREAS->getDescripcionCombo($cEntidad->getIdArea()) : "";
							//Volcamos el nombre del concesionario en area
							$cEmpresaArea = new Empresas();
							$cEmpresaAreaDB = new EmpresasDB($conn);
							$cEmpresaArea->setIdEmpresa($cEntidad->getIdEmpresa());	// Empresa
							$cEmpresaArea = $cEmpresaAreaDB->readEntidad($cEmpresaArea);
							$area = $cEmpresaArea->getNombre();

							global $comboNIVELESJERARQUICOS;
							$nivel = ($cEntidad->getIdNivel() != "") ? $comboNIVELESJERARQUICOS->getDescripcionCombo($cEntidad->getIdNivel()) : "";

							$apellidos = $cEntidad->getApellido1();
							$apellidos .= ($cEntidad->getApellido2() != "") ? " " . $cEntidad->getApellido2() : "";

							//1º Pasamos los datos del usuario a la plataforma de AV
							$bConAV = @$connAV = &ADONewConnection('mysql');
							if (empty($bConAV)) {
								echo(constant("ERR"));
								exit;
							}
							$connAV->Connect(constant("DB_HOST_AV") . ":" . constant("DB_PORT_AV"), constant("DB_USUARIO_AV"), constant("DB_PASSWORD_AV"), constant("DB_DATOS_AV"));
							$connAV->SetFetchMode(constant("ADODB_FETCH_ASSOC"));
							$connAV->Execute("SET NAMES utf8");
							//Miramos si existe el usuario por el dni si está relleno
							$bFoundCorreo=false;
							$bFoundDni=false;
							$_sFindDni="";
							$_sFindDni = $cEntidad->getDni();
							$_sFindDni = trim($_sFindDni);
							$_sFindMail="";
							$_sFindMail = $cEntidad->getMail();
							$_sFindMail = trim($_sFindMail);

							// Busco 1º por el Dni
							$sQUERY = 'SELECT * FROM users WHERE dni = ' . $connAV->qstr($_sFindDni, false);
							$rsUsers = $connAV->Execute($sQUERY);
							if ($rsUsers->NumRows() > 0) {
								$bFoundDni=true;
							}
							//echo $sQUERY;exit;
							//Si no lo encuentro por Dni, busco por el correo
							if (!$bFoundDni) {
								$sQUERY = 'SELECT * FROM users WHERE email = ' . $connAV->qstr($_sFindMail, false);
								$rsUsers = $connAV->Execute($sQUERY);
								if ($rsUsers->NumRows() > 0) {
									$bFoundCorreo=true;
								}
							}
							//echo $sQUERY;exit;
							$bEncontrado= false;
							$_idUsr = "";

							while (!$rsUsers->EOF) {
								$bEncontrado= true;
								$apellidos = $cEntidad->getApellido1();
								$apellidos .= ($cEntidad->getApellido2() != "") ? " " . $cEntidad->getApellido2() : "";
								$sQUERY = 'UPDATE users SET name = ' . $connAV->qstr($cEntidad->getNombre(), false);
								$sQUERY .= ' ,last_name = ' . $connAV->qstr($apellidos, false);
								$sQUERY .= ' ,email = ' . $connAV->qstr($cEntidad->getMail(), false);
								$sQUERY .= ' ,dni = ' . $connAV->qstr($cEntidad->getDni(), false);
								$sQUERY .= ' ,code = ' . $connAV->qstr($sCode, false);
								$sQUERY .= ' ,client_id = ' . $connAV->qstr($client_id, false);
								$sQUERY .= ' ,role_id = ' . $connAV->qstr($role_id, false);
								$sQUERY .= ' ,country = ' . $connAV->qstr($pais, false);
								$sQUERY .= ' ,city = ' . $connAV->qstr($provincia, false);
								$sQUERY .= ' ,area = ' . $connAV->qstr($area, false);
								$sQUERY .= ' ,department = ' . $connAV->qstr($nivel, false);
								$sQUERY .= ' ,updated_at = now()';
								$sQUERY .= ' WHERE id = ' . $rsUsers->fields['id'];
								$_idUsr = $rsUsers->fields['id'];
								$connAV->Execute($sQUERY);
								break;
								$rsUsers->MoveNext();
							}
							$Pass = $cEntidad->getDni();
							if (!$bEncontrado) {
								if (function_exists('password_hash')) {
									// php >= 5.5
									$sPass = password_hash($Pass, PASSWORD_BCRYPT);
								} else {
									$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
									$salt = base64_encode($salt);
									$salt = str_replace('+', '.', $salt);
									$sPass = crypt($Pass, '$2y$10$' . $salt . '$');
								}
								$sTK=$cUtilidades->quickRandom(60);
								$sQUERY = 'INSERT INTO `users` (`name`, `last_name`, `email`, `password`, `dni`, `remember_token`, `client_id`, `code`,
									`role_id`, `image`, `country`, `city`, `area`, `department`, `register_message_id`, `cv`, `hiring_date`, `hash`, `created_at`, `updated_at`) VALUES ';
								$sQUERY .= '(' . $connAV->qstr($cEntidad->getNombre(), false);
								$sQUERY .= ', ' . $connAV->qstr($apellidos, false);
								$sQUERY .= ', ' . $connAV->qstr($cEntidad->getMail(), false);
								$sQUERY .= ', ' . $connAV->qstr($sPass, false);
								$sQUERY .= ', ' . $connAV->qstr($cEntidad->getDni(), false);
								$sQUERY .= ', ' . $connAV->qstr($sTK, false);
								$sQUERY .= ' , ' . $connAV->qstr($client_id, false);
								$sQUERY .= ' , ' . $connAV->qstr($sCode, false);
								$sQUERY .= ', ' . $connAV->qstr($role_id, false);	//Rol de Participante
								$sQUERY .= ', ' . 'NULL';
								$sQUERY .= ', ' . $connAV->qstr($pais, false);
								$sQUERY .= ', ' . $connAV->qstr($provincia, false);
								$sQUERY .= ', ' . $connAV->qstr($area, false);
								$sQUERY .= ', ' . $connAV->qstr($nivel, false);
								;
								$sQUERY .= ', ' . 0;
								$sQUERY .= ', ' . "''";
								$sQUERY .= ', ' . 'now()';
								$sQUERY .= ', ' . $connAV->qstr($sTK, false);
								;
								$sQUERY .= ', ' . 'now()';
								$sQUERY .= ', ' . 'now()';
								$sQUERY .= ')';
								//echo $sQUERY;exit;
								$bOK = $connAV->Execute($sQUERY);
								if ($bOK === false) {
									$e = @ADODB_Pear_Error();
									echo "<br />ERROR: " . $e->message;
									exit;
								} else {
									//Sacamos el id del usuario
									$sQUERY = 'SELECT * FROM users WHERE email = ' . $connAV->qstr($cEntidad->getMail(), false);
									$rsUser = $connAV->Execute($sQUERY);
									while (!$rsUser->EOF) {
										$_idUsr = $rsUser->fields['id'];
										$rsUser->MoveNext();
									}
								}
							}

							//2º Pasamos los informes a la plataforma de AV
							//Buscamos el informe
							$_idPruebaEncontrada="";
							$_idPrueba = "117";	//CUESTIONARIO DE COMPETENCIAS AC
							$cRespuestas_pruebas->setIdEmpresa($cEntidad->getIdEmpresa());
							$cRespuestas_pruebas->setIdProceso($cEntidad->getIdProceso());
							$cRespuestas_pruebas->setIdCandidato($cEntidad->getIdCandidato());
							$cRespuestas_pruebas->setIdPrueba($_idPrueba);
							$sSql = $cRespuestas_pruebasDB->readLista($cRespuestas_pruebas);
							//echo "<br > " . $sSql;
							$rs = $conn->Execute($sSql);
							if ($rs->NumRows() > 0) {
								$_idPruebaEncontrada=$_idPrueba;
							}
							if (empty($_idPruebaEncontrada)) {
								$_idPrueba = "121";	//CUESTIONARIO DE COMPETENCIAS AS
								$cRespuestas_pruebas->setIdEmpresa($cEntidad->getIdEmpresa());
								$cRespuestas_pruebas->setIdProceso($cEntidad->getIdProceso());
								$cRespuestas_pruebas->setIdCandidato($cEntidad->getIdCandidato());
								$cRespuestas_pruebas->setIdPrueba($_idPrueba);
								$sSql = $cRespuestas_pruebasDB->readLista($cRespuestas_pruebas);
								//echo "<br > " . $sSql;
								$rs = $conn->Execute($sSql);
								if ($rs->NumRows() > 0) {
									$_idPruebaEncontrada=$_idPrueba;
								}
							}
							if (empty($_idPruebaEncontrada)) {
								$_idPrueba = "123";	//CUESTIONARIO DE COMPETENCIAS ACD
								$cRespuestas_pruebas->setIdEmpresa($cEntidad->getIdEmpresa());
								$cRespuestas_pruebas->setIdProceso($cEntidad->getIdProceso());
								$cRespuestas_pruebas->setIdCandidato($cEntidad->getIdCandidato());
								$cRespuestas_pruebas->setIdPrueba($_idPrueba);
								$sSql = $cRespuestas_pruebasDB->readLista($cRespuestas_pruebas);
								//echo "<br > " . $sSql;
								$rs = $conn->Execute($sSql);
								if ($rs->NumRows() > 0) {
									$_idPruebaEncontrada=$_idPrueba;
									// echo "<pre>";
									// print_r($rs);
									// echo "</pre>";
								}
							}
							if (empty($_idPruebaEncontrada)) {
								$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra la prueba para el informe";
								error_log($sTypeError . " ->\t" . $sCode . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
								echo "<br />" . $sTypeError;
								exit;
							}

							//3º Miramos que baremo x defecto se aplica a la prueba
							require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
							require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
							$cProceso_baremos = new Proceso_baremos();
							$cProceso_baremosDB = new Proceso_baremosDB($conn);
							$cProceso_baremos->setIdEmpresa($cEntidad->getIdEmpresa());
							$cProceso_baremos->setIdProceso($cEntidad->getIdProceso());
							$cProceso_baremos->setCodIdiomaIso2($rs->fields['codIdiomaIso2']);
							$cProceso_baremos->setIdPrueba($_idPruebaEncontrada);

							$sSQL = $cProceso_baremosDB->readLista($cProceso_baremos);
							//echo "<br />" . $sSQL;
							$rsProceso_baremos = $conn->Execute($sSQL);

							require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
							require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
							$cProceso_informesDB = new Proceso_informesDB($conn);
							$_idBaremo = "";
							$_codIdiomaInforme = "";
							$_idTipoInforme = "";
							while (!$rsProceso_baremos->EOF) {
								$_idBaremo = $rsProceso_baremos->fields['idBaremo'];
								$cProceso_informes = new Proceso_informes();
								$cProceso_informes->setIdEmpresa($cEntidad->getIdEmpresa());
								$cProceso_informes->setIdProceso($cEntidad->getIdProceso());
								$cProceso_informes->setCodIdiomaIso2($rs->fields['codIdiomaIso2']);
								$cProceso_informes->setIdPrueba($_idPruebaEncontrada);
								$cProceso_informes->setIdBaremo($rsProceso_baremos->fields['idBaremo']);
								$sSQL = $cProceso_informesDB->readLista($cProceso_informes);
								//echo "<br />" . $sSQL;
								$rsProceso_informes = $conn->Execute($sSQL);
								while (!$rsProceso_informes->EOF) {
									$_codIdiomaInforme = $rsProceso_informes->fields['codIdiomaInforme'];
									$_idTipoInforme = $rsProceso_informes->fields['idTipoInforme'];
									$rsProceso_informes->MoveNext();
								}
								$rsProceso_baremos->MoveNext();
							}
							$_idBaremo = (!empty($_idBaremo)) ? $_idBaremo : "1";
							$cPruebas	= new Pruebas();  // Pruebas
							$cPruebas->setIdPrueba($_idPruebaEncontrada);
							$cPruebas->setCodIdiomaIso2($rs->fields['codIdiomaIso2']);
							$cPruebas = $cPruebasDB->readEntidad($cPruebas);


							$sNombre = $cUtilidades->SEOTitulo($cPruebas->getNombre() . "_" . $cEntidad->getNombre() . "_" . $cEntidad->getApellido1() . "_" . $cEntidad->getMail() . "_" . $cEntidad->getIdEmpresa() . "_" . $cEntidad->getIdProceso() . "_" . $_idTipoInforme . "_" . $_codIdiomaInforme . "_" . $_idBaremo);
							$sDirImg="imgInformes/";
							$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
							$_ficheroPDF = $spath . $sDirImg . $sNombre . ".pdf";
							//echo  "<br />" . $_ficheroPDF;

							$bSubidoInforme=false;
							if (file_exists($_ficheroPDF)) {
								//Subimos el informe por FTP al servidor donde está AV
								$archivo = $_ficheroPDF;
								$archivo_remoto = constant('FTP_REMOTE_DIR_AV') . $sNombre . ".pdf";
								$servidor_ftp = constant('FTP_SERVER_AV');
								// configurar la conexion basica
								//$id_con = ftp_connect($servidor_ftp);
								$id_con = ftp_ssl_connect($servidor_ftp);
									
								if ((!$id_con)) {
									echo "¡La conexión FTP ha fallado!";
									echo "Se intentó conectar al $servidor_ftp ";
									exit;
								}
								// iniciar sesion con nombre de usuario y contrasenya
								$resultado_login = ftp_login($id_con, constant('FTP_USUARIO_AV'), constant('FTP_PASSWORD_AV'));
								// cargar un archivo
								ftp_pasv($id_con, true);
								if (!ftp_put($id_con, $archivo_remoto, $archivo, FTP_BINARY)) {
									$sTypeError	=	date('d/m/Y H:i:s') . " Hubo un problema durante la transferencia del informe " . $archivo;
									error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
									echo "<br />" . $sTypeError;
								} else {
									$bSubidoInforme=true;
								}
								// cerrar la conexion
								ftp_close($id_con);
							//FIN Subimos el informe por FTP al servidor donde está AV
							} else {
								$sMsgError	=	'<p><strong style="color:red;"><i>NOTA:</i></strong> No se ha encontrado el informe, solicite a People Experts lo adjunte a la plataforma de Assessment.</p>';
								echo "<br />" . $sMsgError;
								$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra el informe " . $_ficheroPDF;
								error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							}

							if ($bSubidoInforme) {
								//Modificamos el registro de usuario de AV incluyendo el informes
								//En caso MAZDA sólo un informe, se guarda en el campo report1
								$sQUERY = 'UPDATE users SET ';
								$sQUERY .= ' report1 = ' . $connAV->qstr($sNombre . ".pdf", false);
								$sQUERY .= ' ,updated_at = now()';
								$sQUERY .= ' WHERE id = ' . $_idUsr;
								$bOK = $connAV->Execute($sQUERY);
								if ($bOK === false) {
									$e = @ADODB_Pear_Error();
									echo "<br />ERROR: " . $e->message;
									exit;
								}
							}
							$bEmpresaFormacion=false;	//No se envia a los contactos de Fromación
							$cEmpresaGestionDB = new EmpresasDB($conn);
							if ($bEmpresaFormacion) {
								//Miramos si están dados de alta los usuarios Clientes equivalente a Academia de Formación
								$cEmpresaCliente = new Empresas();
								$cEmpresaClienteDB = new EmpresasDB($conn);
								$cEmpresaCliente->setIdEmpresa("5650");	// Empresa FormacionMB
								$cEmpresaCliente = $cEmpresaClienteDB->readEntidad($cEmpresaCliente);
								global $comboWI_PAISES;
								$pais = ($cEmpresaCliente->getIdPais() != "") ? $comboWI_PAISES->getDescripcionCombo($cEmpresaCliente->getIdPais()) : "";
								//Miramoso si ya están dados de alta las tres cuentas como usuarios Cliente
								$aEmailCliente[] = $cEmpresaCliente->getMail();	//Este siempre está en $this->
								if ($cEmpresaCliente->getMail2() != "") {
									$aEmailCliente[] = $cEmpresaCliente->getMail2();
								}
								if ($cEmpresaCliente->getMail3() != "") {
									$aEmailCliente[] = $cEmpresaCliente->getMail3();
								}
								for ($i=0, $max = sizeof($aEmailCliente); $i < $max; $i++) {
									$sQUERY = 'SELECT * FROM users WHERE email = ' . $connAV->qstr($aEmailCliente[$i], false);
									$rsCliente = $connAV->Execute($sQUERY);
									$bEncontrado= false;

									$role_id = '2';	//Rol de Clientes
									$_idUsrCli = "";
									$Pass = $cEmpresaCliente->getIdEmpresa();
									$sTK=$cUtilidades->quickRandom(60);
									$sCode = $cEntidad->getIdEmpresa();
									if ($rsCliente) {
										if (function_exists('password_hash')) {
											// php >= 5.5
											$sPass = password_hash($Pass, PASSWORD_BCRYPT);
										} else {
											$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
											$salt = base64_encode($salt);
											$salt = str_replace('+', '.', $salt);
											$sPass = crypt($Pass, '$2y$10$' . $salt . '$');
										}
										while (!$rsCliente->EOF) {
											$bEncontrado= true;
											$sQUERY = 'UPDATE users SET name = ' . $connAV->qstr($cEmpresaCliente->getNombre(), false);
											$sQUERY .= ' ,email = ' . $connAV->qstr($aEmailCliente[$i], false);
											$sQUERY .= ' ,password = ' . $connAV->qstr($sPass, false);
											$sQUERY .= ' ,dni = ' . $connAV->qstr($cEmpresaCliente->getCif(), false);
											$sQUERY .= ' ,code = ' . $connAV->qstr($cEmpresaCliente->getIdEmpresa(), false);
											$sQUERY .= ' ,client_id = ' . $connAV->qstr($client_id, false);
											$sQUERY .= ' ,role_id = ' . $connAV->qstr($role_id, false);
											$sQUERY .= ' ,country = ' . $connAV->qstr($pais, false);
											$sQUERY .= ' ,updated_at = now()';
											$sQUERY .= ' WHERE id = ' . $rsCliente->fields['id'];
											$_idUsrCli = $rsCliente->fields['id'];
											$connAV->Execute($sQUERY);
											$rsCliente->MoveNext();
										}
									}

									if (!$bEncontrado) {
										//No está dado de alta el cliente en AV, damos el alta
										$sQUERY = 'INSERT INTO `users` (`name`, `email`, `password`, `dni`, `remember_token`, `client_id`, `code`,
											`role_id`, `image`, `country`,  `register_message_id`, `cv`, `hash`, `created_at`, `updated_at`) VALUES ';
										$sQUERY .= '(' . $connAV->qstr($cEmpresaCliente->getNombre(), false);
										$sQUERY .= ', ' . $connAV->qstr($aEmailCliente[$i], false);
										$sQUERY .= ', ' . $connAV->qstr($sPass, false);
										$sQUERY .= ', ' . $connAV->qstr($cEmpresaCliente->getCif(), false);
										$sQUERY .= ', ' . $connAV->qstr($sTK, false);
										$sQUERY .= ' , ' . $connAV->qstr($client_id, false);
										$sQUERY .= ' , ' . $connAV->qstr($sCode, false);
										$sQUERY .= ', ' . $connAV->qstr($role_id, false);	//Rol de Participante
										$sQUERY .= ', ' . 'NULL';
										$sQUERY .= ', ' . $connAV->qstr($pais, false);
										$sQUERY .= ', ' . 0;
										$sQUERY .= ', ' . "''";
										$sQUERY .= ', ' . $connAV->qstr($sTK, false);
										$sQUERY .= ', ' . 'now()';
										$sQUERY .= ', ' . 'now()';
										$sQUERY .= ')';
										//echo $sQUERY;exit;
										$bOK = $connAV->Execute($sQUERY);
										if ($bOK === false) {
											$e = @ADODB_Pear_Error();
											echo "<br />ERROR: " . $e->message;
											exit;
										}
									}

									//en todos los caso se notifica a la empresa MNT_ANIADEBAREMO
									//Enviar Notificación de alta de usuario en AV
									//La entidad está preparada para consultar sólo por
									//Id Tipo Notificacion

									$cEmpresaGestion = new Empresas();
									$cEmpresaGestion->setIdEmpresa($cEntidad->getIdEmpresa());
									$cEmpresaGestion = $cEmpresaGestionDB->readEntidad($cEmpresaGestion);
									$cNotificaciones	= new Notificaciones();
									$cNotificaciones->setIdTipoNotificacion(10);	//Registro de usuario Admin empresa en AV
									$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
									$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, $cEmpresaGestion, null, null, $cEntidad, null, null, $aEmailCliente[$i], $cEmpresaCliente->getIdEmpresa());

									$sSubject=$cNotificaciones->getAsunto();
									$sBody=$cNotificaciones->getCuerpo();
									if (!empty($sSubject) && !empty($sBody)) {
										//TAG:: HTTP_SERVER_AV -> @link_acceso_av@
										//TAG:: $aEmailCliente[$i] -> @acceso_usuario_av@
										//TAG:: $sCode -> @acceso_password_av@
										$sBody = str_replace("@link_acceso_av@", constant('HTTP_SERVER_AV'), $sBody);
										$sBody = str_replace("@acceso_usuario_av@", $aEmailCliente[$i], $sBody);
										$sBody = str_replace("@acceso_password_av@", $cEmpresaCliente->getIdEmpresa(), $sBody);
										if (!empty($sMsgError)) {
											$sBody .= "<br /><br />" . $sMsgError;
										}
										//echo $sBody;
										$cNotificaciones->setCuerpo($sBody);
										$sAltBody=strip_tags($sBody);

										// Empresa Padre
										$cEmpresaPadre = new Empresas();
										$cEmpresaPadreDB = new EmpresasDB($conn);
										$cEmpresaPadre->setIdPadre($cEmpresaCliente->getIdPadre());
										$cEmpresaPadre = $cEmpresaPadreDB->readEntidadPadre($cEmpresaPadre);
										//Mandamos a la Empresa proveedora
										$cEmpresaTo = $cEmpresaCliente;
										// $cEmpresaTo->setMail("pbm@people-experts.com");
										// $cEmpresaTo->setMail2("pbm@people-experts.com");
										// $cEmpresaTo->setMail3("pbm@people-experts.com");
										$cEmpresaFrom = $cEmpresaPadre;
										if ($cEntidad_old->getCertificada() != "1") {
											//Si ya estaba señalada como certificada, ya se le envió los datos informativos, sólo una vez.
											if (!enviaEmail($cEmpresaTo, $cEmpresaFrom, $cNotificaciones)) {
												$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
												$sTypeError.= $cEmpresaTo->getNombre() . " [" . $cEmpresaTo->getMail() . "]";
												error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
											}
										}
									}
								}	//FIN del for de cuentas correo Empresa
							} else {
								//Revisamos cuantos usuarios Cliente de MAZDA hay en AV y mandamos correos
								$sQUERY = 'SELECT * FROM users WHERE client_id = 13 ';
								$sQUERY .= ' AND role_id = 2 ';
								//echo $sQUERY;exit;
								$rsUsers = $connAV->Execute($sQUERY);

								$cEmpresaFORMACION = new Empresas();
								$cEmpresaFORMACIONDB = new EmpresasDB($conn);
								$cEmpresaFORMACION->setIdEmpresa("5650");	// Empresa FormacionMB
								$cEmpresaFORMACION = $cEmpresaFORMACIONDB->readEntidad($cEmpresaFORMACION);

								while (!$rsUsers->EOF) {
									//echo "<br />" . $rsUsers->fields['email'];
									$cEmpresaCliente = new Empresas();
									$cEmpresaCliente->setNombre($rsUsers->fields['name'] . " " . $rsUsers->fields['last_name']);
									$cEmpresaCliente->setMail($rsUsers->fields['email']);

									$cEmpresaGestion = new Empresas();
									$cEmpresaGestion->setIdEmpresa($cEntidad->getIdEmpresa());
									$cEmpresaGestion = $cEmpresaGestionDB->readEntidad($cEmpresaGestion);
									$cNotificaciones	= new Notificaciones();
									$cNotificaciones->setIdTipoNotificacion(10);	//Registro de usuario Admin empresa en AV
									$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
									$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, $cEmpresaGestion, null, null, $cEntidad, null, null, $rsUsers->fields['email'], '');

									$sSubject=$cNotificaciones->getAsunto();
									$sBody=$cNotificaciones->getCuerpo();
									if (!empty($sSubject) && !empty($sBody)) {
										//TAG:: HTTP_SERVER_AV -> @link_acceso_av@
										//TAG:: $aEmailCliente[$i] -> @acceso_usuario_av@
										//TAG:: $sCode -> @acceso_password_av@
										$sBody = str_replace("@link_acceso_av@", constant('HTTP_SERVER_AV'), $sBody);
										$sBody = str_replace("@acceso_usuario_av@", $rsUsers->fields['email'], $sBody);
										$sBody = str_replace("@acceso_password_av@", '', $sBody);
										if (!empty($sMsgError)) {
											$sBody .= "<br /><br />" . $sMsgError;
										}
										//echo $sBody;exit;
										$cNotificaciones->setCuerpo($sBody);
										$sAltBody=strip_tags($sBody);

										// Empresa Padre
										$cEmpresaPadre = new Empresas();
										$cEmpresaPadreDB = new EmpresasDB($conn);
										$cEmpresaPadre->setIdPadre($cEmpresaFORMACION->getIdPadre());
										$cEmpresaPadre = $cEmpresaPadreDB->readEntidadPadre($cEmpresaPadre);
										//Mandamos a la Empresa proveedora
										$cEmpresaTo = $cEmpresaCliente;
										// $cEmpresaTo->setMail2("pbm@people-experts.com");
										// $cEmpresaTo->setMail3("pbm@people-experts.com");
										$cEmpresaFrom = $cEmpresaPadre;
										if ($cEntidad_old->getCertificada() != "1") {
											//echo "Antes envío." . $cEmpresaTo->getMail() . " " . $cEmpresaTo->getNombre();
											//Si ya estaba señalada como certificada, ya se le envió los datos informativos, sólo una vez.
											if (!enviaEmail($cEmpresaTo, $cEmpresaFrom, $cNotificaciones)) {
												$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
												$sTypeError.= $cEmpresaTo->getNombre() . " [" . $cEmpresaTo->getMail() . "]";
												error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
											}
										}
									}
									$rsUsers->MoveNext();
								}
							}
						}else{
							//No ha dado consentimiento a Mazda
							$sMsgError	=	'<p><strong style="color:red;"><i>NOTA:</i></strong> Este Participante no ha dado consentimiento para tratar sus datos por MAZDA, no podrá realizar la certificación sin esa autorización firmada.</p>';
							echo "<br />" . $sMsgError;
							$sTypeError	=	date('d/m/Y H:i:s') . " Error NO ACEPTA MAZDA " . $sMsgError;
							error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						}

                    }	//Fin getCertificada

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
				include('Template/Candidatos/mntcandidatosl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Candidatos/mntcandidatosa.php');
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
			include('Template/Candidatos/mntcandidatosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setIdEmpresa($_cEntidadUsuarioTK->getIdEmpresa());
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Candidatos/mntcandidatosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Candidatos/mntcandidatosa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Candidatos/mntcandidatos.php');
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
            if ($_cEntidadUsuarioTK->getIdEmpresa() == "5650") {
                if (strpos($sql, "ORDER BY") === false) {
                    $sql.=" AND (aceptaMazda=1 OR aceptaMazda IS NULL)";
                } else {
                    $aSql = explode("ORDER BY", $sql);
					$sql = $aSql[0] . " AND (aceptaMazda=1 OR aceptaMazda IS NULL)";
					$sql .= " ORDER BY ";
					$sql .= $aSql[1];
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
			include('Template/Candidatos/mntcandidatosl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Candidatos/mntcandidatos.php');
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
		$cEntidad->setCertificada((isset($_POST["fCertificada"])) ? $_POST["fCertificada"] : "");
		$cEntidad->setEnvDiploma((isset($_POST["fEnvDiploma"])) ? $_POST["fEnvDiploma"] : "");

		$cEntidad->setInformado((isset($_POST["fInformado"])) ? $_POST["fInformado"] : "");
		$cEntidad->setFinalizado((isset($_POST["fFinalizado"])) ? $_POST["fFinalizado"] : "");

		$cEntidad->setFechaFinalizado((isset($_POST["fFechaFinalizado"])) ? $_POST["fFechaFinalizado"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getUsuario());
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
		//$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"idEmpresa IN (" . $cEntidad->getIdEmpresa() . ")","","fecMod");
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
		$cEntidad->setCertificada((isset($_POST["LSTCertificada"]) && $_POST["LSTCertificada"] != "") ? $_POST["LSTCertificada"] : "");	$cEntidad->setBusqueda("Certificada", (isset($_POST["LSTCertificada"]) && $_POST["LSTCertificada"] != "" ) ? $_POST["LSTCertificada"] : "");
		$cEntidad->setEnvDiploma((isset($_POST["LSTEnvDiploma"]) && $_POST["LSTEnvDiploma"] != "") ? $_POST["LSTEnvDiploma"] : "");	$cEntidad->setBusqueda("Env. Diploma", (isset($_POST["LSTEnvDiploma"]) && $_POST["LSTEnvDiploma"] != "" ) ? $_POST["LSTEnvDiploma"] : "");

		$cEntidad->setInformado((isset($_POST["LSTInformado"]) && $_POST["LSTInformado"] != "") ? $_POST["LSTInformado"] : "");	$cEntidad->setBusqueda(constant("STR_INFORMADO"), (isset($_POST["LSTInformado"]) && $_POST["LSTInformado"] != "" ) ? $_POST["LSTInformado"] : "");
		$cEntidad->setInformadoHast((isset($_POST["LSTInformadoHast"]) && $_POST["LSTInformadoHast"] != "") ? $_POST["LSTInformadoHast"] : "");	$cEntidad->setBusqueda(constant("STR_INFORMADO") . " " . constant("STR_HASTA"), (isset($_POST["LSTInformadoHast"]) && $_POST["LSTInformadoHast"] != "" ) ? $_POST["LSTInformadoHast"] : "");
		$cEntidad->setFinalizado((isset($_POST["LSTFinalizado"]) && $_POST["LSTFinalizado"] != "") ? $_POST["LSTFinalizado"] : "");	$cEntidad->setBusqueda(constant("STR_FINALIZADO"), (isset($_POST["LSTFinalizado"]) && $_POST["LSTFinalizado"] != "" ) ? $_POST["LSTFinalizado"] : "");
		$cEntidad->setFinalizadoHast((isset($_POST["LSTFinalizadoHast"]) && $_POST["LSTFinalizadoHast"] != "") ? $_POST["LSTFinalizadoHast"] : "");	$cEntidad->setBusqueda(constant("STR_FINALIZADO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFinalizadoHast"]) && $_POST["LSTFinalizadoHast"] != "" ) ? $_POST["LSTFinalizadoHast"] : "");

		$cEntidad->setAceptaMazda((isset($_POST["LSTAceptaMazda"]) && $_POST["LSTAceptaMazda"] != "") ? $_POST["LSTAceptaMazda"] : "");	$cEntidad->setBusqueda("Acepta Mazda", (isset($_POST["LSTAceptaMazda"]) && $_POST["LSTAceptaMazda"] != "" ) ? $_POST["LSTAceptaMazda"] : "");

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


	//Se envia correo de Notificación
	//de que se ha dado de alta una empresa al padre y a Psicologos
	function enviaEmail($cEmpresaTO, $cEmpresaFROM, $cNotificaciones){
		global $conn;

		$sSubject=$cNotificaciones->getAsunto();
		$sBody=$cNotificaciones->getCuerpo();
		$sAltBody=strip_tags($cNotificaciones->getCuerpo());

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
			$mail->SMTPSecure = constant("MAIL_ENCRYPTION");							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';


			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresaFROM->getMail();
			$mail->From = constant("EMAIL_CONTACTO");
			$mail->AddReplyTo($cEmpresaFROM->getMail(), $cEmpresaFROM->getNombre());
			$mail->FromName = $cEmpresaFROM->getNombre();
				$nomEmpresa = $cEmpresaFROM->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $nomEmpresa . " - " . $sSubject;
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
			$mail->AddAddress($cEmpresaTO->getMail(), $cEmpresaTO->getNombre());
			if($cEmpresaTO->getMail2()!=""){
				$mail->AddAddress($cEmpresaTO->getMail2(), $cEmpresaTO->getNombre());

			}
			if($cEmpresaTO->getMail3()!=""){
				$mail->AddAddress($cEmpresaTO->getMail3(), $cEmpresaTO->getNombre());
			}
			/*
			echo "<br />De: " . $cEmpresaFROM->getMail();
			echo "<br />De Nombre: " . $cEmpresaFROM->getNombre();
			echo "<br />Para: " . $cEmpresaTO->getMail();
			echo "<br />Para Nombre: " . $cEmpresaTO->getNombre();
			*/
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
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
	    return $exito;
	}
?>
