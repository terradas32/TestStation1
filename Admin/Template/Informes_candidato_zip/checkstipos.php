<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
    $sInfSelect = $_POST['fIdTipoInforme'];
?>

<select class="obliga" name="fIdTipoInforme" onchange="javascript:cambiaidiomas();">
	<option value="" selected="selected"><?php echo constant('SLC_OPCION');?></option>
	<?php
	if($listaTipos->recordCount()>0){
		while(!$listaTipos->EOF){
			$cTipos_informes = new Tipos_informes();
			$cTipos_informes->setIdTipoInforme($listaTipos->fields['idTipoInforme']);
			$cTipos_informes->setCodIdiomaIso2($sLang);
			$cTipos_informes = $cTipos_informesDB->readEntidad($cTipos_informes);
		?>
			<option value="<?php echo $cTipos_informes->getIdTipoInforme()?>" <?php echo ($cTipos_informes->getIdTipoInforme() == $sInfSelect) ? "selected=\"selected\"" : "";?>><?php echo $cTipos_informes->getNombre()?></option>
			
<?php 			$listaTipos->MoveNext();
		}
	}
 ?>
</select>