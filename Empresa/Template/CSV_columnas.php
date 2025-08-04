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
    $sCSVTable = visualizarVertical($_POST['fFichero'], $_POST['fSrc_type'], $_POST['fSeparadorCampos'], $_POST['fEntrecomillado'], $_POST['fCodificacion'], $_POST['fCabeceras']);

	echo $sCSVTable;

    function visualizarVertical($sFichero, $sSrc_type, $sSeparadorCampos=",", $sEntrecomillado='"', $sCodificacion="ISO-8859-1", $bCabeceras="0")
	{
		global $msg_Error;

		$sEntrecomillado = str_replace("\\", "", $sEntrecomillado);
		$sFichero = constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $sFichero;
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
				$newFile = constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $newFile;
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
				$iCampos=1;
				@set_time_limit(0);
				$retorno = '<table cellspacing="1" cellpadding="2" border="1" width="100%">';
					$retorno .= "<tr bgcolor=\"" . constant("ONMOUSEOUT") . "\">";
						$retorno .= "<td class=\"negrob\" nowrap=\"nowrap\">" . constant("STR_CAMPOS_IMPORTADOS") . "</td>";
						$retorno .= "<td class=\"negrob\" nowrap=\"nowrap\">" . constant("STR_CAMPOS_DE_LA_PLATAFORMA") . "</td>";
					$retorno .= "</tr>";
				$sSELECT_BACK="<select id=\"fCampos\" name=\"fCampos[]\" size=\"1\" class=\"cajatexto\">";
				$sSELECT_BACK.="<option style=\"color:#000000;\" value=\"\">[" . constant("STR_SIN_ASIGNAR") . "]</option>";
				$sSELECT_BACK.="<option style=\"color:#000000;\" value=\"Mail\">" . constant("STR_EMAIL") . "</option>";
				$sSELECT_BACK.="<option style=\"color:#000000;\" value=\"Nombre\">" . constant("STR_NOMBRE") . "</option>";
				$sSELECT_BACK.="<option style=\"color:#000000;\" value=\"Apellido1\">" . constant("STR_APELLIDO_1") . "</option>";
				$sSELECT_BACK.="<option style=\"color:#000000;\" value=\"Apellido2\">" . constant("STR_APELLIDO_2") . "</option>";
				$sSELECT_BACK.="<option style=\"color:#000000;\" value=\"Dni\">" . constant("STR_NIF") . "</option>";
				$sSELECT_BACK.="</select>";

				while (($data = fgetcsv($fp, 32000, $sSeparadorCampos, $sEntrecomillado )) !== FALSE)
				{
					if (!empty($bCabeceras)){
						if ($row > 0) {
					        $num = count($data);

					        for ($c=0; $c < $num; $c++) {
					        	$sSELECT = $sSELECT_BACK;
					        	switch($c)
								{
									case 0:
										$sSELECT = str_replace("value=\"Apellido1\">", "value=\"Apellido1\" selected>", $sSELECT);
										break;
									case 1:
										$sSELECT = str_replace("value=\"Apellido2\">", "value=\"Apellido2\" selected>", $sSELECT);
										break;
									case 2:
										$sSELECT = str_replace("value=\"Dni\">", "value=\"Dni\" selected>", $sSELECT);
										break;
									case 3:
										$sSELECT = str_replace("value=\"Nombre\">", "value=\"Nombre\" selected>", $sSELECT);
										break;
									case 4:
										$sSELECT = str_replace("value=\"Mail\">", "value=\"Mail\" selected>", $sSELECT);
										break;
								}
					        	$retorno .= "<tr>";
					            	$retorno .= "<td colspan=\"2\" class=\"tddatoslista\" nowrap=\"nowrap\"><li>" . $iCampos . "º</td>";
					            $retorno .= "</tr>";
					        	$retorno .= "<tr>";
					            	$retorno .= "<td class=\"tddatoslista\" nowrap=\"nowrap\">" . $data[$c] . "</td>";
					            	$retorno .= "<td class=\"tddatoslista\" nowrap=\"nowrap\">" . $sSELECT . "</td>";
					            $retorno .= "</tr>";
					            $iCampos++;
					        }

					    }
					}else{
				        $num = count($data);

				        for ($c=0; $c < $num; $c++) {
				        	$sSELECT = $sSELECT_BACK;
				        	switch($c)
							{
								case 0:
									$sSELECT = str_replace("value=\"Apellido1\">", "value=\"Apellido1\" selected>", $sSELECT);
									break;
								case 1:
									$sSELECT = str_replace("value=\"Apellido2\">", "value=\"Apellido2\" selected>", $sSELECT);
									break;
								case 2:
									$sSELECT = str_replace("value=\"Nombre\">", "value=\"Nombre\" selected>", $sSELECT);
									break;
								case 3:
									$sSELECT = str_replace("value=\"Mail\">", "value=\"Mail\" selected>", $sSELECT);
									break;
							}
				        	$retorno .= "<tr>";
				            	$retorno .= "<td colspan=\"2\" class=\"tddatoslista\" nowrap=\"nowrap\"><li>" . $iCampos . "º</td>";
				            $retorno .= "</tr>";
				        	$retorno .= "<tr>";
				            	$retorno .= "<td class=\"tddatoslista\" nowrap=\"nowrap\">" . $data[$c] . "</td>";
				            	$retorno .= "<td class=\"tddatoslista\" nowrap=\"nowrap\">" . $sSELECT . "</td>";
				            $retorno .= "</tr>";
				            $iCampos++;
				        }
				    }
				    if (!empty($bCabeceras)){
						if ($row == 1) {
							break;
						}
					}else{
						if ($row == 0) {
							break;
						}
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
