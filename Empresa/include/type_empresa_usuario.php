<?php

	$_Timezone	= "";
	$_Prepago		= "";
	$_PathLogo	= "";
	$_Dongles		= "";
	$sPuebasEmpresa = "";
	$_EmpresaLogada = "";
	if (get_class($_cEntidadUsuarioTK) == "Empresas"){
		$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
		$_Timezone			= $_cEntidadUsuarioTK->getTimezone();
		$_Prepago				= $_cEntidadUsuarioTK->getPrepago();
		$_PathLogo			= $_cEntidadUsuarioTK->getPathLogo();
		$_Dongles				= $_cEntidadUsuarioTK->getDongles();
		$sPuebasEmpresa = $_cEntidadUsuarioTK->getIdsPruebas();
	}else{
		$_dataUSR="Empresa_usuario.php";
		$cEmpr = new Empresas();
		$cEmprDB = new EmpresasDB($conn);
		$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
		$cEmpr->setIdEmpresa($_EmpresaLogada);
		$cEmpr = $cEmprDB->readEntidad($cEmpr);
		$_Timezone			= $cEmpr->getTimezone();
		$_Prepago				= $cEmpr->getPrepago();
		$_PathLogo			= $cEmpr->getPathLogo();
		$_Dongles				= $cEmpr->getDongles();
		$sPuebasEmpresa	= $cEmpr->getIdsPruebas();
	}

 ?>
