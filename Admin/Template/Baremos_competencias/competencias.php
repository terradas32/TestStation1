<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
    
	$comboCOMPETENCIAS	= new Combo($conn,"fIdCompetencia","idCompetencia","nombre","Descripcion","competencias","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr(constant("LENGUAJEDEFECTO"), false),"","fecMod");
?>
<table cellpadding="5" cellspacing="5">
	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdBloque"><?php echo constant("STR_TIPO_COMPETENCIA");?></label>&nbsp;</td>
		<td>
			<select name="fIdTipoCompetencia" class="obliga" style="width: 200px;" onchange="javascript:cambiaIdTipoCompetencia();">
				<option value=""> <?php echo constant('SLC_OPCION')?></option>
				<?php 
				$lista->MoveFirst();
				while(!$lista->EOF){
					$cTipoCompetencia = new Tipos_competencias();
					$cTipoCompetencia->setCodIdiomaIso2($sLang);
					$cTipoCompetencia->setIdPrueba($_POST['fIdPrueba']);
					$cTipoCompetencia->setIdTipoCompetencia($lista->fields['idTipoCompetencia']);
					$cTipoCompetencia = $cTipos_competenciasDB->readEntidad($cTipoCompetencia);
				?>
				
				<option value="<?php echo $cTipoCompetencia->getIdTipoCompetencia()?>" <?php echo (isset($_POST['fIdTipoCompetencia']) &&  $_POST['fIdTipoCompetencia'] == $cTipoCompetencia->getIdTipoCompetencia())?"selected=\"selected\"" : ""?>><?php echo $cTipoCompetencia->getNombre()?></option>	
				<?php	
					$lista->MoveNext();
				}?>
			</select>
		</td>
		
	</tr>

	<tr>
		<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdCompetencia"><?php echo constant("STR_COMPETENCIA");?></label>&nbsp;</td>
		<td><div id="comboIdCompetencia"><?php $comboCOMPETENCIAS->setNombre("fIdCompetencia");?><?php echo $comboCOMPETENCIAS->getHTMLComboNull("1","obliga",constant('SLC_OPCION'),"","");?></div></td>
	</tr>
</table>