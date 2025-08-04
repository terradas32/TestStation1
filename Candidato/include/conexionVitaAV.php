<?php
//$DBType = 'pdo_mysql';
$DBType = 'mysqli';


$DBServer = constant("DB_HOST_AV"); // server name or IP address
$DBUser = constant("DB_USUARIO_AV");
$DBPass = constant("DB_PASSWORD_AV");
$DBName = constant("DB_DATOS_AV");
// 1=ADODB_FETCH_NUM, 2=ADODB_FETCH_ASSOC, 3=ADODB_FETCH_BOTH
$DBPort = 3306;
$dsn_options='?port='.$DBPort.'&persist=0&fetchmode=2';

$dsn = "$DBType://$DBUser:$DBPass@$DBServer/$DBName$dsn_options";
//echo $dsn;
$connAV = NewADOConnection($dsn);

 $connAV->Execute ( "SET NAMES utf8" );
// $connAV->debug = true;
?>
