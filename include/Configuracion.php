<?php
	// Define the webserver and path parameters
	// * DIR_FS_* Directorio físico (local/physical)
	// * DIR_WS_* Directorio del servidor Web (virtual/URL)
	define("HTTP_SERVER", "https://" . $_SERVER["HTTP_HOST"] . "/");	//Siempre terminar en "/"
	define("HTTPS_SERVER", "https://" . $_SERVER["HTTP_HOST"] . "/");	//Siempre terminar en "/"
	define("DIR_WS_GESTOR", "Admin/");
	define("DIR_ADODB", $_SERVER["DOCUMENT_ROOT"] . "/_adodb/");
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
	define("HTML2PS_DIR", constant("DIR_WS_COM") . "html2ps/public_html/");

	//Formato de fecha de usuario
	define("USR_FECHA", "d/m/Y");
	define("USR_SEPARADORMILES", ".");
	define("USR_SEPARADORDECIMAL", ",");
	define("USR_NUMDECIMALES", "2");

	//Directorios físicos
	define("DIR_FS_DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"] . "/"); //Siempre terminar en "/"
	//Fichero de log
	define("DIR_FS_PATH_NAME_LOG", constant("DIR_FS_DOCUMENT_ROOT") . "errores.log");

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
	define("DEFAULT_FIRMA","Negocia Internet");

	//Datos para la clase de envio de correo
    define("MAILER","smtp"); //PUEDE SER mail O smtp
  //Host, nombre de servidor smtp
    define("HOSTMAIL","email-smtp.eu-west-3.amazonaws.com");
    define("PORTMAIL","587");    //25 o 587 normalmente
    define("MAIL_ENCRYPTION","tls");	//tls, ssl
  //usuario y password PARA EL ENVIO DE CORREO, TIENE Q SER UNA CUENTA VÁLIDA
    define("MAILUSERNAME","AKIAZQYBBGX6FKUJR4ON");
    define("MAILPASSWORD","BM7HU83cQOY6poPRQeVCyb0g3K8mb63ZSZRtThYofIgh");
	//Email de contacto.
  	define("EMAIL_CONTACTO","info@test-station.com");
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


	//Contantes de conexión a Base de Datos
	define("DB_TYPE", "mysql");
	define("DB_DATOS", "ts");
	define("DB_HOST", "webcorporativa-2-cluster.cluster-cbvlbud7zscu.eu-west-3.rds.amazonaws.com");
	define("DB_USUARIO", "teststation");
	define("DB_PASSWORD","nosferatu62");
	//FIN Contantes de conexión a Base de Datos
?>
