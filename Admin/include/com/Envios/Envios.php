<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Envios.
**/
class Envios
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
		var $idEnvio;
		var $idEnvioHast;
		var $idEmpresa;
		var $idEmpresaHast;
		var $idProceso;
		var $idProcesoHast;
		var $idTipoCorreo;
		var $idTipoCorreoHast;
		var $idCorreo;
		var $idCorreoHast;
		var $idCandidato;
		var $idCandidatoHast;
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
		$this->idEnvio			= "";
		$this->idEnvioHast			= "";
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->idProceso			= "";
		$this->idProcesoHast			= "";
		$this->idTipoCorreo			= "";
		$this->idTipoCorreoHast			= "";
		$this->idCorreo			= "";
		$this->idCorreoHast			= "";
		$this->idCandidato			= "";
		$this->idCandidatoHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idEnvio,idEmpresa,idProceso,idTipoCorreo,idCorreo,idCandidato,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id Envio,Empresa,Proceso,Tipo Correo,Correo,Candidato,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idEnvio
	* @return int(11)
	*/
	function getIdEnvio(){
		return $this->idEnvio;
	}
	/**
	* Fija el contenido de la propiedad idEnvio
	* @param idEnvio
	* @return void
	*/
	function setIdEnvio($sCadena){
		$this->idEnvio = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idEnvioHast
	* @return int(11)
	*/
	function getIdEnvioHast(){
		return $this->idEnvioHast;
	}
	/**
	* Fija el contenido de la propiedad idEnvioHast
	* @param idEnvio
	* @return void
	*/
	function setIdEnvioHast($sCadena){
		$this->idEnvioHast = $sCadena;
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
	* Devuelve el contenido de la propiedad idEmpresaHast
	* @return int(11)
	*/
	function getIdEmpresaHast(){
		return $this->idEmpresaHast;
	}
	/**
	* Fija el contenido de la propiedad idEmpresaHast
	* @param idEmpresa
	* @return void
	*/
	function setIdEmpresaHast($sCadena){
		$this->idEmpresaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idProceso
	* @return int(11)
	*/
	function getIdProceso(){
		return $this->idProceso;
	}
	/**
	* Fija el contenido de la propiedad idProceso
	* @param idProceso
	* @return void
	*/
	function setIdProceso($sCadena){
		$this->idProceso = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idProcesoHast
	* @return int(11)
	*/
	function getIdProcesoHast(){
		return $this->idProcesoHast;
	}
	/**
	* Fija el contenido de la propiedad idProcesoHast
	* @param idProceso
	* @return void
	*/
	function setIdProcesoHast($sCadena){
		$this->idProcesoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoCorreo
	* @return int(11)
	*/
	function getIdTipoCorreo(){
		return $this->idTipoCorreo;
	}
	/**
	* Fija el contenido de la propiedad idTipoCorreo
	* @param idTipoCorreo
	* @return void
	*/
	function setIdTipoCorreo($sCadena){
		$this->idTipoCorreo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoCorreoHast
	* @return int(11)
	*/
	function getIdTipoCorreoHast(){
		return $this->idTipoCorreoHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoCorreoHast
	* @param idTipoCorreo
	* @return void
	*/
	function setIdTipoCorreoHast($sCadena){
		$this->idTipoCorreoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCorreo
	* @return int(11)
	*/
	function getIdCorreo(){
		return $this->idCorreo;
	}
	/**
	* Fija el contenido de la propiedad idCorreo
	* @param idCorreo
	* @return void
	*/
	function setIdCorreo($sCadena){
		$this->idCorreo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCorreoHast
	* @return int(11)
	*/
	function getIdCorreoHast(){
		return $this->idCorreoHast;
	}
	/**
	* Fija el contenido de la propiedad idCorreoHast
	* @param idCorreo
	* @return void
	*/
	function setIdCorreoHast($sCadena){
		$this->idCorreoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCandidato
	* @return int(11)
	*/
	function getIdCandidato(){
		return $this->idCandidato;
	}
	/**
	* Fija el contenido de la propiedad idCandidato
	* @param idCandidato
	* @return void
	*/
	function setIdCandidato($sCadena){
		$this->idCandidato = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCandidatoHast
	* @return int(11)
	*/
	function getIdCandidatoHast(){
		return $this->idCandidatoHast;
	}
	/**
	* Fija el contenido de la propiedad idCandidatoHast
	* @param idCandidato
	* @return void
	*/
	function setIdCandidatoHast($sCadena){
		$this->idCandidatoHast = $sCadena;
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
}//Fin de la Clase Envios
?>