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
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new PruebasDB($conn);  // Entidad DB
	$cEntidad	= new Pruebas();  // Entidad

	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';

	$comboWI_IDIOMAS	= new Combo($conn,"fCodIdiomaIso2","codIdiomaIso2","nombre","Descripcion","wi_idiomas","",constant("SLC_OPCION"),"activoFront=1","","fecMod");
	$comboTIPOS_PRUEBA	= new Combo($conn,"fIdTipoPrueba","idTipoPrueba","descripcion","Descripcion","tipos_prueba","",constant("SLC_OPCION"),"","","fecMod");
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
					if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'pruebas');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Pruebas/mntpruebasl.php');
				}else{
					$cEntidad	= new Pruebas();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Pruebas/mntpruebasa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Pruebas/mntpruebasa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB, false);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'pruebas');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Pruebas/mntpruebasl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Pruebas/mntpruebasa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB, true);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php 
			}
					if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'pruebas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Pruebas/mntpruebasl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Pruebas/mntpruebasa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Pruebas/mntpruebasa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Pruebas/mntpruebas.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['pruebas_next_page']) && $_POST['pruebas_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'pruebas');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Pruebas/mntpruebasl.php');
			break;
		default:
			$cEntidad->setCodIdiomaIso2($sLang);
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Pruebas/mntpruebas.php');
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
		$cEntidad->setCodigo((isset($_POST["fCodigo"])) ? $_POST["fCodigo"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["fDescripcion"])) ? $_POST["fDescripcion"] : "");
		$cEntidad->setIdTipoPrueba((isset($_POST["fIdTipoPrueba"])) ? $_POST["fIdTipoPrueba"] : "");
		$cEntidad->setObservaciones((isset($_POST["fObservaciones"])) ? $_POST["fObservaciones"] : "");
		$cEntidad->setDuracion((isset($_POST["fDuracion"])) ? $_POST["fDuracion"] : "");
		$cEntidad->setLogoPrueba((isset($_POST["fLogoPrueba"])) ? $_POST["fLogoPrueba"] : "");
		$cEntidad->setCapturaPantalla((isset($_POST["fCapturaPantalla"])) ? $_POST["fCapturaPantalla"] : "");
		$cEntidad->setCabecera((isset($_POST["fCabecera"])) ? $_POST["fCabecera"] : "");
		$cEntidad->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
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
		global $comboWI_IDIOMAS;
		$cEntidad->setCodIdiomaIso2((isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "") ? $_POST["LSTCodIdiomaIso2"] : "");	$cEntidad->setBusqueda(constant("STR_IDIOMA"), (isset($_POST["LSTCodIdiomaIso2"]) && $_POST["LSTCodIdiomaIso2"] != "" ) ? $comboWI_IDIOMAS->getDescripcionCombo($_POST["LSTCodIdiomaIso2"]) : "");
		$cEntidad->setIdPrueba((isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "") ? $_POST["LSTIdPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PRUEBA"), (isset($_POST["LSTIdPrueba"]) && $_POST["LSTIdPrueba"] != "" ) ? $_POST["LSTIdPrueba"] : "");
		$cEntidad->setIdPruebaHast((isset($_POST["LSTIdPruebaHast"]) && $_POST["LSTIdPruebaHast"] != "") ? $_POST["LSTIdPruebaHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_PRUEBA") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdPruebaHast"]) && $_POST["LSTIdPruebaHast"] != "" ) ? $_POST["LSTIdPruebaHast"] : "");
		$cEntidad->setCodigo((isset($_POST["LSTCodigo"]) && $_POST["LSTCodigo"] != "") ? $_POST["LSTCodigo"] : "");	$cEntidad->setBusqueda(constant("STR_CODIGO"), (isset($_POST["LSTCodigo"]) && $_POST["LSTCodigo"] != "" ) ? $_POST["LSTCodigo"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setDescripcion((isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "") ? $_POST["LSTDescripcion"] : "");	$cEntidad->setBusqueda(constant("STR_DESCRIPCION"), (isset($_POST["LSTDescripcion"]) && $_POST["LSTDescripcion"] != "" ) ? $_POST["LSTDescripcion"] : "");
		global $comboTIPOS_PRUEBA;
		$cEntidad->setIdTipoPrueba((isset($_POST["LSTIdTipoPrueba"]) && $_POST["LSTIdTipoPrueba"] != "") ? $_POST["LSTIdTipoPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_TIPO_PRUEBA"), (isset($_POST["LSTIdTipoPrueba"]) && $_POST["LSTIdTipoPrueba"] != "" ) ? $comboTIPOS_PRUEBA->getDescripcionCombo($_POST["LSTIdTipoPrueba"]) : "");
		$cEntidad->setObservaciones((isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "") ? $_POST["LSTObservaciones"] : "");	$cEntidad->setBusqueda(constant("STR_OBSERVACIONES"), (isset($_POST["LSTObservaciones"]) && $_POST["LSTObservaciones"] != "" ) ? $_POST["LSTObservaciones"] : "");
		$cEntidad->setDuracion((isset($_POST["LSTDuracion"]) && $_POST["LSTDuracion"] != "") ? $_POST["LSTDuracion"] : "");	$cEntidad->setBusqueda(constant("STR_DURACION"), (isset($_POST["LSTDuracion"]) && $_POST["LSTDuracion"] != "" ) ? $_POST["LSTDuracion"] : "");
		$cEntidad->setLogoPrueba((isset($_POST["LSTLogoPrueba"]) && $_POST["LSTLogoPrueba"] != "") ? $_POST["LSTLogoPrueba"] : "");	$cEntidad->setBusqueda(constant("STR_LOGO_PRUEBA"), (isset($_POST["LSTLogoPrueba"]) && $_POST["LSTLogoPrueba"] != "" ) ? $_POST["LSTLogoPrueba"] : "");
		$cEntidad->setCapturaPantalla((isset($_POST["LSTCapturaPantalla"]) && $_POST["LSTCapturaPantalla"] != "") ? $_POST["LSTCapturaPantalla"] : "");	$cEntidad->setBusqueda(constant("STR_CAPTURA_PANTALLA"), (isset($_POST["LSTCapturaPantalla"]) && $_POST["LSTCapturaPantalla"] != "" ) ? $_POST["LSTCapturaPantalla"] : "");
		$cEntidad->setCabecera((isset($_POST["LSTCabecera"]) && $_POST["LSTCabecera"] != "") ? $_POST["LSTCabecera"] : "");	$cEntidad->setBusqueda(constant("STR_CABECERA"), (isset($_POST["LSTCabecera"]) && $_POST["LSTCabecera"] != "" ) ? $_POST["LSTCabecera"] : "");
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
		if (!empty($_POST['cfLogoPrueba']) && strtoupper($_POST['cfLogoPrueba']) == 'ON'){
			$cEntidad->setLogoPrueba($_POST['cfLogoPrueba']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfCapturaPantalla']) && strtoupper($_POST['cfCapturaPantalla']) == 'ON'){
			$cEntidad->setCapturaPantalla($_POST['cfCapturaPantalla']);
			$bLlamada=true;
		}
		if (!empty($_POST['cfCabecera']) && strtoupper($_POST['cfCabecera']) == 'ON'){
			$cEntidad->setCabecera($_POST['cfCabecera']);
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
			$_POST['cfLogoPrueba'] = 'on';
			$_POST['cfCapturaPantalla'] = 'on';
			$_POST['cfCabecera'] = 'on';
	}

?>
