<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Empresas.
**/
class Empresas
{
	
	/**
	* Declaración de las variables de Entidad.
	**/
		var $iCont; //Contador Global
		var $aBusqueda; //Parámetros del buscador.
		var $sOrderBy; //Campo order de la query de búsqueda.
		var $sOrder; //Orden DESC ASC.
		var $sLineasPagina; //Líneas por página.
		var $idEmpresa;
		var $idEmpresaHast;
		var $nombre;
		var $idPadre;
		var $idPadreHast;
		var $orden;
		var $ordenHast;
		var $DentroDe;
		var $DespuesDe;
		var $indentacion;
		var $indentacionHast;
		var $pathLogo;
		var $publico;
		var $fecAlta;
		var $fecAltaHast;
		var $fecMod;
		var $fecModHast;
		var $usuAlta;
		var $usuAltaHast;
		var $usuMod;
		var $usuModHast;
		
		var $cif;
		var $usuario;
		var $password;
		var $mail;
		var $mail2;
		var $mail3;
		var $distribuidor;
		var $avisoLegal;
		var $prepago;
		var $ncandidatos;
		var $ncandidatosHast;
		var $dongles;
		var $donglesHast;
		var $entidad;
		var $oficina;
		var $dc;
		var $cuenta;
		var $idPais;
		var $direccion;
		var $umbral_aviso;
		var $umbral_avisoHast;

		var $nombreCan;
		var $apellido1;
		var $mailCan;
		var $edad;
		var $sexo;
		var $nivel;
		var $formacion;
		var $area;
		var $telefono; 
		
		var $ultimoLogin;
		var $ultimoLoginHast;
		var $token;
		var $ultimaAcc;
		var $ultimaAccHast;
		var $idsPruebas;
		var $idsPruebasAleatorias;
		
