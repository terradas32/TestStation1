<?php

$_EscalaMaxima="7";
$_urlTxtToGraf='http://www.cuestionariosonline.net/txtImg.php';
$_grafW=948;
$_grafH=300;
//Funciones de utilidades para el informe.

function get_include_contents($filename) {
	if (is_file($filename)) {
		ob_start();
		include $filename;
		return ob_get_clean();
	}
	return false;
}
    /*
    * Devuelve la diferencia en minutos entre dos fechas dadas de tipo timestamp
    * @param fechaInicio Fecha menos reciente
    * @param fechaFin Fecha más reciente.        
    */
	function tiempoTranscurridoFechas($fechaInicio, $fechaFin)
	{
	    $fecha1 = new DateTime($fechaInicio);
	    $fecha2 = new DateTime($fechaFin);
	    $fecha = $fecha1->diff($fecha2);
	    $tiempo = "";
	    //años
	    if($fecha->y > 0){
	        $tiempo .= $fecha->y;
	        if($fecha->y == 1)
	            $tiempo .= " año, ";
	        else
	            $tiempo .= " años, ";
	    }
	    //meses
	    if($fecha->m > 0){
	        $tiempo .= $fecha->m;
	        if($fecha->m == 1)
	            $tiempo .= " mes, ";
	        else
	            $tiempo .= " meses, ";
	    }
	    //dias
	    if($fecha->d > 0){
	        $tiempo .= $fecha->d;
	        if($fecha->d == 1)
	            $tiempo .= " día, ";
	        else
	            $tiempo .= " días, ";
	    }
	    //horas
	    if($fecha->h > 0){
	        $tiempo .= $fecha->h;
	        if($fecha->h == 1)
	            $tiempo .= " h ";
	        else
	            $tiempo .= " h ";
	    }
	    //minutos
	    if($fecha->i > 0){
	        $tiempo .= $fecha->i;
	        if($fecha->i == 1)
	            $tiempo .= " min";
	        else
	            $tiempo .= " min";
	    }
	    else if($fecha->i == 0) //segundos
	        $tiempo .= $fecha->s." seg";
	         
	    return $tiempo;
	}

	function getChdlPROC($bPerfilRequerido){
		$chdl="";
	    if ($bPerfilRequerido){
	    	$chdl.="Perfil+Requerido";
	    	$chdl.="|";
	    }
	    
	    $chdl.= "Resultados+Procesos";
	    return $chdl;
	}

	function getChdl($bManager,$bPerfilRequerido){
		$chdl="";
	    if ($bPerfilRequerido){
	    	$chdl.="Perfil+Requerido";
	    	$chdl.="|";
	    }
	    if ($bManager){
	    	$chdl.="Resultados+Manager";
	    	$chdl.="|";
	    }
	     
	    $chdl.= "Resultados+Autopercepcion|Resultados+AC";
	    return $chdl;
	}
	function getTRLiterales($bManager,$bPerfilRequerido)
	{
		$sRetorno="";
		$sRetorno.='
               	<tr>
               		<td width="15%">&nbsp;</td>
			';
		if ($bPerfilRequerido){
			$sRetorno.='
                		<td><hr class="PR"></hr></td>
                		<td>Perfil Requerido</td>
               	';
		}
		if ($bManager){
					$sRetorno.='
		                		<td><hr class="RM"></hr></td>
		                		<td>Resultados Manager</td>
				';
		}
		$sRetorno.='
               		<td><hr class="RA"></hr></td>
               		<td style="padding-left: 3px;">Resultados Autopercepción</td>
			<!-- dinamico 
               		<td><hr class="AC" style="background-color: #CC0033;"></hr></td>
				-->
               		<td><hr class="AC" style="background-color: #CC0033;"></hr></td>
               		<td>Resultados AC</td>
               		<td width="15%">&nbsp;</td>
               	</tr>
               	';
		return $sRetorno;
	}
	function getTRLiteralesPROC($bPerfilRequerido)
	{
		$sRetorno="";
		$sRetorno.='
               	<tr>
               		<td width="15%">&nbsp;</td>
			';
		if ($bPerfilRequerido){
			$sRetorno.='
                		<td><hr class="PR"></hr></td>
                		<td>Perfil Requerido</td>
               	';
		}
		$sRetorno.='
			<!-- dinamico 
               		<td><hr class="AC" style="background-color: #CC0033;"></hr></td>
				-->
               		<td><hr class="AC" style="background-color: #CC0033;"></hr></td>
               		<td>Resultados AC</td>
               		<td width="15%">&nbsp;</td>
               	</tr>
               	';
		return $sRetorno;
	}
	
	function getExtrasPROC($bPerfilRequerido){
		global $_EscalaMaxima;
		$iIndice=0;
		
		$sExtras="";
		$chco="";
		if ($bPerfilRequerido){
			$chco.="f19a9d";
			$chco.=",";
		}
		
		$chco.= "cc0033";
		$chls="";

		if ($bPerfilRequerido){
			$chls.="5";
			$chls.="|";
		}
		
		$chls.= "3";
		
		$chls="&chls=" . $chls;
		
		$chm= "";
		if ($bPerfilRequerido){
			$chm.="H,f19a9d," . $iIndice . ",-1,5";
			$chm.="|";
			$iIndice++;
		}
		
/* Dinámico
		 $chm.= "o,$clients->colorSeccion2,1,-1,8";
*/
		$chm.= "o,cc0033,". $iIndice .",-1,8";
		$iIndice++;
		$chm="&chm=" . $chm;
		$chds="&chds=0," . $_EscalaMaxima;
		$chxr="&chxr=0,0," . $_EscalaMaxima . ",1";
		$chg="&chg=100,14.26,0,0";
		$sExtras=$chco . $chls . $chm . $chds . $chxr. $chg;
		
		return $sExtras;
	}
	
	function getExtras($bManager,$bPerfilRequerido){
		global $_EscalaMaxima;
		$iIndice=0;		
		$sExtras="";
		$chco="";

		if ($bPerfilRequerido){
			$chco.="f19a9d";
			$chco.=",";
		}
		
		if ($bManager){
			$chco.="000000";
			$chco.=",";
		}		
/* Dinámico 
		$chco.= "CCCCCC,$clients->colorSeccion2";
*/
		//PR,Manager,Autopercepción, AC
		$chco.= "CCCCCC,cc0033";
		$chls="";
		if ($bPerfilRequerido){
			$chls.="5";
			$chls.="|";
		}
		if ($bManager){
			$chls.="3";
			$chls.="|";
		}

		$chls.= "3|3";
		
		$chls="&chls=" . $chls;
		
		$chm= "";

		if ($bPerfilRequerido){
			$chm.="H,f19a9d," . $iIndice . ",-1,5";
			$chm.="|";
			$iIndice++;
		}
		if ($bManager){
			$chm.="s,000000," . $iIndice . ",-1,8";
			$chm.="|";
			$iIndice++;
		}
		
/* Dinámico
		 $chm.= "d,cccccc,2,-1,8|o,$clients->colorSeccion2,2,-1,8";
*/
		$chm.= "d,cccccc," . $iIndice . ",-1,12|";
		$iIndice++;
		$chm.= "o,cc0033," . $iIndice . ",-1,8";
		$iIndice++;
		
		$chm="&chm=" . $chm;

		$chds="&chds=0," . $_EscalaMaxima;
		$chxr="&chxr=0,0," . $_EscalaMaxima . ",1";
		$chg="&chg=100,14.26,0,0";
		$sExtras=$chco . $chls . $chm . $chds . $chxr. $chg;
		
//		echo "<br />(PR,Manager,Autopercepción, AC) -> sExtras::" . $sExtras;
		return $sExtras;
	}
	
	function getTxtXImg($sTxt)
    {
        $aRetorno = array();
        $iTotal = 0;
        $aTxt = explode(" ", $sTxt);
        if (sizeof($aTxt) > 0){
            $iTotal=sizeof($aTxt);
			if ($iTotal > 0 ) {
				$col1=$iTotal/2;
				$col2=$col1;
				$resto=$iTotal%2;
				switch ($resto){
					case 1:
						$col1=intval(substr($col1,0,strpos($col1, '.')) + 1);
						$col2=intval($col2);
					   break;
					case 2:
						$col1=intval(substr($col2,0,strpos($col2, '.')) + 1);
						$col2=intval(substr($col2,0,strpos($col2, '.')) + 1);
						break;
				}
				$sCol1="";
				$sCol2="";
				for ($x=0; $x < $iTotal; $x++) {
					if ($x < $col1){
						$sCol1.= "+" . $aTxt[$x];
					}else{
						$sCol2.= "+" . $aTxt[$x];
					}
				}
                $aRetorno[0]=substr($sCol1, 1);
                $aRetorno[1]= substr($sCol2, 1);
			}
        }
        return $aRetorno;
    }
    
    //echo tiempoTranscurridoFechas("2017-04-19 16:30:00","2017-04-19 21:00:00");
    $aGrupos = array("Mentalidad empresarial", "Orientación y Satisfacción del Cliente", "Orientación a Objetivos", "Hablidades Sociales", "Valores Personales","Identificación con Audi");
    $aCompetencias = array();
    $aCompetencias[0][0] = "Visión de negocio";
    $aCompetencias[0][1] = "Análisis de Mercado";
    $aCompetencias[0][2] = "Gestión del Cambio";
    
    $aCompetencias[1][0] = "Análisis de Necesidades";
    $aCompetencias[1][1] = "Mentalidad de Servicio";
    $aCompetencias[1][2] = "Entusiasmo";
    $aCompetencias[1][3] = "Optimización de Procesos";
    
    $aCompetencias[2][0] = "Pensamiento Analítico";
    $aCompetencias[2][1] = "Orientación a Resultados";
    $aCompetencias[2][2] = "Innovación / Creatividad";

    $aCompetencias[3][0] = "Capacidad de Comunicación";
    $aCompetencias[3][1] = "Gestión de Conflictos";
    $aCompetencias[3][2] = "Colaboración";
    $aCompetencias[3][3] = "Capacidad de Influencia";
    
    $aCompetencias[4][0] = "Perseverancia";
    $aCompetencias[4][1] = "Honestidad";
    $aCompetencias[4][2] = "Automotivación";
    $aCompetencias[4][3] = "Capacidad de Aprendizaje";

    $aCompetencias[5][0] = "Embajador de Marca";
    $aCompetencias[5][1] = "Imagen Profesional";

    $aVMarca = array("Orientado al éxito", "Responsable", "Apasionado", "Proactivo", "Humano-Justo");
    $aAutopercepcion = array("Marca Audi", "Tecnología básica", "Modelos", "Equipamientos serie/opcional", "Sector y Mercado", "Procesos Ventas AC", "Comunicación", "Gestión vehículo ocasión", "Ventas especiales", "Fidelización cliente -CRM-", "Prospección", "Audi - Servicios financieros");
    $aProcesos = array("Contacto telefónico prospección", "Contacto telef. llamada Cliente", "Contacto telef. de fidelización", "Detección necesidades y conf.", "Negociación y cierre", "Caso de prospección");
    //Indicador si hay cargado Managers en la evaluación
    $bManager=true;	
    $bPerfilRequerido=true;
    
    $aListaGraf = array();
    for ($i=0; $i < sizeof($aGrupos); $i++) {
    	$aListaGraf[]=getTxtXImg($aGrupos[$i]);
    }
    $sL1="";
    $sL2="";
    $sIndice="";
    $sPuntuacionesM="";		//Resultados Manager
