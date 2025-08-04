function confpopup(sPath,sMsg,form){
	if (form == null) form = "izquierda";
	if (confirm(sMsg))	
		eval('top.' + form).location.href=sPath;
}
function zoomWindow(path, name)
{
	var ancho=610;
	var alto=620;
	var fin=ancho + 200;
	var x=25;
	var y=25;
	if (window.resizeTo&&navigator.userAgent.indexOf("Opera")==-1) {
		var ventana = window.open("", null, "resizable,height=1,width=1,top="+ x+ ",left="+ y + ",screenX=" + x + ",screenY=" + y );
		while(ancho <= fin ){
			ventana.moveTo(x,y);
			ventana.resizeTo(ancho,alto);
			x+=5;
			y+=2;
			ancho+=50;
			alto+=10;
		}
		ventana.location = path;
	}else{
		ventana = window.open(path,name);
	}
	ventana.focus();
}
function expandingWindow(website) {
	var windowprops='width=50,height=50,scrollbars=yes,status=yes,resizable=yes'
	var heightspeed = 25; // velocidad vertical
	var widthspeed = 25;  // velocidad horizontal
	var leftdist = 50;    // ditancia (izquierda)
	var topdist = 50;     // distancia (arriba)

	if (window.resizeTo&&navigator.userAgent.indexOf("Opera")==-1) {
		var winwidth = window.screen.availWidth - leftdist;
		var winheight = window.screen.availHeight - topdist;
		var sizer = window.open("","","left=" + leftdist + ",top=" + topdist +","+ windowprops);
		for (sizeheight = 1; sizeheight < winheight; sizeheight += heightspeed)
			sizer.resizeTo("1", sizeheight);
		for (sizewidth = 1; sizewidth < winwidth; sizewidth += widthspeed)
			sizer.resizeTo(sizewidth, sizeheight);
		sizer.location = website;
	}else	window.open(website,'Window');
}
function overTR(tr,color){tr.style.cursor='pointer';tr.bgColor=color;}
function outTR(tr,color){tr.style.cursor='default';tr.bgColor=color;}
var flechason=new Array();
	flechason[0]="sp";
	flechason[1]="flecha";
	
var flechasona=new Array();
for(var i=0; i<flechason.length; i++)	{
	flechasona[i]=new Image();
	flechasona[i].src='graf/'+flechason[i]+'.gif';
}
var clicado=-1;
function cambia(cual,evento){ 
	if(evento==1){ //over
		for (i=0;i<200; i++){
			if (eval("document.a"+i)!=null){
				eval("document.a" + i + ".src = flechasona[0].src");
			}
		}
		eval("document.a" + cual+".src = flechasona[1].src");
	}else if(evento==0){ //out
		for (i=0;i<200; i++){
			if (eval("document.a"+i)!=null){
				eval("document.a" + i + ".src = flechasona[0].src");
			}
		}
		if (clicado!=-1){
			eval("document.a" + clicado +".src = flechasona[1].src");
		}	
	}else{
		if(evento==2){ //click
			clicado = cual;
			document.forms[0]._clicado.value=cual;
		}
	}
}
function setClicado(cual){ 
	for (i=0;i<200; i++){
		if (eval("document.a"+i)!=null){
			eval("document.a" + i + ".src = flechasona[0].src");
		}
	}
	if (cual != "-1"){
    	if (eval("document.a"+cual)!=null){
	      eval("document.a" + cual+".src = flechasona[1].src");
	       clicado = cual;
    	}
	}else{clicado=-1;}
}
var botonOn=new Array();
	botonOn[0]="btn_inicio_on.png";
	botonOn[1]="btn_menu_on.png";
	botonOn[2]="btn_repo_on.png";
	botonOn[3]="btn_contenidos_on.png";
	botonOn[4]="btn_publi_on.png";
	botonOn[5]="btn_eventos_on.png";
	botonOn[6]="btn_faq_on.png";
	botonOn[7]="btn_banner_on.png";
	botonOn[8]="btn_boletines_on.png";
	botonOn[9]="btn_tienda_on.png";
	botonOn[10]="btn_acceso_on.png";
	botonOn[11]="btn_salir_on.png"
	
var botonOff=new Array();
	botonOff[0]="btn_inicio_off.png";
	botonOff[1]="btn_menu_off.png";
	botonOff[2]="btn_repo_off.png";
	botonOff[3]="btn_contenidos_off.png";
	botonOff[4]="btn_publi_off.png";
	botonOff[5]="btn_eventos_off.png";
	botonOff[6]="btn_faq_off.png";
	botonOff[7]="btn_banner_off.png";
	botonOff[8]="btn_boletines_off.png";
	botonOff[9]="btn_tienda_off.png";
	botonOff[10]="btn_acceso_off.png";
	botonOff[11]="btn_salir_off.png";
	
var botonOna=new Array();
for(var i=0; i<botonOn.length; i++)	{
	botonOna[i]=new Image();
	botonOna[i].src='graf/'+botonOn[i];
}
var botonOffa=new Array();
for(var i=0; i<botonOff.length; i++)	{
	botonOffa[i]=new Image();
	botonOffa[i].src='graf/'+botonOff[i];
}
var clicadoImg=-1;
function cambiaImg(cual,evento){
	if(evento==1){ //over
		for (i=0; i < botonOn.length; i++){
			if (eval("document.b" + i) != null){
				eval("document.b" + i + ".src = botonOffa[i].src");
			}
		}
		eval("document.b" + cual + ".src = botonOna[" + cual + "].src");
	}else if(evento==0){ //out
		for (i=0;i < botonOn.length; i++){
			if (eval("document.b"+i)!=null){
				eval("document.b" + i + ".src = botonOffa[" + i + "].src");
			}
		}
		if (clicadoImg != -1){
            if (eval("document.b"+clicadoImg)!=null){
                eval("document.b" + clicadoImg + ".src = botonOna[" + clicadoImg + "].src");
            }
		}	
	}else{
		if(evento==2){ //click
			clicadoImg = cual;
		}
	}
}
