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

	$cUtilidades = new Utilidades();

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");



    $cCandidato = new Candidatos();
	$cCandidatosDB = new CandidatosDB($conn);

	$cCandidato  = $_cEntidadCandidatoTK;

	$cRespuestasPruebasDB = new Respuestas_pruebasDB($conn);
	$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cItemsDB = new ItemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
	$bSinFinalizar=false;
	$pru="";
	$idi="";
    if(isset($_POST['fFinalizar']) && $_POST['fFinalizar'] !="")
    {
    	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
    	$cItems = new Items();

    	$cItems->setIdPrueba($_POST['fIdPrueba']);
    	$cItems->setIdPruebaHast($_POST['fIdPrueba']);
    	$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

    	$sqlItems= $cItemsDB->readLista($cItems);
    	$listaItems = $conn->Execute($sqlItems);

    	$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
    	$cRespuestasPruebasItems->setIdPruebaHast($_POST['fIdPrueba']);
		$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
		$cRespuestasPruebasItems->setIdProcesoHast($_POST['fIdProceso']);
		$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespuestasPruebasItems->setIdEmpresaHast($_POST['fIdEmpresa']);
		$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
		$cRespuestasPruebasItems->setIdCandidatoHast($_POST['fIdCandidato']);
		$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

		$sqlRespItems= $cRespuestasPruebasItemsDB->readLista($cRespuestasPruebasItems);
//		echo "<br />" . $sqlRespItems;
    	$listaRespItems = $conn->Execute($sqlRespItems);

		$cRespuestasPruebas = new Respuestas_pruebas();
    	$cPruebasD = new Pruebas();
    	$cPruebasD->setIdPrueba($_POST['fIdPrueba']);
    	$cPruebasD->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

    	$cPruebasD= $cPruebasDB->readEntidad($cPruebasD);
    	$sPreguntasPorPagina = $cPruebasD->getPreguntasPorPagina();
    	//Prueba finalizada por tiempo
    	if(isset($_POST['fFinalizarPorTiempo']) && $_POST['fFinalizarPorTiempo']=="1")
    	{

    	    $segundosConsumidos = ($cPruebasD->getDuracion()*60)-$_POST['fTiempoFinal'] ;
		    $sSegundos=$segundosConsumidos;
		    if($sSegundos < 60){
		    	$sMinutos="0";
		    }else{
		    	$sMinutos = intval($sSegundos/60);
		    	$sSegundos = $sSegundos%60;
		    }

			$cRespuestasPruebas->setIdPrueba($_POST['fIdPrueba']);
			$cRespuestasPruebas->setIdProceso($_POST['fIdProceso']);
			$cRespuestasPruebas->setIdEmpresa($_POST['fIdEmpresa']);
			$cRespuestasPruebas->setIdCandidato($_POST['fIdCandidato']);
			$cRespuestasPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

			$cRespuestasPruebas = $cRespuestasPruebasDB->readEntidad($cRespuestasPruebas);

			$cRespuestasPruebas->setFinalizado("1");
			$cRespuestasPruebas->setMinutos_test($sMinutos);
			$cRespuestasPruebas->setSegundos_test($sSegundos);
			$cRespuestasPruebasDB->modificar($cRespuestasPruebas);
			$bSinFinalizar=false;
    	}else{
	    	//Prueba finalizada, ha contestado todas las preguntas dentro de tiempo
	    	// o la prueba permite finalizar dejando SIN CONTESTAR
	    	if(($cPruebasD->getPermiteBlancos() == "1") || ($listaRespItems->recordCount() == $listaItems->recordCount()) )
	    	{
	    		$iContestadas24=0;
	    		$k=1;
	    		if ($_POST['fIdPrueba'] == 24){
					while(!$listaRespItems->EOF){
						//Compuebo que hayan pulsado M y Peor y no sólo una opción
						if ($listaRespItems->fields['idOpcion'] !=0){
							$iContestadas24++;
						}
						if ($k % $sPreguntasPorPagina==0){
							if ($iContestadas24 < 2){
								break;
							}
							$iContestadas24=0;
						}
						$k++;
						$listaRespItems->MoveNext();
					}
	    		}
//	    		echo "iContestadas24:::::::::::::::::::::::::::::::::" . $iContestadas24;
//	    		exit;
				if ($iContestadas24 <= 0){
		    	    $segundosConsumidos = ($_POST['fTiempoPrueba']*60)-$_POST['fTiempoFinal'] ;
				    $sSegundos=$segundosConsumidos;
				    if($sSegundos<60){
				    	$sMinutos="0";
				    }else{
				    	$sMinutos = intval($sSegundos/60);
				    	$sSegundos = $sSegundos%60;
				    }

					$cRespuestasPruebas->setIdPrueba($_POST['fIdPrueba']);
					$cRespuestasPruebas->setIdProceso($_POST['fIdProceso']);
					$cRespuestasPruebas->setIdEmpresa($_POST['fIdEmpresa']);
					$cRespuestasPruebas->setIdCandidato($_POST['fIdCandidato']);
					$cRespuestasPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

					$cRespuestasPruebas = $cRespuestasPruebasDB->readEntidad($cRespuestasPruebas);

					$cRespuestasPruebas->setFinalizado("1");
					$cRespuestasPruebas->setMinutos_test($sMinutos);
					$cRespuestasPruebas->setSegundos_test($sSegundos);
					$cRespuestasPruebasDB->modificar($cRespuestasPruebas);
					$bSinFinalizar=false;
				}else {
		    		//No ha contestado todas las preguntas y tiene tiempo
		    		$bSinFinalizar=true;
		    		$pru = $_POST['fIdPrueba'];
		    		$idi = $_POST['fCodIdiomaIso2'];
				}
	    	}else{
	    		//No ha contestado todas las preguntas y tiene tiempo
	    		$bSinFinalizar=true;
	    		$pru = $_POST['fIdPrueba'];
	    		$idi = $_POST['fCodIdiomaIso2'];
	    	}
    	}
    	//Ha finalizado la prueba, hay que descontar dongles y comunicar por mail a la empresa
    	if ($bSinFinalizar == false)
    	{
	    	//Al finalizar una prueba bien se por tiempo o normal, hay que descontar los dongles a la empresa
	    	//y registrarlo en la tabla de consumos.

	    	//1º Miramos los datos de la empresa
	    	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
			require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	    	$cEmpresaConsumo = new Empresas();
	    	$cEmpresaConsumoDB = new EmpresasDB($conn);

	    	$cEmpresaConsumo->setIdEmpresa($_POST['fIdEmpresa']);
	    	$cEmpresaConsumo = $cEmpresaConsumoDB->readEntidad($cEmpresaConsumo);

	    	//2º Miramos los datos del proceso
	    	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
			require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	    	$cProcesos = new Procesos();
	    	$cProcesosDB = new ProcesosDB($conn);

	    	$cProcesos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
	    	$cProcesos->setIdProceso($_POST['fIdProceso']);

	    	$cProcesos = $cProcesosDB->readEntidad($cProcesos);

	    	//3º Miramos que baremo x defecto se aplica a la prueba
	    	require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremosDB.php");
			require_once(constant("DIR_WS_COM") . "Proceso_baremos/Proceso_baremos.php");
			$cProceso_baremos = new Proceso_baremos();
	    	$cProceso_baremosDB = new Proceso_baremosDB($conn);
	    	$cProceso_baremos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
	    	$cProceso_baremos->setIdProceso($cProcesos->getIdProceso());
	    	$cProceso_baremos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	    	$cProceso_baremos->setIdPrueba($_POST['fIdPrueba']);

	    	$sSQL = $cProceso_baremosDB->readLista($cProceso_baremos);
	    	$rsProceso_baremos = $conn->Execute($sSQL);

	    	require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informesDB.php");
			require_once(constant("DIR_WS_COM") . "Proceso_informes/Proceso_informes.php");
	    	$cProceso_informesDB = new Proceso_informesDB($conn);

	    	require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebasDB.php");
			require_once(constant("DIR_WS_COM") . "Informes_pruebas/Informes_pruebas.php");
	    	$cInformes_pruebasDB = new Informes_pruebasDB($conn);

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
		    	$cProceso_informes->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		    	$cProceso_informes->setIdPrueba($_POST['fIdPrueba']);
		    	$cProceso_informes->setIdBaremo($rsProceso_baremos->fields['idBaremo']);
		    	$sSQL = $cProceso_informesDB->readLista($cProceso_informes);
		    	$rsProceso_informes = $conn->Execute($sSQL);

	    		$cBaremos = new Baremos($conn);
	    		$cBaremos->setIdBaremo($cProceso_informes->getIdBaremo());
	    		$cBaremos->setIdPrueba($cProceso_informes->getIdPrueba());
	    		$cBaremos = $cBaremosDB->readEntidad($cBaremos);

	   			while(!$rsProceso_informes->EOF)
	    		{
	    			//5º Miramos el coste
	    			$cInformes_pruebas = new Informes_pruebas();
	    			$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
	    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
	    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
					$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);

					//Sacamos los datos del informe para grabarlo
					$cTipos_informes = new Tipos_informes();
					$cTipos_informes->setCodIdiomaIso2($cInformes_pruebas->getCodIdiomaIso2());
					$cTipos_informes->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());
					$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);

					$dTotalCoste += (int)$cInformes_pruebas->getTarifa();

					//6º Insertamos por cada informe una línea en Consumo
					$cConsumos = new Consumos();
					$cConsumos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
					$cConsumos->setIdProceso($cProcesos->getIdProceso());
					$cConsumos->setIdCandidato($cCandidato->getIdCandidato());
					$cConsumos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
					$cConsumos->setIdPrueba($_POST['fIdPrueba']);
					$cConsumos->setCodIdiomaInforme($cInformes_pruebas->getCodIdiomaIso2());
					$cConsumos->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());
					$cConsumos->setIdBaremo($cProceso_informes->getIdBaremo());
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
					$cConsumos->setUnidades((int)$cInformes_pruebas->getTarifa());
					$cConsumos->setUsuAlta($cCandidato->getIdCandidato());
					$cConsumos->setUsuMod($cCandidato->getIdCandidato());

					$cConsumosDB->insertar($cConsumos);
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
					$cmd = constant("DIR_FS_PATH_PHP") . ' ' . str_replace("Candidato", "Admin", constant("DIR_FS_DOCUMENT_ROOT")) . '/Informes_candidato.php 1 627 ' . $cInformes_pruebas->getIdTipoInforme() . ' ' . $cInformes_pruebas->getCodIdiomaIso2() . ' ' . $_POST['fIdPrueba'] . ' ' . $_POST['fIdEmpresa'] . ' ' . $_POST['fIdProceso'] . ' ' . $_POST['fIdCandidato'] . ' ' . $_POST['fCodIdiomaIso2'] . ' 1';
					//$cUtilidades->execInBackground($cmd);
					$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato.php?MODO=627&fIdTipoInforme=' . $cInformes_pruebas->getIdTipoInforme() . '&fCodIdiomaIso2=' . $cInformes_pruebas->getCodIdiomaIso2() . '&fIdPrueba=' . $_POST['fIdPrueba'] . '&fIdEmpresa=' . $_POST['fIdEmpresa'] . '&fIdProceso=' . $_POST['fIdProceso'] . '&fIdCandidato=' . $_POST['fIdCandidato'] . '&fCodIdiomaIso2Prueba=' . $_POST['fCodIdiomaIso2'] . '&fIdBaremo=1';
					$cUtilidades->backgroundPost($cmdPost);
	    			$rsProceso_informes->MoveNext();
	    		}
		    	$rsProceso_baremos->MoveNext();
	    	}
	    	if ($dTotalCoste > 0 ){
	    		//Lo descontamos de la empresa
	    		$dResto= ($cEmpresaConsumo->getDongles() - $dTotalCoste);
	    		$cEmpresaConsumo->setDongles($dResto);
	    		$cEmpresaConsumoDB->modificar($cEmpresaConsumo);
	    		//Enviamos a la empresa que se ha finalizado esta prueba y que puede ir a descargar
	    		//el PDF
//////////////////////////////////////////////////////////////////
				require_once(constant("DIR_WS_COM") . "Peticiones_dongles/Peticiones_dongles.php");
				$Peticiones_dongles = new Peticiones_dongles();
				require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");
				$cNotificaciones = new Notificaciones();
				require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
				$cNotificacionesDB = new NotificacionesDB($conn);

				$sFrom=$cEmpresaConsumo->getMail();	//Cuenta de correo de la empresa
				$sFromName=$cEmpresaConsumo->getNombre();	//Nombre de la empresa
//				$newPass= $cCandidato->getPassword();
				$sUsuario=$cCandidato->getMail();

				//La entidad está preparada para consultar sólo por
				//Id Tipo Notificacion
				$cNotificaciones->setIdTipoNotificacion(3);	//Finalización del cuestionario
				$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
				$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, $cEmpresaConsumo, $cProcesos, $cPruebasD, $cCandidato, $cRespuestasPruebas, null, $sUsuario, null);

				$sSubject=$cNotificaciones->getAsunto();
				$sBody=$cNotificaciones->getCuerpo();
				$sAltBody=strip_tags($cNotificaciones->getCuerpo());

				// Empresa PE
				$cEmpresaPE = new Empresas();
				$cEmpresaPEDB = new EmpresasDB($conn);
				$cEmpresaPE->setIdEmpresa(constant("EMPRESA_PE"));
				$cEmpresaPE = $cEmpresaPEDB->readEntidad($cEmpresaPE);
				if ($dResto <= 0){
					//Mandamos a la Empresa proveedora y a PE
//					error_log("MAIL EMPRESA PROVEEDORA ->\t **" . $cEmpresaConsumo->getNombre() . "**\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					if (!enviaEmail($cEmpresaConsumo, $cEmpresaConsumo, $cNotificaciones, $sSubject, $sBody)){
						$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
						$sTypeError.= $cEmpresaConsumo->getNombre() . " [" . $cEmpresaConsumo->getMail() . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
					//Mandamos a PE
					//indicando los dongles negativos
					$sBody .= "Donles negativos: " . $dResto;
//					error_log("MAIL EMPRESA MATRIZ ->\t **" . $cEmpresaConsumo->getNombre() . "**\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					if (!enviaEmail($cEmpresaPE, $cEmpresaConsumo, $cNotificaciones, $sSubject, $sBody)){
						$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
						$sTypeError.= $cEmpresaPE->getNombre() . " [" . $cEmpresaPE->getMail() . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
				}else{
					//Mandamos a la Empresa proveedora
//					error_log("MAIL EMPRESA PROVEEDORA ->\t **" . $cEmpresaConsumo->getNombre() . "**\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					if (!enviaEmail($cEmpresaConsumo, $cEmpresaConsumo, $cNotificaciones, $sSubject, $sBody)){
						$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
						$sTypeError.= $cEmpresaConsumo->getNombre() . " [" . $cEmpresaConsumo->getMail() . "]";
						error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
					}
				}