//Dinámico $iPR=$evaluatios->pr;    
    $iPR=5;					//perfil Requerido definido en la evaluación
    $sPuntuacionesPR="";	//Perfil Requerido
    $sPuntuacionesAP="";	//Resultados Autopercepción
    $sPuntuacionesAC="";	//Resultados AC
    for ($i=0; $i < sizeof($aListaGraf); $i++) {
    	$sL1.="|" . $aListaGraf[$i][0];
    	$sL2.="|" . $aListaGraf[$i][1];
    	$sIndice.= "," . $i;
    	if ($bPerfilRequerido){
    		$sPuntuacionesPR.= "," . $iPR;
    	}
    	if ($bManager){
    		$sPuntuacionesM.= "," . rand (1,7);
    	}
    	$sPuntuacionesAP.= "," . rand (1,7);
    	$sPuntuacionesAC.= "," . rand (1,7);
    }
    $sL1 = "1:|" . $sL1;
    $sL2 = "||2:|" . $sL2 . "|";
    
    $sLiterarGrafPerfilGeneral = $sL1 . $sL2;
   
    $sChdl="";
    //$sChdl="&chdl=" . getChdl($bManager,$bPerfilRequerido);
    $sExtras=getExtras($bManager,$bPerfilRequerido);
    //// Le sumamos 2
	$sIndice.= "," . $i;
	$i++;
	$sIndice.= "," . $i;
	////
    $sIndiceGrafPerfilGeneral = substr($sIndice, 1);

    if ($bPerfilRequerido){
    	$sPuntuacionesPerfilRGrafPerfilGeneral = "_" . $sPuntuacionesPR . ",_";
    }
    if ($bManager){
    	$sPuntuacionesManagerGrafPerfilGeneral = "_" . $sPuntuacionesM . ",_";
    }    
    $sPuntuacionesAPGrafPerfilGeneral = "_" . $sPuntuacionesAP . ",_";
    $sPuntuacionesACGrafPerfilGeneral = "_" . $sPuntuacionesAC . ",_";
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Informe {{ $user->name }} {{ $user->last_name }}</title>
<!-- Dinámico
	<link rel="stylesheet" href="{{ URL::asset('css/report.css') }}">
-->
    <link rel="stylesheet" href="css/report.css">
</head>
    
    <style>
   
        #intro{
            /*background-image: url({!! asset("/img/ilustracion.jpg") !!}");*/
            /*background-image: url("img/ilustracion.jpg");*/
            
            background-repeat:no-repeat;
            background-position: bottom right;
           
        }
    
    </style>
<body>
    
    <div id="intro">
    <?php 
    	include("cabecera.php");
    ?>
        <div id="intro-head">
        	<table width="100%" align="center">
        		<tr>
        			<td width="50%">&nbsp;</td>
        			<td class="intro-date" width="5%">Fecha:&nbsp;</td>
        			<!-- Commentario:: Primera fecha Desde del primer ejercicio de la lista de ejercicios -->
        			<td class="intro-date"><span>{{ $exercises[0]["from"] }}</span></td>
        		</tr>
        		<tr>
        			<td></td>
        			<td class="intro-area">Concesión:&nbsp;</td>
        			<td class="intro-area"><span>{{ $user->area }}</span></td>
        		</tr>
        		<tr>
        			<td></td>
<!-- Dinámico       			
					<td colspan="2"><hr class="hrHomeHead" style="background:{{ $clients->colorSeccion2 }} !important;"></td>
-->
        			<td colspan="2"><hr class="hrHomeHead" style="background:#cc0033 !important;"></td>
        		</tr>
        	</table>
        </div>
        <div id="intro-middle">{{ $evaluation->name }}</div>
        <div id="intro-foot">
        	<div class="intro-nombreP">{{ $user->name }} {{ $user->last_name }}</div>
<!-- Dinámico       	
			<div class="intro-line2"><hr class="hr2" style="background:{{ $clients->colorSeccion2 }} !important;"></div>
-->
            <div class="intro-line2"><hr class="hr2" style="background:#cc0033 !important;"></div>
<!-- Dinámico        
			<div class="intro-fotoP"><img src="img/{{ $user->image }}" alt="{{ $user->name }} {{ $user->last_name }}" title="{{ $user->name }} {{ $user->last_name }}" border="0" width="150"></div>
-->    
            <div class="intro-fotoP"><img src="img/user.png" alt="Nombre y apellidos" title="Nombre y apellidos" border="0" width="150"></div>
        </div>
        
        <div id="intro-foot-2">
<!-- Dinámico	
		@if ($bImagenPieHome)
			<img src="img/{{ $clients->colorSeccion2 }}" alt="" title="" border="0">
		@endif 
