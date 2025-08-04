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
	require_once(constant("DIR_WS_COM") . "Export_aptitudinales/Export_aptitudinalesDB.php");
	require_once(constant("DIR_WS_COM") . "Export_aptitudinales/Export_aptitudinales.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new Export_aptitudinalesDB($conn);  // Entidad DB
	$cEntidad	= new Export_aptitudinales();  // Entidad

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";

	include_once('include/type_empresa_usuario.php');

	if (empty($_POST["fHijos"]))
	{
		require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
		require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
		$cEmpresaPadre = new Empresas();
		$cEmpresaPadreDB = new EmpresasDB($conn);
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
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","","","idEmpresa IN(" . $sHijos . ")","","orden");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","nombre,apellido1,apellido2,mail");
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","nombre","nombre","nombre","pruebas","","","idTipoPrueba IN (2,5) AND idPrueba IN (" . $sPuebasEmpresa . ") ","","","nombre");
	$comboTIPOS_INFORMES	= new Combo($conn,"fIdTipoInforme","idTipoInforme","nombre","Descripcion","tipos_informes","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboBAREMOS	= new Combo($conn,"fIdBaremo","idBaremo","nombre","Descripcion","baremos","",constant("SLC_OPCION"),"","","fecMod");
	$comboDESC_EMPRESAS	= new Combo($conn,"_fNomEmpresa","idEmpresa","nombre","Descripcion","empresas","","","","","fecMod");
	$comboDESC_PROCESOS	= new Combo($conn,"_fNomProceso","idProceso","nombre","Descripcion","procesos","","","","","fecMod");
	$comboDESC_CANDIDATOS	= new Combo($conn,"_fNomCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","","","","","fecMod");
	$comboDESC_PRUEBAS	= new Combo($conn,"_fNomPrueba","idPrueba","nombre","Descripcion","pruebas","","","","","fecMod");
	$comboDESC_TIPOS_INFORMES	= new Combo($conn,"_fNomInforme","idTipoInforme","nombre","Descripcion","tipos_informes","","","","","fecMod");
	$comboDESC_BAREMOS	= new Combo($conn,"_fNomBaremo","idBaremo","nombre","Descripcion","baremos","","","","","fecMod");
	$comboSEXOS	= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboEDADES	= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboFORMACIONES	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboNIVELESJERARQUICOS	= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboAREAS	= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
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
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					$cEntidad = readLista($cEntidad);
					if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
					}
					if (!empty($_POST['export_aptitudinales_next_page']) && $_POST['export_aptitudinales_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'export_aptitudinales');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Export_aptitudinales/mntexport_aptitudinalesl.php');
				}else{
					$cEntidad	= new Export_aptitudinales();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Export_aptitudinales/mntexport_aptitudinalesa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Export_aptitudinales/mntexport_aptitudinalesa.php');
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
				if (!empty($_POST['export_aptitudinales_next_page']) && $_POST['export_aptitudinales_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'export_aptitudinales');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Export_aptitudinales/mntexport_aptitudinalesl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Export_aptitudinales/mntexport_aptitudinalesa.php');
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
			if (!empty($_POST['export_aptitudinales_next_page']) && $_POST['export_aptitudinales_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'export_aptitudinales');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Export_aptitudinales/mntexport_aptitudinalesl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setIdEmpresa(constant("EMPRESA_PE"));
			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Export_aptitudinales/mntexport_aptitudinalesa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Export_aptitudinales/mntexport_aptitudinalesa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Export_aptitudinales/mntexport_aptitudinales.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
				$cEntidad->setIdEmpresa($sHijos);
			}
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['export_aptitudinales_next_page']) && $_POST['export_aptitudinales_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'export_aptitudinales');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Export_aptitudinales/mntexport_aptitudinalesl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Export_aptitudinales/mntexport_aptitudinales.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setDescEmpresa((isset($_POST["fDescEmpresa"])) ? $_POST["fDescEmpresa"] : "");
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
		$cEntidad->setDescProceso((isset($_POST["fDescProceso"])) ? $_POST["fDescProceso"] : "");
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cEntidad->setEmail((isset($_POST["fEmail"])) ? $_POST["fEmail"] : "");
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEntidad->setDescPrueba((isset($_POST["fDescPrueba"])) ? $_POST["fDescPrueba"] : "");
		$cEntidad->setFecPrueba((isset($_POST["fFecPrueba"])) ? $_POST["fFecPrueba"] : "");
		$cEntidad->setIdBaremo((isset($_POST["fIdBaremo"])) ? $_POST["fIdBaremo"] : "");
		$cEntidad->setDescBaremo((isset($_POST["fDescBaremo"])) ? $_POST["fDescBaremo"] : "");
		$cEntidad->setFecAltaProceso((isset($_POST["fFecAltaProceso"])) ? $_POST["fFecAltaProceso"] : "");
		$cEntidad->setIdSexo((isset($_POST["fIdSexo"])) ? $_POST["fIdSexo"] : "");
		$cEntidad->setDescSexo((isset($_POST["fDescSexo"])) ? $_POST["fDescSexo"] : "");
		$cEntidad->setIdEdad((isset($_POST["fIdEdad"])) ? $_POST["fIdEdad"] : "");
		$cEntidad->setDescEdad((isset($_POST["fDescEdad"])) ? $_POST["fDescEdad"] : "");
		$cEntidad->setIdFormacion((isset($_POST["fIdFormacion"])) ? $_POST["fIdFormacion"] : "");
		$cEntidad->setDescFormacion((isset($_POST["fDescFormacion"])) ? $_POST["fDescFormacion"] : "");
		$cEntidad->setIdNivel((isset($_POST["fIdNivel"])) ? $_POST["fIdNivel"] : "");
		$cEntidad->setDescNivel((isset($_POST["fDescNivel"])) ? $_POST["fDescNivel"] : "");
		$cEntidad->setIdArea((isset($_POST["fIdArea"])) ? $_POST["fIdArea"] : "");
		$cEntidad->setDescArea((isset($_POST["fDescArea"])) ? $_POST["fDescArea"] : "");
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
		$_Empresas = "";
		if (isset($_POST["LSTIdEmpresa"])){
			if (is_array($_POST["LSTIdEmpresa"])){
				if (!in_array(" ", $_POST["LSTIdEmpresa"])){
					$_Empresas = implode(",", $_POST["LSTIdEmpresa"]);
				}
			}else{
				$_Empresas = $_POST["LSTIdEmpresa"];
			}
		}
		$cEntidad->setIdEmpresa($_Empresas);	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_Empresas) : "");
		$cEntidad->setDescEmpresa((isset($_POST["LSTDescEmpresa"]) && $_POST["LSTDescEmpresa"] != "") ? $_POST["LSTDescEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTDescEmpresa"]) && $_POST["LSTDescEmpresa"] != "" ) ? $_POST["LSTDescEmpresa"] : "");
		global $comboPROCESOS;
	//	$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $comboPROCESOS->getDescripcionCombo($_POST["LSTIdProceso"]) : "");
		$_IdProcesos = "";
		if (isset($_POST["LSTIdProceso"])){
			if (is_array($_POST["LSTIdProceso"])){
				if (!in_array(" ", $_POST["LSTIdProceso"])){
					$sIdProcesos = implode(",", $_POST["LSTIdProceso"]);
					$aIdProcesos = explode(",", $sIdProcesos);
					for ($i=0, $max = sizeof($aIdProcesos); $i < $max; $i++){
						if ($max > 1){
							$_IdProcesos .=",'" . addslashes($aIdProcesos[$i]) . "'";
						}else{
							$_IdProcesos .="," . addslashes($aIdProcesos[$i]) . "";
						}
					}
					if (!empty($_IdProcesos)){
						if ($_IdProcesos != ",''"){
							$_IdProcesos = substr($_IdProcesos, 1);
						}else {
							$_IdProcesos="";
						}
					}
				}
			}else{
				$_IdProcesos = $_POST["LSTIdProceso"];
			}
		}
		$cEntidad->setIdProceso($_IdProcesos);	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? str_replace("'","", $_IdProcesos) : "");

