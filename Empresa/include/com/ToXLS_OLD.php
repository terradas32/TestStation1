<?php
/**
* Realiza la operaciones de contruir un fichero
* XLS a partir se una SQL.
**/
class ToXLS
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $conn; //Conexión con la BBDD
		var $sSQL; //última query ejecutada
		var $msg_Error; //Array con los mensajes de Warning y Errores

	/**
	* Crea un objeto de la clase y almacena en él la 
	* conexión que utilizará con la base de datos
	* @param conn Conexion a traves de la cual
	* realizar las operaciones sobre la base de datos
	**/
	function ToXLS(&$conn)
	{
		$this->conn			= $conn;
		$this->msg_Error	= array();
	}

	/***********************************************************************
	* Inserta una entidad en la base de datos.
	* @param entidad Entidad a insertar con Datos
	* @return long Numero de ID de la entidad
	* @exception Exception Error al ejecutar la acción
	*  en la base de datos
	***********************************************************************/
	function crearXLS(	$sql, $sNombreFicheroExcel = "ExcelFile", $bCabecera = true,
						$bSepararCabecera = false, $DESCListaExcel="")
	{
		$aux				= $this->conn;
		$binDatos			= "";
		$binCabeceraExcel	= "";
		$binDatosExcel		= "";
		$xExcel				= 0;
		$result = $aux->Execute($sql);
		$iColTabla = $result->FieldCount();
		if ($bCabecera){
			if (empty($DESCListaExcel)){
				for ($i = 0; $i < $iColTabla; $i++){
					$nombrefield=$result->FetchField($i);
					$binCabeceraExcel .= $this->getContenido($xExcel, $i, trim(ucwords(utf8_decode(str_replace("_"," ", $nombrefield->name)))));
				}
			}else{
				$aDESCListaExcel = explode(',', $DESCListaExcel);
				for ($i = 0; $i < $iColTabla; $i++){
					$nombrefield=$aDESCListaExcel[$i];
					$binCabeceraExcel .= $this->getContenido($xExcel, $i, trim(ucwords(utf8_decode(str_replace("_"," ", $nombrefield)))));
				}
			}
			$xExcel++;
		}
		// Línea en blanco de separacion con cabecera o
		// línea inicial en blanco si no hay cabecera
		if ($bSepararCabecera){
			$binCabeceraExcel .= $this->getContenido($xExcel, 0, "   ");
			$xExcel++;
		}
		while ($row = $result->FetchRow())
		{
			for ($i = 0; $i < $iColTabla; $i++)
			{
				$nombrefield=$result->FetchField($i);
				$sDato= str_replace(array("\r", "\n", "<br>", "<br />"), array("", chr(10), chr(10), chr(10)), html_entity_decode(strip_tags($row[$nombrefield->name], "<br><br />"),ENT_QUOTES,"UTF-8" ));
				if (!empty($sDato)){

					$sDato = utf8_decode(substr($sDato, 0, 255 )); //En textos muy grandes casca
				}
				$binDatosExcel .= $this->getContenido($xExcel, $i, $sDato);
			}
			$xExcel++;
		}
		$binDatos = $this->ExcelStart() . $binCabeceraExcel . $binDatosExcel . $this->ExcelEnd();
		$this->GeneraXLS($sNombreFicheroExcel, $binDatos );
	}

	// Monta cabecera del archivo(tipo xls)
	function ExcelStart()
	{
		//inicio del archivo excel
		return pack("S*", 0x809, 8, 0,0x10, 0, 0);
	}
	// Contenido para Excel

	/*** str BuildCell(RowNo, ColNo, Cell) ***************************************
	Devuelve los datos formateados deacuerdo a que sean de tipo(int, float, string, boolean)
	- lineaExcel   : Linea del Excel
	- columnaExcel   : Columna del Excel
	- sValor    : Dato a convertir a formato xls
	- RETURN  : Pieza de datos formateada para la celda
	*****************************************************************************/
	private function getContenido($lineaExcel, $columnaExcel, $sValor){
		if(is_numeric($sValor)){
			return(pack("S*", 0x0203, 14, $lineaExcel, $columnaExcel, 0x00).pack("d", $sValor));
		}
		if(is_int($sValor)){
			return(pack("S*", 0x027E, 10, $lineaExcel, $columnaExcel, 0x00).pack("I", ($sValor<<2) | 2));
		}
		if(is_float($sValor)){
			return(pack("S*", 0x0203, 14, $lineaExcel, $columnaExcel, 0x00).pack("d", $sValor));
		}
		if(is_bool($sValor)){
			return($this->BuildCell($lineaExcel, $columnaExcel, (int)($sValor ? 1 : 0)));
		}
		if(is_string($sValor)) {
			$Len_ = strlen($sValor);
			return(pack("S*", 0x0204, $Len_ + 8, $lineaExcel, $columnaExcel, 0x00, $Len_).$sValor);
		}

		// Returns a null cell
		return(pack("S*", 0x0201, 6, $lineaExcel, $columnaExcel, 0x17));
	}
	// Genera arquivo(xls)
	function GeneraXLS($sNombreFicheroExcel, $binDatosExcel ){
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Content-Description: NegociaInternet Generador de XLS");
		header ("Content-Disposition: attachment; filename=" . $sNombreFicheroExcel . ".xls");
		header ('Content-Transfer-Encoding: binary');
		header ('Content-Type: application/force-download');
		header ('Content-Type: application/octet-stream');
		header ("Content-type: application/x-msexcel");
		header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ("Pragma: no-cache");
		print  ( $binDatosExcel);
	}

	// Final del archivo Excel
	function ExcelEnd(){
		 //Final binario del archivo Excel
		return pack('S*', 0x0A, 0);
	}
}	//Fin de la clase ToXLS
?>