///////////////////////////////////////////////////////////////////
	    	}
    	}
    }else{
	    $cCandidato->setIdArea($_POST['fIdArea']);
	    $cCandidato->setIdFormacion($_POST['fIdFormacion']);
	    $cCandidato->setIdSexo($_POST['fIdSexo']);
	    $cCandidato->setIdNivel($_POST['fIdNivel']);
	    $cCandidato->setIdEdad($_POST['fIdEdad']);

	    $cCandidatosDB->modificar($cCandidato);
    }

    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);
    $cPruebasDB = new PruebasDB($conn);

    $cProceso_pruebas->setIdEmpresa($cCandidato->getIdEmpresa());
    $cProceso_pruebas->setIdProceso($cCandidato->getIdProceso());
    $cProceso_pruebas->setOrderBy("orden");
	$cProceso_pruebas->setOrder("ASC");

    $sqlProcPruebas = $cProceso_pruebasDB->readLista($cProceso_pruebas);
    $listaProcesosPruebas = $conn->Execute($sqlProcPruebas);

    //Modificación aleatorias KPMG
    $aPruebasAleatorias = array(45,46,47,48,50,51,52,53,54,55,56,57,58,59,60);
    $aAleatoriasV = array(45,54,55);	//Verbal
    $aAleatoriasN = array(46,50,51);	//Numérica
    $aAleatoriasL = array(47,52,53);	//Lógica
    $aAleatoriasI = array(48,56,57);	//Inglés
    $aAleatoriasIL = array(58,59,60);	//Inglés Listening

	$listaProcesosPruebas->MoveFirst();
    $iListaPruebas = $listaProcesosPruebas->recordCount();
    if($iListaPruebas > 0)
    {	while(!$listaProcesosPruebas->EOF)
		{
			if (in_array($listaProcesosPruebas->fields['idPrueba'], $aPruebasAleatorias))
			{
				$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
    			$cProceso_pruebas_candidatoDB = new Proceso_pruebas_candidatoDB($conn);

				//Miramos si no se le ha asignado previamente
				$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
				$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
				$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
				$sqlProceso_pruebas_candidato = $cProceso_pruebas_candidatoDB->readLista($cProceso_pruebas_candidato);
				$listaProceso_pruebas_candidato = $conn->Execute($sqlProceso_pruebas_candidato);

				if ($listaProceso_pruebas_candidato->recordCount() <= 0)
				{
					//Insertamos la bateria en la tabla

					$sPruebaV = $aAleatoriasV[array_rand($aAleatoriasV)];
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2('es');
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaV);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("1");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

					$sPruebaN = $aAleatoriasN[array_rand($aAleatoriasN)];
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2('es');
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaN);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("2");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

					$sPruebaL = $aAleatoriasL[array_rand($aAleatoriasL)];
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2('es');
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaL);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("3");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

					$sPruebaI = $aAleatoriasI[array_rand($aAleatoriasI)];
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2('en');
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaI);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("4");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

					$sPruebaIL = $aAleatoriasI[array_rand($aAleatoriasI)];
					if ($sPruebaI == 48){
						$sPruebaIL = 58;
					}
					if ($sPruebaI == 56){
						$sPruebaIL = 59;
					}
					if ($sPruebaI == 57){
						$sPruebaIL = 60;
					}
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2('en');
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaIL);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("5");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

				}

				$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
				$cProceso_pruebas_candidato->setIdEmpresa($cCandidato->getIdEmpresa());
    			$cProceso_pruebas_candidato->setIdProceso($cCandidato->getIdProceso());
    			$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
    			$cProceso_pruebas_candidato->setOrderBy("orden");
				$cProceso_pruebas_candidato->setOrder("ASC");

    			$sqlProceso_pruebas_candidato = $cProceso_pruebas_candidatoDB->readLista($cProceso_pruebas_candidato);
    			$listaProcesosPruebas = $conn->Execute($sqlProceso_pruebas_candidato);
    			$iListaPruebas = $listaProcesosPruebas->recordCount();
				break;
			}
			$listaProcesosPruebas->MoveNext();

		}
    }
    $listaProcesosPruebas->MoveFirst();
