<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Historico_cambios.
**/
class Historico_cambios
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $iCont; //Contador Global
		var $aBusqueda; //Parámetros del buscador.
		var $sOrderBy; //Campo order de la query de búsqueda.
		var $sOrder; //Orden DESC ASC.
		var $sLineasPagina; //Líneas por página.
		var $fecCambio;
		var $fecCambioHast;
		var $funcionalidad;
		var $modo;
		var $query;
		var $ip;
		var $idUsuario;
		var $idUsuarioTipo;
		var $login;
		var $nombre;
		var $apellido1;
		var $apellido2;
		var $email;
		var $fecAlta;
		var $fecAltaHast;
		var $fecMod;
		var $fecModHast;
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
		$this->fecCambio			= "";
		$this->fecCambioHast			= "";
		$this->funcionalidad			= "";
		$this->modo			= "";
		$this->query			= "";
		$this->ip			= "";
		$this->idUsuario			= "";
		$this->idUsuarioTipo			= "";
		$this->login			= "";
		$this->nombre			= "";
		$this->apellido1			= "";
		$this->apellido2			= "";
		$this->email			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuMod			= "";
	}


	/**
	* Devuelve el contenido de la propiedad fecCambio
	* @return datetime
	*/
	function getFecCambio(){
		return $this->fecCambio;
	}
	/**
	* Fija el contenido de la propiedad fecCambio
	* @param fecCambio
	* @return void
	*/
	function setFecCambio($sCadena){
		$this->fecCambio = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecCambioHast
	* @return datetime
	*/
	function getFecCambioHast(){
		return $this->fecCambioHast;
	}
	/**
	* Fija el contenido de la propiedad fecCambioHast
	* @param fecCambio
	* @return void
	*/
	function setFecCambioHast($sCadena){
		$this->fecCambioHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad funcionalidad
	* @return varchar(255)
	*/
	function getFuncionalidad(){
		return $this->funcionalidad;
	}
	/**
	* Fija el contenido de la propiedad funcionalidad
	* @param funcionalidad
	* @return void
	*/
	function setFuncionalidad($sCadena){
		$this->funcionalidad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad modo
	* @return varchar(255)
	*/
	function getModo(){
		return $this->modo;
	}
	/**
	* Fija el contenido de la propiedad modo
	* @param modo
	* @return void
	*/
	function setModo($sCadena){
		$this->modo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad query
	* @return longtext
	*/
	function getQuery(){
		return $this->query;
	}
	/**
	* Fija el contenido de la propiedad query
	* @param query
	* @return void
	*/
	function setQuery($sCadena){
		$this->query = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ip
	* @return varchar(255)
	*/
	function getIp(){
		return $this->ip;
	}
	/**
	* Fija el contenido de la propiedad ip
	* @param ip
	* @return void
	*/
	function setIp($sCadena){
		$this->ip = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idUsuario
	* @return int(11)
	*/
	function getIdUsuario(){
		return $this->idUsuario;
	}
	/**
	* Fija el contenido de la propiedad idUsuario
	* @param idUsuario
	* @return void
	*/
	function setIdUsuario($sCadena){
		$this->idUsuario = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idUsuarioTipo
	* @return int(11)
	*/
	function getIdUsuarioTipo(){
		return $this->idUsuarioTipo;
	}
	/**
	* Fija el contenido de la propiedad idUsuarioTipo
	* @param idUsuarioTipo
	* @return void
	*/
	function setIdUsuarioTipo($sCadena){
		$this->idUsuarioTipo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad login
	* @return varchar(255)
	*/
	function getLogin(){
		return $this->login;
	}
	/**
	* Fija el contenido de la propiedad login
	* @param login
	* @return void
	*/
	function setLogin($sCadena){
		$this->login = $sCadena;
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
	* Devuelve el contenido de la propiedad apellido1
	* @return varchar(255)
	*/
	function getApellido1(){
		return $this->apellido1;
	}
	/**
	* Fija el contenido de la propiedad apellido1
	* @param apellido1
	* @return void
	*/
	function setApellido1($sCadena){
		$this->apellido1 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad apellido2
	* @return varchar(255)
	*/
	function getApellido2(){
		return $this->apellido2;
	}
	/**
	* Fija el contenido de la propiedad apellido2
	* @param apellido2
	* @return void
	*/
	function setApellido2($sCadena){
		$this->apellido2 = $sCadena;
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
}//Fin de la Clase Historico_cambios
?>