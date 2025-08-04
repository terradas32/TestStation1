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
	require_once(constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
	require_once(constant("DIR_WS_COM") . "Consumos/Consumos.php");

include_once ('include/conexion.php');

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new ConsumosDB($conn);  // Entidad DB
	$cEntidad	= new Consumos();  // Entidad

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
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","nombre,apellido1,apellido2,mail");
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboBAREMOS	= new Combo($conn,"fIdBaremo","idBaremo","nombre","Descripcion","baremos","",constant("SLC_OPCION"),"","","fecMod");
	$comboDESC_EMPRESAS	= new Combo($conn,"_fNomEmpresa","idEmpresa","nombre","Descripcion","empresas","","","","","fecMod");
	$comboDESC_PROCESOS	= new Combo($conn,"_fNomProceso","idProceso","nombre","Descripcion","procesos","","","","","fecMod");
	$comboDESC_CANDIDATOS	= new Combo($conn,"_fNomCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","","","","","fecMod");
	$comboDESC_PRUEBAS	= new Combo($conn,"_fNomPrueba","idPrueba","nombre","Descripcion","pruebas","","","","","fecMod");
	$comboDESC_TIPOS_INFORMES	= new Combo($conn,"_fNomInforme","idTipoInforme","nombre","Descripcion","tipos_informes","","","","","fecMod");
	$comboDESC_BAREMOS	= new Combo($conn,"_fNomBaremo","idBaremo","nombre","Descripcion","baremos","","","","","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");

	//echo('modo:' . $_POST['MODO']);

	if (!isset($_POST["MODO"])){
	session_start();
    $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
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
					if (!empty($_POST['consumos_next_page']) && $_POST['consumos_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'consumos');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Consumos/mntconsumosl.php');
				}else{
					$cEntidad	= new Consumos();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Consumos/mntconsumosa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Consumos/mntconsumosa.php');
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
				if (!empty($_POST['consumos_next_page']) && $_POST['consumos_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
				$pager = new ADODB_Pager($conn,$sql,'consumos');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Consumos/mntconsumosl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Consumos/mntconsumosa.php');
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
			if (!empty($_POST['consumos_next_page']) && $_POST['consumos_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'consumos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Consumos/mntconsumosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setIdEmpresa($_cEntidadUsuarioTK->getIdEmpresa());
			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Consumos/mntconsumosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Consumos/mntconsumosa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Consumos/mntconsumos.php');
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
				if (!empty($_POST['consumos_next_page']) && $_POST['consumos_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'consumos');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Consumos/mntconsumosl.php');
			break;
		default:
//			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Consumos/mntconsumos.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		$cEntidad->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEntidad->setCodIdiomaInforme((isset($_POST["fCodIdiomaInforme"])) ? $_POST["fCodIdiomaInforme"] : "");
		$cEntidad->setIdTipoInforme((isset($_POST["fIdTipoInforme"])) ? $_POST["fIdTipoInforme"] : "");
		$cEntidad->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
		global $comboDESC_EMPRESAS;
		$sIdEmpresa = (isset($_POST["fIdEmpresa"])  ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setNomEmpresa($comboDESC_EMPRESAS->getDescripcionCombo($sIdEmpresa));
		global $comboDESC_PROCESOS;
		$sIdProceso = (isset($_POST["fIdProceso"])  ? $_POST["fIdProceso"] : "");
		$cEntidad->setNomProceso($comboDESC_PROCESOS->getDescripcionCombo($sIdProceso));
		global $comboDESC_CANDIDATOS;
		$sIdCandidato = (isset($_POST["fIdCandidato"])  ? $_POST["fIdCandidato"] : "");
		$cEntidad->setNomCandidato($comboDESC_CANDIDATOS->getDescripcionCombo($sIdCandidato));
		$cEntidad->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cEntidad->setDni((isset($_POST["fDni"])) ? $_POST["fDni"] : "");
		$cEntidad->setMail((isset($_POST["fMail"])) ? $_POST["fMail"] : "");
		global $comboDESC_WI_IDIOMAS;
		$sCodIdiomaIso2 = (isset($_POST["fCodIdiomaIso2"])  ? $_POST["fCodIdiomaIso2"] : "");
		global $comboDESC_PRUEBAS;
		$sIdPrueba = (isset($_POST["fIdPrueba"])  ? $_POST["fIdPrueba"] : "");
		$cEntidad->setNomPrueba($comboDESC_PRUEBAS->getDescripcionCombo($sIdPrueba));
		$sCodIdiomaIso2 = (isset($_POST["fCodIdiomaIso2"])  ? $_POST["fCodIdiomaIso2"] : "");
		global $comboDESC_TIPOS_INFORMES;
		$sIdTipoInforme = (isset($_POST["fIdTipoInforme"])  ? $_POST["fIdTipoInforme"] : "");
		$cEntidad->setNomInforme($comboDESC_TIPOS_INFORMES->getDescripcionCombo($sIdTipoInforme));
		global $comboDESC_BAREMOS;
		$sIdBaremo = (isset($_POST["fIdBaremo"])  ? $_POST["fIdBaremo"] : "");
		$cEntidad->setNomBaremo($comboDESC_BAREMOS->getDescripcionCombo($sIdBaremo));
		$cEntidad->setConcepto((isset($_POST["fConcepto"])) ? $_POST["fConcepto"] : "");
		$cEntidad->setUnidades((isset($_POST["fUnidades"])) ? $_POST["fUnidades"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
		global $conn;
		global $cUtilidades;
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
		global $comboPROCESOS;
		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $comboPROCESOS->getDescripcionCombo($_POST["LSTIdProceso"]) : "");
		global $comboCANDIDATOS;
		$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","nombre,apellido1,apellido2,mail");
		$cEntidad->setIdCandidato((isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "") ? $_POST["LSTIdCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_CANDIDATO"), (isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "" ) ? $comboCANDIDATOS->getDescripcionCombo($_POST["LSTIdCandidato"]) : "");
		global $comboWI_IDIOMAS;
		$cEntidad->setCodIdiomaIso2((isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "") ? $_POST["LSTCodIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA_PRUEBA"), (isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaIso2"]) : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		$cEntidad->setCodIdiomaInforme((isset($_POST["LSTCodIdiomaInforme"]) && $_POST["LSTCodIdiomaInforme"] != "") ? $_POST["LSTCodIdiomaInforme"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA_INFORME"), (isset($_POST["LSTCodIdiomaInforme"]) && $_POST["LSTCodIdiomaInforme"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaInforme"]) : "");
		global $comboTIPOS_INFORMES;
		$cEntidad->setIdTipoInforme((isset($_POST["LSTIdTipoInforme"]) && $_POST["LSTIdTipoInforme"] != "") ? $_POST["LSTIdTipoInforme"] : "");	$cEntidad->setBusqueda(constant("STR_INFORME"), (isset($_POST["LSTIdTipoInforme"]) && $_POST["LSTIdTipoInforme"] != "" ) ? $comboTIPOS_INFORMES->getDescripcionCombo($_POST["LSTIdTipoInforme"]) : "");
		global $comboBAREMOS;
		$cEntidad->setIdBaremo((isset($_POST["LSTIdBaremo"]) && $_POST["LSTIdBaremo"] != "") ? $_POST["LSTIdBaremo"] : "");	$cEntidad->setBusqueda(constant("STR_BAREMO"), (isset($_POST["LSTIdBaremo"]) && $_POST["LSTIdBaremo"] != "" ) ? $comboBAREMOS->getDescripcionCombo($_POST["LSTIdBaremo"]) : "");
		$cEntidad->setNomEmpresa((isset($_POST["LSTNomEmpresa"]) && $_POST["LSTNomEmpresa"] != "") ? $_POST["LSTNomEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE_EMPRESA"), (isset($_POST["LSTNomEmpresa"]) && $_POST["LSTNomEmpresa"] != "" ) ? $_POST["LSTNomEmpresa"] : "");
		$cEntidad->setNomProceso((isset($_POST["LSTNomProceso"]) && $_POST["LSTNomProceso"] != "") ? $_POST["LSTNomProceso"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE_PROCESO"), (isset($_POST["LSTNomProceso"]) && $_POST["LSTNomProceso"] != "" ) ? $_POST["LSTNomProceso"] : "");
		$cEntidad->setNomCandidato((isset($_POST["LSTNomCandidato"]) && $_POST["LSTNomCandidato"] != "") ? $_POST["LSTNomCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE_CANDIDATO"), (isset($_POST["LSTNomCandidato"]) && $_POST["LSTNomCandidato"] != "" ) ? $_POST["LSTNomCandidato"] : "");
		$cEntidad->setApellido1((isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "") ? $_POST["LSTApellido1"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO1"), (isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "" ) ? $_POST["LSTApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "") ? $_POST["LSTApellido2"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO2"), (isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "" ) ? $_POST["LSTApellido2"] : "");
		$cEntidad->setDni((isset($_POST["LSTDni"]) && $_POST["LSTDni"] != "") ? $_POST["LSTDni"] : "");	$cEntidad->setBusqueda(constant("STR_DNI"), (isset($_POST["LSTDni"]) && $_POST["LSTDni"] != "" ) ? $_POST["LSTDni"] : "");
		$cEntidad->setMail((isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "") ? $_POST["LSTMail"] : "");	$cEntidad->setBusqueda(constant("STR_MAIL"), (isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "" ) ? $_POST["LSTMail"] : "");
		$cEntidad->setNomPrueba((isset($_POST["LSTNomPrueba"]) && $_POST["LSTNomPrueba"] != "") ? $_POST["LSTNomPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTNomPrueba"]) && $_POST["LSTNomPrueba"] != "" ) ? $_POST["LSTNomPrueba"] : "");
		$cEntidad->setNomInforme((isset($_POST["LSTNomInforme"]) && $_POST["LSTNomInforme"] != "") ? $_POST["LSTNomInforme"] : "");	$cEntidad->setBusqueda(constant("STR_INFORME"), (isset($_POST["LSTNomInforme"]) && $_POST["LSTNomInforme"] != "" ) ? $_POST["LSTNomInforme"] : "");
		$cEntidad->setNomBaremo((isset($_POST["LSTNomBaremo"]) && $_POST["LSTNomBaremo"] != "") ? $_POST["LSTNomBaremo"] : "");	$cEntidad->setBusqueda(constant("STR_BAREMO"), (isset($_POST["LSTNomBaremo"]) && $_POST["LSTNomBaremo"] != "" ) ? $_POST["LSTNomBaremo"] : "");
		$cEntidad->setConcepto((isset($_POST["LSTConcepto"]) && $_POST["LSTConcepto"] != "") ? $_POST["LSTConcepto"] : "");	$cEntidad->setBusqueda(constant("STR_CONCEPTO"), (isset($_POST["LSTConcepto"]) && $_POST["LSTConcepto"] != "" ) ? $_POST["LSTConcepto"] : "");
		$cEntidad->setUnidades((isset($_POST["LSTUnidades"]) && $_POST["LSTUnidades"] != "") ? $_POST["LSTUnidades"] : "");	$cEntidad->setBusqueda(constant("STR_UNIDADES"), (isset($_POST["LSTUnidades"]) && $_POST["LSTUnidades"] != "" ) ? $_POST["LSTUnidades"] : "");
		$cEntidad->setUnidadesHast((isset($_POST["LSTUnidadesHast"]) && $_POST["LSTUnidadesHast"] != "") ? $_POST["LSTUnidadesHast"] : "");	$cEntidad->setBusqueda(constant("STR_UNIDADES") . " " . constant("STR_HASTA"), (isset($_POST["LSTUnidadesHast"]) && $_POST["LSTUnidadesHast"] != "" ) ? $_POST["LSTUnidadesHast"] : "");
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
//ob_end_flush();
?>
