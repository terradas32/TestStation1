<?php

use PHPMailer\PHPMailer\PHPMailer;

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
require_once(constant("DIR_WS_COM") . "Usuarios/UsuariosDB.php");
require_once(constant("DIR_WS_COM") . "Usuarios/Usuarios.php");

include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new UsuariosDB($conn);  // Entidad DB
$cEntidad	= new Usuarios();  // Entidad
$strMensaje="";

if (isset($_POST['fGo']))
{
	if (!empty($_POST['fLogin']))
	{
		require_once(constant("DIR_WS_COM") . "/Utilidades.php");
		$cUtilidades	= new Utilidades();
        $bEncontradoUsuario = $cUtilidades->chkChar($_POST['fLogin']);
		if (!$bEncontradoUsuario)
        {
    		$cEntidad->setLogin($_POST['fLogin']);
    		$rowUser = $cEntidadDB->getLogin($cEntidad);
    		if (sizeof($rowUser) > 0 && $rowUser["login"] == $_POST['fLogin'])
    		{
                if (!empty($rowUser["email"]))
                {
                    $cEntidad->setIdUsuario($rowUser["idUsuario"]);
                    $cEntidad = $cEntidadDB->readEntidad($cEntidad);
//////////////////////////////////////////////////////////////////
					// Mandamos con los datos de Empresa PE
					require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
					require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");
					$cEmpresaPE = new Empresas();
					$cEmpresaPEDB = new EmpresasDB($conn);
					$cEmpresaPE->setIdEmpresa(constant("EMPRESA_PE"));
					$cEmpresaPE = $cEmpresaPEDB->readEntidad($cEmpresaPE);
///////////////////////////////////////////////////////////////////
					$bExito = enviaEmail($cEntidad, $cEmpresaPE);
              		if ($bExito){
                        $strMensaje = constant("MSG_SE_HA_ENVIADO_UN_EMAIL_A_SU_CUENTA_DE_CORREO");
                        $_POST['fLogin'] = "";
        			}else{
                        $strMensaje = constant("ERR");
                        $_POST['fLogin'] = "";
                    }
    			}else{
    				$strMensaje = constant('ERR_ADMINISTRADOR');
    			}
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
		<meta name="generator" content="WIZARD, WI2.2 www.negociainternet.com" />
<?php include('include/metatags.php');?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="estilos/estilos-comunes.css" type="text/css" />
	<link rel="stylesheet" href="estilos/estilos-candidato.css" type="text/css" />
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
        <a href="<?php echo constant("HTTP_SERVER_FRONT");?>index.php?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_INICIO");?>"><img src="graf/logo.jpg" alt="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" title="<?php echo constant("NOMBRE_EMPRESA");?> <?php echo constant("STR_INICIO");?>" /></a>
        </div><!-- Fin de logo -->
    <h1><?php echo constant("STR_ADMINISTRACION");?></h1>
    </div><!-- Fin de la cabecera -->
        <div id="banderas">
            <ul class="band_portada">
            	<?php
		            while (!$listaIdiomas->EOF)
		            {
		            ?>
                        <li class="<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?fLang=<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>" title="<?php echo $listaIdiomas->fields['nombre'];?>"><?php echo $listaIdiomas->fields['nombre'];?></a></li>
                    <?php
		              $listaIdiomas->MoveNext();
		            }
                ?>
            </ul>
        </div><!-- Fin de las banderas -->
    <div id="cuerpo">
        <div id="accesos" class="acc_cand">
        <h2><?php echo constant("STR_RECUERDAME_LA_CLAVE");?></h2>
           <p><?php echo constant("STR_TEXTO_RECUPERA_CLAVE");?></p>
        <form method="post" name="login" action="<?php echo $_SERVER['PHP_SELF'];?>" >
        <label><?php echo constant("STR_LOGIN");?></label>
        <input type="text" name="fLogin" class="obliga" />
        <input name="fGo" type="submit" class="btn_acceder" value="<?php echo constant("STR_ACEPTAR");?>" />
        <input type="hidden" name="fLang" value="<?php echo $sLang;?>" />
        </form>
        <div id="error"><p><?php echo $strMensaje;?>&nbsp;</p></div>
        </div><!-- Fin de accesos -->
    </div><!-- Fin de cuerpo -->
    <div id="pie">
        <p class="dweb"><a href="http://www.negociainternet.com" title="Diseño Web"><?php echo constant("STR_DISENO_DESARROLLO");?></a></p>
        <p class="copy"><?php echo constant("NOMBRE_EMPRESA");?> - <?php echo constant("STR_DERECHOS_RESERVADOS");?></p>
    </div><!-- Fin de pie -->
</div><!-- Fin de la pagina -->
<script type="text/javascript">// Script para Autocompletar "off" y que valide con la W3C
	autoComplete();
</script>
</body>
</html>
<?php
	function enviaEmail($cEntidad, $cEmpresaPE)
	{

		$sSubject=$cEntidad->getNombre() . " - Restaurar contraseña.";
		$sBody	= constant("MSG_UD_HA_SOLICITADO_LA_RESTAURACIÓN_DE_SU_CONTRASENA") . ", " . constant("MSG_SI_ESTA_DE_ACUERDO_PULSE_EL_SIGUIENTE_ENLACE") . ": <a href=\"" . constant("HTTP_SERVER") . "restaurar_password.php?sTK=" . $cEntidad->getToken() . "\" title=\"Restaurar contraseña\">" . constant("HTTP_SERVER") . "restaurar_password.php?sTK=" . $cEntidad->getToken() . "</a> Y posteriormente recibirá una nueva clave es su correo electrónico anulando la anterior.";
		$sAltBody=strip_tags($sBody);

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

			// Borro las direcciones de destino establecidas anteriormente
			$mail->clearAllRecipients();

			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = $mail->Mailer = constant("MAILER");

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresa->getMail();
			$mail->From = constant("EMAIL_CONTACTO");
			
			$mail->AddReplyTo($cEmpresaPE->getMail(), $cEmpresaPE->getNombre());
			$mail->FromName = $cEmpresaPE->getNombre();
				$nomEmpresa = $cEmpresaPE->getNombre();
	
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

      		$mail->AddAddress($cEntidad->getEmail(), $cEntidad->getNombre() . " " . $cEntidad->getApellido1() . " " . $cEntidad->getApellido2());


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
			if(!$exito){
				$sTypeError	=	date('d/m/Y H:i:s') . " Problemas enviando correo electrónico FROM::[" . $mail->From . "] TO::[" . $cCandidato->getMail() . "]";
				error_log($sTypeError . " ->\t" . $mail->ErrorInfo . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
			}
			//echo $mail->ErrorInfo;exit;
			// Borro las direcciones de destino establecidas anteriormente
			$mail->ClearAddresses();
		} catch (PHPMailer\PHPMailer\Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";exit;
		}
	    return $exito;
      
	}
?>
