<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Candidatos.
**/
class Candidatos
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
		var $idEmpresa;
		var $idEmpresaHast;
		var $idProceso;
		var $idProcesoHast;
		var $idCandidato;
		var $idCandidatoHast;
		var $nombre;
		var $apellido1;
		var $apellido2;
		var $dni;
		var $mail;
		var $password;
		var $idSexo;
		var $idEdad;
		var $fechaNacimiento;
		var $fechaNacimientoHast;
		var $idPais;
		var $idPaisHast;
		var $idProvincia;
		var $idProvinciaHast;
		var $idCiudad;
		var $idCiudadHast;
		var $idZona;
		var $idZonaHast;
		var $direccion;
		var $codPostal;
		var $idFormacion;
		var $idFormacionHast;
		var $idNivel;
		var $idNivelHast;
		var $idArea;
		var $idAreaHast;
		var $idTipoTelefono;
		var $idTipoTelefonoHast;
		var $telefono;
		var $estadoCivil;
		var $nacionalidad;
		var $informado;
		var $informadoHast;
		var $finalizado;
		var $finalizadoHast;
		var $fechaFinalizado;
		var $fechaFinalizadoHast;
		var $fecAlta;
		var $fecAltaHast;
		var $fecMod;
		var $fecModHast;
		var $usuAlta;
		var $usuAltaHast;
		var $usuMod;
		var $usuModHast;
		var $ultimoLogin;
		var $ultimoLoginHast;
		var $token;
		var $ultimaAcc;
		var $ultimaAccHast;
	/**
	* Constructor q inicializa los datos de la clase.
	* @param $conn			Conexión
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->idProceso			= "";
		$this->idProcesoHast			= "";
		$this->idCandidato			= "";
		$this->idCandidatoHast			= "";
		$this->nombre			= "";
		$this->apellido1			= "";
		$this->apellido2			= "";
		$this->dni			= "";
		$this->mail			= "";
		$this->password			= "";
		$this->idSexo			= "";
		$this->idEdad			= "";
		$this->fechaNacimiento			= "";
		$this->fechaNacimientoHast			= "";
		$this->idPais			= "";
		$this->idPaisHast			= "";
		$this->idProvincia			= "";
		$this->idProvinciaHast			= "";
		$this->idCiudad			= "";
		$this->idCiudadHast			= "";
		$this->idZona			= "";
		$this->idZonaHast			= "";
		$this->direccion			= "";
		$this->codPostal			= "";
		$this->idFormacion			= "";
		$this->idFormacionHast			= "";
		$this->idNivel			= "";
		$this->idNivelHast			= "";
		$this->idArea			= "";
		$this->idAreaHast			= "";
		$this->idTipoTelefono			= "";
		$this->idTipoTelefonoHast			= "";
		$this->telefono			= "";
		$this->estadoCivil			= "";
		$this->nacionalidad			= "";
		$this->informado			= "";
		$this->informadoHast			= "";
		$this->finalizado			= "";
		$this->finalizadoHast			= "";
		$this->fechaFinalizado			= "";
		$this->fechaFinalizadoHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->ultimoLogin			= "";
		$this->ultimoLoginHast			= "";
		$this->token			= "";
		$this->ultimaAcc			= "";
		$this->ultimaAccHast			= "";
		
		$this->PKListaExcel		=	"idEmpresa,idProceso,idCandidato,nombre,apellido1,apellido2,dni,mail,password,sexo,fechaNacimiento,idPais,idProvincia,idCiudad,idZona,direccion,codPostal,idFormacion,idNivel,idArea,idTipoTelefono,telefono,estadoCivil,nacionalidad,informado,finalizado,fechaFinalizado,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id  Empresa,Id  Proceso,Id  Candidato,Nombre,Apellido1,Apellido2,DNI/ N. ID,Mail,Password,Sexo,Fecha de Nacimiento,Id  País,Id  Provincía,Id  Ciudad,Id  Zona,Dirección,Cod Postal,Id Formación,Id  Nivel,Id  Area,Id  Tipo Teléfono,Teléfono,Estado Civil,Nacionalidad,Informado,Finalizado,Fecha de Finalizado,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
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
	* Devuelve el contenido de la propiedad dni
	* @return varchar(255)
	*/
	function getDni(){
		return $this->dni;
	}
	/**
	* Fija el contenido de la propiedad dni
	* @param dni
	* @return void
	*/
	function setDni($sCadena){
		$this->dni = $sCadena;
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
	* Devuelve el contenido de la propiedad idSexo
	* @return int(11)
	*/
	function getIdSexo(){
		return $this->idSexo;
	}
	/**
	* Fija el contenido de la propiedad idSexo
	* @param idSexo
	* @return void
	*/
	function setIdSexo($sCadena){
		$this->idSexo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idEdad
	* @return int(11)
	*/
	function getIdEdad(){
		return $this->idEdad;
	}
	/**
	* Fija el contenido de la propiedad idEdad
	* @param idEdad
	* @return void
	*/
	function setIdEdad($sCadena){
		$this->idEdad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaNacimiento
	* @return datetime
	*/
	function getFechaNacimiento(){
		return $this->fechaNacimiento;
	}
	/**
	* Fija el contenido de la propiedad fechaNacimiento
	* @param fechaNacimiento
	* @return void
	*/
	function setFechaNacimiento($sCadena){
		$this->fechaNacimiento = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaNacimientoHast
	* @return datetime
	*/
	function getFechaNacimientoHast(){
		return $this->fechaNacimientoHast;
	}
	/**
	* Fija el contenido de la propiedad fechaNacimientoHast
	* @param fechaNacimiento
	* @return void
	*/
	function setFechaNacimientoHast($sCadena){
		$this->fechaNacimientoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPais
	* @return int(11)
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
	* Devuelve el contenido de la propiedad idPaisHast
	* @return int(11)
	*/
	function getIdPaisHast(){
		return $this->idPaisHast;
	}
	/**
	* Fija el contenido de la propiedad idPaisHast
	* @param idPais
	* @return void
	*/
	function setIdPaisHast($sCadena){
		$this->idPaisHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idProvincia
	* @return int(11)
	*/
	function getIdProvincia(){
		return $this->idProvincia;
	}
	/**
	* Fija el contenido de la propiedad idProvincia
	* @param idProvincia
	* @return void
	*/
	function setIdProvincia($sCadena){
		$this->idProvincia = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idProvinciaHast
	* @return int(11)
	*/
	function getIdProvinciaHast(){
		return $this->idProvinciaHast;
	}
	/**
	* Fija el contenido de la propiedad idProvinciaHast
	* @param idProvincia
	* @return void
	*/
	function setIdProvinciaHast($sCadena){
		$this->idProvinciaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCiudad
	* @return int(11)
	*/
	function getIdCiudad(){
		return $this->idCiudad;
	}
	/**
	* Fija el contenido de la propiedad idCiudad
	* @param idCiudad
	* @return void
	*/
	function setIdCiudad($sCadena){
		$this->idCiudad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idCiudadHast
	* @return int(11)
	*/
	function getIdCiudadHast(){
		return $this->idCiudadHast;
	}
	/**
	* Fija el contenido de la propiedad idCiudadHast
	* @param idCiudad
	* @return void
	*/
	function setIdCiudadHast($sCadena){
		$this->idCiudadHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idZona
	* @return int(11)
	*/
	function getIdZona(){
		return $this->idZona;
	}
	/**
	* Fija el contenido de la propiedad idZona
	* @param idZona
	* @return void
	*/
	function setIdZona($sCadena){
		$this->idZona = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idZonaHast
	* @return int(11)
	*/
	function getIdZonaHast(){
		return $this->idZonaHast;
	}
	/**
	* Fija el contenido de la propiedad idZonaHast
	* @param idZona
	* @return void
	*/
	function setIdZonaHast($sCadena){
		$this->idZonaHast = $sCadena;
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
	* @return varchar(255)
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
	* Devuelve el contenido de la propiedad idFormacion
	* @return int(11)
	*/
	function getIdFormacion(){
		return $this->idFormacion;
	}
	/**
	* Fija el contenido de la propiedad idFormacion
	* @param idFormacion
	* @return void
	*/
	function setIdFormacion($sCadena){
		$this->idFormacion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idFormacionHast
	* @return int(11)
	*/
	function getIdFormacionHast(){
		return $this->idFormacionHast;
	}
	/**
	* Fija el contenido de la propiedad idFormacionHast
	* @param idFormacion
	* @return void
	*/
	function setIdFormacionHast($sCadena){
		$this->idFormacionHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idNivel
	* @return int(11)
	*/
	function getIdNivel(){
		return $this->idNivel;
	}
	/**
	* Fija el contenido de la propiedad idNivel
	* @param idNivel
	* @return void
	*/
	function setIdNivel($sCadena){
		$this->idNivel = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idNivelHast
	* @return int(11)
	*/
	function getIdNivelHast(){
		return $this->idNivelHast;
	}
	/**
	* Fija el contenido de la propiedad idNivelHast
	* @param idNivel
	* @return void
	*/
	function setIdNivelHast($sCadena){
		$this->idNivelHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idArea
	* @return int(11)
	*/
	function getIdArea(){
		return $this->idArea;
	}
	/**
	* Fija el contenido de la propiedad idArea
	* @param idArea
	* @return void
	*/
	function setIdArea($sCadena){
		$this->idArea = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idAreaHast
	* @return int(11)
	*/
	function getIdAreaHast(){
		return $this->idAreaHast;
	}
	/**
	* Fija el contenido de la propiedad idAreaHast
	* @param idArea
	* @return void
	*/
	function setIdAreaHast($sCadena){
		$this->idAreaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoTelefono
	* @return int(11)
	*/
	function getIdTipoTelefono(){
		return $this->idTipoTelefono;
	}
	/**
	* Fija el contenido de la propiedad idTipoTelefono
	* @param idTipoTelefono
	* @return void
	*/
	function setIdTipoTelefono($sCadena){
		$this->idTipoTelefono = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoTelefonoHast
	* @return int(11)
	*/
	function getIdTipoTelefonoHast(){
		return $this->idTipoTelefonoHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoTelefonoHast
	* @param idTipoTelefono
	* @return void
	*/
	function setIdTipoTelefonoHast($sCadena){
		$this->idTipoTelefonoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad telefono
	* @return varchar(255)
	*/
	function getTelefono(){
		return $this->telefono;
	}
	/**
	* Fija el contenido de la propiedad telefono
	* @param telefono
	* @return void
	*/
	function setTelefono($sCadena){
		$this->telefono = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estadoCivil
	* @return varchar(255)
	*/
	function getEstadoCivil(){
		return $this->estadoCivil;
	}
	/**
	* Fija el contenido de la propiedad estadoCivil
	* @param estadoCivil
	* @return void
	*/
	function setEstadoCivil($sCadena){
		$this->estadoCivil = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nacionalidad
	* @return varchar(255)
	*/
	function getNacionalidad(){
		return $this->nacionalidad;
	}
	/**
	* Fija el contenido de la propiedad nacionalidad
	* @param nacionalidad
	* @return void
	*/
	function setNacionalidad($sCadena){
		$this->nacionalidad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad informado
	* @return int(11)
	*/
	function getInformado(){
		return $this->informado;
	}
	/**
	* Fija el contenido de la propiedad informado
	* @param informado
	* @return void
	*/
	function setInformado($sCadena){
		$this->informado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad informadoHast
	* @return int(11)
	*/
	function getInformadoHast(){
		return $this->informadoHast;
	}
	/**
	* Fija el contenido de la propiedad informadoHast
	* @param informado
	* @return void
	*/
	function setInformadoHast($sCadena){
		$this->informadoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad finalizado
	* @return int(11)
	*/
	function getFinalizado(){
		return $this->finalizado;
	}
	/**
	* Fija el contenido de la propiedad finalizado
	* @param finalizado
	* @return void
	*/
	function setFinalizado($sCadena){
		$this->finalizado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad finalizadoHast
	* @return int(11)
	*/
	function getFinalizadoHast(){
		return $this->finalizadoHast;
	}
	/**
	* Fija el contenido de la propiedad finalizadoHast
	* @param finalizado
	* @return void
	*/
	function setFinalizadoHast($sCadena){
		$this->finalizadoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaFinalizado
	* @return datetime
	*/
	function getFechaFinalizado(){
		return $this->fechaFinalizado;
	}
	/**
	* Fija el contenido de la propiedad fechaFinalizado
	* @param fechaFinalizado
	* @return void
	*/
	function setFechaFinalizado($sCadena){
		$this->fechaFinalizado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaFinalizadoHast
	* @return datetime
	*/
	function getFechaFinalizadoHast(){
		return $this->fechaFinalizadoHast;
	}
	/**
	* Fija el contenido de la propiedad fechaFinalizadoHast
	* @param fechaFinalizado
	* @return void
	*/
	function setFechaFinalizadoHast($sCadena){
		$this->fechaFinalizadoHast = $sCadena;
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
}//Fin de la Clase Candidatos
?>