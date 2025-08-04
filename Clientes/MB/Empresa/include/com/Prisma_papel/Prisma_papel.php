<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Prisma_papel.
**/
class Prisma_papel
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
		var $idPrisma;
		var $idPrismaHast;
		var $usuario;
		var $codigo;
		var $facultad;
		var $sexo;
		var $prisma;
		var $orden;
		var $ordenHast;
		var $carga;
		var $cargaHast;
		var $estado;
		var $estadoHast;
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
		$this->idPrisma			= "";
		$this->idPrismaHast			= "";
		$this->usuario			= "";
		$this->codigo			= "";
		$this->facultad			= "";
		$this->sexo			= "";
		$this->prisma			= "";
		$this->orden			= "";
		$this->ordenHast			= "";
		$this->carga			= "";
		$this->cargaHast			= "";
		$this->estado			= "";
		$this->estadoHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"idPrisma,usuario,codigo,facultad,sexo,prisma,orden,carga,estado,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id Prisma,Usuario de ario,Codigo,Facultad,Sexo,Prisma,Orden,Carga,Estado,Fecha de Alta,Fecha de Modificación,Usuario de Alta,Usuario de Modificación";
	}


	/**
	* Devuelve el contenido de la propiedad idPrisma
	* @return int(11)
	*/
	function getIdPrisma(){
		return $this->idPrisma;
	}
	/**
	* Fija el contenido de la propiedad idPrisma
	* @param idPrisma
	* @return void
	*/
	function setIdPrisma($sCadena){
		$this->idPrisma = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idPrismaHast
	* @return int(11)
	*/
	function getIdPrismaHast(){
		return $this->idPrismaHast;
	}
	/**
	* Fija el contenido de la propiedad idPrismaHast
	* @param idPrisma
	* @return void
	*/
	function setIdPrismaHast($sCadena){
		$this->idPrismaHast = $sCadena;
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
	* Devuelve el contenido de la propiedad facultad
	* @return varchar(255)
	*/
	function getFacultad(){
		return $this->facultad;
	}
	/**
	* Fija el contenido de la propiedad facultad
	* @param facultad
	* @return void
	*/
	function setFacultad($sCadena){
		$this->facultad = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad sexo
	* @return varchar(255)
	*/
	function getSexo(){
		return $this->sexo;
	}
	/**
	* Fija el contenido de la propiedad sexo
	* @param sexo
	* @return void
	*/
	function setSexo($sCadena){
		$this->sexo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad prisma
	* @return varchar(1000)
	*/
	function getPrisma(){
		return $this->prisma;
	}
	/**
	* Fija el contenido de la propiedad prisma
	* @param prisma
	* @return void
	*/
	function setPrisma($sCadena){
		$this->prisma = $sCadena;
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
	* Devuelve el contenido de la propiedad carga
	* @return int(11)
	*/
	function getCarga(){
		return $this->carga;
	}
	/**
	* Fija el contenido de la propiedad carga
	* @param carga
	* @return void
	*/
	function setCarga($sCadena){
		$this->carga = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad cargaHast
	* @return int(11)
	*/
	function getCargaHast(){
		return $this->cargaHast;
	}
	/**
	* Fija el contenido de la propiedad cargaHast
	* @param carga
	* @return void
	*/
	function setCargaHast($sCadena){
		$this->cargaHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estado
	* @return int(11)
	*/
	function getEstado(){
		return $this->estado;
	}
	/**
	* Fija el contenido de la propiedad estado
	* @param estado
	* @return void
	*/
	function setEstado($sCadena){
		$this->estado = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad estadoHast
	* @return int(11)
	*/
	function getEstadoHast(){
		return $this->estadoHast;
	}
	/**
	* Fija el contenido de la propiedad estadoHast
	* @param estado
	* @return void
	*/
	function setEstadoHast($sCadena){
		$this->estadoHast = $sCadena;
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
}//Fin de la Clase Prisma_papel
?>