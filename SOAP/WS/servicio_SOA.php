<?php
	include 'vendor/autoload.php';


	require_once("../../Empresa/include/Configuracion.php");
	define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/WS/servicio_schedule.php?wsdl");
	define("HTTP_SERVER_URL", constant("HTTP_SERVER_FRONT") . "SOAP/WS/servicio_schedule.php");

    // Se incluye la clase a generar
    require_once('servicio_schedule.php');

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

        $autodiscover->setClass('Schedule');
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

        $server->setObject(new \Zend\Soap\Server\DocumentLiteralWrapper(new Schedule()));
        $server->handle();


        // $server->setClass('Schedule');
        // // Primero añadimos la etiqueta de que es un XML
        // $xml = '<?xml version="1.0" encoding="UTF-8"?'.'>'.file_get_contents("php://input");
        // // Eliminamos los caracteres especiales
        // $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        // // Lo convertimos a XML
        // $xml = new SimplexmlElement($xml, TRUE);
        // // Y lo recorremos
        // foreach ($xml->soapenvBody as $body){
        //     foreach ($body->framsetMeeting as $setMeeting){
        //         // Esto gurada la hora en una variable global accesible desde la clase PHP del servicio
        //             $GLOBALS['time'] = $setMeeting->attributes()->time;
        //     }
        // }
        // $server->handle();

    }
?>
