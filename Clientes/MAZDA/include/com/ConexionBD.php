<?php
class ConexionBD{
	/* variables de conexión */
	var $BaseDatos;
	var $Servidor;
	var $Usuario;
	var $Clave;
	
	/* identificador de conexión y consulta */
	var $Conexion_ID = 0;
	var $Consulta_ID = 0;
	
	/* número de error y texto error */
	var $Errno = 0;
	var $Error = "<br><br><br><br><br><br>Se produjo un error al ejecutar la consulta.";
	
	/* Método Constructor: Cada vez que creemos una variable de esta clase,
	se ejecutará esta función */
	function __construct($bd,$host,$user,$pass) {
		$this->BaseDatos = $bd;
		$this->Servidor = $host;
		$this->Usuario = $user;
		$this->Clave = $pass;
	}
	/*Conexión a la base de datos*/
	function conectar(){
		// Conectamos al servidor
		$this->Conexion_ID = mysql_connect($this->Servidor, $this->Usuario, $this->Clave);
		if (!$this->Conexion_ID) {
			$this->Error = "<br><br><br><br><br><br>Ha fallado la conexión.";
			return 0;
		}
		//seleccionamos la base de datos
		if (!@mysql_select_db($this->BaseDatos, $this->Conexion_ID)) {
			$this->Error = "<br><br><br><br><br><br>Imposible abrir ".$this->BaseDatos ;
			return 0;
		}
		/* Si hemos tenido éxito conectando devuelve 
		el identificador de la conexión, sino devuelve 0 */
		return $this->Conexion_ID;
	}

	 /* Ejecuta un consulta */
	function consulta($sql){
		if (empty($sql)) {
			$this->Error = "<br><br><br><br><br><br>No ha especificado una consulta SQL";
			return 0;
		}
		$this->Consulta_ID = @mysql_query($sql, $this->Conexion_ID);
		if (!$this->Consulta_ID) {
			$this->Errno = mysql_errno();
			$this->Error = mysql_error();
		}else{
		}
		return $this->Consulta_ID;
	}
	/* Devuelve el número de campos de una consulta */
	function numcampos() {
		return mysql_num_fields($this->Consulta_ID);
	}
	/* Devuelve el número de registros de una consulta */
	function numregistros(){
		return mysql_num_rows($this->Consulta_ID);
	}
	/* Devuelve el nombre de un campo de una consulta */
	function nombrecampo($numcampo) {
		return mysql_field_name($this->Consulta_ID, $numcampo);
	}

	function verconsulta() {
		echo "<table border=1>\n";
		// mostramos los nombres de los campos
		for ($i = 0; $i < $this->numcampos(); $i++){
			echo "<td><b>".$this->nombrecampo($i)."</b></td>\n";
		}
		echo "</tr>\n";
		// mostrarmos los registros
		while ($row = mysql_fetch_row($this->Consulta_ID)) {
			echo "<tr> \n";
			for ($i = 0; $i < $this->numcampos(); $i++){
				echo "<td>".$row[$i]."</td>\n";
			}
			echo "</tr>\n";
		}
	}
	
