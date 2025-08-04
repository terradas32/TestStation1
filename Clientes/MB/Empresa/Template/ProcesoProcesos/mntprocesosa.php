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
		
<title><?php echo constant("NOMBRE_SITE");?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script src="https://cdn.tiny.cloud/1/19u4q91vla6r5niw2cs7kaymfs18v3j11oizctq52xltyrf4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script><script>tinymce.init({ selector:'.tinymce' });</script>

<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
				
		var	hora = f.fHoraInicio.value;
		if(hora.length!=5){
			alert("Formato de Hora INICIO incorrecto, el formato debe ser: HH:MM");
			return false;
		}
		
		a=hora.charAt(0);
		b=hora.charAt(1);
		c=hora.charAt(2);
		d=hora.charAt(3);
		if (a>=2 && b>3) {
			alert("hora INICIO incorrecta");
			return false;
		}
		if(c!= ":"){
			alert("El formato hora INICIO debe ser HH:MM");
			return false;	
		}
		if (d>5) {
			alert("minutos hora INICIO incorrectos");
			return false;
		}

		var	horaF = f.fHoraFin.value;
		if(horaF.length!=5){
			alert("Formato de Hora FIN incorrecto, el formato debe ser: HH:MM");
			return false;
		}

		aF=horaF.charAt(0);
		bF=horaF.charAt(1);
		cF=horaF.charAt(2);
		dF=horaF.charAt(3);
		if (aF>=2 && bF>3) {
			alert("hora FIN incorrecta");
			return false;
		}
		if(cF!= ":"){
			alert("El formato hora FIN debe ser HH:MM");
			return false;	
		}
		if (dF>5) {
			alert("minutos hora FIN incorrectos");
			return false;
		}
//		if (!CheckTime(hora)){
//			return false;
//		}
//		if (!CheckTime(horaF)){
//			return false;
//		}
			
		lon();
		f.fFechaInicio.value=cFechaFormat(f.fFechaInicio.value);
		f.fFechaFin.value=cFechaFormat(f.fFechaFin.value);
		return true;
	}else	return false;
}
function enviarNuevo(modo)
{
	var f=document.forms[0];
	f.MODO.value =modo;
	f.submit();
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	if(f.fCheckHi.disabled){
		f.fCheckHi.disabled='false';
	}
	var bCheckI = false;
	var bCheckF = false;
	if(f.fCheckHi.checked){
		bCheckI = true;
	}
	
	msg +=vNumber("<?php echo constant("STR_EMPRESA");?>:",f.fIdEmpresa.value,11,true);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fNombre.value,255,true);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.fDescripcion.value,255,false);
//	msg +=vString("<?php echo constant("STR_OBSERVACIONES");?>:",f.fObservaciones.value,4000,false);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_INICIO");?>:",f.fFechaInicio.value,10,true);
	msg +=vNumber("<?php echo constant("STR_HORA_DE_INICIO");?>:",f.fHoraInicio.value,5,true);
	msg +=vDate("<?php echo constant("STR_FECHA_DE_FIN");?>:",f.fFechaFin.value,10,true);
	msg +=vNumber("<?php echo constant("STR_HORA_DE_FIN");?>:",f.fHoraFin.value,5,true);
	msg +=vNumber("<?php echo constant("STR_MODO_REALIZACION_PRUEBAS");?>:",f.fIdModoRealizacion.value,11,true);

	if (msg == "") {
			
		<?php 
			$sFecha = date("d/m/Y");
			$sHoraJS = date("G");
			$sMin = date("i");
		?>
		var HI= f.fHoraInicio.value;
		var aI=HI.charAt(0);
		var bI=HI.charAt(1);
		var cI=HI.charAt(2);
		var dI=HI.charAt(3);
		var eI=HI.charAt(4);
		var horaI = aI + bI;
		var minI = dI + eI;
		
		var HF= f.fHoraFin.value;
		var aF=HF.charAt(0);
		var bF=HF.charAt(1);
		var cF=HF.charAt(2);
		var dF=HF.charAt(3);
		var eF=HF.charAt(4);
		var horaF = aF + bF;
		var minF = dF + eF;
		
		var dFI = toJSDate(f.fFechaInicio.value,"dd/mm/yyyy", horaI, minI);
		var dFF = toJSDate(f.fFechaFin.value,"dd/mm/yyyy", horaF, minF);
		var fDesde   = dFI;
		var fHasta   = dFF;
		var fSistema = toJSDate("<?php echo $sFecha?>","dd/mm/yyyy", <?php echo $sHoraJS?>, <?php echo $sMin?>);

		
			if (f.MODO.value == "<?php echo constant("MNT_ALTA")?>"){
				if(!bCheckI){
					if (fDesde < fSistema){
						msg +="No puede ser menor que hoy.\n";
					}
				}
			}else{
				var fAnt = f.ANT_FECHAINICIO.value
				var hAnt = f.ANT_HORA.value;
				var aAnt=hAnt.charAt(0);
				var bAnt=hAnt.charAt(1);
				var cAnt=hAnt.charAt(2);
				var dAnt=hAnt.charAt(3);
				var eAnt=hAnt.charAt(4);
				var horaAnt = aAnt + bAnt;
				var minAnt = dAnt + eAnt;
		
				var fAnterior = toJSDate(fAnt,"dd/mm/yyyy", horaAnt, minAnt);	
				if ((fDesde.getTime() - fAnterior.getTime()) != 0){
					if (fDesde < fSistema){
						msg +="No puede ser menor que hoy.\n";
					}
				}
			}
		if (fDesde > fHasta)
			msg +="No puede ser mayor que la fecha Fin\n";
		if (msg != "")
			msg ="Fec. Inicio:\n" + msg;
	}
	if (msg == ""){
		if (dFI > dFF){
			msg ="La fecha hora Inicio no puede ser mayor que la de Fin\n";
		}
	}
