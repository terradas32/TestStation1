<<<<<<< HEAD
<?php
	$_HTTP_HOST = (empty($_SERVER["HTTP_HOST"])) ? "local.teststation.com/teststation/" : $_SERVER["HTTP_HOST"];
	$_DOCUMENT_ROOT = (empty($_SERVER["DOCUMENT_ROOT"])) ? "C:\\xampp56\\htdocs\\teststation" : $_SERVER["DOCUMENT_ROOT"];

	// Define the webserver and path parameters
	// * DIR_FS_* Directorio físico (local/physical)
	// * DIR_WS_* Directorio del servidor Web (virtual/URL)
	define("HTTP_SERVER", "https://" . $_HTTP_HOST . "/Candidato/");	//Siempre terminar en "/"
	define("HTTPS_SERVER", "https://" . $_HTTP_HOST . "/Candidato/");	//Siempre terminar en "/"
	define("HTTP_SERVER_FRONT", "https://" . $_HTTP_HOST . "/");	//Siempre terminar en "/"
	define("DIR_WS_GESTOR", "https://" . $_HTTP_HOST . "/Admin/");
	define("DIR_ADODB", $_DOCUMENT_ROOT . "/adodb/");
	define("DIR_WS_GRAF", "graf/");
	define("DIR_WS_INCLUDE", "include/");
	define("DIR_WS_COM", constant("DIR_WS_INCLUDE") . "com/");
	define("DIR_WS_PLANTILLAS", constant("DIR_WS_INCLUDE") . "plantillas/");
	define("DIR_WS_LANG", constant("DIR_WS_INCLUDE") . "lang/");
	define("DIR_WS_AYUDA", "ayuda/");
	define("DIR_WS_COMBOS", "Template/");

	//API Key for ipinfodb.com
	define("API_KEY_IPINFODB", "e4e38b6fac80c9b973bf4883a0d52586ac336169d319018fb548f76c6173370a");
	//API Key for ipinfodb.com
	define("USER_ID_NEUTRINOAPI", "pborregan");
	define("API_KEY_NEUTRINOAPI", "IVpJB3gz8MOADdgrRg4eCAPlKWiSbO1ud95TL6o5hKuB3cEu");



	//Generación gráfica Libreria JPGRAPH
	define("DIR_WS_JPGRAPH", constant("DIR_WS_COM") . "jpgraph/");
	//Generación PDFLibreria HTML2PS
	define("HTML2PS_DIR", constant("DIR_WS_COM") . "html2ps_v2043/public_html/");

	//Formato de fecha de usuario
	define("USR_FECHA", "d/m/Y");
	define("USR_SEPARADORMILES", ".");
	define("USR_SEPARADORDECIMAL", ",");
	define("USR_NUMDECIMALES", "2");

	//Directorios físicos
	define("DIR_FS_DOCUMENT_ROOT", $_DOCUMENT_ROOT . "/Candidato/"); //Siempre terminar en "/"
	define("DIR_FS_DOCUMENT_ROOT_ADMIN", $_DOCUMENT_ROOT . "/Admin/"); //Siempre terminar en "/"
	//Fichero de log
	define("DIR_FS_PATH_NAME_LOG", constant("DIR_FS_DOCUMENT_ROOT") . "errores.log");
	define("DIR_FS_PATH_NAME_CORREO", constant("DIR_FS_DOCUMENT_ROOT") . "correos.log");
	//Fichero de log de la pasarela TPV
	define("DIR_FS_PATH_NAME_LOG_TPV", constant("DIR_FS_DOCUMENT_ROOT") . "logTPV.log");

	//Ejecutable PHP
	define("DIR_FS_PATH_PHP", 'C:\Datos\xampp\php\php.exe');
