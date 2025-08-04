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
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
	require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpv.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Escalas_items/Escalas_items.php");
	require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");
	require_once(constant("DIR_WS_COM") . "Baremos_empresas/Baremos_empresasDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_empresas/Baremos_empresas.php");

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
	$cEntidad	= new Empresas();  // Entidad

	$cEntidadTPVDB	= new Empresas_conf_tpvDB($conn);  // Entidad DB
	$cEntidadTPV	= new Empresas_conf_tpv();  // Entidad

	$cNotificacionesDB	= new NotificacionesDB($conn);
	$cNotificaciones	= new Notificaciones();

	$cBaremosDB	= new BaremosDB($conn);
	$cBaremos	= new Baremos();

	$cBaremos_empresasDB	= new Baremos_empresasDB($conn);
	$cBaremos_empresas	= new Baremos_empresas();


	$cEscalasItemsDB = new Escalas_itemsDB($conn);
	$cEscalasItems = new Escalas_items();



	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
	$_POST["fIdEmpresa"] = $_cEntidadUsuarioTK->getIdEmpresa();
	$_POST["LSTIdEmpresa"] = $_cEntidadUsuarioTK->getIdEmpresa();

	$sSQLPruebaIN = "";
	if (!empty($_POST["fIdEmpresa"])){
		$cEntidad->setIdEmpresa($_POST["fIdEmpresa"]);
		$cEntidad = $cEntidadDB->readEntidad($cEntidad);
		$sSQLPruebaIN = $cEntidad->getIdsPruebas();
		if (!empty($sSQLPruebaIN)){
			//chequeamos si el primer caracter es una coma
			if (substr($sSQLPruebaIN, 0, 1) == ","){
				$sSQLPruebaIN = substr($sSQLPruebaIN, 1);
			}
			$sSQLPruebaIN = " idPrueba IN (" . $sSQLPruebaIN . ") ";
		}
	}
	$comboDENTRO_DE	= new	Combo($conn,"fDentroDe","orden",$conn->Concat("'" . constant("STR_DENTRO_DE") . "'", "' - '", "nombre"),"Descripcion","empresas","","- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -","idEmpresa IN(" . $_cEntidadUsuarioTK->getIdEmpresa() . "," . $_cEntidadUsuarioTK->getIdPadre() . ")","","orden");
	$comboDESPUES_DE	= new	Combo($conn,"fDespuesDe","orden",$conn->Concat("'" . constant("STR_DESPUES_DE") . "'", "' - '", "nombre"),"Descripcion","empresas","","- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -","idEmpresa IN(" . $_cEntidadUsuarioTK->getIdEmpresa() . ")","","orden");
	$comboWI_PAISES	= new Combo($conn,"fIdPais","idPais","descripcion","Descripcion","wi_paises","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","fecMod");
	$comboEMPRESAS	= new Combo($conn,"fIdEmpresa","idEmpresa","nombre","Descripcion","empresas","",constant("SLC_OPCION"),"idEmpresa IN(" . $_cEntidadUsuarioTK->getIdEmpresa() . ")","","orden");
	$comboPRUEBASGROUP	= new Combo($conn,"fIdPrueba","idPrueba","nombre","Descripcion","pruebas","",constant("SLC_OPCION"),$sSQLPruebaIN,"","","idprueba");
	$comboTIPOS_TPV	= new Combo($conn,"fIdtipoTpv","idTipoTpv","descripcion","Descripcion","tipos_tpv","",constant("SLC_OPCION"),"bajaLog=0","","","idTipoTpv");
	//echo('modo:' . $_POST['MODO']);

	if (!isset($_POST['MODO'])){
		session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}
	switch ($_POST['MODO'])
	{
		case constant("MNT_MODIFICAR"):
			$cEntidad = readEntidad($cEntidad);
			quitaImg($cEntidad, $cEntidadDB, false);
			if ($cEntidadDB->modificar($cEntidad))
			{
			    $_POST['MODO']    = constant("MNT_MODIFICAR");
				include('Template/Empresa/mntempresaa.php');
			}else{
				?><script language="javascript" type="text/javascript">jAlert("<?php echo constant("ERR_FORM_ERROR");?>\n<?php echo $cEntidadDB->ver_errores();?>","<?php echo constant("STR_NOTIFICACION");?>");</script><?php
				$_POST['MODO']=constant("MNT_MODIFICAR");
				include('Template/Empresa/mntempresaa.php');
			}
			break;
		default:
		  $cEntidad = readEntidad($cEntidad);
			$cEntidad = $cEntidadDB->readEntidad($cEntidad);
			$_POST['MODO']    = constant("MNT_MODIFICAR");
			include('Template/Empresa/mntempresaa.php');
			break;
	} // end switch
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidad($cEntidad){
		global $_cEntidadUsuarioTK;
		global $cUtilidades;
		global $cEntidadTPV;
		$cEntidad->setIdEmpresa((isset($_POST["fIdEmpresa"])) ? $_POST["fIdEmpresa"] : "");
		$cEntidad->setNombre((isset($_POST["fNombre"])) ? $_POST["fNombre"] : "");
		$cEntidad->setIdPadre((isset($_POST["fIdPadre"])) ? $_POST["fIdPadre"] : "");
		$cEntidad->setCif((isset($_POST["fCif"])) ? $_POST["fCif"] : "");
		$cEntidad->setUsuario((isset($_POST["fUsuario"])) ? $_POST["fUsuario"] : "");
		$cEntidad->setPassword((isset($_POST["fPassword"])) ? $_POST["fPassword"] : "");
		$cEntidad->setPathLogo((isset($_POST["fPathLogo"])) ? $_POST["fPathLogo"] : "");
		$cEntidad->setMail((isset($_POST["fMail"])) ? $_POST["fMail"] : "");
		$cEntidad->setMail2((isset($_POST["fMail2"])) ? $_POST["fMail2"] : "");
		$cEntidad->setMail3((isset($_POST["fMail3"])) ? $_POST["fMail3"] : "");
		$cEntidad->setDistribuidor((isset($_POST["fDistribuidor"])) ? $_POST["fDistribuidor"] : "");
		$cEntidad->setPrepago((isset($_POST["fPrepago"])) ? $_POST["fPrepago"] : "");
		$cEntidad->setNcandidatos((isset($_POST["fNcandidatos"])) ? $_POST["fNcandidatos"] : "");
		$cEntidad->setDongles((isset($_POST["fDongles"])) ? $_POST["fDongles"] : "");
		$cEntidad->setEntidad((isset($_POST["fEntidad"])) ? $_POST["fEntidad"] : "");
		$cEntidad->setOficina((isset($_POST["fOficina"])) ? $_POST["fOficina"] : "");
		$cEntidad->setDc((isset($_POST["fDc"])) ? $_POST["fDc"] : "");
		$cEntidad->setCuenta((isset($_POST["fCuenta"])) ? $_POST["fCuenta"] : "");
		$cEntidad->setIdPais((isset($_POST["fIdPais"])) ? $_POST["fIdPais"] : "");
		$cEntidad->setTimezone((isset($_POST["fTimezone"])) ? $_POST["fTimezone"] : "");
		$cEntidad->setDireccion((isset($_POST["fDireccion"])) ? $_POST["fDireccion"] : "");

		$cEntidad->setPersonaContacto((isset($_POST["fPersonaContacto"])) ? $_POST["fPersonaContacto"] : "");
		$cEntidad->setTlfContacto((isset($_POST["fTlfContacto"])) ? $_POST["fTlfContacto"] : "");

		$cEntidad->setUmbral_aviso((isset($_POST["fUmbral_aviso"])) ? $_POST["fUmbral_aviso"] : "");
		$cEntidad->setOrden((isset($_POST["fOrden"])) ? $_POST["fOrden"] : "");
		$cEntidad->setIndentacion((isset($_POST["fIndentacion"])) ? $_POST["fIndentacion"] : "");
		$cEntidad->setUltimoLogin((isset($_POST["fUltimoLogin"])) ? $_POST["fUltimoLogin"] : "");
		$cEntidad->setToken((isset($_POST["fToken"])) ? $_POST["fToken"] : "");
		$cEntidad->setUltimaAcc((isset($_POST["fUltimaAcc"])) ? $_POST["fUltimaAcc"] : "");
		$cEntidad->setFecAlta((isset($_POST["fFecAlta"])) ? $_POST["fFecAlta"] : "");
		$cEntidad->setFecMod((isset($_POST["fFecMod"])) ? $_POST["fFecMod"] : "");
		$cEntidad->setDentroDe((isset($_POST["fDentroDe"])) ? $_POST["fDentroDe"] : "");
		$cEntidad->setDespuesDe((isset($_POST["fDespuesDe"])) ? $_POST["fDespuesDe"] : "");
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
		global $cEntidadTPV;
		global $_cEntidadUsuarioTK;
		global $sHijos;
		$cEntidad->setIdEmpresa((isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "") ? $_POST["LSTIdEmpresa"] : $sHijos);	$cEntidad->setBusqueda(constant("STR_ID_EMPRESA"), (isset($_POST["LSTIdEmpresa"]) && $_POST["LSTIdEmpresa"] != "" ) ? $_POST["LSTIdEmpresa"] : "*");
		$cEntidad->setIdEmpresaHast((isset($_POST["LSTIdEmpresaHast"]) && $_POST["LSTIdEmpresaHast"] != "") ? $_POST["LSTIdEmpresaHast"] : "");	$cEntidad->setBusqueda(constant("STR_ID_EMPRESA") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdEmpresaHast"]) && $_POST["LSTIdEmpresaHast"] != "" ) ? $_POST["LSTIdEmpresaHast"] : "");
		$cEntidad->setIdPadre((isset($_POST["LSTIdPadre"]) && $_POST["LSTIdPadre"] != "") ? $_POST["LSTIdPadre"] : "");	$cEntidad->setBusqueda(constant("STR_PADRE"), (isset($_POST["LSTIdPadre"]) && $_POST["LSTIdPadre"] != "" ) ? $_POST["LSTIdPadre"] : "");
		$cEntidad->setIdPadreHast((isset($_POST["LSTIdPadreHast"]) && $_POST["LSTIdPadreHast"] != "") ? $_POST["LSTIdPadreHast"] : "");	$cEntidad->setBusqueda(constant("STR_PADRE") . " " . constant("STR_HASTA"), (isset($_POST["LSTIdPadreHast"]) && $_POST["LSTIdPadreHast"] != "" ) ? $_POST["LSTIdPadreHast"] : "");
		$cEntidad->setNombre((isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "") ? $_POST["LSTNombre"] : "");	$cEntidad->setBusqueda(constant("STR_NOMBRE"), (isset($_POST["LSTNombre"]) && $_POST["LSTNombre"] != "" ) ? $_POST["LSTNombre"] : "");
		$cEntidad->setCif((isset($_POST["LSTCif"]) && $_POST["LSTCif"] != "") ? $_POST["LSTCif"] : "");	$cEntidad->setBusqueda(constant("STR_CIF"), (isset($_POST["LSTCif"]) && $_POST["LSTCif"] != "" ) ? $_POST["LSTCif"] : "");
		$cEntidad->setUsuario((isset($_POST["LSTUsuario"]) && $_POST["LSTUsuario"] != "") ? $_POST["LSTUsuario"] : "");	$cEntidad->setBusqueda(constant("STR_USUARIO"), (isset($_POST["LSTUsuario"]) && $_POST["LSTUsuario"] != "" ) ? $_POST["LSTUsuario"] : "");
		$cEntidad->setPassword((isset($_POST["LSTPassword"]) && $_POST["LSTPassword"] != "") ? $_POST["LSTPassword"] : "");	$cEntidad->setBusqueda(constant("STR_CONTRASENA"), (isset($_POST["LSTPassword"]) && $_POST["LSTPassword"] != "" ) ? $_POST["LSTPassword"] : "");
		$cEntidad->setPathLogo((isset($_POST["LSTPathLogo"]) && $_POST["LSTPathLogo"] != "") ? $_POST["LSTPathLogo"] : "");	$cEntidad->setBusqueda(constant("STR_LOGO"), (isset($_POST["LSTPathLogo"]) && $_POST["LSTPathLogo"] != "" ) ? $_POST["LSTPathLogo"] : "");
		$cEntidad->setMail((isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "") ? $_POST["LSTMail"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL"), (isset($_POST["LSTMail"]) && $_POST["LSTMail"] != "" ) ? $_POST["LSTMail"] : "");
		$cEntidad->setMail2((isset($_POST["LSTMail2"]) && $_POST["LSTMail2"] != "") ? $_POST["LSTMail2"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL_2"), (isset($_POST["LSTMail2"]) && $_POST["LSTMail2"] != "" ) ? $_POST["LSTMail2"] : "");
		$cEntidad->setMail3((isset($_POST["LSTMail3"]) && $_POST["LSTMail3"] != "") ? $_POST["LSTMail3"] : "");	$cEntidad->setBusqueda(constant("STR_EMAIL_3"), (isset($_POST["LSTMail3"]) && $_POST["LSTMail3"] != "" ) ? $_POST["LSTMail3"] : "");
		$cEntidad->setDistribuidor((isset($_POST["LSTDistribuidor"]) && $_POST["LSTDistribuidor"] != "") ? $_POST["LSTDistribuidor"] : "");	$cEntidad->setBusqueda(constant("STR_DISTRIBUIDOR"), (isset($_POST["LSTDistribuidor"]) && $_POST["LSTDistribuidor"] != "" ) ? $_POST["LSTDistribuidor"] : "");
		$cEntidad->setPrepago((isset($_POST["LSTPrepago"]) && $_POST["LSTPrepago"] != "") ? $_POST["LSTPrepago"] : "");	$cEntidad->setBusqueda(constant("STR_PREPAGO"), (isset($_POST["LSTPrepago"]) && $_POST["LSTPrepago"] != "" ) ? $_POST["LSTPrepago"] : "");
		$cEntidad->setNcandidatos((isset($_POST["LSTNcandidatos"]) && $_POST["LSTNcandidatos"] != "") ? $_POST["LSTNcandidatos"] : "");	$cEntidad->setBusqueda(constant("STR_NU_CANDIDATOS"), (isset($_POST["LSTNcandidatos"]) && $_POST["LSTNcandidatos"] != "" ) ? $_POST["LSTNcandidatos"] : "");
		$cEntidad->setNcandidatosHast((isset($_POST["LSTNcandidatosHast"]) && $_POST["LSTNcandidatosHast"] != "") ? $_POST["LSTNcandidatosHast"] : "");	$cEntidad->setBusqueda(constant("STR_NU_CANDIDATOS") . " " . constant("STR_HASTA"), (isset($_POST["LSTNcandidatosHast"]) && $_POST["LSTNcandidatosHast"] != "" ) ? $_POST["LSTNcandidatosHast"] : "");
		$cEntidad->setDongles((isset($_POST["LSTDongles"]) && $_POST["LSTDongles"] != "") ? $_POST["LSTDongles"] : "");	$cEntidad->setBusqueda(constant("STR_DONGLES"), (isset($_POST["LSTDongles"]) && $_POST["LSTDongles"] != "" ) ? $_POST["LSTDongles"] : "");
		$cEntidad->setDonglesHast((isset($_POST["LSTDonglesHast"]) && $_POST["LSTDonglesHast"] != "") ? $_POST["LSTDonglesHast"] : "");	$cEntidad->setBusqueda(constant("STR_DONGLES") . " " . constant("STR_HASTA"), (isset($_POST["LSTDonglesHast"]) && $_POST["LSTDonglesHast"] != "" ) ? $_POST["LSTDonglesHast"] : "");
		$cEntidad->setEntidad((isset($_POST["LSTEntidad"]) && $_POST["LSTEntidad"] != "") ? $_POST["LSTEntidad"] : "");	$cEntidad->setBusqueda(constant("STR_ENTIDAD"), (isset($_POST["LSTEntidad"]) && $_POST["LSTEntidad"] != "" ) ? $_POST["LSTEntidad"] : "");
		$cEntidad->setOficina((isset($_POST["LSTOficina"]) && $_POST["LSTOficina"] != "") ? $_POST["LSTOficina"] : "");	$cEntidad->setBusqueda(constant("STR_OFICINA"), (isset($_POST["LSTOficina"]) && $_POST["LSTOficina"] != "" ) ? $_POST["LSTOficina"] : "");
		$cEntidad->setDc((isset($_POST["LSTDc"]) && $_POST["LSTDc"] != "") ? $_POST["LSTDc"] : "");	$cEntidad->setBusqueda(constant("STR_DC"), (isset($_POST["LSTDc"]) && $_POST["LSTDc"] != "" ) ? $_POST["LSTDc"] : "");
		$cEntidad->setCuenta((isset($_POST["LSTCuenta"]) && $_POST["LSTCuenta"] != "") ? $_POST["LSTCuenta"] : "");	$cEntidad->setBusqueda(constant("STR_CUENTA"), (isset($_POST["LSTCuenta"]) && $_POST["LSTCuenta"] != "" ) ? $_POST["LSTCuenta"] : "");
		global $comboWI_PAISES;
		$cEntidad->setIdPais((isset($_POST["LSTIdPais"]) && $_POST["LSTIdPais"] != "") ? $_POST["LSTIdPais"] : "");	$cEntidad->setBusqueda(constant("STR_PAIS"), (isset($_POST["LSTIdPais"]) && $_POST["LSTIdPais"] != "" ) ? $comboWI_PAISES->getDescripcionCombo($_POST["LSTIdPais"]) : "");
		$cEntidad->setDireccion((isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "") ? $_POST["LSTDireccion"] : "");	$cEntidad->setBusqueda(constant("STR_DIRECCION"), (isset($_POST["LSTDireccion"]) && $_POST["LSTDireccion"] != "" ) ? $_POST["LSTDireccion"] : "");
		$cEntidad->setUmbral_aviso((isset($_POST["LSTUmbral_aviso"]) && $_POST["LSTUmbral_aviso"] != "") ? $_POST["LSTUmbral_aviso"] : "");	$cEntidad->setBusqueda(constant("STR_UMBRAL_DE_AVISO"), (isset($_POST["LSTUmbral_aviso"]) && $_POST["LSTUmbral_aviso"] != "" ) ? $_POST["LSTUmbral_aviso"] : "");
		$cEntidad->setUmbral_avisoHast((isset($_POST["LSTUmbral_avisoHast"]) && $_POST["LSTUmbral_avisoHast"] != "") ? $_POST["LSTUmbral_avisoHast"] : "");	$cEntidad->setBusqueda(constant("STR_UMBRAL_DE_AVISO") . " " . constant("STR_HASTA"), (isset($_POST["LSTUmbral_avisoHast"]) && $_POST["LSTUmbral_avisoHast"] != "" ) ? $_POST["LSTUmbral_avisoHast"] : "");
		$cEntidad->setOrden((isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "") ? $_POST["LSTOrden"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN"), (isset($_POST["LSTOrden"]) && $_POST["LSTOrden"] != "" ) ? $_POST["LSTOrden"] : "");
		$cEntidad->setOrdenHast((isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "") ? $_POST["LSTOrdenHast"] : "");	$cEntidad->setBusqueda(constant("STR_ORDEN") . " " . constant("STR_HASTA"), (isset($_POST["LSTOrdenHast"]) && $_POST["LSTOrdenHast"] != "" ) ? $_POST["LSTOrdenHast"] : "");
		$cEntidad->setIndentacion((isset($_POST["LSTIndentacion"]) && $_POST["LSTIndentacion"] != "") ? $_POST["LSTIndentacion"] : "");	$cEntidad->setBusqueda(constant("STR_INDENTACION"), (isset($_POST["LSTIndentacion"]) && $_POST["LSTIndentacion"] != "" ) ? $_POST["LSTIndentacion"] : "");
		$cEntidad->setIndentacionHast((isset($_POST["LSTIndentacionHast"]) && $_POST["LSTIndentacionHast"] != "") ? $_POST["LSTIndentacionHast"] : "");	$cEntidad->setBusqueda(constant("STR_INDENTACION") . " " . constant("STR_HASTA"), (isset($_POST["LSTIndentacionHast"]) && $_POST["LSTIndentacionHast"] != "" ) ? $_POST["LSTIndentacionHast"] : "");
		$cEntidad->setUltimoLogin((isset($_POST["LSTUltimoLogin"]) && $_POST["LSTUltimoLogin"] != "") ? $_POST["LSTUltimoLogin"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN"), (isset($_POST["LSTUltimoLogin"]) && $_POST["LSTUltimoLogin"] != "" ) ? $conn->UserDate($_POST["LSTUltimoLogin"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimoLoginHast((isset($_POST["LSTUltimoLoginHast"]) && $_POST["LSTUltimoLoginHast"] != "") ? $_POST["LSTUltimoLoginHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMO_LOGIN") . " " . constant("STR_HASTA"), (isset($_POST["LSTUltimoLoginHast"]) && $_POST["LSTUltimoLoginHast"] != "" ) ? $conn->UserDate($_POST["LSTUltimoLoginHast"],constant("USR_FECHA"),false) : "");
		$cEntidad->setToken((isset($_POST["LSTToken"]) && $_POST["LSTToken"] != "") ? $_POST["LSTToken"] : "");	$cEntidad->setBusqueda(constant("STR_TOKEN"), (isset($_POST["LSTToken"]) && $_POST["LSTToken"] != "" ) ? $_POST["LSTToken"] : "");
		$cEntidad->setUltimaAcc((isset($_POST["LSTUltimaAcc"]) && $_POST["LSTUltimaAcc"] != "") ? $_POST["LSTUltimaAcc"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMA_ACC"), (isset($_POST["LSTUltimaAcc"]) && $_POST["LSTUltimaAcc"] != "" ) ? $conn->UserDate($_POST["LSTUltimaAcc"],constant("USR_FECHA"),false) : "");
		$cEntidad->setUltimaAccHast((isset($_POST["LSTUltimaAccHast"]) && $_POST["LSTUltimaAccHast"] != "") ? $_POST["LSTUltimaAccHast"] : "");	$cEntidad->setBusqueda(constant("STR_ULTIMA_ACC") . " " . constant("STR_HASTA"), (isset($_POST["LSTUltimaAccHast"]) && $_POST["LSTUltimaAccHast"] != "" ) ? $conn->UserDate($_POST["LSTUltimaAccHast"],constant("USR_FECHA"),false) : "");
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
		if (!empty($_POST['cfPathLogo']) && strtoupper($_POST['cfPathLogo']) == 'ON'){
			$cEntidad->setPathLogo($_POST['cfPathLogo']);
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
			$_POST['cfPathLogo'] = 'on';
	}

?>
