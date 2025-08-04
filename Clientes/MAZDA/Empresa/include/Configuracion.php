<?php
	$_HTTP_HOST = (empty($_SERVER["HTTP_HOST"])) ? "localhost" : $_SERVER["HTTP_HOST"];
	$_DOCUMENT_ROOT = (empty($_SERVER["DOCUMENT_ROOT"])) ? "C:\\Datos\\xampp\\htdocs" : $_SERVER["DOCUMENT_ROOT"];

	define("HTTP_LINK_ACCESO", "http://mazda.test-station.biz");
	// Define the webserver and path parameters
	// * DIR_FS_* Directorio físico (local/physical)
	// * DIR_WS_* Directorio del servidor Web (virtual/URL)
	define("HTTP_SERVER", "http://" . $_HTTP_HOST . "/Clientes/MAZDA/Empresa/");	//Siempre terminar en "/"
	define("HTTPS_SERVER", "https://" . $_HTTP_HOST . "/Clientes/MAZDA/Empresa/");	//Siempre terminar en "/"
	define("HTTP_SERVER_FRONT", "http://" . $_HTTP_HOST . "/Clientes/MAZDA/");	//Siempre terminar en "/"
	define("DIR_WS_GESTOR", "http://" . $_HTTP_HOST . "/Admin/");
	define("DIR_ADODB", $_DOCUMENT_ROOT . "adodb/");
	define("DIR_WS_GRAF", "graf/");
	define("DIR_WS_INCLUDE", "include/");
	define("DIR_WS_COM", constant("DIR_WS_INCLUDE") . "com/");
	define("DIR_WS_PLANTILLAS", constant("DIR_WS_INCLUDE") . "plantillas/");
	define("DIR_WS_LANG", constant("DIR_WS_INCLUDE") . "lang/");
	define("DIR_WS_AYUDA", "ayuda/");
	define("DIR_WS_COMBOS", "Template/");

	//Generación gráfica Libreria JPGRAPH
	define("DIR_WS_JPGRAPH", constant("DIR_WS_COM") . "jpgraph/");
	//Generación PDFLibreria HTML2PS
	define("HTML2PS_DIR", $_DOCUMENT_ROOT . "html2ps_v2043/public_html/");

	//Formato de fecha de usuario
	define("USR_FECHA", "d/m/Y");
	define("USR_SEPARADORMILES", ".");
	define("USR_SEPARADORDECIMAL", ",");
	define("USR_NUMDECIMALES", "2");

	//Directorios físicos
	define("DIR_FS_DOCUMENT_ROOT", $_DOCUMENT_ROOT . "Clientes/MAZDA/Empresa/"); //Siempre terminar en "/"
	define("DIR_FS_DOCUMENT_ROOT_ADMIN", $_DOCUMENT_ROOT . "Admin/"); //Siempre terminar en "/"
	//Fichero de log
	define("DIR_FS_PATH_NAME_LOG", constant("DIR_FS_DOCUMENT_ROOT") . "errores.log");
	define("DIR_FS_PATH_NAME_CORREO", constant("DIR_FS_DOCUMENT_ROOT") . "correos.log");

	define("MB",			1024);	//Tamaño de 1 MB == 1024
	define("CHAR_SEPARA"	,"§");	//Carácter separador de varios valores para contruir arrays.

	//Nombre del site
	define("NOMBRE_SITE", "Test Station");
	define("NOMBRE_EMPRESA", "People Experts");

    //Tiempo de inactividad de sesión en minutos
	define("TIMEOUT_SESION", "60");
	//Nombre de SESSION
	define("NOMBRE_SESSION", "Empresatest-station");

	//Constantes de control de flujo.
	define("MNT_INICIO",0);
	define("MNT_NUEVO",184);
	define("MNT_NUEVO_STEP_1",284);
	define("MNT_NUEVO_STEP_2",384);
	define("MNT_NUEVO_STEP_3",484);
	define("MNT_NUEVO_STEP_4",584);
	define("MNT_ALTA",405);
	define("MNT_MODIFICAR",323);
	define("MNT_BORRAR",411);
	define("MNT_CONSULTAR",131);
	define("MNT_CONSULTAR_STEP_1",231);
	define("MNT_CONSULTAR_STEP_2",331);
	define("MNT_CONSULTAR_STEP_3",431);
	define("MNT_CONSULTAR_STEP_4",531);
	define("MNT_LISTAR",564);
	define("MNT_BUSCAR",111);
	define("MNT_RESUMEN",999);
	define("MNT_AGREGAPRUEBA",611);
	define("MNT_LISTAIDIOMAS",612);
	define("MNT_LISTABAREMOS",613);
	define("MNT_ANIADEBAREMO",614);
	define("MNT_LISTAPUNTBAREMOS",615);
	define("MNT_ANIADECANDIDATOS",616);
	define("MNT_LISTACANDIDATOS",617);
	define("MNT_CARGACANDIDATOS",618);
	define("MNT_CARGACANDIDATOS_DEFINICION",718);
	define("MNT_CARGACANDIDATOS_FINALIZAR",818);
	define("MNT_ESCOGECORREOS",619);
	define("MNT_NUEVOCORREO",620);
	define("MNT_LISTACORREOS",621);

	define("MNT_CARGAITEMS",623);
	define("MNT_GUARDAASIGNADOS",624);
	define("MNT_COMPRUEBAESCALAS",625);
	define("MNT_LISTATIPOS",626);
	define("MNT_EXPORTA",627);
	define("MNT_EXPORTA_HTML",727);
	define("MNT_EXPORTA_WORD",827);
	define("MNT_DESCUENTA_DONGLES",628);
	define("MNT_DESCUENTA_DONGLES_HTML",728);
	define("MNT_DESCUENTA_DONGLES_WORD",828);


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

	//Contantes de conexión a Base de Datos MS SQLSERVER
	define("DB_DATOS_MS", "expertosonline");
	define("DB_HOST_MS", "91.121.122.177");
	define("DB_USUARIO_MS", "expertosonline");
	define("DB_PASSWORD_MS","Lnx4Q97ra");
	//FIN Contantes de conexión a Base de Datos MS SQLSERVER

	//Contantes de conexión a plataforma ecases
	define("HTTP_SERVER_ECASES", "http://e-cases52.test/auth/login");
	define("DB_TYPE_ECASES", "mysql");
	define("DB_PORT_ECASES", "3306");
	define("DB_DATOS_ECASES", "c2ecase");
	define("DB_HOST_ECASES", "localhost");
	define("DB_USUARIO_ECASES", "root");
	define("DB_PASSWORD_ECASES","");
	//FIN Contantes de conexión a plataforma ecases

	//Contantes de conexión a plataforma assessment
	define("HTTP_SERVER_AV", "http://assessmentupdate54.test/login");
	define("DB_TYPE_AV", "mysql");
	define("DB_PORT_AV", "3306");
	define("DB_DATOS_AV", "as");
	define("DB_HOST_AV", "localhost");
	define("DB_USUARIO_AV", "root");
	define("DB_PASSWORD_AV","");
	//FIN Contantes de conexión a plataforma ecases

//Contantes de conexión a FTP plataforma Assessment
	define("FTP_SERVER_AV", "expertos-pe-online.com");
	define("FTP_REMOTE_DIR_AV", "/httpdocs/public/uploads/TS/");
	define("FTP_USUARIO_AV", "expertospe");
	define("FTP_PASSWORD_AV","1Tol7%8g");
//FIN Contantes de conexión a FTP plataforma Assessment

//PRODUCCION Contantes de conexión a FTP plataforma Assessment
	// define("FTP_SERVER_AV", "people-experts.com");
	// define("FTP_REMOTE_DIR_AV", "/public/uploads/TS/");
	// define("FTP_USUARIO_AV", "assessmentpp");
	// define("FTP_PASSWORD_AV","H31BdP3dr0");
//FIN Contantes de conexión a FTP plataforma Assessment

?>