//    echo $iListaPruebas;
    //Fin Modificación aleatorias KPMG

    $comboAREAS			= new Combo($conn,"fIdArea","idArea","nombre","Descripcion","areas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboEDADES		= new Combo($conn,"fIdEdad","idEdad","nombre","Descripcion","edades","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboNIVELES		= new Combo($conn,"fIdNivel","idNivel","nombre","Descripcion","nivelesjerarquicos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboFORMACIONES 	= new Combo($conn,"fIdFormacion","idFormacion","nombre","Descripcion","formaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");
    $comboSEXOS			= new Combo($conn,"fIdSexo","idSexo","nombre","Descripcion","sexos","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false),"","","");

    //Se envia correo de Notificación
	function enviaEmail($cEmpresaTO, $cEmpresaFROM, $cNotificaciones, $sSubject=null, $sBody=null){
		global $conn;
		if ($sSubject == null){
			$sSubject=$cNotificaciones->getAsunto();
		}
		if ($sBody == null){
			$sBody=$cNotificaciones->getCuerpo();
		}
		if ($sBody == null){

			$sAltBody=strip_tags($cNotificaciones->getCuerpo());
		}else{
			$sAltBody=strip_tags($sBody);
		}

		require_once constant("DIR_WS_COM") . 'PHPMailer/Exception.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/PHPMailer.php';
		require_once constant("DIR_WS_COM") . 'PHPMailer/SMTP.php';

		//instanciamos un objeto de la clase phpmailer al que llamamos
		//por ejemplo mail
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);  //PHPMailer instance with exceptions enabled
		$mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		try {
			//Server settings
			//$mail->SMTPDebug = 2; 					                //Enable verbose debug output
			$mail->isSMTP();                                        //Send using SMTP                  
			$mail->Host = constant("HOSTMAIL");						//Set the SMTP server to send through
			$mail->SMTPAuth   = true;                               //Enable SMTP authentication
			$mail->Username = constant("MAILUSERNAME");             //SMTP username
			$mail->Password = constant("MAILPASSWORD");             //SMTP password
			$mail->SMTPSecure = constant("MAIL_ENCRYPTION");							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';

			// Borro las direcciones de destino establecidas anteriormente
			$mail->clearAllRecipients();

			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresaFROM->getMail();
			$mail->From = constant("EMAIL_CONTACTO");
			$mail->AddReplyTo($cEmpresaFROM->getMail(), $cEmpresaFROM->getNombre());
			$mail->FromName = $cEmpresaFROM->getNombre();
				$nomEmpresa = $cEmpresaFROM->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $nomEmpresa . " - " . $sSubject;
			$mail->Body = $sBody;

			//Definimos AltBody por si el destinatario del correo no admite
			//email con formato html
			$mail->AltBody = $sAltBody;

			//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
			//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
			$mail->Timeout=120;

			//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
			$archivo="none";
			if ($archivo !="none") {
				$mail->AddAttachment($archivo,$archivo_name);
			}

			//Indicamos cuales son las direcciones de destino del correo
			$mail->AddAddress($cEmpresaTO->getMail(), $cEmpresaTO->getNombre());
			if($cEmpresaTO->getMail2()!=""){
				$mail->AddAddress($cEmpresaTO->getMail2(), $cEmpresaTO->getNombre());
			}
			if($cEmpresaTO->getMail3()!=""){
				$mail->AddAddress($cEmpresaTO->getMail3(), $cEmpresaTO->getNombre());
			}
			/*
			echo "<br />De: " . $cEmpresaFROM->getMail();
			echo "<br />De Nombre: " . $cEmpresaFROM->getNombre();
			echo "<br />Para: " . $cEmpresaTO->getMail();
			echo "<br />Para Nombre: " . $cEmpresaTO->getNombre();
			*/
			//se envia el mensaje, si no ha habido problemas la variable $success
			//tendra el valor true
			$exito=false;
			//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
			//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
			//segundos despues del anterior, para ello se usa la funcion sleep
			$intentos=1;
			while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
			{
			sleep(rand(0, 2));
				//echo $mail->ErrorInfo;
				$exito = $mail->Send();
				$intentos=$intentos+1;
			}

			//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
			//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
			//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
			if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				$exito=true;
			}
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
	    return $exito;
	}
?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/apple-overlay.css" type="text/css" />
     <script src="codigo/comun.js"></script>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/eventos.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jquery.tools.min.js"></script>
<script   >
//<![CDATA[

function comienzaprueba(idPrueba,idioma)
{
	var f=document.forms[0];
	f.fIdPrueba.value = idPrueba;
	f.fCodIdiomaIso2.value = idioma;

	f.submit();
}
function vuelve(){
	var f=document.forms[0];
	f.action='datosprofesionales.php';
	f.submit();
}
function sinFinalizar(pru,idi){
	alert("<?php echo constant("STR_SIN_FINALIZAR");?>");
	comienzaprueba(pru,idi);
}
//]]>
</script>
	<style type="text/css">

	/* black version of the overlay. simply uses a different background image */
	div.apple_overlay.black {
		background-image:url(graf/sp.gif);
		color:#000;
	}

	div.apple_overlay h2 {
		margin:10px 0 -9px 0;
		font-weight:bold;
		font-size:14px;
	}

	div.black h2 {
		color:#000;
	}

	#triggers {
		margin-top:10px;
		/*text-align:center;*/
	}

	#triggers img {
		background-color:#fff;
		padding:2px;
		border:1px solid #fff;
		margin:2px 5px;
		cursor:pointer;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
	}
	</style>

