<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="estilos/estilos16.css"/>
<link rel="stylesheet" type="text/css" href="estilos/prettyCheckboxes.css"/>
<script type="text/javascript" src="codigo/jQuery1.4.2.js"></script>
<script type="text/javascript" src="codigo/prettyCheckboxes.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("input[type=radio]").prettyCheckboxes();
	});
</script>
<title>Untitled Document</title>

</head>

<body>
<div id="pagina12">
	<form id="form12" method="post" action="#_">
    	<fieldset>
        	<p class="par12">
        	<label for="empresa12">Empresa</label> 
                <select id="empresa12" name="empresa12">
                  <option value="" selected="selected">- selecciona -</option>
                  <option value="Item1">Item1</option>
                  <option value="Item2">Item2</option>
                  <option value="Item3">Item3</option>
                  <option value="Item4">Item4</option>
                </select> 
            </p>
            <p class="par12">
            	<label for="proceso12">Proceso</label>
            	<input type="text" name="proceso12" id="proceso12" value="" />
            </p>
        </fieldset>
	<div id="recargaProceso12">
    	<fieldset id="date">
        	<p class="par12">
                <label for="fecha">Fecha : DD:MM:YYYY</label>
                <input type="text" name="fecha" id="fecha" value="" /> 
            </p>
            <p class="par12 horas">
                <label for="horaInicio">Hora inicio</label>
                <input type="text" name="horaInicio" id="horaInicio" value="" />
                <span class="time">:</span> 
                <input type="text" name="minutosInicio" id="minutosInicio" value="" />
            </p>
            <p class="par12 horas">
                <label for="horaFin">Hora Final</label>
                <input type="text" name="horaFin" id="horaFin" value="" />
                <span class="time">:</span> 
                <input type="text" name="minutosFin" id="minutosFin" value="" />
            </p>
        </fieldset>
    	<fieldset id="datosPersonales">
            <p class="par12">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" value="" /> 
            </p>
            <p class="par12">
                <label for="apellido1">Apellido 1</label>
                <input type="text" name="apellido1" id="apellido1" value="" /> 
            </p>
            <p class="par12">
                <label for="apellido2">Apellido 2</label>
                <input type="text" name="apellido2" id="apellido2" value="" /> 
            </p>
            <p class="par12">
        	<label for="edad">Edad</label> 
                <select id="edad" name="edad">
                  <option value="" selected="selected">- selecciona -</option>
                  <option value="30">Menor 30</option>
                  <option value="3045">Entre 30 - 45</option>
                  <option value="45">Mayor 45</option>
                </select> 
            </p>
            <p class="par12">
        	<label for="sexo">Sexo</label> 
                <select id="sexo" name="sexo">
                  <option value="" selected="selected">- selecciona  -</option>
                  <option value="hombre">Hombre</option>
                  <option value="mujer">Mujer</option>
                </select> 
            </p>
            <p class="par12">
        	<label for="formacionAcad">Nivel de formacion académica</label> 
                <select id="formacionAcad" name="formacionAcad">
                  <option value="" selected="selected">- selecciona  -</option>
                  <option value="Item1">Item1</option>
                  <option value="Item2">Item2</option>
                  <option value="Item3">Item3</option>
                  <option value="Item4">Item4</option>
                </select> 
            </p>
            <p class="par12">
        	<label for="areaPrefesional">Area profesional</label> 
                <select id="areaPrefesional" name="areaPrefesional">
                  <option value="" selected="selected">- selecciona  -</option>
                  <option value="Item1">Item1</option>
                  <option value="Item2">Item2</option>
                  <option value="Item3">Item3</option>
                  <option value="Item4">Item4</option>
                </select> 
            </p>
        </fieldset>
		<div id="respuestas12">
        	<div class="columna">
                <div class="result">
                    <span class="numPreg">1</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span class="inputCheck">
                            <label for="opcionM-1" tabindex="1"></label>
                            <input type="radio" name="fIdOpcionMejor1" id="opcionM-1" value="radio-m1" />
                            <font>A</font>
                            <label for="opcionP-1" tabindex="2"></label>
                            <input type="radio" name="fIdOpcionPeor1" id="opcionP-1" value="radio-p1" />
                        </span>
                        <span class="inputCheck">
                            <label for="opcionM-2" tabindex="3"></label>
                            <input type="radio" name="fIdOpcionMejor1" id="opcionM-2" value="radio-m2" />
                            <font>B</font>
                            <label for="opcionP-2" tabindex="4"></label>
                            <input type="radio" name="fIdOpcionPeor1" id="opcionP-2" value="radio-p2" />
                        </span>
                        <span class="inputCheck">
                            <label for="opcionM-3" tabindex="5"></label>
                            <input type="radio" name="fIdOpcionMejor1" id="opcionM-3"value="radio-m3"  />
                            <font>C</font>
                            <label for="opcionP-3" tabindex="6"></label>
                            <input type="radio" name="fIdOpcionPeor1" id="opcionP-3" value="radio-p3"/>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
               <div class="result">
                    <span class="numPreg">2</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor2" id="opcionM@4" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor2" id="opcionP@4" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor2"  id="opcionM@5" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor2" id="opcionP@5"  /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor2" id="opcionM@6" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor2" id="opcionP@6" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">3</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor3" id="opcionM@7" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor3" id="opcionP@7" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor3" id="opcionM@8" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor3" id="opcionP@8" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor3" id="opcionM@9" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor3" id="opcionP@9" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">4</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor4" id="opcionM@10" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor4" id="opcionP@10" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor4" id="opcionM@11" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor4" id="opcionP@11" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor4" id="opcionM@12" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor4" id="opcionP@12" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
               
                <div class="result">
                    <span class="numPreg">5</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor5" id="opcionM@13" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor5" id="opcionP@13" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor5" id="opcionM@14" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor5" id="opcionP@14" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor5" id="opcionM@15" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor5" id="opcionP@15" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">6</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor6" id="opcionM@16" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor6" id="opcionP@16" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor6" id="opcionM@17" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor6" id="opcionP@17" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor6" id="opcionM@18" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor6" id="opcionP@18" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">7</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor7" id="opcionM@19" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor7" id="opcionP@19" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor7" id="opcionM@20" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor7" id="opcionP@20" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor7" id="opcionM@21" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor7" id="opcionP@21" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">8</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor7" id="opcionM@22" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor8"  id="opcionP@22" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor7" id="opcionM@23" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor8" id="opcionP@23" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor7" id="opcionM@24" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor8" id="opcionP@24" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">9</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor9" id="opcionM@25" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor9" id="opcionP@25" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor9" id="opcionM@26" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor9" id="opcionP@26" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor9" id="opcionM@27" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor9" id="opcionP@27" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">10</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor10" id="opcionM@28" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor10" id="opcionP@28" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor10" id="opcionM@29" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor10" id="opcionP@29" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor10" id="opcionM@30" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor10" id="opcionP@30" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">11</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor11" id="opcionM@31" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor11" id="opcionP@31" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor11" id="opcionM@32" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor11" id="opcionP@32" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor11" id="opcionM@33" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor11" id="opcionP@33" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">12</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor12" id="opcionM@34" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor12" id="opcionP@34" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor12" id="opcionM@35" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor12" id="opcionP@35" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor12" id="opcionM@36" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor12" id="opcionP@36" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
            </div><!-- Fin div columna -->
            
            <div class="columna">
                <div class="result">
                    <span class="numPreg">13</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor13" id="opcionM@37" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor13" id="opcionP@37" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor13" id="opcionM@38" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor13" id="opcionP@38" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor13" id="opcionM@39" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor13" id="opcionP@39" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
               <div class="result">
                    <span class="numPreg">14</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor14" id="opcionM@40" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor14" id="opcionP@40" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor14" id="opcionM@41" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor14" id="opcionP@41" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor14" id="opcionM@42" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor14" id="opcionP@42" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">15</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor15" id="opcionM@43" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor15" id="opcionP@43" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor15" id="opcionM@44" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor15" id="opcionP@44" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor15" id="opcionM@45" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor15" id="opcionP@45" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">16</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor16" id="opcionM@46" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor16" id="opcionP@46" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor16" id="opcionM@47" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor16" id="opcionP@47" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor16" id="opcionM@48" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor16" id="opcionP@48" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
               
                <div class="result">
                    <span class="numPreg">17</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor17" id="opcionM@49" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor17" id="opcionP@49"/></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor17" id="opcionM@50" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor17" id="opcionP@50" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor17" id="opcionM@51" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor17" id="opcionP@51" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">18</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor18" id="opcionM@52" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor18" id="opcionP@52" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor18" id="opcionM@53" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor18" id="opcionP@53" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor18" id="opcionM@54" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor18" id="opcionP@54" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">19</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor19" id="opcionM@55" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor19" id="opcionP@55" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor19" id="opcionM@56" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor19" id="opcionP@56" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor19" id="opcionM@57" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor19" id="opcionP@57" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">20</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor20" id="opcionM@58" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor20" id="opcionP@58" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor20" id="opcionM@59" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor20" id="opcionP@59" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor20" id="opcionM@60" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor20" id="opcionP@60" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">21</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor21" id="opcionM@61"/></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor21" id="opcionP@61" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor21" id="opcionM@62"/></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor21" id="opcionP@62" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor21" id="opcionM@63" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor21" id="opcionP@63" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">22</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor22" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor22" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor22" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor22" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor22" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor22" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">23</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor23" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor23" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor23" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor23" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor23" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor23" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">24</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor24" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor24" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor24" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor24" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor24" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor24" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
            </div><!-- Fin div columna -->
            
            <div class="columna">
                <div class="result">
                    <span class="numPreg">25</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor25" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor25" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor25" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor25" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor25" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor25" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
               <div class="result">
                    <span class="numPreg">26</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor26" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor26" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor26" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor26" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor26" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor26" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">27</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor27" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor27" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor27" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor27" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor27" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor27" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">28</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor28" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor28" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor28" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor28" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor28" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor28" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
               
                <div class="result">
                    <span class="numPreg">29</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor29" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor29" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor29" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor29" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor29" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor29" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">30</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor30" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor30" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor30" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor30" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor30" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor30" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">31</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor31" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor31" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor31" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor31" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor31" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor31" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">32</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor32" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor32" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor32" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor32" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor32" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor32" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">33</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor33" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor33" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor33" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor33" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor33" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor33" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">34</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor34" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor34" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor34" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor34" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor34" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor34" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">35</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor35" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor35" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor35" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor35" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor35" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor35" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">36</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor36" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor36" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor36" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor36" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor36" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor36" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
            </div><!-- Fin div columna -->
            
            <div class="columna">
                <div class="result">
                    <span class="numPreg">37</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor37" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor37" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor37" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor37" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor37" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor37" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
               <div class="result">
                    <span class="numPreg">38</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor38" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor38" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor38" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor38" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor38" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor38" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">39</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor39" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor39" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor39" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor39" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor39" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor39" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">40</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor40" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor40" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor40" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor40" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor40" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor40" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
               
                <div class="result">
                    <span class="numPreg">41</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor41" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor41" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor41" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor41" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor41" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor41" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">42</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor42" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor42" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor42" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor42" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor42" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor42" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">43</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor43" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor43" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor43" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor43" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor43" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor43" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">44</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor44" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor44" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor44" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor44" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor44" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor44" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">45</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor45" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor45" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor45" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor45" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor45" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor45" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">46</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor46" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor46" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor46" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor46" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor46" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor46" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">47</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor47" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor47" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor47" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor47" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor47" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor47" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">48</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor48" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor48" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor48" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor48" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor48" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor48" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
            </div><!-- Fin div columna -->
            
            <div class="columna">
                <div class="result">
                    <span class="numPreg">49</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor49" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor49" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor49" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor49" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor49" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor49" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
               <div class="result">
                    <span class="numPreg">50</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor50" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor50" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor50" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor50" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor50" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor50" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">51</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor51" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor51" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor51" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor51" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor51" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor51" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->
                
                <div class="result">
                    <span class="numPreg">52</span>
                    <span class="mp">
                        <font>M</font>
                        <font>P</font>	
                    </span>
                    <span class="radios">
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor52" /></font>
                            <font>A</font>
                            <font><input type="radio" name="fIdOpcionPeor52" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor52" /></font>
                            <font>B</font>
                            <font><input type="radio" name="fIdOpcionPeor52" /></font>
                        </span>
                        <span>
                            <font><input type="radio" name="fIdOpcionMejor52" /></font>
                            <font>C</font>
                            <font><input type="radio" name="fIdOpcionPeor52" /></font>
                        </span>
                    </span>
                </div><!-- Fin caja result-->

            </div><!-- Fin div columna -->
            
        </div><!-- Fin div respuestas12-->
    </div><!-- Fin div recargaProceso12-->
    </form>
</div>
</body>
</html>