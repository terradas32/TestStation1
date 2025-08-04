<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Export_personalidad.
**/
class Export_personalidad
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
		var $descEmpresa;
		var $idProceso;
		var $idProcesoHast;
		var $descProceso;
		var $idCandidato;
		var $idCandidatoHast;
		var $nombre;
		var $apellido1;
		var $apellido2;
		var $email;
		var $dni;
		var $idPrueba;
		var $idPruebaHast;
		var $descPrueba;
		var $fecPrueba;
		var $fecPruebaHast;
		var $idBaremo;
		var $idBaremoHast;
		var $descBaremo;
		var $fecAltaProceso;
		var $fecAltaProcesoHast;
		var $correctas;
		var $contestadas;
		var $percentil;
		var $percentilHast;
		var $ir;
		var $irHast;
		var $ip;
		var $ipHast;
		var $por;
		var $porHast;
		var $estilo;
		var $idSexo;
		var $idSexoHast;
		var $descSexo;
		var $idEdad;
		var $idEdadHast;
		var $descEdad;
		var $idFormacion;
		var $idFormacionHast;
		var $descFormacion;
		var $idNivel;
		var $idNivelHast;
		var $descNivel;
		var $idArea;
		var $idAreaHast;
		var $descArea;
		var $cobrado;
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
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->descEmpresa			= "";
		$this->idProceso			= "";
		$this->idProcesoHast			= "";
		$this->descProceso			= "";
		$this->idCandidato			= "";
		$this->idCandidatoHast			= "";
		$this->nombre			= "";
		$this->apellido1			= "";
		$this->apellido2			= "";
		$this->email			= "";
		$this->dni			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->descPrueba			= "";
		$this->fecPrueba			= "";
		$this->fecPruebaHast			= "";
		$this->idBaremo			= "";
		$this->idBaremoHast			= "";
		$this->descBaremo			= "";
		$this->fecAltaProceso			= "";
		$this->fecAltaProcesoHast			= "";
		$this->correctas			= "";
		$this->contestadas			= "";
		$this->percentil			= "";
		$this->percentilHast			= "";
		$this->ir			= "";
		$this->irHast			= "";
		$this->ip			= "";
		$this->ipHast			= "";
		$this->por			= "";
		$this->porHast			= "";
		$this->estilo			= "";
		$this->idSexo			= "";
		$this->idSexoHast			= "";
		$this->descSexo			= "";
		$this->idEdad			= "";
		$this->idEdadHast			= "";
		$this->descEdad			= "";
		$this->idFormacion			= "";
		$this->idFormacionHast			= "";
		$this->descFormacion			= "";
		$this->idNivel			= "";
		$this->idNivelHast			= "";
		$this->descNivel			= "";
		$this->idArea			= "";
		$this->idAreaHast			= "";
		$this->descArea			= "";
		$this->cobrado			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"descEmpresa,descProceso,nombre,apellido1,apellido2,email,dni,descPrueba,fecPrueba,descBaremo,fecAltaProceso,correctas,contestadas,percentil,ir,ip,por,estilo,descSexo,descEdad,descFormacion,descNivel,descArea";
		if (defined("STR_EMPRESA")){
			$this->DESCListaExcel	=	constant("STR_EMPRESA") . "," . constant("STR_PROCESO") . "," . constant("STR_NOMBRE") . "," . constant("STR_APELLIDO1") . "," . constant("STR_APELLIDO2") . "," . constant("STR_EMAIL") . "," . constant("STR_DNI") . "," . constant("STR_PRUEBA") . "," . constant("STR_FECHA_DE_PRUEBA") . "," . constant("STR_BAREMO") . "," . constant("STR_FECHA_DE_ALTA_PROCESO") . "," . constant("STR_CORRECTAS") . "," . constant("STR_CONTESTADAS") . "," . constant("STR_PERCENTIL") . "," . constant("STR_INDICE_DE_RAPIDEZ") . "," . constant("STR_INDICE_DE_PRECISION") . "," . constant("STR_PRODUCTO_RENDIMIENTO") . "," . constant("STR_ESTILO") . "," . constant("STR_SEXO") . "," . constant("STR_EDAD") . "," . constant("STR_FORMACION") . "," . constant("STR_NIVEL") . "," . constant("STR_AREA") . "";
		}else {
			$this->DESCListaExcel	=	"EMPRESA,PROCESO,NOMBRE,APELLIDO1,APELLIDO2,EMAIL,DNI,PRUEBA,FECHA_DE_PRUEBA,BAREMO,FECHA_DE_ALTA_PROCESO,CORRECTAS,CONTESTADAS,PERCENTIL,INDICE_DE_RAPIDEZ,INDICE_DE_PRECISION,PRODUCTO_RENDIMIENTO,ESTILO,SEXO,EDAD,FORMACION,NIVEL,AREA";
		}
		
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
	* Devuelve el contenido de la propiedad descEmpresa
	* @return varchar(500)
	*/
	function getDescEmpresa(){
		return $this->descEmpresa;
	}
	/**
	* Fija el contenido de la propiedad descEmpresa
	* @param descEmpresa
	* @return void
	*/
	function setDescEmpresa($sCadena){
		$this->descEmpresa = $sCadena;
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
	* Devuelve el contenido de la propiedad descProceso
	* @return varchar(500)
	*/
	function getDescProceso(){
		return $this->descProceso;
	}
	/**
	* Fija el contenido de la propiedad descProceso
	* @param descProceso
	* @return void
	*/
	function setDescProceso($sCadena){
		$this->descProceso = $sCadena;
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
	* Devuelve el contenido de la propiedad idPrueba
	* @return int(11)
	*/
	function getIdPrueba(){
		return $this->idPrueba;
	}
	/**
	* Fija el contenido de la propiedad idPrueba
	* @param idPrueba
	* @return void
	*/
	function setIdPrueba($sCadena){
		$this->idPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPruebaHast
	* @return int(11)
	*/
	function getIdPruebaHast(){
		return $this->idPruebaHast;
	}
	/**
	* Fija el contenido de la propiedad idPruebaHast
	* @param idPrueba
	* @return void
	*/
	function setIdPruebaHast($sCadena){
		$this->idPruebaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descPrueba
	* @return varchar(255)
	*/
	function getDescPrueba(){
		return $this->descPrueba;
	}
	/**
	* Fija el contenido de la propiedad descPrueba
	* @param descPrueba
	* @return void
	*/
	function setDescPrueba($sCadena){
		$this->descPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecPrueba
	* @return datetime
	*/
	function getFecPrueba(){
		return $this->fecPrueba;
	}
	/**
	* Fija el contenido de la propiedad fecPrueba
	* @param fecPrueba
	* @return void
	*/
	function setFecPrueba($sCadena){
		$this->fecPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecPruebaHast
	* @return datetime
	*/
	function getFecPruebaHast(){
		return $this->fecPruebaHast;
	}
	/**
	* Fija el contenido de la propiedad fecPruebaHast
	* @param fecPrueba
	* @return void
	*/
	function setFecPruebaHast($sCadena){
		$this->fecPruebaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idBaremo
	* @return int(11)
	*/
	function getIdBaremo(){
		return $this->idBaremo;
	}
	/**
	* Fija el contenido de la propiedad idBaremo
	* @param idBaremo
	* @return void
	*/
	function setIdBaremo($sCadena){
		$this->idBaremo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idBaremoHast
	* @return int(11)
	*/
	function getIdBaremoHast(){
		return $this->idBaremoHast;
	}
	/**
	* Fija el contenido de la propiedad idBaremoHast
	* @param idBaremo
	* @return void
	*/
	function setIdBaremoHast($sCadena){
		$this->idBaremoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descBaremo
	* @return varchar(255)
	*/
	function getDescBaremo(){
		return $this->descBaremo;
	}
	/**
	* Fija el contenido de la propiedad descBaremo
	* @param descBaremo
	* @return void
	*/
	function setDescBaremo($sCadena){
		$this->descBaremo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecAltaProceso
	* @return datetime
	*/
	function getFecAltaProceso(){
		return $this->fecAltaProceso;
	}
	/**
	* Fija el contenido de la propiedad fecAltaProceso
	* @param fecAltaProceso
	* @return void
	*/
	function setFecAltaProceso($sCadena){
		$this->fecAltaProceso = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fecAltaProcesoHast
	* @return datetime
	*/
	function getFecAltaProcesoHast(){
		return $this->fecAltaProcesoHast;
	}
	/**
	* Fija el contenido de la propiedad fecAltaProcesoHast
	* @param fecAltaProceso
	* @return void
	*/
	function setFecAltaProcesoHast($sCadena){
		$this->fecAltaProcesoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad correctas
	* @return varchar(11)
	*/
	function getCorrectas(){
		return $this->correctas;
	}
	/**
	* Fija el contenido de la propiedad correctas
	* @param correctas
	* @return void
	*/
	function setCorrectas($sCadena){
		$this->correctas = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad contestadas
	* @return varchar(11)
	*/
	function getContestadas(){
		return $this->contestadas;
	}
	/**
	* Fija el contenido de la propiedad contestadas
	* @param contestadas
	* @return void
	*/
	function setContestadas($sCadena){
		$this->contestadas = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad percentil
	* @return int(11)
	*/
	function getPercentil(){
		return $this->percentil;
	}
	/**
	* Fija el contenido de la propiedad percentil
	* @param percentil
	* @return void
	*/
	function setPercentil($sCadena){
		$this->percentil = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad percentilHast
	* @return int(11)
	*/
	function getPercentilHast(){
		return $this->percentilHast;
	}
	/**
	* Fija el contenido de la propiedad percentilHast
	* @param percentil
	* @return void
	*/
	function setPercentilHast($sCadena){
		$this->percentilHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ir
	* @return decimal(10,2)
	*/
	function getIr(){
		return $this->ir;
	}
	/**
	* Fija el contenido de la propiedad ir
	* @param ir
	* @return void
	*/
	function setIr($sCadena){
		$this->ir = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad irHast
	* @return decimal(10,2)
	*/
	function getIrHast(){
		return $this->irHast;
	}
	/**
	* Fija el contenido de la propiedad irHast
	* @param ir
	* @return void
	*/
	function setIrHast($sCadena){
		$this->irHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad ip
	* @return decimal(10,2)
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
	* Devuelve el contenido de la propiedad ipHast
	* @return decimal(10,2)
	*/
	function getIpHast(){
		return $this->ipHast;
	}
	/**
	* Fija el contenido de la propiedad ipHast
	* @param ip
	* @return void
	*/
	function setIpHast($sCadena){
		$this->ipHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad por
	* @return decimal(10,2)
	*/
	function getPor(){
		return $this->por;
	}
	/**
	* Fija el contenido de la propiedad por
	* @param por
	* @return void
	*/
	function setPor($sCadena){
		$this->por = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad porHast
	* @return decimal(10,2)
	*/
	function getPorHast(){
		return $this->porHast;
	}
	/**
	* Fija el contenido de la propiedad porHast
	* @param por
	* @return void
	*/
	function setPorHast($sCadena){
		$this->porHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estilo
	* @return text
	*/
	function getEstilo(){
		return $this->estilo;
	}
	/**
	* Fija el contenido de la propiedad estilo
	* @param estilo
	* @return void
	*/
	function setEstilo($sCadena){
		$this->estilo = $sCadena;
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
	* Devuelve el contenido de la propiedad idSexoHast
	* @return int(11)
	*/
	function getIdSexoHast(){
		return $this->idSexoHast;
	}
	/**
	* Fija el contenido de la propiedad idSexoHast
	* @param idSexo
	* @return void
	*/
	function setIdSexoHast($sCadena){
		$this->idSexoHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descSexo
	* @return varchar(255)
	*/
	function getDescSexo(){
		return $this->descSexo;
	}
	/**
	* Fija el contenido de la propiedad descSexo
	* @param descSexo
	* @return void
	*/
	function setDescSexo($sCadena){
		$this->descSexo = $sCadena;
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
	* Devuelve el contenido de la propiedad idEdadHast
	* @return int(11)
	*/
	function getIdEdadHast(){
		return $this->idEdadHast;
	}
	/**
	* Fija el contenido de la propiedad idEdadHast
	* @param idEdad
	* @return void
	*/
	function setIdEdadHast($sCadena){
		$this->idEdadHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descEdad
	* @return varchar(255)
	*/
	function getDescEdad(){
		return $this->descEdad;
	}
	/**
	* Fija el contenido de la propiedad descEdad
	* @param descEdad
	* @return void
	*/
	function setDescEdad($sCadena){
		$this->descEdad = $sCadena;
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
	* Devuelve el contenido de la propiedad descFormacion
	* @return varchar(255)
	*/
	function getDescFormacion(){
		return $this->descFormacion;
	}
	/**
	* Fija el contenido de la propiedad descFormacion
	* @param descFormacion
	* @return void
	*/
	function setDescFormacion($sCadena){
		$this->descFormacion = $sCadena;
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
	* Devuelve el contenido de la propiedad descNivel
	* @return varchar(255)
	*/
	function getDescNivel(){
		return $this->descNivel;
	}
	/**
	* Fija el contenido de la propiedad descNivel
	* @param descNivel
	* @return void
	*/
	function setDescNivel($sCadena){
		$this->descNivel = $sCadena;
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
	* Devuelve el contenido de la propiedad descArea
	* @return varchar(255)
	*/
	function getDescArea(){
		return $this->descArea;
	}
	/**
	* Fija el contenido de la propiedad descArea
	* @param descArea
	* @return void
	*/
	function setDescArea($sCadena){
		$this->descArea = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad cobrado
	 * @return varchar(255)
	 */
	function getCobrado(){
		return $this->cobrado;
	}
	/**
	 * Fija el contenido de la propiedad cobrado
	 * @param cobrado
	 * @return void
	 */
	function setCobrado($sCadena){
		$this->cobrado = $sCadena;
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
}//Fin de la Clase Export_personalidad
?>