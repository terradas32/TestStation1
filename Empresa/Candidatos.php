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
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
	$cEntidad	= new Candidatos();  // Entidad

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
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
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
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Candidatos/mntcandidatosa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
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
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Candidatos/mntcandidatosa.php');
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
		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"idEmpresa IN (" . $cEntidad->getIdEmpresa() . ")","","fecMod");
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

?>
