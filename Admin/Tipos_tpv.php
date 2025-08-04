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
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpv.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new Tipos_tpvDB($conn);  // Entidad DB
	$cEntidad	= new Tipos_tpv();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
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
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					if (!empty($_POST['tipos_tpv_next_page']) && $_POST['tipos_tpv_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'tipos_tpv');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Tipos_tpv/mnttipos_tpvl.php');
				}else{
					$cEntidad	= new Tipos_tpv();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Tipos_tpv/mnttipos_tpva.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Tipos_tpv/mnttipos_tpva.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['tipos_tpv_next_page']) && $_POST['tipos_tpv_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'tipos_tpv');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Tipos_tpv/mnttipos_tpvl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Tipos_tpv/mnttipos_tpva.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
			}
					if (!empty($_POST['tipos_tpv_next_page']) && $_POST['tipos_tpv_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'tipos_tpv');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Tipos_tpv/mnttipos_tpvl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Tipos_tpv/mnttipos_tpva.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Tipos_tpv/mnttipos_tpva.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Tipos_tpv/mnttipos_tpv.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['tipos_tpv_next_page']) && $_POST['tipos_tpv_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'tipos_tpv');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Tipos_tpv/mnttipos_tpvl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Tipos_tpv/mnttipos_tpv.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdTipoTpv((isset($_POST["fIdTipoTpv"])) ? $_POST["fIdTipoTpv"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setTERMINAL_TYPE((isset($_POST["fTERMINAL_TYPE"])) ? $_POST["fTERMINAL_TYPE"] : "");
		$cEntidad->setOPERATION_TYPE((isset($_POST["fOPERATION_TYPE"])) ? $_POST["fOPERATION_TYPE"] : "");
		$cEntidad->setURL_NOTIFY((isset($_POST["fURL_NOTIFY"])) ? $_POST["fURL_NOTIFY"] : "");
		$cEntidad->setURL_OK((isset($_POST["fURL_OK"])) ? $_POST["fURL_OK"] : "");
		$cEntidad->setURL_NOOK((isset($_POST["fURL_NOOK"])) ? $_POST["fURL_NOOK"] : "");
		$cEntidad->setSERVICE_ACTION((isset($_POST["fSERVICE_ACTION"])) ? $_POST["fSERVICE_ACTION"] : "");
		$cEntidad->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
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
		$cEntidad->setIdTipoTpv((isset($_POST["LSTIdTipoTpv"]) && $_POST["LSTIdTipoTpv"] != "") ? $_POST["LSTIdTipoTpv"] : "");	$cEntidad->setBusqueda(constant("STR_ID_TIPO_TPV"), (isset($_POST["LSTIdTipoTpv"]) && $_POST["LSTIdTipoTpv"] != "" ) ? $_POST["LSTIdTipoTpv"] : "");
		$cEntidad->setIdTipoTpvHast((isset($_POST["LSTIdTipoTpvHast"]) && $_POST["LSTIdTipoTpvHast"] != "") ? $_POST["LSTIdTipoTpvHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_TIPO_TPV") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdTipoTpvHast"]) && $_POST["LSTIdTipoTpvHast"] != "" ) ? $_POST["LSTIdTipoTpvHast"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		$cEntidad->setTERMINAL_TYPE((isset($_POST["LSTTERMINAL_TYPE"]) && $_POST["LSTTERMINAL_TYPE"] != "") ? $_POST["LSTTERMINAL_TYPE"] : "");	$cEntidad->setBusqueda(constant("STR_TERMINAL_TYPE"), (isset($_POST["LSTTERMINAL_TYPE"]) && $_POST["LSTTERMINAL_TYPE"] != "" ) ? $_POST["LSTTERMINAL_TYPE"] : "");
		$cEntidad->setOPERATION_TYPE((isset($_POST["LSTOPERATION_TYPE"]) && $_POST["LSTOPERATION_TYPE"] != "") ? $_POST["LSTOPERATION_TYPE"] : "");	$cEntidad->setBusqueda(constant("STR_OPERATION_TYPE"), (isset($_POST["LSTOPERATION_TYPE"]) && $_POST["LSTOPERATION_TYPE"] != "" ) ? $_POST["LSTOPERATION_TYPE"] : "");
		$cEntidad->setURL_NOTIFY((isset($_POST["LSTURL_NOTIFY"]) && $_POST["LSTURL_NOTIFY"] != "") ? $_POST["LSTURL_NOTIFY"] : "");	$cEntidad->setBusqueda(constant("STR_URL_NOTIFY"), (isset($_POST["LSTURL_NOTIFY"]) && $_POST["LSTURL_NOTIFY"] != "" ) ? $_POST["LSTURL_NOTIFY"] : "");
		$cEntidad->setURL_OK((isset($_POST["LSTURL_OK"]) && $_POST["LSTURL_OK"] != "") ? $_POST["LSTURL_OK"] : "");	$cEntidad->setBusqueda(constant("STR_URL_OK"), (isset($_POST["LSTURL_OK"]) && $_POST["LSTURL_OK"] != "" ) ? $_POST["LSTURL_OK"] : "");
		$cEntidad->setURL_NOOK((isset($_POST["LSTURL_NOOK"]) && $_POST["LSTURL_NOOK"] != "") ? $_POST["LSTURL_NOOK"] : "");	$cEntidad->setBusqueda(constant("STR_URL_KO"), (isset($_POST["LSTURL_NOOK"]) && $_POST["LSTURL_NOOK"] != "" ) ? $_POST["LSTURL_NOOK"] : "");
		$cEntidad->setSERVICE_ACTION((isset($_POST["LSTSERVICE_ACTION"]) && $_POST["LSTSERVICE_ACTION"] != "") ? $_POST["LSTSERVICE_ACTION"] : "");	$cEntidad->setBusqueda(constant("STR_SERVICE_ACTION"), (isset($_POST["LSTSERVICE_ACTION"]) && $_POST["LSTSERVICE_ACTION"] != "" ) ? $_POST["LSTSERVICE_ACTION"] : "");
		$cEntidad->setBajaLog((isset($_POST["LSTBajaLog"]) && $_POST["LSTBajaLog"] != "") ? $_POST["LSTBajaLog"] : "");	$cEntidad->setBusqueda(constant("STR_BAJA_LOG"), (isset($_POST["LSTBajaLog"]) && $_POST["LSTBajaLog"] != "" ) ? $_POST["LSTBajaLog"] : "");
		$cEntidad->setBajaLogHast((isset($_POST["LSTBajaLogHast"]) && $_POST["LSTBajaLogHast"] != "") ? $_POST["LSTBajaLogHast"] : "");	$cEntidad->setBusqueda(constant("STR_BAJA_LOG") . " " . constant("STR_HASTA"), (isset($_POST["LSTBajaLogHast"]) && $_POST["LSTBajaLogHast"] != "" ) ? $_POST["LSTBajaLogHast"] : "");
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