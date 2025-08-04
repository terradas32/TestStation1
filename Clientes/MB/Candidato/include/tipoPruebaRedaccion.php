<?php	while(!$listItems->EOF){
	
		$cRespuestasPruebasItems = new Respuestas_pruebas_items();
	 	$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
		$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
		$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
		$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
		$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cRespuestasPruebasItems->setIdItem($listItems->fields['idItem']);
		
		$cRespuestasPruebasItems = $cRespPruebasItemsDB->readEntidad($cRespuestasPruebasItems);
		
?>

<table width="60%" border="0" cellpadding="0" cellspacing="0" align="center">
<?php 
if (!$bMultiPagina)
{?>
	<tr>
		<td class="negrob" align="center">
			<?php //echo strtoupper(constant('STR_PREGUNTA') . " " . $listItems->fields['orden'])?>
			<font style="font-family: georgia,arial,verdana; font-size:33pt;font-weight: bold;" ><?php echo $listItems->fields['descripcion'];?></font>
			<br /><br />
		</td>
	</tr>
<?php 
}?>
<?php
if ($listItems->fields['descripcion'] !="")
{
?>
<!-- 
	<tr>
		<td class="negro">
			<?php echo $listItems->fields['descripcion'];?>
		</td>
	</tr>
-->
<?php
}?>
<?php
if ($listItems->fields['path1']!="" || $listItems->fields['path2']!="" ||
	$listItems->fields['path3']!="" || $listItems->fields['path4']!=""){
?>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<?php if($listItems->fields['path1']!=""){?>
							<img src="<?php echo constant('DIR_WS_GESTOR') . $listItems->fields['path1'];?>" />
							<?php }else{?>
							&nbsp;
						<?php }?>
					</td>
					<td><img src="<?php echo constant("DIR_WS_GRAF");?>sp.gif" width="20px" height="20px" border="0" alt="" /></td>
					<td width="50%">
					<?php if($listItems->fields['path2']!=""){?>
							<img src="<?php echo constant('DIR_WS_GESTOR') . $listItems->fields['path2'];?>" />
							<?php }else{?>
							&nbsp;
						<?php }?>
					</td>
				</tr>
				<tr>
					<td width="50%">
					<?php if($listItems->fields['path3']!=""){?>
							<img src="<?php echo constant('DIR_WS_GESTOR') . $listItems->fields['path3'];?>" />
							<?php }else{?>
							&nbsp;
						<?php }?>
					</td>
					<td >&nbsp;</td>
					<td width="50%">	
					<?php if($listItems->fields['path4']!=""){?>
							<img src="<?php echo constant('DIR_WS_GESTOR') . $listItems->fields['path4'];?>" />
							<?php }else{?>
							&nbsp;
						<?php }?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php 
}
?>
	<tr>
		<td >&nbsp;</td>
	</tr>
<?php
if ($listItems->fields['enunciado'] !="")
{
// Orden del reemplazo
$str     = $listItems->fields['enunciado'];
$order   = array("\r\n", "\n", "\r");
$replace = '<br />';
?>
	<tr>
		<td class="negro" style="text-align: justify;">
			<?php echo str_replace($order, $replace, $str);?>
		</td>
	</tr>
<?php 
}
?>
	<tr>
		<td>
			<?php 
				$cOpciones = new Opciones();
				$cOpcionesDB = new OpcionesDB($conn);
				$cOpciones->setIdPrueba($listItems->fields['idPrueba']);
				$cOpciones->setCodIdiomaIso2($listItems->fields['codIdiomaIso2']);
				$cOpciones->setIdItem($listItems->fields['idItem']);
				$cOpciones->setBajaLog("0");
				$sqlOpciones = $cOpcionesDB->readLista($cOpciones);
				$listaOpciones = $conn->Execute($sqlOpciones);
			?>
			<ul>
				<?php 
				if($listaOpciones->recordCount()>0){
					while(!$listaOpciones->EOF)
					{
						$sChecked = '';
						if($cRespuestasPruebasItems->getIdOpcion()== $listaOpciones->fields['idOpcion']){
							$sChecked = 'checked="checked"';
						}
						$sDesc='';
						if ($listaOpciones->fields['codigo'] != ""){
							$sDesc=$listaOpciones->fields['codigo'] . ". ";
						}
						if ($listaOpciones->fields['descripcion'] != ""){
							$sDesc .=$listaOpciones->fields['descripcion'];	
						}		
						$sImg='';
						if ($listaOpciones->fields['pathOpcion'] != ""){
							$sImg = '<img style="display: block;" src="' . constant('DIR_WS_GESTOR') . $listaOpciones->fields['pathOpcion'] . '" alt="' . $sDesc . '" title="' . $sDesc . '" />';
						}
						$sOpcion = '';
						if (empty($sImg)){
							$sOpcion = $sDesc;
						}else{
							$sOpcion = $sDesc;
						}
					?>
						<li class="opciones" <?php echo (!empty($sEstiloOpciones)) ? "style='" . $sEstiloOpciones . "'" : "";?>>
							<?php echo $sImg;?>
							<input type="radio" <?php echo $sChecked;?> name="fIdOpcion<?php echo $listItems->fields['idItem'];?>" value="<?php echo $listaOpciones->fields['codigo'] . "@" . $listaOpciones->fields['idOpcion'];?>" id="opcion<?php echo $listaOpciones->fields['codigo']. "@" . $listItems->fields['idItem'];?>" onfocus="javascript:setFocus(this);" onmouseup="javascript:setFocus(this);" onclick="javascript:permiteBlanco(this);guardarespuesta('<?php echo $listItems->fields['idItem'];?>','<?php echo $listaOpciones->fields['idOpcion'];?>','<?php echo $listItems->fields['orden'];?>',<?php echo $listaOpciones->recordCount()?>);" /> 
							<label for="opcion<?php echo $listaOpciones->fields['codigo']. "@" . $listItems->fields['idItem'];?>"><?php echo $sOpcion;?></label>
						</li>
				
						<?php 	$listaOpciones->MoveNext();
					}
				}
				?>	
			</ul>
		</td>
	</tr>
</table>
<?php 
	$listItems->MoveNext();
	}?>