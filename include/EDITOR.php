<?	/** 
	* Editor.
	* @param String sEDITORNombreCampo -> Nombre del campo Editor.
	* @param String sEDITORClass -> Nombre de la clase de estilo.
	**/
	if (substr($sEDITORNombreCampo,0,3) == "LST"){
		$sMetodo='get' . substr($sEDITORNombreCampo,3);
	}else{
		$sMetodo='get' . substr($sEDITORNombreCampo,1);
	}
	if (!isset($sEDITORStyle) || empty($sEDITORStyle)){
		$sEDITORStyle= 'style="width:423px;height:150px"';
	}
?>
<div id="capa_<?php echo $sEDITORNombreCampo;?>" class="<?php echo $sEDITORClass;?>" <?php echo $sEDITORStyle;?> contentEditable><?php echo call_user_func(array($cEntidad,$sMetodo));?></div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="toolbar">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" >
				<tr>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('Bold');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/bold.gif" alt='<?php echo constant("STR_NEGRITA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('UnderLine');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/under.gif" alt='<?php echo constant("STR_SUBRAYADO");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('Italic');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/italic.gif" alt='<?php echo constant("STR_CURSIVA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('StrikeThrough');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/tachado.gif" alt='<?php echo constant("STR_TACHADO");?>' border="0" /></div></td>
					<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('InsertOrderedList');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/numlist.gif" alt='<?php echo constant("STR_LISTA_NUMERICA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('InsertUnorderedList');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/bullist.gif" alt='<?php echo constant("STR_LISTA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('OutDent');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/deindent.gif" alt='<?php echo constant("STR_QUITAR_SANGRIA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('InDent');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/inindent.gif" alt='<?php echo constant("STR_SANGRIA");?>' border="0" /></div></td>
					<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('Superscript');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/superscript.gif" alt='<?php echo constant("STR_SUPER_INDICE");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('Subscript');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/subscript.gif" alt='<?php echo constant("STR_SUB_INDICE");?>' border="0" /></div></td>
					<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('justifyleft');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/left.gif" alt='<?php echo constant("STR_IZQUIERDA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('justifycenter');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/center.gif" alt='<?php echo constant("STR_CENTRO");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('justifyright');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/right.gif" alt='<?php echo constant("STR_DERECHA");?>' border="0" /></div></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('JustifyFull');capa_<?php echo $sEDITORNombreCampo;?>.focus();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/align_justify.gif" alt='<?php echo constant("STR_JUSTIFICADO");?>' border="0" /></div></td>
					<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
					<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();AddLink()" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/link.gif" alt='<?php echo constant("STR_LINKS");?>' border="0" /></div></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#dedede">
				<tr>
					<td width="3"><img src="graf/sp.gif" width="3" height="1" border="0" alt="" /></td>
					<td bgcolor="#808080"><img src="graf/sp.gif" width="1" height="1" border="0" alt="" /></td>
					<td width="3"><img src="graf/sp.gif" width="3" height="1" border="0" alt="" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" class="toolbar">
				<tr>
					<td>&nbsp;<select name="selectFontName" onchange='document.execCommand("FontName", false,this[this.selectedIndex].value);capa_<?php echo $sEDITORNombreCampo;?>.focus();' id='select<?php echo $sEDITORNombreCampo;?>'>
								<option value="Verdana" selected="selected"><?php echo constant("STR_FUENTE");?>:</option>
								<option value="Arial">Arial</option>
								<option value="Arial Black">Arial Black</option>
								<option value="Arial Narrow">Arial Narrow</option>
								<option value="Batang">Batang</option>
								<option value="BatangChe">BatangChe</option>
								<option value="Book Antiqua">Book Antiqua</option>
								<option value="Bookman Old Style">Bookman Old Style</option>
								<option value="Century">Century</option>
								<option value="Century Gothic">Century Gothic</option>
								<option value="Comic Sans MS">Comic Sans MS</option>
								<option value="Courier">Courier</option>
								<option value="Courier New">Courier New</option>
								<option value="Fixedsys">Fixedsys</option>
								<option value="Garamond">Garamond</option>
								<option value="Georgia">Georgia</option>
								<option value="Haettenschweiler">Haettenschweiler</option>
								<option value="Helvetica">Helvetica</option>
								<option value="Impact">Impact</option>
								<option value="Modern">Modern</option>
								<option value="Monotype Corsiva">Monotype Corsiva</option>
								<option value="Roman">Roman</option>
								<option value="Script">Script</option>
								<option value="Small Fonts">Small Fonts</option>
								<option value="System">System</option>
								<option value="Tahoma">Tahoma</option>
								<option value="Terminal">Terminal</option>
								<option value="Times New Roman">Times New Roman</option>
								<option value="Verdana">Verdana</option>
								<option value="Webdings">Webdings</option>
								<option value="Wingdings">Wingdings</option>
								<option value="Wingdings 2">Wingdings 2</option>
								<option value="Wingdings 3">Wingdings 3</option>
							</select>
							<select name="selectFontSize" onchange='document.execCommand("FontSize", false,this[this.selectedIndex].value);capa_<?php echo $sEDITORNombreCampo;?>.focus();' id='select<?php echo $sEDITORNombreCampo;?>'>
								<option value="1" selected="selected">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
							</select>
						</td>
						<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
						<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();verMenu('paletaColores','FG',180,291)" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/fgcolor.gif" alt='<?php echo constant("STR_COLOR_DE_FUENTE");?>' border="0" /></div></td>
						<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();verMenu('paletaColores','BG',180,291)" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/bgcolor.gif" alt='<?php echo constant("STR_COLOR_DE_FONDO");?>' border="0" /></div></td>
						<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
						<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();AddImage();" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/imagen.gif" alt='<?php echo constant("STR_IMAGEN");?>' border="0" /></div></td>
						<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
						<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();document.execCommand('InsertHorizontalRule');" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/hr.gif" alt='<?php echo constant("STR_REGLA_HORIZONTAL");?>' border="0" /></div></td>
						<td><div class="editbutton" onclick="capa_<?php echo $sEDITORNombreCampo;?>.focus();quitaHTML('<?php echo $sEDITORNombreCampo;?>');" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><img hspace="1" vspace="1" align="middle" src="graf/clean_code.gif" alt='<?php echo constant("STR_QUITAR_CODIGO_HTML");?>' border="0" /></div></td>
						<td><img src="graf/separator.gif" width="2" height="20" border="0" alt="" /></td>
						<td class="negro" colspan="5"><div class="editbutton" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);"><input type="checkbox" onclick="setMode(this.checked,'<?php echo $sEDITORNombreCampo;?>');" /> <?php echo constant("STR_SRC");?>&nbsp;&nbsp;</div></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?php include_once(constant("DIR_WS_INCLUDE") . 'EDITORPaletaColores.php');?>
