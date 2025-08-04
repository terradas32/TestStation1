<?php
	include 'Zend/Soap/Client.php';
//	define("HTTP_SERVER_WSDL", "http://testadmisionesade.com/SOAP/TS/servicio.php?wsdl");
//	define("HTTP_SERVER_WSDL", "http://localhost/teststation/SOAP/TS/servicio.php?wsdl");
    require_once("../../Empresa/include/Configuracion.php"); 
    define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/TS/servicio.php?wsdl");
	
	try {
		$client = new Zend_Soap_Client(constant("HTTP_SERVER_WSDL"));

			$sesion = $client->login('ROBERTO', 'A78301934');
//		$sesion = $client->login('Esade', 'He1Bd3s4d3');
		
		$idConvocatoria = 15;
		$iCandidatoPE= 3;
		$aPruebas = array(34,35,36,37);
//		$idPrueba= 34;
//		$idPrueba= 35;
//		$idPrueba= 36;
//		$idPrueba= 37;

		// Pruebas TS y sus códigos
		// 34 --> Cálculo Numérico
		// 35 --> Razonamiento Verbal
		// 36 --> Suficiencia de Información
		// 37 --> Razonamiento Lógico
//		echo "<br />";
//		echo "<br />***************** insertarConvocatoria ***************";
//		$idConvocatoria = $client->insertarConvocatoria($sesion, "convocatoria", "2012-05-20 21:22", "2012-05-22 20:00", "34,35,36", "en,es,es");
//		echo "<br />" . $idConvocatoria ;
//		echo "<br />";
//		echo "<br />***************** FIN insertarConvocatoria ***************";
//
//		echo "<br />";
//		echo "<br />***************** modificarConvocatoria ***************";
//		$retorno = $client->modificarConvocatoria($sesion, $idConvocatoria, "convocatoria TS " . $idConvocatoria, "2012-08-14 08:00", "2012-09-22 20:00", "34,35,36", "es,es,es");
//		echo "<br />" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN modificarConvocatoria ***************";
//		
//		$sNombre = "Ignacio";
//		$sApellido1 ="Chavarria"; 
//		$sApellido2 ="";
//		$sLogin = "46830188A";
//		$Pass = "Ignacio";
//		$sDNI = "46830188A";
//		$sMail= "mail@mail.es";
//		$sCodCandidatoTS = "C_PR_46830188A";
//		$IdPrograma = 1;
//		//Tratamientos
//		//	1 => 'Sr.'
//		//	2 => 'Sra.'
//		$idTratamiento = 1;
//
//		echo "<br />";
//		echo "<br />***************** AltaCandidatoConv ***************";
//		$retorno = $client->AltaCandidatoConv($sesion, $idConvocatoria, $sNombre, $sApellido1, $sApellido2, $sLogin, $Pass, $sDNI, $sMail, $sCodCandidatoTS, $IdPrograma, $idTratamiento);
//		echo "<br />" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN AltaCandidatoConv ***************";
//
//		echo "<br />";
//		echo "<br />***************** listarCandidatos ***************";
//		$aCandidatos = $client->listarCandidatos($sesion, $idConvocatoria);
//		echo "<br />";
//		print_r($aCandidatos);
//		echo "<br />";
//		echo "<br />***************** FIN listarCandidatos ***************";
//		
//		
//		echo "<br />";
//		echo "<br />***************** ModificarCandidatoConv ***************";
//		$retorno = $client->ModificarCandidatoConv($sesion, $idConvocatoria, "", "", "", "", "PASWORD----", "", "", $sCodCandidatoTS, "", "");
//		echo "<br />::" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN ModificarCandidatoConv ***************";
//		
//		echo "<br />";
//		echo "<br />***************** listarCandidatos ***************";
//		$aCandidatos = $client->listarCandidatos($sesion, $idConvocatoria);
//		echo "<br />";
//		print_r($aCandidatos);
//		echo "<br />";
//		echo "<br />***************** FIN listarCandidatos ***************";
//		
//		echo "<br />";
//		echo "<br />***************** listarPuntuaciones ***************";
//		$aPuntuaciones = array();
//		foreach($aCandidatos as $key => $value )
//		{
//			$aPuntuaciones = $client->listarPuntuaciones($sesion, $aCandidatos[$key]['idProceso'], $idPrueba, $aCandidatos[$key]['codPostal']);
//		}
//		print_r($aPuntuaciones);
//		echo "<br />";
//		echo "<br />***************** FIN listarPuntuaciones ***************";
//		
//		echo "<br />";
//		echo "<br />***************** BorrarCandidatoConv ***************";
//		$retorno = $client->BorrarCandidatoConv($sesion, $idConvocatoria, $sCodCandidatoTS);
//		echo "<br />" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN BorrarCandidatoConv ***************";
//
//		echo "<br />";
//		echo "<br />***************** borrarConvocatoria ***************";
//		$retorno = $client->borrarConvocatoria($sesion, $idConvocatoria);
//		echo "<br />" . $retorno ;
//		echo "<br />";
//		echo "<br />***************** FIN borrarConvocatoria ***************";

		foreach($aPruebas as $key => $value )
		{
			echo "<br />PRUEBA:::::::: " . $value;
			echo "<br />";
			echo "<br />";
			$aPuntuaciones = $client->listarPuntuaciones($sesion, $idConvocatoria, $value, "PSICOLOGOS_01");
			print_r($aPuntuaciones);
		}


	} catch (SoapFault $s) {
		die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
	} catch (Exception $e) {
  		die('ERROR: ' . $e->getMessage());
	}
?>		
<?php
//	$client = new SoapClient(null, array('uri' => 'http://localhost/', 
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