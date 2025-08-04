<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
if ($bPintaBaremo)
{
    if($sMensaje!=""){
    	echo $sMensaje;
    }else{
   	$sBaremoSelect = (!empty($_POST['fIdBaremo'])) ? $_POST['fIdBaremo'] : 1; //1 es Baremo estandar
?>

<select class="obliga" name="fIdBaremo">
	<option value="" selected="selected"><?php echo constant('SLC_OPCION');?></option>
	<?php
	if($listaBaremos->recordCount()>0){
		while(!$listaBaremos->EOF){
		?>
			<option value="<?php echo $listaBaremos->fields['idBaremo']?>" <?php echo ($listaBaremos->fields['idBaremo'] == $sBaremoSelect) ? "selected=\"selected\"" : "";?>><?php echo $listaBaremos->fields['nombre']?></option>
			
<?php 			$listaBaremos->MoveNext();
		}
	}
 ?>
</select>
<?php }
}else{
?>
<input type="hidden" name="fIdBaremo" value="1" />
<input type="hidden" name="fPintaBaremo" value="<?php echo ($bPintaBaremo);?>" />
<?php 	
}

?>