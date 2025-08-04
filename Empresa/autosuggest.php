<?php
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	include_once(constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');

	include_once('include/conexion.php');

	$cUtilidades	= new Utilidades();

	//autosuggest.php?json=true&limit=50&list=mod_ban_banners&vName=title&idName=title,
	if (!empty($_GET["list"]) && !empty($_GET["vName"]) && !empty($_GET['input']))
	{
		$sCampo = $_GET["vName"];
		$sIdCampo = (!empty($_GET["idName"])) ? $_GET["idName"] : $sCampo;
		$sEntidad = ucfirst($_GET["list"]);
		$sEntidadDB = $sEntidad . "DB";
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidadDB . ".php");
		require_once(constant("DIR_WS_COM") . $sEntidad . "/" . $sEntidad . ".php");


include_once ('include/conexion.php');

		$cEntidadDB	= new $sEntidadDB($conn);  // Entidad DB
		$cEntidad	= new $sEntidad();  // Entidad


		$input = strtolower( $_GET['input'] );
		$len = strlen($input);
		$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;


		$aResults = array();
		$count = 0;

		$sMetodo = "set" . ucfirst($sCampo);
		$cEntidad->$sMetodo($_GET['input']);
		if (!empty($_GET["LSTIdEmpresa"])){
			$cEntidad->setIdEmpresa($_GET["LSTIdEmpresa"]);
		}
		if (!empty($_GET["LSTFecPrueba"])){
			$cEntidad->setFecPrueba($_GET["LSTFecPrueba"]);
		}
		if (!empty($_GET["LSTFecPruebaHast"])){
			$cEntidad->setFecPruebaHast($_GET["LSTFecPruebaHast"]);
		}

		$sql = $cEntidadDB->readLista($cEntidad);
		$sql = $sql . " GROUP BY idProceso";
		//error_log("AUTOSUGEST Entidad:" .$sEntidad." Campo:" . $sCampo. " IdCampo:" .$sIdCampo. " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		$lista = $conn->Execute($sql);

		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
		header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header ("Pragma: no-cache"); // HTTP/1.0

		if (isset($_REQUEST['json']))
		{
			header("Content-Type: application/json");
			$info="";
			echo "{\"results\": [";
			$arr = array();
			$count=0;
			while (!$lista->EOF)
			{
				$count++;
				$arr[] = "{\"id\": \"" . str_replace('"' , '\"' , $lista->fields[$sIdCampo]) . "\", \"value\": \"" . str_replace('"' , '\"' , $lista->fields[$sCampo]) . "\", \"info\": \"" . $info . "\"}";
				$lista->MoveNext();

				if ($limit && $count==$limit){
					break;
				}
			}
			echo implode(", ", $arr);
			echo "]}";
		}
	}
?>
