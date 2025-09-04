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
	function __construct(&$conn)
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
					$binCabeceraExcel .= '"' . trim(ucwords(utf8_decode(str_replace("_"," ", $nombrefield->name)))) . '";';
				}
			}else{
				$aDESCListaExcel = explode(',', $DESCListaExcel);
				for ($i = 0; $i < $iColTabla; $i++){
					$nombrefield=$aDESCListaExcel[$i];
					$binCabeceraExcel .= '"' . trim(ucwords(utf8_decode(str_replace("_"," ", $nombrefield)))) . '";';
				}
			}
			$xExcel++;
		}
		// Línea en blanco de separacion con cabecera o

		//if ($bSepararCabecera){
			$binCabeceraExcel .="\n";
			$xExcel++;
		//}
		while ($row = $result->FetchRow())
		{
			for ($i = 0; $i < $iColTabla; $i++)
			{
				$nombrefield=$result->FetchField($i);
				$sDato= str_replace(array("\r", "\n", "<br>", "<br />", '"'), array("", chr(10), chr(10), chr(10), "''"), html_entity_decode(strip_tags($row[$nombrefield->name], "<br><br />"),ENT_QUOTES,"UTF-8" ));
				$binDatosExcel .='"' . $sDato . '";';
			}
			$xExcel++;
			$binDatosExcel .="\n";
		}
		$this->GeneraXLS($sNombreFicheroExcel, $binCabeceraExcel . utf8_decode($binDatosExcel) );
	}

	// Genera arquivo(csv)
	function GeneraXLS($sNombreFicheroExcel, $binDatosExcel ){
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Content-Description: Generador de CSV");
		header ("Content-Disposition: attachment; filename=" . $sNombreFicheroExcel . ".csv");
		header ('Content-Transfer-Encoding: binary');
		header ('Content-Type: application/force-download');
		header ('Content-Type: application/octet-stream');
		//header ("Content-type: application/x-msexcel");
		header ("Content-type: text/x-csv");
		header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ("Pragma: no-cache");
		print  ( $binDatosExcel);
	}

}	//Fin de la clase ToXLS
?>
