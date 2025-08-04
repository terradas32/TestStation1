<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellspaging="0" width="95%" cellpadding="0" border="0">
	
	<img id="loading" src="<?php echo constant('DIR_WS_GRAF');?>loading.gif" style="display:none;">
	<tr><td colspan="3" align="center" class="naranjab">Carga masiva</td></tr>
	<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr>
		<td width="5"><img src="graf/sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="100" class="negrob" valign="middle">Importar candidatos:&nbsp;</td>
		<td width="300"><input onkeydown="blur();" onkeypress="blur();" type="file" id="fFichero" name="fFichero" class="obliga" style="width: 250px;" /></td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" >&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" style="height:10px;" >Puede importar cualquier tipo de fichero <strong>CSV</strong> (Archivo de texto delimitado por comas), seleccione el fichero que desea utilizar y pulse <b>Añadir</b>. Le proporcionamos un ejemplo de fichero de carga pulse: <a href="<?php echo constant("HTTP_SERVER") . "ejemplo.csv";?>" class="naranja">ejemplo.csv</a></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="button" class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ANIADIR");?>" onclick="aniadecandidatomasivo();"/></td>
	</tr>
</table>