	/**********************************************
	 * Convierte una cadena String a formato de SQL.
	 * @param sCadena Cadena a convertir.
	 * @return String Cadena convertida.
	 *********************************************/
	function cChar($sCadena,$comillas=true)
	{ 
		//return ("'" . htmlspecialchars($sCadena) . "'");
		$sChars = array();
			$sChars[0] = '&';
			$sChars[1] = '\"';
			$sChars[2] = '<';
			$sChars[3] = '>';
			$sChars[4] = '|';
			$sChars[5] = '\'';

		$sScapes = array();
			$sScapes[0] = "&amp;";
			$sScapes[1] = "&quot;";
			$sScapes[2] = "&lt;";
			$sScapes[3] = "&gt;";
			$sScapes[4] = "&brvbar;";
			$sScapes[5] = "&rsquo;";

		for ($j = 0; $j < sizeof($sChars); $j++)
		{
			$sCadena = str_replace($sChars[$j], $sScapes[$j], $sCadena);
		}
		if ($comillas)
			return ("'" . $sCadena . "'");
		else return $sCadena;
	}
	function cHTML($sCadena)
	{
		return (htmlspecialchars($sCadena));
	}
	/**********************************************
	 * Convierte una cadena String quitando los acentos.
	 * @param sCadena Cadena a convertir.
	 * @return String Cadena convertida.
	 *********************************************/
	function cQuitaAcentos($sCadena)
	{ 
		$sChars = array();
			$sChars[0] = 'á';
			$sChars[1] = 'é';
			$sChars[2] = 'í';
			$sChars[3] = 'ó';
			$sChars[4] = 'ú';
			$sChars[5] = 'Á';
			$sChars[6] = 'É';
			$sChars[7] = 'Í';
			$sChars[8] = 'Ó';
			$sChars[9] = 'Ú';
			$sChars[10] = 'ä';
			$sChars[11] = 'ë';
			$sChars[12] = 'ï';
			$sChars[13] = 'ö';
			$sChars[14] = 'ü';
			$sChars[15] = 'Ä';
			$sChars[16] = 'Ë';
			$sChars[17] = 'Ï';
			$sChars[18] = 'Ö';
			$sChars[19] = 'Ü';
			$sChars[20] = 'à';
			$sChars[21] = 'è';
			$sChars[22] = 'ì';
			$sChars[23] = 'ò';
			$sChars[24] = 'ù';
			$sChars[25] = 'À';
			$sChars[26] = 'È';
			$sChars[27] = 'Ì';
			$sChars[28] = 'Ò';
			$sChars[29] = 'Ù';
			$sChars[30] = 'â';
			$sChars[31] = 'ê';
			$sChars[32] = 'î';
			$sChars[33] = 'ô';
			$sChars[34] = 'û';
			$sChars[35] = 'Â';
			$sChars[36] = 'Ê';
			$sChars[37] = 'Î';
			$sChars[38] = 'Ô';
			$sChars[39] = 'Û';
			$sChars[40] = 'º';
			$sChars[41] = 'ª';
			$sChars[42] = 'ñ';
		
		$sScapes = array();
			$sScapes[0] = 'a';
			$sScapes[1] = 'e';
			$sScapes[2] = 'i';
			$sScapes[3] = 'o';
			$sScapes[4] = 'u';
			$sScapes[5] = 'A';
			$sScapes[6] = 'E';
			$sScapes[7] = 'I';
			$sScapes[8] = 'O';
			$sScapes[9] = 'U';
			$sScapes[10] = 'a';
			$sScapes[11] = 'e';
			$sScapes[12] = 'i';
			$sScapes[13] = 'o';
			$sScapes[14] = 'u';
			$sScapes[15] = 'A';
			$sScapes[16] = 'E';
			$sScapes[17] = 'I';
			$sScapes[18] = 'O';
			$sScapes[19] = 'U';
			$sScapes[20] = 'a';
			$sScapes[21] = 'e';
			$sScapes[22] = 'i';
			$sScapes[23] = 'o';
			$sScapes[24] = 'u';
			$sScapes[25] = 'A';
			$sScapes[26] = 'E';
			$sScapes[27] = 'I';
			$sScapes[28] = 'O';
			$sScapes[29] = 'U';
			$sScapes[30] = 'a';
			$sScapes[31] = 'e';
			$sScapes[32] = 'i';
			$sScapes[33] = 'o';
			$sScapes[34] = 'u';
			$sScapes[35] = 'A';
			$sScapes[36] = 'E';
			$sScapes[37] = 'I';
			$sScapes[38] = 'O';
			$sScapes[39] = 'U';
			$sScapes[40] = 'o';
			$sScapes[41] = 'a';
			$sScapes[42] = 'n';

		for ($j = 0; $j < sizeof($sChars); $j++)
		{
			$sCadena = str_replace($sChars[$j], $sScapes[$j], $sCadena);
		}
			return ($sCadena);
	}
	function verificar_url($url)
	{
	   //abrimos el archivo en lectura
	   $id = @fopen($url,"r");
	   //hacemos las comprobaciones
	   if ($id) $abierto = true;
	   else $abierto = false;
	   //devolvemos el valor
	   return $abierto;
	   //cerramos el archivo
	   fclose($id);
	}
	function thumb($imagewidth=150,$imageheight=150,$ancho)
	{
		$imagemaxwidth = ($ancho != "") ? $ancho : $imagewidth;
		$imagemaxheight = $imageheight;
		$imagemaxratio =  $imagemaxwidth / $imagemaxheight;
		$imageratio = $imagewidth / $imageheight;
		
		if ($imageratio > $imagemaxratio){  
			$imageoutputwidth = $imagemaxwidth;  
		    $imageoutputheight = ceil ($imagemaxwidth/$imagewidth*$imageheight);  
		}else if ($imageratio < $imagemaxratio){  
			$imageoutputheight = $imagemaxheight;  
			$imageoutputwidth = ceil ($imagemaxheight/$imageheight*$imagewidth);  
		}else{  
			$imageoutputwidth = $imagemaxwidth;  
			$imageoutputheight = $imagemaxheight;  
		}
		$retorno[0] = $imageoutputwidth;
		$retorno[1] = $imageoutputheight;
		return $retorno;
	}
	/**********************************************
	 * Prepara una cadena para ser insertada en la DB.
	 * @param sCadena Cadena a convertir.
	 * @return String Cadena convertida.
	 *********************************************/
	function toSQL($sCadena)
	{ 
			return "'" . addslashes($sCadena) . "'";
	}
	function toFORM($sCadena)
	{ 
			return  htmlspecialchars(stripslashes($sCadena));
	}
	
}//fin de la Clase ConexionBD
function consulta_array($db_query)
{
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
 }
?>
