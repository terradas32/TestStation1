<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Competencias_items.
**/
class Competencias_items
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
		var $idTipoCompetencia;
		var $idTipoCompetenciaHast;
		var $idCompetencia;
		var $idCompetenciaHast;
		var $idPrueba;
		var $idPruebaHast;
		var $idItem;
		var $idItemHast;
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
		$this->idTipoCompetencia			= "";
		$this->idTipoCompetenciaHast			= "";
		$this->idCompetencia			= "";
		$this->idCompetenciaHast			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->idItem			= "";
		$this->idItemHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idTipoCompetencia,idCompetencia,idPrueba,idItem,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Tipo Competencía,Competencía,Prueba,Item,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idTipoCompetencia
	* @return int(11)
	*/
	function getIdTipoCompetencia(){
		return $this->idTipoCompetencia;
	}
	/**
	* Fija el contenido de la propiedad idTipoCompetencia
	* @param idTipoCompetencia
	* @return void
	*/
	function setIdTipoCompetencia($sCadena){
		$this->idTipoCompetencia = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoCompetenciaHast
	* @return int(11)
	*/
	function getIdTipoCompetenciaHast(){
		return $this->idTipoCompetenciaHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoCompetenciaHast
	* @param idTipoCompetencia
	* @return void
	*/
	function setIdTipoCompetenciaHast($sCadena){
		$this->idTipoCompetenciaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCompetencia
	* @return int(11)
	*/
	function getIdCompetencia(){
		return $this->idCompetencia;
	}
	/**
	* Fija el contenido de la propiedad idCompetencia
	* @param idCompetencia
	* @return void
	*/
	function setIdCompetencia($sCadena){
		$this->idCompetencia = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCompetenciaHast
	* @return int(11)
	*/
	function getIdCompetenciaHast(){
		return $this->idCompetenciaHast;
	}
	/**
	* Fija el contenido de la propiedad idCompetenciaHast
	* @param idCompetencia
	* @return void
	*/
	function setIdCompetenciaHast($sCadena){
		$this->idCompetenciaHast = $sCadena;
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
	* Devuelve el contenido de la propiedad idItem
	* @return int(11)
	*/
	function getIdItem(){
		return $this->idItem;
	}
	/**
	* Fija el contenido de la propiedad idItem
	* @param idItem
	* @return void
	*/
	function setIdItem($sCadena){
		$this->idItem = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idItemHast
	* @return int(11)
	*/
	function getIdItemHast(){
		return $this->idItemHast;
	}
	/**
	* Fija el contenido de la propiedad idItemHast
	* @param idItem
	* @return void
	*/
	function setIdItemHast($sCadena){
		$this->idItemHast = $sCadena;
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
}//Fin de la Clase Competencias_items
?>