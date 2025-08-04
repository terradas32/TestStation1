function trim(objet){
	objet.value=Trim(objet.value);
}
function LTrim(s){
	var i=0;
	var j=0;
	for(i=0; i<=s.length; i++){
		if(s.substring(i,i+1) != ' '){
			j=i;
			break;
		}
	}
	return s.substring(j, s.length);
}
function RTrim(s){
	var j=0;
	for(var i=s.length-1; i>-1; i--){
		if(s.substring(i,i+1) != ' '){
			j=i;
			break;
		}
	}
	return s.substring(0, j+1);
}
function Trim(s){
	return LTrim(RTrim(s));
}
function vString(nombre,sValor,iLongitud,bObligatorio){
	var sError = "";
	if (!checkRequired(bObligatorio,sValor)){
		sError+="\t" + STR_CAMPO_REQUERIDO + "\n";
	}
	if (!checkLength(iLongitud,sValor)){
		sError+="\t" + STR_ERROR_LONGITUD + "\n";
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function vNumber(nombre,sValor,iLongitud,bObligatorio){
	var sError = "";
	if (!checkRequired(bObligatorio,sValor)){
		sError+="\t" + STR_CAMPO_REQUERIDO + "\n";
	}
	if (!checkChars(sValor)){
		sError+="\t" + STR_ERROR_CARACTERES + "\n";
	}
	if (!checkLength(iLongitud,sValor)){
		sError+="\t" + STR_ERROR_LONGITUD + "\n";
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function vEmail(nombre,sValor,iLongitud,bObligatorio){
	var sError = "";
	if (!checkRequired(bObligatorio,sValor)){
		sError+="\t" + STR_CAMPO_REQUERIDO + "\n";
	}
	if (!checkEmail(sValor,bObligatorio)){
		sError+="\t" + STR_EMAIL_INCORRECTO + "\n";
	}
	if (!checkLength(iLongitud,sValor)){
		sError+="\t" + STR_ERROR_LONGITUD + "\n";
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function vDc(nombre,sEntidad,sOficina,sDc,sCc,bObligatorio){
	var sError = "";
	if (!checkRequired(bObligatorio,sEntidad)){
		sError+="\t" + STR_BANCO_CAMPO_REQUERIDO + "\n";
	}
	if (!checkRequired(bObligatorio,sOficina)){
		sError+="\t" + STR_OFICINA_CAMPO_REQUERIDO + "\n";
	}
	if (!checkRequired(bObligatorio,sDc)){
		sError+="\t" + STR_DC_CAMPO_REQUERIDO + "\n";
	}
	if (!checkRequired(bObligatorio,sCc)){
		sError+="\t" + STR_CUENTA_CAMPO_REQUERIDO + "\n";
	}
	if (!validarDC(sEntidad,sOficina,sDc,sCc)){
		sError+="\t" + STR_DATOS_BANCARIOS_INCORRECTOS + "\n";
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function vDate(nombre,sValor,iLongitud,bObligatorio){
	var sError = "";
	var bOk= true;
	if (!checkRequired(bObligatorio,sValor)){
		sError+="\t" + STR_CAMPO_REQUERIDO + "\n";
		bOk = false;
	}
	if (!checkChars(sValor)){
		sError+="\t" + STR_ERROR_CARACTERES + "\n";
		bOk = false;
	}
	if (!checkLength(iLongitud,sValor)){
		sError+="\t" + STR_ERROR_LONGITUD + "\n";
		bOk = false;
	}
	if (bOk){
		if (!checkFormat(sValor)){
			sError+="\t" + STR_FORMATO_ERRONEO + "\n";
		}
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function checkRequired(bObligatorio,sValor){
	if (!bObligatorio)
		return true;
	else{
		if (sValor == null)
			return false;
		else{
			if (Trim(sValor) == "")
				return false;
			else return true;	
		}
	}
} // fin de checRequired
function checkLength(iLongitud,sValor){
	if (sValor.length <= iLongitud)	return true;
	else	return false;
} // fin de checkLength
function checkChars(sValor){
	var sVal = "1234567890/-:,.";
	if (sValor != null){
		for (var i = 0; i < sValor.length; i++){
			if (sVal.indexOf(sValor.charAt(i)) == -1)
				return false;
		}
		return true;
	}else return false;
} // fin de checkChars
function checkEmail(sValor,bObligatorio){
	var iPos;	//posicion de la arroba
	var iPosi;	//posicion del punto
	if (sValor == null || sValor.length == 0){
		return checkRequired(bObligatorio,sValor);
	}
	//miramos q haya arroba
	if ((iPos = sValor.indexOf("@")) == -1)
		return false;
	else{
		// si la arroba esta al final
		if (iPos >= sValor.length)
			return false;
	}
	if ((iPosi = sValor.indexOf(".")) == -1)
		return false;
	else{
		// si el punto esta al final
		if (iPosi >= sValor.length)
			return false;
	}
	return true;
} // fin de checkEmail
function checkFormat(sValor){
	if (sValor == null)
		return true;
	if (Trim(sValor) == "")
		return true;
	if (sValor.length < 10)
		return false;
	return ValidaFecha(sValor,"dd/mm/yyyy");
}
var sDd;
var sMes;
var sYyyy;
function ValidaFecha(sFecha, sFormatoFecha){
  var i=0;
  var dia=0;
  var mes=0;
  var anyo=0;
  var tamanio = (sFormatoFecha.length-1)+1;
  while (i< tamanio){
    if (sFormatoFecha.substring(i,i+2) == "dd"){
   	    dia= parseInt(sFecha.substring(i,i+2),10);
		sDd = dia;
		i=i+2;	
		if (i!=tamanio){
			if (!((sFormatoFecha.substring(i,i+1) == "m")||(sFormatoFecha.substring(i,i+1) == "y")))
				i=i+1;
		}
   }
   else
   	if (sFormatoFecha.substring(i,i+2) == "mm"){
   		mes= parseInt(sFecha.substring(i,i+2),10);
		sMes=mes;
		i=i+2 ; 
		if (i!=tamanio){
			if (!((sFormatoFecha.substring(i,i+1) == "d")||(sFormatoFecha.substring(i,i+1) == "y")))
				i=i+1;
		}	
     }
   else
   	if (sFormatoFecha.substring(i,i+4) == "yyyy"){
		anyo= parseInt(sFecha.substring(i,i+4),10);
		sYyyy=anyo;
    	i=i+4;
		if (i!=tamanio){
			if (!((sFormatoFecha.substring(i,i+1) == "m")||(sFormatoFecha.substring(i,i+1) == "d")))
				i=i+1;	
		}
   	}
   else
  		if (sFormatoFecha.substring(i,i+2) == "yy"){
   			anyo= parseInt(sFecha.substring(i,i+2),10);
			sYyyy=anyo;
			i=i+2;
			if (anyo >=25){
				anyo = 1900+anyo;
			}
			if (anyo < 25){
				anyo = 2000+anyo;
			}
			if (i!=tamanio){	
				if (!((sFormatoFecha.substring(i,i+1) == "m")||(sFormatoFecha.substring(i,i+1) == "d")))
					i=i+1;
			}	
	   	}
  }
  return ValidarFecha(dia,mes,anyo);
}
function ValidarFecha (dia,mes,ano){
	if ((ano < 1900) | (ano > 99999))
		return false;
			
	if ((mes < 1) | (mes > 12))
		return false;
		
	if ((mes == 4) | (mes == 6) | (mes == 9) | (mes == 11))	
	{
		if ((dia < 1) | (dia > 30))
			return false;
	}
	else
	{
		if (mes == 2)
		{
			if ((EsBisiesto(ano) == true) & ((dia > 29) | (dia < 1)))
				return false;
			if ((EsBisiesto(ano) == false) & ((dia > 28) | (dia < 1)))	
				return false;
		}
		else
		{
			if ((dia < 1) | (dia > 31))
				return false;
		}
	}
	return true;
}
function cFechaFormat(sFec){
	var sFecha = "";
	if (sFec != "")
	{
		if (sFec != "" && !ValidaFecha(sFec,"dd/mm/yyyy"))
		{
			var expreg = new RegExp("[/]+","g");
			if (!ValidaFecha(sFec,"yyyy/mm/dd"))	return sFecha;
			else	sFecha = sFec.replace(expreg,'-');
		}else
		{
			var expreg = new RegExp("[-]+","g");
			sFecha = getFormatDate(sFec.replace(expreg,'/'));
		}
	}
	return sFecha;
}
function getFormatDate(sFecha){
	sFec="";
	sFec+=sFecha.substring(sFecha.lastIndexOf("/")+1,sFecha.lastIndexOf("/")+5);//A�O
	sFec+="-";
	sFec+=sFecha.substring(sFecha.indexOf("/")+1,sFecha.lastIndexOf("/"));//MES
	sFec+="-";
	sFec+=sFecha.substring(0,sFecha.indexOf("/"));//DIA
	return sFec;
}
function formatThisDate(sFecha){
	sFec="";
	if (sFecha != "" && sFecha != "0000-00-00")
	{
		sFec+=sFecha.substring(sFecha.lastIndexOf("-")+1,sFecha.lastIndexOf("-")+3);//DIA		
		sFec+="/";
		sFec+=sFecha.substring(sFecha.indexOf("-")+1,sFecha.lastIndexOf("-"));//MES
		sFec+="/";
		sFec+=sFecha.substring(0,sFecha.indexOf("-"));//A�O
	}
	return sFec;
}
function EsBisiesto(ano){
 	
 	if ((ano % 4) == 0){return true;}
 	else 
 		if (((ano % 100) == 0) && ((ano % 400) == 0)) {return true;}
      	else { return false;}	
}
function toJSDate(fecha,sFormatoFecha, sHora, sMin)
{
	if (sHora == undefined){
		sHora = "";
	}
	if (sMin == undefined){
		sMin = "";
	}
	
	ValidaFecha(fecha,sFormatoFecha);
	dia=sDd;
	mes=sMes;
	anio=sYyyy;
	if (sHora != "" && sMin !=""){
		return new Date(anio,mes-1,dia, sHora, sMin);
	}
	if (sHora != ""){
		return new Date(anio,mes-1,dia, sHora);
	}
	return new Date(anio,mes-1,dia);
}
function cQuitaAcentos(sCadena){ 
	sChars = "�����������������������������������������";
	sScapes = "aeiouAEIOUaeiouAEIOUaeiouAEIOUaeiouAEIOUn";
	var sRetorno="";
	for (var j = 0; j < sCadena.length; j++)
	{
		aguja=sChars.indexOf(sCadena.charAt(j));
		if (aguja == -1){
			sRetorno+= sCadena.charAt(j);
		}else{ sRetorno+= sScapes.charAt(aguja);}
	}
	return (sRetorno);
}
function vNif(nombre,sValor,iLongitud,bObligatorio){
	var sError = "";
	if (!checkRequired(bObligatorio,sValor))
	{
		sError+="\t" + STR_CAMPO_REQUERIDO + "\n";
	}
	if (!validarNIF(sValor,bObligatorio))
	{
		sError+="\t" + STR_NIF_INCORRECTO + "\n";
	}
	if (!checkLength(iLongitud,sValor))
	{
		sError+="\t" + STR_ERROR_LONGITUD + "\n";
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function vCif(nombre,sValor,iLongitud,bObligatorio){
	var sError = "";
	if (!checkRequired(bObligatorio,sValor))
	{
		sError+="\t" + STR_CAMPO_REQUERIDO + "\n";
	}
	if (!validarCIF(sValor,bObligatorio))
	{
		sError+="\t" + STR_CIF_INCORRECTO + "\n";
	}
	if (!checkLength(iLongitud,sValor))
	{
		sError+="\t" + STR_ERROR_LONGITUD + "\n";
	}
	if (sError !="") sError = nombre + "\n" + sError;
	return sError;
}
function validarNIF(NIF,bObligatorio){
	if (NIF == null || NIF.length == 0)
	{
		return checkRequired(bObligatorio,NIF);
	}
   	var cadena ="TRWAGMYFPDXBNJZSQVHLCKE";
	NIF=NIF.toUpperCase();
	var longitud=NIF.length;
	if (longitud > 9)
		return false;
	longitud=longitud-1;
	var a="";
	a=cadena.substring(NIF.substring(0,NIF.length - 1) %  23,NIF.substring(0,NIF.length - 1) % 23+1);
	var dni="";
	dni=NIF.substring(0,longitud);
	if (!(esDigito(NIF.charAt(longitud)))&&(NIF.charAt(longitud-1)!=" ")&&(NIF.charAt(0)!=" ")){
		if (NIF.charAt(longitud)==a){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
function esDigito(c){
   return ((c >= "0") && (c <= "9"))
}
function validarCIF(CIF,bObligatorio){
	if (CIF == null || CIF.length == 0)
	{
		return checkRequired(bObligatorio,CIF);
	}
	var retorno=true;
	var ultimaL;
	ultimaL=CIF.substring(CIF.length - 1,CIF.length);
	var arrayCif = new Array();
		arrayCif[0] = "J"; arrayCif[1] = "A"; arrayCif[2] = "B"; arrayCif[3] = "C"; arrayCif[4] = "D";
		arrayCif[5] = "E"; arrayCif[6] = "F"; arrayCif[7] = "G"; arrayCif[8] = "H"; arrayCif[9] = "I";

		var cadenaCIF= new String(CIF);
		var primera=CIF.charAt(0);
		var ultima;
		if(!esDigito(primera)){
			if (cadenaCIF.length < 8){
				return false;
			}
			primera=primera.toUpperCase();
			var subcadenaCIF=new String();
			subcadenaCIF=cadenaCIF.substring(1,8);
			for (i = 0; i < subcadenaCIF.length; i++){
        		var c = subcadenaCIF.charAt(i);
        		if (!esDigito(c)){
					return false;
        		}	
    		}
			var j=0;
			var i;
			var digito, division, modulo, suma=0;
			for(i=1;i<8;i++){
				digito=subcadenaCIF.substring(j,i);
				digito = parseInt(digito,10);
				if((i+1)%2==0){ //es par
					digito=digito*2;
					while(digito>=10){
						division=parseInt(digito/10,10);
						modulo=digito%10;
						digito=division+modulo;
					}
				 	suma=suma+digito;
				}else{ //es impar
					suma=suma+digito;
				}
				j++;
			}
			digito=suma;
			suma=parseFloat(suma/10);
			digito=(((Math.ceil(suma)) * 10) - digito);
			if(primera==("P") || primera==("Q") || primera==("S")){
				ultima=arrayCif[digito];
				if (ultimaL==ultima){
					retorno=true
				}else{
					retorno=false;
				}
			}else{
			if (ultimaL==digito){
					retorno=true
				}else{
					retorno=false;
				}
			}
		}else{
			retorno=false;
		}
	return retorno;
}
function validarDC(entidad,sucursal,DC,cc){
	if (LTrim(RTrim(entidad)) !="" && LTrim(RTrim(sucursal)) !="" && LTrim(RTrim(DC)) !="" && LTrim(RTrim(cc)) !=""){
		cadena= "0" + "0" + entidad + sucursal
		pesoOfi=calculaDc(cadena);
		pesoCuent=calculaDc(cc);
		digitocontrol = "" + pesoOfi + pesoCuent;
	
		if (DC != digitocontrol){
			return false;
		}
		return true;
	}else return true;
}
function calculaDc(cadenaCalcu){
	var restoPeso;
	var tabCalcu;
	var resultado = 0; 
	var tabPesos;
	tabCalcu=new Array();
	tabPesos=new Array();
	tabPesos[0] = "01"; tabPesos[1] = "02"; tabPesos[2] = "04"; tabPesos[3] = "08"; tabPesos[4] = "05";
	tabPesos[5] = "10"; tabPesos[6] = "09"; tabPesos[7] = "07"; tabPesos[8] = "03"; tabPesos[9] = "06";
	
	for (var j = 0; j < cadenaCalcu.length; j++){
		tabCalcu[j] = cadenaCalcu.charAt(j);
	}
	for (var i = 0; i < cadenaCalcu.length; i++){
		resultado = resultado + (tabCalcu[i] * tabPesos[i]);
	}
	var r = resultado / 11;
	i = parseInt(r,10);
	var resto = resultado - (11 * i);
	resto = 11 - resto;
	if (resto == 10){
	  restoPeso = 1;
	}else if(resto ==11){
	  resto = 0;
	}
return resto;
}
function comaAPunto(campoPunto, campoComa){
	var expreg = new RegExp("^(\\d+|\\d{1,3}(\\.\\d{3})*)(,\\d{0,2})?$");
    if (isNaN(campoComa.value)) {
        var str = new String(campoComa.value);
		expreg.compile(",(\\d{0,2})"); // Comas de decimales a puntos
        str2 = str.replace(expreg, ".$1");
        if (str2 != '')
        {
            var idx = str2.indexOf(".");
            if (idx == -1)
                str2 = str2.concat(".00");
            else
            {
                for (i = (str2.length - idx); i <= 2; i++)
                    str2 = str2.concat("0");
            }
        }
        campoPunto.value = str2;
    }
}
function puntoAComa(campoPunto, campoComa){
    if (!isNaN(campoPunto.value)) {
        var str = new String(campoPunto.value);
        var str2 = str.replace(/\.(\d{0,2})$/, ",$1");
        if (str2 != '')
        {
            var idx = str2.indexOf(",");
            if (idx == -1)
                str2 = str2.concat(",00");
            else
            {
                for (i = (str2.length - idx); i <= 2; i++)
                    str2 = str2.concat("0");
            }
        }
        campoComa.value = str2;
    }
}
function redondeo(num, decimales){
    var resul;
    if (navigator.appName == "Microsoft Internet Explorer")
    {
        var numObj = new Number(num);
        resul = numObj.toFixed(decimales);
    }
    else // if (navigator.appName == "Netscape")
    {
        var casi = 1 + (0.5 / (Math.pow(10,  decimales)));
        var str = ((num - 1) + casi) + "";
        resul = str.substring(0, str.indexOf('.') + decimales + 1);
    }
    return resul;
}