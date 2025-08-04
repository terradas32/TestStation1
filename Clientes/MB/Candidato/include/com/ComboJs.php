<?php 
class Combojs
{
	function Combojs(&$padre, &$hijo, $funcion){
	
		$script.="function seleccionaComboNull(funcion, sCampo, sValor){";
		$script.="if (funcion != \"\") eval(funcion);";
		$script.="var s2= eval(\"document.forms[0].\" + sCampo);";
		$script.="for (var i=0; i < s2.length; i++){";
		$script.="if (s2.options[i].value == sValor){";
		$script.="s2.options[i].selected=true;";
		$script.="}";
		$script.="}";
		$script.="}\n";
		
		$script.="function anadir(nombre,texto,valor){";
		$script.="formulario=document.forms[0].elements[nombre];formulario.length++;";
		$script.="formulario.options[formulario.length-1].value=valor;";
		$script.="formulario.options[formulario.length-1].text=texto;}\n";
		$script.= $hijo->getJSComboChange($padre, "forms[0]", $funcion);
		echo($script);
	}
}  // fin de la clase
?>
