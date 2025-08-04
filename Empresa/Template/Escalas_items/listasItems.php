<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellpadding="10" cellspacing="10">
	<tr>
		<td>
			<label>Items pendientes de asignar</label>
		</td>
		<td>
			&nbsp;
		</td>
		<td>
			<label>Items asignados</label>
		</td>
	</tr>
	<tr>
		<td>
			<select multiple="multiple" name="fListaPrincipal" size="20" style="width:300px;">
				<?php 
				if($bPinta){
					if($listaItems->recordCount()>0){
					 
						while(!$listaItems->EOF){
						?>
							<option value="<?php echo $listaItems->fields['idItem']?>"><?php echo $listaItems->fields['orden'] . ".- " . $listaItems->fields['enunciado']?></option>
					<?php	$listaItems->MoveNext();
						}
					}
				}?>
			</select>
		</td>
		<td>
			<ul style="margin-left: -30px;">
				<li style="list-style: none;">
					<input type="button" class="botones" name="fAniadir" value=">>>" onclick="javascript:anadir();" />
				</li>
				<li style="list-style: none;margin-top: 20px;">
					<input type="button" class="botones" name="fQuitar" value="&lt;&lt;&lt;" onclick="javascript:quitar();" />
				</li>
				<li style="list-style: none;margin-top: 20px;">
					<input type="button" class="botones" name="fLimpiar" value="Reiniciar" onclick="javascript:limpiar();" />
				</li>
			</ul>	
		</td>
		<td>
			<select multiple="multiple" name="fItems" size="12" style="width:300px;">
			
			<?php 
				if($bPinta){
					if($listaEscalaItems->recordCount()>0){
					 
						while(!$listaEscalaItems->EOF){
							
							$cItem = new Items();
							$cItem->setIdPrueba($listaEscalaItems->fields['idPrueba']);
							$cItem->setIdItem($listaEscalaItems->fields['idItem']);
							$cItem->setCodIdiomaIso2($sLang);
							$cItem = $cItemsDB->readEntidad($cItem);
						?>
							<option value="<?php echo $cItem->getIdItem()?>"><?php echo $cItem->getOrden() . ".- " . $cItem->getEnunciado()?></option>
					
					<?php	$listaEscalaItems->MoveNext();
						}
					}
				}?>
			</select></td>
	</tr>
</table>
		