-->
        	<img src="img/pie_cliente.jpg" alt="imagen del cliente para el pie" title="imagen del cliente para el pie" border="0">
            <br />©Psic&oacute;logos Empresariales, 2016
        </div>
    </div>
    <div id="content">
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>Índice</h4>
            <hr>     
            <div class="temario-li">            
                <ol>
                    <li>Ficha Técnica</li>
                    <li>Descripción del Proceso</li>
                    <li>Agenda del Proceso</li>
                    <li>Competencias {{ $evaluation->name }}</li>
                    <li>Resultados del Participante</li>
                    <li>Conclusiones</li>
                </ol>
<!-- Dinámico	
De momento NO
		@if ($bCVParticipante)
                <ul>
                    <li>Anexo. Curriculum Vitae</li>
                </ul>
		@endif 

                <ul>
                    <li>Anexo. Curriculum Vitae</li>
                </ul>
                 
            </div>
--> 
<!-- Dinámico 
            <div id="confidencialidad" style="border-color:{{ $clients->colorSeccion2 }} !important;">
 -->
			<div id="confidencialidad" style="border-color:#cc0033 !important;">
                    <p>CONFIDENCIALIDAD: El acceso y uso de este informe está restringido a sus destinatarios, quienes se comprometen a mantener la debida confidencialidad sobre sus contenidos y a utilizarlo únicamente con la finalidad para la que ha sido emitido. Su uso debe enmarcarse según lo establecido en la Ley 15/1999 de 13 de Diciembre de Protección de Datos de Carácter Personal (LOPD).</p>
                    <p>Psicólogos Empresariales y Asociados S.A. se compromete y obliga a guardar y a hacer guardar a todos sus colaboradores y empleados la más estricta confidencialidad respecto a cualquier información -en cualquier formato-, recibida de los empleados del cliente y suministrada para la realización de este Proceso, incluyendo especialmente la situación personal de sus empleados. Psicólogos Empresariales y Asociados S.A. no se responsabiliza del inadecuado tratamiento de los datos de carácter personal.</p>                                
            </div>            
            
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>1 - Ficha Técnica</h4>
            <hr>     
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >            
                <tr>
<!-- Dinámico
					<td class="td-desc" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Descripción</td>
-->
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Descripción</td>
<!-- Dinámico                    
					<td class="td-line" style="border-bottom-color:{{ $clients->colorSeccion2 }} !important;">&nbsp;</td>
-->
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>{{ $evaluation->instructions }}</p>
                    </td>                    
                </tr>                
            </table>  
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
<!-- Dinámico
					<td class="td-desc" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Participante</td>
-->
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Participante</td>
<!-- Dinámico                    
					<td class="td-line" style="border-bottom-color:{{ $clients->colorSeccion2 }} !important;">&nbsp;</td>
-->
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>{{ $user->name }} {{ $user->last_name }}</p>
                    </td>                    
                </tr>
            </table>
            @if ($bManager)
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
<!-- Dinámico
					<td class="td-desc" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Manager</td>
-->
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Manager</td>
<!-- Dinámico                    
					<td class="td-line" style="border-bottom-color:{{ $clients->colorSeccion2 }} !important;">&nbsp;</td>
-->
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>{{ $userManager->name }} {{ $userManager->last_name }}</p>
                    </td>                    
                </tr>
            </table>
			@endif
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
<!-- Dinámico
					<td class="td-desc" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Consultor</td>
-->
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Consultor</td>
<!-- Dinámico                    
					<td class="td-line" style="border-bottom-color:{{ $clients->colorSeccion2 }} !important;">&nbsp;</td>
-->
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>{{ $evaluatorFirst->name }} {{ $evaluatorFirst->last_name }}</p>
                    </td>                    
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
<!-- Dinámico
					<td class="td-desc" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Observador/es</td>
-->
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Observador/es</td>
<!-- Dinámico                    
					<td class="td-line" style="border-bottom-color:{{ $clients->colorSeccion2 }} !important;">&nbsp;</td>
-->
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>
                        <textarea id="1-Observadores" name="1-Observadores" rows="3" cols="110">
			            	Campo Editor para el Consultor, por defecto recogerá el nombre del consultor secundario, pudiendo agregar los nombres de otros observadores cliente etc...
			                        	{{ $evaluatorSecond->name }} {{ $evaluatorSecond->last_name }}
			            </textarea>
                        </p>
                    </td>                    
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
<!-- Dinámico
					<td class="td-desc" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;"><input type="text" id="1-FTFecha" name="1-FTFecha" value="Fecha" /></td>
-->
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;"><input type="text" id="1-FTFecha" name="1-FTFecha" value="Fecha" /></td>
<!-- Dinámico                    
					<td class="td-line" style="border-bottom-color:{{ $clients->colorSeccion2 }} !important;">&nbsp;</td>
-->
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <!-- Primera fecha Desde del primer ejercicio de la lista de ejercicios -->
                        <p>
                        <textarea id="1-Observadores" name="1-Observadores" rows="3" cols="110">
			            	Campo Editor para el Consultor, por defecto recogerá Primera fecha Desde del primer ejercicio de la lista de ejercicios, pudiendo agregar el lugar de realización
			                        	{{ $exercises[0]["from"] }}, L'Hospitalet de l' Infant Tarragona
			            </textarea>
                        
                        
                        </p>
                    </td>                    
                </tr>
            </table>
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>2 - Descripción del Proceso</h4>
            <hr>
            <p class="desc-val">
	            <textarea id="2-DescProceso" name="2-DescProceso" rows="6" cols="110">
	            	Campo Editor para el Consultor, por defecto recogerá la descripción de la evaluación, pero puede cambiarse.
	            	{{ $evaluation->description }}
	            </textarea>
            </p>
<!-- Dinámico            
            <div class="sec-block" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Ejercicios</div>
-->            
            <div class="sec-block" style="background-color: #cc0033 !important;color: #fff !important;">Ejercicios</div>
            @foreach ($exercises as $exercise)
                <div class="block-text">
                    <p class="desc-val">
                        <strong>{!! $exercise->name !!}</strong>
                        <br /><br />
                        {!! $exercise->description !!}
                    </p>
                </div>
            @endforeach
            <br />
            <p class="desc-val">
            	<textarea id="2-DescFinalProceso" name="2-DescFinalProceso" rows="10" cols="110">
	            	Campo Editor para el Consultor, por defecto recogerá el siguiente texto::
	            	
	            	Una vez finalizadas las pruebas, concretamente el día siguiente se realizará una entrevista de una hora de duración para presentar los resultados del día anterior.
	            </textarea>
            </p>
            
        </div><!-- fin página -->
                       
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>3 - Agenda del Proceso</h4>
            <hr>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >            
                <tr>
<!-- Dinámico            
                    <td class="td-pru-desc1" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Pruebas</td>
                    <td class="td-pru-desc2" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">&nbsp;</td>
                    <td class="td-pru-desc3" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Duración</td>
-->            
                
                    <td class="td-pru-desc1" style="background-color: #cc0033 !important;color: #fff !important;">Pruebas</td>
                    <td class="td-pru-desc2" style="background-color: #cc0033 !important;color: #fff !important;">&nbsp;</td>
                    <td class="td-pru-desc3" style="background-color: #cc0033 !important;color: #fff !important;">Duración</td>
                                                            
                </tr>
            @foreach ($exercises as $exercise)
                <tr>
                    <td><p style="color:#333;font-weight: bold;">{!! $exercise->name !!}</p></td>
                    <td>&nbsp;</td>
                    <td>
                    	<!-- {!! $exercise->from !!} {!! $exercise->from_hour !!} {!! $exercise->to !!} {!! $exercise->to_hour !!} -->
                    	<?php echo tiempoTranscurridoFechas("2017-04-19 16:30:00","2017-04-19 16:40:00");?>
                    </td>
                    
                </tr>
            @endforeach    
                <tr>
                    <td>&nbsp;</td>
                    <td style="color:#333;font-weight: bold;">Duración total</td>
                    <td><?php echo tiempoTranscurridoFechas("2017-04-19 16:30:00","2017-04-19 21:00:00");?></td>                    
                </tr>
            </table>  

            
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>4 - Competencias</h4>
            <hr>
            <p class="desc-val">
	            <textarea id="4-DescCompetenciasP" name="4-DescCompetenciasP" rows="6" cols="110">
	            	Campo Editor para el Consultor, para comentar esta sección si fuese necesario, por defecto VACIO.
	            </textarea>
            </p>
