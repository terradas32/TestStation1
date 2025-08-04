<table cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PASSWORD");?>&nbsp;</td>
		<td><input type="password" name="fPassword" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_CONF_PASSWORD");?>&nbsp;</td>
		<td><input type="password" name="fConfPassword" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF')?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"></td>
		<td><input type="button" name="cancela" value="Cancelar" class="botones"  onclick="javascript:cierraPagina('contrasenia');" /></td>
	</tr>
</table>