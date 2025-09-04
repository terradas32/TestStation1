<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start();
	require('./include/Configuracion.php');
	include_once('include/Idiomas.php');
	//include_once(constant("DIR_WS_INCLUDE") . 'SeguridadCandidatos.php');
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Items/ItemsDB.php");
	require_once(constant("DIR_WS_COM") . "Items/Items.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas/Proceso_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidatoDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_pruebas_candidato/Proceso_pruebas_candidato.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");


	$cUtilidades = new Utilidades();

include_once ('include/conexion.php');

echo date("Y-m-d H:i:s");
  	$cCandidato = new Candidatos();
	$cCandidatosDB = new CandidatosDB($conn);
	$cProceso_informesDB = new Proceso_informesDB($conn);
	$cProceso_baremosDB = new Proceso_baremosDB($conn);

	$cRespuestasPruebasDB = new Respuestas_pruebasDB($conn);
	$sSQL = "SELECT * FROM empresas";
	$rsEmpresas = $conn->Execute($sSQL);
	$sIdsEmpresas = "";
	while(!$rsEmpresas->EOF){
		$sIdsEmpresas .= "," . $rsEmpresas->fields['idEmpresa'];
		$rsEmpresas->MoveNext();
	}
	if (!empty($sIdsEmpresas)) {
		$sIdsEmpresas = substr($sIdsEmpresas, 1);
	}

	$sSQL = "SELECT * FROM pruebas WHERE idTipoPrueba IN (2,5)";
	$rsPruebasApti = $conn->Execute($sSQL);
	$sIdsAptitudinales = "";
	while(!$rsPruebasApti->EOF){
		$sIdsAptitudinales .= "," . $rsPruebasApti->fields['idPrueba'];
		$rsPruebasApti->MoveNext();
	}
	if (!empty($sIdsAptitudinales)) {
		$sIdsAptitudinales = substr($sIdsAptitudinales, 1);
	}
	$sSQL = "SELECT * FROM respuestas_pruebas_resultados WHERE finalizado=1 AND idPrueba IN (" . $sIdsAptitudinales . ") AND idEmpresa IN (" . $sIdsEmpresas . ") LIMIT 0,5";   
	echo "<br />" . $sSQL;
	$rsRespuestas_pruebasApti = $conn->Execute($sSQL);


	$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);

	$cItemsDB = new ItemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
	$iContador=0;
	while(!$rsRespuestas_pruebasApti->EOF)
	{
 		//Miramos si ya está registrado
// 		$sSQL = "SELECT count(*) AS cuantos FROM export_aptitudinales WHERE idEmpresa=" . $rsRespuestas_pruebasApti->fields['idEmpresa'] . " AND ";
// 		$sSQL .= "idProceso=" . $rsRespuestas_pruebasApti->fields['idProceso'] . " AND ";
// 		$sSQL .= "idCandidato=" . $rsRespuestas_pruebasApti->fields['idCandidato'] . " AND ";
// 		$sSQL .= "idPrueba=" . $rsRespuestas_pruebasApti->fields['idPrueba'] . " ";
// 		$rsYaGenerado = $conn->Execute($sSQL);
// 		if ($rsYaGenerado->fields['cuantos'] == 0)
// 		{
	    	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
	    	$cItems = new Items();

	    	$cItems->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
	    	$cItems->setIdPruebaHast($rsRespuestas_pruebasApti->fields['idPrueba']);
	    	$cItems->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);
	    	$sqlItems= $cItemsDB->readLista($cItems);
	    	$listaItems = $conn->Execute($sqlItems);

	    	$cRespuestasPruebasItems->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
	    	$cRespuestasPruebasItems->setIdPruebaHast($rsRespuestas_pruebasApti->fields['idPrueba']);
			$cRespuestasPruebasItems->setIdProceso($rsRespuestas_pruebasApti->fields['idProceso']);
			$cRespuestasPruebasItems->setIdProcesoHast($rsRespuestas_pruebasApti->fields['idProceso']);
			$cRespuestasPruebasItems->setIdEmpresa($rsRespuestas_pruebasApti->fields['idEmpresa']);
			$cRespuestasPruebasItems->setIdEmpresaHast($rsRespuestas_pruebasApti->fields['idEmpresa']);
			$cRespuestasPruebasItems->setIdCandidato($rsRespuestas_pruebasApti->fields['idCandidato']);
			$cRespuestasPruebasItems->setIdCandidatoHast($rsRespuestas_pruebasApti->fields['idCandidato']);
			$cRespuestasPruebasItems->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);

			$sqlRespItems= $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
	//		echo "<br />" . $sqlRespItems;
	    	$listaRespItems = $conn->Execute($sqlRespItems);

			$cRespuestasPruebas = new Respuestas_pruebas();
	    	$cPruebasD = new Pruebas();
	    	$cPruebasD->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
	    	$cPruebasD->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);

	    	$cPruebasD= $cPruebasDB->readEntidad($cPruebasD);
	    	//Si la prueba tiene multiopciones o especiales,
	    	//Engañamos diciendo que permite blancos ya que no coincidiran el numero
	    	//de items de la prueba con las contestaciones.

	    	$sPreguntasPorPagina = $cPruebasD->getPreguntasPorPagina();
	    	//Prueba finalizada por tiempo
	    	$bSinFinalizar=false;
	    	if ($bSinFinalizar == false)
	    	{
		    	//Al finalizar una prueba bien se por tiempo o normal, hay que descontar los dongles a la empresa
		    	//y registrarlo en la tabla de consumos.

		    	//1º Miramos los datos de la empresa
		    	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
				require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
		    	$cEmpresaConsumo = new Empresas();
		    	$cEmpresaConsumoDB = new EmpresasDB($conn);

		    	$cEmpresaConsumo->setIdEmpresa($rsRespuestas_pruebasApti->fields['idEmpresa']);
		    	$cEmpresaConsumo = $cEmpresaConsumoDB->readEntidad($cEmpresaConsumo);

		    	//2º Miramos los datos del proceso
		    	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
				require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
		    	$cProcesos = new Procesos();
		    	$cProcesosDB = new ProcesosDB($conn);

		    	$cProcesos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
		    	$cProcesos->setIdProceso($rsRespuestas_pruebasApti->fields['idProceso']);

		    	$cProcesos = $cProcesosDB->readEntidad($cProcesos);

		    	//3º Miramos que baremo x defecto se aplica a la prueba
		    	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
				require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
				$cProceso_baremos = new Proceso_baremos();
		    	$cProceso_baremosDB = new Proceso_baremosDB($conn);
		    	$cProceso_baremos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
		    	$cProceso_baremos->setIdProceso($cProcesos->getIdProceso());
		    	$cProceso_baremos->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);
		    	$cProceso_baremos->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);

		    	$sSQL = $cProceso_baremosDB->readLista($cProceso_baremos);
		    	$rsProceso_baremos = $conn->Execute($sSQL);

		    	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
				require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
		    	$cProceso_informesDB = new Proceso_informesDB($conn);

		    	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
				require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
		    	$cInformes_pruebasDB = new Informes_pruebasDB($conn);

		    	require_once(constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresasDB.php");
				require_once(constant("DIR_WS_COM") . "Informes_pruebas_empresas/Informes_pruebas_empresas.php");
		    	$cInformes_pruebas_empresasDB = new Informes_pruebas_empresasDB($conn);

		    	require_once(constant("DIR_WS_COM") . "Consumos/ConsumosDB.php");
				require_once(constant("DIR_WS_COM") . "Consumos/Consumos.php");
		    	$cConsumosDB = new ConsumosDB($conn);

		    	require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informesDB.php");
				require_once(constant("DIR_WS_COM") . "Tipos_informes/Tipos_informes.php");
		    	$cTipos_informesDB = new Tipos_informesDB($conn);

		    	require_once(constant("DIR_WS_COM") . "Baremos/BaremosDB.php");
				require_once(constant("DIR_WS_COM") . "Baremos/Baremos.php");
		    	$cBaremosDB = new BaremosDB($conn);

		    	$dTotalCoste=0;
		    	while(!$rsProceso_baremos->EOF)
		    	{
			    	//4º Miramos que dongles hay que facturar para la prueba finalizada,
			    	// segun los informes seleccionados.
			    	$cProceso_informes = new Proceso_informes();
			    	$cProceso_informes->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
			    	$cProceso_informes->setIdProceso($cProcesos->getIdProceso());
			    	$cProceso_informes->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);
			    	$cProceso_informes->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
			    	$cProceso_informes->setIdBaremo($rsProceso_baremos->fields['idBaremo']);
			    	$sSQL = $cProceso_informesDB->readLista($cProceso_informes);
			    	$rsProceso_informes = $conn->Execute($sSQL);

		    		$cBaremos = new Baremos($conn);
		    		$cBaremos->setIdBaremo($cProceso_informes->getIdBaremo());
		    		$cBaremos->setIdPrueba($cProceso_informes->getIdPrueba());
		    		$cBaremos = $cBaremosDB->readEntidad($cBaremos);

		   			while(!$rsProceso_informes->EOF)
		    		{
		    			//Cambiar Dongels por Cliente/Prueba/Informe
		    			//Miramos si tiene definido dongles por empresa
		    			//5º Miramos el coste
		    			$cInformes_pruebas = new Informes_pruebas_empresas();
		    			$cInformes_pruebas->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
		    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
		    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
		    			$cInformes_pruebas->setIdEmpresa($rsProceso_informes->fields['idEmpresa']);

						$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformes_pruebas);
						$rsIPE = $conn->Execute($sql_IPE);
		    			if ($rsIPE->NumRows() > 0){
		    				$cInformes_pruebas = $cInformes_pruebas_empresasDB->readEntidad($cInformes_pruebas);
		    			}else {
			    			$cInformes_pruebas = new Informes_pruebas();
			    			$cInformes_pruebas->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
			    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
			    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
							$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);
			   			}

							//Sacamos los datos del informe para grabarlo
							$cTipos_informes = new Tipos_informes();
							$cTipos_informes->setCodIdiomaIso2($cInformes_pruebas->getCodIdiomaIso2());
							$cTipos_informes->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());
							$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);

							//$dTotalCoste += (int)$cInformes_pruebas->getTarifa();

							//6º Insertamos por cada informe una línea en Consumo
							$cConsumos = new Consumos();
							$cConsRead = new Consumos();
							$cConsumos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
							$cConsRead->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());

							$cConsumos->setIdProceso($cProcesos->getIdProceso());
							$cConsRead->setIdProceso($cProcesos->getIdProceso());

							$cConsumos->setIdCandidato($cCandidato->getIdCandidato());
							$cConsRead->setIdCandidato($cCandidato->getIdCandidato());

							$cConsumos->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);
							$cConsRead->setCodIdiomaIso2($rsRespuestas_pruebasApti->fields['codIdiomaIso2']);

							$cConsumos->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);
							$cConsRead->setIdPrueba($rsRespuestas_pruebasApti->fields['idPrueba']);

							$cConsumos->setCodIdiomaInforme($cInformes_pruebas->getCodIdiomaIso2());
							$cConsRead->setCodIdiomaInforme($cInformes_pruebas->getCodIdiomaIso2());

							$cConsumos->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());
							$cConsRead->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());

							$cConsumos->setIdBaremo($cProceso_informes->getIdBaremo());
							$cConsRead->setIdBaremo($cProceso_informes->getIdBaremo());

							$cConsumos->setNomEmpresa($cEmpresaConsumo->getNombre());
							$cConsumos->setNomProceso($cProcesos->getNombre());
							$cConsumos->setNomCandidato($cCandidato->getNombre());
							$cConsumos->setApellido1($cCandidato->getApellido1());
							$cConsumos->setApellido2($cCandidato->getApellido2());
							$cConsumos->setDni($cCandidato->getDni());
							$cConsumos->setMail($cCandidato->getMail());
							$cConsumos->setNomPrueba($cPruebasD->getNombre());
							$cConsumos->setNomInforme($cTipos_informes->getNombre());
							$cConsumos->setNomBaremo($cBaremos->getNombre());

							$cConsumos->setConcepto(constant("STR_PRUEBA_FINALIZADA"));
							$cConsRead->setConcepto(constant("STR_PRUEBA_FINALIZADA"));

							$cConsumos->setUnidades((int)$cInformes_pruebas->getTarifa());
							$cConsumos->setUsuAlta($cCandidato->getIdCandidato());
							$cConsumos->setUsuMod($cCandidato->getIdCandidato());
							//Revisamos si ya se le ha cobrado, si el Candidato actualiza la página, no hay que cobrar dos veces
							$sqlConsumos = $cConsumosDB->readLista($cConsRead);

							$rsConsRead = $conn->Execute($sqlConsumos);
							$iConsRead = $rsConsRead->NumRows();

								//Mandamos Generar el informe para que esté disponible en la descarga
								//Parámetros por orden
								// es proceso 1
								// MODO 627
								// fIdTipoInforme Prisma Informe completo 3
								// fCodIdiomaIso2 Idioma del informe es
								// fIdPrueba Prueba prisma 24
								// fIdEmpresa Id de empresa  3788
								// fIdProceso Id del proceso 3
								// fIdCandidato Id Candidato 1
								// fCodIdiomaIso2Prueba Idioma prueba es
								// fIdBaremo Id Baremo, prisma no tiene , le pasamos 1
								$cmd = constant("DIR_FS_PATH_PHP") . ' ' . str_replace("Candidato", "Admin", constant("DIR_FS_DOCUMENT_ROOT")) . '/Informes_candidato.php 1 627 ' . $cInformes_pruebas->getIdTipoInforme() . ' ' . $cInformes_pruebas->getCodIdiomaIso2() . ' ' . $rsRespuestas_pruebasApti->fields['idPrueba'] . ' ' . $rsRespuestas_pruebasApti->fields['idEmpresa'] . ' ' . $rsRespuestas_pruebasApti->fields['idProceso'] . ' ' . $rsRespuestas_pruebasApti->fields['idCandidato'] . ' ' . $rsRespuestas_pruebasApti->fields['codIdiomaIso2'] . ' 1';
								//$cUtilidades->execInBackground($cmd);
								$_idBaremo = $cProceso_informes->getIdBaremo();
								$_idBaremo = (empty($_idBaremo)) ? "1" : $_idBaremo;
								$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidatoCalculosREQUEST.php?MODO=627&fIdTipoInforme=' . $cInformes_pruebas->getIdTipoInforme() . '&fCodIdiomaIso2=' . $cInformes_pruebas->getCodIdiomaIso2() . '&fIdPrueba=' . $rsRespuestas_pruebasApti->fields['idPrueba'] . '&fIdEmpresa=' . $rsRespuestas_pruebasApti->fields['idEmpresa'] . '&fIdProceso=' . $rsRespuestas_pruebasApti->fields['idProceso'] . '&fIdCandidato=' . $rsRespuestas_pruebasApti->fields['idCandidato'] . '&fCodIdiomaIso2Prueba=' . $rsRespuestas_pruebasApti->fields['codIdiomaIso2'] . '&fIdBaremo=' . $_idBaremo;
								echo "<br />" . $cmdPost;
								$cUtilidades->backgroundPost($cmdPost);
		    			$rsProceso_informes->MoveNext();
		    		}
			    	$rsProceso_baremos->MoveNext();
		    	}

	    	}
// 		}else{
			$sSQL = "DELETE FROM respuestas_pruebas_resultados WHERE idEmpresa=" . $rsRespuestas_pruebasApti->fields['idEmpresa'] . " AND ";
			$sSQL .= "idProceso=" . $rsRespuestas_pruebasApti->fields['idProceso'] . " AND ";
			$sSQL .= "idCandidato=" . $rsRespuestas_pruebasApti->fields['idCandidato'] . " AND ";
			$sSQL .= "idPrueba=" . $rsRespuestas_pruebasApti->fields['idPrueba'] . " ";
			$conn->Execute($sSQL);
			echo "<br />" .  $sSQL;
// 		}
    	$rsRespuestas_pruebasApti->MoveNext();
    	$iContador++;
    }
    echo date("Y-m-d H:i:s");

?>
