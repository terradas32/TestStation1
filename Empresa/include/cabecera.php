<?php
require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');

require_once(constant("DIR_WS_COM") . 'Idiomas/Idiomas.php');
require_once(constant("DIR_WS_COM") . 'Idiomas/IdiomasDB.php');
$cIdiomasDB = new IdiomasDB($conn);
$cIdiomas = new Idiomas();
$cIdiomas->setActivoBack(1);
$cIdiomas->setOrder("ASC");
$cIdiomas->setOrderBy("orden");
$sqlIdiomas = $cIdiomasDB->readLista($cIdiomas);
$listaIdiomas = $conn->Execute($sqlIdiomas);
$sHijos = "";

if (empty($_POST["fHijos"]))
{
	$cEmpresaPadre = new Empresas();
	$cEmpresaPadreDB = new EmpresasDB($conn);
	$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
//	$_EmpresaLogada = constant("EMPRESA_PE");
	$sHijos = $cEmpresaPadreDB->getHijos($_EmpresaLogada);
	if (!empty($sHijos)){
		$sHijos .= $_EmpresaLogada;
	}else{
		$sHijos = $_EmpresaLogada;
	}
}else{
	$sHijos = $_POST["fHijos"];
}
//	echo $sHijos;exit;
	$_Prepago="";
	$_PathLogo="";
	$_Dongles="";
	$_Timezone="";
	$_dataUSR="Usuario.php";
	if (get_class($_cEntidadUsuarioTK) == "Empresas"){
		$_Timezone = $_cEntidadUsuarioTK->getTimezone();
		$_Prepago=$_cEntidadUsuarioTK->getPrepago();
		$_PathLogo=$_cEntidadUsuarioTK->getPathLogo();
		$_Dongles=$_cEntidadUsuarioTK->getDongles();
	}else{
		$_dataUSR="Empresa_usuario.php";
		$cEmpr = new Empresas();
		$cEmprDB = new EmpresasDB($conn);
		$_EmpresaLogada = $_cEntidadUsuarioTK->getIdEmpresa();
		$cEmpr->setIdEmpresa($_EmpresaLogada);
		$cEmpr = $cEmprDB->readEntidad($cEmpr);
		$_Timezone = $cEmpr->getTimezone();
		$_Prepago=$cEmpr->getPrepago();
		$_PathLogo=$cEmpr->getPathLogo();
		$_Dongles=$cEmpr->getDongles();
	}
