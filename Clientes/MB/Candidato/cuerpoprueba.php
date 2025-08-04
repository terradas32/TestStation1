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
    $cItemListar->setIdPrueba($_POST['fIdPrueba']);
    $cItemListar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    $cItemListar->setOrderBy("orden");
    $cItemListar->setOrder("ASC");
	if ($_POST['fIdPrueba'] == "84"){	//MB CCT
		$cItemListar->setTipoItem($cCandidato->getEspecialidadMB());
	}
    
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
//	echo "<br />" . $sPreguntasPorPagina; 
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
//	echo "<br />" . $iPreguntasPorPagina; 
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
    
    if(isset($_POST['fBuscaPrimera']) && $_POST['fBuscaPrimera']!=""){
    	$cRespBuscar = new Respuestas_pruebas_items();
		$cRespBuscar->setIdProceso($_POST['fIdProceso']);
		$cRespBuscar->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespBuscar->setIdCandidato($_POST['fIdCandidato']);
		$cRespBuscar->setIdPrueba($_POST['fIdPrueba']);
		$cRespBuscar->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespBuscar->setOrderBy('idItem');
		$cRespBuscar->setOrder('ASC');
		$sqlBusc = $cRespPruebasItemsDB->readLista($cRespBuscar);
//		echo $sqlBusc;
		//Creamos una lista con los items respondidos hasta este momento.
		
		$listaBuscar = $conn->Execute($sqlBusc);
		
		$i=1;
		$nSinResponder=0;
		$iContestadas24=0;
		//Miramos si hay alguna respuesta
		
		if($listaBuscar->recordCount() > 0){
			
			while(!$listaBuscar->EOF){
				if ($listaBuscar->fields['idPrueba'] == 10 ||
					$listaBuscar->fields['idPrueba'] == 12 ||
					$listaBuscar->fields['idPrueba'] == 97 ||
					$listaBuscar->fields['idPrueba'] == 13 ||
					$listaBuscar->fields['idPrueba'] == 24 ||
					$listaBuscar->fields['idPrueba'] == 33 ||
					$listaBuscar->fields['idPrueba'] == 39 ||
					$listaBuscar->fields['idPrueba'] == 49){
						//Compuebo que hayan pulsado M y Peor y no sólo una opción
						if ($listaBuscar->fields['idOpcion'] !=0){
							$iContestadas24++;
						}
						if ($i % $sPreguntasPorPagina==0){
							if ($iContestadas24 < 2){
								$nSinResponder= $nSinResponder+3;
								break;
							}
							$iContestadas24=0;
						}
				}

				if($listaBuscar->fields['idItem'] != $i){
					$cItems->setOrden($i);
					break;
				}
				
				$i++;
				$listaBuscar->MoveNext();
			}
//echo "iContestadas24::" . $iContestadas24;
			//Miramos las que faltan por responder restando los items que tiene esa prueba
			// y el recuento de la lista de preguntas contestadas.
			$iSinResponder= $iTamaniolistaItems - $listaBuscar->recordCount();

//			echo "<br />" . $iSinResponder;
//			echo "<br />" . $i;
			if($i > 1)
			{
				//Si hay más de una respuesta
//				echo "<br />-*****************-> $nSinResponder:-:" . $nSinResponder;
				if($iSinResponder <= 0 )
				{
//				echo "<br />**iSinResponder:-:" . $iSinResponder;
					if ($listaBuscar->fields['idPrueba'] == 10 ||
						$listaBuscar->fields['idPrueba'] == 12 ||
						$listaBuscar->fields['idPrueba'] == 97 ||
						$listaBuscar->fields['idPrueba'] == 13 ||
						$listaBuscar->fields['idPrueba'] == 24 ||
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
								$cItems->setOrden($iSel);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
									document.forms[0].fOrden.value=<?php echo $iSel?>;
									ocultomuestro(1,0,1);
								</script>
						<?php 
							}else{
//							echo "<br />" . $sResto =  $iTamaniolistaItems % $sPreguntasPorPagina;
							$cItems->setOrden(($i-$sPreguntasPorPagina));?>
							<script>
								document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
								document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
								document.forms[0].fOrden.value=<?php echo $i?>;
								ocultomuestro(1,0,1);
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
								$cItems->setOrden($iSel);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
									document.forms[0].fOrden.value=<?php echo $iSel?>;
									ocultomuestro(1,0,1);
								</script>
						<?php 
						}else{
//							echo "<br />" . $sResto =  $iTamaniolistaItems % $sPreguntasPorPagina;
							$cItems->setOrden(($i-$sPreguntasPorPagina));?>
							<script>
								document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
								document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
								document.forms[0].fOrden.value=<?php echo $i?>;
								ocultomuestro(1,0,1);
							</script>
							<?php 
							}
						}
				}else{
//					echo "<br />**********ELSE:-:";
//					echo "<br />" . $i . " == " . $iTamaniolistaItems;
					if($i == $iTamaniolistaItems){
//						echo "<br />" . $i . " == " . $iTamaniolistaItems;
						if ($listaBuscar->fields['idPrueba'] == 10 ||
							$listaBuscar->fields['idPrueba'] == 12 ||
							$listaBuscar->fields['idPrueba'] == 97 ||
							$listaBuscar->fields['idPrueba'] == 13 ||
							$listaBuscar->fields['idPrueba'] == 24 ||
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
									$cItems->setOrden($iSel);?>
									<script>
										document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
										document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
										document.forms[0].fOrden.value=<?php echo $iSel?>;
										ocultomuestro(1,1,0);
									</script>
							<?php 
								}else{
								//$sResto =  $iTamaniolistaItems % $sPreguntasPorPagina;
								$cItems->setOrden(($i-$sPreguntasPorPagina)+1);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
									document.forms[0].fOrden.value=<?php echo $i?>;
									ocultomuestro(1,0,1);
								</script>
								<?php 
								}
						}else{
								//$sResto =  $iTamaniolistaItems % $sPreguntasPorPagina;
								$cItems->setOrden(($i-$sPreguntasPorPagina)+1);?>
								<script>
									document.forms[0].fPreguntas.selectedIndex = <?php echo $iPaginas?>-1;
									document.forms[0].fPaginaSel.value=<?php echo $iPaginas?>;
									document.forms[0].fOrden.value=<?php echo $i?>;
									ocultomuestro(1,0,1);
								</script>
					<?php	}
					}else{
//						echo "<br />iSinResponder:->>>>>>>>>>>>>>:" . $iSinResponder;
//						echo "<br />nSinResponder:->>>>>>>>>>>>>>:" . $nSinResponder;
//						echo "<br />sPreguntasPorPagina:->>>>>>>>>>>>>>:" . $sPreguntasPorPagina;
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
//						echo "<br />*******iInicior:->>>>>>>>>>>>>>:" . $iInicio;
//						echo "<br />iFinal:->>>>>>>>>>>>>>:" . $iFinal; 
						$cItems->setOrden($iSel);?>
						<script>
							document.forms[0].fPreguntas.selectedIndex = <?php echo $iCont?>-1;
							document.forms[0].fPaginaSel.value=<?php echo $iCont?>;
							document.forms[0].fOrden.value=<?php echo $iSel?>;
						<?php
							if ($_POST['fIdPrueba'] == 11 && $iSinResponder == 2){
//								echo "<br />->>>" . $_POST['fIdPrueba'];
						?>
								ocultomuestro(1,0,1);
						<?php
							}else{
								if ($_POST['fIdPrueba'] == 24 && $iSinResponder == 3){ 
						?>
									ocultomuestro(1,0,1);
						<?php	}else {?>
									ocultomuestro(1,1,0);
						<?php 	
								}
//								if (($i + $iSinResponder) >= $iTamaniolistaItems)
//								{
						?>
//										ocultomuestro(1,0,1);
						<?php 			
//								} 
							}
						?>
						</script>		
				<?php	}
				}
			}else{
				//Si sólo hay una respuesta.
				$cItems->setOrden($i);?>
				<script>
					document.forms[0].fPreguntas.selectedIndex = <?php echo $i?>-1;
					document.forms[0].fPaginaSel.value=<?php echo $i?>;
					document.forms[0].fOrden.value=<?php echo $i?>;
					ocultomuestro(0,1,0);
				</script>
			<?php }
			
		}else{
			$cItems->setOrden('1');
			$sOcultomuestro = "ocultomuestro(0,1,0);";
			if ($cPruebas->getIdTIpoPrueba() == 10){
				if ($iPaginas == 1){
					$sOcultomuestro = "ocultomuestro(0,0,1);";
				}else{
					$sOcultomuestro = "ocultomuestro(0,1,0);";
				}
			}else{ 	
				$sOcultomuestro = "ocultomuestro(0,1,0);";
			}
			?>
			<script>
				document.forms[0].fOrden.value= 1;
				document.forms[0].fPaginaSel.value=1;
				document.forms[0].fPreguntas.selectedIndex = 0;
				<?php echo $sOcultomuestro;?>
			</script>
		<?php }
    }else{
		if(isset($_POST['fOrden'])){
//			echo "Orden : " . $_POST['fOrden'] . "<br />";
//			echo "TamañoLista : " . $iTamaniolistaItems . "<br />";
//			echo "sPreguntasPorPagina : " . $sPreguntasPorPagina . "<br />";
//			echo "fPaginaSel : " . $_POST["fPaginaSel"] . "<br />";
//			echo "sPreguntasPorPagina : " . $aPreguntasPorPagina[$_POST["fPaginaSel"]-1] . "<br />";
			if($_POST['fOrden'] !="" && $_POST['fOrden'] !=1){
				$iCalculo = 0;
				if ($bMultiPagina){
					$iCalculo = $_POST['fOrden']+$aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
//					echo "iCalculo : " . $iCalculo . "<br />";
				}else{
					$iCalculo = $_POST['fOrden']+$sPreguntasPorPagina;
				}
				if($iCalculo > $iTamaniolistaItems){
					$cItems->setOrden($_POST['fOrden']);?>
					<script>
						ocultomuestro(1,0,1);
					</script>
				<?php }else{
					$cItems->setOrden($_POST['fOrden']);?>
					<script>
					
						ocultomuestro(1,1,0);
					</script>		
			<?php	}
			}else{
				$cItems->setOrden('1');?>
				<script>
					ocultomuestro(0,1,0);
				</script>
			<?php }
		}else{
			$cItems->setOrden('1');?>
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
//    echo "Lineas : " . $iLineas;
	$OrdenHast=($cItems->getOrden() + $iLineas)-1;

	$cItems->setIdPrueba($_POST['fIdPrueba']);
    $cItems->setIdPruebaHast($_POST['fIdPrueba']);
	$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cItems->setOrden($cItems->getOrden());
	$cItems->setOrdenHast($OrdenHast);
	$cItems->setOrderBy('orden');
	$cItems->setOrder('ASC');
	if ($_POST['fIdPrueba'] == "84"){	//MB CCT
		$cItems->setTipoItem($cCandidato->getEspecialidadMB());
	}
	$sqlItems = $cItemsDB->readLista($cItems);
//	echo "<br />" . $sqlItems;
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
		default:
			include('include/tipoPruebaNormal.php');
			break;
	} // end switch
?>
<input type="hidden" name="fIdItem" value="<?php $listItems->fields['idItem']?>" />
