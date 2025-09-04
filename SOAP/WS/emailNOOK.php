private function enviaEmail($cEmpresa, $cCandidato, $cCorreos_proceso, $IdModoRealizacion)
{
	global $conn;

	$sSubject=$cCorreos_proceso->getAsunto();
	$sBody=$cCorreos_proceso->getCuerpo();
	$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());
	if (empty($sSubject) || empty($sBody)){
		$sTypeError	=	date('d/m/Y H:i:s') . " *** Correo VACIO FROM::[" . $cEmpresa->getMail() . "] TO::[" . $cCandidato->getMail() . "]";
		error_log($sTypeError . " ->\tSUBJECT::" . $sSubject . "\tBODY::" . $sBody . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));
		return false;
	}
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
		//$mail->From = $cEmpresa->getMail();
		$mail->From = constant("EMAIL_CONTACTO");
		$mail->AddReplyTo($cEmpresa->getMail(), $cEmpresa->getNombre());
		$mail->FromName = $cEmpresa->getNombre();
				$nomEmpresa = $cEmpresa->getNombre();

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
		if ($IdModoRealizacion == "2"){	//Administrado SE ENVIAN A LA EMPRESA
			$mail->AddAddress($cEmpresa->getMail(), $cEmpresa->getNombre());
			if($cEmpresa->getMail2()!=""){
				$mail->AddAddress($cEmpresa->getMail2(), $cEmpresa->getNombre());
			}
			if($cEmpresa->getMail3()!=""){
				$mail->AddAddress($cEmpresa->getMail3(), $cEmpresa->getNombre());
			}
		}else{
			$mail->AddAddress($cCandidato->getMail(), $cCandidato->getNombre() . " " . $cCandidato->getApellido1() . " " . $cCandidato->getApellido2());
		}
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