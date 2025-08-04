<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellspacing="0" cellpadding="0" border="0" width="75%">
	<tr><td colspan="3" align="center" class="naranjab"><?php echo constant("STR_ALTA_MANUAL");?></td></tr>
	<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
		<td><input type="text" name="fNombre" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_APELLIDO1");?>&nbsp;</td>
		<td><input type="text" name="fApellido1" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_APELLIDO2");?>&nbsp;</td>
		<td><input type="text" name="fApellido2" value="" class="cajatexto"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NIF");?>&nbsp;</td>
		<td><input type="text" name="fDni" value="" class="cajatexto"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMAIL");?>&nbsp;</td>
		<td><input type="text" name="fMail" value="" class="obliga"  onchange="javascript:trim(this);" /></td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" >
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="button" class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ANIADIR");?>" onclick="javascript:aniadecandidato();"/></td>
	</tr>
</table>