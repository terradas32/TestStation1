<?php
	include 'vendor/autoload.php';
  include 'vendor/zendframework/zend-soap/src/AutoDiscover.php';
  include 'vendor/zendframework/zend-soap/src/Server.php';
  include 'TS_Service.php';

	require_once("../../Empresa/include/Configuracion.php");
	define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/TS/servicio.php?wsdl");
	define("HTTP_SERVER_URL", constant("HTTP_SERVER_FRONT") . "SOAP/TS/servicio.php");

  if(isset($_GET['wsdl']))
  {
    //ini_set("soap.wsdl_cache_enabled", "0");
    // $autodiscover = new Zend\Soap\AutoDiscover(new Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence());
    // $autodiscover->setClass('TS_Service');
    // $autodiscover->setUri(constant("HTTP_SERVER_WSDL"));
    // $autodiscover->handle();

////////////////////////////////////////////////////
$autodiscover = new Zend\Soap\AutoDiscover(/*new \Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence()*/);
 //$autodiscover->setBindingStyle(array('style' => 'document'));
 //$autodiscover->setOperationBodyStyle(array('use' => 'literal'));
 $autodiscover->setClass('TS_Service');
 $autodiscover->setUri(constant("HTTP_SERVER_URL"));

 //header("Content-Type: text/xml");
 header('Content-type: application/xml');
 echo $autodiscover->toXml();
////////////////////////////////////////////////////
    // $autodiscover = new Zend\Soap\AutoDiscover();
    // $autodiscover->setClass('TS_Service');
    // $autodiscover->handle();
  }
  else
  {
		//echo $_RE_REQUEST;exit;
    //$soap = new Zend\Soap\Server(constant("HTTP_SERVER_WSDL"));
		$soap = new \Zend\Soap\Server(constant("HTTP_SERVER_WSDL"), array('cache_wsdl' => WSDL_CACHE_NONE));
    $soap->setClass('TS_Service');
    $soap->handle();
  }

?>
