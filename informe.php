<?php

$_EscalaMaxima="7";

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
		$chm.= "s,cc0033,". $iIndice .",-1,8";
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
		$chm.= "s,cccccc," . $iIndice . ",-1,8|";
		$iIndice++;
		$chm.= "s,cc0033," . $iIndice . ",-1,8";
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

    $aEjercicios = array("Cuestionario de Autopercepción", "Test de Conocimientos", "Caso Práctico Ejercicio 1", "Caso Práctico Ejercicio 2", "Caso Práctico Ejercicio 3", "Role Play I – Contacto telefónico de prospección", "Role Play II – Negociación y cierre", "Entrevista Competencias", "Dinámica - Los Hambrientos");
    $aDescEjercicios = array("Este cuestionario establece la propia percepción del asistente con respecto al perfil en el que se basa la Certificación (modelo de competencias). La autopercepción no repercute en el resultado del análisis, sino que sirve para comparar entre su visión y la de los demás.", "Este cuestionario consiste en un test de 130 preguntas de 13 temáticas con una sola respuesta correcta.", "Consiste en la realización de uno ejercicios en el que se invita a la resolución de distintas situaciones reflejadas en su agenda. El Asesor Comercial deberá describir cada uno de los procesos. Caso practico 1.", "Consiste en la realización de uno ejercicios en el que se invita a la resolución de distintas situaciones reflejadas en su agenda. El Asesor Comercial deberá describir cada uno de los procesos. Caso practico 2.", "Consiste en la realización de uno ejercicios en el que se invita a la resolución de distintas situaciones reflejadas en su agenda. El Asesor Comercial deberá describir cada uno de los procesos. Caso practico 3.", "El marco es una llamada telefónica dirigida a un cliente con el que hace tiempo no se ha establecido ningún tipo de seguimiento. Se desea que el Asesor Comercial sea capaz de recuperar la confianza del cliente, así como de plantear propuestas alternativas que puedan atraerlo de nuevo al concesionario.", "Esta prueba se escenifica con la visita al concesionario de un cliente que está interesado en la compra de un vehículo que ya configuró y además realizó la prueba de conducción. La misión principal del Asesor Comercial consiste en completar y ajustar la oferta, así como realizar, en la medida de lo posible, una venta.", "Para completar la visión global del Asesor Comercial y aclarar cuestiones pendientes, se realiza una entrevista estructurada.", "En esta prueba los Participantes tienen que realizar un debate, en base a una situación planteada, para llegar a un acuerdo o solución consensuada");
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
//Dínamico $iPR=$evaluatios->pr;    
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
    $sL1 = "1:" . $sL1;
    $sL2 = "|2:" . $sL2;
    $sLiterarGrafPerfilGeneral = $sL1 . $sL2;
   
    $sChdl="";
    $sChdl=getChdl($bManager,$bPerfilRequerido);
    $sExtras=getExtras($bManager,$bPerfilRequerido);
    

    $sIndiceGrafPerfilGeneral = substr($sIndice, 1);

    if ($bPerfilRequerido){
    	$sPuntuacionesPerfilRGrafPerfilGeneral = substr($sPuntuacionesPR, 1);
    }
    if ($bManager){
    	$sPuntuacionesManagerGrafPerfilGeneral = substr($sPuntuacionesM, 1);
    }    
    $sPuntuacionesAPGrafPerfilGeneral = substr($sPuntuacionesAP, 1);
    $sPuntuacionesACGrafPerfilGeneral = substr($sPuntuacionesAC, 1);

    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Informe Nombre Participante Apellidos Participante</title>
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
    	include("informeCabecera.php");
    ?>
        <div id="intro-head">
        	<table width="100%" align="center">
        		<tr>
        			<td width="50%">&nbsp;</td>
        			<td class="intro-date" width="5%">Fecha:&nbsp;</td>
        			<!-- Commentario:: Primera fecha Desde del primer ejercicio de la lista de ejercicios -->
        			<td class="intro-date"><span>05/05/2017</span></td>
        		</tr>
        		<tr>
        			<td></td>
        			<td class="intro-area">Concesión:&nbsp;</td>
        			<td class="intro-area"><span>VILAMOBIL MOTOR, S.L.</span></td>
        		</tr>
        		<tr>
        			<td></td>
        			<td colspan="2"><hr class="hrHomeHead" style="background:#cc0033 !important;"></td>
        		</tr>
        	</table>
        </div>
        <div id="intro-middle">Proceso de Certificación AsesoresComercialesVN</div>
        <div id="intro-foot">
        	<div class="intro-nombreP">Nombre Participante Apellidos Participante</div>
            <div class="intro-line2"><hr class="hr2" style="background:#cc0033 !important;"></div>
            <div class="intro-fotoP"><img src="img/user.png" alt="Nombre y apellidos" title="Nombre y apellidos" border="0" width="150"></div>
        </div>
        
        <div id="intro-foot-2">
        	<img src="img/pie_cliente.jpg" alt="imagen del cliente para el pie" title="imagen del cliente para el pie" width="100%" border="0">
            <br />©People Experts, 2017
        </div>
    </div>
    <div id="content">
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>Índice</h4>
            <hr>     
            <div class="temario-li">            
                <ol>
                    <li>Ficha Técnica</li>
                    <li>Descripción del Proceso</li>
                    <li>Agenda del Proceso</li>
                    <li>Competencias Proceso de Certificación AsesoresComercialesVN</li>
                    <li>Resultados del Participante</li>
                    <li>Conclusiones</li>
                </ol>
                <ul>
                    <li>Anexo. Curriculum Vitae</li>
                </ul>
                 
            </div>
			<div id="confidencialidad" style="border-color:#cc0033 !important;">
                    <p>CONFIDENCIALIDAD: El acceso y uso de este informe está restringido a sus destinatarios, quienes se comprometen a mantener la debida confidencialidad sobre sus contenidos y a utilizarlo únicamente con la finalidad para la que ha sido emitido. Su uso debe enmarcarse según lo establecido en la Ley 15/1999 de 13 de Diciembre de Protección de Datos de Carácter Personal (LOPD).</p>
                    <p>Psicólogos Empresariales y Asociados S.A. se compromete y obliga a guardar y a hacer guardar a todos sus colaboradores y empleados la más estricta confidencialidad respecto a cualquier información -en cualquier formato-, recibida de los empleados del cliente y suministrada para la realización de este Proceso, incluyendo especialmente la situación personal de sus empleados. Psicólogos Empresariales y Asociados S.A. no se responsabiliza del inadecuado tratamiento de los datos de carácter personal.</p>                                
            </div>            
            
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>1 - Ficha Técnica</h4>
            <hr>     
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >            
                <tr>
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Descripción</td>
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>Proceso de Certificación del puesto de Asesor Comercial VN.
<br /><br />
Se realiza un Assessment Center de Certificación para analizar la adecuación del perfil
competencial.
                        </p>
                    </td>                    
                </tr>                
            </table>  
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Participante</td>
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>Nombre Participante Apellidos Participante</p>
                    </td>                    
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Manager</td>
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>Nombre del Manager Apellidos del Manager</p>
                    </td>                    
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Consultor</td>
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>Nombre del Consultor Apellidos del Consultor</p>
                    </td>                    
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Observador/es</td>
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <p>
                        
			            	Nombre del Consultor2 Apellidos del Consultor2<br /><br />
			            	Nombre de Observador1 Apellidos de Observador1<br /><br />
			            	Nombre de Observador2 Apellidos de Observador2<br /><br />
			            
                        </p>
                    </td>                    
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                <tr>
                    <td class="td-desc" style="background-color: #cc0033 !important;color: #fff !important;">Fecha y Lugar</td>
                    <td class="td-line" style="border-bottom-color:#cc0033 !important;">&nbsp;</td>                                        
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <!-- Primera fecha Desde del primer ejercicio de la lista de ejercicios -->
                        <p>05/05/2017, L'Hospitalet de l' Infant Tarragona</p>
                    </td>                    
                </tr>
            </table>
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>2 - Descripción del Proceso</h4>
            <hr>
            <p class="desc-val">La Certificación de Asesores Comerciales VNconsiste en una sesión de un día en la que se evalúa si el potencial
