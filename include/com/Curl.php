<?php
class Curl
{
	/**
	* Declaración de las variables de Entidad.
	**/
	var $contenido		= "";
	var $nombreFichero	= "";
	
	/**
	* Constructor q inicializa los datos de CURL
	* @param sUrl			Url de internet
	**/
	function __construct($sUrl)
	{
		$this->nombreFichero = "";
		$this->contenido = "";
		if (!function_exists('curl_init')) {
			echo "NO ESTÁ INTALADA LA LIBRERIA <b>CURL.</b>"; exit;
		}
		$options = array(
        CURLOPT_RETURNTRANSFER => true,         // return web page
        CURLOPT_HEADER         => false,        // don't return headers
        CURLOPT_FOLLOWLOCATION => true,         // follow redirects
        CURLOPT_ENCODING       => "",           // handle all encodings
        CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],     // who am i
        CURLOPT_AUTOREFERER    => true,         // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
        CURLOPT_TIMEOUT        => 120,          // timeout on response
        CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
        CURLOPT_POST            => 1,            // i am sending post data
        CURLOPT_POSTFIELDS     => "",    // this are my post vars
        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
        CURLOPT_SSL_VERIFYPEER => false,        //
        CURLOPT_VERBOSE        => 1                //
    	);
		$ch = curl_init($sUrl); // empleamos la librería cURL para descargar la página
		curl_setopt_array($ch,$options);
		$contenido = curl_exec($ch); // descarga de la página web a la variable
		curl_close($ch);
		$this->contenido = $contenido;
		$path=explode("?",$sUrl);
		$this->nombreFichero=basename($path[0]);
	}

	/**
	* Devuelve el contenido de la URL
	* @return sDatos	String q contiene los datos
	**/	
	function getSRC(){
		return $this->contenido;
	}
	/**
	* Devuelve el nombre del fichero de la URL
	* @return sDatos	String q contiene los datos
	**/	
	function getNombreFichero(){
		return $this->nombreFichero;
	}
}//Fin de la clase ?>
