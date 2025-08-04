<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');
require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
require_once(constant("DIR_WS_COM") . "Faqs/FaqsDB.php");
require_once(constant("DIR_WS_COM") . "Faqs/Faqs.php");
require_once(constant("DIR_WS_COM") . "Recomendaciones/RecomendacionesDB.php");
require_once(constant("DIR_WS_COM") . "Recomendaciones/Recomendaciones.php");
require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
include_once ('include/conexion.php');

$comboRECOMENDACIONES	= new Combo($conn,"fIdRecomendacion","idRecomendacion","titulo","Descripcion","recomendaciones","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false) . " AND zonaPublicacion=1 AND publicar=0","","orden");
$comboFAQS	= new Combo($conn,"fIdFaq","idFaq","pregunta","Descripcion","faqs","",constant("SLC_OPCION"),"codIdiomaIso2=" . $conn->qstr($sLang, false) . " AND zonaPublicacion=1 AND publicar=0","","orden");


$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack("1");
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
$cEntidad	= new Candidatos();  // Entidad
$cFaqsDB	= new FaqsDB($conn);  // Entidad DB
$cFaqs	= new Faqs();  // Entidad
$cFaqs->setCodIdiomaIso2($sLang);
$cFaqs->setZonaPublicacion("1");
$cFaqs->setZonaPublicacionHast("1");
$cFaqs->setPublicar("0");
$cFaqs->setPublicarHast("0");
$cFaqs->setOrderBy("orden");
$cFaqs->setOrder("ASC");
$sqlFaqs = $cFaqsDB->readLista($cFaqs);
$rsFaqs = $conn->Execute($sqlFaqs);
//print_r($rsFaqs);

$cRecomendacionesDB	= new RecomendacionesDB($conn);  // Entidad DB
$cRecomendaciones	= new Recomendaciones();  // Entidad
$cRecomendaciones->setCodIdiomaIso2($sLang);
$cRecomendaciones->setZonaPublicacion("1");
$cRecomendaciones->setZonaPublicacionHast("1");
$cRecomendaciones->setPublicar("0");
$cRecomendaciones->setPublicarHast("0");
$cRecomendaciones->setOrderBy("orden");
$cRecomendaciones->setOrder("ASC");
$sqlRecomendaciones = $cRecomendacionesDB->readLista($cRecomendaciones);
$rsRecomendaciones = $conn->Execute($sqlRecomendaciones);
//echo ($sqlRecomendaciones);

$strMensaje="";
if (!empty($_POST["fEnviar"])){
	if (!empty($_POST["fEmail"]) && !empty($_POST["fName"]) && !empty($_POST["fSubject"]) && !empty($_POST["fMsg"]))
	{
		$strMensaje= "No se ha podido enviar el Correo, intentelo más tarde.";
		if (!enviaEmail($_POST["fEmail"], $_POST["fName"], $_POST["fSubject"], $_POST["fMsg"])){
			$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar el correo a Soporte al Usuario.\n";
			error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		}else{
			$strMensaje= "Correo enviado correctamente.";
		}
	}else{
		$strMensaje= "Todos los datos del formulario son obligatorios.";
	}
}
?>
<!doctype html>
<html lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.negociainternet.com" />
<?php include('include/metatags.php');?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
<style>
.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

.active, .accordion:hover {
    background-color: #ffb200;
}

.accordion:before {
    content: '\002B';
    color: #FF7B0D;
    font-weight: bold;
    float: left;
    margin-right: 5px;


}

.active:before {
    content: "\2212";
}

.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}
</style>
	 <script src="codigo/common.js"></script>
	 <script src="codigo/codigo.js"></script>
	 <script src="codigo/comun.js"></script>
	 <script src="codigo/noback.js"></script>
	 <script src="codigo/jQuery1.4.2.js"></script>

	<script   >
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
		msg +=vString("<?php echo constant("STR_NOMBRE");?>:",f.fName.value,255,true);
		msg +=vEmail("<?php echo constant("STR_EMAIL");?>:",f.fEmail.value,255,true);
		msg +=vString("<?php echo constant("STR_ASUNTO");?>:",f.fSubject.value,255,true);
		msg +=vString("<?php echo constant("STR_MENSAJE");?>:",f.fMsg.value,2500,true);
	if (msg != "") {
		alert("<?php echo constant("ERR_FORM");?>:\n\n"+msg+"\n\n<?php echo constant("ERR_FORM_CORRIJA");?>.\n\n\t<?php echo constant("STR_MUCHAS_GRACIAS");?>.");
		return false;
	}else return true;
	}
	//]]>
	</script>
