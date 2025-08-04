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
	require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
	require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
	
    $cUtilidades	= new Utilidades();
    
	$cEntidadDB	= new UsuariosDB($conn);  // Entidad DB
	$cEntidad	= new Usuarios();  // Entidad
	
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
    $sCol1='';
    $sCol2='';
    
	$_POST["fIdUsuario"] = $_cEntidadUsuarioTK->getUsuario();
	$_POST["LSTIdUsuario"] = $_cEntidadUsuarioTK->getUsuario();
	
	$comboUSUARIOS	= new Combo($conn,"fUsuAlta","idUsuario","login","Descripcion","wi_usuarios","",constant("SLC_OPCION"),"");
	$comboUSUARIOS_TIPOS	= new Combo($conn,"fIdUsuarioTipo","idUsuarioTipo","descripcion","Descripcion","wi_usuarios_tipos","",constant("SLC_OPCION"),"");
	
	//echo('modo:' . $_POST['MODO']);
	
	if (!isset($_POST['MODO'])){
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = "04000 - " . constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	switch ($_POST['MODO'])
	{
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
            $bEncontradoUsuario = $cUtilidades->chkChar($cEntidad->getLogin());
            $bEncontradoPassword = $cUtilidades->chkChar($cEntidad->getPassword());
            if (!$bEncontradoPassword)
            {
                if (!$bEncontradoUsuario)
                {
        			if ($cEntidadDB->modificar($cEntidad))
        			{
        				$_POST['MODO']    = constant("MNT_MODIFICAR");
        				include('Template/Usuario/mntusuarioa.php');
        			}else{
        				?><script>jAlert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php 
        				$_POST['MODO']=constant("MNT_MODIFICAR");
        				include('Template/Usuario/mntusuarioa.php');
        			}
        		}else{
                    ?><script>jAlert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo constant("STR_USUARIO");?>:\n\t<?php echo constant("ERR_FORM_CARACTERES_LOGIN");?>\n\t\",<,>,&,|,', %, ?, =, /, \\");</script><?php 
    				$_POST['MODO']=constant("MNT_MODIFICAR");
    				include('Template/Usuario/mntusuarioa.php');
                }
    		}else{
                ?><script>jAlert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo constant("STR_PASSWORD");?>:\n\t<?php echo constant("ERR_FORM_CARACTERES_LOGIN");?>\n\t\",<,>,&,|,', %, ?, =, /, \\");</script><?php 
    				$_POST['MODO']=constant("MNT_MODIFICAR");
    				include('Template/Usuario/mntusuarioa.php');
            }
			break;
		default:
			$cEntidad = $cEntidadDB->readEntidad(readEntidad($cEntidad));
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Usuario/mntusuarioa.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
        global $_cEntidadUsuarioTK;
        global $cUtilidades;
		$cEntidad->setIdUsuario((isset($_POST["fIdUsuario"])) ? $_POST["fIdUsuario"] : "");
		$cEntidad->setLogin((isset($_POST["fLogin"])) ? $_POST["fLogin"] : "");
		$cEntidad->setPassword((isset($_POST["fPassword"])) ? $_POST["fPassword"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setApellido1((isset($_POST["fApellido1"])) ? $_POST["fApellido1"] : "");
		$cEntidad->setApellido2((isset($_POST["fApellido2"])) ? $_POST["fApellido2"] : "");
		$cEntidad->setEmail((isset($_POST["fEmail"])) ? $_POST["fEmail"] : "");
		$cEntidad->setLoginCorreo((isset($_POST["fLoginCorreo"])) ? $_POST["fLoginCorreo"] : "");
		$cEntidad->setPasswordCorreo((isset($_POST["fPasswordCorreo"])) ? $_POST["fPasswordCorreo"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setUsuAlta($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setUsuMod($_cEntidadUsuarioTK->getIdEmpresa());
		$cEntidad->setFecBaja((isset($_POST["fFecBaja"])) ? $_POST["fFecBaja"] : "");
		$cEntidad->setBajaLog((isset($_POST["fBajaLog"])) ? $_POST["fBajaLog"] : "");
		$cEntidad->setUltimoLogin((isset($_POST["fUltimoLogin"])) ? $_POST["fUltimoLogin"] : "");
		$cEntidad->setIdUsuarioTipo((isset($_POST["fIdUsuarioTipo"])) ? $_POST["fIdUsuarioTipo"] : "");
		return $cEntidad;
	}
	/*
	* "Lee" los parametros recibidos en el request del buscador y los asocia a una determinada Entidad.
	*/
	function readLista($cEntidad){
        global $conn;
        global $cUtilidades;
		$cEntidad->setIdUsuario((!empty($_POST["LSTIdUsuario"])) ? $_POST["LSTIdUsuario"] : "");	$cEntidad->setBusqueda(constant("STR_ID_USUARIO"), (!empty($_POST["LSTIdUsuario"])) ? $_POST["LSTIdUsuario"] : "");
		$cEntidad->setLogin((!empty($_POST["LSTLogin"])) ? $_POST["LSTLogin"] : "");	$cEntidad->setBusqueda(constant("STR_LOGIN"), (!empty($_POST["LSTLogin"])) ? $_POST["LSTLogin"] : "");
		$cEntidad->setPassword((!empty($_POST["LSTPassword"])) ? $_POST["LSTPassword"] : "");	$cEntidad->setBusqueda(constant("STR_PASSWORD"), (!empty($_POST["LSTPassword"])) ? $_POST["LSTPassword"] : "");
		$cEntidad->setNombre((!empty($_POST["LSTNombre"])) ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (!empty($_POST["LSTNombre"])) ? $_POST["LSTNombre"] : "");
		$cEntidad->setApellido1((!empty($_POST["LSTApellido1"])) ? $_POST["LSTApellido1"] : "");	$cEntidad->setBusqueda(constant("STR_PRIMER_APELLIDO"), (!empty($_POST["LSTApellido1"])) ? $_POST["LSTApellido1"] : "");
		$cEntidad->setApellido2((!empty($_POST["LSTApellido2"])) ? $_POST["LSTApellido2"] : "");	$cEntidad->setBusqueda(constant("STR_SEGUNDO APELLIDO"), (!empty($_POST["LSTApellido2"])) ? $_POST["LSTApellido2"] : "");
		$cEntidad->setEmail((!empty($_POST["LSTEmail"])) ? $_POST["LSTEmail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (!empty($_POST["LSTEmail"])) ? $_POST["LSTEmail"] : "");
		$cEntidad->setLoginCorreo((!empty($_POST["LSTLoginCorreo"])) ? $_POST["LSTLoginCorreo"] : "");	$cEntidad->setBusqueda(constant("STR_LOGIN_DE_CORREO"), (!empty($_POST["LSTLoginCorreo"])) ? $_POST["LSTLoginCorreo"] : "");
		$cEntidad->setPasswordCorreo((!empty($_POST["LSTPasswordCorreo"])) ? $_POST["LSTPasswordCorreo"] : "");	$cEntidad->setBusqueda(constant("STR_PASSWORD_DE_CORREO"), (!empty($_POST["LSTPasswordCorreo"])) ? $_POST["LSTPasswordCorreo"] : "");
		$cEntidad->setFecAlta((isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "") ? $_POST["LSTFecAlta"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_ALTA"), (isset($_POST["LSTFecAlta"]) && $_POST["LSTFecAlta"] != "" ) ? $conn->UserDate($_POST["LSTFecAlta"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecAltaHast((isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "") ? $_POST["LSTFecAltaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_ALTA") . constant("STR_HASTA"), (isset($_POST["LSTFecAltaHast"]) && $_POST["LSTFecAltaHast"] != "" ) ? $conn->UserDate($_POST["LSTFecAltaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecMod((isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "") ? $_POST["LSTFecMod"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_MOD"), (isset($_POST["LSTFecMod"]) && $_POST["LSTFecMod"] != "" ) ? $conn->UserDate($_POST["LSTFecMod"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecModHast((isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "") ? $_POST["LSTFecModHast"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_MOD") . constant("STR_HASTA"), (isset($_POST["LSTFecModHast"]) && $_POST["LSTFecModHast"] != "" ) ? $conn->UserDate($_POST["LSTFecModHast"],constant("USR_FECHA"),false) : "");
		global $comboUSUARIOS;
		$cEntidad->setUsuAlta((!empty($_POST["LSTUsuAlta"])) ? $_POST["LSTUsuAlta"] : "");	$cEntidad->setBusqueda(constant("STR_USU_ALTA"), (!empty($_POST["LSTUsuAlta"])) ? $comboUSUARIOS->getDescripcionCombo($_POST["LSTUsuAlta"]) : "");
		$cEntidad->setUsuMod((!empty($_POST["LSTUsuMod"])) ? $_POST["LSTUsuMod"] : "");	$cEntidad->setBusqueda(constant("STR_USU_MOD"), (!empty($_POST["LSTUsuMod"])) ? $comboUSUARIOS->getDescripcionCombo($_POST["LSTUsuMod"]) : "");
		$cEntidad->setFecBaja((!empty($_POST["LSTFecBaja"])) ? $_POST["LSTFecBaja"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_BAJA"), (!empty($_POST["LSTFecBaja"])) ? $conn->UserDate($_POST["LSTFecBaja"],constant("USR_FECHA"),false) : "");
		$cEntidad->setFecBajaHast((!empty($_POST["LSTFecBajaHast"])) ? $_POST["LSTFecBajaHast"] : "");	$cEntidad->setBusqueda(constant("STR_FEC_BAJA") . constant("STR_HASTA"), (!empty($_POST["LSTFecBajaHast"])) ? $conn->UserDate($_POST["LSTFecBajaHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setBajaLog((!empty($_POST["LSTBajaLog"])) ? $_POST["LSTBajaLog"] : "");	$cEntidad->setBusqueda(constant("STR_BAJA_LOG"), (!empty($_POST["LSTBajaLog"])) ? $_POST["LSTBajaLog"] : "");
		$cEntidad->setUltimoLogin((!empty($_POST["LSTUltimoLogin"])) ? $_POST["LSTUltimoLogin"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN"), (!empty($_POST["LSTUltimoLogin"])) ? $conn->UserDate($_POST["LSTUltimoLogin"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimoLoginHast((!empty($_POST["LSTUltimoLoginHast"])) ? $_POST["LSTUltimoLoginHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN") . constant("STR_HASTA"), (!empty($_POST["LSTUltimoLoginHast"])) ? $conn->UserDate($_POST["LSTUltimoLoginHast"],constant("USR_FECHA"),false) : "");
		global $comboUSUARIOS_TIPOS;
		$cEntidad->setIdUsuarioTipo((!empty($_POST["LSTIdUsuarioTipo"])) ? $_POST["LSTIdUsuarioTipo"] : "");	$cEntidad->setBusqueda(constant("STR_TIPO_DE_USUARIO"), (!empty($_POST["LSTIdUsuarioTipo"])) ? $comboUSUARIOS_TIPOS->getDescripcionCombo($_POST["LSTIdUsuarioTipo"]) : "");
		$cEntidad->setOrderBy((!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "");	$cEntidad->setBusqueda(constant("STR_ORDENAR_POR"), (!empty($_POST["LSTOrderBy"])) ? $_POST["LSTOrderBy"] : "");
		$cEntidad->setOrder((!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (!empty($_POST["LSTOrder"])) ? $_POST["LSTOrder"] : "");
		$cEntidad->setLineasPagina((!empty($_POST["LSTLineasPagina"])) ? $_POST["LSTLineasPagina"] : constant("CNF_LINEAS_PAGINA"));
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