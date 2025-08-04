<?php
/*********** Mensajes de Selección de los combos *************/
//Formato: SLC_XXXXX
define("SLC_OPCION", "Select an option");
define("SLC_PERFIL", "Select a Profile");
define("SLC_PERFIL_FUNCIONALIDAD", "Select Profile, Functionality");
/*********** Mensajes Warring de borrado *************/
//Formato: DEL_XXXXX
define("DEL_GENERICO"				, "WARNING! Deleted data cannot be recovered,\\n\\nAre you sure you want to delete this record");
/*********** Mensajes de Confirmación *************/
//Formato: CONF_
define("CONF_ALTA"					, "Record correctly activated with ID ");
define("CONF_SESSION"				, "Your session has expired: data has not been saved.");
define("CONF_ALTA_VARIOS"			, "%s of %s records have been uploaded correctly.");
/*********** Mensajes *************/
//Formato: MSG_
define("MSG_POR_FAVOR_ESPERE_CARGANDO", "Please wait. <br /> Loading ...");
define("MSG_SE_HA_ENVIADO_UN_EMAIL_A_SU_CUENTA_DE_CORREO", "We have sent a message to your Email address.");
define("MSG_SE_HA_ENVIADO_LA_NUEVA_CONTRASENA_A_SU_CORREO", "We have sent a new password to your Email address.");
define("MSG_SU_NUEVA_CONTRASENA_ES", "Your new password is:");
define("MSG_UD_HA_SOLICITADO_LA_RESTAURACIÓN_DE_SU_CONTRASENA", "You have requested your password to be reset");
define("MSG_SI_ESTA_DE_ACUERDO_PULSE_EL_SIGUIENTE_ENLACE", "If you agree, click the link below");
define("MSG_BIENVENIDA", "Welcome to the Administrator platform. ");
define("MSG_BIENVENIDA_COMENZAR", "Choose an option in the left menu to start.");
define("MSG_IMPORTAR_CSV_2", "Select the settings required for correct visualisation of your data.");
define("MSG_IMPORTAR_CSV_3", "Now, select the relationship between the fields imported from your file and the Candidate platform registration fields");
define("MSG_INDIQUE_CAMPO_EMAIL_PARA_IMPORTAR", "Please enter at least the Email field to import.");
define("MSG_SIN_BAREMO_ASIGNADO", "Cannot allocate this test: a scale has not been created for it.");
define("MSG_SIN_INFOME_ASIGNADO", "Cannot allocate this test: a report has not been assigned to it.");
define("MSG_CANDIDATO_SIN_PRUEBAS_FINALIZADSA_PROCESO", "The participant has not completed the tests in this process.");

define("MSG_SIN_DONGLES_PARA_VER_INFORME", "Dongles not have enough to view this report.");
define("MSG_SIN_RESPUESTAS_PARA_LA_PRUEBA", "There are no answers to the test.");
define("MSG_SE_LE_DESCONTARAN_X_DONGLES", " %s dongle units will be deducted.");
define("MSG_DESCONTADOS_X_DONGLES", "Removed %s dongles.");

define("MSG_ANTIGUEDAD_EXPORT_INFO"	, "If you need assessment results from more than one year ago, please contact our helpdesk team: <strong>info@teststation.com</strong> with the required dates.");
define("MSG_DATOS_DE_ACCESO_ENVIADOS_ALTAS_CIEGAS"	, "We have sent you an e-mail with the information to log in.<br/>Please check your inbox and the spam folder.");
define("MSG_YA_REGISTRADO_ALTAS_CIEGAS_CONTINUAR"	, "You have been registered on the system, if you wish to continue, please click on the following <a href=\"remember.php?s=%s\">link</a> and you will receive an e-mail with the information to log in.");
define("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA"	, "The following candidates have previously completed the online evaluation:");
define("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA_SELECCIONE_OPCION"	, "The following candidates have previously completed at least one online evaluation: please select one of the options:");
define("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA_ENVIO_OK"	, "A mail message has been sent to the Candidates marked with <img src=\"" . constant('DIR_WS_GRAF') . "publicado.gif\" border=\"0\" height=\"20\" alt=\"Sent\" /> of the process %s");
define("MSG_CANDIDATOS_YA_REALIZARON_PRUEBA_ZIP_DESCARGA"	, "Click on the image <img src=\"" . constant('DIR_WS_GRAF') . "zip.gif\" border=\"0\" height=\"20\" alt=\"Download\" /> of each candidate to download their reports.");
define("MSG_ENVIO_PROGRAMADO_EJECUCION"	, "*The scheduled mailing process runs every ten minutes. The time set for the mailing is indicative and there may be a significant deviation of this time.");
/*********** Mensajes de Error en los campos del formulario *************/
//Formato: ERR_FORM_XXXXX
define("ERR"								, "General Error: try again later.");
define("ERR_NO_AUTORIZADO"					, "Unauthorised: contact your administrator.");
define("ERR_ADMINISTRADOR"					, "Sorry, contact your administrator.");
define("ERR_FORM"							, "We found the following errors in the form");
define("ERR_FORM_CORRIJA"					, "Correct or complete the specified data");
define("ERR_FORM_LOGIN"						, "Login ID or password incorrect");
define("ERR_FORM_ERROR"						, "Unable to execute the selected action");
define("ERR_FORM_PASS_CONF"					, "Confirm Password and Password are not the same");
define("ERR_FORM_EJECUTAR_OPCION"			, "Unable to execute the selected option");
define("ERR_FORM_CARACTERES_LOGIN"			, "Invalid characters entered.");
define("ERR_FORM_DOWNLOAD_FICHERO"			, "File could not be downloaded.");