// PRODUCCION	define("DIR_FS_PATH_PHP", 'C:\Program Files (x86)\Parallels\Plesk\Additional\PleskPHP5\php.exe');

	define("MB",			1024);	//Tamaño de 1 MB == 1024
	define("CHAR_SEPARA"	,"§");	//Carácter separador de varios valores para contruir arrays.

	//Nombre del site
	define("NOMBRE_SITE", "Test Station");
	define("NOMBRE_EMPRESA", "People Experts");

    //Tiempo de inactividad de sesión en minutos
	define("TIMEOUT_SESION", "60");
	//Nombre de SESSION
	define("NOMBRE_SESSION", "Intranettest-station");

	//Constantes de control de flujo.
	define("MNT_INICIO",0);
	define("MNT_NUEVO",184);
	define("MNT_ALTA",405);
	define("MNT_MODIFICAR",323);
	define("MNT_BORRAR",411);
	define("MNT_CONSULTAR",131);
	define("MNT_LISTAR",564);
	define("MNT_BUSCAR",111);
	define("MNT_AGREGAPRUEBA",611);
	define("MNT_LISTAIDIOMAS",612);
	define("MNT_LISTABAREMOS",613);
	define("MNT_ANIADEBAREMO",614);
	define("MNT_LISTAPUNTBAREMOS",615);
	define("MNT_ANIADECANDIDATOS",616);
	define("MNT_LISTACANDIDATOS",617);
	define("MNT_CARGACANDIDATOS",618);
	define("MNT_ESCOGECORREOS",619);
	define("MNT_NUEVOCORREO",620);
	define("MNT_LISTACORREOS",621);
	define("MNT_GUARDAPLANTILLA",621);
	//Lineas por Página en los listados
	define("CNF_LINEAS_PAGINA",20);

	//Color de fondo de la página
	define("BG_COLOR","White");

	//Colores para los TR de listas
	define("ONMOUSEOVER","White");
	define("ONMOUSEOUT","#E1E1E1");//Se utiliza tambien para el bgcolor por defecto del TR
	define("ONMOUSEOVERSUB","#ff9c33");
	define("ONMOUSEOUTSUB","#C1E9FA");

	//Plantilla con color de fondo.
	define("FONDO_PLANTILLA","#CCCCCC");

	//SW para determinar si se pinta el icono de ayuda. 1 - sí, 0 - No.
	define("B_AYUDA","1");

	//Firma por defecto.
	define("DEFAULT_FIRMA","Azul Pomodoro");

	//Datos para la clase de envio de correo
	define("MAILER","smtp"); //PUEDE SER mail O smtp
    //Host, nombre de servidor smtp
    define("HOSTMAIL","mail.psicologosempresariales.es");
    define("PORTMAIL","587");    //25 o 587 normalmente
    //usuario y password PARA EL ENVIO DE CORREO, TIENE Q SER UNA CUENTA VÁLIDA
    define("MAILUSERNAME","PEASA@psicologosempresariales.es");
    define("MAILPASSWORD","He1BdP34s4");
	//Email de contacto.
	define("EMAIL_CONTACTO","PEASA@psicologosempresariales.es");
	define("PERSONA_CONTACTO","Contacta");

	//Espacio de izquierda,contenido y derecha.
	define("SP_IZQUIERDA","26");
	define("SP_CONTENIDO","498");
	define("SP_DERECHA","1");
	//espacio de la columna de separación entre izquierda, contenido.
	define("SP_COLUMNA1","37");
	//espacio de la columna de separación entre contenido derecha.
	define("SP_COLUMNA2","28");

	//Directorios para calculo de espacio consumido.
	//define("_DIR_CONSUMO", constant("DIR_FS_DOCUMENT_ROOT") . ",/cgis,/statistics/logs,/tmp");
	define("_DIR_CONSUMO", "");
	define("_QUOTA",25); //Cuota en MB de espacio en disco.

	define("LENGUAJEDEFECTO", "es");

	define("EMPRESA_PE", "3788");	//Código de la empresa Psicólogos Empresariales

	//Contantes de conexión a Base de Datos
	define("DB_TYPE", "mysql");
	define("DB_DATOS", "test-station7");
	define("DB_HOST", "localhost");
	define("DB_USUARIO", "test-station7");
	define("DB_PASSWORD","uMf82o7$");
	//FIN Contantes de conexión a Base de Datos

	//Contantes de conexión a plataforma e-Cases
	define("HTTP_SERVER_ECASES", "https://e-cases.test:543/auth/login");
	define("DB_DATOS_ECASES", "c2ecase");
	define("DB_HOST_ECASES", "www.ecases-pe.com");
	define("DB_USUARIO_ECASES", "c2ecase");
	define("DB_PASSWORD_ECASES","GvYx2JsbV@c");
	//FIN Contantes de conexión a plataforma e-Cases

	//Contantes de conexión a plataforma VITA AV
	define("HTTP_SERVER_AV", "https://assessment.people-experts.com/hash/auth/");
	define("DB_DATOS_AV", "as");
	define("DB_HOST_AV", "assessment.people-experts.com");
	define("DB_USUARIO_AV", "assessment");
	define("DB_PASSWORD_AV","wS3cg1_8");
	//FIN Contantes de conexión a plataforma e-Cases
