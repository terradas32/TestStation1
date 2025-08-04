<div id="okSeguir">
<?php 
	require_once('./include/Configuracion.php');
	//include_once('include/Servired/confServired.php');
	include_once('include/Idiomas.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	if (!defined('ADODB_ASSOC_CASE')){
		define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
		include(constant("DIR_ADODB") . 'adodb.inc.php');
	}	
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpv.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cCandidatos_pagos_tpvDB	= new Candidatos_pagos_tpvDB($conn);
	$cCandidatos_pagos_tpv	= new Candidatos_pagos_tpv();

	$cEntidadCandidatosDB	= new CandidatosDB($conn);
	$cEntidadCandidatos	= new Candidatos();

	if (!empty($_POST['sTKCandidatos']))
	{
		$sTK = $_POST['sTKCandidatos'];
		$cEntidadCandidatos->setToken($sTK);
		$cEntidadCandidatos = $cEntidadCandidatosDB->usuarioPorToken($cEntidadCandidatos);
		if ($cEntidadCandidatos->getLocalizadorTpv() != "")
		{
			$cCandidatos_pagos_tpv->setLocalizador($cEntidadCandidatos->getLocalizadorTpv());
			$cCandidatos_pagos_tpv = $cCandidatos_pagos_tpvDB->readEntidadLocalizador($cCandidatos_pagos_tpv);
			if ($cCandidatos_pagos_tpv->getIdRecarga() != "" && ((intval($cCandidatos_pagos_tpv->getCodError()) >= 0 && intval($cCandidatos_pagos_tpv->getCodError()) <= 99)) )
			{
				echo '
				<script   >
					var f=document.forms[0];
					f.action.value="datosprofesionales.php";
					f.submit();
				</script>';	
			}
		}
	}
	
?>
</div>
	