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
	require_once(constant("DIR_WS_COM") . "Peticiones_dongles/Peticiones_donglesDB.php");
	require_once(constant("DIR_WS_COM") . "Peticiones_dongles/Peticiones_dongles.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
	$cEntidadDB	= new Peticiones_donglesDB($conn);  // Entidad DB
	$cEntidad	= new Peticiones_dongles();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$sHijos = "";
	$_POST["fHijos"]="";
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
	//La empresa logada es la última en el array de empresas hijos
	$aHijos = explode(",", $sHijos);
	$cEmpresaLogada = new Empresas();
	$cEmpresaLogadaDB = new EmpresasDB($conn);
	$cEmpresaLogada->setIdEmpresa($aHijos[sizeof($aHijos)-1]);
	$cEmpresaLogada = $cEmpresaLogadaDB->readEntidad($cEmpresaLogada);
	//La que tiene como padre 0 que es el raiz, sólo tendría que ser PE
	//Por lo que si es cero, lo seteamos a PE
	if ($cEmpresaLogada->getIdPadre() == 0){
		$cEmpresaLogada->setIdPadre(constant("EMPRESA_PE"));
	}
	///////////
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $sHijos . ")","","orden");
	$comboEMPRESAS_TODAS	= new Combo($conn,"fIdEmpresaReceptora","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"","","orden");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	
//	echo('modo:' . $_POST['MODO']);
	
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
			//David
			if (!empty($newId)){
			?><script language="javascript" type="text/javascript">alert("Su solicitud ha sido tramitada correctamente.\nRecibirá un e-mail del administrador acerca del estado de la misma.\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					$cEntidad = readLista($cEntidad);
					if ($cEntidad->getIdEmpresa() == ""){
						$cEntidad->setIdEmpresa($sHijos);
					}
					if (!empty($_POST['peticiones_dongles_next_page']) && $_POST['peticiones_dongles_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readListaIN($cEntidad);
					}
					$pager = new ADODB_Pager($conn,$sql,'peticiones_dongles');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Peticiones_dongles/mntpeticiones_donglesl.php');
				}else{
					$cEntidad	= new Peticiones_dongles();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Peticiones_dongles/mntpeticiones_donglesa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Peticiones_dongles/mntpeticiones_donglesa.php');
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
				if (!empty($_POST['peticiones_dongles_next_page']) && $_POST['peticiones_dongles_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
				$pager = new ADODB_Pager($conn,$sql,'peticiones_dongles');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Peticiones_dongles/mntpeticiones_donglesl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Peticiones_dongles/mntpeticiones_donglesa.php');
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
			if (!empty($_POST['peticiones_dongles_next_page']) && $_POST['peticiones_dongles_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readListaIN($cEntidad);
			}
			$pager = new ADODB_Pager($conn,$sql,'peticiones_dongles');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Peticiones_dongles/mntpeticiones_donglesl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Peticiones_dongles/mntpeticiones_donglesa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Peticiones_dongles/mntpeticiones_donglesa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Peticiones_dongles/mntpeticiones_dongles.php');
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
				if (!empty($_POST['peticiones_dongles_next_page']) && $_POST['peticiones_dongles_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readListaIN($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'peticiones_dongles');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Peticiones_dongles/mntpeticiones_donglesl.php');
			break;
		default:
			
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Peticiones_dongles/mntpeticiones_dongles.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		$cEntidad->setIdPeticion((isset($_POST["fIdPeticion"])) ? $_POST["fIdPeticion"] : "");
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setIdEmpresaReceptora((isset($_POST["fIdEmpresaReceptora"])) ? $_POST["fIdEmpresaReceptora"] : "");
		$cEntidad->setNDongles((isset($_POST["fNDongles"])) ? $_POST["fNDongles"] : "");
		$cEntidad->setEstado((isset($_POST["fEstado"])) ? $_POST["fEstado"] : "");
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
		global $_cEntidadUsuarioTK;
		$cEntidad->setIdPeticion((isset($_POST["LSTIdPeticion"]) && $_POST["LSTIdPeticion"] != "") ? $_POST["LSTIdPeticion"] : "");	$cEntidad->setBusqueda(constant("STR_IDPETICION"), (isset($_POST["LSTIdPeticion"]) && $_POST["LSTIdPeticion"] != "" ) ? $_POST["LSTIdPeticion"] : "");
		$cEntidad->setIdPeticionHast((isset($_POST["LSTIdPeticionHast"]) && $_POST["LSTIdPeticionHast"] != "") ? $_POST["LSTIdPeticionHast"] : "");	$cEntidad->setBusqueda(constant("STR_IDPETICION") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdPeticionHast"]) && $_POST["LSTIdPeticionHast"] != "" ) ? $_POST["LSTIdPeticionHast"] : "");
		global $comboEMPRESAS;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $comboEMPRESAS->getDescripcionCombo($_POST["LSTIdEmpresa"]) : "");
		$cEntidad->setIdEmpresaReceptora((isset($_POST["LSTIdEmpresaReceptora"]) && $_POST["LSTIdEmpresaReceptora"] != "") ? $_POST["LSTIdEmpresaReceptora"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA_RECEPTORA"), (isset($_POST["LSTIdEmpresaReceptora"]) && $_POST["LSTIdEmpresaReceptora"] != "" ) ? $_POST["LSTIdEmpresaReceptora"] : "");
		$cEntidad->setIdEmpresaReceptoraHast((isset($_POST["LSTIdEmpresaReceptoraHast"]) && $_POST["LSTIdEmpresaReceptoraHast"] != "") ? $_POST["LSTIdEmpresaReceptoraHast"] : "");	$cEntidad->setBusqueda(constant("STR_EMPRESA_RECEPTORA") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdEmpresaReceptoraHast"]) && $_POST["LSTIdEmpresaReceptoraHast"] != "" ) ? $_POST["LSTIdEmpresaReceptoraHast"] : "");
		$cEntidad->setNDongles((isset($_POST["LSTNDongles"]) && $_POST["LSTNDongles"] != "") ? $_POST["LSTNDongles"] : "");	$cEntidad->setBusqueda(constant("STR_NUMERO_DE_DONGLES"), (isset($_POST["LSTNDongles"]) && $_POST["LSTNDongles"] != "" ) ? $_POST["LSTNDongles"] : "");
		$cEntidad->setNDonglesHast((isset($_POST["LSTNDonglesHast"]) && $_POST["LSTNDonglesHast"] != "") ? $_POST["LSTNDonglesHast"] : "");	$cEntidad->setBusqueda(constant("STR_NUMERO_DE_DONGLES") . " " . constant("STR_HASTA"), (isset($_POST["LSTNDonglesHast"]) && $_POST["LSTNDonglesHast"] != "" ) ? $_POST["LSTNDonglesHast"] : "");
		$cEntidad->setEstado((isset($_POST["LSTEstado"]) && $_POST["LSTEstado"] != "") ? $_POST["LSTEstado"] : "");	$cEntidad->setBusqueda(constant("STR_ESTADO"), (isset($_POST["LSTEstado"]) && $_POST["LSTEstado"] != "" ) ? $_POST["LSTEstado"] : "");
		$cEntidad->setEstadoHast((isset($_POST["LSTEstadoHast"]) && $_POST["LSTEstadoHast"] != "") ? $_POST["LSTEstadoHast"] : "");	$cEntidad->setBusqueda(constant("STR_ESTADO") . " " . constant("STR_HASTA"), (isset($_POST["LSTEstadoHast"]) && $_POST["LSTEstadoHast"] != "" ) ? $_POST["LSTEstadoHast"] : "");
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