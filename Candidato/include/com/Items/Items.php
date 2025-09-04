<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Items.
**/
class Items
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
		var $idItem;
		var $idItemHast;
		var $idPrueba;
		var $idPruebaHast;
		var $codIdiomaIso2;
		var $enunciado;
		var $descripcion;
		var $path1;
		var $path2;
		var $path3;
		var $path4;
		var $correcto;
		var $orden;
		var $ordenHast;
		var $tipoItem;
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
		var $idTipoRazonamiento;
		var $id;
		var $index_tri;
	/**
	* Constructor q inicializa los datos de la clase.
	* @param $conn			Conexión
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->idItem			= "";
		$this->idItemHast			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->codIdiomaIso2			= "";
		$this->enunciado			= "";
		$this->descripcion			= "";
		$this->path1			= "";
		$this->path2			= "";
		$this->path3			= "";
		$this->path4			= "";
		$this->correcto			= "";
		$this->orden			= "";
		$this->ordenHast			= "";
		$this->tipoItem			= "";
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
		$this->idTipoRazonamiento			= "";
		$this->index_tri			= "";
		$this->id			= "";
		
		$this->PKListaExcel		=	"idItem,idPrueba,codIdiomaIso2,enunciado,pathEnunciado,descripcion,pathDescripcion,correcto,bajaLog,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id  Item,Id  Prueba,Idioma,Enunciado,Path Enunciado,Descripción,PathDescripción,Correcto,Baja Log,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";

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
	* Devuelve el contenido de la propiedad enunciado
	* @return varchar(255)
	*/
	function getEnunciado(){
		return $this->enunciado;
	}
	/**
	* Fija el contenido de la propiedad enunciado
	* @param enunciado
	* @return void
	*/
	function setEnunciado($sCadena){
		$this->enunciado = $sCadena;
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
	* Devuelve el contenido de la propiedad path1
	* @return varchar(255)
	*/
	function getPath1(){
		return $this->path1;
	}
	/**
	* Fija el contenido de la propiedad path1
	* @param path1
	* @return void
	*/
	function setPath1($sCadena){
		$this->path1 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad path2
	* @return varchar(255)
	*/
	function getPath2(){
		return $this->path2;
	}
	/**
	* Fija el contenido de la propiedad path2
	* @param path2
	* @return void
	*/
	function setPath2($sCadena){
		$this->path2 = $sCadena;
	}
/**
	* Devuelve el contenido de la propiedad path3
	* @return varchar(255)
	*/
	function getPath3(){
		return $this->path3;
	}
	/**
	* Fija el contenido de la propiedad path3
	* @param path3
	* @return void
	*/
	function setPath3($sCadena){
		$this->path3 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad path4
	* @return varchar(255)
	*/
	function getPath4(){
		return $this->path4;
	}
	/**
	* Fija el contenido de la propiedad path4
	* @param path4
	* @return void
	*/
	function setPath4($sCadena){
		$this->path4 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad correcto
	* @return varchar(255)
	*/
	function getCorrecto(){
		return $this->correcto;
	}
	/**
	* Fija el contenido de la propiedad correcto
	* @param correcto
	* @return void
	*/
	function setCorrecto($sCadena){
		$this->correcto = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad orden
	* @return varchar(255)
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
	* Devuelve el contenido de la propiedad tipoItem
	* @return int(11)
	*/
	function getTipoItem(){
		return $this->tipoItem;
	}
	/**
	* Fija el contenido de la propiedad tipoItem
	* @param tipoItem
	* @return void
	*/
	function setTipoItem($sCadena){
		$this->tipoItem = $sCadena;
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
	* Devuelve el contenido de la propiedad IdTipoRazonamiento
	* @return int(11)
	*/
	function getIdTipoRazonamiento(){
		return $this->idTipoRazonamiento;
	}
	/**
	* Fija el contenido de la propiedad IdTipoRazonamiento
	* @param usuMod
	* @return void
	*/
	function setIdTipoRazonamiento($sCadena){
		$this->idTipoRazonamiento = $sCadena;
	}

		/**
	* Devuelve el contenido de la propiedad index_tri
	* @return int(11)
	*/
	function getIndex_tri(){
		return $this->index_tri;
	}
	/**
	* Fija el contenido de la propiedad index_tri
	* @param index_tri
	* @return void
	*/
	function setIndex_tri($sCadena){
		$this->index_tri = $sCadena;
	}

	
	/**
	* Devuelve el contenido de la propiedad id
	* @return int(11)
	*/
	function getId(){
		return $this->id;
	}
	/**
	* Fija el contenido de la propiedad id
	* @param id
	* @return void
	*/
	function setId($sCadena){
		$this->id = $sCadena;
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
}//Fin de la Clase Items
?>