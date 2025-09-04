<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

	$clave = obtenerClavePlantilla();
	$payload = [
		'sql'   => $sql,
		'nombre'=> $clave,
		'ts'    => time(),
		'nonce' => bin2hex(random_bytes(8)),
	];

	$token = excel_encrypt_payload($payload, EXCEL_ENC_KEY);
	$sig   = excel_sign($token, EXCEL_HMAC_KEY);
	$urlExcel = 'sqlToExcel.php?fSQLtoEXCEL='.base64_encode($token).'&signature='.base64_encode($sig);
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
function setPK(idEmpresa,idProceso,idCandidato,idPrueba)
{
	var f=document.forms[0];
	f.fIdEmpresa.value=idEmpresa;
	f.fIdProceso.value=idProceso;
	f.fIdCandidato.value=idCandidato;
	f.fIdPrueba.value=idPrueba;
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
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_EMPRESA");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descEmpresa"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EMPRESA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descEmpresa';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EMPRESA"));?>';return true"><?php echo constant("STR_EMPRESA");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_PROCESO");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descProceso"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PROCESO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descProceso';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PROCESO"));?>';return true"><?php echo constant("STR_PROCESO");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_CANDIDATO");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "nombre"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_NOMBRE"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='nombre';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_NOMBRE"));?>';return true"><?php echo constant("STR_NOMBRE");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "apellido1"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_APELLIDO1"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='apellido1';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_APELLIDO1"));?>';return true"><?php echo constant("STR_APELLIDO1");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "apellido2"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_APELLIDO2"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='apellido2';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_APELLIDO2"));?>';return true"><?php echo constant("STR_APELLIDO2");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "email"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EMAIL"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='email';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EMAIL"));?>';return true"><?php echo constant("STR_EMAIL");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_PRUEBA");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descPrueba"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PRUEBA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descPrueba';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PRUEBA"));?>';return true"><?php echo constant("STR_PRUEBA");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "fecPrueba"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FECHA_DE_PRUEBA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecPrueba';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FECHA_DE_PRUEBA"));?>';return true"><?php echo constant("STR_FECHA_DE_PRUEBA");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_BAREMO");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descBaremo"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_BAREMO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descBaremo';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_BAREMO"));?>';return true"><?php echo constant("STR_BAREMO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "fecAltaProceso"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FECHA_DE_ALTA_PROCESO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fecAltaProceso';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FECHA_DE_ALTA_PROCESO"));?>';return true"><?php echo constant("STR_FECHA_DE_ALTA_PROCESO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "correctas"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_CORRECTAS"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='correctas';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_CORRECTAS"));?>';return true"><?php echo constant("STR_CORRECTAS");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "contestadas"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_CONTESTADAS"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='contestadas';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_CONTESTADAS"));?>';return true"><?php echo constant("STR_CONTESTADAS");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "percentil"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_PERCENTIL"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='percentil';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_PERCENTIL"));?>';return true"><?php echo constant("STR_PERCENTIL");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "ir"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_IR"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='ir';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_IR"));?>';return true"><?php echo constant("STR_IR");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "ip"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_IP"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='ip';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_IP"));?>';return true"><?php echo constant("STR_IP");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "por"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_POR"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='por';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_POR"));?>';return true"><?php echo constant("STR_POR");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "estilo"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ESTILO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='estilo';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ESTILO"));?>';return true"><?php echo constant("STR_ESTILO");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_SEXO");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descSexo"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_SEXO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descSexo';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_SEXO"));?>';return true"><?php echo constant("STR_SEXO");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_EDAD");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descEdad"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EDAD"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descEdad';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EDAD"));?>';return true"><?php echo constant("STR_EDAD");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_IDFORMACION");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descFormacion"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FORMACION"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descFormacion';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FORMACION"));?>';return true"><?php echo constant("STR_FORMACION");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_NIVEL");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descNivel"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_NIVEL"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descNivel';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_NIVEL"));?>';return true"><?php echo constant("STR_NIVEL");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_ID_AREA");?></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "descArea"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_AREA"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='descArea';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_AREA"));?>';return true"><?php echo constant("STR_AREA");?><?php echo $sPinta;?></a></td>
			<td class="tdnaranjab" align="center"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></td>
		</tr>
		<?php $i=0;
		while (!$lista->EOF)
		{
		?>
		<tr onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idEmpresa']);?>"><?php echo $comboEMPRESAS->getDescripcionCombo($lista->fields['idEmpresa']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descEmpresa']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descEmpresa'], "<b><i><u><strong><br><br />")),0,500);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idProceso']);?>"><?php echo $comboPROCESOS->getDescripcionCombo($lista->fields['idProceso']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descProceso']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descProceso'], "<b><i><u><strong><br><br />")),0,500);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idCandidato']);?>"><?php echo $comboCANDIDATOS->getDescripcionCombo($lista->fields['idCandidato']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['nombre']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['nombre'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['apellido1']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['apellido1'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['apellido2']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['apellido2'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['email']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['email'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idPrueba']);?>"><?php echo $comboPRUEBAS->getDescripcionCombo($lista->fields['idPrueba']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descPrueba']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descPrueba'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fecPrueba']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecPrueba'];?>'));</script></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idBaremo']);?>"><?php echo $comboBAREMOS->getDescripcionCombo($lista->fields['idBaremo']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descBaremo']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descBaremo'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fecAltaProceso']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fecAltaProceso'];?>'));</script></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['correctas']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['correctas'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['contestadas']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['contestadas'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['percentil']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['percentil'], "<b><i><u><strong><br><br />")),0,11);?></td>
			<td class="tddatoslista" valign="top" align="right" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['ir']);?>"><?php echo number_format($lista->fields['ir'], constant("USR_NUMDECIMALES"), constant("USR_SEPARADORDECIMAL"), constant("USR_SEPARADORMILES"));?> </td>
			<td class="tddatoslista" valign="top" align="right" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['ip']);?>"><?php echo number_format($lista->fields['ip'], constant("USR_NUMDECIMALES"), constant("USR_SEPARADORDECIMAL"), constant("USR_SEPARADORMILES"));?> </td>
			<td class="tddatoslista" valign="top" align="right" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['por']);?>"><?php echo number_format($lista->fields['por'], constant("USR_NUMDECIMALES"), constant("USR_SEPARADORDECIMAL"), constant("USR_SEPARADORMILES"));?> </td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['estilo']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['estilo'], "<b><i><u><strong><br><br />")),0,65535);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idSexo']);?>"><?php echo $comboSEXOS->getDescripcionCombo($lista->fields['idSexo']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descSexo']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descSexo'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idEdad']);?>"><?php echo $comboEDADES->getDescripcionCombo($lista->fields['idEdad']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descEdad']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descEdad'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idFormacion']);?>"><?php echo $comboFORMACIONES->getDescripcionCombo($lista->fields['idFormacion']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descFormacion']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descFormacion'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idNivel']);?>"><?php echo $comboNIVELESJERARQUICOS->getDescripcionCombo($lista->fields['idNivel']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descNivel']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descNivel'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idArea']);?>"><?php echo $comboAREAS->getDescripcionCombo($lista->fields['idArea']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['descArea']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['descArea'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="negro" align="center" valign="middle"><?php if($_bBorrar) {?><a href="#" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>','<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idPrueba']);?>');confBorrar(<?php echo constant("MNT_BORRAR");?>,'<?php echo constant("DEL_GENERICO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>menos.gif" width="9" height="9" border="0" alt="<?php echo constant("STR_BORRAR");?>" /></a><?php }?></td>
		</tr>
		<?php $i++;
		$lista->MoveNext();
		} ?>
	</table>
	<?php $s = ob_get_contents();
	ob_end_clean();
	$pager->Render($s);?>

