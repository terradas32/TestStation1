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
	require_once(constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidadesDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas_perfiles_funcionalidades/Empresas_perfiles_funcionalidades.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
    $cUtilidades	= new Utilidades();
    
	$cEntidadDB	= new Empresas_perfiles_funcionalidadesDB($conn);  // Entidad DB
	$cEntidad	= new Empresas_perfiles_funcionalidades();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
    $sCol1='';
    $sCol2='';
	
	$comboEMP_PERFILES	= new Combo($conn,"fIdPerfil","idPerfil","descripcion","Descripcion","emp_perfiles","",constant("SLC_OPCION"),"");
	$comboFUNCIONALIDADES	= new	Combo($conn,"fIdFuncionalidad","idFuncionalidad", "nombre","Descripcion","wi_funcionalidades","",constant("SLC_OPCION"),"","","orden");
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
			$cEntidad = readEntidad($cEntidad);
			$newId	= $cEntidadDB->insertar($cEntidad);
			if (!empty($newId)){
			?><script language="javascript" type="text/javascript">alert("<?php echo constant("CONF_ALTA") . $newId;?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				if ($_POST['ORIGEN'] == constant("MNT_LISTAR"))
				{
					if (!empty($_POST['empresas_perfiles_funcionalidades_next_page']) && $_POST['empresas_perfiles_funcionalidades_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'empresas_perfiles_funcionalidades');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesl.php');
				}else{
					$cEntidad	= new Empresas_perfiles_funcionalidades();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['empresas_perfiles_funcionalidades_next_page']) && $_POST['empresas_perfiles_funcionalidades_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'empresas_perfiles_funcionalidades');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			if (!$cEntidadDB->borrar(readEntidad($cEntidad)))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
			if (!empty($_POST['empresas_perfiles_funcionalidades_next_page']) && $_POST['empresas_perfiles_funcionalidades_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'empresas_perfiles_funcionalidades');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesl.php');
			break;
		case constant("MNT_NUEVO"):
		$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidades.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['empresas_perfiles_funcionalidades_next_page']) && $_POST['empresas_perfiles_funcionalidades_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'empresas_perfiles_funcionalidades');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidadesl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Empresas_perfiles_funcionalidades/mntempresas_perfiles_funcionalidades.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
        global $_cEntidadUsuarioTK;
        global $cUtilidades;
		$cEntidad->setIdPerfil((isset($_POST["fIdPerfil"])) ? $_POST["fIdPerfil"] : "");
		$cEntidad->setIdFuncionalidad((isset($_POST["fIdFuncionalidad"])) ? $_POST["fIdFuncionalidad"] : "");
		$cEntidad->setModificar((isset($_POST["fModificar"])) ? $_POST["fModificar"] : "");
		$cEntidad->setBorrar((isset($_POST["fBorrar"])) ? $_POST["fBorrar"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdUsuario());
		$cEntidad->setPerfilFuncionalidades((isset($_POST["fPerfilFuncionalidades"])) ? $_POST["fPerfilFuncionalidades"] : "");
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
        global $conn;
        global $cUtilidades;
		global $comboEMP_PERFILES;
		$cEntidad->setIdPerfil((isset($_POST["LSTIdPerfil"])) ? $_POST["LSTIdPerfil"] : "");	$cEntidad->setBusqueda(constant("STR_PERFIL"), (isset($_POST["LSTIdPerfil"])) ? $comboEMP_PERFILES->getDescripcionCombo($_POST["LSTIdPerfil"]) : "");
		global $comboFUNCIONALIDADES;
		$cEntidad->setIdFuncionalidad((isset($_POST["LSTIdFuncionalidad"])) ? $_POST["LSTIdFuncionalidad"] : "");	$cEntidad->setBusqueda(constant("STR_FUNCIONALIDAD"), (isset($_POST["LSTIdFuncionalidad"])) ? $comboFUNCIONALIDADES->getDescripcionCombo($_POST["LSTIdFuncionalidad"]) : "");
		$cEntidad->setModificar((isset($_POST["LSTModificar"])) ? $_POST["LSTModificar"] : "");	$cEntidad->setBusqueda(constant("STR_MODIFICAR"), (isset($_POST["LSTModificar"])) ? $_POST["LSTModificar"] : "");
		$cEntidad->setBorrar((isset($_POST["LSTBorrar"])) ? $_POST["LSTBorrar"] : "");	$cEntidad->setBusqueda(constant("STR_BORRAR"), (isset($_POST["LSTBorrar"])) ? $_POST["LSTBorrar"] : "");
        $cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_MOD"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
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
		if ($bLlamada){
			$cEntidadDB->quitaImagen($cEntidad);
		}
	}

?>