<?php
/**
 * Utilidades comunes
 */
class Utilidades
{
	/**********************************************
	* Caracteres no permitidos en login.
	* @param sCadena Cadena a chequear.
	* @return boolean true si lo encuentra en caso contrario false
	*********************************************/
	function chkChar($sCadena)
	{
		$aChars = array('\"','<','>','&','|','\'', '%', '?', '=', '/', '\\');
		$bRetorno = false;

		//miramos si la cadena tiene alguno de los caracteres para escapar
		for ($i = 0; $i < strlen($sCadena); $i++)
		{
			for ($j = 0; $j < count($aChars); $j++)
			{
				if (strpos($sCadena,  $aChars[$j]) === false)
				{
					$bRetorno = false;
				}else{
					$bRetorno = true;
                	break;
                }
			}
			if ($bRetorno)
				break;
		}
		return $bRetorno;
	}
    /**********************************************
	* Genera contraseñas nuevas.
	* @return String mínimo 10 Máximo 20 caracteres
	*********************************************/
	function newPass()
	{
	    $sNewPass = uniqid(md5(rand()), true);
        $iCorte = rand(10, 20);
        $sNewPass = substr($sNewPass, 0, $iCorte);
		return $sNewPass;
	}
	/**********************************************
	* Evita vulnerabilidades de inyección de código HTML/Javascript
	* evitando posibles ataques XSS
	* @param sCadena Cadena a convertir.
	* @return String Cadena convertida.
	*********************************************/
	function validaXSS($sCadena)
	{
        //$sCadena = htmlspecialchars($sCadena, ENT_QUOTES, "UTF-8");
		$sCadena = $this->fixArrayToString($sCadena);
		$sCadena = htmlentities($sCadena, ENT_QUOTES, "UTF-8");
        return $sCadena;
    }
    /**********************************************
	* Evita vulnerabilidades de inyección de código HTML/Javascript
	* evitando posibles ataques XSS
	* @param sCadena Cadena a convertir.
	* @return String Cadena convertida.
	*********************************************/
	function validaXSS_EDITOR($sCadena)
	{
		//$sCadena = htmlspecialchars($sCadena, ENT_QUOTES, "UTF-8");
		$sCadena = $this->fixArrayToString($sCadena);
		$sCadena = htmlentities($sCadena, ENT_QUOTES, "UTF-8");
        return $sCadena;
    }

    function delcaracteresp($sCadena)
    {
    	$sCadena = html_entity_decode($sCadena,ENT_QUOTES,"UTF-8");
    	$sCadena = preg_replace("/[^a-zA-Z0-9 áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛñ]/", "", $sCadena);
    	$sCadena = $this->quitar_espacios_extra($sCadena);
    	return $sCadena;
    }

    function quitar_espacios_extra($str)
	{
        //Utilizamos trim antes de empezar
        $str = trim($str);
        //Inicializamos el string que devolvemos
        $ret_str ="";
        //Recorremos el string
        for($i=0;$i < strlen($str);$i++) {
               /*Si estamos en algo que no es un espacio, seguimos copiando el
               string de entrada al de salida */
               if(substr($str, $i, 1) != " ") {
                       $ret_str .= trim(substr($str, $i, 1));
               } else {
                       /*Si es un espacio nos lo saltamos, aumentando el contador i del bucle*/
                       while(substr($str,$i,1) == " "){
                               $i++;
                       }
                       /* Dado que no queremos quitar todos los espacios, sino solo los repetidos
                       añadimos un espacio después de habernos
                       saltado un nº indeterminado de ellos SI nos saltamos uno, ponemos uno
                       SI nos saltamos 20, ponemos uno igual */
                       $ret_str.= " ";
                       $i--;
               }
        }
        return $ret_str;
	}