<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
			<td width="100%" colspan="2" class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', constant("STR_EXPORTAPTITUDINALES")));?><a href="#_" onclick="javascript:document.forms[0].action='sqlToExcel.php';document.forms[0].submit();document.forms[0].action='<?php echo $_SERVER['PHP_SELF'];?>';"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
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
	<input type="hidden" name="fIdEmpresa" value="" />
	<input type="hidden" name="fIdProceso" value="" />
	<input type="hidden" name="fIdCandidato" value="" />
	<input type="hidden" name="fIdPrueba" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="signature" value="<?php echo(base64_encode($sig)); ?>" />
	<input type="hidden" name="fSQLtoEXCEL" value="<?php echo(base64_encode($token)); ?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTDescEmpresa" value="<?php echo (isset($_POST['LSTDescEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTDescEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTDescProceso" value="<?php echo (isset($_POST['LSTDescProceso'])) ? $cUtilidades->validaXSS($_POST['LSTDescProceso']) : "";?>" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : "";?>" />
	<input type="hidden" name="LSTEmail" value="<?php echo (isset($_POST['LSTEmail'])) ? $cUtilidades->validaXSS($_POST['LSTEmail']) : "";?>" />
	<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : "";?>" />
	<input type="hidden" name="LSTDescPrueba" value="<?php echo (isset($_POST['LSTDescPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTDescPrueba']) : "";?>" />
	<input type="hidden" name="LSTFecPruebaHast" value="<?php echo (isset($_POST['LSTFecPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecPruebaHast']) : "";?>" />
	<input type="hidden" name="LSTFecPrueba" value="<?php echo (isset($_POST['LSTFecPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTFecPrueba']) : "";?>" />
	<input type="hidden" name="LSTIdBaremoHast" value="<?php echo (isset($_POST['LSTIdBaremoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremoHast']) : "";?>" />
	<input type="hidden" name="LSTIdBaremo" value="<?php echo (isset($_POST['LSTIdBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremo']) : "";?>" />
	<input type="hidden" name="LSTDescBaremo" value="<?php echo (isset($_POST['LSTDescBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTDescBaremo']) : "";?>" />
	<input type="hidden" name="LSTFecAltaProcesoHast" value="<?php echo (isset($_POST['LSTFecAltaProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTFecAltaProceso" value="<?php echo (isset($_POST['LSTFecAltaProceso'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaProceso']) : "";?>" />
	<input type="hidden" name="LSTCorrectas" value="<?php echo (isset($_POST['LSTCorrectas'])) ? $cUtilidades->validaXSS($_POST['LSTCorrectas']) : "";?>" />
	<input type="hidden" name="LSTContestadas" value="<?php echo (isset($_POST['LSTContestadas'])) ? $cUtilidades->validaXSS($_POST['LSTContestadas']) : "";?>" />
	<input type="hidden" name="LSTPercentilHast" value="<?php echo (isset($_POST['LSTPercentilHast'])) ? $cUtilidades->validaXSS($_POST['LSTPercentilHast']) : "";?>" />
	<input type="hidden" name="LSTPercentil" value="<?php echo (isset($_POST['LSTPercentil'])) ? $cUtilidades->validaXSS($_POST['LSTPercentil']) : "";?>" />
	<input type="hidden" name="LSTIrHast" value="<?php echo (isset($_POST['LSTIrHast'])) ? $cUtilidades->validaXSS($_POST['LSTIrHast']) : "";?>" />
	<input type="hidden" name="LSTIr" value="<?php echo (isset($_POST['LSTIr'])) ? $cUtilidades->validaXSS($_POST['LSTIr']) : "";?>" />
	<input type="hidden" name="LSTIpHast" value="<?php echo (isset($_POST['LSTIpHast'])) ? $cUtilidades->validaXSS($_POST['LSTIpHast']) : "";?>" />
	<input type="hidden" name="LSTIp" value="<?php echo (isset($_POST['LSTIp'])) ? $cUtilidades->validaXSS($_POST['LSTIp']) : "";?>" />
	<input type="hidden" name="LSTPorHast" value="<?php echo (isset($_POST['LSTPorHast'])) ? $cUtilidades->validaXSS($_POST['LSTPorHast']) : "";?>" />
	<input type="hidden" name="LSTPor" value="<?php echo (isset($_POST['LSTPor'])) ? $cUtilidades->validaXSS($_POST['LSTPor']) : "";?>" />
	<input type="hidden" name="LSTEstilo" value="<?php echo (isset($_POST['LSTEstilo'])) ? $cUtilidades->validaXSS($_POST['LSTEstilo']) : "";?>" />
	<input type="hidden" name="LSTIdSexoHast" value="<?php echo (isset($_POST['LSTIdSexoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexoHast']) : "";?>" />
	<input type="hidden" name="LSTIdSexo" value="<?php echo (isset($_POST['LSTIdSexo'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexo']) : "";?>" />
	<input type="hidden" name="LSTDescSexo" value="<?php echo (isset($_POST['LSTDescSexo'])) ? $cUtilidades->validaXSS($_POST['LSTDescSexo']) : "";?>" />
	<input type="hidden" name="LSTIdEdadHast" value="<?php echo (isset($_POST['LSTIdEdadHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdadHast']) : "";?>" />
	<input type="hidden" name="LSTIdEdad" value="<?php echo (isset($_POST['LSTIdEdad'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdad']) : "";?>" />
	<input type="hidden" name="LSTDescEdad" value="<?php echo (isset($_POST['LSTDescEdad'])) ? $cUtilidades->validaXSS($_POST['LSTDescEdad']) : "";?>" />
	<input type="hidden" name="LSTIdFormacionHast" value="<?php echo (isset($_POST['LSTIdFormacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacionHast']) : "";?>" />
	<input type="hidden" name="LSTIdFormacion" value="<?php echo (isset($_POST['LSTIdFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacion']) : "";?>" />
	<input type="hidden" name="LSTDescFormacion" value="<?php echo (isset($_POST['LSTDescFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTDescFormacion']) : "";?>" />
	<input type="hidden" name="LSTIdNivelHast" value="<?php echo (isset($_POST['LSTIdNivelHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivelHast']) : "";?>" />
	<input type="hidden" name="LSTIdNivel" value="<?php echo (isset($_POST['LSTIdNivel'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivel']) : "";?>" />
	<input type="hidden" name="LSTDescNivel" value="<?php echo (isset($_POST['LSTDescNivel'])) ? $cUtilidades->validaXSS($_POST['LSTDescNivel']) : "";?>" />
	<input type="hidden" name="LSTIdAreaHast" value="<?php echo (isset($_POST['LSTIdAreaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdAreaHast']) : "";?>" />
	<input type="hidden" name="LSTIdArea" value="<?php echo (isset($_POST['LSTIdArea'])) ? $cUtilidades->validaXSS($_POST['LSTIdArea']) : "";?>" />
	<input type="hidden" name="LSTDescArea" value="<?php echo (isset($_POST['LSTDescArea'])) ? $cUtilidades->validaXSS($_POST['LSTDescArea']) : "";?>" />
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
	<input type="hidden" name="export_aptitudinales_next_page" value="<?php echo (isset($_POST['export_aptitudinales_next_page'])) ? $cUtilidades->validaXSS($_POST['export_aptitudinales_next_page']) : "1";?>" />
</div>
<?php include (constant("DIR_WS_INCLUDE") . 'menus.php');?>
<?php //include (constant("DIR_WS_INCLUDE") . 'derecha.php');?>
<?php include (constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>