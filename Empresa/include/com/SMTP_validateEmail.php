<?php
/**
* Validar direcciones de correo electr�nico a trav�s de SMTP
* Esta consulta al servidor SMTP para ver si la direcci�n de correo electr�nico es aceptado.
* Http://creativecommons.org/licenses/by/2.0/ autor @ - Por favor, mantenga este comentario intacta
* @ Gabe@fijiwebdesign.com autor
* @ Contribuyentes adnan@barakatdesigns.net
* @ Versi�n 0.1a
*/
class SMTP_validateEmail {

	/**
	* PHP Socket de recursos para MTA remoto
	* @var recursos $sock
	*/
	var $sock;

	/**
	* El usuario actual se va a validar
	*/
	var $user;
	/**
	* Dominio actual donde el usuario se est� validando
	*/
	var $domain;
	/**
	* Lista de dominios para validar usuarios
	*/
	var $domains;
	/**
	* Puerto SMTP
	*/
	var $port = 25;
	/**
	* El tiempo m�ximo de conexi�n a un MTA
	*/
	var $max_conn_time = 30;
	/**
	* El tiempo m�ximo de lectura para el socket
	*/
	var $max_read_time = 5;

	/**
	* nombre de usuario del remitente
	*/
	var $from_user = 'user';
	/**
	* Nombre de host del remitente
	*/
	var $from_domain = 'localhost';

	/**
	* Servidores de nombres a utilizar al hacer consultas DNS de las entradas MX
	* @var Array $nameservers
	*/
	var $nameservers = array(
	'192.168.0.1'
	);

	var $debug = false;

	/**
	* Inicializa la clase
	* @return Instancia SMTP_validateEmail
	* @param $email Array[optional] Lista [opcional] de mensajes de correo electr�nico para validar
	* @param $sender String[optional] Email del validador
	*/
	function __construct($emails = false, $sender = false) {
		if ($emails) {
			$this->setEmails($emails);
		}
		if ($sender) {
			$this->setSenderEmail($sender);
		}
	}

	function _parseEmail($email) {
		$parts = explode('@', $email);
		$domain = array_pop($parts);
		$user= implode('@', $parts);
		return array($user, $domain);
	}

	/**
	* Definici�n de los correos electr�nicos para validar
	* @param $emails Array Lista de correos electr�nicos
	*/
	function setEmails($emails) {
		foreach($emails as $email) {
			list($user, $domain) = $this->_parseEmail($email);
			if (!isset($this->domains[$domain])) {
				$this->domains[$domain] = array();
			}
			$this->domains[$domain][] = $user;
		}
	}

	/**
	* Establecer el correo electr�nico del remitente / validador
	* @param $email String
	*/
	function setSenderEmail($email) {
		$parts = $this->_parseEmail($email);
		$this->from_user = $parts[0];
		$this->from_domain = $parts[1];
	}

	/**
	* Validar direcciones de correo electr�nico
	* @param String $emails Emails para validar (recipient emails)
	* @param String $sender Sender's Email
	* @return Array Asociativi Lista de emails ys sus resultados de validaci�n
	*/
	function validate($emails = false, $sender = false) {

		$results = array();

		if ($emails) {
		$this->setEmails($emails);
		}
		if ($sender) {
		$this->setSenderEmail($sender);
		}

		// query the MTAs on each Domain
		foreach($this->domains as $domain=>$users)
		{
			$mxs = array();

			// rRecuperar el servidor SMTP a trav�s de consulta MX en el dominio
			list($hosts, $mxweights) = $this->queryMX($domain);

			// Recuperar las prioridades MX
			for($n=0; $n < count($hosts); $n++){
				$mxs[$hosts[$n]] = $mxweights[$n];
			}
			asort($mxs);

			// last fallback is the original domain
			array_push($mxs, $this->domain);

			$this->debug(print_r($mxs, 1));

			$timeout = $this->max_conn_time/count($hosts);

			// Tratar cada host
			while(list($host) = each($mxs)) {
				// Conectar con el servidor SMTP
				$this->debug("try $host:$this->port\n");
				if ($this->sock = fsockopen($host, $this->port, $errno, $errstr, (float) $timeout)) {
					stream_set_timeout($this->sock, $this->max_read_time);
					break;
				}
			}

			// did we get a TCP socket
			if ($this->sock) {
				$reply = fread($this->sock, 2082);
				$this->debug("<<<\n$reply");

				preg_match('/^([0-9]{3}) /ims', $reply, $matches);
				$code = isset($matches[1]) ? $matches[1] : '';

				if($code != '220') {
					// MTA dio un error...
					foreach($users as $user) {
						$results[$user.'@'.$domain] = false;
					}
					continue;  
				}

				// Decir helo
				$this->send("HELO ".$this->from_domain);
				// Hablando de remitente
				$this->send("MAIL FROM: <".$this->from_user.'@'.$this->from_domain.">");

				// Pregunta para cada destinatario en este dominio
				foreach($users as $user) {

					// Pregunta por receptpres
					$reply = $this->send("RCPT TO: <".$user.'@'.$domain.">");

					// Obtener el c�digo y msg de la respuesta
					preg_match('/^([0-9]{3}) /ims', $reply, $matches);
					$code = isset($matches[1]) ? $matches[1] : '';

					if ($code == '250') {
						// Se recibieron 250 por lo que la direcci�n de correo electr�nico fue aceptada
						$results[$user.'@'.$domain] = true;
					} elseif ($code == '451' || $code == '452') {
						// Recibi� 451 por lo que la direcci�n de correo electr�nico estaba en una lista gris (o alg�n error temporal se produjo en el MTA) - se asume que est� ok
						$results[$user.'@'.$domain] = true;
					} else {
						$results[$user.'@'.$domain] = false;
					}
				}
				// salir
				$this->send("quit");
				// cerrar socket
				fclose($this->sock);

			}
		}
		return $results;
	}


	function send($msg) {
		fwrite($this->sock, $msg."\r\n");
		$reply = fread($this->sock, 2082);
		$this->debug(">>>\n$msg\n");
		$this->debug("<<<\n$reply");
		return $reply;
	}

	/**
	* Consulta DNS del servidor para las entradas MX
	* @return
	*/
	function queryMX($domain) {
		$hosts = array();
		$mxweights = array();
		if (function_exists('getmxrr')) {
			getmxrr($domain, $hosts, $mxweights);
		} else {
			// windows, necesita Net_DNS
			require_once 'Net/DNS.php';

			$resolver = new Net_DNS_Resolver();
			$resolver->debug = $this->debug;
			// servidores de nombre para consultar
			$resolver->nameservers = $this->nameservers;
			$resp = $resolver->query($domain, 'MX');
			if ($resp) {
				foreach($resp->answer as $answer) {
					$hosts[] = $answer->exchange;
					$mxweights[] = $answer->preference;
				}
			}

		}
		return array($hosts, $mxweights);
	}

	/**
	* Funci�n simple para replicar el comportamiento de PHP 5. http://php.net/microtime
	*/
	function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function debug($str) {
		if ($this->debug) {
			echo htmlentities($str);
		}
	}
}
?>