define("ERR_SIN_DONGLES", "do not have enough Dongles for reloading. \\n Perform a reload request to run this operation. ");
define("ERR_FORM_ASIG_PRUEBA_BAREMO", "You must select: \\nTest, Test, Norm Set, \\nLanguage report and Types Report to add tests.");
define("ERR_FORM_ASIG_PRUEBA", "You must select: \\nTest, Language, \\nLanguage Report and Types Report to add tests.");
define("ERR_ENVIAR_CORREOS_SIGUIENTES_DIRECCIONES"					, "Could not send emails to the following addresses:");
define("ERR_FECHA_PROGRAMADA_RANGO"					, "The scheduled date must be between the Start date - End date of the assessment and can NOT be earlier than today.");
define("ERR_HORA_PROGRAMADA_FORMATO"					, "Wrong scheduled time format programmed: the format should be: HH:MM.");
define("ERR_MINUTOS_PROGRAMADA_MAXIMO"					, "Minutes for the scheduled time incorrect: maximum value 59.");
//Literales en los campos de formulario.
//Formato: STR_XXXXX
define("STR_CAMPO_REQUERIDO", "Required field");
define("STR_ERROR_LONGITUD", "Length error");
define("STR_ERROR_CARACTERES", "Error Character");
define("STR_EMAIL_INCORRECTO", "Email incorrect");;
define("STR_BANCO_CAMPO_REQUERIDO", "Required field: Bank");
define("STR_OFICINA_CAMPO_REQUERIDO", "Required field: Office");
define("STR_DC_CAMPO_REQUERIDO", "Required field:DC");
define("STR_CUENTA_CAMPO_REQUERIDO", "Required Field: Account ");
define("STR_DATOS_BANCARIOS_INCORRECTOS", "Incorrect bank details");
define("STR_FORMATO_ERRONEO", "Incorrect format");
define("STR_NIF_INCORRECTO", "Incorrect (National) Identity Number");
define("STR_CIF_INCORRECTO", "Incorrect Corporate ID number: CIF");
define("STR_LOGIN", "User");
define("STR_PASSWORD", "Password");
define("STR_RECUERDAME_LA_CLAVE", "Remind me of the key");
define("STR_AYUDA", "Help");
define("STR_ANIADIR", "Add");
define("STR_MENU", "Menu");
define("STR_PADRE", "Next level up");
define("STR_ORDEN", "Order");
define("STR_BORRAR", "Delete");
define("STR_MODIFICAR", "Edit");
define("STR_CUERPO", "Message");
define("STR_TEXTO", "Text");
define("STR_DESCRIPCION", "Description");
define("STR_POPUP", "Popup");
define("STR_ACEPTAR", "OK");
define("STR_CANCELAR", "Cancel");
define("STR_NOMBRE", "First (given) Name");
define("STR_MUCHAS_GRACIAS", "Thank You");
define("STR_CONTENIDO", "Contents");
define("STR_BUSCAR", "Search");
define("STR_USUARIO", "User");
define("STR_ALTA", "New");
define("STR_NUEVO", "New");
define("STR_FECHA", "Date");
define("STR_ESTADO", "Status");
define("STR_SRC", "Edit HTML");
define("STR_NEGRITA", "Bold");
define("STR_CURSIVA", "Italic");
define("STR_IZQUIERDA", "Left");
define("STR_CENTRO", "Centre");
define("STR_DERECHA", "Right");
define("STR_IMAGEN", "Image");
define("STR_FECHA_MODIFICACION", "Date Modified");
define("STR_FICHERO", "File");
define("STR_TIPO", "Type");
define("STR_VER", "View");
define("STR_LINKS", "Links");
define("STR_URL", "Url");
define("STR_PERFIL", "Profile");
define("STR_FUNCIONALIDAD", "Functionality");
define("STR_APELLIDO", "Last (Family) Name");
define("STR_EMAIL", "Email");
define("STR_CONF_PASSWORD", "Confirm Password");
define("STR_DESDE", "From");
define("STR_HASTA", "To");
define("STR_FAQS", "FAQ's");
define("STR_INDICE", "Index");
define("STR_RESPUESTA", "Response");
define("STR_DATOS_DE_CORREO", "Email data");
define("STR_DESPUES_DE", "After");
define("STR_SI", "Yes");
define("STR_NO", "No");
define("STR_UNIDADES", "Units");
define("STR_SUBRAYADO", "Underline");
define("STR_SANGRIA", "Indent");
define("STR_QUITAR_SANGRIA", "Remove Indent");
define("STR_LISTA", "List");
define("STR_LISTA_NUMERICA", "Numbered List");
define("STR_COLOR_DE_FUENTE", "Font colour");
define("STR_COLOR_DE_FONDO", "Background Colour");
define("STR_NINGUNO", "None");
define("STR_ICONO", "Icon");
define("STR_FICHEROS", "Files");
define("STR_LISTA_DE_", "%s List");
define("STR_VOLVER", "Back");
define("STR_BUSCADOR", "Search");
define("STR_ENTRAR", "Login");
define("STR_PARAMETROS_DE_BUSQUEDA", "Search Parameters");
define("STR_REGLA_HORIZONTAL", "Horizontal Ruler");
define("STR_JUSTIFICADO", "Justified");
define("STR_TACHADO", "Strikeout");
define("STR_SUB_INDICE", "Sub index");
define("STR_SUPER_INDICE", "superscript");
define("STR_QUITAR_CODIGO_HTML", "Remove HTML");
define("STR_FUENTE", "Source");
define("STR_QUITAR", "Remove");
define("STR_ORDENAR_POR", "Sort by");
define("STR_ASCENDENTE", "Ascending");
define("STR_DESCENDENTE", "Descending");
define("STR_LINEAS_POR_PAGINA", "Lines per page");
define("STR_DENTRO_DE", "Within");
define("STR_GUARDAR", "Save");
define("STR_TODOS", "All");
define("STR_NUNCA", "Never");
define("STR_INICIO", "Home");
define("STR_MIS_DATOS", "My details");
define("STR_SALIR", "Log out");
define("STR_BAJA", "Termination");
define("STR_ACTIVO", "Active");
define("STR_NO_ACTIVO", "Inactive");
define("STR_LOGIN_DE_CORREO", "Login Email");
define("STR_PASSWORD_DE_CORREO", "Password-mail");
define("STR_TIPO_DE_USUARIO", "User Type");
define("STR_ID", "ID");
define("STR_PRIMER_APELLIDO", "Last Name 1");
define("STR_SEGUNDO_APELLIDO", "Last Name 2");
define("STR_FEC_ALTA", "Registration Date");
define("STR_FEC_MOD", "Date Modified");
define("STR_USU_ALTA", "New User");
define("STR_USU_MOD", "Change user");
define("STR_FEC_BAJA", "Termination Date");
define("STR_BAJA_LOG", "FALTA CONTEXTO Logical Termination.");	//-> Se refiere al estado de un registro en la base de datos Baja Lógica (Sí / No) (Activo / Inactivo), en este caso de Empresa, sólo se utiliza en los registros de log, para el usuario Empresa no es visible.
define("STR_CALENDARIO", "Calendar");
define("STR_ULTIMA_CONEXION", "Last login");
define("STR_EXPORTAR_A_EXCEL", "Export to Excel");
define("STR_VERIFICADO", "Verified");
define("STR_REPETIDO", "Duplicate");
define("STR_IDIOMAS", "Languages");
define("STR_CONTROL_ACCESO", "Access Control");
define("STR_TEXTO_ACCESO", "Complete the following fields for access to the Administrator panel.");
define("STR_TEXTO_RECUPERA_CLAVE", "To retrieve your password fill out the field with your user login.");
define("STR_DISENO_DESARROLLO", "Web Design and Development");
define("STR_DERECHOS_RESERVADOS", "All rights reserved");
define("STR_ANADIR_EN_OTRO_IDIOMA", "Add another language");
define("STR_ID_FUNCIONALIDAD", "Functionality");
define("STR_ENTRADA_POR_DEFECTO", "Default entry");
define("STR_PUBLICO", "Public");
define("STR_INDENTACION", "Indentation");
define("STR_BG_FILE", "BG File");
define("STR_BG_COLOR", "BG Colour");
define("STR_MODO", "Mode");
define("STR_ICONOS_DIRECTOS_DEL_MENU", "Icons Menu Shortcuts");
define("STR_PERFIL_FUNCIONALIDAD", "Profile, Functionality");
define("STR_ID_PERFIL", "Profile ID");
define("STR_ID_USUARIO", "User ID");
define("STR_ULTIMO_LOGIN", "Last login");
define("STR_COD_ISO2", "ISO2 Cod");
define("STR_ACTIVO_EN_EL_FRONT", "Active at the front");
define("STR_ACTIVO_EN_EL_BACK", "Active in the back");
define("STR_CAMBIAR_CONTRASENA", "Change Password");
define("STR_PRIMERO", "First");
define("STR_ID_AREA", "ID Area");
define("STR_IDIOMA", "Language");
define("STR_FECHA_DE_ALTA", "Date Added");
define("STR_FECHA_DE_MODIFICACION", "Date Modified");
define("STR_USUARIO_DE_ALTA", "User Added");
define("STR_USUARIO_DE_MODIFICACION", "Change User");
define("STR_AREAS", "Areas");
define("STR_ID_BAREMO", "Norm Set ID");
define("STR_PRUEBA", "Test");
define("STR_OBSERVACIONES", "Comments");
define("STR_BAREMOS", "FALTA CONTEXTO Scales ID");	//-> Se refiere a los grupos de comparación formado por personas similares al candidato que realizará la prueba, en este caso de Empresa, sólo se utiliza en los registros de log.

