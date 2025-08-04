<?php

/**
 * Crea un objeto de la clase y almacena en él 
 * los valores de la entidad de clase Sec_competencias.
 **/
class Sec_competencias
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
	var $IDCOMPETENCIA;
	var $IDCOMPETENCIAHast;
	var $IDTIPOPRUEBA;
	var $IDTIPOPRUEBAHast;
	var $CODIGO;
	var $DESCRIPCION;
	var $PUNTUACIONMIN;
	var $PUNTUACIONMINHast;
	var $PUNTUACIONMAX;
	var $PUNTUACIONMAXHast;
	var $BAJALOG;
	var $BAJALOGHast;
	var $fecBaja;
	var $fecBajaHast;
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
	 * @param conn			Conexión
	 **/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->sOrderBy			= "";
		$this->sOrder			= "";
		$this->sLineasPagina			= "";
		$this->IDCOMPETENCIA			= "";
		$this->IDCOMPETENCIAHast			= "";
		$this->IDTIPOPRUEBA			= "";
		$this->IDTIPOPRUEBAHast			= "";
		$this->CODIGO			= "";
		$this->DESCRIPCION			= "";
		$this->PUNTUACIONMIN			= "";
		$this->PUNTUACIONMINHast			= "";
		$this->PUNTUACIONMAX			= "";
		$this->PUNTUACIONMAXHast			= "";
		$this->BAJALOG			= "";
		$this->BAJALOGHast			= "";
		$this->fecBaja			= "";
		$this->fecBajaHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"IDCOMPETENCIA,IDTIPOPRUEBA,CODIGO,DESCRIPCION,PUNTUACIONMIN,PUNTUACIONMAX,BAJALOG,fecBaja,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id,Tipo prueba,Código,Descripción,Punt. min.,Punt. max.,Estado,Fecha Estado,Fec. alta,Fec. Mod.,Usu. alta,Usu. mod";
	}


	/**
	 * Devuelve el contenido de la propiedad IDCOMPETENCIA
	 * @return int(11)
	 */
	function getIDCOMPETENCIA(){
		return $this->IDCOMPETENCIA;
	}
	/**
	 * Fija el contenido de la propiedad IDCOMPETENCIA
	 * @param IDCOMPETENCIA
	 * @return void
	 */
	function setIDCOMPETENCIA($sCadena){
		$this->IDCOMPETENCIA = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad IDCOMPETENCIAHast
	 * @return int(11)
	 */
	function getIDCOMPETENCIAHast(){
		return $this->IDCOMPETENCIAHast;
	}
	/**
	 * Fija el contenido de la propiedad IDCOMPETENCIAHast
	 * @param IDCOMPETENCIA
	 * @return void
	 */
	function setIDCOMPETENCIAHast($sCadena){
		$this->IDCOMPETENCIAHast = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad IDTIPOPRUEBA
	 * @return int(11)
	 */
	function getIDTIPOPRUEBA(){
		return $this->IDTIPOPRUEBA;
	}
	/**
	 * Fija el contenido de la propiedad IDTIPOPRUEBA
	 * @param IDTIPOPRUEBA
	 * @return void
	 */
	function setIDTIPOPRUEBA($sCadena){
		$this->IDTIPOPRUEBA = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad IDTIPOPRUEBAHast
	 * @return int(11)
	 */
	function getIDTIPOPRUEBAHast(){
		return $this->IDTIPOPRUEBAHast;
	}
	/**
	 * Fija el contenido de la propiedad IDTIPOPRUEBAHast
	 * @param IDTIPOPRUEBA
	 * @return void
	 */
	function setIDTIPOPRUEBAHast($sCadena){
		$this->IDTIPOPRUEBAHast = $sCadena;
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
	 * Devuelve el contenido de la propiedad DESCRIPCION
	 * @return varchar(255)
	 */
	function getDESCRIPCION(){
		return $this->DESCRIPCION;
	}
	/**
	 * Fija el contenido de la propiedad DESCRIPCION
	 * @param DESCRIPCION
	 * @return void
	 */
	function setDESCRIPCION($sCadena){
		$this->DESCRIPCION = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMIN
	 * @return int(11)
	 */
	function getPUNTUACIONMIN(){
		return $this->PUNTUACIONMIN;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMIN
	 * @param PUNTUACIONMIN
	 * @return void
	 */
	function setPUNTUACIONMIN($sCadena){
		$this->PUNTUACIONMIN = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMINHast
	 * @return int(11)
	 */
	function getPUNTUACIONMINHast(){
		return $this->PUNTUACIONMINHast;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMINHast
	 * @param PUNTUACIONMIN
	 * @return void
	 */
	function setPUNTUACIONMINHast($sCadena){
		$this->PUNTUACIONMINHast = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMAX
	 * @return int(11)
	 */
	function getPUNTUACIONMAX(){
		return $this->PUNTUACIONMAX;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMAX
	 * @param PUNTUACIONMAX
	 * @return void
	 */
	function setPUNTUACIONMAX($sCadena){
		$this->PUNTUACIONMAX = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMAXHast
	 * @return int(11)
	 */
	function getPUNTUACIONMAXHast(){
		return $this->PUNTUACIONMAXHast;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMAXHast
	 * @param PUNTUACIONMAX
	 * @return void
	 */
	function setPUNTUACIONMAXHast($sCadena){
		$this->PUNTUACIONMAXHast = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad BAJALOG
	 * @return int(11)
	 */
	function getBAJALOG(){
		return $this->BAJALOG;
	}
	/**
	 * Fija el contenido de la propiedad BAJALOG
	 * @param BAJALOG
	 * @return void
	 */
	function setBAJALOG($sCadena){
		$this->BAJALOG = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad BAJALOGHast
	 * @return int(11)
	 */
	function getBAJALOGHast(){
		return $this->BAJALOGHast;
	}
	/**
	 * Fija el contenido de la propiedad BAJALOGHast
	 * @param BAJALOG
	 * @return void
	 */
	function setBAJALOGHast($sCadena){
		$this->BAJALOGHast = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad fecBaja
	 * @return datetime
	 */
	function getFecBaja(){
		return $this->fecBaja;
	}
	/**
	 * Fija el contenido de la propiedad fecBaja
	 * @param fecBaja
	 * @return void
	 */
	function setFecBaja($sCadena){
		$this->fecBaja = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad fecBajaHast
	 * @return datetime
	 */
	function getFecBajaHast(){
		return $this->fecBajaHast;
	}
	/**
	 * Fija el contenido de la propiedad fecBajaHast
	 * @param fecBaja
	 * @return void
	 */
	function setFecBajaHast($sCadena){
		$this->fecBajaHast = $sCadena;
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
}//Fin de la Clase Sec_competencias

?>