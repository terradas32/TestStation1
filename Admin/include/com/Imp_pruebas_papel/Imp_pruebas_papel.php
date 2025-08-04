<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Imp_pruebas_papel.
**/
class Imp_pruebas_papel
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
		var $idFicheroSabana;
		var $idFicheroSabanaHast;
		var $idPrueba;
		var $idPruebaHast;
		var $idEmpresa;
		var $proceso;
		var $fichero;
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
		$this->idFicheroSabana			= "";
		$this->idFicheroSabanaHast			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->idEmpresa			= "";
		$this->proceso			= "";
		$this->fichero			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idFicheroSabana,idPrueba,fichero,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id Fichero Sabana,Prueba,Fichero,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idFicheroSabana
	* @return int(11)
	*/
	function getIdFicheroSabana(){
		return $this->idFicheroSabana;
	}
	/**
	* Fija el contenido de la propiedad idFicheroSabana
	* @param idFicheroSabana
	* @return void
	*/
	function setIdFicheroSabana($sCadena){
		$this->idFicheroSabana = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idFicheroSabanaHast
	* @return int(11)
	*/
	function getIdFicheroSabanaHast(){
		return $this->idFicheroSabanaHast;
	}
	/**
	* Fija el contenido de la propiedad idFicheroSabanaHast
	* @param idFicheroSabana
	* @return void
	*/
	function setIdFicheroSabanaHast($sCadena){
		$this->idFicheroSabanaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPrueba
	* @return int(11)
	*/
	function getIdPrueba(){
		return $this->idPrueba;
	}
	/**
	* Fija el contenido de la propiedad idPrueba
	* @param idPrueba
	* @return void
	*/
	function setIdPrueba($sCadena){
		$this->idPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPruebaHast
	* @return int(11)
	*/
	function getIdPruebaHast(){
		return $this->idPruebaHast;
	}
	/**
	* Fija el contenido de la propiedad idPruebaHast
	* @param idPrueba
	* @return void
	*/
	function setIdPruebaHast($sCadena){
		$this->idPruebaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idEmpresa
	* @return int(11)
	*/
	function getIdEmpresa(){
		return $this->idEmpresa;
	}
	/**
	* Fija el contenido de la propiedad idEmpresa
	* @param idEmpresa
	* @return void
	*/
	function setIdEmpresa($sCadena){
		$this->idEmpresa = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad proceso
	* @return int(11)
	*/
	function getProceso(){
		return $this->proceso;
	}
	/**
	* Fija el contenido de la propiedad proceso
	* @param proceso
	* @return void
	*/
	function setProceso($sCadena){
		$this->proceso = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad fichero
	* @return varchar(255)
	*/
	function getFichero(){
		return $this->fichero;
	}
	/**
	* Fija el contenido de la propiedad fichero
	* @param fichero
	* @return void
	*/
	function setFichero($sCadena){
		$this->fichero = $sCadena;
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
}//Fin de la Clase Imp_pruebas_papel
?>