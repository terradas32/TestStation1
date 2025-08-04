<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase ProcesoProcesos.
**/
class ProcesoProcesos
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
		var $idProceso;
		var $idProcesoHast;
		var $idEmpresa;
		var $idEmpresaHast;
		var $nombre;
		var $descripcion;
		var $observaciones;
		var $fechaInicio;
		var $fechaInicioHast;
		var $fechaFin;
		var $fechaFinHast;
		var $idModoRealizacion;
		var $idModoRealizacionHast;
		var $envioContrasenas;
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
		$this->idProceso			= "";
		$this->idProcesoHast			= "";
		$this->idEmpresa			= "";
		$this->idEmpresaHast			= "";
		$this->nombre			= "";
		$this->descripcion			= "";
		$this->observaciones			= "";
		$this->fechaInicio			= "";
		$this->fechaInicioHast			= "";
		$this->fechaFin			= "";
		$this->fechaFinHast			= "";
		$this->idModoRealizacion			= "";
		$this->idModoRealizacionHast			= "";
		$this->envioContrasenas			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idProceso,idEmpresa,nombre,descripcion,observaciones,fechaInicio,fechaFin,idModoRealizacion,envioContrasenas,fecAlta,fecMod,usuAlta,usuMod";
		//$this->DESCListaExcel	=	"Id Proceso,Empresa,Nombre,Descripción,Observaciones,Fecha de Inicio,Fecha de Fin,Modo Realización Pruebas,Envío de Contraseñas,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
		$this->DESCListaExcel	=	constant("STR_ID_PROCESO") . "," . constant("STR_EMPRESA") . "," . constant("STR_NOMBRE") . "," . constant("STR_DESCRIPCION") . "," . constant("STR_OBSERVACIONES") . "," . constant("STR_FECHA_DE_INICIO") . "," . constant("STR_FECHA_DE_FIN") . "," . constant("STR_MODO_REALIZACIÓN_PRUEBAS") . "," . constant("STR_ENVIO_DE_CONTRASENAS") . "," . constant("STR_FECHA_DE_ALTA") . "," . constant("STR_FECHA_DE_MODIFICACION") . "," . constant("STR_USUARIO_DE_ALTA") . "," . constant("STR_USUARIO_DE_MODIFICACION") . "";
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
	* Devuelve el contenido de la propiedad observaciones
	* @return varchar(4000)
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
	* Devuelve el contenido de la propiedad fechaInicio
	* @return datetime
	*/
	function getFechaInicio(){
		return $this->fechaInicio;
	}
	/**
	* Fija el contenido de la propiedad fechaInicio
	* @param fechaInicio
	* @return void
	*/
	function setFechaInicio($sCadena){
		$this->fechaInicio = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaInicioHast
	* @return datetime
	*/
	function getFechaInicioHast(){
		return $this->fechaInicioHast;
	}
	/**
	* Fija el contenido de la propiedad fechaInicioHast
	* @param fechaInicio
	* @return void
	*/
	function setFechaInicioHast($sCadena){
		$this->fechaInicioHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaFin
	* @return datetime
	*/
	function getFechaFin(){
		return $this->fechaFin;
	}
	/**
	* Fija el contenido de la propiedad fechaFin
	* @param fechaFin
	* @return void
	*/
	function setFechaFin($sCadena){
		$this->fechaFin = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad fechaFinHast
	* @return datetime
	*/
	function getFechaFinHast(){
		return $this->fechaFinHast;
	}
	/**
	* Fija el contenido de la propiedad fechaFinHast
	* @param fechaFin
	* @return void
	*/
	function setFechaFinHast($sCadena){
		$this->fechaFinHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idModoRealizacion
	* @return int(11)
	*/
	function getIdModoRealizacion(){
		return $this->idModoRealizacion;
	}
	/**
	* Fija el contenido de la propiedad idModoRealizacion
	* @param idModoRealizacion
	* @return void
	*/
	function setIdModoRealizacion($sCadena){
		$this->idModoRealizacion = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idModoRealizacionHast
	* @return int(11)
	*/
	function getIdModoRealizacionHast(){
		return $this->idModoRealizacionHast;
	}
	/**
	* Fija el contenido de la propiedad idModoRealizacionHast
	* @param idModoRealizacion
	* @return void
	*/
	function setIdModoRealizacionHast($sCadena){
		$this->idModoRealizacionHast = $sCadena;
	}
	
	/**
	* Devuelve el contenido de la propiedad envioContrasenas
	* @return int(11)
	*/
	function getEnvioContrasenas(){
		return $this->envioContrasenas;
	}
	/**
	* Fija el contenido de la propiedad envioContrasenas
	* @param envioContrasenas
	* @return void
	*/
	function setEnvioContrasenas($sCadena){
		$this->envioContrasenas = $sCadena;
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
}//Fin de la Clase ProcesoProcesos
?>