if (!function_exists('convertir_fecha')) {
	function convertir_fecha($fecha_datetime){
		global $_Timezone;
		//Esta función convierte la fecha del formato DATETIME de SQL a formato DD-MM-YYYY HH:mm:ss
		$dt=new datetime($fecha_datetime,new datetimezone($_Timezone));
		$fecha_datetime = gmdate("Y-m-d H:i:s",(time()+$dt->getOffset()));	//Fecha actual de la Zona horaria
		$fecha = explode("-",$fecha_datetime);
		$hora = explode(":",$fecha[2]);
		$fecha_hora = explode(" ",$hora[0]);
		$fecha_convertida = $fecha_hora[0].'/'.$fecha[1].'/'.$fecha[0].' '.$fecha_hora[1].':'.$hora[1].':'.$hora[2];
		return $fecha_convertida . ' <span class="blancob dataUsr">(' . $_Timezone . ') </span>';
	}
}
?>
<input type="hidden" name="fHijos" value="<?php echo $sHijos;?>" />
<div id="cabecera">
		<div id="cabecera-envoltura">
			<div id="cabecera-contenido">
				<div id="cabecera-contenido-superior">
				<div id="cabecera-nombre">
					<?php echo constant("NOMBRE_SITE");?>
				</div>
				<div style="display: none;" onmouseover="javascript:document.getElementById('idiomas').style.overflow='visible';document.getElementById('tituloComboIdiomas').style.display='none';" onmouseout="javascript:document.getElementById('idiomas').style.overflow='hidden';document.getElementById('tituloComboIdiomas').style.display='block';" id="idiomas">
                	<p id="tituloComboIdiomas"><?php echo constant("STR_IDIOMAS");?></p>
  					<ul>
  						<?php while(!$listaIdiomas->EOF){?>
  						<li>
  							<a class="<?php if($sLang==$listaIdiomas->fields['codIdiomaIso2']){echo "enlacesidiomasdentro";}else{echo "enlacesidiomas";}?>" href="#" onclick="javascript:if (document.forms[0].ORIGEN != undefined){document.forms[0].MODO.value=document.forms[0].ORIGEN.value;}document.forms[0].fLang.value='<?php echo $listaIdiomas->fields['codIdiomaIso2'];?>';document.forms[0].submit();"><?php echo $listaIdiomas->fields['nombre'];?></a>
  						</li>
  						<?php $listaIdiomas->MoveNext();}?>
  					</ul>
				</div>
                <div id="cabecera-botones">
					<ul>
            		<li class="inicio"><a href="#" onclick="javascript:block('-1');setClicado('Z');setTitulo('<?php echo constant("NOMBRE_SITE");?>');enviarMenu('bienvenida.php', '<?php echo constant("MNT_BUSCAR");?>');" title="<?php echo constant("STR_INICIO");?>" >&nbsp;</a></li>
            		<li class="misDatos"><a href="#" onclick="javascript:block('-1');setClicado('Z');setTitulo('<?php echo constant("STR_MIS_DATOS");?>');enviarMenu('<?php echo $_dataUSR;?>', '<?php echo constant("MNT_BUSCAR");?>');" title="<?php echo constant("STR_MIS_DATOS");?>">&nbsp;</a></li>
            		<li class="salir"><a href="#" onclick="javascript:enviarMenu('index.php', '<?php echo constant("MNT_BUSCAR");?>');" title="<?php echo constant("STR_SALIR");?>">&nbsp;</a></li>
            		</ul>
				</div>
			    <div id="cabecera-menu-seleccionado">
					<div id="TituloSup"><h1><?php echo (!empty($_POST["_TituloOpcion"])) ? $_POST["_TituloOpcion"] : constant("NOMBRE_SITE");?></h1></div>
				</div>
				</div>
				<div id="cabecera-contenido-inferior">
				<?php
				//Si es por contrato, no mostramos los dongles
				$sDisplay = "";
				if ($_Prepago == "0"){
					$sDisplay = "display:none;";
				}
				?>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr style="height:10px">
	            	<td colspan="2" nowrap="nowrap" class="blancob dataUsr"><?php echo constant("STR_USUARIO");?>:&nbsp;<font class="grisitob dataUsr"><?php echo $_cEntidadUsuarioTK->getUsuario();?></font>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo constant("STR_NOMBRE");?>:&nbsp;<font class="grisitob dataUsr"><?php echo substr($_cEntidadUsuarioTK->getNombre(),0,35);?></font>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (empty($sDisplay)) ? constant("STR_DONGLES") . ":" : "";?>&nbsp;<font class="grisitob dataUsr" style="<?php echo $sDisplay;?>"><?php echo $_Dongles;?></font></td>
								<td nowrap="nowrap" align="right" class="blancob dataUsr"><?php echo constant("STR_ULTIMA_CONEXION");?>:&nbsp;<font class="grisitob dataUsr"><?php echo (is_null($_cEntidadUsuarioTK->getUltimoLogin())) ? constant("STR_NUNCA") : convertir_fecha($_cEntidadUsuarioTK->getUltimoLogin());?></font></td>
                <td nowrap="nowrap" align="right" valign="bottom dataUsr" class="blancob dataUsr"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>



		<div id="cabecera-izquierda">
			<div id="cabecera-logo">
           <h2><?php if ($_PathLogo != ""){
						 $altura = 85;
           		$size = list($anchura, $altura) = @getimagesize(constant("DIR_WS_GESTOR") . $_PathLogo);
							if($size){
							$anchura=$size[0];
							$altura=$size[1];
						}
				if ($altura > 85){
					$altura = 85;
				}
				$altura.="px";
				echo '<img title="' . $_cEntidadUsuarioTK->getNombre() . '" alt="' . $_cEntidadUsuarioTK->getNombre() . '" src="' . constant("DIR_WS_GESTOR") . $_PathLogo . '" height="' . $altura . '" />';
			}else{
				echo $_cEntidadUsuarioTK->getNombre();
			}?>
			</h2>
			</div>

		</div>
		<div id="cabecera-derecha">

		</div>
	</div>
