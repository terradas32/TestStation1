<?php

	include 'vendor/autoload.php';
	//include 'vendor/zendframework/zend-soap/src/Client.php';

	require_once("../../Empresa/include/Configuracion.php");
    define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/TS/servicio.php?wsdl");
		define("HTTP_SERVICE_SOAP", constant("HTTP_SERVER_FRONT") . "SOAP/TS/servicio.php");

    $sesion = "";
    $idConvocatoria = "106";
	try {
		$options = array();
    $options['location'] = constant("HTTP_SERVICE_SOAP");
    $options['uri'] = constant("HTTP_SERVICE_SOAP");
    $options['cache_wsdl'] = WSDL_CACHE_NONE;
    $options['soap_version'] = SOAP_1_2;
    //$client = new Zend\Soap\Client("http://192.168.3.26/PT/PTsvc.php?wsdl", $options);
		$client = new Zend\Soap\Client(constant("HTTP_SERVER_WSDL"), $options);

		echo "<br />";
		echo "<br />***************** LOGIN ***************";
		//$sesion = $client->login('PEASA_DEMOS', 'PEASA_DEMOS');
		$sesion = $client->login('SOAP-PE', '#H31BdS04P@');
		print_r($sesion);exit;
		echo "<br />" . $sesion ;
		echo "<br />";
		echo "<br />***************** FIN LOGIN ***************";

		echo "<br />";
		echo "<br />***************** listar PRUEBAS ***************";
		$aPruebas = $client->listarPruebas($sesion, 'es');
		echo "<br />";
		print_r($aPruebas);
		echo "<br />";
		echo "<br />***************** FIN listar PRUEBAS ***************";

		echo "<br />";
		echo "<br />***************** listar PRUEBAS CONVOCATORIA ***************";
		$aPruebas = $client->listarPruebasConvocatoria($sesion, $idConvocatoria);
		echo "<br />";
		print_r($aPruebas);
		echo "<br />";
		echo "<br />***************** FIN listar PRUEBAS CONVOCATORIA ***************";
//		echo "<br />";
//		echo "<br />***************** insertar CONVOCATORIA ***************";
//		$sFecHoraInicio	= "2015-05-20 21:22";
//		$sFecHoraFin		= "2015-12-31 20:00";
//		$sNombreConvocatoria = "NIPS es, VIPS es, ELT en, PRISMA es (" .  date('d-m-Y H:i:s') . ")";
//		$sPruebas = "16,26,8,24";
//		$sCodIso2Pruebas = "es,es,en,es";
//		$iModoRealizacion = 1;	//1 - Continuo (envia los datos de acceso al candidato), 2 - Administrado (envia los datos de acceso al correo de la empresa)
//		$idConvocatoria = $client->insertarConvocatoria($sesion, $sNombreConvocatoria, $sFecHoraInicio, $sFecHoraFin, $sPruebas, $sCodIso2Pruebas, $iModoRealizacion);
//		echo "<br />Convocatoria:: " . $idConvocatoria ;
//		echo "<br />";
//		echo "<br />***************** FIN insertar CONVOCATORIA ***************";

//		echo "<br />";
//		echo "<br />***************** modificarConvocatoria ***************";
//		$sNombreConvocatoria .= " (" .  date('d-m-Y H:i:s') . ") - " . $idConvocatoria;
//		echo "<br />" . $sNombreConvocatoria ;
//		$sFecHoraInicio	= "2015-05-20 08:05";
//		echo "<br />" . $sFecHoraInicio ;
//		$sFecHoraFin		= "2015-12-31 18:01";
//		echo "<br />" . $sFecHoraFin ;
//		$sPruebas = "26";
//		echo "<br />" . $sPruebas ;
//		$sCodIso2Pruebas = "en";
//		echo "<br />" . $sCodIso2Pruebas ;
//		$retorno = $client->modificarConvocatoria($sesion, $idConvocatoria, $sNombreConvocatoria, $sFecHoraInicio, $sFecHoraFin, $sPruebas, $sCodIso2Pruebas);
//		echo "<br />retorno ::" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN modificarConvocatoria ***************";

		$sNombre = "Pedro";
		$sApellido1 ="Borregán";
		$sApellido2 ="Merino";
		$sDNI = "50960898M";
		$sMail= "pborregan@yahoo.es";
		//Tratamientos
		//	1 => 'Sr.'
		//	2 => 'Sra.'
		$idTratamiento = 1;

//		echo "<br />";
//		echo "<br />***************** AltaCandidatoConv ***************";
//		$idCandidato = $client->AltaCandidatoConv($sesion, $idConvocatoria, $sNombre, $sMail, $sApellido1, $sApellido2, $sDNI, $idTratamiento);
//		echo "<br />Candidato:: " . $idCandidato ;
//		echo "<br />";
//		echo "<br />***************** FIN AltaCandidatoConv ***************";

//		$sNombre = "Susana";
//		$sApellido1 ="Burgos";
//		$sApellido2 ="Medina";
//		$sDNI = "46830188A";
//		$sMail= "sburgos@yahoo.es";
//		//Tratamientos
//		//	1 => 'Sr.'
//		//	2 => 'Sra.'
//		$idTratamiento = 2;
$idCandidato=1;
		echo "<br />";
		echo "<br />***************** ModificarCandidatoConv ***************";
		$retorno = $client->ModificarCandidatoConv($sesion, $idConvocatoria, $idCandidato, $sNombre, $sMail, $sApellido1, $sApellido2, $sDNI, $idTratamiento, 'Pedro');
		echo "<br />::" . $retorno ;
		echo "<br />";
		echo "<br />***************** FIN ModificarCandidatoConv ***************";


		echo "<br />";
		echo "<br />***************** listarCandidatos ***************";
		$aCandidatos = $client->listarCandidatos($sesion, $idConvocatoria);
		echo "<br />";
		print_r($aCandidatos);
		echo "<br />";
		echo "<br />***************** FIN listarCandidatos ***************";

		$idCandidato = 1;
//		echo "<br />Candidato:: " . $idCandidato ;
//		echo "<br />";
//		echo "<br />***************** informarCandidatos ***************";
//		$sResumen = $client->informarCandidatos($sesion, $idConvocatoria, $idCandidato);
//		echo "<br />";
//		echo "<br />" . $sResumen;
//		echo "<br />";
//		echo "<br />***************** FIN informarCandidatos ***************";

		echo "<br />";
		echo "<br />***************** listarPuntuaciones ***************";
		$idPrueba = 16;
		$aPuntuaciones = array();
		foreach($aCandidatos as $key => $value )
		{
			$aPuntuaciones = $client->listarPuntuaciones($sesion, $aCandidatos[$key]['idProceso'], $idPrueba, $aCandidatos[$key]['idCandidato']);
		}
		print_r($aPuntuaciones);
		echo "<br />";
		echo "<br />***************** FIN listarPuntuaciones ***************";

//		echo "<br />";
//		echo "<br />***************** BorrarCandidatoConv ***************";
//		foreach($aCandidatos as $key => $value )
//		{
//			$retorno = $client->BorrarCandidatoConv($sesion, $aCandidatos[$key]['idProceso'], $aCandidatos[$key]['idCandidato']);
//			echo "<br />Candidato::" . $aCandidatos[$key]['idCandidato'] . " -> " . $retorno ;
//		}
//		echo "<br />";
//		echo "<br />***************** FIN BorrarCandidatoConv ***************";
//
//		echo "<br />";
//		echo "<br />***************** borrarConvocatoria ***************";
//		$retorno = $client->borrarConvocatoria($sesion, $idConvocatoria);
//		echo "<br />" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN borrarConvocatoria ***************";

//		foreach($aCandidatos as $key => $value )
//		{
////			echo "<br />" . $aCandidatos[$key]['codPostal'];
//			$aRespuestas = $client->listarRespuestas($sesion, $aCandidatos[$key]['idProceso'], $idPrueba, $aCandidatos[$key]['codPostal']);
//		}
//		print_r($aRespuestas);

	} catch (SoapFault $s) {
		die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
	} catch (Exception $e) {
  		die('ERROR: ' . $e->getMessage());
	}