//		$cEntidad->setDescProceso((isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "") ? $_POST["LSTDescProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "" ) ? $_POST["LSTDescProceso"] : "");
		$_Procesos = "";
		if (isset($_POST["LSTDescProceso"])){
			if (is_array($_POST["LSTDescProceso"])){
				if (!in_array(" ", $_POST["LSTDescProceso"])){
					$sProcesos = implode(",", $_POST["LSTDescProceso"]);
					$aProcesos = explode(",", $sProcesos);
					for ($i=0, $max = sizeof($aProcesos); $i < $max; $i++){
						if ($max > 1){
							$_Procesos .=",'" . addslashes($aProcesos[$i]) . "'";
						}else{
							$_Procesos .="," . addslashes($aProcesos[$i]) . "";
						}
					}
					if (!empty($_Procesos)){
						if ($_Procesos != ",''"){
							$_Procesos = substr($_Procesos, 1);
						}else {
							$_Procesos="";
						}
					}
				}
			}else{
				$_Procesos = $_POST["LSTDescProceso"];
			}
		}
		$cEntidad->setDescProceso($_Procesos);	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "" ) ? str_replace("'","", $_Procesos) : "");

		global $comboCANDIDATOS;
		$cEntidad->setIdCandidato((isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "") ? $_POST["LSTIdCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_ID_CANDIDATO"), (isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "" ) ? $comboCANDIDATOS->getDescripcionCombo($_POST["LSTIdCandidato"]) : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "") ? $_POST["LSTApellido1"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO1"), (isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "" ) ? $_POST["LSTApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "") ? $_POST["LSTApellido2"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO2"), (isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "" ) ? $_POST["LSTApellido2"] : "");
		$cEntidad->setEmail((isset($_POST["LSTEmail"]) && $_POST["LSTEmail"] != "") ? $_POST["LSTEmail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (isset($_POST["LSTEmail"]) && $_POST["LSTEmail"] != "" ) ? $_POST["LSTEmail"] : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		$_Pruebas = "";
		if (isset($_POST["LSTDescPrueba"])){
			if (is_array($_POST["LSTDescPrueba"])){
				if (!in_array(" ", $_POST["LSTDescPrueba"])){
					$sPruebas = implode(",", $_POST["LSTDescPrueba"]);
					$aPruebas = explode(",", $sPruebas);
					for ($i=0, $max = sizeof($aPruebas); $i < $max; $i++){
						$_Pruebas .=",'" . addslashes($aPruebas[$i]) . "'";
					}
					if (!empty($_Pruebas)){
						if ($_Pruebas != ",''"){
							$_Pruebas = substr($_Pruebas, 1);
						}else {
							$_Pruebas="";
						}
					}
				}
			}else{
				$_Pruebas = $_POST["LSTDescPrueba"];
			}
		}
		$cEntidad->setDescPrueba($_Pruebas);	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTDescPrueba"]) && $_POST["LSTDescPrueba"] != "" ) ? str_replace("'","", $_Pruebas) : "");

		$cEntidad->setFecPrueba((isset($_POST["LSTFecPrueba"]) && $_POST["LSTFecPrueba"] != "") ? $_POST["LSTFecPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_PRUEBA"), (isset($_POST["LSTFecPrueba"]) && $_POST["LSTFecPrueba"] != "" ) ? $conn->UserDate($_POST["LSTFecPrueba"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecPruebaHast((isset($_POST["LSTFecPruebaHast"]) && $_POST["LSTFecPruebaHast"] != "") ? $_POST["LSTFecPruebaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_PRUEBA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecPruebaHast"]) && $_POST["LSTFecPruebaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecPruebaHast"],constant("USR_FECHA"),false) : "");
		global $comboBAREMOS;
		$cEntidad->setIdBaremo((isset($_POST["LSTIdBaremo"]) && $_POST["LSTIdBaremo"] != "") ? $_POST["LSTIdBaremo"] : "");	$cEntidad->setBusqueda(constant("STR_ID_BAREMO"), (isset($_POST["LSTIdBaremo"]) && $_POST["LSTIdBaremo"] != "" ) ? $comboBAREMOS->getDescripcionCombo($_POST["LSTIdBaremo"]) : "");
		$cEntidad->setDescBaremo((isset($_POST["LSTDescBaremo"]) && $_POST["LSTDescBaremo"] != "") ? $_POST["LSTDescBaremo"] : "");	$cEntidad->setBusqueda(constant("STR_BAREMO"), (isset($_POST["LSTDescBaremo"]) && $_POST["LSTDescBaremo"] != "" ) ? $_POST["LSTDescBaremo"] : "");
		$cEntidad->setFecAltaProceso((isset($_POST["LSTFecAltaProceso"]) && $_POST["LSTFecAltaProceso"] != "") ? $_POST["LSTFecAltaProceso"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA_PROCESO"), (isset($_POST["LSTFecAltaProceso"]) && $_POST["LSTFecAltaProceso"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaProceso"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaProcesoHast((isset($_POST["LSTFecAltaProcesoHast"]) && $_POST["LSTFecAltaProcesoHast"] != "") ? $_POST["LSTFecAltaProcesoHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA_PROCESO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaProcesoHast"]) && $_POST["LSTFecAltaProcesoHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaProcesoHast"],constant("USR_FECHA"),false) : "");
		global $comboSEXOS;
		$cEntidad->setIdSexo((isset($_POST["LSTIdSexo"]) && $_POST["LSTIdSexo"] != "") ? $_POST["LSTIdSexo"] : "");	$cEntidad->setBusqueda(constant("STR_ID_SEXO"), (isset($_POST["LSTIdSexo"]) && $_POST["LSTIdSexo"] != "" ) ? $comboSEXOS->getDescripcionCombo($_POST["LSTIdSexo"]) : "");
		$cEntidad->setDescSexo((isset($_POST["LSTDescSexo"]) && $_POST["LSTDescSexo"] != "") ? $_POST["LSTDescSexo"] : "");	$cEntidad->setBusqueda(constant("STR_SEXO"), (isset($_POST["LSTDescSexo"]) && $_POST["LSTDescSexo"] != "" ) ? $_POST["LSTDescSexo"] : "");
		global $comboEDADES;
		$cEntidad->setIdEdad((isset($_POST["LSTIdEdad"]) && $_POST["LSTIdEdad"] != "") ? $_POST["LSTIdEdad"] : "");	$cEntidad->setBusqueda(constant("STR_ID_EDAD"), (isset($_POST["LSTIdEdad"]) && $_POST["LSTIdEdad"] != "" ) ? $comboEDADES->getDescripcionCombo($_POST["LSTIdEdad"]) : "");
		$cEntidad->setDescEdad((isset($_POST["LSTDescEdad"]) && $_POST["LSTDescEdad"] != "") ? $_POST["LSTDescEdad"] : "");	$cEntidad->setBusqueda(constant("STR_EDAD"), (isset($_POST["LSTDescEdad"]) && $_POST["LSTDescEdad"] != "" ) ? $_POST["LSTDescEdad"] : "");
		global $comboFORMACIONES;
		$cEntidad->setIdFormacion((isset($_POST["LSTIdFormacion"]) && $_POST["LSTIdFormacion"] != "") ? $_POST["LSTIdFormacion"] : "");	$cEntidad->setBusqueda(constant("STR_IDFORMACION"), (isset($_POST["LSTIdFormacion"]) && $_POST["LSTIdFormacion"] != "" ) ? $comboFORMACIONES->getDescripcionCombo($_POST["LSTIdFormacion"]) : "");
		$cEntidad->setDescFormacion((isset($_POST["LSTDescFormacion"]) && $_POST["LSTDescFormacion"] != "") ? $_POST["LSTDescFormacion"] : "");	$cEntidad->setBusqueda(constant("STR_FORMACION"), (isset($_POST["LSTDescFormacion"]) && $_POST["LSTDescFormacion"] != "" ) ? $_POST["LSTDescFormacion"] : "");
		global $comboNIVELESJERARQUICOS;
		$cEntidad->setIdNivel((isset($_POST["LSTIdNivel"]) && $_POST["LSTIdNivel"] != "") ? $_POST["LSTIdNivel"] : "");	$cEntidad->setBusqueda(constant("STR_ID_NIVEL"), (isset($_POST["LSTIdNivel"]) && $_POST["LSTIdNivel"] != "" ) ? $comboNIVELESJERARQUICOS->getDescripcionCombo($_POST["LSTIdNivel"]) : "");
		$cEntidad->setDescNivel((isset($_POST["LSTDescNivel"]) && $_POST["LSTDescNivel"] != "") ? $_POST["LSTDescNivel"] : "");	$cEntidad->setBusqueda(constant("STR_NIVEL"), (isset($_POST["LSTDescNivel"]) && $_POST["LSTDescNivel"] != "" ) ? $_POST["LSTDescNivel"] : "");
		global $comboAREAS;
		$cEntidad->setIdArea((isset($_POST["LSTIdArea"]) && $_POST["LSTIdArea"] != "") ? $_POST["LSTIdArea"] : "");	$cEntidad->setBusqueda(constant("STR_ID_AREA"), (isset($_POST["LSTIdArea"]) && $_POST["LSTIdArea"] != "" ) ? $comboAREAS->getDescripcionCombo($_POST["LSTIdArea"]) : "");
		$cEntidad->setDescArea((isset($_POST["LSTDescArea"]) && $_POST["LSTDescArea"] != "") ? $_POST["LSTDescArea"] : "");	$cEntidad->setBusqueda(constant("STR_AREA"), (isset($_POST["LSTDescArea"]) && $_POST["LSTDescArea"] != "" ) ? $_POST["LSTDescArea"] : "");

		$cEntidad->setCobrado("1");

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
