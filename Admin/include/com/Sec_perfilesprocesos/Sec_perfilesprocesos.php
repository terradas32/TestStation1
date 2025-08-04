<?php 

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Sec_perfilesprocesos.
**/
class Sec_perfilesprocesos
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
		var $IDPERFILPROCESO;
		var $IDPERFILPROCESOHast;
		var $IDPROCESO;
		var $IDPROCESOHast;
		var $DESCPROCESO;
		var $IDPERFILPUESTO;
		var $IDPERFILPUESTOHast;
		var $DESCPERFILPUESTO;
		var $BAJALOG;
		var $BAJALOGHast;
		var $fecBaja;
		var $fecBajaHast;
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
	* @param conn			Conexión
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->sOrderBy			= "";
		$this->sOrder			= "";
		$this->sLineasPagina			= "";
		$this->IDPERFILPROCESO			= "";
		$this->IDPERFILPROCESOHast			= "";
		$this->IDPROCESO			= "";
		$this->IDPROCESOHast			= "";
		$this->DESCPROCESO			= "";
		$this->IDPERFILPUESTO			= "";
		$this->IDPERFILPUESTOHast			= "";
		$this->DESCPERFILPUESTO			= "";
		$this->BAJALOG			= "";
		$this->BAJALOGHast			= "";
		$this->fecBaja			= "";
		$this->fecBajaHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"IDPERFILPROCESO,DESCPROCESO,DESCPERFILPUESTO,BAJALOG,fecBaja,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id,Proceso,Perfil Puesto,Estado,Fecha Estado,Fec. Alta,Fec. Mod.,Usu. Alta,Usu. Mod";
	}


	/**
	* Devuelve el contenido de la propiedad IDPERFILPROCESO
	* @return int(11)
	*/
	function getIDPERFILPROCESO(){
		return $this->IDPERFILPROCESO;
	}
	/**
	* Fija el contenido de la propiedad IDPERFILPROCESO
	* @param IDPERFILPROCESO
	* @return void
	*/
	function setIDPERFILPROCESO($sCadena){
		$this->IDPERFILPROCESO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPERFILPROCESOHast
	* @return int(11)
	*/
	function getIDPERFILPROCESOHast(){
		return $this->IDPERFILPROCESOHast;
	}
	/**
	* Fija el contenido de la propiedad IDPERFILPROCESOHast
	* @param IDPERFILPROCESO
	* @return void
	*/
	function setIDPERFILPROCESOHast($sCadena){
		$this->IDPERFILPROCESOHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPROCESO
	* @return int(11)
	*/
	function getIDPROCESO(){
		return $this->IDPROCESO;
	}
	/**
	* Fija el contenido de la propiedad IDPROCESO
	* @param IDPROCESO
	* @return void
	*/
	function setIDPROCESO($sCadena){
		$this->IDPROCESO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPROCESOHast
	* @return int(11)
	*/
	function getIDPROCESOHast(){
		return $this->IDPROCESOHast;
	}
	/**
	* Fija el contenido de la propiedad IDPROCESOHast
	* @param IDPROCESO
	* @return void
	*/
	function setIDPROCESOHast($sCadena){
		$this->IDPROCESOHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DESCPROCESO
	* @return varchar(255)
	*/
	function getDESCPROCESO(){
		return $this->DESCPROCESO;
	}
	/**
	* Fija el contenido de la propiedad DESCPROCESO
	* @param DESCPROCESO
	* @return void
	*/
	function setDESCPROCESO($sCadena){
		$this->DESCPROCESO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPERFILPUESTO
	* @return int(11)
	*/
	function getIDPERFILPUESTO(){
		return $this->IDPERFILPUESTO;
	}
	/**
	* Fija el contenido de la propiedad IDPERFILPUESTO
	* @param IDPERFILPUESTO
	* @return void
	*/
	function setIDPERFILPUESTO($sCadena){
		$this->IDPERFILPUESTO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPERFILPUESTOHast
	* @return int(11)
	*/
	function getIDPERFILPUESTOHast(){
		return $this->IDPERFILPUESTOHast;
	}
	/**
	* Fija el contenido de la propiedad IDPERFILPUESTOHast
	* @param IDPERFILPUESTO
	* @return void
	*/
	function setIDPERFILPUESTOHast($sCadena){
		$this->IDPERFILPUESTOHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DESCPERFILPUESTO
	* @return varchar(255)
	*/
	function getDESCPERFILPUESTO(){
		return $this->DESCPERFILPUESTO;
	}
	/**
	* Fija el contenido de la propiedad DESCPERFILPUESTO
	* @param DESCPERFILPUESTO
	* @return void
	*/
	function setDESCPERFILPUESTO($sCadena){
		$this->DESCPERFILPUESTO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad BAJALOG
	* @return int(11)
	*/
	function getBAJALOG(){
		return $this->BAJALOG;
	}
	/**
	* Fija el contenido de la propiedad BAJALOG
	* @param BAJALOG
	* @return void
	*/
	function setBAJALOG($sCadena){
		$this->BAJALOG = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad BAJALOGHast
	* @return int(11)
	*/
	function getBAJALOGHast(){
		return $this->BAJALOGHast;
	}
	/**
	* Fija el contenido de la propiedad BAJALOGHast
	* @param BAJALOG
	* @return void
	*/
	function setBAJALOGHast($sCadena){
		$this->BAJALOGHast = $sCadena;
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
}//Fin de la Clase Sec_perfilesprocesos
?>