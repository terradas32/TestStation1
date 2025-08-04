<?php
class IPLocalizador
{
     /**
     *
     * @param string $ip
     * @param string $key
     * @return IPLocalizadorData
     */
    static function getData($ip,$key)
    {
       if (!$ip) $ip = self::getRealIP();
 
 
        if (!$key)
        $key = "su llave";
        $response =   file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=$key&ip=$ip&format=raw");
 
       $part = split(";", $response);
       
       require_once(constant("DIR_WS_COM") . "Ipinfodb/IPLocalizadorData.php");
       $r = new IPLocalizadorData();
       $r->statusCode = $part[0];
       $r->ipAddress = $part[2];
       $r->countryCode = $part[3];
       $r->countryName = $part[4];
       $r->regionName = $part[5];
       $r->cityName = $part[6];
       $r->zipCode = $part[7];
       $r->latitude = $part[8];
       $r->longitude = $part[9];
       $r->timeZone = $part[10];
 
       return  $r; 
    }
 
	static  function getRealIP()
	{
		
	   if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
	   {
	      $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
	 
	      // los proxys van añadiendo al final de esta cabecera
	      // las direcciones ip que van "ocultando". Para localizar la ip real
	      // del usuario se comienza a mirar por el principio hasta encontrar
	      // una dirección ip que no sea del rango privado. En caso de no
	      // encontrarse ninguna se toma como valor el REMOTE_ADDR
	 
	      $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
	 
	      reset($entries);
	      while (list(, $entry) = each($entries))
	      {
	         $entry = trim($entry);
	         if ( preg_match("/^([0-9]+.[0-9]+.[0-9]+.[0-9]+)/", $entry, $ip_list) )
	         {
	            // http://www.faqs.org/rfcs/rfc1918.html
	            $private_ip = array(
	                  '/^0./',
	                  '/^127.0.0.1/',
	                  '/^192.168..*/',
	                  '/^172.((1[6-9])|(2[0-9])|(3[0-1]))..*/',
	                  '/^10..*/');
	 
	            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	 
	            if ($client_ip != $found_ip)
	            {
	               $client_ip = $found_ip;
	               break;
	            }
	         }
	      }
	   }else{
	      $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
	   }
	 
	   return $client_ip;
	 
	}
}	//Fin de la clase
?>