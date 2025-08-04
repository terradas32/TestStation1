<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Empresas_perfiles_funcionalidades.
**/
class Empresas_perfiles_funcionalidades
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $iCont; //Contador Global
		var $aBusqueda; //Parámetros del buscador.
		var $sOrderBy; //Campo order de la query de búsqueda.
		var $sOrder; //Orden DESC ASC.
		var $sLineasPagina; //Líneas por página.
		var $idPerfil;
		var $idFuncionalidad;
		var $modificar;
		var $borrar;
		var $PerfilFuncionalidades;
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
		$this->aBusqueda			= array();
		$this->idPerfil			= "";
		$this->idFuncionalidad			= "";
		$this->modificar			= "";
		$this->borrar			= "";
		$this->PerfilFuncionalidades	= "";
		$this->fecAlta			= "";
		$this->fecMod			= "";
		$this->usuAlta			= "";
		$this->usuMod			= "";
	}


	/**
	* Devuelve el contenido de la propiedad idPerfil
	* @return int(11)
	*/
	function getIdPerfil(){
		return $this->idPerfil;
	}
	/**
	* Fija el contenido de la propiedad idPerfil
	* @param idPerfil
	* @return void
	*/
	function setIdPerfil($sCadena){
		$this->idPerfil = $sCadena;
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
	* Devuelve el contenido de la propiedad modificar
	* @return char(2)
	*/
	function getModificar(){
		return $this->modificar;
	}
	/**
	* Fija el contenido de la propiedad modificar
	* @param modificar
	* @return void
	*/
	function setModificar($sCadena){
		$this->modificar = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad borrar
	* @return char(2)
	*/
	function getBorrar(){
		return $this->borrar;
	}
	/**
	* Fija el contenido de la propiedad borrar
	* @param borrar
	* @return void
	*/
	function setBorrar($sCadena){
		$this->borrar = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad PerfilFuncionalidades
	* @return char(2)
	*/
	function getPerfilFuncionalidades(){
		return $this->PerfilFuncionalidades;
	}
	/**
	* Fija el contenido de la propiedad PerfilFuncionalidades
	* @param PerfilFuncionalidades
	* @return void
	*/
	function setPerfilFuncionalidades($sCadena){
		$this->PerfilFuncionalidades = $sCadena;
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
		if (!empty($sValor)) {
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
}//Fin de la Clase Empresas_perfiles_funcionalidades
?>