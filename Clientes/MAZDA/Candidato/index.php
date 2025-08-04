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

include_once ('include/conexion.php');
	
$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);	

$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
$cEntidad	= new Candidatos();  // Entidad

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
        	
        	if (!empty($rowUser["mail"]) && $rowUser["mail"] == $_POST['fLogin'] )
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
					
					$fecInicio = $cProcesos->getFechaInicio();
					$fecFin = $cProcesos->getFechaFin();
					//Miramos si puede iniciar las pruebas del proceso
					if ($cUtilidades->isCurrent2Dates($fecInicio, $fecFin))
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
			        		
			        		include_once('datosprofesionales.php');
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