?>
=======
<?php
	$_HTTP_HOST = (empty($_SERVER["HTTP_HOST"])) ? "local.teststation.com/teststation/" : $_SERVER["HTTP_HOST"];
	$_DOCUMENT_ROOT = (empty($_SERVER["DOCUMENT_ROOT"])) ? "C:\\xampp56\\htdocs\\teststation" : $_SERVER["DOCUMENT_ROOT"];

	// Define the webserver and path parameters
	// * DIR_FS_* Directorio físico (local/physical)
	// * DIR_WS_* Directorio del servidor Web (virtual/URL)
	define("HTTP_SERVER", "http://" . $_HTTP_HOST . "/Candidato/");	//Siempre terminar en "/"
	define("HTTPS_SERVER", "https://" . $_HTTP_HOST . "/Candidato/");	//Siempre terminar en "/"
	define("HTTP_SERVER_FRONT", "http://" . $_HTTP_HOST . "/");	//Siempre terminar en "/"
	define("DIR_WS_GESTOR", "http://" . $_HTTP_HOST . "/Admin/");
	define("DIR_ADODB", $_DOCUMENT_ROOT . "/adodb/");
	define("DIR_WS_GRAF", "graf/");
	define("DIR_WS_INCLUDE", "include/");
	define("DIR_WS_COM", constant("DIR_WS_INCLUDE") . "com/");
	define("DIR_WS_PLANTILLAS", constant("DIR_WS_INCLUDE") . "plantillas/");
	define("DIR_WS_LANG", constant("DIR_WS_INCLUDE") . "lang/");
	define("DIR_WS_AYUDA", "ayuda/");
	define("DIR_WS_COMBOS", "Template/");

	//API Key for ipinfodb.com
	define("API_KEY_IPINFODB", "e4e38b6fac80c9b973bf4883a0d52586ac336169d319018fb548f76c6173370a");
	//API Key for ipinfodb.com
	define("USER_ID_NEUTRINOAPI", "pborregan");
	define("API_KEY_NEUTRINOAPI", "IVpJB3gz8MOADdgrRg4eCAPlKWiSbO1ud95TL6o5hKuB3cEu");



	//Generación gráfica Libreria JPGRAPH
	define("DIR_WS_JPGRAPH", constant("DIR_WS_COM") . "jpgraph/");
	//Generación PDFLibreria HTML2PS
	define("HTML2PS_DIR", constant("DIR_WS_COM") . "html2ps_v2043/public_html/");

	//Formato de fecha de usuario
	define("USR_FECHA", "d/m/Y");
	define("USR_SEPARADORMILES", ".");
	define("USR_SEPARADORDECIMAL", ",");
	define("USR_NUMDECIMALES", "2");

	//Directorios físicos
	define("DIR_FS_DOCUMENT_ROOT", $_DOCUMENT_ROOT . "/Candidato/"); //Siempre terminar en "/"
	define("DIR_FS_DOCUMENT_ROOT_ADMIN", $_DOCUMENT_ROOT . "/Admin/"); //Siempre terminar en "/"
	//Fichero de log
	define("DIR_FS_PATH_NAME_LOG", constant("DIR_FS_DOCUMENT_ROOT") . "errores.log");
	define("DIR_FS_PATH_NAME_CORREO", constant("DIR_FS_DOCUMENT_ROOT") . "correos.log");
	//Fichero de log de la pasarela TPV
	define("DIR_FS_PATH_NAME_LOG_TPV", constant("DIR_FS_DOCUMENT_ROOT") . "logTPV.log");

	//Ejecutable PHP
	define("DIR_FS_PATH_PHP", 'C:\Datos\xampp\php\php.exe');