define("STR_ID_RESULTADO", "ID Result");
define("STR_BAREMO", "FALTA CONTEXTO Comparison Group");	//-> Incluido en los FROMS al agregar una prueba para seleccionar el grupo de comparación formado por personas similares al candidato que realiza la prueba.
define("STR_PUNTUACION_MINIMA", "Minimum Score");
define("STR_PUNTUACION_MAXIMA", "Maximum Score");
define("STR_PUNTUACION_BAREMADA", "Rating Scale");
define("STR_BAREMOSRESULTADOS", "FALTA CONTEXTO Baremos_resultados");	//-> Se refiere a las puntuaciones que formal el baremo de comparación, en este caso de Empresa, sólo se utiliza en los registros de log.
define("STR_ID_CANDIDATO", "Candidate ID");
define("STR_EMPRESA", "Company");
define("STR_PROCESO", "Process");
define("STR_APELLIDO_1", "Last Name 1");
define("STR_APELLIDO_2", "Last Name 2");
define("STR_NIF", "DNI/ (National) Identity Number");
define("STR_TRATAMIENTO", "Title");
define("STR_SEXO", "Gender");
define("STR_EDAD", "Age");
define("STR_FECHA_DE_NACIMIENTO", "Date of Birth");
define("STR_PAIS", "Country");
define("STR_PROVINCIA", "Province");
define("STR_MUNICIPIO", "City");
define("STR_ZONA", "Zone");
define("STR_DIRECCION", "Address");
define("STR_CODIGO_POSTAL", "Zip / Post Code");
define("STR_FORMACION", "Education");
define("STR_NIVEL", "FALTA CONTEXTO Professional Level");	//-> Niveles jerárquicos (Auxiliares Administrativos, Operarios y Oficiales, Secretarias Dirección, Mandos Intermedios ....)
define("STR_AREA", "Area");
define("STR_TELEFONO", "Phone");
define("STR_ESTADO_CIVIL", "Marital Status");
define("STR_NACIONALIDAD", "Nationality");
define("STR_INFORMADO", "Sent");
define("STR_FINALIZADO", "Done");
define("STR_FINALIZADO_PROCESO", "Process Completed");
define("STR_FECHA_DE_FINALIZADO", "Date Completed");
define("STR_TOKEN", "Token");
define("STR_ULTIMA_ACCION", "Last Action");
define("STR_CANDIDATOS", "Candidates");
define("STR_ID_CORREO", "Email ID");
define("STR_TIPO_CORREO", "Notification Type");
define("STR_ASUNTO", "Subject");
define("STR_CORREOS", "Email");
define("STR_LITERAL", "Literal");
define("STR_SISTEMA", "System");
define("STR_CORREOSLITERALES", "FALTA CONTEXTO Correos_literales");	//-> Tags que se puede introducir en el cuerpo a asunto del correo que serán sustituidos por el valor real antes del envío del correo.
define("STR_CORREO", "Name");
define("STR_CORREOSPROCESO", "FALTA CONTEXTO Correos_proceso");	//-> Plantillas de correos para el envio de assesos al candidato, puede ser la plantilla de correo original o modificada, sólo se utiliza al guardar registros de log.
define("STR_ID_EDAD", "Id Age");
define("STR_EDADES", "Age");
define("STR_ID_EJEMPLO", "Id Example");
define("STR_ENUNCIADO", "Statement");
define("STR_IMAGEN_1", "Figure 1");
define("STR_IMAGEN_2", "Figure 2");
define("STR_IMAGEN_3", "Figure 3");
define("STR_IMAGEN_4", "Figure 4");
define("STR_CORRECTO", "Correct");
define("STR_BAJA_LOGICA", "FALTA CONTEXTO Low Logic");	//-> Sólo se utiliza para los logs, no es mostrado en el front de Empresa.
define("STR_EJEMPLOS", "Examples");
define("STR_CODIGO", "Code");
define("STR_EMKCHARSETS", "Emk_charsets");
define("STR_ID_EMPRESA", "Company ID");
define("STR_CIF", "CIF");
define("STR_CONTRASENA", "Password");
define("STR_LOGO", "Logo");
define("STR_EMAIL_2", "Email 2");
define("STR_EMAIL_3", "Email 3");
define("STR_DISTRIBUIDOR", "Distributor");
define("STR_PREPAGO", "Prepaid");
define("STR_NU_CANDIDATOS", "No Candidate");
define("STR_DONGLES", "FALTA CONTEXTO Dongles");	//-> Bolsa de unidades a la que se le irá descontando el coste de unidadas definidas según el informe de cada prueba.
define("STR_ENTIDAD", "Bank");
define("STR_OFICINA", "Office");
define("STR_DC", "DC");
define("STR_CUENTA", "Account");
define("STR_UMBRAL_DE_AVISO", "Warning threshold");
define("STR_ULTIMA_ACC", "FALTA CONTEXTO Last Acc");	//->Última acción que realizo el usuario logado (Empresa contra la base de datos), se utiliza principalmente en los logs.
define("STR_EMPRESAS", "Company");
define("STR_ID_ACCESO", "Access ID");
define("STR_IP", "IP");
define("STR_EMPRESASACCESOS", "Company Access");
define("STR_EMPRESASPERFILES", "Company Profiles");
define("STR_EMPRESASPERFILESFUNCIONALIDADES", "FALTA CONTEXTO Company Profiles & Functions");	//-> Funcionalidades del perfil Empresa, se utiliza para dejar rastro en los logs.
define("STR_EMPPERFILES", "FALTA CONTEXTO Employee Profiles");	//-> Perfiles para el usuario Empresa, sólo se utiliza en los logs para dejar rastro.
define("STR_FICHEROSCARGA", "Upload Files");
define("STR_FORMACIONES", "Training");
define("STR_ID_ITEM", "Item ID");
define("STR_PATH1", "Path1");
define("STR_PATH2", "Path2");
define("STR_PATH3", "Path3");
define("STR_PATH4", "Path4");
define("STR_ITEMS", "Items");
define("STR_COMUNIDAD", "Province / Region");
define("STR_ID_MUNICIPIO", "ID Municipality");
define("STR_MUNICIPIOS", "Municipalities");
define("STR_ID_NIVEL", "ID Level");
define("STR_NIVELESJERARQUICOS", "Hierarchy Levels");
define("STR_ITEM", "Item");
define("STR_ID_OPCION", "ID Option");
define("STR_OPCIONES", "Options");
define("STR_ID_PAIS", "ID Country");
define("STR_ID_COMUNIDAD", "ID Community");
define("STR_ID_PROVINCIA", "ID Province");
define("STR_ID_FORMACION", "ID Training");
define("STR_EJEMPLO", "Example");
define("STR_OPCIONESEJEMPLOS", "Sample Options");
define("STR_OPCION", "Option");
define("STR_ID_VALOR", "ID Value");
define("STR_OPCIONESVALORES", "Values Options");
define("STR_ID_PROCESO", "ID Process");
define("STR_FECHA_DE_INICIO", "Start Date");
define("STR_FECHA_DE_FIN", "End Date");
define("STR_HORA_DE_INICIO", "Start Time");
define("STR_HORA_DE_FIN", "End Time");
define("STR_MODO_REALIZACION_PRUEBAS", "Test Execution Mode");
define("STR_PROCESOS", "Processes");
define("STR_PROCESOBAREMOS", "FALTA CONTEXTO Proceso_baremos");	//-> Baremos por prueba dada de alta en el proceso, se utiliza sólo en los logs.
define("STR_ID_MODO_REALIZACION", "ID Performance Mode");
define("STR_MODOREALIZACION", "FALTA CONTEXTO Performance Mode");	//-> Antiguo, sustituido por STR_MODO_REALIZACION_PRUEBAS, se sigue utilizando en algunos log.
define("STR_ID_ZONA", "ID Zone");
define("STR_WIZONAS", "Zones");
define("STR_ID_USUARIO_TIPO", "User ID Type");
define("STR_WIUSUARIOSTIPOS", "User Types");
define("STR_PROCESOPRUEBAS", "FALTA CONTEXTO Test Process");	//-> Pruebas dadas de alta en el proceso, sólo se utiliza en logs.
define("STR_WIPROVINCIAS", "Provinces");
define("STR_CODIGO_PAIS_ISO3", "ISO3 Country Code");
define("STR_CODIGO_PAIS_ISO2", "ISO2 Country Code");
define("STR_WIPAISES", "Countries");
define("STR_ID_PRUEBA", "ID Test");
define("STR_TIPO_PRUEBA", "Test type");
define("STR_DURACION", "Duration");
define("STR_DURACION2"                         , "Duration 2");
define("STR_LOGO_PRUEBA", "FALTA CONTEXTO Logo Testing");	//-> Logotipo de la prueba, en esta visual no se muestra, solo se registra en logs.
define("STR_CAPTURA_PANTALLA", "Screen Capture");
define("STR_CABECERA", "Header");
define("STR_PRUEBAS", "Tests");
define("STR_WIMUNICIPIOS", "Municipalities");
define("STR_FECHA_DE_CAMBIO", "Date of Change");
define("STR_QUERY", "Query");
define("STR_APELLIDO1", "Last name 1");
define("STR_APELLIDO2", "Last name 2");
define("STR_WIHISTORICOCAMBIOS", "Record of Changes");
define("STR_WICCAA", "Provinces / Regions");
define("STR_ID_TRATAMIENTO", "ID Title");
define("STR_TRATAMIENTOS", "Title");
define("STR_CANDIDATO", "Candidate");
define("STR_RESPUESTASPRUEBAS", "Test Answers");
define("STR_ID_TIPO_PRUEBA", "ID Test Type");
define("STR_TIPOSPRUEBA", "Tipos_prueba");
define("STR_ID_TIPO_CORREO", "FALTA CONTEXTO ID Mail Type");	//-> Id de Tipos de correo, recordatorio, envío de acceso, comunicación ... actualmente no se muestra, está fijado siempre a envío de accesos, también queda registrado en logs.
define("STR_TIPOSCORREOS", "Mail Types");
define("STR_ID_SEXO", "Id Gender");
define("STR_SEXOS", "Gender Options");
define("STR_RESPUESTASPRUEBASITEMS", "Tests answers items");
define("STR_MODO_REALIZACION", "Performance Mode");
define("STR_LEIDOS", "Read");
define("STR_CHARSETS", "Character Sets");
define("STR_PREVISUALIZACION_DE_DATOS_A_INSERTAR", "Previewing data to insert");
define("STR_REGISTROS_ENCONTRADOS", "Records found");
define("STR_INFORME_DE_IMPORTACION", "Import Report");
define("STR_MODO_DE_CREACION", "Creation Mode");
define("STR_CON_EDITOR_DE_TEXTO", "With Text Editor");
define("STR_POR_URL", "By Url");
define("STR_CON_HTML", "With HTML");
define("STR_FICHEROS_SIN_ACCESO", "Files not accessible");
define("STR_SUBIR_LOS_ARCHIVOS_DE_MANERA_INDEPENDIENTE", "Upload files independently");
define("STR_SUBIR_LOS_ARCHIVOS_EN_CONJUNTO_ARCHIVO_ZIP", "Upload files together: Zip file");
define("STR_CONFIGURACION_DEL_FICHERO", "File Settings");
define("STR_SEPARADOR_DE_CAMPOS", "Field separator");
define("STR_CODIFICACION_DEL_FICHERO", "File encoding");
define("STR_VALORES_ENTRECOMILLADOS", "FALTA CONTEXTO Values in Quotes");	//-> Cuando se importa un fichero de candidatos, dependiendo del origen del fichero los valores de tipo caracteres pueden ir entrecomillados.
define("STR_SIN_COMILLAS", "Without quotation marks");
define("STR_VALORES_ENCERRADOS_ENTRE_COMILLAS", "Values ​​enclosed in quotes");
define("STR_CABECERAS", "Headers");
define("STR_EL_FICHERO_NO_TIENE_CABECERAS_SE_INSERTARAN_TODAS_LAS_LINEAS", "The file has no headers (All lines are inserted)");
define("STR_EL_FICHERO_TIENE_CABECERAS_NO_SE_INSERTARA_LAS_PRIMERA_LINEA", "The file has headers (Do not insert the first line)");
define("STR_SEGUIR", "Continue");
define("STR_SELECCION_DE_CAMPOS", "Field selection");
define("STR_CAMPOS_IMPORTADOS", "Imported Fields");
define("STR_CAMPOS_DE_LA_PLATAFORMA", "Platform Fields");
define("STR_SIN_ASIGNAR", "Unassigned");
define("STR_GESTION_CONTRASENA", "Password Management");
define("STR_ID_BLOQUE", "ID Block");
define("STR_BLOQUES", "Blocks");
define("STR_BLOQUE", "Block");
define("STR_ID_ESCALA", "FALTA CONTEXTO ID Range");	//-> Identificador interno de la Escala, en esta visual sólo se muestra en logs.
define("STR_ESCALAS", "Scales");
define("STR_ESCALA", "Scale");
define("STR_ESCALASITEMS", "Scale Options");

