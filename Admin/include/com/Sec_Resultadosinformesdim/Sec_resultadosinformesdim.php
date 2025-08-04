<?php 
/**
* Crea un objeto de la clase y almacena en �l 
* los valores de la entidad de clase Sec_resultadosinformesdim.
**/
class Sec_resultadosinformesdim
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
		var $IDCANDIDATO;
		var $IDCANDIDATOHast;
		var $IDPROCESO;
		var $IDPROCESOHast;
		var $IDDIMENSION;
		var $IDDIMENSIONHast;
		var $CODDIMENSION;
		var $DESCDIMENSION;
		var $RESULTDIMENSION;
		var $RESULTDIMENSIONHast;
		var $RESULTSOBRE10;
		var $RESULTSOBRE10Hast;
		var $MEDIAPONDERADA;
		var $MEDIAPONDERADAHast;
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
	* @param conn			Conexi�n
	**/
	function __construct()
	{
		$this->iCont			= 0;
		$this->aBusqueda			= array();
		$this->sOrderBy			= "";
		$this->sOrder			= "";
		$this->sLineasPagina			= "";
		$this->IDCANDIDATO			= "";
		$this->IDCANDIDATOHast			= "";
		$this->IDPROCESO			= "";
		$this->IDPROCESOHast			= "";
		$this->IDDIMENSION			= "";
		$this->IDDIMENSIONHast			= "";
		$this->CODDIMENSION			= "";
		$this->DESCDIMENSION			= "";
		$this->RESULTDIMENSION			= "";
		$this->RESULTDIMENSIONHast			= "";
		$this->RESULTSOBRE10			= "";
		$this->RESULTSOBRE10Hast			= "";
		$this->MEDIAPONDERADA			= "";
		$this->MEDIAPONDERADAHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"IDCANDIDATO,IDPROCESO,IDDIMENSION,CODDIMENSION,DESCDIMENSION,RESULTDIMENSION,RESULTSOBRE10,MEDIAPONDERADA,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id candidato,Id proceso,Id dimensi�n,Coddimensi�n,Descdimensi�n,Resultdimensi�n,Resultsobre10,Mediaponderada,Fec. alta,Fec. mod,Usu. alta,Usu. mod";
	}


	/**
	* Devuelve el contenido de la propiedad IDCANDIDATO
	* @return int(11)
	*/
	function getIDCANDIDATO(){
		return $this->IDCANDIDATO;
	}
	/**
	* Fija el contenido de la propiedad IDCANDIDATO
	* @param IDCANDIDATO
	* @return void
	*/
	function setIDCANDIDATO($sCadena){
		$this->IDCANDIDATO = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDCANDIDATOHast
	* @return int(11)
	*/
	function getIDCANDIDATOHast(){
		return $this->IDCANDIDATOHast;
	}
	/**
	* Fija el contenido de la propiedad IDCANDIDATOHast
	* @param IDCANDIDATO
	* @return void
	*/
	function setIDCANDIDATOHast($sCadena){
		$this->IDCANDIDATOHast = $sCadena;
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
	* Devuelve el contenido de la propiedad IDDIMENSION
	* @return int(11)
	*/
	function getIDDIMENSION(){
		return $this->IDDIMENSION;
	}
	/**
	* Fija el contenido de la propiedad IDDIMENSION
	* @param IDDIMENSION
	* @return void
	*/
	function setIDDIMENSION($sCadena){
		$this->IDDIMENSION = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad IDDIMENSIONHast
	* @return int(11)
	*/
	function getIDDIMENSIONHast(){
		return $this->IDDIMENSIONHast;
	}
	/**
	* Fija el contenido de la propiedad IDDIMENSIONHast
	* @param IDDIMENSION
	* @return void
	*/
	function setIDDIMENSIONHast($sCadena){
		$this->IDDIMENSIONHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad CODDIMENSION
	* @return varchar(255)
	*/
	function getCODDIMENSION(){
		return $this->CODDIMENSION;
	}
	/**
	* Fija el contenido de la propiedad CODDIMENSION
	* @param CODDIMENSION
	* @return void
	*/
	function setCODDIMENSION($sCadena){
		$this->CODDIMENSION = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad DESCDIMENSION
	* @return varchar(255)
	*/
	function getDESCDIMENSION(){
		return $this->DESCDIMENSION;
	}
	/**
	* Fija el contenido de la propiedad DESCDIMENSION
	* @param DESCDIMENSION
	* @return void
	*/
	function setDESCDIMENSION($sCadena){
		$this->DESCDIMENSION = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad RESULTDIMENSION
	* @return decimal(11,0)
	*/
	function getRESULTDIMENSION(){
		return $this->RESULTDIMENSION;
	}
	/**
	* Fija el contenido de la propiedad RESULTDIMENSION
	* @param RESULTDIMENSION
	* @return void
	*/
	function setRESULTDIMENSION($sCadena){
		$this->RESULTDIMENSION = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad RESULTDIMENSIONHast
	* @return decimal(11,0)
	*/
	function getRESULTDIMENSIONHast(){
		return $this->RESULTDIMENSIONHast;
	}
	/**
	* Fija el contenido de la propiedad RESULTDIMENSIONHast
	* @param RESULTDIMENSION
	* @return void
	*/
	function setRESULTDIMENSIONHast($sCadena){
		$this->RESULTDIMENSIONHast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad RESULTSOBRE10
	* @return float
	*/
	function getRESULTSOBRE10(){
		return $this->RESULTSOBRE10;
	}
	/**
	* Fija el contenido de la propiedad RESULTSOBRE10
	* @param RESULTSOBRE10
	* @return void
	*/
	function setRESULTSOBRE10($sCadena){
		$this->RESULTSOBRE10 = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad RESULTSOBRE10Hast
	* @return float
	*/
	function getRESULTSOBRE10Hast(){
		return $this->RESULTSOBRE10Hast;
	}
	/**
	* Fija el contenido de la propiedad RESULTSOBRE10Hast
	* @param RESULTSOBRE10
	* @return void
	*/
	function setRESULTSOBRE10Hast($sCadena){
		$this->RESULTSOBRE10Hast = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad MEDIAPONDERADA
	* @return float
	*/
	function getMEDIAPONDERADA(){
		return $this->MEDIAPONDERADA;
	}
	/**
	* Fija el contenido de la propiedad MEDIAPONDERADA
	* @param MEDIAPONDERADA
	* @return void
	*/
	function setMEDIAPONDERADA($sCadena){
		$this->MEDIAPONDERADA = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad MEDIAPONDERADAHast
	* @return float
	*/
	function getMEDIAPONDERADAHast(){
		return $this->MEDIAPONDERADAHast;
	}
	/**
	* Fija el contenido de la propiedad MEDIAPONDERADAHast
	* @param MEDIAPONDERADA
	* @return void
	*/
	function setMEDIAPONDERADAHast($sCadena){
		$this->MEDIAPONDERADAHast = $sCadena;
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
}//Fin de la Clase Sec_resultadosinformesdim
?>