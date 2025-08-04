<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<table cellspaging="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="1"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="2" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="137" class="negrob" valign="top"><?php echo constant("STR_IDIOMA");?>  Prueba&nbsp;</td>
		<td>
			<select class="obliga" name="fIdioma">
				<option value="" selected="selected"><?php echo constant('SLC_OPCION');?></option>
				<?php
				if($i>0){
					for($i=0 ; $i< sizeof($aIdiomas);$i++){
						$aIdioma = explode("," , $aIdiomas[$i]);?>
						<option value="<?php echo $aIdioma[0]?>"><?php echo $aIdioma[1]?></option>
			<?php 		}
				}
			 ?>
			</select>
		</td>
	</tr>
<?php 
if (!$bPintaBaremo){
?>
	<tr>
		<td width="1"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="2" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="137" class="negrob" valign="top">&nbsp;</td>
		<td>
			<input type="hidden" name="fIdBaremo" value="" />
			<input type="hidden" name="fPintaBaremo" value="<?php echo ($bPintaBaremo);?>" />
		</td>
	</tr>
<?php 
}else{
?>
	<tr>
		<td width="1"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="2" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="137" class="negrob" valign="top"><?php echo constant("STR_BAREMO");?>&nbsp;</td>
		<td>
			<select class="obliga" name="fIdBaremo">
				<option value="" selected="selected"><?php echo constant('SLC_OPCION');?></option>
				<?php
				if($listaBaremos->recordCount()>0){
					while(!$listaBaremos->EOF){
				?>	
						<option value="<?php echo $listaBaremos->fields['idBaremo']?>"><?php echo $listaBaremos->fields['nombre']?></option>
			<?php 		$listaBaremos->MoveNext();
					}
				}
			 ?>
			</select>
			<input type="hidden" name="fPintaBaremo" value="<?php echo ($bPintaBaremo);?>" />
		</td>
	</tr>
<?php 
}
?>
	<tr>
		<td colspan="3" width="200"><?php echo $sMensaje;?></td>
	</tr>
	<tr>
		<td colspan="3" width="200">&nbsp;</td>
	</tr>
	<tr>
		<td width="1"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="2" height="20" border="0" alt="" /></td>
		<td colspan="2" class="negrob" width="200"><?php echo constant("STR_INFORME");?>:</td>
	</tr>
	<tr>
		<td width="1"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="2" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="137" class="negrob" valign="top"><?php echo constant("STR_IDIOMA");?> Informe&nbsp;</td>
		<td>
			<select class="obliga" name="fIdiomaInforme" onchange="javascript:cambiaIdiomaInforme();">
				<option value="-1" selected="selected"><?php echo constant('SLC_OPCION');?></option>
				<?php
				if($ii>0){
					for($i=0 ; $i< sizeof($aIdiomasInformes);$i++){
						$aIdiomasInforme = explode("," , $aIdiomasInformes[$i]);?>
						<option value="<?php echo $aIdiomasInforme[0]?>"><?php echo $aIdiomasInforme[1]?></option>
			<?php 		}
				}
			 ?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="1"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="2" height="20" border="0" alt="" /></td>
		<td nowrap="nowrap" width="137" class="negrob" valign="top"><?php echo constant("STR_TIPOSINFORMES");?>&nbsp;</td>
		<td><div id="comboIdTipoInforme">
			<select class="obliga" name="fIdTipoInforme" onchange="javascript:cambiaIdTipoInforme();">
				<option value="" selected="selected"><?php echo constant('SLC_OPCION');?></option>
			</select>
	</tr>
</table>
<script language="javascript" type="text/javascript">
	//<![CDATA[
	function cambiaIdiomaInforme(){
		var f= document.forms[0];
		$("#comboIdTipoInforme").show().load("jQuery.php",{sPG:"combotipos_informesprueba",bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdTipoInforme",fJSProp:"cambiaIdTipoInforme",fIdPrueba:f.fIdPrueba.value,fCodIdiomaIso2:f.fIdiomaInforme.value,sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
	}
	//]]>
</script>
<script language="javascript" type="text/javascript">
	//<![CDATA[
	function cambiaIdTipoInforme(){								
	}
	//]]>
</script>