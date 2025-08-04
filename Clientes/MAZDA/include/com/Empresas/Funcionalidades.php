<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Funcionalidades.
**/
class Funcionalidades
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $iCont; //Contador Global
		var $aBusqueda; //Parámetros del buscador.
		var $sOrderBy; //Campo order de la query de búsqueda.
		var $sOrder; //Orden DESC ASC.
		var $sLineasPagina; //Líneas por página.
		var $idFuncionalidad;
		var $nombre;
		var $descripcion;
		var $idPadre;
		var $url;
		var $popUp;
		var $orden;
		var $DentroDe;
		var $DespuesDe;
		var $indentacion;
		var $bgFile;
		var $bgColor;
		var $modoDefecto;
		var $iconosMenu;
		var $publico;
		var $fecAlta;
		var $fecMod;
		var $usuAlta;
		var $usuMod;
	/**
	* Constructor q inicializa los datos de la clase.
	* @param $conn			Conexión
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda		= array();
		$this->idFuncionalidad	= "";
		$this->nombre			= "";
		$this->descripcion		= "";
		$this->idPadre			= "";
		$this->url				= "";
		$this->popUp			= "";
		$this->orden			= "";
		$this->DentroDe			= "";
		$this->DespuesDe		= "";
		$this->indentacion		= "";
		$this->bgFile			= "";
		$this->bgColor			= "";
		$this->modoDefecto		= "";
		$this->iconosMenu		= "";
		$this->publico			= "";
		$this->fecAlta			= "";
		$this->fecMod			= "";
		$this->usuAlta			= "";
		$this->usuMod			= "";
	}


	/**
	* Devuelve el contenido de la propiedad idFuncionalidad
	* @return int(11)
	*/
	function getIdFuncionalidad(){
		return $this->idFuncionalidad;
	}
	/**
	* Fija el contenido de la propiedad idFuncionalidad
	* @param idFuncionalidad
	* @return void
	*/
	function setIdFuncionalidad($sCadena){
		$this->idFuncionalidad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nombre
	* @return varchar(255)
	*/
	function getNombre(){
		return $this->nombre;
	}
	/**
	* Fija el contenido de la propiedad nombre
	* @param nombre
	* @return void
	*/
	function setNombre($sCadena){
		$this->nombre = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descripcion
	* @return varchar(255)
	*/
	function getDescripcion(){
		return $this->descripcion;
	}
	/**
	* Fija el contenido de la propiedad descripcion
	* @param descripcion
	* @return void
	*/
	function setDescripcion($sCadena){
		$this->descripcion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPadre
	* @return int(11)
	*/
	function getIdPadre(){
		return $this->idPadre;
	}
	/**
	* Fija el contenido de la propiedad idPadre
	* @param idPadre
	* @return void
	*/
	function setIdPadre($sCadena){
		$this->idPadre = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad url
	* @return varchar(255)
	*/
	function getUrl(){
		return $this->url;
	}
	/**
	* Fija el contenido de la propiedad url
	* @param url
	* @return void
	*/
	function setUrl($sCadena){
		$this->url = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad popUp
	* @return char(2)
	*/
	function getPopUp(){
		return $this->popUp;
	}
	/**
	* Fija el contenido de la propiedad popUp
	* @param popUp
	* @return void
	*/
	function setPopUp($sCadena){
		$this->popUp = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad orden
	* @return int(11)
	*/
	function getOrden(){
		return $this->orden;
	}
	/**
	* Fija el contenido de la propiedad orden
	* @param orden
	* @return void
	*/
	function setOrden($sCadena){
		$this->orden = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DentroDe
	* @return int(11)
	*/
	function getDentroDe(){
		return $this->DentroDe;
	}
	/**
	* Fija el contenido de la propiedad DentroDe
	* @param DentroDe
	* @return void
	*/
	function setDentroDe($sCadena){
		$this->DentroDe = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DespuesDe
	* @return int(11)
	*/
	function getDespuesDe(){
		return $this->DespuesDe;
	}
	/**
	* Fija el contenido de la propiedad DespuesDe
	* @param DespuesDe
	* @return void
	*/
	function setDespuesDe($sCadena){
		$this->DespuesDe = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad indentacion
	* @return int(11)
	*/
	function getIndentacion(){
		return $this->indentacion;
	}
	/**
	* Fija el contenido de la propiedad indentacion
	* @param indentacion
	* @return void
	*/
	function setIndentacion($sCadena){
		$this->indentacion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad bgFile
	* @return varchar(255)
	*/
	function getBgFile(){
		return $this->bgFile;
	}
	/**
	* Fija el contenido de la propiedad bgFile
	* @param bgFile
	* @return void
	*/
	function setBgFile($sCadena){
		$this->bgFile = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad bgColor
	* @return varchar(7)
	*/
	function getBgColor(){
		return $this->bgColor;
	}
	/**
	* Fija el contenido de la propiedad bgColor
	* @param bgColor
	* @return void
	*/
	function setBgColor($sCadena){
		$this->bgColor = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad modoDefecto
	* @return varchar(7)
	*/
	function getModoDefecto(){
		return $this->modoDefecto;
	}
	/**
	* Fija el contenido de la propiedad modoDefecto
	* @param modoDefecto
	* @return void
	*/
	function setModoDefecto($sCadena){
		$this->modoDefecto = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad iconosMenu
	* @return varchar(7)
	*/
	function getIconosMenu(){
		return $this->iconosMenu;
	}
	/**
	* Fija el contenido de la propiedad iconosMenu
	* @param iconosMenu
	* @return void
	*/
	function setIconosMenu($sCadena){
		$this->iconosMenu = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad publico
	* @return varchar(7)
	*/
	function getPublico(){
		return $this->publico;
	}
	/**
	* Fija el contenido de la propiedad publico
	* @param publico
	* @return void
	*/
	function setPublico($sCadena){
		$this->publico = $sCadena;
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
	* @param fecAltaHast
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
	* @param fecModHast
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
}//Fin de la Clase Funcionalidades
?>