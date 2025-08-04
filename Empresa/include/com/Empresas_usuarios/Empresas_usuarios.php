<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Empresas_usuarios.
**/
class Empresas_usuarios
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
		var $idUsuario;
		var $idUsuarioHast;
		var $idEmpresa;
		var $idEmpresaHast;
		var $usuario;
		var $password;
		var $nombre;
		var $apellido1;
		var $apellido2;
		var $email;
		var $fecBaja;
		var $fecBajaHast;
		var $bajaLog;
		var $bajaLogHast;
		var $ultimoLogin;
		var $ultimoLoginHast;
		var $token;
		var $ultimaAcc;
		var $ultimaAccHast;
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
		$this->idUsuario			= "";
		$this->idUsuarioHast			= "";
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->usuario			= "";
		$this->password			= "";
		$this->nombre			= "";
		$this->apellido1			= "";
		$this->apellido2			= "";
		$this->email			= "";
		$this->fecBaja			= "";
		$this->fecBajaHast			= "";
		$this->bajaLog			= "";
		$this->bajaLogHast			= "";
		$this->ultimoLogin			= "";
		$this->ultimoLoginHast			= "";
		$this->token			= "";
		$this->ultimaAcc			= "";
		$this->ultimaAccHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idUsuario,idEmpresa,usuario,password,nombre,apellido1,apellido2,email,fecBaja,bajaLog,ultimoLogin,token,ultimaAcc,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id Usuario,Id Empresa,Usuario,Password,Nombre,Apellido1,Apellido2,Email,Fec. Baja,BajaLog,UltimoLogin,Token,UltimaAcc,Fec. Alta,Fec. Mod,Usu. Alta,Usu. Mod";
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
	* Devuelve el contenido de la propiedad idUsuarioHast
	* @return int(11)
	*/
	function getIdUsuarioHast(){
		return $this->idUsuarioHast;
	}
	/**
	* Fija el contenido de la propiedad idUsuarioHast
	* @param idUsuario
	* @return void
	*/
	function setIdUsuarioHast($sCadena){
		$this->idUsuarioHast = $sCadena;
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
	* Devuelve el contenido de la propiedad usuario
	* @return varchar(255)
	*/
	function getUsuario(){
		return $this->usuario;
	}
	/**
	* Fija el contenido de la propiedad usuario
	* @param usuario
	* @return void
	*/
	function setUsuario($sCadena){
		$this->usuario = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad password
	* @return varchar(255)
	*/
	function getPassword(){
		return $this->password;
	}
	/**
	* Fija el contenido de la propiedad password
	* @param password
	* @return void
	*/
	function setPassword($sCadena){
		$this->password = $sCadena;
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
	* Devuelve el contenido de la propiedad bajaLog
	* @return int(1)
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
	* @return int(1)
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
	* Devuelve el contenido de la propiedad ultimoLogin
	* @return datetime
	*/
	function getUltimoLogin(){
		return $this->ultimoLogin;
	}
	/**
	* Fija el contenido de la propiedad ultimoLogin
	* @param ultimoLogin
	* @return void
	*/
	function setUltimoLogin($sCadena){
		$this->ultimoLogin = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ultimoLoginHast
	* @return datetime
	*/
	function getUltimoLoginHast(){
		return $this->ultimoLoginHast;
	}
	/**
	* Fija el contenido de la propiedad ultimoLoginHast
	* @param ultimoLogin
	* @return void
	*/
	function setUltimoLoginHast($sCadena){
		$this->ultimoLoginHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad token
	* @return varchar(255)
	*/
	function getToken(){
		return $this->token;
	}
	/**
	* Fija el contenido de la propiedad token
	* @param token
	* @return void
	*/
	function setToken($sCadena){
		$this->token = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ultimaAcc
	* @return datetime
	*/
	function getUltimaAcc(){
		return $this->ultimaAcc;
	}
	/**
	* Fija el contenido de la propiedad ultimaAcc
	* @param ultimaAcc
	* @return void
	*/
	function setUltimaAcc($sCadena){
		$this->ultimaAcc = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ultimaAccHast
	* @return datetime
	*/
	function getUltimaAccHast(){
		return $this->ultimaAccHast;
	}
	/**
	* Fija el contenido de la propiedad ultimaAccHast
	* @param ultimaAcc
	* @return void
	*/
	function setUltimaAccHast($sCadena){
		$this->ultimaAccHast = $sCadena;
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
}//Fin de la Clase Empresas_usuarios
?>