</head>
<body onload="noBack();<?php echo ($bSinFinalizar) ? "sinFinalizar('".$pru."','".$idi."');" : "";?>">
<form name="form" method="post" action="Prueba.php">
<?php
$HELP="xx";
$iPruebasFinalizadas=0;
?>
<div id="contenedor">
	<?php include_once(constant("DIR_WS_INCLUDE") . 'cabecera.php');?>
	<div id="envoltura">
		<div id="contenido">
			<div style="margin-left:30%;">
		     	<ul>
		        	<li>
	        			<div id="triggers">
        			    	<?php
//        			    	$iListaPruebas = $listaProcesosPruebas->recordCount();
        			    	if($iListaPruebas > 0)
        			    	{
				        		$i=1;
				        		$iPintadoBoton=0;

				        		while(!$listaProcesosPruebas->EOF){
				        			$cRespuestas = new Respuestas_pruebas();

				        			$cRespuestas->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
				        			$cRespuestas->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);
				        			$cRespuestas->setIdProceso($cCandidato->getIdProceso());
				        			$cRespuestas->setIdEmpresa($cCandidato->getIdEmpresa());
				        			$cRespuestas->setIdCandidato($cCandidato->getIdCandidato());

				        			$cRespuestas = $cRespuestasPruebasDB->readEntidad($cRespuestas);

				        			$cPruebas = new Pruebas();
				        			$cPruebas->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
				        			$cPruebas->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);

				        			$cPruebas = $cPruebasDB->readEntidad($cPruebas);
				        			?>
									<div>
							        	<ul>
								        	<li style="margin-top:10px;"><img style="min-width: 60px;" title="<?php echo $cPruebas->getNombre();?>" src="<?php echo constant("DIR_WS_GESTOR") . $cPruebas->getlogoPrueba()?>"  border="0" alt="" rel="#photo<?php echo $i?>" />
							        	<?php
							        		if($cRespuestas->getFinalizado()== '0' || $cRespuestas->getFinalizado()==""){
							        			if($iPintadoBoton==0){?>
							        				<input type="button" class="botones" value="<?php echo constant("STR_COMENZAR");?>" style="vertical-align: text-bottom;margin-bottom: 15px;" onclick="javascript:comienzaprueba('<?php echo $cPruebas->getIdPrueba()?>', '<?php echo $cPruebas->getCodIdiomaIso2()?>');"/>
							        	<?php	 	$iPintadoBoton++;
							        			}
								        	}else{
								        		$iPruebasFinalizadas++;
								        ?>
								        		<label> <?php echo constant("STR_FINALIZADO");?><br /></label>
								        <?php
											}
										?><br />
												<table width="120px" border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td align="center">
										        			<?php echo $cPruebas->getDescripcion()?>
										        		</td>
										        	</tr>
										        </table>
								        	</li>
							        	</ul>
						        	</div>
				        	<?php		$i++;
				        			$listaProcesosPruebas->MoveNext();
				        		}
							}
							?>
						</div>
						<?php
						$listaProcesosPruebas->MoveFirst();
						if($iListaPruebas > 0){
			        		$j=1;
			        		while(!$listaProcesosPruebas->EOF){
			        			$cPruebas = new Pruebas();
			        			$cPruebas->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
			        			$cPruebas->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);

			        			$cPruebas = $cPruebasDB->readEntidad($cPruebas);
			        			$sPantalla = "graf/sp.gif";
			        			if ($cPruebas->getCapturaPantalla() != ""){
			        				$sPantalla = $cPruebas->getCapturaPantalla();
			        			}
			        			?>
			        			<div class="apple_overlay black" id="photo<?php echo $j?>" >
									<div align="center">
										<img src="<?php echo constant("DIR_WS_GESTOR") . $sPantalla;?>"  border="0" alt="" />
									</div>
									<div class="details">
										<br />
										<h2><?php echo $cPruebas->getNombre();?></h2>
										<br />
										<p>
											<?php echo $cPruebas->getDescripcion();?>
										</p>
									</div>
								</div>
					    <?php		$j++;
			        			$listaProcesosPruebas->MoveNext();
			        		}
						}?>
					</li>
	        	</ul>
		    </div>
