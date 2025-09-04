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
    	$sCadena = preg_replace("/[^a-zA-Z0-9 áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛñÑ]/", "", $sCadena);
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
        $sChars = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','ä','ë','ï','ö','ü','Ä','Ë','Ï','Ö','Ü','à','è','ì','ò','ù','À','È','Ì','Ò','Ù','â','ê','î','ô','û','Â','Ê','Î','Ô','Û','º','ª','ñ','Ñ');
		$sScapes = array('a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','o','a','n','N');
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
	    if (preg_match(	"^[_/a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/", $pMail ) ) {
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
		return mb_strtolower((str_replace(" ","-",$this->delcaracteresp($this->cCambiaAcentos(strip_tags($sCadena))))),'UTF-8');
	}
	/**********************************************
	* Retorna un Booleano si esiste la url.
	* @param url Cadena de tipo url.
	* @return Boolean true encontrado false no encontrado.
	*********************************************/
	function verifica_url($url)
	{
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Curl.php");
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
    *  @return String Fecha formateada por la maskara idioma
    */
    function PrintDate($fecha = "", $mask = "%d de %B de %Y") {
        if ($fecha == ""){
              $fecha = time();
        }else{
              $fecha      =strtotime($fecha);
        }

        setlocale(LC_ALL, 'es-ES');
        $loc = setlocale(LC_TIME, NULL);
        $fecha = strftime($mask, $fecha);
        //Si no funciona setlocale lo sustituimos
        $en = array("January", "February", "March", "April", "May", "June" ,"July", "August", "September" , "October", "November", "December");
        $es = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio" ,"Julio", "Agosto", "Septiembre" , "Octubre", "Noviembre", "Diciembre");
        $fecha = str_replace($en, $es, $fecha);

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
	/**
	*  Especifico para las pruebas de TestStation, devuelve el valor calculado o logico de la prueba.
	*
	*  @param String rsLine Línea de respuestas_pruebas_items
	*  @param String cOpciones Entidad Opciones consultada
	*  @return String Valor calculado según la opción guardada en Respuestas_pruebas_items
	*/
	function getValorCalculadoPRUEBAS($rsLine, $cOpciones, $conn){
		$sValor="";

		//Leemos el item para saber cual es la opción correcta
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/ItemsDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items/Items.php");
		$cItemsDB = new ItemsDB($conn);
		$cItem = new Items();
		$cItem->setIdItem($rsLine->fields['idItem']);
		$cItem->setIdPrueba($rsLine->fields['idPrueba']);
		$cItem->setCodIdiomaIso2($rsLine->fields['codIdiomaIso2']);
		$cItem = $cItemsDB->readEntidad($cItem);

		//Leemos la opción para saber en código de la misma
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Opciones/Opciones.php");
		$cOpcionDB = new OpcionesDB($conn);
		$cOpcion = new Opciones();
		$cOpcion->setIdItem($rsLine->fields['idItem']);
		$cOpcion->setIdPrueba($rsLine->fields['idPrueba']);
		$cOpcion->setIdOpcion($rsLine->fields['idOpcion']);
		$cOpcion->setCodIdiomaIso2($rsLine->fields['codIdiomaIso2']);
		$cOpcion = $cOpcionDB->readEntidad($cOpcion);

		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversosDB.php");
		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Items_inversos/Items_inversos.php");
		$cItems_inversosDB = new Items_inversosDB($conn);
		$cItems_inversos = new Items_inversos();

		$cItems_inversos->setIdPrueba($rsLine->fields['idPrueba']);
		$cItems_inversos->setIdPruebaHast($rsLine->fields['idPrueba']);
		$sqlInversos = $cItems_inversosDB->readLista($cItems_inversos);
		//echo "<br />----*>" . $sqlInversos;
		$listaInversos = $conn->Execute($sqlInversos);
		$nInversos = $listaInversos->recordCount();
		$aInversos = array();
		if($nInversos>0){
			$i=0;
			while(!$listaInversos->EOF){
				$aInversos[$i] = $listaInversos->fields['idItem'];
				$i++;
				$listaInversos->MoveNext();
			}
		}

		if (!empty($rsLine->fields['valor'])){
			return $rsLine->fields['valor'];
		}

		switch ($rsLine->fields['idPrueba'])
		{
			case 13:	//Tipo Prisma CML
				//llega el resulset de la respuestas páginadas de 3 en 3
				//echo " Prueba::" . $rsLine->fields['idPrueba'] . " Líneas::" . $rsLine->recordCount();
				$iVacio=0;
				$iPulsado=0;
				$bTratarValores = true;
				if (!is_array($cOpciones)){
//					echo "<br />No ha llegado el array de Escalas Items.";
					$cOpciones = array();
					$bTratarValores = false;
				}
				if ($bTratarValores)
				{
					while (!$rsLine->EOF)
					{
						//Para esta prueba en $cOpciones, viene un array
						//Con los items de la escala
						if(array_search($rsLine->fields['idItem'], $cOpciones) === false)
						{
							if (!empty($rsLine->fields['codigo'])){
								$iPulsado++;
							}
							$sValor += 0;
						}else{
//							echo "<br />ITEM::" . $rsLine->fields['idItem'];
							if(array_search($rsLine->fields['idItem'], $aInversos) === false)
							{
//								echo " - DIRECTO -";
								if (!empty($rsLine->fields['codigo'])){
									if ($iPulsado == 0)
									{	//Primero pulsado
//										echo " - PULSADO 1º OPCION:: " . $rsLine->fields['codigo'] . "-";
										$sValor += 2;
//										echo " VALOR:: " . $sValor;
									}else{
//										echo " - PULSADO 2º OPCION:: " . $rsLine->fields['codigo'] . "-";
										//Segundo pulsado
										$sValor += 1;
//										echo " VALOR:: " . $sValor;
									}
									$iPulsado++;
								}else{
//									echo " - VACIO -";
									$iVacio++;
									$sValor += 0;
//									echo " VALOR:: " . $sValor;
								}
							}else{
//								echo " - INVERSO -";
								if (!empty($rsLine->fields['codigo'])){
									if ($iPulsado == 0)
									{	//Primero pulsado
//										echo " - PULSADO 1º OPCION:: " . $rsLine->fields['codigo'] . "-";
										$sValor += 0;
//										echo " VALOR:: " . $sValor;
									}else{
//										echo " - PULSADO 2º OPCION:: " . $rsLine->fields['codigo'] . "-";
										//Segundo pulsado
										$sValor += 1;
//										echo " VALOR:: " . $sValor;
									}
									$iPulsado++;
								}else{
//									echo " - VACIO -";
									$iVacio++;
									$sValor += 2;
//									echo " VALOR:: " . $sValor;
								}
							}
							$sSQLUPDATE = "UPDATE respuestas_pruebas_items SET valor = " . $conn->qstr($sValor);
							$sSQLUPDATE .= " WHERE idEmpresa = " . $conn->qstr($rsLine->fields['idEmpresa']);
							$sSQLUPDATE .= " AND idProceso = " . $conn->qstr($rsLine->fields['idProceso']);
							$sSQLUPDATE .= " AND idCandidato = " . $conn->qstr($rsLine->fields['idCandidato']);
							$sSQLUPDATE .= " AND codIdiomaIso2 = " . $conn->qstr($rsLine->fields['codIdiomaIso2']);
							$sSQLUPDATE .= " AND idPrueba = " . $conn->qstr($rsLine->fields['idPrueba']);
							$sSQLUPDATE .= " AND idItem = " . $conn->qstr($rsLine->fields['idItem']);
//							echo "<br />" . $sSQLUPDATE;
							$conn->Execute($sSQLUPDATE);
						}
						$rsLine->MoveNext();
					}
				}else {
					$sValor = $rsLine->fields['valor'];
				}
				break;
			case 11:	//Tipo Prisma TOC
			case 117:	//Tipo Prisma MAZDA
			case 121:	//Tipo Prisma MAZDA
				if ($rsLine->fields['idOpcion'] == "1"){
					$sValor = 1;
				}else{
					$sValor = 0;
				}
				break;
			case 12:	//Cel16
				if ($rsLine->fields['idItem'] > 96){
					if ($rsLine->fields['codigo'] == "M"){
						$sValor = 1;
					}else{
						$sValor = 0;
					}
				}else {
					if(array_search($rsLine->fields['idItem'], $aInversos) === false)
					{
						switch ($rsLine->fields['idOpcion'])
						{
							case '1':	// Mejor
								$sValor = 2;
								break;
							case '2':	// Peor
								$sValor = 0;
								break;
							default:	// Sin contestar opcion 0 en respuestas
								$sValor = 1;
								break;
						}
					}else{
						switch ($rsLine->fields['idOpcion'])
						{
							case '1':	// Mejor
								$sValor = 0;
								break;
							case '2':	// Peor
								$sValor = 2;
								break;
							default:	// Sin contestar opcion 0 en respuestas
								$sValor = 1;
								break;
						}
					}
				}
				break;
			case 22:	//OP4
				switch ($rsLine->fields['idOpcion'])
				{
					case '1':	// Mejor
						$sValor = 1;
						break;
					default:	// Sin contestar opcion 0 en respuestas
						$sValor = 0;
						break;
				}
				break;
			case 24:	//Prisma
			case 33:	//Prisma CLECE
			case 39:	//Prisma PASCUAL
			case 49:	//Habilidades
			case 10:	//Tipo Prisma TEC
			case 87:	//CUESTIONARIO DE PERSONALIDAD LABORAL - SEAS
				if(array_search($rsLine->fields['idItem'], $aInversos) === false)
				{
					switch ($rsLine->fields['idOpcion'])
					{
						case '1':	// Mejor
							$sValor = 2;
							break;
						case '2':	// Peor
							$sValor = 0;
							break;
						default:	// Sin contestar opcion 0 en respuestas
							$sValor = 1;
							break;
					}
				}else{
					switch ($rsLine->fields['idOpcion'])
					{
						case '1':	// Mejor
							$sValor = 0;
							break;
						case '2':	// Peor
							$sValor = 2;
							break;
						default:	// Sin contestar opcion 0 en respuestas
							$sValor = 1;
							break;
					}
				}
				break;
			case 32:	//CIP
				switch ($rsLine->fields['idOpcion'])
				{
					case '1':	// Agrada
						$sValor = 2;
						break;
					case '2':	// Desagrada
						$sValor = 0;
						break;
					default:	// Indiferente
						$sValor = 1;
						break;
				}
				break;
			case 77:	//Flash Trotamundos
			case 71:	//Flash El Hotel
			case 72:	//Flash
			case 73:	//Flash
			case 74:	//Redacción EN ESADE
			case 76:	//HTML5 Planet Trip
			case 99:	//Redacción Forjanor
//				echo "<br />Utilidades:: " . $rsLine->fields['valor'];
				$sValor = $rsLine->fields['valor'];
				break;
			case 83:	//Tipo Prisma
					if(array_search($rsLine->fields['idItem'], $aInversos) === false)
					{
						switch ($rsLine->fields['idOpcion'])
						{
							case '1':	// Mejor
								$sValor = 3;
								break;
							case '2':	// Peor
								$sValor = 1;
								break;
							default:	// Sin contestar opcion 0 en respuestas
								$sValor = 2;
								break;
						}
					}else{
						switch ($rsLine->fields['idOpcion'])
						{
							case '1':	// Mejor
								$sValor = 1;
								break;
							case '2':	// Peor
								$sValor = 3;
								break;
							default:	// Sin contestar opcion 0 en respuestas
								$sValor = 2;
								break;
						}
					}
					break;
			default:
				if(strtoupper($cItem->getCorrecto()) == strtoupper($cOpcion->getCodigo())){
					$sValor = 1;
				}else{
					$sValor = 0;
				}
//				echo "<br />Empresa::" . $rsLine->fields['idEmpresa'] . " Proceso::" . $rsLine->fields['idProceso'] . " Prueba::" . $rsLine->fields['idPrueba'] . " Candidato::" . $rsLine->fields['idCandidato'] . " **->orden::" . $rsLine->fields['orden'] . " " . $cItem->getCorrecto() . " == " . $cOpcion->getCodigo() . " ->valor::" . $sValor;
				break;
		} // end switch

		return $sValor;
	}

	// $fechanacimiento (aaaa-mm-dd)
	function calculaedad($fechanacimiento){
		list($ano,$mes,$dia) = explode("-",$fechanacimiento);
		$ano_diferencia  = date("Y") - $ano;
		$mes_diferencia = date("m") - $mes;
		$dia_diferencia   = date("d") - $dia;
		if ($dia_diferencia < 0 || $mes_diferencia < 0)
			$ano_diferencia--;
		return $ano_diferencia;
	}

	function quickRandom($length = 16)
	{
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

			return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
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