//FALTA CONTEXTO

define("STR_ID_TIPO_COMPETENCIA", "Competence Type ID");	//-> Identificador del tipo de competencia,  sólo se utiliza en logs.
define("STR_TIPOSCOMPETENCIAS", "Competence Types");	//-> Está definido heredado de Admin, no se está utilizando.
define("STR_TIPO_DE_COMPETENCIA", "Competence Type");	//-> Está definido heredado de Admin, se sigue utilizando en algunos log.
define("STR_ID_COMPETENCIA", "Competence ID");	//-> Identificador de competencia,  sólo se utiliza en logs.
define("STR_COMPETENCIAS", "Competencies");	//-> En visual no se muestra, solo se registra en logs.
define("STR_TIPO_COMPETENCIA", "Competence type"); //-> Antiguo, sustituido por STR_TIPO_DE_COMPETENCIA, se sigue utilizando en algunos log.
define("STR_COMPETENCIA", "Competence");	//-> Sólo se utiliza en registros de logs.
define("STR_COMPETENCIASITEMS", "Competencies_items");	//-> No se utiliza.


define("STR_ITEMSINVERSOS", "FALTA CONTEXTO Items_inversos");	//-> Sólo se utiliza en registros de logs.
define("STR_ID_TIPO_INFORME", "ID Report Type");
define("STR_TIPOSINFORMES", "Type of Report");
define("STR_N_VECES_DESCARGADO", "Downloaded N times");
define("STR_INFORMESPRUEBAS", "Test Results Reports");
define("STR_IDPETICION", "ID Request");
define("STR_NUMERO_DE_DONGLES", "Number of Dongles");
define("STR_PETICIONESDONGLES", "Dongles request");
define("STR_EMPRESA_RECEPTORA", "Recipient Company");
define("STR_NOTIFICACIONESTIPOS", "Notification Options");
define("STR_TIPO_DE_NOTIFICACION", "Notification Type");
define("STR_NOTIFICACIONES", "Notices");
define("STR_VISIBLE", "Visible");
define("STR_INSTRUCCIONESPRUEBAS", "User Instructions");
define("STR_ID_AYUDA"                         , "ID Help");
define("STR_PRUEBASAYUDAS"                         , "Test Help");
define("STR_EJEMPLOSAYUDAS"                         , "Help Examples");
define("STR_TARIFA"                         , "Rate");
define("STR_ENVIAR_CORREOS", "Send Email");
define("STR_ENVIAR_MAS_TARDE", "Send Email later");
define("STR_DETALLE"                         , "Detail");
define("STR_GESTIONAR_OPCIONES", "Management Options");
define("STR_GUARDAR_SEGUIR", "Save and continue");
define("STR_ID_PRISMA", "ID Prisma");
define("STR_CARGA", "Load");
define("STR_FACULTAD", "School");
define("STR_PRISMAPAPEL", "FALTA CONTEXTO Prisma paper");	//->No se utiliza, Heredado de Admin, importación de ficheros de corrección en papel de prueba PRISM@
define("STR_PRISMA", "Prisma");
define("STR_IDSECCION"                         , "Section ID");
define("STR_SECCIONESINFORMES"                         , "Reports Sections");
define("STR_SECCION"                         , "Section");
define("STR_TEXTOSSECCIONES"                         , "Text Sections");
define("STR_TIPO_INFORME"                         , "Type of Report");