?>
<?php
//	$client = new Zend\Soap\Client(null, array('uri' => 'http://localhost/',
//										'location' => 'http://localhost/teststation/SOAP/TS/servicio.php'));
//	$sesion = $client->login('Negocia', 'Negocia');
//	echo "<br />" . $sesion ;
//	echo "<br />" . md5("Pedrito");
//
//	$idConvocatoria = 23;
//	$idPrueba= 34;
//
//	$aCandidatos = $client->listarCandidatos($sesion, $idConvocatoria);
//	echo "<br />";
////	print_r($aCandidatos);
//
////		// Pruebas TS y sus códigos
////		// 34 --> Cálculo Numérico
////		// 35 --> Razonamiento Verbal
////		// 36 --> Suficiencia de Información
////		// 37 --> Razonamiento Lógico
//
////	$idConvocatoria = $client->insertarConvocatoria($sesion, "convocatoria", "2012-05-20 21:22", "2012-05-22 20:00", "34,35,36");
////	echo "<br />" . $idConvocatoria ;
//
//
////	$retorno = $client->modificarConvocatoria($sesion, $idConvocatoria, "convocatoria TS " . $idConvocatoria, "2012-08-14 08:00", "2012-09-22 20:00");
////		echo "<br />" . $retorno ;
//	$sNombre = "Ignacio";
//	$sApellido1 ="Chavarria";
//	$sApellido2 ="";
//	$sLogin = "46830188A";
//	$Pass = "Ignacio";
//	$sDNI = "46830188A";
//	$sMail= "mail@mail.es";
//	$sCodCandidatoTS = "C_PR_46830188A";
//	$IdPrograma = 1;
////	$retorno = $client->AltaCandidatoConv($sesion, $idConvocatoria, $sNombre, $sApellido1, $sApellido2, $sLogin, $Pass, $sDNI, $sMail, $sCodCandidatoTS, $IdPrograma);
////	echo "<br />" . $retorno ;
//
////	$retorno = $client->borrarConvocatoria($sesion, $idConvocatoria);
////	echo "<br />" . $retorno ;
//
//		foreach($aCandidatos as $key => $value )
//		{
//				$aRespuestas = $client->listarRespuestas($sesion, $aCandidatos[$key]['idProceso'], $idPrueba, $aCandidatos[$key]['codPostal']);
////				print_r($aRespuestas);
////				echo "<br />----------------------------------------------<br />";
//		}
//	print_r($aRespuestas);
//
////		foreach($aCandidatos as $key => $value )
////		{
////			echo "<br />" . $aCandidatos[$key]['codPostal'];
////				$aPuntuaciones = $client->listarPuntuaciones($sesion, $aCandidatos[$key]['idProceso'], $idPrueba, $aCandidatos[$key]['codPostal']);
////		}
////	print_r($aPuntuaciones);
//
//
?>
