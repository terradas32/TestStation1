<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellspacing="0" cellpadding="0" border="0">
	<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>

	<tr>
		<td align="center"><input type="button" class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" onclick="javascript:volcar();"/></td>
		<td align="center"><input type="button" class="botones" id="bid-cancel" name="fBtnOk" value="<?php echo constant("STR_CANCELAR");?>" onclick="javascript:cargaalta();"/></td>
	</tr>
</table>