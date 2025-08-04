<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

if($listaCandidatos->recordCount()>0)
{
?>
<table width="99%" border="2" cellspacing="0" cellpadding="3">
		<tr>
			<td class="tdnaranjab"><?php echo constant("STR_NOMBRE");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_APELLIDO_1");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_APELLIDO_2");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_NIF");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_EMAIL");?></td>
			<td class="tdnaranjab" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:borracandidato('*');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" title="<?php echo constant("STR_BORRAR_TODO");?>" alt="<?php echo constant("STR_BORRAR_TODO");?>" /></a><?php } ?></td>
		</tr>
	<?php 
		while(!$listaCandidatos->EOF){?>
			<tr>
				<td class="tddatoslista1">
					<?php echo $listaCandidatos->fields['nombre']?>
				</td>
				<td class="tddatoslista1">
					<?php echo $listaCandidatos->fields['apellido1']?>
				</td>
				<td class="tddatoslista1">
					<?php echo $listaCandidatos->fields['apellido2']?>
				</td>
				<td class="tddatoslista1">
					<?php echo $listaCandidatos->fields['dni']?>
				</td>
				<td class="tddatoslista1">
					<?php echo $listaCandidatos->fields['mail']?>
				</td>
				<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:borracandidato('<?php echo $listaCandidatos->fields['idCandidato'];?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" title="<?php echo constant("STR_BORRAR");?>" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
			</tr>
	<?php		$listaCandidatos->MoveNext();
		} 
	?>
</table>
<?php 
}?>