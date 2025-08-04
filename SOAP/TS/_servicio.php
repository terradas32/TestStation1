<?php
  include 'Zend/Soap/AutoDiscover.php';
  include 'Zend/Soap/Server.php';
  include 'TS_Service.php';
//	define("HTTP_SERVER_WSDL", "http://testadmisionesade.com/SOAP/TS/servicio.php?wsdl");
//	define("HTTP_SERVER_WSDL", "http://localhost/teststation/SOAP/TS/servicio.php?wsdl");
	require_once("../../Empresa/include/Configuracion.php"); 
	define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/TS/servicio.php?wsdl");
	
  if(isset($_GET['wsdl'])) 
  {
    $autodiscover = new Zend_Soap_AutoDiscover();
    $autodiscover->setClass('TS_Service');
    $autodiscover->handle();
  } 
  else 
  {
    $soap = new Zend_Soap_Server(constant("HTTP_SERVER_WSDL"));
    $soap->setClass('TS_Service');
    $soap->handle();
  }

?>
<?php  
//	include 'TS_Service.php';  
//  
//	$soap = new SoapServer(null, array('uri' => 'http://localhost/'));  
//	$soap->setClass('TS_Service');  
//	$soap->handle();  
?>  