<script >
		function cerrar(){
		setTimeout("self.close();",11000);
		}
		resizeTo(opener.document.documentElement.clientWidth-50,opener.document.documentElement.clientHeight);
</script>
</head>
<body >

<div >
		<div id="head" class="candidato" style="background-image: none !important;">
				<div class="logo">
				<a href="index.php" title="Inicio"><img src="graf/logo.jpg" alt="#" title="#" /></a>
				</div><!-- Fin de logo -->
		</div>
		<br />
		<!-- Fin de la cabecera -->
		<div id="cuerpo">
				<div id="aviso" class="acc_cand" style="width: 100%;">
						<h2 style="padding-left: 3%;"><?php echo constant("STR_AYUDA_AL_CANDIDATO");?></h2>
						<table cellspacing="1" cellpadding="5" border="1" align="center" style="border-color: #000;border-style: inherit;display: none;" width="90%">
							<tr>
								<td>
						<?php
						if ($rsRecomendaciones->NumRows() > 0){
							$comboRECOMENDACIONES->setNombre("LSTIdRecomendacion");
							echo '
							<table width="100%" border="0">
								<tr>
									<td width="80" align="center" style="background-color:#ffb200;"><img src="graf/engranaje.png" alt="" title="" /></td>
									<td align="center" style="background-color:#ffb200;"><h2 style="font-size:20pt;letter-spacing:1em;color:#fff;">' . constant("STR_SOPORTE_AL_USUARIO") . '</h2></td>
								</tr>
								<tr>
									<td align="center" height="80">&nbsp;</td>
									<td align="center">' . $comboRECOMENDACIONES->getHTMLCombo("1","cajatexto"," "," style='width: 50%;' onchange=\"document.location.href='#Rt'+this.options[this.selectedIndex].value\" ","") . '</td>
								</tr>
							</table>
							';
						}
						if ($rsFaqs->NumRows() > 0){
							$comboFAQS->setNombre("LSTIdFaq");
							echo '
							<table width="100%" border="0">
								<tr>
									<td width="80" align="center" style="background-color:#FF7B0D;"><img src="graf/candidato.gif" alt="" title="" /></td>
									<td align="center" style="background-color:#FF7B0D;"><h2 style="font-size:20pt;letter-spacing:1em;color:#fff;">FAQ&acute;s</h2></td>
								</tr>
								<tr>
									<td align="center" height="80">&nbsp;</td>
									<td align="center">' . $comboFAQS->getHTMLCombo("1","cajatexto",""," style='width: 50%;' onchange=\"document.location.href='#Fp'+this.options[this.selectedIndex].value\" ","") . '</td>
								</tr>
							</table>
							';

						}
						?>
							</td>
						</tr>
						</table>
						<br /><br />

						<table cellspacing="1" cellpadding="5" border="0" align="center" width="90%">
							<tr>
								<td valign="top">
									<?php
									if ($rsRecomendaciones->NumRows() > 0){
										echo "<h1 style='width:40%;float:left;'>" . constant("STR_SOPORTE_AL_USUARIO") . "</h1>";
									}
									?>
									<h3 id="expandir" style="float:right;width:40%;margin-right: 10px;text-align: right;cursor: pointer;" onclick="todos();" style="cursor: pointer;">+ <?php echo constant("STR_EXPANDIR_TODO");?></h2>
								</td>
							</tr>
							<tr>
									<td width="100%" valign="top" >
									<?php
										while (!$rsRecomendaciones->EOF)
										{
											echo '<a name="Rt' . $rsRecomendaciones->fields['idRecomendacion'] . '"></a><button class="accordion" style="color:#000;font-size:1.5em;margin-top: 15px;" id="Rt' . $rsRecomendaciones->fields['idRecomendacion'] . '" >';
											echo str_replace("\n","<br />",strip_tags($rsRecomendaciones->fields['titulo'], "<b><i><u><strong><br><br />"));
											echo '</button>';
											echo '<div class="panel"><p id="Rd' . $rsRecomendaciones->fields['idRecomendacion'] . '" style="text-align: justify;">';
											echo str_replace("\n","<br />",strip_tags($rsRecomendaciones->fields['descripcion'], "<b><i><u><strong><br><br />"));
											echo '</p></div>';
											$rsRecomendaciones->MoveNext();
										}
									?>
								</td>
							</tr>
							<tr>
									<td valign="top">
										<?php
										if ($rsFaqs->NumRows() > 0){
											echo "<p><h1>FAQ's</h1></p>";
										}
										?>
									<?php

										while (!$rsFaqs->EOF)
										{
											echo '<a name="Fp' . $rsFaqs->fields['idFaq'] . '"></a><button class="accordion" style="color:#000;font-size:1.5em;margin-top: 15px;" id="Fp' . $rsFaqs->fields['idFaq'] . '" >';
											echo str_replace("\n","<br />",strip_tags($rsFaqs->fields['pregunta'], "<b><i><u><strong><br><br />"));
											echo '</button>';
											echo '<div class="panel"><p id="Fr' . $rsFaqs->fields['idFaq'] . '" style="text-align: justify;">';
											echo str_replace("\n","<br />",strip_tags($rsFaqs->fields['respuesta'], "<b><i><u><strong><br><br />"));
											echo '</p></div>';
											$rsFaqs->MoveNext();
										}
									?>
								</td>
							</tr>
							<tr>
								<td valign="top" class="negrob"><p><h1><a href="#_" style="text-decoration: none;color: #000;" title="<?php echo constant("STR_CONTACTA_CON_NOSOTROS");?>" onclick="document.getElementById('contacto').style.display='block';"><?php echo constant("STR_CONTACTA_CON_NOSOTROS");?></a></h1></p></td>
							</tr>

						</table>
						<div id="contacto" style="display: none;">
						<form name="form" id="form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return enviar();">
						<table cellspacing="1" cellpadding="5" border="0" align="center" width="90%">
							<tr>
								<td width="100%" valign="top" align="center" >
										<p>
											<h1><?php echo constant("STR_CONTACTA_CON_NOSOTROS");?></h1>
										</p>
											<table cellspacing="1" cellpadding="5" border="0" width="100%">
											<tr>
													<td width="50%"><input style="width: 98%;" type="text" name="fName" placeholder="<?php echo constant("STR_NOMBRE");?>" /></td>
													<td><input style="width: 98%;" type="text" name="fEmail" placeholder="<?php echo constant("STR_EMAIL");?>" /></td>
											</tr>
											<tr>
													<td colspan="2">
														<input style="width: 99%;" type="text" name="fSubject" placeholder="<?php echo constant("STR_ASUNTO");?>" />
													</td>
											</tr>
											<tr>
													<td colspan="2">
														<textarea style="width: 99%;height: 150px;" name="fMsg" placeholder="<?php echo constant("STR_MENSAJE");?>" cols="10" ></textarea>
													</td>
											</tr>
											<tr>
													<td colspan="2" align="center">
														<input name="fEnviar" type="submit" class="botones" value="<?php echo constant("STR_ENVIAR");?>" />
													</td>

											</tr>
										</table>
									</td>
							</tr>
							<tr>
								<td>
									<p>
										<input name="fAceptar" type="button" onclick="javascript:self.close();" class="botones" value="<?php echo constant("STR_SALIR");?>" />
									</p>
								</td>
							</tr>
						</table>
					</form>
					</div>
					<table cellspacing="1" cellpadding="5" border="0" width="100%">
						<tr>
							<td align="center"><div id="error"><p style="color:green;font-size:15px;"><?php echo $strMensaje;?>&nbsp;</p></div>
							<input type="hidden" id="msg" name="msg" value="" />
							</td>
						</tr>
					</table>
						<br /><br /><br /><br /><br /><br />