//FALTA CONTEXTO
//-> *** Solo se utilizan en logs, heredados de Admin IR: Índice de Rapidez, IP: Índice de Precisión
define("STR_ID_RANGO_IR"                         , "FALTA CONTEXTO Go Go Range");
define("STR_RANGO_SUPERIOR"                         , "Upper Range");
define("STR_RANGO_INFERIOR"                         , "Lower Range");
define("STR_RANGOSIR"                         , "FALTA CONTEXTO Rangos_ir");
define("STR_ID_SIGNO"                         , "ID Sign");
define("STR_SIGNO"                         , "Sign");
define("STR_SIGNOS"                         , "Signs");
define("STR_ID_RANGO_IP"                         , "ID IP Range");
define("STR_RANGO_IR"                         , "Go Range");
define("STR_RANGOSIP"                         , "Rangos_ip");
define("STR_IR"                         , "SI");
define("STR_RANGOSTEXTOS"                         , "Rangos_textos");
//-> ***


//TEXTOS EN INFORMES
define("STR_INFORME", "Report");
define("STR_NOMBRE_DE_LA_PRUEBA", "Test Name");
define("STR_INFORMES", "Reports");
define("STR_IDIOMA_INFORMES", "Report Language");
define("STR_BORRAR_TODO", "Clear All");
define("STR_SOLICITA", "Request");
define("STR_TRAMITA", "Process");
define("STR_IDIOMA_PRUEBA", "Test Language");
define("STR_IDIOMA_INFORME", "Report Language");
define("STR_NOMBRE_EMPRESA", "Company Name");
define("STR_NOMBRE_PROCESO", "Process Name");
define("STR_NOMBRE_CANDIDATO", "Forename");
define("STR_DNI", "DNI/ N. ID");
define("STR_MAIL", "Email Address");
define("STR_CONCEPTO", "Issue");
define("STR_CONSUMOS", "Consumption");
define("STR_DESCARGAR", "Download");
define("STR_DESCARGA_DE_INTORME", "Download Report");
define("STR_LEIDO_INSTRUCCIONES", "Read instructions");
define("STR_LEIDO_EJEMPLOS", "Read examples");
define("STR_MINUTOS_TEST", "Test - Minutes");
define("STR_SEGUNDOS_TEST", "Test - Seconds");
define("STR_ID_FICHERO_SABANA"                         , "FALTA CONTEXTO ID Fichero Sabana");	//-> No se utiliza en Empresa
define("STR_ID_TEXTO"                         , "Text ID");
define("STR_PUNT_MIN"                         , "Min Score");
define("STR_PUNT_MAX"                         , "Max Score");
define("STR_RESULTADO"                         , "RESULT");
define("STR_PRUEBASPAPEL"                         , "FALTA CONTEXTO Pruebas_papel");	//-> No se utiliza en Empresa
define("STR_FORMACION_ACADEMICA"                         , "Level of Education");
define("STR_AREA_PROFESIONAL"                         , "Professional area");
define("STR_PUNTUACION_MIN"                         , "FALTA CONTEXTO Puntuación Min");	//-> Heredado de Admin, sólo se utiliza en logs.
define("STR_PUNTUACION_MAX"                         , "Puntuación Max");
define("STR_PERFILESIDEALES"                         , "Ideal Profiles");
define("STR_FECHA_DE_FINALIZACION"                         , "Completion Date");
define("STR_DATOS_TITULO"                         , "PERSONAL DATA");
define("STR_DATOS_MB"                         , "FALTA CONTEXTO Solicitar Sector MB");	//-> Sectores en automoción Lígeros, Pesados (Utilizado sólo Cliente Mercedes Benz)
define("STR_SECTOR", "Sector");
define("STR_CONCESION", "FALTA CONTEXTO Dealership / Authorised Service Centres");		//-> Concesión o concesionario automoción (Utilizado sólo Cliente Mercedes Benz)
define("STR_FECHA_NACIMIENTO", "Date of Birth");
define("STR_GENERAR",  "Generate");
define("STR_APTITUDES",  "Aptitudes");

