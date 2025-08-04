<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

if (sizeof($aPruebas) > 0)
{
    ?>
<table width="99%" border="2" cellspacing="0" cellpadding="3">
		<tr>
			<td class="tdnaranjab">
				<?php echo constant("STR_NOMBRE_DE_LA_PRUEBA");?>
			</td>
			<td class="tdnaranjab">
				<?php echo constant("STR_IDIOMA");?>
			</td>
			<td class="tdnaranjab">
				<?php echo constant("STR_BAREMO");?>
			</td>
			<td class="tdnaranjab">
				<?php echo constant("STR_IDIOMA_INFORMES");?>
			</td>
			<td class="tdnaranjab">
				<?php echo constant("STR_INFORMES");?>
			</td>
			<td class="tdnaranjab">
			</td>
		</tr>
	<?php if($i>0){
		for($i=0; $i<sizeof($aPruebas);$i++){
			$aPrueba = explode(",", $aPruebas[$i]);?>
			
		<tr>
			<td class="tddatoslista1">
				<?php echo $aPrueba[2]?>
			</td>
			<td class="tddatoslista1">
				<?php echo $aPrueba[3]?>
			</td>
			<td class="tddatoslista1">
				<?php echo $aPrueba[5]?>
			</td>
			<td class="tddatoslista1">
				<?php echo $aPrueba[7]?>
			</td>
			<td nowrap="nowrap" class="tddatoslista1">
				<?php echo $aPrueba[9]?>
			</td>
			<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:borraprueba('<?php echo $aPrueba[0];?>','<?php echo $aPrueba[1];?>','<?php echo $aPrueba[4];?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" title="<?php echo constant("STR_BORRAR");?>" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php } ?></td>
		</tr>


	<?php	}
	}?>
</table>
	<?php
}?>