<div id="pie">
    <p class="dweb">&nbsp;</p>
    <p class="copy dweb"><a href="https://www.people-experts.com" target="_blank" title="Expertos en personas"><?php echo constant("NOMBRE_EMPRESA");?></a> - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    <!-- <p class="copy dweb"><a href="<?php echo constant("HTTP_SERVER")?>legal.html" target="_blank" title="<?php echo constant("STR_AVISO_LEGAL");?>"><?php echo constant("STR_AVISO_LEGAL");?></a></p> -->
</div><!-- Fin de pie -->
<br /><br /><br />
				</div><!-- Fin de accesos -->

		</div><!-- Fin de cuerpo -->

</div><!-- Fin de la pagina -->
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}
var msg="<?php echo $strMensaje;?>";
if (msg !=""){
	var o = document.getElementById('msg').
	o.focus();
}
function todos(){
	var acc = document.getElementsByClassName("accordion");
	var i;
	var el = document.getElementById("expandir");
	var sCual = el.innerHTML;
	var bContraer = true;
	if (sCual.indexOf("+") >= 0){
		bContraer =false;
	}
	if (bContraer){
		for (i = 0; i < acc.length; i++) {
			acc[i].classList.replace("active", "active:before");
		    var panel = acc[i].nextElementSibling;
		   	panel.style.maxHeight = null;
		}
		el.innerHTML = "+ <?php echo constant("STR_EXPANDIR_TODO");?>";
	}
	if (!bContraer){
		for (i = 0; i < acc.length; i++) {
			if (!acc[i].classList.contains("active")){
				acc[i].classList.toggle("active");
			}
		    var panel = acc[i].nextElementSibling;
		    panel.style.maxHeight = panel.scrollHeight + "px";
		}
		el.innerHTML = "- <?php echo constant("STR_CONTRAER_TODO");?>";
	}
}
</script>
</body>
</html>
<?php