if (msg != "") {
	alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
	return false;
}else return true;
}
function cambiaModoEnvio(){
	var f=document.forms[0];
	
	if (f.fIdModoRealizacion.value == "2"){
		//Administrado
		document.getElementById('EnvContrasenia').style.display = 'block';
<?php 
	if ($cEntidad->getEnvioContrasenas() == "1"){
?>		
		document.getElementById('fEnvioContrasenas1').checked = true;
		document.getElementById('fEnvioContrasenas2').checked = false;
<?php
	}
?>
<?php 
	if ($cEntidad->getEnvioContrasenas() == "2"){
?>		
		document.getElementById('fEnvioContrasenas1').checked = false;
		document.getElementById('fEnvioContrasenas2').checked = true;
<?php
	}
?>		
	}else{
		//Continuo
		document.getElementById('EnvContrasenia').style.display = 'none';
		document.getElementById('fEnvioContrasenas1').checked = true;
		document.getElementById('fEnvioContrasenas2').checked = false;
	}	
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
<body onload="_body_onload();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');cambiaModoEnvio();"  onunload="_body_onunload();">
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
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" class="negro" valign="top"><?php echo constant("STR_LOS_CAMPOS_MARCADOS_CON");?> <input type="text" name="--" value="" class="obliga" style="width:25px;height:10px;" onfocus="blur();" /> <?php echo constant("STR_OBLIGATORIOS");?></td>
				</tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td width="100%" style="border-bottom: 1px solid #000000;">
						<ul class="listaProceso">
							<li class="mActivo"><span><img src="./graf/iconsMenu/Tools.png" title="Datos proceso" alt="Datos proceso" /></span><b style="color:#00adef"><?php echo constant("STR_DATOS_PROCESO");?></b></li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/Folder.png" title="Pruebas" alt="Pruebas" /></span><?php echo constant("STR_PRUEBAS");?></li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/User.png" title="Candidatos" alt="Candidatos" /></span><?php echo constant("STR_CANDIDATOS");?></li>
							<li class="mArrow">&nbsp;</li>
							<li><span><img src="./graf/iconsMenu/mail.png" title="Comunicación" alt="Comunicación" /></span><?php echo constant("STR_COMUNICACION");?></li>
						</ul>
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2" >
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr><td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))){echo(constant("STR_ANIADIR"));}else{echo(constant("STR_MODIFICAR"));}?></td></tr>
							<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_EMPRESA");?>&nbsp;</td>
								<?php 
									$sReadOnly="";
									if ($_POST["MODO"] != constant("MNT_ALTA")){
										$sReadOnly = " onchange='this.value=" . $cEntidad->getIdEmpresa() . "'";
									}
								?>
								<td><?php $comboEMPRESAS->setNombre("fIdEmpresa");?><?php echo $comboEMPRESAS->getHTMLComboMenu("1","obliga",$cEntidad->getIdEmpresa(), $sReadOnly,"");?></td>
							</tr>

							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_NOMBRE");?>&nbsp;</td>
								<td><input type="text" name="fNombre" value="<?php echo $cEntidad->getNombre();?>" class="obliga"  onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_DESCRIPCION");?>&nbsp;</td>
								<td><textarea cols="1" name="fDescripcion" rows="2" class="cajatexto"  onchange="javascript:trim(this);"><?php echo $cEntidad->getDescripcion();?></textarea></td>
							</tr>
