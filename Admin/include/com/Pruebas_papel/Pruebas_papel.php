<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Pruebas_papel.
**/
class Pruebas_papel
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $iCont; //Contador Global
		var $aBusqueda; //Parámetros del buscador.
		var $sOrderBy; //Campo order de la query de búsqueda.
		var $sOrder; //Orden DESC ASC.
		var $sLineasPagina; //Líneas por página.
		var $PKListaExcel; //Campos de select para Excel
		var $DESCListaExcel; //Descripción a presentar en Excel
		var $id;
		var $idHast;
		var $CODIGO;
		var $EDAD;
		var $SEXO;
		var $NOMBRE;
		var $APELLIDO1;
		var $APELLIDO2;
		var $RESULTADO;
		var $ORDEN;
		var $ORDENHast;
		var $carga;
		var $fecAlta;
		var $fecAltaHast;
		var $fecMod;
		var $fecModHast;
		var $usuAlta;
		var $usuAltaHast;
		var $usuMod;
		var $usuModHast;
	/**
	* Constructor q inicializa los datos de la clase.
	* @param $conn			Conexión
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->id			= "";
		$this->idHast			= "";
		$this->CODIGO			= "";
		$this->EDAD			= "";
		$this->SEXO			= "";
		$this->NOMBRE			= "";
		$this->APELLIDO1			= "";
		$this->APELLIDO2			= "";
		$this->RESULTADO			= "";
		$this->ORDEN			= "";
		$this->ORDENHast			= "";
		$this->carga			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"id,CODIGO,EDAD,SEXO,NOMBRE,APELLIDO1,APELLIDO2,RESULTADO,ORDEN,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id,CODIGO,EDAD,SEXO,NOMBRE,APELLIDO 1,APELLIDO 2,RESULTADO,ORDEN,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad id
	* @return int(11)
	*/
	function getId(){
		return $this->id;
	}
	/**
	* Fija el contenido de la propiedad id
	* @param id
	* @return void
	*/
	function setId($sCadena){
		$this->id = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idHast
	* @return int(11)
	*/
	function getIdHast(){
		return $this->idHast;
	}
	/**
	* Fija el contenido de la propiedad idHast
	* @param id
	* @return void
	*/
	function setIdHast($sCadena){
		$this->idHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad CODIGO
	* @return varchar(255)
	*/
	function getCODIGO(){
		return $this->CODIGO;
	}
	/**
	* Fija el contenido de la propiedad CODIGO
	* @param CODIGO
	* @return void
	*/
	function setCODIGO($sCadena){
		$this->CODIGO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad EDAD
	* @return varchar(255)
	*/
	function getEDAD(){
		return $this->EDAD;
	}
	/**
	* Fija el contenido de la propiedad EDAD
	* @param EDAD
	* @return void
	*/
	function setEDAD($sCadena){
		$this->EDAD = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad SEXO
	* @return varchar(255)
	*/
	function getSEXO(){
		return $this->SEXO;
	}
	/**
	* Fija el contenido de la propiedad SEXO
	* @param SEXO
	* @return void
	*/
	function setSEXO($sCadena){
		$this->SEXO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad NOMBRE
	* @return varchar(255)
	*/
	function getNOMBRE(){
		return $this->NOMBRE;
	}
	/**
	* Fija el contenido de la propiedad NOMBRE
	* @param NOMBRE
	* @return void
	*/
	function setNOMBRE($sCadena){
		$this->NOMBRE = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad APELLIDO1
	* @return varchar(255)
	*/
	function getAPELLIDO1(){
		return $this->APELLIDO1;
	}
	/**
	* Fija el contenido de la propiedad APELLIDO1
	* @param APELLIDO1
	* @return void
	*/
	function setAPELLIDO1($sCadena){
		$this->APELLIDO1 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad APELLIDO2
	* @return varchar(255)
	*/
	function getAPELLIDO2(){
		return $this->APELLIDO2;
	}
	/**
	* Fija el contenido de la propiedad APELLIDO2
	* @param APELLIDO2
	* @return void
	*/
	function setAPELLIDO2($sCadena){
		$this->APELLIDO2 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad RESULTADO
	* @return varchar(2500)
	*/
	function getRESULTADO(){
		return $this->RESULTADO;
	}
	/**
	* Fija el contenido de la propiedad RESULTADO
	* @param RESULTADO
	* @return void
	*/
	function setRESULTADO($sCadena){
		$this->RESULTADO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ORDEN
	* @return int(11)
	*/
	function getORDEN(){
		return $this->ORDEN;
	}
	/**
	* Fija el contenido de la propiedad ORDEN
	* @param ORDEN
	* @return void
	*/
	function setORDEN($sCadena){
		$this->ORDEN = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ORDENHast
	* @return int(11)
	*/
	function getORDENHast(){
		return $this->ORDENHast;
	}
	/**
	* Fija el contenido de la propiedad ORDENHast
	* @param ORDEN
	* @return void
	*/
	function setORDENHast($sCadena){
		$this->ORDENHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad carga
	* @return int(11)
	*/
	function getCarga(){
		return $this->carga;
	}
	/**
	* Fija el contenido de la propiedad carga
	* @param carga
	* @return void
	*/
	function setCarga($sCadena){
		$this->carga = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad fecAlta
	* @return datetime
	*/
	function getFecAlta(){
		return $this->fecAlta;
	}
	/**
	* Fija el contenido de la propiedad fecAlta
	* @param fecAlta
	* @return void
	*/
	function setFecAlta($sCadena){
		$this->fecAlta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecAltaHast
	* @return datetime
	*/
	function getFecAltaHast(){
		return $this->fecAltaHast;
	}
	/**
	* Fija el contenido de la propiedad fecAltaHast
	* @param fecAlta
	* @return void
	*/
	function setFecAltaHast($sCadena){
		$this->fecAltaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecMod
	* @return datetime
	*/
	function getFecMod(){
		return $this->fecMod;
	}
	/**
	* Fija el contenido de la propiedad fecMod
	* @param fecMod
	* @return void
	*/
	function setFecMod($sCadena){
		$this->fecMod = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecModHast
	* @return datetime
	*/
	function getFecModHast(){
		return $this->fecModHast;
	}
	/**
	* Fija el contenido de la propiedad fecModHast
	* @param fecMod
	* @return void
	*/
	function setFecModHast($sCadena){
		$this->fecModHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuAlta
	* @return int(11)
	*/
	function getUsuAlta(){
		return $this->usuAlta;
	}
	/**
	* Fija el contenido de la propiedad usuAlta
	* @param usuAlta
	* @return void
	*/
	function setUsuAlta($sCadena){
		$this->usuAlta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuAltaHast
	* @return int(11)
	*/
	function getUsuAltaHast(){
		return $this->usuAltaHast;
	}
	/**
	* Fija el contenido de la propiedad usuAltaHast
	* @param usuAlta
	* @return void
	*/
	function setUsuAltaHast($sCadena){
		$this->usuAltaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuMod
	* @return int(11)
	*/
	function getUsuMod(){
		return $this->usuMod;
	}
	/**
	* Fija el contenido de la propiedad usuMod
	* @param usuMod
	* @return void
	*/
	function setUsuMod($sCadena){
		$this->usuMod = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuModHast
	* @return int(11)
	*/
	function getUsuModHast(){
		return $this->usuModHast;
	}
	/**
	* Fija el contenido de la propiedad usuModHast
	* @param usuMod
	* @return void
	*/
	function setUsuModHast($sCadena){
		$this->usuModHast = $sCadena;
	}
	/**
	* Devuelve el contenido de los parámetros de Búsqueda.
	* @return Array aBusqueda.
	*/
	function getBusqueda(){
		return $this->aBusqueda;
	}
	/**
	* Fija el contenido de los parámetros de Búsqueda.
	* @param Literal descriptivo del campo.
	* @param Valor del campo de Búsqueda.
	* @return void
	*/
	function setBusqueda($sLiteral, $sValor){
		if ($sValor != "") {
			$this->aBusqueda[$this->iCont][0] = $sLiteral;
			$this->aBusqueda[$this->iCont][1] = $sValor;
			$this->iCont++;
		}
	}
	/**
	* Devuelve el campo orden de la query.
	* @return String nombre campo.
	*/
	function getOrderBy(){
		return $this->sOrderBy;
	}
	/**
	* Fija el campo orden de la query.
	* @param String Nombre del campo.
	* @return void
	*/
	function setOrderBy($sCadena){
		$this->sOrderBy = $sCadena;
	}
	/**
	* Devuelve el tipo de orden de la query.
	* @return String orden del campo.
	*/
	function getOrder(){
		return $this->sOrder;
	}
	/**
	* Fija el tipo de orden de la query.
	* @param String orden del campo.
	* @return void
	*/
	function setOrder($sCadena){
		$this->sOrder = $sCadena;
	}
	/**
	* Devuelve el número de filas a pintar en la paginación.
	* @return Int número de líneas para la paginación.
	*/
	function getLineasPagina(){
		return $this->sLineasPagina;
	}
	/**
	* Fija el número de filas a pintar en la paginación.
	* @param Int orden del campo.
	* @return void
	*/
	function setLineasPagina($sCadena){
		$this->sLineasPagina = $sCadena;
	}
	/**
	* Devuelve los campos a seleccionar para excel.
	* @return String separado por comas de los campos.
	*/
	function getPKListaExcel(){
		return $this->PKListaExcel;
	}
	/**
	* Fija los campos a seleccionar para excel.
	* @param String separado por comas.
	* @return void
	*/
	function setPKListaExcel($sCadena){
		$this->PKListaExcel = $sCadena;
	}
	/**
	* Devuelve la descripción de los campos a seleccionar para excel.
	* @return String separado por comas de la descripción de los campos.
	*/
	function getDESCListaExcel(){
		return $this->DESCListaExcel;
	}
	/**
	* Fija la descripción de los campos a seleccionar para excel.
	* @param String separado por comas de la descripción de los campos.
	* @return void
	*/
	function setDESCListaExcel($sCadena){
		$this->DESCListaExcel = $sCadena;
	}
}//Fin de la Clase Pruebas_papel
?>