<?php 
	require_once('./include/Configuracion.php');
	include_once('include/Servired/confServired.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos_pagos_tpv/Candidatos_pagos_tpv.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Tipos_tpv/Tipos_tpv.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpvDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas_conf_tpv/Empresas_conf_tpv.php");
	
	
include_once ('include/conexion.php');
	
	$cUtilidades	= new Utilidades();
	
	$cCandidatos_pagos_tpvDB	= new Candidatos_pagos_tpvDB($conn);
	$cCandidatos_pagos_tpv	= new Candidatos_pagos_tpv();

	$cEntidadCandidatosDB	= new CandidatosDB($conn);
	$cEntidadCandidatos	= new Candidatos();

	//Leer POST del sistema de PayPal y añadir 'cmd'
	$req = "";
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$req .= "$key=$value";
	}
	error_log(date('d/m/Y H:i:s') . "\t" . $req . "\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));

	$version=(!empty($_POST['Ds_SignatureVersion'])) ? $_POST['Ds_SignatureVersion'] : "";
	$params = (!empty($_POST['Ds_MerchantParameters'])) ? $_POST['Ds_MerchantParameters'] : "";
	$signatureRX = (!empty($_POST['Ds_Signature'])) ? $_POST['Ds_Signature'] : "";
	
	if (empty($version) || empty($params) || empty($signatureRX)){
		error_log(date('d/m/Y H:i:s') . "\tNo se han recibido los datos correctos::\t" . $req . "\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));		
	}
	
//    $sCodError			=	$_POST['Ds_Response'];
//	$sIdTerminal		=	$_POST['Ds_Terminal'];
//	$sIdComercio		=	$_POST['Ds_MerchantCode'];
//	$sIdTransaccion		=	$_POST['Ds_Order'];
//	$sMoneda			=	$_POST['Ds_Currency'];
//	$sImporte			=	$_POST['Ds_Amount'];
//	$sFechaHora			=	$_POST['Ds_Date'] . $_POST['Ds_Hour'];
//	$sCodAutorizacion	=	$_POST['Ds_AuthorisationCode'];
//	$sFirma				=	$_POST['Ds_Signature'];
//	$sTK				=	$_POST['Ds_MerchantData'];

	// Se incluye la librería
	include_once(constant("DIR_WS_INCLUDE") . 'Servired/apiRedsys.php');
	// Se crea Objeto
	$miObj = new RedsysAPI;

	$decodec = $miObj->decodeMerchantParameters($params);
	
    $sCodError			=	$miObj->getParameter('Ds_Response');
	$sIdTerminal		=	$miObj->getParameter('Ds_Terminal');
	$sIdComercio		=	$miObj->getParameter('Ds_MerchantCode');
	$sIdTransaccion		=	$miObj->getParameter('Ds_Order');
	$sMoneda			=	$miObj->getParameter('Ds_Currency');
	$sImporte			=	$miObj->getParameter('Ds_Amount');
	$sFechaHora			=	$miObj->getParameter('Ds_Date') . " " . $miObj->getParameter('Ds_Hour');
	$sCodAutorizacion	=	$miObj->getParameter('Ds_AuthorisationCode');
	$sFirma				=	$signatureRX;
	$sTK				=	$miObj->getParameter('Ds_MerchantData');
	
	$_params = "\nDs_Response::" . $sCodError;
	$_params .= "\nDs_Terminal::" . $sIdTerminal;
	
	$_params .= "\nDs_MerchantCode::" . $sIdComercio;
	$_params .= "\nDs_Order::" . $sIdTransaccion;
	$_params .= "\nDs_Currency::" . $sMoneda;
	$_params .= "\nDs_Amount::" . $sImporte;
	$_params .= "\nsFechaHora::" . $sFechaHora;
	$_params .= "\nDs_AuthorisationCode::" . $sCodAutorizacion;
	$_params .= "\nDs_MerchantData::" . $sTK;
	
	error_log(date('d/m/Y H:i:s') . " PARAMETROS:\t" . $_params . " \n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));	
	if (!empty($sTK))
	{
		$cEntidadCandidatos->setToken($sTK);
		$cEntidadCandidatos = $cEntidadCandidatosDB->usuarioPorToken($cEntidadCandidatos);
	}else{
		//FALTA TK de usuario
		error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> FALTA TK de usuario:\t" . $req . " \n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
		exit;
	}

	switch ($sCodError)
	{
		case (intval($sCodError) >= 0 && intval($sCodError) <= 99):
			$sDesError			=	"Transacción autorizada";
			break;
		case "101":
			$sDesError			=	"Tarjeta caducada";
			break;
		case "102":
			$sDesError			=	"Tarjeta en excepción transitoria o bajo sospecha de fraude";
			break;
		case "106":
			$sDesError			=	"Intentos de PIN excedidos";
			break;
		case "125":
			$sDesError			=	"Tarjeta no efectiva";
			break;
		case "129":
			$sDesError			=	"Código de seguridad (CVV2/CVC2) incorrecto";
			break;
		case "180":
			$sDesError			=	"Tarjeta ajena al servicio";
			break;
		case "184":
			$sDesError			=	"Error en la autenticación del titular";
			break;
		case "190":
			$sDesError			=	"Denegación del emisor sin especificar motivo";
			break;
		case "191":
			$sDesError			=	"Fecha de caducidad errónea";
			break;
		case "202":
			$sDesError			=	"Tarjeta en excepción transitoria o bajo sospecha de fraude con retirada de tarjeta";
			break;
		case "904":
			$sDesError			=	"Comercio no registrado en FUC";
			break;
		case "909":
			$sDesError			=	"Error de sistema";
			break;
		case "913":
			$sDesError			=	"Pedido repetido";
			break;
		case "944":
			$sDesError			=	"Sesión Incorrecta";
			break;
		case "950":
			$sDesError			=	"Operación de devolución no permitida";
			break;
		case "9912":
		case "912":
			$sDesError			=	"Emisor no disponible";
			break;
		case "9064":
		    $sDesError			=	"Número de posiciones de la tarjeta incorrecto";
		    break;
		case "9078":
		    $sDesError			=	"Tipo de operación no permitida para esa tarjeta";
		    break;
		case "9093":
		    $sDesError			=	"Tarjeta no existente";
		    break;
		case "9094":
		    $sDesError			=	"Rechazo servidores internacionales";
		    break;
		case "9104":
		    $sDesError			=	"Comercio con “titular seguro” y titular sin clave de compra segura";
		    break;
		case "9218":
		    $sDesError			=	"El comercio no permite op. seguras por entrada /operaciones";
		    break;
		case "9253":
		    $sDesError			=	"Tarjeta no cumple el check-digit";
		    break;
		case "9256":
		    $sDesError			=	"El comercio no puede realizar preautorizaciones";
		    break;
		case "9257":
		    $sDesError			=	"Esta tarjeta no permite operativa de preautorizaciones";
		    break;
		case "9261":
		    $sDesError			=	"Operación detenida por superar el control de restricciones en la entrada al SIS";
		    break;
		case "9913":
		    $sDesError			=	"Error en la confirmación que el comercio envía al TPV Virtual (solo aplicable en la opción de sincronización SOAP)";
		    break;
		case "9914":
		    $sDesError			=	"Confirmación “KO” del comercio (solo aplicable en la opción de sincronización SOAP)";
		    break;
		case "9915":
		    $sDesError			=	"A petición del usuario se ha cancelado el pago";
		    break;
		case "9928":
		    $sDesError			=	"Anulación de autorización en diferido realizada por el SIS (proceso batch)";
		    break;
		case "9929":
		    $sDesError			=	"Anulación de autorización en diferido realizada por el comercio";
		    break;
		case "9997":
		    $sDesError			=	"Se está procesando otra transacción en SIS con la misma tarjeta";
		    break;
		case "9998":
		    $sDesError			=	"Operación en proceso de solicitud de datos de tarjeta";
		    break;
		case "9999":
		    $sDesError			=	"Operación que ha sido redirigida al emisor a autenticar";
		    break;
		default:
			$sDesError			=	"Transacción denegada";
			break;
	} // end switch
    $kc="";
    
    if (!empty($sCodError) && !empty($sIdTerminal) &&
		!empty($sIdComercio) && !empty($sIdTransaccion) &&
		!empty($sMoneda) && !empty($sImporte) &&
		!empty($sFirma) && !empty($sTK))
	{
		$cCandidatos_pagos_tpv->setLocalizador($sIdTransaccion);
		$cCandidatos_pagos_tpv = $cCandidatos_pagos_tpvDB->readEntidadLocalizador($cCandidatos_pagos_tpv);
		if ($cCandidatos_pagos_tpv->getIdRecarga() != "")
		{	//Encontrado, comprobamos la firma
			
			$cEmpresasDB	= new EmpresasDB($conn);
			$cEmpresas	= new Empresas();
	        $cEmpresas->setIdEmpresa($cCandidatos_pagos_tpv->getIdEmpresa());
	        $cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
	        if ($cEmpresas->getSrvTPV() == "1"){
	        	$_sTipoTpv = $cEmpresas->getIdTipoTpv();
	        							
	        	$cTipos_tpvDB	= new Tipos_tpvDB($conn);
	        	$cTipos_tpv	= new Tipos_tpv();
	        	$cTipos_tpv->setIdTipoTpv($_sTipoTpv);
	        	$cTipos_tpv = $cTipos_tpvDB->readEntidad($cTipos_tpv);
				if (empty($_sTipoTpv)){
					$strMensaje = "Code:FD_TTPV0400 - Ha ocurrido un error al contactar con la pasarela del banco. Contacte con " . $cEmpresas->getNombre();
					error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> " . $strMensaje . ":\t" . $req . "\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
					exit;
				}else{
					//Miramos los datos de configuración del TPV
					$cEmpresas_conf_tpvDB	= new Empresas_conf_tpvDB($conn);
	    			$cEmpresas_conf_tpv	= new Empresas_conf_tpv();
    				$cEmpresas_conf_tpv->setIdEmpresa($cCandidatos_pagos_tpv->getIdEmpresa());
					$cEmpresas_conf_tpv->setIdTipoTpv($_sTipoTpv);
					$cEmpresas_conf_tpv = $cEmpresas_conf_tpvDB->readEntidad($cEmpresas_conf_tpv);
					if ($cEmpresas_conf_tpv->getBUSINESS_CODE() == ""){
						$strMensaje = "Code:FD_BCODE0400 - Ha ocurrido un error al contactar con la pasarela del banco. Contacte con " . $cEmpresas->getNombre();
						error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> " . $strMensaje . ":\t" . $req . "\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
						exit;
					}
					$kc= $cEmpresas_conf_tpv->getBUSINESS_PASS();
				}
			}else{
				//La empresa ha desactivado el servicio, Por lo que no se lanzará la pasarela.
				error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> La empresa ha desactivado el servicio de TPV:\t" . $req . "\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
				exit;
			}
			
			/*
			Digest=SHA-1(
			-Ds_ Amount +
			-Ds_ Order + 
			-Ds_MerchantCode + 
			-Ds_ Currency + 
			-Ds_Response + 
			-SECRET CODE)
			*/
			$importeTotal = $cCandidatos_pagos_tpv->getImpBaseImpuestos(); 
			$importedosdec = number_format($importeTotal, 2, '.', '');
			$importe_formateado=str_replace(',','',str_replace('.','',$importedosdec));
							
			//$sMessage = $importe_formateado . $cCandidatos_pagos_tpv->getLocalizador() . constant("SERVIRED_BUSINESS_COD") . constant("SERVIRED_MONEDA") . $sCodError . constant("SERVIRED_BUSINESS_PASS");
			//$sSignature = strtoupper(sha1($sMessage));
			$sSignature = $miObj->createMerchantSignatureNotif($kc, $params);
						
			if ($sSignature === $sFirma){
				//La operación parece correcta, modificamos el estado
				$cCandidatos_pagos_tpv->setCodEstado("OK");
				$cCandidatos_pagos_tpv->setCodAutorizacion($sCodAutorizacion);
				$cCandidatos_pagos_tpv->setCodError($sCodError);
				$cCandidatos_pagos_tpv->setDesError($sDesError);
				if (!$cCandidatos_pagos_tpvDB->modificar($cCandidatos_pagos_tpv)){
					//Errror Cambiando el estado de una operacion OK
					error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> Error cambiando el estado de una operación OK:\n" . $req . " [descError::" . $sDesError . "]\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
				}else{
					//Contabilizamos el importe si el codigo de error es 0000
					if (intval($sCodError) >= 0 && intval($sCodError) <= 99)
					{
						error_log(date('d/m/Y H:i:s') . " ¡OPERACIÓN CORRECTA!\t[codError::" . $sCodError . "]:\t" . $req . " [descError::" . $sDesError . "]\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
						// Marcamos al candidato como que ha pagado
						// Le metemos el localizador
						$cEntidadCandidatos->setLocalizadorTpv($sIdTransaccion);
						if (!$cEntidadCandidatosDB->modificarPagado($cEntidadCandidatos)){
							//Errror Cambiando el localizador
							error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> Error cambiando el localizador :\n" . $req . " [descError::" . $sDesError . "]\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));							
						}
					}
				}
			}else{
				//Errror en la firma de la operación
				error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> Firmas Distintas:\n" . $req . " [descError::" . $sDesError . "][CALC:" . $sSignature . " != POST:" . $sFirma . "]\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
			}
		}else{
			//Errror en el localizador
			error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> LOCALIZADOR NO ENCONTRADO:\t" . $req . " [descError::" . $sDesError . "]\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));			
		}
	}else{
		//FALTAN DATOS
		error_log(date('d/m/Y H:i:s') . " ¡ATENCIÓN! -> Datos Incompletos:\t" . $req . " [descError::" . $sDesError . "]\n", 3, constant("DIR_FS_PATH_NAME_LOG_TPV"));
	}
?>