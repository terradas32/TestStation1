<?php

/**
* Crea un objeto de la clase y almacena en él
* los valores de la entidad de clase Web_slider.
**/
class Web_slider
{

	/**
	* Declaración de las variables de Entidad.
	**/
		protected $iCont; //Contador Global
		protected $aBusqueda; //Parámetros del buscador.
		protected $sGroupBy; //Campo group de la query de búsqueda.
		protected $sOrderBy; //Campo order de la query de búsqueda.
		protected $sOrder; //Orden DESC ASC.
		protected $sLineasPagina; //Líneas por página.
		protected $PKListaExcel; //Campos de select para Excel
		protected $DESCListaExcel; //Descripción a presentar en Excel
		protected $imgMultiProp; //Imagen MultiUploas
		protected $idSlider;
		protected $idSliderHast;
		protected $code;
		protected $titulo;
		protected $pathImagen;
		protected $urlDestino;
		protected $orden;
		protected $ordenHast;
		protected $fecAlta;
		protected $fecAltaHast;
		protected $fecMod;
		protected $fecModHast;
		protected $usuAlta;
		protected $usuAltaHast;
		protected $usuMod;
		protected $usuModHast;
	/**
	* Constructor q inicializa los datos de la clase.
	* @param $conn			Conexión
	**/
	public function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->idSlider			= "";
		$this->idSliderHast			= "";
		$this->code			= "";
		$this->titulo			= "";
		$this->pathImagen			= "";
		$this->urlDestino			= "";
		$this->orden			= "";
		$this->ordenHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->imgMultiProp		= "";
		$this->PKListaExcel		=	"idSlider,code,titulo,pathImagen,urlDestino,orden";
		$this->DESCListaExcel	=	"Slider,Code,Título,Imagen adjunta,Url Destino,Orden";
	}


	/**
	* Devuelve el contenido de la propiedad idSlider
	* @return int(11)
	*/
	public function getIdSlider(){
		return $this->idSlider;
	}
	/**
	* Fija el contenido de la propiedad idSlider
	* @param idSlider
	* @return void
	*/
	public function setIdSlider($sCadena){
		$this->idSlider = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idSliderHast
	* @return int(11)
	*/
	public function getIdSliderHast(){
		return $this->idSliderHast;
	}
	/**
	* Fija el contenido de la propiedad idSliderHast
	* @param idSlider
	* @return void
	*/
	public function setIdSliderHast($sCadena){
		$this->idSliderHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad code
	* @return varchar(255)
	*/
	public function getCode(){
		return $this->code;
	}
	/**
	* Fija el contenido de la propiedad code
	* @param code
	* @return void
	*/
	public function setCode($sCadena){
		$this->code = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad titulo
	* @return varchar(255)
	*/
	public function getTitulo(){
		return $this->titulo;
	}
	/**
	* Fija el contenido de la propiedad titulo
	* @param titulo
	* @return void
	*/
	public function setTitulo($sCadena){
		$this->titulo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad pathImagen
	* @return varchar(255)
	*/
	public function getPathImagen(){
		return $this->pathImagen;
	}
	/**
	* Fija el contenido de la propiedad pathImagen
	* @param pathImagen
	* @return void
	*/
	public function setPathImagen($sCadena){
		$this->pathImagen = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad urlDestino
	* @return varchar(255)
	*/
	public function getUrlDestino(){
		return $this->urlDestino;
	}
	/**
	* Fija el contenido de la propiedad urlDestino
	* @param urlDestino
	* @return void
	*/
	public function setUrlDestino($sCadena){
		$this->urlDestino = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad orden
	* @return int(11)
	*/
	public function getOrden(){
		return $this->orden;
	}
	/**
	* Fija el contenido de la propiedad orden
	* @param orden
	* @return void
	*/
	public function setOrden($sCadena){
		$this->orden = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ordenHast
	* @return int(11)
	*/
	public function getOrdenHast(){
		return $this->ordenHast;
	}
	/**
	* Fija el contenido de la propiedad ordenHast
	* @param orden
	* @return void
	*/
	public function setOrdenHast($sCadena){
		$this->ordenHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecAlta
	* @return datetime
	*/
	public function getFecAlta(){
		return $this->fecAlta;
	}
	/**
	* Fija el contenido de la propiedad fecAlta
	* @param fecAlta
	* @return void
	*/
	public function setFecAlta($sCadena){
		$this->fecAlta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecAltaHast
	* @return datetime
	*/
	public function getFecAltaHast(){
		return $this->fecAltaHast;
	}
	/**
	* Fija el contenido de la propiedad fecAltaHast
	* @param fecAlta
	* @return void
	*/
	public function setFecAltaHast($sCadena){
		$this->fecAltaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecMod
	* @return datetime
	*/
	public function getFecMod(){
		return $this->fecMod;
	}
	/**
	* Fija el contenido de la propiedad fecMod
	* @param fecMod
	* @return void
	*/
	public function setFecMod($sCadena){
		$this->fecMod = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecModHast
	* @return datetime
	*/
	public function getFecModHast(){
		return $this->fecModHast;
	}
	/**
	* Fija el contenido de la propiedad fecModHast
	* @param fecMod
	* @return void
	*/
	public function setFecModHast($sCadena){
		$this->fecModHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuAlta
	* @return int(11)
	*/
	public function getUsuAlta(){
		return $this->usuAlta;
	}
	/**
	* Fija el contenido de la propiedad usuAlta
	* @param usuAlta
	* @return void
	*/
	public function setUsuAlta($sCadena){
		$this->usuAlta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuAltaHast
	* @return int(11)
	*/
	public function getUsuAltaHast(){
		return $this->usuAltaHast;
	}
	/**
	* Fija el contenido de la propiedad usuAltaHast
	* @param usuAlta
	* @return void
	*/
	public function setUsuAltaHast($sCadena){
		$this->usuAltaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuMod
	* @return int(11)
	*/
	public function getUsuMod(){
		return $this->usuMod;
	}
	/**
	* Fija el contenido de la propiedad usuMod
	* @param usuMod
	* @return void
	*/
	public function setUsuMod($sCadena){
		$this->usuMod = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad usuModHast
	* @return int(11)
	*/
	public function getUsuModHast(){
		return $this->usuModHast;
	}
	/**
	* Fija el contenido de la propiedad usuModHast
	* @param usuMod
	* @return void
	*/
	public function setUsuModHast($sCadena){
		$this->usuModHast = $sCadena;
	}
	/**
	* Devuelve el contenido de los parámetros de Búsqueda.
	* @return Array aBusqueda.
	*/
	public function getBusqueda(){
		return $this->aBusqueda;
	}
	/**
	* Fija el contenido de los parámetros de Búsqueda.
	* @param Literal descriptivo del campo.
	* @param Valor del campo de Búsqueda.
	* @return void
	*/
	public function setBusqueda($sLiteral, $sValor){
		if ($sValor != "") {
			$this->aBusqueda[$this->iCont][0] = $sLiteral;
			$this->aBusqueda[$this->iCont][1] = $sValor;
			$this->iCont++;
		}
	}
	/**
	* Devuelve el campo group de la query.
	* @return String nombre campo.
	*/
	public function getGroupBy(){
		return $this->sGroupBy;
	}
	/**
	* Fija el campo group de la query.
	* @param String Nombre del campo.
	* @return void
	*/
	public function setGroupBy($sCadena){
		$this->sGroupBy = $sCadena;
	}
	/**
	* Devuelve el campo orden de la query.
	* @return String nombre campo.
	*/
	public function getOrderBy(){
		return $this->sOrderBy;
	}
	/**
	* Fija el campo orden de la query.
	* @param String Nombre del campo.
	* @return void
	*/
	public function setOrderBy($sCadena){
		$this->sOrderBy = $sCadena;
	}
	/**
	* Devuelve el tipo de orden de la query.
	* @return String orden del campo.
	*/
	public function getOrder(){
		return $this->sOrder;
	}
	/**
	* Fija el tipo de orden de la query.
	* @param String orden del campo.
	* @return void
	*/
	public function setOrder($sCadena){
		$this->sOrder = $sCadena;
	}
	/**
	* Devuelve el número de filas a pintar en la paginación.
	* @return Int número de líneas para la paginación.
	*/
	public function getLineasPagina(){
		return $this->sLineasPagina;
	}
	/**
	* Fija el número de filas a pintar en la paginación.
	* @param Int orden del campo.
	* @return void
	*/
	public function setLineasPagina($sCadena){
		$this->sLineasPagina = $sCadena;
	}
	/**
	* Devuelve los campos a seleccionar para excel.
	* @return String separado por comas de los campos.
	*/
	public function getPKListaExcel(){
		return $this->PKListaExcel;
	}
	/**
	* Fija los campos a seleccionar para excel.
	* @param String separado por comas.
	* @return void
	*/
	public function setPKListaExcel($sCadena){
		$this->PKListaExcel = $sCadena;
	}
	/**
	* Devuelve la descripción de los campos a seleccionar para excel.
	* @return String separado por comas de la descripción de los campos.
	*/
	public function getDESCListaExcel(){
		return $this->DESCListaExcel;
	}
	/**
	* Fija la descripción de los campos a seleccionar para excel.
	* @param String separado por comas de la descripción de los campos.
	* @return void
	*/
	public function setDESCListaExcel($sCadena){
		$this->DESCListaExcel = $sCadena;
	}
	/**
	* Devuelve el array de propiedades multiUpload.
	* @return array.
	*/
	public function getImgMultiProp(){
		return $this->imgMultiProp;
	}
	/**
	* Fija el array de propiedades multiUpload.
	* @param array .
	* @return void
	*/
	public function setImgMultiProp($sCadena){
		$this->imgMultiProp = $sCadena;
	}
}//Fin de la Clase Web_slider
?>
