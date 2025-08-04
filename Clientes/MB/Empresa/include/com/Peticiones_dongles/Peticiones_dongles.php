<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Peticiones_dongles.
**/
class Peticiones_dongles
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
		var $idPeticion;
		var $idPeticionHast;
		var $idEmpresa;
		var $idEmpresaHast;
		var $idEmpresaReceptora;
		var $idEmpresaReceptoraHast;
		var $nDongles;
		var $nDonglesHast;
		var $estado;
		var $estadoHast;
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
		$this->idPeticion			= "";
		$this->idPeticionHast			= "";
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->idEmpresaReceptora			= "";
		$this->idEmpresaReceptoraHast			= "";
		$this->nDongles			= "";
		$this->nDonglesHast			= "";
		$this->estado			= "";
		$this->estadoHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idPeticion,idEmpresa,idEmpresaReceptora,nDongles,estado,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"IdPetición,Empresa,Empresa Receptora,Número de Dongles,Estado,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idPeticion
	* @return int(11)
	*/
	function getIdPeticion(){
		return $this->idPeticion;
	}
	/**
	* Fija el contenido de la propiedad idPeticion
	* @param idPeticion
	* @return void
	*/
	function setIdPeticion($sCadena){
		$this->idPeticion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPeticionHast
	* @return int(11)
	*/
	function getIdPeticionHast(){
		return $this->idPeticionHast;
	}
	/**
	* Fija el contenido de la propiedad idPeticionHast
	* @param idPeticion
	* @return void
	*/
	function setIdPeticionHast($sCadena){
		$this->idPeticionHast = $sCadena;
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
	* Devuelve el contenido de la propiedad idEmpresaReceptora
	* @return int(11)
	*/
	function getIdEmpresaReceptora(){
		return $this->idEmpresaReceptora;
	}
	/**
	* Fija el contenido de la propiedad idEmpresaReceptora
	* @param idEmpresaReceptora
	* @return void
	*/
	function setIdEmpresaReceptora($sCadena){
		$this->idEmpresaReceptora = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idEmpresaReceptoraHast
	* @return int(11)
	*/
	function getIdEmpresaReceptoraHast(){
		return $this->idEmpresaReceptoraHast;
	}
	/**
	* Fija el contenido de la propiedad idEmpresaReceptoraHast
	* @param idEmpresaReceptora
	* @return void
	*/
	function setIdEmpresaReceptoraHast($sCadena){
		$this->idEmpresaReceptoraHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nDongles
	* @return int(11)
	*/
	function getNDongles(){
		return $this->nDongles;
	}
	/**
	* Fija el contenido de la propiedad nDongles
	* @param nDongles
	* @return void
	*/
	function setNDongles($sCadena){
		$this->nDongles = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nDonglesHast
	* @return int(11)
	*/
	function getNDonglesHast(){
		return $this->nDonglesHast;
	}
	/**
	* Fija el contenido de la propiedad nDonglesHast
	* @param nDongles
	* @return void
	*/
	function setNDonglesHast($sCadena){
		$this->nDonglesHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estado
	* @return int(11)
	*/
	function getEstado(){
		return $this->estado;
	}
	/**
	* Fija el contenido de la propiedad estado
	* @param estado
	* @return void
	*/
	function setEstado($sCadena){
		$this->estado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estadoHast
	* @return int(11)
	*/
	function getEstadoHast(){
		return $this->estadoHast;
	}
	/**
	* Fija el contenido de la propiedad estadoHast
	* @param estado
	* @return void
	*/
	function setEstadoHast($sCadena){
		$this->estadoHast = $sCadena;
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
}//Fin de la Clase Peticiones_dongles
?>