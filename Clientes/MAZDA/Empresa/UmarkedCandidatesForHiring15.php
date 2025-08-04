<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

//
ob_start();
	require_once('./include/Configuracion.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . 'Idiomas.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . 'Seguridad.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresas/Empresas.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Notificaciones/NotificacionesDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Notificaciones/Notificaciones.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Procesos/Procesos.php");

include_once ('include/conexion.php');

	$_POST['sTK'] = "";	//Inicializamos TK
	//Revisamos TK de la empresa padre de MAZDA
	$cAutologinDB	= new EmpresasDB($conn);  // Entidad DB
	$cAutologin	= new Empresas();  // Entidad
	$cAutologin->setIdEmpresa("5650");	// Empresa FormacionMB
	$cAutologin = $cAutologinDB->readEntidad($cAutologin);
	if ($cAutologin->getToken() != ""){
		$_POST['sTK'] = $cAutologin->getToken();
		$_POST['MODO'] = constant("MNT_ALTA");
	}else{
		error_log("UNMARKED CANDIDATES FOR HIRING ->\t Error ejecutando el proceso de envío recordatorio.\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
		echo "UNMARKED CANDIDATES FOR HIRING -> Error ejecutando el proceso de envío recordatorio.";
	}

	//require_once(constant("DIR_WS_INCLUDE") . "SeguridadTemplate.php");

	$cUtilidades	= new Utilidades();

	$cEntidadDB	= new EmpresasDB($conn);  // Entidad DB
	$cEntidad	= new Empresas();  // Entidad

	$cCandidatosDB	= new CandidatosDB($conn);  // Entidad DB
	$cCandidatos	= new Candidatos();  // Entidad

	$cNotificacionesDB	= new NotificacionesDB($conn);
	$cNotificaciones	= new Notificaciones();

	$cProcesosDB	= new ProcesosDB($conn);  // Entidad DB
	$cProcesos	= new Procesos();  // Entidad

	$_cEntidadUsuarioTK = getUsuarioToken($conn);

	$sCol1='';
	$sCol2='';

	$sHijos = "";

	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();

	$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);

	switch ($_POST['MODO'])
	{
		case constant("MNT_ALTA"):

				if (!empty($sHijos)){
					$sHijos = substr($sHijos, 0, -1);
				}
				$cEntidad->setIdEmpresa($sHijos);
				$sSQL = $cEntidadDB->readListaIN($cEntidad);
				$rsEmpresas = $conn->Execute($sSQL);
				$sHTMLCandidatosHead = "";
				$sHTMLCandidatosHead .= '<tr>';
				$sHTMLCandidatosHead .= '<td><strong>';
				$sHTMLCandidatosHead .= 'DNI';
				$sHTMLCandidatosHead .= '</strong></td>';
				$sHTMLCandidatosHead .= '<td><strong>';
				$sHTMLCandidatosHead .= 'NOMBRE';
				$sHTMLCandidatosHead .= '</strong></td>';
				$sHTMLCandidatosHead .= '<td><strong>';
				$sHTMLCandidatosHead .= '1º APELLIDO';
				$sHTMLCandidatosHead .= '</strong></td>';
				$sHTMLCandidatosHead .= '<td><strong>';
				$sHTMLCandidatosHead .= '2º APELLIDO';
				$sHTMLCandidatosHead .= '</strong></td>';
				$sHTMLCandidatosHead .= '<td><strong>';
				$sHTMLCandidatosHead .= 'EMAIL';
				$sHTMLCandidatosHead .= '</strong></td>';
				$sHTMLCandidatosHead .= '</tr>';
				while (!$rsEmpresas->EOF)
				{
					$sHTMLCandidatos = "";
					$cCandidatos	= new Candidatos();  // Entidad
					$cCandidatos->setIdEmpresa($rsEmpresas->fields['idEmpresa']);
					$cCandidatos->setFinalizado("1");
					$sSQL = $cCandidatosDB->readListaIN($cCandidatos);
					$rsCandidatos = $conn->Execute($sSQL);
					while (!$rsCandidatos->EOF)
					{
						//Miramos si ya se le ha enviado aviso a los 15 días
						$sSQL = 'SELECT * FROM umarkedcandidatesforhiring WHERE ';
						$sSQL .= ' idEmpresa = ' . $rsCandidatos->fields['idEmpresa'];
						$sSQL .= ' AND idProceso = ' . $rsCandidatos->fields['idProceso'];
						$sSQL .= ' AND idCandidato = ' . $rsCandidatos->fields['idCandidato'];
						$sSQL .= ' AND fechaInicio IS NOT NULL ';

						//echo "<br />" . $sSQL;
						$rsAvisos15 = $conn->Execute($sSQL);
						$bAvisar=false;
            $now = date('Y-m-d');
						$dAhora = strtotime($now);
						if ($rsAvisos15->NumRows() > 0)
						{
							$cProcesos->setIdEmpresa($rsCandidatos->fields['idEmpresa']);
							$cProcesos->setIdProceso($rsCandidatos->fields['idProceso']);
							$cProcesos = $cProcesosDB->readEntidad($cProcesos);
							if (!empty($rsAvisos15->fields['UltimaFecha'])){
								$fecInicio = $rsAvisos15->fields['UltimaFecha'];
								$formato = 'Y-m-d';
								$fecha = DateTime::createFromFormat($formato, $fecInicio);
								$intervalo = new DateInterval('P15D'); // 15 días
								$fecha->add($intervalo);
								$sFecha15 = $fecha->format('Y-m-d');
								$dFecha15 = strtotime($sFecha15);
                //echo "<br />fecInicio::" . $fecInicio . " sFecha15::" . $sFecha15 . " now::" . $now;
							}else{
								$fecInicio = $rsAvisos15->fields['fechaInicio'];
                //echo "<br />------->" . $fecInicio;
								$formato = 'Y-m-d';
								$fecha = DateTime::createFromFormat($formato, $fecInicio);
								$intervalo = new DateInterval('P15D'); // 15 días
								$fecha->add($intervalo);
								$sFecha15 = $fecha->format('Y-m-d');
								$dFecha15 = strtotime($sFecha15);
                //echo "<br />fecInicio::" . $fecInicio . " sFecha15::" . $sFecha15 . " now::" . $now;
							}

							//echo "<br />" . $rsCandidatos->fields['idEmpresa'] . ' - ' . $rsCandidatos->fields['idProceso'] . ' - ' . $fecInicio;


              //echo "<br />sFecha15::" . $sFecha15 . " now::" . $now;
							if ($now == $sFecha15){
								$bAvisar=true;
								$sSQL = 'UPDATE umarkedcandidatesforhiring SET ';
								$sSQL .= 'UltimaFecha = \'' . $sFecha15 . '\' ';
								$sSQL .= 'WHERE idEmpresa= ' . $rsCandidatos->fields['idEmpresa'];
								$sSQL .= ' AND idProceso= ' . $rsCandidatos->fields['idProceso'];
								$sSQL .= ' AND idCandidato= ' . $rsCandidatos->fields['idCandidato'];
								$conn->Execute($sSQL);
							}
							if ($rsCandidatos->fields['certificada'] == "" && $bAvisar)
							{
									$sHTMLCandidatos .= '<tr>';
									$sHTMLCandidatos .= '<td>';
									$sHTMLCandidatos .= $rsCandidatos->fields['dni'];
									$sHTMLCandidatos .= '</td>';
									$sHTMLCandidatos .= '<td>';
									$sHTMLCandidatos .= $rsCandidatos->fields['nombre'];
									$sHTMLCandidatos .= '</td>';
									$sHTMLCandidatos .= '<td>';
									$sHTMLCandidatos .= $rsCandidatos->fields['apellido1'];
									$sHTMLCandidatos .= '</td>';
									$sHTMLCandidatos .= '<td>';
									$sHTMLCandidatos .= $rsCandidatos->fields['apellido2'];
									$sHTMLCandidatos .= '</td>';
									$sHTMLCandidatos .= '<td>';
									$sHTMLCandidatos .= $rsCandidatos->fields['mail'];
									$sHTMLCandidatos .= '</td>';
									$sHTMLCandidatos .= '</tr>';
							}
						}
						$rsCandidatos->MoveNext();
					}

					if (!empty($sHTMLCandidatos))
					{
						//Enviar sin contratar x Empresa.
						$sFrom=$rsEmpresas->fields['mail'];	//Cuenta de correo de la empresa
						$sFromName=$rsEmpresas->fields['nombre'];	//Nombre de la empresa
						$newPass= '';
						$sUsuario='';
						$cEntidad	= new Empresas();  // Entidad
						$cEntidad->setIdEmpresa($rsEmpresas->fields['idEmpresa']);
						$cEntidad = $cEntidadDB->readEntidad($cEntidad);
						//La entidad está preparada para consultar sólo por
						//Id Tipo Notificacion
						$cNotificaciones->setIdTipoNotificacion(9);	//Recordatorio NO marcados contratación
						$cNotificaciones = $cNotificacionesDB->readEntidad($cNotificaciones);
						$cNotificaciones = $cNotificacionesDB->parseaHTML($cNotificaciones, $cEntidad, null, null, null, null, null, $sUsuario, $newPass);

						$sSubject=$cNotificaciones->getAsunto();
						$sBody=$cNotificaciones->getCuerpo();
						//TAG:: @lista_candidatos_pte_gestion@
						$sBody = str_replace("@lista_candidatos_pte_gestion@", '<table width="100%">' . $sHTMLCandidatosHead . $sHTMLCandidatos . '</table>', $sBody);

						$cNotificaciones->setCuerpo($sBody);
						$sAltBody=strip_tags($sBody);

						// Empresa Padre
						$cEmpresaPadre = new Empresas();
						$cEmpresaPadreDB = new EmpresasDB($conn);
						$cEmpresaPadre->setIdPadre($cEntidad->getIdPadre());
						$cEmpresaPadre = $cEmpresaPadreDB->readEntidadPadre($cEmpresaPadre);
						//Mandamos a la Empresa proveedora

						if (!enviaEmail($cEntidad, $cEmpresaPadre, $cNotificaciones)){
							$sTypeError=date('d/m/Y H:i:s') . " No se ha podido enviar correos a las siguientes direcciones:\n";
							$sTypeError.= $cEmpresaPadre->getNombre() . " [" . $cEmpresaPadre->getMail() . "]";
							error_log($sTypeError . "\n", 3, constant("DIR_FS_PATH_NAME_LOG"));
						}
					}
					$rsEmpresas->MoveNext();
				}

			break;
	} // end switch


	//Se envia correo de Notificación
	//de que se ha dado de alta una empresa al padre y a Psicologos
	function enviaEmail($cEmpresaTO, $cEmpresaFROM, $cNotificaciones){
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
			$mail->SMTPSecure = 'tls';							    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port      = constant("PORTMAIL");                                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above


			$mail->CharSet = 'utf-8';
			$mail->Debugoutput = 'html';


			//Con la propiedad Mailer le indicamos que vamos a usar un
			//servidor smtp
			$mail->Mailer = $mail->Mailer = constant("MAILER");;

			//Asignamos a Host el nombre de nuestro servidor smtp
			$mail->Host = constant("HOSTMAIL");

			//Le indicamos que el servidor smtp requiere autenticaciÃ³n
			$mail->SMTPAuth = true;

			//Le decimos cual es nuestro nombre de usuario y password
			$mail->Username = constant("MAILUSERNAME");
			$mail->Password = constant("MAILPASSWORD");

			//Indicamos cual es nuestra dirección de correo y el nombre que
			//queremos que vea el usuario que lee nuestro correo
			//$mail->From = $cEmpresaFROM->getMail();
			$mail->From = constant("MAILUSERNAME");
			$mail->AddReplyTo($cEmpresaFROM->getMail(), $cEmpresaFROM->getNombre());
			$mail->FromName = $cEmpresaFROM->getNombre();

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
			$mail->AddAddress($cEmpresaTO->getMail(), $cEmpresaTO->getNombre());
			if($cEmpresaTO->getMail2()!=""){
				$mail->AddAddress($cEmpresaTO->getMail2(), $cEmpresaTO->getNombre());

			}
			if($cEmpresaTO->getMail3()!=""){
				$mail->AddAddress($cEmpresaTO->getMail3(), $cEmpresaTO->getNombre());
			}
			/*
			echo "<br />De: " . $cEmpresaFROM->getMail();
			echo "<br />De Nombre: " . $cEmpresaFROM->getNombre();
			echo "<br />Para: " . $cEmpresaTO->getMail();
			echo "<br />Para Nombre: " . $cEmpresaTO->getNombre();
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
