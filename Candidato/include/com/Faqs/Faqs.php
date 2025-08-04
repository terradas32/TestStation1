<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Faqs.
**/
class Faqs
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
		var $idFaq;
		var $idFaqHast;
		var $codIdiomaIso2;
		var $pregunta;
		var $respuesta;
		var $zonaPublicacion;
		var $zonaPublicacionHast;
		var $orden;
		var $ordenHast;
		var $publicar;
		var $publicarHast;
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
		$this->idFaq			= "";
		$this->idFaqHast			= "";
		$this->codIdiomaIso2			= "";
		$this->pregunta			= "";
		$this->respuesta			= "";
		$this->zonaPublicacion			= "";
		$this->zonaPublicacionHast			= "";
		$this->orden			= "";
		$this->ordenHast			= "";
		$this->publicar			= "";
		$this->publicarHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idFaq,codIdiomaIso2,pregunta,respuesta,zonaPublicacion,orden,publicar,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id,Idioma,Pregunta,Respuesta,Zona Publicación,Orden,Publicar,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idFaq
	* @return int(11)
	*/
	function getIdFaq(){
		return $this->idFaq;
	}
	/**
	* Fija el contenido de la propiedad idFaq
	* @param idFaq
	* @return void
	*/
	function setIdFaq($sCadena){
		$this->idFaq = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idFaqHast
	* @return int(11)
	*/
	function getIdFaqHast(){
		return $this->idFaqHast;
	}
	/**
	* Fija el contenido de la propiedad idFaqHast
	* @param idFaq
	* @return void
	*/
	function setIdFaqHast($sCadena){
		$this->idFaqHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad codIdiomaIso2
	* @return varchar(2)
	*/
	function getCodIdiomaIso2(){
		return $this->codIdiomaIso2;
	}
	/**
	* Fija el contenido de la propiedad codIdiomaIso2
	* @param codIdiomaIso2
	* @return void
	*/
	function setCodIdiomaIso2($sCadena){
		$this->codIdiomaIso2 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad pregunta
	* @return mediumtext
	*/
	function getPregunta(){
		return $this->pregunta;
	}
	/**
	* Fija el contenido de la propiedad pregunta
	* @param pregunta
	* @return void
	*/
	function setPregunta($sCadena){
		$this->pregunta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad respuesta
	* @return mediumtext
	*/
	function getRespuesta(){
		return $this->respuesta;
	}
	/**
	* Fija el contenido de la propiedad respuesta
	* @param respuesta
	* @return void
	*/
	function setRespuesta($sCadena){
		$this->respuesta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad zonaPublicacion
	* @return int(11)
	*/
	function getZonaPublicacion(){
		return $this->zonaPublicacion;
	}
	/**
	* Fija el contenido de la propiedad zonaPublicacion
	* @param zonaPublicacion
	* @return void
	*/
	function setZonaPublicacion($sCadena){
		$this->zonaPublicacion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad zonaPublicacionHast
	* @return int(11)
	*/
	function getZonaPublicacionHast(){
		return $this->zonaPublicacionHast;
	}
	/**
	* Fija el contenido de la propiedad zonaPublicacionHast
	* @param zonaPublicacion
	* @return void
	*/
	function setZonaPublicacionHast($sCadena){
		$this->zonaPublicacionHast = $sCadena;
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
	* Devuelve el contenido de la propiedad ordenHast
	* @return int(11)
	*/
	function getOrdenHast(){
		return $this->ordenHast;
	}
	/**
	* Fija el contenido de la propiedad ordenHast
	* @param orden
	* @return void
	*/
	function setOrdenHast($sCadena){
		$this->ordenHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad publicar
	* @return int(1)
	*/
	function getPublicar(){
		return $this->publicar;
	}
	/**
	* Fija el contenido de la propiedad publicar
	* @param publicar
	* @return void
	*/
	function setPublicar($sCadena){
		$this->publicar = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad publicarHast
	* @return int(1)
	*/
	function getPublicarHast(){
		return $this->publicarHast;
	}
	/**
	* Fija el contenido de la propiedad publicarHast
	* @param publicar
	* @return void
	*/
	function setPublicarHast($sCadena){
		$this->publicarHast = $sCadena;
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
}//Fin de la Clase Faqs
?>