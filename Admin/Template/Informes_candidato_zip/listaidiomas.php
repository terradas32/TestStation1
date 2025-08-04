<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
   	$sCodIdiomaInformeSelect = (!empty($_POST['fCodIdiomaInforme'])) ? $_POST['fCodIdiomaInforme'] : "";
    ?>
<select class="obliga" name="fCodIdiomaIso2">
	<option value="" selected="selected"><?php echo constant('SLC_OPCION');?></option>
<?php
	if($i>0){
		for($i=0 ; $i< sizeof($aIdiomas);$i++){
			$cIdiomas = new Idiomas();
			$cIdiomas = $aIdiomas[$i];
		?>
			<option value="<?php echo $cIdiomas->getCodIso2()?>" <?php echo ($cIdiomas->getCodIso2() == $sCodIdiomaInformeSelect) ? "selected=\"selected\"" : "";?>><?php echo $cIdiomas->getNombre()?></option>
<?php 		}
	}?>
</select>