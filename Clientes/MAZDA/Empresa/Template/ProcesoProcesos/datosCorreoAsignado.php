<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<input type="text" id="fNombreDato" name="fNombreDato" value="<?php echo $cCorreosProceso->getNombre()?>" class="obliga"  onchange="javascript:trim(this);" /></td>
<input type="text" id="fAsuntoDato" name="fAsuntoDato" value="<?php echo $cCorreosProceso->getAsunto();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
<textarea cols="1" id="fCuerpoDato" name="fCuerpoDato" rows="6" class="obliga tinymce"  onchange="javascript:trim(this);"><?php echo $cCorreosProceso->getCuerpo()?></textarea></td>
<textarea cols="1" id="fDescripcionDato" name="fDescripcionDato" rows="2" class="cajatexto"  onchange="javascript:trim(this);"><?php echo $cCorreosProceso->getDescripcion()?></textarea></td>
<input type="text" id="fIdCorreoNew" name="fIdCorreoNew" value="<?php echo $cCorreosProceso->getIdCorreo()?>" />
<?php $comboTIPOS_CORREOS->setNombre("fIdTipoCorreoNew");?><?php echo $comboTIPOS_CORREOS->getHTMLCombo("1","obliga",$cCorreosProceso->getIdTipoCorreo()," ","");?></td>
