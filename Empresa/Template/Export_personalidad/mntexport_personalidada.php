<?php
if (!defined("DIR_FS_DOCUMENT_ROOT")) {
	require_once("../../include/SeguridadTemplate.php");
} else {
	require_once("include/SeguridadTemplate.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang; ?>" xml:lang="<?php echo $sLang; ?>">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="generator" content="WIZARD, Wi2.22 www.negociainternet.com" />

	<title><?php echo constant("NOMBRE_SITE"); ?></title>
	<link rel="stylesheet" href="estilos/estilos.css" type="text/css" />
	<link rel="stylesheet" href="estilos/jquery.alerts.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/common.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/codigo.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/comun.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
	<script language="javascript" type="text/javascript" src="codigo/jquery.alert.js"></script>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		<?php include_once(constant("DIR_WS_INCLUDE") . "msg_error_JS.php"); ?>

		function enviar() {
			var f = document.forms[0];
			if (validaForm()) {
				lon();
				f.fFecPrueba.value = cFechaFormat(f.fFecPrueba.value);
				f.fIdBaremo.value = f.elements['fIdBaremo'].value;
				f.fFecAltaProceso.value = cFechaFormat(f.fFecAltaProceso.value);
				comaAPunto(f.fIr, f.fIr);
				comaAPunto(f.fIp, f.fIp);
				comaAPunto(f.fPor, f.fPor);
				return true;
			} else return false;
		}

		function validaForm() {
			var f = document.forms[0];
			var msg = "";
			msg += vNumber("<?php echo constant("STR_EMPRESA"); ?>:", f.fIdEmpresa.value, 11, true);
			msg += vString("<?php echo constant("STR_EMPRESA"); ?>:", f.fDescEmpresa.value, 500, true);
			msg += vNumber("<?php echo constant("STR_PROCESO"); ?>:", f.fIdProceso.value, 11, true);
			msg += vString("<?php echo constant("STR_PROCESO"); ?>:", f.fDescProceso.value, 500, true);
			msg += vNumber("<?php echo constant("STR_CANDIDATO"); ?>:", f.fIdCandidato.value, 11, true);
			msg += vString("<?php echo constant("STR_NOMBRE"); ?>:", f.fNombre.value, 255, true);
			msg += vString("<?php echo constant("STR_APELLIDO1"); ?>:", f.fApellido1.value, 255, true);
			msg += vString("<?php echo constant("STR_APELLIDO2"); ?>:", f.fApellido2.value, 255, false);
			msg += vString("<?php echo constant("STR_EMAIL"); ?>:", f.fEmail.value, 255, true);
			msg += vNumber("<?php echo constant("STR_PRUEBA"); ?>:", f.fIdPrueba.value, 11, true);
			msg += vNumber("<?php echo constant("STR_TIPO_INFORME"); ?>:", f.fIdTipoInforme.value, 11, true);
			msg += vString("<?php echo constant("STR_PRUEBA"); ?>:", f.fDescPrueba.value, 255, true);
			msg += vDate("<?php echo constant("STR_FECHA_DE_PRUEBA"); ?>:", f.fFecPrueba.value, 10, true);
			msg += vNumber("<?php echo constant("STR_BAREMO"); ?>:", f.elements['fIdBaremo'].value, 11, false);
			msg += vString("<?php echo constant("STR_BAREMO"); ?>:", f.fDescBaremo.value, 255, false);
			msg += vDate("<?php echo constant("STR_FECHA_DE_ALTA_PROCESO"); ?>:", f.fFecAltaProceso.value, 10, true);
			msg += vString("<?php echo constant("STR_CORRECTAS"); ?>:", f.fCorrectas.value, 11, true);
			msg += vString("<?php echo constant("STR_CONTESTADAS"); ?>:", f.fContestadas.value, 11, true);
			msg += vNumber("<?php echo constant("STR_PERCENTIL"); ?>:", f.fPercentil.value, 11, true);
			msg += vNumber("<?php echo constant("STR_IR"); ?>:", f.fIr.value, 13, false);
			msg += vNumber("<?php echo constant("STR_IP"); ?>:", f.fIp.value, 13, false);
			msg += vNumber("<?php echo constant("STR_POR"); ?>:", f.fPor.value, 13, false);
			msg += vString("<?php echo constant("STR_ESTILO"); ?>:", f.fEstilo.value, 65535, false);
			msg += vNumber("<?php echo constant("STR_SEXO"); ?>:", f.fIdSexo.value, 11, false);
			msg += vString("<?php echo constant("STR_SEXO"); ?>:", f.fDescSexo.value, 255, false);
			msg += vNumber("<?php echo constant("STR_EDAD"); ?>:", f.fIdEdad.value, 11, false);
			msg += vString("<?php echo constant("STR_EDAD"); ?>:", f.fDescEdad.value, 255, false);
			msg += vNumber("<?php echo constant("STR_FORMACION"); ?>:", f.fIdFormacion.value, 11, false);
			msg += vString("<?php echo constant("STR_FORMACION"); ?>:", f.fDescFormacion.value, 255, false);
			msg += vNumber("<?php echo constant("STR_NIVEL"); ?>:", f.fIdNivel.value, 11, false);
			msg += vString("<?php echo constant("STR_NIVEL"); ?>:", f.fDescNivel.value, 255, false);
			msg += vNumber("<?php echo constant("STR_AREA"); ?>:", f.fIdArea.value, 11, false);
			msg += vString("<?php echo constant("STR_AREA"); ?>:", f.fDescArea.value, 255, false);
			if (msg != "") {
				jAlert("<?php echo constant("ERR_FORM"); ?>:\n\n" + msg + "\n\n<?php echo constant("ERR_FORM_CORRIJA"); ?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS"); ?>.", "<?php echo constant("STR_NOTIFICACION"); ?>");
				return false;
			} else return true;
		}

		function abrirVentana(bImg, file) {
			preurl = "view.php?bImg=" + bImg + "&File=" + file;
			prename = "File";
			var miv = window.open(preurl, prename, "height=150,width=150,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
			miv.focus();
		}

		function abrirCalendario(page, titulo) {
			var miC = window.open(page, titulo, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=149,Height=148');
			miC.focus();
		}

		function enviarMenu(pagina, modo) {
			var f = document.forms[0];
			if (pagina != 'null') {
				lon();
				f.MODO.value = modo;
				f.action = pagina;
				f.submit();
			}
		}

		function setTitulo(titulo) {
			if (eval("document.getElementById('cabecera-menu-seleccionado').innerHTML") != null) {
				document.getElementById('cabecera-menu-seleccionado').innerHTML = titulo;
				document.forms[0]._TituloOpcion.value = titulo;
			}
		}

		function block(idBlock) {
			for (i = 0; i < 200; i++) {
				if (eval("document.getElementById('block" + i + "')") != null) {
					eval("document.getElementById('block" + i + "').style.display = 'none'");
				}
			}
			if (eval("document.getElementById('block" + idBlock + "')") != null) {
				eval("document.getElementById('block" + idBlock + "').style.display = 'block'");
			}
			document.forms[0]._block.value = idBlock;
		}
		onclick = "javascript:if (document.getElementById('Mas').style.display == 'none'){document.getElementById('Mas').style.display='block';}else{document.getElementById('Mas').style.display='none';}"
		//]]>
	</script>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
		function _body_onload() {
			loff();
		}

		function _body_onunload() {
			lon();
		}
		//]]>
	</script>
</head>

<body onload="_body_onload();cambiaIdPrueba();block('<?php echo ($_POST["_block"] != "") ? $_POST["_block"] : "-1"; ?>');setClicado('<?php echo $_POST["_clicado"]; ?>');" onunload="_body_onunload();">
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onclick="javascript:return false;">
		<tr>
			<td id="loaderContainerWH">
				<div id="loader">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
								<p><img src="estilos/icons/loading.gif" height="32" width="32" border="0" title="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO"); ?>" alt="<?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO"); ?>" /><strong><?php echo constant("MSG_POR_FAVOR_ESPERE_CARGANDO"); ?></strong></p>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>

	<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return enviar('<?php echo $_POST["MODO"]; ?>');">
		<?php
		if ($_POST['MODO'] == constant("MNT_ALTA"))	$HELP = "xx";
		else	$HELP = "xx";
		?>
		<div id="contenedor">
			<?php include(constant("DIR_WS_INCLUDE") . "cabecera.php"); ?>
			<div id="envoltura">
				<div id="contenido">
					<div style="width: 100%">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr>
								<td colspan="3" align="center" class="naranjab"><?php if (isset($_POST['MODO']) && ($_POST['MODO'] == constant("MNT_ALTA"))) {
																					echo (constant("STR_ANIADIR"));
																				} else {
																					echo (constant("STR_MODIFICAR"));
																				} ?></td>
							</tr>
							<tr>
								<td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="1" height="10" border="0" alt="" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEmpresa"><?php echo constant("STR_EMPRESA"); ?></label>&nbsp;</td>
								<td><?php $comboEMPRESAS->setNombre("fIdEmpresa"); ?><?php echo $comboEMPRESAS->getHTMLCombo("6", "obliga", $cEntidad->getIdEmpresa(), "  multiple=\"multiple\" ", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescEmpresa"><?php echo constant("STR_EMPRESA"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescEmpresa" name="fDescEmpresa" value="<?php echo $cEntidad->getDescEmpresa(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdProceso"><?php echo constant("STR_PROCESO"); ?></label>&nbsp;</td>
								<td><input type="text" id="fIdProceso" name="fIdProceso" value="<?php echo $cEntidad->getIdProceso(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescProceso"><?php echo constant("STR_PROCESO"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescProceso" name="fDescProceso" value="<?php echo $cEntidad->getDescProceso(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdCandidato"><?php echo constant("STR_CANDIDATO"); ?></label>&nbsp;</td>
								<td><input type="text" id="fIdCandidato" name="fIdCandidato" value="<?php echo $cEntidad->getIdCandidato(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fNombre"><?php echo constant("STR_NOMBRE"); ?></label>&nbsp;</td>
								<td><input type="text" id="fNombre" name="fNombre" value="<?php echo $cEntidad->getNombre(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido1"><?php echo constant("STR_APELLIDO1"); ?></label>&nbsp;</td>
								<td><input type="text" id="fApellido1" name="fApellido1" value="<?php echo $cEntidad->getApellido1(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fApellido2"><?php echo constant("STR_APELLIDO2"); ?></label>&nbsp;</td>
								<td><input type="text" id="fApellido2" name="fApellido2" value="<?php echo $cEntidad->getApellido2(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fEmail"><?php echo constant("STR_EMAIL"); ?></label>&nbsp;</td>
								<td><input type="text" id="fEmail" name="fEmail" value="<?php echo $cEntidad->getEmail(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdPrueba"><?php echo constant("STR_PRUEBA"); ?></label>&nbsp;</td>
								<td><?php $comboPRUEBAS->setNombre("fIdPrueba"); ?><?php echo $comboPRUEBAS->getHTMLCombo("1", "obliga", $cEntidad->getIdPrueba(), " onchange=\"javascript:cambiaIdPrueba()\"", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescPrueba"><?php echo constant("STR_PRUEBA"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescPrueba" name="fDescPrueba" value="<?php echo $cEntidad->getDescPrueba(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFecPrueba"><?php echo constant("STR_FECHA_DE_PRUEBA"); ?></label>&nbsp;</td>
								<?php if ($cEntidad->getFecPrueba() != "" && $cEntidad->getFecPrueba() != "0000-00-00" && $cEntidad->getFecPrueba() != "0000-00-00 00:00:00") {
									$cEntidad->setFecPrueba($conn->UserDate($cEntidad->getFecPrueba(), constant("USR_FECHA"), false));
								} else {
									//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
									$date = date('Y-m-d', strtotime('+10 year'));
									$cEntidad->setFecPrueba($date);
									$cEntidad->setFecPrueba($conn->UserDate($cEntidad->getFecPrueba(), constant("USR_FECHA"), false));
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFecPrueba','<?php echo constant("STR_CALENDARIO"); ?>');"><img src="<?php echo constant('DIR_WS_GRAF'); ?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO'); ?>" align="bottom" /></a>&nbsp;<input type="text" id="fFecPrueba" name="fFecPrueba" value="<?php echo $cEntidad->getFecPrueba(); ?>" class=cajatexto id="tid-obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdBaremo"><?php echo constant("STR_BAREMO"); ?></label>&nbsp;</td>
								<td>
									<div id="comboIdBaremo"><?php $comboBAREMOS->setNombre("fIdBaremo"); ?><?php echo $comboBAREMOS->getHTMLComboNull("1", "cajatexto", $cEntidad->getIdBaremo(), "onchange=\"javascript:cambiaIdBaremo()\" ", ""); ?></div>
								</td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescBaremo"><?php echo constant("STR_BAREMO"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescBaremo" name="fDescBaremo" value="<?php echo $cEntidad->getDescBaremo(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fFecAltaProceso"><?php echo constant("STR_FECHA_DE_ALTA_PROCESO"); ?></label>&nbsp;</td>
								<?php if ($cEntidad->getFecAltaProceso() != "" && $cEntidad->getFecAltaProceso() != "0000-00-00" && $cEntidad->getFecAltaProceso() != "0000-00-00 00:00:00") {
									$cEntidad->setFecAltaProceso($conn->UserDate($cEntidad->getFecAltaProceso(), constant("USR_FECHA"), false));
								} else {
									//Palabras especiales (tomorrow, yesterday, ago, fortnight, now, today, day, week, month, year, hour, minute, min, second, sec)
									$date = date('Y-m-d', strtotime('+10 year'));
									$cEntidad->setFecAltaProceso($date);
									$cEntidad->setFecAltaProceso($conn->UserDate($cEntidad->getFecAltaProceso(), constant("USR_FECHA"), false));
								}
								?>
								<td><a href="#" onclick="javascript:abrirCalendario('calendario.php?openerNombre=fFecAltaProceso','<?php echo constant("STR_CALENDARIO"); ?>');"><img src="<?php echo constant('DIR_WS_GRAF'); ?>icon_calendario.gif" width="22" height="18" border="0" alt="<?php echo constant('STR_CALENDARIO'); ?>" align="bottom" /></a>&nbsp;<input type="text" id="fFecAltaProceso" name="fFecAltaProceso" value="<?php echo $cEntidad->getFecAltaProceso(); ?>" class=cajatexto id="tid-obliga" style="width:75px;" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fCorrectas"><?php echo constant("STR_CORRECTAS"); ?></label>&nbsp;</td>
								<td><input type="text" id="fCorrectas" name="fCorrectas" value="<?php echo $cEntidad->getCorrectas(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fContestadas"><?php echo constant("STR_CONTESTADAS"); ?></label>&nbsp;</td>
								<td><input type="text" id="fContestadas" name="fContestadas" value="<?php echo $cEntidad->getContestadas(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fPercentil"><?php echo constant("STR_PERCENTIL"); ?></label>&nbsp;</td>
								<td><input type="text" id="fPercentil" name="fPercentil" value="<?php echo $cEntidad->getPercentil(); ?>" class="obliga" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIr"><?php echo constant("STR_IR"); ?></label>&nbsp;</td>
								<td><input type="text" id="fIr" name="fIr" value="<?php echo $cEntidad->getIr(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIp"><?php echo constant("STR_IP"); ?></label>&nbsp;</td>
								<td><input type="text" id="fIp" name="fIp" value="<?php echo $cEntidad->getIp(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fPor"><?php echo constant("STR_POR"); ?></label>&nbsp;</td>
								<td><input type="text" id="fPor" name="fPor" value="<?php echo $cEntidad->getPor(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fEstilo"><?php echo constant("STR_ESTILO"); ?></label>&nbsp;</td>
								<td><textarea id="fEstilo" name="fEstilo" rows="6" cols="1" class="cajatexto" onchange="javascript:trim(this);"><?php echo $cEntidad->getEstilo(); ?></textarea></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdSexo"><?php echo constant("STR_SEXO"); ?></label>&nbsp;</td>
								<td><?php $comboSEXOS->setNombre("fIdSexo"); ?><?php echo $comboSEXOS->getHTMLCombo("1", "cajatexto", $cEntidad->getIdSexo(), " ", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescSexo"><?php echo constant("STR_SEXO"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescSexo" name="fDescSexo" value="<?php echo $cEntidad->getDescSexo(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdEdad"><?php echo constant("STR_EDAD"); ?></label>&nbsp;</td>
								<td><?php $comboEDADES->setNombre("fIdEdad"); ?><?php echo $comboEDADES->getHTMLCombo("1", "cajatexto", $cEntidad->getIdEdad(), " ", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescEdad"><?php echo constant("STR_EDAD"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescEdad" name="fDescEdad" value="<?php echo $cEntidad->getDescEdad(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdFormacion"><?php echo constant("STR_FORMACION"); ?></label>&nbsp;</td>
								<td><?php $comboFORMACIONES->setNombre("fIdFormacion"); ?><?php echo $comboFORMACIONES->getHTMLCombo("1", "cajatexto", $cEntidad->getIdFormacion(), " ", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescFormacion"><?php echo constant("STR_FORMACION"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescFormacion" name="fDescFormacion" value="<?php echo $cEntidad->getDescFormacion(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdNivel"><?php echo constant("STR_NIVEL"); ?></label>&nbsp;</td>
								<td><?php $comboNIVELESJERARQUICOS->setNombre("fIdNivel"); ?><?php echo $comboNIVELESJERARQUICOS->getHTMLCombo("1", "cajatexto", $cEntidad->getIdNivel(), " ", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescNivel"><?php echo constant("STR_NIVEL"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescNivel" name="fDescNivel" value="<?php echo $cEntidad->getDescNivel(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fIdArea"><?php echo constant("STR_AREA"); ?></label>&nbsp;</td>
								<td><?php $comboAREAS->setNombre("fIdArea"); ?><?php echo $comboAREAS->getHTMLCombo("1", "cajatexto", $cEntidad->getIdArea(), " ", ""); ?></td>
							</tr>
							<tr>
								<td width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="5" height="20" border="0" alt="" /></td>
								<td nowrap="nowrap" width="140" class="negrob" valign="top"><label for="fDescArea"><?php echo constant("STR_AREA"); ?></label>&nbsp;</td>
								<td><input type="text" id="fDescArea" name="fDescArea" value="<?php echo $cEntidad->getDescArea(); ?>" class="cajatexto" onchange="javascript:trim(this);" /></td>
							</tr>
							<tr>
								<td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="1" height="10" border="0" alt="" /></td>
							</tr>
							<tr>
								<td colspan="3"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="1" height="1" border="0" alt="" /></td>
							</tr>
							<tr>
								<td colspan="3" width="5"><img src="<?php echo constant('DIR_WS_GRAF'); ?>sp.gif" width="1" height="10" border="0" alt="" /></td>
							</tr>
						</table>
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td><input type="button" class="botones" id="bid-volver" name="btnAdd" value="<?php echo constant("STR_VOLVER"); ?>" onclick="javascript:document.forms[0].MODO.value=document.forms[0].ORIGEN.value;document.forms[0].submit();" /></td>
								<td><input type="submit" class="botones" <?php echo ($_bModificar) ? '' : 'disabled="disabled"'; ?> id="bid-ok" name="fBtnOk" value="<?php echo constant("STR_ACEPTAR"); ?>" /></td>
							</tr>
						</table>
					</div>
				</div>
				<input type="hidden" name="ORIGEN" value="<?php echo (!empty($_POST['ORIGEN'])) ? $_POST['ORIGEN'] : ''; ?>" />
				<input type="hidden" name="fCodIdiomaIso2" value="<?php echo constant("LENGUAJEDEFECTO"); ?>" />
				<input type="hidden" name="LSTIdEmpresaHast" value="<?php echo (isset($_POST['LSTIdEmpresaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresaHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdEmpresa" value="<?php echo (isset($_POST['LSTIdEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTIdEmpresa']) : ""; ?>" />
				<input type="hidden" name="LSTDescEmpresa" value="<?php echo (isset($_POST['LSTDescEmpresa'])) ? $cUtilidades->validaXSS($_POST['LSTDescEmpresa']) : ""; ?>" />
				<input type="hidden" name="LSTIdProcesoHast" value="<?php echo (isset($_POST['LSTIdProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdProcesoHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdProceso" value="<?php echo (isset($_POST['LSTIdProceso'])) ? $cUtilidades->validaXSS($_POST['LSTIdProceso']) : ""; ?>" />
				<input type="hidden" name="LSTDescProceso" value="<?php echo (isset($_POST['LSTDescProceso'])) ? $cUtilidades->validaXSS($_POST['LSTDescProceso']) : ""; ?>" />
				<input type="hidden" name="LSTIdCandidatoHast" value="<?php echo (isset($_POST['LSTIdCandidatoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidatoHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdCandidato" value="<?php echo (isset($_POST['LSTIdCandidato'])) ? $cUtilidades->validaXSS($_POST['LSTIdCandidato']) : ""; ?>" />
				<input type="hidden" name="LSTNombre" value="<?php echo (isset($_POST['LSTNombre'])) ? $cUtilidades->validaXSS($_POST['LSTNombre']) : ""; ?>" />
				<input type="hidden" name="LSTApellido1" value="<?php echo (isset($_POST['LSTApellido1'])) ? $cUtilidades->validaXSS($_POST['LSTApellido1']) : ""; ?>" />
				<input type="hidden" name="LSTApellido2" value="<?php echo (isset($_POST['LSTApellido2'])) ? $cUtilidades->validaXSS($_POST['LSTApellido2']) : ""; ?>" />
				<input type="hidden" name="LSTEmail" value="<?php echo (isset($_POST['LSTEmail'])) ? $cUtilidades->validaXSS($_POST['LSTEmail']) : ""; ?>" />
				<input type="hidden" name="LSTIdPruebaHast" value="<?php echo (isset($_POST['LSTIdPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdPruebaHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdPrueba" value="<?php echo (isset($_POST['LSTIdPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTIdPrueba']) : ""; ?>" />
				<input type="hidden" name="LSTDescPrueba" value="<?php echo (isset($_POST['LSTDescPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTDescPrueba']) : ""; ?>" />
				<input type="hidden" name="LSTFecPruebaHast" value="<?php echo (isset($_POST['LSTFecPruebaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecPruebaHast']) : ""; ?>" />
				<input type="hidden" name="LSTFecPrueba" value="<?php echo (isset($_POST['LSTFecPrueba'])) ? $cUtilidades->validaXSS($_POST['LSTFecPrueba']) : ""; ?>" />
				<input type="hidden" name="LSTIdBaremoHast" value="<?php echo (isset($_POST['LSTIdBaremoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremoHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdBaremo" value="<?php echo (isset($_POST['LSTIdBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTIdBaremo']) : ""; ?>" />
				<input type="hidden" name="LSTDescBaremo" value="<?php echo (isset($_POST['LSTDescBaremo'])) ? $cUtilidades->validaXSS($_POST['LSTDescBaremo']) : ""; ?>" />
				<input type="hidden" name="LSTFecAltaProcesoHast" value="<?php echo (isset($_POST['LSTFecAltaProcesoHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaProcesoHast']) : ""; ?>" />
				<input type="hidden" name="LSTFecAltaProceso" value="<?php echo (isset($_POST['LSTFecAltaProceso'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaProceso']) : ""; ?>" />
				<input type="hidden" name="LSTCorrectas" value="<?php echo (isset($_POST['LSTCorrectas'])) ? $cUtilidades->validaXSS($_POST['LSTCorrectas']) : ""; ?>" />
				<input type="hidden" name="LSTContestadas" value="<?php echo (isset($_POST['LSTContestadas'])) ? $cUtilidades->validaXSS($_POST['LSTContestadas']) : ""; ?>" />
				<input type="hidden" name="LSTPercentilHast" value="<?php echo (isset($_POST['LSTPercentilHast'])) ? $cUtilidades->validaXSS($_POST['LSTPercentilHast']) : ""; ?>" />
				<input type="hidden" name="LSTPercentil" value="<?php echo (isset($_POST['LSTPercentil'])) ? $cUtilidades->validaXSS($_POST['LSTPercentil']) : ""; ?>" />
				<input type="hidden" name="LSTIrHast" value="<?php echo (isset($_POST['LSTIrHast'])) ? $cUtilidades->validaXSS($_POST['LSTIrHast']) : ""; ?>" />
				<input type="hidden" name="LSTIr" value="<?php echo (isset($_POST['LSTIr'])) ? $cUtilidades->validaXSS($_POST['LSTIr']) : ""; ?>" />
				<input type="hidden" name="LSTIpHast" value="<?php echo (isset($_POST['LSTIpHast'])) ? $cUtilidades->validaXSS($_POST['LSTIpHast']) : ""; ?>" />
				<input type="hidden" name="LSTIp" value="<?php echo (isset($_POST['LSTIp'])) ? $cUtilidades->validaXSS($_POST['LSTIp']) : ""; ?>" />
				<input type="hidden" name="LSTPorHast" value="<?php echo (isset($_POST['LSTPorHast'])) ? $cUtilidades->validaXSS($_POST['LSTPorHast']) : ""; ?>" />
				<input type="hidden" name="LSTPor" value="<?php echo (isset($_POST['LSTPor'])) ? $cUtilidades->validaXSS($_POST['LSTPor']) : ""; ?>" />
				<input type="hidden" name="LSTEstilo" value="<?php echo (isset($_POST['LSTEstilo'])) ? $cUtilidades->validaXSS($_POST['LSTEstilo']) : ""; ?>" />
				<input type="hidden" name="LSTIdSexoHast" value="<?php echo (isset($_POST['LSTIdSexoHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexoHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdSexo" value="<?php echo (isset($_POST['LSTIdSexo'])) ? $cUtilidades->validaXSS($_POST['LSTIdSexo']) : ""; ?>" />
				<input type="hidden" name="LSTDescSexo" value="<?php echo (isset($_POST['LSTDescSexo'])) ? $cUtilidades->validaXSS($_POST['LSTDescSexo']) : ""; ?>" />
				<input type="hidden" name="LSTIdEdadHast" value="<?php echo (isset($_POST['LSTIdEdadHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdadHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdEdad" value="<?php echo (isset($_POST['LSTIdEdad'])) ? $cUtilidades->validaXSS($_POST['LSTIdEdad']) : ""; ?>" />
				<input type="hidden" name="LSTDescEdad" value="<?php echo (isset($_POST['LSTDescEdad'])) ? $cUtilidades->validaXSS($_POST['LSTDescEdad']) : ""; ?>" />
				<input type="hidden" name="LSTIdFormacionHast" value="<?php echo (isset($_POST['LSTIdFormacionHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacionHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdFormacion" value="<?php echo (isset($_POST['LSTIdFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTIdFormacion']) : ""; ?>" />
				<input type="hidden" name="LSTDescFormacion" value="<?php echo (isset($_POST['LSTDescFormacion'])) ? $cUtilidades->validaXSS($_POST['LSTDescFormacion']) : ""; ?>" />
				<input type="hidden" name="LSTIdNivelHast" value="<?php echo (isset($_POST['LSTIdNivelHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivelHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdNivel" value="<?php echo (isset($_POST['LSTIdNivel'])) ? $cUtilidades->validaXSS($_POST['LSTIdNivel']) : ""; ?>" />
				<input type="hidden" name="LSTDescNivel" value="<?php echo (isset($_POST['LSTDescNivel'])) ? $cUtilidades->validaXSS($_POST['LSTDescNivel']) : ""; ?>" />
				<input type="hidden" name="LSTIdAreaHast" value="<?php echo (isset($_POST['LSTIdAreaHast'])) ? $cUtilidades->validaXSS($_POST['LSTIdAreaHast']) : ""; ?>" />
				<input type="hidden" name="LSTIdArea" value="<?php echo (isset($_POST['LSTIdArea'])) ? $cUtilidades->validaXSS($_POST['LSTIdArea']) : ""; ?>" />
				<input type="hidden" name="LSTDescArea" value="<?php echo (isset($_POST['LSTDescArea'])) ? $cUtilidades->validaXSS($_POST['LSTDescArea']) : ""; ?>" />
				<input type="hidden" name="LSTFecAltaHast" value="<?php echo (isset($_POST['LSTFecAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecAltaHast']) : ""; ?>" />
				<input type="hidden" name="LSTFecAlta" value="<?php echo (isset($_POST['LSTFecAlta'])) ? $cUtilidades->validaXSS($_POST['LSTFecAlta']) : ""; ?>" />
				<input type="hidden" name="LSTFecModHast" value="<?php echo (isset($_POST['LSTFecModHast'])) ? $cUtilidades->validaXSS($_POST['LSTFecModHast']) : ""; ?>" />
				<input type="hidden" name="LSTFecMod" value="<?php echo (isset($_POST['LSTFecMod'])) ? $cUtilidades->validaXSS($_POST['LSTFecMod']) : ""; ?>" />
				<input type="hidden" name="LSTUsuAltaHast" value="<?php echo (isset($_POST['LSTUsuAltaHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAltaHast']) : ""; ?>" />
				<input type="hidden" name="LSTUsuAlta" value="<?php echo (isset($_POST['LSTUsuAlta'])) ? $cUtilidades->validaXSS($_POST['LSTUsuAlta']) : ""; ?>" />
				<input type="hidden" name="LSTUsuModHast" value="<?php echo (isset($_POST['LSTUsuModHast'])) ? $cUtilidades->validaXSS($_POST['LSTUsuModHast']) : ""; ?>" />
				<input type="hidden" name="LSTUsuMod" value="<?php echo (isset($_POST['LSTUsuMod'])) ? $cUtilidades->validaXSS($_POST['LSTUsuMod']) : ""; ?>" />
				<input type="hidden" name="LSTOrderBy" value="<?php echo (isset($_POST['LSTOrderBy'])) ? $cUtilidades->validaXSS($_POST['LSTOrderBy']) : ""; ?>" />
				<input type="hidden" name="LSTOrder" value="<?php echo (isset($_POST['LSTOrder'])) ? $cUtilidades->validaXSS($_POST['LSTOrder']) : ""; ?>" />
				<input type="hidden" name="LSTLineasPagina" value="<?php echo (isset($_POST['LSTLineasPagina'])) ? $cUtilidades->validaXSS($_POST['LSTLineasPagina']) : constant("CNF_LINEAS_PAGINA"); ?>" />
				<input type="hidden" name="export_personalidad_next_page" value="<?php echo (isset($_POST['export_personalidad_next_page'])) ? $cUtilidades->validaXSS($_POST['export_personalidad_next_page']) : "1"; ?>" />
				<script language="javascript" type="text/javascript">
					//<![CDATA[
					function cambiaIdPrueba() {
						var f = document.forms[0];
						$("#comboIdBaremo").show().load("jQuery.php", {
							sPG: "combobaremos",
							bBus: "0",
							multiple: "0",
							nLineas: "1",
							bObliga: "1",
							bgColor: "<?php echo constant("BG_COLOR") ?>",
							fNombreCampo: "fIdBaremo",
							fJSProp: "cambiaIdBaremo",
							fIdPrueba: f.fIdPrueba.value,
							fIdTipoInforme: f.fIdTipoInforme.value,
							vSelected: "<?php echo $cEntidad->getIdBaremo(); ?>",
							sTK: "<?php echo $_cEntidadUsuarioTK->getToken(); ?>"
						}).fadeIn("slow");
					}
					//]]>
				</script>
				<script language="javascript" type="text/javascript">
					//<![CDATA[
					function cambiaIdBaremo() {}
					//]]>
				</script>
			</div>
			<?php include(constant("DIR_WS_INCLUDE") . "menus.php"); ?>
			<?php //include (constant("DIR_WS_INCLUDE") . "derecha.php");
			?>
			<?php include(constant("DIR_WS_INCLUDE") . "pie.php"); ?>
		</div>
	</form>
</body>

</html>
<input type="hidden" name="export_personalidad_next_page" value="<?php echo (isset($_POST['export_personalidad_next_page'])) ? $cUtilidades->validaXSS($_POST['export_personalidad_next_page']) : "1"; ?>" />