<!-- Dinámico
            <div class="sec-block" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Nivel de desempeño / profesionalidad</div>
-->
            <div class="sec-block" style="background-color: #cc0033 !important;color: #fff !important;">Nivel de desempeño / profesionalidad</div>
<!-- Dinámico
            <div class="ndp" style="color: {{ $clients->colorSeccion2 }} !important;border-color: {{ $clients->colorSeccion2 }} !important;">
-->            
            <div class="ndp" style="color: #cc0033 !important;border-color: #cc0033 !important;">
<!-- Dinámico
              	$iMargen=0;
                @foreach ($GrpCompetitions as $GrpCompetition)
                    <div class="galleta" style="border-color: {{ $clients->colorSeccion2 }} !important;">{!! $GrpCompetition->name !!}</div>
                    $iMargen+=25;
                @endforeach
				<span class="cptxt" style="bottom:<?php echo $iMargen;?>px !important;">Competencias Personales</span>
-->
              <?php
              $iMargen=0; 
              for ($i=0; $i < sizeof($aGrupos); $i++) {
              	echo '<div class="galleta" style="border-color: #cc0033 !important;">' . $aGrupos[$i] . '</div>';
              	$iMargen+=25;
              }
              ?>       
                <span class="cptxt" style="bottom:<?php echo $iMargen;?>px !important;">Competencias Personales</span>
            </div>
            
        </div><!-- fin página -->
 
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>5 - Resultado General: {{ $user->name }} {{ $user->last_name }}</h4>
            <hr>
            <div id="intro-middle" align="center" style="margin-top: 100px !important;">
            <!-- Si seccionCP o -->
	            <table border="0" cellspacing="0" cellpadding="0" width="40%" >
	        
            <?php
            	$seccionCP="Si está vacio, no se pinta el bloque en la generación del PDF";
				if (!empty($seccionCP)){
	        ?>            
	                <tr>
	                    <td class="td-desc" style="background-color: #ccc;color:#000;text-align:center;width:250px;white-space: nowrap;">
		                    <input type="text" id="seccionCP" name="seccionCP" value="Competencias Personales" /> 
	                    </td>
	                    <td class="td-line" style="text-align:center;height: 30px;">
		                    <input type="text" id="estadoCP" name="estadoCP" value="APTO" />
	                    </td>                                        
	                </tr>
	                <tr>
	                    <td>&nbsp;</td>
	                    <td>&nbsp;</td>                    
	                </tr> 
			<?php
				}
				
				$seccionCT="Si está vacio, no se pinta el bloque en la generación del PDF";
				if (!empty($seccionCT)){
			?>
	                <tr>
	                    <td class="td-desc" style="background-color: #ccc;color:#000;text-align:center;width:250px;white-space: nowrap;">
	                        <input type="text" id="seccionCT" name="seccionCT" value="Competencias Técnicas" />
	                    </td>
	                    <td class="td-line" style="text-align:center;height: 30px;">            
	                    	<input type="text" id="estadoCT" name="estadoCT" value="APTO" />
	                    </td>                            
	                </tr>
	                <tr>
	                    <td>&nbsp;</td>
	                    <td>&nbsp;</td>                    
	                </tr> 
			<?php
				} 
				
				$seccionPV="Si está vacio, no se pinta el bloque en la generación del PDF";
				if (!empty($seccionPV)){
					?>
					                <tr>
					                    <td class="td-desc" style="background-color: #ccc;color:#000;text-align:center;width:250px;white-space: nowrap;">
					                        <input type="text" id="seccionPV" name="seccionPV" value="Proceso de Ventas Audi" />
					                    </td>
					                    <td class="td-line" style="text-align:center;height: 30px;">            
					                    	<input type="text" id="estadoPV" name="estadoPV" value="APTO" />
					                    </td>                            
					                </tr>
					                <tr>
					                    <td>&nbsp;</td>
					                    <td>&nbsp;</td>                    
					                </tr> 
							<?php
								} 
								
			?>
			<!-- Este bloque de resultado siempre se pintará y el consultor podrá modificar, igual que ne los anteriores,
			 el literal del título y poner en estado lo que se haya consencuado con el cliente del tipo ACTO, VÁLIDO, ACEPTADO etc.
				**** -> Por defecto pondrá APTO 
			-->	                
	                <tr>
<!-- Dinámico
            			<td class="td-desc" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;text-align:center;width:250px;white-space: nowrap;">
-->            
	                    <td class="td-desc" style="background-color:#cc0033 !important;color: #fff !important;text-align:center;width:250px;white-space: nowrap;">
	                    	<input type="text" id="seccionCPT" name="seccionCPT" value="Resultado" />
	                    </td>
	                    <td class="td-line" style="text-align:center;border-bottom-color:#cc0033 !important;height: 30px;">
	                    	<input type="text" id="estadoCPT" name="estadoCPT" value="APTO" />
	                    </td>                                        
	                </tr>
	                <tr>
	                    <td>&nbsp;</td>
	                    <td>&nbsp;</td>                    
	                </tr> 
	            </table>
	             
	            <p class="desc-val" style="font-size:14px;font-weight: normal;">
		            <textarea id="DescConsultorResultadoGeneral" name="DescConsultorResultadoGeneral" rows="20" cols="110">Campo Editor para que el Consultor ponga la explicación del resultado.</textarea>
				</p> 
	            
            </div>
     
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>5 - Resultado General: {{ $user->name }} {{ $user->last_name }}</h4>
            <hr>
<!-- Dinámico
   			<div class="sec-block" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;"><input type="text" id="PerfilGeneralSeccCP" name="PerfilGeneralSeccCP" value="Competencias Personales" /></div>
-->            
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;"><input type="text" id="PerfilGeneralSeccCP" name="PerfilGeneralSeccCP" value="Competencias Personales" /></div>
            <div class="block-text">
                <p class="desc-val">
                
                <table border="0" class="tbLiterales" cellspacing="0" cellpadding="0" width="<?php echo $_grafW;?>" >
                	<?php echo getTRLiterales(true, true);?>
                	<tr>
                		<td colspan="10"><img title="Competencias Personales" alt="Competencias Personales" src="https://chart.googleapis.com/chart?cht=lxy&chs=<?php echo $_grafW;?>x<?php echo $_grafH;?>&chxl=<?php echo $sLiterarGrafPerfilGeneral;?><?php echo $sChdl;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafPerfilGeneral;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafPerfilGeneral)) ? $sPuntuacionesPerfilRGrafPerfilGeneral . "|-1|" : "";?><?php echo (!empty($sPuntuacionesManagerGrafPerfilGeneral)) ? $sPuntuacionesManagerGrafPerfilGeneral . "|-1|" : "";?><?php echo $sPuntuacionesAPGrafPerfilGeneral;?>|-1|<?php echo $sPuntuacionesACGrafPerfilGeneral;?>&chco=<?php echo $sExtras;?>&chdls=333333,12&chdlp=t|l"></td>
                	</tr>
                </table>
                      
                </p>
            </div>
<?php 
			$bManager=true;
			$bPerfilRequerido=true;
            	$sL1="";
            	$sL2="";
            	$sIndice="";
            	$iIndice=0;
            	$sPuntuacionesM="";		////Resultados Manager
