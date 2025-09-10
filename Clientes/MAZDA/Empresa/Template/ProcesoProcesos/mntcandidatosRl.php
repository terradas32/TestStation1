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
			<td colspan="6" class="negrob"><?php echo constant("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA_SELECCIONE_OPCION");?>:
			</td>
		</tr>
		<tr>
			<td class="tdnaranjab"><?php echo constant("STR_NOMBRE");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_APELLIDO_1");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_APELLIDO_2");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_NIF");?></td>
			<td class="tdnaranjab"><?php echo constant("STR_EMAIL");?></td>
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
			</tr>
	<?php		$listaCandidatos->MoveNext();
		} 
	?>
</table>
<?php 
}?>