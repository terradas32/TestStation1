<?php 
function convertir_fecha($fecha_datetime){
	//Esta función convierte la fecha del formato DATETIME de SQL a formato DD-MM-YYYY HH:mm:ss
	$fecha = explode("-",$fecha_datetime);
	$hora = explode(":",$fecha[2]);
	$fecha_hora = explode(" ",$hora[0]);
	$fecha_convertida = $fecha_hora[0].'/'.$fecha[1].'/'.$fecha[0].' '.$fecha_hora[1].':'.$hora[1].':'.$hora[2];
	return $fecha_convertida;
}

require_once(constant("DIR_WS_COM") . 'Idiomas/Idiomas.php');
require_once(constant("DIR_WS_COM") . 'Idiomas/IdiomasDB.php');
$cIdiomasDB = new IdiomasDB($conn);
$cIdiomas = new Idiomas();
$cIdiomas->setActivoBack(1);
$cIdiomas->setOrder("ASC");
$cIdiomas->setOrderBy("orden");
$sqlIdiomas = $cIdiomasDB->readLista($cIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);
?>

<div id="cabecera">			
		<div id="cabecera-envoltura">
			<div id="cabecera-contenido">
				<div id="cabecera-contenido-superior">	
				<div id="cabecera-nombre">
					<?php echo constant("NOMBRE_SITE");?>
				</div>			
				<div id="cabecera-botones">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
            			<tr>
                    <td onclick="javascript:cambiaImg(0,2);" onmouseover ="javascript:cambiaImg(0,1)" onmouseout ="javascript:cambiaImg(0,0)"><a href="#" onclick="javascript:block('-1');setClicado('Z');setTitulo('<?php echo constant("NOMBRE_SITE");?>');enviarMenu('bienvenida.php', '<?php echo constant("MNT_BUSCAR");?>');" ><img src="<?php echo constant("DIR_WS_GRAF");?>btn_inicio_off.png" name="b0" title="<?php echo constant("STR_INICIO");?>" alt="<?php echo constant("STR_INICIO");?>" border="0" /></a></td>
            				<td onclick="javascript:cambiaImg(1,2);" onmouseover ="javascript:cambiaImg(10,1)" onmouseout ="javascript:cambiaImg(10,0)"><a href="#" onclick="javascript:block('-1');setClicado('Z');setTitulo('<?php echo constant("STR_MIS_DATOS");?>');enviarMenu('Usuario.php', '<?php echo constant("MNT_BUSCAR");?>');" ><img src="<?php echo constant("DIR_WS_GRAF");?>btn_acceso_off.png" name="b10" title="<?php echo constant("STR_MIS_DATOS");?>" alt="<?php echo constant("STR_MIS_DATOS");?>" border="0" /></a></td>
            				<td onclick="javascript:cambiaImg(11,2);" onmouseover ="javascript:cambiaImg(11,1)" onmouseout ="javascript:cambiaImg(11,0)"><a href="#" onclick="javascript:enviarMenu('index.php', '<?php echo constant("MNT_BUSCAR");?>');" ><img src="<?php echo constant("DIR_WS_GRAF");?>btn_salir_off.png" name="b11" title="<?php echo constant("STR_SALIR");?>" alt="<?php echo constant("STR_SALIR");?>" border="0" /></a></td>
            			</tr>
            		</table>			
				</div>	
				<div onmouseover="javascript:document.getElementById('idiomas').style.overflow='visible';" onmouseout="javascript:document.getElementById('idiomas').style.overflow='hidden';" id="idiomas"><?php echo constant("STR_IDIOMAS");?><br />       
  					<table cellpadding="0" cellspacing="5">
  						<?php while(!$listaIdiomas->EOF){?>
  						<tr>
  							<td><a class="<?php if($sLang==$listaIdiomas->fields['codIdiomaIso2']){echo "enlacesidiomasdentro";}else{echo "enlacesidiomas";}?>" href="#" onclick="javascript:if (document.forms[0].ORIGEN != undefined){document.forms[0].MODO.value=document.forms[0].ORIGEN.value;}document.forms[0].fLang.value='<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>';document.forms[0].submit();"><?php echo $listaIdiomas->fields['nombre'];?></a></td>
  						</tr>
  						<?php $listaIdiomas->MoveNext();}?>
  						</table>      				
				</div>
				

				<div id="cabecera-menu-seleccionado">
					<div id="TituloSup"><?php echo (!empty($_POST["_TituloOpcion"])) ? $_POST["_TituloOpcion"] : constant("NOMBRE_SITE");?></div></td>
				</div>							
				</div>
				<div id="cabecera-contenido-inferior">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr height="10px">	            		    
			            	<td colspan="2" nowrap="nowrap" class="negrob"><?php echo constant("STR_CANDIDATO");?>:&nbsp;<font class="blancob"><?php echo $cCandidato->getNombre();?>&nbsp;<?php echo $cCandidato->getApellido1();?>&nbsp;<?php echo $cCandidato->getApellido2();?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><?php echo constant("STR_MAIL");?>:&nbsp;<font class="blancob"><?php echo $cCandidato->getMail();?></font></td>
							
	                        <td nowrap="nowrap" align="right" valign="bottom" class="negrob"></td>
	            		</tr>
					</table>	
				</div>
			</div>
		</div>
		<div id="cabecera-izquierda">
			<div id="cabecera-logo">
				<!-- <img height="70" width="208px" alt="<?php echo constant("NOMBRE_EMPRESA");?>" src="<?php echo constant("DIR_WS_GRAF");?>logo.png" border="0" /> -->
				<img height="70" alt="People Experts" src="https://test-station.com/Admin/graf/logo.jpg" border="0" />
			</div>
		</div>
		<div id="cabecera-derecha">
			
		</div>
	</div>