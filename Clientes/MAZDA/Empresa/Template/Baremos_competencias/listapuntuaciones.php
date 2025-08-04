<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table width="350" border="2" cellspacing="0" cellpadding="3">
		<tr>
			<td class="tdnaranjab1">
				Punt. Mínima
			</td>
			<td class="tdnaranjab1">
				Punt. Máxima
			</td>
			<td class="tdnaranjab1">
				Punt. Baremada
			</td>
			<td class="tdnaranjab1">
			</td>
		</tr>
	<?php if($listaResultados->recordCount()>0){
			while(!$listaResultados->EOF){?>
				<tr>
					<td class="tddatoslista1">
						<?php echo $listaResultados->fields['puntMin'];?>
					</td>
					<td class="tddatoslista1">
						<?php echo $listaResultados->fields['puntMax'];?>
					</td>
					<td class="tddatoslista1">
						<?php echo $listaResultados->fields['puntBaremada'];?>
					</td>
					<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:borrarresult('<?php echo addslashes($listaResultados->fields['idResultado']);?>','<?php echo addslashes($listaResultados->fields['idBaremo']);?>','<?php echo addslashes($listaResultados->fields['idPrueba']);?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
				</tr>
	<?php			$listaResultados->MoveNext();
			}
		}?>
</table>