<?php
class Combo
{
	/**
	* Declaración de las variables de Entidad.
	**/
	var $miconexion;
	var $sDefOptionColor		= "#000000"; //Color por defecto del primer option
	var $sOptionColor			= "#000000"; //Color por defecto del resto de opciones
	var $sNombre				= "";
	var $sIdKey					= "";
	var $sPipe					= "";
	var $sDescKey				= "";
	var $sTabla					= "";
	var $sIdPadre				= "";
	var $sWhere					= "";
	var $sAsIdKey				= "";
	var $vDatos					= "";
	var $sAction				= "";
	var $sDefault				= "";
	var $sOrderBy				= "";
	var $sGroupBy				= "";
	
	/**
	* constructor q inicializa los datos de un combo
	* @param conn Conexion a traves de la cual realizar las operaciones sobre la base de datos
	* @param sNombre		Nombre del combo
	* @param sIdKey			LLave donde encontrar el value de cada valor
	* @param sPipe			De esta concatenacion de campos sacamos el valor de la descripcion
	* @param sDescKey		LLave para encontrar la descripcion de cada valor
	* @param sTabla			Tabla de donde obtener los datos del valor
	* @param sPadre			Identificado del campo perteneciente al padre
	* @param sDefault		Primera opcion del combo
	* @param sWhere			Where del combo
	* @param sAsIdKey		LLave donde encontrar el value de cada valor del sIdKey (útil en Joins)
	* @param sOrderBy		Ordenar por
	**/
	function __construct(&$conn, $sNombre, $sIdKey, $sPipe, $sDescKey, $sTabla, $sPadre, $sDefault, $sWhere, $sAsIdKey="", $sOrderBy="",$sGroupBy="")
	{
		$this->miconexion	= $conn;
		$this->miconexion->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->sNombre 		= $sNombre;
		$this->sIdKey 		= $sIdKey;
		$this->sPipe 		= $sPipe;
		$this->sDescKey 	= $sDescKey;
		$this->sTabla 		= $sTabla;
		$this->sOrderBy		= $sOrderBy;
		$this->sGroupBy		= $sGroupBy;
		
		if ($sPadre != "")
			$this->sIdPadre = strtoupper($sPadre);
		else $this->sIdPadre = "";
		if ($sWhere != "")
			$this->sWhere 	= $sWhere;
		else $this->sWhere 	= "";
		if ($sAsIdKey != ""){
			$this->sAsIdKey = $sAsIdKey;
		}
		$this->vDatos 		= $this->consultaDatos();
		$this->sAction 		= "onChange='javascript:cambia$sNombre()'";
		$this->sDefault 	= $sDefault;
	} // fin del constructor
    /** establece los datos devueltos por una select **/
	function consultaDatos() 
	{
    	$sQuery = "";
		$aux = $this->miconexion;
		
		//creamos la query
		$sQuery.="SELECT $this->sIdKey ";//	$sQuery.="SELECT " . $this->sIdKey;  //-josemi-
		if (!empty($this->sAsIdKey)){
			$sQuery.= "AS " . $this->sAsIdKey;
			$this->sIdKey = $this->sAsIdKey;
		}
		$sQuery.=",";
		
		//si no tenemos concatenacion de campos el nombre de descripcion = campo
		if ($this->sPipe == "")
			$sQuery.=$this->sDescKey;
		else
			$sQuery.=$this->sPipe . " AS " . $this->sDescKey;
					
	    if ($this->sIdPadre != "")
		    $sQuery.=", $this->sIdPadre";
		
    	$sQuery.=" FROM " . $this->sTabla;
    	$sQuery.=	$this->getWhere(); //NOSE COMO HACER LLEGAR ASTA AQUI LA SENTENCIA DEL WHERE PARA QUE SEA DINAMICO <<<<<$sQuery.=" WHERE BajaLog!='on' " >>>>;
		$sQuery.= (empty($this->sGroupBy)) ? "" : " GROUP BY " . $this->sGroupBy;
    	$sQuery.= " ORDER BY ";
		$sQuery.= (empty($this->sOrderBy)) ? $this->sDescKey : $this->sOrderBy;
		
		//obtenemos la conexion
		//echo $sQuery . "<br />";
		$result = $aux->Execute($sQuery);
		
		return $result;
	} // fin de consultaDatos