define("STR_MENU_1",    ":: Security");
define("STR_MENU_2",    "Modif. user data");
define("STR_MENU_3",    "Functionalities");
define("STR_MENU_4",    "Func Profile");
define("STR_MENU_5",    "User Profiles");
define("STR_MENU_6",    "Profiles");
define("STR_MENU_7",    "Users");
define("STR_MENU_8",    ":: Configuration");
define("STR_MENU_9",    "Languages");
define("STR_MENU_41",    "Texts for mail");
define("STR_MENU_13",    "Test Types");
define("STR_MENU_14",    ":: Test Management");
define("STR_MENU_15",    "Tests");
define("STR_MENU_17",    "Company");
define("STR_MENU_18",    "Processes");
define("STR_MENU_42",    "Email Template");
define("STR_MENU_22",    "Scales");
define("STR_MENU_21",    ":: Companies and Processes");
define("STR_MENU_39",    "User Profiles Company");
define("STR_MENU_38",    "Func Company Profile");
define("STR_MENU_27",    "Types of post");
define("STR_MENU_29",    "Areas");
define("STR_MENU_30",    "Genders");
define("STR_MENU_31",    "Ages");
define("STR_MENU_32",    "Levels of Hierarchy");
define("STR_MENU_33",    "FALTA CONTEXTO formations");	//-> Menú Gestión Formaciones Académicas, de momento no se muestra en Empresa.
define("STR_MENU_40",    "Company Profiles");
define("STR_MENU_45",    "Candidates");
define("STR_MENU_36",    ":: Security: Companies");
define("STR_MENU_47",    "Charsets files");
define("STR_MENU_48",    "Company Profile");
define("STR_MENU_70",    ":: Email Management");
define("STR_MENU_71",    "Email Sending");
define("STR_MENU_51",    "Municipalities");
define("STR_MENU_57",    "Test Performance Mode");
define("STR_MENU_58",    "areas");
define("STR_MENU_59",    "User Types");
define("STR_MENU_61",    "provinces");
define("STR_MENU_62",    "countries");
define("STR_MENU_72",    "blocks");
define("STR_MENU_65",    "Regional Authorities");
define("STR_MENU_66",    "Titles");
define("STR_MENU_67",    "Candidates Test Data");
define("STR_MENU_68",    "Test Response items");
define("STR_MENU_69",    ":: Candidates Management");
define("STR_MENU_73",    "scales");
define("STR_MENU_74",    "Assign. Scales to Items");
define("STR_MENU_75",    "Types of skills");
define("STR_MENU_76",    "competences");
define("STR_MENU_77",    "Assign. skills to items");
define("STR_MENU_78",    "FALTA CONTEXTO items inverse");	//-> Menú Configuración de items inversos, heredado de Admin, no se muestra en Empresa, tiene que estar por integridad de plataforma.
define("STR_MENU_79",    "Types of Report");
define("STR_MENU_80",    "Test Reports");
define("STR_MENU_81",    ":: Report Management");
define("STR_MENU_82",    "Candidates' Reports");
define("STR_MENU_83",    "Dongles Request");
define("STR_MENU_84",    "Types of Notifications");
define("STR_MENU_85",    "Notifications ::");
define("STR_MENU_86",    "Notifications");
define("STR_MENU_87",    "Test Instructions");
define("STR_MENU_88",    "Test Help");
define("STR_MENU_89",    "Help Examples");
define("STR_MENU_92",    "sections reports");
define("STR_MENU_93",    "Texts by section");
define("STR_MENU_94",    "Rangos ir");
define("STR_MENU_95",    "Signos");
define("STR_MENU_96",    "Rangos ip");
define("STR_MENU_97",    "Rangos textos");
define("STR_MENU_98",    "Scales for Competence");
define("STR_MENU_102",    "Consumption");
define("STR_MENU_107",    "Search Company");
define("STR_MENU_106",    "New Company");
define("STR_MENU_105",    "Search Process");
define("STR_MENU_104",    "New Process");
define("STR_MENU_108",    ":: Import prisms paper");
define("STR_MENU_110",    ":: Menu help");
define("STR_MENU_111",    "New Request");
define("STR_MENU_112",    "Search Request");
define("STR_MENU_113",    "Search Consumption");
define("STR_MENU_114",    "Search Candidates");
define("STR_MENU_115",    "Search Candidates' Reports");
define("STR_MENU_116",    "Search Email Sending");
define("STR_MENU_117",    "Search Email Template");
define("STR_MENU_120",    "Export Aptitude");
define("STR_MENU_134",    ":: Video tutorials");
define("STR_MENU_135",    "Export Personality");

define("STR_DESC_MENU_1",    "Security Management");
define("STR_DESC_MENU_2",    "Modify my details");
define("STR_DESC_MENU_3",    "Management functionalities");
define("STR_DESC_MENU_4",    "Assigning functions to profile");
define("STR_DESC_MENU_5",    "Allocation of user profiles");
define("STR_DESC_MENU_6",    "Profile Management");
define("STR_DESC_MENU_7",    "User Management");
define("STR_DESC_MENU_8",    "Auxiliary table management application");
define("STR_DESC_MENU_9",    "Management Languages");
define("STR_DESC_MENU_41",    "Texts for mail");
define("STR_DESC_MENU_13",    "Test Types");
define("STR_DESC_MENU_14",    "Test Management, items, response options ...");
define("STR_DESC_MENU_15",    "New test process, items, options ...");
define("STR_DESC_MENU_17",    "Company Management");
define("STR_DESC_MENU_18",    "Processes");
define("STR_DESC_MENU_42",    "Email Template");
define("STR_DESC_MENU_22",    "scales");
define("STR_DESC_MENU_21",    "Companies and Processes");
define("STR_DESC_MENU_39",    "Assigning user profiles Company");
define("STR_DESC_MENU_38",    "Assigning functions to Company Profile");
define("STR_DESC_MENU_27",    "Types of post");
define("STR_DESC_MENU_29",    "Areas");
define("STR_DESC_MENU_30",    "genders");
define("STR_DESC_MENU_31",    "ages");

