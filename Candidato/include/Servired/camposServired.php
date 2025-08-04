<div id="pago">
<?php 
	// Se incluye la librería
	include_once $_sPrefijo . 'apiRedsys.php';
	// Se crea Objeto
	$miObj = new RedsysAPI;
		
	// Valores de entrada
	//$fuc="999008881";
	$fuc=$cEmpresas_conf_tpv->getBUSINESS_CODE();
	//$terminal="871";
	$terminal=$cTipos_tpv->getTERMINAL_TYPE();
	//$moneda="978";
	$moneda=$cEmpresas_conf_tpv->getBUSINESS_COINC();
	//$trans="0";
	$trans=$cTipos_tpv->getOPERATION_TYPE();
	//$url="";
	$url=constant("HTTP_SERVER") . $cTipos_tpv->getURL_NOTIFY();
	$urlOK=$cTipos_tpv->getURL_OK();
	$urlKO="";

	//$order=date('ymdHis');	
	//$id=time();
	$order=date('ymdHis');
	$amount="";
	$importeTotal = (!empty($intentaRecargar)) ? $intentaRecargar : $cEntidad->getImpBaseImpuestos();
	$importedosdec = number_format($importeTotal, 2, '.', '');
	$importe_formateado=str_replace(',','',str_replace('.','',$importedosdec));
	$amount=$importe_formateado;
	$productDescription = constant("STR_TPV_CUESTIONARIOS");
	$merchantName = $cEmpresas_conf_tpv->getBUSINESS_NAME();
	$sCodLenguaje = "001";
	if ($sLang != "en"){
		$sCodLenguaje = "001";
	}else {
		$sCodLenguaje = "002";
	}
	$merchantData=$_cEntidadCandidatoTK->getToken();
	
	// Se Rellenan los campos
	$miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
	$miObj->setParameter("DS_MERCHANT_ORDER",strval($order));
	$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
	$miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
	$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
	$miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
	$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
	$miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);		
	$miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);
	
	//Opcionales
	$miObj->setParameter("DS_MERCHANT_MERCHANTNAME",$merchantName);
	$miObj->setParameter("DS_MERCHANT_PRODUCTDESCRIPTION",$productDescription);
	$miObj->setParameter("DS_MERCHANT_CONSUMERLANGUAGE",$sCodLenguaje);
	$miObj->setParameter("DS_MERCHANTDATA",$merchantData);
	$miObj->setParameter("DS_MERCHANT_MERCHANTDATA",$merchantData);

	//Datos de configuración
	$version="HMAC_SHA256_V1";
	//$kc = 'Mk9m98IfEblmPfrpsawt7BmxObt98Jev';//Clave recuperada de CANALES
	$kc= $cEmpresas_conf_tpv->getBUSINESS_PASS();
	// Se generan los parámetros de la petición
	$request = "";
	$params = $miObj->createMerchantParameters();
	$signature = $miObj->createMerchantSignature($kc);

	echo '<input type="hidden" name="item_number" value="' . $order . '" />';
	echo '<input type="hidden" name="item_name" value="' . $productDescription . '" />';
	echo '<input type="hidden" name="amount" value="' . $importeTotal . '" />';
	
	echo '<input type="hidden" name="Ds_SignatureVersion" value="' . $version . '" />';
	echo '<input type="hidden" name="Ds_MerchantParameters" value="' . $params . '" />';
	echo '<input type="hidden" name="Ds_Signature" value="' . $signature . '" />';
	
?>
									
</div>
	