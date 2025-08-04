<?php	
	require_once(constant("DIR_WS_COM") . "Pruebas/PruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Pruebas/Pruebas.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebasDB.php");
	require_once(constant("DIR_WS_COM") . "Respuestas_pruebas/Respuestas_pruebas.php");
	$cRespuestasPruebasDB = new Respuestas_pruebasDB($conn);
	$cRespuestasPruebas = new Respuestas_pruebas();
	
	$cPruebas = new Pruebas();
	$cPruebasDB = new PruebasDB($conn);
    $cPruebas->setIdPrueba($_POST['fIdPrueba']);
	$cPruebas->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cPruebas = $cPruebasDB->readEntidad($cPruebas);
	
	//Para KPMG Prueba de ingles con tiempo última 10 preguntas (41 - 50)
	$bKPMGInglesPrueba=false;
	$bKPMGIngles=false;
	
    $aKPMGIngles = array(48,56,57);
    if (in_array($cPruebas->getIdPrueba(), $aKPMGIngles)){
    	$bKPMGInglesPrueba=true;
    }
//Viene de cuerpo prueba con el fIndex_tri seteado, devuelve 1 sólo item
while(!$listItems->EOF){
	//Miramos ese item que orden de pregunta tiene en la tabla temporal de TRI
	$sqlTRI= 'SELECT * FROM tri_init_items WHERE 
		idEmpresa   = ' . $_POST['fIdEmpresa'] . 
		' AND idProceso   = ' . $_POST['fIdProceso'] .
		' AND idCandidato = ' . $_POST['fIdCandidato'] .
		' AND idPrueba    = ' . $_POST['fIdPrueba'] . 
		' AND index_tri    = ' . $listItems->fields['index_tri'] .
		' ';
	$rsOrden_tri = $conn->Execute($sqlTRI);
	$pregunta_orden = 0;
	while (!$rsOrden_tri->EOF) {
		$pregunta_orden = $rsOrden_tri->fields['orden'];
		$rsOrden_tri->MoveNext();
	}
	$cRespuestasPruebasItems = new Respuestas_pruebas_items();
	$cRespuestasPruebasItems->setIdProceso($_POST['fIdProceso']);
	$cRespuestasPruebasItems->setIdEmpresa($_POST['fIdEmpresa']);
	$cRespuestasPruebasItems->setIdCandidato($_POST['fIdCandidato']);
	$cRespuestasPruebasItems->setIdPrueba($_POST['fIdPrueba']);
	$cRespuestasPruebasItems->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
	$cRespuestasPruebasItems->setIndex_tri($_POST['fIndex_tri']);
	$cRespuestasPruebasItems->setIdItem($listItems->fields['idItem']);

	// $cRespuestasPruebasItems = $cRespPruebasItemsDB->readEntidad($cRespuestasPruebasItems);
	$cRespuestasPruebasItems = $cRespPruebasItemsDB->readEntidadIndex_tri($cRespuestasPruebasItems);
		
?>

<table width="95%" border="0" cellpadding="0" cellspacing="0">
<?php 
if (!$bMultiPagina)
{?>
	<tr>
		<td class="negrob">
			<?php echo strtoupper(constant('STR_PREGUNTA'));?> <?php echo $pregunta_orden; ?>
			<br /><br />
		</td>
	</tr>
<?php 
}?>
<?php
if ($listItems->fields['descripcion'] !="")
{
?>
	<tr>
		<td class="negro">
			<?php echo $listItems->fields['descripcion'];?>
		</td>
	</tr>
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
?>
	<tr>
		<td class="negrob">
			<?php echo $listItems->fields['enunciado'];?>
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
							<input type="radio" <?php echo $sChecked;?> name="fIdOpcion<?php echo $listItems->fields['id'] ;?>" value="<?php echo $listaOpciones->fields['codigo'] . "@" . $listaOpciones->fields['idOpcion'];?>" id="opcion<?php echo $listaOpciones->fields['codigo']. "@" . $listItems->fields['id'];?>" onfocus="javascript:setFocus(this);" onmouseup="javascript:setFocus(this);" onclick="javascript:permiteBlanco(this);guardarespuesta_tri('<?php echo $listItems->fields['id'];?>','<?php echo $listaOpciones->fields['idOpcion'];?>','<?php echo $listItems->fields['orden'];?>',<?php echo $listaOpciones->recordCount()?>);" /> 
							<label for="opcion<?php echo $listaOpciones->fields['codigo']. "@" . $listItems->fields['id'];?>"><?php echo $sOpcion;?></label>
						</li>
				
				<?php 		$listaOpciones->MoveNext();
					}
				}
				?>	
			</ul>
		</td>
	</tr>
</table>
<?php 
		$listItems->MoveNext();
	}
//Para KPMG Prueba de ingles con tiempo última 10 preguntas (41 - 50)
    	if ($bKPMGIngles)
    	{
	    	$sTime=0;
		    if($cPruebas->getDuracion2() > 0){
				$segundos=$cPruebas->getDuracion2()*60 - $sTime;
			}
			?>
			<script  >
				$('#countdown-retro').text('');
				$(function (){
					$.epiclock('#countdown-retro').toggle();
					$('#countdown-retro2').epiclock({mode: $.epiclock.modes.timer, offset: {seconds: <?php echo $segundos?>}, format: 'x{h} i{m} s{s}'}).bind('timer', function ()
	                {
	                    alert("<?php echo constant("TU_TIEMPO_SE_HA_AGOTADO");?>");
	                    terminarPorTiempo();
	                });
					
				 });
				document.getElementById("divatras").style.display = 'none';
			</script>
			<?php 
    	}
//FIN Para KPMG Prueba de ingles
	?>
				<script  >
				$(function (){
					cargaListening();
				 });
				<?php
				echo $sFinalizaTRI;
				?>
			</script>
	