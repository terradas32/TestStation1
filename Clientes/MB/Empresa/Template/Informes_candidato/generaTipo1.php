<?php
		$cNivelesjerarquicos = new Nivelesjerarquicos();
		$cNivelesjerarquicos->setIdNivel($cCandidato->getIdNivel());
		$cNivelesjerarquicos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cNivelesjerarquicos = $cNivelesjerarquicosDB->readEntidad($cNivelesjerarquicos);
		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		
		@set_time_limit(0);
		ini_set("memory_limit","1024M");
		
		//$comboPREFIJOS	= new Combo($aux,"fIdPrefijo","idPrefijo","prefijo","Descripcion","prefijos","","","","","");
		$_NEWPAGE = '<!--NewPage-->';
		$_HEADER = '';
		$sHtmlCab	= '<table width="100%" border="0">
					<tr>
    					<td width="50%">
    						'.strtoupper($cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2()).'
    					</td>
    					<td width="50%" align="right">
    						'.date("d/m/Y").'
    					</td>
    				</tr>
    				
    		</table>';
		$sHtml		= '';
		$sHtmlFin	= '';
		//$aux			= $this->conn;
		
		//Recogemos los datos de la empresa gestora de la aplicación
//		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresa/EmpresaDB.php");
//		require_once(constant("DIR_FS_DOCUMENT_ROOT") . constant("DIR_WS_COM") . "Empresa/Empresa.php");
//		$cEntidadEmpresasDB	= new EmpresaDB($aux);  // Entidad DB
//		$cEntidadEmpresas	= new Empresa();  // Entidad
//		$cEntidadEmpresas->setIdEmpresa(1);
//		$cEntidadEmpresas = $cEntidadEmpresasDB->readEntidad($cEntidadEmpresas);
//		

		$sDirImg="imgContratos";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
		$sHtmlInicio='
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
				
					<style media="screen" type="text/css" >
						body{
							font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
							font-size: 12pt;
							font-weight: normal;
							color: #000000;
						}
					</style>
				</head>
			<body>';
	
	$sHtmlFin .='
	</body>
	</html>';
	
	//$sFechaCon = $this->convertir_fecha($cEntidadEmpresas->getFechaInscripcion());

	//$sFecha = explode(" " , $sFechaCon); 
	$sHtmlCab .='
	';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("3");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
		$sHtml.= '<br /><br /><br /><br /><br /><br /><br /><br />
			<table width="100%" border="0" cellspacing="20" cellpadding="20">
					<tr>
    					<td width="80%" align="center" height="350">
    						<font style="font-size: 42px;color: #004080;">' . $cTextos_secciones->getTexto() . '</font>
    					</td>
    				</tr>
					<tr>
    					<td width="80%" align="center">
    						<img src="'.constant('DIR_WS_GESTOR'). str_replace(".jpg" , "Informe.jpg",$cPruebas->getLogoPrueba()).'" width="450" />
    					</td>
    				</tr>  
    				<tr>';
		if ($cPruebas->getIdPrueba() == 26 || $cPruebas->getIdPrueba() == 16){
			//Para VIPS y NIPS
			$sHtml.= '
    					<td width="80%" align="center" height="200">
	    						<font style="font-size: 42px;color: #004080;">&nbsp;</font>
    					</td>
	    				';
		}else{
			//Para las pruebas de ESADE
			$sHtml.= '
	    					<td width="80%" align="center" height="200">
	    						<font style="font-size: 42px;color: #004080;">' . constant("STR_PRUEBAS_ADMISION_NIVEL_GRADO") . '</font>
	    					</td>
	    				';
		}
		$sHtml.= '
    				</tr><br /><br /><br />
    				<tr>
    					<td width="100%" align="center" height="300">
    						<table width="70%" style="border:2px solid #004080">
    							<tr>
    								<td style="padding:20px;height:200px;">
    									<table width="100%" cellpadding="10" cellspacing="10">
    										<tr>
    											<td width="100%">
    												<font style="font-size: 22px;color: #004080;font-weight: bold;">' . constant("STR_NOMBRE_APELLIDOS") . ':</font>  <font style="font-size: 22px;color: #000000;">'.strtoupper($cCandidato->getNombre(). ' ' . $cCandidato->getApellido1(). ' ' .$cCandidato->getApellido2()).'</font>
    											</td>
    										</tr>
    										<tr>
    											<td width="80%">
    												<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_FECHA_INFORME").':</font>  <font style="font-size: 22px;color: #000000;">'.date("d/m/Y").'</font>
    											</td>
    										</tr>
    									</table>
    								</td>
    							</tr>
    						</table>
    					</td>
    				</tr>    				
    		</table><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		$sHtml.='	'.$_NEWPAGE.$sHtmlCab.'<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%"><br /><br />';
						
    	//TITULO INTRODUCCIÓN
		$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">'.strtoupper(constant('STR_INTRODUCCION')).'</font>	
    							</p>';
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("1");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
    	// TEXTO INTRODUCCIÓN
    			
		$sHtml.=$cTextos_secciones->getTexto();

		$sHtml.='				<p align="justify" style="font-size:22px;">'.constant("STR_ESTE_INFORME_REPRESENTA").'</p>
								<p align="left">
									<ul>
										<li style="list-style:square;padding:20px;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PUNTUACION_DIRECTA").':</font><br /><br />
    										<font style="font-size: 22px;color: #000000;">'.constant("STR_EXP_PUNT_DIRECTA").'</font>
	    								</li>
	    								<li style="list-style:square;padding:20px;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").':</font><br /><br />
    										<font style="font-size: 22px;color: #000000;">'.constant("STR_EXP_PUNT_PECENTIL").'</font>
	    								</li>
	    								<li style="list-style:square;padding:20px;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").':</font><br /><br />
    										<font style="font-size: 22px;color: #000000;">'.constant("STR_EXP_INDICE_RAPIDEZ").'</font>
	    								</li>
	    								<li style="list-style:square;padding:20px;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_PRECISION").':</font><br /><br />
    										<font style="font-size: 22px;color: #000000;">'.constant("STR_EXP_INDICE_PRECISION").'</font>
	    								</li>
	    								<li style="list-style:square;padding:20px;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").':</font><br /><br />
    										<font style="font-size: 22px;color: #000000;">'.constant("STR_EXP_PRODUCTO_RENDIMIENTO").'</font>
	    								</li>
	    								<li style="list-style:square;padding:20px;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_ESTILO_PROCESAMIENTO_MENTAL").':</font><br /><br />
    										<font style="font-size: 22px;color: #000000;">'.constant("STR_EXP_ESTILO_PROCESAMIENTO_MENTAL").'</font>
	    								</li>
	    							</ul>
	    							<br />
	    							<br />
	    							<br /><br /><br />
								</p>';
		$sHtml.='			</td>
    					</tr>
    				</table>';

    	$sHtml.='	'.$_NEWPAGE.$sHtmlCab.'
    				<table width="100%" border="0">';
		$sHtml.='		<tr>
    						<td width="80%"><br /><br />';
						
		$IR = number_format($listaRespItems->recordCount() / $listaItemsPrueba->recordCount(),2);
		$sIR = str_replace("." , "," , $IR);
		$IP = number_format($iPDirecta/$listaRespItems->recordCount() ,2);
		$sIP = str_replace("." , "," , $IP);
		$POR = number_format($IR*$IP ,2);
		$sPOR = str_replace("." , "," , number_format($POR ,2));
		
		$cTextos_secciones = new Textos_secciones();
		$cTextos_secciones->setIdSeccion("2");
		$cTextos_secciones->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
		$cTextos_secciones->setIdPrueba($cPruebas->getIdPrueba());
		$cTextos_secciones->setIdTipoInforme($idTipoInforme);
		$cTextos_secciones = $cTextos_seccionesDB->readEntidad($cTextos_secciones); 
    	// TEXTO INTRODUCCIÓN
    			
		
		
    	$sHtml.='				<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">'.strtoupper(html_entity_decode($cTextos_secciones->getTexto(),ENT_QUOTES,"UTF-8")).'</font>	
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="60%" align="center">
    							<table cellspacing="30" cellpadding="15" border="0" width="70%">
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_TOTAL_PREGUNTAS").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'. $listaItemsPrueba->recordCount().'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_NUM_PREGUNTAS_ACERTADAS").'(P.D.)</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'.$iPDirecta.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PUNTUACION_PERCENTIL").'(P.C.)</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'.$iPercentil.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_RAPIDEZ").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'.$sIR.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_INDICE_PRECISION").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'.$sIP.'</font>
    									</td>
    								</tr>
    								<tr>
    									<td width="90%" style="border:3px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PRODUCTO_RENDIMIENTO").'</font>
    									</td>
    									<td width="10%" style="border:3px solid #004080;" align="center"><font style="font-size: 22px;color: #6e6e6e;font-weight: bold;">'.$sPOR.'</font>
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>';
    	$iWhidth = 1;
    	$sHtml.='		<tr>
    						<td width="80%" align="center">
    							<table width="90%" cellspacing="0" cellpadding="0" style="background:#c0c0c0;border-left:3px solid #004080;border-right:3px solid #004080;">
    								<tr>
    									<td width="19%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.constant("STR_PUNT_INFORMES").'</font>
    									<td>
    									<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.strtoupper(constant("STR_BAJO_POTENCIAL")).'</font>
    									<td>
    									<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.strtoupper(constant("STR_MEDIO_POTENCIAL")).'</font>
    									<td>
    									<td width="27%" align="center" style="height:60px;border:2px solid #004080;">
    										<font style="font-size: 22px;color: #004080;font-weight: bold;">'.strtoupper(constant("STR_ALTO_POTENCIAL")).'</font>
    									<td>
    								</tr>
    							</table>
    							<table width="90%" cellspacing="0" cellpadding="0" style="border-left:4px solid #004080;border-bottom:3px solid #004080;border-right:4px solid #004080;">
    								<tr>
    									<td width="19%" align="center" style="height:45px;border-right:2px solid #004080;">
    										<font style="font-size: 22px;color: #000000;font-weight: bold;">PD = '.$iPDirecta.'</font>
    									<td>
    									<td width="81%" style="height:45px;border-left:2px solid #004080;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'numeritosEstandar.jpg'.'" style="width:100%;">
    									<td>
    								</tr>
    								<tr>
    									<td width="19%" align="center" style="height:40px;border-right:2px solid #004080;">
    										<font style="font-size: 22px;color: #000000;font-weight: bold;">PC = '.$iPercentil.' %</font>
    									<td>
    									<td width="81%" style="height:40px;border-left:2px solid #004080;vertical-align:middle;">
    										<img src="'.constant('DIR_WS_GESTOR') . constant('DIR_WS_GRAF'). 'bodoque_gigante.jpg'.'" style="width:'.$iWhidth*$iPercentil.'%;height:35px;">	
    									<td>
    								</tr>
    							</table>
    						</td>
    					</tr>
    					<tr>
    						<td height="40">&nbsp;
    						</td>
    					</tr>
    	';
    	$sHtml.='		<tr>
    						<td>
    							<p style="text-align:center;background:#004080;height:25px;padding:5px;">
    								<font style="font-size: 21px;color:#FFFFFF;font-weight: bold;">'.strtoupper(constant("STR_ESTILO_PROCESAMIENTO_MENTAL")).'</font>	
    							</p>
    						</td>
    					</tr>
    					<tr>
    						<td width="95%">';
		//Miramos lo que tenemos que pintar de los textos segun los rangos.
		$cRangos_textos = new Rangos_textos();
		$cRangos_textos->setIdPrueba($cPruebas->getIdPrueba());
		$cRangos_textos->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    	$cRangos_textos->setIdTipoInforme($idTipoInforme);
		
    	$cRangos_ir = new Rangos_ir();
    	
    	$sqlRangosTextos = $cRangos_textosDB->readLista($cRangos_textos); 
