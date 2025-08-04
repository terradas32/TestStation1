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
/////
echo "<br>INICIO" . date("Y-m-d H:m:s");
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'responsabilidades.php','405','UPDATE candidatos SET idCandidato=\\'24000\\', idEmpresa=\\'5850\\', idProceso=\\'1\\', nombre=\\'.\\', apellido1=\\'.\\', apellido2=\\'\\', dni=\\'\\', mail=\\'1b4740a7667a5beb81@questionary.com\\', idTratamiento=\\'\\', idSexo=\\'0\\', idEdad=\\'8\\', fechaNacimiento=null,idPais=\\'\\', idProvincia=\\'\\', idMunicipio=\\'\\', idZona=\\'\\', direccion=\\'\\', codPostal=\\'\\', idFormacion=\\'0\\', idNivel=\\'0\\', idArea=\\'11\\', telefono=\\'\\', codIso2PaisProcedencia=\\'\\', estadoCivil=\\'\\', nacionalidad=\\'\\', sectorMB=NULL, concesionMB=NULL, baseMB=NULL, puestoEvaluar=NULL, responsableDirecto=NULL, categoriaForjanor=NULL, especialidadMB=NULL, informado=\\'1\\',finalizado=\\'0\\', fecMod=NOW(),usuMod=\\'0\\'  WHERE idCandidato=\\'24000\\' AND idEmpresa=\\'5850\\' AND idProceso=\\'1\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'pruebas.php','405','UPDATE candidatos SET idCandidato=\\'24000\\', idEmpresa=\\'5850\\', idProceso=\\'1\\', nombre=\\'.\\', apellido1=\\'.\\', apellido2=\\'\\', dni=\\'\\', mail=\\'1b4740a7667a5beb81@questionary.com\\', idTratamiento=\\'\\', idSexo=\\'0\\', idEdad=\\'8\\', fechaNacimiento=null,idPais=\\'\\', idProvincia=\\'\\', idMunicipio=\\'\\', idZona=\\'\\', direccion=\\'\\', codPostal=\\'\\', idFormacion=\\'0\\', idNivel=\\'0\\', idArea=\\'11\\', telefono=\\'\\', codIso2PaisProcedencia=\\'\\', estadoCivil=\\'\\', nacionalidad=\\'\\', sectorMB=NULL, concesionMB=NULL, baseMB=NULL, puestoEvaluar=NULL, responsableDirecto=NULL, categoriaForjanor=NULL, especialidadMB=NULL, informado=\\'1\\',finalizado=\\'0\\', fecMod=NOW(),usuMod=\\'0\\'  WHERE idCandidato=\\'24000\\' AND idEmpresa=\\'5850\\' AND idProceso=\\'1\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='40'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='41'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='42'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='5', segundos_test='50', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'5\\', segundos_test=\\'50\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='5', segundos_test='55', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'5\\', segundos_test=\\'55\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='6', segundos_test='0', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'6\\', segundos_test=\\'0\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='6', segundos_test='5', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'6\\', segundos_test=\\'5\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='40'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='40'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','DELETE FROM respuestas_pruebas_items WHERE  idEmpresa=\\'5850\\'  AND idProceso=\\'1\\'  AND idCandidato=\\'24000\\'  AND codIdiomaIso2=\\'es\\'  AND idPrueba=\\'24\\'  AND idItem=\\'40\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'','')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT COUNT(idEmpresa) AS Max FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='40' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE from respuestas_pruebas_items where idEmpresa=5850 and idProceso=1 AND idCandidato=24000 AND codIdiomaIso2='es' and idPrueba='24' and idItem=40 ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES ('5850','CAIXABANK-BANKIA','1','Cuestionario de competencias Caixabank - Bankia ES','24000','. . (1b4740a7667a5beb81@questionary.com)','es','Español','24','PRISM@','40','Me muestro pacífico en la convivencia.','0','','','40','',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES (\\'5850\\',\\'CAIXABANK-BANKIA\\',\\'1\\',\\'Cuestionario de competencias Caixabank - Bankia ES\\',\\'24000\\',\\'. . (1b4740a7667a5beb81@questionary.com)\\',\\'es\\',\\'Español\\',\\'24\\',\\'PRISM@\\',\\'40\\',\\'Me muestro pacífico en la convivencia.\\',\\'0\\',\\'\\',\\'\\',\\'40\\',\\'\\',NOW(),NOW(),\\'\\',\\'\\')','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'','')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='41' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='41'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','DELETE FROM respuestas_pruebas_items WHERE  idEmpresa=\\'5850\\'  AND idProceso=\\'1\\'  AND idCandidato=\\'24000\\'  AND codIdiomaIso2=\\'es\\'  AND idPrueba=\\'24\\'  AND idItem=\\'41\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'','')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT COUNT(idEmpresa) AS Max FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='41' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='41'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES ('5850','CAIXABANK-BANKIA','1','Cuestionario de competencias Caixabank - Bankia ES','24000','. . (1b4740a7667a5beb81@questionary.com)','es','Español','24','PRISM@','41','Soy eficaz analizando datos.','1','Mejor','','41','',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES (\\'5850\\',\\'CAIXABANK-BANKIA\\',\\'1\\',\\'Cuestionario de competencias Caixabank - Bankia ES\\',\\'24000\\',\\'. . (1b4740a7667a5beb81@questionary.com)\\',\\'es\\',\\'Español\\',\\'24\\',\\'PRISM@\\',\\'41\\',\\'Soy eficaz analizando datos.\\',\\'1\\',\\'Mejor\\',\\'\\',\\'41\\',\\'\\',NOW(),NOW(),\\'\\',\\'\\')','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'','')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='42' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='42'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','DELETE FROM respuestas_pruebas_items WHERE  idEmpresa=\\'5850\\'  AND idProceso=\\'1\\'  AND idCandidato=\\'24000\\'  AND codIdiomaIso2=\\'es\\'  AND idPrueba=\\'24\\'  AND idItem=\\'42\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'','')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT COUNT(idEmpresa) AS Max FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='42' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='42'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES ('5850','CAIXABANK-BANKIA','1','Cuestionario de competencias Caixabank - Bankia ES','24000','. . (1b4740a7667a5beb81@questionary.com)','es','Español','24','PRISM@','42','Digo claramente lo que pienso.','0','','','42','',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES (\\'5850\\',\\'CAIXABANK-BANKIA\\',\\'1\\',\\'Cuestionario de competencias Caixabank - Bankia ES\\',\\'24000\\',\\'. . (1b4740a7667a5beb81@questionary.com)\\',\\'es\\',\\'Español\\',\\'24\\',\\'PRISM@\\',\\'42\\',\\'Digo claramente lo que pienso.\\',\\'0\\',\\'\\',\\'\\',\\'42\\',\\'\\',NOW(),NOW(),\\'\\',\\'\\')','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='6', segundos_test='10', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'6\\', segundos_test=\\'10\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='40'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='41'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='42'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='42' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','DELETE FROM respuestas_pruebas_items WHERE  idEmpresa=\\'5850\\'  AND idProceso=\\'1\\'  AND idCandidato=\\'24000\\'  AND codIdiomaIso2=\\'es\\'  AND idPrueba=\\'24\\'  AND idItem=\\'42\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT COUNT(idEmpresa) AS Max FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='42' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "DELETE FROM respuestas_pruebas_items WHERE  idEmpresa='5850'  AND idProceso='1'  AND idCandidato='24000'  AND codIdiomaIso2='es'  AND idPrueba='24'  AND idItem='42'";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES ('5850','CAIXABANK-BANKIA','1','Cuestionario de competencias Caixabank - Bankia ES','24000','. . (1b4740a7667a5beb81@questionary.com)','es','Español','24','PRISM@','42','Digo claramente lo que pienso.','2','Peor','','42','',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardarespuestatipo7.php','405','INSERT INTO respuestas_pruebas_items (idEmpresa,descEmpresa,idProceso,descProceso,idCandidato,descCandidato,codIdiomaIso2,descIdiomaIso2,idPrueba,descPrueba,idItem,descItem,idOpcion,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod) VALUES (\\'5850\\',\\'CAIXABANK-BANKIA\\',\\'1\\',\\'Cuestionario de competencias Caixabank - Bankia ES\\',\\'24000\\',\\'. . (1b4740a7667a5beb81@questionary.com)\\',\\'es\\',\\'Español\\',\\'24\\',\\'PRISM@\\',\\'42\\',\\'Digo claramente lo que pienso.\\',\\'2\\',\\'Peor\\',\\'\\',\\'42\\',\\'\\',NOW(),NOW(),\\'0\\',\\'0\\')','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='6', segundos_test='15', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'6\\', segundos_test=\\'15\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='43' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='44' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "SELECT *  FROM respuestas_pruebas_items WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' AND idItem='45' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "UPDATE respuestas_pruebas SET idEmpresa='5850', descEmpresa='CAIXABANK-BANKIA', idProceso='1', descProceso='Cuestionario de competencias Caixabank - Bankia ES', idCandidato='24000', descCandidato='. . (1b4740a7667a5beb81@questionary.com)', codIdiomaIso2='es', descIdiomaIso2='Español', idPrueba='24', descPrueba='PRISM@', finalizado='0', leidoInstrucciones='1', leidoEjemplos='1', minutos_test='6', segundos_test='20', minutos2_test='', segundos2_test='', pantalla='0', campoLibre='', fecMod=NOW(),usuMod='0' WHERE idEmpresa='5850' AND idProceso='1' AND idCandidato='24000' AND codIdiomaIso2='es' AND idPrueba='24' ";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
$sql = "INSERT INTO wi_historico_cambios (fecCambio,funcionalidad,modo,query,ip,idUsuario,idUsuarioTipo,login,nombre,apellido1,apellido2,email,fecAlta,fecMod,usuAlta,usuMod) VALUES (NOW(),'guardatiempo.php','405','UPDATE respuestas_pruebas SET idEmpresa=\\'5850\\', descEmpresa=\\'CAIXABANK-BANKIA\\', idProceso=\\'1\\', descProceso=\\'Cuestionario de competencias Caixabank - Bankia ES\\', idCandidato=\\'24000\\', descCandidato=\\'. . (1b4740a7667a5beb81@questionary.com)\\', codIdiomaIso2=\\'es\\', descIdiomaIso2=\\'Español\\', idPrueba=\\'24\\', descPrueba=\\'PRISM@\\', finalizado=\\'0\\', leidoInstrucciones=\\'1\\', leidoEjemplos=\\'1\\', minutos_test=\\'6\\', segundos_test=\\'20\\', minutos2_test=\\'\\', segundos2_test=\\'\\', pantalla=\\'0\\', campoLibre=\\'\\', fecMod=NOW(),usuMod=\\'0\\' WHERE idEmpresa=\\'5850\\' AND idProceso=\\'1\\' AND idCandidato=\\'24000\\' AND codIdiomaIso2=\\'es\\' AND idPrueba=\\'24\\' ','139.47.106.220','0','0','Admin','Administrador','Sistema','','pbm@people-experts.com',NOW(),NOW(),'0','0')";
if($conn->Execute($sql) === false){

	$sTypeError	=	date('d/m/Y H:i:s') . " Error SQL [][" . $sql . "]";
	echo "<br />" . $sTypeError;
	exit;
}
echo "<br>FIN" . date("Y-m-d H:m:s");

?>
