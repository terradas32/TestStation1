<?php
    if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../../include/SeguridadTemplate.php");
    }else{
    	require_once("include/SeguridadTemplate.php");
    }

	$clave = 'candidatos';
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
function setPK(idCandidato,idEmpresa,idProceso)
{
	var f=document.forms[0];
	f.fIdCandidato.value=idCandidato;
	f.fIdEmpresa.value=idEmpresa;
	f.fIdProceso.value=idProceso;
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
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_EMPRESA");?></td>
			<td class="tdnaranjab" align="center" valign="top"><?php echo constant("STR_PROCESO");?></td>
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
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_APELLIDO_1"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='apellido1';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_APELLIDO_1"));?>';return true"><?php echo constant("STR_APELLIDO_1");?><?php echo $sPinta;?></a></td>
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
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_APELLIDO_2"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='apellido2';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_APELLIDO_2"));?>';return true"><?php echo constant("STR_APELLIDO_2");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "dni"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_NIF"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='dni';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_NIF"));?>';return true"><?php echo constant("STR_NIF");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "mail"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_EMAIL"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='mail';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_EMAIL"));?>';return true"><?php echo constant("STR_EMAIL");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "telefono"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_TELEFONO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='telefono';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_TELEFONO"));?>';return true"><?php echo constant("STR_TELEFONO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "informado"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_INFORMADO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='informado';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_INFORMADO"));?>';return true"><?php echo constant("STR_INFORMADO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "ultimoLogin"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_ULTIMO_LOGIN"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='ultimoLogin';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_ULTIMO_LOGIN"));?>';return true"><?php echo constant("STR_ULTIMO_LOGIN");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "finalizado"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FINALIZADO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='finalizado';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FINALIZADO"));?>';return true"><?php echo constant("STR_FINALIZADO");?><?php echo $sPinta;?></a></td>
			<?php 
			$sPinta = "";
			$sOrden = "ASC";
			if ($cEntidad->getOrderBy() == "fechaFinalizado"){
				if ($cEntidad->getOrder() == "ASC"){
					$sOrden = "DESC";
					$sPinta = $strOrderASC;
				}else{
					$sOrden = "ASC";
					$sPinta = $strOrderDESC;
				}
			}
			?>
			<td class="tdnaranjab" align="center"><a title="<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo htmlentities(constant("STR_FECHA_DE_FINALIZADO"), ENT_COMPAT, "UTF-8");?>" href="#" class="tdnaranjab" style="color:#ffffff" onclick="javascript:document.forms[0].fReordenar.value=1;document.forms[0].LSTOrderBy.value='fechaFinalizado';document.forms[0].LSTOrder.value='<?php echo $sOrden;?>';enviar('<?php echo constant("MNT_LISTAR");?>');" onmouseout="window.status='';return true" onmouseover="window.status='<?php echo constant("STR_ORDENAR_POR");?> >>> <?php echo addslashes(constant("STR_FECHA_DE_FINALIZADO"));?>';return true"><?php echo constant("STR_FECHA_DE_FINALIZADO");?><?php echo $sPinta;?></a></td>
		</tr>
		<?php	$i=0;
		while (!$lista->EOF)
		{
			$comboPROCESOS	= new Combo($conn,"fIdProceso","idProceso","nombre","Descripcion","procesos","",constant("SLC_OPCION"),"idEmpresa=" . $conn->qstr($lista->fields['idEmpresa'], false) . " AND idProceso=" . $conn->qstr($lista->fields['idProceso'], false),"","fecMod");
		?>
		<tr onmouseover="this.bgColor='<?php echo constant("ONMOUSEOVER");?>'" onmouseout="this.bgColor='<?php echo constant("ONMOUSEOUT");?>'" bgcolor="<?php echo constant("ONMOUSEOUT");?>">
			<td bgcolor="<?php echo constant("BG_COLOR");?>"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="10" height="1" border="0" alt="" /></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idEmpresa']);?>"><?php echo $comboEMPRESAS->getDescripcionCombo($lista->fields['idEmpresa']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['idProceso']);?>"><?php echo $comboPROCESOS->getDescripcionCombo($lista->fields['idProceso']);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['nombre']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['nombre'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['apellido1']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['apellido1'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['apellido2']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['apellido2'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['dni']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['dni'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['mail']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['mail'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['telefono']);?>"><?php echo substr(str_replace("\n","<br />",strip_tags($lista->fields['telefono'], "<b><i><u><strong><br><br />")),0,255);?></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['informado']);?>"><?php echo ($lista->fields['informado'] > 0 ) ? constant("STR_SI") : constant("STR_NO");?></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['ultimoLogin']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['ultimoLogin'];?>'));</script></td>
			<td class="tddatoslista" valign="top" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['finalizado']);?>"><?php echo ($lista->fields['finalizado'] > 0 ) ? constant("STR_SI") : constant("STR_NO");?></td>
			<td class="tddatoslista" valign="top" align="center" onclick="javascript:setPK('<?php echo addslashes($lista->fields['idCandidato']);?>','<?php echo addslashes($lista->fields['idEmpresa']);?>','<?php echo addslashes($lista->fields['idProceso']);?>');enviar(<?php echo constant("MNT_CONSULTAR");?>);" title="<?php echo strip_tags($lista->fields['fechaFinalizado']);?>"><script language="javascript" type="text/javascript">document.write(formatThisDate('<?php echo $lista->fields['fechaFinalizado'];?>'));</script></td>
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
			<td width="100%" colspan="2" class="naranjab"><?php echo sprintf(constant("STR_LISTA_DE_"),str_replace('_', ' ', constant("STR_CANDIDATOS")));?><a href="#_" onclick="javascript:document.forms[0].action='sqlToExcel.php';document.forms[0].submit();document.forms[0].action='<?php echo $_SERVER['PHP_SELF'];?>';"><img src="<?php echo constant('DIR_WS_GRAF');?>excel.gif" width="34" height="35" align="right" border="0" alt="<?php echo constant("STR_EXPORTAR_A_EXCEL");?>" /></a></td>
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
	<input type="hidden" name="fIdCandidato" value="" />
	<input type="hidden" name="fIdEmpresa" value="" />
	<input type="hidden" name="fIdProceso" value="" />
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_LISTAR");?>" />
	<input type="hidden" name="fReordenar" value="" />
	<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : "";?>" />
	<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : "";?>" />
	<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : "";?>" />
	<input type="hidden" name="LSTDni" value="<?php echo (isset($_POST['LSTDni'])) ? $cUtilidades->validaXSS($_POST['LSTDni']) : "";?>" />
	<input type="hidden" name="LSTMail" value="<?php echo (isset($_POST['LSTMail'])) ? $cUtilidades->validaXSS($_POST['LSTMail']) : "";?>" />
	<input type="hidden" name="LSTPassword" value="<?php echo (isset($_POST['LSTPassword'])) ? $cUtilidades->validaXSS($_POST['LSTPassword']) : "";?>" />
	<input type="hidden" name="LSTIdTratamientoHast" value="<?php echo (isset($_POST['LSTIdTratamientoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTratamientoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTratamiento" value="<?php echo (isset($_POST['LSTIdTratamiento'])) ? $cUtilidades->validaXSS($_POST['LSTIdTratamiento']) : "";?>" />
	<input type="hidden" name="LSTIdSexoHast" value="<?php echo (isset($_POST['LSTIdSexoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexoHast']) : "";?>" />
	<input type="hidden" name="LSTIdSexo" value="<?php echo (isset($_POST['LSTIdSexo'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexo']) : "";?>" />
	<input type="hidden" name="LSTIdEdadHast" value="<?php echo (isset($_POST['LSTIdEdadHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdadHast']) : "";?>" />
	<input type="hidden" name="LSTIdEdad" value="<?php echo (isset($_POST['LSTIdEdad'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdad']) : "";?>" />
	<input type="hidden" name="LSTFechaNacimientoHast" value="<?php echo (isset($_POST['LSTFechaNacimientoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaNacimientoHast']) : "";?>" />
	<input type="hidden" name="LSTFechaNacimiento" value="<?php echo (isset($_POST['LSTFechaNacimiento'])) ? $cUtilidades->validaXSS($_POST['LSTFechaNacimiento']) : "";?>" />
	<input type="hidden" name="LSTIdPais" value="<?php echo (isset($_POST['LSTIdPais'])) ? $cUtilidades->validaXSS($_POST['LSTIdPais']) : "";?>" />
	<input type="hidden" name="LSTIdProvincia" value="<?php echo (isset($_POST['LSTIdProvincia'])) ? $cUtilidades->validaXSS($_POST['LSTIdProvincia']) : "";?>" />
	<input type="hidden" name="LSTIdMunicipio" value="<?php echo (isset($_POST['LSTIdMunicipio'])) ? $cUtilidades->validaXSS($_POST['LSTIdMunicipio']) : "";?>" />
	<input type="hidden" name="LSTIdZona" value="<?php echo (isset($_POST['LSTIdZona'])) ? $cUtilidades->validaXSS($_POST['LSTIdZona']) : "";?>" />
	<input type="hidden" name="LSTDireccion" value="<?php echo (isset($_POST['LSTDireccion'])) ? $cUtilidades->validaXSS($_POST['LSTDireccion']) : "";?>" />
	<input type="hidden" name="LSTCodPostal" value="<?php echo (isset($_POST['LSTCodPostal'])) ? $cUtilidades->validaXSS($_POST['LSTCodPostal']) : "";?>" />
	<input type="hidden" name="LSTIdFormacionHast" value="<?php echo (isset($_POST['LSTIdFormacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacionHast']) : "";?>" />
	<input type="hidden" name="LSTIdFormacion" value="<?php echo (isset($_POST['LSTIdFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacion']) : "";?>" />
	<input type="hidden" name="LSTIdNivelHast" value="<?php echo (isset($_POST['LSTIdNivelHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivelHast']) : "";?>" />
	<input type="hidden" name="LSTIdNivel" value="<?php echo (isset($_POST['LSTIdNivel'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivel']) : "";?>" />
	<input type="hidden" name="LSTIdAreaHast" value="<?php echo (isset($_POST['LSTIdAreaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdAreaHast']) : "";?>" />
	<input type="hidden" name="LSTIdArea" value="<?php echo (isset($_POST['LSTIdArea'])) ? $cUtilidades->validaXSS($_POST['LSTIdArea']) : "";?>" />
	<input type="hidden" name="LSTTelefono" value="<?php echo (isset($_POST['LSTTelefono'])) ? $cUtilidades->validaXSS($_POST['LSTTelefono']) : "";?>" />
	<input type="hidden" name="LSTEstadoCivil" value="<?php echo (isset($_POST['LSTEstadoCivil'])) ? $cUtilidades->validaXSS($_POST['LSTEstadoCivil']) : "";?>" />
	<input type="hidden" name="LSTNacionalidad" value="<?php echo (isset($_POST['LSTNacionalidad'])) ? $cUtilidades->validaXSS($_POST['LSTNacionalidad']) : "";?>" />
	<input type="hidden" name="LSTInformadoHast" value="<?php echo (isset($_POST['LSTInformadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTInformadoHast']) : "";?>" />
	<input type="hidden" name="LSTInformado" value="<?php echo (isset($_POST['LSTInformado'])) ? $cUtilidades->validaXSS($_POST['LSTInformado']) : "";?>" />
	<input type="hidden" name="LSTFinalizadoHast" value="<?php echo (isset($_POST['LSTFinalizadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFinalizadoHast']) : "";?>" />
	<input type="hidden" name="LSTFinalizado" value="<?php echo (isset($_POST['LSTFinalizado'])) ? $cUtilidades->validaXSS($_POST['LSTFinalizado']) : "";?>" />
	<input type="hidden" name="LSTFechaFinalizadoHast" value="<?php echo (isset($_POST['LSTFechaFinalizadoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinalizadoHast']) : "";?>" />
	<input type="hidden" name="LSTFechaFinalizado" value="<?php echo (isset($_POST['LSTFechaFinalizado'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinalizado']) : "";?>" />
	<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : "";?>" />
	<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : "";?>" />
	<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : "";?>" />
	<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : "";?>" />
	<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : "";?>" />
	<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : "";?>" />
	<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : "";?>" />
	<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : "";?>" />
	<input type="hidden" name="LSTUltimoLoginHast" value="<?php echo (isset($_POST['LSTUltimoLoginHast'])) ? $cUtilidades->validaXSS($_POST['LSTUltimoLoginHast']) : "";?>" />
	<input type="hidden" name="LSTUltimoLogin" value="<?php echo (isset($_POST['LSTUltimoLogin'])) ? $cUtilidades->validaXSS($_POST['LSTUltimoLogin']) : "";?>" />
	<input type="hidden" name="LSTToken" value="<?php echo (isset($_POST['LSTToken'])) ? $cUtilidades->validaXSS($_POST['LSTToken']) : "";?>" />
	<input type="hidden" name="LSTUltimaAccHast" value="<?php echo (isset($_POST['LSTUltimaAccHast'])) ? $cUtilidades->validaXSS($_POST['LSTUltimaAccHast']) : "";?>" />
	<input type="hidden" name="LSTUltimaAcc" value="<?php echo (isset($_POST['LSTUltimaAcc'])) ? $cUtilidades->validaXSS($_POST['LSTUltimaAcc']) : "";?>" />
	<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderBy']) : "";?>" />
	<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $cUtilidades->validaXSS($_POST['LSTOrder']) : "";?>" />
	<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA");?>" />
	<input type="hidden" name="candidatos_next_page" value="<?php echo (isset($_POST['candidatos_next_page'])) ? $cUtilidades->validaXSS($_POST['candidatos_next_page']) : "1";?>" />
	<input type="hidden" name="signature" value="<?php echo(base64_encode($sig)); ?>" />
	<input type="hidden" name="fSQLtoEXCEL" value="<?php echo(base64_encode($token)); ?>" />
	</div>
<?php include (constant("DIR_WS_INCLUDE") . 'menus.php');?>
<?php //include (constant("DIR_WS_INCLUDE") . 'derecha.php');?>
<?php include (constant("DIR_WS_INCLUDE") . 'pie.php');?>
</div>
</form>
</body></html>