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

	//Para las pruebas de FLASH, verificamos que nos llega:
	// "usuario" y "prueba";
	$bGame = false;
	If (!empty($_REQUEST["usuario"]) && !empty($_REQUEST["prueba"])){
		//Es una llamada de finalización de pruebas FLASH
		$_POST['sTKCandidatos'] = $_REQUEST["usuario"];
		$bGame = true;
	}
	//FIN pruebas de FLASH

	$cUtilidades = new Utilidades();

include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");



	//Para las pruebas de ECases, verificamos que se inician y se crea registro
	// en proceso_pruebas si no está creado.
	$bECases=0;	//Identificador de si la prueba es de la plataforma e-Cases
	if (isset($_REQUEST["fECases"]) && $_REQUEST["fECases"] == "1")
	{

include_once ('include/conexionECases.php');

		//Es una llamada de finalización de prueba
		$cPECases = new Pruebas();
		$cPECasesDB = new PruebasDB($conn);


		$cPPECases = new Proceso_pruebas();
		$cPPECasesDB = new Proceso_pruebasDB($conn);
		$cPPECases->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
		$cPPECases->setIdProceso($_cEntidadCandidatoTK->getIdProceso());
		$cPPECases->setOrderBy("orden");
		$cPPECases->setOrder("ASC");

		$sqlPPECases = $cPPECasesDB->readLista($cPPECases);
//		echo "<br />" . $sqlPPECases;
		$lPPECases = $conn->Execute($sqlPPECases);
		//Buscamos la prueba que estamos, la 1ª sin finalizar
		$cRPECasesDB = new Respuestas_pruebasDB($conn);
	    while(!$lPPECases->EOF){
			$cRPECases = new Respuestas_pruebas();

			$cRPECases->setIdPrueba($lPPECases->fields['idPrueba']);
			$cRPECases->setCodIdiomaIso2($lPPECases->fields['codIdiomaIso2']);
			$cRPECases->setIdProceso($_cEntidadCandidatoTK->getIdProceso());
			$cRPECases->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
			$cRPECases->setIdCandidato($_cEntidadCandidatoTK->getIdCandidato());

			$lRPECases = $cRPECasesDB->readLista($cRPECases);
			if ($lPPECases->NumRows() > 0){
				while(!$lPPECases->EOF){
		       		if($cRPECases->getFinalizado()== '0' || $cRPECases->getFinalizado()==""){
//		       			echo "<br />" . $lPPECases->fields['idPrueba'];
				    	$cPECases->setIdPrueba($lPPECases->fields['idPrueba']);
			    		$cPECases->setCodIdiomaIso2($lPPECases->fields['codIdiomaIso2']);
		       			break;
		       		}
					$lPPECases->MoveNext();
				}
			}
       		$lPPECases->MoveNext();
	    }

    	$cPECases= $cPECasesDB->readEntidad($cPECases);
    	if ($cPECases->getCodigo() == ""){
    		echo  "Ha ocurrido un error, No se ha especificado el código de ejercicio de e-Cases.";
			exit;
    	}
    	//Buscamos la simulación
    	$sSQL= "SELECT * FROM simulacion WHERE nombre = " . $connECases->qstr($cPECases->getCodigo(), false);
		$rsEjercicioEcase = $connECases->Execute($sSQL);
		$_ClientECases = "";
		$_SimulacionECases = "";
//		echo "<br />sSQL::" . $sSQL;
		if ($rsEjercicioEcase){
			while (!$rsEjercicioEcase->EOF)
			{
//				echo "<br />cliente_id::" . $rsEjercicioEcase->fields['cliente_id'];
//				echo "<br />simulacion_id::" . $rsEjercicioEcase->fields['id'];
				$_ClientECases = $rsEjercicioEcase->fields['cliente_id'];
				$_SimulacionECases = $rsEjercicioEcase->fields['id'];
				$rsEjercicioEcase->MoveNext();
			}
		}else{
			$e = ADODB_Pear_Error();
			echo "<br />ERROR: " . $e->message;
			exit;
		}
		if (empty($_ClientECases) || empty($_SimulacionECases)){
			echo  "Ha ocurrido un error, No se ha detectado el cliente y/o simulación para la simulación de e-cases asignada.";
			exit;
		}
    	//Buscamos al usuario
    	$sSQL= "SELECT * FROM users WHERE email = " . $connECases->qstr($_cEntidadCandidatoTK->getMail(), false) . " AND id_cliente=" . $connECases->qstr($_ClientECases, false);
		$rsUsrEcase = $connECases->Execute($sSQL);
		$_UsrECases = "";
		if ($rsUsrEcase){
			while (!$rsUsrEcase->EOF)
			{
//				echo "<br />user_id::" . $rsUsrEcase->fields['ID'];
				$_UsrECases = $rsUsrEcase->fields['ID'];
				$rsUsrEcase->MoveNext();
			}
		}else{
			$e = ADODB_Pear_Error();
			echo "<br />ERROR: " . $e->message;
			exit;
		}
		if (empty($_UsrECases)){
			echo  "Ha ocurrido un error, No se ha detectado el usuario e-cases.";
			exit;
		}
		//Miramos si ha finalizado
    	$sSQL= "SELECT * FROM simulacion_usuario WHERE id_simulacion = " . $connECases->qstr($_SimulacionECases, false) . " AND id_usuario=" . $connECases->qstr($_UsrECases, false);
		$rsFinEcase = $connECases->Execute($sSQL);
		$_FinECases = "0";
		if ($rsFinEcase){
			while (!$rsFinEcase->EOF)
			{
//				echo "<br />finalizado::" . $rsFinEcase->fields['estado'];
				if ($rsFinEcase->fields['estado'] == "-1"){
					//En e-cases en estado "-1" es FINALIZADO
					$_FinECases="1";
				}
				$rsFinEcase->MoveNext();
			}
		}else{
			$e = ADODB_Pear_Error();
			echo "<br />ERROR: " . $e->message;
			exit;
		}

		if ($_FinECases == "1")
		{
			$lPPECases->MoveFirst();
		    $cRPECasesDB = new Respuestas_pruebasDB($conn);
		    while(!$lPPECases->EOF){
				$cRPECases = new Respuestas_pruebas();

				$cRPECases->setIdPrueba($lPPECases->fields['idPrueba']);
				$cRPECases->setCodIdiomaIso2($lPPECases->fields['codIdiomaIso2']);
				$cRPECases->setIdProceso($_cEntidadCandidatoTK->getIdProceso());
				$cRPECases->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
				$cRPECases->setIdCandidato($_cEntidadCandidatoTK->getIdCandidato());

				$cRPECases = $cRPECasesDB->readEntidad($cRPECases);

	       		if($cRPECases->getFinalizado()== '0' || $cRPECases->getFinalizado()==""){
	       			//Esta prueba es la que hay que finalizar
	       			$cRPECases->setFinalizado("1");
                    $cRPECasesDB->insertar($cRPECases);
	       			$cRPECasesDB->modificar($cRPECases);
	       			$bECases=0;
	       			$_REQUEST["fECases"]=0;
	       			break;
	       		}
	       		$lPPECases->MoveNext();
		    }
		}

	}
	//FIN - Para las pruebas de ECases,
  	$cCandidato = new Candidatos();
	$cCandidatosDB = new CandidatosDB($conn);
	$cProceso_informesDB = new Proceso_informesDB($conn);
	$cProceso_baremosDB = new Proceso_baremosDB($conn);

	$cCandidato  = $_cEntidadCandidatoTK;

    if (empty($_POST['fIdEmpresa'])){
        $_POST['fIdEmpresa'] = $cCandidato->getIdEmpresa();
    }
	$cRespuestasPruebasDB = new Respuestas_pruebasDB($conn);
	$cRespuestasPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cItemsDB = new ItemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
	$bSinFinalizar=false;
	$pru="";
	$idi="";
   	$sMinutos="-1";
   	$sSegundos="-1";
   	$sMinutos2="-1";
	$sSegundos2="-1";

    if(isset($_POST['fFinalizar']) && $_POST['fFinalizar'] !="")
    {
    	//echo "A::" . $_POST['fFinalizar'];
    	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
    	$cItems = new Items();

    	$cItems->setIdPrueba($_POST['fIdPrueba']);
    	$cItems->setIdPruebaHast($_POST['fIdPrueba']);
    	$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
        if ($_POST['fIdPrueba'] == "84"){	//MB CCT
    		$cItems->setTipoItem($cCandidato->getEspecialidadMB());
    	}
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
    	//Si la prueba tiene multiopciones o especiales,
    	//Engañamos diciendo que permite blancos ya que no coincidiran el numero
    	//de items de la prueba con las contestaciones.
    	
    	if ($_POST['fIdPrueba'] == 80){	// 80 -> CUESTIONARIO TRAYECTORIA PROFESIONAL MB
			$cPruebasD->setPermiteBlancos("1");    		
    	} 
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
				if ($_POST['fIdPrueba'] == 32 && $listaRespItems->recordCount() == 247){
					$cPruebasD->setPermiteBlancos("1");
				}
	    	if(($cPruebasD->getPermiteBlancos() == "1") || ($listaRespItems->recordCount() == $listaItems->recordCount()) )
	    	{
	    		$iContestadas24=0;
	    		$k=1;
	    		if ($_POST['fIdPrueba'] == 24 || $_POST['fIdPrueba'] == 83){
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
	    		if(($listaRespItems->recordCount() == $listaItems->recordCount()) && $_POST['fIdPrueba'] == 83 )
	    		{
	    			$iContestadas24=0;
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
	    			//Cambiar Dongels por Cliente/Prueba/Informe
	    			//Miramos si tiene definido dongles por empresa
	    			//5º Miramos el coste
	    			$cInformes_pruebas = new Informes_pruebas_empresas();
	    			$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
	    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
	    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
	    			$cInformes_pruebas->setIdEmpresa($rsProceso_informes->fields['idEmpresa']);

						$sql_IPE = $cInformes_pruebas_empresasDB->readLista($cInformes_pruebas);
						$rsIPE = $conn->Execute($sql_IPE);
	    			if ($rsIPE->NumRows() > 0){
	    				$cInformes_pruebas = $cInformes_pruebas_empresasDB->readEntidad($cInformes_pruebas);
	    			}else {
		    			$cInformes_pruebas = new Informes_pruebas();
		    			$cInformes_pruebas->setIdPrueba($_POST['fIdPrueba']);
		    			$cInformes_pruebas->setCodIdiomaIso2($rsProceso_informes->fields['codIdiomaInforme']);
		    			$cInformes_pruebas->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
							$cInformes_pruebas = $cInformes_pruebasDB->readEntidad($cInformes_pruebas);
		   			}

						//Sacamos los datos del informe para grabarlo
						$cTipos_informes = new Tipos_informes();
						$cTipos_informes->setCodIdiomaIso2($cInformes_pruebas->getCodIdiomaIso2());
						$cTipos_informes->setIdTipoInforme($cInformes_pruebas->getIdTipoInforme());
						$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);

						//$dTotalCoste += $cInformes_pruebas->getTarifa();

						//6º Insertamos por cada informe una línea en Consumo
						$cConsumos = new Consumos();
						$cConsRead = new Consumos();
						$cConsumos->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());
						$cConsRead->setIdEmpresa($cEmpresaConsumo->getIdEmpresa());

						$cConsumos->setIdProceso($cProcesos->getIdProceso());
						$cConsRead->setIdProceso($cProcesos->getIdProceso());

						$cConsumos->setIdCandidato($cCandidato->getIdCandidato());
						$cConsRead->setIdCandidato($cCandidato->getIdCandidato());

						$cConsumos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
						$cConsRead->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

						$cConsumos->setIdPrueba($_POST['fIdPrueba']);
						$cConsRead->setIdPrueba($_POST['fIdPrueba']);

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

						$cConsumos->setUnidades($cInformes_pruebas->getTarifa());
						$cConsumos->setUsuAlta($cCandidato->getIdCandidato());
						$cConsumos->setUsuMod($cCandidato->getIdCandidato());
						//Revisamos si ya se le ha cobrado, si el Candidato actualiza la página, no hay que cobrar dos veces
						$sqlConsumos = $cConsumosDB->readLista($cConsRead);

						$rsConsRead = $conn->Execute($sqlConsumos);
						$iConsRead = $rsConsRead->NumRows();
						if ($iConsRead <= 0)
						{
							$dTotalCoste += $cInformes_pruebas->getTarifa();
							$idConsumo = $cConsumosDB->insertar($cConsumos);
							$sDescuentaMatriz = $cEmpresaConsumo->getDescuentaMatriz();
							if (!empty($sDescuentaMatriz)){
								//Consultamos los datos de la empresa a la que realmente se le descontará
								$cMatrizConsumo = new Empresas();
								$cMatrizConsumoDB = new EmpresasDB($conn);
								$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
								$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
								$sSQL = "UPDATE consumos SET descuentaMatriz=" . $sDescuentaMatriz . ", ";
								$sSQL .= "nomDescuentaMatriz=" . $conn->qstr($cMatrizConsumo->getNombre(), false) . " ";
								$sSQL .= "WHERE ";
								$sSQL .= "idEmpresa=" . $cConsumos->getIdEmpresa() . " AND ";
								$sSQL .= "idProceso=" . $cConsumos->getIdProceso() . " AND ";
								$sSQL .= "idCandidato=" . $cConsumos->getIdCandidato() . " AND ";
								$sSQL .= "codIdiomaIso2='" . $cConsumos->getCodIdiomaIso2() . "' AND ";
								$sSQL .= "idPrueba='" . $cConsumos->getIdPrueba() . "' AND ";
								$sSQL .= "codIdiomaInforme='" . $cConsumos->getCodIdiomaInforme() . "' AND ";
								$sSQL .= "idTipoInforme='" . $cConsumos->getIdTipoInforme() . "' ";
								$conn->Execute($sSQL);
								$sTypeError=date('d/m/Y H:i:s') . " Descontando unidades de la Matriz [" . $sDescuentaMatriz . "] : ";
								$sTypeError.= $sSQL;
								error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
							}
								

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
							$_idBaremo = $cProceso_informes->getIdBaremo();
							$_idBaremo = (empty($_idBaremo)) ? "1" : $_idBaremo;
							$cmdPost = constant("DIR_WS_GESTOR") . 'Informes_candidato.php?MODO=627&fIdTipoInforme=' . $cInformes_pruebas->getIdTipoInforme() . '&fCodIdiomaIso2=' . $cInformes_pruebas->getCodIdiomaIso2() . '&fIdPrueba=' . $_POST['fIdPrueba'] . '&fIdEmpresa=' . $_POST['fIdEmpresa'] . '&fIdProceso=' . $_POST['fIdProceso'] . '&fIdCandidato=' . $_POST['fIdCandidato'] . '&fCodIdiomaIso2Prueba=' . $_POST['fCodIdiomaIso2'] . '&fIdBaremo=' . $_idBaremo;
							$cUtilidades->backgroundPost($cmdPost);
						}else{
							$sTypeError=date('d/m/Y H:i:s') . " Las unidades ya fueron descontadas el día : ";
							$sTypeError.= $rsConsRead->fields['fecAlta'] . " [Emp:" . $cConsRead->getIdEmpresa() . " - Pro:" . $cConsRead->getIdProceso() . " - Can:" . $cConsRead->getIdCandidato() . " - Pru. iso2:" . $cConsRead->getCodIdiomaIso2() . " - Pru:" . $cConsRead->getIdPrueba() . " - inf. iso2:" . $cConsRead->getCodIdiomaInforme() . " - bar:" . $cConsRead->getIdBaremo() . " - concepto:" . $cConsRead->getConcepto() . "]";
							error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						}
	    			$rsProceso_informes->MoveNext();
	    		}
		    	$rsProceso_baremos->MoveNext();
	    	}
				//echo "----->" . $dTotalCoste;
	    	if ($dTotalCoste > 0 ){
	    		//Lo descontamos de la empresa
	    		$sDescuentaMatriz = $cEmpresaConsumo->getDescuentaMatriz();
	    		if (!empty($sDescuentaMatriz)){
	    			//Consultamos los datos de la empresa a la que realmente se le descontará Matriz
	    			$cMatrizConsumo = new Empresas();
	    			$cMatrizConsumoDB = new EmpresasDB($conn);
	    			$cMatrizConsumo->setIdEmpresa($sDescuentaMatriz);
	    			$cMatrizConsumo = $cMatrizConsumoDB->readEntidad($cMatrizConsumo);
	    			$dResto= ($cMatrizConsumo->getDongles() - $dTotalCoste);
	    			$cMatrizConsumo->setDongles($dResto);
	    			$cMatrizConsumoDB->modificar($cMatrizConsumo);
	    			
	    			$sTypeError=date('d/m/Y H:i:s') . " Descontadas [" . $dTotalCoste . "] unidades de la Matriz [" . $sDescuentaMatriz . "] : ";
	    			$sTypeError.= $sSQL;
	    			error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
	    		}else {
		    		$dResto= ($cEmpresaConsumo->getDongles() - $dTotalCoste);
		    		$cEmpresaConsumo->setDongles($dResto);
		    		$cEmpresaConsumoDB->modificar($cEmpresaConsumo);
	    		}
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
	    	//Actualizamos los ids y las Descripciones de la tabla de consumos
	    	$sSQLUPDATE = "UPDATE consumos c, candidatos ca SET c.idSexo=ca.idSexo,c.idEdad=ca.idEdad,c.idFormacion=ca.idFormacion,c.idNivel=ca.idNivel,c.idArea=ca.idArea WHERE ca.idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false) . " AND ca.idProceso=" . $conn->qstr($_POST['fIdProceso'], false) . " AND ca.idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false) . " AND c.idEmpresa=ca.idEmpresa  AND c.idProceso=ca.idProceso AND c.idCandidato=ca.idCandidato;";
	    	$conn->Execute($sSQLUPDATE);
	    	//echo "<br />" . $sSQLUPDATE;
	    	$sSQLUPDATE = "UPDATE consumos c, sexos s SET c.descSexo=s.nombre WHERE c.idSexo=s.idSexo AND s.codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false) . " AND c.idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false) . " AND c.idProceso=" . $conn->qstr($_POST['fIdProceso'], false) . " AND c.idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false) . " AND c.idPrueba=" . $conn->qstr($_POST['fIdPrueba'], false) . ";";
	    	$conn->Execute($sSQLUPDATE);
	    	//echo "<br />" . $sSQLUPDATE;
	    	$sSQLUPDATE = "UPDATE consumos c, edades e SET c.descEdad=e.nombre WHERE c.idEdad=e.idEdad AND e.codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false) . " AND c.idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false) . " AND c.idProceso=" . $conn->qstr($_POST['fIdProceso'], false) . " AND c.idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false) . " AND c.idPrueba=" . $conn->qstr($_POST['fIdPrueba'], false) . ";";
	    	$conn->Execute($sSQLUPDATE);
	    	//echo "<br />" . $sSQLUPDATE;
	    	$sSQLUPDATE = "UPDATE consumos c, formaciones f SET c.descFormacion=f.nombre WHERE c.idFormacion=f.idFormacion AND f.codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false) . " AND c.idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false) . " AND c.idProceso=" . $conn->qstr($_POST['fIdProceso'], false) . " AND c.idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false) . " AND c.idPrueba=" . $conn->qstr($_POST['fIdPrueba'], false) . ";";
	    	$conn->Execute($sSQLUPDATE);
	    	//echo "<br />" . $sSQLUPDATE;
	    	$sSQLUPDATE = "UPDATE consumos c, nivelesjerarquicos n SET c.descNivel=n.nombre WHERE c.idNivel=n.idNivel AND n.codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false) . " AND c.idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false) . " AND c.idProceso=" . $conn->qstr($_POST['fIdProceso'], false) . " AND c.idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false) . " AND c.idPrueba=" . $conn->qstr($_POST['fIdPrueba'], false) . ";";
	    	$conn->Execute($sSQLUPDATE);
	    	//echo "<br />" . $sSQLUPDATE;
	    	$sSQLUPDATE = "UPDATE consumos c, areas a SET c.descArea=a.nombre WHERE c.idArea=a.idArea AND a.codIdiomaIso2=" . $conn->qstr($_POST['fCodIdiomaIso2'], false) . " AND c.idEmpresa=" . $conn->qstr($_POST['fIdEmpresa'], false) . " AND c.idProceso=" . $conn->qstr($_POST['fIdProceso'], false) . " AND c.idCandidato=" . $conn->qstr($_POST['fIdCandidato'], false) . " AND c.idPrueba=" . $conn->qstr($_POST['fIdPrueba'], false) . ";";
	    	$conn->Execute($sSQLUPDATE);
	    	//echo "<br />" . $sSQLUPDATE;
	    	
    	}
    }else{
    	//echo "B::" . $_POST['fFinalizar'];
    	if (!$bGame){
    		//echo "C::" . $_POST['fFinalizar'];

            if (isset($_POST['fIdArea']) && $_POST['fIdArea'] != ""){
    		    $cCandidato->setIdArea($_POST['fIdArea']);
            }
            if (isset($_POST['fIdFormacion']) && $_POST['fIdFormacion'] != ""){
    		    $cCandidato->setIdFormacion($_POST['fIdFormacion']);
            }
            if (isset($_POST['fIdSexo']) && $_POST['fIdSexo'] != ""){
    		    $cCandidato->setIdSexo($_POST['fIdSexo']);
            }
            if (isset($_POST['fIdNivel']) && $_POST['fIdNivel'] != ""){
    		    $cCandidato->setIdNivel($_POST['fIdNivel']);
            }
            if (isset($_POST['fIdEdad']) && $_POST['fIdEdad'] != ""){
    		    $cCandidato->setIdEdad($_POST['fIdEdad']);
            }
            if (isset($_POST['fTelefono']) && $_POST['fTelefono'] != ""){
    		    $cCandidato->setTelefono($_POST['fTelefono']);
            }
            if (isset($_POST['fNombre']) && $_POST['fNombre'] != ""){
    		    $cCandidato->setNombre($_POST['fNombre']);
            }
            if (isset($_POST['fApellido1']) && $_POST['fApellido1'] != ""){
    		    $cCandidato->setApellido1($_POST['fApellido1']);
            }
            if (isset($_POST['fApellido2']) && $_POST['fApellido2'] != ""){
            	$cCandidato->setApellido2($_POST['fApellido2']);
            }
            if (isset($_POST['fMailCan']) && $_POST['fMailCan'] != ""){
    		    $cCandidato->setMail($_POST['fMailCan']);
            }
            if (isset($_POST['fFechaNacimiento']) && $_POST['fFechaNacimiento'] != ""){
            	$cCandidato->setFechaNacimiento($_POST['fFechaNacimiento']);
            }
            if (isset($_POST['fConcesionMB']) && $_POST['fConcesionMB'] != ""){
            	$cCandidato->setConcesionMB($_POST['fConcesionMB']);
            }
            if (isset($_POST['fBaseMB']) && $_POST['fBaseMB'] != ""){
            	$cCandidato->setBaseMB($_POST['fBaseMB']);
            }
            if (isset($_POST['fNifCan']) && $_POST['fNifCan'] != ""){
            	$cCandidato->setDni($_POST['fNifCan']);
            }

            if (isset($_POST['fPuestoEvaluar']) && $_POST['fPuestoEvaluar'] != ""){
            	$cCandidato->setPuestoEvaluar($_POST['fPuestoEvaluar']);
            }
            if (isset($_POST['fResponsableDirecto']) && $_POST['fResponsableDirecto'] != ""){
            	$cCandidato->setResponsableDirecto($_POST['fResponsableDirecto']);
            }
            if (isset($_POST['fCategoriaForjanor']) && $_POST['fCategoriaForjanor'] != ""){
            	$cCandidato->setCategoriaForjanor($_POST['fCategoriaForjanor']);
            }
            
    	    if (isset($_POST['fSectorMB']) && $_POST['fSectorMB'] != ""){
    	    	$sSectorMB = "";
    	    	if (is_array($_POST['fSectorMB'])){
    	    		$sSectorMB = implode(",", $_POST['fSectorMB']);
    	    	}else{
    	    		$sSectorMB = $_POST['fSectorMB'];
    	    	}
    		    $cCandidato->setSectorMB($sSectorMB);
            }
    	    if (isset($_POST['fEspecialidadMB']) && $_POST['fEspecialidadMB'] != ""){
    	    	$sEspecialidadMB = "";
    	    	if (is_array($_POST['fEspecialidadMB'])){
    	    		$sEspecialidadMB = implode(",", $_POST['fEspecialidadMB']);
    	    	}else{
    	    		$sEspecialidadMB = $_POST['fEspecialidadMB'];
    	    	}
    		    $cCandidato->setEspecialidadMB($sEspecialidadMB);
            }
            
    	    if (!empty($_POST['fNivelConocimientoMB'])){
    	    	$sNivelConocimientoMB = "";
    	    	if (is_array($_POST['fNivelConocimientoMB'])){
    	    		$sNivelConocimientoMB = implode(",", $_POST['fNivelConocimientoMB']);
    	    	}else{
    	    		$sNivelConocimientoMB = $_POST['fNivelConocimientoMB'];
    	    	}
    		    $cCandidato->setNivelConocimientoMB($sNivelConocimientoMB);
            }
            
    	    if ($cCandidato->getIdCandidato() != ""){
      		    $cCandidatosDB->modificar($cCandidato);
            }else{
                echo(constant("ERR") . " C not found.");
                exit;
            }
    	}else{
    		//echo "D::" . $_POST['fFinalizar'];
    		?>
    		<script language="javascript" type="text/javascript">
			//<![CDATA[
				self.close();
			//]]>
			</script>

    		<?php
    		//echo "ES UN GAME FLASH";exit;
    	}
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
    {
    	//echo "E::" . $_POST['fFinalizar'];
    	while(!$listaProcesosPruebas->EOF)
		{
			//echo "F::" . $_POST['fFinalizar'];
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
					//echo "G::" . $_POST['fFinalizar'];
					//Insertamos la bateria en la tabla
					$sIdiomaPrueba = 'es';
					$sIdiomaInforme = 'es';
					$sIdTipoInforme = '1';
					$sIdBaremo = '1';
					$sPruebaV = $aAleatoriasV[array_rand($aAleatoriasV)];
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaV);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("1");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

					$cProceso_informes = new Proceso_informes();
					$cProceso_informes->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_informes->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					//Le seteamos la prueba asignada aleatoriamente
					$cProceso_informes->setIdPrueba($sPruebaV);
					$cProceso_informes->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_informes->setCodIdiomaInforme($sIdiomaInforme);
					$cProceso_informes->setIdTipoInforme($sIdTipoInforme);
					$cProceso_informes->setIdBaremo($sIdBaremo);
					//La guardamos en Procesos informes
					$cProceso_informesDB->insertar($cProceso_informes);

					$cProceso_baremos = new Proceso_baremos();
			    	$cProceso_baremos->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
			    	$cProceso_baremos->setIdProceso($listaProcesosPruebas->fields['idProceso']);
			    	$cProceso_baremos->setCodIdiomaIso2($sIdiomaPrueba);
			    	$cProceso_baremos->setIdBaremo($sIdBaremo);
			    	$cProceso_baremos->setIdPrueba($sPruebaV);
			    	$cProceso_baremosDB->insertar($cProceso_baremos);

					$sIdiomaPrueba = 'es';
					$sIdiomaInforme = 'es';
					$sIdTipoInforme = '1';
					$sIdBaremo = '1';
					$sPruebaN = $aAleatoriasN[array_rand($aAleatoriasN)];
					$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					$cProceso_pruebas_candidato->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_pruebas_candidato->setIdPrueba($sPruebaN);
					$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
					$cProceso_pruebas_candidato->setOrden("2");
					$cProceso_pruebas_candidato->setUsuAlta(0);
					$cProceso_pruebas_candidato->setUsuMod(0);
					$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

					$cProceso_informes = new Proceso_informes();
					$cProceso_informes->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_informes->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					//Le seteamos la prueba asignada aleatoriamente
					$cProceso_informes->setIdPrueba($sPruebaN);
					$cProceso_informes->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_informes->setCodIdiomaInforme($sIdiomaInforme);
					$cProceso_informes->setIdTipoInforme($sIdTipoInforme);
					$cProceso_informes->setIdBaremo($sIdBaremo);
					//La guardamos en Procesos informes
					$cProceso_informesDB->insertar($cProceso_informes);

					$cProceso_baremos = new Proceso_baremos();
			    	$cProceso_baremos->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
			    	$cProceso_baremos->setIdProceso($listaProcesosPruebas->fields['idProceso']);
			    	$cProceso_baremos->setCodIdiomaIso2($sIdiomaPrueba);
			    	$cProceso_baremos->setIdBaremo($sIdBaremo);
			    	$cProceso_baremos->setIdPrueba($sPruebaN);
			    	$cProceso_baremosDB->insertar($cProceso_baremos);

					$sIdiomaPrueba = 'es';
					$sIdiomaInforme = 'es';
					$sIdTipoInforme = '1';
					$sIdBaremo = '1';
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

					$cProceso_informes = new Proceso_informes();
					$cProceso_informes->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_informes->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					//Le seteamos la prueba asignada aleatoriamente
					$cProceso_informes->setIdPrueba($sPruebaL);
					$cProceso_informes->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_informes->setCodIdiomaInforme($sIdiomaInforme);
					$cProceso_informes->setIdTipoInforme($sIdTipoInforme);
					$cProceso_informes->setIdBaremo($sIdBaremo);
					//La guardamos en Procesos informes
					$cProceso_informesDB->insertar($cProceso_informes);

					$cProceso_baremos = new Proceso_baremos();
			    	$cProceso_baremos->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
			    	$cProceso_baremos->setIdProceso($listaProcesosPruebas->fields['idProceso']);
			    	$cProceso_baremos->setCodIdiomaIso2($sIdiomaPrueba);
			    	$cProceso_baremos->setIdBaremo($sIdBaremo);
			    	$cProceso_baremos->setIdPrueba($sPruebaL);
			    	$cProceso_baremosDB->insertar($cProceso_baremos);

					$sIdiomaPrueba = 'en';
					$sIdiomaInforme = 'es';
					$sIdTipoInforme = '1';
					$sIdBaremo = '1';
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

					$cProceso_informes = new Proceso_informes();
					$cProceso_informes->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_informes->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					//Le seteamos la prueba asignada aleatoriamente
					$cProceso_informes->setIdPrueba($sPruebaI);
					$cProceso_informes->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_informes->setCodIdiomaInforme($sIdiomaInforme);
					$cProceso_informes->setIdTipoInforme($sIdTipoInforme);
					$cProceso_informes->setIdBaremo($sIdBaremo);
					//La guardamos en Procesos informes
					$cProceso_informesDB->insertar($cProceso_informes);

					$cProceso_baremos = new Proceso_baremos();
			    	$cProceso_baremos->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
			    	$cProceso_baremos->setIdProceso($listaProcesosPruebas->fields['idProceso']);
			    	$cProceso_baremos->setCodIdiomaIso2($sIdiomaPrueba);
			    	$cProceso_baremos->setIdBaremo($sIdBaremo);
			    	$cProceso_baremos->setIdPrueba($sPruebaI);
			    	$cProceso_baremosDB->insertar($cProceso_baremos);

			    	$sIdiomaPrueba = 'en';
					$sIdiomaInforme = 'es';
					$sIdTipoInforme = '1';
					$sIdBaremo = '1';
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

					$cProceso_informes = new Proceso_informes();
					$cProceso_informes->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
					$cProceso_informes->setIdProceso($listaProcesosPruebas->fields['idProceso']);
					//Le seteamos la prueba asignada aleatoriamente
					$cProceso_informes->setIdPrueba($sPruebaIL);
					$cProceso_informes->setCodIdiomaIso2($sIdiomaPrueba);
					$cProceso_informes->setCodIdiomaInforme($sIdiomaInforme);
					$cProceso_informes->setIdTipoInforme($sIdTipoInforme);
					$cProceso_informes->setIdBaremo($sIdBaremo);
					//La guardamos en Procesos informes
					$cProceso_informesDB->insertar($cProceso_informes);

					$cProceso_baremos = new Proceso_baremos();
			    	$cProceso_baremos->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
			    	$cProceso_baremos->setIdProceso($listaProcesosPruebas->fields['idProceso']);
			    	$cProceso_baremos->setCodIdiomaIso2($sIdiomaPrueba);
			    	$cProceso_baremos->setIdBaremo($sIdBaremo);
			    	$cProceso_baremos->setIdPrueba($sPruebaIL);
			    	$cProceso_baremosDB->insertar($cProceso_baremos);

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

    ///////////////////////////////////////////////////////////////////////////////
    //// Pruebas aleatorias en general                                         ////
    ///////////////////////////////////////////////////////////////////////////////
    //Sacamos las pruebas que tiene la empresa asignadas como aleatorias

    $aPruebasAleatorias = array();

    //1º Miramos los datos de la empresa
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
	$cEmpresaAleatorias = new Empresas();
	$cEmpresaAleatoriasDB = new EmpresasDB($conn);

	$cEmpresaAleatorias->setIdEmpresa($_POST['fIdEmpresa']);
	$cEmpresaAleatorias = $cEmpresaAleatoriasDB->readEntidad($cEmpresaAleatorias);
	//Pruebas aleatorias
	$sIdsPruebasAleatorias = $cEmpresaAleatorias->getIdsPruebasAleatorias();
	//Pruebas Autorizadas a la empresa
	$sIdsPruebasAutorizadas = $cEmpresaAleatorias->getIdsPruebas();
	if (!empty($sIdsPruebasAleatorias)){
		$aPruebasAleatorias = explode(',', $sIdsPruebasAleatorias);
	}
	$idTipoPrueba = -1;
    $idTipoRazonamiento = -1;
    $idTipoNivel = -1;

	$listaProcesosPruebas->MoveFirst();
    $iListaPruebas = $listaProcesosPruebas->recordCount();

    if($iListaPruebas > 0)
    {
    	//echo "H::" . $_POST['fFinalizar'];
    	$bAleatorias=false;
    	//Saco las pruebas que tien el proceso normal
    	while(!$listaProcesosPruebas->EOF)
		{	if (in_array($listaProcesosPruebas->fields['idPrueba'], $aPruebasAleatorias))
			{
				$bAleatorias=true;
				break;
			}
			$listaProcesosPruebas->MoveNext();
		}
		$listaProcesosPruebas->MoveFirst();
//		 echo "<br >A::" . $sIdsPruebasAleatorias;
//    	 echo "<br >B::" . $iListaPruebas;
//    	 echo "<br >C::" . $bAleatorias;


		if ($bAleatorias)
		{
			//echo "I::" . $_POST['fFinalizar'];
			$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
	    	$cProceso_pruebas_candidatoDB = new Proceso_pruebas_candidatoDB($conn);

			//Miramos si no se le ha asignado previamente una bateria
			$cProceso_pruebas_candidato->setIdEmpresa($cCandidato->getIdEmpresa());
			$cProceso_pruebas_candidato->setIdProceso($_POST['fIdProceso']);
			$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
			$sqlProceso_pruebas_candidato = $cProceso_pruebas_candidatoDB->readLista($cProceso_pruebas_candidato);
			$listaProceso_pruebas_candidato = $conn->Execute($sqlProceso_pruebas_candidato);

			if ($listaProceso_pruebas_candidato->recordCount() <= 0)
			{  	while(!$listaProcesosPruebas->EOF)
				{	if (in_array($listaProcesosPruebas->fields['idPrueba'], $aPruebasAleatorias))
					{
						//Miramos esa prueba que de qué tipo es, razonamiento mide y que nivel
						$cPruebasAleatorias = new Pruebas();
						$cPruebasAleatoriasDB = new PruebasDB($conn);
		    			$cPruebasAleatorias->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
		    			$cPruebasAleatorias->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);

		    			$cPruebasAleatorias= $cPruebasAleatoriasDB->readEntidad($cPruebasAleatorias);
						$idTipoPrueba = $cPruebasAleatorias->getIdTipoPrueba();
		    			$idTipoRazonamiento = $cPruebasAleatorias->getIdTipoRazonamiento();
		    			$idTipoNivel = $cPruebasAleatorias->getIdTipoNivel();
		    			if (empty($idTipoNivel)){
		    				$idTipoNivel="1";	//Operarios
		    			}
		    			//Recogo todas las pruebas que tiene activas la empresa (sIdsPruebasAutorizadas)
		    			//que sean de ese TipoPrueba, Razonamiento, Nivel e idioma.

		    			$sSQLAleatorio = 'SELECT * FROM pruebas
		    							  WHERE idTipoRazonamiento=\'' . $idTipoRazonamiento . '\'
		    							  AND idTipoPrueba=' . $idTipoPrueba . '
		    							  AND idTipoNivel=' . $idTipoNivel . '
		    							  AND codIdiomaIso2=\'' . $listaProcesosPruebas->fields['codIdiomaIso2'] . '\'
		    							  AND idprueba IN (' . $sIdsPruebasAutorizadas . ')';
//		    			echo "<br >" . $sSQLAleatorio;
		    			$listaAleatorios = $conn->Execute($sSQLAleatorio);

		    			if ($listaAleatorios->recordCount() <= 0)
						{
							echo "Ha ocurrido un error en la asignacion de prueba A, contacte con su administrador.";
							exit;
						}
						$sAleatorias= "";
						$sPruebaAleatoria= "";
						$aAleatorias = array();
						//Recojo las pruebas para meterlas en un array
						while(!$listaAleatorios->EOF)
						{
							$sAleatorias.="," . $listaAleatorios->fields['idPrueba'];
							$listaAleatorios->MoveNext();
						}
//						echo "<br >ALEATORIAS::" . $sAleatorias;
						if (!empty($sAleatorias)){
							$sAleatorias = substr($sAleatorias, 1);
							$aAleatorias = explode(',', $sAleatorias);
						}

						$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
		    			$cProceso_pruebas_candidatoDB = new Proceso_pruebas_candidatoDB($conn);

						//Insertamos la bateria en la tabla
						$sPruebaAleatoria = $aAleatorias[array_rand($aAleatorias)];
						$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
						$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
						$cProceso_pruebas_candidato->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);
						$cProceso_pruebas_candidato->setIdPrueba($sPruebaAleatoria);
						$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
						$cProceso_pruebas_candidato->setOrden($listaProcesosPruebas->fields['orden']);
						$cProceso_pruebas_candidato->setUsuAlta(0);
						$cProceso_pruebas_candidato->setUsuMod(0);
						$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);

						$cProceso_informes = new Proceso_informes();
						$cProceso_informes->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
						$cProceso_informes->setIdProceso($listaProcesosPruebas->fields['idProceso']);
						$cProceso_informes->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
						$cProceso_informes->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);
						$sSQLProceso_informes = $cProceso_informesDB->readLista($cProceso_informes);
						$rsProceso_informes = $conn->Execute($sSQLProceso_informes);
						$cProceso_informes->setCodIdiomaInforme($rsProceso_informes->fields['codIdiomaInforme']);
						$cProceso_informes->setIdTipoInforme($rsProceso_informes->fields['idTipoInforme']);
						$cProceso_informes->setIdBaremo($rsProceso_informes->fields['idBaremo']);
						$cProceso_informes = $cProceso_informesDB->readEntidad($cProceso_informes);
						//Le seteamos la prueba asignada aleatoriamente
						$cProceso_informes->setIdPrueba($sPruebaAleatoria);
						//La guardamos en la tabla proceso_informes
						$cProceso_informesDB->insertar($cProceso_informes);

						$cProceso_baremos = new Proceso_baremos();
				    	$cProceso_baremos->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
				    	$cProceso_baremos->setIdProceso($listaProcesosPruebas->fields['idProceso']);
				    	$cProceso_baremos->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);
				    	$cProceso_baremos->setIdBaremo($rsProceso_informes->fields['idBaremo']);
				    	$cProceso_baremos->setIdPrueba($sPruebaAleatoria);
				    	$cProceso_baremosDB->insertar($cProceso_baremos);

					}else{
						//Si no está en array de aleatorias, es una prueba normal que hay que dar de alta
						$sPruebaAleatoria = $aAleatorias[array_rand($aAleatorias)];
						$cProceso_pruebas_candidato->setIdEmpresa($listaProcesosPruebas->fields['idEmpresa']);
						$cProceso_pruebas_candidato->setIdProceso($listaProcesosPruebas->fields['idProceso']);
						$cProceso_pruebas_candidato->setCodIdiomaIso2($listaProcesosPruebas->fields['codIdiomaIso2']);
						$cProceso_pruebas_candidato->setIdPrueba($listaProcesosPruebas->fields['idPrueba']);
						$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
						$cProceso_pruebas_candidato->setOrden($listaProcesosPruebas->fields['orden']);
						$cProceso_pruebas_candidato->setUsuAlta(0);
						$cProceso_pruebas_candidato->setUsuMod(0);
						$cProceso_pruebas_candidatoDB->insertar($cProceso_pruebas_candidato);
					}
					$listaProcesosPruebas->MoveNext();
				}
				$cProceso_pruebas_candidato = new Proceso_pruebas_candidato();
				$cProceso_pruebas_candidato->setIdEmpresa($cCandidato->getIdEmpresa());
		    	$cProceso_pruebas_candidato->setIdProceso($cCandidato->getIdProceso());
		    	$cProceso_pruebas_candidato->setIdCandidato($_POST['fIdCandidato']);
		    	$cProceso_pruebas_candidato->setOrderBy("orden");
				$cProceso_pruebas_candidato->setOrder("ASC");
			}
			$cProceso_pruebas_candidato->setOrderBy("orden");
			$cProceso_pruebas_candidato->setOrder("ASC");
			$sqlProceso_pruebas_candidato = $cProceso_pruebas_candidatoDB->readLista($cProceso_pruebas_candidato);
		    $listaProcesosPruebas = $conn->Execute($sqlProceso_pruebas_candidato);
		    $iListaPruebas = $listaProcesosPruebas->recordCount();
		}
    }
    $listaProcesosPruebas->MoveFirst();
    ///////////////////////////////////////////////////////////////////////////////
    //// FIN Pruebas aleatorias en general                                     ////
    ///////////////////////////////////////////////////////////////////////////////

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
			$mail->SMTPSecure = 'tls';							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';


			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = $mail->Mailer = constant("MAILER");;

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			$mail->From = $cEmpresaFROM->getMail();
			$mail->FromName = $cEmpresaFROM->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $sSubject;
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/apple-overlay.css" type="text/css" />
    <script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.tools.min.js"></script>
