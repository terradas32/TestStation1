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
		$sPrueba = "";
		while ($row = $result->FetchRow())
		{
/*			if ($xExcel>1){
				break;
			}
*/
			$sPrueba .= "<br />**FILA " . $xExcel . " ::: "; 
			for ($i = 0; $i < $iColTabla; $i++)
			{
				$nombrefield=$result->FetchField($i);
				$sPrueba .= utf8_decode(str_replace(array("\r", "\n"), array("", " "), strip_tags(html_entity_decode($row[$nombrefield->name],ENT_QUOTES,"UTF-8"))));
				$binDatosExcel .= $this->getContenido($xExcel, $i, utf8_decode(str_replace(array("\r", "\n"), array("", " "), strip_tags(html_entity_decode($row[$nombrefield->name],ENT_QUOTES,"UTF-8")))));
			}
			$xExcel++;
		}
//		echo $sPrueba;
//		exit;
		$binDatos = $this->ExcelStart() . $binCabeceraExcel . $binDatosExcel . $this->ExcelEnd();
		$this->GeneraXLS($sNombreFicheroExcel, $binDatos );
	}

	// Monta cabecera del archivo(tipo xls)
	function ExcelStart()
	{
		//inicio del archivo excel
		return pack( "vvvvvv", 0x809, 0x08, 0x00,0x10, 0x0, 0x0 ); //Almacena los datos en binario
	}
	// Contenido para Excel
	function getContenido( $lineaExcel, $columnaExcel, $sValor)
	{
		$binDatos = "";
		$iTamanio = strlen( $sValor );
		$binDatos = pack( "v*", 0x0204, 8 + $iTamanio, $lineaExcel, $columnaExcel, 0x00, $iTamanio ); //Almacena los datos en binario
		$binDatos .= $sValor;
		return $binDatos;
	}

	// Gera arquivo(xls)
	function GeneraXLS($sNombreFicheroExcel, $binDatosExcel ){
		header ( "Expires: 0");
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/octet-stream; name=" . $sNombreFicheroExcel . ".xls");
		header ( "Content-Disposition: attachment; filename=" . $sNombreFicheroExcel . ".xls"); 
		header ( "Content-Description: MID Gera excel" );
		print  ( $binDatosExcel);
	}

	// Final del archivo Excel
	function ExcelEnd(){
		return pack( "vv", 0x0A, 0x00); //Final binario del archivo Excel
	}
}	//Fin de la clase ToXLS
?>
