var NS = (window.Event) ? 1 : 0;
var OLD="";
if (NS) {
//    document.captureEvents(Event.KEYDOWN | Event.KEYPRESS | Event.KEYUP);    
//    document.captureEvents(Event.MOUSEDOWN | Event.CLICK | Event.MOUSEUP); 
}

function kpress(e){
	//key = (NS) ? e.which : window.event.keyCode;
	if (!e) var e = window.event;
	if (e.keyCode) key = e.keyCode;
	else if (e.which) key = e.which;
	


	cod = eval(key);
	if (cod == 17)
	{
		OLD = cod;
	}else
	{
		if ( cod == 85 && OLD == 17)
		{
			OLD="";
			event.keyCode = 0;
//			alert ("� Azul Pomodoro 2004");
	        return false;
		}
	}
//	alert(cod);
    	if (113 == cod ||
			114 == cod ||
			115 == cod ||
			117 == cod ||
			118 == cod ||
			119 == cod ||
			120 == cod ||
			121 == cod ||
//			122 == cod ||
//			123 == cod ||
//			37 == cod ||
//			39 == cod ||
			93 == cod )
		{
			if (NS){
//				window.location.replace("http://www.azulpomodoro.com");
			}else{
				event.keyCode = 0;
			}
//		    alert ("� Azul Pomodoro 2004");
	        return false;
	    }
/*
		else{
			if ((cod == 17) && NS){
				window.location.replace("http://www.azulpomodoro.com");
				alert ("� Azul Pomodoro 2004\nOptimizado para Microsoft Internet Explorer");
			}
		}
*/		
}

function mpress(e){
//    cod = (NS) ? e.which : window.event.keyCode;
    var cod;
    if (!e) var e = window.event;
	if (e.keyCode) cod = e.keyCode;
	else if (e.which) cod = e.which;    
    if (NS) {
        if (eval(cod)!=1) {
//            alert ("� Azul Pomodoro 2004");
            return false;
        }
    }
}

function contexto(e){
//    alert ("� Azul Pomodoro 2004");
    return false;
}

// Eventos de Teclado (F11)
document.onkeydown = kpress;

// Eventos de Rat�n
document.onmouseup     = mpress;
document.onclick       = mpress;
document.onmousedown   = mpress;
document.oncontextmenu = contexto;