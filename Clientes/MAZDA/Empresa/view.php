<?php

	require_once('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");

$query = explode("&", $_SERVER['QUERY_STRING']);
if ($query[0] != ""){
	$bImg = stripos($query[0], "bImg");
	if ($bImg === false) {
		echo "Error";
		exit;
	}else{
		$aImg=explode("=", $query[0]);
		if ($aImg[0] !="" && $aImg[1] !=""){
			$bImg=$aImg[1];
		}else{
			echo "Error";
			exit;
		}
	}
}
if ($query[1] != ""){
	$File = stripos($query[1], "File");
	if ($bImg === false) {
		echo "Error";
		exit;
	}else{
		$aFile=explode("=", $query[1]);
		if ($aFile[0] !="" && $aFile[1] !=""){
			$File=$aFile[1];
		}else{
			echo "Error";
			exit;
		}
	}
}
//Quitar URL
$s1 =  str_replace(constant("DIR_WS_GESTOR"), "", base64_decode($File));
$sDocumento = constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . $s1;
if (file_exists($sDocumento))
{
	if(ini_get('zlib.output_compression')){
		ini_set('zlib.output_compression', 'Off');
	}
	// get the file mime type using the file extension
	switch(strtolower(substr(strrchr($sDocumento,'.'),1)))
	{
		case 'pdf': $mime = 'application/pdf'; break;
		case 'zip': $mime = 'application/zip'; break;
		case 'jpeg':
		case 'jpg': $mime = 'image/jpg'; break;
		case 'mp3': $mime = 'audio/mpeg'; break;
		default: $mime = 'application/force-download'; break;
	}
	header('Pragma: public');   // required
	header('Expires: 0');	  // no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private',false);

	 //if(!preg_match("/MSIE 7.0/",$_SERVER['HTTP_USER_AGENT'])){ 
		  //removing the Content Type for IE 7 seems to work
		header('Content-Type: '.$mime);
	 //}
	header("Accept-Ranges: bytes");
    header("Content-Disposition: attachment; filename=\"".basename(base64_decode($File))."\"");
    header('Content-Transfer-Encoding: binary');
    header("Content-Length: ".filesize($sDocumento));

    readfile($sDocumento);
}
else
{
    echo "<script type=\"text/javascript\">window.setTimeout(\"cerrar();\", 100);function cerrar(){alert('" . constant("ERR_FORM_DOWNLOAD_FICHERO") . "');self.close();}</script>";
}

?>
