<?php
session_start();
ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Ficheros_carga/Ficheros_cargaDB.php");
	require_once(constant("DIR_WS_COM") . "Ficheros_carga/Ficheros_carga.php");
	
include_once ('include/conexion.php');
	
	
	$cEntidadDB	= new ProcesosDB($conn);  // Entidad DB
	$cEntidad	= new Procesos();  // Entidad

	$cFicheros_cargaDB	= new Ficheros_cargaDB($conn);  // Entidad DB

		
	$_cEntidadUsuarioTK = getUsuarioToken($conn);
	$sCol1='';
	$sCol2='';
			
	$error = "";
	$msg = "";
	$fileElementName = 'fFichero';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;
			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES['fFichero']['tmp_name']) || $_FILES['fFichero']['tmp_name'] == 'none')
	{
		//$error = 'No file was uploaded..';
	}else 
	{
		
		$cFicheros_carga = new Ficheros_carga();
		$cFicheros_carga->setFichero("ficheros/".$_FILES['fFichero']['name']);
		$cFicheros_carga->setTipo($_FILES['fFichero']['type']);
		$cFicheros_carga->setUsuAlta($_cEntidadUsuarioTK->getIdUsuario());
		$IdCargaFichero = $cFicheros_cargaDB->insertar($cFicheros_carga);
		$sTypeError	=	date('d/m/Y H:i:s') . " Fichero CARGADO " . $IdCargaFichero;
		error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		$_SESSION["IdCargaFichero" . constant("NOMBRE_SESSION")] = $IdCargaFichero;
//		$msg .= " Fichero " . $_FILES['fFichero']['name'] . " \\n ";
	//$msg .= print_r($_FILES['fFichero']);
		//for security reason, we force to remove all uploaded file
		//@unlink($_FILES['fFichero']);
//		@move_uploaded_file($_FILES['fFichero']['tmp_name'], constant("DIR_FS_DOCUMENT_ROOT") . "ficheros/".$_FILES['fFichero']['name']);		
	}		
//	echo "{";
//	echo				"error: '" . $error . "',\n";
//	echo				"msg: '" . $msg . "'\n";
//	echo "}";


ob_end_flush();
?>