<script language="javascript" type="text/javascript">
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
var adWin;
function checkChild() {
	var f=document.forms[0];
	if (adWin.closed) {
		f.action='pruebas.php';
		f.submit();
	} else setTimeout("checkChild()",1);
}
function pan_completa(see){

	var tamanox=window.screen.availWidth;
	var tamanoy=window.screen.availHeight;

	var posicionx=0;
	var posiciony=0;
	var direccion="<?php echo Constant("HTTP_SERVER");?>game.php?see=" + see;
	adWin = window.open("",'ventana','fullscreen=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=0');
	adWin.resizeTo(tamanox,tamanoy);
	adWin.moveTo(posicionx,posiciony);
	adWin.location=direccion;
	adWin.focus();
	checkChild();
}
function checkECases() {
	var f=document.forms[0];
	if (adWin.closed) {
		f.fECases.value=1;
		f.action='pruebas.php';
		f.submit();

	} else setTimeout("checkECases()",1);
}
function pan_ECases(see){


	var tamanox=window.screen.availWidth;
	var tamanoy=window.screen.availHeight;

	var posicionx=0;
	var posiciony=0;
	var direccion="<?php echo Constant("HTTP_SERVER");?>eCases.php?see=" + see;
	adWin = window.open("",'ventana','fullscreen=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=0');
	adWin.resizeTo(tamanox,tamanoy);
	adWin.moveTo(posicionx,posiciony);
	adWin.location=direccion;
	adWin.focus();
	checkECases();
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
			<div style="margin-left:20%;">
	        			<div id="triggers">
							<table cellpadding="5" cellspacing="0" border="1" width="80%;">
								<tr style="background-color:#000;color:#ffffff;font-weight:bold;">
									<td align="center" ><?php echo constant("STR_PRUEBAS");?></td>
									<td align="center"><?php echo constant("STR_TIEMPO_REQUERIDO");?></td>
									<td align="center"><?php echo constant("STR_N_DE_PREGUNTAS");?></td>
									<td align="center"><?php echo constant("STR_ESTADO");?></td>
								</tr>
        			    	<?php
//        			    	$iListaPruebas = $listaProcesosPruebas->recordCount();
        			    	if($iListaPruebas > 0)
        			    	{
        			    		//echo "J::" . $_POST['fFinalizar'];
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
				        			$cItems = new Items();
				        			
				        			$cItems->setIdPrueba($cPruebas->getIdPrueba());
				        			$cItems->setIdPruebaHast($cPruebas->getIdPrueba());
				        			$cItems->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
				        			if ($listaProcesosPruebas->fields['idPrueba'] == "84"){	//MB CCT
				        				$cItems->setTipoItem($cCandidato->getEspecialidadMB());
				        			}
				        			$sqlItems= $cItemsDB->readLista($cItems);
				        			$listaItems = $conn->Execute($sqlItems);
				        			?>
									
							        	<tr>
											<td align="center">
							        			<?php echo $cPruebas->getDescripcion();?>
							        		</td>
								        	<td align="center">
								        		<?php
                            $TeimpoTotal=  $cPruebas->getDuracion();
                            if ($cPruebas->getDuracion2() != ""){
                              $TeimpoTotal+= $cPruebas->getDuracion2();
                            }
                            echo ($TeimpoTotal > 0) ? $TeimpoTotal . " min" : "&infin; min"
                            ?>
								        	</td>
								        	<td align="center">
								        		<?php echo $listaItems->recordCount();?>
								        	</td>
								        	<td align="center">
							        	<?php
							        		if($cRespuestas->getFinalizado()== '0' || $cRespuestas->getFinalizado()==""){
							        			if($iPintadoBoton==0){
							       					switch ($cPruebas->getIdTipoPrueba())
													{
														case "17":	//e-Cases
															$bECases=1;
															if ($cRespuestas->getDescCandidato() != ""){
																//Ha empezado

															}else{
																//NO ha EMPEZADO.
															}
															$sLLAMADA = "ruta=" . constant("HTTP_SERVER") . "&proceso=" . $cCandidato->getIdProceso() . "&prueba=" . $cPruebas->getIdPrueba() . "&usuario=" . $cCandidato->getToken();
								        					?>
								        					<input type="button" class="botones" value="<?php echo constant("STR_COMENZAR");?>" style="vertical-align: text-bottom;margin-bottom: 15px;" onclick="javascript:pan_ECases('<?php echo base64_encode($sLLAMADA);?>');"/>
								        					<?php
															break;

														case "11":	//FLASH o HTML
								        					if ($sMinutos == "-1"){
								        						$sMinutos=$cPruebas->getDuracion();
								        					}
								        					if ($sSegundos == "-1"){
								        						$sSegundos="00";
								        					}
															if ($sMinutos2 == "-1"){
																$sMinutos2=$cPruebas->getDuracion2();
																$sSegundos2="00";
															}
															$sBgcolor = "";
								        					if ($cPruebas->getEstiloOpciones() == ""){
																$sBgcolor=$cPruebas->getEstiloOpciones();
															}
								        					$sPantalla=0;
															$sCampoLibre="";
															$sSubmit = 1;
															$sTK = $cCandidato->getToken();


															$sItemsLlamada = "";
								        					if ($cRespuestas->getDescCandidato() != ""){
								        						//Ha empezado
								        						$sMinutos=$cRespuestas->getMinutos_test();
								        						$sSegundos =$cRespuestas->getSegundos_test();
								        						$sPantalla = $cRespuestas->getPantalla();
																$sMinutos2=$cRespuestas->getMinutos2_test();
																$sSegundos2=$cRespuestas->getSegundos2_test();
																$sCampoLibre=$cRespuestas->getCampolibre();
																$sCampoLibre = str_replace("Â", "", $sCampoLibre);
																$sCampoLibre = str_replace("Ã", "", $sCampoLibre);
								        						//Miramos las respuestas si ha empezado.
																$cRespuestas_pruebas_items = new Respuestas_pruebas_items();
																$cRespuestas_pruebas_items->setIdProceso($cRespuestas->getIdProceso());
																$cRespuestas_pruebas_items->setIdPrueba($cRespuestas->getIdPrueba());
																$cRespuestas_pruebas_items->setIdCandidato($cRespuestas->getIdCandidato());
																$cRespuestas_pruebas_items->setOrderBy("orden");
                                                            	$cRespuestas_pruebas_items->setOrder("ASC");
																$sqlRespuestas_pruebas_items= $cRespuestasPruebasItemsDB->readLista($cRespuestas_pruebas_items);
																$listaRespuestas_pruebas_items = $conn->Execute($sqlRespuestas_pruebas_items);
																if ($listaRespuestas_pruebas_items->RecordCount() > 0){
										        					while(!$listaRespuestas_pruebas_items->EOF){
																		$sItemsLlamada .= "&" . $listaRespuestas_pruebas_items->fields['descItem'] . "=" . $listaRespuestas_pruebas_items->fields['valor'];
																		$listaRespuestas_pruebas_items->MoveNext();
																	}
																}else{
																	$cItems = new Items();
																	$cItems->setIdPrueba($cPruebas->getIdPrueba());
			    													$cItems->setIdPruebaHast($cPruebas->getIdPrueba());
			    													$cItems->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
			    													if ($cPruebas->getIdPrueba() == "84"){	//MB CCT
			    														$cItems->setTipoItem($cCandidato->getEspecialidadMB());
			    													}
			    													$sqlItems= $cItemsDB->readLista($cItems);
																	$listaItems = $conn->Execute($sqlItems);
																	while(!$listaItems->EOF){
																		$sItemsLlamada .= "&" . $listaItems->fields['descripcion'] . "=";
																		$listaItems->MoveNext();
																	}
																}
								        					}else{
																//NO ha EMPEZADO se le pasan la lista de items sin valores.
																$cItems = new Items();
																$cItems->setIdPrueba($cPruebas->getIdPrueba());
		    													$cItems->setIdPruebaHast($cPruebas->getIdPrueba());
		    													$cItems->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
														        if ($cPruebas->getIdPrueba() == "84"){	//MB CCT
    																$cItems->setTipoItem($cCandidato->getEspecialidadMB());
    															}
		    													$sqlItems= $cItemsDB->readLista($cItems);
		    													//echo $sqlItems . "<br />";
																$listaItems = $conn->Execute($sqlItems);
																while(!$listaItems->EOF){
																	$sItemsLlamada .= "&" . $listaItems->fields['descripcion'] . "=";
																	$listaItems->MoveNext();
																}
															}
															$sLLAMADA = "";

															if (!empty($sItemsLlamada)){
																$sLLAMADA = "ruta=" . constant("HTTP_SERVER") . "&proceso=" . $cCandidato->getIdProceso() . "&convocatoria=" . $cCandidato->getIdProceso() . "&sala=1&prueba=" . $cPruebas->getIdPrueba() . "&usuario=" . $cCandidato->getToken() . "&pantalla=" . $sPantalla . "&segundos=" . $sSegundos . "&minutos=" . $sMinutos . "&segundos2=" . $sSegundos2 . "&minutos2=" . $sMinutos2 . "&campolibre=" . $sCampoLibre . $sItemsLlamada;
															}
//															echo str_replace("&", "<br />", $sLLAMADA);

				        					?>
								        					<input type="button" class="botones" value="<?php echo constant("STR_COMENZAR");?>" style="vertical-align: text-bottom;margin-bottom: 15px;" onclick="javascript:pan_completa('<?php echo base64_encode($sLLAMADA);?>');"/>
				        					<?php

								        					break;
							        				default:
					        			?>
								        				<input type="button" class="botones" value="<?php echo constant("STR_COMENZAR");?>" style="vertical-align: text-bottom;margin-bottom: 15px;" onclick="javascript:comienzaprueba('<?php echo $cPruebas->getIdPrueba()?>', '<?php echo $cPruebas->getCodIdiomaIso2()?>');"/>
							        	<?php	 		break;
													}	//END switch
							        				$iPintadoBoton++;
							        			}
								        	}else{
								        		$iPruebasFinalizadas++;
								        ?>
								        		<label> <?php echo constant("STR_FINALIZADO");?><br /></label>
								        <?php
											}
										?>
								        	</td>
							        	</tr>

				        	<?php		$i++;
				        			$listaProcesosPruebas->MoveNext();
				        		}
							}
							?>
							</table>
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
										<h2><?php echo $cPruebas->getDescripcion();?></h2>
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

		    </div>
<?php	if ($iPruebasFinalizadas == $iListaPruebas){	?>
		    <div style="margin-left:49%;padding-top:10px;">
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
	<script language="javascript" type="text/javascript">
		$(function() {
			$("#triggers img[rel]").overlay({effect: 'apple'});
		});
	</script>
<input type="hidden" name="sTKCandidatos" value="<?php echo $cCandidato->getToken();?>" />
<input type="hidden" name="fIdEmpresa" value="<?php echo $cCandidato->getIdEmpresa();?>" />
<input type="hidden" name="fIdProceso" value="<?php echo $cCandidato->getIdProceso();?>" />
<input type="hidden" name="fIdCandidato" value="<?php echo $cCandidato->getIdCandidato();?>" />
<input type="hidden" name="fMail" value="<?php echo $cCandidato->getMail();?>" />
<input type="hidden" name="fNif" value="<?php echo $cCandidato->getDni();?>" />
<input type="hidden" name="fIdPrueba" value="" />
<input type="hidden" name="fCodIdiomaIso2" value="" />
<input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
<input type="hidden" name="fECases" value="" />

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
