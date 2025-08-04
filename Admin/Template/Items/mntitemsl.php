<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />

<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
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
	return true;
}
function setPK(codIdiomaIso2,idPrueba,idItem)
{
	var f=document.forms[0];
	f.fCodIdiomaIso2.value=codIdiomaIso2;
	f.fIdPrueba.value=idPrueba;
	f.fIdItem.value=idItem;
}
function confBorrar(Modo,sMsg)
{
	if (confirm(sMsg))
		enviar(Modo);
}
function abrirVentana(bImg, file){
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
}
function enviarMenu(pagina, modo)
{
	var f=document.forms[0];
	if (pagina != 'null'){
		lon();
		f.MODO.value = modo;
		f.action = pagina;
		f.submit();
	}
}
function setTitulo(titulo)
{
	if (eval("document.getElementById('cabecera-menu-seleccionado').innerHTML") != null){
		document.getElementById('cabecera-menu-seleccionado').innerHTML=titulo;
		document.forms[0]._TituloOpcion.value=titulo;
	}
}
function block(idBlock){
	for (i=0;i<200; i++){
		if (eval("document.getElementById('block" + i + "')") != null){
			eval("document.getElementById('block" + i + "').style.display = 'none'");
		}
	}
	if (eval("document.getElementById('block" + idBlock + "')") != null){
		eval("document.getElementById('block" + idBlock + "').style.display = 'block'");
	}
	document.forms[0]._block.value=idBlock;
}
onclick="javascript:if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
//]]>
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');" onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
<?php ob_start();?>
	<table cellspacing="1" cellpadding="2" border="0" width="100%">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<?php
			$strOrderASC = "&nbsp;<img src='" . constant('DIR_WS_GRAF') . "asc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			$strOrderDESC = "&nbsp;<img src='" . constant('DIR_WS_GRAF') . "desc_order.gif' width='7' height='7' border='0' alt='' />&nbsp;";
			?>

		
			<?php
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "idItem"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities("Nº de Pregunta", ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='idItem';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes("Nº de Pregunta");?>';return true"><?php echo "Nº de Pregunta";?><?php echo $sPinta;?></a></td>
			<?php
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "enunciado"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ENUNCIADO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='enunciado';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ENUNCIADO"));?>';return true"><?php echo constant("STR_ENUNCIADO");?><?php echo $sPinta;?></a></td>
			<?php
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descripcion"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<?php
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "correcto"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities("Observaciones", ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='correcto';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes("Observaciones");?>';return true"><?php echo "Observaciones";?><?php echo $sPinta;?></a></td>
			<?php
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "orden"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities("Puntuación", ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='orden';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes("Puntuación");?>';return true"><?php echo "Puntuación";?><?php echo $sPinta;?></a></td>
			<?php
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "bajaLog"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>

		</tr>
		<?php	$i=0;
		while (!$lista->EOF)
		{
		?>
		<tr onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idItem']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['idItem'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['enunciado']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['enunciado'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descripcion']);?>"><textarea></textarea> </</td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['codIdiomaIso2']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>','<?php echo addslashes($lista->fields['idItem']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['orden']);?>"><input style="width: 50px;" type=text></td>

		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>

<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return enviar(document.forms[0].MODO.value);">
<?php $HELP="xx";
$aBuscador= $cEntidad->getBusqueda();
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" align="left" class="naranjab" valign="top"><?php echo constant("STR_PARAMETROS_DE_BUSQUEDA");?></td>
			<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%">
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
					<?php $iTotal = sizeof($aBuscador);
					if ($iTotal > 0 ) {
						//Calculo los ficheros por columna.
						$col1=$iTotal/2;
						$col2=$col1;
						$resto=$iTotal%2;
						switch ($resto){
							case 1:
								$col1=(substr($col1,0,strpos($col1, '.')) + 1);
								break;
							case 2:
								$col1=(substr($col2,0,strpos($col2, '.')) + 1);
								$col2=(substr($col2,0,strpos($col2, '.')) + 1);
								break;
						}
						$sCol1="";
						$sCol2="";
						for ($x=0; $x < $iTotal; $x++) {
							if ($x < $col1){
								$sCol1.=$cUtilidades->validaXSS($aBuscador[$x][0]) . ":&nbsp;<font class='negrob'>" .  $cUtilidades->validaXSS($aBuscador[$x][1]) . "</font><br /><br />";
							}else{
								$sCol2.=$cUtilidades->validaXSS($aBuscador[$x][0]) . ":&nbsp;<font class='negrob'>" .  $cUtilidades->validaXSS($aBuscador[$x][1]) . "</font><br /><br />";
							}
						}
					}
					?>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol1;?></td>
						<td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
						<td nowrap="nowrap" class="azul" valign="top" width="35%"><?php echo $sCol2;?></td>
						<td width="30%"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td></tr>
		<tr><td><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td colspan="3" bgcolor="#FF8F19"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td>
		</tr>
		<tr><td colspan="4"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td width="100%" colspan="2" class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', "Preguntas"));?><a href="sqlToExcel.php?fSQLtoEXCEL=<?php echo base64_encode($sql . constant("CHAR_SEPARA") . "items");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
		</tr>
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width="15%" class="azul" align="left">(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width="85%" class="azul" align="left"><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<?php echo $pager->getGrid();?>
	<br />
	<table cellspacing="0" width="100%" cellpadding="0" border="0">
		<tr>
			<td width="10"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td nowrap="nowrap" width="15%" class="azul" align="left">(<font class="naranja"><?php echo $pager->getFooter();?></font>)</td>
			<td width="85%" class="azul" align="left"><?php echo $pager->getHeader();?></td>
		</tr>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="submit" class="botones" id="bid-buscador" name="btnAdd" value="<?php echo constant("STR_BUSCADOR");?>" onclick="javascript:document.forms[0].MODO.value=<?php echo constant("MNT_BUSCAR");?>" /></td>
			<td><input type="submit" class="botones" id="bid-alta" name="btnAdd" value="<?php echo constant("STR_ALTA");?>" onclick="javascript:document.forms[0].MODO.value=<?php echo constant("MNT_NUEVO");?>" /></td>
		</tr>
	</table>
	</div>
</div>
	<br />
	<input type="hidden" name="fCodIdiomaIso2" value="" />
	<input type="hidden" name="fIdPrueba" value="" />
	<input type="hidden" name="fIdItem" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTIdItemHast" value="<?php echo (isset($_POST['LSTIdItemHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdItemHast']) : "";?>" />
	<input type="hidden" name="LSTIdItem" value="<?php echo (isset($_POST['LSTIdItem'])) ? $cUtilidades->validaXSS($_POST['LSTIdItem']) : "";?>" />
	<input type="hidden" name="LSTEnunciado" value="<?php echo (isset($_POST['LSTEnunciado'])) ? $cUtilidades->validaXSS($_POST['LSTEnunciado']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTPath1" value="<?php echo (isset($_POST['LSTPath1'])) ? $cUtilidades->validaXSS($_POST['LSTPath1']) : "";?>" />
	<input type="hidden" name="LSTPath2" value="<?php echo (isset($_POST['LSTPath2'])) ? $cUtilidades->validaXSS($_POST['LSTPath2']) : "";?>" />
	<input type="hidden" name="LSTPath3" value="<?php echo (isset($_POST['LSTPath3'])) ? $cUtilidades->validaXSS($_POST['LSTPath3']) : "";?>" />
	<input type="hidden" name="LSTPath4" value="<?php echo (isset($_POST['LSTPath4'])) ? $cUtilidades->validaXSS($_POST['LSTPath4']) : "";?>" />
	<input type="hidden" name="LSTCorrecto" value="<?php echo (isset($_POST['LSTCorrecto'])) ? $cUtilidades->validaXSS($_POST['LSTCorrecto']) : "";?>" />
	<input type="hidden" name="LSTOrdenHast" value="<?php echo (isset($_POST['LSTOrdenHast'])) ? $cUtilidades->validaXSS($_POST['LSTOrdenHast']) : "";?>" />
	<input type="hidden" name="LSTOrden" value="<?php echo (isset($_POST['LSTOrden'])) ? $cUtilidades->validaXSS($_POST['LSTOrden']) : "";?>" />
	<input type="hidden" name="LSTBajaLogHast" value="<?php echo (isset($_POST['LSTBajaLogHast'])) ? $cUtilidades->validaXSS($_POST['LSTBajaLogHast']) : "";?>" />
	<input type="hidden" name="LSTBajaLog" value="<?php echo (isset($_POST['LSTBajaLog'])) ? $cUtilidades->validaXSS($_POST['LSTBajaLog']) : "";?>" />
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
	<input type="hidden" name="items_next_page" value="<?php echo (isset($_POST['items_next_page'])) ? $cUtilidades->validaXSS($_POST['items_next_page']) : "1";?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . 'menus.php');?>
<?php //include (constant("DIR_WS_INCLUDE") . 'derecha.php');?>
<?php include (constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>
