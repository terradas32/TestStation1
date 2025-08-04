<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Tipos_tpv.
**/
class Tipos_tpv
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
		var $idTipoTpv;
		var $idTipoTpvHast;
		var $descripcion;
		var $TERMINAL_TYPE;
		var $OPERATION_TYPE;
		var $URL_NOTIFY;
		var $URL_OK;
		var $URL_NOOK;
		var $SERVICE_ACTION;
		var $bajaLog;
		var $bajaLogHast;
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
		$this->idTipoTpv			= "";
		$this->idTipoTpvHast			= "";
		$this->descripcion			= "";
		$this->TERMINAL_TYPE			= "";
		$this->OPERATION_TYPE			= "";
		$this->URL_NOTIFY			= "";
		$this->URL_OK			= "";
		$this->URL_NOOK			= "";
		$this->SERVICE_ACTION			= "";
		$this->bajaLog			= "";
		$this->bajaLogHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idTipoTpv,descripcion,TERMINAL_TYPE,OPERATION_TYPE,URL_NOTIFY,URL_OK,URL_NOOK,SERVICE_ACTION,bajaLog,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id Tipo Tpv,Descripción,TERMINAL TYPE,OPERATION TYPE,URL NOTIFY,URL OK,URL KO,SERVICE ACTiON,Baja Log,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idTipoTpv
	* @return int(11)
	*/
	function getIdTipoTpv(){
		return $this->idTipoTpv;
	}
	/**
	* Fija el contenido de la propiedad idTipoTpv
	* @param idTipoTpv
	* @return void
	*/
	function setIdTipoTpv($sCadena){
		$this->idTipoTpv = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoTpvHast
	* @return int(11)
	*/
	function getIdTipoTpvHast(){
		return $this->idTipoTpvHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoTpvHast
	* @param idTipoTpv
	* @return void
	*/
	function setIdTipoTpvHast($sCadena){
		$this->idTipoTpvHast = $sCadena;
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
	* Devuelve el contenido de la propiedad TERMINAL_TYPE
	* @return varchar(3)
	*/
	function getTERMINAL_TYPE(){
		return $this->TERMINAL_TYPE;
	}
	/**
	* Fija el contenido de la propiedad TERMINAL_TYPE
	* @param TERMINAL_TYPE
	* @return void
	*/
	function setTERMINAL_TYPE($sCadena){
		$this->TERMINAL_TYPE = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad OPERATION_TYPE
	* @return varchar(3)
	*/
	function getOPERATION_TYPE(){
		return $this->OPERATION_TYPE;
	}
	/**
	* Fija el contenido de la propiedad OPERATION_TYPE
	* @param OPERATION_TYPE
	* @return void
	*/
	function setOPERATION_TYPE($sCadena){
		$this->OPERATION_TYPE = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad URL_NOTIFY
	* @return varchar(2500)
	*/
	function getURL_NOTIFY(){
		return $this->URL_NOTIFY;
	}
	/**
	* Fija el contenido de la propiedad URL_NOTIFY
	* @param URL_NOTIFY
	* @return void
	*/
	function setURL_NOTIFY($sCadena){
		$this->URL_NOTIFY = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad URL_OK
	* @return varchar(2500)
	*/
	function getURL_OK(){
		return $this->URL_OK;
	}
	/**
	* Fija el contenido de la propiedad URL_OK
	* @param URL_OK
	* @return void
	*/
	function setURL_OK($sCadena){
		$this->URL_OK = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad URL_NOOK
	* @return varchar(2500)
	*/
	function getURL_NOOK(){
		return $this->URL_NOOK;
	}
	/**
	* Fija el contenido de la propiedad URL_NOOK
	* @param URL_NOOK
	* @return void
	*/
	function setURL_NOOK($sCadena){
		$this->URL_NOOK = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad SERVICE_ACTION
	* @return varchar(2500)
	*/
	function getSERVICE_ACTION(){
		return $this->SERVICE_ACTION;
	}
	/**
	* Fija el contenido de la propiedad SERVICE_ACTION
	* @param SERVICE_ACTION
	* @return void
	*/
	function setSERVICE_ACTION($sCadena){
		$this->SERVICE_ACTION = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad bajaLog
	* @return int(2)
	*/
	function getBajaLog(){
		return $this->bajaLog;
	}
	/**
	* Fija el contenido de la propiedad bajaLog
	* @param bajaLog
	* @return void
	*/
	function setBajaLog($sCadena){
		$this->bajaLog = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad bajaLogHast
	* @return int(2)
	*/
	function getBajaLogHast(){
		return $this->bajaLogHast;
	}
	/**
	* Fija el contenido de la propiedad bajaLogHast
	* @param bajaLog
	* @return void
	*/
	function setBajaLogHast($sCadena){
		$this->bajaLogHast = $sCadena;
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
}//Fin de la Clase Tipos_tpv
?>