//Gestión = "Administration" o "Handling"

define("STR_DESC_MENU_32",    "Hierarchical Level Management");
define("STR_DESC_MENU_33",    "formations");
define("STR_DESC_MENU_40",    "Company Profile Management");
define("STR_DESC_MENU_45",    "Manual management of candidates");
define("STR_DESC_MENU_36",    "Enterprise Security Management");
define("STR_DESC_MENU_47",    "Charsets files");
define("STR_DESC_MENU_48",    "Company Profile");
define("STR_DESC_MENU_70",    "Management of Post");
define("STR_DESC_MENU_71",    "Sending e");
define("STR_DESC_MENU_51",    "Management of Municipalities");
define("STR_DESC_MENU_57",    "Embodiment management of the Test");
define("STR_DESC_MENU_58",    "Zone Management");
define("STR_DESC_MENU_59",    "User Types");
define("STR_DESC_MENU_61",    "Provinces Management");
define("STR_DESC_MENU_62",    "Countries Management");
define("STR_DESC_MENU_72",    "blocks");
define("STR_DESC_MENU_65",    "Autonomous Management / United");
define("STR_DESC_MENU_66",    "Management Treatments");
define("STR_DESC_MENU_67",    "Test data candidates");
define("STR_DESC_MENU_68",    "Replies evidence items");
define("STR_DESC_MENU_69",    "Lead Management");
define("STR_DESC_MENU_73",    "scales");
define("STR_DESC_MENU_74",    "Allocation to Items Scales");
define("STR_DESC_MENU_75",    "Types of skills");
define("STR_DESC_MENU_76",    "competences");
define("STR_DESC_MENU_77",    "Assign. skills to items");
define("STR_DESC_MENU_78",    "items inverse");
define("STR_DESC_MENU_79",    "Types of Report");
define("STR_DESC_MENU_80",    "Allocation of test reports");
define("STR_DESC_MENU_81",    "Reporting");
define("STR_DESC_MENU_82",    "Candidates' Reports");
define("STR_DESC_MENU_83",    "Dongles Request");
define("STR_DESC_MENU_84",    "Types of Notifications");
define("STR_DESC_MENU_85",    "Notifications ::");
define("STR_DESC_MENU_86",    "Notifications");
define("STR_DESC_MENU_87",    "Test Instructions");
define("STR_DESC_MENU_88",    "Aids tests");
define("STR_DESC_MENU_89",    "Examples Aids");
define("STR_DESC_MENU_92",    "sections reports");
define("STR_DESC_MENU_93",    "Texts by section");
define("STR_DESC_MENU_94",    "Rangos_ir.php");
define("STR_DESC_MENU_95",    "Signos.php");
define("STR_DESC_MENU_96",    "Rangos_ip.php");
define("STR_DESC_MENU_97",    "Rangos_textos.php");
define("STR_DESC_MENU_98",    "Competence Scales");
define("STR_DESC_MENU_102",    "Consumption");
define("STR_DESC_MENU_103",    "Excel: rights and wrongs");
define("STR_DESC_MENU_107",    "Search Company");
define("STR_DESC_MENU_106",    "New Company");
define("STR_DESC_MENU_105",    "Search Process");
define("STR_DESC_MENU_104",    "New Process");
define("STR_DESC_MENU_108",    "Import paper prisms");
define("STR_DESC_MENU_110",    "Menu :: help");
define("STR_DESC_MENU_111",    "New Request");
define("STR_DESC_MENU_112",    "Search Request");
define("STR_DESC_MENU_113",    "Search Consumption");
define("STR_DESC_MENU_114",    "Search Reports");
define("STR_DESC_MENU_115",    "Search Candidates' Reports");
define("STR_DESC_MENU_116",    "Search Email Sending");
define("STR_DESC_MENU_117",    "Search Email Template");
define("STR_DESC_MENU_120",    "Export Aptitude");
define("STR_DESC_MENU_134",    ":: Video tutorials");
define("STR_DESC_MENU_135",    "Export Personality");

define("STR_ID_TIPO_TPV"                         , "ID POS Terminal Type");
define("STR_TERMINAL_TYPE"                         , "TERMINAL TYPE");
define("STR_OPERATION_TYPE"                         , "OPERATION TYPE");
define("STR_URL_NOTIFY"                         , "URL NOTIFY");
define("STR_URL_OK"                         , "URL OK");
define("STR_URL_KO"                         , "URL KO");
define("STR_SERVICE_ACTION"                         , "SERVICE ACTiON");
define("STR_TIPOSTPV"                         , "Tipos POS Terminal");

define("STR_BUSINESS_NAME"                         , "BUSINESS NAME");
define("STR_BUSINESS_CODE"                         , "BUSINESS CODE");
define("STR_BUSINESS_PASS"                         , "BUSINESS PASS");
define("STR_BUSINESS_COINC"                         , "BUSINESS COINC");
define("STR_EMPRESASCONFTPV"                         , "FALTA CONTEXTO Empresas conf POS Terminal");	//-> Datos para configurar pasarela de pago mediante terminal bancario, de momento no está activo en enmpresa.