<?php	if ($iPruebasFinalizadas == $iListaPruebas){	?>
		    <div style="margin-left:30%;padding-top:10px;">
		    	<input type="button" class="botones" value="<?php echo constant("STR_SALIR")?>" onclick="javascript:top.location.replace('<?php echo constant("HTTP_SERVER");?>');" />
		    </div>
<?php	}
//		else{?>
<!--
		    <div style="margin-left:30%;padding-top:10px;">
		    	<input type="button" class="botones" value="<?php echo constant("STR_VOLVER")?>" onclick="javascript:vuelve();"/>
		    </div>
-->
<?php
//		}?>
		</div>
	</div>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
	<script   >
		$(function() {
			$("#triggers img[rel]").overlay({effect: 'apple'});
		});
	</script>
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fIdPrueba" value="" />
<input type="hidden" name="fCodIdiomaIso2" value="" />
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />

</form>

</body>
</html>
<?php
/*
 * Comprobamos si ha finalizado todas las pruebas del proceso
 * En ese caso marcamos el candidato como finalizado y la fecha
 * de finalización.
 * Sólo para aquellos candidatos que no estén finalizados previamente.
*/
if ($iPruebasFinalizadas == $iListaPruebas){
	//Consultamos el candidato
	if ($cCandidato->getFinalizado() != "1"){
		$cCandidato->setFinalizado("1");
		$cCandidato->setFechaFinalizado($conn->sysTimeStamp);
		$cCandidatosDB->modificar($cCandidato);
	}
}
?>
