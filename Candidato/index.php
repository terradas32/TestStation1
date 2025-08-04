<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpv.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpv.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpv.php");
	require_once(constant("DIR_WS_COM") . "Ipinfodb/IPLocalizador.php");
include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
$cEntidad	= new Candidatos();  // Entidad
$cIPLocalizador = new IPLocalizador();

$strMensaje = "";
if (isset($_POST['fGo'])){
	if ((!empty($_POST['fLogin'])) && (!empty($_POST['fPwd'])))
	{
		$cEntidad->setMail($_POST['fLogin']);
		$cEntidad->setPassword($_POST['fPwd']);
		require_once(constant("DIR_WS_COM") . "/Utilidades.php");
		$cUtilidades	= new Utilidades();
        $bEncontradoUsuario = $cUtilidades->chkChar($_POST['fLogin']);
        $bEncontradoPassword = $cUtilidades->chkChar($_POST['fPwd']);

		if (!$bEncontradoPassword && !$bEncontradoUsuario)
        {
        	$rowUser = $cEntidadDB->Login($cEntidad);

        	if (!empty($rowUser["mail"]) && $rowUser["mail"] == $_POST['fLogin'])
        	{

        		if (!empty($rowUser["idProceso"]))
        		{
	        		$cEntidad->setMail($rowUser["mail"]);
	        		$cEntidad->setIdEmpresa($rowUser["idEmpresa"]);
	        		$cEntidad->setIdProceso($rowUser["idProceso"]);
	        		$cEntidad->setIdCandidato($rowUser["idCandidato"]);
	        		//Sacamos la información del proceso
	        		$cProcesosDB	= new ProcesosDB($conn);
					$cProcesos	= new Procesos();
					$cProcesos->setIdEmpresa($rowUser["idEmpresa"]);
					$cProcesos->setIdProceso($rowUser["idProceso"]);
					$cProcesos = $cProcesosDB->readEntidad($cProcesos);
					//Miramos desde donde se conecta el candidato

					//$ip = $cIPLocalizador->getRealIP();
					//$ip = "80.28.158.173";
					//$cIPLocalizadorData = $cIPLocalizador->getData($ip, constant("API_KEY_IPINFODB"));

// 					echo "<br />" . $cIPLocalizadorData->statusCode;
// 					echo "<br />" . $cIPLocalizadorData->ipAddress;
// 					echo "<br />" . $cIPLocalizadorData->countryCode;
// 					echo "<br />" . $cIPLocalizadorData->countryName;
// 					echo "<br />" . $cIPLocalizadorData->regionName;
// 					echo "<br />" . $cIPLocalizadorData->cityName;
// 					echo "<br />" . $cIPLocalizadorData->zipCode;
// 					echo "<br />" . $cIPLocalizadorData->latitude;
// 					echo "<br />" . $cIPLocalizadorData->longitude;
// 					echo "<br />" . $cIPLocalizadorData->timeZone;

					$cEmpresasDB	= new EmpresasDB($conn);
					$cEmpresas	= new Empresas();
					$cEmpresas->setIdEmpresa($rowUser["idEmpresa"]);
					$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

					$fecInicio = $cProcesos->getFechaInicio();
					$fecFin = $cProcesos->getFechaFin();
					//Miramos si puede iniciar las pruebas del proceso
					if ($cUtilidades->isCurrent2Dates($fecInicio, $fecFin, $cEmpresas->getTimezone()))
					{
						//Miramos si aun estándo dentro de fechas, ya ha finalizado
						//Todas las pruebas del proceso
//						if (empty($rowUser['finalizado']))
//						{
							$token =md5(uniqid('', true));
							$cEntidad->setToken($token);
							$cEntidadDB->ActualizaToken($cEntidad);

			        		//Actualizamos el último login
			        	    if ($cEntidadDB->ultimoLogin($cEntidad) == false)
			        		{
			        			echo constant("ERR");
			        			exit;
			        		}
			        		//Seteamos el token y las variables necesarias
			        		$_POST['sTKCandidatos'] = $token;
			        		if ($rowUser["pagoTpv"] == "1"){
			        			//Miramos a ver si ha pagado
			        			if (!empty($rowUser["localizadorTpv"]))
			        			{
			        				//Verificamos si el pago se ha realizado correctamente
			        				$cCandidatos_pagos_tpvDB	= new Candidatos_pagos_tpvDB($conn);
			        				$cCandidatos_pagos_tpv	= new Candidatos_pagos_tpv();
			        				$cCandidatos_pagos_tpv->setLocalizador($rowUser["localizadorTpv"]);
			        				$cCandidatos_pagos_tpv = $cCandidatos_pagos_tpvDB->readEntidadLocalizador($cCandidatos_pagos_tpv);
			        				if ($cCandidatos_pagos_tpv->getIdRecarga() != "" && (intval($cCandidatos_pagos_tpv->getCodError()) >= 0 && intval($cCandidatos_pagos_tpv->getCodError()) <= 99) )
			        				{
										include_once('datosprofesionales.php');
			        				}else {
			        					//Lanzamos la pasarela
			        					echo "<br />1::Lanzar pasarela.";
			        				}
			        			}else{
			        				//Lanzamos la pasarela es el primer intento
	        						//Sacamos los datos de la empresa
	        						$cEmpresasDB	= new EmpresasDB($conn);
									$cEmpresas	= new Empresas();
	        						$cEmpresas->setIdEmpresa($rowUser["idEmpresa"]);
	        						$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
	        						if ($cEmpresas->getSrvTPV() == "1"){
	        							$_sTipoTpv = $cEmpresas->getIdTipoTpv();

	        							$cTipos_tpvDB	= new Tipos_tpvDB($conn);
	        							$cTipos_tpv	= new Tipos_tpv();
	        							$cTipos_tpv->setIdTipoTpv($_sTipoTpv);
	        							$cTipos_tpv = $cTipos_tpvDB->readEntidad($cTipos_tpv);


	        							if (empty($_sTipoTpv)){
	        								$strMensaje = "<br />Code:FD_TTPV0400 - Ha ocurrido un error al contactar con la pasarela del banco.<br />Contacte con " . $cEmpresas->getNombre();
	        							}else{
	        								//Miramos los datos de configuración del TPV
	        								$cEmpresas_conf_tpvDB	= new Empresas_conf_tpvDB($conn);
	        								$cEmpresas_conf_tpv	= new Empresas_conf_tpv();
	        								$cEmpresas_conf_tpv->setIdEmpresa($rowUser["idEmpresa"]);
	        								$cEmpresas_conf_tpv->setIdTipoTpv($_sTipoTpv);
	        								$cEmpresas_conf_tpv = $cEmpresas_conf_tpvDB->readEntidad($cEmpresas_conf_tpv);
// 	        								echo "<br />32::Lanzar pasarela primer intento.";
// 	        								echo "<br />32:mail:" . $rowUser["mail"];
// 	        								echo "<br />32:idEmpresa:" . $rowUser["idEmpresa"];
// 	        								echo "<br />32:idProceso:" . $rowUser["idProceso"];
// 	        								echo "<br />32:idCandidato:" . $rowUser["idCandidato"];
	        								if ($cEmpresas_conf_tpv->getBUSINESS_CODE() == ""){
	        									$strMensaje = "<br />Code:FD_BCODE0400 - Ha ocurrido un error al contactar con la pasarela del banco.<br />Contacte con " . $cEmpresas->getNombre();
	        								}else{
	        									include_once('lanzaTpv.php');
	        								}
	        							}
	        						}else{
	        							//La empresa ha desactivado el servicio, Por lo que no se lanzará la pasarela.
	        							error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> La empresa ha desactivado el servicio de TPV, Por lo que no se lanzará la pasarela.\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
	        							include_once('datosprofesionales.php');
	        						}
			        			}
			        		}else{
			        			include_once('datosprofesionales.php');
			        		}
//						}else{
//							$strMensaje = constant("STR_USTED_HA_FINALIZADO_TODAS_LAS_PRUEBAS_DE_ESTE_PROCESO");
//						}
					}else{
						$strMensaje = constant("STR_PROCESO_FUERA_DE_FECHAS");
					}
        		}else{
        			$strMensaje = constant("ERR_NO_AUTORIZADO");
        		}
        	}else{
        		$strMensaje = constant("ERR_NO_AUTORIZADO");
        	}
        }else $strMensaje = constant("ERR_FORM_LOGIN");
	}else $strMensaje = constant("ERR_FORM_LOGIN");
	if (!empty($strMensaje)){
        include('Template/login.php');
    }
}else{
	include('Template/login.php');
}
?>
