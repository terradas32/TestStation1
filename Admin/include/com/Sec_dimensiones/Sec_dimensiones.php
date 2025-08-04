<?php
/**
 * Crea un objeto de la clase y almacena en �l 
 * los valores de la entidad de clase Sec_dimensiones.
 **/
class Sec_dimensiones
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
	var $IDCOMPETENCIA;
	var $IDCOMPETENCIAHast;
	var $IDDIMENSION;
	var $IDDIMENSIONHast;
	var $CODIGO;
	var $NOMBRE;
	var $PUNTUACIONMIN;
	var $PUNTUACIONMINHast;
	var $PUNTUACIONMAX;
	var $PUNTUACIONMAXHast;
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
		$this->IDTIPOPRUEBA			= "";
		$this->IDTIPOPRUEBAHast			= "";
		$this->IDCOMPETENCIA			= "";
		$this->IDCOMPETENCIAHast			= "";
		$this->IDDIMENSION			= "";
		$this->IDDIMENSIONHast			= "";
		$this->CODIGO			= "";
		$this->NOMBRE			= "";
		$this->PUNTUACIONMIN			= "";
		$this->PUNTUACIONMINHast			= "";
		$this->PUNTUACIONMAX			= "";
		$this->PUNTUACIONMAXHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"IDTIPOPRUEBA,IDCOMPETENCIA,IDDIMENSION,CODIGO,NOMBRE,PUNTUACIONMIN,PUNTUACIONMAX,fecAlta,fecMod,usuAlta,usuMod";
		$this->DESCListaExcel	=	"Id tipoprueba,Id competencia,Id,C�digo,Nombre,Punt. M�nima,Punt. M�xima,Fec. alta,Fec. Mod.,Usu. alta,Usu. mod";
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
	 * Devuelve el contenido de la propiedad IDCOMPETENCIA
	 * @return int(11)
	 */
	function getIDCOMPETENCIA(){
		return $this->IDCOMPETENCIA;
	}
	/**
	 * Fija el contenido de la propiedad IDCOMPETENCIA
	 * @param IDCOMPETENCIA
	 * @return void
	 */
	function setIDCOMPETENCIA($sCadena){
		$this->IDCOMPETENCIA = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad IDCOMPETENCIAHast
	 * @return int(11)
	 */
	function getIDCOMPETENCIAHast(){
		return $this->IDCOMPETENCIAHast;
	}
	/**
	 * Fija el contenido de la propiedad IDCOMPETENCIAHast
	 * @param IDCOMPETENCIA
	 * @return void
	 */
	function setIDCOMPETENCIAHast($sCadena){
		$this->IDCOMPETENCIAHast = $sCadena;
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
	 * Devuelve el contenido de la propiedad CODIGO
	 * @return varchar(255)
	 */
	function getCODIGO(){
		return $this->CODIGO;
	}
	/**
	 * Fija el contenido de la propiedad CODIGO
	 * @param CODIGO
	 * @return void
	 */
	function setCODIGO($sCadena){
		$this->CODIGO = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad NOMBRE
	 * @return varchar(250)
	 */
	function getNOMBRE(){
		return $this->NOMBRE;
	}
	/**
	 * Fija el contenido de la propiedad NOMBRE
	 * @param NOMBRE
	 * @return void
	 */
	function setNOMBRE($sCadena){
		$this->NOMBRE = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMIN
	 * @return int(11)
	 */
	function getPUNTUACIONMIN(){
		return $this->PUNTUACIONMIN;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMIN
	 * @param PUNTUACIONMIN
	 * @return void
	 */
	function setPUNTUACIONMIN($sCadena){
		$this->PUNTUACIONMIN = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMINHast
	 * @return int(11)
	 */
	function getPUNTUACIONMINHast(){
		return $this->PUNTUACIONMINHast;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMINHast
	 * @param PUNTUACIONMIN
	 * @return void
	 */
	function setPUNTUACIONMINHast($sCadena){
		$this->PUNTUACIONMINHast = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMAX
	 * @return int(11)
	 */
	function getPUNTUACIONMAX(){
		return $this->PUNTUACIONMAX;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMAX
	 * @param PUNTUACIONMAX
	 * @return void
	 */
	function setPUNTUACIONMAX($sCadena){
		$this->PUNTUACIONMAX = $sCadena;
	}
	/**
	 * Devuelve el contenido de la propiedad PUNTUACIONMAXHast
	 * @return int(11)
	 */
	function getPUNTUACIONMAXHast(){
		return $this->PUNTUACIONMAXHast;
	}
	/**
	 * Fija el contenido de la propiedad PUNTUACIONMAXHast
	 * @param PUNTUACIONMAX
	 * @return void
	 */
	function setPUNTUACIONMAXHast($sCadena){
		$this->PUNTUACIONMAXHast = $sCadena;
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
}//Fin de la Clase Sec_dimensiones
?>