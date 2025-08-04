<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellspacing="0" cellpadding="0" border="0" width="75%">
	<tr><td colspan="3" align="center" class="naranjab"><?php echo "Altas anónimas";?></td></tr>
	<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo "Nº de Altas";?>&nbsp;</td>
		<td><input type="text" name="fNumAltas" value="" class="obliga" style="width: 75px;" onchange="javascript:trim(this);" /></td>
	</tr>
	<?php 
//Miramos si la empresa seleccionada tiene activado el pago por TPV
 if ($bTpvActivo){
 	?>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top">&nbsp;</td>
		<td class="negrob" align="center">¿Debe pagar a través de tpv para realizar la prueba?&nbsp;</td>
	</tr>
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top">&nbsp;</td>
		<td align="center"><select name="fPagoTpv" >
				<option value="0">No</option>
				<option value="1">Sí</option>
			</select>
		</td>
	</tr>
 	<?php 
 }else {echo '<input type="hidden" name="fPagoTpv" value="0" />';}
?>
	<tr>
		<td colspan="3" style="height:10px;" >
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="button" class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ANIADIR");?>" onclick="javascript:aniadeblind();"/></td>
	</tr>
</table>