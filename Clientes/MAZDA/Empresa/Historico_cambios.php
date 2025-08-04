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
	require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambiosDB.php");
	require_once(constant("DIR_WS_COM") . "Historico_cambios/Historico_cambios.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new Historico_cambiosDB($conn);  // Entidad DB
	$cEntidad	= new Historico_cambios();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
	$comboWI_USUARIOS	= new Combo($conn,"fIdUsuario","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	$comboWI_USUARIOS_TIPOS	= new Combo($conn,"fIdUsuarioTipo","idUsuarioTipo","descripcion","Descripcion","wi_usuarios_tipos","",constant("SLC_OPCION"),"","","fecMod");
	
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
					if (!empty($_POST['historico_cambios_next_page']) && $_POST['historico_cambios_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'historico_cambios');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Historico_cambios/mnthistorico_cambiosl.php');
				}else{
					$cEntidad	= new Historico_cambios();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Historico_cambios/mnthistorico_cambiosa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Historico_cambios/mnthistorico_cambiosa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['historico_cambios_next_page']) && $_POST['historico_cambios_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'historico_cambios');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Historico_cambios/mnthistorico_cambiosl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Historico_cambios/mnthistorico_cambiosa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
					if (!empty($_POST['historico_cambios_next_page']) && $_POST['historico_cambios_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'historico_cambios');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Historico_cambios/mnthistorico_cambiosl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Historico_cambios/mnthistorico_cambiosa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Historico_cambios/mnthistorico_cambiosa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Historico_cambios/mnthistorico_cambios.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['historico_cambios_next_page']) && $_POST['historico_cambios_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'historico_cambios');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Historico_cambios/mnthistorico_cambiosl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Historico_cambios/mnthistorico_cambios.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setId((isset($_POST["fId"])) ? $_POST["fId"] : "");
		$cEntidad->setFecCambio((isset($_POST["fFecCambio"])) ? $_POST["fFecCambio"] : "");
		$cEntidad->setFuncionalidad((isset($_POST["fFuncionalidad"])) ? $_POST["fFuncionalidad"] : "");
		$cEntidad->setModo((isset($_POST["fModo"])) ? $_POST["fModo"] : "");
		$cEntidad->setQuery((isset($_POST["fQuery"])) ? $_POST["fQuery"] : "");
		$cEntidad->setIp((isset($_POST["fIp"])) ? $_POST["fIp"] : "");
		$cEntidad->setIdUsuario((isset($_POST["fIdUsuario"])) ? $_POST["fIdUsuario"] : "");
		$cEntidad->setIdUsuarioTipo((isset($_POST["fIdUsuarioTipo"])) ? $_POST["fIdUsuarioTipo"] : "");
		$cEntidad->setLogin((isset($_POST["fLogin"])) ? $_POST["fLogin"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cEntidad->setEmail((isset($_POST["fEmail"])) ? $_POST["fEmail"] : "");
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
		$cEntidad->setId((isset($_POST["LSTId"]) && $_POST["LSTId"] != "") ? $_POST["LSTId"] : "");	$cEntidad->setBusqueda(constant("STR_ID"), (isset($_POST["LSTId"]) && $_POST["LSTId"] != "" ) ? $_POST["LSTId"] : "");
		$cEntidad->setIdHast((isset($_POST["LSTIdHast"]) && $_POST["LSTIdHast"] != "") ? $_POST["LSTIdHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdHast"]) && $_POST["LSTIdHast"] != "" ) ? $_POST["LSTIdHast"] : "");
		$cEntidad->setFecCambio((isset($_POST["LSTFecCambio"]) && $_POST["LSTFecCambio"] != "") ? $_POST["LSTFecCambio"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_CAMBIO"), (isset($_POST["LSTFecCambio"]) && $_POST["LSTFecCambio"] != "" ) ? $conn->UserDate($_POST["LSTFecCambio"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecCambioHast((isset($_POST["LSTFecCambioHast"]) && $_POST["LSTFecCambioHast"] != "") ? $_POST["LSTFecCambioHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_CAMBIO") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecCambioHast"]) && $_POST["LSTFecCambioHast"] != "" ) ? $conn->UserDate($_POST["LSTFecCambioHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFuncionalidad((isset($_POST["LSTFuncionalidad"]) && $_POST["LSTFuncionalidad"] != "") ? $_POST["LSTFuncionalidad"] : "");	$cEntidad->setBusqueda(constant("STR_FUNCIONALIDAD"), (isset($_POST["LSTFuncionalidad"]) && $_POST["LSTFuncionalidad"] != "" ) ? $_POST["LSTFuncionalidad"] : "");
		$cEntidad->setModo((isset($_POST["LSTModo"]) && $_POST["LSTModo"] != "") ? $_POST["LSTModo"] : "");	$cEntidad->setBusqueda(constant("STR_MODO"), (isset($_POST["LSTModo"]) && $_POST["LSTModo"] != "" ) ? $_POST["LSTModo"] : "");
		$cEntidad->setQuery((isset($_POST["LSTQuery"]) && $_POST["LSTQuery"] != "") ? $_POST["LSTQuery"] : "");	$cEntidad->setBusqueda(constant("STR_QUERY"), (isset($_POST["LSTQuery"]) && $_POST["LSTQuery"] != "" ) ? $_POST["LSTQuery"] : "");
		$cEntidad->setIp((isset($_POST["LSTIp"]) && $_POST["LSTIp"] != "") ? $_POST["LSTIp"] : "");	$cEntidad->setBusqueda(constant("STR_IP"), (isset($_POST["LSTIp"]) && $_POST["LSTIp"] != "" ) ? $_POST["LSTIp"] : "");
		global $comboWI_USUARIOS;
		$cEntidad->setIdUsuario((isset($_POST["LSTIdUsuario"]) && $_POST["LSTIdUsuario"] != "") ? $_POST["LSTIdUsuario"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO"), (isset($_POST["LSTIdUsuario"]) && $_POST["LSTIdUsuario"] != "" ) ? $comboWI_USUARIOS->getDescripcionCombo($_POST["LSTIdUsuario"]) : "");
		global $comboWI_USUARIOS_TIPOS;
		$cEntidad->setIdUsuarioTipo((isset($_POST["LSTIdUsuarioTipo"]) && $_POST["LSTIdUsuarioTipo"] != "") ? $_POST["LSTIdUsuarioTipo"] : "");	$cEntidad->setBusqueda(constant("STR_ID_USUARIO_TIPO"), (isset($_POST["LSTIdUsuarioTipo"]) && $_POST["LSTIdUsuarioTipo"] != "" ) ? $comboWI_USUARIOS_TIPOS->getDescripcionCombo($_POST["LSTIdUsuarioTipo"]) : "");
		$cEntidad->setLogin((isset($_POST["LSTLogin"]) && $_POST["LSTLogin"] != "") ? $_POST["LSTLogin"] : "");	$cEntidad->setBusqueda(constant("STR_LOGIN"), (isset($_POST["LSTLogin"]) && $_POST["LSTLogin"] != "" ) ? $_POST["LSTLogin"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "") ? $_POST["LSTApellido1"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO1"), (isset($_POST["LSTApellido1"]) && $_POST["LSTApellido1"] != "" ) ? $_POST["LSTApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "") ? $_POST["LSTApellido2"] : "");	$cEntidad->setBusqueda(constant("STR_APELLIDO2"), (isset($_POST["LSTApellido2"]) && $_POST["LSTApellido2"] != "" ) ? $_POST["LSTApellido2"] : "");
		$cEntidad->setEmail((isset($_POST["LSTEmail"]) && $_POST["LSTEmail"] != "") ? $_POST["LSTEmail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (isset($_POST["LSTEmail"]) && $_POST["LSTEmail"] != "" ) ? $_POST["LSTEmail"] : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_ALTA") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FECHA_DE_MODIFICACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
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