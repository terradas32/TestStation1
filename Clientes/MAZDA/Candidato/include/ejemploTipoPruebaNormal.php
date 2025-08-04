<?php
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	
	$cPruebas = new Pruebas();
	$cPruebasDB = new PruebasDB($conn);
    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	
	$cPruebas = $cPruebasDB->readEntidad($cPruebas);
	$sEstiloOpciones = $cPruebas->getEstiloOpciones();
	
while(!$listEjemplos->EOF){
?>
<table cellspacing="0" cellpadding="5" border="0"  width="100%">
	<tr>
		<td class="negro">
			<?php echo $listEjemplos->fields['descripcion'];?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php if($listEjemplos->fields['path1']!=""){?>
				<img align="left" src="<?php echo constant('DIR_WS_GESTOR') . $listEjemplos->fields['path1']?>" />
			<?php }?>
			<?php if($listEjemplos->fields['path2']!=""){?>
				<img align="right" src="<?php echo constant('DIR_WS_GESTOR') . $listEjemplos->fields['path2']?>" />
			<?php }?>
			<?php if($listEjemplos->fields['path3']!=""){?>
				<img align="left" src="<?php echo constant('DIR_WS_GESTOR') . $listEjemplos->fields['path3']?>" />
			<?php }?>
			<?php if($listEjemplos->fields['path4']!=""){?>
				<img align="right" src="<?php echo constant('DIR_WS_GESTOR') . $listEjemplos->fields['path4']?>" />
			<?php }?>
		</td>
	</tr>
	<tr>
		<td class="negrob">
			<?php echo $listEjemplos->fields['enunciado']?>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
				$cOpciones = new Opciones_ejemplos();
				$cOpcionesDB = new Opciones_ejemplosDB($conn);
				$cOpciones->setIdPrueba($listEjemplos->fields['idPrueba']);
				$cOpciones->setCodIdiomaIso2($listEjemplos->fields['codIdiomaIso2']);
				$cOpciones->setIdEjemplo($listEjemplos->fields['idEjemplo']);
				$cOpciones->setBajaLog("0");
				$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
				$listaOpciones = $conn->Execute($sqlOpciones);
			?>
			<ul>
				<?php 
				if($listaOpciones->recordCount()>0)
				{
					while(!$listaOpciones->EOF){
						$sChecked = '';
						if($listEjemplos->fields['correcto'] == $listaOpciones->fields['codigo']){
							$sChecked = 'checked="checked"';
						}
						$sDesc='';
						if ($listaOpciones->fields['codigo'] != ""){
							$sDesc= $listaOpciones->fields['codigo'] . ". ";
						}
						if ($listaOpciones->fields['descripcion'] != ""){
							$sDesc .= $listaOpciones->fields['descripcion'];	
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
						<li class="opciones" <?php echo (!empty($sEstiloOpciones)) ? "style='" . $sEstiloOpciones . "'" : "";?>>
							<input type="radio" <?php echo $sChecked;?> disabled="disabled" name="fIdOpcion<?php echo $listaOpciones->fields['descripcion'];?>" value="" /> 
							<label ><?php echo $sOpcion;?></label>
						</li>
				<?php 		$listaOpciones->MoveNext();
					}
				} ?>	
			</ul>
		</td>
	</tr>
</table>
<?php 
	$listEjemplos->MoveNext();
	}?>