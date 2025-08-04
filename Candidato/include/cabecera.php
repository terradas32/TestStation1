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
require_once(constant("DIR_WS_COM") . 'Empresas/Empresas.php');
require_once(constant("DIR_WS_COM") . 'Empresas/EmpresasDB.php');
$cEmpresasDB = new EmpresasDB($conn);
$cEmpresas = new Empresas();
$cEmpresas->setIdEmpresa($_cEntidadCandidatoTK->getIdEmpresa());
$cEmpresas = $cEmpresasDB->readEntidad($cEmpresas);
$sLogo = '<img alt="' . constant("NOMBRE_EMPRESA") . '" src="' . constant("DIR_WS_GRAF") . 'logo.png" border="0" />';
if ($cEmpresas->getPathLogo() != ""){
	$altura = 85;
	$size = @getimagesize(constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo());
	if($size){
		$anchura=$size[0];
		$altura=$size[1];
	}
	if ($altura > 85){
		$altura = 85;
	}
		$altura.="px";
		$sLogo = '<img title="' . $cEmpresas->getNombre() . '" alt="' . $cEmpresas->getNombre() . '" src="' . constant("DIR_WS_GESTOR") . $cEmpresas->getPathLogo() . '" height="' . $altura . '" />';
}else{
	$sLogo = $cEmpresas->getNombre();
}
?>

<div id="cabecera">
		<div id="cabecera-envoltura">
			<div id="cabecera-contenido">
				<div id="cabecera-contenido-superior">
				<div id="cabecera-nombre">
					<?php echo constant("NOMBRE_SITE");?>
				</div>
            	<div id="cabecera-botones">
					<ul>
            		<li class="salir"><a href="<?php echo constant("HTTP_SERVER_FRONT");?>index.php?fLang=<?php echo $sLang;?>" title="<?php echo constant("STR_SALIR");?>"><?php echo constant("STR_SALIR");?></a></li>
            		</ul>
				</div>

				<div id="cabecera-menu-seleccionado">
					<div id="TituloSup"><?php echo (!empty($_POST["_TituloOpcion"])) ? $_POST["_TituloOpcion"] : constant("NOMBRE_SITE");?></div>
				</div>
				</div>
				<div id="cabecera-contenido-inferior">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr >
			            	<td colspan="2" nowrap="nowrap" class="blanco"><?php echo constant("STR_CANDIDATO");?>:&nbsp;<font class="blancob"><?php echo $cCandidato->getNombre();?>&nbsp;<?php echo $cCandidato->getApellido1();?>&nbsp;<?php echo $cCandidato->getApellido2();?></font>&nbsp;&nbsp;&nbsp;&nbsp;
			            	<?php
			            	if ($cEmpresas->getVerCorreo() == ""){
			            		echo constant("STR_MAIL");?>:&nbsp;<font class="blancob"><?php echo $cCandidato->getMail();
			            	}
			            	?></font>

			            	</td>
							<td nowrap="nowrap" align="right" class="blanco" style="display:none;"><?php echo constant("STR_ULTIMA_CONEXION");?>:&nbsp;<font class="blancob"><?php echo (is_null($cCandidato->getUltimoLogin())) ? constant("STR_NUNCA") : convertir_fecha($cCandidato->getUltimoLogin());?></font></td>
	                        <td nowrap="nowrap" align="right" valign="bottom" class="blanco"></td>
	            		</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="cabecera-izquierda">
			<div id="cabecera-logo">
			<h2>
				<?php
					if ($cProcesos->getProcesoConfidencial() != "1"){
						echo $sLogo;
					}
				?>
			</h2>
			</div>
		</div>
		<div id="cabecera-derecha">

		</div>
	</div>
