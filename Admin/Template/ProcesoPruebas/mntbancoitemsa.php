<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
?>
<?php
	$cPruebas = new Pruebas();
	$cPruebas->setIdPrueba($cItems->getIdPrueba());
	$cPruebas->setCodIdiomaIso2($cItems->getCodIdiomaIso2());
	$cPruebas = $cEntidadDB->readEntidad($cPruebas);
	
	$cIdiomas2->setCodIso2($cPruebas->getCodIdiomaIso2());
	$cIdiomas2 = $cIdiomas2DB->readEntidad($cIdiomas2);
?>
<?php
	
	if(isset($_POST['fDisabled']) && !empty($_POST['fDisabled'])){
		$sDisabled = 1;	
	}else $sDisabled = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
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
function enviar()
{
	var f=document.forms[0];
	f.fItem.value=1;
	
	if (validaForm()){
		lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function abrirVentana(bImg, file){
	preurl = "view.php?bImg=" + bImg + "&File=" + file;
	prename = "File";
	var miv=window.open(preurl, prename,"height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
	miv.focus();
}
function abrirCalendario(page, titulo){
	var miC=window.open(page, titulo,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=149,Height=148');
	miC.focus();
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
function guardaryseguir(){
	
	var f=document.forms[0];
	f.fSeguir.value =1;
	if(enviar('<?php echo $_POST["MODO"];?>')){
		f.submit();
	}
}
onclick="if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
//]]>
</script>
<script language="javascript" type="text/javascript">
//<![CDATA[
function _body_onload(){	loff();	}
function _body_onunload(){	lon();	}
//]]>
</script>
</head>
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return enviar('<?php echo $_POST["MODO"];?>');">
<?
if ($_POST['MODO'] == constant("MNT_ALTA"))	$HELP="xx";
else	$HELP="xx";
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
			<table cellspacing="2" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_PRUEBA");?>&nbsp;</td>
					<td class="negro"><?php  echo $cPruebas->getNombre();?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top">Razonamiento&nbsp;</td>
					<td><?php echo $comboTIPOS_RAZONAMIENTOS->getDescripcionCombo($cPruebas->getIdTipoRazonamiento());?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_IDIOMA");?>&nbsp;</td>
					<td><?php echo $cIdiomas2->getNombre()?></td>
				</tr>
				<tr>
					<td width="5" colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
				</tr>
				<tr>
					<td width="5" colspan="3" class="naranjab">&nbsp;Número de items a agregar a la prueba por dificultad:</td>
				</tr>
				<tr>
					<td width="5" colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
				</tr>

				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" colspan="2" valign="top">
						<table cellspacing="2" cellpadding="0" border="0">
						<?php
						$rsITEMS_DIFICULTAD		= $comboITEMS_DIFICULTAD->getDatos();
						$sAsIdITEMS_DIFICULTAD		= $comboITEMS_DIFICULTAD->getIdKey();
						$sAsITEMS_DIFICULTAD		= $comboITEMS_DIFICULTAD->getDescKey();
						$iITEMS_DIFICULTAD		= $rsITEMS_DIFICULTAD->RecordCount();
						$sDefaultITEMS_DIFICULTAD	= $comboITEMS_DIFICULTAD->getDefault();
						$rsITEMS_DIFICULTAD->Move(0); //Posicionamos en el primer registro.

						while (!$rsITEMS_DIFICULTAD->EOF) {
							echo "<tr>";
							echo '<td width="15"><img src="'. constant('DIR_WS_GRAF') . 'sp.gif" width="5" height="20" border="0" alt="" /></td>';
							//Revisamos por cada dificultad los items que tenemos del razonamiento de la prueba.
							$cItems_dificultad	= new Items();  // Items
							$cItems_dificultad->setIdTipoRazonamiento($cPruebas->getIdTipoRazonamiento());
							$cItems_dificultad->setCodIdiomaIso2($cPruebas->getCodIdiomaIso2());
							$cItems_dificultad->setIdDificultad($rsITEMS_DIFICULTAD->fields[$sAsIdITEMS_DIFICULTAD]);
							$cItems_dificultad->setIdPrueba($cPruebas->getIdPrueba());
							$sql_ITEMS_DIFICULTAD = $cItemsDB->readListaPtePorDificultad($cItems_dificultad);
							//echo "<br>" . $sql_ITEMS_DIFICULTAD;
							$lista_ITEMS_DIFICULTAD = $conn->Execute($sql_ITEMS_DIFICULTAD);
							
							echo '<td>
									<input type="text" style="width: 24px;" name="fNumItemsDificultad' . $rsITEMS_DIFICULTAD->fields[$sAsIdITEMS_DIFICULTAD] . '" value="" class="cajatexto"  onchange="javascript:trim(this);" />
								  </td>';
							echo "<td>" . $rsITEMS_DIFICULTAD->fields[$sAsITEMS_DIFICULTAD] . "</td>";
							echo '<td width="15"><img src="'. constant('DIR_WS_GRAF') . 'sp.gif" width="5" height="20" border="0" alt="" /></td>';
							echo '<td >Encontrados: <b>' . $lista_ITEMS_DIFICULTAD->RecordCount() . '</b></td>';

							$rsITEMS_DIFICULTAD->MoveNext();
							echo "</tr>";
						}

						?>
						</table>
					</td>
				</tr>

				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].fItem.value=1;document.forms[0].fCodIdiomaIso2.disabled=false;document.forms[0].submit();" /></td>
			<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fOpcion" value="" />
	<input type="hidden" name="fIdPrueba" value="<?php echo $cItems->getIdPrueba()?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $cItems->getCodIdiomaIso2()?>" />
	<input type="hidden" name="fItem" value="" />
	<input type="hidden" name="fId" value="<?php echo $cItems->getId();?>" />
	<input type="hidden" name="fDisabled" value="<?php echo $sDisabled;?>" />
	<input type="hidden" name="fSeguir" value="" />
	<input type="hidden" name="fIdItem" value="<?php echo $cItems->getIdItem();?>" />
	<input type="hidden" name="fFecAlta" value="<?php echo $cItems->getFecAlta();?>" />
	<input type="hidden" name="fFecMod" value="<?php echo $cItems->getFecMod();?>" />
	<input type="hidden" name="fUsuAlta" value="<?php echo $cItems->getUsuAlta();?>" />
	<input type="hidden" name="fUsuMod" value="<?php echo $cItems->getUsuMod();?>" />
	<input type="hidden" name="LSTIdItemHast" value="<?php echo (isset($_POST['LSTIdItemHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdItemHast']) : "";?>" />
	<input type="hidden" name="LSTIdItem" value="<?php echo (isset($_POST['LSTIdItem'])) ? $cUtilidades->validaXSS($_POST['LSTIdItem']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTCodIdiomaIso2" value="<?php echo (isset($_POST['LSTCodIdiomaIso2'])) ? $cUtilidades->validaXSS($_POST['LSTCodIdiomaIso2']) : "";?>" />
	<input type="hidden" name="LSTEnunciado" value="<?php echo (isset($_POST['LSTEnunciado'])) ? $cUtilidades->validaXSS($_POST['LSTEnunciado']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTPath1" value="<?php echo (isset($_POST['LSTPath1'])) ? $cUtilidades->validaXSS($_POST['LSTPath1']) : "";?>" />
	<input type="hidden" name="LSTPath2" value="<?php echo (isset($_POST['LSTPath2'])) ? $cUtilidades->validaXSS($_POST['LSTPath2']) : "";?>" />
	<input type="hidden" name="LSTPath3" value="<?php echo (isset($_POST['LSTPath3'])) ? $cUtilidades->validaXSS($_POST['LSTPath3']) : "";?>" />
	<input type="hidden" name="LSTPath4" value="<?php echo (isset($_POST['LSTPath4'])) ? $cUtilidades->validaXSS($_POST['LSTPath4']) : "";?>" />
	<input type="hidden" name="LSTCorrecto" value="<?php echo (isset($_POST['LSTCorrecto'])) ? $cUtilidades->validaXSS($_POST['LSTCorrecto']) : "";?>" />
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
	<input type="hidden" name="LSTOrderItBy" value="<?php echo (isset($_POST['LSTOrderItBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderItBy']) : "";?>" />
	<input type="hidden" name="LSTOrderIt" value="<?php echo (isset($_POST['LSTOrderIt'])) ? $cUtilidades->validaXSS($_POST['LSTOrderIt']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="items_next_page" value="<?php echo (isset($_POST['items_next_page'])) ? $cUtilidades->validaXSS($_POST['items_next_page']) : "1";?>" />
	</div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>