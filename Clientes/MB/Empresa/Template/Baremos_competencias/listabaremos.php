<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table width="400" border="2" cellspacing="0" cellpadding="3">
		<tr>
			<td class="tdnaranjab1">
				Baremo
			</td>
			<td class="tdnaranjab1" width="85">
				Añadir datos
			</td>
			<td class="tdnaranjab1">
			</td>

		</tr>
	<?php if($listaBaremos->recordCount()>0){
			while(!$listaBaremos->EOF){?>
				<tr>
					<td class="tddatoslista1">
						<?php echo $listaBaremos->fields['nombre'];?>
					</td>
					<td class="tddatoslista1" width="85">
						<a href="#" onclick="javascript:setPK('<?php echo addslashes($listaBaremos->fields['idBaremo']);?>','<?php echo addslashes($listaBaremos->fields['idPrueba']);?>','<?php echo addslashes($listaBaremos->fields['idBloque']);?>','<?php echo addslashes($listaBaremos->fields['idEscala']);?>');enviar1(<?php echo constant("MNT_CONSULTAR");?>);" >Ver Detalle</a>
					</td>
					<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:borrabaremo('<?php echo addslashes($listaBaremos->fields['idBaremo']);?>','<?php echo addslashes($listaBaremos->fields['idPrueba']);?>','<?php echo addslashes($listaBaremos->fields['idBloque']);?>','<?php echo addslashes($listaBaremos->fields['idEscala']);?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
				</tr>
	<?php			$listaBaremos->MoveNext();
			}
		}?>
</table>