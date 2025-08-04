<?php
// Ignorar los abortos hechos por el usuario y permitir que el script
// se ejecute para siempre
ignore_user_abort(true);
set_time_limit(0);

	require_once('include/Configuracion.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . 'Idiomas.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos/Baremos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_competencias/Baremos_competenciasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Baremos_competencias/Baremos_competencias.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");


include_once ('include/conexion.php');


	$cUtilidades	= new Utilidades();

	$cTipos_informesDB	= new Tipos_informesDB($conn);  // Entidad DB
	$cIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
	$cBaremosDB	= new BaremosDB($conn);  // Entidad DB
	$cBaremos_competenciasDB	= new Baremos_competenciasDB($conn);  // Entidad DB
	$cInformes_pruebasDB	= new Informes_pruebasDB($conn);  // Entidad DB
	$cInformes_pruebas_empresasDB	= new Informes_pruebas_empresasDB($conn);  // Entidad DB
	$cEmpresasDB	= new EmpresasDB($conn);  // Entidad DB
	$cCandidatosDB	= new CandidatosDB($conn);  // Entidad DB
	$cProcesosDB	= new ProcesosDB($conn);  // Entidad DB
	$cPruebasDB	= new PruebasDB($conn);  // Entidad DB
	$cProceso_informesDB = new Proceso_informesDB($conn);	// Entidad DB

	$cEntidadDB	= new Respuestas_pruebasDB($conn);  // Entidad DB
	$cEntidad	= new Respuestas_pruebas();  // Entidad

	//seleccionamos los informes que faltan por generar, cuyo estado es = 0


	$sqlGenerar="SELECT * FROM informes_generar WHERE generado=0 ORDER BY fecAlta ASC";
	$pager = new ADODB_Pager($conn,$sqlGenerar,'Generar');
	$pager->curr_page=1;
	$LnPag = 3; //Las filas a mostrar o registros a tratar ponemos 3 informes a generar
	$pager->setRows($LnPag);
	$listaGenerar=$pager->getRS();
	if($listaGenerar->RecordCount() > 0)
	{
		while(!$listaGenerar->EOF)
		{
			$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato_REQUEST.php?MODO=627&fIdTipoInforme=' . $listaGenerar->fields['idTipoInforme'] . '&fCodIdiomaIso2=' . $listaGenerar->fields['codIdiomaInforme'] . '&fIdPrueba=' . $listaGenerar->fields['idPrueba'] . '&fIdEmpresa=' . $listaGenerar->fields['idEmpresa'] . '&fIdProceso=' . $listaGenerar->fields['idProceso'] . '&fIdCandidato=' . $listaGenerar->fields['idCandidato'] . '&fCodIdiomaIso2Prueba=' . $listaGenerar->fields['codIdiomaIso2'] . '&fIdBaremo=' . $listaGenerar->fields['idBaremo'];
      		echo "<br /><a href='" . $cmdPost . "' target='_blank'>" . $cmdPost . "</a>";
			// $cUtilidades->backgroundPost($cmdPost);
			//$cUtilidades->execInBackground("/usr/bin/curl -sL " . $cmdPost);

			//////
			$url = constant("DIR_WS_GESTOR") . 'Informes_candidato_REQUEST.php';
			$data = [
				'MODO' => '627',
				'fIdTipoInforme' => $listaGenerar->fields['idTipoInforme'],
				'fCodIdiomaIso2' => $listaGenerar->fields['codIdiomaInforme'],
				'fIdPrueba' => $listaGenerar->fields['idPrueba'],
				'fIdEmpresa' => $listaGenerar->fields['idEmpresa'],
				'fIdProceso' => $listaGenerar->fields['idProceso'],
				'fIdCandidato' => $listaGenerar->fields['idCandidato'],
				'fCodIdiomaIso2Prueba' => $listaGenerar->fields['codIdiomaIso2'],
				'fIdBaremo' => $listaGenerar->fields['idBaremo']
			];
			
			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			if ($result === FALSE) { 
				echo "<br />" . $result . " error en la llamada";
				$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Error en la llamada  [" . $cmdPost . "][" . $listaGenerar->fields['id'] . "]";
			} else {

				$sql = "UPDATE informes_generar SET ";
				$sql .= "generado=1,";
				$sql .= "fecMod=" . $conn->sysTimeStamp . ",";
				$sql .= "usuMod=0";
				$sql .= " WHERE ";
				$sql .="id=" . $conn->qstr($listaGenerar->fields['id'], false) ;

				if($conn->Execute($sql) === false){
					$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [InformesGenerarBacGround]";
					error_log($sTypeError . " ->\t" . $sql . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}

				$sTypeError	=	"\n" . date('d/m/Y H:i:s') . " Generando en background [" . $cmdPost . "][" . $listaGenerar->fields['id'] . "]";
			}
			error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
			// sleep(3); //Retrasamos la llamada al siguiente 2 minutos
			$listaGenerar->moveNext();
		}
	}

?>
