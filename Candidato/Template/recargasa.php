<?php 
	$_sPrefijo = ""; 
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
    	$_sPrefijo = "../";
    }
    require_once($_sPrefijo . "include/Configuracion.php");

	
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpv.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpv.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpv.php");
	
include_once ('include/conexion.php');
	
	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");
		
	$cUtilidades	= new Utilidades();

	$cEmpresasDB	= new EmpresasDB($conn);
	$cEmpresas	= new Empresas();
	$cEmpresas->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
	$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
	
	$cTipos_tpvDB	= new Tipos_tpvDB($conn);
	$cTipos_tpv	= new Tipos_tpv();
	$cTipos_tpv->setIdTipoTpv($cEmpresas->getIdTipoTpv());
	$cTipos_tpv = $cTipos_tpvDB->readEntidad($cTipos_tpv);
	$_sTipoTpv = $cEmpresas->getIdTipoTpv();
	
	$cEmpresas_conf_tpvDB	= new Empresas_conf_tpvDB($conn);
	$cEmpresas_conf_tpv	= new Empresas_conf_tpv();
	$cEmpresas_conf_tpv->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
	$cEmpresas_conf_tpv->setIdTipoTpv($cEmpresas->getIdTipoTpv());
	$cEmpresas_conf_tpv = $cEmpresas_conf_tpvDB->readEntidad($cEmpresas_conf_tpv);
	
	
	$cEntidadDB	= new Candidatos_pagos_tpvDB($conn);  // Entidad DB
	$cEntidad	= new Candidatos_pagos_tpv();  // Entidad
	
    $cEntidad	= readEntidadPayPal($cEntidad);

	$cEntidadBack	= new Candidatos_pagos_tpv();  // Entidad
	$cEntidadBack->setLocalizador($cEntidad->getLocalizador());
	//echo "<br />//--> " . $cEntidad->getLocalizador();
	$cEntidadBack = $cEntidadDB->readEntidadLocalizador($cEntidadBack);
	
	if ($cEntidadBack->getIdRecarga() == "")
	{
//		echo ("<br />Inserto" . $cEntidadBack->getLocalizador());
		$newId	= $cEntidadDB->insertar($cEntidad);
		if (empty($newId)){
			error_log(date('d/m/Y H:i:s') . " Error generando LOCALIZADOR [recargasa][insertar] :\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
	}else{
//		echo ("<br />MOD" . $cEntidadBack->getIdRecarga());
		$cEntidad->setIdRecarga($cEntidadBack->getIdRecarga());
		$cEntidad = $cEntidadDB->readEntidad($cEntidad);
		$order=date('ymdHis');
		$cEntidad->setLocalizador($order);
		if (!$cEntidadDB->modificar($cEntidad)){
			error_log(date('d/m/Y H:i:s') . " Error REgenerando LOCALIZADOR [recargasa][modificar] :\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}
	}
	/*
	* "Lee" los parametros recibidos en el request y los asocia a una determinada Entidad.
	*/
	function readEntidadPayPal($cEntidad){
		global $_cEntidadCandidatoTK;
		global $cUtilidades;
		$cEntidad->setIdRecarga((isset($_POST["fIdRecarga"])) ? $_POST["fIdRecarga"] : "");
		$cEntidad->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
		$cEntidad->setIdProceso($_cEntidadCandidatoTK->getIdProceso());
		$cEntidad->setIdCandidato($_cEntidadCandidatoTK->getIdCandidato());
		$cEntidad->setLocalizador((isset($_POST["item_number"])) ? $_POST["item_number"] : "");
		$cEntidad->setDescripcion((isset($_POST["item_name"])) ? $_POST["item_name"] : "");
		$cEntidad->setImpBase((isset($_POST["amount"])) ? $_POST["amount"] : "0");
		$cEntidad->setImpImpuestos((isset($_POST["tax"])) ? $_POST["tax"] : "0");
		$cEntidad->setImpBaseImpuestos($cEntidad->getImpBase() + $cEntidad->getImpImpuestos());
		$cEntidad->setEmail($_cEntidadCandidatoTK->getMail());
		$cEntidad->setNombre($_cEntidadCandidatoTK->getNombre());
		$cEntidad->setApellidos($_cEntidadCandidatoTK->getApellido1() . " " .  $_cEntidadCandidatoTK->getApellido2());
		$cEntidad->setDireccion((isset($_POST["address1"])) ? $_POST["address1"] : "");
		$cEntidad->setCodPostal((isset($_POST["zip"])) ? $_POST["zip"] : "");
		$cEntidad->setCiudad((isset($_POST["city"])) ? $_POST["city"] : "");
		$cEntidad->setTelefono1((isset($_POST["night_phone_b"])) ? $_POST["night_phone_b"] : "");
		$cEntidad->setCodEstado(constant("STR_TPV_PENDIENTE"));
		$cEntidad->setCodAutorizacion((isset($_POST["fCodAutorizacion"])) ? $_POST["fCodAutorizacion"] : "");
		$cEntidad->setCodError((isset($_POST["fCodError"])) ? $_POST["fCodError"] : "");
		$cEntidad->setDesError((isset($_POST["fDesError"])) ? $_POST["fDesError"] : "");
		$cEntidad->setUsuAlta($_cEntidadCandidatoTK->getIdCandidato());
		$cEntidad->setUsuMod($_cEntidadCandidatoTK->getIdCandidato());
		return $cEntidad;
	}
	
?>