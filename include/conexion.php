<?php
//$DBType = 'pdo_mysql';
$DBType = 'mysqli';


$DBServer = constant("DB_HOST"); // server name or IP address
$DBUser = constant("DB_USUARIO");
$DBPass = rawurlencode(constant("DB_PASSWORD"));
$DBName = constant("DB_DATOS");

// 1=ADODB_FETCH_NUM, 2=ADODB_FETCH_ASSOC, 3=ADODB_FETCH_BOTH
$DBPort = 3306;
$dsn_options='?port='.$DBPort.'&persist=0&fetchmode=2';

$dsn = "$DBType://$DBUser:$DBPass@$DBServer/$DBName$dsn_options";
//echo $dsn;
$conn = NewADOConnection($dsn);
$conn->Execute ( "SET NAMES utf8" );
// $conn->debug = true;
?>
