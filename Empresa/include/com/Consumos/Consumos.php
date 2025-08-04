<?php

/**
* Crea un objeto de la clase y almacena en él 
* los valores de la entidad de clase Consumos.
**/
class Consumos
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
		var $idProceso;
		var $idProcesoHast;
		var $idCandidato;
		var $idCandidatoHast;
		var $codIdiomaIso2;
		var $idPrueba;
		var $idPruebaHast;
		var $codIdiomaInforme;
		var $idTipoInforme;
		var $idTipoInformeHast;
		var $idBaremo;
		var $idBaremoHast;
		var $nomEmpresa;
		var $nomProceso;
		var $nomCandidato;
		var $apellido1;
		var $apellido2;
		var $dni;
		var $mail;
		var $nomPrueba;
		var $nomInforme;
		var $nomBaremo;
		var $concepto;
		var $unidades;
		var $unidadesHast;
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
		$this->idProceso			= "";
		$this->idProcesoHast			= "";
		$this->idCandidato			= "";
		$this->idCandidatoHast			= "";
		$this->codIdiomaIso2			= "";
		$this->idPrueba			= "";
		$this->idPruebaHast			= "";
		$this->codIdiomaInforme			= "";
		$this->idTipoInforme			= "";
		$this->idTipoInformeHast			= "";
		$this->idBaremo			= "";
		$this->idBaremoHast			= "";
		$this->nomEmpresa			= "";
		$this->nomProceso			= "";
		$this->nomCandidato			= "";
		$this->apellido1			= "";
		$this->apellido2			= "";
		$this->dni			= "";
		$this->mail			= "";
		$this->nomPrueba			= "";
		$this->nomInforme			= "";
		$this->nomBaremo			= "";
		$this->concepto			= "";
		$this->unidades			= "";
		$this->unidadesHast			= "";
		$this->fecAlta			= "";
		$this->fecAltaHast			= "";
		$this->fecMod			= "";
		$this->fecModHast			= "";
		$this->usuAlta			= "";
		$this->usuAltaHast			= "";
		$this->usuMod			= "";
		$this->usuModHast			= "";
		$this->PKListaExcel		=	"nomEmpresa,nomProceso,nomCandidato,apellido1,apellido2,dni,mail,codIso2PaisProcedencia,nomPrueba,codIdiomaIso2,nomInforme,codIdiomaInforme,nomBaremo,concepto,unidades,descSexo,descEdad,descFormacion,descNivel,descArea,nomDescuentaMatriz,fecAlta";
		$this->DESCListaExcel	=	constant("STR_NOMBRE_EMPRESA") . "," . constant("STR_NOMBRE_PROCESO") . "," . constant("STR_NOMBRE_CANDIDATO") . "," . constant("STR_APELLIDO1") . "," . constant("STR_APELLIDO2") . "," . constant("STR_DNI") . "," . constant("STR_EMAIL") . "," . constant("STR_PAIS_DE_PROCEDENCIA") . "," .constant("STR_PRUEBA") . "," . constant("STR_IDIOMA_PRUEBA") . "," . constant("STR_INFORME") . "," . constant("STR_IDIOMA_INFORME") . "," . constant("STR_BAREMO") . "," . constant("STR_CONCEPTO") . "," . constant("STR_UNIDADES") . "," . constant("STR_SEXO") . "," . constant("STR_EDAD") . "," . constant("STR_FORMACION") . "," . constant("STR_NIVEL") . "," . constant("STR_AREA") . "," . constant("STR_EMPRESA_A_LA_QUE_SE_LE_DESCUENTA") . "," . constant("STR_FECHA_DE_ALTA_PROCESO") . "";
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
	* Devuelve el contenido de la propiedad codIdiomaInforme
	* @return char(2)
	*/
	function getCodIdiomaInforme(){
		return $this->codIdiomaInforme;
	}
	/**
	* Fija el contenido de la propiedad codIdiomaInforme
	* @param codIdiomaInforme
	* @return void
	*/
	function setCodIdiomaInforme($sCadena){
		$this->codIdiomaInforme = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoInforme
	* @return int(11)
	*/
	function getIdTipoInforme(){
		return $this->idTipoInforme;
	}
	/**
	* Fija el contenido de la propiedad idTipoInforme
	* @param idTipoInforme
	* @return void
	*/
	function setIdTipoInforme($sCadena){
		$this->idTipoInforme = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad idTipoInformeHast
	* @return int(11)
	*/
	function getIdTipoInformeHast(){
		return $this->idTipoInformeHast;
	}
	/**
	* Fija el contenido de la propiedad idTipoInformeHast
	* @param idTipoInforme
	* @return void
	*/
	function setIdTipoInformeHast($sCadena){
		$this->idTipoInformeHast = $sCadena;
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
	* Devuelve el contenido de la propiedad nomEmpresa
	* @return varchar(255)
	*/
	function getNomEmpresa(){
		return $this->nomEmpresa;
	}
	/**
	* Fija el contenido de la propiedad nomEmpresa
	* @param nomEmpresa
	* @return void
	*/
	function setNomEmpresa($sCadena){
		$this->nomEmpresa = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nomProceso
	* @return varchar(255)
	*/
	function getNomProceso(){
		return $this->nomProceso;
	}
	/**
	* Fija el contenido de la propiedad nomProceso
	* @param nomProceso
	* @return void
	*/
	function setNomProceso($sCadena){
		$this->nomProceso = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nomCandidato
	* @return varchar(255)
	*/
	function getNomCandidato(){
		return $this->nomCandidato;
	}
	/**
	* Fija el contenido de la propiedad nomCandidato
	* @param nomCandidato
	* @return void
	*/
	function setNomCandidato($sCadena){
		$this->nomCandidato = $sCadena;
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
	* Devuelve el contenido de la propiedad mail
	* @return varchar(255)
	*/
	function getMail(){
		return $this->mail;
	}
	/**
	* Fija el contenido de la propiedad mail
	* @param mail
	* @return void
	*/
	function setMail($sCadena){
		$this->mail = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nomPrueba
	* @return varchar(255)
	*/
	function getNomPrueba(){
		return $this->nomPrueba;
	}
	/**
	* Fija el contenido de la propiedad nomPrueba
	* @param nomPrueba
	* @return void
	*/
	function setNomPrueba($sCadena){
		$this->nomPrueba = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nomInforme
	* @return varchar(255)
	*/
	function getNomInforme(){
		return $this->nomInforme;
	}
	/**
	* Fija el contenido de la propiedad nomInforme
	* @param nomInforme
	* @return void
	*/
	function setNomInforme($sCadena){
		$this->nomInforme = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad nomBaremo
	* @return varchar(255)
	*/
	function getNomBaremo(){
		return $this->nomBaremo;
	}
	/**
	* Fija el contenido de la propiedad nomBaremo
	* @param nomBaremo
	* @return void
	*/
	function setNomBaremo($sCadena){
		$this->nomBaremo = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad concepto
	* @return varchar(255)
	*/
	function getConcepto(){
		return $this->concepto;
	}
	/**
	* Fija el contenido de la propiedad concepto
	* @param concepto
	* @return void
	*/
	function setConcepto($sCadena){
		$this->concepto = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad unidades
	* @return int(11)
	*/
	function getUnidades(){
		return $this->unidades;
	}
	/**
	* Fija el contenido de la propiedad unidades
	* @param unidades
	* @return void
	*/
	function setUnidades($sCadena){
		$this->unidades = $sCadena;
	}
	/**
	* Devuelve el contenido de la propiedad unidadesHast
	* @return int(11)
	*/
	function getUnidadesHast(){
		return $this->unidadesHast;
	}
	/**
	* Fija el contenido de la propiedad unidadesHast
	* @param unidades
	* @return void
	*/
	function setUnidadesHast($sCadena){
		$this->unidadesHast = $sCadena;
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
}//Fin de la Clase Consumos
?>