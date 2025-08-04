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
	require_once(constant("DIR_WS_COM") . "Funcionalidades/FuncionalidadesDB.php");
	require_once(constant("DIR_WS_COM") . "Funcionalidades/Funcionalidades.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
    $cUtilidades	= new Utilidades();
    
	$cEntidadDB	= new FuncionalidadesDB($conn);  // Entidad DB
	$cEntidad	= new Funcionalidades();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
    $sCol1='';
    $sCol2='';
	
	$comboDENTRO_DE	= new	Combo($conn,"fDentroDe","orden",$conn->Concat("'" . constant("STR_DENTRO_DE") . "'", "' - '", "nombre"),"Descripcion","wi_funcionalidades","","- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -","","","orden");
	$comboDESPUES_DE	= new	Combo($conn,"fDespuesDe","orden",$conn->Concat("'" . constant("STR_DESPUES_DE") . "'", "' - '", "nombre"),"Descripcion","wi_funcionalidades","","- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -","","","orden");
	$comboUSUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","login","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"");
	
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
					if (!empty($_POST['funcionalidades_next_page']) && $_POST['funcionalidades_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'funcionalidades');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Funcionalidades/mntfuncionalidadesl.php');
				}else{
					$cEntidad	= new Funcionalidades();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Funcionalidades/mntfuncionalidadesa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Funcionalidades/mntfuncionalidadesa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['funcionalidades_next_page']) && $_POST['funcionalidades_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'funcionalidades');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Funcionalidades/mntfuncionalidadesl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Funcionalidades/mntfuncionalidadesa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
			if (!empty($_POST['funcionalidades_next_page']) && $_POST['funcionalidades_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'funcionalidades');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Funcionalidades/mntfuncionalidadesl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("orden");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_ORDEN"));
			$cEntidad->setOrder("ASC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "ASC");
			$_POST["LSTOrderBy"] = "orden";
			$_POST["LSTOrder"] = "ASC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Funcionalidades/mntfuncionalidadesa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Funcionalidades/mntfuncionalidadesa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Funcionalidades/mntfuncionalidades.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['funcionalidades_next_page']) && $_POST['funcionalidades_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'funcionalidades');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Funcionalidades/mntfuncionalidadesl.php');
			break;
		default:
			$cEntidad->setOrderBy("orden");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_ORDEN"));
			$cEntidad->setOrder("ASC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "ASC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Funcionalidades/mntfuncionalidades.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
        global $_cEntidadUsuarioTK;
        global $cUtilidades;
		$cEntidad->setIdFuncionalidad((isset($_POST["fIdFuncionalidad"])) ? $_POST["fIdFuncionalidad"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setIdPadre((isset($_POST["fIdPadre"])) ? $_POST["fIdPadre"] : "");
		$cEntidad->setUrl((isset($_POST["fUrl"])) ? $_POST["fUrl"] : "");
		$cEntidad->setPopUp((isset($_POST["fPopUp"])) ? $_POST["fPopUp"] : "");
		$cEntidad->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
		$cEntidad->setDentroDe((isset($_POST["fDentroDe"])) ? $_POST["fDentroDe"] : "");
		$cEntidad->setDespuesDe((isset($_POST["fDespuesDe"])) ? $_POST["fDespuesDe"] : "");
		$cEntidad->setIndentacion((isset($_POST["fIndentacion"])) ? $_POST["fIndentacion"] : "");
		$cEntidad->setBgFile((isset($_POST["fBgFile"])) ? $_POST["fBgFile"] : "");
		$cEntidad->setBgColor((isset($_POST["fBgColor"])) ? $_POST["fBgColor"] : "");
		$cEntidad->setModoDefecto((isset($_POST["fModoDefecto"])) ? $_POST["fModoDefecto"] : "");
		$cEntidad->setIconosMenu((isset($_POST["fIconosMenu"])) ? $_POST["fIconosMenu"] : array());
		$cEntidad->setPublico((isset($_POST["fPublico"])) ? $_POST["fPublico"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getUsuario());
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
        global $conn;
        global $cUtilidades;
		$cEntidad->setIdFuncionalidad((isset($_POST["LSTIdFuncionalidad"])) ? $_POST["LSTIdFuncionalidad"] : "");	$cEntidad->setBusqueda(constant("STR_ID_FUNCIONALIDAD"), (isset($_POST["LSTIdFuncionalidad"])) ? $_POST["LSTIdFuncionalidad"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"])) ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"])) ? $_POST["LSTNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"])) ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"])) ? $_POST["LSTDescripcion"] : "");
		$cEntidad->setUrl((isset($_POST["LSTUrl"])) ? $_POST["LSTUrl"] : "");	$cEntidad->setBusqueda(constant("STR_URL"), (isset($_POST["LSTUrl"])) ? $_POST["LSTUrl"] : "");
		$cEntidad->setPopUp((isset($_POST["LSTPopUp"])) ? $_POST["LSTPopUp"] : "");	$cEntidad->setBusqueda(constant("STR_POPUP"), (isset($_POST["LSTPopUp"])) ? $_POST["LSTPopUp"] : "");
		$cEntidad->setOrden((isset($_POST["LSTOrden"])) ? $_POST["LSTOrden"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrden"])) ? $_POST["LSTOrden"] : "");
		$cEntidad->setIndentacion((isset($_POST["LSTIndentacion"])) ? $_POST["LSTIndentacion"] : "");	$cEntidad->setBusqueda(constant("STR_INDENTACION"), (isset($_POST["LSTIndentacion"])) ? $_POST["LSTIndentacion"] : "");
		$cEntidad->setBgFile((isset($_POST["LSTBgFile"])) ? $_POST["LSTBgFile"] : "");	$cEntidad->setBusqueda(constant("STR_BG_FILE"), (isset($_POST["LSTBgFile"])) ? $_POST["LSTBgFile"] : "");
		$cEntidad->setBgColor((isset($_POST["LSTBgColor"])) ? $_POST["LSTBgColor"] : "");	$cEntidad->setBusqueda(constant("STR_BG_COLOR"), (isset($_POST["LSTBgColor"])) ? $_POST["LSTBgColor"] : "");
		$cEntidad->setModoDefecto((isset($_POST["LSTModoDefecto"])) ? $_POST["LSTModoDefecto"] : "");	$cEntidad->setBusqueda(constant("STR_MODO"), (isset($_POST["LSTModoDefecto"])) ? $_POST["LSTModoDefecto"] : "");
		$cEntidad->setPublico((isset($_POST["LSTPublico"])) ? $_POST["LSTPublico"] : "");	$cEntidad->setBusqueda(constant("STR_PUBLICO"), (isset($_POST["LSTPublico"])) ? $_POST["LSTPublico"] : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_ALTA") . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_MOD"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_MOD") . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboUSUARIOS;
		$cEntidad->setUsuAlta((isset($_POST["LSTUsuAlta"])) ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USU_ALTA"), (isset($_POST["LSTUsuAlta"])) ? $comboUSUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((isset($_POST["LSTUsuMod"])) ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USU_MOD"), (isset($_POST["LSTUsuMod"])) ? $comboUSUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
		$cEntidad->setOrderBy((isset($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "");	$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), (isset($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "");
		$cEntidad->setOrder((isset($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "");
		$cEntidad->setLineasPagina((!empty($_POST["LSTLineasPagina"]) && is_numeric ($_POST["LSTLineasPagina"])) ? $_POST["LSTLineasPagina"] : constant("CNF_LINEAS_PAGINA"));
		$_POST["LSTLineasPagina"] = $cEntidad->getLineasPagina();
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request, los asocia a
	* una determinada Entidad para borrar las imagenes seleccionadas.
	*/
	function quitaImg($cEntidad, $cEntidadDB){
		$bLlamada=false;
		if (!empty($_POST['cfBgFile']) && strtoupper($_POST['cfBgFile']) == 'ON'){
			$cEntidad->setBgFile($_POST['cfBgFile']);
			$bLlamada=true;
		}
		if ($bLlamada){
			$cEntidadDB->quitaImagen($cEntidad);
		}
	}

?>