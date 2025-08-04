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
<script language="javascript" type="text/javascript">
//<![CDATA[
<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php");?>
function enviar1(Modo)
{
	var f=document.forms[0];
	f.MODO.value = Modo;
	f.submit();
}
function enviar()
{
	var f=document.forms[0];
	if (validaForm()){
		lon();
		f.LSTFecAlta.value=cFechaFormat(f.LSTFecAlta.value);
		f.LSTFecAltaHast.value=cFechaFormat(f.LSTFecAltaHast.value);
		f.LSTFecMod.value=cFechaFormat(f.LSTFecMod.value);
		f.LSTFecModHast.value=cFechaFormat(f.LSTFecModHast.value);
		return true;
	}else	return false;
}
function validaForm()
{
	var f=document.forms[0];
	var msg="";
	
	msg +=vNumber("<?php echo constant("STR_ID_PRUEBA");?>:", f.LSTIdPrueba.value, 11, false);
	msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.LSTNombre.value,255, false);
	msg +=vString("<?php echo constant("STR_DESCRIPCION");?>:",f.LSTDescripcion.value,255, false);
	msg +=vString("<?php echo constant("STR_OBSERVACIONES");?>:",f.LSTObservaciones.value,4000, false);
	msg +=vDate("Fecha de Alta:",f.LSTFecAlta.value,10,false);
	msg +=vDate("Fecha de Alta <?php echo constant("STR_HASTA");?>:",f.LSTFecAltaHast.value,10,false);
	msg +=vDate("Fecha de Modificación:",f.LSTFecMod.value,10,false);
	msg +=vDate("Fecha de Modificación <?php echo constant("STR_HASTA");?>:",f.LSTFecModHast.value,10,false);
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
		return false;
	}else return true;
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
function setPK(idBaremo,idPrueba,idBloque, idEscala)
{
	var f=document.forms[0];
	f.fIdBaremo.value=idBaremo;
	f.fIdPrueba.value=idPrueba;
	if(f.fIdBloque!=null){
		f.fIdBloque.value=idBloque;
	}
	if(f.fIdEscala!=null){
		f.fIdEscala.value=idEscala;
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

function aniadebaremo(){

	var f = document.forms[0];
	var paginacargada = "Baremos.php";
	if(f.fIdBloque == null && f.fIdEscala == null){
		if(f.fIdPrueba.value==""){
			alert("Debe seleccionar una prueba.");
		}else{
			$("div#aniadebaremo").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,MODO:"<?php echo constant('MNT_ANIADEBAREMO')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
		}
	}else{
		if(f.fIdPrueba.value!="" && f.fIdBloque.value!="" && f.fIdEscala.value!=""){
			$("div#aniadebaremo").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,MODO:"<?php echo constant('MNT_ANIADEBAREMO')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
		}else{
			alert("Debe seleccionar una prueba, un bloque y una escala.");
		}
	}
}
function comprueba(){
	var f = document.forms[0];
	var paginacargada = "Baremos.php";
	
	if(f.fIdPrueba.value!=""){
		var idBloque="-1";
		var idEscala="-1";
		if(f.fIdBloqueOrigen.value !=""){
			idBloque = f.fIdBloqueOrigen.value;
		}
		if(f.fIdBloqueOrigen.value !=""){
			idEscala = f.fIdEscalaOrigen.value;
		}
		$("div#escalas").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value, fIdBloque:idBloque, fIdEscala:idEscala, MODO:"<?php echo constant('MNT_COMPRUEBAESCALAS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	}
}
function listabaremos(escala, compNo){
	var f = document.forms[0];
	var paginacargada = "Baremos.php";
	if(escala!=null && escala!=""){
		f.fIdEscala.value = escala;
	}
	if(f.fIdBloque ==null && f.fIdEscala == null){
		if(f.fIdPrueba.value!=""){
			var idBloque="-1";
			var idEscala="-1";
			if(f.fIdBloqueOrigen.value !=""){
				idBloque = f.fIdBloqueOrigen.value;
			}
			if(f.fIdBloqueOrigen.value !=""){
				idEscala = f.fIdEscalaOrigen.value;
			}
			if(compNo=="0"){
				$("div#listabaremos").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,fIdBloque: "0",fIdEscala: "0",MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
			}else{
				$("div#listabaremos").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value, fIdBloque: idBloque, fIdEscala: idEscala,MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
			}
			
		}else{
			$("div#aniadebaremo").empty();
			$("div#listabaremos").empty();
		}
	}else{
		if(f.fIdPrueba.value!=""){
			if(compNo=="0"){
				$("div#listabaremos").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,fIdBloque: "0",fIdEscala: "0",MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
			}else{
				if(f.fIdBloque.value !="" && f.fIdEscala.value !=""){
					$("div#listabaremos").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,fIdBloque: f.fIdBloque.value,fIdEscala: f.fIdEscala.value,MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
				}else{
					if(f.fIdBloqueOrigen.value !="" && f.fIdEscalaOrigen.value !=""){
						$("div#listabaremos").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,fIdBloque: f.fIdBloqueOrigen.value,fIdEscala: f.fIdEscalaOrigen.value,MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
					}else{
						$("div#listabaremos").hide().load(paginacargada,{fIdPrueba: f.fIdPrueba.value,fIdBloque: "-1",fIdEscala: "-1",MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
					}
				}
			}
		}else{
			$("div#aniadebaremo").empty();
			$("div#listabaremos").empty();
		}
	}
}

function guardabaremos(){
	var f = document.forms[0];
	var paginacargada = "Baremos.php";

	if(f.fIdBloque ==null && f.fIdEscala == null){
		if(f.fIdPrueba.value!=""){
			$("div#listabaremos").hide().load(paginacargada,{fAniade:"1",fIdPrueba: f.fIdPrueba.value,fNombre: f.fNombre.value,fDescripcion: f.fDescripcion.value,MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
		}else{
			$("div#listabaremos").empty();
		}
	}else{
		if(f.fIdPrueba.value!="" && f.fIdBloque.value!="" && f.fIdEscala.value!=""){
			$("div#listabaremos").hide().load(paginacargada,{fAniade:"1",fIdPrueba: f.fIdPrueba.value,fIdBloque: f.fIdBloque.value,fIdEscala: f.fIdEscala.value,fNombre: f.fNombre.value,fDescripcion: f.fDescripcion.value,MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
		}else{
			$("div#listabaremos").empty();
		}
	}
}
function borrabaremo(idBaremo, idPrueba, idBloque, idEscala){
	var f = document.forms[0];
	if(confirm('<?php echo constant("DEL_GENERICO");?>')){
		var paginacargada = "Baremos.php";
		$("div#listabaremos").hide().load(paginacargada,{fBorra:"1",fIdPrueba: idPrueba,fIdBloque: idBloque,fIdEscala: idEscala,fIdBaremo: idBaremo,MODO:"<?php echo constant('MNT_LISTABAREMOS')?>", sTK:"<?php echo $_cEntidadUsuarioTK->getToken()?>" }).fadeIn("slow");
	}
}
function cierraaniade(){
	$("div#aniadebaremo").empty();	
}
function cambiaIdBloque(escala){
	var f= document.forms[0];
	$("#comboIdEscala").show().load("jQuery.php",{sPG:"comboescalas2",vSelected:escala,bBus:"0",multiple:"0",nLineas:"1",bObliga:"1",bgColor:"<?php echo constant("BG_COLOR")?>",fNombreCampo:"fIdEscala",fJSProp:"",fCodIdiomaIso2:f.fCodIdiomaIso2.value,fIdBloque:f.fIdBloque.value,sTK:"<?php echo $_cEntidadUsuarioTK->getToken();?>"}).fadeIn("slow");
}
function asignaBloque(idBloque,idEscala){
	var f= document.forms[0];
	f.fIdEscala.value = idEscala;
	f.fIdBloque.value = idBloque;
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
<body onload="_body_onload();comprueba();listabaremos();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1";?>');setClicado('<?php echo $_POST["_clicado"];?>');"  onunload="_body_onunload();">
<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" alt="" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO");?></strong></p></td></tr></table></div></td></tr></table>
	
		<form name="form" id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?
$HELP="xx";
?>
<div id="contenedor">
<?php include (constant("DIR_WS_INCLUDE") . "cabecera.php");?>
	<div id="envoltura">
		<div id="contenido">
		<div style="width: 100%">
			<table cellspacing="0" cellpadding="0" width="600" border="0">
				<tr><td colspan="3" align="center" class="naranjab"><?php echo constant("STR_BUSCADOR");?></td></tr>
				<tr><td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="5" height="20" border="0" alt="" /></td>
					<td nowrap="nowrap" width="140" class="negrob" valign="top"><?php echo constant("STR_ID_PRUEBA");?>&nbsp;</td>
					<td><?php $comboPRUEBASGROUP->setNombre("fIdPrueba");?><?php echo $comboPRUEBASGROUP->getHTMLCombo("1","obliga",$cEntidad->getIdPrueba()," onchange=\"javascript:comprueba()\" style=\"width:400px;\"","");?></td>
				</tr>
				<tr>
					<td colspan="3" width="550">
					<div id="escalas">
					</div>
					
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td colspan="3" width="500" align="center"><input type="button" class="botones" id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ANIADIR");?>" onclick="javascript:aniadebaremo();"/></td>
				</tr>
				<tr>
					<td colspan="3" style="height:10px;" >
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="3" >
						<div id="aniadebaremo"></div>	
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr>
					<td colspan="3">
						<div id="listabaremos"></div>	
					</td>
				</tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
				<tr><td colspan="3" ><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="1" border="0" alt="" /></td></tr>
				<tr><td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF');?>sp.gif" width="1" height="10" border="0" alt="" /></td></tr>
			</table>
	</div>
</div>						
	<input type="hidden" name="ORIGEN" value="<?php echo constant("MNT_BUSCAR");?>" />
	<input type="hidden" name="fCodIdiomaIso2" value="<?php echo $sLang?>" />
	<input type="hidden" name="fIdBaremo" value="" />
	<input type="hidden" name="fCierra" value="" />
	<input type="hidden" name="fIdBloqueOrigen" value="<?php echo isset($_POST['fIdBloque'])? $_POST['fIdBloque']: ""?>" />
	<input type="hidden" name="fIdEscalaOrigen" value="<?php echo isset($_POST['fIdEscala'])? $_POST['fIdEscala']: ""?>" />
	<input type="hidden" name="fBorra" value="" />
	<input type="hidden" name="fAniade" value="" />
	<input type="hidden" name="baremos_next_page" value="1" /></div>
<?php include (constant("DIR_WS_INCLUDE") . "menus.php");?>
<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");?>
<?php include (constant("DIR_WS_INCLUDE") . "pie.php");?></div>
</form>

</body></html>