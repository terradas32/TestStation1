<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>


<table cellspacing="2" cellpadding="0" width="100%" border="0">
<!--  2::cambiado por nacho para que no se vea ni se pueda cambiar el nombre de la plantilla seleccionada -->
	<tr style="display:none">
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
		<td><input type="text" name="fNombreNew" value="<?php echo $cCorreos->getNombre()?>" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_ASUNTO");?>&nbsp;</td>
		<td><input type="text" name="fAsuntoNew" value="<?php echo $cCorreos->getAsunto();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_CUERPO");?>&nbsp;</td>
		<td><textarea cols="1" id="fCuerpoNew" data-id="fCuerpoNew" name="fCuerpoNew" rows="6" class="tinymce obliga"  onchange="javascript:trim(this);"><?php echo $cCorreos->getCuerpo()?></textarea></td>
	</tr>
<!--
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" class="negrob" valign="top"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
		<td><textarea cols="1" name="fDescripcionNew" rows="2" class="cajatexto"  onchange="javascript:trim(this);"><?php echo $cCorreos->getDescripcion()?></textarea></td>
	</tr>
-->
<input type="hidden" name="fDescripcionNew" value="<?php echo $cCorreos->getDescripcion()?>" class="cajatexto"  onchange="javascript:trim(this);" />

	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr>
		<td colspan="3" ><a href="#_" onclick="document.getElementById('litCorr').style.display='block';" ><img alt="<?php echo constant("STR_AYUDA");?>" title="<?php echo constant("STR_AYUDA");?>" src="<?php echo constant('DIR_WS_GRAF');?>help.gif" ></a>
			<div id="litCorr" style="display:none" >
				<table cellspacing="2" cellpadding="0" width="100%" border="0">
					<?php include_once('include/literales_correos.php');?>
				</table>
			</div>
		</td>
	</tr>

<!-- Modificado por nacho para que solo se pueda seleccionar Añadir al proceso -->

	<tr style="display:none">
		<td width="100%" colspan="3">
			<table width="100%" border="0">
				<tr>
<!--				<td width="50"><input type="button" class="botones" id="bid-cancel" value="<?php //echo constant('STR_CANCELAR')?>" onclick="javascript:cancela();"/></td>-->
					<td width="275">
						<select name="fAccion" class="cajatexto">
		  					<option value="" selected><?php echo constant('SLC_OPCION')?></option>
							<option value="1">Añadir al proceso</option>
<!--						<option value="2">Guardar como Plantilla</option>-->
<!--						<option value="3">Guardar como Plantilla y añadir al proceso</option>-->
						</select>
					</td>
					<td><input type="button" class="botones" id="bid-ok" value="<?php echo constant('STR_ACEPTAR')?>" onclick="javascript:guardaplantilla();"/></td>

				</tr>
			</table>
		</td>
	</tr>

<!--
	<tr>
		<td width="100%" colspan="3">
			<table width="100%" border="0">
				<tr>
					<td width="55px">&nbsp;</td>
					<td width="275px">
						<li style="list-style:disc;"><strong class="naranja">Añadir al proceso</strong>:&nbsp;Asigna la plantilla a este proceso.</li>
						<li style="list-style:disc;"><strong class="naranja">Guardar como Plantilla</strong>:&nbsp;Guarda la plantilla sin asignar al proceso para poder seleccionarla en este u otros procesos.</li>
						<li style="list-style:disc;"><strong class="naranja">Guardar como Plantilla y añadir al proceso</strong>:&nbsp;Guarda como plantilla y asigna al proceso.</li>
					</td>
					<td width="50px">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
-->
	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr><td colspan="3" bgcolor="#000000" style="height:1px;"></td></tr>
	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
</table>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
      tinymce.init({
        selector: '.tinymce'
      });

  });
</script>
