<?php

// servicio.php

require_once __DIR__ . '/vendor/autoload.php';

require_once("../../Empresa/include/Configuracion.php");

// Se incluye la clase a generar
require_once('WS_Service.php');

define("HTTP_SERVER_URL", constant("HTTP_SERVER_FRONT") . "SOAP/WS/servicio.php");
$serviceNs    = 'https://ts-ssff.expertos-pe-online.com/'; 
$serverUrl = constant("HTTP_SERVER_URL");

$options = [
    'uri' => $serverUrl,
];
$server = new \Laminas\Soap\Server($serviceNs, $options);

if (isset($_GET['wsdl'])) {
    $soapAutoDiscover = new \Laminas\Soap\AutoDiscover(new \Laminas\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence());


    $soapAutoDiscover->setBindingStyle(array('style' => 'document'));
    //$soapAutoDiscover->setBindingStyle(array('style' => 'document', 'transport' => 'https://testadmisionesade.com/SOAP/WS/'));
    //$soapAutoDiscover->setOperationBodyStyle(array('use' => 'literal'));
    $soapAutoDiscover->setOperationBodyStyle(['use' => 'literal', 
                                                'targetNamespace' => $serviceNs, 
                                                'namespace' => $serviceNs],);
    $soapAutoDiscover->setClass('WS_Service', $serviceNs);
    $soapAutoDiscover->setUri($serverUrl);
    //$soapAutoDiscover->setServiceName('ws_esade');
    
    header("Content-Type: text/xml");
    $wsdl= $soapAutoDiscover->generate()->toXml();
    //$wsdl= str_replace('targetNamespace="' . constant("HTTP_SERVER_FRONT"),'targetNamespace="' . $serviceNs, $wsdl);
    $wsdl= str_replace(constant("HTTP_SERVER_FRONT"), $serviceNs, $wsdl);
    $wsdl= str_replace('location="' . $serviceNs,'location="' . constant("HTTP_SERVER_FRONT"), $wsdl);
    

    echo $wsdl;
} else {
    $soap = new \Laminas\Soap\Server($serverUrl . '?wsdl');
    $soap->setObject(new \Laminas\Soap\Server\DocumentLiteralWrapper(new WS_Service()));
    $soap->handle();
}
?>
