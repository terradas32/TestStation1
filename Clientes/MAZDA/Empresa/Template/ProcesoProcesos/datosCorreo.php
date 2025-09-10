<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<input type="text" id="fNombreDato" name="fNombreDato" value="<?php echo $cCorreos->getNombre()?>" class="obliga"  onchange="javascript:trim(this);" />
<input type="text" id="fAsuntoDato" name="fAsuntoDato" value="<?php echo $cCorreos->getAsunto();?>" class="obliga"  onchange="javascript:trim(this);" />
<textarea cols="1" id="fCuerpoDato" name="fCuerpoDato" data-id="fCuerpoDato" rows="6" class="obliga"  onchange="javascript:trim(this);"><?php echo $cCorreos->getCuerpo()?></textarea>
<input type="text" id="fDescripcionDato" name="fDescripcionDato" value="<?php echo $cCorreos->getDescripcion()?>" class="cajatexto"  onchange="javascript:trim(this);" />
