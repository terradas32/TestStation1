<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Pruebas.
**/
class Pruebas
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
		var $idPrueba;
		var $idPruebaHast;
		var $codIdiomaIso2;
		var $codigo;
		var $nombre;
		var $descripcion;
		var $idTipoPrueba;
		var $idTipoPruebaHast;
		var $idTipoRazonamiento;
		var $idTipoRazonamientoHast;
		var $idTipoNivel;
		var $idTipoNivelHast;
		
		var $observaciones;
		var $duracion;
		var $duracion2;
		var $logoPrueba;
		var $capturaPantalla;
		var $cabecera;
		var $preguntasPorPagina;
		var $preguntasPorPaginaHast;
		var $estiloOpciones;
		var $permiteBlancos;
		
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
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->codIdiomaIso2			= "";
		$this->codigo			= "";
		$this->nombre			= "";
		$this->descripcion			= "";
		$this->idTipoPrueba			= "";
		$this->idTipoPruebaHast			= "";
		$this->idTipoRazonamiento			= "";
		$this->idTipoRazonamientoHast			= "";
		$this->idTipoNivel			= "";
		$this->idTipoNivelHast			= "";
		
		
		$this->observaciones			= "";
		$this->duracion			= "";
		$this->duracion2			= "";
		$this->logoPrueba			= "";
		$this->capturaPantalla			= "";
		$this->cabecera			= "";
		$this->preguntasPorPagina  ="";
		$this->preguntasPorPaginaHast  ="";
		$this->estiloOpciones  ="";
		$this->permiteBlancos  ="";
		
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
		$this->PKListaExcel		=	"idPrueba,codIdiomaIso2,codigo,nombre,descripcion,idTipoPrueba,idTipoRazonamiento,IdTipoNivel,observaciones,duracion,duracion2,bajaLog,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id  Prueba,Idioma,Código,Nombre,Descripción,Tipo Prueba,Razonamiento,Nivel,Observaciones,Duración,Duración 2,Baja Log,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
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
	* Devuelve el contenido de la propiedad codIdiomaIso2
	* @return char(2)
	*/
	function getCodIdiomaIso2(){
		return $this->codIdiomaIso2;
	}
	/**
	* Fija el contenido de la propiedad codIdiomaIso2
	* @param codIdiomaIso2
	* @return void
	*/
	function setCodIdiomaIso2($sCadena){
		$this->codIdiomaIso2 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad codigo
	* @return varchar(255)
	*/
	function getCodigo(){
		return $this->codigo;
	}
	/**
	* Fija el contenido de la propiedad codigo
	* @param codigo
	* @return void
	*/
	function setCodigo($sCadena){
		$this->codigo = $sCadena;
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
	* Devuelve el contenido de la propiedad idTipoPrueba
	* @return int(11)
	*/
	function getIdTipoPrueba(){
		return $this->idTipoPrueba;
	}
	/**
	* Fija el contenido de la propiedad idTipoPrueba
	* @param idTipoPrueba
	* @return void
	*/
	function setIdTipoPrueba($sCadena){
		$this->idTipoPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoPruebaHast
	* @return int(11)
	*/
	function getIdTipoPruebaHast(){
		return $this->idTipoPruebaHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoPruebaHast
	* @param idTipoPrueba
	* @return void
	*/
	function setIdTipoPruebaHast($sCadena){
		$this->idTipoPruebaHast = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad idTipoRazonamiento
	* @return int(11)
	*/
	function getIdTipoRazonamiento(){
		return $this->idTipoRazonamiento;
	}
	/**
	* Fija el contenido de la propiedad idTipoRazonamiento
	* @param idTipoRazonamiento
	* @return void
	*/
	function setIdTipoRazonamiento($sCadena){
		$this->idTipoRazonamiento = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoRazonamientoHast
	* @return int(11)
	*/
	function getIdTipoRazonamientoHast(){
		return $this->idTipoRazonamientoHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoRazonamientoHast
	* @param idTipoRazonamiento
	* @return void
	*/
	function setIdTipoRazonamientoHast($sCadena){
		$this->idTipoRazonamientoHast = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad idTipoNivel
	* @return int(11)
	*/
	function getIdTipoNivel(){
		return $this->idTipoNivel;
	}
	/**
	* Fija el contenido de la propiedad idTipoNivel
	* @param idTipoNivel
	* @return void
	*/
	function setIdTipoNivel($sCadena){
		$this->idTipoNivel = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoNivelHast
	* @return int(11)
	*/
	function getIdTipoNivelHast(){
		return $this->idTipoNivelHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoNivelHast
	* @param idTipoNivel
	* @return void
	*/
	function setIdTipoNivelHast($sCadena){
		$this->idTipoNivelHast = $sCadena;
	}
	
	
	/**
	* Devuelve el contenido de la propiedad observaciones
	* @return varchar(255)
	*/
	function getObservaciones(){
		return $this->observaciones;
	}
	/**
	* Fija el contenido de la propiedad observaciones
	* @param observaciones
	* @return void
	*/
	function setObservaciones($sCadena){
		$this->observaciones = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad duracion
	* @return varchar(255)
	*/
	function getDuracion(){
		return $this->duracion;
	}
	/**
	* Fija el contenido de la propiedad duracion
	* @param duracion
	* @return void
	*/
	function setDuracion($sCadena){
		$this->duracion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad duracion2
	* @return varchar(255)
	*/
	function getDuracion2(){
		return $this->duracion2;
	}
	/**
	* Fija el contenido de la propiedad duracion
	* @param duracion2
	* @return void
	*/
	function setDuracion2($sCadena){
		$this->duracion2 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad logoPrueba
	* @return varchar(255)
	*/
	function getLogoPrueba(){
		return $this->logoPrueba;
	}
	/**
	* Fija el contenido de la propiedad logoPrueba
	* @param logoPrueba
	* @return void
	*/
	function setLogoPrueba($sCadena){
		$this->logoPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad capturaPantalla
	* @return varchar(255)
	*/
	function getCapturaPantalla(){
		return $this->capturaPantalla;
	}
	/**
	* Fija el contenido de la propiedad capturaPantalla
	* @param capturaPantalla
	* @return void
	*/
	function setCapturaPantalla($sCadena){
		$this->capturaPantalla = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad cabecera
	* @return varchar(255)
	*/
	function getCabecera(){
		return $this->cabecera;
	}
	/**
	* Fija el contenido de la propiedad cabecera
	* @param cabecera
	* @return void
	*/
	function setCabecera($sCadena){
		$this->cabecera= $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad preguntasPorPagina
	* @return varchar(255)
	*/
	function getPreguntasPorPagina(){
		return $this->preguntasPorPagina;
	}
	/**
	* Fija el contenido de la propiedad preguntasPorPagina
	* @param cabecera
	* @return void
	*/
	function setPreguntasPorPagina($sCadena){
		$this->preguntasPorPagina= $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad preguntasPorPaginaHast
	* @return varchar(255)
	*/
	function getPreguntasPorPaginaHast(){
		return $this->preguntasPorPaginaHast;
	}
	/**
	* Fija el contenido de la propiedad preguntasPorPaginaHast
	* @param cabecera
	* @return void
	*/
	function setPreguntasPorPaginaHast($sCadena){
		$this->preguntasPorPaginaHast= $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estiloOpciones
	* @return varchar(255)
	*/
	function getEstiloOpciones(){
		return $this->estiloOpciones;
	}
	/**
	* Fija el contenido de la propiedad estiloOpciones
	* @param cabecera
	* @return void
	*/
	function setEstiloOpciones($sCadena){
		$this->estiloOpciones= $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad permiteBlancos
	* @return varchar(255)
	*/
	function getPermiteBlancos(){
		return $this->permiteBlancos;
	}
	/**
	* Fija el contenido de la propiedad permiteBlancos
	* @param cabecera
	* @return void
	*/
	function setPermiteBlancos($sCadena){
		$this->permiteBlancos= $sCadena;
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
}//Fin de la Clase Pruebas
?>