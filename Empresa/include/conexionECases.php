<?php
//$DBType = 'pdo_mysql';
$DBType = 'mysqli';


$DBServer = constant("DB_HOST_ECASES"); // server name or IP address
$DBUser = constant("DB_USUARIO_ECASES");
$DBPass = constant("DB_PASSWORD_ECASES");
$DBName = constant("DB_DATOS_ECASES");
// 1=ADODB_FETCH_NUM, 2=ADODB_FETCH_ASSOC, 3=ADODB_FETCH_BOTH
$dsn_options='?persist=0&fetchmode=2';

$dsn = "$DBType://$DBUser:$DBPass@$DBServer/$DBName$dsn_options";
//echo $dsn;
if (!empty($DBUser)){

$connECases = NewADOConnection($dsn);

$connECases->Execute("SET NAMES utf8");
// $connECases->debug = true;
}
?>
