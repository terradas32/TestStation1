<?php 
if (!defined ("DIR_FS_DOCUMENT_ROOT")){
        require_once("../include/Configuracion.php");
}
    include_once(constant("DIR_FS_DOCUMENT_ROOT") . "include/Idiomas.php");
	include_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_INCLUDE") . "Seguridad.php");
	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Utilidades.php");
	
    $bLogado = isLogado($conn);

    if (!$bLogado){
    	session_start();
        $_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
		header("Location: " . constant("HTTP_SERVER") . "msg.php");
		exit;
	}else{
		if (!isUsuarioActivo($conn)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("CONF_SESSION");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
		}
		//Recogemos los Menus
		$sMenus = getMenus($conn);
		if (empty($sMenus)){
			session_start();
			$_SESSION["mensaje" . constant("NOMBRE_SESSION")] = constant("ERR_NO_AUTORIZADO");
			header("Location: " . constant("HTTP_SERVER") . "msg.php");
			exit;
        }
        $_cEntidadUsuarioTK = getUsuarioToken($conn);
    }
  	require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Combo.php");
	
		$sValor			= "";
		$sOption			= "";
		$lineas			= "1";
		$sComboProp		= "onchange=\"javascript:pruebasPersonalidad()\"";
		$sPrefijo		= "f";
		$sNombreCampo	= $sPrefijo . "IdProceso";
		$sWhere			= "";
		$bJoin			= false;
		$sFrame			= "";
		$sBgColor		= "";
		$sObliga        = "cajatexto";
		if (isset($_POST["bBus"]) && $_POST["bBus"]){
			$sPrefijo = "LST";
		}else{
			$sPrefijo = "f";
		}
		if (isset($_POST["bgColor"]) && !empty($_POST["bgColor"])){
			$sBgColor = "background-color:" . $_POST["bgColor"] . ";";
		}
		if (isset($_POST["fFrame"]) && !empty($_POST["fFrame"])){
			$sFrame = ($_POST["fFrame"] != "_") ? $_POST["fFrame"] : "";
		}else{
			$sFrame= "";
		}
		if (isset($_POST["fNombreCampo"]) && !empty($_POST["fNombreCampo"])){
			$sNombreCampo = $_POST["fNombreCampo"];
		}else{
			$sNombreCampo	= $sPrefijo . "IdProceso";
		}
		if (isset($_POST["fJSProp"]) && !empty($_POST["fJSProp"])){
			$sComboProp		= "onchange=\"javascript:" . $sFrame . $_POST["fJSProp"] . "()\"";
		}
		if (isset($_POST[$sPrefijo . "IdProceso"]) && $_POST[$sPrefijo . "IdProceso"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProceso=" . $conn->qstr($_POST[$sPrefijo . "IdProceso"], false);
		}else {
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idProceso='-1'";
		}
		if (isset($_POST[$sPrefijo . "IdEmpresa"])){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idEmpresa=" . $conn->qstr($_POST[$sPrefijo . "IdEmpresa"], false);
		}
		if (isset($_POST[$sPrefijo . "Nombre"]) && $_POST[$sPrefijo . "Nombre"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "nombre=" . $conn->qstr($_POST[$sPrefijo . "Nombre"], false);
		}
		if (isset($_POST[$sPrefijo . "Descripcion"]) && $_POST[$sPrefijo . "Descripcion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "descripcion=" . $conn->qstr($_POST[$sPrefijo . "Descripcion"], false);
		}
		if (isset($_POST[$sPrefijo . "Observaciones"]) && $_POST[$sPrefijo . "Observaciones"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "observaciones=" . $conn->qstr($_POST[$sPrefijo . "Observaciones"], false);
		}
		if (isset($_POST[$sPrefijo . "FechaInicio"]) && $_POST[$sPrefijo . "FechaInicio"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fechaInicio=" . $conn->qstr($_POST[$sPrefijo . "FechaInicio"], false);
		}
		if (isset($_POST[$sPrefijo . "FechaFin"]) && $_POST[$sPrefijo . "FechaFin"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fechaFin=" . $conn->qstr($_POST[$sPrefijo . "FechaFin"], false);
		}
		if (isset($_POST[$sPrefijo . "IdModoRealizacion"]) && $_POST[$sPrefijo . "IdModoRealizacion"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "idModoRealizacion=" . $conn->qstr($_POST[$sPrefijo . "IdModoRealizacion"], false);
		}
		if (isset($_POST[$sPrefijo . "FecAlta"]) && $_POST[$sPrefijo . "FecAlta"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fecAlta=" . $conn->qstr($_POST[$sPrefijo . "FecAlta"], false);
		}
		if (isset($_POST[$sPrefijo . "FecMod"]) && $_POST[$sPrefijo . "FecMod"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "fecMod=" . $conn->qstr($_POST[$sPrefijo . "FecMod"], false);
		}
		if (isset($_POST[$sPrefijo . "UsuAlta"]) && $_POST[$sPrefijo . "UsuAlta"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "usuAlta=" . $conn->qstr($_POST[$sPrefijo . "UsuAlta"], false);
		}
		if (isset($_POST[$sPrefijo . "UsuMod"]) && $_POST[$sPrefijo . "UsuMod"] != ""){
			$sWhere.=(empty($sWhere) ? " " : " AND ") . "usuMod=" . $conn->qstr($_POST[$sPrefijo . "UsuMod"], false);
		}
		
//		echo $sWhere;
		
		if (empty($sWhere)){
			$sWhere = "usuMod='-1'";
		}
		if ($bJoin){
			//$comboPRUEBASGROUP= new Combo($conn, $sPrefijo . "IdProceso", "DISTINCT(s.idProceso)", "s.nombre", "descripcion", "procesos s, suscriptorestemasboletins stm", "", constant("SLC_OPCION"), $sWhere, "idProceso", "fecMod");
		}else{
			$comboPRUEBASPROCESO	= 	new Combo($conn, $sPrefijo . "IdPrueba","idPrueba","idPrueba"	,"idPrueba",	"proceso_pruebas",	"","",$sWhere,"","","");
			$rsPRUEBASPROCESO		= $comboPRUEBASPROCESO->getDatos();
			$sPRUEBASPROCESO = "";
			while (!$rsPRUEBASPROCESO->EOF)
			{
				$sPRUEBASPROCESO .= "," . $rsPRUEBASPROCESO->fields["idPrueba"];
				$rsPRUEBASPROCESO->MoveNext();
			}
			$comboPRUEBASPROCESO	= 	new Combo($conn, $sPrefijo . "IdPrueba","idPrueba","idPrueba"	,"idPrueba",	"proceso_pruebas_candidato",	"","",$sWhere,"","","");
			$rsPRUEBASPROCESO		= $comboPRUEBASPROCESO->getDatos();
			while (!$rsPRUEBASPROCESO->EOF)
			{
				$sPRUEBASPROCESO .= "," . $rsPRUEBASPROCESO->fields["idPrueba"];
				$rsPRUEBASPROCESO->MoveNext();
			}
			$sWhere2 ="";
			if (!empty($sPRUEBASPROCESO)){
				$sPRUEBASPROCESO = substr($sPRUEBASPROCESO,1);
				//Quitamos repetidas
				$aPRUEBASPROCESO = explode(",", $sPRUEBASPROCESO);
				$aPRUEBASPROCESO = array_unique($aPRUEBASPROCESO);
				$sPRUEBASPROCESO = implode(",", $aPRUEBASPROCESO);
				
				$sWhere2.=(empty($sWhere2) ? " " : " AND ") . "idPrueba IN (" . $sPRUEBASPROCESO . ") AND bajaLog=0 AND idTipoPrueba NOT IN (2,5) ";
			}else {
				$sWhere2.=(empty($sWhere2) ? " " : " AND ") . "idPrueba='-1'";
			}

			$comboPRUEBASGROUP	= new Combo($conn, $sPrefijo . "IdPrueba","idPrueba","nombre","Descripcion","pruebas","","",$sWhere2, "idprueba", "nombre", "idprueba");
		}
		if (isset($_POST["nLineas"]) &&  $_POST["nLineas"] > 1)
			$lineas		= $_POST["nLineas"];
		if (isset($_POST["bObliga"]) &&  !empty($_POST["bObliga"]))
			$sObliga= "obliga";
		if (isset($_POST["bBus"]) && $_POST["bBus"]){
			$sValor=explode(",",isset($_POST[$sNombreCampo]) ? $_POST[$sNombreCampo] : "");
		}else{
			//$sOption		= "<option value='-1'>Todos</option>";
			$sValor=explode(",",isset($_POST[$sNombreCampo]) ? $_POST[$sNombreCampo] : "");
		}
		if (isset($_POST["vSelected"])){
			$sValor = explode(",",$_POST["vSelected"]);
		}
		if (isset($_POST["multiple"]) && $_POST["multiple"]){
			//$sNombreCampo=$sNombreCampo . "[]";
			$sComboProp	.= " multiple=\"multiple\" ";
		}
		if (isset($_POST["fDisabled"]) && !empty($_POST["fDisabled"])){
			$sComboProp	.= " " . $_POST["fDisabled"];
		}
		//Otras propiedades
		//		$sComboProp	.= " style=\"" . $sBgColor . "\"";
		$comboPRUEBASGROUP->setNombre($sNombreCampo);
//echo $comboPRUEBASGROUP->getHTMLCombo($lineas, $sObliga, $sValor, $sComboProp, $sOption);

					//Recogemos para ese proceso las pruebas de tipo actitudinal
					
					$aPruebas = explode(",",$sPRUEBASPROCESO);  
					$rsPRUEBASGROUP		= $comboPRUEBASGROUP->getDatos();
					$sAsIdPRUEBASGROUP		= $comboPRUEBASGROUP->getIdKey();
					$sAsPRUEBASGROUP		= $comboPRUEBASGROUP->getDescKey();
					$iPRUEBASGROUP		= $rsPRUEBASGROUP->RecordCount();
					$sDefaultPRUEBASGROUP	= $comboPRUEBASGROUP->getDefault();
					$sPRUEBASGROUP1 = '';
					$i=0;
					$rsPRUEBASGROUP->Move(0); //Posicionamos en el primer registro.
					$sChecked = "";
					$sIdsPruebas = "";
					$aNombrePruebaANTERIOR=array();
					while (!$rsPRUEBASGROUP->EOF)
					{
						$sChecked = "";
						if (in_array($rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP], $aPruebas)){
							$sIdsPruebas .= "," . $rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP]; 
							$sChecked='checked=\"checked\"';
						}
						if ($i==0){
							$sPRUEBASGROUP1 .= '<tr>';
						}
						if (($i%6) == 0){
							$sPRUEBASGROUP1 .= '</tr><tr>';
						}
						$sNombrePrueba = $rsPRUEBASGROUP->fields[$sAsPRUEBASGROUP];
						$sEsconder = "";
						if (in_array($sNombrePrueba, $aNombrePruebaANTERIOR)){
							$sPRUEBASGROUP1 .= '<input style="display:none;" id="' . $sPrefijo . 'IdPrueba' . $i . '" name="' . $sPrefijo . 'IdPrueba' . $i . '" type="checkbox" ' . $sChecked . ' onclick="setIdsPruebas(' . $iPRUEBASGROUP . ');" value="' . $rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP] . '">';	
						}else { 
							$sPRUEBASGROUP1 .= '<td style="padding-right:20px"><input id="' . $sPrefijo . 'IdPrueba' . $i . '" name="' . $sPrefijo . 'IdPrueba' . $i . '" type="checkbox" ' . $sChecked . ' onclick="setIdsPruebas(' . $iPRUEBASGROUP . ');" value="' . $rsPRUEBASGROUP->fields[$sAsIdPRUEBASGROUP] . '">' . '<label for="' . $sPrefijo . 'IdPrueba' . $i . '" title="' . $rsPRUEBASGROUP->fields[$sAsPRUEBASGROUP] . '">' . $rsPRUEBASGROUP->fields[$sAsPRUEBASGROUP] . '</label>';
						}
						$sPRUEBASGROUP1 .= '</td>';
						$i++;
						$aNombrePruebaANTERIOR[] = $sNombrePrueba;
						$rsPRUEBASGROUP->MoveNext();
					}
					if (!empty($sIdsPruebas)){
						$sIdsPruebas = substr($sIdsPruebas,1);
					}
					if (empty($sPRUEBASGROUP1)){
						$sPRUEBASGROUP1 .= '<td style="padding-right:20px">Sin datos';
						$sPRUEBASGROUP1 .= '</td>';
					}
					$sPRUEBASGROUP1 .= '<input type=hidden name="fIdsPruebas" value="' . $sIdsPruebas . '">';
?>
						<table><tr><?php echo $sPRUEBASGROUP1;?></tr></table>