//Dinámico $iPR=$evaluatios->pr;    
			    $iPR=5;					//perfil Requerido definido en la evaluación
			    $sPuntuacionesPR="";	//Perfil Requerido
            	$sPuntuacionesAP="";	//Resultados Autopercepción
            	$sPuntuacionesAC="";	//Resultados AC
			$sPiePerfilDetallado="";
			$sTxtToImg = "";
            for ($i=0; $i < sizeof($aGrupos); $i++) {
            	$aCompGrp = $aCompetencias[$i];
            	$sPiePerfilDetallado.="<td align='center' colspan='" . sizeof($aCompGrp) . "' style='border: 1px solid;'><strong>" . str_replace(" ", "<br />", $aGrupos[$i]) . "</strong></td>";
            	$aListaGraf = array();
            	for ($x=0; $x < sizeof($aCompGrp); $x++) {
            		$aListaGraf[]=getTxtXImg($aCompGrp[$x]);
            		$sTxtToImg.='<td align="center" style="width: 40px;padding-bottom:10px;" valign="top"><img src="' . $_urlTxtToGraf . '?s=' . $aCompGrp[$x] . '"></td>';
            	}
            	
            	for ($x=0; $x < sizeof($aListaGraf); $x++) {
            		//$sL1.="|" . $aListaGraf[$x][0];
            		//$sL2.="|" . $aListaGraf[$x][1];
            		$sL1.="|";
            		$sL2.="|";
            		$sIndice.= "," . $iIndice;
					$iIndice++;
					
            	    if ($bPerfilRequerido){
            			$sPuntuacionesPR.= "," . $iPR;
            		}
            		if ($bManager){
            			$sPuntuacionesM.= "," . rand (1,7);
            		}            		
            		$sPuntuacionesAP.= "," . rand (1,7);
            		$sPuntuacionesAC.= "," . rand (1,7);
            	}
			}
			    $sL1 = "1:|" . $sL1;
			    $sL2 = "||2:|" . $sL2 . "|";
						
            	//$sLiterarGrafCompGrp = "&chxl=" . $sL1 . $sL2;
            	$sLiterarGrafCompGrp = "&chxl=" . $sL1;
            	$chxt = "&chxt=y";
			    //// Le sumamos 2
				$sIndice.= "," . $iIndice;
				$iIndice++;
				$sIndice.= "," . $iIndice;
				////
            	
            	$sIndiceGrafCompGrp = substr($sIndice, 1);
			            	
                if ($bPerfilRequerido){
            		$sPuntuacionesPerfilRGrafCompGrp = "_" . $sPuntuacionesPR . ",_";
            	}
            	if ($bManager){
            		$sPuntuacionesManagerGrafCompGrp = "_" . $sPuntuacionesM . ",_";
            	}            	
            	$sPuntuacionesAPGrafCompGrp = "_" . $sPuntuacionesAP . ",_";
            	$sPuntuacionesACGrafCompGrp = "_" . $sPuntuacionesAC . ",_";
            	
            $sMana = (!empty($sPuntuacionesPerfilRGrafCompGrp)) ? $sPuntuacionesPerfilRGrafCompGrp . "|-1|" : "";
            $sMana .= (!empty($sPuntuacionesManagerGrafCompGrp)) ? $sPuntuacionesManagerGrafCompGrp . "|-1|" : "";
            
?>
<!-- Dinámico
   			<div class="sec-block" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;"><input type="text" id="PerfilGeneralSeccCP" name="PerfilGeneralSeccCP" value="Competencias Personales, Perfil Detallado" /></div>
   			Este es el gráfico para investigar 
-->            
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;"><input type="text" id="PerfilGeneralSeccCP" name="PerfilGeneralSeccCP" value="Perfil Detallado, Competencias Personales" /></div>
            <div class="block-text">
                <p class="desc-val">
	                <table border="0" class="tbLiterales" cellspacing="0" cellpadding="0" width="<?php echo $_grafW-40; //de izquierda y derecha ?>" >
	                	<?php echo getTRLiterales(true, true);?>
	                	<tr>
	                		<td colspan="10">
		                		<?php echo '<img title="Competencias Personales, Perfil Detallado" alt="Competencias Personales, Perfil Detallado" src="https://chart.googleapis.com/chart?cht=lxy&chs=' . $_grafW . 'x' . $_grafH . $sLiterarGrafCompGrp . '' . $sChdl . $chxt . '&chd=t:' . $sIndiceGrafCompGrp . '|' . $sMana .  $sPuntuacionesAPGrafCompGrp . '|-1|' . $sPuntuacionesACGrafCompGrp . '&chco=' . $sExtras . '&chdls=333333,12&chdlp=t|l">';?>
	                		</td>
	                	</tr>
	                </table>
	                <table border="0" class="tbLiterales" style="margin: 0 auto !important;" cellspacing="0" cellpadding="0" width="<?php echo $_grafW-40; //de izquierda y derecha ?>" >
	                	<tr>
						<?php 
                      		echo $sTxtToImg;
                      	?>
	                	</tr>
                      	<tr>
                      	<?php 
                      		echo $sPiePerfilDetallado;
                      	?>
                    	</tr>
					</table>
                </p>
            </div>
            <div class="block-text">
                <p class="desc-val">
                    <textarea id="PerfilGeneralDescConsultor" name="PerfilGeneralDescConsultor" rows="20" cols="110">Campo Editor para el Consultor</textarea>
                </p>
            </div>
            
        </div><!-- fin página -->
        
<div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
   		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>5 - Valores de Marca: {{ $user->name }} {{ $user->last_name }}</h4>
            <hr>
<!-- Dinámico
   			<div class="sec-block" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Valores de Marca</div>
-->            
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">Valores de Marca</div>
            <div class="block-text">
                <p class="desc-val">
			<?php 
				$aListaGraf = array();
				for ($i=0; $i < sizeof($aVMarca); $i++) {
					$aListaGraf[]=getTxtXImg($aVMarca[$i]);
				}
				$sL1="";
				$sL2="";
				$sIndice="";
				$sPuntuacionesM="";		////Resultados Manager
//Dínamico $iPR=$evaluatios->pr;    
			    $iPR=5;					//perfil Requerido definido en la evaluación
			    $sPuntuacionesPR="";	//Perfil Requerido
				$sPuntuacionesAP="";	//Resultados Autopercepción
				$sPuntuacionesAC="";	//Resultados AC
				for ($i=0; $i < sizeof($aListaGraf); $i++) {
					$sL1.="|" . $aListaGraf[$i][0];
					$sL2.="|" . $aListaGraf[$i][1];
					$sIndice.= "," . $i;
					$sPuntuacionesM.= "," . rand (1,7);
					$sPuntuacionesPR.= "," . $iPR;
					$sPuntuacionesAP.= "," . rand (1,7);
					$sPuntuacionesAC.= "," . rand (1,7);
				}
			    $sL1 = "1:|" . $sL1;
			    $sL2 = "||2:|" . $sL2 . "|";
							
				$sLiterarGrafVMarca = $sL1 . $sL2;
			    //// Le sumamos 2
				$sIndice.= "," . $i;
				$i++;
				$sIndice.= "," . $i;
				////
				
				$sIndiceGrafVMarca = substr($sIndice, 1);
				
				if ($bPerfilRequerido){
					$sPuntuacionesPerfilRGrafVMarca = "_" . $sPuntuacionesPR . ",_";
				}
				if ($bManager){
					$sPuntuacionesManagerGrafVMarca = "_" . $sPuntuacionesM . ",_";
				}				
				$sPuntuacionesAPGrafVMarca = "_" . $sPuntuacionesAP . ",_";
				$sPuntuacionesACGrafVMarca = "_" . $sPuntuacionesAC . ",_";
			?>
	                <table border="0" class="tbLiterales" cellspacing="0" cellpadding="0" width="<?php echo $_grafW;?>" >
	                	<?php echo getTRLiterales(true, true);?>
	                	<tr>
	                		<td colspan="10">
		                		<img title="Valores de Marca" alt="Valores de Marca" src="https://chart.googleapis.com/chart?cht=lxy&chs=<?php echo $_grafW;?>x<?php echo $_grafH;?>&chxl=<?php echo $sLiterarGrafVMarca;?><?php echo $sChdl;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafVMarca;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafVMarca)) ? $sPuntuacionesPerfilRGrafVMarca . "|-1|" : "";?><?php echo (!empty($sPuntuacionesManagerGrafVMarca)) ? $sPuntuacionesManagerGrafVMarca . "|-1|" : "";?><?php echo $sPuntuacionesAPGrafVMarca;?>|-1|<?php echo $sPuntuacionesACGrafVMarca;?>&chco=<?php echo $sExtras;?>&chdls=333333,12&chdlp=t|l">
	                		</td>
	                	</tr>
	                </table>
                      
                </p>
            </div>
            <div class="block-text">
                <p class="desc-val">
                    <textarea id="ValoresMarcaDescConsultor" name="ValoresMarcaDescConsultor" rows="20" cols="110">Campo Editor para el Consultor comentarios para Valores de Marca
                    