<!-- 
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_OBSERVACIONES");?>&nbsp;</td>
								<td><textarea class="tinymce"><?php echo  $cEntidad->getObservaciones();?></textarea>
										</td>
							</tr>
 -->
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_FECHA_DE_INICIO");?>&nbsp;</td>
								<?php 
								$sHoraInicio= "";
								if ($cEntidad->getFechaInicio() != "" && $cEntidad->getFechaInicio() != "0000-00-00" && $cEntidad->getFechaInicio() != "0000-00-00 00:00:00"){
									$aInicio=explode(" ", $cEntidad->getFechaInicio());
									$sHoraInicio= substr($aInicio[1], 0, 5);  // HH:MM									
									$cEntidad->setFechaInicio($conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false));
								}else{
									
									$date = date('Y-m-d H:i:s');
									$cEntidad->setFechaInicio($date);
									$aInicio=explode(" ", $cEntidad->getFechaInicio());
									$sHoraInicio= substr($aInicio[1], 0, 5);  // HH:MM									
									$cEntidad->setFechaInicio($conn->UserDate($cEntidad->getFechaInicio(),constant("USR_FECHA"),false));
								}
								?>
								<td>
									<?php 
									if($_POST['MODO'] == constant('MNT_MODIFICAR')){
										if($listaInformados->recordCount()>0){?>
											<img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" />&nbsp;<input type="text" readonly="readonly" name="fFechaInicio" value="<?php echo $cEntidad->getFechaInicio();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" readonly="readonly" name="fHoraInicio" value="<?php echo $sHoraInicio;?>" class="obliga" style="width:35px;" onchange="javascript:trim(this);" />&nbsp;&nbsp;<?php echo constant('STR_VERIFICADO')?>:&nbsp;<input type="checkbox" checked="checked" name="fCheckHi" disabled="disabled"/>
									<?php 
										}else{?>
											<a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaInicio','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" name="fFechaInicio" value="<?php echo $cEntidad->getFechaInicio();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" name="fHoraInicio" value="<?php echo $sHoraInicio;?>" class="obliga" style="width:35px;" onchange="javascript:trim(this);" />&nbsp;&nbsp;<?php echo constant('STR_VERIFICADO')?>:&nbsp;<input type="checkbox" name="fCheckHi" checked="checked" />
									<?php 
										}
									}else{?>
										<a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaInicio','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" name="fFechaInicio" value="<?php echo $cEntidad->getFechaInicio();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" name="fHoraInicio" value="<?php echo $sHoraInicio;?>" class="obliga" style="width:35px;" onchange="javascript:trim(this);" />&nbsp;&nbsp;<?php echo constant('STR_VERIFICADO')?>:&nbsp;<input type="checkbox" name="fCheckHi" />
										
									<?php 
									}?>
								</td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_FECHA_DE_FIN");?>&nbsp;</td>
								<?php 
								$sHoraFin="";
								if ($cEntidad->getFechaFin() != "" && $cEntidad->getFechaFin() != "0000-00-00" && $cEntidad->getFechaFin() != "0000-00-00 00:00:00"){
									$aFin=explode(" ", $cEntidad->getFechaFin());
									$sHoraFin= substr($aFin[1], 0, 5);  // HH:MM
									$cEntidad->setFechaFin($conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false));
								}else{
									//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
									$date = date('Y-m-d 23:59:59', strtotime('+1 week'));
									$cEntidad->setFechaFin($date);
									$aFin=explode(" ", $cEntidad->getFechaFin());
									$sHoraFin= substr($aFin[1], 0, 5);  // HH:MM
									$cEntidad->setFechaFin($conn->UserDate($cEntidad->getFechaFin(),constant("USR_FECHA"),false));
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFechaFin','<?php echo constant("STR_CALENDARIO");?>');"><img src="<?php echo constant('DIR_WS_GRAF');?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO');?>" align="bottom" /></a>&nbsp;<input type="text" name="fFechaFin" value="<?php echo $cEntidad->getFechaFin();?>" class="obliga" style="width:75px;" onchange="javascript:trim(this);" />&nbsp;<input type="text" name="fHoraFin" value="<?php echo $sHoraFin;?>" class="obliga" style="width:35px;" onchange="javascript:trim(this);" /></td>
							</tr>
 							
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_MODO_REALIZACION_PRUEBAS");?>&nbsp;</td>
								<?php
								// modificado para que ponga por defecto el valor Continuo en el combo
								if ($cEntidad->getIdModoRealizacion() == ""){
									$cEntidad->setIdModoRealizacion("1");	
								}
								?>
								<td><?php $comboMODO_REALIZACION->setNombre("fIdModoRealizacion");?><?php echo $comboMODO_REALIZACION->getHTMLCombo("1","obliga",$cEntidad->getIdModoRealizacion()," onchange=\"cambiaModoEnvio();\"","");?></td>
							</tr>
							<tr>
								<td colspan="3">
									<div id="EnvContrasenia" style="display: none;" >
										<table cellspacing="0" cellpadding="0" width="100%" border="0">
											<tr>
												<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
												<td nowrap="nowrap" width="170" class="negrob" valign="top"><?php echo constant("STR_ENVIO_DE_CONTRASENAS");?>&nbsp;</td>
												<td><input type="radio" name="fEnvioContrasenas" id="fEnvioContrasenas1" value="1" <?php if ($cEntidad->getEnvioContrasenas() == "1"){echo "checked";}?>><label for="fEnvioContrasenas1"><?php echo constant("STR_INDIVIDUALES");?></label></input>&nbsp;<input type="radio" name="fEnvioContrasenas" id="fEnvioContrasenas2" value="2" <?php if ($cEntidad->getEnvioContrasenas() == "2"){echo "checked";}?>><label for="fEnvioContrasenas2"><?php echo constant("STR_JUNTAS_EN_UN_SOLO_CORREO");?></label></input></td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
							<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
							<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
							<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
						
						</table>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER");?>" onclick="document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
								<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"';?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_SEGUIR");?>" /></td>
							</tr>
						</table>
					</td>
				</tr>
				
	</table>
	
	</div>
