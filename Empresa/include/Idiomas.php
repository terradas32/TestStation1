<?php
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
	require_once("Configuracion.php");
}
?>
<?php
if (empty($_REQUEST['fLang'])){
	//Si biene vacio, asignamos el lenguaje del usuario, sino el defecto

	$sIdiomaUsr = (!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) : "";
	$sLang = (!empty($sIdiomaUsr)) ? $sIdiomaUsr : constant("LENGUAJEDEFECTO");
}else{
	$sLang = $_REQUEST['fLang'];
}
if (!empty($sLang)){
    if (file_exists(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . strtolower($sLang) . ".php" )){
	   require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . strtolower($sLang) . ".php");
	   $sLang = strtolower($sLang);
	}else{
        require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . constant("LENGUAJEDEFECTO") . ".php");
        $sLang = constant("LENGUAJEDEFECTO");
    }
}else{
    require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_LANG") . constant("LENGUAJEDEFECTO") . ".php");
    $sLang = constant("LENGUAJEDEFECTO");
}
if ($sLang != "es"){
	//Formato de fecha de usuario
	$_USR_FECHA = "Y/m/d";
	$_USR_SEPARADORMILES = ",";
	$_USR_SEPARADORDECIMAL = ".";
}
$_REQUEST['lang']=$sLang;
?>
