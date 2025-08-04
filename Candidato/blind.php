<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

session_start();
require_once('include/Configuracion.php');
include_once('include/Idiomas.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
	include(constant("DIR_ADODB") . 'adodb.inc.php');
	include_once(constant("DIR_WS_COM") . 'adodb-pager.inc.php');
	require_once(constant("DIR_WS_COM") . "Combo.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultadosDB.php");
	require_once(constant("DIR_WS_COM") . "Baremos_resultados/Baremos_resultados.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/IdiomasDB.php");
	require_once(constant("DIR_WS_COM") . "Idiomas/Idiomas.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/CandidatosDB.php");
	require_once(constant("DIR_WS_COM") . "Candidatos/Candidatos.php");
	require_once(constant("DIR_WS_COM") . "Procesos/ProcesosDB.php");
	require_once(constant("DIR_WS_COM") . "Procesos/Procesos.php");
	require_once(constant("DIR_WS_COM") . "Utilidades.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_procesoDB.php");
	require_once(constant("DIR_WS_COM") . "Correos_proceso/Correos_proceso.php");
	require_once(constant("DIR_WS_COM") . "Correos/CorreosDB.php");
	require_once(constant("DIR_WS_COM") . "Correos/Correos.php");
	require_once(constant("DIR_WS_COM") . "Envios/EnviosDB.php");
	require_once(constant("DIR_WS_COM") . "Envios/Envios.php");
	require_once(constant("DIR_WS_COM") . "Empresas/EmpresasDB.php");
	require_once(constant("DIR_WS_COM") . "Empresas/Empresas.php");


include_once ('include/conexion.php');

$cEntidadIdiomasDB	= new IdiomasDB($conn);  // Entidad DB
$cEntidadIdiomas	= new Idiomas();  // Entidad
$cEntidadIdiomas->setActivoBack(1);
$sqlIdiomas = $cEntidadIdiomasDB->readLista($cEntidadIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);

$cEntidadDB	= new CandidatosDB($conn);  // Entidad DB
$cEntidad	= new Candidatos();  // Entidad
$cProcesosDB	= new ProcesosDB($conn);
$cProcesos	= new Procesos();
$cEnviosDB	= new EnviosDB($conn);  // Entidad DB
$cEnvios	= new Envios();  // Entidad
$cUtilidades	= new Utilidades();

$cEmpresas = new Empresas();
$cEmpresasDB = new EmpresasDB($conn);

$strMensaje = "";
$sLogo = '<img alt="' . constant("NOMBRE_EMPRESA") . '" src="' . constant("DIR_WS_GRAF") . 'logo.png" border="0" />';
//Miramos si viene el hash por url, en tal caso recogemos la información de empresa y Proceso
if (!empty($_GET['h'])){

	$h = $_GET['h'];
	//Miramos si intentan hacernos injection
	 $bEncontradoTK = $cUtilidades->chkChar($h);
	 if (!$bEncontradoTK)
	 {
	 	//Recogemos la información de Empresa y Proceso
// 	 	$aH[0] -> Empresa
// 	 	$aH[1] -> Proceso
	 	$aH = explode(constant("CHAR_SEPARA"), base64_decode($h));
	 	//Validamos que la empresa tiene ese proceso.
		$cEmpresas->setIdEmpresa($aH[0]);
		$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

		if ($cEmpresas->getPathLogo() != ""){
			$size = @getimagesize(constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo());
			$anchura=$size[0];
			$altura=$size[1];
			if ($altura > 85){
				$altura = 85;
			}
				$altura.="px";
				$sLogo = '<img title="' . $cEmpresas->getNombre() . '" alt="' . $cEmpresas->getNombre() . '" src="' . constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo() . '" height="' . $altura . '" />';
		}else{
			$sLogo = $cEmpresas->getNombre();
		}

	 	$cProcesos->setIdEmpresa($aH[0]);
	 	$cProcesos->setIdProceso($aH[1]);
	 	$_POST['fIdEmpresa'] = $aH[0];
	 	$_POST['fIdProceso'] = $aH[1];
	 	$cProcesos = $cProcesosDB->readEntidad($cProcesos);

	 	$fecInicio = $cProcesos->getFechaInicio();
	 	$fecFin = $cProcesos->getFechaFin();
	 	if (!empty($fecInicio) && !empty($fecFin))
	 	{
	 		//Miramos si quedan candidatos temporales

	 		$cEntidad->setIdEmpresa($aH[0]);
	 		$cEntidad->setIdProceso($aH[1]);
	 		$sSQL = "SELECT * FROM candidatos WHERE idEmpresa=" . $cEntidad->getIdEmpresa();
	 		$sSQL .= " AND idProceso=" . $cEntidad->getIdProceso();
	 		$sSQL .= " AND mail=''";
	 		$sSQL .= " AND nombre=''";
	 		//echo "<br>" . $sSQL;
	 		$rsC = $conn->Execute($sSQL);

	 		if($rsC->recordCount() > 0){
	 			include('Template/datosblind.php');
	 		}else{
	 			$strMensaje = "985:: " . constant("ERR_PROCESO_DESHABILITADO_ALTAS_CIEGAS");
	 		}

	 	}else {
	 		$strMensaje = "910:: " . constant("ERR_NO_AUTORIZADO");
	 	}
	 }else $strMensaje = constant("ERR_FORM_LOGIN");
	if (!empty($strMensaje)){
		$_SESSION['mensaje' . constant("NOMBRE_SESSION")] = $strMensaje;
        header('Location: ' . constant("HTTP_SERVER") . 'msg.php?fLang=' . $sLang);
    }
}else {
	if (isset($_POST['fGo'])){

		if ( (!empty($_POST['fNombre'])) && (!empty($_POST['fApellido1'])) && (!empty($_POST['fMailCan'])))
		{
 			if ( (!empty($_POST['fIdEmpresa'])) && (!empty($_POST['fIdProceso'])))
 			{
 				//Miramos si ya está registrado
				$cEmpresas->setIdEmpresa($_POST['fIdEmpresa']);
				$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

				if ($cEmpresas->getPathLogo() != ""){
					$size = @getimagesize(constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo());
					$anchura=$size[0];
					$altura=$size[1];
					if ($altura > 85){
						$altura = 85;
					}
						$altura.="px";
						$sLogo = '<img title="' . $cEmpresas->getNombre() . '" alt="' . $cEmpresas->getNombre() . '" src="' . constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo() . '" height="' . $altura . '" />';
				}else{
					$sLogo = $cEmpresas->getNombre();
				}
 				$cEntidad->setIdEmpresa($_POST['fIdEmpresa']);
 				$cEntidad->setIdProceso($_POST['fIdProceso']);
 				$cEntidad->setMail($_POST['fMailCan']);
 				$cEntidad = $cEntidadDB->consultaPorMail($cEntidad);
 				$sToken = $cEntidad->getToken();
				$cProcesos->setIdEmpresa($_POST['fIdEmpresa']);
				$cProcesos->setIdProceso($_POST['fIdProceso']);
				$cProcesos = $cProcesosDB->readEntidad($cProcesos);

  				if ($cEntidad->getNombre() == "")
  				{

	 				$fecInicio = $cProcesos->getFechaInicio();
	 				$fecFin = $cProcesos->getFechaFin();
	 				if (!empty($fecInicio) && !empty($fecFin))
	 				{
	 					//Miramos si quedan candidatos temporales
	 					$cEntidad->setIdEmpresa($_POST['fIdEmpresa']);
	 					$cEntidad->setIdProceso($_POST['fIdProceso']);
	 					$sSQL = "SELECT * FROM candidatos WHERE idEmpresa=" . $cEntidad->getIdEmpresa();
	 					$sSQL .= " AND idProceso=" . $cEntidad->getIdProceso();
	 					$sSQL .= " AND mail=''";
	 					$sSQL .= " AND nombre=''";
	 					//echo "<br>" . $sSQL;
	 					$rsC = $conn->Execute($sSQL);

	 					if($rsC->recordCount() > 0){
	 						//Asignamos uno de los vacios y le mandamos correo

	 						$cEntidad->setIdCandidato($rsC->fields['idCandidato']);
	 						$cEntidad = $cEntidadDB->readEntidad($cEntidad);
	 						$cEntidad->setIdEmpresa($_POST['fIdEmpresa']);
	 						$cEntidad->setIdProceso($_POST['fIdProceso']);
	 						$cEntidad->setNombre($_POST['fNombre']);
	 						$cEntidad->setApellido1($_POST['fApellido1']);
	 						$cEntidad->setMail($_POST['fMailCan']);	//Para poder actualizar el TK
	 						$cEntidadDB->modificar($cEntidad);
	 						$cEntidad = $cEntidadDB->candidatoPorToken($cEntidad);

	 						if ($cEntidad->getIdProceso() != "")
	 						{
	 							//Sacamos la información del proceso
	 							$cProcesosDB	= new ProcesosDB($conn);
	 							$cProcesos	= new Procesos();
	 							$cProcesos->setIdEmpresa($cEntidad->getIdEmpresa());
	 							$cProcesos->setIdProceso($cEntidad->getIdProceso());
	 							$cProcesos = $cProcesosDB->readEntidad($cProcesos);

	 							$fecInicio = $cProcesos->getFechaInicio();
	 							$fecFin = $cProcesos->getFechaFin();

	 							$cEmpresas = new Empresas();
	 							$cEmpresasDB = new EmpresasDB($conn);
	 							$cEmpresas->setIdEmpresa($cEntidad->getIdEmpresa());
	 							$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

	 							//Miramos si puede iniciar las pruebas del proceso
	 							if ($cUtilidades->isCurrent2Dates($fecInicio, $fecFin, $cEmpresas->getTimezone()))
	 							{
	 								//Miramos si aun estándo dentro de fechas, ya ha finalizado
	 								//Todas las pruebas del proceso
	 								//				if (empty($rowUser['finalizado']))
	 								//				{
	 								//NO actualizamos el TK para la UNAV
	 								//$token =md5(uniqid('', true));
	 								//$cEntidad->setToken($token);

	 								//Le dejamos con el mismo Token, pero actualizamos acción
	 								//para que no de sesion caducada
	 								$cEntidadDB->ActualizaToken($cEntidad);


	 								//Actualizamos el último login
// 	 								if ($cEntidadDB->ultimoLogin($cEntidad) == false)
// 	 								{
// 	 									echo constant("ERR");
// 	 									exit;
// 	 								}
	 								//Seteamos el token y las variables necesarias
	 								$_POST['sTKCandidatos'] = $cEntidad->getToken();
	 								$_SESSION["sTKCandidatos" . constant("NOMBRE_SESSION")] = $cEntidad->getToken();

	 								//******************************

	 								$cProcesosDB	= new ProcesosDB($conn);  // Entidad DB
	 								$cProcesos		= new Procesos();  // Entidad

	 								$cEmpresas = new Empresas();
	 								$cEmpresasDB = new EmpresasDB($conn);
	 								$cEmpresas->setIdEmpresa($cEntidad->getIdEmpresa());
	 								$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);

	 								//Ponemos una contraseña al Candidato y le enviamos un correo
	 								$sFrom=$cEmpresas->getMail();	//Cuenta de correo de la empresa
	 								$sFromName=$cEmpresas->getNombre();	//Nombre de la empresa

	 								$cProcesos->setIdProceso($cEntidad->getIdProceso());
	 								$cProcesos->setIdEmpresa($cEntidad->getIdEmpresa());
	 								$cProcesos = $cProcesosDB->readEntidad($cProcesos);
	 								$IdModoRealizacion = "1";

	 								$sMailsNOEnviados="";
	 								$sNOEnviados= "";
	 								$iFallidos = 0;


	 								$sBody = "";
	 								$sAltBody = "";
	 								$sSubject = "";
	 								$aMailsNOEnviados = (empty($sMailsNOEnviados)) ? array() : explode(",", $sMailsNOEnviados);

	 								$newPass= $cUtilidades->newPass();
	 								$sUsuario=$cEntidad->getMail();

	 								$cCorreos_procesoDB	= new Correos_procesoDB($conn);  // Entidad DB
	 								$cCorreos_proceso	= new Correos_proceso();  // Entidad
	 								$cCorreos_proceso->setIdEmpresa($cEntidad->getIdEmpresa());
	 								$cCorreos_proceso->setIdProceso($cEntidad->getIdProceso());
	 								$cCorreos_proceso->setIdTipoCorreo("1");
	 								$sSQL = $cCorreos_procesoDB->readLista($cCorreos_proceso);
	 								$rsCorreos_proceso = $conn->Execute($sSQL);
	 								if ($rsCorreos_proceso->recordCount() > 0){
	 									$cCorreos_proceso->setIdCorreo($rsCorreos_proceso->fields['idCorreo']);
	 								}else{
	 									$cCorreos_proceso->setIdCorreo("1");
	 								}
	 								$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);
	 								if ($cCorreos_proceso->getNombre() =="")
	 								{
	 									$cCorreos = new Correos();
	 									$cCorreosDB = new CorreosDB($conn);
	 									$cCorreos->setIdCorreo("1");
	 									$cCorreos->setIdTipoCorreo("1");
	 									$cCorreos->setIdEmpresa("0");
	 									$cCorreos = $cCorreosDB->readEntidad($cCorreos);

	 									$cCorreos_proceso = new Correos_proceso();
	 									$cCorreos_proceso->setIdEmpresa($cEntidad->getIdEmpresa());
	 									$cCorreos_proceso->setIdProceso($cEntidad->getIdProceso());
	 									$cCorreos_proceso->setIdTipoCorreo("1");
	 									$cCorreos_proceso->setIdCorreo("1");
	 									$cCorreos_proceso->setNombre($cCorreos->getNombre());
	 									$cCorreos_proceso->setAsunto($cCorreos->getAsunto());
	 									$cCorreos_proceso->setCuerpo($cCorreos->getCuerpo());
	 									$cCorreos_proceso->setDescripcion($cCorreos->getDescripcion());
	 									$cCorreos_procesoDB->insertar($cCorreos_proceso);

	 									$cCorreos_proceso = new Correos_proceso();
	 									$cCorreos_proceso->setIdEmpresa($cEntidad->getIdEmpresa());
	 									$cCorreos_proceso->setIdProceso($cEntidad->getIdProceso());
	 									$cCorreos_proceso->setIdTipoCorreo("1");
	 									$cCorreos_proceso->setIdCorreo("1");
	 									$cCorreos_proceso = $cCorreos_procesoDB->readEntidad($cCorreos_proceso);

	 								}
	 								$sMSG="";

	 								$cCorreos_proceso = $cCorreos_procesoDB->parseaHTML($cCorreos_proceso, $cEntidad, $cProcesos, $cEmpresas, $sUsuario, $newPass);

	 								$sSubject=$cCorreos_proceso->getAsunto();
	 								$sBody=$cCorreos_proceso->getCuerpo();
	 								$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

	 								if (!empty($sBody) && !empty($sSubject))
	 								{
	 									if (!in_array(strtolower($cEntidad->getMail()), $aMailsNOEnviados))
	 									{
	 										if (!enviaEmail($cEmpresas, $cEntidad, $cCorreos_proceso, $IdModoRealizacion)){
	 											//informamos de los emails q no se han podido enviar.
	 											$iFallidos++;
	 											$sMSG_JS_ERROR="No se ha podido enviar correos a las siguientes direcciones:\\n";
	 											$sNOEnviados.= $cEntidad->getNombre() . " " . $cEntidad->getApellido1() . " " . $cEntidad->getApellido2() . " [" . $cEntidad->getMail() . "]\\n";
	 											$sMailsNOEnviados.= "," . $cEntidad->getMail();
	 										}else{
	 											//Actualizamos el usuario con la nueva contraseña
	 											//Lo ponemos como informado
	 											$cEntidad->setPassword($newPass);
	 											$cEntidad->setInformado(1);
	 											$OK = $cEntidadDB->modificar($cEntidad);
	 											$OK = $cEntidadDB->modificarPass($cEntidad);

	 											$cEnvios_hist	= new Envios();
	 											$cEnvios_hist->setIdEmpresa($cEntidad->getIdEmpresa());
	 											$cEnvios_hist->setIdProceso($cEntidad->getIdProceso());
	 											$cEnvios_hist->setIdTipoCorreo("1");
	 											$cEnvios_hist->setIdCorreo("1");
	 											$cEnvios_hist->setIdCandidato($cEntidad->getIdCandidato());
	 											$cEnvios_hist->setUsuAlta($cEmpresas->getIdEmpresa());
	 											$cEnvios_hist->setUsuMod($cEmpresas->getIdEmpresa());
	 											$cEnviosDB->insertar($cEnvios_hist);
	 											$sTypeError	=	date('d/m/Y H:i:s') . "BLIND Correo enviado FROM::[" . $sFrom . "] TO::[" . $cEntidad->getMail() . "]";
	 											error_log($sTypeError . " ->\t" . $cCorreos_proceso->getCuerpo() . "\n", 3, constant("DIR_FS_PATH_NAME_CORREO"));

	 										}
	 									}
	 								}else{
	 									//informamos de los emails q no se han podido enviar.
	 									$iFallidos++;
	 									$sMSG_JS_ERROR="MSG400 empty BODY::\\t" . $cEntidad->getMail();
	 									$sMSG_JS_RESUMEN.="\\n" . $sMSG_JS_ERROR;
	 									$sNOEnviados.= $cEntidad->getNombre() . " " . $cEntidad->getApellido1() . " " . $cEntidad->getApellido2() . " [" . $cEntidad->getMail() . "]\\n";
	 									$sMailsNOEnviados.= "," . $cEntidad->getMail();
	 								}
	 								if (!empty($sNOEnviados)){
	 									$strMensaje = "No le hemos podido enviar sus datos de acceso al proceso, pongase en contacto con el administrado.";
	 								}else{
	 									$strMensaje = "<p style='color:green;'>" . constant("MSG_DATOS_DE_ACCESO_ENVIADOS_ALTAS_CIEGAS") . "</p>";
	 								}


	 								//******************************

	 							}else{
	 								$strMensaje = constant("STR_PROCESO_FUERA_DE_FECHAS");
	 							}
	 						}else{
	 							$strMensaje = "905-1:: " . constant("ERR_NO_AUTORIZADO");
	 						}

	 					}else{
	 						$strMensaje = "985:: " . constant("ERR_PROCESO_DESHABILITADO_ALTAS_CIEGAS");
	 					}

	 				}else {
	 					$strMensaje = "910-1:: " . constant("ERR_NO_AUTORIZADO");
	 				}
 				}else {
 					$strMensaje = sprintf(constant("MSG_YA_REGISTRADO_ALTAS_CIEGAS_CONTINUAR"),$sLang, $sToken);
 				}
			}else {
				$strMensaje = "985-1:: " . constant("ERR_PROCESO_DESHABILITADO_ALTAS_CIEGAS");
			}
		}else{
			$strMensaje = constant("ERR_FORM_TODOS_CAMPOS_REQUERIDOS");
		}
		if (!empty($strMensaje)){
	        include('Template/datosblind.php');
	    }
	}else{
        header('Location: ' . constant("HTTP_SERVER") . '');

	}
}

function enviaEmail($cEmpresa, $cCandidato, $cCorreos_proceso, $IdModoRealizacion){
	global $conn;
	global $cProcesos;
	$sSubject=$cCorreos_proceso->getAsunto();
	$sBody=$cCorreos_proceso->getCuerpo();
	$sAltBody=strip_tags($cCorreos_proceso->getCuerpo());

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
		$mail->From = constant("MAILUSERNAME");
		if ($cProcesos->getProcesoConfidencial() == "1"){
			$mail->AddReplyTo(constant("MAILUSERNAME"), constant("NOMBRE_EMPRESA"));
			$mail->FromName = constant("NOMBRE_EMPRESA");
		}else{
			$mail->AddReplyTo($cEmpresa->getMail(), $cEmpresa->getNombre());
			$mail->FromName = $cEmpresa->getNombre();
		}

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
		if(!$exito){
			$sTypeError	=	date('d/m/Y H:i:s') . "BLIND Problemas enviando correo electrónico FROM::[" . $mail->From . "] TO::[" . $cCandidato->getMail() . "]";
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
