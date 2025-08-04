<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	
    $bLogado = isLogado($conn);

    if (!$bLogado){
    	session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");;
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}else{
		if (!isUsuarioActivo($conn)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
		}
		//Recogemos los Menus
		$sMenus = getMenus($conn);
		if (empty($sMenus)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
        }
        $_cEntidadUsuarioTK = getUsuarioToken($conn);
    }
    $sCSVTable = importar($_POST['fFichero'], $_POST['fSrc_type'], $_POST['fSeparadorCampos'], $_POST['fEntrecomillado'], $_POST['fCodificacion'], $_POST['fCabeceras']);
    
	echo $sCSVTable;
	
  function importar($sFichero, $sSrc_type, $sSeparadorCampos=",", $sEntrecomillado='"', $sCodificacion="ISO-8859-1", $bCabeceras="0")
	{
		//echo "enclosure: " . stripslashes($sEntrecomillado) . "<br />";
		$sEntrecomillado = stripslashes($sEntrecomillado);
		global $msg_Error;
		$iLineas = 0;
		$sFichero = constant("DIR_FS_DOCUMENT_ROOT") . $sFichero;
		$retorno		= "";
		$bMagic = true;
		//Vamos a pasar los datos de un fichero con lo que no tiene el escapado automatico de PHP y hay que escaparlo
		if (false){
			$bMagic = false;
		}
		if (function_exists('mime_content_type')) {
			$src_type = mime_content_type($sFichero);
		} else if (function_exists('finfo_file')) {
			$info = finfo_open(FILEINFO_MIME);
			$src_type = finfo_file($info, $sFichero);
			finfo_close($info); 
		}

		if ($src_type == '') {
			if ($sSrc_type != ""){
				$src_type = $sSrc_type;
			}else{
				$src_type = "application/unknown";
			}
		}
		switch ($src_type)
		{
			case "text/comma-separated-values":
			case "text/csv":
			case "application/octet-stream":
			case "text/plain":
			case "application/vnd.ms-excel":
			                			
				$fc = iconv($sCodificacion, 'utf-8', file_get_contents($sFichero));
				$baseFile = basename($sFichero); 
				$newFile = str_replace(".", "temp.", $baseFile);
				$newFile = constant("DIR_FS_DOCUMENT_ROOT") . $newFile;
				file_put_contents($newFile, $fc);
				$fp = @fopen($newFile,"r");
				
				if (!$fp) {
					$msg_Error	= array();
					$sTypeError	=	date('d/m/Y H:i:s') . " Error NO se encuentra el fichero [" . $sFichero . "]";
					$msg_Error[]	= $sTypeError;
					error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					return "";
				}
				
				$row = 0;
				@set_time_limit(0);
				$retorno = '<table cellspacing="1" cellpadding="2" border="1" width="100%">';
				while (($data = fgetcsv($fp, 32000, $sSeparadorCampos, $sEntrecomillado )) !== FALSE)
				{
					if (!empty($bCabeceras)){
						if ($row > 0) {
					        $num = count($data);
				        	$sColor = (($row % 2) == 0) ? constant("ONMOUSEOUT") : constant("ONMOUSEOVER");
				        	$retorno .= "<tr bgcolor=\"" . $sColor . "\">";
					        for ($c=0; $c < $num; $c++) {
					            $retorno .= "<td class=\"tddatoslista\" nowrap=\"nowrap\">" . $data[$c] . "</td>";
					        }
					        $retorno .= "</tr>";
					        $iLineas++;
					    }
					}else{
				        $num = count($data);
				        $sColor = (($row % 2) == 0) ? constant("ONMOUSEOUT") : constant("ONMOUSEOVER");
				        $retorno .= "<tr bgcolor=\"" . $sColor . "\">";
				        for ($c=0; $c < $num; $c++) {
				            $retorno .= "<td class=\"tddatoslista\" nowrap=\"nowrap\">" . $data[$c] . "</td>";
				        }
				        $retorno .= "</tr>";
				        $iLineas++;
				    }
				    $row++;
				}
				fclose($fp);
				unlink($newFile);
				$retorno .= "</tbody></table>";
				break;
			default:
				$retorno = "";
				$msg_Error	= array();
				$sTypeError	=	date('d/m/Y H:i:s') . " Error Tipo Fichero  [" . $src_type . "][Formato no soportado]";
				$msg_Error[]	= $sTypeError;
				error_log($sTypeError . " ->\t\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				break;
		} // end switch
		$retorno = constant("STR_REGISTROS_ENCONTRADOS") . ": " . $iLineas . "<br /><br />" . $retorno;
		return $retorno;
	}
	function ver_errores($type=1)
	{
		global $msg_Error;
		$msg_string = "";
		foreach ($msg_Error as $value)
		{
			$msg_string .= $value;
			switch($type)
			{
				case 1:
					$msg_string .= "\\n";
					break;
				case 2:
					$msg_string .= "<br />";
					break;
				case 3:
					$msg_string .= "\n";
					break;
				default:
					$msg_string .= "\\n";
			}
		}
		return $msg_string;
	}

?>

