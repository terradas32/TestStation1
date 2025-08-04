<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Baremos_resultados.
**/
class Baremos_resultados
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
		var $idResultado;
		var $idResultadoHast;
		var $idBaremo;
		var $idBaremoHast;
		var $idPrueba;
		var $idPruebaHast;
		var $idBloque;
		var $idBloqueHast;
		var $idEscala;
		var $idEscalaHast;
		var $puntMin;
		var $puntMinHast;
		var $puntMax;
		var $puntMaxHast;
		var $puntBaremada;
		var $puntBaremadaHast;
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
		$this->idResultado			= "";
		$this->idResultadoHast			= "";
		$this->idBaremo			= "";
		$this->idBaremoHast			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->idBloque			= "";
		$this->idBloqueHast			= "";
		$this->idEscala			= "";
		$this->idEscalaHast			= "";
		$this->puntMin			= "";
		$this->puntMinHast			= "";
		$this->puntMax			= "";
		$this->puntMaxHast			= "";
		$this->puntBaremada			= "";
		$this->puntBaremadaHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idResultado,idBaremo,idPrueba,puntMin,puntMax,puntBaremada,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id Resultado,Baremo,Prueba,Puntuación Mínima,Puntuación Máxima,Puntuación Baremada,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idResultado
	* @return int(11)
	*/
	function getIdResultado(){
		return $this->idResultado;
	}
	/**
	* Fija el contenido de la propiedad idResultado
	* @param idResultado
	* @return void
	*/
	function setIdResultado($sCadena){
		$this->idResultado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idResultadoHast
	* @return int(11)
	*/
	function getIdResultadoHast(){
		return $this->idResultadoHast;
	}
	/**
	* Fija el contenido de la propiedad idResultadoHast
	* @param idResultado
	* @return void
	*/
	function setIdResultadoHast($sCadena){
		$this->idResultadoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idBaremo
	* @return int(11)
	*/
	function getIdBaremo(){
		return $this->idBaremo;
	}
	/**
	* Fija el contenido de la propiedad idBaremo
	* @param idBaremo
	* @return void
	*/
	function setIdBaremo($sCadena){
		$this->idBaremo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idBaremoHast
	* @return int(11)
	*/
	function getIdBaremoHast(){
		return $this->idBaremoHast;
	}
	/**
	* Fija el contenido de la propiedad idBaremoHast
	* @param idBaremo
	* @return void
	*/
	function setIdBaremoHast($sCadena){
		$this->idBaremoHast = $sCadena;
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
	* Devuelve el contenido de la propiedad idBloque
	* @return int(11)
	*/
	function getIdBloque(){
		return $this->idBloque;
	}
	/**
	* Fija el contenido de la propiedad idBloque
	* @param idBloque
	* @return void
	*/
	function setIdBloque($sCadena){
		$this->idBloque = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idBloqueHast
	* @return int(11)
	*/
	function getIdBloqueHast(){
		return $this->idBloqueHast;
	}
	/**
	* Fija el contenido de la propiedad idBloqueHast
	* @param idBloque
	* @return void
	*/
	function setIdBloqueHast($sCadena){
		$this->idBloqueHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idEscala
	* @return int(11)
	*/
	function getIdEscala(){
		return $this->idEscala;
	}
	/**
	* Fija el contenido de la propiedad idEscala
	* @param idEscala
	* @return void
	*/
	function setIdEscala($sCadena){
		$this->idEscala = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idEscalaHast
	* @return int(11)
	*/
	function getIdEscalaHast(){
		return $this->idEscalaHast;
	}
	/**
	* Fija el contenido de la propiedad idEscalaHast
	* @param idEscala
	* @return void
	*/
	function setIdEscalaHast($sCadena){
		$this->idEscalaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad puntMin
	* @return int(11)
	*/
	function getPuntMin(){
		return $this->puntMin;
	}
	/**
	* Fija el contenido de la propiedad puntMin
	* @param puntMin
	* @return void
	*/
	function setPuntMin($sCadena){
		$this->puntMin = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad puntMinHast
	* @return int(11)
	*/
	function getPuntMinHast(){
		return $this->puntMinHast;
	}
	/**
	* Fija el contenido de la propiedad puntMinHast
	* @param puntMin
	* @return void
	*/
	function setPuntMinHast($sCadena){
		$this->puntMinHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad puntMax
	* @return int(11)
	*/
	function getPuntMax(){
		return $this->puntMax;
	}
	/**
	* Fija el contenido de la propiedad puntMax
	* @param puntMax
	* @return void
	*/
	function setPuntMax($sCadena){
		$this->puntMax = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad puntMaxHast
	* @return int(11)
	*/
	function getPuntMaxHast(){
		return $this->puntMaxHast;
	}
	/**
	* Fija el contenido de la propiedad puntMaxHast
	* @param puntMax
	* @return void
	*/
	function setPuntMaxHast($sCadena){
		$this->puntMaxHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad puntBaremada
	* @return int(11)
	*/
	function getPuntBaremada(){
		return $this->puntBaremada;
	}
	/**
	* Fija el contenido de la propiedad puntBaremada
	* @param puntBaremada
	* @return void
	*/
	function setPuntBaremada($sCadena){
		$this->puntBaremada = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad puntBaremadaHast
	* @return int(11)
	*/
	function getPuntBaremadaHast(){
		return $this->puntBaremadaHast;
	}
	/**
	* Fija el contenido de la propiedad puntBaremadaHast
	* @param puntBaremada
	* @return void
	*/
	function setPuntBaremadaHast($sCadena){
		$this->puntBaremadaHast = $sCadena;
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
}//Fin de la Clase Baremos_resultados
?>