del asistente cumple con los requisitos para el puesto. Para lograr este propósito, el participante realiza una serie de
ejercicios que describimos a continuación:
            </p>
            <div class="sec-block" style="background-color: #cc0033 !important;color: #fff !important;">Ejercicios</div>
            <?php 
            	for ($i=0; $i < sizeof($aEjercicios); $i++) {
			?>
                <div class="block-text">
                    <p class="desc-val">
                        <strong><?php echo $aEjercicios[$i];?></strong>
                        <br /><br />
                        <?php echo $aDescEjercicios[$i];?>
                    </p>
                </div>
			<?php 
            	}
			?>
            <br /><br /><br />
            <p class="desc-val">Una vez finalizadas las pruebas, concretamente el día siguiente se realizará una entrevista de una hora de duración para presentar los resultados del día anterior.</p>
            
        </div><!-- fin página -->
                       
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>3 - Agenda del Proceso</h4>
            <hr>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" >            
                <tr>
                    <td class="td-pru-desc1" style="background-color: #cc0033 !important;color: #fff !important;">Pruebas</td>
                    <td class="td-pru-desc2" style="background-color: #cc0033 !important;color: #fff !important;">&nbsp;</td>
                    <td class="td-pru-desc3" style="background-color: #cc0033 !important;color: #fff !important;">Duración</td>
                                                            
                </tr>
            <?php 
            	for ($i=0; $i < sizeof($aEjercicios); $i++) {
			?>
                <tr>
                    <td><p style="color:#333;font-weight: bold;"><?php echo $aEjercicios[$i];?></p></td>
                    <td>&nbsp;</td>
                    <td>
                    	<?php echo tiempoTranscurridoFechas("2017-04-19 16:30:00","2017-04-19 16:40:00");?>
                    </td>
                    
                </tr>
   			<?php 
            	}
			?>
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
		    	include("informeCabecera.php");
		    ?>
            <h4>4 - Competencias</h4>
            <hr>
            <p class="desc-val">
            	Informe basado en el Perfil de competencias (técnicas y personales) y funciones del Asesor Comercial VN.
            </p>
            <div class="sec-block" style="background-color: #cc0033 !important;color: #fff !important;">Nivel de desempeño / profesionalidad</div>
            <div class="ndp" style="color: #cc0033 !important;border-color: #cc0033 !important;">
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
		    	include("informeCabecera.php");
		    ?>
            <h4>5 - Resultado General: Nombre Participante Apellidos Participante</h4>
            <hr>
            <div id="intro-middle" align="center" style="margin-top: 100px !important;">
            <!-- Si seccionCP o -->
	            <table border="0" cellspacing="0" cellpadding="0" width="40%" >
	        
            <?php
            	$seccionCP="Si está vacio, no se pinta el bloque en la generación del PDF";
				if (!empty($seccionCP)){
	        ?>            
	                <tr>
	                    <td class="td-desc" style="background-color: #ccc;color:#000;text-align:center;width:250px;white-space: nowrap;">Competencias Personales</td>
	                    <td class="td-line" style="text-align:center;font-size: 14px;">APTO</td>                                        
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
	                    <td class="td-desc" style="background-color: #ccc;color:#000;text-align:center;width:250px;white-space: nowrap;">Competencias Técnicas</td>
	                    <td class="td-line" style="text-align:center;font-size: 14px;">APTO</td>                            
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
					                    <td class="td-desc" style="background-color: #ccc;color:#000;text-align:center;width:250px;white-space: nowrap;">Proceso de Ventas Audi</td>
					                    <td class="td-line" style="text-align:center;font-size: 14px;">APTO</td>                            
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
	                    <td class="td-desc" style="background-color:#cc0033 !important;color: #fff !important;text-align:center;width:250px;white-space: nowrap;">Resultado</td>
	                    <td class="td-line" style="text-align:center;border-bottom-color:#cc0033 !important;font-size: 14px;">APTO</td>                                        
	                </tr>
	                <tr>
	                    <td>&nbsp;</td>
	                    <td>&nbsp;</td>                    
	                </tr> 
	            </table>
	             
	            <p class="desc-val" style="font-size:14px;font-weight: normal;">
	            Los resultados obtenidos en el Assessment de Certificación indican que el Sr. Nombre Participante tiene unas Competencias Personales, unas Competencias Técnicas y unos conocimientos en Procesos de Venta alineados con los requisitos de la Marca Audi.
<br /><br />
En consecuencia, CERTIFICAMOS al Sr. Nombre Participante como Asesor Comercial VN Audi.
				</p> 
	            
            </div>
     
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>5 - Resultado General: Nombre Participante Apellidos Participante</h4>
            <hr>
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">Competencias Personales</div>
            <div class="block-text">
                <p class="desc-val">
                      <img title="Competencias Personales" alt="Competencias Personales" src="https://chart.googleapis.com/chart?cht=lxy&chs=948x300&chxl=<?php echo $sLiterarGrafPerfilGeneral;?>&chdl=<?php echo $sChdl;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafPerfilGeneral;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafPerfilGeneral)) ? $sPuntuacionesPerfilRGrafPerfilGeneral . "|-1|" : "";?><?php echo (!empty($sPuntuacionesManagerGrafPerfilGeneral)) ? $sPuntuacionesManagerGrafPerfilGeneral . "|-1|" : "";?><?php echo $sPuntuacionesAPGrafPerfilGeneral;?>|-1|<?php echo $sPuntuacionesACGrafPerfilGeneral;?>&chco=<?php echo $sExtras;?>&chdls=333333,12&chdlp=t|l">
                </p>
            </div>
<?php 
			$bManager=true;
			$bPerfilRequerido=true;
            	$sL1="";
            	$sL2="";
            	$sIndice="";
            	$sPuntuacionesM="";		////Resultados Manager
//Dínamico $iPR=$evaluatios->pr;    
			    $iPR=5;					//perfil Requerido definido en la evaluación
			    $sPuntuacionesPR="";	//Perfil Requerido
            	$sPuntuacionesAP="";	//Resultados Autopercepción
            	$sPuntuacionesAC="";	//Resultados AC
			$sPiePerfilDetallado="";
            for ($i=0; $i < sizeof($aGrupos); $i++) {
            	$sPiePerfilDetallado.="<td align='center'><strong>" . $aGrupos[$i] . "</strong></td>";
            	$aCompGrp = $aCompetencias[$i];
            	$aListaGraf = array();
            	for ($x=0; $x < sizeof($aCompGrp); $x++) {
            		$aListaGraf[]=getTxtXImg($aCompGrp[$x]);
            	}
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
			}
            	$sL1 = "1:" . $sL1;
            	$sL2 = "|2:" . $sL2;
            	$sLiterarGrafCompGrp = $sL1 . $sL2;
            	$sIndiceGrafCompGrp = substr($sIndice, 1);

                if ($bPerfilRequerido){
            		$sPuntuacionesPerfilRGrafCompGrp = substr($sPuntuacionesPR, 1);
            	}
            	if ($bManager){
            		$sPuntuacionesManagerGrafCompGrp = substr($sPuntuacionesM, 1);
            	}            	
            	$sPuntuacionesAPGrafCompGrp = substr($sPuntuacionesAP, 1);
            	$sPuntuacionesACGrafCompGrp = substr($sPuntuacionesAC, 1);
            
            $sMana = (!empty($sPuntuacionesPerfilRGrafCompGrp)) ? $sPuntuacionesPerfilRGrafCompGrp . "|-1|" : "";
            $sMana .= (!empty($sPuntuacionesManagerGrafCompGrp)) ? $sPuntuacionesManagerGrafCompGrp . "|-1|" : "";
            
?>
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">Competencias Personales. Perfil Detallado</div>
            <div class="block-text">
                <p class="desc-val">
                      <?php echo '<img title="Competencias Personales, Perfil Detallado" alt="Competencias Personales, Perfil Detallado" src="https://chart.googleapis.com/chart?cht=lxy&chs=948x300&chxl=' . $sLiterarGrafCompGrp . '&chdl=' . $sChdl . '&chxt=y,x,x&chd=t:' . $sIndiceGrafCompGrp . '|' . $sMana .  $sPuntuacionesAPGrafCompGrp . '|-1|' . $sPuntuacionesACGrafCompGrp . '&chco=' . $sExtras . '&chdls=333333,12&chdlp=t|l">';?>
                      <table width="948">
                      	<tr>
                      	<?php 
                      		echo $sPiePerfilDetallado;
                      	?>
                      	</tr>
                      </table>
                </p>
            </div>
            <div class="block-text">
                <p class="desc-val">Comentarios del Consultor sobre la interpertación de las Gráficas si fuese necesario. Si no pone comentarios este espacio no saldrá en el informe.</p>
            </div>
            
        </div><!-- fin página -->
        
<div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
   		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>5 - Valores de Marca: Nombre Participante Apellidos Participante</h4>
            <hr>
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
				$sL1 = "1:" . $sL1;
				$sL2 = "|2:" . $sL2;
				$sLiterarGrafVMarca = $sL1 . $sL2;
				$sIndiceGrafVMarca = substr($sIndice, 1);

				if ($bPerfilRequerido){
					$sPuntuacionesPerfilRGrafVMarca = substr($sPuntuacionesPR, 1);
				}
				if ($bManager){
					$sPuntuacionesManagerGrafVMarca = substr($sPuntuacionesM, 1);
				}				
				$sPuntuacionesAPGrafVMarca = substr($sPuntuacionesAP, 1);
				$sPuntuacionesACGrafVMarca = substr($sPuntuacionesAC, 1);
			?>
                      <img title="Valores de Marca" alt="Valores de Marca" src="https://chart.googleapis.com/chart?cht=lxy&chs=948x300&chxl=<?php echo $sLiterarGrafVMarca;?>&chdl=<?php echo $sChdl;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafVMarca;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafVMarca)) ? $sPuntuacionesPerfilRGrafVMarca . "|-1|" : "";?><?php echo (!empty($sPuntuacionesManagerGrafVMarca)) ? $sPuntuacionesManagerGrafVMarca . "|-1|" : "";?><?php echo $sPuntuacionesAPGrafVMarca;?>|-1|<?php echo $sPuntuacionesACGrafVMarca;?>&chco=<?php echo $sExtras;?>&chdls=333333,12&chdlp=t|l">
                </p>
            </div>
            <div class="block-text">
                <p class="desc-val">
                <span style="color:#cc0033;font-weight: bold;">Orientación al Éxito</span><br />Visión de negocio + Visión estratégica + Pensamiento analítico + Orientación a resultados + Dirección de personas
                <br /><br /><span style="color:#cc0033;font-weight: bold;">Responsable</span><br />Análisis de mercado + Análisis de necesidades + Capacidad de comunicación + Imagen profesional
                <br /><br /><span style="color:#cc0033;font-weight: bold;">Apasionado</span><br />Entusiasmo + Capacidad de comunicación + Capacidad de influencia + Perseverancia + Embajador de la Marca
                <br /><br /><span style="color:#cc0033;font-weight: bold;">Proactivo</span><br />Gestión del cambio + Optimización de procesos + Innovación/creatividad + Capacidad de aprendizaje
                <br /><br /><span style="color:#cc0033;font-weight: bold;">Humano -Justo</span><br />Mentalidad de servicio + Colaboración + Desarrollo de personas + Honestidad
                </p>
            </div>
            
        </div><!-- fin página -->
        
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>5 - Resultado General: Nombre Participante Apellidos Participante</h4>
            <hr>
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">Competencias Técnicas</div>
            <div class="block-text">
                <p class="desc-val">
	                <?php 
	                //
	                $aListaGraf = array();
	                for ($i=0; $i < sizeof($aAutopercepcion); $i++) {
	                	$aListaGraf[]=getTxtXImg($aAutopercepcion[$i]);
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

	                	if ($bPerfilRequerido){
	                		$sPuntuacionesPR.= "," . $iPR;
	                	}
	                	if ($bManager){
	                		$sPuntuacionesM.= "," . rand (1,7);
	                	}	                	
	                	$sPuntuacionesAP.= "," . rand (1,7);
	                	$sPuntuacionesAC.= "," . rand (1,7);
	                }
	                $sL1 = "1:" . $sL1;
	                $sL2 = "|2:" . $sL2;
	                $sLiterarGrafPerfilGeneralCT = $sL1 . $sL2;
	                
	                $sChdl="";
	                $sChdl=getChdl($bManager,$bPerfilRequerido);
	                $sExtras=getExtras($bManager,$bPerfilRequerido);
	                 
	                 
	                $sIndiceGrafPerfilGeneralCT = substr($sIndice, 1);

	                if ($bPerfilRequerido){
	                	$sPuntuacionesPerfilRGrafPerfilGeneralCT = substr($sPuntuacionesPR, 1);
	                }
	                if ($bManager){
	                	$sPuntuacionesManagerGrafPerfilGeneralCT = substr($sPuntuacionesM, 1);
	                }	                
	                $sPuntuacionesAPGrafPerfilGeneralCT = substr($sPuntuacionesAP, 1);
	                $sPuntuacionesACGrafPerfilGeneralCT = substr($sPuntuacionesAC, 1);
	                 
	                 
	                //
	                
	                ?>
                      <img title="Perfil General" alt="Perfil General" src="https://chart.googleapis.com/chart?cht=lxy&chs=948x300&chxl=<?php echo $sLiterarGrafPerfilGeneralCT;?>&chdl=<?php echo $sChdl;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafPerfilGeneralCT;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafPerfilGeneralCT)) ? $sPuntuacionesPerfilRGrafPerfilGeneralCT . "|-1|" : "";?><?php echo (!empty($sPuntuacionesManagerGrafPerfilGeneralCT)) ? $sPuntuacionesManagerGrafPerfilGeneralCT . "|-1|" : "";?><?php echo $sPuntuacionesAPGrafPerfilGeneralCT;?>|-1|<?php echo $sPuntuacionesACGrafPerfilGeneralCT;?>&chco=<?php echo $sExtras;?>&chdls=333333,12&chdlp=t|l">
					<br /><br />
                </p>
            </div>
            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">Procesos de Venta</div>
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
//Dínamico $iPR=$evaluatios->pr;    
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
	                $sL1 = "1:" . $sL1;
	                $sL2 = "|2:" . $sL2;
	                $sLiterarGrafPerfilGeneralPRO = $sL1 . $sL2;
	                
	                $sChdlPROC="";
	                $sChdlPROC=getChdlPROC($bPerfilRequerido);
	                $sExtrasPROC=getExtrasPROC($bPerfilRequerido);
	                 
	                 
	                $sIndiceGrafPerfilGeneralPRO = substr($sIndice, 1);

	                if ($bPerfilRequerido){
	                	$sPuntuacionesPerfilRGrafPerfilGeneralPRO = substr($sPuntuacionesPR, 1);
	                }
	                
	                $sPuntuacionesPROCGrafPerfilGeneralPROC = substr($sPuntuacionesPROC, 1);
	                ?>
                      <img title="Resultados Procesos" alt="Resultados Procesos" src="https://chart.googleapis.com/chart?cht=lxy&chs=948x300&chxl=<?php echo $sLiterarGrafPerfilGeneralPRO;?>&chdl=<?php echo $sChdlPROC;?>&chxt=y,x,x&chd=t:<?php echo $sIndiceGrafPerfilGeneralPRO;?>|<?php echo (!empty($sPuntuacionesPerfilRGrafPerfilGeneralPRO)) ? $sPuntuacionesPerfilRGrafPerfilGeneralPRO . "|-1|" : "";?><?php echo $sPuntuacionesPROCGrafPerfilGeneralPROC;?>&chco=<?php echo $sExtrasPROC;?>&chdls=333333,12&chdlp=t|l">
                </p>
            </div>

            <div class="block-text">
                <p class="desc-val">
                    Comentarios del Consultor sobre la interpertación de las Gráficas si fuese necesario. Si no pone comentarios este espacio no saldrá en el informe.
                </p>
            </div>
            
        </div><!-- fin página -->
        
		<?php 
			
			$sPaginaInicio='
            		<div style="page-break-before: always;">
            		<!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
			';
	    	$sPaginaInicio.= get_include_contents('informeCabecera.php');
	    	$sPaginaInicio.='
            				<h4>5 - Resultados en detalle: Nombre Participante Apellidos Participante</h4>
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
//Dínamico $iPR=$evaluatios->pr;    
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
            	$sL1 = "1:" . $sL1;
            	$sL2 = "|2:" . $sL2;
            	$sLiterarGrafCompGrp = $sL1 . $sL2;
            	$sIndiceGrafCompGrp = substr($sIndice, 1);

                if ($bPerfilRequerido){
            		$sPuntuacionesPerfilRGrafCompGrp = substr($sPuntuacionesPR, 1);
            	}
            	if ($bManager){
            		$sPuntuacionesManagerGrafCompGrp = substr($sPuntuacionesM, 1);
            	}            	
            	$sPuntuacionesAPGrafCompGrp = substr($sPuntuacionesAP, 1);
            	$sPuntuacionesACGrafCompGrp = substr($sPuntuacionesAC, 1);
            	
            	
            	if ($i%2 == 0 && $i > 0){
            		echo $sPaginaInicio . $sPaginaCDG . $sPaginaFin;
            		$sPaginaCDG="";
            	}
            	$sMana = (!empty($sPuntuacionesPerfilRGrafCompGrp)) ? $sPuntuacionesPerfilRGrafCompGrp . "|-1|" : "";
            	$sMana .= (!empty($sPuntuacionesManagerGrafCompGrp)) ? $sPuntuacionesManagerGrafCompGrp . "|-1|" : "";
            	
            	
		        $sPaginaCDG.='
			            <div class="sec-block" style="background-color:#cc0033 !important;color: #fff !important;">' . $aGrupos[$i] . '</div>
			            <div class="block-text">
			                <p class="desc-val">
			                      <img title="Resultados en detalle del grupo ' . $aGrupos[$i] . '" alt="Resultados en detalle del grupo ' . $aGrupos[$i] . '" src="https://chart.googleapis.com/chart?cht=lxy&chs=948x300&chxl=' . $sLiterarGrafCompGrp . '&chdl=' . $sChdl . '&chxt=y,x,x&chd=t:' . $sIndiceGrafCompGrp . '|' . $sMana .  $sPuntuacionesAPGrafCompGrp . '|-1|' . $sPuntuacionesACGrafCompGrp . '&chco=' . $sExtras . '&chdls=333333,12&chdlp=t|l">
			                </p>
			            </div>
			            <div class="block-text">
			                <p class="desc-val">
				Comentarios del Consultor sobre la interpertación de las Gráficas de ' . $aGrupos[$i] . '. Si no pone comentarios este espacio no saldrá en el informe.
				
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
		    	include("informeCabecera.php");
		    ?>
            <h4>5 - Resultados Numéricos: Nombre Participante Apellidos Participante</h4>
            <hr>
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="90%" >
               <tr>
                    <td class="td-rn-desc1"><p>&nbsp;</p></td>
				<?php
				//Hay que calcular el colespan, segun los ejercicios del proceso y si tiene o no Manager
				//$aEjercicios = array("Auto Percepción", "Entrevista", "El caso", "Roleplay 1");
				$iColspanTD=1;
				if ($bManager){
					$iColspanTD++;
				?>
                    <td class="td-rn-desc2"><strong>Manager</strong></td>
                <?php 
				}
				
				for ($i=0; $i < sizeof($aEjercicios); $i++) {
					$iColspanTD++;
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
                    <td class="tdLineDN-H" style="background:#cc0033 !important;color: #fff !important;" colspan="<?php echo $iColspanTD;?>">Competencias Personales</td>
                </tr>
			<?php
			
			for ($i=0; $i < sizeof($aGrupos); $i++) {
			?>
    		    <tr>
                    <td class="tdLineDN-Gr" colspan="<?php echo $iColspanTD;?>"><?php echo mb_strtoupper($aGrupos[$i]);?></td>
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
				for ($p=0; $p < sizeof($aEjercicios); $p++) {
				?>
				<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
				<!-- 
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
					<td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
					<td class="td-rn-desc2">&nbsp;</td>
				 -->
			<?php }?>
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
                    <td class="tdTotalDN" colspan="3">Resultado Global</td>
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
                    <td class="tdTotalDN">Resultado Global Competencias Técnicas</td>
                    <td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc3"><?php echo rand(1, 100);?>%</td>                    
                </tr>
            </table> 

            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="90%" >
                <tr>
                    <td class="tdLineDN-H" colspan="8" style="background-color: #cc0033 !important;color: #fff !important;">Procesos</td>
                </tr>
               <tr>
                    <td class="" rowspan="2" style="/*width: 30% !important;*/"><p>&nbsp;</p></td>
                    <td class="td-rn-desc2" rowspan="2"><strong>Entrevista Competencias</strong></td>
                    <td class="td-rn-desc2" rowspan="2"><strong>Role Play I</strong></td>                    
                    <td class="td-rn-desc2" rowspan="2"><strong>Role Play II</strong></td>
                    <td class="td-rn-desc2" colspan="3"><strong>Caso Práctico</strong></td>
                    <td class="td-rn-desc2" ><strong>Total</strong></td>
                </tr>

                <tr>
                    <td class="td-rn-desc2"><strong>Ejercicio 1</strong></td>
                    <td class="td-rn-desc2"><strong>Ejercicio 2</strong></td>
                    <td class="td-rn-desc2"><strong>Ejercicio 3</strong></td>
                    <td class="td-rn-desc2"></td>
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
						<td class="td-rn-desc3" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
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
	                    <td class="td-rn-desc3" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
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
						<td class="td-rn-desc3" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
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
						<td class="td-rn-desc3" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
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
	                    <td class="td-rn-desc3" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
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
	                    <td class="td-rn-desc3" style="border-radius: 10px;">' . rand(1, 100) . '%</td>
	                    <td class="td-rn-desc2">&nbsp;</td>
	                </tr>';
							
							break;
					}
					
			?>
			<?php 
					
				}
			?>
<!-- 			
                <tr>
                    <td class="">Contacto telefónico llamada cliente</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                </tr> 
                <tr>
                    <td class="">Contacto telefónico de fidelización</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                </tr> 
                <tr>
                    <td class="">Detección necesidades y configuración</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                </tr> 
                <tr>
                    <td class="">Negociación y cierre</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                </tr> 
                <tr>
                    <td class="">Caso de prospección</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                </tr> 
 -->
                <tr>
                    <td colspan="8">&nbsp;</td>
                </tr>
                <tr>
                    <td class="tdTotalDN">Resultado Global Procesos</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc2">&nbsp;</td>
                    <td class="td-rn-desc3" style="border-radius: 10px;"><?php echo rand(1, 100);?>%</td>
                    
                </tr>
           </table>
                        
        </div><!-- fin página -->
        
 
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>6 - Conclusiones: Nombre Participante Apellidos Participante</h4>
            <hr>
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="95%" >
                <tr>
                    <td class="tdLineCO-H" style="background-color: #cc0033 !important;color: #fff !important;">Puntos Fuertes</td>
                </tr>
                <tr>
                    <td class="td-CO-desc">
                    	El Sr. Nombre Participante ha obtenido en el área de Competencias Técnicas un resultado global ligeramente por encima de los
estándares de la Marca. Destacamos su conocimiento en los bloques de Audi Servicios Financieros, Comunicación, Procesos de
Venta, Gestión del Vehículo de Ocasión y Modelos.
<br /><br />
El resultado global alcanzado en el área de Procesos de Ventas es destacado. A resaltar fundamentalmente los procesos de
Contacto Telefónico de Prospección, Contacto Telefónico llamada Cliente, Contacto Telefónico de Fidelización y Detección de
Necesidades.
<br /><br />
Por último, en el área de Competencias Personales obtiene un resultado global que se sitúa por encima de los estándares de Audi.
Cabe destacar los resultados obtenidos fundamentalmente en las competencias de Mentalidad de Servicio, Colaboración,
Perseverancia, Honestidad, Embajador de la Marca, Comunicación e Imagen Profesional.
                    </td>
                </tr>
            </table>  
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="95%" >
                <tr>
                    <td class="tdLineCO-H" style="background-color: #cc0033 !important;color: #fff !important;">Áreas de Mejora</td>
                </tr>
                <tr>
                    <td class="td-CO-desc">
	                    El Sr. Nombre Participante en el área de Competencias Técnicas tiene margen de mejora en los bloques de Sector y Mercado y
Fidelización Cliente CRM.
<br /><br />
A pesar de haber alcanzado un resultado destacable en el área de Procesos de Ventas debe mejorar en Caso de Prospección.
<br /><br />
Aunque en el área de Competencias Personales ha alcanzado en general unos buenos resultados, hay algunas competencias en
las cuales se puede desarrollar más como las de Visión de Negocio, Análisis del Mercado, Gestión del Cambio, Pensamiento
Analítico e Innovación/Creatividad.
                    
                    </td>
                </tr>
            </table>  
            <table border="0" id="tbDN" class="tbDN" cellspacing="0" cellpadding="0" width="95%" >
                <tr>
                    <td class="tdLineCO-H" style="background-color: #cc0033 !important;color: #fff !important;">Recomendaciones Formativas</td>
                </tr>
                <tr>
                    <td class="td-CO-desc" >
                    	Recomendamos al Sr. Nombre Participante que siga activamente la formación que la Marca pone a disposición, tal y como viene haciendo
hasta el momento. Teniendo en cuenta que su trayectoria en el sector del automóvil no es destacada, con mayor razón debe
esforzarse en mejorar sus conocimientos y Competencias Técnicas, especialmente en lo referente a Sector y Mercado. Es
importante que profundice no sólo en la propia Marca sino también en el conocimiento de otras marcas Premium con las que Audi
compite en el mercado.
<br /><br />
En relación al área de Competencias Personales, si bien todas están alineadas con los estándares de Audi, siempre existe margen
de mejora, especialmente en aquellas relacionadas con la competencia de Mentalidad Empresarial. La subcompetencia de Análisis
de Mercado nos lleva de nuevo a la necesidad de profundizar en las tendencias del mercado del sector del automóvil, lo cual le
llevará a trabajar con la finalidad de asegurar una mejor competitividad para su empresa.
                    
                    </td>
                </tr>
            </table>  

        </div><!-- fin página -->
       
        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
		    <?php 
		    	include("informeCabecera.php");
		    ?>
            <h4>&nbsp;</h4>
            <hr>
            <div id="intro-middle">&nbsp;</div>
            <div id="intro-foot">
                <div class="intro-nombreP">Anexo</div>
                <div class="intro-line2"><hr class="hr2" style="background:#cc0033 !important;"></div>
                <div class="intro-nameP">Curriculum Vitae</div>
                    
            </div>

        </div><!-- fin página -->

        <div style="page-break-before: always;">
            <!--<div style="background-color:white; height:30px; width:30px; position:absolute; z-index:999; top:50px"></div>-->
            <div id="div-ficha-cv">
                <img src="img/fichaCv1.jpg" alt="fichaCv2.jpg, 391kB" title="FichaCv2" border="0" > 
                <img src="img/fichaCv2.jpg" alt="fichaCv1.jpg, 453kB" title="FichaCv1" border="0" >            
            </div>
        </div><!-- fin página -->                      
    </div><!-- fin content -->  
        
</body>
</html>