function enviaEmail($FROM, $FROMNombre, $Asunto, $Cuerpo){
	global $conn;

	$sSubject=$Asunto;
	$sBody=$Cuerpo;
	$sAltBody=strip_tags($Cuerpo);

	require_once constant("DIR_WS_COM") . 'PHPMailer/Exception.php';
	require_once constant("DIR_WS_COM") . 'PHPMailer/PHPMailer.php';
	require_once constant("DIR_WS_COM") . 'PHPMailer/SMTP.php';

	//instanciamos un objeto de la clase phpmailer al que llamamos
	//por ejemplo mail
	$mail = new PHPMailer\PHPMailer\PHPMailer(true);  //PHPMailer instance with exceptions enabled
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	try {
		//Server settings
		//$mail->SMTPDebug = 2; 					                //Enable verbose debug output
		$mail->isSMTP();                                        //Send using SMTP                  
		$mail->Host = constant("HOSTMAIL");						//Set the SMTP server to send through
		$mail->SMTPAuth   = true;                               //Enable SMTP authentication
		$mail->Username = constant("MAILUSERNAME");             //SMTP username
		$mail->Password = constant("MAILPASSWORD");             //SMTP password
		$mail->SMTPSecure = 'tls';							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


		$mail->CharSet = 'utf-8';
		$mail->Debugoutput = 'html';

		// Borro las direcciones de destino establecidas anteriormente
		$mail->clearAllRecipients();

		//Con la propiedad Mailer le indicamos que vamos a usar un
		//servidor smtp
		$mail->Mailer = $mail->Mailer = constant("MAILER");;

		//Asignamos a Host el nombre de nuestro servidor smtp
		$mail->Host = "mail.test-station.biz";

		//Le indicamos que el servidor smtp requiere autenticaciÃ³n
		$mail->SMTPAuth = true;

		//Le decimos cual es nuestro nombre de usuario y password
		$mail->Username = "helpdesk@test-station.biz";
		$mail->Password = constant("MAILPASSWORD");

		//Indicamos cual es nuestra dirección de correo y el nombre que
		//queremos que vea el usuario que lee nuestro correo
		//$mail->From = $cEmpresaFROM->getMail();
		$mail->From = "helpdesk@test-station.biz";
		$mail->AddReplyTo($FROM, $FROMNombre);
		$mail->FromName = $FROMNombre;

		//Asignamos asunto y cuerpo del mensaje
		//El cuerpo del mensaje lo ponemos en formato html, haciendo
		//que se vea en negrita
		$mail->Subject = $sSubject;
		$mail->Body = $sBody;

		//Definimos AltBody por si el destinatario del correo no admite
		//email con formato html
		$mail->AltBody = $sAltBody;

		//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar
		//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120
		$mail->Timeout=120;

		//Indicamos el fichero a adjuntar si el usuario seleccionÃ³ uno en el formulario
		$archivo="none";
		if ($archivo !="none") {
			$mail->AddAttachment($archivo,$archivo_name);
		}

		//Indicamos cuales son las direcciones de destino del correo
		$mail->AddAddress("helpdesk@test-station.biz", "Soporte al Usuario");

		//se envia el mensaje, si no ha habido problemas la variable $success
		//tendra el valor true
		$exito=false;
		//Si el mensaje no ha podido ser enviado se realizaran 2 intentos mas
		//como mucho para intentar enviar el mensaje, cada intento se hara 2 s
		//segundos despues del anterior, para ello se usa la funcion sleep
		$intentos=1;
			while((!$exito)&&($intentos<2)&&($mail->ErrorInfo!="SMTP Error: Data not accepted"))
			{
			sleep(rand(0, 2));
					//echo $mail->ErrorInfo;
					$exito = $mail->Send();
					$intentos=$intentos+1;
			}

		//La clase phpmailer tiene un pequeño bug y es que cuando envia un mail con
		//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho
		//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
		if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
			$exito=true;
		}
		// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
	} catch (PHPMailer\PHPMailer\Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
	}
		return $exito;
}
 ?>