	/** establece los datos devueltos por una select **/
	function consultaDatosQuery($sQuery) 
	{
	    //obtenemos la conexion
		$aux = $this->miconexion;
		$result = $aux->Execute($sQuery);
		return $result;
	    
	} // fin de consultaDatos
	
	/**
	* establece los datos en un vector
	* @param sQuery			String con la query desde la q generamos los datos del combo
	**/
	function setDatos($sQuery)
	{
		$this->vDatos = $this->consultaDatosQuery($sQuery);
	}
	
	/**
	* devuelve los datos contenidos en un vector
	* @return vDatos			Vector de Hashtable q contiene los datos
	**/	
	function getDatos()
	{
		return $this->vDatos;
	}
	
	/**
	* devuelve el nombre del combo
	* @return String
	*/
	function getNombre()
	{
		return $this->sNombre;
	}
	
	/**
	* establece el valor de la propiedad sNombre
	* @sNombre					
	*/
	function setNombre($sCadena)
	{
		$this->sNombre = $sCadena;
	}

	/**
	* devuelve el campo q contiene las id de los option
	* @return String
	*/
	function getIdKey()
	{
		return $this->sIdKey;
	}
	
	/**
	* establece el valor de la propiedad sIdKey
	* @sIdKey
	*/
	function setIdKey($sCadena)
	{
		$this->sIdKey = $sCadena;
	}
	
	/**
	* devuelve el campo q contiene las descripciones de las options
	* @return String
	*/
	function getDescKey()
	{
		return $this->sDescKey;
	}
	
	/**
	* establece el valor de la propiedad sDescKey
	* @sDescKey
	*/
	function setDescKey($sCadena)
	{
		$this->sNombre = $sCadena;
	}

	/**
	* devuelve la tabla del combo
	* @return String
	*/
	function getTabla()
	{
		return $this->sTabla;
	}
	
	/**
	* establece el valor de la propiedad sTabla
	* @sTabla
	*/
	function setTabla($sCadena)
	{
		$this->sTabla= $sCadena;
	}
	
	/**
	* devuelve la clave q contiene la id del padre
	* @return String
	*/
	function getIdPadre()
	{
		return $this->sIdPadre;
	}
	
	/**
	* establece el valor de la propiedad sIdPadre
	* @sIdPadre
	*/
	function setIdPadre($sCadena)
	{
		$this->sIdPadre= $sCadena;
	}

	/**
	* devuelve el action del combo
	* @return String
	*/
	function getAction()
	{
		return $this->sAction;
	}
	
	/**
	* establece el valor de la propiedad sAction
	* @sAction
	*/
	function setAction($sCadena)
	{
		$this->sAction= $sCadena;
	}

	/**
	* devuelve la opcion inicial del combo
	* @return String
	*/
	function getDefault()
	{
		return $this->sDefault;
	}
	
	/**
	* establece el valor de la propiedad sDefault
	* @param sDefault
	*/
	function setDefault($sCadena)
	{
		$this->sDefault= $sCadena;
	}
	
	/**
	* devuelve el where del combo
	* @return String
	*/
	function getWhere()
	{
		if ($this->sWhere != "")
			return " WHERE $this->sWhere";
		else return "";
	}
		
