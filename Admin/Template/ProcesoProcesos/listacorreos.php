<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
if($listaCP->recordCount() > 0)
{
?>
<table width="100%">
	<tr>
		<td class="tdnaranjab1" width="40%"><?php echo constant("STR_TIPO");?></td>
		<td class="tdnaranjab1" width="45%"><?php echo constant("STR_NOMBRE");?></td>
		<td class="tdnaranjab1" width="10%"><?php echo constant("STR_DETALLE");?></td>
		<td class="tdnaranjab1" width="5%"></td>
	</tr>
	<?php
}
	$iListaCorreos=0;
	if($listaCP->recordCount() > 0){
		$iListaCorreos=$listaCP->recordCount();
		while(!$listaCP->EOF){
			$cTipoCorreo = new Tipos_correos();
			$cTipoCorreo->setIdTipoCorreo($listaCP->fields['idTipoCorreo']);
			$cTipoCorreo = $cTiposCorreosDB->readEntidad($cTipoCorreo);?>
		<tr>
			<td width="42%" class="tddatoslista1"><?php echo $cTipoCorreo->getNombre()?></td>
			<td width="45%" class="tddatoslista1"><?php echo $listaCP->fields['nombre']?></td>
			<td width="10%" align="center" class="tddatoslista1"><img src="<?php echo constant('DIR_WS_GRAF');?>productlist.gif" border="0" alt="<?php echo constant("STR_BORRAR");?>" onclick="javascript:consultacorreoproceso('<?php echo $listaCP->fields['idEmpresa'];?>','<?php echo $listaCP->fields['idProceso'];?>','<?php echo $listaCP->fields['idTipoCorreo'];?>','<?php echo $listaCP->fields['idCorreo'];?>');" /></td>
			<td width="3%" class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:borraasignados('<?php echo $listaCP->fields['idEmpresa'];?>','<?php echo $listaCP->fields['idProceso'];?>','<?php echo $listaCP->fields['idTipoCorreo'];?>','<?php echo $listaCP->fields['idCorreo'];?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
		</tr>
		<?php
			$listaCP->MoveNext();	
		}
	}
	?>
	<input type="hidden" name="fIListaCorreos" value="<?php echo $iListaCorreos;?>" />
</table>
<script language="javascript" type="text/javascript">
if ("<?php echo $iListaCorreos;?>" > 0){
	document.forms[0].fBtnEnviarCorreos.disabled=false;
}else{
	document.forms[0].fBtnEnviarCorreos.disabled=true;
}
</script>