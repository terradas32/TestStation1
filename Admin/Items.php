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
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new ItemsDB($conn);  // Entidad DB
	$cEntidad	= new Items();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboPRUEBAS	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),"","","fecMod");
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
					if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'items');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Items/mntitemsl.php');
				}else{
					$cEntidad	= new Items();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Items/mntitemsa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Items/mntitemsa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB, false);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'items');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Items/mntitemsl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Items/mntitemsa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB, true);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
					if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'items');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Items/mntitemsl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Items/mntitemsa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Items/mntitemsa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Items/mntitems.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['items_next_page']) && $_POST['items_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'items');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Items/mntitemsl.php');
			break;
		default:
			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Items/mntitems.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setCodIdiomaIso2((isset($_POST["fCodIdiomaIso2"])) ? $_POST["fCodIdiomaIso2"] : "");
		$cEntidad->setIdPrueba((isset($_POST["fIdPrueba"])) ? $_POST["fIdPrueba"] : "");
		$cEntidad->setIdItem((isset($_POST["fIdItem"])) ? $_POST["fIdItem"] : "");
		$cEntidad->setEnunciado((isset($_POST["fEnunciado"])) ? $_POST["fEnunciado"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setPath1((isset($_POST["fPath1"])) ? $_POST["fPath1"] : "");
		$cEntidad->setPath2((isset($_POST["fPath2"])) ? $_POST["fPath2"] : "");
		$cEntidad->setPath3((isset($_POST["fPath3"])) ? $_POST["fPath3"] : "");
		$cEntidad->setPath4((isset($_POST["fPath4"])) ? $_POST["fPath4"] : "");
		$cEntidad->setCorrecto((isset($_POST["fCorrecto"])) ? $_POST["fCorrecto"] : "");
		$cEntidad->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
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
		global $comboWI_IDIOMAS;
		$cEntidad->setCodIdiomaIso2((isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "") ? $_POST["LSTCodIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaIso2"]) : "");
		global $comboPRUEBAS;
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $comboPRUEBAS->getDescripcionCombo($_POST["LSTIdPrueba"]) : "");
		$cEntidad->setIdItem((isset($_POST["LSTIdItem"]) && $_POST["LSTIdItem"] != "") ? $_POST["LSTIdItem"] : "");	$cEntidad->setBusqueda(constant("STR_ID_ITEM"), (isset($_POST["LSTIdItem"]) && $_POST["LSTIdItem"] != "" ) ? $_POST["LSTIdItem"] : "");
		$cEntidad->setIdItemHast((isset($_POST["LSTIdItemHast"]) && $_POST["LSTIdItemHast"] != "") ? $_POST["LSTIdItemHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_ITEM") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdItemHast"]) && $_POST["LSTIdItemHast"] != "" ) ? $_POST["LSTIdItemHast"] : "");
		$cEntidad->setEnunciado((isset($_POST["LSTEnunciado"]) && $_POST["LSTEnunciado"] != "") ? $_POST["LSTEnunciado"] : "");	$cEntidad->setBusqueda(constant("STR_ENUNCIADO"), (isset($_POST["LSTEnunciado"]) && $_POST["LSTEnunciado"] != "" ) ? $_POST["LSTEnunciado"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		$cEntidad->setPath1((isset($_POST["LSTPath1"]) && $_POST["LSTPath1"] != "") ? $_POST["LSTPath1"] : "");	$cEntidad->setBusqueda(constant("STR_PATH1"), (isset($_POST["LSTPath1"]) && $_POST["LSTPath1"] != "" ) ? $_POST["LSTPath1"] : "");
		$cEntidad->setPath2((isset($_POST["LSTPath2"]) && $_POST["LSTPath2"] != "") ? $_POST["LSTPath2"] : "");	$cEntidad->setBusqueda(constant("STR_PATH2"), (isset($_POST["LSTPath2"]) && $_POST["LSTPath2"] != "" ) ? $_POST["LSTPath2"] : "");
		$cEntidad->setPath3((isset($_POST["LSTPath3"]) && $_POST["LSTPath3"] != "") ? $_POST["LSTPath3"] : "");	$cEntidad->setBusqueda(constant("STR_PATH3"), (isset($_POST["LSTPath3"]) && $_POST["LSTPath3"] != "" ) ? $_POST["LSTPath3"] : "");
		$cEntidad->setPath4((isset($_POST["LSTPath4"]) && $_POST["LSTPath4"] != "") ? $_POST["LSTPath4"] : "");	$cEntidad->setBusqueda(constant("STR_PATH4"), (isset($_POST["LSTPath4"]) && $_POST["LSTPath4"] != "" ) ? $_POST["LSTPath4"] : "");
		$cEntidad->setCorrecto((isset($_POST["LSTCorrecto"]) && $_POST["LSTCorrecto"] != "") ? $_POST["LSTCorrecto"] : "");	$cEntidad->setBusqueda(constant("STR_CORRECTO"), (isset($_POST["LSTCorrecto"]) && $_POST["LSTCorrecto"] != "" ) ? $_POST["LSTCorrecto"] : "");
		$cEntidad->setOrden((isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "") ? $_POST["LSTOrden"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "" ) ? $_POST["LSTOrden"] : "");
		$cEntidad->setOrdenHast((isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "") ? $_POST["LSTOrdenHast"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN") . " " . constant("STR_HASTA"), (isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "" ) ? $_POST["LSTOrdenHast"] : "");
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
		if ($bBorrar){
			setBorradoRegistro();
		}
		if (!empty($_POST['cfPath1']) && strtoupper($_POST['cfPath1']) == 'ON'){
			$cEntidad->setPath1($_POST['cfPath1']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath2']) && strtoupper($_POST['cfPath2']) == 'ON'){
			$cEntidad->setPath2($_POST['cfPath2']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath3']) && strtoupper($_POST['cfPath3']) == 'ON'){
			$cEntidad->setPath3($_POST['cfPath3']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfPath4']) && strtoupper($_POST['cfPath4']) == 'ON'){
			$cEntidad->setPath4($_POST['cfPath4']);
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
			$_POST['cfPath1'] = 'on';
			$_POST['cfPath2'] = 'on';
			$_POST['cfPath3'] = 'on';
			$_POST['cfPath4'] = 'on';
	}

?>