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
require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");

include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
$cEntidad	= new Empresas();  // Entidad

$strMensaje="";

if (isset($_GET['sTK']))
{
	if (!empty($_GET['sTK']))
	{
		require_once(constant("DIR_WS_COM") . "/Utilidades.php");
		$cUtilidades	= new Utilidades();
        $bEncontrado = $cUtilidades->chkChar($_GET['sTK']);
		if (!$bEncontrado)
        {
    		$cEntidad->setToken($_GET['sTK']);
    		$cEntidad = $cEntidadDB->usuarioPorToken($cEntidad);
    		if ($cEntidad->getIdEmpresa() != "")
    		{
                $cEntidad->setPassword($cUtilidades->newPass());

//////////////////////////////////////////////////////////////////

				// Mandamos con los datos de Empresa PE
				require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
				require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
				// Empresa PE
				$cEmpresaPE = new Empresas();
				$cEmpresaPEDB = new EmpresasDB($conn);
				$cEmpresaPE->setIdEmpresa(constant("EMPRESA_PE"));
				$cEmpresaPE = $cEmpresaPEDB->readEntidad($cEmpresaPE);

				$newPass= $cEntidad->getPassword();
				$sUsuario=$cEntidad->getUsuario();
				//La entidad está preparada para consultar sólo por
				//Id Tipo Notificacion
				require_once(constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
				require_once(constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");
				$cNotificacionesDB	= new NotificacionesDB($conn);
				$cNotificaciones	= new Notificaciones();
				$cNotificaciones->setIdTipoNotificacion(4);	//Recordatorio de contraseña
				$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
				$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, null, null, null, null, null, null, $sUsuario, $newPass);

				$sSubject=$cNotificaciones->getAsunto();
				$sBody=$cNotificaciones->getCuerpo();
				$sAltBody=strip_tags($cNotificaciones->getCuerpo());
				//Mandamos con datos de PE
				if (!enviaEmail($cEntidad, $cEmpresaPE, $cNotificaciones)){
					$strMensaje = constant("ERR");
					$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
					$sTypeError.= $cEntidad->getNombre() . " [" . $cEntidadPE->getMail() . "]";
					error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
				}else{
                    $cEntidadDB->modificar($cEntidad);
                    $strMensaje = constant("MSG_SE_HA_ENVIADO_LA_NUEVA_CONTRASENA_A_SU_CORREO");
				}
///////////////////////////////////////////////////////////////////
    		}else{
    			$strMensaje = constant('ERR_ADMINISTRADOR');
    		}
        }else{
            $strMensaje = constant('ERR_ADMINISTRADOR');
        }
	}else $strMensaje = constant('ERR_FORM_ERROR');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $sLang;?>" xml:lang="<?php echo $sLang;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="generator" content="WIZARD, WI2.2 www.azulpomodoro.com" />
<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="codigo/noback.js"></script>
    <script language="javascript" type="text/javascript" src="codigo/eventos.js"></script>
	<script type="text/javascript">
        function autoComplete()
        {
                var i = 0;
                // Recorres los elementos INPUT del documento
                for(var node; node = document.getElementsByTagName('input')[i]; i++){
                // Obtienes el tipo de INPUT
                var type = node.getAttribute('type').toLowerCase();
                // Si es del tipo TEXT deshabilitas su autocompletado
                if(type == 'text'){
                    node.setAttribute('autocomplete', 'off');
                }
            }
        }
    </script>
</head>
<body onload="autoComplete();">
<div id="pagina">
    <div id="head" class="candidato">
        <div class="logo">
        <a href="<?php echo constant("HTTP_SERVER_FRONT")?>index.php" title="Inicio"><img src="graf/logo.jpg" alt="#" title="#" /></a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_EMPRESA");?></h1>
    </div><!-- Fin de la cabecera -->
    <div id="cuerpo">
        <div id="accesos" class="acc_cand">
        <h2><?php echo constant("STR_RECUERDAME_LA_CLAVE");?></h2>
        <form method="post" name="login" action="<?php echo $_SERVER['PHP_SELF'];?>" >
        <input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
        </form>
        <div id="error"><p><?php echo $strMensaje;?>&nbsp;</p></div>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <!-- <p class="dweb"><a href="http://www.azulpomodoro.com" title="Diseño Web"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
         --><p class="copy"><?php echo constant("NOMBRE_EMPRESA");?> - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body>
</html>
<?php
	//Se envia correo de Notificación
	//de que se ha dado de alta una empresa al padre y a Psicologos
	function enviaEmail($cTO, $cFROM, $cNotificaciones){
		global $conn;

		$sSubject=$cNotificaciones->getAsunto();
		$sBody=$cNotificaciones->getCuerpo();
		$sAltBody=strip_tags($cNotificaciones->getCuerpo());

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
			$mail->SMTPSecure = constant("MAIL_ENCRYPTION");							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';


			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo

			//$mail->From = $cFROM->getMail();
			$mail->From = constant("EMAIL_CONTACTO");
			$mail->AddReplyTo($cFROM->getMail(), $cFROM->getNombre());
			$mail->FromName = $cFROM->getNombre();
				$nomEmpresa = $cFROM->getNombre();

			//Asignamos asunto y cuerpo del mensaje
			//El cuerpo del mensaje lo ponemos en formato html, haciendo
			//que se vea en negrita
			$mail->Subject = $nomEmpresa . " - " . $sSubject;
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
			$mail->AddAddress($cTO->getMail(), $cTO->getNombre());
			/*
			echo "<br />De: " . $cFROM->getMail();
			echo "<br />De Nombre: " . $cFROM->getNombre();
			echo "<br />Para: " . $cTO->getMail();
			echo "<br />Para Nombre: " . $cTO->getNombre();
			*/
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
