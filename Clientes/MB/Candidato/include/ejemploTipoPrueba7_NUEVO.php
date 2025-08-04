<?php
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	
	$cPruebas = new Pruebas();
	$cPruebasDB = new PruebasDB($conn);
    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	
	$cPruebas = $cPruebasDB->readEntidad($cPruebas);
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();
	
	$cOpciones = new Opciones_ejemplos();
	$cOpcionesDB = new Opciones_ejemplosDB($conn);
	$cOpciones->setIdPrueba($_POST['fIdPrueba']);
	$cOpciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cOpciones->setIdEjemplo(1);
	$cOpciones->setBajaLog("0");
	$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
	$listaOpciones = $conn->Execute($sqlOpciones);
	$i_Opciones = $listaOpciones->recordCount();
?>
<table cellspacing="0" cellpadding="5" border="0"  width="100%">
	<tr>
		<td class="negro">
			<?php echo $listEjemplos->fields['descripcion'];?>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="5" border="0" >
	<tr>
		<td valign="top">
			&nbsp;
		</td>
<?php 
		while(!$listaOpciones->EOF)
		{
?>
		<td valign="top">
			<?php echo $listaOpciones->fields['descripcion'];?>
		</td>
<?php 
			$listaOpciones->MoveNext();
		}
?>
	</tr>	
	<?php 
	while(!$listEjemplos->EOF){
	?>
		<tr>
			<td class="negrob">
				<?php echo $listEjemplos->fields['enunciado']?>
			</td>
			<?php 
 
				$cOpciones = new Opciones_ejemplos();
				$cOpcionesDB = new Opciones_ejemplosDB($conn);
				$cOpciones->setIdPrueba($listEjemplos->fields['idPrueba']);
				$cOpciones->setCodIdiomaIso2($listEjemplos->fields['codIdiomaIso2']);
				$cOpciones->setIdEjemplo($listEjemplos->fields['idEjemplo']);
				$cOpciones->setBajaLog("0");
				$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
				$listaOpciones = $conn->Execute($sqlOpciones);
				if($listaOpciones->recordCount()>0)
				{
					while(!$listaOpciones->EOF)
					{
						$sChecked = '';
						if($listEjemplos->fields['correcto'] == $listaOpciones->fields['codigo']){
							$sChecked = 'checked="checked"';
						}
						$sDesc='';
						if ($listaOpciones->fields['codigo'] != ""){
							$sDesc=$listaOpciones->fields['codigo'] . " ";
						}
						if ($listaOpciones->fields['descripcion'] != ""){
							$sDesc .=" " . $listaOpciones->fields['descripcion'];	
						}		
						$sImg='';
						if ($listaOpciones->fields['pathOpcion'] != ""){
							$sImg = '<img src="' . constant('DIR_WS_GESTOR') . $listaOpciones->fields['pathOpcion'] . '" alt="' . $sDesc . '" title="' . $sDesc . '" />';
						}
						$sOpcion = '';
						if (empty($sImg)){
							$sOpcion = $sDesc;
						}else{
							$sOpcion = $sDesc . $sImg;
						}
					?>
						<td>
							<input type="radio" <?php echo $sChecked;?> disabled="disabled" name="fIdOpcion<?php echo $listaOpciones->fields['descripcion'];?>" value="" id="" />
						</td>
						<?php 	
						$listaOpciones->MoveNext();
					}	//Fin del while
				}	//Fin del if recordCount
				?>			
		</tr>
<?php $listEjemplos->MoveNext();
	}?>
	</table>
<table cellspacing="0" cellpadding="5" border="0"  width="100%">
	<tr>
		<td class="negro">
			<?php
				$listEjemplos->MoveLast(); 
				echo $listEjemplos->fields['descripcion'];
			?>
		</td>
	</tr>
</table>