//		echo "<br />" . $sqlRangosTextos;
		$listaRangosTextos = $conn->Execute($sqlRangosTextos);
		
		$caseIr = 0;
		
		$caseIp = 0;
		$bEncontrado=false;
		$bEncontradoIp=false;
		
		$idRango="";
		$idRangoIp="";
		
		if($listaRangosTextos->recordCount()>0)
		{
			while(!$listaRangosTextos->EOF)
			{
				if(!$bEncontrado)
				{
					$cRango_ir = new Rangos_ir();				
					
					$cRango_ir->setIdRangoIr($listaRangosTextos->fields['idIr']);
					$cRango_ir = $cRangos_irDB->readEntidad($cRango_ir);
					$aRangoSup = explode(" " , $cRango_ir->getRangoSup());
					$aRangoInf = explode(" " , $cRango_ir->getRangoInf());
					
					$sSignoSup = $aRangoSup[0];
					$sPuntSup = $aRangoSup[1];
	
					$sSignoInf = $aRangoInf[0];
					$sPuntInf = $aRangoInf[1];
					
					
//					if(($IR $sSignoSup $sPuntSup) && ($IR $sSignoInf $sPuntInf)){
//						echo $IR . " es :" . $sSignoSup . " que " . $sPuntSup . " y " . $sSignoInf . " que " . $sPuntInf;
//					}
//					echo "<br />sup: " . $sSignoSup . " inf: " . $sSignoInf;
//					echo "<br />Punt sup: " . $sPuntSup . " Punt inf: " . $sPuntInf;
//					echo "<br />IR: " . $IR ;
//					echo "<br />";
					
					$idRango="";
					
					switch ($sSignoSup)
					{
						case "<":
							switch ($sSignoInf)
							{
								case "<":
									if($IR < $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR < $sPuntSup && $IR <= $sPuntInf){
										echo $IR . " es : < que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR < $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR < $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : < que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}		
							break;
						case "<=":
							switch ($sSignoInf)
							{
								case "<":
									if($IR <= $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR <= $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR <= $sPuntSup && $IR > $sPuntInf){
//										echo $IR . " es : <= que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR <= $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : <= que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}		
							break;	
						case ">":
							switch ($sSignoInf)
							{
								case "<":
									if($IR > $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR > $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR > $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR > $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : > que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}		
							break;	
						case ">=":
							switch ($sSignoInf)
							{
								case "<":
									if($IR >= $sPuntSup && $IR < $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y < que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case "<=":
									if($IR >= $sPuntSup && $IR <= $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y <= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">":
									if($IR >= $sPuntSup && $IR > $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y > que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
								case ">=":
									if($IR >= $sPuntSup && $IR >= $sPuntInf){
										//echo $IR . " es : >= que " . $sPuntSup . " y >= que " . $sPuntInf . "<br />";
										$idRango = $cRango_ir->getIdRangoIr();
										$bEncontrado=true;
										break;
									}
							}		
							break;
					}
				}
//				echo "rango " . $idRango . "<br />";
				
				
				if(!$bEncontradoIp && !empty($idRango))
				{
					$cRango_ip = new Rangos_ip();				
					
					$cRango_ip->setIdRangoIr($idRango);
					$cRango_ip->setIdRangoIp($listaRangosTextos->fields['idIp']);
					$cRango_ip = $cRangos_ipDB->readEntidad($cRango_ip);
					$aRangoSupIp = explode(" " , $cRango_ip->getRangoSup());
					$aRangoInfIp = explode(" " , $cRango_ip->getRangoInf());
					
					$sSignoSupIp = $aRangoSupIp[0];
					$sPuntSupIp = $aRangoSupIp[1];
	
					$sSignoInfIp = $aRangoInfIp[0];
					$sPuntInfIp = $aRangoInfIp[1];
					//Ahora lo hacemos para El IP
					$idRangoIp="";
				
					switch ($sSignoSupIp)
					{
						case "<":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP < $sPuntSupIp && $IP < $sPuntInfIp){
										//echo $IP . " es : < que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP < $sPuntSupIp && $IP <= $sPuntInfIp){
										//echo $IP . " es : < que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP < $sPuntSupIp && $IP > $sPuntInfIp){
										//echo $IP . " es : < que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP < $sPuntSupIp && $IP >= $sPuntInfIp){
										//echo $IP . " es : < que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}		
							break;
						case "<=":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP <= $sPuntSupIp && $IP < $sPuntInfIp){
										//echo $IP . " es : <= que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP <= $sPuntSupIp && $IP <= $sPuntInfIp){
										//echo $IP . " es : <= que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP <= $sPuntSupIp && $IP > $sPuntInfIp){
										//echo $IP . " es : <= que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP <= $sPuntSupIp && $IP >= $sPuntInfIp){
										//echo $IP . " es : <= que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}		
							break;	
						case ">":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP > $sPuntSupIp && $IP < $sPuntInfIp){
										//echo $IP . " es : > que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP > $sPuntSupIp && $IP <= $sPuntInfIp){
										//echo $IP . " es : > que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP > $sPuntSupIp && $IP > $sPuntInfIp){
										//echo $IP . " es : > que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP > $sPuntSupIp && $IP >= $sPuntInfIp){
										//echo $IP . " es : > que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
							}		
							break;	
						case ">=":
							switch ($sSignoInfIp)
							{
								case "<":
									if($IP >= $sPuntSupIp && $IP < $sPuntInfIp){
										//echo $IP . " es : >= que " . $sPuntSupIp . " y < que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case "<=":
									if($IP >= $sPuntSupIp && $IP <= $sPuntInfIp){
										//echo $IP . " es : >= que " . $sPuntSupIp . " y <= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">":
									if($IP >= $sPuntSupIp && $IP > $sPuntInfIp){
										//echo $IP . " es : >= que " . $sPuntSupIp . " y > que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;
									}
								case ">=":
									if($IP >= $sPuntSupIp && $IP >= $sPuntInfIp){
										//echo $IP . " es : >= que " . $sPuntSupIp . " y >= que " . $sPuntInfIp . "<br />";
										$idRangoIp = $cRango_ip->getIdRangoIp();
										$bEncontradoIp=true;
										break;										
									}
							}		
							break;
					}
				}
				$listaRangosTextos->MoveNext();
			}
		}
		if (empty($idRango) || empty($idRangoIp)){
			echo "ERROR calculo de rangos. Contacte con el Administrador.";
			exit;
		} 
		$cRan_test = new Rangos_textos();
		$cRan_test->setIdPrueba($cPruebas->getIdPrueba());
		$cRan_test->setCodIdiomaIso2($_POST['fCodIdiomaIso2']);
    	$cRan_test->setIdTipoInforme($idTipoInforme);
    	$cRan_test->setIdIp($idRangoIp);
    	$cRan_test->setIdIr($idRango);
    	
    	$cRan_test = $cRangos_textosDB->readEntidad($cRan_test);
    	$sHtml .= $cRan_test->getTexto();

    	
	    $sHtml.='			</td>
    					</tr>
    			</table>';
	if (!empty($sHtml))
	{
		$replace = array('@', '.');
//		$sNombre = $cCandidato->getMail() . "_" . $_POST['fIdEmpresa']. "_" .$_POST['fIdProceso'] . "_" . $cPruebas->getNombre();
		$sDirImg="imgInformes/";
		$spath = (substr(constant("DIR_FS_DOCUMENT_ROOT_ADMIN"), -1, 1) != '/') ? constant("DIR_FS_DOCUMENT_ROOT_ADMIN") . '/' : constant("DIR_FS_DOCUMENT_ROOT_ADMIN");
		
		$_fichero = $spath . $sDirImg . $sNombre . ".html";
		//$cEntidad->chk_dir($spath . $sDirImg, 0777);
		
		if(is_file($_fichero)){
			unlink($_fichero);
		}
		error_log(utf8_decode($sHtmlInicio . $sHtml . $sHtmlFin), 3, $_fichero);
	}
//		error_reporting(E_ALL);
//		ini_set("display_errors","1");
		if (ini_get("pcre.backtrack_limit") < 2000000) { ini_set("pcre.backtrack_limit",2000000); };
		@set_time_limit(0);
		@define("OUTPUT_FILE_DIRECTORY", $spath . $sDirImg);
		require_once(constant('HTML2PS_DIR') . 'config.inc.php');
		require_once(constant('HTML2PS_DIR') . 'pipeline.factory.class.php');
		$g_baseurl = constant('DIR_WS_GESTOR') . $sDirImg . $sNombre . '.html';
		$GLOBALS['g_config'] = array(
                             'compress'      => '',
                             'cssmedia'      => 'Screen',
                             'debugbox'      => '',
                             'debugnoclip'   => '',
                             'draw_page_border'	=>   '',
                             'encoding'      => '',
                             'html2xhtml'    => 1,
                             'imagequality_workaround' => '',
                             'landscape'     => '',
                             'margins'       => array(
                                                      'left'    => 15,
                                                      'right'   => 15,
                                                      'top'     => 10,
                                                      'bottom'  => 10
                                                      ),
                             'media'         => 'A4',
                             'method'        => 'fpdf',
                             'mode'          => 'html',
                             'output'        => 2,
                             'pagewidth'     => 1024,
                             'pdfversion'    => '1.3',
                             'ps2pdf'        => '',
                             'pslevel'       => 3,
                             'renderfields'  => 1,
                             'renderforms'   => '',
                             'renderimages'  => 1,
                             'renderlinks'   => '',
                             'scalepoints'   => 1,
                             'smartpagebreak' => '',
                             'transparency_workaround' => ''
                             );
		parse_config_file(constant('HTML2PS_DIR') . 'html2ps.config');
		$g_media = Media::predefined($GLOBALS['g_config']['media']);
		$g_media->set_landscape($GLOBALS['g_config']['landscape']);
		$g_media->set_margins($GLOBALS['g_config']['margins']);
		$g_media->set_pixels($GLOBALS['g_config']['pagewidth']);
		
		$pipeline = new Pipeline;
		$pipeline->configure($GLOBALS['g_config']);
		// Configure the fetchers
		if (extension_loaded('curl')) {
			require_once(constant('HTML2PS_DIR') . 'fetcher.url.curl.class.php');
			$pipeline->fetchers = array(new FetcherUrlCurl());
		} else {
			require_once(constant('HTML2PS_DIR') . 'fetcher.url.class.php');
			$pipeline->fetchers[] = new FetcherURL();
		};
		
		// Configure the data filters
		$pipeline->data_filters[] = new DataFilterDoctype();
		$pipeline->data_filters[] = new DataFilterUTF8($GLOBALS['g_config']['encoding']);
		if ($GLOBALS['g_config']['html2xhtml']) {
			$pipeline->data_filters[] = new DataFilterHTML2XHTML();
		} else {
			$pipeline->data_filters[] = new DataFilterXHTML2XHTML();
		};
		
		$pipeline->parser = new ParserXHTML();
		
		// "PRE" tree filters
		
		$pipeline->pre_tree_filters = array();
		
		$header_html    = $_HEADER;
		$_NEWPAGE = '<!--NewPage-->';
		$footer_html    = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="100%" align="center"><p style="font-size:11px;"> '. strtoupper($cPruebas->getNombre()) .' '.constant("STR_PIE_INFORMES").'</p></td>
				</tr>
			</table>
			';
		//$footer_html = $footer_html;
		$filter = new PreTreeFilterHeaderFooter($header_html, $footer_html);
		$pipeline->pre_tree_filters[] = $filter;
		
		if ($GLOBALS['g_config']['renderfields']) {
			$pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
		};
		// 
		
		if ($GLOBALS['g_config']['method'] === 'ps') {
			$pipeline->layout_engine = new LayoutEnginePS();
		} else {
			$pipeline->layout_engine = new LayoutEngineDefault();
		};
		
		$pipeline->post_tree_filters = array();
		
		// Configure the output format
		if ($GLOBALS['g_config']['pslevel'] == 3) {
			$image_encoder = new PSL3ImageEncoderStream();
		} else {
			$image_encoder = new PSL2ImageEncoderStream();
		};
		
		switch ($GLOBALS['g_config']['method']) {
			case 'fastps':
				if ($GLOBALS['g_config']['pslevel'] == 3) {
					$pipeline->output_driver = new OutputDriverFastPS($image_encoder);
				} else {
					$pipeline->output_driver = new OutputDriverFastPSLevel2($image_encoder);
				};
				break;
			case 'pdflib':
				$pipeline->output_driver = new OutputDriverPDFLIB16($GLOBALS['g_config']['pdfversion']);
				break;
			case 'fpdf':
				$pipeline->output_driver = new OutputDriverFPDF();
				break;
			case 'png':
				$pipeline->output_driver = new OutputDriverPNG();
				break;
			case 'pcl':
				$pipeline->output_driver = new OutputDriverPCL();
				break;
			default:
				die("Unknown output method");
		};
		
		// Setup watermark
		$watermark_text = '';
		if ($watermark_text != '') {
			$pipeline->add_feature('watermark', array('text' => $watermark_text));
		};
		
		if ($GLOBALS['g_config']['debugbox']) {
			$pipeline->output_driver->set_debug_boxes(true);
		}
		
		if ($GLOBALS['g_config']['draw_page_border']) {
			$pipeline->output_driver->set_show_page_border(true);
		}
		
		if ($GLOBALS['g_config']['ps2pdf']) {
			$pipeline->output_filters[] = new OutputFilterPS2PDF($GLOBALS['g_config']['pdfversion']);
		}
		
		if ($GLOBALS['g_config']['compress'] && $GLOBALS['g_config']['method'] == 'fastps') {
			$pipeline->output_filters[] = new OutputFilterGZip();
		}
		$process_mode='';
		if ($process_mode == 'batch') {
			$filename = "batch";
		} else {
			$filename = $g_baseurl;
		};
		
		switch ($GLOBALS['g_config']['output']) {
			case 0:
				$pipeline->destination = new DestinationBrowser($filename);
				break;
			case 1:
				$pipeline->destination = new DestinationDownload($filename);
				break;
			case 2:
				$pipeline->destination = new DestinationFile($filename, '');
				$pipeline->destination->set_filename($sNombre);
				break;
		};
		
		// Add additional requested features
		$toc_location = '';	//after o before
		if (!empty($toc_location)) {
			$pipeline->add_feature('toc', array('location' => $toc_location));
		}
		$automargins = '';
		if (!empty($automargins)) {
			$pipeline->add_feature('automargins', array());
		};
		
		// Start the conversion
		
		$time = time();
		if ($process_mode == 'batch') {
			$batch = array();
		
			for ($i=0; $i<count($batch); $i++) {
				if (trim($batch[$i]) != "") {
					if (!preg_match("/^https?:/",$batch[$i])) {
						$batch[$i] = "http://".$batch[$i];
					}
				};
			};
		
			$status = $pipeline->process_batch($batch, $g_media);
		} else {
			$status = $pipeline->process($g_baseurl, $g_media);
		};
		
		error_log(sprintf("Processing of '%s' completed in %u seconds", $g_baseurl, time() - $time), 3, constant("DIR_FS_PATH_NAME_LOG"));
		
		if ($status == null) {
			print($pipeline->error_message());
			error_log("Error in conversion pipeline", 3, constant("DIR_FS_PATH_NAME_LOG"));
			die();
		}else{
			//$cEntidad->setPdf($sDirImg . $sNombre . '.pdf');
			//$this->modificarPDF($cEntidad);
		}
?>