	/**
	* metodo q devuelve el codigo HTML q genera el Combo
	* @param sSize			size del combo
	* @param sClass			class del combo
	* @param sValor			Valor seleccionado por defecto
	* @param sAction		Accion a ejecutar.
	* @param sMoreOptions	Si hay que añadir mas opciones que la de por defecto,
	*						Se añaden literalmente. Ej: <option value='-1'>Descipción</option>
	* @return String
	*/
	function getHTMLCombo($sSize, $sClass, $sValor, $sAction, $sMoreOptions="")
	{
		$sHTML = "";    // el HTML q genera el Combo
		$sAccion = "";	//accion q se ejecutara
		if ($sAction != "")
			$sAccion = $sAction;
		
		$this->setAction($sAccion);
		// Creamos la cabecera del Combo		
		$sHTML.=$this->setCabeceraCombo($this->sNombre,$sSize,$sClass,$sAccion);
	    // si tenemos un primer opcion lo insertamos
		if ($this->sDefault != "")
		{
			if ($sValor != "")
				$sHTML.=$this->addNewOptionBoolean($this->sDefault, false);
			else
				$sHTML.=$this->addNewOptionBoolean($this->sDefault, true);
		}
		//Si hay que añadir mas opciones que la de por defecto
		//Se añaden literalmente. Ej: <option value='-1'>Descipción</option>
		if ($sMoreOptions != "")
		{
			$sHTML.=$sMoreOptions;
		}
	    // recogemos los nombres de las keys de las options

       	// recorremos todos los datos del vector para introducirlos como options en el combo
		$this->vDatos->Move(0); //Posicionamos en el primer registro.
		while (!$this->vDatos->EOF)
		{
			$sHTML.=$this->addNewOption($this->vDatos->fields,$this->sIdKey,$this->sDescKey,$sValor);
			$this->vDatos->MoveNext();
		}

		 // una vez tenemos todos los options cerramos el select
		 $sHTML.="</select>\n";
		
		return $sHTML;	
	} // fin getHTMLCombo

	/**
	* metodo q devuelve el codigo HTML q genera el Combo
	* @param sSize					size del combo
	* @param sClass				class del combo
	* @param sValor				Valor seleccionado por defecto
	* @param sAction				Accion a ejecutar. Null pone una por defecto
	* @return String
	*/
	function getHTMLComboNull($sSize,$sClass,$sValor,$sAction)
	{
		$sHTML = "";    // el HTML q genera el Combo
		$sAccion = "";	//accion q se ejecutara
		
		$this->sAccion = $sAction;
		$this->setAction($this->sAccion);
		// Creamos la cabecera del Combo		
	    $sHTML.=$this->setCabeceraCombo($this->sNombre,$sSize,$sClass,$this->sAccion);
	    // si tenemos un primer opcion lo insertamos
		if ($this->sDefault != "")
		{
			if ($sValor != "")
					$sHTML.=$this->addNewOptionBoolean($this->sDefault, false);
			else
					$sHTML.=$this->addNewOptionBoolean($this->sDefault, true);
		}
			 $sHTML.="</select>\n";
			
		return $sHTML;	
	} // fin getHTMLCombo
	
	/**
	* Metodo q genera una nueva opcion para el Combo
	* @param hOption		Hashtable con los datos de esta nueva opcion
	* @param sValue			primera Key de la tabla hash hOption
	* @param sDef			segunda Key de la tabal hash hOption
	* @param sDefault		clave seleccionada por defecto
	* @return String
	*/
	function addNewOption($hOption,$sValue, $sDef, $sDefault)
	{
		$sOption = "";		//Contiene la nueva opcion
		$sAux = "";			//String auxiliar para comparar values
		
		$sOption.="\t<option style='color:" . $this->getOptionColor() . ";' value='";
		
		$sAux = $hOption[$sValue];
		$sOption.=$sAux ."'";
		// si coincide con el seleccionado lo ponemos cono selected
		if (is_array($sDefault)){
			//Recorremos el array de seleccionados hasta que encontremos uno.
			for ($i=0, $max = sizeof($sDefault); $i < $max; $i++){
				if ($sDefault[$i] != "" && strtolower(trim($sDefault[$i])) == strtolower(trim($sAux)) ){
					$sOption.=" selected=\"selected\"";
					break;
				}
			}
		}else{
			if ($sDefault != "" && strtolower(trim($sDefault)) == strtolower(trim($sAux)) ){
				$sOption.=" selected=\"selected\"";
			}
		}
		$sOption.=">" . $hOption[$sDef] . "</option>\n";
		
		return $sOption;
	} // fin de addNewOption

