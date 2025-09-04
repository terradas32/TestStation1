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
