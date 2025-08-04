<?php
/**
 * Ejemplo del uso del datetime y datetimezone para obtener la fecha de cualquier
 * parte del mundo
 */

# Mostramos la fecha de nuestro servidor

echo "<br />Fecha Actual: ".date("Y/m/d H:i:s");

# Mostramos la fecha en UTC

echo "<br />Fecha UTC: ".gmdate("Y/m/d H:i:s");

 

echo "<hr>";

 

# Mostramos la diferencia en segundos de Europe/Madrid en referencia al UTC
# y mostramos la fecha de la zona horaria Europe/Madrid

$timezone="America/Argentina/Buenos_Aires";

$dt=new datetime("now",new datetimezone($timezone));

echo "<br />Zona horaria: ".$dt->getTimezone()->getName();

echo "<br />Diferencia horaria con UTC (segundos): ".$dt->getOffset();

echo "<br />Fecha de la Zona horaria: ".gmdate("Y/m/d H:i:s",(time()+$dt->getOffset()));

 

echo "<hr>";

 

# Mostramos la diferencia en segundos de Europe/Madrid en referencia al UTC
# en una fecha dada

$dateTimeUTC="2001-01-01 12:00:00";

$dt=new datetime($dateTimeUTC,new datetimezone($timezone));

echo "<br />Zona horaria: ".$dt->getTimezone()->getName();

echo "<br />Diferencia horaria con UTC (segundos): ".$dt->getOffset();

echo "<br />Fecha de la Zona horaria: ".date("Y/m/d H:i:s",(strtotime($dateTimeUTC)+$dt->getOffset()));


# Mostramos el listado de todas las zonas horarias con su hora actual

echo "<hr>";

echo "<p>Listado de las zonas horarias</p>";

 

echo "<div style='clear:both;float:left;width:200px;'>ZonaHoraria</div>";

echo "<div style='float:left;width:70px;'>Dif. UTC</div>";

echo "<div style='float:left;width:200px;text-align:center;'>Hora Actual</div>";

 

foreach(DateTimeZone::listIdentifiers() as $tz)
{
	echo "<div style='clear:both;float:left;width:200px;'>".$tz."</div>";
	
	$dt=new datetime("now",new datetimezone($tz));
	echo "<div style='float:left;width:70px;text-align:right;'>".$dt->getOffset()."</div>";
	echo "<div style='float:left;width:200px;text-align:center;'>".gmdate("Y/m/d H:i:s",(time()+$dt->getOffset()))."</div>";
}
?>