	/**
	* Metodo q genera la primera opcion para el Combo
	* @param sDef				Mensaje del primer option
	* @param bDefault			Dice si el primer elemento es el seleccionado
	* @return String
	*/
	function addNewOptionBoolean($sDef,$bDefault)
	{
		$sOption = "";
		$sOption.="\t<option style='color:" . $this->getDefOptionColor() . ";' value=''";
		if ($bDefault)
			$sOption.=" selected=\"selected\"";
		$sOption.=">$sDef </option>\n";
		return $sOption;
	}
	function getDefOptionColor(){
		return $this->sDefOptionColor;
	}
	function setDefOptionColor($sColor){
		$this->sDefOptionColor = $sColor;
	}
	function getOptionColor(){
		return $this->sOptionColor;
	}
	function setOptionColor($sColor){
		$this->sOptionColor = $sColor;
	}
	
	/**
	* Metodo q genera la cabecera del select
	* @param sIdSelect			Identificador del Combo
	* @param sSize				Size del Combo
	* @param sClass			Class del Combo
	* @param sAction 			Action de javascript
	* @return String
	*/
	function setCabeceraCombo($sIdSelect,$sSize,$sClass, $sAction)
	{
		$sCabecera = "";	//Contiene la cabecera del select
		$sCabecera.="<select name='$sIdSelect'";
		if ($sSize != "")
			$sCabecera.=" size='$sSize'";
		if ($sClass != "")
			$sCabecera.=" class='$sClass'";
		if ($sAction != "")
		    $sCabecera.=" $sAction";	
		$sCabecera.=">\n";	
		return $sCabecera;
	}	// fin de setCabeceraCombo

	/** Metodo q devuelve la funcion de javascript para cambiar este combo dependiendo
	*	del combo padre
	* @param cPadre		Combo padre
	* @param sFormulario	El nombre del formulario donde el combo esta contenido
	* @param sFuncion	Nombre de la funcion JavaScript, si null nombre del padre.
	* @return String		Funcion de javascript
	*/
	function getJSComboChange($cPadre, $sFormulario, $sFuncion)
	{
		$$sJS		= "";
		$sIdPadre	= "";
		$sIdHijo	= "";
		$sDescAux	= "";
		$stAux		= "";
		if ($sFuncion != "")
			$sNomFunc = $sFuncion;
		else $sNomFunc = $cPadre->getNombre();
		
		// introducimos la cabecera de la funcion JS con el nombre del combo
		$sJS.="function cambia" . $sNomFunc  . "()\n{\n";
		$sJS.="\tdocument." . $sFormulario . ".elements['" . $this->sNombre . "'].length=0;\n";
		$sJS.="\tanadir('" . $this->sNombre . "', '" . $this->sDefault . "', '');\n";
		$sJS.="\tdocument.forms[0].elements['" . $this->sNombre . "'].selectedIndex=0;\n"; 
		$sJS.="\tvar sel = document." . $sFormulario . "." . $cPadre->getNombre() . ".options[document." . $sFormulario . "." . $cPadre->getNombre() . ".selectedIndex].value;\n";

	    // datos del Combo padre
		
		$eAux = $cPadre->getDatos();
		//recorremos el vector de datos del combo padre
		$eAux->Move(0); //Posicionamos en el primer registro.
		while (!$eAux->EOF)
		{
			// el ID de cada opcion del combo padre
			$sIdPadre = $eAux->fields[strtoupper($cPadre->getIdKey())];
			$sJS.="\tif (sel == '" . $sIdPadre . "') \n\t{ \n";
			// cogemos los datos del combo hijo
			$eAux2 = $this->vDatos; 	
			//recorremos los datos del combo hijo
			$eAux2->Move(0); //Posicionamos en el primer registro.
			while (!$eAux2->EOF)
			{
				// el ID padre de cada opcion del hijo
				$sIdHijo = $eAux2->fields[strtoupper($this->sIdPadre)];
				// si esa opcion pertenece al padre
				if (strtoupper($sIdHijo) == strtoupper($sIdPadre))
				{
					$sDescAux = $eAux2->fields[$this->sDescKey];
					// si hay ' en la cadena hay q quitarla
					if (strchr($sDescAux,"'"))
					{
						$sDescAux = str_replace("'"," ",$sDescAux);
					}
					$sJS.="\t\tanadir('" . $this->sNombre . "','" . $sDescAux . "','" . $eAux2->fields[$this->sIdKey] . "');\n";
				} 
				$eAux2->MoveNext();
			} //fin de while(eAux2...)
			// cerramos la llave del if en JS
			$sJS.="\t}\n";
			$eAux->MoveNext();
		} // fin de while (eAux.hasMoreElements())
		$sJS.="}\n";
		
		return $sJS;	
	} // fin de getJSComboChange
	
