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
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpv.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new Candidatos_pagos_tpvDB($conn);  // Entidad DB
	$cEntidad	= new Candidatos_pagos_tpv();  // Entidad
	
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
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato","nombre","Descripcion","candidatos","",constant("SLC_OPCION"),"","","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	
	//echo('modo:' . $_POST['MODO']);
	
	if (!isset($_POST["MODO"])){
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
					if (!empty($_POST['candidatos_pagos_tpv_next_page']) && $_POST['candidatos_pagos_tpv_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'candidatos_pagos_tpv');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpvl.php');
				}else{
					$cEntidad	= new Candidatos_pagos_tpv();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpva.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpva.php');
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
				if (!empty($_POST['candidatos_pagos_tpv_next_page']) && $_POST['candidatos_pagos_tpv_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
				$pager = new ADODB_Pager($conn,$sql,'candidatos_pagos_tpv');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpvl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpva.php');
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
			if (!empty($_POST['candidatos_pagos_tpv_next_page']) && $_POST['candidatos_pagos_tpv_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'candidatos_pagos_tpv');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpvl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpva.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpva.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpv.php');
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
				if (!empty($_POST['candidatos_pagos_tpv_next_page']) && $_POST['candidatos_pagos_tpv_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'candidatos_pagos_tpv');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpvl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Candidatos_pagos_tpv/mntcandidatos_pagos_tpv.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdRecarga((isset($_POST["fIdRecarga"])) ? $_POST["fIdRecarga"] : "");
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		$cEntidad->setLocalizador((isset($_POST["fLocalizador"])) ? $_POST["fLocalizador"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setImpBase((isset($_POST["fImpBase"])) ? $_POST["fImpBase"] : "");
		$cEntidad->setImpImpuestos((isset($_POST["fImpImpuestos"])) ? $_POST["fImpImpuestos"] : "");
		$cEntidad->setImpBaseImpuestos((isset($_POST["fImpBaseImpuestos"])) ? $_POST["fImpBaseImpuestos"] : "");
		$cEntidad->setEmail((isset($_POST["fEmail"])) ? $_POST["fEmail"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setApellidos((isset($_POST["fApellidos"])) ? $_POST["fApellidos"] : "");
		$cEntidad->setDireccion((isset($_POST["fDireccion"])) ? $_POST["fDireccion"] : "");
		$cEntidad->setCodPostal((isset($_POST["fCodPostal"])) ? $_POST["fCodPostal"] : "");
		$cEntidad->setCiudad((isset($_POST["fCiudad"])) ? $_POST["fCiudad"] : "");
		$cEntidad->setTelefono1((isset($_POST["fTelefono1"])) ? $_POST["fTelefono1"] : "");
		$cEntidad->setCodEstado((isset($_POST["fCodEstado"])) ? $_POST["fCodEstado"] : "");
		$cEntidad->setCodAutorizacion((isset($_POST["fCodAutorizacion"])) ? $_POST["fCodAutorizacion"] : "");
		$cEntidad->setCodError((isset($_POST["fCodError"])) ? $_POST["fCodError"] : "");
		$cEntidad->setDesError((isset($_POST["fDesError"])) ? $_POST["fDesError"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
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
		$cEntidad->setIdRecarga((isset($_POST["LSTIdRecarga"]) && $_POST["LSTIdRecarga"] != "") ? $_POST["LSTIdRecarga"] : "");	$cEntidad->setBusqueda(constant("STR_ID_RECARGA"), (isset($_POST["LSTIdRecarga"]) && $_POST["LSTIdRecarga"] != "" ) ? $_POST["LSTIdRecarga"] : "");
		$cEntidad->setIdRecargaHast((isset($_POST["LSTIdRecargaHast"]) && $_POST["LSTIdRecargaHast"] != "") ? $_POST["LSTIdRecargaHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_RECARGA") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdRecargaHast"]) && $_POST["LSTIdRecargaHast"] != "" ) ? $_POST["LSTIdRecargaHast"] : "");
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
//		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"idEmpresa IN (" . $cEntidad->getIdEmpresa() . ")","","fecMod");
		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $comboPROCESOS->getDescripcionCombo($_POST["LSTIdProceso"]) : "");
		$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2"),"Descripcion","candidatos","","","idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdCandidato((isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "") ? $_POST["LSTIdCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_CANDIDATO"), (isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "" ) ? $comboCANDIDATOS->getDescripcionCombo($_POST["LSTIdCandidato"]) : "");
		$cEntidad->setLocalizador((isset($_POST["LSTLocalizador"]) && $_POST["LSTLocalizador"] != "") ? $_POST["LSTLocalizador"] : "");	$cEntidad->setBusqueda(constant("STR_LOCALIZADOR"), (isset($_POST["LSTLocalizador"]) && $_POST["LSTLocalizador"] != "" ) ? $_POST["LSTLocalizador"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		$cEntidad->setImpBase((isset($_POST["LSTImpBase"]) && $_POST["LSTImpBase"] != "") ? $_POST["LSTImpBase"] : "");	$cEntidad->setBusqueda(constant("STR_IMP_BASE"), (isset($_POST["LSTImpBase"]) && $_POST["LSTImpBase"] != "" ) ? $_POST["LSTImpBase"] : "");
		$cEntidad->setImpBaseHast((isset($_POST["LSTImpBaseHast"]) && $_POST["LSTImpBaseHast"] != "") ? $_POST["LSTImpBaseHast"] : "");	$cEntidad->setBusqueda(constant("STR_IMP_BASE") . " " . constant("STR_HASTA"), (isset($_POST["LSTImpBaseHast"]) && $_POST["LSTImpBaseHast"] != "" ) ? $_POST["LSTImpBaseHast"] : "");
		$cEntidad->setImpImpuestos((isset($_POST["LSTImpImpuestos"]) && $_POST["LSTImpImpuestos"] != "") ? $_POST["LSTImpImpuestos"] : "");	$cEntidad->setBusqueda(constant("STR_IMP_IMPUESTOS"), (isset($_POST["LSTImpImpuestos"]) && $_POST["LSTImpImpuestos"] != "" ) ? $_POST["LSTImpImpuestos"] : "");
		$cEntidad->setImpImpuestosHast((isset($_POST["LSTImpImpuestosHast"]) && $_POST["LSTImpImpuestosHast"] != "") ? $_POST["LSTImpImpuestosHast"] : "");	$cEntidad->setBusqueda(constant("STR_IMP_IMPUESTOS") . " " . constant("STR_HASTA"), (isset($_POST["LSTImpImpuestosHast"]) && $_POST["LSTImpImpuestosHast"] != "" ) ? $_POST["LSTImpImpuestosHast"] : "");
		$cEntidad->setImpBaseImpuestos((isset($_POST["LSTImpBaseImpuestos"]) && $_POST["LSTImpBaseImpuestos"] != "") ? $_POST["LSTImpBaseImpuestos"] : "");	$cEntidad->setBusqueda(constant("STR_IMP_BASE_IMPUESTOS"), (isset($_POST["LSTImpBaseImpuestos"]) && $_POST["LSTImpBaseImpuestos"] != "" ) ? $_POST["LSTImpBaseImpuestos"] : "");
		$cEntidad->setImpBaseImpuestosHast((isset($_POST["LSTImpBaseImpuestosHast"]) && $_POST["LSTImpBaseImpuestosHast"] != "") ? $_POST["LSTImpBaseImpuestosHast"] : "");	$cEntidad->setBusqueda(constant("STR_IMP_BASE_IMPUESTOS") . " " . constant("STR_HASTA"), (isset($_POST["LSTImpBaseImpuestosHast"]) && $_POST["LSTImpBaseImpuestosHast"] != "" ) ? $_POST["LSTImpBaseImpuestosHast"] : "");
		$cEntidad->setEmail((isset($_POST["LSTEmail"]) && $_POST["LSTEmail"] != "") ? $_POST["LSTEmail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (isset($_POST["LSTEmail"]) && $_POST["LSTEmail"] != "" ) ? $_POST["LSTEmail"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setApellidos((isset($_POST["LSTApellidos"]) && $_POST["LSTApellidos"] != "") ? $_POST["LSTApellidos"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDOS"), (isset($_POST["LSTApellidos"]) && $_POST["LSTApellidos"] != "" ) ? $_POST["LSTApellidos"] : "");
		$cEntidad->setDireccion((isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "") ? $_POST["LSTDireccion"] : "");	$cEntidad->setBusqueda(constant("STR_DIRECCION"), (isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "" ) ? $_POST["LSTDireccion"] : "");
		$cEntidad->setCodPostal((isset($_POST["LSTCodPostal"]) && $_POST["LSTCodPostal"] != "") ? $_POST["LSTCodPostal"] : "");	$cEntidad->setBusqueda(constant("STR_COD_POSTAL"), (isset($_POST["LSTCodPostal"]) && $_POST["LSTCodPostal"] != "" ) ? $_POST["LSTCodPostal"] : "");
		$cEntidad->setCiudad((isset($_POST["LSTCiudad"]) && $_POST["LSTCiudad"] != "") ? $_POST["LSTCiudad"] : "");	$cEntidad->setBusqueda(constant("STR_CIUDAD"), (isset($_POST["LSTCiudad"]) && $_POST["LSTCiudad"] != "" ) ? $_POST["LSTCiudad"] : "");
		$cEntidad->setTelefono1((isset($_POST["LSTTelefono1"]) && $_POST["LSTTelefono1"] != "") ? $_POST["LSTTelefono1"] : "");	$cEntidad->setBusqueda(constant("STR_TELEFONO1"), (isset($_POST["LSTTelefono1"]) && $_POST["LSTTelefono1"] != "" ) ? $_POST["LSTTelefono1"] : "");
		$cEntidad->setCodEstado((isset($_POST["LSTCodEstado"]) && $_POST["LSTCodEstado"] != "") ? $_POST["LSTCodEstado"] : "");	$cEntidad->setBusqueda(constant("STR_COD_ESTADO"), (isset($_POST["LSTCodEstado"]) && $_POST["LSTCodEstado"] != "" ) ? $_POST["LSTCodEstado"] : "");
		$cEntidad->setCodAutorizacion((isset($_POST["LSTCodAutorizacion"]) && $_POST["LSTCodAutorizacion"] != "") ? $_POST["LSTCodAutorizacion"] : "");	$cEntidad->setBusqueda(constant("STR_COD_AUTORIZACION"), (isset($_POST["LSTCodAutorizacion"]) && $_POST["LSTCodAutorizacion"] != "" ) ? $_POST["LSTCodAutorizacion"] : "");
		$cEntidad->setCodError((isset($_POST["LSTCodError"]) && $_POST["LSTCodError"] != "") ? $_POST["LSTCodError"] : "");	$cEntidad->setBusqueda(constant("STR_COD_ERROR"), (isset($_POST["LSTCodError"]) && $_POST["LSTCodError"] != "" ) ? $_POST["LSTCodError"] : "");
		$cEntidad->setDesError((isset($_POST["LSTDesError"]) && $_POST["LSTDesError"] != "") ? $_POST["LSTDesError"] : "");	$cEntidad->setBusqueda(constant("STR_DESC_ERROR"), (isset($_POST["LSTDesError"]) && $_POST["LSTDesError"] != "" ) ? $_POST["LSTDesError"] : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboWI_USUARIOS;
		$cEntidad->setUsuAlta((isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "") ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_ALTA"), (isset($_POST["LSTUsuAlta"]) && $_POST["LSTUsuAlta"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "") ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO_DE_MODIFICACION"), (isset($_POST["LSTUsuMod"]) && $_POST["LSTUsuMod"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
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
		if ($bLlamada){
			$cEntidadDB->quitaImagen($cEntidad);
		}
	}
//ob_end_flush();
?>