</div>
	<input type="hidden" name="ANT_FECHAINICIO" value="<?php echo $cEntidad->getFechaInicio()?>" />
	<input type="hidden" name="ANT_HORA" value="<?php echo $sHoraInicio?>" />

	<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : '';?>" />
	<input type="hidden" name="fIdProceso" value="<?php echo (empty($_POST['AddIdioma'])) ? $cEntidad->getIdProceso() : $_POST['fIdProceso'];?>" />
	<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : "";?>" />
	<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : "";?>" />
	<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : "";?>" />
	<input type="hidden" name="LSTDescripcion" value="<?php echo (isset($_POST['LSTDescripcion'])) ? $cUtilidades->validaXSS($_POST['LSTDescripcion']) : "";?>" />
	<input type="hidden" name="LSTObservaciones" value="<?php echo (isset($_POST['LSTObservaciones'])) ? $cUtilidades->validaXSS($_POST['LSTObservaciones']) : "";?>" />
	<input type="hidden" name="LSTFechaInicioHast" value="<?php echo (isset($_POST['LSTFechaInicioHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaInicioHast']) : "";?>" />
	<input type="hidden" name="LSTFechaInicio" value="<?php echo (isset($_POST['LSTFechaInicio'])) ? $cUtilidades->validaXSS($_POST['LSTFechaInicio']) : "";?>" />
	<input type="hidden" name="LSTFechaFinHast" value="<?php echo (isset($_POST['LSTFechaFinHast'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFinHast']) : "";?>" />
	<input type="hidden" name="LSTFechaFin" value="<?php echo (isset($_POST['LSTFechaFin'])) ? $cUtilidades->validaXSS($_POST['LSTFechaFin']) : "";?>" />
	<input type="hidden" name="LSTIdTipoProcesoHast" value="<?php echo (isset($_POST['LSTIdTipoProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoProcesoHast']) : "";?>" />
	<input type="hidden" name="LSTIdTipoProceso" value="<?php echo (isset($_POST['LSTIdTipoProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdTipoProceso']) : "";?>" />
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
	<input type="hidden" name="procesos_next_page" value="<?php echo (isset($_POST['procesos_next_page'])) ? $cUtilidades->validaXSS($_POST['procesos_next_page']) : "1";?>" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>