	/**
	* metodo q devuelve el String con la descripción de la selección del Combo
	* @param sValor				Valor seleccionado por defecto
	* @return String
	*/

	/*function getDescripcionCombo($sValor)
	{
		$sDescripcion = "";   // La descripción del valor
		$sAux = "";		//String auxiliar para comparar values
    	// recorremos todos los datos del vector 
		$this->vDatos->Move(0); //Posicionamos en el primer registro.
		while (!$this->vDatos->EOF)
		{
    		$sAux = $this->vDatos->fields[$this->sIdKey];
			if ($sValor != "" && (strtoupper(trim($sValor)) == strtoupper(trim($sAux))))
			{
				$sDescripcion = $this->vDatos->fields[$this->sDescKey];
				break;
			}
			$this->vDatos->MoveNext();
    	}
		return $sDescripcion;	
	} // fin getDescripcionCombo*/

	
	function getDescripcionCombo($sValor)
	{
		$sDescripcion = "";   // La descripción del valor
		$sAux = "";		//String auxiliar para comparar values
		$cadena = explode(",",$sValor);
		if (is_array ($cadena)){
			foreach ($cadena as $sValor) {
				// recorremos todos los datos del vector 
				$this->vDatos->Move(0); //Posicionamos en el primer registro.
				while (!$this->vDatos->EOF)
				{
    				$sAux = $this->vDatos->fields[$this->sIdKey];
					if ($sValor != "" && (strtoupper(trim($sValor)) == strtoupper(trim($sAux))))
					{
						$sDescripcion .=  $this->vDatos->fields[$this->sDescKey] . "," ;
						break;
					}
					$this->vDatos->MoveNext();
    			}
				$sValor = "";
			}// fin for
			$sDescripcion = substr($sDescripcion,0,strlen($sDescripcion)-1);
		}else{
			// recorremos todos los datos del vector 
			$this->vDatos->Move(0); //Posicionamos en el primer registro.
			while (!$this->vDatos->EOF)
			{
    			$sAux = $this->vDatos->fields[$this->sIdKey];
				if ($sValor != "" && (strtoupper(trim($sValor)) == strtoupper(trim($sAux))))
				{
					$sDescripcion = $this->vDatos->fields[$this->sDescKey];
					break;
				}
				$this->vDatos->MoveNext();
    		}
		}//fin else
		return $sDescripcion;	
	} // fin getDescripcionCombo

