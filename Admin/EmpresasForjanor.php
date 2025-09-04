<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
	require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
	$cEntidad	= new Empresas();  // Entidad

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

	$comboDENTRO_DE	= new	Combo($conn,"fDentroDe","orden",$conn->Concat("'" . constant("STR_DENTRO_DE") . "'", "' - '", "nombre"),"Descripcion","empresas","","","idEmpresa IN(" . $sHijos . ")","","orden");
	$comboDESPUES_DE	= new	Combo($conn,"fDespuesDe","orden",$conn->Concat("'" . constant("STR_DESPUES_DE") . "'", "' - '", "nombre"),"Descripcion","empresas","","- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -","idEmpresa IN(" . $sHijos . ")","","orden");
	$comboWI_PAISES	= new Combo($conn,"fIdPais","idPais","descripcion","Descripcion","wi_paises","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","","","bajaLog=0","","","idprueba");

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
				//Si da de alta la empresa, la ponemos en el string de asignación de id para el usr
				//Tener en cuenta que la última siempre es la logada
				if ($_POST["fHijos"]){
					$_POST["fHijos"]=  $newId . "," . $_POST["fHijos"];
					$sHijos = $_POST["fHijos"];
				}
//				$cEntidad = readLista($cEntidad);
				if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
				}
//////////////////////////////////////////////////////////////////
				/*
				 * Aunque en este comunicado no se utilice datos de:
				 * PROCESO, CANDIDATO, PRUEBA, RESPUESTAS_PRUEBAS
				 * se pasan los objetos para que sirva la misma función para más sitios
				 */
				require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
				$cProcesos = new Procesos();
				require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
				$cCandidatos = new Candidatos();
				require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
				$cPruebas = new Pruebas();
				require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
				$cRespuestas_pruebas = new Respuestas_pruebas();
				require_once(constant("DIR_WS_COM") . "Peticiones_dongles/Peticiones_dongles.php");
				$Peticiones_dongles = new Peticiones_dongles();

				$sFrom=$cEntidad->getMail();	//Cuenta de correo de la empresa
				$sFromName=$cEntidad->getNombre();	//Nombre de la empresa
				$newPass= $cEntidad->getPassword();
				$sUsuario=$cEntidad->getUsuario();
				//La entidad está preparada para consultar sólo por
				//Id Tipo Notificacion
				$cNotificaciones->setIdTipoNotificacion(1);	//Alta de Nueva empresa
				$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
				$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, $cEntidad, null, null, null, null, null, $sUsuario, $newPass);

				$sSubject=$cNotificaciones->getAsunto();
				$sBody=$cNotificaciones->getCuerpo();
				$sAltBody=strip_tags($cNotificaciones->getCuerpo());
				// Empresa PE
				$cEmpresaPE = new Empresas();
				$cEmpresaPEDB = new EmpresasDB($conn);
				$cEmpresaPE->setIdEmpresa(constant("EMPRESA_PE"));
				$cEmpresaPE = $cEmpresaPEDB->readEntidad($cEmpresaPE);
				// Empresa Padre
				$cEmpresaPadre = new Empresas();
				$cEmpresaPadreDB = new EmpresasDB($conn);
				$cEmpresaPadre->setIdPadre($cEntidad->getIdPadre());
				$cEmpresaPadre = $cEmpresaPadreDB->readEntidadPadre($cEmpresaPadre);
				if ($cEntidad->getIdPadre() == "" ||  $cEntidad->getIdPadre() == 0){
					//Mandamos SOLO a PE
					error_log("CREADA EMPRESA MATRIZ ->\t **" . $cEntidad->getNombre() . "**\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					if (!enviaEmail($cEmpresaPE, $cEntidad, $cNotificaciones)){
						$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
						$sTypeError.= $cEmpresaPE->getNombre() . " [" . $cEmpresaPE->getMail() . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
				}else{
					//Mandamos a la Empresa proveedora y a PE
					error_log("CREADA EMPRESA PROVEEDORA ->\t **" . $cEmpresaPadre->getNombre() . "**\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					if (!enviaEmail($cEmpresaPadre, $cEntidad, $cNotificaciones)){
						$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
						$sTypeError.= $cEmpresaPadre->getNombre() . " [" . $cEmpresaPadre->getMail() . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}

					if (!enviaEmail($cEmpresaPE, $cEntidad, $cNotificaciones)){
						$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
						$sTypeError.= $cEmpresaPE->getNombre() . " [" . $cEmpresaPE->getMail() . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
				}
///////////////////////////////////////////////////////////////////

			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					$cEntidad = readLista($cEntidad);
					if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
					}
					if (!empty($_POST['empresas_next_page']) && $_POST['empresas_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'empresas');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Empresas/mntempresasl.php');
				}else{
					$cEntidad	= new Empresas();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Empresas/mntempresasa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Empresas/mntempresasa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB);
			if ($cEntidadDB->modificar($cEntidad))
			{
				$cEntidad = readLista($cEntidad);
					if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
				}
				if (!empty($_POST['empresas_next_page']) && $_POST['empresas_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
				$pager = new ADODB_Pager($conn,$sql,'empresas');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Empresas/mntempresasl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Empresas/mntempresasa.php');
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
			if (!empty($_POST['empresas_next_page']) && $_POST['empresas_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'empresas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Empresas/mntempresasl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setIdEmpresa(constant("EMPRESA_PE"));
//			$cEntidad->setOrderBy("orden");
//			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_ORDEN"));
//			$cEntidad->setOrder("ASC");
//			$cEntidad->setBusqueda(constant("STR_ORDEN"), "ASC");
//			$_POST["LSTOrderBy"] = "orden";
//			$_POST["LSTOrder"] = "ASC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Empresas/mntempresasa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Empresas/mntempresasa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Empresas/mntempresas.php');
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
				if (!empty($_POST['empresas_next_page']) && $_POST['empresas_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'empresas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Empresas/mntempresasl.php');
			break;
		default:
//			$cEntidad->setOrderBy("orden");
//			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_ORDEN"));
//			$cEntidad->setOrder("ASC");
//			$cEntidad->setBusqueda(constant("STR_ORDEN"), "ASC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Empresas/mntempresas.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setIdPadre((isset($_POST["fIdPadre"])) ? $_POST["fIdPadre"] : "");
		$cEntidad->setCif((isset($_POST["fCif"])) ? $_POST["fCif"] : "");
		$cEntidad->setUsuario((isset($_POST["fUsuario"])) ? $_POST["fUsuario"] : "");
		$cEntidad->setPassword((isset($_POST["fPassword"])) ? $_POST["fPassword"] : "");
		$cEntidad->setPathLogo((isset($_POST["fPathLogo"])) ? $_POST["fPathLogo"] : "");
		$cEntidad->setMail((isset($_POST["fMail"])) ? $_POST["fMail"] : "");
		$cEntidad->setMail2((isset($_POST["fMail2"])) ? $_POST["fMail2"] : "");
		$cEntidad->setMail3((isset($_POST["fMail3"])) ? $_POST["fMail3"] : "");
		$cEntidad->setDistribuidor((isset($_POST["fDistribuidor"])) ? $_POST["fDistribuidor"] : "");
		$cEntidad->setAvisoLegal((isset($_POST["fAvisoLegal"])) ? $_POST["fAvisoLegal"] : "");
		$cEntidad->setPrepago((isset($_POST["fPrepago"])) ? $_POST["fPrepago"] : "");
		$cEntidad->setNcandidatos((isset($_POST["fNcandidatos"])) ? $_POST["fNcandidatos"] : "");
		$cEntidad->setDongles((isset($_POST["fDongles"])) ? $_POST["fDongles"] : "");
		$cEntidad->setEntidad((isset($_POST["fEntidad"])) ? $_POST["fEntidad"] : "");
		$cEntidad->setOficina((isset($_POST["fOficina"])) ? $_POST["fOficina"] : "");
		$cEntidad->setDc((isset($_POST["fDc"])) ? $_POST["fDc"] : "");
		$cEntidad->setCuenta((isset($_POST["fCuenta"])) ? $_POST["fCuenta"] : "");
		$cEntidad->setIdPais((isset($_POST["fIdPais"])) ? $_POST["fIdPais"] : "");
		$cEntidad->setDireccion((isset($_POST["fDireccion"])) ? $_POST["fDireccion"] : "");

		$cEntidad->setPersonaContacto((isset($_POST["fPersonaContacto"])) ? $_POST["fPersonaContacto"] : "");
		$cEntidad->setTlfContacto((isset($_POST["fTlfContacto"])) ? $_POST["fTlfContacto"] : "");

		$cEntidad->setUmbral_aviso((isset($_POST["fUmbral_aviso"])) ? $_POST["fUmbral_aviso"] : "");

		$cEntidad->setIdsPruebas((isset($_POST["fIdsPruebas"])) ? $_POST["fIdsPruebas"] : "");
		$cEntidad->setIdsPruebasAleatorias((isset($_POST["fIdsPruebasAleatorias"])) ? $_POST["fIdsPruebasAleatorias"] : "");
		//echo "pp::" . $_POST["fEdad"];exit;
		$cEntidad->setEdad((isset($_POST["fEdad"])) ? $_POST["fEdad"] : "");
		$cEntidad->setSexo((isset($_POST["fSexo"])) ? $_POST["fSexo"] : "");
		$cEntidad->setNivel((isset($_POST["fNivel"])) ? $_POST["fNivel"] : "");
		$cEntidad->setFormacion((isset($_POST["fFormacion"])) ? $_POST["fFormacion"] : "");
		$cEntidad->setArea((isset($_POST["fArea"])) ? $_POST["fArea"] : "");
		$cEntidad->setTelefono((isset($_POST["fTelefono"])) ? $_POST["fTelefono"] : "");
		$cEntidad->setNombreCan((isset($_POST["fNombreCan"])) ? $_POST["fNombreCan"] : "");
		$cEntidad->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cEntidad->setMailCan((isset($_POST["fMailCan"])) ? $_POST["fMailCan"] : "");
		$cEntidad->setNifCan((isset($_POST["fNifCan"])) ? $_POST["fNifCan"] : "");

		$cEntidad->setSectorMB((isset($_POST["fSectorMB"])) ? $_POST["fSectorMB"] : "");
		$cEntidad->setCodIso2PaisProcedencia((isset($_POST["fCodIso2PaisProcedencia"])) ? $_POST["fCodIso2PaisProcedencia"] : "");

		$cEntidad->setConcesionMB((isset($_POST["fConcesionMB"])) ? $_POST["fConcesionMB"] : "");
		$cEntidad->setBaseMB((isset($_POST["fBaseMB"])) ? $_POST["fBaseMB"] : "");

		$cEntidad->setFecNacimientoMB((isset($_POST["fFecNacimientoMB"])) ? $_POST["fFecNacimientoMB"] : "");
		$cEntidad->setEspecialidadMB((isset($_POST["fEspecialidadMB"])) ? $_POST["fEspecialidadMB"] : "");
		$cEntidad->setNivelConocimientoMB((isset($_POST["fNivelConocimientoMB"])) ? $_POST["fNivelConocimientoMB"] : "");
		$cEntidad->setSrvTPV((isset($_POST["fSrvTPV"])) ? $_POST["fSrvTPV"] : "");

		$cEntidad->setPuestoEvaluar((isset($_POST["fPuestoEvaluar"])) ? $_POST["fPuestoEvaluar"] : "");
		$cEntidad->setResponsableDirecto((isset($_POST["fResponsableDirecto"])) ? $_POST["fResponsableDirecto"] : "");
		$cEntidad->setCategoriaForjanor((isset($_POST["fCategoriaForjanor"])) ? $_POST["fCategoriaForjanor"] : "");

		$cEntidad->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
		$cEntidad->setIndentacion((isset($_POST["fIndentacion"])) ? $_POST["fIndentacion"] : "");
		$cEntidad->setUltimoLogin((isset($_POST["fUltimoLogin"])) ? $_POST["fUltimoLogin"] : "");
		$cEntidad->setToken((isset($_POST["fToken"])) ? $_POST["fToken"] : "");
		$cEntidad->setUltimaAcc((isset($_POST["fUltimaAcc"])) ? $_POST["fUltimaAcc"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setDentroDe((isset($_POST["fDentroDe"])) ? $_POST["fDentroDe"] : "");
		$cEntidad->setDespuesDe((isset($_POST["fDespuesDe"])) ? $_POST["fDespuesDe"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_ID_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $_POST["LSTIdEmpresa"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setIdEmpresaHast((isset($_POST["LSTIdEmpresaHast"]) && $_POST["LSTIdEmpresaHast"] != "") ? $_POST["LSTIdEmpresaHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_EMPRESA") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdEmpresaHast"]) && $_POST["LSTIdEmpresaHast"] != "" ) ? $_POST["LSTIdEmpresaHast"] : "");
		$cEntidad->setIdPadre((isset($_POST["LSTIdPadre"]) && $_POST["LSTIdPadre"] != "") ? $_POST["LSTIdPadre"] : "");	$cEntidad->setBusqueda(constant("STR_PADRE"), (isset($_POST["LSTIdPadre"]) && $_POST["LSTIdPadre"] != "" ) ? $_POST["LSTIdPadre"] : "");
		$cEntidad->setIdPadreHast((isset($_POST["LSTIdPadreHast"]) && $_POST["LSTIdPadreHast"] != "") ? $_POST["LSTIdPadreHast"] : "");	$cEntidad->setBusqueda(constant("STR_PADRE") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdPadreHast"]) && $_POST["LSTIdPadreHast"] != "" ) ? $_POST["LSTIdPadreHast"] : "");
		$cEntidad->setCif((isset($_POST["LSTCif"]) && $_POST["LSTCif"] != "") ? $_POST["LSTCif"] : "");	$cEntidad->setBusqueda(constant("STR_CIF"), (isset($_POST["LSTCif"]) && $_POST["LSTCif"] != "" ) ? $_POST["LSTCif"] : "");
		$cEntidad->setUsuario((isset($_POST["LSTUsuario"]) && $_POST["LSTUsuario"] != "") ? $_POST["LSTUsuario"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO"), (isset($_POST["LSTUsuario"]) && $_POST["LSTUsuario"] != "" ) ? $_POST["LSTUsuario"] : "");
		$cEntidad->setPassword((isset($_POST["LSTPassword"]) && $_POST["LSTPassword"] != "") ? $_POST["LSTPassword"] : "");	$cEntidad->setBusqueda(constant("STR_CONTRASENA"), (isset($_POST["LSTPassword"]) && $_POST["LSTPassword"] != "" ) ? $_POST["LSTPassword"] : "");
		$cEntidad->setPathLogo((isset($_POST["LSTPathLogo"]) && $_POST["LSTPathLogo"] != "") ? $_POST["LSTPathLogo"] : "");	$cEntidad->setBusqueda(constant("STR_LOGO"), (isset($_POST["LSTPathLogo"]) && $_POST["LSTPathLogo"] != "" ) ? $_POST["LSTPathLogo"] : "");
		$cEntidad->setMail((isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "") ? $_POST["LSTMail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "" ) ? $_POST["LSTMail"] : "");
		$cEntidad->setMail2((isset($_POST["LSTMail2"]) && $_POST["LSTMail2"] != "") ? $_POST["LSTMail2"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL_2"), (isset($_POST["LSTMail2"]) && $_POST["LSTMail2"] != "" ) ? $_POST["LSTMail2"] : "");
		$cEntidad->setMail3((isset($_POST["LSTMail3"]) && $_POST["LSTMail3"] != "") ? $_POST["LSTMail3"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL_3"), (isset($_POST["LSTMail3"]) && $_POST["LSTMail3"] != "" ) ? $_POST["LSTMail3"] : "");
		$cEntidad->setDistribuidor((isset($_POST["LSTDistribuidor"]) && $_POST["LSTDistribuidor"] != "") ? $_POST["LSTDistribuidor"] : "");	$cEntidad->setBusqueda(constant("STR_DISTRIBUIDOR"), (isset($_POST["LSTDistribuidor"]) && $_POST["LSTDistribuidor"] != "" ) ? $_POST["LSTDistribuidor"] : "");
		$cEntidad->setAvisoLegal((isset($_POST["LSTAvisoLegal"]) && $_POST["LSTAvisoLegal"] != "") ? $_POST["LSTAvisoLegal"] : "");	$cEntidad->setBusqueda("Mostrar Aviso Legal", (isset($_POST["LSTAvisoLegal"]) && $_POST["LSTAvisoLegal"] != "" ) ? $_POST["LSTAvisoLegal"] : "");
		$cEntidad->setPrepago((isset($_POST["LSTPrepago"]) && $_POST["LSTPrepago"] != "") ? $_POST["LSTPrepago"] : "");	$cEntidad->setBusqueda(constant("STR_PREPAGO"), (isset($_POST["LSTPrepago"]) && $_POST["LSTPrepago"] != "" ) ? $_POST["LSTPrepago"] : "");
		$cEntidad->setNcandidatos((isset($_POST["LSTNcandidatos"]) && $_POST["LSTNcandidatos"] != "") ? $_POST["LSTNcandidatos"] : "");	$cEntidad->setBusqueda(constant("STR_NU_CANDIDATOS"), (isset($_POST["LSTNcandidatos"]) && $_POST["LSTNcandidatos"] != "" ) ? $_POST["LSTNcandidatos"] : "");
		$cEntidad->setNcandidatosHast((isset($_POST["LSTNcandidatosHast"]) && $_POST["LSTNcandidatosHast"] != "") ? $_POST["LSTNcandidatosHast"] : "");	$cEntidad->setBusqueda(constant("STR_NU_CANDIDATOS") . " " . constant("STR_HASTA"), (isset($_POST["LSTNcandidatosHast"]) && $_POST["LSTNcandidatosHast"] != "" ) ? $_POST["LSTNcandidatosHast"] : "");
		$cEntidad->setDongles((isset($_POST["LSTDongles"]) && $_POST["LSTDongles"] != "") ? $_POST["LSTDongles"] : "");	$cEntidad->setBusqueda(constant("STR_DONGLES"), (isset($_POST["LSTDongles"]) && $_POST["LSTDongles"] != "" ) ? $_POST["LSTDongles"] : "");
		$cEntidad->setDonglesHast((isset($_POST["LSTDonglesHast"]) && $_POST["LSTDonglesHast"] != "") ? $_POST["LSTDonglesHast"] : "");	$cEntidad->setBusqueda(constant("STR_DONGLES") . " " . constant("STR_HASTA"), (isset($_POST["LSTDonglesHast"]) && $_POST["LSTDonglesHast"] != "" ) ? $_POST["LSTDonglesHast"] : "");
		$cEntidad->setEntidad((isset($_POST["LSTEntidad"]) && $_POST["LSTEntidad"] != "") ? $_POST["LSTEntidad"] : "");	$cEntidad->setBusqueda(constant("STR_ENTIDAD"), (isset($_POST["LSTEntidad"]) && $_POST["LSTEntidad"] != "" ) ? $_POST["LSTEntidad"] : "");
		$cEntidad->setOficina((isset($_POST["LSTOficina"]) && $_POST["LSTOficina"] != "") ? $_POST["LSTOficina"] : "");	$cEntidad->setBusqueda(constant("STR_OFICINA"), (isset($_POST["LSTOficina"]) && $_POST["LSTOficina"] != "" ) ? $_POST["LSTOficina"] : "");
		$cEntidad->setDc((isset($_POST["LSTDc"]) && $_POST["LSTDc"] != "") ? $_POST["LSTDc"] : "");	$cEntidad->setBusqueda(constant("STR_DC"), (isset($_POST["LSTDc"]) && $_POST["LSTDc"] != "" ) ? $_POST["LSTDc"] : "");
		$cEntidad->setCuenta((isset($_POST["LSTCuenta"]) && $_POST["LSTCuenta"] != "") ? $_POST["LSTCuenta"] : "");	$cEntidad->setBusqueda(constant("STR_CUENTA"), (isset($_POST["LSTCuenta"]) && $_POST["LSTCuenta"] != "" ) ? $_POST["LSTCuenta"] : "");
		global $comboWI_PAISES;
		$cEntidad->setIdPais((isset($_POST["LSTIdPais"]) && $_POST["LSTIdPais"] != "") ? $_POST["LSTIdPais"] : "");	$cEntidad->setBusqueda(constant("STR_PAIS"), (isset($_POST["LSTIdPais"]) && $_POST["LSTIdPais"] != "" ) ? $comboWI_PAISES->getDescripcionCombo($_POST["LSTIdPais"]) : "");
		$cEntidad->setDireccion((isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "") ? $_POST["LSTDireccion"] : "");	$cEntidad->setBusqueda(constant("STR_DIRECCION"), (isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "" ) ? $_POST["LSTDireccion"] : "");
		$cEntidad->setUmbral_aviso((isset($_POST["LSTUmbral_aviso"]) && $_POST["LSTUmbral_aviso"] != "") ? $_POST["LSTUmbral_aviso"] : "");	$cEntidad->setBusqueda(constant("STR_UMBRAL_DE_AVISO"), (isset($_POST["LSTUmbral_aviso"]) && $_POST["LSTUmbral_aviso"] != "" ) ? $_POST["LSTUmbral_aviso"] : "");
		$cEntidad->setUmbral_avisoHast((isset($_POST["LSTUmbral_avisoHast"]) && $_POST["LSTUmbral_avisoHast"] != "") ? $_POST["LSTUmbral_avisoHast"] : "");	$cEntidad->setBusqueda(constant("STR_UMBRAL_DE_AVISO") . " " . constant("STR_HASTA"), (isset($_POST["LSTUmbral_avisoHast"]) && $_POST["LSTUmbral_avisoHast"] != "" ) ? $_POST["LSTUmbral_avisoHast"] : "");
		$cEntidad->setSrvTPV((isset($_POST["LSTSrvTPV"]) && $_POST["LSTSrvTPV"] != "") ? $_POST["LSTSrvTPV"] : "");	$cEntidad->setBusqueda("SrvTPV", (isset($_POST["LSTSrvTPV"]) && $_POST["LSTSrvTPV"] != "" ) ? $_POST["LSTSrvTPV"] : "");

		$cEntidad->setIdsPruebas((isset($_POST["LSTIdsPruebas"]) && $_POST["LSTIdsPruebas"] != "") ? $_POST["LSTIdsPruebas"] : "");	$cEntidad->setBusqueda("Pruebas", (isset($_POST["LSTIdsPruebas"]) && $_POST["LSTIdsPruebas"] != "" ) ? $_POST["LSTIdsPruebas"] : "");
		$cEntidad->setIdsPruebasAleatorias((isset($_POST["LSTIdsPruebasAleatorias"]) && $_POST["LSTIdsPruebasAleatorias"] != "") ? $_POST["LSTIdsPruebasAleatorias"] : "");	$cEntidad->setBusqueda("Pruebas", (isset($_POST["LSTIdsPruebasAleatorias"]) && $_POST["LSTIdsPruebasAleatorias"] != "" ) ? $_POST["LSTIdsPruebasAleatorias"] : "");

		$cEntidad->setOrden((isset($_POST["LSTOrden"])) ? $_POST["LSTOrden"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrden"])) ? $_POST["LSTOrden"] : "");
		$cEntidad->setOrdenHast((isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "") ? $_POST["LSTOrdenHast"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN") . " " . constant("STR_HASTA"), (isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "" ) ? $_POST["LSTOrdenHast"] : "");
		$cEntidad->setIndentacion((isset($_POST["LSTIndentacion"])) ? $_POST["LSTIndentacion"] : "");	$cEntidad->setBusqueda(constant("STR_INDENTACION"), (isset($_POST["LSTIndentacion"])) ? $_POST["LSTIndentacion"] : "");
		$cEntidad->setIndentacionHast((isset($_POST["LSTIndentacionHast"]) && $_POST["LSTIndentacionHast"] != "") ? $_POST["LSTIndentacionHast"] : "");	$cEntidad->setBusqueda(constant("STR_INDENTACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTIndentacionHast"]) && $_POST["LSTIndentacionHast"] != "" ) ? $_POST["LSTIndentacionHast"] : "");
		$cEntidad->setUltimoLogin((isset($_POST["LSTUltimoLogin"]) && $_POST["LSTUltimoLogin"] != "") ? $_POST["LSTUltimoLogin"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN"), (isset($_POST["LSTUltimoLogin"]) && $_POST["LSTUltimoLogin"] != "" ) ? $conn->UserDate($_POST["LSTUltimoLogin"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimoLoginHast((isset($_POST["LSTUltimoLoginHast"]) && $_POST["LSTUltimoLoginHast"] != "") ? $_POST["LSTUltimoLoginHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN") . " " . constant("STR_HASTA"), (isset($_POST["LSTUltimoLoginHast"]) && $_POST["LSTUltimoLoginHast"] != "" ) ? $conn->UserDate($_POST["LSTUltimoLoginHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setToken((isset($_POST["LSTToken"]) && $_POST["LSTToken"] != "") ? $_POST["LSTToken"] : "");	$cEntidad->setBusqueda(constant("STR_TOKEN"), (isset($_POST["LSTToken"]) && $_POST["LSTToken"] != "" ) ? $_POST["LSTToken"] : "");
		$cEntidad->setUltimaAcc((isset($_POST["LSTUltimaAcc"]) && $_POST["LSTUltimaAcc"] != "") ? $_POST["LSTUltimaAcc"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMA_ACC"), (isset($_POST["LSTUltimaAcc"]) && $_POST["LSTUltimaAcc"] != "" ) ? $conn->UserDate($_POST["LSTUltimaAcc"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimaAccHast((isset($_POST["LSTUltimaAccHast"]) && $_POST["LSTUltimaAccHast"] != "") ? $_POST["LSTUltimaAccHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMA_ACC") . " " . constant("STR_HASTA"), (isset($_POST["LSTUltimaAccHast"]) && $_POST["LSTUltimaAccHast"] != "" ) ? $conn->UserDate($_POST["LSTUltimaAccHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboWI_USUARIOS;
		$cEntidad->setUsuAlta((isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "") ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_ALTA"), (isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "") ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_MODIFICACION"), (isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
		$cEntidad->setOrderBy((isset($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "");	$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), (isset($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "");
		$cEntidad->setOrder((isset($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "");
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
		if (!empty($_POST['cfPathLogo']) && strtoupper($_POST['cfPathLogo']) == 'ON'){
			$cEntidad->setPathLogo($_POST['cfPathLogo']);
			$bLlamada=true;
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
			$_POST['cfPathLogo'] = 'on';
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

			// Borro las direcciones de destino establecidas anteriormente
			$mail->clearAllRecipients();

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
