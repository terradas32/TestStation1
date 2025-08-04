<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Candidatos_pagos_tpv.
**/
class Candidatos_pagos_tpv
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
		var $idRecarga;
		var $idRecargaHast;
		var $idEmpresa;
		var $idEmpresaHast;
		var $idProceso;
		var $idProcesoHast;
		var $idCandidato;
		var $idCandidatoHast;
		var $localizador;
		var $descripcion;
		var $impBase;
		var $impBaseHast;
		var $impImpuestos;
		var $impImpuestosHast;
		var $impBaseImpuestos;
		var $impBaseImpuestosHast;
		var $email;
		var $nombre;
		var $apellidos;
		var $direccion;
		var $codPostal;
		var $ciudad;
		var $telefono1;
		var $codEstado;
		var $codAutorizacion;
		var $codError;
		var $desError;
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
		$this->idRecarga			= "";
		$this->idRecargaHast			= "";
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->idProceso			= "";
		$this->idProcesoHast			= "";
		$this->idCandidato			= "";
		$this->idCandidatoHast			= "";
		$this->localizador			= "";
		$this->descripcion			= "";
		$this->impBase			= "";
		$this->impBaseHast			= "";
		$this->impImpuestos			= "";
		$this->impImpuestosHast			= "";
		$this->impBaseImpuestos			= "";
		$this->impBaseImpuestosHast			= "";
		$this->email			= "";
		$this->nombre			= "";
		$this->apellidos			= "";
		$this->direccion			= "";
		$this->codPostal			= "";
		$this->ciudad			= "";
		$this->telefono1			= "";
		$this->codEstado			= "";
		$this->codAutorizacion			= "";
		$this->codError			= "";
		$this->desError			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idRecarga,idEmpresa,idProceso,idCandidato,localizador,descripcion,impBase,impImpuestos,impBaseImpuestos,email,nombre,apellidos,direccion,codPostal,ciudad,telefono1,codEstado,codAutorizacion,codError,desError,fecAlta,fecMod";
		$this->DESCListaExcel	=	"Id Recarga,Empresa,Proceso,Candidato,Localizador,Descripción,Imp. Base,Imp. Impuestos,Imp. Base Impuestos,Email,Nombre,Apellidos,Dirección,Cód. Postal,Ciudad,Telefono1,Cód. Estado,Cód. Autorización,Cód. Error,Desc. Error,Fecha de Alta,Fecha de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idRecarga
	* @return int(11)
	*/
	function getIdRecarga(){
		return $this->idRecarga;
	}
	/**
	* Fija el contenido de la propiedad idRecarga
	* @param idRecarga
	* @return void
	*/
	function setIdRecarga($sCadena){
		$this->idRecarga = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idRecargaHast
	* @return int(11)
	*/
	function getIdRecargaHast(){
		return $this->idRecargaHast;
	}
	/**
	* Fija el contenido de la propiedad idRecargaHast
	* @param idRecarga
	* @return void
	*/
	function setIdRecargaHast($sCadena){
		$this->idRecargaHast = $sCadena;
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
	* Devuelve el contenido de la propiedad localizador
	* @return varchar(255)
	*/
	function getLocalizador(){
		return $this->localizador;
	}
	/**
	* Fija el contenido de la propiedad localizador
	* @param localizador
	* @return void
	*/
	function setLocalizador($sCadena){
		$this->localizador = $sCadena;
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
	* Devuelve el contenido de la propiedad impBase
	* @return decimal(10,2)
	*/
	function getImpBase(){
		return $this->impBase;
	}
	/**
	* Fija el contenido de la propiedad impBase
	* @param impBase
	* @return void
	*/
	function setImpBase($sCadena){
		$this->impBase = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad impBaseHast
	* @return decimal(10,2)
	*/
	function getImpBaseHast(){
		return $this->impBaseHast;
	}
	/**
	* Fija el contenido de la propiedad impBaseHast
	* @param impBase
	* @return void
	*/
	function setImpBaseHast($sCadena){
		$this->impBaseHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad impImpuestos
	* @return decimal(10,2)
	*/
	function getImpImpuestos(){
		return $this->impImpuestos;
	}
	/**
	* Fija el contenido de la propiedad impImpuestos
	* @param impImpuestos
	* @return void
	*/
	function setImpImpuestos($sCadena){
		$this->impImpuestos = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad impImpuestosHast
	* @return decimal(10,2)
	*/
	function getImpImpuestosHast(){
		return $this->impImpuestosHast;
	}
	/**
	* Fija el contenido de la propiedad impImpuestosHast
	* @param impImpuestos
	* @return void
	*/
	function setImpImpuestosHast($sCadena){
		$this->impImpuestosHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad impBaseImpuestos
	* @return decimal(10,2)
	*/
	function getImpBaseImpuestos(){
		return $this->impBaseImpuestos;
	}
	/**
	* Fija el contenido de la propiedad impBaseImpuestos
	* @param impBaseImpuestos
	* @return void
	*/
	function setImpBaseImpuestos($sCadena){
		$this->impBaseImpuestos = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad impBaseImpuestosHast
	* @return decimal(10,2)
	*/
	function getImpBaseImpuestosHast(){
		return $this->impBaseImpuestosHast;
	}
	/**
	* Fija el contenido de la propiedad impBaseImpuestosHast
	* @param impBaseImpuestos
	* @return void
	*/
	function setImpBaseImpuestosHast($sCadena){
		$this->impBaseImpuestosHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad email
	* @return varchar(255)
	*/
	function getEmail(){
		return $this->email;
	}
	/**
	* Fija el contenido de la propiedad email
	* @param email
	* @return void
	*/
	function setEmail($sCadena){
		$this->email = $sCadena;
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
	* Devuelve el contenido de la propiedad apellidos
	* @return varchar(255)
	*/
	function getApellidos(){
		return $this->apellidos;
	}
	/**
	* Fija el contenido de la propiedad apellidos
	* @param apellidos
	* @return void
	*/
	function setApellidos($sCadena){
		$this->apellidos = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad direccion
	* @return varchar(255)
	*/
	function getDireccion(){
		return $this->direccion;
	}
	/**
	* Fija el contenido de la propiedad direccion
	* @param direccion
	* @return void
	*/
	function setDireccion($sCadena){
		$this->direccion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad codPostal
	* @return varchar(10)
	*/
	function getCodPostal(){
		return $this->codPostal;
	}
	/**
	* Fija el contenido de la propiedad codPostal
	* @param codPostal
	* @return void
	*/
	function setCodPostal($sCadena){
		$this->codPostal = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ciudad
	* @return varchar(255)
	*/
	function getCiudad(){
		return $this->ciudad;
	}
	/**
	* Fija el contenido de la propiedad ciudad
	* @param ciudad
	* @return void
	*/
	function setCiudad($sCadena){
		$this->ciudad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad telefono1
	* @return varchar(255)
	*/
	function getTelefono1(){
		return $this->telefono1;
	}
	/**
	* Fija el contenido de la propiedad telefono1
	* @param telefono1
	* @return void
	*/
	function setTelefono1($sCadena){
		$this->telefono1 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad codEstado
	* @return varchar(255)
	*/
	function getCodEstado(){
		return $this->codEstado;
	}
	/**
	* Fija el contenido de la propiedad codEstado
	* @param codEstado
	* @return void
	*/
	function setCodEstado($sCadena){
		$this->codEstado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad codAutorizacion
	* @return varchar(255)
	*/
	function getCodAutorizacion(){
		return $this->codAutorizacion;
	}
	/**
	* Fija el contenido de la propiedad codAutorizacion
	* @param codAutorizacion
	* @return void
	*/
	function setCodAutorizacion($sCadena){
		$this->codAutorizacion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad codError
	* @return varchar(255)
	*/
	function getCodError(){
		return $this->codError;
	}
	/**
	* Fija el contenido de la propiedad codError
	* @param codError
	* @return void
	*/
	function setCodError($sCadena){
		$this->codError = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad desError
	* @return mediumtext
	*/
	function getDesError(){
		return $this->desError;
	}
	/**
	* Fija el contenido de la propiedad desError
	* @param desError
	* @return void
	*/
	function setDesError($sCadena){
		$this->desError = $sCadena;
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
}//Fin de la Clase Candidatos_pagos_tpv
?>