	/**********************************************
	* Convierte una cadena String sustituyendo acentos por letra sin acento.
	* @param sCadena Cadena a convertir.
	* @return String Cadena convertida.
	*********************************************/
	function cCambiaAcentos($sCadena)
	{
        $sChars = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','ä','ë','ï','ö','ü','Ä','Ë','Ï','Ö','Ü','à','è','ì','ò','ù','À','È','Ì','Ò','Ù','â','ê','î','ô','û','Â','Ê','Î','Ô','Û','º','ª','ñ');
		$sScapes = array('a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','o','a','n');
		for ($j = 0; $j < sizeof($sChars); $j++)
		{
			$sCadena = str_replace($sChars[$j], $sScapes[$j], $sCadena);
		}
			return ($sCadena);
	}
	/**********************************************
	* Retorna un array con las posiciones donde encuentra letras mayusculas.
	* @param sCadena Cadena a tratar.
	* @return String Cadena convertida.
	*********************************************/
	function posicion_letrasMAYS($sCadena) {
	    $u = 0;
	    $d = 0;
	    $n = strlen($sCadena);
	    $aPosicion = array();
	    $posicion="";
	    for ($x=0; $x<$n; $x++) {
	        $d = ord($sCadena[$x]);
	        if ($d > 64 && $d < 91) {
	            $posicion = "," . $x;
	            $u++;
	        }
	    }
	    if (!empty($posicion)){
	        $posicion = substr($posicion,1);
	        $aPosicion = explode(",", $posicion);
	    }
	    return (array)$aPosicion;
	}
	function ValidaMail($pMail) {
	    if (preg_match(	"/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/", $pMail ) ) {
	       return true;
	    } else {
	       return false;
	    }
	}
	/**********************************************
	* Retorna un String optimizado para la utilización SEO.
	* @param sCadena Cadena a tratar.
	* @return String Cadena convertida.
	*********************************************/
	function SEOTitulo($sCadena){
		return mb_strtolower((str_replace(" ","-",$this->delcaracteresp(strip_tags($sCadena)))),'UTF-8');
	}
	/**********************************************
	* Retorna un Booleano si esiste la url.
	* @param url Cadena de tipo url.
	* @return Boolean true encontrado false no encontrado.
	*********************************************/
	function verifica_url($url)
	{
		require_once(constant("DIR_WS_COM") . "Curl.php");
		$cEntidadFile= new Curl($url);
		$aHeaders = $cEntidadFile->getHeaders();
		if (preg_match("|200|", $aHeaders[0])) {
			return true;
		} else {
			return false;
		}
	}
	/**********************************************
	* Calcula el alto y ancho de una imagen con respectos a un ancho dado.
	* @param imagewidth Ancho de la imagen.
	* @param imageheight Alto de la imagen.
	* @param ancho Ancho a crear.
	* @return Array [0] --> Ancho.
	* 				[1] --> Alto.
	*********************************************/
	function thumb($imagewidth=150,$imageheight=150,$ancho)
	{
		$imagemaxwidth = ($ancho != "") ? $ancho : $imagewidth;
		$imagemaxheight = $imageheight;
		$imagemaxratio =  $imagemaxwidth / $imagemaxheight;
		$imageratio = $imagewidth / $imageheight;

		if ($imageratio > $imagemaxratio){
			$imageoutputwidth = $imagemaxwidth;
		    $imageoutputheight = ceil ($imagemaxwidth/$imagewidth*$imageheight);
		}else if ($imageratio < $imagemaxratio){
			$imageoutputheight = $imagemaxheight;
			$imageoutputwidth = ceil ($imagemaxheight/$imageheight*$imagewidth);
		}else{
			$imageoutputwidth = $imagemaxwidth;
			$imageoutputheight = $imagemaxheight;
		}
		$retorno[0] = $imageoutputwidth;
		$retorno[1] = $imageoutputheight;
		return $retorno;
	}

	/**
	*  Extrae archivos ZIP a un directorio especificado
	*
	*  @param String file El archivo ZIP a extraer (incluyendo el path) o el _FILES['campo']['tmp_name']
	*  @param String extractPath El path donde se extraera el archivo ZIP
	*  @return Array Con el nombre de los archivos extraidos
	*/
	function zipExtract($file, $extractPath)
	{
	    $files = array();
	    $zip = new ZipArchive;
	    $res = $zip->open($file);
	    if ($res === TRUE) {
			for($i = 0; $i < $zip->numFiles; $i++) {
				$files[] = $zip->getNameIndex($i);;
			}
	        $zip->extractTo($extractPath);
	        $zip->close();
	    }
	    return $files;
	}

    /**
    *  Devuelve una fecha formateada para imprimir
    *
    *  @param String fecha fecha en formato ingles
    *  @param String mask Mascara de representacion de la fecha por defecto ejemplo: (20 de mayo de 2011 a las 22:45)
    *  @return String Fecha formateada por la maskara
    */
    function PrintDate($fecha = "", $mask = "%d de %B de %Y a las %I:%M") {
        if ($fecha == ""){
              $fecha = time();
        }else{
              $fecha      =strtotime($fecha);
        }
        setlocale(LC_ALL, 'es-ES');
        $loc = setlocale(LC_TIME, NULL);
        $fecha = strftime($mask, $fecha);
        return $fecha;
    }

	/**
	*  Compara si la fecha actual está entre dos fechas.
	*
	*  @param String sql DATETIME fechaInicio
	*  @param String sql DATETIME fechaFin
	*  @return boolean True si la fecha sistema está entre las dos fechas,
	*/
	function isCurrent2Dates($fecInicio, $fecFin){
		$fecActual = time();
		$fecInicio = strtotime($fecInicio);
		$fecFin = strtotime($fecFin);
		if(($fecActual >= $fecInicio) && ($fecActual <= $fecFin)){
			return true;
		}else{
			return false;
		}
	}

	/*
	* Devuelve si el sistema operativo es Wundows
	*
	*  @return boolean True si es Windows
	*/
	function is_windows(){
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	        return true;
	    }
	    return false;
	}
	/*
	* Se ejecuta el cmd en background (sin ventana en windows)
	* sin esperar que el PHP termine, tanto en Windows como en Linux/Unix.
	*
	*  @param String cmd comando a jecutar en background
	*  @return void
	*/
    function execInBackground($cmd) {
        if ($this->is_windows()){
            pclose(popen("start /B ". $cmd, "r"));
        }else{
            exec($cmd . " > /dev/null &");
        }
    }

	function backgroundPost($url){
		$parts=parse_url($url);

		$fp = fsockopen($parts['host'],
		isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);

		if (!$fp) {
			return false;
		} else {
			$out = "POST ".$parts['path']." HTTP/1.1\r\n";
			$out.= "Host: ".$parts['host']."\r\n";
			$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out.= "Content-Length: ".strlen($parts['query'])."\r\n";
			$out.= "Connection: Close\r\n\r\n";
			if (isset($parts['query']))
				$out.= $parts['query'];

			fwrite($fp, $out);
			fclose($fp);
			return true;
		}
	}

	
	/*
	 * Soluciona el tratamiento como fatal error del paso de Array a la funcion nativa htmlentities
	 * a partir de PHP 8
	 */

	 function fixArrayToString(string | array $string) : string
	 {
		if (gettype($string)==='array'){

			$string = (count($string) > 0) ? strval($string[0]) : "";
		}
		return $string;
	 }
}//Fin de la Clase Utilidades
?>
