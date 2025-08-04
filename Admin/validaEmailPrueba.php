<?php

// El correo electr�nico para validar  
$email = 'joe@gmail.com';  
//Un remitente opcional
$sender = 'user@example.com';  
// Instancia de la clase 
$SMTP_Valid = new SMTP_validateEmail();  
// Hacer la validaci�n
$result = $SMTP_Valid->validate($email, $sender);  
//Ver los resultados 
var_dump($result);  
echo $email.' is '.($result ? 'valido' : 'invalido')."\n";  
  
// Enviar por correo electr�nico? 
if ($result) {  
  //mail(...);  
}

?>  