CALCULOS::    
                Orientación al Éxito: Visión de negocio + Visión estratégica + Pensamiento analítico + Orientación a resultados + Dirección de personas
                Responsable: Análisis de mercado + Análisis de necesidades + Capacidad de comunicación + Imagen profesional
                Apasionado: Entusiasmo + Capacidad de comunicación + Capacidad de influencia + Perseverancia + Embajador de la Marca
                Proactivo: Gestión del cambio + Optimización de procesos + Innovación/creatividad + Capacidad de aprendizaje
                Humano -Justo: Mentalidad de servicio + Colaboración + Desarrollo de personas + Honestidad
                    </textarea>
                </p>
                
            </div>
            
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>5 - Resultado General: {{ $user->name }} {{ $user->last_name }}</h4>
            <hr>
<!-- Dinámico
   			<div class="sec-block" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;"><input type="text" id="PerfilGeneralSeccCT" name="PerfilGeneralSeccCT" value="Competencias Técnicas" /></div>
-->            
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;"><input type="text" id="PerfilGeneralSeccCT" name="PerfilGeneralSeccCT" value="Competencias Técnicas" /></div>
            <div class="block-text">
                <p class="desc-val">
	                <?php 
	                //
					$sTxtToImg = "";
	                $aListaGraf = array();
	                for ($i=0; $i < sizeof($aAutopercepcion); $i++) {
	                	$aListaGraf[]=getTxtXImg($aAutopercepcion[$i]);
	                	$sTxtToImg.='<td align="center" style="width: 40px;padding-bottom:10px;" valign="top"><img src="' . $_urlTxtToGraf . '?s=' . $aAutopercepcion[$i] . '"></td>';
	                }
	                $sL1="";
	                $sL2="";
	                $sIndice="";
	                $sPuntuacionesM="";		////Resultados Manager
//Dinámico $iPR=$evaluatios->pr;    
				    $iPR=5;					//perfil Requerido definido en la evaluación
				    $sPuntuacionesPR="";	//Perfil Requerido
	                $sPuntuacionesAP="";	//Resultados Autopercepción
	                $sPuntuacionesAC="";	//Resultados AC
	                for ($i=0; $i < sizeof($aListaGraf); $i++) {
	                	//$sL1.="|" . $aListaGraf[$i][0];
	                	//$sL2.="|" . $aListaGraf[$i][1];
	                	$sL1.="|";
            			$sL2.="|";
	                	
	                	$sIndice.= "," . $i;

	                	if ($bPerfilRequerido){
	                		$sPuntuacionesPR.= "," . $iPR;
	                	}
	                	if ($bManager){
	                		$sPuntuacionesM.= "," . rand (1,7);
	                	}	                	
	                	$sPuntuacionesAP.= "," . rand (1,7);
	                	$sPuntuacionesAC.= "," . rand (1,7);
	                }
				    $sL1 = "1:|" . $sL1;
				    $sL2 = "||2:|" . $sL2 . "|";
					                
	                $sLiterarGrafPerfilGeneralCT = "&chxl=" . $sL1 . $sL2;
	            	$sLiterarGrafPerfilGeneralCT = "&chxl=" . $sL1;
            		$chxt = "&chxt=y";
	                
	                $sChdl="";
	                //$sChdl="&chdl=" . getChdl($bManager,$bPerfilRequerido);
	                $sExtras=getExtras($bManager,$bPerfilRequerido);
	                 
				    //// Le sumamos 2
					$sIndice.= "," . $i;
					$i++;
					$sIndice.= "," . $i;
					////	                 
	                $sIndiceGrafPerfilGeneralCT = substr($sIndice, 1);

	                if ($bPerfilRequerido){
	                	$sPuntuacionesPerfilRGrafPerfilGeneralCT = "_" . $sPuntuacionesPR . ",_";
	                }
	                if ($bManager){
	                	$sPuntuacionesManagerGrafPerfilGeneralCT = "_" . $sPuntuacionesM . ",_";
	                }	                
	                $sPuntuacionesAPGrafPerfilGeneralCT = "_" . $sPuntuacionesAP . ",_";
	                $sPuntuacionesACGrafPerfilGeneralCT = "_" . $sPuntuacionesAC . ",_";
	                 
	                 
	                //
	                
	                ?>
	                <table border="0" class="tbLiterales" cellspacing="0" cellpadding="0" width="<?php echo $_grafW-40; //de izquierda y derecha ?>" >
	                	<?php echo getTRLiterales(true, true);?>
	                	<tr>
	                		<td colspan="10">
		                		<img title="Competencias Técnicas" alt="Competencias Técnicas" src="https://chart.googleapis.com/chart?cht=lxy&chs=<?php echo $_grafW;?>x<?php echo $_grafH;?>&chxl=<?php echo $sLiterarGrafPerfilGeneralCT;?><?php echo $sChdl;?><?php echo $chxt;?>&chd=t:<?php echo $sIndiceGrafPerfilGeneralCT;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafPerfilGeneralCT)) ? $sPuntuacionesPerfilRGrafPerfilGeneralCT . "|-1|" : "";?><?php echo (!empty($sPuntuacionesManagerGrafPerfilGeneralCT)) ? $sPuntuacionesManagerGrafPerfilGeneralCT . "|-1|" : "";?><?php echo $sPuntuacionesAPGrafPerfilGeneralCT;?>|-1|<?php echo $sPuntuacionesACGrafPerfilGeneralCT;?>&chco=<?php echo $sExtras;?>&chdls=333333,12&chdlp=t|l">
								<table border="0" class="tbLiterales" style="margin: 0 auto !important;" cellspacing="0" cellpadding="0" width="<?php echo $_grafW-40; //de izquierda y derecha ?>" >
				                	<tr>
									<?php 
			                      		echo $sTxtToImg;
			                      	?>
				                	</tr>
								</table>
	                		</td>
	                	</tr>
	                </table>

                </p>
            </div>
<!-- Dinámico
   			<div class="sec-block" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;"><input type="text" id="PerfilGeneralSeccPV" name="PerfilGeneralSeccPV" value="Procesos de Venta" /></div>
-->            
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;"><input type="text" id="PerfilGeneralSeccPV" name="PerfilGeneralSeccPV" value="Procesos de Venta" /></div>
            <div class="block-text">
                <p class="desc-val">
	                <?php 
	                //
	                $aListaGraf = array();
	                for ($i=0; $i < sizeof($aProcesos); $i++) {
	                	$aListaGraf[]=getTxtXImg($aProcesos[$i]);
	                }
	                $sL1="";
	                $sL2="";
	                $sIndice="";
	                $sPuntuacionesM="";		////Resultados Manager
