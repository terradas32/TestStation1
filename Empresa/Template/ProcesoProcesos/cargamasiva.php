<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellspaging="0" width="95%" cellpadding="0" border="0">
	
	<img id="loading" src="<?php echo constant('DIR_WS_GRAF');?>loading.gif" style="display:none;">
	<tr><td colspan="3" align="center" class="naranjab"><?php echo constant("STR_CARGA_MASIVA");?></td></tr>
	<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr>
		<td nowrap="nowrap" width="140" class="negrob" valign="middle"><?php echo constant("STR_IMPORTAR_CANDIDATOS");?>:&nbsp;</td>
		<td width="300"><input onkeydown="blur();" onkeypress="blur();" type="file" id="fFichero" name="fFichero" class="obliga" style="width: 250px;" /></td>
		<td width="100%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" >&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" ><?php echo constant("MSG_IMPORTAR_CANDIDATOS");?> <a href="<?php echo constant("HTTP_SERVER") . "ejemplo.csv";?>" class="naranja">ejemplo.csv</a></td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" ></td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" ><?php echo constant("MSG_IMPORTAR_CANDIDATOS2");?></td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" ></td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" ><strong><?php echo constant("MSG_IMPORTAR_CANDIDATOS3");?></strong></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="button" class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ANIADIR");?>" onclick="aniadecandidatomasivo();"/></td>
	</tr>
</table>