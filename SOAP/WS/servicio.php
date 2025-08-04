<?php
	include 'vendor/autoload.php';


	require_once("../../Empresa/include/Configuracion.php");
	define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/WS/servicio.php?wsdl");
	define("HTTP_SERVER_URL", constant("HTTP_SERVER_FRONT") . "SOAP/WS/servicio.php");

    // Se incluye la clase a generar
    require_once('WS_Service.php');

    // Si en la url está presente la entrada ?wsdl
    if (strtolower($_SERVER['QUERY_STRING']) == "wsdl")
    {
        // Se incluye la clase AutoDiscover que es la encargada de generar en forma automática el WSDL

        require_once('vendor/zendframework/zend-uri/src/Uri.php');
        require_once('vendor/zendframework/zend-soap/src/AutoDiscover.php');
        require_once('vendor/zendframework/zend-soap/src/Wsdl/ComplexTypeStrategy/ArrayOfTypeComplex.php');

        $autodiscover = new Zend\Soap\AutoDiscover();
        $autodiscover->setComplexTypeStrategy(new \Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex());
        $autodiscover->setOperationBodyStyle(
            array('use' => 'literal',
            'namespace' => 'http://framework.zend.com')
        );

        $autodiscover->setClass('WS_Service');
        $autodiscover->setUri(constant("HTTP_SERVER_URL"));
        // header("Content-type: text/xml");
        // echo $autodiscover->toXML();
        $autodiscover->handle();
    }
    // Si no
    else
    {
        require_once('vendor/zendframework/zend-soap/src/Server.php');
        $wsdl_url = sprintf('http://%s%s?wsdl', $_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME']);
        $server = new \Zend\Soap\Server($wsdl_url);

        // $server->setObject(new \Zend\Soap\Server\DocumentLiteralWrapper(new WS_Service()));
        // $server->handle();

		    $server->setClass('WS_Service');
		    $server->handle();
    }
?>
