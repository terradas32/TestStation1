<?php
function calcula_numero_dia_semana($dia,$mes,$ano){
	$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia,$ano));
	if ($numerodiasemana == 0) 
		$numerodiasemana = 6;
	else
		$numerodiasemana--;
	return $numerodiasemana;
}

//funcion que devuelve el último día de un mes y año dados
function ultimoDia($mes,$ano){ 
    $ultimo_dia=28; 
    while (checkdate($mes,$ultimo_dia + 1,$ano)){ 
       $ultimo_dia++; 
    } 
    return $ultimo_dia; 
} 

function dame_nombre_mes($mes){
	 switch ($mes){
	 	case 1:
			$nombre_mes="enero";
			break;
	 	case 2:
			$nombre_mes="febrero";
			break;
	 	case 3:
			$nombre_mes="marzo";
			break;
	 	case 4:
			$nombre_mes="abril";
			break;
	 	case 5:
			$nombre_mes="mayo";
			break;
	 	case 6:
			$nombre_mes="junio";
			break;
	 	case 7:
			$nombre_mes="julio";
			break;
	 	case 8:
			$nombre_mes="agosto";
			break;
	 	case 9:
			$nombre_mes="septiembre";
			break;
	 	case 10:
			$nombre_mes="octubre";
			break;
	 	case 11:
			$nombre_mes="noviembre";
			break;
	 	case 12:
			$nombre_mes="diciembre";
			break;
	}
	return $nombre_mes;
}

function dame_dia_semana($dia){
	$nombre_dia = array();
	 switch ($dia){
	 	case 1:
			$nombre_dia[0]="l";
			$nombre_dia[1]="Lunes";
			break;
	 	case 2:
			$nombre_dia[0]="m";
			$nombre_dia[1]="Martes";
			break;
	 	case 3:
			$nombre_dia[0]="m";
			$nombre_dia[1]="Miercoles";
			break;
	 	case 4:
			$nombre_dia[0]="j";
			$nombre_dia[1]="Jueves";
			break;
	 	case 5:
			$nombre_dia[0]="v";
			$nombre_dia[1]="Viernes";
			break;
	 	case 6:
			$nombre_dia[0]="s";
			$nombre_dia[1]="Sábado";
			break;
	 	case 7:
			$nombre_dia[0]="d";
			$nombre_dia[1]="Domingo";
			break;
	}
	return $nombre_dia;
}

function mostrar_calendario($mes, $ano, $opener=true, $nameOpener=""){

	$dT = time();
	$dD = date("d", $dT);
	$dD=(strlen($dD)==1) ? "0" . $dD : $dD;
	$dM = date("n", $dT);
	$dM=(strlen($dM)==1) ? "0" . $dM : $dM;
	$dA = date("Y", $dT);
	$sDateTime=$dA. "-" . $dM . "-" . $dD;
	echo '
		<table width="100%" align="center" cellspacing="1" cellpadding="1" border="0" bgcolor="#ffcc00">
			<tr>
				<td align="center"><b>CALENDARIO</b></td>
			</tr>
		</table>';
	//tomo el nombre del mes que hay que imprimir
	$nombre_mes = dame_nombre_mes($mes);
	echo '
		<table width="100%" align="center" cellspacing="1" cellpadding="1" border="0" bgcolor="#cccccc">
				<tr>
					<td align="center"><a href="#" onclick="javascript:delM();"><img src="graf/flechaI.gif" border="0" alt="" /></a></td>
					<td nowrap="nowrap" align="center">' . $nombre_mes . ' ' . $ano . '</td>
					<td align="center"><a href="#" onclick="javascript:addM();"><img src="graf/flecha.gif" border="0" alt="" /></a></td>
				</tr>
		</table>';
	//construyo la cabecera de la tabla
	echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">';
	echo "<tr>";
	for ($i=1; $i <= 7; $i++){
		$sDS = dame_dia_semana($i);
		echo '<td align="center" class="negro" title="' . $sDS[1] . '">' . $sDS[0]  . '</td>';
	}
	echo '
		</tr>
		<tr>
			<td colspan="7" bgcolor="#cccccc" height="1px"></td>
		</tr>';
	//Variable para llevar la cuenta del dia actual
	$dia_actual = 1;
	//calculo el numero del dia de la primera semana
	$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
	//echo "Numero del dia de demana del primer: $numero_dia <br>";
	//calculo el último dia del mes
	$ultimo_dia = ultimoDia($mes,$ano);
	//escribo la primera fila de la semana
	echo "<tr>";
	$x=0;
	for ($i=0; $i < 7; $i++){
		$dFecha= (strlen($dia_actual)==1) ? "0" . $dia_actual : $dia_actual;
		$dFecha .= "/" . $mes . "/" . $ano;
		$sEvento="";
		$sEvento=$dia_actual;
		if (date("d") == $dia_actual)
		{
			$hoy="<td align=\"center\" title=\"" . $dFecha . "\" class=\"negro\" onclick=\"javascript:selectedD('" . $dFecha . "');\" style=\"cursor:pointer;background-color:#ffcc00;border:1px solid;border-color:#FF9900;\" id='td" . $x . "'>" . $sEvento . "</td>";
		}else
		{
			$hoy="<td align=\"center\" title=\"" . $dFecha . "\" class=\"negro\" onclick=\"javascript:selectedD('" . $dFecha . "');\" style=\"cursor:pointer;\" id='td" . $x . "'>" . $sEvento . "</td>";
		}
		if ($i < $numero_dia){
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo nada en la celda
			echo "<td></td>";
		} else {
			echo $hoy;
			$dia_actual++;
		}
		$x++;
	}
	echo "</tr>";
	
	//recorro todos los demás días hasta el final del mes
	$numero_dia = 0;
	while ($dia_actual <= $ultimo_dia){
		//si estamos a principio de la semana escribo el <TR>
		if ($numero_dia == 0)
			echo "<tr>";
		$dFecha= (strlen($dia_actual)==1) ? "0" . $dia_actual : $dia_actual;
		$dFecha .= "/" . $mes . "/" . $ano;
		$sEvento="";
		$sEvento=$dia_actual;
		if (date("d") == $dia_actual)
		{
			$hoy="<td align=\"center\" title=\"" . $dFecha . "\" class=\"negro\" onclick=\"javascript:selectedD('" . $dFecha . "');\" style=\"cursor:pointer;background-color:#ffcc00;border:1px solid;border-color:#FF9900;\" id='td" . $x . "'>" . $sEvento . "</td>";
		}else
		{
			$hoy="<td align=\"center\" title=\"" . $dFecha . "\" class=\"negro\" onclick=\"javascript:selectedD('" . $dFecha . "');\" style=\"cursor:pointer;\" id='td" . $x . "'>" . $sEvento . "</td>";
		}
		echo $hoy;
		$dia_actual++;
		$numero_dia++;
		//si es el uñtimo de la semana, me pongo al principio de la semana y escribo el </tr>
		if ($numero_dia == 7){
			$numero_dia = 0;
			echo "</tr>";
		}
		$x++;
	}
	
	//compruebo que celdas me faltan por escribir vacias de la última semana del mes
	if ($numero_dia < 7){
		echo "<tr>";
	}
	for ($i=$numero_dia; $i < 7; $i++){
		echo "<td></td>";
	}
	echo "</tr>";
	echo "</table>";
}
