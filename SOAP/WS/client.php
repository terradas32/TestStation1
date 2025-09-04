<?php

 //ini_set('soap.wsdl_cache_enabled', '0');
 ini_set('soap.wsdl_cache_ttl', '0'); 

require_once __DIR__ . '/vendor/autoload.php';

require_once("../../Empresa/include/Configuracion.php");
define("HTTP_SERVER_WSDL", constant("HTTP_SERVER_FRONT") . "SOAP/WS/servicio.php?wsdl");

try {
  $client = new Laminas\Soap\Client(constant("HTTP_SERVER_WSDL"));



echo "<br /> INIT LOGIN <br />";

  $aParams = ["usuario" => "DIRHU_ADMIN",
              "password" => "H31BdT3Ch1N"];
  $result = $client->login($aParams);
  //var_dump($result);
  $sesion= $result->loginResult;
  echo "<br />";
  echo $sesion;
  echo "<br />";
echo "<br /> FIN LOGIN <br />";

  echo "<br />";
  echo "<br />";
echo "<br />***************** listar PRUEBAS ***************";
  $aParams = ["sesion" => $sesion,
              "idioma" => "es"];
  
  $result = $client->listarPruebas($aParams);
  //var_dump($result);
  $aPruebas= $result->listarPruebasResult;
  echo "<pre>";
    print_r($aPruebas);
  echo "</pre>";
  echo "<br />";
echo "<br />***************** FIN listar PRUEBAS ***************";

  echo "<br />";
  echo "<br />";
echo "<br />***************** listar PRUEBAS CONVOCATORIA ***************";
  $aParams = ["sesion" => $sesion,
              "idConvocatoria" => "1"];
  $result = $client->listarPruebasConvocatoria($aParams);
  //var_dump($result);
  $aPruebasConvocatoria= $result->listarPruebasConvocatoriaResult;
  echo "<pre>";
    print_r($PruebasConvocatoria);
  echo "</pre>";
  echo "<br />";
echo "<br />***************** FIN listar PRUEBAS CONVOCATORIA ***************";

  echo "<br />";
  echo "<br />";
echo "<br />***************** insertar CONVOCATORIA ***************";
  $aParams = ["sesion" => $sesion,
              "nombre" => "NIPS es, VIPS es, ELT en, PRISMA es (" .  date("d-m-Y H:i:s") . ")",
              "fecInicio" => "2022-11-28 09:10",
              "fecFin" => "2022-11-30 13:00",
              "test" => "16,26,8,24",
              "langtest" => "es,es,en,es",
              "iModoRealizacion" => "1"];	//1 - Continuo (envia los datos de acceso al candidato), 2 - Administrado (envia los datos de acceso al correo de la empresa)
  $result = $client->insertarConvocatoria($aParams);
  //var_dump($result);
  $idConvocatoria= $result->insertarConvocatoriaResult;
  echo "<br />";
  echo($idConvocatoria);
  echo "<br />";
echo "<br />Convocatoria:: " . $idConvocatoria ;
echo "<br />";
echo "<br />***************** FIN insertar CONVOCATORIA ***************";

} catch (SoapFault $s) {
  die('ERROR SoapFault: [' . $s->faultcode . '] ' . $s->faultstring);
} catch (Exception $e) {
    die('ERROR Exception: ' . $e->getMessage());
}
?>
