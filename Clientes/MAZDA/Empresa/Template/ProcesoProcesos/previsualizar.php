<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
	<table cellspacing="0" cellpadding="0" border="0" width="750px">
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td class="subtitulo"><?php echo constant("STR_CONFIGURACION_DEL_FICHERO")?></td>
		</tr>
		<tr><td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td bgcolor="#FF8F19"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
		</tr>
		<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td class="negro"><?php echo constant("MSG_IMPORTAR_CSV_2");?>&nbsp;</td>
		</tr>
		<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="20" border="0" alt="" /></td></tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td nowrap="nowrap" class="negrob" valign="bottom"><?php echo constant("STR_SEPARADOR_DE_CAMPOS")?>&nbsp;</td>
		</tr>
<?php
if (empty($_POST["fSeparadorCamposSel"])){
	$_POST["fSeparadorCamposSel"] = ";";
} 
?>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td class="negro">
				<select name="fSeparadorCampos" size="1" class="obliga" onchange="javascript:cambiaCSV_lista();cambiaSeparador();">
					<option style="color:#000000;" value=";" <?php echo ($_POST["fSeparadorCamposSel"] == ";") ? "selected=\"selected\"" : ""; ?>><?php echo constant("STR_PUNTO_Y_COMA");?></option>
					<option style="color:#000000;" value="," <?php echo ($_POST["fSeparadorCamposSel"] == ",") ? "selected=\"selected\"" : ""; ?>><?php echo constant("STR_COMA");?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td nowrap="nowrap" class="negrob" valign="bottom"><?php echo constant("STR_CODIFICACION_DEL_FICHERO")?>&nbsp;</td>
		</tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td><?php $comboEMK_CHARSETS->setNombre("fCodificacion");?><?php echo $comboEMK_CHARSETS->getHTMLCombo("1","obliga",$_POST["fCodificacionSel"],"onchange=\"javascript:cambiaCSV_lista();cambiaCodificacion();\"","");?></td>
			
		</tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td nowrap="nowrap" class="negrob" valign="bottom"><?php echo constant("STR_VALORES_ENTRECOMILLADOS")?>&nbsp;</td>
		</tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td class="negro">
				<select name="fEntrecomillado" size="1" class="obliga" onchange="javascript:cambiaCSV_lista();cambiaEntrecomillado();">
					<option style="color:#000000;" value=" "  <?php echo ($_POST["fEntrecomilladoSel"] == "") ? "selected=\"selected\"" : ""; ?>><?php echo constant("STR_SIN_COMILLAS")?></option>
					<option style="color:#000000;" value='"' <?php echo ($_POST["fEntrecomilladoSel"] == '"') ? "selected=\"selected\"" : ""; ?>><?php echo constant("STR_VALORES_ENCERRADOS_ENTRE_COMILLAS")?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td nowrap="nowrap" class="negrob" valign="bottom"><?php echo constant("STR_CABECERAS")?>&nbsp;</td>
		</tr>
<?php
if (empty($_POST["fCabecerasSel"])){
	$_POST["fCabecerasSel"] = "1";
} 
?>

		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td class="negro">
				<select name="fCabeceras" size="1" class="obliga" onchange="javascript:cambiaCSV_lista();cambiaSinCabeceras();">
					<option style="color:#000000;" value="0" <?php echo ($_POST["fCabecerasSel"] == "0") ? "selected=\"selected\"" : ""; ?>><?php echo constant("STR_EL_FICHERO_NO_TIENE_CABECERAS_SE_INSERTARAN_TODAS_LAS_LINEAS")?></option>
					<option style="color:#000000;" value="1" <?php echo ($_POST["fCabecerasSel"] == "1") ? "selected=\"selected\"" : ""; ?>><?php echo constant("STR_EL_FICHERO_TIENE_CABECERAS_NO_SE_INSERTARA_LAS_PRIMERA_LINEA")?></option>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="20" border="0" alt="" /></td></tr>
		<tr>
			<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
			<td nowrap="nowrap" class="subtitulo" valign="bottom"><?php echo constant("STR_PREVISUALIZACION_DE_DATOS_A_INSERTAR")?>&nbsp;</td>
		</tr>
		<tr><td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td bgcolor="#FF8F19"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
		</tr>
		<tr><td colspan="2"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	
	<br /><div id="CSV_lista" style="margin:0 0 0 10px;max-width:750px;max-height:300px;overflow:scroll;"></div>
	<br />

	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].fTipoAlta.value.value=1;cargaalta();" /></td>
			<td><input type="button" class="botones" id="bid-ok" name="btnAdd" value="<?php echo constant("STR_SEGUIR");?>" onclick="definirCampos();" /></td>
		</tr>
	</table>
	
<!-- </div> -->	

	<br />
	<input type="hidden" name="fIdFichero" value="<?php echo $cFich->getIdFichero();?>" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="fFichero" value="<?php echo ($_POST['fFichero']);?>" />
	<input type="hidden" name="fSrc_type" value="<?php echo ($_POST['fSrc_type']);?>" />	
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
		function cambiaCSV_lista(){
			var f= document.forms[0];
			f.sPG.value="CSV_lista";
			$("#loaderContainer").ajaxStart(function(){$(this).show();}).ajaxComplete(function(){$(this).hide();});
			$("#CSV_lista").hide().load("jQuery.php",$("form").serializeArray()).fadeIn("slow");

		}
		cambiaCSV_lista();
		//]]>
	</script>
	