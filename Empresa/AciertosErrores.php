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
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new Respuestas_pruebas_itemsDB($conn);  // Entidad DB
	$cEntidad	= new Respuestas_pruebas_items();  // Entidad

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
		$sSQLPruebaIN = "";
	if (!empty($_EmpresaLogada)){
		$cEmpresaPadre->setIdEmpresa($_EmpresaLogada);
		$cEmpresaPadre = $cEmpresaPadreDB->readEntidad($cEmpresaPadre);
		$sSQLPruebaIN = $cEmpresaPadre->getIdsPruebas();
		if (!empty($sSQLPruebaIN)){
			//chequeamos si el primer caracter es una coma
			if (substr($sSQLPruebaIN, 0, 1) == ","){
				$sSQLPruebaIN = substr($sSQLPruebaIN, 1);
			}
			$sSQLPruebaIN = " bajaLog=0 AND idPrueba IN (" . $sSQLPruebaIN . ") ";
		}
	}
	if (empty($sSQLPruebaIN)){
		$sSQLPruebaIN = "bajaLog=0 ";
	}
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboDESC_EMPRESAS	= new Combo($conn,"_fDescEmpresa","idEmpresa","nombre","Descripcion","empresas","","","","","fecMod");
	$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"","","fecMod");
	$comboDESC_PROCESOS	= new Combo($conn,"_fDescProceso","idProceso","nombre","Descripcion","procesos","","","","","fecMod");
	$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","nombre,apellido1,apellido2,mail");
	$comboDESC_CANDIDATOS	= new Combo($conn,"_fDescCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"","","fecMod");
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboDESC_WI_IDIOMAS	= new Combo($conn,"_fDescIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","","","","","fecMod");
//	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),$sSQLPruebaIN,"","","idprueba");
	$comboDESC_PRUEBAS	= new Combo($conn,"_fDescPrueba","idPrueba","nombre","Descripcion","pruebas","","","","","fecMod");
	$comboITEMS	= new Combo($conn,"fIdItem","idItem","enunciado","Descripcion","items","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboDESC_ITEMS	= new Combo($conn,"_fDescItem","idItem","enunciado","Descripcion","items","","","","","fecMod");
	$comboOPCIONES	= new Combo($conn,"fIdOpcion","idOpcion","descripcion","Descripcion","opciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
	$comboDESC_OPCIONES	= new Combo($conn,"_fDescOpcion","idOpcion","descripcion","Descripcion","opciones","","","","","fecMod");
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
					if (!empty($_POST['respuestas_pruebas_items_next_page']) && $_POST['respuestas_pruebas_items_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas_items');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/AciertosErrores/mntrespuestas_pruebas_itemsl.php');
				}else{
					$cEntidad	= new Respuestas_pruebas_items();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/AciertosErrores/mntrespuestas_pruebas_itemsa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/AciertosErrores/mntrespuestas_pruebas_itemsa.php');
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
				if (!empty($_POST['respuestas_pruebas_items_next_page']) && $_POST['respuestas_pruebas_items_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
				$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas_items');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/AciertosErrores/mntrespuestas_pruebas_itemsl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/AciertosErrores/mntrespuestas_pruebas_itemsa.php');
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
			if (!empty($_POST['respuestas_pruebas_items_next_page']) && $_POST['respuestas_pruebas_items_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas_items');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/AciertosErrores/mntrespuestas_pruebas_itemsl.php');
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
			include('Template/AciertosErrores/mntrespuestas_pruebas_itemsa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/AciertosErrores/mntrespuestas_pruebas_itemsa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/AciertosErrores/mntrespuestas_pruebas_items.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if ($cEntidad->getIdEmpresa() == ""){
					$cEntidad->setIdEmpresa($sHijos);
			}
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readListaAciertosErrores($cEntidad);
			}else{
				if (!empty($_POST['respuestas_pruebas_items_next_page']) && $_POST['respuestas_pruebas_items_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaAciertosErrores($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaAciertosErrores($cEntidad);
				}
			}

			$pager = new ADODB_Pager($conn,$sql,'respuestas_pruebas_items');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/AciertosErrores/mntrespuestas_pruebas_itemsl.php');
			break;
		default:
//			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/AciertosErrores/mntrespuestas_pruebas_items.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $conn;
		global $_cEntidadUsuarioTK;
		global $cUtilidades;

		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		global $comboDESC_EMPRESAS;
		$sIdEmpresa = (isset($_POST["fIdEmpresa"])  ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setDescEmpresa($comboDESC_EMPRESAS->getDescripcionCombo($sIdEmpresa));
		$cEntidad->setIdProceso((isset($_POST["fIdProceso"])) ? $_POST["fIdProceso"] : "");
//		global $comboDESC_PROCESOS;
		$comboDESC_PROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $conn->qstr($sIdEmpresa, false),"","fecMod");
		$sIdProceso = (isset($_POST["fIdProceso"])  ? $_POST["fIdProceso"] : "");
		$cEntidad->setDescProceso($comboDESC_PROCESOS->getDescripcionCombo($sIdProceso));
		$cEntidad->setIdCandidato((isset($_POST["fIdCandidato"])) ? $_POST["fIdCandidato"] : "");
//		global $comboDESC_CANDIDATOS;
		$sIdCandidato = (isset($_POST["fIdCandidato"])  ? $_POST["fIdCandidato"] : "");

		$comboDESC_CANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato","nombre","Descripcion","candidatos","","","idEmpresa=" . $conn->qstr($sIdEmpresa, false) . " AND idProceso=" . $conn->qstr($sIdProceso, false). " AND idCandidato=" . $conn->qstr($sIdCandidato, false),"","fecMod");
		$cEntidad->setDescCandidato($comboDESC_CANDIDATOS->getDescripcionCombo($sIdCandidato));
		$cEntidad->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		global $comboDESC_WI_IDIOMAS;
		$sCodIdiomaIso2 = (isset($_POST["fCodIdiomaIso2"])  ? $_POST["fCodIdiomaIso2"] : "");
		$cEntidad->setDescIdiomaIso2($comboDESC_WI_IDIOMAS->getDescripcionCombo($sCodIdiomaIso2));
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		global $comboDESC_PRUEBAS;
		$sIdPrueba = (isset($_POST["fIdPrueba"])  ? $_POST["fIdPrueba"] : "");
		$cEntidad->setDescPrueba($comboDESC_PRUEBAS->getDescripcionCombo($sIdPrueba));
		$cEntidad->setIdItem((isset($_POST["fIdItem"])) ? $_POST["fIdItem"] : "");
//		global $comboDESC_ITEMS;
		$sIdItem = (isset($_POST["fIdItem"])  ? $_POST["fIdItem"] : "");

		$comboDESC_ITEMS	= new Combo($conn,"fIdItem","idItem","enunciado","Descripcion","items","","","codIdiomaIso2=" . $conn->qstr($sCodIdiomaIso2, false) . " AND idPrueba=" . $conn->qstr($sIdPrueba, false) . " AND idItem=" . $conn->qstr($sIdItem, false),"","fecMod");
		$cEntidad->setDescItem($comboDESC_ITEMS->getDescripcionCombo($sIdItem));
		$cEntidad->setIdOpcion((isset($_POST["fIdOpcion"])) ? $_POST["fIdOpcion"] : "");
//		global $comboDESC_OPCIONES;
		$sIdOpcion = (isset($_POST["fIdOpcion"])  ? $_POST["fIdOpcion"] : "");

		$comboDESC_OPCIONES	= new Combo($conn,"fIdOpcion","idOpcion","descripcion","Descripcion","opciones","","","codIdiomaIso2=" . $conn->qstr($sCodIdiomaIso2, false) . " AND idPrueba=" . $conn->qstr($sIdPrueba, false) . " AND idItem=" . $conn->qstr($sIdItem, false) . " AND idOpcion=" . $conn->qstr($sIdOpcion, false),"","fecMod");
		$cEntidad->setDescOpcion($comboDESC_OPCIONES->getDescripcionCombo($sIdOpcion));
		$cEntidad->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
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
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
		$cEntidad->setDescEmpresa((isset($_POST["LSTDescEmpresa"]) && $_POST["LSTDescEmpresa"] != "") ? $_POST["LSTDescEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTDescEmpresa"]) && $_POST["LSTDescEmpresa"] != "" ) ? $_POST["LSTDescEmpresa"] : "");
		global $comboPROCESOS;
		$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","","","idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdProceso((isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "") ? $_POST["LSTIdProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTIdProceso"]) && $_POST["LSTIdProceso"] != "" ) ? $comboPROCESOS->getDescripcionCombo($_POST["LSTIdProceso"]) : "");
		$cEntidad->setDescProceso((isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "") ? $_POST["LSTDescProceso"] : "");	$cEntidad->setBusqueda(constant("STR_PROCESO"), (isset($_POST["LSTDescProceso"]) && $_POST["LSTDescProceso"] != "" ) ? $_POST["LSTDescProceso"] : "");
		global $comboCANDIDATOS;
		$comboCANDIDATOS	= new Combo($conn,"fIdCandidato","idCandidato",$conn->Concat("nombre", "' '", "apellido1", "apellido2", "' ('", "mail" , "')'"),"Descripcion","candidatos","",constant("SLC_OPCION"),"idEmpresa=" . $conn->qstr($cEntidad->getIdEmpresa(), false) . " AND idProceso=" . $conn->qstr($cEntidad->getIdProceso(), false),"","fecMod");
		$cEntidad->setIdCandidato((isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "") ? $_POST["LSTIdCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_CANDIDATO"), (isset($_POST["LSTIdCandidato"]) && $_POST["LSTIdCandidato"] != "" ) ? $comboCANDIDATOS->getDescripcionCombo($_POST["LSTIdCandidato"]) : "");
		$cEntidad->setDescCandidato((isset($_POST["LSTDescCandidato"]) && $_POST["LSTDescCandidato"] != "") ? $_POST["LSTDescCandidato"] : "");	$cEntidad->setBusqueda(constant("STR_CANDIDATO"), (isset($_POST["LSTDescCandidato"]) && $_POST["LSTDescCandidato"] != "" ) ? $_POST["LSTDescCandidato"] : "");
		global $comboWI_IDIOMAS;
		$cEntidad->setCodIdiomaIso2((isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "") ? $_POST["LSTCodIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaIso2"]) : "");
		$cEntidad->setDescIdiomaIso2((isset($_POST["LSTDescIdiomaIso2"]) && $_POST["LSTDescIdiomaIso2"] != "") ? $_POST["LSTDescIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTDescIdiomaIso2"]) && $_POST["LSTDescIdiomaIso2"] != "" ) ? $_POST["LSTDescIdiomaIso2"] : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		$cEntidad->setDescPrueba((isset($_POST["LSTDescPrueba"]) && $_POST["LSTDescPrueba"] != "") ? $_POST["LSTDescPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTDescPrueba"]) && $_POST["LSTDescPrueba"] != "" ) ? $_POST["LSTDescPrueba"] : "");
		global $comboITEMS;
		$cEntidad->setIdItem((isset($_POST["LSTIdItem"]) && $_POST["LSTIdItem"] != "") ? $_POST["LSTIdItem"] : "");	$cEntidad->setBusqueda(constant("STR_ITEM"), (isset($_POST["LSTIdItem"]) && $_POST["LSTIdItem"] != "" ) ? $comboITEMS->getDescripcionCombo($_POST["LSTIdItem"]) : "");
		$cEntidad->setDescItem((isset($_POST["LSTDescItem"]) && $_POST["LSTDescItem"] != "") ? $_POST["LSTDescItem"] : "");	$cEntidad->setBusqueda(constant("STR_ITEM"), (isset($_POST["LSTDescItem"]) && $_POST["LSTDescItem"] != "" ) ? $_POST["LSTDescItem"] : "");
		global $comboOPCIONES;
		$cEntidad->setIdOpcion((isset($_POST["LSTIdOpcion"]) && $_POST["LSTIdOpcion"] != "") ? $_POST["LSTIdOpcion"] : "");	$cEntidad->setBusqueda(constant("STR_OPCION"), (isset($_POST["LSTIdOpcion"]) && $_POST["LSTIdOpcion"] != "" ) ? $comboOPCIONES->getDescripcionCombo($_POST["LSTIdOpcion"]) : "");
		$cEntidad->setDescOpcion((isset($_POST["LSTDescOpcion"]) && $_POST["LSTDescOpcion"] != "") ? $_POST["LSTDescOpcion"] : "");	$cEntidad->setBusqueda(constant("STR_OPCION"), (isset($_POST["LSTDescOpcion"]) && $_POST["LSTDescOpcion"] != "" ) ? $_POST["LSTDescOpcion"] : "");
		$cEntidad->setOrden((isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "") ? $_POST["LSTOrden"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "" ) ? $_POST["LSTOrden"] : "");
		$cEntidad->setOrdenHast((isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "") ? $_POST["LSTOrdenHast"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN") . " " . constant("STR_HASTA"), (isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "" ) ? $_POST["LSTOrdenHast"] : "");
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

?>
