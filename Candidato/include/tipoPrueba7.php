<?php
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	$sItemsXPagina = $sPreguntasPorPagina;
	$sItemEspecial = 1;
	$aPreguntasPorPagina = explode("-", $sPreguntasPorPagina);
	$iPreguntasPorPagina = count($aPreguntasPorPagina);
    

	if (!empty($_POST["fPaginaSel"]) && $_POST["fPaginaSel"] > 1) {
		if ($iPreguntasPorPagina > 1) {
			$sItemsXPagina = $aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
		} else {
			$sItemsXPagina = $sPreguntasPorPagina;
		}	
		//echo "<br />//-->fPaginaSel::" . $_POST["fPaginaSel"];
		$iPaginaActual=$_POST["fPaginaSel"];
	}else{
		if ($iPreguntasPorPagina > 1) {
			$sItemsXPagina = $aPreguntasPorPagina[$_POST["fPaginaSel"]-1];
			$iPaginaActual = intval($OrdenHast/$aPreguntasPorPagina[$_POST["fPaginaSel"]-1]);
		} else {
			$iPaginaActual = intval($OrdenHast/$sPreguntasPorPagina);
		}
	}
		

//	echo "<br />//-->Pa--::" . $iPaginaActual;
	switch ($_POST['fIdPrueba'])
	{
		case "12":
		case "106":
		case "128":
			if ($iPaginaActual >= 33)
			{
				if ($iPaginaActual == 33){
					//				echo "=============================";
					$OrdenHast=($cItems->getOrden() + 1);
				}else{
					//				echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
					$OrdenHast=($cItems->getOrden() + $iLineas)-1;
				}
				//Esta prueba desde la página 33 este item, sólo presenta Mejor
				$sItemEspecial = 98;
			}
			$cItems->setIdPrueba($_POST['fIdPrueba']);
			$cItems->setIdPruebaHast($_POST['fIdPrueba']);
			$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cItems->setOrden($cItems->getOrden());
			$cItems->setOrdenHast($OrdenHast);
			$cItems->setOrderBy('orden');
			$cItems->setOrder('ASC');

			$sqlItems = $cItemsDB->readLista($cItems);
			//		echo "<br />" . $sqlItems;
			$listItems = $conn->Execute($sqlItems);
			$listItems->MoveFirst();
			break;
		case "97":
			if ($iPaginaActual >= 33)
			{
				if ($iPaginaActual == 33){
					//				echo "=============================";
					$OrdenHast=($cItems->getOrden() + 1);
				}else{
					//				echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
					$OrdenHast=($cItems->getOrden() + $iLineas)-1;
				}
				//Esta prueba desde la página 33 este item, sólo presenta Mejor
				$sItemEspecial = 98;
			}
			$cItems->setIdPrueba($_POST['fIdPrueba']);
			$cItems->setIdPruebaHast($_POST['fIdPrueba']);
			$cItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
			$cItems->setOrden($cItems->getOrden());
			$cItems->setOrdenHast($OrdenHast);
			$cItems->setOrderBy('orden');
			$cItems->setOrder('ASC');

			$sqlItems = $cItemsDB->readLista($cItems);
			//		echo "<br />" . $sqlItems;
			$listItems = $conn->Execute($sqlItems);
			$listItems->MoveFirst();
			break;
	}

	$cPruebas = new Pruebas();
	$cPruebasDB = new PruebasDB($conn);
    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);

	$cPruebas = $cPruebasDB->readEntidad($cPruebas);
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();

	$cOpciones = new Opciones();
	$cOpcionesDB = new OpcionesDB($conn);
	$cOpciones->setIdPrueba($_POST['fIdPrueba']);
	$cOpciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cOpciones->setIdItem($sItemEspecial);
	$cOpciones->setBajaLog("0");
	$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
//	echo "<br />" .  $sqlOpciones;
	$listaOpciones = $conn->Execute($sqlOpciones);
	$i_Opciones = $listaOpciones->recordCount();
	$sOpciones = '';
?>
<table cellspacing="5" cellpadding="0" border="0" width="95%">
		<tr>
			<td class="negrob" colspan="<?php echo $i_Opciones+1;?>">
				<?php echo  mb_strtoupper(constant("STR_PAGINA"), 'UTF-8');?>&nbsp;<?php echo $iPaginaActual;?> <?php echo constant("STR_DE");?> <?php echo $iPaginas?>
				<br />
				<br />
			</td>
		</tr>
		<tr>
			<td class="negrob" colspan="<?php echo $i_Opciones+1;?>" width="100%">
				<?php echo ($listItems->fields['descripcion'] != "")  ? $listItems->fields['descripcion'] : constant("STR_SOY_UNA_PERSONA");?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="90%">
				&nbsp;
			</td>
<?php
		while(!$listaOpciones->EOF)
		{
			$sOpciones .= ',' . $listaOpciones->fields['descripcion'];
		?>
		<td align="center" valign="top" width="5%">
			<?php echo $listaOpciones->fields['descripcion'];?>
		</td>
<?php
			$listaOpciones->MoveNext();
		}
		if (!empty($sOpciones)){
			$sOpciones = substr($sOpciones,1);
		}
?>
		</tr>
	<?php

	while(!$listItems->EOF){

		$cRespuestasPruebasItems = new Respuestas_pruebas_items();
	 	$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
		$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
		$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
		$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespuestasPruebasItems->setIdItem($listItems->fields['idItem']);

		$cRespuestasPruebasItems = $cRespPruebasItemsDB->readEntidad($cRespuestasPruebasItems);?>

		<tr>
			<td class="negrob">
				<?php echo $listItems->fields['enunciado']?>
			</td>
			<?php
				$cOpciones = new Opciones();
				$cOpcionesDB = new OpcionesDB($conn);
				$cOpciones->setIdPrueba($listItems->fields['idPrueba']);
				$cOpciones->setCodIdiomaIso2($listItems->fields['codIdiomaIso2']);
				$cOpciones->setIdItem($listItems->fields['idItem']);
				$cOpciones->setBajaLog("0");
				$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
				$listaOpciones = $conn->Execute($sqlOpciones);

				if($listaOpciones->recordCount()>0){
					$i=1;
					while(!$listaOpciones->EOF){
						$sChecked = '';
					?>

						<td align="center">
						<?php
							if($cRespuestasPruebasItems->getIdOpcion()== $listaOpciones->fields['idOpcion']){
								$sChecked = 'checked="checked"';
							}
							?>
							<input type="radio" <?php echo $sChecked;?> name="fIdOpcion<?php echo $listaOpciones->fields['descripcion'];?>" value="<?php echo $listaOpciones->fields['codigo'] . "@" . $listaOpciones->fields['idOpcion']?>" id="opcion<?php echo $listaOpciones->fields['codigo']. "@" . $listItems->fields['idItem']?>" onclick="javascript:guardarespuestatipo7('<?php echo $listItems->fields['idItem'];?>','<?php echo $listaOpciones->fields['idOpcion']?>','<?php echo $listItems->fields['orden'];?>',<?php echo $sItemsXPagina?>,this,<?php echo $cItems->getOrden()?>,<?php echo ($cItems->getOrden() + $sItemsXPagina)-1?>,'<?php echo $sOpciones;?>');" />
						</td>

				<?php		$i++;
						$listaOpciones->MoveNext();
					}
				} ?>
		</tr>
<?php $listItems->MoveNext();
	}?>
	</table>

<script>
	MathJax.typeset();	
</script>