//Dinámico $iPR=$evaluatios->pr;    
				    $iPR=5;					//perfil Requerido definido en la evaluación
				    $sPuntuacionesPR="";	//Perfil Requerido
	                $sPuntuacionesPROC="";	//Resultados Procesos
	                for ($i=0; $i < sizeof($aListaGraf); $i++) {
	                	$sL1.="|" . $aListaGraf[$i][0];
	                	$sL2.="|" . $aListaGraf[$i][1];
	                	$sIndice.= "," . $i;
	                	if ($bPerfilRequerido){
	                		$sPuntuacionesPR.= "," . $iPR;
	                	}
	                	$sPuntuacionesPROC.= "," . rand (1,7);
	                }
				    $sL1 = "1:|" . $sL1;
				    $sL2 = "||2:|" . $sL2 . "|";
					                
	                $sLiterarGrafPerfilGeneralPRO = $sL1 . $sL2;
	                
	                $sChdlPROC="";
	                //$sChdlPROC="&chdl=" . getChdlPROC($bPerfilRequerido);
	                $sExtrasPROC=getExtrasPROC($bPerfilRequerido);
	                 
				    //// Le sumamos 2
					$sIndice.= "," . $i;
					$i++;
					$sIndice.= "," . $i;
					////	                 
	                $sIndiceGrafPerfilGeneralPRO = substr($sIndice, 1);

	                if ($bPerfilRequerido){
	                	$sPuntuacionesPerfilRGrafPerfilGeneralPRO = "_" . $sPuntuacionesPR . ",_";
	                }
	                
	                $sPuntuacionesPROCGrafPerfilGeneralPROC = "_" . $sPuntuacionesPROC . ",_";
	                ?>
	                <table border="0" class="tbLiterales" cellspacing="0" cellpadding="0" width="<?php echo $_grafW;?>" >
	                	<?php echo getTRLiteralesPROC(true);?>
	                	<tr>
	                		<td colspan="10">
		                		<img title="Resultados Procesos" alt="Resultados Procesos" src="https://chart.googleapis.com/chart?cht=lxy&chs=<?php echo $_grafW;?>x<?php echo $_grafH;?>&chxl=<?php echo $sLiterarGrafPerfilGeneralPRO;?><?php echo $sChdlPROC;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafPerfilGeneralPRO;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafPerfilGeneralPRO)) ? $sPuntuacionesPerfilRGrafPerfilGeneralPRO . "|-1|" : "";?><?php echo $sPuntuacionesPROCGrafPerfilGeneralPROC;?>&chco=<?php echo $sExtrasPROC;?>&chdls=333333,12&chdlp=t|l">
	                		</td>
	                	</tr>
	                </table>
                    
                </p>
            </div>

            <div class="block-text">
                <p class="desc-val">
                    <textarea id="PerfilGeneralCTDescConsultor" name="PerfilGeneralCTDescConsultor" rows="20" cols="110">Campo Editor para el Consultor</textarea>
                </p>
            </div>
            
        </div><!-- fin página -->
        
		<?php 
			
			$sPaginaInicio='
            		<div style="page-break-before: always;">
            		<!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
			';
	    	$sPaginaInicio.= get_include_contents('cabecera.php');
	    	$sPaginaInicio.='
            				<h4>5 - Resultados en detalle: {{ $user->name }} {{ $user->last_name }}</h4>
            				<hr>
            		';
			$sPaginaCDG="";
			$sPaginaFin='</div><!-- fin página -->';
            for ($i=0; $i < sizeof($aGrupos); $i++) {
            	$aCompGrp = $aCompetencias[$i];
            	$aListaGraf = array();
            	for ($x=0; $x < sizeof($aCompGrp); $x++) {
            		$aListaGraf[]=getTxtXImg($aCompGrp[$x]);
            	}
            	$sL1="";
            	$sL2="";
            	$sIndice="";
            	$sPuntuacionesM="";		////Resultados Manager
//Dinámico $iPR=$evaluatios->pr;    
			    $iPR=5;					//perfil Requerido definido en la evaluación
			    $sPuntuacionesPR="";	//Perfil Requerido
            	$sPuntuacionesAP="";	//Resultados Autopercepción
            	$sPuntuacionesAC="";	//Resultados AC
            	for ($x=0; $x < sizeof($aListaGraf); $x++) {
            		$sL1.="|" . $aListaGraf[$x][0];
            		$sL2.="|" . $aListaGraf[$x][1];
            		$sIndice.= "," . $x;

            	    if ($bPerfilRequerido){
            			$sPuntuacionesPR.= "," . $iPR;
            		}
            		if ($bManager){
            			$sPuntuacionesM.= "," . rand (1,7);
            		}            		
            		$sPuntuacionesAP.= "," . rand (1,7);
            		$sPuntuacionesAC.= "," . rand (1,7);
            	}
			    $sL1 = "1:|" . $sL1;
			    $sL2 = "||2:|" . $sL2 . "|";
			            	
            	$sLiterarGrafCompGrp = $sL1 . $sL2;
            	//// Le sumamos 2
				$sIndice.= "," . $x;
				$x++;
				$sIndice.= "," . $x;
				////
            	$sIndiceGrafCompGrp = substr($sIndice, 1);

                if ($bPerfilRequerido){
            		$sPuntuacionesPerfilRGrafCompGrp = "_" . $sPuntuacionesPR . ",_";
            	}
            	if ($bManager){
            		$sPuntuacionesManagerGrafCompGrp = "_" . $sPuntuacionesM . ",_";
            	}            	
            	$sPuntuacionesAPGrafCompGrp = "_" . $sPuntuacionesAP . ",_";
            	$sPuntuacionesACGrafCompGrp = "_" . $sPuntuacionesAC . ",_";
            	
            	
            	if ($i%2 == 0 && $i > 0){
            		echo $sPaginaInicio . $sPaginaCDG . $sPaginaFin;
            		$sPaginaCDG="";
            	}
            	$sMana = (!empty($sPuntuacionesPerfilRGrafCompGrp)) ? $sPuntuacionesPerfilRGrafCompGrp . "|-1|" : "";
            	$sMana .= (!empty($sPuntuacionesManagerGrafCompGrp)) ? $sPuntuacionesManagerGrafCompGrp . "|-1|" : "";
            	
            	
		        $sPaginaCDG.='
<!-- Dinámico
			   			<div class="sec-block" style="background-color:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">' . $aGrupos[$i] . '</div>
-->            
			            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">' . $aGrupos[$i] . '</div>
			            <div class="block-text">
			                <p class="desc-val">
				                <table border="0" class="tbLiterales" cellspacing="0" cellpadding="0" width="' . $_grafW . '" >
				                	' .  getTRLiterales(true, true) . '
				                	<tr>
				                		<td colspan="10">
				                			<img title="Resultados en detalle del grupo ' . $aGrupos[$i] . '" alt="Resultados en detalle del grupo ' . $aGrupos[$i] . '" src="https://chart.googleapis.com/chart?cht=lxy&chs=' . $_grafW . 'x' . $_grafH . '&chxl=' . $sLiterarGrafCompGrp . '' . $sChdl . '&chxt=y,x,x&chd=t:' . $sIndiceGrafCompGrp . '|' . $sMana .  $sPuntuacionesAPGrafCompGrp . '|-1|' . $sPuntuacionesACGrafCompGrp . '&chco=' . $sExtras . '&chdls=333333,12&chdlp=t|l">
				                		</td>
				                	</tr>
				                </table>
							                
			                      
			                </p>
			            </div>
			            <div class="block-text">
			                <p class="desc-val">
			                <textarea id="CDG' . $i . 'DescConsultor" name="CDG' . $i . 'DescConsultor" rows="20" cols="110" >Campo Editor para el Consultor para describir los Resultados en detalle del grupo ' . $aGrupos[$i] . '</textarea>
			                </p>
			            </div>
		    		';
            }
            if (!empty($sPaginaCDG)){
            	echo $sPaginaInicio . $sPaginaCDG . $sPaginaFin;
            	$sPaginaCDG="";
            }
		?>

        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>5 - Resultados Numéricos: {{ $user->name }} {{ $user->last_name }}</h4>
            <hr>
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="90%" >
               <tr>
                    <td class="td-rn-desc1"><p>&nbsp;</p></td>
				<?php
				//Hay que calcular el colespan, segun los ejercicios del proceso y si tiene o no Manager
				$aEjercicios = array("Auto Percepción", "Entrevista", "El caso", "Roleplay 1");
				$iColspanTD=1;
				 
				if ($bManager){
					$iColspanTD++;
				?>
                    <td class="td-rn-desc2"><strong>Manager</strong></td>
                <?php 
				}
				
				for ($i=0; $i < sizeof($aEjercicios); $i++) {
					$iColspanTD++;
					//<td class="td-rn-desc2">{!! $exercise->name !!}</td>;
				?>
					<td class="td-rn-desc2"><strong><?php echo $aEjercicios[$i];?></strong></td>
				<?php 
				}
                ?>
            	<?php 
            	$iColspanTD++;
            	//echo $iColspanTD; 
            	?>
                    <td class="td-rn-desc2"><strong>Total</strong></td>
                </tr>            
                <tr>
