<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
var MSG_INDIQUE_CAMPO_EMAIL_PARA_IMPORTAR = "<?php echo constant("MSG_INDIQUE_CAMPO_EMAIL_PARA_IMPORTAR");?>";
function enviar(Modo)
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		f.MODO.value = Modo;
		f.submit();
	}else	return false;
}
function validaForm()
{
	var oSelect = document.getElementsByTagName('select');
	var bEncontrado = false;
	for(i=0; i < oSelect.length; i++ ){
		if (oSelect[i].name == "fCampos[]"){
			oCampos = oSelect[i];
			for(j=0; j < oCampos.length; j++ ){
				if (oCampos.value == "Mail"){
					bEncontrado= true;
					break;
				}
			}
			if (bEncontrado)	break;
		}
	}
	var msg="";
	if (!bEncontrado) {
		msg += MSG_INDIQUE_CAMPO_EMAIL_PARA_IMPORTAR;
		jAlert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.","<?php echo constant("STR_NOTIFICACION");?>");
	}
	return bEncontrado;
}
//]]>
</script>
			<div id="Campos" style="display:block;">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td class="subtitulo"><?php echo constant("STR_SELECCION_DE_CAMPOS")?></td>
					</tr>
					<tr><td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td bgcolor="#FF8F19"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
					<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td class="negro"><?php echo constant("MSG_IMPORTAR_CSV_3");?>&nbsp;</td>
					</tr>
					<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				</table>
				<br /><div id="CSV_columnas" style="margin:0 0 0 10px;max-width:99%;max-height:300px;overflow:scroll;"></div>
				<br />
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="volvercandidatomasivo();" /></td>
						<td><input type="button" class="botones" id="bid-ok" name="btnAdd" value="<?php echo constant("STR_SEGUIR");?>" onclick="if (validaForm()){finalizarCandidatoMasivo();}" /></td>
					</tr>
				</table>
			</div>
	<br />
	<input type="hidden" name="fIdFichero" value="<?php echo (isset($_POST['fIdFichero'])) ? $_POST['fIdFichero'] : "";?>" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="fFichero" value="<?php echo (isset($_POST['fFichero'])) ? $_POST['fFichero'] : "";?>" />
	<input type="hidden" name="fSrc_type" value="<?php echo (isset($_POST['fSrc_type'])) ? $_POST['fSrc_type'] : "";?>" />
	
	<input type="hidden" name="fSeparadorCampos" value="<?php echo (isset($_POST['fSeparadorCampos'])) ? $_POST['fSeparadorCampos'] : "";?>" />
	<input type="hidden" name="fCodificacion" value="<?php echo (isset($_POST['fCodificacion'])) ? $_POST['fCodificacion'] : "";?>" />
	<input type="hidden" name="fEntrecomillado" value='<?php echo (isset($_POST['fEntrecomillado'])) ? $_POST['fEntrecomillado'] : "";?>' />
	<input type="hidden" name="fCabeceras" value="<?php echo (isset($_POST['fCabeceras'])) ? $_POST['fCabeceras'] : "";?>" />

  	<input type="hidden" name="LSTIdFicheroHast" value="<?php echo (isset($_POST['LSTIdFicheroHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdFicheroHast']) : "";?>" />
	<input type="hidden" name="LSTIdFichero" value="<?php echo (isset($_POST['LSTIdFichero'])) ? $cUtilidades->validaXSS($_POST['LSTIdFichero']) : "";?>" />
	<input type="hidden" name="LSTFichero" value="<?php echo (isset($_POST['LSTFichero'])) ? $cUtilidades->validaXSS($_POST['LSTFichero']) : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : "";?>" />
	<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
	<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderBy']) : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $cUtilidades->validaXSS($_POST['LSTOrder']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function cambiaCSV_columnas(){
			var f= document.forms[0];
			f.sPG.value="CSV_columnas";
			$("#loaderContainer").ajaxStart(function(){$(this).show();}).ajaxComplete(function(){$(this).hide();});
			$("#CSV_columnas").hide().load("jQuery.php",$("form").serializeArray()).fadeIn("slow");
		}
		function cambiaCSV_detalle(){
			var f= document.forms[0];
			f.sPG.value="CSV_detalle";
			$("#loaderContainer").ajaxStart(function(){$(this).show();}).ajaxComplete(function(){$(this).hide();});
			$("#CSV_detalle").hide().load("jQuery.php",$("form").serializeArray()).fadeIn("slow");
		}
		cambiaCSV_columnas();
		//]]>
	</script>
</body></html>