	/**
	* metodo q devuelve el codigo HTML q genera el Combo de tipo Menú
	* @param sSize			size del combo
	* @param sClass			class del combo
	* @param sValor			Valor seleccionado por defecto
	* @param sAction		Accion a ejecutar.
	* @param sMoreOptions	Si hay que añadir mas opciones que la de por defecto,
	*						Se añaden literalmente. Ej: <option value='-1'>Descipción</option>
	* @return String
	*/
	function getHTMLComboMenu($sSize, $sClass, $sValor, $sAction, $sMoreOptions="")
	{
		$sHTML = "";    // el HTML q genera el Combo
		$sAccion = "";	//accion q se ejecutara
		if ($sAction != "")
			$sAccion = $sAction;
		
		$this->setAction($sAccion);
		// Creamos la cabecera del Combo
		$sHTML.=$this->setCabeceraCombo($this->sNombre,$sSize,$sClass,$sAccion);
	    // si tenemos un primer opcion lo insertamos
		if ($this->sDefault != "")
		{
			if ($sValor != "")
				$sHTML.=$this->addNewOptionBoolean($this->sDefault, false);
			else
				$sHTML.=$this->addNewOptionBoolean($this->sDefault, true);
		}
		//Si hay que añadir mas opciones que la de por defecto
		//Se añaden literalmente. Ej: <option value='-1'>Descipción</option>
		if ($sMoreOptions != "")
		{
			$sHTML.=$sMoreOptions;
		}
	    // recogemos los nombres de las keys de las options
		
       	// recorremos todos los datos del vector para introducirlos como options en el combo
		$this->vDatos->Move(0); //Posicionamos en el primer registro.
		while (!$this->vDatos->EOF)
		{
			$sHTML.=$this->addNewOptionMenu($this->vDatos->fields,$this->sIdKey,$this->sDescKey,$sValor);
			$this->vDatos->MoveNext();
		}

		 // una vez tenemos todos los options cerramos el select
		 $sHTML.="</select>\n";
		
		return $sHTML;	
	} // fin getHTMLComboMenu

	/**
	* Metodo q genera una nueva opcion para el Combo Menú
	* @param hOption		Hashtable con los datos de esta nueva opcion
	* @param sValue			primera Key de la tabla hash hOption
	* @param sDef			segunda Key de la tabal hash hOption
	* @param sDefault		clave seleccionada por defecto
	* @return String
	*/
	function addNewOptionMenu($hOption,$sValue, $sDef, $sDefault)
	{
		$sOption = "";		//Contiene la nueva opcion
		$sAux = "";			//String auxiliar para comparar values
    	$sQuery = "";
		$aux = $this->miconexion;
		
		$sOption.="\t<option style='color:" . $this->getOptionColor() . ";' value='";
		
		$sAux = $hOption[$sValue];
		$sOption.=$sAux ."'";
		// si coincide con el seleccionado lo ponemos cono selected
		if (is_array($sDefault)){
			//Recorremos el array de seleccionados hasta que encontremos uno.
			for ($i=0, $max = sizeof($sDefault); $i < $max; $i++){
				if ($sDefault[$i] != "" && strtolower(trim($sDefault[$i])) == strtolower(trim($sAux)) ){
					$sOption.=" selected=\"selected\"";
					break;
				}
			}
		}else{
			if ($sDefault != "" && strtolower(trim($sDefault)) == strtolower(trim($sAux)) ){
				$sOption.=" selected=\"selected\"";
			}
		}
		$sQuery.=" SELECT indentacion ";
		$sQuery.=" FROM " . strtolower($this->sTabla);
    	$sQuery.=	$this->getWhere();
		$sQuery.=	($this->getWhere() == "") ? " WHERE " : " AND ";
		$sQuery.=	$this->sIdKey . " = " . $aux->qstr($sAux, false);
		$sQuery.= " ORDER BY ";
		$sQuery.= (empty($this->sOrderBy)) ? $this->sDescKey : $this->sOrderBy;
		//obtenemos la conexion
		$result = $aux->Execute($sQuery);
		//Nos tiene que dar un solo elemento ya q vamos por clave.
		$indentacion = 0;
		if ($result){
			while ($arr = $result->FetchRow())
			{
				$indentacion = $arr["indentacion"];
			}
		}else{
			echo("Error SQL:\n" . $sQuery);
			exit;
		}
		$espacios = "";
		for ($i=0; $i < $indentacion; $i++){
			$espacios .="&nbsp;&nbsp;";
		}
		$sOption.=">" . $espacios . $hOption[$sDef] . "</option>\n";
		
		return $sOption;
	} // fin de addNewOptionMenu
} // fin de la clase
?>