	/**
	* Constructor q inicializa los datos de la clase.
	* @param $conn			Conexión
	**/
	function Empresas()
	{
		$this->iCont			= 0;
		$this->aBusqueda		= array();
		$this->idEmpresa	= "";
		$this->idEmpresaHast	= "";
		$this->nombre			= "";
		$this->idPadre			= "";
		$this->idPadreHast			= "";
		$this->orden			= "";
		$this->ordenHast			= "";
		$this->DentroDe			= "";
		$this->DespuesDe		= "";
		$this->indentacion		= "";
		$this->indentacionHast		= "";
		$this->pathLogo			= "";
		$this->publico			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		
		$this->cif			= "";
		$this->usuario			= "";
		$this->password			= "";
		$this->mail			= "";
		$this->mail2			= "";
		$this->mail3			= "";
		$this->distribuidor			= "";
		$this->avisoLegal			= "";
		$this->prepago			= "";
		$this->ncandidatos			= "";
		$this->ncandidatosHast			= "";
		$this->dongles			= "";
		$this->donglesHast			= "";
		$this->entidad			= "";
		$this->oficina			= "";
		$this->dc			= "";
		$this->cuenta			= "";
		$this->idPais			= "";
		$this->direccion			= "";
		$this->umbral_aviso			= "";
		$this->umbral_avisoHast			= "";

		$this->nombreCan			= "";
		$this->apellido1			= "";
		$this->mailCan			= "";
		
		$this->edad			= "";
		$this->sexo			= "";
		$this->nivel			= "";
		$this->formacion			= "";
		$this->area			= "";
		$this->telefono			= ""; 

		$this->ultimoLogin			= "";
		$this->ultimoLoginHast			= "";
		$this->token			= "";
		$this->ultimaAcc			= "";
		$this->ultimaAccHast			= "";
		$this->idsPruebas			= "";
		$this->idsPruebasAleatorias			= "";
		
		
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
	* @param idEmpresaHast
	* @return void
	*/
	function setIdEmpresaHast($sCadena){
		$this->idEmpresaHast = $sCadena;
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
	* Devuelve el contenido de la propiedad idPadre
	* @return int(11)
	*/
	function getIdPadre(){
		return $this->idPadre;
	}
	/**
	* Fija el contenido de la propiedad idPadre
	* @param idPadre
	* @return void
	*/
	function setIdPadre($sCadena){
		$this->idPadre = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPadreHast
	* @return int(11)
	*/
	function getIdPadreHast(){
		return $this->idPadreHast;
	}
	/**
	* Fija el contenido de la propiedad idPadreHast
	* @param idPadreHast
	* @return void
	*/
	function setIdPadreHast($sCadena){
		$this->idPadreHast = $sCadena;
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
	* @param ordenHast
	* @return void
	*/
	function setOrdenHast($sCadena){
		$this->ordenHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DentroDe
	* @return int(11)
	*/
	function getDentroDe(){
		return $this->DentroDe;
	}
	/**
	* Fija el contenido de la propiedad DentroDe
	* @param DentroDe
	* @return void
	*/
	function setDentroDe($sCadena){
		$this->DentroDe = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DespuesDe
	* @return int(11)
	*/
	function getDespuesDe(){
		return $this->DespuesDe;
	}
	/**
	* Fija el contenido de la propiedad DespuesDe
	* @param DespuesDe
	* @return void
	*/
	function setDespuesDe($sCadena){
		$this->DespuesDe = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad indentacion
	* @return int(11)
	*/
	function getIndentacion(){
		return $this->indentacion;
	}
	/**
	* Fija el contenido de la propiedad indentacion
	* @param indentacion
	* @return void
	*/
	function setIndentacion($sCadena){
		$this->indentacion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad indentacionHast
	* @return int(11)
	*/
	function getIndentacionHast(){
		return $this->indentacionHast;
	}
	/**
	* Fija el contenido de la propiedad indentacionHast
	* @param indentacionHast
	* @return void
	*/
	function setIndentacionHast($sCadena){
		$this->indentacionHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad publico
	* @return varchar(7)
	*/
	function getPublico(){
		return $this->publico;
	}
	/**
	* Fija el contenido de la propiedad publico
	* @param publico
	* @return void
	*/
	function setPublico($sCadena){
		$this->publico = $sCadena;
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
	* Devuelve el contenido de la propiedad cif
	* @return varchar(255)
	*/
	function getCif(){
		return $this->cif;
	}
	/**
	* Fija el contenido de la propiedad cif
	* @param cif
	* @return void
	*/
	function setCif($sCadena){
		$this->cif = $sCadena;
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
	* Devuelve el contenido de la propiedad pathLogo
	* @return varchar(500)
	*/
	function getPathLogo(){
		return $this->pathLogo;
	}
	/**
	* Fija el contenido de la propiedad pathLogo
	* @param pathLogo
	* @return void
	*/
	function setPathLogo($sCadena){
		$this->pathLogo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad mail
	* @return varchar(255)
	*/
	function getMail(){
		return $this->mail;
	}
	/**
	* Fija el contenido de la propiedad mail
	* @param mail
	* @return void
	*/
	function setMail($sCadena){
		$this->mail = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad mail2
	* @return varchar(255)
	*/
	function getMail2(){
		return $this->mail2;
	}
	/**
	* Fija el contenido de la propiedad mail2
	* @param mail2
	* @return void
	*/
	function setMail2($sCadena){
		$this->mail2 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad mail3
	* @return varchar(255)
	*/
	function getMail3(){
		return $this->mail3;
	}
	/**
	* Fija el contenido de la propiedad mail3
	* @param mail3
	* @return void
	*/
	function setMail3($sCadena){
		$this->mail3 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad distribuidor
	* @return varchar(2)
	*/
	function getDistribuidor(){
		return $this->distribuidor;
	}
	/**
	* Fija el contenido de la propiedad distribuidor
	* @param distribuidor
	* @return void
	*/
	function setDistribuidor($sCadena){
		$this->distribuidor = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad avisoLegal
	* @return varchar(2)
	*/
	function getAvisoLegal(){
		return $this->avisoLegal;
	}
	/**
	* Fija el contenido de la propiedad avisoLegal
	* @param avisoLegal
	* @return void
	*/
	function setAvisoLegal($sCadena){
		$this->avisoLegal = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad prepago
	* @return varchar(2)
	*/
	function getPrepago(){
		return $this->prepago;
	}
	/**
	* Fija el contenido de la propiedad prepago
	* @param prepago
	* @return void
	*/
	function setPrepago($sCadena){
		$this->prepago = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ncandidatos
	* @return int(11)
	*/
	function getNcandidatos(){
		return $this->ncandidatos;
	}
	/**
	* Fija el contenido de la propiedad ncandidatos
	* @param ncandidatos
	* @return void
	*/
	function setNcandidatos($sCadena){
		$this->ncandidatos = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ncandidatosHast
	* @return int(11)
	*/
	function getNcandidatosHast(){
		return $this->ncandidatosHast;
	}
	/**
	* Fija el contenido de la propiedad ncandidatosHast
	* @param ncandidatos
	* @return void
	*/
	function setNcandidatosHast($sCadena){
		$this->ncandidatosHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad dongles
	* @return int(11)
	*/
	function getDongles(){
		return $this->dongles;
	}
	/**
	* Fija el contenido de la propiedad dongles
	* @param dongles
	* @return void
	*/
	function setDongles($sCadena){
		$this->dongles = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad donglesHast
	* @return int(11)
	*/
	function getDonglesHast(){
		return $this->donglesHast;
	}
	/**
	* Fija el contenido de la propiedad donglesHast
	* @param dongles
	* @return void
	*/
	function setDonglesHast($sCadena){
		$this->donglesHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad entidad
	* @return int(4)
	*/
	function getEntidad(){
		return $this->entidad;
	}
	/**
	* Fija el contenido de la propiedad entidad
	* @param entidad
	* @return void
	*/
	function setEntidad($sCadena){
		$this->entidad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad oficina
	* @return int(4)
	*/
	function getOficina(){
		return $this->oficina;
	}
	/**
	* Fija el contenido de la propiedad oficina
	* @param oficina
	* @return void
	*/
	function setOficina($sCadena){
		$this->oficina = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad dc
	* @return int(2)
	*/
	function getDc(){
		return $this->dc;
	}
	/**
	* Fija el contenido de la propiedad dc
	* @param dc
	* @return void
	*/
	function setDc($sCadena){
		$this->dc = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad cuenta
	* @return int(10)
	*/
	function getCuenta(){
		return $this->cuenta;
	}
	/**
	* Fija el contenido de la propiedad cuenta
	* @param cuenta
	* @return void
	*/
	function setCuenta($sCadena){
		$this->cuenta = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPais
	* @return char(3)
	*/
	function getIdPais(){
		return $this->idPais;
	}
	/**
	* Fija el contenido de la propiedad idPais
	* @param idPais
	* @return void
	*/
	function setIdPais($sCadena){
		$this->idPais = $sCadena;
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
	* Devuelve el contenido de la propiedad umbral_aviso
	* @return int(11)
	*/
	function getUmbral_aviso(){
		return $this->umbral_aviso;
	}
	/**
	* Fija el contenido de la propiedad umbral_aviso
	* @param umbral_aviso
	* @return void
	*/
	function setUmbral_aviso($sCadena){
		$this->umbral_aviso = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad umbral_avisoHast
	* @return int(11)
	*/
	function getUmbral_avisoHast(){
		return $this->umbral_avisoHast;
	}
	/**
	* Fija el contenido de la propiedad umbral_avisoHast
	* @param umbral_aviso
	* @return void
	*/
	function setUmbral_avisoHast($sCadena){
		$this->umbral_avisoHast = $sCadena;
	}
	function setNombreCan($sCadena){
		$this->nombreCan= $sCadena;
	}
	function setApellido1($sCadena){
		$this->apellido1= $sCadena;
	}
	function setMailCan($sCadena){
		$this->mailCan= $sCadena;
	}
	function setEdad($sCadena){
		$this->edad = $sCadena;
	}
	function setSexo($sCadena){
		$this->sexo = $sCadena;
	}
	function setNivel($sCadena){
		$this->nivel = $sCadena;
	}
	function setFormacion($sCadena){
		$this->formacion = $sCadena;
	}
	function setArea($sCadena){
		$this->area = $sCadena;
	}
	function setTelefono($sCadena){
		$this->telefono = $sCadena;
	} 
	function getNombreCan(){
		return $this->nombreCan;
	}
	function getApellido1(){
		return $this->apellido1;
	}
	function getMailCan(){
		return $this->mailCan;
	}
	
	function getEdad(){
		return $this->edad;
	}
	function getSexo(){
		return $this->sexo;
	}
	function getNivel(){
		return $this->nivel;
	}
	function getFormacion(){
		return $this->formacion;
	}
	function getArea(){
		return $this->area;
	}
	function getTelefono(){
		return $this->telefono;
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
	* Devuelve el contenido de la propiedad idsPruebas
	* @return text
	*/
	function getIdsPruebas(){
		return $this->idsPruebas;
	}
	/**
	* Fija el contenido de la propiedad idsPruebas
	* @param idsPruebas
	* @return void
	*/
	function setIdsPruebas($sCadena){
		$this->idsPruebas = $sCadena;
	}
	
	
	/**
	* Devuelve el contenido de la propiedad idsPruebasAleatorias
	* @return text
	*/
	function getIdsPruebasAleatorias(){
		return $this->idsPruebasAleatorias;
	}
	/**
	* Fija el contenido de la propiedad idsPruebasAleatorias
	* @param idsPruebasAleatorias
	* @return void
	*/
	function setIdsPruebasAleatorias($sCadena){
		$this->idsPruebasAleatorias = $sCadena;
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
}//Fin de la Clase Empresas
?>