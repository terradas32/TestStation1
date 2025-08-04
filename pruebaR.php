<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');


require_once('include/Configuracion.php');

define('ADODB_ASSOC_CASE', 2); # No cambiar las letras para ADODB_FETCH_ASSOC
include(constant("DIR_ADODB") . 'adodb.inc.php');

include_once ('include/conexion.php');

$sql = "SELECT * FROM users_pedro";
$sPass = password_hash("mayo2018", PASSWORD_BCRYPT);
$sTK=quickRandom(60);
echo "<br />Pass: " . $sPass;
echo "<br />sTK: " . $sTK;
exit;
$lista = $conn->Execute($sql);
echo "<br />Iniciado UPDATE";
		while (!$lista->EOF)
		{
			$sPass = password_hash($lista->fields['email'], PASSWORD_BCRYPT);
			$sTK=quickRandom(60);
			$sSQL= "UPDATE users_pedro set password='" . $sPass . "' , ";
			$sSQL.="remember_token='" . $sTK . "' ";
			$sSQL.="WHERE id='" . $lista->fields['id'] . "' ";

			//echo "<br />" . $sSQL;
			if($conn->Execute($sSQL) === false){

				$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [" . constant("MNT_MODIFICAR") . "]";
				echo "<br />" . $sTypeError;
			}

			$lista->MoveNext();
		}
echo "<br />Finalizado UPDATE";
function quickRandom($length = 16)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}
?>
