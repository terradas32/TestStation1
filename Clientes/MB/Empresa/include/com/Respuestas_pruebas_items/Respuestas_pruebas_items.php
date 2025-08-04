<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Respuestas_pruebas_items.
**/
class Respuestas_pruebas_items
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
		var $descCandidato;
		var $codIdiomaIso2;
		var $descIdiomaIso2;
		var $idPrueba;
		var $idPruebaHast;
		var $descPrueba;
		var $idItem;
		var $idItemHast;
		var $descItem;
		var $idOpcion;
		var $idOpcionHast;
		var $descOpcion;
		var $codigo;
		var $orden;
		var $ordenHast;
		var $valor;
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
		$this->descCandidato			= "";
		$this->codIdiomaIso2			= "";
		$this->descIdiomaIso2			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->descPrueba			= "";
		$this->idItem			= "";
		$this->idItemHast			= "";
		$this->descItem			= "";
		$this->idOpcion			= "";
		$this->idOpcionHast			= "";
		$this->descOpcion			= "";
		$this->codigo			= "";
		$this->orden			= "";
		$this->ordenHast			= "";
		$this->valor			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"descEmpresa,descProceso,descCandidato,descIdiomaIso2,descPrueba,descItem,descOpcion,codigo,orden,valor,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Empresa,Proceso,Candidato,Idioma,Prueba,Item,Opción,Código,Orden,Valor,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
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
	* @return varchar(255)
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
	* @return varchar(255)
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
	* Devuelve el contenido de la propiedad descCandidato
	* @return varchar(255)
	*/
	function getDescCandidato(){
		return $this->descCandidato;
	}
	/**
	* Fija el contenido de la propiedad descCandidato
	* @param descCandidato
	* @return void
	*/
	function setDescCandidato($sCadena){
		$this->descCandidato = $sCadena;
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
	* Devuelve el contenido de la propiedad descIdiomaIso2
	* @return varchar(255)
	*/
	function getDescIdiomaIso2(){
		return $this->descIdiomaIso2;
	}
	/**
	* Fija el contenido de la propiedad descIdiomaIso2
	* @param descIdiomaIso2
	* @return void
	*/
	function setDescIdiomaIso2($sCadena){
		$this->descIdiomaIso2 = $sCadena;
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
	* Devuelve el contenido de la propiedad idItem
	* @return int(11)
	*/
	function getIdItem(){
		return $this->idItem;
	}
	/**
	* Fija el contenido de la propiedad idItem
	* @param idItem
	* @return void
	*/
	function setIdItem($sCadena){
		$this->idItem = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idItemHast
	* @return int(11)
	*/
	function getIdItemHast(){
		return $this->idItemHast;
	}
	/**
	* Fija el contenido de la propiedad idItemHast
	* @param idItem
	* @return void
	*/
	function setIdItemHast($sCadena){
		$this->idItemHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descItem
	* @return varchar(255)
	*/
	function getDescItem(){
		return $this->descItem;
	}
	/**
	* Fija el contenido de la propiedad descItem
	* @param descItem
	* @return void
	*/
	function setDescItem($sCadena){
		$this->descItem = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idOpcion
	* @return int(11)
	*/
	function getIdOpcion(){
		return $this->idOpcion;
	}
	/**
	* Fija el contenido de la propiedad idOpcion
	* @param idOpcion
	* @return void
	*/
	function setIdOpcion($sCadena){
		$this->idOpcion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idOpcionHast
	* @return int(11)
	*/
	function getIdOpcionHast(){
		return $this->idOpcionHast;
	}
	/**
	* Fija el contenido de la propiedad idOpcionHast
	* @param idOpcion
	* @return void
	*/
	function setIdOpcionHast($sCadena){
		$this->idOpcionHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad descOpcion
	* @return varchar(255)
	*/
	function getDescOpcion(){
		return $this->descOpcion;
	}
	/**
	* Fija el contenido de la propiedad descOpcion
	* @param descOpcion
	* @return void
	*/
	function setDescOpcion($sCadena){
		$this->descOpcion = $sCadena;
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
	* @param orden
	* @return void
	*/
	function setOrdenHast($sCadena){
		$this->ordenHast = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad valor
	* @return varchar
	*/
	function getValor(){
		return $this->valor;
	}
	/**
	* Fija el contenido de la propiedad valor
	* @param valor
	* @return void
	*/
	function setValor($sCadena){
		$this->valor = $sCadena;
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
}//Fin de la Clase Respuestas_pruebas_items
?>