//FALTA CONTEXTO
//-> Estos literales son para la gestión de la pasarela TPV con el banco sólo para cliente ADEN
define("STR_ID_RECARGA"                         , "ID Recarga");
define("STR_LOCALIZADOR"                         , "Localizador");
define("STR_IMP_BASE"                         , "Imp. Base");
define("STR_IMP_IMPUESTOS"                         , "Imp. Impuestos");
define("STR_IMP_BASE_IMPUESTOS"                         , "Imp. Base Impuestos");
define("STR_APELLIDOS"                         , "Apellidos");
define("STR_COD_POSTAL"                         , "Cód. Postal");
define("STR_CIUDAD"                         , "Ciudad");
define("STR_COD_ESTADO"                         , "Cód. Estado");
define("STR_COD_AUTORIZACION"                         , "Cód. Autorización");
define("STR_COD_ERROR"                         , "Cód. Error");
define("STR_DESC_ERROR"                         , "Desc. Error");
define("STR_CANDIDATOSPAGOSTPV"                         , "Candidates paying through POS Terminal");
define("STR_TIPO_TPV"                         , "POS Terminal Type");
define("STR_DATOS_BANCARIOS",  "Data bank");
define("STR_CONTRATO",  "Contract");
define("STR_LOS_CAMPOS_MARCADOS_CON",  "Fields that are shaded in grey");
define("STR_OBLIGATORIOS",  "are mandatory");
define("STR_DATOS_PROCESO",  "Process Detail");
define("STR_COMUNICACION",  "Communication");
define("STR_SELECCIONA_EL_TIPO_DE_CARGA_DE_CANDIDATOS",  "Select upload candidates option");
define("STR_CARGA_MASIVA",  "Bulk Upload");
define("STR_ALTA_MANUAL",  "Manual Registration");
define("STR_IMPORTAR_CANDIDATOS",  "Import Candidates");
define("MSG_IMPORTAR_CANDIDATOS",  "Any type of <strong>CSV</strong> (comma delimited) file can be imported, choose the file and then click on <strong>add button</strong>. Here there are an example upload file:");
define("MSG_IMPORTAR_CANDIDATOS2",  "Upload a maximum of <strong>500 candidates</strong> the same time.");
define("MSG_IMPORTAR_CANDIDATOS3",  "Wait until upload resume appears");
define("MSG_VARIOS_MIN_NO_CIERRE",  "The process can take some minutes. <br />Do not close the browser");
define("STR_DE",  "of");
define("STR_ENVIADOS_CORRECTAMENTE",  "emails have been sent correctly");
define("STR_PUNTO_Y_COMA",  "Semicolon (;)");
define("STR_COMA",  "Comma (,)");
define("STR_PAGINA",  "Page");
define("STR_ENVIO_DE_CONTRASENAS",  "Sending Credentials");
define("STR_INDIVIDUALES",  "Individually");
define("STR_JUNTAS_EN_UN_SOLO_CORREO",  "Together in one email");
define("STR_PERÍODO_DE_TIEMPO",  "Date Range");
define("STR_SELECCIONE_UNA_EMPRESA_Y_PROCESO",  "Select Company and Process");

define("STR_REGISTROS_SE_EXPORTARAN",  "records will be exported");
define("STR_ESTA_OPERACION_PUEDE_DURAR_VARIOS_MINUTOS",  "This operation may take some minutes");
define("STR_PULSE_SOBRE_EL_ICONO_EXCEL_PARA_DESCARGAR_LAS_PUNTUACIONES",  "Click the Excel icon to download the file");

define("STR_RECHAZADA",  "Rejected");
define("STR_ACEPTADA",  "Accepted");
define("STR_PENDIENTE",  "Pending");
define("STR_NO_DISPONE_DE_SUFICIENTES_UNIDADES_PARA_EFECTUAR_LA_OPERACION",  "Doesn’t have enough units to complete the process");
define("STR_UNIDADES_DISPONIBLES",  "Available Units");
define("STR_UNIDADES_A_CONSUMIR",  "Consuming Units");
define("STR_POR_FAVOR_RECARGUE_UN_MINIMO_DE",  "Please reload at least");
define("STR_NO_HAY_RESULTADOS",  "No results were found");

define("STR_PUBLICAR"                         , "Publicar");
define("STR_ZONA_PUBLICACION"                         , "Zona Publicación");
define("STR_RECOMENDACIONES"                         , "Recomendaciones");

define("STR_FECHA_DE_PRUEBA"                         , "Assessment period");
define("STR_FECHA_DE_ALTA_PROCESO"                         , "Process Activation Date");
define("STR_CORRECTAS"                         , "Correct");
define("STR_CONTESTADAS"                         , "Replied");
define("STR_PERCENTIL"                         , "Percentile");
define("STR_POR"                         , "By");
define("STR_ESTILO"                         , "Style");
define("STR_IDFORMACION"                         , "IdFormación");
define("STR_EXPORTAPTITUDINALES"                         , "Export_aptitudinales");
define("STR_EXPORTPERSONALIDAD"                         , "Export_personalidad");

define("STR_DESCONTAR_UNIDADES_DE_LA_MATRIZ"                         , "Descontar Unidades de la Matriz");
define("STR_EL_REGISTRO_NO_EXISTE"					, "The record does not exist.");
define("STR_ALTAS_ANONIMAS", "Unknown candidates");
define("STR_TEXTO_ACCESO_BLIND"						, "To register in the process, fill in the following fields with your information.");
define("STR_NECESITA_AYUDA_SOPORTE_AL_USUARIO"						, "Do you need help? User support");
define("STR_AYUDA_AL_CANDIDATO"						, "Candidate Support");
define("STR_SOPORTE_AL_USUARIO"						, "User Support");
define("STR_CONTACTA_CON_NOSOTROS"						, "Contact us");
define("STR_EXPANDIR_TODO"						, "Expand all");
define("STR_CONTRAER_TODO"						, "Collapse all");
define("STR_MENSAJE"						, "Message");
define("STR_ENVIAR"						, "Send");
define("STR_AYUDA_A_LA_EMPRESA"						, "Client Support");

define("STR_INDICE_DE_RAPIDEZ"						, "Speed Index");
define("STR_INDICE_DE_PRECISION"						, "Precision Index");
if (!defined('STR_PRODUCTO_RENDIMIENTO')) {
	define("STR_PRODUCTO_RENDIMIENTO"						, "Product Performance");
}
define("STR_EMPRESA_A_LA_QUE_SE_LE_DESCUENTA"						, "Company whose units are deducted");
define("STR_PAIS_DE_PROCEDENCIA"                         , "Country of Origin");
define("STR_G_C"                         , "G.C.");

define("STR_ALTA_ANONIMA"                         , "Unknown candidate link");
define("STR_ESTA_SEGURO"                         , "Are you sure?");
define("STR_N_DE_ALTAS"                         , "Number of unknown candidates");
define("STR_TODAS", "All");
define("STR_AVISO_LEGAL"                         , "Legal");
define("STR_DESCARGAR_INFORMES"                         , "Download Reports");
define("STR_VOLVER_A_EVALUAR"                         , "Send a new assessment");
define("STR_VOLVER_A_EVALUAR_TODAS"                         , "Volver a evaluar todas");
define("STR_ZONA_HORARIA"                         , "Time zone");
define("STR_NO_DEFINIDA"                         , "Undefined");
define("STR_PERSONA_CONTACTO"                         , "Contact person");
define("STR_TLF_CONTACTO"                         , "Phone Contact");
define("STR_PROGRAMAR_ENVIO"                         , "Schedule mail delivery");
define("STR_REPLICADA"                         , "Replicada");
define("STR_CONFIRMACION"                         , "Confirm");
define("STR_ENVIO_PROGRAMADO"                         , "Scheduled shipping.");
define("STR_NOTIFICACION"                         , "Alert");
define("STR_NOTIFICACION_DE_ENVIO_PROGRAMADO"                         , "Scheduled shipping summary");
define("STR_PROCESO_CONFIDENCIAL"                         , "Confidential process");
?>
