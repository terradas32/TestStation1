<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


ob_start(); 
	require('./include/Configuracion.php');
	if (!isset($_REQUEST["fLang"])){
		$_REQUEST["fLang"] = $_REQUEST["fCodIdiomaIso2"];
	}
	include('include/Idiomas.php');
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
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_itemsDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas_items/Respuestas_pruebas_items.php");
	require_once(constant("DIR_WS_COM") . "Opciones/OpcionesDB.php");
	require_once(constant("DIR_WS_COM") . "Opciones/Opciones.php");
	require_once(constant("DIR_WS_COM") . "Combo.php");


	include_once ('include/conexion.php');

	require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

    $cRespPruebasItemsDB = new Respuestas_pruebas_itemsDB($conn);
	$cPruebasDB = new PruebasDB($conn);
    $cItemsDB = new ItemsDB($conn);


    $cCandidato = new Candidatos();
    $cCandidato  = $_cEntidadCandidatoTK;

    $cProceso_pruebas = new Proceso_pruebas();
    $cProceso_pruebasDB = new Proceso_pruebasDB($conn);


    $cPruebas = new Pruebas();
	$cItemListar = new Items();
    $cItemListar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cItemListar->setIdTipoRazonamiento($_POST['fIdTipoRazonamiento']);
	//$cItemListar->setIndex_tri($_POST['fIndex_tri']);
	$cItemListar->setOrderBy("id");
	$cItemListar->setOrder("ASC");

    $sqlItems = $cItemsDB->readLista($cItemListar);
    $listaItems = $conn->Execute($sqlItems);

    $iTamaniolistaItems = $listaItems->recordCount();

    $cItems = new Items();

    $cPruebas = new Pruebas();

    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

	$cPruebas = $cPruebasDB->readEntidad($cPruebas);

	$sPreguntasPorPagina = $cPruebas->getPreguntasPorPagina();
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();

	$aPreguntasPorPagina = explode("-", $sPreguntasPorPagina);
	$iPreguntasPorPagina = count($aPreguntasPorPagina);
	$bMultiPagina = false;
	if ($iPreguntasPorPagina > 1){
		//Quiere decir que le llegan en formato 5-6-5-5-5-4-6-4 por ejemplo las preguntas por página
		$bMultiPagina = true;
	}else{
		if($sPreguntasPorPagina < 1){
			$sPreguntasPorPagina = 1;
		}
	}
    if ($bMultiPagina){
    	$iPaginas = $iPreguntasPorPagina;	//Contador del array Multipágina
    }else{
    	$iPaginas = $iTamaniolistaItems / $sPreguntasPorPagina;
    	if($iTamaniolistaItems % $sPreguntasPorPagina !=0){
			$iPaginas = intval($iPaginas) + 1;
		}
    }
    /****************************************************************
     * Si se ha pulsado el botón de buscar preguntas sin contestar
     * nos llegará esta variable con un valor de 1 y aquí se buscará
     * la primera pregunta que no tenga nada respondido
     ****************************************************************/
    if(isset($_POST['fBuscaPrimera']) && $_POST['fBuscaPrimera']!="")
	{
    	$cRespBuscar = new Respuestas_pruebas_items();
		$cRespBuscar->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespBuscar->setIdProceso($_POST['fIdProceso']);
		$cRespBuscar->setIdPrueba($_POST['fIdPrueba']);
		$cRespBuscar->setIdCandidato($_POST['fIdCandidato']);
		$cRespBuscar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespBuscar->setOrderBy('orden_tri');
		$cRespBuscar->setOrder('ASC');
		$sqlBusc = $cRespPruebasItemsDB->readLista($cRespBuscar);
		//Creamos una lista con los items respondidos hasta este momento.
		$listaBuscar = $conn->Execute($sqlBusc);
		$i=1;
		$nSinResponder=0;
		$iContestadas24=0;
		//Miramos si hay alguna respuesta

		if($listaBuscar->recordCount() > 0)
		{
			while(!$listaBuscar->EOF){
				if($listaBuscar->fields['orden'] != $i){
					//$cItems->setOrden($i);
					break;
				}

				$i++;
				$listaBuscar->MoveNext();
			}
			//Miramos las que faltan por responder restando los items que tiene esa prueba
			// y el recuento de la lista de preguntas contestadas.
			$iSinResponder= $iTamaniolistaItems - $listaBuscar->recordCount();

			if($i > 1)
			{
				//Si hay más de una respuesta
				if($iSinResponder <= 0 )
				{
					if ($listaBuscar->fields['idPrueba'] == 10 ||
						$listaBuscar->fields['idPrueba'] == 12 ||
						$listaBuscar->fields['idPrueba'] == 106 ||
						$listaBuscar->fields['idPrueba'] == 128 ||
						$listaBuscar->fields['idPrueba'] == 97 ||
						$listaBuscar->fields['idPrueba'] == 13 ||
						$listaBuscar->fields['idPrueba'] == 24 ||
						$listaBuscar->fields['idPrueba'] == 105 ||
						$listaBuscar->fields['idPrueba'] == 33 ||
						$listaBuscar->fields['idPrueba'] == 39 ||
						$listaBuscar->fields['idPrueba'] == 49)
					{

							if ($nSinResponder > 0){
								$iSel=0;
								$iInicio=1;
								$iFinal=$sPreguntasPorPagina;
								for($iCont=1;$iCont<=$iPaginas;$iCont++){
									if($iInicio<= $i && $i <= $iFinal){
										$iSel=$iInicio;
										break;
									}
									$iInicio= $iInicio+$sPreguntasPorPagina;
									$iFinal= $iFinal+$sPreguntasPorPagina;
								}
								//$cItems->setOrden($iSel);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
									document.forms[0].fOrden.value=<?php echo $iSel?>;
									ocultomuestro(0,0,1);
								</script>
						<?php
							}else{
							//$cItems->setOrden(($i-$sPreguntasPorPagina));?>
							<script>
								document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
								document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
								document.forms[0].fOrden.value=<?php echo $i?>;
								ocultomuestro(0,0,1);
							</script>
							<?php
							}
					}else{

						if ($nSinResponder > 0)
						{
								$iSel=0;
								$iInicio=1;
								$iFinal=$sPreguntasPorPagina;
								for($iCont=1;$iCont<=$iPaginas;$iCont++){
									if($iInicio<= $i && $i <= $iFinal){
										$iSel=$iInicio;
										break;
									}
									$iInicio= $iInicio+$sPreguntasPorPagina;
									$iFinal= $iFinal+$sPreguntasPorPagina;
								}
								//$cItems->setOrden($iSel);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
									document.forms[0].fOrden.value=<?php echo $iSel?>;
									ocultomuestro(0,0,1);
								</script>
						<?php
						}else{
							//$cItems->setOrden(($i-$sPreguntasPorPagina));?>
							<script>
								document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
								document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
								document.forms[0].fOrden.value=<?php echo $i?>;
								ocultomuestro(0,0,1);
							</script>
							<?php
							}
						}
				}else{
					if($i == $iTamaniolistaItems){
						if ($listaBuscar->fields['idPrueba'] == 10 ||
							$listaBuscar->fields['idPrueba'] == 12 ||
							$listaBuscar->fields['idPrueba'] == 106 ||
							$listaBuscar->fields['idPrueba'] == 128 ||
							$listaBuscar->fields['idPrueba'] == 97 ||
							$listaBuscar->fields['idPrueba'] == 13 ||
							$listaBuscar->fields['idPrueba'] == 24 ||
							$listaBuscar->fields['idPrueba'] == 105 ||
							$listaBuscar->fields['idPrueba'] == 33 ||
							$listaBuscar->fields['idPrueba'] == 39 ||
							$listaBuscar->fields['idPrueba'] == 49){

							if ($nSinResponder > 0){

									$iSel=0;
									$iInicio=1;
									$iFinal=$sPreguntasPorPagina;
									for($iCont=1;$iCont<=$iPaginas;$iCont++){
										if($iInicio<= $i && $i <= $iFinal){
											$iSel=$iInicio;
											break;
										}
										$iInicio= $iInicio+$sPreguntasPorPagina;
										$iFinal= $iFinal+$sPreguntasPorPagina;
									}
									//$cItems->setOrden($iSel);?>
									<script>
										document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
										document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
										document.forms[0].fOrden.value=<?php echo $iSel?>;
										ocultomuestro(0,1,0);
									</script>
							<?php
								}else{
								//$sResto =  $iTamaniolistaItems % $sPreguntasPorPagina;
								//$cItems->setOrden(($i-$sPreguntasPorPagina)+1);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
									document.forms[0].fOrden.value=<?php echo $i?>;
									ocultomuestro(0,0,1);
								</script>
								<?php
								}
						}else{
								//$sResto =  $iTamaniolistaItems % $sPreguntasPorPagina;
								//$cItems->setOrden(($i-$sPreguntasPorPagina)+1);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
									document.forms[0].fOrden.value=<?php echo $i?>;
									ocultomuestro(0,0,1);
								</script>
					<?php	}
					}else{
						$iSel=0;
						$iInicio=1;
						if ($bMultiPagina){
							$iFinal=$iPaginas;
						}else{
							$iFinal=$sPreguntasPorPagina;
						}
						for($iCont=1;$iCont<=$iPaginas;$iCont++){
							if($iInicio<= $i && $i <= $iFinal){
								$iSel=$iInicio;
								break;
							}
							if ($bMultiPagina){
								$iInicio= $iInicio+$aPreguntasPorPagina[$iCont-1];
								$iFinal= $iFinal+$aPreguntasPorPagina[$iCont-1];
							}else {
								$iInicio= $iInicio+$sPreguntasPorPagina;
								$iFinal= $iFinal+$sPreguntasPorPagina;
							}
						}
						//$cItems->setOrden($iSel);?>
						<script>
							document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
							document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
							document.forms[0].fOrden.value=<?php echo $iSel?>;
						<?php
							if ($_POST['fIdPrueba'] == 11 && $iSinResponder == 2){
						?>
								ocultomuestro(0,0,1);
						<?php
							}else{
								if ((($_POST['fIdPrueba'] == 24) || ($_POST['fIdPrueba'] == 105)) && $iSinResponder == 3){
						?>
									ocultomuestro(0,0,1);
						<?php	}else {?>
									ocultomuestro(0,1,0);
						<?php
								}
								//if (($i + $iSinResponder) >= $iTamaniolistaItems)
								//{
						?>
								//	ocultomuestro(0,0,1);
						<?php
								//}
							}
						?>
						</script>
				<?php	}
				}
			}else{
				//Si sólo hay una respuesta.
				//$cItems->setOrden("$i");?>
				<script>
					document.forms[0].fPreguntas.selectedIndex = <?php echo $i?>-1;
					document.forms[0].fPaginaSel.value=<?php echo $i?>;
					document.forms[0].fOrden.value=<?php echo $i?>;
					ocultomuestro(0,1,0);
				</script>
			<?php }

		}else{
			//No ha empezado a responder
			$sOcultomuestro = "ocultomuestro(0,1,0);";
			?>
			<script>
				document.forms[0].fOrden.value= 1;
				document.forms[0].fPaginaSel.value=1;
				document.forms[0].fPreguntas.selectedIndex = 0;
				<?php echo $sOcultomuestro;?>
			</script>
<?php	}
    }else{
		if(isset($_POST['fOrden'])){
			if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
				//Ya hemos contestado items, miramos el siguiente
				//Puede ser de los 3 iniciales o llamar a R
				$sTRIError= "";
				$extension=".csv";
				$filename = constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . "imgItems/" . $cPruebas->getCodIdiomaIso2() . '_' . $cPruebas->getIdTipoRazonamiento() . $extension;				
				$sIndex_tri = "";
				//Miramos Si hay que llamar o ya hemos llamado al script de R
				$sSQL = 'SELECT * FROM tri_init_items WHERE
				idEmpresa   = ' . $_POST['fIdEmpresa'] . 
				' AND idProceso   = ' . $_POST['fIdProceso'] .
				' AND idCandidato = ' . $_POST['fIdCandidato'] .
				' AND idPrueba    = ' . $_POST['fIdPrueba'] . ' ORDER BY orden ASC';

				$rsiniciado_tri = $conn->Execute($sSQL);
				if ($rsiniciado_tri->recordCount() <= 0) {
					echo "Error iniciando TEST, salga y vuelva a entrar.";
					exit;
				}else{
					//Ya tenemos asignados items, sacamos el siguiente no contestado.
					$items_contestados=0;
					while (!$rsiniciado_tri->EOF)
					{
						//Miramos si ha sido sido contestado
						$sSQL = 'SELECT * FROM respuestas_pruebas_items WHERE
							idEmpresa   = ' . $_POST['fIdEmpresa'] . 
							' AND idProceso   = ' . $_POST['fIdProceso'] .
							' AND idCandidato = ' . $_POST['fIdCandidato'] .
							' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
							' AND id_tri    = ' . $rsiniciado_tri->fields['id_tri'] . '';
						$rsRPI = $conn->Execute($sSQL);
						if ($rsRPI->recordCount() <= 0){
							//primero sin contestar
							$cItemListar->setIndex_tri($rsiniciado_tri->fields['index_tri']);
							$sIndex_tri = $rsiniciado_tri->fields['index_tri'];
							$_POST["fIndex_tri"] = $sIndex_tri;
							break;
						}else{
							//Sí lo ha contestado 
							$items_contestados++;
						}
						$rsiniciado_tri->MoveNext();
					}
					if ($items_contestados >= 3 && empty($sIndex_tri) )
					{
						// Ha contestado los iniciales y NO tiene ninguno pendiente de contestar.
						// Hay que llamar al script R nextItem.r 
						$num_preguntas_max = $cPruebas->getNum_preguntas_max_tri();
						
						//#[1] -> Nombre del fichero generado con el Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI
						//#se sustituye por el nombre de fichero ejemplo: pathTo "2.csv" para Verbal
						//#[2] -> in_respuestas - Array de aciertos y errores del tipo [1,1,0,1,0,0,1] - aciertos = 1 y fallos = 0
						//#[3] -> in_respuestas_index - Array de indice de respuestas de la matriz del tipo [2,14,20,31,40,35,27] (fila del csv o Excel)
						//#[4] -> num_preguntas_max número de preguntas máximas de la prueba para TRI.
						//#[5] -> tipo_razonamiento tipo de razonamiento de la prueba, Num´rico Verbal etc
						//Sacamos todos los contestados en orden_tri
						$sSQL = 'SELECT * FROM respuestas_pruebas_items WHERE
						idEmpresa   = ' . $_POST['fIdEmpresa'] . 
						' AND idProceso   = ' . $_POST['fIdProceso'] .
						' AND idCandidato = ' . $_POST['fIdCandidato'] .
						' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
						' ORDER BY orden_tri ASC'  
						;
						$rsRPI = $conn->Execute($sSQL);

						$response = array();
						$aParams = array();
						$tipo_razonamiento = $cPruebas->getIdTipoRazonamiento();
						$in_respuestas = "";
						$in_respuestas_index = "";
						while (!$rsRPI->EOF) {
							$in_respuestas .= "," . $rsRPI->fields['valor'];
							$in_respuestas_index .= "," . $rsRPI->fields['index_tri'];
							$rsRPI->MoveNext();
						}
						if (!empty($in_respuestas)){
							$in_respuestas = substr($in_respuestas, 1);
						}
						if (!empty($in_respuestas_index)){
							$in_respuestas_index = substr($in_respuestas_index, 1);
						}
						$aParams[0] = "\"" . $filename . "\"";
						$aParams[1] = $in_respuestas;
						$aParams[2] = $in_respuestas_index;
						$aParams[3] = $num_preguntas_max;
						$aParams[4] = $tipo_razonamiento;
						$sRcommand = "Rscript " . constant("DIR_FS_DOCUMENT_ROOT") . "TRI/nextItem.r " . implode(" ", $aParams);
						exec($sRcommand, $response);
						$sResponse = implode(",", $response);
						$findme = '$item';
						$pos = strpos($sResponse, $findme);
						if ($pos === false){
							//Guardamos la nota en respuestas_pruebas
							//y finalizamos la prueba
							$nota = str_replace("[1] ", "", trim($sResponse));
							$nota = str_replace("[1]", "", trim($nota));
							$SQL = "UPDATE respuestas_pruebas ";
							$SQL .= "SET nota_tri= " . $nota . " ";
							$SQL .= "WHERE idEmpresa= " . $_POST['fIdEmpresa'] . " ";
							$SQL .= "AND idProceso= " . $_POST['fIdProceso'] . " ";
							$SQL .= "AND idCandidato= " . $_POST['fIdCandidato'] . " ";
							$SQL .= "AND idPrueba= " . $_POST['fIdPrueba'] . " ";
							$rs = $conn->Execute($SQL);
							$sFinalizaTRI = '
							$(function (){
								terminarTRI();
							 });
							 ';
						}else{
							$sItems = str_replace("[1] ", "", trim($response[1]));
							$sItems = str_replace("[1]", "", trim($sItems));
							if (!is_numeric($sItems)){
								$sTRIError .= "<br /><b>Error cargando siguiente pregunta [" . $sResponse . "] \"R\" tipo: ". basename($filename, $extension) . "</b>";
								$sTRIError .= "<br />";
								$sTRIError .= "<br />";
								$sTRIError .= "<br />Contacte con quien le ha proporcionado el acceso.";
								echo $sTRIError;
								exit;
							}else{
								$aItems = explode(",",$sItems);
								//Guardamos el item en la tabla temporal tri_init_items
								for ($i=0, $max = sizeof($aItems); $i < $max; $i++){
									$oItemL = new Items();
									$oItemL->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
									$oItemL->setIdTipoRazonamiento($cPruebas->getIdTipoRazonamiento());
									$oItemL->setIndex_tri($aItems[$i]);
									$sqlItemL = $cItemsDB->readLista($oItemL);
									$rsItem = $conn->Execute($sqlItemL);
									$id_item="";	
									while (!$rsItem->EOF) {
										$id_item = $rsItem->fields['id'];
										$rsItem->MoveNext();
									}
									$sSQL = 'SELECT max(orden)+1 AS orden FROM tri_init_items WHERE
									idEmpresa   = ' . $_POST['fIdEmpresa'] . 
									' AND idProceso   = ' . $_POST['fIdProceso'] .
									' AND idCandidato = ' . $_POST['fIdCandidato'] .
									' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
									' '  
									;
									$rsOrden = $conn->Execute($sSQL);
									$orden="";	
									while (!$rsOrden->EOF) {
										$orden = $rsOrden->fields['orden'];
										$rsOrden->MoveNext();
									}
									$sSQL = 'INSERT INTO tri_init_items (
									idEmpresa,
									idProceso,
									idCandidato,
									idPrueba,
									index_tri,
									id_tri,
									orden) VALUES (' . 
									$_POST['fIdEmpresa'] . ',' .
									$_POST['fIdProceso']  . ',' .
									$_POST['fIdCandidato']  . ',' .
									$_POST['fIdPrueba'] . ',' .
									$aItems[$i] . ',' .
									$id_item . ',' .
									$orden . ');
									';
									//Nos guardamos el primer item por el que empezamos
									if ($i == 0){
										$init_items[] = $aItems[$i];
									}
									$conn->Execute($sSQL);
								}
								$sIndex_tri = implode(",", $init_items);
								$_POST["fIndex_tri"] = $sIndex_tri;
								$cItemListar->setIndex_tri(implode(",", $init_items));
							}
						}
					}else{
						//echo "<br> Tiene que contestar -> sIndex_tri:: " . $sIndex_tri;
						$_POST["fIndex_tri"] = $sIndex_tri;
					}
				}
//
				
				$iCalculo = 0;
				if ($bMultiPagina){
					$iCalculo = $_POST['fOrden']+$aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
				}else{
					$iCalculo = $_POST['fOrden']+$sPreguntasPorPagina;
				}
				//Miro 
				if($iCalculo > $iTamaniolistaItems){
					//$cItems->setOrden($_POST['fOrden']);?>
					<script>
						ocultomuestro(0,0,1);
					</script>
		<?php	}else{
					//$cItems->setOrden($_POST['fOrden']);?>
					<script>

						ocultomuestro(0,1,0);
					</script>
		<?php	}
			}else{
				//$cItems->setOrden('1');?>
				<script>
					ocultomuestro(0,1,0);
				</script>
			<?php }
		}else{
			//$cItems->setOrden('1');?>
				<script>
					ocultomuestro(0,1,0);
				</script>
		<?php }
    }
    $iLineas = $sPreguntasPorPagina;
    if (empty($_POST["fPaginaSel"])){
    	$_POST["fPaginaSel"]=1;
    }
	if ($bMultiPagina){
		$iLineas = $aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
	}
	//$OrdenHast=($cItems->getOrden() + $iLineas)-1;

	//Al ser pruebas del tipo TRI, no consultamos los items por prueba
	$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cItems->setIdTipoRazonamiento($_POST['fIdTipoRazonamiento']);
	$cItems->setIndex_tri($_POST["fIndex_tri"]);
	$cItems->setOrderBy("id");
	$cItems->setOrder("ASC");

	$sqlItems = $cItemsDB->readLista($cItems);
	$listItems = $conn->Execute($sqlItems);
	$listItems->MoveFirst();

	//Miramos si es de tipo de personalidad(prisma, clp...)
	//Con esto aplicamos posibles plantillas por tipo de prueba
	//1 -->360º
	//2 -->Aptitudes
	//3 -->Competencias
	//4 -->Estilo de Aprendizaje
	//5 -->Inglés
	//9 -->Intereses Profesionales
	//6 -->Motivaciones
	//7 -->Personalidad
	//8 -->Varias
	switch ($cPruebas->getIdTIpoPrueba())
	{
		case "3":	//3 -->Competencias
			include('include/tipoPrueba3.php');
			break;
		case "6":	//6 -->Motivaciones
			include('include/tipoPrueba6.php');
			break;
		case "7":	//7 -->Personalidad
			//personalidad(prisma, clp...)
			include('include/tipoPrueba7.php');
			break;
		case "9":	//9 -->Intereses Profesionales
			//Parecidas en apariencia a personalidad(prisma, clp...)
			//PERO no deja ninguna opción en blanco
			include('include/tipoPrueba9.php');
			break;
		case "10":
			//Prueba de redacción
			include('include/tipoPruebaRedaccion.php');
			break;
		case "14":	//14 -->CUESTIONARIO TRAYECTORIA PROFESIONAL
			include('include/tipoPrueba14.php');
			break;
		case "20":	//20 -->TRI
			include('include/tipoPruebaTRI.php');
			break;
		default:
			include('include/tipoPruebaNormal.php');
			break;
	} // end switch 
?>
<input type="hidden" name="fIdItem" value="<?php $listItems->fields['idItem']?>" />