<!-- Dinámico
					<td class="tdLineDN-H" style="background:{{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;" colspan="<?php echo $iColspanTD;?>">Competencias Personales</td>
-->
                    <td class="tdLineDN-H" style="background:#cc0033 !important;color: #fff !important;" colspan="<?php echo $iColspanTD;?>">Competencias Personales</td>
                </tr>
			<?php
			
			for ($i=0; $i < sizeof($aGrupos); $i++) {
			?>
    		    <tr>
                    <td class="tdLineDN-Gr" colspan="<?php echo $iColspanTD;?>"><?php echo $aGrupos[$i];?></td>
                </tr>
			<?php
				
				$aCompGrp = $aCompetencias[$i];
				$aListaTabla = array();
				for ($x=0; $x < sizeof($aCompGrp); $x++) {
			?>
				<tr>
					<td><span class="indent"><?php echo $aCompGrp[$x];?></span></td>
			<?php
				if ($bManager){
			?>
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
			<?php 
				}
				//Calcular las medias x ejercicio
				?>
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
					<td class="td-rn-desc2">&nbsp;</td>
					<td class="td-rn-desc3"><?php echo rand(1, 100);?>%</td>
				</tr>
		    	<?php 
				}
				
			}
            ?>    
                <tr>
                    <td>&nbsp;</td>
			<?php
			$iResta=1;
			if ($bManager){
				$iResta++;
				echo '<td>&nbsp;</td>';
			}
			
			for ($j=0; $j < $iColspanTD-$iResta; $j++) {
			
				echo '<td>&nbsp;</td>';
			}
			?>
                </tr>
                <tr>
                	<td>&nbsp;</td>
			<?php
			$iResta=1;
			if ($bManager){
				$iResta++;
				echo '
				    <td>&nbsp;</td>
	        		';
			}
			
			for ($j=0; $j < $iColspanTD-($iResta+3); $j++) {
				echo '<td>&nbsp;</td>';
			}
			?>                
                    <td class="tdTotalDN" colspan="2">Resultado Global</td>
                    <td class="td-rn-desc3"><?php echo rand(1, 100);?>%</td>                    
                </tr>
                <tr>
			<?php 
			for ($j=0; $j < sizeof($iColspanTD); $j++) {
				echo '<td>&nbsp;</td>';
			}
			?>
                </tr>
            </table>

            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="90%" >
               <tr>
                    <td class="td-rn-desc1"><p>&nbsp;</p></td>
                    <td class="td-rn-desc2"><strong>Manager</strong></td>                    
                    <td class="td-rn-desc2"><strong>Auto Percepción</strong></td>
                    <td class="td-rn-desc2"><strong>Test Conocimiento</strong></td>
                </tr>            
                <tr>
<!-- Dinámico
					<td class="tdLineDN-H" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;" colspan="4">Competencias Técnicas</td>
-->
                    <td class="tdLineDN-H" style="background-color: #cc0033 !important;color: #fff !important;" colspan="4">Competencias Técnicas</td>
                </tr>
            <?php 
				for ($i=0; $i < sizeof($aAutopercepcion); $i++) {
			?>
                <tr>
                    <td><span class="indent"><?php echo $aAutopercepcion[$i]?></span></td>
                    <td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc3"><?php echo rand(1, 100);?>%</td>                    
                </tr>                
			<?php 
				}
			?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" class="tdTotalDN">Resultado Global Competencias Técnicas</td>
                    <td class="td-rn-desc3"><?php echo rand(1, 100);?>%</td>                    
                </tr>
            </table> 

            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="90%" >
                <tr>
<!-- Dinámico
					<td class="tdLineDN-H" colspan="8" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Procesos</td>
-->
                    <td class="tdLineDN-H" colspan="8" style="background-color: #cc0033 !important;color: #fff !important;">Procesos</td>
                </tr>
               <tr>
                    <td class="" rowspan="2" style="/*width: 30% !important;*/"><p>&nbsp;</p></td>
                    <td class="td-rn-desc2" rowspan="2"><strong>Entrevista Competencias</strong></td>
                    <td class="td-rn-desc2" rowspan="2"><strong>Role Play I</strong></td>                    
                    <td class="td-rn-desc2" rowspan="2"><strong>Role Play II</strong></td>
                    <td class="td-rn-desc2" colspan="3"><strong>Caso Práctico</strong></td>
                    <td class="td-rn-desc2" >&nbsp;</td>
                </tr>

                <tr>
                    <td class="td-rn-desc2"><strong>Ejercicio 1</strong></td>
                    <td class="td-rn-desc2"><strong>Ejercicio 2</strong></td>
                    <td class="td-rn-desc2"><strong>Ejercicio 3</strong></td>
                    <td class="td-rn-desc2"><strong>Total</strong></td>
                </tr>
            <?php 
				for ($i=0; $i < sizeof($aProcesos); $i++) {
					$iPinta=rand(1, 6);
										$iPinta=rand(1, 6);
					switch ($iPinta)
					{
						case 1:
               echo ' <tr>
	                    <td class="indent">' . $aProcesos[$i] . '</td>
						<td class="td-rn-desc2" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							
							break;
						case 2:
               echo ' <tr>
	                    <td class="indent">' . $aProcesos[$i] . '</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							break;
						case 3:
               echo ' <tr>
	                    <td class="indent">' . $aProcesos[$i] . '</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
						<td class="td-rn-desc2" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							
							break;
						case 4:
               echo ' <tr>
	                    <td class="indent">' . $aProcesos[$i] . '</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
						<td class="td-rn-desc2" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							
							break;
						case 5:
               echo ' <tr>
	                    <td class="indent">' . $aProcesos[$i] . '</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							
							break;
						case 6:
               echo ' <tr>
	                    <td class="indent">' . $aProcesos[$i] . '</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                    <td class="td-rn-desc2" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							
							break;
					}
					
			?>
			<?php 
					
				}
			?>
                <tr>
                    <td colspan="8">&nbsp;</td>
                </tr>
                <tr>
                    <td  colspan="7" class="tdTotalDN">Resultado Global Procesos</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    
                </tr>
           </table>
                        
        </div><!-- fin página -->
        
 
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("cabecera.php");
		    ?>
            <h4>6 - Conclusiones: {{ $user->name }} {{ $user->last_name }}</h4>
            <hr>
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="95%" >
                <tr>
<!-- Dinámico
					<td class="tdLineCO-H" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Puntos Fuertes</td>
-->
                    <td class="tdLineCO-H" style="background-color: #cc0033 !important;color: #fff !important;">Puntos Fuertes</td>
                </tr>
                <tr>
                    <td class="td-CO-desc">
                    	{{ $feedback->puntosFuertes }}
                    </td>
                </tr>
            </table>  
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="95%" >
                <tr>
<!-- Dinámico
					<td class="tdLineCO-H" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Áreas de Mejora</td>
-->
                    <td class="tdLineCO-H" style="background-color: #cc0033 !important;color: #fff !important;">Áreas de Mejora</td>
                </tr>
                <tr>
                    <td class="td-CO-desc">
	                    {{ $feedback->areasMejora }}
                    </td>
                </tr>
            </table>  
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="95%" >
                <tr>
<!-- Dinámico
					<td class="tdLineCO-H" style="background-color: {{ $clients->colorSeccion2 }} !important;color: {{ $clients->colorFontSeccion2 }} !important;">Recomendaciones Formativas</td>
-->
                    <td class="tdLineCO-H" style="background-color: #cc0033 !important;color: #fff !important;">Recomendaciones Formativas</td>
                </tr>
                <tr>
                    <td class="td-CO-desc" >
                    	{{ $feedback->DescfeedbackRF }}
                    </td>
                </tr>
            </table>  

        </div><!-- fin página -->
                       
    </div><!-- fin content -->  
        

</body>
</html>