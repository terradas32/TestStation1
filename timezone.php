<?php

function getOptiosSelect_timezone_list() {
	static $allRegions = array(
			DateTimeZone::AFRICA,
			DateTimeZone::AMERICA,
			DateTimeZone::ANTARCTICA,
			DateTimeZone::ASIA,
			DateTimeZone::ATLANTIC,
			DateTimeZone::AUSTRALIA,
			DateTimeZone::EUROPE,
			DateTimeZone::INDIAN,
			DateTimeZone::PACIFIC
	);
	// Makes it easier to create option groups next
	$list = array ('AFRICA','AMERICA','ANTARCTICA','ASIA','ATLANTIC','AUSTRALIA','EUROPE','INDIAN','PACIFIC');
	// Make array holding the regions (continents), they are arrays w/ all their cities
	$region = array();
	foreach ($allRegions as $area){
		array_push ($region,DateTimeZone::listIdentifiers( $area ));
	}
	$count = count ($region); $i = 0; $holder = '';
	// Go through each region one by one, sorting and formatting it's cities
	while ($i < $count){
		$chunck = $region[$i];
		// Create the region (continents) option group
		$holder .= '<optgroup label="---------- '.$list[$i].' ----------">';
		$timezone_offsets = array();
		foreach( $chunck as $timezone ){
			$tz = new DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
		}
		ksort ($timezone_offsets);
		$timezone_list = array();
		foreach ($timezone_offsets as $timezone => $offset){
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate( 'H:i', abs($offset) );
			$pretty_offset = "UTC ${offset_prefix}${offset_formatted}";
			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}
		// All the formatting is done, finish and move on to next region
		foreach ($timezone_list as $key => $val){
			$holder .= '<option value="'.$key.'">'.$val.'</option>';
		}
		$holder .= '</optgroup>';
		++$i;
	}
	return $holder;
}
echo '<select class="obliga" name="fTimezone" id="fTimezone" size="1">' . getOptiosSelect_timezone_list() . '</select>';



// # Mostramos el listado de todas las zonas horarias con su hora actual
// echo "<hr>";
// echo "<p>Listado de las zonas horarias</p>";
// echo "<div style='clear:both;float:left;width:200px;'>ZonaHoraria</div>";
// echo "<div style='float:left;width:70px;'>Dif. UTC</div>";
// echo "<div style='float:left;width:200px;text-align:center;'>Hora Actual</div>";
// foreach(DateTimeZone::listIdentifiers() as $tz)
// {
// 	echo "<div style='clear:both;float:left;width:200px;'>".$tz."</div>";
// 	$dt=new datetime("now",new datetimezone($tz));
// 	echo "<div style='float:left;width:70px;text-align:right;'>".$dt->getOffset()."</div>";
// 	echo "<div style='float:left;width:200px;text-align:center;'>".gmdate("Y/m/d H:i:s",(time()+$dt->getOffset()))."</div>";
// }

///print_r(DateTimeZone::listIdentifiers());

// foreach(timezone_abbreviations_list() as $abbr => $timezone){
//         foreach($timezone as $val){
//                 if(isset($val['timezone_id'])){
//                         var_dump($abbr,$val['timezone_id']);
//                 }
//         }
// }
?>