// PRODUCCION	define("DIR_FS_PATH_PHP", 'C:\Program Files (x86)\Parallels\Plesk\Additional\PleskPHP5\php.exe');

	define("MB",			1024);	//Tamaño de 1 MB == 1024
	define("CHAR_SEPARA"	,"§");	//Carácter separador de varios valores para contruir arrays.

	//Nombre del site
	define("NOMBRE_SITE", "Test Station");
	define("NOMBRE_EMPRESA", "People Experts");

    //Tiempo de inactividad de sesión en minutos
	define("TIMEOUT_SESION", "60");
	//Nombre de SESSION
	define("NOMBRE_SESSION", "Intranettest-station");

	//Constantes de control de flujo.
	define("MNT_INICIO",0);
	define("MNT_NUEVO",184);
	define("MNT_ALTA",405);
	define("MNT_MODIFICAR",323);
	define("MNT_BORRAR",411);
	define("MNT_CONSULTAR",131);
	define("MNT_LISTAR",564);
	define("MNT_BUSCAR",111);
	define("MNT_AGREGAPRUEBA",611);
	define("MNT_LISTAIDIOMAS",612);
	define("MNT_LISTABAREMOS",613);
	define("MNT_ANIADEBAREMO",614);
	define("MNT_LISTAPUNTBAREMOS",615);
	define("MNT_ANIADECANDIDATOS",616);
	define("MNT_LISTACANDIDATOS",617);
	define("MNT_CARGACANDIDATOS",618);
	define("MNT_ESCOGECORREOS",619);
	define("MNT_NUEVOCORREO",620);
	define("MNT_LISTACORREOS",621);
	define("MNT_GUARDAPLANTILLA",621);
	//Lineas por Página en los listados
	define("CNF_LINEAS_PAGINA",20);

	//Color de fondo de la página
	define("BG_COLOR","White");

	//Colores para los TR de listas
	define("ONMOUSEOVER","White");
	define("ONMOUSEOUT","#E1E1E1");//Se utiliza tambien para el bgcolor por defecto del TR
	define("ONMOUSEOVERSUB","#ff9c33");
	define("ONMOUSEOUTSUB","#C1E9FA");

	//Plantilla con color de fondo.
	define("FONDO_PLANTILLA","#CCCCCC");

	//SW para determinar si se pinta el icono de ayuda. 1 - sí, 0 - No.
	define("B_AYUDA","1");

	//Firma por defecto.
	define("DEFAULT_FIRMA","Azul Pomodoro");

	//Datos para la clase de envio de correo
	define("MAILER","smtp"); //PUEDE SER mail O smtp
    //Host, nombre de servidor smtp
    define("HOSTMAIL","mail.psicologosempresariales.es");
    define("PORTMAIL","587");    //25 o 587 normalmente
    //usuario y password PARA EL ENVIO DE CORREO, TIENE Q SER UNA CUENTA VÁLIDA
    define("MAILUSERNAME","PEASA@psicologosempresariales.es");
    define("MAILPASSWORD","He1BdP34s4");
	//Email de contacto.
	define("EMAIL_CONTACTO","PEASA@psicologosempresariales.es");
	define("PERSONA_CONTACTO","Contacta");

	//Espacio de izquierda,contenido y derecha.
	define("SP_IZQUIERDA","26");
	define("SP_CONTENIDO","498");
	define("SP_DERECHA","1");
	//espacio de la columna de separación entre izquierda, contenido.
	define("SP_COLUMNA1","37");
	//espacio de la columna de separación entre contenido derecha.
	define("SP_COLUMNA2","28");

	//Directorios para calculo de espacio consumido.
	//define("_DIR_CONSUMO", constant("DIR_FS_DOCUMENT_ROOT") . ",/cgis,/statistics/logs,/tmp");
	define("_DIR_CONSUMO", "");
	define("_QUOTA",25); //Cuota en MB de espacio en disco.

	define("LENGUAJEDEFECTO", "es");

	define("EMPRESA_PE", "3788");	//Código de la empresa Psicólogos Empresariales

	 //Contantes de conexión a Base de Datos
        define("DB_TYPE", "mysql");
        define("DB_DATOS", "ts");
        define("DB_HOST", "webcorporativa-2-cluster.cluster-cbvlbud7zscu.eu-west-3.rds.amazonaws.com");
        define("DB_USUARIO", "teststation");
        define("DB_PASSWORD","nosferatu62");
	//FIN Contantes de conexión a Base de Datos

	//Contantes de conexión a plataforma e-Cases
	define("HTTP_SERVER_ECASES", "https://e-cases.test:543/auth/login");
	define("DB_DATOS_ECASES", "c2ecase");
	define("DB_HOST_ECASES", "www.ecases-pe.com");
	define("DB_USUARIO_ECASES", "c2ecase");
	define("DB_PASSWORD_ECASES","GvYx2JsbV@c");
	//FIN Contantes de conexión a plataforma e-Cases

	//Contantes de conexión a plataforma VITA AV
	define("HTTP_SERVER_AV", "https://assessment.people-experts.com/hash/auth/");
	define("DB_DATOS_AV", "as");
	define("DB_HOST_AV", "assessment.people-experts.com");
	define("DB_USUARIO_AV", "assessment");
	define("DB_PASSWORD_AV","wS3cg1_8");
	//FIN Contantes de conexión a plataforma e-Cases
?>
>>>>>>> ef67b2adad35376e7004f53c2ad7cef5f1096846
