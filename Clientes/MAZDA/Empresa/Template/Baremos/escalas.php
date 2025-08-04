<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
    
	$comboESCALAS	= new Combo($conn,"fIdEscala","idBloque","nombre","Descripcion","escalas","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
?>
<table cellpadding="5" cellspacing="5">
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdBloque"><?php echo constant("STR_BLOQUE");?></label>&nbsp;</td>
		<td>
			<select name="fIdBloque" class="obliga" style="width: 200px;" onchange="javascript:cambiaIdBloque();">
				<option value=""> <?php echo constant('SLC_OPCION')?></option>
				<?php 
				$lista->MoveFirst();
				while(!$lista->EOF){
					$cBloques = new Bloques();
					$cBloques->setCodIdiomaIso2($sLang);
					$cBloques->setIdBloque($lista->fields['idBloque']);
					$cBloques = $cBloquesDB->readEntidad($cBloques);
				?>
				
				<option value="<?php echo $cBloques->getIdBloque()?>" <?php echo ($_POST['fIdBloque'] == $cBloques->getIdBloque())?"selected=\"selected\"" : ""?>><?php echo $cBloques->getNombre()?></option>	
				<?php	
					$lista->MoveNext();
				}?>
			</select>
		</td>
		
	</tr>

	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEscala"><?php echo constant("STR_ESCALA");?></label>&nbsp;</td>
		<td><div id="comboIdEscala"><?php $comboESCALAS->setNombre("fIdEscala");?><?php echo $comboESCALAS->getHTMLComboNull("1","obliga",constant('SLC_OPCION'),"","");?></div></td>
	</tr>
	<?php if($_POST['fIdBloque']!="-1"){?>
		<script type="text/javascript">
			asignaBloque('<?php echo $_POST['fIdBloque']?>' ,'<?php echo $_POST['fIdEscala']?>' );
			cambiaIdBloque('<?php echo $_POST['fIdEscala']?>');
			listabaremos('<?php echo $_POST['fIdEscala']?>');
		</script>
	<?php }?>
</table>