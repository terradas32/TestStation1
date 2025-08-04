<?php
//Funciones de utilidades para el informe.

    /*
    * Devuelve la diferencia en minutos entre dos fechas dadas de tipo timestamp
    * @param date1 Fecha menos reciente
    * @param optional date2 Fecha más reciente, default fecha hora sistema.        
    */
    function getMinutos($date1, $date2="")
    {
    	if ($date2){
    		$date2 = date("Y-m-d H:i:s");
    	}
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        return round(($date2 - $date1) / 60);
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
    
    $aGrupos = array("Mentalidad empresarial", "Orientación y Satisfacción del Cliente", "Orientación a Objetivos", "Hablidades Sociales", "Valores Personales","Identificación con Audi");
    $aListaGraf = array();
    for ($i=0; $i < sizeof($aGrupos); $i++) {
    	$aListaGraf[]=getTxtXImg($aGrupos[$i]);
    }
    $sL1="";
    $sL2="";
    for ($i=0; $i < sizeof($aListaGraf); $i++) {
    	$sL1.="|" . $aListaGraf[$i][0];
    	$sL2.="|" . $aListaGraf[$i][1];
    }
    $sL1 = "1:" . $sL1;
    $sL2 = "|2:" . $sL2;

    echo "<br />--::" . $sL1 . $sL2;
    echo "<br />--::1:|Mentalidad|Orientación+y+Satisfacción|Orientación+a|Hablidades|Valores|Identificación+con|2:|Empresarial|del+Cliente|Objetivos|Sociales|Personales|Audi";
    
?>
