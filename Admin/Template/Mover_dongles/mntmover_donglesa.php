<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }
    $rOnly="";
    if($_POST['MODO'] == constant('MNT_MODIFICAR')){
    	$rOnly = "readonly=\"readonly\"";
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
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	f.fIdEmpresa.disabled=false;

	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vNumber("<?php echo constant("STR_NUMERO_DE_DONGLES");?>:",f.fNDongles.value,11,true);
  if (parseInt(f.fNDongles.value,10) > parseInt(f.fNDonglesOrigen.value,10)){
    msg +="\nNo puede mover más unidades de las que dispone en Origen."
  }
	if(document.forms[0].fEstado!=null){
		aEstado = document.forms[0].fEstado;
		sId = "";
		for(i=0; i < aEstado.length; i++ ){
			if (aEstado[i].type == "radio" && aEstado[i].name == "fEstado"){
				if (aEstado[i].checked)
				{
					sId = aEstado[i].value;
				}
			}
		}

		msg +=vNumber("<?php echo constant("STR_ESTADO");?>:",sId,11,true);
	}
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
function setViewUnidadesOrigen(){
  var f = document.forms[0];
	var paginacargada = "Mover_dongles.php";
	$("div#unidadesOrigen").load(paginacargada,
    {
		fIdEmpresaOrigen: f.fIdEmpresaReceptora.value,
		MODO:"555",
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>"
  },function(){
    if (typeof f.fNDonglesOrigen === 'undefined' || f.fNDonglesOrigen.value <= 0){
      $("#bid-ok").attr("disabled", "disabled");
    }else{
      $('#bid-ok').removeAttr("disabled")
    }
  });
}

function setViewUnidadesDestino(){
  var f = document.forms[0];
	var paginacargada = "Mover_dongles.php";
	$("div#unidadesDestino").load(paginacargada,{
		fIdEmpresaDestino: f.fIdEmpresa.value,
		MODO:"555",
		sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" });
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
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0"  title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>

		<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return enviar('<?php echo $_POST["MODO"];?>');">
<?
if ($_POST['MODO'] == constant("MNT_ALTA"))	$HELP="xx";
else	$HELP="xx";
//echo $_POST['MODO'];
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
        <?php
          // La empresa que TRAMITA es la logada que efectua los movimientos, desde la administración es EMPRESA_PE
  				$sIdEmpresaTramita = constant("EMPRESA_PE");
        ?>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEmpresaTramita">TRAMITA</label>&nbsp;</td>
					<td><input type="hidden" id="fIdEmpresaTramita" name="fIdEmpresaTramita" value="<?php echo $sIdEmpresaTramita;?>" /><?php echo $comboEMPRESAS_TODAS->getDescripcionCombo($sIdEmpresaTramita);?></td>
				</tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
<?php
      // La empresa Receptora es a quien le quitamos las unidades, mostramos las unidades que posee
      // ORIGEN
			if($_POST['MODO'] != constant("MNT_ALTA")){
				$sIdEmpresaReceptora = $cEntidad->getIdEmpresaReceptora();
			}else{
				$sIdEmpresaReceptora = $cEmpresaLogada->getIdPadre();
			}
?>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEmpresaReceptora">ORIGEN</label>&nbsp;</td>
          <td><?php $comboEMPRESAS->setNombre("fIdEmpresaReceptora");?><?php echo $comboEMPRESAS->getHTMLCombo("1","obliga",$sIdEmpresaReceptora," onchange=\"javascript:setViewUnidadesOrigen()\"","");?></td>
				</tr>
        <tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNDonglesOrigen">Unidades en ORIGEN</label>&nbsp;</td>
          <td>
              <div id="unidadesOrigen">
                <input type="text" disabled="disabled" id="fNDonglesOrigen" name="fNDonglesOrigen" readonly="readonly" value="" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />
              </div>
          </td>
				</tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
        <tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
<?php
      //La empresa Solicita es el Destino de las unidades
      //DESTINO
			if($_POST['MODO'] != constant("MNT_ALTA")){
				$sIdEmpresaSolicita = $cEntidad->getIdEmpresa();
			}else{
				$sIdEmpresaSolicita = $cEmpresaLogada->getIdEmpresa();
			}
?>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEmpresa">DESTINO</label>&nbsp;</td>
          <td><?php $comboEMPRESAS->setNombre("fIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLCombo("1","obliga",$sIdEmpresaSolicita," onchange=\"javascript:setViewUnidadesDestino()\"","");?></td>
				</tr>
        <tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNDonglesDestino">Unidades en DESTINO</label>&nbsp;</td>
          <td>
              <div id="unidadesDestino">
                <input type="text" disabled="disabled" id="fNDonglesDestino" name="fNDonglesDestino" readonly="readonly" value="" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />
              </div>
          </td>
				</tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNDongles">Unidades a mover</label>&nbsp;</td>
					<td><input type="text" id="fNDongles" name="fNDongles" <?php echo $rOnly?> value="<?php echo $cEntidad->getNDongles();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
				</tr>
<?php		$sDis="";
			if($_POST['MODO'] != constant("MNT_ALTA")){
				if($sHijos != ""){
					if ($cEntidad->getEstado() != 0){
						$sDis='disabled="disabled"';
					}else{
						if($cEntidad->getIdEmpresa() == constant("EMPRESA_PE")){
							$sDis="";
						}else{
							if ($cEntidad->getIdEmpresa() != $cEmpresaLogada->getIdEmpresa()){
								$sDis="";
							}else{
								$sDis='disabled="disabled"';
							}
						}
					}
				}else{
					$sDis='disabled="disabled"';
				}
?>
					<tr>
						<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
						<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fEstado"><?php echo constant("STR_ESTADO");?></label>&nbsp;</td>
						<td><input type="radio" name="fEstado" id="fEstado2" value="2" <?php echo $sDis?> <?php echo ($cEntidad->getEstado() != "" && strtoupper($cEntidad->getEstado()) == "2") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fEstado2">Rechazada</label>&nbsp;<input type="radio" name="fEstado" id="fEstado1" value="1" <?php echo $sDis?> <?php echo ($cEntidad->getEstado() != "" && strtoupper($cEntidad->getEstado()) == "1") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fEstado1">Aceptada</label>&nbsp;<input type="radio" name="fEstado" id="fEstado0" value="0" <?php echo $sDis?> <?php echo ($cEntidad->getEstado() != "" && strtoupper($cEntidad->getEstado()) == "0") ? "checked=\"checked\"" : "";?> />&nbsp;<label for="fEstado0">Pendiente</label>&nbsp;<input type="hidden" name="fEstadoHast" value="<?php echo $cEntidad->getEstado();?>" /></td>
					</tr>
<?php
				}
?>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<!--<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;lon();document.forms[0].submit();" /></td>-->
			<?php if($sDis==""){?>
				<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR");?>" /></td>
			<?php }?>
		</tr>
	</table>
	</div>
</div>
	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdPeticion" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdPeticion() : $_POST['fIdPeticion'];?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO");?>" />
	<input type="hidden" name="LSTIdPeticionHast" value="<?php echo (isset($_POST['LSTIdPeticionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPeticionHast']) : "";?>" />
	<input type="hidden" name="LSTIdPeticion" value="<?php echo (isset($_POST['LSTIdPeticion'])) ? $cUtilidades->validaXSS($_POST['LSTIdPeticion']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaReceptoraHast" value="<?php echo (isset($_POST['LSTIdEmpresaReceptoraHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaReceptoraHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaReceptora" value="<?php echo (isset($_POST['LSTIdEmpresaReceptora'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaReceptora']) : "";?>" />
	<input type="hidden" name="LSTNDonglesHast" value="<?php echo (isset($_POST['LSTNDonglesHast'])) ? $cUtilidades->validaXSS($_POST['LSTNDonglesHast']) : "";?>" />
	<input type="hidden" name="LSTNDongles" value="<?php echo (isset($_POST['LSTNDongles'])) ? $cUtilidades->validaXSS($_POST['LSTNDongles']) : "";?>" />
	<input type="hidden" name="LSTEstadoHast" value="<?php echo (isset($_POST['LSTEstadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTEstadoHast']) : "";?>" />
	<input type="hidden" name="LSTEstado" value="<?php echo (isset($_POST['LSTEstado'])) ? $cUtilidades->validaXSS($_POST['LSTEstado']) : "";?>" />
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
	<input type="hidden" name="mover_dongles_next_page" value="<?php echo (isset($_POST['mover_dongles_next_page'])) ? $cUtilidades->validaXSS($_POST['mover_dongles_next_page']) : "1";?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?>
</div>
</form>
</body></html>
	<input type="hidden" name="mover_dongles_next_page" value="<?php echo (isset($_POST['mover_dongles_next_page'])) ? $cUtilidades->validaXSS($_POST['mover_dongles_next_page']) : "1";?>" />
