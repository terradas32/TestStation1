<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
			<table cellspacing="0" cellpadding="0" width="545" border="0">
				
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
					<td><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
					<td><textarea cols="1" name="fDescripcion" rows="6" class="obliga"  onchange="javascript:trim(this);"><?php echo $cEntidad->getDescripcion();?></textarea></td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:cierraaniade();" /></td>
					<td><input type="button" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" onclick="javascript:guardabaremos();" /></td>
				</tr>
			</table>