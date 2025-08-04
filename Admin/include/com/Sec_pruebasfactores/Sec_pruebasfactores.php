<?php 
/**
* Crea un objeto de la clase y almacena en �l 
* los valores de la entidad de clase Sec_pruebasfactores.
**/
class Sec_pruebasfactores
{
	
	/**
	* Declaraci�n de las variables de Entidad.
	**/
		var $iCont; //Contador Global
		var $aBusqueda; //Par�metros del buscador.
		var $sOrderBy; //Campo order de la query de b�squeda.
		var $sOrder; //Orden DESC ASC.
		var $sLineasPagina; //L�neas por p�gina.
		var $PKListaExcel; //Campos de select para Excel
		var $DESCListaExcel; //Descripci�n a presentar en Excel
		var $IDTIPOPRUEBA;
		var $IDTIPOPRUEBAHast;
		var $IDPRUEBA;
		var $IDPRUEBAHast;
		var $IDFACTOR;
		var $IDFACTORHast;
		var $CODFACTOR;
		var $fecAlta;
		var $fecAltaHast;
		var $fecMod;
		var $fecModHast;
		var $usuAlta;
		var $usuAltaHast;
		var $usuMod;
		var $usuModHast;
		var $IDTIPOPRUEBAANTERIOR;
		var $IDPRUEBAANTERIOR;
		var $IDFACTORANTERIOR;
	/**
	* Constructor q inicializa los datos de la clase.
	* @param conn			Conexi�n
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->sOrderBy			= "";
		$this->sOrder			= "";
		$this->sLineasPagina			= "";
		$this->IDTIPOPRUEBA			= "";
		$this->IDTIPOPRUEBAHast			= "";
		$this->IDPRUEBA			= "";
		$this->IDPRUEBAHast			= "";
		$this->IDFACTOR			= "";
		$this->IDFACTORHast			= "";
		$this->CODFACTOR			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->IDTIPOPRUEBAANTERIOR			= "";
		$this->IDPRUEBAANTERIOR			= "";
		$this->IDFACTORANTERIOR			= "";
		$this->PKListaExcel		=	"IDTIPOPRUEBA,IDPRUEBA,IDFACTOR,CODFACTOR,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Yipo de prueba,Prueba,Factor / Competencia,C�digo,Fec. alta,Fec. mod,Usu. alta,Usu. mod";
	}


	/**
	* Devuelve el contenido de la propiedad IDTIPOPRUEBA
	* @return int(11)
	*/
	function getIDTIPOPRUEBA(){
		return $this->IDTIPOPRUEBA;
	}
	/**
	* Fija el contenido de la propiedad IDTIPOPRUEBA
	* @param IDTIPOPRUEBA
	* @return void
	*/
	function setIDTIPOPRUEBA($sCadena){
		$this->IDTIPOPRUEBA = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDTIPOPRUEBAHast
	* @return int(11)
	*/
	function getIDTIPOPRUEBAHast(){
		return $this->IDTIPOPRUEBAHast;
	}
	/**
	* Fija el contenido de la propiedad IDTIPOPRUEBAHast
	* @param IDTIPOPRUEBA
	* @return void
	*/
	function setIDTIPOPRUEBAHast($sCadena){
		$this->IDTIPOPRUEBAHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPRUEBA
	* @return int(11)
	*/
	function getIDPRUEBA(){
		return $this->IDPRUEBA;
	}
	/**
	* Fija el contenido de la propiedad IDPRUEBA
	* @param IDPRUEBA
	* @return void
	*/
	function setIDPRUEBA($sCadena){
		$this->IDPRUEBA = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPRUEBAHast
	* @return int(11)
	*/
	function getIDPRUEBAHast(){
		return $this->IDPRUEBAHast;
	}
	/**
	* Fija el contenido de la propiedad IDPRUEBAHast
	* @param IDPRUEBA
	* @return void
	*/
	function setIDPRUEBAHast($sCadena){
		$this->IDPRUEBAHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDFACTOR
	* @return int(11)
	*/
	function getIDFACTOR(){
		return $this->IDFACTOR;
	}
	/**
	* Fija el contenido de la propiedad IDFACTOR
	* @param IDFACTOR
	* @return void
	*/
	function setIDFACTOR($sCadena){
		$this->IDFACTOR = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDFACTORHast
	* @return int(11)
	*/
	function getIDFACTORHast(){
		return $this->IDFACTORHast;
	}
	/**
	* Fija el contenido de la propiedad IDFACTORHast
	* @param IDFACTOR
	* @return void
	*/
	function setIDFACTORHast($sCadena){
		$this->IDFACTORHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad CODFACTOR
	* @return varchar(255)
	*/
	function getCODFACTOR(){
		return $this->CODFACTOR;
	}
	/**
	* Fija el contenido de la propiedad CODFACTOR
	* @param CODFACTOR
	* @return void
	*/
	function setCODFACTOR($sCadena){
		$this->CODFACTOR = $sCadena;
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
	* Devuelve el contenido de la propiedad IDTIPOPRUEBAANTERIOR
	* @return int(11)
	*/
	function getIDTIPOPRUEBAANTERIOR(){
		return $this->IDTIPOPRUEBAANTERIOR;
	}
	/**
	* Fija el contenido de la propiedad IDTIPOPRUEBAANTERIOR
	* @param IDTIPOPRUEBAANTERIOR
	* @return void
	*/
	function setIDTIPOPRUEBAANTERIOR($sCadena){
		$this->IDTIPOPRUEBAANTERIOR = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDPRUEBAANTERIOR
	* @return int(11)
	*/
	function getIDPRUEBAANTERIOR(){
		return $this->IDPRUEBAANTERIOR;
	}
	/**
	* Fija el contenido de la propiedad IDPRUEBAANTERIOR
	* @param IDPRUEBAANTERIOR
	* @return void
	*/
	function setIDPRUEBAANTERIOR($sCadena){
		$this->IDPRUEBAANTERIOR = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDFACTORANTERIOR
	* @return int(11)
	*/
	function getIDFACTORANTERIOR(){
		return $this->IDFACTORANTERIOR;
	}
	/**
	* Fija el contenido de la propiedad IDFACTORANTERIOR
	* @param IDFACTORANTERIOR
	* @return void
	*/
	function setIDFACTORANTERIOR($sCadena){
		$this->IDFACTORANTERIOR = $sCadena;
	}
	/**
	* Devuelve el contenido de los par�metros de B�squeda.
	* @return Array aBusqueda.
	*/
	function getBusqueda(){
		return $this->aBusqueda;
	}
	/**
	* Fija el contenido de los par�metros de B�squeda.
	* @param Literal descriptivo del campo.
	* @param Valor del campo de B�squeda.
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
	* Devuelve el n�mero de filas a pintar en la paginaci�n.
	* @return Int n�mero de l�neas para la paginaci�n.
	*/
	function getLineasPagina(){
		return $this->sLineasPagina;
	}
	/**
	* Fija el n�mero de filas a pintar en la paginaci�n.
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
	* Devuelve la descripci�n de los campos a seleccionar para excel.
	* @return String separado por comas de la descripci�n de los campos.
	*/
	function getDESCListaExcel(){
		return $this->DESCListaExcel;
	}
	/**
	* Fija la descripci�n de los campos a seleccionar para excel.
	* @param String separado por comas de la descripci�n de los campos.
	* @return void
	*/
	function setDESCListaExcel($sCadena){
		$this->DESCListaExcel = $sCadena;
	}
}//Fin de la Clase Sec_pruebasfactores
?>