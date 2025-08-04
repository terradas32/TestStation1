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
	require_once(constant("DIR_WS_COM") . "Rangos_ip/Rangos_ipDB.php");
	require_once(constant("DIR_WS_COM") . "Rangos_ip/Rangos_ip.php");
	require_once(constant("DIR_WS_COM") . "Signos/SignosDB.php");
	require_once(constant("DIR_WS_COM") . "Signos/Signos.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cEntidadDB	= new Rangos_ipDB($conn);  // Entidad DB
	$cEntidad	= new Rangos_ip();  // Entidad
	$cSignosDB = new SignosDB($conn);
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	
	$comboSIGNOS = new Combo($conn,"fIdSigno","idSigno","signo","Descripcion","signos","",constant("SLC_OPCION"),"","","fecMod");
	$comboRANGOS_IR	= new Combo($conn,"fIdRangoIr","idRangoIr",$conn->Concat("rangoSup" , '" ---- "' , "rangoInf"),"Descripcion","rangos_ir","",constant("SLC_OPCION"),"","","fecMod");
	$comboWI_USUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","nombre","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"","","fecMod");
	
	//echo('modo:' . $_POST['MODO']);
	
	$idsS = 0;
	$idsI = 0;
	
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
					if (!empty($_POST['rangos_ip_next_page']) && $_POST['rangos_ip_next_page'] > 1 ){
						$bInit=false;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}else{
						$bInit=true;
						$sql = $cEntidadDB->readLista(readLista($cEntidad));
					}
					$pager = new ADODB_Pager($conn,$sql,'rangos_ip');
					if ($bInit)	$pager->curr_page=1;
					$pager->showPageLinks = true;
					$LnPag = $cEntidad->getLineasPagina();
					if (!empty($LnPag) && is_numeric ($LnPag)){
						$pager->setRows($LnPag);
					}else{
						$pager->setRows(constant("CNF_LINEAS_PAGINA"));
					}
					$lista=$pager->getRS();
					include('Template/Rangos_ip/mntrangos_ipl.php');
				}else{
					$cEntidad	= new Rangos_ip();  // inicializamos la Entidad
					$_POST['MODO']    = constant("MNT_ALTA");
					include('Template/Rangos_ip/mntrangos_ipa.php');
				}
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_ALTA");
				include('Template/Rangos_ip/mntrangos_ipa.php');
			}
			break;
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			if ($cEntidadDB->modificar($cEntidad))
			{
				if (!empty($_POST['rangos_ip_next_page']) && $_POST['rangos_ip_next_page'] > 1 ){
					$bInit=false;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista(readLista($cEntidad));
				}
				$pager = new ADODB_Pager($conn,$sql,'rangos_ip');
				if ($bInit)	$pager->curr_page=1;
				$pager->showPageLinks = true;
				$LnPag = $cEntidad->getLineasPagina();
				if (!empty($LnPag) && is_numeric ($LnPag)){
					$pager->setRows($LnPag);
				}else{
					$pager->setRows(constant("CNF_LINEAS_PAGINA"));
				}
				$lista=$pager->getRS();
				include('Template/Rangos_ip/mntrangos_ipl.php');
			}else{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Rangos_ip/mntrangos_ipa.php');
			}
			break;
		case constant("MNT_BORRAR"):
			$cEntidad = readEntidad($cEntidad);
			if (!$cEntidadDB->borrar($cEntidad))
			{
				?><script language="javascript" type="text/javascript">alert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>");</script><?php 
			}
					if (!empty($_POST['rangos_ip_next_page']) && $_POST['rangos_ip_next_page'] > 1 ){
				$bInit=false;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}else{
				$bInit=true;
				$sql = $cEntidadDB->readLista(readLista($cEntidad));
			}
			$pager = new ADODB_Pager($conn,$sql,'rangos_ip');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Rangos_ip/mntrangos_ipl.php');
			break;
		case constant("MNT_NUEVO"):
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST["LSTOrderBy"] = "fecMod";
			$_POST["LSTOrder"] = "DESC";
			$_POST['MODO']    = constant("MNT_ALTA");
			include('Template/Rangos_ip/mntrangos_ipa.php');
			break;
		case constant("MNT_CONSULTAR"):
			$cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$cSigno = new Signos();
			
			$arS = explode(" " , $cEntidad->getRangoSup());
			$cEntidad->setRangoSup($arS[1]);
			
			$cSigno->setSigno($arS[0]);
			$cSigno = $cSignosDB->readEntidadSigno($cSigno);
			$idsS = $cSigno->getIdSigno();
			
			$arI = explode(" " , $cEntidad->getRangoInf());
			$cEntidad->setRangoInf($arI[1]);
			
			$cSigno->setSigno($arI[0]);
			$cSigno = $cSignosDB->readEntidadSigno($cSigno);
			$idsI = $cSigno->getIdSigno();
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Rangos_ip/mntrangos_ipa.php');
			break;
		case constant("MNT_BUSCAR"):
			$_POST['MODO']    = constant("MNT_LISTAR");
			$cEntidad = readLista($cEntidad);
			include('Template/Rangos_ip/mntrangos_ip.php');
			break;
		case constant("MNT_LISTAR"):
			$cEntidad = readLista($cEntidad);
			if (isset($_POST["fReordenar"]) &&  !empty($_POST["fReordenar"])){
				$bInit=true;
				$sql = $cEntidadDB->readLista($cEntidad);
			}else{
				if (!empty($_POST['rangos_ip_next_page']) && $_POST['rangos_ip_next_page'] > 1){
					$bInit=false;
					$sql = $cEntidadDB->readLista($cEntidad);
				}else{
					$bInit=true;
					$sql = $cEntidadDB->readLista($cEntidad);
				}
			}
			$pager = new ADODB_Pager($conn,$sql,'rangos_ip');
			if ($bInit)	$pager->curr_page=1;
			$pager->showPageLinks = true;
			$LnPag = $cEntidad->getLineasPagina();
			if (!empty($LnPag) && is_numeric ($LnPag)){
				$pager->setRows($LnPag);
			}else{
				$pager->setRows(constant("CNF_LINEAS_PAGINA"));
			}
			$lista=$pager->getRS();
			include('Template/Rangos_ip/mntrangos_ipl.php');
			break;
		default:
			$cEntidad->setOrderBy("fecMod");
			$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), constant("STR_FEC_MOD"));
			$cEntidad->setOrder("DESC");
			$cEntidad->setBusqueda(constant("STR_ORDEN"), "DESC");
			$_POST['MODO']    = constant("MNT_LISTAR");
			include('Template/Rangos_ip/mntrangos_ip.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		global $conn;
		
		$cSignos = new Signos();
		$cSignosDB = new SignosDB($conn);
		
		$sSignoSup ="";
		$sSignoInf ="";
		
		if(isset($_REQUEST['fIdSignoSup'])){
			$cSignos->setIdSigno($_REQUEST['fIdSignoSup']);
			$cSignos = $cSignosDB->readEntidad($cSignos);	
			$sSignoSup = $cSignos->getSigno() . " "; 
		}
		if(isset($_REQUEST['fIdSignoInf'])){
			$cSignos->setIdSigno($_REQUEST['fIdSignoInf']);
			$cSignos = $cSignosDB->readEntidad($cSignos);
			$sSignoInf = $cSignos->getSigno() . " ";
		}
		
		$cEntidad->setIdRangoIp((isset($_POST["fIdRangoIp"])) ? $_POST["fIdRangoIp"] : "");
		$cEntidad->setIdRangoIr((isset($_POST["fIdRangoIr"])) ? $_POST["fIdRangoIr"] : "");
		$cEntidad->setRangoSup((isset($_POST["fRangoSup"])) ? $sSignoSup. $_POST["fRangoSup"] : "");
		$cEntidad->setRangoInf((isset($_POST["fRangoInf"])) ? $sSignoInf . $_POST["fRangoInf"] : "");
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
		$cEntidad->setIdRangoIp((isset($_POST["LSTIdRangoIp"]) && $_POST["LSTIdRangoIp"] != "") ? $_POST["LSTIdRangoIp"] : "");	$cEntidad->setBusqueda(constant("STR_ID_RANGO_IP"), (isset($_POST["LSTIdRangoIp"]) && $_POST["LSTIdRangoIp"] != "" ) ? $_POST["LSTIdRangoIp"] : "");
		$cEntidad->setIdRangoIpHast((isset($_POST["LSTIdRangoIpHast"]) && $_POST["LSTIdRangoIpHast"] != "") ? $_POST["LSTIdRangoIpHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_RANGO_IP") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdRangoIpHast"]) && $_POST["LSTIdRangoIpHast"] != "" ) ? $_POST["LSTIdRangoIpHast"] : "");
		global $comboRANGOS_IR;
		$cEntidad->setIdRangoIr((isset($_POST["LSTIdRangoIr"]) && $_POST["LSTIdRangoIr"] != "") ? $_POST["LSTIdRangoIr"] : "");	$cEntidad->setBusqueda(constant("STR_RANGO_IR"), (isset($_POST["LSTIdRangoIr"]) && $_POST["LSTIdRangoIr"] != "" ) ? $comboRANGOS_IR->getDescripcionCombo($_POST["LSTIdRangoIr"]) : "");
		$cEntidad->setRangoSup((isset($_POST["LSTRangoSup"]) && $_POST["LSTRangoSup"] != "") ? $_POST["LSTRangoSup"] : "");	$cEntidad->setBusqueda(constant("STR_RANGO_SUPERIOR"), (isset($_POST["LSTRangoSup"]) && $_POST["LSTRangoSup"] != "" ) ? $_POST["LSTRangoSup"] : "");
		$cEntidad->setRangoInf((isset($_POST["LSTRangoInf"]) && $_POST["LSTRangoInf"] != "") ? $_POST["LSTRangoInf"] : "");	$cEntidad->setBusqueda(constant("STR_RANGO_INFERIOR"), (isset($_POST["LSTRangoInf"]) && $_POST["LSTRangoInf"] != "" ) ? $_POST["LSTRangoInf"] : "");
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